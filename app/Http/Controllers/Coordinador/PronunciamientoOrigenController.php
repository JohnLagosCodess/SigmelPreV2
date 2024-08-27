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

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Image;
use Html2Text\Html2Text;

class PronunciamientoOrigenController extends Controller
{
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
        //Traer info informacion pronunciamiento
        // sigmel_informacion_pronunciamiento_eventos
        $info_pronuncia= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_pronunciamiento_eventos as pr')
        ->select('pr.ID_evento','pr.Id_Asignacion', 'Id_proceso', 'pr.Id_primer_calificador','c.Tipo_Entidad','pr.Id_nombre_calificador','e.Nombre_entidad'
        ,'pr.Nit_calificador','pr.Dir_calificador','pr.Email_calificador','pr.Telefono_calificador','pr.Depar_calificador','pr.Ciudad_calificador'
        ,'pr.Id_tipo_pronunciamiento','p.Nombre_parametro as Tpronuncia','pr.Id_tipo_evento','ti.Nombre_evento','pr.Id_tipo_origen','or.Nombre_parametro as T_origen'
        ,'pr.Fecha_evento','pr.Dictamen_calificador','pr.Fecha_calificador','pr.N_siniestro','pr.Fecha_estruturacion','pr.Porcentaje_pcl','pr.Rango_pcl'
        ,'pr.Decision','pr.Fecha_pronuncia','pr.Asunto_cali','pr.Sustenta_cali','pr.Destinatario_principal','pr.Tipo_entidad','pr.Nombre_entidad as Nombre_entidad_correspon','pr.Copia_afiliado','pr.copia_empleador','pr.Copia_eps'
        ,'pr.Copia_afp','pr.Copia_arl','pr.Copia_junta_regional','pr.Copia_junta_nacional','pr.Junta_regional_cual','sie.Nombre_entidad as Ciudad_Junta'
        ,'pr.N_anexos','pr.Elaboro_pronuncia','pr.Reviso_Pronuncia','pr.Ciudad_correspon','pr.N_radicado','pr.Firmar','pr.Fecha_correspondencia'
        ,'pr.Archivo_pronuncia')
        ->leftJoin('sigmel_gestiones.sigmel_lista_entidades as c', 'c.Id_Entidad', '=', 'pr.Id_primer_calificador')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as e', 'e.Id_Entidad', '=', 'pr.Id_nombre_calificador')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as p', 'p.Id_Parametro', '=', 'pr.Id_tipo_pronunciamiento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as ti', 'ti.Id_Evento', '=', 'pr.Id_tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as or', 'or.Id_Parametro', '=', 'pr.Id_tipo_origen')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'pr.Junta_regional_cual')
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
        return view('coordinador.pronunciamientoOrigenATEL', compact('user','array_datos_pronunciamientoOrigen','info_pronuncia','array_datos_diagnostico_motcalifi','consecutivo',
        'array_comunicados', 'caso_notificado'));
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
                'Otro_destinatario' => 1,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            sigmel_informacion_pronunciamiento_eventos::on('sigmel_gestiones')->insert($datos_info_pronunciamiento_eventos);
            sleep(2);
            if($request->decision_pr != 'Silencio'){
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);
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
            ->where([
                ['ID_evento', $Id_EventoPronuncia],
                ['Id_Asignacion', $Id_Asignacion_Pronuncia]
            ])->update($datos_info_pronunciamiento_eventos);
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
                'Otro_destinatario' => 1,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
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
            if($request->decision_pr != 'Silencio' && $request->Id_Comunicado == "null"){
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);
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
                ['N_radicado',$radicado]
            ])->update($datos_info_comunicado_eventos);

            sleep(2);
            $datos_info_accion_evento= [    
                'F_calificacion_servicio' => $datetime
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $Id_EventoPronuncia)->update($datos_info_accion_evento);

            $mensajes = array(
                "parametro" => 'update_pronunciamiento',
                "parametro2" => 'guardo',
                "mensaje2" => 'Actualiza satisfactoriamente.'
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

        /* Captura de variables que vienen del ajax */
        $bandera_tipo_proforma = $request->bandera_tipo_proforma;

        $ciudad = $request->ciudad;
        $fecha = $request->fecha;
        $id_evento = $request->nro_siniestro;
        
        $nro_radicado = $request->nro_radicado;
        $nombre_afiliado = $request->nombre_afiliado;
        $tipo_identificacion = $request->tipo_identificacion;
        $num_identificacion = $request->num_identificacion;
        $fecha_dictamen = $request->fecha_dictamen;

        $origen = "<b>".$request->origen."</b>";

        $asunto = strtoupper($request->asunto);
        $sustentacion = $request->sustentacion;

        $Id_Asignacion_consulta_dx = $request->Id_Asignacion_consulta_dx;
        $Id_Proceso_consulta_dx = $request->Id_Proceso_consulta_dx;

        $destinatario_principal = $request->destinatario_principal;
        $tipo_entidad_correspon = $request->tipo_entidad_correspon;
        $nombre_entidad_correspon = $request->nombre_entidad_correspon;
        
        $copia_afiliado = $request->copia_afiliado;
        $copia_empleador = $request->copia_empleador;
        $copia_eps = $request->copia_eps;
        $copia_afp = $request->copia_afp;
        $copia_arl = $request->copia_arl;
        $copia_junta_regional = $request->copia_junta_regional;
        $copia_junta_nacional = $request->copia_junta_nacional;
        
        $firmar = $request->firmar;
        
        $Id_cliente_firma = $request->Id_cliente_firma;
        $nro_anexos = $request->nro_anexos;
        
        $nombre_entidad = $request->nombre_entidad;
        $email_entidad = $request->email_entidad;
        $direccion_entidad = $request->direccion_entidad;
        $telefono_entidad = $request->telefono_entidad;
        $ciudad_entidad = $request->ciudad_entidad;
        $departamento_entidad = $request->departamento_entidad;
        $nro_dictamen_pri_cali = $request->nro_dictamen_pri_cali;
        $fecha_dictamen_pri_cali = $request->fecha_dictamen_pri_cali;
        $Id_comunicado = $request->id_comunicado;
        $N_siniestro = $request->N_siniestro;


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
            } else {
                $nombre_destinatario = "";
                $direccion_destinatario = "";
                $email_destinatario = "";
                $telefono_destinatario = "";
                $ciudad_destinatario = "";
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
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Emails as Email','sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad')
                ->where([['siae.ID_evento','=', $id_evento]])
                ->get();

                $array_datos = json_decode(json_encode($datos), true);
    
                if (count($array_datos) > 0) {
                    $nombre_destinatario = $array_datos[0]["Nombre_entidad"];
                    $direccion_destinatario = $array_datos[0]["Direccion"];
                    $email_destinatario = $array_datos[0]["Email"];
                    $telefono_destinatario = $array_datos[0]["Telefonos"];
                    $ciudad_destinatario = $array_datos[0]["Nombre_ciudad"];
                } else {
                    $nombre_destinatario = "";
                    $direccion_destinatario = "";
                    $email_destinatario = "";
                    $telefono_destinatario = "";
                    $ciudad_destinatario = "";
                }
            } else {
                $nombre_destinatario = $nombre_entidad;
                $direccion_destinatario = $direccion_entidad;
                $email_destinatario = $email_entidad;
                $telefono_destinatario = $telefono_entidad;
                $ciudad_destinatario = $ciudad_entidad;
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
        $final_copias_jrci = isset($copia_junta_regional) ? 'JRCI': '';
        $final_copias_jnci = isset($copia_junta_nacional) ? 'JNCI': '';

        $total_copias = array_filter(array(
            'copia_afiliado' => $final_copia_afiliado,
            'copia_empleador' => $final_copia_empleador,
            'copia_eps' => $final_copia_eps,
            'copia_afp' => $final_copia_afp,
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

            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            // ->select('siae.Id_servicio')
            // ->where([
            //     ['siae.Id_Asignacion', $Id_Asignacion_consulta_dx],
            //     ['siae.ID_evento', $id_evento],
            //     ['siae.Id_proceso', $Id_Proceso_consulta_dx],
            // ])->get();

            // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            // ->select('Nombre_documento')
            // ->where([
            //     ['Nombre_documento', $nombre_pdf],
            // ])->get();
            
            // if(count($verficar_documento) == 0){
                
            //     // Se valida si antes de insertar la info del doc de acuerdo ya hay un doc de desacuerdo
            //     $nombre_docu_desacuerdo = "ORI_DESACUERDO_{$Id_Asignacion_consulta_dx}_{$num_identificacion}.docx";
            //     $verificar_docu_desacuerdo = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->select('Nombre_documento')
            //     ->where([
            //         ['Nombre_documento', $nombre_docu_desacuerdo],
            //     ])->get();

            //     // Si no existe info del documento de desacuerdo, inserta la info del documento de acuerdo
            //     // De lo contrario hace una actualización de la info
            //     if (count($verificar_docu_desacuerdo) == 0) {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion_consulta_dx,
            //             'Id_proceso' => $Id_Proceso_consulta_dx,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $id_evento,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $nro_radicado,
            //             'F_elaboracion_correspondencia' => $fecha,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => Auth::user()->name,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
            //     }else{
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion_consulta_dx,
            //             'Id_proceso' => $Id_Proceso_consulta_dx,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $id_evento,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $nro_radicado,
            //             'F_elaboracion_correspondencia' => $fecha,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => Auth::user()->name,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //         ->where([
            //             ['Id_Asignacion', $Id_Asignacion_consulta_dx],
            //             ['N_radicado_documento', $nro_radicado],
            //             ['ID_evento', $id_evento]
            //         ])
            //         ->update($info_descarga_documento);
            //     }

            // }

            // return $pdf->download($nombre_pdf);
            $datos = [
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

            // Creación de Header
            $header = $section->addHeader();
            $imagenPath_header = public_path($ruta_logo);
            $header->addImage($imagenPath_header, array('width' => 150, 'align' => 'right'));
            $esti = array('size' => 11, 'font' => 'Arial');
            $encabezado = $header->addTextRun(['alignment' => 'right']);
            $encabezado->addText('Página ');
            $encabezado->addField('PAGE');
            $encabezado->addText(' de ');
            $encabezado->addField('NUMPAGES');
            $header->addTextBreak();
                      
            // Creación de Contenido
            $fecha_formateada = fechaFormateada($fecha);
            $section->addText($ciudad.' '.$fecha_formateada, array('bold' => true), array('align' => 'right'));
            $section->addTextBreak();

            $table = $section->addTable();

            $table->addRow();

            $cell1 = $table->addCell(6000);

            $textRun1 = $cell1->addTextRun(array('alignment'=>'left'));
            $textRun1->addText('Señores: ',array('bold' => true));
            $textRun1->addTextBreak();
            $textRun1->addText($nombre_destinatario);
            $textRun1->addTextBreak();
            $textRun1->addText($email_destinatario);
            $textRun1->addTextBreak();
            $textRun1->addText($direccion_destinatario);
            $textRun1->addTextBreak();
            $textRun1->addText($telefono_destinatario);
            $textRun1->addTextBreak();
            $textRun1->addText($ciudad_destinatario);

            $cell2 = $table->addCell(4000);

            $nestedTable = $cell2->addTable(array('borderSize' => 12, 'borderColor' => '000000', 'width' => 80 * 60, 'alignment'=>'right'));
            $nestedTable->addRow();
            $nestedCell = $nestedTable->addCell();
            $nestedTextRun = $nestedCell->addTextRun(array('alignment'=>'left'));
            $nestedTextRun->addText('Nro. Radicado: ', array('bold' => true));
            $nestedTextRun->addTextBreak();
            $nestedTextRun->addText($nro_radicado, array('bold' => true));
            $nestedTextRun->addTextBreak();
            $nestedTextRun->addText($tipo_identificacion . ' ' . $num_identificacion, array('bold' => true));
            $nestedTextRun->addTextBreak();
            $nestedTextRun->addText('Siniestro: ' . $N_siniestro, array('bold' => true));

            $section->addTextBreak();
            $section->addTextBreak();

            $table = $section->addTable(array('alignment'=>'center'));

            // Configuramos el reemplazo de la etiqueta del asunto
            $patron1_asunto = '/\{\{\$NRO_DICTAMEN_PRI_CALI\}\}/';
            $patron2_asunto = '/\{\{\$FECHA_DICTAMEN_PRI_CALI\}\}/';

            if (preg_match($patron1_asunto, $asunto) && preg_match($patron2_asunto, $asunto)) {
                $asunto_modificado = str_replace('{{$NRO_DICTAMEN_PRI_CALI}}', $nro_dictamen_pri_cali, $asunto);
                $asunto_modificado = str_replace('{{$FECHA_DICTAMEN_PRI_CALI}}', $fecha_dictamen_pri_cali, $asunto_modificado);

                $asunto = $asunto_modificado;
            }else{
                $asunto = "";
            }

            $table->addRow();


            $cell1 = $table->addCell(8000);

            $asuntoyafiliado = $cell1->addTextRun(array('alignment'=>'left'));
            $asuntoyafiliado->addText('Asunto: ', array('bold' => true));
            $asuntoyafiliado->addText($asunto, array('bold' => true));
            $asuntoyafiliado->addTextBreak();
            $asuntoyafiliado->addText('Paciente: ', array('bold' => true));
            $asuntoyafiliado->addText(strtoupper($nombre_afiliado)." ".$tipo_identificacion." ".$num_identificacion);
            $asuntoyafiliado->addTextBreak();
            $asuntoyafiliado->addText('Ramo: ', array('bold' => true));
            $asuntoyafiliado->addText($ramo);
            $asuntoyafiliado->addTextBreak();
            $asuntoyafiliado->addText('Siniestro: ', array('bold' => true));
            $asuntoyafiliado->addText($N_siniestro);
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
                // $section->addText($Firma_cliente);
            }

            $section->addTextBreak();
            $section->addText('HUGO IGNACIO GÓMEZ DAZA', array('bold' => true));
            $section->addText('Representante Legal para Asuntos de Seguridad Social', array('bold' => true));
            $section->addText('Convenio Codess - Seguros de Vida Alfa S.A.', array('bold' => true));
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
                $JRCI = 'JRCI';
                $JNCI = 'JNCI';

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

                if (isset($Agregar_copias[$JRCI])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">JRCI: </span>' . $Agregar_copias['JRCI'] . '</td></tr>';
                }

                if (isset($Agregar_copias[$JNCI])) {
                    $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">JNCI: </span>' . $Agregar_copias['JNCI'] . '</td></tr>';
                }
            }

            $htmltabla2 .= '</table>';
            Html::addHtml($section, $htmltabla2, false, true);
            $section->addTextBreak();
            // Configuramos el footer
            $info = $nombre_afiliado." - ".$tipo_identificacion." ".$num_identificacion." - Siniestro: ".$id_evento;
            $footer = $section->addFooter();
            $footer-> addText($info, array('size' => 10, 'bold' => true), array('align' => 'center'));
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

            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            // ->select('siae.Id_servicio')
            // ->where([
            //     ['siae.Id_Asignacion', $Id_Asignacion_consulta_dx],
            //     ['siae.ID_evento', $id_evento],
            //     ['siae.Id_proceso', $Id_Proceso_consulta_dx],
            // ])->get();

            // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            // ->select('Nombre_documento')
            // ->where([
            //     ['Nombre_documento', $nombre_docx],
            // ])->get();
            
            // if(count($verficar_documento) == 0){

            //     // Se valida si antes de insertar la info del doc de desacuerdo ya hay un doc de acuerdo
            //     $nombre_docu_acuerdo = "ORI_ACUERDO_{$Id_Asignacion_consulta_dx}_{$num_identificacion}.pdf";
            //     $verificar_docu_acuerdo = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->select('Nombre_documento')
            //     ->where([
            //         ['Nombre_documento', $nombre_docu_acuerdo],
            //     ])->get();

            //     // Si no existe info del documento de acuerdo, inserta la info del documento de desacuerdo
            //     // De lo contrario hace una actualización de la info
            //     if (count($verificar_docu_acuerdo) == 0) {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion_consulta_dx,
            //             'Id_proceso' => $Id_Proceso_consulta_dx,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $id_evento,
            //             'Nombre_documento' => $nombre_docx,
            //             'N_radicado_documento' => $nro_radicado,
            //             'F_elaboracion_correspondencia' => $fecha,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => Auth::user()->name,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
            //     }else{
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion_consulta_dx,
            //             'Id_proceso' => $Id_Proceso_consulta_dx,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $id_evento,
            //             'Nombre_documento' => $nombre_docx,
            //             'N_radicado_documento' => $nro_radicado,
            //             'F_elaboracion_correspondencia' => $fecha,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => Auth::user()->name,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //         ->where([
            //             ['Id_Asignacion', $Id_Asignacion_consulta_dx],
            //             ['N_radicado_documento', $nro_radicado],
            //             ['ID_evento', $id_evento]
            //         ])
            //         ->update($info_descarga_documento);
            //     }
            // }

            // return response()->download(public_path("Documentos_Eventos/{$id_evento}/{$nombre_docx}"));

            // Leer el contenido del archivo guardado y codificarlo en base64
            $contenidoWord = File::get(public_path("Documentos_Eventos/{$id_evento}/{$nombre_docx}"));

            $datos = [
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
