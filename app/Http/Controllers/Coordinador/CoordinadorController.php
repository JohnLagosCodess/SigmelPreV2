<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//llamado de modelos para formulario BandejaPCL y captura de data
use App\Models\sigmel_lista_procesos_servicios;
use App\Models\cndatos_bandeja_eventos;
use App\Models\cndatos_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_acciones;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\User;
use Illuminate\Support\Arr;

class CoordinadorController extends Controller
{
    public function show(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('coordinador.index', compact('user'));
    }

    // Bandeja PCL Coordinador
    public function mostrarVistaBandejaPCL(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();        

        return view('coordinador.bandejaPCL', compact('user'));
    }
    
    // Cargar selectores de Bandeja PCL
    public function cargueListadoSelectoresBandejaPCL(Request $request){
        $parametro = $request->parametro;
        
        // listado de procesos que almenos tienen configurado una paramétrica
        if($parametro == 'listado_procesos_parametrizados'){
            $listado_procesos_parametrizados = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_lista_procesos_servicios as slps')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_parametrizaciones_clientes as sipc', 'slps.Id_proceso', '=', 'sipc.Id_proceso')
            ->select('slps.Id_proceso', 'slps.Nombre_proceso')
            ->whereNotNull('sipc.Id_proceso')
            ->groupBy('slps.Id_proceso')->get();

            $info_listado_procesos_parametrizados = json_decode(json_encode($listado_procesos_parametrizados, true));
            return response()->json($info_listado_procesos_parametrizados);
        }

        //Listado servicio calificacion PCL
        if($parametro == 'lista_servicios_pcl'){
            $listado_servicio_PCl = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                // ['Nombre_proceso', '=', 'Calificación PCL'],
                ['Id_proceso', '=', $request->id_proceso],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_PCl = json_decode(json_encode($listado_servicio_PCl, true));
            return response()->json($info_listado_servicio_PCl);
        }

        // listado de acciones
        if ($parametro == 'listado_accion') {
            // $array_Id_asignacion = $request->Id_asignacion;

            /* Iniciamos trayendo las acciones a ejecutar configuradas en la tabla de parametrizaciones
            dependiendo del id del cliente, id del proceso, id del servicio, estado activo */
            
            // $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            // ->select('Cliente')->where('ID_evento', $request->nro_evento)->first();

            // $id_cliente = $array_id_cliente["Cliente"];

            $acciones_a_ejecutar = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Accion_ejecutar')
            ->where([
                // ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $request->Id_proceso],
                ['sipc.Servicio_asociado', '=', $request->Id_servicio],
                ['sipc.Bandeja_trabajo', '=', 'Si'],
                ['sipc.Status_parametrico', '=', 'Activo']
            ])->get();

            $info_acciones_a_ejecutar = json_decode(json_encode($acciones_a_ejecutar, true));
            // echo "<pre>"; print_r($info_acciones_a_ejecutar); echo "</pre>";
            if (count($info_acciones_a_ejecutar) > 0) {
                // Extraemos las acciones antecesoras a partir de las acciones a ejecutar
                $array_acciones_ejecutar = [];
                for ($i=0; $i < count($info_acciones_a_ejecutar); $i++) { 
                    array_push($array_acciones_ejecutar, $info_acciones_a_ejecutar[$i]->Accion_ejecutar);
                };

                $extraccion_acciones_antecesoras = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
                ->select('Accion_ejecutar','Accion_antecesora')
                ->where([
                    // ['Id_cliente', '=', $id_cliente],
                    ['Id_proceso', '=', $request->Id_proceso],
                    ['Servicio_asociado', '=', $request->Id_servicio],
                ])
                ->whereIn('Accion_ejecutar', $array_acciones_ejecutar)
                ->get();
                
                $info_extraccion_acciones_antecesoras = json_decode(json_encode($extraccion_acciones_antecesoras, true));
                
                // En caso de que almenos exista una acción antecesora, se debe analizar si esta acción 
                // (que depende de una acción ejecutar) está en la tabla de auditorias de asignacion de eventos dependiendo
                // del id del proceso y el id del servicio. El id de la accion a ejecutar estaría dentro de las opciones a mostrar solo si se encuentra el id
                // de la accion antecesora en dicha tabla
                if (count($info_extraccion_acciones_antecesoras) > 0) {
                    
                    foreach ($info_extraccion_acciones_antecesoras as $key => $value) {
                        if ($info_extraccion_acciones_antecesoras[$key]->Accion_antecesora !== null) {
                            $busqueda_accion_antecesora = DB::table(getDatabaseName('sigmel_auditorias') .'sigmel_auditorias_informacion_asignacion_eventos as saiae')
                            ->select('saiae.Aud_Id_accion')
                            ->where([
                                // ['saiae.Aud_Id_Asignacion', '=', $request->Id_asignacion],
                                // ['saiae.Aud_ID_evento', '=', $request->nro_evento],
                                ['saiae.Aud_Id_proceso', '=', $request->Id_proceso],
                                ['saiae.Aud_Id_servicio', '=', $request->Id_servicio],
                                ['saiae.Aud_Id_accion', $info_extraccion_acciones_antecesoras[$key]->Accion_antecesora]
                            ])
                            ->get();

                            // Si no existe en la tabla debe eliminar la información de la acción a ejecutar ya que esta no se debe mostrar.
                            if (count($busqueda_accion_antecesora) == 0) {
                                unset($info_extraccion_acciones_antecesoras[$key]);
                            }
                        }
                    }
                    
                    $info_extraccion_acciones_antecesoras = array_values($info_extraccion_acciones_antecesoras);
                    
                    /* echo "<pre>";
                    print_r($info_extraccion_acciones_antecesoras);
                    echo "</pre>"; */

                    // Extraemos los id de las acciones a ejecutar para buscarlas en la tabla sigmel_informacion_acciones;
                    $array_listado_acciones = [];
                    for ($a=0; $a < count($info_extraccion_acciones_antecesoras); $a++) { 
                        array_push($array_listado_acciones, $info_extraccion_acciones_antecesoras[$a]->Accion_ejecutar);
                    }

                    // print_r($array_listado_acciones);
                    $listado_acciones = sigmel_informacion_acciones::on('sigmel_gestiones')
                    ->select('Id_Accion', 'Accion as Nombre_accion')
                    ->where([
                        ['Status_accion', '=', 'Activo']
                    ])
                    ->whereIn('Id_Accion', $array_listado_acciones)
                    ->get();

                    $info_listado_acciones_nuevo_servicio = json_decode(json_encode($listado_acciones, true));
                    return response()->json(($info_listado_acciones_nuevo_servicio));
                }
            }

        }        

        // listado de profesionales para el proceso pcl
        if ($parametro == 'lista_profesional_pcl') {
            
            $listado_profesional_pcl = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(2, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_PCL = json_decode(json_encode($listado_profesional_pcl, true));
            return response()->json($info_listado_profesional_PCL);
        }
        
    }

    public function sinFiltroBandejaPCL(Request $request){

        $BandejaPClTotal = $request->BandejaPClTotal;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user;             

        if($BandejaPClTotal == 'CargaBandejaPCl'){
            // Consultar la vista de mysql, traer eventos acorde al proceso
            if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'){ // si el rol es analista o profesional o comité
                $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where([
                    ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                    ['Id_profesional', '=', $newId_user]
                ])
                ->get();  
            }else{
                $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where([
                    ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                ])
                ->get();  
            }
            // $ID_evento_bandeja = $bandejaPCL[0]->ID_evento;
            // $Id_proceso_bandeja = $bandejaPCL[0]->Id_proceso;
            
            // // Json vacio para llenado en Else en caso de que no haya un proceso anterior
            // $Ids_Nombre_proceso_anterior = response()->json([]);
            
            // // validacion de la vista
            // if (!empty($bandejaPCL[0]->Nombre_proceso_actual)) {
            //     $datos_bandejaPCl = [];
            //     foreach ($bandejaPCL as $item) {
            //         // Accede a cada propiedad del objeto dentro del bucle  para capturar el id_asignacion                  
            //         $datos_bandejaPCl[]=[                        
            //             'Id_Asignacion_bandeja_actual' => $item->Id_Asignacion,
            //         ];
            //     }      
                
            //     // cantidad de id_asignacion 
            //     $cantidad_Id_Asignacion = count($datos_bandejaPCl);

            //     // Validar si existe un porceso anterior con los Id_Asignacion del array $datos_bandejaPCl
            //     if ($cantidad_Id_Asignacion > 0) {
            //         //$cantidad_Id_Asignacion += 1; 
            //         $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            //         ->select('Id_proceso', 'Id_Asignacion')
            //         ->where('ID_evento', $ID_evento_bandeja);
            //         $maxIdAsignacionBandeja = max(array_column($datos_bandejaPCl, 'Id_Asignacion_bandeja_actual'));
            //         $validar_proceso_anterior = $validar_proceso_anterior
            //         ->where('Id_Asignacion', '<', $maxIdAsignacionBandeja)
            //         ->orderBy('Id_Asignacion', 'desc')
            //         ->limit($cantidad_Id_Asignacion)
            //         ->get();                   
            //         // foreach ($datos_bandejaPCl as $dato) {
            //         //     $validar_proceso_anterior->orWhere(function ($query) use ($dato) {
            //         //         $query->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja_actual']);
            //         //     });
            //         // }
            //         // $validar_proceso_anterior = $validar_proceso_anterior
            //         // ->orderBy('Id_Asignacion', 'desc')
            //         // ->limit($cantidad_Id_Asignacion)
            //         // ->get();  
                    
            //         if (count($validar_proceso_anterior) > 0) {
                        
            //             // Se construyen los id del proceso y asignacion
            //             $proceso_id = [];
            //             $asignacion_id = [];
            //             foreach ($validar_proceso_anterior as $key) {
            //                 $proceso_id[]=[                        
            //                     'Id_Proceso_anterior' => $key->Id_proceso
            //                 ];    
            //                 $asignacion_id[] = [
            //                     'Id_Asignacion_bandeja' => $key->Id_Asignacion
            //                 ];
            //             }  
                        
            //             // Validar el nombre del proceso anterior en base a los id_procesos optenidos en el array  $proceso_id
            //             $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //             ->select('Nombre_proceso', 'Id_proceso')->whereIn('Id_proceso', $proceso_id)
            //             ->groupBy('Nombre_proceso')
            //             ->get();  
            //             // construir array para los nombres de los procesos
            //             $nombre_proceso = [];
            //             foreach ($validar_Nombre_proceso_anterior as $value) {
            //                 $nombre_proceso[] = [
            //                     'Nombre_proceso_anterior' => $value->Nombre_proceso,
            //                     'Id_proceso' => $value->Id_proceso,
            //                 ];
            //             }
                       
            //             // acondicionar los array acorde al order la consulta Inicial de $bandejaPCL
            //             //array_shift($proceso_id);                                            
            //             $orden_proceso_id = array_reverse($proceso_id);
            //             //array_pop($asignacion_id);                        
            //             $orden_asignacion_id = array_reverse($asignacion_id);
                        
            //             //Combinar los array de  orden_proceso_id y nombre_proceso acorde al proceso
            //             foreach ($orden_proceso_id as $key => $value) {
            //                 foreach ($nombre_proceso as $item) {
            //                     if ($value['Id_Proceso_anterior'] == $item['Id_proceso']) {
            //                         $orden_proceso_id[$key]['Nombre_proceso_anterior'] = $item['Nombre_proceso_anterior'];
            //                         break;
            //                     }
            //                 }
            //             }
                        
            //             // combinar array anterior orden_proceso_id con el array orden_asignacion_id 
            //             $combinar_proceso_asignacion = array();
            //             foreach ($orden_asignacion_id as $key => $valor) {
            //                 if (isset($orden_proceso_id[$key])) {
            //                     // Fusionar arrays
            //                     $combinar_proceso_asignacion[] = array_merge($orden_proceso_id[$key], $valor);
            //                 }
            //             }                   
                        
            //             if (count($combinar_proceso_asignacion) === count($datos_bandejaPCl)) {
            //                 $numElementos = count($combinar_proceso_asignacion);
                        
            //                 for ($i = 0; $i < $numElementos; $i++) {
            //                     // Combinar los sub-arrays uno a uno
            //                     $array_datos_proce_asignacion[] = array_merge($combinar_proceso_asignacion[$i], $datos_bandejaPCl[$i]);
            //                 }
            //             }
                        
            //             // se convierte el array  $bandejaPCL a un object
            //             $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));
                       
            //             //Combinar el array object con el array combinar_proceso_asignacion
            //             foreach ($arraybandejaPCL as $key2 => $value2) {
            //                 foreach ($array_datos_proce_asignacion as $value1) {
            //                     // Verifica si los Id_Asignacion coinciden
            //                     if ($value2->Id_Asignacion == $value1['Id_Asignacion_bandeja_actual']) {
            //                         // Agrega las propiedades al segundo array
            //                         $arraybandejaPCL[$key2]->Id_Proceso_anterior = $value1['Id_Proceso_anterior'];
            //                         $arraybandejaPCL[$key2]->Nombre_proceso_anterior = $value1['Nombre_proceso_anterior'];
            //                     }
            //                 }
            //             }
            //         } 
            //         // else {    
            //         //     // Validar el nombre del proceso que viene siendo el mismo ya que no hay uno anterior                
            //         //     $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         //     ->select('Nombre_proceso')->where([['Id_proceso', $Id_proceso_bandeja]])
            //         //     ->limit(1)->get(); 
    
            //         //     // Se alimenta el Json Vacio
            //         //     $Ids_Nombre_proceso_anterior = response()->json([
            //         //         'Id_Proceso_anterior' => $Id_proceso_bandeja,
            //         //         'Nombre_proceso_anterior' => $validar_Nombre_proceso_anterior[0]->Nombre_proceso,
            //         //     ]);
            //         //     // Se Captura los valosres del json y se agregan al object $arraybandejaPCL
            //         //     $Ids_Nombre_proceso_anterior_array = json_decode($Ids_Nombre_proceso_anterior->getContent(), true);
            //         //     $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));
            //         //     $arraybandejaPCL[0]->Id_Proceso_anterior = $Ids_Nombre_proceso_anterior_array['Id_Proceso_anterior'];
            //         //     $arraybandejaPCL[0]->Nombre_proceso_anterior = $Ids_Nombre_proceso_anterior_array['Nombre_proceso_anterior'];
            //         // }
                    
            //     } 
            //     // else {
            //     //     $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            //     //     ->select('Id_proceso', 'Id_Asignacion')
            //     //     ->where('ID_evento', $ID_evento_bandeja);
            //     //     foreach ($datos_bandejaPCl as $dato) {
            //     //         $validar_proceso_anterior->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja_actual']);
            //     //     }                    
            //     //     $validar_proceso_anterior = $validar_proceso_anterior
            //     //     ->orderBy('Id_Asignacion', 'desc')
            //     //     ->limit($cantidad_Id_Asignacion)
            //     //     ->get(); 
            //     //     // Si se cumple la validacion anterior entra al IF Si no entra al Else y Utiliza el Json
                    
            //     //     if (count($validar_proceso_anterior) > 0) {
                        
            //     //         // Se construyen los id del proceso y asignacion
            //     //         $proceso_id = [];
            //     //         $asignacion_id = [];
            //     //         foreach ($validar_proceso_anterior as $key) {
            //     //             $proceso_id[]=[                        
            //     //                 'Id_Proceso_anterior' => $key->Id_proceso
            //     //             ];    
            //     //             $asignacion_id[] = [
            //     //                 'Id_Asignacion_bandeja' => $key->Id_Asignacion
            //     //             ];
            //     //         }  
                        
            //     //         // Validar el nombre del proceso anterior en base a los id_procesos optenidos en el array  $proceso_id
            //     //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //     //         ->select('Nombre_proceso', 'Id_proceso')->whereIn('Id_proceso', $proceso_id)
            //     //         ->groupBy('Nombre_proceso')
            //     //         ->get();  
            //     //         // construir array para los nombres de los procesos
            //     //         $nombre_proceso = [];
            //     //         foreach ($validar_Nombre_proceso_anterior as $value) {
            //     //             $nombre_proceso[] = [
            //     //                 'Nombre_proceso_anterior' => $value->Nombre_proceso,
            //     //                 'Id_proceso' => $value->Id_proceso,
            //     //             ];
            //     //         }
                        
            //     //         $orden_proceso_id = array_reverse($proceso_id);
                        
            //     //         $orden_asignacion_id = array_reverse($asignacion_id);
                        
            //     //         //Combinar los array de  orden_proceso_id y nombre_proceso acorde al proceso
            //     //         foreach ($orden_proceso_id as $key => $value) {
            //     //             foreach ($nombre_proceso as $item) {
            //     //                 if ($value['Id_Proceso_anterior'] == $item['Id_proceso']) {
            //     //                     $orden_proceso_id[$key]['Nombre_proceso_anterior'] = $item['Nombre_proceso_anterior'];
            //     //                     break;
            //     //                 }
            //     //             }
            //     //         }
                        
            //     //         // combinar array anterior orden_proceso_id con el array orden_asignacion_id 
            //     //         $combinar_proceso_asignacion = array();
            //     //         foreach ($orden_asignacion_id as $key => $valor) {
            //     //             if (isset($orden_proceso_id[$key])) {
            //     //                 // Fusionar arrays
            //     //                 $combinar_proceso_asignacion[] = array_merge($orden_proceso_id[$key], $valor);
            //     //             }
            //     //         }                   
                        
            //     //         if (count($combinar_proceso_asignacion) === count($datos_bandejaPCl)) {
            //     //             $numElementos = count($combinar_proceso_asignacion);
                        
            //     //             for ($i = 0; $i < $numElementos; $i++) {
            //     //                 // Combinar los sub-arrays uno a uno
            //     //                 $array_datos_proce_asignacion[] = array_merge($combinar_proceso_asignacion[$i], $datos_bandejaPCl[$i]);
            //     //             }
            //     //         }
                        
            //     //         // se convierte el array  $bandejaPCL a un object
            //     //         $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));
            //     //         // Combinar el array object con el array combinar_proceso_asignacion
            //     //         foreach ($arraybandejaPCL as $key2 => $value2) {
            //     //             foreach ($array_datos_proce_asignacion as $value1) {
            //     //                 // Verifica si los Id_Asignacion coinciden
            //     //                 if ($value2->Id_Asignacion == $value1['Id_Asignacion_bandeja_actual']) {
            //     //                     // Agrega las propiedades al segundo array
            //     //                     $arraybandejaPCL[$key2]->Id_Proceso_anterior = $value1['Id_Proceso_anterior'];
            //     //                     $arraybandejaPCL[$key2]->Nombre_proceso_anterior = $value1['Nombre_proceso_anterior'];
            //     //                 }
            //     //             }
            //     //         }
            //     //     } else {    
            //     //         // Validar el nombre del proceso que viene siendo el mismo ya que no hay uno anterior                
            //     //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //     //         ->select('Nombre_proceso')->where([['Id_proceso', $Id_proceso_bandeja]])
            //     //         ->limit(1)->get(); 

            //     //         // Se alimenta el Json Vacio
            //     //         $Ids_Nombre_proceso_anterior = response()->json([
            //     //             'Id_Proceso_anterior' => $Id_proceso_bandeja,
            //     //             'Nombre_proceso_anterior' => $validar_Nombre_proceso_anterior[0]->Nombre_proceso,
            //     //         ]);
            //     //         // Se Captura los valosres del json y se agregan al object $arraybandejaPCL
            //     //         $Ids_Nombre_proceso_anterior_array = json_decode($Ids_Nombre_proceso_anterior->getContent(), true);
            //     //         $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));
            //     //         $arraybandejaPCL[0]->Id_Proceso_anterior = $Ids_Nombre_proceso_anterior_array['Id_Proceso_anterior'];
            //     //         $arraybandejaPCL[0]->Nombre_proceso_anterior = $Ids_Nombre_proceso_anterior_array['Nombre_proceso_anterior'];
            //     //     }
            //     // }
            // }  

            $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));  
            return response()->json($arraybandejaPCL);

        }
    }

    public function filtroBandejaPCl(Request $request){
        
        $consultar_f_desde = $request->consultar_f_desde;
        $consultar_f_hasta = $request->consultar_f_hasta;
        $consultar_g_dias = $request->consultar_g_dias;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user; 
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                    // Consultar la vista de mysql, traer eventos acorde al proceso
                    if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'){ // si el rol es analista o profesional o comité
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                            ['Id_profesional', '=', $newId_user]
                        ])
                        ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                        ->get(); 

                    }else{

                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ])
                        ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                        ->get(); 
                    }
                    
                    // if (count($bandejaPCL)>0) {
                    //     $ID_evento_bandeja = $bandejaPCL[0]->ID_evento;
                    //     $Id_proceso_bandeja = $bandejaPCL[0]->Id_proceso;                        
                    // }

                    // // Json vacio para llenado en Else en caso de que no haya un proceso anterior
                    // $Ids_Nombre_proceso_anterior = response()->json([]);
                    
                    // // validacion de la vista
                    // if (!empty($bandejaPCL[0]->Nombre_proceso_actual)) {
                    //     $datos_bandejaPCl = [];
                    //     foreach ($bandejaPCL as $item) {
                    //         // Accede a cada propiedad del objeto dentro del bucle  para capturar el id_asignacion                  
                    //         $datos_bandejaPCl[]=[                        
                    //             'Id_Asignacion_bandeja_actual' => $item->Id_Asignacion,
                    //         ];
                    //     }      
                        
                    //     // cantidad de id_asignacion 
                    //     $cantidad_Id_Asignacion = count($datos_bandejaPCl);
                        
                    //     // Validar si existe un porceso anterior con los Id_Asignacion del array $datos_bandejaPCl
                    //     if ($cantidad_Id_Asignacion > 0) {
                            
                    //         $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                    //         ->select('Id_proceso', 'Id_Asignacion')
                    //         ->where('ID_evento', $ID_evento_bandeja); 
                    //         // foreach ($datos_bandejaPCl as $dato) {
                    //         //     $validar_proceso_anterior->orWhere(function ($query) use ($dato) {
                    //         //         $query->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja_actual']);
                    //         //     });
                    //         // }               
                    //         $maxIdAsignacionBandeja = max(array_column($datos_bandejaPCl, 'Id_Asignacion_bandeja_actual'));
                    //         $validar_proceso_anterior = $validar_proceso_anterior
                    //         ->where('Id_Asignacion', '<', $maxIdAsignacionBandeja)
                    //         ->orderBy('Id_Asignacion', 'desc')
                    //         ->limit($cantidad_Id_Asignacion)
                    //        ->get();  
                    //         // $validar_proceso_anterior = $validar_proceso_anterior
                    //         // ->orderBy('Id_Asignacion', 'desc')
                    //         // //->limit($cantidad_Id_Asignacion)
                    //         // ->get();  

                    //         if (count($validar_proceso_anterior) > 0) {
                                
                    //             // Se construyen los id del proceso y asignacion
                    //             $proceso_id = [];
                    //             $asignacion_id = [];
                    //             foreach ($validar_proceso_anterior as $key) {
                    //                 $proceso_id[]=[                        
                    //                     'Id_Proceso_anterior' => $key->Id_proceso
                    //                 ];    
                    //                 $asignacion_id[] = [
                    //                     'Id_Asignacion_bandeja' => $key->Id_Asignacion
                    //                 ];
                    //             }  
                                
                    //             // Validar el nombre del proceso anterior en base a los id_procesos optenidos en el array  $proceso_id
                    //             $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
                    //             ->select('Nombre_proceso', 'Id_proceso')->whereIn('Id_proceso', $proceso_id)
                    //             ->groupBy('Nombre_proceso')
                    //             ->get();  
                    //             // construir array para los nombres de los procesos
                    //             $nombre_proceso = [];
                    //             foreach ($validar_Nombre_proceso_anterior as $value) {
                    //                 $nombre_proceso[] = [
                    //                     'Nombre_proceso_anterior' => $value->Nombre_proceso,
                    //                     'Id_proceso' => $value->Id_proceso,
                    //                 ];
                    //             }
                                                           
                    //             // acondicionar los array acorde al order la consulta Inicial de $bandejaPCL
                    //             //array_shift($proceso_id);                                            
                    //             $orden_proceso_id = array_reverse($proceso_id);
                    //             //array_pop($asignacion_id);                        
                    //             $orden_asignacion_id = array_reverse($asignacion_id);                                
                                
                    //             //Combinar los array de  orden_proceso_id y nombre_proceso acorde al proceso
                    //             foreach ($orden_proceso_id as $key => $value) {
                    //                 foreach ($nombre_proceso as $item) {
                    //                     if ($value['Id_Proceso_anterior'] == $item['Id_proceso']) {
                    //                         $orden_proceso_id[$key]['Nombre_proceso_anterior'] = $item['Nombre_proceso_anterior'];
                    //                         break;
                    //                     }
                    //                 }
                    //             }
                                
                    //             // combinar array anterior orden_proceso_id con el array orden_asignacion_id 
                    //             $combinar_proceso_asignacion = array();
                    //             foreach ($orden_asignacion_id as $key => $valor) {
                    //                 if (isset($orden_proceso_id[$key])) {
                    //                     // Fusionar arrays
                    //                     $combinar_proceso_asignacion[] = array_merge($orden_proceso_id[$key], $valor);
                    //                 }
                    //             }                   
                               
                    //             // for ($i = 1; $i <= $cantidad_Id_Asignacion; $i++) {
                    //             //     array_pop($combinar_proceso_asignacion);
                    //             // }
                                
                    //             if (count($combinar_proceso_asignacion) === count($datos_bandejaPCl)) {
                    //                 $numElementos = count($combinar_proceso_asignacion);
                                
                    //                 for ($i = 0; $i < $numElementos; $i++) {
                    //                     // Combinar los sub-arrays uno a uno
                    //                     $array_datos_proce_asignacion[] = array_merge($combinar_proceso_asignacion[$i], $datos_bandejaPCl[$i]);
                    //                 }
                    //             }
                                
                    //             // se convierte el array  $bandejaPCL a un object
                    //             $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));

                                
                    //             //Combinar el array object con el array combinar_proceso_asignacion
                    //             foreach ($arraybandejaPCL as $key2 => $value2) {
                    //                 foreach ($array_datos_proce_asignacion as $value1) {
                    //                     // Verifica si los Id_Asignacion coinciden
                    //                     if ($value2->Id_Asignacion == $value1['Id_Asignacion_bandeja_actual']) {
                    //                         // Agrega las propiedades al segundo array
                    //                         $arraybandejaPCL[$key2]->Id_Proceso_anterior = $value1['Id_Proceso_anterior'];
                    //                         $arraybandejaPCL[$key2]->Nombre_proceso_anterior = $value1['Nombre_proceso_anterior'];
                    //                     }
                    //                 }
                    //             }
                    //         } 
                                                        
                    //     }                         
                    // }                   
                    $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));            
                    if (count($bandejaPCL)>0){                                                
                        return response()->json($arraybandejaPCL);                        
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada.',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }                    
            break;
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and empty($consultar_g_dias)):

                    // Consultar la vista de mysql, traer eventos acorde al proceso
                    if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'){ // si el rol es analista o profesional o comité
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Id_profesional', '=', $newId_user]
                        ])
                        ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                        ->get(); 

                    }else{
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                        ])
                        ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                        ->get(); 
                    }
                    // if (count($bandejaPCL)>0) {
                    //     $ID_evento_bandeja = $bandejaPCL[0]->ID_evento;
                    //     $Id_proceso_bandeja = $bandejaPCL[0]->Id_proceso;                        
                    // }

                    // // Json vacio para llenado en Else en caso de que no haya un proceso anterior
                    // $Ids_Nombre_proceso_anterior = response()->json([]);
                    
                    // // validacion de la vista
                    // if (!empty($bandejaPCL[0]->Nombre_proceso_actual)) {
                    //     $datos_bandejaPCl = [];
                    //     foreach ($bandejaPCL as $item) {
                    //         // Accede a cada propiedad del objeto dentro del bucle  para capturar el id_asignacion                  
                    //         $datos_bandejaPCl[]=[                        
                    //             'Id_Asignacion_bandeja_actual' => $item->Id_Asignacion,
                    //         ];
                    //     }      
                        
                    //     // cantidad de id_asignacion 
                    //     $cantidad_Id_Asignacion = count($datos_bandejaPCl);
                        
                    //     // Validar si existe un porceso anterior con los Id_Asignacion del array $datos_bandejaPCl
                    //     if ($cantidad_Id_Asignacion > 0) {
                            
                    //         $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                    //         ->select('Id_proceso', 'Id_Asignacion')
                    //         ->where('ID_evento', $ID_evento_bandeja); 
                    //         // foreach ($datos_bandejaPCl as $dato) {
                    //         //     $validar_proceso_anterior->orWhere(function ($query) use ($dato) {
                    //         //         $query->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja_actual']);
                    //         //     });
                    //         // }               
                    //         $maxIdAsignacionBandeja = max(array_column($datos_bandejaPCl, 'Id_Asignacion_bandeja_actual'));
                    //         $validar_proceso_anterior = $validar_proceso_anterior
                    //         ->where('Id_Asignacion', '<', $maxIdAsignacionBandeja)
                    //         ->orderBy('Id_Asignacion', 'desc')
                    //         ->limit($cantidad_Id_Asignacion)
                    //        ->get();  
                    //         // $validar_proceso_anterior = $validar_proceso_anterior
                    //         // ->orderBy('Id_Asignacion', 'desc')
                    //         // //->limit($cantidad_Id_Asignacion)
                    //         // ->get();  

                    //         if (count($validar_proceso_anterior) > 0) {
                                
                    //             // Se construyen los id del proceso y asignacion
                    //             $proceso_id = [];
                    //             $asignacion_id = [];
                    //             foreach ($validar_proceso_anterior as $key) {
                    //                 $proceso_id[]=[                        
                    //                     'Id_Proceso_anterior' => $key->Id_proceso
                    //                 ];    
                    //                 $asignacion_id[] = [
                    //                     'Id_Asignacion_bandeja' => $key->Id_Asignacion
                    //                 ];
                    //             }  
                                
                    //             // Validar el nombre del proceso anterior en base a los id_procesos optenidos en el array  $proceso_id
                    //             $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
                    //             ->select('Nombre_proceso', 'Id_proceso')->whereIn('Id_proceso', $proceso_id)
                    //             ->groupBy('Nombre_proceso')
                    //             ->get();  
                    //             // construir array para los nombres de los procesos
                    //             $nombre_proceso = [];
                    //             foreach ($validar_Nombre_proceso_anterior as $value) {
                    //                 $nombre_proceso[] = [
                    //                     'Nombre_proceso_anterior' => $value->Nombre_proceso,
                    //                     'Id_proceso' => $value->Id_proceso,
                    //                 ];
                    //             }
                                                           
                    //             // acondicionar los array acorde al order la consulta Inicial de $bandejaPCL
                    //             //array_shift($proceso_id);                                            
                    //             $orden_proceso_id = array_reverse($proceso_id);
                    //             //array_pop($asignacion_id);                        
                    //             $orden_asignacion_id = array_reverse($asignacion_id);                                
                                
                    //             //Combinar los array de  orden_proceso_id y nombre_proceso acorde al proceso
                    //             foreach ($orden_proceso_id as $key => $value) {
                    //                 foreach ($nombre_proceso as $item) {
                    //                     if ($value['Id_Proceso_anterior'] == $item['Id_proceso']) {
                    //                         $orden_proceso_id[$key]['Nombre_proceso_anterior'] = $item['Nombre_proceso_anterior'];
                    //                         break;
                    //                     }
                    //                 }
                    //             }
                                
                    //             // combinar array anterior orden_proceso_id con el array orden_asignacion_id 
                    //             $combinar_proceso_asignacion = array();
                    //             foreach ($orden_asignacion_id as $key => $valor) {
                    //                 if (isset($orden_proceso_id[$key])) {
                    //                     // Fusionar arrays
                    //                     $combinar_proceso_asignacion[] = array_merge($orden_proceso_id[$key], $valor);
                    //                 }
                    //             }                   
                               
                    //             // for ($i = 1; $i <= $cantidad_Id_Asignacion; $i++) {
                    //             //     array_pop($combinar_proceso_asignacion);
                    //             // }
                                
                    //             if (count($combinar_proceso_asignacion) === count($datos_bandejaPCl)) {
                    //                 $numElementos = count($combinar_proceso_asignacion);
                                
                    //                 for ($i = 0; $i < $numElementos; $i++) {
                    //                     // Combinar los sub-arrays uno a uno
                    //                     $array_datos_proce_asignacion[] = array_merge($combinar_proceso_asignacion[$i], $datos_bandejaPCl[$i]);
                    //                 }
                    //             }
                                
                    //             // se convierte el array  $bandejaPCL a un object
                    //             $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));

                                
                    //             //Combinar el array object con el array combinar_proceso_asignacion
                    //             foreach ($arraybandejaPCL as $key2 => $value2) {
                    //                 foreach ($array_datos_proce_asignacion as $value1) {
                    //                     // Verifica si los Id_Asignacion coinciden
                    //                     if ($value2->Id_Asignacion == $value1['Id_Asignacion_bandeja_actual']) {
                    //                         // Agrega las propiedades al segundo array
                    //                         $arraybandejaPCL[$key2]->Id_Proceso_anterior = $value1['Id_Proceso_anterior'];
                    //                         $arraybandejaPCL[$key2]->Nombre_proceso_anterior = $value1['Nombre_proceso_anterior'];
                    //                     }
                    //                 }
                    //             }
                    //         } 
                                                        
                    //     }                         
                    // }   
                    $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true)); 
                    if (count($bandejaPCL)>0) {
                        return response()->json($arraybandejaPCL);
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada.',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }
            break;
            case (empty($consultar_f_desde) and empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                    // Consultar la vista de mysql, traer eventos acorde al proceso
                    if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'){ // si el rol es analista o profesional o comité
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                            ['Id_profesional', '=', $newId_user]
                        ])                    
                        ->get(); 
                    }else{
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ])                    
                        ->get(); 

                    }
                        
                    // if (count($bandejaPCL)>0) {
                    //     $ID_evento_bandeja = $bandejaPCL[0]->ID_evento;
                    //     $Id_proceso_bandeja = $bandejaPCL[0]->Id_proceso;                        
                    // }

                    // // Json vacio para llenado en Else en caso de que no haya un proceso anterior
                    // $Ids_Nombre_proceso_anterior = response()->json([]);
                    
                    // // validacion de la vista
                    // if (!empty($bandejaPCL[0]->Nombre_proceso_actual)) {
                    //     $datos_bandejaPCl = [];
                    //     foreach ($bandejaPCL as $item) {
                    //         // Accede a cada propiedad del objeto dentro del bucle  para capturar el id_asignacion                  
                    //         $datos_bandejaPCl[]=[                        
                    //             'Id_Asignacion_bandeja_actual' => $item->Id_Asignacion,
                    //         ];
                    //     }      
                        
                    //     // cantidad de id_asignacion 
                    //     $cantidad_Id_Asignacion = count($datos_bandejaPCl);
                        
                    //     // Validar si existe un porceso anterior con los Id_Asignacion del array $datos_bandejaPCl
                    //     if ($cantidad_Id_Asignacion > 0) {
                            
                    //         $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                    //         ->select('Id_proceso', 'Id_Asignacion')
                    //         ->where('ID_evento', $ID_evento_bandeja); 
                    //         // foreach ($datos_bandejaPCl as $dato) {
                    //         //     $validar_proceso_anterior->orWhere(function ($query) use ($dato) {
                    //         //         $query->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja_actual']);
                    //         //     });
                    //         // }               
                    //         $maxIdAsignacionBandeja = max(array_column($datos_bandejaPCl, 'Id_Asignacion_bandeja_actual'));
                    //         $validar_proceso_anterior = $validar_proceso_anterior
                    //         ->where('Id_Asignacion', '<', $maxIdAsignacionBandeja)
                    //         ->orderBy('Id_Asignacion', 'desc')
                    //         ->limit($cantidad_Id_Asignacion)
                    //        ->get();  
                    //         // $validar_proceso_anterior = $validar_proceso_anterior
                    //         // ->orderBy('Id_Asignacion', 'desc')
                    //         // //->limit($cantidad_Id_Asignacion)
                    //         // ->get();  

                    //         if (count($validar_proceso_anterior) > 0) {
                                
                    //             // Se construyen los id del proceso y asignacion
                    //             $proceso_id = [];
                    //             $asignacion_id = [];
                    //             foreach ($validar_proceso_anterior as $key) {
                    //                 $proceso_id[]=[                        
                    //                     'Id_Proceso_anterior' => $key->Id_proceso
                    //                 ];    
                    //                 $asignacion_id[] = [
                    //                     'Id_Asignacion_bandeja' => $key->Id_Asignacion
                    //                 ];
                    //             }  
                                
                    //             // Validar el nombre del proceso anterior en base a los id_procesos optenidos en el array  $proceso_id
                    //             $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
                    //             ->select('Nombre_proceso', 'Id_proceso')->whereIn('Id_proceso', $proceso_id)
                    //             ->groupBy('Nombre_proceso')
                    //             ->get();  
                    //             // construir array para los nombres de los procesos
                    //             $nombre_proceso = [];
                    //             foreach ($validar_Nombre_proceso_anterior as $value) {
                    //                 $nombre_proceso[] = [
                    //                     'Nombre_proceso_anterior' => $value->Nombre_proceso,
                    //                     'Id_proceso' => $value->Id_proceso,
                    //                 ];
                    //             }
                                                           
                    //             // acondicionar los array acorde al order la consulta Inicial de $bandejaPCL
                    //             //array_shift($proceso_id);                                            
                    //             $orden_proceso_id = array_reverse($proceso_id);
                    //             //array_pop($asignacion_id);                        
                    //             $orden_asignacion_id = array_reverse($asignacion_id);                                
                                
                    //             //Combinar los array de  orden_proceso_id y nombre_proceso acorde al proceso
                    //             foreach ($orden_proceso_id as $key => $value) {
                    //                 foreach ($nombre_proceso as $item) {
                    //                     if ($value['Id_Proceso_anterior'] == $item['Id_proceso']) {
                    //                         $orden_proceso_id[$key]['Nombre_proceso_anterior'] = $item['Nombre_proceso_anterior'];
                    //                         break;
                    //                     }
                    //                 }
                    //             }
                                
                    //             // combinar array anterior orden_proceso_id con el array orden_asignacion_id 
                    //             $combinar_proceso_asignacion = array();
                    //             foreach ($orden_asignacion_id as $key => $valor) {
                    //                 if (isset($orden_proceso_id[$key])) {
                    //                     // Fusionar arrays
                    //                     $combinar_proceso_asignacion[] = array_merge($orden_proceso_id[$key], $valor);
                    //                 }
                    //             }                   
                               
                    //             // for ($i = 1; $i <= $cantidad_Id_Asignacion; $i++) {
                    //             //     array_pop($combinar_proceso_asignacion);
                    //             // }
                                
                    //             if (count($combinar_proceso_asignacion) === count($datos_bandejaPCl)) {
                    //                 $numElementos = count($combinar_proceso_asignacion);
                                
                    //                 for ($i = 0; $i < $numElementos; $i++) {
                    //                     // Combinar los sub-arrays uno a uno
                    //                     $array_datos_proce_asignacion[] = array_merge($combinar_proceso_asignacion[$i], $datos_bandejaPCl[$i]);
                    //                 }
                    //             }
                                
                    //             // se convierte el array  $bandejaPCL a un object
                    //             $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));

                                
                    //             //Combinar el array object con el array combinar_proceso_asignacion
                    //             foreach ($arraybandejaPCL as $key2 => $value2) {
                    //                 foreach ($array_datos_proce_asignacion as $value1) {
                    //                     // Verifica si los Id_Asignacion coinciden
                    //                     if ($value2->Id_Asignacion == $value1['Id_Asignacion_bandeja_actual']) {
                    //                         // Agrega las propiedades al segundo array
                    //                         $arraybandejaPCL[$key2]->Id_Proceso_anterior = $value1['Id_Proceso_anterior'];
                    //                         $arraybandejaPCL[$key2]->Nombre_proceso_anterior = $value1['Nombre_proceso_anterior'];
                    //                     }
                    //                 }
                    //             }
                    //         } 
                                                        
                    //     }                         
                    // } 
                    $arraybandejaPCL = json_decode(json_encode($bandejaPCL, true));                  
                    if (count($bandejaPCL)>0) {
                        return response()->json($arraybandejaPCL);
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }
            break;     
            case (!empty($consultar_f_desde) and empty($consultar_f_hasta) and empty($consultar_g_dias)):
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensajes" => 'Debe ingresar la fecha Hasta para poder filtrar',
                        "registros" => 0
                    );
                    return json_decode(json_encode($mensajes, true));
            break;  
            case (empty($consultar_f_desde) and !empty($consultar_f_hasta) and empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la Fecha Desde para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;  
            case (!empty($consultar_f_desde) and empty($consultar_f_hasta) and !empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la fecha Hasta para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;  
            case (empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la Fecha Desde para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;              
            default:                
            break;
        }
    
        
    }

    public function actualizarBandejaPCL(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $usuario = Auth::user()->name;
        $time = time();
        $date = date("Y-m-d", $time);
        $date_con_hora = date("Y-m-d h:i:s", $time);
        
        $IdEventoBandejaPCl = $request->array;
        $Id_proceso = $request->json['proceso_parametrizado'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];
        $Id_accion = $request->json['accion'];
        $Id_profesional = $request->json['profesional'];
        $Descripcion_bandeja = $request->json['descripcion_bandeja'];

        // Paso N°1: Extraemos el id estado de la tabla de parametrizaciones dependiendo del
        // id proceso, id servicio, id accion. Este id irá como estado  en el evento
        $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.Estado')
        ->where([
            // ['sipc.Id_cliente', '=', $request->cliente],
            ['sipc.Id_proceso', '=', $Id_proceso],
            ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
            ['sipc.Accion_ejecutar','=',  $Id_accion]
        ])->get();

        if(count($estado_acorde_a_parametrica)>0){
            $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
        }else{
            $Id_Estado_evento = 223;
        }
        
        // Paso N°2: Obtenemos los id del proceso y servicio anteriores dependiendo del o los id de asignacion
        $array_id_procesos = [];
        $array_id_servicios = [];
        for ($a=0; $a < count($IdEventoBandejaPCl); $a++) { 
            $array_ids = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('Id_proceso', 'Id_servicio')
            ->where('Id_Asignacion', $IdEventoBandejaPCl[$a])->get();

            $info_array_ids = json_decode(json_encode($array_ids, true));

            array_push($array_id_procesos, $info_array_ids[0]->Id_proceso);
            array_push($array_id_servicios, $info_array_ids[0]->Id_servicio);
        }

        // Paso N°3: Obtenemos el nombre del profesional y se setea el dato de F_asignacion_calificacion
        if (!empty($Id_profesional)) {
            $profesional = DB::table('users')
            ->select('name')->where('id',$Id_profesional)
            ->get();

            $nombre = json_decode(json_encode($profesional));
            $nombre_profesional= $nombre[0]->name;
            $F_asignacion_calificacion = $date_con_hora;
        }else{
            $Id_profesional = null;
            $nombre_profesional = null;
            $F_asignacion_calificacion = null;
        }

        // Paso N°4: Armado de datos
        $array_datos_finales_actualizar = [];
        for ($m=0; $m < count($IdEventoBandejaPCl); $m++) {
            switch (true) {
                // CASO 1: Id asignacion no es vacio y id profesional no es vacio y id servicio no es vacio
                case (!empty($IdEventoBandejaPCl) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
            
                    /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
                    $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
                    ->select('sipc.Detiene_tiempo_gestion')
                    ->where([
                        // ['sipc.Id_cliente', '=', $id_cliente],
                        ['sipc.Id_proceso', '=', $Id_proceso],
                        ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
                        ['sipc.Accion_ejecutar', '=', $Id_accion]
                    ])->get();

                    if(count($casilla_detiene_tiempo_gestion) > 0){
                        $Detiene_tiempo_gestion = $casilla_detiene_tiempo_gestion[0]->Detiene_tiempo_gestion;
                        if ($Detiene_tiempo_gestion == "Si") {
                            $Detener_tiempo_gestion = "Si";
                            $F_detencion_tiempo_gestion = $date;
                        }else{
                            $Detener_tiempo_gestion = "No";
                            $F_detencion_tiempo_gestion = null;
                        }
                    };

                    $actualizar_bandejaPCL = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'F_asignacion_calificacion' => $F_asignacion_calificacion,
                        'Id_profesional' =>  $Id_profesional,
                        'Nombre_profesional' => $nombre_profesional,
                        'Descripcion_bandeja' => $Descripcion_bandeja,
                        'Nombre_usuario' => $usuario,
                        'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                        'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion
                    ];

                    /* echo "CASO 1 <br>";
                    echo "<pre>";
                    print_r($actualizar_bandejaPCL);
                    echo "</pre>"; */

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaPCL);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
                
                break;
                // CASO 2: Id asignacion no es vacio y id profesional es vacio y id servicio no es vacio
                case (!empty($IdEventoBandejaPCl) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):


                    /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
                    $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
                    ->select('sipc.Detiene_tiempo_gestion')
                    ->where([
                        // ['sipc.Id_cliente', '=', $id_cliente],
                        ['sipc.Id_proceso', '=', $Id_proceso],
                        ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
                        ['sipc.Accion_ejecutar', '=', $Id_accion]
                    ])->get();

                    if(count($casilla_detiene_tiempo_gestion) > 0){
                        $Detiene_tiempo_gestion = $casilla_detiene_tiempo_gestion[0]->Detiene_tiempo_gestion;
                        if ($Detiene_tiempo_gestion == "Si") {
                            $Detener_tiempo_gestion = "Si";
                            $F_detencion_tiempo_gestion = $date;
                        }else{
                            $Detener_tiempo_gestion = "No";
                            $F_detencion_tiempo_gestion = null;
                        }
                    };

                    $actualizar_bandejaPCL_Servicio = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'Nombre_usuario' => $usuario,
                        'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                        'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion
                    ]; 
                    
                    /* echo "CASO 2 <br>";
                    echo "<pre>";
                    print_r($actualizar_bandejaPCL_Servicio);
                    echo "</pre>"; */

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaPCL_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                break;
                // CASO 3: Id asignacion no es vacio y id profesional no es vacio y id servicio es vacio
                case (!empty($IdEventoBandejaPCl) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):

                    /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
                    $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
                    ->select('sipc.Detiene_tiempo_gestion')
                    ->where([
                        // ['sipc.Id_cliente', '=', $id_cliente],
                        ['sipc.Id_proceso', '=', $Id_proceso],
                        ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
                        ['sipc.Accion_ejecutar', '=', $Id_accion]
                    ])->get();

                    if(count($casilla_detiene_tiempo_gestion) > 0){
                        $Detiene_tiempo_gestion = $casilla_detiene_tiempo_gestion[0]->Detiene_tiempo_gestion;
                        if ($Detiene_tiempo_gestion == "Si") {
                            $Detener_tiempo_gestion = "Si";
                            $F_detencion_tiempo_gestion = $date;
                        }else{
                            $Detener_tiempo_gestion = "No";
                            $F_detencion_tiempo_gestion = null;
                        }
                    };

                    $actualizar_bandejaPCL_Profesional = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'F_asignacion_calificacion' => $F_asignacion_calificacion,
                        'Id_profesional' =>  $Id_profesional,
                        'Nombre_profesional' => $nombre_profesional,
                        'Descripcion_bandeja' => $Descripcion_bandeja,
                        'Nombre_usuario' => $usuario,
                        'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                        'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion
                    ];

                    /* echo "CASO 3 <br>";
                    echo "<pre>";
                    print_r($actualizar_bandejaPCL_Profesional);
                    echo "</pre>"; */

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaPCL_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                break;
                // CASO 4: Id asignacion no es vacio y id profesional es vacio y id servicio es vacio
                case (!empty($IdEventoBandejaPCl) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_PCL',
                        "mensaje" => 'No se puede realizar la actualización de la información.'
                    );

                    // echo "CASO 4 <br>";
                    // return json_decode(json_encode($mensajes, true));
                break;
                    
                default:                
                break;
            };
        };

        // Paso N° 5: Actualización de la información
        for ($b=0; $b < count($array_datos_finales_actualizar); $b++) { 
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $IdEventoBandejaPCl[$b])
            ->update($array_datos_finales_actualizar[$b]);
        }
        $array_datos_finales_actualizar = [];
        return json_decode(json_encode($mensajes, true));

    }
}
