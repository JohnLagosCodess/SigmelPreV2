<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use PDF;

use App\Http\Requests\CargarDocRequest;

//Cargar Modelos
use App\Models\sigmel_lista_entidades;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_regional_juntas;
use App\Models\sigmel_informacion_pronunciamiento_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_auditorias_pronunciamiento_eventos;

class PronunciamientoPCLController extends Controller
{
    // TODO LO REFERENTE SERVICIO PRONUNCIAMIENTO
    public function mostrarVistaPronunciamiento(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $date=date("Y-m-d");
        $Id_evento_calitec=$request->Id_evento_pcl;
        $Id_asignacion_calitec = $request->Id_asignacion_pcl;
        $array_datos_pronunciamientoPcl = DB::select('CALL psrcalificacionpcl(?)', array($Id_asignacion_calitec));
        //Traer info informacion pronunciamiento
        $info_pronuncia= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_pronunciamiento_eventos as pr')
        ->select('pr.ID_evento','pr.Id_primer_calificador','c.Tipo_Entidad','pr.Id_nombre_calificador','e.Nombre_entidad'
        ,'pr.Nit_calificador','pr.Dir_calificador','pr.Email_calificador','pr.Telefono_calificador','pr.Depar_calificador','pr.Ciudad_calificador'
        ,'pr.Id_tipo_pronunciamiento','p.Nombre_parametro as Tpronuncia','pr.Id_tipo_evento','ti.Nombre_evento','pr.Id_tipo_origen','or.Nombre_parametro as T_origen'
        ,'pr.Fecha_evento','pr.Dictamen_calificador','pr.Fecha_calificador','pr.Fecha_estruturacion','pr.Porcentaje_pcl','pr.Rango_pcl'
        ,'pr.Decision','pr.Fecha_pronuncia','pr.Asunto_cali','pr.Sustenta_cali','pr.Destinatario_principal','pr.Tipo_entidad','pr.Nombre_entidad','pr.Copia_afiliado','pr.copia_empleador','pr.Copia_eps'
        ,'pr.Copia_afp','pr.Copia_arl','pr.Copia_junta_regional','pr.Copia_junta_nacional','pr.Junta_regional_cual','j.Ciudad_Junta'
        ,'pr.N_anexos','pr.Elaboro_pronuncia','pr.Reviso_Pronuncia','pr.Ciudad_correspon','pr.N_radicado','pr.Firmar','pr.Fecha_correspondencia'
        ,'pr.Archivo_pronuncia')
        ->leftJoin('sigmel_gestiones.sigmel_lista_entidades as c', 'c.Id_Entidad', '=', 'pr.Id_primer_calificador')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as e', 'e.Id_Entidad', '=', 'pr.Id_nombre_calificador')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as p', 'p.Id_Parametro', '=', 'pr.Id_tipo_pronunciamiento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as ti', 'ti.Id_Evento', '=', 'pr.Id_tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as or', 'or.Id_Parametro', '=', 'pr.Id_tipo_origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_regional_juntas as j', 'j.Id_juntaR', '=', 'pr.Junta_regional_cual')
        ->where([
            ['pr.ID_evento', '=', $Id_evento_calitec],
            ['pr.Id_Asignacion', '=', $Id_asignacion_calitec]
        ])
        ->get();
        
        $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->select('side.Id_Diagnosticos_motcali', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->where([
            ['side.Estado', '=', 'Activo'],
            ['side.ID_evento', '=', $Id_evento_calitec],
            ['side.Id_Asignacion', '=', $Id_asignacion_calitec]
        ])
        ->get(); 

        // creación de consecutivo para el comunicado
       $radicadocomunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
       ->select('N_radicado')
       ->where([
           ['ID_evento',$Id_evento_calitec],
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

        return view('coordinador.pronunciamientoPCL', compact('user','array_datos_pronunciamientoPcl','info_pronuncia','array_datos_diagnostico_motcalifi','consecutivo'));
    }
    //Ver Documento Pronuncia
    public function VerDocumentoPronuncia(Request $request){
        $Idevento=$request->Id_evento;
        $nomarchivo=$request->nom_archivo;
        $rutaDocumento = $Idevento. '/' .$nomarchivo;
        $urlDocumentoPr = public_path('Documentos_Eventos/' .$rutaDocumento);
        if (file_exists($urlDocumentoPr)) {
            return response()->download($urlDocumentoPr,$nomarchivo);
        } else {
            return response()->json([
                'message' => 'El archivo no existe.',
            ], 404);
        }
    }  
    
    //Cargar Selectores pronunciamiento
    public function cargueListadoSelectoresPronunciamiento(Request $request){
    
        $parametro = $request->parametro;
        // Listado tipo entidad
        if($parametro == 'lista_primer_calificador'){
            $listado_tipo_entidad = sigmel_lista_entidades::on('sigmel_gestiones')
            ->select('Id_Entidad', 'Tipo_Entidad')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_tipo_entidad = json_decode(json_encode($listado_tipo_entidad, true));
            return response()->json($info_listado_tipo_entidad);
        }
        // Nombre de entidades
        if($parametro == "lista_nombre_entidad"){
            $datos_nom_enti = sigmel_informacion_entidades::on('sigmel_gestiones')
                ->select('Id_Entidad', 'Nombre_entidad')
                ->where([
                    ['IdTipo_entidad', $request->id_primer_calificador],
                    ['Estado_entidad', 'activo']
                ])
                ->get();

            $informacion_datos_nom_enti = json_decode(json_encode($datos_nom_enti, true));
            return response()->json($informacion_datos_nom_enti);
        }
        // Datos Entidad
        if($parametro == "lista_nombre_entidad_da"){
            $datos_nom_enti_da = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as enti')
                ->select('enti.Nit_entidad','enti.Direccion','enti.Emails','enti.Telefonos'
                ,'d.Nombre_departamento','d.Nombre_municipio')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as d', 'enti.Id_Ciudad', '=', 'd.Id_municipios')
                ->where([
                    ['Id_Entidad', $request->id_primer_calificador_da]
                ])
                ->get();

            $informacion_datos_nom_enti_da = json_decode(json_encode($datos_nom_enti_da, true));
            return response()->json($informacion_datos_nom_enti_da);
        }
        //Lista tipo pronuciamiento
        if($parametro == "lista_tipo_pronuncia"){
            $datos_tipo_pronuncia = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Tipo pronunciamiento'],
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_pronuncia = json_decode(json_encode($datos_tipo_pronuncia, true));
            return response()->json($informacion_datos_tipo_pronuncia);
        }
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
        //Lista tipo origen
        if($parametro == "lista_tipo_origen"){
            $datos_tipo_origen = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Origen Cie10'],
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_origen = json_decode(json_encode($datos_tipo_origen, true));
            return response()->json($informacion_datos_tipo_origen);
        }
        //Lista juntas regional
        if($parametro == "lista_regional_junta"){
            $datos_tipo_junta = sigmel_lista_regional_juntas::on('sigmel_gestiones')
                ->select('Id_juntaR','Ciudad_Junta')
                ->where([
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_junta = json_decode(json_encode($datos_tipo_junta, true));
            return response()->json($informacion_datos_tipo_junta);
        }

        //Lista lider grupos
        if($parametro == "lista_lider_grupo"){
            $datos_lider_grupo = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_usuarios_grupos_trabajos as ug')
                ->select('ug.id_equipo_trabajo','li.name')
                ->leftJoin('sigmel_sys.users as g', 'ug.id_usuarios_asignados', '=', 'g.id')
                ->leftJoin('sigmel_gestiones.sigmel_grupos_trabajos as gr', 'ug.id_equipo_trabajo', '=', 'gr.id')
                ->leftJoin('sigmel_sys.users as li', 'gr.lider', '=', 'li.id')
                ->where([
                    ['g.name', $request->nom_usuario_session]
                ])
                ->get();

            $informacion_datos_lider_grupo = json_decode(json_encode($datos_lider_grupo, true));
            return response()->json($informacion_datos_lider_grupo);
        }

        // Listado tipo entidad
        if($parametro == "lista_tipo_entidad"){
            $listado_tipo_entidad = sigmel_lista_entidades::on('sigmel_gestiones')
            ->select('Id_Entidad', 'Tipo_Entidad')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_tipo_entidad = json_decode(json_encode($listado_tipo_entidad, true));
            return response()->json($info_listado_tipo_entidad);
        }

        // listado nombre entidad
        if ($parametro == "nombre_entidad") {
            $listado_nombres_entidades = sigmel_informacion_entidades::on("sigmel_gestiones")
            ->select('Id_Entidad', 'Nombre_entidad')
            ->where(
                [['IdTipo_entidad', $request->id_tipo_entidad]]
            )->get();

            $info_listado_nombres_entidades = json_decode(json_encode($listado_nombres_entidades, true));
            return response()->json($info_listado_nombres_entidades);
        }

    }

    //Guardar o actualizar informacion pronunciamiento
    public function guardarInfoServiPronuncia(Request $request){
    
        if(!Auth::check()){
            return redirect('/');
        }
        $datetime = date("Y-m-d h:i:s");
        $date=date("Y-m-d");
        $nombre_usuario = Auth::user()->name;
        $Id_EventoPronuncia = $request->Id_EventoPronuncia;
        $Id_ProcesoPronuncia = $request->Id_ProcesoPronuncia;
        $Id_Asignacion_Pronuncia = $request->Id_Asignacion_Pronuncia;
        // Captura del array de los datos de la tabla
        
        $array_diagnosticos_motivo_calificacion = json_decode($request->datos_finales_diagnosticos_moticalifi);

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        //Valida que no este vacio el CIE10
        if(!empty($array_diagnosticos_motivo_calificacion)){
            $array_datos_organizados = [];
            foreach ($array_diagnosticos_motivo_calificacion as $subarray_datos) {

                array_unshift($subarray_datos, $request->Id_ProcesoPronuncia);
                array_unshift($subarray_datos, $request->Id_Asignacion_Pronuncia);
                array_unshift($subarray_datos, $request->Id_EventoPronuncia);

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

        }
        //Proceso para subir archivo
        if($request->file('DocPronuncia') <> ""){
            $archivo = $request->file('DocPronuncia');
            $path = public_path('Documentos_Eventos/'.$Id_EventoPronuncia);
            $mode = 0777;
            $tipo_archivo = "Documento Pronunciamiento";
            $nombre_lista_documento = str_replace(' ', '_', $tipo_archivo);

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode, true, true);
                chmod($path, $mode);
            }

            $nombre_final_documento_en_carpeta = $nombre_lista_documento."_IdEvento_".$Id_EventoPronuncia.".".$archivo->extension();
            Storage::putFileAs($Id_EventoPronuncia, $archivo, $nombre_final_documento_en_carpeta);
        }else{
            //Consulta Nombre archivo
            $Archivo_Actual = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')
            ->select('Archivo_pronuncia')
            ->where([
                ['ID_evento', '=', $request->Id_EventoPronuncia],
                ['Id_Asignacion', '=', $request->Id_Asignacion_Pronuncia]
            ])->get();
            if(count($Archivo_Actual)>0){
                $nombre_final_documento_en_carpeta=$Archivo_Actual[0]->Archivo_pronuncia;
            }else{
                $nombre_final_documento_en_carpeta='N/A';
            }
        }

        if ($request->destinatario_principal == "Si") {
            $destinatario_principal = "Si";
            $tipo_entidad = $request->tipo_entidad;
            $nombre_entidad = $request->nombre_entidad;
        }else{
            $destinatario_principal = "No";
            $tipo_entidad = null;
            $nombre_entidad = null;
        }

        //valida la acción del botón
        if ($request->bandera_pronuncia_guardar_actualizar == 'Guardar') {
        
            $datos_info_pronunciamiento_eventos = [
                'ID_Evento' => $Id_EventoPronuncia,
                'Id_proceso' => $Id_ProcesoPronuncia,
                'Id_Asignacion' => $Id_Asignacion_Pronuncia,
                'Id_primer_calificador' => $request->primer_calificador,
                'Id_nombre_calificador' => $request->nombre_calificador,
                'Nit_calificador' => $request->nit_calificador,
                'Dir_calificador' => $request->dir_calificador,
                'Email_calificador' => $request->mail_calificador,
                'Telefono_calificador' => $request->telefono_calificador,
                'Depar_calificador' =>  $request->depar_calificador,
                'Ciudad_calificador' => $request->ciudad_calificador,
                'Id_tipo_pronunciamiento' => $request->tipo_pronunciamiento,
                'Id_tipo_evento' => $request->tipo_evento,
                'Id_tipo_origen' => $request->tipo_origen,
                'Fecha_evento' => $request->fecha_evento,
                'Dictamen_calificador' => $request->dictamen_calificador,
                'Fecha_calificador' => $request->fecha_calificador,
                'Fecha_estruturacion' => $request->fecha_estruturacion,
                'Porcentaje_pcl' => $request->porcentaje_pcl,
                'Rango_pcl' => $request->rango_pcl,
                'Decision' => $request->decision_pr,
                'Fecha_pronuncia' => $datetime,
                'Asunto_cali' => $request->asunto_cali,
                'Sustenta_cali' => $request->sustenta_cali,
                'Destinatario_principal' => $destinatario_principal,
                'Tipo_entidad' => $tipo_entidad,
                'Nombre_entidad' => $nombre_entidad,
                'Copia_afiliado' => $request->copia_afiliado,
                'Copia_empleador' => $request->copia_empleador,
                'Copia_eps' => $request->copia_eps,
                'Copia_afp' => $request->copia_afp,
                'Copia_arl' => $request->copia_arl,
                'Copia_junta_regional' => $request->junta_regional,
                'Copia_junta_nacional' => $request->junta_nacional,
                'Junta_regional_cual' => $request->junta_regional_cual,
                'N_anexos' => $request->n_anexos,
                'Elaboro_pronuncia' => $request->elaboro,
                'Reviso_pronuncia' => $request->reviso,
                'Ciudad_correspon' => $request->ciudad_correspon,
                'N_radicado' => $request->n_radicado,
                'Firmar' => $request->firmar,
                'Fecha_correspondencia' => $request->fecha_correspon,
                'Archivo_pronuncia' => $nombre_final_documento_en_carpeta,
                'created_at' => $datetime,
            ];
            $datos_info_comunicado_eventos = [
                'ID_Evento' => $Id_EventoPronuncia,
                'Id_proceso' => $Id_ProcesoPronuncia,
                'Id_Asignacion' => $Id_Asignacion_Pronuncia,
                'Ciudad' => $request->ciudad_correspon,
                'F_comunicado' => $date,
                'N_radicado' => $request->n_radicado,
                'Cliente' => $request->primer_calificador,
                'Nombre_afiliado' => $request->nombre_afiliado,
                'T_documento' => 'N/A',
                'N_identificacion' => $request->identificacion,
                'Destinatario' => 'N/A',
                'Nombre_destinatario' => 'N/A',
                'Nit_cc' => 'N/A',
                'Direccion_destinatario' => 'N/A',
                'Telefono_destinatario' => '001',
                'Email_destinatario' => 'N/A',
                'Id_departamento' => '001',
                'Id_municipio' => '001',
                'Asunto'=> $request->asunto_cali,
                'Cuerpo_comunicado' => $request->sustenta_cali,
                'Forma_envio' => '0',
                'Elaboro' => $request->elaboro,
                'Reviso' => '0',
                'Anexos' => $request->n_anexos,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')->insert($datos_info_pronunciamiento_eventos);
            sleep(2);
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);
            sleep(2);
            // REGISTRO ACTIVIDAD PARA AUDITORIA //
            $Id_Pronuncia = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')->select('Id_Pronuncia')->latest('Id_Pronuncia')->first();
            $accion_realizada = "Registro de Pronuciamiento {$Id_Pronuncia['Id_Pronuncia']}";
            $registro_actividad = [
                'Id_Pronuncia' => $Id_Pronuncia['Id_Pronuncia'],
                'ID_Evento' => $Id_EventoPronuncia,
                'Id_proceso' => $Id_ProcesoPronuncia,
                'Id_Asignacion' => $Id_Asignacion_Pronuncia,
                'Id_primer_calificador' => $request->primer_calificador,
                'Id_nombre_calificador' => $request->nombre_calificador,
                'Nit_calificador' => $request->nit_calificador,
                'Dir_calificador' => $request->dir_calificador,
                'Email_calificador' => $request->mail_calificador,
                'Telefono_calificador' => $request->telefono_calificador,
                'Depar_calificador' =>  $request->depar_calificador,
                'Ciudad_calificador' => $request->ciudad_calificador,
                'Id_tipo_pronunciamiento' => $request->tipo_pronunciamiento,
                'Id_tipo_evento' => $request->tipo_evento,
                'Id_tipo_origen' => $request->tipo_origen,
                'Fecha_evento' => $request->fecha_evento,
                'Dictamen_calificador' => $request->dictamen_calificador,
                'Fecha_calificador' => $request->fecha_calificador,
                'Fecha_estruturacion' => $request->fecha_estruturacion,
                'Porcentaje_pcl' => $request->porcentaje_pcl,
                'Rango_pcl' => $request->rango_pcl,
                'Decision' => $request->decision_pr,
                'Fecha_pronuncia' => $datetime,
                'Asunto_cali' => $request->asunto_cali,
                'Sustenta_cali' => $request->sustenta_cali,
                'Destinatario_principal' => $destinatario_principal,
                'Tipo_entidad' => $tipo_entidad,
                'Nombre_entidad' => $nombre_entidad,
                'Copia_afiliado' => $request->copia_afiliado,
                'Copia_empleador' => $request->copia_empleador,
                'Copia_eps' => $request->copia_eps,
                'Copia_afp' => $request->copia_afp,
                'Copia_arl' => $request->copia_arl,
                'Copia_junta_regional' => $request->junta_regional,
                'Copia_junta_nacional' => $request->junta_nacional,
                'Junta_regional_cual' => $request->junta_regional_cual,
                'N_anexos' => $request->n_anexos,
                'Elaboro_pronuncia' => $request->elaboro,
                'Reviso_pronuncia' => $request->reviso,
                'Ciudad_correspon' => $request->ciudad_correspon,
                'N_radicado' => $request->n_radicado,
                'Firmar' => $request->firmar,
                'Fecha_correspondencia' => $request->fecha_correspon,
                'Archivo_pronuncia' => $nombre_final_documento_en_carpeta,
                'id_usuario_sesion' => Auth::id(),
                'nombre_usuario_sesion' => Auth::user()->name,
                'acccion_realizada' => $accion_realizada,
                'fecha_registro_accion' => $datetime
            ];
            sigmel_auditorias_pronunciamiento_eventos::on('sigmel_auditorias')->insert($registro_actividad);
            if(!empty($array_diagnosticos_motivo_calificacion)){
                // Inserción de la información
                foreach ($array_datos_con_keys as $insertar_diagnostico) {
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico);
                } 
            }
            $mensajes = array(
                "parametro" => 'agregar_pronunciamiento',
                "parametro2" => 'guardo',
                "mensaje" => 'Guardado satisfactoriamente.'
            ); 

            return json_decode(json_encode($mensajes, true));

        }elseif($request->bandera_pronuncia_guardar_actualizar == 'Actualizar'){

            if ($request->tipo_evento == 2) {
                $Fecha_evento = null;
            } else {
                $Fecha_evento = $request->fecha_evento;
            }           

            $datos_info_pronunciamiento_eventos = [
                'Id_primer_calificador' => $request->primer_calificador,
                'Id_nombre_calificador' => $request->nombre_calificador,
                'Nit_calificador' => $request->nit_calificador,
                'Dir_calificador' => $request->dir_calificador,
                'Email_calificador' => $request->mail_calificador,
                'Telefono_calificador' => $request->telefono_calificador,
                'Depar_calificador' =>  $request->depar_calificador,
                'Ciudad_calificador' => $request->ciudad_calificador,
                'Id_tipo_pronunciamiento' => $request->tipo_pronunciamiento,
                'Id_tipo_evento' => $request->tipo_evento,
                'Id_tipo_origen' => $request->tipo_origen,
                'Fecha_evento' => $Fecha_evento,
                'Dictamen_calificador' => $request->dictamen_calificador,
                'Fecha_calificador' => $request->fecha_calificador,
                'Fecha_estruturacion' => $request->fecha_estruturacion,
                'Porcentaje_pcl' => $request->porcentaje_pcl,
                'Rango_pcl' => $request->rango_pcl,
                'Decision' => $request->decision_pr,
                'Fecha_pronuncia' => $datetime,
                'Asunto_cali' => $request->asunto_cali,
                'Sustenta_cali' => $request->sustenta_cali,
                'Destinatario_principal' => $destinatario_principal,
                'Tipo_entidad' => $tipo_entidad,
                'Nombre_entidad' => $nombre_entidad,
                'Copia_afiliado' => $request->copia_afiliado,
                'Copia_empleador' => $request->copia_empleador,
                'Copia_eps' => $request->copia_eps,
                'Copia_afp' => $request->copia_afp,
                'Copia_arl' => $request->copia_arl,
                'Copia_junta_regional' => $request->junta_regional,
                'Copia_junta_nacional' => $request->junta_nacional,
                'Junta_regional_cual' => $request->junta_regional_cual,
                'N_anexos' => $request->n_anexos,
                'Elaboro_pronuncia' => $request->elaboro,
                'Reviso_pronuncia' => $request->reviso,
                'Ciudad_correspon' => $request->ciudad_correspon,
                'Firmar' => $request->firmar,
                'Fecha_correspondencia' => $request->fecha_correspon,
                'Archivo_pronuncia' => $nombre_final_documento_en_carpeta,
                'updated_at' => $datetime,
            ];
            sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')
            ->where('ID_Evento', $Id_EventoPronuncia)->update($datos_info_pronunciamiento_eventos);
            sleep(2);
            // REGISTRO ACTIVIDAD PARA AUDITORIA //
            $Id_Pronuncia = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')->select('Id_Pronuncia','Id_Asignacion')->latest('Id_Pronuncia')->first();
            $accion_realizada = "Actualiza Pronuciamiento {$Id_Pronuncia['Id_Pronuncia']}";
            $registro_actividad = [
                'Id_Pronuncia' => $Id_Pronuncia['Id_Pronuncia'],
                'ID_Evento' => $Id_EventoPronuncia,
                'Id_proceso' => $Id_ProcesoPronuncia,
                'Id_Asignacion' => $Id_Pronuncia['Id_Asignacion'],
                'Id_primer_calificador' => $request->primer_calificador,
                'Id_nombre_calificador' => $request->nombre_calificador,
                'Nit_calificador' => $request->nit_calificador,
                'Dir_calificador' => $request->dir_calificador,
                'Email_calificador' => $request->mail_calificador,
                'Telefono_calificador' => $request->telefono_calificador,
                'Depar_calificador' =>  $request->depar_calificador,
                'Ciudad_calificador' => $request->ciudad_calificador,
                'Id_tipo_pronunciamiento' => $request->tipo_pronunciamiento,
                'Id_tipo_evento' => $request->tipo_evento,
                'Id_tipo_origen' => $request->tipo_origen,
                'Fecha_evento' => $Fecha_evento,
                'Dictamen_calificador' => $request->dictamen_calificador,
                'Fecha_calificador' => $request->fecha_calificador,
                'Fecha_estruturacion' => $request->fecha_estruturacion,
                'Porcentaje_pcl' => $request->porcentaje_pcl,
                'Rango_pcl' => $request->rango_pcl,
                'Decision' => $request->decision_pr,
                'Fecha_pronuncia' => $datetime,
                'Asunto_cali' => $request->asunto_cali,
                'Sustenta_cali' => $request->sustenta_cali,
                'Destinatario_principal' => $destinatario_principal,
                'Tipo_entidad' => $tipo_entidad,
                'Nombre_entidad' => $nombre_entidad,
                'Copia_afiliado' => $request->copia_afiliado,
                'Copia_empleador' => $request->copia_empleador,
                'Copia_eps' => $request->copia_eps,
                'Copia_afp' => $request->copia_afp,
                'Copia_arl' => $request->copia_arl,
                'Copia_junta_regional' => $request->junta_regional,
                'Copia_junta_nacional' => $request->junta_nacional,
                'Junta_regional_cual' => $request->junta_regional_cual,
                'N_anexos' => $request->n_anexos,
                'Elaboro_pronuncia' => $request->elaboro,
                'Reviso_pronuncia' => $request->reviso,
                'Ciudad_correspon' => $request->ciudad_correspon,
                'N_radicado' => $request->n_radicado,
                'Firmar' => $request->firmar,
                'Fecha_correspondencia' => $request->fecha_correspon,
                'Archivo_pronuncia' => $nombre_final_documento_en_carpeta,
                'id_usuario_sesion' => Auth::id(),
                'nombre_usuario_sesion' => Auth::user()->name,
                'acccion_realizada' => $accion_realizada,
                'fecha_registro_accion' => $datetime
            ];
            sigmel_auditorias_pronunciamiento_eventos::on('sigmel_auditorias')->insert($registro_actividad);
            if(!empty($array_diagnosticos_motivo_calificacion)){
                // Inserción de la información
                foreach ($array_datos_con_keys as $insertar_diagnostico) {
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico);
                } 
            }
            $mensajes = array(
                "parametro" => 'update_pronunciamiento',
                "parametro2" => 'guardo',
                "mensaje2" => 'Actualiza satisfactoriamente.'
            ); 

            return json_decode(json_encode($mensajes, true));

        } 


    }


}
