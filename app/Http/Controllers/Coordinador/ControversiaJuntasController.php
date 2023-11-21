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
        'j.Sustentacion_concepto_jrci','j.F_sustenta_jrci','j.F_notificacion_recurso_jrci','j.N_radicado_recurso_jrci','j.Termino_contro_propia_jrci'
        )
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa', 'j.Primer_calificador', '=', 'pa.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa2', 'j.Parte_controvierte_califi', '=', 'pa2.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa3', 'j.Jrci_califi_invalidez', '=', 'pa3.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa4', 'j.Origen_controversia', '=', 'pa4.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa5', 'j.Origen_jrci_emitido', '=', 'pa5.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d', 'j.Manual_de_califi', '=', 'd.Id_Decreto')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as d1', 'j.Manual_de_califi_jrci_emitido', '=', 'd1.Id_Decreto')
        ->where('j.ID_evento',  '=', $Id_evento_juntas)
        ->get();


        return view('coordinador.controversiaJuntas', compact('user','array_datos_controversiaJuntas','arrayinfo_controvertido'));
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
        $f_contro_primer_califi = $request->f_contro_primer_califi;
        $f_notifi_afiliado = $request->f_notifi_afiliado;
        //Validar registro termino de controversia
        /* $conteoDias = sigmel_calendarios::on('sigmel_gestiones')
        ->whereBetween('Fecha', [$f_notifi_afiliado, $f_contro_primer_califi])
        ->where('Calendario', 'LunesAViernes')
        ->where('EsHabil', 1)
        ->where('EsFestivo', 0)
        ->count();
        if($conteoDias > 10){
            $terminos='Fuera de términos';
        }else{
            $terminos='Dentro de términos';  
        } */
        // validacion de bandera para guardar o actualizar
        // insercion de datos a la tabla de sigmel_informacion_controversia_juntas_eventos

        /* if ($request->bandera_controvertido_guardar_actualizar == 'Guardar') {

            $datos_info_controvertido= [
                'ID_evento' => $newIdEvento,
                'Id_Asignacion' => $newIdAsignacion,
                'Id_proceso' => $Id_proceso,
                'Enfermedad_heredada' => $request->enfermedad_heredada,
                'F_transferencia_enfermedad' => $request->f_transferencia_enfermedad,
                'Primer_calificador' => $request->primer_calificador,
                'Nom_entidad' => $request->nom_entidad,
                'N_dictamen_controvertido' => $request->N_dictamen_controvertido,
                'F_notifi_afiliado' => $request->f_notifi_afiliado,
                'Termino_contro_califi' => $terminos,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')->insert($datos_info_controvertido);

            $mensajes = array(
                "parametro" => 'agregar_controvertido',
                "mensaje" => 'Registro agregado satisfactoriamente.'
            );

            return json_decode(json_encode($mensajes, true));

        }else{*/
        // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos
        $datos_info_controvertido_juntas= [
            'Enfermedad_heredada' => $request->enfermedad_heredada,
        ];
           
        sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
        ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_controvertido_juntas);

        $mensajes = array(
            "parametro" => 'agregar_controvertido',
            "mensaje" => 'Registro actualizado satisfactoriamente.'
        );
    
        return json_decode(json_encode($mensajes, true));
        //}
    }
}
