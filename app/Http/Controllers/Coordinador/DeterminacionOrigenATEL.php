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
use App\Models\cndatos_eventos;

class DeterminacionOrigenATEL extends Controller
{
    public function mostrarVistaDtoATEL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $Id_evento_dto_atel=$request->Id_evento_calitec;
        $Id_asignacion_dto_atel = $request->Id_asignacion_calitec;
        $Id_proceso_dto_atel = $request->Id_proceso_calitec;

        $array_datos_DTO_ATEL = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion_dto_atel));

        $numero_consecutivo = 0000000 + 1;
        // Formatear el número consecutivo a 7 dígitos
        $numero_consecutivo = str_pad($numero_consecutivo, 7, "0", STR_PAD_LEFT);

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

        // TRAER DATOS CIE10
        $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['side.ID_evento',$Id_evento_dto_atel],['side.Estado', '=', 'Activo']])->get(); 

        // TRAER DATOS EXAMENES E INTERCONSULTAS
        $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_dto_atel],
            ['Id_Asignacion',$Id_asignacion_dto_atel],
            ['Id_proceso',$Id_proceso_dto_atel],
            ['Estado', 'Activo']
        ])
        ->get();

        return view('coordinador.determinacionOrigenATEL', compact('user', 'array_datos_DTO_ATEL', 'numero_consecutivo', 
        'motivo_solicitud_actual', 'datos_apoderado_actual', 
        'array_datos_info_laboral', 'listado_documentos_solicitados', 
        'dato_articulo_12', 'array_datos_diagnostico_motcalifi',
        'array_datos_examenes_interconsultas'));
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
            ->select('Id_Cie_diagnostico', 'CIE10')
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

    }
}
