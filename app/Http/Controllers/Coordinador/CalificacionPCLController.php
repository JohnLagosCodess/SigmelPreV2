<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
/* use Dompdf\Dompdf;
use Dompdf\Options; */
use PDF;
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
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_campimetria_visuales;
use App\Models\sigmel_lista_califi_decretos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\cndatos_eventos;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_info_campimetria_ojo_izq_eventos;
use App\Models\sigmel_info_campimetria_ojo_der_eventos;
use App\Models\sigmel_informacion_agudeza_visual_eventos;
use App\Models\sigmel_lista_tablas_1507_decretos;
use App\Models\cndatos_info_comunicado_eventos;
use App\Models\sigmel_historial_acciones_eventos;
use App\Models\sigmel_informacion_agudeza_auditiva_eventos;
use App\Models\sigmel_informacion_decreto_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_informacion_examenes_interconsultas_eventos;
use App\Models\sigmel_informacion_pericial_eventos;
use App\Models\sigmel_lista_cie_diagnosticos;
use App\Models\sigmel_lista_clases_decretos;
use App\Models\sigmel_informacion_deficiencias_alteraciones_eventos;
use App\Models\sigmel_informacion_laboralmente_activo_eventos;
use App\Models\sigmel_informacion_libro2_libro3_eventos;
use App\Models\sigmel_informacion_rol_ocupacional_eventos;
use App\Models\sigmel_lista_tipo_eventos;
use Psy\Readline\Hoa\Console;
use Svg\Tag\Rect;
use App\Models\sigmel_lista_procesos_servicios;

class CalificacionPCLController extends Controller
{
    public function mostrarVistaCalificacionPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $newIdAsignacion=$request->newIdAsignacion;
        $newIdEvento = $request->newIdEvento;

        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));
        $array_datos_destinatarios = DB::select('CALL psrcomunicados(?)', array($newIdEvento));
        //Consulta Vista a mostrar
        $TraeVista= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_lista_procesos_servicios as p')
        ->select('v.nombre_renderizar')
        ->leftJoin('sigmel_sys.sigmel_vistas as v', 'p.Id_vista', '=', 'v.id')
        ->where('p.Id_Servicio',  '=', $array_datos_calificacionPcl[0]->Id_Servicio)
        ->get();
        $SubModulo=$TraeVista[0]->nombre_renderizar; //Enviar a la vista del SubModulo    

        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where([
            ['Estado', 'Activo'], ['Id_proceso',$array_datos_calificacionPcl[0]->Id_proceso],
            ['ID_evento', $newIdEvento]
        ])
        ->get();


       // creación de consecutivo para el comunicado
       $radicadocomunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
       ->select('N_radicado')
       ->where([
           ['ID_evento',$newIdEvento],
           ['F_comunicado',$date],
           ['Id_proceso','2']
       ])
       ->orderBy('N_radicado', 'desc')
       ->limit(1)
       ->get();
       
       if(count($radicadocomunicado)==0){
           $fechaActual = date("Ymd");
           // Obtener el último valor de la base de datos o archivo
           $consecutivoP1 = "SAL-PCL";
           $consecutivoP2 = $fechaActual;
           $consecutivoP3 = '000000';
           $ultimoDigito = substr($consecutivoP3, -6);
           $consecutivoInicial = $consecutivoP1.$consecutivoP2.$consecutivoP3; 
           $nuevoConsecutivo = $ultimoDigito + 1;
           // Reiniciar el consecutivo si es un nuevo día
           if (date("Ymd") != $fechaActual) {
               $nuevoConsecutivo = 0;
           }
           // Poner ceros a la izquierda para llegar a una longitud de 6 dígitos
           $nuevoConsecutivoFormatted = str_pad($nuevoConsecutivo, 6, "0", STR_PAD_LEFT);
           $consecutivo = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted; 
           
       }else{
           $fechaActual = date("Ymd");
           $ultimoConsecutivo = $radicadocomunicado[0]->N_radicado;
           $ultimoDigito = substr($ultimoConsecutivo, -6);
           $nuevoConsecutivo = $ultimoDigito + 1;
           // Reiniciar el consecutivo si es un nuevo día
           if (date("Ymd") != $fechaActual) {
               $nuevoConsecutivo = 0;
           }
           // Poner ceros a la izquierda para llegar a una longitud de 6 dígitos
           $nuevoConsecutivoFormatted = str_pad($nuevoConsecutivo, 6, "0", STR_PAD_LEFT);
           $consecutivo = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted;
       }

       $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
       ->select('Id_Documento_Solicitado', 'Aporta_documento')
       ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion], ['Estado', 'Inactivo'], ['Aporta_documento', 'No']])
       ->get();

        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));

        $arraycampa_documento_solicitado = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $newIdEvento],
            ['Estado', 'Activo'],
        ])
        ->get();
        
        return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'array_datos_destinatarios', 'listado_documentos_solicitados', 'arraylistado_documentos', 'dato_validacion_no_aporta_docs','arraylistado_documentos','SubModulo','consecutivo','arraycampa_documento_solicitado'));
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

            sleep(2);

            $datos_info_historial_acciones = [
                'ID_evento' => $newIdEvento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Guardado Modulo Calificacion Pcl.",
                'Descripcion' => $request->descripcion_accion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
            sleep(2);
            $mensajes = array(
                "parametro" => 'agregarCalificacionPcl',
                "parametro_1" => 'guardo',
                "mensaje_1" => 'Registro agregado satisfactoriamente.'
            );

            return json_decode(json_encode($mensajes, true));

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

            sleep(2);

            $datos_info_historial_acciones = [
                'ID_evento' => $newIdEvento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Actualizado Modulo Calificacion Pcl.",
                'Descripcion' => $request->descripcion_accion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
            sleep(2);
            $mensajes = array(
                "parametro" => 'agregarCalificacionPcl',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }

        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));

        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));
    
        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where('Estado', 'Activo')
        ->get();

        $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'Aporta_documento')
        ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion], ['Estado', 'Inactivo'], ['Aporta_documento', 'No']])
        ->get();
        

        $arraycampa_documento_solicitado = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $newIdEvento],
            ['Estado', 'Activo'],
        ])
        ->get();

        return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'arraylistado_documentos', 'listado_documentos_solicitados', 'dato_validacion_no_aporta_docs','arraycampa_documento_solicitado'));
        
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
            ->where([['ID_evento', $request->Id_evento],['Id_Asignacion', $request->Id_Asignacion], ['Estado', 'Inactivo'], ['Aporta_documento', 'No']])
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
                    //'F_solicitud_documento' => "",
                    'Id_Documento' => 0,
                    'Nombre_documento' => "N/A",
                    'Descripcion' => "N/A",
                    'Id_solicitante' => 0,
                    'Nombre_solicitante' => "N/A",
                    //'F_recepcion_documento' => "",
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
        
        sleep(2);
        $datos_info_historial_acciones = [
            'ID_evento' => $newIdEvento,
            'F_accion' => $date,
            'Nombre_usuario' => $usuario,
            'Accion_realizada' => "Se agrego seguimiento.",
            'Descripcion' => $descripcion_seguimiento,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);

        $mensajes = array(
            "parametro" => 'agregar_seguimiento',
            "mensaje" => 'Seguimiento agregado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    // Captura de la data para el DataTable Historial de Seguimientos

    public function historialSeguimientosPCL(Request $request){
        $HistorialSeguimientoPcl = $request->HistorialSeguimientoPcl;
        $newId_evento = $request->newId_evento;
        $newId_asignacion = $request->newId_asignacion;

        if ($HistorialSeguimientoPcl == 'CargaHistorialSeguimiento') {
            
            $hitorialAgregarSeguimiento = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->select('ID_evento','F_seguimiento','Causal_seguimiento','Descripcion_seguimiento','Nombre_usuario')
            ->where([
                ['ID_evento', $newId_evento]
            ])
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
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
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
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
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
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
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

    public function guardarComunicado(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Id_evento = $request->Id_evento;
        $Id_asignacion = $request->Id_asignacion;
        $Id_procesos = $request->Id_procesos;
        $radioafiliado_comunicado = $request->radioafiliado_comunicado;
        $radioempresa_comunicado = $request->radioempresa_comunicado;
        $radioOtro = $request->radioOtro;
        $total_agregarcopias = '';
        $agregar_copia = $request->agregar_copia;
        $agregarcopias = implode(", ", $agregar_copia);
        if ($agregarcopias == 'CopiaVacia') {
            $total_agregarcopias = '';
        }else{
            $total_agregarcopias = $agregarcopias;
        }
        if(!empty($radioafiliado_comunicado) && empty($radioempresa_comunicado) && empty($radioOtro)){
            $destinatario = 'Afiliado';
        }elseif(empty($radioafiliado_comunicado) && !empty($radioempresa_comunicado) && empty($radioOtro)){
            $destinatario = 'Empresa';
        }elseif(empty($radioafiliado_comunicado) && empty($radioempresa_comunicado) && !empty($radioOtro)){
            $destinatario = 'Otro';
        }
        $datos_info_registrarComunicadoPcl=[

            'ID_evento' => $Id_evento,
            'Id_Asignacion' => $Id_asignacion,
            'Id_proceso' => $Id_procesos,
            'Ciudad' => $request->ciudad,
            'F_comunicado' => $request->fecha_comunicado2,
            'N_radicado' => $request->radicado2,
            'Cliente' => $request->cliente_comunicado2,
            'Nombre_afiliado' => $request->nombre_afiliado_comunicado2,
            'T_documento' => $request->tipo_documento_comunicado2,
            'N_identificacion' => $request->identificacion_comunicado2,
            'Destinatario' => $destinatario,
            'Nombre_destinatario' => $request->nombre_destinatario,
            'Nit_cc' => $request->nic_cc,
            'Direccion_destinatario' => $request->direccion_destinatario,
            'Telefono_destinatario' => $request->telefono_destinatario,
            'Email_destinatario' => $request->email_destinatario,
            'Id_departamento' => $request->departamento_destinatario,
            'Id_municipio' => $request->ciudad_destinatario,
            'Asunto' => $request->asunto,
            'Cuerpo_comunicado' => $request->cuerpo_comunicado,
            'Anexos' => $request->anexos,
            'Forma_envio' => $request->forma_envio,
            'Elaboro' => $request->elaboro2,
            'Reviso' => $request->reviso,
            'Agregar_copia' => $total_agregarcopias,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];
        
        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_registrarComunicadoPcl);

        sleep(2);
        $datos_info_historial_acciones = [
            'ID_evento' => $Id_evento,
            'F_accion' => $date,
            'Nombre_usuario' => $nombre_usuario,
            'Accion_realizada' => "Se genera comunicado.",
            'Descripcion' => $request->asunto,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        
        $mensajes = array(
            "parametro" => 'agregar_comunicado',
            "mensaje" => 'Comunicado generado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    public function historialComunicadosPCL(Request $request){

        $HistorialComunicadosPcl = $request->HistorialComunicadosPcl;
        $newId_evento = $request->newId_evento;
        $newId_asignacion = $request->newId_asignacion;        
        if ($HistorialComunicadosPcl == 'CargarComunicados') {
            
            $hitorialAgregarComunicado = cndatos_info_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $newId_evento]
            ])
            ->get();
            $arrayhitorialAgregarComunicado = json_decode(json_encode($hitorialAgregarComunicado, true));
            return response()->json(($arrayhitorialAgregarComunicado));

        }
        
    }

    public function mostrarModalComunicadoPCL(Request $request){

        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;        
        $destinatario_principal_comu = $request->destinatario_principal;
        $id_evento = $request->id_evento;
        $id_asignacion = $request->id_asignacion;
        $id_proceso = $request->id_proceso;
        $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
        ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
        ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
        ->where([['sgt.Id_proceso_equipo', '=', $id_proceso]])->get();

        return response()->json([
            'destinatario_principal_comu' => $destinatario_principal_comu,
            'array_datos_lider' => $array_datos_lider,
        ]);
        
    }

    public function actualizarComunicado(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Id_comunicado_editar = $request->Id_comunicado_editar;
        $Id_evento_editar = $request->Id_evento_editar;
        $Id_asignacion_editar = $request->Id_asignacion_editar;
        $Id_procesos_editar = $request->Id_procesos_editar;
        $radioafiliado_comunicado_editar = $request->radioafiliado_comunicado_editar;
        $radioempresa_comunicado_editar = $request->radioempresa_comunicado_editar;
        $radioOtro_editar = $request->radioOtro_editar;
        $total_agregarcopias = '';
        $agregar_copia_editar = $request->agregar_copia_editar;
        $agregarcopias = implode(", ", $agregar_copia_editar);
        if ($agregarcopias == 'CopiaVacia') {
            $total_agregarcopias = '';
        }else{
            $total_agregarcopias = $agregarcopias;
        }
        if(!empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar) && empty($radioOtro_editar)){
            $destinatario = 'Afiliado';
        }elseif(empty($radioafiliado_comunicado_editar) && !empty($radioempresa_comunicado_editar) && empty($radioOtro_editar)){
            $destinatario = 'Empresa';
        }elseif(empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar) && !empty($radioOtro_editar)){
            $destinatario = 'Otro';
        }

        $datos_info_actualizarComunicadoPcl=[

            'ID_evento' => $Id_evento_editar,
            'Id_Asignacion' => $Id_asignacion_editar,
            'Id_proceso' => $Id_procesos_editar,
            'Ciudad' => $request->ciudad_editar,
            'F_comunicado' => $request->fecha_comunicado2_editar,
            'N_radicado' => $request->radicado2_editar,
            'Cliente' => $request->cliente_comunicado2_editar,
            'Nombre_afiliado' => $request->nombre_afiliado_comunicado2_editar,
            'T_documento' => $request->tipo_documento_comunicado2_editar,
            'N_identificacion' => $request->identificacion_comunicado2_editar,
            'Destinatario' => $destinatario,
            'Nombre_destinatario' => $request->nombre_destinatario_editar,
            'Nit_cc' => $request->nic_cc_editar,
            'Direccion_destinatario' => $request->direccion_destinatario_editar,
            'Telefono_destinatario' => $request->telefono_destinatario_editar,
            'Email_destinatario' => $request->email_destinatario_editar,
            'Id_departamento' => $request->departamento_destinatario_editar,
            'Id_municipio' => $request->ciudad_destinatario_editar,
            'Asunto' => $request->asunto_editar,
            'Cuerpo_comunicado' => $request->cuerpo_comunicado_editar,
            'Anexos' => $request->anexos_editar,
            'Forma_envio' => $request->forma_envio_editar,
            'Elaboro' => $request->elaboro2_editar,
            'Reviso' => $request->reviso_editar,
            'Agregar_copia' => $total_agregarcopias,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];

        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado_editar)
        ->update($datos_info_actualizarComunicadoPcl);

        sleep(2);
        $datos_info_historial_acciones = [
            'ID_evento' => $Id_evento_editar,
            'F_accion' => $date,
            'Nombre_usuario' => $nombre_usuario,
            'Accion_realizada' => "Se actualiza comunicado.",
            'Descripcion' => $request->asunto_editar,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        
        $mensajes = array(
            "parametro" => 'actualizar_comunicado',
            "mensaje" => 'Comunicado actualizado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    public function generarPdf(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;
        $departamento_pdf = $request->departamento_pdf;
        $departamento_destinatario_act = $request->departamento_destinatario_act;
        $ciudad_pdf = $request->ciudad_pdf;
        $ciudad_destinatario_act = $request->ciudad_destinatario_act;
        $Forma_envio = $request->forma_envio_act;
        $Reviso = $request->reviso_act;
        $nombre_destinatario = $request->nombre_destinatario_act2;
        $nit_cc = $request->nic_cc_act2;
        $direccion_destinatario = $request->direccion_destinatario_act2;
        $telefono_destinatario = $request->telefono_destinatario_act2;
        $email_destinatario = $request->email_destinatario_act2;

        if (empty($departamento_destinatario_act) && empty($ciudad_destinatario_act)) {
            $Id_departamento = $departamento_pdf;
            $Id_municipio = $ciudad_pdf;
        }elseif(!empty($departamento_destinatario_act) && !empty($ciudad_destinatario_act)){
            $Id_departamento = $departamento_destinatario_act;
            $Id_municipio = $ciudad_destinatario_act;
        }

        if (empty($nombre_destinatario) && empty($nit_cc) && empty($direccion_destinatario) && 
            empty($telefono_destinatario) && empty($email_destinatario)) {
                $Nombre_destinatario = $request->nombre_destinatario_act;
                $Nit_cc = $request->nic_cc_editar;
                $Direccion_destinatario = $request->direccion_destinatario_act;
                $Telefono_destinatario = $request->telefono_destinatario_act;
                $Email_destinatario = $request->email_destinatario_act;

        }elseif(!empty($nombre_destinatario) && !empty($nit_cc) && !empty($direccion_destinatario) && 
            !empty($telefono_destinatario) && !empty($email_destinatario)){
                $Nombre_destinatario = $nombre_destinatario;
                $Nit_cc = $nit_cc;
                $Direccion_destinatario = $direccion_destinatario;
                $Telefono_destinatario = $telefono_destinatario;
                $Email_destinatario = $email_destinatario;
         }

        $departamentos_info_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
        ->select('Nombre_departamento')
        ->where('Id_departamento',$Id_departamento)
        ->get();
        sleep(2);
        $ciudad_info_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
        ->select('Nombre_municipio')
        ->where('Id_municipios',$Id_municipio)
        ->get();
        sleep(2);         
        $reviso_info_lider = DB::table('users')
        ->select('name')
        ->where('id', $Reviso)
        ->get();
        sleep(2);
        $forma_info_envio = sigmel_lista_parametros::on('sigmel_gestiones')
        ->select('Nombre_parametro')
        ->where('Id_parametro', $Forma_envio)
        ->get();
        sleep(2);
        $nombre_departamento = $departamentos_info_comunicado[0]->Nombre_departamento;
        $nombre_ciudad = $ciudad_info_comunicado[0]->Nombre_municipio;
        $reviso_lider = $reviso_info_lider[0]->name;
        $forma_envio = $forma_info_envio[0]->Nombre_parametro;

        $total_copias = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {            
            // Obtén el valor de todos los inputs dentro de un div con el ID "myDiv"
            foreach ($_POST as $key => $value) {
                // Verifica si el nombre del input contiene "myDiv" en su nombre
                if (strpos($key, 'input') !== false) {                    
                    array_push($total_copias, $value);                    
                }
            }
        }
        sleep(2);              
        $Id_comunicado = $request->Id_comunicado_act;
        $ID_evento = $request->Id_evento_act;
        $Id_Asignacion = $request->Id_asignacion_act;
        $Id_proceso = $request->Id_procesos_act;
        $Ciudad = $request->ciudad_comunicado_act;
        $F_comunicado = $request->fecha_comunicado2_act;
        $N_radicado = $request->radicado2_act;
        $Cliente = $request->cliente_comunicado2_act;
        $Nombre_afiliado = $request->nombre_afiliado_comunicado2_act;
        $T_documento = $request->tipo_documento_comunicado2_act;
        $N_identificacion = $request->identificacion_comunicado2_act;
        $Destinatario = $request->afiliado_comunicado_act;
        $Nombre_departamento = $nombre_departamento;
        $Nombre_ciudad = $nombre_ciudad;
        $Asunto = $request->asunto_act;
        $Cuerpo_comunicado = $request->cuerpo_comunicado_act;
        $Anexos = $request->anexos_act;
        $Forma_envios = $forma_envio;
        $Elaboro = $request->elaboro2_act;
        $Cargo = $cargo_profesional;
        $Reviso_lider = $reviso_lider;
        $Agregar_copias = implode(", ",$total_copias);
        $Nombre_usuario = $nombre_usuario;
        $F_registro = $date;
 
        // Obtener los datos del formulario
        $data = [
            'ID_evento' => $ID_evento,
            'Id_Asignacion' => $Id_Asignacion,
            'Id_proceso' => $Id_proceso,
            'Ciudad' => $Ciudad,
            'F_comunicado' => $F_comunicado,
            'N_radicado' => $N_radicado,
            'Cliente' => $Cliente,
            'Nombre_afiliado' => $Nombre_afiliado,
            'T_documento' => $T_documento,
            'N_identificacion' => $N_identificacion,
            'Destinatario' => $Destinatario,
            'Nombre_destinatario' => $Nombre_destinatario,
            'Nit_cc' => $Nit_cc,
            'Direccion_destinatario' => $Direccion_destinatario,
            'Telefono_destinatario' => $Telefono_destinatario,
            'Email_destinatario' => $Email_destinatario,
            'Nombre_departamento' => $Nombre_departamento,
            'Nombre_ciudad' => $Nombre_ciudad,
            'Asunto' => $Asunto,
            'Cuerpo_comunicado' => $Cuerpo_comunicado,
            'Anexos' => $Anexos,
            'Forma_envio' => $Forma_envios,
            'Elaboro' => $Elaboro,
            'Cargo' => $Cargo,
            'Reviso' => $Reviso_lider,
            'Agregar_copia' => $Agregar_copias,
            'Nombre_usuario' => $Nombre_usuario,
            'F_registro' => $F_registro,
        ];
        // Crear una instancia de Dompdf

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/coordinador/comunicadoPdf', $data);
        $fileName = 'Comunicado_'.$N_radicado.'.pdf';
        return $pdf->download($fileName);        
    }

    public function historialAcciones(Request $request){

        $datos_info_historial_acciones = sigmel_historial_acciones_eventos::on('sigmel_gestiones')
        ->select('F_accion', 'Nombre_usuario', 'Accion_realizada', 'Descripcion')
        ->where('ID_evento', $request->ID_evento)
        ->orderBy('F_accion', 'asc')
        ->get();

        return response()->json($datos_info_historial_acciones);
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

        $datos_demos =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
        ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
        'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
        ->where([['side.ID_Evento',$Id_evento_calitec]])->get(); 

        // Obtener el último consecutivo de la base de datos
        $consecutivoDictamen = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
        ->max('Numero_dictamen');

        if ($consecutivoDictamen > 0) {
            $numero_consecutivo = $consecutivoDictamen + 1;
        }else{
            $numero_consecutivo = 0000000 + 1;
        }
        // Formatear el número consecutivo a 7 dígitos
        $numero_consecutivo = str_pad($numero_consecutivo, 7, "0", STR_PAD_LEFT);

        $array_info_decreto_evento = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
        ->where([
            ['ID_Evento', $Id_evento_calitec]
        ])
        ->get();
        if (count($array_info_decreto_evento) > 0) {

            $Historiaclínicacompleta  = "Historia clínica completa";
            $Exámenespreocupacionales = "Exámenes preocupacionales";
            $Epicrisis = "Epicrisis";
            $Exámenesperiódicosocupacionales  = "Exámenes periódicos ocupacionales";
            $Exámenesparaclinicos  = "Exámenes paraclinicos";
            $ExámenesPostocupacionales  = "Exámenes Post-ocupacionales";
            $Conceptosdesaludocupacional   = "Conceptos de salud ocupacional";

            $arraytotalRealcionDocumentos = [
                'Historia clínica completa',
                'Exámenes preocupacionales',
                'Epicrisis',
                'Exámenes periódicos ocupacionales',
                'Exámenes paraclinicos',
                'Exámenes Post-ocupacionales',
                'Conceptos de salud ocupacional',
            ];

            foreach ($arraytotalRealcionDocumentos as &$valor) {    
                $valor = trim($valor);
                $valor = str_replace("-", "", $valor);  
                //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                $valor = preg_replace("/\s+/", "", $valor); 
            }
            $relacionDocuementos = $array_info_decreto_evento[0]->Relacion_documentos;
            $separaRelacionDocumentos = explode(", ",$relacionDocuementos);  
            
            foreach ($separaRelacionDocumentos as &$valor) {    
                $valor = trim($valor);
                $valor = str_replace("-", "", $valor);  
                //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                $valor = preg_replace("/\s+/", "", $valor);     
            }
            foreach ($arraytotalRealcionDocumentos as $index => $value) {
                if (!in_array($value, $separaRelacionDocumentos)) {
                    ${$value} = "vacio";
                }
            }                       
        }else{
            list(
                $Historiaclínicacompleta, 
                $Exámenespreocupacionales, 
                $Epicrisis, 
                $Exámenesperiódicosocupacionales, 
                $Exámenesparaclinicos, 
                $ExámenesPostocupacionales, 
                $Conceptosdesaludocupacional
            ) = array_fill(0, 7, 'vacio');
        }
        $array_datos_relacion_documentos = [
            'Historiaclinicacompleta' => $Historiaclínicacompleta, 
            'Examenespreocupacionales' => $Exámenespreocupacionales, 
            'Epicrisis' => $Epicrisis, 
            'Examenesperiodicosocupacionales' => $Exámenesperiódicosocupacionales, 
            'Examenesparaclinicos' => $Exámenesparaclinicos, 
            'ExamenesPostocupacionales' => $ExámenesPostocupacionales, 
            'Conceptosdesaludocupacion' => $Conceptosdesaludocupacional,
        ];

        $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_calitec],
            ['Estado', 'Activo']
        ])
        ->get();

        $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['side.ID_evento',$Id_evento_calitec],['side.Estado', '=', 'Activo']])->get(); 
        
        $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
        ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
        'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
        'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
        ->where([['sidae.ID_evento',$Id_evento_calitec],['sidae.Estado', '=', 'Activo']])->get();         
        
        $array_agudeza_Auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_calitec],
            ['Estado', 'Activo']
        ])
        ->get();

        $array_laboralmente_Activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_calitec]
        ])
        ->get();

        $array_rol_ocupacional =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_rol_ocupacional_eventos as siroe')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siroe.Poblacion_calificar')
        ->select('siroe.Id_Rol_ocupacional', 'siroe.ID_evento', 'siroe.Id_Asignacion', 'siroe.Id_proceso', 'siroe.Poblacion_calificar', 
        'slp.Nombre_parametro', 'siroe.Motriz_postura_simetrica', 'siroe.Motriz_actividad_espontanea', 'siroe.Motriz_sujeta_cabeza',
        'siroe.Motriz_sentarse_apoyo', 'siroe.Motriz_gira_sobre_mismo', 'siroe.Motriz_sentanser_sin_apoyo', 'siroe.Motriz_pasa_tumbado_sentado',
        'siroe.Motriz_pararse_apoyo', 'siroe.Motriz_pasos_apoyo', 'siroe.Motriz_pararse_sin_apoyo', 'siroe.Motriz_anda_solo', 'siroe.Motriz_empujar_pelota_pies',
        'siroe.Motriz_andar_obstaculos', 'siroe.Adaptativa_succiona', 'siroe.Adaptativa_fija_mirada', 'siroe.Adaptativa_sigue_trayectoria_objeto',
        'siroe.Adaptativa_sostiene_sonajero', 'siroe.Adaptativa_tiende_mano_hacia_objeto', 'siroe.Adaptativa_sostiene_objeto_manos',
        'siroe.Adaptativa_abre_cajones', 'siroe.Adaptativa_bebe_solo', 'siroe.Adaptativa_quitar_prenda_vestir', 
        'siroe.Adaptativa_reconoce_funcion_espacios_casa', 'siroe.Adaptativa_imita_trazo_lapiz', 'siroe.Adaptativa_abre_puerta',
        'siroe.Total_criterios_desarrollo', 'siroe.Juego_estudio_clase', 'siroe.Total_rol_estudio_clase', 'siroe.Adultos_mayores',
        'siroe.Total_rol_adultos_ayores', 'siroe.Nombre_usuario', 'siroe.F_registro')
        ->where([['siroe.ID_evento',$Id_evento_calitec]])->get();    
        
        $array_libros_2_3 = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_calitec]
        ])
        ->get();

        $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?)', array($Id_evento_calitec));
        $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?)', array($Id_evento_calitec));
        $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?)', array($Id_evento_calitec));
        $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?)', array($Id_evento_calitec));
        $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?)', array($Id_evento_calitec));
        $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?)', array($Id_evento_calitec));
        $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?)', array($Id_evento_calitec));

        if(!empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
            $array_Deficiencias50 = $array_datos_deficiencicas50[0]->deficiencias;
            $deficiencias = explode(",", $array_Deficiencias50);
            //print_r($deficiencias);                        

            $ultimos_valores = array_slice($deficiencias, -1);
            list($agudezaAudtivaDef) = $ultimos_valores;
            
            foreach ($deficiencias as $index => $value) {
                if ($value == $agudezaAudtivaDef) {
                    $deficiencias[$index] = $agudezaAudtivaDef * 2;
                }
            }            
            //print_r($deficiencias);
                                  
            //print_r($deficiencias);
            while(count($deficiencias) > 1) {
                $a = $deficiencias[0];
                $b = $deficiencias[1];
                $resultado = $a + (100 - $a) * $b / 100;
                array_shift($deficiencias);
                array_shift($deficiencias);
                array_unshift($deficiencias, $resultado);
            }
            //print_r($deficiencias);
            foreach ($deficiencias as &$value) {
                $value = round($value, 2); 
                               
                $TotalDeficiencia50 = $value * 50 / 100;
            }
            
        }elseif(empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
            $array_Deficiencias50 = $array_datos_deficiencicas50_1[0]->deficiencias;
            $deficiencias = explode(",", $array_Deficiencias50);
            //print_r($deficiencias);            
                       
            while(count($deficiencias) > 1) {
                $a = $deficiencias[0];
                $b = $deficiencias[1];
                $resultado = $a + (100 - $a) * $b / 100;
                array_shift($deficiencias);
                array_shift($deficiencias);
                array_unshift($deficiencias, $resultado);
            }
            //print_r($deficiencias);
            foreach ($deficiencias as &$value) {
                $value = round($value, 2); 
                               
                $TotalDeficiencia50 = $value * 50 / 100;
            }
            
        }elseif(empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)){
            $array_Deficiencias50 = $array_datos_deficiencicas50_2[0]->deficiencias;
            $deficiencias = explode(",", $array_Deficiencias50);
            //print_r($deficiencias);    
            usort($deficiencias, function($a, $b) {
                $numA = preg_replace('/[^0-9.]+/', '', $a);
                $numB = preg_replace('/[^0-9.]+/', '', $b);
            
                if ($numA > $numB) {
                    return -1;
                } else if ($numA < $numB) {
                    return 1;
                } else {
                    return 0;
                }
            });            
            //print_r($deficiencias);
            foreach ($deficiencias as $key => $value) {
                if (strpos($value, "(si)") !== false) {
                    $deficiencias[$key] = 23.20;
                }
            }
            //print_r($deficiencias);            
            while(count($deficiencias) > 1) {
                $a = $deficiencias[0];
                $b = $deficiencias[1];
                $resultado = $a + (100 - $a) * $b / 100;
                array_shift($deficiencias);
                array_shift($deficiencias);
                array_unshift($deficiencias, $resultado);
            }
            //print_r($deficiencias);
            foreach ($deficiencias as &$value) {
                $value = round($value, 2); 
                               
                $TotalDeficiencia50 = $value * 50 / 100;
            }
            
        }elseif(!empty($array_datos_deficiencicas50_3) && empty($array_datos_deficiencicas50_1)) {
            $array_Deficiencias50 = $array_datos_deficiencicas50_3[0]->deficiencias;
            $deficiencias = explode(",", $array_Deficiencias50);
            //print_r($deficiencias);            
            $ultimos_valores = array_slice($deficiencias, -1);
            list($agudezaAudtivaDef) = $ultimos_valores;
            
            //print_r($deficiencias);
            usort($deficiencias, function($a, $b) {
                $numA = preg_replace('/[^0-9.]+/', '', $a);
                $numB = preg_replace('/[^0-9.]+/', '', $b);
            
                if ($numA > $numB) {
                    return -1;
                } else if ($numA < $numB) {
                    return 1;
                } else {
                    return 0;
                }
            });            
            //print_r($deficiencias);
            foreach ($deficiencias as $key => $value) {
                if (strpos($value, "(si)") !== false) {
                    $deficiencias[$key] = 23.20;
                }
            }
            //print_r($deficiencias);
            $indexDoble = null;            
            foreach ($deficiencias as $index => $value) {
                if ($value == $agudezaAudtivaDef) {
                    $indexDoble = $index;
                    break;
                }
            }            
            if ($indexDoble !== null) {
                $deficiencias[$indexDoble] *= 2;
            }            
            //print_r($deficiencias);
            while(count($deficiencias) > 1) {
                $a = $deficiencias[0];
                $b = $deficiencias[1];
                $resultado = $a + (100 - $a) * $b / 100;
                array_shift($deficiencias);
                array_shift($deficiencias);
                array_unshift($deficiencias, $resultado);
            }
            //print_r($deficiencias);
            foreach ($deficiencias as &$value) {
                $value = round($value, 2); 
                               
                $TotalDeficiencia50 = $value * 50 / 100;
            }
            
        }elseif(!empty($array_datos_deficiencicas50_4) && empty($array_datos_deficiencicas50)){
            $array_Deficiencias50 = $array_datos_deficiencicas50_4[0]->deficiencias;
            $deficiencias = explode(",", $array_Deficiencias50);
            //print_r($deficiencias);  
            usort($deficiencias, function($a, $b) {
                $numA = preg_replace('/[^0-9.]+/', '', $a);
                $numB = preg_replace('/[^0-9.]+/', '', $b);
            
                if ($numA > $numB) {
                    return -1;
                } else if ($numA < $numB) {
                    return 1;
                } else {
                    return 0;
                }
            });            
            //print_r($deficiencias);
            foreach ($deficiencias as $key => $value) {
                if (strpos($value, "(si)") !== false) {
                    $deficiencias[$key] = 23.20;
                }
            }                       
            //print_r($deficiencias);
            while(count($deficiencias) > 1) {
                $a = $deficiencias[0];
                $b = $deficiencias[1];
                $resultado = $a + (100 - $a) * $b / 100;
                array_shift($deficiencias);
                array_shift($deficiencias);
                array_unshift($deficiencias, $resultado);
            }
            //print_r($deficiencias);
            foreach ($deficiencias as &$value) {
                $value = round($value, 2); 
                               
                $TotalDeficiencia50 = $value * 50 / 100;
            }
            
            
        }elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
            $array_Deficiencias50 = $array_datos_deficiencicas50_5[0]->deficiencias;
            $deficiencias = explode(",", $array_Deficiencias50);
            //print_r($deficiencias);            
            $ultimos_valores = array_slice($deficiencias, -2);
            list($agudezaAudtivaDef, $agudezaVisualDef) = $ultimos_valores;
                
            $indexDoble = null;            
            foreach ($deficiencias as $index => $value) {
                if ($value == $agudezaAudtivaDef) {
                    $indexDoble = $index;
                    break;
                }
            }            
            if ($indexDoble !== null) {
                $deficiencias[$indexDoble] *= 2;
            }            
            //print_r($deficiencias);
            while(count($deficiencias) > 1) {
                $a = $deficiencias[0];
                $b = $deficiencias[1];
                $resultado = $a + (100 - $a) * $b / 100;
                array_shift($deficiencias);
                array_shift($deficiencias);
                array_unshift($deficiencias, $resultado);
            }
            //print_r($deficiencias);
            foreach ($deficiencias as &$value) {
                $value = round($value, 2); 
                               
                $TotalDeficiencia50 = $value * 50 / 100;
            }
            
        }elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)) {
            $array_Deficiencias50 = $array_datos_deficiencicas50_6[0]->deficiencias;
            $deficiencias = explode(",", $array_Deficiencias50);
            //print_r($deficiencias);            
            $ultimos_valores = array_slice($deficiencias, -2);
            list($agudezaAudtivaDef, $agudezaVisualDef) = $ultimos_valores;
                       
            //print_r($deficiencias);
            usort($deficiencias, function($a, $b) {
                $numA = preg_replace('/[^0-9.]+/', '', $a);
                $numB = preg_replace('/[^0-9.]+/', '', $b);
            
                if ($numA > $numB) {
                    return -1;
                } else if ($numA < $numB) {
                    return 1;
                } else {
                    return 0;
                }
            });            
            //print_r($deficiencias);
            foreach ($deficiencias as $key => $value) {
                if (strpos($value, "(si)") !== false) {
                    $deficiencias[$key] = 23.20;
                }
            }
            //print_r($deficiencias);
            $indexDoble = null;            
            foreach ($deficiencias as $index => $value) {
                if ($value == $agudezaAudtivaDef) {
                    $indexDoble = $index;
                    break;
                }
            }            
            if ($indexDoble !== null) {
                $deficiencias[$indexDoble] *= 2;
            }        
            //print_r($deficiencias);
            while(count($deficiencias) > 1) {
                $a = $deficiencias[0];
                $b = $deficiencias[1];
                $resultado = $a + (100 - $a) * $b / 100;
                array_shift($deficiencias);
                array_shift($deficiencias);
                array_unshift($deficiencias, $resultado);
            }
            //print_r($deficiencias);
            foreach ($deficiencias as &$value) {
                $value = round($value, 2); 
                               
                $TotalDeficiencia50 = $value * 50 / 100;
            }
            
        }else{
            $deficiencias = 0;
            $TotalDeficiencia50 =0;
        }

        $array_dictamen_pericial =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
        ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
        'side.F_evento', 'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia')
        ->where([['side.ID_evento',$Id_evento_calitec]])->get();        

        return view('coordinador.calificacionTecnicaPCL', compact('user','array_datos_calificacionPclTecnica','motivo_solicitud_actual','datos_apoderado_actual', 'hay_agudeza_visual','datos_demos','array_info_decreto_evento','array_datos_relacion_documentos','array_datos_examenes_interconsultas','numero_consecutivo','array_datos_diagnostico_motcalifi', 'array_agudeza_Auditiva', 'array_datos_deficiencias_alteraciones', 'array_laboralmente_Activo', 'array_rol_ocupacional', 'array_libros_2_3', 'deficiencias', 'TotalDeficiencia50', 'array_dictamen_pericial'));
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

         // Listado poblacion a calificar PCL
         if($parametro == 'lista_poblacion_calificar'){
            $listado_poblacion_califi = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Poblacion a calificar'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_poblacion_califi = json_decode(json_encode($listado_poblacion_califi, true));
            return response()->json($info_listado_poblacion_califi);
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

        // Listado cie diagnosticos motivo calificacion (Calificacion Tecnica)
        if ($parametro == 'listado_CIE10') {
            $listado_cie_diagnostico = sigmel_lista_cie_diagnosticos::on('sigmel_gestiones')
            ->select('Id_Cie_diagnostico', 'CIE10')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_cie_diagnostico = json_decode(json_encode($listado_cie_diagnostico, true));
            return response()->json($info_listado_cie_diagnostico);
        }

        // Listado Origen CIE10 diagnosticos motivo calificacion (Calificacion Tecnica)
        if ($parametro == 'listado_OrgienCIE10') {
            $listado_Origen_CIE10 = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Origen Cie10'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Origen_CIE10 = json_decode(json_encode($listado_Origen_CIE10, true));
            return response()->json($info_listado_Origen_CIE10);
        }

        //Nombre diagnostico CIE10
        $Id_CIE = $request->seleccion;
        
        if ($parametro == 'listado_NombreCIE10') {
            $listado_Nombre_CIE10 = sigmel_lista_cie_diagnosticos::on('sigmel_gestiones')
            ->select('Descripcion_diagnostico')
            ->where([
                ['Id_Cie_diagnostico', '=', $Id_CIE],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Nombre_CIE10 = json_decode(json_encode($listado_Nombre_CIE10, true));
            return response()->json($info_listado_Nombre_CIE10);
            
        }

        // Listados agudeza auditiva
        if ($parametro == 'agudeza_auditiva') {
            $listado_Agudeza_auditiva = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'agudeza auditiva'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Agudeza_auditiva = json_decode(json_encode($listado_Agudeza_auditiva, true));
            return response()->json($info_listado_Agudeza_auditiva);
            
        }

        // Listados tipo evento
        if ($parametro == 'lista_tipo_evento') {
            $listado_Tipo_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Id_Evento', 'Nombre_evento')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Tipo_evento = json_decode(json_encode($listado_Tipo_evento, true));
            return response()->json($info_listado_Tipo_evento);
            
        }

        // Listados Origen
        if ($parametro == 'lista_origen') {
            $listado_Origen = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Origen Cie10'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_listado_Origen = json_decode(json_encode($listado_Origen, true));
            return response()->json($info_listado_listado_Origen);
            
        }

        // Listados Tipo de enfermedad
        if ($parametro == 'lista_Tipo_enfermedad') {
            $listado_Tipo_enfermedad = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Tipo enfermedad'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Tipo_enfermedad = json_decode(json_encode($listado_Tipo_enfermedad, true));
            return response()->json($info_listado_Tipo_enfermedad);
            
        }
        

    }

    //4 Formularios iniciales

    public function guardarDecretoDicRelaDocFund(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;
        $id_Evento_decreto = $request->Id_Evento_decreto;
        $id_Proceso_decreto = $request->Id_Proceso_decreto;
        $id_Asignacion_decreto = $request->Id_Asignacion_decreto;
        $origen_firme = $request->origen_firme;
        $origen_cobertura = $request->origen_cobertura;        

        if ($origen_firme == 49 && $origen_cobertura == 51 || $origen_firme == 48 && $origen_cobertura == 51 || $origen_firme == 49 && $origen_cobertura == 50) {
            $banderaGuardarNoDecreto = $request->banderaGuardarNoDecreto;
            $decreto_califi = $request->decreto_califi;  
            
            if ($banderaGuardarNoDecreto == 'Guardar') {
                $datos_info_Nodecreto_eventos = [
                        'ID_Evento' => $id_Evento_decreto,
                        'Id_proceso' => $id_Proceso_decreto,
                        'Id_Asignacion' => $id_Asignacion_decreto,
                        'Origen_firme' => $origen_firme,
                        'Cobertura' => $origen_cobertura,
                        'Decreto_calificacion' => $decreto_califi,                    
                        'Nombre_usuario' => $usuario,
                        'F_registro' => $date,
                ];
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')->insert($datos_info_Nodecreto_eventos);
    
                $mensajes = array(
                    "parametro" => 'agregar_Nodecreto_parte',                
                    "mensaje" => 'Guardado satisfactoriamente.'
                );        
        
                return json_decode(json_encode($mensajes, true));
                
            } elseif($banderaGuardarNoDecreto == 'Actualizar'){

                $datos_info_Nodecreto_eventos = [

                    'ID_Evento' => $id_Evento_decreto,
                    'Id_proceso' => $id_Proceso_decreto,
                    'Id_Asignacion' => $id_Asignacion_decreto,
                    'Origen_firme' => $origen_firme,
                    'Cobertura' => $origen_cobertura,
                    'Decreto_calificacion' => $decreto_califi,                    
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];

                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where('ID_Evento', $id_Evento_decreto)->update($datos_info_Nodecreto_eventos);

                $mensajes = array(
                    "parametro" => 'actualizar_Nodecreto_parte',                
                    "mensaje2" => 'Actualizado satisfactoriamente.'
                );        
        
                return json_decode(json_encode($mensajes, true));
            }
            
            

        }elseif($origen_firme == 48 && $origen_cobertura == 50){
            if ($request->bandera_decreto_guardar_actualizar == 'Guardar') {
                
                $origen_firme = $request->origen_firme;
                $origen_cobertura = $request->origen_cobertura;
                $decreto_califi = $request->decreto_califi;
                $numeroDictamen = $request->numeroDictamen;
                $motivo_solicitud = $request->motivo_solicitud;         
                $relacion_documentos = $request->Relacion_Documentos;            
                if (!empty($relacion_documentos)) {
                    $total_relacion_documentos = implode(", ", $relacion_documentos);                
                }else{
                    $total_relacion_documentos = '';
                }
                $descripcion_otros = $request->descripcion_otros;
                $descripcion_enfermedad = $request->descripcion_enfermedad;
                $datos_info_decreto_eventos = [
                    'ID_Evento' => $id_Evento_decreto,
                    'Id_proceso' => $id_Proceso_decreto,
                    'Id_Asignacion' => $id_Asignacion_decreto,
                    'Origen_firme' => $origen_firme,
                    'Cobertura' => $origen_cobertura,
                    'Decreto_calificacion' => $decreto_califi,
                    'Numero_dictamen' => $numeroDictamen,
                    'Relacion_documentos' => $total_relacion_documentos,
                    'Otros_relacion_doc' => $descripcion_otros,
                    'Descripcion_enfermedad_actual' => $descripcion_enfermedad,
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];
    
                $dato_info_pericial_eventos = [
                    'Id_motivo_solicitud' => $motivo_solicitud,
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')->insert($datos_info_decreto_eventos);
                sleep(2);
                sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_pericial_eventos);
                
                $mensajes = array(
                    "parametro" => 'agregar_decreto_parte',
                    "parametro2" => 'guardo',
                    "mensaje" => 'Guardado satisfactoriamente.'
                );        
        
                return json_decode(json_encode($mensajes, true));
    
            }elseif($request->bandera_decreto_guardar_actualizar == 'Actualizar'){
    
                $origen_firme = $request->origen_firme;
                $origen_cobertura = $request->origen_cobertura;
                $decreto_califi = $request->decreto_califi;
                $numeroDictamen = $request->numeroDictamen;
                $motivo_solicitud = $request->motivo_solicitud;         
                $relacion_documentos = $request->Relacion_Documentos;
                if (!empty($relacion_documentos)) {
                    $total_relacion_documentos = implode(", ", $relacion_documentos);                
                }else{
                    $total_relacion_documentos = '';
                }
                $descripcion_otros = $request->descripcion_otros;
                $descripcion_enfermedad = $request->descripcion_enfermedad;
                $datos_info_decreto_eventos = [
                    'ID_Evento' => $id_Evento_decreto,
                    'Id_proceso' => $id_Proceso_decreto,
                    'Id_Asignacion' => $id_Asignacion_decreto,
                    'Origen_firme' => $origen_firme,
                    'Cobertura' => $origen_cobertura,
                    'Decreto_calificacion' => $decreto_califi,
                    'Numero_dictamen' => $numeroDictamen,
                    'Relacion_documentos' => $total_relacion_documentos,
                    'Otros_relacion_doc' => $descripcion_otros,
                    'Descripcion_enfermedad_actual' => $descripcion_enfermedad,
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];
    
                $dato_info_pericial_eventos = [
                    'Id_motivo_solicitud' => $motivo_solicitud,
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where('ID_Evento', $id_Evento_decreto)->update($datos_info_decreto_eventos);
                sleep(2);
                sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_pericial_eventos);
        
                $mensajes = array(
                    "parametro" => 'update_decreto_parte',
                    "mensaje2" => 'Actualizado satisfactoriamente.'
                ); 
    
                return json_decode(json_encode($mensajes, true));
    
            }
        }

    }

    public function guardarExamenesInterconsulta(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
        $max_id = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->max('Id_Examenes_interconsultas');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_examenes_interconsultas_eventos AUTO_INCREMENT = ".($max_id));
        }
        // Captura del array de los datos de la tabla
        $array_examenes_interconsultas = $request->datos_finales_examenes_interconsultas;

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];
        foreach ($array_examenes_interconsultas as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);

            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_examenes_interconsultas_eventos
        $array_tabla_examen_interconsulta = ['ID_evento','Id_Asignacion','Id_proceso',
        'F_examen_interconsulta','Nombre_examen_interconsulta','Descripcion_resultado',
        'Nombre_usuario','F_registro'];

        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_tabla_examen_interconsulta, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar_examen) {
            sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')->insert($insertar_examen);
        } 

        $mensajes = array(
            "parametro" => 'inserto_informacion',
            "mensaje" => 'Exámen e interconsulta guardado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function eliminarExamenInterconsulta(Request $request){
        $id_fila_examen = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')->where('Id_Examenes_interconsultas', $id_fila_examen)
        ->update($fila_actualizar);

        $total_registros_examen = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_examen_eliminada',
            'total_registros' => $total_registros_examen,
            "mensaje" => 'Exámen e Interconsulta eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    public function guardarDiagnosticoMotivoCalificacion(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
        $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->max('Id_Diagnosticos_motcali');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
        }

        // Captura del array de los datos de la tabla
        $array_diagnosticos_motivo_calificacion = $request->datos_finales_diagnosticos_moticalifi;

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];
        foreach ($array_diagnosticos_motivo_calificacion as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);

            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
        $array_tabla_diagnosticos_motivo_calificacion = ['ID_evento','Id_Asignacion','Id_proceso',
        'CIE10','Nombre_CIE10','Origen_CIE10','Deficiencia_motivo_califi_condiciones',
        'Nombre_usuario','F_registro'];

        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_tabla_diagnosticos_motivo_calificacion, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar_diagnostico) {
            sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico);
        } 

        $mensajes = array(
            "parametro" => 'inserto_diagnostico',
            "mensaje" => 'Diagnóstico motivo de calificación guardado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    public function eliminarDiagnosticoMotivoCalificacion(Request $request){
        $id_fila_diagnostico = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->where('Id_Diagnosticos_motcali', $id_fila_diagnostico)
        ->update($fila_actualizar);

        $total_registros_diagnostico = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_diagnostico_eliminada',
            'total_registros' => $total_registros_diagnostico,
            "mensaje" => 'Diagnóstico motivo de calificación eliminado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }    

    // Agudeza Auditiva

    public function guardarDeficienciasAgudezaAuditivas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $ID_evento = $request->ID_evento;
        $Id_Asignacion = $request->Id_Asignacion;
        $Id_proceso = $request->Id_proceso;
        $oido_izquierdo = $request->oido_izquierdo;
        $oido_derecho = $request->oido_derecho;
        $Agudeza_Auditivas = $request->Agudeza_Auditivas;
        
        foreach ($Agudeza_Auditivas as $auditiva) {
            $auditiva;            
            foreach ($auditiva as $columna => $deficiencia) {
                $$columna = $deficiencia;
            }
        }
                    
        $datos_agudeza_auditiva = [
            'ID_evento' => $ID_evento,
            'Id_Asignacion' => $Id_Asignacion,
            'Id_proceso' => $Id_proceso,
            'Oido_Izquierdo' => $oido_izquierdo,
            'Oido_Derecho' => $oido_derecho,
            'Deficiencia_monoaural_izquierda' => $columna0,
            'Deficiencia_monoaural_derecha' => $columna1,
            'Deficiencia_binaural' => $columna2,
            'Adicion_tinnitus' => $columna3,
            'Deficiencia' => $columna4,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];
        
        sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')->insert($datos_agudeza_auditiva);
        
        $mensajes = array(
            "parametro" => 'insertar_agudeza_auditiva',
            "mensaje" => 'Agudeza auditiva guardada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function eliminarAgudezaAuditiva(Request $request){

        $id_fila_agudeza_auditiva = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')->where('Id_Agudeza_auditiva', $id_fila_agudeza_auditiva)
        ->update($fila_actualizar);

        $total_registros_agudeza_auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_agudeza_auditiva_eliminada',
            'total_registros' => $total_registros_agudeza_auditiva,
            "mensaje" => 'Agudeza auditiva eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    public function actualizarDxPrincipalAgudezaAuditiva(Request $request){
        
        $dxPrincipal = $request->dxPrincipal;
        $Id_evento = $request->Id_evento;
        $banderaDxPrincipal = $request->banderaDxPrincipal;

        if ($banderaDxPrincipal == 'SiDxPrincipal') {
            $fila_actulizar = [
                'Dx_Principal' => 'Si'
            ];
    
            sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento],
                ['Estado', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_auditiva_agregado',
                "mensaje" => 'Dx Principal Agudeza auditiva agreagado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        } elseif($banderaDxPrincipal == 'NoDxPrincipal'){
            $fila_actulizar = [
                'Dx_Principal' => 'No'
            ];
    
            sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento],
                ['Estado', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_auditiva_agregado',
                "mensaje" => 'Dx Principal Agudeza auditiva eliminado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    public function actualizarDeficienciasAgudezaAuditivas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $ID_evento_editar = $request->ID_evento_editar;
        $Id_Asignacion_editar = $request->Id_Asignacion_editar;
        $Id_proceso_editar = $request->Id_proceso_editar;
        $oido_izquierdo_editar = $request->oido_izquierdo_editar;
        $oido_derecho_editar = $request->oido_derecho_editar;
        $Agudeza_Auditivas_editar = $request->Agudeza_Auditivas_editar;
        
        foreach ($Agudeza_Auditivas_editar as $auditiva_editar) {
            $auditiva_editar;            
            foreach ($auditiva_editar as $columna => $deficiencia_editar) {
                $$columna = $deficiencia_editar;
            }
        }
                    
        $datos_agudeza_auditiva_editar = [
            'ID_evento' => $ID_evento_editar,
            'Id_Asignacion' => $Id_Asignacion_editar,
            'Id_proceso' => $Id_proceso_editar,
            'Oido_Izquierdo' => $oido_izquierdo_editar,
            'Oido_Derecho' => $oido_derecho_editar,
            'Deficiencia_monoaural_izquierda' => $columnaEditar0,
            'Deficiencia_monoaural_derecha' => $columnaEditar1,
            'Deficiencia_binaural' => $columnaEditar2,
            'Adicion_tinnitus' => $columnaEditar3,
            'Deficiencia' => $columnaEditar4,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];
        
        sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento' , $ID_evento_editar],
            ['Estado' , 'Activo']
        ])
        ->unpdate($datos_agudeza_auditiva_editar);
        
        $mensajes = array(
            "parametro" => 'insertar_agudeza_auditiva_editar',
            "mensaje" => 'Agudeza auditiva actualizada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    // Agudeza visual

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
    
    public function eliminarAgudezaVisual(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        $id_agudeza = $request->Id_agudeza;
        $id_evento = $request->ID_evento;

        /* Borrado de la información general */
        sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_agudeza', '=', $id_agudeza],
            ['ID_evento', '=', $id_evento]
        ])->delete();

        /* Borrado de la información de la campimetría para ojo izquierdo  */
        sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $id_agudeza)->delete();

        /* Borrado de la información de la campimetría para ojo derecho */
        sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $id_agudeza)->delete();

        $mensajes = array(
            "parametro" => 'borro',
            "mensaje" => 'Información de Agudeza visual eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function actualizarDxPrincipalAgudezaVisual(Request $request){
        
        $dx_principal_visual = $request->dx_principal_visual;
        $Id_evento = $request->Id_evento;
        $banderaDxPrincipal_visual = $request->banderaDxPrincipal_visual;

        if ($banderaDxPrincipal_visual == 'SiDxPrincipal') {
            $fila_actulizar = [
                'Dx_Principal' => 'Si'
            ];
    
            sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento]
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_visual_agregado',
                "mensaje" => 'Dx Principal Agudeza visual agreagado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        } elseif($banderaDxPrincipal_visual == 'NoDxPrincipal'){
            $fila_actulizar = [
                'Dx_Principal' => 'No'
            ];
    
            sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento]
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_visual_agregado',
                "mensaje" => 'Dx Principal Agudeza visual eliminado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    /* TODO LO REFERENTE DEFICIENCIA POR ALTERACIONES DE LOS SISTEMAS GENERALES */
    public function ListadoSelectoresDefiAlteraciones(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $parametro = $request->parametro;

        if($parametro == 'listado_tablas_decreto'){

            $listado_tablas_decreto_1507 = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'Nombre_tabla')->where([['Estado', '=', 'Activo']])->get();
            

            $info_listado_tablas_decreto_1507 = json_decode(json_encode($listado_tablas_decreto_1507, true));
            return response()->json($info_listado_tablas_decreto_1507);
        };

        if ($parametro == "nombre_tabla") {
            $nombre_tabla = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Nombre_tabla', 'Ident_tabla')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_nombre_tabla = json_decode(json_encode($nombre_tabla, true));
            return response()->json($info_nombre_tabla);
        };

        if ($parametro == "selector_FP") {
            $selector_FP = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'FP')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_FP = json_decode(json_encode($selector_FP, true));
            return response()->json($info_selector_FP);
        }

        if ($parametro == "selector_CFM1") {
            $selector_CFM1 = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'CFM1')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_CFM1 = json_decode(json_encode($selector_CFM1, true));
            return response()->json($info_selector_CFM1);
        }

        if ($parametro == "selector_CFM2") {
            $selector_CFM2 = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'CFM2')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_CFM2 = json_decode(json_encode($selector_CFM2, true));
            return response()->json($info_selector_CFM2);
        }

        if ($parametro == "selector_FU") {
            $selector_FU = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'FU')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_FU = json_decode(json_encode($selector_FU, true));
            return response()->json($info_selector_FU);
        }

        if ($parametro == "selector_CAT") {
            $selector_CAT = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'CAT')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_CAT = json_decode(json_encode($selector_CAT, true));
            return response()->json($info_selector_CAT);
        }

        if ($parametro == "MSD") {
            $msd = sigmel_lista_clases_decretos::on('sigmel_gestiones')
            ->select('MSD')->where('Id_tabla', $request->Id_tabla)->get();
        }

        $info_msd = json_decode(json_encode($msd, true));
        return response()->json($info_msd);

    }

    public function consultaValorDeficiencia(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $string_deficiencia = sigmel_lista_clases_decretos::on('sigmel_gestiones')
        ->select($request->columna)->where('Id_tabla', $request->Id_tabla)->get();

        $info_string_deficiencia = json_decode(json_encode($string_deficiencia, true));
        return response()->json($info_string_deficiencia);
        
    }

    public function GuardarDeficienciaAlteraciones(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
    
        /* CAPTURA DE DATOS DE LA DEFICIENCIA */
        $array_datos = $request->datos_finales_deficiencias_alteraciones;

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

        // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU',	'CAT', 'Clase_Final', 
        'Dx_Principal', 'MSD', 'Deficiencia', 'Nombre_usuario','F_registro'];
        
        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar) {
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->insert($insertar);
        }

        $mensajes = array(
            "parametro" => 'inserto_informacion_deficiencias',
            "mensaje" => 'Deficiencia guardada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function eliminarDeficienciaAteraciones(Request $request){
        $id_fila_deficiencia_alteraciones = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_alteraciones)
        ->update($fila_actualizar);

        $total_registros_diagnostico = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_deficiencia_alteracion_eliminada',
            'total_registros' => $total_registros_diagnostico,
            "mensaje" => 'Deficiencia por alteraciones eliminado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));        
    }

    public function actualizarDxPrincipalDeficienciasAlteraciones(Request $request){
        
        $fila = $request->fila;
        $banderaDxPrincipalDA = $request->banderaDxPrincipalDA;
        $Id_evento = $request->Id_evento;            

        if ($banderaDxPrincipalDA == 'SiDxPrincipal_deficiencia_alteraciones') {
            $fila_actulizar = [
                'Dx_Principal' => 'Si'
            ];
    
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
            ->where([
                ['Id_Deficiencia', $fila],
                ['ID_evento', $Id_evento],
                ['Estado', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalDeficienciaAlteracion_agregado',
                "mensaje" => 'Dx Principal Deficiencias alteraciones agreagada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));  

        }elseif($banderaDxPrincipalDA == 'NoDxPrincipal_deficiencia_alteraciones'){           

            $fila_actulizar = [
                'Dx_Principal' => 'No'
            ];
    
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
            ->where([
                ['Id_Deficiencia', $fila],
                ['ID_evento', $Id_evento],
                ['Estado', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalDeficienciaAlteracion_eliminado',
                "mensaje" => 'Dx Principal Deficiencias alteraciones eliminada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Laboralmente Activo Rol Ocupacional

    public function guardarLaboralmenteActivo(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $Id_Evento_decreto = $request -> Id_Evento_decreto;
        $Id_Proceso_decreto = $request -> Id_Proceso_decreto;
        $Id_Asignacion_decreto = $request -> Id_Asignacion_decreto;
        $restricion_rol = $request -> restricion_rol;
        $auto_suficiencia = $request -> auto_suficiencia;
        $edad_cronologica_adulto = $request -> edad_cronologica_adulto;
        $edad_cronologica_menor = $request -> edad_cronologica_menor;
        $resultado_rol_laboral_30 = $request -> resultado_rol_laboral_30;
        $mirar = $request -> mirar;
        $escuchar = $request -> escuchar;
        $aprender = $request -> aprender;
        $calcular = $request -> calcular;
        $pensar = $request -> pensar;
        $leer = $request -> leer;
        $escribir = $request -> escribir;
        $matematicos = $request -> matematicos;
        $decisiones = $request -> decisiones;
        $tareas_simples = $request -> tareas_simples;
        $resultado_tabla6 = $request -> resultado_tabla6;
        $comunicarse_mensaje = $request -> comunicarse_mensaje;
        $no_comunicarse_mensaje = $request -> no_comunicarse_mensaje;
        $comunicarse_signos = $request -> comunicarse_signos;
        $comunicarse_escrito = $request -> comunicarse_escrito;
        $habla = $request -> habla;
        $no_verbales = $request -> no_verbales;
        $mensajes_escritos = $request -> mensajes_escritos;
        $sostener_conversa = $request -> sostener_conversa;
        $iniciar_discusiones = $request -> iniciar_discusiones;
        $utiliza_dispositivos = $request -> utiliza_dispositivos;
        $resultado_tabla7 = $request -> resultado_tabla7;
        $cambiar_posturas = $request -> cambiar_posturas;
        $posicion_cuerpo = $request -> posicion_cuerpo;
        $llevar_objetos = $request -> llevar_objetos;
        $uso_fino_mano = $request -> uso_fino_mano;
        $uso_mano_brazo = $request -> uso_mano_brazo;
        $desplazarse_entorno = $request -> desplazarse_entorno;
        $distintos_lugares = $request -> distintos_lugares;
        $desplazarse_con_equipo = $request -> desplazarse_con_equipo;
        $transporte_pasajero = $request -> transporte_pasajero;
        $conduccion = $request -> conduccion;
        $resultado_tabla8 = $request -> resultado_tabla8;
        $lavarse = $request -> lavarse;
        $cuidado_cuerpo = $request -> cuidado_cuerpo;
        $higiene_personal = $request -> higiene_personal;
        $vestirse = $request -> vestirse;
        $quitarse_ropa = $request -> quitarse_ropa;
        $ponerse_calzado = $request -> ponerse_calzado;
        $comer = $request -> comer;
        $beber = $request -> beber;
        $cuidado_salud = $request -> cuidado_salud;
        $control_dieta = $request -> control_dieta;
        $resultado_tabla9 = $request -> resultado_tabla9;
        $adquisicion_para_vivir = $request -> adquisicion_para_vivir;
        $bienes_servicios = $request -> bienes_servicios;
        $comprar = $request -> comprar;
        $preparar_comida = $request -> preparar_comida;
        $quehaceres_casa = $request -> quehaceres_casa;
        $limpieza_vivienda = $request -> limpieza_vivienda;
        $objetos_hogar = $request -> objetos_hogar;
        $ayudar_los_demas = $request -> ayudar_los_demas;
        $mantenimiento_dispositivos = $request -> mantenimiento_dispositivos;
        $cuidado_animales = $request -> cuidado_animales;
        $resultado_tabla10 = $request -> resultado_tabla10;
        $total_otras = $request -> total_otras;
        $total_rol_areas = $request -> total_rol_areas;       

        if ($request -> bandera_LaboralActivo_guardar_actualizar == 'Guardar') {
            $datos_laboralmenteActivo = [
                'ID_evento' => $Id_Evento_decreto,
                'Id_Asignacion' => $Id_Proceso_decreto,
                'Id_proceso' => $Id_Asignacion_decreto,
                'Restricciones_rol' => $restricion_rol,
                'Autosuficiencia_economica' => $auto_suficiencia,
                'Edad_cronologica_menor' => $edad_cronologica_menor,
                'Edad_cronologica' => $edad_cronologica_adulto,
                'Total_rol_laboral' => $resultado_rol_laboral_30,
                'Aprendizaje_mirar' => $mirar,
                'Aprendizaje_escuchar' => $escuchar,
                'Aprendizaje_aprender' => $aprender,
                'Aprendizaje_calcular' => $calcular,
                'Aprendizaje_pensar' => $pensar,
                'Aprendizaje_leer' => $leer,
                'Aprendizaje_escribir' => $escribir,
                'Aprendizaje_matematicos' => $matematicos,
                'Aprendizaje_resolver' => $decisiones,
                'Aprendizaje_tareas' => $tareas_simples,
                'Aprendizaje_total' => $resultado_tabla6,
                'Comunicacion_verbales' => $comunicarse_mensaje,
                'Comunicacion_noverbales' => $no_comunicarse_mensaje,
                'Comunicacion_formal' => $comunicarse_signos,
                'Comunicacion_escritos' => $comunicarse_escrito,
                'Comunicacion_habla' => $habla,
                'Comunicacion_produccion' => $no_verbales,
                'Comunicacion_mensajes' => $mensajes_escritos,
                'Comunicacion_conversacion' => $sostener_conversa,
                'Comunicacion_discusiones' => $iniciar_discusiones,
                'Comunicacion_dispositivos' => $utiliza_dispositivos,
                'Comunicacion_total' => $resultado_tabla7,
                'Movilidad_cambiar_posturas' => $cambiar_posturas,
                'Movilidad_mantener_posicion' => $posicion_cuerpo,
                'Movilidad_objetos' => $llevar_objetos,
                'Movilidad_uso_mano' => $uso_fino_mano,
                'Movilidad_mano_brazo' => $uso_mano_brazo,
                'Movilidad_Andar' => $desplazarse_entorno,
                'Movilidad_desplazarse' => $distintos_lugares,
                'Movilidad_equipo' => $desplazarse_con_equipo,
                'Movilidad_transporte' => $transporte_pasajero,
                'Movilidad_conduccion' => $conduccion,
                'Movilidad_total' => $resultado_tabla8,
                'Cuidado_lavarse' => $lavarse,
                'Cuidado_partes_cuerpo' => $cuidado_cuerpo,
                'Cuidado_higiene' => $higiene_personal,
                'Cuidado_vestirse' => $vestirse,
                'Cuidado_quitarse' => $quitarse_ropa,
                'Cuidado_ponerse_calzado' => $ponerse_calzado,
                'Cuidado_comer' => $comer,
                'Cuidado_beber' => $beber,
                'Cuidado_salud' => $cuidado_salud,
                'Cuidado_dieta' => $control_dieta,
                'Cuidado_total' => $resultado_tabla9,
                'Domestica_vivir' => $adquisicion_para_vivir,
                'Domestica_bienes' => $bienes_servicios,
                'Domestica_comprar' => $comprar,
                'Domestica_comidas' => $preparar_comida,
                'Domestica_quehaceres' => $quehaceres_casa,
                'Domestica_limpieza' => $limpieza_vivienda,
                'Domestica_objetos' => $objetos_hogar,
                'Domestica_ayudar' => $ayudar_los_demas,
                'Domestica_mantenimiento' => $mantenimiento_dispositivos,
                'Domestica_animales' => $cuidado_animales,
                'Domestica_total' => $resultado_tabla10,
                'Total_otras_areas' => $total_otras,
                'Total_laboral_otras_areas' => $total_rol_areas,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];            
            sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')->insert($datos_laboralmenteActivo);
            
            $mensajes = array(
                "parametro" => 'insertar_laboralmente_activo',
                "mensaje" => 'Laboralmente activo guardado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));

        }elseif($request -> bandera_LaboralActivo_guardar_actualizar == 'Actualizar'){

            $datos_laboralmenteActivo = [
                'ID_evento' => $Id_Evento_decreto,
                'Id_Asignacion' => $Id_Proceso_decreto,
                'Id_proceso' => $Id_Asignacion_decreto,
                'Restricciones_rol' => $restricion_rol,
                'Autosuficiencia_economica' => $auto_suficiencia,
                'Edad_cronologica_menor' => $edad_cronologica_menor,
                'Edad_cronologica' => $edad_cronologica_adulto,
                'Total_rol_laboral' => $resultado_rol_laboral_30,
                'Aprendizaje_mirar' => $mirar,
                'Aprendizaje_escuchar' => $escuchar,
                'Aprendizaje_aprender' => $aprender,
                'Aprendizaje_calcular' => $calcular,
                'Aprendizaje_pensar' => $pensar,
                'Aprendizaje_leer' => $leer,
                'Aprendizaje_escribir' => $escribir,
                'Aprendizaje_matematicos' => $matematicos,
                'Aprendizaje_resolver' => $decisiones,
                'Aprendizaje_tareas' => $tareas_simples,
                'Aprendizaje_total' => $resultado_tabla6,
                'Comunicacion_verbales' => $comunicarse_mensaje,
                'Comunicacion_noverbales' => $no_comunicarse_mensaje,
                'Comunicacion_formal' => $comunicarse_signos,
                'Comunicacion_escritos' => $comunicarse_escrito,
                'Comunicacion_habla' => $habla,
                'Comunicacion_produccion' => $no_verbales,
                'Comunicacion_mensajes' => $mensajes_escritos,
                'Comunicacion_conversacion' => $sostener_conversa,
                'Comunicacion_discusiones' => $iniciar_discusiones,
                'Comunicacion_dispositivos' => $utiliza_dispositivos,
                'Comunicacion_total' => $resultado_tabla7,
                'Movilidad_cambiar_posturas' => $cambiar_posturas,
                'Movilidad_mantener_posicion' => $posicion_cuerpo,
                'Movilidad_objetos' => $llevar_objetos,
                'Movilidad_uso_mano' => $uso_fino_mano,
                'Movilidad_mano_brazo' => $uso_mano_brazo,
                'Movilidad_Andar' => $desplazarse_entorno,
                'Movilidad_desplazarse' => $distintos_lugares,
                'Movilidad_equipo' => $desplazarse_con_equipo,
                'Movilidad_transporte' => $transporte_pasajero,
                'Movilidad_conduccion' => $conduccion,
                'Movilidad_total' => $resultado_tabla8,
                'Cuidado_lavarse' => $lavarse,
                'Cuidado_partes_cuerpo' => $cuidado_cuerpo,
                'Cuidado_higiene' => $higiene_personal,
                'Cuidado_vestirse' => $vestirse,
                'Cuidado_quitarse' => $quitarse_ropa,
                'Cuidado_ponerse_calzado' => $ponerse_calzado,
                'Cuidado_comer' => $comer,
                'Cuidado_beber' => $beber,
                'Cuidado_salud' => $cuidado_salud,
                'Cuidado_dieta' => $control_dieta,
                'Cuidado_total' => $resultado_tabla9,
                'Domestica_vivir' => $adquisicion_para_vivir,
                'Domestica_bienes' => $bienes_servicios,
                'Domestica_comprar' => $comprar,
                'Domestica_comidas' => $preparar_comida,
                'Domestica_quehaceres' => $quehaceres_casa,
                'Domestica_limpieza' => $limpieza_vivienda,
                'Domestica_objetos' => $objetos_hogar,
                'Domestica_ayudar' => $ayudar_los_demas,
                'Domestica_mantenimiento' => $mantenimiento_dispositivos,
                'Domestica_animales' => $cuidado_animales,
                'Domestica_total' => $resultado_tabla10,
                'Total_otras_areas' => $total_otras,
                'Total_laboral_otras_areas' => $total_rol_areas,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ]; 

            sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $Id_Evento_decreto)->update($datos_laboralmenteActivo);
            sleep(2);

            $mensajes = array(
                "parametro" => 'update_laboralmente_activo',
                "mensaje2" => 'Laboralmente activo actualizado satisfactoriamente.'
            ); 

            return json_decode(json_encode($mensajes, true));

        }
        
    }

    public function guardarRolOcupacional(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $Id_EventoDecreto = $request -> Id_EventoDecreto;
        $Id_ProcesoDecreto = $request -> Id_ProcesoDecreto;
        $Id_Asignacion_Dcreto = $request -> Id_Asignacion_Dcreto;
        $poblacion_califi = $request -> poblacion_califi;
        $mantiene_postura = $request -> mantiene_postura;
        $actividad_espontanea = $request -> actividad_espontanea;
        $sujeta_cabeza = $request -> sujeta_cabeza;
        $sienta_apoyo = $request -> sienta_apoyo;
        $sobre_mismo = $request -> sobre_mismo;
        $sentado_sin_apoyo = $request -> sentado_sin_apoyo;
        $tumbado_sentado = $request -> tumbado_sentado;
        $pie_apoyo = $request -> pie_apoyo;
        $pasos_apoyo = $request -> pasos_apoyo;
        $mantiene_sin_apoyo = $request -> mantiene_sin_apoyo;
        $anda_solo = $request -> anda_solo;
        $empuja_pelota = $request -> empuja_pelota;
        $sorteando_obstaculos = $request -> sorteando_obstaculos;
        $succiona = $request -> succiona;
        $fija_mirada = $request -> fija_mirada;
        $trayectoria_objeto = $request -> trayectoria_objeto;
        $sostiene_sonajero = $request -> sostiene_sonajero;
        $hacia_objeto = $request -> hacia_objeto;
        $sostiene_objeto = $request -> sostiene_objeto;
        $abre_cajones = $request -> abre_cajones;
        $bebe_solo = $request -> bebe_solo;
        $quita_prenda = $request -> quita_prenda;
        $espacios_casa = $request -> espacios_casa;
        $imita_trazaso = $request -> imita_trazaso;
        $abre_puerta = $request -> abre_puerta;
        $total_tabla12 = $request -> total_tabla12;
        $roles_ocupacionales_juego = $request -> roles_ocupacionales_juego;
        $total_tabla13 = $request -> total_tabla13;
        $roles_ocupacionales_adultos = $request -> roles_ocupacionales_adultos;
        $total_tabla14 = $request -> total_tabla14;
        $bandera_RolOcupacional_guardar_actualizar = $request -> bandera_RolOcupacional_guardar_actualizar;

        if ($bandera_RolOcupacional_guardar_actualizar == 'Guardar') {
            
            $datos_rolOcupacional =[
                'ID_evento' => $Id_EventoDecreto,
                'Id_Asignacion' => $Id_ProcesoDecreto,
                'Id_proceso' => $Id_Asignacion_Dcreto,
                'Poblacion_calificar' => $poblacion_califi,
                'Motriz_postura_simetrica' => $mantiene_postura,
                'Motriz_actividad_espontanea' => $actividad_espontanea,
                'Motriz_sujeta_cabeza' => $sujeta_cabeza,
                'Motriz_sentarse_apoyo' => $sienta_apoyo,
                'Motriz_gira_sobre_mismo' => $sobre_mismo,
                'Motriz_sentanser_sin_apoyo' => $sentado_sin_apoyo,
                'Motriz_pasa_tumbado_sentado' => $tumbado_sentado,
                'Motriz_pararse_apoyo' => $pie_apoyo,
                'Motriz_pasos_apoyo' => $pasos_apoyo,
                'Motriz_pararse_sin_apoyo' => $mantiene_sin_apoyo,
                'Motriz_anda_solo' => $anda_solo,
                'Motriz_empujar_pelota_pies' => $empuja_pelota,
                'Motriz_andar_obstaculos' => $sorteando_obstaculos,
                'Adaptativa_succiona' => $succiona,
                'Adaptativa_fija_mirada' => $fija_mirada,
                'Adaptativa_sigue_trayectoria_objeto' => $trayectoria_objeto,
                'Adaptativa_sostiene_sonajero' => $sostiene_sonajero,
                'Adaptativa_tiende_mano_hacia_objeto' => $hacia_objeto,
                'Adaptativa_sostiene_objeto_manos' => $sostiene_objeto,
                'Adaptativa_abre_cajones' => $abre_cajones,
                'Adaptativa_bebe_solo' => $bebe_solo,
                'Adaptativa_quitar_prenda_vestir' => $quita_prenda,
                'Adaptativa_reconoce_funcion_espacios_casa' => $espacios_casa,
                'Adaptativa_imita_trazo_lapiz' => $imita_trazaso,
                'Adaptativa_abre_puerta' => $abre_puerta,
                'Total_criterios_desarrollo' => $total_tabla12,
                'Juego_estudio_clase' => $roles_ocupacionales_juego,
                'Total_rol_estudio_clase' => $total_tabla13,
                'Adultos_mayores' => $roles_ocupacionales_adultos,
                'Total_rol_adultos_ayores' => $total_tabla14,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')->insert($datos_rolOcupacional);            
            $mensajes = array(
                "parametro" => 'insertar_rol_ocupacional',
                "mensaje" => 'Rol ocupacional guardado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));

        }elseif($bandera_RolOcupacional_guardar_actualizar == 'Actualizar') {           

            $datos_rolOcupacional =[
                'ID_evento' => $Id_EventoDecreto,
                'Id_Asignacion' => $Id_ProcesoDecreto,
                'Id_proceso' => $Id_Asignacion_Dcreto,
                'Poblacion_calificar' => $poblacion_califi,
                'Motriz_postura_simetrica' => $mantiene_postura,
                'Motriz_actividad_espontanea' => $actividad_espontanea,
                'Motriz_sujeta_cabeza' => $sujeta_cabeza,
                'Motriz_sentarse_apoyo' => $sienta_apoyo,
                'Motriz_gira_sobre_mismo' => $sobre_mismo,
                'Motriz_sentanser_sin_apoyo' => $sentado_sin_apoyo,
                'Motriz_pasa_tumbado_sentado' => $tumbado_sentado,
                'Motriz_pararse_apoyo' => $pie_apoyo,
                'Motriz_pasos_apoyo' => $pasos_apoyo,
                'Motriz_pararse_sin_apoyo' => $mantiene_sin_apoyo,
                'Motriz_anda_solo' => $anda_solo,
                'Motriz_empujar_pelota_pies' => $empuja_pelota,
                'Motriz_andar_obstaculos' => $sorteando_obstaculos,
                'Adaptativa_succiona' => $succiona,
                'Adaptativa_fija_mirada' => $fija_mirada,
                'Adaptativa_sigue_trayectoria_objeto' => $trayectoria_objeto,
                'Adaptativa_sostiene_sonajero' => $sostiene_sonajero,
                'Adaptativa_tiende_mano_hacia_objeto' => $hacia_objeto,
                'Adaptativa_sostiene_objeto_manos' => $sostiene_objeto,
                'Adaptativa_abre_cajones' => $abre_cajones,
                'Adaptativa_bebe_solo' => $bebe_solo,
                'Adaptativa_quitar_prenda_vestir' => $quita_prenda,
                'Adaptativa_reconoce_funcion_espacios_casa' => $espacios_casa,
                'Adaptativa_imita_trazo_lapiz' => $imita_trazaso,
                'Adaptativa_abre_puerta' => $abre_puerta,
                'Total_criterios_desarrollo' => $total_tabla12,
                'Juego_estudio_clase' => $roles_ocupacionales_juego,
                'Total_rol_estudio_clase' => $total_tabla13,
                'Adultos_mayores' => $roles_ocupacionales_adultos,
                'Total_rol_adultos_ayores' => $total_tabla14,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $Id_EventoDecreto)->update($datos_rolOcupacional);
            sleep(2);
            
            $mensajes = array(
                "parametro" => 'actualizar_rol_ocupacional',
                "mensaje2" => 'Rol ocupacional actualizado satisfactoriamente.'
            ); 
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Libro 2 20% y libro 3 30%

    public function guardarLibro2_3(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $Id_EventoDecreto = $request->Id_EventoDecreto;
        $Id_ProcesoDecreto = $request->Id_ProcesoDecreto;
        $Id_Asignacion_Dcreto = $request->Id_Asignacion_Dcreto;
        $conducta_10 = $request->conducta_10;
        $conducta_11 = $request->conducta_11;
        $conducta_12 = $request->conducta_12;
        $conducta_13 = $request->conducta_13;
        $conducta_14 = $request->conducta_14;
        $conducta_15 = $request->conducta_15;
        $conducta_16 = $request->conducta_16;
        $conducta_17 = $request->conducta_17;
        $conducta_18 = $request->conducta_18;
        $conducta_19 = $request->conducta_19;
        $total_conducta = $request->total_conducta;
        $comunicacion_20 = $request->comunicacion_20;
        $comunicacion_21 = $request->comunicacion_21;
        $comunicacion_22 = $request->comunicacion_22;
        $comunicacion_23 = $request->comunicacion_23;
        $comunicacion_24 = $request->comunicacion_24;
        $comunicacion_25 = $request->comunicacion_25;
        $comunicacion_26 = $request->comunicacion_26;
        $comunicacion_27 = $request->comunicacion_27;
        $comunicacion_28 = $request->comunicacion_28;
        $comunicacion_29 = $request->comunicacion_29;
        $total_comunicacion = $request->total_comunicacion;
        $cuidado_personal_30 = $request->cuidado_personal_30;
        $cuidado_personal_31 = $request->cuidado_personal_31;
        $cuidado_personal_32 = $request->cuidado_personal_32;
        $cuidado_personal_33 = $request->cuidado_personal_33;
        $cuidado_personal_34 = $request->cuidado_personal_34;
        $cuidado_personal_35 = $request->cuidado_personal_35;
        $cuidado_personal_36 = $request->cuidado_personal_36;
        $cuidado_personal_37 = $request->cuidado_personal_37;
        $cuidado_personal_38 = $request->cuidado_personal_38;
        $cuidado_personal_39 = $request->cuidado_personal_39;
        $total_cuidado_personal = $request->total_cuidado_personal;
        $lomocion_40 = $request->lomocion_40;
        $lomocion_41 = $request->lomocion_41;
        $lomocion_42 = $request->lomocion_42;
        $lomocion_43 = $request->lomocion_43;
        $lomocion_44 = $request->lomocion_44;
        $lomocion_45 = $request->lomocion_45;
        $lomocion_46 = $request->lomocion_46;
        $lomocion_47 = $request->lomocion_47;
        $lomocion_48 = $request->lomocion_48;
        $lomocion_49 = $request->lomocion_49;
        $total_lomocion = $request->total_lomocion;
        $disposicion_50 = $request->disposicion_50;
        $disposicion_51 = $request->disposicion_51;
        $disposicion_52 = $request->disposicion_52;
        $disposicion_53 = $request->disposicion_53;
        $disposicion_54 = $request->disposicion_54;
        $disposicion_55 = $request->disposicion_55;
        $disposicion_56 = $request->disposicion_56;
        $disposicion_57 = $request->disposicion_57;
        $disposicion_58 = $request->disposicion_58;
        $disposicion_59 = $request->disposicion_59;
        $total_disposicion = $request->total_disposicion;
        $destreza_60 = $request->destreza_60;
        $destreza_61 = $request->destreza_61;
        $destreza_62 = $request->destreza_62;
        $destreza_63 = $request->destreza_63;
        $destreza_64 = $request->destreza_64;
        $destreza_65 = $request->destreza_65;
        $destreza_66 = $request->destreza_66;
        $destreza_67 = $request->destreza_67;
        $destreza_68 = $request->destreza_68;
        $destreza_69 = $request->destreza_69;
        $total_destreza = $request->total_destreza;
        $situacion_70 = $request->situacion_70;
        $situacion_71 = $request->situacion_71;
        $situacion_72 = $request->situacion_72;
        $situacion_73 = $request->situacion_73;
        $situacion_74 = $request->situacion_74;
        $situacion_75 = $request->situacion_75;
        $situacion_76 = $request->situacion_76;
        $situacion_77 = $request->situacion_77;
        $situacion_78 = $request->situacion_78;
        $total_situacion = $request->total_situacion;
        $total_discapacidades = $request->total_discapacidades;
        $orientacion = $request->orientacion;
        $indepen_fisica = $request->indepen_fisica;
        $desplazamiento = $request->desplazamiento;
        $ocupacional = $request->ocupacional;
        $social = $request->social;
        $economica = $request->economica;
        $cronologica_adulto = $request->cronologica_adulto;
        $cronologica_menor = $request->cronologica_menor;
        $total_minusvalia = $request->total_minusvalia;
        $bandera_Libros2_3_guardar_actualizar = $request->bandera_Libros2_3_guardar_actualizar;

        if($bandera_Libros2_3_guardar_actualizar == 'Guardar'){
            $datos_Libros2_3 =[
                'ID_evento' => $Id_EventoDecreto,
                'Id_Asignacion' => $Id_ProcesoDecreto,
                'Id_proceso' => $Id_Asignacion_Dcreto,
                'Conducta10' => $conducta_10,
                'Conducta11' => $conducta_11,
                'Conducta12' => $conducta_12,
                'Conducta13' => $conducta_13,
                'Conducta14' => $conducta_14,
                'Conducta15' => $conducta_15,
                'Conducta16' => $conducta_16,
                'Conducta17' => $conducta_17,
                'Conducta18' => $conducta_18,
                'Conducta19' => $conducta_19,
                'Total_conducta' => $total_conducta,
                'Comunicacion20' => $comunicacion_20,
                'Comunicacion21' => $comunicacion_21,
                'Comunicacion22' => $comunicacion_22,
                'Comunicacion23' => $comunicacion_23,
                'Comunicacion24' => $comunicacion_24,
                'Comunicacion25' => $comunicacion_25,
                'Comunicacion26' => $comunicacion_26,
                'Comunicacion27' => $comunicacion_27,
                'Comunicacion28' => $comunicacion_28,
                'Comunicacion29' => $comunicacion_29,
                'Total_comunicacion' => $total_comunicacion,
                'Personal30' => $cuidado_personal_30,
                'Personal31' => $cuidado_personal_31,
                'Personal32' => $cuidado_personal_32,
                'Personal33' => $cuidado_personal_33,
                'Personal34' => $cuidado_personal_34,
                'Personal35' => $cuidado_personal_35,
                'Personal36' => $cuidado_personal_36,
                'Personal37' => $cuidado_personal_37,
                'Personal38' => $cuidado_personal_38,
                'Personal39' => $cuidado_personal_39,
                'Total_personal' => $total_cuidado_personal,
                'Locomocion40' => $lomocion_40,
                'Locomocion41' => $lomocion_41,
                'Locomocion42' => $lomocion_42,
                'Locomocion43' => $lomocion_43,
                'Locomocion44' => $lomocion_44,
                'Locomocion45' => $lomocion_45,
                'Locomocion46' => $lomocion_46,
                'Locomocion47' => $lomocion_47,
                'Locomocion48' => $lomocion_48,
                'Locomocion49' => $lomocion_49,
                'Total_locomocion' => $total_lomocion,
                'Disposicion50' => $disposicion_50,
                'Disposicion51' => $disposicion_51,
                'Disposicion52' => $disposicion_52,
                'Disposicion53' => $disposicion_53,
                'Disposicion54' => $disposicion_54,
                'Disposicion55' => $disposicion_55,
                'Disposicion56' => $disposicion_56,
                'Disposicion57' => $disposicion_57,
                'Disposicion58' => $disposicion_58,
                'Disposicion59' => $disposicion_59,
                'Total_disposicion' => $total_disposicion,
                'Destreza60' => $destreza_60,
                'Destreza61' => $destreza_61,
                'Destreza62' => $destreza_62,
                'Destreza63' => $destreza_63,
                'Destreza64' => $destreza_64,
                'Destreza65' => $destreza_65,
                'Destreza66' => $destreza_66,
                'Destreza67' => $destreza_67,
                'Destreza68' => $destreza_68,
                'Destreza69' => $destreza_69,
                'Total_destreza' => $total_destreza,
                'Situacion70' => $situacion_70,
                'Situacion71' => $situacion_71,
                'Situacion72' => $situacion_72,
                'Situacion73' => $situacion_73,
                'Situacion74' => $situacion_74,
                'Situacion75' => $situacion_75,
                'Situacion76' => $situacion_76,
                'Situacion77' => $situacion_77,
                'Situacion78' => $situacion_78,
                'Total_situacion' => $total_situacion,
                'Total_discapacidad' => $total_discapacidades,
                'Orientacion' => $orientacion,
                'Idenpendencia_fisica' => $indepen_fisica,
                'Desplazamiento' => $desplazamiento,
                'Ocupacional' => $ocupacional,
                'Integracion' => $social,
                'Autosuficiencia' => $economica,
                'Edad_cronologica_menor' => $cronologica_menor,
                'Edad_cronologica_adulto' => $cronologica_adulto,
                'Total_minusvalia' => $total_minusvalia,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')->insert($datos_Libros2_3);            
            $mensajes = array(
                "parametro" => 'insertar_libros_2_3',
                "mensaje" => 'Libros II y III guardados satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
            
        }elseif($bandera_Libros2_3_guardar_actualizar == 'Actualizar'){
            $datos_Libros2_3 =[
                'ID_evento' => $Id_EventoDecreto,
                'Id_Asignacion' => $Id_ProcesoDecreto,
                'Id_proceso' => $Id_Asignacion_Dcreto,
                'Conducta10' => $conducta_10,
                'Conducta11' => $conducta_11,
                'Conducta12' => $conducta_12,
                'Conducta13' => $conducta_13,
                'Conducta14' => $conducta_14,
                'Conducta15' => $conducta_15,
                'Conducta16' => $conducta_16,
                'Conducta17' => $conducta_17,
                'Conducta18' => $conducta_18,
                'Conducta19' => $conducta_19,
                'Total_conducta' => $total_conducta,
                'Comunicacion20' => $comunicacion_20,
                'Comunicacion21' => $comunicacion_21,
                'Comunicacion22' => $comunicacion_22,
                'Comunicacion23' => $comunicacion_23,
                'Comunicacion24' => $comunicacion_24,
                'Comunicacion25' => $comunicacion_25,
                'Comunicacion26' => $comunicacion_26,
                'Comunicacion27' => $comunicacion_27,
                'Comunicacion28' => $comunicacion_28,
                'Comunicacion29' => $comunicacion_29,
                'Total_comunicacion' => $total_comunicacion,
                'Personal30' => $cuidado_personal_30,
                'Personal31' => $cuidado_personal_31,
                'Personal32' => $cuidado_personal_32,
                'Personal33' => $cuidado_personal_33,
                'Personal34' => $cuidado_personal_34,
                'Personal35' => $cuidado_personal_35,
                'Personal36' => $cuidado_personal_36,
                'Personal37' => $cuidado_personal_37,
                'Personal38' => $cuidado_personal_38,
                'Personal39' => $cuidado_personal_39,
                'Total_personal' => $total_cuidado_personal,
                'Locomocion40' => $lomocion_40,
                'Locomocion41' => $lomocion_41,
                'Locomocion42' => $lomocion_42,
                'Locomocion43' => $lomocion_43,
                'Locomocion44' => $lomocion_44,
                'Locomocion45' => $lomocion_45,
                'Locomocion46' => $lomocion_46,
                'Locomocion47' => $lomocion_47,
                'Locomocion48' => $lomocion_48,
                'Locomocion49' => $lomocion_49,
                'Total_locomocion' => $total_lomocion,
                'Disposicion50' => $disposicion_50,
                'Disposicion51' => $disposicion_51,
                'Disposicion52' => $disposicion_52,
                'Disposicion53' => $disposicion_53,
                'Disposicion54' => $disposicion_54,
                'Disposicion55' => $disposicion_55,
                'Disposicion56' => $disposicion_56,
                'Disposicion57' => $disposicion_57,
                'Disposicion58' => $disposicion_58,
                'Disposicion59' => $disposicion_59,
                'Total_disposicion' => $total_disposicion,
                'Destreza60' => $destreza_60,
                'Destreza61' => $destreza_61,
                'Destreza62' => $destreza_62,
                'Destreza63' => $destreza_63,
                'Destreza64' => $destreza_64,
                'Destreza65' => $destreza_65,
                'Destreza66' => $destreza_66,
                'Destreza67' => $destreza_67,
                'Destreza68' => $destreza_68,
                'Destreza69' => $destreza_69,
                'Total_destreza' => $total_destreza,
                'Situacion70' => $situacion_70,
                'Situacion71' => $situacion_71,
                'Situacion72' => $situacion_72,
                'Situacion73' => $situacion_73,
                'Situacion74' => $situacion_74,
                'Situacion75' => $situacion_75,
                'Situacion76' => $situacion_76,
                'Situacion77' => $situacion_77,
                'Situacion78' => $situacion_78,
                'Total_situacion' => $total_situacion,
                'Total_discapacidad' => $total_discapacidades,
                'Orientacion' => $orientacion,
                'Idenpendencia_fisica' => $indepen_fisica,
                'Desplazamiento' => $desplazamiento,
                'Ocupacional' => $ocupacional,
                'Integracion' => $social,
                'Autosuficiencia' => $economica,
                'Edad_cronologica_menor' => $cronologica_menor,
                'Edad_cronologica_adulto' => $cronologica_adulto,
                'Total_minusvalia' => $total_minusvalia,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $Id_EventoDecreto)->update($datos_Libros2_3);
            sleep(2);            
            $mensajes = array(
                "parametro" => 'actualizar_libros_2_3',
                "mensaje2" => 'Libros II y III actualizados satisfactoriamente.'
            ); 
            return json_decode(json_encode($mensajes, true));   
        }

    }

    public function guardardictamenPericial(Request $request){

        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);
        $Id_EventoDecreto = $request->Id_EventoDecreto;
        $Id_ProcesoDecreto = $request->Id_ProcesoDecreto;
        $Id_Asignacion_Dcreto = $request->Id_Asignacion_Dcreto;
        $suma_combinada = $request->suma_combinada;
        $Total_Deficiencia50 = $request->Total_Deficiencia50;
        $porcentaje_pcl = $request->porcentaje_pcl;        
        $rango_pcl = $request->rango_pcl;        
        $tipo_evento = $request->tipo_evento;        
        $tipo_origen = $request->tipo_origen;  
        $f_evento_pericial = $request->f_evento_pericial;
        $f_estructura_pericial = $request->f_estructura_pericial;      
        $sustenta_fecha = $request->sustenta_fecha;        
        $detalle_califi = $request->detalle_califi;        
        $enfermedad_catastrofica = $request->enfermedad_catastrofica;        
        $enfermedad_congenita = $request->enfermedad_congenita;        
        $tipo_enfermedad = $request->tipo_enfermedad;        
        $requiere_persona = $request->requiere_persona;        
        $requiere_decisiones_persona = $request->requiere_decisiones_persona;        
        $requiere_dispositivo_apoyo = $request->requiere_dispositivo_apoyo;        
        $justi_dependencia = $request->justi_dependencia; 
        if (empty($requiere_persona) && empty($requiere_decisiones_persona) && empty($requiere_dispositivo_apoyo)) {
            $justi_dependencia = '';
        } else {
            $justi_dependencia = $justi_dependencia;
        }
        $datos_dictamenPericial =[
            'Suma_combinada' => $suma_combinada,
            'Total_Deficiencia50' => $Total_Deficiencia50,
            'Porcentaje_pcl' => $porcentaje_pcl,
            'Rango_pcl' => $rango_pcl,
            'Tipo_evento' => $tipo_evento,
            'Origen' => $tipo_origen,
            'F_evento' => $f_evento_pericial,
            'F_estructuracion' => $f_estructura_pericial,
            'Sustentacion_F_estructuracion' => $sustenta_fecha,
            'Detalle_calificacion' => $detalle_califi,
            'Enfermedad_catastrofica' => $enfermedad_catastrofica,
            'Enfermedad_congenita' => $enfermedad_congenita,
            'Tipo_enfermedad' => $tipo_enfermedad,
            'Requiere_tercera_persona' => $requiere_persona,
            'Requiere_tercera_persona_decisiones' => $requiere_decisiones_persona,
            'Requiere_dispositivo_apoyo' => $requiere_dispositivo_apoyo,
            'Justificacion_dependencia' => $justi_dependencia,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];

        sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $Id_EventoDecreto)->update($datos_dictamenPericial);   

        $mensajes = array(
            "parametro" => 'insertar_dictamen_pericial',
            "mensaje" => 'Concepto final del dictamen pericial guardado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    // Deficiencias Decreto Cero

    public function guardarDeficieciasDecretoCero(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;   

        /* CAPTURA DE DATOS DE LA DEFICIENCIA */
        $array_datos = $request->datos_finales_deficiciencias_decreto_cero;
        //print_r($array_datos);

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

        // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'Deficiencia', 'Nombre_usuario','F_registro'];
        
        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar) {
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->insert($insertar);
        }

        $mensajes = array(
            "parametro" => 'inserto_informacion_deficiencias_decreto_cero',
            "mensaje" => 'Deficiencia guardada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));    

    }

    public function eliminarDeficieciasDecretoCero(Request $request){
        $id_fila_deficiencia_cero = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_cero)
        ->update($fila_actualizar);

        $total_registros_deficiencias_cero = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_deficiencia_cero_eliminada',
            'total_registros' => $total_registros_deficiencias_cero,
            "mensaje" => 'Deficiencia eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }  

    // Deficiencias Decreto Tres

    public function guardarDeficieciasDecretoTres(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;   

        /* CAPTURA DE DATOS DE LA DEFICIENCIA */
        $array_datos = $request->datos_finales_deficiciencias_decreto_tres;
        //print_r($array_datos);

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

        // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Tabla1999', 'Titulo_tabla1999', 'Deficiencia', 'Nombre_usuario','F_registro'];
        
        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar) {
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->insert($insertar);
        }

        $mensajes = array(
            "parametro" => 'inserto_informacion_deficiencias_decreto_tres',
            "mensaje" => 'Deficiencia guardada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));    

    }

    public function eliminarDeficieciasDecretoTres(Request $request){
        $id_fila_deficiencia_cero = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_cero)
        ->update($fila_actualizar);

        $total_registros_deficiencias_tres = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_deficiencia_tres_eliminada',
            'total_registros' => $total_registros_deficiencias_tres,
            "mensaje" => 'Deficiencia eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }  
}
