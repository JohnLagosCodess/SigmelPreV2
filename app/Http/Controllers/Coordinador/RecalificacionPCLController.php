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
        $Id_servicio = 6;
        $array_datos_RecalificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($Id_asignacion_recali));

        $array_datos_motivo_solicitud = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pericial_eventos as sipe')
        ->leftJoin('sigmel_gestiones.sigmel_lista_motivo_solicitudes as slms', 'slms.Id_Solicitud', '=', 'sipe.Id_motivo_solicitud')
        ->select('sipe.Id_Pericial', 'sipe.ID_evento', 'sipe.Id_motivo_solicitud', 'slms.Nombre_solicitud', 'sipe.Tipo_vinculacion', 
        'sipe.Regimen_salud', 'sipe.Id_solicitante', 'sipe.Id_nombre_solicitante', 'sipe.Fuente_informacion', 'sipe.Nombre_usuario', 
        'sipe.F_registro')
        ->where([['sipe.ID_evento',$Id_evento_recali]])->get(); 

        $validar_estado_decreto = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_Asignacion', '=', 'side.Id_Asignacion')
        ->select('side.ID_Evento', 'side.Id_Asignacion as Id_Asignacion_decreto', 'siae.Id_Asignacion as Id_Asignacion_asig', 'siae.Id_servicio',
        'side.Estado_decreto')->where([['side.ID_Evento',$Id_evento_recali], ['siae.Id_servicio', $Id_servicio]])->get();  
        
        if (count($validar_estado_decreto) > 0) {
            $datos_decreto =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
            ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
            ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
            'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
            ->where([['side.ID_Evento',$Id_evento_recali], ['side.Id_Asignacion',$validar_estado_decreto[0]->Id_Asignacion_decreto]])->get(); 
        } else {
            $datos_decreto =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
            ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
            ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
            'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
            ->where([['side.ID_Evento',$Id_evento_recali]])->get(); 
        }
        

        $validar_evento_asignacion = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->select('ID_Evento','Id_Asignacion', 'Id_servicio')->where([['ID_Evento',$Id_evento_recali],['Id_servicio',$Id_servicio]])->get();        
        
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

        if (count($validar_estado_decreto) > 0) {
            $array_info_decreto_evento = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
            ->where([
                ['ID_Evento', $Id_evento_recali],['Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto]
            ])
            ->get();
        } else {
            $array_info_decreto_evento = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
            ->where([
                ['ID_Evento', $Id_evento_recali]
            ])
            ->get();
        }
           
        $array_info_decreto_evento_re = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
        ->where([
            ['ID_Evento', $Id_evento_recali],['Id_Asignacion', $Id_asignacion_recali]
        ])
        ->get();

        if (count($array_info_decreto_evento_re) > 0) {

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
        }elseif(count($array_info_decreto_evento) > 0){
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

        if (count($validar_estado_decreto) > 0) {            
            $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_evento_recali],
                ['Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto],
                ['Estado_Recalificacion', 'Activo']
            ])
            ->get();
        } else {
            $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_evento_recali],
                ['Estado_Recalificacion', 'Activo']
            ])
            ->get();
        }        
        
        $array_datos_examenes_interconsultasre = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_recali],
            ['Id_Asignacion',$Id_asignacion_recali],
            ['Estado_Recalificacion', 'Activo']
        ])
        ->get();

        if (count($validar_estado_decreto) > 0) {            
            $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
            ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
            'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
            ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto], ['side.Estado_Recalificacion', '=', 'Activo']])->get(); 

        } else {

            $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
            ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
            'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
            ->where([['side.ID_evento',$Id_evento_recali], ['side.Estado_Recalificacion', '=', 'Activo']])->get();            
        }          

        $array_datos_diagnostico_motcalifire =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.Id_Asignacion', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['side.ID_evento',$Id_evento_recali], ['side.Id_Asignacion',$Id_asignacion_recali], ['side.Estado_Recalificacion', '=', 'Activo']])->get(); 

        if (count($validar_estado_decreto) > 0) {            
            $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
            ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
            ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
            'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
            'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
            ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion', $validar_estado_decreto[0]->Id_Asignacion_decreto], ['sidae.Estado_Recalificacion', '=', 'Activo']])->get(); 
        } else {

            $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
            ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
            ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
            'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
            'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
            ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Estado_Recalificacion', '=', 'Activo']])->get();          
        }
        
        $array_datos_deficiencias_alteracionesre =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
        ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
        'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
        'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
        ->where([['sidae.ID_evento',$Id_evento_recali], ['sidae.Id_Asignacion',$Id_asignacion_recali], ['sidae.Estado_Recalificacion', '=', 'Activo']])->get(); 

        if (count($validar_estado_decreto) > 0) {            
            $array_agudeza_Auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_evento_recali],
                ['Id_Asignacion',$validar_estado_decreto[0]->Id_Asignacion_decreto],
                ['Estado_Recalificacion', 'Activo']
            ])
            ->get();
        } else {
            $array_agudeza_Auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento',$Id_evento_recali],
                ['Estado_Recalificacion', 'Activo']
            ])
            ->get();          
        }

        $array_agudeza_Auditivare = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_recali],
            ['Id_Asignacion',$Id_asignacion_recali],
            ['Estado_Recalificacion', 'Activo']
        ])
        ->get();

        if (count($validar_estado_decreto) > 0) {            
            $hay_agudeza_visual = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
            ->where([['ID_evento_re', $Id_evento_recali], ['Id_Asignacion_re',$validar_estado_decreto[0]->Id_Asignacion_decreto], ['Estado', '=', 'Activo']])->get(); 
        } else {
            $hay_agudeza_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_evento_recali]])->get();           
        }

        $hay_agudeza_visualre = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
        ->where([['ID_evento_re', $Id_evento_recali], ['Id_Asignacion_re', $Id_asignacion_recali], ['Estado', 'Activo']])->get(); 
        
        return view('coordinador.recalificacionPCL', compact('user','array_datos_RecalificacionPcl', 'array_datos_motivo_solicitud', 'validar_estado_decreto', 'datos_decreto', 'validar_evento_asignacion', 'numero_consecutivo', 'array_info_decreto_evento', 'array_info_decreto_evento_re', 'array_datos_relacion_documentos', 'motivo_solicitud_actual', 'datos_apoderado_actual', 'array_datos_examenes_interconsultas', 'array_datos_examenes_interconsultasre', 'array_datos_diagnostico_motcalifi', 'array_datos_diagnostico_motcalifire', 'array_datos_deficiencias_alteraciones', 'array_datos_deficiencias_alteracionesre', 'array_agudeza_Auditiva', 'array_agudeza_Auditivare', 'hay_agudeza_visual', 'hay_agudeza_visualre'));

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
            ->select('Id_Cie_diagnostico', 'CIE10')
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
                    'Relacion_documentos' => $total_relacion_documentos,
                    'Otros_relacion_doc' => $descripcion_otros,
                    'Descripcion_enfermedad_actual' => $descripcion_enfermedad,
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];
    
                $dato_info_pericial_eventos = [
                    'Id_motivo_solicitud' => $motivo_solicitud,
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')->insert($datos_info_decreto_eventos);
                sleep(2);
                sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_pericial_eventos);
                sleep(2);
                $array_datos_agudeza_visual_calif = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$id_Evento_decreto ]])->get();
                
                if(count($array_datos_agudeza_visual_calif) > 0){
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
                        'Id_Asignacion_re' => $Id_Asignacion_re,
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
                        'Nombre_usuario' => $Nombre_usuario,
                        'F_registro' => $F_registro,
                    ];

                    sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')->insert($array_datos_agudeza_visual_Reca);

                    $array_datos_ojo_derecho = sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')
                    ->select('Id_agudeza', 'InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5',
                    'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10', 'Nombre_usuario',
                    'F_registro')->where([['Id_agudeza',$Id_agudeza_vis]])->get();

                    foreach ($array_datos_ojo_derecho as $registro) {                            
                            sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')
                            ->insert($registro->toArray());
                    }

                    $array_datos_ojo_izquierdo = sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')
                    ->select('Id_agudeza', 'InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5',
                    'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10', 'Nombre_usuario',
                    'F_registro')->where([['Id_agudeza',$Id_agudeza_vis]])->get();

                    foreach ($array_datos_ojo_izquierdo as $registro) {                            
                        sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')
                        ->insert($registro->toArray());
                    }
                }                    

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

        $total_registros_examen = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_examen_eliminada',
            'total_registros' => $total_registros_examen,
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

        $total_registros_diagnostico = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_diagnostico_eliminada',
            'total_registros' => $total_registros_diagnostico,
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

        $total_registros_diagnostico = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_deficiencia_alteracion_eliminada',
            'total_registros' => $total_registros_diagnostico,
            "mensaje" => 'Deficiencia por alteraciones eliminado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));        
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

        $total_registros_agudeza_auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado_Recalificacion', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_agudeza_auditiva_eliminada',
            'total_registros' => $total_registros_agudeza_auditiva,
            "mensaje" => 'Agudeza auditiva eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
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
        $id_agudeza = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')->select('Id_agudeza')->latest('Id_agudeza')->first();

        // Envío de la información de la campimetría para ojo izquierdo 
        $grilla_ojo_izq = $request->grilla_ojo_izq;
        foreach ($grilla_ojo_izq as $key => $insertar_info_grid_ojo_izq) {
            $insertar_info_grid_ojo_izq = array("Id_agudeza" => $id_agudeza['Id_agudeza']) + $insertar_info_grid_ojo_izq;
            sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_izq);
        }

        // Envío de la información de la campimetría para ojo derecho 
        $grilla_ojo_der = $request->grilla_ojo_der;
        foreach ($grilla_ojo_der as $key => $insertar_info_grid_ojo_der) {
            $insertar_info_grid_ojo_der = array("Id_agudeza" => $id_agudeza['Id_agudeza']) + $insertar_info_grid_ojo_der;
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
            'Nombre_usuario' => $usuario,
            'F_registro' => $date,
        ];

        sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_agudeza_re', '=', $id_agudeza],
            ['ID_evento_re', '=', $id_evento]
        ])->update($datos_actualizar_agudeza_visual);

        /* Borrado de la información de la campimetría para ojo izquierdo  */
        sigmel_info_campimetria_ojo_izqre_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $id_agudeza)->delete();

        /* Borrado de la información de la campimetría para ojo derecho */
        sigmel_info_campimetria_ojo_derre_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $id_agudeza)->delete();

        $mensajes = array(
            "parametro" => 'borro',
            "mensaje" => 'Información de Agudeza visual eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    /* public function actualizarDxPrincipalAgudezaVisual(Request $request){
        
        $dx_principal_visual = $request->dx_principal_visual;
        $Id_evento = $request->Id_evento;
        $banderaDxPrincipal_visual = $request->banderaDxPrincipal_visual;

        if ($banderaDxPrincipal_visual == 'SiDxPrincipal') {
            $fila_actulizar = [
                'Dx_Principal' => 'Si'
            ];
    
            sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento]
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_visual_agregado',
                "mensaje" => 'Dx Principal Agudeza visual agreagado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        } elseif($banderaDxPrincipal_visual == 'NoDxPrincipal'){
            $fila_actulizar = [
                'Dx_Principal' => 'No'
            ];
    
            sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento]
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_visual_agregado',
                "mensaje" => 'Dx Principal Agudeza visual eliminado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    } */


}
