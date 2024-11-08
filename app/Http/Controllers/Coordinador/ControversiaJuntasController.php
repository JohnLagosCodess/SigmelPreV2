<?php

namespace App\Http\Controllers\Coordinador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_lista_califi_decretos;
use App\Models\sigmel_calendarios;
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_controversia_juntas_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_lista_regional_juntas;
use App\Models\sigmel_lista_solicitantes;
use App\Models\sigmel_clientes;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_registro_descarga_documentos;
use App\Models\sigmel_informacion_correspondencia_eventos;
use App\Models\sigmel_registro_documentos_eventos;
use App\Services\GlobalService;
use App\Traits\GenerarRadicados;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Image;

class ControversiaJuntasController extends Controller
{
    use GenerarRadicados;
    protected $globalService;

    public function __construct(GlobalService $globalService)
    {
        $this->globalService = $globalService;
    }
    public function mostrarVistaPronunciamientoJuntas(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $date=date("Y-m-d");
        $Id_evento_juntas=$request->Id_evento_juntas;
        $Id_asignacion_juntas = $request->Id_asignacion_juntas;
        $array_datos_controversiaJuntas = DB::select('CALL psrcalificacionJuntas(?)', array($Id_asignacion_juntas));
        $Id_servicio = $request->Id_Servicio;

        // Trae informacion de controversia_juntas
        $arrayinfo_controvertido= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_controversia_juntas_eventos as j')
        ->select('j.Id_Asignacion_Servicio_Anterior','j.ID_evento','j.Enfermedad_heredada','j.F_transferencia_enfermedad','j.Primer_calificador','pa.Nombre_parametro as Calificador'
        ,'j.Nom_entidad','j.N_dictamen_controvertido','j.N_siniestro','j.F_notifi_afiliado','j.Parte_controvierte_califi','pa2.Nombre_parametro as ParteCalificador','j.Nombre_controvierte_califi',
        'j.N_radicado_entrada_contro','j.Contro_origen','j.Contro_pcl','j.Contro_diagnostico','j.Contro_f_estructura','j.Contro_m_califi',
        'j.F_contro_primer_califi','j.F_contro_radi_califi','j.Termino_contro_califi','j.Jrci_califi_invalidez','sie.Nombre_entidad as JrciNombre',
        'j.Origen_controversia','pa4.Nombre_parametro as OrigenContro','j.Manual_de_califi','d.Nombre_decreto','j.Total_deficiencia','j.Total_rol_ocupacional','j.Total_discapacidad',
        'j.Total_minusvalia','j.Porcentaje_pcl','j.Rango_pcl','j.F_estructuracion_contro','j.N_pago_jnci_contro','j.F_pago_jnci_contro','j.F_radica_pago_jnci_contro','j.F_envio_jrci','j.N_dictamen_jrci_emitido'
        ,'j.F_dictamen_jrci_emitido','j.Origen_jrci_emitido','pa5.Nombre_parametro as OrigenEmitidoJrci','j.Manual_de_califi_jrci_emitido','d1.Nombre_decreto as Nombre_decretoJrci','j.Total_deficiencia_jrci_emitido',
        'j.Total_rol_ocupacional_jrci_emitido','j.Total_discapacidad_jrci_emitido','j.Total_minusvalia_jrci_emitido','j.Porcentaje_pcl_jrci_emitido','j.Rango_pcl_jrci_emitido',
        'j.F_estructuracion_contro_jrci_emitido','j.Resumen_dictamen_jrci','j.F_noti_dictamen_jrci','j.F_radica_dictamen_jrci','j.F_maxima_recurso_jrci','j.Decision_dictamen_jrci',
        'j.Sustentacion_concepto_jrci','j.F_sustenta_jrci','j.F_notificacion_recurso_jrci','j.N_radicado_recurso_jrci','j.Termino_contro_propia_jrci','j.Causal_decision_jrci',
        'j.Firmeza_intere_contro_jrci','j.Firmeza_reposicion_jrci','j.Firmeza_acta_ejecutoria_jrci','j.Firmeza_apelacion_jnci_jrci','j.Parte_contro_ante_jrci','pa7.Nombre_parametro as NomPresentaJrci',
        'j.Nombre_presen_contro_jrci','j.F_contro_otra_jrci','j.Contro_origen_jrci','j.Contro_pcl_jrci','j.Contro_diagnostico_jrci','j.Contro_f_estructura_jrci','j.Contro_m_califi_jrci','j.Reposicion_dictamen_jrci',
        'j.N_dictamen_reposicion_jrci','j.F_dictamen_reposicion_jrci','j.Origen_reposicion_jrci','pa8.Nombre_parametro as Nombre_origenRepoJrci','j.Manual_reposicion_jrci','d2.Nombre_decreto as Nombre_decretoRepoJrci',
        'j.Total_deficiencia_reposicion_jrci','j.Total_reposicion_jrci','j.Total_discapacidad_reposicion_jrci','j.Total_minusvalia_reposicion_jrci','j.Porcentaje_pcl_reposicion_jrci','j.Rango_pcl_reposicion_jrci'
        ,'j.F_estructuracion_contro_reposicion_jrci','j.Resumen_dictamen_reposicion_jrci','j.F_noti_dictamen_reposicion_jrci','j.F_radica_dictamen_reposicion_jrci','j.F_maxima_apelacion_jrci','j.Decision_dictamen_repo_jrci'
        ,'j.Decision_dictamen_repo_jrci','j.Causal_decision_repo_jrci','j.Sustentacion_concepto_repo_jrci','j.F_sustenta_reposicion_jrci','j.F_noti_apela_recurso_jrci'
        ,'j.N_radicado_apela_recurso_jrci','j.T_propia_apela_recurso_jrci','j.Correspon_pago_jnci','j.N_orden_pago_jnci','j.F_orden_pago_jnci','j.F_radi_pago_jnci','j.N_acta_ejecutario_emitida_jrci'
        ,'j.F_acta_ejecutoria_emitida_jrci','j.F_firmeza_dictamen_jrci','j.Dictamen_firme_jrci','j.N_dictamen_jnci_emitido','j.F_dictamen_jnci_emitido','j.Origen_jnci_emitido','pa10.Nombre_parametro as NombreOrigen'
        ,'j.Manual_de_califi_jnci_emitido','d3.Nombre_decreto as Nombre_decretoJnci','j.Total_deficiencia_jnci_emitido','j.Total_rol_ocupacional_jnci_emitido','j.Total_discapacidad_jnci_emitido'
        ,'j.Total_minusvalia_jnci_emitido','j.Porcentaje_pcl_jnci_emitido','j.Rango_pcl_jnci_emitido','j.F_estructuracion_contro_jnci_emitido','j.Resumen_dictamen_jnci','j.Sustentacion_dictamen_jnci'
        ,'j.F_sustenta_ante_jnci','j.F_noti_ante_jnci','j.F_radica_dictamen_jnci','j.F_envio_jnci')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa', 'j.Primer_calificador', '=', 'pa.Id_Parametro','j.')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa2', 'j.Parte_controvierte_califi', '=', 'pa2.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'j.Jrci_califi_invalidez', '=', 'sie.Id_Entidad')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa4', 'j.Origen_controversia', '=', 'pa4.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa5', 'j.Origen_jrci_emitido', '=', 'pa5.Id_Parametro')
        //->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa6', 'j.Causal_decision_jrci', '=', 'pa6.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa7', 'j.Parte_contro_ante_jrci', '=', 'pa7.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa8', 'j.Origen_reposicion_jrci', '=', 'pa8.Id_Parametro')
        //->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa9', 'j.Causal_decision_repo_jrci', '=', 'pa9.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa10', 'j.Origen_jnci_emitido', '=', 'pa10.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d3', 'j.Manual_de_califi_jnci_emitido', '=', 'd3.Id_Decreto')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d', 'j.Manual_de_califi', '=', 'd.Id_Decreto')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d1', 'j.Manual_de_califi_jrci_emitido', '=', 'd1.Id_Decreto')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d2', 'j.Manual_reposicion_jrci', '=', 'd2.Id_Decreto')
        ->where([['j.ID_evento',  '=', $Id_evento_juntas],['j.Id_Asignacion', $Id_asignacion_juntas]])
        ->get();
        //dd($Id_evento_juntas,$Id_asignacion_juntas);
        // TRAER DATOS CIE10 (Diagnóstico motivo de calificación)
        $array_datos_diagnostico_motcalifi_contro =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
        ->where([['side.ID_evento',$Id_evento_juntas],
            ['side.Id_Asignacion',$Id_asignacion_juntas],
            ['side.Id_proceso',$array_datos_controversiaJuntas[0]->Id_proceso],
            ['side.Item_servicio', '=', 'Controvertido Juntas'],
            ['side.Estado', '=', 'Activo']
        ])->get(); 

        // TRAER DATOS CIE10 (Diagnóstico motivo de calificación emitido Jrci)
        $array_datos_diagnostico_motcalifi_emitido_jrci=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
        ->where([['side.ID_evento',$Id_evento_juntas],
            ['side.Id_Asignacion',$Id_asignacion_juntas],
            ['side.Id_proceso',$array_datos_controversiaJuntas[0]->Id_proceso],
            ['side.Item_servicio', '=', 'Emitido JRCI'],
            ['side.Estado', '=', 'Activo']
        ])->get(); 
        // TRAER DATOS CIE10 (Reposición del Dictamen por parte de la JRCI)
        $array_datos_diagnostico_reposi_dictamen_jrci=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
        ->where([['side.ID_evento',$Id_evento_juntas],
            ['side.Id_Asignacion',$Id_asignacion_juntas],
            ['side.Id_proceso',$array_datos_controversiaJuntas[0]->Id_proceso],
            ['side.Item_servicio', '=', 'Reposicion JRCI'],
            ['side.Estado', '=', 'Activo']
        ])->get(); 

        // TRAER DATOS CIE10 (emitido por la Junta Nacional de Calificación de Invalidez (JNCI))
        $array_datos_diagnostico_motcalifi_emitido_jnci=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
        ->where([['side.ID_evento',$Id_evento_juntas],
            ['side.Id_Asignacion',$Id_asignacion_juntas],
            ['side.Id_proceso',$array_datos_controversiaJuntas[0]->Id_proceso],
            ['side.Item_servicio', '=', 'Emitido JNCI'],
            ['side.Estado', '=', 'Activo']
        ])->get(); 

        //Trae Documetos Generales del evento
        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?,?,?)',array($Id_evento_juntas, $Id_servicio, $Id_asignacion_juntas));

        // cantidad de documentos cargados

        $cantidad_documentos_cargados = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $Id_evento_juntas],
            ['Id_servicio', $Id_servicio]
        ])->get();
        
        $array_comite_interdisciplinario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comite_interdisciplinario_eventos as sicie')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sicie.Nombre_dest_principal')
        ->select('sicie.Id_com_inter', 'sicie.ID_evento', 'sicie.Id_proceso', 'sicie.Id_Asignacion', 'sicie.Visar', 'sicie.Profesional_comite', 'sicie.F_visado_comite',
        'sicie.Destinatario_principal', 'sicie.Otro_destinatario', 'sicie.Tipo_destinatario', 'sicie.Nombre_dest_principal', 'sie.Nombre_entidad',
        'sicie.Nombre_destinatario','sicie.Nit_cc', 'sicie.Direccion_destinatario', 'sicie.Telefono_destinatario', 'sicie.Email_destinatario',
        'sicie.Departamento_destinatario', 'sicie.Ciudad_destinatario', 'sicie.Asunto', 'sicie.Cuerpo_comunicado', 'sicie.Copia_empleador',
        'sicie.Copia_eps', 'sicie.Copia_afp', 'sicie.Copia_arl', 'sicie.Copia_jr', 'sicie.Cual_jr', 'sicie.Copia_jn', 'sicie.Anexos',
        'sicie.Elaboro', 'sicie.Reviso', 'sicie.Firmar', 'sicie.Ciudad', 'sicie.F_correspondecia', 'sicie.N_radicado', 'sicie.Nombre_usuario',
        'sicie.F_registro','sicie.Decision_dictamen')        
        ->where([
            ['ID_evento',$Id_evento_juntas],
            ['Id_Asignacion',$Id_asignacion_juntas]
        ])
        ->get(); 

       
        // creación de consecutivo para el comunicado
        $consecutivo = $this->getRadicado('juntas',$Id_evento_juntas);

        // traemos los comunicados
        $array_comunicados_correspondencia = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$Id_evento_juntas], ['Id_Asignacion',$Id_asignacion_juntas], ['T_documento','N/A'],['Modulo_creacion','controversiaJuntas']])->get();
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
                $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_juntas,$Id_asignacion_juntas,$comunicado["Id_Comunicado"]);
            }
            
        }
        //Obtenemos las secciones a mostrar
        $array_control = $this->controlJuntas($Id_evento_juntas, $Id_asignacion_juntas,  $array_datos_controversiaJuntas[0]->Nombre_servicio);
        // Extraemos el id de asignacion con el que fue creado la controversia
        // En caso de que tenga el dato se procede a analizar si el servicio que tiene asociado ese id asignacion es una calitec, recalificación, revision pension
        // Si tiene, mandamos una bandera a la vista para inhabilitar el selector manual calificación de la sección Diagnósticos del Dictamen Controvertido
        if(!empty($arrayinfo_controvertido[0]->Id_Asignacion_Servicio_Anterior)){
            
            $id_asignacion_servicio_anterior = $arrayinfo_controvertido[0]->Id_Asignacion_Servicio_Anterior;

            $info_servicio = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('Id_servicio')
            ->where([['Id_Asignacion', $id_asignacion_servicio_anterior]])->get();

            if(!empty($info_servicio[0]->Id_servicio)){
                if($info_servicio[0]->Id_servicio == 6 || $info_servicio[0]->Id_servicio == 7 || $info_servicio[0]->Id_servicio == 8){
                    $bandera_manual_calificacion = 'desactivar';
                }else{
                    $bandera_manual_calificacion = 'activar';
                }
            }else{
                $bandera_manual_calificacion = 'activar';
            }
            
        }else{
            $bandera_manual_calificacion = 'activar';
        }

        // Consultamos si el caso está en la bandeja de Notificaciones
        $array_caso_notificado = BandejaNotifiController::evento_en_notificaciones($Id_evento_juntas,$Id_asignacion_juntas);
        if(count($array_caso_notificado) > 0){
            $caso_notificado = $array_caso_notificado[0]->Notificacion;
        }

        //Traer el N_siniestro del evento
        $N_siniestro_evento = $this->globalService->retornarNumeroSiniestro($Id_evento_juntas);     
        //dd($arrayinfo_controvertido);
        return view('coordinador.controversiaJuntas', compact('user','array_datos_controversiaJuntas','arrayinfo_controvertido',
        'array_datos_diagnostico_motcalifi_contro','array_datos_diagnostico_motcalifi_emitido_jrci',
        'array_datos_diagnostico_reposi_dictamen_jrci',
        'array_datos_diagnostico_motcalifi_emitido_jnci','arraylistado_documentos', 
        'array_comite_interdisciplinario', 'consecutivo', 'array_comunicados_correspondencia', 'Id_servicio','array_control', 'bandera_manual_calificacion', 'caso_notificado','N_siniestro_evento',
        'cantidad_documentos_cargados','arraylistado_documentos'));
    
    }

        /**
     * Funcion para determinar las secciones visibles basadas en el tipo de controversia
     * y sus permisos, comparando la información de la controversia con los permisos predefinidos.
     * @param $id_evento int identificador del evento.
     * @param $id_asignacion int Id de asignacion del evento.
     * @param $Servicio string El servicio asociado al evento.
     * @return Array con las secciones disponibles para mostrar
     */
    public function controlJuntas($id_evento, $id_asignacion,$Servicio) {

        // Definimos las secciones que podran ser visibles dependiento el tipo de controversia.
        $permisos = [
            "Diagnosticos_dictamen" => [],
            "Dictamen_emitido_JRCI" => ['% PCL'],
            "Dictamen_emitido_JNCI" => ['% PCL'],
            'Servicios' => ['Controversia PCL'] //Aqui agregamos los servicios que podran ver todas los formularios, de momento en la vista solo esta seteada para PCL
        ];
    
        // Obtenemos la información de controversia_juntas
        $controvertido =(array) DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos')
            ->select('Contro_origen', 'Contro_pcl', 'Contro_diagnostico', 'Contro_f_estructura', 'Contro_m_califi')
            ->where('ID_evento', $id_evento)
            ->where('Id_Asignacion', $id_asignacion)
            ->first();
    
        // Combinamos los resultados
        array_push($controvertido, $Servicio);
    
        //Comparamos la info del controvertido vs los @permisos y en caso de encontrar alguna coincidencia dejara en true la seccion a mostrar
        $resultado = [];
        foreach ($permisos as $key => $value) {
            $permisoCumplido = false;
            foreach ($value as $elemento) {
                if (in_array($elemento, $controvertido)) {
                    $permisoCumplido = true;
                    break;
                }
            }
            $resultado[$key] = $permisoCumplido;
        }
        //dd($resultado);
        return $resultado;
    }

    //Cargar Selectores pronunciamiento
    public function cargueListadoSelectoresJuntasControversia(Request $request){
        $parametro = $request->parametro;
         //Lista tipo origen controvertido
         if($parametro == "lista_tipo_origen_controver"){
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
        //Lista tipo decreto califi
        if($parametro == "lista_tipo_califi_decretos"){
            $datos_tipo_decreto = sigmel_lista_califi_decretos::on('sigmel_gestiones')
                ->select('Id_Decreto','Nombre_decreto')
                ->whereIn('Id_Decreto', [1, 3])
                ->where([
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_decreto = json_decode(json_encode($datos_tipo_decreto, true));
            return response()->json($informacion_datos_tipo_decreto);
        }
        //Lista tipo origen Emitod JRCI
        if($parametro == "lista_tipo_origen_emitdo_jrci"){
            $datos_tipo_origen_emitido = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Origen Cie10'],
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_origen_emitido = json_decode(json_encode($datos_tipo_origen_emitido, true));
            return response()->json($informacion_datos_tipo_origen_emitido);
        }
        //Lista tipo decreto califi
        if($parametro == "lista_tipo_califi_decretos_jrci_emitido"){
            $datos_tipo_decreto_jrci = sigmel_lista_califi_decretos::on('sigmel_gestiones')
                ->select('Id_Decreto','Nombre_decreto')
                ->whereIn('Id_Decreto', [1, 3])
                ->where([
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_decreto_jrci= json_decode(json_encode($datos_tipo_decreto_jrci, true));
            return response()->json($informacion_datos_tipo_decreto_jrci);
        }
        // Lista tipo de causal
        if($parametro == "lista_causales_jrci"){
            if($request->causal=='Acuerdo'){
                $datos_tipo_causal_jrci = sigmel_lista_parametros::on('sigmel_gestiones')
                    ->select('Id_Parametro','Nombre_parametro')
                    ->where([
                        ['Tipo_lista', '=', 'Causales Acuerdo Jrci'],
                        ['Estado', '=', 'activo'],
                    ])
                    ->get();
            }else{
                $datos_tipo_causal_jrci = sigmel_lista_parametros::on('sigmel_gestiones')
                    ->select('Id_Parametro','Nombre_parametro')
                    ->where([
                        ['Tipo_lista', '=', 'Causales Desacuerdo Jrci'],
                        ['Estado', '=', 'activo'],
                    ])
                    ->get();
            }
            $informacion_ddatos_tipo_causal_jrci= json_decode(json_encode($datos_tipo_causal_jrci, true));
            return response()->json($informacion_ddatos_tipo_causal_jrci);
        }
        // Listado parte que controvierte
        if($parametro == 'lista_controvierte_calificacion'){
            $listado_contro_califi = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Juntas Controversia'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_contro_califi = json_decode(json_encode($listado_contro_califi, true));
            return response()->json($info_listado_contro_califi);
        }
        //Lista tipo origen reposicion JRCI
        if($parametro == "lista_tipo_reposicion_jrci"){
            $datos_tipo_origen_repo = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Origen Cie10'],
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_origen_repo = json_decode(json_encode($datos_tipo_origen_repo, true));
            return response()->json($informacion_datos_tipo_origen_repo);
        }

        //Lista tipo decreto califi reposicion
        if($parametro == "lista_tipo_califi_decretos_jrci_reposicion"){
            $datos_tipo_decreto_jrci_re = sigmel_lista_califi_decretos::on('sigmel_gestiones')
                ->select('Id_Decreto','Nombre_decreto')
                ->whereIn('Id_Decreto', [1, 3])
                ->where([
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_decreto_jrci_re= json_decode(json_encode($datos_tipo_decreto_jrci_re, true));
            return response()->json($informacion_datos_tipo_decreto_jrci_re);
        }

         //Lista tipo origen Emitod JNCI
         if($parametro == "lista_tipo_origen_emitdo_jnci"){
            $datos_tipo_origen_emitido_jnci = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Origen Cie10'],
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_origen_emitido_jnci = json_decode(json_encode($datos_tipo_origen_emitido_jnci, true));
            return response()->json($informacion_datos_tipo_origen_emitido_jnci);
        }

        //Lista tipo decreto califi emitido JNCI
        if($parametro == "lista_tipo_califi_decretos_jnci_reposicion"){
            $datos_tipo_decreto_jnci_re = sigmel_lista_califi_decretos::on('sigmel_gestiones')
                ->select('Id_Decreto','Nombre_decreto')
                ->whereIn('Id_Decreto', [1, 3])
                ->where([
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_tipo_decreto_jnci_re= json_decode(json_encode($datos_tipo_decreto_jnci_re, true));
            return response()->json($informacion_datos_tipo_decreto_jnci_re);
        }

        //Lista tipo destinatario
        if($parametro == "lista_tipo_destinatario"){
            $datos_lista_tipo_destinatario = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Tipo Destinatario'],
                    ['Estado', '=', 'activo'],
                ])
                ->get();

            $informacion_datos_lista_tipo_destinatario = json_decode(json_encode($datos_lista_tipo_destinatario, true));
            return response()->json($informacion_datos_lista_tipo_destinatario);
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

    //Guarda informacion de controvertido Juntas Modulo
    public function guardarControvertidoMoJuntas(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        // Guarda Registro CIE10 contro vertido
        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
         $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
         ->max('Id_Diagnosticos_motcali');
         if ($max_id <> "") {
             DB::connection('sigmel_gestiones')
             ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
         }

        if (!empty($request->Motivo_calificacion_controvertido)) {
            if (count($request->Motivo_calificacion_controvertido) > 0) {
                // Captura del array de los datos de la tabla
                $array_diagnosticos_motivo_calificacion_contro = $request->Motivo_calificacion_controvertido;
                $array_datos_organizados_motivo_calificacion_contro = [];
                foreach ($array_diagnosticos_motivo_calificacion_contro as $subarray_datos_motivo_calificacion_contro) {
    
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->Id_proceso);
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->newId_asignacion);
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->newId_evento);
    
                    $subarray_datos_motivo_calificacion_contro[] = $nombre_usuario;
                    $subarray_datos_motivo_calificacion_contro[] = $date;
                    $subarray_datos_motivo_calificacion_contro[] = 'Controvertido Juntas';
    
                    array_push($array_datos_organizados_motivo_calificacion_contro, $subarray_datos_motivo_calificacion_contro);
                }
    
                // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
                $array_tabla_diagnosticos_motivo_calificacion_contro = ['ID_evento','Id_Asignacion','Id_proceso',
                'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
                'Principal', 'Nombre_usuario','F_registro','Item_servicio'];
                // Combinación de los campos de la tabla con los datos
                $array_datos_con_keys_motivo_calificacion_contro = [];
                foreach ($array_datos_organizados_motivo_calificacion_contro as $subarray_datos_organizados_motivo_calificacion_contro) {
                    array_push($array_datos_con_keys_motivo_calificacion_contro, array_combine($array_tabla_diagnosticos_motivo_calificacion_contro, $subarray_datos_organizados_motivo_calificacion_contro));
                }
    
                // Inserción de la información
                foreach ($array_datos_con_keys_motivo_calificacion_contro as $insertar_diagnostico_contro) {
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico_contro);
                }
            }
        }
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE
        if (count($info_controverisa_juntas) > 0) {
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'N_siniestro' => $request->n_siniestro,
                'Origen_controversia' => $request->origen_controversia,
                'Manual_de_califi' => $request->manual_de_califi,
                'Total_deficiencia' => $request->total_deficiencia,
                'Total_rol_ocupacional' => $request->total_rol_ocupacional,
                'Total_discapacidad' => $request->total_discapacidad,
                'Total_minusvalia' => $request->total_minusvalia,
                'Porcentaje_pcl' => $request->porcentaje_pcl,
                'Rango_pcl' => $request->rango_pcl,
                'F_estructuracion_contro' => $request->f_estructuracion_contro,
                'N_pago_jnci_contro' => $request->n_pago_jnci_contro,
                'F_pago_jnci_contro' => $request->f_pago_jnci_contro,
                'F_radica_pago_jnci_contro' => $request->f_radica_pago_jnci_contro,
                'F_envio_jrci' => $request->f_envio_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
            $mensajes = array(
                "parametro" => 'registro_controvertido_juntas',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );
            
        } else {
            //Captura los datos a insertar en controversia
            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'N_siniestro' => $request->n_siniestro,
                'Origen_controversia' => $request->origen_controversia,
                'Manual_de_califi' => $request->manual_de_califi,
                'Total_deficiencia' => $request->total_deficiencia,
                'Total_rol_ocupacional' => $request->total_rol_ocupacional,
                'Total_discapacidad' => $request->total_discapacidad,
                'Total_minusvalia' => $request->total_minusvalia,
                'Porcentaje_pcl' => $request->porcentaje_pcl,
                'Rango_pcl' => $request->rango_pcl,
                'F_estructuracion_contro' => $request->f_estructuracion_contro,
                'N_pago_jnci_contro' => $request->n_pago_jnci_contro,
                'F_pago_jnci_contro' => $request->f_pago_jnci_contro,
                'F_radica_pago_jnci_contro' => $request->f_radica_pago_jnci_contro,
                'F_envio_jrci' => $request->f_envio_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
            $mensajes = array(
                "parametro" => 'registro_controvertido_juntas',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            );
        }
    
        return json_decode(json_encode($mensajes, true));
    }

    //Guarda informacion de emitido Jrci
    public function guardarEmitidoMoJrci(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        // Guarda Registro CIE10 contro vertido
        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
         $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
         ->max('Id_Diagnosticos_motcali');
         if ($max_id <> "") {
             DB::connection('sigmel_gestiones')
             ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
         }

        if (!empty($request->Motivo_calificacion_emitido)) {
            if (count($request->Motivo_calificacion_emitido) > 0) {
                // Captura del array de los datos de la tabla
                $array_diagnosticos_motivo_calificacion_contro = $request->Motivo_calificacion_emitido;
                $array_datos_organizados_motivo_calificacion_contro = [];
                foreach ($array_diagnosticos_motivo_calificacion_contro as $subarray_datos_motivo_calificacion_contro) {
    
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->Id_proceso);
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->newId_asignacion);
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->newId_evento);
    
                    $subarray_datos_motivo_calificacion_contro[] = $nombre_usuario;
                    $subarray_datos_motivo_calificacion_contro[] = $date;
                    $subarray_datos_motivo_calificacion_contro[] = 'Emitido JRCI';
    
                    array_push($array_datos_organizados_motivo_calificacion_contro, $subarray_datos_motivo_calificacion_contro);
                }
    
                // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
                $array_tabla_diagnosticos_motivo_calificacion_contro = ['ID_evento','Id_Asignacion','Id_proceso',
                'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
                'Principal', 'Nombre_usuario','F_registro','Item_servicio'];
                // Combinación de los campos de la tabla con los datos
                $array_datos_con_keys_motivo_calificacion_contro = [];
                foreach ($array_datos_organizados_motivo_calificacion_contro as $subarray_datos_organizados_motivo_calificacion_contro) {
                    array_push($array_datos_con_keys_motivo_calificacion_contro, array_combine($array_tabla_diagnosticos_motivo_calificacion_contro, $subarray_datos_organizados_motivo_calificacion_contro));
                }
    
                // Inserción de la información
                foreach ($array_datos_con_keys_motivo_calificacion_contro as $insertar_diagnostico_contro) {
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico_contro);
                }
            }
        }
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE
        if (count($info_controverisa_juntas) > 0) {

            $f_maxima_r_jrci = $this->calcularFechaMaximaJRCI($request->f_radica_dictamen_jrci,$request->newId_asignacion,$request->newId_evento);

            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'N_dictamen_jrci_emitido' => $request->n_dictamen_jrci_emitido,
                'F_dictamen_jrci_emitido' => $request->f_dictamen_jrci_emitido,
                'Origen_jrci_emitido' => $request->origen_jrci_emitido,
                'Manual_de_califi_jrci_emitido' => $request->manual_de_califi_jrci_emitido,
                'Total_deficiencia_jrci_emitido' => $request->total_deficiencia_jrci_emitido,
                'Total_rol_ocupacional_jrci_emitido' => $request->total_rol_ocupacional_jrci_emitido,
                'Total_discapacidad_jrci_emitido' => $request->total_discapacidad_jrci_emitido,
                'Total_minusvalia_jrci_emitido' => $request->total_minusvalia_jrci_emitido,
                'Porcentaje_pcl_jrci_emitido' => $request->porcentaje_pcl_jrci_emitido,
                'Rango_pcl_jrci_emitido' => $request->rango_pcl_jrci_emitido,
                'F_estructuracion_contro_jrci_emitido' => $request->f_estructuracion_contro_jrci_emitido,
                'Resumen_dictamen_jrci' => $request->resumen_dictamen_jrci,
                'F_radica_dictamen_jrci' => $request->f_radica_dictamen_jrci,  
                'F_noti_dictamen_jrci' => $request->f_noti_dictamen_jrci,
                'F_maxima_recurso_jrci' => $f_maxima_r_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_emitido_jrci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );            
        } else {
            //Captura los datos a insertar en controversia
            $f_maxima_r_jrci = $this->calcularFechaMaximaJRCI($request->f_radica_dictamen_jrci,$request->newId_asignacion,$request->newId_evento);

            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'N_dictamen_jrci_emitido' => $request->n_dictamen_jrci_emitido,
                'F_dictamen_jrci_emitido' => $request->f_dictamen_jrci_emitido,
                'Origen_jrci_emitido' => $request->origen_jrci_emitido,
                'Manual_de_califi_jrci_emitido' => $request->manual_de_califi_jrci_emitido,
                'Total_deficiencia_jrci_emitido' => $request->total_deficiencia_jrci_emitido,
                'Total_rol_ocupacional_jrci_emitido' => $request->total_rol_ocupacional_jrci_emitido,
                'Total_discapacidad_jrci_emitido' => $request->total_discapacidad_jrci_emitido,
                'Total_minusvalia_jrci_emitido' => $request->total_minusvalia_jrci_emitido,
                'Porcentaje_pcl_jrci_emitido' => $request->porcentaje_pcl_jrci_emitido,
                'Rango_pcl_jrci_emitido' => $request->rango_pcl_jrci_emitido,
                'F_estructuracion_contro_jrci_emitido' => $request->f_estructuracion_contro_jrci_emitido,
                'Resumen_dictamen_jrci' => $request->resumen_dictamen_jrci,
                'F_radica_dictamen_jrci' => $request->f_radica_dictamen_jrci,  
                'F_noti_dictamen_jrci' => $request->f_noti_dictamen_jrci,
                'F_maxima_recurso_jrci' => $f_maxima_r_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_emitido_jrci',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            ); 
        }
    
        return json_decode(json_encode($mensajes, true));
    }

    /**
     * PBS 043 Se solicita calcular la fecha maxima para recurso ante JRCI segun la claficiacion de LunesAViernes o LunesASabado
     * @param F_dictamen_jrci Fecha de radicado del dictamen JRCI
     * @param id_asignacion id de asignacion el evento.
     * @param id_evento id del evento que se esta procesando.
     * @return date FechaMaxima para recurso ante JRCI teniendo en cuenta los dias habiles.
     */
    public function calcularFechaMaximaJRCI($F_dictamen_jrci,$id_asignacion,$id_evento){
        $fechaMaxima = null;

        /** @var clasificacion Regiones sobre las cuales se estara operando en el grupo u otro */
        $clasificacion = [
            'LunesAViernes' => ['ATLÁNTICO','BOGOTÁ','CUNDINAMARCA','BOLÍVAR','BOYACÁ','HUILA','MAGDALENA','SANTANDER','VALLE DEL CAUCA','TOLIMA','CESAR'],
            'LunesASabado' => ['ANTIOQUIA','META','CALDAS','NARIÑO','NORTE DE SANTANDER','RISARALDA','QUINDÍO','CAUCA','CASANARE']
        ];

        if(!empty($F_dictamen_jrci)){

            //Junta Regional de Calificación de Invalidez
            $entidad= sigmel_informacion_entidades::on('sigmel_gestiones')->select('Nombre_entidad')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_controversia_juntas_eventos as JE','JE.Jrci_califi_invalidez','Id_Entidad')
            ->where([
                ['ID_evento',$id_evento],
                ['Id_Asignacion',$id_asignacion],
                ['IdTipo_entidad',4] //Tipo JRCI
                ])->first();

            if($entidad){
                foreach ($clasificacion as $grupo => $regiones) {
                    foreach ($regiones as $region) {
                        if ($grupo == 'LunesAViernes' && stripos($entidad->Nombre_entidad, $region) !== false) {
                            $fechaMaxima = calcularDiasHabiles($F_dictamen_jrci);
                            break 2;
                        }elseif ($grupo == 'LunesASabado' && stripos($entidad->Nombre_entidad, $region) !== false) {
                            $fechaMaxima = calcularDiasHabiles($F_dictamen_jrci,$grupo);
                            break 2;
                        }
                    }
                }
            }
        }
        return $fechaMaxima;
    }

    //Guarda informacion revision Jrci
    public function guardarRevisionMoJrci(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        $bandera_porfesional_pronunciamiento = $request->bandera_porfesional_pronunciamiento;
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();

        //Si el evento existe hace el IF y si no hace ELSE

        if ($bandera_porfesional_pronunciamiento == 'Actualizar') {
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'Decision_dictamen_jrci' => $request->decision_dictamen_jrci,
                'Causal_decision_jrci' => $request->causal_decision,
                'Sustentacion_concepto_jrci' => $request->sustentacion_concepto_jrci,
                'F_sustenta_jrci' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_revision_jrci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );            
        } else {

            if (count($info_controverisa_juntas) > 0) {
                //Captura los datos a actualizar en controversia
                $datos_info_controvertido_juntas= [
                    'Decision_dictamen_jrci' => $request->decision_dictamen_jrci,
                    'Causal_decision_jrci' => $request->causal_decision,
                    'Sustentacion_concepto_jrci' => $request->sustentacion_concepto_jrci,
                    'F_sustenta_jrci' => $date,
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
                
                sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
                ->update($datos_info_controvertido_juntas);

                sleep(2);
            
                // Actualizacion del profesional calificador
                $datos_profesional_calificador = [
                    'Id_calificador' => Auth::user()->id,
                    'Nombre_calificador' => $nombre_usuario
                ];

                sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $newIdAsignacion)->update($datos_profesional_calificador);
        
        
                $mensajes = array(
                    "parametro" => 'registro_revision_jrci',
                    "mensaje" => 'Registro actualizado satisfactoriamente.'
                ); 
            } else {                
                //Captura los datos a insertar en controversia
                $datos_info_controvertido_juntas= [
                    'ID_evento' => $request->newId_evento,
                    'Id_Asignacion' => $request->newId_asignacion,
                    'Id_proceso' => $request->Id_proceso,
                    'Decision_dictamen_jrci' => $request->decision_dictamen_jrci,
                    'Causal_decision_jrci' => $request->causal_decision,
                    'Sustentacion_concepto_jrci' => $request->sustentacion_concepto_jrci,
                    'F_sustenta_jrci' => $date,
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
                   
                sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
                ->insert($datos_info_controvertido_juntas);
    
                sleep(2);
                
                // Actualizacion del profesional calificador
                $datos_profesional_calificador = [
                    'Id_calificador' => Auth::user()->id,
                    'Nombre_calificador' => $nombre_usuario
                ];
    
                sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $newIdAsignacion)->update($datos_profesional_calificador);
        
                $mensajes = array(
                    "parametro" => 'registro_revision_jrci',
                    "mensaje" => 'Registro guardado satisfactoriamente.'
                );
            }
            
        }
    
        return json_decode(json_encode($mensajes, true));
    }

    //Guarda informacion recursos Jrci
    public function guardarRecursoMoJrci(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        if($request->f_notificacion_recurso_jrci < $request->f_maxima_recurso_jrci){
            $TerminoRecurso='Dentro de términos';
        }else{
            $TerminoRecurso='Fuera de términos';
        }
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE

        if (count($info_controverisa_juntas) > 0) {
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'F_notificacion_recurso_jrci' => $request->f_notificacion_recurso_jrci,
                'N_radicado_recurso_jrci' => $request->n_radicado_recurso_jrci,
                'Termino_contro_propia_jrci' => $TerminoRecurso,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_recurso_jrci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );            
        } else {
            //Captura los datos a insertar en controversia
            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'F_notificacion_recurso_jrci' => $request->f_notificacion_recurso_jrci,
                'N_radicado_recurso_jrci' => $request->n_radicado_recurso_jrci,
                'Termino_contro_propia_jrci' => $TerminoRecurso,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_recurso_jrci',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            );   
        }
    
        return json_decode(json_encode($mensajes, true));
    }

    //Guardar informacion partes interesadas
    public function guardarParteMoJrci(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;    
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE

        if (count($info_controverisa_juntas) > 0) {
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'Firmeza_intere_contro_jrci' => $request->firmeza_intere_contro_jrci,
                'Firmeza_reposicion_jrci' => $request->firmeza_reposicion_jrci,
                'Firmeza_acta_ejecutoria_jrci' => $request->firmeza_acta_ejecutoria_jrci,
                'Firmeza_apelacion_jnci_jrci' => $request->firmeza_apelacion_jnci_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_parte_jrci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );            
        } else {
            //Captura los datos a insertar en controversia
            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Firmeza_intere_contro_jrci' => $request->firmeza_intere_contro_jrci,
                'Firmeza_reposicion_jrci' => $request->firmeza_reposicion_jrci,
                'Firmeza_acta_ejecutoria_jrci' => $request->firmeza_acta_ejecutoria_jrci,
                'Firmeza_apelacion_jnci_jrci' => $request->firmeza_apelacion_jnci_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_parte_jrci',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            );  
        }
    
        return json_decode(json_encode($mensajes, true));
    } 

    //Guardar informacion partes interesadas controversia Jrci
    public function guardarParteControMoJrci(Request $request){
        
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;    
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE

        if (count($info_controverisa_juntas) > 0) {
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'Parte_contro_ante_jrci' => $request->parte_contro_ante_jrci,
                'Nombre_presen_contro_jrci' => $request->nombre_presen_contro_jrci,
                'F_contro_otra_jrci' => $request->f_contro_otra_jrci,
                'Contro_origen_jrci' => $request->contro_origen_jrci,
                'Contro_pcl_jrci' => $request->contro_pcl_jrci,
                'Contro_diagnostico_jrci' => $request->contro_diagnostico_jrci,
                'Contro_f_estructura_jrci' => $request->contro_f_estructura_jrci,
                'Contro_m_califi_jrci' => $request->contro_m_califi_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_parte_contro_jrci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );            
        } else {
            //Captura los datos a insertar en controversia
            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Parte_contro_ante_jrci' => $request->parte_contro_ante_jrci,
                'Nombre_presen_contro_jrci' => $request->nombre_presen_contro_jrci,
                'F_contro_otra_jrci' => $request->f_contro_otra_jrci,
                'Contro_origen_jrci' => $request->contro_origen_jrci,
                'Contro_pcl_jrci' => $request->contro_pcl_jrci,
                'Contro_diagnostico_jrci' => $request->contro_diagnostico_jrci,
                'Contro_f_estructura_jrci' => $request->contro_f_estructura_jrci,
                'Contro_m_califi_jrci' => $request->contro_m_califi_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_parte_contro_jrci',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            ); 
        } 

        return json_decode(json_encode($mensajes, true));
    } 

    //Guardar informacion datos reposicion Jrci
    public function guardarDatosRepoMoJrci(Request $request){
        
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;  
          
        // Guarda Registro CIE10 contro vertido
        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
        $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->max('Id_Diagnosticos_motcali');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
        }

       if (!empty($request->Motivo_calificacion_repo)) {
           if (count($request->Motivo_calificacion_repo) > 0) {
               // Captura del array de los datos de la tabla
               $array_diagnosticos_motivo_calificacion_contro = $request->Motivo_calificacion_repo;
               $array_datos_organizados_motivo_calificacion_contro = [];
               foreach ($array_diagnosticos_motivo_calificacion_contro as $subarray_datos_motivo_calificacion_contro) {
   
                   array_unshift($subarray_datos_motivo_calificacion_contro, $request->Id_proceso);
                   array_unshift($subarray_datos_motivo_calificacion_contro, $request->newId_asignacion);
                   array_unshift($subarray_datos_motivo_calificacion_contro, $request->newId_evento);
   
                   $subarray_datos_motivo_calificacion_contro[] = $nombre_usuario;
                   $subarray_datos_motivo_calificacion_contro[] = $date;
                   $subarray_datos_motivo_calificacion_contro[] = 'Reposicion JRCI';
   
                   array_push($array_datos_organizados_motivo_calificacion_contro, $subarray_datos_motivo_calificacion_contro);
               }
   
               // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
               $array_tabla_diagnosticos_motivo_calificacion_contro = ['ID_evento','Id_Asignacion','Id_proceso',
               'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
               'Principal', 'Nombre_usuario','F_registro','Item_servicio'];
               // Combinación de los campos de la tabla con los datos
               $array_datos_con_keys_motivo_calificacion_contro = [];
               foreach ($array_datos_organizados_motivo_calificacion_contro as $subarray_datos_organizados_motivo_calificacion_contro) {
                   array_push($array_datos_con_keys_motivo_calificacion_contro, array_combine($array_tabla_diagnosticos_motivo_calificacion_contro, $subarray_datos_organizados_motivo_calificacion_contro));
               }
   
               // Inserción de la información
               foreach ($array_datos_con_keys_motivo_calificacion_contro as $insertar_diagnostico_contro) {
                   sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico_contro);
               }
           }
       }
        
       // Validar si existe el evento
       $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
       ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
       //Si el evento existe hace el IF y si no hace ELSE

       if (count($info_controverisa_juntas) > 0) {
           //Captura los datos a actualizar en controversia
           $datos_info_controvertido_juntas= [
               'Reposicion_dictamen_jrci' => $request->reposicion_dictamen_jrci,
               'N_dictamen_reposicion_jrci' => $request->n_dictamen_reposicion_jrci,
               'F_dictamen_reposicion_jrci' => $request->f_dictamen_reposicion_jrci,
               'Origen_reposicion_jrci' => $request->origen_reposicion_jrci,
               'Manual_reposicion_jrci' => $request->manual_reposicion_jrci,
               'Total_deficiencia_reposicion_jrci' => $request->total_deficiencia_reposicion_jrci,
               'Total_discapacidad_reposicion_jrci' => $request->total_discapacidad_reposicion_jrci,
               'Total_minusvalia_reposicion_jrci' => $request->total_minusvalia_reposicion_jrci,
               'porcentaje_pcl_reposicion_jrci' => $request->porcentaje_pcl_reposicion_jrci,
               'f_estructuracion_contro_reposicion_jrci' => $request->f_estructuracion_contro_reposicion_jrci,
               'resumen_dictamen_reposicion_jrci' => $request->resumen_dictamen_reposicion_jrci,
               'f_noti_dictamen_reposicion_jrci' => $request->f_noti_dictamen_reposicion_jrci,
               'f_radica_dictamen_reposicion_jrci' => $request->f_radica_dictamen_reposicion_jrci,
               'Nombre_usuario' => $nombre_usuario,
               'F_registro' => $date,
           ];
           
           sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
           ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
           ->update($datos_info_controvertido_juntas);
   
           $mensajes = array(
               "parametro" => 'registro_datos_repo_jrci',
               "mensaje" => 'Registro actualizado satisfactoriamente.'
           );
           
       } else {
           //Captura los datos a insertar en controversia
           $datos_info_controvertido_juntas= [
               'ID_evento' => $request->newId_evento,
               'Id_Asignacion' => $request->newId_asignacion,
               'Id_proceso' => $request->Id_proceso,
               'Reposicion_dictamen_jrci' => $request->reposicion_dictamen_jrci,
               'N_dictamen_reposicion_jrci' => $request->n_dictamen_reposicion_jrci,
               'F_dictamen_reposicion_jrci' => $request->f_dictamen_reposicion_jrci,
               'Origen_reposicion_jrci' => $request->origen_reposicion_jrci,
               'Manual_reposicion_jrci' => $request->manual_reposicion_jrci,
               'Total_deficiencia_reposicion_jrci' => $request->total_deficiencia_reposicion_jrci,
               'Total_discapacidad_reposicion_jrci' => $request->total_discapacidad_reposicion_jrci,
               'Total_minusvalia_reposicion_jrci' => $request->total_minusvalia_reposicion_jrci,
               'porcentaje_pcl_reposicion_jrci' => $request->porcentaje_pcl_reposicion_jrci,
               'f_estructuracion_contro_reposicion_jrci' => $request->f_estructuracion_contro_reposicion_jrci,
               'resumen_dictamen_reposicion_jrci' => $request->resumen_dictamen_reposicion_jrci,
               'f_noti_dictamen_reposicion_jrci' => $request->f_noti_dictamen_reposicion_jrci,
               'f_radica_dictamen_reposicion_jrci' => $request->f_radica_dictamen_reposicion_jrci,
               'Nombre_usuario' => $nombre_usuario,
               'F_registro' => $date,
           ];
           
           sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
           ->insert($datos_info_controvertido_juntas);
   
           $mensajes = array(
               "parametro" => 'registro_datos_repo_jrci',
               "mensaje" => 'Registro guardado satisfactoriamente.'
           );
       }

        return json_decode(json_encode($mensajes, true));
    } 
    
    //Guarda Revisión ante recurso de reposición de la Junta Regional
    public function guardarRegiRepoMoJrci(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE

        if (count($info_controverisa_juntas) > 0) {
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'Decision_dictamen_repo_jrci' => $request->decision_dictamen_repo_jrci,
                'Causal_decision_repo_jrci' => $request->causal_decision_repo,
                'Sustentacion_concepto_repo_jrci' => $request->sustentacion_concepto_repo_jrci,
                'F_sustenta_reposicion_jrci' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_reposicion_jrci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );            
        } else {
            //Captura los datos a insertar en controversia
            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Decision_dictamen_repo_jrci' => $request->decision_dictamen_repo_jrci,
                'Causal_decision_repo_jrci' => $request->causal_decision_repo,
                'Sustentacion_concepto_repo_jrci' => $request->sustentacion_concepto_repo_jrci,
                'F_sustenta_reposicion_jrci' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_reposicion_jrci',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            );
        }
    
        return json_decode(json_encode($mensajes, true));
    }

    //Guarda Apelación de recurso ante la JNCI
    public function guardarRegiApelaMoJrci(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        if($request->f_noti_apela_recurso_jrci < $request->f_maxima_apelacion_jrci){
            $TerminoRecurso='Dentro de términos';
        }else{
            $TerminoRecurso='Fuera de términos';
        }
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE

        if (count($info_controverisa_juntas) > 0) {
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'F_noti_apela_recurso_jrci' => $request->f_noti_apela_recurso_jrci,
                'N_radicado_apela_recurso_jrci' => $request->n_radicado_apela_recurso_jrci,
                'T_propia_apela_recurso_jrci' => $TerminoRecurso,
                'Correspon_pago_jnci' => $request->correspon_pago_jnci,
                'N_orden_pago_jnci' => $request->n_orden_pago_jnci,
                'F_orden_pago_jnci' => $request->f_orden_pago_jnci,
                'F_radi_pago_jnci' => $request->f_radi_pago_jnci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_apela_jrci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );            
        } else {
            //Captura los datos a insertar en controversia
            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'F_noti_apela_recurso_jrci' => $request->f_noti_apela_recurso_jrci,
                'N_radicado_apela_recurso_jrci' => $request->n_radicado_apela_recurso_jrci,
                'T_propia_apela_recurso_jrci' => $TerminoRecurso,
                'Correspon_pago_jnci' => $request->correspon_pago_jnci,
                'N_orden_pago_jnci' => $request->n_orden_pago_jnci,
                'F_orden_pago_jnci' => $request->f_orden_pago_jnci,
                'F_radi_pago_jnci' => $request->f_radi_pago_jnci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_apela_jrci',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            );
            
        }
    
        return json_decode(json_encode($mensajes, true));
    }

    //Guarda Acta Ejecutoria emitida por JRCI
    public function guardarRegiActaMoJrci(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        if($request->f_firmeza_dictamen_jrci<>''){
            $Dictamen_firme_jrci='Dictamen en firme';
        }else{
            $Dictamen_firme_jrci='';
        }
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE

        if (count($info_controverisa_juntas) > 0) {            
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'N_acta_ejecutario_emitida_jrci' => $request->n_acta_ejecutario_emitida_jrci,
                'F_acta_ejecutoria_emitida_jrci' => $request->f_acta_ejecutoria_emitida_jrci,
                'F_firmeza_dictamen_jrci' => $request->f_firmeza_dictamen_jrci,
                'Dictamen_firme_jrci' => $Dictamen_firme_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_acta_jrci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );
        } else {
            //Captura los datos a insertar en controversia
            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'N_acta_ejecutario_emitida_jrci' => $request->n_acta_ejecutario_emitida_jrci,
                'F_acta_ejecutoria_emitida_jrci' => $request->f_acta_ejecutoria_emitida_jrci,
                'F_firmeza_dictamen_jrci' => $request->f_firmeza_dictamen_jrci,
                'Dictamen_firme_jrci' => $Dictamen_firme_jrci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_acta_jrci',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            );
        }
    
        return json_decode(json_encode($mensajes, true));
    }

    //Guarda informacion de emitido Jrci
    public function guardarEmitidoMoJnci(Request $request){
    
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        // Guarda Registro CIE10 contro vertido
        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
         $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
         ->max('Id_Diagnosticos_motcali');
         if ($max_id <> "") {
             DB::connection('sigmel_gestiones')
             ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
         }

        if (!empty($request->Motivo_calificacion_emitido)) {
            if (count($request->Motivo_calificacion_emitido) > 0) {
                // Captura del array de los datos de la tabla
                $array_diagnosticos_motivo_calificacion_contro = $request->Motivo_calificacion_emitido;
                $array_datos_organizados_motivo_calificacion_contro = [];
                foreach ($array_diagnosticos_motivo_calificacion_contro as $subarray_datos_motivo_calificacion_contro) {
    
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->Id_proceso);
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->newId_asignacion);
                    array_unshift($subarray_datos_motivo_calificacion_contro, $request->newId_evento);
    
                    $subarray_datos_motivo_calificacion_contro[] = $nombre_usuario;
                    $subarray_datos_motivo_calificacion_contro[] = $date;
                    $subarray_datos_motivo_calificacion_contro[] = 'Emitido JNCI';
    
                    array_push($array_datos_organizados_motivo_calificacion_contro, $subarray_datos_motivo_calificacion_contro);
                }
    
                // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
                $array_tabla_diagnosticos_motivo_calificacion_contro = ['ID_evento','Id_Asignacion','Id_proceso',
                'CIE10','Nombre_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Lateralidad_CIE10', 'Origen_CIE10', 
                'Principal', 'Nombre_usuario','F_registro','Item_servicio'];
                // Combinación de los campos de la tabla con los datos
                $array_datos_con_keys_motivo_calificacion_contro = [];
                foreach ($array_datos_organizados_motivo_calificacion_contro as $subarray_datos_organizados_motivo_calificacion_contro) {
                    array_push($array_datos_con_keys_motivo_calificacion_contro, array_combine($array_tabla_diagnosticos_motivo_calificacion_contro, $subarray_datos_organizados_motivo_calificacion_contro));
                }
    
                // Inserción de la información
                foreach ($array_datos_con_keys_motivo_calificacion_contro as $insertar_diagnostico_contro) {
                    sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico_contro);
                }
            }
        }
        
        // Validar si existe el evento
        $info_controverisa_juntas = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])->get();
        //Si el evento existe hace el IF y si no hace ELSE

        if (count($info_controverisa_juntas) > 0) {
            //Captura los datos a actualizar en controversia
            $datos_info_controvertido_juntas= [
                'N_dictamen_jnci_emitido' => $request->n_dictamen_jnci_emitido,
                'F_dictamen_jnci_emitido' => $request->f_dictamen_jnci_emitido,
                'Origen_jnci_emitido' => $request->origen_jnci_emitido,
                'Manual_de_califi_jnci_emitido' => $request->manual_de_califi_jnci_emitido,
                'Total_deficiencia_jnci_emitido' => $request->total_deficiencia_jnci_emitido,
                'Total_rol_ocupacional_jnci_emitido' => $request->total_rol_ocupacional_jnci_emitido,
                'Total_discapacidad_jnci_emitido' => $request->total_discapacidad_jnci_emitido,
                'Total_minusvalia_jnci_emitido' => $request->total_minusvalia_jnci_emitido,
                'Porcentaje_pcl_jnci_emitido' => $request->porcentaje_pcl_jnci_emitido,
                'Rango_pcl_jnci_emitido' => $request->rango_pcl_jnci_emitido,
                'F_estructuracion_contro_jnci_emitido' => $request->f_estructuracion_contro_jnci_emitido,
                'Resumen_dictamen_jnci' => $request->resumen_dictamen_jnci,
                'Sustentacion_dictamen_jnci' => $request->sustentacion_dictamen_jnci,
                'F_radica_dictamen_jnci' => $request->f_radica_dictamen_jnci,
                'F_envio_jnci' => $request->f_envio_jnci,
                'F_noti_ante_jnci' => $request->f_noti_ante_jnci,  
                'F_sustenta_ante_jnci' => $request->f_sustenta_ante_jnci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento],['Id_Asignacion',$newIdAsignacion]])
            ->update($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_emitido_jnci',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );            
        } else {
            //Captura los datos a insertar en controversia
            $datos_info_controvertido_juntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'N_dictamen_jnci_emitido' => $request->n_dictamen_jnci_emitido,
                'F_dictamen_jnci_emitido' => $request->f_dictamen_jnci_emitido,
                'Origen_jnci_emitido' => $request->origen_jnci_emitido,
                'Manual_de_califi_jnci_emitido' => $request->manual_de_califi_jnci_emitido,
                'Total_deficiencia_jnci_emitido' => $request->total_deficiencia_jnci_emitido,
                'Total_rol_ocupacional_jnci_emitido' => $request->total_rol_ocupacional_jnci_emitido,
                'Total_discapacidad_jnci_emitido' => $request->total_discapacidad_jnci_emitido,
                'Total_minusvalia_jnci_emitido' => $request->total_minusvalia_jnci_emitido,
                'Porcentaje_pcl_jnci_emitido' => $request->porcentaje_pcl_jnci_emitido,
                'Rango_pcl_jnci_emitido' => $request->rango_pcl_jnci_emitido,
                'F_estructuracion_contro_jnci_emitido' => $request->f_estructuracion_contro_jnci_emitido,
                'Resumen_dictamen_jnci' => $request->resumen_dictamen_jnci,
                'Sustentacion_dictamen_jnci' => $request->sustentacion_dictamen_jnci,
                'F_radica_dictamen_jnci' => $request->f_radica_dictamen_jnci,  
                'F_envio_jnci' => $request->f_envio_jnci,
                'F_noti_ante_jnci' => $request->f_noti_ante_jnci,  
                'F_sustenta_ante_jnci' => $request->f_sustenta_ante_jnci,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
               
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->insert($datos_info_controvertido_juntas);
    
            $mensajes = array(
                "parametro" => 'registro_emitido_jnci',
                "mensaje" => 'Registro guardado satisfactoriamente.'
            );
        }
    
        return json_decode(json_encode($mensajes, true));
    }

    //Eliminar Diagnosticos
    public function eliminarDiagnosticoMotivoCalificacionContro(Request $request){
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
            ['Id_proceso', $request->Id_proceso],
            ['Item_servicio','Controvertido Juntas']
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

    // Comite Interdisciplinario

    // public function guardarcomiteinterdisciplinarioJuntas(Request $request){
    //     if (!Auth::check()) {
    //         return redirect('/');
    //     }
    //     $time = time();
    //     $nombre_usuario = Auth::user()->name;
    //     $date = date("Y-m-d", $time);
    //     $newId_evento = $request->newId_evento;
    //     $Id_proceso = $request->Id_proceso;
    //     $newId_asignacion = $request->newId_asignacion;
    //     $visar = $request->visar;
    //     $profesional_comite = $request->profesional_comite;
    //     $f_visado_comite = $request->f_visado_comite;

    //     $datos_comiteInterdisciplinario = [
    //         'ID_evento' => $newId_evento,
    //         'Id_proceso' => $Id_proceso,
    //         'Id_Asignacion' => $newId_asignacion,
    //         'Visar' => $visar,
    //         'Profesional_comite' => $profesional_comite,
    //         'F_visado_comite' => $f_visado_comite,
    //         'Nombre_usuario' => $nombre_usuario,
    //         'F_registro' => $date
    //     ];
    //     sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')->insert($datos_comiteInterdisciplinario);            
    //     $mensajes = array(
    //         "parametro" => 'insertar_comite_interdisciplinario',
    //         "mensaje" => 'Comite Interdisciplinario guardado satisfactoriamente.'
    //     );    
    //     return json_decode(json_encode($mensajes, true));
    // }

    // Correspondencia

    public function guardarcorrespondenciaJuntas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }

        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);
        $newId_evento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        $newId_asignacion = $request->newId_asignacion;
        $destinatario_principal = $request->destinatario_principal;
        $otrodestinariop = $request->otrodestinariop;
        $tipo_destinatario_principal = $request->tipo_destinatario_principal;
        $nombre_destinatariopri = $request->nombre_destinatariopri;
        $Nombre_dest_principal_afi_empl = $request->Nombre_dest_principal_afi_empl;
        $nombreAfiliado = $request->nombre_afiliado;
        
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
        $afiliado = $request->afiliado;
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
        // $agregar_copias_comu = $afiliado.','.$empleador.','.$eps.','.$afp.','.$arl.','.$jrci.','.$jnci;

        $variables_llenas = array();

        if (!empty($empleador)) {
            $variables_llenas[] = $empleador;
        }
        if (!empty($eps)) {
            $variables_llenas[] = $eps;
        }
        if (!empty($afp)) {
            $variables_llenas[] = $afp;
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

        $agregar_copias_comu = implode(',', $variables_llenas);
        
        $anexos = $request->anexos;
        $elaboro = $request->elaboro;
        $reviso = $request->reviso;
        $firmar = $request->firmar;
        $ciudad = $request->ciudad;
        // $f_correspondencia = $request->f_correspondencia;
        $f_correspondencia = $date;
        $radicado = $this->disponible($request->radicado,$newId_evento)
                        ->getRadicado('juntas',$newId_evento);
        $bandera_correspondecia_guardar_actualizar = $request->bandera_correspondecia_guardar_actualizar;

        // eL número de identificacion siempre será el del afiliado.
        $array_nro_ident_afi = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('Nro_identificacion')
        ->where([['ID_evento', $newId_evento]])
        ->get();

        if (count($array_nro_ident_afi) > 0) {
            $nro_identificacion = $array_nro_ident_afi[0]->Nro_identificacion;
        }else{
            $nro_identificacion = 'N/A';
        }
        // el destinatario siempre será Afiliado debido a que en ARL el otro destinatario no funciona
        if ($otrodestinariop == '') {
            $Destinatario = 'Jrci';
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
        // $Destinatario = 'Jrci';
        // Se crea un array que contiene las copias y se filtra por las que traigan el dato
        $array_copias = [$afiliado, $empleador, $eps, $afp, $arl, $jrci, $jnci];
        $variables_filtradas = array_filter($array_copias, function($var) {
            return !empty($var);
        });
        // Verifica si el array resultante está vacío
        if (!empty($variables_filtradas)) {
            // Si hay elementos en el array, los concatenamos con comas
            $Agregar_copias = implode(", ", $variables_filtradas);
        } else {
            // Si el array está vacío, asignamos una cadena vacía
            $Agregar_copias = '';
        }

        if($request->decision_dictamen === 'Desacuerdo'){
            $tipo_descarga = 'RECURSO JRCI';
        }
        else if($request->decision_dictamen === 'Acuerdo'){
            $tipo_descarga = 'ACUERDO JRCI';
        }else{
            $tipo_descarga = 'Controversia';
        }

        //Se asignan los IDs de destinatario por cada posible destinatario
        $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario(true, true);
        if ($bandera_correspondecia_guardar_actualizar == 'Guardar') {
            $datos_correspondencia = [
                'ID_evento' => $newId_evento,
                'Id_proceso' => $Id_proceso,
                'Id_Asignacion' => $newId_asignacion,
                'Visar' => 'No',
                'Profesional_comite' => null,
                'F_visado_comite' => null,
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
                'Copia_afiliado' => $afiliado,
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
                'Decision_dictamen' => $request->decision_dictamen,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];
    
            $id_comite = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
            ->insertGetId($datos_correspondencia);       
    
            $datos_info_comunicado_eventos = [
                'ID_Evento' => $newId_evento,
                'Id_proceso' => $Id_proceso,
                'Id_Asignacion' => $newId_asignacion,
                'Ciudad' => $ciudad,
                'F_comunicado' => $date,
                'N_radicado' => $radicado,
                'Cliente' => 'N/A',
                'Nombre_afiliado' => $nombreAfiliado,
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
                'Agregar_copia' => $Agregar_copias,
                'JRCI_copia' => $cual,
                'Anexos' => $anexos,
                'Tipo_descarga' => $tipo_descarga,
                'Modulo_creacion' => 'controversiaJuntas',
                'Reemplazado' => 0,
                'Otro_destinatario' => $request->nombre_destinatariopri ? 1 : 0,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_comunicado_eventos);
    
            $this->generarProforma($request->decision_dictamen, $id_comunicado,$id_comite,$newId_evento,$newId_asignacion,$Id_proceso, $request);

            $mensajes = array(
                "parametro" => 'insertar_correspondencia',
                "mensaje" => 'Correspondencia guardada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
            
        } 
        elseif($bandera_correspondecia_guardar_actualizar == 'Actualizar') {
            $datos_correspondencia = [
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
                'Copia_afiliado' => $afiliado,
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
                // 'N_radicado' => $radicado,
                'Decision_dictamen' => $request->decision_dictamen,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];
            
            sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$newId_evento],
                ['Id_Asignacion',$newId_asignacion]
            ])->update($datos_correspondencia); 
            
            $datos_info_comunicado_eventos = [
                'ID_Evento' => $newId_evento,
                'Id_proceso' => $Id_proceso,
                'Id_Asignacion' => $newId_asignacion,
                'Ciudad' => $ciudad,
                'F_comunicado' => $date,
                // 'N_radicado' => $radicado,
                'Cliente' => 'N/A',
                'Nombre_afiliado' => $nombreAfiliado,
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
                'Agregar_copia' => $Agregar_copias,
                'JRCI_copia' => $cual,
                'Anexos' => $anexos,
                'Tipo_descarga' => $tipo_descarga,
                'Modulo_creacion' => 'controversiaJuntas',
                'Reemplazado' => 0,
                'Otro_destinatario' => $request->nombre_destinatariopri ? 1 : 0,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];   
                
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $newId_evento],
                    ['Id_Asignacion',$newId_asignacion],
                    ['Id_proceso', $Id_proceso],
                    ['N_radicado',$request->radicado]
                    ])
            ->update($datos_info_comunicado_eventos);

            $mensajes = array(
                "parametro" => 'actualizar_correspondencia',
                "mensaje" => 'Correspondencia actualizada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
        }
        

    }

    public  function generarProforma($tipo, $id_comunicado,$id_comite, $id_evento,$id_asignacion,$proceso,$request){
        $data = [
            "_token" => $request->_token,
            "id_comite_inter" => $id_comite,
            "id_cliente" => $request->id_cliente,
            "id_proceso" => $proceso,
            'id_servicio' => $request->id_servicio,
            "id_asignacion" => $id_asignacion,
            "nro_radicado" => $request->radicado,
            "num_identificacion" => $request->num_identificacion,
            'tipo_identificacion' => $request->tipo_identificacion,
            "id_evento" => $id_evento,
            "id_Jrci_califi_invalidez" => $request->id_Jrci_califi_invalidez,
            "nombre_junta_regional" => $request->nombre_junta_regional,
            'f_dictamen_jrci_emitido' => $request->f_dictamen_jrci_emitido,
            "nro_dictamen" => $request->nro_dictamen,
            "nombre_afiliado" => $request->nombre_afiliado,
            "origen_jrci_emitido" => $request->origen_jrci_emitido,
            "manual_de_califi_jrci_emitido" => $request->manual_de_califi_jrci_emitido,
            "sustentacion_concepto_jrci" => $request->sustentacion_concepto_jrci,
            "sustentacion_concepto_jrci1" => $request->sustentacion_concepto_jrci1,
            'copia_afiliado' => $request->afiliado,
            'copia_empleador' => $request->empleador,
            'copia_eps' => $request->eps,
            'copia_afp'=> $request->afp,
            'copia_arl' => $request->arl,
            'porcentaje_pcl_jrci_emitido' => $request->porcentaje_pcl_jrci_emitido,
            'f_estructuracion_contro_jrci_emitido' => $request->f_estructuracion_contro_jrci_emitido,
            "asunto" => $request->Asunto,
            "cuerpo" => $request->cuerpo_comunicado,
            "firmar" => $request->firmar,
            "elaboro" => $request->elaboro,
            "reviso" => $request->reviso,
            "firmar" => $request->firmar,
            "id_comunicado" => $id_comunicado,
            "N_siniestro" => $request->N_siniestro,
        ];
        
        $requestTMP = new Request();
        $requestTMP->setMethod('POST');
        $requestTMP->request->add($data);
       
        if($tipo == 'Acuerdo'){
            $this->DescargarProformaPronunDictaAcuerdo($requestTMP);
        }elseif($tipo == 'Desacuerdo'){
            $this->DescargarProformaRecursoReposicion($requestTMP);
        }

        return "PDF generado";
    }

    public function CargueInformacionCorrespondencia(Request $request){

        // $tupla_comunicado = $request->tupla_comunicado;
        $id_evento = $request->id_evento;
        $id_asignacion = $request->id_asignacion;
        $parametro = $request->parametro;
        
        if($parametro == "controvertido"){
            $arrayinfo_controvertido= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_controversia_juntas_eventos as j')
            ->select('j.ID_evento','j.Enfermedad_heredada','j.F_transferencia_enfermedad','j.Primer_calificador','pa.Nombre_parametro as Calificador'
            ,'j.Nom_entidad','j.N_dictamen_controvertido','j.F_notifi_afiliado','j.Parte_controvierte_califi','pa2.Nombre_parametro as ParteCalificador','j.Nombre_controvierte_califi',
            'j.N_radicado_entrada_contro','j.Contro_origen','j.Contro_pcl','j.Contro_diagnostico','j.Contro_f_estructura','j.Contro_m_califi',
            'j.F_contro_primer_califi','j.F_contro_radi_califi','j.Termino_contro_califi','j.Jrci_califi_invalidez','sie.Nombre_entidad as JrciNombre',
            'j.Origen_controversia','pa4.Nombre_parametro as OrigenContro','j.Manual_de_califi','d.Nombre_decreto','j.Total_deficiencia','j.Total_rol_ocupacional','j.Total_discapacidad',
            'j.Total_minusvalia','j.Porcentaje_pcl','j.Rango_pcl','j.F_estructuracion_contro','j.N_pago_jnci_contro','j.F_pago_jnci_contro','j.F_radica_pago_jnci_contro','j.F_envio_jrci','j.N_dictamen_jrci_emitido'
            ,'j.F_dictamen_jrci_emitido','j.Origen_jrci_emitido','pa5.Nombre_parametro as OrigenEmitidoJrci','j.Manual_de_califi_jrci_emitido','d1.Nombre_decreto as Nombre_decretoJrci','j.Total_deficiencia_jrci_emitido',
            'j.Total_rol_ocupacional_jrci_emitido','j.Total_discapacidad_jrci_emitido','j.Total_minusvalia_jrci_emitido','j.Porcentaje_pcl_jrci_emitido','j.Rango_pcl_jrci_emitido',
            'j.F_estructuracion_contro_jrci_emitido','j.Resumen_dictamen_jrci','j.F_noti_dictamen_jrci','j.F_radica_dictamen_jrci','j.F_maxima_recurso_jrci','j.Decision_dictamen_jrci',
            'j.Sustentacion_concepto_jrci','j.F_sustenta_jrci','j.F_notificacion_recurso_jrci','j.N_radicado_recurso_jrci','j.Termino_contro_propia_jrci','j.Causal_decision_jrci',
            'j.Firmeza_intere_contro_jrci','j.Firmeza_reposicion_jrci','j.Firmeza_acta_ejecutoria_jrci','j.Firmeza_apelacion_jnci_jrci','j.Parte_contro_ante_jrci','pa7.Nombre_parametro as NomPresentaJrci',
            'j.Nombre_presen_contro_jrci','j.F_contro_otra_jrci','j.Contro_origen_jrci','j.Contro_pcl_jrci','j.Contro_diagnostico_jrci','j.Contro_f_estructura_jrci','j.Contro_m_califi_jrci','j.Reposicion_dictamen_jrci',
            'j.N_dictamen_reposicion_jrci','j.F_dictamen_reposicion_jrci','j.Origen_reposicion_jrci','pa8.Nombre_parametro as Nombre_origenRepoJrci','j.Manual_reposicion_jrci','d2.Nombre_decreto as Nombre_decretoRepoJrci',
            'j.Total_deficiencia_reposicion_jrci','j.Total_reposicion_jrci','j.Total_discapacidad_reposicion_jrci','j.Total_minusvalia_reposicion_jrci','j.Porcentaje_pcl_reposicion_jrci','j.Rango_pcl_reposicion_jrci'
            ,'j.F_estructuracion_contro_reposicion_jrci','j.Resumen_dictamen_reposicion_jrci','j.F_noti_dictamen_reposicion_jrci','j.F_radica_dictamen_reposicion_jrci','j.F_maxima_apelacion_jrci','j.Decision_dictamen_repo_jrci'
            ,'j.Decision_dictamen_repo_jrci','j.Causal_decision_repo_jrci','j.Sustentacion_concepto_repo_jrci','j.F_sustenta_reposicion_jrci','j.F_noti_apela_recurso_jrci'
            ,'j.N_radicado_apela_recurso_jrci','j.T_propia_apela_recurso_jrci','j.Correspon_pago_jnci','j.N_orden_pago_jnci','j.F_orden_pago_jnci','j.F_radi_pago_jnci','j.N_acta_ejecutario_emitida_jrci'
            ,'j.F_acta_ejecutoria_emitida_jrci','j.F_firmeza_dictamen_jrci','j.Dictamen_firme_jrci','j.N_dictamen_jnci_emitido','j.F_dictamen_jnci_emitido','j.Origen_jnci_emitido','pa10.Nombre_parametro as NombreOrigen'
            ,'j.Manual_de_califi_jnci_emitido','pa11.Nombre_parametro as Nombre_decretoJnci','j.Total_deficiencia_jnci_emitido','j.Total_rol_ocupacional_jnci_emitido','j.Total_discapacidad_jnci_emitido'
            ,'j.Total_minusvalia_jnci_emitido','j.Porcentaje_pcl_jnci_emitido','j.Rango_pcl_jnci_emitido','j.F_estructuracion_contro_jnci_emitido','j.Resumen_dictamen_jnci','j.Sustentacion_dictamen_jnci'
            ,'j.F_sustenta_ante_jnci','j.F_noti_ante_jnci','j.F_radica_dictamen_jnci','j.F_envio_jnci')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa', 'j.Primer_calificador', '=', 'pa.Id_Parametro','j.')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa2', 'j.Parte_controvierte_califi', '=', 'pa2.Id_Parametro')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'j.Jrci_califi_invalidez', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa4', 'j.Origen_controversia', '=', 'pa4.Id_Parametro')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa5', 'j.Origen_jrci_emitido', '=', 'pa5.Id_Parametro')
            //->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa6', 'j.Causal_decision_jrci', '=', 'pa6.Id_Parametro')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa7', 'j.Parte_contro_ante_jrci', '=', 'pa7.Id_Parametro')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa8', 'j.Origen_reposicion_jrci', '=', 'pa8.Id_Parametro')
            //->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa9', 'j.Causal_decision_repo_jrci', '=', 'pa9.Id_Parametro')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa10', 'j.Origen_jnci_emitido', '=', 'pa10.Id_Parametro')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa11', 'j.Manual_de_califi_jnci_emitido', '=', 'pa11.Id_Parametro')
            ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d', 'j.Manual_de_califi', '=', 'd.Id_Decreto')
            ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d1', 'j.Manual_de_califi_jrci_emitido', '=', 'd1.Id_Decreto')
            ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d2', 'j.Manual_reposicion_jrci', '=', 'd2.Id_Decreto')
            ->where([
                ['j.ID_evento',  '=', $id_evento],
                ['j.Id_Asignacion',  '=', $id_asignacion]
            ])
            ->get();

            if(!empty($arrayinfo_controvertido[0]->JrciNombre)) 
            { 
                $destinatario_principal =  $arrayinfo_controvertido[0]->JrciNombre;
            }else{
                $destinatario_principal =  "";
            }

            $datos =['destinatario_principal' => $destinatario_principal];

            return response()->json($datos);
        }

        if($parametro == "correspondencia"){
            $array_comite_interdisciplinario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comite_interdisciplinario_eventos as sicie')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sicie.Nombre_dest_principal')
            ->select('sicie.ID_evento', 'sicie.Id_proceso', 'sicie.Id_Asignacion', 'sicie.Visar', 'sicie.Profesional_comite', 'sicie.F_visado_comite',
            'sicie.Destinatario_principal', 'sicie.Otro_destinatario', 'sicie.Tipo_destinatario', 'sicie.Nombre_dest_principal', 'sie.Nombre_entidad',
            'sicie.Nombre_destinatario','sicie.Nit_cc', 'sicie.Direccion_destinatario', 'sicie.Telefono_destinatario', 'sicie.Email_destinatario',
            'sicie.Departamento_destinatario', 'sicie.Ciudad_destinatario', 'sicie.Asunto', 'sicie.Cuerpo_comunicado', 'sicie.Copia_afiliado', 'sicie.Copia_empleador',
            'sicie.Copia_eps', 'sicie.Copia_afp', 'sicie.Copia_arl', 'sicie.Copia_jr', 'sicie.Cual_jr', 'sicie.Copia_jn', 'sicie.Anexos',
            'sicie.Elaboro', 'sicie.Reviso', 'sicie.Firmar', 'sicie.Ciudad', 'sicie.F_correspondecia', 'sicie.N_radicado', 'sicie.Nombre_usuario',
            'sicie.F_registro')        
            ->where([
                ['Id_com_inter',$request->id_comite_inter],
                ['ID_evento',$id_evento],
                ['Id_Asignacion',$id_asignacion]
            ])
            ->get();

            // checkbox otro destinatario principal
            if(!empty($array_comite_interdisciplinario[0]->Otro_destinatario)) 
            { 
                $checkeado_otro_destinatario =  "Si";
            }else{
                $checkeado_otro_destinatario =  "No";
            }

            // tipo de destinatario principal opcion: afp, arl, eps
            if(!empty($array_comite_interdisciplinario[0]->Tipo_destinatario))
            { 
                $db_tipo_destinatario_principal = $array_comite_interdisciplinario[0]->Tipo_destinatario;
            }else{
                $db_tipo_destinatario_principal = "";
            }

            // nombre del destinatario principal opcion: afp, arl, eps
            if(!empty($array_comite_interdisciplinario[0]->Nombre_dest_principal))
            { 
                $db_nombre_destinatariopri = $array_comite_interdisciplinario[0]->Nombre_dest_principal;
            }else{
                $db_nombre_destinatariopri= "";
            }

            // Datos de la opcion otro del tipo de destinatario principal
            if (!empty($array_comite_interdisciplinario[0]->Nombre_destinatario)) {
                $nombre_destinatario = $array_comite_interdisciplinario[0]->Nombre_destinatario;
            }else{
                $nombre_destinatario = "";
            }

            if (!empty($array_comite_interdisciplinario[0]->Nit_cc)) {
                $nitcc_destinatario = $array_comite_interdisciplinario[0]->Nit_cc;
            }else{
                $nitcc_destinatario = "";
            }

            if (!empty($array_comite_interdisciplinario[0]->Direccion_destinatario)) {
                $direccion_destinatario = $array_comite_interdisciplinario[0]->Direccion_destinatario;
            } else {
                $direccion_destinatario = "";
            }

            if (!empty($array_comite_interdisciplinario[0]->Telefono_destinatario)) {
                $telefono_destinatario = $array_comite_interdisciplinario[0]->Telefono_destinatario;
            } else {
                $telefono_destinatario = "";
            }

            if (!empty($array_comite_interdisciplinario[0]->Email_destinatario)) {
                $email_destinatario = $array_comite_interdisciplinario[0]->Email_destinatario;
            } else {
                $email_destinatario = "";
            }
            
            if (!empty($array_comite_interdisciplinario[0]->Departamento_destinatario)) {
                $departamento_destinatario = $array_comite_interdisciplinario[0]->Departamento_destinatario;
            } else {
                $departamento_destinatario = "";
            }
            
            if (!empty($array_comite_interdisciplinario[0]->Ciudad_destinatario)) {
                $ciudad_destinatario = $array_comite_interdisciplinario[0]->Ciudad_destinatario;
            } else {
                $ciudad_destinatario = "";
            }

            // Asunto
            if (!empty($array_comite_interdisciplinario[0]->Asunto)) {
                $Asunto = $array_comite_interdisciplinario[0]->Asunto;
            } else {
                $Asunto = "";
            }
            
            // Cuerpo del Comunicado
            if (!empty($array_comite_interdisciplinario[0]->Cuerpo_comunicado)) {
               $cuerpo_comunicado = $array_comite_interdisciplinario[0]->Cuerpo_comunicado;
            } else {
                $cuerpo_comunicado = "";
            }
            
            // Copias a partes interesadas
            if (!empty($array_comite_interdisciplinario[0]->Copia_afiliado)) {
                $checkeado_afiliado = "Si";
            } else {
                $checkeado_afiliado = "No";
            }

            if (!empty($array_comite_interdisciplinario[0]->Copia_empleador)) {
                $checkeado_empleador = "Si";
            } else {
                $checkeado_empleador = "No";
            }

            if (!empty($array_comite_interdisciplinario[0]->Copia_eps)) {
                $checkeado_eps = "Si";
            } else {
                $checkeado_eps = "No";
            }

            if (!empty($array_comite_interdisciplinario[0]->Copia_afp)) {
                $checkeado_afp = "Si";
            } else {
                $checkeado_afp = "No";
            }

            if (!empty($array_comite_interdisciplinario[0]->Copia_arl)) {
                $checkeado_arl = "Si";
            } else {
                $checkeado_arl = "No";
            }
            
            if (!empty($array_comite_interdisciplinario[0]->Copia_jr)) {
                $checkeado_copia_jr = "Si";
            } else {
                $checkeado_copia_jr = "No";
            }
            
            if (!empty($array_comite_interdisciplinario[0]->Cual_jr)) {
                $bd_cual_jr = $array_comite_interdisciplinario[0]->Cual_jr;
            } else {
                $bd_cual_jr = "";
            }
            
            if (!empty($array_comite_interdisciplinario[0]->Copia_jn)) {
                $checkeado_copia_jn = "Si";
            } else {
                $checkeado_copia_jn = "No";
            }
            
            /* Anexos */
            if (!empty($array_comite_interdisciplinario[0]->Anexos)) {
                $anexos = $array_comite_interdisciplinario[0]->Anexos;
            } else {
                $anexos = "";
            }
            
            /* Elaboró */
            if (!empty($array_comite_interdisciplinario[0]->Elaboro)) {
                $elaboro = $array_comite_interdisciplinario[0]->Elaboro;
            } else {
                $elaboro = Auth::user()->name;
            }
            
            /* Reviso */
            if (!empty($array_comite_interdisciplinario[0]->Reviso)) {
                $bd_reviso = $array_comite_interdisciplinario[0]->Reviso;
            } else {
                $bd_reviso = "";
            }
            
            /* Checkbox Firmar */
            if (!empty($array_comite_interdisciplinario[0]->Firmar)) {
                $firmar = "Si";
            } else {
                $firmar = "No";
            }
            
            /* Ciudad */
            if (!empty($array_comite_interdisciplinario[0]->Ciudad)) {
                $ciudad = $array_comite_interdisciplinario[0]->Ciudad;
            } else {
                $ciudad = "Bogotá D.C";
            }
            
            // Fecha Correspondencia
            if (!empty($array_comite_interdisciplinario[0]->F_correspondecia)) {
                $f_correspondencia = $array_comite_interdisciplinario[0]->F_correspondecia;
            } else {
                $f_correspondencia = now()->format('Y-m-d');
            }
            

            $datos_correspondencia = [
                'checkeado_otro_destinatario' => $checkeado_otro_destinatario,
                'db_tipo_destinatario_principal' => $db_tipo_destinatario_principal,
                'db_nombre_destinatariopri' => $db_nombre_destinatariopri,
                'nombre_destinatario' => $nombre_destinatario,
                'nitcc_destinatario' => $nitcc_destinatario,
                'direccion_destinatario' => $direccion_destinatario,
                'telefono_destinatario' => $telefono_destinatario,
                'email_destinatario' => $email_destinatario,
                'departamento_destinatario' => $departamento_destinatario,
                'ciudad_destinatario' => $ciudad_destinatario,
                'Asunto' => $Asunto,
                'cuerpo_comunicado' => $cuerpo_comunicado,
                'checkeado_afiliado' => $checkeado_afiliado,
                'checkeado_empleador' => $checkeado_empleador,
                'checkeado_eps' => $checkeado_eps,
                'checkeado_afp' => $checkeado_afp,
                'checkeado_arl' => $checkeado_arl,
                'checkeado_copia_jr' => $checkeado_copia_jr,
                'bd_cual_jr' => $bd_cual_jr,
                'checkeado_copia_jn' => $checkeado_copia_jn,
                'anexos' => $anexos,
                'elaboro' => $elaboro,
                'bd_reviso' => $bd_reviso,
                'firmar' => $firmar,
                'ciudad' => $ciudad,
                'f_correspondencia' => $f_correspondencia
            ];

            return response()->json($datos_correspondencia);

        }

        if ($parametro == "controversia_juntas") {

            $array_datos_controversiaJuntas = DB::select('CALL psrcalificacionJuntas(?)', array($id_asignacion));

            /* Nombre destinatario opcion afiliado */
            $nombre_destinatario_afi = $array_datos_controversiaJuntas[0]->Nombre_afiliado;
            /* Nombre destinatario opcion empleador */
            $nombre_destinatario_emp = $array_datos_controversiaJuntas[0]->Empleador_afi;

            /* id servicio ya sea controversia pcl o controversia origen */
            // $id_servicio = $array_datos_controversiaJuntas[0]->Id_Servicio ;

            $datos_controversiaJuntas = [
                'nombre_destinatario_afi' => $nombre_destinatario_afi,
                'nombre_destinatario_emp' => $nombre_destinatario_emp,
                // 'id_servicio' => $id_servicio
            ];

            return response()->json($datos_controversiaJuntas);
        }



    }

    /* Proforma Desacuerdo */
    public function DescargarProformaRecursoReposicion(Request $request){
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        /* Captura de variables que vienen del ajax */
        $id_comite_inter = $request->id_comite_inter;
        $id_cliente = $request->id_cliente;
        $id_asignacion = $request->id_asignacion;
        $id_proceso = $request->id_proceso;
        $id_servicio = $request->id_servicio;
        $nro_radicado = $request->nro_radicado;
        $nombre_afiliado = $request->nombre_afiliado;
        $tipo_identificacion = $request->tipo_identificacion;
        $Id_comunicado = $request->id_comunicado;
        $num_identificacion = $request->num_identificacion;
        $id_evento = $request->id_evento;
        $id_Jrci_califi_invalidez = $request->id_Jrci_califi_invalidez;
        $f_dictamen_jrci_emitido = $request->f_dictamen_jrci_emitido;
        $sustentacion_concepto_jrci = $request->sustentacion_concepto_jrci;
        $sustentacion_concepto_jrci1 = $request->sustentacion_concepto_jrci1;
        $copia_afiliado = $request->copia_afiliado;
        $copia_empleador = $request->copia_empleador;
        $copia_eps = $request->copia_eps;
        $copia_afp = $request->copia_afp;
        $copia_arl = $request->copia_arl;
        $copia_jrci = $request->copia_jrci;
        $copia_jnci = $request->copia_jnci;
        $jrci_elegida = $request->jrci_elegida;
        $asunto = strtoupper($request->asunto);
        $cuerpo = $request->cuerpo;
        $firmar = $request->firmar;
        $N_siniestro = $request->N_siniestro;
        $porcentaje_pcl_jrci_emitido = $request->porcentaje_pcl_jrci_emitido;
        $f_estructuracion_contro_jrci_emitido = $request->f_estructuracion_contro_jrci_emitido;

        /* Creación de las variables faltantes que no están en el ajax */

        // Validación información Destinatario Principal
        $datos_para_destinatario_principal = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([['Id_com_inter', $id_comite_inter]])->get();

        $array_datos_para_destinatario_principal = json_decode(json_encode($datos_para_destinatario_principal), true);
        $checkbox_otro_destinatario = $array_datos_para_destinatario_principal[0]["Otro_destinatario"];

        //Fecha de sustentacion antes la JRCI
        $fecha_sustent_jrci = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$id_evento],['Id_Asignacion',$id_asignacion]])
        ->value('F_sustenta_jrci');

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
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Departamento', '=', 'sldm2.Id_departamento')
                    ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad', 'sldm2.Nombre_departamento')
                    ->where([
                        ['sie.Id_Entidad', $id_entidad],
                        ['sie.IdTipo_entidad', $tipo_destinatario]
                    ])->get();

                    $nombre_junta = $datos_entidad[0]->Nombre_entidad;
                    $direccion_junta = $datos_entidad[0]->Direccion;
                    $telefono_junta = $datos_entidad[0]->Telefonos;
                    $ciudad_junta = $datos_entidad[0]->Nombre_ciudad;
                    $departamento_junta = $datos_entidad[0]->Nombre_departamento;

                break;
                
                // Si escoge la opción Afiliado: Se sacan los datos del destinatario principal pero del afiliado
                case ($tipo_destinatario == 4):
                    $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('siae.Nombre_afiliado','siae.Direccion','siae.Telefono_contacto','sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
                    ->where([['siae.ID_evento','=', $id_evento]])
                    ->get();
        
                    $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);
        
                    $nombre_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_afiliado"];;
                    $direccion_junta = $array_datos_municipio_ciudad_afiliado[0]["Direccion"];
                    $telefono_junta = $array_datos_municipio_ciudad_afiliado[0]["Telefono_contacto"];
                    $ciudad_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"];
                    $departamento_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_departamento"];

                break;

                // Si escoge la opción Empleador: Se sacan los datos del destinatario principal pero del Empleador
                case ($tipo_destinatario == 5):
                    $datos_entidad_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_municipio', '=', 'sldm.Id_municipios')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_departamento', '=', 'sldm2.Id_departamento')
                    ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_municipio as Nombre_ciudad', 'sldm2.Nombre_departamento')
                    ->where([['sile.ID_evento', $id_evento]])->get();

                    $nombre_junta = $datos_entidad_empleador[0]->Empresa;
                    $direccion_junta = $datos_entidad_empleador[0]->Direccion;
                    $telefono_junta = $datos_entidad_empleador[0]->Telefono_empresa;
                    $ciudad_junta = $datos_entidad_empleador[0]->Nombre_ciudad;
                    $departamento_junta = $datos_entidad_empleador[0]->Nombre_departamento;

                break;
                
                // Si escoge la opción Otro: se sacan los datos del destinatario de la tabla sigmel_informacion_comite_interdisciplinario_eventos
                case ($tipo_destinatario == 8):
                    // aqui validamos si los datos no vienen vacios, debido a que si  vienen vacios, toca marcar ''
                    if (!empty($array_datos_para_destinatario_principal[0]["Nombre_destinatario"])) {
                        $nombre_junta = $array_datos_para_destinatario_principal[0]["Nombre_destinatario"];
                    } else {
                        $nombre_junta = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Direccion_destinatario"])) {
                        $direccion_junta = $array_datos_para_destinatario_principal[0]["Direccion_destinatario"];
                    } else {
                        $direccion_junta = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Telefono_destinatario"])) {
                        $telefono_junta = $array_datos_para_destinatario_principal[0]["Telefono_destinatario"];
                    } else {
                        $telefono_junta = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Ciudad_destinatario"])) {
                        $ciudad_junta = $array_datos_para_destinatario_principal[0]["Ciudad_destinatario"];
                    } else {
                        $ciudad_junta = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Departamento_destinatario"])) {
                        $departamento_junta = $array_datos_para_destinatario_principal[0]["Departamento_destinatario"];
                    } else {
                        $departamento_junta = "";
                    };

                break;

                default:
                    # code...
                break;
            }
        }else{
            // Datos Junta regional
            $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
            ->where([['sie.Id_Entidad', $id_Jrci_califi_invalidez]])->get();

            $array_datos_junta_regional = json_decode(json_encode($datos_junta_regional), true);

            if(count($array_datos_junta_regional)>0){
                $nombre_junta = $request->nombre_junta_regional;
                $direccion_junta = $array_datos_junta_regional[0]["Direccion"];
                $telefono_junta = $array_datos_junta_regional[0]["Telefonos"];
                $departamento_junta = $array_datos_junta_regional[0]["Nombre_departamento"];
                $ciudad_junta = $array_datos_junta_regional[0]["Nombre_ciudad"];
            }else {
                $nombre_junta = "";
                $direccion_junta = "";
                $telefono_junta = "";
                $ciudad_junta = "";
                $departamento_junta = "";
            }

            // $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            // ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
            // ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
            // ->select('siae.Nombre_afiliado','siae.Direccion','siae.Telefono_contacto','sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
            // ->where([['siae.ID_evento','=', $id_evento]])
            // ->get();

            // $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);

            // if (count($array_datos_municipio_ciudad_afiliado) > 0) {
            //     $nombre_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_afiliado"];;
            //     $direccion_junta = $array_datos_municipio_ciudad_afiliado[0]["Direccion"];
            //     $telefono_junta = $array_datos_municipio_ciudad_afiliado[0]["Telefono_contacto"];
            //     $ciudad_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"];
            //     $departamento_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_departamento"];
            // } else {
            //     $nombre_junta = "";
            //     $direccion_junta = "";
            //     $telefono_junta = "";
            //     $departamento_junta = "";
            //     $ciudad_junta = "";
            // }
            
        }

        /* Tipos de controversia primera calificación */
        $datos_tipo_controversia = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->select('Contro_origen', 'Contro_pcl', 'Contro_diagnostico', 'Contro_f_estructura', 'Contro_m_califi')
        ->where([['ID_evento',$id_evento],
            ['Id_Asignacion',$id_asignacion],
        ])->get();
        $array_datos_tipo_controversia = json_decode(json_encode($datos_tipo_controversia), true);

        if (count($array_datos_tipo_controversia) > 0) {

            // Obtener los valores del primer elemento del array
            $controversias = array_values($array_datos_tipo_controversia[0]);
            $controversias = array_filter($controversias); // Eliminar valores null
            //En el PBS060 piden Tipo de controversia primera calificación de la siguiente manera: Si se seleccionan las opciones Origen o Diagnósticos no deberá traer nada),
            if($id_servicio != "12"){
                $controversias = array_diff($controversias, ['Origen', 'Diagnósticos']);
            }
            
            if (!empty($controversias)) {
                // Extraer el último valor si hay más de un elemento
                $ultimo_tipo_controversia = array_pop($controversias);
            
                // Concatenar los valores con comas y agregar "y" antes del último
                if (!empty($controversias)) {
                    $string_tipos_controversia = implode(", ", $controversias) . " y " . $ultimo_tipo_controversia;
                } else {
                    // Si solo hay un valor, no es necesario el "y", ni separarlo por comas
                    $string_tipos_controversia = $ultimo_tipo_controversia;
                }
                $string_tipos_controversia = str_replace('% PCL', 'la pérdida de capacidad laboral', $string_tipos_controversia);
                $string_tipos_controversia = str_replace('Fecha estructuración', 'la Fecha de estructuración', $string_tipos_controversia);
                $string_tipos_controversia = str_replace('Manual de calificación', 'el Manual de calificación', $string_tipos_controversia);

            } else {
                $string_tipos_controversia = "";
            }

        } else {
            $string_tipos_controversia = "";
        }
        // Traer datos CIE10 de Dictamen emitido por la Junta Regional de Calificación de Invalidez (JRCI)
        $diagnosticos_cie10 = array();
        $datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->select('slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->where([
            ['side.ID_evento', '=', $id_evento],
            ['side.Id_Asignacion', '=', $id_asignacion],
            ['side.Item_servicio', '=', 'Emitido JRCI'],
            ['side.Estado', '=', 'Activo'],
        ])
        ->get();

        $array_datos_diagnostico_motcalifi = json_decode(json_encode($datos_diagnostico_motcalifi), true);

        for ($i=0; $i < count($array_datos_diagnostico_motcalifi); $i++) { 
            $dato_concatenado = "(<b>".$array_datos_diagnostico_motcalifi[$i]['Codigo']."</b>) ".mb_strtoupper($array_datos_diagnostico_motcalifi[$i]['Nombre_CIE10'], 'UTF-8').", ".$array_datos_diagnostico_motcalifi[$i]['Nombre_parametro']."";
            array_push($diagnosticos_cie10, $dato_concatenado);
        }

        $string_diagnosticos_cie10 = implode(" - ", $diagnosticos_cie10);
        $string_diagnosticos_cie10 = $string_diagnosticos_cie10;

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
        if(isset($copia_afiliado)){
            
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
            $Agregar_copias['Empleador'] = $this->globalService->retornarEmpleador($num_identificacion,$id_evento);   
        }

        if (isset($copia_eps)) {
            $Agregar_copias['EPS'] = $this->globalService->retornarCopiaEntidad($num_identificacion,$id_evento,'eps');
        }

        if (isset($copia_afp)) {
            $Agregar_copias['AFP'] = $this->globalService->retornarCopiaEntidad($num_identificacion,$id_evento,'afp');
        }

        if(isset($copia_arl)){
            $Agregar_copias['ARL'] = $this->globalService->retornarCopiaEntidad($num_identificacion,$id_evento,'arl');
        }

        if(isset($copia_jrci)){
            $Agregar_copias['JRCI'] = $this->globalService->retornarJrci(null,$jrci_elegida);
        }

        if(isset($copia_jnci)){
            $Agregar_copias['JNCI'] = $this->globalService->retornarJnci();
        }

        /* Validación Firma Cliente */
        $validarFirma = isset($firmar) ? 'Firmar' : 'Sin Firma';
        
        if ($validarFirma == "Firmar") {
            $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente')
            ->where('Id_cliente', $id_cliente)->limit(1)->get();

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
        ->where([['Id_cliente', $id_cliente]])
        ->limit(1)->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
            $ruta_logo = "/logos_clientes/{$id_cliente}/{$logo_header}";
        } else {
            $logo_header = "Sin logo";
            $ruta_logo = "";
        }

        $dato_logo_footer = sigmel_clientes::on('sigmel_gestiones')
        ->select('Footer_cliente')
        ->where([['Id_cliente', $id_cliente]])
        ->limit(1)->get();

        if (count($dato_logo_footer) > 0 && $dato_logo_footer[0]->Footer_cliente != null) {
            $logo_footer = $dato_logo_footer[0]->Footer_cliente;
            $ruta_logo_footer = "/footer_clientes/{$id_cliente}/{$logo_footer}";
        } else {
            $logo_footer = null;
            $ruta_logo_footer = null;
        }

        /* Extraemos los datos del footer */
        // $datos_footer = sigmel_clientes::on('sigmel_gestiones')
        // ->select('footer_dato_1', 'footer_dato_2', 'footer_dato_3', 'footer_dato_4', 'footer_dato_5')
        // ->where('Id_cliente', $id_cliente)->get();

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

        /* Construcción proforma en formato docx (word) */
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
        $fontStylePaginado = ['name' => 'Calibri', 'size' => 8];
        $encabezado->addText('Página ', $fontStylePaginado);
        $encabezado->addField('PAGE',[],[],null,$fontStylePaginado);
        $encabezado->addText(' de ',$fontStylePaginado);
        $encabezado->addField('NUMPAGES',[],[],null,$fontStylePaginado);
        $header->addTextBreak();

        // Creación de Contenido
        $fecha_formateada = fechaFormateada($fecha_sustent_jrci ? $fecha_sustent_jrci : $date);
        $section->addText('Bogotá D.C. '.$fecha_formateada, array('bold' => true), array('align' => 'right'));
        $section->addTextBreak();


        $table = $section->addTable();

        $table->addRow();

        $cell1 = $table->addCell(6000);

        $textRun1 = $cell1->addTextRun(array('alignment'=>'left'));
        $textRun1->addText('Señores: ',array('bold' => true));
        $textRun1->addTextBreak();
        $textRun1->addText($nombre_junta);
        $textRun1->addTextBreak();
        $textRun1->addText($direccion_junta);
        $textRun1->addTextBreak();
        $textRun1->addText('Tel: ');
        $textRun1->addText($telefono_junta);
        $textRun1->addTextBreak();
        if($ciudad_junta !== 'Bogota D.C.'){
            $textRun1->addText($ciudad_junta.' - '.$departamento_junta);
        }else{
            $textRun1->addText($ciudad_junta);
        }

        $section->addTextBreak();
        $section->addTextBreak();
        // $htmltabla1 = '<table align="justify" style="width: 100%; border: none;">
        //     <tr>
        //         <td>
        //             <p><b>Señores: </b>'.$nombre_junta.'</p>
        //             <p><b>Dirección: </b>'.$direccion_junta.'</p>
        //             <p><b>Teléfono: </b>'.$telefono_junta.'</p>
        //             <p><b>Ciudad: </b>'.$ciudad_junta.' - '.$departamento_junta.'</p>
        //         </td>
        //         <td>
        //             <table style="width: 60%; border: 3px black solid;">
        //                 <tr>
        //                     <td>
        //                         <p><b>Nro. Radicado: '.$nro_radicado.'</b></p>  
        //                         <p><b>'.$tipo_identificacion." ".$num_identificacion.'</b></p>
        //                         <p><b>Siniestro: '.$id_evento.'</b></p>
        //                     </td>
        //                 </tr>
        //             </table>
        //         </td>
        //     </tr>
        // </table>';

        // Html::addHtml($section, $htmltabla1, false, true);

        $patron_asunto = '/\{\{\$F_DICTAMEN_JRCI_ASUNTO\}\}/'; 
        if (preg_match($patron_asunto, $asunto)) {
            $asunto_modificado = str_replace('{{$F_DICTAMEN_JRCI_ASUNTO}}', date("d/m/Y", strtotime($f_dictamen_jrci_emitido)), $asunto);
            $asunto = $asunto_modificado;
        }else{
            $asunto = "";
        }

        // $section->addText('Asunto: '.$asunto, array('bold' => true));
        // $section->addTextBreak();
        // $section->addText('Afiliado: '.$nombre_afiliado." ".$tipo_identificacion." ".$num_identificacion, array('bold' => true));

        $table = $section->addTable(array('alignment'=>'center'));

        $table->addRow();

        $cell1 = $table->addCell(10000);

        $asuntoyafiliado = $cell1->addTextRun(array('alignment'=>'left'));
        $asuntoyafiliado->addText('Asunto: ', array('bold' => true));
        $asuntoyafiliado->addText($asunto, array('bold' => true));
        $asuntoyafiliado->addTextBreak();
        $asuntoyafiliado->addText('PACIENTE: ', array('bold' => true));
        $asuntoyafiliado->addText($nombre_afiliado." ".$tipo_identificacion." ".$num_identificacion,array('bold' => true));
        $section->addTextBreak();


        // Configuramos el reemplazo de las etiquetas del cuerpo del comunicado
        $patron1 = '/\{\{\$sustentacion_jrci\}\}/'; // Sustentación Concepto JRCI (Revisión ante concepto de la Junta Regional)
        $patron2 = '/\{\{\$sustentacion_jrci1\}\}/'; // Sustentación Concepto JRCI (Revisión ante recurso de reposición de la Junta Regional)

        $patron3 = '/\{\{\$nombre_afiliado\}\}/';
        $patron4 = '/\{\{\$tipo_identificacion_afiliado\}\}/';
        $patron5 = '/\{\{\$num_identificacion_afiliado\}\}/';
        $patron6 = '/\{\{\$cie10_nombre_cie10_jrci\}\}/';
        $patron7 = '/\{\{\$pcl_jrci\}\}/';
        $patron8 = '/\{\{\$f_estructuracion_jrci\}\}/';
        $patron9 = '/\{\{\$tipos_controversia\}\}/';

        // $cuerpo = str_replace(['<br>', '<br/>', '<br />', '</br>'], '', $cuerpo);

        $cuerpo_modificado = str_replace('HUGO IGNACIO GÓMEZ DAZA', '<b>HUGO IGNACIO GÓMEZ DAZA</b>', $cuerpo);
        $cuerpo_modificado = str_replace('SEGUROS DE VIDA ALFA S.A.', '<b>SEGUROS DE VIDA ALFA S.A.</b>', $cuerpo_modificado);
        $cuerpo_modificado = str_replace('RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN', '<b>RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN</b>', $cuerpo_modificado);
        $cuerpo_modificado = str_replace('RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE APELACIÓN', '<b>RECURSO DE REPOSICIÓN Y EN SUBSIDIO DE APELACIÓN</b>', $cuerpo_modificado);
        $cuerpo_modificado = str_replace('ANEXO:', '<b style="text-align:center;">ANEXO:</b>', $cuerpo_modificado);
        $cuerpo_modificado = str_replace('PÉRDIDA DE CAPACIDAD LABORAL', '<b>PÉRDIDA DE CAPACIDAD LABORAL</b>', $cuerpo_modificado);
        $cuerpo_modificado = str_replace('NOTIFICACIONES:', '<b style="text-align:center;">NOTIFICACIONES:</b>', $cuerpo_modificado);
        
        $cuerpo_modificado = str_replace('</p>', '</p><br></br>', $cuerpo_modificado);
        $cuerpo_modificado = str_replace('<p><br>', ' ', $cuerpo_modificado);

        if (preg_match($patron1, $cuerpo_modificado) && preg_match($patron2, $cuerpo_modificado)) {

            if($request->id_servicio === '12' && preg_match($patron3, $cuerpo_modificado) && preg_match($patron4, $cuerpo_modificado) && preg_match($patron5, $cuerpo_modificado) && preg_match($patron6, $cuerpo_modificado) && preg_match($patron9, $cuerpo_modificado)){
                // Ambos patrones encontrados
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci1}}', $sustentacion_concepto_jrci1, $cuerpo_modificado);

                $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.mb_strtoupper($nombre_afiliado, 'UTF-8').'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipo_identificacion_afiliado}}', '<b>'.strtoupper($tipo_identificacion).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$num_identificacion_afiliado}}', '<b>'.$num_identificacion.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipos_controversia}}', strtolower($string_tipos_controversia), $cuerpo_modificado);
    
                $cuerpo_final = nl2br($cuerpo_modificado);
            }
            else if($request->id_servicio !== '12' && preg_match($patron3, $cuerpo_modificado) && preg_match($patron4, $cuerpo_modificado) && preg_match($patron5, $cuerpo_modificado) && preg_match($patron6, $cuerpo_modificado) && preg_match($patron7, $cuerpo_modificado) && preg_match($patron8, $cuerpo_modificado) && preg_match($patron9, $cuerpo_modificado)) {
                // Ambos patrones encontrados
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci1}}', $sustentacion_concepto_jrci1, $cuerpo_modificado);

                $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.mb_strtoupper($nombre_afiliado, 'UTF-8').'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipo_identificacion_afiliado}}', '<b>'.strtoupper($tipo_identificacion).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$num_identificacion_afiliado}}', '<b>'.$num_identificacion.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$pcl_jrci}}', $porcentaje_pcl_jrci_emitido, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$f_estructuracion_jrci}}', '<b>'.date("d/m/Y", strtotime($f_estructuracion_contro_jrci_emitido)).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipos_controversia}}', '<b>'.$string_tipos_controversia.'</b>', $cuerpo_modificado);
    
                $cuerpo_final = nl2br($cuerpo_modificado);
            }
            else{
                $cuerpo_final = "";
            }
                 
        } elseif (preg_match($patron1, $cuerpo_modificado)) {
            if ($request->id_servicio === '12' && preg_match($patron3, $cuerpo_modificado) && preg_match($patron4, $cuerpo_modificado) && preg_match($patron5, $cuerpo_modificado) && preg_match($patron6, $cuerpo_modificado) && preg_match($patron9, $cuerpo_modificado)) {
                // Ambos patrones encontrados
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);

                $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.mb_strtoupper($nombre_afiliado, 'UTF-8').'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipo_identificacion_afiliado}}', '<b>'.strtoupper($tipo_identificacion).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$num_identificacion_afiliado}}', '<b>'.$num_identificacion.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipos_controversia}}', strtolower($string_tipos_controversia), $cuerpo_modificado);
    
                $cuerpo_final = nl2br($cuerpo_modificado);
            }
            else if($request->id_servicio !== '12' && preg_match($patron3, $cuerpo_modificado) && preg_match($patron4, $cuerpo_modificado) && preg_match($patron5, $cuerpo_modificado) && preg_match($patron6, $cuerpo_modificado) && preg_match($patron7, $cuerpo_modificado) && preg_match($patron8, $cuerpo_modificado) && preg_match($patron9, $cuerpo_modificado)) {
                // Ambos patrones encontrados
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);

                $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.mb_strtoupper($nombre_afiliado, 'UTF-8').'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipo_identificacion_afiliado}}', '<b>'.strtoupper($tipo_identificacion).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$num_identificacion_afiliado}}', '<b>'.$num_identificacion.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$pcl_jrci}}', $porcentaje_pcl_jrci_emitido, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$f_estructuracion_jrci}}', '<b>'.date("d/m/Y", strtotime($f_estructuracion_contro_jrci_emitido)).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipos_controversia}}', '<b>'.$string_tipos_controversia.'</b>', $cuerpo_modificado);
    
                $cuerpo_final = nl2br($cuerpo_modificado);
            }
            else{
                $cuerpo_final = "";

            }
        } elseif (preg_match($patron2, $cuerpo_modificado)) {
            if ($request->id_servicio === '12' && preg_match($patron3, $cuerpo_modificado) && preg_match($patron4, $cuerpo_modificado) && preg_match($patron5, $cuerpo_modificado) && preg_match($patron6, $cuerpo_modificado) && preg_match($patron9, $cuerpo_modificado)) {
                // Ambos patrones encontrados
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci1}}', $sustentacion_concepto_jrci1, $cuerpo_modificado);


                $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.mb_strtoupper($nombre_afiliado, 'UTF-8').'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipo_identificacion_afiliado}}', '<b>'.strtoupper($tipo_identificacion).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$num_identificacion_afiliado}}', '<b>'.$num_identificacion.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipos_controversia}}', strtolower($string_tipos_controversia), $cuerpo_modificado);
    
                $cuerpo_final = nl2br($cuerpo_modificado);
            }
            else if($request->id_servicio !== '12' && preg_match($patron3, $cuerpo_modificado) && preg_match($patron4, $cuerpo_modificado) && preg_match($patron5, $cuerpo_modificado) && preg_match($patron6, $cuerpo_modificado) && preg_match($patron7, $cuerpo_modificado) && preg_match($patron8, $cuerpo_modificado) && preg_match($patron9, $cuerpo_modificado)) {
                // Ambos patrones encontrados
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci1}}', $sustentacion_concepto_jrci1, $cuerpo_modificado);

                $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.mb_strtoupper($nombre_afiliado, 'UTF-8').'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipo_identificacion_afiliado}}', '<b>'.strtoupper($tipo_identificacion).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$num_identificacion_afiliado}}', '<b>'.$num_identificacion.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$pcl_jrci}}', $porcentaje_pcl_jrci_emitido, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$f_estructuracion_jrci}}', '<b>'.date("d/m/Y", strtotime($f_estructuracion_contro_jrci_emitido)).'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$tipos_controversia}}', '<b>'.$string_tipos_controversia.'</b>', $cuerpo_modificado);
    
                $cuerpo_final = nl2br($cuerpo_modificado);
            }
            else{
                $cuerpo_final = "";

            }
        } else {
            // Ninguno de los patrones encontrados
            $cuerpo_final = "";

        }

        $section->addTextBreak();
        Html::addHtml($section, $cuerpo_final, false, false);
        $section->addTextBreak();
        $section->addText('Cordialmente,');
        $section->addTextBreak();

        if($Firma_cliente != "No firma"){
            // Agregar </img> en la imagen de la firma
            $patronetiqueta = '/<img(.*?)>/';
            $Firma_cliente = preg_replace($patronetiqueta, '<img$1></img>', $Firma_cliente);
            $Firma_cliente = str_replace(['<br>', '<br/>', '<br />', '</br>'], '', $Firma_cliente);
            
            // Quitamos el style y agregamos los atributos width y height
            $patronstyle = '/<img[^>]+style="width:\s*([\d.]+)px;\s*height:\s*([\d.]+)px[^"]*"[^>]*>/';
            preg_match($patronstyle, $Firma_cliente, $coincidencias);
            if (count($coincidencias) == 0) {
                $width = "119.075";
                $height = "69.9062";
            }else{
                $width = count($coincidencias)>0 ? $coincidencias[1] : '100px'; // Valor de width
                $height = count($coincidencias)>0 ? $coincidencias[2] : '70px'; // Valor de height
            }
        
            $nuevoStyle = 'width="'.$width.'" height="'.$height.'"';
            $htmlModificado = reemplazarStyleImg($Firma_cliente, $nuevoStyle);
            Html::addHtml($section, $htmlModificado, false, true);
        }else{
            $section->addTextBreak();
            $section->addTextBreak();
            $section->addTextBreak();
        }

        // $section->addTextBreak();
        $section->addTextBreak();
        $section->addText('HUGO IGNACIO GÓMEZ DAZA', array('bold' => true));
        $section->addText('C.C. 80413626');
        $section->addText('Representante Legal');
        $section->addText('Seguros de Vida Alfa S.A.');
        $section->addTextBreak();
        // $section->addText('Elaboró: '.$nombre_usuario, array('bold' => true));
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
        }

        $htmltabla2 .= '</table>';
        Html::addHtml($section, $htmltabla2, false, true);
        $section->addTextBreak();
        $section->addTextBreak();
        // $section->addTextBreak();
        // $section->addText($nombre_afiliado." - ".$tipo_identificacion." ".$num_identificacion." - Siniestro: ".$id_evento, array('bold' => true));

        //Cuadro con la información del siniestro
        $tableCuadro = $section->addTable();

        $tableCuadro->addRow();
        
        $cellCuadro = $tableCuadro->addCell(10000);
        //Estilo del texto del cuadro
        $styleTextCuadro = ['bold' => true,'name' => 'Calibri', 'size' => 8];
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
        // $table = $footer->addTable('myTable');
        // $table->addRow();
        // $cell1 = $table->addCell(80000, ['gridSpan' => 2]);
        // $textRun = $cell1->addTextRun(['alignment' => 'center']);
        // $textRun->addText('Página ');
        // $textRun->addField('PAGE');

        // Generamos el documento y luego se guarda
        $writer = new Word2007($phpWord);
        $nombre_docx = "JUN_DESACUERDO_{$id_asignacion}_{$num_identificacion}_{$nro_radicado}.docx";
        $writer->save(public_path("Documentos_Eventos/{$id_evento}/{$nombre_docx}"));
        $actualizar_nombre_documento = [
            'Nombre_documento' => $nombre_docx
        ];
        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
        ->update($actualizar_nombre_documento);
        /* Inserción del registro de que fue descargado */

        // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        ->select('sice.F_comunicado')
        ->where([
            ['sice.N_radicado', $nro_radicado]
        ])
        ->get();

        $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

        // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
        $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        ->select('Nombre_documento')
        ->where([
            ['Nombre_documento', $nombre_docx],
        ])->get();
        
        if(count($verficar_documento) == 0){

            // Se valida si antes de insertar la info del doc de desacuerdo ya hay un doc de acuerdo
            $nombre_docu_acuerdo = "JUN_ACUERDO_{$id_asignacion}_{$num_identificacion}_{$nro_radicado}.pdf";
            $verificar_docu_acuerdo = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_docu_acuerdo],
            ])->get();

            // Si no existe info del documento de acuerdo, inserta la info del documento de desacuerdo
            // De lo contrario hace una actualización de la info
            if (count($verificar_docu_acuerdo) == 0) {
                $info_descarga_documento = [
                    'Id_Asignacion' => $id_asignacion,
                    'Id_proceso' => $id_proceso,
                    'Id_servicio' => $id_servicio,
                    'ID_evento' => $id_evento,
                    'Nombre_documento' => $nombre_docx,
                    'N_radicado_documento' => $nro_radicado,
                    'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                    'F_descarga_documento' => $date,
                    'Nombre_usuario' => $nombre_usuario,
                ];
                
                sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
            }else{
                $info_descarga_documento = [
                    'Id_Asignacion' => $id_asignacion,
                    'Id_proceso' => $id_proceso,
                    'Id_servicio' => $id_servicio,
                    'ID_evento' => $id_evento,
                    'Nombre_documento' => $nombre_docx,
                    'N_radicado_documento' => $nro_radicado,
                    'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                    'F_descarga_documento' => $date,
                    'Nombre_usuario' => $nombre_usuario,
                ];
                
                sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->where([
                    ['Id_Asignacion', $id_asignacion],
                    ['N_radicado_documento', $nro_radicado],
                    ['ID_evento' => $id_evento],
                ])
                ->update($info_descarga_documento);
            }

        }

        return response()->download(public_path("Documentos_Eventos/{$id_evento}/{$nombre_docx}"));
    }

    /* Proforma Acuerdo */
    public function DescargarProformaPronunDictaAcuerdo(Request $request){
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        /* Captura de variables que vienen del ajax */
        $id_comite_inter = $request->id_comite_inter;
        $id_cliente = $request->id_cliente;
        $id_asignacion = $request->id_asignacion;
        $id_proceso = $request->id_proceso;
        $Id_comunicado = $request->id_comunicado;
        $id_servicio = $request->id_servicio;
        $nro_radicado = $request->nro_radicado;
        $tipo_identificacion = $request->tipo_identificacion;
        $num_identificacion = $request->num_identificacion;
        $id_evento = $request->id_evento;
        $id_Jrci_califi_invalidez = $request->id_Jrci_califi_invalidez;
        $nro_dictamen = $request->nro_dictamen;
        $nombre_afiliado = $request->nombre_afiliado;
        $f_dictamen_jrci_emitido = $request->f_dictamen_jrci_emitido;
        $porcentaje_pcl_jrci_emitido = $request->porcentaje_pcl_jrci_emitido;
        $origen_jrci_emitido = $request->origen_jrci_emitido;
        $f_estructuracion_contro_jrci_emitido = $request->f_estructuracion_contro_jrci_emitido;
        $manual_de_califi_jrci_emitido = $request->manual_de_califi_jrci_emitido;
        $sustentacion_concepto_jrci = "<b>".$request->sustentacion_concepto_jrci."</b>";
        $sustentacion_concepto_jrci1 = "<b>".$request->sustentacion_concepto_jrci1."</b>";
        $copia_afiliado = $request->copia_afiliado;
        $copia_empleador = $request->copia_empleador;
        $copia_eps = $request->copia_eps;
        $copia_afp = $request->copia_afp;
        $copia_arl = $request->copia_arl;
        $asunto = strtoupper($request->asunto);
        $cuerpo = $request->cuerpo;
        $firmar = $request->firmar;
        $N_siniestro = $request->N_siniestro;

        /* Creación de las variables faltantes que no están en el ajax */

        // Validación información Destinatario Principal
        $datos_para_destinatario_principal = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([['Id_com_inter', $id_comite_inter]])->get();

        //Fecha de sustentacion antes la JRCI
        $fecha_sustent_jrci = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$id_evento],['Id_Asignacion',$id_asignacion]])
        ->value('F_sustenta_jrci');

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
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Departamento', '=', 'sldm2.Id_departamento')
                    ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_municipio as Nombre_ciudad', 'sldm2.Nombre_departamento')
                    ->where([
                        ['sie.Id_Entidad', $id_entidad],
                        ['sie.IdTipo_entidad', $tipo_destinatario]
                    ])->get();

                    $nombre_junta = $datos_entidad[0]->Nombre_entidad;
                    $direccion_junta = $datos_entidad[0]->Direccion;
                    $telefono_junta = $datos_entidad[0]->Telefonos;
                    $ciudad_junta = $datos_entidad[0]->Nombre_ciudad;
                    $departamento_junta = $datos_entidad[0]->Nombre_ciudad;

                break;
                
                // Si escoge la opción Afiliado: Se sacan los datos del destinatario principal pero del afiliado
                case ($tipo_destinatario == 4):
                    $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('siae.Nombre_afiliado','siae.Direccion','siae.Telefono_contacto','sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
                    ->where([['siae.ID_evento','=', $id_evento]])
                    ->get();
        
                    $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);
        
                    $nombre_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_afiliado"];;
                    $direccion_junta = $array_datos_municipio_ciudad_afiliado[0]["Direccion"];
                    $telefono_junta = $array_datos_municipio_ciudad_afiliado[0]["Telefono_contacto"];
                    $ciudad_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"];
                    $departamento_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_departamento"];

                break;

                // Si escoge la opción Empleador: Se sacan los datos del destinatario principal pero del Empleador
                case ($tipo_destinatario == 5):
                    $datos_entidad_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_municipio', '=', 'sldm.Id_municipios')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_departamento', '=', 'sldm2.Id_departamento')
                    ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_municipio as Nombre_ciudad', 'sldm2.Nombre_departamento')
                    ->where([['sile.ID_evento', $id_evento]])->get();

                    $nombre_junta = $datos_entidad_empleador[0]->Empresa;
                    $direccion_junta = $datos_entidad_empleador[0]->Direccion;
                    $telefono_junta = $datos_entidad_empleador[0]->Telefono_empresa;
                    $ciudad_junta = $datos_entidad_empleador[0]->Nombre_ciudad;
                    $departamento_junta = $datos_entidad_empleador[0]->Nombre_departamento;

                break;
                
                // Si escoge la opción Otro: se sacan los datos del destinatario de la tabla sigmel_informacion_comite_interdisciplinario_eventos
                case ($tipo_destinatario == 8):
                    // aqui validamos si los datos no vienen vacios, debido a que si  vienen vacios, toca marcar ''
                    if (!empty($array_datos_para_destinatario_principal[0]["Nombre_destinatario"])) {
                        $nombre_junta = $array_datos_para_destinatario_principal[0]["Nombre_destinatario"];
                    } else {
                        $nombre_junta = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Direccion_destinatario"])) {
                        $direccion_junta = $array_datos_para_destinatario_principal[0]["Direccion_destinatario"];
                    } else {
                        $direccion_junta = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Telefono_destinatario"])) {
                        $telefono_junta = $array_datos_para_destinatario_principal[0]["Telefono_destinatario"];
                    } else {
                        $telefono_junta = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Ciudad_destinatario"])) {
                        $ciudad_junta = $array_datos_para_destinatario_principal[0]["Ciudad_destinatario"];
                    } else {
                        $ciudad_junta = "";
                    };

                    if (!empty($array_datos_para_destinatario_principal[0]["Departamento_destinatario"])) {
                        $departamento_junta = $array_datos_para_destinatario_principal[0]["Departamento_destinatario"];
                    } else {
                        $departamento_junta = "";
                    };

                break;

                default:
                    # code...
                break;
            }
        }else{
            // Datos Junta regional
            $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
            ->where([['sie.Id_Entidad', $id_Jrci_califi_invalidez]])->get();

            $array_datos_junta_regional = json_decode(json_encode($datos_junta_regional), true);

            if(count($array_datos_junta_regional)>0){
                $nombre_junta = $request->nombre_junta_regional;
                $direccion_junta = $array_datos_junta_regional[0]["Direccion"];
                $telefono_junta = $array_datos_junta_regional[0]["Telefonos"];
                $ciudad_junta = $array_datos_junta_regional[0]["Nombre_ciudad"];
                $departamento_junta = $array_datos_junta_regional[0]["Nombre_departamento"];
            }else {
                    $nombre_junta = "";
                    $direccion_junta = "";
                    $telefono_junta = "";
                    $ciudad_junta = "";
                    $departamento_junta = "";
            }

            // $datos_municipio_ciudad_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            //         ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
            //         ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
            //         ->select('siae.Nombre_afiliado','siae.Direccion','siae.Telefono_contacto','sldm.Nombre_departamento', 'sldm2.Nombre_municipio')
            //         ->where([['siae.ID_evento','=', $id_evento]])
            //         ->get();
        
            // $array_datos_municipio_ciudad_afiliado = json_decode(json_encode($datos_municipio_ciudad_afiliado), true);

            // if (count($array_datos_municipio_ciudad_afiliado) > 0) {
            //     $nombre_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_afiliado"];;
            //     $direccion_junta = $array_datos_municipio_ciudad_afiliado[0]["Direccion"];
            //     $telefono_junta = $array_datos_municipio_ciudad_afiliado[0]["Telefono_contacto"];
            //     $ciudad_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_municipio"];
            //     $departamento_junta = $array_datos_municipio_ciudad_afiliado[0]["Nombre_departamento"];
            // } else {
            //     $nombre_junta = "";
            //     $direccion_junta = "";
            //     $telefono_junta = "";
            //     $ciudad_junta = "";
            //     $departamento_junta = "";
            // }
            
        }

        // Traer datos CIE10 (Diagnóstico motivo de calificación) jrci
        $datos_diagnostico_motcalifi_emitido_jrci=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
        ->where([['side.ID_evento',$id_evento],
            ['side.Id_proceso',$id_proceso],
            ['side.Item_servicio', '=', 'Emitido JRCI'],
            ['side.Estado', '=', 'Activo']
        ])->get(); 

        $array_datos_diagnostico_motcalifi_emitido_jrci = json_decode(json_encode($datos_diagnostico_motcalifi_emitido_jrci), true);

        if (count($array_datos_diagnostico_motcalifi_emitido_jrci) > 0) {

            $diagnosticos_cie10_jrci = array();
    
            for ($i=0; $i < count($array_datos_diagnostico_motcalifi_emitido_jrci); $i++) { 
                $dx_concatenados = $array_datos_diagnostico_motcalifi_emitido_jrci[$i]["Codigo"]. " - ".$array_datos_diagnostico_motcalifi_emitido_jrci[$i]["Nombre_CIE10"];
                array_push($diagnosticos_cie10_jrci, $dx_concatenados);
            }
    
            // Contar la cantidad de elementos en el array
            $totalElementos = count($diagnosticos_cie10_jrci);
    
            // Inicializar la cadena de resultado
            $string_diagnosticos_cie10_jrci = '';
     
            // Recorrer el array
            foreach ($diagnosticos_cie10_jrci as $indice => $elemento) {
                // Verificar si es el último elemento
                if ($indice == $totalElementos - 1) {
                    // Si es el último, añadir solo el elemento sin coma
                    $string_diagnosticos_cie10_jrci .= $elemento;
                } elseif ($indice == $totalElementos - 2) {
                    // Si es el antepenúltimo, añadir "y" en lugar de ","
                    $string_diagnosticos_cie10_jrci .= $elemento . " y ";
                } else {
                    // Para cualquier otro elemento, añadir ","
                    $string_diagnosticos_cie10_jrci .= $elemento . ", ";
                }
            };
            $string_diagnosticos_cie10_jrci = "<b>".$string_diagnosticos_cie10_jrci."</b>";
            
        } else {
            $string_diagnosticos_cie10_jrci = "";
        }

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
        if(isset($copia_afiliado)){
            
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
            ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sile.Email', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['sile.Nro_identificacion', $num_identificacion],['sile.ID_evento', $id_evento]])
            ->get();

            $nombre_empleador = $datos_empleador[0]->Empresa;
            $direccion_empleador = $datos_empleador[0]->Direccion;
            $email_empleador = $datos_empleador[0]->Email;
            $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
            $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
            $municipio_empleador = $datos_empleador[0]->Nombre_municipio;

            $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$email_empleador."; ".$telefono_empleador."; ".$ciudad_empleador."; ".$municipio_empleador.".";   
        }

        if (isset($copia_eps)) {
            $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Emails as Email','sie.Telefonos', 'sie.Otros_Telefonos', 
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

            $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps ."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;
        }

        if (isset($copia_afp)) {
            $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Emails as Email','sie.Telefonos', 'sie.Otros_Telefonos',
            'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $id_evento]])
            ->get();

            $nombre_afp = $datos_afp[0]->Nombre_afp;
            $direccion_afp = $datos_afp[0]->Direccion;
            if ($datos_afp[0]->Otros_Telefonos != "") {
                $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
            } else {
                $telefonos_afp = $datos_afp[0]->Telefonos;
            }
            $email_afp = $datos_afp[0]->Email;
            $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
            $minucipio_afp = $datos_afp[0]->Nombre_municipio;

            $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$email_afp."; ".$telefonos_afp."; ".$ciudad_afp."; ".$minucipio_afp;
        }

        if(isset($copia_arl)){
            $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
            ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
            ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Emails as Email','sie.Telefonos', 'sie.Otros_Telefonos',
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

        /* Validación Firma Cliente */
        $validarFirma = isset($firmar) ? 'Firmar' : 'Sin Firma';
        
        if ($validarFirma == "Firmar") {
            $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente')
            ->where('Id_cliente', $id_cliente)->limit(1)->get();

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
        ->where([['Id_cliente', $id_cliente]])
        ->limit(1)->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
            $ruta_logo = "/logos_clientes/{$id_cliente}/{$logo_header}";
        } else {
            $logo_header = "Sin logo";
            $ruta_logo = "";
        }

        //Footer image
        $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
        ->select('Footer_cliente')
        ->where([['Id_cliente', $id_cliente]])
        ->limit(1)->get();

        if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
            $footer = $footer_imagen[0]->Footer_cliente;
        } else {
            $footer = null;
        } 

        /* Extraemos los datos del footer */
        // $datos_footer = sigmel_clientes::on('sigmel_gestiones')
        // ->select('footer_dato_1', 'footer_dato_2', 'footer_dato_3', 'footer_dato_4', 'footer_dato_5')
        // ->where('Id_cliente', $id_cliente)->get();

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

        $datos_finales_proforma_acuerdo = [
            'id_cliente' => $id_cliente,
            'logo_header' => $logo_header,
            'fecha_sustentacion_jrci' => fechaFormateada($fecha_sustent_jrci ? $fecha_sustent_jrci : $date),
            'nombre_junta' => $nombre_junta,
            'direccion_junta' => $direccion_junta,
            'telefono_junta' => $telefono_junta,
            'ciudad_junta' => $ciudad_junta,
            'departamento_junta' => $departamento_junta,
            'nro_radicado'=> $nro_radicado,
            'tipo_identificacion'=> $tipo_identificacion,
            'num_identificacion'=> $num_identificacion,
            'id_evento'=> $id_evento,
            'asunto' => $asunto,
            'cuerpo' => $cuerpo,
            'nro_dictamen' => $nro_dictamen,
            'nombre_afiliado' => $nombre_afiliado,
            'f_dictamen_jrci_emitido' => $f_dictamen_jrci_emitido,
            'porcentaje_pcl_jrci_emitido' => $porcentaje_pcl_jrci_emitido,
            'origen_jrci_emitido' => $origen_jrci_emitido,
            'f_estructuracion_contro_jrci_emitido' => $f_estructuracion_contro_jrci_emitido,
            'manual_de_califi_jrci_emitido' => $manual_de_califi_jrci_emitido,
            'sustentacion_concepto_jrci' => $sustentacion_concepto_jrci,
            'sustentacion_concepto_jrci1' => $sustentacion_concepto_jrci1,
            'Agregar_copia' => $Agregar_copias,
            'string_diagnosticos_cie10_jrci' => $string_diagnosticos_cie10_jrci,
            'Firma_cliente' => $Firma_cliente,
            'nombre_usuario' => $nombre_usuario,
            'footer' => $footer,
            'N_siniestro' => $N_siniestro,
            'id_servicio' => $id_servicio
            // 'footer_dato_1' => $footer_dato_1,
            // 'footer_dato_2' => $footer_dato_2,
            // 'footer_dato_3' => $footer_dato_3,
            // 'footer_dato_4' => $footer_dato_4,
            // 'footer_dato_5' => $footer_dato_5,
        ];

        // print_r($datos_finales_proforma_acuerdo);

        /* Creación del pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/Juntas/pronunciamiento_frente_dictamen', $datos_finales_proforma_acuerdo);
        
        $nombre_pdf = "JUN_ACUERDO_{$id_asignacion}_{$num_identificacion}_{$nro_radicado}.pdf";

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

        // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        // $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        // ->select('sice.F_comunicado')
        // ->where([
        //     ['sice.N_radicado', $nro_radicado]
        // ])
        // ->get();

        // $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

        // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
        // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        // ->select('Nombre_documento')
        // ->where([
        //     ['Nombre_documento', $nombre_pdf],
        // ])->get();
        
        // if(count($verficar_documento) == 0){

        //     // Se valida si antes de insertar la info del doc de acuerdo ya hay un doc de desacuerdo
        //     $nombre_docu_desacuerdo = "JUN_DESACUERDO_{$id_asignacion}_{$num_identificacion}_{$nro_radicado}.docx";
        //     $verificar_docu_desacuerdo = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        //     ->select('Nombre_documento')
        //     ->where([
        //         ['Nombre_documento', $nombre_docu_desacuerdo],
        //     ])->get();

        //     // Si no existe info del documento de desacuerdo, inserta la info del documento de acuerdo
        //     // De lo contrario hace una actualización de la info
        //     if (count($verificar_docu_desacuerdo) == 0) {
        //         $info_descarga_documento = [
        //             'Id_Asignacion' => $id_asignacion,
        //             'Id_proceso' => $id_proceso,
        //             'Id_servicio' => $id_servicio,
        //             'ID_evento' => $id_evento,
        //             'Nombre_documento' => $nombre_pdf,
        //             'N_radicado_documento' => $nro_radicado,
        //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
        //             'F_descarga_documento' => $date,
        //             'Nombre_usuario' => $nombre_usuario,
        //         ];
                
        //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        //     }else{
        //         $info_descarga_documento = [
        //             'Id_Asignacion' => $id_asignacion,
        //             'Id_proceso' => $id_proceso,
        //             'Id_servicio' => $id_servicio,
        //             'ID_evento' => $id_evento,
        //             'Nombre_documento' => $nombre_pdf,
        //             'N_radicado_documento' => $nro_radicado,
        //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
        //             'F_descarga_documento' => $date,
        //             'Nombre_usuario' => $nombre_usuario,
        //         ];
                
        //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        //         ->where([
        //             ['Id_Asignacion', $id_asignacion],
        //             ['N_radicado_documento', $nro_radicado],
        //             ['ID_evento', $id_evento]
        //         ])
        //         ->update($info_descarga_documento);
        //     }
        // }

        // return $dompdf->stream($nombre_pdf);
        return $pdf->download($nombre_pdf);

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