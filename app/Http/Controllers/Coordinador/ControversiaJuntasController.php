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
use App\Models\sigmel_informacion_firmas_clientes;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Shared\Html;

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
        'j.F_contro_primer_califi','j.F_contro_radi_califi','j.Termino_contro_califi','j.Jrci_califi_invalidez','sie.Nombre_entidad as JrciNombre',
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
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'j.Jrci_califi_invalidez', '=', 'sie.Id_Entidad')
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
        
        $array_comite_interdisciplinario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comite_interdisciplinario_eventos as sicie')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sicie.Nombre_dest_principal')
        ->select('sicie.ID_evento', 'sicie.Id_proceso', 'sicie.Id_Asignacion', 'sicie.Visar', 'sicie.Profesional_comite', 'sicie.F_visado_comite',
        'sicie.Destinatario_principal', 'sicie.Otro_destinatario', 'sicie.Tipo_destinatario', 'sicie.Nombre_dest_principal', 'sie.Nombre_entidad',
        'sicie.Nombre_destinatario','sicie.Nit_cc', 'sicie.Direccion_destinatario', 'sicie.Telefono_destinatario', 'sicie.Email_destinatario',
        'sicie.Departamento_destinatario', 'sicie.Ciudad_destinatario', 'sicie.Asunto', 'sicie.Cuerpo_comunicado', 'sicie.Copia_empleador',
        'sicie.Copia_eps', 'sicie.Copia_afp', 'sicie.Copia_arl', 'sicie.Copia_jr', 'sicie.Cual_jr', 'sicie.Copia_jn', 'sicie.Anexos',
        'sicie.Elaboro', 'sicie.Reviso', 'sicie.Firmar', 'sicie.Ciudad', 'sicie.F_correspondecia', 'sicie.N_radicado', 'sicie.Nombre_usuario',
        'sicie.F_registro')        
        ->where([
            ['ID_evento',$Id_evento_juntas],
            ['Id_Asignacion',$Id_asignacion_juntas]
        ])
        ->get(); 

        // creación de consecutivo para el comunicado
        $radicadocomunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->select('N_radicado')
        ->where([
            ['ID_evento',$Id_evento_juntas],
            ['F_comunicado',$date],
            ['Id_proceso','2']
        ])
        ->orderBy('N_radicado', 'desc')
        ->limit(1)
        ->get();
            
        if(count($radicadocomunicado)==0){
            $fechaActual = date("Ymd");
            // Obtener el último valor de la base de datos o archivo
            $consecutivoP1 = "SAL-JUN";
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
            $consecutivo = "SAL-JUN" . $fechaActual . $nuevoConsecutivoFormatted;            
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
            $consecutivo = "SAL-JUN" . $fechaActual . $nuevoConsecutivoFormatted;
        }

        return view('coordinador.controversiaJuntas', compact('user','array_datos_controversiaJuntas','arrayinfo_controvertido','array_datos_diagnostico_motcalifi_contro','array_datos_diagnostico_motcalifi_emitido_jrci','array_datos_diagnostico_reposi_dictamen_jrci','array_datos_diagnostico_motcalifi_emitido_jnci','arraylistado_documentos', 'array_comite_interdisciplinario', 'consecutivo'));
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
            ->insert($datos_correspondencia);       
    
            $datos_info_comunicado_eventos = [
                'ID_Evento' => $newId_evento,
                'Id_proceso' => $Id_proceso,
                'Id_Asignacion' => $newId_asignacion,
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
                ['ID_evento',$newId_evento],
                ['Id_Asignacion',$newId_asignacion]
            ])->update($datos_correspondencia);       
    
            $mensajes = array(
                "parametro" => 'actualizar_correspondencia',
                "mensaje" => 'Correspondencia actualizada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
        }
        

    }

    public function DescargarProformaRecursoReposicion(Request $request){
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        /* Captura de variables que vienen del ajax */
        $id_cliente = $request->id_cliente;
        $id_evento = $request->id_evento;
        $id_asignacion = $request->id_asignacion;
        $id_proceso = $request->id_proceso;
        $id_servicio = $request->id_servicio;
        $tipo_identificacion = $request->tipo_identificacion;
        $num_identificacion = $request->num_identificacion;
        $id_Jrci_califi_invalidez = $request->id_Jrci_califi_invalidez;
        $nombre_junta_regional = $request->nombre_junta_regional;
        $f_dictamen_jrci_emitido = $request->f_dictamen_jrci_emitido;
        $nombre_afiliado = $request->nombre_afiliado;
        $porcentaje_pcl_jrci_emitido = $request->porcentaje_pcl_jrci_emitido;
        $f_estructuracion_contro_jrci_emitido = $request->f_estructuracion_contro_jrci_emitido;
        $sustentacion_concepto_jrci = $request->sustentacion_concepto_jrci;
        $copia_empleador = $request->copia_empleador;
        $copia_eps = $request->copia_eps;
        $copia_afp = $request->copia_afp;
        $copia_arl = $request->copia_arl;
        $asunto = strtoupper($request->asunto);
        $cuerpo = $request->cuerpo;
        $firmar = $request->firmar;
        $nro_radicado = $request->nro_radicado;
        $origen_jrci_emitido = $request->origen_jrci_emitido;

        /* Creación de las variables faltantes que no están en el ajax */

        // Datos Junta regional
        $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
        ->select('sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
        ->where([['sie.Id_Entidad', $id_Jrci_califi_invalidez]])->get();

        $array_datos_junta_regional = json_decode(json_encode($datos_junta_regional), true);

        if(count($array_datos_junta_regional)>0){
            $direccion_junta = $array_datos_junta_regional[0]["Direccion"];
            $telefono_junta = $array_datos_junta_regional[0]["Telefonos"];
            $departamento_junta = $array_datos_junta_regional[0]["Nombre_departamento"];
            $ciudad_junta = $array_datos_junta_regional[0]["Nombre_ciudad"];
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

            // Controversia pcl
            if($id_servicio == 13){
                for ($i=0; $i < count($array_datos_diagnostico_motcalifi_emitido_jrci); $i++) { 
                    array_push($diagnosticos_cie10_jrci, $array_datos_diagnostico_motcalifi_emitido_jrci[$i]["Nombre_CIE10"]);
                }
            }else if($id_servicio == 12){ // Controversia origen
                for ($i=0; $i < count($array_datos_diagnostico_motcalifi_emitido_jrci); $i++) { 
                    $dx_concatenados = $array_datos_diagnostico_motcalifi_emitido_jrci[$i]["Codigo"]. " - ".$array_datos_diagnostico_motcalifi_emitido_jrci[$i]["Nombre_CIE10"];
                    array_push($diagnosticos_cie10_jrci, $dx_concatenados);
                }
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
            ->where([['sile.Nro_identificacion', $num_identificacion],['sile.ID_evento', $id_evento]])
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
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $id_evento]])
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
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $id_evento]])
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
            ->where([['Nro_identificacion', $num_identificacion],['ID_evento', $id_evento]])
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

        /* Extraemos los datos del footer */
        $datos_footer = sigmel_clientes::on('sigmel_gestiones')
        ->select('footer_dato_1', 'footer_dato_2', 'footer_dato_3', 'footer_dato_4', 'footer_dato_5')
        ->where('Id_cliente', $id_cliente)->get();

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

        /* Construcción proforma en formato docx (word) */
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

        // Creación de Header
        $header = $section->addHeader();
        $imagenPath_header = public_path($ruta_logo);
        $header->addImage($imagenPath_header, array('width' => 150, 'align' => 'right'));

        // Creación de Contenido
        $section->addText('Bogotá D.C, '.$date, array('bold' => true));
        $section->addTextBreak();
        $htmltabla1 = '<table align="justify" style="width: 100%; border: none;">
            <tr>
                <td>
                    <p><b>Señores:</b>
                        <br>'.
                        $nombre_junta_regional.'</br>
                    </p>
                    <p><b>Dirección:</b>
                        <br>'.
                        $direccion_junta.'</br>
                    </p>
                    <p><b>Teléfono:</b>
                        <br>'.
                        $telefono_junta.'</br>
                    </p>
                    <p><b>Ciudad:</b>
                        <br>'.
                        $ciudad_junta.' - '.$departamento_junta.'</br>
                    </p>
                </td>
                <td>
                    <br></br>
                    <table style="width: 60%; border: 3px black solid;">
                        <tr>
                            <td>
                                <p><b>Nro. Radicado: '.$nro_radicado.'</b></p>  
                                <p><b>'.$tipo_identificacion." ".$num_identificacion.'</b></p>
                                <p><b>Siniestro: '.$id_evento.'</b></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>';
    
        Html::addHtml($section, $htmltabla1, false, true);
        
        $patron_asunto = '/\{\{\$F_DICTAMEN_JRCI\}\}/'; 
        if (preg_match($patron_asunto, $asunto)) {
            $asunto_modificado = str_replace('{{$F_DICTAMEN_JRCI}}', $f_dictamen_jrci_emitido, $asunto);
            $asunto = $asunto_modificado;
        }else{
            $asunto = "";
        }

        $section->addText('Asunto: '.$asunto, array('bold' => true));
        $section->addTextBreak();
        $section->addText('Afiliado: '.$nombre_afiliado." ".$tipo_identificacion." ".$num_identificacion, array('bold' => true));

        // Configuramos el reemplazo de las etiquetas del cuerpo del comunicado
        $patron1 = '/\{\{\$nombre_afiliado\}\}/';
        $patron2 = '/\{\{\$junta_regional\}\}/';
        $patron3 = '/\{\{\$cie10_jrci\}\}/';
        $patron4 = '/\{\{\$pcl_jrci\}\}/';
        $patron5 = '/\{\{\$f_estructuracion_jrci\}\}/';
        $patron6 = '/\{\{\$sustentacion_jrci\}\}/';
        $patron7 = '/\{\{\$origen_dx_jrci\}\}/';
        $patron8 = '/\{\{\$cie10_nombre_cie10_jrci\}\}/';
        
        // Controversia pcl
        if($id_servicio == 13){
            if (preg_match($patron1, $cuerpo) && preg_match($patron2, $cuerpo) && preg_match($patron3, $cuerpo) && preg_match($patron4, $cuerpo) && preg_match($patron5, $cuerpo) && preg_match($patron6, $cuerpo)) {
    
                $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.$nombre_afiliado.'</b>', $cuerpo);
                $cuerpo_modificado = str_replace('{{$junta_regional}}', '<b>'.$nombre_junta_regional.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$cie10_jrci}}', $string_diagnosticos_cie10_jrci, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$pcl_jrci}}', '<b>'.$porcentaje_pcl_jrci_emitido.'%</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$f_estructuracion_jrci}}', $f_estructuracion_contro_jrci_emitido, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);
    
    
                $cuerpo_modificado = str_replace('HUGO IGNACIO GÓMEZ DAZA', '<b>HUGO IGNACIO GÓMEZ DAZA</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('SEGUROS DE VIDA ALFA S.A.', '<b>SEGUROS DE VIDA ALFA S.A.</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN', '<b>RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('PCL', '<b>PCL</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('calificación de PCL', '<b>calificación de PCL</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('ANEXO:', '<b>ANEXO:</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('NOTIFICACIONES:', '<b>NOTIFICACIONES:</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('</p>', '</p><br></br>', $cuerpo_modificado);
                $cuerpo = $cuerpo_modificado;
    
            }else{
                $cuerpo = "";
            }
           
        }else if($id_servicio == 12){ // Controversia origen
            if (preg_match($patron1, $cuerpo) && preg_match($patron2, $cuerpo) &&  preg_match($patron6, $cuerpo) &&  preg_match($patron7, $cuerpo) &&  preg_match($patron8, $cuerpo)) {
    
        
                $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', '<b>'.$nombre_afiliado.'</b>', $cuerpo);
                $cuerpo_modificado = str_replace('{{$junta_regional}}', '<b>'.$nombre_junta_regional.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$origen_dx_jrci}}', '<b>'.$origen_jrci_emitido.'</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$cie10_nombre_cie10_jrci}}', $string_diagnosticos_cie10_jrci, $cuerpo_modificado);
                $cuerpo_modificado = str_replace('{{$sustentacion_jrci}}', $sustentacion_concepto_jrci, $cuerpo_modificado);
    
    
                $cuerpo_modificado = str_replace('HUGO IGNACIO GÓMEZ DAZA', '<b>HUGO IGNACIO GÓMEZ DAZA</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('SEGUROS DE VIDA ALFA S.A.', '<b>SEGUROS DE VIDA ALFA S.A.</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN', '<b>RECURSO DE REPOSICIÓN Y EN SUBSIDIO EL DE APELACIÓN</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('ORIGEN', '<b>ORIGEN</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('ANEXO:', '<b>ANEXO:</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('NOTIFICACIONES:', '<b>NOTIFICACIONES:</b>', $cuerpo_modificado);
                $cuerpo_modificado = str_replace('</p>', '</p><br></br>', $cuerpo_modificado);
                $cuerpo = $cuerpo_modificado;
    
            }else{
                $cuerpo = "";
            }
        }

        $section->addTextBreak();
        Html::addHtml($section, $cuerpo, false, true);
        $section->addTextBreak();
        $section->addText('Cordialmente,');
        $section->addTextBreak();

        if($Firma_cliente != "No firma"){
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
        }else{
            $section->addText($Firma_cliente);
        }

        $section->addTextBreak();
        $section->addText('HUGO IGNACIO GÓMEZ DAZA', array('bold' => true));
        $section->addTextBreak();
        $section->addText('Representante Legal para Asuntos de Seguridad Social', array('bold' => true));
        $section->addTextBreak();
        $section->addText('Convenio Codess - Seguros de Vida Alfa S.A', array('bold' => true));
        $section->addTextBreak();
        $section->addText('Elaboró: '.$nombre_usuario, array('bold' => true));
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

            // $Afiliado = 'Afiliado';
            $Empleador = 'Empleador';
            $EPS = 'EPS';
            $AFP = 'AFP';
            $ARL = 'ARL';

            // if (isset($Agregar_copias[$Afiliado])) {
            //     $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Afiliado: </span>' . $Agregar_copias['Afiliado'] . '</td></tr>';
            // }

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
        $section->addText($nombre_afiliado." - ".$tipo_identificacion." ".$num_identificacion." - Siniestro: ".$id_evento, array('bold' => true));

        // Configuramos el footer
        $footer = $section->addFooter();
        $tableStyle = array(
            'cellMargin'  => 50,
        );
        $phpWord->addTableStyle('myTable', $tableStyle);
        $table = $footer->addTable('myTable');

        $table->addRow();
        // $table->addCell(80000, ['gridSpan' => 2])->addText('Seguros Alfa S.A. y Seguros de Vida Alfa S.A.', array('size' => 10, 'color' => '#184F56', 'bold' => true));
        $table->addCell(80000, ['gridSpan' => 2])->addText($footer_dato_1, array('size' => 10));
        $table->addRow();
        // $table->addCell()->addText('Líneas de atención al cliente', array('size' => 10, 'color' => '#184F56', 'bold' => true));
        $table->addCell()->addText($footer_dato_2, array('size' => 10));
        $cell = $table->addCell();
        $textRun = $cell->addTextRun(['alignment' => 'right']);
        // $textRun->addText('www.segurosalfa.com.co', array('size' => 10));
        $textRun->addText($footer_dato_3, array('size' => 10));
        $table->addRow();
        // $table->addCell(80000, ['gridSpan' => 2])->addText('Bogotá: 3077032, a nivel nacional: 018000122532', array('size' => 10));
        $table->addCell(80000, ['gridSpan' => 2])->addText($footer_dato_4, array('size' => 10));
        $table->addRow();
        // $table->addCell(80000, ['gridSpan' => 2])->addText('Habilitadas en jornada continua de lunes a viernes de 8:00 a.m. a 6:00 p.m.', array('size' => 10));
        $table->addCell(80000, ['gridSpan' => 2])->addText($footer_dato_5, array('size' => 10));
        $table->addRow();
        $cell1 = $table->addCell(80000, ['gridSpan' => 2]);
        $textRun = $cell1->addTextRun(['alignment' => 'center']);
        $textRun->addText('Página ');
        $textRun->addField('PAGE');

        

        // Generamos el documento y luego se guarda
        $writer = new Word2007($phpWord);
        $nombre_docx = "JUN_DESACUERDO_{$id_asignacion}_{$num_identificacion}.docx";
        $writer->save(public_path("Documentos_Eventos/{$id_evento}/{$nombre_docx}"));
        return response()->download(public_path("Documentos_Eventos/{$id_evento}/{$nombre_docx}"));

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