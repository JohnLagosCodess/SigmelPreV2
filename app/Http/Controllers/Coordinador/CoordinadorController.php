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
        
        if ($parametro == 'lista_profesional_pcl') {
            
            $listado_profesional_pcl = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(2, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_PCL = json_decode(json_encode($listado_profesional_pcl, true));
            return response()->json($info_listado_profesional_PCL);
        }

        //Listado servicio calificacion PCL
        if($parametro == 'lista_servicios_pcl'){
            $listado_servicio_PCl = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                ['Nombre_proceso', '=', 'Calificación PCL'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_PCl = json_decode(json_encode($listado_servicio_PCl, true));
            return response()->json($info_listado_servicio_PCl);
        }
    }

    public function sinFiltroBandejaPCL(Request $request){

        $BandejaPClTotal = $request->BandejaPClTotal;        

        if($BandejaPClTotal == 'CargaBandejaPCl'){
            // Consultar la vista de mysql, traer eventos acorde al proceso
            $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Calificación PCL']
            ])
            ->get();  
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
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                    // Consultar la vista de mysql, traer eventos acorde al proceso
                    $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                    ])
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get(); 
                    
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
                    $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                    ])
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get(); 
                    
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
                    $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                    ])                    
                    ->get(); 
                    
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
        //print_r($request->json);
        $IdEventoBandejaPCl = $request->array;
        $Id_profesional = $request->json['profesional'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];

        $profesional = DB::table('users')
        ->select('name')->where('id',$Id_profesional)
        ->get();
        
        if (count($profesional) > 0) {
            $nombre = json_decode(json_encode($profesional));
            $nombre_profesional= $nombre[0]->name; 

            $actualizar_bandejaPCL = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional,
                'Id_servicio' => $Id_Servicio_redireccionar
            ];       

            $actualizar_bandejaPCL_Profesional = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional
            ]; 
        }else{
            $actualizar_bandejaPCL_Servicio = [
                'Nombre_usuario' => $usuario,
                'Id_servicio' => $Id_Servicio_redireccionar
            ]; 
        }   
        switch (true) {
            case (!empty($IdEventoBandejaPCl) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
        
                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaPCl)
                    ->update($actualizar_bandejaPCL);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
            
                    return json_decode(json_encode($mensajes, true));
                
            break;
            case (!empty($IdEventoBandejaPCl) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaPCl)
                    ->update($actualizar_bandejaPCL_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;
            
            case (!empty($IdEventoBandejaPCl) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaPCl)
                    ->update($actualizar_bandejaPCL_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;

            case (!empty($IdEventoBandejaPCl) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_PCL',
                        "mensaje" => 'Debe seleccionar el Profesional o Redireccionar a, para Actualizar'
                    );

                    return json_decode(json_encode($mensajes, true));
            break;
            
            default:                
            break;
        }
        
    }
}
