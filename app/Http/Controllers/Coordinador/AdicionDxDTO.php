<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_lista_regional_juntas;
use App\Models\sigmel_lista_solicitantes;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_clientes;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_registro_descarga_documentos;
use App\Services\GlobalService;
use App\Traits\GenerarRadicados;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class AdicionDxDTO extends Controller
{
    use GenerarRadicados;

    protected $globalService;

    public function __construct(GlobalService $globalService)
    {
        $this->globalService = $globalService;
    }

    // public function mostrarVistaAdicionDxDTO(Request $request){
    //     if(!Auth::check()){
    //         return redirect('/');
    //     }
    //     $user = Auth::user();
    //     $time = time();
    //     $date = date("Y-m-d", $time);
    //     $Id_evento = $request->Id_evento_calitec;
    //     $Id_asignacion = $request->Id_asignacion_calitec;
    //     $Id_proceso = $request->Id_proceso_calitec;

    //     // traer informacion de la tabla sigmel_informacion_adiciones_dx_eventos
    //     $info_adicion_dx = sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
    //     ->where('ID_evento', $Id_evento)->get();

    //     $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion));

    //     // Validación: Validar si existe un dto atel antes de hacer todo
    //     $datos_bd_DTO_ATEL = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
    //     ->where('ID_evento', $Id_evento)->get();

    //     $bandera_hay_dto = null;
    //     $bandera_tipo_evento = null;
    //     if (count($datos_bd_DTO_ATEL) == 0) {
    //         $bandera_hay_dto = "no_hay_dto_atel";
    //         $nombre_del_evento_guardado = "";
    //     }else{

    //         $bandera_hay_dto = "hay_dto_atel";

    //         // Validación: Validar que el tipo de evento sea Accidente o Sin Cobertura
    //         $id_evento_guardado_dto_atel = $datos_bd_DTO_ATEL[0]->Tipo_evento;

    //         if ($id_evento_guardado_dto_atel == 1 || $id_evento_guardado_dto_atel == 4) {
    //             $bandera_tipo_evento = "tipo_evento_correcto";
    //         } else {
    //             $bandera_tipo_evento = "tipo_evento_incorrecto";
    //         }
            

    //         $array_nombre_del_evento_guardado = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
    //         ->select('Nombre_evento')
    //         ->where('Id_Evento', $id_evento_guardado_dto_atel)->get();
    //         $nombre_del_evento_guardado = $array_nombre_del_evento_guardado[0]->Nombre_evento;
    //     }

    //     $consecutivo_dto_atel = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
    //     ->max('Numero_dictamen');
        
    //     if ($consecutivo_dto_atel > 0) {
    //         $numero_consecutivo = $consecutivo_dto_atel + 1;
    //     }else{
    //         $numero_consecutivo = 0000000 + 1;
    //     }

    //     //Traer Motivo de solicitud,
    //     $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
    //     ->select('Id_motivo_solicitud','Nombre_solicitud')
    //     ->where('ID_evento', $Id_evento)
    //     ->get();

    //     //Traer Información apoderado 
    //     $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
    //     ->select('Nombre_apoderado','Nro_identificacion_apoderado')
    //     ->where('ID_evento', $Id_evento)
    //     ->get();

    //     // Traer Información laboral
    //     $array_datos_info_laboral=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
    //     ->leftJoin('sigmel_gestiones.sigmel_lista_arls as sla', 'sla.Id_arl', '=', 'sile.Id_arl')
    //     ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'sile.Id_departamento')
    //     ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldms', 'sldms.Id_municipios', '=', 'sile.Id_municipio')
    //     ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'sile.Id_actividad_economica')
    //     ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'sile.Id_clase_riesgo')
    //     ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
    //     ->select('sile.ID_evento', 'sile.Tipo_empleado','sile.Id_arl', 'sla.Nombre_arl', 'sile.Empresa', 'sile.Nit_o_cc', 'sile.Telefono_empresa',
    //     'sile.Email', 'sile.Direccion', 'sile.Id_departamento', 'sldm.Nombre_departamento', 'sile.Id_municipio', 
    //     'sldms.Nombre_municipio', 'sile.Id_actividad_economica', 'slae.Nombre_actividad', 'sile.Id_clase_riesgo', 
    //     'slcr.Nombre_riesgo', 'sile.Persona_contacto', 'sile.Telefono_persona_contacto', 'sile.Id_codigo_ciuo', 'slcc.Nombre_ciuo', 
    //     'sile.F_ingreso', 'sile.Cargo', 'sile.Funciones_cargo', 'sile.Antiguedad_empresa', 'sile.Antiguedad_cargo_empresa', 
    //     'sile.F_retiro', 'sile.Descripcion')
    //     ->where([['sile.ID_evento','=', $Id_evento]])
    //     ->orderBy('sile.F_registro', 'desc')
    //     ->limit(1)
    //     ->get();

    //     //Trae Documentos Solicitados del proceso origen solamente
    //     $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
    //     ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
    //     'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
    //     ->where([
    //         ['ID_evento',$Id_evento],
    //         ['Estado','Activo'],
    //         ['Id_proceso','1']
    //      ])
    //     ->get();

    //     //Trae si ya marco Articulo 12
    //     $dato_articulo_12= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_documentos_solicitados_eventos')
    //    ->select('Articulo_12')
    //    ->where([
    //             ['ID_evento', $Id_evento],
    //             ['Id_Asignacion', $Id_asignacion], 
    //             ['Id_proceso', '1'], 
    //             ['Articulo_12','=','No_mas_seguimiento']
    //         ])
    //     ->orderBy('Id_Documento_Solicitado', 'desc')
    //     ->limit(1)
    //     ->get();

    //     // Traer datos 
    //     if(count($datos_bd_DTO_ATEL) > 0){

    //         $nombre_tipo_accidente = sigmel_lista_parametros::on('sigmel_gestiones')
    //         ->select('Nombre_parametro')
    //         ->where([
    //             ['Tipo_lista', '=', 'Tipo de accidiente'],
    //             ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Tipo_accidente],
    //             ['Estado', '=' ,'activo']
    //         ])->get();            
            
    //         if (count($nombre_tipo_accidente) > 0) {
    //             $nombre_tipo_accidente = $nombre_tipo_accidente[0]['Nombre_parametro'];
    //         }else{
    //             $nombre_tipo_accidente = '';
    //         }

    //         $nombre_grado_severidad = sigmel_lista_parametros::on('sigmel_gestiones')
    //         ->select('Nombre_parametro')
    //         ->where([
    //             ['Tipo_lista', '=', 'Grado de Severidad'],
    //             ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Grado_severidad],
    //             ['Estado', '=' ,'activo']
    //         ])->get();

    //         if (count($nombre_grado_severidad) > 0) {
    //             $nombre_grado_severidad = $nombre_grado_severidad[0]['Nombre_parametro'];                
    //         } else {
    //             $nombre_grado_severidad = '';                                
    //         }
            
    //         $nombre_factor_riesgo = sigmel_lista_parametros::on('sigmel_gestiones')
    //         ->select('Nombre_parametro')
    //         ->where([
    //             ['Tipo_lista', '=', 'Factor de Riesgo'],
    //             ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Factor_riesgo],
    //             ['Estado', '=' ,'activo']
    //         ])->get();

    //         if (count($nombre_factor_riesgo) > 0 ) {
    //             $nombre_factor_riesgo = $nombre_factor_riesgo[0]['Nombre_parametro'];
    //         } else {
    //             $nombre_factor_riesgo = '';
    //         }
            

    //         $nombre_tipo_lesion = sigmel_lista_parametros::on('sigmel_gestiones')
    //         ->select('Nombre_parametro')
    //         ->where([
    //             ['Tipo_lista', '=', 'Tipo de Lesion'],
    //             ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Tipo_lesion],
    //             ['Estado', '=' ,'activo']
    //         ])->get();

    //         if (count($nombre_tipo_lesion) > 0) {
    //             $nombre_tipo_lesion = $nombre_tipo_lesion[0]['Nombre_parametro'];                
    //         } else {
    //             $nombre_tipo_lesion = '';                                
    //         }            

    //         $nombre_parte_cuerpo_afectada = sigmel_lista_parametros::on('sigmel_gestiones')
    //         ->select('Nombre_parametro')
    //         ->where([
    //             ['Tipo_lista', '=', 'Parte Cuerpo Afectada'],
    //             ['Id_Parametro', $datos_bd_DTO_ATEL[0]->Parte_cuerpo_afectada],
    //             ['Estado', '=' ,'activo']
    //         ])->get();

    //         if (count($nombre_parte_cuerpo_afectada) > 0) {
    //             $nombre_parte_cuerpo_afectada = $nombre_parte_cuerpo_afectada[0]['Nombre_parametro'];                
    //         } else {
    //             $nombre_parte_cuerpo_afectada = '';                                
    //         }

    //         // TRAER DATOS EXAMENES E INTERCONSULTAS DEL DTO ATEL
    //         if (count($info_adicion_dx) > 0) {
    //             $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
    //             ->whereIn('Id_Asignacion', [$datos_bd_DTO_ATEL[0]->Id_Asignacion, $info_adicion_dx[0]->Id_Asignacion])
    //             ->where([
    //                 ['ID_evento',$Id_evento],
    //                 ['Id_proceso',$Id_proceso],
    //                 ['Estado', 'Activo']
    //             ])->get();
    //         } else {
    //             $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
    //             ->where([
    //                 ['ID_evento',$Id_evento],
    //                 ['Id_Asignacion', $datos_bd_DTO_ATEL[0]->Id_Asignacion],
    //                 ['Id_proceso',$Id_proceso],
    //                 ['Estado', 'Activo']
    //             ])->get();
    //         }
            
    
    //         // TRAER DATOS CIE10 (Diagnóstico motivo de calificación) Visuales
    //         $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
    //         ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
    //         ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
    //         ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
    //         ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.Id_proceso', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
    //         'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
    //         ->where([['side.ID_evento',$datos_bd_DTO_ATEL[0]->ID_evento],
    //             ['side.Id_Asignacion',$datos_bd_DTO_ATEL[0]->Id_Asignacion],
    //             ['side.Id_proceso',$datos_bd_DTO_ATEL[0]->Id_proceso],
    //             ['side.Estado', '=', 'Activo']
    //         ])->get(); 

    //         if (count($info_adicion_dx) > 0) {
    //             // TRAER DATOS CIE10 (Diagnóstico motivo de calificación) Adicionales
    //             $array_datos_diagnostico_adicionales =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
    //             ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
    //             ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
    //             ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
    //             ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.Id_proceso', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
    //             'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10',
    //             'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal', 'side.F_adicion_CIE10')
    //             ->where([['side.ID_evento',$info_adicion_dx[0]->ID_evento],
    //                 ['side.Id_Asignacion',$info_adicion_dx[0]->Id_Asignacion],
    //                 ['side.Id_proceso',$info_adicion_dx[0]->Id_proceso],
    //                 ['side.Estado', '=', 'Activo']
    //             ])->get(); 
    //         }else{
    //             $array_datos_diagnostico_adicionales = "";
    //         }
            
    //     }else{
    //         $nombre_tipo_accidente = "";
    //         $nombre_grado_severidad = "";
    //         $nombre_factor_riesgo = "";
    //         $nombre_tipo_lesion = "";
    //         $nombre_parte_cuerpo_afectada = "";
    //         $array_datos_examenes_interconsultas = "";
    //         $array_datos_diagnostico_motcalifi = "";
    //         $array_datos_diagnostico_adicionales = "";
    //     }

    //     $array_comite_interdisciplinario = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
    //     ->where([
    //         ['ID_evento',$Id_evento],
    //         ['Id_Asignacion',$Id_asignacion]
    //     ])
    //     ->get(); 

    //     // creación de consecutivo para el comunicado
    //     $radicadocomunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
    //     ->select('N_radicado')
    //     ->where([
    //         ['ID_evento',$Id_evento],
    //         ['F_comunicado',$date],
    //         ['Id_proceso','1']
    //     ])
    //     ->orderBy('N_radicado', 'desc')
    //     ->limit(1)
    //     ->get();
            
    //     if(count($radicadocomunicado)==0){
    //         $fechaActual = date("Ymd");
    //         // Obtener el último valor de la base de datos o archivo
    //         $consecutivoP1 = "SAL-ORI";
    //         $consecutivoP2 = $fechaActual;
    //         $consecutivoP3 = '000000';
    //         $ultimoDigito = substr($consecutivoP3, -6);
    //         $consecutivoInicial = $consecutivoP1.$consecutivoP2.$consecutivoP3; 
    //         $nuevoConsecutivo = $ultimoDigito + 1;
    //         // Reiniciar el consecutivo si es un nuevo día
    //         if (date("Ymd") != $fechaActual) {
    //             $nuevoConsecutivo = 0;
    //         }
    //         // Poner ceros a la izquierda para llegar a una longitud de 6 dígitos
    //         $nuevoConsecutivoFormatted = str_pad($nuevoConsecutivo, 6, "0", STR_PAD_LEFT);
    //         $consecutivo = "SAL-ORI" . $fechaActual . $nuevoConsecutivoFormatted;            
    //     }else{
    //         $fechaActual = date("Ymd");
    //         $ultimoConsecutivo = $radicadocomunicado[0]->N_radicado;
    //         $ultimoDigito = substr($ultimoConsecutivo, -6);
    //         $nuevoConsecutivo = $ultimoDigito + 1;
    //         // Reiniciar el consecutivo si es un nuevo día
    //         if (date("Ymd") != $fechaActual) {
    //             $nuevoConsecutivo = 0;
    //         }
    //         // Poner ceros a la izquierda para llegar a una longitud de 6 dígitos
    //         $nuevoConsecutivoFormatted = str_pad($nuevoConsecutivo, 6, "0", STR_PAD_LEFT);
    //         $consecutivo = "SAL-ORI" . $fechaActual . $nuevoConsecutivoFormatted;
    //     }
        
    //     $array_comunicados_correspondencia = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
    //     ->where([['ID_evento',$Id_evento], ['Id_Asignacion',$Id_asignacion], ['T_documento','N/A'], ['Modulo_creacion','adicionDxDtoOrigen']])->get();
    //     foreach ($array_comunicados_correspondencia as $comunicado) {
    //         if ($comunicado['Nombre_documento'] != null && $comunicado['Tipo_descarga'] != 'Manual') {
    //             $filePath = public_path('Documentos_Eventos/'.$comunicado->ID_evento.'/'.$comunicado->Nombre_documento);
    //             if(File::exists($filePath)){
    //                 $comunicado['Existe'] = true;
    //             }
    //             else{
    //                 $comunicado['Existe'] = false;
    //             }
    //         }
    //         else if($comunicado['Tipo_descarga'] === 'Manual'){
    //             $filePath = public_path('Documentos_Eventos/'.$comunicado['ID_evento'].'/'.$comunicado['Asunto']);
    //             if(File::exists($filePath)){
    //                 $comunicado['Existe'] = true;
    //             }
    //             else{
    //                 $comunicado['Existe'] = false;
    //             }
    //         }
    //         else{
    //             $comunicado['Existe'] = false;
    //         }
    //     }
    //     /* Nombre Afp */
    //     $afp_afiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as sie')
    //     ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
    //     ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad')
    //     ->where([['Id_Entidad', $array_datos_calificacion_origen[0]->Id_afp]])
    //     ->get();

    //     /* Traer datos de la AFP de Conocimiento */
    //     $info_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
    //     ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp_entidad_conocimiento', '=', 'sie.Id_Entidad')
    //     ->select('siae.Entidad_conocimiento')
    //     ->where([['siae.ID_evento', $Id_evento]])
    //     ->get();

    //     return view('coordinador.adicionDxDtoOrigen', compact('user', 'datos_bd_DTO_ATEL', 'bandera_hay_dto', 'array_datos_calificacion_origen', 
    //     'bandera_tipo_evento', 'nombre_del_evento_guardado', 'numero_consecutivo', 'motivo_solicitud_actual',
    //     'datos_apoderado_actual', 'array_datos_info_laboral', 'nombre_tipo_accidente','nombre_grado_severidad',
    //     'nombre_factor_riesgo','nombre_tipo_lesion','nombre_parte_cuerpo_afectada',
    //     'listado_documentos_solicitados', 'dato_articulo_12', 'array_datos_examenes_interconsultas',
    //     'array_datos_diagnostico_motcalifi', 'info_adicion_dx', 'array_datos_diagnostico_adicionales','array_comite_interdisciplinario', 
    //     'consecutivo', 'array_comunicados_correspondencia', 'afp_afiliado', 'info_afp_conocimiento'));
    // }

    public function mostrarVistaAdicionDxDTO(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $Id_evento = $request->Id_evento_calitec;
        $Id_asignacion_actual = $request->Id_asignacion_calitec;
        $Id_proceso = $request->Id_proceso_calitec;

        $bandera_hay_dto = "hay_dto_atel";

        // Escenario N°1: Mostrar el formulario vacío cuando se crea una Adición Dx por primera
        // vez desde el módulo nuevo o desde un servicio distinto al de DTO
        $info_adicion_dx_actual = sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $Id_evento],
            ['Id_Asignacion', $Id_asignacion_actual]
        ])->get();
        
        if(count($info_adicion_dx_actual) > 0){
            // echo "DX ACTUAL";
            // Consultamos la información de la Adición Dx
            $info_adicion_dx = sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento],
                ['Id_Asignacion', $Id_asignacion_actual]
            ])->get();

            // TRAER DATOS EXAMENES E INTERCONSULTAS DEL ADX
            $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
            ->whereIn('Id_Asignacion', [$info_adicion_dx[0]->Id_Asignacion])
            ->where([
                ['ID_evento',$Id_evento],
                ['Id_proceso',$Id_proceso],
                ['Estado', 'Activo']
            ])->orderBy('F_examen_interconsulta','DESC')->get();

            // $array_datos_diagnostico_motcalifi = "";
            // TRAER DATOS CIE10 (Diagnóstico motivo de calificación) Visuales
            $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
            ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.Id_proceso', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
            'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
            ->where([['side.ID_evento',$info_adicion_dx[0]->ID_evento],
                ['side.Id_Asignacion',$info_adicion_dx[0]->Id_Asignacion],
                ['side.Id_proceso',$info_adicion_dx[0]->Id_proceso],
                ['side.Estado', '=', 'Activo'],
                ['side.Dx_Adicionado', '=', 'No']
            ])->get();

            // TRAER DATOS CIE10 (Diagnóstico motivo de calificación) Adicionales
            $array_datos_diagnostico_adicionales = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
            ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.Id_proceso', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
            'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10',
            'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal', 'side.F_adicion_CIE10')
            ->where([['side.ID_evento',$info_adicion_dx[0]->ID_evento],
                ['side.Id_Asignacion',$info_adicion_dx[0]->Id_Asignacion],
                ['side.Id_proceso',$info_adicion_dx[0]->Id_proceso],
                ['side.Estado', '=', 'Activo'],
                ['side.Dx_Adicionado', '=', 'Si']
            ])->get(); 

            // Validar si el tipo de evento es Accidente o Sin Cobertura
            $id_evento_guardado = $info_adicion_dx[0]->Tipo_evento;
            if ($id_evento_guardado == 1 || $id_evento_guardado == 4) {
                $bandera_tipo_evento = "tipo_evento_correcto";
            } else {
                $bandera_tipo_evento = "tipo_evento_incorrecto";
            }

            // Nombre del evento guardado
            $array_nombre_del_evento_guardado = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Nombre_evento')
            ->where('Id_Evento', $id_evento_guardado)->get();

            $nombre_del_evento_guardado = $array_nombre_del_evento_guardado[0]->Nombre_evento;

            //Trae si ya marco Articulo 12
            $dato_articulo_12= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_documentos_solicitados_eventos')
            ->select('Articulo_12')
            ->where([
                ['ID_evento', $Id_evento],
                ['Id_Asignacion', $info_adicion_dx[0]->Id_Asignacion], 
                ['Id_proceso', '1'], 
                ['Articulo_12','=','No_mas_seguimiento']
            ])
            ->orderBy('Id_Documento_Solicitado', 'desc')
            ->limit(1)
            ->get();

            // Comite interdisciplinario
            $array_comite_interdisciplinario = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_evento],
                ['Id_Asignacion',$info_adicion_dx[0]->Id_Asignacion]
            ])
            ->get(); 
            
            // Comunicados
            $array_comunicados_correspondencia = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$Id_evento], ['Id_Asignacion',$info_adicion_dx[0]->Id_Asignacion], ['T_documento','N/A'], ['Modulo_creacion','adicionDxDtoOrigen']])->get();

            foreach ($array_comunicados_correspondencia as $comunicado) {
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
                    $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento,$info_adicion_dx[0]->Id_Asignacion,$comunicado["Id_Comunicado"]);
                }
                

            };

            $datos_bd_DTO_ATEL = array();

            $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($info_adicion_dx[0]->Id_Asignacion));

            /* Nombre Afp */
            $afp_afiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
            ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad', 'sie.Emails as Email')
            ->where([['Id_Entidad', $array_datos_calificacion_origen[0]->Id_afp]])
            ->get();

            
        }else{

            // Consultamos la información de la dto
            $datos_bd_DTO_ATEL = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $Id_evento)
            ->get();

            // traemos la informacion de la adición dx más reciente
            $info_adicion_dx_reciente = sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $Id_evento)
            ->orderBy('Id_Asignacion', 'desc')
            ->limit(1)
            ->get();

            // Escenario N°2: Cuando hay una DTO mostrar la información de la DTO
            if(count($datos_bd_DTO_ATEL) > 0 && count($info_adicion_dx_reciente) == 0){
                // echo "SOLO DTO";
                // TRAER DATOS EXAMENES E INTERCONSULTAS DEL DTO ATEL
                $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento],
                    ['Id_Asignacion', $datos_bd_DTO_ATEL[0]->Id_Asignacion],
                    ['Id_proceso',$Id_proceso],
                    ['Estado', 'Activo']
                ])->orderBy('F_examen_interconsulta','DESC')->get();

                $array_datos_diagnostico_adicionales = "";

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

                // Validación: Validar que el tipo de evento sea Accidente o Sin Cobertura
                $id_evento_guardado_dto_atel = $datos_bd_DTO_ATEL[0]->Tipo_evento;

                if ($id_evento_guardado_dto_atel == 1 || $id_evento_guardado_dto_atel == 4) {
                    $bandera_tipo_evento = "tipo_evento_correcto";
                } else {
                    $bandera_tipo_evento = "tipo_evento_incorrecto";
                }

                // Nombre del evento guardado
                $array_nombre_del_evento_guardado = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
                ->select('Nombre_evento')
                ->where('Id_Evento', $id_evento_guardado_dto_atel)->get();
                $nombre_del_evento_guardado = $array_nombre_del_evento_guardado[0]->Nombre_evento;

                //Trae si ya marco Articulo 12
                $dato_articulo_12= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_documentos_solicitados_eventos')
                ->select('Articulo_12')
                ->where([
                    ['ID_evento', $Id_evento],
                    ['Id_Asignacion', $datos_bd_DTO_ATEL[0]->Id_Asignacion], 
                    ['Id_proceso', '1'], 
                    ['Articulo_12','=','No_mas_seguimiento']
                ])
                ->orderBy('Id_Documento_Solicitado', 'desc')
                ->limit(1)
                ->get();

                // Comite interdisciplinario
                $array_comite_interdisciplinario = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento],
                    ['Id_Asignacion',$Id_asignacion_actual]
                ])
                ->get(); 
                
                // Comunicados
                $array_comunicados_correspondencia = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$Id_evento], ['Id_Asignacion',$Id_asignacion_actual], ['T_documento','N/A'], ['Modulo_creacion','adicionDxDtoOrigen']])->get(); 

                foreach ($array_comunicados_correspondencia as $comunicado) {
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
                        $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento,$Id_asignacion_actual,$comunicado["Id_Comunicado"]); 
                    }
                    
                };

                $info_adicion_dx = array();
                $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion_actual));

                /* Nombre Afp */
                $afp_afiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad', 'sie.Emails as Email')
                ->where([['Id_Entidad', $array_datos_calificacion_origen[0]->Id_afp]])
                ->get();

            }
            // Escenario N°3: Cuando hay una Adición Dx mostrar la información de la Adición Dx más reciente
            elseif((count($datos_bd_DTO_ATEL) > 0 && count($info_adicion_dx_reciente) > 0) || (count($datos_bd_DTO_ATEL) == 0 && count($info_adicion_dx_reciente) > 0)){
                // echo "DX MÁS RECIENTE";
                // Consultamos la información de la Adición Dx
                $info_adicion_dx = sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
                ->where('ID_evento', $Id_evento)
                ->orderBy('Id_Asignacion', 'desc')
                ->limit(1)
                ->get();


                // TRAER DATOS EXAMENES E INTERCONSULTAS DEL ADX
                $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                ->whereIn('Id_Asignacion', [$info_adicion_dx[0]->Id_Asignacion])
                ->where([
                    ['ID_evento',$Id_evento],
                    ['Id_proceso',$Id_proceso],
                    ['Estado', 'Activo']
                ])->orderBy('F_examen_interconsulta','DESC')->get();

                // $array_datos_diagnostico_motcalifi = "";
                // TRAER DATOS CIE10 (Diagnóstico motivo de calificación) Visuales
                $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.Id_proceso', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
                ->where([['side.ID_evento',$info_adicion_dx[0]->ID_evento],
                    ['side.Id_Asignacion',$info_adicion_dx[0]->Id_Asignacion],
                    ['side.Id_proceso',$info_adicion_dx[0]->Id_proceso],
                    ['side.Estado', '=', 'Activo'],
                    ['side.Dx_Adicionado', '=', 'No']
                ])->get();

                // TRAER DATOS CIE10 (Diagnóstico motivo de calificación) Adicionales
                $array_datos_diagnostico_adicionales = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.Id_proceso', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10',
                'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal', 'side.F_adicion_CIE10')
                ->where([['side.ID_evento',$info_adicion_dx[0]->ID_evento],
                    ['side.Id_Asignacion',$info_adicion_dx[0]->Id_Asignacion],
                    ['side.Id_proceso',$info_adicion_dx[0]->Id_proceso],
                    ['side.Estado', '=', 'Activo'],
                    ['side.Dx_Adicionado', '=', 'Si']
                ])->get(); 

                // Validar si el tipo de evento es Accidente o Sin Cobertura
                $id_evento_guardado = $info_adicion_dx[0]->Tipo_evento;
                if ($id_evento_guardado == 1 || $id_evento_guardado == 4) {
                    $bandera_tipo_evento = "tipo_evento_correcto";
                } else {
                    $bandera_tipo_evento = "tipo_evento_incorrecto";
                }

                // Nombre del evento guardado
                $array_nombre_del_evento_guardado = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
                ->select('Nombre_evento')
                ->where('Id_Evento', $id_evento_guardado)->get();

                $nombre_del_evento_guardado = $array_nombre_del_evento_guardado[0]->Nombre_evento;

                //Trae si ya marco Articulo 12
                $dato_articulo_12= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_documentos_solicitados_eventos')
                ->select('Articulo_12')
                ->where([
                    ['ID_evento', $Id_evento],
                    ['Id_Asignacion', $info_adicion_dx[0]->Id_Asignacion], 
                    ['Id_proceso', '1'], 
                    ['Articulo_12','=','No_mas_seguimiento']
                ])
                ->orderBy('Id_Documento_Solicitado', 'desc')
                ->limit(1)
                ->get();

                // Comite interdisciplinario
                $array_comite_interdisciplinario = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento],
                    ['Id_Asignacion',$Id_asignacion_actual]
                ])
                ->get(); 
                
                // Comunicados
                $array_comunicados_correspondencia = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$Id_evento], ['Id_Asignacion',$Id_asignacion_actual], ['T_documento','N/A'], ['Modulo_creacion','adicionDxDtoOrigen']])->get();

                foreach ($array_comunicados_correspondencia as $comunicado) {
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
                        $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento,$Id_asignacion_actual,$comunicado["Id_Comunicado"]);
                    }
                    
                };
                // $datos_bd_DTO_ATEL = array();
                $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion_actual));

                /* Nombre Afp */
                $afp_afiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad', 'sie.Emails as Email')
                ->where([['Id_Entidad', $array_datos_calificacion_origen[0]->Id_afp]])
                ->get();
            }
            // Escenario N°4: Mostrar el formulario vacío para llenar la Adición Dx
            elseif(count($datos_bd_DTO_ATEL) == 0 && count($info_adicion_dx_reciente) == 0){
                // echo "FORMULARIO VACÍO";
                // $info_adicion_dx = sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
                // ->where([
                //     ['ID_evento', $Id_evento],
                //     ['Id_Asignacion', $Id_asignacion_actual]
                // ])->get();
                
                // $datos_bd_DTO_ATEL = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
                // ->where('ID_evento', $Id_evento)
                // ->get();

                // $info_adicion_dx = "";
                // $datos_bd_DTO_ATEL = "";
                $info_adicion_dx = array();
                $datos_bd_DTO_ATEL = array();
                $array_datos_examenes_interconsultas = "";
                $array_datos_diagnostico_motcalifi = "";
                $array_datos_diagnostico_adicionales = "";
                $dato_articulo_12 = "";
                $array_comite_interdisciplinario = "";
                $nombre_del_evento_guardado = "";
                $array_comunicados_correspondencia = array();

                $info_tipo_evento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_lista_tipo_eventos as slte')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_eventos as sie', 'slte.Id_Evento', '=', 'sie.Tipo_evento')
                ->select('slte.Nombre_evento', 'slte.Id_Evento')
                ->where('sie.ID_evento', $Id_evento)->get();

                $id_evento_guardado = $info_tipo_evento[0]->Id_Evento;
                if ($id_evento_guardado == 1 || $id_evento_guardado == 4) {
                    $bandera_tipo_evento = "tipo_evento_correcto";
                } else {
                    $bandera_tipo_evento = "tipo_evento_incorrecto";
                }

                $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion_actual));

                /* Nombre Afp */
                $afp_afiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Ciudad', '=', 'sldm.Id_municipios')
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad', 'sie.Emails as Email')
                ->where([['Id_Entidad', $array_datos_calificacion_origen[0]->Id_afp]])
                ->get();
            }
        }

        // $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion_actual));

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
        $array_datos_info_laboral = $this->globalService->retornarInformaciónLaboral($Id_evento);

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

        // creación de consecutivo para el comunicado
        $consecutivo = $this->getRadicado('origen',$Id_evento);
        // dd($array_comite_interdisciplinario);
        /* Traer datos de la AFP de Conocimiento */
        $info_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp_entidad_conocimiento', '=', 'sie.Id_Entidad')
        ->select('siae.Entidad_conocimiento')
        ->where([['siae.ID_evento', $Id_evento]])
        ->get();

        // Consultamos si el caso está en la bandeja de Notificaciones
        $array_caso_notificado = BandejaNotifiController::evento_en_notificaciones($Id_evento,$Id_asignacion_actual);

        if(count($array_caso_notificado) > 0){
            $caso_notificado = $array_caso_notificado[0]->Notificacion;
        }

        //Traer el N_siniestro del evento
        $N_siniestro_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('N_siniestro')
        ->where([['ID_evento',$Id_evento]])
        ->get();

        return view('coordinador.adicionDxDtoOrigen', compact('user', 'Id_asignacion_actual', 'datos_bd_DTO_ATEL', 'bandera_hay_dto', 'array_datos_calificacion_origen', 
            'bandera_tipo_evento', 'nombre_del_evento_guardado', 'numero_consecutivo', 'motivo_solicitud_actual',
            'datos_apoderado_actual', 'array_datos_info_laboral','listado_documentos_solicitados', 'dato_articulo_12', 'array_datos_examenes_interconsultas',
            'array_datos_diagnostico_motcalifi', 'info_adicion_dx', 'array_datos_diagnostico_adicionales','array_comite_interdisciplinario', 'consecutivo', 
            'array_comunicados_correspondencia', 'afp_afiliado', 'info_afp_conocimiento', 'caso_notificado', 'N_siniestro_evento'
            )
        );
        
       
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
            ->whereNotIn('Nombre_parametro', ['Mixto','Integral','Derivado del evento','No derivado del evento'])
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
            ->where([['sgt.Id_proceso_equipo', '=', $request->idProcesoLider]])
            ->get();

            $informacion_datos_reviso = json_decode(json_encode($array_datos_reviso, true));
            return response()->json($informacion_datos_reviso);
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

    // public function GuardaroActualizarInfoAdicionDX(Request $request){
    //     if(!Auth::check()){
    //         return redirect('/');
    //     }
    //     $time = time();
    //     $datetime = date("Y-m-d H:i:s");
    //     $date = date("Y-m-d", $time);
    //     $nombre_usuario = Auth::user()->name;

    //     // Paso N°1: Actualizar el motivo de solicitud y tipo de evento
    //     $datos_actualizar_motivo_solicitud = [
    //         'Id_motivo_solicitud' => $request->motivo_solicitud
    //     ];

    //     sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
    //     ->where('ID_evento', $request->ID_Evento)->update($datos_actualizar_motivo_solicitud);

    //     $datos_actualizar_tipo_evento = [
    //         'Tipo_evento' => $request->Tipo_evento
    //     ];

    //     sigmel_informacion_eventos::on('sigmel_gestiones')
    //     ->where('ID_evento', $request->ID_Evento)->update($datos_actualizar_tipo_evento);

    //     // Paso N°2: Guardar los datos de Examenes interconsultas

    //     // Seteo del autoincrement para mantener el primary key siempre consecutivo.
    //     $max_id = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
    //     ->max('Id_Examenes_interconsultas');
    //     if ($max_id <> "") {
    //         DB::connection('sigmel_gestiones')
    //         ->statement("ALTER TABLE sigmel_informacion_examenes_interconsultas_eventos AUTO_INCREMENT = ".($max_id));
    //     }

    //     if (!empty($request->Examenes_interconsultas)) {
    //         if (count($request->Examenes_interconsultas) > 0) {
    //             // Captura del array de los datos de la tabla
    //             $array_examenes_interconsultas = $request->Examenes_interconsultas;
    
    //             // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
    //             $array_datos_organizados_examenes_interconsultas = [];
    //             foreach ($array_examenes_interconsultas as $subarray_datos) {
    
    //                 array_unshift($subarray_datos, $request->Id_proceso);
    //                 array_unshift($subarray_datos, $request->Id_Asignacion);
    //                 array_unshift($subarray_datos, $request->ID_Evento);
    
    //                 $subarray_datos[] = $nombre_usuario;
    //                 $subarray_datos[] = $date;
    
    //                 array_push($array_datos_organizados_examenes_interconsultas, $subarray_datos);
    //             }
    
    //             // Creación de array con los campos de la tabla: sigmel_informacion_examenes_interconsultas_eventos
    //             $array_tabla_examen_interconsulta = ['ID_evento','Id_Asignacion','Id_proceso',
    //             'F_examen_interconsulta','Nombre_examen_interconsulta','Descripcion_resultado',
    //             'Nombre_usuario','F_registro'];
    
    //             // Combinación de los campos de la tabla con los datos
    //             $array_datos_con_keys_examenes_interconsultas = [];
    //             foreach ($array_datos_organizados_examenes_interconsultas as $subarray_datos_organizados_examenes_interconsultas) {
    //                 array_push($array_datos_con_keys_examenes_interconsultas, array_combine($array_tabla_examen_interconsulta, $subarray_datos_organizados_examenes_interconsultas));
    //             }
    
    //             // Inserción de la información
    //             foreach ($array_datos_con_keys_examenes_interconsultas as $insertar_examen) {
    //                 sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')->insert($insertar_examen);
    //             } 
    //         }
    //     }

    //     // Paso N°3: Guardar los datos de Adiciones de Diagnósticos
    //     $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
    //     ->max('Id_Diagnosticos_motcali');
    //     if ($max_id <> "") {
    //         DB::connection('sigmel_gestiones')
    //         ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
    //     }
 
    //     if (!empty($request->Adicion_motivo_calificacion)) {
    //         if (count($request->Adicion_motivo_calificacion) > 0) {
    //             // Captura del array de los datos de la tabla
    //             $array_diagnosticos_motivo_calificacion = $request->Adicion_motivo_calificacion;
    //             $array_datos_organizados_motivo_calificacion = [];
    //             foreach ($array_diagnosticos_motivo_calificacion as $subarray_datos_motivo_calificacion) {
    
    //                 array_unshift($subarray_datos_motivo_calificacion, $request->Id_proceso);
    //                 array_unshift($subarray_datos_motivo_calificacion, $request->Id_Asignacion);
    //                 array_unshift($subarray_datos_motivo_calificacion, $request->ID_Evento);
    
    //                 $subarray_datos_motivo_calificacion[] = $nombre_usuario;
    //                 $subarray_datos_motivo_calificacion[] = $date;
    
    //                 array_push($array_datos_organizados_motivo_calificacion, $subarray_datos_motivo_calificacion);
    //             }
    
    //             // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
    //             $array_tabla_diagnosticos_motivo_calificacion = ['ID_evento','Id_Asignacion','Id_proceso',
    //             'F_adicion_CIE10', 'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
    //             'Principal', 'Nombre_usuario','F_registro'];
    //             // Combinación de los campos de la tabla con los datos
    //             $array_datos_con_keys_motivo_calificacion = [];
    //             foreach ($array_datos_organizados_motivo_calificacion as $subarray_datos_organizados_motivo_calificacion) {
    //                 array_push($array_datos_con_keys_motivo_calificacion, array_combine($array_tabla_diagnosticos_motivo_calificacion, $subarray_datos_organizados_motivo_calificacion));
    //             }


    //             // Inserción de la información
    //             foreach ($array_datos_con_keys_motivo_calificacion as $insertar_diagnostico) {
    //                 sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico);
    //             }
    //         }
    //     }

    //     // Paso N° 4: Guardar los datos del formulario en la  tabla sigmel_informacion_adiciones_dx_eventos
    //     $Tipo_evento = $request->Tipo_evento;
    //     if (!empty($request->Relacion_documentos)) {
    //         $total_relacion_documentos = implode(", ", $request->Relacion_documentos);                
    //     }else{
    //         $total_relacion_documentos = '';
    //     }


    //     // Tipo de formulario: Accidente
    //     if ($Tipo_evento == 1) {
    //         $datos_formulario = [
    //             'ID_evento' => $request->ID_Evento,
    //             'Id_Asignacion' => $request->Id_Asignacion,
    //             'Id_proceso' => $request->Id_proceso,
    //             'Id_Dto_ATEL' => $request->Id_Dto_ATEL,
    //             'Activo' => $request->Activo,
    //             'Tipo_evento' => $request->Tipo_evento,
    //             'N_siniestro' => $request->N_siniestro,
    //             'Relacion_documentos' => $total_relacion_documentos,
    //             'Otros_relacion_documentos' => $request->Otros_relacion_documentos,
    //             'Sustentacion_Adicion_Dx' => $request->Sustentacion_Adicion_Dx,
    //             'Origen' => $request->Origen,
    //             'N_radicado' => $request->radicado_dictamen,
    //             'Nombre_usuario' => $nombre_usuario,
    //             'F_registro' => $date
    //         ];

    //     }

    //     $Id_Adiciones_Dx = $request->Id_Adiciones_Dx;

    //     if ($Id_Adiciones_Dx == "") {
    //         sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')->insert($datos_formulario);
            
    //         $datos_info_comunicado_eventos = [
    //             'ID_Evento' => $request->ID_Evento,
    //             'Id_proceso' => $request->Id_proceso,
    //             'Id_Asignacion' => $request->Id_Asignacion,
    //             'Ciudad' => 'N/A',
    //             'F_comunicado' => $date,
    //             'N_radicado' => $request->radicado_dictamen,
    //             'Cliente' => 'N/A',
    //             'Nombre_afiliado' => 'N/A',
    //             'T_documento' => 'N/A',
    //             'N_identificacion' => 'N/A',
    //             'Destinatario' => 'N/A',
    //             'Nombre_destinatario' => 'N/A',
    //             'Nit_cc' => 'N/A',
    //             'Direccion_destinatario' => 'N/A',
    //             'Telefono_destinatario' => '001',
    //             'Email_destinatario' => 'N/A',
    //             'Id_departamento' => '001',
    //             'Id_municipio' => '001',
    //             'Asunto'=> 'N/A',
    //             'Cuerpo_comunicado' => 'N/A',
    //             'Forma_envio' => '0',
    //             'Elaboro' => $nombre_usuario,
    //             'Reviso' => 'N/A',
    //             'Anexos' => 'N/A',
    //             'Tipo_descarga' => 'Dictamen',
    //             'Modulo_creacion' => 'adicionDxDtoOrigen',
    //             'Reemplazado' => 0,
    //             'Nombre_usuario' => $nombre_usuario,
    //             'F_registro' => $date,
    //         ];
    
    //         sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);

    //         $mensaje = 'Información guardada satisfactoriamente.';
    //     } else {
    //         sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
    //         ->where('Id_Adiciones_Dx', $Id_Adiciones_Dx)->update($datos_formulario);
    //         $mensaje = 'Información actualizada satisfactoriamente.';

    //         $comunicado_reemplazado = [
    //             'Reemplazado' => 0
    //         ];
    //         sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
    //             ->where([
    //                 ['ID_evento',$request->ID_Evento],
    //                 ['Id_Asignacion',$request->Id_Asignacion],
    //                 ['N_radicado',$request->radicado_dictamen]
    //                 ])
    //         ->update($comunicado_reemplazado);
    //     }

    //     // Actualizacion del profesional calificador
    //     $datos_profesional_calificador = [
    //         'Id_profesional' => Auth::user()->id,
    //         'Nombre_profesional' => $nombre_usuario
    //     ];

    //     sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
    //     ->where('Id_Asignacion', $request->Id_Asignacion)->update($datos_profesional_calificador);

    //     sleep(2);
    //     $datos_info_accion_evento= [    
    //         'F_calificacion_servicio' => $datetime
    //     ];

    //     sigmel_informacion_accion_eventos::on('sigmel_gestiones')
    //     ->where('ID_evento',$request->ID_Evento)->update($datos_info_accion_evento);
        
    //     $mensajes = array(
    //         "parametro" => 'agregar_dto_atel',
    //         "mensaje" => $mensaje
    //     ); 

    //     return json_decode(json_encode($mensajes, true));

    // }

    public function GuardaroActualizarInfoAdicionDX(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
		$datetime = date("Y-m-d H:i:s");
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $radicado = $this->disponible($request->radicado_dictamen,$request->ID_Evento)->getRadicado('origen',$request->ID_Evento);
        
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

        // Paso N° 2: Guardar los datos de Examenes interconsultas que vienen de una dto o de otra adx.
        $IdExamenesInterconsultas = $request->IdExamenesInterconsultas;
        if(!empty($IdExamenesInterconsultas)){
            $registrosDataExamenesInteconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
            ->select('ID_evento', 'Id_Asignacion', 'Id_proceso', 'F_examen_interconsulta', 'Nombre_examen_interconsulta', 'Descripcion_resultado')
            ->whereIn('Id_Examenes_interconsultas', $IdExamenesInterconsultas)->get();             
            if (!empty($registrosDataExamenesInteconsultas[0]->ID_evento)) {
                sleep(3);
                foreach ($registrosDataExamenesInteconsultas as $registro) { 
                    $registro->Id_Asignacion = $request->Id_Asignacion;
                    $registro->Nombre_usuario = $nombre_usuario;
                    $registro->F_registro = $date;
                    sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                    ->insert($registro->toArray());
                }
            } 
        }

        // Paso N° 2.1: Guardar los datos de Examenes interconsultas Nuevos que se inserten nuevos

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

        // Paso N°3: Guardar los datos de los Diagnosticos Motivo Calificación que vienen de una dto otra adx.
        $IdDiagMotCali = $request->IdDiagMotCali;
        if (!empty($IdDiagMotCali)) {
            $registrosDataDiagMotCali = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
            ->select('ID_evento','Id_Asignacion','Id_proceso','F_adicion_CIE10', 'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10','Principal', 'Dx_Adicionado')
            ->whereIn('Id_Diagnosticos_motcali', $IdDiagMotCali)->get();
            if (!empty($registrosDataDiagMotCali[0]->ID_evento)) {
                sleep(3);
                foreach ($registrosDataDiagMotCali as $registro) {
                    $registro->Id_Asignacion = $request->Id_Asignacion;
                    $registro->Dx_Adicionado = 'No';
                    $registro->Nombre_usuario = $nombre_usuario;
                    $registro->F_registro = $date;
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
                    ->insert($registro->toArray());
                }
            }
        }

        // Paso N° 3.1: Guardar los datos de Diagnosticos Motivo Calificación que se inserten nuevos
        $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->max('Id_Diagnosticos_motcali');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
        }

        if(!empty($request->datos_finales_mot_calificacion)){
            if (count($request->datos_finales_mot_calificacion) > 0) {
                // Captura del array de los datos de la tabla
                $array_diagnosticos_motivo_calificacion = $request->datos_finales_mot_calificacion;
                $array_datos_organizados_motivo_calificacion = [];
                foreach ($array_diagnosticos_motivo_calificacion as $subarray_datos_motivo_calificacion) {

                    array_unshift($subarray_datos_motivo_calificacion, $request->Id_proceso);
                    array_unshift($subarray_datos_motivo_calificacion, $request->Id_Asignacion);
                    array_unshift($subarray_datos_motivo_calificacion, $request->ID_Evento);

                    $subarray_datos_motivo_calificacion[] = "No";
                    $subarray_datos_motivo_calificacion[] = $nombre_usuario;
                    $subarray_datos_motivo_calificacion[] = $date;

                    array_push($array_datos_organizados_motivo_calificacion, $subarray_datos_motivo_calificacion);
                }

                // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
                $array_tabla_diagnosticos_motivo_calificacion = ['ID_evento','Id_Asignacion','Id_proceso','CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
                'Principal', 'Dx_Adicionado','Nombre_usuario','F_registro'];
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

        // Paso N°4: Guardar los datos de Adiciones de Diagnósticos que vienen de otras adx
        $IdDiagMotCaliAdi = $request->IdDiagMotCaliAdi;
        if (!empty($IdDiagMotCaliAdi)) {
            $registrosDataDiagMotCali = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
            ->select('ID_evento','Id_Asignacion','Id_proceso','F_adicion_CIE10', 'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10','Principal','Dx_Adicionado')
            ->whereIn('Id_Diagnosticos_motcali', $IdDiagMotCaliAdi)->get();
            if (!empty($registrosDataDiagMotCali[0]->ID_evento)) {
                sleep(3);
                foreach ($registrosDataDiagMotCali as $registro) {
                    $registro->Id_Asignacion = $request->Id_Asignacion;
                    $registro->Dx_Adicionado = 'Si';
                    $registro->Nombre_usuario = $nombre_usuario;
                    $registro->F_registro = $date;
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
                    ->insert($registro->toArray());
                }
            }
        }

        // Paso N°4.1 : Guardar los datos de Adiciones de Diagnósticos que se inserten nuevos
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
    
                    $subarray_datos_motivo_calificacion[] = "Si";
                    $subarray_datos_motivo_calificacion[] = $nombre_usuario;
                    $subarray_datos_motivo_calificacion[] = $date;
    
                    array_push($array_datos_organizados_motivo_calificacion, $subarray_datos_motivo_calificacion);
                }
    
                // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
                $array_tabla_diagnosticos_motivo_calificacion = ['ID_evento','Id_Asignacion','Id_proceso',
                'F_adicion_CIE10', 'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
                'Principal', 'Dx_Adicionado','Nombre_usuario','F_registro'];
                // Combinación de los campos de la tabla con los datos
                $array_datos_con_keys_motivo_calificacion = [];
                foreach ($array_datos_organizados_motivo_calificacion as $subarray_datos_organizados_motivo_calificacion) {
                    array_push($array_datos_con_keys_motivo_calificacion, array_combine($array_tabla_diagnosticos_motivo_calificacion, $subarray_datos_organizados_motivo_calificacion));
                }

                // echo "<pre>";
                // print_r($array_datos_con_keys_motivo_calificacion);
                // echo "</pre>";

                // Inserción de la información
                foreach ($array_datos_con_keys_motivo_calificacion as $insertar_diagnostico) {
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico);
                }
            }
        }

        // Paso N° 5: Guardar los datos del formulario en la  tabla sigmel_informacion_adiciones_dx_eventos
        $Tipo_evento = $request->Tipo_evento;
        if (!empty($request->Relacion_documentos)) {
            $total_relacion_documentos = implode(", ", $request->Relacion_documentos);                
        }else{
            $total_relacion_documentos = '';
        }


        $Id_Adiciones_Dx = $request->Id_Adiciones_Dx;

        //SOLUCIÓN PROVISIONAL PARA CORREGIR ERROR DE MULTIPLES DX, EN LOS CUALES NO GENERA DICTAMEN DEBIDO A QUE LA LOGICA ACTUAL SOLO PERMITE UN DX
        $documentos = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$request->ID_Evento],
            ['Id_Asignacion',$request->Id_Asignacion], 
            ['T_documento','N/A'], 
            ['Modulo_creacion','adicionDxDtoOrigen']])->get();
        
        // Tipo de formulario: Accidente
        if ($Tipo_evento == 1) {
            $datos_formulario = [
                'ID_evento' => $request->ID_Evento,
                'Id_Asignacion' => $request->Id_Asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Id_Dto_ATEL' => $request->Id_Dto_ATEL,
                'Activo' => $request->Activo,
                'Tipo_evento' => $request->Tipo_evento,
                'N_siniestro' => $request->N_siniestro,
                'Relacion_documentos' => $total_relacion_documentos,
                'Otros_relacion_documentos' => $request->Otros_relacion_documentos,
                'Sustentacion_Adicion_Dx' => $request->Sustentacion_Adicion_Dx,
                'Origen' => $request->Origen,
                'N_radicado' => $request->radicado_dictamen,
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
                'Sustentacion' => $request->Sustentacion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];

        }

        $Id_Adiciones_Dx = $request->Id_Adiciones_Dx;

        //SOLUCIÓN PROVISIONAL PARA CORREGIR ERROR DE MULTIPLES DX, EN LOS CUALES NO GENERA DICTAMEN DEBIDO A QUE LA LOGICA ACTUAL SOLO PERMITE UN DX
        $documentos = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$request->ID_Evento], ['Id_Asignacion',$request->Id_Asignacion], ['T_documento','N/A'], ['Modulo_creacion','adicionDxDtoOrigen']])->get();
        
        //Copias y destinatario
        $info_afp_conocimiento = $this->globalService->retornarcuentaConAfpConocimiento($request->ID_Evento);
            if(!empty($info_afp_conocimiento[0]->Entidad_conocimiento) && $info_afp_conocimiento[0]->Entidad_conocimiento == "Si"){
                $agregar_copias_dml = "Afiliado, Empleador, EPS, AFP, ARL, AFP_Conocimiento";
            }
            else{
                $agregar_copias_dml = "Afiliado, Empleador, EPS, AFP, ARL";
            }
            $Destinatario = 'Afp';

        if ($Id_Adiciones_Dx == "" || count($documentos) == 0) {
            //Asigna radicado correspondiente al nuevo comunicado
            $datos_formulario['N_radicado'] = $radicado;

            sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')->insert($datos_formulario);

            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $request->N_siniestro
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$request->ID_Evento]])
            ->update($dato_actualizar_n_siniestro);

            sleep(2);

            //Se asignan los IDs de destinatario por cada posible destinatario
            $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario();

            $datos_info_comunicado_eventos = [
                'ID_Evento' => $request->ID_Evento,
                'Id_proceso' => $request->Id_proceso,
                'Id_Asignacion' => $request->Id_Asignacion,
                'Ciudad' => 'N/A',
                'F_comunicado' => $date,
                'N_radicado' => $radicado,
                'Cliente' => 'N/A',
                'Nombre_afiliado' => 'N/A',
                'T_documento' => 'N/A',
                'N_identificacion' => 'N/A',
                'Destinatario' => $Destinatario,
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
                'Agregar_copia' => $agregar_copias_dml,
                'Tipo_descarga' => 'Dictamen',
                'Modulo_creacion' => 'adicionDxDtoOrigen',
                'Reemplazado' => 0,
                'N_siniestro' => $request->N_siniestro,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);

            
            // Actualizacion del profesional calificador
            $datos_profesional_calificador = [
                'Id_calificador' => Auth::user()->id,
                'Nombre_calificador' => $nombre_usuario
            ];
    
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $request->Id_Asignacion)->update($datos_profesional_calificador);
            
            $mensaje = 'Información guardada satisfactoriamente.';
            
        } else {
            sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
            ->where('Id_Adiciones_Dx', $Id_Adiciones_Dx)->update($datos_formulario);
            $mensaje = 'Información actualizada satisfactoriamente.';

            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $request->N_siniestro
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$request->ID_Evento]])
            ->update($dato_actualizar_n_siniestro);

            sleep(2);
            //Copias y destinatario de un dictamen segun la ficha PBS054
            $comunicado_reemplazado = [
                'Destinatario' => $Destinatario,
                'Agregar_copia' => $agregar_copias_dml,
                'Reemplazado' => 0,
                'N_siniestro' => $request->N_siniestro,
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$request->ID_Evento],
                    ['Id_Asignacion',$request->Id_Asignacion],
                    ['N_radicado',$request->radicado_dictamen]
                    ])
            ->update($comunicado_reemplazado);
        }

		
		sleep(2);
        $datos_info_accion_evento= [    
            'F_calificacion_servicio' => $datetime
        ];

        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
        ->where('ID_evento',$request->ID_Evento)->update($datos_info_accion_evento);
        
        /* Aqui se realizaba la actualización de cierta información del DTO, debido a la ficha PBS051 esto ya no se realiza */

        // if($request->bandera_info_evento == 'Actualizar' || !empty($request->Justificacion_revision_origen)){
        //     //Informacion del evento
        //     $datos_formulario = [
        //         'Tipo_accidente' => $request->Tipo_accidente,
        //         'Fecha_evento' => $request->Fecha_evento,
        //         'N_siniestro' => $request->N_siniestro,
        //         'Hora_evento' => $request->Hora_evento,
        //         'Grado_severidad' => $request->Grado_severidad,
        //         'Mortal' => $request->Mortal,
        //         'Fecha_fallecimiento' => $request->Fecha_fallecimiento,
        //         'Descripcion_FURAT' => $request->Descripcion_FURAT,
        //         'Factor_riesgo' => $request->Factor_riesgo,
        //         'Tipo_lesion' => $request->Tipo_lesion,
        //         'Parte_cuerpo_afectada' => $request->Parte_cuerpo_afectada,
        //         'Justificacion_revision_origen' => $request->Justificacion_revision_origen,
        //     ];
                
        //     sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
        //         ->where('ID_evento',$request->ID_Evento)->update($datos_formulario);
        // }
        
        //Capturamos el Id del comunicado para poder generarlo en el servidor
        $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$request->ID_Evento],
            ['Id_Asignacion',$request->Id_Asignacion],
            ['N_radicado',$request->radicado_dictamen]
            ])
        ->value('Id_Comunicado');

        $mensajes = array(
            "parametro" => 'agregar_dto_atel',
            'Id_Comunicado' => $id_comunicado ? $id_comunicado : null,
            "mensaje" => $mensaje
        ); 
		

        return json_decode(json_encode($mensajes, true));

    }

    // Comite Interdisciplinario

    public function guardarcomiteinterdisciplinarioAdx(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);
        $Id_Evento = $request->Id_Evento;
        $Id_Proceso_adicion_dx = $request->Id_Proceso_adicion_dx;
        $Id_Asignacion_adicion_dx = $request->Id_Asignacion_adicion_dx;
        $visar = $request->visar;
        $profesional_comite = $request->profesional_comite;
        $f_visado_comite = $request->f_visado_comite;

        $datos_comiteInterdisciplinario = [
            'ID_evento' => $Id_Evento,
            'Id_proceso' => $Id_Proceso_adicion_dx,
            'Id_Asignacion' => $Id_Asignacion_adicion_dx,
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

    // Correspondencia

    public function guardarcorrespondenciaAdx(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);

        $Id_Evento = $request->Id_Evento;
        $Id_Proceso_adicion_dx = $request->Id_Proceso_adicion_dx;
        $Id_Asignacion_adicion_dx = $request->Id_Asignacion_adicion_dx;
        $oficio_origen = $request->oficio_origen;
        if ($oficio_origen == '') {
            $oficio_origen = 'No';
        }
      
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
        $beneficiario = $request->beneficiario;
        $empleador = $request->empleador;
        $eps = $request->eps;
        $afp = $request->afp;
        $afp_conocimiento = $request->afp_conocimiento;
        $arl = $request->arl;
        $jrci = $request->jrci;        
        $cual = $request->cual;
        if($cual == ''){
            $cual = null;
        }
        $jnci = $request->jnci;
        // $agregar_copias_comu = $empleador.','.$eps.','.$afp.','.$arl.','.$jrci.','.$jnci;

        $variables_llenas = array();

        if (!empty($beneficiario)) {
            $variables_llenas[] = $beneficiario;
        }
        if (!empty($empleador)) {
            $variables_llenas[] = $empleador;
        }
        if (!empty($eps)) {
            $variables_llenas[] = $eps;
        }
        if (!empty($afp)) {
            $variables_llenas[] = $afp;
        }
        if (!empty($afp_conocimiento)) {
            $variables_llenas[] = $afp_conocimiento;
        }
        if (!empty($arl)) {
            $variables_llenas[] = $arl;
        }
        if (!empty($jrci)) {
            $variables_llenas[] = $jrci;
        }
        if (!empty($jnci)) {
            $variables_llenas[] = $jnci;
        }

        if(count($variables_llenas) > 0){
            $agregar_copias_comu = implode(', ', $variables_llenas);
        }else{
            $agregar_copias_comu = '';
        }

        $anexos = $request->anexos;
        $elaboro = $request->elaboro;
        $reviso = $request->reviso;
        $firmar = $request->firmar;
        $ciudad = $request->ciudad;
        $f_correspondencia = $request->f_correspondencia;
        $radicado = $this->disponible($request->radicado,$Id_Evento)->getRadicado('origen',$Id_Evento);
        $bandera_correspondecia_guardar_actualizar = $request->bandera_correspondecia_guardar_actualizar;

        /* Se completan los siguientes datos para lo del tema del pbs 014 */

        // eL número de identificacion será el del afiliado.
        $array_nro_ident_afi = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('Nro_identificacion')
        ->where([['ID_evento', $Id_Evento]])
        ->get();

        if (count($array_nro_ident_afi) > 0) {
            $nro_identificacion = $array_nro_ident_afi[0]->Nro_identificacion;
        }else{
            $nro_identificacion = 'N/A';
        }

        // el nombre del destinatario principal dependerá de lo siguiente:
        // Si no se seleccciona la opción otro destinatario principal: el destinatario será por defecto la AFP que tenga el afiliado.
        // Si selecciona la opción otro destinatario principal: el destinataria dependerá del tipo de destinatario que se seleccione.

        // Caso 1: Arl, Caso 2: Afp, Caso 3: Eps, Caso 4: Afiliado, Caso 5: Empleador.
        if ($otrodestinariop == '') {
            $Destinatario = 'Afp';
        } else {
            switch ($tipo_destinatario_principal) {
                case '1':
                    $Destinatario = 'Arl';
                break;

                case '2':
                    $Destinatario = 'Afp';
                break;

                case '3':
                    $Destinatario = 'Eps';
                break;

                case '4':
                    $Destinatario = 'Afiliado';
                break;

                case '5':
                    $Destinatario = 'Empleador';
                break;
                
                default:
                    $Destinatario = 'N/A';
                break;
            }
        }

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
                'Copia_afiliado' => $beneficiario,
                'Copia_empleador' => $empleador,
                'Copia_eps' => $eps,
                'Copia_afp' => $afp,
                'Copia_arl' => $arl,
                'Copia_afp_conocimiento' => $afp_conocimiento,
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
                ['ID_evento',$Id_Evento],
                ['Id_Asignacion',$Id_Asignacion_adicion_dx]
            ])->update($datos_correspondencia);    
            
            //Se asignan los IDs de destinatario por cada posible destinatario
            $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario();
    
            $datos_info_comunicado_eventos = [
                'ID_Evento' => $Id_Evento,
                'Id_proceso' => $Id_Proceso_adicion_dx,
                'Id_Asignacion' => $Id_Asignacion_adicion_dx,
                'Ciudad' => $ciudad,
                'F_comunicado' => $date,
                'N_radicado' => $radicado,
                'Cliente' => 'N/A',
                'Nombre_afiliado' => $destinatario_principal,
                'T_documento' => 'N/A',
                'N_identificacion' => $nro_identificacion,
                'Destinatario' => $Destinatario,
                'Nombre_destinatario' => $request->nombre_destinatariopri ? $request->nombre_destinatariopri : 'N/A',
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
                'Tipo_descarga' => 'Comunicado',
                'Modulo_creacion' => 'adicionDxDtoOrigen',
                'Reemplazado' => 0,
                'Otro_destinatario' => $request->nombre_destinatariopri ? 1 : 0,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_comunicado_eventos);
    
            $mensajes = array(
                "parametro" => 'insertar_correspondencia',
                'Id_Comunicado' => $id_comunicado ? $id_comunicado : null,
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
                'Copia_afiliado' => $beneficiario,
                'Copia_empleador' => $empleador,
                'Copia_eps' => $eps,
                'Copia_afp' => $afp,
                'Copia_arl' => $arl,
                'Copia_afp_conocimiento' => $afp_conocimiento,
                'Copia_jr' => $jrci,
                'Cual_jr' => $cual,
                'Copia_jn' => $jnci,
                'Anexos' => $anexos,
                'Elaboro' => $elaboro,
                'Reviso' => $reviso,
                'Firmar' => $firmar,
                'Ciudad' => $ciudad,
                'F_correspondecia' => $f_correspondencia,
                // 'N_radicado' => $radicado,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];
    
            sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_Evento],
                ['Id_Asignacion',$Id_Asignacion_adicion_dx]
            ])->update($datos_correspondencia);       

            $datos_info_comunicado_eventos = [
                'ID_Evento' => $Id_Evento,
                'Id_proceso' => $Id_Proceso_adicion_dx,
                'Id_Asignacion' => $Id_Asignacion_adicion_dx,
                'Ciudad' => $ciudad,
                'F_comunicado' => $date,
                // 'N_radicado' => $radicado,
                'Cliente' => 'N/A',
                'Nombre_afiliado' => $destinatario_principal,
                'T_documento' => 'N/A',
                'N_identificacion' => $nro_identificacion,
                'Destinatario' => $Destinatario,
                'Nombre_destinatario' => $request->nombre_destinatariopri ? $request->nombre_destinatariopri : 'N/A',
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
                'Tipo_descarga' => 'Comunicado',
                'Modulo_creacion' => 'adicionDxDtoOrigen',
                'Reemplazado' => 0,
                'Otro_destinatario' => $request->nombre_destinatariopri ? 1 : 0,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            
            // $datos_info_comunicado_eventos = [
            //     'Agregar_copia' => $agregar_copias_comu,
            //     'JRCI_copia' => $cual,
            //     'Nombre_usuario' => $nombre_usuario,
            //     'F_registro' => $date,
            //     'Reemplazado' => 0
            // ];   
                
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([ 
                ['ID_evento', $Id_Evento],
                ['Id_Asignacion',$Id_Asignacion_adicion_dx],
                ['Id_proceso', $Id_Proceso_adicion_dx],               
                ['N_radicado',$request->radicado]
            ])->update($datos_info_comunicado_eventos);

            //Capturamos el Id del comunicado para poder generarlo en el servidor
            $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_Evento],
                ['Id_Asignacion',$Id_Asignacion_adicion_dx],
                ['Id_proceso', $Id_Proceso_adicion_dx],
                ['N_radicado',$request->radicado]
                ])
            ->value('Id_Comunicado');

            $mensajes = array(
                "parametro" => 'actualizar_correspondencia',
                'Id_Comunicado' => $id_comunicado ? $id_comunicado : null,
                "mensaje" => 'Correspondencia actualizada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
        }
        

    }

    // Descargar proforma DML ORIGEN PREVISIONAL (DICTAMEN)
    public function ADescargaProformaDMLPrev(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user= Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $id_evento = $request->id_evento;
        $Id_Asignacion = $request->Id_Asignacion;
        $Id_Proceso = $request->Id_Proceso;
        $Id_comunicado = $request->id_comunicado;


        $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_Asignacion));
        $array_datos_info_laboral = $this->globalService->retornarInformaciónLaboral($id_evento);
        // Consultamos la información de la Adición Dx
        $info_adicion_dx = sigmel_informacion_adiciones_dx_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $id_evento)
        ->orderBy('Id_Asignacion', 'desc')
        ->limit(1)
        ->get();
        //Capturamos el tipo de evento
        if (count($info_adicion_dx) > 0) {
            $id_evento_guardado = $info_adicion_dx[0]->Tipo_evento;
            $array_nombre_del_evento_guardado = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Nombre_evento')
            ->where('Id_Evento', $id_evento_guardado)->get();
            $nombre_del_evento_guardado = $array_nombre_del_evento_guardado[0]->Nombre_evento;
        }else{
            $nombre_del_evento_guardado = "";
        }

        $datos_bd_DTO_ATEL = sigmel_informacion_dto_atel_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $id_evento)
            ->get();
        /* captura de variables del formulario */

        // $id_cliente = $request->id_cliente;
        $id_cliente = $array_datos_calificacion_origen[0]->Id_cliente;

        // $f_dictamen = date("d-m-Y", strtotime($request->f_dictamen));
        $f_dictamen = date("d-m-Y", strtotime($array_datos_calificacion_origen[0]->F_registro_asignacion));

        // $empresa_laboral = $request->empresa_laboral;
        $empresa_laboral = !empty($array_datos_info_laboral[0]->Empresa) ? $array_datos_info_laboral[0]->Empresa : null;
        
        // $nit_cc_laboral = $request->nit_cc_laboral;
        $nit_cc_laboral = !empty($array_datos_info_laboral[0]->Nit_o_cc) ? $array_datos_info_laboral[0]->Nit_o_cc : null;
        
        // $cargo_laboral = $request->cargo_laboral;
        $cargo_laboral = !empty($array_datos_info_laboral[0]->Cargo) ? $array_datos_info_laboral[0]->Cargo : null;

        // $antiguedad_cargo_laboral = $request->antiguedad_cargo_laboral;
        $antiguedad_cargo_laboral = !empty($array_datos_info_laboral[0]->Antiguedad_cargo_empresa) ? $array_datos_info_laboral[0]->Antiguedad_cargo_empresa : null;

        // $act_economica_laboral = $request->act_economica_laboral;
        $act_economica_laboral = (!empty($array_datos_info_laboral[0]->Id_actividad_economica) && !empty($array_datos_info_laboral[0]->Nombre_actividad)) ? $array_datos_info_laboral[0]->Id_actividad_economica." - ".$array_datos_info_laboral[0]->Nombre_actividad : '';

        // $justificacion_revision_origen = $request->justificacion_revision_origen;
        $justificacion_revision_origen = !empty($datos_bd_DTO_ATEL[0]->Justificacion_revision_origen) ? $datos_bd_DTO_ATEL[0]->Justificacion_revision_origen : null;

        // $nombre_evento = $request->nombre_evento;
        $nombre_evento = $nombre_del_evento_guardado;

        // $f_evento = date("d-m-Y", strtotime($request->f_evento));
        if (!empty($datos_bd_DTO_ATEL[0]->Fecha_evento)) {
            $f_evento = date("d-m-Y", strtotime($datos_bd_DTO_ATEL[0]->Fecha_evento));
        }else{
            $f_evento = "";
        }

        // if (!empty($request->f_fallecimiento)) {
        //     $f_fallecimiento = date("d-m-Y", strtotime($request->f_fallecimiento));
        // } else {
        //     $f_fallecimiento = "";
        // }
        if (!empty($datos_bd_DTO_ATEL[0]->Fecha_fallecimiento)) {
            $f_fallecimiento = date("d-m-Y", strtotime($datos_bd_DTO_ATEL[0]->Fecha_fallecimiento));
        } else {
            $f_fallecimiento = "";
        }

        $N_siniestro = $request->N_siniestro;

        // $sustentacion_califi_origen = $request->sustentacion_califi_origen;
        // $sustentacion_califi_origen = !empty($datos_bd_DTO_ATEL[0]->Sustentacion) ? $datos_bd_DTO_ATEL[0]->Sustentacion : null;
        $sustentacion_califi_origen = $request->sustentacion;
        
        $origen = $request->origen;
        //QR
        $formattedData = "";
        $dictamenOrigenQr = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_decreto_eventos as side', 'side.Id_Asignacion', '=', 'siae.Id_Asignacion')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_afiliado_eventos as siaf', 'siaf.ID_evento', '=', 'siae.ID_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siaf.Tipo_documento')
        ->select('siaf.Nombre_afiliado', 'slp.Nombre_parametro', 'siaf.Nro_identificacion', 'siae.Consecutivo_dictamen', 
        'side.Porcentaje_pcl', 'side.F_estructuracion', 'siae.ID_evento')
        ->where('siae.Id_Asignacion', $Id_Asignacion)->get();     

        if (!$dictamenOrigenQr->isEmpty()) {
            // Crear una cadena para almacenar los datos en el formato deseado                    
        
            foreach ($dictamenOrigenQr as $evento) {
                // Construir la cadena de texto con el formato deseado
                $formattedData .= $evento->Nombre_afiliado."\n";
                $formattedData .= $evento->Nombre_parametro." ".$evento->Nro_identificacion . "\n";
                $formattedData .= "N° Dictámen: ".$evento->Consecutivo_dictamen."\n";
                $formattedData .= "Cod. Verificación: ".$evento->ID_evento."\n";
                // Agregar un salto de línea después de cada conjunto de atributos de evento
                $formattedData .= "\n";
            }
                            
        }

        // Codigo QR
        $datosQr = $formattedData;
        $codigoQR = QrCode::size(110)->margin(0.5)->generate($datosQr); 

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
        )->where([['siae.ID_evento', $id_evento]])
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
            ['ID_evento',$id_evento],
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
        ->select('ID_evento', 'Id_proceso', 'Id_Asignacion', 'Visar', 'F_visado_comite')
        ->where([['Id_Asignacion',$Id_Asignacion], ['Visar','Si']])->get();
        /* Armado de datos para reemplazarlos en la plantilla */
        $datos_finales_dml_origen_previsional = [
            'codigoQR' => $codigoQR,
            'id_cliente' => $id_cliente,
            'logo_header' => $logo_header,
            'id_evento' => $id_evento,
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
            'validacion_visado' => $validacion_visado,
            'N_siniestro' => $N_siniestro
        ];

        /* Creación del pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/Origen_Atel/dml_origen_atel', $datos_finales_dml_origen_previsional);

        $indicativo = time();

        // $nombre_pdf = "ORI_DML_{$Id_Asignacion}_{$nro_ident_afiliado}.pdf";
        $nombre_pdf = "ORI_DML_{$Id_Asignacion}_{$nro_ident_afiliado}_{$indicativo}.pdf";

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
        //     ['siae.Id_Asignacion', $Id_Asignacion],
        //     ['siae.ID_evento', $id_evento],
        //     ['siae.Id_proceso', $Id_Proceso],
        // ])->get();

        // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        // $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        // ->select('sice.F_comunicado', 'sice.N_radicado')
        // ->where([
        //     ['sice.ID_evento', $id_evento],
        //     ['sice.Id_Asignacion', $Id_Asignacion],
        //     ['sice.Id_proceso', $Id_Proceso],
        //     ['sice.T_documento', 'N/A'],
        //     ['sice.Ciudad', 'N/A'],
        // ])
        // ->get();
        
        // $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;
        // $N_radicado_documento = $dato_f_elaboracion_correspondencia[0]->N_radicado;

        // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
        // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        // ->select('Nombre_documento')
        // ->where([
        //     ['Nombre_documento', $nombre_pdf],
        // ])->get();
        
        // if(count($verficar_documento) == 0){
        //     $info_descarga_documento = [
        //         'Id_Asignacion' => $Id_Asignacion,
        //         'Id_proceso' => $Id_Proceso,
        //         'Id_servicio' => $Id_servicio,
        //         'ID_evento' => $id_evento,
        //         'Nombre_documento' => $nombre_pdf,
        //         'N_radicado_documento' => $N_radicado_documento,
        //         'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
        //         'F_descarga_documento' => $date,
        //         'Nombre_usuario' => $nombre_usuario,
        //     ];
            
        //     sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        // }

        // return $pdf->download($nombre_pdf); 

        $datos = [
            'nombre_documento' => $nombre_pdf,
            'n_identificacion' => $nro_ident_afiliado,
            'indicativo' => $indicativo,
            'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
        ];
        
        return response()->json($datos);

    }

    // Descarga proforma Notificación del DML previsional (OFICIO REMISORIO)
    public function ADescargaProformaNotiDMLPrev(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        
        $user= Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $Id_Evento = $request->Id_Evento;
        $Id_asignacion = $request->Id_asignacion;
        $id_comunicado = $request->id_comunicado;
        $Id_Proceso = $request->Id_proceso;

        $array_comite_interdisciplinario = $this->globalService->retornarComiteInterdisciplinario($Id_Evento,$Id_asignacion);
        $array_datos_calificacion_origen = DB::select('CALL psrcalificacionOrigen(?)', array($Id_asignacion));
        $afp_afiliado = $this->globalService->retornarInformaciónEntidad($array_datos_calificacion_origen[0]->Id_afp);
        
        /* Captura de variables del formulario */

        // $id_com_inter = $request->id_com_inter;
        $id_com_inter = !empty($array_comite_interdisciplinario[0]->Id_com_inter) ? $array_comite_interdisciplinario[0]->Id_com_inter : null;

        // $ciudad = $request->ciudad;
        $ciudad = !empty($array_comite_interdisciplinario[0]->Ciudad) ? $array_comite_interdisciplinario[0]->Ciudad : "Bogotá D.C.";

        // $fecha =  fechaFormateada($request->fecha);
        $fecha =  fechaFormateada(!empty($array_comite_interdisciplinario[0]->F_correspondecia) ? $array_comite_interdisciplinario[0]->F_correspondecia : now()->format('Y-m-d'));

        // $asunto = strtoupper($request->asunto);
        $asunto = !empty($array_comite_interdisciplinario[0]->Asunto) ?  strtoupper($array_comite_interdisciplinario[0]->Asunto) : "Sin Asunto";
            
        $cuerpo = $request->cuerpo;

        // $tipo_identificacion = $request->tipo_identificacion;
        $tipo_identificacion = !empty($array_datos_calificacion_origen[0]->Nombre_tipo_documento) ? $array_datos_calificacion_origen[0]->Nombre_tipo_documento : null;

        // $num_identificacion = $request->num_identificacion;
        $num_identificacion = !empty($array_datos_calificacion_origen[0]->Nro_identificacion) ? $array_datos_calificacion_origen[0]->Nro_identificacion : null;

        // $nombre_afiliado = $request->nombre_afiliado;
        $nombre_afiliado = !empty($array_datos_calificacion_origen[0]->Nombre_afiliado) ? $array_datos_calificacion_origen[0]->Nombre_afiliado : null;
        
        // $direccion_afiliado = $request->direccion_afiliado;
        $direccion_afiliado = !empty($array_datos_calificacion_origen[0]->Direccion) ?  $array_datos_calificacion_origen[0]->Direccion : null;

        // $telefono_afiliado = $request->telefono_afiliado;
        $telefono_afiliado = !empty($array_datos_calificacion_origen[0]->Telefono_contacto) ? $array_datos_calificacion_origen[0]->Telefono_contacto : null;

        // $nombre_afp = $request->nombre_afp;
        $nombre_afp = !empty($afp_afiliado[0]->Nombre_entidad) ? $afp_afiliado[0]->Nombre_entidad : null;

        // $email_afp = $request->email_afp;
        $email_afp = !empty($afp_afiliado[0]->Email) ? $afp_afiliado[0]->Email : null;

        // $direccionAfp = $request->direccion_afp; 
        $direccionAfp = !empty($afp_afiliado[0]->Direccion) ? $afp_afiliado[0]->Direccion : null;

        // $telefono_afp = $request->telefono_afp;
        $telefono_afp = !empty($afp_afiliado[0]->Telefonos) ? $afp_afiliado[0]->Telefonos : null;

        $ciudad_afiliado = $request->ciudad_afiliado; //Este dato no se esta enviando

        // $ciudad_afp = $request->ciudad_afp;
        $ciudad_afp = !empty($afp_afiliado[0]->Nombre_ciudad) ? $afp_afiliado[0]->Nombre_ciudad : null;

        // $Id_cliente_firma = $request->Id_cliente_firma;
        $Id_cliente_firma = !empty($array_datos_calificacion_origen[0]->Id_cliente) ? $array_datos_calificacion_origen[0]->Id_cliente : null;

        $origen = $request->origen;
        $copia_beneficiario = $request->copia_beneficiario;
        $copia_empleador = $request->copia_empleador;
        $copia_eps = $request->copia_eps;
        $copia_afp = $request->copia_afp;
        $copia_afp_conocimiento = $request->copia_afp_conocimiento;
        $copia_arl = $request->copia_arl;
        $firmar = $request->firmar;
        $anexos = $request->anexos;
        $tipo_evento = $request->tipo_evento;
        $N_siniestro = $request->N_siniestro;
        /* Creación de las variables faltantes que no están en el formulario */
        $dato_nro_radicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->select('N_radicado')
        ->where([['Id_Comunicado', $id_comunicado]])
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
                    ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Emails', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad', 'sldm.Nombre_departamento')
                    ->where([
                        ['sie.Id_Entidad', $id_entidad],
                        ['sie.IdTipo_entidad', $tipo_destinatario]
                    ])->get();

                    $nombre_destinatario_principal = $datos_entidad[0]->Nombre_entidad;
                    $email_destinatario_principal = $datos_entidad[0]->Emails;
                    $direccion_destinatario_principal = $datos_entidad[0]->Direccion;
                    $telefono_destinatario_principal = $datos_entidad[0]->Telefonos;
                    $ciudad_destinatario_principal = $datos_entidad[0]->Nombre_ciudad.' ('.$datos_entidad[0]->Nombre_departamento.')';
                break;
                
                // Si escoge la opción Afiliado: Se sacan los datos del destinatario principal pero del afiliado
                case ($tipo_destinatario == 4):
                    $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
                    ->where([['siae.ID_evento','=', $Id_Evento]])
                    ->get();
        
                    $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);
        
                    $nombre_destinatario_principal = $nombre_afp;
                    $email_destinatario_principal = $email_afp;
                    $direccion_destinatario_principal = $direccionAfp;
                    $telefono_destinatario_principal = $telefono_afp;
                    $ciudad_destinatario_principal = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"].' ('.$array_datos_municipio_ciudad_afiliado[0]["Nombre_departamento"].')';
                break;

                // Si escoge la opción Empleador: Se sacan los datos del destinatario principal pero del Empleador
                case ($tipo_destinatario == 5):
                    $datos_entidad_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_municipio', '=', 'sldm.Id_municipios')
                    ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sile.Email','sldm.Nombre_municipio as Nombre_ciudad','sldm.Nombre_departamento')
                    ->where([['sile.ID_evento', $Id_Evento]])->get();

                    $nombre_destinatario_principal = $datos_entidad_empleador[0]->Empresa;
                    $email_destinatario_principal = $datos_entidad_empleador[0]->Email;
                    $direccion_destinatario_principal = $datos_entidad_empleador[0]->Direccion;
                    $telefono_destinatario_principal = $datos_entidad_empleador[0]->Telefono_empresa;
                    $ciudad_destinatario_principal = $datos_entidad_empleador[0]->Nombre_ciudad.' ('.$datos_entidad_empleador[0]->Nombre_departamento.')';
                break;
                
                // Si escoge la opción Otro: se sacan los datos del destinatario de la tabla sigmel_informacion_comite_interdisciplinario_eventos
                case ($tipo_destinatario == 8):
                    // aqui validamos si los datos no vienen vacios, debido a que si  vienen vacios, toca marcar ''
                    if (!empty($array_datos_para_destinatario_principal[0]["Nombre_destinatario"])) {
                        $nombre_destinatario_principal = $array_datos_para_destinatario_principal[0]["Nombre_destinatario"];
                    } else {
                        $nombre_destinatario_principal = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Email_destinatario"])) {
                        $email_destinatario_principal = $array_datos_para_destinatario_principal[0]["Email_destinatario"];
                    } else {
                        $email_destinatario_principal = "";
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
                        $ciudad_destinatario_principal = $array_datos_para_destinatario_principal[0]["Ciudad_destinatario"].' ('.$array_datos_para_destinatario_principal[0]["Departamento_destinatario"].')';
                    } else {
                        $ciudad_destinatario_principal = "";
                    };
                break;

                default:
                    # code...
                break;
            }
        } 
        // En caso de que no: la info del destinatario principal se saca de la AFP
        else {
            // $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            // ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
            // ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
            // ->select('sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
            // ->where([['siae.ID_evento','=', $Id_Evento]])
            // ->get();

            // $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);

            $nombre_destinatario_principal = $nombre_afp;
            $email_destinatario_principal = $email_afp;
            $direccion_destinatario_principal = $direccionAfp;
            $telefono_destinatario_principal = $telefono_afp;
            // $ciudad_destinatario_principal = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"];
            $ciudad_destinatario_principal = $ciudad_afp === 'Bogotá D.C.' ? $ciudad_afp.' ('.$ciudad_afp.')' : $ciudad_afp;
        }
        
        $ramo = "Previsionales";
        
        /* Copias Interesadas */
        // Validamos si los checkbox esta marcados
        $final_copia_beneficiario = isset($copia_beneficiario) ? 'Beneficiario' : '';
        $final_copia_empleador = isset($copia_empleador) ? 'Empleador' : '';
        $final_copia_eps = isset($copia_eps) ? 'EPS' : '';
        $final_copia_afp = isset($copia_afp) ? 'AFP' : '';
        $final_copia_afp_conocimiento = isset($copia_afp_conocimiento) ? 'AFP_Conocimiento' : '';
        $final_copia_arl = isset($copia_arl) ? 'ARL' : '';

        $total_copias = array_filter(array(
            'copia_beneficiario' => $final_copia_beneficiario,
            'copia_empleador' => $final_copia_empleador,
            'copia_eps' => $final_copia_eps,
            'copia_afp' => $final_copia_afp,
            'copia_afp_conocimiento' => $final_copia_afp_conocimiento,
            'copia_arl' => $final_copia_arl,
        )); 

        sleep(2);
        
        // Conversión de las key en variables con sus respectivos datos
        extract($total_copias);
        
        $Agregar_copias = [];
        if (isset($copia_beneficiario)) {
            // $Id_Evento 
            $datos_beneficiario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento_benefi', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio_benefi', '=', 'sldm2.Id_municipios')
            ->select('siae.Nombre_afiliado_benefi', 'siae.Direccion_benefi','siae.Email', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
            ->where([['siae.ID_evento', $Id_Evento ]])
            ->get();

            $nombre_beneficiario = $datos_beneficiario[0]->Nombre_afiliado_benefi;
            $direccion_beneficiario = $datos_beneficiario[0]->Direccion_benefi;
            $departamento_beneficiario = $datos_beneficiario[0]->Nombre_departamento;
            $ciudad_beneficiario = $datos_beneficiario[0]->Nombre_ciudad;
            $email_beneficiario = $datos_beneficiario[0]->Email;


            $Agregar_copias['Beneficiario'] = $nombre_beneficiario."; ".$direccion_beneficiario."; ".$email_beneficiario."; ".$departamento_beneficiario."; ".$ciudad_beneficiario.".";
        }

        if(isset($copia_empleador)){

            $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
            ->select('sile.Empresa', 'sile.Direccion','sile.Email', 'sile.Telefono_empresa', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['sile.Nro_identificacion', $num_identificacion],['sile.ID_evento', $Id_Evento]])
            ->get();

            $nombre_empleador = $datos_empleador[0]->Empresa;
            $direccion_empleador = $datos_empleador[0]->Direccion;
            $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
            $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
            $municipio_empleador = $datos_empleador[0]->Nombre_municipio;
            $email_empleador = $datos_empleador[0]->Email;

            $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$email_empleador."; ".$telefono_empleador."; ".$ciudad_empleador." - ".$municipio_empleador.".";   
        }

        if (isset($copia_eps)) {
            $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $Id_Evento]])
            ->get();

            $nombre_eps = $datos_eps[0]->Nombre_eps;
            $direccion_eps = $datos_eps[0]->Direccion;
            if ($datos_eps[0]->Otros_Telefonos != "") {
                $telefonos_eps = $datos_eps[0]->Telefonos.",".$datos_eps[0]->Otros_Telefonos;
            } else {
                $telefonos_eps = $datos_eps[0]->Telefonos;
            }
            $ciudad_eps = $datos_eps[0]->Nombre_ciudad;
            $email_eps = $datos_eps[0]->Email;
            $municipio_eps = $datos_eps[0]->Nombre_municipio;

            $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps."; ".$telefonos_eps."; ".$ciudad_eps." - ".$municipio_eps;
        }

        if (isset($copia_afp)) {
            $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Emails as Email','sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $Id_Evento]])
            ->get();

            $nombre_afp = $datos_afp[0]->Nombre_afp;
            $direccion_afp = $datos_afp[0]->Direccion;
            if ($datos_afp[0]->Otros_Telefonos != "") {
                $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
            } else {
                $telefonos_afp = $datos_afp[0]->Telefonos;
            }
            $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
            $email_afp = $datos_afp[0]->Email;
            $municipio_afp = $datos_afp[0]->Nombre_municipio;

            $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$email_afp."; ".$telefonos_afp."; ".$ciudad_afp." - ".$municipio_afp;
        }

        if (isset($copia_afp_conocimiento)) {
            $dato_id_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->select('siae.Entidad_conocimiento', 'siae.Id_afp_entidad_conocimiento')
            ->where([['siae.ID_evento', $Id_Evento]])
            ->get();

            if (count($dato_id_afp_conocimiento) > 0) {

                $si_entidad_conocimiento = $dato_id_afp_conocimiento[0]->Entidad_conocimiento;

                if ($si_entidad_conocimiento == "Si") {
                    $id_afp_conocimiento = $dato_id_afp_conocimiento[0]->Id_afp_entidad_conocimiento;
    
                    $datos_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Emails as Email','sie.Telefonos', 'sie.Otros_Telefonos', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                    ->where([['sie.Id_Entidad', $id_afp_conocimiento]])
                    ->get();
    
                    $nombre_afp_conocimiento = $datos_afp_conocimiento[0]->Nombre_entidad;
                    $direccion_afp_conocimiento = $datos_afp_conocimiento[0]->Direccion;
                    if ($datos_afp_conocimiento[0]->Otros_Telefonos != "") {
                        $telefonos_afp_conocimiento = $datos_afp_conocimiento[0]->Telefonos.",".$datos_afp_conocimiento[0]->Otros_Telefonos;
                    } else {
                        $telefonos_afp_conocimiento = $datos_afp_conocimiento[0]->Telefonos;
                    }
                    $email_afp_conocimiento = $datos_afp_conocimiento[0]->Email;
                    $ciudad_afp_conocimiento = $datos_afp_conocimiento[0]->Nombre_ciudad;
                    $municipio_afp_conocimiento = $datos_afp_conocimiento[0]->Nombre_municipio;
    
                    $Agregar_copias['AFP_Conocimiento'] = $nombre_afp_conocimiento."; ".$direccion_afp_conocimiento."; ".$email_afp_conocimiento."; ".$telefonos_afp_conocimiento."; ".$ciudad_afp_conocimiento." - ".$municipio_afp_conocimiento;
                } else {
                    // $Agregar_copias['AFP_Conocimiento'] = '';
                }
                
            } else {
                // $Agregar_copias['AFP_Conocimiento'] = '';
            }
            
        }

        if(isset($copia_arl)){
            $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Emails as Email', 'sie.Otros_Telefonos',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $Id_Evento]])
            ->get();

            $nombre_arl = $datos_arl[0]->Nombre_arl;
            $direccion_arl = $datos_arl[0]->Direccion;
            if ($datos_arl[0]->Otros_Telefonos != "") {
                $telefonos_arl = $datos_arl[0]->Telefonos.",".$datos_arl[0]->Otros_Telefonos;
            } else {
                $telefonos_arl = $datos_arl[0]->Telefonos;
            }
            $email_arl = $datos_arl[0]->Email;
            $ciudad_arl = $datos_arl[0]->Nombre_ciudad;
            $municipio_arl = $datos_arl[0]->Nombre_municipio;

            $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$email_arl."; ".$telefonos_arl."; ".$ciudad_arl." - ".$municipio_arl;
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

        //Footer image
        $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
        ->select('Footer_cliente')
        ->where([['Id_cliente', $Id_cliente_firma]])
        ->limit(1)->get();

        if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
            $footer = $footer_imagen[0]->Footer_cliente;
        } else {
            $footer = null;
        } 

        /* Extraemos los datos del footer */
        // $datos_footer = sigmel_clientes::on('sigmel_gestiones')
        // ->select('footer_dato_1', 'footer_dato_2', 'footer_dato_3', 'footer_dato_4', 'footer_dato_5')
        // ->where('Id_cliente',  $Id_cliente_firma)->get();

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
            'Id_Evento' => $Id_Evento,
            'nombre_afiliado' => $nombre_afiliado,
            'origen' => $origen,
            'nro_radicado' => $nro_radicado,
            'anexos' => $anexos,
            'nombre_destinatario_principal' => $nombre_destinatario_principal,
            'email_destinatario_principal' => $email_destinatario_principal,
            'direccion_destinatario_principal' => $direccion_destinatario_principal,
            'telefono_destinatario_principal' => $telefono_destinatario_principal,
            'ciudad_destinatario_principal' => $ciudad_destinatario_principal,
            'ramo' => $ramo,
            'Agregar_copia' => $Agregar_copias,
            'Firma_cliente' => $Firma_cliente,
            'nombre_usuario' => $nombre_usuario,
            'footer' => $footer,
            'N_siniestro' => $N_siniestro,
            'tipo_evento' => $tipo_evento,
            // 'footer_dato_1' => $footer_dato_1,
            // 'footer_dato_2' => $footer_dato_2,
            // 'footer_dato_3' => $footer_dato_3,
            // 'footer_dato_4' => $footer_dato_4,
            // 'footer_dato_5' => $footer_dato_5,
        ];
        /* Creación del pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/Origen_Atel/notificacion_dml_origen', $datos_finales_noti_dml_origen);
        
        $indicativo = time();

        // $nombre_pdf = "ORI_OFICIO_{$Id_asignacion}_{$num_identificacion}.pdf";
        $nombre_pdf = "ORI_OFICIO_{$Id_asignacion}_{$num_identificacion}_{$indicativo}.pdf";

        //Obtener el contenido del PDF
        $output = $pdf->output();
        //Guardar el PDF en un archivo
        file_put_contents(public_path("Documentos_Eventos/{$Id_Evento}/{$nombre_pdf}"), $output);


        $actualizar_nombre_documento = [
            'Nombre_documento' => $nombre_pdf
        ];

        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $id_comunicado)
        ->update($actualizar_nombre_documento);
        /* Inserción del registro de que fue descargado */
        // Extraemos el id del proceso y servicio asociado
        // $dato_id_servicio_proceso = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        // ->select('siae.Id_proceso','siae.Id_servicio')
        // ->where([
        //     ['siae.Id_Asignacion', $Id_asignacion],
        //     ['siae.ID_evento', $Id_Evento],
        // ])->get();

        // $Id_Proceso = $dato_id_servicio_proceso[0]->Id_proceso;
        // $Id_servicio = $dato_id_servicio_proceso[0]->Id_servicio;

        // // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        // $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        // ->select('sice.F_comunicado', 'sice.N_radicado')
        // ->where([
        //     ['sice.ID_evento', $Id_Evento],
        //     ['sice.Id_Asignacion', $Id_asignacion],
        //     ['sice.Id_proceso', $Id_Proceso],
        //     ['sice.T_documento', 'N/A'],
        //     ['sice.Ciudad', '!=', 'N/A'],
        // ])
        // ->get();

        // $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;
        // $N_radicado_documento = $dato_f_elaboracion_correspondencia[0]->N_radicado;

        // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
        // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        // ->select('Nombre_documento')
        // ->where([
        //     ['Nombre_documento', $nombre_pdf],
        // ])->get();
        
        // if(count($verficar_documento) == 0){
        //     $info_descarga_documento = [
        //         'Id_Asignacion' => $Id_asignacion,
        //         'Id_proceso' => $Id_Proceso,
        //         'Id_servicio' => $Id_servicio,
        //         'ID_evento' => $Id_Evento,
        //         'Nombre_documento' => $nombre_pdf,
        //         'N_radicado_documento' => $N_radicado_documento,
        //         'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
        //         'F_descarga_documento' => $date,
        //         'Nombre_usuario' => $nombre_usuario,
        //     ];
            
        //     sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        // }

        
        // return $pdf->download($nombre_pdf); 

        $datos = [
            'nombre_documento' => $nombre_pdf,
            'n_identificacion' => $num_identificacion,
            'indicativo' => $indicativo,
            'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
        ];
        
        return response()->json($datos);

    }

}
