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

class BandejaJuntasController extends Controller
{
    // Bandeja Origen Coordinador
    public function mostrarVistaBandejaJuntas(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();        

        return view('coordinador.bandejaJuntas', compact('user'));
    }

     //Selectores Bandeja Juntas
     public function cargueListadoSelectoresBandejaJuntas(Request $request){
        $parametro = $request->parametro;
        
        if ($parametro == 'lista_profesional_juntas') {
            
            $listado_profesional_juntas = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(3, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_Juntas = json_decode(json_encode($listado_profesional_juntas, true));
            return response()->json($info_listado_profesional_Juntas);
        }

        //Listado servicio proceso Origen
        if($parametro == 'lista_servicios_juntas'){
            $listado_servicio_Juntas = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                ['Nombre_proceso', '=', 'Juntas'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_Juntas = json_decode(json_encode($listado_servicio_Juntas, true));
            return response()->json($info_listado_servicio_Juntas);
        }
    }

    public function sinFiltroBandejaJuntas(Request $request){

        $BandejaJuntasTotal = $request->BandejaJuntasTotal;        

        if($BandejaJuntasTotal == 'CargaBandejaJuntas'){

            $bandejaJuntassin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Juntas']
            ])
            ->whereNull('Nombre_proceso_anterior');

            $bandejaJuntas = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Juntas'],
                ['Id_proceso_anterior', '<>', 3]
            ])
            ->union($bandejaJuntassin_Pro_ant)
            ->get();

            $arraybandejaJuntas = json_decode(json_encode($bandejaJuntas, true));
            return response()->json($arraybandejaJuntas);

        }
    }
    public function filtrosBandejaJuntas(Request $request){
        
        $consultar_f_desde = $request->consultar_f_desde;
        $consultar_f_hasta = $request->consultar_f_hasta;
        $consultar_g_dias = $request->consultar_g_dias;
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                    $bandejaJuntassin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->whereNull('Nombre_proceso_anterior')
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);
                    
                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Juntas'],
                            ['Id_proceso_anterior', '<>', 3],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                        ])            
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->union($bandejaJuntassin_Pro_ant)
                    ->get();
            
                    $arraybandejaJuntasFiltros = json_decode(json_encode($bandejaJuntasFiltros, true));
                    if (count($arraybandejaJuntasFiltros)>0) {
                        return response()->json($arraybandejaJuntasFiltros);                        
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
                    
                    $bandejaJuntassin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas']
                    ])
                    ->whereNull('Nombre_proceso_anterior')
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);

                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Juntas'],
                            ['Id_proceso_anterior', '<>', 3],
                        ])            
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->union($bandejaJuntassin_Pro_ant)
                    ->get();                    

                    $arraybandejaJuntasFiltros = json_decode(json_encode($bandejaJuntasFiltros, true));
                    if (count($arraybandejaJuntasFiltros)>0) {
                        return response()->json($arraybandejaJuntasFiltros);
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
                    
                    $bandejaJuntassin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->whereNull('Nombre_proceso_anterior');
                    
                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Juntas'],
                            ['Id_proceso_anterior', '<>', 3],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                        ])            
                    ->union($bandejaJuntassin_Pro_ant)
                    ->get();
                
                    $arraybandejaJuntasFiltros = json_decode(json_encode($bandejaJuntasFiltros, true));
                    if (count($arraybandejaJuntasFiltros)>0) {
                        return response()->json($arraybandejaJuntasFiltros);
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
    public function actualizarBandejaJuntas(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $usuario = Auth::user()->name;        
        //print_r($request->json);
        $IdEventoBandejaJuntas = $request->array;
        $Id_profesional = $request->json['profesional'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];

        $profesional = DB::table('users')
        ->select('name')->where('id',$Id_profesional)
        ->get();
        if (count($profesional) > 0) {
            $nombre = json_decode(json_encode($profesional));
            $nombre_profesional= $nombre[0]->name; 

            $actualizar_bandejaJuntas = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional,
                'Id_servicio' => $Id_Servicio_redireccionar
            ];       

            $actualizar_bandejaJuntas_Profesional = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional
            ]; 
        }else{
            $actualizar_bandejaJuntas_Servicio = [
                'Nombre_usuario' => $usuario,
                'Id_servicio' => $Id_Servicio_redireccionar
            ]; 
        }
        
        switch (true) {
            case (!empty($IdEventoBandejaJuntas) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
        
                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaJuntas)
                    ->update($actualizar_bandejaJuntas);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
            
                    return json_decode(json_encode($mensajes, true));
                
            break;
            case (!empty($IdEventoBandejaJuntas) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaJuntas)
                    ->update($actualizar_bandejaJuntas_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;
            
            case (!empty($IdEventoBandejaJuntas) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaJuntas)
                    ->update($actualizar_bandejaJuntas_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;

            case (!empty($IdEventoBandejaJuntas) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_Juntas',
                        "mensaje" => 'Debe seleccionar el Profesional o Redireccionar a, para Actualizar'
                    );

                    return json_decode(json_encode($mensajes, true));
            break;
            
            default:                
            break;
        }
        
    }
}
