<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_historial_acciones_eventos;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_tipo_evento_documentos;
use App\Models\sigmel_lista_grupo_documentales;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\cndatos_info_comunicado_eventos;
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_seguimientos_eventos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;
use App\Models\sigmel_informacion_historial_accion_eventos;

class CalificacionOrigenController extends Controller
{
    public function mostrarVistaCalificacionOrigen(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $time = time();
        $date = date("Y-m-d", $time);
        $newIdAsignacion=$request->newIdAsignacion;
        $newIdEvento = $request->newIdEvento;
        if ($request->Id_Servicio <> "") {
            $Id_servicio = $request->Id_Servicio;
        } else {
            $Id_servicio = $request->newIdServicio;
        }

        $array_datos_calificacionOrigen = DB::select('CALL psrcalificacionOrigen(?)', array($newIdAsignacion));
        //Trae Documetos Generales del evento
        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?,?)',array($newIdEvento,$Id_servicio));
        //Consulta Vista a mostrar
        $TraeVista= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_lista_procesos_servicios as p')
        ->select('v.nombre_renderizar')
        ->leftJoin('sigmel_sys.sigmel_vistas as v', 'p.Id_vista', '=', 'v.id')
        ->where('p.Id_Servicio',  '=', $array_datos_calificacionOrigen[0]->Id_Servicio)
        ->get();
        $SubModulo=$TraeVista[0]->nombre_renderizar; //Enviar a la vista del SubModulo    
        // Trae Tipo De evento formulario Nuevo
        $Fnuevo= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_eventos as f')
        ->select('f.Tipo_evento','n.Nombre_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as n', 'n.Id_Evento', '=', 'f.Tipo_evento')
        ->where('f.ID_evento',  '=', $newIdEvento)
        ->get();

        //Trae Documentos Solicitados
        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where([
            ['ID_evento',$newIdEvento],
            ['Id_Asignacion', $newIdAsignacion],
            ['Estado','Activo'],
            ['Id_proceso','1']
         ])
        ->get();
        
        $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'Aporta_documento')
        ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion], ['Estado', 'Inactivo'], ['Aporta_documento', 'No']])
        ->get();
        
        //Trae el ultimo grupo documeltal
       $dato_ultimo_grupo_doc= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_documentos_solicitados_eventos as d')
       ->select('d.Grupo_documental','s.Tipo_documento')
       ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_evento_documentos as s', 'd.Grupo_documental', '=', 's.Id_Tipo_documento')
       ->where([
                ['d.ID_evento', $newIdEvento],
                ['d.Id_Asignacion', $newIdAsignacion], 
                ['d.Id_proceso', '1'], 
                ['d.Estado', 'Activo'], 
                ['d.Grupo_documental','<>','']
            ])
        ->orderBy('d.Id_Documento_Solicitado', 'desc')
        ->limit(1)
        ->get();
        //Trae si ya marco Articulo 12
        $dato_articulo_12= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_documentos_solicitados_eventos')
       ->select('Articulo_12')
       ->where([
                ['ID_evento', $newIdEvento],
                ['Id_Asignacion', $newIdAsignacion], 
                ['Id_proceso', '1'], 
                ['Articulo_12','=','No_mas_seguimiento']
            ])
        ->orderBy('Id_Documento_Solicitado', 'desc')
        ->limit(1)
        ->get();
        //Trae Documentos sugeridos
        if(!empty($dato_ultimo_grupo_doc[0]->Grupo_documental)){
            $dato_doc_sugeridos = sigmel_lista_grupo_documentales::on('sigmel_gestiones')
            ->select('Id_documental','Documento')
            ->where('Id_Tipo_documento',$dato_ultimo_grupo_doc[0]->Grupo_documental)
            ->get(); 
        }else{
            $dato_doc_sugeridos= "";
        } 
        
        $arraycampa_documento_solicitado = count($listado_documentos_solicitados);

        // creación de consecutivo para el comunicado
       $radicadocomunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
       ->select('N_radicado')
       ->where([
           ['ID_evento',$newIdEvento],
           ['F_comunicado',$date],
           ['Id_proceso','1']
       ])
       ->orderBy('N_radicado', 'desc')
       ->limit(1)
       ->get();
       
       if(count($radicadocomunicado)==0){
           $fechaActual = date("Ymd");
           // Obtener el último valor de la base de datos o archivo
           $consecutivoP1 = "SAL-ORI";
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
           $consecutivo = "SAL-ORI" . $fechaActual . $nuevoConsecutivoFormatted; 
           
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
           $consecutivo = "SAL-ORI" . $fechaActual . $nuevoConsecutivoFormatted;
       }
       //Consulta Primer Seguimiento
       $primer_seguimiento = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
       ->select('*')
       ->where([
           ['Causal_seguimiento', '=', 'Primer seguimiento'],
           ['Estado', '=', 'Activo'],
           ['Id_proceso', '=', '1'],
           ['ID_evento', '=', $newIdEvento],
           ['Id_Asignacion', $newIdAsignacion]
       ])
       ->get();
       //Consulta Segundo Seguimiento
       $segundo_seguimiento = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
       ->select('*')
       ->where([
           ['Causal_seguimiento', '=', 'Segundo seguimiento'],
           ['Estado', '=', 'Activo'],
           ['Id_proceso', '=', '1'],
           ['ID_evento', '=', $newIdEvento],
           ['Id_Asignacion', $newIdAsignacion]

       ])
       ->get();
       //Consulta tercer Seguimiento
       $tercer_seguimiento = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
       ->select('*')
       ->where([
           ['Causal_seguimiento', '=', 'Tercer seguimiento'],
           ['Estado', '=', 'Activo'],
           ['Id_proceso', '=', '1'],
           ['ID_evento', '=', $newIdEvento],
           ['Id_Asignacion', $newIdAsignacion]

       ])
       ->get();
       //Consulta Listado Historial de seguimientos
       $listado_seguimiento_solicitados = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
       ->select('*')
       ->where([
           ['ID_evento',$newIdEvento],
           ['Id_Asignacion', $newIdAsignacion],
           ['Estado','Activo'],
           ['Id_proceso','1']
        ])
       ->get();

        //Consulta comite interdisciplinario del evento
       $cali_profe_comite = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
       ->select('Profesional_comite','F_visado_comite')
       ->where([
           ['ID_evento',$newIdEvento],
           ['Id_Asignacion',$newIdAsignacion],
           ['Id_proceso','1']
        ])
       ->get();
        return view('coordinador.calificacionOrigen', compact('user','nombre_usuario','array_datos_calificacionOrigen','arraylistado_documentos',
        'arraycampa_documento_solicitado','SubModulo','Fnuevo','listado_documentos_solicitados','dato_validacion_no_aporta_docs','dato_ultimo_grupo_doc',
        'dato_doc_sugeridos','dato_articulo_12','consecutivo','primer_seguimiento','segundo_seguimiento','tercer_seguimiento','listado_seguimiento_solicitados','cali_profe_comite', 'Id_servicio'));
    }

    //Guardar informacion del modulo de Origen ATEL
    public function guardarCalificacionOrigen(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $date_time = date("Y-m-d H:i:s");
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        $Id_servicio = $request->Id_servicio;

        $Accion_realizar = $request->accion;

        if ($Accion_realizar == 7) {
            $Fecha_devolucion_comite = $date_time;
            $Causal_devolucion_comite =$request->causal_devolucion_comite;
        }else{
            if ($request->fecha_devolucion == "0000-00-00 00:00:00" || $request->fecha_devolucion == "Sin Fecha Devolución") {
                $Fecha_devolucion_comite = null;
            } else {
                $Fecha_devolucion_comite = $request->fecha_devolucion;
            }
            $Causal_devolucion_comite =$request->causal_devolucion_comite;
        }

        // Fecha de asignación para DTO 
        if ($Accion_realizar == 2 || $Accion_realizar == 101) {
            $Fecha_asignacion_dto = $date_time;
        }else{
            if ($request->fecha_asignacion_dto == "0000-00-00 00:00:00" || $request->fecha_asignacion_dto == "Sin Fecha de Asignación para DTO") {
                $Fecha_asignacion_dto = null;
            } else {
                $Fecha_asignacion_dto = $request->fecha_asignacion_dto;
            }
        }

        // Programación para la Nueva Fecha de Radicación
        if ($request->nueva_fecha_radicacion <> "") {
            $Nueva_fecha_radicacion = $request->nueva_fecha_radicacion;
        } else {
            $Nueva_fecha_radicacion = null;
        }

        // validacion de bandera para guardar o actualizar
        if ($request->banderaguardar == 'Guardar') {
            
            // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
            // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
            // en la creación de un evento
            // MAURO PARAMETRICA
            $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')->where('ID_evento', $newIdEvento)->first();

            $id_cliente = $array_id_cliente["Cliente"];

            $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Estado')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar','=',  $request->accion]
            ])->get();

            if(count($estado_acorde_a_parametrica)>0){
                $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
            }else{
                $Id_Estado_evento = 223;
            }

            /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
            $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Detiene_tiempo_gestion')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar', '=', $request->accion]
            ])->get();

            if(count($casilla_detiene_tiempo_gestion) > 0){
                $Detiene_tiempo_gestion = $casilla_detiene_tiempo_gestion[0]->Detiene_tiempo_gestion;
                if ($Detiene_tiempo_gestion == "Si") {
                    $Detener_tiempo_gestion = "Si";
                    $F_detencion_tiempo_gestion = $date;
                }else{
                    $Detener_tiempo_gestion = "No";
                    $F_detencion_tiempo_gestion = null;
                }
            };

            //Captura de datos para el id y nombre del profesional

            $id_profesional = $request->profesional;

            if (!empty($id_profesional)) {
                $nombre_profesional = DB::table('users')->select('id', 'name')
                ->where('id',$id_profesional)->get();   
                
                if (count($nombre_profesional) > 0) {
                    $asignacion_profesional = $nombre_profesional[0]->name;                    
                }
                
            } else {
                $id_profesional = null;
                $asignacion_profesional = null;                    
            }

            
    
            // insercion de datos a la tabla de sigmel_informacion_accion_eventos
            $datos_info_registrarCalifcacionOrigen= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
                // 'F_accion' => $request->f_accion,
                'F_accion' => $date_time,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Estado_Facturacion' => $request->estado_facturacion,
                'Causal_devolucion_comite' => $Causal_devolucion_comite,
                'F_devolucion_comite' => $Fecha_devolucion_comite,
                'Descripcion_accion' => $request->descripcion_accion,
                'F_cierre' => $request->fecha_cierre,
                'Nombre_usuario' => $nombre_usuario,
                'F_asignacion_dto' => $Fecha_asignacion_dto,
                'F_registro' => $date,
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')->insert($datos_info_registrarCalifcacionOrigen);

            sleep(2);

            // Actualizar la tabla sigmel_informacion_asignacion_eventos
            $datos_info_actualizarAsignacionEvento= [    
                'Id_accion' => $request->accion,
                'Id_Estado_evento' => $Id_Estado_evento,          
                'F_alerta' => $request->fecha_alerta,
                'Id_profesional' => $id_profesional,
                'Nombre_profesional' => $asignacion_profesional,   
                'Nueva_F_radicacion' => $Nueva_fecha_radicacion,          
                'Nombre_usuario' => $nombre_usuario,
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                // 'F_registro' => $date,
            ];

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            sleep(2);

            $datos_info_historial_acciones = [
                'ID_evento' => $newIdEvento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Guardado Modulo Calificacion Origen ATEL.",
                'Descripcion' => $request->descripcion_accion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
            sleep(2);

            // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos

            $datos_historial_accion_eventos = [
                'ID_evento' => $newIdEvento,
                'Id_proceso' => $Id_proceso,
                'Id_servicio' => $Id_servicio,
                'Id_accion' => $Accion_realizar,
                'Descripcion' => $request->descripcion_accion,
                'F_accion' => $date_time,
                'Nombre_usuario' => $nombre_usuario,
            ];

            $idInsertado = sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')->insertGetId($datos_historial_accion_eventos);

            sleep(2);

            // Cargue de documento
            if($request->hasFile('cargue_documentos')){
                $archivo = $request->file('cargue_documentos');
                $path = public_path('Documentos_Eventos/'.$newIdEvento);
                $mode = 0777;
                $tipo_archivo = "Documento Historial Origen";
                $nombre_documento = str_replace(' ', '_', $tipo_archivo);

                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode, true, true);
                    chmod($path, $mode);
                }

                $nombre_final_documento = $nombre_documento."$idInsertado"."_IdEvento_".$newIdEvento.".".$archivo->extension();
                Storage::putFileAs($newIdEvento, $archivo, $nombre_final_documento);
            }else{
                
                $nombre_final_documento='N/A';            
            }     

            // Insertar nombre documento
            
            $nombre_documento_historial = [                
                'Documento' => $nombre_final_documento,                
            ];

            sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
            ->where('Id_historial_accion',$idInsertado)->update($nombre_documento_historial);           

            sleep(2);
            
            $mensajes = array(
                "parametro" => 'agregarCalificacionOrigen',
                "parametro_1" => 'guardo',
                "mensaje_1" => 'Registro agregado satisfactoriamente.'
            );

            return json_decode(json_encode($mensajes, true));

        }elseif ($request->banderaguardar == 'Actualizar') {
            
            // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
            // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
            // en la creación de un evento
            // MAURO PARAMETRICA
            $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')->where('ID_evento', $newIdEvento)->first();

            $id_cliente = $array_id_cliente["Cliente"];

            $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Estado')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar','=',  $request->accion]
            ])->get();

            if(count($estado_acorde_a_parametrica)>0){
                $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
            }else{
                $Id_Estado_evento = 223;
            }

            /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
            $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Detiene_tiempo_gestion')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar', '=', $request->accion]
            ])->get();

            if(count($casilla_detiene_tiempo_gestion) > 0){
                $Detiene_tiempo_gestion = $casilla_detiene_tiempo_gestion[0]->Detiene_tiempo_gestion;
                if ($Detiene_tiempo_gestion == "Si") {
                    $Detener_tiempo_gestion = "Si";
                    $F_detencion_tiempo_gestion = $date;
                }else{
                    $Detener_tiempo_gestion = "No";
                    $F_detencion_tiempo_gestion = null;
                }
            };

            //Captura de datos para el id y nombre del profesional

            $id_profesional = $request->profesional;

            if (!empty($id_profesional)) {
                $nombre_profesional = DB::table('users')->select('id', 'name')
                ->where('id',$id_profesional)->get();   
                
                if (count($nombre_profesional) > 0) {
                    $asignacion_profesional = $nombre_profesional[0]->name;                    
                }
                
            } else {
                $id_profesional = null;
                $asignacion_profesional = null;                    
            }

            
            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos
            $datos_info_registrarCalifcacionOrigen= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
                // 'F_accion' => $request->f_accion,
                'F_accion' => $date_time,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Estado_Facturacion' => $request->estado_facturacion,
                'Causal_devolucion_comite' => $Causal_devolucion_comite,
                'F_devolucion_comite' => $Fecha_devolucion_comite,
                'Descripcion_accion' => $request->descripcion_accion,
                'F_cierre' => $request->fecha_cierre,
                'Nombre_usuario' => $nombre_usuario,
                'F_asignacion_dto' => $Fecha_asignacion_dto,
                'F_registro' => $date,
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_registrarCalifcacionOrigen);
            
            sleep(2);

            // Actualizacion tabla sigmel_informacion_asignacion_eventos
            $datos_info_actualizarAsignacionEvento= [    
                'Id_accion' => $request->accion,
                'Id_Estado_evento' => $Id_Estado_evento,          
                'F_alerta' => $request->fecha_alerta,   
                'Id_profesional' => $id_profesional,
                'Nombre_profesional' => $asignacion_profesional,
                'Nueva_F_radicacion' => $Nueva_fecha_radicacion,            
                'Nombre_usuario' => $nombre_usuario,
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                // 'F_registro' => $date,
            ];

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            sleep(2);

            $datos_info_historial_acciones = [
                'ID_evento' => $newIdEvento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Actualizado Modulo Calificacion Origen ATEL.",
                'Descripcion' => $request->descripcion_accion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
            sleep(2);

            // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos

            $datos_historial_accion_eventos = [
                'ID_evento' => $newIdEvento,
                'Id_proceso' => $Id_proceso,
                'Id_servicio' => $Id_servicio,
                'Id_accion' => $Accion_realizar,
                'Descripcion' => $request->descripcion_accion,
                'F_accion' => $date_time,
                'Nombre_usuario' => $nombre_usuario,
            ];

            $idInsertado = sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')->insertGetId($datos_historial_accion_eventos);

            sleep(2);

            // Cargue de documento
            if($request->hasFile('cargue_documentos')){
                $archivo = $request->file('cargue_documentos');
                $path = public_path('Documentos_Eventos/'.$newIdEvento);
                $mode = 0777;
                $tipo_archivo = "Documento Historial Origen";
                $nombre_documento = str_replace(' ', '_', $tipo_archivo);

                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode, true, true);
                    chmod($path, $mode);
                }

                $nombre_final_documento = $nombre_documento."$idInsertado"."_IdEvento_".$newIdEvento.".".$archivo->extension();
                Storage::putFileAs($newIdEvento, $archivo, $nombre_final_documento);
            }else{
                
                $nombre_final_documento='N/A';            
            }     

            // Insertar nombre documento
            
            $nombre_documento_historial = [                
                'Documento' => $nombre_final_documento,                
            ];

            sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
            ->where('Id_historial_accion',$idInsertado)->update($nombre_documento_historial);           

            sleep(2);

            $mensajes = array(
                "parametro" => 'agregarCalificacionOrigen',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    //Cargar Selectores Origen ATEL
    public function cargueListadoSelectoresOrigenAtel(Request $request){
        $parametro = $request->parametro;
        //Lista tipo evento
        if($parametro == "lista_tipo_evento"){
            $datos_tipo_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
                ->select('Id_Evento','Nombre_evento')
                ->where([
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $informacion_datos_tipo_evento = json_decode(json_encode($datos_tipo_evento, true));
            return response()->json($informacion_datos_tipo_evento);
        }
        //Listado Grupo Documental
        if($parametro == "lista_grupo_documental"){
            $datos_tipo_documetal = sigmel_lista_tipo_evento_documentos::on('sigmel_gestiones')
                ->select('Id_Tipo_documento','Tipo_documento')
                ->where([
                    ['Estado', '=', 'activo'],
                    ['Id_Tipo_Evento', '=', $request->tipo_evento_doc]
                ])
                ->get();

            $informacion_datos_tipo_documetal = json_decode(json_encode($datos_tipo_documetal, true));
            return response()->json($informacion_datos_tipo_documetal);
        }

        //Listado Grupo Documental
        if($parametro == "lista_tipo_documental"){
            $datos_tipo_documetal = sigmel_lista_tipo_evento_documentos::on('sigmel_gestiones')
                ->select('Id_Tipo_documento','Tipo_documento')
                ->where([
                    ['Estado', '=', 'activo'],
                    ['Id_Tipo_Evento', '=', $request->tipo_evento_doc]
                ])
                ->get();

            $informacion_datos_tipo_documetal = json_decode(json_encode($datos_tipo_documetal, true));
            return response()->json($informacion_datos_tipo_documetal);
        }
        //Listado documentos sugeridos
        if($parametro == "lista_doc_sugeridos"){
            $datos_doc_sugeridos = sigmel_lista_grupo_documentales::on('sigmel_gestiones')
                ->select('Id_documental','Documento')
                ->where([
                    ['Estado', '=', 'activo'],
                    ['Id_Tipo_documento', '=', $request->id_gr_documental]
                ])
                ->get();

            $informacion_datos_doc_sugeridos = json_decode(json_encode($datos_doc_sugeridos, true));
            return response()->json($informacion_datos_doc_sugeridos);
        }

        // listado de profesionales segun proceso
        if ($parametro == 'lista_profesional_proceso') {
            $id_proceso_asignacion = $request->id_proceso;
            $lista_profesional_proceso = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET($id_proceso_asignacion, id_procesos_usuario) > 0")->get();

            $info_lista_profesional_proceso = json_decode(json_encode($lista_profesional_proceso, true));
            return response()->json($info_lista_profesional_proceso);
        }

        // Listado Causal de devolucion comite PCL
        if($parametro == 'lista_causal_devo_comite'){            

            $listado_causal_devo_comite = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_lista_causal_devoluciones as slcd')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_proceso', '=', 'slcd.Id_proceso')
            ->select('slcd.Id_causal_devo', 'slcd.Causal_devolucion')
            ->where([
                ['siae.Id_Asignacion',$request->Id_asignacion_pro], 
                ['slcd.Id_proceso',$request->Id_proceso_actual], 
                ['slcd.Estado','activo']
            ])->get();            

            $info_listado_causal_devo_comite = json_decode(json_encode($listado_causal_devo_comite, true));
            return response()->json($info_listado_causal_devo_comite);
        }

        if($parametro == "listado_accion"){
            /* Iniciamos trayendo las acciones a ejecutar configuradas en la tabla de parametrizaciones
            dependiendo del id del cliente, id del proceso, id del servicio, estado activo */
            
            $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')->where('ID_evento', $request->nro_evento)->first();

            $id_cliente = $array_id_cliente["Cliente"];

            $acciones_a_ejecutar = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Accion_ejecutar')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $request->Id_proceso],
                ['sipc.Servicio_asociado', '=', $request->Id_servicio],
                ['sipc.Modulo_principal', '=', 'Si'],
                ['sipc.Status_parametrico', '=', 'Activo']
            ])->get();

            $info_acciones_a_ejecutar = json_decode(json_encode($acciones_a_ejecutar, true));
           
            if (count($info_acciones_a_ejecutar) > 0) {
                // Extraemos las acciones antecesoras a partir de las acciones a ejecutar
                $array_acciones_ejecutar = [];
                for ($i=0; $i < count($info_acciones_a_ejecutar); $i++) { 
                    array_push($array_acciones_ejecutar, $info_acciones_a_ejecutar[$i]->Accion_ejecutar);
                };
                $extraccion_acciones_antecesoras = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
                ->select('Accion_ejecutar','Accion_antecesora')
                ->where([
                    ['Id_cliente', '=', $id_cliente],
                    ['Id_proceso', '=', $request->Id_proceso],
                    ['Servicio_asociado', '=', $request->Id_servicio],
                ])
                ->whereIn('Accion_ejecutar', $array_acciones_ejecutar)
                ->get();
                
                $info_extraccion_acciones_antecesoras = json_decode(json_encode($extraccion_acciones_antecesoras, true));

                // En caso de que almenos exista una acción antecesora, se debe analizar si esta acción 
                // (que depende de una acción ejecutar) está en la tabla de auditorias de asignacion de eventos dependiendo
                // del id del proceso y el id del servicio. El id de la accion a ejecutar estaría dentro de las opciones a mostrar solo si se encuentra el id
                // de la accion antecesora en dicha tabla
                if (count($info_extraccion_acciones_antecesoras) > 0) {
                    
                    foreach ($info_extraccion_acciones_antecesoras as $key => $value) {
                        if ($info_extraccion_acciones_antecesoras[$key]->Accion_antecesora !== null) {
                            $busqueda_accion_antecesora = DB::table(getDatabaseName('sigmel_auditorias') .'sigmel_auditorias_informacion_asignacion_eventos as saiae')
                            ->select('saiae.Aud_Id_accion')
                            ->where([
                                ['saiae.Aud_Id_Asignacion', '=', $request->Id_asignacion],
                                ['saiae.Aud_ID_evento', '=', $request->nro_evento],
                                ['saiae.Aud_Id_proceso', '=', $request->Id_proceso],
                                ['saiae.Aud_Id_servicio', '=', $request->Id_servicio],
                                ['saiae.Aud_Id_accion', $info_extraccion_acciones_antecesoras[$key]->Accion_antecesora]
                            ])
                            ->get();

                            // Si no existe en la tabla debe eliminar la información de la acción a ejecutar ya que esta no se debe mostrar.
                            if (count($busqueda_accion_antecesora) == 0) {
                                unset($info_extraccion_acciones_antecesoras[$key]);
                            }
                        }
                    }
                    
                    $info_extraccion_acciones_antecesoras = array_values($info_extraccion_acciones_antecesoras);
                    
                    /* echo "<pre>";
                    print_r($info_extraccion_acciones_antecesoras);
                    echo "</pre>"; */

                    // Extraemos los id de las acciones a ejecutar para buscarlas en la tabla sigmel_informacion_acciones;
                    $array_listado_acciones = [];
                    for ($a=0; $a < count($info_extraccion_acciones_antecesoras); $a++) { 
                        array_push($array_listado_acciones, $info_extraccion_acciones_antecesoras[$a]->Accion_ejecutar);
                    }

                    // print_r($array_listado_acciones);
                    $listado_acciones = sigmel_informacion_acciones::on('sigmel_gestiones')
                    ->select('Id_Accion', 'Accion as Nombre_accion')
                    ->where([
                        ['Status_accion', '=', 'Activo']
                    ])
                    ->whereIn('Id_Accion', $array_listado_acciones)
                    ->get();

                    $info_listado_acciones_nuevo_servicio = json_decode(json_encode($listado_acciones, true));
                    return response()->json(($info_listado_acciones_nuevo_servicio));
                }
            }
        }

        if ($parametro == "lista_tipos_docs") {
            // $datos_tipos_documentos_familia = sigmel_lista_documentos::on('sigmel_gestiones')
            // ->select('Nro_documento', 'Nombre_documento')
            // ->get();

            $datos_tipos_documentos_familia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_lista_documentos as sld')
            ->leftJoin('sigmel_gestiones.sigmel_registro_documentos_eventos as srde', 'sld.Id_Documento', '=', 'srde.Id_Documento')
            ->select('sld.Nro_documento', 'sld.Nombre_documento')
            ->where([
                ['srde.ID_evento', $request->evento],
                ['srde.Id_servicio', $request->servicio],
                ['sld.Estado', 'activo']
            ])
            ->groupBy('sld.Nro_documento')
            // ->orderBy('sld.Nro_documento', 'ASC')
            ->get();

            $info_datos_tipos_documentos_familia = json_decode(json_encode($datos_tipos_documentos_familia, true));
            return response()->json($info_datos_tipos_documentos_familia);
        }
    }

    // Guardar la información del Listado de Documentos solicitados
    public function GuardarDocumentosSeguimiento(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $date = date("Y-m-d h:i:s");
        $nombre_usuario = Auth::user()->name;
        $parametro = $request->parametro;
        $articulo_12 = $request->articulo_12;
        $grupo_documental = $request->grupo_documental;
        $tipo_evento_doc =$request->tipo_evento_doc;
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
                $subarray_datos[] = $articulo_12;
                $subarray_datos[] = $grupo_documental;
    
                array_push($array_datos_organizados, $subarray_datos);
            }
    
            // Creación de array con los campos de la tabla: sigmel_informacion_documentos_solicitados_eventos
            $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso','F_solicitud_documento','Nombre_documento',
            'Id_solicitante','Nombre_solicitante','F_recepcion_documento', 'Aporta_documento', 'Nombre_usuario','F_registro','Articulo_12','Grupo_documental'];
            // Combinación de los campos de la tabla con los datos
            $array_datos_con_keys = [];
            foreach ($array_datos_organizados as $subarray_datos_organizados) {
                array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
            }
            // Inserción de la información
            foreach ($array_datos_con_keys as $insertar) {
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')->insert($insertar);
            }
            if(!empty($request->articulo_12)){
                sleep(2);
                $f_recepcion_doc= [              
                    'F_recepcion_doc_origen' => $date
                ];
                sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $request->Id_evento],
                    ['Id_proceso', '1'], 
                ])->update($f_recepcion_doc);
            }
            if(empty($request->articulo_12)){
                sleep(2);
                //Quita articulo 12 cuando esta vacio
                $update_articulo_12= [              
                    'Articulo_12' => ''
                ];
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $request->Id_evento],
                    ['Id_proceso', '1'], 
                ])->update($update_articulo_12);
            }
            sleep(2);
            //Actualiza Tipo de evento
            $update_tipo_evento= [              
                'Tipo_evento' => $tipo_evento_doc
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $request->Id_evento)->update($update_tipo_evento);
           

            $mensajes = array(
                "parametro" => 'inserto_informacion',
                "mensaje" => 'Información guardada satisfactoriamente.'
            );
        }
        // Validación: No se inserta datos y selecciona el checkbox de Articulo 12
        if ($parametro == "no_aporta") {

            $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'Aporta_documento')
            ->where([['ID_evento', $request->Id_evento],['Id_Asignacion', $request->Id_Asignacion], ['Estado', 'Inactivo'], ['Articulo_12', 'No_mas_seguimiento']])
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
                    'Id_Documento' => 0,
                    'Nombre_documento' => "N/A",
                    'Descripcion' => "N/A",
                    'Id_solicitante' => 0,
                    'Nombre_solicitante' => "N/A",
                    'Aporta_documento' => "No",
                    'Articulo_12' => "No_mas_seguimiento",
                    //'Grupo_documental' => $request->grupo_documental,
                    'Estado' => "Inactivo",
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date
                ];
             
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')->insert($insertar);
                $mensajes = array(
                    "parametro" => 'inserto_informacion',
                    "mensaje" => 'Información guardada satisfactoriamente.'
                );

                sleep(2);
                $f_recepcion_doc= [              
                    'F_recepcion_doc_origen' => $date
                ];
                sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $request->Id_evento],
                    ['Id_proceso', '1'], 
                ])->update($f_recepcion_doc);

            }

        }
        return json_decode(json_encode($mensajes, true));

    }

    // Eliminar fila de algun registro de la tabla de listado documentos seguimiento
    public function EliminarFilaSeguimiento(Request $request){

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

    // Editar fecha de algun registro de la tabla de listado documentos solicitados
    public function EditarFecha_Recepcion_Doc_soli_ori(Request $request){

        $Id_evento   = $request->Id_evento;
        $Fechas_recepcion = $request->Fechas_recepcion;

        if(isset($Fechas_recepcion)){
            // Itera sobre las fechas de recepción
            foreach ($Fechas_recepcion as $fecha) {
                $id = $fecha['id'];
                $nueva_fecha = $fecha['fecha'];
    
                // Actualiza las fechas de recepción para el evento específico
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
                    ->where('ID_evento', $Id_evento)
                    ->where('Id_Documento_Solicitado', $id)
                    ->update(['F_recepcion_documento' => $nueva_fecha]);
            }
    
            $mensajes = array(
                "parametro" => 'filas_editadas',
                "mensaje" => 'Fechas actualizadas satisfactoriamente.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        // else {
        //     $mensajes = array(
        //         "parametro" => 'filas_NO_editadas',
        //         "mensaje" => 'Debe seleccionar la Fecha de recepción de documentos.'
        //     );  
        // }

    }

    //Captura de datos para insertar el comunicado Orige
    public function captuarDestinatariosPrincipalOrigen(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $nombreusuario = Auth::user()->name; 
        $destinatarioPrincipal = $request->destinatarioPrincipal;
        $identificacion_comunicado_afiliado = $request->identificacion_comunicado_afiliado;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso; 

        switch (true) {
            case ($destinatarioPrincipal == 'Afiliado'):                
                $array_datos_destinatarios = DB::select('CALL psrcomunicados(?,?)', array($newIdEvento,$identificacion_comunicado_afiliado)); 
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();

                // Traer opción seleccionada del campo Medio de notificación del la información del afiliado/beneficiario
                $info_medio_noti = DB::table(getDatabaseName('sigmel_gestiones'). 'sigmel_informacion_afiliado_eventos as siae')
                ->select('siae.Medio_notificacion')
                ->where([['siae.ID_evento', $newIdEvento]])
                ->get();
                
                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_destinatarios' => $array_datos_destinatarios,
                    'array_datos_lider' => $array_datos_lider,
                    'info_medio_noti' => $info_medio_noti
                ]);
            break;
            case ($destinatarioPrincipal == 'Empresa'):                
                $array_datos_destinatarios = DB::select('CALL psrcomunicados(?,?)', array($newIdEvento,$identificacion_comunicado_afiliado)); 
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();

                // Traer opción seleccionada del campo Medio de notificación del la información del afiliado/beneficiario
                $info_medio_noti = DB::table(getDatabaseName('sigmel_gestiones'). 'sigmel_informacion_laboral_eventos as sile')
                ->select('sile.Medio_notificacion')
                ->where([['sile.ID_evento', $newIdEvento]])
                ->get();

                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_destinatarios' => $array_datos_destinatarios,                    
                    'array_datos_lider' => $array_datos_lider,
                    'info_medio_noti' => $info_medio_noti
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

    //Guardar Comunicado Desde cero
    public function guardarComunicadoOrigen(Request $request){
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
        $copiaComunicadoTotal = $request->copiaComunicadoTotal;
        if (!empty($copiaComunicadoTotal)) {
            $total_copia_comunicado = implode(", ", $copiaComunicadoTotal);                
        }else{
            $total_copia_comunicado = '';
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
            'Agregar_copia' => $total_copia_comunicado,
            'Firmar_Comunicado' => $request->firmarcomunicado,
            'Tipo_descarga' => $request->tipo_descarga,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];
        
        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_registrarComunicadoPcl);

        sleep(2);
        $datos_info_historial_acciones = [
            'ID_evento' => $Id_evento,
            'F_accion' => $date,
            'Nombre_usuario' => $nombre_usuario,
            'Accion_realizada' => "Se genera comunicado Origen ATEL.",
            'Descripcion' => $request->asunto,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        
        $mensajes = array(
            "parametro" => 'agregar_comunicado',
            "mensaje" => 'Comunicado generado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    public function historialComunicadosOrigen(Request $request){

        $HistorialComunicadosOrigen = $request->HistorialComunicadosOrigen;
        $newId_evento = $request->newId_evento;
        $newId_asignacion = $request->newId_asignacion;        
        if ($HistorialComunicadosOrigen == 'CargarComunicados') {
            
            $hitorialAgregarComunicado = cndatos_info_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $newId_evento],
                ['Id_Asignacion', $newId_asignacion],
                ['Id_proceso', '1']
            ])
            ->get();
            $arrayhitorialAgregarComunicado = json_decode(json_encode($hitorialAgregarComunicado, true));
            return response()->json(($arrayhitorialAgregarComunicado));

        }
        
    }
    //Mostrar datos de comunicado edición
    public function mostrarModalComunicadoOrigen(Request $request){

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

    //Actualizar Comunicado
    public function actualizarComunicadoOrigen(Request $request){
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
        $copiaComunicadoTotal = $request->agregar_copia_editar;
        if (!empty($copiaComunicadoTotal)) {
            $total_copia_comunicado = implode(", ", $copiaComunicadoTotal);                
        }else{
            $total_copia_comunicado = '';
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
            'Agregar_copia' => $total_copia_comunicado,
            'Firmar_Comunicado' => $request->firmarcomunicado,
            'Tipo_descarga' => $request->tipo_descarga,
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
            'Accion_realizada' => "Se actualiza comunicado Origen ATEL.",
            'Descripcion' => $request->asunto_editar,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        
        $mensajes = array(
            "parametro" => 'actualizar_comunicado',
            "mensaje" => 'Comunicado actualizado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }
    //Guardar Seguimientos Historial Origen
    public function GuardarHistorialSeguiOrigen(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $date = date("Y-m-d");
        $datetime = date("Y-m-d h:i:s");
        $nombre_usuario = Auth::user()->name;
        $parametro = $request->parametro;
        $primer_causal = $request->primer_causal;
        $descrip_seguimiento1 = $request->descrip_seguimiento1;
        $segundo_causal = $request->segundo_causal;
        $descrip_seguimiento2 = $request->descrip_seguimiento2;
        $tercer_causal = $request->tercer_causal; 
        $descrip_seguimiento3 = $request->descrip_seguimiento3;

        //Valida el primer seguimiento
        if($descrip_seguimiento1<>''){

            $fecha_estipula_segui= $request->f_estipulada1;
            //Consulta si ya tiene registro de primer seguirmiento
            $conteo_p1 = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->select('Causal_seguimiento as P1')
            ->where([
                ['Causal_seguimiento', '=', $primer_causal],
                ['Estado', '=', 'Activo'],
                ['Id_proceso', '=', '1'],
                ['ID_evento', '=', $request->Id_evento]
            ])
            ->get();
            //Si no tiene registro lo agrega
            if(empty($conteo_p1[0]->P1)){
                $datos_primer_segui = [
                    'ID_Evento' => $request->Id_evento,
                    'Id_Asignacion' => $request->Id_Asignacion,
                    'Id_proceso' => $request->Id_proceso,
                    'F_seguimiento' => $date,
                    'F_estipula_seguimiento' => $fecha_estipula_segui,
                    'Causal_seguimiento' => $primer_causal,
                    'Descripcion_seguimiento' => $descrip_seguimiento1,
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date
                    
                ];
                sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')->insert($datos_primer_segui);
            }
        }
        //Valida el segundo seguimiento
        if($descrip_seguimiento2<>''){

            $fecha_estipula_segui2= $request->f_estipulada2;
            //Consulta si ya tiene registro de primer seguirmiento
            $conteo_p2 = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->select('Causal_seguimiento as P2')
            ->where([
                ['Causal_seguimiento', '=', $segundo_causal],
                ['Estado', '=', 'Activo'],
                ['Id_proceso', '=', '1'],
                ['ID_evento', '=', $request->Id_evento]
            ])
            ->get();
            //Si no tiene registro lo agrega
            if(empty($conteo_p2[0]->P2)){
                $datos_segundo_segui = [
                    'ID_Evento' => $request->Id_evento,
                    'Id_Asignacion' => $request->Id_Asignacion,
                    'Id_proceso' => $request->Id_proceso,
                    'F_seguimiento' => $date,
                    'F_estipula_seguimiento' => $fecha_estipula_segui2,
                    'Causal_seguimiento' => $segundo_causal,
                    'Descripcion_seguimiento' => $descrip_seguimiento2,
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date
                    
                ];
                sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')->insert($datos_segundo_segui);
            }
        }
        //Valida el tercer seguimiento
        if($descrip_seguimiento3<>''){

            $fecha_estipula_segui3= $request->f_estipulada3;
            //Consulta si ya tiene registro de primer seguirmiento
            $conteo_p3 = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->select('Causal_seguimiento as P3')
            ->where([
                ['Causal_seguimiento', '=', $tercer_causal],
                ['Estado', '=', 'Activo'],
                ['Id_proceso', '=', '1'],
                ['ID_evento', '=', $request->Id_evento]
            ])
            ->get();
            //Si no tiene registro lo agrega
            if(empty($conteo_p3[0]->P3)){
                $datos_tercer_segui = [
                    'ID_Evento' => $request->Id_evento,
                    'Id_Asignacion' => $request->Id_Asignacion,
                    'Id_proceso' => $request->Id_proceso,
                    'F_seguimiento' => $date,
                    'F_estipula_seguimiento' => $fecha_estipula_segui3,
                    'Causal_seguimiento' => $tercer_causal,
                    'Descripcion_seguimiento' => $descrip_seguimiento3,
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date
                    
                ];
                sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')->insert($datos_tercer_segui);
            }
        }

        // Si registra otros seguimientos
        if (!empty($request->datos_finales_documentos_solicitados)) {

            // Seteo del autoincrement para mantener el primary key siempre consecutivo.
            $max_id = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->max('Id_Seguimiento');
            if ($max_id <> "") {
                DB::connection('sigmel_gestiones')
                ->statement("ALTER TABLE sigmel_informacion_seguimientos_eventos AUTO_INCREMENT = ".($max_id));
            }
            
           
            // Captura del array de los datos de la tabla
            $array_datos = $request->datos_finales_documentos_solicitados;
            // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
            $array_datos_organizados = [];
            foreach ($array_datos as $subarray_datos) {
                
                array_unshift($subarray_datos, $request->Id_proceso);
                array_unshift($subarray_datos, $request->Id_Asignacion);
                array_unshift($subarray_datos, $request->Id_evento);

                $subarray_datos[] = $date;
    
                array_push($array_datos_organizados, $subarray_datos);
            }
    
            // Creación de array con los campos de la tabla: sigmel_informacion_documentos_solicitados_eventos
            $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso','Causal_seguimiento','F_estipula_seguimiento',
            'F_seguimiento','Descripcion_seguimiento','Nombre_usuario','F_registro'];
            // Combinación de los campos de la tabla con los datos
            $array_datos_con_keys = [];
            foreach ($array_datos_organizados as $subarray_datos_organizados) {
                array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
            }
            // Inserción de la información
            foreach ($array_datos_con_keys as $insertar) {
                sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')->insert($insertar);
            }
        }
       
        $mensajes = array(
            "parametro" => 'inserto_informacion',
            "mensaje" => 'Información guardada satisfactoriamente.'
        );
        return json_decode(json_encode($mensajes, true)); 

    }

    // Eliminar fila de algun registro de la tabla de historial de seguimientos
    public function EliminarFilaHistoSeguimiento(Request $request){

        $id_fila = $request->fila;

        $dato_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')->where('Id_seguimiento', $id_fila)
        ->update($dato_actualizar);

        $total_registros = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_eliminada',
            'total_registros' => $total_registros,
            "mensaje" => 'Información eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    // Historial de acciones de la parametrica de la tabla sigmel_informacion_historial_accion_eventos

    public function historialAccionesEventoOri (Request $request){

        $array_datos_historial_accion_eventos = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_historial_accion_eventos as sihae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia', 'sia.Id_Accion', '=', 'sihae.Id_accion')
        ->select('sihae.Id_historial_accion', 'sihae.ID_evento', 'sihae.Id_proceso', 'sihae.Id_servicio', 'sihae.Id_accion', 
        'sia.Accion', 'sihae.Documento', 'sihae.Descripcion', 'sihae.F_accion', 'sihae.Nombre_usuario')
        ->where([['sihae.ID_evento', $request->ID_evento],['sihae.Id_proceso', $request->Id_proceso]])
        ->orderBy('sihae.F_accion', 'asc')->get();
       
        return response()->json($array_datos_historial_accion_eventos);
    }

}
