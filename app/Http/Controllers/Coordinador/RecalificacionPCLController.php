<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\sigmel_informacion_pericial_eventos;
use App\Models\sigmel_informacion_decreto_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\cndatos_eventos;
use App\Models\sigmel_campimetria_visuales;
use App\Models\sigmel_clientes;
use App\Models\sigmel_info_campimetria_ojo_der_eventos;
use App\Models\sigmel_info_campimetria_ojo_derre_eventos;
use App\Models\sigmel_info_campimetria_ojo_izq_eventos;
use App\Models\sigmel_info_campimetria_ojo_izqre_eventos;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_agudeza_auditiva_eventos;
use App\Models\sigmel_informacion_agudeza_visual_eventos;
use App\Models\sigmel_informacion_agudeza_visualre_eventos;
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_deficiencias_alteraciones_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_informacion_examenes_interconsultas_eventos;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_informacion_laboralmente_activo_eventos;
use App\Models\sigmel_informacion_libro2_libro3_eventos;
use App\Models\sigmel_informacion_rol_ocupacional_eventos;
use App\Models\sigmel_lista_califi_decretos;
use App\Models\sigmel_lista_cie_diagnosticos;
use App\Models\sigmel_lista_clases_decretos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_lista_regional_juntas;
use App\Models\sigmel_lista_solicitantes;
use App\Models\sigmel_lista_tablas_1507_decretos;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_registro_descarga_documentos;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class RecalificacionPCLController extends Controller
{
    public function mostrarVistaRecalificacionPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        // validar si las variables Evento y Asignacion vienen desde el modulo princinpal o desde el modulo gestion inicial edicion
        if (!empty($request->Id_asignacion_pcl)) {
            $Id_evento_recali=$request->Id_evento_pcl;
            $Id_asignacion_recali = $request->Id_asignacion_pcl;      
            $Id_servicioRecalifi = $request->Idservicio;
        }else{
            $Id_evento_recali=$request->Id_evento_recali;
            $Id_asignacion_recali = $request->Id_asignacion_recali; 
            $Id_servicioRecalifi = $request->Id_servicio_recali;
        }

        if ($Id_servicioRecalifi == 7) {            
            $Id_RecalificacionId_Revisionpension = 8;
        } else {            
            $Id_RecalificacionId_Revisionpension = 7;
        }

        $Id_proceso_recali = 2;
        $Id_servicioCalifi= 6;

        // validar si con el evento hay una calificacion tecnica
        $validar_evento_CalifiTecnica = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->select('ID_Evento','Id_Asignacion', 'Id_proceso', 'Id_servicio')
        ->where([['ID_Evento',$Id_evento_recali],['Id_servicio',$Id_servicioCalifi], ['Id_proceso',$Id_proceso_recali]])->get();

        // Obtener el minimo y el maximo id de asignacion y estado del decreto saber el orden de la gestion de los id de asignacion

        $eventoAsigancionMin_Recalifi = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
        ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig',
        'siae.Id_proceso' , 'siae.Id_servicio', 'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_proceso', $Id_proceso_recali]])
        ->whereIn('siae.Id_servicio', [$Id_servicioRecalifi, $Id_RecalificacionId_Revisionpension])
        ->groupBy('side.ID_Evento', 'side.Id_Asignacion', 'siae.Id_Asignacion', 'siae.Id_proceso', 'siae.Id_servicio', 
        'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->orderBy('side.Id_Asignacion', 'asc')
        ->limit(1)
        //->get();
        ->min('siae.Id_Asignacion');

        $eventoAsigancion_Recalifi = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
        ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig',
        'siae.Id_proceso' , 'siae.Id_servicio', 'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->where([
            ['side.ID_Evento',$Id_evento_recali], 
            ['siae.Id_proceso', $Id_proceso_recali],
            ['side.Estado_decreto', 'Cerrado']
        ])
        ->whereIn('siae.Id_servicio', [$Id_servicioRecalifi, $Id_RecalificacionId_Revisionpension])
        ->groupBy('side.ID_Evento', 'side.Id_Asignacion', 'siae.Id_Asignacion', 'siae.Id_proceso', 'siae.Id_servicio', 
        'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->orderBy('side.Id_Asignacion', 'desc')
        ->limit(1)
        //->get();
        ->max('siae.Id_Asignacion');

        // Validacion del estado del decreto de la recalificacion nueva        
        $eventoAsigancion_Recalifi_estadoDecreto = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
        ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig',
        'siae.Id_proceso' , 'siae.Id_servicio', 'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->where([
            ['side.ID_Evento',$Id_evento_recali],
            ['siae.Id_proceso', $Id_proceso_recali]
        ])
        ->whereIn('siae.Id_servicio', [$Id_servicioRecalifi, $Id_RecalificacionId_Revisionpension])
        ->groupBy('side.ID_Evento', 'side.Id_Asignacion', 'siae.Id_Asignacion', 'siae.Id_proceso', 'siae.Id_servicio', 
        'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->orderBy('side.Id_Asignacion', 'desc')
        ->limit(1)
        ->get();
        //->max('siae.Id_Asignacion'); 
                
        // Validar estado del decreto de la recalificacion anterior o reciente 
        $validar_estado_decreto = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
        ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
        'side.Porcentaje_pcl', 'side.PCL_anterior', 'side.Estado_decreto')
        ->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_servicio', $Id_servicioCalifi]])->get(); 
        
        // Validar PCl anterior de la Recalficacion
        $eventoAsigancionMax_RecaRecali = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.ID_evento', '=', 'side.ID_evento')
        ->select('side.Id_Asignacion')
        ->where([['side.Id_Asignacion', '<', DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos  as siae')->select('siae.Id_Asignacion')->max('siae.Id_Asignacion')], 
                ['side.ID_Evento', $Id_evento_recali], 
                ['side.Id_proceso', $Id_proceso_recali]
        ])        
        ->whereIn('siae.Id_servicio', [$Id_servicioRecalifi, $Id_RecalificacionId_Revisionpension])     
        ->max('side.Id_Asignacion');
        // Validar si se trae el porcentaje de pcl actual o el porcentaje de pcl anterior segun el id asignacion        
        if(!empty($eventoAsigancionMax_RecaRecali) && $eventoAsigancionMax_RecaRecali < $Id_asignacion_recali){            
            // echo 'if';       
            // echo '<hr>';
            // echo $eventoAsigancionMax_RecaRecali;
            $eventoAsigancion_RecalifiPCL = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
            ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
            'side.Porcentaje_pcl', 'side.Estado_decreto')
            ->where([
                ['side.ID_Evento',$Id_evento_recali], 
                ['side.Id_Asignacion', $eventoAsigancionMax_RecaRecali]
            ])->get();  
        }elseif(!empty($eventoAsigancionMax_RecaRecali) && $eventoAsigancionMax_RecaRecali > $Id_asignacion_recali){            
            // echo 'elseif';       
            // echo '<hr>';
            // echo $eventoAsigancionMax_RecaRecali;
            $eventoAsigancion_RecalifiPCL = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
            ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
            'side.PCL_anterior', 'side.Estado_decreto')
            ->where([
                ['side.ID_Evento',$Id_evento_recali], 
                ['siae.Id_servicio', $Id_servicioRecalifi], 
                ['side.Id_Asignacion', $Id_asignacion_recali]
            ])->get();         
        }elseif($eventoAsigancionMax_RecaRecali == $Id_asignacion_recali){      
            // echo 'esta aqui else';
            // echo '<hr>';
            // echo $eventoAsigancionMax_RecaRecali;
            $eventoAsigancion_RecalifiPCL = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
            ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig',
            'siae.Id_proceso' , 'siae.Id_servicio', 'side.PCL_anterior', 'side.Estado_decreto')
            ->where([
                ['side.ID_Evento',$Id_evento_recali],
                ['siae.Id_servicio', $Id_servicioRecalifi], 
                ['siae.Id_proceso', $Id_proceso_recali],
                ['side.Id_Asignacion', $Id_asignacion_recali]
            ])
            ->groupBy('side.ID_Evento', 'side.Id_Asignacion', 'siae.Id_Asignacion', 'siae.Id_proceso', 'siae.Id_servicio', 
            'side.PCL_anterior', 'side.Estado_decreto')
            ->orderBy('side.Id_Asignacion', 'desc')
            ->limit(1)
            ->get();
        }    

        // traer todos los datos del evento segun el id de asignacion
        $array_datos_RecalificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($Id_asignacion_recali));

        /* Traer datos de la AFP de Conocimiento */
        $info_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
        ->select('siae.Entidad_conocimiento')
        ->where([['siae.ID_evento', $Id_evento_recali]])
        ->get();

        // Condicional IF para Recalificacion sobre Recalificacion y Else para Recalifacion sobre Calificacion tecnica

        if ($eventoAsigancionMin_Recalifi != $Id_asignacion_recali && !empty($eventoAsigancionMin_Recalifi)) {             
            if(!empty($eventoAsigancion_Recalifi)){
                // IF para captura de datos de la recalificacion anterior o reciente           
                if ($eventoAsigancion_Recalifi >= 1) {                                                        
                    
                    $datos_decreto =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
                    ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
                    'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
                    ->where([['side.ID_Evento',$Id_evento_recali], ['side.Id_Asignacion',$eventoAsigancion_Recalifi]])->get(); 
                    
                    $array_info_decreto_evento = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
                    ->where([
                        ['ID_Evento', $Id_evento_recali],['Id_Asignacion', $eventoAsigancion_Recalifi]
                    ])
                    ->get();
                    
                    $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_Asignacion', $eventoAsigancion_Recalifi],
                        ['Id_proceso',$Id_proceso_recali],
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();
            
                    $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                    ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                    'slp.Nombre_parametro', 'side.Principal', 'side.Deficiencia_motivo_califi_condiciones','slp2.Nombre_parametro as Nombre_parametro_lateralidad'
                    )->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion', $eventoAsigancion_Recalifi], ['Id_proceso',$Id_proceso_recali], ['side.Estado_Recalificacion', '=', 'Activo']])->get();
        
                    $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                    ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                    'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                    'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Dominancia', 'sidae.Deficiencia', 
                    'sidae.Total_deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                    ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion', $eventoAsigancion_Recalifi], ['sidae.Estado_Recalificacion', '=', 'Activo']])
                    ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
                    ->get();
        
                    $array_agudeza_Auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_Asignacion',$eventoAsigancion_Recalifi],
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();
            
                    $hay_agudeza_visual = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                    ->where([['ID_evento_re', $Id_evento_recali], ['Id_Asignacion_re',$eventoAsigancion_Recalifi], ['Estado_Recalificacion', '=', 'Activo']])->get();
                        
                    $array_laboralmente_Activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_Asignacion',$eventoAsigancion_Recalifi],
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();
        
                    $array_rol_ocupacional =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_rol_ocupacional_eventos as siroe')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siroe.Poblacion_calificar')
                    ->select('siroe.Id_Rol_ocupacional', 'siroe.ID_evento', 'siroe.Id_Asignacion', 'siroe.Id_proceso', 'siroe.Poblacion_calificar', 
                    'slp.Nombre_parametro', 'siroe.Motriz_postura_simetrica', 'siroe.Motriz_actividad_espontanea', 'siroe.Motriz_sujeta_cabeza',
                    'siroe.Motriz_sentarse_apoyo', 'siroe.Motriz_gira_sobre_mismo', 'siroe.Motriz_sentanser_sin_apoyo', 'siroe.Motriz_pasa_tumbado_sentado',
                    'siroe.Motriz_pararse_apoyo', 'siroe.Motriz_pasos_apoyo', 'siroe.Motriz_pararse_sin_apoyo', 'siroe.Motriz_anda_solo', 'siroe.Motriz_empujar_pelota_pies',
                    'siroe.Motriz_andar_obstaculos', 'siroe.Adaptativa_succiona', 'siroe.Adaptativa_fija_mirada', 'siroe.Adaptativa_sigue_trayectoria_objeto',
                    'siroe.Adaptativa_sostiene_sonajero', 'siroe.Adaptativa_tiende_mano_hacia_objeto', 'siroe.Adaptativa_sostiene_objeto_manos',
                    'siroe.Adaptativa_abre_cajones', 'siroe.Adaptativa_bebe_solo', 'siroe.Adaptativa_quitar_prenda_vestir', 
                    'siroe.Adaptativa_reconoce_funcion_espacios_casa', 'siroe.Adaptativa_imita_trazo_lapiz', 'siroe.Adaptativa_abre_puerta',
                    'siroe.Total_criterios_desarrollo', 'siroe.Juego_estudio_clase', 'siroe.Total_rol_estudio_clase', 'siroe.Adultos_mayores',
                    'siroe.Total_rol_adultos_ayores', 'siroe.Nombre_usuario', 'siroe.F_registro')
                    ->where([['siroe.ID_evento',$Id_evento_recali], ['siroe.Id_Asignacion',$eventoAsigancion_Recalifi], ['Estado_Recalificacion', 'Activo']])->get();
        
                    $array_libros_2_3 = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_Asignacion',$eventoAsigancion_Recalifi],
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();
            
                    $array_dictamen_pericial =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
                    ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Monto_indemnizacion', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                    'side.F_evento', 'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.N_siniestro', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                    'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                    'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia')
                    ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion', $eventoAsigancion_Recalifi]])->get();
                    
                } 
                // Inicia captura de datos de la recalificacion actual o nueva
                $datos_decretore =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
                ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
                ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
                'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
                ->where([['side.ID_Evento',$Id_evento_recali], ['side.Id_Asignacion', $Id_asignacion_recali]])->get();
                
                // Obtener el último consecutivo de la base de datos
                $consecutivoDictamen = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->max('Numero_dictamen');
        
                if ($consecutivoDictamen > 0) {
                    $numero_consecutivo = $consecutivoDictamen + 1;
                }else{
                    $numero_consecutivo = 0000000 + 1;
                }
                // Formatear el número consecutivo a 7 dígitos
                $numero_consecutivo = str_pad($numero_consecutivo, 7, "0", STR_PAD_LEFT); 
                   
                $array_info_decreto_evento_re = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
                ->where([
                    ['ID_Evento', $Id_evento_recali],['Id_Asignacion', $Id_asignacion_recali]
                ])
                ->get();
        
                if (!empty($array_info_decreto_evento_re[0]->Id_Asignacion)) {
        
                    $Historiaclínicacompleta  = "Historia clínica completa";
                    $Exámenespreocupacionales = "Exámenes preocupacionales";
                    $Epicrisis = "Epicrisis";
                    $Exámenesperiódicosocupacionales  = "Exámenes periódicos ocupacionales";
                    $Exámenesparaclinicos  = "Exámenes paraclinicos";
                    $ExámenesPostocupacionales  = "Exámenes Post-ocupacionales";
                    $Conceptosdesaludocupacional   = "Conceptos de salud ocupacional";
        
                    $arraytotalRealcionDocumentos = [
                        'Historia clínica completa',
                        'Exámenes preocupacionales',
                        'Epicrisis',
                        'Exámenes periódicos ocupacionales',
                        'Exámenes paraclinicos',
                        'Exámenes Post-ocupacionales',
                        'Conceptos de salud ocupacional',
                    ];
        
                    foreach ($arraytotalRealcionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor); 
                    }
                    $relacionDocuementos = $array_info_decreto_evento_re[0]->Relacion_documentos;
                    $separaRelacionDocumentos = explode(", ",$relacionDocuementos);  
                    
                    foreach ($separaRelacionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor);     
                    }
                    foreach ($arraytotalRealcionDocumentos as $index => $value) {
                        if (!in_array($value, $separaRelacionDocumentos)) {
                            ${$value} = "vacio";
                        }
                    }                       
                }elseif(!empty($array_info_decreto_evento[0]->Id_Asignacion)){
                    $Historiaclínicacompleta  = "Historia clínica completa";
                    $Exámenespreocupacionales = "Exámenes preocupacionales";
                    $Epicrisis = "Epicrisis";
                    $Exámenesperiódicosocupacionales  = "Exámenes periódicos ocupacionales";
                    $Exámenesparaclinicos  = "Exámenes paraclinicos";
                    $ExámenesPostocupacionales  = "Exámenes Post-ocupacionales";
                    $Conceptosdesaludocupacional   = "Conceptos de salud ocupacional";
        
                    $arraytotalRealcionDocumentos = [
                        'Historia clínica completa',
                        'Exámenes preocupacionales',
                        'Epicrisis',
                        'Exámenes periódicos ocupacionales',
                        'Exámenes paraclinicos',
                        'Exámenes Post-ocupacionales',
                        'Conceptos de salud ocupacional',
                    ];
        
                    foreach ($arraytotalRealcionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor); 
                    }
                    $relacionDocuementos = $array_info_decreto_evento[0]->Relacion_documentos;
                    $separaRelacionDocumentos = explode(", ",$relacionDocuementos);  
                    
                    foreach ($separaRelacionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor);     
                    }
                    foreach ($arraytotalRealcionDocumentos as $index => $value) {
                        if (!in_array($value, $separaRelacionDocumentos)) {
                            ${$value} = "vacio";
                        }
                    }
                }else{
                    list(
                        $Historiaclínicacompleta, 
                        $Exámenespreocupacionales, 
                        $Epicrisis, 
                        $Exámenesperiódicosocupacionales, 
                        $Exámenesparaclinicos, 
                        $ExámenesPostocupacionales, 
                        $Conceptosdesaludocupacional
                    ) = array_fill(0, 7, 'vacio');
                }
                $array_datos_relacion_documentos = [
                    'Historiaclinicacompleta' => $Historiaclínicacompleta, 
                    'Examenespreocupacionales' => $Exámenespreocupacionales, 
                    'Epicrisis' => $Epicrisis, 
                    'Examenesperiodicosocupacionales' => $Exámenesperiódicosocupacionales, 
                    'Examenesparaclinicos' => $Exámenesparaclinicos, 
                    'ExamenesPostocupacionales' => $ExámenesPostocupacionales, 
                    'Conceptosdesaludocupacion' => $Conceptosdesaludocupacional,
                ];
                       
                //Traer Motivo de solicitud,Dominancia actual
                $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
                ->select('Id_motivo_solicitud','Nombre_solicitud','Id_dominancia','Nombre_dominancia')
                ->where([
                    ['ID_evento', '=', $Id_evento_recali]
                ])
                ->get();
                
                $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
                ->select('Nombre_apoderado','Nro_identificacion_apoderado')
                ->where([
                    ['ID_evento', '=', $Id_evento_recali]
                ])
                ->get(); 
                
                $array_datos_examenes_interconsultasre = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Id_proceso',$Id_proceso_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
        
                $array_datos_diagnostico_motcalifire =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                'slp.Nombre_parametro', 'side.Principal', 'side.Deficiencia_motivo_califi_condiciones','slp2.Nombre_parametro as Nombre_parametro_lateralidad')
                ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali], ['Id_proceso',$Id_proceso_recali], ['side.Estado_Recalificacion', '=', 'Activo']])->get(); 
        
                $array_datos_deficiencias_alteracionesre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Dominancia', 'sidae.Deficiencia', 
                'sidae.Total_deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion',$Id_asignacion_recali], ['sidae.Estado_Recalificacion', '=', 'Activo']])
                ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
                ->get(); 
          
                $array_agudeza_Auditivare = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
    
                $hay_agudeza_visualre = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                ->where([['ID_evento_re', $Id_evento_recali], ['Id_Asignacion_re', $Id_asignacion_recali], ['Estado_Recalificacion', '=', 'Activo']])->get();

                $array_laboralmente_Activore = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
        
                $array_rol_ocupacionalre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_rol_ocupacional_eventos as siroe')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siroe.Poblacion_calificar')
                ->select('siroe.Id_Rol_ocupacional', 'siroe.ID_evento', 'siroe.Id_Asignacion', 'siroe.Id_proceso', 'siroe.Poblacion_calificar', 
                'slp.Nombre_parametro', 'siroe.Motriz_postura_simetrica', 'siroe.Motriz_actividad_espontanea', 'siroe.Motriz_sujeta_cabeza',
                'siroe.Motriz_sentarse_apoyo', 'siroe.Motriz_gira_sobre_mismo', 'siroe.Motriz_sentanser_sin_apoyo', 'siroe.Motriz_pasa_tumbado_sentado',
                'siroe.Motriz_pararse_apoyo', 'siroe.Motriz_pasos_apoyo', 'siroe.Motriz_pararse_sin_apoyo', 'siroe.Motriz_anda_solo', 'siroe.Motriz_empujar_pelota_pies',
                'siroe.Motriz_andar_obstaculos', 'siroe.Adaptativa_succiona', 'siroe.Adaptativa_fija_mirada', 'siroe.Adaptativa_sigue_trayectoria_objeto',
                'siroe.Adaptativa_sostiene_sonajero', 'siroe.Adaptativa_tiende_mano_hacia_objeto', 'siroe.Adaptativa_sostiene_objeto_manos',
                'siroe.Adaptativa_abre_cajones', 'siroe.Adaptativa_bebe_solo', 'siroe.Adaptativa_quitar_prenda_vestir', 
                'siroe.Adaptativa_reconoce_funcion_espacios_casa', 'siroe.Adaptativa_imita_trazo_lapiz', 'siroe.Adaptativa_abre_puerta',
                'siroe.Total_criterios_desarrollo', 'siroe.Juego_estudio_clase', 'siroe.Total_rol_estudio_clase', 'siroe.Adultos_mayores',
                'siroe.Total_rol_adultos_ayores', 'siroe.Nombre_usuario', 'siroe.F_registro')
                ->where([['siroe.ID_evento',$Id_evento_recali], ['siroe.Id_Asignacion',$Id_asignacion_recali], ['Estado_Recalificacion', 'Activo']])->get();  
                                     
                $array_libros_2_3re = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
                
                if(!empty($array_datos_RecalificacionPcl[0]->Id_Asignacion)){
                    $Id_servicio_balt = $array_datos_RecalificacionPcl[0]->Id_Servicio;
                }
                
                // Validacion de Deficiencias solo en tabla Auditiva                
                $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tabla Visual
                $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tabla Alteraciones del sistema
                $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Auditiva y Alteraciones del sistema
                $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Visual y Alteraciones del sistema
                $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Auditiva y Visual
                $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Alteraciones del sistema, Auditiva y Visual 
                $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));    
                
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Auditiva  
                if(!empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    
                    $array_Deficiencias50 = $array_datos_deficiencicas50[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);                        
        
                    $ultimos_valores = array_slice($deficiencias, -1);
                    list($agudezaAudtivaDef) = $ultimos_valores;
                    
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $deficiencias[$index] = $agudezaAudtivaDef * 2;
                        }
                    }            
                    //print_r($deficiencias);
                                          
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                       
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Visual
                elseif(empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_1[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                               
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                       
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Alteraciones del sistema
                elseif(empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_2[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);    
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);            
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                       
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Auditiva y Alteraciones del sistema
                elseif(!empty($array_datos_deficiencicas50_3) && empty($array_datos_deficiencicas50_1)) {
                    $array_Deficiencias50 = $array_datos_deficiencicas50_3[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -1);
                    list($agudezaAudtivaDef) = $ultimos_valores;
                    
                    //print_r($deficiencias);
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }            
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                       
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Visual y Alteraciones del sistema
                elseif(!empty($array_datos_deficiencicas50_4) && empty($array_datos_deficiencicas50)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_4[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);  
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }                       
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                       
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Auditiva y Visual
                elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_5[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -2);
                    list($agudezaAudtivaDef, $agudezaVisualDef) = $ultimos_valores;
                        
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }            
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                       
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Alteraciones del sistema, Auditiva y Visual
                elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)) {
                    
                    $array_Deficiencias50 = $array_datos_deficiencicas50_6[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -2);
                    list($agudezaAudtivaDef, $agudezaVisualDef) = $ultimos_valores;
                               
                    //print_r($deficiencias);
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }        
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                       
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                else{            
                    $deficiencias = 0;
                    $TotalDeficiencia50 =0;
                }

                $array_comite_interdisciplinariore = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali]
                ])
                ->get(); 
        
                // creación de consecutivo para el comunicado
                $radicadocomunicadore = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->select('N_radicado')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['F_comunicado',$date],
                    ['Id_proceso','2']
                ])
                ->orderBy('N_radicado', 'desc')
                ->limit(1)
                ->get();
                    
                if(count($radicadocomunicadore)==0){
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
                    $consecutivore = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted;            
                }else{
                    $fechaActual = date("Ymd");
                    $ultimoConsecutivo = $radicadocomunicadore[0]->N_radicado;
                    $ultimoDigito = substr($ultimoConsecutivo, -6);
                    $nuevoConsecutivo = $ultimoDigito + 1;
                    // Reiniciar el consecutivo si es un nuevo día
                    if (date("Ymd") != $fechaActual) {
                        $nuevoConsecutivo = 0;
                    }
                    // Poner ceros a la izquierda para llegar a una longitud de 6 dígitos
                    $nuevoConsecutivoFormatted = str_pad($nuevoConsecutivo, 6, "0", STR_PAD_LEFT);
                    $consecutivore = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted;
                }
        
                $array_dictamen_pericialre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
                ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Monto_indemnizacion', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                'side.F_evento', 'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.N_siniestro', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.Estado_decreto',
                'side.N_radicado')
                ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali]])->get();
                
                $array_comunicados_correspondenciare = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$Id_evento_recali], ['Id_Asignacion',$Id_asignacion_recali], ['T_documento','N/A'], ['Modulo_creacion','recalificacionPCL']])->get();
                foreach ($array_comunicados_correspondenciare as $comunicado) {
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
                    $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_recali,$Id_asignacion_recali,$comunicado_inter->Id_Comunicado);

                }
                // $array_comunicados_comite_interre = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
                // ->where([['ID_evento',$Id_evento_recali], ['Id_Asignacion',$Id_asignacion_recali]])->get();  

                $array_comunicados_comite_interre = DB::table('sigmel_gestiones.sigmel_informacion_comite_interdisciplinario_eventos as sicie')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_comunicado_eventos as sice', function ($join) {
                    $join->on('sicie.ID_evento', '=', 'sice.ID_evento')
                        ->on('sicie.N_radicado', '=', 'sice.N_radicado');
                })
                ->where('sicie.ID_evento', $Id_evento_recali)
                ->where('sicie.Id_Asignacion', $Id_asignacion_recali)
                ->select('sicie.*', 'sice.Id_Comunicado', 'sice.Reemplazado', 'sice.Nombre_documento','sice.N_siniestro')
                ->get();
                foreach ($array_comunicados_comite_interre as $comunicado_inter) {
                    if ($comunicado_inter->Nombre_documento != null) {
                        $filePath = public_path('Documentos_Eventos/'.$comunicado_inter->ID_evento.'/'.$comunicado_inter->Nombre_documento);
                        if(File::exists($filePath)){
                            $comunicado_inter->Existe = true;
                        }
                        else{
                            $comunicado_inter->Existe = false;
                        }
                    }
                    else{
                        $comunicado_inter->Existe = false;
                    }

                    $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_recali,$Id_asignacion_recali,$comunicado_inter->Id_Comunicado);
                }  
                
                return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'eventoAsigancion_Recalifi', 'eventoAsigancion_Recalifi_estadoDecreto', 'validar_estado_decreto', 'eventoAsigancion_RecalifiPCL', 'datos_decreto', 'datos_decretore', 'validar_evento_CalifiTecnica', 'numero_consecutivo', 'array_info_decreto_evento', 'array_info_decreto_evento_re', 'array_datos_relacion_documentos', 'motivo_solicitud_actual', 'datos_apoderado_actual', 'array_datos_examenes_interconsultas', 'array_datos_examenes_interconsultasre', 'array_datos_diagnostico_motcalifi', 'array_datos_diagnostico_motcalifire', 'array_datos_deficiencias_alteraciones', 'array_datos_deficiencias_alteracionesre', 'array_agudeza_Auditiva', 'array_agudeza_Auditivare', 'hay_agudeza_visual', 'hay_agudeza_visualre', 'array_laboralmente_Activo', 'array_laboralmente_Activore', 'array_rol_ocupacional', 'array_rol_ocupacionalre', 'array_libros_2_3', 'array_libros_2_3re', 'deficiencias', 'TotalDeficiencia50', 'array_comite_interdisciplinariore', 'consecutivore', 'array_dictamen_pericial', 'array_dictamen_pericialre', 'array_comunicados_correspondenciare', 'array_comunicados_comite_interre', 'info_afp_conocimiento'));
                
            }                        
        } 
        elseif($eventoAsigancionMin_Recalifi == $Id_asignacion_recali || empty($eventoAsigancionMin_Recalifi)) {                           
            // IF para la captura de datos sin Calificación técnica y elseif captura de datos con Calificación técnica
            if(empty($validar_evento_CalifiTecnica[0]->Id_servicio)){   
                
                $datos_decretore =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
                ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
                ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
                'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
                ->where([['side.ID_Evento',$Id_evento_recali], ['side.Id_Asignacion', $Id_asignacion_recali]])->get();
        
                // Obtener el último consecutivo de la base de datos
                $consecutivoDictamen = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->max('Numero_dictamen');
        
                if ($consecutivoDictamen > 0) {
                    $numero_consecutivo = $consecutivoDictamen + 1;
                }else{
                    $numero_consecutivo = 0000000 + 1;
                }
                // Formatear el número consecutivo a 7 dígitos
                $numero_consecutivo = str_pad($numero_consecutivo, 7, "0", STR_PAD_LEFT); 
                    
                $array_info_decreto_evento_re = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
                ->where([
                    ['ID_Evento', $Id_evento_recali],['Id_Asignacion', $Id_asignacion_recali]
                ])
                ->get();
        
                if (!empty($array_info_decreto_evento_re[0]->Id_Asignacion)) {
        
                    $Historiaclínicacompleta  = "Historia clínica completa";
                    $Exámenespreocupacionales = "Exámenes preocupacionales";
                    $Epicrisis = "Epicrisis";
                    $Exámenesperiódicosocupacionales  = "Exámenes periódicos ocupacionales";
                    $Exámenesparaclinicos  = "Exámenes paraclinicos";
                    $ExámenesPostocupacionales  = "Exámenes Post-ocupacionales";
                    $Conceptosdesaludocupacional   = "Conceptos de salud ocupacional";
        
                    $arraytotalRealcionDocumentos = [
                        'Historia clínica completa',
                        'Exámenes preocupacionales',
                        'Epicrisis',
                        'Exámenes periódicos ocupacionales',
                        'Exámenes paraclinicos',
                        'Exámenes Post-ocupacionales',
                        'Conceptos de salud ocupacional',
                    ];
        
                    foreach ($arraytotalRealcionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor); 
                    }
                    $relacionDocuementos = $array_info_decreto_evento_re[0]->Relacion_documentos;
                    $separaRelacionDocumentos = explode(", ",$relacionDocuementos);  
                    
                    foreach ($separaRelacionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor);     
                    }
                    foreach ($arraytotalRealcionDocumentos as $index => $value) {
                        if (!in_array($value, $separaRelacionDocumentos)) {
                            ${$value} = "vacio";
                        }
                    }                       
                }else{
                    list(
                        $Historiaclínicacompleta, 
                        $Exámenespreocupacionales, 
                        $Epicrisis, 
                        $Exámenesperiódicosocupacionales, 
                        $Exámenesparaclinicos, 
                        $ExámenesPostocupacionales, 
                        $Conceptosdesaludocupacional
                    ) = array_fill(0, 7, 'vacio');
                }
                $array_datos_relacion_documentos = [
                    'Historiaclinicacompleta' => $Historiaclínicacompleta, 
                    'Examenespreocupacionales' => $Exámenespreocupacionales, 
                    'Epicrisis' => $Epicrisis, 
                    'Examenesperiodicosocupacionales' => $Exámenesperiódicosocupacionales, 
                    'Examenesparaclinicos' => $Exámenesparaclinicos, 
                    'ExamenesPostocupacionales' => $ExámenesPostocupacionales, 
                    'Conceptosdesaludocupacion' => $Conceptosdesaludocupacional,
                ];
                        
                //Traer Motivo de solicitud,Dominancia actual
                $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
                ->select('Id_motivo_solicitud','Nombre_solicitud','Id_dominancia','Nombre_dominancia')
                ->where([
                    ['ID_evento', '=', $Id_evento_recali]
                ])
                ->get();
                
                $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
                ->select('Nombre_apoderado','Nro_identificacion_apoderado')
                ->where([
                    ['ID_evento', '=', $Id_evento_recali]
                ])
                ->get(); 
                
                $array_datos_examenes_interconsultasre = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Id_proceso',$Id_proceso_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
        
                $array_datos_diagnostico_motcalifire =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                'slp.Nombre_parametro', 'side.Principal', 'side.Deficiencia_motivo_califi_condiciones','slp2.Nombre_parametro as Nombre_parametro_lateralidad')
                ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali], ['Id_proceso',$Id_proceso_recali], ['side.Estado_Recalificacion', '=', 'Activo']])->get(); 
        
                $array_datos_deficiencias_alteracionesre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Dominancia', 'sidae.Deficiencia', 
                'sidae.Total_deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion',$Id_asignacion_recali], ['sidae.Estado_Recalificacion', '=', 'Activo']])
                ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
                ->get(); 
            
                $array_agudeza_Auditivare = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
                
                $hay_agudeza_visualre = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                ->where([['ID_evento_re', $Id_evento_recali], ['Id_Asignacion_re', $Id_asignacion_recali], ['Estado_Recalificacion', '=', 'Activo']])->get();
                                
                $array_laboralmente_Activore = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
        
                $array_rol_ocupacionalre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_rol_ocupacional_eventos as siroe')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siroe.Poblacion_calificar')
                ->select('siroe.Id_Rol_ocupacional', 'siroe.ID_evento', 'siroe.Id_Asignacion', 'siroe.Id_proceso', 'siroe.Poblacion_calificar', 
                'slp.Nombre_parametro', 'siroe.Motriz_postura_simetrica', 'siroe.Motriz_actividad_espontanea', 'siroe.Motriz_sujeta_cabeza',
                'siroe.Motriz_sentarse_apoyo', 'siroe.Motriz_gira_sobre_mismo', 'siroe.Motriz_sentanser_sin_apoyo', 'siroe.Motriz_pasa_tumbado_sentado',
                'siroe.Motriz_pararse_apoyo', 'siroe.Motriz_pasos_apoyo', 'siroe.Motriz_pararse_sin_apoyo', 'siroe.Motriz_anda_solo', 'siroe.Motriz_empujar_pelota_pies',
                'siroe.Motriz_andar_obstaculos', 'siroe.Adaptativa_succiona', 'siroe.Adaptativa_fija_mirada', 'siroe.Adaptativa_sigue_trayectoria_objeto',
                'siroe.Adaptativa_sostiene_sonajero', 'siroe.Adaptativa_tiende_mano_hacia_objeto', 'siroe.Adaptativa_sostiene_objeto_manos',
                'siroe.Adaptativa_abre_cajones', 'siroe.Adaptativa_bebe_solo', 'siroe.Adaptativa_quitar_prenda_vestir', 
                'siroe.Adaptativa_reconoce_funcion_espacios_casa', 'siroe.Adaptativa_imita_trazo_lapiz', 'siroe.Adaptativa_abre_puerta',
                'siroe.Total_criterios_desarrollo', 'siroe.Juego_estudio_clase', 'siroe.Total_rol_estudio_clase', 'siroe.Adultos_mayores',
                'siroe.Total_rol_adultos_ayores', 'siroe.Nombre_usuario', 'siroe.F_registro')
                ->where([['siroe.ID_evento',$Id_evento_recali], ['siroe.Id_Asignacion',$Id_asignacion_recali], ['Estado_Recalificacion', 'Activo']])->get();  
        
                $array_libros_2_3re = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
                
                if(!empty($array_datos_RecalificacionPcl[0]->Id_Asignacion)){
                    $Id_servicio_balt = $array_datos_RecalificacionPcl[0]->Id_Servicio;
                }                    

                // Validacion de Deficiencias solo en tabla Auditiva                
                $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tabla Visual
                $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tabla Alteraciones del sistema
                $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Auditiva y Alteraciones del sistema
                $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Visual y Alteraciones del sistema
                $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Auditiva y Visual
                $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Alteraciones del sistema, Auditiva y Visual 
                $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));    
                
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Auditiva  
                if(!empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    
                    $array_Deficiencias50 = $array_datos_deficiencicas50[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);                        
        
                    $ultimos_valores = array_slice($deficiencias, -1);
                    list($agudezaAudtivaDef) = $ultimos_valores;
                    
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $deficiencias[$index] = $agudezaAudtivaDef * 2;
                        }
                    }            
                    //print_r($deficiencias);
                                            
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Visual
                elseif(empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_1[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                                
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Alteraciones del sistema
                elseif(empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_2[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);    
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);            
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Auditiva y Alteraciones del sistema
                elseif(!empty($array_datos_deficiencicas50_3) && empty($array_datos_deficiencicas50_1)) {
                    $array_Deficiencias50 = $array_datos_deficiencicas50_3[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -1);
                    list($agudezaAudtivaDef) = $ultimos_valores;
                    
                    //print_r($deficiencias);
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }            
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Visual y Alteraciones del sistema
                elseif(!empty($array_datos_deficiencicas50_4) && empty($array_datos_deficiencicas50)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_4[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);  
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }                       
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Auditiva y Visual
                elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_5[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -2);
                    list($agudezaAudtivaDef, $agudezaVisualDef) = $ultimos_valores;
                        
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }            
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Alteraciones del sistema, Auditiva y Visual
                elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)) {
                    
                    $array_Deficiencias50 = $array_datos_deficiencicas50_6[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -2);
                    list($agudezaAudtivaDef, $agudezaVisualDef) = $ultimos_valores;
                                
                    //print_r($deficiencias);
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }        
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                else{            
                    $deficiencias = 0;
                    $TotalDeficiencia50 =0;
                }

                $array_tipo_fecha_evento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'sie.Tipo_evento')
                ->select('sie.ID_evento', 'sie.Tipo_evento', 'slte.Nombre_evento', 'sie.F_evento')
                ->where('sie.ID_evento', $Id_evento_recali)
                ->get();

                $array_comite_interdisciplinariore = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali]
                ])
                ->get(); 
        
                // creación de consecutivo para el comunicado
                $radicadocomunicadore = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->select('N_radicado')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['F_comunicado',$date],
                    ['Id_proceso','2']
                ])
                ->orderBy('N_radicado', 'desc')
                ->limit(1)
                ->get();
                    
                if(count($radicadocomunicadore)==0){
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
                    $consecutivore = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted;            
                }else{
                    $fechaActual = date("Ymd");
                    $ultimoConsecutivo = $radicadocomunicadore[0]->N_radicado;
                    $ultimoDigito = substr($ultimoConsecutivo, -6);
                    $nuevoConsecutivo = $ultimoDigito + 1;
                    // Reiniciar el consecutivo si es un nuevo día
                    if (date("Ymd") != $fechaActual) {
                        $nuevoConsecutivo = 0;
                    }
                    // Poner ceros a la izquierda para llegar a una longitud de 6 dígitos
                    $nuevoConsecutivoFormatted = str_pad($nuevoConsecutivo, 6, "0", STR_PAD_LEFT);
                    $consecutivore = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted;
                }
        
                $array_dictamen_pericialre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
                ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Monto_indemnizacion', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                'side.F_evento', 'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.N_siniestro', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.Estado_decreto',
                'side.N_radicado')
                ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali]])->get();  
                
                $array_comunicados_correspondenciare = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$Id_evento_recali], ['Id_Asignacion',$Id_asignacion_recali], ['T_documento','N/A'], ['Modulo_creacion','recalificacionPCL']])->get();  
                foreach ($array_comunicados_correspondenciare as $comunicado) {
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

                    $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_recali,$Id_asignacion_recali,$comunicado_inter->Id_Comunicado);
                }
                // $array_comunicados_comite_interre = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
                // ->where([['ID_evento',$Id_evento_recali], ['Id_Asignacion',$Id_asignacion_recali]])->get();  
                $array_comunicados_comite_interre = DB::table('sigmel_gestiones.sigmel_informacion_comite_interdisciplinario_eventos as sicie')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_comunicado_eventos as sice', function ($join) {
                    $join->on('sicie.ID_evento', '=', 'sice.ID_evento')
                        ->on('sicie.N_radicado', '=', 'sice.N_radicado');
                })
                ->where('sicie.ID_evento', $Id_evento_recali)
                ->where('sicie.Id_Asignacion', $Id_asignacion_recali)
                ->select('sicie.*', 'sice.Id_Comunicado', 'sice.Reemplazado', 'sice.Nombre_documento','sice.N_siniestro')
                ->get();
                foreach ($array_comunicados_comite_interre as $comunicado_inter) {
                    if ($comunicado_inter->Nombre_documento != null) {
                        $filePath = public_path('Documentos_Eventos/'.$comunicado_inter->ID_evento.'/'.$comunicado_inter->Nombre_documento);
                        if(File::exists($filePath)){
                            $comunicado_inter->Existe = true;
                        }
                        else{
                            $comunicado_inter->Existe = false;
                        }
                    }
                    else{
                        $comunicado_inter->Existe = false;
                    }
                    $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_recali,$Id_asignacion_recali,$comunicado_inter->Id_Comunicado);
                }                
                
                return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'validar_estado_decreto', 'datos_decretore', 'validar_evento_CalifiTecnica', 'array_info_decreto_evento_re', 'array_datos_relacion_documentos', 'motivo_solicitud_actual', 'datos_apoderado_actual', 'array_datos_examenes_interconsultasre', 'array_datos_diagnostico_motcalifire', 'array_datos_deficiencias_alteracionesre', 'array_agudeza_Auditivare', 'hay_agudeza_visualre', 'array_laboralmente_Activore', 'array_rol_ocupacionalre', 'array_libros_2_3re', 'deficiencias', 'TotalDeficiencia50', 'array_tipo_fecha_evento', 'array_comite_interdisciplinariore', 'consecutivore', 'array_dictamen_pericialre', 'array_comunicados_correspondenciare', 'array_comunicados_comite_interre', 'info_afp_conocimiento'));
                // return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'array_datos_motivo_solicitud', 'validar_estado_decreto', 'datos_decreto', 'datos_decretore', 'validar_evento_CalifiTecnica', 'numero_consecutivo', 'array_info_decreto_evento', 'array_info_decreto_evento_re', 'array_datos_relacion_documentos', 'motivo_solicitud_actual', 'datos_apoderado_actual', 'array_datos_examenes_interconsultas', 'array_datos_examenes_interconsultasre', 'array_datos_diagnostico_motcalifi', 'array_datos_diagnostico_motcalifire', 'array_datos_deficiencias_alteraciones', 'array_datos_deficiencias_alteracionesre', 'array_agudeza_Auditiva', 'array_agudeza_Auditivare', 'hay_agudeza_visual', 'hay_agudeza_visualre', 'array_laboralmente_Activo', 'array_laboralmente_Activore', 'array_rol_ocupacional', 'array_rol_ocupacionalre', 'array_libros_2_3', 'array_libros_2_3re', 'deficiencias', 'TotalDeficiencia50', 'array_comite_interdisciplinariore', 'consecutivore', 'array_dictamen_pericial', 'array_dictamen_pericialre', 'array_comunicados_correspondenciare', 'array_comunicados_comite_interre', 'info_afp_conocimiento'));
            }            
            elseif (!empty($validar_evento_CalifiTecnica[0]->Id_servicio)) { 
                
                if (!empty($validar_estado_decreto[0]->ID_Evento)) {
                    $datos_decreto =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
                    ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
                    'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
                    ->where([['side.ID_Evento',$Id_evento_recali], ['side.Id_Asignacion',$validar_estado_decreto[0]->Id_Asignacion_decreto]])->get(); 
                    
                    $array_info_decreto_evento = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
                    ->where([
                        ['ID_Evento', $Id_evento_recali],['Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto]
                    ])
                    ->get();
                    
                    $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto],
                        ['Id_proceso',$Id_proceso_recali],
                        ['Estado', 'Activo']
                    ])
                    ->get();
        
                    $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                    ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                    'slp.Nombre_parametro', 'side.Principal', 'side.Deficiencia_motivo_califi_condiciones','slp2.Nombre_parametro as Nombre_parametro_lateralidad')
                    ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto], ['Id_proceso',$Id_proceso_recali], ['side.Estado', '=', 'Activo']])->get();
        
                    $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                    ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                    'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                    'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Dominancia', 'sidae.Deficiencia', 
                    'sidae.Total_deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                    ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto], ['sidae.Estado', '=', 'Activo']])
                    ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
                    ->get();
        
                    $array_agudeza_Auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_Asignacion',$validar_estado_decreto[0]->Id_Asignacion_decreto],
                        ['Estado', 'Activo']
                    ])
                    ->get();
        
                    $hay_agudeza_visual = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                    ->where([['ID_evento_re', $Id_evento_recali], ['Id_Asignacion_re',$validar_estado_decreto[0]->Id_Asignacion_decreto], ['Estado_Recalificacion', '=', 'Activo']])->get();
        
                    $array_laboralmente_Activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_Asignacion',$validar_estado_decreto[0]->Id_Asignacion_decreto],
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();
        
                    $array_rol_ocupacional =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_rol_ocupacional_eventos as siroe')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siroe.Poblacion_calificar')
                    ->select('siroe.Id_Rol_ocupacional', 'siroe.ID_evento', 'siroe.Id_Asignacion', 'siroe.Id_proceso', 'siroe.Poblacion_calificar', 
                    'slp.Nombre_parametro', 'siroe.Motriz_postura_simetrica', 'siroe.Motriz_actividad_espontanea', 'siroe.Motriz_sujeta_cabeza',
                    'siroe.Motriz_sentarse_apoyo', 'siroe.Motriz_gira_sobre_mismo', 'siroe.Motriz_sentanser_sin_apoyo', 'siroe.Motriz_pasa_tumbado_sentado',
                    'siroe.Motriz_pararse_apoyo', 'siroe.Motriz_pasos_apoyo', 'siroe.Motriz_pararse_sin_apoyo', 'siroe.Motriz_anda_solo', 'siroe.Motriz_empujar_pelota_pies',
                    'siroe.Motriz_andar_obstaculos', 'siroe.Adaptativa_succiona', 'siroe.Adaptativa_fija_mirada', 'siroe.Adaptativa_sigue_trayectoria_objeto',
                    'siroe.Adaptativa_sostiene_sonajero', 'siroe.Adaptativa_tiende_mano_hacia_objeto', 'siroe.Adaptativa_sostiene_objeto_manos',
                    'siroe.Adaptativa_abre_cajones', 'siroe.Adaptativa_bebe_solo', 'siroe.Adaptativa_quitar_prenda_vestir', 
                    'siroe.Adaptativa_reconoce_funcion_espacios_casa', 'siroe.Adaptativa_imita_trazo_lapiz', 'siroe.Adaptativa_abre_puerta',
                    'siroe.Total_criterios_desarrollo', 'siroe.Juego_estudio_clase', 'siroe.Total_rol_estudio_clase', 'siroe.Adultos_mayores',
                    'siroe.Total_rol_adultos_ayores', 'siroe.Nombre_usuario', 'siroe.F_registro')
                    ->where([['siroe.ID_evento',$Id_evento_recali], ['siroe.Id_Asignacion',$validar_estado_decreto[0]->Id_Asignacion_decreto], ['Estado_Recalificacion', 'Activo']])->get();
        
                    $array_libros_2_3 = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_Asignacion',$validar_estado_decreto[0]->Id_Asignacion_decreto],
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();
        
                    $array_dictamen_pericial =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
                    ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Monto_indemnizacion', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                    'side.F_evento', 'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.N_siniestro', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                    'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                    'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia')
                    ->where([['side.ID_evento',$Id_evento_recali]], ['side.Id_Asignacion',$validar_estado_decreto[0]->Id_Asignacion_decreto])->get();  
        
                } else {
                    $datos_decreto =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
                    ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
                    'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
                    ->where([['side.ID_Evento',$Id_evento_recali]])->get(); 
                    
                    $array_info_decreto_evento = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
                    ->where([
                        ['ID_Evento', $Id_evento_recali]
                    ])
                    ->get();   
                                            
                    $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Id_proceso',$Id_proceso_recali],
                        ['Estado', 'Activo']
                    ])
                    ->get();
        
                    $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                    ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                    'slp.Nombre_parametro', 'side.Principal', 'side.Deficiencia_motivo_califi_condiciones','slp2.Nombre_parametro as Nombre_parametro_lateralidad')
                    ->where([['side.ID_evento',$Id_evento_recali], ['Id_proceso',$Id_proceso_recali], ['side.Estado', '=', 'Activo']])->get();
        
                    $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                    ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                    'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                    'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Dominancia', 'sidae.Deficiencia', 
                    'sidae.Total_deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                    ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Estado', '=', 'Activo']])
                    ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
                    ->get();
        
                    $array_agudeza_Auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Estado', 'Activo']
                    ])
                    ->get();
        
                    $hay_agudeza_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
                    ->where([['ID_evento', $Id_evento_recali]])->get();
        
                    $array_laboralmente_Activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();
                    
                    $array_rol_ocupacional =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_rol_ocupacional_eventos as siroe')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siroe.Poblacion_calificar')
                    ->select('siroe.Id_Rol_ocupacional', 'siroe.ID_evento', 'siroe.Id_Asignacion', 'siroe.Id_proceso', 'siroe.Poblacion_calificar', 
                    'slp.Nombre_parametro', 'siroe.Motriz_postura_simetrica', 'siroe.Motriz_actividad_espontanea', 'siroe.Motriz_sujeta_cabeza',
                    'siroe.Motriz_sentarse_apoyo', 'siroe.Motriz_gira_sobre_mismo', 'siroe.Motriz_sentanser_sin_apoyo', 'siroe.Motriz_pasa_tumbado_sentado',
                    'siroe.Motriz_pararse_apoyo', 'siroe.Motriz_pasos_apoyo', 'siroe.Motriz_pararse_sin_apoyo', 'siroe.Motriz_anda_solo', 'siroe.Motriz_empujar_pelota_pies',
                    'siroe.Motriz_andar_obstaculos', 'siroe.Adaptativa_succiona', 'siroe.Adaptativa_fija_mirada', 'siroe.Adaptativa_sigue_trayectoria_objeto',
                    'siroe.Adaptativa_sostiene_sonajero', 'siroe.Adaptativa_tiende_mano_hacia_objeto', 'siroe.Adaptativa_sostiene_objeto_manos',
                    'siroe.Adaptativa_abre_cajones', 'siroe.Adaptativa_bebe_solo', 'siroe.Adaptativa_quitar_prenda_vestir', 
                    'siroe.Adaptativa_reconoce_funcion_espacios_casa', 'siroe.Adaptativa_imita_trazo_lapiz', 'siroe.Adaptativa_abre_puerta',
                    'siroe.Total_criterios_desarrollo', 'siroe.Juego_estudio_clase', 'siroe.Total_rol_estudio_clase', 'siroe.Adultos_mayores',
                    'siroe.Total_rol_adultos_ayores', 'siroe.Nombre_usuario', 'siroe.F_registro')
                    ->where([['siroe.ID_evento',$Id_evento_recali], ['Estado_Recalificacion', 'Activo']])->get();
        
                    $array_libros_2_3 = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento',$Id_evento_recali],
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();

                    $array_dictamen_pericial =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
                    ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Monto_indemnizacion', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                    'side.F_evento', 'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.N_siniestro', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                    'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                    'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia')
                    ->where([['side.ID_evento',$Id_evento_recali]])->get();
                
                }
                
                $datos_decretore =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
                    ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
                    'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
                    ->where([['side.ID_Evento',$Id_evento_recali], ['side.Id_Asignacion', $Id_asignacion_recali]])->get();
        
                // Obtener el último consecutivo de la base de datos
                $consecutivoDictamen = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->max('Numero_dictamen');
        
                if ($consecutivoDictamen > 0) {
                    $numero_consecutivo = $consecutivoDictamen + 1;
                }else{
                    $numero_consecutivo = 0000000 + 1;
                }
                // Formatear el número consecutivo a 7 dígitos
                $numero_consecutivo = str_pad($numero_consecutivo, 7, "0", STR_PAD_LEFT); 
                    
                $array_info_decreto_evento_re = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
                ->where([
                    ['ID_Evento', $Id_evento_recali],['Id_Asignacion', $Id_asignacion_recali]
                ])
                ->get();
        
                if (!empty($array_info_decreto_evento_re[0]->Id_Asignacion)) {
        
                    $Historiaclínicacompleta  = "Historia clínica completa";
                    $Exámenespreocupacionales = "Exámenes preocupacionales";
                    $Epicrisis = "Epicrisis";
                    $Exámenesperiódicosocupacionales  = "Exámenes periódicos ocupacionales";
                    $Exámenesparaclinicos  = "Exámenes paraclinicos";
                    $ExámenesPostocupacionales  = "Exámenes Post-ocupacionales";
                    $Conceptosdesaludocupacional   = "Conceptos de salud ocupacional";
        
                    $arraytotalRealcionDocumentos = [
                        'Historia clínica completa',
                        'Exámenes preocupacionales',
                        'Epicrisis',
                        'Exámenes periódicos ocupacionales',
                        'Exámenes paraclinicos',
                        'Exámenes Post-ocupacionales',
                        'Conceptos de salud ocupacional',
                    ];
        
                    foreach ($arraytotalRealcionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor); 
                    }
                    $relacionDocuementos = $array_info_decreto_evento_re[0]->Relacion_documentos;
                    $separaRelacionDocumentos = explode(", ",$relacionDocuementos);  
                    
                    foreach ($separaRelacionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor);     
                    }
                    foreach ($arraytotalRealcionDocumentos as $index => $value) {
                        if (!in_array($value, $separaRelacionDocumentos)) {
                            ${$value} = "vacio";
                        }
                    }                       
                }elseif(!empty($array_info_decreto_evento[0]->Id_Asignacion)){
                    $Historiaclínicacompleta  = "Historia clínica completa";
                    $Exámenespreocupacionales = "Exámenes preocupacionales";
                    $Epicrisis = "Epicrisis";
                    $Exámenesperiódicosocupacionales  = "Exámenes periódicos ocupacionales";
                    $Exámenesparaclinicos  = "Exámenes paraclinicos";
                    $ExámenesPostocupacionales  = "Exámenes Post-ocupacionales";
                    $Conceptosdesaludocupacional   = "Conceptos de salud ocupacional";
        
                    $arraytotalRealcionDocumentos = [
                        'Historia clínica completa',
                        'Exámenes preocupacionales',
                        'Epicrisis',
                        'Exámenes periódicos ocupacionales',
                        'Exámenes paraclinicos',
                        'Exámenes Post-ocupacionales',
                        'Conceptos de salud ocupacional',
                    ];
        
                    foreach ($arraytotalRealcionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor); 
                    }
                    $relacionDocuementos = $array_info_decreto_evento[0]->Relacion_documentos;
                    $separaRelacionDocumentos = explode(", ",$relacionDocuementos);  
                    
                    foreach ($separaRelacionDocumentos as &$valor) {    
                        $valor = trim($valor);
                        $valor = str_replace("-", "", $valor);  
                        //$valor = strtr($valor, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU'); 
                        $valor = preg_replace("/\s+/", "", $valor);     
                    }
                    foreach ($arraytotalRealcionDocumentos as $index => $value) {
                        if (!in_array($value, $separaRelacionDocumentos)) {
                            ${$value} = "vacio";
                        }
                    }
                }else{
                    list(
                        $Historiaclínicacompleta, 
                        $Exámenespreocupacionales, 
                        $Epicrisis, 
                        $Exámenesperiódicosocupacionales, 
                        $Exámenesparaclinicos, 
                        $ExámenesPostocupacionales, 
                        $Conceptosdesaludocupacional
                    ) = array_fill(0, 7, 'vacio');
                }
                $array_datos_relacion_documentos = [
                    'Historiaclinicacompleta' => $Historiaclínicacompleta, 
                    'Examenespreocupacionales' => $Exámenespreocupacionales, 
                    'Epicrisis' => $Epicrisis, 
                    'Examenesperiodicosocupacionales' => $Exámenesperiódicosocupacionales, 
                    'Examenesparaclinicos' => $Exámenesparaclinicos, 
                    'ExamenesPostocupacionales' => $ExámenesPostocupacionales, 
                    'Conceptosdesaludocupacion' => $Conceptosdesaludocupacional,
                ];
                        
                //Traer Motivo de solicitud,Dominancia actual
                $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
                ->select('Id_motivo_solicitud','Nombre_solicitud','Id_dominancia','Nombre_dominancia')
                ->where([
                    ['ID_evento', '=', $Id_evento_recali]
                ])
                ->get();
                
                $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
                ->select('Nombre_apoderado','Nro_identificacion_apoderado')
                ->where([
                    ['ID_evento', '=', $Id_evento_recali]
                ])
                ->get(); 
                
                $array_datos_examenes_interconsultasre = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Id_proceso',$Id_proceso_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
        
                $array_datos_diagnostico_motcalifire =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                'slp.Nombre_parametro', 'side.Principal', 'side.Deficiencia_motivo_califi_condiciones','slp2.Nombre_parametro as Nombre_parametro_lateralidad')
                ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali], ['Id_proceso',$Id_proceso_recali], ['side.Estado_Recalificacion', '=', 'Activo']])->get(); 
        
                $array_datos_deficiencias_alteracionesre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Dominancia', 'sidae.Deficiencia', 
                'sidae.Total_deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion',$Id_asignacion_recali], ['sidae.Estado_Recalificacion', '=', 'Activo']])
                ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
                ->get(); 
            
                $array_agudeza_Auditivare = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
                
                $hay_agudeza_visualre = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                ->where([['ID_evento_re', $Id_evento_recali], ['Id_Asignacion_re', $Id_asignacion_recali], ['Estado_Recalificacion', '=', 'Activo']])->get();
                                
                $array_laboralmente_Activore = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
        
                $array_rol_ocupacionalre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_rol_ocupacional_eventos as siroe')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siroe.Poblacion_calificar')
                ->select('siroe.Id_Rol_ocupacional', 'siroe.ID_evento', 'siroe.Id_Asignacion', 'siroe.Id_proceso', 'siroe.Poblacion_calificar', 
                'slp.Nombre_parametro', 'siroe.Motriz_postura_simetrica', 'siroe.Motriz_actividad_espontanea', 'siroe.Motriz_sujeta_cabeza',
                'siroe.Motriz_sentarse_apoyo', 'siroe.Motriz_gira_sobre_mismo', 'siroe.Motriz_sentanser_sin_apoyo', 'siroe.Motriz_pasa_tumbado_sentado',
                'siroe.Motriz_pararse_apoyo', 'siroe.Motriz_pasos_apoyo', 'siroe.Motriz_pararse_sin_apoyo', 'siroe.Motriz_anda_solo', 'siroe.Motriz_empujar_pelota_pies',
                'siroe.Motriz_andar_obstaculos', 'siroe.Adaptativa_succiona', 'siroe.Adaptativa_fija_mirada', 'siroe.Adaptativa_sigue_trayectoria_objeto',
                'siroe.Adaptativa_sostiene_sonajero', 'siroe.Adaptativa_tiende_mano_hacia_objeto', 'siroe.Adaptativa_sostiene_objeto_manos',
                'siroe.Adaptativa_abre_cajones', 'siroe.Adaptativa_bebe_solo', 'siroe.Adaptativa_quitar_prenda_vestir', 
                'siroe.Adaptativa_reconoce_funcion_espacios_casa', 'siroe.Adaptativa_imita_trazo_lapiz', 'siroe.Adaptativa_abre_puerta',
                'siroe.Total_criterios_desarrollo', 'siroe.Juego_estudio_clase', 'siroe.Total_rol_estudio_clase', 'siroe.Adultos_mayores',
                'siroe.Total_rol_adultos_ayores', 'siroe.Nombre_usuario', 'siroe.F_registro')
                ->where([['siroe.ID_evento',$Id_evento_recali], ['siroe.Id_Asignacion',$Id_asignacion_recali], ['Estado_Recalificacion', 'Activo']])->get();  
        
                $array_libros_2_3re = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali],
                    ['Estado_Recalificacion', 'Activo']
                ])
                ->get();
                
                if(!empty($array_datos_RecalificacionPcl[0]->Id_Asignacion)){
                    $Id_servicio_balt = $array_datos_RecalificacionPcl[0]->Id_Servicio;
                }                    

                // Validacion de Deficiencias solo en tabla Auditiva                
                $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tabla Visual
                $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tabla Alteraciones del sistema
                $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Auditiva y Alteraciones del sistema
                $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Visual y Alteraciones del sistema
                $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Auditiva y Visual
                $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                // Validacion de Deficiencias solo en tablas Alteraciones del sistema, Auditiva y Visual 
                $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));    
                
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Auditiva  
                if(!empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    
                    $array_Deficiencias50 = $array_datos_deficiencicas50[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);                        
        
                    $ultimos_valores = array_slice($deficiencias, -1);
                    list($agudezaAudtivaDef) = $ultimos_valores;
                    
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $deficiencias[$index] = $agudezaAudtivaDef * 2;
                        }
                    }            
                    //print_r($deficiencias);
                                            
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Visual
                elseif(empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_1[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                                
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tabla Alteraciones del sistema
                elseif(empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_2[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);    
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);            
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Auditiva y Alteraciones del sistema
                elseif(!empty($array_datos_deficiencicas50_3) && empty($array_datos_deficiencicas50_1)) {
                    $array_Deficiencias50 = $array_datos_deficiencicas50_3[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -1);
                    list($agudezaAudtivaDef) = $ultimos_valores;
                    
                    //print_r($deficiencias);
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }            
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Visual y Alteraciones del sistema
                elseif(!empty($array_datos_deficiencicas50_4) && empty($array_datos_deficiencicas50)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_4[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);  
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }                       
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Auditiva y Visual
                elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
                    $array_Deficiencias50 = $array_datos_deficiencicas50_5[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -2);
                    list($agudezaAudtivaDef, $agudezaVisualDef) = $ultimos_valores;
                        
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }            
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                // Calculo Suma combinada y total 50% Deficiencia solo en tablas Alteraciones del sistema, Auditiva y Visual
                elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)) {
                    
                    $array_Deficiencias50 = $array_datos_deficiencicas50_6[0]->deficiencias;
                    $deficiencias = explode(",", $array_Deficiencias50);
                    //print_r($deficiencias);            
                    $ultimos_valores = array_slice($deficiencias, -2);
                    list($agudezaAudtivaDef, $agudezaVisualDef) = $ultimos_valores;
                                
                    //print_r($deficiencias);
                    usort($deficiencias, function($a, $b) {
                        $numA = preg_replace('/[^0-9.]+/', '', $a);
                        $numB = preg_replace('/[^0-9.]+/', '', $b);
                    
                        if ($numA > $numB) {
                            return -1;
                        } else if ($numA < $numB) {
                            return 1;
                        } else {
                            return 0;
                        }
                    });            
                    //print_r($deficiencias);
                    foreach ($deficiencias as $key => $value) {
                        if (strpos($value, "(si)") !== false) {
                            //$deficiencias[$key] = 23.20;
                            $numerodeficiencia = (float) preg_replace('/[^\d.]/', '', $value);
                            $nuevoValor = $numerodeficiencia * 0.2;
                            $a = $numerodeficiencia;
                            $b = $nuevoValor;
                            $resultadoMSD = $a + (100 - $a) * $b / 100;
                            $deficiencias[$key] = $resultadoMSD;
                        }
                    }
                    //print_r($deficiencias);
                    $indexDoble = null;            
                    foreach ($deficiencias as $index => $value) {
                        if ($value == $agudezaAudtivaDef) {
                            $indexDoble = $index;
                            break;
                        }
                    }            
                    if ($indexDoble !== null) {
                        $deficiencias[$indexDoble] *= 2;
                    }        
                    //print_r($deficiencias);
                    while(!empty($deficiencias) && count($deficiencias) > 1) {
                        $a = $deficiencias[0];
                        $b = $deficiencias[1];
                        $resultado = $a + (100 - $a) * $b / 100;
                        array_shift($deficiencias);
                        array_shift($deficiencias);
                        array_unshift($deficiencias, $resultado);
                    }
                    //print_r($deficiencias);
                    foreach ($deficiencias as &$value) {
                        $value = round($value, 2); 
                                        
                        $TotalDeficiencia50 = $value * 50 / 100;
                    }
                    
                }
                else{            
                    $deficiencias = 0;
                    $TotalDeficiencia50 =0;
                }

                $array_comite_interdisciplinariore = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['Id_Asignacion',$Id_asignacion_recali]
                ])
                ->get(); 
        
                // creación de consecutivo para el comunicado
                $radicadocomunicadore = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->select('N_radicado')
                ->where([
                    ['ID_evento',$Id_evento_recali],
                    ['F_comunicado',$date],
                    ['Id_proceso','2']
                ])
                ->orderBy('N_radicado', 'desc')
                ->limit(1)
                ->get();
                    
                if(count($radicadocomunicadore)==0){
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
                    $consecutivore = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted;            
                }else{
                    $fechaActual = date("Ymd");
                    $ultimoConsecutivo = $radicadocomunicadore[0]->N_radicado;
                    $ultimoDigito = substr($ultimoConsecutivo, -6);
                    $nuevoConsecutivo = $ultimoDigito + 1;
                    // Reiniciar el consecutivo si es un nuevo día
                    if (date("Ymd") != $fechaActual) {
                        $nuevoConsecutivo = 0;
                    }
                    // Poner ceros a la izquierda para llegar a una longitud de 6 dígitos
                    $nuevoConsecutivoFormatted = str_pad($nuevoConsecutivo, 6, "0", STR_PAD_LEFT);
                    $consecutivore = "SAL-PCL" . $fechaActual . $nuevoConsecutivoFormatted;
                }
        
                $array_dictamen_pericialre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
                ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Monto_indemnizacion', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                'side.F_evento', 'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.N_siniestro', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.Estado_decreto',
                'side.N_radicado')
                ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali]])->get();  
                
                $array_comunicados_correspondenciare = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$Id_evento_recali], ['Id_Asignacion',$Id_asignacion_recali], ['T_documento','N/A'], ['Modulo_creacion','recalificacionPCL']])->get();  
                foreach ($array_comunicados_correspondenciare as $comunicado) {
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
                    $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_recali,$Id_asignacion_recali,$comunicado->Id_Comunicado);
                }
                // $array_comunicados_comite_interre = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
                // ->where([['ID_evento',$Id_evento_recali], ['Id_Asignacion',$Id_asignacion_recali]])->get();  
                $array_comunicados_comite_interre = DB::table('sigmel_gestiones.sigmel_informacion_comite_interdisciplinario_eventos as sicie')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_comunicado_eventos as sice', function ($join) {
                    $join->on('sicie.ID_evento', '=', 'sice.ID_evento')
                        ->on('sicie.N_radicado', '=', 'sice.N_radicado');
                })
                ->where('sicie.ID_evento', $Id_evento_recali)
                ->where('sicie.Id_Asignacion', $Id_asignacion_recali)
                ->select('sicie.*', 'sice.Id_Comunicado', 'sice.Reemplazado', 'sice.Nombre_documento','sice.N_siniestro')
                ->get();
                foreach ($array_comunicados_comite_interre as $comunicado_inter) {
                    if ($comunicado_inter->Nombre_documento != null) {
                        $filePath = public_path('Documentos_Eventos/'.$comunicado_inter->ID_evento.'/'.$comunicado_inter->Nombre_documento);
                        if(File::exists($filePath)){
                            $comunicado_inter->Existe = true;
                        }
                        else{
                            $comunicado_inter->Existe = false;
                        }
                    }
                    else{
                        $comunicado_inter->Existe = false;
                    }
                    $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_recali,$Id_asignacion_recali,$comunicado_inter->Id_Comunicado);
                }  

                return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'validar_estado_decreto', 'datos_decreto', 'datos_decretore', 'validar_evento_CalifiTecnica', 'numero_consecutivo', 'array_info_decreto_evento', 'array_info_decreto_evento_re', 'array_datos_relacion_documentos', 'motivo_solicitud_actual', 'datos_apoderado_actual', 'array_datos_examenes_interconsultas', 'array_datos_examenes_interconsultasre', 'array_datos_diagnostico_motcalifi', 'array_datos_diagnostico_motcalifire', 'array_datos_deficiencias_alteraciones', 'array_datos_deficiencias_alteracionesre', 'array_agudeza_Auditiva', 'array_agudeza_Auditivare', 'hay_agudeza_visual', 'hay_agudeza_visualre', 'array_laboralmente_Activo', 'array_laboralmente_Activore', 'array_rol_ocupacional', 'array_rol_ocupacionalre', 'array_libros_2_3', 'array_libros_2_3re', 'deficiencias', 'TotalDeficiencia50', 'array_comite_interdisciplinariore', 'consecutivore', 'array_dictamen_pericial', 'array_dictamen_pericialre', 'array_comunicados_correspondenciare', 'array_comunicados_comite_interre', 'info_afp_conocimiento'));
                
            }
        }
    }

    public function cargueListadoSelectoresRecalificacionPcl(Request $request){
        $parametro = $request->parametro;
        // Listado Origen Firme calificacion PCL
        if($parametro == 'lista_origen_firme_pcl'){
            $listado_origen_firme = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Firme'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_origen_firme = json_decode(json_encode($listado_origen_firme, true));
            return response()->json($info_listado_origen_firme);
        }
        // Listado Cobertura calificacion PCL
        if($parametro == 'lista_origen_cobertura_pcl'){
            $listado_origen_cobertura = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Cobertura'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_origen_cobertura = json_decode(json_encode($listado_origen_cobertura, true));
            return response()->json($info_listado_origen_cobertura);
        }
        // Listado decreto calificacion PCL
        if($parametro == 'lista_cali_decreto_pcl'){
            $listado_cali_decreto = sigmel_lista_califi_decretos::on('sigmel_gestiones')
            ->select('Id_Decreto', 'Nombre_decreto')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_cali_decreto = json_decode(json_encode($listado_cali_decreto, true));
            return response()->json($info_listado_cali_decreto);
        }
        // Listado motivo solicitud PCL
        if($parametro == 'lista_motivo_solicitud'){
            $listado_motivo_solicitud = sigmel_lista_motivo_solicitudes::on('sigmel_gestiones')
            ->select('Id_Solicitud', 'Nombre_solicitud')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_motivo_solicitud = json_decode(json_encode($listado_motivo_solicitud, true));
            return response()->json($info_listado_motivo_solicitud);
        }

         // Listado poblacion a calificar PCL
         if($parametro == 'lista_poblacion_calificar'){
            $listado_poblacion_califi = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Poblacion a calificar'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_poblacion_califi = json_decode(json_encode($listado_poblacion_califi, true));
            return response()->json($info_listado_poblacion_califi);
        }
        
        // Listado selectores agudeza visual (modal agudeza visual)
        if ($parametro == "agudeza_visual") {
            $listado_agudeza_visual = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'agudeza_visual'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_agudeza_visual = json_decode(json_encode($listado_agudeza_visual, true));
            return response()->json($info_listado_agudeza_visual);
        }

        // Listado cie diagnosticos motivo calificacion (Calificacion Tecnica)
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

        // Listado Origen CIE10 diagnosticos motivo calificacion (Calificacion Tecnica)
        if ($parametro == 'listado_OrgienCIE10') {
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

        // Listados agudeza auditiva
        if ($parametro == 'agudeza_auditiva') {
            $listado_Agudeza_auditiva = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'agudeza auditiva'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Agudeza_auditiva = json_decode(json_encode($listado_Agudeza_auditiva, true));
            return response()->json($info_listado_Agudeza_auditiva);
            
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

        // Listados tipo evento
        if ($parametro == 'lista_tipo_evento') {
            $listado_Tipo_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Id_Evento', 'Nombre_evento')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Tipo_evento = json_decode(json_encode($listado_Tipo_evento, true));
            return response()->json($info_listado_Tipo_evento);
            
        }

        // Listados Origen
        if ($parametro == 'lista_origen') {
            $listado_Origen = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Origen Cie10'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_listado_Origen = json_decode(json_encode($listado_Origen, true));
            return response()->json($info_listado_listado_Origen);
            
        }

        // Listados Tipo de enfermedad
        if ($parametro == 'lista_Tipo_enfermedad') {
            $listado_Tipo_enfermedad = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Tipo enfermedad'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_Tipo_enfermedad = json_decode(json_encode($listado_Tipo_enfermedad, true));
            return response()->json($info_listado_Tipo_enfermedad);
            
        }
        

    }

    //4 Formularios iniciales

    public function guardarDecretoDicRelaDocFundRe(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;
        $id_Evento_decreto = $request->Id_Evento_decreto;
        $id_Proceso_decreto = $request->Id_Proceso_decreto;
        $id_Asignacion_decreto = $request->Id_Asignacion_decreto;
        $origen_firme = $request->origen_firme;
        $origen_cobertura = $request->origen_cobertura; 
        $valorDataAuditivo = $request->valorDataAuditivo;
        $valorDataVisual = $request->valorDataVisual;
        $DataDeficiencias= $request->ValorDataDeficiencias;
        $ValorDataExamenesInterconsultas = $request->ValorDataExamenesInterconsultas;
        $ValorDataDiagnosticos = $request->ValorDataDiagnosticos;
        $ValorDataDeficienciasDecretoCero = $request->ValorDataDeficienciasDecretoCero;
        $ValorDataDeficienciasDecretotres = $request->ValorDataDeficienciasDecretotres;
        
        if ($origen_firme == 49 && $origen_cobertura == 51 || $origen_firme == 48 && $origen_cobertura == 51 || $origen_firme == 49 && $origen_cobertura == 50) {
            $banderaGuardarNoDecreto = $request->banderaGuardarNoDecreto;
            $decreto_califi = $request->decreto_califi;  
            
            if ($banderaGuardarNoDecreto == 'Guardar') {
                $datos_info_Nodecreto_eventos = [
                        'ID_Evento' => $id_Evento_decreto,
                        'Id_proceso' => $id_Proceso_decreto,
                        'Id_Asignacion' => $id_Asignacion_decreto,
                        'Origen_firme' => $origen_firme,
                        'Cobertura' => $origen_cobertura,
                        'Decreto_calificacion' => $decreto_califi, 
                        'Estado_decreto' => 'Abierto',
                        'Nombre_usuario' => $usuario,
                        'F_registro' => $date,
                ];
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')->insert($datos_info_Nodecreto_eventos);
    
                $mensajes = array(
                    "parametro" => 'agregar_Nodecreto_parte',                
                    "mensaje" => 'Guardado satisfactoriamente.'
                );        
        
                return json_decode(json_encode($mensajes, true));
                
            } elseif($banderaGuardarNoDecreto == 'Actualizar'){

                $datos_info_Nodecreto_eventos = [

                    'ID_Evento' => $id_Evento_decreto,
                    'Id_proceso' => $id_Proceso_decreto,
                    'Id_Asignacion' => $id_Asignacion_decreto,
                    'Origen_firme' => $origen_firme,
                    'Cobertura' => $origen_cobertura,
                    'Decreto_calificacion' => $decreto_califi,                    
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];

                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where([['ID_Evento', $id_Evento_decreto],['Id_Asignacion', $id_Asignacion_decreto]])->update($datos_info_Nodecreto_eventos);

                $mensajes = array(
                    "parametro" => 'actualizar_Nodecreto_parte',                
                    "mensaje2" => 'Actualizado satisfactoriamente.'
                );        
        
                return json_decode(json_encode($mensajes, true));
            }
            
            

        }elseif($origen_firme == 48 && $origen_cobertura == 50){
            if ($request->bandera_decreto_guardar_actualizar == 'Guardar') {
                
                $origen_firme = $request->origen_firme;
                $origen_cobertura = $request->origen_cobertura;
                $decreto_califi = $request->decreto_califi;
                $numeroDictamen = $request->numeroDictamen;
                $motivo_solicitud = $request->motivo_solicitud;
                $pcl_anterior = $request->pcl_anterior;
                $descripcion_nueva_calificacion = $request->descripcion_nueva_calificacion;         
                $relacion_documentos = $request->Relacion_Documentos;            
                if (!empty($relacion_documentos)) {
                    $total_relacion_documentos = implode(", ", $relacion_documentos);                
                }else{
                    $total_relacion_documentos = '';
                }
                $descripcion_otros = $request->descripcion_otros;
                $descripcion_enfermedad = $request->descripcion_enfermedad;
                $dominancia = $request->dominancia;
                $id_afiliado = $request->id_afiliado;

                $datos_info_decreto_eventos = [
                    'ID_Evento' => $id_Evento_decreto,
                    'Id_proceso' => $id_Proceso_decreto,
                    'Id_Asignacion' => $id_Asignacion_decreto,
                    'Origen_firme' => $origen_firme,
                    'Cobertura' => $origen_cobertura,
                    'Decreto_calificacion' => $decreto_califi,
                    'Numero_dictamen' => $numeroDictamen,
                    'PCL_anterior' => $pcl_anterior,
                    'Descripcion_nueva_calificacion' => $descripcion_nueva_calificacion,
                    'Relacion_documentos' => $total_relacion_documentos,
                    'Otros_relacion_doc' => $descripcion_otros,
                    'Descripcion_enfermedad_actual' => $descripcion_enfermedad,
                    'Estado_decreto' => 'Abierto',
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];
    
                $dato_info_pericial_eventos = [
                    'Id_motivo_solicitud' => $motivo_solicitud,
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')->insert($datos_info_decreto_eventos);

                sleep(3);

                sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_pericial_eventos); 

                sleep(2);
                
                // Actualización de la dominancia
                $dato_dominancia = [
                    'Id_dominancia' => $dominancia
                ];

                sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
                ->where([
                    ['Id_Afiliado', $id_afiliado],
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_dominancia);

                // Examenes Interconsultas
                if(!empty($ValorDataExamenesInterconsultas)){
                    $registrosDataExamenesInteconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                    ->select('ID_evento', 'Id_Asignacion', 'Id_proceso', 'F_examen_interconsulta', 'Nombre_examen_interconsulta', 'Descripcion_resultado', 
                    'Estado', 'Estado_Recalificacion')
                    ->whereIn('Id_Examenes_interconsultas', $ValorDataExamenesInterconsultas)->get();             
                    if (!empty($registrosDataExamenesInteconsultas[0]->ID_evento)) {
                        
                        // sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                        // ->whereIn('Id_Examenes_interconsultas', $ValorDataExamenesInterconsultas)
                        // ->update(['Estado_Recalificacion' => 'Inactivo']);                
                        
                        sleep(3);
                        
                        foreach ($registrosDataExamenesInteconsultas as $registro) { 
                            $registro->Id_Asignacion = $id_Asignacion_decreto;
                            $registro->Estado = 'Inactivo';
                            $registro->Estado_Recalificacion = 'Activo';
                            $registro->Nombre_usuario = $usuario;
                            $registro->F_registro = $date;
                            sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
                            ->insert($registro->toArray());
                        }
                    } 
                }

                // Diagnosticos CIE10

                if(!empty($ValorDataDiagnosticos)){
                    $registrosDataDiagnosticos = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
                    ->select('ID_evento', 'Id_Asignacion', 'Id_proceso', 'CIE10', 'Nombre_CIE10', 'Origen_CIE10', 'Lateralidad_CIE10', 'Deficiencia_motivo_califi_condiciones', 'Principal',
                    'Estado', 'Estado_Recalificacion')
                    ->whereIn('Id_Diagnosticos_motcali', $ValorDataDiagnosticos)->get();             
                    if (!empty($registrosDataDiagnosticos[0]->ID_evento)) {
                        
                        // sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
                        // ->whereIn('Id_Diagnosticos_motcali', $ValorDataDiagnosticos)
                        // ->update(['Estado_Recalificacion' => 'Inactivo']);                
                        
                        sleep(3);
                        
                        foreach ($registrosDataDiagnosticos as $registro) { 
                            $registro->Id_Asignacion = $id_Asignacion_decreto;
                            $registro->Estado = 'Inactivo';
                            $registro->Estado_Recalificacion = 'Activo';
                            $registro->Nombre_usuario = $usuario;
                            $registro->F_registro = $date;
                            sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
                            ->insert($registro->toArray());
                        }
                    } 
                }

                // Deficiencias del sistema 1507
                //print_r($DataDeficiencias);
                if (!empty($DataDeficiencias)) {
                    $registrosDataDeficiencias = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                    ->select('ID_evento', 'Id_Asignacion', 'Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU', 'CAT', 
                    'Clase_Final', 'Dx_Principal', 'MSD', 'Tabla1999', 'Titulo_tabla1999', 'Dominancia', 'Deficiencia', 'Total_deficiencia', 
                    'Estado', 'Estado_Recalificacion')
                    ->whereIn('Id_Deficiencia', $DataDeficiencias)->get();             
                    if (!empty($registrosDataDeficiencias[0]->ID_evento)) {
                        
                        // sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                        // ->whereIn('Id_Deficiencia', $DataDeficiencias)
                        // ->update(['Estado_Recalificacion' => 'Inactivo']);                
                        
                        sleep(3);
                        
                        foreach ($registrosDataDeficiencias as $registro) { 
                            $registro->Id_Asignacion = $id_Asignacion_decreto;
                            $registro->Estado = 'Inactivo';
                            $registro->Estado_Recalificacion = 'Activo';
                            $registro->Nombre_usuario = $usuario;
                            $registro->F_registro = $date;
                            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                            ->insert($registro->toArray());
                        }
                    } 
                } 

                // Deficiencias Decreto Cero
                if (!empty($ValorDataDeficienciasDecretoCero)) {
                    $registrosDataDeficienciasDecretoCero = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                    ->select('ID_evento', 'Id_Asignacion', 'Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU', 'CAT', 
                    'Clase_Final', 'Dx_Principal', 'MSD', 'Tabla1999', 'Titulo_tabla1999', 'Total_deficiencia', 'Estado', 'Estado_Recalificacion')
                    ->whereIn('Id_Deficiencia', $ValorDataDeficienciasDecretoCero)->get();             
                    if (!empty($registrosDataDeficienciasDecretoCero[0]->ID_evento)) {
                        
                        // sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                        // ->whereIn('Id_Deficiencia', $ValorDataDeficienciasDecretoCero)
                        // ->update(['Estado_Recalificacion' => 'Inactivo']);                
                        
                        sleep(3);
                        
                        foreach ($registrosDataDeficienciasDecretoCero as $registro) { 
                            $registro->Id_Asignacion = $id_Asignacion_decreto;
                            $registro->Estado = 'Inactivo';
                            $registro->Estado_Recalificacion = 'Activo';
                            $registro->Nombre_usuario = $usuario;
                            $registro->F_registro = $date;
                            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                            ->insert($registro->toArray());
                        }
                    } 
                }

                // Deficiencias Decreto 1999
                if(!empty($ValorDataDeficienciasDecretotres)){
                    $registrosDataDeficienciasDecretotres = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                    ->select('ID_evento', 'Id_Asignacion', 'Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU', 'CAT', 
                    'Clase_Final', 'Dx_Principal', 'MSD', 'Tabla1999', 'Titulo_tabla1999', 'Total_deficiencia', 'Estado', 'Estado_Recalificacion')
                    ->whereIn('Id_Deficiencia', $ValorDataDeficienciasDecretotres)->get();             
                    if (!empty($registrosDataDeficienciasDecretotres[0]->ID_evento)) {
                        
                        // sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                        // ->whereIn('Id_Deficiencia', $ValorDataDeficienciasDecretotres)
                        // ->update(['Estado_Recalificacion' => 'Inactivo']);                
                        
                        sleep(3);
                        
                        foreach ($registrosDataDeficienciasDecretotres as $registro) { 
                            $registro->Id_Asignacion = $id_Asignacion_decreto;
                            $registro->Estado = 'Inactivo';
                            $registro->Estado_Recalificacion = 'Activo';
                            $registro->Nombre_usuario = $usuario;
                            $registro->F_registro = $date;
                            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                            ->insert($registro->toArray());
                        }
                    }
                }

                //Agudeza Auditiva Recalificacion
                sleep(3);
                if (!empty($valorDataAuditivo)) {
                    // $array_datos_EstadoReca_auditivo = [
                    //     'Estado_Recalificacion' => 'Inactivo', 
                    // ];
                    // sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
                    // ->where([['ID_evento',$id_Evento_decreto ], ['Id_Agudeza_auditiva',$valorDataAuditivo]])
                    // ->update($array_datos_EstadoReca_auditivo);
    
                    $array_datos_agudeza_auditiva_calif = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
                    ->where([['ID_evento',$id_Evento_decreto ], ['Id_Agudeza_auditiva', $valorDataAuditivo]])->get();
    
                    if (!empty($array_datos_agudeza_auditiva_calif[0]->Id_Agudeza_auditiva)) {
                        
                        
                        $ID_evento = $array_datos_agudeza_auditiva_calif[0]->ID_evento;
                        $Id_Asignacion = $array_datos_agudeza_auditiva_calif[0]->Id_Asignacion;
                        $Id_proceso = $array_datos_agudeza_auditiva_calif[0]->Id_proceso;
                        $Oido_Izquierdo = $array_datos_agudeza_auditiva_calif[0]->Oido_Izquierdo;
                        $Oido_Derecho = $array_datos_agudeza_auditiva_calif[0]->Oido_Derecho;
                        $Deficiencia_monoaural_izquierda = $array_datos_agudeza_auditiva_calif[0]->Deficiencia_monoaural_izquierda;
                        $Deficiencia_monoaural_derecha = $array_datos_agudeza_auditiva_calif[0]->Deficiencia_monoaural_derecha;
                        $Deficiencia_binaural = $array_datos_agudeza_auditiva_calif[0]->Deficiencia_binaural;
                        $Adicion_tinnitus = $array_datos_agudeza_auditiva_calif[0]->Adicion_tinnitus;
                        $Dx_Principal = $array_datos_agudeza_auditiva_calif[0]->Dx_Principal;
                        $Deficiencia = $array_datos_agudeza_auditiva_calif[0]->Deficiencia;
                        $Estado = $array_datos_agudeza_auditiva_calif[0]->Estado;
                        $Estado_Recalificacion = $array_datos_agudeza_auditiva_calif[0]->Estado_Recalificacion;
                        $Nombre_usuario = $array_datos_agudeza_auditiva_calif[0]->Nombre_usuario;
                        $F_registro = $array_datos_agudeza_auditiva_calif[0]->F_registro;
    
                        $array_datos_agudeza_auditiva_Reca = [
                            'ID_evento' => $ID_evento,
                            'Id_Asignacion' => $id_Asignacion_decreto,
                            'Id_proceso' => $Id_proceso,
                            'Oido_Izquierdo' => $Oido_Izquierdo,
                            'Oido_Derecho' => $Oido_Derecho,
                            'Deficiencia_monoaural_izquierda' => $Deficiencia_monoaural_izquierda,
                            'Deficiencia_monoaural_derecha' => $Deficiencia_monoaural_derecha,
                            'Deficiencia_binaural' => $Deficiencia_binaural,
                            'Adicion_tinnitus' => $Adicion_tinnitus,
                            'Dx_Principal' => $Dx_Principal,
                            'Deficiencia' => $Deficiencia,
                            'Estado' => 'Inactivo',
                            'Estado_Recalificacion' => 'Activo',                        
                            'Nombre_usuario' => $Nombre_usuario,                        
                            'F_registro' => $F_registro,
                        ];
    
                        sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')->insert($array_datos_agudeza_auditiva_Reca);
        
                        
                    }
                }
                
                //Agudeza Visual Recalificacion

                if (!empty($decreto_califi) && $decreto_califi == 1) {

                    if (!empty($valorDataVisual)) {
                        sleep(3);
                        $array_datos_agudeza_visual_calif = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
                        ->where([['ID_evento',$id_Evento_decreto ], ['Id_agudeza',$valorDataVisual]])->get();
        
                        $array_datos_agudeza_visualre_calif = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                        ->where([['ID_evento_re',$id_Evento_decreto ], ['Id_agudeza_re',$valorDataVisual]])->get();

                        /* echo $valorDataVisual;
                        echo '<hr>';
                        echo '<pre>';
                            print_r($array_datos_agudeza_visualre_calif);
                        echo '</pre>'; */
                        
                        if(!empty($array_datos_agudeza_visualre_calif[0]->Id_agudeza_re)){
            
                            sleep(3);
                            /* $array_datos_EstadoReca_visual = [
                                'Estado_Recalificacion' => 'Inactivo', 
                            ];
        
                            sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                            ->where([['ID_evento_re',$id_Evento_decreto ], ['Id_agudeza_re',$valorDataVisual]])
                            ->update($array_datos_EstadoReca_visual); */
        
                            $Id_agudeza_visre = $array_datos_agudeza_visualre_calif[0]->Id_agudeza_re;
                            $ID_evento_re = $array_datos_agudeza_visualre_calif[0]->ID_evento_re;
                            $Id_Asignacion_re = $array_datos_agudeza_visualre_calif[0]->Id_Asignacion_re;
                            $Id_proceso_re = $array_datos_agudeza_visualre_calif[0]->Id_proceso_re;
                            $Ceguera_Total_re = $array_datos_agudeza_visualre_calif[0]->Ceguera_Total_re;
                            $Agudeza_Ojo_Izq_re = $array_datos_agudeza_visualre_calif[0]->Agudeza_Ojo_Izq_re;
                            $Agudeza_Ojo_Der_re = $array_datos_agudeza_visualre_calif[0]->Agudeza_Ojo_Der_re;
                            $Agudeza_Ambos_Ojos_re = $array_datos_agudeza_visualre_calif[0]->Agudeza_Ambos_Ojos_re;
                            $PAVF_re = $array_datos_agudeza_visualre_calif[0]->PAVF_re;
                            $DAV_re = $array_datos_agudeza_visualre_calif[0]->DAV_re;
                            $Campo_Visual_Ojo_Izq_re = $array_datos_agudeza_visualre_calif[0]->Campo_Visual_Ojo_Izq_re;
                            $Campo_Visual_Ojo_Der_re = $array_datos_agudeza_visualre_calif[0]->Campo_Visual_Ojo_Der_re;
                            $Campo_Visual_Ambos_Ojos_re = $array_datos_agudeza_visualre_calif[0]->Campo_Visual_Ambos_Ojos_re;
                            $CVF_re = $array_datos_agudeza_visualre_calif[0]->CVF_re;
                            $DCV_re = $array_datos_agudeza_visualre_calif[0]->DCV_re;
                            $DSV_re = $array_datos_agudeza_visualre_calif[0]->DSV_re;
                            $Dx_Principal_re = $array_datos_agudeza_visualre_calif[0]->Dx_Principal_re;
                            $Deficiencia_re = $array_datos_agudeza_visualre_calif[0]->Deficiencia_re;
                            $Nombre_usuario = $array_datos_agudeza_visualre_calif[0]->Nombre_usuario;
                            $F_registro = $array_datos_agudeza_visualre_calif[0]->F_registro;
        
                            $array_datos_agudeza_visual_Reca = [
                                'ID_evento_re' => $ID_evento_re,
                                'Id_Asignacion_re' => $id_Asignacion_decreto,
                                'Id_proceso_re' => $Id_proceso_re,
                                'Ceguera_Total_re' => $Ceguera_Total_re,
                                'Agudeza_Ojo_Izq_re' => $Agudeza_Ojo_Izq_re,
                                'Agudeza_Ojo_Der_re' => $Agudeza_Ojo_Der_re,
                                'Agudeza_Ambos_Ojos_re' => $Agudeza_Ambos_Ojos_re,
                                'PAVF_re' => $PAVF_re,
                                'DAV_re' => $DAV_re,
                                'Campo_Visual_Ojo_Izq_re' => $Campo_Visual_Ojo_Izq_re,
                                'Campo_Visual_Ojo_Der_re' => $Campo_Visual_Ojo_Der_re,
                                'Campo_Visual_Ambos_Ojos_re' => $Campo_Visual_Ambos_Ojos_re,
                                'CVF_re' => $CVF_re,
                                'DCV_re' => $DCV_re,
                                'DSV_re' => $DSV_re,
                                'Dx_Principal_re' => $Dx_Principal_re,
                                'Deficiencia_re' => $Deficiencia_re,
                                'Estado' => 'Inactivo',
                                'Nombre_usuario' => $Nombre_usuario,
                                'F_registro' => $F_registro,
                            ];

                            
                            sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')->insert($array_datos_agudeza_visual_Reca);
                            
                            sleep(3);                            
        
                            $array_datos_agudeza_visualre_calif = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                            ->select('Id_agudeza_re')->where([['ID_evento_re',$id_Evento_decreto ], ['Id_Asignacion_re',$id_Asignacion_decreto]])
                            ->get();
        
                            if(!empty($array_datos_agudeza_visualre_calif[0]->Id_agudeza_re)){                        
                                $newId_AgudezaRe = $array_datos_agudeza_visualre_calif[0]->Id_agudeza_re;
                            }
                            sleep(3);
                                    
                            $array_datos_ojo_derecho = sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')
                            ->select('Id_agudeza', 'InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5',
                            'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10', 'Nombre_usuario',
                            'F_registro')->where([['Id_agudeza',$Id_agudeza_visre]])->get();
                            
                            foreach ($array_datos_ojo_derecho as $registro) {  
                                $registro->Id_agudeza = $newId_AgudezaRe;
                                sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')
                                ->insert($registro->toArray());
                            }
                            sleep(3);                              
        
                            $array_datos_ojo_izquierdo = sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')
                            ->select('Id_agudeza', 'InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5',
                            'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10', 'Nombre_usuario',
                            'F_registro')->where([['Id_agudeza',$Id_agudeza_visre]])->get();
        
                            foreach ($array_datos_ojo_izquierdo as $registro) {                            
                                $registro->Id_agudeza = $newId_AgudezaRe;
                                sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')
                                ->insert($registro->toArray());
                            }
                            $mensajes = array(
                                "parametro" => 'agregar_decreto_parte',
                                "parametro2" => 'guardo',
                                "mensaje" => 'Guardado satisfactoriamente.'
                            );        
                    
                            return json_decode(json_encode($mensajes, true));
                                
                        }                        
                    }elseif(empty($valorDataVisual)){
                        sleep(3);

                        $array_datos_maxid_visualInactivo = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                        ->select('Id_agudeza_re')
                        ->where([['ID_evento_re',$id_Evento_decreto ]])
                        ->max('Id_agudeza_re');

                        $array_datos_maxid_visualestado = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                        ->select('Estado_Recalificacion')
                        ->where([['Id_agudeza_re',$array_datos_maxid_visualInactivo ]])
                        ->get();

                        $valida_Evento_enOtrosDecretos = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                        ->select('ID_Evento', 'Id_proceso', 'Id_Asignacion', 'Decreto_calificacion')
                        ->where([['ID_Evento',$id_Evento_decreto], ['Id_proceso', $id_Proceso_decreto]])
                        ->where(function ($query) {
                            $query->where('Decreto_calificacion', 2)
                                ->orWhere('Decreto_calificacion', 3);
                        })
                        ->get();

                        if (!empty($array_datos_maxid_visualestado[0]->Estado_Recalificacion) && $array_datos_maxid_visualestado[0]->Estado_Recalificacion == 'Inactivo') {
                            $mensajes = array(
                                "parametro" => 'agregar_decreto_parte',
                                "parametro2" => 'guardo',
                                "mensaje" => 'Guardado satisfactoriamente.'
                            );        
                    
                            return json_decode(json_encode($mensajes, true));   

                        }elseif (!empty($valida_Evento_enOtrosDecretos[0]->Decreto_calificacion) && $valida_Evento_enOtrosDecretos[0]->Decreto_calificacion <> 1) {
                            $mensajes = array(
                                "parametro" => 'agregar_decreto_parte',
                                "parametro2" => 'guardo',
                                "mensaje" => 'Guardado satisfactoriamente.'
                            );        
                    
                            return json_decode(json_encode($mensajes, true));                                                       
                        
                        } else {
                            
                            $array_datos_agudeza_visual_calif = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
                            ->where([['ID_evento',$id_Evento_decreto]])->get();
            
                            $array_datos_agudeza_visualre_calif = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                            ->where([['ID_evento_re',$id_Evento_decreto ], ['Id_agudeza_re',$valorDataVisual]])->get();
                            
                            if(!empty($array_datos_agudeza_visual_calif[0]->ID_evento) && empty($array_datos_agudeza_visualre_calif[0]->Id_agudeza_re)){
            
                                
                                $Id_agudeza_vis = $array_datos_agudeza_visual_calif[0]->Id_agudeza;
                                $ID_evento_re = $array_datos_agudeza_visual_calif[0]->ID_evento;
                                $Id_Asignacion_re = $array_datos_agudeza_visual_calif[0]->Id_Asignacion;
                                $Id_proceso_re = $array_datos_agudeza_visual_calif[0]->Id_proceso;
                                $Ceguera_Total_re = $array_datos_agudeza_visual_calif[0]->Ceguera_Total;
                                $Agudeza_Ojo_Izq_re = $array_datos_agudeza_visual_calif[0]->Agudeza_Ojo_Izq;
                                $Agudeza_Ojo_Der_re = $array_datos_agudeza_visual_calif[0]->Agudeza_Ojo_Der;
                                $Agudeza_Ambos_Ojos_re = $array_datos_agudeza_visual_calif[0]->Agudeza_Ambos_Ojos;
                                $PAVF_re = $array_datos_agudeza_visual_calif[0]->PAVF;
                                $DAV_re = $array_datos_agudeza_visual_calif[0]->DAV;
                                $Campo_Visual_Ojo_Izq_re = $array_datos_agudeza_visual_calif[0]->Campo_Visual_Ojo_Izq;
                                $Campo_Visual_Ojo_Der_re = $array_datos_agudeza_visual_calif[0]->Campo_Visual_Ojo_Der;
                                $Campo_Visual_Ambos_Ojos_re = $array_datos_agudeza_visual_calif[0]->Campo_Visual_Ambos_Ojos;
                                $CVF_re = $array_datos_agudeza_visual_calif[0]->CVF;
                                $DCV_re = $array_datos_agudeza_visual_calif[0]->DCV;
                                $DSV_re = $array_datos_agudeza_visual_calif[0]->DSV;
                                $Dx_Principal_re = $array_datos_agudeza_visual_calif[0]->Dx_Principal;
                                $Deficiencia_re = $array_datos_agudeza_visual_calif[0]->Deficiencia;
                                $Nombre_usuario = $array_datos_agudeza_visual_calif[0]->Nombre_usuario;
                                $F_registro = $array_datos_agudeza_visual_calif[0]->F_registro;
            
                                $array_datos_agudeza_visual_Reca = [
                                    'ID_evento_re' => $ID_evento_re,
                                    'Id_Asignacion_re' => $id_Asignacion_decreto,
                                    'Id_proceso_re' => $Id_proceso_re,
                                    'Ceguera_Total_re' => $Ceguera_Total_re,
                                    'Agudeza_Ojo_Izq_re' => $Agudeza_Ojo_Izq_re,
                                    'Agudeza_Ojo_Der_re' => $Agudeza_Ojo_Der_re,
                                    'Agudeza_Ambos_Ojos_re' => $Agudeza_Ambos_Ojos_re,
                                    'PAVF_re' => $PAVF_re,
                                    'DAV_re' => $DAV_re,
                                    'Campo_Visual_Ojo_Izq_re' => $Campo_Visual_Ojo_Izq_re,
                                    'Campo_Visual_Ojo_Der_re' => $Campo_Visual_Ojo_Der_re,
                                    'Campo_Visual_Ambos_Ojos_re' => $Campo_Visual_Ambos_Ojos_re,
                                    'CVF_re' => $CVF_re,
                                    'DCV_re' => $DCV_re,
                                    'DSV_re' => $DSV_re,
                                    'Dx_Principal_re' => $Dx_Principal_re,
                                    'Deficiencia_re' => $Deficiencia_re,
                                    'Estado' => 'Inactivo',
                                    'Nombre_usuario' => $Nombre_usuario,
                                    'F_registro' => $F_registro,
                                ];
            
                                sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')->insert($array_datos_agudeza_visual_Reca);
                                
                                sleep(3);                            
        
                                $array_datos_agudeza_visualre_calif = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
                                ->select('Id_agudeza_re')->where([['ID_evento_re',$id_Evento_decreto ], ['Id_Asignacion_re',$id_Asignacion_decreto]])
                                ->get();
            
                                if(!empty($array_datos_agudeza_visualre_calif[0]->Id_agudeza_re)){                        
                                    $newId_AgudezaRe = $array_datos_agudeza_visualre_calif[0]->Id_agudeza_re;
                                }
                                sleep(3);
            
                                $array_datos_ojo_derecho = sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')
                                ->select('Id_agudeza', 'InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5',
                                'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10', 'Nombre_usuario',
                                'F_registro')->where([['Id_agudeza',$Id_agudeza_vis]])->get();
            
                                foreach ($array_datos_ojo_derecho as $registro) {   
                                    $registro->Id_agudeza = $newId_AgudezaRe;                         
                                    sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')
                                    ->insert($registro->toArray());
                                }
            
                                $array_datos_ojo_izquierdo = sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')
                                ->select('Id_agudeza', 'InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5',
                                'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10', 'Nombre_usuario',
                                'F_registro')->where([['Id_agudeza',$Id_agudeza_vis]])->get();
            
                                foreach ($array_datos_ojo_izquierdo as $registro) {  
                                    $registro->Id_agudeza = $newId_AgudezaRe;                          
                                    sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')
                                    ->insert($registro->toArray());
                                }
                                sleep(3);
                                $mensajes = array(
                                    "parametro" => 'agregar_decreto_parte',
                                    "parametro2" => 'guardo',
                                    "mensaje" => 'Guardado satisfactoriamente.'
                                );        
                        
                                return json_decode(json_encode($mensajes, true));
                        }
                        

                        }
                    }
                }
                
                sleep(3);
                $mensajes = array(
                    "parametro" => 'agregar_decreto_parte',
                    "parametro2" => 'guardo',
                    "mensaje" => 'Guardado satisfactoriamente.'
                );        
        
               return json_decode(json_encode($mensajes, true));  
    
            }elseif($request->bandera_decreto_guardar_actualizar == 'Actualizar'){
    
                $origen_firme = $request->origen_firme;
                $origen_cobertura = $request->origen_cobertura;
                $decreto_califi = $request->decreto_califi;
                $numeroDictamen = $request->numeroDictamen;
                $motivo_solicitud = $request->motivo_solicitud;  
                $descripcion_nueva_calificacion = $request->descripcion_nueva_calificacion;       
                $relacion_documentos = $request->Relacion_Documentos;
                if (!empty($relacion_documentos)) {
                    $total_relacion_documentos = implode(", ", $relacion_documentos);                
                }else{
                    $total_relacion_documentos = '';
                }
                $descripcion_otros = $request->descripcion_otros;
                $descripcion_enfermedad = $request->descripcion_enfermedad;
                $dominancia = $request->dominancia;
                $id_afiliado = $request->id_afiliado;

                $datos_info_decreto_eventos = [
                    'ID_Evento' => $id_Evento_decreto,
                    'Id_proceso' => $id_Proceso_decreto,
                    'Id_Asignacion' => $id_Asignacion_decreto,
                    'Origen_firme' => $origen_firme,
                    'Cobertura' => $origen_cobertura,
                    'Decreto_calificacion' => $decreto_califi,
                    'Numero_dictamen' => $numeroDictamen,
                    'Descripcion_nueva_calificacion' => $descripcion_nueva_calificacion,
                    'Relacion_documentos' => $total_relacion_documentos,
                    'Otros_relacion_doc' => $descripcion_otros,
                    'Descripcion_enfermedad_actual' => $descripcion_enfermedad,
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];
    
                $dato_info_pericial_eventos = [
                    'Id_motivo_solicitud' => $motivo_solicitud,
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where([['ID_Evento', $id_Evento_decreto], ['Id_Asignacion', $id_Asignacion_decreto]])->update($datos_info_decreto_eventos);

                sleep(2);

                sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_pericial_eventos);

                sleep(2);

                // Actualización de la dominancia
                $dato_dominancia = [
                    'Id_dominancia' => $dominancia
                ];

                sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
                ->where([
                    ['Id_Afiliado', $id_afiliado],
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_dominancia);
        
                $mensajes = array(
                    "parametro" => 'update_decreto_parte',
                    "mensaje2" => 'Actualizado satisfactoriamente.'
                ); 
    
                return json_decode(json_encode($mensajes, true));
            }
        } 

    }
    // Examenes interconsultas
    public function guardarExamenesInterconsultaRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $estado = $request->Estado;

        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
        $max_id = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->max('Id_Examenes_interconsultas');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_examenes_interconsultas_eventos AUTO_INCREMENT = ".($max_id));
        }
        // Captura del array de los datos de la tabla
        $array_examenes_interconsultas = $request->datos_finales_examenes_interconsultas;

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];
        foreach ($array_examenes_interconsultas as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);
            //array_unshift($subarray_datos, $request->Estado);

            $subarray_datos[] = $estado;
            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_examenes_interconsultas_eventos
        $array_tabla_examen_interconsulta = ['ID_evento','Id_Asignacion','Id_proceso',
        'F_examen_interconsulta','Nombre_examen_interconsulta','Descripcion_resultado','Estado',
        'Nombre_usuario','F_registro'];

        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_tabla_examen_interconsulta, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar_examen) {
            sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')->insert($insertar_examen);
        } 

        $mensajes = array(
            "parametro" => 'inserto_informacion',
            "mensaje" => 'Exámen e interconsulta guardado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function eliminarExamenInterconsultaRe(Request $request){
        $id_fila_examen = $request->fila;
        $fila_actualizar = [            
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')->where('Id_Examenes_interconsultas', $id_fila_examen)
        ->update($fila_actualizar);

        /* $total_registros_examen = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count(); */

        $mensajes = array(
            "parametro" => 'fila_examen_eliminada',
            //'total_registros' => $total_registros_examen,
            "mensaje" => 'Exámen e Interconsulta eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }
    // Diagnosticos CIE10
    public function guardarDiagnosticoMotivoCalificacionRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Estado = $request->Estado;

        // Seteo del autoincrement para mantener el primary key siempre consecutivo.
        $max_id = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->max('Id_Diagnosticos_motcali');
        if ($max_id <> "") {
            DB::connection('sigmel_gestiones')
            ->statement("ALTER TABLE sigmel_informacion_diagnosticos_eventos AUTO_INCREMENT = ".($max_id));
        }

        // Captura del array de los datos de la tabla
        $array_diagnosticos_motivo_calificacion = $request->datos_finales_diagnosticos_moticalifi;

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];
        foreach ($array_diagnosticos_motivo_calificacion as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);

            $subarray_datos[] = $Estado;
            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
        $array_tabla_diagnosticos_motivo_calificacion = ['ID_evento','Id_Asignacion','Id_proceso',
        'CIE10','Nombre_CIE10','Lateralidad_CIE10','Origen_CIE10', 'Principal', 'Deficiencia_motivo_califi_condiciones','Estado','Nombre_usuario','F_registro'];

        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_tabla_diagnosticos_motivo_calificacion, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar_diagnostico) {
            sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->insert($insertar_diagnostico);
        } 

        $mensajes = array(
            "parametro" => 'inserto_diagnostico',
            "mensaje" => 'Diagnóstico motivo de calificación guardado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }
       
    public function eliminarDiagnosticoMotivoCalificacionRe(Request $request){
        $id_fila_diagnostico = $request->fila;
        $fila_actualizar = [
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->where('Id_Diagnosticos_motcali', $id_fila_diagnostico)
        ->update($fila_actualizar);

        /* $total_registros_diagnostico = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count(); */

        $mensajes = array(
            "parametro" => 'fila_diagnostico_eliminada',
            //'total_registros' => $total_registros_diagnostico,
            "mensaje" => 'Diagnóstico motivo de calificación eliminado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    public function actualizarDxPrincipalDiagnosticoRe(Request $request){
        
        $fila = $request->fila;
        $banderaDxPrincipalDA = $request->banderaDxPrincipalDA;
        $Id_evento = $request->Id_evento;      
        
        if ($banderaDxPrincipalDA == 'SiDxPrincipal_diagnostico') {
            $fila_actulizar = [
                'Principal' => 'Si'
            ];
            
            sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
            ->where([
                ['Id_Diagnosticos_motcali', $fila],
                ['ID_evento', $Id_evento],
                ['Estado_Recalificacion', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalDiagnostico_agregado',
                "mensaje" => 'Dx Principal Diagnósticos motivo de calificación agreagado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));  

        }elseif($banderaDxPrincipalDA == 'NoDxPrincipal_diagnostico'){           

            $fila_actulizar = [
                'Principal' => 'No'
            ];
    
            sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
            ->where([
                ['Id_Diagnosticos_motcali', $fila],
                ['ID_evento', $Id_evento],
                ['Estado_Recalificacion', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalDiagnostico_eliminado',
                "mensaje" => 'Dx Principal Diagnósticos motivo de calificación eliminado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Deficiencias por alteraciones 

    public function ListadoSelectoresDefiAlteracionesRe(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $parametro = $request->parametro;

        if($parametro == 'listado_tablas_decreto'){

            $listado_tablas_decreto_1507 = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'Nombre_tabla')->where([['Estado', '=', 'Activo']])->get();
            

            $info_listado_tablas_decreto_1507 = json_decode(json_encode($listado_tablas_decreto_1507, true));
            return response()->json($info_listado_tablas_decreto_1507);
        };

        if ($parametro == "nombre_tabla") {
            $nombre_tabla = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Nombre_tabla', 'Ident_tabla')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_nombre_tabla = json_decode(json_encode($nombre_tabla, true));
            return response()->json($info_nombre_tabla);
        };

        if ($parametro == "selector_FP") {
            $selector_FP = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'FP')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_FP = json_decode(json_encode($selector_FP, true));
            return response()->json($info_selector_FP);
        }

        if ($parametro == "selector_CFM1") {
            $selector_CFM1 = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'CFM1')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_CFM1 = json_decode(json_encode($selector_CFM1, true));
            return response()->json($info_selector_CFM1);
        }

        if ($parametro == "selector_CFM2") {
            $selector_CFM2 = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'CFM2')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_CFM2 = json_decode(json_encode($selector_CFM2, true));
            return response()->json($info_selector_CFM2);
        }

        if ($parametro == "selector_FU") {
            $selector_FU = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'FU')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_FU = json_decode(json_encode($selector_FU, true));
            return response()->json($info_selector_FU);
        }

        if ($parametro == "selector_CAT") {
            $selector_CAT = sigmel_lista_tablas_1507_decretos::on('sigmel_gestiones')
            ->select('Id_tabla', 'Ident_tabla', 'CAT')
            ->where('Id_tabla', $request->Id_tabla)->get();

            $info_selector_CAT = json_decode(json_encode($selector_CAT, true));
            return response()->json($info_selector_CAT);
        }

        if ($parametro == "MSD") {
            $msd = sigmel_lista_clases_decretos::on('sigmel_gestiones')
            ->select('MSD')->where('Id_tabla', $request->Id_tabla)->get();
        }

        $info_msd = json_decode(json_encode($msd, true));
        return response()->json($info_msd);

    }

    public function consultaValorDeficienciaRe(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $string_deficiencia = sigmel_lista_clases_decretos::on('sigmel_gestiones')
        ->select($request->columna)->where('Id_tabla', $request->Id_tabla)->get();

        $info_string_deficiencia = json_decode(json_encode($string_deficiencia, true));
        return response()->json($info_string_deficiencia);
        
    }

    public function GuardarDeficienciaAlteracionesRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Estado = $request->Estado;
        // CAPTURA DE DATOS DE LA DEFICIENCIA 
        $array_datos = $request->datos_finales_deficiencias_alteraciones;

        foreach ($array_datos as $subarray) {
            $cantidad_elementos = count($subarray);
        
            // Verificar la cantidad de elementos en el subarray
            if ($cantidad_elementos == 10) {
                // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
                $array_datos_organizados = [];
        
                // foreach ($array_datos as $subarray_datos) {
        
                    array_unshift($subarray, $request->Id_proceso);
                    array_unshift($subarray, $request->Id_Asignacion);
                    array_unshift($subarray, $request->Id_evento);
        
                    $subarray[] = $Estado;
                    $subarray[] = $nombre_usuario;
                    $subarray[] = $date;
        
                    array_push($array_datos_organizados, $subarray);
                // }
        
                // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
                
                $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU',	'CAT', 
                'MSD', 'Dominancia', 'Deficiencia', 'Total_deficiencia', 'Estado', 'Nombre_usuario','F_registro'];
                
                // Combinación de los campos de la tabla con los datos
                $array_datos_con_keys = [];
                foreach ($array_datos_organizados as $subarray_datos_organizados) {
                    array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
                }
        
                // Inserción de la información
                foreach ($array_datos_con_keys as $insertar) {
                    sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->insert($insertar);
                }        
                           
                sleep(2);

                $mensajes = array(
                    "parametro" => 'inserto_informacion_deficiencias',
                    "mensaje" => 'Deficiencia guardada satisfactoriamente.'
                );
            } 
            elseif ($cantidad_elementos == 11) {
                // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
                $array_datos_organizados = [];
        
                // foreach ($array_datos as $subarray_datos) {
        
                    array_unshift($subarray, $request->Id_proceso);
                    array_unshift($subarray, $request->Id_Asignacion);
                    array_unshift($subarray, $request->Id_evento);
        
                    $subarray[] = $Estado;
                    $subarray[] = $nombre_usuario;
                    $subarray[] = $date;
        
                    array_push($array_datos_organizados, $subarray);
                // }
        
                // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
                
                $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU',	'CAT', 'Clase_Final', 
                'MSD', 'Dominancia', 'Deficiencia', 'Total_deficiencia', 'Estado', 'Nombre_usuario','F_registro'];
                
                // Combinación de los campos de la tabla con los datos
                $array_datos_con_keys = [];
                foreach ($array_datos_organizados as $subarray_datos_organizados) {
                    array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
                }
        
                // Inserción de la información
                foreach ($array_datos_con_keys as $insertar) {
                    sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->insert($insertar);
                }
                sleep(2);
                
                $mensajes = array(
                    "parametro" => 'inserto_informacion_deficiencias',
                    "mensaje" => 'Deficiencia guardada satisfactoriamente.'
                );
            }
            else{
                sleep(2);
                $mensajes = array(
                    "parametro" => 'inserto_informacion_deficiencias',
                    "mensaje" => 'Deficiencia NO guardada.'
                );
            }             
        }

        return json_decode(json_encode($mensajes, true));
    }

    public function eliminarDeficienciaAteracionesRe(Request $request){
        $id_fila_deficiencia_alteraciones = $request->fila;
        $fila_actualizar = [
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_alteraciones)
        ->update($fila_actualizar);

        /* $total_registros_diagnostico = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count(); */

        $mensajes = array(
            "parametro" => 'fila_deficiencia_alteracion_eliminada',
            //'total_registros' => $total_registros_diagnostico,
            "mensaje" => 'Deficiencia por alteraciones eliminado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));        
    }

    public function actualizarDxPrincipalDeficienciasAlteracionesRe(Request $request){
        
        $fila = $request->fila;
        $banderaDxPrincipalDA = $request->banderaDxPrincipalDA;
        $Id_evento = $request->Id_evento;            

        if ($banderaDxPrincipalDA == 'SiDxPrincipal_deficiencia_alteraciones') {
            $fila_actulizar = [
                'Dx_Principal' => 'Si'
            ];
    
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
            ->where([
                ['Id_Deficiencia', $fila],
                ['ID_evento', $Id_evento],
                ['Estado_Recalificacion', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalDeficienciaAlteracion_agregado',
                "mensaje" => 'Dx Principal Deficiencias alteraciones agreagada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));  

        }elseif($banderaDxPrincipalDA == 'NoDxPrincipal_deficiencia_alteraciones'){           

            $fila_actulizar = [
                'Dx_Principal' => 'No'
            ];
    
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
            ->where([
                ['Id_Deficiencia', $fila],
                ['ID_evento', $Id_evento],
                ['Estado_Recalificacion', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalDeficienciaAlteracion_eliminado',
                "mensaje" => 'Dx Principal Deficiencias alteraciones eliminada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Agudeza auditiva

    public function guardarDeficienciasAgudezaAuditivasRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Estado = $request->Estado;

        $ID_evento = $request->ID_evento;
        $Id_Asignacion = $request->Id_Asignacion;
        $Id_proceso = $request->Id_proceso;
        $oido_izquierdo = $request->oido_izquierdo;
        $oido_derecho = $request->oido_derecho;
        $Agudeza_Auditivas = $request->Agudeza_Auditivas;
        
        foreach ($Agudeza_Auditivas as $auditiva) {
            $auditiva;            
            foreach ($auditiva as $columna => $deficiencia) {
                $$columna = $deficiencia;
            }
        }
                    
        $datos_agudeza_auditiva = [
            'ID_evento' => $ID_evento,
            'Id_Asignacion' => $Id_Asignacion,
            'Id_proceso' => $Id_proceso,
            'Oido_Izquierdo' => $oido_izquierdo,
            'Oido_Derecho' => $oido_derecho,
            'Deficiencia_monoaural_izquierda' => $columna0,
            'Deficiencia_monoaural_derecha' => $columna1,
            'Deficiencia_binaural' => $columna2,
            'Adicion_tinnitus' => $columna3,
            'Deficiencia' => $columna4,
            'Estado' => $Estado,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];
        
        sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')->insert($datos_agudeza_auditiva);
        
        $mensajes = array(
            "parametro" => 'insertar_agudeza_auditiva',
            "mensaje" => 'Agudeza auditiva guardada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function eliminarAgudezaAuditivaRe(Request $request){

        $id_fila_agudeza_auditiva = $request->fila;
        $fila_actualizar = [
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')->where('Id_Agudeza_auditiva', $id_fila_agudeza_auditiva)
        ->update($fila_actualizar);

        /* $total_registros_agudeza_auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count(); */

        $mensajes = array(
            "parametro" => 'fila_agudeza_auditiva_eliminada',
            //'total_registros' => $total_registros_agudeza_auditiva,
            "mensaje" => 'Agudeza auditiva eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    public function actualizarDxPrincipalAgudezaAuditivaRe(Request $request){
        
        $dataAuditiva = $request->dataAuditiva;
        $Id_evento = $request->Id_evento;
        $banderaDxPrincipal = $request->banderaDxPrincipal;
        if ($banderaDxPrincipal == 'SiDxPrincipal') {
            $fila_actulizar = [
                'Dx_Principal' => 'Si'
            ];
    
            sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento],
                ['Id_Agudeza_auditiva', $dataAuditiva],
                ['Estado_Recalificacion', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_auditiva_agregado',
                "mensaje" => 'Dx Principal Agudeza auditiva agreagado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        } elseif($banderaDxPrincipal == 'NoDxPrincipal'){
            $fila_actulizar = [
                'Dx_Principal' => 'No'
            ];
    
            sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento],
                ['Id_Agudeza_auditiva', $dataAuditiva],
                ['Estado_Recalificacion', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_auditiva_agregado',
                "mensaje" => 'Dx Principal Agudeza auditiva eliminado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Agudeza visual

    public function ConsultaCampimetriaXFilaRe(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $parametro = $request->parametro;
        if ($parametro == "nuevo") {
            $Id_Fila = $request->Id_Fila;
            $listado_campimetria = sigmel_campimetria_visuales::on('sigmel_gestiones')
            ->select('Fila1', 'Fila2', 'Fila3', 'Fila4', 'Fila5', 'Fila6', 'Fila7', 'Fila8', 'Fila9', 'Fila10')
            ->get();
            $info = json_decode(json_encode($listado_campimetria, true));
        };

        if ($parametro == "edicion_ojo_izq") {
            $listado_campimetria_ojo_izq = sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')
            ->select('InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5', 'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10')
            ->where('Id_agudeza', $request->Id_agudeza)
            ->get();
            $info = json_decode(json_encode($listado_campimetria_ojo_izq, true));
        };

        if ($parametro == "edicion_ojo_der") {
            $listado_campimetria_ojo_der = sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')
            ->select('InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5', 'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10')
            ->where('Id_agudeza', $request->Id_agudeza)
            ->get();
            $info = json_decode(json_encode($listado_campimetria_ojo_der, true));
        };


        return response()->json($info);

    }
    
    public function guardarAgudezaVisualRe(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        // Inserción de información del formulario 
        sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')->insert($request->info_formulario);        
        // Extraemos el id insertado para almacenar los datos de la campimetria
        $id_agudeza = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')->select('Id_agudeza_re')->latest('Id_agudeza_re')->first();
        // Envío de la información de la campimetría para ojo izquierdo 
        $grilla_ojo_izq = $request->grilla_ojo_izq;
        foreach ($grilla_ojo_izq as $key => $insertar_info_grid_ojo_izq) {
            $insertar_info_grid_ojo_izq = array("Id_agudeza" => $id_agudeza['Id_agudeza_re']) + $insertar_info_grid_ojo_izq;
            sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_izq);
        }

        // Envío de la información de la campimetría para ojo derecho 
        $grilla_ojo_der = $request->grilla_ojo_der;
        foreach ($grilla_ojo_der as $key => $insertar_info_grid_ojo_der) {
            $insertar_info_grid_ojo_der = array("Id_agudeza" => $id_agudeza['Id_agudeza_re']) + $insertar_info_grid_ojo_der;
            sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_der);
        }

        $mensajes = array(
            "parametro" => 'guardo',
            "mensaje" => 'Información de Agudeza visual agregada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function infoAgudezaVisualRe(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $informacion_agudeza_visual = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
        ->where("ID_evento_re", $request->ID_evento)
        ->get();

        $info_agudeza = json_decode(json_encode($informacion_agudeza_visual, true));
        return response()->json($info_agudeza);

    }

    public function actualizarAgudezaVisualRe (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        // Actualización de información del formulario
        sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_agudeza_re', '=', $request->Id_agudeza],
            ['ID_evento_re', '=', $request->ID_evento]
        ])
        ->update($request->info_formulario);


        // Envío de la información de la campimetría para ojo izquierdo  
        sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $request->Id_agudeza)->delete();

        $grilla_ojo_izq = $request->grilla_ojo_izq;
        foreach ($grilla_ojo_izq as $key => $insertar_info_grid_ojo_izq) {
            $insertar_info_grid_ojo_izq = array("Id_agudeza" => $request->Id_agudeza) + $insertar_info_grid_ojo_izq;
            sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_izq);
        }

        // Envío de la información de la campimetría para ojo derecho
        sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $request->Id_agudeza)->delete();
        $grilla_ojo_der = $request->grilla_ojo_der;
        foreach ($grilla_ojo_der as $key => $insertar_info_grid_ojo_der) {
            $insertar_info_grid_ojo_der = array("Id_agudeza" => $request->Id_agudeza) + $insertar_info_grid_ojo_der;
            sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_der);
        }

        $mensajes = array(
            "parametro" => 'actualizo',
            "mensaje" => 'Información de Agudeza visual actualizada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }
    
    public function eliminarAgudezaVisualRe(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        $id_agudeza = $request->Id_agudeza;
        $id_evento = $request->ID_evento;

        /* Borrado de la información general */
        $datos_actualizar_agudeza_visual = [
            'Estado' => 'Inactivo',
            'Estado_Recalificacion' => 'Inactivo',
            'Nombre_usuario' => $usuario,
            'F_registro' => $date,
        ];

        sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_agudeza_re', '=', $id_agudeza],
            ['ID_evento_re', '=', $id_evento]
        ])->update($datos_actualizar_agudeza_visual);

        /* Borrado de la información de la campimetría para ojo izquierdo  */
        /* sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $id_agudeza)->delete(); */

        /* Borrado de la información de la campimetría para ojo derecho */
        /* sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $id_agudeza)->delete(); */

        $mensajes = array(
            "parametro" => 'borro',
            "mensaje" => 'Información de Agudeza visual eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function actualizarDxPrincipalAgudezaVisualRe(Request $request){
        
        $dataVisual = $request->dataVisual;
        $Id_evento = $request->Id_evento;
        $banderaDxPrincipal_visual = $request->banderaDxPrincipal_visual;        
        if ($banderaDxPrincipal_visual == 'SiDxPrincipal') {
            $fila_actulizar = [
                'Dx_Principal_re' => 'Si'
            ];
            
            sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento_re', $Id_evento],
                ['Id_agudeza_re', $dataVisual]
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_visual_agregado',
                "mensaje" => 'Dx Principal Agudeza visual agreagado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
        } elseif($banderaDxPrincipal_visual == 'NoDxPrincipal'){
            $fila_actulizar = [
                'Dx_Principal_re' => 'No'
            ];
    
            sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento_re', $Id_evento],
                ['Id_agudeza_re', $dataVisual]
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_visual_agregado',
                "mensaje" => 'Dx Principal Agudeza visual eliminado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Laboralmente Activo Rol Ocupacional

    public function guardarLaboralmenteActivoRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $Id_Evento_decreto = $request -> Id_Evento_decreto;
        $Id_Proceso_decreto = $request -> Id_Proceso_decreto;
        $Id_Asignacion_decreto = $request -> Id_Asignacion_decreto;
        $restricion_rol = $request -> restricion_rol;
        $auto_suficiencia = $request -> auto_suficiencia;
        $edad_cronologica_adulto = $request -> edad_cronologica_adulto;
        $edad_cronologica_menor = $request -> edad_cronologica_menor;
        $resultado_rol_laboral_30 = $request -> resultado_rol_laboral_30;
        $mirar = $request -> mirar;
        $escuchar = $request -> escuchar;
        $aprender = $request -> aprender;
        $calcular = $request -> calcular;
        $pensar = $request -> pensar;
        $leer = $request -> leer;
        $escribir = $request -> escribir;
        $matematicos = $request -> matematicos;
        $decisiones = $request -> decisiones;
        $tareas_simples = $request -> tareas_simples;
        $resultado_tabla6 = $request -> resultado_tabla6;
        $comunicarse_mensaje = $request -> comunicarse_mensaje;
        $no_comunicarse_mensaje = $request -> no_comunicarse_mensaje;
        $comunicarse_signos = $request -> comunicarse_signos;
        $comunicarse_escrito = $request -> comunicarse_escrito;
        $habla = $request -> habla;
        $no_verbales = $request -> no_verbales;
        $mensajes_escritos = $request -> mensajes_escritos;
        $sostener_conversa = $request -> sostener_conversa;
        $iniciar_discusiones = $request -> iniciar_discusiones;
        $utiliza_dispositivos = $request -> utiliza_dispositivos;
        $resultado_tabla7 = $request -> resultado_tabla7;
        $cambiar_posturas = $request -> cambiar_posturas;
        $posicion_cuerpo = $request -> posicion_cuerpo;
        $llevar_objetos = $request -> llevar_objetos;
        $uso_fino_mano = $request -> uso_fino_mano;
        $uso_mano_brazo = $request -> uso_mano_brazo;
        $desplazarse_entorno = $request -> desplazarse_entorno;
        $distintos_lugares = $request -> distintos_lugares;
        $desplazarse_con_equipo = $request -> desplazarse_con_equipo;
        $transporte_pasajero = $request -> transporte_pasajero;
        $conduccion = $request -> conduccion;
        $resultado_tabla8 = $request -> resultado_tabla8;
        $lavarse = $request -> lavarse;
        $cuidado_cuerpo = $request -> cuidado_cuerpo;
        $higiene_personal = $request -> higiene_personal;
        $vestirse = $request -> vestirse;
        $quitarse_ropa = $request -> quitarse_ropa;
        $ponerse_calzado = $request -> ponerse_calzado;
        $comer = $request -> comer;
        $beber = $request -> beber;
        $cuidado_salud = $request -> cuidado_salud;
        $control_dieta = $request -> control_dieta;
        $resultado_tabla9 = $request -> resultado_tabla9;
        $adquisicion_para_vivir = $request -> adquisicion_para_vivir;
        $bienes_servicios = $request -> bienes_servicios;
        $comprar = $request -> comprar;
        $preparar_comida = $request -> preparar_comida;
        $quehaceres_casa = $request -> quehaceres_casa;
        $limpieza_vivienda = $request -> limpieza_vivienda;
        $objetos_hogar = $request -> objetos_hogar;
        $ayudar_los_demas = $request -> ayudar_los_demas;
        $mantenimiento_dispositivos = $request -> mantenimiento_dispositivos;
        $cuidado_animales = $request -> cuidado_animales;
        $resultado_tabla10 = $request -> resultado_tabla10;
        $total_otras = $request -> total_otras;
        $total_rol_areas = $request -> total_rol_areas;       
        
        //echo $higiene_personal;

        if ($request -> bandera_LaboralActivo_guardar_actualizar == 'Guardar') {
            
            // $Ultimo_Id_Asignacion = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
            // ->where([['ID_evento', $Id_Evento_decreto]])
            // ->max('Id_Asignacion');
    
            // $Estado_Recalificacion_laboral = [
            //     'Estado_Recalificacion' => 'Inactivo'
            // ];
            
            // sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
            // ->where([['ID_evento', $Id_Evento_decreto], ['Id_Asignacion', $Ultimo_Id_Asignacion]])
            // ->update($Estado_Recalificacion_laboral);

            $datos_laboralmenteActivo = [
                'ID_evento' => $Id_Evento_decreto,
                'Id_Asignacion' => $Id_Asignacion_decreto,
                'Id_proceso' => $Id_Proceso_decreto,                
                'Restricciones_rol' => $restricion_rol,
                'Autosuficiencia_economica' => $auto_suficiencia,
                'Edad_cronologica_menor' => $edad_cronologica_menor,
                'Edad_cronologica' => $edad_cronologica_adulto,
                'Total_rol_laboral' => $resultado_rol_laboral_30,
                'Aprendizaje_mirar' => $mirar,
                'Aprendizaje_escuchar' => $escuchar,
                'Aprendizaje_aprender' => $aprender,
                'Aprendizaje_calcular' => $calcular,
                'Aprendizaje_pensar' => $pensar,
                'Aprendizaje_leer' => $leer,
                'Aprendizaje_escribir' => $escribir,
                'Aprendizaje_matematicos' => $matematicos,
                'Aprendizaje_resolver' => $decisiones,
                'Aprendizaje_tareas' => $tareas_simples,
                'Aprendizaje_total' => $resultado_tabla6,
                'Comunicacion_verbales' => $comunicarse_mensaje,
                'Comunicacion_noverbales' => $no_comunicarse_mensaje,
                'Comunicacion_formal' => $comunicarse_signos,
                'Comunicacion_escritos' => $comunicarse_escrito,
                'Comunicacion_habla' => $habla,
                'Comunicacion_produccion' => $no_verbales,
                'Comunicacion_mensajes' => $mensajes_escritos,
                'Comunicacion_conversacion' => $sostener_conversa,
                'Comunicacion_discusiones' => $iniciar_discusiones,
                'Comunicacion_dispositivos' => $utiliza_dispositivos,
                'Comunicacion_total' => $resultado_tabla7,
                'Movilidad_cambiar_posturas' => $cambiar_posturas,
                'Movilidad_mantener_posicion' => $posicion_cuerpo,
                'Movilidad_objetos' => $llevar_objetos,
                'Movilidad_uso_mano' => $uso_fino_mano,
                'Movilidad_mano_brazo' => $uso_mano_brazo,
                'Movilidad_Andar' => $desplazarse_entorno,
                'Movilidad_desplazarse' => $distintos_lugares,
                'Movilidad_equipo' => $desplazarse_con_equipo,
                'Movilidad_transporte' => $transporte_pasajero,
                'Movilidad_conduccion' => $conduccion,
                'Movilidad_total' => $resultado_tabla8,
                'Cuidado_lavarse' => $lavarse,
                'Cuidado_partes_cuerpo' => $cuidado_cuerpo,
                'Cuidado_higiene' => $higiene_personal,
                'Cuidado_vestirse' => $vestirse,
                'Cuidado_quitarse' => $quitarse_ropa,
                'Cuidado_ponerse_calzado' => $ponerse_calzado,
                'Cuidado_comer' => $comer,
                'Cuidado_beber' => $beber,
                'Cuidado_salud' => $cuidado_salud,
                'Cuidado_dieta' => $control_dieta,
                'Cuidado_total' => $resultado_tabla9,
                'Domestica_vivir' => $adquisicion_para_vivir,
                'Domestica_bienes' => $bienes_servicios,
                'Domestica_comprar' => $comprar,
                'Domestica_comidas' => $preparar_comida,
                'Domestica_quehaceres' => $quehaceres_casa,
                'Domestica_limpieza' => $limpieza_vivienda,
                'Domestica_objetos' => $objetos_hogar,
                'Domestica_ayudar' => $ayudar_los_demas,
                'Domestica_mantenimiento' => $mantenimiento_dispositivos,
                'Domestica_animales' => $cuidado_animales,
                'Domestica_total' => $resultado_tabla10,
                'Total_otras_areas' => $total_otras,
                'Total_laboral_otras_areas' => $total_rol_areas,
                'Estado' => 'Inactivo',
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];            
            sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')->insert($datos_laboralmenteActivo);
            
            $mensajes = array(
                "parametro" => 'insertar_laboralmente_activo',
                "mensaje" => 'Laboralmente activo guardado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));

        }elseif($request -> bandera_LaboralActivo_guardar_actualizar == 'Actualizar'){

            $datos_laboralmenteActivo = [
                'ID_evento' => $Id_Evento_decreto,
                'Id_Asignacion' => $Id_Asignacion_decreto,
                'Id_proceso' => $Id_Proceso_decreto,                
                'Restricciones_rol' => $restricion_rol,
                'Autosuficiencia_economica' => $auto_suficiencia,
                'Edad_cronologica_menor' => $edad_cronologica_menor,
                'Edad_cronologica' => $edad_cronologica_adulto,
                'Total_rol_laboral' => $resultado_rol_laboral_30,
                'Aprendizaje_mirar' => $mirar,
                'Aprendizaje_escuchar' => $escuchar,
                'Aprendizaje_aprender' => $aprender,
                'Aprendizaje_calcular' => $calcular,
                'Aprendizaje_pensar' => $pensar,
                'Aprendizaje_leer' => $leer,
                'Aprendizaje_escribir' => $escribir,
                'Aprendizaje_matematicos' => $matematicos,
                'Aprendizaje_resolver' => $decisiones,
                'Aprendizaje_tareas' => $tareas_simples,
                'Aprendizaje_total' => $resultado_tabla6,
                'Comunicacion_verbales' => $comunicarse_mensaje,
                'Comunicacion_noverbales' => $no_comunicarse_mensaje,
                'Comunicacion_formal' => $comunicarse_signos,
                'Comunicacion_escritos' => $comunicarse_escrito,
                'Comunicacion_habla' => $habla,
                'Comunicacion_produccion' => $no_verbales,
                'Comunicacion_mensajes' => $mensajes_escritos,
                'Comunicacion_conversacion' => $sostener_conversa,
                'Comunicacion_discusiones' => $iniciar_discusiones,
                'Comunicacion_dispositivos' => $utiliza_dispositivos,
                'Comunicacion_total' => $resultado_tabla7,
                'Movilidad_cambiar_posturas' => $cambiar_posturas,
                'Movilidad_mantener_posicion' => $posicion_cuerpo,
                'Movilidad_objetos' => $llevar_objetos,
                'Movilidad_uso_mano' => $uso_fino_mano,
                'Movilidad_mano_brazo' => $uso_mano_brazo,
                'Movilidad_Andar' => $desplazarse_entorno,
                'Movilidad_desplazarse' => $distintos_lugares,
                'Movilidad_equipo' => $desplazarse_con_equipo,
                'Movilidad_transporte' => $transporte_pasajero,
                'Movilidad_conduccion' => $conduccion,
                'Movilidad_total' => $resultado_tabla8,
                'Cuidado_lavarse' => $lavarse,
                'Cuidado_partes_cuerpo' => $cuidado_cuerpo,
                'Cuidado_higiene' => $higiene_personal,
                'Cuidado_vestirse' => $vestirse,
                'Cuidado_quitarse' => $quitarse_ropa,
                'Cuidado_ponerse_calzado' => $ponerse_calzado,
                'Cuidado_comer' => $comer,
                'Cuidado_beber' => $beber,
                'Cuidado_salud' => $cuidado_salud,
                'Cuidado_dieta' => $control_dieta,
                'Cuidado_total' => $resultado_tabla9,
                'Domestica_vivir' => $adquisicion_para_vivir,
                'Domestica_bienes' => $bienes_servicios,
                'Domestica_comprar' => $comprar,
                'Domestica_comidas' => $preparar_comida,
                'Domestica_quehaceres' => $quehaceres_casa,
                'Domestica_limpieza' => $limpieza_vivienda,
                'Domestica_objetos' => $objetos_hogar,
                'Domestica_ayudar' => $ayudar_los_demas,
                'Domestica_mantenimiento' => $mantenimiento_dispositivos,
                'Domestica_animales' => $cuidado_animales,
                'Domestica_total' => $resultado_tabla10,
                'Total_otras_areas' => $total_otras,
                'Total_laboral_otras_areas' => $total_rol_areas,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ]; 

            sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_Evento_decreto], ['Id_Asignacion', $Id_Asignacion_decreto]])->update($datos_laboralmenteActivo);
            sleep(2);

            $mensajes = array(
                "parametro" => 'update_laboralmente_activo',
                "mensaje2" => 'Laboralmente activo actualizado satisfactoriamente.'
            ); 

            return json_decode(json_encode($mensajes, true));

        }
        
    }

    public function guardarRolOcupacionalRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $Id_EventoDecreto = $request -> Id_EventoDecreto;
        $Id_ProcesoDecreto = $request -> Id_ProcesoDecreto;
        $Id_Asignacion_Dcreto = $request -> Id_Asignacion_Dcreto;
        $poblacion_califi = $request -> poblacion_califi;
        $mantiene_postura = $request -> mantiene_postura;
        $actividad_espontanea = $request -> actividad_espontanea;
        $sujeta_cabeza = $request -> sujeta_cabeza;
        $sienta_apoyo = $request -> sienta_apoyo;
        $sobre_mismo = $request -> sobre_mismo;
        $sentado_sin_apoyo = $request -> sentado_sin_apoyo;
        $tumbado_sentado = $request -> tumbado_sentado;
        $pie_apoyo = $request -> pie_apoyo;
        $pasos_apoyo = $request -> pasos_apoyo;
        $mantiene_sin_apoyo = $request -> mantiene_sin_apoyo;
        $anda_solo = $request -> anda_solo;
        $empuja_pelota = $request -> empuja_pelota;
        $sorteando_obstaculos = $request -> sorteando_obstaculos;
        $succiona = $request -> succiona;
        $fija_mirada = $request -> fija_mirada;
        $trayectoria_objeto = $request -> trayectoria_objeto;
        $sostiene_sonajero = $request -> sostiene_sonajero;
        $hacia_objeto = $request -> hacia_objeto;
        $sostiene_objeto = $request -> sostiene_objeto;
        $abre_cajones = $request -> abre_cajones;
        $bebe_solo = $request -> bebe_solo;
        $quita_prenda = $request -> quita_prenda;
        $espacios_casa = $request -> espacios_casa;
        $imita_trazaso = $request -> imita_trazaso;
        $abre_puerta = $request -> abre_puerta;
        $total_tabla12 = $request -> total_tabla12;
        $roles_ocupacionales_juego = $request -> roles_ocupacionales_juego;
        $total_tabla13 = $request -> total_tabla13;
        $roles_ocupacionales_adultos = $request -> roles_ocupacionales_adultos;
        $total_tabla14 = $request -> total_tabla14;
        $bandera_RolOcupacional_guardar_actualizar = $request -> bandera_RolOcupacional_guardar_actualizar;

        if ($bandera_RolOcupacional_guardar_actualizar == 'Guardar') {

            /* $Ultimo_Id_Asignacion = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_EventoDecreto]])
            ->max('Id_Asignacion');
    
            $Estado_Recalificacion_ocupacional = [
                'Estado_Recalificacion' => 'Inactivo'
            ];
            
            sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Ultimo_Id_Asignacion]])
            ->update($Estado_Recalificacion_ocupacional); */

            $datos_rolOcupacional =[
                'ID_evento' => $Id_EventoDecreto,
                'Id_Asignacion' => $Id_Asignacion_Dcreto,
                'Id_proceso' => $Id_ProcesoDecreto,                
                'Poblacion_calificar' => $poblacion_califi,
                'Motriz_postura_simetrica' => $mantiene_postura,
                'Motriz_actividad_espontanea' => $actividad_espontanea,
                'Motriz_sujeta_cabeza' => $sujeta_cabeza,
                'Motriz_sentarse_apoyo' => $sienta_apoyo,
                'Motriz_gira_sobre_mismo' => $sobre_mismo,
                'Motriz_sentanser_sin_apoyo' => $sentado_sin_apoyo,
                'Motriz_pasa_tumbado_sentado' => $tumbado_sentado,
                'Motriz_pararse_apoyo' => $pie_apoyo,
                'Motriz_pasos_apoyo' => $pasos_apoyo,
                'Motriz_pararse_sin_apoyo' => $mantiene_sin_apoyo,
                'Motriz_anda_solo' => $anda_solo,
                'Motriz_empujar_pelota_pies' => $empuja_pelota,
                'Motriz_andar_obstaculos' => $sorteando_obstaculos,
                'Adaptativa_succiona' => $succiona,
                'Adaptativa_fija_mirada' => $fija_mirada,
                'Adaptativa_sigue_trayectoria_objeto' => $trayectoria_objeto,
                'Adaptativa_sostiene_sonajero' => $sostiene_sonajero,
                'Adaptativa_tiende_mano_hacia_objeto' => $hacia_objeto,
                'Adaptativa_sostiene_objeto_manos' => $sostiene_objeto,
                'Adaptativa_abre_cajones' => $abre_cajones,
                'Adaptativa_bebe_solo' => $bebe_solo,
                'Adaptativa_quitar_prenda_vestir' => $quita_prenda,
                'Adaptativa_reconoce_funcion_espacios_casa' => $espacios_casa,
                'Adaptativa_imita_trazo_lapiz' => $imita_trazaso,
                'Adaptativa_abre_puerta' => $abre_puerta,
                'Total_criterios_desarrollo' => $total_tabla12,
                'Juego_estudio_clase' => $roles_ocupacionales_juego,
                'Total_rol_estudio_clase' => $total_tabla13,
                'Adultos_mayores' => $roles_ocupacionales_adultos,
                'Total_rol_adultos_ayores' => $total_tabla14,
                'Estado' => 'Inactivo',
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')->insert($datos_rolOcupacional);            
            $mensajes = array(
                "parametro" => 'insertar_rol_ocupacional',
                "mensaje" => 'Rol ocupacional guardado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));

        }elseif($bandera_RolOcupacional_guardar_actualizar == 'Actualizar') {           

            $datos_rolOcupacional =[
                'ID_evento' => $Id_EventoDecreto,
                'Id_Asignacion' => $Id_Asignacion_Dcreto,
                'Id_proceso' => $Id_ProcesoDecreto,
                'Poblacion_calificar' => $poblacion_califi,
                'Motriz_postura_simetrica' => $mantiene_postura,
                'Motriz_actividad_espontanea' => $actividad_espontanea,
                'Motriz_sujeta_cabeza' => $sujeta_cabeza,
                'Motriz_sentarse_apoyo' => $sienta_apoyo,
                'Motriz_gira_sobre_mismo' => $sobre_mismo,
                'Motriz_sentanser_sin_apoyo' => $sentado_sin_apoyo,
                'Motriz_pasa_tumbado_sentado' => $tumbado_sentado,
                'Motriz_pararse_apoyo' => $pie_apoyo,
                'Motriz_pasos_apoyo' => $pasos_apoyo,
                'Motriz_pararse_sin_apoyo' => $mantiene_sin_apoyo,
                'Motriz_anda_solo' => $anda_solo,
                'Motriz_empujar_pelota_pies' => $empuja_pelota,
                'Motriz_andar_obstaculos' => $sorteando_obstaculos,
                'Adaptativa_succiona' => $succiona,
                'Adaptativa_fija_mirada' => $fija_mirada,
                'Adaptativa_sigue_trayectoria_objeto' => $trayectoria_objeto,
                'Adaptativa_sostiene_sonajero' => $sostiene_sonajero,
                'Adaptativa_tiende_mano_hacia_objeto' => $hacia_objeto,
                'Adaptativa_sostiene_objeto_manos' => $sostiene_objeto,
                'Adaptativa_abre_cajones' => $abre_cajones,
                'Adaptativa_bebe_solo' => $bebe_solo,
                'Adaptativa_quitar_prenda_vestir' => $quita_prenda,
                'Adaptativa_reconoce_funcion_espacios_casa' => $espacios_casa,
                'Adaptativa_imita_trazo_lapiz' => $imita_trazaso,
                'Adaptativa_abre_puerta' => $abre_puerta,
                'Total_criterios_desarrollo' => $total_tabla12,
                'Juego_estudio_clase' => $roles_ocupacionales_juego,
                'Total_rol_estudio_clase' => $total_tabla13,
                'Adultos_mayores' => $roles_ocupacionales_adultos,
                'Total_rol_adultos_ayores' => $total_tabla14,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_rolOcupacional);
            sleep(2);
            
            $mensajes = array(
                "parametro" => 'actualizar_rol_ocupacional',
                "mensaje2" => 'Rol ocupacional actualizado satisfactoriamente.'
            ); 
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Libro 2 20% y libro 3 30%

    public function guardarLibro2_3Re(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $Id_EventoDecreto = $request->Id_EventoDecreto;
        $Id_ProcesoDecreto = $request->Id_ProcesoDecreto;
        $Id_Asignacion_Dcreto = $request->Id_Asignacion_Dcreto;
        $conducta_10 = $request->conducta_10;
        $conducta_11 = $request->conducta_11;
        $conducta_12 = $request->conducta_12;
        $conducta_13 = $request->conducta_13;
        $conducta_14 = $request->conducta_14;
        $conducta_15 = $request->conducta_15;
        $conducta_16 = $request->conducta_16;
        $conducta_17 = $request->conducta_17;
        $conducta_18 = $request->conducta_18;
        $conducta_19 = $request->conducta_19;
        $total_conducta = $request->total_conducta;
        $comunicacion_20 = $request->comunicacion_20;
        $comunicacion_21 = $request->comunicacion_21;
        $comunicacion_22 = $request->comunicacion_22;
        $comunicacion_23 = $request->comunicacion_23;
        $comunicacion_24 = $request->comunicacion_24;
        $comunicacion_25 = $request->comunicacion_25;
        $comunicacion_26 = $request->comunicacion_26;
        $comunicacion_27 = $request->comunicacion_27;
        $comunicacion_28 = $request->comunicacion_28;
        $comunicacion_29 = $request->comunicacion_29;
        $total_comunicacion = $request->total_comunicacion;
        $cuidado_personal_30 = $request->cuidado_personal_30;
        $cuidado_personal_31 = $request->cuidado_personal_31;
        $cuidado_personal_32 = $request->cuidado_personal_32;
        $cuidado_personal_33 = $request->cuidado_personal_33;
        $cuidado_personal_34 = $request->cuidado_personal_34;
        $cuidado_personal_35 = $request->cuidado_personal_35;
        $cuidado_personal_36 = $request->cuidado_personal_36;
        $cuidado_personal_37 = $request->cuidado_personal_37;
        $cuidado_personal_38 = $request->cuidado_personal_38;
        $cuidado_personal_39 = $request->cuidado_personal_39;
        $total_cuidado_personal = $request->total_cuidado_personal;
        $lomocion_40 = $request->lomocion_40;
        $lomocion_41 = $request->lomocion_41;
        $lomocion_42 = $request->lomocion_42;
        $lomocion_43 = $request->lomocion_43;
        $lomocion_44 = $request->lomocion_44;
        $lomocion_45 = $request->lomocion_45;
        $lomocion_46 = $request->lomocion_46;
        $lomocion_47 = $request->lomocion_47;
        $lomocion_48 = $request->lomocion_48;
        $lomocion_49 = $request->lomocion_49;
        $total_lomocion = $request->total_lomocion;
        $disposicion_50 = $request->disposicion_50;
        $disposicion_51 = $request->disposicion_51;
        $disposicion_52 = $request->disposicion_52;
        $disposicion_53 = $request->disposicion_53;
        $disposicion_54 = $request->disposicion_54;
        $disposicion_55 = $request->disposicion_55;
        $disposicion_56 = $request->disposicion_56;
        $disposicion_57 = $request->disposicion_57;
        $disposicion_58 = $request->disposicion_58;
        $disposicion_59 = $request->disposicion_59;
        $total_disposicion = $request->total_disposicion;
        $destreza_60 = $request->destreza_60;
        $destreza_61 = $request->destreza_61;
        $destreza_62 = $request->destreza_62;
        $destreza_63 = $request->destreza_63;
        $destreza_64 = $request->destreza_64;
        $destreza_65 = $request->destreza_65;
        $destreza_66 = $request->destreza_66;
        $destreza_67 = $request->destreza_67;
        $destreza_68 = $request->destreza_68;
        $destreza_69 = $request->destreza_69;
        $total_destreza = $request->total_destreza;
        $situacion_70 = $request->situacion_70;
        $situacion_71 = $request->situacion_71;
        $situacion_72 = $request->situacion_72;
        $situacion_73 = $request->situacion_73;
        $situacion_74 = $request->situacion_74;
        $situacion_75 = $request->situacion_75;
        $situacion_76 = $request->situacion_76;
        $situacion_77 = $request->situacion_77;
        $situacion_78 = $request->situacion_78;
        $total_situacion = $request->total_situacion;
        $total_discapacidades = $request->total_discapacidades;
        $orientacion = $request->orientacion;
        $indepen_fisica = $request->indepen_fisica;
        $desplazamiento = $request->desplazamiento;
        $ocupacional = $request->ocupacional;
        $social = $request->social;
        $economica = $request->economica;
        $cronologica_adulto = $request->cronologica_adulto;
        $cronologica_menor = $request->cronologica_menor;
        $total_minusvalia = $request->total_minusvalia;
        $bandera_Libros2_3_guardar_actualizar = $request->bandera_Libros2_3_guardar_actualizar;

        if($bandera_Libros2_3_guardar_actualizar == 'Guardar'){

            /* $Ultimo_Id_Asignacion = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_EventoDecreto]])
            ->max('Id_Asignacion');
    
            $Estado_Recalificacion_libros = [
                'Estado_Recalificacion' => 'Inactivo'
            ];
            
            sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Ultimo_Id_Asignacion]])
            ->update($Estado_Recalificacion_libros); */

            $datos_Libros2_3 =[
                'ID_evento' => $Id_EventoDecreto,
                'Id_Asignacion' => $Id_Asignacion_Dcreto,
                'Id_proceso' => $Id_ProcesoDecreto,                
                'Conducta10' => $conducta_10,
                'Conducta11' => $conducta_11,
                'Conducta12' => $conducta_12,
                'Conducta13' => $conducta_13,
                'Conducta14' => $conducta_14,
                'Conducta15' => $conducta_15,
                'Conducta16' => $conducta_16,
                'Conducta17' => $conducta_17,
                'Conducta18' => $conducta_18,
                'Conducta19' => $conducta_19,
                'Total_conducta' => $total_conducta,
                'Comunicacion20' => $comunicacion_20,
                'Comunicacion21' => $comunicacion_21,
                'Comunicacion22' => $comunicacion_22,
                'Comunicacion23' => $comunicacion_23,
                'Comunicacion24' => $comunicacion_24,
                'Comunicacion25' => $comunicacion_25,
                'Comunicacion26' => $comunicacion_26,
                'Comunicacion27' => $comunicacion_27,
                'Comunicacion28' => $comunicacion_28,
                'Comunicacion29' => $comunicacion_29,
                'Total_comunicacion' => $total_comunicacion,
                'Personal30' => $cuidado_personal_30,
                'Personal31' => $cuidado_personal_31,
                'Personal32' => $cuidado_personal_32,
                'Personal33' => $cuidado_personal_33,
                'Personal34' => $cuidado_personal_34,
                'Personal35' => $cuidado_personal_35,
                'Personal36' => $cuidado_personal_36,
                'Personal37' => $cuidado_personal_37,
                'Personal38' => $cuidado_personal_38,
                'Personal39' => $cuidado_personal_39,
                'Total_personal' => $total_cuidado_personal,
                'Locomocion40' => $lomocion_40,
                'Locomocion41' => $lomocion_41,
                'Locomocion42' => $lomocion_42,
                'Locomocion43' => $lomocion_43,
                'Locomocion44' => $lomocion_44,
                'Locomocion45' => $lomocion_45,
                'Locomocion46' => $lomocion_46,
                'Locomocion47' => $lomocion_47,
                'Locomocion48' => $lomocion_48,
                'Locomocion49' => $lomocion_49,
                'Total_locomocion' => $total_lomocion,
                'Disposicion50' => $disposicion_50,
                'Disposicion51' => $disposicion_51,
                'Disposicion52' => $disposicion_52,
                'Disposicion53' => $disposicion_53,
                'Disposicion54' => $disposicion_54,
                'Disposicion55' => $disposicion_55,
                'Disposicion56' => $disposicion_56,
                'Disposicion57' => $disposicion_57,
                'Disposicion58' => $disposicion_58,
                'Disposicion59' => $disposicion_59,
                'Total_disposicion' => $total_disposicion,
                'Destreza60' => $destreza_60,
                'Destreza61' => $destreza_61,
                'Destreza62' => $destreza_62,
                'Destreza63' => $destreza_63,
                'Destreza64' => $destreza_64,
                'Destreza65' => $destreza_65,
                'Destreza66' => $destreza_66,
                'Destreza67' => $destreza_67,
                'Destreza68' => $destreza_68,
                'Destreza69' => $destreza_69,
                'Total_destreza' => $total_destreza,
                'Situacion70' => $situacion_70,
                'Situacion71' => $situacion_71,
                'Situacion72' => $situacion_72,
                'Situacion73' => $situacion_73,
                'Situacion74' => $situacion_74,
                'Situacion75' => $situacion_75,
                'Situacion76' => $situacion_76,
                'Situacion77' => $situacion_77,
                'Situacion78' => $situacion_78,
                'Total_situacion' => $total_situacion,
                'Total_discapacidad' => $total_discapacidades,
                'Orientacion' => $orientacion,
                'Idenpendencia_fisica' => $indepen_fisica,
                'Desplazamiento' => $desplazamiento,
                'Ocupacional' => $ocupacional,
                'Integracion' => $social,
                'Autosuficiencia' => $economica,
                'Edad_cronologica_menor' => $cronologica_menor,
                'Edad_cronologica_adulto' => $cronologica_adulto,
                'Total_minusvalia' => $total_minusvalia,
                'Estado' => 'Inactivo',
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')->insert($datos_Libros2_3);            
            $mensajes = array(
                "parametro" => 'insertar_libros_2_3',
                "mensaje" => 'Libros II y III guardados satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
            
        }elseif($bandera_Libros2_3_guardar_actualizar == 'Actualizar'){
            $datos_Libros2_3 =[
                'ID_evento' => $Id_EventoDecreto,
                'Id_Asignacion' => $Id_Asignacion_Dcreto,
                'Id_proceso' => $Id_ProcesoDecreto,                
                'Conducta10' => $conducta_10,
                'Conducta11' => $conducta_11,
                'Conducta12' => $conducta_12,
                'Conducta13' => $conducta_13,
                'Conducta14' => $conducta_14,
                'Conducta15' => $conducta_15,
                'Conducta16' => $conducta_16,
                'Conducta17' => $conducta_17,
                'Conducta18' => $conducta_18,
                'Conducta19' => $conducta_19,
                'Total_conducta' => $total_conducta,
                'Comunicacion20' => $comunicacion_20,
                'Comunicacion21' => $comunicacion_21,
                'Comunicacion22' => $comunicacion_22,
                'Comunicacion23' => $comunicacion_23,
                'Comunicacion24' => $comunicacion_24,
                'Comunicacion25' => $comunicacion_25,
                'Comunicacion26' => $comunicacion_26,
                'Comunicacion27' => $comunicacion_27,
                'Comunicacion28' => $comunicacion_28,
                'Comunicacion29' => $comunicacion_29,
                'Total_comunicacion' => $total_comunicacion,
                'Personal30' => $cuidado_personal_30,
                'Personal31' => $cuidado_personal_31,
                'Personal32' => $cuidado_personal_32,
                'Personal33' => $cuidado_personal_33,
                'Personal34' => $cuidado_personal_34,
                'Personal35' => $cuidado_personal_35,
                'Personal36' => $cuidado_personal_36,
                'Personal37' => $cuidado_personal_37,
                'Personal38' => $cuidado_personal_38,
                'Personal39' => $cuidado_personal_39,
                'Total_personal' => $total_cuidado_personal,
                'Locomocion40' => $lomocion_40,
                'Locomocion41' => $lomocion_41,
                'Locomocion42' => $lomocion_42,
                'Locomocion43' => $lomocion_43,
                'Locomocion44' => $lomocion_44,
                'Locomocion45' => $lomocion_45,
                'Locomocion46' => $lomocion_46,
                'Locomocion47' => $lomocion_47,
                'Locomocion48' => $lomocion_48,
                'Locomocion49' => $lomocion_49,
                'Total_locomocion' => $total_lomocion,
                'Disposicion50' => $disposicion_50,
                'Disposicion51' => $disposicion_51,
                'Disposicion52' => $disposicion_52,
                'Disposicion53' => $disposicion_53,
                'Disposicion54' => $disposicion_54,
                'Disposicion55' => $disposicion_55,
                'Disposicion56' => $disposicion_56,
                'Disposicion57' => $disposicion_57,
                'Disposicion58' => $disposicion_58,
                'Disposicion59' => $disposicion_59,
                'Total_disposicion' => $total_disposicion,
                'Destreza60' => $destreza_60,
                'Destreza61' => $destreza_61,
                'Destreza62' => $destreza_62,
                'Destreza63' => $destreza_63,
                'Destreza64' => $destreza_64,
                'Destreza65' => $destreza_65,
                'Destreza66' => $destreza_66,
                'Destreza67' => $destreza_67,
                'Destreza68' => $destreza_68,
                'Destreza69' => $destreza_69,
                'Total_destreza' => $total_destreza,
                'Situacion70' => $situacion_70,
                'Situacion71' => $situacion_71,
                'Situacion72' => $situacion_72,
                'Situacion73' => $situacion_73,
                'Situacion74' => $situacion_74,
                'Situacion75' => $situacion_75,
                'Situacion76' => $situacion_76,
                'Situacion77' => $situacion_77,
                'Situacion78' => $situacion_78,
                'Total_situacion' => $total_situacion,
                'Total_discapacidad' => $total_discapacidades,
                'Orientacion' => $orientacion,
                'Idenpendencia_fisica' => $indepen_fisica,
                'Desplazamiento' => $desplazamiento,
                'Ocupacional' => $ocupacional,
                'Integracion' => $social,
                'Autosuficiencia' => $economica,
                'Edad_cronologica_menor' => $cronologica_menor,
                'Edad_cronologica_adulto' => $cronologica_adulto,
                'Total_minusvalia' => $total_minusvalia,                
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_Libros2_3);
            sleep(2);            
            $mensajes = array(
                "parametro" => 'actualizar_libros_2_3',
                "mensaje2" => 'Libros II y III actualizados satisfactoriamente.'
            ); 
            return json_decode(json_encode($mensajes, true));   
        }

    }
    

    // Deficiencias Decreto Cero

    public function guardarDeficieciasDecretoCeroRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;   
        /* CAPTURA DE DATOS DE LA DEFICIENCIA */
        $array_datos = $request->datos_finales_deficiciencias_decreto_cero;
        $Estado = $request->Estado;
        //print_r($array_datos);

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];

        foreach ($array_datos as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);

            $subarray_datos[] = $Estado;
            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'Total_deficiencia', 'Estado', 'Nombre_usuario','F_registro'];
        
        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar) {
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->insert($insertar);
        }

        $mensajes = array(
            "parametro" => 'inserto_informacion_deficiencias_decreto_cero',
            "mensaje" => 'Deficiencia guardada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));    

    }

    public function eliminarDeficieciasDecretoCeroRe(Request $request){
        $id_fila_deficiencia_cero = $request->fila;
        $fila_actualizar = [
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_cero)
        ->update($fila_actualizar);

        /* $total_registros_deficiencias_cero = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count(); */

        $mensajes = array(
            "parametro" => 'fila_deficiencia_cero_eliminada',
            //'total_registros' => $total_registros_deficiencias_cero,
            "mensaje" => 'Deficiencia eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

    // Deficiencias Decreto Tres

    public function guardarDeficieciasDecretoTresRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;   
        $Estado= $request->Estado;
        /* CAPTURA DE DATOS DE LA DEFICIENCIA */
        $array_datos = $request->datos_finales_deficiciencias_decreto_tres;
        //print_r($array_datos);

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];

        foreach ($array_datos as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);

            $subarray_datos[] = $Estado;
            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Tabla1999', 'Titulo_tabla1999', 'Total_deficiencia', 'Estado', 'Nombre_usuario','F_registro'];
        
        // Combinación de los campos de la tabla con los datos
        $array_datos_con_keys = [];
        foreach ($array_datos_organizados as $subarray_datos_organizados) {
            array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
        }

        // Inserción de la información
        foreach ($array_datos_con_keys as $insertar) {
            sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->insert($insertar);
        }

        $mensajes = array(
            "parametro" => 'inserto_informacion_deficiencias_decreto_tres',
            "mensaje" => 'Deficiencia guardada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));    

    }

    public function eliminarDeficieciasDecretoTresRe(Request $request){
        $id_fila_deficiencia_cero = $request->fila;
        $fila_actualizar = [
            'Estado_Recalificacion' => 'Inactivo',
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_cero)
        ->update($fila_actualizar);

        /* $total_registros_deficiencias_tres = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count(); */

        $mensajes = array(
            "parametro" => 'fila_deficiencia_tres_eliminada',
            //'total_registros' => $total_registros_deficiencias_tres,
            "mensaje" => 'Deficiencia eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    } 

    // Comite Interdisciplinario

    public function guardarcomiteinterdisciplinarioRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);
        $Id_EventoDecreto = $request->Id_EventoDecreto;
        $Id_ProcesoDecreto = $request->Id_ProcesoDecreto;
        $Id_Asignacion_Dcreto = $request->Id_Asignacion_Dcreto;
        $visar = $request->visar;
        $profesional_comite = $request->profesional_comite;
        $f_visado_comite = $request->f_visado_comite;

        $datos_comiteInterdisciplinario = [
            'ID_evento' => $Id_EventoDecreto,
            'Id_proceso' => $Id_ProcesoDecreto,
            'Id_Asignacion' => $Id_Asignacion_Dcreto,
            'Visar' => $visar,
            'Profesional_comite' => $profesional_comite,
            'F_visado_comite' => $f_visado_comite,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];
        sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')->insert($datos_comiteInterdisciplinario); 
        
        // Cerrar el decreto
        $cerrar_decreto =[
            'Estado_decreto' => 'Cerrado',
        ];

        sigmel_informacion_decreto_eventos::on('sigmel_gestiones')->where([['ID_Evento',$Id_EventoDecreto],['Id_Asignacion',$Id_Asignacion_Dcreto]])
        ->update($cerrar_decreto);

        $mensajes = array(
            "parametro" => 'insertar_comite_interdisciplinario',
            "mensaje" => 'Comite Interdisciplinario guardado satisfactoriamente.'
        );    
        return json_decode(json_encode($mensajes, true));
    }

    // Correspondencia  

    public function guardarcorrespondenciaRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);

        $Id_EventoDecreto = $request->Id_EventoDecreto;
        $Id_ProcesoDecreto = $request->Id_ProcesoDecreto;
        $Id_Asignacion_Dcreto = $request->Id_Asignacion_Dcreto;
        $oficiopcl = $request->oficiopcl;
        $oficioinca = $request->oficioinca;
        $formatob = $request->formatob;
        $formatoc = $request->formatoc;
        $formatod = $request->formatod;
        $formatoe = $request->formatoe;

        if ($oficiopcl == '') {
            $oficiopcl = 'No';
        }
        if($oficioinca == ''){
            $oficioinca = 'No';
        }
        if($formatob == '') {
            $formatob = 'No';
        }
        if($formatoc == '') {
            $formatoc = 'No';
        }
        if($formatod == '') {
            $formatod = 'No';
        }
        if($formatoe == '') {
            $formatoe = 'No';
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
        $empleador = $request->empleador;
        $eps = $request->eps;
        $afp = $request->afp;
        $afp_conocimiento = $request->afp_conocimiento;
        $arl = $request->arl;
        $jrci = $request->jrci;        
        $cual = $request->cual;
        $N_siniestro = $request->N_siniestro;
        if($cual == ''){
            $cual = null;
        }
        $jnci = $request->jnci;
        // $agregar_copias_comu = $empleador.','.$eps.','.$afp.','.$arl.','.$jrci.','.$jnci;
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

        $agregar_copias_comu = implode(',', $variables_llenas);
        
        $anexos = $request->anexos;
        $elaboro = $request->elaboro;
        $reviso = $request->reviso;
        $firmar = $request->firmar;
        $ciudad = $request->ciudad;
        $f_correspondencia = $request->f_correspondencia;
        $radicado = $request->radicado;
        $bandera_correspondecia_guardar_actualizar = $request->bandera_correspondecia_guardar_actualizar;

        /* Se completan los siguientes datos para lo del tema del pbs 014 */

        // eL número de identificacion será el del afiliado.
        $array_nro_ident_afi = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('Nro_identificacion')
        ->where([['ID_evento', $Id_EventoDecreto]])
        ->get();

        if (count($array_nro_ident_afi) > 0) {
            $nro_identificacion = $array_nro_ident_afi[0]->Nro_identificacion;
        }else{
            $nro_identificacion = 'N/A';
        }

        // el nombre del destinatario principal dependerá de lo siguiente:
        // Si no se seleccciona la opción otro destinatario principal: el destinatario será por defecto la Afiliado.
        // Si selecciona la opción otro destinatario principal: el destinataria dependerá del tipo de destinatario que se seleccione.

        // Caso 1: Arl, Caso 2: Afp, Caso 3: Eps, Caso 4: Afiliado, Caso 5: Empleador.
        if ($otrodestinariop == '') {
            $Destinatario = 'Afiliado';
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
                'Oficio_pcl' => $oficiopcl,
                'Oficio_incapacidad' => $oficioinca,
                'Formatob' => $formatob,
                'Formatoc' => $formatoc,
                'Formatod' => $formatod,
                'Formatoe' => $formatoe,
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
                ['ID_evento',$Id_EventoDecreto],
                ['Id_Asignacion',$Id_Asignacion_Dcreto]
            ])->update($datos_correspondencia);       
    
            $datos_info_comunicado_eventos = [
                'ID_Evento' => $Id_EventoDecreto,
                'Id_proceso' => $Id_ProcesoDecreto,
                'Id_Asignacion' => $Id_Asignacion_Dcreto,
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
                'Tipo_descarga' => $request->tipo_descarga,
                'Modulo_creacion' => 'recalificacionPCL',
                'Reemplazado' => 0,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
                'N_siniestro' => $N_siniestro
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
                'Oficio_pcl' => $oficiopcl,
                'Oficio_incapacidad' => $oficioinca,
                'Formatob' => $formatob,
                'Formatoc' => $formatoc,
                'Formatod' => $formatod,
                'Formatoe' => $formatoe,
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
                ['ID_evento',$Id_EventoDecreto],
                ['Id_Asignacion',$Id_Asignacion_Dcreto]
            ])->update($datos_correspondencia);   
            
            $datos_info_comunicado_eventos = [
                'ID_Evento' => $Id_EventoDecreto,
                'Id_proceso' => $Id_ProcesoDecreto,
                'Id_Asignacion' => $Id_Asignacion_Dcreto,
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
                'Tipo_descarga' => $request->tipo_descarga,
                'Modulo_creacion' => 'recalificacionPCL',
                'Reemplazado' => 0,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
                'N_siniestro' => $N_siniestro
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

    //Guardar dictamen pericial

    public function guardardictamenPericialRe(Request $request){

        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);
        $Decreto_pericial = $request->Decreto_pericial;
        $Id_EventoDecreto = $request->Id_EventoDecreto;
        $Id_ProcesoDecreto = $request->Id_ProcesoDecreto;
        $Id_Asignacion_Dcreto = $request->Id_Asignacion_Dcreto;
        $suma_combinada = $request->suma_combinada;
        $Total_Deficiencia50 = $request->Total_Deficiencia50;
        $total_discapacidades = $request->total_discapacidades;
        $total_minusvalia = $request->total_minusvalia;
        $total_porcentajePcl = $Total_Deficiencia50 + $total_discapacidades + $total_minusvalia;
        $radicado_dictamen = $request->radicado_dictamen;
        $porcentaje_pcl = $request->porcentaje_pcl;  
        $rango_pcl = $request->rango_pcl;     
        $monto_inde = $request->monto_inde;        
        $tipo_evento = $request->tipo_evento;        
        $tipo_origen = $request->tipo_origen;  
        $f_evento_pericial = $request->f_evento_pericial;
        $f_estructura_pericial = $request->f_estructura_pericial;
        $n_siniestro = $request->n_siniestro;
        $requiere_rev_pension = $request->requiere_rev_pension;
        $sustenta_fecha = $request->sustenta_fecha;        
        $detalle_califi = $request->detalle_califi;        
        $enfermedad_catastrofica = $request->enfermedad_catastrofica;        
        $enfermedad_congenita = $request->enfermedad_congenita;        
        $tipo_enfermedad = $request->tipo_enfermedad;        
        $requiere_persona = $request->requiere_persona;        
        $requiere_decisiones_persona = $request->requiere_decisiones_persona;        
        $requiere_dispositivo_apoyo = $request->requiere_dispositivo_apoyo;        
        $justi_dependencia = $request->justi_dependencia; 
        if (empty($requiere_persona) && empty($requiere_decisiones_persona) && empty($requiere_dispositivo_apoyo)) {
            $justi_dependencia = '';
        } else {
            $justi_dependencia = $justi_dependencia;
        }
        $bandera_dictamen_pericial = $request->bandera_dictamen_pericial;

        if ($bandera_dictamen_pericial == 'Guardar') {
            if($Decreto_pericial == 3){
                $datos_dictamenPericial =[
                    'Suma_combinada' => $suma_combinada,
                    'Total_Deficiencia50' => $Total_Deficiencia50,
                    'Porcentaje_pcl' => $total_porcentajePcl,
                    'Rango_pcl' => $rango_pcl,
                    'Monto_indemnizacion' => $monto_inde,
                    'Tipo_evento' => $tipo_evento,
                    'Origen' => $tipo_origen,
                    'F_evento' => $f_evento_pericial,
                    'F_estructuracion' => $f_estructura_pericial,
                    'Requiere_Revision_Pension' => $requiere_rev_pension,
                    'N_siniestro' => $n_siniestro,
                    'Sustentacion_F_estructuracion' => $sustenta_fecha,
                    'Detalle_calificacion' => $detalle_califi,
                    'Enfermedad_catastrofica' => $enfermedad_catastrofica,
                    'Enfermedad_congenita' => $enfermedad_congenita,
                    'Tipo_enfermedad' => $tipo_enfermedad,
                    'Requiere_tercera_persona' => $requiere_persona,
                    'Requiere_tercera_persona_decisiones' => $requiere_decisiones_persona,
                    'Requiere_dispositivo_apoyo' => $requiere_dispositivo_apoyo,
                    'Justificacion_dependencia' => $justi_dependencia,
                    'N_radicado'=> $radicado_dictamen,
                    'Estado_decreto' => 'Cerrado',
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_dictamenPericial); 

                // Actualizacion del profesional calificador
                $datos_profesional_calificador = [
                    'Id_calificador' => Auth::user()->id,
                    'Nombre_calificador' => Auth::user()->name,
                    'F_calificacion' => $date
                ];
            
                sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $Id_Asignacion_Dcreto)->update($datos_profesional_calificador);
    
                $datos_info_comunicado_eventos = [
                    'ID_Evento' => $Id_EventoDecreto,
                    'Id_proceso' => $Id_ProcesoDecreto,
                    'Id_Asignacion' => $Id_Asignacion_Dcreto,
                    'Ciudad' => 'N/A',
                    'F_comunicado' => $date,
                    'N_radicado' => $radicado_dictamen,
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
                    'Tipo_descarga' => 'Dictamen',
                    'Modulo_creacion' => 'recalificacionPCL',
                    'N_siniestro' => $n_siniestro,
                ];
        
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);
    
            }else{
                $datos_dictamenPericial =[
                    'Suma_combinada' => $suma_combinada,
                    'Total_Deficiencia50' => $Total_Deficiencia50,
                    'Porcentaje_pcl' => $porcentaje_pcl,
                    'Rango_pcl' => $rango_pcl,
                    'Monto_indemnizacion' => $monto_inde,
                    'Tipo_evento' => $tipo_evento,
                    'Origen' => $tipo_origen,
                    'F_evento' => $f_evento_pericial,
                    'F_estructuracion' => $f_estructura_pericial,
                    'Requiere_Revision_Pension' => $requiere_rev_pension,
                    'N_siniestro' => $n_siniestro,
                    'Sustentacion_F_estructuracion' => $sustenta_fecha,
                    'Detalle_calificacion' => $detalle_califi,
                    'Enfermedad_catastrofica' => $enfermedad_catastrofica,
                    'Enfermedad_congenita' => $enfermedad_congenita,
                    'Tipo_enfermedad' => $tipo_enfermedad,
                    'Requiere_tercera_persona' => $requiere_persona,
                    'Requiere_tercera_persona_decisiones' => $requiere_decisiones_persona,
                    'Requiere_dispositivo_apoyo' => $requiere_dispositivo_apoyo,
                    'Justificacion_dependencia' => $justi_dependencia,
                    'N_radicado'=> $radicado_dictamen,
                    'Estado_decreto' => 'Cerrado',
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_dictamenPericial); 

                // Actualizacion del profesional calificador
                $datos_profesional_calificador = [
                    'Id_calificador' => Auth::user()->id,
                    'Nombre_calificador' => Auth::user()->name,
                    'F_calificacion' => $date
                ];
            
                sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $Id_Asignacion_Dcreto)->update($datos_profesional_calificador);
                
                $datos_info_comunicado_eventos = [
                    'ID_Evento' => $Id_EventoDecreto,
                    'Id_proceso' => $Id_ProcesoDecreto,
                    'Id_Asignacion' => $Id_Asignacion_Dcreto,
                    'Ciudad' => 'N/A',
                    'F_comunicado' => $date,
                    'N_radicado' => $radicado_dictamen,
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
                    'Tipo_descarga' => 'Dictamen',
                    'Modulo_creacion' => 'recalificacionPCL',
                    'N_siniestro' => $n_siniestro,
                ];
        
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_eventos);
            }    
            $mensajes = array(
                "parametro" => 'insertar_dictamen_pericial',
                "mensaje" => 'Concepto final del dictamen pericial guardado satisfactoriamente.'
            );            
        } elseif($bandera_dictamen_pericial == 'Actualizar') {
            if($Decreto_pericial == 3){
                $datos_dictamenPericial =[
                    'Suma_combinada' => $suma_combinada,
                    'Total_Deficiencia50' => $Total_Deficiencia50,
                    'Porcentaje_pcl' => $total_porcentajePcl,
                    'Rango_pcl' => $rango_pcl,
                    'Monto_indemnizacion' => $monto_inde,
                    'Tipo_evento' => $tipo_evento,
                    'Origen' => $tipo_origen,
                    'F_evento' => $f_evento_pericial,
                    'F_estructuracion' => $f_estructura_pericial,
                    'Requiere_Revision_Pension' => $requiere_rev_pension,
                    'N_siniestro' => $n_siniestro,
                    'Sustentacion_F_estructuracion' => $sustenta_fecha,
                    'Detalle_calificacion' => $detalle_califi,
                    'Enfermedad_catastrofica' => $enfermedad_catastrofica,
                    'Enfermedad_congenita' => $enfermedad_congenita,
                    'Tipo_enfermedad' => $tipo_enfermedad,
                    'Requiere_tercera_persona' => $requiere_persona,
                    'Requiere_tercera_persona_decisiones' => $requiere_decisiones_persona,
                    'Requiere_dispositivo_apoyo' => $requiere_dispositivo_apoyo,
                    'Justificacion_dependencia' => $justi_dependencia,
                    'N_radicado'=> $radicado_dictamen,
                    'Estado_decreto' => 'Cerrado',
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_dictamenPericial); 

            }else{
                $datos_dictamenPericial =[
                    'Suma_combinada' => $suma_combinada,
                    'Total_Deficiencia50' => $Total_Deficiencia50,
                    'Porcentaje_pcl' => $porcentaje_pcl,
                    'Rango_pcl' => $rango_pcl,
                    'Monto_indemnizacion' => $monto_inde,
                    'Tipo_evento' => $tipo_evento,
                    'Origen' => $tipo_origen,
                    'F_evento' => $f_evento_pericial,
                    'F_estructuracion' => $f_estructura_pericial,
                    'Requiere_Revision_Pension' => $requiere_rev_pension,
                    'N_siniestro' => $n_siniestro,
                    'Sustentacion_F_estructuracion' => $sustenta_fecha,
                    'Detalle_calificacion' => $detalle_califi,
                    'Enfermedad_catastrofica' => $enfermedad_catastrofica,
                    'Enfermedad_congenita' => $enfermedad_congenita,
                    'Tipo_enfermedad' => $tipo_enfermedad,
                    'Requiere_tercera_persona' => $requiere_persona,
                    'Requiere_tercera_persona_decisiones' => $requiere_decisiones_persona,
                    'Requiere_dispositivo_apoyo' => $requiere_dispositivo_apoyo,
                    'Justificacion_dependencia' => $justi_dependencia,
                    'N_radicado'=> $radicado_dictamen,
                    'Estado_decreto' => 'Cerrado',
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_dictamenPericial);  
                
            }
            $comunicado_reemplazado = [
                'Reemplazado' => 0,
                'N_siniestro' => $n_siniestro,
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_EventoDecreto],
                    ['Id_Asignacion',$Id_Asignacion_Dcreto],
                    ['N_radicado',$radicado_dictamen]
                    ])
            ->update($comunicado_reemplazado);
            // dd($comunicado_reemplazado);
            $mensajes = array(
                "parametro" => 'insertar_dictamen_pericial',
                "mensaje" => 'Concepto final del dictamen pericial actualizado satisfactoriamente.'
            );
        }
        return json_decode(json_encode($mensajes, true));
    }

    // Generar PDF del Dictamen de PCL 1507

    public function generarPdfDictamenPclRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        $ID_Evento_comuni = $request->ID_Evento_comuni;
        $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
        $Id_Proceso_comuni = $request->Id_Proceso_comuni;
        $Radicado_comuni = $request->Radicado_comuni;
        $Id_Comunicado = $request->Id_Comunicado;
        // $N_siniestro = $request->N_siniestro;
        
        $formattedData = "";

        $dictamenPclQr = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_decreto_eventos as side', 'side.Id_Asignacion', '=', 'siae.Id_Asignacion')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_afiliado_eventos as siaf', 'siaf.ID_evento', '=', 'siae.ID_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siaf.Tipo_documento')
        ->select('siaf.Nombre_afiliado', 'slp.Nombre_parametro', 'siaf.Nro_identificacion', 'siae.Consecutivo_dictamen', 
        'side.Porcentaje_pcl', 'side.F_estructuracion', 'siae.ID_evento')
        ->where('siae.Id_Asignacion', $Id_Asignacion_comuni)->get();     

        if (!$dictamenPclQr->isEmpty()) {
            // Crear una cadena para almacenar los datos en el formato deseado                    
        
            foreach ($dictamenPclQr as $evento) {
                // Construir la cadena de texto con el formato deseado
                $formattedData .= $evento->Nombre_afiliado."\n";
                $formattedData .= $evento->Nombre_parametro." ".$evento->Nro_identificacion . "\n";
                $formattedData .= "N° Dictámen: ".$evento->Consecutivo_dictamen."\n";
                $formattedData .= "CALIFICACIÓN: ".$evento->Porcentaje_pcl."\n";
                $formattedData .= "Fecha estructuración: ".$evento->F_estructuracion."\n";
                $formattedData .= "Cod. Verificación: ".$evento->ID_evento."\n";
        
                // Agregar un salto de línea después de cada conjunto de atributos de evento
                $formattedData .= "\n";
            }
                            
        }

        // Codigo QR
        $datos = $formattedData;
        $codigoQR = QrCode::size(110)->margin(0.5)->generate($datos);          

        //Captura de datos de informacion general del dictamen pericial

        $fecha_dictamen = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->select('F_visado_comite')->where([['ID_evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get();
        if(count($fecha_dictamen) == 0){
            $Fecha_dictamen = '';
        }else{
            $Fecha_dictamen = $fecha_dictamen[0]->F_visado_comite;
        }
        $array_datos_info_dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as sltp', 'sltp.Id_Evento', '=', 'side.Tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'side.Tipo_enfermedad')
        ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'side.Cobertura', 'side.Decreto_calificacion', 
        'side.Numero_dictamen', 'side.PCL_anterior', 'side.Descripcion_nueva_calificacion', 'side.Relacion_documentos', 'side.Otros_relacion_doc', 
        'side.Descripcion_enfermedad_actual', 'side.Suma_combinada', 'side.Total_Deficiencia50', 'side.Porcentaje_pcl', 'side.Rango_pcl', 
        'side.Monto_indemnizacion', 'side.Tipo_evento', 'sltp.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro as Nombre_origen', 'side.F_evento', 
        'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slpa.Nombre_parametro as Nombre_enfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.N_radicado', 
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro', 'side.N_siniestro')
        ->where([['side.ID_Evento',$ID_Evento_comuni], ['side.Id_Asignacion',$Id_Asignacion_comuni]])->get();        
        $DictamenNo = $array_datos_info_dictamen[0]->Numero_dictamen;
        $N_siniestro = $array_datos_info_dictamen[0]->N_siniestro;
                
        $motivo_solicitud_dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pericial_eventos as sipe')
        ->leftJoin('sigmel_gestiones.sigmel_lista_motivo_solicitudes as slms', 'slms.Id_Solicitud', '=', 'sipe.Id_motivo_solicitud')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sipe.Regimen_salud')
        ->leftJoin('sigmel_gestiones.sigmel_lista_solicitantes as sls', 'sls.Id_solicitante', '=', 'sipe.Id_solicitante')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sipe.Id_nombre_solicitante')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
        ->select('sipe.Id_motivo_solicitud','slms.Nombre_solicitud', 'sipe.Regimen_salud', 'slp.Nombre_parametro as Regimenes_salud', 
        'sipe.Id_solicitante', 'sls.Solicitante', 'sipe.Id_nombre_solicitante', 'sie.Nombre_entidad', 'sie.Nit_entidad', 'sie.Telefonos', 
        'sie.Emails', 'sie.Direccion', 'sie.Id_Ciudad', 'sldm.Nombre_municipio')
        ->where([['ID_evento',$ID_Evento_comuni]])->limit(1)->get();        
        $Motivo_solicitud = $motivo_solicitud_dictamen[0]->Nombre_solicitud;
        $Id_solicitante_dic = $motivo_solicitud_dictamen[0]->Id_solicitante;

        $array_datos_info_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siae.Tipo_documento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'siae.Nivel_escolar')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpar', 'slpar.Id_Parametro', '=', 'siae.Estado_civil')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'siae.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmu', 'sldmu.Id_municipios', '=', 'siae.Id_municipio_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'siae.Id_eps')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sien', 'sien.Id_Entidad', '=', 'siae.Id_afp')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sient', 'sient.Id_Entidad', '=', 'siae.Id_arl')
        ->select('siae.ID_evento', 'siae.Nombre_afiliado', 'siae.Tipo_documento', 'slp.Nombre_parametro as T_documento', 'siae.Nro_identificacion', 
        'siae.F_nacimiento', 'siae.Edad', 'siae.Genero', 'siae.Email', 'siae.Telefono_contacto', 'siae.Estado_civil', 
        'slpar.Nombre_parametro as Estado_civi', 'siae.Nivel_escolar', 'slpa.Nombre_parametro as Escolaridad', 
        'siae.Apoderado', 'siae.Nombre_apoderado', 'siae.Nro_identificacion_apoderado', 'siae.Id_dominancia', 'siae.Direccion', 
        'siae.Id_departamento', 'siae.Id_municipio', 'sldm.Nombre_municipio as Nombre_municipio', 'siae.Ocupacion', 'siae.Tipo_afiliado', 
        'siae.Ibc', 'siae.Id_eps', 'sie.Nombre_entidad as Entidad_eps', 'siae.Id_afp', 'sien.Nombre_entidad as Entidad_afp', 'siae.Id_arl', 
        'sient.Nombre_entidad as Entidad_arl', 'siae.Activo', 'siae.Medio_notificacion', 'siae.Nombre_afiliado_benefi', 
        'siae.Tipo_documento_benefi', 'siae.Nro_identificacion_benefi', 'siae.Direccion_benefi', 'siae.Id_departamento_benefi', 
        'siae.Id_municipio_benefi', 'sldmu.Nombre_municipio as Nombre_municipio_benefi', 'siae.Nombre_usuario', 'siae.F_registro', 
        'F_actualizacion')
        ->where([['ID_Evento',$ID_Evento_comuni]])->get();        

        $Tipo_afiliado = $array_datos_info_afiliado[0]->Tipo_afiliado;
        $Ocupacion_afiliado = $array_datos_info_afiliado[0]->Ocupacion;

        if ($Tipo_afiliado !== 27 ) {
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $NroIden_afiliado_dic = $array_datos_info_afiliado[0]->Nro_identificacion;
            $Telefono_afiliado_dic = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Email_afiliado_dic = $array_datos_info_afiliado[0]->Email;
            $Direccion_afiliado_dic = $array_datos_info_afiliado[0]->Direccion;
            $Ciudad_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_municipio;
        }else{
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
            $NroIden_afiliado_dic = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
            $Telefono_afiliado_dic = '';
            $Email_afiliado_dic = '';
            $Direccion_afiliado_dic = $array_datos_info_afiliado[0]->Direccion_benefi;
            $Ciudad_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
        }

        if($Id_solicitante_dic == 1 || $Id_solicitante_dic == 2 ||  $Id_solicitante_dic == 3){
            $Solicitante_dic = $motivo_solicitud_dictamen[0]->Solicitante;
            $Nombre_entidad_dic = $motivo_solicitud_dictamen[0]->Nombre_entidad;
            $Nit_entidad = $motivo_solicitud_dictamen[0]->Nit_entidad;
            $Telefonos_dic = $motivo_solicitud_dictamen[0]->Telefonos;
            $Emails_dic = $motivo_solicitud_dictamen[0]->Emails;
            $Direccion_dic = $motivo_solicitud_dictamen[0]->Direccion;
            $Nombre_municipio_dic = $motivo_solicitud_dictamen[0]->Nombre_municipio;
        }else{
            $Solicitante_dic = $motivo_solicitud_dictamen[0]->Solicitante;
            $Nombre_entidad_dic = $Nombre_afiliado_dic;
            $Nit_entidad = $NroIden_afiliado_dic;
            $Telefonos_dic = $Telefono_afiliado_dic;
            $Emails_dic = $Email_afiliado_dic;
            $Direccion_dic = $Direccion_afiliado_dic;
            $Nombre_municipio_dic = $Ciudad_afiliado_dic;
        }

        //Captura de datos de informacion general de la entidad calificadora

        $array_datos_info_entidad_cali = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
        ->leftJoin('sigmel_gestiones.sigmel_clientes as sc', 'sc.Id_cliente', '=', 'sie.Cliente')
        ->select('sie.ID_evento', 'sie.Cliente', 'sc.Nombre_cliente', 'sc.Nit', 'sc.Telefono_principal', 'sc.Direccion', 'sc.Email_principal')
        ->where([['sie.ID_evento',$ID_Evento_comuni]])->get();                
        
        $Cliente = $array_datos_info_entidad_cali[0]->Cliente;        
        // Logo cliente del Header

        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
        } else {
            $logo_header = "Sin logo";
        }       

        $Nombre_cliente_ent = $array_datos_info_entidad_cali[0]->Nombre_cliente;
        $Nit_ent = $array_datos_info_entidad_cali[0]->Nit;
        $Telefono_principal_ent = $array_datos_info_entidad_cali[0]->Telefono_principal;
        $Direccion_ent = $array_datos_info_entidad_cali[0]->Direccion;
        $Email_principal_ent = $array_datos_info_entidad_cali[0]->Email_principal;        

        //Captura de datos generales de la persona calificada

        if ($Tipo_afiliado == 27) {
            $Afiliado_per_cal = '';
            $Beneficiario_per_cal = 'X';
            function separarNombreApellido($nombreCompleto) {
                // Dividir la cadena en palabras
                $palabras = explode(' ', $nombreCompleto);
                $numPalabras = count($palabras);
            
                if ($numPalabras == 2) {
                    $nombre = $palabras[0];
                    $apellido = $palabras[1];
                } elseif ($numPalabras == 3) {
                    $nombre = $palabras[0];
                    $apellido = implode(' ', array_slice($palabras, 1));
                } elseif ($numPalabras == 4) {
                    $nombre = implode(' ', array_slice($palabras, 0, 2));
                    $apellido = implode(' ', array_slice($palabras, 2));
                } else {
                    $nombre = '';
                    $apellido = '';
                }
            
                return array('nombre' => $nombre, 'apellido' => $apellido);
            }  
            $Nombre_per_cal = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $ResultadoNombre_per_cal = separarNombreApellido($Nombre_per_cal);            
            $Tipo_documento_per_cal = $array_datos_info_afiliado[0]->T_documento;            
            $NroIden_per_cal = $array_datos_info_afiliado[0]->Nro_identificacion;
            $F_nacimiento_per_cal = $array_datos_info_afiliado[0]->F_nacimiento;            
            $Edad_per_cal = $array_datos_info_afiliado[0]->Edad;            
            $Nivel_escolar_per_cal = $array_datos_info_afiliado[0]->Escolaridad;
            $Estado_civil_per_cal = $array_datos_info_afiliado[0]->Estado_civi;
            $Telefono_per_cal = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Direccion_per_cal = $array_datos_info_afiliado[0]->Direccion;
            $Ciudad_per_cal = $array_datos_info_afiliado[0]->Nombre_municipio;
            $Email_per_cal = $array_datos_info_afiliado[0]->Email;
            $Nombre_ben = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
            $Tipo_iden_ben = $array_datos_info_afiliado[0]->Tipo_documento_benefi;            
            $Documento_iden_ben = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
            $Telefono_iden_ben = '';
            $Ciudad_iden_ben = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
            //Datod del acudiente
            if($Edad_per_cal < 18){
                $Nombre_acudiente = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
                $Documento_acudiente = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
                $Telefono_acudiente = '';
                $Ciudad_acudiente = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
            }else{
                $Nombre_acudiente = '';
                $Documento_acudiente = '';
                $Telefono_acudiente = '';
                $Ciudad_acudiente = '';
            }
        }else {
            $Afiliado_per_cal = 'X';
            $Beneficiario_per_cal = '';
            function separarNombreApellido($nombreCompleto) {
                // Dividir la cadena en palabras
                $palabras = explode(' ', $nombreCompleto);
                $numPalabras = count($palabras);
            
                if ($numPalabras == 2) {
                    $nombre = $palabras[0];
                    $apellido = $palabras[1];
                } elseif ($numPalabras == 3) {
                    $nombre = $palabras[0];
                    $apellido = implode(' ', array_slice($palabras, 1));
                } elseif ($numPalabras == 4) {
                    $nombre = implode(' ', array_slice($palabras, 0, 2));
                    $apellido = implode(' ', array_slice($palabras, 2));
                } else {
                    $nombre = '';
                    $apellido = '';
                }
            
                return array('nombre' => $nombre, 'apellido' => $apellido);
            }  
            $Nombre_per_cal = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $ResultadoNombre_per_cal = separarNombreApellido($Nombre_per_cal);            
            $Tipo_documento_per_cal = $array_datos_info_afiliado[0]->T_documento;            
            $NroIden_per_cal = $array_datos_info_afiliado[0]->Nro_identificacion;
            $F_nacimiento_per_cal = $array_datos_info_afiliado[0]->F_nacimiento;            
            $Edad_per_cal = $array_datos_info_afiliado[0]->Edad;            
            $Nivel_escolar_per_cal = $array_datos_info_afiliado[0]->Escolaridad;
            $Estado_civil_per_cal = $array_datos_info_afiliado[0]->Estado_civi;
            $Telefono_per_cal = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Direccion_per_cal = $array_datos_info_afiliado[0]->Direccion;
            $Ciudad_per_cal = $array_datos_info_afiliado[0]->Nombre_municipio;
            $Email_per_cal = $array_datos_info_afiliado[0]->Email;
            $Nombre_ben = '';
            $Tipo_iden_ben = '';
            $Documento_iden_ben = '';
            $Telefono_iden_ben = '';
            $Ciudad_iden_ben = '';
            $Nombre_acudiente = '';
            $Documento_acudiente = '';
            $Telefono_acudiente = '';
            $Ciudad_acudiente = '';
        }

        if ($Documento_iden_ben == '') {
            $Numero_documento_afiliado = $NroIden_per_cal;
            $Documento_afiliado = $Tipo_documento_per_cal;
            $Nombre_afiliado_pre = $Nombre_per_cal;
        } else {            
            $Numero_documento_afiliado = $Documento_iden_ben;
            $Documento_afiliado = $Tipo_iden_ben;
            $Nombre_afiliado_pre = $Nombre_ben;
        }
        

        //Captura de datos de Etapas del ciclo vital

        $validar_laboralmente_activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion','Activo']])->get();       

        if (count($validar_laboralmente_activo) > 0) {
            $Poblacion_edad_econo_activa = 'X';
        }else{
            $Poblacion_edad_econo_activa = '';
        }        

        $validar_rol_ocupacional = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion','Activo']])->get();       

        if (count($validar_rol_ocupacional) > 0) {
            if ($validar_rol_ocupacional[0]->Poblacion_calificar == 75) {
                $Bebe_menor3 = 'X';
                $Ninos_adolecentes = '';
                $Adultos_mayores = '';                
            }elseif($validar_rol_ocupacional[0]->Poblacion_calificar == 76){
                $Bebe_menor3 = '';
                $Ninos_adolecentes = 'X';
                $Adultos_mayores = '';
            }elseif($validar_rol_ocupacional[0]->Poblacion_calificar == 77){
                $Bebe_menor3 = '';
                $Ninos_adolecentes = '';
                $Adultos_mayores = 'X';
            }
            
        }else{
            $Bebe_menor3 = '';
            $Ninos_adolecentes = '';
            $Adultos_mayores = '';
        } 

        //Captura de datos de Afiliacion al siss:

        $Regimen_salud_ecv = $motivo_solicitud_dictamen[0]->Regimen_salud;
        
        if($Regimen_salud_ecv == 37) {
            $Contributivo_ecv = 'X';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = '';
        }elseif($Regimen_salud_ecv == 38){
            $Contributivo_ecv = '';
            $Subsidiado_ecv = 'X';
            $No_afiliado_ecv = '';
        }elseif($Regimen_salud_ecv == 39){
            $Contributivo_ecv = '';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = 'X';
        }else{
            $Contributivo_ecv = '';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = '';
        }
        
        $Entidad_eps = $array_datos_info_afiliado[0]->Entidad_eps;
        $Entidad_afp = $array_datos_info_afiliado[0]->Entidad_afp;
        $Entidad_arl = $array_datos_info_afiliado[0]->Entidad_arl;

        //Captura de datos Antecedentes laborales del calificado

        $array_datos_info_antecedentes_laborales = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
        ->select('sile.Tipo_empleado', 'sile.Cargo', 'sile.Id_codigo_ciuo', 'slcc.Nombre_ciuo', 'sile.Funciones_cargo', 'sile.Empresa', 
        'sile.Nit_o_cc')->where([['ID_Evento',$ID_Evento_comuni]])->get();

        $Tipo_empleado_laboral = $array_datos_info_antecedentes_laborales[0]->Tipo_empleado;

        if ($Tipo_empleado_laboral == 'Empleado actual') {
            $Independiente_laboral = '';
            $Dedependiente_laboral = 'X';
        } else {
            $Independiente_laboral = 'X';
            $Dedependiente_laboral = '';
        }

        $Nombre_cargo_laboral = $array_datos_info_antecedentes_laborales[0]->Cargo;
        $Codigo_ciuo_laboral = $array_datos_info_antecedentes_laborales[0]->Nombre_ciuo;
        $Funciones_cargo_laboral = $array_datos_info_antecedentes_laborales[0]->Funciones_cargo;
        $Empresa_laboral = $array_datos_info_antecedentes_laborales[0]->Empresa;
        $Nit_laboral = $array_datos_info_antecedentes_laborales[0]->Nit_o_cc;    
        
        //Captura de datos Realacion de documentos/examenes fisico(Descripción)

        $array_datos_relacion_examentes = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado_Recalificacion','Activo']])->get();  

        //Captura de datos Fundamentos para la calificacion de la perdida de la capacidad laboral y ocupacional - titulos I Y II

        $Descripcion_enfermedad_actual = $array_datos_info_dictamen[0]->Descripcion_enfermedad_actual;

        $array_diagnosticos_fc = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro as Nombre_origen', 
        'slp2.Nombre_parametro as Nombre_lateralidad', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado_Recalificacion','Activo']])->get();  

        $array_deficiencias_alteraciones = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
        ->select('sidae.Id_tabla', 'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.FU', 'sidae.CFM1', 'sidae.CFM2', 
        'sidae.Clase_Final', 'sidae.Dominancia', 'sidae.Deficiencia', 'sidae.Total_deficiencia', 'sidae.CAT', 'sidae.MSD')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion','Activo']])
        ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
        ->get();  
        
        $Suma_combinada_fc = $array_datos_info_dictamen[0]->Suma_combinada;

        $array_deficiencia_auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();

        $array_deficiencia_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get(); 
        
        $array_deficiencia_visualre = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
        ->where([['ID_evento_re',$ID_Evento_comuni], ['Id_Asignacion_re',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get(); 

        $Total_deficiencia50_fc = $array_datos_info_dictamen[0]->Total_Deficiencia50;

        $array_datos_laboralmente_activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();  

        $array_datos_rol_ocupacional = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();

        //Captura de datos Concepto final del dictamen pericial
        
        $Porcentaje_Pcl_dp = $array_datos_info_dictamen[0]->Porcentaje_pcl;
        $F_estructuracion_dp = $array_datos_info_dictamen[0]->F_estructuracion;
        $Tipo_evento_dp = $array_datos_info_dictamen[0]->Nombre_evento;
        $Sustentacion_F_estructuracion_dp = $array_datos_info_dictamen[0]->Sustentacion_F_estructuracion;
        $F_evento_dp = $array_datos_info_dictamen[0]->F_evento;
        $Origen_dp = $array_datos_info_dictamen[0]->Nombre_origen;
        $Detalle_calificacion_dp = $array_datos_info_dictamen[0]->Detalle_calificacion;
        $Enfermedad_catastrofica_dp = $array_datos_info_dictamen[0]->Enfermedad_catastrofica;
        $Enfermedad_congenita_dp = $array_datos_info_dictamen[0]->Enfermedad_congenita;
        $validar_servicio_revision_pension = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->select('Id_servicio')->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get();  
        $Revision_pension_dp = $validar_servicio_revision_pension[0]->Id_servicio;
        $Nombre_enfermedad_dp = $array_datos_info_dictamen[0]->Nombre_enfermedad;
        $Requiere_tercera_persona_dp = $array_datos_info_dictamen[0]->Requiere_tercera_persona;
        $Requiere_tercera_persona_decisiones_dp = $array_datos_info_dictamen[0]->Requiere_tercera_persona_decisiones;
        $Requiere_dispositivo_apoyo_dp = $array_datos_info_dictamen[0]->Requiere_dispositivo_apoyo;
        $Justificacion_dependencia_dp = $array_datos_info_dictamen[0]->Justificacion_dependencia;

        //consulta si esta visado o no para mostrar las firmas

        $validacion_visado = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->select('ID_evento', 'Id_proceso', 'Id_Asignacion', 'Visar')
        ->where([['Id_Asignacion',$Id_Asignacion_comuni], ['Visar','Si']])->get();
               
        //Obtener los datos del formulario
        
        $data = [
            'logo_header' => $logo_header,
            'Id_cliente_ent' => $Cliente,
            'codigoQR' => $codigoQR,
            'ID_evento' => $ID_Evento_comuni,
            'Id_Asignacion' => $Id_Asignacion_comuni,
            'Id_proceso' => $Id_Proceso_comuni,
            'Radicado_comuni' => $Radicado_comuni,
            'Fecha_dictamen'=> $Fecha_dictamen,
            'DictamenNo' => $DictamenNo,
            'Motivo_solicitud' => $Motivo_solicitud,
            'Solicitante_dic' => $Solicitante_dic,
            'Nombre_entidad_dic' => $Nombre_entidad_dic,
            'Nit_entidad' => $Nit_entidad,
            'Telefonos_dic' => $Telefonos_dic,
            'Emails_dic' => $Emails_dic,
            'Direccion_dic' => $Direccion_dic,
            'Nombre_municipio_dic' => $Nombre_municipio_dic,
            'Nombre_cliente_ent' => $Nombre_cliente_ent,
            'Nit_ent' => $Nit_ent,
            'Telefono_principal_ent' => $Telefono_principal_ent,
            'Direccion_ent' => $Direccion_ent,
            'Email_principal_ent' => $Email_principal_ent,
            'Afiliado_per_cal' => $Afiliado_per_cal,
            'Beneficiario_per_cal' => $Beneficiario_per_cal,
            'ResultadoNombre_per_cal' => $Nombre_per_cal,
            'Tipo_documento_per_cal' => $Tipo_documento_per_cal,
            'NroIden_per_cal' => $NroIden_per_cal,
            'F_nacimiento_per_cal' => $F_nacimiento_per_cal,
            'Edad_per_cal' => $Edad_per_cal,
            'Nivel_escolar_per_cal' => $Nivel_escolar_per_cal,
            'Estado_civil_per_cal' => $Estado_civil_per_cal,
            'Telefono_per_cal' => $Telefono_per_cal,
            'Direccion_per_cal' => $Direccion_per_cal,
            'Ciudad_per_cal' => $Ciudad_per_cal,
            'Email_per_cal' => $Email_per_cal,
            'Nombre_ben' => $Nombre_ben,
            'Documento_iden_ben' => $Documento_iden_ben,
            'Telefono_iden_ben' => $Telefono_iden_ben,
            'Ciudad_iden_ben' => $Ciudad_iden_ben,
            'Poblacion_edad_econo_activa' => $Poblacion_edad_econo_activa,
            'Bebe_menor3' => $Bebe_menor3,
            'Ninos_adolecentes' => $Ninos_adolecentes,
            'Adultos_mayores' => $Adultos_mayores,
            'Nombre_acudiente' => $Nombre_acudiente,
            'Documento_acudiente' => $Documento_acudiente,
            'Telefono_acudiente' => $Telefono_acudiente,
            'Ciudad_acudiente' => $Ciudad_acudiente,
            'Contributivo_ecv' => $Contributivo_ecv,
            'Subsidiado_ecv' => $Subsidiado_ecv,
            'No_afiliado_ecv' => $No_afiliado_ecv,
            'Entidad_eps' => $Entidad_eps,
            'Entidad_afp' => $Entidad_afp,
            'Entidad_arl' => $Entidad_arl,
            'Independiente_laboral' => $Independiente_laboral,
            'Dedependiente_laboral' => $Dedependiente_laboral,
            'Nombre_cargo_laboral' => $Nombre_cargo_laboral,
            'Ocupacion_afiliado' => $Ocupacion_afiliado,
            'Codigo_ciuo_laboral' => $Codigo_ciuo_laboral,
            'Funciones_cargo_laboral' => $Funciones_cargo_laboral,
            'Empresa_laboral' => $Empresa_laboral,
            'Nit_laboral' => $Nit_laboral,
            'array_datos_relacion_examentes' => $array_datos_relacion_examentes,
            'Descripcion_enfermedad_actual' => $Descripcion_enfermedad_actual,
            'array_diagnosticos_fc' => $array_diagnosticos_fc,
            'array_deficiencias_alteraciones' => $array_deficiencias_alteraciones,
            'Suma_combinada_fc' => $Suma_combinada_fc,
            'array_deficiencia_auditiva' => $array_deficiencia_auditiva,
            'array_deficiencia_visual' => $array_deficiencia_visual,
            'array_deficiencia_visualre' => $array_deficiencia_visualre,
            'Total_deficiencia50_fc' => $Total_deficiencia50_fc,
            'array_datos_laboralmente_activo' => $array_datos_laboralmente_activo,
            'array_datos_rol_ocupacional' => $array_datos_rol_ocupacional,
            'Porcentaje_Pcl_dp' => $Porcentaje_Pcl_dp,
            'F_estructuracion_dp' => $F_estructuracion_dp,
            'Tipo_evento_dp' => $Tipo_evento_dp,
            'Sustentacion_F_estructuracion_dp' => $Sustentacion_F_estructuracion_dp,
            'F_evento_dp' => $F_evento_dp,
            'Origen_dp' => $Origen_dp,
            'Detalle_calificacion_dp' => $Detalle_calificacion_dp,
            'Enfermedad_catastrofica_dp' => $Enfermedad_catastrofica_dp,
            'Enfermedad_congenita_dp' => $Enfermedad_congenita_dp,
            'Revision_pension_dp' => $Revision_pension_dp,      
            'Nombre_enfermedad_dp' => $Nombre_enfermedad_dp,
            'Requiere_tercera_persona_dp' => $Requiere_tercera_persona_dp,
            'Requiere_tercera_persona_decisiones_dp' => $Requiere_tercera_persona_decisiones_dp,
            'Requiere_dispositivo_apoyo_dp' => $Requiere_dispositivo_apoyo_dp,
            'Justificacion_dependencia_dp' => $Justificacion_dependencia_dp,
            'Numero_documento_afiliado' => $Numero_documento_afiliado,
            'Documento_afiliado' => $Documento_afiliado,
            'Nombre_afiliado_pre' => $Nombre_afiliado_pre,
            'validacion_visado' => $validacion_visado,
            'N_siniestro' => $N_siniestro
        ];

        // Crear una instancia de Dompdf
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/PCL/dictamen_Pcl1507prev', $data);        
        $nombre_pdf = 'PCL_DML_'.$Id_Asignacion_comuni.'_'.$Numero_documento_afiliado.'.pdf';    
        //Obtener el contenido del PDF
        $output = $pdf->output();
        //Guardar el PDF en un archivo
        file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni}/{$nombre_pdf}"), $output);
        $actualizar_nombre_documento = [
            'Nombre_documento' => $nombre_pdf
        ];
        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
        ->update($actualizar_nombre_documento);

        /* Inserción del registro de que fue descargado */
        // Extraemos el id del servicio asociado
        $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->select('siae.Id_servicio')
        ->where([
            ['siae.Id_Asignacion', $Id_Asignacion_comuni],
            ['siae.ID_evento', $ID_Evento_comuni],
            ['siae.Id_proceso', $Id_Proceso_comuni],
        ])->get();

        $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        ->select('sice.F_comunicado')
        ->where([
            ['sice.N_radicado', $Radicado_comuni]
        ])
        ->get();

        $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

        // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
        $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        ->select('Nombre_documento')
        ->where([
            ['Nombre_documento', $nombre_pdf],
        ])->get();
        
        if(count($verficar_documento) == 0){
            $info_descarga_documento = [
                'Id_Asignacion' => $Id_Asignacion_comuni,
                'Id_proceso' => $Id_Proceso_comuni,
                'Id_servicio' => $Id_servicio,
                'ID_evento' => $ID_Evento_comuni,
                'Nombre_documento' => $nombre_pdf,
                'N_radicado_documento' => $Radicado_comuni,
                'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                'F_descarga_documento' => $date,
                'Nombre_usuario' => $nombre_usuario,
            ];
            
            sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        }

        return $pdf->download($nombre_pdf);
    }
    // Generar PDF del Dictamen de PCL 917

    public function generarPdfDictamenPcl917Re(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        $ID_Evento_comuni = $request->ID_Evento_comuni;
        $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
        $Id_Proceso_comuni = $request->Id_Proceso_comuni;
        $Radicado_comuni = $request->Radicado_comuni;
        $Id_Comunicado = $request->Id_Comunicado;
        // $N_siniestro = $request->N_siniestro;
        
        $formattedData = "";

        $dictamenPclQr = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_decreto_eventos as side', 'side.Id_Asignacion', '=', 'siae.Id_Asignacion')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_afiliado_eventos as siaf', 'siaf.ID_evento', '=', 'siae.ID_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siaf.Tipo_documento')
        ->select('siaf.Nombre_afiliado', 'slp.Nombre_parametro', 'siaf.Nro_identificacion', 'siae.Consecutivo_dictamen', 
        'side.Porcentaje_pcl', 'side.F_estructuracion', 'siae.ID_evento')
        ->where('siae.Id_Asignacion', $Id_Asignacion_comuni)->get();     

        if (!$dictamenPclQr->isEmpty()) {
            // Crear una cadena para almacenar los datos en el formato deseado                    
        
            foreach ($dictamenPclQr as $evento) {
                // Construir la cadena de texto con el formato deseado
                $formattedData .= $evento->Nombre_afiliado."\n";
                $formattedData .= $evento->Nombre_parametro." ".$evento->Nro_identificacion . "\n";
                $formattedData .= "N° Dictámen: ".$evento->Consecutivo_dictamen."\n";
                $formattedData .= "CALIFICACIÓN: ".$evento->Porcentaje_pcl."\n";
                $formattedData .= "Fecha estructuración: ".$evento->F_estructuracion."\n";
                $formattedData .= "Cod. Verificación: ".$evento->ID_evento."\n";
        
                // Agregar un salto de línea después de cada conjunto de atributos de evento
                $formattedData .= "\n";
            }
                            
        }

        // Codigo QR
        $datos = $formattedData;
        $codigoQR = QrCode::size(110)->margin(0.5)->generate($datos);         

        //Captura de datos de informacion general del dictamen pericial

        $fecha_dictamen = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->select('F_visado_comite')->where([['ID_evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get();
        if(count($fecha_dictamen) == 0){
            $Fecha_dictamen = '';
        }else{
            $Fecha_dictamen = $fecha_dictamen[0]->F_visado_comite;
        }
        $array_datos_info_dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as sltp', 'sltp.Id_Evento', '=', 'side.Tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'side.Tipo_enfermedad')
        ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'side.Cobertura', 'side.Decreto_calificacion', 
        'side.Numero_dictamen', 'side.PCL_anterior', 'side.Descripcion_nueva_calificacion', 'side.Relacion_documentos', 'side.Otros_relacion_doc', 
        'side.Descripcion_enfermedad_actual', 'side.Suma_combinada', 'side.Total_Deficiencia50', 'side.Porcentaje_pcl', 'side.Rango_pcl', 
        'side.Monto_indemnizacion', 'side.Tipo_evento', 'sltp.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro as Nombre_origen', 'side.F_evento', 
        'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slpa.Nombre_parametro as Nombre_enfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.N_radicado', 
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro','side.N_siniestro')
        ->where([['side.ID_Evento',$ID_Evento_comuni], ['side.Id_Asignacion',$Id_Asignacion_comuni]])->get();        
        $DictamenNo = $array_datos_info_dictamen[0]->Numero_dictamen;
        $N_siniestro = $array_datos_info_dictamen[0]->N_siniestro;
        $motivo_solicitud_dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pericial_eventos as sipe')
        ->leftJoin('sigmel_gestiones.sigmel_lista_motivo_solicitudes as slms', 'slms.Id_Solicitud', '=', 'sipe.Id_motivo_solicitud')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sipe.Regimen_salud')
        ->leftJoin('sigmel_gestiones.sigmel_lista_solicitantes as sls', 'sls.Id_solicitante', '=', 'sipe.Id_solicitante')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sipe.Id_nombre_solicitante')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
        ->select('sipe.Id_motivo_solicitud','slms.Nombre_solicitud', 'sipe.Regimen_salud', 'slp.Nombre_parametro as Regimenes_salud', 
        'sipe.Id_solicitante', 'sls.Solicitante', 'sipe.Id_nombre_solicitante', 'sie.Nombre_entidad', 'sie.Nit_entidad', 'sie.Telefonos', 
        'sie.Emails', 'sie.Direccion', 'sie.Id_Ciudad', 'sldm.Nombre_municipio')
        ->where([['ID_evento',$ID_Evento_comuni]])->limit(1)->get();        
        $Motivo_solicitud = $motivo_solicitud_dictamen[0]->Nombre_solicitud;
        $Id_solicitante_dic = $motivo_solicitud_dictamen[0]->Id_solicitante;

        $array_datos_info_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siae.Tipo_documento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'siae.Nivel_escolar')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpar', 'slpar.Id_Parametro', '=', 'siae.Estado_civil')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'siae.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmu', 'sldmu.Id_municipios', '=', 'siae.Id_municipio_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'siae.Id_eps')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sien', 'sien.Id_Entidad', '=', 'siae.Id_afp')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sient', 'sient.Id_Entidad', '=', 'siae.Id_arl')
        ->select('siae.ID_evento', 'siae.Nombre_afiliado', 'siae.Tipo_documento', 'slp.Nombre_parametro as T_documento', 'siae.Nro_identificacion', 
        'siae.F_nacimiento', 'siae.Edad', 'siae.Genero', 'siae.Email', 'siae.Telefono_contacto', 'siae.Estado_civil', 
        'slpar.Nombre_parametro as Estado_civi', 'siae.Nivel_escolar', 'slpa.Nombre_parametro as Escolaridad', 
        'siae.Apoderado', 'siae.Nombre_apoderado', 'siae.Nro_identificacion_apoderado', 'siae.Id_dominancia', 'siae.Direccion', 
        'siae.Id_departamento', 'siae.Id_municipio', 'sldm.Nombre_municipio as Nombre_municipio', 'siae.Ocupacion', 'siae.Tipo_afiliado', 
        'siae.Ibc', 'siae.Id_eps', 'sie.Nombre_entidad as Entidad_eps', 'siae.Id_afp', 'sien.Nombre_entidad as Entidad_afp', 'siae.Id_arl', 
        'sient.Nombre_entidad as Entidad_arl', 'siae.Activo', 'siae.Medio_notificacion', 'siae.Nombre_afiliado_benefi', 
        'siae.Tipo_documento_benefi', 'siae.Nro_identificacion_benefi', 'siae.Direccion_benefi', 'siae.Id_departamento_benefi', 
        'siae.Id_municipio_benefi', 'sldmu.Nombre_municipio as Nombre_municipio_benefi', 'siae.Nombre_usuario', 'siae.F_registro', 
        'F_actualizacion')
        ->where([['ID_Evento',$ID_Evento_comuni]])->get();        

        $Tipo_afiliado = $array_datos_info_afiliado[0]->Tipo_afiliado;
        $Ocupacion_afiliado = $array_datos_info_afiliado[0]->Ocupacion;

        if ($Tipo_afiliado !== 27 ) {
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado;            
        }else{
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;            
        }

        if($Id_solicitante_dic == 1 || $Id_solicitante_dic == 2 ||  $Id_solicitante_dic == 3){
            $Solicitante_dic = $motivo_solicitud_dictamen[0]->Solicitante;
            $Nombre_entidad_dic = $motivo_solicitud_dictamen[0]->Nombre_entidad;            
        }else{
            $Solicitante_dic = $motivo_solicitud_dictamen[0]->Solicitante;
            $Nombre_entidad_dic = $Nombre_afiliado_dic;            
        }

        //Captura de datos de informacion general de la entidad calificadora

        $array_datos_info_entidad_cali = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
        ->leftJoin('sigmel_gestiones.sigmel_clientes as sc', 'sc.Id_cliente', '=', 'sie.Cliente')
        ->select('sie.ID_evento', 'sie.Cliente', 'sc.Nombre_cliente', 'sc.Nit', 'sc.Telefono_principal', 'sc.Direccion', 'sc.Email_principal')
        ->where([['sie.ID_evento',$ID_Evento_comuni]])->get();                
        
        $Cliente = $array_datos_info_entidad_cali[0]->Cliente;        
        // Logo cliente del Header

        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
        } else {
            $logo_header = "Sin logo";
        }       

        $Nombre_cliente_ent = $array_datos_info_entidad_cali[0]->Nombre_cliente;
        $Nit_ent = $array_datos_info_entidad_cali[0]->Nit;
        $Telefono_principal_ent = $array_datos_info_entidad_cali[0]->Telefono_principal;
        $Direccion_ent = $array_datos_info_entidad_cali[0]->Direccion;
        $Email_principal_ent = $array_datos_info_entidad_cali[0]->Email_principal;        

        //Captura de datos generales de la persona calificada

        if ($Tipo_afiliado == 27) {
            $Afiliado_per_cal = '';
            $Beneficiario_per_cal = 'X';                                  
        }else {
            $Afiliado_per_cal = 'X';
            $Beneficiario_per_cal = '';             
        }        
        $Nombre_per_cal = $array_datos_info_afiliado[0]->Nombre_afiliado;           
        $Tipo_documento_per_cal = $array_datos_info_afiliado[0]->T_documento;            
        $NroIden_per_cal = $array_datos_info_afiliado[0]->Nro_identificacion;
        $F_nacimiento_per_cal = $array_datos_info_afiliado[0]->F_nacimiento;            
        $Edad_per_cal = $array_datos_info_afiliado[0]->Edad;            
        $Nivel_escolar_per_cal = $array_datos_info_afiliado[0]->Escolaridad;
        $Estado_civil_per_cal = $array_datos_info_afiliado[0]->Estado_civi;
        $Telefono_per_cal = $array_datos_info_afiliado[0]->Telefono_contacto;
        $Direccion_per_cal = $array_datos_info_afiliado[0]->Direccion;
        $Ciudad_per_cal = $array_datos_info_afiliado[0]->Nombre_municipio;
        $Email_per_cal = $array_datos_info_afiliado[0]->Email;
        $Numero_documento_afiliado = $NroIden_per_cal;  
        $Documento_afiliado = $Tipo_documento_per_cal;
        $Nombre_afiliado_pre = $Nombre_per_cal;       

        //Captura de datos de Afiliacion al siss:

        $Regimen_salud_ecv = $motivo_solicitud_dictamen[0]->Regimen_salud;
        
        if($Regimen_salud_ecv == 37) {
            $Contributivo_ecv = 'X';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = '';
        }elseif($Regimen_salud_ecv == 38){
            $Contributivo_ecv = '';
            $Subsidiado_ecv = 'X';
            $No_afiliado_ecv = '';
        }elseif($Regimen_salud_ecv == 39){
            $Contributivo_ecv = '';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = 'X';
        }else{
            $Contributivo_ecv = '';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = '';
        }
        
        $Entidad_eps = $array_datos_info_afiliado[0]->Entidad_eps;
        $Entidad_afp = $array_datos_info_afiliado[0]->Entidad_afp;
        $Entidad_arl = $array_datos_info_afiliado[0]->Entidad_arl;

        //Captura de datos Antecedentes laborales del calificado

        $array_datos_info_antecedentes_laborales = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
        ->select('sile.Tipo_empleado', 'sile.Cargo', 'sile.Id_codigo_ciuo', 'slcc.Nombre_ciuo', 'sile.Funciones_cargo', 'sile.Empresa', 
        'sile.Nit_o_cc')->where([['ID_Evento',$ID_Evento_comuni]])->get();

        $Tipo_empleado_laboral = $array_datos_info_antecedentes_laborales[0]->Tipo_empleado;

        if ($Tipo_empleado_laboral == 'Empleado actual') {
            $Independiente_laboral = '';
            $Dedependiente_laboral = 'X';
        } else {
            $Independiente_laboral = 'X';
            $Dedependiente_laboral = '';
        }

        $Nombre_cargo_laboral = $array_datos_info_antecedentes_laborales[0]->Cargo;
        $Codigo_ciuo_laboral = $array_datos_info_antecedentes_laborales[0]->Nombre_ciuo;
        $Empresa_laboral = $array_datos_info_antecedentes_laborales[0]->Empresa;
        $Nit_laboral = $array_datos_info_antecedentes_laborales[0]->Nit_o_cc;    
        
        //Captura de datos Realacion de documentos/examenes fisico(Descripción)

        $array_datos_relacion_examentes = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado_Recalificacion', 'Activo']])->get();  

        //Captura de datos Fundamentos para la calificacion de la perdida de la capacidad laboral y ocupacional - libros I, II y III

        $Descripcion_enfermedad_actual = $array_datos_info_dictamen[0]->Descripcion_enfermedad_actual;

        $array_diagnosticos_fc = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro as Nombre_origen', 
        'slp2.Nombre_parametro as Nombre_lateralidad', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado_Recalificacion', 'Activo']])->get();  

        $array_deficiencias_alteraciones = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')        
        ->select('sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Total_deficiencia')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])
        ->orderByRaw("CAST(sidae.Deficiencia AS DECIMAL(10,2)) DESC")
        ->get();  
        
        $Suma_combinada_fc = $array_datos_info_dictamen[0]->Suma_combinada;        

        $Total_deficiencia50_fc = $array_datos_info_dictamen[0]->Total_Deficiencia50;

        $array_datos_libros23 = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();  

        //Captura de datos Concepto final del dictamen pericial
        
        $Porcentaje_Pcl_dp = $array_datos_info_dictamen[0]->Porcentaje_pcl;
        $F_estructuracion_dp = $array_datos_info_dictamen[0]->F_estructuracion;
        $Tipo_evento_dp = $array_datos_info_dictamen[0]->Nombre_evento;
        $Sustentacion_F_estructuracion_dp = $array_datos_info_dictamen[0]->Sustentacion_F_estructuracion;
        $F_evento_dp = $array_datos_info_dictamen[0]->F_evento;
        $Origen_dp = $array_datos_info_dictamen[0]->Nombre_origen;
        $Detalle_calificacion_dp = $array_datos_info_dictamen[0]->Detalle_calificacion;
        $Enfermedad_catastrofica_dp = $array_datos_info_dictamen[0]->Enfermedad_catastrofica;
        $Enfermedad_congenita_dp = $array_datos_info_dictamen[0]->Enfermedad_congenita;
        $validar_servicio_revision_pension = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->select('Id_servicio')->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get();  
        $Revision_pension_dp = $validar_servicio_revision_pension[0]->Id_servicio;
        $Nombre_enfermedad_dp = $array_datos_info_dictamen[0]->Nombre_enfermedad;
        $Requiere_tercera_persona_dp = $array_datos_info_dictamen[0]->Requiere_tercera_persona;
        $Requiere_tercera_persona_decisiones_dp = $array_datos_info_dictamen[0]->Requiere_tercera_persona_decisiones;
        $Requiere_dispositivo_apoyo_dp = $array_datos_info_dictamen[0]->Requiere_dispositivo_apoyo;
        $Justificacion_dependencia_dp = $array_datos_info_dictamen[0]->Justificacion_dependencia;

        //consulta si esta visado o no para mostrar las firmas

        $validacion_visado = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->select('ID_evento', 'Id_proceso', 'Id_Asignacion', 'Visar')
        ->where([['Id_Asignacion',$Id_Asignacion_comuni], ['Visar','Si']])->get();
               
        //Obtener los datos del formulario
        
        $data = [
            'logo_header' => $logo_header,
            'Id_cliente_ent' => $Cliente,
            'codigoQR' => $codigoQR,
            'ID_evento' => $ID_Evento_comuni,
            'Id_Asignacion' => $Id_Asignacion_comuni,
            'Id_proceso' => $Id_Proceso_comuni,
            'Radicado_comuni' => $Radicado_comuni,
            'Fecha_dictamen'=> $Fecha_dictamen,
            'DictamenNo' => $DictamenNo,
            'Motivo_solicitud' => $Motivo_solicitud,
            'Solicitante_dic' => $Solicitante_dic,
            'Nombre_entidad_dic' => $Nombre_entidad_dic,            
            'Nombre_cliente_ent' => $Nombre_cliente_ent,
            'Nit_ent' => $Nit_ent,
            'Telefono_principal_ent' => $Telefono_principal_ent,
            'Direccion_ent' => $Direccion_ent,
            'Email_principal_ent' => $Email_principal_ent,
            'Afiliado_per_cal' => $Afiliado_per_cal,
            'Beneficiario_per_cal' => $Beneficiario_per_cal,
            'ResultadoNombre_per_cal' => $Nombre_per_cal,
            'Tipo_documento_per_cal' => $Tipo_documento_per_cal,
            'NroIden_per_cal' => $NroIden_per_cal,
            'F_nacimiento_per_cal' => $F_nacimiento_per_cal,
            'Edad_per_cal' => $Edad_per_cal,
            'Nivel_escolar_per_cal' => $Nivel_escolar_per_cal,
            'Estado_civil_per_cal' => $Estado_civil_per_cal,
            'Telefono_per_cal' => $Telefono_per_cal,
            'Direccion_per_cal' => $Direccion_per_cal,
            'Ciudad_per_cal' => $Ciudad_per_cal,
            'Email_per_cal' => $Email_per_cal,                                   
            'Contributivo_ecv' => $Contributivo_ecv,
            'Subsidiado_ecv' => $Subsidiado_ecv,
            'No_afiliado_ecv' => $No_afiliado_ecv,
            'Entidad_eps' => $Entidad_eps,
            'Entidad_afp' => $Entidad_afp,
            'Entidad_arl' => $Entidad_arl,
            'Independiente_laboral' => $Independiente_laboral,
            'Dedependiente_laboral' => $Dedependiente_laboral,
            'Nombre_cargo_laboral' => $Nombre_cargo_laboral,
            'Ocupacion_afiliado' => $Ocupacion_afiliado,
            'Codigo_ciuo_laboral' => $Codigo_ciuo_laboral,
            'Empresa_laboral' => $Empresa_laboral,
            'Nit_laboral' => $Nit_laboral,
            'array_datos_relacion_examentes' => $array_datos_relacion_examentes,
            'Descripcion_enfermedad_actual' => $Descripcion_enfermedad_actual,
            'array_diagnosticos_fc' => $array_diagnosticos_fc,
            'array_deficiencias_alteraciones' => $array_deficiencias_alteraciones,
            'Suma_combinada_fc' => $Suma_combinada_fc,
            'Total_deficiencia50_fc' => $Total_deficiencia50_fc,
            'array_datos_libros23' => $array_datos_libros23,
            'Porcentaje_Pcl_dp' => $Porcentaje_Pcl_dp,
            'F_estructuracion_dp' => $F_estructuracion_dp,
            'Tipo_evento_dp' => $Tipo_evento_dp,
            'Sustentacion_F_estructuracion_dp' => $Sustentacion_F_estructuracion_dp,
            'F_evento_dp' => $F_evento_dp,
            'Origen_dp' => $Origen_dp,
            'Detalle_calificacion_dp' => $Detalle_calificacion_dp,
            'Enfermedad_catastrofica_dp' => $Enfermedad_catastrofica_dp,
            'Enfermedad_congenita_dp' => $Enfermedad_congenita_dp,
            'Revision_pension_dp' => $Revision_pension_dp,
            'Nombre_enfermedad_dp' => $Nombre_enfermedad_dp,
            'Requiere_tercera_persona_dp' => $Requiere_tercera_persona_dp,
            'Requiere_tercera_persona_decisiones_dp' => $Requiere_tercera_persona_decisiones_dp,
            'Requiere_dispositivo_apoyo_dp' => $Requiere_dispositivo_apoyo_dp,
            'Justificacion_dependencia_dp' => $Justificacion_dependencia_dp,
            'Numero_documento_afiliado' => $Numero_documento_afiliado,
            'Documento_afiliado' => $Documento_afiliado,
            'Nombre_afiliado_pre' => $Nombre_afiliado_pre,
            'validacion_visado' => $validacion_visado,
            'N_siniestro' => $N_siniestro
        ];

        // Crear una instancia de Dompdf

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/PCL/dictamen_Pcl917prev', $data);        
        $nombre_pdf = 'PCL_DML_'.$Id_Asignacion_comuni.'_'.$Numero_documento_afiliado.'.pdf';    
        //Obtener el contenido del PDF
        $output = $pdf->output();
        //Guardar el PDF en un archivo
        file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni}/{$nombre_pdf}"), $output);
        $actualizar_nombre_documento = [
            'Nombre_documento' => $nombre_pdf
        ];
        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
        ->update($actualizar_nombre_documento);
        /* Inserción del registro de que fue descargado */
        // Extraemos el id del servicio asociado
        $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->select('siae.Id_servicio')
        ->where([
            ['siae.Id_Asignacion', $Id_Asignacion_comuni],
            ['siae.ID_evento', $ID_Evento_comuni],
            ['siae.Id_proceso', $Id_Proceso_comuni],
        ])->get();

        $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        ->select('sice.F_comunicado')
        ->where([
            ['sice.N_radicado', $Radicado_comuni]
        ])
        ->get();

        $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

        // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
        $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        ->select('Nombre_documento')
        ->where([
            ['Nombre_documento', $nombre_pdf],
        ])->get();
        
        if(count($verficar_documento) == 0){
            $info_descarga_documento = [
                'Id_Asignacion' => $Id_Asignacion_comuni,
                'Id_proceso' => $Id_Proceso_comuni,
                'Id_servicio' => $Id_servicio,
                'ID_evento' => $ID_Evento_comuni,
                'Nombre_documento' => $nombre_pdf,
                'N_radicado_documento' => $Radicado_comuni,
                'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                'F_descarga_documento' => $date,
                'Nombre_usuario' => $nombre_usuario,
            ];
            
            sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        }

        return $pdf->download($nombre_pdf);
    }
    // Generar PDF de Notificacion numerica para el decreto 1507, 1507 cero y 917

    public function generarOficio_PclRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        $ID_Evento_comuni_comite = $request->ID_Evento_comuni_comite;
        $Id_Asignacion_comuni_comite = $request->Id_Asignacion_comuni_comite;
        $Id_Proceso_comuni_comite = $request->Id_Proceso_comuni_comite;
        $Radicado_comuni_comite = $request->Radicado_comuni_comite;
        $Firma_comuni_comite = $request->Firma_comuni_comite;
        $Id_Comunicado = $request->Id_Comunicado;
        // $N_siniestro = $request->N_siniestro;

        $formattedData = "";

        $dictamenPclQr = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_decreto_eventos as side', 'side.Id_Asignacion', '=', 'siae.Id_Asignacion')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_afiliado_eventos as siaf', 'siaf.ID_evento', '=', 'siae.ID_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siaf.Tipo_documento')
        ->select('siaf.Nombre_afiliado', 'slp.Nombre_parametro', 'siaf.Nro_identificacion', 'siae.Consecutivo_dictamen', 
        'side.Porcentaje_pcl', 'side.F_estructuracion', 'siae.ID_evento')
        ->where('siae.Id_Asignacion', $Id_Asignacion_comuni_comite)->get();     

        if (!$dictamenPclQr->isEmpty()) {
            // Crear una cadena para almacenar los datos en el formato deseado                    
        
            foreach ($dictamenPclQr as $evento) {
                // Construir la cadena de texto con el formato deseado
                $formattedData .= $evento->Nombre_afiliado."\n";
                $formattedData .= $evento->Nombre_parametro." ".$evento->Nro_identificacion . "\n";
                $formattedData .= "N° Dictámen: ".$evento->Consecutivo_dictamen."\n";
                $formattedData .= "CALIFICACIÓN: ".$evento->Porcentaje_pcl."\n";
                $formattedData .= "Fecha estructuración: ".$evento->F_estructuracion."\n";
                $formattedData .= "Cod. Verificación: ".$evento->ID_evento."\n";
        
                // Agregar un salto de línea después de cada conjunto de atributos de evento
                $formattedData .= "\n";
            }
                            
        }

        // Codigo QR
        $datos = $formattedData;
        $codigoQR = QrCode::size(110)->margin(0.5)->generate($datos);     

        // Captura de datos para logo del cliente y informacion de las entidades

        $array_datos_info_entidad_cali = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
        ->leftJoin('sigmel_gestiones.sigmel_clientes as sc', 'sc.Id_cliente', '=', 'sie.Cliente')
        ->select('sie.ID_evento', 'sie.Cliente', 'sc.Nombre_cliente', 'sc.Nit', 'sc.Telefono_principal', 'sc.Direccion', 'sc.Email_principal')
        ->where([['sie.ID_evento',$ID_Evento_comuni_comite]])->get();                
        
        $Cliente = $array_datos_info_entidad_cali[0]->Cliente;        
        // Logo cliente del Header
        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
        } else {
            $logo_header = "Sin logo";
        } 

        //Footer image
        $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
        ->select('Footer_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->limit(1)->get();

        if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
            $footer = $footer_imagen[0]->Footer_cliente;
        } else {
            $footer = null;
        } 

        // Captura de datos de Comite interdiciplinario y correspondencia

        $array_datos_comite_inter = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni_comite], ['Id_Asignacion',$Id_Asignacion_comuni_comite]])->get(); 

        $Asunto_correspondencia = $array_datos_comite_inter[0]->Asunto;
        $Cuerpo_comunicado_correspondencia = $array_datos_comite_inter[0]->Cuerpo_comunicado;
        $Ciudad_correspondencia = $array_datos_comite_inter[0]->Ciudad;
        $F_correspondecia = $array_datos_comite_inter[0]->F_correspondecia;        
        $Anexos_correspondecia = $array_datos_comite_inter[0]->Anexos;
        $Elaboro_correspondecia = $array_datos_comite_inter[0]->Elaboro;
        $Copia_empleador_correspondecia = $array_datos_comite_inter[0]->Copia_empleador;
        $Copia_eps_correspondecia = $array_datos_comite_inter[0]->Copia_eps;
        $Copia_afp_correspondecia = $array_datos_comite_inter[0]->Copia_afp;
        $Copia_afp_conocimiento_correspondencia = $array_datos_comite_inter[0]->Copia_afp_conocimiento;
        $Copia_arl_correspondecia = $array_datos_comite_inter[0]->Copia_arl;
        $Oficio_pcl = $array_datos_comite_inter[0]->Oficio_pcl;
        $Oficio_incapacidad = $array_datos_comite_inter[0]->Oficio_incapacidad;
        $Formatob = $array_datos_comite_inter[0]->Formatob;
        $Formatoc = $array_datos_comite_inter[0]->Formatoc;
        $Formatod = $array_datos_comite_inter[0]->Formatod;
        $Formatoe = $array_datos_comite_inter[0]->Formatoe;

        //Captura de datos del afiliado 

        $array_datos_info_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siae.Tipo_documento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'siae.Nivel_escolar')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpar', 'slpar.Id_Parametro', '=', 'siae.Estado_civil')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as slde', 'slde.Id_departamento', '=', 'siae.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'siae.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpara', 'slpara.Id_Parametro', '=', 'siae.Tipo_documento_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldep', 'sldep.Id_departamento', '=', 'siae.Id_departamento_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmu', 'sldmu.Id_municipios', '=', 'siae.Id_municipio_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'siae.Id_eps')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldepa', 'sldepa.Id_departamento', '=', 'sie.Id_Departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmun', 'sldmun.Id_municipios', '=', 'sie.Id_Ciudad')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sien', 'sien.Id_Entidad', '=', 'siae.Id_afp')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldepar', 'sldepar.Id_departamento', '=', 'sien.Id_Departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmuni', 'sldmuni.Id_municipios', '=', 'sien.Id_Ciudad')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sient', 'sient.Id_Entidad', '=', 'siae.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldepart', 'sldepart.Id_departamento', '=', 'sient.Id_Departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmunic', 'sldmunic.Id_municipios', '=', 'sient.Id_Ciudad')
        ->select('siae.ID_evento', 'siae.Nombre_afiliado', 'siae.Tipo_documento', 'slp.Nombre_parametro as T_documento', 
        'siae.Nro_identificacion', 'siae.F_nacimiento', 'siae.Edad', 'siae.Genero', 'siae.Email', 'siae.Telefono_contacto', 
        'siae.Estado_civil', 'slpar.Nombre_parametro as Estado_civi', 'siae.Nivel_escolar', 'slpa.Nombre_parametro as Escolaridad', 
        'siae.Apoderado', 'siae.Nombre_apoderado', 'siae.Nro_identificacion_apoderado', 'siae.Id_dominancia', 'siae.Direccion', 
        'siae.Id_departamento', 'slde.Nombre_departamento as Nombre_departamento', 'siae.Id_municipio', 'sldm.Nombre_municipio as Nombre_municipio', 
        'siae.Ocupacion', 'siae.Tipo_afiliado', 'siae.Ibc', 'siae.Id_eps', 'sie.Nombre_entidad as Entidad_eps','sie.Emails as Email_eps', 'sie.Direccion as Direccion_eps', 
        'sie.Telefonos as Telefono_eps', 'sie.Id_Departamento', 'sldepa.Nombre_departamento as Nombre_departamento_eps', 'sie.Id_Ciudad', 
        'sldmun.Nombre_municipio as Nombre_municipio_eps', 'siae.Id_afp', 'sien.Nombre_entidad as Entidad_afp', 'sien.Emails as Email_afp', 
        'sien.Direccion as Direccion_afp', 'sien.Telefonos as Telefono_afp', 'sien.Id_Departamento', 
        'sldepar.Nombre_departamento as Nombre_departamento_afp', 'sien.Id_Ciudad', 
        'sldmuni.Nombre_municipio as Nombre_municipio_afp', 'siae.Id_arl', 'sient.Nombre_entidad as Entidad_arl', 'sient.Emails as Email_arl', 
        'sient.Direccion as Direccion_arl', 'sient.Telefonos as Telefono_arl', 'sient.Id_Departamento', 
        'sldepart.Nombre_departamento as Nombre_departamento_arl', 'sient.Id_Ciudad',
        'sldmunic.Nombre_municipio as Nombre_municipio_arl',
        'siae.Activo',
        'siae.Medio_notificacion', 'siae.Nombre_afiliado_benefi', 'siae.Tipo_documento_benefi', 'slpara.Nombre_parametro as Tipo_documento_benfi',         
        'siae.Nro_identificacion_benefi', 'siae.Direccion_benefi', 'siae.Id_departamento_benefi', 
        'sldep.Nombre_departamento as Nombre_departamento_benefi', 'siae.Id_municipio_benefi', 
        'sldmu.Nombre_municipio as Nombre_municipio_benefi', 'siae.Nombre_usuario', 'siae.F_registro', 'F_actualizacion')
        ->where([['ID_Evento',$ID_Evento_comuni_comite]])->limit(1)->get(); 

        $Tipo_afiliado = $array_datos_info_afiliado[0]->Tipo_afiliado;

        // if ($Tipo_afiliado !== 27 ) {
        $Nombre_afiliado_pie = $array_datos_info_afiliado[0]->Nombre_afiliado;
        $Edad_afiliado = $array_datos_info_afiliado[0]->Edad;
        $Nombre_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_afiliado;
        $Direccion_afiliado_noti = $array_datos_info_afiliado[0]->Direccion;
        $Telefono_afiliado_noti = $array_datos_info_afiliado[0]->Telefono_contacto;
        $Departamento_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_departamento;            
        $Ciudad_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_municipio;
        $T_documento_noti = $array_datos_info_afiliado[0]->T_documento;            
        $NroIden_afiliado_noti = $array_datos_info_afiliado[0]->Nro_identificacion;
        $Email_afiliado_noti = $array_datos_info_afiliado[0]->Email;
        // }else{
        //     $Nombre_afiliado_pie = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
        //     $Nombre_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
        //     $Direccion_afiliado_noti = $array_datos_info_afiliado[0]->Direccion_benefi;
        //     $Telefono_afiliado_noti = '';
        //     $Departamento_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_departamento_benefi;            
        //     $Ciudad_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
        //     $T_documento_noti = $array_datos_info_afiliado[0]->Tipo_documento_benfi;            
        //     $NroIden_afiliado_noti = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
        //     $Email_afiliado_noti = '';
        // }

        if(!empty($Copia_eps_correspondecia) && $Copia_eps_correspondecia == 'EPS'){
            $Nombre_eps = $array_datos_info_afiliado[0]->Entidad_eps;
            $Direccion_eps = $array_datos_info_afiliado[0]->Direccion_eps;
            $Telefono_eps = $array_datos_info_afiliado[0]->Telefono_eps;        
            $Email_eps = $array_datos_info_afiliado[0]->Email_eps;    
            $Ciudad_departamento_eps = $array_datos_info_afiliado[0]->Nombre_municipio_eps.'-'.$array_datos_info_afiliado[0]->Nombre_departamento_eps;            
        }else{
            $Nombre_eps = '';
            $Direccion_eps = '';
            $Telefono_eps = '';
            $Email_eps = '';
            $Ciudad_departamento_eps = '';
        }
        
        if(!empty($Copia_afp_correspondecia) && $Copia_afp_correspondecia == 'AFP'){
            $Nombre_afp = $array_datos_info_afiliado[0]->Entidad_afp;
            $Direccion_afp = $array_datos_info_afiliado[0]->Direccion_afp;
            $Telefono_afp = $array_datos_info_afiliado[0]->Telefono_afp;
            $Email_afp = $array_datos_info_afiliado[0]->Email_afp;
            $Ciudad_departamento_afp = $array_datos_info_afiliado[0]->Nombre_municipio_afp.'-'.$array_datos_info_afiliado[0]->Nombre_departamento_afp;
        }else{
            $Nombre_afp = '';
            $Direccion_afp = '';
            $Telefono_afp = '';
            $Email_afp = '';
            $Ciudad_departamento_afp = '';
        }

        if (!empty($Copia_afp_conocimiento_correspondencia) && $Copia_afp_conocimiento_correspondencia == "AFP_Conocimiento") {
            $dato_id_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
            ->select('siae.Entidad_conocimiento','siae.Id_afp_entidad_conocimiento')
            ->where([['siae.ID_evento', $ID_Evento_comuni_comite]])
            ->get();

            $si_entidad_conocimiento = $dato_id_afp_conocimiento[0]->Entidad_conocimiento;
            $id_afp_conocimiento = $dato_id_afp_conocimiento[0]->Id_afp_entidad_conocimiento;

            if ($si_entidad_conocimiento == "Si") {
                $datos_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos','sie.Emails as Email', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                ->where([['sie.Id_Entidad', $id_afp_conocimiento]])
                ->get();
    
                $Nombre_afp_conocimiento = $datos_afp_conocimiento[0]->Nombre_entidad;
                $Direccion_afp_conocimiento = $datos_afp_conocimiento[0]->Direccion;
                $Telefonos_afp_conocimiento = $datos_afp_conocimiento[0]->Telefonos;
                $Email_afp_conocimiento = $datos_afp_conocimiento[0]->Email;
                $Ciudad_departamento_afp_conocimiento = $datos_afp_conocimiento[0]->Nombre_ciudad.'-'.$datos_afp_conocimiento[0]->Nombre_departamento;
            } else {
                $Copia_afp_conocimiento_correspondencia = '';

                $Nombre_afp_conocimiento = '';
                $Direccion_afp_conocimiento = '';
                $Telefonos_afp_conocimiento = '';
                $Email_afp_conocimiento = '';
                $Ciudad_departamento_afp_conocimiento = '';
            }

        } else {
            $Nombre_afp_conocimiento = '';
            $Direccion_afp_conocimiento = '';
            $Telefonos_afp_conocimiento = '';
            $Email_afp_conocimiento = '';
            $Ciudad_departamento_afp_conocimiento = '';
        }

        if(!empty($Copia_arl_correspondecia) && $Copia_arl_correspondecia == 'ARL'){
            $Nombre_arl = $array_datos_info_afiliado[0]->Entidad_arl;
            $Direccion_arl = $array_datos_info_afiliado[0]->Direccion_arl;
            $Telefono_arl = $array_datos_info_afiliado[0]->Telefono_arl;
            $Email_arl = $array_datos_info_afiliado[0]->Email_arl;
            $Ciudad_departamento_arl = $array_datos_info_afiliado[0]->Nombre_municipio_arl.'-'.$array_datos_info_afiliado[0]->Nombre_departamento_arl;
        }else{
            $Nombre_arl = '';   
            $Direccion_arl = '';
            $Telefono_arl = '';
            $Email_arl = '';
            $Ciudad_departamento_arl = '';
        }
        
        // Captura de datos del dictamen pericial
        $array_datos_info_dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as sltp', 'sltp.Id_Evento', '=', 'side.Tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'side.Tipo_enfermedad')
        ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'side.Cobertura', 'side.Decreto_calificacion', 'slcd.Nombre_decreto', 
        'side.Numero_dictamen', 'side.PCL_anterior', 'side.Descripcion_nueva_calificacion', 'side.Relacion_documentos', 'side.Otros_relacion_doc', 
        'side.Descripcion_enfermedad_actual', 'side.Suma_combinada', 'side.Total_Deficiencia50', 'side.Porcentaje_pcl', 'side.Rango_pcl', 
        'side.Monto_indemnizacion', 'side.Tipo_evento', 'sltp.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro as Nombre_origen', 'side.F_evento', 
        'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slpa.Nombre_parametro as Nombre_enfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.N_radicado', 
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro','side.N_siniestro')
        ->where([['side.ID_Evento',$ID_Evento_comuni_comite], ['side.Id_Asignacion',$Id_Asignacion_comuni_comite]])->get(); 
        $N_siniestro = $array_datos_info_dictamen[0]->N_siniestro;
        $PorcentajePcl_dp = $array_datos_info_dictamen[0]->Porcentaje_pcl;
        $F_estructuracionPcl_dp = $array_datos_info_dictamen[0]->F_estructuracion;
        $OrigenPcl_dp = $array_datos_info_dictamen[0]->Nombre_origen;  
        $Detalle_calificacion_Fbdp = $array_datos_info_dictamen[0]->Detalle_calificacion;   
        $Nombre_decreto_dp = $array_datos_info_dictamen[0]->Nombre_decreto;
        $Suma_combinada_dp = $array_datos_info_dictamen[0]->Suma_combinada;
        $Total_Deficiencia50_dp = $array_datos_info_dictamen[0]->Total_Deficiencia50;


        // Captura de los nombres CIE10

        $array_diagnosticosPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10')
        ->where([['ID_Evento',$ID_Evento_comuni_comite], ['Id_Asignacion',$Id_Asignacion_comuni_comite], ['Id_proceso',$Id_Proceso_comuni_comite], ['side.Estado', 'Activo']])->get(); 
        
        if(count($array_diagnosticosPcl) > 0){
            // Obtener el array de nombres CIE10
            $NombresCIE10 = $array_diagnosticosPcl->pluck('Nombre_CIE10')->toArray();            
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
        
        // validamos la firma esta marcado para la Captura de la firma del cliente           
        if ($Firma_comuni_comite == 'Firma') {            
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

        // Captura de datos de informacion laboral

        $array_datos_info_laboral = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as slde', 'slde.Id_departamento', '=', 'sile.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sile.Id_municipio')
        ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sile.Id_departamento', 'slde.Nombre_departamento', 
        'sile.Id_municipio', 'sldm.Nombre_municipio', 'sile.Email')->where([['ID_Evento',$ID_Evento_comuni_comite]])->limit(1)->get();

        $Nombre_empresa_noti = $array_datos_info_laboral[0]->Empresa;
        $Direccion_empresa_noti = $array_datos_info_laboral[0]->Direccion;
        $Telefono_empresa_noti = $array_datos_info_laboral[0]->Telefono_empresa;
        $Email_empresa_noti = $array_datos_info_laboral[0]->Email;
        $Ciudad_departamento_empresa_noti = $array_datos_info_laboral[0]->Nombre_municipio.'-'.$array_datos_info_laboral[0]->Nombre_departamento;        

        if(!empty($Copia_empleador_correspondecia) && $Copia_empleador_correspondecia == 'Empleador'){
            $copiaNombre_empresa_noti = $Nombre_empresa_noti;
            $copiaDireccion_empresa_noti = $Direccion_empresa_noti;
            $copiaTelefono_empresa_noti = $Telefono_empresa_noti;
            $copiaEmail_empresa_noti = $Email_empresa_noti;
            $copiaCiudad_departamento_empresa_noti = $Ciudad_departamento_empresa_noti;
        }else{
            $copiaNombre_empresa_noti = '';
            $copiaDireccion_empresa_noti = '';
            $copiaTelefono_empresa_noti = '';
            $copiaEmail_empresa_noti = '';
            $copiaCiudad_departamento_empresa_noti = '';
        }

        // Validación información Destinatario Principal
        $checkbox_otro_destinatario = $array_datos_comite_inter[0]->Otro_destinatario;

        //  Si el checkbox fue marcado entonces se entra a mirar las demás validaciones
        if ($checkbox_otro_destinatario == "Si") {
            // 1: ARL; 2: AFP; 3: EPS; 4: AFILIADO; 5:EMPLEADOR; 8: OTRO
            
            $tipo_destinatario = $array_datos_comite_inter[0]->Tipo_destinatario;
            switch (true) {
                // Si escoge alguna opcion de estas: ARL, AFP, EPS se sacan los datos del destinatario principal de la entidad
                case ($tipo_destinatario == 1 || $tipo_destinatario == 2 || $tipo_destinatario == 3):
                    $id_entidad = $array_datos_comite_inter[0]->Nombre_dest_principal;

                    $datos_entidad = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as slde', 'slde.Id_departamento', '=', 'sie.Id_Departamento')
                    ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento as Nombre_departamento',
                    'sldm.Nombre_municipio as Nombre_municipio')
                    ->where([
                        ['sie.Id_Entidad', $id_entidad],
                        ['sie.IdTipo_entidad', $tipo_destinatario]
                    ])->get();                   

                    $nombre_destinatario_principal = $datos_entidad[0]->Nombre_entidad;
                    $direccion_destinatario_principal = $datos_entidad[0]->Direccion;
                    $telefono_destinatario_principal = $datos_entidad[0]->Telefonos;
                    $ciudad_destinatario_principal = $datos_entidad[0]->Nombre_municipio.'-'.$datos_entidad[0]->Nombre_departamento;
                break;
                
                // Si escoge la opción Afiliado: Se sacan los datos del destinatario principal pero del afiliado
                case ($tipo_destinatario == 4):                            
                    $nombre_destinatario_principal = $Nombre_afiliado_noti;
                    $direccion_destinatario_principal = $Direccion_afiliado_noti;
                    $telefono_destinatario_principal = $Telefono_afiliado_noti;
                    $ciudad_destinatario_principal = $Ciudad_afiliado_noti.'-'.$Departamento_afiliado_noti;
                break;

                // Si escoge la opción Empleador: Se sacan los datos del destinatario principal pero del Empleador
                case ($tipo_destinatario == 5):                   

                    $nombre_destinatario_principal = $Nombre_empresa_noti;
                    $direccion_destinatario_principal = $Direccion_empresa_noti;
                    $telefono_destinatario_principal = $Telefono_empresa_noti;
                    $ciudad_destinatario_principal = $Ciudad_departamento_empresa_noti;
                break;
                
                // Si escoge la opción Otro: se sacan los datos del destinatario de la tabla sigmel_informacion_comite_interdisciplinario_eventos
                case ($tipo_destinatario == 8):
                    // aqui validamos si los datos no vienen vacios, debido a que si  vienen vacios, toca marcar ''
                    if (!empty($array_datos_comite_inter[0]->Nombre_destinatario)) {
                        $nombre_destinatario_principal = $array_datos_comite_inter[0]->Nombre_destinatario;
                    } else {
                        $nombre_destinatario_principal = "";
                    };

                    if (!empty($array_datos_comite_inter[0]["Direccion_destinatario"])) {
                        $direccion_destinatario_principal = $array_datos_comite_inter[0]["Direccion_destinatario"];
                    } else {
                        $direccion_destinatario_principal = "";
                    };

                    if (!empty($array_datos_comite_inter[0]->Telefono_destinatario)) {
                        $telefono_destinatario_principal = $array_datos_comite_inter[0]->Telefono_destinatario;
                    } else {
                        $telefono_destinatario_principal = "";
                    };

                    if (!empty($array_datos_comite_inter[0]->Ciudad_destinatario)) {
                        $ciud_destinatario_principal = $array_datos_comite_inter[0]->Ciudad_destinatario;
                    } else {
                        $ciud_destinatario_principal = "";
                    };

                    if (!empty($array_datos_comite_inter[0]->Departamento_destinatario)) {
                        $depart_destinatario_principal = $array_datos_comite_inter[0]->Departamento_destinatario;
                    } else {
                        $depart_destinatario_principal = "";
                    };

                    $ciudad_destinatario_principal = $ciud_destinatario_principal.'-'.$depart_destinatario_principal;
                break;

                default:
                    # code...
                break;
            }
        }// En caso de que no: la info del destinatario principal se saca del afiliado
        else {            
            $nombre_destinatario_principal = $Nombre_afiliado_noti;
            $direccion_destinatario_principal = $Direccion_afiliado_noti;
            $telefono_destinatario_principal = $Telefono_afiliado_noti;
            $ciudad_destinatario_principal = $Ciudad_afiliado_noti.'-'.$Departamento_afiliado_noti;
        }

        /* Extraemos los datos del footer */
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
        // captura de datos de los tabla de deficiencicias alteraciones por factor
        $deficiencias_calculadas_factores = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
        ->select('sidae.Id_tabla', 'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.Clase_Final', 'sidae.Deficiencia')
        ->where([['sidae.ID_evento',$ID_Evento_comuni_comite],['sidae.Id_Asignacion',$Id_Asignacion_comuni_comite]])->get();
        // captura de datos de la tabla rol ocupacional para el total del rol ocupacionl
        $infototal_rol_ocupacional50 = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        ->select('Poblacion_calificar', 'Total_criterios_desarrollo', 'Total_rol_estudio_clase', 'Total_rol_adultos_ayores')
        ->where([['ID_evento',$ID_Evento_comuni_comite],['Id_Asignacion',$Id_Asignacion_comuni_comite]])->get();
        if (count($infototal_rol_ocupacional50) > 0) {
            $Poblacion_calificar = $infototal_rol_ocupacional50[0]->Poblacion_calificar;
            // validamos cual tabla fue selecionada 75(tabla 12), 76(tabla 13), 77(tabla 14) para capturar su total
            if ($Poblacion_calificar == 75) {
                $total_rol_ocupacional50 = $infototal_rol_ocupacional50[0]->Total_criterios_desarrollo;
            } elseif($Poblacion_calificar == 76){
                $total_rol_ocupacional50 = $infototal_rol_ocupacional50[0]->Total_rol_estudio_clase;                
            } elseif($Poblacion_calificar == 77){
                $total_rol_ocupacional50 = $infototal_rol_ocupacional50[0]->Total_rol_adultos_ayores;                
            }            
        } else {
            $total_rol_ocupacional50 = '';
        }  
        // captura de datos de la rol laboralmente activos para la totalidad del rol laboral y otras areas y eda cronologica
        $info_laboralmenteactivo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->select('Edad_cronologica_menor', 'Edad_cronologica', 'Total_rol_laboral', 'Total_otras_areas', 'Total_laboral_otras_areas')
        ->where([['ID_evento',$ID_Evento_comuni_comite],['Id_Asignacion',$Id_Asignacion_comuni_comite]])->get();
        
        if (count($info_laboralmenteactivo) > 0) {
            if (!empty($info_laboralmenteactivo[0]->Edad_cronologica_menor)) {
                $total_edad_cronologica = $info_laboralmenteactivo[0]->Edad_cronologica_menor;
            } elseif(!empty($info_laboralmenteactivo[0]->Edad_cronologica)) {
                $total_edad_cronologica = $info_laboralmenteactivo[0]->Edad_cronologica;                
            }else {
                $total_edad_cronologica = '';                
            }
            if (!empty($info_laboralmenteactivo[0]->Total_rol_laboral)) {
                $Total_rol_laboral = $info_laboralmenteactivo[0]->Total_rol_laboral;
            }else {
                $Total_rol_laboral = '0';                
            }
            if (!empty($info_laboralmenteactivo[0]->Total_otras_areas)) {
                $Total_otras_areas = $info_laboralmenteactivo[0]->Total_otras_areas;
            }else {
                $Total_otras_areas = '0';                
            }
            if (!empty($info_laboralmenteactivo[0]->Total_laboral_otras_areas)) {
                $Total_laboral_otras_areas = $info_laboralmenteactivo[0]->Total_laboral_otras_areas;
            }else {
                $Total_laboral_otras_areas = '0';                
            }
        } else {
            $total_edad_cronologica = '';
            $Total_rol_laboral = '0';                
            $Total_otras_areas = '0';
            $Total_laboral_otras_areas = '0';                
        }
        
        
        //Obtener los datos del formulario IF para el Oficio PCL y else para Oficio Incapacidad

        if ($Oficio_pcl ==  'Si') {            
            $data = [
                'codigoQR' => $codigoQR,
                'logo_header' => $logo_header,
                'Id_cliente_ent' => $Cliente,
                'ID_evento' => $ID_Evento_comuni_comite,
                'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                'Id_proceso' => $Id_Proceso_comuni_comite,
                'Radicado_comuni' => $Radicado_comuni_comite,
                'Asunto_correspondencia' => $Asunto_correspondencia,
                'Cuerpo_comunicado_correspondencia' => $Cuerpo_comunicado_correspondencia,
                'F_correspondecia' => $F_correspondecia,
                'Ciudad_correspondencia' => $Ciudad_correspondencia,
                'Nombre_afiliado_pie' => $Nombre_afiliado_pie,
                'Nombre_afiliado' => $nombre_destinatario_principal,
                'direccion_destinatario_principal' => $direccion_destinatario_principal,
                'telefono_destinatario_principal' => $telefono_destinatario_principal,
                'ciudad_destinatario_principal' => $ciudad_destinatario_principal,
                'T_documento_noti' => $T_documento_noti,
                'NroIden_afiliado_noti' => $NroIden_afiliado_noti,
                'Email_afiliado_noti' => $Email_afiliado_noti, 
                'PorcentajePcl_dp' => $PorcentajePcl_dp,
                'F_estructuracionPcl_dp' => $F_estructuracionPcl_dp,
                'OrigenPcl_dp' => $OrigenPcl_dp,
                'CIE10Nombres' => $CIE10Nombres,
                'Firma_cliente' => $Firma_cliente,
                'Anexos_correspondecia' => $Anexos_correspondecia,
                'Elaboro_correspondecia' => $Elaboro_correspondecia,
                'Nombre_empresa_noti' => $Nombre_empresa_noti,
                'Direccion_empresa_noti' => $Direccion_empresa_noti,
                'Telefono_empresa_noti' => $Telefono_empresa_noti,
                'Ciudad_departamento_empresa_noti' => $Ciudad_departamento_empresa_noti,
                'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
                'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
                'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
                'Copia_afp_conocimiento_correspondencia' => $Copia_afp_conocimiento_correspondencia,
                'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
                'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
                'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
                'copiaEmail_empresa_noti'=> $copiaEmail_empresa_noti,
                'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
                'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
                'Nombre_eps' => $Nombre_eps,
                'Direccion_eps' => $Direccion_eps,
                'Telefono_eps' => $Telefono_eps,
                'Ciudad_departamento_eps' => $Ciudad_departamento_eps,
                'Nombre_afp' => $Nombre_afp,
                'Direccion_afp' => $Direccion_afp,
                'Telefono_afp' => $Telefono_afp,
                'Ciudad_departamento_afp' => $Ciudad_departamento_afp,
                'Nombre_afp_conocimiento' => $Nombre_afp_conocimiento,
                'Direccion_afp_conocimiento' => $Direccion_afp_conocimiento,
                'Telefonos_afp_conocimiento' => $Telefonos_afp_conocimiento,
                'Ciudad_departamento_afp_conocimiento' => $Ciudad_departamento_afp_conocimiento,
                'Nombre_arl' => $Nombre_arl,
                'Direccion_arl' => $Direccion_arl,
                'Telefono_arl' => $Telefono_arl,
                'Email_eps' => $Email_eps,
                'Email_afp' => $Email_afp,
                'Email_afp_conocimiento' => $Email_afp_conocimiento,
                'Email_arl' => $Email_arl,
                'Ciudad_departamento_arl' => $Ciudad_departamento_arl,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_remisorio_pcl', $data);            
            $nombre_pdf = 'PCL_OFICIO_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';    
            //Obtener el contenido del PDF
            $output = $pdf->output();
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni_comite}/{$nombre_pdf}"), $output);
            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_pdf
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
            ->update($actualizar_nombre_documento);
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
                ['siae.ID_evento', $ID_Evento_comuni_comite],
                ['siae.Id_proceso', $Id_Proceso_comuni_comite],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
            $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
            ->select('sice.F_comunicado')
            ->where([
                ['sice.N_radicado', $Radicado_comuni_comite]
            ])
            ->get();

            $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_pdf],
            ])->get();
            
            if(count($verficar_documento) == 0){
                // Se valida si antes de insertar la info del doc de Oficio PCl ya hay un documento de Oficio Pcl Incapacidad
                // Formato B (Por el momento solo se trabaja en el modulo principal de PCL), Formato C, Formato D y Formato E
                $nombre_docu_pcl_inc = "PCL_OFICIO_INC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoB = "PCL_OFICIO_FB_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoC = "PCL_OFICIO_FC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoD = "PCL_OFICIO_FD_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoE = "PCL_OFICIO_FE_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";

                $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->whereIN('Nombre_documento', [$nombre_docu_pcl_inc, $nombre_docu_formatoB, 
                    $nombre_docu_formatoC, $nombre_docu_formatoD, $nombre_docu_formatoE]
                )->get();                
                // Si no existe info del documento de Oficio pcl Incapacidad, Formato B, Formato C, Formato D y Formato E
                // inserta la info del documento de Oficio Pcl, De lo contrario hace una actualización de la info
                if (count($verificar_docu_otro) == 0) {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);                    
                } else {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    ->where([
                        ['Id_Asignacion', $Id_Asignacion_comuni_comite],
                        ['N_radicado_documento', $Radicado_comuni_comite],
                        ['ID_evento', $ID_Evento_comuni_comite]
                    ])
                    ->update($info_descarga_documento);                    
                }
                
            }

            return $pdf->download($nombre_pdf);
        } elseif($Oficio_incapacidad == 'Si') {
            $data = [
                'codigoQR' => $codigoQR,
                'logo_header' => $logo_header,
                'Id_cliente_ent' => $Cliente,
                'ID_evento' => $ID_Evento_comuni_comite,
                'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                'Id_proceso' => $Id_Proceso_comuni_comite,
                'Radicado_comuni' => $Radicado_comuni_comite,
                'Asunto_correspondencia' => $Asunto_correspondencia,
                'Cuerpo_comunicado_correspondencia' => $Cuerpo_comunicado_correspondencia,
                'F_correspondecia' => $F_correspondecia,
                'Ciudad_correspondencia' => $Ciudad_correspondencia,
                'Nombre_afiliado_pie' => $Nombre_afiliado_pie,
                'Nombre_afiliado' => $nombre_destinatario_principal,
                'direccion_destinatario_principal' => $direccion_destinatario_principal,
                'telefono_destinatario_principal' => $telefono_destinatario_principal,
                'ciudad_destinatario_principal' => $ciudad_destinatario_principal,
                'T_documento_noti' => $T_documento_noti,
                'NroIden_afiliado_noti' => $NroIden_afiliado_noti,
                'Email_afiliado_noti' => $Email_afiliado_noti, 
                'PorcentajePcl_dp' => $PorcentajePcl_dp,
                'F_estructuracionPcl_dp' => $F_estructuracionPcl_dp,
                'OrigenPcl_dp' => $OrigenPcl_dp,
                'CIE10Nombres' => $CIE10Nombres,
                'Firma_cliente' => $Firma_cliente,
                'Anexos_correspondecia' => $Anexos_correspondecia,
                'Elaboro_correspondecia' => $Elaboro_correspondecia,
                'Nombre_empresa_noti' => $Nombre_empresa_noti,
                'Direccion_empresa_noti' => $Direccion_empresa_noti,
                'Telefono_empresa_noti' => $Telefono_empresa_noti,
                'Ciudad_departamento_empresa_noti' => $Ciudad_departamento_empresa_noti,
                'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
                'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
                'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
                'Copia_afp_conocimiento_correspondencia' => $Copia_afp_conocimiento_correspondencia,
                'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
                'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
                'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
                'copiaEmail_empresa_noti' => $copiaEmail_empresa_noti,
                'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
                'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
                'Nombre_eps' => $Nombre_eps,
                'Direccion_eps' => $Direccion_eps,
                'Telefono_eps' => $Telefono_eps,
                'Ciudad_departamento_eps' => $Ciudad_departamento_eps,
                'Nombre_afp' => $Nombre_afp,
                'Direccion_afp' => $Direccion_afp,
                'Telefono_afp' => $Telefono_afp,
                'Ciudad_departamento_afp' => $Ciudad_departamento_afp,
                'Nombre_afp_conocimiento' => $Nombre_afp_conocimiento,
                'Direccion_afp_conocimiento' => $Direccion_afp_conocimiento,
                'Telefonos_afp_conocimiento' => $Telefonos_afp_conocimiento,
                'Ciudad_departamento_afp_conocimiento' => $Ciudad_departamento_afp_conocimiento,
                'Nombre_arl' => $Nombre_arl,
                'Direccion_arl' => $Direccion_arl,
                'Telefono_arl' => $Telefono_arl,
                'Ciudad_departamento_arl' => $Ciudad_departamento_arl,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro,
                'Email_eps' => $Email_eps,
                'Email_afp' => $Email_afp,
                'Email_afp_conocimiento' => $Email_afp_conocimiento,
                'Email_arl' => $Email_arl,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_remisorio_pcl_incapacidad', $data);            
            $nombre_pdf = 'PCL_OFICIO_INC_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';    
            //Obtener el contenido del PDF
            $output = $pdf->output();
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni_comite}/{$nombre_pdf}"), $output);
            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_pdf
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
            ->update($actualizar_nombre_documento);
            
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
                ['siae.ID_evento', $ID_Evento_comuni_comite],
                ['siae.Id_proceso', $Id_Proceso_comuni_comite],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
            $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
            ->select('sice.F_comunicado')
            ->where([
                ['sice.N_radicado', $Radicado_comuni_comite]
            ])
            ->get();

            $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_pdf],
            ])->get();
            
            if(count($verficar_documento) == 0){
                // Se valida si antes de insertar la info del doc de Oficio PCl Incapacidad ya hay un documento de Oficio Pcl
                // Formato B (Por el momento solo se trabaja en el modulo principal de PCL), Formato C, Formato D y Formato E
                $nombre_docu_pcl = "PCL_OFICIO_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoB = "PCL_OFICIO_FB_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoC = "PCL_OFICIO_FC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoD = "PCL_OFICIO_FD_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoE = "PCL_OFICIO_FE_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";

                $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->whereIN('Nombre_documento', [$nombre_docu_pcl, $nombre_docu_formatoB, 
                    $nombre_docu_formatoC, $nombre_docu_formatoD, $nombre_docu_formatoE]
                )->get();                
                // Si no existe info del documento de Oficio Pcl, Formato B, Formato C, Formato D y Formato E
                // inserta la info del documento de Oficio Pcl, De lo contrario hace una actualización de la info
                if (count($verificar_docu_otro) == 0) {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);                    
                } else {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    ->where([
                        ['Id_Asignacion', $Id_Asignacion_comuni_comite],
                        ['N_radicado_documento', $Radicado_comuni_comite],
                        ['ID_evento', $ID_Evento_comuni_comite]
                    ])
                    ->update($info_descarga_documento); 
                }
                
            }

            return $pdf->download($nombre_pdf);
        } elseif($Formatob == 'Si') {
            $data = [
                'codigoQR' => $codigoQR,
                'logo_header' => $logo_header,
                'Id_cliente_ent' => $Cliente,
                'ID_evento' => $ID_Evento_comuni_comite,
                'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                'Id_proceso' => $Id_Proceso_comuni_comite,
                'Radicado_comuni' => $Radicado_comuni_comite,
                'Asunto_correspondencia' => $Asunto_correspondencia,
                'Cuerpo_comunicado_correspondencia' => $Cuerpo_comunicado_correspondencia,
                'F_correspondecia' => $F_correspondecia,
                'Ciudad_correspondencia' => $Ciudad_correspondencia,
                'Nombre_afiliado_pie' => $Nombre_afiliado_pie,
                'Nombre_afiliado' => $nombre_destinatario_principal,
                'direccion_destinatario_principal' => $direccion_destinatario_principal,
                'telefono_destinatario_principal' => $telefono_destinatario_principal,
                'ciudad_destinatario_principal' => $ciudad_destinatario_principal,
                'T_documento_noti' => $T_documento_noti,
                'NroIden_afiliado_noti' => $NroIden_afiliado_noti,
                'Email_afiliado_noti' => $Email_afiliado_noti, 
                'PorcentajePcl_dp' => $PorcentajePcl_dp,
                'F_estructuracionPcl_dp' => $F_estructuracionPcl_dp,
                'OrigenPcl_dp' => $OrigenPcl_dp,
                'CIE10Nombres' => $CIE10Nombres,
                'Detalle_calificacion_Fbdp' => $Detalle_calificacion_Fbdp,
                'Firma_cliente' => $Firma_cliente,
                'Anexos_correspondecia' => $Anexos_correspondecia,
                'Elaboro_correspondecia' => $Elaboro_correspondecia,
                'Nombre_empresa_noti' => $Nombre_empresa_noti,
                'Direccion_empresa_noti' => $Direccion_empresa_noti,
                'Telefono_empresa_noti' => $Telefono_empresa_noti,
                'Ciudad_departamento_empresa_noti' => $Ciudad_departamento_empresa_noti,
                'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
                'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
                'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
                'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
                'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
                'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
                'copiaEmail_empresa_noti' => $copiaEmail_empresa_noti,
                'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
                'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
                'Nombre_eps' => $Nombre_eps,
                'Direccion_eps' => $Direccion_eps,
                'Telefono_eps' => $Telefono_eps,
                'Ciudad_departamento_eps' => $Ciudad_departamento_eps,
                'Nombre_afp' => $Nombre_afp,
                'Direccion_afp' => $Direccion_afp,
                'Telefono_afp' => $Telefono_afp,
                'Ciudad_departamento_afp' => $Ciudad_departamento_afp,
                'Nombre_arl' => $Nombre_arl,
                'Direccion_arl' => $Direccion_arl,
                'Telefono_arl' => $Telefono_arl,
                'Ciudad_departamento_arl' => $Ciudad_departamento_arl,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro,
                'Email_eps' => $Email_eps,
                'Email_afp' => $Email_afp,
                'Email_afp_conocimiento' => $Email_afp_conocimiento,
                'Email_arl' => $Email_arl,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_formato_b_revisionPension', $data);            
            $nombre_pdf = 'PCL_OFICIO_FB_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';    
            //Obtener el contenido del PDF
            $output = $pdf->output();   
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni_comite}/{$nombre_pdf}"), $output);
            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_pdf
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
            ->update($actualizar_nombre_documento);
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
                ['siae.ID_evento', $ID_Evento_comuni_comite],
                ['siae.Id_proceso', $Id_Proceso_comuni_comite],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
            $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
            ->select('sice.F_comunicado')
            ->where([
                ['sice.N_radicado', $Radicado_comuni_comite]
            ])
            ->get();

            $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_pdf],
            ])->get();
            
            if(count($verficar_documento) == 0){
                // Se valida si antes de insertar la info del doc de Formato B (Por el momento solo se trabaja en el modulo principal de PCL)
                //  ya hay un documento de Oficio Pcl, Oficio Incapacidad, Formato C, Formato D y Formato E
                $nombre_docu_pcl = "PCL_OFICIO_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_pcl_inc = "PCL_OFICIO_INC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoC = "PCL_OFICIO_FC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoD = "PCL_OFICIO_FD_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoE = "PCL_OFICIO_FE_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";

                $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->whereIN('Nombre_documento', [$nombre_docu_pcl, $nombre_docu_pcl_inc, 
                    $nombre_docu_formatoC, $nombre_docu_formatoD, $nombre_docu_formatoE]
                )->get();                
                // Si no existe info del documento de Oficio Pcl, Oficio Incapacidad, Formato C, Formato D y Formato E
                // inserta la info del documento de Formato B, De lo contrario hace una actualización de la info
                if (count($verificar_docu_otro) == 0) {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                }else{
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    ->where([
                        ['Id_Asignacion', $Id_Asignacion_comuni_comite],
                        ['N_radicado_documento', $Radicado_comuni_comite],
                        ['ID_evento', $ID_Evento_comuni_comite]
                    ])
                    ->update($info_descarga_documento);
                }
            }

            return $pdf->download($nombre_pdf);
        } elseif($Formatoc == 'Si') {
            $data = [
                'codigoQR' => $codigoQR,
                'logo_header' => $logo_header,
                'Id_cliente_ent' => $Cliente,
                'ID_evento' => $ID_Evento_comuni_comite,
                'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                'Id_proceso' => $Id_Proceso_comuni_comite,
                'Radicado_comuni' => $Radicado_comuni_comite,
                'Asunto_correspondencia' => $Asunto_correspondencia,
                'Cuerpo_comunicado_correspondencia' => $Cuerpo_comunicado_correspondencia,
                'F_correspondecia' => $F_correspondecia,
                'Ciudad_correspondencia' => $Ciudad_correspondencia,
                'Nombre_afiliado_pie' => $Nombre_afiliado_pie,
                'Edad_afiliado' => $Edad_afiliado,
                'Nombre_afiliado' => $nombre_destinatario_principal,
                'direccion_destinatario_principal' => $direccion_destinatario_principal,
                'telefono_destinatario_principal' => $telefono_destinatario_principal,
                'ciudad_destinatario_principal' => $ciudad_destinatario_principal,
                'T_documento_noti' => $T_documento_noti,
                'NroIden_afiliado_noti' => $NroIden_afiliado_noti,
                'Email_afiliado_noti' => $Email_afiliado_noti, 
                'PorcentajePcl_dp' => $PorcentajePcl_dp,
                'F_estructuracionPcl_dp' => $F_estructuracionPcl_dp,
                'OrigenPcl_dp' => $OrigenPcl_dp,
                'CIE10Nombres' => $CIE10Nombres,
                'Detalle_calificacion_Fbdp' => $Detalle_calificacion_Fbdp,
                'Nombre_decreto_dp' => $Nombre_decreto_dp,
                'deficiencias_calculadas_factores' => $deficiencias_calculadas_factores,
                'Suma_combinada_dp' => $Suma_combinada_dp,
                'Total_Deficiencia50_dp' => $Total_Deficiencia50_dp,
                'total_rol_ocupacional50' => $total_rol_ocupacional50,
                'total_edad_cronologica' => $total_edad_cronologica,
                'Total_rol_laboral' => $Total_rol_laboral,
                'Total_otras_areas' => $Total_otras_areas,
                'Total_laboral_otras_areas' => $Total_laboral_otras_areas,
                'Firma_cliente' => $Firma_cliente,
                'Anexos_correspondecia' => $Anexos_correspondecia,
                'Elaboro_correspondecia' => $Elaboro_correspondecia,
                'Nombre_empresa_noti' => $Nombre_empresa_noti,
                'Direccion_empresa_noti' => $Direccion_empresa_noti,
                'Telefono_empresa_noti' => $Telefono_empresa_noti,
                'Ciudad_departamento_empresa_noti' => $Ciudad_departamento_empresa_noti,
                'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
                'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
                'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
                'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
                'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
                'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
                'copiaEmail_empresa_noti' => $copiaEmail_empresa_noti,
                'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
                'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
                'Nombre_eps' => $Nombre_eps,
                'Direccion_eps' => $Direccion_eps,
                'Telefono_eps' => $Telefono_eps,
                'Ciudad_departamento_eps' => $Ciudad_departamento_eps,
                'Nombre_afp' => $Nombre_afp,
                'Direccion_afp' => $Direccion_afp,
                'Telefono_afp' => $Telefono_afp,
                'Ciudad_departamento_afp' => $Ciudad_departamento_afp,
                'Nombre_arl' => $Nombre_arl,
                'Direccion_arl' => $Direccion_arl,
                'Telefono_arl' => $Telefono_arl,
                'Ciudad_departamento_arl' => $Ciudad_departamento_arl,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro,
                'Email_eps' => $Email_eps,
                'Email_afp' => $Email_afp,
                'Email_afp_conocimiento' => $Email_afp_conocimiento,
                'Email_arl' => $Email_arl,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            // dd("Formato C : ",$data);
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_formato_c_revisionPension', $data);            
            $nombre_pdf = 'PCL_OFICIO_FC_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';    
            //Obtener el contenido del PDF
            $output = $pdf->output();   
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni_comite}/{$nombre_pdf}"), $output);
            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_pdf
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
            ->update($actualizar_nombre_documento);
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
                ['siae.ID_evento', $ID_Evento_comuni_comite],
                ['siae.Id_proceso', $Id_Proceso_comuni_comite],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
            $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
            ->select('sice.F_comunicado')
            ->where([
                ['sice.N_radicado', $Radicado_comuni_comite]
            ])
            ->get();

            $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_pdf],
            ])->get();
            
            if(count($verficar_documento) == 0){
                // Se valida si antes de insertar la info del doc de Formato C
                //  ya hay un documento de Oficio Pcl, Oficio Incapacidad, Formato B (Por el momento solo se trabaja en el modulo principal de PCL), Formato D y Formato E
                $nombre_docu_pcl = "PCL_OFICIO_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_pcl_inc = "PCL_OFICIO_INC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoB = "PCL_OFICIO_FB_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoD = "PCL_OFICIO_FD_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoE = "PCL_OFICIO_FE_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";

                $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->whereIN('Nombre_documento', [$nombre_docu_pcl, $nombre_docu_pcl_inc, 
                    $nombre_docu_formatoB, $nombre_docu_formatoD, $nombre_docu_formatoE]
                )->get();                
                // Si no existe info del documento de Oficio Pcl, Oficio Incapacidad, Formato B, Formato D y Formato E
                // inserta la info del documento de Formato C, De lo contrario hace una actualización de la info
                if (count($verificar_docu_otro) == 0) {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                }else{
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    ->where([
                        ['Id_Asignacion', $Id_Asignacion_comuni_comite],
                        ['N_radicado_documento', $Radicado_comuni_comite],
                        ['ID_evento', $ID_Evento_comuni_comite]
                    ])
                    ->update($info_descarga_documento);
                }                
            }

            return $pdf->download($nombre_pdf);
        } elseif($Formatod == 'Si') {
            $data = [
                'codigoQR' => $codigoQR,
                'logo_header' => $logo_header,
                'Id_cliente_ent' => $Cliente,
                'ID_evento' => $ID_Evento_comuni_comite,
                'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                'Id_proceso' => $Id_Proceso_comuni_comite,
                'Radicado_comuni' => $Radicado_comuni_comite,
                'Asunto_correspondencia' => $Asunto_correspondencia,
                'Cuerpo_comunicado_correspondencia' => $Cuerpo_comunicado_correspondencia,
                'F_correspondecia' => $F_correspondecia,
                'Ciudad_correspondencia' => $Ciudad_correspondencia,
                'Nombre_afiliado_pie' => $Nombre_afiliado_pie,
                'Nombre_afiliado' => $nombre_destinatario_principal,
                'direccion_destinatario_principal' => $direccion_destinatario_principal,
                'telefono_destinatario_principal' => $telefono_destinatario_principal,
                'ciudad_destinatario_principal' => $ciudad_destinatario_principal,
                'T_documento_noti' => $T_documento_noti,
                'NroIden_afiliado_noti' => $NroIden_afiliado_noti,
                'Email_afiliado_noti' => $Email_afiliado_noti, 
                'PorcentajePcl_dp' => $PorcentajePcl_dp,
                'F_estructuracionPcl_dp' => $F_estructuracionPcl_dp,
                'OrigenPcl_dp' => $OrigenPcl_dp,
                'CIE10Nombres' => $CIE10Nombres,
                'Detalle_calificacion_Fbdp' => $Detalle_calificacion_Fbdp,
                'Nombre_decreto_dp' => $Nombre_decreto_dp,
                'Firma_cliente' => $Firma_cliente,
                'Anexos_correspondecia' => $Anexos_correspondecia,
                'Elaboro_correspondecia' => $Elaboro_correspondecia,
                'Nombre_empresa_noti' => $Nombre_empresa_noti,
                'Direccion_empresa_noti' => $Direccion_empresa_noti,
                'Telefono_empresa_noti' => $Telefono_empresa_noti,
                'Ciudad_departamento_empresa_noti' => $Ciudad_departamento_empresa_noti,
                'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
                'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
                'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
                'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
                'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
                'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
                'copiaEmail_empresa_noti' => $copiaEmail_empresa_noti,
                'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
                'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
                'Nombre_eps' => $Nombre_eps,
                'Direccion_eps' => $Direccion_eps,
                'Telefono_eps' => $Telefono_eps,
                'Ciudad_departamento_eps' => $Ciudad_departamento_eps,
                'Nombre_afp' => $Nombre_afp,
                'Direccion_afp' => $Direccion_afp,
                'Telefono_afp' => $Telefono_afp,
                'Ciudad_departamento_afp' => $Ciudad_departamento_afp,
                'Nombre_arl' => $Nombre_arl,
                'Direccion_arl' => $Direccion_arl,
                'Telefono_arl' => $Telefono_arl,
                'Ciudad_departamento_arl' => $Ciudad_departamento_arl,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro,
                'Email_eps' => $Email_eps,
                'Email_afp' => $Email_afp,
                'Email_afp_conocimiento' => $Email_afp_conocimiento,
                'Email_arl' => $Email_arl,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_formato_d_revisionPension', $data);            
            $nombre_pdf = 'PCL_OFICIO_FD_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';    
            //Obtener el contenido del PDF
            $output = $pdf->output();   
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni_comite}/{$nombre_pdf}"), $output);
            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_pdf
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
            ->update($actualizar_nombre_documento);
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
                ['siae.ID_evento', $ID_Evento_comuni_comite],
                ['siae.Id_proceso', $Id_Proceso_comuni_comite],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
            $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
            ->select('sice.F_comunicado')
            ->where([
                ['sice.N_radicado', $Radicado_comuni_comite]
            ])
            ->get();

            $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_pdf],
            ])->get();
            
            if(count($verficar_documento) == 0){
                // Se valida si antes de insertar la info del doc de Formato D
                //  ya hay un documento de Oficio Pcl, Oficio Incapacidad, Formato B (Por el momento solo se trabaja en el modulo principal de PCL) y Formato E
                $nombre_docu_pcl = "PCL_OFICIO_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_pcl_inc = "PCL_OFICIO_INC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoB = "PCL_OFICIO_FB_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoC = "PCL_OFICIO_FC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoE = "PCL_OFICIO_FE_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";

                $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->whereIN('Nombre_documento', [$nombre_docu_pcl, $nombre_docu_pcl_inc, 
                    $nombre_docu_formatoB, $nombre_docu_formatoC, $nombre_docu_formatoE]
                )->get();                
                // Si no existe info del documento de Oficio Pcl, Oficio Incapacidad, Formato B, Formato C y Formato E
                // inserta la info del documento de Formato D, De lo contrario hace una actualización de la info
                if (count($verificar_docu_otro) == 0) {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                }else{
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    ->where([
                        ['Id_Asignacion', $Id_Asignacion_comuni_comite],
                        ['N_radicado_documento', $Radicado_comuni_comite],
                        ['ID_evento', $ID_Evento_comuni_comite]
                    ])
                    ->update($info_descarga_documento);
                } 
            }

            return $pdf->download($nombre_pdf);
        } elseif($Formatoe == 'Si') {
            $data = [
                'codigoQR' => $codigoQR,
                'logo_header' => $logo_header,
                'Id_cliente_ent' => $Cliente,
                'ID_evento' => $ID_Evento_comuni_comite,
                'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                'Id_proceso' => $Id_Proceso_comuni_comite,
                'Radicado_comuni' => $Radicado_comuni_comite,
                'Asunto_correspondencia' => $Asunto_correspondencia,
                'Cuerpo_comunicado_correspondencia' => $Cuerpo_comunicado_correspondencia,
                'F_correspondecia' => $F_correspondecia,
                'Ciudad_correspondencia' => $Ciudad_correspondencia,
                'Nombre_afiliado_pie' => $Nombre_afiliado_pie,
                'Edad_afiliado' => $Edad_afiliado,
                'Nombre_afiliado' => $nombre_destinatario_principal,
                'direccion_destinatario_principal' => $direccion_destinatario_principal,
                'telefono_destinatario_principal' => $telefono_destinatario_principal,
                'ciudad_destinatario_principal' => $ciudad_destinatario_principal,
                'T_documento_noti' => $T_documento_noti,
                'NroIden_afiliado_noti' => $NroIden_afiliado_noti,
                'Email_afiliado_noti' => $Email_afiliado_noti, 
                'PorcentajePcl_dp' => $PorcentajePcl_dp,
                'F_estructuracionPcl_dp' => $F_estructuracionPcl_dp,
                'OrigenPcl_dp' => $OrigenPcl_dp,
                'CIE10Nombres' => $CIE10Nombres,
                'Detalle_calificacion_Fbdp' => $Detalle_calificacion_Fbdp,
                'Nombre_decreto_dp' => $Nombre_decreto_dp,
                'deficiencias_calculadas_factores' => $deficiencias_calculadas_factores,
                'Suma_combinada_dp' => $Suma_combinada_dp,
                'Total_Deficiencia50_dp' => $Total_Deficiencia50_dp,
                'total_rol_ocupacional50' => $total_rol_ocupacional50,
                'total_edad_cronologica' => $total_edad_cronologica,
                'Total_rol_laboral' => $Total_rol_laboral,
                'Total_otras_areas' => $Total_otras_areas,
                'Total_laboral_otras_areas' => $Total_laboral_otras_areas,
                'Firma_cliente' => $Firma_cliente,
                'Anexos_correspondecia' => $Anexos_correspondecia,
                'Elaboro_correspondecia' => $Elaboro_correspondecia,
                'Nombre_empresa_noti' => $Nombre_empresa_noti,
                'Direccion_empresa_noti' => $Direccion_empresa_noti,
                'Telefono_empresa_noti' => $Telefono_empresa_noti,
                'Ciudad_departamento_empresa_noti' => $Ciudad_departamento_empresa_noti,
                'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
                'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
                'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
                'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
                'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
                'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
                'copiaEmail_empresa_noti' => $copiaEmail_empresa_noti,
                'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
                'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
                'Nombre_eps' => $Nombre_eps,
                'Direccion_eps' => $Direccion_eps,
                'Telefono_eps' => $Telefono_eps,
                'Ciudad_departamento_eps' => $Ciudad_departamento_eps,
                'Nombre_afp' => $Nombre_afp,
                'Direccion_afp' => $Direccion_afp,
                'Telefono_afp' => $Telefono_afp,
                'Ciudad_departamento_afp' => $Ciudad_departamento_afp,
                'Nombre_arl' => $Nombre_arl,
                'Direccion_arl' => $Direccion_arl,
                'Telefono_arl' => $Telefono_arl,
                'Ciudad_departamento_arl' => $Ciudad_departamento_arl,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro,
                'Email_eps' => $Email_eps,
                'Email_afp' => $Email_afp,
                'Email_afp_conocimiento' => $Email_afp_conocimiento,
                'Email_arl' => $Email_arl,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_formato_e_revisionPension', $data);            
            $nombre_pdf = 'PCL_OFICIO_FE_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';    
            //Obtener el contenido del PDF
            $output = $pdf->output();   
            //Guardar el PDF en un archivo
            file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni_comite}/{$nombre_pdf}"), $output);
            $actualizar_nombre_documento = [
                'Nombre_documento' => $nombre_pdf
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
            ->update($actualizar_nombre_documento);
            /* Inserción del registro de que fue descargado */
            // Extraemos el id del servicio asociado
            $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            ->select('siae.Id_servicio')
            ->where([
                ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
                ['siae.ID_evento', $ID_Evento_comuni_comite],
                ['siae.Id_proceso', $Id_Proceso_comuni_comite],
            ])->get();

            $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
            $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
            ->select('sice.F_comunicado')
            ->where([
                ['sice.N_radicado', $Radicado_comuni_comite]
            ])
            ->get();

            $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

            // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['Nombre_documento', $nombre_pdf],
            ])->get();
            
            if(count($verficar_documento) == 0){
                // Se valida si antes de insertar la info del doc de Formato E
                //  ya hay un documento de Oficio Pcl, Oficio Incapacidad, Formato B (Por el momento solo se trabaja en el modulo principal de PCL) y Formato D
                $nombre_docu_pcl = "PCL_OFICIO_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_pcl_inc = "PCL_OFICIO_INC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoB = "PCL_OFICIO_FB_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoC = "PCL_OFICIO_FC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $nombre_docu_formatoD = "PCL_OFICIO_FD_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";

                $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->whereIN('Nombre_documento', [$nombre_docu_pcl, $nombre_docu_pcl_inc, 
                    $nombre_docu_formatoB, $nombre_docu_formatoC, $nombre_docu_formatoD]
                )->get();                
                // Si no existe info del documento de Oficio Pcl, Oficio Incapacidad, Formato B, Formato C y Formato D
                // inserta la info del documento de Formato E, De lo contrario hace una actualización de la info
                if (count($verificar_docu_otro) == 0) {
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                }else{
                    $info_descarga_documento = [
                        'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                        'Id_proceso' => $Id_Proceso_comuni_comite,
                        'Id_servicio' => $Id_servicio,
                        'ID_evento' => $ID_Evento_comuni_comite,
                        'Nombre_documento' => $nombre_pdf,
                        'N_radicado_documento' => $Radicado_comuni_comite,
                        'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                        'F_descarga_documento' => $date,
                        'Nombre_usuario' => $nombre_usuario,
                    ];
                    
                    sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    ->where([
                        ['Id_Asignacion', $Id_Asignacion_comuni_comite],
                        ['N_radicado_documento', $Radicado_comuni_comite],
                        ['ID_evento', $ID_Evento_comuni_comite]
                    ])
                    ->update($info_descarga_documento);
                } 
            }

            return $pdf->download($nombre_pdf);
        }        
    }

    // Generar PDF del Dictamen de PCL Cero

    public function generarPdfDictamenPclCeroRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        $ID_Evento_comuni = $request->ID_Evento_comuni;
        $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
        $Id_Proceso_comuni = $request->Id_Proceso_comuni;
        $Radicado_comuni = $request->Radicado_comuni;
        $Id_Comunicado = $request->Id_Comunicado;
        
        $formattedData = "";

        $dictamenPclQr = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_decreto_eventos as side', 'side.Id_Asignacion', '=', 'siae.Id_Asignacion')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_afiliado_eventos as siaf', 'siaf.ID_evento', '=', 'siae.ID_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siaf.Tipo_documento')
        ->select('siaf.Nombre_afiliado', 'slp.Nombre_parametro', 'siaf.Nro_identificacion', 'siae.Consecutivo_dictamen', 
        'side.Porcentaje_pcl', 'side.F_estructuracion', 'siae.ID_evento')
        ->where('siae.Id_Asignacion', $Id_Asignacion_comuni)->get();     

        if (!$dictamenPclQr->isEmpty()) {
            // Crear una cadena para almacenar los datos en el formato deseado                    
        
            foreach ($dictamenPclQr as $evento) {
                // Construir la cadena de texto con el formato deseado
                $formattedData .= $evento->Nombre_afiliado."\n";
                $formattedData .= $evento->Nombre_parametro." ".$evento->Nro_identificacion . "\n";
                $formattedData .= "N° Dictámen: ".$evento->Consecutivo_dictamen."\n";
                $formattedData .= "CALIFICACIÓN: ".$evento->Porcentaje_pcl."\n";
                $formattedData .= "Fecha estructuración: ".$evento->F_estructuracion."\n";
                $formattedData .= "Cod. Verificación: ".$evento->ID_evento."\n";
        
                // Agregar un salto de línea después de cada conjunto de atributos de evento
                $formattedData .= "\n";
            }
                            
        }

        // Codigo QR
        $datos = $formattedData;
        $codigoQR = QrCode::size(110)->margin(0.5)->generate($datos);         

        //Captura de datos de informacion general del dictamen pericial

        $fecha_dictamen = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->select('F_visado_comite')->where([['ID_evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get();
        if(count($fecha_dictamen) == 0){
            $Fecha_dictamen = '';
        }else{
            $Fecha_dictamen = $fecha_dictamen[0]->F_visado_comite;
        }
        $array_datos_info_dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as sltp', 'sltp.Id_Evento', '=', 'side.Tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'side.Tipo_enfermedad')
        ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'side.Cobertura', 'side.Decreto_calificacion', 
        'side.Numero_dictamen', 'side.PCL_anterior', 'side.Descripcion_nueva_calificacion', 'side.Relacion_documentos', 'side.Otros_relacion_doc', 
        'side.Descripcion_enfermedad_actual', 'side.Suma_combinada', 'side.Total_Deficiencia50', 'side.Porcentaje_pcl', 'side.Rango_pcl', 
        'side.Monto_indemnizacion', 'side.Tipo_evento', 'sltp.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro as Nombre_origen', 'side.F_evento', 
        'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slpa.Nombre_parametro as Nombre_enfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.N_radicado', 
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro', 'side.N_siniestro')
        ->where([['side.ID_Evento',$ID_Evento_comuni], ['side.Id_Asignacion',$Id_Asignacion_comuni]])->get();  
        $N_siniestro = $array_datos_info_dictamen[0]->N_siniestro;
        $DictamenNo = $array_datos_info_dictamen[0]->Numero_dictamen;
        
                
        $motivo_solicitud_dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pericial_eventos as sipe')
        ->leftJoin('sigmel_gestiones.sigmel_lista_motivo_solicitudes as slms', 'slms.Id_Solicitud', '=', 'sipe.Id_motivo_solicitud')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sipe.Regimen_salud')
        ->leftJoin('sigmel_gestiones.sigmel_lista_solicitantes as sls', 'sls.Id_solicitante', '=', 'sipe.Id_solicitante')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sipe.Id_nombre_solicitante')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sie.Id_Ciudad')
        ->select('sipe.Id_motivo_solicitud','slms.Nombre_solicitud', 'sipe.Regimen_salud', 'slp.Nombre_parametro as Regimenes_salud', 
        'sipe.Id_solicitante', 'sls.Solicitante', 'sipe.Id_nombre_solicitante', 'sie.Nombre_entidad', 'sie.Nit_entidad', 'sie.Telefonos', 
        'sie.Emails', 'sie.Direccion', 'sie.Id_Ciudad', 'sldm.Nombre_municipio')
        ->where([['ID_evento',$ID_Evento_comuni]])->limit(1)->get();        
        $Motivo_solicitud = $motivo_solicitud_dictamen[0]->Nombre_solicitud;
        $Id_solicitante_dic = $motivo_solicitud_dictamen[0]->Id_solicitante;

        $array_datos_info_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siae.Tipo_documento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'siae.Nivel_escolar')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpar', 'slpar.Id_Parametro', '=', 'siae.Estado_civil')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'siae.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmu', 'sldmu.Id_municipios', '=', 'siae.Id_municipio_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'siae.Id_eps')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sien', 'sien.Id_Entidad', '=', 'siae.Id_afp')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sient', 'sient.Id_Entidad', '=', 'siae.Id_arl')
        ->select('siae.ID_evento', 'siae.Nombre_afiliado', 'siae.Tipo_documento', 'slp.Nombre_parametro as T_documento', 'siae.Nro_identificacion', 
        'siae.F_nacimiento', 'siae.Edad', 'siae.Genero', 'siae.Email', 'siae.Telefono_contacto', 'siae.Estado_civil', 
        'slpar.Nombre_parametro as Estado_civi', 'siae.Nivel_escolar', 'slpa.Nombre_parametro as Escolaridad', 
        'siae.Apoderado', 'siae.Nombre_apoderado', 'siae.Nro_identificacion_apoderado', 'siae.Id_dominancia', 'siae.Direccion', 
        'siae.Id_departamento', 'siae.Id_municipio', 'sldm.Nombre_municipio as Nombre_municipio', 'siae.Ocupacion', 'siae.Tipo_afiliado', 
        'siae.Ibc', 'siae.Id_eps', 'sie.Nombre_entidad as Entidad_eps', 'siae.Id_afp', 'sien.Nombre_entidad as Entidad_afp', 'siae.Id_arl', 
        'sient.Nombre_entidad as Entidad_arl', 'siae.Activo', 'siae.Medio_notificacion', 'siae.Nombre_afiliado_benefi', 
        'siae.Tipo_documento_benefi', 'siae.Nro_identificacion_benefi', 'siae.Direccion_benefi', 'siae.Id_departamento_benefi', 
        'siae.Id_municipio_benefi', 'sldmu.Nombre_municipio as Nombre_municipio_benefi', 'siae.Nombre_usuario', 'siae.F_registro', 
        'F_actualizacion')
        ->where([['ID_Evento',$ID_Evento_comuni]])->get();        

        $Tipo_afiliado = $array_datos_info_afiliado[0]->Tipo_afiliado;
        $Ocupacion_afiliado = $array_datos_info_afiliado[0]->Ocupacion;

        if ($Tipo_afiliado !== 27 ) {
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $NroIden_afiliado_dic = $array_datos_info_afiliado[0]->Nro_identificacion;
            $Telefono_afiliado_dic = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Email_afiliado_dic = $array_datos_info_afiliado[0]->Email;
            $Direccion_afiliado_dic = $array_datos_info_afiliado[0]->Direccion;
            $Ciudad_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_municipio;
        }else{
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
            $NroIden_afiliado_dic = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
            $Telefono_afiliado_dic = '';
            $Email_afiliado_dic = '';
            $Direccion_afiliado_dic = $array_datos_info_afiliado[0]->Direccion_benefi;
            $Ciudad_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
        }

        if($Id_solicitante_dic == 1 || $Id_solicitante_dic == 2 ||  $Id_solicitante_dic == 3){
            $Solicitante_dic = $motivo_solicitud_dictamen[0]->Solicitante;
            $Nombre_entidad_dic = $motivo_solicitud_dictamen[0]->Nombre_entidad;
            $Nit_entidad = $motivo_solicitud_dictamen[0]->Nit_entidad;
            $Telefonos_dic = $motivo_solicitud_dictamen[0]->Telefonos;
            $Emails_dic = $motivo_solicitud_dictamen[0]->Emails;
            $Direccion_dic = $motivo_solicitud_dictamen[0]->Direccion;
            $Nombre_municipio_dic = $motivo_solicitud_dictamen[0]->Nombre_municipio;
        }else{
            $Solicitante_dic = $motivo_solicitud_dictamen[0]->Solicitante;
            $Nombre_entidad_dic = $Nombre_afiliado_dic;
            $Nit_entidad = $NroIden_afiliado_dic;
            $Telefonos_dic = $Telefono_afiliado_dic;
            $Emails_dic = $Email_afiliado_dic;
            $Direccion_dic = $Direccion_afiliado_dic;
            $Nombre_municipio_dic = $Ciudad_afiliado_dic;
        }

        //Captura de datos de informacion general de la entidad calificadora

        $array_datos_info_entidad_cali = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
        ->leftJoin('sigmel_gestiones.sigmel_clientes as sc', 'sc.Id_cliente', '=', 'sie.Cliente')
        ->select('sie.ID_evento', 'sie.Cliente', 'sc.Nombre_cliente', 'sc.Nit', 'sc.Telefono_principal', 'sc.Direccion', 'sc.Email_principal')
        ->where([['sie.ID_evento',$ID_Evento_comuni]])->get();                
        
        $Cliente = $array_datos_info_entidad_cali[0]->Cliente;        
        // Logo cliente del Header

        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
        } else {
            $logo_header = "Sin logo";
        }       

        $Nombre_cliente_ent = $array_datos_info_entidad_cali[0]->Nombre_cliente;
        $Nit_ent = $array_datos_info_entidad_cali[0]->Nit;
        $Telefono_principal_ent = $array_datos_info_entidad_cali[0]->Telefono_principal;
        $Direccion_ent = $array_datos_info_entidad_cali[0]->Direccion;
        $Email_principal_ent = $array_datos_info_entidad_cali[0]->Email_principal;        

        //Captura de datos generales de la persona calificada

        if ($Tipo_afiliado == 27) {
            $Afiliado_per_cal = '';
            $Beneficiario_per_cal = 'X';
            function separarNombreApellido($nombreCompleto) {
                // Dividir la cadena en palabras
                $palabras = explode(' ', $nombreCompleto);
                $numPalabras = count($palabras);
            
                if ($numPalabras == 2) {
                    $nombre = $palabras[0];
                    $apellido = $palabras[1];
                } elseif ($numPalabras == 3) {
                    $nombre = $palabras[0];
                    $apellido = implode(' ', array_slice($palabras, 1));
                } elseif ($numPalabras == 4) {
                    $nombre = implode(' ', array_slice($palabras, 0, 2));
                    $apellido = implode(' ', array_slice($palabras, 2));
                } else {
                    $nombre = '';
                    $apellido = '';
                }
            
                return array('nombre' => $nombre, 'apellido' => $apellido);
            }  
            $Nombre_per_cal = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $ResultadoNombre_per_cal = separarNombreApellido($Nombre_per_cal);            
            $Tipo_documento_per_cal = $array_datos_info_afiliado[0]->T_documento;            
            $NroIden_per_cal = $array_datos_info_afiliado[0]->Nro_identificacion;
            $F_nacimiento_per_cal = $array_datos_info_afiliado[0]->F_nacimiento;            
            $Edad_per_cal = $array_datos_info_afiliado[0]->Edad;            
            $Nivel_escolar_per_cal = $array_datos_info_afiliado[0]->Escolaridad;
            $Estado_civil_per_cal = $array_datos_info_afiliado[0]->Estado_civi;
            $Telefono_per_cal = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Direccion_per_cal = $array_datos_info_afiliado[0]->Direccion;
            $Ciudad_per_cal = $array_datos_info_afiliado[0]->Nombre_municipio;
            $Email_per_cal = $array_datos_info_afiliado[0]->Email;
            $Nombre_ben = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
            $Tipo_iden_ben = $array_datos_info_afiliado[0]->Tipo_documento_benefi;            
            $Documento_iden_ben = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
            $Telefono_iden_ben = '';
            $Ciudad_iden_ben = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
            //Datod del acudiente
            if($Edad_per_cal < 18){
                $Nombre_acudiente = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
                $Documento_acudiente = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
                $Telefono_acudiente = '';
                $Ciudad_acudiente = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
            }else{
                $Nombre_acudiente = '';
                $Documento_acudiente = '';
                $Telefono_acudiente = '';
                $Ciudad_acudiente = '';
            }
        }else {
            $Afiliado_per_cal = 'X';
            $Beneficiario_per_cal = '';
            function separarNombreApellido($nombreCompleto) {
                // Dividir la cadena en palabras
                $palabras = explode(' ', $nombreCompleto);
                $numPalabras = count($palabras);
            
                if ($numPalabras == 2) {
                    $nombre = $palabras[0];
                    $apellido = $palabras[1];
                } elseif ($numPalabras == 3) {
                    $nombre = $palabras[0];
                    $apellido = implode(' ', array_slice($palabras, 1));
                } elseif ($numPalabras == 4) {
                    $nombre = implode(' ', array_slice($palabras, 0, 2));
                    $apellido = implode(' ', array_slice($palabras, 2));
                } else {
                    $nombre = '';
                    $apellido = '';
                }
            
                return array('nombre' => $nombre, 'apellido' => $apellido);
            }  
            $Nombre_per_cal = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $ResultadoNombre_per_cal = separarNombreApellido($Nombre_per_cal);            
            $Tipo_documento_per_cal = $array_datos_info_afiliado[0]->T_documento;            
            $NroIden_per_cal = $array_datos_info_afiliado[0]->Nro_identificacion;
            $F_nacimiento_per_cal = $array_datos_info_afiliado[0]->F_nacimiento;            
            $Edad_per_cal = $array_datos_info_afiliado[0]->Edad;            
            $Nivel_escolar_per_cal = $array_datos_info_afiliado[0]->Escolaridad;
            $Estado_civil_per_cal = $array_datos_info_afiliado[0]->Estado_civi;
            $Telefono_per_cal = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Direccion_per_cal = $array_datos_info_afiliado[0]->Direccion;
            $Ciudad_per_cal = $array_datos_info_afiliado[0]->Nombre_municipio;
            $Email_per_cal = $array_datos_info_afiliado[0]->Email;
            $Nombre_ben = '';
            $Tipo_iden_ben = '';
            $Documento_iden_ben = '';
            $Telefono_iden_ben = '';
            $Ciudad_iden_ben = '';
            $Nombre_acudiente = '';
            $Documento_acudiente = '';
            $Telefono_acudiente = '';
            $Ciudad_acudiente = '';
        }

        if ($Documento_iden_ben == '') {
            $Numero_documento_afiliado = $NroIden_per_cal;
            $Documento_afiliado = $Tipo_documento_per_cal;
            $Nombre_afiliado_pre = $Nombre_per_cal;
        } else {            
            $Numero_documento_afiliado = $Documento_iden_ben;
            $Documento_afiliado = $Tipo_iden_ben;
            $Nombre_afiliado_pre = $Nombre_ben;
        }
        

        //Captura de datos de Etapas del ciclo vital

        $validar_laboralmente_activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();       

        if (count($validar_laboralmente_activo) > 0) {
            $Poblacion_edad_econo_activa = 'X';
        }else{
            $Poblacion_edad_econo_activa = '';
        }        

        $validar_rol_ocupacional = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();       

        if (count($validar_rol_ocupacional) > 0) {
            if ($validar_rol_ocupacional[0]->Poblacion_calificar == 75) {
                $Bebe_menor3 = 'X';
                $Ninos_adolecentes = '';
                $Adultos_mayores = '';                
            }elseif($validar_rol_ocupacional[0]->Poblacion_calificar == 76){
                $Bebe_menor3 = '';
                $Ninos_adolecentes = 'X';
                $Adultos_mayores = '';
            }elseif($validar_rol_ocupacional[0]->Poblacion_calificar == 77){
                $Bebe_menor3 = '';
                $Ninos_adolecentes = '';
                $Adultos_mayores = 'X';
            }
            
        }else{
            $Bebe_menor3 = '';
            $Ninos_adolecentes = '';
            $Adultos_mayores = '';
        } 

        //Captura de datos de Afiliacion al siss:

        $Regimen_salud_ecv = $motivo_solicitud_dictamen[0]->Regimen_salud;
        
        if($Regimen_salud_ecv == 37) {
            $Contributivo_ecv = 'X';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = '';
        }elseif($Regimen_salud_ecv == 38){
            $Contributivo_ecv = '';
            $Subsidiado_ecv = 'X';
            $No_afiliado_ecv = '';
        }elseif($Regimen_salud_ecv == 39){
            $Contributivo_ecv = '';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = 'X';
        }else{
            $Contributivo_ecv = '';
            $Subsidiado_ecv = '';
            $No_afiliado_ecv = '';
        }
        
        $Entidad_eps = $array_datos_info_afiliado[0]->Entidad_eps;
        $Entidad_afp = $array_datos_info_afiliado[0]->Entidad_afp;
        $Entidad_arl = $array_datos_info_afiliado[0]->Entidad_arl;

        //Captura de datos Antecedentes laborales del calificado

        $array_datos_info_antecedentes_laborales = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
        ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'sile.Id_actividad_economica')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'sile.Id_clase_riesgo')
        ->select('sile.Tipo_empleado', 'sile.Cargo', 'sile.Id_codigo_ciuo', 'slcc.Nombre_ciuo', 'sile.Funciones_cargo', 'sile.Empresa', 
        'sile.Nit_o_cc', 'sile.Id_actividad_economica', 'slae.Nombre_actividad', 'sile.Id_clase_riesgo','slcr.Nombre_riesgo')
        ->where([['ID_Evento',$ID_Evento_comuni]])->get();

        $Tipo_empleado_laboral = $array_datos_info_antecedentes_laborales[0]->Tipo_empleado;

        if ($Tipo_empleado_laboral == 'Empleado actual') {
            $Independiente_laboral = '';
            $Dedependiente_laboral = 'X';
        } else {
            $Independiente_laboral = 'X';
            $Dedependiente_laboral = '';
        }

        $Nombre_cargo_laboral = $array_datos_info_antecedentes_laborales[0]->Cargo;
        $Codigo_ciuo_laboral = $array_datos_info_antecedentes_laborales[0]->Nombre_ciuo;
        $Actividad_econo_laboral = $array_datos_info_antecedentes_laborales[0]->Nombre_actividad;
        $Clase_laboral = $array_datos_info_antecedentes_laborales[0]->Nombre_riesgo;
        $Funciones_cargo_laboral = $array_datos_info_antecedentes_laborales[0]->Funciones_cargo;
        $Empresa_laboral = $array_datos_info_antecedentes_laborales[0]->Empresa;
        $Nit_laboral = $array_datos_info_antecedentes_laborales[0]->Nit_o_cc;    
        
        //Captura de datos Realacion de documentos/examenes fisico(Descripción)

        $array_datos_relacion_examentes = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado_Recalificacion', 'Activo']])->get();  

        //Captura de datos Fundamentos para la calificacion de la perdida de la capacidad laboral y ocupacional - titulos I Y II

        $Descripcion_enfermedad_actual = $array_datos_info_dictamen[0]->Descripcion_enfermedad_actual;

        $array_diagnosticos_fc = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro as Nombre_origen', 
        'slp2.Nombre_parametro as Nombre_lateralidad', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado_Recalificacion', 'Activo']])->get();  

        $array_deficiencias_alteraciones = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
        ->select('sidae.Id_tabla', 'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.FU', 'sidae.CFM1', 'sidae.CFM2', 
        'sidae.Clase_Final', 'sidae.Total_deficiencia', 'sidae.CAT', 'sidae.MSD')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();  
        
        $Suma_combinada_fc = $array_datos_info_dictamen[0]->Suma_combinada;

        $array_deficiencia_auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();
        
        $array_deficiencia_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get(); 

        $Total_deficiencia50_fc = $array_datos_info_dictamen[0]->Total_Deficiencia50;

        $array_datos_laboralmente_activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();  

        $array_datos_rol_ocupacional = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get();

        //Captura de datos Concepto final del dictamen pericial
        
        $Porcentaje_Pcl_dp = 0;
        $F_estructuracion_dp = $array_datos_info_dictamen[0]->F_estructuracion;
        $Tipo_evento_dp = $array_datos_info_dictamen[0]->Nombre_evento;
        $Sustentacion_F_estructuracion_dp = $array_datos_info_dictamen[0]->Sustentacion_F_estructuracion;
        $F_evento_dp = $array_datos_info_dictamen[0]->F_evento;
        $Origen_dp = $array_datos_info_dictamen[0]->Nombre_origen;
        $Detalle_calificacion_dp = $array_datos_info_dictamen[0]->Detalle_calificacion;
        $Enfermedad_catastrofica_dp = $array_datos_info_dictamen[0]->Enfermedad_catastrofica;
        $Enfermedad_congenita_dp = $array_datos_info_dictamen[0]->Enfermedad_congenita;
        $Nombre_enfermedad_dp = $array_datos_info_dictamen[0]->Nombre_enfermedad;
        $Requiere_tercera_persona_dp = $array_datos_info_dictamen[0]->Requiere_tercera_persona;
        $Requiere_tercera_persona_decisiones_dp = $array_datos_info_dictamen[0]->Requiere_tercera_persona_decisiones;
        $Requiere_dispositivo_apoyo_dp = $array_datos_info_dictamen[0]->Requiere_dispositivo_apoyo;
        $Justificacion_dependencia_dp = $array_datos_info_dictamen[0]->Justificacion_dependencia;

        //consulta si esta visado o no para mostrar las firmas

        $validacion_visado = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->select('ID_evento', 'Id_proceso', 'Id_Asignacion', 'Visar')
        ->where([['Id_Asignacion',$Id_Asignacion_comuni], ['Visar','Si']])->get();
               
        //Obtener los datos del formulario
        
        $data = [
            'logo_header' => $logo_header,
            'Id_cliente_ent' => $Cliente,
            'codigoQR' => $codigoQR,
            'ID_evento' => $ID_Evento_comuni,
            'Id_Asignacion' => $Id_Asignacion_comuni,
            'Id_proceso' => $Id_Proceso_comuni,
            'Radicado_comuni' => $Radicado_comuni,
            'Fecha_dictamen'=> $Fecha_dictamen,
            'DictamenNo' => $DictamenNo,
            'Motivo_solicitud' => $Motivo_solicitud,
            'Solicitante_dic' => $Solicitante_dic,
            'Nombre_entidad_dic' => $Nombre_entidad_dic,
            'Nit_entidad' => $Nit_entidad,
            'Telefonos_dic' => $Telefonos_dic,
            'Emails_dic' => $Emails_dic,
            'Direccion_dic' => $Direccion_dic,
            'Nombre_municipio_dic' => $Nombre_municipio_dic,
            'Nombre_cliente_ent' => $Nombre_cliente_ent,
            'Nit_ent' => $Nit_ent,
            'Telefono_principal_ent' => $Telefono_principal_ent,
            'Direccion_ent' => $Direccion_ent,
            'Email_principal_ent' => $Email_principal_ent,
            'Afiliado_per_cal' => $Afiliado_per_cal,
            'Beneficiario_per_cal' => $Beneficiario_per_cal,
            'ResultadoNombre_per_cal' => $Nombre_per_cal,
            'Tipo_documento_per_cal' => $Tipo_documento_per_cal,
            'NroIden_per_cal' => $NroIden_per_cal,
            'F_nacimiento_per_cal' => $F_nacimiento_per_cal,
            'Edad_per_cal' => $Edad_per_cal,
            'Nivel_escolar_per_cal' => $Nivel_escolar_per_cal,
            'Estado_civil_per_cal' => $Estado_civil_per_cal,
            'Telefono_per_cal' => $Telefono_per_cal,
            'Direccion_per_cal' => $Direccion_per_cal,
            'Ciudad_per_cal' => $Ciudad_per_cal,
            'Email_per_cal' => $Email_per_cal,
            'Nombre_ben' => $Nombre_ben,
            'Documento_iden_ben' => $Documento_iden_ben,
            'Telefono_iden_ben' => $Telefono_iden_ben,
            'Ciudad_iden_ben' => $Ciudad_iden_ben,
            'Poblacion_edad_econo_activa' => $Poblacion_edad_econo_activa,
            'Bebe_menor3' => $Bebe_menor3,
            'Ninos_adolecentes' => $Ninos_adolecentes,
            'Adultos_mayores' => $Adultos_mayores,
            'Nombre_acudiente' => $Nombre_acudiente,
            'Documento_acudiente' => $Documento_acudiente,
            'Telefono_acudiente' => $Telefono_acudiente,
            'Ciudad_acudiente' => $Ciudad_acudiente,
            'Contributivo_ecv' => $Contributivo_ecv,
            'Subsidiado_ecv' => $Subsidiado_ecv,
            'No_afiliado_ecv' => $No_afiliado_ecv,
            'Entidad_eps' => $Entidad_eps,
            'Entidad_afp' => $Entidad_afp,
            'Entidad_arl' => $Entidad_arl,
            'Independiente_laboral' => $Independiente_laboral,
            'Dedependiente_laboral' => $Dedependiente_laboral,
            'Nombre_cargo_laboral' => $Nombre_cargo_laboral,
            'Ocupacion_afiliado' => $Ocupacion_afiliado,
            'Codigo_ciuo_laboral' => $Codigo_ciuo_laboral,
            'Actividad_econo_laboral' => $Actividad_econo_laboral,
            'Clase_laboral' => $Clase_laboral,
            'Funciones_cargo_laboral' => $Funciones_cargo_laboral,
            'Empresa_laboral' => $Empresa_laboral,
            'Nit_laboral' => $Nit_laboral,
            'array_datos_relacion_examentes' => $array_datos_relacion_examentes,
            'Descripcion_enfermedad_actual' => $Descripcion_enfermedad_actual,
            'array_diagnosticos_fc' => $array_diagnosticos_fc,
            'array_deficiencias_alteraciones' => $array_deficiencias_alteraciones,
            'Suma_combinada_fc' => $Suma_combinada_fc,
            'array_deficiencia_auditiva' => $array_deficiencia_auditiva,
            'array_deficiencia_visual' => $array_deficiencia_visual,
            'Total_deficiencia50_fc' => $Total_deficiencia50_fc,
            'array_datos_laboralmente_activo' => $array_datos_laboralmente_activo,
            'array_datos_rol_ocupacional' => $array_datos_rol_ocupacional,
            'Porcentaje_Pcl_dp' => $Porcentaje_Pcl_dp,
            'F_estructuracion_dp' => $F_estructuracion_dp,
            'Tipo_evento_dp' => $Tipo_evento_dp,
            'Sustentacion_F_estructuracion_dp' => $Sustentacion_F_estructuracion_dp,
            'F_evento_dp' => $F_evento_dp,
            'Origen_dp' => $Origen_dp,
            'Detalle_calificacion_dp' => $Detalle_calificacion_dp,
            'Enfermedad_catastrofica_dp' => $Enfermedad_catastrofica_dp,
            'Enfermedad_congenita_dp' => $Enfermedad_congenita_dp,
            'Nombre_enfermedad_dp' => $Nombre_enfermedad_dp,
            'Requiere_tercera_persona_dp' => $Requiere_tercera_persona_dp,
            'Requiere_tercera_persona_decisiones_dp' => $Requiere_tercera_persona_decisiones_dp,
            'Requiere_dispositivo_apoyo_dp' => $Requiere_dispositivo_apoyo_dp,
            'Justificacion_dependencia_dp' => $Justificacion_dependencia_dp,
            'Numero_documento_afiliado' => $Numero_documento_afiliado,
            'Documento_afiliado' => $Documento_afiliado,
            'Nombre_afiliado_pre' => $Nombre_afiliado_pre,
            'validacion_visado' => $validacion_visado,
            'N_siniestro' => $N_siniestro
        ];

        // Crear una instancia de Dompdf

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Prev/PCL/dictamen_Pcl_Ceroprev', $data);        
        $nombre_pdf = 'PCL_DML_'.$Id_Asignacion_comuni.'_'.$Numero_documento_afiliado.'.pdf';    
        //Obtener el contenido del PDF
        $output = $pdf->output();
        //Guardar el PDF en un archivo
        file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni}/{$nombre_pdf}"), $output);
        $actualizar_nombre_documento = [
            'Nombre_documento' => $nombre_pdf
        ];
        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
        ->update($actualizar_nombre_documento);
        /* Inserción del registro de que fue descargado */
        // Extraemos el id del servicio asociado
        $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->select('siae.Id_servicio')
        ->where([
            ['siae.Id_Asignacion', $Id_Asignacion_comuni],
            ['siae.ID_evento', $ID_Evento_comuni],
            ['siae.Id_proceso', $Id_Proceso_comuni],
        ])->get();

        $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        ->select('sice.F_comunicado')
        ->where([
            ['sice.N_radicado', $Radicado_comuni]
        ])
        ->get();

        $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

        // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
        $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        ->select('Nombre_documento')
        ->where([
            ['Nombre_documento', $nombre_pdf],
        ])->get();
        
        if(count($verficar_documento) == 0){
            $info_descarga_documento = [
                'Id_Asignacion' => $Id_Asignacion_comuni,
                'Id_proceso' => $Id_Proceso_comuni,
                'Id_servicio' => $Id_servicio,
                'ID_evento' => $ID_Evento_comuni,
                'Nombre_documento' => $nombre_pdf,
                'N_radicado_documento' => $Radicado_comuni,
                'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                'F_descarga_documento' => $date,
                'Nombre_usuario' => $nombre_usuario,
            ];
            
            sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        }

        return $pdf->download($nombre_pdf);   
    }
    // Generar PDF de Notificacion Cero

    public function generarPdfNotificacionPclCeroRe(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        $ID_Evento_comuni_comite = $request->ID_Evento_comuni_comite;
        $Id_Asignacion_comuni_comite = $request->Id_Asignacion_comuni_comite;
        $Id_Proceso_comuni_comite = $request->Id_Proceso_comuni_comite;
        $Radicado_comuni_comite = $request->Radicado_comuni_comite;
        $Firma_comuni_comite = $request->Firma_comuni_comite;
        $Id_Comunicado = $request->Id_Comunicado;

        // Captura de datos para logo del cliente y informacion de las entidades

        $array_datos_info_entidad_cali = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
        ->leftJoin('sigmel_gestiones.sigmel_clientes as sc', 'sc.Id_cliente', '=', 'sie.Cliente')
        ->select('sie.ID_evento', 'sie.Cliente', 'sc.Nombre_cliente', 'sc.Nit', 'sc.Telefono_principal', 'sc.Direccion', 'sc.Email_principal')
        ->where([['sie.ID_evento',$ID_Evento_comuni_comite]])->get();                
        
        $Cliente = $array_datos_info_entidad_cali[0]->Cliente;        
        // Logo cliente del Header
        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->get();

        if (count($dato_logo_header) > 0) {
            $logo_header = $dato_logo_header[0]->Logo_cliente;
        } else {
            $logo_header = "Sin logo";
        } 

        //Footer image
        $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
        ->select('Footer_cliente')
        ->where([['Id_cliente', $Cliente]])
        ->limit(1)->get();

        if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
            $footer = $footer_imagen[0]->Footer_cliente;
        } else {
            $footer = null;
        } 
        // Captura de datos de Comite interdiciplinario y correspondencia

        $array_datos_comite_inter = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni_comite], ['Id_Asignacion',$Id_Asignacion_comuni_comite]])->get(); 

        $Asunto_correspondencia = $array_datos_comite_inter[0]->Asunto;
        $Cuerpo_comunicado_correspondencia = $array_datos_comite_inter[0]->Cuerpo_comunicado;
        $Ciudad_correspondencia = $array_datos_comite_inter[0]->Ciudad;
        $F_correspondecia = $array_datos_comite_inter[0]->F_correspondecia;        
        $Anexos_correspondecia = $array_datos_comite_inter[0]->Anexos;
        $Elaboro_correspondecia = $array_datos_comite_inter[0]->Elaboro;
        $Copia_empleador_correspondecia = $array_datos_comite_inter[0]->Copia_empleador;
        $Copia_eps_correspondecia = $array_datos_comite_inter[0]->Copia_eps;
        $Copia_afp_correspondecia = $array_datos_comite_inter[0]->Copia_afp;
        $Copia_arl_correspondecia = $array_datos_comite_inter[0]->Copia_arl;


        //Captura de datos del afiliado 

        $array_datos_info_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'siae.Tipo_documento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'siae.Nivel_escolar')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpar', 'slpar.Id_Parametro', '=', 'siae.Estado_civil')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as slde', 'slde.Id_departamento', '=', 'siae.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'siae.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpara', 'slpara.Id_Parametro', '=', 'siae.Tipo_documento_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldep', 'sldep.Id_departamento', '=', 'siae.Id_departamento_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmu', 'sldmu.Id_municipios', '=', 'siae.Id_municipio_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'siae.Id_eps')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldepa', 'sldepa.Id_departamento', '=', 'sie.Id_Departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmun', 'sldmun.Id_municipios', '=', 'sie.Id_Ciudad')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sien', 'sien.Id_Entidad', '=', 'siae.Id_afp')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldepar', 'sldepar.Id_departamento', '=', 'sien.Id_Departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmuni', 'sldmuni.Id_municipios', '=', 'sien.Id_Ciudad')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sient', 'sient.Id_Entidad', '=', 'siae.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldepart', 'sldepart.Id_departamento', '=', 'sient.Id_Departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmunic', 'sldmunic.Id_municipios', '=', 'sient.Id_Ciudad')
        ->select('siae.ID_evento', 'siae.Nombre_afiliado', 'siae.Tipo_documento', 'slp.Nombre_parametro as T_documento', 
        'siae.Nro_identificacion', 'siae.F_nacimiento', 'siae.Edad', 'siae.Genero', 'siae.Email', 'siae.Telefono_contacto', 
        'siae.Estado_civil', 'slpar.Nombre_parametro as Estado_civi', 'siae.Nivel_escolar', 'slpa.Nombre_parametro as Escolaridad', 
        'siae.Apoderado', 'siae.Nombre_apoderado', 'siae.Nro_identificacion_apoderado', 'siae.Id_dominancia', 'siae.Direccion', 
        'siae.Id_departamento', 'slde.Nombre_departamento as Nombre_departamento', 'siae.Id_municipio', 'sldm.Nombre_municipio as Nombre_municipio', 
        'siae.Ocupacion', 'siae.Tipo_afiliado', 'siae.Ibc', 'siae.Id_eps', 'sie.Nombre_entidad as Entidad_eps', 'sie.Direccion as Direccion_eps', 
        'sie.Telefonos as Telefono_eps', 'sie.Id_Departamento', 'sldepa.Nombre_departamento as Nombre_departamento_eps', 'sie.Id_Ciudad', 
        'sldmun.Nombre_municipio as Nombre_municipio_eps', 'siae.Id_afp', 'sien.Nombre_entidad as Entidad_afp', 
        'sien.Direccion as Direccion_afp', 'sien.Telefonos as Telefono_afp', 'sien.Id_Departamento', 
        'sldepar.Nombre_departamento as Nombre_departamento_afp', 'sien.Id_Ciudad', 
        'sldmuni.Nombre_municipio as Nombre_municipio_afp', 'siae.Id_arl', 'sient.Nombre_entidad as Entidad_arl', 
        'sient.Direccion as Direccion_arl', 'sient.Telefonos as Telefono_arl', 'sient.Id_Departamento', 
        'sldepart.Nombre_departamento as Nombre_departamento_arl', 'sient.Id_Ciudad',
        'sldmunic.Nombre_municipio as Nombre_municipio_arl',
        'siae.Activo', 
        'siae.Medio_notificacion', 'siae.Nombre_afiliado_benefi', 'siae.Tipo_documento_benefi', 'slpara.Nombre_parametro as Tipo_documento_benfi',         
        'siae.Nro_identificacion_benefi', 'siae.Direccion_benefi', 'siae.Id_departamento_benefi', 
        'sldep.Nombre_departamento as Nombre_departamento_benefi', 'siae.Id_municipio_benefi', 
        'sldmu.Nombre_municipio as Nombre_municipio_benefi', 'siae.Nombre_usuario', 'siae.F_registro', 'F_actualizacion')
        ->where([['ID_Evento',$ID_Evento_comuni_comite]])->limit(1)->get(); 

        $Tipo_afiliado = $array_datos_info_afiliado[0]->Tipo_afiliado;

        if ($Tipo_afiliado !== 27 ) {
            $Nombre_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $Direccion_afiliado_noti = $array_datos_info_afiliado[0]->Direccion;
            $Telefono_afiliado_noti = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Departamento_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_departamento;            
            $Ciudad_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_municipio;
            $T_documento_noti = $array_datos_info_afiliado[0]->T_documento;            
            $NroIden_afiliado_noti = $array_datos_info_afiliado[0]->Nro_identificacion;
            $Email_afiliado_noti = $array_datos_info_afiliado[0]->Email;
        }else{
            $Nombre_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
            $Direccion_afiliado_noti = $array_datos_info_afiliado[0]->Direccion_benefi;
            $Telefono_afiliado_noti = '';
            $Departamento_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_departamento_benefi;            
            $Ciudad_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
            $T_documento_noti = $array_datos_info_afiliado[0]->Tipo_documento_benfi;            
            $NroIden_afiliado_noti = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
            $Email_afiliado_noti = '';
        }

        if(!empty($Copia_eps_correspondecia) && $Copia_eps_correspondecia == 'EPS'){
            $Nombre_eps = $array_datos_info_afiliado[0]->Entidad_eps;
            $Direccion_eps = $array_datos_info_afiliado[0]->Direccion_eps;
            $Telefono_eps = $array_datos_info_afiliado[0]->Telefono_eps;        
            $Ciudad_departamento_eps = $array_datos_info_afiliado[0]->Nombre_municipio_eps.'-'.$array_datos_info_afiliado[0]->Nombre_departamento_eps;            
        }else{
            $Nombre_eps = '';
            $Direccion_eps = '';
            $Telefono_eps = '';
            $Ciudad_departamento_eps = '';
        }
        
        if(!empty($Copia_afp_correspondecia) && $Copia_afp_correspondecia == 'AFP'){
            $Nombre_afp = $array_datos_info_afiliado[0]->Entidad_afp;
            $Direccion_afp = $array_datos_info_afiliado[0]->Direccion_afp;
            $Telefono_afp = $array_datos_info_afiliado[0]->Telefono_afp;
            $Ciudad_departamento_afp = $array_datos_info_afiliado[0]->Nombre_municipio_afp.'-'.$array_datos_info_afiliado[0]->Nombre_departamento_afp;
        }else{
            $Nombre_afp = '';
            $Direccion_afp = '';
            $Telefono_afp = '';
            $Ciudad_departamento_afp = '';
        }

        if(!empty($Copia_arl_correspondecia) && $Copia_arl_correspondecia == 'ARL'){
            $Nombre_arl = $array_datos_info_afiliado[0]->Entidad_arl;
            $Direccion_arl = $array_datos_info_afiliado[0]->Direccion_arl;
            $Telefono_arl = $array_datos_info_afiliado[0]->Telefono_arl;
            $Ciudad_departamento_arl = $array_datos_info_afiliado[0]->Nombre_municipio_arl.'-'.$array_datos_info_afiliado[0]->Nombre_departamento_arl;
        }else{
            $Nombre_arl = '';
            $Direccion_arl = '';
            $Telefono_arl = '';
            $Ciudad_departamento_arl = '';
        }

        
        // Captura de datos del dictamen pericial
        $array_datos_info_dictamen = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as sltp', 'sltp.Id_Evento', '=', 'side.Tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpa', 'slpa.Id_Parametro', '=', 'side.Tipo_enfermedad')
        ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'side.Cobertura', 'side.Decreto_calificacion', 
        'side.Numero_dictamen', 'side.PCL_anterior', 'side.Descripcion_nueva_calificacion', 'side.Relacion_documentos', 'side.Otros_relacion_doc', 
        'side.Descripcion_enfermedad_actual', 'side.Suma_combinada', 'side.Total_Deficiencia50', 'side.Porcentaje_pcl', 'side.Rango_pcl', 
        'side.Monto_indemnizacion', 'side.Tipo_evento', 'sltp.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro as Nombre_origen', 'side.F_evento', 
        'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slpa.Nombre_parametro as Nombre_enfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.N_radicado', 
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro','side.N_siniestro')
        ->where([['side.ID_Evento',$ID_Evento_comuni_comite], ['side.Id_Asignacion',$Id_Asignacion_comuni_comite]])->get(); 
        $N_siniestro = $array_datos_info_dictamen[0]->N_siniestro;
        $PorcentajePcl_cero = 0;

        // Captura de los nombres CIE10

        $array_diagnosticosPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10')
        ->where([['ID_Evento',$ID_Evento_comuni_comite], ['Id_Asignacion',$Id_Asignacion_comuni_comite], ['Id_proceso',$Id_Proceso_comuni_comite], ['Estado_Recalificacion', 'Activo']])->get(); 
        
        if(count($array_diagnosticosPcl) > 0){
            // Obtener el array de nombres CIE10
            $NombresCIE10 = $array_diagnosticosPcl->pluck('Nombre_CIE10')->toArray();            
            // Obtener el número de elementos en el array
            $num_elementos = count($NombresCIE10);
            // Si hay más de un elemento en el array
            if ($num_elementos > 1) {
                // Separar el último elemento del resto
                $ultimo_elemento = array_pop($NombresCIE10);
                $resto_elementos = implode(', ', $NombresCIE10);

                // Concatenar los elementos con "y"
                $CIE10Nombres_cero = $resto_elementos . ' y ' . $ultimo_elemento;
            } else {
                // Si solo hay un elemento, no es necesario cambiar nada
                $CIE10Nombres_cero = reset($NombresCIE10);
            }
        }else{
            $CIE10Nombres_cero = '';
        }
        
        // validamos la firma esta marcado para la Captura de la firma del cliente           
        if ($Firma_comuni_comite == 'Firma') {            
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

        // Captura de datos de informacion laboral

        $array_datos_info_laboral = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as slde', 'slde.Id_departamento', '=', 'sile.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'sile.Id_municipio')
        ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sile.Id_departamento', 'slde.Nombre_departamento', 
        'sile.Id_municipio', 'sldm.Nombre_municipio')->where([['ID_Evento',$ID_Evento_comuni_comite]])->limit(1)->get();

        $Nombre_empresa_noti = $array_datos_info_laboral[0]->Empresa;
        $Direccion_empresa_noti = $array_datos_info_laboral[0]->Direccion;
        $Telefono_empresa_noti = $array_datos_info_laboral[0]->Telefono_empresa;
        $Ciudad_departamento_empresa_noti = $array_datos_info_laboral[0]->Nombre_municipio.'-'.$array_datos_info_laboral[0]->Nombre_departamento;        

        if(!empty($Copia_empleador_correspondecia) && $Copia_empleador_correspondecia == 'Empleador'){
            $copiaNombre_empresa_noti = $Nombre_empresa_noti;
            $copiaDireccion_empresa_noti = $Direccion_empresa_noti;
            $copiaTelefono_empresa_noti = $Telefono_empresa_noti;
            $copiaCiudad_departamento_empresa_noti = $Ciudad_departamento_empresa_noti;
        }else{
            $copiaNombre_empresa_noti = '';
            $copiaDireccion_empresa_noti = '';
            $copiaTelefono_empresa_noti = '';
            $copiaCiudad_departamento_empresa_noti = '';
        }

        /* Extraemos los datos del footer */
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

        //Obtener los datos del formulario
        
        $data = [
            'logo_header' => $logo_header,
            'Id_cliente_ent' => $Cliente,
            'ID_evento' => $ID_Evento_comuni_comite,
            'Id_Asignacion' => $Id_Asignacion_comuni_comite,
            'Id_proceso' => $Id_Proceso_comuni_comite,
            'Radicado_comuni' => $Radicado_comuni_comite,
            'Asunto_correspondencia' => $Asunto_correspondencia,
            'Cuerpo_comunicado_correspondencia' => $Cuerpo_comunicado_correspondencia,
            'F_correspondecia' => $F_correspondecia,
            'Ciudad_correspondencia' => $Ciudad_correspondencia,
            'Nombre_afiliado_noti' => $Nombre_afiliado_noti,
            'Direccion_afiliado_noti' => $Direccion_afiliado_noti,
            'Telefono_afiliado_noti' => $Telefono_afiliado_noti,
            'Departamento_afiliado_noti' => $Departamento_afiliado_noti,
            'Ciudad_afiliado_noti' => $Ciudad_afiliado_noti,
            'T_documento_noti' => $T_documento_noti,
            'NroIden_afiliado_noti' => $NroIden_afiliado_noti,
            'Email_afiliado_noti' => $Email_afiliado_noti, 
            'PorcentajePcl_cero' => $PorcentajePcl_cero,
            'CIE10Nombres_cero' => $CIE10Nombres_cero,
            'Firma_cliente' => $Firma_cliente,
            'Anexos_correspondecia' => $Anexos_correspondecia,
            'Elaboro_correspondecia' => $Elaboro_correspondecia,
            'Nombre_empresa_noti' => $Nombre_empresa_noti,
            'Direccion_empresa_noti' => $Direccion_empresa_noti,
            'Telefono_empresa_noti' => $Telefono_empresa_noti,
            'Ciudad_departamento_empresa_noti' => $Ciudad_departamento_empresa_noti,
            'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
            'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
            'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
            'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
            'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
            'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
            'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
            'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
            'Nombre_eps' => $Nombre_eps,
            'Direccion_eps' => $Direccion_eps,
            'Telefono_eps' => $Telefono_eps,
            'Ciudad_departamento_eps' => $Ciudad_departamento_eps,
            'Nombre_afp' => $Nombre_afp,
            'Direccion_afp' => $Direccion_afp,
            'Telefono_afp' => $Telefono_afp,
            'Ciudad_departamento_afp' => $Ciudad_departamento_afp,
            'Nombre_arl' => $Nombre_arl,
            'Direccion_arl' => $Direccion_arl,
            'Telefono_arl' => $Telefono_arl,
            'Ciudad_departamento_arl' => $Ciudad_departamento_arl,
            'footer' => $footer,
            'N_siniestro' => $N_siniestro,
            // 'footer_dato_1' => $footer_dato_1,
            // 'footer_dato_2' => $footer_dato_2,
            // 'footer_dato_3' => $footer_dato_3,
            // 'footer_dato_4' => $footer_dato_4,
            // 'footer_dato_5' => $footer_dato_5,
        ];

        // Crear una instancia de Dompdf
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Arl/PCL/notificacion_pcl_cero', $data);        
        $nombre_pdf = 'PCL_OFICIO_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';    
        //Obtener el contenido del PDF
        $output = $pdf->output();
        //Guardar el PDF en un archivo
        file_put_contents(public_path("Documentos_Eventos/{$ID_Evento_comuni_comite}/{$nombre_pdf}"), $output);
        $actualizar_nombre_documento = [
            'Nombre_documento' => $nombre_pdf
        ];
        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_Comunicado)
        ->update($actualizar_nombre_documento);
        /* Inserción del registro de que fue descargado */
        // Extraemos el id del servicio asociado
        $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->select('siae.Id_servicio')
        ->where([
            ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
            ['siae.ID_evento', $ID_Evento_comuni_comite],
            ['siae.Id_proceso', $Id_Proceso_comuni_comite],
        ])->get();

        $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        ->select('sice.F_comunicado')
        ->where([
            ['sice.N_radicado', $Radicado_comuni_comite]
        ])
        ->get();

        $F_elaboracion_correspondencia = $dato_f_elaboracion_correspondencia[0]->F_comunicado;

        // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
        $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
        ->select('Nombre_documento')
        ->where([
            ['Nombre_documento', $nombre_pdf],
        ])->get();
        
        if(count($verficar_documento) == 0){
            $info_descarga_documento = [
                'Id_Asignacion' => $Id_Asignacion_comuni_comite,
                'Id_proceso' => $Id_Proceso_comuni_comite,
                'Id_servicio' => $Id_servicio,
                'ID_evento' => $ID_Evento_comuni_comite,
                'Nombre_documento' => $nombre_pdf,
                'N_radicado_documento' => $Radicado_comuni_comite,
                'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                'F_descarga_documento' => $date,
                'Nombre_usuario' => $nombre_usuario,
            ];
            
            sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        }

        return $pdf->download($nombre_pdf);
    }

}
