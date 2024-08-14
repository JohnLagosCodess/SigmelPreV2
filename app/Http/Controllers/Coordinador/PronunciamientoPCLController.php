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
use Illuminate\Support\Facades\Validator;

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
use App\Models\sigmel_clientes;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_registro_descarga_documentos;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Image;
use Html2Text\Html2Text;
use Mockery\Undefined;

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
        ->select('pr.ID_evento', 'pr.Id_Asignacion', 'Id_proceso','pr.Id_primer_calificador','c.Tipo_Entidad','pr.Id_nombre_calificador','e.Nombre_entidad as Nombre_calificador'
        ,'pr.Nit_calificador','pr.Dir_calificador','pr.Email_calificador','pr.Telefono_calificador','pr.Depar_calificador','pr.Ciudad_calificador'
        ,'pr.Id_tipo_pronunciamiento','p.Nombre_parametro as Tpronuncia','pr.Id_tipo_evento','ti.Nombre_evento','pr.Id_tipo_origen','or.Nombre_parametro as T_origen'
        ,'pr.Fecha_evento','pr.Dictamen_calificador','pr.N_siniestro','pr.Fecha_calificador','pr.Fecha_estruturacion','pr.Porcentaje_pcl','pr.Rango_pcl'
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
        'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones','slp2.Nombre_parametro as Nombre_parametro_lateralidad')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
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
        $array_comunicados = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$Id_evento_calitec], ['Id_Asignacion',$Id_asignacion_calitec], ['Modulo_creacion','pronunciamientoPCL']])->get();
        foreach ($array_comunicados as $comunicado) {
            if ($comunicado['Nombre_documento'] != null && $comunicado['Tipo_descarga'] != 'Manual') {
                $filePath = public_path('Documentos_Eventos/'.$comunicado->ID_evento.'/'.$comunicado->Nombre_documento);
                if(File::exists($filePath)){
                    $comunicado['Existe'] = true;
                }
                else{
                    $comunicado['Existe'] = false;
                }
            }
            else if($comunicado['Tipo_descarga'] === 'Manual'){
                $filePath = public_path('Documentos_Eventos/'.$comunicado['ID_evento'].'/'.$comunicado['Asunto']);
                if(File::exists($filePath)){
                    $comunicado['Existe'] = true;
                }
                else{
                    $comunicado['Existe'] = false;
                }
            }
            else{
                $comunicado['Existe'] = false;
            }
        }

        // Consultamos si el caso está en la bandeja de Notificaciones
        $array_caso_notificado = BandejaNotifiController::evento_en_notificaciones($request->evento,$request->id_asignacion);

        if(count($array_caso_notificado) > 0){
            $caso_notificado = $array_caso_notificado[0]->Notificacion;
        }

        return view('coordinador.pronunciamientoPCL', compact('user','array_datos_pronunciamientoPcl','info_pronuncia','array_datos_diagnostico_motcalifi','consecutivo', 'array_comunicados','caso_notificado'));
    
    }
    //Ver Documento Pronuncia
    public function VerDocumentoPronuncia(Request $request){
        $Idevento=$request->Id_evento;
        $nomarchivo=$request->nom_archivo;
        $Id_Asignacion = $request->Id_Asignacion;
        $Id_proceso = $request->Id_proceso;
        $Fecha_correspondencia = $request->Fecha_correspondencia;
        $N_radicado = $request->N_radicado;
        $rutaDocumento = $Idevento. '/' .$nomarchivo;
        $urlDocumentoPr = public_path('Documentos_Eventos/' .$rutaDocumento);
        if (file_exists($urlDocumentoPr)) {

            $time = time();
            $date = date("Y-m-d", $time);
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Id_Asignacion],
                ['siae.ID_evento', $Idevento],
                ['siae.Id_proceso', $Id_proceso],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nomarchivo],
            ])->get();
            
            if(count($verficar_documento) == 0){
                $info_descarga_documento = [
                    'Id_Asignacion' => $Id_Asignacion,
                    'Id_proceso' => $Id_proceso,
                    'Id_servicio' => $Id_servicio,
                    'ID_evento' => $Idevento,
                    'Nombre_documento' => $nomarchivo,
                    'N_radicado_documento' => $N_radicado,
                    'F_elaboracion_correspondencia' => $Fecha_correspondencia,
                    'F_descarga_documento' => $date,
                    'Nombre_usuario' => Auth::user()->name,
                ];
                
                sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
            }

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
            // $datos_lider_grupo = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_usuarios_grupos_trabajos as ug')
            //     ->select('ug.id_equipo_trabajo','li.name')
            //     ->leftJoin('sigmel_sys.users as g', 'ug.id_usuarios_asignados', '=', 'g.id')
            //     ->leftJoin('sigmel_gestiones.sigmel_grupos_trabajos as gr', 'ug.id_equipo_trabajo', '=', 'gr.id')
            //     ->leftJoin('sigmel_sys.users as li', 'gr.lider', '=', 'li.id')
            //     ->where([
            //         ['g.name', $request->nom_usuario_session]
            //     ])
            //     ->get();

            $datos_lider_grupo =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
            ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
            ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
            ->where([['sgt.Id_proceso_equipo', '=', '1']])->get();

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

        //Lista estados notificacion correspondencia
        if($parametro == "EstadosNotificacionCorrespondencia"){
            $datos_status_notificacion_correspondencia = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro','Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Estatus_Correspondencia'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $datos_status_notificacion_corresp = json_decode(json_encode($datos_status_notificacion_correspondencia, true));
            return response()->json($datos_status_notificacion_corresp);
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
            'CIE10','Nombre_CIE10','Lateralidad_CIE10','Origen_CIE10','Deficiencia_motivo_califi_condiciones',
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
        if ($request->copia_afiliado == 'undefined') {
            $copia_afiliado = null;            
        } else {
            $copia_afiliado = $request->copia_afiliado;            
        }
        if ($request->copia_empleador == 'undefined') {
            $copia_empleador = null;                        
        } else {
            $copia_empleador = $request->copia_empleador;            
        }
        if ($request->copia_eps == 'undefined') {
            $copia_eps = null;                        
        } else {         
            $copia_eps = $request->copia_eps;
        }
        if ($request->copia_afp == 'undefined') {
            $copia_afp = null;                        
        } else {         
            $copia_afp = $request->copia_afp;
        }
        if ($request->copia_afp == 'undefined') {
            $copia_afp = null;                        
        } else {         
            $copia_afp = $request->copia_afp;
        }
        if ($request->copia_arl == 'undefined') {
            $copia_arl = null;                        
        } else {         
            $copia_arl = $request->copia_arl;
        }
        if ($request->junta_regional == 'undefined') {
            $junta_regional = null;                        
        } else {         
            $junta_regional = $request->junta_regional;
        }
        if ($junta_regional == null) {
            $cual =  null;
        } else {
            $cual =  $request->junta_regional_cual;
        }        
        if ($request->junta_nacional == 'undefined') {
            $junta_nacional = null;                        
        } else {         
            $junta_nacional = $request->junta_nacional;
        }

        // Agrupa las variables en un array
        $variables = array($copia_afiliado, $copia_empleador, $copia_eps, $copia_afp, $copia_arl, $junta_regional, $junta_nacional);

        // Filtra los elementos nulos del array
        $variables_filtradas = array_filter($variables, function($valor) {
            return $valor !== null;
        });

        // Verifica si el array resultante está vacío
        if (!empty($variables_filtradas)) {
            // Si hay elementos en el array, los concatenamos con comas
            $agregar_copias_comu = implode(',', $variables_filtradas);
        } else {
            // Si el array está vacío, asignamos una cadena vacía
            $agregar_copias_comu = '';
        }
        
        $radicado = $request->n_radicado;


        /* Se completan los siguientes datos para lo del tema del pbs 014 */

        // el nombre del destinatario principal dependerá del la selección del primer calificador
        $id_primer_calificador = $request->primer_calificador;

        // Caso 1: Arl, Caso 2: Afp, Caso 3: Eps
        switch ($id_primer_calificador) {
            case '1': $Destinatario = 'Arl'; break;

            case '2': $Destinatario = 'Afp'; break;

            case '3': $Destinatario = 'Eps'; break;

            default: $Destinatario = 'N/A'; break;
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
                'N_siniestro' => $request->n_siniestro,
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
                'Copia_afiliado' => $copia_afiliado,
                'Copia_empleador' => $copia_empleador,
                'Copia_eps' => $copia_eps,
                'Copia_afp' => $copia_afp,
                'Copia_arl' => $copia_arl,
                'Copia_junta_regional' => $junta_regional,
                'Copia_junta_nacional' => $junta_nacional,
                'Junta_regional_cual' => $cual,
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
                'Destinatario' => $Destinatario,
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
                'Agregar_copia' => $agregar_copias_comu,
                'Anexos' => $request->n_anexos,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
                'Tipo_descarga' => $request->decision_pr,
                'Reemplazado' => 0,
                'Modulo_creacion' => 'pronunciamientoPCL',
            ];
            sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')->insert($datos_info_pronunciamiento_eventos);
            sleep(2);
            if($request->decision_pr != 'Silencio'){
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);
                sleep(2);
            }
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
                'N_siniestro' => $request->n_siniestro,
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
                'Copia_afiliado' => $copia_afiliado,
                'Copia_empleador' => $copia_empleador,
                'Copia_eps' => $copia_eps,
                'Copia_afp' => $copia_afp,
                'Copia_arl' => $copia_arl,
                'Copia_junta_regional' => $junta_regional,
                'Copia_junta_nacional' => $junta_nacional,
                'Junta_regional_cual' => $cual,
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

            // Actualizacion del profesional calificador
            $datos_profesional_calificador = [
                'Id_calificador' => Auth::user()->id,
                'Nombre_calificador' => Auth::user()->name,
                'F_calificacion' => $date
            ];
        
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $Id_Asignacion_Pronuncia)->update($datos_profesional_calificador);
            
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
                'N_siniestro' => $request->n_siniestro,
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
                'Copia_afiliado' => $copia_afiliado,
                'Copia_empleador' => $copia_empleador,
                'Copia_eps' => $copia_eps,
                'Copia_afp' => $copia_afp,
                'Copia_arl' => $copia_arl,
                'Copia_junta_regional' => $junta_regional,
                'Copia_junta_nacional' => $junta_nacional,
                'Junta_regional_cual' => $cual,
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
            ->where([['ID_Evento', $Id_EventoPronuncia], ['Id_Asignacion',$Id_Asignacion_Pronuncia]])->update($datos_info_pronunciamiento_eventos);
            sleep(2);

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
                'Destinatario' => $Destinatario,
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
                'Tipo_descarga' => $request->decision_pr,
                'Modulo_creacion' => 'pronunciamientoPCL',
                'Reemplazado' => 0,
            ];
            // dd($request->decision_pr);
            if($request->decision_pr != 'Silencio' && $request->Id_Comunicado){
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where([
                    ['ID_evento', $Id_EventoPronuncia],
                    ['Id_Asignacion', $Id_Asignacion_Pronuncia],
                    ['Id_Comunicado', $request->Id_Comunicado]
                ])->update($datos_info_comunicado_eventos);
                sleep(2);
            }
            else if($request->decision_pr == 'Silencio' && $request->Id_Comunicado){
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $request->Id_Comunicado)->delete();
                $archivos_a_eliminar = [
                    "PCL_ACUERDO_{$Id_Asignacion_Pronuncia}_{$request->identificacion}.pdf",
                    "PCL_DESACUERDO_{$Id_Asignacion_Pronuncia}_{$request->identificacion}.docx"
                ];
                
                foreach ($archivos_a_eliminar as $archivo) {
                    $ruta_archivo = "Documentos_Eventos/{$Id_EventoPronuncia}/{$archivo}";
                    $path = public_path($ruta_archivo);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }
                sleep(2);
            }
            if($request->decision_pr != 'Silencio' && $request->Id_Comunicado == "null"){
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);
                sleep(2);
            }
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
                'N_siniestro' => $request->n_siniestro,
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
                'Copia_afiliado' => $copia_afiliado,
                'Copia_empleador' => $copia_empleador,
                'Copia_eps' => $copia_eps,
                'Copia_afp' => $copia_afp,
                'Copia_arl' => $copia_arl,
                'Copia_junta_regional' => $junta_regional,
                'Copia_junta_nacional' => $junta_nacional,
                'Junta_regional_cual' => $cual,
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

            $datos_info_comunicado_eventos = [
                'Agregar_copia' => $agregar_copias_comu,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];   
                
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([                
                ['N_radicado',$radicado]
            ])->update($datos_info_comunicado_eventos);
            
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

    //Generar PDF de proformas pronunciamiento pcl Acuerdo y desacuerdo
    public function generarPdfProformaPro(Request $request) {
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        $Id_comunicado = $request->id_comunicado;
        $fecha = $request->fecha;
        $nro_radicado = $request->nro_radicado;
        $Id_Evento_pronuncia_corre = $request->Id_Evento_pronuncia_corre;
        $Asignacion_Pronuncia_corre = $request->Asignacion_Pronuncia_corre;
        $Id_Proceso_pronuncia_corre = $request->Id_Proceso_pronuncia_corre;
        $Nombre_afiliado_corre = $request->Nombre_afiliado_corre;
        $Iden_afiliado_corre = $request->Iden_afiliado_corre;
        $Firma_corre = $request->Firma_corre;
        $desicion_proforma = $request->desicion_proforma;
        $N_siniestro = $request->N_siniestro;

        $datos = $Id_Evento_pronuncia_corre;
        // Codigo QR y Logo del Header
        $codigoQR = QrCode::size(110)->margin(0.5)->generate($datos);   

        // Captura de datos para logo del cliente y informacion de las entidades

        $array_datos_info_entidad_cali = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
        ->leftJoin('sigmel_gestiones.sigmel_clientes as sc', 'sc.Id_cliente', '=', 'sie.Cliente')
        ->select('sie.ID_evento', 'sie.Cliente', 'sc.Nombre_cliente', 'sc.Nit', 'sc.Telefono_principal', 'sc.Direccion', 'sc.Email_principal')
        ->where([['sie.ID_evento',$Id_Evento_pronuncia_corre]])->get();                
        
        $Cliente = $array_datos_info_entidad_cali[0]->Cliente;        
        // Logo cliente del Header
        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->limit(1)->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
            $ruta_logo = "/logos_clientes/{$Cliente}/{$logo_header}";
        } else {
            $logo_header = "Sin logo";
            $ruta_logo = "";
        }       

        $info_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siae.Tipo_documento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'siae.Id_municipio')
        ->select('siae.Nombre_afiliado', 'siae.Nro_identificacion', 'siae.Tipo_documento', 'slp.Nombre_parametro as Tipo_documento',
        'siae.Direccion', 'siae.Telefono_contacto', 'siae.Id_departamento', 'sldm.Nombre_departamento', 'siae.Id_municipio',
        'sldm.Nombre_municipio')
        ->where([['siae.Nro_identificacion', $Iden_afiliado_corre], ['siae.Nombre_afiliado',$Nombre_afiliado_corre]])->get();
        $Direccion = $info_afiliado[0]->Direccion;
        $Telefono_contacto = $info_afiliado[0]->Telefono_contacto;
        $Nombre_departamento = $info_afiliado[0]->Nombre_departamento;
        $Nombre_municipio = $info_afiliado[0]->Nombre_municipio;
        $Tipo_documento_afi = $info_afiliado[0]->Tipo_documento;

        $info_pronunciamiento= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_pronunciamiento_eventos as pr')
        ->select('pr.ID_evento','pr.Id_primer_calificador','c.Tipo_Entidad','pr.Id_nombre_calificador','e.Nombre_entidad as Nombre_calificador'
        ,'pr.Nit_calificador','pr.Dir_calificador','pr.Email_calificador','pr.Telefono_calificador','pr.Depar_calificador','pr.Ciudad_calificador'
        ,'pr.Id_tipo_pronunciamiento','p.Nombre_parametro as Tpronuncia','pr.Id_tipo_evento','ti.Nombre_evento','pr.Id_tipo_origen','or.Nombre_parametro as T_origen'
        ,'pr.Fecha_evento','pr.Dictamen_calificador','pr.Fecha_calificador','pr.Fecha_estruturacion','pr.Porcentaje_pcl','pr.Rango_pcl'
        ,'pr.Decision','pr.Fecha_pronuncia','pr.Asunto_cali','pr.Sustenta_cali','pr.Destinatario_principal','pr.Tipo_entidad', 
        'pr.Nombre_entidad as Id_Nombre_entidad', 'en.Nombre_entidad as Nombre_entidades', 'en.Emails as Email_entidad','en.Direccion', 'en.Telefonos', 'en.Id_Ciudad', 
        'sldm.Nombre_municipio as Nombre_ciudad', 'sldm.Id_departamento', 'sldm.Nombre_departamento', 'pr.Copia_afiliado','pr.copia_empleador',
        'pr.Copia_eps' ,'pr.Copia_afp','pr.Copia_arl','pr.Copia_junta_regional','pr.Copia_junta_nacional','pr.Junta_regional_cual',
        'j.Ciudad_Junta' ,'pr.N_anexos','pr.Elaboro_pronuncia','pr.Reviso_Pronuncia','pr.Ciudad_correspon','pr.N_radicado','pr.Firmar',
        'pr.Fecha_correspondencia','pr.Archivo_pronuncia')
        ->leftJoin('sigmel_gestiones.sigmel_lista_entidades as c', 'c.Id_Entidad', '=', 'pr.Id_primer_calificador')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as e', 'e.Id_Entidad', '=', 'pr.Id_nombre_calificador')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as en', 'en.Id_Entidad', '=', 'pr.Nombre_entidad')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'en.Id_Ciudad')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as p', 'p.Id_Parametro', '=', 'pr.Id_tipo_pronunciamiento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as ti', 'ti.Id_Evento', '=', 'pr.Id_tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as or', 'or.Id_Parametro', '=', 'pr.Id_tipo_origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_regional_juntas as j', 'j.Id_juntaR', '=', 'pr.Junta_regional_cual')
        ->where([
            ['pr.ID_evento', '=', $Id_Evento_pronuncia_corre],
            ['pr.Id_Asignacion', '=', $Asignacion_Pronuncia_corre]
        ])
        ->get();
        $Ciudad_correspon = $info_pronunciamiento[0]->Ciudad_correspon;
        $Fecha_correspondencia = $info_pronunciamiento[0]->Fecha_correspondencia;
        $N_radicado = $info_pronunciamiento[0]->N_radicado;
        $Destinatario_principal = $info_pronunciamiento[0]->Destinatario_principal;
        $Decision = $info_pronunciamiento[0]->Decision;
        
        // Destinatario Principal si y Decision Acuerdo: Se saca la informacion de la entidad
        // Destinatario Principal no y Decision Acuerdo: Se saca la informacion del afiliado
        // Destinatario Principal si y Decision Desacuerdo: Se saca la informacion de la entidad.
        // Destinatario Principal no y Decision Desacuerdo: Se saca la informacion del Calificador.


        if ($Destinatario_principal == 'Si' && $Decision == 'Acuerdo') {
            $Nombre_entidades = $info_pronunciamiento[0]->Nombre_entidades;  
            $Email_enti = $info_pronunciamiento[0]->Email_entidad;     
            $Direccion_enti = $info_pronunciamiento[0]->Direccion;
            $Telefonos_enti = $info_pronunciamiento[0]->Telefonos;
            $Nombre_ciudad_enti = $info_pronunciamiento[0]->Nombre_ciudad;
            $Nombre_departamento_enti = $info_pronunciamiento[0]->Nombre_departamento;            
        } elseif($Destinatario_principal == 'No' && $Decision == 'Acuerdo') {
            // $Nombre_entidades = $Nombre_afiliado_corre;       
            // $Direccion_enti = $Direccion;
            // $Telefonos_enti = $Telefono_contacto;
            // $Nombre_ciudad_enti = $Nombre_municipio;
            // $Nombre_departamento_enti = $Nombre_departamento;

            $datos = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Departamento', '=', 'sldm1.Id_departamento')
            ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sie.Emails as Email_entidad', 'sldm.Nombre_municipio as Nombre_ciudad', 'sldm1.Nombre_departamento')
            ->where([['siae.ID_evento','=', $Id_Evento_pronuncia_corre]])
            ->get();

            $array_datos = json_decode(json_encode($datos), true);
            if (count($array_datos) > 0) {
                $Nombre_entidades = $array_datos[0]["Nombre_entidad"];
                $Email_enti = $array_datos[0]["Email_entidad"];      
                $Direccion_enti = $array_datos[0]["Direccion"];
                $Telefonos_enti = $array_datos[0]["Telefonos"];
                $Nombre_ciudad_enti = $array_datos[0]["Nombre_ciudad"];
                $Nombre_departamento_enti = $array_datos[0]["Nombre_departamento"];
            }else{
                $Nombre_entidades = "";       
                $Direccion_enti = "";
                $Email_enti = "";
                $Telefonos_enti = "";
                $Nombre_ciudad_enti = "";
                $Nombre_departamento_enti = "";
            }

        }elseif($Destinatario_principal == 'Si' && $Decision == 'Desacuerdo') {
            $Email_calificador = $info_pronunciamiento[0]->Email_calificador;
            $Entidad_calificador = $info_pronunciamiento[0]->Nombre_entidades;       
            $Dir_calificador = $info_pronunciamiento[0]->Direccion;
            $Telefono_calificador = $info_pronunciamiento[0]->Telefonos;
            $Ciudad_calificador = $info_pronunciamiento[0]->Nombre_ciudad;
            $Nombre_departamento_enti = $info_pronunciamiento[0]->Nombre_departamento;  
        }elseif($Destinatario_principal == 'No' && $Decision == 'Desacuerdo') {
            $Email_calificador = $info_pronunciamiento[0]->Email_calificador;
            $Entidad_calificador = $info_pronunciamiento[0]->Nombre_calificador;
            $Dir_calificador = $info_pronunciamiento[0]->Dir_calificador;
            $Telefono_calificador = $info_pronunciamiento[0]->Telefono_calificador;
            $Ciudad_calificador = $info_pronunciamiento[0]->Ciudad_calificador; 
        }
        

        $Asunto_cali = $info_pronunciamiento[0]->Asunto_cali;
        $Nombre_calificador = $info_pronunciamiento[0]->Nombre_calificador;        
        $Fecha_calificador = $info_pronunciamiento[0]->Fecha_calificador;
        $T_origen = $info_pronunciamiento[0]->T_origen;
        $Porcentaje_pcl = $info_pronunciamiento[0]->Porcentaje_pcl;
        $Fecha_estruturacion = $info_pronunciamiento[0]->Fecha_estruturacion;
        // $Sustenta_cali = $info_pronunciamiento[0]->Sustenta_cali;
        $Sustenta_cali = $request->Sustenta_cali;
        $N_anexos = $info_pronunciamiento[0]->N_anexos;
        $Elaboro_pronuncia = $info_pronunciamiento[0]->Elaboro_pronuncia;
                
        // Captura de info para los CIE10
        $array_diagnosticosPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro as Nombre_origen')
        ->where([['ID_Evento',$Id_Evento_pronuncia_corre], ['Id_Asignacion',$Asignacion_Pronuncia_corre], ['side.Estado_Recalificacion', 'Activo']])->get(); 
        
        if(count($array_diagnosticosPcl) > 0){
            // Obtener el array de nombres CIE10 y codigo cie10
            $NombresCIE10 = $array_diagnosticosPcl->map(function ($item) {
                return '('.$item->Codigo_cie10.')('.$item->Nombre_CIE10.')('.$item->Nombre_origen.')';
            })->toArray();
                
            // Obtener el número de elementos en el array
            $num_elementos = count($NombresCIE10);
            // Si hay más de un elemento en el array
            if ($num_elementos > 1) {
                // Separar el último elemento del resto
                $ultimo_elemento = array_pop($NombresCIE10);
                $resto_elementos = implode(', ', $NombresCIE10);

                // Concatenar los elementos con "y"
                $CIE10Nombres = $resto_elementos . ' y ' . $ultimo_elemento;
            } else {
                // Si solo hay un elemento, no es necesario cambiar nada
                $CIE10Nombres = reset($NombresCIE10);
            }
        }else{
            $CIE10Nombres = '';
        }
                
        // Validamos si los checkbox esta marcados
        $final_copia_afiliado = isset($request->copia_afiliado) ? 'Afiliado' : '';
        $final_copia_empleador = isset($request->copia_empleador) ? 'Empleador' : '';
        $final_copia_eps = isset($request->copia_eps) ? 'EPS' : '';
        $final_copia_afp = isset($request->copia_afp) ? 'AFP' : '';
        $final_copia_arl = isset($request->copia_arl) ? 'ARL' : '';

        $total_copias = array_filter(array(
            'copia_afiliado' => $final_copia_afiliado,
            'copia_empleador' => $final_copia_empleador,
            'copia_eps' => $final_copia_eps,
            'copia_afp' => $final_copia_afp,
            'copia_arl' => $final_copia_arl,
        )); 

        
        sleep(2);
        
        // Conversión de las key en variables con sus respectivos datos
        extract($total_copias);
        $Agregar_copias = [];
        if (isset($copia_afiliado)) {

            $AfiliadoData = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('siae.Nombre_afiliado', 'siae.Direccion', 'siae.Telefono_contacto', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'siae.Email')
            ->where([['siae.Nro_identificacion', $Iden_afiliado_corre],['siae.ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();
            $nombreAfiliado = $AfiliadoData[0]->Nombre_afiliado;
            $direccionAfiliado = $AfiliadoData[0]->Direccion;
            $telefonoAfiliado = $AfiliadoData[0]->Telefono_contacto;
            $ciudadAfiliado = $AfiliadoData[0]->Nombre_ciudad;
            $municipioAfiliado = $AfiliadoData[0]->Nombre_municipio;
            $emailAfiliado = $AfiliadoData[0]->Email;            
            $Agregar_copias['Afiliado'] = $nombreAfiliado."; ".$direccionAfiliado."; ".$emailAfiliado."; ".$telefonoAfiliado."; ".$ciudadAfiliado."; ".$municipioAfiliado."."; 
        }

        if(isset($copia_empleador)){

            $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sile.Email','sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['sile.Nro_identificacion', $Iden_afiliado_corre],['sile.ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();

            
            if (preg_match("/&/", $datos_empleador[0]->Empresa)) {
                $nombre_empleador = htmlspecialchars(preg_replace('/&/', '&amp;', $datos_empleador[0]->Empresa));
            } else {
                $nombre_empleador = $datos_empleador[0]->Empresa;
            }
            
            $direccion_empleador = $datos_empleador[0]->Direccion;
            $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
            $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
            $email_empleador = $datos_empleador[0]->Email;
            $municipio_empleador = $datos_empleador[0]->Nombre_municipio;

            $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$email_empleador."; ".$telefono_empleador."; ".$ciudad_empleador."; ".$municipio_empleador.".";   
        }

        if (isset($copia_eps)) {
            $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Emails as Email','sie.Otros_Telefonos', 
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $Iden_afiliado_corre],['ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();

            $nombre_eps = $datos_eps[0]->Nombre_eps;
            $direccion_eps = $datos_eps[0]->Direccion;
            $email_eps = $datos_eps[0]->Email;
            if ($datos_eps[0]->Otros_Telefonos != "") {
                $telefonos_eps = $datos_eps[0]->Telefonos.",".$datos_eps[0]->Otros_Telefonos;
            } else {
                $telefonos_eps = $datos_eps[0]->Telefonos;
            }
            $ciudad_eps = $datos_eps[0]->Nombre_ciudad;
            $minucipio_eps = $datos_eps[0]->Nombre_municipio;

            $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;
        }

        if (isset($copia_afp)) {
            $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Emails as Email','sie.Otros_Telefonos',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $Iden_afiliado_corre],['ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();

            $nombre_afp = $datos_afp[0]->Nombre_afp;
            $direccion_afp = $datos_afp[0]->Direccion;
            $email_afp = $datos_afp[0]->Email;
            if ($datos_afp[0]->Otros_Telefonos != "") {
                $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
            } else {
                $telefonos_afp = $datos_afp[0]->Telefonos;
            }
            $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
            $minucipio_afp = $datos_afp[0]->Nombre_municipio;

            $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$email_afp."; ".$telefonos_afp."; ".$ciudad_afp."; ".$minucipio_afp;
        }

        if(isset($copia_arl)){
            $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Emails as Email', 'sie.Otros_Telefonos',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $Iden_afiliado_corre],['ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();

            $nombre_arl = $datos_arl[0]->Nombre_arl;
            $direccion_arl = $datos_arl[0]->Direccion;
            $email_arl = $datos_arl[0]->Email;
            if ($datos_arl[0]->Otros_Telefonos != "") {
                $telefonos_arl = $datos_arl[0]->Telefonos.",".$datos_arl[0]->Otros_Telefonos;
            } else {
                $telefonos_arl = $datos_arl[0]->Telefonos;
            }
            
            $ciudad_arl = $datos_arl[0]->Nombre_ciudad;
            $minucipio_arl = $datos_arl[0]->Nombre_municipio;

            $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$email_arl."; ".$telefonos_arl."; ".$ciudad_arl."; ".$minucipio_arl;
        }        
        // validamos la firma esta marcado para la Captura de la firma del cliente           
        if ($Firma_corre == 'firmar') {            
            $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente', 'Nombre_cliente')
            ->where('Id_cliente', $Cliente)->get();
    
            $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
            ->where('Id_cliente', $idcliente[0]->Id_cliente)->get();

            if(count($firmaclientecompleta) > 0){
                $Firma_cliente = $firmaclientecompleta[0]->Firma;
            }else{
                $Firma_cliente = 'No firma';
            }
            
        }else{
            $Firma_cliente = 'No firma';
        }

        //Footer
        $dato_logo_footer = sigmel_clientes::on('sigmel_gestiones')
        ->select('Footer_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->limit(1)->get();
        if (count($dato_logo_footer) > 0 && $dato_logo_footer[0]->Footer_cliente != null) {
            $footer = $dato_logo_footer[0]->Footer_cliente;
            $ruta_logo_footer = "/footer_clientes/{$Cliente}/{$footer}";
        } else {
            $footer = null;
            $ruta_logo_footer = null;
        }
        //  Extraemos los datos del footer 
        // $datos_footer = sigmel_clientes::on('sigmel_gestiones')
        // ->select('footer_dato_1', 'footer_dato_2', 'footer_dato_3', 'footer_dato_4', 'footer_dato_5')
        // ->where('Id_cliente',  $Cliente)->get();

        // if(count($datos_footer) > 0){
        //     $footer_dato_1 = $datos_footer[0]->footer_dato_1;
        //     $footer_dato_2 = $datos_footer[0]->footer_dato_2;
        //     $footer_dato_3 = $datos_footer[0]->footer_dato_3;
        //     $footer_dato_4 = $datos_footer[0]->footer_dato_4;
        //     $footer_dato_5 = $datos_footer[0]->footer_dato_5;

        // }else{
        //     $footer_dato_1 = "";
        //     $footer_dato_2 = "";
        //     $footer_dato_3 = "";
        //     $footer_dato_4 = "";
        //     $footer_dato_5 = "";
        // }

        if ($desicion_proforma == 'proforma_acuerdo') {
            $data = [
                'codigoQR' => $codigoQR,
                'logo_header' => $logo_header,
                'id_cliente' => $Cliente,
                'ID_evento' => $Id_Evento_pronuncia_corre,
                'Id_Asignacion' => $Asignacion_Pronuncia_corre,
                'Id_proceso' => $Id_Proceso_pronuncia_corre,
                'Nombre_afiliado_corre' => $Nombre_afiliado_corre,
                'Tipo_documento_afi' => $Tipo_documento_afi,
                'Iden_afiliado_corre' => $Iden_afiliado_corre,
                'Ciudad_correspon' => $Ciudad_correspon,
                'Fecha_correspondencia' => $Fecha_correspondencia,
                'N_radicado' => $N_radicado,
                'Nombre_afiliado_corre' => $Nombre_afiliado_corre,
                'Tipo_documento_afi' => $Tipo_documento_afi,
                'Iden_afiliado_corre' => $Iden_afiliado_corre,
                'Nombre_entidades' => $Nombre_entidades,
                'Direccion_enti' => $Direccion_enti,
                'Telefonos_enti' => $Telefonos_enti,
                'Email_enti' => $Email_enti,
                'Nombre_ciudad_enti' => $Nombre_ciudad_enti,
                'Nombre_departamento_enti' => $Nombre_departamento_enti,
                'Asunto_cali' => $Asunto_cali,
                'Nombre_calificador' => $Nombre_calificador,
                'Fecha_calificador' => $Fecha_calificador,
                'Porcentaje_pcl' => $Porcentaje_pcl,
                'Fecha_estruturacion' => $Fecha_estruturacion,
                'Sustenta_cali' => $Sustenta_cali,
                'Firma_cliente' => $Firma_cliente,
                'N_anexos' => $N_anexos,
                'Elaboro_pronuncia' => $Elaboro_pronuncia,            
                'Agregar_copia' => $Agregar_copias,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
    
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_pro_acuerdo', $data);            
            $nombre_pdf = "PCL_ACUERDO_{$Asignacion_Pronuncia_corre}_{$Iden_afiliado_corre}.pdf";
            //Obtener el contenido del PDF
            $output = $pdf->output();
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$Id_Evento_pronuncia_corre}/{$nombre_pdf}"), $output);
            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_pdf
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
            ->update($actualizar_nombre_documento);
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Asignacion_Pronuncia_corre],
                ['siae.ID_evento', $Id_Evento_pronuncia_corre],
                ['siae.Id_proceso', $Id_Proceso_pronuncia_corre],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_pdf],
            ])->get();
            
            if(count($verficar_documento) == 0){

                // Se valida si antes de insertar la info del doc de acuerdo ya hay un doc de desacuerdo
                $nombre_docu_desacuerdo = "PCL_DESACUERDO_{$Asignacion_Pronuncia_corre}_{$Iden_afiliado_corre}.docx";
                $verificar_docu_desacuerdo = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->where([
                    ['Nombre_documento', $nombre_docu_desacuerdo],
                ])->get();

                // Si no existe info del documento de desacuerdo, inserta la info del documento de acuerdo
                // De lo contrario hace una actualización de la info
                if (count($verificar_docu_desacuerdo) == 0) {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Asignacion_Pronuncia_corre,
                        'Id_proceso' => $Id_Proceso_pronuncia_corre,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $Id_Evento_pronuncia_corre,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $nro_radicado,
                        'F_elaboracion_correspondencia' => $fecha,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                }else{
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Asignacion_Pronuncia_corre,
                        'Id_proceso' => $Id_Proceso_pronuncia_corre,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $Id_Evento_pronuncia_corre,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $nro_radicado,
                        'F_elaboracion_correspondencia' => $fecha,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    ->where([
                        ['Id_Asignacion', $Asignacion_Pronuncia_corre],
                        ['N_radicado_documento', $nro_radicado],
                        ['ID_evento', $Id_Evento_pronuncia_corre]
                    ])
                    ->update($info_descarga_documento);
                }

            }

            return $pdf->download($nombre_pdf);   
        } 
        else {

            // $fecha_radicado_alfa = "N/A";

            $phpWord = new PhpWord();
            // Configuramos la fuente y el tamaño de letra para todo el documento
            $phpWord->setDefaultFontName('Arial');
            $phpWord->setDefaultFontSize(12);
            // Configuramos la alineación justificada para todo el documento
            $phpWord->setDefaultParagraphStyle(
                array('align' => 'both', 'spaceAfter' => 0, 'spaceBefore' => 0)
            );
            // Configurar el idioma del documento a español
            $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('es-ES'));

            // Configuramos las margenes del documento (estrechas)
            $section = $phpWord->addSection();
            $section->setMarginLeft(0.5 * 72);
            $section->setMarginRight(0.5 * 72);
            $section->setMarginTop(0.5 * 72);
            $section->setMarginBottom(0.5 * 72);

            // $ruta_vigilado = "/images/logos_preformas/vigilado.png";
            // $imagenPath_vigilado = public_path($ruta_vigilado);

            // $imageStyle = array(
            //     'height' => 150,
            //     'positioning' => Image::POSITION_ABSOLUTE,
            //     // 'marginLeft' => -50,  // Ajusta este valor según tus necesidades
            //     'marginRight' => -500,
            //     'marginTop' => 600,
            //     // 'wrappingStyle' => 'behind', 
            // );


            // Creación de Header
            $header = $section->addHeader();
            $imagenPath_header = public_path($ruta_logo);
            $header->addImage($imagenPath_header, array('width' => 150, 'align' => 'right'));
            $test = $header->addTextRun(['alignment' => 'right']);
            $test->addText('Página ');
            $test->addField('PAGE');
            $test->addText(' de ');
            $test->addField('NUMPAGES');
            $header->addTextBreak();
                      
            // Creación de Contenido
            $section->addText($Ciudad_correspon.' '.$Fecha_correspondencia, array('bold' => true));
            $section->addTextBreak();

            $table = $section->addTable();

    
            $table->addRow();


            $cell1 = $table->addCell(6000);


            $textRun1 = $cell1->addTextRun(array('alignment'=>'left'));
            $textRun1->addText('Señores: ',array('bold' => true));
            $textRun1->addTextBreak();
            $textRun1->addText($Email_calificador);
            $textRun1->addTextBreak();
            $textRun1->addText($Entidad_calificador);
            $textRun1->addTextBreak();
            $textRun1->addText($Dir_calificador);
            $textRun1->addTextBreak();
            $textRun1->addText($Telefono_calificador);
            $textRun1->addTextBreak();
            $textRun1->addText($Ciudad_calificador);

            $cell2 = $table->addCell(4000);

            $nestedTable = $cell2->addTable(array('borderSize' => 12, 'borderColor' => '000000', 'width' => 80 * 60, 'alignment'=>'right'));
            $nestedTable->addRow();
            $nestedCell = $nestedTable->addCell();
            $nestedTextRun = $nestedCell->addTextRun(array('alignment'=>'left'));
            $nestedTextRun->addText('Nro. Radicado: ', array('bold' => true));
            $nestedTextRun->addTextBreak();
            $nestedTextRun->addText($nro_radicado, array('bold' => true));
            $nestedTextRun->addTextBreak();
            $nestedTextRun->addText($Tipo_documento_afi . ' ' . $Iden_afiliado_corre, array('bold' => true));
            $nestedTextRun->addTextBreak();
            $nestedTextRun->addText('Siniestro: ' . $N_siniestro, array('bold' => true));
            
            $section->addTextBreak();
            $section->addTextBreak();

            $table = $section->addTable(array('alignment'=>'center'));

    
            $table->addRow();


            $cell1 = $table->addCell(8000);

            $asuntoyafiliado = $cell1->addTextRun(array('alignment'=>'left'));
            $asuntoyafiliado->addText('Asunto: ', array('bold' => true));
            $asuntoyafiliado->addText($Asunto_cali, array('bold' => true));
            $asuntoyafiliado->addTextBreak();
            $asuntoyafiliado->addText('Paciente: ', array('bold' => true));
            $asuntoyafiliado->addText($Nombre_afiliado_corre." ".$Tipo_documento_afi." ".$Iden_afiliado_corre);
            $asuntoyafiliado->addTextBreak();
            $asuntoyafiliado->addText('Ramo: ', array('bold' => true));
            $asuntoyafiliado->addText('Previsionales');
            $asuntoyafiliado->addTextBreak();
            $asuntoyafiliado->addText('Siniestro: ', array('bold' => true));
            $asuntoyafiliado->addText($N_siniestro);
            $section->addTextBreak();

            // $section->addText('Asunto: '.$Asunto_cali, array('bold' => true));
            // $section->addText('PACIENTE: '.$Nombre_afiliado_corre." ".$Tipo_documento_afi." ".$Iden_afiliado_corre, array('bold' => false));
            // $section->addText('Ramo: Previsionales', array('bold' => false));
            // $section->addText('Siniestro '.$Id_Evento_pronuncia_corre, array('bold' => true));

            // Configuramos el reemplazo de la variable de los cie 10
            $Sustenta_cali = str_replace(['<br>', '<br/>', '<br />', '</br>'], '', $Sustenta_cali);
            $patron1 = '/\{\{\$Nombre_afiliado\}\}/';
            $patron2 = '/\{\{\$CIE10_Nombres_Origen\}\}/';
            // $patronx = '/\{\{\$OrigenPcl\}\}/';
            $patron3 = '/\{\{\$PorcentajePcl\}\}/';
            $patron4 = '/\{\{\$F_estructuracionPcl\}\}/';
            if (preg_match($patron1, $Sustenta_cali) && preg_match($patron2, $Sustenta_cali) &&
            preg_match($patron3, $Sustenta_cali) && preg_match($patron4, $Sustenta_cali)) {
                $texto_modificado = str_replace('{{$Nombre_afiliado}}', '<b>'.$Nombre_afiliado_corre.'</b>', $Sustenta_cali);
                $texto_modificado = str_replace('{{$CIE10_Nombres_Origen}}', '<b>'.$CIE10Nombres.'</b>', $texto_modificado);
                // $texto_modificado = str_replace('{{$OrigenPcl}}', $T_origen , $texto_modificado);
                $texto_modificado = str_replace('{{$PorcentajePcl}}', '<b>'.$Porcentaje_pcl.'</b>', $texto_modificado);
                $texto_modificado = str_replace('{{$F_estructuracionPcl}}', '<b>'.$Fecha_estruturacion.'</b>', $texto_modificado);
                $texto_modificado = str_replace('</p>', '</p><br></br>', $texto_modificado);
                $texto_modificado = str_replace('<p><br>', ' ', $texto_modificado);
                $cuerpo = $texto_modificado;
            } else {
                $cuerpo = "";
            }

            $section->addTextBreak();
            Html::addHtml($section, $cuerpo, false, true);
            $section->addTextBreak();
            $section->addText('Cordialmente,');
            $section->addTextBreak();

            if ($Firma_cliente != "No firma") {
                # code...
                // Agregar </img> en la imagen de la firma
                $patronetiqueta = '/<img(.*?)>/';
                $Firma_cliente = preg_replace($patronetiqueta, '<img$1></img>', $Firma_cliente);
                $Firma_cliente = str_replace(['<br>', '<br/>', '<br />', '</br>'], '', $Firma_cliente);
                
                // Quitamos el style y agregamos los atributos width y height
                $patronstyle = '/<img[^>]+style="width:\s*([\d.]+)px;\s*height:\s*([\d.]+)px[^"]*"[^>]*>/';
                preg_match($patronstyle, $Firma_cliente, $coincidencias);
                $width = count($coincidencias)>0 ? $coincidencias[1] : '100px'; // Valor de width
                $height = count($coincidencias)>0 ? $coincidencias[2] : '70px'; // Valor de height
            
                $nuevoStyle = 'width="'.$width.'" height="'.$height.'"';
                $htmlModificado = reemplazarStyleImg($Firma_cliente, $nuevoStyle);
                
                Html::addHtml($section, $htmlModificado, false, true);
            }else{
                // $section->addText($Firma_cliente);
            }
            $section->addTextBreak();
            $section->addText('HUGO IGNACIO GÓMEZ DAZA', array('bold' => true));
            $section->addText('Representante Legal para Asuntos de Seguridad Social', array('bold' => true));
            $section->addText('Convenio Codess - Seguros de Vida Alfa S.A', array('bold' => true));
            $section->addTextBreak();
            // $section->addText('Elaboró: '.Auth::user()->name, array('bold' => true));
            // $section->addTextBreak();
            // Configuramos la tabla de copias a partes interesadas
            $htmltabla2 = '<table style="text-align: justify; width:100%; border-collapse: collapse; margin-left: auto; margin-right: auto;">';
            if (count($Agregar_copias) == 0) {
                $htmltabla2 .= '
                    <tr>
                        <td style="border: 1px solid #000; padding: 5px;"><span style="font-weight:bold;">Copia: </span>No se registran copias</td>                                                                                
                    </tr>';
            } else {
                $htmltabla2 .= '
                    <tr>
                        <td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Copia:</span></td>                            
                    </tr>';

                $Afiliado = 'Afiliado';
                $Empleador = 'Empleador';
                $EPS = 'EPS';
                $AFP = 'AFP';
                $ARL = 'ARL';

                if (isset($Agregar_copias[$Afiliado])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Afiliado: </span>' . $Agregar_copias['Afiliado'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$Empleador])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Empleador: </span>' . $Agregar_copias['Empleador'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$EPS])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">EPS: </span>' . $Agregar_copias['EPS'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$AFP])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">AFP: </span>' . $Agregar_copias['AFP'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$ARL])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">ARL: </span>' . $Agregar_copias['ARL'] . '</td></tr>';
                }
            }

            $htmltabla2 .= '</table>';
            Html::addHtml($section, $htmltabla2, false, true);
            
            // Configuramos el footer
            $footer = $section->addFooter();
            $footer-> addText($Nombre_afiliado_corre." - ".$Tipo_documento_afi." "."$Iden_afiliado_corre".' - Siniestro '."$N_siniestro", array('size' => 10, 'bold' => true), array('align' => 'center'));
            if($ruta_logo_footer != null){
                $imagenPath_footer = public_path($ruta_logo_footer);
                $footer->addImage($imagenPath_footer, array('width' => 450, 'height' => 70, 'alignment' => 'left'));
            }
            $table = $footer->addTable('myTable');


            // Generamos el documento y luego se guarda
            $writer = new Word2007($phpWord);
            $nombre_docx = "PCL_DESACUERDO_{$Asignacion_Pronuncia_corre}_{$Iden_afiliado_corre}.docx";
            $writer->save(public_path("Documentos_Eventos/{$Id_Evento_pronuncia_corre}/{$nombre_docx}"));
            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_docx
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
            ->update($actualizar_nombre_documento);
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Asignacion_Pronuncia_corre],
                ['siae.ID_evento', $Id_Evento_pronuncia_corre],
                ['siae.Id_proceso', $Id_Proceso_pronuncia_corre],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_docx],
            ])->get();
            
            if(count($verficar_documento) == 0){

                // Se valida si antes de insertar la info del doc de desacuerdo ya hay un doc de acuerdo
                $nombre_docu_acuerdo = "PCL_ACUERDO_{$Asignacion_Pronuncia_corre}_{$Iden_afiliado_corre}.pdf";
                $verificar_docu_acuerdo = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->where([
                    ['Nombre_documento', $nombre_docu_acuerdo],
                ])->get();

                // Si no existe info del documento de acuerdo, inserta la info del documento de desacuerdo
                // De lo contrario hace una actualización de la info
                if (count($verificar_docu_acuerdo) == 0) {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Asignacion_Pronuncia_corre,
                        'Id_proceso' => $Id_Proceso_pronuncia_corre,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $Id_Evento_pronuncia_corre,
                        'Nombre_documento' => $nombre_docx,
                        'N_radicado_documento' => $nro_radicado,
                        'F_elaboracion_correspondencia' => $fecha,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                }else{

                    $info_descarga_documento = [
                        'Id_Asignacion' => $Asignacion_Pronuncia_corre,
                        'Id_proceso' => $Id_Proceso_pronuncia_corre,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $Id_Evento_pronuncia_corre,
                        'Nombre_documento' => $nombre_docx,
                        'N_radicado_documento' => $nro_radicado,
                        'F_elaboracion_correspondencia' => $fecha,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    ->where([
                        ['Id_Asignacion', $Asignacion_Pronuncia_corre],
                        ['N_radicado_documento', $nro_radicado],
                        ['ID_evento', $Id_Evento_pronuncia_corre]
                    ])
                    ->update($info_descarga_documento);
                }

            }

            return response()->download(public_path("Documentos_Eventos/{$Id_Evento_pronuncia_corre}/{$nombre_docx}"));
        }  
        
    }

}
function reemplazarStyleImg($html, $nuevoStyle)
{
    // Utilizar expresiones regulares para encontrar y reemplazar el atributo style
    $patron = '/<img([^>]*)style="[^"]*"[^>]*>/';
    $htmlModificado = preg_replace_callback($patron, function ($coincidencia) use ($nuevoStyle) {
        $imgTag = $coincidencia[0];
        $imgTagModificado = preg_replace('/style="[^"]*"/', $nuevoStyle, $imgTag);
        return $imgTagModificado;
    }, $html);

    return $htmlModificado;
}