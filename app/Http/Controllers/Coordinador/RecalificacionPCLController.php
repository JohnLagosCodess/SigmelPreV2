<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\sigmel_informacion_pericial_eventos;
use App\Models\sigmel_informacion_decreto_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\cndatos_eventos;
use App\Models\sigmel_campimetria_visuales;
use App\Models\sigmel_info_campimetria_ojo_der_eventos;
use App\Models\sigmel_info_campimetria_ojo_derre_eventos;
use App\Models\sigmel_info_campimetria_ojo_izq_eventos;
use App\Models\sigmel_info_campimetria_ojo_izqre_eventos;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_agudeza_auditiva_eventos;
use App\Models\sigmel_informacion_agudeza_visual_eventos;
use App\Models\sigmel_informacion_agudeza_visualre_eventos;
use App\Models\sigmel_informacion_deficiencias_alteraciones_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_informacion_examenes_interconsultas_eventos;
use App\Models\sigmel_informacion_laboralmente_activo_eventos;
use App\Models\sigmel_informacion_libro2_libro3_eventos;
use App\Models\sigmel_informacion_rol_ocupacional_eventos;
use App\Models\sigmel_lista_califi_decretos;
use App\Models\sigmel_lista_cie_diagnosticos;
use App\Models\sigmel_lista_clases_decretos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_lista_tablas_1507_decretos;
use App\Models\sigmel_lista_tipo_eventos;

class RecalificacionPCLController extends Controller
{
    public function mostrarVistaRecalificacionPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        $Id_evento_recali=$request->Id_evento_pcl;
        $Id_asignacion_recali = $request->Id_asignacion_pcl;
        $Id_proceso_recali = 2;
        $Id_servicioCalifi= 6;
        $Id_servicioRecalifi = 7;

        // validar id evento de la calificacion tecnica
        $validar_evento_CalifiTecnica = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->select('ID_Evento','Id_Asignacion', 'Id_proceso', 'Id_servicio')
        ->where([['ID_Evento',$Id_evento_recali],['Id_servicio',$Id_servicioCalifi], ['Id_proceso',$Id_proceso_recali]])->get();

        // validar id evento y asignacion de la recalificacion para saber si hay id de asignacion menores que no se han recalificado
        $validar_evento_Recali = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->select('ID_Evento','Id_Asignacion', 'Id_proceso', 'Id_servicio')
        ->where([
            ['ID_Evento',$Id_evento_recali],['Id_servicio',$Id_servicioRecalifi], 
            ['Id_proceso',$Id_proceso_recali], ['Id_Asignacion', '<', $Id_asignacion_recali]
        ])->get();

        if(!empty($validar_evento_Recali[0]->Id_Asignacion)){
            $resultadosIdAsignacion = [];
            foreach ($validar_evento_Recali as $registro) {
                $resultadosIdAsignacion[] = [                
                    'Id_Asignacion' => $registro->Id_Asignacion,                
                ];
            }        
            $array_datos_idasignacion_decretos = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
            ->select('Id_Asignacion', 'Estado_decreto')->whereIn('Id_Asignacion', $resultadosIdAsignacion)
            ->orderBy('Id_Asignacion', 'desc')
            //->limit(1)
            ->get();
        }


        // $evento_AsignacionMin = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        // ->select('ID_Evento','Id_Asignacion', 'Id_proceso', 'Id_servicio')
        // ->where([['ID_Evento',$Id_evento_recali],['Id_servicio',$Id_servicioRecalifi], ['Id_proceso',$Id_proceso_recali]])
        // //->get();
        // ->min('Id_Asignacion');

        // Obtener el minimo y el maximo id de asignacion y estado del decreto

        $eventoAsigancionMin_Recalifi = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
        ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig',
        'siae.Id_proceso' , 'siae.Id_servicio', 'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_servicio', $Id_servicioRecalifi], ['siae.Id_proceso', $Id_proceso_recali]])
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
            ['siae.Id_servicio', $Id_servicioRecalifi], 
            ['siae.Id_proceso', $Id_proceso_recali],
            ['side.Estado_decreto', 'Cerrado']
        ])
        ->groupBy('side.ID_Evento', 'side.Id_Asignacion', 'siae.Id_Asignacion', 'siae.Id_proceso', 'siae.Id_servicio', 
        'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->orderBy('side.Id_Asignacion', 'desc')
        ->limit(1)
        //->get();
        ->max('siae.Id_Asignacion');

        $eventoAsigancion_Recalifi_estadoDecreto = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
        ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig',
        'siae.Id_proceso' , 'siae.Id_servicio', 'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->where([
            ['side.ID_Evento',$Id_evento_recali],
            ['siae.Id_servicio', $Id_servicioRecalifi], 
            ['siae.Id_proceso', $Id_proceso_recali]
        ])
        ->groupBy('side.ID_Evento', 'side.Id_Asignacion', 'siae.Id_Asignacion', 'siae.Id_proceso', 'siae.Id_servicio', 
        'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->orderBy('side.Id_Asignacion', 'desc')
        ->limit(1)
        ->get();
        //->max('siae.Id_Asignacion');        

        // Obtener el motivo solicitud para la primera recalificacion

        $array_datos_motivo_solicitud = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pericial_eventos as sipe')
        ->leftJoin('sigmel_gestiones.sigmel_lista_motivo_solicitudes as slms', 'slms.Id_Solicitud', '=', 'sipe.Id_motivo_solicitud')
        ->select('sipe.Id_Pericial', 'sipe.ID_evento', 'sipe.Id_motivo_solicitud', 'slms.Nombre_solicitud', 'sipe.Tipo_vinculacion', 
        'sipe.Regimen_salud', 'sipe.Id_solicitante', 'sipe.Id_nombre_solicitante', 'sipe.Fuente_informacion', 'sipe.Nombre_usuario', 
        'sipe.F_registro')
        ->where([['sipe.ID_evento',$Id_evento_recali]])->get(); 
        
        // Validar estado del decreto

        $validar_estado_decreto = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
        ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
        'side.Porcentaje_pcl', 'side.Estado_decreto')
        ->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_servicio', $Id_servicioCalifi]])->get(); 
        
        // Validar PCl anterior de la Recalficacion
        $eventoAsigancionMax_RecaRecali = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.ID_evento', '=', 'side.ID_evento')
        ->select('side.Id_Asignacion')
        ->where([['side.Id_Asignacion', '<', DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos  as siae')->select('siae.Id_Asignacion')->max('siae.Id_Asignacion')], 
                ['side.ID_Evento', $Id_evento_recali], 
                ['side.Id_proceso', $Id_proceso_recali], 
                ['siae.Id_servicio', $Id_servicioRecalifi]
        ])        
        ->max('side.Id_Asignacion');
        
        if(!empty($eventoAsigancionMax_RecaRecali) && $eventoAsigancionMax_RecaRecali < $Id_asignacion_recali){            
            // echo 'if';       
            // echo '<hr>';
            // echo $eventoAsigancionMax_RecaRecali;
            $eventoAsigancion_RecalifiPCL = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
            ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
            'side.Porcentaje_pcl', 'side.Estado_decreto')
            ->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_servicio', $Id_servicioRecalifi], 
            ['side.Id_Asignacion', $eventoAsigancionMax_RecaRecali]])->get();  
        }elseif(!empty($eventoAsigancionMax_RecaRecali) && $eventoAsigancionMax_RecaRecali > $Id_asignacion_recali){            
            // echo 'elseif';       
            // echo '<hr>';
            // echo $eventoAsigancionMax_RecaRecali;
            $eventoAsigancion_RecalifiPCL = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
            ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
            'side.PCL_anterior', 'side.Estado_decreto')
            ->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_servicio', $Id_servicioRecalifi], 
            ['side.Id_Asignacion', $Id_asignacion_recali]])->get();         
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
         
        // echo '<pre>';
        //     echo print_r($eventoAsigancion_RecalifiPCL);
        //     echo '<hr>';
        // echo '</pre>';

        // traer todos los datos del evento segun el id de asignacion
        $array_datos_RecalificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($Id_asignacion_recali));

        // Condicional IF para Recalificacion sobre Recalificacion y Else para Recalifacion sobre Calificacion tecnica

        if ($eventoAsigancionMin_Recalifi != $Id_asignacion_recali && !empty($eventoAsigancionMin_Recalifi)) { 
            if (count($validar_evento_Recali) != count($array_datos_idasignacion_decretos)) {
                return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'array_datos_motivo_solicitud', 'validar_estado_decreto', 'validar_evento_CalifiTecnica', 'array_datos_idasignacion_decretos', 'validar_evento_Recali'));                   
            }else {
                if(empty($eventoAsigancion_Recalifi)){
                    return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'array_datos_motivo_solicitud', 'eventoAsigancion_Recalifi', 'eventoAsigancion_Recalifi_estadoDecreto', 'validar_estado_decreto', 'eventoAsigancion_RecalifiPCL', 'validar_evento_CalifiTecnica'));
                } 
                elseif(!empty($eventoAsigancion_Recalifi)){
                    if ($eventoAsigancion_Recalifi >= 1) {                                    
                        // $array_datos_motivo_solicitud = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pericial_eventos as sipe')
                        // ->leftJoin('sigmel_gestiones.sigmel_lista_motivo_solicitudes as slms', 'slms.Id_Solicitud', '=', 'sipe.Id_motivo_solicitud')
                        // ->select('sipe.Id_Pericial', 'sipe.ID_evento', 'sipe.Id_motivo_solicitud', 'slms.Nombre_solicitud', 'sipe.Tipo_vinculacion', 
                        // 'sipe.Regimen_salud', 'sipe.Id_solicitante', 'sipe.Id_nombre_solicitante', 'sipe.Fuente_informacion', 'sipe.Nombre_usuario', 
                        // 'sipe.F_registro')
                        // ->where([['sipe.ID_evento',$Id_evento_recali]])->get(); 
                
                        // $validar_estado_decreto = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        // ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
                        // ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
                        // 'side.Porcentaje_pcl', 'side.Estado_decreto')
                        // ->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_servicio', $Id_servicioRecalifi]])->get();
                        
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
                            ['Estado_Recalificacion', 'Activo']
                        ])
                        ->get();
                
                        $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                        'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
                        ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion', $eventoAsigancion_Recalifi], ['side.Estado_Recalificacion', '=', 'Activo']])->get();
            
                        $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                        ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                        'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                        'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                        ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion', $eventoAsigancion_Recalifi], ['sidae.Estado_Recalificacion', '=', 'Activo']])->get();
            
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
                        ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                        'side.F_evento', 'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia')
                        ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion', $eventoAsigancion_Recalifi]])->get();
                        
                    } 
                    /* else {
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
                            ['Estado', 'Activo']
                        ])
                        ->get();
            
                        $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                        'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
                        ->where([['side.ID_evento',$Id_evento_recali], ['side.Estado', '=', 'Activo']])->get();
            
                        $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                        ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                        'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                        'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                        ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Estado', '=', 'Activo']])->get();
            
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
                        ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                        'side.F_evento', 'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia')
                        ->where([['side.ID_evento',$Id_evento_recali]])->get();
                    
                    } */
    
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
                        ['Estado_Recalificacion', 'Activo']
                    ])
                    ->get();
            
                    $array_datos_diagnostico_motcalifire =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                    ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                    'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
                    ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali], ['side.Estado_Recalificacion', '=', 'Activo']])->get(); 
            
                    $array_datos_deficiencias_alteracionesre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                    ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                    'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                    'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                    ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion',$Id_asignacion_recali], ['sidae.Estado_Recalificacion', '=', 'Activo']])->get(); 
              
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
            
                    // if($validar_estado_decreto[0]->Id_Asignacion_decreto == $Id_asignacion_recali){
                    // }
                    // elseif (count($validar_estado_decreto) > 0) {
            
                    //     if(count($array_datos_RecalificacionPcl) > 0){
                    //         $Id_servicio_balt = 6;
                    //     }
                
                    //     $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                    //     $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                    //     $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                    //     $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                    //     $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                    //     $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                    //     $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                        
                    // }       
                    
                    if(!empty($array_datos_RecalificacionPcl[0]->Id_Asignacion)){
                        $Id_servicio_balt = $array_datos_RecalificacionPcl[0]->Id_Servicio;
                    }
            
                    $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                    $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                    $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                    $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                    $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                    $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                    $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));    
                    
                    
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
                        
                    }elseif(empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
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
                        
                    }elseif(empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)){
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
                        
                    }elseif(!empty($array_datos_deficiencicas50_3) && empty($array_datos_deficiencicas50_1)) {
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
                        
                    }elseif(!empty($array_datos_deficiencicas50_4) && empty($array_datos_deficiencicas50)){
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
                        
                        
                    }elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
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
                        
                    }elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)) {
                        
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
                        
                    }else{            
                        $deficiencias = 0;
                        $TotalDeficiencia50 =0;
                    }
            
                    $array_dictamen_pericialre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
                    ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                    'side.F_evento', 'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                    'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                    'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.Estado_decreto')
                    ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali]])->get();        
                    
                    return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'array_datos_motivo_solicitud', 'eventoAsigancion_Recalifi', 'eventoAsigancion_Recalifi_estadoDecreto', 'validar_estado_decreto', 'eventoAsigancion_RecalifiPCL', 'datos_decreto', 'datos_decretore', 'validar_evento_CalifiTecnica', 'numero_consecutivo', 'array_info_decreto_evento', 'array_info_decreto_evento_re', 'array_datos_relacion_documentos', 'motivo_solicitud_actual', 'datos_apoderado_actual', 'array_datos_examenes_interconsultas', 'array_datos_examenes_interconsultasre', 'array_datos_diagnostico_motcalifi', 'array_datos_diagnostico_motcalifire', 'array_datos_deficiencias_alteraciones', 'array_datos_deficiencias_alteracionesre', 'array_agudeza_Auditiva', 'array_agudeza_Auditivare', 'hay_agudeza_visual', 'hay_agudeza_visualre', 'array_laboralmente_Activo', 'array_laboralmente_Activore', 'array_rol_ocupacional', 'array_rol_ocupacionalre', 'array_libros_2_3', 'array_libros_2_3re', 'deficiencias', 'TotalDeficiencia50', 'array_dictamen_pericial', 'array_dictamen_pericialre'));
                    
                }
            }
        } elseif($eventoAsigancionMin_Recalifi == $Id_asignacion_recali || empty($eventoAsigancionMin_Recalifi)) {   
            
            if (!empty($validar_evento_Recali[0]->Id_Asignacion)) {
                return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'array_datos_motivo_solicitud', 'validar_estado_decreto', 'validar_evento_CalifiTecnica', 'array_datos_idasignacion_decretos', 'validar_evento_Recali'));                
            } else {
                if (!empty($validar_evento_CalifiTecnica[0]->Id_servicio)) {
                    if (count($validar_evento_CalifiTecnica) == 1) {                
                        // $array_datos_motivo_solicitud = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pericial_eventos as sipe')
                        // ->leftJoin('sigmel_gestiones.sigmel_lista_motivo_solicitudes as slms', 'slms.Id_Solicitud', '=', 'sipe.Id_motivo_solicitud')
                        // ->select('sipe.Id_Pericial', 'sipe.ID_evento', 'sipe.Id_motivo_solicitud', 'slms.Nombre_solicitud', 'sipe.Tipo_vinculacion', 
                        // 'sipe.Regimen_salud', 'sipe.Id_solicitante', 'sipe.Id_nombre_solicitante', 'sipe.Fuente_informacion', 'sipe.Nombre_usuario', 
                        // 'sipe.F_registro')
                        // ->where([['sipe.ID_evento',$Id_evento_recali]])->get(); 
                
                        // $validar_estado_decreto = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        // ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
                        // ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
                        // 'side.Porcentaje_pcl', 'side.Estado_decreto')
                        // ->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_servicio', $Id_servicioCalifi]])->get();  
                        
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
                                ['Estado', 'Activo']
                            ])
                            ->get();
                
                            $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                            ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                            'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
                            ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto], ['side.Estado', '=', 'Activo']])->get();
                
                            $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                            ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                            'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                            'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                            ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto], ['sidae.Estado', '=', 'Activo']])->get();
                
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
                            ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                            'side.F_evento', 'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
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
                                ['Estado', 'Activo']
                            ])
                            ->get();
                
                            $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                            ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                            'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
                            ->where([['side.ID_evento',$Id_evento_recali], ['side.Estado', '=', 'Activo']])->get();
                
                            $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                            ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                            ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                            'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                            'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                            ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Estado', '=', 'Activo']])->get();
                
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
                            ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                            'side.F_evento', 'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
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
                            ['Estado_Recalificacion', 'Activo']
                        ])
                        ->get();
                
                        $array_datos_diagnostico_motcalifire =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
                        'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
                        ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali], ['side.Estado_Recalificacion', '=', 'Activo']])->get(); 
                
                        $array_datos_deficiencias_alteracionesre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
                        ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
                        'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
                        'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
                        ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion',$Id_asignacion_recali], ['sidae.Estado_Recalificacion', '=', 'Activo']])->get(); 
                  
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
                
                        // if($validar_estado_decreto[0]->Id_Asignacion_decreto == $Id_asignacion_recali){
                        // }
                        // elseif (count($validar_estado_decreto) > 0) {
                
                        //     if(count($array_datos_RecalificacionPcl) > 0){
                        //         $Id_servicio_balt = 6;
                        //     }
                    
                        //     $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                        //     $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                        //     $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                        //     $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                        //     $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                        //     $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                        //     $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?,?,?)', array($Id_evento_recali,$validar_estado_decreto[0]->Id_Asignacion_decreto,$Id_servicio_balt));
                            
                        // }       
                        
                        if(!empty($array_datos_RecalificacionPcl[0]->Id_Asignacion)){
                            $Id_servicio_balt = $array_datos_RecalificacionPcl[0]->Id_Servicio;
                        }                    
    
                        $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                        $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                        $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                        $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                        $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                        $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));
                        $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?,?,?)', array($Id_evento_recali,$Id_asignacion_recali,$Id_servicio_balt));    
                        
                        
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
                            
                        }elseif(empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
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
                            
                        }elseif(empty($array_datos_deficiencicas50)  && empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)){
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
                            
                        }elseif(!empty($array_datos_deficiencicas50_3) && empty($array_datos_deficiencicas50_1)) {
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
                            
                        }elseif(!empty($array_datos_deficiencicas50_4) && empty($array_datos_deficiencicas50)){
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
                            
                            
                        }elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && empty($array_datos_deficiencicas50_2)){
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
                            
                        }elseif(!empty($array_datos_deficiencicas50)  && !empty($array_datos_deficiencicas50_1) && !empty($array_datos_deficiencicas50_2)) {
                            
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
                            
                        }else{            
                            $deficiencias = 0;
                            $TotalDeficiencia50 =0;
                        }
                
                        $array_dictamen_pericialre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
                        ->select('side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
                        'side.F_evento', 'side.F_estructuracion', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
                        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
                        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.Estado_decreto')
                        ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali]])->get();        
                        
                        return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'array_datos_motivo_solicitud', 'validar_estado_decreto', 'datos_decreto', 'datos_decretore', 'validar_evento_CalifiTecnica', 'numero_consecutivo', 'array_info_decreto_evento', 'array_info_decreto_evento_re', 'array_datos_relacion_documentos', 'motivo_solicitud_actual', 'datos_apoderado_actual', 'array_datos_examenes_interconsultas', 'array_datos_examenes_interconsultasre', 'array_datos_diagnostico_motcalifi', 'array_datos_diagnostico_motcalifire', 'array_datos_deficiencias_alteraciones', 'array_datos_deficiencias_alteracionesre', 'array_agudeza_Auditiva', 'array_agudeza_Auditivare', 'hay_agudeza_visual', 'hay_agudeza_visualre', 'array_laboralmente_Activo', 'array_laboralmente_Activore', 'array_rol_ocupacional', 'array_rol_ocupacionalre', 'array_libros_2_3', 'array_libros_2_3re', 'deficiencias', 'TotalDeficiencia50', 'array_dictamen_pericial', 'array_dictamen_pericialre'));
    
                    }
                }                
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
                    ->select('ID_evento', 'Id_Asignacion', 'Id_proceso', 'CIE10', 'Nombre_CIE10', 'Origen_CIE10', 'Deficiencia_motivo_califi_condiciones',
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

                // Deficiencias del sistema
                //print_r($DataDeficiencias);
                if (!empty($DataDeficiencias)) {
                    $registrosDataDeficiencias = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
                    ->select('ID_evento', 'Id_Asignacion', 'Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU', 'CAT', 
                    'Clase_Final', 'Dx_Principal', 'MSD', 'Tabla1999', 'Titulo_tabla1999', 'Deficiencia', 'Estado', 'Estado_Recalificacion')
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
                    'Clase_Final', 'Dx_Principal', 'MSD', 'Tabla1999', 'Titulo_tabla1999', 'Deficiencia', 'Estado', 'Estado_Recalificacion')
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
                    'Clase_Final', 'Dx_Principal', 'MSD', 'Tabla1999', 'Titulo_tabla1999', 'Deficiencia', 'Estado', 'Estado_Recalificacion')
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
        
                $mensajes = array(
                    "parametro" => 'update_decreto_parte',
                    "mensaje2" => 'Actualizado satisfactoriamente.'
                ); 
    
                return json_decode(json_encode($mensajes, true));
            }
        } 

    }

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
        'CIE10','Nombre_CIE10','Origen_CIE10','Deficiencia_motivo_califi_condiciones','Estado',
        'Nombre_usuario','F_registro'];

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
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU',	'CAT', 'Clase_Final', 
        'Dx_Principal', 'MSD', 'Deficiencia', 'Estado', 'Nombre_usuario','F_registro'];
        
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
            "parametro" => 'inserto_informacion_deficiencias',
            "mensaje" => 'Deficiencia guardada satisfactoriamente.'
        );

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
        
        
        if ($request -> bandera_LaboralActivo_guardar_actualizar == 'Guardar') {
            
            /* $Ultimo_Id_Asignacion = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_Evento_decreto]])
            ->max('Id_Asignacion');
    
            $Estado_Recalificacion_laboral = [
                'Estado_Recalificacion' => 'Inactivo'
            ];
            
            sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_Evento_decreto], ['Id_Asignacion', $Ultimo_Id_Asignacion]])
            ->update($Estado_Recalificacion_laboral); */

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
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'Deficiencia', 'Estado', 'Nombre_usuario','F_registro'];
        
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
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Tabla1999', 'Titulo_tabla1999', 'Deficiencia', 'Estado', 'Nombre_usuario','F_registro'];
        
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
        $porcentaje_pcl = $request->porcentaje_pcl;  
        $rango_pcl = $request->rango_pcl;        
        $tipo_evento = $request->tipo_evento;        
        $tipo_origen = $request->tipo_origen;  
        $f_evento_pericial = $request->f_evento_pericial;
        $f_estructura_pericial = $request->f_estructura_pericial;      
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
        if($Decreto_pericial == 3){
            $datos_dictamenPericial =[
                'Suma_combinada' => $suma_combinada,
                'Total_Deficiencia50' => $Total_Deficiencia50,
                'Porcentaje_pcl' => $total_porcentajePcl,
                'Rango_pcl' => $rango_pcl,
                'Tipo_evento' => $tipo_evento,
                'Origen' => $tipo_origen,
                'F_evento' => $f_evento_pericial,
                'F_estructuracion' => $f_estructura_pericial,
                'Sustentacion_F_estructuracion' => $sustenta_fecha,
                'Detalle_calificacion' => $detalle_califi,
                'Enfermedad_catastrofica' => $enfermedad_catastrofica,
                'Enfermedad_congenita' => $enfermedad_congenita,
                'Tipo_enfermedad' => $tipo_enfermedad,
                'Requiere_tercera_persona' => $requiere_persona,
                'Requiere_tercera_persona_decisiones' => $requiere_decisiones_persona,
                'Requiere_dispositivo_apoyo' => $requiere_dispositivo_apoyo,
                'Justificacion_dependencia' => $justi_dependencia,
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
                'Tipo_evento' => $tipo_evento,
                'Origen' => $tipo_origen,
                'F_evento' => $f_evento_pericial,
                'F_estructuracion' => $f_estructura_pericial,
                'Sustentacion_F_estructuracion' => $sustenta_fecha,
                'Detalle_calificacion' => $detalle_califi,
                'Enfermedad_catastrofica' => $enfermedad_catastrofica,
                'Enfermedad_congenita' => $enfermedad_congenita,
                'Tipo_enfermedad' => $tipo_enfermedad,
                'Requiere_tercera_persona' => $requiere_persona,
                'Requiere_tercera_persona_decisiones' => $requiere_decisiones_persona,
                'Requiere_dispositivo_apoyo' => $requiere_dispositivo_apoyo,
                'Justificacion_dependencia' => $justi_dependencia,
                'Estado_decreto' => 'Cerrado',
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_dictamenPericial);  
        }

        $mensajes = array(
            "parametro" => 'insertar_dictamen_pericial',
            "mensaje" => 'Concepto final del dictamen pericial guardado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }

}
