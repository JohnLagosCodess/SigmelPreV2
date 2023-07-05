<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\cndatos_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;

class BuscarEventoController extends Controller
{
    /* TODO LO REFERENTE AL FORMULARIO DE BUSCAR UN EVENTO*/
    // Busqueda Evaluado y evento
    public function mostrarVistaBuscarEvento(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        return view('administrador.busquedaEvento', compact('user'));

    }


    // Resultado de busqueda
    public function consultaInformacionEvento(Request $request){
    
        $consultar_nro_identificacion = $request->consultar_nro_identificacion;
        $consultar_id_evento = $request->consultar_id_evento;

        /* 
            CASO N° 1: Cuando se consulta solamente por el número de identificación.
            CASO N° 2: Cuando se consulta solamente por el id de evento.
            CASO N° 3: Cuando se consulta por número de identificación y id de evento.
        */
        switch(true)
        {
            case (!empty($consultar_nro_identificacion) and empty($consultar_id_evento)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where('Nro_identificacion', $consultar_nro_identificacion)
                    ->orderBy('ID_evento', 'desc')
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));
                if(count($array_informacion_eventos)>0){
                    return response()->json($informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            case (!empty($consultar_id_evento) and empty($consultar_nro_identificacion)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where('ID_evento', $consultar_id_evento)
                    ->orderBy('ID_evento', 'desc')
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));
                if(count($array_informacion_eventos)>0){
                    return response()->json($informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            case (!empty($consultar_id_evento) and !empty($consultar_nro_identificacion)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nro_identificacion', '=', $consultar_nro_identificacion],
                        ['ID_evento', '=', $consultar_id_evento]
                    ])
                    ->orderBy('ID_evento', 'desc')
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));
                if(count($array_informacion_eventos)>0){
                    return response()->json($informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            default:
            break;
        }
        
    }

    // Crear un nuevo servicio para el Evento seleccionado
    public function crearNuevoServicio(Request $request){
        
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        
        // Actualizamos a No el servicio escogido (tupla) para deshabilitar la opcion
        // de crear nuevo servicio
        $actualizar_estado_bandera_nuevo_servicio = [
            'Visible_Nuevo_Servicio' => 'No'
        ];

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->where('Id_Asignacion', $request->tupla_servicio_escogido)
        ->update($actualizar_estado_bandera_nuevo_servicio);

        // Recopilación de datos para insertar el nuevo servicio
        $datos_nuevo_servicio = [
            'ID_evento' => $request->id_evento,
            'Id_proceso' => $request->id_proceso_actual,
            'Visible_Nuevo_Proceso' => 'Si',
            'Id_servicio' => $request->nuevo_servicio,
            'Visible_Nuevo_Servicio' => 'Si',
            'Id_accion' => $request->nueva_accion,
            'Descripcion' => $request->nueva_descripcion,
            'F_alerta' => $request->nueva_fecha_alerta,
            'Id_Estado_evento' => 1,
            'F_accion' => $request->nueva_fecha_accion,
            'F_radicacion' => $request->nueva_fecha_radicacion,
            'Nombre_profesional' => $request->nuevo_profesional,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->insert($datos_nuevo_servicio);

        sleep(1);
        $mensajes = array(
            "parametro" => 'creo_servicio',
            "retorno_id_evento" => $request->id_evento,
            "mensaje" => 'Servicio agregado satisfactoriamente. Por favor hacer clic en el botón Actualizar para visualizar los cambios.'
        );
        
        return json_decode(json_encode($mensajes, true));

    }

    // Crear un nuevo proceso para el Evento seleccionado
    public function crearNuevoProceso(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        // Actualizamos a No el proceso escogido (tupla) para deshabilitar la opcion
        // de crear nuevo proceso.

        $actualizar_estado_bandera_nuevo_proceso = [
            'Visible_Nuevo_Proceso' => 'No'
        ];

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->where('Id_Asignacion', $request->tupla_proceso_escogido)
        ->update($actualizar_estado_bandera_nuevo_proceso);

        $datos_nuevo_proceso = [
            'ID_evento' => $request->id_evento,
            'Id_proceso' => $request->selector_nuevo_proceso,
            'Visible_Nuevo_Proceso' => 'Si',
            'Id_servicio' => $request->selector_nuevo_servicio,
            'Visible_Nuevo_Servicio' => 'Si',
            'Id_accion' => $request->nueva_accion_nuevo_proceso,
            'Descripcion' => $request->nueva_descripcion_nuevo_proceso,
            'F_alerta' => $request->nueva_fecha_alerta_nuevo_proceso,
            'Id_Estado_evento' => 1,
            'F_accion' => $request->nueva_fecha_accion_nuevo_proceso,
            'F_radicacion' => $request->fecha_radicacion_nuevo_proceso,
            'Nombre_profesional' => $request->nuevo_profesional_nuevo_proceso,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->insert($datos_nuevo_proceso);

        sleep(1);
        $mensajes = array(
            "parametro" => 'creo_proceso',
            "retorno_id_evento" => $request->id_evento,
            "mensaje" => 'Proceso agregado satisfactoriamente. Por favor hacer clic en el botón Actualizar para visualizar los cambios.'
        );

        return json_decode(json_encode($mensajes, true));


    }
}
