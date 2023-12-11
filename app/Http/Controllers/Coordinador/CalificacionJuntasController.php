<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_historial_acciones_eventos;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_informacion_controversia_juntas_eventos;
use App\Models\sigmel_calendarios;
use App\Models\sigmel_informacion_pagos_honorarios_eventos;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\cndatos_info_comunicado_eventos;
use App\Models\sigmel_informacion_seguimientos_eventos;

use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;

class CalificacionJuntasController extends Controller
{
    public function mostrarVistaCalificacionJuntas(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $time = time();
        $date = date("Y-m-d", $time);
        $newIdAsignacion=$request->newIdAsignacion;
        $newIdEvento = $request->newIdEvento;

        $array_datos_calificacionJuntas = DB::select('CALL psrcalificacionJuntas(?)', array($newIdAsignacion));
        //Trae Documetos Generales del evento
        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));
        // Consulta Informacion de afiliado
        $arrayinfo_afiliado= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as Afi')
        ->select('Afi.Direccion','ci.Nombre_municipio','ci.Nombre_departamento','Afi.Telefono_contacto','Afi.Email','Afi.F_actualizacion')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as ci', 'Afi.Id_municipio', '=', 'ci.Id_municipios')
        ->where('Afi.ID_evento',  '=', $newIdEvento)
        ->get();
        // Trae informacion de controversia_juntas
        $arrayinfo_controvertido= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_controversia_juntas_eventos as j')
        ->select('j.ID_evento','j.Enfermedad_heredada','j.F_transferencia_enfermedad','j.Primer_calificador','pa.Nombre_parametro as Calificador'
        ,'j.Nom_entidad','j.N_dictamen_controvertido','j.F_notifi_afiliado','j.Parte_controvierte_califi','pa2.Nombre_parametro as ParteCalificador','j.Nombre_controvierte_califi',
        'j.N_radicado_entrada_contro','j.Contro_origen','j.Contro_pcl','j.Contro_diagnostico','j.Contro_f_estructura','j.Contro_m_califi',
        'j.F_contro_primer_califi','j.F_contro_radi_califi','j.Termino_contro_califi','j.Jrci_califi_invalidez','pa3.Nombre_parametro as JrciNombre')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa', 'j.Primer_calificador', '=', 'pa.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa2', 'j.Parte_controvierte_califi', '=', 'pa2.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa3', 'j.Jrci_califi_invalidez', '=', 'pa3.Id_Parametro')
        ->where('j.ID_evento',  '=', $newIdEvento)
        ->get();

        //Trae Pago de Honorarios 
        $arrayinfo_pagos= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_pagos_honorarios_eventos as p')
        ->select('p.Tipo_pago','pa.Nombre_parametro as NomPago','p.F_solicitud_pago','pa2.Nombre_parametro as JuntaPago'
        ,'p.N_orden_pago','p.Valor_pagado','p.F_pago_honorarios','p.F_pago_radicacion')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa', 'p.Tipo_pago', '=', 'pa.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa2', 'p.Pago_junta', '=', 'pa2.Id_Parametro')
        ->where('p.ID_evento',  '=', $newIdEvento)
        ->get();

        //Trae Listado de documentos
        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where([
            ['Estado', 'Activo'], ['Id_proceso',$array_datos_calificacionJuntas[0]->Id_proceso],
            ['ID_evento', $newIdEvento]
        ])
        ->get();

        //Valida si no aporta documentos
        $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
       ->select('Id_Documento_Solicitado', 'Aporta_documento')
       ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion], ['Estado', 'Inactivo'], ['Aporta_documento', 'No']])
       ->get();

       //$arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));

        $arraycampa_documento_solicitado = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $newIdEvento],
            ['Id_proceso',$array_datos_calificacionJuntas[0]->Id_proceso],
            ['Estado', 'Activo'],
        ])
        ->get();

        // creación de consecutivo para el comunicado
       $radicadocomunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
       ->select('N_radicado')
       ->where([
           ['ID_evento',$newIdEvento],
           ['F_comunicado',$date],
           ['Id_proceso','3']
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
       //Historial De Seguimientos
       $hitorialAgregarSeguimiento = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->select('ID_evento','F_seguimiento','Causal_seguimiento','Descripcion_seguimiento','Nombre_usuario')
            ->where([
                ['ID_evento', $newIdEvento],
                ['Estado','Activo'],
                ['Id_proceso','3']
            ])
            ->get();

        //Consulta Vista a mostrar
        $TraeVista= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_lista_procesos_servicios as p')
        ->select('v.nombre_renderizar')
        ->leftJoin('sigmel_sys.sigmel_vistas as v', 'p.Id_vista', '=', 'v.id')
        ->where('p.Id_Servicio',  '=', $array_datos_calificacionJuntas[0]->Id_Servicio)
        ->get();
        $SubModulo=$TraeVista[0]->nombre_renderizar; //Enviar a la vista del SubModulo    


        return view('coordinador.calificacionJuntas', compact('user','array_datos_calificacionJuntas','arraylistado_documentos','arrayinfo_afiliado','arrayinfo_controvertido','arrayinfo_pagos','listado_documentos_solicitados','dato_validacion_no_aporta_docs','arraycampa_documento_solicitado','consecutivo','hitorialAgregarSeguimiento','SubModulo'));
    }
    //Cargar Selectores Juntas
    public function cargueListadoSelectoresJuntas(Request $request){
    
        $parametro = $request->parametro;
        //Lista tipo evento
        if($parametro == "lista_tipo_evento"){
            $datos_tipo_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
                ->select('Id_Evento','Nombre_evento')
                ->where([
                    ['Id_Evento', '<=', 2],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $informacion_datos_tipo_evento = json_decode(json_encode($datos_tipo_evento, true));
            return response()->json($informacion_datos_tipo_evento);
        }
        // Listado tipo entidad
        if($parametro == 'lista_primer_calificador'){
            $listado_tipo_entidad = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Juntas Controversia'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_tipo_entidad = json_decode(json_encode($listado_tipo_entidad, true));
            return response()->json($info_listado_tipo_entidad);
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
        // Listado Junta Jrci Invalidez
        if($parametro == 'lista_juntas_invalidez'){
            $listado_juntas_invalidez = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Jrci Invalidez'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_juntas_invalidez = json_decode(json_encode($listado_juntas_invalidez, true));
            return response()->json($info_listado_juntas_invalidez);
        }
        // Listado tipo de pago
        if($parametro == 'lista_tipo_pago'){
            $listado_tipo_pago = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Tipo de pago'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_tipo_pago = json_decode(json_encode($listado_tipo_pago, true));
            return response()->json($info_listado_tipo_pago);
        }
        // Listado Junta Pagos Honorarios
        if($parametro == 'lista_juntas_pago'){
            $listado_pagos_juntas = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Jrci Invalidez'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_pagos_juntas = json_decode(json_encode($listado_pagos_juntas, true));
            return response()->json($info_listado_pagos_juntas);
        }

        if ($parametro == 'listado_solicitantes') {
            $datos_solicitantes = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Juntas Controversia'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $informacion_solicitantes = json_decode(json_encode($datos_solicitantes), true);
            return response()->json($informacion_solicitantes);
        }

        if($parametro = "listado_accion"){
            /* Iniciamos trayendo las acciones a ejecutar configuradas en la tabla de parametrizaciones
            dependiendo del id del cliente, id del proceso, id del servicio, estado activo */
            
            $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')->where('ID_evento', $request->nro_evento)->first();

            $id_cliente = $array_id_cliente["Cliente"];

            $acciones_a_ejecutar = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Accion_ejecutar')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $request->Id_proceso],
                ['sipc.Servicio_asociado', '=', $request->Id_servicio],
                ['sipc.Status_parametrico', '=', 'Activo']
            ])->get();

            $info_acciones_a_ejecutar = json_decode(json_encode($acciones_a_ejecutar, true));

            if (count($info_acciones_a_ejecutar) > 0) {
                // Extraemos las acciones antecesoras a partir de las acciones a ejecutar
                $array_acciones_ejecutar = [];
                for ($i=0; $i < count($info_acciones_a_ejecutar); $i++) { 
                    array_push($array_acciones_ejecutar, $info_acciones_a_ejecutar[$i]->Accion_ejecutar);
                };
                $extraccion_acciones_antecesoras = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
                ->select('Accion_ejecutar','Accion_antecesora')
                ->where([
                    ['Id_cliente', '=', $id_cliente],
                    ['Id_proceso', '=', $request->Id_proceso],
                    ['Servicio_asociado', '=', $request->Id_servicio],
                ])
                ->whereIn('Accion_ejecutar', $array_acciones_ejecutar)
                ->get();
                
                $info_extraccion_acciones_antecesoras = json_decode(json_encode($extraccion_acciones_antecesoras, true));

                // En caso de que almenos exista una acción antecesora, se debe analizar si esta acción 
                // (que depende de una acción ejecutar) está en la tabla de auditorias de asignacion de eventos dependiendo
                // del id del proceso y el id del servicio. El id de la accion a ejecutar estaría dentro de las opciones a mostrar solo si se encuentra el id
                // de la accion antecesora en dicha tabla
                if (count($info_extraccion_acciones_antecesoras) > 0) {
                    
                    foreach ($info_extraccion_acciones_antecesoras as $key => $value) {
                        if ($info_extraccion_acciones_antecesoras[$key]->Accion_antecesora !== null) {
                            $busqueda_accion_antecesora = DB::table(getDatabaseName('sigmel_auditorias') .'sigmel_auditorias_informacion_asignacion_eventos as saiae')
                            ->select('saiae.Aud_Id_accion')
                            ->where([
                                ['saiae.Aud_Id_Asignacion', '=', $request->Id_asignacion],
                                ['saiae.Aud_ID_evento', '=', $request->nro_evento],
                                ['saiae.Aud_Id_proceso', '=', $request->Id_proceso],
                                ['saiae.Aud_Id_servicio', '=', $request->Id_servicio],
                                ['saiae.Aud_Id_accion', $info_extraccion_acciones_antecesoras[$key]->Accion_antecesora]
                            ])
                            ->get();

                            // Si no existe en la tabla debe eliminar la información de la acción a ejecutar ya que esta no se debe mostrar.
                            if (count($busqueda_accion_antecesora) == 0) {
                                unset($info_extraccion_acciones_antecesoras[$key]);
                            }
                        }
                    }
                    
                    $info_extraccion_acciones_antecesoras = array_values($info_extraccion_acciones_antecesoras);
                    
                    /* echo "<pre>";
                    print_r($info_extraccion_acciones_antecesoras);
                    echo "</pre>"; */

                    // Extraemos los id de las acciones a ejecutar para buscarlas en la tabla sigmel_informacion_acciones;
                    $array_listado_acciones = [];
                    for ($a=0; $a < count($info_extraccion_acciones_antecesoras); $a++) { 
                        array_push($array_listado_acciones, $info_extraccion_acciones_antecesoras[$a]->Accion_ejecutar);
                    }

                    // print_r($array_listado_acciones);
                    $listado_acciones = sigmel_informacion_acciones::on('sigmel_gestiones')
                    ->select('Id_Accion', 'Accion as Nombre_accion')
                    ->where([
                        ['Status_accion', '=', 'Activo']
                    ])
                    ->whereIn('Id_Accion', $array_listado_acciones)
                    ->get();

                    $info_listado_acciones_nuevo_servicio = json_decode(json_encode($listado_acciones, true));
                    return response()->json(($info_listado_acciones_nuevo_servicio));
                }
            }
        }
    }

    //Guardar informacion del modulo de Juntas
    public function guardarCalificacionJuntas(Request $request){
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
        $Id_servicio = $request->Id_servicio;

        // validacion de bandera para guardar o actualizar
        if ($request->bandera_accion_guardar_actualizar == 'Guardar') {
               
            // insercion de datos a la tabla de sigmel_informacion_accion_eventos
    
            $datos_info_registrarCalifcacionJuntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
                'F_accion' => $request->f_accion,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Causal_devolucion_comite' => 'N/A',
                'Descripcion_accion' => $request->descripcion_accion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
            // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
            // en la creación de un evento
            // MAURO PARAMETRICA
            $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')->where('ID_evento', $newIdEvento)->first();

            $id_cliente = $array_id_cliente["Cliente"];

            $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Estado')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar','=',  $request->accion]
            ])->get();

            if(count($estado_acorde_a_parametrica)>0){
                $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
            }else{
                $Id_Estado_evento = 223;
            }

            /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
            $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Detiene_tiempo_gestion')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar', '=', $request->accion]
            ])->get();

            if(count($casilla_detiene_tiempo_gestion) > 0){
                $Detiene_tiempo_gestion = $casilla_detiene_tiempo_gestion[0]->Detiene_tiempo_gestion;
                if ($Detiene_tiempo_gestion == "Si") {
                    $Detener_tiempo_gestion = "Si";
                    $F_detencion_tiempo_gestion = $date;
                }else{
                    $Detener_tiempo_gestion = "No";
                    $F_detencion_tiempo_gestion = null;
                }
            };

            $datos_info_actualizarAsignacionEvento= [ 
                'Id_accion' => $request->accion,
                'Id_Estado_evento' => $Id_Estado_evento,             
                'F_alerta' => $request->fecha_alerta,                
                'Nombre_usuario' => $nombre_usuario,
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                // 'F_registro' => $date,
            ];
    
            sigmel_informacion_accion_eventos::on('sigmel_gestiones')->insert($datos_info_registrarCalifcacionJuntas);

            sleep(2);

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            sleep(2);

            $datos_info_historial_acciones = [
                'ID_evento' => $newIdEvento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Guardado Modulo Calificacion Juntas.",
                'Descripcion' => $request->descripcion_accion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
            sleep(2);
            $mensajes = array(
                "parametro" => 'agregarCalificacionJuntas',
                "parametro_1" => 'guardo',
                "mensaje_1" => 'Registro agregado satisfactoriamente.'
            );

            return json_decode(json_encode($mensajes, true));

        }elseif ($request->bandera_accion_guardar_actualizar == 'Actualizar') {
            
            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos

            $datos_info_registrarCalifcacionJuntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
                'F_accion' => $request->f_accion,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Causal_devolucion_comite' => 'N/A',
                'Descripcion_accion' => $request->descripcion_accion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
            // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
            // en la creación de un evento
            // MAURO PARAMETRICA
            $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')->where('ID_evento', $newIdEvento)->first();

            $id_cliente = $array_id_cliente["Cliente"];

            $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Estado')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar','=',  $request->accion]
            ])->get();

            if(count($estado_acorde_a_parametrica)>0){
                $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
            }else{
                $Id_Estado_evento = 223;
            }

            /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
            $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Detiene_tiempo_gestion')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar', '=', $request->accion]
            ])->get();

            if(count($casilla_detiene_tiempo_gestion) > 0){
                $Detiene_tiempo_gestion = $casilla_detiene_tiempo_gestion[0]->Detiene_tiempo_gestion;
                if ($Detiene_tiempo_gestion == "Si") {
                    $Detener_tiempo_gestion = "Si";
                    $F_detencion_tiempo_gestion = $date;
                }else{
                    $Detener_tiempo_gestion = "No";
                    $F_detencion_tiempo_gestion = null;
                }
            };

            $datos_info_actualizarAsignacionEvento= [      
                'Id_accion' => $request->accion,
                'Id_Estado_evento' => $Id_Estado_evento,        
                'F_alerta' => $request->fecha_alerta,                
                'Nombre_usuario' => $nombre_usuario,
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                // 'F_registro' => $date,
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_registrarCalifcacionJuntas);
            sleep(2);
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            sleep(2);

            $datos_info_historial_acciones = [
                'ID_evento' => $newIdEvento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Actualizado Modulo Juntas.",
                'Descripcion' => $request->descripcion_accion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
            sleep(2);
            $mensajes = array(
                "parametro" => 'agregarCalificacionJuntas',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }
    //Guarda informacion de controvertido Juntas
    public function guardarControvertidoJuntas(Request $request){
    
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
        $conteoDias = sigmel_calendarios::on('sigmel_gestiones')
        ->whereBetween('Fecha', [$f_notifi_afiliado, $f_contro_primer_califi])
        ->where('Calendario', 'LunesAViernes')
        ->where('EsHabil', 1)
        ->where('EsFestivo', 0)
        ->count();
        if($conteoDias > 10){
            $terminos='Fuera de términos';
        }else{
            $terminos='Dentro de términos';  
        }
        // validacion de bandera para guardar o actualizar
        // insercion de datos a la tabla de sigmel_informacion_controversia_juntas_eventos

        if ($request->bandera_controvertido_guardar_actualizar == 'Guardar') {

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

        }else{
            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos
            $datos_info_actuali_controvertido= [
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
           
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actuali_controvertido);

            $mensajes = array(
                "parametro" => 'agregar_controvertido',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
        }
    }
    //Guarda informacion de controversia Juntas
    public function guardarControversiaJuntas(Request $request){
    
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
        $conteoDias = sigmel_calendarios::on('sigmel_gestiones')
        ->whereBetween('Fecha', [$f_notifi_afiliado, $f_contro_primer_califi])
        ->where('Calendario', 'LunesAViernes')
        ->where('EsHabil', 1)
        ->where('EsFestivo', 0)
        ->count();
        if($conteoDias > 10){
            $terminos='Fuera de términos';
        }else{
            $terminos='Dentro de términos';  
        }

        // validacion de bandera para guardar o actualizar
        // insercion de datos a la tabla de sigmel_informacion_controversia_juntas_eventos
        if ($request->bandera_controversia_guardar_actualizar == 'Guardar') {

            $datos_info_controversia= [
                'ID_evento' => $newIdEvento,
                'Id_Asignacion' => $newIdAsignacion,
                'Id_proceso' => $Id_proceso,
                'Parte_controvierte_califi' => $request->parte_controvierte_califi,
                'Nombre_controvierte_califi' => $request->nombre_controvierte_califi,
                'N_radicado_entrada_contro' => $request->n_radicado_entrada_contro,
                'Contro_origen' => $request->contro_origen,
                'Contro_pcl' => $request->contro_pcl,
                'Contro_diagnostico' => $request->contro_diagnostico,
                'Contro_f_estructura' => $request->contro_f_estructura,
                'Contro_m_califi' => $request->contro_m_califi,
                'F_contro_primer_califi' => $request->f_contro_primer_califi,
                'F_contro_radi_califi' => $request->f_contro_radi_califi,
                'Termino_contro_califi' => $terminos,
                'Jrci_califi_invalidez' => $request->jrci_califi_invalidez,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')->insert($datos_info_controversia);

            $mensajes = array(
                "parametro" => 'agregar_controversia',
                "mensaje" => 'Registro agregado satisfactoriamente.'
            );

            return json_decode(json_encode($mensajes, true));

        }else{
            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos
            $datos_info_actuali_controversia= [
                'Parte_controvierte_califi' => $request->parte_controvierte_califi,
                'Nombre_controvierte_califi' => $request->nombre_controvierte_califi,
                'N_radicado_entrada_contro' => $request->n_radicado_entrada_contro,
                'Contro_origen' => $request->contro_origen,
                'Contro_pcl' => $request->contro_pcl,
                'Contro_diagnostico' => $request->contro_diagnostico,
                'Contro_f_estructura' => $request->contro_f_estructura,
                'Contro_m_califi' => $request->contro_m_califi,
                'F_contro_primer_califi' => $request->f_contro_primer_califi,
                'F_contro_radi_califi' => $request->f_contro_radi_califi,
                'Termino_contro_califi' => $terminos,
                'Jrci_califi_invalidez' => $request->jrci_califi_invalidez,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actuali_controversia);

            $mensajes = array(
                "parametro" => 'agregar_controversia',
                "mensaje" => 'Registro actualizado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));
        }
    }
    //Guarda informacion pagos honorarios
    public function guardarPagosJuntas(Request $request){
    
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

        $datos_info_pagos= [
            'ID_evento' => $newIdEvento,
            'Id_Asignacion' => $newIdAsignacion,
            'Id_proceso' => $Id_proceso,
            'Tipo_pago' => $request->tipo_pago,
            'F_solicitud_pago' => $request->f_solicitud_pago,
            'Pago_junta' => $request->pago_junta,
            'N_orden_pago' => $request->n_orden_pago,
            'Valor_pagado' => $request->valor_pagado,
            'F_pago_honorarios' => $request->f_pago_honorarios,
            'F_pago_radicacion' => $request->f_pago_radicacion,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];    
        sigmel_informacion_pagos_honorarios_eventos::on('sigmel_gestiones')->insert($datos_info_pagos);

        $mensajes = array(
            "parametro" => 'agregar_pagosjuntas',
            "mensaje" => 'Registro agregado satisfactoriamente.'
        );
        return json_decode(json_encode($mensajes, true));
    }
    // Guardar la información del Listado de Documentos solicitados
    public function GuardarDocumentosSolicitadosJuntas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $parametro = $request->parametro;

        if ($parametro == "datos_bitacora") {

            // Seteo del autoincrement para mantener el primary key siempre consecutivo.
            $max_id = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->max('Id_Documento_Solicitado');
            if ($max_id <> "") {
                DB::connection('sigmel_gestiones')
                ->statement("ALTER TABLE sigmel_informacion_documentos_solicitados_eventos AUTO_INCREMENT = ".($max_id));
            }

            // Validacion: Se desmarca la opción no aporta documentos y se inserta registros.
            if ($request->tupla_no_aporta <> 0) {
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
                ->where('Id_Documento_Solicitado', $request->tupla_no_aporta)->delete();
            }
            
            $aporta_documento = 'Si';
            // Captura del array de los datos de la tabla
            $array_datos = $request->datos_finales_documentos_solicitados;
    
            // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
            $array_datos_organizados = [];
            foreach ($array_datos as $subarray_datos) {
    
                array_unshift($subarray_datos, $request->Id_proceso);
                array_unshift($subarray_datos, $request->Id_Asignacion);
                array_unshift($subarray_datos, $request->Id_evento);
    
                $subarray_datos[] = $aporta_documento;
                $subarray_datos[] = $nombre_usuario;
                $subarray_datos[] = $date;
    
                array_push($array_datos_organizados, $subarray_datos);
            }
            // Creación de array con los campos de la tabla: sigmel_informacion_documentos_solicitados_eventos
            $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso','F_solicitud_documento','Nombre_documento',
            'Descripcion','Id_solicitante','Nombre_solicitante','F_recepcion_documento', 'Aporta_documento', 'Nombre_usuario','F_registro'];
            
            // Combinación de los campos de la tabla con los datos
           $array_datos_con_keys = [];
            foreach ($array_datos_organizados as $subarray_datos_organizados) {
                array_push($array_datos_con_keys, array_combine($array_keys_tabla, $subarray_datos_organizados));
            }
    
            // Inserción de la información
            foreach ($array_datos_con_keys as $insertar) {
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')->insert($insertar);
            }
    
            $mensajes = array(
                "parametro" => 'inserto_informacion',
                "mensaje" => 'Información guardada satisfactoriamente.'
            ); 
        }

        // Validación: No se inserta datos y selecciona el checkbox de No aporta documentos
        if ($parametro == "no_aporta") {

            $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Id_Documento_Solicitado', 'Aporta_documento')
            ->where([['ID_evento', $request->Id_evento],['Id_Asignacion', $request->Id_Asignacion], ['Estado', 'Inactivo'], ['Aporta_documento', 'No']])
            ->get();

            if (count($dato_validacion_no_aporta_docs)> 0) {
                $mensajes = array(
                    "parametro" => 'replicando_no_aporta',
                    "mensaje" => 'No puede registrar esta opción de nuevo.'
                );
            }else{
                $insertar = [
                    'ID_evento' => $request->Id_evento,
                    'Id_Asignacion' => $request->Id_Asignacion,
                    'Id_proceso' => $request->Id_proceso,
                    //'F_solicitud_documento' => "",
                    'Id_Documento' => 0,
                    'Nombre_documento' => "N/A",
                    'Descripcion' => "N/A",
                    'Id_solicitante' => 0,
                    'Nombre_solicitante' => "N/A",
                    //'F_recepcion_documento' => "",
                    'Aporta_documento' => "No",
                    'Estado' => "Inactivo",
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date
                ];
             
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')->insert($insertar);
                $mensajes = array(
                    "parametro" => 'inserto_informacion',
                    "mensaje" => 'Información guardada satisfactoriamente.'
                );

            }

        }

        return json_decode(json_encode($mensajes, true));

    }
    //Captura de datos para insertar el comunicado Orige

    public function captuarDestinatariosPrincipalJuntas(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        $nombreusuario = Auth::user()->name; 
        $destinatarioPrincipal = $request->destinatarioPrincipal;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso; 

        switch (true) {
            case ($destinatarioPrincipal == 'Afiliado'):                
                $array_datos_destinatarios = DB::select('CALL psrcomunicados(?)', array($newIdEvento)); 
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();  
                
                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_destinatarios' => $array_datos_destinatarios,
                    'array_datos_lider' => $array_datos_lider
                ]);
            break;
            case ($destinatarioPrincipal == 'Empresa'):                
                $array_datos_destinatarios = DB::select('CALL psrcomunicados(?)', array($newIdEvento)); 
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();  
                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_destinatarios' => $array_datos_destinatarios,                    
                    'array_datos_lider' => $array_datos_lider

                ]);
            break;
            case ($destinatarioPrincipal == 'Otro'):  
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();  
                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_lider' => $array_datos_lider
                ]);
            break;
                         
            default:                
            break;
        }

    }
    //Guardar Comunicado Desde cero
    public function guardarComunicadoJuntas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Id_evento = $request->Id_evento;
        $Id_asignacion = $request->Id_asignacion;
        $Id_procesos = $request->Id_procesos;
        $radioafiliado_comunicado = $request->radioafiliado_comunicado;
        $radioempresa_comunicado = $request->radioempresa_comunicado;
        $radioOtro = $request->radioOtro;
        $copiaComunicadoTotal = $request->copiaComunicadoTotal;
        if (!empty($copiaComunicadoTotal)) {
            $total_copia_comunicado = implode(", ", $copiaComunicadoTotal);                
        }else{
            $total_copia_comunicado = '';
        }

        if(!empty($radioafiliado_comunicado) && empty($radioempresa_comunicado) && empty($radioOtro)){
            $destinatario = 'Afiliado';
        }elseif(empty($radioafiliado_comunicado) && !empty($radioempresa_comunicado) && empty($radioOtro)){
            $destinatario = 'Empresa';
        }elseif(empty($radioafiliado_comunicado) && empty($radioempresa_comunicado) && !empty($radioOtro)){
            $destinatario = 'Otro';
        }
        $datos_info_registrarComunicadoPcl=[

            'ID_evento' => $Id_evento,
            'Id_Asignacion' => $Id_asignacion,
            'Id_proceso' => $Id_procesos,
            'Ciudad' => $request->ciudad,
            'F_comunicado' => $request->fecha_comunicado2,
            'N_radicado' => $request->radicado2,
            'Cliente' => $request->cliente_comunicado2,
            'Nombre_afiliado' => $request->nombre_afiliado_comunicado2,
            'T_documento' => $request->tipo_documento_comunicado2,
            'N_identificacion' => $request->identificacion_comunicado2,
            'Destinatario' => $destinatario,
            'Nombre_destinatario' => $request->nombre_destinatario,
            'Nit_cc' => $request->nic_cc,
            'Direccion_destinatario' => $request->direccion_destinatario,
            'Telefono_destinatario' => $request->telefono_destinatario,
            'Email_destinatario' => $request->email_destinatario,
            'Id_departamento' => $request->departamento_destinatario,
            'Id_municipio' => $request->ciudad_destinatario,
            'Asunto' => $request->asunto,
            'Cuerpo_comunicado' => $request->cuerpo_comunicado,
            'Anexos' => $request->anexos,
            'Forma_envio' => $request->forma_envio,
            'Elaboro' => $request->elaboro2,
            'Reviso' => $request->reviso,
            'Agregar_copia' => $total_copia_comunicado,
            'Firmar_Comunicado' => $request->firmarcomunicado,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];
        
        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_registrarComunicadoPcl);

        sleep(2);
        $datos_info_historial_acciones = [
            'ID_evento' => $Id_evento,
            'F_accion' => $date,
            'Nombre_usuario' => $nombre_usuario,
            'Accion_realizada' => "Se genera comunicado Juntas.",
            'Descripcion' => $request->asunto,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        
        $mensajes = array(
            "parametro" => 'agregar_comunicado',
            "mensaje" => 'Comunicado generado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }
    //Historial Comunicado
    public function historialComunicadosJuntas(Request $request){

        $HistorialComunicadosOrigen = $request->HistorialComunicadosOrigen;
        $newId_evento = $request->newId_evento;
        $newId_asignacion = $request->newId_asignacion;        
        if ($HistorialComunicadosOrigen == 'CargarComunicados') {
            
            $hitorialAgregarComunicado = cndatos_info_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $newId_evento],
                ['Id_proceso', '3']
            ])
            ->get();
            $arrayhitorialAgregarComunicado = json_decode(json_encode($hitorialAgregarComunicado, true));
            return response()->json(($arrayhitorialAgregarComunicado));

        }
        
    }
    //Mostrar datos de comunicado edición
    public function mostrarModalComunicadoJuntas(Request $request){

        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;        
        $destinatario_principal_comu = $request->destinatario_principal;
        $id_evento = $request->id_evento;
        $id_asignacion = $request->id_asignacion;
        $id_proceso = $request->id_proceso;
        $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
        ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
        ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
        ->where([['sgt.Id_proceso_equipo', '=', $id_proceso]])->get();

        return response()->json([
            'destinatario_principal_comu' => $destinatario_principal_comu,
            'array_datos_lider' => $array_datos_lider,
        ]);
        
    }
    //Actualizar Comunicado
    public function actualizarComunicadoJuntas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Id_comunicado_editar = $request->Id_comunicado_editar;
        $Id_evento_editar = $request->Id_evento_editar;
        $Id_asignacion_editar = $request->Id_asignacion_editar;
        $Id_procesos_editar = $request->Id_procesos_editar;
        $radioafiliado_comunicado_editar = $request->radioafiliado_comunicado_editar;
        $radioempresa_comunicado_editar = $request->radioempresa_comunicado_editar;
        $radioOtro_editar = $request->radioOtro_editar;
        $copiaComunicadoTotal = $request->agregar_copia_editar;
        if (!empty($copiaComunicadoTotal)) {
            $total_copia_comunicado = implode(", ", $copiaComunicadoTotal);                
        }else{
            $total_copia_comunicado = '';
        }

        if(!empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar) && empty($radioOtro_editar)){
            $destinatario = 'Afiliado';
        }elseif(empty($radioafiliado_comunicado_editar) && !empty($radioempresa_comunicado_editar) && empty($radioOtro_editar)){
            $destinatario = 'Empresa';
        }elseif(empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar) && !empty($radioOtro_editar)){
            $destinatario = 'Otro';
        }

        $datos_info_actualizarComunicadoPcl=[

            'ID_evento' => $Id_evento_editar,
            'Id_Asignacion' => $Id_asignacion_editar,
            'Id_proceso' => $Id_procesos_editar,
            'Ciudad' => $request->ciudad_editar,
            'F_comunicado' => $request->fecha_comunicado2_editar,
            'N_radicado' => $request->radicado2_editar,
            'Cliente' => $request->cliente_comunicado2_editar,
            'Nombre_afiliado' => $request->nombre_afiliado_comunicado2_editar,
            'T_documento' => $request->tipo_documento_comunicado2_editar,
            'N_identificacion' => $request->identificacion_comunicado2_editar,
            'Destinatario' => $destinatario,
            'Nombre_destinatario' => $request->nombre_destinatario_editar,
            'Nit_cc' => $request->nic_cc_editar,
            'Direccion_destinatario' => $request->direccion_destinatario_editar,
            'Telefono_destinatario' => $request->telefono_destinatario_editar,
            'Email_destinatario' => $request->email_destinatario_editar,
            'Id_departamento' => $request->departamento_destinatario_editar,
            'Id_municipio' => $request->ciudad_destinatario_editar,
            'Asunto' => $request->asunto_editar,
            'Cuerpo_comunicado' => $request->cuerpo_comunicado_editar,
            'Anexos' => $request->anexos_editar,
            'Forma_envio' => $request->forma_envio_editar,
            'Elaboro' => $request->elaboro2_editar,
            'Reviso' => $request->reviso_editar,
            'Agregar_copia' => $total_copia_comunicado,
            'Firmar_Comunicado' => $request->firmarcomunicado,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];

        sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado_editar)
        ->update($datos_info_actualizarComunicadoPcl);

        sleep(2);
        $datos_info_historial_acciones = [
            'ID_evento' => $Id_evento_editar,
            'F_accion' => $date,
            'Nombre_usuario' => $nombre_usuario,
            'Accion_realizada' => "Se actualiza comunicado Juntas ATEL.",
            'Descripcion' => $request->asunto_editar,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        
        $mensajes = array(
            "parametro" => 'actualizar_comunicado',
            "mensaje" => 'Comunicado actualizado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    // Insercion agregar seguimiento
    public function guardarAgregarSeguimientoJuntas(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;
        $fecha_seguimiento = $request->fecha_seguimiento;
        $causal_seguimiento = $request->causal_seguimiento;
        $descripcion_seguimiento = $request->descripcion_seguimiento;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;

        $datos_info_causalSeguimiento = [
            'ID_evento' => $newIdEvento,
            'Id_Asignacion' => $newIdAsignacion,
            'Id_proceso' => $Id_proceso,
            'F_seguimiento' => $fecha_seguimiento,
            'Causal_seguimiento' => $causal_seguimiento,
            'Descripcion_seguimiento' => $descripcion_seguimiento,
            'Nombre_usuario'=> $usuario,
            'F_registro'=> $date,
        ];

        sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')->insert($datos_info_causalSeguimiento);
        
        sleep(2);
        $datos_info_historial_acciones = [
            'ID_evento' => $newIdEvento,
            'F_accion' => $date,
            'Nombre_usuario' => $usuario,
            'Accion_realizada' => "Se agrego seguimiento Juntas.",
            'Descripcion' => $descripcion_seguimiento,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);

        $mensajes = array(
            "parametro" => 'agregar_seguimiento',
            "mensaje" => 'Seguimiento agregado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }
}
