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

            $bandejaPCLsin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Calificación PCL']
            ])
            ->whereNull('Nombre_proceso_anterior');

            $bandejaPCL = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                ['Id_proceso_anterior', '<>', 2]
            ])
            ->union($bandejaPCLsin_Pro_ant)
            ->get();

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

                    $bandejaPCLsin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->whereNull('Nombre_proceso_anterior')
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);
                    
                    $bandejaPCLFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Id_proceso_anterior', '<>', 2],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                        ])            
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->union($bandejaPCLsin_Pro_ant)
                    ->get();
            
                    $arraybandejaPCLFiltros = json_decode(json_encode($bandejaPCLFiltros, true));
                    if (count($arraybandejaPCLFiltros)>0) {
                        return response()->json($arraybandejaPCLFiltros);                        
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
                    
                    $bandejaPCLsin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Calificación PCL']
                    ])
                    ->whereNull('Nombre_proceso_anterior')
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);

                    $bandejaPCLFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Id_proceso_anterior', '<>', 2],
                        ])            
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->union($bandejaPCLsin_Pro_ant)
                    ->get();                    

                    $arraybandejaPCLFiltros = json_decode(json_encode($bandejaPCLFiltros, true));
                    if (count($arraybandejaPCLFiltros)>0) {
                        return response()->json($arraybandejaPCLFiltros);
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
                    
                    $bandejaPCLsin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->whereNull('Nombre_proceso_anterior');
                    
                    $bandejaPCLFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                            ['Nombre_proceso_actual', '=', 'Calificación PCL'],
                            ['Id_proceso_anterior', '<>', 2],
                            ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                        ])            
                    ->union($bandejaPCLsin_Pro_ant)
                    ->get();
                
                    $arraybandejaPCLFiltros = json_decode(json_encode($bandejaPCLFiltros, true));
                    if (count($arraybandejaPCLFiltros)>0) {
                        return response()->json($arraybandejaPCLFiltros);
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
        $profesional = $request->json['profesional'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];
        $actualizar_bandejaPCL = [
            'Nombre_usuario' => $usuario,
            'Nombre_profesional' => $profesional,
            'Id_servicio' => $Id_Servicio_redireccionar
        ];         
        $actualizar_bandejaPCL_Profesional = [
            'Nombre_usuario' => $usuario,
            'Nombre_profesional' => $profesional
        ]; 
        $actualizar_bandejaPCL_Servicio = [
            'Nombre_usuario' => $usuario,
            'Id_servicio' => $Id_Servicio_redireccionar
        ]; 
        
        switch (true) {
            case (!empty($IdEventoBandejaPCl) and !empty($profesional) and !empty($Id_Servicio_redireccionar)):
        
                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaPCl)
                    ->update($actualizar_bandejaPCL);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
            
                    return json_decode(json_encode($mensajes, true));
                
            break;
            case (!empty($IdEventoBandejaPCl) and empty($profesional) and !empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaPCl)
                    ->update($actualizar_bandejaPCL_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;
            
            case (!empty($IdEventoBandejaPCl) and !empty($profesional) and empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaPCl)
                    ->update($actualizar_bandejaPCL_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_PCL',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;

            case (!empty($IdEventoBandejaPCl) and empty($profesional) and empty($Id_Servicio_redireccionar)):
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
