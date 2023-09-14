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


class BandejaOrigenController extends Controller
{
    // Bandeja Origen Coordinador
    public function mostrarVistaBandejaOrigen(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();        

        return view('Coordinador.bandejaOrigen', compact('user'));
    }

    //Selectores Bandeja Origen
    public function cargueListadoSelectoresBandejaOrigen(Request $request){
        $parametro = $request->parametro;
        
        if ($parametro == 'lista_profesional_origen') {
            
            $listado_profesional_origen = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(1, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_Origen = json_decode(json_encode($listado_profesional_origen, true));
            return response()->json($info_listado_profesional_Origen);
        }

        //Listado servicio proceso Origen
        if($parametro == 'lista_servicios_origen'){
            $listado_servicio_Origen = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                ['Nombre_proceso', '=', 'Origen'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_Origen = json_decode(json_encode($listado_servicio_Origen, true));
            return response()->json($info_listado_servicio_Origen);
        }
    }

    public function sinFiltroBandejaOrigen(Request $request){

        $BandejaOrigenTotal = $request->BandejaOrigenTotal;        

        if($BandejaOrigenTotal == 'CargaBandejaOrigen'){

            $bandejaOrigensin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Origen']
            ])
            ->whereNull('Nombre_proceso_anterior');

            $bandejaOrigen = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Origen'],
                ['Id_proceso_anterior', '<>', 1]
            ])
            ->union($bandejaOrigensin_Pro_ant)
            ->get();

            $arraybandejaOrigen = json_decode(json_encode($bandejaOrigen, true));
            return response()->json($arraybandejaOrigen);

        }
    }

    public function filtrosBandejaOrigen(Request $request){
        
        $consultar_f_desde = $request->consultar_f_desde;
        $consultar_f_hasta = $request->consultar_f_hasta;
        $consultar_g_dias = $request->consultar_g_dias;
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                    $bandejaOrigensin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Origen'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->whereNull('Nombre_proceso_anterior')
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);
                    
                    $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                            ['Id_proceso_anterior', '<>', 1],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                        ])            
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->union($bandejaOrigensin_Pro_ant)
                    ->get();
            
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
                    
                    $bandejaOrigensin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Origen']
                    ])
                    ->whereNull('Nombre_proceso_anterior')
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);

                    $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                            ['Id_proceso_anterior', '<>', 1],
                        ])            
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->union($bandejaOrigensin_Pro_ant)
                    ->get();                    

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
                    
                    $bandejaOrigensin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Origen'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->whereNull('Nombre_proceso_anterior');
                    
                    $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Origen'],
                            ['Id_proceso_anterior', '<>', 1],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                        ])            
                    ->union($bandejaOrigensin_Pro_ant)
                    ->get();
                
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
        //print_r($request->json);
        $IdEventoBandejaOrigen = $request->array;
        $Id_profesional = $request->json['profesional'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];

        $profesional = DB::table('users')
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
        }
        
    }

}
