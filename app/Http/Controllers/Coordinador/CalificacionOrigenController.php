<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_historial_acciones_eventos;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_tipo_evento_documentos;
use App\Models\sigmel_lista_grupo_documentales;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_informacion_eventos;




class CalificacionOrigenController extends Controller
{
    public function mostrarVistaCalificacionOrigen(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $newIdAsignacion=$request->newIdAsignacion;
        $newIdEvento = $request->newIdEvento;

        $array_datos_calificacionOrigen = DB::select('CALL psrcalificacionOrigen(?)', array($newIdAsignacion));
        //Trae Documetos Generales del evento
        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));
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
        
        return view('coordinador.calificacionOrigen', compact('user','array_datos_calificacionOrigen','arraylistado_documentos','arraycampa_documento_solicitado','SubModulo','Fnuevo','listado_documentos_solicitados','dato_validacion_no_aporta_docs','dato_ultimo_grupo_doc','dato_doc_sugeridos','dato_articulo_12'));
    }

    //Guardar informacion del modulo de Origen ATEL
    public function guardarCalificacionOrigen(Request $request){
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
    
            $datos_info_registrarCalifcacionOrigen= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
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
    
            sigmel_informacion_accion_eventos::on('sigmel_gestiones')->insert($datos_info_registrarCalifcacionOrigen);

            sleep(2);

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
            $mensajes = array(
                "parametro" => 'agregarCalificacionOrigen',
                "parametro_1" => 'guardo',
                "mensaje_1" => 'Registro agregado satisfactoriamente.'
            );

            return json_decode(json_encode($mensajes, true));

        }elseif ($request->bandera_accion_guardar_actualizar == 'Actualizar') {
            
            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos

            $datos_info_registrarCalifcacionOrigen= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
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
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_registrarCalifcacionOrigen);
            sleep(2);
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
                    'Grupo_documental' => $request->grupo_documental,
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
}
