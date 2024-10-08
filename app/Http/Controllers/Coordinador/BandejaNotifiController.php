<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Administrador\AccionesController;
use App\Models\User;

use App\Models\sigmel_lista_procesos_servicios;
use App\Models\cndatos_bandeja_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_numero_orden_eventos;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\sigmel_informacion_correspondencia_eventos;
use App\Models\sigmel_informacion_historial_accion_eventos;
use App\Models\sigmel_informacion_eventos;

use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\AccionesAutomaticas;
use App\Services\ServiceBus;

class BandejaNotifiController extends Controller
{
    private $BaseBandeja;

    /*public function __construct(ServiceBus $serviceBus)
    {

        $serviceBus->registrarServicio([
            'BaseBandeja' => \App\Services\BaseBandeja::class
        ]);
        
        $this->BaseBandeja = $serviceBus->llamar('BaseBandeja');

    }*/

    // Bandeja Notifiacion Coordinador
    public function mostrarVistaBandejaNotifi(){
        $user = Auth::user();    
        // consulta numero de orden
        $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
        ->select('Numero_orden')
        ->get();

        $listado_Acciones = AccionesController::getAccionesNotificacion();

        return view('coordinador.bandejaNotifi', compact('user','n_orden','listado_Acciones'));
    }

    //Selectores Bandeja Notifi
    public function cargueListadoSelectoresBandejaNotifi(Request $request){
        $parametro = $request->parametro;
        
        // listado de procesos que almenos tienen configurado una paramétrica
        if($parametro == 'listado_procesos_parametrizados'){
            $listado_procesos_parametrizados = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_lista_procesos_servicios as slps')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_parametrizaciones_clientes as sipc', 'slps.Id_proceso', '=', 'sipc.Id_proceso')
            ->select('slps.Id_proceso', 'slps.Nombre_proceso')
            ->whereNotNull('sipc.Id_proceso')
            ->groupBy('slps.Id_proceso')->get();

            $info_listado_procesos_parametrizados = json_decode(json_encode($listado_procesos_parametrizados, true));
            return response()->json($info_listado_procesos_parametrizados);
        }
        
        //Listado servicio proceso Notifi
        if($parametro == 'lista_servicios_notifi'){
            $listado_servicio_Notifi = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                // ['Nombre_proceso', '=', 'Notificaciones'],
                ['Id_proceso', '=', $request->id_proceso],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_Notifi = json_decode(json_encode($listado_servicio_Notifi, true));
            return response()->json($info_listado_servicio_Notifi);
        }


        // listado de profesionales para el proceso notificaciones
        if ($parametro == 'lista_profesional_notifi') {
            
            $listado_profesional_notifi = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(4, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_Notifi = json_decode(json_encode($listado_profesional_notifi, true));
            return response()->json($info_listado_profesional_Notifi);
        }

    }

    public function sinFiltroBandejaNotifi(Request $request){

        $BandejaNotifiTotal = $request->BandejaNotifiTotal;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user;     

        $time = time();
        $date = date("Y-m-d", $time);
        $year = date("Y");  

        if($BandejaNotifiTotal == 'CargaBandejaNotifi'){

            if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                $bandejaNotifi = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where('Id_profesional', '=', $newId_user)->where(function($query){
                    $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                })
                //->whereBetween('F_registro_asignacion', [$year.'-01-01' , $date])
                ->get();
                //dd( $newId_user);
            }else{
                $bandejaNotifi = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where('Enviar_bd_Notificacion', '=', 'Si')
                //->whereBetween('F_registro_asignacion', [$year.'-01-01' , $date])
                ->get();

            }
            return response()->json($bandejaNotifi);

        }
    }

    public function filtrosBandejaNotifi(Request $request){
        
        $consultar_f_desde = $request->consultar_f_desde;
        $consultar_f_hasta = $request->consultar_f_hasta;
        $consultar_g_dias = $request->consultar_g_dias;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user; 
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ['Id_profesional', '=', $newId_user]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                }else{
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where('Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias)->where(function($query){
                        $query->where('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                }

            
                    $arraybandejaNotifiFiltros = json_decode(json_encode($bandejaNotifiFiltros, true));
                    if (count($arraybandejaNotifiFiltros)>0) {
                        return response()->json($arraybandejaNotifiFiltros);                        
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada.',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }                    
            break;
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and empty($consultar_g_dias)):
                    
                if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where('Id_profesional', '=', $newId_user)->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                
                }else{
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where('Enviar_bd_Notificacion', '=', 'Si')
                    ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                }

                    $arraybandejaNotifiFiltros = json_decode(json_encode($bandejaNotifiFiltros, true));
                    if (count($arraybandejaNotifiFiltros)>0) {
                        return response()->json($arraybandejaNotifiFiltros);
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada.',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }
            break;
            case (empty($consultar_f_desde) and empty($consultar_f_hasta) and !empty($consultar_g_dias)):
                    
                if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ['Id_profesional', '=', $newId_user]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->get();
                }else{
                    $bandejaNotifiFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where('Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias)->where(function($query){
                        $query->where('Enviar_bd_Notificacion', '=', 'Si');
                    })
                    ->get();

                }

                
                    $arraybandejaNotifiFiltros = json_decode(json_encode($bandejaNotifiFiltros, true));
                    if (count($arraybandejaNotifiFiltros)>0) {
                        return response()->json($arraybandejaNotifiFiltros);
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }
            break;     
            case (!empty($consultar_f_desde) and empty($consultar_f_hasta) and empty($consultar_g_dias)):
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensajes" => 'Debe ingresar la fecha Hasta para poder filtrar',
                        "registros" => 0
                    );
                    return json_decode(json_encode($mensajes, true));
            break;  
            case (empty($consultar_f_desde) and !empty($consultar_f_hasta) and empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la Fecha Desde para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;  
            case (!empty($consultar_f_desde) and empty($consultar_f_hasta) and !empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la fecha Hasta para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;  
            case (empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la Fecha Desde para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;              
            default:                
            break;
        }
    
        
    }


    public function alertaNaranjasRojasOrigen(Request $request) {
        $alertas = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
        ->where([['Estado_alerta_automatica', '=', 'Ejecucion']])
        ->get();
        return response()->json(['data' => $alertas]);
    }

    //Obtener informacion relacionada a los eventos de notificacion
    public function infomacionEnventosNotifiacion(Request $request){

        if($request->bandera == 'info_evento'){
            $request->validate([
                'bandera' => 'required',
                'id_asignacion' => 'required',
                'evento' => 'required'
            ]);

            $info_evento = BandejaNotifiController::evento_en_notificaciones($request->evento,$request->id_asignacion);
            $info_evento = json_decode(json_encode($info_evento, true));
            return response()->json($info_evento);
        }
    }

    public static function finalizarNotificacion(string $evento,int $id_asignacion,bool $estado, bool $agregar_n_orden = false){
        if(!$estado){
            sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->where([
                ['ID_evento',$evento],
                ['Id_Asignacion',$id_asignacion]
            ])->update(['Estado_correspondencia' => '1']);
        }else{
            sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->where([
                ['ID_evento',$evento],
                ['Id_Asignacion',$id_asignacion]
            ])->update(['Estado_correspondencia' => '0']); //desactiva la correspondencia para ediccion
        }

        if($agregar_n_orden){
            self::actualizar_n_evento($evento,$id_asignacion);
        }
    }

    public static function actualizar_n_evento(string $evento,$id_asignacion){
        //consecutivo actual
        $n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
        ->select('Numero_orden')
        ->get();

        //Consecutivo del evento
        $n_ordenNotificacion = DB::table(getDatabaseName('sigmel_gestiones') . "sigmel_informacion_asignacion_eventos")
        ->select('N_de_orden')->where([
            ['ID_evento',$evento],
            ['Id_Asignacion',$id_asignacion]
        ])->get()->first();

        //Asignar un consecutivo en caso de no tenga uno
        $N_orden_evento= $n_ordenNotificacion->N_de_orden ?? $n_orden[0]->Numero_orden;

        DB::table(getDatabaseName('sigmel_gestiones') . "sigmel_informacion_asignacion_eventos")
        ->where([
            ['ID_evento',$evento],
            ['Id_Asignacion',$id_asignacion]
        ])->update(['N_de_orden' => $N_orden_evento]);
    }

    public static function evento_en_notificaciones(string $id_evento,int $id_asignacion,$comunicado = null){
        $condiciones = array(['siaev.Id_Asignacion',$id_asignacion],
        ['siaev.ID_evento',$id_evento]);
        
        if($comunicado != null){
            array_push($condiciones,['Id_comunicado',$comunicado]);
        }

        $enviar_notificacion = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->from('sigmel_informacion_asignacion_eventos as siaev')
        ->select('siaev.Notificacion','sicoe.Estado_correspondencia')
        ->leftjoin('sigmel_gestiones.sigmel_informacion_correspondencia_eventos as sicoe','sicoe.Id_Asignacion','siaev.Id_Asignacion')
        ->where($condiciones)->get()->map(function($item){
            //1 - Activo 0 - Inactivo: Siempre y el evento no tenga alguna correspondencia y/o su estado sea 1 la se mostrara la correspodencia 
            $item->Notificacion = $item->Notificacion == 'No'  && is_null($item->Estado_correspondencia) ? 'No' : 'Si';
            $item->Estado_correspondencia =  $item->Notificacion == 'No'  && is_null($item->Estado_correspondencia) ? '0' :
                ($item->Notificacion == 'Si'  && is_null($item->Estado_correspondencia) ? '1' : $item->Estado_correspondencia);
            return $item;
        });

        return $enviar_notificacion;
    }

    public static function estado_Correspondencia(string $id_evento,int $id_asignacion,int $id_comunicado){
        $estadoCorrespondencia = sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')->select('Estado_correspondencia')
        ->where('Id_Comunicado',$id_comunicado)->first();

        $evento_en_notificacion  = self::evento_en_notificaciones($id_evento,$id_asignacion);

        //Caso cuando la notificacion esta en la bd de notificacion y/o los destinarios estan visibles afuera de la bd
        if ($estadoCorrespondencia === null && $evento_en_notificacion[0]->Notificacion = 'Si') {
            return $evento_en_notificacion[0]->Estado_correspondencia;
        } else {
            return $estadoCorrespondencia->Estado_correspondencia;
        }
    }

    /**
     * Despacha los diferentes procesos relacionados a la bandeja de notificaciones
     */
    public function proceso_notificaciones(Request $request){

        switch($request->bandera){
            case 'ejecutar_accion': 
                    $request->validate([
                        'accion_ejecutar' => 'required|int',
                        'datos_evento' => 'required|array',
                        'datos_evento.*.*.proceso' => 'required',
                        'datos_evento.*.*.servicio' => 'required',
                        'datos_evento.*.*.id_evento' => 'required'
                    ]);

                    return response()->json(
                    $this->ejecutar_accion($request->accion_ejecutar,$request->f_accion,
                    $request->descripcion,$request->f_alerta,$request->datos_evento));
                break;
            case 'getEventos':
                    return $this->getEventos($request);
                break;
        }

    }

    /**
     * Obtiene los eventos disponibles para ejecutar la accion seleccionada por el usuario
     */
    private function getEventos(Request $request){
        //Obtiene las acciones antecesoras cuya accion pertenezca a la seleccionada por el usuario
        $acciones_antesesora = DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_parametrizaciones_clientes')
        ->select('sia.Accion','Servicio_asociado')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia','sia.Id_Accion','Accion_antecesora') //Accion antecesora
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia2','sia2.Id_Accion','Accion_ejecutar') //Accion actual
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp','sia2.Estado_accion','slp.Id_Parametro')
         ->where([
             ['sia2.Accion',$request->id_acion_ejecutar],
             ['slp.Nombre_parametro','=','Notificado']
         ])->get()->toArray();

         //Filtra los eventos de acuerdo a las  acciones antecesoras
         $accion_antesesora = array_column($acciones_antesesora,'Accion');
         $servicios_asociados = array_column($acciones_antesesora,'Servicio_asociado');
 
         if(!is_null($acciones_antesesora[0])){
            $query = DB::table(getDatabaseName('sigmel_gestiones') . 'cndatos_bandeja_eventos')
                ->whereIn('Accion',$accion_antesesora)
                ->whereIn('Id_Servicio',$servicios_asociados)
                ->where(function($query){
                    $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'Si');
                });

            //Filtra los eventos de acuerdo al usuario
            if (in_array($request->newId_rol, ['3', '5', '10'])) {
                $query->where('Id_profesional', $request->newId_user);
            }

            $eventos = $query->get();
         }
 
         $response = [
             'estado' => $eventos->isEmpty() ? 'Sin datos' : 'ok',
             'datos' => $eventos ?? null
         ];
 
         return response()->json($response);
    }

    /**
     * @param int accion Accion a ejecutar
     * @param string f_accion Fecha en la que se ejecuto dicha accion
     * @param string descripcion Descripcion de la accion a ejecutar
     * @param string f_alerta fecha de alerta para ejecutar
     * @param Array datosEvento
     * @return string
     */
    private function ejecutar_accion(int $accion,string $f_accion, string $descripcion = null, string $f_alerta = null, Array $datosEvento){

        $nombre_usuario = Auth::user()->name;
        $estado_ejecucion = [];

        foreach($datosEvento as $evento => $id_asignacion){
            foreach($id_asignacion as $id => $values){

                //Recopilamos la informacion para las tablas de asignacion_eventos e historial de acciones
                $id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')->select('Cliente')->where('ID_evento',$values["id_evento"])->first();
                $proceso = $values["proceso"];
                $servicio = $values["servicio"];
                $id_evento = $values["id_evento"];
                $dataActualizar['Id_accion'] = $accion;
                $dataActualizar['Descripcion'] = $descripcion;
                $dataActualizar['Notificacion'] = self::ingresar_notificacion($id_cliente->Cliente,$servicio,$proceso,$accion); //Si - No
                $dataActualizar['F_alerta'] = $f_alerta;
                $dataActualizar['F_accion'] = $f_accion;
                $dataActualizar['Nombre_usuario'] = $nombre_usuario;

                sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $id)
                ->update($dataActualizar);

                //Se quita el campo para no generar conflictos con la tabla de historial_acciones
                unset($dataActualizar['F_alerta']);
            
                $data_historial_accion = [
                    'Id_Asignacion' => $id,
                    'ID_evento' => $values["id_evento"],
                    'Id_proceso' => $values["proceso"],
                    'Documento' => 'N/A',
                    'Id_servicio' => $values["servicio"],
                ];

                //Se completan los datos para el historial
                $data_historial_accion = array_merge($data_historial_accion,$dataActualizar);

                sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
                ->where('Id_Asignacion', $id)
                ->insert($data_historial_accion);

                //Datos necesarios para la alertas y movimientos automaticos, debe mantener el siguiente orden.
                $data = Array($f_accion,$accion,$id_cliente->Cliente,$proceso,$servicio,$id_evento,$id);

                $acciones_automaticas = new AccionesAutomaticas();
                    //Despacha las acciones a ejecutar
                    $acciones_automaticas->registrarAccion([
                        'MovimientosAutomaticos' => \App\Services\MovimientosAutomaticas::class,
                        'AlertasNaranjas' => \App\Services\AlertasNaranjas::class,
                    ])->with($data)->llamarAcciones();

                    $estado_ejecucion[] = [
                        'idevento' => $values["id_evento"],
                        'idasignacion' => $id,
                        'detalles' => $acciones_automaticas->response
                    ];
                    
            }
        }

        return $estado_ejecucion;

    }

    /**
     * Valida si una accion a ejecutar debe ser enviada a la bandeja de notificaciones
     * @param int Id del cliente al cual la accion se encuentra asociada
     * @param int Servicio asociado a la accion a ejecutar
     * @param int Proceso asociado a la accion a ejecutar
     * @param int Id de la accion a ejecutar
     * @return string Si|No
     */
    public static function ingresar_notificacion(int $id_cliente,int $servicio,int $id_proceso,int $accion_ejecutar): string {
        $estado_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.Estado', 'sipc.Enviar_a_bandeja_trabajo_destino as enviarA')
        ->where([
            ['sipc.Id_proceso', '=', $id_proceso],
            ['sipc.Servicio_asociado', '=', $servicio],
            ['sipc.Accion_ejecutar','=',  $accion_ejecutar],
            ['sipc.Cliente','=',  $id_cliente]
        ])->get();

        return $estado_parametrica->enviarA ?? 'No';

    }

}

