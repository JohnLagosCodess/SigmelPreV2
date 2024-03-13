<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
//llamado de modelos para formulario BandejaOrigen y captura de data
use App\Models\sigmel_lista_procesos_servicios;
use App\Models\cndatos_bandeja_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;

use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;
use App\Models\sigmel_informacion_historial_accion_eventos;

class BandejaOrigenController extends Controller
{
    // Bandeja Origen Coordinador
    public function mostrarVistaBandejaOrigen(){
        if(!Auth::check()){
            return redirect('/');
        }
        $id_usuario = Auth::id();
        $email_usuario = Auth::user()->email;

        $datos = DB::table('sigmel_roles as sr')
                        ->leftJoin("sigmel_usuarios_roles as sur", 'sr.id', '=', 'sur.rol_id') 
                        ->leftJoin("users as u", 'u.id', '=', 'sur.usuario_id' ) 
                        ->select("sr.nombre_rol")
                        ->where([
                            ['u.id', '=', $id_usuario],
                            ['u.email', '=', $email_usuario],
                        ])
                        ->get();

        $informacion_usuario = json_decode(json_encode($datos), true);
        if (count($informacion_usuario) > 0) {
            $rol_usuario = $informacion_usuario[0]['nombre_rol'];
            
            $user = Auth::user();
            $user->rol_usuario = $rol_usuario;
            return view('coordinador.bandejaOrigen', compact('user'));

        }else{
            return redirect()->route('login');
        }
    }

    //Selectores Bandeja Origen
    public function cargueListadoSelectoresBandejaOrigen(Request $request){
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

        //Listado servicio proceso Origen
        if($parametro == 'lista_servicios_origen'){
            $listado_servicio_Origen = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                // ['Nombre_proceso', '=', 'Origen'],
                ['Id_proceso', '=', $request->id_proceso],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_Origen = json_decode(json_encode($listado_servicio_Origen, true));
            return response()->json($info_listado_servicio_Origen);
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

        if ($parametro == 'lista_profesional_origen') {
            
            $listado_profesional_origen = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(1, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_Origen = json_decode(json_encode($listado_profesional_origen, true));
            return response()->json($info_listado_profesional_Origen);
        }

    }

    public function sinFiltroBandejaOrigen(Request $request){

        $BandejaOrigenTotal = $request->BandejaOrigenTotal;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user;     

        if($BandejaOrigenTotal == 'CargaBandejaOrigen'){

            // Consultar la vista de mysql, traer eventos acorde al proceso
            if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'){ // si el rol es analista o profesional o comité
                $bandejaOrigen = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where([
                    ['Nombre_proceso_actual', '=', 'Origen'],
                    ['Id_profesional', '=', $newId_user]
                ])
                ->get();
            }else{
                $bandejaOrigen = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where([
                    ['Nombre_proceso_actual', '=', 'Origen']
                ])
                ->get();
            }
            // $ID_evento_bandeja = $bandejaOrigen[0]->ID_evento;
            // $Id_proceso_bandeja = $bandejaOrigen[0]->Id_proceso;

            // Json vacio para llenado en Else en caso de que no haya un proceso anterior            
            //$Ids_Nombre_proceso_anterior = response()->json([]);

            // validacion de la vista

            // if (!empty($bandejaOrigen[0]->Nombre_proceso_actual)) {
            //     $datos_bandejaOrigen = [];
            //     foreach ($bandejaOrigen as $item) {
            //         // Accede a cada propiedad del objeto dentro del bucle  para capturar el id_asignacion                  
            //         $datos_bandejaOrigen[]=[                        
            //             'Id_Asignacion_bandeja' => $item->Id_Asignacion,
            //         ];
            //     }    
            //     // echo '<pre>';
            //     //     print_r($datos_bandejaOrigen);
            //     // echo '</pre>';             
            //     // calcular cantidad de id_asignacion 
            //     $cantidad_Id_Asignacion = count($datos_bandejaOrigen);
            //     //$Id_Asignacion_cantidad = $cantidad_Id_Asignacion; 
            //     // if ($cantidad_Id_Asignacion == 1) {                    
            //     // } else {
            //     //     # code...
            //     // }
            //     //$cantidad_Id_Asignacion += 1; 
                
            //     //echo $cantidad_Id_Asignacion;

            //     // Validar si existe un porceso anterior con los Id_Asignacion del array $datos_bandejaPCl

            //     $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            //     ->select('Id_proceso', 'Id_Asignacion')
            //     ->where('ID_evento', $ID_evento_bandeja);
            //     foreach ($datos_bandejaOrigen as $dato) {
            //         $validar_proceso_anterior->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja']);
            //     }
            //     // foreach ($datos_bandejaOrigen as $dato) {
            //     //     $validar_proceso_anterior->orWhere(function ($query) use ($dato) {
            //     //         $query->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja']);
            //     //     });
            //     // }
            //     $validar_proceso_anterior = $validar_proceso_anterior
            //     ->orderBy('Id_Asignacion', 'desc')
            //     ->limit($cantidad_Id_Asignacion)
            //     ->get();
            //     // echo '<pre>';
            //     //     print_r($validar_proceso_anterior);
            //     // echo '</pre>';
            //     // echo count($validar_proceso_anterior);
            //     // Si se cumple la validacion anterior entra al IF Si no entra al Else y Utiliza el Json

            //     if (count($validar_proceso_anterior) > 0) {
            //         //echo 'IF';
            //         // Se construyen los id del proceso y asignacion
            //         $proceso_id = [];
            //         $asignacion_id = [];
            //         foreach ($validar_proceso_anterior as $key) {
            //             $proceso_id[]=[                        
            //                 'Id_Proceso_anterior' => $key->Id_proceso
            //             ];    
            //             $asignacion_id[] = [
            //                 'Id_Asignacion_bandeja' => $key->Id_Asignacion
            //             ];
            //         } 

            //         // Validar el nombre del proceso anterior en base a los id_procesos optenidos en el array  $proceso_id

            //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         ->select('Nombre_proceso', 'Id_proceso')->whereIn('Id_proceso', $proceso_id)
            //         ->groupBy('Nombre_proceso')
            //         ->get();  
            //         // construir array para los nombres de los procesos
            //         $nombre_proceso = [];
            //         foreach ($validar_Nombre_proceso_anterior as $value) {
            //             $nombre_proceso[] = [
            //                 'Nombre_proceso_anterior' => $value->Nombre_proceso,
            //                 'Id_proceso' => $value->Id_proceso,
            //             ];
            //         }   

            //         // acondicionar los array acorde al order la consulta Inicial de $bandejaPCL
            //         array_shift($proceso_id);                    
            //         $orden_proceso_id = array_reverse($proceso_id);
            //         array_pop($asignacion_id);
            //         $orden_asignacion_id = array_reverse($asignacion_id);

            //         //Combinar los array de  orden_proceso_id y nombre_proceso acorde al proceso
            //         foreach ($orden_proceso_id as $key => $value) {
            //             foreach ($nombre_proceso as $item) {
            //                 if ($value['Id_Proceso_anterior'] == $item['Id_proceso']) {
            //                     $orden_proceso_id[$key]['Nombre_proceso_anterior'] = $item['Nombre_proceso_anterior'];
            //                     break;
            //                 }
            //             }
            //         }

            //         // combinar array anterior orden_proceso_id con el array orden_asignacion_id 
            //         $combinar_proceso_asignacion = array();
            //         foreach ($orden_asignacion_id as $key => $valor) {
            //             if (isset($orden_proceso_id[$key])) {
            //                 // Fusionar arrays
            //                 $combinar_proceso_asignacion[] = array_merge($orden_proceso_id[$key], $valor);
            //             }
            //         } 

            //         // se convierte el array  $bandejaOrigen a un object
            //         $arraybandejaOrigen = json_decode(json_encode($bandejaOrigen, true));
            //         // Combinar el array object con el array combinar_proceso_asignacion
            //         foreach ($arraybandejaOrigen as $key2 => $value2) {
            //             foreach ($combinar_proceso_asignacion as $value1) {
            //                 // Verifica si los Id_Asignacion coinciden
            //                 if ($value2->Id_Asignacion == $value1['Id_Asignacion_bandeja']) {
            //                     // Agrega las propiedades al segundo array
            //                     $arraybandejaOrigen[$key2]->Id_Proceso_anterior = $value1['Id_Proceso_anterior'];
            //                     $arraybandejaOrigen[$key2]->Nombre_proceso_anterior = $value1['Nombre_proceso_anterior'];
            //                 }
            //             }
            //         }
            //     } else {
            //         //echo 'ELSE';

            //         // Validar el nombre del proceso que viene siendo el mismo ya que no hay uno anterior                
            //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         ->select('Nombre_proceso')->where([['Id_proceso', $Id_proceso_bandeja]])
            //         ->limit(1)->get(); 
            //         // Se alimenta el Json Vacio
            //         $Ids_Nombre_proceso_anterior = response()->json([
            //             'Id_Proceso_anterior' => $Id_proceso_bandeja,
            //             'Nombre_proceso_anterior' => $validar_Nombre_proceso_anterior[0]->Nombre_proceso,
            //         ]);
            //         // Se Captura los valosres del json y se agregan al object $arraybandejaOrigen
            //         $Ids_Nombre_proceso_anterior_array = json_decode($Ids_Nombre_proceso_anterior->getContent(), true);                    
            //         $arraybandejaOrigen = json_decode(json_encode($bandejaOrigen, true));    
            //         $arraybandejaOrigen[0]->Id_Proceso_anterior = $Ids_Nombre_proceso_anterior_array['Id_Proceso_anterior'];
            //         $arraybandejaOrigen[0]->Nombre_proceso_anterior = $Ids_Nombre_proceso_anterior_array['Nombre_proceso_anterior'];
                    
            //     }
            // }

            $arraybandejaOrigen = json_decode(json_encode($bandejaOrigen, true));                                                 
            return response()->json($arraybandejaOrigen);                

        }
    }

    public function filtrosBandejaOrigen(Request $request){
        
        $consultar_f_desde = $request->consultar_f_desde;
        $consultar_f_hasta = $request->consultar_f_hasta;
        $consultar_g_dias = $request->consultar_g_dias;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user; 
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                     // Consultar la vista de mysql, traer eventos acorde al proceso
                    if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'){ // si el rol es analista o profesional o comité
                        $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                            ['Id_profesional', '=', $newId_user]
                        ])
                        ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                        ->get();
                    }else{
                        $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                        ])
                        ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                        ->get();
                    }
                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);
                    
                    // $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Origen'],
                    //         ['Id_proceso_anterior', '<>', 1],
                    //         ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    //     ])            
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    // ->union($bandejaOrigensin_Pro_ant)
                    // ->get();
            
                    $arraybandejaOrigenFiltros = json_decode(json_encode($bandejaOrigenFiltros, true));
                    if (count($arraybandejaOrigenFiltros)>0) {
                        return response()->json($arraybandejaOrigenFiltros);                        
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
                        $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                            ['Id_profesional', '=', $newId_user]
                        ])
                        ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                        ->get();
                    }else{
                        $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                        ])
                        ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                        ->get();
                    }
                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);

                    // $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Origen'],
                    //         ['Id_proceso_anterior', '<>', 1],
                    //     ])            
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    // ->union($bandejaOrigensin_Pro_ant)
                    // ->get();                    

                    $arraybandejaOrigenFiltros = json_decode(json_encode($bandejaOrigenFiltros, true));
                    if (count($arraybandejaOrigenFiltros)>0) {
                        return response()->json($arraybandejaOrigenFiltros);
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
                        $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                            ['Id_profesional', '=', $newId_user]
                        ])
                        ->get();
                    }else{
                        $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                        ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ])
                        ->get();

                    }
                    // ->whereNull('Nombre_proceso_anterior');
                    
                    // $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Origen'],
                    //         ['Id_proceso_anterior', '<>', 1],
                    //         ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    //     ])            
                    // ->union($bandejaOrigensin_Pro_ant)
                    // ->get();
                
                    $arraybandejaOrigenFiltros = json_decode(json_encode($bandejaOrigenFiltros, true));
                    if (count($arraybandejaOrigenFiltros)>0) {
                        return response()->json($arraybandejaOrigenFiltros);
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
    
    public function actualizarBandejaOrigen(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $usuario = Auth::user()->name;        
        $time = time();
        $date = date("Y-m-d", $time);
        $date_con_hora = date("Y-m-d h:i:s", $time);
        $date_time = date("Y-m-d H:i:s");

        $IdEventoBandejaOrigen = $request->array;
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
        for ($a=0; $a < count($IdEventoBandejaOrigen); $a++) { 
            $array_ids = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('Id_proceso', 'Id_servicio')
            ->where('Id_Asignacion', $IdEventoBandejaOrigen[$a])->get();
 
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
        for ($m=0; $m < count($IdEventoBandejaOrigen); $m++) {
            switch (true) {
                // CASO 1: Id asignacion no es vacio y id profesional no es vacio y id servicio no es vacio
                case (!empty($IdEventoBandejaOrigen) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
            
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

                    $actualizar_bandejaOrigen = [
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

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaOrigen);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
                
                    // return json_decode(json_encode($mensajes, true));
                break;
                // CASO 2: Id asignacion no es vacio y id profesional es vacio y id servicio no es vacio
                case (!empty($IdEventoBandejaOrigen) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
    
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

                    $actualizar_bandejaOrigen_Servicio = [
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

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaOrigen_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
    
                    // return json_decode(json_encode($mensajes, true));
                break;
                // CASO 3: Id asignacion no es vacio y id profesional no es vacio y id servicio es vacio
                case (!empty($IdEventoBandejaOrigen) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
    
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

                    $actualizar_bandejaOrigen_Profesional = [
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

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaOrigen_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    // return json_decode(json_encode($mensajes, true));
                break;
                // CASO 4: Id asignacion no es vacio y id profesional es vacio y id servicio es vacio
                case (!empty($IdEventoBandejaOrigen) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_Origen',
                        "mensaje" => 'Debe seleccionar el Profesional o Redireccionar a, para Actualizar'
                    );

                    // return json_decode(json_encode($mensajes, true));
                break;
                
                default:                
                break;
            }
        };

        // Paso N° 5: Actualización de la información
        for ($b=0; $b < count($array_datos_finales_actualizar); $b++) { 
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $IdEventoBandejaOrigen[$b])
            ->update($array_datos_finales_actualizar[$b]);
        }
        $array_datos_finales_actualizar = [];

        sleep(2);
        
        // Capturar todos los eventos de los id de asignacion y almacenarlo en array array_id_eventos
        $array_id_eventos = [];
        for ($a=0; $a < count($IdEventoBandejaOrigen); $a++) {
            $array_datos_eventos = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('ID_evento')->where('Id_Asignacion', $IdEventoBandejaOrigen[$a])->get();
            $info_array_eventos = json_decode(json_encode($array_datos_eventos, true));
            array_push($array_id_eventos, $info_array_eventos[0]->ID_evento);
        }

        // Captura de informacion de los campos de la bandeja que se van actualizar

        $datos_historial_accion_eventos = [
            'Id_proceso' => $Id_proceso,
            'Id_servicio' => $Id_Servicio_redireccionar,
            'Id_accion' => $Id_accion,
            'Descripcion' => $Descripcion_bandeja,
            'F_accion' => $date_time,
            'Nombre_usuario' => $usuario,
        ];
       
        // Construir array para la insercion
        $array_datos_historial_accion_eventos = array();
        foreach ($array_id_eventos as $evento) {
            $item = array_merge(array('ID_evento' => $evento), $datos_historial_accion_eventos);
            $array_datos_historial_accion_eventos[] = $item;
        }

        foreach ($array_datos_historial_accion_eventos as $historial) {
            sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
            ->insert($historial);
        }
        return json_decode(json_encode($mensajes, true));

        /* $profesional = DB::table('users')
        ->select('name')->where('id',$Id_profesional)
        ->get();
        if (count($profesional) > 0) {
            $nombre = json_decode(json_encode($profesional));
            $nombre_profesional= $nombre[0]->name; 

            $actualizar_bandejaOrigen = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional,
                'Id_servicio' => $Id_Servicio_redireccionar
            ];       

            $actualizar_bandejaOrigen_Profesional = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional
            ]; 
        }else{
            $actualizar_bandejaOrigen_Servicio = [
                'Nombre_usuario' => $usuario,
                'Id_servicio' => $Id_Servicio_redireccionar
            ]; 
        }
        
        switch (true) {
            case (!empty($IdEventoBandejaOrigen) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
        
                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaOrigen)
                    ->update($actualizar_bandejaOrigen);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
            
                    return json_decode(json_encode($mensajes, true));
                
            break;
            case (!empty($IdEventoBandejaOrigen) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaOrigen)
                    ->update($actualizar_bandejaOrigen_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;
            
            case (!empty($IdEventoBandejaOrigen) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaOrigen)
                    ->update($actualizar_bandejaOrigen_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;

            case (!empty($IdEventoBandejaOrigen) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_Origen',
                        "mensaje" => 'Debe seleccionar el Profesional o Redireccionar a, para Actualizar'
                    );

                    return json_decode(json_encode($mensajes, true));
            break;
            
            default:                
            break;
        } */
        
    }

}
