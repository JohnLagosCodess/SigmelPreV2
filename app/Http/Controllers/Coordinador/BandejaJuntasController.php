<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
//llamado de modelos para formulario BandejaOrigen y captura de data
use App\Models\sigmel_lista_procesos_servicios;
use App\Models\cndatos_bandeja_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;

use App\Models\sigmel_informacion_parametrizaciones_clientes;
use App\Models\sigmel_informacion_acciones;
use App\Models\sigmel_informacion_alertas_automaticas_eventos;
use App\Models\sigmel_informacion_historial_accion_eventos;
use App\Models\sigmel_numero_orden_eventos;

use App\Models\sigmel_informacion_alertas_ans_eventos;

class BandejaJuntasController extends Controller
{
    // Bandeja Origen Coordinador
    public function mostrarVistaBandejaJuntas(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();        

        return view('coordinador.bandejaJuntas', compact('user'));
    }

     //Selectores Bandeja Juntas
     public function cargueListadoSelectoresBandejaJuntas(Request $request){
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
        
        //Listado servicio proceso Juntas
        if($parametro == 'lista_servicios_juntas'){
            $listado_servicio_Juntas = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                // ['Nombre_proceso', '=', 'Juntas'],
                ['Id_proceso', '=', $request->id_proceso],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_Juntas = json_decode(json_encode($listado_servicio_Juntas, true));
            return response()->json($info_listado_servicio_Juntas);
        }

        // listado de acciones
        if ($parametro == 'listado_accion') {
            // $array_Id_asignacion = $request->Id_asignacion;

            /* Iniciamos trayendo las acciones a ejecutar configuradas en la tabla de parametrizaciones
            dependiendo del id del cliente, id del proceso, id del servicio, estado activo */
            
            // $array_id_cliente = sigmel_informacion_eventos::on('sigmel_gestiones')
            // ->select('Cliente')->where('ID_evento', $request->nro_evento)->first();

            // $id_cliente = $array_id_cliente["Cliente"];

            $acciones_a_ejecutar = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Accion_ejecutar')
            ->where([
                // ['sipc.Id_cliente', '=', $id_cliente],
                ['sipc.Id_proceso', '=', $request->Id_proceso],
                ['sipc.Servicio_asociado', '=', $request->Id_servicio],
                ['sipc.Bandeja_trabajo', '=', 'Si'],
                ['sipc.Status_parametrico', '=', 'Activo']
            ])->get();

            $info_acciones_a_ejecutar = json_decode(json_encode($acciones_a_ejecutar, true));
            // echo "<pre>"; print_r($info_acciones_a_ejecutar); echo "</pre>";
            if (count($info_acciones_a_ejecutar) > 0) {
                // Extraemos las acciones antecesoras a partir de las acciones a ejecutar
                $array_acciones_ejecutar = [];
                for ($i=0; $i < count($info_acciones_a_ejecutar); $i++) { 
                    array_push($array_acciones_ejecutar, $info_acciones_a_ejecutar[$i]->Accion_ejecutar);
                };

                $extraccion_acciones_antecesoras = sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
                ->select('Accion_ejecutar','Accion_antecesora')
                ->where([
                    // ['Id_cliente', '=', $id_cliente],
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
                                // ['saiae.Aud_Id_Asignacion', '=', $request->Id_asignacion],
                                // ['saiae.Aud_ID_evento', '=', $request->nro_evento],
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

        // listado de profesionales para el proceso Juntas
        if ($parametro == 'lista_profesional_juntas') {
            
            $id_proceso = $request->Id_proceso;
            $id_servicio = $request->Id_servicio;
            $id_accion = $request->Id_accion;

            /* Extraemos el equippo de trabajo y el profesional asignado configurados en la paramétrica */
            $info_equipo_prof_asig = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
            ->select('sipc.Equipo_trabajo', 'sipc.Profesional_asignado')
            ->where([
                // ['sipc.Id_cliente', '=', $id_cliente],
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

                $listado_profesional_juntas = DB::table('users')->select('id', 'name')
                ->where('estado', 'Activo')
                ->whereRaw("FIND_IN_SET(3, id_procesos_usuario) > 0")->get();
                
                $info_listado_profesional_Juntas = json_decode(json_encode($listado_profesional_juntas, true));
                // return response()->json($info_listado_profesional_Juntas);

                return response()->json([
                    'info_listado_profesionales' => $info_listado_profesional_Juntas,
                    'Profesional_asignado' => ''
                ]);
            }


        }

    }

    public function sinFiltroBandejaJuntas(Request $request){

        $BandejaJuntasTotal = $request->BandejaJuntasTotal;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user;

        $time = time();
        $date = date("Y-m-d", $time);
        $year = date("Y");

        if($BandejaJuntasTotal == 'CargaBandejaJuntas'){

            if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                $bandejaJuntas = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where([
                    ['Nombre_proceso_actual', '=', 'Juntas'],
                    ['Id_profesional', '=', $newId_user],
                ])->where(function($query){
                    $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                })
                ->orderByRaw("
                    CASE 
                        WHEN Fecha_alerta = 'VENCIDO' THEN 1
                        WHEN Fecha_alerta = 'PRÓXIMO A VENCER' THEN 2
                        WHEN Fecha_alerta = 'VIGENTE' THEN 3
                    END, F_vencimiento ASC
                ")
                //->whereBetween('F_registro_asignacion', [$year.'-01-01' , $date])
                ->get();
            }else{
                $bandejaJuntas = cndatos_bandeja_eventos::on('sigmel_gestiones')
                ->where([
                    ['Nombre_proceso_actual', '=', 'Juntas']
                ])->where(function($query){
                    $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                })
                // ->whereBetween('F_registro_asignacion', [$year.'-01-01' , $date])
                ->whereBetween('F_registro_asignacion', ['2024-07-01' , $date])
                ->orderByRaw("
                    CASE 
                        WHEN Fecha_alerta = 'VENCIDO' THEN 1
                        WHEN Fecha_alerta = 'PRÓXIMO A VENCER' THEN 2
                        WHEN Fecha_alerta = 'VIGENTE' THEN 3
                    END, F_vencimiento ASC
                ")
                ->get();

            }
            // $bandejaJuntassin_Pro_ant = cndatos_bandeja_eventos::on('sigmel_gestiones')
            // ->where([
            //     ['Nombre_proceso_actual', '=', 'Juntas']
            // ])
            // ->whereNull('Nombre_proceso_anterior');

            // $bandejaJuntas = cndatos_bandeja_eventos::on('sigmel_gestiones')
            // ->where([
            //     ['Nombre_proceso_actual', '=', 'Juntas'],
            //     ['Id_proceso_anterior', '<>', 3]
            // ])
            // ->union($bandejaJuntassin_Pro_ant)
            // ->get();

            // $Ids_Nombre_proceso_anterior = response()->json([]);
            
            // foreach ($bandejaJuntas as $item) {
            //     // Accede a cada propiedad del objeto dentro del bucle
            //     $Id_Asignacion_bandeja = $item->Id_Asignacion;
            //     $ID_evento_bandeja = $item->ID_evento;
            //     $Id_proceso_bandeja = $item->Id_proceso;

            //     $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            //     ->select('Id_proceso')
            //     ->where([['ID_evento', $ID_evento_bandeja], ['Id_Asignacion', '<', $Id_Asignacion_bandeja]])
            //     ->orderBy('Id_Asignacion', 'desc')
            //     ->limit(1)
            //     ->get();
            //     //echo $validar_proceso_anterior[0]->Id_proceso;
            //     if (count($validar_proceso_anterior) > 0) {                    
            //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         ->select('Nombre_proceso')->where([['Id_proceso', $validar_proceso_anterior[0]->Id_proceso]])
            //         ->limit(1)->get();
    
            //         //echo $validar_Nombre_proceso_anterior[0]->Nombre_proceso;
    
            //         $Ids_Nombre_proceso_anterior = response()->json([
            //             'Id_Proceso_anterior' => $validar_proceso_anterior[0]->Id_proceso,
            //             'Nombre_proceso_anterior' => $validar_Nombre_proceso_anterior[0]->Nombre_proceso,
            //         ]);
    
            //         $Ids_Nombre_proceso_anterior_array = json_decode($Ids_Nombre_proceso_anterior->getContent(), true);
                    
            //         $arraybandejaJuntas = json_decode(json_encode($bandejaJuntas, true));
    
            //         $arraybandejaJuntas[0]->Id_Proceso_anterior = $Ids_Nombre_proceso_anterior_array['Id_Proceso_anterior'];
            //         $arraybandejaJuntas[0]->Nombre_proceso_anterior = $Ids_Nombre_proceso_anterior_array['Nombre_proceso_anterior'];
            //     } else {
            //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         ->select('Nombre_proceso')->where([['Id_proceso', $Id_proceso_bandeja]])
            //         ->limit(1)->get(); 

            //         $Ids_Nombre_proceso_anterior = response()->json([
            //             'Id_Proceso_anterior' => $Id_proceso_bandeja,
            //             'Nombre_proceso_anterior' => $validar_Nombre_proceso_anterior[0]->Nombre_proceso,
            //         ]);
    
            //         $Ids_Nombre_proceso_anterior_array = json_decode($Ids_Nombre_proceso_anterior->getContent(), true);

            //         $arraybandejaJuntas = json_decode(json_encode($bandejaJuntas, true));
                    
            //         $arraybandejaJuntas[0]->Id_Proceso_anterior = $Ids_Nombre_proceso_anterior_array['Id_Proceso_anterior'];
            //         $arraybandejaJuntas[0]->Nombre_proceso_anterior = $Ids_Nombre_proceso_anterior_array['Nombre_proceso_anterior'];
            //     }
            // }
            
            $arraybandejaJuntas = json_decode(json_encode($bandejaJuntas, true));
            return response()->json($arraybandejaJuntas);

        }
    }

    public function filtrosBandejaJuntas(Request $request){
        
        $consultar_f_desde = $request->consultar_f_desde;
        $consultar_f_hasta = $request->consultar_f_hasta;
        $consultar_g_dias = $request->consultar_g_dias;
        $newId_rol = $request->newId_rol; 
        $newId_user = $request->newId_user; 
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                if($newId_rol=='5' || $newId_rol=='10'|| $newId_rol == '3'){ // si el rol es analista o profesional o comité
                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ['Id_profesional', '=', $newId_user]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                    })
                    ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                    ->orderByRaw("
                        CASE 
                            WHEN Fecha_alerta = 'VENCIDO' THEN 1
                            WHEN Fecha_alerta = 'PRÓXIMO A VENCER' THEN 2
                            WHEN Fecha_alerta = 'VIGENTE' THEN 3
                        END, F_vencimiento ASC
                    ")
                    ->get();
                }else{
                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                    })
                    ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                    ->orderByRaw("
                        CASE 
                            WHEN Fecha_alerta = 'VENCIDO' THEN 1
                            WHEN Fecha_alerta = 'PRÓXIMO A VENCER' THEN 2
                            WHEN Fecha_alerta = 'VIGENTE' THEN 3
                        END, F_vencimiento ASC
                    ")
                    ->get();
                }
                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);
                    
                    // $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Juntas'],
                    //         ['Id_proceso_anterior', '<>', 3],
                    //         ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    //     ])            
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    // ->union($bandejaJuntassin_Pro_ant)
                    // ->get();
            
                    $arraybandejaJuntasFiltros = json_decode(json_encode($bandejaJuntasFiltros, true));
                    if (count($arraybandejaJuntasFiltros)>0) {
                        return response()->json($arraybandejaJuntasFiltros);                        
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
                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas'],
                        ['Id_profesional', '=', $newId_user]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                    })
                    ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                    ->orderByRaw("
                        CASE 
                            WHEN Fecha_alerta = 'VENCIDO' THEN 1
                            WHEN Fecha_alerta = 'PRÓXIMO A VENCER' THEN 2
                            WHEN Fecha_alerta = 'VIGENTE' THEN 3
                        END, F_vencimiento ASC
                    ")
                    ->get();
                
                }else{
                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas'],
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                    })
                    ->whereBetween(DB::raw('DATE(F_accion)'), [$consultar_f_desde ,$consultar_f_hasta])
                    ->orderByRaw("
                        CASE 
                            WHEN Fecha_alerta = 'VENCIDO' THEN 1
                            WHEN Fecha_alerta = 'PRÓXIMO A VENCER' THEN 2
                            WHEN Fecha_alerta = 'VIGENTE' THEN 3
                        END, F_vencimiento ASC
                    ")
                    ->get();
                }

                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);

                    // $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Juntas'],
                    //         ['Id_proceso_anterior', '<>', 3],
                    //     ])            
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    // ->union($bandejaJuntassin_Pro_ant)
                    // ->get();                    

                    $arraybandejaJuntasFiltros = json_decode(json_encode($bandejaJuntasFiltros, true));
                    if (count($arraybandejaJuntasFiltros)>0) {
                        return response()->json($arraybandejaJuntasFiltros);
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
                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                        ['Id_profesional', '=', $newId_user]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                    })
                    ->orderByRaw("
                        CASE 
                            WHEN Fecha_alerta = 'VENCIDO' THEN 1
                            WHEN Fecha_alerta = 'PRÓXIMO A VENCER' THEN 2
                            WHEN Fecha_alerta = 'VIGENTE' THEN 3
                        END, F_vencimiento ASC
                    ")
                    ->get();
                }else{
                    $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Juntas'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])->where(function($query){
                        $query->whereNull('Enviar_bd_Notificacion')->orWhere('Enviar_bd_Notificacion', '=', 'No');
                    })
                    ->orderByRaw("
                        CASE 
                            WHEN Fecha_alerta = 'VENCIDO' THEN 1
                            WHEN Fecha_alerta = 'PRÓXIMO A VENCER' THEN 2
                            WHEN Fecha_alerta = 'VIGENTE' THEN 3
                        END, F_vencimiento ASC
                    ")
                    ->get();

                }
                    // ->whereNull('Nombre_proceso_anterior');
                    
                    // $bandejaJuntasFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Juntas'],
                    //         ['Id_proceso_anterior', '<>', 3],
                    //         ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    //     ])            
                    // ->union($bandejaJuntassin_Pro_ant)
                    // ->get();
                
                    $arraybandejaJuntasFiltros = json_decode(json_encode($bandejaJuntasFiltros, true));
                    if (count($arraybandejaJuntasFiltros)>0) {
                        return response()->json($arraybandejaJuntasFiltros);
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

    /* función para las alertas de la parametrica */
    public function alertaNaranjasRojasJuntas(Request $request) {
        $alertas = sigmel_informacion_alertas_automaticas_eventos::on('sigmel_gestiones')
        ->where([['Estado_alerta_automatica', '=', 'Ejecucion']])
        ->get();
        return response()->json(['data' => $alertas]);
    }

    /* Función para consultar si hay un ANS ejecutado dependiendo de su Id de asignacion */
    public function consultaANSejecutado(Request $request){

        $id_asignacion = $request->id_asignacion;

        $alertas_ans = sigmel_informacion_alertas_ans_eventos::on('sigmel_gestiones')
        ->where([['Id_Asignacion', $id_asignacion]])
        ->get();

        $array_alertas_ans = json_decode(json_encode($alertas_ans, true));
        return response()->json($array_alertas_ans);
    }

    public function actualizarBandejaJuntas(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $usuario = Auth::user()->name;        
        $time = time();
        $date = date("Y-m-d", $time);
        $date_con_hora = date("Y-m-d h:i:s", $time);
        $date_time = date("Y-m-d H:i:s");

        $IdEventoBandejaJuntas = $request->array;
        $Id_proceso = $request->json['proceso_parametrizado'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];
        $Id_accion = $request->json['accion'];
        $Id_profesional = $request->json['profesional'];
        $Descripcion_bandeja = $request->json['descripcion_bandeja'];

        // Paso N°1: Extraemos el id estado de la tabla de parametrizaciones dependiendo del
        // id proceso, id servicio, id accion. Este id irá como estado  en el evento
        $estado_acorde_a_parametrica = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->select('sipc.Estado', 'sipc.Enviar_a_bandeja_trabajo_destino as enviarA')
        ->where([
            // ['sipc.Id_cliente', '=', $request->cliente],
            ['sipc.Id_proceso', '=', $Id_proceso],
            ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
            ['sipc.Accion_ejecutar','=',  $Id_accion]
        ])->get();


        if(count($estado_acorde_a_parametrica)>0){
            $Id_Estado_evento = $estado_acorde_a_parametrica[0]->Estado;
        }else{
            $Id_Estado_evento = 223;
        }

        // Paso N°2: Obtenemos los id del proceso y servicio anteriores dependiendo del o los id de asignacion
        $array_id_procesos = [];
        $array_id_servicios = [];
        for ($a=0; $a < count($IdEventoBandejaJuntas); $a++) { 
            $array_ids = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('Id_proceso', 'Id_servicio')
            ->where('Id_Asignacion', $IdEventoBandejaJuntas[$a])->get();

            $info_array_ids = json_decode(json_encode($array_ids, true));

            array_push($array_id_procesos, $info_array_ids[0]->Id_proceso);
            array_push($array_id_servicios, $info_array_ids[0]->Id_servicio);
        }

        // Paso N°3: Obtenemos el nombre del profesional y se setea el dato de F_asignacion_calificacion
        if (!empty($Id_profesional)) {
            $profesional = DB::table('users')
            ->select('name')->where('id',$Id_profesional)
            ->get();

            $nombre = json_decode(json_encode($profesional));
            $nombre_profesional= $nombre[0]->name;
            $F_asignacion_calificacion = $date_con_hora;
        }else{
            $Id_profesional = null;
            $nombre_profesional = null;
            $F_asignacion_calificacion = null;
        }

        // Paso N°4: Armado de datos
        $array_datos_finales_actualizar = [];
        for ($m=0; $m < count($IdEventoBandejaJuntas); $m++) {
            switch (true) {
                // CASO 1: Id asignacion no es vacio y id profesional no es vacio y id servicio no es vacio
                case (!empty($IdEventoBandejaJuntas) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
            
                    /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
                    $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
                    ->select('sipc.Detiene_tiempo_gestion')
                    ->where([
                        // ['sipc.Id_cliente', '=', $id_cliente],
                        ['sipc.Id_proceso', '=', $Id_proceso],
                        ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
                        ['sipc.Accion_ejecutar', '=', $Id_accion]
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

                    $actualizar_bandejaJuntas = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'F_asignacion_calificacion' => $F_asignacion_calificacion,
                        'Id_profesional' =>  $Id_profesional,
                        'Nombre_profesional' => $nombre_profesional,
                        'Descripcion_bandeja' => $Descripcion_bandeja,
                        'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',
                        //'N_de_orden' => $N_orden_evento,
                        'Nombre_usuario' => $usuario,
                        'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                        'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion
                    ]; 

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaJuntas);
                
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
                
                    // return json_decode(json_encode($mensajes, true));
                    
                break;
                // CASO 2: Id asignacion no es vacio y id profesional es vacio y id servicio no es vacio
                case (!empty($IdEventoBandejaJuntas) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
    
                    /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
                    $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
                    ->select('sipc.Detiene_tiempo_gestion')
                    ->where([
                        // ['sipc.Id_cliente', '=', $id_cliente],
                        ['sipc.Id_proceso', '=', $Id_proceso],
                        ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
                        ['sipc.Accion_ejecutar', '=', $Id_accion]
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

                    $actualizar_bandejaJuntas_Servicio = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',
                        //'N_de_orden' => $N_orden_evento,
                        'Nombre_usuario' => $usuario,
                        'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                        'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion
                    ];

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaJuntas_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    // return json_decode(json_encode($mensajes, true));
    
                break;
                // CASO 3: Id asignacion no es vacio y id profesional no es vacio y id servicio es vacio
                case (!empty($IdEventoBandejaJuntas) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
    
                    /* Verificación de que el check de detiene tiempo gestion este en sí acorde a la paramétrica */
                    $casilla_detiene_tiempo_gestion = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
                    ->select('sipc.Detiene_tiempo_gestion')
                    ->where([
                        // ['sipc.Id_cliente', '=', $id_cliente],
                        ['sipc.Id_proceso', '=', $Id_proceso],
                        ['sipc.Servicio_asociado', '=', $Id_Servicio_redireccionar],
                        ['sipc.Accion_ejecutar', '=', $Id_accion]
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
                    
                    $actualizar_bandejaJuntas_Profesional = [
                        'Id_proceso' => $Id_proceso,
                        'Id_servicio' => $Id_Servicio_redireccionar,
                        'Id_accion' => $Id_accion,
                        'Id_estado_evento' => $Id_Estado_evento,
                        'Id_proceso_anterior' => $array_id_procesos[$m],
                        'Id_servicio_anterior' => $array_id_servicios[$m],
                        'F_asignacion_calificacion' => $F_asignacion_calificacion,
                        'Id_profesional' =>  $Id_profesional,
                        'Nombre_profesional' => $nombre_profesional,
                        'Descripcion_bandeja' => $Descripcion_bandeja,
                        'Notificacion' => isset($estado_acorde_a_parametrica[0]->enviarA) ? $estado_acorde_a_parametrica[0]->enviarA : 'No',
                        //'N_de_orden' => $N_orden_evento,
                        'Nombre_usuario' => $usuario,
                        'Detener_tiempo_gestion' => $Detener_tiempo_gestion,
                        'F_detencion_tiempo_gestion' => $F_detencion_tiempo_gestion
                    ]; 

                    // Insertamos los datos en un array para luego realizar la actualización
                    array_push($array_datos_finales_actualizar, $actualizar_bandejaJuntas_Profesional);
    
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    // return json_decode(json_encode($mensajes, true));
    
                break;
                // CASO 4: Id asignacion no es vacio y id profesional es vacio y id servicio es vacio
                case (!empty($IdEventoBandejaJuntas) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_Juntas',
                        "mensaje" => 'Debe seleccionar el Profesional o Redireccionar a, para Actualizar'
                    );
    
                    // return json_decode(json_encode($mensajes, true));
                break;
                
                default:                
                break;
            }
        };

        // Paso N° 5: Actualización de la información
        for ($b=0; $b < count($array_datos_finales_actualizar); $b++) { 
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $IdEventoBandejaJuntas[$b])
            ->update($array_datos_finales_actualizar[$b]);
        }
        $array_datos_finales_actualizar = [];

        sleep(2);
        
        // Capturar todos los eventos de los id de asignacion y almacenarlo en array array_id_asignacion
        $array_id_asignacion = [];
        for ($a=0; $a < count($IdEventoBandejaJuntas); $a++) {
            $array_datos_asignacion = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            ->select('Id_Asignacion', 'ID_evento')->where('Id_Asignacion', $IdEventoBandejaJuntas[$a])->get();
            // Convertir la colección a un array de objetos stdClass
            $info_array_asignacion = $array_datos_asignacion->toArray();
            // Verificar que el array no esté vacío y contenga al menos un elemento
            if (!empty($info_array_asignacion)) {
                $array_id_asignacion[] = [
                    $info_array_asignacion[0]['Id_Asignacion'], 
                    $info_array_asignacion[0]['ID_evento']
                ];

                //Habilita edicion del proceso de correspodencia para el proceso de notificacion.
                if(isset($estado_acorde_a_parametrica[0]->enviarA)){
                    BandejaNotifiController::finalizarNotificacion($info_array_asignacion[0]['ID_evento'], $info_array_asignacion[0]['Id_Asignacion'],false,true);
                }
            }

        }        
        // Captura de informacion de los campos de la bandeja que se van actualizar
        $datos_historial_accion_eventos = [
            'Id_proceso' => $Id_proceso,
            'Id_servicio' => $Id_Servicio_redireccionar,
            'Id_accion' => $Id_accion,
            'Documento' => 'N/A',
            'Descripcion' => $Descripcion_bandeja,
            'F_accion' => $date_time,
            'Nombre_usuario' => $usuario,
        ];
        
        // Construir array para la insercion
        $array_datos_historial_accion_eventos = array();
        foreach ($array_id_asignacion as $asignacion) {
            // Crear un array combinando Id_Asignacion y ID_evento
            $asignacion_array = [
                'Id_Asignacion' => $asignacion[0],
                'ID_evento' => $asignacion[1]
            ];            
            // Fusionar el array de asignación con los datos de historial
            $item = array_merge($asignacion_array, $datos_historial_accion_eventos);            
            // Agregar el item al array de resultados
            $array_datos_historial_accion_eventos[] = $item;
        }
        
        foreach ($array_datos_historial_accion_eventos as $historial) {
            sigmel_informacion_historial_accion_eventos::on('sigmel_gestiones')
            ->insert($historial);
        }
        return json_decode(json_encode($mensajes, true));

        /* $profesional = DB::table('users')
        ->select('name')->where('id',$Id_profesional)
        ->get();
        if (count($profesional) > 0) {
            $nombre = json_decode(json_encode($profesional));
            $nombre_profesional= $nombre[0]->name; 

            $actualizar_bandejaJuntas = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional,
                'Id_servicio' => $Id_Servicio_redireccionar
            ];       

            $actualizar_bandejaJuntas_Profesional = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional
            ]; 
        }else{
            $actualizar_bandejaJuntas_Servicio = [
                'Nombre_usuario' => $usuario,
                'Id_servicio' => $Id_Servicio_redireccionar
            ]; 
        }
        
        switch (true) {
            case (!empty($IdEventoBandejaJuntas) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
        
                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaJuntas)
                    ->update($actualizar_bandejaJuntas);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
            
                    return json_decode(json_encode($mensajes, true));
                
            break;
            case (!empty($IdEventoBandejaJuntas) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaJuntas)
                    ->update($actualizar_bandejaJuntas_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;
            
            case (!empty($IdEventoBandejaJuntas) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaJuntas)
                    ->update($actualizar_bandejaJuntas_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Juntas',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;

            case (!empty($IdEventoBandejaJuntas) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_Juntas',
                        "mensaje" => 'Debe seleccionar el Profesional o Redireccionar a, para Actualizar'
                    );

                    return json_decode(json_encode($mensajes, true));
            break;
            
            default:                
            break;
        } */
        
    }
}
