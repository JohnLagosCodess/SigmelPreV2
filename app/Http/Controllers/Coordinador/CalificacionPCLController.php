<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
/* use Dompdf\Dompdf;
use Dompdf\Options; */
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use App\Models\cndatos_bandeja_eventos;
use App\Models\cndatos_comunicado_eventos;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_lista_causal_seguimiento;
use App\Models\sigmel_informacion_seguimientos_eventos;
use App\Models\sigmel_registro_documentos_eventos;
use App\Models\sigmel_lista_documentos;
use App\Models\sigmel_lista_solicitantes;
use App\Models\sigmel_lista_departamentos_municipios;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_documentos_solicitados_eventos;
use App\Models\sigmel_campimetria_visuales;
use App\Models\sigmel_lista_califi_decretos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\cndatos_eventos;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_info_campimetria_ojo_izq_eventos;
use App\Models\sigmel_info_campimetria_ojo_der_eventos;
use App\Models\sigmel_informacion_agudeza_visual_eventos;
use App\Models\sigmel_lista_tablas_1507_decretos;
use App\Models\cndatos_info_comunicado_eventos;
use App\Models\sigmel_clientes;
use App\Models\sigmel_historial_acciones_eventos;
use App\Models\sigmel_informacion_agudeza_auditiva_eventos;
use App\Models\sigmel_informacion_decreto_eventos;
use App\Models\sigmel_informacion_diagnosticos_eventos;
use App\Models\sigmel_informacion_examenes_interconsultas_eventos;
use App\Models\sigmel_informacion_pericial_eventos;
use App\Models\sigmel_lista_cie_diagnosticos;
use App\Models\sigmel_lista_clases_decretos;
use App\Models\sigmel_informacion_deficiencias_alteraciones_eventos;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_informacion_firmas_clientes;
use App\Models\sigmel_informacion_laboral_eventos;
use App\Models\sigmel_informacion_laboralmente_activo_eventos;
use App\Models\sigmel_informacion_libro2_libro3_eventos;
use App\Models\sigmel_informacion_rol_ocupacional_eventos;
use App\Models\sigmel_lista_tipo_eventos;

use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;
use App\Models\sigmel_informacion_acciones_automaticas_eventos;
use App\Models\sigmel_informacion_agudeza_visualre_eventos;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\sigmel_informacion_comite_interdisciplinario_eventos;
use App\Models\sigmel_informacion_historial_accion_eventos;
use App\Models\sigmel_registro_descarga_documentos;
use App\Models\sigmel_lista_causal_devoluciones;
use App\Models\sigmel_lista_dominancias;
use App\Models\sigmel_lista_procesos_servicios;
use App\Models\sigmel_lista_regional_juntas;
use App\Models\sigmel_auditorias_informacion_accion_eventos;
use App\Models\sigmel_numero_orden_eventos;
use App\Services\GenerarDictamenesPcl;
use App\Services\GlobalService;
use App\Traits\GenerarRadicados;

use DateTime;
use Psy\Readline\Hoa\Console;
use Svg\Tag\Rect;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CalificacionPCLController extends Controller
{
    use GenerarRadicados;

    protected $globalService;

    public function __construct(GlobalService $globalService)
    {
        $this->globalService = $globalService;
    }

    public function mostrarVistaCalificacionPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);

        if (!empty($request->newIdAsignacion)) {
            // vienen del formulario de la bandeja
            $newIdAsignacion=$request->newIdAsignacion;
            $newIdEvento = $request->newIdEvento;         
            // $Id_servicio = $request->Id_Servicio;
            if ($request->Id_Servicio <> "") {
                $Id_servicio = $request->Id_Servicio;
            } else {
                $Id_servicio = $request->newIdServicio;
            }
        } else {
            // vienen desde la edición del evento
            $newIdAsignacion=$request->Id_asignacion_pcl;
            $newIdEvento = $request->Id_evento_pcl;
            $Id_servicio = $request->Id_servicio_pcl;     
        }
        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));
        $Nro_ident_afiliado = $array_datos_calificacionPcl[0]->Nro_identificacion;
        $array_datos_destinatarios = cndatos_comunicado_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$newIdEvento],['Nro_identificacion',$Nro_ident_afiliado]])
        ->limit(1)->get();
        
        //Consulta Vista a mostrar
        $TraeVista= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_lista_procesos_servicios as p')
        ->select('v.nombre_renderizar')
        ->leftJoin('sigmel_sys.sigmel_vistas as v', 'p.Id_vista', '=', 'v.id')
        ->where('p.Id_Servicio',  '=', $array_datos_calificacionPcl[0]->Id_Servicio)
        ->get();
        $SubModulo=$TraeVista[0]->nombre_renderizar; //Enviar a la vista del SubModulo    

        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where([
            ['Estado', 'Activo'], ['Id_proceso',$array_datos_calificacionPcl[0]->Id_proceso],
            ['ID_evento', $newIdEvento],
            ['Id_Asignacion', $newIdAsignacion]
        ])
        ->get();


       // creación de consecutivo para el comunicado
       $consecutivo = $this->getRadicado('pcl',$newIdEvento);

       $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
       ->select('Id_Documento_Solicitado', 'Aporta_documento')
       ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion], ['Estado', 'Inactivo'], ['Aporta_documento', 'No']])
       ->get();

       $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?,?,?)',array($newIdEvento, $Id_servicio,$newIdAsignacion));

       // cantidad de documentos cargados

       $cantidad_documentos_cargados = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
       ->where([
           ['ID_evento', $newIdEvento],
           ['Id_servicio', $Id_servicio]
       ])->get();

        $arraycampa_documento_solicitado = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $newIdEvento],
            ['Estado', 'Activo'],
        ])
        ->get();    
        
        $info_comite_inter = sigmel_informacion_comite_interdisciplinario_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion]])->get();

        $info_accion_eventos = sigmel_informacion_accion_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion]])->get();

        // Validar si la accion ejecutada tiene enviar a notificaciones            
        $enviar_notificaciones = BandejaNotifiController::evento_en_notificaciones($newIdEvento,$newIdAsignacion);

        //Traer el N_siniestro del evento
        $N_siniestro_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('N_siniestro')
        ->where([['ID_evento',$newIdEvento]])
        ->get();        
        
        return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'array_datos_destinatarios', 'listado_documentos_solicitados', 
        'arraylistado_documentos', 'cantidad_documentos_cargados', 'dato_validacion_no_aporta_docs', 'SubModulo','consecutivo','arraycampa_documento_solicitado', 
        'info_comite_inter', 'Id_servicio', 'info_accion_eventos', 'enviar_notificaciones','N_siniestro_evento'));
    }

    public function cargueListadoSelectoresModuloCalifcacionPcl(Request $request){
        $parametro = $request->parametro;
        // Listado Modalidad calificacion PCL

        if($parametro == 'lista_modalidad_calificacion_pcl'){
            $listado_modalidad_calificacion = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Modalidad de Calificacion'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_modalidad_calificacion = json_decode(json_encode($listado_modalidad_calificacion, true));
            return response()->json($info_listado_modalidad_calificacion);

        }

        // Listado Fuente de informacion PCL

        if($parametro == 'lista_fuente_informacion'){
            $listado_fuente_info_calificacion = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Fuente informacion'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_fuente_info_calificacion = json_decode(json_encode($listado_fuente_info_calificacion, true));
            return response()->json($info_listado_fuente_info_calificacion);

        }

        //  listado Causal seguimiento en la modal Agregar Seguimiento
        if ($parametro == 'lista_causal_seguimiento_pcl'){
            $listado_causal_seguimiento = sigmel_lista_causal_seguimiento::on('sigmel_gestiones')
            ->select('Id_causal', 'Nombre_causal')
            ->where([
                ['Estado', '=', 'activo']                
            ])
            ->get();

            $info_listado_causal_seguimiento= json_decode(json_encode($listado_causal_seguimiento, true));
            return response()->json($info_listado_causal_seguimiento);
        }

        // Listados generar comunicado

        if ($parametro == "departamentos_generar_comunicado") {
            
            $listado_departamentos_generar_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_departamento', 'Nombre_departamento')
                ->where('Estado', 'activo')
                ->groupBy('Nombre_departamento')
                ->get();
            
            $info_lista_departamentos_generar_comunicado = json_decode(json_encode($listado_departamentos_generar_comunicado, true));
            return response()->json($info_lista_departamentos_generar_comunicado);
        }

        if($parametro == "municipios_generar_comunicado"){
            $listado_municipios_generar_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_municipios', 'Nombre_municipio')
                ->where([
                    ['Id_departamento', '=', $request->id_departamento_destinatario],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_lista_municipios_generar_comunicado = json_decode(json_encode($listado_municipios_generar_comunicado, true));
            return response()->json($info_lista_municipios_generar_comunicado);
        }

        if($parametro == 'lista_forma_envio'){
            $listado_modalidad_calificacion = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Forma de envio'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_modalidad_calificacion = json_decode(json_encode($listado_modalidad_calificacion, true));
            return response()->json($info_listado_modalidad_calificacion);

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

        // Listado Causal de devolucion comite PCL
        if($parametro == 'lista_causal_devo_comite'){            

            $listado_causal_devo_comite = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_lista_causal_devoluciones as slcd')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_asignacion_eventos as siae', 'siae.Id_proceso', '=', 'slcd.Id_proceso')
            ->select('slcd.Id_causal_devo', 'slcd.Causal_devolucion')
            ->where([
                ['siae.Id_Asignacion',$request->Id_asignacion_pro], 
                ['slcd.Id_proceso',$request->Id_proceso_actual], 
                ['slcd.Estado','activo']
            ])->get();            

            $info_listado_causal_devo_comite = json_decode(json_encode($listado_causal_devo_comite, true));
            return response()->json($info_listado_causal_devo_comite);
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

        if ($parametro == "listado_solicitud_documentos") {
            $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Nombre_documento', 'Descripcion')
            ->where([
                ['Estado', 'Activo'], ['Id_proceso',$request->id_proceso],
                ['ID_evento', $request->id_evento],
                ['Id_Asignacion', $request->id_asignacion]
            ])
            ->get();

            $info_listado_documentos_solicitados = json_decode(json_encode($listado_documentos_solicitados,true));
            return response()->json($info_listado_documentos_solicitados);
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

    public function guardarCalificacionPCL(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $datetime = date("Y-m-d h:i:s", $time);
        $date_time = date("Y-m-d H:i:s");
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        $Id_proceso = $request->Id_proceso;
        $Id_servicio = $request->Id_servicio;

        $Accion_realizar = $request->accion;

        //if ($Accion_realizar == 52 || $Accion_realizar == 98 || $Accion_realizar == 99) {
        if ($Accion_realizar == 224) {
            $Fecha_devolucion_comite = $date;
            $Causal_devolucion_comite =$request->causal_devolucion_comite;
        }else{
            if ($request->fecha_devolucion == "0000-00-00 00:00:00" || $request->fecha_devolucion == "Sin Fecha Devolución") {
                $Fecha_devolucion_comite = null;
            } else {
                $Fecha_devolucion_comite = $request->fecha_devolucion;
            }
            $Causal_devolucion_comite =$request->causal_devolucion_comite;
        }

        // Programación para la Nueva Fecha de Radicación
        if ($request->nueva_fecha_radicacion <> "") {
            $Nueva_fecha_radicacion = $request->nueva_fecha_radicacion;
        } else {
            $Nueva_fecha_radicacion = null;
        }
        
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

            if(count($estado_acorde_a_parametrica)>0){
                $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
            }else{
                $Id_Estado_evento = 223;
            }

            //Trae El numero de orden actual
            $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
            ->select('Numero_orden')
            ->get();

            $n_ordenNotificacion = DB::table(getDatabaseName('sigmel_gestiones') . "sigmel_informacion_asignacion_eventos")
            ->select('N_de_orden')->where('Id_Asignacion', $newIdAsignacion)->get()->first();

            //Asignamos #n de orden cuado se envie un caso a notificaciones
            if(!empty($estado_acorde_a_parametrica[0]->enviarA) && $estado_acorde_a_parametrica[0]->enviarA != 'No'){
                BandejaNotifiController::finalizarNotificacion($newIdEvento,$newIdAsignacion,false);
                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? $n_orden[0]->Numero_orden;
            }else{

                BandejaNotifiController::finalizarNotificacion($newIdEvento,$newIdAsignacion,true);
                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? null;
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
                          
            $datos_info__registrarCalifcacionPcl= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                // 'Modalidad_calificacion' => $request->modalidad_calificacion,
                'fuente_informacion' => $request->fuente_informacion,
                'F_accion' => $date_time,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Estado_Facturacion' => $request->estado_facturacion,
                'Causal_devolucion_comite' => $Causal_devolucion_comite,                    
                'F_devolucion_comite' => $Fecha_devolucion_comite,
                'Descripcion_accion' => $request->descripcion_accion,
                'F_cierre' => $request->fecha_cierre,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            $aud_datos_info_registrarCalifcacionPcl= [
                'Aud_ID_evento' => $request->newId_evento,
                'Aud_Id_Asignacion' => $request->newId_asignacion,
                'Aud_Id_proceso' => $request->Id_proceso,
                // 'Aud_Modalidad_calificacion' => $request->modalidad_calificacion,
                'Aud_fuente_informacion' => $request->fuente_informacion,
                'Aud_F_accion' => $date_time,
                'Aud_Accion' => $request->accion,
                'Aud_F_Alerta' => $request->fecha_alerta,
                'Aud_Enviar' => $request->enviar,
                'Aud_Estado_Facturacion' => $request->estado_facturacion,
                'Aud_Causal_devolucion_comite' => $Causal_devolucion_comite,                    
                'Aud_F_devolucion_comite' => $Fecha_devolucion_comite,
                'Aud_Descripcion_accion' => $request->descripcion_accion,
                'Aud_F_cierre' => $request->fecha_cierre,
                'Aud_Nombre_usuario' => $nombre_usuario,
                'Aud_F_registro' => $date,
            ];
            

            $Id_Accion_eventos = sigmel_informacion_accion_eventos::on('sigmel_gestiones')->insertGetId($datos_info__registrarCalifcacionPcl);

            // Realizamos la inserción a la tabla de auditoria sigmel_auditorias_informacion_accion_eventos
            sigmel_auditorias_informacion_accion_eventos::on('sigmel_auditorias')->insert($aud_datos_info_registrarCalifcacionPcl);

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

            // Actualización tabla sigmel_informacion_asginacion_eventos
            $datos_info_actualizarAsignacionEvento= [      
                'Id_accion' => $request->accion,
                'Id_Estado_evento' => $Id_Estado_evento,
                'F_alerta' => $request->fecha_alerta,
                'F_accion' => $date_time,
                'Id_profesional' => $id_profesional,
                'Nombre_profesional' => $asignacion_profesional,
                'Nueva_F_radicacion' => $Nueva_fecha_radicacion,
                'N_de_orden' =>  $N_orden_evento,
                'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',          
                'Nombre_usuario' => $nombre_usuario,
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                // 'F_registro' => $date,
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
                'Accion_realizada' => "Guardado Modulo Calificacion Pcl.",
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
                $tipo_archivo = "Documento Historial PCL";
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
                "parametro" => 'agregarCalificacionPcl',
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

            if(count($estado_acorde_a_parametrica)>0){
                $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
            }else{
                $Id_Estado_evento = 223;
            }

            //Trae El numero de orden actual
            $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
            ->select('Numero_orden')
            ->get();

            $n_ordenNotificacion = DB::table(getDatabaseName('sigmel_gestiones') . "sigmel_informacion_asignacion_eventos")
            ->select('N_de_orden')->where('Id_Asignacion', $newIdAsignacion)->get()->first();

            //Asignamos #n de orden cuado se envie un caso a notificaciones
            if(!empty($estado_acorde_a_parametrica[0]->enviarA) && $estado_acorde_a_parametrica[0]->enviarA != 'No'){
                BandejaNotifiController::finalizarNotificacion($newIdEvento,$newIdAsignacion,false);
                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? $n_orden[0]->Numero_orden;
            }else{
                BandejaNotifiController::finalizarNotificacion($newIdEvento,$newIdAsignacion,true);
                $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? null;
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
            
            $datos_info_actualizarCalifcacionPcl= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Id_proceso' => $request->Id_proceso,
                // 'Modalidad_calificacion' => $request->modalidad_calificacion,
                'fuente_informacion' => $request->fuente_informacion,
                'F_accion' => $date_time,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Estado_Facturacion' => $request->estado_facturacion,
                'Causal_devolucion_comite' => $Causal_devolucion_comite,
                'F_devolucion_comite' => $Fecha_devolucion_comite,
                'Descripcion_accion' => $request->descripcion_accion,
                'F_cierre' => $request->fecha_cierre,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            $aud_datos_info_actualizarCalifcacionPcl= [
                'Aud_ID_evento' => $request->newId_evento,
                'Aud_Id_Asignacion' => $request->newId_asignacion,
                'Aud_Id_proceso' => $request->Id_proceso,
                // 'Aud_Modalidad_calificacion' => $request->modalidad_calificacion,
                'Aud_fuente_informacion' => $request->fuente_informacion,
                'Aud_F_accion' => $date_time,
                'Aud_Accion' => $request->accion,
                'Aud_F_Alerta' => $request->fecha_alerta,
                'Aud_Enviar' => $request->enviar,
                'Aud_Estado_Facturacion' => $request->estado_facturacion,
                'Aud_Causal_devolucion_comite' => $Causal_devolucion_comite,
                'Aud_F_devolucion_comite' => $Fecha_devolucion_comite,
                'Aud_Descripcion_accion' => $request->descripcion_accion,
                'Aud_F_cierre' => $request->fecha_cierre,
                'Aud_Nombre_usuario' => $nombre_usuario,
                'Aud_F_registro' => $date,
            ];
            

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarCalifcacionPcl);

            // Realizamos la inserción a la tabla de auditoria sigmel_auditorias_informacion_accion_eventos
            sigmel_auditorias_informacion_accion_eventos::on('sigmel_auditorias')->insert($aud_datos_info_actualizarCalifcacionPcl);

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

            // Actualizar la tabla sigmel_informacion_asignacion_eventos
            $datos_info_actualizarAsignacionEvento= [      
                'Id_accion' => $request->accion,
                'Id_Estado_evento' => $Id_Estado_evento, 
                'F_alerta' => $request->fecha_alerta, 
                'F_accion' => $date_time,
                'Id_profesional' => $id_profesional,
                'Nombre_profesional' => $asignacion_profesional,
                'Nueva_F_radicacion' => $Nueva_fecha_radicacion,
                'N_de_orden' =>  $N_orden_evento,
                'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',  
                'Nombre_usuario' => $nombre_usuario,
                'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion,
                // 'F_registro' => $date,
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
                'Accion_realizada' => "Actualizado Modulo Calificacion Pcl.",
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
                $tipo_archivo = "Documento Historial PCL";
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
                "parametro" => 'agregarCalificacionPcl',
                "mensaje" => 'Registro actualizado satisfactoriamente.',
                "mensaje_2" => $mensaje_2
            );
    
            return json_decode(json_encode($mensajes, true));            
        }

        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));

        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?,?)',array($newIdEvento, $Id_servicio));
    
        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->where('Estado', 'Activo')
        ->get();

        $dato_validacion_no_aporta_docs = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'Aporta_documento')
        ->where([['ID_evento', $newIdEvento],['Id_Asignacion', $newIdAsignacion], ['Estado', 'Inactivo'], ['Aporta_documento', 'No']])
        ->get();
        

        $arraycampa_documento_solicitado = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento', $newIdEvento],
            ['Estado', 'Activo'],
        ])
        ->get();

        return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'arraylistado_documentos', 'listado_documentos_solicitados', 
        'dato_validacion_no_aporta_docs','arraycampa_documento_solicitado', 'Id_servicio'));
        
    }

    // Cargue de listado de Documentos Solicitados para el modal Solicitud Documentos-Seguimientos
    public function CargarDatosSolicitados(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $parametro = $request->parametro;

        if ($parametro == 'listado_documentos_solicitados') {
            $datos_docs_solicitados = sigmel_lista_documentos::on('sigmel_gestiones')
            ->select('Id_Documento', 'Nro_documento', 'Nombre_documento')
            ->where('Estado', 'activo')
            ->whereIn('Nro_documento', [4,31,9,28,29,30,37])->get();

            $informacion_docs_solicitados = json_decode(json_encode($datos_docs_solicitados), true);
            return response()->json($informacion_docs_solicitados);
        }

        if ($parametro == 'listado_solicitantes') {
            $datos_solicitantes = sigmel_lista_solicitantes::on('sigmel_gestiones')
            ->select('Id_solicitante', 'Solicitante')
            ->where('Estado', 'activo')
            ->whereNotIn('Id_solicitante', [6,7])
            ->groupBy('Id_solicitante','Solicitante')
            ->get();

            $informacion_solicitantes = json_decode(json_encode($datos_solicitantes), true);
            return response()->json($informacion_solicitantes);
        }
    }

    // Guardar la información del Listado de Documentos solicitados
    public function GuardarDocumentosSolicitados(Request $request){
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
            $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso','F_solicitud_documento','Id_Documento','Nombre_documento',
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

    public function CargarDocumentosSolicitados(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }

        $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->select('Id_Documento_Solicitado', 'F_solicitud_documento', 'Nombre_documento', 
        'Descripcion', 'Nombre_solicitante', 'F_recepcion_documento')
        ->get();

        $info_listado_documentos_solicitados = json_decode(json_encode($listado_documentos_solicitados, true));
        return response()->json($info_listado_documentos_solicitados);
    }

    // Eliminar fila de algun registro de la tabla de listado documentos seguimiento
    public function EliminarFila(Request $request){

        $id_fila = $request->fila;

        $dato_actualizar = [
            'Estado' => 'Inactivo'
        ];

        sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')->where('Id_Documento_Solicitado', $id_fila)
        ->update($dato_actualizar);

        $total_registros = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_eliminada',
            'total_registros' => $total_registros,
            "mensaje" => 'Información eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    // Editar fecha de algun registro de la tabla de listado documentos solicitados
    public function EditarFecha_Recepcion_Doc_soli(Request $request){

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

    // Insercion agregar seguimiento
    public function guardarAgregarSeguimiento(Request $request){
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
            'Accion_realizada' => "Se agrego seguimiento.",
            'Descripcion' => $descripcion_seguimiento,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);

        $mensajes = array(
            "parametro" => 'agregar_seguimiento',
            "mensaje" => 'Seguimiento agregado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    // Captura de la data para el DataTable Historial de Seguimientos

    public function historialSeguimientosPCL(Request $request){
        $HistorialSeguimientoPcl = $request->HistorialSeguimientoPcl;
        $newId_evento = $request->newId_evento;
        $newId_asignacion = $request->newId_asignacion;

        if ($HistorialSeguimientoPcl == 'CargaHistorialSeguimiento') {
            
            $hitorialAgregarSeguimiento = sigmel_informacion_seguimientos_eventos::on('sigmel_gestiones')
            ->select('ID_evento','F_seguimiento','Causal_seguimiento','Descripcion_seguimiento','Nombre_usuario')
            ->where([
                ['ID_evento', $newId_evento],
                ['Id_Asignacion', $newId_asignacion],
                ['Estado','Activo'],
                ['Id_proceso','2']
            ])
            ->get();

            $arrayhistorialSeguimiento = json_decode(json_encode($hitorialAgregarSeguimiento, true));
            return response()->json(($arrayhistorialSeguimiento));

        }
    }

    //Captura de datos para insertar el comunicado

    public function captuarDestinatariosPrincipal(Request $request){
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

    public function guardarComunicado(Request $request){
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

        $radicado = $this->disponible($request->radicado2,$Id_evento)->getRadicado('pcl',$Id_evento);

        //Se asignan los IDs de destinatario por cada posible destinatario
        $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario();
        
        if($tipo_descarga != 'Manual'){
            $radioafiliado_comunicado = $request->radioafiliado_comunicado;
            $radioempresa_comunicado = $request->radioempresa_comunicado;
            $radioOtro = $request->radioOtro;
            $firmarcomunicado = $request->firmarcomunicado;        
            if (!empty($firmarcomunicado)) {
                $firmacliente = implode($firmarcomunicado);
            } else {
                $firmacliente = '';
            }   
            $agregar_copia = $request->agregar_copia;
            if (!empty($agregar_copia)) {
                $total_agregarcopias = implode(", ", $agregar_copia);                
            }else{
                $total_agregarcopias = '';
            }        
            if(!empty($radioafiliado_comunicado) && empty($radioempresa_comunicado) && empty($radioOtro)){
                $destinatario = 'Afiliado';
            }elseif(empty($radioafiliado_comunicado) && !empty($radioempresa_comunicado) && empty($radioOtro)){
                $destinatario = 'Empleador';
            }elseif(empty($radioafiliado_comunicado) && empty($radioempresa_comunicado) && !empty($radioOtro)){
                $destinatario = 'Otro';
            }

            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $request->N_siniestro
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$Id_evento]])
            ->update($dato_actualizar_n_siniestro);
            
            sleep(2);

            $tipo_descarga = $request->tipo_descarga;
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
                'Agregar_copia' => $total_agregarcopias,
                'Firmar_Comunicado' => $firmacliente,
                'Tipo_descarga' => $tipo_descarga,
                'Modulo_creacion' => 'calificacionPCL',
                'Reemplazado'=> 0,
                'N_siniestro' => $request->N_siniestro,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
            
            $Id_Comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_registrarComunicadoPcl);

            sleep(2);
            $datos_info_historial_acciones = [
                'ID_evento' => $Id_evento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Se genera comunicado.",
                'Descripcion' => $request->asunto,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);

            $mensajes = array(
                "parametro" => 'agregar_comunicado',
                "mensaje" => 'Comunicado generado satisfactoriamente.',
                "Id_Comunicado" => $Id_Comunicado,
                "comunicadoSigmel" => 'DocumentoSigmel'
            );
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

            $datos_info_comunicado_manual=[
    
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
                'Modulo_creacion' => 'calificacionPCL',
                'Reemplazado' => 0,
                // 'Nombre_documento' => $request->Nombre_documento,
                'Nombre_documento' => $nombre_final_documento,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insert($datos_info_comunicado_manual);

            sleep(2);
            $datos_info_historial_acciones = [
                'ID_evento' => $Id_evento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Se genera comunicado de forma manual en $modulo.",
                // 'Descripcion' => $request->asunto,
                'Descripcion' => $nombre_final_documento,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);

            $mensajes = array(
                "parametro" => 'agregar_comunicado',
                "mensaje" => 'Comunicado generado satisfactoriamente.',
                "comunicadoSigmel" => 'DocumentoManual'
            );
        }
        

        return json_decode(json_encode($mensajes, true));

    }

    public function historialComunicadosPCL(Request $request){

        $HistorialComunicadosPcl = $request->HistorialComunicadosPcl;
        $newId_evento = $request->newId_evento;
        $newId_asignacion = $request->newId_asignacion;        
        if ($HistorialComunicadosPcl == 'CargarComunicados') {
            
            $hitorialAgregarComunicado = cndatos_info_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $newId_evento],
                ['Id_Asignacion', $newId_asignacion],
                ['Id_proceso', '2']
            ])
            ->get();
            // Validar si la accion ejecutada tiene enviar a notificaciones
            $enviar_notificacion =  BandejaNotifiController::evento_en_notificaciones($newId_evento,$newId_asignacion);
            
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
            $arrayhitorialAgregarComunicado = json_decode(json_encode($hitorialAgregarComunicado, true));
            return response()->json([
                'hitorialAgregarComunicado' => $hitorialAgregarComunicado,
                'enviar_notificacion' => $enviar_notificacion
            ]);


        }

        if($request->bandera == 'Actualizar'){
            $request->validate([
                'bandera' => 'required',
                'radicado' => 'required',
                'id_asignacion' => 'required'
            ]);

            //Accion Actualizar status,nota del comunicado
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where([
                ['N_radicado',$request->radicado],
                ['Id_Asignacion', $request->id_asignacion]])->update([
                'Nota' => $request->Nota,
                'Estado_notificacion' => $request->Estado_general
            ]);

            $mensajeResponse = 'Comunicado actualizado correctamente';
            
            return $mensajeResponse;
        }

        if($request->bandera == 'info_comunicado'){
            $request->validate([
                'bandera' => 'required',
                'radicado' => 'required',
                'id_asignacion' => 'required'
            ]);

            $infoComunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['N_radicado', $request->radicado],
                ['Id_Asignacion', $request->id_asignacion],
            ])
            ->get();

            $arrayhitorialAgregarComunicado = json_decode(json_encode($infoComunicado, true));
            return response()->json(($arrayhitorialAgregarComunicado));
        }

        
        if($request->bandera == 'Actualizar'){
            $request->validate([
                'bandera' => 'required',
                'radicado' => 'required',
                'id_asignacion' => 'required'
            ]);

            //Accion Actualizar status,nota del comunicado
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where([
                ['N_radicado',$request->radicado],
                ['Id_Asignacion', $request->id_asignacion]])->update([
                'Nota' => $request->Nota,
                'Estado_notificacion' => $request->Estado_general
            ]);

            $mensajeResponse = 'Comunicado actualizado correctamente';
            
            return $mensajeResponse;
        }
    }

    public function mostrarModalComunicadoPCL(Request $request){

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

    public function actualizarComunicado(Request $request){
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
        $firmarcomunicado = $request->firmarcomunicado_editar;
        if (!empty($firmarcomunicado)) {
            $firmacliente = implode($firmarcomunicado);
        } else {
            $firmacliente = '';
        }        
        $agregar_copia = $request->agregar_copia_editar;
        if (!empty($agregar_copia)) {
            $total_agregarcopias = implode(", ", $agregar_copia);                
        }else{
            $total_agregarcopias = '';
        }  
        if(!empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar) && empty($radioOtro_editar)){
            $destinatario = 'Afiliado';
        }elseif(empty($radioafiliado_comunicado_editar) && !empty($radioempresa_comunicado_editar) && empty($radioOtro_editar)){
            $destinatario = 'Empleador';
        }elseif(empty($radioafiliado_comunicado_editar) && empty($radioempresa_comunicado_editar) && !empty($radioOtro_editar)){
            $destinatario = 'Otro';
        }

        //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
        $dato_actualizar_n_siniestro = [
            'N_siniestro' => $request->N_siniestro
        ];
        sigmel_informacion_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$Id_evento_editar]])
        ->update($dato_actualizar_n_siniestro);
        sleep(2);

        $tipo_descarga = $request->tipo_descarga;

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
            'Firmar_Comunicado' => $firmacliente,
            'Agregar_copia' => $total_agregarcopias,
            'Tipo_descarga' => $tipo_descarga,
            'Modulo_creacion' => 'calificacionPCL',
            'Reemplazado' => 0,
            'N_siniestro' => $request->N_siniestro,
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
            'Accion_realizada' => "Se actualiza comunicado.",
            'Descripcion' => $request->asunto_editar,
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_info_historial_acciones);
        
        $mensajes = array(
            "parametro" => 'actualizar_comunicado',
            "mensaje" => 'Comunicado actualizado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    public function generarPdf(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;
        $departamento_pdf = $request->departamento_pdf;
        $departamento_destinatario_act = $request->departamento_destinatario_act;
        $ciudad_pdf = $request->ciudad_pdf;
        $ciudad_destinatario_act = $request->ciudad_destinatario_act;
        $Forma_envio = $request->forma_envio_act;
        $Reviso = $request->reviso_act;

        // Si la descarga se hace desde el Icono Descargar (Icono OJO)
        if($request->bandera_descarga == 'IconoDescarga'){
            
            if ($request->afiliado_comunicado_act == "Otro") {
                $nombre_destinatario = $request->nombre_destinatario_act;
                $nit_cc = $request->nic_cc_act;
                $direccion_destinatario = $request->direccion_destinatario_act;
                $telefono_destinatario = $request->telefono_destinatario_act;
                $email_destinatario = $request->email_destinatario_act;
            } else {            
                $nombre_destinatario = $request->nombre_destinatario_act2;
                $nit_cc = $request->nic_cc_act2;
                $direccion_destinatario = $request->direccion_destinatario_act2;
                $telefono_destinatario = $request->telefono_destinatario_act2;
                $email_destinatario = $request->email_destinatario_act2;
            }

            if (empty($departamento_destinatario_act) && empty($ciudad_destinatario_act)) {
                $Id_departamento = $departamento_pdf;
                $Id_municipio = $ciudad_pdf;
            }elseif(!empty($departamento_destinatario_act) && !empty($ciudad_destinatario_act)){
                $Id_departamento = $departamento_destinatario_act;
                $Id_municipio = $ciudad_destinatario_act;
            }

            if (empty($nombre_destinatario) && empty($nit_cc) && empty($direccion_destinatario) && 
                empty($telefono_destinatario) && empty($email_destinatario)) {
                    $Nombre_destinatario = $request->nombre_destinatario_act;
                    $Nit_cc = $request->nic_cc_editar;
                    $Direccion_destinatario = $request->direccion_destinatario_act;
                    $Telefono_destinatario = $request->telefono_destinatario_act;
                    $Email_destinatario = $request->email_destinatario_act;
    
            }elseif(!empty($nombre_destinatario) && !empty($nit_cc) && !empty($direccion_destinatario) && 
                !empty($telefono_destinatario) && !empty($email_destinatario)){
                $Nombre_destinatario = $nombre_destinatario;
                $Nit_cc = $nit_cc;
                $Direccion_destinatario = $direccion_destinatario;
                $Telefono_destinatario = $telefono_destinatario;
                $Email_destinatario = $email_destinatario;
            }
        }
        // La descarga se hace desde que se guarda el comunicado
        elseif($request->bandera_descarga == 'BotonGuardarComunicado'){

            $nombre_destinatario = $request->nombre_destinatario_act2;
            $nit_cc = $request->nic_cc_act2;
            $direccion_destinatario = $request->direccion_destinatario_act2;
            $telefono_destinatario = $request->telefono_destinatario_act2;
            $email_destinatario = $request->email_destinatario_act2;

            $Id_departamento = $departamento_pdf;
            $Id_municipio = $ciudad_pdf;

            $Nombre_destinatario = $nombre_destinatario;
            $Nit_cc = $nit_cc;
            $Direccion_destinatario = $direccion_destinatario;
            $Telefono_destinatario = $telefono_destinatario;
            $Email_destinatario = $email_destinatario;

        }

        $departamentos_info_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
        ->select('Nombre_departamento')
        ->where('Id_departamento',$Id_departamento)
        ->get();
        sleep(2);
        $ciudad_info_comunicado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
        ->select('Nombre_municipio')
        ->where('Id_municipios',$Id_municipio)
        ->get();
        sleep(2);         
        $reviso_info_lider = DB::table('users')
        ->select('name')
        ->where('id', $Reviso)
        ->get();
        sleep(2);
        $forma_info_envio = sigmel_lista_parametros::on('sigmel_gestiones')
        ->select('Nombre_parametro')
        ->where('Id_parametro', $Forma_envio)
        ->get();
        sleep(2);
        $nombre_departamento = $departamentos_info_comunicado[0]->Nombre_departamento;
        $nombre_ciudad = $ciudad_info_comunicado[0]->Nombre_municipio;
        $reviso_lider = $reviso_info_lider[0]->name;
        $forma_envio = $forma_info_envio[0]->Nombre_parametro;
        
                     
        $Id_comunicado = $request->Id_comunicado_act;
        $ID_evento = $request->Id_evento_act;
        $Id_Asignacion = $request->Id_asignacion_act;
        $Id_proceso = $request->Id_procesos_act;
        $Ciudad = $request->ciudad_comunicado_act;
        $F_comunicado = $request->fecha_comunicado2_act;
        $N_radicado = $request->radicado2_act;
        $Cliente = $request->cliente_comunicado2_act;
        $Nombre_afiliado = $request->nombre_afiliado_comunicado2_act;
        $T_documento = $request->tipo_documento_comunicado2_act;
        $N_identificacion = $request->identificacion_comunicado2_act;
        $Destinatario = $request->afiliado_comunicado_act;
        $Nombre_departamento = $nombre_departamento;
        $Nombre_ciudad = $nombre_ciudad;
        $Asunto = $request->asunto_act;
        $Cuerpo_comunicado = $request->cuerpo_comunicado_act;
        $Anexos = $request->anexos_act;
        $Forma_envios = $forma_envio;
        $Elaboro = $request->elaboro2_act;
        $Cargo = $cargo_profesional;
        $Reviso_lider = $reviso_lider;        
        $Nombre_usuario = $nombre_usuario;
        $F_registro = $date;

        // Si la descarga se hace desde el Icono Descargar (Icono OJO)
        if ($request->bandera_descarga == 'IconoDescarga') {            
            // validamos si el checkbox de la firma esta marcado
            $validarFirma = isset($request->firmarcomunicado_editar) ? 'Firmar Documento' : 'No lleva firma';
        } 
        // La descarga se hace desde que se guarda el comunicado
        elseif($request->bandera_descarga == 'BotonGuardarComunicado') {
            $validarFirma = isset($request->firmarcomunicado_editar) ? 'firmar comunicado' : 'No lleva firma';            
        }       

        
        if ($validarFirma == 'Firmar Documento' || $validarFirma == 'firmar comunicado') {            
            $idcliente = sigmel_clientes::on('sigmel_gestiones')->select('Id_cliente', 'Nombre_cliente')
            ->where('Nombre_cliente', $Cliente)->get();
    
            $firmaclientecompleta = sigmel_informacion_firmas_clientes::on('sigmel_gestiones')->select('Firma')
            ->where([['Id_cliente', $idcliente[0]->Id_cliente], ['Estado', '=', 'Activo']])->limit(1)->get();

            if(count($firmaclientecompleta) > 0){
                $Firma_cliente = $firmaclientecompleta[0]->Firma;
            }else{
                $Firma_cliente = 'No firma';
            }
            
        }else{
            $Firma_cliente = 'No firma';
        }

        /* Agregamos el indicativo */
        $indicativo = time();
        
        if($request->tipo_documento_descarga_califi_editar == "Documento_Origen"){

            $dato_fecha_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('F_evento')
            ->where([['ID_evento', $ID_evento]])
            ->get();

            /* Creación de las variables faltantes que no están en el formulario */
            $array_datos_fecha_evento = json_decode(json_encode($dato_fecha_evento), true);
            $fecha_evento = $array_datos_fecha_evento[0]["F_evento"];
            // Si la descarga se hace desde el Icono Descargar (Icono OJO)
            if ($request->bandera_descarga == 'IconoDescarga') {
                /* Copias Interesadas */
                // Validamos si los checkbox esta marcados
                $final_copia_afiliado = isset($request->edit_copia_afiliado) ? 'Afiliado' : '';
                $final_copia_empleador = isset($request->edit_copia_empleador) ? 'Empleador' : '';
                $final_copia_eps = isset($request->edit_copia_eps) ? 'EPS' : '';
                $final_copia_afp = isset($request->edit_copia_afp) ? 'AFP' : '';
                $final_copia_arl = isset($request->edit_copia_arl) ? 'ARL' : '';
    
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
                
            } 
            // La descarga se hace desde que se guarda el comunicado
            elseif ($request->bandera_descarga == 'BotonGuardarComunicado') {

                $copiaComunicadosOrigen = $request->agregar_copia_editar;
                $claves_copias = ['Afiliado' => 'copia_afiliado', 'Empleador' => 'copia_empleador', 'EPS' => 'copia_eps', 'AFP' => 'copia_afp', 'ARL' => 'copia_arl'];

                if ($copiaComunicadosOrigen > 0) {
                    
                    // Inicializar el array con todas las claves con valores vacíos
                    $total_copias = array_fill_keys(array_values($claves_copias), '');
    
                    // Iterar sobre cada copia en $copiaComunicadosOrigen y asignar su valor correspondiente
                    foreach ($copiaComunicadosOrigen as $elemento) {
                        if (isset($claves_copias[$elemento])) {
                            $total_copias[$claves_copias[$elemento]] = $elemento;
                        }
                    }
    
                    // Filtrar las claves que tienen valores vacíos
                    $total_copias = array_filter($total_copias);
    
                    // Convertir las claves en variables con sus respectivos valores
                    extract($total_copias);
                }
            }
                        
            $Agregar_copias = [];
            if (isset($copia_afiliado)) {
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

            if(isset($copia_empleador)){

                $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sile.Email', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
                ->get();

                $nombre_empleador = $datos_empleador[0]->Empresa;
                $direccion_empleador = $datos_empleador[0]->Direccion;
                $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
                $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
                $email_empleador = $datos_empleador[0]->Email;
                $municipio_empleador = $datos_empleador[0]->Nombre_municipio;

                $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$email_empleador."; ".$telefono_empleador."; ".$ciudad_empleador."; ".$municipio_empleador.".";   
            }

            if (isset($copia_eps)) {
                $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                    ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Emails as Email_eps', 'sie.Telefonos', 'sie.Otros_Telefonos', 
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
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
                $minucipio_eps = $datos_eps[0]->Nombre_municipio;

                $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;
            }

            if (isset($copia_afp)) {
                $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email_afp',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
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
                $minucipio_afp = $datos_afp[0]->Nombre_municipio;

                $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$email_afp."; ".$telefonos_afp."; ".$ciudad_afp."; ".$minucipio_afp;
            }

            if(isset($copia_arl)){
                $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email_arl',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
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
                $minucipio_arl = $datos_arl[0]->Nombre_municipio;

                $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$email_arl."; ".$telefonos_arl."; ".$ciudad_arl."; ".$minucipio_arl;
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

            //Trae Documentos Solicitados
            $listado_documentos_solicitados = sigmel_informacion_documentos_solicitados_eventos::on('sigmel_gestiones')
            ->select('Nombre_documento')
            ->where([
                ['ID_evento',$ID_evento],
                ['Estado','Activo'],
                ['Id_proceso', $Id_proceso]
            ])
            ->get();

            $array_listado_documentos_solicitados = json_decode(json_encode($listado_documentos_solicitados), true);

            $pruebas_solicitadas = array();

            for ($i=0; $i < count($array_listado_documentos_solicitados); $i++) { 
                array_push($pruebas_solicitadas, $array_listado_documentos_solicitados[$i]["Nombre_documento"]);
            }
            
            $string_pruebas_solicitadas = "<b>".implode(", ", $pruebas_solicitadas)."</b>";

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
    

            $data = [
                'logo_header' => $logo_header,
                'id_cliente' => $id_cliente,
                'ciudad' => $request->ciudad_comunicado_act,
                'fecha' => fechaFormateada($request->fecha_comunicado2_act),
                'nombre' => $Nombre_destinatario,
                'direccion' => $Direccion_destinatario,
                'telefono' => $Telefono_destinatario,
                'municipio' => $nombre_ciudad,
                'departamento' => $nombre_departamento,
                'nro_radicado' => $request->radicado2_act,
                'tipo_identificacion' => $T_documento,
                'num_identificacion' =>  $N_identificacion,
                'nro_siniestro' => $ID_evento,
                'asunto' => strtoupper($request->asunto_act),
                'cuerpo' => $Cuerpo_comunicado,
                'string_pruebas_solicitadas' => $string_pruebas_solicitadas ,
                'fecha_evento' => $fecha_evento,
                'Firma_cliente' => $Firma_cliente,
                'nombre_usuario' => $nombre_usuario,
                'Agregar_copia' => $Agregar_copias,
                'footer' => $footer,
                'N_siniestro' => $request->n_siniestro_proforma_editar,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];

            // Creación y guardado del pdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Arl/Origen_Atel/solicitud_pruebas', $data);

            // $nombre_pdf = "ORI_SOL_DOC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            $nombre_pdf = "ORI_SOL_DOC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}_{$indicativo}.pdf";

            $output = $pdf->output();

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
            //     // Se valida si antes de insertar la info del doc de origen ya hay un documento de tipo otro
            //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado}.pdf";
            //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->select('Nombre_documento')
            //     ->where([
            //         ['Nombre_documento', $nombre_docu_otro],
            //     ])->get();

            //     // Si no existe info del documento de tipo otro, inserta la info del documento de origen
            //     // De lo contrario hace una actualización de la info
            //     if (count($verificar_docu_otro) == 0) {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion,
            //             'Id_proceso' => $Id_proceso,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $ID_evento,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
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
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => $nombre_usuario,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //         ->where([
            //             ['Id_Asignacion', $Id_Asignacion],
            //             ['N_radicado_documento', $N_radicado],
            //             ['ID_evento', $ID_evento]
            //         ])
            //         ->update($info_descarga_documento);
            //     }
            // }

            // return $pdf->download($nombre_pdf);

            $datos = [
                'indicativo' => $indicativo,
                'nombre_pdf' => $nombre_pdf,
                'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
            ];
            
            return response()->json($datos);

        }
        elseif($request->tipo_documento_descarga_califi_editar == "Documento_PCL"){

            $dato_fecha_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('F_evento')
            ->where([['ID_evento', $ID_evento]])
            ->get();

            /* Creación de las variables faltantes que no están en el formulario */
            $array_datos_fecha_evento = json_decode(json_encode($dato_fecha_evento), true);
            $fecha_evento = $array_datos_fecha_evento[0]["F_evento"];
            // Si la descarga se hace desde el Icono Descargar (Icono OJO)
            if ($request->bandera_descarga == 'IconoDescarga') {                
                /* Copias Interesadas */
                // Validamos si los checkbox esta marcados
                $final_copia_afiliado = isset($request->edit_copia_afiliado) ? 'Afiliado' : '';
                $final_copia_empleador = isset($request->edit_copia_empleador) ? 'Empleador' : '';
                $final_copia_eps = isset($request->edit_copia_eps) ? 'EPS' : '';
                $final_copia_afp = isset($request->edit_copia_afp) ? 'AFP' : '';
                $final_copia_arl = isset($request->edit_copia_arl) ? 'ARL' : '';
    
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

            } 
            // La descarga se hace desde que se guarda el comunicado
            elseif ($request->bandera_descarga == 'BotonGuardarComunicado') {
                $copiaComunicadosPcl = $request->agregar_copia_editar;
                $claves_copias = ['Afiliado' => 'copia_afiliado', 'Empleador' => 'copia_empleador', 'EPS' => 'copia_eps', 'AFP' => 'copia_afp', 'ARL' => 'copia_arl'];
                
                if ($copiaComunicadosPcl > 0) {
                    // Inicializar el array con todas las claves con valores vacíos
                    $total_copias = array_fill_keys(array_values($claves_copias), '');
    
                    // Iterar sobre cada copia en $copiaComunicadosPcl y asignar su valor correspondiente
                    foreach ($copiaComunicadosPcl as $elemento) {
                        if (isset($claves_copias[$elemento])) {
                            $total_copias[$claves_copias[$elemento]] = $elemento;
                        }
                    }
    
                    // Filtrar las claves que tienen valores vacíos
                    $total_copias = array_filter($total_copias);
    
                    // Convertir las claves en variables con sus respectivos valores
                    extract($total_copias);
                    
                } 
            }
            
            $Agregar_copias = [];
            if (isset($copia_afiliado)) {
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

            if(isset($copia_empleador)){

                $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sile.Email','sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
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
                ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Emails as Email','sie.Otros_Telefonos', 
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
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

                $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;
            }

            if (isset($copia_afp)) {
                $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'sie.Emails as Email')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                ->get();

                $nombre_afp = $datos_afp[0]->Nombre_afp;
                $direccion_afp = $datos_afp[0]->Direccion;
                $email_afp = $datos_afp[0]->Email;
                if ($datos_afp[0]->Otros_Telefonos != "") {
                    $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
                } else {
                    $telefonos_afp = $datos_afp[0]->Telefonos;
                }
                $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
                $minucipio_afp = $datos_afp[0]->Nombre_municipio;

                $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$email_afp."; ".$telefonos_afp."; ".$ciudad_afp."; ".$minucipio_afp;
            }

            if(isset($copia_arl)){
                $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'sie.Emails as Email')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
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
            //Footer_Image
            $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
            ->select('Footer_cliente')
            ->where([['Id_cliente', $id_cliente]])
            ->limit(1)->get();

            if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
                $footer = $footer_imagen[0]->Footer_cliente;
            } else {
                $footer = null;
            } 

            //Trae Documentos Solicitados
            $listado_documentos_solicitados = $this->globalService->retornarListadoDocumentos($ID_evento,$Id_proceso,$Id_Asignacion);
            if($listado_documentos_solicitados){
                $array_listado_documentos_solicitados = json_decode(json_encode($listado_documentos_solicitados), true);
                $string_documentos_solicitados = "<ul>";
    
                for ($i=0; $i < count($array_listado_documentos_solicitados); $i++) { 
                    $string_documentos_solicitados .= "<li>".$array_listado_documentos_solicitados[$i]["Descripcion"]."</li>";
                }
                $string_documentos_solicitados .= "</ul>";
            }else{
                $string_documentos_solicitados = '';
            }
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
            $data = [
                'logo_header' => $logo_header,
                'id_cliente' => $id_cliente,
                'ciudad' => $request->ciudad_comunicado_act,
                'fecha' => fechaFormateada($request->fecha_comunicado2_act),
                'Nombre_afiliado' => $Nombre_afiliado,
                'Email_afiliado' => $email_destinatario,
                'T_documento' => $T_documento,
                'N_identificacion'  => $N_identificacion,
                'nombre' => $Nombre_destinatario,
                'direccion' => $Direccion_destinatario,
                'telefono' => $Telefono_destinatario,
                'municipio' => $nombre_ciudad,
                'departamento' => $nombre_departamento,
                'nro_radicado' => $request->radicado2_act,
                'tipo_identificacion' => $T_documento,
                'num_identificacion' =>  $N_identificacion,
                'nro_siniestro' => $ID_evento,
                'asunto' => strtoupper($request->asunto_act),
                'cuerpo' => $Cuerpo_comunicado, 
                'fecha_evento' => $fecha_evento,
                'Firma_cliente' => $Firma_cliente,
                'nombre_usuario' => $nombre_usuario,
                'Anexos' => $Anexos,
                'Agregar_copia' => $Agregar_copias,
                'footer' => $footer,
                'N_siniestro' => $request->n_siniestro_proforma_editar,
                'Documentos_solicitados' => $string_documentos_solicitados
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];

            // Creación y guardado del pdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/solicitud_documentos_pcl', $data);

            // $nombre_pdf = "PCL_SOL_DOC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            $nombre_pdf = "PCL_SOL_DOC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}_{$indicativo}.pdf";

            $output = $pdf->output();

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

            //     // Se valida si antes de insertar la info del doc de Documento_Revision_pension ya hay un documento de tipo otro
            //     // Formato B, Revision Pensión
            //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado}.pdf";
            //     $nombre_docu_formatoB = "PCL_OFICIO_FB_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            //     $nombre_docu_solicitud_revision = "PCL_OFICIO_REV_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            //     $nombre_docu_no_recalificacion = "PCL_OFICIO_REC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";

            //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->select('Nombre_documento')
            //     ->whereIN('Nombre_documento', [$nombre_docu_otro, $nombre_docu_formatoB, 
            //         $nombre_docu_solicitud_revision, $nombre_docu_no_recalificacion]
            //     )->get();

            //     // Si no existe info del documento de solicitud pcl, tipo otro, Formato B, Revision Pensión
            //     // inserta la info del documento de Documento_Revision_pension, De lo contrario hace una actualización de la info
            //     if (count($verificar_docu_otro) == 0) {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion,
            //             'Id_proceso' => $Id_proceso,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $ID_evento,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
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
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => $nombre_usuario,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //         ->where([
            //             ['Id_Asignacion', $Id_Asignacion],
            //             ['N_radicado_documento', $N_radicado],
            //             ['ID_evento', $ID_evento]
            //         ])
            //         ->update($info_descarga_documento);
            //     }
            // }

            // return $pdf->download($nombre_pdf);

            $datos = [
                'indicativo' => $indicativo,
                'nombre_pdf' => $nombre_pdf,
                'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
            ];
            
            return response()->json($datos);

        }
        elseif($request->tipo_documento_descarga_califi_editar == "Formato_B_Revision_pension"){

            $dato_fecha_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('F_evento')
            ->where([['ID_evento', $ID_evento]])
            ->get();

            /* Creación de las variables faltantes que no están en el formulario */
            $array_datos_fecha_evento = json_decode(json_encode($dato_fecha_evento), true);
            $fecha_evento = $array_datos_fecha_evento[0]["F_evento"];
            // Si la descarga se hace desde el Icono Descargar (Icono OJO)
            if ($request->bandera_descarga == 'IconoDescarga') {
                /* Copias Interesadas */
                // Validamos si los checkbox esta marcados
                $final_copia_afiliado = isset($request->edit_copia_afiliado) ? 'Afiliado' : '';
                $final_copia_empleador = isset($request->edit_copia_empleador) ? 'Empleador' : '';
                $final_copia_eps = isset($request->edit_copia_eps) ? 'EPS' : '';
                $final_copia_afp = isset($request->edit_copia_afp) ? 'AFP' : '';
                $final_copia_arl = isset($request->edit_copia_arl) ? 'ARL' : '';
    
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

            } 
            // La descarga se hace desde que se guarda el comunicado
            elseif($request->bandera_descarga == 'BotonGuardarComunicado') {
                $copiaComunicadosPcl = $request->agregar_copia_editar;
                $claves_copias = ['Afiliado' => 'copia_afiliado', 'Empleador' => 'copia_empleador', 'EPS' => 'copia_eps', 'AFP' => 'copia_afp', 'ARL' => 'copia_arl'];
                
                if ($copiaComunicadosPcl > 0) {
                    // Inicializar el array con todas las claves con valores vacíos
                    $total_copias = array_fill_keys(array_values($claves_copias), '');
    
                    // Iterar sobre cada copia en $copiaComunicadosPcl y asignar su valor correspondiente
                    foreach ($copiaComunicadosPcl as $elemento) {
                        if (isset($claves_copias[$elemento])) {
                            $total_copias[$claves_copias[$elemento]] = $elemento;
                        }
                    }
    
                    // Filtrar las claves que tienen valores vacíos
                    $total_copias = array_filter($total_copias);
    
                    // Convertir las claves en variables con sus respectivos valores
                    extract($total_copias);                    
                }
            }
            
            $Agregar_copias = [];
            if (isset($copia_afiliado)) {
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

            if(isset($copia_empleador)){

                $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
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
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
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
                $minucipio_eps = $datos_eps[0]->Nombre_municipio;

                $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;
            }

            if (isset($copia_afp)) {
                $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
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
                $minucipio_afp = $datos_afp[0]->Nombre_municipio;

                $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$telefonos_afp."; ".$ciudad_afp."; ".$minucipio_afp;
            }

            if(isset($copia_arl)){
                $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
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
                $minucipio_arl = $datos_arl[0]->Nombre_municipio;

                $Agregar_copias['ARL'] = $nombre_arl."; ".$direccion_arl."; ".$telefonos_arl."; ".$ciudad_arl."; ".$minucipio_arl;
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
            
            //Footer_Image
            $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
            ->select('Footer_cliente')
            ->where([['Id_cliente', $id_cliente]])
            ->limit(1)->get();

            if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
                $footer = $footer_imagen[0]->Footer_cliente;
            } else {
                $footer = null;
            } 

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

            $data = [
                'logo_header' => $logo_header,
                'id_cliente' => $id_cliente,
                'ciudad' => $request->ciudad_comunicado_act,
                'fecha' => fechaFormateada($request->fecha_comunicado2_act),
                'Nombre_afiliado' => $Nombre_afiliado,
                'T_documento' => $T_documento,
                'N_identificacion'  => $N_identificacion,  
                'nombre' => $Nombre_destinatario,
                'direccion' => $Direccion_destinatario,
                'telefono' => $Telefono_destinatario,
                'municipio' => $nombre_ciudad,
                'departamento' => $nombre_departamento,
                'nro_radicado' => $request->radicado2_act,
                'tipo_identificacion' => $T_documento,
                'num_identificacion' =>  $N_identificacion,
                'nro_siniestro' => $ID_evento,
                'asunto' => strtoupper($request->asunto_act),
                'cuerpo' => $Cuerpo_comunicado, 
                'fecha_evento' => $fecha_evento,
                'Firma_cliente' => $Firma_cliente,
                'nombre_usuario' => $nombre_usuario,
                'Anexos' => $Anexos,
                'Agregar_copia' => $Agregar_copias,
                'footer' => $footer,
                'email_destinatario' => $email_destinatario,
                'N_siniestro' => $request->n_siniestro_proforma_editar,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];

            // Creación y guardado del pdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_formato_b_revisionPension', $data);


            // $nombre_pdf = "PCL_OFICIO_FB_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            $nombre_pdf = "PCL_OFICIO_FB_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}_{$indicativo}.pdf";

            $output = $pdf->output();

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

            //     // Se valida si antes de insertar la info del doc de Formato_B_Revision_pension ya hay un documento de solicitud pcl
            //     // tipo otro y/o Formato B
            //     $nombre_docu_solicitud_pcl = "PCL_SOL_DOC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado}.pdf";
            //     $nombre_docu_solicitud_revision = "PCL_OFICIO_REV_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";

            //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->select('Nombre_documento')
            //     ->whereIN('Nombre_documento', [$nombre_docu_solicitud_pcl, $nombre_docu_otro, $nombre_docu_solicitud_revision]
            //     )->get();

            //     // Si no existe info del documento de solicitud pcl, tipo otro, Formato B 
            //     // inserta la info del documento de Formato_B_Revision_pension, De lo contrario hace una actualización de la info
            //     if (count($verificar_docu_otro) == 0) {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion,
            //             'Id_proceso' => $Id_proceso,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $ID_evento,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
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
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => $nombre_usuario,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //         ->where([
            //             ['Id_Asignacion', $Id_Asignacion],
            //             ['N_radicado_documento', $N_radicado],
            //             ['ID_evento', $ID_evento]
            //         ])
            //         ->update($info_descarga_documento);
            //     }
            // }

            // return $pdf->download($nombre_pdf);

            $datos = [
                'indicativo' => $indicativo,
                'nombre_pdf' => $nombre_pdf,
                'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
            ];
            
            return response()->json($datos);

        }
        elseif($request->tipo_documento_descarga_califi_editar == "Documento_Revision_pension"){

            $dato_fecha_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('F_evento')
            ->where([['ID_evento', $ID_evento]])
            ->get();

            /* Creación de las variables faltantes que no están en el formulario */
            $array_datos_fecha_evento = json_decode(json_encode($dato_fecha_evento), true);
            $fecha_evento = $array_datos_fecha_evento[0]["F_evento"];
            // Si la descarga se hace desde el Icono Descargar (Icono OJO)
            if ($request->bandera_descarga == 'IconoDescarga') {
                /* Copias Interesadas */
                // Validamos si los checkbox esta marcados
                $final_copia_afiliado = isset($request->edit_copia_afiliado) ? 'Afiliado' : '';
                $final_copia_empleador = isset($request->edit_copia_empleador) ? 'Empleador' : '';
                $final_copia_eps = isset($request->edit_copia_eps) ? 'EPS' : '';
                $final_copia_afp = isset($request->edit_copia_afp) ? 'AFP' : '';
                $final_copia_arl = isset($request->edit_copia_arl) ? 'ARL' : '';
    
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
                
            } 
            // La descarga se hace desde que se guarda el comunicado
            elseif ($request->bandera_descarga == 'BotonGuardarComunicado') {
                $copiaComunicadosPcl = $request->agregar_copia_editar;
                $claves_copias = ['Afiliado' => 'copia_afiliado', 'Empleador' => 'copia_empleador', 'EPS' => 'copia_eps', 'AFP' => 'copia_afp', 'ARL' => 'copia_arl'];
                
                if ($copiaComunicadosPcl > 0) {
                    // Inicializar el array con todas las claves con valores vacíos
                    $total_copias = array_fill_keys(array_values($claves_copias), '');
    
                    // Iterar sobre cada copia en $copiaComunicadosPcl y asignar su valor correspondiente
                    foreach ($copiaComunicadosPcl as $elemento) {
                        if (isset($claves_copias[$elemento])) {
                            $total_copias[$claves_copias[$elemento]] = $elemento;
                        }
                    }
    
                    // Filtrar las claves que tienen valores vacíos
                    $total_copias = array_filter($total_copias);
    
                    // Convertir las claves en variables con sus respectivos valores
                    extract($total_copias);                    
                }
            }

            $email_afiliado = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
            ->select('Email')
            ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
            ->get();
            
            //Trae Documentos Solicitados
            $listado_documentos_solicitados = $this->globalService->retornarListadoDocumentos($ID_evento,$Id_proceso,$Id_Asignacion);
            if($listado_documentos_solicitados){
                $array_listado_documentos_solicitados = json_decode(json_encode($listado_documentos_solicitados), true);
                $string_documentos_solicitados = "<ul>";

                for ($i=0; $i < count($array_listado_documentos_solicitados); $i++) { 
                    $string_documentos_solicitados .= "<li>".$array_listado_documentos_solicitados[$i]["Descripcion"]."</li>";
                }
                $string_documentos_solicitados .= "</ul>";
            }else{
                $string_documentos_solicitados = '';
            }
            
            $Agregar_copias = [];
            if (isset($copia_afiliado)) {
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

            if(isset($copia_empleador)){

                $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa','sile.Email', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
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
                ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
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

                $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;
            }

            if (isset($copia_afp)) {
                $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                ->get();

                $nombre_afp = $datos_afp[0]->Nombre_afp;
                $direccion_afp = $datos_afp[0]->Direccion;
                $email_afp = $datos_afp[0]->Email;
                if ($datos_afp[0]->Otros_Telefonos != "") {
                    $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
                } else {
                    $telefonos_afp = $datos_afp[0]->Telefonos;
                }
                $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
                $minucipio_afp = $datos_afp[0]->Nombre_municipio;

                $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$email_afp."; ".$telefonos_afp."; ".$ciudad_afp."; ".$minucipio_afp;
            }

            if(isset($copia_arl)){
                $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
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

            //Footer_Image
            $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
            ->select('Footer_cliente')
            ->where([['Id_cliente', $id_cliente]])
            ->limit(1)->get();

            if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
                $footer = $footer_imagen[0]->Footer_cliente;
            } else {
                $footer = null;
            } 

            $data = [
                'logo_header' => $logo_header,
                'id_cliente' => $id_cliente,
                'email_destinatario' => $email_destinatario,
                'ciudad' => $request->ciudad_comunicado_act,
                'fecha' => fechaFormateada($request->fecha_comunicado2_act),
                'Nombre_afiliado' => $Nombre_afiliado,
                'T_documento' => $T_documento,
                'N_identificacion'  => $N_identificacion,  
                'nombre' => $Nombre_destinatario,
                'direccion' => $Direccion_destinatario,
                'telefono' => $Telefono_destinatario,
                'municipio' => $nombre_ciudad,
                'departamento' => $nombre_departamento,
                'nro_radicado' => $request->radicado2_act,
                'tipo_identificacion' => $T_documento,
                'num_identificacion' =>  $N_identificacion,
                'nro_siniestro' => $ID_evento,
                'asunto' => strtoupper($request->asunto_act),
                'cuerpo' => $Cuerpo_comunicado, 
                'fecha_evento' => $fecha_evento,
                'Firma_cliente' => $Firma_cliente,
                'nombre_usuario' => $nombre_usuario,
                'Anexos' => $Anexos,
                'Agregar_copia' => $Agregar_copias,
                'footer' => $footer,
                'Documentos_solicitados' => $string_documentos_solicitados,
                'N_siniestro' => $request->n_siniestro_proforma_editar,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            // Creación y guardado del pdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/solicitud_documentos_revpen', $data);

            // $nombre_pdf = "PCL_OFICIO_REV_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            $nombre_pdf = "PCL_OFICIO_REV_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}_{$indicativo}.pdf";

            $output = $pdf->output();

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

            //     // Se valida si antes de insertar la info del doc de Documento_Revision_pension ya hay un documento de solicitud pcl
            //     // tipo otro  y/o Formato B
            //     $nombre_docu_solicitud_pcl = "PCL_SOL_DOC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado}.pdf";
            //     $nombre_docu_formatoB = "PCL_OFICIO_FB_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";

            //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->select('Nombre_documento')
            //     ->whereIN('Nombre_documento', [$nombre_docu_solicitud_pcl, $nombre_docu_otro, $nombre_docu_formatoB]
            //     )->get();

            //     // Si no existe info del documento de solicitud pcl, tipo otro,, Formato B 
            //     // inserta la info del documento de Documento_Revision_pension, De lo contrario hace una actualización de la info
            //     if (count($verificar_docu_otro) == 0) {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion,
            //             'Id_proceso' => $Id_proceso,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $ID_evento,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
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
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => $nombre_usuario,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //         ->where([
            //             ['Id_Asignacion', $Id_Asignacion],
            //             ['N_radicado_documento', $N_radicado],
            //             ['ID_evento', $ID_evento]
            //         ])
            //         ->update($info_descarga_documento);
            //     }
            // }

            // return $pdf->download($nombre_pdf);

            $datos = [
                'indicativo' => $indicativo,
                'nombre_pdf' => $nombre_pdf,
                'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
            ];
            
            return response()->json($datos);

        }
        elseif($request->tipo_documento_descarga_califi_editar == "Documento_No_Recalificacion"){

            $dato_fecha_evento = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('F_evento')
            ->where([['ID_evento', $ID_evento]])
            ->get();

            /* Creación de las variables faltantes que no están en el formulario */
            $array_datos_fecha_evento = json_decode(json_encode($dato_fecha_evento), true);
            $fecha_evento = $array_datos_fecha_evento[0]["F_evento"];
            // Si la descarga se hace desde el Icono Descargar (Icono OJO)
            if ($request->bandera_descarga == 'IconoDescarga') {
                /* Copias Interesadas */
                // Validamos si los checkbox esta marcados
                $final_copia_afiliado = isset($request->edit_copia_afiliado) ? 'Afiliado' : '';
                $final_copia_empleador = isset($request->edit_copia_empleador) ? 'Empleador' : '';
                $final_copia_eps = isset($request->edit_copia_eps) ? 'EPS' : '';
                $final_copia_afp = isset($request->edit_copia_afp) ? 'AFP' : '';
                $final_copia_arl = isset($request->edit_copia_arl) ? 'ARL' : '';
    
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
                
            } 
            // La descarga se hace desde que se guarda el comunicado
            elseif ($request->bandera_descarga == 'BotonGuardarComunicado'){
                $copiaComunicadosPcl = $request->agregar_copia_editar;
                $claves_copias = ['Afiliado' => 'copia_afiliado', 'Empleador' => 'copia_empleador', 'EPS' => 'copia_eps', 'AFP' => 'copia_afp', 'ARL' => 'copia_arl'];

                if ($copiaComunicadosPcl > 0) {                    
                    // Inicializar el array con todas las claves con valores vacíos
                    $total_copias = array_fill_keys(array_values($claves_copias), '');
    
                    // Iterar sobre cada copia en $copiaComunicadosPcl y asignar su valor correspondiente
                    foreach ($copiaComunicadosPcl as $elemento) {
                        if (isset($claves_copias[$elemento])) {
                            $total_copias[$claves_copias[$elemento]] = $elemento;
                        }
                    }
    
                    // Filtrar las claves que tienen valores vacíos
                    $total_copias = array_filter($total_copias);
    
                    // Convertir las claves en variables con sus respectivos valores
                    extract($total_copias);
                }
            }
            
            $Agregar_copias = [];
            if (isset($copia_afiliado)) {              
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

            if(isset($copia_empleador)){

                $datos_empleador = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sile.Id_departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sile.Id_municipio', '=', 'sldm2.Id_municipios')
                ->select('sile.Empresa', 'sile.Direccion', 'sile.Telefono_empresa', 'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio','sile.Email')
                ->where([['sile.Nro_identificacion', $N_identificacion],['sile.ID_evento', $ID_evento]])
                ->get();

                $nombre_empleador = $datos_empleador[0]->Empresa;
                $direccion_empleador = $datos_empleador[0]->Direccion;
                $telefono_empleador = $datos_empleador[0]->Telefono_empresa;
                $ciudad_empleador = $datos_empleador[0]->Nombre_ciudad;
                $municipio_empleador = $datos_empleador[0]->Nombre_municipio;
                $email_empleador = $datos_empleador[0]->Email;

                $Agregar_copias['Empleador'] = $nombre_empleador."; ".$direccion_empleador."; ".$email_empleador."; ".$telefono_empleador."; ".$ciudad_empleador."; ".$municipio_empleador.".";   
            }

            if (isset($copia_eps)) {
                $datos_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_eps', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
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

                $Agregar_copias['EPS'] = $nombre_eps."; ".$direccion_eps."; ".$email_eps."; ".$telefonos_eps."; ".$ciudad_eps."; ".$minucipio_eps;
            }

            if (isset($copia_afp)) {
                $datos_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_afp', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos', 'sie.Emails as Email',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                ->get();

                $nombre_afp = $datos_afp[0]->Nombre_afp;
                $direccion_afp = $datos_afp[0]->Direccion;
                $email_afp = $datos_afp[0]->Email;
                if ($datos_afp[0]->Otros_Telefonos != "") {
                    $telefonos_afp = $datos_afp[0]->Telefonos.",".$datos_afp[0]->Otros_Telefonos;
                } else {
                    $telefonos_afp = $datos_afp[0]->Telefonos;
                }
                $ciudad_afp = $datos_afp[0]->Nombre_ciudad;
                $minucipio_afp = $datos_afp[0]->Nombre_municipio;

                $Agregar_copias['AFP'] = $nombre_afp."; ".$direccion_afp."; ".$email_afp."; ".$telefonos_afp."; ".$ciudad_afp."; ".$minucipio_afp;
            }

            if(isset($copia_arl)){
                $datos_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sie.Id_Departamento', '=', 'sldm.Id_departamento')
                ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm2', 'sie.Id_Ciudad', '=', 'sldm2.Id_municipios')
                ->select('sie.Nombre_entidad as Nombre_arl', 'sie.Direccion', 'sie.Telefonos', 'sie.Otros_Telefonos',
                'sldm.Nombre_departamento as Nombre_ciudad', 'sldm2.Nombre_municipio', 'sie.Emails as Email')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
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
            
            //Footer_Image
            $footer_imagen = sigmel_clientes::on('sigmel_gestiones')
            ->select('Footer_cliente')
            ->where([['Id_cliente', $id_cliente]])
            ->limit(1)->get();

            if (count($footer_imagen) > 0 && $footer_imagen[0]->Footer_cliente != null) {
                $footer = $footer_imagen[0]->Footer_cliente;
            } else {
                $footer = null;
            } 

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
            'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro')
            ->where([['side.ID_Evento',$ID_evento], ['side.Id_Asignacion',$Id_Asignacion]])->get(); 
            
            if (count($array_datos_info_dictamen) > 0) {
                $PorcentajePcl_dp = $array_datos_info_dictamen[0]->Porcentaje_pcl;
                $F_estructuracionPcl_dp = $array_datos_info_dictamen[0]->F_estructuracion;
                $OrigenPcl_dp = $array_datos_info_dictamen[0]->Nombre_origen;                
            } else {
                $PorcentajePcl_dp = '';
                $F_estructuracionPcl_dp = '';
                $OrigenPcl_dp = '';    
            }
                        
            // Captura de info para los CIE10
            $array_diagnosticosPcl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
            ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
            ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10')
            ->where([['ID_Evento',$ID_evento], ['Id_Asignacion',$Id_Asignacion], ['Id_proceso',$Id_proceso], ['side.Estado_Recalificacion', 'Activo']])->get(); 
            
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
            // Imprimir la cadena resultante            
            $data = [
                'logo_header' => $logo_header,
                'id_cliente' => $id_cliente,
                'ciudad' => $request->ciudad_comunicado_act,
                'fecha' => fechaFormateada($request->fecha_comunicado2_act),
                'Nombre_afiliado' => $Nombre_afiliado,
                'T_documento' => $T_documento,
                'N_identificacion'  => $N_identificacion,  
                'nombre' => $Nombre_destinatario,
                'direccion' => $Direccion_destinatario,
                'telefono' => $Telefono_destinatario,
                'municipio' => $nombre_ciudad,
                'departamento' => $nombre_departamento,
                'email_destinatario' => $email_destinatario,
                'nro_radicado' => $request->radicado2_act,
                'tipo_identificacion' => $T_documento,
                'num_identificacion' =>  $N_identificacion,
                'nro_siniestro' => $ID_evento,
                'asunto' => strtoupper($request->asunto_act),
                'PorcentajePcl_dp' => $PorcentajePcl_dp,
                'F_estructuracionPcl_dp' => $F_estructuracionPcl_dp,
                'OrigenPcl_dp' => $OrigenPcl_dp,
                'CIE10Nombres' => $CIE10Nombres,
                'cuerpo' => $Cuerpo_comunicado, 
                'fecha_evento' => $fecha_evento,
                'Firma_cliente' => $Firma_cliente,
                'nombre_usuario' => $nombre_usuario,
                'Anexos' => $Anexos,
                'Agregar_copia' => $Agregar_copias,
                'footer' => $footer,
                'N_siniestro' => $request->n_siniestro_proforma_editar,
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];

            // Creación y guardado del pdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_no_recalificacion', $data);

            // $nombre_pdf = "PCL_OFICIO_REC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            $nombre_pdf = "PCL_OFICIO_REC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}_{$indicativo}.pdf";

            $output = $pdf->output();

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


            //     // Se valida si antes de insertar la info del doc de Documento_Revision_pension ya hay un documento de solicitud pcl
            //     // tipo otro
            //     $nombre_docu_solicitud_pcl = "PCL_SOL_DOC_{$Id_comunicado}_{$Id_Asignacion}_{$N_identificacion}.pdf";
            //     $nombre_docu_otro = "Comunicado_{$Id_comunicado}_{$N_radicado}.pdf";

            //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->select('Nombre_documento')
            //     ->whereIN('Nombre_documento', [$nombre_docu_solicitud_pcl, $nombre_docu_otro]
            //     )->get();

            //     // Si no existe info del documento de solicitud pcl, tipo otro,
            //     // inserta la info del documento de Documento_Revision_pension, De lo contrario hace una actualización de la info
            //     if (count($verificar_docu_otro) == 0) {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion,
            //             'Id_proceso' => $Id_proceso,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $ID_evento,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => $nombre_usuario,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
            //     } else {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion,
            //             'Id_proceso' => $Id_proceso,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $ID_evento,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $N_radicado,
            //             'F_elaboracion_correspondencia' => $F_comunicado,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => $nombre_usuario,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //         ->where([
            //             ['Id_Asignacion', $Id_Asignacion],
            //             ['N_radicado_documento', $N_radicado],
            //             ['ID_evento', $ID_evento]
            //         ])
            //         ->update($info_descarga_documento);
            //     }
            // }

            // return $pdf->download($nombre_pdf);

            $datos = [
                'indicativo' => $indicativo,
                'nombre_pdf' => $nombre_pdf,
                'pdf' => base64_encode($pdf->download($nombre_pdf)->getOriginalContent())
            ];
            
            return response()->json($datos);

        }
        else{
            // Si la descarga se hace desde el Icono Descargar (Icono OJO)
            if ($request->bandera_descarga == 'IconoDescarga') {
                // Validamos si los checkbox esta marcados
                $edit_copias_afiliado = isset($request->edit_copia_afiliado) ? 'Afiliado' : '';
                $edit_copias_empleador = isset($request->edit_copia_empleador) ? 'Empleador' : '';
                $edit_copias_eps = isset($request->edit_copia_eps) ? 'EPS' : '';
                $edit_copias_afp = isset($request->edit_copia_afp) ? 'AFP' : '';
                $edit_copias_arl = isset($request->edit_copia_arl) ? 'ARL' : '';
                $edit_copias_jrci = isset($request->edit_copia_jrci) ? 'JRCI': '';
                $edit_copias_jnci = isset($request->edit_copia_jnci) ? 'JNCI': '';
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
                
            } 
            // La descarga se hace desde que se guarda el comunicado
            elseif ($request->bandera_descarga == 'BotonGuardarComunicado') {
                $copiaComunicados = $request->agregar_copia_editar;
                $claves_copias = ['Afiliado' => 'edit_copia_afiliado', 'Empleador' => 'edit_copia_empleador', 'EPS' => 'edit_copia_eps', 'AFP' => 'edit_copia_afp', 'ARL' => 'edit_copia_arl', 'JRCI' => 'edit_copia_jrci', 'JNCI' => 'edit_copia_jnci'];

                if ($copiaComunicados > 0) {
                    // Inicializar el array con todas las claves con valores vacíos
                    $total_copias = array_fill_keys(array_values($claves_copias), '');
    
                    // Iterar sobre cada copia en $copiaComunicados y asignar su valor correspondiente
                    foreach ($copiaComunicados as $elemento) {
                        if (isset($claves_copias[$elemento])) {
                            $total_copias[$claves_copias[$elemento]] = $elemento;
                        }
                    }
    
                    // Filtrar las claves que tienen valores vacíos
                    $total_copias = array_filter($total_copias);
    
                    // Convertir las claves en variables con sus respectivos valores
                    extract($total_copias);                    
                }
            }
            
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
                $nomb_email_Empleador = sigmel_informacion_laboral_eventos::on('sigmel_gestiones')
                ->select('Empresa', 'Email')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                ->get();
                $empleador_nomb = $nomb_email_Empleador[0]->Empresa;
                $empleador_email = $nomb_email_Empleador[0]->Email;            
                $Agregar_copias['Empleador'] = $empleador_nomb.' '.$empleador_email;            
            }

            if (isset($edit_copia_eps)) {
                $nomb_email_eps = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_eps', '=', 'sie.Id_Entidad')
                ->select('siae.Id_eps', 'sie.Nombre_entidad', 'sie.Emails')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                ->get(); 
                $eps_nomb = $nomb_email_eps[0]->Nombre_entidad;
                $eps_email = $nomb_email_eps[0]->Emails;
                $Agregar_copias['EPS'] = $eps_nomb.' '.$eps_email;
            }

            if (isset($edit_copia_afp)) {
                $nomb_email_afp = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp', '=', 'sie.Id_Entidad')
                ->select('siae.Id_afp', 'sie.Nombre_entidad', 'sie.Emails')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                ->get(); 
                $afp_nomb = $nomb_email_afp[0]->Nombre_entidad;
                $afp_email = $nomb_email_afp[0]->Emails;
                $Agregar_copias['AFP'] = $afp_nomb.' '.$afp_email;
            }

            if (isset($edit_copia_arl)) {
                $nomb_email_arl = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
                ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_arl', '=', 'sie.Id_Entidad')
                ->select('siae.Id_arl', 'sie.Nombre_entidad', 'sie.Emails')
                ->where([['Nro_identificacion', $N_identificacion],['ID_evento', $ID_evento]])
                ->get();
                $arl_nomb = $nomb_email_arl[0]->Nombre_entidad;
                $arl_email = $nomb_email_arl[0]->Emails;
                $Agregar_copias['ARL'] = $arl_nomb.' '.$arl_email;
            }
            
            if(isset($edit_copia_jrci)){
                if(!empty($request->input_jrci_seleccionado_copia_editar)){
                    $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
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
                        'sldm1.Nombre_municipio as Nombre_ciudad'
                    )->where([
                        ['sie.Id_Entidad', $request->id_jrci_del_input]
                    ])->get();

                    $jrci_nomb = $datos_jrci[0]->Nombre_entidad;
                    $jrci_email = $datos_jrci[0]->Emails;

                    $Agregar_copias['JRCI'] = $jrci_nomb.' '.$jrci_email;

                }else{

                    $datos_jrci = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_entidades as sie')
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
                        'sldm1.Nombre_municipio as Nombre_ciudad'
                    )->where([
                        ['sie.Id_Entidad', $request->jrci_califi_invalidez_copia_editar]
                    ])->get();

                    $jrci_nomb = $datos_jrci[0]->Nombre_entidad;
                    $jrci_email = $datos_jrci[0]->Emails;

                    $Agregar_copias['JRCI'] = $jrci_nomb.' '.$jrci_email;
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
                    'sie.Emails',
                    'sldm.Id_departamento',
                    'sldm.Nombre_departamento',
                    'sldm1.Id_municipios',
                    'sldm1.Nombre_municipio as Nombre_ciudad'
                )->where([
                    ['sie.IdTipo_entidad', 5]
                ])->limit(1)->get();

                $jnci_nomb = $datos_jnci[0]->Nombre_entidad;
                $jnci_email = $datos_jnci[0]->Emails;

                $Agregar_copias['JNCI'] = $jnci_nomb.' '.$jnci_email;

            }

            /* Extraer el id del cliente */
            $dato_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('Cliente')
            ->where([['ID_evento', $ID_evento]])
            ->get();

            if (count($dato_id_cliente)>0) {
                $id_cliente = $dato_id_cliente[0]->Cliente;
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
            
            //Obtener los datos del formulario
            $data = [
                'ID_evento' => $ID_evento,
                'Id_Asignacion' => $Id_Asignacion,
                'Id_proceso' => $Id_proceso,
                'Ciudad' => $Ciudad,
                'F_comunicado' => $F_comunicado,
                'N_radicado' => $N_radicado,
                'Cliente' => $Cliente,
                'Nombre_afiliado' => $Nombre_afiliado,
                'T_documento' => $T_documento,
                'N_identificacion' => $N_identificacion,
                'Destinatario' => $Destinatario,
                'Nombre_destinatario' => $Nombre_destinatario,
                'Nit_cc' => $Nit_cc,
                'Direccion_destinatario' => $Direccion_destinatario,
                'Telefono_destinatario' => $Telefono_destinatario,
                'Email_destinatario' => $Email_destinatario,
                'Nombre_departamento' => $Nombre_departamento,
                'Nombre_ciudad' => $Nombre_ciudad,
                'Asunto' => $Asunto,
                'Cuerpo_comunicado' => $Cuerpo_comunicado,
                'Firma_cliente' => $Firma_cliente,
                'Anexos' => $Anexos,
                'Forma_envio' => $Forma_envios,
                'Elaboro' => $Elaboro,
                'Cargo' => $Cargo,
                'Reviso' => $Reviso_lider,
                'Agregar_copia' => $Agregar_copias,
                'Nombre_usuario' => $Nombre_usuario,
                'F_registro' => $F_registro,
                'id_cliente' => $id_cliente,
                'footer' => $footer,
                'N_siniestro' => $request->n_siniestro_proforma_editar,
            ];

            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/coordinador/comunicadoPdf', $data);

            // $fileName = 'Comunicado_'.$Id_comunicado.'_'.$N_radicado.'.pdf';
            $fileName = 'Comunicado_'.$N_radicado.'_'.$Id_comunicado.'_'.$indicativo.'.pdf';

            $output = $pdf->output();
            file_put_contents(public_path("Documentos_Eventos/{$ID_evento}/{$fileName}"), $output);

            $actualizar_nombre_documento = [
                'Nombre_documento' => $fileName
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
            // $actualizar_nombre_documento = [
            //     'Nombre_documento' => $fileName
            // ];
            // sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->where('Id_Comunicado', $Id_comunicado)
            // ->update($actualizar_nombre_documento);
            // // Se pregunta por el nombre del documento si ya existe para evitar insertarlo más de una vez
            // $verficar_documento = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            // ->select('Nombre_documento')
            // ->where([
            //     ['Id_Asignacion', $Id_Asignacion],
            //     ['Id_proceso', $Id_proceso],
            //     ['Id_servicio', $Id_servicio],
            //     ['ID_evento', $ID_evento],
            //     ['N_radicado_documento', $N_radicado]
            // ])->get();
            
            // if(count($verficar_documento) == 0){
            //     $info_descarga_documento = [
            //         'Id_Asignacion' => $Id_Asignacion,
            //         'Id_proceso' => $Id_proceso,
            //         'Id_servicio' => $Id_servicio,
            //         'ID_evento' => $ID_evento,
            //         'Nombre_documento' => $fileName,
            //         'N_radicado_documento' => $N_radicado,
            //         'F_elaboracion_correspondencia' => $F_comunicado,
            //         'F_descarga_documento' => $date,
            //         'Nombre_usuario' => $nombre_usuario,
            //     ];
                
            //     sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
            // }else{
            //     $info_descarga_documento = [
            //         'Id_Asignacion' => $Id_Asignacion,
            //         'Id_proceso' => $Id_proceso,
            //         'Id_servicio' => $Id_servicio,
            //         'ID_evento' => $ID_evento,
            //         'Nombre_documento' => $fileName,
            //         'N_radicado_documento' => $N_radicado,
            //         'F_elaboracion_correspondencia' => $F_comunicado,
            //         'F_descarga_documento' => $date,
            //         'Nombre_usuario' => $nombre_usuario,
            //     ];
                
            //     sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->where([
            //         ['Id_Asignacion', $Id_Asignacion],
            //         ['N_radicado_documento', $N_radicado],
            //         ['ID_evento', $ID_evento]
            //     ])
            //     ->update($info_descarga_documento);
            // }

            // return $pdf->download($fileName);

            $datos = [
                'indicativo' => $indicativo,
                'nombre_pdf' => $fileName,
                'pdf' => base64_encode($pdf->download($fileName)->getOriginalContent())
            ];
            
            return response()->json($datos);

        }
    }

    public function historialAcciones(Request $request){

        $datos_info_historial_acciones = sigmel_historial_acciones_eventos::on('sigmel_gestiones')
        ->select('F_accion', 'Nombre_usuario', 'Accion_realizada', 'Descripcion')
        ->where('ID_evento', $request->ID_evento)
        ->orderBy('F_accion', 'asc')
        ->get();        

        return response()->json($datos_info_historial_acciones);
    }

    // Historial de acciones de la parametrica de la tabla sigmel_informacion_historial_accion_eventos

    public function historialAccionesEventoPcl (Request $request){

        $array_datos_historial_accion_eventos = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_historial_accion_eventos as sihae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia', 'sia.Id_Accion', '=', 'sihae.Id_accion')
        ->select('sihae.Id_historial_accion', 'sihae.ID_evento', 'sihae.Id_proceso', 'sihae.Id_servicio', 'sihae.Id_accion', 
        'sia.Accion', 'sihae.Documento', 'sihae.Descripcion', 'sihae.F_accion', 'sihae.Nombre_usuario')
        ->where([['sihae.ID_evento', $request->ID_evento],['sihae.Id_proceso', $request->Id_proceso]])
        ->orderBy('sihae.F_accion', 'asc')->get();
       
        return response()->json($array_datos_historial_accion_eventos);
    }


    /* TODO LO REFERENTE AL SUBMÓDULO DE CALIFICACIÓN TÉNCICA PCL */
    public function mostrarVistaCalificacionTecnicaPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $time = time();
        $date = date("Y-m-d", $time);
        // validar si las variables Evento y Asignacion vienen desde el modulo princinpal o desde el modulo gestion inicial edicion
        if (!empty($request->Id_asignacion_pcl)) {
            $Id_evento_calitec=$request->Id_evento_pcl;
            $Id_asignacion_calitec = $request->Id_asignacion_pcl;            
        }else{
            $Id_evento_calitec=$request->Id_evento_calitec;
            $Id_asignacion_calitec = $request->Id_asignacion_calitec; 
        }

        $Id_proceso_cali = 2;
        //$Id_servicio_balt = $request->Id_servicio_calitec;
        $hay_agudeza_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $Id_evento_calitec)->get();

        $array_datos_calificacionPclTecnica = DB::select('CALL psrcalificacionpcl(?)', array($Id_asignacion_calitec));
        //Traer Motivo de solicitud,Dominancia actual
        $motivo_solicitud_actual = cndatos_eventos::on('sigmel_gestiones')
        ->select('Id_motivo_solicitud','Nombre_solicitud','Id_dominancia','Nombre_dominancia')
        ->where([
            ['ID_evento', '=', $Id_evento_calitec]
        ])
        ->get();

        //Traer Información apoderado  y Edad del afiliado
        $datos_apoderado_actual = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('Nombre_apoderado','Nro_identificacion_apoderado', 'Edad')
        ->where([
            ['ID_evento', '=', $Id_evento_calitec]
        ])
        ->get();

        $edad_afiliado = $datos_apoderado_actual[0]->Edad;        

        $datos_demos =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_firme')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Cobertura')
        ->leftJoin('sigmel_gestiones.sigmel_lista_califi_decretos as slcd', 'slcd.Id_Decreto', '=', 'side.Decreto_calificacion')        
        ->select('side.ID_Evento', 'side.Id_proceso', 'side.Id_Asignacion', 'side.Origen_firme', 'slp.Nombre_parametro as Origen', 
        'side.Cobertura', 'slps.Nombre_parametro as Coberturas', 'side.Decreto_calificacion', 'slcd.Nombre_decreto')
        ->where([['side.ID_Evento',$Id_evento_calitec]])->get(); 

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

        $array_info_decreto_evento = sigmel_informacion_decreto_eventos::on('sigmel_gestiones')        
        ->where([
            ['ID_Evento', $Id_evento_calitec],
            ['Id_Asignacion', $Id_asignacion_calitec]
        ])
        ->get();
        if (count($array_info_decreto_evento) > 0) {

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

        $array_datos_examenes_interconsultas = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_calitec],
            ['Id_Asignacion',$Id_asignacion_calitec],
            ['Id_proceso',$Id_proceso_cali],
            ['Estado', 'Activo']
        ])->orderBy('F_examen_interconsulta','ASC')
        ->get();

        $array_datos_diagnostico_motcalifi =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.Id_Diagnosticos_motcali', 'side.ID_evento', 'side.CIE10', 'slcd.CIE10 as Codigo', 'side.Nombre_CIE10', 'side.Origen_CIE10', 
        'slp.Nombre_parametro', 'side.Principal', 'side.Deficiencia_motivo_califi_condiciones','slp2.Nombre_parametro as Nombre_parametro_lateralidad')
        ->where([['side.ID_evento',$Id_evento_calitec], ['side.Id_Asignacion',$Id_asignacion_calitec], ['Id_proceso',$Id_proceso_cali], ['side.Estado', '=', 'Activo']])->get(); 
        
        $array_datos_deficiencias_alteraciones =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
        ->select('sidae.Id_Deficiencia', 'sidae.ID_evento', 'sidae.Id_Asignacion', 'sidae.Id_proceso', 'sidae.Id_tabla',
        'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.CFM1', 'sidae.CFM2', 'sidae.FU', 'sidae.CAT', 'sidae.Clase_Final', 
        'sidae.Dx_Principal', 'sidae.MSD', 'sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Dominancia', 'sidae.Deficiencia', 
        'sidae.Total_deficiencia', 'sidae.Estado', 'sidae.Nombre_usuario', 'sidae.F_registro')
        ->where([['sidae.ID_evento',$Id_evento_calitec], ['sidae.Id_Asignacion',$Id_asignacion_calitec], ['sidae.Estado', '=', 'Activo']])
        ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
        ->get();         
        
        $array_agudeza_Auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_calitec],
            ['Id_Asignacion',$Id_asignacion_calitec],
            ['Estado', 'Activo']
        ])
        ->get();

        $array_laboralmente_Activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_calitec],
            ['Id_Asignacion',$Id_asignacion_calitec],
            ['Estado', 'Activo']
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
        ->where([['siroe.ID_evento',$Id_evento_calitec], ['siroe.Id_Asignacion',$Id_asignacion_calitec], ['siroe.Estado', 'Activo']])->get();    
        
        $array_libros_2_3 = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento',$Id_evento_calitec],
            ['Id_Asignacion',$Id_asignacion_calitec],
            ['Estado', 'Activo']
        ])
        ->get();

        if(count($array_datos_calificacionPclTecnica) > 0){
            $Id_servicio_balt = $array_datos_calificacionPclTecnica[0]->Id_Servicio;
        } 
        // Validacion de Deficiencias solo en tabla Auditiva                
        $array_datos_deficiencicas50 = DB::select('CALL psrbalthazaraudpcldef(?,?,?)', array($Id_evento_calitec,$Id_asignacion_calitec,$Id_servicio_balt));
        // Validacion de Deficiencias solo en tabla Visual
        $array_datos_deficiencicas50_1 = DB::select('CALL psrbalthazarvispcldef(?,?,?)', array($Id_evento_calitec,$Id_asignacion_calitec,$Id_servicio_balt));
        // Validacion de Deficiencias solo en tabla Alteraciones del sistema
        $array_datos_deficiencicas50_2 = DB::select('CALL psrbalthazardefpcl(?,?,?)', array($Id_evento_calitec,$Id_asignacion_calitec,$Id_servicio_balt));
        // Validacion de Deficiencias solo en tablas Auditiva y Alteraciones del sistema
        $array_datos_deficiencicas50_3 = DB::select('CALL psrbalthazaraudpcl(?,?,?)', array($Id_evento_calitec,$Id_asignacion_calitec,$Id_servicio_balt));
        // Validacion de Deficiencias solo en tablas Visual y Alteraciones del sistema
        $array_datos_deficiencicas50_4 = DB::select('CALL psrbalthazarvispcl(?,?,?)', array($Id_evento_calitec,$Id_asignacion_calitec,$Id_servicio_balt));
        // Validacion de Deficiencias solo en tablas Auditiva y Visual
        $array_datos_deficiencicas50_5 = DB::select('CALL psrbalthazaraudvispcl(?,?,?)', array($Id_evento_calitec,$Id_asignacion_calitec,$Id_servicio_balt));
        // Validacion de Deficiencias solo en tablas Alteraciones del sistema, Auditiva y Visual 
        $array_datos_deficiencicas50_6 = DB::select('CALL psrbalthazarpcl(?,?,?)', array($Id_evento_calitec,$Id_asignacion_calitec,$Id_servicio_balt));

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
            while(count($deficiencias) > 1) {
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
                       
            while(count($deficiencias) > 1) {
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
            while(count($deficiencias) > 1) {
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
            while(count($deficiencias) > 1) {
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
            while(count($deficiencias) > 1) {
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
            while(count($deficiencias) > 1) {
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
            while(count($deficiencias) > 1) {
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
        ->where('sie.ID_evento', $Id_evento_calitec)
        ->get();

        $array_comite_interdisciplinario = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comite_interdisciplinario_eventos as sicie')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'sicie.Nombre_dest_principal')
        ->select('sicie.ID_evento', 'sicie.Id_proceso', 'sicie.Id_Asignacion', 'sicie.Visar', 'sicie.Profesional_comite', 'sicie.F_visado_comite',
        'sicie.Oficio_pcl', 'sicie.Oficio_incapacidad', 'sicie.Destinatario_principal', 'sicie.Otro_destinatario', 'sicie.Tipo_destinatario', 
        'sicie.Nombre_dest_principal', 'sie.Nombre_entidad', 'sicie.Nombre_destinatario','sicie.Nit_cc', 'sicie.Direccion_destinatario', 
        'sicie.Telefono_destinatario', 'sicie.Email_destinatario', 'sicie.Departamento_destinatario', 'sicie.Ciudad_destinatario', 
        'sicie.Asunto', 'sicie.Cuerpo_comunicado', 'sicie.Copia_afiliado', 'sicie.Copia_empleador', 'sicie.Copia_eps', 'sicie.Copia_afp', 'sicie.Copia_arl', 
        'sicie.Copia_afp_conocimiento', 'sicie.Copia_jr', 'sicie.Cual_jr', 'sicie.Copia_jn', 'sicie.Anexos', 'sicie.Elaboro', 'sicie.Reviso', 'sicie.Firmar', 'sicie.Ciudad', 
        'sicie.F_correspondecia', 'sicie.N_radicado', 'sicie.Nombre_usuario', 'sicie.F_registro')        
        ->where([
            ['ID_evento',$Id_evento_calitec],
            ['Id_Asignacion',$Id_asignacion_calitec]
        ])
        ->get();

        // creación de consecutivo para el comunicado
        $consecutivo = $this->getRadicado('pcl',$Id_evento_calitec);

        $array_dictamen_pericial =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_decreto_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'slte.Id_Evento', '=', 'side.Tipo_evento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'side.Tipo_enfermedad')
        ->select('side.N_radicado','side.Porcentaje_pcl', 'side.Rango_pcl', 'side.Monto_indemnizacion', 'side.Tipo_evento', 'slte.Nombre_evento', 'side.Origen', 'slp.Nombre_parametro', 
        'side.F_evento', 'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.N_siniestro', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slps.Nombre_parametro as TipoEnfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.Nombre_usuario',
        'side.F_registro')
        ->where([['side.ID_evento',$Id_evento_calitec], ['side.Id_Asignacion',$Id_asignacion_calitec]])->get();        

        $array_comunicados_correspondencia = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where([['ID_evento',$Id_evento_calitec], ['Id_Asignacion',$Id_asignacion_calitec], ['T_documento','N/A'], ['Modulo_creacion','calificacionTecnicaPCL']])->get();  
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
            if($comunicado->Id_Comunicado){
                $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_calitec,$Id_asignacion_calitec,$comunicado->Id_Comunicado);
            }
            
        } 
        $array_comunicados_comite_inter = DB::table('sigmel_gestiones.sigmel_informacion_comite_interdisciplinario_eventos as sicie')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_comunicado_eventos as sice', function ($join) {
            $join->on('sicie.ID_evento', '=', 'sice.ID_evento')
                 ->on('sicie.N_radicado', '=', 'sice.N_radicado');
        })
        ->where('sicie.ID_evento', $Id_evento_calitec)
        ->where('sicie.Id_Asignacion', $Id_asignacion_calitec)
        ->select('sicie.*', 'sice.Id_Comunicado', 'sice.Reemplazado', 'sice.Nombre_documento', 'sice.N_siniestro')
        ->get();

        foreach ($array_comunicados_comite_inter as $comunicado_inter) {
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

            if($comunicado_inter->Id_Comunicado){
                $comunicado['Estado_correspondencia'] = BandejaNotifiController::estado_Correspondencia($Id_evento_calitec,$Id_asignacion_calitec,$comunicado_inter->Id_Comunicado);
            }
        } 
        /* Traer datos de la AFP de Conocimiento */
        $info_afp_conocimiento = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'siae.Id_afp_entidad_conocimiento', '=', 'sie.Id_Entidad')
        ->select('siae.Entidad_conocimiento')
        ->where([['siae.ID_evento', $Id_evento_calitec]])
        ->get();

        //Traer el N_siniestro del evento
        $N_siniestro_evento = $this->globalService->retornarNumeroSiniestro($Id_evento_calitec);

        //Traer la modalidad de calificación
        $Modalidad_calificacion = $this->globalService->retornarModalidadCalificacionPCL($Id_evento_calitec,$Id_asignacion_calitec);

        return view('coordinador.calificacionTecnicaPCL', compact('user','array_datos_calificacionPclTecnica','motivo_solicitud_actual','datos_apoderado_actual', 
        'hay_agudeza_visual','datos_demos','array_info_decreto_evento','array_datos_relacion_documentos','array_datos_examenes_interconsultas','numero_consecutivo',
        'array_datos_diagnostico_motcalifi', 'array_agudeza_Auditiva', 'array_datos_deficiencias_alteraciones', 'array_laboralmente_Activo', 'array_rol_ocupacional', 
        'array_libros_2_3', 'deficiencias', 'TotalDeficiencia50', 'array_tipo_fecha_evento', 'array_comite_interdisciplinario', 'consecutivo', 'array_dictamen_pericial', 
        'array_comunicados_correspondencia', 'array_comunicados_comite_inter', 'info_afp_conocimiento','N_siniestro_evento', 'edad_afiliado', 'Modalidad_calificacion'));
    }

    public function cargueListadoSelectoresCalifcacionTecnicaPcl(Request $request){
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

        /* TRAER LISTADO DE DOMINANCIAS */
        if ($parametro == "lista_dominancia") {
            
            $listado_dominancia = sigmel_lista_dominancias::on('sigmel_gestiones')
                ->select('Id_Dominancia', 'Nombre_dominancia')
                ->where('Estado', 'activo')
                ->get();
            
            $info_lista_dominancia = json_decode(json_encode($listado_dominancia, true));
            return response()->json($info_lista_dominancia);
        }
        

    }

    //4 Formularios iniciales

    public function guardarDecretoDicRelaDocFund(Request $request){
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
        $modalidad_calificacion = $request->modalidad_calificacion;

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
                        'Estado_decreto' =>  'Abierto',
                        'Modalidad_calificacion' => $modalidad_calificacion,
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
                    'Modalidad_calificacion' => $modalidad_calificacion,
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];

                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where('ID_Evento', $id_Evento_decreto)->update($datos_info_Nodecreto_eventos);

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
                    'Relacion_documentos' => $total_relacion_documentos,
                    'Otros_relacion_doc' => $descripcion_otros,
                    'Descripcion_enfermedad_actual' => $descripcion_enfermedad,
                    'Estado_decreto' =>  'Abierto',
                    'Modalidad_calificacion' => $modalidad_calificacion,
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];
    
                $dato_info_pericial_eventos = [
                    'Id_motivo_solicitud' => $motivo_solicitud,
                ];

                $dato_info_dominancia_afiliado = [
                    'Id_dominancia' => $dominancia
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')->insert($datos_info_decreto_eventos);
                sleep(2);
                sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
                ->where([                    
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_pericial_eventos);

                sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
                ->where([
                    ['Id_Afiliado', $id_afiliado],
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_dominancia_afiliado);
                
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
                    'Relacion_documentos' => $total_relacion_documentos,
                    'Otros_relacion_doc' => $descripcion_otros,
                    'Descripcion_enfermedad_actual' => $descripcion_enfermedad,
                    'Modalidad_calificacion' => $modalidad_calificacion,
                    'Nombre_usuario' => $usuario,
                    'F_registro' => $date,
                ];
    
                $dato_info_pericial_eventos = [
                    'Id_motivo_solicitud' => $motivo_solicitud,
                ];

                $dato_info_dominancia_afiliado = [
                    'Id_dominancia' => $dominancia
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where('ID_Evento', $id_Evento_decreto)->update($datos_info_decreto_eventos);
                sleep(2);
                sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_pericial_eventos);
        
                sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
                ->where([
                    ['Id_Afiliado', $id_afiliado],
                    ['ID_evento', $id_Evento_decreto]
                ])->update($dato_info_dominancia_afiliado);

                $mensajes = array(
                    "parametro" => 'update_decreto_parte',
                    "mensaje2" => 'Actualizado satisfactoriamente.'
                ); 
    
                return json_decode(json_encode($mensajes, true));
    
            }
        }

    }
    // Examenes Interconsultas
    public function guardarExamenesInterconsulta(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Estado_Recalificacion = $request->Estado_Recalificacion;
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

            $subarray_datos[] = $Estado_Recalificacion;
            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_examenes_interconsultas_eventos
        $array_tabla_examen_interconsulta = ['ID_evento','Id_Asignacion','Id_proceso',
        'F_examen_interconsulta','Nombre_examen_interconsulta','Descripcion_resultado', 'Estado_Recalificacion',
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

    public function eliminarExamenInterconsulta(Request $request){
        $id_fila_examen = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo',
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')->where('Id_Examenes_interconsultas', $id_fila_examen)
        ->update($fila_actualizar);

        $total_registros_examen = sigmel_informacion_examenes_interconsultas_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_examen_eliminada',
            'total_registros' => $total_registros_examen,
            "mensaje" => 'Exámen e Interconsulta eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }
    // Diagnosticos CIE10
    public function guardarDiagnosticoMotivoCalificacion(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Estado_Recalificacion = $request->Estado_Recalificacion;
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

            $subarray_datos[] = $Estado_Recalificacion;
            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_diagnosticos_eventos
        $array_tabla_diagnosticos_motivo_calificacion = ['ID_evento','Id_Asignacion','Id_proceso',
        'CIE10','Nombre_CIE10','Lateralidad_CIE10','Origen_CIE10', 'Principal', 'Deficiencia_motivo_califi_condiciones', 'Estado_Recalificacion',
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

    public function eliminarDiagnosticoMotivoCalificacion(Request $request){
        $id_fila_diagnostico = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo',
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')->where('Id_Diagnosticos_motcali', $id_fila_diagnostico)
        ->update($fila_actualizar);

        $total_registros_diagnostico = sigmel_informacion_diagnosticos_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_diagnostico_eliminada',
            'total_registros' => $total_registros_diagnostico,
            "mensaje" => 'Diagnóstico motivo de calificación eliminado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }    

    public function actualizarDxPrincipalDiagnostico(Request $request){
        
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
                ['Estado', 'Activo']
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
                ['Estado', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalDiagnostico_eliminado',
                "mensaje" => 'Dx Principal Diagnósticos motivo de calificación eliminado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Agudeza Auditiva

    public function guardarDeficienciasAgudezaAuditivas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $ID_evento = $request->ID_evento;
        $Id_Asignacion = $request->Id_Asignacion;
        $Id_proceso = $request->Id_proceso;
        $oido_izquierdo = $request->oido_izquierdo;
        $oido_derecho = $request->oido_derecho;
        $Agudeza_Auditivas = $request->Agudeza_Auditivas;
        $Estado_Recalificacion = $request->Estado_Recalificacion;
        
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
            'Estado_Recalificacion' => $Estado_Recalificacion,
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

    public function eliminarAgudezaAuditiva(Request $request){

        $id_fila_agudeza_auditiva = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo',
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')->where('Id_Agudeza_auditiva', $id_fila_agudeza_auditiva)
        ->update($fila_actualizar);

        $total_registros_agudeza_auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_agudeza_auditiva_eliminada',
            'total_registros' => $total_registros_agudeza_auditiva,
            "mensaje" => 'Agudeza auditiva eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }

    public function actualizarDxPrincipalAgudezaAuditiva(Request $request){
        
        $dxPrincipal = $request->dxPrincipal;
        $Id_evento = $request->Id_evento;
        $banderaDxPrincipal = $request->banderaDxPrincipal;

        if ($banderaDxPrincipal == 'SiDxPrincipal') {
            $fila_actulizar = [
                'Dx_Principal' => 'Si'
            ];
    
            sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_evento],
                ['Estado', 'Activo']
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
                ['Estado', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalagudeza_auditiva_agregado',
                "mensaje" => 'Dx Principal Agudeza auditiva eliminado satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    public function actualizarDeficienciasAgudezaAuditivas(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $ID_evento_editar = $request->ID_evento_editar;
        $Id_Asignacion_editar = $request->Id_Asignacion_editar;
        $Id_proceso_editar = $request->Id_proceso_editar;
        $oido_izquierdo_editar = $request->oido_izquierdo_editar;
        $oido_derecho_editar = $request->oido_derecho_editar;
        $Agudeza_Auditivas_editar = $request->Agudeza_Auditivas_editar;
        
        foreach ($Agudeza_Auditivas_editar as $auditiva_editar) {
            $auditiva_editar;            
            foreach ($auditiva_editar as $columna => $deficiencia_editar) {
                $$columna = $deficiencia_editar;
            }
        }
                    
        $datos_agudeza_auditiva_editar = [
            'ID_evento' => $ID_evento_editar,
            'Id_Asignacion' => $Id_Asignacion_editar,
            'Id_proceso' => $Id_proceso_editar,
            'Oido_Izquierdo' => $oido_izquierdo_editar,
            'Oido_Derecho' => $oido_derecho_editar,
            'Deficiencia_monoaural_izquierda' => $columnaEditar0,
            'Deficiencia_monoaural_derecha' => $columnaEditar1,
            'Deficiencia_binaural' => $columnaEditar2,
            'Adicion_tinnitus' => $columnaEditar3,
            'Deficiencia' => $columnaEditar4,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date,
        ];
        
        sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([
            ['ID_evento' , $ID_evento_editar],
            ['Estado' , 'Activo']
        ])
        ->unpdate($datos_agudeza_auditiva_editar);
        
        $mensajes = array(
            "parametro" => 'insertar_agudeza_auditiva_editar',
            "mensaje" => 'Agudeza auditiva actualizada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    // Agudeza visual

    public function ConsultaCampimetriaXFila(Request $request){
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
            $listado_campimetria_ojo_izq = sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')
            ->select('InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5', 'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10')
            ->where('Id_agudeza', $request->Id_agudeza)
            ->get();
            $info = json_decode(json_encode($listado_campimetria_ojo_izq, true));
        };

        if ($parametro == "edicion_ojo_der") {
            $listado_campimetria_ojo_der = sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')
            ->select('InfoFila1', 'InfoFila2', 'InfoFila3', 'InfoFila4', 'InfoFila5', 'InfoFila6', 'InfoFila7', 'InfoFila8', 'InfoFila9', 'InfoFila10')
            ->where('Id_agudeza', $request->Id_agudeza)
            ->get();
            $info = json_decode(json_encode($listado_campimetria_ojo_der, true));
        };


        return response()->json($info);

    }

    public function guardarAgudezaVisual(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        /* Inserción de información del formulario */
        sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')->insert($request->info_formulario);

        // Extraemos el id insertado para almacenar los datos de la campimetria
        $id_agudeza = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')->select('Id_agudeza')->latest('Id_agudeza')->first();

        /* Envío de la información de la campimetría para ojo izquierdo */
        $grilla_ojo_izq = $request->grilla_ojo_izq;
        foreach ($grilla_ojo_izq as $key => $insertar_info_grid_ojo_izq) {
            $insertar_info_grid_ojo_izq = array("Id_agudeza" => $id_agudeza['Id_agudeza']) + $insertar_info_grid_ojo_izq;
            sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_izq);
        }

        /* Envío de la información de la campimetría para ojo derecho */
        $grilla_ojo_der = $request->grilla_ojo_der;
        foreach ($grilla_ojo_der as $key => $insertar_info_grid_ojo_der) {
            $insertar_info_grid_ojo_der = array("Id_agudeza" => $id_agudeza['Id_agudeza']) + $insertar_info_grid_ojo_der;
            sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_der);
        }

        $mensajes = array(
            "parametro" => 'guardo',
            "mensaje" => 'Información de Agudeza visual agregada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function infoAgudezaVisual(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $informacion_agudeza_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where("ID_evento", $request->ID_evento)
        ->get();

        $info_agudeza = json_decode(json_encode($informacion_agudeza_visual, true));
        return response()->json($info_agudeza);

    }

    public function actualizarAgudezaVisual (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        /* Actualización de información del formulario */
        sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_agudeza', '=', $request->Id_agudeza],
            ['ID_evento', '=', $request->ID_evento]
        ])
        ->update($request->info_formulario);


        /* Envío de la información de la campimetría para ojo izquierdo  */
        sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $request->Id_agudeza)->delete();

        $grilla_ojo_izq = $request->grilla_ojo_izq;
        foreach ($grilla_ojo_izq as $key => $insertar_info_grid_ojo_izq) {
            $insertar_info_grid_ojo_izq = array("Id_agudeza" => $request->Id_agudeza) + $insertar_info_grid_ojo_izq;
            sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_izq);
        }

        /* Envío de la información de la campimetría para ojo derecho */
        sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $request->Id_agudeza)->delete();
        $grilla_ojo_der = $request->grilla_ojo_der;
        foreach ($grilla_ojo_der as $key => $insertar_info_grid_ojo_der) {
            $insertar_info_grid_ojo_der = array("Id_agudeza" => $request->Id_agudeza) + $insertar_info_grid_ojo_der;
            sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')->insert($insertar_info_grid_ojo_der);
        }

        $mensajes = array(
            "parametro" => 'actualizo',
            "mensaje" => 'Información de Agudeza visual actualizada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
        
    }
    
    public function eliminarAgudezaVisual(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $usuario = Auth::user()->name;

        $id_agudeza = $request->Id_agudeza;
        $id_evento = $request->ID_evento;

        /* Borrado de la información general */
        sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where([
            ['Id_agudeza', '=', $id_agudeza],
            ['ID_evento', '=', $id_evento]
        ])->delete();

        /* Borrado de la información de la campimetría para ojo izquierdo  */
        sigmel_info_campimetria_ojo_izq_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $id_agudeza)->delete();

        /* Borrado de la información de la campimetría para ojo derecho */
        sigmel_info_campimetria_ojo_der_eventos::on('sigmel_gestiones')
        ->where('Id_agudeza', $id_agudeza)->delete();

        $mensajes = array(
            "parametro" => 'borro',
            "mensaje" => 'Información de Agudeza visual eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));
    }

    public function actualizarDxPrincipalAgudezaVisual(Request $request){
        
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
    }

    /* TODO LO REFERENTE DEFICIENCIA POR ALTERACIONES DE LOS SISTEMAS GENERALES */
    public function ListadoSelectoresDefiAlteraciones(Request $request){
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

    public function consultaValorDeficiencia(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $string_deficiencia = sigmel_lista_clases_decretos::on('sigmel_gestiones')
        ->select($request->columna)->where('Id_tabla', $request->Id_tabla)->get();

        $info_string_deficiencia = json_decode(json_encode($string_deficiencia, true));
        return response()->json($info_string_deficiencia);
        
    }

    public function GuardarDeficienciaAlteraciones(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $Estado_Recalificacion = $request->Estado_Recalificacion;
        /* CAPTURA DE DATOS DE LA DEFICIENCIA */
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
        
                    $subarray[] = $Estado_Recalificacion;
                    $subarray[] = $nombre_usuario;
                    $subarray[] = $date;
        
                    array_push($array_datos_organizados, $subarray);
                // }
        
                // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
                
                $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU',	'CAT', 
                'MSD', 'Dominancia', 'Deficiencia', 'Total_deficiencia', 'Estado_Recalificacion', 'Nombre_usuario','F_registro'];
                
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
        
                    $subarray[] = $Estado_Recalificacion;
                    $subarray[] = $nombre_usuario;
                    $subarray[] = $date;
        
                    array_push($array_datos_organizados, $subarray);
                // }
        
                // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
                
                $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'FP', 'CFM1', 'CFM2', 'FU',	'CAT', 'Clase_Final', 
                'MSD', 'Dominancia', 'Deficiencia', 'Total_deficiencia', 'Estado_Recalificacion', 'Nombre_usuario','F_registro'];
                
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

    public function eliminarDeficienciaAteraciones(Request $request){
        $id_fila_deficiencia_alteraciones = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo',
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_alteraciones)
        ->update($fila_actualizar);

        $total_registros_diagnostico = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_deficiencia_alteracion_eliminada',
            'total_registros' => $total_registros_diagnostico,
            "mensaje" => 'Deficiencia por alteraciones eliminado satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));        
    }

    public function actualizarDxPrincipalDeficienciasAlteraciones(Request $request){
        
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
                ['Estado', 'Activo']
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
                ['Estado', 'Activo']
            ])->update($fila_actulizar);
    
            $mensajes = array(
                "parametro" => 'fila_dxPrincipalDeficienciaAlteracion_eliminado',
                "mensaje" => 'Dx Principal Deficiencias alteraciones eliminada satisfactoriamente.'
            );
    
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Laboralmente Activo Rol Ocupacional

    public function guardarLaboralmenteActivo(Request $request){
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

    public function guardarRolOcupacional(Request $request){
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
            ->where('ID_evento', $Id_EventoDecreto)->update($datos_rolOcupacional);
            sleep(2);
            
            $mensajes = array(
                "parametro" => 'actualizar_rol_ocupacional',
                "mensaje2" => 'Rol ocupacional actualizado satisfactoriamente.'
            ); 
            return json_decode(json_encode($mensajes, true));            
        }
    }

    // Libro 2 20% y libro 3 30%

    public function guardarLibro2_3(Request $request){
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
            ->where('ID_evento', $Id_EventoDecreto)->update($datos_Libros2_3);
            sleep(2);            
            $mensajes = array(
                "parametro" => 'actualizar_libros_2_3',
                "mensaje2" => 'Libros II y III actualizados satisfactoriamente.'
            ); 
            return json_decode(json_encode($mensajes, true));   
        }

    }
    // Comite Interdisciplinario

    public function guardarcomiteinterdisciplinario(Request $request){
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

    public function guardarcorrespondencia(Request $request){
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
        if ($oficiopcl == '') {
            $oficiopcl = 'No';
        }
        if($oficioinca == ''){
            $oficioinca = 'No';
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
        $afiliado = $request->afiliado;
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

        if (!empty($afiliado)) {
            $variables_llenas[] = $afiliado;
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

        $agregar_copias_comu = implode(',', $variables_llenas);
        
        $anexos = $request->anexos;
        $elaboro = $request->elaboro;
        $reviso = $request->reviso;
        $firmar = $request->firmar;
        $ciudad = $request->ciudad;
        $f_correspondencia = $request->f_correspondencia;
        $radicado = $this->disponible($request->radicado,$Id_EventoDecreto)->getRadicado('pcl',$Id_EventoDecreto);
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

            //Se asignan los IDs de destinatario por cada posible destinatario
            $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario();

            $datos_correspondencia = [
                'Oficio_pcl' => $oficiopcl,
                'Oficio_incapacidad' => $oficioinca,
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
                'Modulo_creacion' => 'calificacionTecnicaPCL',
                'N_siniestro' => $N_siniestro,
                'Reemplazado' => 0,
                'Otro_destinatario' => $request->nombre_destinatariopri ? 1 : 0,
                'Id_Destinatarios' => $ids_destinatarios,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            $Id_Comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_comunicado_eventos);
    
            $mensajes = array(
                "parametro" => 'insertar_correspondencia',
                "mensaje" => 'Correspondencia guardada satisfactoriamente.',
                "Id_Comunicado" => $Id_Comunicado,
                "Bandera_boton_guardar_oficio" => 'boton_oficio'
            );
    
            return json_decode(json_encode($mensajes, true));
            
        } 
        elseif($bandera_correspondecia_guardar_actualizar == 'Actualizar') {
            $datos_correspondencia = [
                'Oficio_pcl' => $oficiopcl,
                'Oficio_incapacidad' => $oficioinca,
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
                //'N_radicado' => $radicado,
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
                //'N_radicado' => $radicado,
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
                'Modulo_creacion' => 'calificacionTecnicaPCL',
                'N_siniestro' => $N_siniestro,
                'Reemplazado' => 0,
                'Otro_destinatario' => $request->nombre_destinatariopri ? 1 : 0,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];  
                
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->where([
                ['ID_evento', $Id_EventoDecreto],
                ['Id_Asignacion',$Id_Asignacion_Dcreto],
                ['Id_proceso', $Id_ProcesoDecreto],
                ['N_radicado',$request->radicado]
            ])
            ->update($datos_info_comunicado_eventos);

            $capturar_Id_Comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->select('Id_Comunicado')
            ->where([
                ['ID_evento', $Id_EventoDecreto],
                ['Id_Asignacion',$Id_Asignacion_Dcreto],
                ['Id_proceso', $Id_ProcesoDecreto],
                ['N_radicado',$request->radicado]
            ])
            ->get();

            $Id_Comunicado = $capturar_Id_Comunicado[0]->Id_Comunicado;
            
            $mensajes = array(
                "parametro" => 'actualizar_correspondencia',
                "mensaje" => 'Correspondencia actualizada satisfactoriamente.',
                "Id_Comunicado" => $Id_Comunicado,
                "Bandera_boton_guardar_oficio" => 'boton_oficio'
            );
    
            return json_decode(json_encode($mensajes, true));
        }
    }

    // Dictame Pericial

    public function guardardictamenPericial(Request $request){

        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $nombre_usuario = Auth::user()->name;
        $date = date("Y-m-d", $time);
        $bandera_dictamen_pericial = $request->bandera_dictamen_pericial;
        //if validacion para actualizar siempre el pcl, rango, monto y else actualizacion normal desde el form del dictamen pericial
        if ($bandera_dictamen_pericial == 'bandera_Pcl_rango_monto') {
            $Decreto_pericial = $request->Decreto_pericial;
            $Id_EventoDecreto = $request->Id_EventoDecreto;
            $Id_ProcesoDecreto = $request->Id_ProcesoDecreto;
            $Id_Asignacion_Dcreto = $request->Id_Asignacion_Dcreto;
            $porcentaje_pcl = $request->porcentaje_pcl;
            $rango_pcl = $request->rango_pcl;
            $monto_inde = $request->monto_inde;
            $sumas_combinada = $request->sumas_combinada;
            $Totales_Deficiencia50 = $request->Totales_Deficiencia50;
        } else {
            
            $Decreto_pericial = $request->Decreto_pericial;
            $Id_EventoDecreto = $request->Id_EventoDecreto;
            $Id_ProcesoDecreto = $request->Id_ProcesoDecreto;
            $Id_Asignacion_Dcreto = $request->Id_Asignacion_Dcreto;
            $suma_combinada = $request->suma_combinada;
            $Total_Deficiencia50 = $request->Total_Deficiencia50;
            $total_discapacidades = $request->total_discapacidades;
            $total_minusvalia = $request->total_minusvalia;
            $total_porcentajePcl = $Total_Deficiencia50 + $total_discapacidades + $total_minusvalia;
            $radicado_dictamen = $this->disponible($request->radicado_dictamen,$Id_EventoDecreto)->getRadicado('pcl',$Id_EventoDecreto);        
    
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
    
            // eL número de identificacion siempre será el del afiliado.
            $array_nro_ident_afi = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
            ->select('Nro_identificacion')
            ->where([['ID_evento', $Id_EventoDecreto]])
            ->get();
    
            if (count($array_nro_ident_afi) > 0) {
                $nro_identificacion = $array_nro_ident_afi[0]->Nro_identificacion;
            }else{
                $nro_identificacion = 'N/A';
            }
            
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $request->Id_EventoDecreto)->update(["Tipo_evento" => $request->tipo_evento]);
        }        

        /*
            Se trae el id del servicio en base a las siguientes solicitudes:
            1. DML PCL 1507 / DML 1507 CERO / DML PCL 917 y Oficio Incapacidad y Oficio PCL: 
                Cuando el servicio sea Calificación técnica o Recalificación NO marcar a la AFP como copia, revertir marcación actual. 
                Se marcará como destinatario principal al Afiliado y a la EPS y ARL como copia.
            2. DML PCL 1507 / DML 1507 CERO / DML PCL 917: Adicionar las siguientes validaciones para la marcación automática de las copias:
        */
        $info_afp_conocimiento = $this->globalService->retornarcuentaConAfpConocimiento($Id_EventoDecreto);
        if(!empty($info_afp_conocimiento[0]->Entidad_conocimiento) && $info_afp_conocimiento[0]->Entidad_conocimiento == "Si"){
            $agregar_copias_dml = "EPS, ARL, AFP_Conocimiento";
        }
        else{
            $agregar_copias_dml = "EPS, ARL";
        }
        $Destinatario = 'Afiliado';

        if ($bandera_dictamen_pericial == 'Guardar') { 
            //Se asignan los IDs de destinatario por cada posible destinatario
            $ids_destinatarios = $this->globalService->asignacionConsecutivoIdDestinatario();
                       
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

                //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
                $dato_actualizar_n_siniestro = [
                    'N_siniestro' => $n_siniestro
                ];
                sigmel_informacion_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$Id_EventoDecreto]])
                ->update($dato_actualizar_n_siniestro);

                sleep(2);
    
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
                    'N_identificacion' => $nro_identificacion,
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
                    'Modulo_creacion' => 'calificacionTecnicaPCL',
                    'Reemplazado' => 0,
                    'N_siniestro' => $n_siniestro,
                    'Id_Destinatarios' => $ids_destinatarios,
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
        
                $Id_Comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_comunicado_eventos);
    
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

                //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
                $dato_actualizar_n_siniestro = [
                    'N_siniestro' => $n_siniestro
                ];
                sigmel_informacion_eventos::on('sigmel_gestiones')
                ->where([['ID_evento',$Id_EventoDecreto]])
                ->update($dato_actualizar_n_siniestro);

                sleep(2);

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
                    'N_identificacion' => $nro_identificacion,
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
                    'Modulo_creacion' => 'calificacionTecnicaPCL',
                    'Reemplazado' => 0,
                    'N_siniestro' => $n_siniestro,
                    'Id_Destinatarios' => $ids_destinatarios,
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
        
                $Id_Comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')->insertGetId($datos_info_comunicado_eventos);
               
            }    
            $mensajes = array(
                "parametro" => 'insertar_dictamen_pericial',
                "mensaje" => 'Concepto final del dictamen pericial guardado satisfactoriamente.',
                'Id_Comunicado' => $Id_Comunicado,
                'radicado_dictamen' => $radicado_dictamen,
                'Bandera_boton_guardar_dictamen' => 'boton_dictamen',                               
            );

            return json_decode(json_encode($mensajes, true));

        } elseif ($bandera_dictamen_pericial == 'Actualizar') {
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
                   // 'N_radicado'=> $radicado_dictamen,
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
                    //'N_radicado'=> $radicado_dictamen,
                    'Estado_decreto' => 'Cerrado',
                    'Nombre_usuario' => $nombre_usuario,
                    'F_registro' => $date,
                ];
        
                sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
                ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_dictamenPericial); 
                
            }    
            //Actualización del N_siniestro del evento, el cual pidieron fuera "Global"
            $dato_actualizar_n_siniestro = [
                'N_siniestro' => $n_siniestro
            ];
            sigmel_informacion_eventos::on('sigmel_gestiones')
            ->where([['ID_evento',$Id_EventoDecreto]])
            ->update($dato_actualizar_n_siniestro);

            sleep(2);
            

            $comunicado_reemplazado = [
                'Destinatario' => $Destinatario,
                'Agregar_copia' => $agregar_copias_dml,
                'Reemplazado' => 0,
                'N_siniestro' => $n_siniestro,
            ];
            sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento',$Id_EventoDecreto],
                    ['Id_Asignacion',$Id_Asignacion_Dcreto],
                    ['N_radicado',$request->radicado_dictamen]
                    ])
            ->update($comunicado_reemplazado);
            
            $capturar_Id_Comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
            ->select('Id_Comunicado')
            ->where([
                    ['ID_evento',$Id_EventoDecreto],
                    ['Id_Asignacion',$Id_Asignacion_Dcreto],
                    ['N_radicado',$request->radicado_dictamen]
                    ])
            ->get();
            $Id_Comunicado = $capturar_Id_Comunicado[0]->Id_Comunicado;
            
            $mensajes = array(
                "parametro" => 'insertar_dictamen_pericial',
                "mensaje" => 'Concepto final del dictamen pericial actualizado satisfactoriamente.',
                'Id_Comunicado' => $Id_Comunicado,
                'radicado_dictamen' => $radicado_dictamen,
                'Bandera_boton_guardar_dictamen' => 'boton_dictamen',                               

            );

            return json_decode(json_encode($mensajes, true));
            
        } elseif ($bandera_dictamen_pericial == 'bandera_Pcl_rango_monto'){

            $datos_dictamenPericial =[  
                'Suma_combinada' => $sumas_combinada,
                'Total_Deficiencia50' => $Totales_Deficiencia50,
                'Porcentaje_pcl' => $porcentaje_pcl,
                'Rango_pcl' => $rango_pcl,
                'Monto_indemnizacion' => $monto_inde,                
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_decreto_eventos::on('sigmel_gestiones')
            ->where([['ID_evento', $Id_EventoDecreto], ['Id_Asignacion', $Id_Asignacion_Dcreto]])->update($datos_dictamenPericial); 
            
            // return 'Se actualizo porcentaje pcl: '.$porcentaje_pcl.', rango: '.$rango_pcl.' y monto: '.$monto_inde.' de la Asignacion: '.$Id_Asignacion_Dcreto;
        }

    }

    // Deficiencias Decreto Cero

    public function guardarDeficieciasDecretoCero(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;   
        $Estado_Recalificacion = $request->Estado_Recalificacion;
        /* CAPTURA DE DATOS DE LA DEFICIENCIA */
        $array_datos = $request->datos_finales_deficiciencias_decreto_cero;
        //print_r($array_datos);

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];

        foreach ($array_datos as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);

            $subarray_datos[] = $Estado_Recalificacion;
            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Id_tabla', 'Total_deficiencia', 
        'Estado_Recalificacion', 'Nombre_usuario','F_registro'];
        
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

    public function eliminarDeficieciasDecretoCero(Request $request){
        $id_fila_deficiencia_cero = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo',
            'Estado_Recalificacion' => 'Inactivo'
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_cero)
        ->update($fila_actualizar);

        $total_registros_deficiencias_cero = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_deficiencia_cero_eliminada',
            'total_registros' => $total_registros_deficiencias_cero,
            "mensaje" => 'Deficiencia eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }  

    // Deficiencias Decreto Tres

    public function guardarDeficieciasDecretoTres(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;   
        $Estado_Recalificacion = $request->Estado_Recalificacion;
        /* CAPTURA DE DATOS DE LA DEFICIENCIA */
        $array_datos = $request->datos_finales_deficiciencias_decreto_tres;
        //print_r($array_datos);

        // Iteración para extraer los datos de la tabla y adicionar los datos de Id evento, Id asignacion y Id proceso
        $array_datos_organizados = [];

        foreach ($array_datos as $subarray_datos) {

            array_unshift($subarray_datos, $request->Id_proceso);
            array_unshift($subarray_datos, $request->Id_Asignacion);
            array_unshift($subarray_datos, $request->Id_evento);

            $subarray_datos[] = $Estado_Recalificacion;
            $subarray_datos[] = $nombre_usuario;
            $subarray_datos[] = $date;

            array_push($array_datos_organizados, $subarray_datos);
        }

        // Creación de array con los campos de la tabla: sigmel_informacion_deficiencias_alteraciones_eventos
        
        $array_keys_tabla = ['ID_evento','Id_Asignacion','Id_proceso', 'Tabla1999', 'Titulo_tabla1999', 'Total_deficiencia', 
        'Estado_Recalificacion', 'Nombre_usuario','F_registro'];
        
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

    public function eliminarDeficieciasDecretoTres(Request $request){
        $id_fila_deficiencia_cero = $request->fila;
        $fila_actualizar = [
            'Estado' => 'Inactivo',
            'Estado_Recalificacion' => 'Inactivo',
        ];

        sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')->where('Id_Deficiencia', $id_fila_deficiencia_cero)
        ->update($fila_actualizar);

        $total_registros_deficiencias_tres = sigmel_informacion_deficiencias_alteraciones_eventos::on('sigmel_gestiones')
        ->where([['ID_evento', $request->Id_evento],['Estado', 'Activo']])->count();

        $mensajes = array(
            "parametro" => 'fila_deficiencia_tres_eliminada',
            'total_registros' => $total_registros_deficiencias_tres,
            "mensaje" => 'Deficiencia eliminada satisfactoriamente.'
        );

        return json_decode(json_encode($mensajes, true));

    }  

    // Generar PDF del Dictamen de PCL 1507

    public function generarPdfDictamenPcl(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        if ($request->Bandera_boton_guardar_dictamen == 'boton_dictamen') {
            $ID_Evento_comuni = $request->ID_Evento_comuni;
            $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
            $Id_Proceso_comuni = $request->Id_Proceso_comuni;
            $Radicado_comuni = $request->Radicado_comuni;
            $Id_Comunicado = $request->Id_Comunicado;
            $N_siniestro = $request->N_siniestro;           
        } 
        else {
            
            $ID_Evento_comuni = $request->ID_Evento_comuni;
            $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
            $Id_Proceso_comuni = $request->Id_Proceso_comuni;
            $Radicado_comuni = $request->Radicado_comuni;
            $Id_Comunicado = $request->Id_Comunicado;
            $N_siniestro = $request->N_siniestro;
        }
        
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
        'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slpa.Nombre_parametro as Nombre_enfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.N_radicado', 
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro')
        ->where([['side.ID_Evento',$ID_Evento_comuni], ['side.Id_Asignacion',$Id_Asignacion_comuni]])->get();        
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
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmdafi','sldmdafi.Id_departamento','=','siae.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmdbenefi','sldmdbenefi.Id_departamento','=','siae.Id_departamento_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_municipios', '=', 'siae.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldmu', 'sldmu.Id_municipios', '=', 'siae.Id_municipio_benefi')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sie', 'sie.Id_Entidad', '=', 'siae.Id_eps')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sien', 'sien.Id_Entidad', '=', 'siae.Id_afp')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_entidades as sient', 'sient.Id_Entidad', '=', 'siae.Id_arl')
        ->select('siae.ID_evento', 'siae.Nombre_afiliado', 'siae.Tipo_documento', 'slp.Nombre_parametro as T_documento', 'siae.Nro_identificacion', 
        'siae.F_nacimiento', 'siae.Edad', 'siae.Genero', 'siae.Email', 'siae.Telefono_contacto', 'siae.Estado_civil', 
        'slpar.Nombre_parametro as Estado_civi', 'siae.Nivel_escolar', 'slpa.Nombre_parametro as Escolaridad', 
        'siae.Apoderado', 'siae.Nombre_apoderado', 'siae.Nro_identificacion_apoderado', 'siae.Id_dominancia', 'siae.Direccion', 
        'siae.Id_departamento', 'siae.Id_municipio', 'siae.Ocupacion', 'siae.Tipo_afiliado', 
        'siae.Ibc', 'siae.Id_eps', 'sie.Nombre_entidad as Entidad_eps', 'siae.Id_afp', 'sien.Nombre_entidad as Entidad_afp', 'siae.Id_arl', 
        'sient.Nombre_entidad as Entidad_arl', 'siae.Activo', 'siae.Medio_notificacion', 'siae.Nombre_afiliado_benefi', 
        'siae.Tipo_documento_benefi', 'siae.Nro_identificacion_benefi', 'siae.Direccion_benefi', 'siae.Id_departamento_benefi','sldmdafi.Nombre_departamento as Nombre_departamento_afi',
        'sldm.Nombre_municipio as Nombre_municipio','sldmdbenefi.Nombre_departamento as Nombre_departamento_benefi','sldmu.Nombre_municipio as Nombre_municipio_benefi','siae.Id_municipio_benefi','siae.Nombre_usuario', 
        'siae.F_registro','F_actualizacion')
        ->where([['ID_Evento',$ID_Evento_comuni]])->get();  
                
        $Tipo_afiliado = $array_datos_info_afiliado[0]->Tipo_afiliado;
        $Ocupacion_afiliado = $array_datos_info_afiliado[0]->Ocupacion;
        
        // Debido a un error en el modulo nuevo, guarda beneficiario donde va el afiliado y afiliado donde va el beneficiario, por eso ya no es relevante el tipo de afiliado

        // if ($Tipo_afiliado !== 27 ) {
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $NroIden_afiliado_dic = $array_datos_info_afiliado[0]->Nro_identificacion;
            $Telefono_afiliado_dic = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Email_afiliado_dic = $array_datos_info_afiliado[0]->Email;
            $Direccion_afiliado_dic = $array_datos_info_afiliado[0]->Direccion;
            $Ciudad_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_municipio;
        // }else{
        //     $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
        //     $NroIden_afiliado_dic = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
        //     $Telefono_afiliado_dic = '';
        //     $Email_afiliado_dic = '';
        //     $Direccion_afiliado_dic = $array_datos_info_afiliado[0]->Direccion_benefi;
        //     $Ciudad_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
        // }

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
            $Tipo_iden_ben = $array_datos_info_afiliado[0]->T_documento;            
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

        // if ($Documento_iden_ben == '') {
            $Numero_documento_afiliado = $NroIden_per_cal;
            $Documento_afiliado = $Tipo_documento_per_cal;
            $Nombre_afiliado_pre = $Nombre_per_cal;
        // } else {            
        //     $Numero_documento_afiliado = $Documento_iden_ben;
        //     $Documento_afiliado = $Tipo_iden_ben;
        //     $Nombre_afiliado_pre = $Nombre_ben;
        // }
        

        //Captura de datos de Etapas del ciclo vital

        $validar_laboralmente_activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado','Activo']])->get();       

        if (count($validar_laboralmente_activo) > 0) {
            $Poblacion_edad_econo_activa = 'X';
        }else{
            $Poblacion_edad_econo_activa = '';
        }        

        $validar_rol_ocupacional = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado','Activo']])->get();       

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
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado','Activo']])->get();  

        //Captura de datos Fundamentos para la calificacion de la perdida de la capacidad laboral y ocupacional - titulos I Y II

        $Descripcion_enfermedad_actual = $array_datos_info_dictamen[0]->Descripcion_enfermedad_actual;

        $array_diagnosticos_fc = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro as Nombre_origen', 
        'slp2.Nombre_parametro as Nombre_lateralidad', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['side.Estado','Activo']])->get();  

        $array_deficiencias_alteraciones = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
        ->select('sidae.Id_tabla', 'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.FU', 'sidae.CFM1', 'sidae.CFM2', 
        'sidae.Clase_Final', 'sidae.Dominancia', 'sidae.Deficiencia', 'sidae.Total_deficiencia', 'sidae.CAT', 'sidae.MSD')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['sidae.Estado','Activo']])
        ->orderByRaw("CAST(sidae.Total_deficiencia AS DECIMAL(10,2)) DESC")
        ->get();  
        
        $Suma_combinada_fc = $array_datos_info_dictamen[0]->Suma_combinada;

        $array_deficiencia_auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();
        
        $array_deficiencia_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get(); 

        $array_deficiencia_visualre = sigmel_informacion_agudeza_visualre_eventos::on('sigmel_gestiones')
        ->where([['ID_evento_re',$ID_Evento_comuni], ['Id_Asignacion_re',$Id_Asignacion_comuni], ['Estado_Recalificacion', 'Activo']])->get(); 

        $Total_deficiencia50_fc = $array_datos_info_dictamen[0]->Total_Deficiencia50;

        $array_datos_laboralmente_activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();  

        $array_datos_rol_ocupacional = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();

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
        // $validar_servicio_revision_pension = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        // ->select('Id_servicio')->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get();  
        $Revision_pension_dp = $array_datos_info_dictamen[0]->Requiere_Revision_Pension;
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

        $indicativo = time();
        // $nombre_pdf = 'PCL_DML_'.$Id_Asignacion_comuni.'_'.$Numero_documento_afiliado.'.pdf';    
        $nombre_pdf = 'PCL_DML_'.$Id_Asignacion_comuni.'_'.$Numero_documento_afiliado.'_'.$indicativo.'.pdf';    

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
        // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        // ->select('siae.Id_servicio')
        // ->where([
        //     ['siae.Id_Asignacion', $Id_Asignacion_comuni],
        //     ['siae.ID_evento', $ID_Evento_comuni],
        //     ['siae.Id_proceso', $Id_Proceso_comuni],
        // ])->get();

        // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        // $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        // ->select('sice.F_comunicado')
        // ->where([
        //     ['sice.N_radicado', $Radicado_comuni]
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
        //     $info_descarga_documento = [
        //         'Id_Asignacion' => $Id_Asignacion_comuni,
        //         'Id_proceso' => $Id_Proceso_comuni,
        //         'Id_servicio' => $Id_servicio,
        //         'ID_evento' => $ID_Evento_comuni,
        //         'Nombre_documento' => $nombre_pdf,
        //         'N_radicado_documento' => $Radicado_comuni,
        //         'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
        //         'F_descarga_documento' => $date,
        //         'Nombre_usuario' => $nombre_usuario,
        //     ];
            
        //     sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        // }

        return $pdf->download($nombre_pdf);
    }
    // Generar PDF del Dictamen de PCL 917

    public function generarPdfDictamenPcl917(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        if ($request->Bandera_boton_guardar_dictamen == 'boton_dictamen') {
            $ID_Evento_comuni = $request->ID_Evento_comuni;
            $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
            $Id_Proceso_comuni = $request->Id_Proceso_comuni;
            $Radicado_comuni = $request->Radicado_comuni;
            $Id_Comunicado = $request->Id_Comunicado;
            $N_siniestro = $request->N_siniestro;
        } else {
            $ID_Evento_comuni = $request->ID_Evento_comuni;
            $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
            $Id_Proceso_comuni = $request->Id_Proceso_comuni;
            $Radicado_comuni = $request->Radicado_comuni;
            $Id_Comunicado = $request->Id_Comunicado;
            $N_siniestro = $request->N_siniestro;            
        }
        
        
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
        'side.F_estructuracion', 'side.Requiere_Revision_Pension', 'side.Sustentacion_F_estructuracion', 'side.Detalle_calificacion', 'side.Enfermedad_catastrofica', 
        'side.Enfermedad_congenita', 'side.Tipo_enfermedad', 'slpa.Nombre_parametro as Nombre_enfermedad', 'side.Requiere_tercera_persona', 
        'side.Requiere_tercera_persona_decisiones', 'side.Requiere_dispositivo_apoyo', 'side.Justificacion_dependencia', 'side.N_radicado', 
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro')
        ->where([['side.ID_Evento',$ID_Evento_comuni], ['side.Id_Asignacion',$Id_Asignacion_comuni]])->get();        
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

        // if ($Tipo_afiliado !== 27 ) {
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado;            
        // }else{
            // $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;            
        // }

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
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado', 'Activo']])->get();  

        //Captura de datos Fundamentos para la calificacion de la perdida de la capacidad laboral y ocupacional - libros I, II y III

        $Descripcion_enfermedad_actual = $array_datos_info_dictamen[0]->Descripcion_enfermedad_actual;

        $array_diagnosticos_fc = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro as Nombre_origen', 
        'slp2.Nombre_parametro as Nombre_lateralidad', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['side.Estado', 'Activo']])->get();  

        $array_deficiencias_alteraciones = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')        
        ->select('sidae.Tabla1999', 'sidae.Titulo_tabla1999', 'sidae.Total_deficiencia')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['sidae.Estado', 'Activo']])
        ->orderByRaw("CAST(sidae.Deficiencia AS DECIMAL(10,2)) DESC")
        ->get();  
        
        $Suma_combinada_fc = $array_datos_info_dictamen[0]->Suma_combinada;        

        $Total_deficiencia50_fc = $array_datos_info_dictamen[0]->Total_Deficiencia50;

        $array_datos_libros23 = sigmel_informacion_libro2_libro3_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();  

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
        // $validar_servicio_revision_pension = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        // ->select('Id_servicio')->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get();  
        $Revision_pension_dp = $array_datos_info_dictamen[0]->Requiere_Revision_Pension;        
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
        
        $indicativo = time();

        // $nombre_pdf = 'PCL_DML_'.$Id_Asignacion_comuni.'_'.$Numero_documento_afiliado.'.pdf';    
        $nombre_pdf = 'PCL_DML_'.$Id_Asignacion_comuni.'_'.$Numero_documento_afiliado.'_'.$indicativo.'.pdf';    

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
        // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        // ->select('siae.Id_servicio')
        // ->where([
        //     ['siae.Id_Asignacion', $Id_Asignacion_comuni],
        //     ['siae.ID_evento', $ID_Evento_comuni],
        //     ['siae.Id_proceso', $Id_Proceso_comuni],
        // ])->get();

        // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        // $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        // ->select('sice.F_comunicado')
        // ->where([
        //     ['sice.N_radicado', $Radicado_comuni]
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
        //     $info_descarga_documento = [
        //         'Id_Asignacion' => $Id_Asignacion_comuni,
        //         'Id_proceso' => $Id_Proceso_comuni,
        //         'Id_servicio' => $Id_servicio,
        //         'ID_evento' => $ID_Evento_comuni,
        //         'Nombre_documento' => $nombre_pdf,
        //         'N_radicado_documento' => $Radicado_comuni,
        //         'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
        //         'F_descarga_documento' => $date,
        //         'Nombre_usuario' => $nombre_usuario,
        //     ];
            
        //     sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        // }

        return $pdf->download($nombre_pdf);
    }
    // Generar PDF de Notificacion numerica para el decreto 1507 y 917

    public function generarOficio_Pcl(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        if ($request->Bandera_boton_guardar_oficio == 'boton_oficio') {            
            $ID_Evento_comuni_comite = $request->ID_Evento_comuni_comite;
            $Id_Asignacion_comuni_comite = $request->Id_Asignacion_comuni_comite;
            $Id_Proceso_comuni_comite = $request->Id_Proceso_comuni_comite;
            $Radicado_comuni_comite = $request->Radicado_comuni_comite;
            $Firma_comuni_comite = $request->Firma_comuni_comite;
            $Id_Comunicado = $request->Id_Comunicado;
            $N_siniestro = $request->N_siniestro;            
        } else {
            $ID_Evento_comuni_comite = $request->ID_Evento_comuni_comite;
            $Id_Asignacion_comuni_comite = $request->Id_Asignacion_comuni_comite;
            $Id_Proceso_comuni_comite = $request->Id_Proceso_comuni_comite;
            $Radicado_comuni_comite = $request->Radicado_comuni_comite;
            $Firma_comuni_comite = $request->Firma_comuni_comite;
            $Id_Comunicado = $request->Id_Comunicado;
            $N_siniestro = $request->N_siniestro;            
        }
        
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

        //Footer_Image
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
        $Copia_afiliado_correspondencia = $array_datos_comite_inter[0]->Copia_afiliado;
        $Copia_empleador_correspondecia = $array_datos_comite_inter[0]->Copia_empleador;
        $Copia_eps_correspondecia = $array_datos_comite_inter[0]->Copia_eps;
        $Copia_afp_correspondecia = $array_datos_comite_inter[0]->Copia_afp;
        $Copia_afp_conocimiento_correspondencia = $array_datos_comite_inter[0]->Copia_afp_conocimiento;
        $Copia_arl_correspondecia = $array_datos_comite_inter[0]->Copia_arl;
        $Oficio_pcl = $array_datos_comite_inter[0]->Oficio_pcl;

        // echo $Cuerpo_comunicado_correspondencia;

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
        'sie.Telefonos as Telefono_eps', 'sie.Emails as Email_eps', 'sie.Id_Departamento', 'sldepa.Nombre_departamento as Nombre_departamento_eps', 'sie.Id_Ciudad', 
        'sldmun.Nombre_municipio as Nombre_municipio_eps', 'siae.Id_afp', 'sien.Nombre_entidad as Entidad_afp', 
        'sien.Direccion as Direccion_afp', 'sien.Telefonos as Telefono_afp', 'sien.Emails as Email_afp', 'sien.Id_Departamento', 
        'sldepar.Nombre_departamento as Nombre_departamento_afp', 'sien.Id_Ciudad', 
        'sldmuni.Nombre_municipio as Nombre_municipio_afp', 'siae.Id_arl', 'sient.Nombre_entidad as Entidad_arl', 
        'sient.Direccion as Direccion_arl', 'sient.Telefonos as Telefono_arl', 'sient.Emails as Email_arl','sient.Id_Departamento', 
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

        if (!empty($Copia_afiliado_correspondencia) && $Copia_afiliado_correspondencia == 'Afiliado') {
            $Nombre_afiliado = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $Direccion_afiliado = $array_datos_info_afiliado[0]->Direccion;
            $Telefono_afiliado = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Email_afiliado = $array_datos_info_afiliado[0]->Email;
            $Ciudad_departamento_afiliado = $array_datos_info_afiliado[0]->Nombre_municipio.'-'.$array_datos_info_afiliado[0]->Nombre_departamento;
        } else {
            $Nombre_afiliado = '';
            $Direccion_afiliado = '';
            $Telefono_afiliado = '';
            $Email_afiliado = '';
            $Ciudad_departamento_afiliado = '';
        }

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
                ->select('sie.Nombre_entidad', 'sie.Direccion', 'sie.Telefonos', 'sie.Emails as Email','sie.Otros_Telefonos', 'sldm.Nombre_departamento', 'sldm2.Nombre_municipio as Nombre_ciudad')
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
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro')
        ->where([['side.ID_Evento',$ID_Evento_comuni_comite], ['side.Id_Asignacion',$Id_Asignacion_comuni_comite]])->get(); 
        
        $PorcentajePcl_dp = $array_datos_info_dictamen[0]->Porcentaje_pcl;
        $F_estructuracionPcl_dp = $array_datos_info_dictamen[0]->F_estructuracion;
        $OrigenPcl_dp = $array_datos_info_dictamen[0]->Nombre_origen;        

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
                'F_correspondecia' => fechaFormateada($F_correspondecia),
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
                'Copia_afiliado_correspondencia' => $Copia_afiliado_correspondencia,
                'Copia_afiliado_correo' => $Email_afiliado,
                'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
                'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
                'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
                'Copia_afp_conocimiento_correspondencia' => $Copia_afp_conocimiento_correspondencia,
                'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
                'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
                'copiaEmail_empresa_noti' => $copiaEmail_empresa_noti,
                'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
                'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
                'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
                'Nombre_afiliado_copia' => $Nombre_afiliado,
                'Direccion_afiliado_copia' => $Direccion_afiliado,
                'Telefono_afiliado_copia' => $Telefono_afiliado,
                'Ciudad_departamento_afiliado_copia' => $Ciudad_departamento_afiliado,
                'Nombre_eps' => $Nombre_eps,
                'Direccion_eps' => $Direccion_eps,
                'Telefono_eps' => $Telefono_eps,
                'Email_eps' => $Email_eps,
                'Ciudad_departamento_eps' => $Ciudad_departamento_eps,
                'Nombre_afp' => $Nombre_afp,
                'Direccion_afp' => $Direccion_afp,
                'Telefono_afp' => $Telefono_afp,
                'Email_afp' => $Email_afp,
                'Ciudad_departamento_afp' => $Ciudad_departamento_afp,
                'Nombre_afp_conocimiento' => $Nombre_afp_conocimiento,
                'Direccion_afp_conocimiento' => $Direccion_afp_conocimiento,
                'Telefonos_afp_conocimiento' => $Telefonos_afp_conocimiento,
                'Ciudad_departamento_afp_conocimiento' => $Ciudad_departamento_afp_conocimiento,
                'Nombre_arl' => $Nombre_arl,
                'Direccion_arl' => $Direccion_arl,
                'Telefono_arl' => $Telefono_arl,
                'Ciudad_departamento_arl' => $Ciudad_departamento_arl,
                'Email_arl' => $Email_arl,
                'footer' => $footer,
                'N_siniestro' => $N_siniestro,
                'Email_afp_conocimiento' => $Email_afp_conocimiento
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_remisorio_pcl', $data);     
            
            $indicativo = time();

            // $nombre_pdf = 'PCL_OFICIO_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';    
            $nombre_pdf = 'PCL_OFICIO_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'_'.$indicativo.'.pdf';    

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
                // Se valida si antes de insertar la info del doc de origen ya hay un documento de tipo otro
                $nombre_docu_pcl_ica = "PCL_OFICIO_INC_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
                $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
                ->select('Nombre_documento')
                ->where([
                    ['Nombre_documento', $nombre_docu_pcl_ica],
                ])->get();

                // Si no existe info del documento de Oficio pcl incapacidad, inserta la info del documento de Oficio pcl
                // De lo contrario hace una actualización de la info
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
        } else {
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
                'F_correspondecia' => fechaFormateada($F_correspondecia),
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
                'Copia_afiliado_correspondencia' => $Copia_afiliado_correspondencia,
                'Copia_afiliado_correo' => $Email_afiliado,
                'Copia_empleador_correspondecia' => $Copia_empleador_correspondecia,
                'Copia_eps_correspondecia' => $Copia_eps_correspondecia,
                'Copia_afp_correspondecia' => $Copia_afp_correspondecia,
                'Copia_afp_conocimiento_correspondencia' => $Copia_afp_conocimiento_correspondencia,
                'Copia_arl_correspondecia' => $Copia_arl_correspondecia,
                'copiaNombre_empresa_noti' => $copiaNombre_empresa_noti,
                'copiaEmail_empresa_noti' => $copiaEmail_empresa_noti,
                'copiaDireccion_empresa_noti' => $copiaDireccion_empresa_noti,
                'copiaTelefono_empresa_noti' => $copiaTelefono_empresa_noti,
                'copiaCiudad_departamento_empresa_noti' => $copiaCiudad_departamento_empresa_noti,
                'Nombre_afiliado_copia' => $Nombre_afiliado,
                'Direccion_afiliado_copia' => $Direccion_afiliado,
                'Telefono_afiliado_copia' => $Telefono_afiliado,
                'Ciudad_departamento_afiliado_copia' => $Ciudad_departamento_afiliado,
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
                'Email_arl' => $Email_arl,
                'Email_afp_conocimiento' => $Email_afp_conocimiento
                // 'footer_dato_1' => $footer_dato_1,
                // 'footer_dato_2' => $footer_dato_2,
                // 'footer_dato_3' => $footer_dato_3,
                // 'footer_dato_4' => $footer_dato_4,
                // 'footer_dato_5' => $footer_dato_5,
            ];
            // Crear una instancia de Dompdf
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('/Proformas/Proformas_Prev/PCL/oficio_remisorio_pcl_incapacidad', $data);       

            $indicativo = time();

            // $nombre_pdf = 'PCL_OFICIO_INC_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';
            $nombre_pdf = 'PCL_OFICIO_INC_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'_'.$indicativo.'.pdf';

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
            // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
            // ->select('siae.Id_servicio')
            // ->where([
            //     ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
            //     ['siae.ID_evento', $ID_Evento_comuni_comite],
            //     ['siae.Id_proceso', $Id_Proceso_comuni_comite],
            // ])->get();

            // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

            // // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
            // $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
            // ->select('sice.F_comunicado')
            // ->where([
            //     ['sice.N_radicado', $Radicado_comuni_comite]
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
            //     // Se valida si antes de insertar la info del doc de origen ya hay un documento de tipo otro
            //     $nombre_docu_pcl_ica = "PCL_OFICIO_{$Id_Asignacion_comuni_comite}_{$NroIden_afiliado_noti}.pdf";
            //     $verificar_docu_otro = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //     ->select('Nombre_documento')
            //     ->where([
            //         ['Nombre_documento', $nombre_docu_pcl_ica],
            //     ])->get();

            //     // Si no existe info del documento de Oficio pcl incapacidad, inserta la info del documento de Oficio pcl
            //     // De lo contrario hace una actualización de la info
            //     if (count($verificar_docu_otro) == 0) {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion_comuni_comite,
            //             'Id_proceso' => $Id_Proceso_comuni_comite,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $ID_Evento_comuni_comite,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $Radicado_comuni_comite,
            //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => $nombre_usuario,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
                    
            //     } else {
            //         $info_descarga_documento = [
            //             'Id_Asignacion' => $Id_Asignacion_comuni_comite,
            //             'Id_proceso' => $Id_Proceso_comuni_comite,
            //             'Id_servicio' => $Id_servicio,
            //             'ID_evento' => $ID_Evento_comuni_comite,
            //             'Nombre_documento' => $nombre_pdf,
            //             'N_radicado_documento' => $Radicado_comuni_comite,
            //             'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
            //             'F_descarga_documento' => $date,
            //             'Nombre_usuario' => $nombre_usuario,
            //         ];
                    
            //         sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            //         ->where([
            //             ['Id_Asignacion', $Id_Asignacion_comuni_comite],
            //             ['N_radicado_documento', $Radicado_comuni_comite],
            //             ['ID_evento', $ID_Evento_comuni_comite]
            //         ])
            //         ->update($info_descarga_documento);
            //     }
                
            // }

            return $pdf->download($nombre_pdf);
        }
                
    }

    // Generar PDF del Dictamen de PCL Cero

    public function generarPdfDictamenPclCero(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $cargo_profesional = Auth::user()->cargo;

        if ($request->Bandera_boton_guardar_dictamen == 'boton_dictamen') {
            $ID_Evento_comuni = $request->ID_Evento_comuni;
            $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
            $Id_Proceso_comuni = $request->Id_Proceso_comuni;
            $Radicado_comuni = $request->Radicado_comuni;
            $Id_Comunicado = $request->Id_Comunicado;
            $N_siniestro = $request->N_siniestro;
        } else {
            $ID_Evento_comuni = $request->ID_Evento_comuni;
            $Id_Asignacion_comuni = $request->Id_Asignacion_comuni;
            $Id_Proceso_comuni = $request->Id_Proceso_comuni;
            $Radicado_comuni = $request->Radicado_comuni;
            $Id_Comunicado = $request->Id_Comunicado;
            $N_siniestro = $request->N_siniestro;            
        }
        
        
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
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro')
        ->where([['side.ID_Evento',$ID_Evento_comuni], ['side.Id_Asignacion',$Id_Asignacion_comuni]])->get();        
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

        // if ($Tipo_afiliado !== 27 ) {
            $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $NroIden_afiliado_dic = $array_datos_info_afiliado[0]->Nro_identificacion;
            $Telefono_afiliado_dic = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Email_afiliado_dic = $array_datos_info_afiliado[0]->Email;
            $Direccion_afiliado_dic = $array_datos_info_afiliado[0]->Direccion;
            $Ciudad_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_municipio;
        // }else{
        //     $Nombre_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_afiliado_benefi;
        //     $NroIden_afiliado_dic = $array_datos_info_afiliado[0]->Nro_identificacion_benefi;
        //     $Telefono_afiliado_dic = '';
        //     $Email_afiliado_dic = '';
        //     $Direccion_afiliado_dic = $array_datos_info_afiliado[0]->Direccion_benefi;
        //     $Ciudad_afiliado_dic = $array_datos_info_afiliado[0]->Nombre_municipio_benefi;
        // }

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
            $Tipo_iden_ben = $array_datos_info_afiliado[0]->T_documento;            
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

        // if ($Documento_iden_ben == '') {
            $Numero_documento_afiliado = $NroIden_per_cal;
            $Documento_afiliado = $Tipo_documento_per_cal;
            $Nombre_afiliado_pre = $Nombre_per_cal;
        // } else {            
        //     $Numero_documento_afiliado = $Documento_iden_ben;
        //     $Documento_afiliado = $Tipo_iden_ben;
        //     $Nombre_afiliado_pre = $Nombre_ben;
        // }
        

        //Captura de datos de Etapas del ciclo vital

        // $validar_laboralmente_activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        // ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();       

        // if (count($validar_laboralmente_activo) > 0) {
        //     $Poblacion_edad_econo_activa = 'X';
        // }else{
        //     $Poblacion_edad_econo_activa = '';
        // }     
        
        $Poblacion_edad_econo_activa = 'X';

        // $validar_rol_ocupacional = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        // ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();       

        // if (count($validar_rol_ocupacional) > 0) {
        //     if ($validar_rol_ocupacional[0]->Poblacion_calificar == 75) {
        //         $Bebe_menor3 = 'X';
        //         $Ninos_adolecentes = '';
        //         $Adultos_mayores = '';                
        //     }elseif($validar_rol_ocupacional[0]->Poblacion_calificar == 76){
        //         $Bebe_menor3 = '';
        //         $Ninos_adolecentes = 'X';
        //         $Adultos_mayores = '';
        //     }elseif($validar_rol_ocupacional[0]->Poblacion_calificar == 77){
        //         $Bebe_menor3 = '';
        //         $Ninos_adolecentes = '';
        //         $Adultos_mayores = 'X';
        //     }
            
        // }else{
        //     $Bebe_menor3 = '';
        //     $Ninos_adolecentes = '';
        //     $Adultos_mayores = '';
        // } 

        $Bebe_menor3 = '';
        $Ninos_adolecentes = '';
        $Adultos_mayores = '';

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
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['Estado', 'Activo']])->get();  

        //Captura de datos Fundamentos para la calificacion de la perdida de la capacidad laboral y ocupacional - titulos I Y II

        $Descripcion_enfermedad_actual = $array_datos_info_dictamen[0]->Descripcion_enfermedad_actual;

        $array_diagnosticos_fc = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_diagnosticos_eventos as side')
        ->leftJoin('sigmel_gestiones.sigmel_lista_cie_diagnosticos as slcd', 'slcd.Id_Cie_diagnostico', '=', 'side.CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'side.Origen_CIE10')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp2', 'slp2.Id_Parametro', '=', 'side.Lateralidad_CIE10')
        ->select('side.CIE10', 'slcd.CIE10 as Codigo_cie10', 'side.Nombre_CIE10', 'side.Origen_CIE10', 'slp.Nombre_parametro as Nombre_origen', 
        'slp2.Nombre_parametro as Nombre_lateralidad', 'side.Deficiencia_motivo_califi_condiciones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Id_proceso',$Id_Proceso_comuni], ['side.Estado', 'Activo']])->get();  

        $array_deficiencias_alteraciones = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_deficiencias_alteraciones_eventos as sidae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tablas_1507_decretos as sltd', 'sltd.Id_tabla', '=', 'sidae.Id_tabla')
        ->select('sidae.Id_tabla', 'sltd.Ident_tabla', 'sltd.Nombre_tabla', 'sidae.FP', 'sidae.FU', 'sidae.CFM1', 'sidae.CFM2', 
        'sidae.Clase_Final', 'sidae.Total_deficiencia', 'sidae.CAT', 'sidae.MSD')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['sidae.Estado', 'Activo']])->get();  
        
        $Suma_combinada_fc = $array_datos_info_dictamen[0]->Suma_combinada;

        $array_deficiencia_auditiva = sigmel_informacion_agudeza_auditiva_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();
        
        $array_deficiencia_visual = sigmel_informacion_agudeza_visual_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni]])->get(); 

        $Total_deficiencia50_fc = $array_datos_info_dictamen[0]->Total_Deficiencia50;

        $array_datos_laboralmente_activo = sigmel_informacion_laboralmente_activo_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();  

        $array_datos_rol_ocupacional = sigmel_informacion_rol_ocupacional_eventos::on('sigmel_gestiones')
        ->where([['ID_Evento',$ID_Evento_comuni], ['Id_Asignacion',$Id_Asignacion_comuni], ['Estado', 'Activo']])->get();

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

        $indicativo = time();
        $nombre_pdf = 'PCL_DML_'.$Id_Asignacion_comuni.'_'.$Numero_documento_afiliado.'_'.$indicativo.'.pdf';    

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
        // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        // ->select('siae.Id_servicio')
        // ->where([
        //     ['siae.Id_Asignacion', $Id_Asignacion_comuni],
        //     ['siae.ID_evento', $ID_Evento_comuni],
        //     ['siae.Id_proceso', $Id_Proceso_comuni],
        // ])->get();

        // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        // $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        // ->select('sice.F_comunicado')
        // ->where([
        //     ['sice.N_radicado', $Radicado_comuni]
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
        //     $info_descarga_documento = [
        //         'Id_Asignacion' => $Id_Asignacion_comuni,
        //         'Id_proceso' => $Id_Proceso_comuni,
        //         'Id_servicio' => $Id_servicio,
        //         'ID_evento' => $ID_Evento_comuni,
        //         'Nombre_documento' => $nombre_pdf,
        //         'N_radicado_documento' => $Radicado_comuni,
        //         'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
        //         'F_descarga_documento' => $date,
        //         'Nombre_usuario' => $nombre_usuario,
        //     ];
            
        //     sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        // }

        return $pdf->download($nombre_pdf);   
    }
    // Generar PDF de Notificacion Cero

    public function generarPdfNotificacionPclCero(Request $request){
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
        $N_siniestro = $request->N_siniestro;


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

        //Footer_Image
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

        // if ($Tipo_afiliado !== 27 ) {
            $Nombre_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_afiliado;
            $Direccion_afiliado_noti = $array_datos_info_afiliado[0]->Direccion;
            $Telefono_afiliado_noti = $array_datos_info_afiliado[0]->Telefono_contacto;
            $Departamento_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_departamento;            
            $Ciudad_afiliado_noti = $array_datos_info_afiliado[0]->Nombre_municipio;
            $T_documento_noti = $array_datos_info_afiliado[0]->T_documento;            
            $NroIden_afiliado_noti = $array_datos_info_afiliado[0]->Nro_identificacion;
            $Email_afiliado_noti = $array_datos_info_afiliado[0]->Email;
        // }else{
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
        'side.Estado_decreto', 'side.Nombre_usuario', 'side.F_registro')
        ->where([['side.ID_Evento',$ID_Evento_comuni_comite], ['side.Id_Asignacion',$Id_Asignacion_comuni_comite]])->get(); 
        
        $PorcentajePcl_cero = 0;

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
        'sile.Id_municipio', 'sldm.Nombre_municipio','sile.Email')->where([['ID_Evento',$ID_Evento_comuni_comite]])->limit(1)->get();

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
            'copiaEmail_empresa_noti' => $copiaEmail_empresa_noti,
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
            'N_siniestro' => $N_siniestro
            // 'footer_dato_1' => $footer_dato_1,
            // 'footer_dato_2' => $footer_dato_2,
            // 'footer_dato_3' => $footer_dato_3,
            // 'footer_dato_4' => $footer_dato_4,
            // 'footer_dato_5' => $footer_dato_5,
        ];

        // Crear una instancia de Dompdf

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('/Proformas/Proformas_Arl/PCL/notificacion_pcl_cero', $data);        

        $indicativo = time();

        // $nombre_pdf = 'PCL_OFICIO_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'.pdf';
        $nombre_pdf = 'PCL_OFICIO_'.$Id_Asignacion_comuni_comite.'_'.$NroIden_afiliado_noti.'_'.$indicativo.'.pdf';

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
        // $dato_id_servicio = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        // ->select('siae.Id_servicio')
        // ->where([
        //     ['siae.Id_Asignacion', $Id_Asignacion_comuni_comite],
        //     ['siae.ID_evento', $ID_Evento_comuni_comite],
        //     ['siae.Id_proceso', $Id_Proceso_comuni_comite],
        // ])->get();

        // $Id_servicio = $dato_id_servicio[0]->Id_servicio;

        // // Extraemos la Fecha de elaboración de correspondencia: Esta consulta aplica solo para los dictamenes
        // $dato_f_elaboracion_correspondencia = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_comunicado_eventos as sice') 
        // ->select('sice.F_comunicado')
        // ->where([
        //     ['sice.N_radicado', $Radicado_comuni_comite]
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
        //     $info_descarga_documento = [
        //         'Id_Asignacion' => $Id_Asignacion_comuni_comite,
        //         'Id_proceso' => $Id_Proceso_comuni_comite,
        //         'Id_servicio' => $Id_servicio,
        //         'ID_evento' => $ID_Evento_comuni_comite,
        //         'Nombre_documento' => $nombre_pdf,
        //         'N_radicado_documento' => $Radicado_comuni_comite,
        //         'F_elaboracion_correspondencia' => $F_elaboracion_correspondencia,
        //         'F_descarga_documento' => $date,
        //         'Nombre_usuario' => $nombre_usuario,
        //     ];
            
        //     sigmel_registro_descarga_documentos::on('sigmel_gestiones')->insert($info_descarga_documento);
        // }

        return $pdf->download($nombre_pdf);
    }
}
