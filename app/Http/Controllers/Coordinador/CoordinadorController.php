<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
//llamado de modelos para formulario BandejaPCL y captura de data
use App\Models\sigmel_lista_procesos_servicios;
use App\Models\cndatos_bandeja_eventos;
use App\Models\cndatos_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_historial_acciones_eventos;

use App\Models\sigmel_informacion_acciones;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\sigmel_informacion_historial_accion_eventos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_correspondencia_eventos;
use App\Models\sigmel_numero_orden_eventos;
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
            
            $id_proceso = $request->Id_proceso;
            $id_servicio = $request->Id_servicio;
            $id_accion = $request->Id_accion;

            /* Extraemos el equippo de trabajo y el profesional asignado configurados en la paramétrica */
            $info_equipo_prof_asig = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Equipo_trabajo', 'sipc.Profesional_asignado')
            ->where([
                // ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $id_proceso],
                ['sipc.Servicio_asociado', '=', $id_servicio],
                ['sipc.Accion_ejecutar', '=', $id_accion]
            ])->get();

            /* Si el profesional asignado está configurado entonces el listado de profesionales
            se cargará con los usuarios que pertenecen al equipo de trabajo configurado en la paramétrica */
            if($info_equipo_prof_asig[0]->Profesional_asignado <> ""){
                $listado_profesionales = DB::table('users as u')
                ->leftJoin('sigmel_gestiones.sigmel_usuarios_grupos_trabajos as sugt', 'u.id', '=', 'sugt.id_usuarios_asignados')
                ->select('u.id', 'u.name')
                ->where([['sugt.id_equipo_trabajo', $info_equipo_prof_asig[0]->Equipo_trabajo]])
                ->get();

                $info_listado_profesionales = json_decode(json_encode($listado_profesionales, true));
                return response()->json([
                    'info_listado_profesionales' => $info_listado_profesionales,
                    'Profesional_asignado' => $info_equipo_prof_asig[0]->Profesional_asignado
                ]);
            }else{
                
                $listado_profesional_pcl = DB::table('users')->select('id', 'name')
                ->where('estado', 'Activo')
                ->whereRaw("FIND_IN_SET(2, id_procesos_usuario) > 0")->get();
    
                $info_listado_profesional_PCL = json_decode(json_encode($listado_profesional_pcl, true));
                // return response()->json($info_listado_profesional_PCL);

                return response()->json([
                    'info_listado_profesionales' => $info_listado_profesional_PCL,
                    'Profesional_asignado' => ''
                ]);

            }
        }
        
    }

    public function sinFiltroBandejaPCL(Request $request){

        $BandejaPClTotal = $request->BandejaPClTotal;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user;
        
        $time = time();
        $date = date("Y-m-d", $time);
        $year = date("Y");

        if($BandejaPClTotal == 'CargaBandejaPCl'){
            // Consultar la vista de mysql, traer eventos acorde al proceso
            if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where([
                    ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                    ['Id_profesional', '=', $newId_user]
                ])->where(function($query){
                    $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                })
                //->whereBetween('F_registro_asignacion', [$year.'-01-01' , $date])
                ->get();  
            }else{
                $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where([
                    ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                ])->where(function($query){
                    $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                })
                ->whereBetween('F_registro_asignacion', [$year.'-01-01' , $date])
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
                    if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                            ['Id_profesional', '=', $newId_user]
                        ])->where(function($query){
                            $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                        })->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                        ->get(); 

                    }else{

                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ])->where(function($query){
                            $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                        })->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
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
                    if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Id_profesional', '=', $newId_user]
                        ])->where(function($query){
                            $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                        })->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                        ->get(); 

                    }else{
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                        ])->where(function($query){
                            $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                        })->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
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
                    if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                            ['Id_profesional', '=', $newId_user]
                        ])->where(function($query){
                            $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                        })->get(); 
                    }else{
                        $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ])->where(function($query){
                            $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                        })->get(); 

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

    public function alertaNaranjasRojasPCL(Request $request) {
        $alertas = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
        ->where([['Estado_alerta_automatica', '=', 'Ejecucion']])
        ->get();
        return response()->json(['data' => $alertas]);
    }

    public function actualizarBandejaPCL(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $usuario = Auth::user()->name;
        $time = time();
        $date = date("Y-m-d", $time);
        $date_con_hora = date("Y-m-d h:i:s", $time);
        $date_time = date("Y-m-d H:i:s");

        $IdEventoBandejaPCl = $request->array;
        $Id_proceso = $request->json['proceso_parametrizado'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];
        $Id_accion = $request->json['accion'];
        $Id_profesional = $request->json['profesional'];
        $Descripcion_bandeja = $request->json['descripcion_bandeja'];

        // Paso N°1: Extraemos el id estado de la tabla de parametrizaciones dependiendo del
        // id proceso, id servicio, id accion. Este id irá como estado  en el evento
        $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.Estado', 'sipc.Enviar_a_bandeja_trabajo_destino as enviarA')
        ->where([
            // ['sipc.Id_cliente', '=', $request->cliente],
            ['sipc.Id_proceso', '=', $Id_proceso],
            ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
            ['sipc.Accion_ejecutar','=',  $Id_accion]
        ])->get();

        //Trae El numero de orden actual
        $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
        ->select('Numero_orden')
        ->get();

        //Asignamos #n de orden cuado se envie un caso a notificaciones
        if(!empty($estado_acorde_a_parametrica[0]->enviarA)){
            $N_orden_evento=$n_orden[0]->Numero_orden;
        }else{
            $N_orden_evento=null;
        }

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
                        'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',
                        'N_de_orden' => $N_orden_evento,
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
                        'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',
                        'N_de_orden' => $N_orden_evento,
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
                        'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',
                        'N_de_orden' => $N_orden_evento,
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

        sleep(2);
        
        // Capturar todos los eventos de los id de asignacion y almacenarlo en array array_id_asignacion
        $array_id_asignacion = [];
        for ($a=0; $a < count($IdEventoBandejaPCl); $a++) {
            $array_datos_asignacion = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('Id_Asignacion', 'ID_evento')->where('Id_Asignacion', $IdEventoBandejaPCl[$a])->get();
            // Convertir la colección a un array de objetos stdClass
            $info_array_asignacion = $array_datos_asignacion->toArray();
            // Verificar que el array no esté vacío y contenga al menos un elemento
            if (!empty($info_array_asignacion)) {
                $array_id_asignacion[] = [
                    $info_array_asignacion[0]['Id_Asignacion'], 
                    $info_array_asignacion[0]['ID_evento']
                ];

                //Habilita edicion del proceso de correspodencia para el proceso de notificacion.
                if(isset($estado_acorde_a_parametrica[0]->enviarA)){
                    BandejaNotifiController::finalizarNotificacion($info_array_asignacion[0]['ID_evento'], $info_array_asignacion[0]['Id_Asignacion'],false);
                }
            }

        }        
        // Captura de informacion de los campos de la bandeja que se van actualizar
        $datos_historial_accion_eventos = [
            'Id_proceso' => $Id_proceso,
            'Id_servicio' => $Id_Servicio_redireccionar,
            'Id_accion' => $Id_accion,
            'Documento' => 'N/A',
            'Descripcion' => $Descripcion_bandeja,
            'F_accion' => $date_time,
            'Nombre_usuario' => $usuario,
        ];
        
        // Construir array para la insercion
        $array_datos_historial_accion_eventos = array();
        foreach ($array_id_asignacion as $asignacion) {
            // Crear un array combinando Id_Asignacion y ID_evento
            $asignacion_array = [
                'Id_Asignacion' => $asignacion[0],
                'ID_evento' => $asignacion[1]
            ];            
            // Fusionar el array de asignación con los datos de historial
            $item = array_merge($asignacion_array, $datos_historial_accion_eventos);            
            // Agregar el item al array de resultados
            $array_datos_historial_accion_eventos[] = $item;
        }
        
        foreach ($array_datos_historial_accion_eventos as $historial) {
            sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
            ->insert($historial);
        }
        return json_decode(json_encode($mensajes, true));

    }

    public function reemplazarDocumento(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Id_comunicado = $request->id_comunicado;
        $Id_evento = $request->id_evento;
        $Id_asignacion = $request->id_asignacion;
        $Id_procesos = $request->id_proceso;
        $tipo_descarga = $request->tipo_descarga;
        $modulo_creacion = $request->modulo_creacion;
        $numero_identificacion = $request->numero_identificacion;
        $n_radicado = $request->n_radicado;
        $nombre_doc_anterior = $request->nombre_anterior;

        if($request->hasFile('doc_de_reemplazo')){
            $archivo = $request->file('doc_de_reemplazo');
            $path = public_path('Documentos_Eventos/'.$Id_evento);
            $mode = 0777;

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode, true, true);
                chmod($path, octdec($mode));
            }

            if($tipo_descarga === 'Manual'){
                $nombre_final_documento = $request->asunto;
                if($nombre_doc_anterior !== '' && $nombre_doc_anterior !== $nombre_final_documento){
                    File::delete($path.'/'.$nombre_doc_anterior);
                }

                // echo $nombre_final_documento."<br>";
                // echo $nombre_doc_anterior;

                // Obtenemos el nombre original del archivo (incluyendo la extensión)
                $documentName = $archivo->getClientOriginalName();
                // Obtenemos la extensión del archivo
                $extension = $archivo->getClientOriginalExtension();
                // Obtenemos el nombre del archivo sin la extensión
                $nameWithoutExtension = pathinfo($documentName, PATHINFO_FILENAME);

                /* Agregamos el indicativo */
                $indicativo = time();

                // el nuevo nombre del documento será:
                $nombre_final_documento = "{$nameWithoutExtension}_{$indicativo}.{$extension}";

            }
            else {
                $nombre_final_documento = $request -> nombre_documento;
            }
            Storage::putFileAs($Id_evento, $archivo, $nombre_final_documento);
        } 

        if($tipo_descarga === 'Manual'){
            $datos_comunicado_actualizar=[
                'F_comunicado' => $date,
                'Elaboro' => $nombre_usuario,
                'Nombre_documento' => $request->nombre_documento,
                // 'Asunto' => $request->asunto,
                'Asunto' => $nombre_final_documento,
                'Reemplazado' => 1,
            ];
        }
        else{
            $datos_comunicado_actualizar=[
                'F_comunicado' => $date,
                'Elaboro' => $nombre_usuario,
                'Reemplazado' => 1,
            ];
        }

        

        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
        ->update($datos_comunicado_actualizar);

        sleep(2);
        $datos_info_historial_acciones = [
            'ID_evento' => $Id_evento,
            'F_accion' => $date,
            'Nombre_usuario' => $nombre_usuario,
            'Accion_realizada' => "Reemplaza el comunicado con ID_Comunicado $Id_comunicado",
            'Descripcion' => $request->asunto,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
    
    
        $mensajes = array(
            "parametro" => 'reemplazar_comunicado',
            "mensaje" => 'Comunicado reemplazado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function getInfoComunicado(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $id_comunicado = $request->id_comunicado;
        $info_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([['Id_comunicado',$id_comunicado]])
            ->get();

        return response()->json($info_comunicado);
    }

    public function getInformacionCorrespondencia(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Id_comunicado = $request->id_comunicado;
        $Id_evento = $request->id_evento;
        $Id_asignacion = $request->id_asignacion;
        $Id_proceso = $request->id_proceso;
        $Tipo_correspondencia = $request->tipo_correspondencia;
        $Previous_saved = $request->previous_saved;
        $info_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->select('Destinatario','Nombre_destinatario','Otro_destinatario','JRCI_Destinatario')
            ->where([['Id_Comunicado', $Id_comunicado]])
            ->get();

        if($Previous_saved === 'false'){
            //Nro_Orden
            $nro_orden = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('N_de_orden')
            ->where([['Id_Asignacion', $Id_asignacion]])
            ->first();
            $response = [
                'nro_orden' => $nro_orden ? $nro_orden->N_de_orden : null,
            ];
            //Informacion afiliado
            $infoAfiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
                ->select('siae.Direccion as Direccion_destinatario','ci.Nombre_municipio as Ciudad_destinatario','ci.Nombre_departamento as Departamento_destinatario',
                'siae.Telefono_contacto as Telefono_destinatario','siae.Email as Email_destinatario','siae.Nombre_afiliado as Nombre_destinatario',
                'siae.Medio_notificacion as Medio_notificacion_destinatario', 'siae.Nro_identificacion as Documento_destinatario', 'siae.Entidad_conocimiento', 'siae.Id_afp_entidad_conocimiento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as ci', 'siae.Id_municipio', '=', 'ci.Id_municipios')
                ->where('siae.ID_evento',  '=', $Id_evento)
                ->get();
            if($Tipo_correspondencia != '' && $Tipo_correspondencia != null){
                $Tipo_correspondencia = strtolower($Tipo_correspondencia);
                if($Tipo_correspondencia === 'afiliado' && !empty($infoAfiliado)){
                    $response['datos'] = count($infoAfiliado) > 0 ? $infoAfiliado[0] : null;
                    if(!empty($info_comunicado) && strtolower($info_comunicado[0]->Otro_destinatario === 1)){
                        if($info_comunicado[0]->Destinatario === $Tipo_correspondencia){
                            $response['datos'] = count($infoAfiliado) > 0 ? $infoAfiliado[0] : null;
                        }
                    }
                }
                else if(($Tipo_correspondencia === 'empresa' || $Tipo_correspondencia === 'empleador') && !empty($infoAfiliado)){
                    $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('sile.Empresa as Nombre_destinatario', 'sile.Direccion as Direccion_destinatario', 'sile.Telefono_empresa as Telefono_destinatario',
                    'sile.Email as Email_destinatario', 'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario',
                    'sile.Medio_notificacion as Medio_notificacion_destinatario')
                    ->where([['sile.Nro_identificacion', $infoAfiliado[0]->Documento_destinatario],['sile.ID_evento', $Id_evento]])
                    ->get();
                    $response['datos'] = count($datos_empleador) > 0 ? $datos_empleador[0] : null;
                    if(!empty($info_comunicado) && strtolower($info_comunicado[0]->Otro_destinatario === 1)){
                        
                        if($info_comunicado[0]->Destinatario === $Tipo_correspondencia){
                            $response['datos'] = count($datos_empleador) > 0 ? $datos_empleador[0] : null;
                        }
                    }
                }
                else if($Tipo_correspondencia === 'eps' && !empty($infoAfiliado)){
                    $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                    ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Emails as Email_destinatario', 'sie.Telefonos as Telefono_destinatario', 
                    'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario','slp.Nombre_parametro as Medio_notificacion_destinatario')
                    ->where([['Nro_identificacion', $infoAfiliado[0]->Documento_destinatario],['ID_evento', $Id_evento]])
                    ->get();
                    $response['datos'] = count($datos_eps) > 0 ? $datos_eps[0] : null;
                    if(!empty($info_comunicado) && $info_comunicado[0]->Otro_destinatario === 1){
                        if(strtolower($info_comunicado[0]->Destinatario) === $Tipo_correspondencia && $info_comunicado[0]->Nombre_destinatario != 'N/A'){
                            $datos_eps_otro_destinatario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                            ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Emails as Email_destinatario', 'sie.Telefonos as Telefono_destinatario', 
                            'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario','slp.Nombre_parametro as Medio_notificacion_destinatario')
                            ->where([['Id_Entidad', $info_comunicado[0]->Nombre_destinatario]])
                            ->get();
                            $response['datos'] = count($datos_eps_otro_destinatario) > 0 ? $datos_eps_otro_destinatario[0] : null;
                        }
                    }                    
                }
                else if($Tipo_correspondencia === 'afp' && !empty($infoAfiliado)){
                    $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                    ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Telefonos as Telefono_destinatario', 'sie.Otros_Telefonos', 'sie.Emails as Email_destinatario',
                    'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario','slp.Nombre_parametro as Medio_notificacion_destinatario')
                    ->where([['Nro_identificacion', $infoAfiliado[0]->Documento_destinatario],['ID_evento', $Id_evento]])
                    ->get();
                    $response['datos'] = count($datos_afp) > 0 ? $datos_afp[0] : null;
                    if(!empty($info_comunicado) && $info_comunicado[0]->Otro_destinatario === 1){
                        if(strtolower($info_comunicado[0]->Destinatario) === $Tipo_correspondencia && $info_comunicado[0]->Nombre_destinatario != 'N/A'){
                            $datos_afp_otro_destinatario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                            ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Emails as Email_destinatario', 'sie.Telefonos as Telefono_destinatario', 
                            'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario','slp.Nombre_parametro as Medio_notificacion_destinatario')
                            ->where([['Id_Entidad', $info_comunicado[0]->Nombre_destinatario]])
                            ->get();
                            $response['datos'] = count($datos_afp_otro_destinatario) > 0 ? $datos_afp_otro_destinatario[0] : null;
                        }
                    }
                }
                else if($Tipo_correspondencia === 'arl' && !empty($infoAfiliado)){
                    $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                    ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Telefonos as Telefono_destinatario', 'sie.Otros_Telefonos', 'sie.Emails as Email_destinatario',
                    'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario', 'slp.Nombre_parametro as Medio_notificacion_destinatario')
                    ->where([['Nro_identificacion', $infoAfiliado[0]->Documento_destinatario],['ID_evento', $Id_evento]])
                    ->get();
                    $response['datos'] = count($datos_arl) > 0 ? $datos_arl[0] : null;
                    if(!empty($info_comunicado) && $info_comunicado[0]->Otro_destinatario === 1){
                        if(strtolower($info_comunicado[0]->Destinatario) === $Tipo_correspondencia && $info_comunicado[0]->Nombre_destinatario != 'N/A'){
                            $datos_arl_otro_destinatario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                            ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Emails as Email_destinatario', 'sie.Telefonos as Telefono_destinatario', 
                            'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario','slp.Nombre_parametro as Medio_notificacion_destinatario')
                            ->where([['Id_Entidad', $info_comunicado[0]->Nombre_destinatario]])
                            ->get();
                            $response['datos'] = count($datos_arl_otro_destinatario) > 0 ? $datos_arl_otro_destinatario[0] : null;
                        }
                    }
                }
                else if($Tipo_correspondencia === 'jrci'){
                    $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos as sicje') 
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sicje.Jrci_califi_invalidez', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                    ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Telefonos as Telefono_destinatario', 'sie.Otros_Telefonos', 'sie.Emails as Email_destinatario',
                    'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario', 'slp.Nombre_parametro as Medio_notificacion_destinatario')                   
                    ->where([['ID_evento', $Id_evento],['Id_Asignacion', $Id_asignacion],['Id_proceso', $Id_proceso]])
                    ->get();
                    $response['datos'] = count($datos_jrci) > 0 ? $datos_jrci[0] : null;
                    if(!empty($info_comunicado) && $info_comunicado[0]->Otro_destinatario === 1){
                        if(strtolower($info_comunicado[0]->Destinatario) === $Tipo_correspondencia && $info_comunicado[0]->JRCI_Destinatario && is_numeric($info_comunicado[0]->JRCI_Destinatario)){
                            $datos_jrci_otro_destinatario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                            ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Emails as Email_destinatario', 'sie.Telefonos as Telefono_destinatario', 
                            'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario','slp.Nombre_parametro as Medio_notificacion_destinatario')
                            ->where([['Id_Entidad', $info_comunicado[0]->JRCI_Destinatario]])
                            ->get();
                            $response['datos'] = count($datos_jrci_otro_destinatario) > 0 ? $datos_jrci_otro_destinatario[0] : null;
                        }
                    }
                }
                else if($Tipo_correspondencia === 'jnci' && !empty($infoAfiliado)){
                    $datos_jnci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                    ->select('sie.Nombre_entidad as Nombre_destinatario', 
                        'sie.Direccion as Direccion_destinatario', 
                        'sie.Telefonos as Telefono_destinatario',
                        'sie.Emails as Email_destinatario',
                        'sldm.Nombre_departamento as Departamento_destinatario',
                        'sldm1.Nombre_municipio as Ciudad_destinatario',
                        'slp.Nombre_parametro as Medio_notificacion_destinatario'
                    )->where([
                        ['sie.IdTipo_entidad', 5],['sie.Id_Entidad',111]
                    ])->limit(1)->get();
                    $response['datos'] = count($datos_jnci) > 0 ? $datos_jnci[0] : null;
                }
                else if($Tipo_correspondencia === 'afp_conocimiento' && !empty($infoAfiliado)){
                    $si_entidad_conocimiento = $infoAfiliado[0]->Entidad_conocimiento;

                    if ($si_entidad_conocimiento == "Si") {
                        $id_afp_conocimiento = $infoAfiliado[0]->Id_afp_entidad_conocimiento;

                        $datos_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sie.Id_Medio_Noti')
                        ->select('sie.Nombre_entidad as Nombre_destinatario', 'sie.Direccion as Direccion_destinatario', 'sie.Emails as Email_destinatario','sie.Telefonos as Telefono_destinatario', 
                        'sie.Otros_Telefonos', 'sldm.Nombre_departamento as Departamento_destinatario', 'sldm2.Nombre_municipio as Ciudad_destinatario','slp.Nombre_parametro as Medio_notificacion_destinatario')
                        ->where([['sie.Id_Entidad', $id_afp_conocimiento]])
                        ->get();
                        $response['datos'] = count($datos_afp_conocimiento) > 0 ? $datos_afp_conocimiento[0] : null;
                    }
                }
                
                return response()->json($response);
            }
        }
        else{
            $info_correspondencia = sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')
            ->where([['Id_comunicado', $Id_comunicado],['Tipo_correspondencia', $Tipo_correspondencia]])
            ->get();

            return response()->json($info_correspondencia);
        }
    }

    public function guardarInformacionCorrespondencia(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $id_evento = $request->id_evento;
        $id_asignacion = $request->id_asignacion;
        $id_proceso = $request->id_proceso;
        $id_comunicado = $request->id_comunicado;
        $correspondencia = $request->correspondencia;
        $tipo_correspondencia = $request->tipo_correspondencia;
        $accion = $request->accion;
        $id_correspondencia = $request->id_correspondencia;
        
        $info_asignacion_eventos = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->select('Id_servicio')
        ->where([['Id_Asignacion',$id_asignacion]])
        ->first();
        $id_servicio = $info_asignacion_eventos ? $info_asignacion_eventos->Id_servicio : null;

        if (!empty($correspondencia)) {
            $correspondencia_guardada = implode(", ", $correspondencia);                
        }else{
            $correspondencia_guardada = '';
        }
        if($accion === 'Guardar'){
            $datos_info_correspondencia= [
                'ID_evento' => $id_evento,
                'Id_Asignacion' => $id_asignacion,
                'Id_proceso' => $id_proceso,
                'Id_servicio' => $id_servicio,
                'Id_comunicado' => $id_comunicado,
                'Nombre_afiliado' => $request->nombre_afiliado,
                'N_identificacion' => $request->n_identificacion_afiliado,
                'N_radicado' => $request->n_radicado,
                'N_orden' => $request->n_orden,
                'Tipo_destinatario' => $request->tipo_destinatario,
                'Nombre_destinatario' => $request->nombre_destinatario,
                'Direccion_destinatario' => $request->direccion_destinatario,
                'Departamento' => $request->departamento_destinatario,
                'Ciudad' => $request->ciudad_destinatario,
                'Telefono_destinatario' => $request->telefono_destinatario,
                'Email_destinatario' => $request->email_destinatario,
                'Medio_notificacion' => $request->medio_notificacion_destinatario,
                'N_guia' => $request->n_guia,
                'Folios' => $request->folios,
                'F_envio' => $request->fecha_envio,
                'F_notificacion' => $request->fecha_notificacion,
                'Id_Estado_corresp' => $request->estado_notificacion,
                'tipo_correspondencia' => $tipo_correspondencia,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];
            sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->insert($datos_info_correspondencia);
            
            //Actualizamos en los comunicados los destinatarios a los que se les ha guardado correspondencia
            $datos_info_comunicado = [
                'Correspondencia' => $correspondencia_guardada
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado',$id_comunicado)->update($datos_info_comunicado);


            $mensajes = array(
                "parametro" => 'agregar_correspondencia',
                "mensaje" => 'Correspondencia guardada satisfactoriamente.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        else{
            $datos_info_correspondencia= [
                'ID_evento' => $id_evento,
                'Id_Asignacion' => $id_asignacion,
                'Id_proceso' => $id_proceso,
                'Id_servicio' => $id_servicio,
                'Id_comunicado' => $id_comunicado,
                'Nombre_afiliado' => $request->nombre_afiliado,
                'N_identificacion' => $request->n_identificacion_afiliado,
                'N_radicado' => $request->n_radicado,
                'N_orden' => $request->n_orden,
                'Tipo_destinatario' => $request->tipo_destinatario,
                'Nombre_destinatario' => $request->nombre_destinatario,
                'Direccion_destinatario' => $request->direccion_destinatario,
                'Departamento' => $request->departamento_destinatario,
                'Ciudad' => $request->ciudad_destinatario,
                'Telefono_destinatario' => $request->telefono_destinatario,
                'Email_destinatario' => $request->email_destinatario,
                'Medio_notificacion' => $request->medio_notificacion_destinatario,
                'N_guia' => $request->n_guia,
                'Folios' => $request->folios,
                'F_envio' => $request->fecha_envio,
                'F_notificacion' => $request->fecha_notificacion,
                'Id_Estado_corresp' => $request->estado_notificacion,
                'tipo_correspondencia' => $tipo_correspondencia,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];
            sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->where('Id_Correspondencia',$id_correspondencia)->update($datos_info_correspondencia);
            
            //Actualizamos en los comunicados los destinatarios a los que se les ha guardado correspondencia
            $datos_info_comunicado = [
                'Correspondencia' => $correspondencia_guardada
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado',$id_comunicado)->update($datos_info_comunicado);


            $mensajes = array(
                "parametro" => 'agregar_correspondencia',
                "mensaje" => 'Correspondencia actualizada satisfactoriamente.'
            );
            return json_decode(json_encode($mensajes, true));
        }
    }
}
