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
        
        if ($parametro == 'lista_profesional_notifi') {
            
            $listado_profesional_notifi = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(4, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_Notifi = json_decode(json_encode($listado_profesional_notifi, true));
            return response()->json($info_listado_profesional_Notifi);
        }

        //Listado servicio proceso Notifi
        if($parametro == 'lista_servicios_notifi'){
            $listado_servicio_Notifi = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                ['Nombre_proceso', '=', 'Notificaciones'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_Notifi = json_decode(json_encode($listado_servicio_Notifi, true));
            return response()->json($info_listado_servicio_Notifi);
        }
    }

    public function sinFiltroBandejaNotifi(Request $request){

        $BandejaNotifiTotal = $request->BandejaNotifiTotal;        

        if($BandejaNotifiTotal == 'CargaBandejaNotifi'){

            $bandejaNotifi = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Notificaciones']
            ])
            ->get();

            // $bandejaNotifisin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
            // ->where([
            //     ['Nombre_proceso_actual', '=', 'Notificaciones']
            // ])
            // ->whereNull('Nombre_proceso_anterior');

            // $bandejaNotifi = cndatos_bandeja_eventos::on('sigmel_gestiones')
            // ->where([
            //     ['Nombre_proceso_actual', '=', 'Notificaciones'],
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
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Notificaciones'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();

                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);
                    
                    // $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Notificaciones'],
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
                    
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Notificaciones'],
                    ])
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();


                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);

                    // $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Notificaciones'],
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
                    
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Notificaciones'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->get();

                    // ->whereNull('Nombre_proceso_anterior');
                    
                    // $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Notificaciones'],
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
    public function actualizarBandejaNotifi(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $usuario = Auth::user()->name;        
        //print_r($request->json);
        $IdEventoBandejaNotifi = $request->array;
        $Id_profesional = $request->json['profesional'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];

        $profesional = DB::table('users')
        ->select('name')->where('id',$Id_profesional)
        ->get();
        if (count($profesional) > 0) {
            $nombre = json_decode(json_encode($profesional));
            $nombre_profesional= $nombre[0]->name; 

            $actualizar_bandejaNotifi = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional,
                'Id_servicio' => $Id_Servicio_redireccionar
            ];       

            $actualizar_bandejaNotifi_Profesional = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional
            ]; 
        }else{
            $actualizar_bandejaNotifi_Servicio = [
                'Nombre_usuario' => $usuario,
                'Id_servicio' => $Id_Servicio_redireccionar
            ]; 
        }
        
        switch (true) {
            case (!empty($IdEventoBandejaNotifi) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
        
                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaNotifi)
                    ->update($actualizar_bandejaNotifi);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Notifi',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
            
                    return json_decode(json_encode($mensajes, true));
                
            break;
            case (!empty($IdEventoBandejaNotifi) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaNotifi)
                    ->update($actualizar_bandejaNotifi_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Notifi',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;
            
            case (!empty($IdEventoBandejaNotifi) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaNotifi)
                    ->update($actualizar_bandejaNotifi_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Notifi',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;

            case (!empty($IdEventoBandejaNotifi) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_Notifi',
                        "mensaje" => 'Debe seleccionar el Profesional o Redireccionar a, para Actualizar'
                    );

                    return json_decode(json_encode($mensajes, true));
            break;
            
            default:                
            break;
        }
        
    }
}
