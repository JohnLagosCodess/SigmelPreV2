<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_lista_cie_diagnosticos;
use App\Models\sigmel_informacion_examenes_interconsultas_eventos;
use App\Models\sigmel_informacion_dto_atel_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_informacion_pericial_eventos;
use App\Models\sigmel_informacion_eventos;
use App\Models\cndatos_eventos;
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_lista_regional_juntas;
use App\Models\sigmel_clientes;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_lista_solicitantes;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_accion_eventos;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Dompdf\Dompdf;
use Dompdf\Options;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class DeterminacionOrigenATEL extends Controller
{
    public function mostrarVistaDtoATEL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $Id_evento_dto_atel = $request->Id_evento_calitec;
        $Id_asignacion_dto_atel = $request->Id_asignacion_calitec;
        $Id_proceso_dto_atel = $request->Id_proceso_calitec;

        $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion_dto_atel));


        // $consecutivo_dto_atel = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
        // ->max('Numero_dictamen');
        
        // if ($consecutivo_dto_atel > 0) {
        //     $numero_consecutivo = $consecutivo_dto_atel + 1;
        // }else{
        //     $numero_consecutivo = 0000000 + 1;
        // }

        // // Formatear el número consecutivo a 7 dígitos
        // $numero_consecutivo = str_pad($numero_consecutivo, 7, "0", STR_PAD_LEFT);

        // Obtenemos la informaciópn de  la tabla sigmel_informacion_dto_atel_eventos
        $datos_bd_DTO_ATEL = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $Id_evento_dto_atel)->get();

        // obtenemos el nombre del evento
        if (count($datos_bd_DTO_ATEL) > 0) {
            $id_evento_guardado_dto_atel = $datos_bd_DTO_ATEL[0]->Tipo_evento;
            $array_nombre_del_evento_guardado = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Nombre_evento')
            ->where('Id_Evento', $id_evento_guardado_dto_atel)->get();
            $nombre_del_evento_guardado = $array_nombre_del_evento_guardado[0]->Nombre_evento;
        }else{
            $nombre_del_evento_guardado = "";
        }
        

        //Traer Motivo de solicitud,
        $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
        ->select('Id_motivo_solicitud','Nombre_solicitud')
        ->where('ID_evento', $Id_evento_dto_atel)
        ->get();

        //Traer Información apoderado 
        $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('Nombre_apoderado','Nro_identificacion_apoderado')
        ->where('ID_evento', $Id_evento_dto_atel)
        ->get();

        // Traer Información laboral
        $array_datos_info_laboral=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as sla', 'sla.Id_arl', '=', 'sile.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'sile.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldms', 'sldms.Id_municipios', '=', 'sile.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'sile.Id_actividad_economica')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'sile.Id_clase_riesgo')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
        ->select('sile.ID_evento', 'sile.Tipo_empleado','sile.Id_arl', 'sla.Nombre_arl', 'sile.Empresa', 'sile.Nit_o_cc', 'sile.Telefono_empresa',
        'sile.Email', 'sile.Direccion', 'sile.Id_departamento', 'sldm.Nombre_departamento', 'sile.Id_municipio', 
        'sldms.Nombre_municipio', 'sile.Id_actividad_economica', 'slae.Nombre_actividad', 'sile.Id_clase_riesgo', 
        'slcr.Nombre_riesgo', 'sile.Persona_contacto', 'sile.Telefono_persona_contacto', 'sile.Id_codigo_ciuo', 'slcc.Nombre_ciuo', 
        'sile.F_ingreso', 'sile.Cargo', 'sile.Funciones_cargo', 'sile.Antiguedad_empresa', 'sile.Antiguedad_cargo_empresa', 
        'sile.F_retiro', 'sile.Descripcion')
        ->where([['sile.ID_evento','=', $Id_evento_dto_atel]])
        ->orderBy('sile.F_registro', 'desc')
        ->limit(1)
        ->get();

        //Trae Documentos Solicitados del proceso origen solamente
        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where([
            ['ID_evento',$Id_evento_dto_atel],
            ['Estado','Activo'],
            ['Id_proceso','1']
         ])
        ->get();

        //Trae si ya marco Articulo 12
        $dato_articulo_12= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_documentos_solicitados_eventos')
       ->select('Articulo_12')
       ->where([
                ['ID_evento', $Id_evento_dto_atel],
                ['Id_Asignacion', $Id_asignacion_dto_atel], 
                ['Id_proceso', '1'], 
                ['Articulo_12','=','No_mas_seguimiento']
            ])
        ->orderBy('Id_Documento_Solicitado', 'desc')
        ->limit(1)
        ->get();

        // TRAER DATOS EXAMENES E INTERCONSULTAS
        $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_dto_atel],
            ['Id_Asignacion',$Id_asignacion_dto_atel],
            ['Id_proceso',$Id_proceso_dto_atel],
            ['Estado', 'Activo']
        ])
        ->get();

        // TRAER DATOS CIE10 (Diagnóstico motivo de calificación)
        $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
        ->where([['side.ID_evento',$Id_evento_dto_atel],
            ['side.Id_Asignacion',$Id_asignacion_dto_atel],
            ['side.Id_proceso',$Id_proceso_dto_atel],
            ['side.Estado', '=', 'Activo']
        ])->get(); 

        // echo "<pre>";
        // print_r($array_datos_diagnostico_motcalifi);
        // echo "</pre>";
       
        // TRAER DATOS DE HISTORICO LABORAL (APLICA SOLAMENTE PARA EL FORMULARIO DE ENFERMEDAD)
        $array_datos_historico_laboral = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_historico_empresas_afiliados as shea')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as slarl', 'slarl.Id_Arl', '=', 'shea.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'shea.Id_departamento') 
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sldm1.Id_municipios', '=', 'shea.Id_municipio') 
        ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'shea.Id_actividad_economica')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'shea.Id_clase_riesgo')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'shea.Id_codigo_ciuo')
        ->select(
            'shea.Tipo_empleado',
            'shea.Id_arl',
            'slarl.Nombre_arl',
            'shea.Empresa',
            'shea.Nit_o_cc',
            'shea.Telefono_empresa',
            'shea.Email',
            'shea.Direccion',
            'shea.Id_departamento',
            'sldm.Nombre_departamento',
            'shea.Id_municipio',
            'sldm1.Nombre_municipio',
            'shea.Id_actividad_economica',
            'slae.id_codigo',
            'slae.Nombre_actividad',
            DB::raw("CONCAT(slae.id_codigo,' - ',slae.Nombre_actividad) as full_actividad_economica"),
            'shea.Id_clase_riesgo',
            'slcr.Nombre_riesgo',
            'shea.Persona_contacto',
            'shea.Telefono_persona_contacto',
            'shea.Id_codigo_ciuo',
            'slcc.id_codigo_ciuo',
            'slcc.Nombre_ciuo',
            DB::raw("CONCAT(slcc.id_codigo_ciuo,' - ',slcc.Nombre_ciuo) as full_ciuo"),
            'shea.F_ingreso',
            'shea.Cargo',
            'shea.Funciones_cargo',
            'shea.Antiguedad_empresa',
            'shea.Antiguedad_cargo_empresa',
            'shea.F_retiro',
            'shea.Descripcion'
        )
        ->where([
            ['shea.Nro_identificacion', '=', $array_datos_calificacion_origen[0]->Nro_identificacion]
        ])
        ->distinct()
        ->get();

        $array_comite_interdisciplinario = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_dto_atel],
            ['Id_Asignacion',$Id_asignacion_dto_atel]
        ])
        ->get(); 

        // creación de consecutivo para el comunicado
        $radicadocomunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->select('N_radicado')
        ->where([
            ['ID_evento',$Id_evento_dto_atel],
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

        $array_comunicados_correspondencia = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$Id_evento_dto_atel], ['Id_Asignacion',$Id_asignacion_dto_atel], ['T_documento','N/A']])->get();  

        return view('coordinador.determinacionOrigenATEL', compact('user', 'array_datos_calificacion_origen', 
        'motivo_solicitud_actual', 'datos_apoderado_actual', 
        'array_datos_info_laboral', 'listado_documentos_solicitados', 
        'dato_articulo_12', 'array_datos_diagnostico_motcalifi',
        'array_datos_examenes_interconsultas', 'array_datos_historico_laboral', 'datos_bd_DTO_ATEL', 'nombre_del_evento_guardado','array_comite_interdisciplinario', 'consecutivo', 'array_comunicados_correspondencia'));
    }

    public function cargueListadoSelectoresDTOATEL(Request $request){
        $parametro = $request->parametro;

        if ($parametro == "tipo_de_evento_si") {
            $listado_tipos_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Id_Evento', 'Nombre_evento')
            ->where('Estado', 'activo')
            ->whereNotIn('Nombre_evento', ['Sin Cobertura'])
            ->get();

            $info_tipos_evento = json_decode(json_encode($listado_tipos_evento, true));
            return response()->json($info_tipos_evento);
        }
        if ($parametro == "tipo_de_evento_no") {
            $listado_tipos_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Id_Evento', 'Nombre_evento')
            ->where('Estado', 'activo')
            ->whereNotIn('Id_Evento', [1,2,3])
            ->get();

            $info_tipos_evento = json_decode(json_encode($listado_tipos_evento, true));
            return response()->json($info_tipos_evento);
        }
        if ($parametro == "motivo_solicitud") {
            $listado_motivos_solicitud = sigmel_lista_motivo_solicitudes::on('sigmel_gestiones')
            ->select('Id_Solicitud', 'Nombre_solicitud')
            ->where('Estado', 'activo')
            ->get();

            $info_motivos_solicitud = json_decode(json_encode($listado_motivos_solicitud, true));
            return response()->json($info_motivos_solicitud);
        }

        if($parametro == "tipo_accidente"){
            $listado_tipos_accidente = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([['Tipo_lista', '=', 'Tipo de accidiente'], ['Estado', '=' ,'activo']])
            ->get();

            $info_tipos_accidente = json_decode(json_encode($listado_tipos_accidente, true));
            return response()->json($info_tipos_accidente);
        }

        if($parametro == "grado_severidad"){
            $listado_grado_severidad = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([['Tipo_lista', '=', 'Grado de Severidad'], ['Estado', '=' ,'activo']])
            ->get();

            $info_grado_severidad = json_decode(json_encode($listado_grado_severidad, true));
            return response()->json($info_grado_severidad);
        }

        if ($parametro == "factor_riesgo") {
            $listado_factor_riesgo = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([['Tipo_lista', '=', 'Factor de Riesgo'], ['Estado', '=' ,'activo']])
            ->get();

            $info_factor_riesgo = json_decode(json_encode($listado_factor_riesgo, true));
            return response()->json($info_factor_riesgo);
        }

        if ($parametro == "tipo_lesion") {
            $listado_tipo_lesion = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([['Tipo_lista', '=', 'Tipo de Lesion'], ['Estado', '=' ,'activo']])
            ->get();
        
            $info_tipo_lesion = json_decode(json_encode($listado_tipo_lesion, true));
            return response()->json($info_tipo_lesion);
        }

        if ($parametro == "parte_cuerpo_afectada") {
            $listado_parte_cuerpo_afectada = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([['Tipo_lista', '=', 'Parte Cuerpo Afectada'], ['Estado', '=' ,'activo']])
            ->get();
        
            $info_parte_cuerpo_afectada = json_decode(json_encode($listado_parte_cuerpo_afectada, true));
            return response()->json($info_parte_cuerpo_afectada);
        }

        // Listado cie diagnosticos motivo calificacion
        if ($parametro == 'listado_CIE10') {
            $listado_cie_diagnostico = sigmel_lista_cie_diagnosticos::on('sigmel_gestiones')
            ->select('Id_Cie_diagnostico', 'CIE10', 'Descripcion_diagnostico')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_cie_diagnostico = json_decode(json_encode($listado_cie_diagnostico, true));
            return response()->json($info_listado_cie_diagnostico);
        }

        // Listado Origen CIE10 diagnosticos motivo calificacion
        if ($parametro == 'listado_OrigenCIE10') {
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

        // Listado Lateralidad CIE10 diagnosticos motivo calificacion
        if ($parametro == 'listado_LateralidadCIE10') {
            $listado_Lateralidad_CIE10 = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Lateralidad Cie10'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Lateralidad_CIE10 = json_decode(json_encode($listado_Lateralidad_CIE10, true));
            return response()->json($info_listado_Lateralidad_CIE10);
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

        // Selector Origen con tipo de evento: Accidente
        if ($parametro == "origen_vali_1") {
            $listado_origen_vali_1 = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Origen DTO ATEL'],
                ['Estado', '=', 'activo']
            ])
            ->whereNotIn('Nombre_parametro', ['Incidente', 'Sin Cobertura'])
            ->get();
            $info_origen_vali_1 = json_decode(json_encode($listado_origen_vali_1, true));
            return response()->json($info_origen_vali_1);
        }

        // Selector Origen con tipo de evento: Incidente
        if ($parametro == "origen_vali_2") {
            $listado_origen_vali_2 = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Origen DTO ATEL'],
                ['Estado', '=', 'activo']
            ])
            ->whereNotIn('Nombre_parametro', ['Común', 'Laboral', 'Sin Origen', 'Sin Cobertura'])
            ->get();
            $info_origen_vali_2 = json_decode(json_encode($listado_origen_vali_2, true));
            return response()->json($info_origen_vali_2);
        }

        // Selector Origen con tipo de evento: Sin Cobertura
        if ($parametro == "origen_vali_3") {
            $listado_origen_vali_3 = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Origen DTO ATEL'],
                ['Estado', '=', 'activo']
            ])
            ->whereNotIn('Nombre_parametro', ['Común', 'Laboral', 'Sin Origen', 'Incidente'])
            ->get();
            $info_origen_vali_3 = json_decode(json_encode($listado_origen_vali_3, true));
            return response()->json($info_origen_vali_3);
        }

        // listado destinatario
        if($parametro == 'listado_destinatarios'){
            $listado_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')
                ->select('Id_solicitante', 'Solicitante')
                ->whereIn('Solicitante', ['ARL','AFP','EPS','Afiliado','Empleador','Otro'])
                ->groupBy('Id_solicitante','Solicitante')
                ->get();

            $info_listado_solicitante = json_decode(json_encode($listado_solicitante, true));
            return response()->json(($info_listado_solicitante));
        }

        // listaoo nombre de destinatario
        if($parametro == "nombre_destinatariopri"){
            /* $listado_nombre_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')
            ->select('Id_Nombre_solicitante', 'Nombre_solicitante')
            ->where([
                ['Id_solicitante', '=', $request->id_solicitante],
                ['Estado', '=', 'activo']
            ])
            ->get(); */

            $listado_nombre_solicitante = sigmel_informacion_entidades::on('sigmel_gestiones')
            ->select('Id_Entidad as Id_Nombre_solicitante', 'Nombre_entidad as Nombre_solicitante')
            ->where([
                ['IdTipo_entidad', '=', $request->id_solicitante],
                ['Estado_entidad', '=', 'activo']
            ])
            ->get();


            $info_listado_nombre_solicitante = json_decode(json_encode($listado_nombre_solicitante, true));
            return response()->json(($info_listado_nombre_solicitante));
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

        //Lista Lider de procesos
        if($parametro == "lista_reviso"){
            $array_datos_reviso =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
            ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
            ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
            ->where([['sgt.Id_proceso_equipo', '=', $request->idProcesoLider]])->get();

            $informacion_datos_reviso = json_decode(json_encode($array_datos_reviso, true));
            return response()->json($informacion_datos_reviso);
        }  

    }

    public function GuardaroActualizarInfoDTOTAEL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
        $datetime = date("Y-m-d H:i:s");
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        // Paso N°1: Actualizar el motivo de solicitud y tipo de evento
        $datos_actualizar_motivo_solicitud = [
            'Id_motivo_solicitud' => $request->motivo_solicitud
        ];

        sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $request->ID_Evento)->update($datos_actualizar_motivo_solicitud);

        $datos_actualizar_tipo_evento = [
            'Tipo_evento' => $request->Tipo_evento
        ];

        sigmel_informacion_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $request->ID_Evento)->update($datos_actualizar_tipo_evento);

        // Paso N°2: Guardar los datos de Examenes interconsultas

        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
        $max_id = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->max('Id_Examenes_interconsultas');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_examenes_interconsultas_eventos AUTO_INCREMENT = ".($max_id));
        }

        if (!empty($request->Examenes_interconsultas)) {
            if (count($request->Examenes_interconsultas) > 0) {
                // Captura del array de los datos de la tabla
                $array_examenes_interconsultas = $request->Examenes_interconsultas;
    
                // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
                $array_datos_organizados_examenes_interconsultas = [];
                foreach ($array_examenes_interconsultas as $subarray_datos) {
    
                    array_unshift($subarray_datos, $request->Id_proceso);
                    array_unshift($subarray_datos, $request->Id_Asignacion);
                    array_unshift($subarray_datos, $request->ID_Evento);
    
                    $subarray_datos[] = $nombre_usuario;
                    $subarray_datos[] = $date;
    
                    array_push($array_datos_organizados_examenes_interconsultas, $subarray_datos);
                }
    
                // Creación de array con los campos de la tabla: sigmel_informacion_examenes_interconsultas_eventos
                $array_tabla_examen_interconsulta = ['ID_evento','Id_Asignacion','Id_proceso',
                'F_examen_interconsulta','Nombre_examen_interconsulta','Descripcion_resultado',
                'Nombre_usuario','F_registro'];
    
                // Combinación de los campos de la tabla con los datos
                $array_datos_con_keys_examenes_interconsultas = [];
                foreach ($array_datos_organizados_examenes_interconsultas as $subarray_datos_organizados_examenes_interconsultas) {
                    array_push($array_datos_con_keys_examenes_interconsultas, array_combine($array_tabla_examen_interconsulta, $subarray_datos_organizados_examenes_interconsultas));
                }
    
                // Inserción de la información
                foreach ($array_datos_con_keys_examenes_interconsultas as $insertar_examen) {
                    sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')->insert($insertar_examen);
                } 
            }
        }

        // Paso N°3: Guardar los datos de Diagnosticos motivo de calificacion

        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
        $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->max('Id_Diagnosticos_motcali');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
        }

        if (!empty($request->Motivo_calificacion)) {
            if (count($request->Motivo_calificacion) > 0) {
                // Captura del array de los datos de la tabla
                $array_diagnosticos_motivo_calificacion = $request->Motivo_calificacion;
                $array_datos_organizados_motivo_calificacion = [];
                foreach ($array_diagnosticos_motivo_calificacion as $subarray_datos_motivo_calificacion) {
    
                    array_unshift($subarray_datos_motivo_calificacion, $request->Id_proceso);
                    array_unshift($subarray_datos_motivo_calificacion, $request->Id_Asignacion);
                    array_unshift($subarray_datos_motivo_calificacion, $request->ID_Evento);
    
                    $subarray_datos_motivo_calificacion[] = $nombre_usuario;
                    $subarray_datos_motivo_calificacion[] = $date;
    
                    array_push($array_datos_organizados_motivo_calificacion, $subarray_datos_motivo_calificacion);
                }
    
                // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
                $array_tabla_diagnosticos_motivo_calificacion = ['ID_evento','Id_Asignacion','Id_proceso',
                'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
                'Principal', 'Nombre_usuario','F_registro'];
                // Combinación de los campos de la tabla con los datos
                $array_datos_con_keys_motivo_calificacion = [];
                foreach ($array_datos_organizados_motivo_calificacion as $subarray_datos_organizados_motivo_calificacion) {
                    array_push($array_datos_con_keys_motivo_calificacion, array_combine($array_tabla_diagnosticos_motivo_calificacion, $subarray_datos_organizados_motivo_calificacion));
                }
    
                // Inserción de la información
                foreach ($array_datos_con_keys_motivo_calificacion as $insertar_diagnostico) {
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico);
                }
            }
        }

        // Paso N° 4: Guardar los datos del formulario dto_atel
        $Tipo_evento = $request->Tipo_evento;

        if (!empty($request->Relacion_documentos)) {
            $total_relacion_documentos = implode(", ", $request->Relacion_documentos);                
        }else{
            $total_relacion_documentos = '';
        }

        // Tipo de formulario: Accidente, Incidente, Sin Cobertura
        if ($Tipo_evento == 1 || $Tipo_evento == 3 || $Tipo_evento == 4) {
            $datos_formulario = [
                'ID_Evento' => $request->ID_Evento,
                'Id_Asignacion' => $request->Id_Asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Activo' => $request->Activo,
                'Tipo_evento' => $request->Tipo_evento,
                'Fecha_dictamen' => $request->Fecha_dictamen,
                'Numero_dictamen' => $request->Numero_dictamen,
                'Tipo_accidente' => $request->Tipo_accidente,
                'Fecha_evento' => $request->Fecha_evento,
                'Hora_evento' => $request->Hora_evento,
                'Grado_severidad' => $request->Grado_severidad,
                'Mortal' => $request->Mortal,
                'Fecha_fallecimiento' => $request->Fecha_fallecimiento,
                'Descripcion_FURAT' => $request->Descripcion_FURAT,
                'Factor_riesgo' => $request->Factor_riesgo,
                'Tipo_lesion' => $request->Tipo_lesion,
                'Parte_cuerpo_afectada' => $request->Parte_cuerpo_afectada,
                'Justificacion_revision_origen' => $request->Justificacion_revision_origen,
                'Relacion_documentos' => $total_relacion_documentos,
                'Otros_relacion_documentos' => $request->Otros_relacion_documentos,
                'Sustentacion' => $request->Sustentacion,
                'Origen' => $request->Origen,
                'N_radicado' => $request->radicado_dictamen,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

        }
        // Tipo de Formulario: Enfermedad
        else if ($Tipo_evento == 2) {
            $datos_formulario = [
                'ID_Evento' => $request->ID_Evento,
                'Id_Asignacion' => $request->Id_Asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Activo' => $request->Activo,
                'Tipo_evento' => $request->Tipo_evento,
                'Fecha_dictamen' => $request->Fecha_dictamen,
                'Numero_dictamen' => $request->Numero_dictamen,
                'Fecha_diagnostico_enfermedad'=>$request->Fecha_diagnostico_enfermedad,
                'Mortal' => $request->Mortal,
                'Fecha_fallecimiento' => $request->Fecha_fallecimiento,
                'Factor_riesgo' => $request->Factor_riesgo,
                'Enfermedad_heredada'=> $request->Enfermedad_heredada,
                'Nombre_entidad_hereda'=> $request->Nombre_entidad_hereda,
                'Justificacion_revision_origen' => $request->Justificacion_revision_origen,
                'Relacion_documentos' => $total_relacion_documentos,
                'Otros_relacion_documentos' => $request->Otros_relacion_documentos,
                'Sustentacion' => $request->Sustentacion,
                'Origen' => $request->Origen,
                'N_radicado' => $request->radicado_dictamen,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
        }

        $Id_Dto_ATEL = $request->Id_Dto_ATEL;
        if ($Id_Dto_ATEL == "") {
            sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')->insert($datos_formulario);

            $datos_info_comunicado_eventos = [
                'ID_Evento' => $request->ID_Evento,
                'Id_proceso' => $request->Id_proceso,
                'Id_Asignacion' => $request->Id_Asignacion,
                'Ciudad' => 'N/A',
                'F_comunicado' => $date,
                'N_radicado' => $request->radicado_dictamen,
                'Cliente' => 'N/A',
                'Nombre_afiliado' => 'N/A',
                'T_documento' => 'N/A',
                'N_identificacion' => 'N/A',
                'Destinatario' => 'N/A',
                'Nombre_destinatario' => 'N/A',
                'Nit_cc' => 'N/A',
                'Direccion_destinatario' => 'N/A',
                'Telefono_destinatario' => '001',
                'Email_destinatario' => 'N/A',
                'Id_departamento' => '001',
                'Id_municipio' => '001',
                'Asunto'=> 'N/A',
                'Cuerpo_comunicado' => 'N/A',
                'Forma_envio' => '0',
                'Elaboro' => $nombre_usuario,
                'Reviso' => 'N/A',
                'Anexos' => 'N/A',
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);

            $mensaje = 'Información guardada satisfactoriamente.';

        }else{
            sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
            ->where('Id_Dto_ATEL', $Id_Dto_ATEL)->update($datos_formulario);
            $mensaje = 'Información actualizada satisfactoriamente.';
        }
        
        // Actualizacion del profesional calificador
        $datos_profesional_calificador = [
            'Id_profesional' => Auth::user()->id,
            'Nombre_profesional' => $nombre_usuario
        ];

        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->where('Id_Asignacion', $request->Id_Asignacion)->update($datos_profesional_calificador);

        sleep(2);
        $datos_info_accion_evento= [    
            'F_calificacion_servicio' => $datetime
        ];

        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $request->ID_Evento)->update($datos_info_accion_evento);
        
        $mensajes = array(
            "parametro" => 'agregar_dto_atel',
            "mensaje" => $mensaje
        ); 

        return json_decode(json_encode($mensajes, true));
    

    }

    public function eliminarExamenInterconsulta(Request $request){
        $id_fila_examen = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_Examenes_interconsultas', $id_fila_examen],
            ['ID_evento', $request->Id_evento],
            ['Id_Asignacion', $request->Id_asignacion],
            ['Id_proceso', $request->Id_proceso],
        ])
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

    public function eliminarDiagnosticoMotivoCalificacion(Request $request){
        $id_fila_diagnostico = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_Diagnosticos_motcali', $id_fila_diagnostico],
            ['ID_evento', $request->Id_evento],
            ['Id_Asignacion', $request->Id_asignacion],
            ['Id_proceso', $request->Id_proceso],
        ])
        ->update($fila_actualizar);

        // Se cambio de Si a No ese Dx Principal
        $fila_actualizar = [
            'Principal' => 'No'
        ];

        sigmel_informacion_diagnosticos_eventos::on("sigmel_gestiones")
        ->where([
            ['Id_Diagnosticos_motcali', $id_fila_diagnostico],
            ['ID_evento', $request->Id_evento],
            ['Id_Asignacion', $request->Id_asignacion],
            ['Id_proceso', $request->Id_proceso]
        ])->update($fila_actualizar);

        $total_registros_diagnostico = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_diagnostico_eliminada',
            'total_registros' => $total_registros_diagnostico,
            "mensaje" => 'Diagnóstico motivo de calificación y Dx Principal eliminados satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function actualizarDxPrincipalDTOATEL(Request $request){
        $bandera = $request->bandera;
        $Id_evento = $request->Id_evento;
        $Id_Asignacion = $request->Id_Asignacion;
        $Id_proceso = $request->Id_proceso;
        $fila = $request->fila;

        if ($bandera == "Si") {
            $fila_actualizar = [
                'Principal' => 'Si'
            ];

            sigmel_informacion_diagnosticos_eventos::on("sigmel_gestiones")
            ->where([
                ['Id_Diagnosticos_motcali', $fila],
                ['ID_evento', $Id_evento],
                ['Id_Asignacion', $Id_Asignacion],
                ['Id_proceso', $Id_proceso],
                ['Estado', 'Activo']
            ])->update($fila_actualizar);

            $mensaje = "Dx Principal agreagado satisfactoriamente.";

        } else {
            $fila_actualizar = [
                'Principal' => 'No'
            ];

            sigmel_informacion_diagnosticos_eventos::on("sigmel_gestiones")
            ->where([
                ['Id_Diagnosticos_motcali', $fila],
                ['ID_evento', $Id_evento],
                ['Id_Asignacion', $Id_Asignacion],
                ['Id_proceso', $Id_proceso],
                ['Estado', 'Activo']
            ])->update($fila_actualizar);

            $mensaje = "Dx Principal eliminado satisfactoriamente.";
        }

        $mensajes = array(
            "parametro" => 'hecho',
            "mensaje" => $mensaje
        );

        return json_decode(json_encode($mensajes, true)); 
    }

    // Comite InterdisciplinarioDTO

    public function guardarcomiteinterdisciplinarioDto(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);
        $Id_Evento_dto_atel = $request->Id_Evento_dto_atel;
        $Id_Proceso_dto_atel = $request->Id_Proceso_dto_atel;
        $Id_Asignacion_dto_atel = $request->Id_Asignacion_dto_atel;
        $visar = $request->visar;
        $profesional_comite = $request->profesional_comite;
        $f_visado_comite = $request->f_visado_comite;

        $datos_comiteInterdisciplinario = [
            'ID_evento' => $Id_Evento_dto_atel,
            'Id_proceso' => $Id_Proceso_dto_atel,
            'Id_Asignacion' => $Id_Asignacion_dto_atel,
            'Visar' => $visar,
            'Profesional_comite' => $profesional_comite,
            'F_visado_comite' => $f_visado_comite,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];
        sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')->insert($datos_comiteInterdisciplinario);            
        $mensajes = array(
            "parametro" => 'insertar_comite_interdisciplinario',
            "mensaje" => 'Comite Interdisciplinario guardado satisfactoriamente.'
        );    
        return json_decode(json_encode($mensajes, true));
    }

    // CorrespondenciaDTO

    public function guardarcorrespondenciaDto(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);

        $Id_Evento_dto_atel = $request->Id_Evento_dto_atel;
        $Id_Proceso_dto_atel = $request->Id_Proceso_dto_atel;
        $Id_Asignacion_dto_atel = $request->Id_Asignacion_dto_atel;
        $oficio_origen = $request->oficio_origen;
        if ($oficio_origen == '') {
            $oficio_origen = 'No';
        }
        // $oficioinca = $request->oficioinca;
        // if($oficioinca == ''){
        //     $oficioinca = 'No';
        // }
        $destinatario_principal = $request->destinatario_principal;
        $otrodestinariop = $request->otrodestinariop;
        $tipo_destinatario_principal = $request->tipo_destinatario_principal;
        $nombre_destinatariopri = $request->nombre_destinatariopri;
        $Nombre_dest_principal_afi_empl = $request->Nombre_dest_principal_afi_empl;
        if ($tipo_destinatario_principal == '') {
            $tipo_destinatario_principal = null;
            $nombre_destinatariopri = null;
            $Nombre_dest_principal_afi_empl = null;
        }
        if($tipo_destinatario_principal != 8){
            $nombre_destinatario = null;
            $nitcc_destinatario = null;
            $direccion_destinatario = null;
            $telefono_destinatario = null;
            $email_destinatario = null;
            $departamento_destinatario = null;
            $ciudad_destinatario = null;
        }else{
            $nombre_destinatario = $request->nombre_destinatario;
            $nitcc_destinatario = $request->nitcc_destinatario;
            $direccion_destinatario = $request->direccion_destinatario;
            $telefono_destinatario = $request->telefono_destinatario;
            $email_destinatario = $request->email_destinatario;
            $departamento_destinatario = $request->departamento_destinatario;
            $ciudad_destinatario = $request->ciudad_destinatario;
        }
        $Asunto = $request->Asunto;
        $cuerpo_comunicado = $request->cuerpo_comunicado;
        $empleador = $request->empleador;
        $eps = $request->eps;
        $afp = $request->afp;
        $arl = $request->arl;
        $jrci = $request->jrci;        
        $cual = $request->cual;
        if($cual == ''){
            $cual = null;
        }
        $jnci = $request->jnci;
        $agregar_copias_comu = $empleador.','.$eps.','.$afp.','.$arl.','.$jrci.','.$jnci;
        $anexos = $request->anexos;
        $elaboro = $request->elaboro;
        $reviso = $request->reviso;
        $firmar = $request->firmar;
        $ciudad = $request->ciudad;
        // $f_correspondencia = $request->f_correspondencia;
        $f_correspondencia = $date;
        $radicado = $request->radicado;
        $bandera_correspondecia_guardar_actualizar = $request->bandera_correspondecia_guardar_actualizar;


        if ($bandera_correspondecia_guardar_actualizar == 'Guardar') {
            $datos_correspondencia = [
                'Oficio_Origen' => $oficio_origen,
                // 'Oficio_incapacidad' => $oficioinca,
                'Destinatario_principal' => $destinatario_principal,
                'Otro_destinatario' => $otrodestinariop,
                'Tipo_destinatario' => $tipo_destinatario_principal,
                'Nombre_dest_principal' => $nombre_destinatariopri,
                'Nombre_dest_principal_afi_empl' => $Nombre_dest_principal_afi_empl,
                'Nombre_destinatario' => $nombre_destinatario,
                'Nit_cc' => $nitcc_destinatario,
                'Direccion_destinatario' => $direccion_destinatario,
                'Telefono_destinatario' => $telefono_destinatario,
                'Email_destinatario' => $email_destinatario,
                'Departamento_destinatario' => $departamento_destinatario,
                'Ciudad_destinatario' => $ciudad_destinatario,
                'Asunto' => $Asunto,
                'Cuerpo_comunicado' => $cuerpo_comunicado,
                'Copia_empleador' => $empleador,
                'Copia_eps' => $eps,
                'Copia_afp' => $afp,
                'Copia_arl' => $arl,
                'Copia_jr' => $jrci,
                'Cual_jr' => $cual,
                'Copia_jn' => $jnci,
                'Anexos' => $anexos,
                'Elaboro' => $elaboro,
                'Reviso' => $reviso,
                'Firmar' => $firmar,
                'Ciudad' => $ciudad,
                'F_correspondecia' => $f_correspondencia,
                'N_radicado' => $radicado,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];
    
            sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_Evento_dto_atel],
                ['Id_Asignacion',$Id_Asignacion_dto_atel]
            ])->update($datos_correspondencia);       
    
            $datos_info_comunicado_eventos = [
                'ID_Evento' => $Id_Evento_dto_atel,
                'Id_proceso' => $Id_Proceso_dto_atel,
                'Id_Asignacion' => $Id_Asignacion_dto_atel,
                'Ciudad' => $ciudad,
                'F_comunicado' => $date,
                'N_radicado' => $radicado,
                'Cliente' => 'N/A',
                'Nombre_afiliado' => $destinatario_principal,
                'T_documento' => 'N/A',
                'N_identificacion' => 'N/A',
                'Destinatario' => 'N/A',
                'Nombre_destinatario' => 'N/A',
                'Nit_cc' => 'N/A',
                'Direccion_destinatario' => 'N/A',
                'Telefono_destinatario' => '001',
                'Email_destinatario' => 'N/A',
                'Id_departamento' => '001',
                'Id_municipio' => '001',
                'Asunto'=> $Asunto,
                'Cuerpo_comunicado' => $cuerpo_comunicado,
                'Forma_envio' => '0',
                'Elaboro' => $elaboro,
                'Reviso' => $reviso,
                'Agregar_copia' => $agregar_copias_comu,
                'JRCI_copia' => $cual,
                'Anexos' => $anexos,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);
    
            $mensajes = array(
                "parametro" => 'insertar_correspondencia',
                "mensaje" => 'Correspondencia guardada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
            
        } 
        elseif($bandera_correspondecia_guardar_actualizar == 'Actualizar') {
            $datos_correspondencia = [
                'Oficio_Origen' => $oficio_origen,
                // 'Oficio_incapacidad' => $oficioinca,
                'Destinatario_principal' => $destinatario_principal,
                'Otro_destinatario' => $otrodestinariop,
                'Tipo_destinatario' => $tipo_destinatario_principal,
                'Nombre_dest_principal' => $nombre_destinatariopri,
                'Nombre_dest_principal_afi_empl' => $Nombre_dest_principal_afi_empl,
                'Nombre_destinatario' => $nombre_destinatario,
                'Nit_cc' => $nitcc_destinatario,
                'Direccion_destinatario' => $direccion_destinatario,
                'Telefono_destinatario' => $telefono_destinatario,
                'Email_destinatario' => $email_destinatario,
                'Departamento_destinatario' => $departamento_destinatario,
                'Ciudad_destinatario' => $ciudad_destinatario,
                'Asunto' => $Asunto,
                'Cuerpo_comunicado' => $cuerpo_comunicado,
                'Copia_empleador' => $empleador,
                'Copia_eps' => $eps,
                'Copia_afp' => $afp,
                'Copia_arl' => $arl,
                'Copia_jr' => $jrci,
                'Cual_jr' => $cual,
                'Copia_jn' => $jnci,
                'Anexos' => $anexos,
                'Elaboro' => $elaboro,
                'Reviso' => $reviso,
                'Firmar' => $firmar,
                'Ciudad' => $ciudad,
                'F_correspondecia' => $f_correspondencia,
                'N_radicado' => $radicado,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];
    
            sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_Evento_dto_atel],
                ['Id_Asignacion',$Id_Asignacion_dto_atel]
            ])->update($datos_correspondencia);
            
            $datos_info_comunicado_eventos = [
                'Agregar_copia' => $agregar_copias_comu,
                'JRCI_copia' => $cual,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];   
                
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([                
                ['N_radicado',$radicado]
            ])->update($datos_info_comunicado_eventos);
    
            $mensajes = array(
                "parametro" => 'actualizar_correspondencia',
                "mensaje" => 'Correspondencia actualizada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
        }
        

    }
    
    // Descargar proforma DML ORIGEN PREVISIONAL (DICTAMEN)
    public function DescargaProformaDMLPrev(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        
        $user= Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        /* captura de variables del formulario */
        $id_cliente = $request->id_cliente;
        $nro_siniestro = $request->nro_siniestro;
        $Id_Asignacion = $request->Id_Asignacion;
        $Id_Proceso = $request->Id_Proceso;
        $f_dictamen = date("d-m-Y", strtotime($request->f_dictamen));
        $empresa_laboral = $request->empresa_laboral;
        $nit_cc_laboral = $request->nit_cc_laboral;
        $cargo_laboral = $request->cargo_laboral;
        $antiguedad_cargo_laboral = $request->antiguedad_cargo_laboral;
        $act_economica_laboral = $request->act_economica_laboral;
        $justificacion_revision_origen = $request->justificacion_revision_origen;
        $nombre_evento = $request->nombre_evento;
        $f_evento = date("d-m-Y", strtotime($request->f_evento));
        if (!empty($request->f_fallecimiento)) {
            $f_fallecimiento = date("d-m-Y", strtotime($request->f_fallecimiento));
        } else {
            $f_fallecimiento = "";
        }
        
        $sustentacion_califi_origen = $request->sustentacion_califi_origen;
        $origen = $request->origen;

        /* Creación de las variables faltantes que no están en el formulario */

        // fecha solicitud
        $array_datos_calificacionOrigen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_Asignacion));
        $fecha_solicitud = date("d-m-Y", strtotime($array_datos_calificacionOrigen[0]->F_radicacion));

        /* 2. DATOS PERSONALES */
        $informacion_del_afiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'siae.Tipo_documento', '=', 'slp.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp1', 'siae.Genero', '=', 'slp1.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'siae.Estado_civil', '=', 'slp2.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp3', 'siae.Nivel_escolar', '=', 'slp3.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp4', 'siae.Tipo_afiliado', '=', 'slp4.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie1', 'siae.Id_arl', '=', 'sie1.Id_Entidad')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie2', 'siae.Id_afp', '=', 'sie2.Id_Entidad')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_municipio', '=', 'sldm.Id_municipios')
        ->select(
            'siae.Nombre_afiliado',
            'slp.Nombre_parametro as Tipo_documento',
            'siae.Nro_identificacion',
            'siae.F_nacimiento',
            'siae.Edad',
            'slp1.Nombre_parametro as Genero',
            'slp2.Nombre_parametro as Nombre_Estado_Civil',
            'slp3.Nombre_parametro as Nombre_Nivel_Escolar',
            'sie.Nombre_entidad as Nombre_eps',
            'sie1.Nombre_entidad as Nombre_arl',
            'sie2.Nombre_entidad as Nombre_afp',
            'sldm.Nombre_municipio as Nombre_ciudad'
        )->where([['siae.ID_evento', $nro_siniestro]])
        ->get();

        $nombre_afiliado = $informacion_del_afiliado[0]->Nombre_afiliado;
        $tipo_doc_afiliado = $informacion_del_afiliado[0]->Tipo_documento;
        $nro_ident_afiliado = $informacion_del_afiliado[0]->Nro_identificacion;
        $f_nacimiento_afiliado = date("d-m-Y", strtotime($informacion_del_afiliado[0]->F_nacimiento));
        $edad_afiliado = $informacion_del_afiliado[0]->Edad;
        $genero_afiliado = $informacion_del_afiliado[0]->Genero;
        $estado_civil_afiliado = $informacion_del_afiliado[0]->Nombre_Estado_Civil;
        $escolaridad_afiliado = $informacion_del_afiliado[0]->Nombre_Nivel_Escolar;
        $eps_afiliado = $informacion_del_afiliado[0]->Nombre_eps;
        $arl_afiliado = $informacion_del_afiliado[0]->Nombre_arl;
        $afp_afiliado = $informacion_del_afiliado[0]->Nombre_afp;
        $ciudad_afiliado = $informacion_del_afiliado[0]->Nombre_ciudad;

        /* 4.2 RELACIÓN DE DOCUMENTOS */
        $datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->select('F_examen_interconsulta', 'Nombre_examen_interconsulta', 'Descripcion_resultado')
        ->where([
            ['ID_evento',$nro_siniestro],
            ['Id_Asignacion',$Id_Asignacion],
            ['Id_proceso',$Id_Proceso],
            ['Estado', 'Activo']
        ])
        ->get();

        $documentos_relacionados = array();
        foreach ($datos_examenes_interconsultas as $examen) {
            $array_temporal_examen = array(
                // 'fecha' => $examen->F_examen_interconsulta,
                'nombre' => $examen->Nombre_examen_interconsulta,
                'descripcion' => $examen->Descripcion_resultado
            );

            array_push($documentos_relacionados, $array_temporal_examen);
            $array_temporal_examen = array();
        }

        /* datos del logo que va en el header */
        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $id_cliente]])
        ->limit(1)->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
        } else {
            $logo_header = "Sin logo";
        }

        //consulta si esta visado o no para mostrar las firmas

        $validacion_visado = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->select('ID_evento', 'Id_proceso', 'Id_Asignacion', 'Visar')
        ->where([['Id_Asignacion',$Id_Asignacion], ['Visar','Si']])->get();

        /* Armado de datos para reemplazarlos en la plantilla */
        $datos_finales_dml_origen_previsional = [
            'id_cliente' => $id_cliente,
            'logo_header' => $logo_header,
            'nro_siniestro' => $nro_siniestro,
            'fecha_solicitud' => $fecha_solicitud,
            'fecha_concepto' => $f_dictamen,
            'ciudad' => $ciudad_afiliado,
            'nombre_afiliado' => $nombre_afiliado,
            'tipo_doc_afiliado' => $tipo_doc_afiliado,
            'nro_ident_afiliado' => $nro_ident_afiliado,
            'fecha_nacimiento_afiliado' => $f_nacimiento_afiliado,
            'edad_afiliado' => $edad_afiliado,
            'genero_afiliado' => $genero_afiliado,
            'estado_civil_afiliado' => $estado_civil_afiliado,
            'escolaridad_afiliado' => $escolaridad_afiliado,
            'eps_afiliado' => $eps_afiliado,
            'arl_afiliado' => $arl_afiliado,
            'afp_afiliado' => $afp_afiliado,
            'empresa_laboral' => $empresa_laboral,
            'nit_cc_laboral' => $nit_cc_laboral,
            'cargo_laboral' => $cargo_laboral,
            'antiguedad_cargo_laboral' => $antiguedad_cargo_laboral,
            'act_economica_laboral' => $act_economica_laboral,
            'justificacion_revision_origen' => $justificacion_revision_origen,
            'documentos_relacionados' => $documentos_relacionados,
            'nombre_evento' => $nombre_evento,
            'origen' => $origen,
            'fecha_evento' => $f_evento,
            'fecha_fallecimiento' => $f_fallecimiento,
            'sustentacion_califi_origen' => $sustentacion_califi_origen,
            'nombre_usuario' => $nombre_usuario,
            'validacion_visado' => $validacion_visado
        ];

        /* Creación del pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/Origen_Atel/dml_origen_atel', $datos_finales_dml_origen_previsional);

        $nombre_pdf = "ORI_DML_{$Id_Asignacion}_{$nro_ident_afiliado}.pdf";

        //Obtener el contenido del PDF
        $output = $pdf->output();
        //Guardar el PDF en un archivo
        file_put_contents(public_path("Documentos_Eventos/{$nro_siniestro}/{$nombre_pdf}"), $output);

        // return $dompdf->stream($nombre_pdf);
        return $pdf->download($nombre_pdf); 
    }

    // Descarga proforma Notificación del DML previsional (OFICIO REMISORIO)
    public function DescargaProformaNotiDMLPrev(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        
        $user= Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        
        /* Captura de variables del formulario */
        $id_tupla_comunicado = $request->id_tupla_comunicado;
        $id_com_inter = $request->id_com_inter;
        $ciudad = $request->ciudad;
        $fecha = $request->fecha;
        $asunto = strtoupper($request->asunto);
        $cuerpo = $request->cuerpo;
        $tipo_identificacion = $request->tipo_identificacion;
        $num_identificacion = $request->num_identificacion;
        $nro_siniestro = $request->nro_siniestro;
        $nombre_afiliado = $request->nombre_afiliado;
        $direccion_afiliado = $request->direccion_afiliado;
        $telefono_afiliado = $request->telefono_afiliado;
        $Id_asignacion = $request->Id_asignacion;
        $Id_cliente_firma = $request->Id_cliente_firma;
        $origen = "<b>".$request->origen."</b>";
        $copia_empleador = $request->copia_empleador;
        $copia_eps = $request->copia_eps;
        $copia_afp = $request->copia_afp;
        $copia_arl = $request->copia_arl;
        $firmar = $request->firmar;
        $anexos = $request->anexos;

        /* Creación de las variables faltantes que no están en el formulario */
        $dato_nro_radicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->select('N_radicado')
        ->where([['Id_Comunicado', $id_tupla_comunicado]])
        ->get();

        $array_dato_nro_radicado = json_decode(json_encode($dato_nro_radicado), true);
        $nro_radicado = $array_dato_nro_radicado[0]["N_radicado"];

        // Validación información Destinatario Principal
        $datos_para_destinatario_principal = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([['Id_com_inter', $id_com_inter]])->get();

        $array_datos_para_destinatario_principal = json_decode(json_encode($datos_para_destinatario_principal), true);

        $checkbox_otro_destinatario = $array_datos_para_destinatario_principal[0]["Otro_destinatario"];

        //  Si el checkbox fue marcado entonces se entra a mirar las demás validaciones
        if ($checkbox_otro_destinatario == "Si") {
            // 1: ARL; 2: AFP; 3: EPS; 4: AFILIADO; 5:EMPLEADOR; 8: OTRO
            
            $tipo_destinatario = $array_datos_para_destinatario_principal[0]["Tipo_destinatario"];
            switch (true) {
                // Si escoge alguna opcion de estas: ARL, AFP, EPS se sacan los datos del destinatario principal de la entidad
                case ($tipo_destinatario == 1 || $tipo_destinatario == 2 || $tipo_destinatario == 3):
                    $id_entidad = $array_datos_para_destinatario_principal[0]["Nombre_dest_principal"];

                    $datos_entidad = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
                    ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad')
                    ->where([
                        ['sie.Id_Entidad', $id_entidad],
                        ['sie.IdTipo_entidad', $tipo_destinatario]
                    ])->get();

                    $nombre_destinatario_principal = $datos_entidad[0]->Nombre_entidad;
                    $direccion_destinatario_principal = $datos_entidad[0]->Direccion;
                    $telefono_destinatario_principal = $datos_entidad[0]->Telefonos;
                    $ciudad_destinatario_principal = $datos_entidad[0]->Nombre_ciudad;
                break;
                
                // Si escoge la opción Afiliado: Se sacan los datos del destinatario principal pero del afiliado
                case ($tipo_destinatario == 4):
                    $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
                    ->where([['siae.ID_evento','=', $nro_siniestro]])
                    ->get();
        
                    $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);
        
                    $nombre_destinatario_principal = $nombre_afiliado;
                    $direccion_destinatario_principal = $direccion_afiliado;
                    $telefono_destinatario_principal = $telefono_afiliado;
                    $ciudad_destinatario_principal = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"];
                break;

                // Si escoge la opción Empleador: Se sacan los datos del destinatario principal pero del Empleador
                case ($tipo_destinatario == 5):
                    $datos_entidad_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_municipio', '=', 'sldm.Id_municipios')
                    ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_municipio as Nombre_ciudad')
                    ->where([['sile.ID_evento', $nro_siniestro]])->get();

                    $nombre_destinatario_principal = $datos_entidad_empleador[0]->Empresa;
                    $direccion_destinatario_principal = $datos_entidad_empleador[0]->Direccion;
                    $telefono_destinatario_principal = $datos_entidad_empleador[0]->Telefono_empresa;
                    $ciudad_destinatario_principal = $datos_entidad_empleador[0]->Nombre_ciudad;
                break;
                
                // Si escoge la opción Otro: se sacan los datos del destinatario de la tabla sigmel_informacion_comite_interdisciplinario_eventos
                case ($tipo_destinatario == 8):
                    // aqui validamos si los datos no vienen vacios, debido a que si  vienen vacios, toca marcar ''
                    if (!empty($array_datos_para_destinatario_principal[0]["Nombre_destinatario"])) {
                        $nombre_destinatario_principal = $array_datos_para_destinatario_principal[0]["Nombre_destinatario"];
                    } else {
                        $nombre_destinatario_principal = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Direccion_destinatario"])) {
                        $direccion_destinatario_principal = $array_datos_para_destinatario_principal[0]["Direccion_destinatario"];
                    } else {
                        $direccion_destinatario_principal = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Telefono_destinatario"])) {
                        $telefono_destinatario_principal = $array_datos_para_destinatario_principal[0]["Telefono_destinatario"];
                    } else {
                        $telefono_destinatario_principal = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Ciudad_destinatario"])) {
                        $ciudad_destinatario_principal = $array_datos_para_destinatario_principal[0]["Ciudad_destinatario"];
                    } else {
                        $ciudad_destinatario_principal = "";
                    };
                break;

                default:
                    # code...
                break;
            }
        } 
        // En caso de que no: la info del destinatario principal se saca del afiliado
        else {
            $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
            ->where([['siae.ID_evento','=', $nro_siniestro]])
            ->get();

            $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);

            $nombre_destinatario_principal = $nombre_afiliado;
            $direccion_destinatario_principal = $direccion_afiliado;
            $telefono_destinatario_principal = $telefono_afiliado;
            $ciudad_destinatario_principal = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"];
        }
        
        $ramo = "Previsionales";
        
        /* Copias Interesadas */
        // Validamos si los checkbox esta marcados
        $final_copia_empleador = isset($copia_empleador) ? 'Empleador' : '';
        $final_copia_eps = isset($copia_eps) ? 'EPS' : '';
        $final_copia_afp = isset($copia_afp) ? 'AFP' : '';
        $final_copia_arl = isset($copia_arl) ? 'ARL' : '';

        $total_copias = array_filter(array(
            'copia_empleador' => $final_copia_empleador,
            'copia_eps' => $final_copia_eps,
            'copia_afp' => $final_copia_afp,
            'copia_arl' => $final_copia_arl,
        )); 

        sleep(2);
        
        // Conversión de las key en variables con sus respectivos datos
        extract($total_copias);
        
        $Agregar_copias = [];
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

            $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$telefono_empleador."; ".$ciudad_empleador." - ".$municipio_empleador.".";
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
            $municipio_eps = $datos_eps[0]->Nombre_municipio;

            $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$telefonos_eps."; ".$ciudad_eps." - ".$municipio_eps;
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
            $municipio_afp = $datos_afp[0]->Nombre_municipio;

            $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$telefonos_afp."; ".$ciudad_afp." - ".$municipio_afp;
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
            $municipio_arl = $datos_arl[0]->Nombre_municipio;

            $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$telefonos_arl."; ".$ciudad_arl." - ".$municipio_arl;
        }

        /* Validación Firma Cliente */
        $validarFirma = isset($firmar) ? 'Firmar Documento' : 'Sin Firma';
        
        if ($validarFirma == "Firmar Documento") {
            $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente')
            ->where('Id_cliente', $Id_cliente_firma)->get();
    
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
        } else {
            $logo_header = "Sin logo";
        }

        /* Extraemos los datos del footer */
        $datos_footer = sigmel_clientes::on('sigmel_gestiones')
        ->select('footer_dato_1', 'footer_dato_2', 'footer_dato_3', 'footer_dato_4', 'footer_dato_5')
        ->where('Id_cliente',  $Id_cliente_firma)->get();

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

        /* Armado de datos para reemplazarlos en la plantilla */
        $datos_finales_noti_dml_origen = [
            'id_cliente' => $Id_cliente_firma,
            'logo_header' => $logo_header,
            'ciudad' => $ciudad,
            'fecha' => $fecha,
            'asunto' => $asunto,
            'cuerpo' => $cuerpo,
            'tipo_identificacion' => $tipo_identificacion,
            'num_identificacion' => $num_identificacion,
            'nro_siniestro' => $nro_siniestro,
            'nombre_afiliado' => $nombre_afiliado,
            'origen' => $origen,
            'nro_radicado' => $nro_radicado,
            'anexos' => $anexos,
            'nombre_destinatario_principal' => $nombre_destinatario_principal,
            'direccion_destinatario_principal' => $direccion_destinatario_principal,
            'telefono_destinatario_principal' => $telefono_destinatario_principal,
            'ciudad_destinatario_principal' => $ciudad_destinatario_principal,
            'ramo' => $ramo,
            'Agregar_copia' => $Agregar_copias,
            'Firma_cliente' => $Firma_cliente,
            'nombre_usuario' => $nombre_usuario,
            'footer_dato_1' => $footer_dato_1,
            'footer_dato_2' => $footer_dato_2,
            'footer_dato_3' => $footer_dato_3,
            'footer_dato_4' => $footer_dato_4,
            'footer_dato_5' => $footer_dato_5,
        ];

        /* Creación del pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/Origen_Atel/notificacion_dml_origen', $datos_finales_noti_dml_origen);
        
        $nombre_pdf = "ORI_OFICIO_{$Id_asignacion}_{$num_identificacion}.pdf";

        //Obtener el contenido del PDF
        $output = $pdf->output();
        //Guardar el PDF en un archivo
        file_put_contents(public_path("Documentos_Eventos/{$nro_siniestro}/{$nombre_pdf}"), $output);

        // return $dompdf->stream($nombre_pdf);
        return $pdf->download($nombre_pdf); 


    }

}
