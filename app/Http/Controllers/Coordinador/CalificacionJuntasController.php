<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_historial_acciones_eventos;

class CalificacionJuntasController extends Controller
{
    public function mostrarVistaCalificacionJuntas(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $time = time();
        $date = date("Y-m-d", $time);
        $newIdAsignacion=$request->newIdAsignacion;
        $newIdEvento = $request->newIdEvento;

        $array_datos_calificacionJuntas = DB::select('CALL psrcalificacionJuntas(?)', array($newIdAsignacion));
         //Trae Documetos Generales del evento
         $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));
        

        return view('coordinador.calificacionJuntas', compact('user','array_datos_calificacionJuntas','arraylistado_documentos'));
    }

    //Guardar informacion del modulo de Juntas
    public function guardarCalificacionJuntas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;

        // validacion de bandera para guardar o actualizar
        if ($request->bandera_accion_guardar_actualizar == 'Guardar') {
               
            // insercion de datos a la tabla de sigmel_informacion_accion_eventos
    
            $datos_info_registrarCalifcacionJuntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
                'F_accion' => $request->f_accion,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Causal_devolucion_comite' => 'N/A',
                'Descripcion_accion' => $request->descripcion_accion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            $datos_info_actualizarAsignacionEvento= [              
                'F_alerta' => $request->fecha_alerta,                
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_accion_eventos::on('sigmel_gestiones')->insert($datos_info_registrarCalifcacionJuntas);

            sleep(2);

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            sleep(2);

            $datos_info_historial_acciones = [
                'ID_evento' => $newIdEvento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Guardado Modulo Calificacion Juntas.",
                'Descripcion' => $request->descripcion_accion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
            sleep(2);
            $mensajes = array(
                "parametro" => 'agregarCalificacionJuntas',
                "parametro_1" => 'guardo',
                "mensaje_1" => 'Registro agregado satisfactoriamente.'
            );

            return json_decode(json_encode($mensajes, true));

        }elseif ($request->bandera_accion_guardar_actualizar == 'Actualizar') {
            
            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos

            $datos_info_registrarCalifcacionJuntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
                'F_accion' => $request->f_accion,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Causal_devolucion_comite' => 'N/A',
                'Descripcion_accion' => $request->descripcion_accion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            $datos_info_actualizarAsignacionEvento= [              
                'F_alerta' => $request->fecha_alerta,                
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_registrarCalifcacionJuntas);
            sleep(2);
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            sleep(2);

            $datos_info_historial_acciones = [
                'ID_evento' => $newIdEvento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Actualizado Modulo Juntas.",
                'Descripcion' => $request->descripcion_accion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
            sleep(2);
            $mensajes = array(
                "parametro" => 'agregarCalificacionJuntas',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }
}
