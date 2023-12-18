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
use App\Models\sigmel_informacion_controversia_juntas_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;



class ControversiaJuntasController extends Controller
{
    public function mostrarVistaPronunciamientoJuntas(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $date=date("Y-m-d");
        $Id_evento_juntas=$request->Id_evento_juntas;
        $Id_asignacion_juntas = $request->Id_asignacion_juntas;
        $array_datos_controversiaJuntas = DB::select('CALL psrcalificacionJuntas(?)', array($Id_asignacion_juntas));

        // Trae informacion de controversia_juntas
        $arrayinfo_controvertido= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_controversia_juntas_eventos as j')
        ->select('j.ID_evento','j.Enfermedad_heredada','j.F_transferencia_enfermedad','j.Primer_calificador','pa.Nombre_parametro as Calificador'
        ,'j.Nom_entidad','j.N_dictamen_controvertido','j.F_notifi_afiliado','j.Parte_controvierte_califi','pa2.Nombre_parametro as ParteCalificador','j.Nombre_controvierte_califi',
        'j.N_radicado_entrada_contro','j.Contro_origen','j.Contro_pcl','j.Contro_diagnostico','j.Contro_f_estructura','j.Contro_m_califi',
        'j.F_contro_primer_califi','j.F_contro_radi_califi','j.Termino_contro_califi','j.Jrci_califi_invalidez','pa3.Nombre_parametro as JrciNombre',
        'j.Origen_controversia','pa4.Nombre_parametro as OrigenContro','j.Manual_de_califi','d.Nombre_decreto','j.Total_deficiencia','j.Total_rol_ocupacional','j.Total_discapacidad',
        'j.Total_minusvalia','j.Porcentaje_pcl','j.Rango_pcl','j.F_estructuracion_contro','j.N_pago_jnci_contro','j.F_pago_jnci_contro','j.F_radica_pago_jnci_contro','j.N_dictamen_jrci_emitido'
        ,'j.F_dictamen_jrci_emitido','j.Origen_jrci_emitido','pa5.Nombre_parametro as OrigenEmitidoJrci','j.Manual_de_califi_jrci_emitido','d1.Nombre_decreto as Nombre_decretoJrci','j.Total_deficiencia_jrci_emitido',
        'j.Total_rol_ocupacional_jrci_emitido','j.Total_discapacidad_jrci_emitido','j.Total_minusvalia_jrci_emitido','j.Porcentaje_pcl_jrci_emitido','j.Rango_pcl_jrci_emitido',
        'j.F_estructuracion_contro_jrci_emitido','j.Resumen_dictamen_jrci','j.F_noti_dictamen_jrci','j.F_radica_dictamen_jrci','j.F_maxima_recurso_jrci','j.Decision_dictamen_jrci',
        'j.Sustentacion_concepto_jrci','j.F_sustenta_jrci','j.F_notificacion_recurso_jrci','j.N_radicado_recurso_jrci','j.Termino_contro_propia_jrci','j.Causal_decision_jrci','pa6.Nombre_parametro as NombreCausal',
        'j.Firmeza_intere_contro_jrci','j.Firmeza_reposicion_jrci','j.Firmeza_acta_ejecutoria_jrci','j.Firmeza_apelacion_jnci_jrci','j.Parte_contro_ante_jrci','pa7.Nombre_parametro as NomPresentaJrci',
        'j.Nombre_presen_contro_jrci','j.F_contro_otra_jrci','j.Contro_origen_jrci','j.Contro_pcl_jrci','j.Contro_diagnostico_jrci','j.Contro_f_estructura_jrci','j.Contro_m_califi_jrci','j.Reposicion_dictamen_jrci',
        'j.N_dictamen_reposicion_jrci','j.F_dictamen_reposicion_jrci','j.Origen_reposicion_jrci','pa8.Nombre_parametro as Nombre_origenRepoJrci','j.Manual_reposicion_jrci','d2.Nombre_decreto as Nombre_decretoRepoJrci',
        'j.Total_deficiencia_reposicion_jrci','j.Total_reposicion_jrci','j.Total_discapacidad_reposicion_jrci','j.Total_minusvalia_reposicion_jrci','j.Porcentaje_pcl_reposicion_jrci','j.Rango_pcl_reposicion_jrci'
        ,'j.F_estructuracion_contro_reposicion_jrci','j.Resumen_dictamen_reposicion_jrci','j.F_noti_dictamen_reposicion_jrci','j.F_radica_dictamen_reposicion_jrci','j.F_maxima_apelacion_jrci','j.Decision_dictamen_repo_jrci'
        ,'j.Decision_dictamen_repo_jrci','j.Causal_decision_repo_jrci','pa9.Nombre_parametro as NombreCausalRepo','j.Sustentacion_concepto_repo_jrci','j.F_sustenta_reposicion_jrci','j.F_noti_apela_recurso_jrci'
        ,'j.N_radicado_apela_recurso_jrci','j.T_propia_apela_recurso_jrci','j.Correspon_pago_jnci','j.N_orden_pago_jnci','j.F_orden_pago_jnci','j.F_radi_pago_jnci','j.N_acta_ejecutario_emitida_jrci'
        ,'j.F_acta_ejecutoria_emitida_jrci','j.F_firmeza_dictamen_jrci','j.Dictamen_firme_jrci','j.N_dictamen_jnci_emitido','j.F_dictamen_jnci_emitido','j.Origen_jnci_emitido','pa10.Nombre_parametro as NombreOrigen'
        ,'j.Manual_de_califi_jnci_emitido','pa11.Nombre_parametro as Nombre_decretoJnci','j.Total_deficiencia_jnci_emitido','j.Total_rol_ocupacional_jnci_emitido','j.Total_discapacidad_jnci_emitido'
        ,'j.Total_minusvalia_jnci_emitido','j.Porcentaje_pcl_jnci_emitido','j.Rango_pcl_jnci_emitido','j.F_estructuracion_contro_jnci_emitido','j.Resumen_dictamen_jnci','j.Sustentacion_dictamen_jnci'
        ,'j.F_sustenta_ante_jnci','j.F_noti_ante_jnci','j.F_radica_dictamen_jnci')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa', 'j.Primer_calificador', '=', 'pa.Id_Parametro','j.')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa2', 'j.Parte_controvierte_califi', '=', 'pa2.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa3', 'j.Jrci_califi_invalidez', '=', 'pa3.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa4', 'j.Origen_controversia', '=', 'pa4.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa5', 'j.Origen_jrci_emitido', '=', 'pa5.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa6', 'j.Causal_decision_jrci', '=', 'pa6.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa7', 'j.Parte_contro_ante_jrci', '=', 'pa7.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa8', 'j.Origen_reposicion_jrci', '=', 'pa8.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa9', 'j.Causal_decision_repo_jrci', '=', 'pa9.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa10', 'j.Origen_jnci_emitido', '=', 'pa10.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa11', 'j.Manual_de_califi_jnci_emitido', '=', 'pa11.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d', 'j.Manual_de_califi', '=', 'd.Id_Decreto')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d1', 'j.Manual_de_califi_jrci_emitido', '=', 'd1.Id_Decreto')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d2', 'j.Manual_reposicion_jrci', '=', 'd2.Id_Decreto')
        ->where('j.ID_evento',  '=', $Id_evento_juntas)
        ->get();
        
        // TRAER DATOS CIE10 (Diagnóstico motivo de calificación)
        $array_datos_diagnostico_motcalifi_contro =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro as Nombre_parametro_origen', 'side.Deficiencia_motivo_califi_condiciones', 'side.Lateralidad_CIE10', 'slp2.Nombre_parametro as Nombre_parametro_lateralidad', 'side.Principal')
        ->where([['side.ID_evento',$Id_evento_juntas],
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
            ['side.Id_proceso',$array_datos_controversiaJuntas[0]->Id_proceso],
            ['side.Item_servicio', '=', 'Emitido JNCI'],
            ['side.Estado', '=', 'Activo']
        ])->get(); 

        //Trae Documetos Generales del evento
        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($Id_evento_juntas));

        return view('coordinador.controversiaJuntas', compact('user','array_datos_controversiaJuntas','arrayinfo_controvertido','array_datos_diagnostico_motcalifi_contro','array_datos_diagnostico_motcalifi_emitido_jrci','array_datos_diagnostico_reposi_dictamen_jrci','array_datos_diagnostico_motcalifi_emitido_jnci','arraylistado_documentos'));
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
        //Captura los datos a guardar en controversia
        $datos_info_controvertido_juntas= [
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
        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_controvertido_juntas',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
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
        //Captura los datos a guardar en controversia
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
        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_emitido_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
        return json_decode(json_encode($mensajes, true));
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
        
        //Captura los datos a guardar en controversia
        $datos_info_controvertido_juntas= [
            'Decision_dictamen_jrci' => $request->decision_dictamen_jrci,
            'Causal_decision_jrci' => $request->causal_decision,
            'Sustentacion_concepto_jrci' => $request->sustentacion_concepto_jrci,
            'F_sustenta_jrci' => $date,
        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_revision_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
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
        
        //Captura los datos a guardar en controversia
        $datos_info_controvertido_juntas= [
            'F_notificacion_recurso_jrci' => $request->f_notificacion_recurso_jrci,
            'N_radicado_recurso_jrci' => $request->n_radicado_recurso_jrci,
            'Termino_contro_propia_jrci' => $TerminoRecurso,
        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_recurso_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
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
        //Captura los datos a guardar en controversia
        $datos_info_controvertido_juntas= [
            'Firmeza_intere_contro_jrci' => $request->firmeza_intere_contro_jrci,
            'Firmeza_reposicion_jrci' => $request->firmeza_reposicion_jrci,
            'Firmeza_acta_ejecutoria_jrci' => $request->firmeza_acta_ejecutoria_jrci,
            'Firmeza_apelacion_jnci_jrci' => $request->firmeza_apelacion_jnci_jrci,
        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_parte_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
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
        //Captura los datos a guardar en controversia
        $datos_info_controvertido_juntas= [
            'Parte_contro_ante_jrci' => $request->parte_contro_ante_jrci,
            'Nombre_presen_contro_jrci' => $request->nombre_presen_contro_jrci,
            'F_contro_otra_jrci' => $request->f_contro_otra_jrci,
            'Contro_origen_jrci' => $request->contro_origen_jrci,
            'Contro_pcl_jrci' => $request->contro_pcl_jrci,
            'Contro_diagnostico_jrci' => $request->contro_diagnostico_jrci,
            'Contro_f_estructura_jrci' => $request->contro_f_estructura_jrci,
            'Contro_m_califi_jrci' => $request->contro_m_califi_jrci,
        ];
        
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_parte_contro_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );

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
        //Captura los datos a guardar en controversia
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
        ];
        
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_datos_repo_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );

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
        
        //Captura los datos a guardar en controversia
        $datos_info_controvertido_juntas= [
            'Decision_dictamen_repo_jrci' => $request->decision_dictamen_repo_jrci,
            'Causal_decision_repo_jrci' => $request->causal_decision_repo,
            'Sustentacion_concepto_repo_jrci' => $request->sustentacion_concepto_repo_jrci,
            'F_sustenta_reposicion_jrci' => $date,
        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_reposicion_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
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
        //Captura los datos a guardar en controversia
        $datos_info_controvertido_juntas= [
            'F_noti_apela_recurso_jrci' => $request->f_noti_apela_recurso_jrci,
            'N_radicado_apela_recurso_jrci' => $request->n_radicado_apela_recurso_jrci,
            'T_propia_apela_recurso_jrci' => $TerminoRecurso,
            'Correspon_pago_jnci' => $request->correspon_pago_jnci,
            'N_orden_pago_jnci' => $request->n_orden_pago_jnci,
            'F_orden_pago_jnci' => $request->f_orden_pago_jnci,
            'F_radi_pago_jnci' => $request->f_radi_pago_jnci,

        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_apela_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
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
        //Captura los datos a guardar en controversia
        $datos_info_controvertido_juntas= [
            'N_acta_ejecutario_emitida_jrci' => $request->n_acta_ejecutario_emitida_jrci,
            'F_acta_ejecutoria_emitida_jrci' => $request->f_acta_ejecutoria_emitida_jrci,
            'F_firmeza_dictamen_jrci' => $request->f_firmeza_dictamen_jrci,
            'Dictamen_firme_jrci' => $Dictamen_firme_jrci,

        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_acta_jrci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
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
        //Captura los datos a guardar en controversia
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
            'F_noti_ante_jnci' => $request->f_noti_ante_jnci,  
            'F_sustenta_ante_jnci' => $request->f_sustenta_ante_jnci,  
        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'registro_emitido_jnci',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
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



}
