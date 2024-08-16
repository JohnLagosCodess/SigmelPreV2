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
use App\Models\sigmel_informacion_correspondencia_eventos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

            $info_evento = BandejaNotifiController::evento_en_notificaciones($request->evento,$request->id_asignacion);
            $info_evento = json_decode(json_encode($info_evento, true));
            return response()->json($info_evento);
        }
    }

    public static function finalizarNotificacion(string $evento,int $id_asignacion,bool $estado){
        if(!$estado){
            sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->where([
                ['ID_evento',$evento],
                ['Id_Asignacion',$id_asignacion]
            ])->update(['Estado_correspondencia' => '1']);
        }else{
            sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->where([
                ['ID_evento',$evento],
                ['Id_Asignacion',$id_asignacion]
            ])->update(['Estado_correspondencia' => '0']); //desactiva la correspondencia para ediccion
        }
    }

    public static function evento_en_notificaciones(string $id_evento,int $id_asignacion,$comunicado = null){
        $condiciones = array(['siaev.Id_Asignacion',$id_asignacion],
        ['siaev.ID_evento',$id_evento]);
        if($comunicado != null){
            array_push($condiciones,['Id_comunicado',$comunicado]);
        }

        $enviar_notificacion = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->from('sigmel_informacion_asignacion_eventos as siaev')
        ->select('siaev.Notificacion','sicoe.Estado_correspondencia')
        ->leftjoin('sigmel_gestiones.sigmel_informacion_correspondencia_eventos as sicoe','sicoe.Id_Asignacion','siaev.Id_Asignacion')
        ->where($condiciones)->get()->map(function($item){
            //1 - Activo 0 - Inactivo: Siempre y el evento no tenga alguna correspondencia y/o su estado sea 1 la se mostrara la correspodencia 
            $item->Notificacion = $item->Notificacion == 'No'  && is_null($item->Estado_correspondencia) ? 'No' : 'Si';
            return $item;
        });

        return $enviar_notificacion;
    }
}
