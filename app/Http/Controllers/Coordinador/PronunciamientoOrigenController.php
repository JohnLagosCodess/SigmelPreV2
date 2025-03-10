<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PDF;

use App\Models\sigmel_lista_entidades;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_regional_juntas;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_pronunciamiento_eventos;
use App\Models\sigmel_auditorias_pronunciamiento_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;

use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_clientes;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_registro_descarga_documentos;
use App\Models\sigmel_lista_departamentos_municipios;
use App\Services\GlobalService;
use App\Traits\GenerarRadicados;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Image;
use Html2Text\Html2Text;

class PronunciamientoOrigenController extends Controller
{
    use GenerarRadicados;

    protected $globalService;

    public function __construct(GlobalService $globalService)
    {
        $this->globalService = $globalService;
    }

    // TODO LO REFERENTE SERVICIO PRONUNCIAMIENTO
    public function mostrarVistaPronunciamientoOrigen(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $date=date("Y-m-d");
        $Id_evento_calitec=$request->Id_evento_calitec;
        $Id_asignacion_calitec = $request->Id_asignacion_calitec;
        $array_datos_pronunciamientoOrigen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion_calitec));

        //Traer info informacion pronunciamiento de la tabla sigmel_informacion_pronunciamiento_eventos dependiendo del id evento y id asignacion
        $info_pronuncia= $this->globalService->retornarInformacionPronunciamiento($Id_evento_calitec,$Id_asignacion_calitec);

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
        $consecutivo = $this->getRadicado('origen',$Id_evento_calitec);

        $array_comunicados = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$Id_evento_calitec], ['Id_Asignacion',$Id_asignacion_calitec],['Modulo_creacion','pronunciamientoOrigen']])->get();  
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
            if($comunicado["Id_Comunicado"]){
                $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_calitec,$Id_asignacion_calitec,$comunicado["Id_Comunicado"]);
            }

        }

        // Consultamos si el caso está en la bandeja de Notificaciones
        $array_caso_notificado = BandejaNotifiController::evento_en_notificaciones($Id_evento_calitec,$Id_asignacion_calitec);

        if(count($array_caso_notificado) > 0){
            $caso_notificado = $array_caso_notificado[0]->Notificacion;
        }

        //Traer el N_siniestro del evento
        $N_siniestro_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('N_siniestro')
        ->where([['ID_evento',$Id_evento_calitec]])
        ->get();

        $Id_servicio = 3;
        $Id_Asignacion = $Id_asignacion_calitec;
        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?,?,?)',array($Id_evento_calitec,$Id_servicio,$Id_asignacion_calitec));

        $entidades_conocimiento = $this->globalService->getAFPConocimientosParaCorrespondencia($Id_evento_calitec,$Id_asignacion_calitec);

        /* Traer datos de la AFP de Conocimiento */
        $info_afp_conocimiento = $this->globalService->retornarcuentaConAfpConocimiento($Id_evento_calitec);

        return view('coordinador.pronunciamientoOrigenATEL', compact('user','array_datos_pronunciamientoOrigen','info_pronuncia','array_datos_diagnostico_motcalifi','consecutivo',
        'array_comunicados', 'caso_notificado','N_siniestro_evento', 'arraylistado_documentos', 'Id_servicio', 'Id_Asignacion','entidades_conocimiento', 'info_afp_conocimiento'));
    }

    //Cargar Selectores pronunciamiento
    public function cargueListadoSelectoresPronunciamientoOrigen(Request $request){
    
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
                    ['Tipo_lista', '=', 'Tipo pronunciamiento Origen'],
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
            // $datos_tipo_junta = sigmel_lista_regional_juntas::on('sigmel_gestiones')
            //     ->select('Id_juntaR','Ciudad_Junta')
            //     ->where([
            //         ['Estado', '=', 'activo'],
            //     ])
            //     ->get();

            $datos_tipo_junta = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
            ->select('sie.Id_Entidad as Id_juntaR','sie.Nombre_entidad as Ciudad_Junta')
            ->where([
                ['sie.IdTipo_entidad', 4]
            ])->get();

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

        if ($parametro == "docs_complementarios") {

            $datos_tipos_documentos_familia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_lista_documentos as sld')
            ->leftJoin('sigmel_gestiones.sigmel_registro_documentos_eventos as srde', 'sld.Id_Documento', '=', 'srde.Id_Documento')
            ->select('sld.Nro_documento', 'sld.Nombre_documento')
            ->where([
                ['srde.ID_evento', $request->evento],
                ['srde.Id_servicio', $request->servicio],
                ['srde.Id_Documento', $request->tipo_correspondencia],
                ['sld.Estado', 'activo']
            ])
            // ->whereIn('srde.Id_Documento', [19, 20, 21, 22, 23])
            ->groupBy('sld.Nro_documento')
            ->get();

            $info_datos_tipos_documentos_familia = json_decode(json_encode($datos_tipos_documentos_familia, true));
            return response()->json($info_datos_tipos_documentos_familia);
        }
    }

    //Guardar o actualizar informacion pronunciamiento
    public function guardarInfoServiPronunciaOrigen(Request $request){
    
        if(!Auth::check()){
            return redirect('/');
        }
        $datetime = date("Y-m-d H:i:s");
        $date=date("Y-m-d");
        $nombre_usuario = Auth::user()->name;
        $Id_EventoPronuncia = $request->Id_EventoPronuncia;
        $Id_ProcesoPronuncia = $request->Id_ProcesoPronuncia;
        $Id_Asignacion_Pronuncia = $request->Id_Asignacion_Pronuncia;
        // Captura del array de los datos de la tabla
        
        if(isset($request->otro_calificador)){
            $info_ciudad = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
            ->select('Id_municipios','Id_departamento')
            ->where('Nombre_municipio', 'like', "%{$request->ciudad_calificador}%")
            ->orWhere('Nombre_departamento', 'like', "%{$request->depar_calificador}%")
            ->first();

            if(empty($info_ciudad)){
                $consecutivo_dep = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_departamento')->max('Id_departamento');
                $consecutivo_dep += 1;
                
                $info_ciudad = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->insertGetId([
                    "Nombre_departamento" => $request->depar_calificador,
                    "Nombre_municipio" => $request->ciudad_calificador, 
                    "Id_departamento" =>  $consecutivo_dep,
                    "F_registro" => date('Y-m-d'),
                    "Estado" => "activo"
                ]);
            }

            $info_entidad = [
                "Direccion" => $request->dir_calificador,
                "Dirigido" => $request->otro_calificador,
                "Nombre_entidad" => $request->otro_calificador, 
                "Emails" => $request->mail_calificador,
                "Id_Departamento" => $info_ciudad->Id_departamento ?? $consecutivo_dep ,
                "Id_Ciudad" => $info_ciudad->Id_municipios ?? $info_ciudad,
                "Sucursal" => $request->ciudad_calificador,
                "Nit_entidad" => $request->nit_calificador,
                "Telefonos" => $request->telefono_calificador,
                'IdTipo_entidad' => 6,
                "Estado_entidad" => 'activo'
            ];

            $tmp_entidad = sigmel_informacion_entidades::on('sigmel_gestiones')->updateOrCreate(['Nombre_entidad' => $request->otro_calificador],$info_entidad);
            @$request->nombre_calificador = $tmp_entidad->Id_Entidad;

        }

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
            'CIE10','Nombre_CIE10','Lateralidad_CIE10','Origen_CIE10',
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
            $tipo_archivo = "Documento Pronunciamiento Origen";
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

        if (empty($request->copia_afiliado)) {
            $copia_afiliado = null;            
        } else {
            $copia_afiliado = $request->copia_afiliado;            
        }
        if (empty($request->copia_empleador)) {
            $copia_empleador = null;                        
        } else {
            $copia_empleador = $request->copia_empleador;            
        }
        if (empty($request->copia_eps)) {
            $copia_eps = null;                        
        } else {         
            $copia_eps = $request->copia_eps;
        }
        if (empty($request->copia_afp)) {
            $copia_afp = null;                        
        } else {         
            $copia_afp = $request->copia_afp;
        }
        if (empty($request->copia_afp)) {
            $copia_afp = null;                        
        } else {         
            $copia_afp = $request->copia_afp;
        }
        if (empty($request->copia_arl)) {
            $copia_arl = null;                   
        } else {         
            $copia_arl = $request->copia_arl;
        }
        // dd($request->copia_afp_conocimiento, $request->copia_arl);
        if (empty($request->junta_regional)) {
            $junta_regional = null;                        
        } else {         
            $junta_regional = $request->junta_regional;
        }
        if (empty($junta_regional)) {
            $cual =  null;
        } else {
            $cual =  $request->junta_regional_cual;
        }        
        if (empty($request->junta_nacional)) {
            $junta_nacional = null;                        
        } else {         
            $junta_nacional = $request->junta_nacional;
        }
        if (empty($request->copia_afp_conocimiento)) {
            $copia_afp_conocimiento = null;                        
            $copy_afp_conocimiento = null;
        } else {      
            // traemos la informacion de las copias dependiendo de cuantas entidades de conocimiento hay
            $copy_afp_conocimiento = 'AFP_Conocimiento';
            $str_entidades = $this->globalService->retornarStringCopiasEntidadConocimiento($Id_EventoPronuncia);
            $copia_afp_conocimiento = $str_entidades;
        }

        // Agrupa las variables en un array
        $variables = array($copia_afiliado, $copia_empleador, $copia_eps, $copia_afp, $copia_arl, $copia_afp_conocimiento, $junta_regional, $junta_nacional);

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

        $radicado = $this->disponible($request->n_radicado,$Id_EventoPronuncia)->getRadicado('origen',$Id_EventoPronuncia);

        /* Se completan los siguientes datos para lo del tema del pbs 014 */

        // el nombre del destinatario principal dependerá del la selección del primer calificador
        $id_primer_calificador = $request->primer_calificador;

        // Caso 1: Arl, Caso 2: Afp, Caso 3: Eps
        switch ($id_primer_calificador) {
            case '1':
                $Destinatario = 'Arl';
            break;

            case '2':
                $Destinatario = 'Afp';
            break;

            case '3':
                $Destinatario = 'Eps';
            break;
            
            default:
                $Destinatario = 'N/A';
            break;
        }
        if(!$nombre_entidad){
            $id_dest_principal = $request->nombre_calificador;
        }
        else{
            switch ($request->tipo_entidad) {
                case '1':
                    $Destinatario = 'Arl';
                break;
    
                case '2':
                    $Destinatario = 'Afp';
                break;
    
                case '3':
                    $Destinatario = 'Eps';
                break;
                
                default:
                    $Destinatario = 'N/A';
                break;
            }
            $id_dest_principal = $nombre_entidad;
        }
        //valida la acción del botón
        if ($request->bandera_pronuncia_guardar_actualizar == 'Guardar') {
            //Se asignan los IDs de destinatario por cada posible destinatario
            $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario();
        
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
                'Copia_Afp_Conocimiento' => $copy_afp_conocimiento,
                'Copia_junta_regional' => $junta_regional,
                'Copia_junta_nacional' => $junta_nacional,
                'Junta_regional_cual' => $cual,
                'N_anexos' => $request->n_anexos,
                'Elaboro_pronuncia' => $request->elaboro,
                'Reviso_pronuncia' => $request->reviso,
                'Ciudad_correspon' => $request->ciudad_correspon,
                'N_radicado' => $radicado,
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
                'N_radicado' => $radicado,
                'Cliente' => $request->primer_calificador,
                'Nombre_afiliado' => $request->nombre_afiliado,
                'T_documento' => 'N/A',
                'N_identificacion' => $request->identificacion,
                'Destinatario' => $Destinatario,
                'Nombre_destinatario' => $id_dest_principal ? $id_dest_principal : 'N/A',
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
                'JRCI_copia' => $cual,
                'Anexos' => $request->n_anexos,
                'Tipo_descarga' => $request->decision_pr,
                'Modulo_creacion' => 'pronunciamientoOrigen',
                'Reemplazado' => 0,
                'N_siniestro' => $request->n_siniestro,
                //Siempre va a ser otro destinatario, debido a que el destinatario es el primer calificador.
                'Otro_destinatario' => 1,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')->insert($datos_info_pronunciamiento_eventos);
            sleep(2);

            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $request->n_siniestro
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$Id_EventoPronuncia]])
            ->update($dato_actualizar_n_siniestro);

            sleep(2);
            $id_comunicado = null;
            if($request->decision_pr != 'Silencio' && !isset($request->otro_calificador)){
                $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_comunicado_eventos);
                sleep(2);
            }
            // REGISTRO ACTIVIDAD PARA AUDITORIA //
            $Id_Pronuncia = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')->select('Id_Pronuncia')->latest('Id_Pronuncia')->first();
            $accion_realizada = "Registro de Pronuciamiento Origen {$Id_Pronuncia['Id_Pronuncia']}";
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
                'N_radicado' => $radicado,
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

            // Actualizacion del profesional calificador
            $datos_profesional_calificador = [
                'Id_calificador' => Auth::user()->id,
                'Nombre_calificador' => Auth::user()->name
            ];
        
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $Id_Asignacion_Pronuncia)->update($datos_profesional_calificador);

            sleep(2);
            $datos_info_accion_evento= [    
                'F_calificacion_servicio' => $datetime
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $Id_EventoPronuncia)->update($datos_info_accion_evento);

            $mensajes = array(
                "decision" => $request->decision_pr,
                "Id_Comunicado" => $id_comunicado ? $id_comunicado : null,
                "parametro" => 'agregar_pronunciamiento',
                "parametro2" => 'guardo',
                "mensaje" => 'Información guardada satisfactoriamente.'
            ); 

            return json_decode(json_encode($mensajes, true));

        }elseif($request->bandera_pronuncia_guardar_actualizar == 'Actualizar'){

            //Capturamos el Id del comunicado para poder generarlo en el servidor
            $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_EventoPronuncia],
                ['Id_Asignacion',$Id_Asignacion_Pronuncia],
                ['Id_proceso', $Id_ProcesoPronuncia],
                ['N_radicado',$request->n_radicado]
                ])
            ->value('Id_Comunicado');

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
                'Copia_Afp_Conocimiento' => $copy_afp_conocimiento,
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
            ->where([
                ['ID_evento', $Id_EventoPronuncia],
                ['Id_Asignacion', $Id_Asignacion_Pronuncia]
            ])->update($datos_info_pronunciamiento_eventos);
            sleep(2);

            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $request->n_siniestro
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$Id_EventoPronuncia]])
            ->update($dato_actualizar_n_siniestro);

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
                'Nombre_destinatario' => $id_dest_principal ? $id_dest_principal : 'N/A',
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
                'Agregar_copia' => $agregar_copias_comu,
                'JRCI_copia' => $cual,
                'Anexos' => $request->n_anexos,
                'Tipo_descarga' => $request->decision_pr,
                'Modulo_creacion' => 'pronunciamientoOrigen',
                'Reviso' => 0,
                'Reemplazado' => 0,
                'N_siniestro' => $request->n_siniestro,
                'Otro_destinatario' => 1,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            // dd($request->decision_pr);
            if($request->decision_pr != 'Silencio' && $id_comunicado && !isset($request->otro_calificador)){
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where([
                    ['ID_evento', $Id_EventoPronuncia],
                    ['Id_Asignacion', $Id_Asignacion_Pronuncia],
                    ['Id_Comunicado', $id_comunicado]
                ])->update($datos_info_comunicado_eventos);
                sleep(2);
            }
            else if($request->decision_pr == 'Silencio' && $id_comunicado && !isset($request->otro_calificador)){
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $id_comunicado)->delete();
                $archivos_a_eliminar = [
                    "ORI_ACUERDO_{$Id_Asignacion_Pronuncia}_{$request->identificacion}.pdf",
                    "ORI_DESACUERDO_{$Id_Asignacion_Pronuncia}_{$request->identificacion}.docx"
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
            if($request->decision_pr != 'Silencio' && !$id_comunicado && !isset($request->otro_calificador)){
                //Se asignan los IDs de destinatario por cada posible destinatario
                $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario();
                $datos_info_comunicado_eventos['Id_Destinatarios'] = $ids_destinatarios;
                //Si la decisión es silencio y no existe ningun comunicado relacionado se hace una inserción 
                $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_comunicado_eventos);
                sleep(2);
            }
            // REGISTRO ACTIVIDAD PARA AUDITORIA //
            $Id_Pronuncia = sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')->select('Id_Pronuncia','Id_Asignacion')->latest('Id_Pronuncia')->first();
            $accion_realizada = "Actualiza Pronuciamiento Origen {$Id_Pronuncia['Id_Pronuncia']}";
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
            sigmel_auditorias_pronunciamiento_eventos::on('sigmel_auditorias')->insert($registro_actividad);
            if(!empty($array_diagnosticos_motivo_calificacion)){
                // Inserción de la información
                foreach ($array_datos_con_keys as $insertar_diagnostico) {
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico);
                } 
            }
            
            $datos_info_comunicado_eventos = [
                'Agregar_copia' => $agregar_copias_comu,
                'Nombre_usuario' => $nombre_usuario,
                'JRCI_copia' => $cual,
                'F_registro' => $date,
            ];   
                
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([                
                ['N_radicado',$radicado],
                ['ID_evento', $Id_EventoPronuncia],
                ['Id_Asignacion', $Id_Asignacion_Pronuncia],
            ])->update($datos_info_comunicado_eventos);

            sleep(2);
            $datos_info_accion_evento= [    
                'F_calificacion_servicio' => $datetime
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $Id_EventoPronuncia)->update($datos_info_accion_evento);

            $mensajes = array(
                "decision" => $request->decision_pr,
                "Id_Comunicado" => $id_comunicado ? $id_comunicado : null,
                "parametro" => 'update_pronunciamiento',
                "parametro2" => 'guardo',
                "mensaje" => 'Información actualizada satisfactoriamente.'
            ); 

            return json_decode(json_encode($mensajes, true));

        } 


    }

    //Ver Documento Pronuncia
    public function VerDocumentoPronunciamiento(Request $request){
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

    /* Descargue de proforma de Acuerdo o Desacuerdo */
    public function DescargarProformaPronunciamiento(Request $request){
        $time = time();
        $date = date("Y-m-d", $time);
        $id_evento = $request->id_evento;
        $Id_Asignacion_consulta_dx = $request->id_asignacion;
        $Id_Proceso_consulta_dx = $request->id_proceso;
        $Id_comunicado = $request->id_comunicado;
        
        $array_datos_pronunciamientoOrigen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_Asignacion_consulta_dx));
        $info_pronuncia = $this->globalService->retornarInformacionPronunciamiento($id_evento,$Id_Asignacion_consulta_dx);
        $info_comunicado = $this->globalService->retornarInformacionComunicado($Id_comunicado);
        if($info_comunicado && !empty($info_comunicado[0]->Tipo_descarga)){
            if($info_comunicado[0]->Tipo_descarga === 'Acuerdo'){
                $bandera_tipo_proforma = "proforma_acuerdo";
            }
            else if($info_comunicado[0]->Tipo_descarga === 'Desacuerdo'){
                $bandera_tipo_proforma = "proforma_desacuerdo";
            }
        }
        else{
            $bandera_tipo_proforma = '';
        }
        /* Captura de variables que vienen del ajax */
        
        $ciudad = $request->ciudad;
        $fecha = $request->fecha;
        $nro_radicado = $request->nro_radicado;
        
        // $nombre_afiliado = $request->nombre_afiliado;
        $nombre_afiliado = !empty($array_datos_pronunciamientoOrigen[0]->Nombre_afiliado) ? $array_datos_pronunciamientoOrigen[0]->Nombre_afiliado : null;
        
        // $tipo_identificacion = $request->tipo_identificacion;
        $tipo_identificacion = !empty($array_datos_pronunciamientoOrigen[0]->Nombre_tipo_documento) ?  $array_datos_pronunciamientoOrigen[0]->Nombre_tipo_documento : null;

        // $num_identificacion = $request->num_identificacion;
        $num_identificacion = !empty($array_datos_pronunciamientoOrigen[0]->Nro_identificacion) ? $array_datos_pronunciamientoOrigen[0]->Nro_identificacion : null;

        $fecha_dictamen = $request->fecha_dictamen;
        $fecha_dictamen = date("d/m/Y", strtotime($fecha_dictamen));

        $origen = "<b>".$request->origen."</b>";

        $asunto = strtoupper($request->asunto);
        $sustentacion = $request->sustentacion;

        $destinatario_principal = $request->destinatario_principal;
        $tipo_entidad_correspon = $request->tipo_entidad_correspon;
        $nombre_entidad_correspon = $request->nombre_entidad_correspon;
        
        $copia_afiliado = $request->copia_afiliado;
        $copia_empleador = $request->copia_empleador;
        $copia_eps = $request->copia_eps;
        $copia_afp = $request->copia_afp;
        $copia_afp_conocimiento = $request->copia_afp_conocimiento;
        $copia_arl = $request->copia_arl;

        $copia_junta_regional = $request->copia_junta_regional;
        $copia_junta_nacional = $request->copia_junta_nacional;
        
        $firmar = $request->firmar;
        
        // $Id_cliente_firma = $request->Id_cliente_firma;
        $Id_cliente_firma = !empty($array_datos_pronunciamientoOrigen[0]->Id_cliente) ? $array_datos_pronunciamientoOrigen[0]->Id_cliente : null;

        $nro_anexos = $request->nro_anexos;
        
        // $nombre_entidad = $request->nombre_entidad;
        $nombre_entidad = $info_pronuncia && !empty($info_pronuncia[0]->Nombre_entidad) ? $info_pronuncia[0]->Nombre_entidad : null;

        $email_entidad = $request->email_entidad;
        $direccion_entidad = $request->direccion_entidad;
        $telefono_entidad = $request->telefono_entidad;
        $ciudad_entidad = $request->ciudad_entidad;
        $departamento_entidad = $request->departamento_entidad;
        $nro_dictamen_pri_cali = $request->nro_dictamen_pri_cali;
        $fecha_dictamen_pri_cali = $request->fecha_dictamen_pri_cali;
        

        if (!empty($request->N_siniestro)) {
            $N_siniestro = $request->N_siniestro;
        } else {
            $N_siniestro = '';
        }
        


        /* Creación de las variables faltantes que no están en el ajax */

        // Información Destinatario Principal
        if($destinatario_principal == "Si"){
            // Si es Si, la informacion del destinatario principal se saca de la entidad
            $datos_entidad = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Departamento', '=', 'sldm2.Id_departamento')
            ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sie.Emails as Email','sldm.Nombre_municipio as Nombre_ciudad', 'sldm2.Nombre_departamento')
            ->where([
                ['sie.Id_Entidad', $nombre_entidad_correspon],
                ['sie.IdTipo_entidad', $tipo_entidad_correspon]
            ])->get();
            
            if (count($datos_entidad) > 0) {
                $nombre_destinatario = $datos_entidad[0]->Nombre_entidad;
                $direccion_destinatario = $datos_entidad[0]->Direccion;
                $email_destinatario = $datos_entidad[0]->Email;
                $telefono_destinatario = $datos_entidad[0]->Telefonos;
                $ciudad_destinatario = $datos_entidad[0]->Nombre_ciudad;
                $departamento_destinatario = $datos_entidad[0]->Nombre_departamento;
            } else {
                $nombre_destinatario = "";
                $direccion_destinatario = "";
                $email_destinatario = "";
                $telefono_destinatario = "";
                $ciudad_destinatario = "";
                $departamento_destinatario = "";
            }
        }else{
            /* Si es No, la info del destinatario principal se saca dependiendo de las
            siguientes validaciones */

            // si la proforma es un Acuerdo : Destinatario Principal es la AFP del Afiliado
            // si la proforma es un Desacuerdo: Destinatario Principal es el primer califcador.

            if ($bandera_tipo_proforma == "proforma_acuerdo") {
                // $datos = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                // ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                // ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                // ->select('siae.Nombre_afiliado', 'siae.Direccion', 'siae.Telefono_contacto', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
                // ->where([['siae.ID_evento','=', $id_evento]])
                // ->get();

                $datos = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Departamento', '=', 'sldm2.Id_departamento')
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Emails as Email','sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad', 'sldm2.Nombre_departamento')
                ->where([['siae.ID_evento','=', $id_evento]])
                ->get();

                $array_datos = json_decode(json_encode($datos), true);
    
                if (count($array_datos) > 0) {
                    $nombre_destinatario = $array_datos[0]["Nombre_entidad"];
                    $direccion_destinatario = $array_datos[0]["Direccion"];
                    $email_destinatario = $array_datos[0]["Email"];
                    $telefono_destinatario = $array_datos[0]["Telefonos"];
                    $ciudad_destinatario = $array_datos[0]["Nombre_ciudad"];
                    $departamento_destinatario = $array_datos[0]["Nombre_departamento"];
                    
                } else {
                    $nombre_destinatario = "";
                    $direccion_destinatario = "";
                    $email_destinatario = "";
                    $telefono_destinatario = "";
                    $ciudad_destinatario = "";
                    $departamento_destinatario = "";
                }
            } else {
                $nombre_destinatario = $nombre_entidad;
                $direccion_destinatario = $direccion_entidad;
                $email_destinatario = $email_entidad;
                $telefono_destinatario = $telefono_entidad;
                $ciudad_destinatario = $ciudad_entidad;
                $departamento_destinatario = $departamento_entidad;
            }
        }

        // CIE10 NOMBRES CIE10 ORIGEN CIE10
        $diagnosticos_cie10 = array();
        $datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->select('slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->where([
            ['side.Estado', '=', 'Activo'],
            ['side.ID_evento', '=', $id_evento],
            ['side.Id_Asignacion', '=', $Id_Asignacion_consulta_dx]
        ])
        ->get();

        $array_datos_diagnostico_motcalifi = json_decode(json_encode($datos_diagnostico_motcalifi), true);

        for ($i=0; $i < count($array_datos_diagnostico_motcalifi); $i++) { 
            // $dato_concatenado = "<b>(".$array_datos_diagnostico_motcalifi[$i]['Codigo'].")(".$array_datos_diagnostico_motcalifi[$i]['Nombre_CIE10'].")(".$array_datos_diagnostico_motcalifi[$i]['Nombre_parametro'].")";
            $dato_concatenado = "(<b>".$array_datos_diagnostico_motcalifi[$i]['Codigo']."</b>) ".strtoupper($array_datos_diagnostico_motcalifi[$i]['Nombre_CIE10'])." de origen ".$array_datos_diagnostico_motcalifi[$i]['Nombre_parametro']."";
            array_push($diagnosticos_cie10, $dato_concatenado);
        }

        $string_diagnosticos_cie10 = implode(", ", $diagnosticos_cie10);
        $string_diagnosticos_cie10 = $string_diagnosticos_cie10;

        /* Copias Interesadas */
        // Validamos si los checkbox esta marcados
        $final_copia_afiliado = isset($copia_afiliado) ? 'Afiliado' : '';
        $final_copia_empleador = isset($copia_empleador) ? 'Empleador' : '';
        $final_copia_eps = isset($copia_eps) ? 'EPS' : '';
        $final_copia_afp = isset($copia_afp) ? 'AFP' : '';
        $final_copia_arl = isset($copia_arl) ? 'ARL' : '';
        $final_copia_afp_conocimiento = isset($copia_afp_conocimiento) ? 'AFP_Conocimiento' : '';

        $final_copias_jrci = isset($copia_junta_regional) ? 'JRCI': '';
        $final_copias_jnci = isset($copia_junta_nacional) ? 'JNCI': '';

        $total_copias = array_filter(array(
            'copia_afiliado' => $final_copia_afiliado,
            'copia_empleador' => $final_copia_empleador,
            'copia_eps' => $final_copia_eps,
            'copia_afp' => $final_copia_afp,
            'copia_afp_conocimiento' => $final_copia_afp_conocimiento,
            'copia_arl' => $final_copia_arl,
            'copia_jrci' => $final_copias_jrci,
            'copia_jnci' => $final_copias_jnci,
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
            ->where([['siae.Nro_identificacion', $num_identificacion],['siae.ID_evento', $id_evento]])
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
            ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'sile.Email')
            ->where([['sile.Nro_identificacion', $num_identificacion],['sile.ID_evento', $id_evento]])
            ->get();

            if (preg_match("/&/", $datos_empleador[0]->Empresa)) {
                $nombre_empleador = htmlspecialchars(preg_replace('/&/', '&amp;', $datos_empleador[0]->Empresa));
            } else {
                $nombre_empleador = $datos_empleador[0]->Empresa;
            }
            $direccion_empleador = $datos_empleador[0]->Direccion;
            $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
            $email_empleador = $datos_empleador[0]->Email;
            $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
            $municipio_empleador = $datos_empleador[0]->Nombre_municipio;

            $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$email_empleador."; ".$telefono_empleador."; ".$ciudad_empleador."; ".$municipio_empleador.".";   
        }

        if (isset($copia_eps)) {
            $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email', 
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $id_evento]])
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
            ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email', 
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $id_evento]])
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

        if (isset($copia_afp_conocimiento)) {
            if($bandera_tipo_proforma == "proforma_acuerdo"){
                $datos_entidades_conocimiento = $this->globalService->informacionEntidadesConocimientoEvento($id_evento, 'pdf');
            }else{
                $datos_entidades_conocimiento = $this->globalService->informacionEntidadesConocimientoEvento($id_evento, 'word');
            }
            $Agregar_copias['AFP_Conocimiento'] = $datos_entidades_conocimiento;
        }

        if(isset($copia_arl)){
            $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $id_evento]])
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

        if(isset($copia_jrci)){
            $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
            ->select('sie.Nombre_entidad', 
                'sie.Nit_entidad', 
                'sie.Direccion', 
                'sie.Telefonos',
                'sie.Otros_Telefonos',
                'sie.Emails',
                'sldm.Id_departamento',
                'sldm.Nombre_departamento',
                'sldm1.Id_municipios',
                'sldm1.Nombre_municipio as Nombre_ciudad'
            )->where([
                ['sie.Id_Entidad', $request->junta_regional_cual]
            ])->get();

            $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
            $direccion_jrci = $datos_jrci[0]->Direccion;

            if ($datos_jrci[0]->Otros_Telefonos != "") {
                $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
            } else {
                $telefonos_jrci = $datos_jrci[0]->Telefonos;
            }
            $email_jrci = $datos_jrci[0]->Emails;
            $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
            $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
                
            $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$email_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;

        }
        
        if(isset($copia_jnci)){
            $datos_jnci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
            ->select('sie.Nombre_entidad', 
                'sie.Nit_entidad', 
                'sie.Direccion', 
                'sie.Telefonos',
                'sie.Otros_Telefonos',
                'sie.Emails',
                'sldm.Id_departamento',
                'sldm.Nombre_departamento',
                'sldm1.Id_municipios',
                'sldm1.Nombre_municipio as Nombre_ciudad'
            )->where([
                ['sie.IdTipo_entidad', 5]
            ])->limit(1)->get();

            $nombre_jnci = $datos_jnci[0]->Nombre_entidad;
            $direccion_jnci = $datos_jnci[0]->Direccion;

            if ($datos_jnci[0]->Otros_Telefonos != "") {
                $telefonos_jnci = $datos_jnci[0]->Telefonos.",".$datos_jnci[0]->Otros_Telefonos;
            } else {
                $telefonos_jnci = $datos_jnci[0]->Telefonos;
            }
            $email_jnci = $datos_jnci[0]->Emails;
            $ciudad_jnci = $datos_jnci[0]->Nombre_ciudad;
            $departamento_jnci = $datos_jnci[0]->Nombre_departamento;

            $Agregar_copias['JNCI'] = $nombre_jnci."; ".$direccion_jnci."; ".$email_jnci."; ".$telefonos_jnci."; ".$ciudad_jnci." - ".$departamento_jnci;

        }

        /* Validación Firma Cliente */
        $validarFirma = isset($firmar) ? 'firmar' : 'Sin Firma';
        
        if ($validarFirma == "firmar") {
            $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente')
            ->where('Id_cliente', $Id_cliente_firma)->limit(1)->get();

            $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
            ->where('Id_cliente', $idcliente[0]->Id_cliente)->limit(1)->get();

            if(count($firmaclientecompleta) > 0){
                $Firma_cliente = $firmaclientecompleta[0]->Firma;
            }else{
                $Firma_cliente = 'No firma';
            }
        } else {
            $Firma_cliente = 'No firma';
        }
        /* datos del logo que va en el header */
        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $Id_cliente_firma]])
        ->limit(1)->get();
        if (count($dato_logo_header) > 0 && $dato_logo_header[0]->Logo_cliente != null) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
            $ruta_logo = "/logos_clientes/{$Id_cliente_firma}/{$logo_header}";
        } else {
            $logo_header = "Sin logo";
            $ruta_logo = "";
        }

        /* Extraemos los datos del footer */
        // $datos_footer = sigmel_clientes::on('sigmel_gestiones')
        // ->select('footer_dato_1', 'footer_dato_2', 'footer_dato_3', 'footer_dato_4', 'footer_dato_5')
        // ->where('Id_cliente', $Id_cliente_firma)->get();

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

        $ramo = "Previsionales";

        if ($bandera_tipo_proforma == "proforma_acuerdo") {
            $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
                ->select('Footer_cliente')
                ->where([['Id_cliente', $Id_cliente_firma]])
                ->limit(1)->get();

                if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
                    $footer = $footer_imagen[0]->Footer_cliente;
                } else {
                    $footer = null;
                } 
            $datos_finales_proforma = [
                'logo_header' => $logo_header,
                'id_cliente' => $Id_cliente_firma,
                'ciudad' => $ciudad,
                'fecha' => fechaFormateada($fecha),
                'nro_siniestro' => $id_evento,
                'nro_radicado' => $nro_radicado,
                'nombre_afiliado' => $nombre_afiliado,
                'tipo_identificacion' => $tipo_identificacion,
                'num_identificacion' => $num_identificacion,
                'fecha_dictamen' => $fecha_dictamen,
                'ramo' => $ramo,
                'asunto' => $asunto,
                'cuerpo' => $sustentacion,
                'nro_anexos' => $nro_anexos,
                'nombre_destinatario' => $nombre_destinatario,
                'direccion_destinatario' => $direccion_destinatario,
                'email_destinatario' => $email_destinatario,
                'telefono_destinatario' => $telefono_destinatario,
                'ciudad_destinatario' => $ciudad_destinatario,
                'departamento_destinatario' => $departamento_destinatario,
                'nombre_entidad_calificadora' => $nombre_entidad,
                'Firma_cliente' => $Firma_cliente,
                'Agregar_copia' => $Agregar_copias,
                'nombre_usuario' => Auth::user()->name,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            
    
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/Origen_Atel/pronunciamiento_acuerdo', $datos_finales_proforma);
            
            $indicativo = time();

            // $nombre_pdf = "ORI_ACUERDO_{$Id_Asignacion_consulta_dx}_{$num_identificacion}.pdf";
            $nombre_pdf = "ORI_ACUERDO_{$Id_Asignacion_consulta_dx}_{$num_identificacion}_{$indicativo}.pdf";
    
            //Obtener el contenido del PDF
            $output = $pdf->output();
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$id_evento}/{$nombre_pdf}"), $output);

            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_pdf
            ];

            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
            ->update($actualizar_nombre_documento);
            
            $datos = [
                'nombre_documento' => $nombre_pdf,
                'tipo_proforma' => "proforma_acuerdo",
                'indicativo' => $indicativo,
                'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
            ];
            
            return response()->json($datos);

        }
         else {
            $dato_logo_footer = sigmel_clientes::on('sigmel_gestiones')
            ->select('Footer_cliente')
            ->where([['Id_cliente', $Id_cliente_firma]])
            ->limit(1)->get();

            if (count($dato_logo_footer) > 0 && $dato_logo_footer[0]->Footer_cliente != null) {
                $logo_footer = $dato_logo_footer[0]->Footer_cliente;
                $ruta_logo_footer = "/footer_clientes/{$Id_cliente_firma}/{$logo_footer}";
            } else {
                $logo_footer = null;
                $ruta_logo_footer = null;
            }
            
            //Marca de agua
            $styleVigilado = [
                'width' => 20,           
                'height' => 100,  
                'marginTop' => 600,        
                'marginLeft' => -50,       
                'wrappingStyle' => 'behind',   // Imagen detrás del texto
                'positioning' => Image::POSITION_RELATIVE, 
                'posVerticalRel' => 'page', 
                'posHorizontal' => Image::POSITION_ABSOLUTE,
                'posVertical' => Image::POSITION_ABSOLUTE, // Centrado verticalmente en la página
            ];

            $pathVigilado = "/var/www/html/Sigmel/public/images/logos_preformas/vigilado.png";

            $phpWord = new PhpWord();
            // Configuramos la fuente y el tamaño de letra para todo el documento
            $phpWord->setDefaultFontName('Verdana');
            $phpWord->setDefaultFontSize(10);
            // Configuramos la alineación justificada para todo el documento
            $phpWord->setDefaultParagraphStyle(
                array('align' => 'both', 'spaceAfter' => 0, 'spaceBefore' => 0)
            );

            // Configurar el idioma del documento a español
            $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('es-ES'));
    

            //Section header
            $estilosPaginaWord = array(
                'headerHeight'=> 0,
                'footerHeight'=> Converter::inchToTwip(.2),
                'marginLeft'  => Converter::inchToTwip(1),
                'marginRight' => Converter::inchToTwip(1),
                'marginTop'   => 0,
                'marginBottom'=> 0,
            );
            $section = $phpWord->addSection($estilosPaginaWord);
            // Configuramos las margenes del documento (estrechas)
            // $section = $phpWord->addSection();
            // $section->setMarginLeft(0.5 * 72);
            // $section->setMarginRight(0.5 * 72);
            // $section->setMarginTop(0.5 * 72);
            // $section->setMarginBottom(0.5 * 72);

            // Creación de Header
            $header = $section->addHeader();
            $header->addWatermark($pathVigilado, $styleVigilado);
            $imagenPath_header = public_path($ruta_logo);
            $header->addImage($imagenPath_header, array('width' => 110, 'height' => 30, 'align' => 'right'));
            $encabezado = $header->addTextRun(['alignment' => 'right']);
            //FontStyle del paginado
            $fontStylePaginado = ['name' => 'Calibri', 'size' => 8];
            $encabezado->addText('Página ', $fontStylePaginado);
            $encabezado->addField('PAGE',[],[],null,$fontStylePaginado);
            $encabezado->addText(' de ',$fontStylePaginado);
            $encabezado->addField('NUMPAGES',[],[],null,$fontStylePaginado);
            $header->addTextBreak();
                      
            // Creación de Contenido
            $fecha_formateada = fechaFormateada($fecha);
            $section->addText($ciudad.' '.$fecha_formateada, array('bold' => true), array('align' => 'right'));
            $section->addTextBreak();

            $table = $section->addTable();

            $table->addRow();

            $cell = $table->addCell(7000);

            $textRun1 = $cell->addTextRun(array('alignment'=>'left'));
            $textRun1->addText('Señores: ',array('bold' => true));
            $textRun1->addTextBreak();
            $textRun1->addText($nombre_destinatario);
            $textRun1->addTextBreak();
            $textRun1->addText($email_destinatario);
            $textRun1->addTextBreak();
            $textRun1->addText($direccion_destinatario);
            $textRun1->addTextBreak();
            $textRun1->addText('Tel: ');
            $textRun1->addText($telefono_destinatario);
            $textRun1->addTextBreak();
            if($ciudad_destinatario !== 'Bogota D.C.'){
                $textRun1->addText($ciudad_destinatario.' - '.$departamento_destinatario);
            }else{
                $textRun1->addText($ciudad_destinatario);
            }

            $section->addTextBreak();
            $section->addTextBreak();

            $table = $section->addTable(array('alignment'=>'center'));

            // Configuramos el reemplazo de la etiqueta del asunto
            $patron1_asunto = '/\{\{\$NRO_DICTAMEN_PRI_CALI\}\}/';
            $patron2_asunto = '/\{\{\$FECHA_DICTAMEN_PRI_CALI\}\}/';

            if (preg_match($patron1_asunto, $asunto) && preg_match($patron2_asunto, $asunto)) {
                $asunto_modificado = str_replace('{{$NRO_DICTAMEN_PRI_CALI}}', $nro_dictamen_pri_cali, $asunto);
                $asunto_modificado = str_replace('{{$FECHA_DICTAMEN_PRI_CALI}}', date("d/m/Y", strtotime($fecha_dictamen_pri_cali)), $asunto_modificado);

                $asunto = $asunto_modificado;
            }else{
                $asunto = "";
            }

            $table->addRow();


            $cell1 = $table->addCell(2000);
            $cell2 = $table->addCell(8000);

            $asuntotext = $cell1->addTextRun(array('alignment'=>'left'));
            $asuntotext->addText('Asunto: ', array('bold' => true));
            $asuntoyafiliado = $cell2->addTextRun(array('alignment'=>'both'));
            $asuntoyafiliado->addText($asunto, array('bold' => true));
            $asuntoyafiliado->addTextBreak();
            $asuntoyafiliado->addText('PACIENTE: ', array('bold' => true));
            $asuntoyafiliado->addText(strtoupper($nombre_afiliado)." ".$tipo_identificacion." ".$num_identificacion,array('bold' => true));
            // $asuntoyafiliado->addTextBreak();
            // $asuntoyafiliado->addText('Ramo: ', array('bold' => true));
            // $asuntoyafiliado->addText($ramo);
            // $asuntoyafiliado->addTextBreak();
            // $asuntoyafiliado->addText('Siniestro: ', array('bold' => true));
            // $asuntoyafiliado->addText($N_siniestro);
            $section->addTextBreak();

            // $section->addText('Asunto: '.$asunto, array('bold' => true));
            // $section->addText('PACIENTE: '.$nombre_afiliado.' '.$tipo_identificacion.' '.$num_identificacion, array('bold' => true));
            // $section->addText('Ramo: '.$ramo, array('bold' => true));
            // $section->addText('Siniestro: '.$id_evento, array('bold' => true));

            // Configuramos el reemplazo de las etiquetas en el cuerpo
            $sustentacion = str_replace(['<br>', '<br/>', '<br />', '</br>'], '', $sustentacion);
            $patron1 = '/\{\{\$nombre_afiliado\}\}/';
            $patron2 = '/\{\{\$tipo_documento\}\}/';
            $patron3 = '/\{\{\$nro_identificacion\}\}/';
            $patron4 = '/\{\{\$cie10_nombrecie10_origencie10\}\}/';

            if (preg_match($patron1, $sustentacion) && preg_match($patron2, $sustentacion) &&
                preg_match($patron3, $sustentacion) && preg_match($patron4, $sustentacion)) {
                
                    $texto_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.strtoupper($nombre_afiliado).'</b>', $sustentacion);
                    $texto_modificado = str_replace('{{$tipo_documento}}', '<b>'.strtoupper($tipo_identificacion).'</b>', $texto_modificado);
                    $texto_modificado = str_replace('{{$nro_identificacion}}', '<b>'.$num_identificacion.'</b>', $texto_modificado);
                    $texto_modificado = str_replace('{{$cie10_nombrecie10_origencie10}}', $string_diagnosticos_cie10, $texto_modificado);

                    $texto_modificado = str_replace('HUGO IGNACIO GÓMEZ DAZA', '<b>HUGO IGNACIO GÓMEZ DAZA</b>', $texto_modificado);
                    $texto_modificado = str_replace('SEGUROS DE VIDA ALFA S.A.', '<b>SEGUROS DE VIDA ALFA S.A.</b>', $texto_modificado);
                    $texto_modificado = str_replace('AFP', '<b>AFP</b>', $texto_modificado);
                    $texto_modificado = str_replace('PORVENIR S.A.', '<b>PORVENIR S.A.</b>', $texto_modificado);
                    $texto_modificado = str_replace('RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE APELACIÓN', '<b>RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE APELACIÓN</b>', $texto_modificado);
                    $texto_modificado = str_replace('ORIGEN', '<b>ORIGEN</b>', $texto_modificado);
                    $texto_modificado = str_replace('ANEXO:', '<b>ANEXO:</b>', $texto_modificado);
                    $texto_modificado = str_replace('NOTIFICACIONES:', '<b>NOTIFICACIONES:</b>', $texto_modificado);
                    

                    $texto_modificado = str_replace('</p>', '</p><br></br>', $texto_modificado);
                    $texto_modificado = str_replace('<p><br>', ' ', $texto_modificado);
                    $cuerpo = $texto_modificado;
            } else {
                $cuerpo = "";
            }

            $section->addTextBreak();
            Html::addHtml($section, $cuerpo, false, true);
            $section->addTextBreak();
            $section->addText('Cordialmente,', array('bold' => true));
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
                //PBS060 Piden "Eliminar la frase "No firma", dejar un espacio de 5 renglones entre "Cordialmente," y el nombre del representante legal Alfa".
                $section->addTextBreak();
                $section->addTextBreak();
                $section->addTextBreak();
                // $section->addText($Firma_cliente);
            }

            $section->addTextBreak();
            $section->addText('HUGO IGNACIO GÓMEZ DAZA', array('bold' => true));
            $section->addText('C.C. 80413626');
            $section->addText('Representante Legal');
            $section->addText('Seguros de Vida Alfa S.A.');
            $section->addTextBreak();
            // $section->addText('Elaboró: '.Auth::user()->name, array('bold' => true));
            // $section->addTextBreak();

            // Configuramos la tabla de copias a partes interesadas
            $htmltabla2 = '<table style="text-align: justify; width:100%; border-collapse: collapse; margin-left: auto; margin-right: auto;">';
            if (count($Agregar_copias) == 0) {
                $htmltabla2 .= '
                    <tr>
                        <td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">Copia: </span>No se registran copias</td>
                    </tr>';
            } else {
                $htmltabla2 .= '
                    <tr>
                        <td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">Copia:</span></td>
                    </tr>';
                $Afiliado = 'Afiliado';
                $Empleador = 'Empleador';
                $EPS = 'EPS';
                $AFP = 'AFP';
                $ARL = 'ARL';
                $AFP_Conocimiento = 'AFP_Conocimiento';
                $JRCI = 'JRCI';
                $JNCI = 'JNCI';

                if (isset($Agregar_copias[$Afiliado])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">Afiliado: </span>' . $Agregar_copias['Afiliado'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$Empleador])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">Empleador: </span>' . $Agregar_copias['Empleador'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$EPS])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">EPS: </span>' . $Agregar_copias['EPS'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$AFP])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">AFP: </span>' . $Agregar_copias['AFP'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$ARL])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">ARL: </span>' . $Agregar_copias['ARL'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$JRCI])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">JRCI: </span>' . $Agregar_copias['JRCI'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$JNCI])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify; font-family: Verdana; font-size: 8pt; font-style: italic;"><span style="font-weight:bold;">JNCI: </span>' . $Agregar_copias['JNCI'] . '</td></tr>';
                }

                if(isset($Agregar_copias[$AFP_Conocimiento])){
                    $htmltabla2.= $Agregar_copias['AFP_Conocimiento'];
                }
            }

            $htmltabla2 .= '</table>';
            Html::addHtml($section, $htmltabla2, false, true);
            $section->addTextBreak();
            $section->addTextBreak();

            //Cuadro con la información del siniestro
            $tableCuadro = $section->addTable();

            $tableCuadro->addRow();
            
            $cellCuadro = $tableCuadro->addCell(10000);
            //Estilo del texto del cuadro
            $styleTextCuadro = ['bold' => true,'name' => 'Calibri', 'size' => 9];
            //Cuadro
            $cuadro = $cellCuadro->addTable(array('borderSize' => 12, 'borderColor' => '000000', 'width' => 80*60, 'alignment'=>'center'));
            $cuadro->addRow();
            $celdaCuadro = $cuadro->addCell();
            $cuadroTextRun = $celdaCuadro->addTextRun(array('alignment'=>'left'));
            $cuadroTextRun->addText('Nro. Radicado: ', $styleTextCuadro);
            $cuadroTextRun->addTextBreak();
            $cuadroTextRun->addText($nro_radicado, $styleTextCuadro);
            $cuadroTextRun->addTextBreak();
            $cuadroTextRun->addText($tipo_identificacion . ' ' . $num_identificacion, $styleTextCuadro);
            $cuadroTextRun->addTextBreak();
            $cuadroTextRun->addText('Siniestro: ' . $N_siniestro, $styleTextCuadro);

            // Configuramos el footer
            // $info = $nombre_afiliado." - ".$tipo_identificacion." ".$num_identificacion." - Siniestro: ".$N_siniestro;
            $footer = $section->addFooter();
            // $footer-> addText($info, array('size' => 10, 'bold' => true), array('align' => 'center'));
            if($ruta_logo_footer != null){
                $imagenPath_footer = public_path($ruta_logo_footer);
                $footer->addImage($imagenPath_footer, array('width' => 450, 'height' => 70, 'alignment' => 'left'));
            }
            $table = $footer->addTable('myTable');

            // Generamos el documento y luego se guarda
            $writer = new Word2007($phpWord);

            $indicativo = time();

            // $nombre_docx = "ORI_DESACUERDO_{$Id_Asignacion_consulta_dx}_{$num_identificacion}.docx";
            $nombre_docx = "ORI_DESACUERDO_{$Id_Asignacion_consulta_dx}_{$num_identificacion}_{$indicativo}.docx";

            $writer->save(public_path("Documentos_Eventos/{$id_evento}/{$nombre_docx}"));

            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_docx
            ];

            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
            ->update($actualizar_nombre_documento);

            // Leer el contenido del archivo guardado y codificarlo en base64
            $contenidoWord = File::get(public_path("Documentos_Eventos/{$id_evento}/{$nombre_docx}"));

            $datos = [
                'nombre_documento' => $nombre_docx,
                'tipo_proforma' => "proforma_desacuerdo",
                'indicativo' => $indicativo,
                'word' => base64_encode($contenidoWord)
            ];
            
            return response()->json($datos);

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
