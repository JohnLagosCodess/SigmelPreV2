<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use App\Models\cndatos_bandeja_eventos;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_lista_causal_seguimiento;
use App\Models\sigmel_informacion_seguimientos_eventos;
use App\Models\sigmel_registro_documentos_eventos;
use App\Models\sigmel_lista_documentos;
use App\Models\sigmel_lista_solicitantes;
use App\Models\sigmel_lista_departamentos_municipios;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;

class CalificacionPCLController extends Controller
{
    public function mostrarVistaCalificacionPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $newIdAsignacion=$request->newIdAsignacion;
        $newIdEvento = $request->newIdEvento;       

        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));
        $array_datos_destinatarios = DB::select('CALL psrcomunicados(?)', array($newIdEvento));

        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where('Estado', 'Activo')
        ->get();

        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento)); 

       /*  // creación de consecutivo para el comunicado
        $fechaActual = date("Ymd");
        // Obtener el último valor de la base de datos o archivo
        $ultimoConsecutivo = 'SAL-PCL20230728000000'; 
        $ultimoDigito = substr($ultimoConsecutivo, -6);
        $nuevoConsecutivo = $ultimoDigito + 1;
        // Reiniciar el consecutivo si es un nuevo día
        if (date("Ymd") != $fechaActual) {
            $nuevoConsecutivo = 0;
        }
        // Poner ceros a la izquierda para llegar a una longitud de 6 dígitos
        $nuevoConsecutivoFormatted = str_pad($nuevoConsecutivo, 6, "0", STR_PAD_LEFT);
        $consecutivo = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted;     */    

        // return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl'));
        return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'array_datos_destinatarios', 'listado_documentos_solicitados','arraylistado_documentos'));
    }

    public function cargueListadoSelectoresModuloCalifcacionPcl(Request $request){
        $parametro = $request->parametro;
        // Listado Modalidad calificacion PCL

        if($parametro == 'lista_modalidad_calificacion_pcl'){
            $listado_modalidad_calificacion = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Modalidad de Calificacion'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_modalidad_calificacion = json_decode(json_encode($listado_modalidad_calificacion, true));
            return response()->json($info_listado_modalidad_calificacion);

        }

        //  listado Causal seguimiento en la modal Agregar Seguimiento
        if ($parametro == 'lista_causal_seguimiento_pcl'){
            $listado_causal_seguimiento = sigmel_lista_causal_seguimiento::on('sigmel_gestiones')
            ->select('Id_causal', 'Nombre_causal')
            ->where([
                ['Estado', '=', 'activo']                
            ])
            ->get();

            $info_listado_causal_seguimiento= json_decode(json_encode($listado_causal_seguimiento, true));
            return response()->json($info_listado_causal_seguimiento);
        }

        // Listados generar comunicado

        if ($parametro == "departamentos_generar_comunicado") {
            
            $listado_departamentos_generar_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_departamento', 'Nombre_departamento')
                ->where('Estado', 'activo')
                ->groupBy('Nombre_departamento')
                ->get();
            
            $info_lista_departamentos_generar_comunicado = json_decode(json_encode($listado_departamentos_generar_comunicado, true));
            return response()->json($info_lista_departamentos_generar_comunicado);
        }

        if($parametro == "municipios_generar_comunicado"){
            $listado_municipios_generar_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_municipios', 'Nombre_municipio')
                ->where([
                    ['Id_departamento', '=', $request->id_departamento_destinatario],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_lista_municipios_generar_comunicado = json_decode(json_encode($listado_municipios_generar_comunicado, true));
            return response()->json($info_lista_municipios_generar_comunicado);
        }

        if($parametro == 'lista_forma_envio'){
            $listado_modalidad_calificacion = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Forma de envio'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_modalidad_calificacion = json_decode(json_encode($listado_modalidad_calificacion, true));
            return response()->json($info_listado_modalidad_calificacion);

        }
    }

    public function guardarCalificacionPCL(Request $request){
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
    
            $datos_info__registrarCalifcacionPcl= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => $request->modalidad_calificacion,
                'F_accion' => $request->f_accion,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Causal_devolucion_comite' => $request->causal_devolucion_comite,
                'Descripcion_accion' => $request->descripcion_accion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            $datos_info_actualizarAsignacionEvento= [              
                'F_alerta' => $request->fecha_alerta,                
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_accion_eventos::on('sigmel_gestiones')->insert($datos_info__registrarCalifcacionPcl);

            sleep(2);

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);
    
            $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));

            $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
            'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
            ->where('Estado', 'Activo')
            ->get();

            $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento)); 
    
            return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'listado_documentos_solicitados', 'arraylistado_documentos'));
        }elseif ($request->bandera_accion_guardar_actualizar == 'Actualizar') {
            
            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos

            $datos_info_actualizarCalifcacionPcl= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => $request->modalidad_calificacion,
                'F_accion' => $request->f_accion,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Causal_devolucion_comite' => $request->causal_devolucion_comite,
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
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarCalifcacionPcl);
            sleep(2);
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));

            $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
            'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
            ->where('Estado', 'Activo')
            ->get();

            $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));
            return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'listado_documentos_solicitados', 'arraylistado_documentos'));
            
            //return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'arraylistado_documentos'));
        }
        
    }

    // Cargue de listado de Documentos Solicitados para el modal Solicitud Documentos-Seguimientos
    public function CargarDatosSolicitados(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $parametro = $request->parametro;

        if ($parametro == 'listado_documentos_solicitados') {
            $datos_docs_solicitados = sigmel_lista_documentos::on('sigmel_gestiones')
            ->select('Id_Documento', 'Nro_documento', 'Nombre_documento')
            ->where('Estado', 'activo')
            ->whereIn('Nro_documento', [4,31,9,28,29,30,37])->get();

            $informacion_docs_solicitados = json_decode(json_encode($datos_docs_solicitados), true);
            return response()->json($informacion_docs_solicitados);
        }

        if ($parametro == 'listado_solicitantes') {
            $datos_solicitantes = sigmel_lista_solicitantes::on('sigmel_gestiones')
            ->select('Id_solicitante', 'Solicitante')
            ->where('Estado', 'activo')
            ->whereNotIn('Id_solicitante', [6,7])
            ->groupBy('Id_solicitante','Solicitante')
            ->get();

            $informacion_solicitantes = json_decode(json_encode($datos_solicitantes), true);
            return response()->json($informacion_solicitantes);
        }
    }

    // Guardar la información del Listado de Documentos solicitados
    public function GuardarDocumentosSolicitados(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        // Captura del array de los datos de la tabla
        $array_datos = $request->datos_finales_documentos_solicitados;

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];
        foreach ($array_datos as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);

            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_documentos_solicitados_eventos
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso','F_solicitud_documento','Id_Documento','Nombre_documento',
        'Descripcion','Id_solicitante','Nombre_solicitante','F_recepcion_documento','Nombre_usuario','F_registro'];
        
        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar) {
            sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')->insert($insertar);
        }

        $mensajes = array(
            "parametro" => 'inserto_informacion',
            "mensaje" => 'Información guardada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    public function CargarDocumentosSolicitados(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }

        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->get();

        $info_listado_documentos_solicitados = json_decode(json_encode($listado_documentos_solicitados, true));
        return response()->json($info_listado_documentos_solicitados);
    }

    // Eliminar fila de algun registro de la tabla de listado documentos seguimiento
    public function EliminarFila(Request $request){

        $id_fila = $request->fila;

        $dato_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')->where('Id_Documento_Solicitado', $id_fila)
        ->update($dato_actualizar);

        $mensajes = array(
            "parametro" => 'fila_eliminada',
            "mensaje" => 'Información eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    // Insercion agregar seguimiento
    public function guardarAgregarSeguimiento(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;
        $fecha_seguimiento = $request->fecha_seguimiento;
        $causal_seguimiento = $request->causal_seguimiento;
        $descripcion_seguimiento = $request->descripcion_seguimiento;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;

        $datos_info_causalSeguimiento = [
            'ID_evento' => $newIdEvento,
            'Id_Asignacion' => $newIdAsignacion,
            'Id_proceso' => $Id_proceso,
            'F_seguimiento' => $fecha_seguimiento,
            'Causal_seguimiento' => $causal_seguimiento,
            'Descripcion_seguimiento' => $descripcion_seguimiento,
            'Nombre_usuario'=> $usuario,
            'F_registro'=> $date,
        ];

        sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')->insert($datos_info_causalSeguimiento);

        $mensajes = array(
            "parametro" => 'agregar_seguimiento',
            "mensaje" => 'Seguimiento agregado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    // Captura de la data para el DataTable Historial de Seguimientos

    public function historialSeguimientosPCL(Request $request){
        $HistorialSeguimientoPcl = $request->HistorialSeguimientoPcl;

        //echo $HistorialSeguimientoPcl;

        if ($HistorialSeguimientoPcl == 'CargaHistorialSeguimiento') {
            
            $hitorialAgregarSeguimiento = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->select('F_seguimiento','Causal_seguimiento','Descripcion_seguimiento','Nombre_usuario')
            ->get();

            $arrayhistorialSeguimiento = json_decode(json_encode($hitorialAgregarSeguimiento, true));
            return response()->json(($arrayhistorialSeguimiento));

        }
    }

    //Captura de datos para insertar el comunicado

    public function captuarDestinatariosPrincipal(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $nombreusuario = Auth::user()->name; 
        $destinatarioPrincipal = $request->destinatarioPrincipal;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso; 

        switch (true) {
            case ($destinatarioPrincipal == 'Afiliado'):                
                $array_datos_destinatarios = DB::select('CALL psrcomunicados(?)', array($newIdEvento)); 
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('sgt.Id_proceso_equipo', 'ssu.name')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();  
                
                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_destinatarios' => $array_datos_destinatarios,
                    'array_datos_lider' => $array_datos_lider
                ]);
            break;
            case ($destinatarioPrincipal == 'Empresa'):                
                $array_datos_destinatarios = DB::select('CALL psrcomunicados(?)', array($newIdEvento)); 
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('sgt.Id_proceso_equipo', 'ssu.name')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();  
                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_destinatarios' => $array_datos_destinatarios,                    
                    'array_datos_lider' => $array_datos_lider

                ]);
            break;
            case ($destinatarioPrincipal == 'Otro'):  
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('sgt.Id_proceso_equipo', 'ssu.name')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();  
                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_lider' => $array_datos_lider
                ]);
            break;
                         
            default:                
            break;
        }

    }

}
