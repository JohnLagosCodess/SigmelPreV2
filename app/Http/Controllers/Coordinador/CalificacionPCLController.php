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
use App\Models\sigmel_campimetria_visuales;
use App\Models\sigmel_lista_califi_decretos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\cndatos_eventos;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_info_campimetria_ojo_izq_eventos;
use App\Models\sigmel_info_campimetria_ojo_der_eventos;
use App\Models\sigmel_informacion_agudeza_visual_eventos;

class CalificacionPCLController extends Controller
{
    public function mostrarVistaCalificacionPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $newIdAsignacion=$request->newIdAsignacion;
        $newIdEvento = $request->newIdEvento;
        $SubModulo='CalficacionTecnicaPCL'; //Enviar a la vista del SubModulo    

        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));
        $array_datos_destinatarios = DB::select('CALL psrcomunicados(?)', array($newIdEvento));

        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where('Estado', 'Activo')
        ->get();


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

        $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'Aporta_documento')
        ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion],
            ['Estado', 'Inactivo'], ['F_solicitud_documento', '0000-00-00']])
        ->get();

        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));

        return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'array_datos_destinatarios', 'listado_documentos_solicitados', 'arraylistado_documentos', 'dato_validacion_no_aporta_docs','arraylistado_documentos','SubModulo'));
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

            $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento)); 

            $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
            'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
            ->where('Estado', 'Activo')
            ->get();

            $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'Aporta_documento')
            ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion],['Estado', 'Inactivo']])
            ->get();
    
            // return redirect('/calificacionPCL')->with('user','array_datos_calificacionPcl', 'arraylistado_documentos', 'listado_documentos_solicitados', 'dato_validacion_no_aporta_docs');
            return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'listado_documentos_solicitados', 'arraylistado_documentos', 'listado_documentos_solicitados', 'dato_validacion_no_aporta_docs'));
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

            $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));
            return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'listado_documentos_solicitados', 'arraylistado_documentos'));
        
            $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
            'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
            ->where('Estado', 'Activo')
            ->get();
    
            $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'Aporta_documento')
            ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion],['Estado', 'Inactivo']])
            ->get();
    
            return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'arraylistado_documentos', 'listado_documentos_solicitados', 'dato_validacion_no_aporta_docs'));
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

        $parametro = $request->parametro;

        if ($parametro == "datos_bitacora") {

            // Seteo del autoincrement para mantener el primary key siempre consecutivo.
            $max_id = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->max('Id_Documento_Solicitado');
            if ($max_id <> "") {
                DB::connection('sigmel_gestiones')
                ->statement("ALTER TABLE sigmel_informacion_documentos_solicitados_eventos AUTO_INCREMENT = ".($max_id));
            }

            // Validacion: Se desmarca la opción no aporta documentos y se inserta registros.
            if ($request->tupla_no_aporta <> 0) {
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
                ->where('Id_Documento_Solicitado', $request->tupla_no_aporta)->delete();
            }
            
            $aporta_documento = 'Si';
            // Captura del array de los datos de la tabla
            $array_datos = $request->datos_finales_documentos_solicitados;
    
            // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
            $array_datos_organizados = [];
            foreach ($array_datos as $subarray_datos) {
    
                array_unshift($subarray_datos, $request->Id_proceso);
                array_unshift($subarray_datos, $request->Id_Asignacion);
                array_unshift($subarray_datos, $request->Id_evento);
    
                $subarray_datos[] = $aporta_documento;
                $subarray_datos[] = $nombre_usuario;
                $subarray_datos[] = $date;
    
                array_push($array_datos_organizados, $subarray_datos);
            }
    
            // Creación de array con los campos de la tabla: sigmel_informacion_documentos_solicitados_eventos
            $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso','F_solicitud_documento','Id_Documento','Nombre_documento',
            'Descripcion','Id_solicitante','Nombre_solicitante','F_recepcion_documento', 'Aporta_documento', 'Nombre_usuario','F_registro'];
            
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
        }

        // Validación: No se inserta datos y selecciona el checkbox de No aporta documentos
        if ($parametro == "no_aporta") {

            $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'Aporta_documento')
            ->where([['ID_evento', $request->Id_evento],['Id_Asignacion', $request->Id_Asignacion],
                ['Estado', 'Inactivo'], ['F_solicitud_documento', '0000-00-00']])
            ->get();

            if (count($dato_validacion_no_aporta_docs)> 0) {
                $mensajes = array(
                    "parametro" => 'replicando_no_aporta',
                    "mensaje" => 'No puede registrar esta opción de nuevo.'
                );
            }else{
                $insertar = [
                    'ID_evento' => $request->Id_evento,
                    'Id_Asignacion' => $request->Id_Asignacion,
                    'Id_proceso' => $request->Id_proceso,
                    'F_solicitud_documento' => "",
                    'Id_Documento' => 0,
                    'Nombre_documento' => "N/A",
                    'Descripcion' => "N/A",
                    'Id_solicitante' => 0,
                    'Nombre_solicitante' => "N/A",
                    'F_recepcion_documento' => "",
                    'Aporta_documento' => "No",
                    'Estado' => "Inactivo",
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date
                ];
             
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')->insert($insertar);
                $mensajes = array(
                    "parametro" => 'inserto_informacion',
                    "mensaje" => 'Información guardada satisfactoriamente.'
                );

            }

        }

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

        $total_registros = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_eliminada',
            'total_registros' => $total_registros,
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


    /* TODO LO REFERENTE AL SUBMÓDULO DE CALIFICACIÓN TÉNCICA PCL */
    public function mostrarVistaCalificacionTecnicaPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $Id_evento_calitec=$request->Id_evento_calitec;
        $Id_asignacion_calitec = $request->Id_asignacion_calitec;

        $hay_agudeza_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $Id_evento_calitec)->get();

        $array_datos_calificacionPclTecnica = DB::select('CALL psrcalificacionpcl(?)', array($Id_asignacion_calitec));
        //Traer Motivo de solicitud,Dominancia actual
        $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
        ->select('Id_motivo_solicitud','Nombre_solicitud','Id_dominancia','Nombre_dominancia')
        ->where([
            ['ID_evento', '=', $Id_evento_calitec]
        ])
        ->get();
        //Traer Información apoderado 
        $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('Nombre_apoderado','Nro_identificacion_apoderado')
        ->where([
            ['ID_evento', '=', $Id_evento_calitec]
        ])
        ->get();

        return view('coordinador.calificacionTecnicaPCL', compact('user','array_datos_calificacionPclTecnica','motivo_solicitud_actual','datos_apoderado_actual', 'hay_agudeza_visual'));
    }

    public function cargueListadoSelectoresCalifcacionTecnicaPcl(Request $request){
        $parametro = $request->parametro;
        // Listado Origen Firme calificacion PCL
        if($parametro == 'lista_origen_firme_pcl'){
            $listado_origen_firme = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Firme'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_origen_firme = json_decode(json_encode($listado_origen_firme, true));
            return response()->json($info_listado_origen_firme);
        }
        // Listado Cobertura calificacion PCL
        if($parametro == 'lista_origen_cobertura_pcl'){
            $listado_origen_cobertura = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Cobertura'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_origen_cobertura = json_decode(json_encode($listado_origen_cobertura, true));
            return response()->json($info_listado_origen_cobertura);
        }
        // Listado decreto calificacion PCL
        if($parametro == 'lista_cali_decreto_pcl'){
            $listado_cali_decreto = sigmel_lista_califi_decretos::on('sigmel_gestiones')
            ->select('Id_Decreto', 'Nombre_decreto')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_cali_decreto = json_decode(json_encode($listado_cali_decreto, true));
            return response()->json($info_listado_cali_decreto);
        }
        // Listado motivo solicitud PCL
        if($parametro == 'lista_motivo_solicitud'){
            $listado_motivo_solicitud = sigmel_lista_motivo_solicitudes::on('sigmel_gestiones')
            ->select('Id_Solicitud', 'Nombre_solicitud')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_motivo_solicitud = json_decode(json_encode($listado_motivo_solicitud, true));
            return response()->json($info_listado_motivo_solicitud);
        }
        
        // Listado selectores agudeza visual (modal agudeza visual)
        if ($parametro == "agudeza_visual") {
            $listado_agudeza_visual = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'agudeza_visual'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_agudeza_visual = json_decode(json_encode($listado_agudeza_visual, true));
            return response()->json($info_listado_agudeza_visual);
        }

    }

    public function ConsultaCampimetriaXFila(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $parametro = $request->parametro;
        if ($parametro == "nuevo") {
            $Id_Fila = $request->Id_Fila;
            $listado_campimetria = sigmel_campimetria_visuales::on('sigmel_gestiones')
            ->select('Fila1', 'Fila2', 'Fila3', 'Fila4', 'Fila5', 'Fila6', 'Fila7', 'Fila8', 'Fila9', 'Fila10')
            ->get();
            $info = json_decode(json_encode($listado_campimetria, true));
        };

        if ($parametro == "edicion_ojo_izq") {
            $listado_campimetria_ojo_izq = sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')
            ->select('InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5', 'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10')
            ->where('Id_agudeza', $request->Id_agudeza)
            ->get();
            $info = json_decode(json_encode($listado_campimetria_ojo_izq, true));
        };

        if ($parametro == "edicion_ojo_der") {
            $listado_campimetria_ojo_der = sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')
            ->select('InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5', 'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10')
            ->where('Id_agudeza', $request->Id_agudeza)
            ->get();
            $info = json_decode(json_encode($listado_campimetria_ojo_der, true));
        };


        return response()->json($info);

    }

    public function guardarAgudezaVisual(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        /* Inserción de información del formulario */
        sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')->insert($request->info_formulario);

        // Extraemos el id insertado para almacenar los datos de la campimetria
        $id_agudeza = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')->select('Id_agudeza')->latest('Id_agudeza')->first();

        /* Envío de la información de la campimetría para ojo izquierdo */
        $grilla_ojo_izq = $request->grilla_ojo_izq;
        foreach ($grilla_ojo_izq as $key => $insertar_info_grid_ojo_izq) {
            $insertar_info_grid_ojo_izq = array("Id_agudeza" => $id_agudeza['Id_agudeza']) + $insertar_info_grid_ojo_izq;
            sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_izq);
        }

        /* Envío de la información de la campimetría para ojo derecho */
        $grilla_ojo_der = $request->grilla_ojo_der;
        foreach ($grilla_ojo_der as $key => $insertar_info_grid_ojo_der) {
            $insertar_info_grid_ojo_der = array("Id_agudeza" => $id_agudeza['Id_agudeza']) + $insertar_info_grid_ojo_der;
            sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_der);
        }

        $mensajes = array(
            "parametro" => 'guardo',
            "mensaje" => 'Información de Agudeza visual agregada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function infoAgudezaVisual(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $informacion_agudeza_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where("ID_evento", $request->ID_evento)
        ->get();

        $info_agudeza = json_decode(json_encode($informacion_agudeza_visual, true));
        return response()->json($info_agudeza);

    }

    public function actualizarAgudezaVisual (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        /* Actualización de información del formulario */
        sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_agudeza', '=', $request->Id_agudeza],
            ['ID_evento', '=', $request->ID_evento]
            ])
        ->update($request->info_formulario);


        /* Envío de la información de la campimetría para ojo izquierdo  */
        sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $request->Id_agudeza)->delete();

        $grilla_ojo_izq = $request->grilla_ojo_izq;
        foreach ($grilla_ojo_izq as $key => $insertar_info_grid_ojo_izq) {
            $insertar_info_grid_ojo_izq = array("Id_agudeza" => $request->Id_agudeza) + $insertar_info_grid_ojo_izq;
            sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_izq);
        }

        /* Envío de la información de la campimetría para ojo derecho */
        sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $request->Id_agudeza)->delete();
        $grilla_ojo_der = $request->grilla_ojo_der;
        foreach ($grilla_ojo_der as $key => $insertar_info_grid_ojo_der) {
            $insertar_info_grid_ojo_der = array("Id_agudeza" => $request->Id_agudeza) + $insertar_info_grid_ojo_der;
            sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_der);
        }

        $mensajes = array(
            "parametro" => 'actualizo',
            "mensaje" => 'Información de Agudeza visual actualizada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }


}
