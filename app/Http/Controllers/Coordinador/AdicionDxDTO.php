<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\sigmel_informacion_dto_atel_eventos;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\cndatos_eventos;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_informacion_examenes_interconsultas_eventos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\sigmel_lista_cie_diagnosticos;
use App\Models\sigmel_informacion_pericial_eventos;
use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_informacion_adiciones_dx_eventos;

class AdicionDxDTO extends Controller
{
    public function mostrarVistaAdicionDxDTO(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        $Id_evento = $request->Id_evento_calitec;
        $Id_asignacion = $request->Id_asignacion_calitec;
        $Id_proceso = $request->Id_proceso_calitec;

        // traer informacion de la tabla sigmel_informacion_adiciones_dx_eventos
        $info_adicion_dx = sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $Id_evento)->get();

        $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion));

        // Validación: Validar si existe un dto atel antes de hacer todo
        $datos_bd_DTO_ATEL = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $Id_evento)->get();

        $bandera_hay_dto = null;
        $bandera_tipo_evento = null;
        if (count($datos_bd_DTO_ATEL) == 0) {
            $bandera_hay_dto = "no_hay_dto_atel";
            $nombre_del_evento_guardado = "";
        }else{

            $bandera_hay_dto = "hay_dto_atel";

            // Validación: Validar que el tipo de evento sea Accidente o Sin Cobertura
            $id_evento_guardado_dto_atel = $datos_bd_DTO_ATEL[0]->Tipo_evento;

            if ($id_evento_guardado_dto_atel == 1 || $id_evento_guardado_dto_atel == 4) {
                $bandera_tipo_evento = "tipo_evento_correcto";
            } else {
                $bandera_tipo_evento = "tipo_evento_incorrecto";
            }
            

            $array_nombre_del_evento_guardado = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Nombre_evento')
            ->where('Id_Evento', $id_evento_guardado_dto_atel)->get();
            $nombre_del_evento_guardado = $array_nombre_del_evento_guardado[0]->Nombre_evento;
        }

        $consecutivo_dto_atel = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
        ->max('Numero_dictamen');
        
        if ($consecutivo_dto_atel > 0) {
            $numero_consecutivo = $consecutivo_dto_atel + 1;
        }else{
            $numero_consecutivo = 0000000 + 1;
        }

        //Traer Motivo de solicitud,
        $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
        ->select('Id_motivo_solicitud','Nombre_solicitud')
        ->where('ID_evento', $Id_evento)
        ->get();

        //Traer Información apoderado 
        $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('Nombre_apoderado','Nro_identificacion_apoderado')
        ->where('ID_evento', $Id_evento)
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
        ->where([['sile.ID_evento','=', $Id_evento]])
        ->orderBy('sile.F_registro', 'desc')
        ->limit(1)
        ->get();

        //Trae Documentos Solicitados del proceso origen solamente
        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where([
            ['ID_evento',$Id_evento],
            ['Estado','Activo'],
            ['Id_proceso','1']
         ])
        ->get();

        //Trae si ya marco Articulo 12
        $dato_articulo_12= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_documentos_solicitados_eventos')
       ->select('Articulo_12')
       ->where([
                ['ID_evento', $Id_evento],
                ['Id_Asignacion', $Id_asignacion], 
                ['Id_proceso', '1'], 
                ['Articulo_12','=','No_mas_seguimiento']
            ])
        ->orderBy('Id_Documento_Solicitado', 'desc')
        ->limit(1)
        ->get();

        // Traer datos 
        if(count($datos_bd_DTO_ATEL) > 0){

            $nombre_tipo_accidente = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Tipo de accidiente'],
                ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Tipo_accidente],
                ['Estado', '=' ,'activo']
            ])->get();

            $nombre_tipo_accidente = $nombre_tipo_accidente[0]['Nombre_parametro'];

            $nombre_grado_severidad = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Grado de Severidad'],
                ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Grado_severidad],
                ['Estado', '=' ,'activo']
            ])->get();

            $nombre_grado_severidad = $nombre_grado_severidad[0]['Nombre_parametro'];

            $nombre_factor_riesgo = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Factor de Riesgo'],
                ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Factor_riesgo],
                ['Estado', '=' ,'activo']
            ])->get();

            $nombre_factor_riesgo = $nombre_factor_riesgo[0]['Nombre_parametro'];

            $nombre_tipo_lesion = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Tipo de Lesion'],
                ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Tipo_lesion],
                ['Estado', '=' ,'activo']
            ])->get();

            $nombre_tipo_lesion = $nombre_tipo_lesion[0]['Nombre_parametro'];

            $nombre_parte_cuerpo_afectada = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Parte Cuerpo Afectada'],
                ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Parte_cuerpo_afectada],
                ['Estado', '=' ,'activo']
            ])->get();

            $nombre_parte_cuerpo_afectada = $nombre_parte_cuerpo_afectada[0]['Nombre_parametro'];

            // TRAER DATOS EXAMENES E INTERCONSULTAS DEL DTO ATEL
            if (count($info_adicion_dx) > 0) {
                $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                ->whereIn('Id_Asignacion', [$datos_bd_DTO_ATEL[0]->Id_Asignacion, $info_adicion_dx[0]->Id_Asignacion])
                ->where([
                    ['ID_evento',$Id_evento],
                    ['Id_proceso',$Id_proceso],
                    ['Estado', 'Activo']
                ])->get();
            } else {
                $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento],
                    ['Id_Asignacion', $datos_bd_DTO_ATEL[0]->Id_Asignacion],
                    ['Id_proceso',$Id_proceso],
                    ['Estado', 'Activo']
                ])->get();
            }
            
    
            // TRAER DATOS CIE10 (Diagnóstico motivo de calificación) Visuales
            $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
            ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.Id_proceso', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
            'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
            ->where([['side.ID_evento',$datos_bd_DTO_ATEL[0]->ID_evento],
                ['side.Id_Asignacion',$datos_bd_DTO_ATEL[0]->Id_Asignacion],
                ['side.Id_proceso',$datos_bd_DTO_ATEL[0]->Id_proceso],
                ['side.Estado', '=', 'Activo']
            ])->get(); 

            if (count($info_adicion_dx) > 0) {
                // TRAER DATOS CIE10 (Diagnóstico motivo de calificación) Adicionales
                $array_datos_diagnostico_adicionales =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.Id_proceso', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10',
                'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal', 'side.F_adicion_CIE10')
                ->where([['side.ID_evento',$info_adicion_dx[0]->ID_evento],
                    ['side.Id_Asignacion',$info_adicion_dx[0]->Id_Asignacion],
                    ['side.Id_proceso',$info_adicion_dx[0]->Id_proceso],
                    ['side.Estado', '=', 'Activo']
                ])->get(); 
            }else{
                $array_datos_diagnostico_adicionales = "";
            }
            
        }else{
            $nombre_tipo_accidente = "";
            $nombre_grado_severidad = "";
            $nombre_factor_riesgo = "";
            $nombre_tipo_lesion = "";
            $nombre_parte_cuerpo_afectada = "";
            $array_datos_examenes_interconsultas = "";
            $array_datos_diagnostico_motcalifi = "";
            $array_datos_diagnostico_adicionales = "";
        }

        

        return view('coordinador.adicionDxDtoOrigen', compact('user', 'datos_bd_DTO_ATEL', 'bandera_hay_dto', 'array_datos_calificacion_origen', 
        'bandera_tipo_evento', 'nombre_del_evento_guardado', 'numero_consecutivo', 'motivo_solicitud_actual',
        'datos_apoderado_actual', 'array_datos_info_laboral', 'nombre_tipo_accidente','nombre_grado_severidad',
        'nombre_factor_riesgo','nombre_tipo_lesion','nombre_parte_cuerpo_afectada',
        'listado_documentos_solicitados', 'dato_articulo_12', 'array_datos_examenes_interconsultas',
        'array_datos_diagnostico_motcalifi', 'info_adicion_dx', 'array_datos_diagnostico_adicionales'));
    }

    public function cargueListadoSelectoresAdicionDx(Request $request){
        $parametro = $request->parametro;

        if ($parametro == "tipo_de_evento_si") {
            $listado_tipos_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Id_Evento', 'Nombre_evento')
            ->where('Estado', 'activo')
            ->whereNotIn('Nombre_evento', ['Enfermedad', 'Incidente', 'Sin Cobertura'])
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
        // if ($parametro == "origen_vali_2") {
        //     $listado_origen_vali_2 = sigmel_lista_parametros::on('sigmel_gestiones')
        //     ->select('Id_Parametro', 'Nombre_parametro')
        //     ->where([
        //         ['Tipo_lista', '=', 'Origen DTO ATEL'],
        //         ['Estado', '=', 'activo']
        //     ])
        //     ->whereNotIn('Nombre_parametro', ['Común', 'Laboral', 'Sin Origen', 'Sin Cobertura'])
        //     ->get();
        //     $info_origen_vali_2 = json_decode(json_encode($listado_origen_vali_2, true));
        //     return response()->json($info_origen_vali_2);
        // }

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

    public function GuardaroActualizarInfoAdicionDX(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
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

        // Paso N°3: Guardar los datos de Adiciones de Diagnósticos
        $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->max('Id_Diagnosticos_motcali');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
        }
 
        if (!empty($request->Adicion_motivo_calificacion)) {
            if (count($request->Adicion_motivo_calificacion) > 0) {
                // Captura del array de los datos de la tabla
                $array_diagnosticos_motivo_calificacion = $request->Adicion_motivo_calificacion;
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
                'F_adicion_CIE10', 'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
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

        // Paso N° 4: Guardar los datos del formulario en la  tabla sigmel_informacion_adiciones_dx_eventos
        $Tipo_evento = $request->Tipo_evento;
        if (!empty($request->Relacion_documentos)) {
            $total_relacion_documentos = implode(", ", $request->Relacion_documentos);                
        }else{
            $total_relacion_documentos = '';
        }


        // Tipo de formulario: Accidente
        if ($Tipo_evento == 1) {
            $datos_formulario = [
                'ID_evento' => $request->ID_Evento,
                'Id_Asignacion' => $request->Id_Asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Id_Dto_ATEL' => $request->Id_Dto_ATEL,
                'Activo' => $request->Activo,
                'Tipo_evento' => $request->Tipo_evento,
                'Relacion_documentos' => $total_relacion_documentos,
                'Otros_relacion_documentos' => $request->Otros_relacion_documentos,
                'Sustentacion_Adicion_Dx' => $request->Sustentacion_Adicion_Dx,
                'Origen' => $request->Origen,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];

        }

        $Id_Adiciones_Dx = $request->Id_Adiciones_Dx;

        if ($Id_Adiciones_Dx == "") {
            sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')->insert($datos_formulario);
            $mensaje = 'Información guardada satisfactoriamente.';
        } else {
            sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
            ->where('Id_Adiciones_Dx', $Id_Adiciones_Dx)->update($datos_formulario);
            $mensaje = 'Información actualizada satisfactoriamente.';
        }
        
        $mensajes = array(
            "parametro" => 'agregar_dto_atel',
            "mensaje" => $mensaje
        ); 

        return json_decode(json_encode($mensajes, true));

    }
}