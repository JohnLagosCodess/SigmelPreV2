<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use App\Models\sigmel_lista_procesos_servicios;
use App\Models\cndatos_bandeja_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_numero_orden_eventos;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;

use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;

class BandejaNotifiController extends Controller
{
    // Bandeja Notifiacion Coordinador
    public function mostrarVistaBandejaNotifi(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();    
        // consulta numero de orden
        $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
        ->select('Numero_orden')
        ->get();
        return view('coordinador.bandejaNotifi', compact('user','n_orden'));
    }

    //Selectores Bandeja Notifi
    public function cargueListadoSelectoresBandejaNotifi(Request $request){
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
        
        //Listado servicio proceso Notifi
        if($parametro == 'lista_servicios_notifi'){
            $listado_servicio_Notifi = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                // ['Nombre_proceso', '=', 'Notificaciones'],
                ['Id_proceso', '=', $request->id_proceso],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_Notifi = json_decode(json_encode($listado_servicio_Notifi, true));
            return response()->json($info_listado_servicio_Notifi);
        }


        // listado de profesionales para el proceso notificaciones
        if ($parametro == 'lista_profesional_notifi') {
            
            $listado_profesional_notifi = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(4, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_Notifi = json_decode(json_encode($listado_profesional_notifi, true));
            return response()->json($info_listado_profesional_Notifi);
        }

    }

    public function sinFiltroBandejaNotifi(Request $request){

        $BandejaNotifiTotal = $request->BandejaNotifiTotal;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user;     

        $time = time();
        $date = date("Y-m-d", $time);
        $year = date("Y");  

        if($BandejaNotifiTotal == 'CargaBandejaNotifi'){

            if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                $bandejaNotifi = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where('Id_profesional', '=', $newId_user)->where(function($query){
                    $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                })
                //->whereBetween('F_registro_asignacion', [$year.'-01-01' , $date])
                ->get();
                //dd( $newId_user);
            }else{
                $bandejaNotifi = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where('Enviar_bd_Notificacion', '=', 'Si')
                //->whereBetween('F_registro_asignacion', [$year.'-01-01' , $date])
                ->get();

            }
            // $bandejaNotifisin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
            // ->where([
            //     ['Enviar_bd_Notificacion', '=', 'Si'],
            // ])
            // ->whereNull('Nombre_proceso_anterior');

            // $bandejaNotifi = cndatos_bandeja_eventos::on('sigmel_gestiones')
            // ->where([
            //     ['Enviar_bd_Notificacion', '=', 'Si'],,
            //     ['Id_proceso_anterior', '<>', 4]
            // ])
            // ->union($bandejaNotifisin_Pro_ant)
            // ->get();

            // $Ids_Nombre_proceso_anterior = response()->json([]);
            
            // foreach ($bandejaOrigen as $item) {
            //     // Accede a cada propiedad del objeto dentro del bucle
            //     $Id_Asignacion_bandeja = $item->Id_Asignacion;
            //     $ID_evento_bandeja = $item->ID_evento;
            //     $Id_proceso_bandeja = $item->Id_proceso;

            //     $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            //     ->select('Id_proceso')
            //     ->where([['ID_evento', $ID_evento_bandeja], ['Id_Asignacion', '<', $Id_Asignacion_bandeja]])
            //     ->orderBy('Id_Asignacion', 'desc')
            //     ->limit(1)
            //     ->get();
            //     //echo $validar_proceso_anterior[0]->Id_proceso;
            //     if (count($validar_proceso_anterior) > 0) {
            //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         ->select('Nombre_proceso')->where([['Id_proceso', $validar_proceso_anterior[0]->Id_proceso]])
            //         ->limit(1)->get();    
            //         //echo $validar_Nombre_proceso_anterior[0]->Nombre_proceso;    
            //         $Ids_Nombre_proceso_anterior = response()->json([
            //             'Id_Proceso_anterior' => $validar_proceso_anterior[0]->Id_proceso,
            //             'Nombre_proceso_anterior' => $validar_Nombre_proceso_anterior[0]->Nombre_proceso,
            //         ]);
    
            //         $Ids_Nombre_proceso_anterior_array = json_decode($Ids_Nombre_proceso_anterior->getContent(), true);
                    
            //         $arraybandejaNotifi = json_decode(json_encode($bandejaOrigen, true));
    
            //         $arraybandejaNotifi[0]->Id_Proceso_anterior = $Ids_Nombre_proceso_anterior_array['Id_Proceso_anterior'];
            //         $arraybandejaNotifi[0]->Nombre_proceso_anterior = $Ids_Nombre_proceso_anterior_array['Nombre_proceso_anterior'];
                    
            //     } else {
            //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         ->select('Nombre_proceso')->where([['Id_proceso', $Id_proceso_bandeja]])
            //         ->limit(1)->get(); 

            //         $Ids_Nombre_proceso_anterior = response()->json([
            //             'Id_Proceso_anterior' => $Id_proceso_bandeja,
            //             'Nombre_proceso_anterior' => $validar_Nombre_proceso_anterior[0]->Nombre_proceso,
            //         ]);
    
            //         $Ids_Nombre_proceso_anterior_array = json_decode($Ids_Nombre_proceso_anterior->getContent(), true);
                    
            //         $arraybandejaNotifi = json_decode(json_encode($bandejaOrigen, true));
    
            //         $arraybandejaNotifi[0]->Id_Proceso_anterior = $Ids_Nombre_proceso_anterior_array['Id_Proceso_anterior'];
            //         $arraybandejaNotifi[0]->Nombre_proceso_anterior = $Ids_Nombre_proceso_anterior_array['Nombre_proceso_anterior'];
                    
            //     }
            // }
            $arraybandejaNotifi = json_decode(json_encode($bandejaNotifi, true));
            return response()->json($bandejaNotifi);

        }
    }

    public function filtrosBandejaNotifi(Request $request){
        
        $consultar_f_desde = $request->consultar_f_desde;
        $consultar_f_hasta = $request->consultar_f_hasta;
        $consultar_g_dias = $request->consultar_g_dias;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user; 
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ['Id_profesional', '=', $newId_user]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                }else{
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where('Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias)->where(function($query){
                        $query->where('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                }
                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);
                    
                    // $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Enviar_bd_Notificacion', '=', 'Si'],,
                    //         ['Id_proceso_anterior', '<>', 4],
                    //         ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    //     ])            
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    // ->union($bandejaNotifisin_Pro_ant)
                    // ->get();
            
                    $arraybandejaNotifiFiltros = json_decode(json_encode($bandejaNotifiFiltros, true));
                    if (count($arraybandejaNotifiFiltros)>0) {
                        return response()->json($arraybandejaNotifiFiltros);                        
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
                    
                if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where('Id_profesional', '=', $newId_user)->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                
                }else{
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where('Enviar_bd_Notificacion', '=', 'Si')
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                }

                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);

                    // $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Enviar_bd_Notificacion', '=', 'Si'],,
                    //         ['Id_proceso_anterior', '<>', 4],
                    //     ])            
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    // ->union($bandejaNotifisin_Pro_ant)
                    // ->get();                    

                    $arraybandejaNotifiFiltros = json_decode(json_encode($bandejaNotifiFiltros, true));
                    if (count($arraybandejaNotifiFiltros)>0) {
                        return response()->json($arraybandejaNotifiFiltros);
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
                    
                if($newId_rol=='5' || $newId_rol=='9' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ['Id_profesional', '=', $newId_user]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->get();
                }else{
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where('Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias)->where(function($query){
                        $query->where('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->get();

                }
                    // ->whereNull('Nombre_proceso_anterior');
                    
                    // $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Enviar_bd_Notificacion', '=', 'Si'],,
                    //         ['Id_proceso_anterior', '<>', 4],
                    //         ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    //     ])            
                    // ->union($bandejaNotifisin_Pro_ant)
                    // ->get();
                
                    $arraybandejaNotifiFiltros = json_decode(json_encode($bandejaNotifiFiltros, true));
                    if (count($arraybandejaNotifiFiltros)>0) {
                        return response()->json($arraybandejaNotifiFiltros);
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

    //No se esta usando actualmente
    public function actualizarBandejaNotifi(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $usuario = Auth::user()->name;        
        $time = time();
        $date_con_hora = date("Y-m-d h:i:s", $time);

        $IdEventoBandejaNotifi = $request->array;
        $Id_proceso = $request->json['proceso_parametrizado'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];
        $Id_accion = $request->json['accion'];
        $Id_profesional = $request->json['profesional'];

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
        for ($a=0; $a < count($IdEventoBandejaNotifi); $a++) { 
            $array_ids = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('Id_proceso', 'Id_servicio')
            ->where('Id_Asignacion', $IdEventoBandejaNotifi[$a])->get();

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
        for ($m=0; $m < count($IdEventoBandejaNotifi); $m++) {
            switch (true) {
                // CASO 1: Id asignacion no es vacio y id profesional no es vacio y id servicio no es vacio
                case (!empty($IdEventoBandejaNotifi) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
            
                    $actualizar_bandejaNotifi = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'F_asignacion_calificacion' => $F_asignacion_calificacion,
                        'Id_profesional' =>  $Id_profesional,
                        'Nombre_profesional' => $nombre_profesional,
                        'Nombre_usuario' => $usuario
                    ];

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaNotifi);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Notifi',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
            
                    // return json_decode(json_encode($mensajes, true));
                    
                break;
                // CASO 2: Id asignacion no es vacio y id profesional es vacio y id servicio no es vacio
                case (!empty($IdEventoBandejaNotifi) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
    
                    $actualizar_bandejaNotifi_Servicio = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'Nombre_usuario' => $usuario,
                    ]; 

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaNotifi_Servicio);
    
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Notifi',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
    
                    // return json_decode(json_encode($mensajes, true));
    
                break;
                // CASO 3: Id asignacion no es vacio y id profesional no es vacio y id servicio es vacio
                case (!empty($IdEventoBandejaNotifi) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
    
                    $actualizar_bandejaNotifi_Profesional = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'F_asignacion_calificacion' => $F_asignacion_calificacion,
                        'Id_profesional' =>  $Id_profesional,
                        'Nombre_profesional' => $nombre_profesional,
                        'Nombre_usuario' => $usuario
                    ];

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaNotifi_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Notifi',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    // return json_decode(json_encode($mensajes, true));
    
                break;
                // CASO 4: Id asignacion no es vacio y id profesional es vacio y id servicio es vacio
                case (!empty($IdEventoBandejaNotifi) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_Notifi',
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
            ->where('Id_Asignacion', $IdEventoBandejaNotifi[$b])
            ->update($array_datos_finales_actualizar[$b]);
        }
        $array_datos_finales_actualizar = [];
        return json_decode(json_encode($mensajes, true));

        // $profesional = DB::table('users')
        // ->select('name')->where('id',$Id_profesional)
        // ->get();
        // if (count($profesional) > 0) {
        //     $nombre = json_decode(json_encode($profesional));
        //     $nombre_profesional= $nombre[0]->name; 
            
        // }else{
            
        // }
        
        
        
    }

    public function alertaNaranjasRojasOrigen(Request $request) {
        $alertas = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
        ->where([['Estado_alerta_automatica', '=', 'Ejecucion']])
        ->get();
        return response()->json(['data' => $alertas]);
    }

    //Obtener informacion relacionada a los eventos de notificacion
    public function infomacionEnventosNotifiacion(Request $request){

        if($request->bandera == 'info_evento'){
            $request->validate([
                'bandera' => 'required',
                'id_asignacion' => 'required',
                'evento' => 'required'
            ]);

            $info_evento = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->select('Notificacion')->where([
                ['Id_Asignacion',$request->id_asignacion],
                ['ID_evento',$request->evento]
            ])->get();

            $info_evento = json_decode(json_encode($info_evento, true));
            return response()->json($info_evento);
        }
    }
}
