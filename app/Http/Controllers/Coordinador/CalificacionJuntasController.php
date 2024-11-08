<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use App\Models\cndatos_comunicado_eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_lista_departamentos_municipios;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_laboral_eventos;
use App\Models\sigmel_registro_descarga_documentos;
use App\Models\sigmel_registro_documentos_eventos;
use App\Models\sigmel_lista_documentos;

use App\Models\sigmel_clientes;
use App\Models\sigmel_informacion_acciones_automaticas_eventos;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_informacion_historial_accion_eventos;
use App\Models\sigmel_auditorias_informacion_accion_eventos;
use App\Models\sigmel_informacion_expedientes_eventos;
use App\Models\sigmel_numero_orden_eventos;
use App\Services\GlobalService;
use App\Traits\GenerarRadicados;

use DateTime;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Image;

class CalificacionJuntasController extends Controller
{
    use GenerarRadicados;

    protected $globalService;

    public function __construct(GlobalService $globalService)
    {
        $this->globalService = $globalService;
    }

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
        if ($request->Id_Servicio <> "") {
            $Id_servicio = $request->Id_Servicio;
        } else {
            $Id_servicio = $request->newIdServicio;
        }

        $array_datos_calificacionJuntas = DB::select('CALL psrcalificacionJuntas(?)', array($newIdAsignacion));
        //Trae Documetos Generales del evento
        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?,?,?)',array($newIdEvento, $Id_servicio, $newIdAsignacion));

        // cantidad de documentos cargados

        $cantidad_documentos_cargados = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $newIdEvento],
            ['Id_servicio', $Id_servicio]
        ])->get();

        // Consulta Informacion de afiliado
        $arrayinfo_afiliado= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as Afi')
        ->select('Afi.Direccion','ci.Nombre_municipio','ci.Nombre_departamento','Afi.Telefono_contacto','Afi.Email','Afi.F_actualizacion')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as ci', 'Afi.Id_municipio', '=', 'ci.Id_municipios')
        ->where('Afi.ID_evento',  '=', $newIdEvento)
        ->get();
        // Trae informacion de controversia_juntas
        $arrayinfo_controvertido= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_controversia_juntas_eventos as j')
        ->select('j.ID_evento','j.Enfermedad_heredada','j.F_transferencia_enfermedad','j.Primer_calificador','pa.Nombre_parametro as Calificador'
        ,'j.Nom_entidad','j.N_dictamen_controvertido','j.F_dictamen_controvertido','j.N_siniestro','j.F_notifi_afiliado','j.Parte_controvierte_califi','pa2.Nombre_parametro as ParteCalificador','j.Nombre_controvierte_califi',
        'j.N_radicado_entrada_contro','j.Contro_origen','j.Contro_pcl','j.Contro_diagnostico','j.Contro_f_estructura','j.Contro_m_califi',
        'j.F_contro_primer_califi','j.F_contro_radi_califi','j.Termino_contro_califi','j.Jrci_califi_invalidez','sie.Nombre_entidad as JrciNombre','j.F_envio_jrci', 'j.F_envio_jnci','j.F_plazo_controversia','j.Observaciones')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa', 'j.Primer_calificador', '=', 'pa.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa2', 'j.Parte_controvierte_califi', '=', 'pa2.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'j.Jrci_califi_invalidez', '=', 'sie.Id_Entidad')
        ->where([['j.ID_evento',  '=', $newIdEvento],['j.Id_Asignacion', $newIdAsignacion]])
        ->get();

        //Trae Pago de Honorarios 
        $arrayinfo_pagos= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_pagos_honorarios_eventos as p')
        ->select('p.Tipo_pago','pa.Nombre_parametro as NomPago','p.F_solicitud_pago','pa2.Nombre_parametro as JuntaPago'
        ,'p.N_orden_pago','p.Valor_pagado','p.F_pago_honorarios','p.F_pago_radicacion')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa', 'p.Tipo_pago', '=', 'pa.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as pa2', 'p.Pago_junta', '=', 'pa2.Id_Parametro')
        ->where([['p.ID_evento',  '=', $newIdEvento],['p.Id_Asignacion', $newIdAsignacion]])
        ->get();

        //Trae Listado de documentos
        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where([
            ['Estado', 'Activo'], ['Id_proceso',$array_datos_calificacionJuntas[0]->Id_proceso],
            ['ID_evento', $newIdEvento],
            ['Id_Asignacion', $newIdAsignacion]
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
       $consecutivo = $this->config(['prefix' => 'SAL-JUN'])->getRadicado('juntas',$newIdEvento);

       //Historial De Seguimientos
       $hitorialAgregarSeguimiento = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->select('ID_evento','F_seguimiento','Causal_seguimiento','Descripcion_seguimiento','Nombre_usuario')
            ->where([
                ['ID_evento', $newIdEvento],
                ['Id_Asignacion', $newIdAsignacion],
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
        
        //Se agregar principalmente por la ficha psb026, desdepues de la implementacion deberia ser auto. a traves del trigger, para los registros anteriores se agregar las siguientes lineas, el cual anexa la fecha de plazo
        if(!empty($arrayinfo_controvertido[0]->F_notifi_afiliado) && empty($arrayinfo_controvertido[0]->F_plazo_controversia)){
            $fecha_controversia = calcularDiasHabiles($arrayinfo_controvertido[0]->F_notifi_afiliado);

            // $arrayinfo_controvertido[0]->F_plazo_controversia = $fecha_controversia[0]->Fecha;
            $arrayinfo_controvertido[0]->F_plazo_controversia = $fecha_controversia;
        }

        // Validar si la accion ejecutada tiene enviar a notificaciones            
        $enviar_notificaciones = BandejaNotifiController::evento_en_notificaciones($newIdEvento,$newIdAsignacion);

        //Traer el N_siniestro del evento
        $N_siniestro_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('N_siniestro')
        ->where([['ID_evento',$newIdEvento]])
        ->get();

        $info_evento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'sie.Tipo_evento')
        ->select('sie.ID_evento', 'sie.Tipo_evento', 'slte.Nombre_evento', 'sie.F_evento')
        ->where('sie.ID_evento', $newIdEvento)
        ->first();
        
        $validar_lista_chequeo = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $newIdEvento],
            ['Id_servicio', $Id_servicio],
            ['Nombre_documento', 'Lista_chequeo'],
        ])->get();

        $info_cuadro_expedientes = sigmel_informacion_expedientes_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $newIdEvento],
            ['Id_servicio', $Id_servicio],
            ['Nombre_documento', 'Lista_chequeo'],
        ])->get();

        return view('coordinador.calificacionJuntas', compact('user','array_datos_calificacionJuntas','arraylistado_documentos', 'cantidad_documentos_cargados', 'arrayinfo_afiliado',
        'arrayinfo_controvertido','arrayinfo_pagos','listado_documentos_solicitados','dato_validacion_no_aporta_docs',
        'arraycampa_documento_solicitado','consecutivo','hitorialAgregarSeguimiento','SubModulo', 'Id_servicio','enviar_notificaciones', 'N_siniestro_evento', 'info_evento', 'validar_lista_chequeo', 'info_cuadro_expedientes'));
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

        //Lista tipo evento
        if($parametro == "lista_tipo_evento_juntas"){
            $datos_tipo_evento = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
                ->select('Id_Evento','Nombre_evento')
                ->where('Estado', '=', 'activo')
                ->get();

            $informacion_datos_tipo_evento = json_decode(json_encode($datos_tipo_evento, true));
            return response()->json($informacion_datos_tipo_evento);
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
        //Listado fuentes de informacion
        if($parametro == 'lista_fuente_informacion'){
            $listado_fuente_info = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Fuente de Información'],
                ['Estado', '=', 'activo'],
            ])->whereIn('Nombre_Parametro',$request->opciones)->get();

            return json_decode(json_encode($listado_fuente_info, true));

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
        // Obtenemos la parte que controvierte
        if($parametro == 'info_afiliado'){
            $controvierte = DB::select( 'SELECT ' . getDatabaseName('sigmel_gestiones') ."ObtenerControvierte(:controvierte,:evento) as Nombre",[
                'controvierte' => $request->controvierte,
                'evento' => $request->evento,
            ]);
            return response()->json($controvierte[0]);
        }
        // Listado Junta Jrci Invalidez
        if($parametro == 'lista_juntas_invalidez'){
            // $listado_juntas_invalidez = sigmel_lista_parametros::on('sigmel_gestiones')
            // ->select('Id_Parametro', 'Nombre_parametro')
            // ->where([
            //     ['Tipo_lista', '=', 'Jrci Invalidez'],
            //     ['Estado', '=', 'activo']
            // ])
            // ->get();
            $listado_juntas_invalidez = sigmel_informacion_entidades::on('sigmel_gestiones')
            ->select('Id_Entidad as Id_Parametro', 'Nombre_entidad as Nombre_parametro')
            ->where([
                ['IdTipo_entidad', '4'],
                ['Estado_entidad', 'activo']
            ])
            ->get();

            // echo "<pre>";
            // print_r($listado_juntas_invalidez);
            // echo "</pre>";

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

        // listado de profesionales segun proceso
        if ($parametro == 'lista_profesional_proceso') {
            $id_proceso_asignacion = $request->id_proceso;
            $lista_profesional_proceso = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET($id_proceso_asignacion, id_procesos_usuario) > 0")->get();

            $info_lista_profesional_proceso = json_decode(json_encode($lista_profesional_proceso, true));
            return response()->json($info_lista_profesional_proceso);
        }

        // listado de profesionales segun la acción a realizar
        if ($parametro == 'lista_profesional_accion') {
            if ($request->Id_cliente == "") {
                $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
                ->select('Cliente')->where('ID_evento', $request->nro_evento)->first();

                $id_cliente = $array_id_cliente["Cliente"];
            } else {
                $id_cliente = $request->Id_cliente;
            }
            
            $id_proceso = $request->Id_proceso;
            $id_servicio = $request->Id_servicio;
            $id_accion = $request->Id_accion;

            /* Extraemos el equippo de trabajo y el profesional asignado configurados en la paramétrica */
            $info_equipo_prof_asig = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Equipo_trabajo', 'sipc.Profesional_asignado')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $id_proceso],
                ['sipc.Servicio_asociado', '=', $id_servicio],
                ['sipc.Accion_ejecutar', '=', $id_accion]
            ])->get();

            /* Si el profesional asignado está configurado entonces el listado de profesionales
            se cargará con los usuarios que pertenecen al equipo de trabajo configurado en la paramétrica */
            if($info_equipo_prof_asig[0]->Profesional_asignado <> ""){
                $listado_profesionales = DB::table('users as u')
                ->leftJoin('sigmel_gestiones.sigmel_usuarios_grupos_trabajos as sugt', 'u.id', '=', 'sugt.id_usuarios_asignados')
                ->select('u.id', 'u.name')
                ->where([['sugt.id_equipo_trabajo', $info_equipo_prof_asig[0]->Equipo_trabajo]])
                ->get();

                $info_listado_profesionales = json_decode(json_encode($listado_profesionales, true));
                return response()->json([
                    'info_listado_profesionales' => $info_listado_profesionales,
                    'Profesional_asignado' => $info_equipo_prof_asig[0]->Profesional_asignado
                ]);
            }else{
                $lista_profesional_proceso = DB::table('users')->select('id', 'name')
                ->where('estado', 'Activo')
                ->whereRaw("FIND_IN_SET($id_proceso, id_procesos_usuario) > 0")->get();

                $info_lista_profesional_proceso = json_decode(json_encode($lista_profesional_proceso, true));
                // return response()->json($info_lista_profesional_proceso);
                return response()->json([
                    'info_listado_profesionales' => $info_lista_profesional_proceso,
                    'Profesional_asignado' => ''
                ]);
            }
        }

        if($parametro == "listado_accion"){
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
                ['sipc.Modulo_principal', '=', 'Si'],
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

        if ($parametro == "lista_tipos_docs") {
            // $datos_tipos_documentos_familia = sigmel_lista_documentos::on('sigmel_gestiones')
            // ->select('Nro_documento', 'Nombre_documento')
            // ->get();

            $datos_tipos_documentos_familia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_lista_documentos as sld')
            ->leftJoin('sigmel_gestiones.sigmel_registro_documentos_eventos as srde', 'sld.Id_Documento', '=', 'srde.Id_Documento')
            ->select('sld.Nro_documento', 'sld.Nombre_documento')
            ->where([
                ['srde.ID_evento', $request->evento],
                ['srde.Id_servicio', $request->servicio],
                ['sld.Estado', 'activo']
            ])
            ->groupBy('sld.Nro_documento')
            ->get();

            $info_datos_tipos_documentos_familia = json_decode(json_encode($datos_tipos_documentos_familia, true));
            return response()->json($info_datos_tipos_documentos_familia);
        }

        if($parametro == "listado_documentos"){
            $match = ['Orden de pago honorarios', 'Dictamen de Calificación', 'Notificación al usuario', 'Apelación al dictamen', 'Anexo G (Datos Generales)', 'Fotocopia Documento Identidad', 'Autorización historia clínica', 'Historia clínica completa', 'Concepto de rehabilitación', 'Conceptos o recomendaciones y/o restricciones ocupacionales', 'Registro civil de defunción', 'Acta de levantamiento del cadáver', 'Protocolo de necropsia', 'Exámenes complementarios', 'Relación de incapacidades', 'Dictamen Junta Regional', 'Origen de la patología', 'Guía Afiliado', 'Guía Empleador', 'Guía ARL', 'Guía AFP', 'Guía EPS', 'Lista de chequeo'];

            if (empty($request->Id_servicio)) {
                return;
            }
            $Id_servicio = $request->Id_servicio;
            $newIdEvento = $request->Id_evento;
            $documentos = DB::select('CALL psrvistadocumentos(?,?)',array((string)$newIdEvento , (int) $Id_servicio));
        
            $lista_chequeo = [];

            foreach($documentos as $documento){
                if(in_array($documento->Nombre_documento,$match))
                    $lista_chequeo[] = [
                        'id_Registro_Documento' => $documento->id_Registro_Documento,
                        'Id_Documento' => $documento->Id_Documento,
                        'doc_nombre' => $documento->Nombre_documento,
                        'archivo' => $documento->nombre_Documento,
                        'ext' => $documento->formato_documento,
                        'status_doc' => $documento->estado_documento,
                        'check' => $documento->Lista_chequeo
                    ];
            }

            // Creamos un array asociativo para obtener los índices de $match
            $matchIndex = array_flip($match);

            // Función de comparación para ordenar según el orden de $match
            usort($lista_chequeo, function($a, $b) use ($matchIndex) {
                return $matchIndex[$a['doc_nombre']] - $matchIndex[$b['doc_nombre']];
            });  
            
            // dd($lista_chequeo);            

            // Reorganizar los documentos de homologación según pbs 062 
            // Array para almacenar los documentos con el mismo nombre
            $documentos_repetidos = [];
            // lista para almacenar documentos únicos
            $nueva_lista_chequeo = []; 

            // Iterar sobre el array original
            foreach ($lista_chequeo as $key => $chequeo) {
                $nombre = $chequeo['doc_nombre'];
                $repetido = false;
                // Buscar otros documentos con el mismo nombre
                for ($i = $key + 1; $i < count($lista_chequeo); $i++) {
                    if ($lista_chequeo[$i]['doc_nombre'] === $nombre) {
                        // Guardar el documento repetido
                        $documentos_repetidos[] = $lista_chequeo[$i];
                        // Marcar este documento como repetido
                        $repetido = true;
                        break;
                    }
                }

                // Si no es repetido, agregarlo a la nueva lista
                if (!$repetido) {
                    $nueva_lista_chequeo[] = $chequeo;
                }
            }

            // Al final, reemplazar la lista original con la lista sin repetidos
            $lista_chequeo = array_merge($nueva_lista_chequeo, $documentos_repetidos);

            // dd($lista_chequeo);

            //Comunicados 
            /* $comunicados = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos')
            ->select('Asunto as Nombre','Id_Comunicado', 'Lista_chequeo')
            ->where([
                ['ID_evento', $request->Id_evento],
                ['Id_Asignacion', $request->Id_asignacion],
                ['Asunto', 'not like', '%Lista_chequeo%'],
                ['Modulo_creacion', 'calificacionJuntas']
            ])->get()->toArray();

            foreach($comunicados as $comunicado){
                $lista_chequeo['comunicados'][] = [
                    'nombre' =>preg_replace("/\.[^.]*$/", '', $comunicado->Nombre), //Le quitamos cualquier ext al archivo
                    'Id_Comunicado' => $comunicado->Id_Comunicado,
                    'Lista_chequeo' => $comunicado->Lista_chequeo,
                ];
            } */

            return response()->json($lista_chequeo);
        }

        //Listado bandejas de destino
        if($parametro == 'lista_bandejas_destino'){            

            $request->validate([
                'Id_proceso'=> 'required',
                'Id_cliente' => 'required',
                'Id_servicio' => 'required',
                'Id_accion' => 'required'
            ]);
            //Caso cuando en la parametrica hay un 'enviar a' para la accion a ejecutar.
            $lista_bandejas_destino = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps2', 'sipc.Bandeja_trabajo_destino', '=', 'slps2.Id_proceso')
            ->select('slps2.Nombre_proceso as Nombre_proceso','sipc.Bandeja_trabajo_destino as bd_destino')
            ->where([
                ['sipc.Id_proceso',$request->Id_proceso], 
                ['sipc.Id_cliente',$request->Id_cliente],
                ['sipc.Servicio_asociado',$request->Id_servicio],
                ['sipc.Accion_ejecutar',$request->Id_accion]
            ])->first();            
            
            if(empty($lista_bandejas_destino->Nombre_proceso)){
                $lista_bandejas_destino = [
                    'Nombre_proceso' => "NO ESTA DEFINIDO",
                    'bd_destino' => 0
                ];
            }

            $lista_bandejas_destino = json_decode(json_encode($lista_bandejas_destino, true));
            return response()->json($lista_bandejas_destino);
        }
    }

    //Guardar informacion del modulo de Juntas
    public function guardarCalificacionJuntas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $date_time = date("Y-m-d H:i:s");
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        $Id_servicio = $request->Id_servicio;
        $Accion_realizar = $request->accion;

        // F de asignación para pronunciamiento de Juntas
        if ($Accion_realizar == 155 ) {
            $F_asignacion_pronu_juntas = $date_time;
        }else{
            // $F_asignacion_pronu_juntas = "0000-00-00 00:00:00";
            $F_asignacion_pronu_juntas = null;
        }

        // Programación para la Nueva Fecha de Radicación
        if ($request->nueva_fecha_radicacion <> "") {
            $Nueva_fecha_radicacion = $request->nueva_fecha_radicacion;
        } else {
            $Nueva_fecha_radicacion = null;
        }

        $n_ordenNotificacion = DB::table(getDatabaseName('sigmel_gestiones') . "sigmel_informacion_asignacion_eventos")
        ->select('N_de_orden')->where('Id_Asignacion', $newIdAsignacion)->get()->first();

        // validacion de bandera para guardar o actualizar
        if ($request->banderaguardar == 'Guardar') {

            // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
            // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
            // en la creación de un evento
            // MAURO PARAMETRICA
            $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')->where('ID_evento', $newIdEvento)->first();

            $id_cliente = $array_id_cliente["Cliente"];

            $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Estado','sipc.Enviar_a_bandeja_trabajo_destino as enviarA')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar','=',  $request->accion]
            ])->get();



            //Asignamos #n de orden cuado se envie un caso a notificaciones
            if(!empty($estado_acorde_a_parametrica[0]->enviarA) && $estado_acorde_a_parametrica[0]->enviarA != 'No'){
                //Trae El numero de orden actual
                $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
                ->select('Numero_orden')
                ->get();

                BandejaNotifiController::finalizarNotificacion($newIdEvento,$newIdAsignacion,false);
                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? $n_orden[0]->Numero_orden;
            }else{
                BandejaNotifiController::finalizarNotificacion($newIdEvento,$newIdAsignacion,true);

                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? null;
            }

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

            //Captura de datos para el id y nombre del profesional

            $id_profesional = $request->profesional;

            if (!empty($id_profesional)) {
                $nombre_profesional = DB::table('users')->select('id', 'name')
                ->where('id',$id_profesional)->get();   
                
                if (count($nombre_profesional) > 0) {
                    $asignacion_profesional = $nombre_profesional[0]->name;                    
                }
                
            } else {
                $id_profesional = null;
                $asignacion_profesional = null;                    
            }

            // insercion de datos a la tabla de sigmel_informacion_accion_eventos
            $datos_info_registrarCalifcacionJuntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
                // 'F_accion' => $request->f_accion,
                'F_accion' => $date_time,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Estado_Facturacion' => $request->estado_facturacion,
                'Causal_devolucion_comite' => 'N/A',
                'Descripcion_accion' => $request->descripcion_accion,
                'F_cierre' => $request->fecha_cierre,
                'Nombre_usuario' => $nombre_usuario,
                'F_asignacion_pronu_juntas' => $F_asignacion_pronu_juntas,
                'Fuente_informacion' => $request->fuente_info_juntas,
                'F_registro' => $date,
            ];

            $Id_Accion_eventos = sigmel_informacion_accion_eventos::on('sigmel_gestiones')->insertGetId($datos_info_registrarCalifcacionJuntas);

            // Realizamos la inserción a la tabla de auditoria sigmel_auditorias_informacion_accion_eventos
            $aud_datos_info_registrarCalifcacionJuntas= [
                'Aud_ID_evento' => $request->newId_evento,
                'Aud_Id_Asignacion' => $request->newId_asignacion,
                'Aud_Id_proceso' => $request->Id_proceso,
                'Aud_Modalidad_calificacion' => 'N/A',
                'Aud_F_accion' => $date_time,
                'Aud_Accion' => $request->accion,
                'Aud_F_Alerta' => $request->fecha_alerta,
                'Aud_Enviar' => $request->enviar,
                'Aud_Estado_Facturacion' => $request->estado_facturacion,
                'Aud_Causal_devolucion_comite' => 'N/A',
                'Aud_Descripcion_accion' => $request->descripcion_accion,
                'Aud_F_cierre' => $request->fecha_cierre,
                'Aud_Nombre_usuario' => $nombre_usuario,
				'Aud_F_asignacion_pronu_juntas' => $F_asignacion_pronu_juntas,
                'Aud_Fuente_informacion' => $request->fuente_info_juntas,
                'Aud_F_registro' => $date,
            ];
            sigmel_auditorias_informacion_accion_eventos::on('sigmel_auditorias')->insert($aud_datos_info_registrarCalifcacionJuntas);

            // Capturar el id accion para validar la accion que se acabo de guardar
            $info_accion_evento = sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->select('Accion', 'F_accion')
            ->where([
                ['Id_Accion', $Id_Accion_eventos],
            ])
            ->get();
            // accion a realizar
            $AccionEvento = $info_accion_evento[0]->Accion;            
            // captura de movimiento automatico, tiempo de movimiento (dias) y accion automatica segun la accion a realizar 
            // segun al servicio asosciado
            $info_accion_automatica = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->select('Movimiento_automatico','Tiempo_movimiento','Accion_automatica')
            ->where([
                ['Accion_ejecutar', $AccionEvento],
                ['Id_cliente', $id_cliente],
                ['Id_proceso', $Id_proceso],
                ['Servicio_asociado', $Id_servicio],
                ['Status_parametrico', 'Activo']
            ])->get();
            $Movimiento_automatico = $info_accion_automatica[0]->Movimiento_automatico;
            $Tiempo_movimiento = $info_accion_automatica[0]->Tiempo_movimiento;
            $Accion_automatica = $info_accion_automatica[0]->Accion_automatica;
            // case 1: si hay movimiento automatico, tiempo movimiento y accion automatica 
            // Case 2: Si hay movimiento automatico y tiempo movimiento pero no accion automatica
            // Case 3: Si hay movimiento automatico, accion automatica y no hay tiempo movimiento
            // Case 4: Si hay movimiento automatico y no hay tiempo movimiento y accion automatica
            switch (true) {
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and !empty($Tiempo_movimiento) and !empty($Accion_automatica)):
                        $info_datos_accion_automatica = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_parametrizaciones_clientes as sipc')
                        ->leftJoin('sigmel_sys.users as u', 'u.id', '=', 'sipc.Profesional_asignado')
                        ->select('sipc.Accion_ejecutar', 'sipc.Estado', 'sipc.Profesional_asignado', 'u.name')
                        ->where([
                            ['sipc.Accion_ejecutar', $Accion_automatica],
                            ['sipc.Id_cliente', $id_cliente],
                            ['sipc.Id_proceso', $Id_proceso],
                            ['sipc.Servicio_asociado', $Id_servicio],
                            ['sipc.Status_parametrico', 'Activo']
                        ])->get();
                        
                            $Accion_ejecutar_automatica = $info_datos_accion_automatica[0]->Accion_ejecutar;
                            $Profesional_asignado_automatico = $info_datos_accion_automatica[0]->Profesional_asignado;
                            $NombreProfesional_asignado_automatico = $info_datos_accion_automatica[0]->name;
                            $Id_Estado_evento_automatico = $info_datos_accion_automatica[0]->Estado;
                            
                            // Se suman los dias a la fecha actual para saber la fecha del movimiento automatico
                            $dateTime = new DateTime($date_time);
                            $dias = $Tiempo_movimiento; // Número de días que quieres sumar
                            $dateTime->modify("+$dias days");
                            $F_movimiento_automatico = $dateTime->format('Y-m-d');                            
                            
                            $array_info_datos_accion_automatica = [
                                'Id_Asignacion' => $newIdAsignacion,
                                'ID_evento' => $newIdEvento,
                                'Id_proceso' => $Id_proceso,
                                'Id_servicio' => $Id_servicio,
                                'Id_cliente' =>$id_cliente,
                                'Accion_automatica' => $Accion_ejecutar_automatica,
                                'Id_Estado_evento_automatico' => $Id_Estado_evento_automatico,
                                'F_accion' => $date_time,
                                'Id_profesional_automatico' => $Profesional_asignado_automatico,
                                'Nombre_profesional_automatico' => $NombreProfesional_asignado_automatico,
                                'F_movimiento_automatico' => $F_movimiento_automatico,
                                'Estado_accion_automatica' => 'Pendiente',
                                'Nombre_usuario' => $nombre_usuario,
                                'F_registro' => $date,

                            ];

                            sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_accion_automatica);
                            
                            $mensaje_2 = 'la acción parametrizada tiene una Acción automática y se ejecutará en '.$Tiempo_movimiento.' día(s)';
                        
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and !empty($Tiempo_movimiento) and empty($Accion_automatica)):
                        $mensaje_2 = 'la acción parametrizada tiene movimiento automático, Tiempo de moviemiento (Días) pero no cuenta con una Acción automática';
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and empty($Tiempo_movimiento) and !empty($Accion_automatica)):
                    $mensaje_2 = 'la acción parametrizada tiene movimiento automático, Acción automatica pero no cuenta con Tiempo de moviemiento (Días)';
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and empty($Tiempo_movimiento) and empty($Accion_automatica)):
                        $mensaje_2 = 'la acción parametrizada tiene movimiento automático, pero no cuenta con un Tiempo de moviemiento (Días) y Acción automática';
                    break;                
                default:       
                        $mensaje_2 = 'la acción parametrizada NO tiene Movimiento Automático';
                    break;
            }  
            sleep(2);

            // Actualizacion tabla sigmel_informacion_asignacion_eventos
            $datos_info_actualizarAsignacionEvento= [ 
                'Id_accion' => $request->accion,
                'Id_Estado_evento' => $Id_Estado_evento,             
                'F_alerta' => $request->fecha_alerta,
                'F_accion' => $date_time,
                'Id_profesional' => $id_profesional,
                'Nombre_profesional' => $asignacion_profesional,
                'Nueva_F_radicacion' => $Nueva_fecha_radicacion,
                'N_de_orden' => $N_orden_evento,     
                'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No', 
                'Nombre_usuario' => $nombre_usuario,
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                'F_registro' => $date,
            ];

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            sleep(2);

            $F_accionEvento = $info_accion_evento[0]->F_accion;
            $info_datos_alertar_accion_ejecutar = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->select('Tiempo_alerta', 'Porcentaje_alerta_naranja', 'Porcentaje_alerta_roja')
            ->where([
                ['Accion_ejecutar', $AccionEvento],
                ['Id_cliente', $id_cliente],
                ['Id_proceso', $Id_proceso],
                ['Servicio_asociado', $Id_servicio],
                ['Status_parametrico', 'Activo']
            ])
            ->get();
            $Tiempo_alerta = $info_datos_alertar_accion_ejecutar[0]->Tiempo_alerta;
            $Porcentaje_alerta_naranja = $info_datos_alertar_accion_ejecutar[0]->Porcentaje_alerta_naranja;
            $Porcentaje_alerta_roja = $info_datos_alertar_accion_ejecutar[0]->Porcentaje_alerta_roja;
            // case 1: Validar si hay tiempo de alerta para crear la nueva fecha de alerta segun la fecha de accion
                // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
            // case 2: Validar si hay tiempo de alerta y porcentaje de alerta naraja para crear la alerta naranja
                // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
                // formula AN = (TA*PN)/100 (AN= Alerta naranja, TA = tiempo de alerta y PN = porcentaje de alerta naranja)
            // case 3: Validar si hay tiempo de alerta y porcentaje de alerta roja para crear la alerta roja
                // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
                // formula AR = (TA*PR)/100 (AR= Alerta roja, TA = tiempo de alerta y PR = porcentaje de alerta roja)
            // case 4: Validar si hay tiempo de alerta, porcentaje de alerta naraja y porcentaje de alerta roja para crear todas las alertas
                // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
                // formula AN = (TA*PN)/100 (AN= Alerta naranja, TA = tiempo de alerta y PN = porcentaje de alerta naranja)
                // formula AR = (TA*PR)/100 (AR= Alerta roja, TA = tiempo de alerta y PR = porcentaje de alerta roja)
            switch (true) {
                case (!empty($Tiempo_alerta) and empty($Porcentaje_alerta_naranja) and empty($Porcentaje_alerta_roja)):
                        $Nueva_F_Alerta = new DateTime($F_accionEvento);
                        $horas = $Tiempo_alerta;
                        $minutosAdicionales = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta->modify("+$horas hours");
                        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                        
                        $infoNueva_F_AlertaEvento_accion = [
                            'F_Alerta' => $Nueva_F_AlertaEvento
                        ];

                        $infoNueva_F_AlertaEvento_asignacion = [
                            'F_alerta' => $Nueva_F_AlertaEvento
                        ];
                        
                        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_accion);

                        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_asignacion);
                    break;
                case (!empty($Tiempo_alerta) and !empty($Porcentaje_alerta_naranja)  and empty($Porcentaje_alerta_roja)):
                        $Nueva_F_Alerta = new DateTime($F_accionEvento);
                        $horas = $Tiempo_alerta;
                        $minutosAdicionales = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta->modify("+$horas hours");
                        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                        
                        $infoNueva_F_AlertaEvento_accion = [
                            'F_Alerta' => $Nueva_F_AlertaEvento
                        ];

                        $infoNueva_F_AlertaEvento_asignacion = [
                            'F_alerta' => $Nueva_F_AlertaEvento
                        ];
                        
                        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_accion);

                        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_asignacion);

                        $Alerta_Naranja = ($Tiempo_alerta * $Porcentaje_alerta_naranja) / 100;

                        $Nueva_F_Alerta_Naranja = new DateTime($F_accionEvento);
                        $horas = $Alerta_Naranja;
                        $minutosAdicionales_naranja = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta_Naranja->modify("+$horas hours");
                        $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
                        $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
                        $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');

                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $newIdAsignacion,
                            'ID_evento' => $newIdEvento,
                            'Id_proceso' => $Id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                            'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,                            
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => $nombre_usuario,
                            'F_registro' => $date,
                        ];

                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_alertas_automatica);
                        
                    break;
                case (!empty($Tiempo_alerta) and empty($Porcentaje_alerta_naranja) and !empty($Porcentaje_alerta_roja)):
                        $Nueva_F_Alerta = new DateTime($F_accionEvento);
                        $horas = $Tiempo_alerta;
                        $minutosAdicionales = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta->modify("+$horas hours");
                        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                        
                        $infoNueva_F_AlertaEvento_accion = [
                            'F_Alerta' => $Nueva_F_AlertaEvento
                        ];

                        $infoNueva_F_AlertaEvento_asignacion = [
                            'F_alerta' => $Nueva_F_AlertaEvento
                        ];
                        
                        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_accion);

                        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_asignacion);

                        $Alerta_Roja = ($Tiempo_alerta * $Porcentaje_alerta_roja) / 100;

                        $Nueva_F_Alerta_Roja = new DateTime($F_accionEvento);
                        $horas_roja = $Alerta_Roja;
                        $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;
                        $horas_roja = floor($horas_roja);
                        $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
                        $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
                        $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
                        $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');

                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $newIdAsignacion,
                            'ID_evento' => $newIdEvento,
                            'Id_proceso' => $Id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,                            
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => $nombre_usuario,
                            'F_registro' => $date,
                        ];

                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_alertas_automatica);                        

                    break;
                case (!empty($Tiempo_alerta) and !empty($Porcentaje_alerta_naranja) and !empty($Porcentaje_alerta_roja)):
                        $Nueva_F_Alerta = new DateTime($F_accionEvento);
                        $horas = $Tiempo_alerta;
                        $minutosAdicionales = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta->modify("+$horas hours");
                        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                        
                        $infoNueva_F_AlertaEvento_accion = [
                            'F_Alerta' => $Nueva_F_AlertaEvento
                        ];

                        $infoNueva_F_AlertaEvento_asignacion = [
                            'F_alerta' => $Nueva_F_AlertaEvento
                        ];
                        
                        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_accion);

                        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_asignacion);

                        $Alerta_Naranja = ($Tiempo_alerta * $Porcentaje_alerta_naranja) / 100;

                        $Nueva_F_Alerta_Naranja = new DateTime($F_accionEvento);
                        $horas_naranja = $Alerta_Naranja;
                        $minutosAdicionales_naranja = ($horas_naranja - floor($horas_naranja)) * 60;
                        $horas_naranja = floor($horas_naranja);
                        $Nueva_F_Alerta_Naranja->modify("+$horas_naranja hours");
                        $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
                        $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
                        $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');

                        $Alerta_Roja = ($Tiempo_alerta * $Porcentaje_alerta_roja) / 100;

                        $Nueva_F_Alerta_Roja = new DateTime($F_accionEvento);
                        $horas_roja = $Alerta_Roja;
                        $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;
                        $horas_roja = floor($horas_roja);
                        $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
                        $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
                        $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
                        $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');

                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $newIdAsignacion,
                            'ID_evento' => $newIdEvento,
                            'Id_proceso' => $Id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                            'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => $nombre_usuario,
                            'F_registro' => $date,
                        ];

                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_alertas_automatica);
                        
                    break;
                default:
                    
                    break;
            }
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

            // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos

            $datos_historial_accion_eventos = [
                'Id_Asignacion' => $newIdAsignacion,
                'ID_evento' => $newIdEvento,
                'Id_proceso' => $Id_proceso,
                'Id_servicio' => $Id_servicio,
                'Id_accion' => $Accion_realizar,
                'Descripcion' => $request->descripcion_accion,
                'F_accion' => $date_time,
                'Nombre_usuario' => $nombre_usuario,
            ];

            $idInsertado = sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')->insertGetId($datos_historial_accion_eventos);

            sleep(2);

            // Cargue de documento
            if($request->hasFile('cargue_documentos')){
                $archivo = $request->file('cargue_documentos');
                $path = public_path('Documentos_Eventos/'.$newIdEvento);
                $mode = 0777;
                $tipo_archivo = "Documento Historial Juntas";
                $nombre_documento = str_replace(' ', '_', $tipo_archivo);

                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode, true, true);
                    chmod($path, $mode);
                }

                $nombre_final_documento = $nombre_documento."$idInsertado"."_IdEvento_".$newIdEvento.".".$archivo->extension();
                Storage::putFileAs($newIdEvento, $archivo, $nombre_final_documento);
            }else{
                
                $nombre_final_documento='N/A';            
            }     

            // Insertar nombre documento
            
            $nombre_documento_historial = [                
                'Documento' => $nombre_final_documento,                
            ];

            sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
            ->where('Id_historial_accion',$idInsertado)->update($nombre_documento_historial);           

            sleep(2);
            
            $mensajes = array(
                "parametro" => 'agregarCalificacionJuntas',
                "parametro_1" => 'guardo',
                "mensaje_1" => 'Registro agregado satisfactoriamente.',
                "mensaje_2" => $mensaje_2
            );

            return json_decode(json_encode($mensajes, true));

        }elseif ($request->banderaguardar == 'Actualizar') {

            $datos_estado_acciones_automaticas = [
                'Estado_accion_automatica' => 'Ejecutada'
            ];

            sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)
            ->update($datos_estado_acciones_automaticas);

            $datos_estado_alertas_automaticas = [
                'Estado_alerta_automatica' => 'Finalizada'
            ];

            sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)
            ->update($datos_estado_alertas_automaticas);
            
            // Extraemos el id estado de la tabla de parametrizaciones dependiendo del
            // id del cliente, id proceso, id servicio, id accion. Este id irá como estado inicial
            // en la creación de un evento
            // MAURO PARAMETRICA
            $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')->where('ID_evento', $newIdEvento)->first();

            $id_cliente = $array_id_cliente["Cliente"];

            $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Estado','sipc.Enviar_a_bandeja_trabajo_destino as enviarA')
            ->where([
                ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $Id_proceso],
                ['sipc.Servicio_asociado', '=', $Id_servicio],
                ['sipc.Accion_ejecutar','=',  $request->accion]
            ])->get();

            //Asignamos #n de orden cuado se envie un caso a notificaciones, y habilitamos edicion de correspondencia en funcion de este
            if(!empty($estado_acorde_a_parametrica[0]->enviarA) && $estado_acorde_a_parametrica[0]->enviarA != 'No'){
                //Trae El numero de orden actual
                $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
                ->select('Numero_orden')
                ->get();

                BandejaNotifiController::finalizarNotificacion($newIdEvento,$newIdAsignacion,false);
                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? $n_orden[0]->Numero_orden;
            }else{
                BandejaNotifiController::finalizarNotificacion($newIdEvento,$newIdAsignacion,true);

                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? null;
            }

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

            //Captura de datos para el id y nombre del profesional

            $id_profesional = $request->profesional;

            if (!empty($id_profesional)) {
                $nombre_profesional = DB::table('users')->select('id', 'name')
                ->where('id',$id_profesional)->get();   
                
                if (count($nombre_profesional) > 0) {
                    $asignacion_profesional = $nombre_profesional[0]->name;                    
                }
                
            } else {
                $id_profesional = null;
                $asignacion_profesional = null;                    
            }

            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos
            $datos_info_registrarCalifcacionJuntas= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'Modalidad_calificacion' => 'N/A',
                // 'F_accion' => $request->f_accion,
                'F_accion' => $date_time,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Estado_Facturacion' => $request->estado_facturacion,
                'Causal_devolucion_comite' => 'N/A',
                'Descripcion_accion' => $request->descripcion_accion,
                'F_cierre' => $request->fecha_cierre,
                'Nombre_usuario' => $nombre_usuario,
                'F_asignacion_pronu_juntas' => $F_asignacion_pronu_juntas,
                'Fuente_informacion' => $request->fuente_info_juntas,
                'F_registro' => $date,
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_registrarCalifcacionJuntas);

            // Realizamos la inserción a la tabla de auditoria sigmel_auditorias_informacion_accion_eventos
            $aud_datos_info_registrarCalifcacionJuntas= [
                'Aud_ID_evento' => $request->newId_evento,
                'Aud_Id_Asignacion' => $request->newId_asignacion,
                'Aud_Id_proceso' => $request->Id_proceso,
                'Aud_Modalidad_calificacion' => 'N/A',
                'Aud_F_accion' => $date_time,
                'Aud_Accion' => $request->accion,
                'Aud_F_Alerta' => $request->fecha_alerta,
                'Aud_Enviar' => $request->enviar,
                'Aud_Estado_Facturacion' => $request->estado_facturacion,
                'Aud_Causal_devolucion_comite' => 'N/A',
                'Aud_Descripcion_accion' => $request->descripcion_accion,
                'Aud_F_cierre' => $request->fecha_cierre,
                'Aud_Nombre_usuario' => $nombre_usuario,
				'Aud_F_asignacion_pronu_juntas' => $F_asignacion_pronu_juntas,
                'Aud_Fuente_informacion' => $request->fuente_info_juntas,
                'Aud_F_registro' => $date,
            ];
            sigmel_auditorias_informacion_accion_eventos::on('sigmel_auditorias')->insert($aud_datos_info_registrarCalifcacionJuntas);

            //Capturar el id accion para validar la accion que se acabo de guardar
            $info_accion_evento = sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->select('Accion', 'F_accion')
            ->where([
                ['Id_Asignacion', $newIdAsignacion],
            ])
            ->get();
            // accion a realizar
            $AccionEvento = $info_accion_evento[0]->Accion;            
            // captura de movimiento automatico, tiempo de movimiento (dias) y accion automatica segun la accion a realizar 
            // segun al servicio asosciado
            $info_accion_automatica = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->select('Movimiento_automatico','Tiempo_movimiento','Accion_automatica')
            ->where([
                ['Accion_ejecutar', $AccionEvento],
                ['Id_cliente', $id_cliente],
                ['Id_proceso', $Id_proceso],
                ['Servicio_asociado', $Id_servicio],
                ['Status_parametrico', 'Activo']
            ])->get(); 
            $Movimiento_automatico = $info_accion_automatica[0]->Movimiento_automatico;
            $Tiempo_movimiento = $info_accion_automatica[0]->Tiempo_movimiento;
            $Accion_automatica = $info_accion_automatica[0]->Accion_automatica;
            // case 1: si hay movimiento automatico, tiempo movimiento y accion automatica 
            // Case 2: Si hay movimiento automatico y tiempo movimiento pero no accion automatica
            // Case 3: Si hay movimiento automatico, accion automatica y no hay tiempo movimiento
            // Case 4: Si hay movimiento automatico y no hay tiempo movimiento y accion automatica
            switch (true) {
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and !empty($Tiempo_movimiento) and !empty($Accion_automatica)):
                        $info_datos_accion_automatica = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_parametrizaciones_clientes as sipc')
                        ->leftJoin('sigmel_sys.users as u', 'u.id', '=', 'sipc.Profesional_asignado')
                        ->select('sipc.Accion_ejecutar', 'sipc.Estado', 'sipc.Profesional_asignado', 'u.name')
                        ->where([
                            ['sipc.Accion_ejecutar', $Accion_automatica],
                            ['sipc.Id_cliente', $id_cliente],
                            ['sipc.Id_proceso', $Id_proceso],
                            ['sipc.Servicio_asociado', $Id_servicio],
                            ['sipc.Status_parametrico', 'Activo']
                        ])->get();
                        
                            $Accion_ejecutar_automatica = $info_datos_accion_automatica[0]->Accion_ejecutar;
                            $Profesional_asignado_automatico = $info_datos_accion_automatica[0]->Profesional_asignado;
                            $NombreProfesional_asignado_automatico = $info_datos_accion_automatica[0]->name;
                            $Id_Estado_evento_automatico = $info_datos_accion_automatica[0]->Estado;

                            // Se suman los dias a la fecha actual para saber la fecha del movimiento automatico
                            $dateTime = new DateTime($date_time);
                            $dias = $Tiempo_movimiento; // Número de días que quieres sumar
                            $dateTime->modify("+$dias days");
                            $F_movimiento_automatico = $dateTime->format('Y-m-d');   

                            // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_acciones_automaticas_eventos para insert o update
                            $info_datos_acciones_automaticas_eventos = sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
                            ->where([['Id_Asignacion', $newIdAsignacion]])->get();

                            if (count($info_datos_acciones_automaticas_eventos) > 0) {
                                
                                $array_info_datos_accion_automatica = [
                                    'Id_Asignacion' => $newIdAsignacion,
                                    'ID_evento' => $newIdEvento,
                                    'Id_proceso' => $Id_proceso,
                                    'Id_servicio' => $Id_servicio,
                                    'Id_cliente' =>$id_cliente,
                                    'Accion_automatica' => $Accion_ejecutar_automatica,
                                    'Id_Estado_evento_automatico' => $Id_Estado_evento_automatico,
                                    'F_accion' => $date_time,
                                    'Id_profesional_automatico' => $Profesional_asignado_automatico,
                                    'Nombre_profesional_automatico' => $NombreProfesional_asignado_automatico,
                                    'F_movimiento_automatico' => $F_movimiento_automatico,
                                    'Estado_accion_automatica' => 'Pendiente',
                                    'Nombre_usuario' => $nombre_usuario,
                                    'F_registro' => $date,
    
                                ];
    
                                sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')
                                ->where([['Id_Asignacion', $newIdAsignacion]])
                                ->update($array_info_datos_accion_automatica);
                                
                                $mensaje_2 = 'la acción parametrizada tiene una Acción automatica y se ejecutará en '.$Tiempo_movimiento.' día(s)';

                            } else {
                                
                                $array_info_datos_accion_automatica = [
                                    'Id_Asignacion' => $newIdAsignacion,
                                    'ID_evento' => $newIdEvento,
                                    'Id_proceso' => $Id_proceso,
                                    'Id_servicio' => $Id_servicio,
                                    'Id_cliente' =>$id_cliente,
                                    'Accion_automatica' => $Accion_ejecutar_automatica,
                                    'Id_Estado_evento_automatico' => $Id_Estado_evento_automatico,
                                    'F_accion' => $date_time,
                                    'Id_profesional_automatico' => $Profesional_asignado_automatico,
                                    'Nombre_profesional_automatico' => $NombreProfesional_asignado_automatico,
                                    'F_movimiento_automatico' => $F_movimiento_automatico,
                                    'Estado_accion_automatica' => 'Pendiente',
                                    'Nombre_usuario' => $nombre_usuario,
                                    'F_registro' => $date,
    
                                ];
    
                                sigmel_informacion_acciones_automaticas_eventos::on('sigmel_gestiones')->insert($array_info_datos_accion_automatica);
                                
                                $mensaje_2 = 'la acción parametrizada tiene una Acción automatica y se ejecutará en '.$Tiempo_movimiento.' día(s)';
                                                               
                            }                            
                        
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and !empty($Tiempo_movimiento) and empty($Accion_automatica)):
                        $mensaje_2 = 'la acción parametrizada tiene movimiento automatico, Tiempo de movimiento (Días) pero no cuenta con una Acción automatica';
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and empty($Tiempo_movimiento) and !empty($Accion_automatica)):
                        $mensaje_2 = 'la acción parametrizada tiene movimiento automatico, Acción automatica pero no cuenta con Tiempo de movimiento (Días)';
                    break;
                case (!empty($Movimiento_automatico) and $Movimiento_automatico == 'Si' and empty($Tiempo_movimiento) and empty($Accion_automatica)):
                        $mensaje_2 = 'la acción parametrizada tiene movimiento automatico, pero no cuenta con un Tiempo de movimiento (Días) y Acción automatica';
                    break;                    
                default:   
                        $mensaje_2 = 'la acción parametrizada NO tiene Movimiento Automático';
                    break;
            } 

            sleep(2);

            // Actualizar tabla sigmel_informacion_asignacion_eventos
            $datos_info_actualizarAsignacionEvento= [      
                'Id_accion' => $request->accion,
                'Id_Estado_evento' => $Id_Estado_evento,        
                'F_alerta' => $request->fecha_alerta,
                'F_accion' => $date_time,
                'Id_profesional' => $id_profesional,
                'Nombre_profesional' => $asignacion_profesional,
                'Nueva_F_radicacion' => $Nueva_fecha_radicacion,
                'N_de_orden' => $N_orden_evento,     
                'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',              
                'Nombre_usuario' => $nombre_usuario,
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                'F_registro' => $date,
            ];

            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarAsignacionEvento);

            sleep(2);

            $F_accionEvento = $info_accion_evento[0]->F_accion;
            $info_datos_alertar_accion_ejecutar = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
            ->select('Tiempo_alerta', 'Porcentaje_alerta_naranja', 'Porcentaje_alerta_roja')
            ->where([
                ['Accion_ejecutar', $AccionEvento],
                ['Id_cliente', $id_cliente],
                ['Id_proceso', $Id_proceso],
                ['Servicio_asociado', $Id_servicio],
                ['Status_parametrico', 'Activo']
            ])
            ->get();
            $Tiempo_alerta = $info_datos_alertar_accion_ejecutar[0]->Tiempo_alerta;
            $Porcentaje_alerta_naranja = $info_datos_alertar_accion_ejecutar[0]->Porcentaje_alerta_naranja;
            $Porcentaje_alerta_roja = $info_datos_alertar_accion_ejecutar[0]->Porcentaje_alerta_roja;
            // case 1: Validar si hay tiempo de alerta para crear la nueva fecha de alerta segun la fecha de accion
                // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
            // case 2: Validar si hay tiempo de alerta y porcentaje de alerta naraja para crear la alerta naranja
                // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
                // formula AN = (TA*PN)/100 (AN= Alerta naranja, TA = tiempo de alerta y PN = porcentaje de alerta naranja)
            // case 3: Validar si hay tiempo de alerta y porcentaje de alerta roja para crear la alerta roja
                // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
                // formula AR = (TA*PR)/100 (AR= Alerta roja, TA = tiempo de alerta y PR = porcentaje de alerta roja)
            // case 4: Validar si hay tiempo de alerta, porcentaje de alerta naraja y porcentaje de alerta roja para crear todas las alertas
                // formula FA= FC+TA (FA = fecha alerta, FC = fecha accion y TA = tiempo de alerta)
                // formula AN = (TA*PN)/100 (AN= Alerta naranja, TA = tiempo de alerta y PN = porcentaje de alerta naranja)
                // formula AR = (TA*PR)/100 (AR= Alerta roja, TA = tiempo de alerta y PR = porcentaje de alerta roja)
            switch (true) {
                case (!empty($Tiempo_alerta) and empty($Porcentaje_alerta_naranja) and empty($Porcentaje_alerta_roja)):
                        $Nueva_F_Alerta = new DateTime($F_accionEvento);
                        $horas = $Tiempo_alerta;
                        $minutosAdicionales = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta->modify("+$horas hours");
                        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                        
                        $infoNueva_F_AlertaEvento_accion = [
                            'F_Alerta' => $Nueva_F_AlertaEvento
                        ];

                        $infoNueva_F_AlertaEvento_asignacion = [
                            'F_alerta' => $Nueva_F_AlertaEvento
                        ];
                        
                        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_accion);

                        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_asignacion);                       
                    break;
                case (!empty($Tiempo_alerta) and !empty($Porcentaje_alerta_naranja)  and empty($Porcentaje_alerta_roja)):
                        $Nueva_F_Alerta = new DateTime($F_accionEvento);
                        $horas = $Tiempo_alerta;
                        $minutosAdicionales = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta->modify("+$horas hours");
                        $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                        $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                        
                        $infoNueva_F_AlertaEvento_accion = [
                            'F_Alerta' => $Nueva_F_AlertaEvento
                        ];

                        $infoNueva_F_AlertaEvento_asignacion = [
                            'F_alerta' => $Nueva_F_AlertaEvento
                        ];
                        
                        sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_accion);

                        sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])
                        ->update($infoNueva_F_AlertaEvento_asignacion);

                        $Alerta_Naranja = ($Tiempo_alerta * $Porcentaje_alerta_naranja) / 100;

                        $Nueva_F_Alerta_Naranja = new DateTime($F_accionEvento);
                        $horas = $Alerta_Naranja;
                        $minutosAdicionales_naranja = ($horas - floor($horas)) * 60;
                        $horas = floor($horas);
                        $Nueva_F_Alerta_Naranja->modify("+$horas hours");
                        $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
                        $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
                        $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');

                        // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
                        $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                        ->where([['Id_Asignacion', $newIdAsignacion]])->get();

                        if (count($info_datos_alertar_automaticas_eventos) > 0) {
                            $array_info_datos_alertas_automatica = [
                                'Id_Asignacion' => $newIdAsignacion,
                                'ID_evento' => $newIdEvento,
                                'Id_proceso' => $Id_proceso,
                                'Id_servicio' => $Id_servicio,
                                'Id_cliente' =>$id_cliente,
                                'Accion_ejecutar' => $AccionEvento,
                                'F_accion' => $date_time,
                                'Tiempo_alerta' => $Tiempo_alerta,
                                'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                                'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,   
                                'Porcentaje_alerta_roja' => null,
                                'F_accion_alerta_roja' => null,                           
                                'Estado_alerta_automatica' => 'Ejecucion',
                                'Nombre_usuario' => $nombre_usuario,
                                'F_registro' => $date,
                            ];
    
                            sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                            ->where('Id_Asignacion', $newIdAsignacion)
                            ->update($array_info_datos_alertas_automatica);                            
                        } else {
                            $array_info_datos_alertas_automatica = [
                                'Id_Asignacion' => $newIdAsignacion,
                                'ID_evento' => $newIdEvento,
                                'Id_proceso' => $Id_proceso,
                                'Id_servicio' => $Id_servicio,
                                'Id_cliente' =>$id_cliente,
                                'Accion_ejecutar' => $AccionEvento,
                                'F_accion' => $date_time,
                                'Tiempo_alerta' => $Tiempo_alerta,
                                'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                                'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento, 
                                'Porcentaje_alerta_roja' => null,
                                'F_accion_alerta_roja' => null,                           
                                'Estado_alerta_automatica' => 'Ejecucion',
                                'Nombre_usuario' => $nombre_usuario,
                                'F_registro' => $date,
                            ];
    
                            sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                            
                            ->insert($array_info_datos_alertas_automatica); 
                        }                        
                    break;
                case (!empty($Tiempo_alerta) and empty($Porcentaje_alerta_naranja) and !empty($Porcentaje_alerta_roja)):
                    $Nueva_F_Alerta = new DateTime($F_accionEvento);
                    $horas = $Tiempo_alerta;
                    $minutosAdicionales = ($horas - floor($horas)) * 60;
                    $horas = floor($horas);
                    $Nueva_F_Alerta->modify("+$horas hours");
                    $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                    $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                    
                    $infoNueva_F_AlertaEvento_accion = [
                        'F_Alerta' => $Nueva_F_AlertaEvento
                    ];

                    $infoNueva_F_AlertaEvento_asignacion = [
                        'F_alerta' => $Nueva_F_AlertaEvento
                    ];
                    
                    sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $newIdAsignacion]])
                    ->update($infoNueva_F_AlertaEvento_accion);

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $newIdAsignacion]])
                    ->update($infoNueva_F_AlertaEvento_asignacion);                    

                    $Alerta_Roja = ($Tiempo_alerta * $Porcentaje_alerta_roja) / 100;

                    $Nueva_F_Alerta_Roja = new DateTime($F_accionEvento);
                    $horas_roja = $Alerta_Roja;
                    $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;
                    $horas_roja = floor($horas_roja);
                    $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
                    $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
                    $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
                    $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');

                    // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
                    $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $newIdAsignacion]])->get();                    
                    if (count($info_datos_alertar_automaticas_eventos) > 0) {
                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $newIdAsignacion,
                            'ID_evento' => $newIdEvento,
                            'Id_proceso' => $Id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => null,
                            'F_accion_alerta_naranja' => null,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => $nombre_usuario,
                            'F_registro' => $date,
                        ];
    
                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                        ->where('Id_Asignacion', $newIdAsignacion)
                        ->update($array_info_datos_alertas_automatica);
                        
                    } else {
                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $newIdAsignacion,
                            'ID_evento' => $newIdEvento,
                            'Id_proceso' => $Id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => null,
                            'F_accion_alerta_naranja' => null,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => $nombre_usuario,
                            'F_registro' => $date,
                        ];
    
                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                        
                        ->insert($array_info_datos_alertas_automatica);
                    }
                    break;

                case (!empty($Tiempo_alerta) and !empty($Porcentaje_alerta_naranja) and !empty($Porcentaje_alerta_roja)):
                    $Nueva_F_Alerta = new DateTime($F_accionEvento);
                    $horas = $Tiempo_alerta;
                    $minutosAdicionales = ($horas - floor($horas)) * 60;
                    $horas = floor($horas);
                    $Nueva_F_Alerta->modify("+$horas hours");
                    $Nueva_F_Alerta->modify("+$minutosAdicionales minutes");
                    $Nueva_F_AlertaEvento = $Nueva_F_Alerta->format('Y-m-d H:i:s');
                    
                    $infoNueva_F_AlertaEvento_accion = [
                        'F_Alerta' => $Nueva_F_AlertaEvento
                    ];

                    $infoNueva_F_AlertaEvento_asignacion = [
                        'F_alerta' => $Nueva_F_AlertaEvento
                    ];
                    
                    sigmel_informacion_accion_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $newIdAsignacion]])
                    ->update($infoNueva_F_AlertaEvento_accion);

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $newIdAsignacion]])
                    ->update($infoNueva_F_AlertaEvento_asignacion);

                    $Alerta_Naranja = ($Tiempo_alerta * $Porcentaje_alerta_naranja) / 100;
                    
                    $Nueva_F_Alerta_Naranja = new DateTime($F_accionEvento);
                    $horas_naranja = $Alerta_Naranja;
                    $minutosAdicionales_naranja = ($horas_naranja - floor($horas_naranja)) * 60;                    
                    $horas_naranja = floor($horas_naranja);                   
                    $Nueva_F_Alerta_Naranja->modify("+$horas_naranja hours");
                    $minutosAdicionales_naranja_entero = round($minutosAdicionales_naranja);
                    $Nueva_F_Alerta_Naranja->modify("+$minutosAdicionales_naranja_entero minutes");
                    $Nueva_F_Alerta_NaranjaEvento = $Nueva_F_Alerta_Naranja->format('Y-m-d H:i:s');
                    
                    $Alerta_Roja = ($Tiempo_alerta * $Porcentaje_alerta_roja) / 100;
                    
                    $Nueva_F_Alerta_Roja = new DateTime($F_accionEvento);
                    $horas_roja = $Alerta_Roja;
                    $minutosAdicionales_roja = ($horas_roja - floor($horas_roja)) * 60;                    
                    $horas_roja = floor($horas_roja);                    
                    $Nueva_F_Alerta_Roja->modify("+$horas_roja hours");
                    $minutosAdicionales_roja_entero = round($minutosAdicionales_roja);
                    $Nueva_F_Alerta_Roja->modify("+$minutosAdicionales_roja_entero minutes");
                    $Nueva_F_Alerta_RojaEvento = $Nueva_F_Alerta_Roja->format('Y-m-d H:i:s');
                    
                    // Validar si existe el Id_Asignacion en la tabla sigmel_informacion_alertas_automaticas_eventos para insert o update
                    $info_datos_alertar_automaticas_eventos = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                    ->where([['Id_Asignacion', $newIdAsignacion]])->get();

                    if (count($info_datos_alertar_automaticas_eventos) > 0) {
                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $newIdAsignacion,
                            'ID_evento' => $newIdEvento,
                            'Id_proceso' => $Id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                            'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => $nombre_usuario,
                            'F_registro' => $date,
                        ];
    
                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
                        ->where('Id_Asignacion', $newIdAsignacion)
                        ->update($array_info_datos_alertas_automatica);
                        
                    } else {
                        $array_info_datos_alertas_automatica = [
                            'Id_Asignacion' => $newIdAsignacion,
                            'ID_evento' => $newIdEvento,
                            'Id_proceso' => $Id_proceso,
                            'Id_servicio' => $Id_servicio,
                            'Id_cliente' =>$id_cliente,
                            'Accion_ejecutar' => $AccionEvento,
                            'F_accion' => $date_time,
                            'Tiempo_alerta' => $Tiempo_alerta,
                            'Porcentaje_alerta_naranja' => $Porcentaje_alerta_naranja,
                            'F_accion_alerta_naranja' => $Nueva_F_Alerta_NaranjaEvento,
                            'Porcentaje_alerta_roja' => $Porcentaje_alerta_roja,
                            'F_accion_alerta_roja' => $Nueva_F_Alerta_RojaEvento,
                            'Estado_alerta_automatica' => 'Ejecucion',
                            'Nombre_usuario' => $nombre_usuario,
                            'F_registro' => $date,
                        ];
    
                        sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')                        
                        ->insert($array_info_datos_alertas_automatica);
                    }
                    
                    break;
                default:
                    
                    break;
            }

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

            // Insertar informacion en la tabla sigmel_informacion_historial_accion_eventos

            $datos_historial_accion_eventos = [
                'Id_Asignacion' => $newIdAsignacion,
                'ID_evento' => $newIdEvento,
                'Id_proceso' => $Id_proceso,
                'Id_servicio' => $Id_servicio,
                'Id_accion' => $Accion_realizar,
                'Descripcion' => $request->descripcion_accion,
                'F_accion' => $date_time,
                'Nombre_usuario' => $nombre_usuario,
            ];

            $idInsertado = sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')->insertGetId($datos_historial_accion_eventos);

            sleep(2);

            // Cargue de documento
            if($request->hasFile('cargue_documentos')){
                $archivo = $request->file('cargue_documentos');
                $path = public_path('Documentos_Eventos/'.$newIdEvento);
                $mode = 0777;
                $tipo_archivo = "Documento Historial Juntas";
                $nombre_documento = str_replace(' ', '_', $tipo_archivo);

                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode, true, true);
                    chmod($path, $mode);
                }

                $nombre_final_documento = $nombre_documento."$idInsertado"."_IdEvento_".$newIdEvento.".".$archivo->extension();
                Storage::putFileAs($newIdEvento, $archivo, $nombre_final_documento);
            }else{
                
                $nombre_final_documento='N/A';            
            }     

            // Insertar nombre documento
            
            $nombre_documento_historial = [                
                'Documento' => $nombre_final_documento,                
            ];

            sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
            ->where('Id_historial_accion',$idInsertado)->update($nombre_documento_historial);           

            sleep(2);

            $mensajes = array(
                "parametro" => 'agregarCalificacionJuntas',
                "mensaje" => 'Registro actualizado satisfactoriamente.',
                "mensaje_2" => $mensaje_2
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
        $f_contro_radi_califi = $request->f_contro_radi_califi;
        $f_notifi_afiliado = $request->f_notifi_afiliado;
        //Validar registro termino de controversia
        $conteoDias = sigmel_calendarios::on('sigmel_gestiones')
        ->whereBetween('Fecha', [$f_notifi_afiliado, $f_contro_radi_califi])
        ->where('Calendario', 'LunesAViernes')
        ->where('EsHabil', 1)
        ->where('EsFestivo', 0)
        ->count();
        if($conteoDias > 11){
                $terminos='Fuera de términos';
        }else{
                $terminos='Dentro de términos';  
        }
        // validacion de bandera para guardar o actualizar
        // insercion de datos a la tabla de sigmel_informacion_controversia_juntas_eventos
        sigmel_informacion_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $newIdEvento)->update(["Tipo_evento" => $request->tipo_evento]);

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
                'F_dictamen_controvertido' => $request->f_dictamen_controvertido,
                'N_siniestro' => $request->n_siniestro,
                'F_notifi_afiliado' => $request->f_notifi_afiliado,
                'Termino_contro_califi' => $terminos,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')->insert($datos_info_controvertido);

            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $request->n_siniestro,
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento]])
            ->update($dato_actualizar_n_siniestro);

            sleep(2);

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
                'F_dictamen_controvertido' => $request->f_dictamen_controvertido,
                'N_siniestro' => $request->n_siniestro,
                'F_notifi_afiliado' => $request->f_notifi_afiliado,
                'Termino_contro_califi' => $terminos,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
           
            sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actuali_controvertido);

            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $request->n_siniestro,
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$newIdEvento]])
            ->update($dato_actualizar_n_siniestro);

            sleep(2);

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
        $f_contro_radi_califi = $request->f_contro_radi_califi;
        $f_notifi_afiliado = $request->f_notifi_afiliado;
        //Validar registro termino de controversia
        $conteoDias = sigmel_calendarios::on('sigmel_gestiones')
        ->whereBetween('Fecha', [$f_notifi_afiliado, $f_contro_radi_califi])
        ->where('Calendario', 'LunesAViernes')
        ->where('EsHabil', 1)
        ->where('EsFestivo', 0)
        ->count();
        if($conteoDias > 11){
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
                'F_envio_jrci' => $request->fecha_envio_jrci,
                'F_envio_jnci' => $request->fecha_envio_jnci,
                'Observaciones' => $request->Observaciones
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
                'F_envio_jrci' => $request->fecha_envio_jrci,
                'F_envio_jnci' => $request->fecha_envio_jnci,
                'Observaciones' => $request->Observaciones
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
     // Editar fecha de algun registro de la tabla de listado documentos solicitados
     public function EditarFecha_Recepcion_Doc_soli_jun(Request $request){

        $Id_evento   = $request->Id_evento;
        $Fechas_recepcion = $request->Fechas_recepcion;

        if(isset($Fechas_recepcion)){
            // Itera sobre las fechas de recepción
            foreach ($Fechas_recepcion as $fecha) {
                $id = $fecha['id'];
                $nueva_fecha = $fecha['fecha'];
    
                // Actualiza las fechas de recepción para el evento específico
                sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
                    ->where('ID_evento', $Id_evento)
                    ->where('Id_Documento_Solicitado', $id)
                    ->update(['F_recepcion_documento' => $nueva_fecha]);
            }   
    
            $mensajes = array(
                "parametro" => 'filas_editadas',
                "mensaje" => 'Fechas actualizadas satisfactoriamente.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        // else {
        //     $mensajes = array(
        //         "parametro" => 'filas_NO_editadas',
        //         "mensaje" => 'Debe seleccionar la Fecha de recepción de documentos.'
        //     );  
        // }

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
        $identificacion_comunicado_afiliado = $request->identificacion_comunicado_afiliado;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso; 

        switch (true) {
            case ($destinatarioPrincipal == 'Afiliado'):                
                $array_datos_destinatarios = cndatos_comunicado_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$newIdEvento],['Nro_identificacion',$identificacion_comunicado_afiliado]])
                ->limit(1)->get(); 
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();

                // Traer opción seleccionada del campo Medio de notificación del la información del afiliado/beneficiario
                $info_medio_noti = DB::table(getDatabaseName('sigmel_gestiones'). 'sigmel_informacion_afiliado_eventos as siae')
                ->select('siae.Medio_notificacion')
                ->where([['siae.ID_evento', $newIdEvento]])
                ->get();
                
                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_destinatarios' => $array_datos_destinatarios,
                    'array_datos_lider' => $array_datos_lider,
                    'info_medio_noti' => $info_medio_noti
                ]);
            break;
            case ($destinatarioPrincipal == 'Empleador'):                
                $array_datos_destinatarios = cndatos_comunicado_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$newIdEvento],['Nro_identificacion',$identificacion_comunicado_afiliado]])
                ->limit(1)->get(); 
                $array_datos_lider =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_grupos_trabajos as sgt')
                ->leftJoin('sigmel_sys.users as ssu', 'ssu.id', '=', 'sgt.lider')
                ->select('ssu.id', 'ssu.name', 'sgt.Id_proceso_equipo')
                ->where([['sgt.Id_proceso_equipo', '=', $Id_proceso]])->get();

                // Traer opción seleccionada del campo Medio de notificación del la información del afiliado/beneficiario
                $info_medio_noti = DB::table(getDatabaseName('sigmel_gestiones'). 'sigmel_informacion_laboral_eventos as sile')
                ->select('sile.Medio_notificacion')
                ->where([['sile.ID_evento', $newIdEvento]])
                ->get();

                return response()->json([
                    'nombreusuario' => $nombreusuario,
                    'destinatarioPrincipal' => $destinatarioPrincipal,
                    'array_datos_destinatarios' => $array_datos_destinatarios,                    
                    'array_datos_lider' => $array_datos_lider,
                    'info_medio_noti' => $info_medio_noti
                ]);
            break;
            case ($destinatarioPrincipal == 'EPS_comunicado'):
                $dato_id_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->select('siae.Id_eps')
                ->where([
                    ['siae.ID_evento',$newIdEvento],
                    ['siae.Nro_identificacion', $identificacion_comunicado_afiliado]
                ])
                ->get();
                
                $id_eps = $dato_id_eps[0]->Id_eps;

                $array_datos_destinatarios = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                ->select('sie.Nombre_entidad', 
                    'sie.Nit_entidad', 
                    'sie.Direccion', 
                    'sie.Telefonos',
                    'sie.Emails',
                    'sldm.Id_departamento',
                    'sldm.Nombre_departamento',
                    'sldm1.Id_municipios',
                    'sldm1.Nombre_municipio as Nombre_ciudad',
                    'sie.Id_Medio_Noti'
                )->where([
                    ['sie.Id_Entidad', $id_eps]
                ])->get();

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
            case ($destinatarioPrincipal == 'AFP_comunicado'):
                $dato_id_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->select('siae.Id_afp')
                ->where([
                    ['siae.ID_evento',$newIdEvento],
                    ['siae.Nro_identificacion', $identificacion_comunicado_afiliado]
                ])
                ->get();
                
                $id_afp = $dato_id_afp[0]->Id_afp;

                $array_datos_destinatarios = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                ->select('sie.Nombre_entidad', 
                    'sie.Nit_entidad', 
                    'sie.Direccion', 
                    'sie.Telefonos',
                    'sie.Emails',
                    'sldm.Id_departamento',
                    'sldm.Nombre_departamento',
                    'sldm1.Id_municipios',
                    'sldm1.Nombre_municipio as Nombre_ciudad',
                    'sie.Id_Medio_Noti'
                )->where([
                    ['sie.Id_Entidad', $id_afp]
                ])->get();

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
            case ($destinatarioPrincipal == 'ARL_comunicado'):
                $dato_id_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->select('siae.Id_arl')
                ->where([
                    ['siae.ID_evento',$newIdEvento],
                    ['siae.Nro_identificacion', $identificacion_comunicado_afiliado]
                ])
                ->get();
                
                $id_arl = $dato_id_arl[0]->Id_arl;

                $array_datos_destinatarios = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                ->select('sie.Nombre_entidad', 
                    'sie.Nit_entidad', 
                    'sie.Direccion', 
                    'sie.Telefonos',
                    'sie.Emails',
                    'sldm.Id_departamento',
                    'sldm.Nombre_departamento',
                    'sldm1.Id_municipios',
                    'sldm1.Nombre_municipio as Nombre_ciudad',
                    'sie.Id_Medio_Noti'
                )->where([
                    ['sie.Id_Entidad', $id_arl]
                ])->get();

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
            case ($destinatarioPrincipal == 'JRCI_comunicado'):

                $array_datos_destinatarios = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                ->select('sie.Nombre_entidad', 
                    'sie.Nit_entidad', 
                    'sie.Direccion', 
                    'sie.Telefonos',
                    'sie.Emails',
                    'sldm.Id_departamento',
                    'sldm.Nombre_departamento',
                    'sldm1.Id_municipios',
                    'sldm1.Nombre_municipio as Nombre_ciudad',
                    'sie.Id_Medio_Noti'
                )->where([
                    ['sie.Id_Entidad', $request->id_jrci]
                ])->get();

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
            case ($destinatarioPrincipal == 'JNCI_comunicado'):

                $array_datos_destinatarios = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                ->select('sie.Nombre_entidad', 
                    'sie.Nit_entidad', 
                    'sie.Direccion', 
                    'sie.Telefonos',
                    'sie.Emails',
                    'sldm.Id_departamento',
                    'sldm.Nombre_departamento',
                    'sldm1.Id_municipios',
                    'sldm1.Nombre_municipio as Nombre_ciudad',
                    'sie.Id_Medio_Noti'
                )->where([
                    ['sie.IdTipo_entidad', 5]
                ])->limit(1)->get();

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
        $tipo_descarga = $request->tipo_descarga;
        $otro_destinatario_jrci = is_numeric($request->JRCI_Destinatario) ? 1 : 0;

        $radicado = $this->disponible($request->radicado2,$Id_evento)->getRadicado('juntas',$Id_evento);

        //Se asignan los IDs de destinatario por cada posible destinatario
        $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario(true,true);

        if($tipo_descarga != 'Manual'){
            $radiojrci_comunicado = $request->radiojrci_comunicado;
            $radiojnci_comunicado = $request->radiojnci_comunicado;
            $radioafiliado_comunicado = $request->radioafiliado_comunicado;
            $radioempresa_comunicado = $request->radioempresa_comunicado;
            $radioeps_comunicado = $request->radioeps_comunicado;
            $radioafp_comunicado = $request->radioafp_comunicado;
            $radioarl_comunicado = $request->radioarl_comunicado;

            $radioOtro = $request->radioOtro;
            $copiaComunicadoTotal = $request->copiaComunicadoTotal;
            if (!empty($copiaComunicadoTotal)) {
                $total_copia_comunicado = implode(", ", $copiaComunicadoTotal);                
            }else{
                $total_copia_comunicado = '';
            }

            if(!empty($radiojrci_comunicado) && empty($radiojnci_comunicado) && empty($radioafiliado_comunicado) && empty($radioempresa_comunicado)
                && empty($radioeps_comunicado) && empty($radioafp_comunicado) && empty($radioarl_comunicado) && empty($radioOtro)){
                    $destinatario = 'Jrci';
            }
            elseif(empty($radiojrci_comunicado) && !empty($radiojnci_comunicado) && empty($radioafiliado_comunicado) && empty($radioempresa_comunicado)
                && empty($radioeps_comunicado) && empty($radioafp_comunicado) && empty($radioarl_comunicado) && empty($radioOtro)){
                    $destinatario = 'Jnci';
            }
            elseif(empty($radiojrci_comunicado) && empty($radiojnci_comunicado) && !empty($radioafiliado_comunicado) && empty($radioempresa_comunicado)
                && empty($radioeps_comunicado) && empty($radioafp_comunicado) && empty($radioarl_comunicado) && empty($radioOtro)){
                $destinatario = 'Afiliado';
            }
            elseif(empty($radiojrci_comunicado) && empty($radiojnci_comunicado) && empty($radioafiliado_comunicado) && !empty($radioempresa_comunicado)
                && empty($radioeps_comunicado) && empty($radioafp_comunicado) && empty($radioarl_comunicado) && empty($radioOtro)){
                $destinatario = 'Empleador';
            }
            elseif(empty($radiojrci_comunicado) && empty($radiojnci_comunicado) && empty($radioafiliado_comunicado) && empty($radioempresa_comunicado)
                && !empty($radioeps_comunicado) && empty($radioafp_comunicado) && empty($radioarl_comunicado) && empty($radioOtro)){
                    $destinatario = 'Eps';
            }
            elseif(empty($radiojrci_comunicado) && empty($radiojnci_comunicado) && empty($radioafiliado_comunicado) && empty($radioempresa_comunicado)
                && empty($radioeps_comunicado) && !empty($radioafp_comunicado) && empty($radioarl_comunicado) && empty($radioOtro)){
                    $destinatario = 'Afp';
            }
            elseif(empty($radiojrci_comunicado) && empty($radiojnci_comunicado) && empty($radioafiliado_comunicado) && empty($radioempresa_comunicado)
                && empty($radioeps_comunicado) && empty($radioafp_comunicado) && !empty($radioarl_comunicado) && empty($radioOtro)){
                    $destinatario = 'Arl';
            }
            elseif(empty($radiojrci_comunicado) && empty($radiojnci_comunicado) && empty($radioafiliado_comunicado) && empty($radioempresa_comunicado)
                && empty($radioeps_comunicado) && empty($radioafp_comunicado) && empty($radioarl_comunicado) && !empty($radioOtro)){
                    $destinatario = 'Otro';
            }
            
            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $request->N_siniestro,
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$Id_evento]])
            ->update($dato_actualizar_n_siniestro);

            sleep(2);

            $datos_info_registrarComunicadoPcl=[
                'ID_evento' => $Id_evento,
                'Id_Asignacion' => $Id_asignacion,
                'Id_proceso' => $Id_procesos,
                'Ciudad' => $request->ciudad,
                'F_comunicado' => $request->fecha_comunicado2,
                'N_radicado' => $radicado,
                'Cliente' => $request->cliente_comunicado2,
                'Nombre_afiliado' => $request->nombre_afiliado_comunicado2,
                'T_documento' => $request->tipo_documento_comunicado2,
                'N_identificacion' => $request->identificacion_comunicado2,
                'Destinatario' => $destinatario,
                'JRCI_Destinatario' => $request->JRCI_Destinatario,
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
                'JRCI_copia' => $request->JRCI_copia,
                'Firmar_Comunicado' => $request->firmarcomunicado,
                'Tipo_descarga' => $request->tipo_descarga,
                'Modulo_creacion' => $request->modulo_creacion,
                'Otro_destinatario' => $otro_destinatario_jrci,
                'N_siniestro' => $request->N_siniestro,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            
            $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_registrarComunicadoPcl);

            sleep(2);
            $datos_info_historial_acciones = [
                'ID_evento' => $Id_evento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Se genera comunicado Juntas.",
                'Descripcion' => $request->asunto,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        }
        else if($tipo_descarga == 'Manual'){
            if($request->modulo){
                $modulo = $request->modulo;
            }
            else{
                $modulo = '';
            }

            if($request->hasFile('cargue_comunicados')){
                $archivo = $request->file('cargue_comunicados');
                $path = public_path('Documentos_Eventos/'.$Id_evento);
                $mode = 777;

                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode, true, true);
                    chmod($path, $mode);
                }

                // $nombre_final_documento = $nombre_documento."_IdEvento_".$Id_evento.".".$archivo->extension();
                // $nombre_final_documento = $request->asunto;

                // Obtenemos el nombre original del archivo (incluyendo la extensión)
                $documentName = $archivo->getClientOriginalName();
                // Obtenemos la extensión del archivo
                $extension = $archivo->getClientOriginalExtension();
                // Obtenemos el nombre del archivo sin la extensión
                $nameWithoutExtension = pathinfo($documentName, PATHINFO_FILENAME);

                /* Agregamos el indicativo */
                $indicativo = time();

                // el nuevo nombre del documento será:
                $nombre_final_documento = "{$nameWithoutExtension}_{$indicativo}.{$extension}";

                Storage::putFileAs($Id_evento, $archivo, $nombre_final_documento);

            }else{
                $nombre_final_documento='N/A';            
            }  

            $datos_info_registrarComunicadoJuntas=[
                'ID_evento' => $Id_evento,
                'Id_Asignacion' => $Id_asignacion,
                'Id_proceso' => $Id_procesos,
                'Ciudad' => $request->ciudad,
                'F_comunicado' => $date,
                'N_radicado' => $radicado,
                'Cliente' => $request->cliente_comunicado2,
                'Nombre_afiliado' => $request->nombre_afiliado_comunicado2,
                'T_documento' => $request->tipo_documento_comunicado2,
                'N_identificacion' => $request->identificacion_comunicado2,
                'Destinatario' => $request->destinatario,
                'Nombre_destinatario' => $request->nombre_destinatario,
                'Nit_cc' => $request->nic_cc,
                'Direccion_destinatario' => $request->direccion_destinatario,
                'Telefono_destinatario' => $request->telefono_destinatario,
                'Email_destinatario' => $request->email_destinatario,
                'Id_departamento' => $request->departamento_destinatario,
                'Id_municipio' => $request->ciudad_destinatario,
                // 'Asunto' => $request->asunto,
                'Asunto' => $nombre_final_documento,
                'Cuerpo_comunicado' => $request->cuerpo_comunicado,
                'Anexos' => $request->anexos,
                'Forma_envio' => $request->forma_envio,
                'Elaboro' => $nombre_usuario,
                'Reviso' => $request->reviso,
                'Agregar_copia' => '',
                'Firmar_Comunicado' => $request->firmarcomunicado,
                'Tipo_descarga' => $request->tipo_descarga,
                'Modulo_creacion' => $request->modulo_creacion,
                // 'Nombre_documento' => $request->Nombre_documento,
                'Otro_destinatario' => 0,
                'Nombre_documento' => $nombre_final_documento,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            $id_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_registrarComunicadoJuntas);

            sleep(2);
            $datos_info_historial_acciones = [
                'ID_evento' => $Id_evento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Se genera comunicado de forma manual en $modulo.",
                'Descripcion' => $request->asunto,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        }
        $entidades = ['Jrci', 'Jnci', 'Eps', 'Afp', 'Arl'];

        $principalDestinatario = function($entidades, $destinatario) {
            return in_array($destinatario, $entidades) ? strtoupper($destinatario) . "_comunicado" : $destinatario;
        };

        $mensajes = array(
            "parametro" => 'agregar_comunicado',
            'status_pdf' => $this->generarPDF($request, $id_comunicado,$principalDestinatario($entidades,$destinatario ?? null),'guardar'),
            "mensaje" => 'Comunicado generado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }
    public function generarPDF($data_comunicado,$id_comunicado,$principalDestinatario,$tipo_proceso){
        
        //Dado que los procesos de guardar y actualizar estan separados, y las clave-valor no son los mismos,
        //se utiliza params para homolarlo en uno solo.
        $params = [
            'guardar' => [
                'p1' => '_token',
                'p2' => 'cliente_comunicado2',
                'p3' => 'nombre_afiliado_comunicado2',
                'p4' => 'tipo_documento_comunicado2',
                'p5' => 'identificacion_comunicado2',
                'p6' => 'Id_evento',
                'p7' => 'tipo_descarga',
                //'p8' => 'afiliado_comunicado_act',
                'p9' => 'nombre_destinatario',
                'p10' => 'nic_cc',
                'p11' => 'direccion_destinatario',
                'p12' => 'telefono_destinatario',
                'p13' => 'email_destinatario',
                'p14' => 'departamento_destinatario',
                'p15' => 'ciudad_destinatario',
                'p16' => 'asunto',
                'p17' => 'cuerpo_comunicado',
                //'p18' => 'files',
                'p19' => 'anexos',
                'p20' => 'forma_envio',
                'p21' => 'elaboro2',
                'p22' => 'reviso',
                'p23' => 'firmarcomunicado',
                'p24' => 'ciudad',
                //'p25' => 'Id_comunicado_act',
                'p26' => 'Id_evento',
                'p27' => 'Id_asignacion',
                'p28' => 'Id_procesos',
                'p29' => 'fecha_comunicado2',
                'p30' => 'copiaComunicadoTotal',
                'p31' => 'radicado2',
                'p32' => 'edit_copia_afiliado',
                'p33' => 'edit_copia_empleador',
                'p34' => 'edit_copia_eps',
                'p35' => 'edit_copia_afp',
                'p36' => 'edit_copia_arl',
                'p37' => 'edit_copia_jrci',
                'p38' => 'edit_copia_jnci',
                'p39' => 'N_siniestro',
                'p40' => 'Nombre_junta_act',
                'p41' => 'F_estructuracion_act',
                'p42' => 'F_dictamen_act',
                'p43' => 'input_jrci_seleccionado_copia_editar',
                'p44' => 'id_jrci_del_input',
                'p45' => 'F_radicacion_contro_pri_cali_act',
                'p46' => 'F_notifi_afiliado_act',
                'p47' => 'Id_junta_act',
                'p48' => 'tipo_de_preforma_editar',
            ],
            'actualizar' => [
                'p1' => '_token',
                'p2' => 'cliente_comunicado2_editar',
                'p3' => 'nombre_afiliado_comunicado2_editar',
                'p4' => 'tipo_documento_comunicado2_editar',
                'p5' => 'identificacion_comunicado2_editar',
                'p6' => 'Id_evento_editar',
                'p7' => 'tipo_descarga',
                //'p8' => 'afiliado_comunicado_act',
                'p9' => 'nombre_destinatario_editar',
                'p10' => 'nic_cc_editar',
                'p11' => 'direccion_destinatario_editar',
                'p12' => 'telefono_destinatario_editar',
                'p13' => 'email_destinatario_editar',
                'p14' => 'departamento_destinatario_editar',
                'p15' => 'ciudad_destinatario_editar',
                'p16' => 'asunto_editar',
                'p17' => 'cuerpo_comunicado_editar',
                //'p18' => 'files',
                'p19' => 'anexos_editar',
                'p20' => 'forma_envio_editar',
                'p21' => 'elaboro2_editar',
                'p22' => 'reviso_editar',
                'p23' => 'firmarcomunicado_editar',
                'p24' => 'ciudad_editar',
                //'p25' => 'Id_comunicado_act',
                'p26' => 'Id_evento_editar',
                'p27' => 'Id_asignacion_editar',
                'p28' => 'Id_procesos_editar',
                'p29' => 'fecha_comunicado2_editar',
                'p30' => 'agregar_copia_editar',
                'p31' => 'radicado2_editar',
                'p32' => 'edit_copia_afiliado',
                'p33' => 'edit_copia_empleador',
                'p34' => 'edit_copia_eps',
                'p35' => 'edit_copia_afp',
                'p36' => 'edit_copia_arl',
                'p37' => 'edit_copia_jrci',
                'p38' => 'edit_copia_jnci',
                'p39' => 'N_siniestro',
                'p40' => 'Nombre_junta_act',
                'p41' => 'F_estructuracion_act',
                'p42' => 'F_dictamen_act',
                'p43' => 'input_jrci_seleccionado_copia_editar',
                'p44' => 'id_jrci_del_input',
                'p45' => 'F_radicacion_contro_pri_cali_act',
                'p46' => 'F_notifi_afiliado_act',
                'p47' => 'Id_junta_act',
                'p48' => 'tipo_de_preforma_editar',
            ]
        ];

        $data = [
            '_token' => $data_comunicado->{$params[$tipo_proceso]['p1']},
            'cliente_comunicado2_act' => $data_comunicado->{$params[$tipo_proceso]['p2']},
            'nombre_afiliado_comunicado2_act' => $data_comunicado->{$params[$tipo_proceso]['p3']},
            'tipo_documento_comunicado2_act' => $data_comunicado->{$params[$tipo_proceso]['p4']},
            'identificacion_comunicado2_act' => $data_comunicado->{$params[$tipo_proceso]['p5']},
            'id_evento_comunicado2_act' => $data_comunicado->{$params[$tipo_proceso]['p6']},
            'tipo_documento_descarga_califi_editar' => $data_comunicado->{$params[$tipo_proceso]['p7']},
            'afiliado_comunicado_act' => $principalDestinatario,
            'nombre_destinatario_act2' => $data_comunicado->{$params[$tipo_proceso]['p9']},
            'nic_cc_act2' => $data_comunicado->{$params[$tipo_proceso]['p10']},
            'direccion_destinatario_act2' => $data_comunicado->{$params[$tipo_proceso]['p11']},
            'telefono_destinatario_act2' => $data_comunicado->{$params[$tipo_proceso]['p12']},
            'email_destinatario_act2' => $data_comunicado->{$params[$tipo_proceso]['p13']},
            'departamento_pdf' => $data_comunicado->{$params[$tipo_proceso]['p14']},
            'ciudad_pdf' => $data_comunicado->{$params[$tipo_proceso]['p15']},
            'asunto_act' => $data_comunicado->{$params[$tipo_proceso]['p16']},
            'cuerpo_comunicado_act' => $data_comunicado->{$params[$tipo_proceso]['p17']},
            'files' => null,
            'anexos_act' => $data_comunicado->{$params[$tipo_proceso]['p19']},
            'forma_envio_act' => $data_comunicado->{$params[$tipo_proceso]['p20']},
            'elaboro2_act' => $data_comunicado->{$params[$tipo_proceso]['p21']},
            'reviso_act' => $data_comunicado->{$params[$tipo_proceso]['p22']},
            'firmarcomunicado_editar' => $data_comunicado->{$params[$tipo_proceso]['p23']},
            'ciudad_comunicado_act' => $data_comunicado->{$params[$tipo_proceso]['p24']},
            'Id_comunicado_act' => $id_comunicado,
            'Id_evento_act' => $data_comunicado->{$params[$tipo_proceso]['p26']},
            'Id_asignacion_act' => $data_comunicado->{$params[$tipo_proceso]['p27']},
            'Id_procesos_act' => $data_comunicado->{$params[$tipo_proceso]['p28']},
            'fecha_comunicado2_act' => $data_comunicado->{$params[$tipo_proceso]['p29']},
            'agregar_copia_editar' => $data_comunicado->{$params[$tipo_proceso]['p30']},
            'radicado2_act' => $data_comunicado->{$params[$tipo_proceso]['p31']},
            'edit_copia_afiliado' => ($data_comunicado->{$params[$tipo_proceso]['p32']} == 'true'),
            'edit_copia_empleador' => ($data_comunicado->{$params[$tipo_proceso]['p33']} == 'true'),
            'edit_copia_eps' => ($data_comunicado->{$params[$tipo_proceso]['p34']} == 'true'),
            'edit_copia_afp' => ($data_comunicado->{$params[$tipo_proceso]['p35']} == 'true'),
            'edit_copia_arl' => ($data_comunicado->{$params[$tipo_proceso]['p36']} == 'true'),
            'edit_copia_jrci' => ($data_comunicado->{$params[$tipo_proceso]['p37']} == 'true'),
            'edit_copia_jnci' => ($data_comunicado->{$params[$tipo_proceso]['p38']} == 'true'),
            'n_siniestro_proforma_editar' => $data_comunicado->{$params[$tipo_proceso]['p39']},
            'Nombre_junta_act' => $data_comunicado->{$params[$tipo_proceso]['p40']},
            'F_estructuracion_act' => $data_comunicado->{$params[$tipo_proceso]['p41']},
            'F_dictamen_act' => $data_comunicado->{$params[$tipo_proceso]['p42']},
            'input_jrci_seleccionado_copia_editar' => $data_comunicado->{$params[$tipo_proceso]['p43']},
            'id_jrci_del_input' => $data_comunicado->{$params[$tipo_proceso]['p44']},
            'F_radicacion_contro_pri_cali_act' => $data_comunicado->{$params[$tipo_proceso]['p45']},
            'F_notifi_afiliado_act' => $data_comunicado->{$params[$tipo_proceso]['p46']},
            'Id_junta_act' => $data_comunicado->{$params[$tipo_proceso]['p47']},
            'tipo_de_preforma_editar' => $data_comunicado->{$params[$tipo_proceso]['p48']},
        ];
        
        $requestTMP = new Request();
        $requestTMP->setMethod('POST');
        $requestTMP->request->add($data);

        $this->DescargarProformasJuntas($requestTMP);

        return "PDF generado";
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
                ['Id_Asignacion', $newId_asignacion],
                ['Id_proceso', '3'],
                ['N_radicado', '<>', '']
            ])
            ->get();

            // Validar si la accion ejecutada tiene enviar a notificaciones
            
            $enviar_notificacion = BandejaNotifiController::evento_en_notificaciones($newId_evento,$newId_asignacion);

            foreach ($hitorialAgregarComunicado as &$comunicado) {
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
                    $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($newId_evento,$newId_asignacion,$comunicado["Id_Comunicado"]);
                }
            }
            
            return response()->json([
                'hitorialAgregarComunicado' => $hitorialAgregarComunicado,
                'enviar_notificacion' => $enviar_notificacion
            ]);
        }

        //Accion Actualizar status,nota del comunicado
        if($request->bandera == 'Actualizar'){
            $request->validate([
                'bandera' => 'required',
                'radicado' => 'required',
                'id_asignacion' => 'required'
            ]);
            
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where([
                ['N_radicado',$request->radicado],
                ['Id_Asignacion', $request->id_asignacion]])->update([
                'Nota' => $request->Nota,
                'Estado_notificacion' => $request->Estado_general
            ]);

            $mensajeResponse = 'Comunicado acualizado correctamente';
            
            return $mensajeResponse;
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
        $radiojrci_comunicado_editar = $request->radiojrci_comunicado_editar;
        $radiojnci_comunicado_editar = $request->radiojnci_comunicado_editar;
        $radioafiliado_comunicado_editar = $request->radioafiliado_comunicado_editar;
        $radioempresa_comunicado_editar = $request->radioempresa_comunicado_editar;
        $radioeps_comunicado_editar = $request->radioeps_comunicado_editar;
        $radioafp_comunicado_editar = $request->radioafp_comunicado_editar;
        $radioarl_comunicado_editar = $request->radioarl_comunicado_editar;
        $radioOtro_editar = $request->radioOtro_editar;
        $copiaComunicadoTotal = $request->agregar_copia_editar;
        $otro_destinatario_jrci = is_numeric($request->JRCI_Destinatario_editar) ? 1 : 0;
        if (!empty($copiaComunicadoTotal)) {
            $total_copia_comunicado = implode(", ", $copiaComunicadoTotal);                
        }else{
            $total_copia_comunicado = '';
        }

        if(!empty($radiojrci_comunicado_editar) && empty($radiojnci_comunicado_editar) && empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar)
            && empty($radioeps_comunicado_editar) && empty($radioafp_comunicado_editar) && empty($radioarl_comunicado_editar) && empty($radioOtro_editar)){
                $destinatario = 'Jrci';
        }
        elseif(empty($radiojrci_comunicado_editar) && !empty($radiojnci_comunicado_editar) && empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar)
            && empty($radioeps_comunicado_editar) && empty($radioafp_comunicado_editar) && empty($radioarl_comunicado_editar) && empty($radioOtro_editar)){
                $destinatario = 'Jnci';
        }
        elseif(empty($radiojrci_comunicado_editar) && empty($radiojnci_comunicado_editar) && !empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar)
            && empty($radioeps_comunicado_editar) && empty($radioafp_comunicado_editar) && empty($radioarl_comunicado_editar) && empty($radioOtro_editar)){
                $destinatario = 'Afiliado';
        }
        elseif(empty($radiojrci_comunicado_editar) && empty($radiojnci_comunicado_editar) && empty($radioafiliado_comunicado_editar) && !empty($radioempresa_comunicado_editar)
            && empty($radioeps_comunicado_editar) && empty($radioafp_comunicado_editar) && empty($radioarl_comunicado_editar) && empty($radioOtro_editar)){
                $destinatario = 'Empleador';
        }
        elseif(empty($radiojrci_comunicado_editar) && empty($radiojnci_comunicado_editar) && empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar)
            && !empty($radioeps_comunicado_editar) && empty($radioafp_comunicado_editar) && empty($radioarl_comunicado_editar) && empty($radioOtro_editar)){
                $destinatario = 'Eps';
        }
        elseif(empty($radiojrci_comunicado_editar) && empty($radiojnci_comunicado_editar) && empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar)
            && empty($radioeps_comunicado_editar) && !empty($radioafp_comunicado_editar) && empty($radioarl_comunicado_editar) && empty($radioOtro_editar)){
                $destinatario = 'Afp';
        }
        elseif(empty($radiojrci_comunicado_editar) && empty($radiojnci_comunicado_editar) && empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar)
            && empty($radioeps_comunicado_editar) && empty($radioafp_comunicado_editar) && !empty($radioarl_comunicado_editar) && empty($radioOtro_editar)){
                $destinatario = 'Arl';
        }
        elseif(empty($radiojrci_comunicado_editar) && empty($radiojnci_comunicado_editar) && empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar)
            && empty($radioeps_comunicado_editar) && empty($radioafp_comunicado_editar) && empty($radioarl_comunicado_editar) && !empty($radioOtro_editar)){
                $destinatario = 'Otro';
        }

        //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
        $dato_actualizar_n_siniestro = [
            'N_siniestro' => $request->N_siniestro,
        ];
        sigmel_informacion_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$Id_evento_editar]])
        ->update($dato_actualizar_n_siniestro);

        sleep(2);

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
            'JRCI_Destinatario' => $request->JRCI_Destinatario_editar,
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
            'JRCI_copia' => $request->JRCI_copia_editar,
            'Firmar_Comunicado' => $request->firmarcomunicado_editar,
            'Tipo_descarga' => $request->tipo_descarga,
            'Modulo_creacion' => $request->modulo_creacion,
            'Reemplazado' => 0,
            'N_siniestro' => $request->N_siniestro,
            'Otro_destinatario' => $otro_destinatario_jrci,
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
            'Accion_realizada' => "Se actualiza comunicado Juntas.",
            'Descripcion' => $request->asunto_editar,
        ];

        $entidades = ['Jrci', 'Jnci', 'Eps', 'Afp', 'Arl'];

        $principalDestinatario = function($entidades, $destinatario) {
            return in_array($destinatario, $entidades) ? strtoupper($destinatario) . "_comunicado" : $destinatario;
        };
        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        
        $mensajes = array(
            "parametro" => 'actualizar_comunicado',
            'status_pdf' => $this->generarPDF($request, $Id_comunicado_editar,$principalDestinatario($entidades,$destinatario),'actualizar'),
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

    public function DescargarProformasJuntas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $Id_comunicado = $request->Id_comunicado_act;

        //Traemos toda la información del comunicado
        $informacion_comunicado = $this->globalService->retornarInformacionComunicado($Id_comunicado);
        if($informacion_comunicado && !empty($informacion_comunicado[0]->F_registro)){
            $fecha_comunicado = $informacion_comunicado[0]->F_registro;
        }else{
            $fecha_comunicado = $date;
        }
        $F_elaboracion_correspondencia = $request->fecha_comunicado2_act;
        $N_radicado_documento = $request->radicado2_act;

        /* Datos Fijos en la proforma */
        $nombre_afiliado = $request->nombre_afiliado_comunicado2_act;
        $tipo_identificacion = $request->tipo_documento_comunicado2_act;
        $N_identificacion = $request->identificacion_comunicado2_act;
        $ID_evento = $request->id_evento_comunicado2_act;
        $Id_Asignacion = $request->Id_asignacion_act;
        $Id_proceso = $request->Id_procesos_act;
        $asunto = "<b>".strtoupper($request->asunto_act)."</b>";
        $cuerpo = $request->cuerpo_comunicado_act;
        $Cliente = $request->cliente_comunicado2_act;
        $Email_destinatario = $request->email_destinatario_act2;

        /* DATOS VARIABLES */
        $tipo_de_preforma = $request->tipo_de_preforma_editar;
        $detinatario_principal = $request->afiliado_comunicado_act;

        $indicativo = time();
        switch (true) {
            case ($tipo_de_preforma == "Oficio_Afiliado"):
                if ($detinatario_principal != "Otro") {
                    $nombre = $request->nombre_destinatario_act2;
                    $direccion = $request->direccion_destinatario_act2;
                    $telefono = $request->telefono_destinatario_act2;
                } else {
                    $nombre = $request->nombre_destinatario_act;
                    $direccion = $request->direccion_destinatario_act;
                    $telefono = $request->telefono_destinatario_act;
                }
                
                $departamento_destinatario_act = $request->departamento_destinatario_act;
                $ciudad_destinatario_act = $request->ciudad_destinatario_act;
                $departamento_pdf = $request->departamento_pdf;
                $ciudad_pdf = $request->ciudad_pdf;
        
                if (empty($departamento_destinatario_act) && empty($ciudad_destinatario_act)) {
                    $Id_departamento = $departamento_pdf;
                    $Id_municipio = $ciudad_pdf;
                }elseif(!empty($departamento_destinatario_act) && !empty($ciudad_destinatario_act)){
                    $Id_departamento = $departamento_destinatario_act;
                    $Id_municipio = $ciudad_destinatario_act;
                }
        
                $departamento = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Nombre_departamento')
                ->where('Id_departamento',$Id_departamento)
                ->get();
                sleep(2);
                $ciudad = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Nombre_municipio as Nombre_ciudad')
                ->where('Id_municipios',$Id_municipio)
                ->get();
        
                $departamento_final = $departamento[0]->Nombre_departamento;
                $ciudad_final = $ciudad[0]->Nombre_ciudad;
                $nro_radicado = $request->radicado2_act;
                $nombre_junta = $request->Nombre_junta_act;

                // Validamos si los checkbox esta marcados
                $edit_copias_afiliado = $request->edit_copia_afiliado ? 'Afiliado' : '';
                $edit_copias_empleador = $request->edit_copia_empleador ? 'Empleador' : '';
                $edit_copias_eps = $request->edit_copia_eps ? 'EPS' : '';
                $edit_copias_afp = $request->edit_copia_afp ? 'AFP' : '';
                $edit_copias_arl = $request->edit_copia_arl ? 'ARL' : '';
                $edit_copias_jrci = $request->edit_copia_jrci ? 'JRCI': '';
                $edit_copias_jnci = $request->edit_copia_jnci ? 'JNCI': '';
                $total_copias = array_filter(array(
                    'edit_copia_afiliado' => $edit_copias_afiliado,
                    'edit_copia_empleador' => $edit_copias_empleador,
                    'edit_copia_eps' => $edit_copias_eps,
                    'edit_copia_afp' => $edit_copias_afp,
                    'edit_copia_arl' => $edit_copias_arl,
                    'edit_copia_jrci' => $edit_copias_jrci,
                    'edit_copia_jnci' => $edit_copias_jnci,
                ));   
                sleep(2);

                // Filtramos las llaves del array
                extract($total_copias);
                // Creamos array para empezar a llenarlos con las copias
                $Agregar_copias = [];
                if (isset($edit_copia_afiliado)) {
                    $AfiliadoData = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('siae.Nombre_afiliado', 'siae.Direccion', 'siae.Telefono_contacto', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'siae.Email')
                    ->where([['siae.Nro_identificacion', $N_identificacion],['siae.ID_evento', $ID_evento]])
                    ->get();
                    $nombreAfiliado = $AfiliadoData[0]->Nombre_afiliado;
                    $direccionAfiliado = $AfiliadoData[0]->Direccion;
                    $telefonoAfiliado = $AfiliadoData[0]->Telefono_contacto;
                    $ciudadAfiliado = $AfiliadoData[0]->Nombre_ciudad;
                    $municipioAfiliado = $AfiliadoData[0]->Nombre_municipio;
                    $emailAfiliado = $AfiliadoData[0]->Email;            
                    $Agregar_copias['Afiliado'] = $nombreAfiliado."; ".$direccionAfiliado."; ".$emailAfiliado."; ".$telefonoAfiliado."; ".$ciudadAfiliado."; ".$municipioAfiliado.".";
                } 
                
                if(isset($edit_copia_empleador)) {            
                    $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
                    ->get();

                    $nombre_empleador = $datos_empleador[0]->Empresa;
                    $direccion_empleador = $datos_empleador[0]->Direccion;
                    $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
                    $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
                    $departamento_empleador = $datos_empleador[0]->Nombre_departamento;

                    $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$telefono_empleador."; ".$ciudad_empleador." - ".$departamento_empleador.".";
                }

                if (isset($edit_copia_eps)) {
                    $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_eps = $datos_eps[0]->Nombre_eps;
                    $direccion_eps = $datos_eps[0]->Direccion;
                    if ($datos_eps[0]->Otros_Telefonos != "") {
                        $telefonos_eps = $datos_eps[0]->Telefonos.",".$datos_eps[0]->Otros_Telefonos;
                    } else {
                        $telefonos_eps = $datos_eps[0]->Telefonos;
                    }
                    $ciudad_eps = $datos_eps[0]->Nombre_ciudad;
                    $departamento_eps = $datos_eps[0]->Nombre_departamento;

                    $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$telefonos_eps."; ".$ciudad_eps." - ".$departamento_eps;
                }

                if (isset($edit_copia_afp)) {
                    $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_afp = $datos_afp[0]->Nombre_afp;
                    $direccion_afp = $datos_afp[0]->Direccion;
                    if ($datos_afp[0]->Otros_Telefonos != "") {
                        $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
                    } else {
                        $telefonos_afp = $datos_afp[0]->Telefonos;
                    }
                    $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
                    $departamento_afp = $datos_afp[0]->Nombre_departamento;

                    $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$telefonos_afp."; ".$ciudad_afp." - ".$departamento_afp;
                }

                if (isset($edit_copia_arl)) {
                    $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_arl = $datos_arl[0]->Nombre_arl;
                    $direccion_arl = $datos_arl[0]->Direccion;
                    if ($datos_arl[0]->Otros_Telefonos != "") {
                        $telefonos_arl = $datos_arl[0]->Telefonos.",".$datos_arl[0]->Otros_Telefonos;
                    } else {
                        $telefonos_arl = $datos_arl[0]->Telefonos;
                    }
                    
                    $ciudad_arl = $datos_arl[0]->Nombre_ciudad;
                    $departamento_arl = $datos_arl[0]->Nombre_departamento;

                    $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$telefonos_arl."; ".$ciudad_arl." - ".$departamento_arl;
                };

                if(isset($edit_copia_jrci)){
                    if(!empty($request->input_jrci_seleccionado_copia_editar)){
                        $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                        ->select('sie.Nombre_entidad', 
                            'sie.Nit_entidad', 
                            'sie.Direccion', 
                            'sie.Telefonos',
                            'sie.Otros_Telefonos',
                            'sie.Emails',
                            'sldm.Id_departamento',
                            'sldm.Nombre_departamento',
                            'sldm1.Id_municipios',
                            'sldm1.Nombre_municipio as Nombre_ciudad'
                        )->where([
                            ['sie.Id_Entidad', $request->id_jrci_del_input]
                        ])->get();
    
                        $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
                        $direccion_jrci = $datos_jrci[0]->Direccion;

                        if ($datos_jrci[0]->Otros_Telefonos != "") {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
                        } else {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos;
                        }

                        $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
                        $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
    
                        $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
    
                    }else{
    
                        $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                        ->select('sie.Nombre_entidad', 
                            'sie.Nit_entidad', 
                            'sie.Direccion', 
                            'sie.Telefonos',
                            'sie.Otros_Telefonos',
                            'sie.Emails',
                            'sldm.Id_departamento',
                            'sldm.Nombre_departamento',
                            'sldm1.Id_municipios',
                            'sldm1.Nombre_municipio as Nombre_ciudad'
                        )->where([
                            ['sie.Id_Entidad', $request->jrci_califi_invalidez_copia_editar]
                        ])->get();
    
                        $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
                        $direccion_jrci = $datos_jrci[0]->Direccion;

                        if ($datos_jrci[0]->Otros_Telefonos != "") {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
                        } else {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos;
                        }

                        $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
                        $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
    
                        $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
                    }
                }
                
                if(isset($edit_copia_jnci)){
                    $datos_jnci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                    ->select('sie.Nombre_entidad', 
                        'sie.Nit_entidad', 
                        'sie.Direccion', 
                        'sie.Telefonos',
                        'sie.Otros_Telefonos',
                        'sie.Emails',
                        'sldm.Id_departamento',
                        'sldm.Nombre_departamento',
                        'sldm1.Id_municipios',
                        'sldm1.Nombre_municipio as Nombre_ciudad'
                    )->where([
                        ['sie.IdTipo_entidad', 5]
                    ])->limit(1)->get();
    
                    $nombre_jnci = $datos_jnci[0]->Nombre_entidad;
                    $direccion_jnci = $datos_jnci[0]->Direccion;

                    if ($datos_jnci[0]->Otros_Telefonos != "") {
                        $telefonos_jnci = $datos_jnci[0]->Telefonos.",".$datos_jnci[0]->Otros_Telefonos;
                    } else {
                        $telefonos_jnci = $datos_jnci[0]->Telefonos;
                    }

                    $ciudad_jnci = $datos_jnci[0]->Nombre_ciudad;
                    $departamento_jnci = $datos_jnci[0]->Nombre_departamento;

                    $Agregar_copias['JNCI'] = $nombre_jnci."; ".$direccion_jnci."; ".$telefonos_jnci."; ".$ciudad_jnci." - ".$departamento_jnci;
    
                }

                // validamos si el checkbox de la firma esta marcado
                $validarFirma = isset($request->firmarcomunicado_editar) ? 'firmar comunicado' : 'No lleva firma';
                
                if ($validarFirma == 'firmar comunicado') {            
                    $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente', 'Nombre_cliente')
                    ->where('Nombre_cliente', $Cliente)->get();
            
                    $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
                    ->where('Id_cliente', $idcliente[0]->Id_cliente)->limit(1)->get();

                    if(count($firmaclientecompleta) > 0){
                        $Firma_cliente = $firmaclientecompleta[0]->Firma;
                    }else{
                        $Firma_cliente = 'No firma';
                    }
                    
                }else{
                    $Firma_cliente = 'No firma';
                }

                /* Extraer el id del cliente */
                $dato_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
                ->select('Cliente')
                ->where([['ID_evento', $ID_evento]])
                ->get();

                if (count($dato_id_cliente)>0) {
                    $id_cliente = $dato_id_cliente[0]->Cliente;
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

                /* datos del footer */
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

                /* Recolección de datos para la proforma en pdf */
                $data = [
                    'id_cliente' => $id_cliente,
                    'fecha' => fechaFormateada($fecha_comunicado),
                    'nombre_afiliado' => $nombre_afiliado,
                    'tipo_identificacion' => $tipo_identificacion,
                    'num_identificacion' => $N_identificacion,
                    'ID_evento' => $ID_evento,
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'departamento_final' => $departamento_final,
                    'ciudad_final' => $ciudad_final,
                    'nro_radicado' => $nro_radicado,
                    'nombre_junta' => $nombre_junta,
                    'asunto' => $asunto,
                    'cuerpo' => $cuerpo,
                    'Agregar_copia' => $Agregar_copias,
                    'Firma_cliente' => $Firma_cliente,
                    'logo_header' => $logo_header,
                    'footer' => $footer,
                    'nombre_usuario' => $nombre_usuario,
                    'N_siniestro' => $request->n_siniestro_proforma_editar,
                    'email_destinatario' => $Email_destinatario
                ];

                $extension_proforma = "pdf";
                $ruta_proforma = '/Proformas/Proformas_Prev/Juntas/oficio_afiliado';
                $nombre_documento = "JUN_OFICIOA_AFILIADO";

                /* Creación del pdf */
                $pdf = app('dompdf.wrapper');
                $pdf->loadView($ruta_proforma, $data);
                
                // $nombre_pdf = "{$nombre_documento}_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.{$extension_proforma}";
                $nombre_pdf = "{$nombre_documento}_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}_{$indicativo}.{$extension_proforma}";

                //Obtener el contenido del PDF
                $output = $pdf->output();

                //Guardar el PDF en un archivo
                file_put_contents(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_pdf}"), $output);

                $actualizar_nombre_documento = [
                    'Nombre_documento' => $nombre_pdf
                ];
                
                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
                ->update($actualizar_nombre_documento);

                /* Inserción del registro de que fue descargado */
                // // Extraemos el id del servicio asociado
                // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
                // ->select('siae.Id_servicio')
                // ->where([
                //     ['siae.Id_Asignacion', $Id_Asignacion],
                //     ['siae.ID_evento', $ID_evento],
                //     ['siae.Id_proceso', $Id_proceso],
                // ])->get();

                // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

                // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
                // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                // ->select('Nombre_documento')
                // ->where([
                //     ['Nombre_documento', $nombre_pdf],
                // ])->get();
                
                // if(count($verficar_documento) == 0){

                //     // Se valida si antes de insertar la info del doc de Oficio_Afiliado ya hay un documento de Oficio_Juntas_JRCI
                //     // o Remision_Expediente_JRCI o Devolucion_Expediente_JRCI o Solicitud_Dictamen_JRCI o de tipo otro
                //     $nombre_docu_oficio_juntas = "JUN_OFICIO_JRCI_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                //     $nombre_docu_remision_expediente = "JUN_REM_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                //     $nombre_docu_devolucion_expediente = "JUN_DEV_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.docx";
                //     $nombre_docu_solicitud_dictamen = "JUN_SOL_DICTAMEN_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.docx";
                //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado_documento}.pdf";

                //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                //     ->select('Nombre_documento')
                //     ->whereIN('Nombre_documento', [$nombre_docu_oficio_juntas, $nombre_docu_remision_expediente, $nombre_docu_devolucion_expediente,
                //         $nombre_docu_solicitud_dictamen, $nombre_docu_otro
                //     ]
                //     )->get();

                //     // Si no existe info del documento de Oficio_Juntas_JRCI o Remision_Expediente_JRCI o Devolucion_Expediente_JRCI o Solicitud_Dictamen_JRCI o de tipo otro
                //     // inserta la info del documento de Oficio_Afiliado, De lo contrario hace una actualización de la info
                //     if (count($verificar_docu_otro) == 0) {
                //         $info_descarga_documento = [
                //             'Id_Asignacion' => $Id_Asignacion,
                //             'Id_proceso' => $Id_proceso,
                //             'Id_servicio' => $Id_servicio,
                //             'ID_evento' => $ID_evento,
                //             'Nombre_documento' => $nombre_pdf,
                //             'N_radicado_documento' => $N_radicado_documento,
                //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                //             'F_descarga_documento' => $date,
                //             'Nombre_usuario' => $nombre_usuario,
                //         ];
                        
                //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                //     }else{
                //         $info_descarga_documento = [
                //             'Id_Asignacion' => $Id_Asignacion,
                //             'Id_proceso' => $Id_proceso,
                //             'Id_servicio' => $Id_servicio,
                //             'ID_evento' => $ID_evento,
                //             'Nombre_documento' => $nombre_pdf,
                //             'N_radicado_documento' => $N_radicado_documento,
                //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                //             'F_descarga_documento' => $date,
                //             'Nombre_usuario' => $nombre_usuario,
                //         ];
                        
                //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                //         ->where([
                //             ['Id_Asignacion', $Id_Asignacion],
                //             ['N_radicado_documento', $N_radicado_documento],
                //             ['ID_evento', $ID_evento]
                //         ])
                //         ->update($info_descarga_documento);
                //     }
                // }

                // return $pdf->download($nombre_pdf);

                $datos = [
                    'indicativo' => $indicativo,
                    'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
                ];
                
                return response()->json($datos);

            break;

            case ($tipo_de_preforma == "Oficio_Juntas_JRCI"):
            
                $nro_radicado = $request->radicado2_act;

                /* Extraer el id del cliente */
                $dato_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
                ->select('Cliente')
                ->where([['ID_evento', $ID_evento]])
                ->get();

                if (count($dato_id_cliente)>0) {
                    $id_cliente = $dato_id_cliente[0]->Cliente;
                }

                /* 1. DATOS DE LA ENTIDAD REMITENTE */
                $informacion_cliente = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_clientes as sc')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sc.Id_Ciudad', '=', 'sldm.Id_municipios')
                ->select(
                    'sc.Nombre_cliente', 
                    'sc.Direccion',
                    DB::raw("CONCAT_WS(', ', sc.Telefono_principal, sc.Otros_telefonos) as Telefonos") ,
                    'sldm.Nombre_municipio as Nombre_ciudad'
                )
                ->where([['Id_cliente', $id_cliente]])
                ->get();

                if(count($informacion_cliente) > 0){
                    $nombre_cliente = $informacion_cliente[0]->Nombre_cliente;
                    $direccion_cliente = $informacion_cliente[0]->Direccion;
                    $teleonos_cliente = $informacion_cliente[0]->Telefonos;
                    $ciudad_cliente = $informacion_cliente[0]->Nombre_ciudad;
                }else{
                    $nombre_cliente = "";
                    $direccion_cliente = "";
                    $teleonos_cliente = "";
                    $ciudad_cliente = "";
                }

                /* 2. DATOS DE LA PERSONA REMITIDA */
                $informacion_persona_remitida = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'siae.Tipo_documento', '=', 'slp.Id_Parametro')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'siae.Genero', '=', 'slp2.Id_Parametro')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie2', 'siae.Id_arl', '=', 'sie2.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_laboral_eventos as sile', 'siae.ID_evento', '=', 'sile.ID_evento')
                ->select(
                    'siae.Nombre_afiliado',
                    'slp.Nombre_parametro as Tipo_documento',
                    'siae.Nro_identificacion',
                    'siae.Edad',
                    'slp2.Nombre_parametro as Genero',
                    'siae.F_nacimiento',
                    'siae.Direccion',
                    'siae.Telefono_contacto',
                    'sldm.Nombre_departamento',
                    'sldm2.Nombre_municipio as Nombre_ciudad',
                    'sie.Nombre_entidad as Nombre_AFP',
                    'sie2.Nombre_entidad as Nombre_ARL',
                    'sile.Tipo_empleado'
                )
                ->where([['siae.ID_evento', $ID_evento]])
                ->get();

                if (count($informacion_persona_remitida) > 0) {
                    $nombre_afiliado = $informacion_persona_remitida[0]->Nombre_afiliado;
                    $tipo_doc_afiliado = $informacion_persona_remitida[0]->Tipo_documento;
                    $num_identificacion_afiliado = $informacion_persona_remitida[0]->Nro_identificacion;
                    $edad_afiliado = $informacion_persona_remitida[0]->Edad;
                    $genero_afiliado = $informacion_persona_remitida[0]->Genero;
                    $fecha_nacimiento_afiliado = $informacion_persona_remitida[0]->F_nacimiento;
                    $direccion_afiliado = $informacion_persona_remitida[0]->Direccion;
                    $telefono_afiliado = $informacion_persona_remitida[0]->Telefono_contacto;
                    $departamento_afiliado = $informacion_persona_remitida[0]->Nombre_departamento;
                    $ciudad_afiliado = $informacion_persona_remitida[0]->Nombre_ciudad;
                    $tipo_vinculacion = $informacion_persona_remitida[0]->Tipo_empleado;
                    $afp_afiliado = $informacion_persona_remitida[0]->Nombre_AFP;
                    $arl_afiliado = $informacion_persona_remitida[0]->Nombre_ARL;
                } else {
                    $nombre_afiliado = "";
                    $tipo_doc_afiliado = "";
                    $num_identificacion_afiliado = "";
                    $edad_afiliado = "";
                    $genero_afiliado = "";
                    $fecha_nacimiento_afiliado = "";
                    $direccion_afiliado = "";
                    $telefono_afiliado = "";
                    $departamento_afiliado = "";
                    $ciudad_afiliado = "";
                    $tipo_vinculacion = "";
                    $afp_afiliado = "";
                    $arl_afiliado = "";
                }

                /* 3. DATOS LABORALES DE LA PERSONA REMITIDA */
                $informacion_laboral_persona_remitida = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'sile.Id_actividad_economica', '=', 'slae.Id_ActEco')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                ->select(
                    'sile.Empresa',
                    'sile.Direccion',
                    'sile.Telefono_empresa',
                    'slae.Nombre_actividad',
                    'sile.Cargo',
                    'sldm.Nombre_departamento',
                    'sldm2.Nombre_municipio as Nombre_ciudad'
                )
                ->where([['sile.ID_evento', $ID_evento]])
                ->get();

                if (count($informacion_laboral_persona_remitida) > 0) {
                    $nombre_empresa = $informacion_laboral_persona_remitida[0]->Empresa;
                    $direccion_empresa = $informacion_laboral_persona_remitida[0]->Direccion;
                    $telefono_empresa = $informacion_laboral_persona_remitida[0]->Telefono_empresa;
                    $actividad_empresa = $informacion_laboral_persona_remitida[0]->Nombre_actividad;
                    $cargo_empresa = $informacion_laboral_persona_remitida[0]->Cargo;
                    $departamento_empresa = $informacion_laboral_persona_remitida[0]->Nombre_departamento;
                    $ciudad_empresa = $informacion_laboral_persona_remitida[0]->Nombre_ciudad;
                } else {
                    $nombre_empresa = "";
                    $direccion_empresa = "";
                    $telefono_empresa = "";
                    $actividad_empresa = "";
                    $cargo_empresa = "";
                    $departamento_empresa = "";
                    $ciudad_empresa = "";
                }

                /* 4. MOTIVO DE LA REMISIÓN */

                $informacion_motivo_remision = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_controversia_juntas_eventos as sicje')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'sicje.Parte_controvierte_califi', '=', 'slp.Id_Parametro')
                ->select('sicje.Contro_pcl', 'sicje.Contro_origen', 'sicje.Contro_diagnostico', 'sicje.Contro_f_estructura', 'sicje.Contro_m_califi', 'slp.Nombre_parametro')
                ->where([['ID_evento', $ID_evento],['Id_Asignacion',$Id_Asignacion]])
                ->get();

                if (count($informacion_motivo_remision) > 0) {
                    $pcl = $informacion_motivo_remision[0]->Contro_pcl;
                    $origen = $informacion_motivo_remision[0]->Contro_origen;
                    $diagnosticos = $informacion_motivo_remision[0]->Contro_diagnostico;
                    $fecha_estructuracion = $informacion_motivo_remision[0]->Contro_f_estructura;
                    $manual_calificacion = $informacion_motivo_remision[0]->Contro_m_califi;
                    $parte_controvierte_califi = $informacion_motivo_remision[0]->Nombre_parametro;
                } else {
                    $pcl = "";
                    $origen = "";
                    $diagnosticos = "";
                    $fecha_estructuracion = "";
                    $manual_calificacion = "";
                    $parte_controvierte_califi = "";
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

                $nombre_usuario = Auth::user()->name;
                $cargo_usuario = Auth::user()->cargo;

                // validamos si el checkbox de la firma esta marcado
                /* $validarFirma = isset($request->firmarcomunicado_editar) ? 'firmar comunicado' : 'No lleva firma';
                            
                if ($validarFirma == 'firmar comunicado') {            
                    $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente', 'Nombre_cliente')
                    ->where('Nombre_cliente', $Cliente)->get();

                    $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
                    ->where('Id_cliente', $idcliente[0]->Id_cliente)->limit(1)->get();

                    if(count($firmaclientecompleta) > 0){
                        $Firma_cliente = $firmaclientecompleta[0]->Firma;
                    }else{
                        $Firma_cliente = 'No firma';
                    }
                    
                }else{
                    $Firma_cliente = 'No firma';
                } */

                /* Recolección de datos para la proforma en pdf */
                $data = [
                    'id_cliente' => $id_cliente,
                    'logo_header' => $logo_header,
                    'fecha' => fechaFormateada($fecha_comunicado),
                    'ID_evento' => $ID_evento,
                    'nro_radicado' => $nro_radicado,
                    'nombre_cliente' => $nombre_cliente,
                    'direccion_cliente' => $direccion_cliente,
                    'ciudad_cliente' => $ciudad_cliente,
                    'teleonos_cliente' => $teleonos_cliente,
                    'nombre_afiliado' => $nombre_afiliado,
                    'tipo_doc_afiliado' => $tipo_doc_afiliado,
                    'num_identificacion_afiliado' => $num_identificacion_afiliado,
                    'parte_controvierte_califi' => $parte_controvierte_califi,
                    'edad_afiliado' => $edad_afiliado,
                    'genero_afiliado' => $genero_afiliado,
                    'fecha_nacimiento_afiliado' => date('d/m/Y', strtotime($fecha_nacimiento_afiliado)),
                    'direccion_afiliado' => $direccion_afiliado,
                    'departamento_afiliado' => $departamento_afiliado,
                    'ciudad_afiliado' => $ciudad_afiliado,
                    'telefono_afiliado' => $telefono_afiliado,
                    'tipo_vinculacion' => $tipo_vinculacion,
                    'afp_afiliado' => $afp_afiliado,
                    'arl_afiliado' => $arl_afiliado,
                    'nombre_empresa' => $nombre_empresa,
                    'direccion_empresa' => $direccion_empresa,
                    'telefono_empresa' => $telefono_empresa,
                    'departamento_empresa' => $departamento_empresa,
                    'ciudad_empresa' => $ciudad_empresa,
                    'actividad_empresa' => $actividad_empresa,
                    'cargo_empresa' => $cargo_empresa,
                    'pcl' => $pcl,
                    'origen' => $origen,
                    'diagnosticos' => $diagnosticos,
                    'fecha_estructuracion' => $fecha_estructuracion,
                    'manual_calificacion' => $manual_calificacion,
                    'nombre_usuario' => $nombre_usuario,
                    'cargo_usuario' => $cargo_usuario,
                    'N_siniestro' => $request->n_siniestro_proforma_editar,
                ];

                $extension_proforma = "pdf";
                $ruta_proforma = '/Proformas/Proformas_Prev/Juntas/oficio_remisorio_jrci';
                $nombre_documento = "JUN_OFICIO_JRCI";

                /* Creación del pdf */
                $pdf = app('dompdf.wrapper');
                $pdf->loadView($ruta_proforma, $data);
                
                // $nombre_pdf = "{$nombre_documento}_{$Id_comunicado}_{$Id_Asignacion}_{$num_identificacion_afiliado}.{$extension_proforma}";
                $nombre_pdf = "{$nombre_documento}_{$Id_comunicado}_{$Id_Asignacion}_{$num_identificacion_afiliado}_{$indicativo}.{$extension_proforma}";

                //Obtener el contenido del PDF
                $output = $pdf->output();

                //Guardar el PDF en un archivo
                file_put_contents(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_pdf}"), $output);

                $actualizar_nombre_documento = [
                    'Nombre_documento' => $nombre_pdf
                ];

                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
                ->update($actualizar_nombre_documento);

                /* Inserción del registro de que fue descargado */
                // // Extraemos el id del servicio asociado
                // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
                // ->select('siae.Id_servicio')
                // ->where([
                //     ['siae.Id_Asignacion', $Id_Asignacion],
                //     ['siae.ID_evento', $ID_evento],
                //     ['siae.Id_proceso', $Id_proceso],
                // ])->get();

                // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

                // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
                // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                // ->select('Nombre_documento')
                // ->where([
                //     ['Nombre_documento', $nombre_pdf],
                // ])->get();
                
                // if(count($verficar_documento) == 0){

                //     // Se valida si antes de insertar la info del doc de Oficio_Juntas_JRCI ya hay un documento de Oficio_Afiliado
                //     // o Remision_Expediente_JRCI o Devolucion_Expediente_JRCI o Solicitud_Dictamen_JRCI o de tipo otro
                //     $nombre_docu_oficio_afiliado = "JUN_OFICIOA_AFILIADO_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                //     $nombre_docu_remision_expediente = "JUN_REM_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                //     $nombre_docu_devolucion_expediente = "JUN_DEV_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.docx";
                //     $nombre_docu_solicitud_dictamen = "JUN_SOL_DICTAMEN_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.docx";
                //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado_documento}.pdf";

                //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                //     ->select('Nombre_documento')
                //     ->whereIN('Nombre_documento', [$nombre_docu_oficio_afiliado, $nombre_docu_remision_expediente, $nombre_docu_devolucion_expediente,
                //         $nombre_docu_solicitud_dictamen, $nombre_docu_otro
                //     ]
                //     )->get();

                //     // Si no existe info del documento de Oficio_Afiliado o Remision_Expediente_JRCI o Devolucion_Expediente_JRCI o Solicitud_Dictamen_JRCI o de tipo otro
                //     // inserta la info del documento de Oficio_Juntas_JRCI, De lo contrario hace una actualización de la info
                //     if (count($verificar_docu_otro) == 0) {
                //         $info_descarga_documento = [
                //             'Id_Asignacion' => $Id_Asignacion,
                //             'Id_proceso' => $Id_proceso,
                //             'Id_servicio' => $Id_servicio,
                //             'ID_evento' => $ID_evento,
                //             'Nombre_documento' => $nombre_pdf,
                //             'N_radicado_documento' => $N_radicado_documento,
                //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                //             'F_descarga_documento' => $date,
                //             'Nombre_usuario' => $nombre_usuario,
                //         ];
                        
                //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                //     }else{
                //         $info_descarga_documento = [
                //             'Id_Asignacion' => $Id_Asignacion,
                //             'Id_proceso' => $Id_proceso,
                //             'Id_servicio' => $Id_servicio,
                //             'ID_evento' => $ID_evento,
                //             'Nombre_documento' => $nombre_pdf,
                //             'N_radicado_documento' => $N_radicado_documento,
                //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                //             'F_descarga_documento' => $date,
                //             'Nombre_usuario' => $nombre_usuario,
                //         ];
                        
                //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                //         ->where([
                //             ['Id_Asignacion', $Id_Asignacion],
                //             ['N_radicado_documento', $N_radicado_documento],
                //             ['ID_evento', $ID_evento]
                //         ])
                //         ->update($info_descarga_documento);
                //     }

                // }

                // return $pdf->download($nombre_pdf); 
                $datos = [
                    'indicativo' => $indicativo,
                    'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
                ];
                
                return response()->json($datos);

            break;

            case ($tipo_de_preforma == "Remision_Expediente_JRCI"):

                $nro_radicado = $request->radicado2_act;
                $Id_junta_act = $request->Id_junta_act;
                $f_notifi_afiliado_act = $request->F_notifi_afiliado_act;
                $f_notifi_afiliado_act = date("d/m/Y", strtotime($f_notifi_afiliado_act));
                $f_radicacion_contro_pri_cali_act = $request->F_radicacion_contro_pri_cali_act;
                $f_radicacion_contro_pri_cali_act = date("d/m/Y", strtotime($f_radicacion_contro_pri_cali_act));
                $f_estructuracion_act = $request->F_estructuracion_act;
                $f_estructuracion_act = date("d/m/Y", strtotime($f_estructuracion_act));
                /* Extraer el id del cliente */
                $dato_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
                ->select('Cliente')
                ->where([['ID_evento', $ID_evento]])
                ->get();

                if (count($dato_id_cliente)>0) {
                    $id_cliente = $dato_id_cliente[0]->Cliente;
                }

                // Datos Junta regional
                if ($Id_junta_act != "") {
                    $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['sie.Id_Entidad', $Id_junta_act]])->get();
                } else {
                    $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad','sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['sie.Id_Entidad', $request->jrci_califi_invalidez_comunicado_editar]])->get();
                }
                

                $array_datos_junta_regional = json_decode(json_encode($datos_junta_regional), true);

                if(count($array_datos_junta_regional)>0){
                    if ($Id_junta_act != "") {
                        $nombre_junta = $request->Nombre_junta_act;
                    }else{
                        $nombre_junta = $array_datos_junta_regional[0]["Nombre_entidad"];
                    }
                    $direccion_junta = $array_datos_junta_regional[0]["Direccion"];
                    $telefono_junta = $array_datos_junta_regional[0]["Telefonos"];
                    $departamento_junta = $array_datos_junta_regional[0]["Nombre_departamento"];
                    $ciudad_junta = $array_datos_junta_regional[0]["Nombre_ciudad"];
                }else{
                    $nombre_junta = "";
                    $direccion_junta = "";
                    $telefono_junta = "";
                    $departamento_junta = "";
                    $ciudad_junta = "";
                }

                // Informacion del afiliado
                $informacion_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'siae.Tipo_documento', '=', 'slp.Id_Parametro')
                ->select(
                    'slp.Nombre_parametro as Tipo_documento',
                    'siae.Nro_identificacion',
                    'siae.Nombre_afiliado',
                    'siae.Direccion',
                    'siae.Telefono_contacto',
                )
                ->where([['siae.ID_evento', $ID_evento]])
                ->get();

                if (count($informacion_afiliado) > 0) {
                    $tipo_doc_afiliado = $informacion_afiliado[0]->Tipo_documento;
                    $num_identificacion_afiliado = $informacion_afiliado[0]->Nro_identificacion;
                    $nombre_afiliado = $informacion_afiliado[0]->Nombre_afiliado;
                    $direccion_afiliado = $informacion_afiliado[0]->Direccion;
                    $telefono_afiliado = $informacion_afiliado[0]->Telefono_contacto;
                } else {
                    $tipo_doc_afiliado = "";
                    $num_identificacion_afiliado = "";
                    $nombre_afiliado = "";
                    $direccion_afiliado = "";
                    $telefono_afiliado = "";
                }

                // tipo de evento
                $info_tipo_evento = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
                ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'sie.Tipo_evento', '=', 'slte.Id_Evento')
                ->select('slte.Nombre_evento')
                ->where([['sie.ID_evento', $ID_evento]])
                ->get();

                if (count($info_tipo_evento) > 0) {
                    $tipo_evento = $info_tipo_evento[0]->Nombre_evento;
                } else {
                    $tipo_evento = "";
                }
                
                // nombres cie10
                $datos_diagnostico_motcalifi_contro = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
                ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
                ->select('side.Nombre_CIE10')
                ->where([['side.ID_evento',$ID_evento],
                    ['side.Id_proceso',$Id_proceso],
                    ['side.Item_servicio', '=', 'Controvertido Juntas'],
                    ['side.Estado', '=', 'Activo']
                ])->get(); 


                $array_datos_diagnostico_motcalifi_contro = json_decode(json_encode($datos_diagnostico_motcalifi_contro), true);

                if (count($array_datos_diagnostico_motcalifi_contro) > 0) {

                    $diagnosticos_cie10_jrci = array();
                
                        for ($i=0; $i < count($array_datos_diagnostico_motcalifi_contro); $i++) { 
                            $dx_concatenados = $array_datos_diagnostico_motcalifi_contro[$i]["Nombre_CIE10"];
                            array_push($diagnosticos_cie10_jrci, $dx_concatenados);
                        }
                
                        $string_diagnosticos_cie10_jrci = implode(", ", $diagnosticos_cie10_jrci);
                        $string_diagnosticos_cie10_jrci = $string_diagnosticos_cie10_jrci;

                } else {
                    $string_diagnosticos_cie10_jrci = "";
                }

                // Número de orden de pago 
                $informacion_nro_orden_pago = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_pagos_honorarios_eventos as p')
                ->select('p.N_orden_pago')
                ->where('p.ID_evento',  '=', $ID_evento)
                ->limit(1)->get();

                if (count($informacion_nro_orden_pago) > 0) {
                    $nro_orden_pago = $informacion_nro_orden_pago[0]->N_orden_pago;
                } else {
                    $nro_orden_pago = "";
                }
                
                // Tipos de controversia
                $informacion_tipos_controversia = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
                ->select('Contro_origen', 'Contro_pcl', 'Contro_diagnostico', 'Contro_f_estructura', 'Contro_m_califi', 'Observaciones', 'Porcentaje_pcl','F_estructuracion_contro')
                ->where([['ID_evento', $ID_evento],['Id_Asignacion',$Id_Asignacion]])
                ->get();

                if (count($informacion_tipos_controversia) > 0) {
                    $observaciones_controversia = $informacion_tipos_controversia[0]->Observaciones;
                    $fecha_estructuración = $informacion_tipos_controversia[0]->F_estructuracion_contro;
                    $fecha_estructuración = date("d/m/Y", strtotime($fecha_estructuración));
                    $porcentaje_pcl = $informacion_tipos_controversia[0]->Porcentaje_pcl;
                } else {
                    $observaciones_controversia = "";
                    $fecha_estructuración = "";
                    $porcentaje_pcl = "";
                }

                // dd("INFORMACIÓN TIPOS CONTROVERSIA : ",$informacion_tipos_controversia);

                $array_informacion_tipos_controversia = json_decode(json_encode($informacion_tipos_controversia), true);

                if (count($array_informacion_tipos_controversia) > 0) {

                    $array_tipos_controversias = array();

                    for ($a=0; $a < count($array_informacion_tipos_controversia); $a++) { 

                        if ($array_informacion_tipos_controversia[$a]["Contro_origen"] != "") {
                            array_push($array_tipos_controversias, $array_informacion_tipos_controversia[$a]["Contro_origen"]);
                        };

                        if ($array_informacion_tipos_controversia[$a]["Contro_pcl"] != "") {
                            array_push($array_tipos_controversias, $array_informacion_tipos_controversia[$a]["Contro_pcl"]);
                        };

                        if ($array_informacion_tipos_controversia[$a]["Contro_diagnostico"] != "") {
                            array_push($array_tipos_controversias, $array_informacion_tipos_controversia[$a]["Contro_diagnostico"]);
                        };

                        if ($array_informacion_tipos_controversia[$a]["Contro_f_estructura"] != "") {
                            array_push($array_tipos_controversias, $array_informacion_tipos_controversia[$a]["Contro_f_estructura"]);
                        };

                        if ($array_informacion_tipos_controversia[$a]["Contro_m_califi"] != "") {
                            array_push($array_tipos_controversias, $array_informacion_tipos_controversia[$a]["Contro_m_califi"]);
                        };

                    }
                    // $pcl = $informacion_tipos_controversia[0]->Contro_pcl;
                    // $origen = $informacion_tipos_controversia[0]->Contro_origen;
                    // $diagnosticos = $informacion_tipos_controversia[0]->Contro_diagnostico;
                    // $fecha_estructuracion = $informacion_tipos_controversia[0]->Contro_f_estructura;
                    // $manual_calificacion = $informacion_tipos_controversia[0]->Contro_m_califi;

                    $string_tipos_controversia = implode(", ", $array_tipos_controversias);
                    $string_tipos_controversia = $string_tipos_controversia;
                    

                } else {
                    // $pcl = "";
                    // $origen = "";
                    // $diagnosticos = "";
                    // $fecha_estructuracion = "";
                    // $manual_calificacion = "";
                    $string_tipos_controversia = "";
                }

                // Copias a partes interesadas
                $edit_copias_afiliado = $request->edit_copia_afiliado ? 'Afiliado' : '';
                $edit_copias_empleador = $request->edit_copia_empleador ? 'Empleador' : '';
                $edit_copias_eps = $request->edit_copia_eps ? 'EPS' : '';
                $edit_copias_afp = $request->edit_copia_afp ? 'AFP' : '';
                $edit_copias_arl = $request->edit_copia_arl ? 'ARL' : '';
                $edit_copias_jrci = $request->edit_copia_jrci ? 'JRCI': '';
                $edit_copias_jnci = $request->edit_copia_jnci ? 'JNCI': '';

                $total_copias = array_filter(array(
                    'edit_copia_afiliado' => $edit_copias_afiliado,
                    'edit_copia_empleador' => $edit_copias_empleador,
                    'edit_copia_eps' => $edit_copias_eps,
                    'edit_copia_afp' => $edit_copias_afp,
                    'edit_copia_arl' => $edit_copias_arl,
                    'edit_copia_jrci' => $edit_copias_jrci,
                    'edit_copia_jnci' => $edit_copias_jnci,
                ));   
                sleep(2);

                // Filtramos las llaves del array
                extract($total_copias);
                // Creamos array para empezar a llenarlos con las copias
                $Agregar_copias = [];
                if (isset($edit_copia_afiliado)) {
                    $AfiliadoData = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('siae.Nombre_afiliado', 'siae.Direccion', 'siae.Telefono_contacto', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'siae.Email')
                    ->where([['siae.Nro_identificacion', $N_identificacion],['siae.ID_evento', $ID_evento]])
                    ->get();
                    $nombreAfiliado = $AfiliadoData[0]->Nombre_afiliado;
                    $direccionAfiliado = $AfiliadoData[0]->Direccion;
                    $telefonoAfiliado = $AfiliadoData[0]->Telefono_contacto;
                    $ciudadAfiliado = $AfiliadoData[0]->Nombre_ciudad;
                    $municipioAfiliado = $AfiliadoData[0]->Nombre_municipio;
                    $emailAfiliado = $AfiliadoData[0]->Email;            
                    $Agregar_copias['Afiliado'] = $nombreAfiliado."; ".$direccionAfiliado."; ".$emailAfiliado."; ".$telefonoAfiliado."; ".$ciudadAfiliado."; ".$municipioAfiliado.".";
                    
                } 
                
                if(isset($edit_copia_empleador)) {            
                    $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
                    ->get();

                    $nombre_empleador = $datos_empleador[0]->Empresa;
                    $direccion_empleador = $datos_empleador[0]->Direccion;
                    $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
                    $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
                    $departamento_empleador = $datos_empleador[0]->Nombre_departamento;

                    $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$telefono_empleador."; ".$ciudad_empleador." - ".$departamento_empleador.".";
                }

                if (isset($edit_copia_eps)) {
                    $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_eps = $datos_eps[0]->Nombre_eps;
                    $direccion_eps = $datos_eps[0]->Direccion;
                    if ($datos_eps[0]->Otros_Telefonos != "") {
                        $telefonos_eps = $datos_eps[0]->Telefonos.",".$datos_eps[0]->Otros_Telefonos;
                    } else {
                        $telefonos_eps = $datos_eps[0]->Telefonos;
                    }
                    $ciudad_eps = $datos_eps[0]->Nombre_ciudad;
                    $departamento_eps = $datos_eps[0]->Nombre_departamento;

                    $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$telefonos_eps."; ".$ciudad_eps." - ".$departamento_eps;
                }

                if (isset($edit_copia_afp)) {
                    $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_afp = $datos_afp[0]->Nombre_afp;
                    $direccion_afp = $datos_afp[0]->Direccion;
                    if ($datos_afp[0]->Otros_Telefonos != "") {
                        $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
                    } else {
                        $telefonos_afp = $datos_afp[0]->Telefonos;
                    }
                    $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
                    $departamento_afp = $datos_afp[0]->Nombre_departamento;

                    $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$telefonos_afp."; ".$ciudad_afp." - ".$departamento_afp;
                }

                if (isset($edit_copia_arl)) {
                    $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_arl = $datos_arl[0]->Nombre_arl;
                    $direccion_arl = $datos_arl[0]->Direccion;
                    if ($datos_arl[0]->Otros_Telefonos != "") {
                        $telefonos_arl = $datos_arl[0]->Telefonos.",".$datos_arl[0]->Otros_Telefonos;
                    } else {
                        $telefonos_arl = $datos_arl[0]->Telefonos;
                    }
                    
                    $ciudad_arl = $datos_arl[0]->Nombre_ciudad;
                    $departamento_arl = $datos_arl[0]->Nombre_departamento;

                    $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$telefonos_arl."; ".$ciudad_arl." - ".$departamento_arl;
                };

                if(isset($edit_copia_jrci)){
                    if(!empty($request->input_jrci_seleccionado_copia_editar)){
                        $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                        ->select('sie.Nombre_entidad', 
                            'sie.Nit_entidad', 
                            'sie.Direccion', 
                            'sie.Telefonos',
                            'sie.Otros_Telefonos',
                            'sie.Emails',
                            'sldm.Id_departamento',
                            'sldm.Nombre_departamento',
                            'sldm1.Id_municipios',
                            'sldm1.Nombre_municipio as Nombre_ciudad'
                        )->where([
                            ['sie.Id_Entidad', $request->id_jrci_del_input]
                        ])->get();
    
                        $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
                        $direccion_jrci = $datos_jrci[0]->Direccion;

                        if ($datos_jrci[0]->Otros_Telefonos != "") {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
                        } else {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos;
                        }

                        $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
                        $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
    
                        $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
    
                    }else{
    
                        $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                        ->select('sie.Nombre_entidad', 
                            'sie.Nit_entidad', 
                            'sie.Direccion', 
                            'sie.Telefonos',
                            'sie.Otros_Telefonos',
                            'sie.Emails',
                            'sldm.Id_departamento',
                            'sldm.Nombre_departamento',
                            'sldm1.Id_municipios',
                            'sldm1.Nombre_municipio as Nombre_ciudad'
                        )->where([
                            ['sie.Id_Entidad', $request->jrci_califi_invalidez_copia_editar]
                        ])->get();
    
                        $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
                        $direccion_jrci = $datos_jrci[0]->Direccion;

                        if ($datos_jrci[0]->Otros_Telefonos != "") {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
                        } else {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos;
                        }

                        $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
                        $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
    
                        $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
                    }
                }
                
                if(isset($edit_copia_jnci)){
                    $datos_jnci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                    ->select('sie.Nombre_entidad', 
                        'sie.Nit_entidad', 
                        'sie.Direccion', 
                        'sie.Telefonos',
                        'sie.Otros_Telefonos',
                        'sie.Emails',
                        'sldm.Id_departamento',
                        'sldm.Nombre_departamento',
                        'sldm1.Id_municipios',
                        'sldm1.Nombre_municipio as Nombre_ciudad'
                    )->where([
                        ['sie.IdTipo_entidad', 5]
                    ])->limit(1)->get();
    
                    $nombre_jnci = $datos_jnci[0]->Nombre_entidad;
                    $direccion_jnci = $datos_jnci[0]->Direccion;

                    if ($datos_jnci[0]->Otros_Telefonos != "") {
                        $telefonos_jnci = $datos_jnci[0]->Telefonos.",".$datos_jnci[0]->Otros_Telefonos;
                    } else {
                        $telefonos_jnci = $datos_jnci[0]->Telefonos;
                    }

                    $ciudad_jnci = $datos_jnci[0]->Nombre_ciudad;
                    $departamento_jnci = $datos_jnci[0]->Nombre_departamento;

                    $Agregar_copias['JNCI'] = $nombre_jnci."; ".$direccion_jnci."; ".$telefonos_jnci."; ".$ciudad_jnci." - ".$departamento_jnci;
    
                }

                // validamos si el checkbox de la firma esta marcado
                $validarFirma = isset($request->firmarcomunicado_editar) ? 'firmar comunicado' : 'No lleva firma';
                            
                if ($validarFirma == 'firmar comunicado') {            
                    $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente', 'Nombre_cliente')
                    ->where('Nombre_cliente', $Cliente)->get();

                    $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
                    ->where('Id_cliente', $idcliente[0]->Id_cliente)->limit(1)->get();

                    if(count($firmaclientecompleta) > 0){
                        $Firma_cliente = $firmaclientecompleta[0]->Firma;
                    }else{
                        $Firma_cliente = 'No firma';
                    }
                    
                }else{
                    $Firma_cliente = 'No firma';
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

                

                /* datos del footer */
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

                /* Recolección de datos para la proforma en pdf */
                $data = [
                    'ID_evento' => $ID_evento,
                    'id_cliente' => $id_cliente,
                    'fecha' => fechaFormateada($fecha_comunicado),
                    'logo_header' => $logo_header,
                    'nro_radicado' => $nro_radicado,
                    'nro_orden_pago' => $nro_orden_pago,
                    'f_notifi_afiliado_act' => $f_notifi_afiliado_act,
                    'f_radicacion_contro_pri_cali_act' => $f_radicacion_contro_pri_cali_act,
                    'f_estructuracion_act' => $f_estructuracion_act,
                    'asunto' => $asunto,
                    'cuerpo' => $cuerpo,
                    'nombre_junta' => $nombre_junta,
                    'direccion_junta' => $direccion_junta,
                    'telefono_junta' => $telefono_junta,
                    'departamento_junta' => $departamento_junta,
                    'ciudad_junta' => $ciudad_junta,
                    'tipo_doc_afiliado' => $tipo_doc_afiliado,
                    'num_identificacion_afiliado' => $num_identificacion_afiliado,
                    'nombre_afiliado' => $nombre_afiliado,
                    'direccion_afiliado' => $direccion_afiliado,
                    'telefono_afiliado' => $telefono_afiliado,
                    'tipo_evento' => $tipo_evento,
                    'string_diagnosticos_cie10_jrci' => $string_diagnosticos_cie10_jrci,
                    'string_tipos_controversia' => $string_tipos_controversia,
                    'Agregar_copia' => $Agregar_copias,
                    'Firma_cliente' => $Firma_cliente,
                    'nombre_usuario' => $nombre_usuario,
                    'footer' => $footer,
                    'N_siniestro' => $request->n_siniestro_proforma_editar,
                    'observaciones_controversia' => $observaciones_controversia,
                    'fecha_estructuración' => $fecha_estructuración,
                    'porcentaje_pcl' => $porcentaje_pcl
                    // 'footer_dato_1' => $footer_dato_1,
                    // 'footer_dato_2' => $footer_dato_2,
                    // 'footer_dato_3' => $footer_dato_3,
                    // 'footer_dato_4' => $footer_dato_4,
                    // 'footer_dato_5' => $footer_dato_5
                ];

                $extension_proforma = "pdf";
                $ruta_proforma = '/Proformas/Proformas_Prev/Juntas/remision_expediente';
                $nombre_documento = "JUN_REM_EXPEDIENTE";

                /* Creación del pdf */
                $pdf = app('dompdf.wrapper');
                $pdf->loadView($ruta_proforma, $data);
                
                // $nombre_pdf = "{$nombre_documento}_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.{$extension_proforma}";
                $nombre_pdf = "{$nombre_documento}_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}_{$indicativo}.{$extension_proforma}";

                //Obtener el contenido del PDF
                $output = $pdf->output();
                //Guardar el PDF en un archivo
                file_put_contents(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_pdf}"), $output);

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
                //     ['siae.ID_evento', $ID_evento],
                //     ['siae.Id_proceso', $Id_proceso],
                // ])->get();

                // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

                // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
                // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                // ->select('Nombre_documento')
                // ->where([
                //     ['Nombre_documento', $nombre_pdf],
                // ])->get();
                
                // if(count($verficar_documento) == 0){

                //     // Se valida si antes de insertar la info del doc de Remision_Expediente_JRCI ya hay un documento de Oficio_Afiliado
                //     // o Oficio_Juntas_JRCI o Devolucion_Expediente_JRCI o Solicitud_Dictamen_JRCI o de tipo otro
                //     $nombre_docu_oficio_afiliado = "JUN_OFICIOA_AFILIADO_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                //     $nombre_docu_oficio_juntas = "JUN_OFICIO_JRCI_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                //     $nombre_docu_devolucion_expediente = "JUN_DEV_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.docx";
                //     $nombre_docu_solicitud_dictamen = "JUN_SOL_DICTAMEN_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.docx";
                //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado_documento}.pdf";

                //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                //     ->select('Nombre_documento')
                //     ->whereIN('Nombre_documento', [$nombre_docu_oficio_afiliado, $nombre_docu_oficio_juntas, $nombre_docu_devolucion_expediente,
                //         $nombre_docu_solicitud_dictamen, $nombre_docu_otro
                //     ]
                //     )->get();

                //     // Si no existe info del documento de Oficio_Afiliado, Oficio_Juntas_JRCI o Devolucion_Expediente_JRCI o Solicitud_Dictamen_JRCI o de tipo otro
                //     // inserta la info del documento de Remision_Expediente_JRCI, De lo contrario hace una actualización de la info
                //     if (count($verificar_docu_otro) == 0) {
                //         $info_descarga_documento = [
                //             'Id_Asignacion' => $Id_Asignacion,
                //             'Id_proceso' => $Id_proceso,
                //             'Id_servicio' => $Id_servicio,
                //             'ID_evento' => $ID_evento,
                //             'Nombre_documento' => $nombre_pdf,
                //             'N_radicado_documento' => $N_radicado_documento,
                //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                //             'F_descarga_documento' => $date,
                //             'Nombre_usuario' => $nombre_usuario,
                //         ];
                        
                //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                //     }else{
                //         $info_descarga_documento = [
                //             'Id_Asignacion' => $Id_Asignacion,
                //             'Id_proceso' => $Id_proceso,
                //             'Id_servicio' => $Id_servicio,
                //             'ID_evento' => $ID_evento,
                //             'Nombre_documento' => $nombre_pdf,
                //             'N_radicado_documento' => $N_radicado_documento,
                //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                //             'F_descarga_documento' => $date,
                //             'Nombre_usuario' => $nombre_usuario,
                //         ];
                        
                //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                //         ->where([
                //             ['Id_Asignacion', $Id_Asignacion],
                //             ['N_radicado_documento', $N_radicado_documento],
                //             ['ID_evento', $ID_evento]
                //         ])
                //         ->update($info_descarga_documento);
                //     }
                // }

                // return $pdf->download($nombre_pdf); 
                $datos = [
                    'indicativo' => $indicativo,
                    'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
                ];
                
                return response()->json($datos);
            break;
            
            case ($tipo_de_preforma == "Devolucion_Expediente_JRCI"):

                $nro_radicado = $request->radicado2_act;
                $Id_junta_act = $request->Id_junta_act;
                //Fecha envio JRCI
                $fecha_envio_jrci = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
                    ->where([['ID_evento',$ID_evento],['Id_Asignacion',$Id_Asignacion]])
                    ->value('F_envio_jrci');

                /* Extraer el id del cliente */
                $dato_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
                ->select('Cliente')
                ->where([['ID_evento', $ID_evento]])
                ->get();

                if (count($dato_id_cliente)>0) {
                    $id_cliente = $dato_id_cliente[0]->Cliente;
                }

                /* Tipos de controversia primera calificación */
                $datos_tipo_controversia = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
                ->select('Contro_origen', 'Contro_pcl', 'Contro_diagnostico', 'Contro_f_estructura', 'Contro_m_califi')
                ->where([['ID_evento',$ID_evento],
                    ['Id_Asignacion',$Id_Asignacion],
                ])->get();
                $array_datos_tipo_controversia = json_decode(json_encode($datos_tipo_controversia), true);

                if (count($array_datos_tipo_controversia) > 0) {

                    // Obtener los valores del primer elemento del array
                    $controversias = array_values($array_datos_tipo_controversia[0]);
                    $controversias = array_filter($controversias); // Eliminar valores null
                    
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
                        $string_tipos_controversia = str_replace('% PCL', '%PCL', $string_tipos_controversia);
                        // $string_tipos_controversia = str_replace('Fecha estructuración', 'la Fecha de estructuración', $string_tipos_controversia);
                        // $string_tipos_controversia = str_replace('Manual de calificación', 'el Manual de calificación', $string_tipos_controversia);

                    } else {
                        $string_tipos_controversia = "";
                    }

                } else {
                    $string_tipos_controversia = "";
                }
                
                // Datos Junta regional
                if ($Id_junta_act != "") {
                    $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['sie.Id_Entidad', $Id_junta_act]])->get();
                }else{
                    $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['sie.Id_Entidad', $request->jrci_califi_invalidez_comunicado_editar]])->get();
                }

                $array_datos_junta_regional = json_decode(json_encode($datos_junta_regional), true);

                if(count($array_datos_junta_regional)>0){
                    if ($Id_junta_act != "") {
                        $nombre_junta = $request->Nombre_junta_act;
                    }else{
                        $nombre_junta = $array_datos_junta_regional[0]["Nombre_entidad"];
                    }
                    $direccion_junta = $array_datos_junta_regional[0]["Direccion"];
                    $telefono_junta = $array_datos_junta_regional[0]["Telefonos"];
                    $departamento_junta = $array_datos_junta_regional[0]["Nombre_departamento"];
                    $ciudad_junta = $array_datos_junta_regional[0]["Nombre_ciudad"];
                }else{
                    $nombre_junta = "";
                    $direccion_junta = "";
                    $telefono_junta = "";
                    $departamento_junta = "";
                    $ciudad_junta = "";
                }

                // Informacion del afiliado
                $informacion_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'siae.Tipo_documento', '=', 'slp.Id_Parametro')
                ->select(
                    'slp.Nombre_parametro as Tipo_documento',
                    'siae.Nro_identificacion',
                    'siae.Nombre_afiliado',
                )
                ->where([['siae.ID_evento', $ID_evento]])
                ->get();

                if (count($informacion_afiliado) > 0) {
                    $tipo_doc_afiliado = $informacion_afiliado[0]->Tipo_documento;
                    $num_identificacion_afiliado = $informacion_afiliado[0]->Nro_identificacion;
                    $nombre_afiliado = $informacion_afiliado[0]->Nombre_afiliado;
                } else {
                    $tipo_doc_afiliado = "";
                    $num_identificacion_afiliado = "";
                    $nombre_afiliado = "";
                }

                // Copias a partes interesadas
                $edit_copias_afiliado = $request->edit_copia_afiliado ? 'Afiliado' : '';
                $edit_copias_empleador = $request->edit_copia_empleador ? 'Empleador' : '';
                $edit_copias_eps = $request->edit_copia_eps ? 'EPS' : '';
                $edit_copias_afp = $request->edit_copia_afp ? 'AFP' : '';
                $edit_copias_arl = $request->edit_copia_arl ? 'ARL' : '';
                $edit_copias_jrci = $request->edit_copia_jrci ? 'JRCI': '';
                $edit_copias_jnci = $request->edit_copia_jnci ? 'JNCI': '';

                $total_copias = array_filter(array(
                    'edit_copia_afiliado' => $edit_copias_afiliado,
                    'edit_copia_empleador' => $edit_copias_empleador,
                    'edit_copia_eps' => $edit_copias_eps,
                    'edit_copia_afp' => $edit_copias_afp,
                    'edit_copia_arl' => $edit_copias_arl,
                    'edit_copia_jrci' => $edit_copias_jrci,
                    'edit_copia_jnci' => $edit_copias_jnci,
                ));   
                sleep(2);

                // Filtramos las llaves del array
                extract($total_copias);
                // Creamos array para empezar a llenarlos con las copias
                $Agregar_copias = [];
                if (isset($edit_copia_afiliado)) {
                    $AfiliadoData = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('siae.Nombre_afiliado', 'siae.Direccion', 'siae.Telefono_contacto', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'siae.Email')
                    ->where([['siae.Nro_identificacion', $N_identificacion],['siae.ID_evento', $ID_evento]])
                    ->get();
                    $nombreAfiliado = $AfiliadoData[0]->Nombre_afiliado;
                    $direccionAfiliado = $AfiliadoData[0]->Direccion;
                    $telefonoAfiliado = $AfiliadoData[0]->Telefono_contacto;
                    $ciudadAfiliado = $AfiliadoData[0]->Nombre_ciudad;
                    $municipioAfiliado = $AfiliadoData[0]->Nombre_municipio;
                    $emailAfiliado = $AfiliadoData[0]->Email;            
                    $Agregar_copias['Afiliado'] = $nombreAfiliado."; ".$direccionAfiliado."; ".$emailAfiliado."; ".$telefonoAfiliado."; ".$ciudadAfiliado."; ".$municipioAfiliado.".";  
                } 
                
                if(isset($edit_copia_empleador)) {            
                    $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
                    ->get();

                    $nombre_empleador = $datos_empleador[0]->Empresa;
                    $direccion_empleador = $datos_empleador[0]->Direccion;
                    $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
                    $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
                    $departamento_empleador = $datos_empleador[0]->Nombre_departamento;

                    $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$telefono_empleador."; ".$ciudad_empleador." - ".$departamento_empleador.".";
                }

                if (isset($edit_copia_eps)) {
                    $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_eps = $datos_eps[0]->Nombre_eps;
                    $direccion_eps = $datos_eps[0]->Direccion;
                    if ($datos_eps[0]->Otros_Telefonos != "") {
                        $telefonos_eps = $datos_eps[0]->Telefonos.",".$datos_eps[0]->Otros_Telefonos;
                    } else {
                        $telefonos_eps = $datos_eps[0]->Telefonos;
                    }
                    $ciudad_eps = $datos_eps[0]->Nombre_ciudad;
                    $departamento_eps = $datos_eps[0]->Nombre_departamento;

                    $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$telefonos_eps."; ".$ciudad_eps." - ".$departamento_eps;
                }

                if (isset($edit_copia_afp)) {
                    $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_afp = $datos_afp[0]->Nombre_afp;
                    $direccion_afp = $datos_afp[0]->Direccion;
                    if ($datos_afp[0]->Otros_Telefonos != "") {
                        $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
                    } else {
                        $telefonos_afp = $datos_afp[0]->Telefonos;
                    }
                    $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
                    $departamento_afp = $datos_afp[0]->Nombre_departamento;

                    $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$telefonos_afp."; ".$ciudad_afp." - ".$departamento_afp;
                }

                if (isset($edit_copia_arl)) {
                    $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_arl = $datos_arl[0]->Nombre_arl;
                    $direccion_arl = $datos_arl[0]->Direccion;
                    if ($datos_arl[0]->Otros_Telefonos != "") {
                        $telefonos_arl = $datos_arl[0]->Telefonos.",".$datos_arl[0]->Otros_Telefonos;
                    } else {
                        $telefonos_arl = $datos_arl[0]->Telefonos;
                    }
                    
                    $ciudad_arl = $datos_arl[0]->Nombre_ciudad;
                    $departamento_arl = $datos_arl[0]->Nombre_departamento;

                    $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$telefonos_arl."; ".$ciudad_arl." - ".$departamento_arl;
                };

                if(isset($edit_copia_jrci)){
                    if(!empty($request->input_jrci_seleccionado_copia_editar)){
                        $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                        ->select('sie.Nombre_entidad', 
                            'sie.Nit_entidad', 
                            'sie.Direccion', 
                            'sie.Telefonos',
                            'sie.Otros_Telefonos',
                            'sie.Emails',
                            'sldm.Id_departamento',
                            'sldm.Nombre_departamento',
                            'sldm1.Id_municipios',
                            'sldm1.Nombre_municipio as Nombre_ciudad'
                        )->where([
                            ['sie.Id_Entidad', $request->id_jrci_del_input]
                        ])->get();
    
                        $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
                        $direccion_jrci = $datos_jrci[0]->Direccion;

                        if ($datos_jrci[0]->Otros_Telefonos != "") {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
                        } else {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos;
                        }

                        $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
                        $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
    
                        $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
    
                    }else{
    
                        $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                        ->select('sie.Nombre_entidad', 
                            'sie.Nit_entidad', 
                            'sie.Direccion', 
                            'sie.Telefonos',
                            'sie.Otros_Telefonos',
                            'sie.Emails',
                            'sldm.Id_departamento',
                            'sldm.Nombre_departamento',
                            'sldm1.Id_municipios',
                            'sldm1.Nombre_municipio as Nombre_ciudad'
                        )->where([
                            ['sie.Id_Entidad', $request->jrci_califi_invalidez_copia_editar]
                        ])->get();
    
                        $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
                        $direccion_jrci = $datos_jrci[0]->Direccion;

                        if ($datos_jrci[0]->Otros_Telefonos != "") {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
                        } else {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos;
                        }

                        $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
                        $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
    
                        $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
                    }
                }
                
                if(isset($edit_copia_jnci)){
                    $datos_jnci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                    ->select('sie.Nombre_entidad', 
                        'sie.Nit_entidad', 
                        'sie.Direccion', 
                        'sie.Telefonos',
                        'sie.Otros_Telefonos',
                        'sie.Emails',
                        'sldm.Id_departamento',
                        'sldm.Nombre_departamento',
                        'sldm1.Id_municipios',
                        'sldm1.Nombre_municipio as Nombre_ciudad'
                    )->where([
                        ['sie.IdTipo_entidad', 5]
                    ])->limit(1)->get();
    
                    $nombre_jnci = $datos_jnci[0]->Nombre_entidad;
                    $direccion_jnci = $datos_jnci[0]->Direccion;

                    if ($datos_jnci[0]->Otros_Telefonos != "") {
                        $telefonos_jnci = $datos_jnci[0]->Telefonos.",".$datos_jnci[0]->Otros_Telefonos;
                    } else {
                        $telefonos_jnci = $datos_jnci[0]->Telefonos;
                    }

                    $ciudad_jnci = $datos_jnci[0]->Nombre_ciudad;
                    $departamento_jnci = $datos_jnci[0]->Nombre_departamento;

                    $Agregar_copias['JNCI'] = $nombre_jnci."; ".$direccion_jnci."; ".$telefonos_jnci."; ".$ciudad_jnci." - ".$departamento_jnci;
    
                }

                // validamos si el checkbox de la firma esta marcado
                $validarFirma = isset($request->firmarcomunicado_editar) ? 'firmar comunicado' : 'No lleva firma';
                                        
                if ($validarFirma == 'firmar comunicado') {            
                    $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente', 'Nombre_cliente')
                    ->where('Nombre_cliente', $Cliente)->get();

                    $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
                    ->where('Id_cliente', $idcliente[0]->Id_cliente)->limit(1)->get();

                    if(count($firmaclientecompleta) > 0){
                        $Firma_cliente = $firmaclientecompleta[0]->Firma;
                    }else{
                        $Firma_cliente = 'No firma';
                    }
                    
                }else{
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

                //Footer
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

                /* datos del footer */
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
                $test = $header->addTextRun(['alignment' => 'right']);
                $test->addText('Página ');
                $test->addField('PAGE');
                $test->addText(' de ');
                $test->addField('NUMPAGES');
                $header->addTextBreak();

                // Creación de Contenido
                $fecha_formateada = fechaFormateada($fecha_comunicado);
                $section->addText('Bogotá D.C. '.$fecha_formateada, array('bold' => true), array('align' => 'right'));
                $section->addTextBreak();

                $table = $section->addTable();

    
                $table->addRow();


                $cell1 = $table->addCell(10000);

                $textRun1 = $cell1->addTextRun(array('alignment'=>'both'));
                $textRun1->addText('Señores: ',array('bold' => true));
                $textRun1->addTextBreak();
                $textRun1->addText($nombre_junta);
                $textRun1->addTextBreak();
                $textRun1->addText($direccion_junta);
                $textRun1->addTextBreak();
                $textRun1->addText($telefono_junta);
                $textRun1->addTextBreak();
                $textRun1->addText($ciudad_junta);
                
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
                    //                         <p><b>'.$tipo_doc_afiliado." ".$num_identificacion_afiliado.'</b></p>
                    //                         <p><b>Siniestro: '.$request->n_siniestro_proforma_editar.'</b></p>
                    //                     </td>
                    //                 </tr>
                    //             </table>
                    //         </td>
                    //     </tr>
                    // </table>';

                    // Html::addHtml($section, $htmltabla1, false, true);
                    $table = $section->addTable(array('alignment'=>'center'));
    
                    $table->addRow();

                    $cell1 = $table->addCell(10000);

                    $asuntoyafiliado = $cell1->addTextRun(array('alignment'=>'left'));
                    $asuntoyafiliado->addText('Asunto: ', array('bold' => true));
                    $asuntoyafiliado->addText($request->asunto_act, array('bold' => true));
                    $asuntoyafiliado->addTextBreak();
                    $asuntoyafiliado->addText($tipo_doc_afiliado." ".$num_identificacion_afiliado." ".$nombre_afiliado);
                    $asuntoyafiliado->addTextBreak();
                    $asuntoyafiliado->addText('Siniestro: ', array('bold' => true));
                    $asuntoyafiliado->addText($request->n_siniestro_proforma_editar);


                    // Configuramos el reemplazo de las etiquetas del cuerpo del comunicado
                    $patron1 = '/\{\{\$nombre_junta\}\}/';
                    $patron2 = '(Anotar Fecha de envío de expediente)';
                    $patron3 = '(Anotar la causal de controversia)';
                
                    $cuerpo = str_replace(['<br>', '<br/>', '<br />', '</br>'], '', $cuerpo);

                    $cuerpo_modificado = str_replace('</p>', '</p><br></br>', $cuerpo);
                    $cuerpo_modificado = str_replace('<p><br>', ' ', $cuerpo_modificado);

                    //Fecha de envio a JRCI
                    $fecha_envio_jrci = $fecha_envio_jrci ? date('d/m/Y',strtotime($fecha_envio_jrci)) : 'SIN FECHA';

                    if(preg_match($patron2,$cuerpo_modificado) && preg_match($patron3, $cuerpo_modificado)){
                        $cuerpo_modificado = str_replace('(Anotar Fecha de envío de expediente)', '<b>'.$fecha_envio_jrci.'</b>', $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('(Anotar la causal de controversia)', $string_tipos_controversia, $cuerpo_modificado);
                    }

                    if (preg_match($patron1, $cuerpo_modificado)) {

                        $cuerpo_modificado = str_replace('{{$nombre_junta}}', $nombre_junta, $cuerpo_modificado);
                        $cuerpo = $cuerpo_modificado;

                    } else {
                        $cuerpo = "";
                    }

                    $section->addTextBreak();
                    Html::addHtml($section, $cuerpo, false, false);
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
                        //$width = count($coincidencias)>0 ? $coincidencias[1] : '100px'; // Valor de width
                        //$height = count($coincidencias)>0 ? $coincidencias[2] : '70px'; // Valor de height
                        $width = 150; // Valor de width
                        $height = 80; // Valor de height
                    
                        $nuevoStyle = 'width="'.$width.'" height="'.$height.'"';
                        $htmlModificado = reemplazarStyleImg($Firma_cliente, $nuevoStyle);
                        Html::addHtml($section, $htmlModificado, false, true);
                    }else{
                        $section->addText($Firma_cliente);
                    }
                    
                    $section->addTextBreak();
                    $section->addText('Departamento de medicina laboral', array('bold' => true));
                    $section->addTextBreak();
                    $section->addText('Convenio Codess - Seguros de Vida Alfa S.A', array('bold' => true));
                    $section->addTextBreak();
                    // $section->addText('Elaboró: '.$nombre_usuario, array('bold' => true));
                    // $section->addTextBreak();

                    // Configuramos la tabla de copias a partes interesadas
                    // $htmltabla2 = '<table style="text-align: justify; width:100%; border-collapse: collapse; margin-left: auto; margin-right: auto;">';
                    // if (count($Agregar_copias) == 0) {
                    //     $htmltabla2 .= '
                    //         <tr>
                    //             <td style="border: 1px solid #000; padding: 5px;"><span style="font-weight:bold;">Copia: </span>No se registran copias</td>                                                                                
                    //         </tr>';
                    // } else {
                    //     $htmltabla2 .= '
                    //         <tr>
                    //             <td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Copia:</span></td>                            
                    //         </tr>';

                    //     $Afiliado = 'Afiliado';
                    //     $Empleador = 'Empleador';
                    //     $EPS = 'EPS';
                    //     $AFP = 'AFP';
                    //     $ARL = 'ARL';
                    //     $JRCI = 'JRCI';
                    //     $JNCI = 'JNCI';

                    //     if (isset($Agregar_copias[$Afiliado])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Afiliado: </span>' . $Agregar_copias['Afiliado'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$Empleador])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Empleador: </span>' . $Agregar_copias['Empleador'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$EPS])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">EPS: </span>' . $Agregar_copias['EPS'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$AFP])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">AFP: </span>' . $Agregar_copias['AFP'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$ARL])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">ARL: </span>' . $Agregar_copias['ARL'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$JRCI])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">JRCI: </span>' . $Agregar_copias['JRCI'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$JNCI])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">JNCI: </span>' . $Agregar_copias['JNCI'] . '</td></tr>';
                    //     }
                    // }

                    // $htmltabla2 .= '</table>';
                    // Html::addHtml($section, $htmltabla2, false, true);
                    // $section->addTextBreak();

                    //Cuadro con la información del siniestro
                    $tableCuadro = $section->addTable();

                    $tableCuadro->addRow();
                    
                    $cellCuadro = $tableCuadro->addCell(10000);
                    //Cuadro
                    $cuadro = $cellCuadro->addTable(array('borderSize' => 12, 'borderColor' => '000000', 'width' => 80*60, 'alignment'=>'center'));
                    $cuadro->addRow();
                    $celdaCuadro = $cuadro->addCell();
                    $cuadroTextRun = $celdaCuadro->addTextRun(array('alignment'=>'left'));
                    $cuadroTextRun->addText('Nro. Radicado: ', array('bold' => true));
                    $cuadroTextRun->addTextBreak();
                    $cuadroTextRun->addText($nro_radicado, array('bold' => true));
                    $cuadroTextRun->addTextBreak();
                    $cuadroTextRun->addText($tipo_doc_afiliado . ' ' . $num_identificacion_afiliado, array('bold' => true));
                    $cuadroTextRun->addTextBreak();
                    $cuadroTextRun->addText('Siniestro: ' . $request->n_siniestro_proforma_editar, array('bold' => true));

                    // Configuramos el footer
                    $info = $nombre_afiliado." - ".$tipo_doc_afiliado." ".$num_identificacion_afiliado." - Siniestro: ".$request->n_siniestro_proforma_editar;
                    $footer = $section->addFooter();
                    $footer-> addText($info, array('size' => 10, 'bold' => true), array('align' => 'center'));
                    
                    if($ruta_logo_footer != null){
                        $imagenPath_footer = public_path($ruta_logo_footer);
                        $footer->addImage($imagenPath_footer, array('width' => 450, 'height' => 70, 'alignment' => 'left'));
                    }
                    $table = $footer->addTable('myTable');
                    $table->addRow();
                    $cell1 = $table->addCell(80000, ['gridSpan' => 2]);
                    $textRun = $cell1->addTextRun(['alignment' => 'center']);
                    $textRun->addText('Página ');
                    $textRun->addField('PAGE');

                    // Generamos el documento y luego se guarda
                    $writer = new Word2007($phpWord);

                    // $nombre_docx = "JUN_DEV_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$num_identificacion_afiliado}.docx";
                    $nombre_docx = "JUN_DEV_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$num_identificacion_afiliado}_{$indicativo}.docx";

                    $writer->save(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_docx}"));

                    $actualizar_nombre_documento = [
                        'Nombre_documento' => $nombre_docx
                    ];

                    sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
                    ->update($actualizar_nombre_documento);

                    /* Inserción del registro de que fue descargado */
                    // // Extraemos el id del servicio asociado
                    // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
                    // ->select('siae.Id_servicio')
                    // ->where([
                    //     ['siae.Id_Asignacion', $Id_Asignacion],
                    //     ['siae.ID_evento', $ID_evento],
                    //     ['siae.Id_proceso', $Id_proceso],
                    // ])->get();

                    // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

                    // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
                    // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    // ->select('Nombre_documento')
                    // ->where([
                    //     ['Nombre_documento', $nombre_docx],
                    // ])->get();
                    
                    // if(count($verficar_documento) == 0){

                    //     // Se valida si antes de insertar la info del doc de Devolucion_Expediente_JRCI ya hay un documento de Oficio_Afiliado
                    //     // o Oficio_Juntas_JRCI o Remision_Expediente_JRCI o Solicitud_Dictamen_JRCI o de tipo otro
                    //     $nombre_docu_oficio_afiliado = "JUN_OFICIOA_AFILIADO_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                    //     $nombre_docu_oficio_juntas = "JUN_OFICIO_JRCI_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                    //     $nombre_docu_remision_expediente = "JUN_REM_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                    //     $nombre_docu_solicitud_dictamen = "JUN_SOL_DICTAMEN_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.docx";
                    //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado_documento}.pdf";

                    //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    //     ->select('Nombre_documento')
                    //     ->whereIN('Nombre_documento', [$nombre_docu_oficio_afiliado, $nombre_docu_oficio_juntas, $nombre_docu_remision_expediente,
                    //         $nombre_docu_solicitud_dictamen, $nombre_docu_otro
                    //     ]
                    //     )->get();

                    //     // Si no existe info del documento de Oficio_Afiliado o Oficio_Juntas_JRCI o Remision_Expediente_JRCI o Solicitud_Dictamen_JRCI o de tipo otro
                    //     // inserta la info del documento de Devolucion_Expediente_JRCI, De lo contrario hace una actualización de la info
                    //     if (count($verificar_docu_otro) == 0) {
                    //         $info_descarga_documento = [
                    //             'Id_Asignacion' => $Id_Asignacion,
                    //             'Id_proceso' => $Id_proceso,
                    //             'Id_servicio' => $Id_servicio,
                    //             'ID_evento' => $ID_evento,
                    //             'Nombre_documento' => $nombre_docx,
                    //             'N_radicado_documento' => $N_radicado_documento,
                    //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                    //             'F_descarga_documento' => $date,
                    //             'Nombre_usuario' => $nombre_usuario,
                    //         ];
                            
                    //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                    //     }else{
                    //         $info_descarga_documento = [
                    //             'Id_Asignacion' => $Id_Asignacion,
                    //             'Id_proceso' => $Id_proceso,
                    //             'Id_servicio' => $Id_servicio,
                    //             'ID_evento' => $ID_evento,
                    //             'Nombre_documento' => $nombre_docx,
                    //             'N_radicado_documento' => $N_radicado_documento,
                    //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                    //             'F_descarga_documento' => $date,
                    //             'Nombre_usuario' => $nombre_usuario,
                    //         ];
                            
                    //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    //         ->where([
                    //             ['Id_Asignacion', $Id_Asignacion],
                    //             ['N_radicado_documento', $N_radicado_documento],
                    //             ['ID_evento', $ID_evento]
                    //         ])
                    //         ->update($info_descarga_documento);
                    //     }
                    // }

                    // return response()->download(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_docx}"));

                    // Leer el contenido del archivo guardado y codificarlo en base64
                    $contenidoWord = File::get(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_docx}"));

                    $datos = [
                        'indicativo' => $indicativo,
                        'word' => base64_encode($contenidoWord)
                    ];
                    
                    return response()->json($datos);

            break;

            case($tipo_de_preforma == "Solicitud_Dictamen_JRCI"):

                $nro_radicado = $request->radicado2_act;
                $Id_junta_act = $request->Id_junta_act;
                
                $fecha_envio_jrci = sigmel_informacion_controversia_juntas_eventos::on('sigmel_gestiones')
                    ->where([['ID_evento',$ID_evento],['Id_Asignacion',$Id_Asignacion]])
                    ->value('F_envio_jrci');
                /* Extraer el id del cliente */
                $dato_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
                ->select('Cliente')
                ->where([['ID_evento', $ID_evento]])
                ->get();

                if (count($dato_id_cliente)>0) {
                    $id_cliente = $dato_id_cliente[0]->Cliente;
                }

                // Datos Junta regional
                if ($Id_junta_act != "") {
                    $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad', 'sie.Emails', 'sie.Otros_Emails')
                    ->where([['sie.Id_Entidad', $Id_junta_act]])->get();
                }else{
                    $datos_junta_regional = DB::table(getDatabaseName('sigmel_gestiones').'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad','sie.Direccion', 'sie.Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad', 'sie.Emails', 'sie.Otros_Emails')
                    ->where([['sie.Id_Entidad', $request->jrci_califi_invalidez_comunicado_editar]])->get();
                }

                $array_datos_junta_regional = json_decode(json_encode($datos_junta_regional), true);

                if(count($array_datos_junta_regional)>0){
                    if ($Id_junta_act != "") {
                        $nombre_junta = $request->Nombre_junta_act;
                    }else{
                        $nombre_junta = $array_datos_junta_regional[0]["Nombre_entidad"];
                    }
                    $direccion_junta = $array_datos_junta_regional[0]["Direccion"];
                    $telefono_junta = $array_datos_junta_regional[0]["Telefonos"];
                    $departamento_junta = $array_datos_junta_regional[0]["Nombre_departamento"];
                    $ciudad_junta = $array_datos_junta_regional[0]["Nombre_ciudad"];
                    $email_principal = $array_datos_junta_regional[0]["Emails"];
                    $otros_emails = $array_datos_junta_regional[0]["Otros_Emails"];
                    $concatenar_emails = $email_principal.", ".$otros_emails;

                }else{
                    $nombre_junta = "";
                    $direccion_junta = "";
                    $telefono_junta = "";
                    $departamento_junta = "";
                    $ciudad_junta = "";
                    $email_principal = "";
                    $otros_emails = "";
                    $concatenar_emails = "";
                }


                // Informacion del afiliado
                $informacion_afiliado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'siae.Tipo_documento', '=', 'slp.Id_Parametro')
                ->select(
                    'slp.Nombre_parametro as Tipo_documento',
                    'siae.Nro_identificacion',
                    'siae.Nombre_afiliado',
                )
                ->where([['siae.ID_evento', $ID_evento]])
                ->get();

                if (count($informacion_afiliado) > 0) {
                    $tipo_doc_afiliado = $informacion_afiliado[0]->Tipo_documento;
                    $num_identificacion_afiliado = $informacion_afiliado[0]->Nro_identificacion;
                    $nombre_afiliado = $informacion_afiliado[0]->Nombre_afiliado;
                } else {
                    $tipo_doc_afiliado = "";
                    $num_identificacion_afiliado = "";
                    $nombre_afiliado = "";
                }

                // Copias a partes interesadas
                $edit_copias_afiliado = $request->edit_copia_afi ? 'Afiliado' : '';
                $edit_copias_empleador = $request->edit_copia_empl ? 'Empleador' : '';
                $edit_copias_eps = $request->edit_copi ? 'EPS' : '';
                $edit_copias_afp = $request->edit_copi ? 'AFP' : '';
                $edit_copias_arl = $request->edit_copi ? 'ARL' : '';
                $edit_copias_jrci = $request->edit_copia ? 'JRCI': '';
                $edit_copias_jnci = $request->edit_copia ? 'JNCI': '';

                $total_copias = array_filter(array(
                    'edit_copia_afiliado' => $edit_copias_afiliado,
                    'edit_copia_empleador' => $edit_copias_empleador,
                    'edit_copia_eps' => $edit_copias_eps,
                    'edit_copia_afp' => $edit_copias_afp,
                    'edit_copia_arl' => $edit_copias_arl,
                    'edit_copia_jrci' => $edit_copias_jrci,
                    'edit_copia_jnci' => $edit_copias_jnci,
                ));   
                sleep(2);

                // Filtramos las llaves del array
                extract($total_copias);
                // Creamos array para empezar a llenarlos con las copias
                $Agregar_copias = [];
                if (isset($copia_afiliado)) {
                    $AfiliadoData = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'siae.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'siae.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('siae.Nombre_afiliado', 'siae.Direccion', 'siae.Telefono_contacto', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'siae.Email')
                    ->where([['siae.Nro_identificacion', $num_identificacion],['siae.ID_evento', $nro_siniestro]])
                    ->get();
                    $nombreAfiliado = $AfiliadoData[0]->Nombre_afiliado;
                    $direccionAfiliado = $AfiliadoData[0]->Direccion;
                    $telefonoAfiliado = $AfiliadoData[0]->Telefono_contacto;
                    $ciudadAfiliado = $AfiliadoData[0]->Nombre_ciudad;
                    $municipioAfiliado = $AfiliadoData[0]->Nombre_municipio;
                    $emailAfiliado = $AfiliadoData[0]->Email;            
                    $Agregar_copias['Afiliado'] = $nombreAfiliado."; ".$direccionAfiliado."; ".$emailAfiliado."; ".$telefonoAfiliado."; ".$ciudadAfiliado."; ".$municipioAfiliado.".";          
                }

                if(isset($edit_copia_empleador)) {            
                    $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                    ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sile.Email', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
                    ->get();

                    if (preg_match("/&/", $datos_empleador[0]->Empresa)) {
                        $nombre_empleador = htmlspecialchars(preg_replace('/&/', '&amp;', $datos_empleador[0]->Empresa));
                    } else {
                        $nombre_empleador = $datos_empleador[0]->Empresa;
                    }

                    $direccion_empleador = $datos_empleador[0]->Direccion;
                    $email_empleador = $datos_empleador[0]->Email;
                    $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
                    $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
                    $departamento_empleador = $datos_empleador[0]->Nombre_departamento;

                    $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$email_empleador."; ".$telefono_empleador."; ".$ciudad_empleador." - ".$departamento_empleador.".";
                }

                if (isset($edit_copia_eps)) {
                    $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email_eps',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_eps = $datos_eps[0]->Nombre_eps;
                    $direccion_eps = $datos_eps[0]->Direccion;
                    $email_eps = $datos_eps[0]->Email_eps;
                    if ($datos_eps[0]->Otros_Telefonos != "") {
                        $telefonos_eps = $datos_eps[0]->Telefonos.",".$datos_eps[0]->Otros_Telefonos;
                    } else {
                        $telefonos_eps = $datos_eps[0]->Telefonos;
                    }
                    $ciudad_eps = $datos_eps[0]->Nombre_ciudad;
                    $departamento_eps = $datos_eps[0]->Nombre_departamento;

                    $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps."; ".$telefonos_eps."; ".$ciudad_eps." - ".$departamento_eps;
                }

                if (isset($edit_copia_afp)) {
                    $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email_afp',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_afp = $datos_afp[0]->Nombre_afp;
                    $direccion_afp = $datos_afp[0]->Direccion;
                    $email_afp = $datos_afp[0]->Email_afp;
                    if ($datos_afp[0]->Otros_Telefonos != "") {
                        $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
                    } else {
                        $telefonos_afp = $datos_afp[0]->Telefonos;
                    }
                    $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
                    $departamento_afp = $datos_afp[0]->Nombre_departamento;

                    $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$email_afp."; ".$telefonos_afp."; ".$ciudad_afp." - ".$departamento_afp;
                }

                if (isset($edit_copia_arl)) {
                    $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                    ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                    ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos','sie.Emails as Email_arl',
                    'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
                    ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                    ->get();

                    $nombre_arl = $datos_arl[0]->Nombre_arl;
                    $direccion_arl = $datos_arl[0]->Direccion;
                    $email_arl = $datos_arl[0]->Email_arl;
                    if ($datos_arl[0]->Otros_Telefonos != "") {
                        $telefonos_arl = $datos_arl[0]->Telefonos.",".$datos_arl[0]->Otros_Telefonos;
                    } else {
                        $telefonos_arl = $datos_arl[0]->Telefonos;
                    }
                    
                    $ciudad_arl = $datos_arl[0]->Nombre_ciudad;
                    $departamento_arl = $datos_arl[0]->Nombre_departamento;

                    $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$email_arl."; ".$telefonos_arl."; ".$ciudad_arl." - ".$departamento_arl;
                };

                if(isset($edit_copia_jrci)){
                    if(!empty($request->input_jrci_seleccionado_copia_editar)){
                        $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                        ->select('sie.Nombre_entidad', 
                            'sie.Nit_entidad', 
                            'sie.Direccion', 
                            'sie.Telefonos',
                            'sie.Otros_Telefonos',
                            'sie.Emails',
                            'sldm.Id_departamento',
                            'sldm.Nombre_departamento',
                            'sldm1.Id_municipios',
                            'sldm1.Nombre_municipio as Nombre_ciudad'
                        )->where([
                            ['sie.Id_Entidad', $request->id_jrci_del_input]
                        ])->get();
    
                        $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
                        $direccion_jrci = $datos_jrci[0]->Direccion;

                        if ($datos_jrci[0]->Otros_Telefonos != "") {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
                        } else {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos;
                        }
                        $email_jrci = $datos_jrci[0]->Emails;
                        $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
                        $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
    
                        $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$email_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
    
                    }else{
    
                        $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                        ->select('sie.Nombre_entidad', 
                            'sie.Nit_entidad', 
                            'sie.Direccion', 
                            'sie.Telefonos',
                            'sie.Otros_Telefonos',
                            'sie.Emails',
                            'sldm.Id_departamento',
                            'sldm.Nombre_departamento',
                            'sldm1.Id_municipios',
                            'sldm1.Nombre_municipio as Nombre_ciudad'
                        )->where([
                            ['sie.Id_Entidad', $request->jrci_califi_invalidez_copia_editar]
                        ])->get();
    
                        $nombre_jrci = $datos_jrci[0]->Nombre_entidad;
                        $email_jrci = $datos_jrci[0]->Emails;
                        $direccion_jrci = $datos_jrci[0]->Direccion;

                        if ($datos_jrci[0]->Otros_Telefonos != "") {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos.",".$datos_jrci[0]->Otros_Telefonos;
                        } else {
                            $telefonos_jrci = $datos_jrci[0]->Telefonos;
                        }

                        $ciudad_jrci = $datos_jrci[0]->Nombre_ciudad;
                        $departamento_jrci = $datos_jrci[0]->Nombre_departamento;
    
                        $Agregar_copias['JRCI'] = $nombre_jrci."; ".$direccion_jrci."; ".$email_jrci."; ".$telefonos_jrci."; ".$ciudad_jrci." - ".$departamento_jrci;
                    }
                }
                
                if(isset($edit_copia_jnci)){
                    $datos_jnci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sie.Id_Ciudad', '=', 'sldm1.Id_municipios')
                    ->select('sie.Nombre_entidad', 
                        'sie.Nit_entidad', 
                        'sie.Direccion', 
                        'sie.Telefonos',
                        'sie.Otros_Telefonos',
                        'sie.Emails',
                        'sldm.Id_departamento',
                        'sldm.Nombre_departamento',
                        'sldm1.Id_municipios',
                        'sldm1.Nombre_municipio as Nombre_ciudad'
                    )->where([
                        ['sie.IdTipo_entidad', 5]
                    ])->limit(1)->get();
    
                    $nombre_jnci = $datos_jnci[0]->Nombre_entidad;
                    $direccion_jnci = $datos_jnci[0]->Direccion;
                    $email_jnci = $datos_jnci[0]->Emails;
                    if ($datos_jnci[0]->Otros_Telefonos != "") {
                        $telefonos_jnci = $datos_jnci[0]->Telefonos.",".$datos_jnci[0]->Otros_Telefonos;
                    } else {
                        $telefonos_jnci = $datos_jnci[0]->Telefonos;
                    }

                    $ciudad_jnci = $datos_jnci[0]->Nombre_ciudad;
                    $departamento_jnci = $datos_jnci[0]->Nombre_departamento;

                    $Agregar_copias['JNCI'] = $nombre_jnci."; ".$direccion_jnci."; ".$email_jnci."; ".$telefonos_jnci."; ".$ciudad_jnci." - ".$departamento_jnci;
    
                }

                // validamos si el checkbox de la firma esta marcado
                $validarFirma = isset($request->firmarcomunicado_editar) ? 'firmar comunicado' : 'No lleva firma';
                                        
                if ($validarFirma == 'firmar comunicado') {            
                    $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente', 'Nombre_cliente')
                    ->where('Nombre_cliente', $Cliente)->get();

                    $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
                    ->where('Id_cliente', $idcliente[0]->Id_cliente)->limit(1)->get();

                    if(count($firmaclientecompleta) > 0){
                        $Firma_cliente = $firmaclientecompleta[0]->Firma;
                    }else{
                        $Firma_cliente = 'No firma';
                    }
                    
                }else{
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

                //Footer
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

                /* datos del footer */
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
                $phpWord->setDefaultFontName('Arial');
                $phpWord->setDefaultFontSize(11);
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
                $header->addWatermark($pathVigilado, $styleVigilado);
                $imagenPath_header = public_path($ruta_logo);
                $header->addImage($imagenPath_header, array('width' => 150, 'align' => 'right'));
                $test = $header->addTextRun(['alignment' => 'right']);
                $test->addText('Página ');
                $test->addField('PAGE');
                $test->addText(' de ');
                $test->addField('NUMPAGES');
                $header->addTextBreak();

                // Creación de Contenido
                $section->addText('Bogotá D.C. '.fechaFormateada($fecha_comunicado), array('bold' => true), array('align' => 'right'));
                $section->addTextBreak();

                $table = $section->addTable();                    
                $table->addRow();
                $cell1 = $table->addCell(10000);

                $textRun1 = $cell1->addTextRun(array('alignment'=>'left'));
                $textRun1->addText('Señores: ',array('bold' => true));
                $textRun1->addTextBreak();
                $textRun1->addText($nombre_junta, array('bold' => true));
                $textRun1->addTextBreak();
                $textRun1->addText('Dirección: '.$direccion_junta);
                $textRun1->addTextBreak();
                $textRun1->addText('Teléfono: '.$telefono_junta);
                $textRun1->addTextBreak();
                $textRun1->addText($ciudad_junta.' - '.$departamento_junta);
    
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
                    //                         <p><b>'.$tipo_doc_afiliado." ".$num_identificacion_afiliado.'</b></p>
                    //                         <p><b>Siniestro: '.$request->n_siniestro_proforma_editar.'</b></p>
                    //                     </td>
                    //                 </tr>
                    //             </table>
                    //         </td>
                    //     </tr>
                    // </table>';

                    // Html::addHtml($section, $htmltabla1, false, true);

                    $table = $section->addTable(array('alignment'=>'center'));

    
                    $table->addRow();
        
        
                    $cell1 = $table->addCell(2000);
                    $cell2 = $table->addCell(8000);
        
                    $asunto = $cell1->addTextRun(array('alignment'=>'left'));
                    $asunto->addText('Asunto: ', array('bold' => true));
                    $asuntoyafiliado = $cell2->addTextRun(array('alignment'=>'left'));
                    $asuntoyafiliado->addText($request->asunto_act, array('bold' => true));
                    $asuntoyafiliado->addTextBreak();
                    $asuntoyafiliado->addText('PACIENTE: ', array('bold' => true));
                    $asuntoyafiliado->addText($nombre_afiliado." ".$tipo_doc_afiliado." ".$num_identificacion_afiliado, array('bold' => true));
                    $section->addTextBreak();
                    // Configuramos el reemplazo de las etiquetas del cuerpo del comunicado
                    $patron1 = '/\{\{\$nombre_afiliado\}\}/';
                    $patron2 = '/\{\{\$correo_solicitud_informacion\}\}/';
                    $patron3 = '(Anote Fecha de notificación del Dictamen de JRCI)';
                    $patron4 = '(Anote Fecha de solicitud de Dictamen)';
                    
                    $cuerpo = str_replace(['<br>', '<br/>', '<br />', '</br>'], '', $cuerpo);
                    //Fecha de envio a JRCI
                    $fecha_envio_jrci = $fecha_envio_jrci ? date('d/m/Y',strtotime($fecha_envio_jrci)) : 'SIN FECHA';

                    $cuerpo_modificado = str_replace('</p>', '</p><br></br>', $cuerpo);
                    $cuerpo_modificado = str_replace('<p><br>', ' ', $cuerpo_modificado);
                    
                    if(preg_match($patron3,$cuerpo_modificado) && preg_match($patron4, $cuerpo_modificado)){
                        $cuerpo_modificado = str_replace('(Anote Fecha de notificación del Dictamen de JRCI)', '<b>'.$fecha_envio_jrci.'</b>', $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('(Anote Fecha de solicitud de Dictamen)', '<b>'.$fecha_envio_jrci.'</b>', $cuerpo_modificado);
                    }

                    if (preg_match($patron1, $cuerpo_modificado) && preg_match($patron2, $cuerpo_modificado)) {

                        $cuerpo_modificado = str_replace('{{$nombre_afiliado}}', $nombre_afiliado, $cuerpo_modificado);
                        $cuerpo_modificado = str_replace('{{$correo_solicitud_informacion}}', $concatenar_emails, $cuerpo_modificado);
                        $cuerpo = $cuerpo_modificado;

                    } else {
                        $cuerpo = "";
                    }

                    $section->addTextBreak();
                    Html::addHtml($section, $cuerpo, false, false);
                    $section->addTextBreak();
                    $section->addText('Atentamente,');
                    $section->addTextBreak();

                    if($Firma_cliente != "No firma"){
                        // Agregar </img> en la imagen de la firma
                        $patronetiqueta = '/<img(.*?)>/';
                        $Firma_cliente = preg_replace($patronetiqueta, '<img$1></img>', $Firma_cliente);
                        $Firma_cliente = str_replace(['<br>', '<br/>', '<br />', '</br>'], '', $Firma_cliente);
                        
                        // Quitamos el style y agregamos los atributos width y height
                        $patronstyle = '/<img[^>]+style="width:\s*([\d.]+)px;\s*height:\s*([\d.]+)px[^"]*"[^>]*>/';
                        preg_match($patronstyle, $Firma_cliente, $coincidencias);
                        $width = count($coincidencias)>0 ? $coincidencias[1] : '100px'; // Valor de width
                        $height = count($coincidencias)>0 ? $coincidencias[2] : '70px'; // Valor de height
                    
                        $nuevoStyle = 'width="'.$width.'" height="'.$height.'"';
                        $htmlModificado = reemplazarStyleImg($Firma_cliente, $nuevoStyle);
                        Html::addHtml($section, $htmlModificado, false, true);
                    }else{
                        $section->addText($Firma_cliente);
                    }
                    
                    // $section->addTextBreak();
                    $section->addText('Departamento de medicina laboral', array('bold' => true));
                    // $section->addTextBreak();
                    $section->addText('Convenio CODESS -  Seguros de Vida Alfa S.A.');
                    $section->addText('Seguros Alfa S.A. y Seguros de Vida Alfa S.A.');
                    $section->addTextBreak();

                    $table2 = $section->addTable();
                    $table2 = $section->addTable(array('alignment'=>'center'));

                    $table2->addRow();
                    $cell2 = $table2->addCell(5000);
    
                    $nestedTable = $cell2->addTable(array('borderSize' => 12, 'borderColor' => '000000', 'width' => 80 * 60, 'alignment'=>'center'));
                    $nestedTable->addRow();
                    $nestedCell = $nestedTable->addCell();
                    $nestedTextRun = $nestedCell->addTextRun(array('alignment'=>'left'));
                    $nestedTextRun->addText('Nro. Radicado: ', array('bold' => true));
                    $nestedTextRun->addTextBreak();
                    $nestedTextRun->addText($nro_radicado, array('bold' => true));
                    $nestedTextRun->addTextBreak();
                    $nestedTextRun->addText($tipo_doc_afiliado . ' ' . $num_identificacion_afiliado, array('bold' => true));
                    $nestedTextRun->addTextBreak();
                    $nestedTextRun->addText('Siniestro: ' . $request->n_siniestro_proforma_editar, array('bold' => true));
                    
                    // $section->addText('Elaboró: '.$nombre_usuario, array('bold' => true));
                    // $section->addTextBreak();

                    // // Configuramos la tabla de copias a partes interesadas
                    // $htmltabla2 = '<table style="text-align: justify; width:100%; border-collapse: collapse; margin-left: auto; margin-right: auto;">';
                    // if (count($Agregar_copias) == 0) {
                    //     $htmltabla2 .= '
                    //         <tr>
                    //             <td style="border: 1px solid #000; padding: 5px;"><span style="font-weight:bold;">Copia: </span>No se registran copias</td>                                                                                
                    //         </tr>';
                    // } else {
                    //     $htmltabla2 .= '
                    //         <tr>
                    //             <td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Copia:</span></td>                            
                    //         </tr>';

                    //     $Afiliado = 'Afiliado';
                    //     $Empleador = 'Empleador';
                    //     $EPS = 'EPS';
                    //     $AFP = 'AFP';
                    //     $ARL = 'ARL';
                    //     $JRCI = 'JRCI';
                    //     $JNCI = 'JNCI';

                    //     if (isset($Agregar_copias[$Afiliado])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Afiliado: </span>' . $Agregar_copias['Afiliado'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$Empleador])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">Empleador: </span>' . $Agregar_copias['Empleador'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$EPS])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">EPS: </span>' . $Agregar_copias['EPS'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$AFP])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">AFP: </span>' . $Agregar_copias['AFP'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$ARL])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">ARL: </span>' . $Agregar_copias['ARL'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$JRCI])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">JRCI: </span>' . $Agregar_copias['JRCI'] . '</td></tr>';
                    //     }

                    //     if (isset($Agregar_copias[$JNCI])) {
                    //         $htmltabla2 .= '<tr><td style="border: 1px solid #000; padding: 5px; text-align: justify;"><span style="font-weight:bold;">JNCI: </span>' . $Agregar_copias['JNCI'] . '</td></tr>';
                    //     }
                    // }

                    // $htmltabla2 .= '</table>';
                    // Html::addHtml($section, $htmltabla2, false, true);
                    // $section->addTextBreak();

                    // Configuramos el footer
                    $info = $nombre_afiliado." - ".$tipo_doc_afiliado." ".$num_identificacion_afiliado." - Siniestro: ".$request->n_siniestro_proforma_editar;
                    $footer = $section->addFooter();
                    $footer-> addText($info, array('size' => 10, 'bold' => true), array('align' => 'center'));
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

                    $nombre_docx = "JUN_SOL_DICTAMEN_{$Id_comunicado}_{$Id_Asignacion}_{$num_identificacion_afiliado}.docx";
                    $nombre_docx = "JUN_SOL_DICTAMEN_{$Id_comunicado}_{$Id_Asignacion}_{$num_identificacion_afiliado}_{$indicativo}.docx";

                    $writer->save(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_docx}"));
                    
                    $actualizar_nombre_documento = [
                        'Nombre_documento' => $nombre_docx
                    ];

                    sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
                    ->update($actualizar_nombre_documento);

                    /* Inserción del registro de que fue descargado */
                    // // Extraemos el id del servicio asociado
                    // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
                    // ->select('siae.Id_servicio')
                    // ->where([
                    //     ['siae.Id_Asignacion', $Id_Asignacion],
                    //     ['siae.ID_evento', $ID_evento],
                    //     ['siae.Id_proceso', $Id_proceso],
                    // ])->get();

                    // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

                    // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
                    // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    // ->select('Nombre_documento')
                    // ->where([
                    //     ['Nombre_documento', $nombre_docx],
                    // ])->get();
                    
                    // if(count($verficar_documento) == 0){

                    //     // Se valida si antes de insertar la info del doc de Solicitud_Dictamen_JRCI ya hay un documento de Oficio_Afiliado
                    //     // o Oficio_Juntas_JRCI o Remision_Expediente_JRCI o Devolucion_Expediente_JRCI o de tipo otro
                    //     $nombre_docu_oficio_afiliado = "JUN_OFICIOA_AFILIADO_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                    //     $nombre_docu_oficio_juntas = "JUN_OFICIO_JRCI_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                    //     $nombre_docu_remision_expediente = "JUN_REM_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
                    //     $nombre_docu_devolucion_expediente = "JUN_DEV_EXPEDIENTE_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.docx";
                    //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado_documento}.pdf";

                    //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    //     ->select('Nombre_documento')
                    //     ->whereIN('Nombre_documento', [$nombre_docu_oficio_afiliado, $nombre_docu_oficio_juntas, $nombre_docu_remision_expediente,
                    //         $nombre_docu_devolucion_expediente, $nombre_docu_otro
                    //     ]
                    //     )->get();
                    
                    //     // Si no existe info del documento de Oficio_Afiliado o Oficio_Juntas_JRCI o Remision_Expediente_JRCI o Devolucion_Expediente_JRCI o  de tipo otro
                    //     // inserta la info del documento de Solicitud_Dictamen_JRCI, De lo contrario hace una actualización de la info
                    //     if (count($verificar_docu_otro) == 0) {
                    //         $info_descarga_documento = [
                    //             'Id_Asignacion' => $Id_Asignacion,
                    //             'Id_proceso' => $Id_proceso,
                    //             'Id_servicio' => $Id_servicio,
                    //             'ID_evento' => $ID_evento,
                    //             'Nombre_documento' => $nombre_docx,
                    //             'N_radicado_documento' => $N_radicado_documento,
                    //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                    //             'F_descarga_documento' => $date,
                    //             'Nombre_usuario' => $nombre_usuario,
                    //         ];
                            
                    //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                    //     }else{
                    //         $info_descarga_documento = [
                    //             'Id_Asignacion' => $Id_Asignacion,
                    //             'Id_proceso' => $Id_proceso,
                    //             'Id_servicio' => $Id_servicio,
                    //             'ID_evento' => $ID_evento,
                    //             'Nombre_documento' => $nombre_docx,
                    //             'N_radicado_documento' => $N_radicado_documento,
                    //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
                    //             'F_descarga_documento' => $date,
                    //             'Nombre_usuario' => $nombre_usuario,
                    //         ];
                            
                    //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                    //         ->where([
                    //             ['Id_Asignacion', $Id_Asignacion],
                    //             ['N_radicado_documento', $N_radicado_documento],
                    //             ['ID_evento', $ID_evento]
                    //         ])
                    //         ->update($info_descarga_documento);
                    //     }
                    // }

                    // return response()->download(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_docx}"));

                    // Leer el contenido del archivo guardado y codificarlo en base64
                    $contenidoWord = File::get(public_path("Documentos_Eventos/{$ID_evento}/{$nombre_docx}"));

                    $datos = [
                        'indicativo' => $indicativo,
                        'word' => base64_encode($contenidoWord)
                    ];
                    
                    return response()->json($datos);
            break;
            
            default:
                # code...
            break;
        }
        
    }

    // Historial de acciones de la parametrica de la tabla sigmel_informacion_historial_accion_eventos

    public function historialAccionesEventoJun (Request $request){

        $array_datos_historial_accion_eventos = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_historial_accion_eventos as sihae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia', 'sia.Id_Accion', '=', 'sihae.Id_accion')
        ->select('sihae.Id_historial_accion', 'sihae.ID_evento', 'sihae.Id_proceso', 'sihae.Id_servicio', 'sihae.Id_accion', 
        'sia.Accion', 'sihae.Documento', 'sihae.Descripcion', 'sihae.F_accion', 'sihae.Nombre_usuario')
        ->where([['sihae.ID_evento', $request->ID_evento],['sihae.Id_proceso', $request->Id_proceso]])
        ->orderBy('sihae.F_accion', 'asc')->get();
       
        return response()->json($array_datos_historial_accion_eventos);
    }

    // Funcion para procesar y generar la lista de chequeo requeridos para este de avuerdo a los documentos generales - pbs 036
    public  function generarListaChequeo(Request $request)
    {
        $request->validate([
            'Id_evento' => 'required',
            'Id_servicio' => 'required',
            'afiliado' => 'required',
            'identificacion' => 'required',
            'lista_chequeo' => 'required|array',
            'lista_chequeo.*.id_doc' => 'required',
            'lista_chequeo.*.statusDoc' => 'required',
            'lista_chequeo.*.nombreDoc' => 'required',
            // 'lista_chequeo.*.posicion' => 'required'
        ], [
            '*.required' => 'hacen falta datos para poder procesar la solicitud.'
        ]);

        //Documentos necesarios para lista de de chequeo, (Homologación de documentos - PBS 036) 
        $lista_documentos = ['Registro civil de defunción' ,'Protocolo de necropsia' ,'Lista de chequeo' ,'Guía EPS' ,'Guía Empleador' ,'Guía ARL' ,'Guía AFP' ,'Guía Afiliado' ,'Fotocopia Documento Identidad' ,'Anexo G (Datos Generales)' ,'Exámenes complementarios' ,'Dictamen Junta Regional' ,'Conceptos o recomendaciones y/o restricciones ocupacionales' ,'Relación de incapacidades' ,'Concepto de rehabilitación' ,'Origen de la patología' ,'Dictamen de Calificación' ,'Autorización historia clínica' ,'Apelación al dictamen' ,'Acta de levantamiento del cadáver' ,'Historia clínica completa' ,'Orden de pago honorarios' ,'Notificación al usuario'];

        $documentos = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_lista_documentos')
        ->select('Id_Documento', 'Nombre_documento', 'Descripcion_documento')
        ->whereIn('Nombre_documento', $lista_documentos)->get();

        /** @var $lista_chequeo documentos generales, los cuales fueron o no chequeados por el usuario */
        $lista_chequeo = [];

        /** @var $comunicados Comunicados chequeados por el usuario para ser incluido en la lista de chequeo */
        // $comunicados = [];

        //Validamos si de los documentos requeridos, alguno fue checkeado para marcarlo como incluido en la lista de chequeo
        foreach ($documentos as $documento) {
            $documento_incluido = false;

            foreach ($request->lista_chequeo as $lista) {
                if ($lista['id_doc'] == $documento->Id_Documento) {
                    $documento_incluido = true;

                    //Actualizamos la tabla sigmel_registro_documentos_eventos para chequearlo
                    $this->actualizarListaChequeo($request->Id_evento, $documento->Id_Documento, 'Si','registro_documentos');
                    break;
                } else {
                    $this->actualizarListaChequeo($request->Id_evento, $documento->Id_Documento, 'No','registro_documentos');
                }
            }

            $lista_chequeo[$documento->Nombre_documento] = [
                'Descripcion_documento' => $documento->Descripcion_documento,
                'Incluido' => $documento_incluido
            ];
        }

        //Caso comunicados: Cuando el usuario chequea alguno de los comunicados en Historial de comunicados y expedientes para incluirlo en la lista de chequeo
        /*foreach ($request->lista_chequeo as $lista) {
            if (isset($lista['idComunicado'])) {
                if($lista['statusDoc'] == 'Comunicado'){
                    $comunicados[] = ['Descripcion_documento' => $lista['nombreDoc']];

                    $this->actualizarListaChequeo($request->Id_evento, $lista['idComunicado'], 'Si','info_comunicados');
                }
            }else{
                $this->actualizarListaChequeo($request->Id_evento, null, 'No','info_comunicados');
            }
        }*/
       // $lista_chequeo['comunicados'] = $comunicados;

        //Obtenemos el id del documento general para ' lista de chequeo'
        $indice_lista_chequeo = $documentos->search(function ($documento) {
            return $documento->Nombre_documento === 'Lista de chequeo';
        });

        $indice_lista_chequeo = $documentos[$indice_lista_chequeo]->Id_Documento;


        /* datos del logo que va en el header */
        $dato_logo_header = sigmel_clientes::on('sigmel_gestiones')
        ->select('Logo_cliente', 'Footer_cliente', 'Id_cliente')
        ->where([['Nombre_cliente', $request->cliente]])
            ->limit(1)->get();

        // Datos de la proforma
        $data = [
            'afiliado' => $request->afiliado,
            'identificacion' => $request->identificacion,
            'logo_header' => $dato_logo_header[0]->Logo_cliente,
            'footer' => $dato_logo_header[0]->Footer_cliente,
            'id_cliente' => $dato_logo_header[0]->Id_cliente,
            'lista_chequeo' => $lista_chequeo
        ];
        //Verificamos si existe algun radicado asociado a la lista
        $radicado_comunicado = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos')
        ->select('N_radicado')->where([
            ['ID_evento',$request->Id_evento],
            ['Asunto','LIKE','Lista_chequeo%']
        ])->first();
        
        $n_radicado = !empty($radicado_comunicado) ? $radicado_comunicado->N_radicado  :$this->getRadicado('juntas',$request->Id_evento); //Para cada lista de chequeo se le debe generar un radicado


        $extension_proforma = "pdf";
        $ruta_proforma = '/Proformas/Proformas_Prev/Juntas/lista_chequeo';
        $nombre_documento = "Lista_chequeo";

        /* Creación del pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadView($ruta_proforma, $data);

        $nombre_pdf = "{$nombre_documento}.{$extension_proforma}";

        //Obtener el contenido del PDF
        $output = $pdf->output();
        //Guardar el PDF en un archivo
        file_put_contents(public_path("Documentos_Eventos/{$request->Id_evento}/{$nombre_pdf}"), $output);

        //Registramos una unica vez la lista de chequeo
        if ($request->bandera == 'Guardar' && !empty($indice_lista_chequeo) && empty($radicado_comunicado)) {

            $fecha_actual = date("Y-m-d", time());
            
            // datps sigmel_registro_documentos_eventos
            $registro_documento = [
                'Id_Documento' => $indice_lista_chequeo , //id Lista de chequeo
                'ID_evento' => $request->Id_evento,
                'Nombre_documento' => $nombre_documento,
                'Formato_documento' => 'pdf',
                'Id_servicio' => $request->Id_servicio,
                'Lista_chequeo' => 'Si',                
                'Estado' => 'activo',
                'F_cargue_documento' => $fecha_actual,
                'Nombre_usuario' => Auth::user()->name,
                'F_registro'  => $fecha_actual
            ];

            // datos sigmel_informacion_comunicado_eventos
            $data_comunicado_eventos = [
                'ID_evento' => $request->Id_evento,
                'Id_Asignacion' => $request->Id_asignacion,
                'Id_proceso' => $request->Id_proceso,
                'F_comunicado' => $fecha_actual,
                // 'N_radicado' => $n_radicado,
                'Cliente' => $request->cliente,
                'Nombre_afiliado' => $request->afiliado,
                'T_documento'  => $request->t_documento,
                'N_identificacion' => $request->identificacion,
                'Tipo_descarga' => 'Manual',
                'Modulo_creacion' => 'calificacionJuntas',
                'Asunto' => $nombre_pdf,
                'Nombre_documento' => $nombre_pdf,
                'Elaboro' => Auth::user()->name,
                'Nombre_usuario' => Auth::user()->name,
                'F_registro'  => $fecha_actual
            ];

            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($data_comunicado_eventos);

            sigmel_registro_documentos_eventos::on('sigmel_gestiones')->insert($registro_documento);

            //Consultar los documentos de la lista de chequeo del evento y servicio

            $info_registros_documentos = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $request->Id_evento],
                ['Id_servicio', $request->Id_servicio],
                ['Lista_chequeo', 'Si']
            ])->get();

            $info_array_registros_documentos = $info_registros_documentos->toArray();          

            // Recorremos el array $info_array_registros_documentos para capturar la info necesaria para insertarlos 
            // en la tabla sigmel_informacion_expedientes_eventos
            foreach ($info_array_registros_documentos as $registro_documentos) {

                // Organizamos el array para la insercion de los datos encontrados en el array
                $info_datos_expedientes = [
                    'Id_Documento' => $registro_documentos['Id_Documento'],
                    'ID_evento' => $registro_documentos['ID_evento'],
                    'Nombre_documento' => $registro_documentos['Nombre_documento'],
                    'Formato_documento' => $registro_documentos['Formato_documento'],
                    'Id_servicio' => $registro_documentos['Id_servicio'],
                    'Estado' => 'activo',
                    'Nombre_usuario' => Auth::user()->name,
                    'F_registro' => $fecha_actual
                ];

                // Condicionalmente agregamos 'Posicion' y 'Folear' si cumple la condición
                if ($registro_documentos['Nombre_documento'] == 'Lista_chequeo') {
                    $info_datos_expedientes['Posicion'] = 2;
                    $info_datos_expedientes['Folear'] = 'No';
                }

                // Insertamos los registros en la tabla  sigmel_informacion_expedientes_eventos

                sigmel_informacion_expedientes_eventos::on('sigmel_gestiones')->insert($info_datos_expedientes);
            }

            $mensaje = 'Registro agregado satisfactoriamente.';
        }else{

            $array_lista_chequeo = $request->lista_chequeo;

            dd($array_lista_chequeo);

            $fecha_actual = date("Y-m-d", time());
            //Consultar los documentos de la lista de chequeo del evento y servicio por si hubo algún cambio
            // Validar cuales siguen dentro de la lista de chequeo (Si)
            $info_registros_documentos_Si = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $request->Id_evento],
                ['Id_servicio', $request->Id_servicio],
                ['Lista_chequeo', 'Si']
            ])->get();

            $info_array_registros_documentosSi = $info_registros_documentos_Si->toArray();

            // Validar cuales siguen dentro de la lista de chequeo (No) y distinto al documento Lista_chequeo
            $info_registros_documentos_No = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $request->Id_evento],
                ['Id_servicio', $request->Id_servicio],
                ['Lista_chequeo', 'No'],
                ['Nombre_documento', '<>', 'Lista_chequeo'],
            ])->get();

            $info_array_registros_documentosNo = $info_registros_documentos_No->toArray();

            // Recorremos el array de los documentos que continuan en la lista de chequeo y actualizamos su posicion

            foreach ($info_array_registros_documentosSi as $chequeo_Si) {
                
                // Se organiza el array para la actualizacion, se activa el estado para tenerlo encuenta en la unificación del expediente

                $info_datos_expedientes = [                    
                    'Estado' => 'activo',
                    'Nombre_usuario' => Auth::user()->name,
                    'F_registro' => $fecha_actual
                ];

                // Recorre el array de la lista de chequeo  array_lista_chequeo para actulizar la posicion de los inputs

                foreach ($array_lista_chequeo as $id_doc_posicion) {
                    // validamo si los id de documentos son iguales para captura su poscion
                    if ($chequeo_Si['Id_Documento'] == $id_doc_posicion['id_doc']) {
                        $info_datos_expedientes['Posicion'] = $id_doc_posicion['posicion'];                        
                    }
                }

                // dd($info_datos_expedientes);

                // Actualizamos los registros en la tabla  sigmel_informacion_expedientes_eventos

                sigmel_informacion_expedientes_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $request->Id_evento],
                    ['Id_servicio', $request->Id_servicio],
                    ['Id_Documento', $chequeo_Si['Id_Documento']],
                ])
                ->update($info_datos_expedientes);


            }

            // Recorremos el array de los documentos que NO continuan en la lista de chequeo y actualizamos su posicion

            foreach ($info_array_registros_documentosNo as $chequeo_No) {

                 // Se organiza el array para la actualizacion, se inactiva el estado para no tenerlo encuenta en la unificación del expediente

                 $info_datos_expedientes = [                    
                    'Estado' => 'Inactiva',
                    'Nombre_usuario' => Auth::user()->name,
                    'F_registro' => $fecha_actual
                ];

                // Recorre el array de la lista de chequeo  array_lista_chequeo para actulizar la posicion de los inputs

                foreach ($array_lista_chequeo as $id_doc_posicion) {
                    // validamo si los id de documentos son iguales para captura su poscion
                    if ($chequeo_No['Id_Documento'] == $id_doc_posicion['id_doc']) {
                        $info_datos_expedientes['Posicion'] = null;                        
                    }
                }
            
            }

            // $mensaje = 'Registro actualizado satisfactoriamente. Para poder visualizar la lista de chequeo en el historial de comunicaciones debe recargar la pagina.';
            $mensaje = 'Registro actualizado satisfactoriamente.';

        }

        $mensajes = array(
            "parametro" => 'agregar_lista_chequeo',
            'nombre_proforma' => $nombre_pdf,
            "message" => $mensaje,
        );

        return json_decode(json_encode($mensajes, true));
    }

    /**
    * Actualiza el estado de Lista_chequeo
    *
    * @param int $id_evento
    * @param int $id_documento
    * @param string $estado
    * @return void
    */
    function actualizarListaChequeo($id_evento, $id_documento, $estado, $tabla) {
        switch($tabla){
            case 'registro_documentos' :
                    sigmel_registro_documentos_eventos::on('sigmel_gestiones')
                    ->where([
                        ['ID_evento', $id_evento],
                        ['Id_Documento', $id_documento]
                    ])->update(['Lista_chequeo' => $estado]);
                break;
            case 'info_comunicados' :

                $condicion = [['ID_evento', $id_evento]];

                if ($id_documento != null) {
                    $condicion[] = ['Id_Comunicado', $id_documento];
                }

                sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                    ->where($condicion)->update(['Lista_chequeo' => $estado]);

                break;
        }
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
