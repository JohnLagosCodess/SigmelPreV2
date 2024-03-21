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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Image;
use Html2Text\Html2Text;

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
        ->select('pr.ID_evento','pr.Id_primer_calificador','c.Tipo_Entidad','pr.Id_nombre_calificador','e.Nombre_entidad as Nombre_calificador'
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

            // Actualizacion del profesional calificador
            $datos_profesional_calificador = [
                'Id_profesional' => Auth::user()->id,
                'Nombre_profesional' => Auth::user()->name,
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
            ->where([['ID_Evento', $Id_EventoPronuncia], ['Id_Asignacion',$Id_Asignacion_Pronuncia]])->update($datos_info_pronunciamiento_eventos);
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
            // Actualizacion del profesional calificador
            $datos_profesional_calificador = [
                'Id_profesional' => Auth::user()->id,
                'Nombre_profesional' => Auth::user()->name,
                'F_ajuste_calificacion' => $date
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

        $Id_Evento_pronuncia_corre = $request->Id_Evento_pronuncia_corre;
        $Asignacion_Pronuncia_corre = $request->Asignacion_Pronuncia_corre;
        $Id_Proceso_pronuncia_corre = $request->Id_Proceso_pronuncia_corre;
        $Nombre_afiliado_corre = $request->Nombre_afiliado_corre;
        $Iden_afiliado_corre = $request->Iden_afiliado_corre;
        $Firma_corre = $request->Firma_corre;
        $desicion_proforma = $request->desicion_proforma;

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
        'pr.Nombre_entidad as Id_Nombre_entidad', 'en.Nombre_entidad as Nombre_entidades', 'en.Direccion', 'en.Telefonos', 'en.Id_Ciudad', 
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
        if ($Destinatario_principal == 'Si' && $Decision == 'Acuerdo') {
            $Nombre_entidades = $info_pronunciamiento[0]->Nombre_entidades;       
            $Direccion_enti = $info_pronunciamiento[0]->Direccion;
            $Telefonos_enti = $info_pronunciamiento[0]->Telefonos;
            $Nombre_ciudad_enti = $info_pronunciamiento[0]->Nombre_ciudad;
            $Nombre_departamento_enti = $info_pronunciamiento[0]->Nombre_departamento;            
        } elseif($Destinatario_principal == 'No' && $Decision == 'Acuerdo') {
            $Nombre_entidades = $Nombre_afiliado_corre;       
            $Direccion_enti = $Direccion;
            $Telefonos_enti = $Telefono_contacto;
            $Nombre_ciudad_enti = $Nombre_municipio;
            $Nombre_departamento_enti = $Nombre_departamento; 
        }elseif($Destinatario_principal == 'Si' && $Decision == 'Desacuerdo') {
            $Entidad_calificador = $info_pronunciamiento[0]->Nombre_entidades;       
            $Dir_calificador = $info_pronunciamiento[0]->Direccion;
            $Telefono_calificador = $info_pronunciamiento[0]->Telefonos;
            $Ciudad_calificador = $info_pronunciamiento[0]->Nombre_ciudad;
            $Nombre_departamento_enti = $info_pronunciamiento[0]->Nombre_departamento;  
        }elseif($Destinatario_principal == 'No' && $Decision == 'Desacuerdo') {
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
        $Sustenta_cali = $info_pronunciamiento[0]->Sustenta_cali;
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
            $emailAfiliado = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
            ->select('Email')
            ->where([['Nro_identificacion', $Iden_afiliado_corre],['ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();
            $afiliadoEmail = $emailAfiliado[0]->Email;            
            $Agregar_copias['Afiliado'] = $afiliadoEmail;            
        }

        if(isset($copia_empleador)){

            $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['sile.Nro_identificacion', $Iden_afiliado_corre],['sile.ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();

            $nombre_empleador = $datos_empleador[0]->Empresa;
            $direccion_empleador = $datos_empleador[0]->Direccion;
            $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
            $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
            $municipio_empleador = $datos_empleador[0]->Nombre_municipio;

            $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$telefono_empleador."; ".$ciudad_empleador."; ".$municipio_empleador.".";   
        }

        if (isset($copia_eps)) {
            $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $Iden_afiliado_corre],['ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();

            $nombre_eps = $datos_eps[0]->Nombre_eps;
            $direccion_eps = $datos_eps[0]->Direccion;
            if ($datos_eps[0]->Otros_Telefonos != "") {
                $telefonos_eps = $datos_eps[0]->Telefonos.",".$datos_eps[0]->Otros_Telefonos;
            } else {
                $telefonos_eps = $datos_eps[0]->Telefonos;
            }
            $ciudad_eps = $datos_eps[0]->Nombre_ciudad;
            $minucipio_eps = $datos_eps[0]->Nombre_municipio;

            $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;
        }

        if (isset($copia_afp)) {
            $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $Iden_afiliado_corre],['ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();

            $nombre_afp = $datos_afp[0]->Nombre_afp;
            $direccion_afp = $datos_afp[0]->Direccion;
            if ($datos_afp[0]->Otros_Telefonos != "") {
                $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
            } else {
                $telefonos_afp = $datos_afp[0]->Telefonos;
            }
            $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
            $minucipio_afp = $datos_afp[0]->Nombre_municipio;

            $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$telefonos_afp."; ".$ciudad_afp."; ".$minucipio_afp;
        }

        if(isset($copia_arl)){
            $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $Iden_afiliado_corre],['ID_evento', $Id_Evento_pronuncia_corre]])
            ->get();

            $nombre_arl = $datos_arl[0]->Nombre_arl;
            $direccion_arl = $datos_arl[0]->Direccion;
            if ($datos_arl[0]->Otros_Telefonos != "") {
                $telefonos_arl = $datos_arl[0]->Telefonos.",".$datos_arl[0]->Otros_Telefonos;
            } else {
                $telefonos_arl = $datos_arl[0]->Telefonos;
            }
            
            $ciudad_arl = $datos_arl[0]->Nombre_ciudad;
            $minucipio_arl = $datos_arl[0]->Nombre_municipio;

            $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$telefonos_arl."; ".$ciudad_arl."; ".$minucipio_arl;
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

        //  Extraemos los datos del footer 
        $datos_footer = sigmel_clientes::on('sigmel_gestiones')
        ->select('footer_dato_1', 'footer_dato_2', 'footer_dato_3', 'footer_dato_4', 'footer_dato_5')
        ->where('Id_cliente',  $Cliente)->get();

        if(count($datos_footer) > 0){
            $footer_dato_1 = $datos_footer[0]->footer_dato_1;
            $footer_dato_2 = $datos_footer[0]->footer_dato_2;
            $footer_dato_3 = $datos_footer[0]->footer_dato_3;
            $footer_dato_4 = $datos_footer[0]->footer_dato_4;
            $footer_dato_5 = $datos_footer[0]->footer_dato_5;

        }else{
            $footer_dato_1 = "";
            $footer_dato_2 = "";
            $footer_dato_3 = "";
            $footer_dato_4 = "";
            $footer_dato_5 = "";
        }

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
                'footer_dato_1' => $footer_dato_1,
                'footer_dato_2' => $footer_dato_2,
                'footer_dato_3' => $footer_dato_3,
                'footer_dato_4' => $footer_dato_4,
                'footer_dato_5' => $footer_dato_5,
            ];
    
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_pro_acuerdo', $data);            
            $nombre_pdf = "PCL_ACUERDO_'{$Asignacion_Pronuncia_corre}_{$Iden_afiliado_corre}.pdf";    
            //Obtener el contenido del PDF
            $output = $pdf->output();
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$Id_Evento_pronuncia_corre}/{$nombre_pdf}"), $output);
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
            // $header->addImage($imagenPath_vigilado, $imageStyle);
            
            
            
            
            // Creación de Contenido
            $section->addText($Ciudad_correspon.' '.$Fecha_correspondencia, array('bold' => true));
            $section->addTextBreak();
            $htmltabla1 = '<table align="justify" style="width: 100%; border: none;">
                <tr>
                    <td>
                        <p><b>Señores: </b>'.$Entidad_calificador.'</p>
                        <p style="margin-top: 1px;"><b>Dirección: </b>'.$Dir_calificador.'</p>
                        <p><b>Teléfono: </b>'.$Telefono_calificador.'</p>
                        <p><b>Ciudad: </b>'.$Ciudad_calificador.'</p>
                    </td>                    
                </tr>
            </table>';
        
            Html::addHtml($section, $htmltabla1, false, true);

            $section->addText('Asunto: '.$Asunto_cali, array('bold' => true));
            $section->addText('PACIENTE: '.$Nombre_afiliado_corre." ".$Tipo_documento_afi." ".$Iden_afiliado_corre, array('bold' => false));
            $section->addText('Ramo: Previsionales', array('bold' => false));
            $section->addText('Siniestro '.$Id_Evento_pronuncia_corre, array('bold' => true));

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
                $width = $coincidencias[1]; // Valor de width
                $height = $coincidencias[2]; // Valor de height
            
                $nuevoStyle = 'width="'.$width.'" height="'.$height.'"';
                $htmlModificado = reemplazarStyleImg($Firma_cliente, $nuevoStyle);
                
                Html::addHtml($section, $htmlModificado, false, true);
            }else{
                $section->addText($Firma_cliente);
            }
            $section->addTextBreak();
            $section->addText('HUGO IGNACIO GÓMEZ DAZA', array('bold' => true));
            $section->addText('Representante Legal para Asuntos de Seguridad Social', array('bold' => true));
            $section->addText('Convenio Codess - Seguros de Vida Alfa S.A', array('bold' => true));
            $section->addTextBreak();
            $section->addText('Elaboró: '.Auth::user()->name, array('bold' => true));
            $section->addTextBreak();
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
            $tableStyle = array(
                'cellMargin'  => 50,
            );
            $phpWord->addTableStyle('myTable', $tableStyle);
            $table = $footer->addTable('myTable');
            
            $table->addRow();
            $cell = $table->addCell(80000, ['gridSpan' => 2]);
            $textRun = $cell->addTextRun(['alignment' => 'center']);
            $textRun->addText($Nombre_afiliado_corre." - ".$Tipo_documento_afi." "."(".$Iden_afiliado_corre.")".' - Siniestro '."(".$Id_Evento_pronuncia_corre.")", array('size' => 12, 'bold' => true));
            $table->addRow();
            $table->addCell(80000, ['gridSpan' => 2])->addText($footer_dato_1, array('size' => 10));
            $table->addRow();
            $table->addCell()->addText($footer_dato_2, array('size' => 10));
            $cell1 = $table->addCell();
            $textRun = $cell1->addTextRun(['alignment' => 'right']);
            $textRun->addText($footer_dato_3, array('size' => 10));
            $table->addRow();
            $table->addCell(80000, ['gridSpan' => 2])->addText($footer_dato_4, array('size' => 10));
            $table->addRow();
            $table->addCell(80000, ['gridSpan' => 2])->addText($footer_dato_5, array('size' => 10));
            $table->addRow();
            $cell2 = $table->addCell(80000, ['gridSpan' => 2]);
            $textRun = $cell2->addTextRun(['alignment' => 'center']);
            $textRun->addText('Página ');
            $textRun->addField('PAGE');

            // Generamos el documento y luego se guarda
            $writer = new Word2007($phpWord);
            $nombre_docx = "PCL_DESACUERDO_{$Asignacion_Pronuncia_corre}_{$Iden_afiliado_corre}.docx";
            $writer->save(public_path("Documentos_Eventos/{$Id_Evento_pronuncia_corre}/{$nombre_docx}"));
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