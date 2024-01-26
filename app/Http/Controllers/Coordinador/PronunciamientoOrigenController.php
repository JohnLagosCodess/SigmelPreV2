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
        ->select('pr.ID_evento','pr.Id_primer_calificador','c.Tipo_Entidad','pr.Id_nombre_calificador','e.Nombre_entidad'
        ,'pr.Nit_calificador','pr.Dir_calificador','pr.Email_calificador','pr.Telefono_calificador','pr.Depar_calificador','pr.Ciudad_calificador'
        ,'pr.Id_tipo_pronunciamiento','p.Nombre_parametro as Tpronuncia','pr.Id_tipo_evento','ti.Nombre_evento','pr.Id_tipo_origen','or.Nombre_parametro as T_origen'
        ,'pr.Fecha_evento','pr.Dictamen_calificador','pr.Fecha_calificador','pr.Fecha_estruturacion','pr.Porcentaje_pcl','pr.Rango_pcl'
        ,'pr.Decision','pr.Fecha_pronuncia','pr.Asunto_cali','pr.Sustenta_cali','pr.Destinatario_principal','pr.Tipo_entidad','pr.Copia_afiliado','pr.copia_empleador','pr.Copia_eps'
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

        return view('coordinador.pronunciamientoOrigenATEL', compact('user','array_datos_pronunciamientoOrigen','info_pronuncia','array_datos_diagnostico_motcalifi','consecutivo'));
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
    public function guardarInfoServiPronunciaOrigen(Request $request){
    
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
            'CIE10','Nombre_CIE10','Origen_CIE10',
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
            ->where([
                ['ID_evento', $Id_EventoPronuncia],
                ['Id_Asignacion', $Id_Asignacion_Pronuncia]
            ])->update($datos_info_pronunciamiento_eventos);
            sleep(2);
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

    /* Descargue de proforma de Acuerdo o Desacuerdo */
    public function DescargarProformaPronunciamiento(Request $request){
        $time = time();
        $date = date("Y-m-d", $time);

        /* Captura de variables que vienen del ajax */
        $bandera_tipo_proforma = $request->bandera_tipo_proforma;
        $ciudad = $request->ciudad;
        $fecha = $request->fecha;
        $nro_radicado = $request->nro_radicado;
        $tipo_identificacion = $request->tipo_identificacion;
        $num_identificacion = $request->num_identificacion;
        $nro_siniestro = $request->nro_siniestro;
        $nombre_afiliado = $request->nombre_afiliado;
        $direccion_afiliado = $request->direccion_afiliado;
        $telefono_afiliado = $request->telefono_afiliado;
        $origen = "<b>".$request->origen."</b>";
        $asunto = strtoupper($request->asunto);
        $sustentacion = $request->sustentacion;
        $Id_Asignacion_consulta_dx = $request->Id_Asignacion_consulta_dx;
        $Id_Proceso_consulta_dx = $request->Id_Proceso_consulta_dx;
        $copia_afiliado = $request->copia_afiliado;
        $copia_empleador = $request->copia_empleador;
        $copia_eps = $request->copia_eps;
        $copia_afp = $request->copia_afp;
        $copia_arl = $request->copia_arl;
        $firmar = $request->firmar;
        $Id_cliente_firma = $request->Id_cliente_firma;
        $nombre_entidad = $request->nombre_entidad;
        $direccion_entidad = $request->direccion_entidad;
        $telefono_entidad = $request->telefono_entidad;
        $ciudad_entidad = $request->ciudad_entidad;
        $departamento_entidad = $request->departamento_entidad;

        /* Creación de las variables faltantes que no están en el ajax */
        $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
        ->select('sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
        ->where([['siae.ID_evento','=', $nro_siniestro]])
        ->get();

        $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);

        $nombre_departamento_afiliado = $array_datos_municipio_ciudad_afiliado[0]["Nombre_departamento"];
        $nombre_municipio_afiliado = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"];

        $dato_fecha_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('F_evento')
        ->where([['ID_evento', $nro_siniestro]])
        ->get();

        $array_datos_fecha_evento = json_decode(json_encode($dato_fecha_evento), true);

        $fecha_evento = $array_datos_fecha_evento[0]["F_evento"];

        // TRAER DATOS CIE10 (Diagnóstico motivo de calificación)
        $diagnosticos_cie10 = array();
        $datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->select('side.Nombre_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->where([
            ['side.Estado', '=', 'Activo'],
            ['side.ID_evento', '=', $nro_siniestro],
            ['side.Id_Asignacion', '=', $Id_Asignacion_consulta_dx]
        ])
        ->get(); 
        
        $array_datos_diagnostico_motcalifi = json_decode(json_encode($datos_diagnostico_motcalifi), true);

        for ($i=0; $i < count($array_datos_diagnostico_motcalifi); $i++) { 
            array_push($diagnosticos_cie10, $array_datos_diagnostico_motcalifi[$i]["Nombre_CIE10"]);
        }

        // Contar la cantidad de elementos en el array
        $totalElementos = count($diagnosticos_cie10);

        // Inicializar la cadena de resultado
        $string_diagnosticos_cie10 = '';

        // Recorrer el array
        foreach ($diagnosticos_cie10 as $indice => $elemento) {
            // Verificar si es el último elemento
            if ($indice == $totalElementos - 1) {
                // Si es el último, añadir solo el elemento sin coma
                $string_diagnosticos_cie10 .= $elemento;
            } elseif ($indice == $totalElementos - 2) {
                // Si es el antepenúltimo, añadir "y" en lugar de ","
                $string_diagnosticos_cie10 .= $elemento . " y ";
            } else {
                // Para cualquier otro elemento, añadir ","
                $string_diagnosticos_cie10 .= $elemento . ", ";
            }
        };

        $string_diagnosticos_cie10 = "<b>".$string_diagnosticos_cie10."</b>";


        /* Copias Interesadas */
        // Validamos si los checkbox esta marcados
        $final_copia_afiliado = isset($copia_afiliado) ? 'Afiliado' : '';
        $final_copia_empleador = isset($copia_empleador) ? 'Empleador' : '';
        $final_copia_eps = isset($copia_eps) ? 'EPS' : '';
        $final_copia_afp = isset($copia_afp) ? 'AFP' : '';
        $final_copia_arl = isset($copia_arl) ? 'ARL' : '';

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
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $nro_siniestro]])
            ->get();
            $afiliadoEmail = $emailAfiliado[0]->Email;            
            $Agregar_copias['Afiliado'] = $afiliadoEmail;            
        }

        if(isset($copia_empleador)){

            $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['sile.Nro_identificacion', $num_identificacion],['sile.ID_evento', $nro_siniestro]])
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
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $nro_siniestro]])
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
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $nro_siniestro]])
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
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $nro_siniestro]])
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

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
            $ruta_logo = "/logos_clientes/{$Id_cliente_firma}/{$logo_header}";
        } else {
            $logo_header = "Sin logo";
            $ruta_logo = "";
        }
        
        if ($bandera_tipo_proforma == "proforma_acuerdo") {
            $datos_finales_proforma = [
                'logo_header' => $logo_header,
                'id_cliente' => $Id_cliente_firma,
                'ciudad' => $ciudad,
                'fecha' => $fecha,
                'nombre_afiliado' => $nombre_afiliado,
                'direccion_afiliado' => $direccion_afiliado,
                'telefonos_afiliado' => $telefono_afiliado,
                'municipio_afiliado' => $nombre_municipio_afiliado,
                'departamento_afiliado' => $nombre_departamento_afiliado,
                'asunto' => $asunto,
                'cuerpo' => $sustentacion,
                'nro_radicado' => $nro_radicado,
                'tipo_identificacion' => $tipo_identificacion,
                'num_identificacion' => $num_identificacion,
                'nro_siniestro' => $nro_siniestro,
                'identificacion' => $num_identificacion,
                'fecha_evento' => $fecha_evento,
                'string_diagnosticos_cie10' => $string_diagnosticos_cie10,
                'origen' => $origen,
                'Firma_cliente' => $Firma_cliente,
                'Agregar_copia' => $Agregar_copias,
                'nombre_usuario' => Auth::user()->name
            ];
            

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Arl/Origen_Atel/acuerdo_calificacion', $datos_finales_proforma);
            
            $nombre_pdf = "ORI_ACUERDO_{$Id_Asignacion_consulta_dx}_{$num_identificacion}.pdf";
    
            //Obtener el contenido del PDF
            $output = $pdf->output();
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$nro_siniestro}/{$nombre_pdf}"), $output);
    
            return $pdf->download($nombre_pdf); 
        } else {

            $fecha_radicado_alfa = "N/A";

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
            $section->addText('Bogotá D.C, '.$fecha, array('bold' => true));
            $section->addTextBreak();
            $htmltabla1 = '<table align="justify" style="width: 100%; border: none;">
                <tr>
                    <td>
                        <p><b>Señores:</b>
                            <br>'.
                            $nombre_entidad.'</br>
                        </p>
                        <p><b>Dirección:</b>
                            <br>'.
                            $direccion_entidad.'</br>
                        </p>
                        <p><b>Teléfono:</b>
                            <br>'.
                            $telefono_entidad.'</br>
                        </p>
                        <p><b>Ciudad:</b>
                            <br>'.
                            $ciudad_entidad.' - '.$departamento_entidad.'</br>
                        </p>
                    </td>
                    <td>
                        <br></br>
                        <table style="width: 60%; border: 3px black solid;">
                            <tr>
                                <td>
                                    <p><b>Nro. Radicado: '.$nro_radicado.'</b></p>  
                                    <p><b>'.$tipo_identificacion." ".$num_identificacion.'</b></p>
                                    <p><b>Siniestro: '.$nro_siniestro.'</b></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';
        
            Html::addHtml($section, $htmltabla1, false, true);

            $section->addText('Asunto: '.$asunto, array('bold' => true));
            $section->addText('Afiliado: '.$nombre_afiliado." ".$tipo_identificacion." ".$num_identificacion, array('bold' => true));
            $section->addText('Radicado en Alfa:  '.$fecha_radicado_alfa, array('bold' => true));

            // Configuramos el reemplazo de la variable de los cie 10
            $patron1 = '/\{\{\$diagnosticos_cie10\}\}/';
            if (preg_match($patron1, $sustentacion)) {
                $texto_modificado = str_replace('{{$diagnosticos_cie10}}', $string_diagnosticos_cie10, $sustentacion);
                $texto_modificado = str_replace('</p>', '</p><br></br>', $texto_modificado);
                $cuerpo = $texto_modificado;
            } else {
                $cuerpo = "";
            }

            $section->addTextBreak();
            Html::addHtml($section, $cuerpo, false, true);
            $section->addTextBreak();
            $section->addText('Cordialmente,');
            $section->addTextBreak();

            // Agregar </img> en la imagen de la firma
            $patronetiqueta = '/<img(.*?)>/';
            $Firma_cliente = preg_replace($patronetiqueta, '<img$1></img>', $Firma_cliente);
            
            // Quitamos el style y agregamos los atributos width y height
            $patronstyle = '/<img[^>]+style="width:\s*([\d.]+)px;\s*height:\s*([\d.]+)px[^"]*"[^>]*>/';
            preg_match($patronstyle, $Firma_cliente, $coincidencias);
            $width = $coincidencias[1]; // Valor de width
            $height = $coincidencias[2]; // Valor de height
        
            $nuevoStyle = 'width="'.$width.'" height="'.$height.'"';
            $htmlModificado = reemplazarStyleImg($Firma_cliente, $nuevoStyle);
            
            Html::addHtml($section, $htmlModificado, false, true);
            $section->addTextBreak();
            $section->addText('Dirección de Servicios Médicos de Seguridad Social', array('bold' => true));
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
            $section->addTextBreak();
            $texto_html_gris = '“Finalmente, reiteramos que en nuestra Compañía contamos con la mejor disposición para atender sus quejas y reclamos a través del defensor consumidor financiero, en la Av. Calle 26 No 59-15, local 6 y 7. Conmutador: 7435333 Extensión: 14454, Fax Ext. 14456 o Correo Electrónico: defensor del consumidor financiero@segurosdevidaalfa.com.co”.';
            $color_hex = '#828282';
            $final_gris = '<p style="color:' . $color_hex . ';">' . $texto_html_gris . '</p>';
            Html::addHtml($section, $final_gris, false, true);

            // Configuramos el footer
            $footer = $section->addFooter();
            $tableStyle = array(
                'cellMargin'  => 50,
            );
            $phpWord->addTableStyle('myTable', $tableStyle);
            $table = $footer->addTable('myTable');

            $table->addRow();
            $table->addCell(80000, ['gridSpan' => 2])->addText('Seguros Alfa S.A. y Seguros de Vida Alfa S.A.', array('size' => 10, 'color' => '#184F56', 'bold' => true));
            $table->addRow();
            $table->addCell()->addText('Líneas de atención al cliente', array('size' => 10, 'color' => '#184F56', 'bold' => true));
            $cell = $table->addCell();
            $textRun = $cell->addTextRun(['alignment' => 'right']);
            $textRun->addText('www.segurosalfa.com.co', array('size' => 10, 'color' => '#184F56', 'bold' => true));
            $table->addRow();
            $table->addCell(80000, ['gridSpan' => 2])->addText('Bogotá: 3077032, a nivel nacional: 018000122532', array('size' => 10));
            $table->addRow();
            $table->addCell(80000, ['gridSpan' => 2])->addText('Habilitadas en jornada continua de lunes a viernes de 8:00 a.m. a 6:00 p.m.', array('size' => 10));
            $table->addRow();
            $cell1 = $table->addCell(80000, ['gridSpan' => 2]);
            $textRun = $cell1->addTextRun(['alignment' => 'center']);
            $textRun->addText('Página ');
            $textRun->addField('PAGE');

            // Generamos el documento y luego se guarda
            $writer = new Word2007($phpWord);
            $nombre_docx = "ORI_DESACUERDO_{$Id_Asignacion_consulta_dx}_{$num_identificacion}.docx";
            $writer->save(public_path("Documentos_Eventos/{$nro_siniestro}/{$nombre_docx}"));
            return response()->download(public_path("Documentos_Eventos/{$nro_siniestro}/{$nombre_docx}"));
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
