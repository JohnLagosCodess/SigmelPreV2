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
use App\Models\sigmel_lista_clases_decretos;

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
        $SubModulo='CalficacionTecnicaPCL'; //Enviar a la vista del SubModulo    

        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));
        $array_datos_destinatarios = DB::select('CALL psrcomunicados(?)', array($newIdEvento));

        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where('Estado', 'Activo')
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
        $agregar_copia = $request->agregar_copia;
        $total_agregarcopias = implode(", ", $agregar_copia);

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

        if ($HistorialComunicadosPcl == 'CargarComunicados') {
            
            $hitorialAgregarComunicado = cndatos_info_comunicado_eventos::on('sigmel_gestiones')
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
        $agregar_copia_editar = $request->agregar_copia_editar;
        $total_agregarcopias = implode(", ", $agregar_copia_editar);

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

        //Traer Informacion ya registrada tecnica
        $datos_demos= array(
            "Origen" => "48",
            "NombreOrigen" => "Si",
            "Cobertura" => "50",
            "NombreCobertura" => "Si",
            "Decreto" => "1",
            "NombreDecreto" => "MUCI - 1507 de 2014",
        );
        
        return view('coordinador.calificacionTecnicaPCL', compact('user','array_datos_calificacionPclTecnica','motivo_solicitud_actual','datos_apoderado_actual', 'hay_agudeza_visual','datos_demos'));
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

}
