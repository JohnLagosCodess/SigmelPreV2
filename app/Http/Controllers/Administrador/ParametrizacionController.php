<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Modelos
use App\Models\sigmel_informacion_servicios_contratados;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_grupos_trabajos;
use App\Models\sigmel_informacion_parametrizaciones_clientes;

class ParametrizacionController extends Controller
{
    public function mostrarVistaParametrizacion(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;

        $Id_cliente = $request->Id_cliente;

        /* Validación de que el cliente tenga por lo menos un servicio contratado del proceso ORIGEN */
        $conteo_servicios_proceso_origen_atel = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
        // ->select(DB::raw('count(*) as numero_servicios'))
        ->where([
            ['sisc.Id_cliente', $Id_cliente],
            ['sisc.Id_proceso', '=', '1']
        ])->count();

        /* Validación de que el cliente tenga por lo menos un servicio contratado del proceso CALIFICACIÓN PCL */
        $conteo_servicios_proceso_calificacion_pcl = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
        // ->select(DB::raw('count(*) as numero_servicios'))
        ->where([
            ['sisc.Id_cliente', $Id_cliente],
            ['sisc.Id_proceso', '=', '2']
        ])->count();

        /* Validación de que el cliente tenga por lo menos un servicio contratado del proceso JUNTAS */
        $conteo_servicios_proceso_juntas = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
        // ->select(DB::raw('count(*) as numero_servicios'))
        ->where([
            ['sisc.Id_cliente', $Id_cliente],
            ['sisc.Id_proceso', '=', '3']
        ])->count();

        // Traemos la información de las parametrizaciones del proceso origen atel
        $listado_parametrizaciones_proceso_origen_atel =  DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sipc.Servicio_asociado', '=', 'slps.Id_Servicio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'sipc.Estado', '=', 'slp.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia', 'sipc.Accion_ejecutar', '=', 'sia.Id_Accion')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia2', 'sipc.Accion_antecesora', '=', 'sia2.Id_Accion')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps2', 'sipc.Bandeja_trabajo_destino', '=', 'slps2.Id_proceso')
        ->leftJoin('sigmel_gestiones.sigmel_grupos_trabajos as sgt', 'sipc.Equipo_trabajo', '=', 'sgt.id')
        ->leftJoin('sigmel_sys.users as u', 'sipc.Profesional_asignado', '=', 'u.id')
        ->select(
            'sipc.Id_parametrizacion',
            'sipc.Id_cliente',
            'sipc.Id_proceso',
            'sipc.F_creacion_movimiento',
            'sipc.Servicio_asociado',
            'slps.Nombre_servicio',
            'sipc.Estado',
            'slp.Nombre_parametro as Nombre_estado',
            'sipc.Accion_ejecutar',
            'sia.Accion as Nombre_accion',
            'sipc.Accion_antecesora',
            'sia2.Accion as Nombre_accion_antecesora',
            'sipc.Modulo_nuevo',
            'sipc.Modulo_consultar',
            'sipc.Bandeja_trabajo',
            'sipc.Modulo_principal',
            'sipc.Detiene_tiempo_gestion',
            'sipc.Equipo_trabajo',
            'sgt.nombre as Nombre_equipo_trabajo',
            'sipc.Profesional_asignado',
            'u.name as Nombre_profesional',
            'sipc.Enviar_a_bandeja_trabajo_destino',
            'sipc.Bandeja_trabajo_destino',
            'slps2.Nombre_proceso as Nombre_bandeja_trabajo_destino',
            'sipc.Estado_facturacion',
            'sipc.Tiempo_alerta',
            'sipc.Porcentaje_alerta_naranja',
            'sipc.Porcentaje_alerta_roja',
            'sipc.Status_parametrico',
            'sipc.Motivo_descripcion_movimiento',
            'sipc.Nombre_usuario',
            'sipc.F_actualizacion_movimiento'
        )->where([
            ['sipc.Id_cliente', $Id_cliente],
            ['sipc.Id_proceso', '1']
        ])->groupBy('sipc.Id_parametrizacion')->get();

        // Conteo de movimientos activos e inactivos del proceso origen atel
        $conteo_activos_inactivos_parametrizaciones_origen_atel = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes')
        ->select(DB::raw("COUNT(IF(Status_parametrico = 'Activo', 1, NULL)) AS 'Activos'"), DB::raw("COUNT(IF(Status_parametrico = 'Inactivo', 1, NULL)) AS 'Inactivos'"))
        ->where([
            ['Id_cliente', $Id_cliente],
            ['Id_proceso', '1']
        ])
        ->get();

        // Traemos la información de las parametrizaciones del proceso calificación pcl
        $listado_parametrizaciones_proceso_calificacion_pcl =  DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sipc.Servicio_asociado', '=', 'slps.Id_Servicio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'sipc.Estado', '=', 'slp.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia', 'sipc.Accion_ejecutar', '=', 'sia.Id_Accion')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia2', 'sipc.Accion_antecesora', '=', 'sia2.Id_Accion')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps2', 'sipc.Bandeja_trabajo_destino', '=', 'slps2.Id_proceso')
        ->leftJoin('sigmel_gestiones.sigmel_grupos_trabajos as sgt', 'sipc.Equipo_trabajo', '=', 'sgt.id')
        ->leftJoin('sigmel_sys.users as u', 'sipc.Profesional_asignado', '=', 'u.id')
        ->select(
            'sipc.Id_parametrizacion',
            'sipc.Id_cliente',
            'sipc.Id_proceso',
            'sipc.F_creacion_movimiento',
            'sipc.Servicio_asociado',
            'slps.Nombre_servicio',
            'sipc.Estado',
            'slp.Nombre_parametro as Nombre_estado',
            'sipc.Accion_ejecutar',
            'sia.Accion as Nombre_accion',
            'sipc.Accion_antecesora',
            'sia2.Accion as Nombre_accion_antecesora',
            'sipc.Modulo_nuevo',
            'sipc.Modulo_consultar',
            'sipc.Bandeja_trabajo',
            'sipc.Modulo_principal',
            'sipc.Detiene_tiempo_gestion',
            'sipc.Equipo_trabajo',
            'sgt.nombre as Nombre_equipo_trabajo',
            'sipc.Profesional_asignado',
            'u.name as Nombre_profesional',
            'sipc.Enviar_a_bandeja_trabajo_destino',
            'sipc.Bandeja_trabajo_destino',
            'slps2.Nombre_proceso as Nombre_bandeja_trabajo_destino',
            'sipc.Estado_facturacion',
            'sipc.Tiempo_alerta',
            'sipc.Porcentaje_alerta_naranja',
            'sipc.Porcentaje_alerta_roja',
            'sipc.Status_parametrico',
            'sipc.Motivo_descripcion_movimiento',
            'sipc.Nombre_usuario',
            'sipc.F_actualizacion_movimiento'
        )->where([
            ['sipc.Id_cliente', $Id_cliente],
            ['sipc.Id_proceso', '2']
        ])->groupBy('sipc.Id_parametrizacion')->get();

        // Conteo de movimientos activos e inactivos del proceso calificacion pcl
        $conteo_activos_inactivos_parametrizaciones_calificacion_pcl = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes')
        ->select(DB::raw("COUNT(IF(Status_parametrico = 'Activo', 1, NULL)) AS 'Activos'"), DB::raw("COUNT(IF(Status_parametrico = 'Inactivo', 1, NULL)) AS 'Inactivos'"))
        ->where([
            ['Id_cliente', $Id_cliente],
            ['Id_proceso', '2']
        ])
        ->get();

        // Traemos la información de las parametrizaciones del proceso juntas
        $listado_parametrizaciones_proceso_juntas =  DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes as sipc')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sipc.Servicio_asociado', '=', 'slps.Id_Servicio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'sipc.Estado', '=', 'slp.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia', 'sipc.Accion_ejecutar', '=', 'sia.Id_Accion')
        ->leftJoin('sigmel_gestiones.sigmel_informacion_acciones as sia2', 'sipc.Accion_antecesora', '=', 'sia2.Id_Accion')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps2', 'sipc.Bandeja_trabajo_destino', '=', 'slps2.Id_proceso')
        ->leftJoin('sigmel_gestiones.sigmel_grupos_trabajos as sgt', 'sipc.Equipo_trabajo', '=', 'sgt.id')
        ->leftJoin('sigmel_sys.users as u', 'sipc.Profesional_asignado', '=', 'u.id')
        ->select(
            'sipc.Id_parametrizacion',
            'sipc.Id_cliente',
            'sipc.Id_proceso',
            'sipc.F_creacion_movimiento',
            'sipc.Servicio_asociado',
            'slps.Nombre_servicio',
            'sipc.Estado',
            'slp.Nombre_parametro as Nombre_estado',
            'sipc.Accion_ejecutar',
            'sia.Accion as Nombre_accion',
            'sipc.Accion_antecesora',
            'sia2.Accion as Nombre_accion_antecesora',
            'sipc.Modulo_nuevo',
            'sipc.Modulo_consultar',
            'sipc.Bandeja_trabajo',
            'sipc.Modulo_principal',
            'sipc.Detiene_tiempo_gestion',
            'sipc.Equipo_trabajo',
            'sgt.nombre as Nombre_equipo_trabajo',
            'sipc.Profesional_asignado',
            'u.name as Nombre_profesional',
            'sipc.Enviar_a_bandeja_trabajo_destino',
            'sipc.Bandeja_trabajo_destino',
            'slps2.Nombre_proceso as Nombre_bandeja_trabajo_destino',
            'sipc.Estado_facturacion',
            'sipc.Tiempo_alerta',
            'sipc.Porcentaje_alerta_naranja',
            'sipc.Porcentaje_alerta_roja',
            'sipc.Status_parametrico',
            'sipc.Motivo_descripcion_movimiento',
            'sipc.Nombre_usuario',
            'sipc.F_actualizacion_movimiento'
        )->where([
            ['sipc.Id_cliente', $Id_cliente],
            ['sipc.Id_proceso', '3']
        ])->groupBy('sipc.Id_parametrizacion')->get();

        // Conteo de movimientos activos e inactivos del proceso juntas
        $conteo_activos_inactivos_parametrizaciones_juntas = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_parametrizaciones_clientes')
        ->select(DB::raw("COUNT(IF(Status_parametrico = 'Activo', 1, NULL)) AS 'Activos'"), DB::raw("COUNT(IF(Status_parametrico = 'Inactivo', 1, NULL)) AS 'Inactivos'"))
        ->where([
            ['Id_cliente', $Id_cliente],
            ['Id_proceso', '3']
        ])
        ->get();

        return view('administrador.parametrizacion', compact('user', 'Id_cliente', 'nombre_usuario', 'conteo_servicios_proceso_origen_atel', 'conteo_servicios_proceso_calificacion_pcl', 'conteo_servicios_proceso_juntas',
        'listado_parametrizaciones_proceso_origen_atel', 
        'conteo_activos_inactivos_parametrizaciones_origen_atel', 
        'listado_parametrizaciones_proceso_calificacion_pcl', 
        'conteo_activos_inactivos_parametrizaciones_calificacion_pcl', 
        'listado_parametrizaciones_proceso_juntas',
        'conteo_activos_inactivos_parametrizaciones_juntas'));
    }

    public function CargueSelectoresParametrizar(Request $request){
        $parametro = $request->parametro;
        $Id_cliente = $request->Id_cliente;

        /* INFORMACIÓN PARA TRAER DE TODO LO RELACIONADO AL PROCESO ORIGEN ATEL */
        // servicios asociados
        if ($parametro == "servicios_asociados_proceso_origen_atel") {
            $servicios_proceso_origen_atel = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
            ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
            ->select('sisc.Id_servicio', 'slps.Nombre_servicio')
            ->where([
                ['sisc.Id_cliente', $Id_cliente],
                ['sisc.Id_proceso', '=', '1']
            ])->get();

            $informacion_servicios_proceso_origen_atel = json_decode(json_encode($servicios_proceso_origen_atel), true);
            return response()->json($informacion_servicios_proceso_origen_atel);
        }

        // listado de estados
        if ($parametro == "lista_estados") {
            $listado_estados = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Acciones'],
                ['Estado', '=', 'activo'],
            ])->get();

            $info_lista_estados = json_decode(json_encode($listado_estados, true));
            return response()->json($info_lista_estados);
        };

        // Acciones a ejecutar
        if ($parametro == "acciones_ejecutar_proceso_origen_atel") {
            $id_accion_seleccionada_origen_atel = $request->id_accion_seleccionada_origen_atel;
            $acciones_ejecutar_proceso_origen_atel = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_acciones as sia')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'sia.Estado_accion', '=', 'slp.Id_Parametro')
            ->select('sia.Id_Accion', 'sia.Accion')
            ->where([
                ['sia.Estado_accion', $id_accion_seleccionada_origen_atel],
                ['sia.Status_accion', '=', 'Activo']
            ])->get();

            $informacion_acciones_ejecutar_proceso_origen_atel = json_decode(json_encode($acciones_ejecutar_proceso_origen_atel), true);
            return response()->json($informacion_acciones_ejecutar_proceso_origen_atel);
        }

        // Acciones antecesoras
        if($parametro == "acciones_antecesoras_proceso_origen_atel"){
            $servicio_asociado_origen_atel = $request->servicio_asociado_origen_atel;
            $acciones_antecesoras_origen_atel = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_acciones as sia')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_parametrizaciones_clientes as sipc', 'sia.Id_Accion', '=', 'sipc.Accion_ejecutar')
            ->select('sia.Id_Accion', 'sia.Accion')
            ->where([
                ['sipc.Servicio_asociado', $servicio_asociado_origen_atel],
                ['sipc.Id_cliente', '=', $Id_cliente]
            ])->get();

            $informacion_acciones_antecesoras_proceso_origen_atel = json_decode(json_encode($acciones_antecesoras_origen_atel), true);
            return response()->json($informacion_acciones_antecesoras_proceso_origen_atel);
        }
        
        // Bandeja de trabajo destino
        if ($parametro == "bandeja_trabajo_destino_proceso_origen_atel") {
            $bandeja_trabajo_destino_proceso_origen_atel = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
            ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
            ->select('sisc.Id_proceso', 'slps.Nombre_proceso')
            ->whereNotIn('sisc.Id_proceso', [1])
            ->where([
                ['sisc.Id_cliente', $Id_cliente],
            ])->groupBy('sisc.Id_proceso')->get();

            $informacion_bandeja_trabajo_destino_proceso_origen_atel = json_decode(json_encode($bandeja_trabajo_destino_proceso_origen_atel), true);
            return response()->json($informacion_bandeja_trabajo_destino_proceso_origen_atel);
        }

        // equipos de trabajo relacionados al proceso
        if ($parametro == "equipos_trabajo_proceso_origen_atel") {
            $equipos_trabajo_proceso_origen_atel = sigmel_grupos_trabajos::on('sigmel_gestiones')
            ->select('id', 'nombre')
            ->where([
                ['Id_proceso_equipo', '=', 1],
                ['Accion', '=', $request->id_accion_seleccionada],
                ['estado', '=', 'activo']
            ])->get();

            $informacion_equipos_trabajo_proceso_origen_atel = json_decode(json_encode($equipos_trabajo_proceso_origen_atel), true);
            return response()->json($informacion_equipos_trabajo_proceso_origen_atel);
        }

        // listado de profesionales relacionados al equipo de trabajo
        if ($parametro == "listado_profesionales_proceso_origen_atel") {
            $listado_profesionales_proceso_origen_atel = DB::table('users as u')
            ->leftJoin('sigmel_gestiones.sigmel_usuarios_grupos_trabajos as sugt', 'u.id', '=', 'sugt.id_usuarios_asignados')
            ->select('u.id', 'u.name as nombre')
            ->where([['sugt.id_equipo_trabajo', $request->id_equipo_seleccionado]])
            ->get();

            $informacion_listado_profesionales_proceso_origen_atel = json_decode(json_encode($listado_profesionales_proceso_origen_atel), true);
            return response()->json($informacion_listado_profesionales_proceso_origen_atel);
        }

        /* INFORMACIÓN PARA TRAER DE TODO LO RELACIONADO AL PROCESO CALIFICACIÓN PCL */

        // servicios asociados
        if ($parametro == "servicios_asociados_proceso_calificacion_pcl") {
            $servicios_proceso_calificacion_pcl = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
            ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
            ->select('sisc.Id_servicio', 'slps.Nombre_servicio')
            ->where([
                ['sisc.Id_cliente', $Id_cliente],
                ['sisc.Id_proceso', '=', '2']
            ])->get();

            $informacion_servicios_proceso_calificacion_pcl = json_decode(json_encode($servicios_proceso_calificacion_pcl), true);
            return response()->json($informacion_servicios_proceso_calificacion_pcl);
        }

        // listado de estados
        if ($parametro == "lista_estados") {
            $listado_estados = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Acciones'],
                ['Estado', '=', 'activo'],
            ])->get();

            $info_lista_estados = json_decode(json_encode($listado_estados, true));
            return response()->json($info_lista_estados);
        };

        // Acciones a ejecutar
        if ($parametro == "acciones_ejecutar_proceso_calificacion_pcl") {
            $id_accion_seleccionada_calificacion_pcl = $request->id_accion_seleccionada_calificacion_pcl;
            $acciones_ejecutar_proceso_calificacion_pcl = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_acciones as sia')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'sia.Estado_accion', '=', 'slp.Id_Parametro')
            ->select('sia.Id_Accion', 'sia.Accion')
            ->where([
                ['sia.Estado_accion', $id_accion_seleccionada_calificacion_pcl],
                ['sia.Status_accion', '=', 'Activo']
            ])->get();

            $informacion_acciones_ejecutar_proceso_calificacion_pcl = json_decode(json_encode($acciones_ejecutar_proceso_calificacion_pcl), true);
            return response()->json($informacion_acciones_ejecutar_proceso_calificacion_pcl);
        }

        // Acciones antecesoras
        if($parametro == "acciones_antecesoras_proceso_calificacion_pcl"){
            $servicio_asociado_calificacion_pcl = $request->servicio_asociado_calificacion_pcl;
            $acciones_antecesoras_calificacion_pcl = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_acciones as sia')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_parametrizaciones_clientes as sipc', 'sia.Id_Accion', '=', 'sipc.Accion_ejecutar')
            ->select('sia.Id_Accion', 'sia.Accion')
            ->where([
                ['sipc.Servicio_asociado', $servicio_asociado_calificacion_pcl],
                ['sipc.Id_cliente', '=', $Id_cliente]
            ])->get();

            $informacion_acciones_antecesoras_proceso_calificacion_pcl = json_decode(json_encode($acciones_antecesoras_calificacion_pcl), true);
            return response()->json($informacion_acciones_antecesoras_proceso_calificacion_pcl);
        }
        
        // Bandeja de trabajo destino
        if ($parametro == "bandeja_trabajo_destino_proceso_calificacion_pcl") {
            $bandeja_trabajo_destino_proceso_calificacion_pcl = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
            ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
            ->select('sisc.Id_proceso', 'slps.Nombre_proceso')
            ->whereNotIn('sisc.Id_proceso', [2])
            ->where([
                ['sisc.Id_cliente', $Id_cliente],
            ])->groupBy('sisc.Id_proceso')->get();

            $informacion_bandeja_trabajo_destino_proceso_calificacion_pcl = json_decode(json_encode($bandeja_trabajo_destino_proceso_calificacion_pcl), true);
            return response()->json($informacion_bandeja_trabajo_destino_proceso_calificacion_pcl);
        }

        // equipos de trabajo relacionados al proceso
        if ($parametro == "equipos_trabajo_proceso_calificacion_pcl") {
            $equipos_trabajo_proceso_calificacion_pcl = sigmel_grupos_trabajos::on('sigmel_gestiones')
            ->select('id', 'nombre')
            ->where([
                ['Id_proceso_equipo', '=', 2],
                ['Accion', '=', $request->id_accion_seleccionada],
                ['estado', '=', 'activo']
            ])->get();

            $informacion_equipos_trabajo_proceso_calificacion_pcl = json_decode(json_encode($equipos_trabajo_proceso_calificacion_pcl), true);
            return response()->json($informacion_equipos_trabajo_proceso_calificacion_pcl);
        }

        // listado de profesionales relacionados al equipo de trabajo
        if ($parametro == "listado_profesionales_proceso_calificacion_pcl") {
            $listado_profesionales_proceso_calificacion_pcl = DB::table('users as u')
            ->leftJoin('sigmel_gestiones.sigmel_usuarios_grupos_trabajos as sugt', 'u.id', '=', 'sugt.id_usuarios_asignados')
            ->select('u.id', 'u.name as nombre')
            ->where([['sugt.id_equipo_trabajo', $request->id_equipo_seleccionado]])
            ->get();

            $informacion_listado_profesionales_proceso_calificacion_pcl = json_decode(json_encode($listado_profesionales_proceso_calificacion_pcl), true);
            return response()->json($informacion_listado_profesionales_proceso_calificacion_pcl);
        }

        /* INFORMACIÓN PARA TRAER DE TODO LO RELACIONADO AL PROCESO JUNTAS */

        // servicios asociados
        if ($parametro == "servicios_asociados_proceso_juntas") {
            $servicios_proceso_juntas = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
            ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
            ->select('sisc.Id_servicio', 'slps.Nombre_servicio')
            ->where([
                ['sisc.Id_cliente', $Id_cliente],
                ['sisc.Id_proceso', '=', '3']
            ])->get();

            $informacion_servicios_proceso_juntas = json_decode(json_encode($servicios_proceso_juntas), true);
            return response()->json($informacion_servicios_proceso_juntas);
        }

        // listado de estados
        if ($parametro == "lista_estados") {
            $listado_estados = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Acciones'],
                ['Estado', '=', 'activo'],
            ])->get();

            $info_lista_estados = json_decode(json_encode($listado_estados, true));
            return response()->json($info_lista_estados);
        };

        // Acciones a ejecutar
        if ($parametro == "acciones_ejecutar_proceso_juntas") {
            $id_accion_seleccionada_juntas = $request->id_accion_seleccionada_juntas;
            $acciones_ejecutar_proceso_juntas = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_acciones as sia')
            ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'sia.Estado_accion', '=', 'slp.Id_Parametro')
            ->select('sia.Id_Accion', 'sia.Accion')
            ->where([
                ['sia.Estado_accion', $id_accion_seleccionada_juntas],
                ['sia.Status_accion', '=', 'Activo']
            ])->get();

            $informacion_acciones_ejecutar_proceso_juntas = json_decode(json_encode($acciones_ejecutar_proceso_juntas), true);
            return response()->json($informacion_acciones_ejecutar_proceso_juntas);
        }

        // Acciones antecesoras
        if($parametro == "acciones_antecesoras_proceso_juntas"){
            $servicio_asociado_juntas = $request->servicio_asociado_juntas;
            $acciones_antecesoras_juntas = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_acciones as sia')
            ->leftJoin('sigmel_gestiones.sigmel_informacion_parametrizaciones_clientes as sipc', 'sia.Id_Accion', '=', 'sipc.Accion_ejecutar')
            ->select('sia.Id_Accion', 'sia.Accion')
            ->where([
                ['sipc.Servicio_asociado', $servicio_asociado_juntas],
                ['sipc.Id_cliente', '=', $Id_cliente]
            ])->get();

            $informacion_acciones_antecesoras_proceso_juntas = json_decode(json_encode($acciones_antecesoras_juntas), true);
            return response()->json($informacion_acciones_antecesoras_proceso_juntas);
        }
        
        // Bandeja de trabajo destino
        if ($parametro == "bandeja_trabajo_destino_proceso_juntas") {
            $bandeja_trabajo_destino_proceso_juntas = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_servicios_contratados as sisc')
            ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sisc.Id_servicio', '=', 'slps.Id_Servicio')
            ->select('sisc.Id_proceso', 'slps.Nombre_proceso')
            ->whereNotIn('sisc.Id_proceso', [3])
            ->where([
                ['sisc.Id_cliente', $Id_cliente],
            ])->groupBy('sisc.Id_proceso')->get();

            $informacion_bandeja_trabajo_destino_proceso_juntas = json_decode(json_encode($bandeja_trabajo_destino_proceso_juntas), true);
            return response()->json($informacion_bandeja_trabajo_destino_proceso_juntas);
        }

        // equipos de trabajo relacionados al proceso
        if ($parametro == "equipos_trabajo_proceso_juntas") {
            $equipos_trabajo_proceso_juntas = sigmel_grupos_trabajos::on('sigmel_gestiones')
            ->select('id', 'nombre')
            ->where([
                ['Id_proceso_equipo', '=', 3],
                ['Accion', '=', $request->id_accion_seleccionada],
                ['estado', '=', 'activo']
            ])->get();

            $informacion_equipos_trabajo_proceso_juntas = json_decode(json_encode($equipos_trabajo_proceso_juntas), true);
            return response()->json($informacion_equipos_trabajo_proceso_juntas);
        }

        // listado de profesionales relacionados al equipo de trabajo
        if ($parametro == "listado_profesionales_proceso_juntas") {
            $listado_profesionales_proceso_juntas = DB::table('users as u')
            ->leftJoin('sigmel_gestiones.sigmel_usuarios_grupos_trabajos as sugt', 'u.id', '=', 'sugt.id_usuarios_asignados')
            ->select('u.id', 'u.name as nombre')
            ->where([['sugt.id_equipo_trabajo', $request->id_equipo_seleccionado]])
            ->get();

            $informacion_listado_profesionales_proceso_juntas = json_decode(json_encode($listado_profesionales_proceso_juntas), true);
            return response()->json($informacion_listado_profesionales_proceso_juntas);
        }

    }

    public function EnvioParametrizacionOrigenAtel(Request $request){

        // Captura del id del cliente al cual se le guardará la parametrización
        $Id_cliente = $request->Id_cliente;
        
        // Evaluamos que el array de datos de la tabla de parametrizaciones no venga vacío
        if (count($request->array_datos_fila_parametrizacion_origen_atel) > 0) {

            $array_datos_fila_parametrizacion_origen_atel = $request->array_datos_fila_parametrizacion_origen_atel;
            
            // Extraemos solamente los valores del array
            $array_datos_organizados_parametrizacion_origen_atel = [];
            for ($i=0; $i < count($array_datos_fila_parametrizacion_origen_atel); $i++) {
                array_push($array_datos_organizados_parametrizacion_origen_atel, $array_datos_fila_parametrizacion_origen_atel[$i]["valor"]);
            }

            // Agregramos el id del cliente y el proceso al array: Recordar que el proceso es 1 por que es Origen
            array_unshift($array_datos_organizados_parametrizacion_origen_atel, 1);
            array_unshift($array_datos_organizados_parametrizacion_origen_atel, $Id_cliente);

            // Borramos el dato de contador ya que este no se necesitará
            unset($array_datos_organizados_parametrizacion_origen_atel[2]);

            /* echo "<pre>";
            print_r($array_datos_organizados_parametrizacion_origen_atel);
            echo "</pre>"; */

            // Creamos el array con el nombre de las columnas de la tabla sigmel_informacion_parametrizaciones_clientes
            $array_tabla_parametrizaciones_cliente = ['Id_cliente','Id_proceso','F_creacion_movimiento','Servicio_asociado',
            'Estado','Accion_ejecutar','Accion_antecesora','Modulo_nuevo','Modulo_consultar','Bandeja_trabajo',
            'Modulo_principal','Detiene_tiempo_gestion','Equipo_trabajo','Profesional_asignado','Enviar_a_bandeja_trabajo_destino','Bandeja_trabajo_destino',
            'Estado_facturacion','Tiempo_alerta','Porcentaje_alerta_naranja','Porcentaje_alerta_roja',
            'Status_parametrico','Motivo_descripcion_movimiento','Nombre_usuario','F_actualizacion_movimiento'
            ];

            // Realizamos la combinación del array de datos y los nombres de las columnas de la tabla
            $array_datos_con_keys_parametrizacion_origen_atel = [];
            array_push($array_datos_con_keys_parametrizacion_origen_atel, array_combine($array_tabla_parametrizaciones_cliente, $array_datos_organizados_parametrizacion_origen_atel));

            // Inserción de datos
            sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')->insert($array_datos_con_keys_parametrizacion_origen_atel);

            $enviar_mensaje = 'Parametrización guardada satisfactoriamente.';
            $mensajes = array(
                "parametro" => 'agrego_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
    
            return json_decode(json_encode($mensajes, true));
        }else{
            $enviar_mensaje = 'No se pudo guardar la parametrización.';
            $mensajes = array(
                "parametro" => 'no_agrego_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
    
            return json_decode(json_encode($mensajes, true));
        }
    }

    public function ActualizarParametrizacionOrigenAtel(Request $request){
        // Captura del id del cliente al cual se le guardará la parametrización
        $Id_cliente = $request->Id_cliente;
                
        // Evaluamos que el array de datos de la tabla de parametrizaciones no venga vacío
        if (count($request->array_datos_fila_parametrizacion_origen_atel) > 0) {

            $array_datos_fila_parametrizacion_origen_atel = $request->array_datos_fila_parametrizacion_origen_atel;
            
            // Extraemos solamente los valores del array
            $array_datos_organizados_parametrizacion_origen_atel = [];
            for ($i=0; $i < count($array_datos_fila_parametrizacion_origen_atel); $i++) {
                array_push($array_datos_organizados_parametrizacion_origen_atel, $array_datos_fila_parametrizacion_origen_atel[$i]["valor"]);
            }

            // Agregramos el id del cliente y el proceso al array: Recordar que el proceso es 1 por que es Origen
            array_unshift($array_datos_organizados_parametrizacion_origen_atel, 1);
            array_unshift($array_datos_organizados_parametrizacion_origen_atel, $Id_cliente);


            // Borramos el dato de contador ya que este no se necesitará
            // unset($array_datos_organizados_parametrizacion_origen_atel[2]);

            // seteamos la fecha de actualizacion
            $time = time();
            $date = date("Y-m-d", $time);

            
            $array_datos_organizados_parametrizacion_origen_atel[22] = $date;

            /* echo "<pre>";
            print_r($array_datos_organizados_parametrizacion_origen_atel);
            echo "</pre>"; */

            // Creamos el array con el nombre de las columnas de la tabla sigmel_informacion_parametrizaciones_clientes
            $array_tabla_parametrizaciones_cliente = ['Id_cliente','Id_proceso','F_creacion_movimiento','Servicio_asociado',
            'Estado','Accion_ejecutar','Accion_antecesora','Modulo_nuevo','Modulo_consultar','Bandeja_trabajo',
            'Modulo_principal','Detiene_tiempo_gestion','Equipo_trabajo','Profesional_asignado','Enviar_a_bandeja_trabajo_destino','Bandeja_trabajo_destino',
            'Estado_facturacion','Tiempo_alerta','Porcentaje_alerta_naranja','Porcentaje_alerta_roja',
            'Status_parametrico','Motivo_descripcion_movimiento','Nombre_usuario','F_actualizacion_movimiento'
            ];

            // Realizamos la combinación del array de datos y los nombres de las columnas de la tabla
            $array_datos_con_keys_parametrizacion_origen_atel = [];
            array_push($array_datos_con_keys_parametrizacion_origen_atel, array_combine($array_tabla_parametrizaciones_cliente, $array_datos_organizados_parametrizacion_origen_atel));

            // Actualización de datos
            foreach ($array_datos_con_keys_parametrizacion_origen_atel as $actualizar_parametrizacion_origen_atel) {
                sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
                ->where([
                    ['Id_parametrizacion', $request->id_parametrizacion_origen_atel_editar],
                    ['Id_cliente', $Id_cliente]
                ])
                ->update($actualizar_parametrizacion_origen_atel);
            }
            
            $enviar_mensaje = 'Parametrización actualizada satisfactoriamente.';
            $mensajes = array(
                "parametro" => 'actualizo_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 

            return json_decode(json_encode($mensajes, true));

        }else{
            $enviar_mensaje = 'No se pudo guardar la parametrización.';
            $mensajes = array(
                "parametro" => 'no_actualizo_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 

            return json_decode(json_encode($mensajes, true));
        }
       
    }

    public function EnvioParametrizacionCalificacionPcl(Request $request){

        // Captura del id del cliente al cual se le guardará la parametrización
        $Id_cliente = $request->Id_cliente;
        
        // Evaluamos que el array de datos de la tabla de parametrizaciones no venga vacío
        if (count($request->array_datos_fila_parametrizacion_calificacion_pcl) > 0) {
    
            $array_datos_fila_parametrizacion_calificacion_pcl = $request->array_datos_fila_parametrizacion_calificacion_pcl;
            
            // Extraemos solamente los valores del array
            $array_datos_organizados_parametrizacion_calificacion_pcl = [];
            for ($i=0; $i < count($array_datos_fila_parametrizacion_calificacion_pcl); $i++) {
                array_push($array_datos_organizados_parametrizacion_calificacion_pcl, $array_datos_fila_parametrizacion_calificacion_pcl[$i]["valor"]);
            }
    
            // Agregramos el id del cliente y el proceso al array: Recordar que el proceso es 2 por que es Calificación pcl
            array_unshift($array_datos_organizados_parametrizacion_calificacion_pcl, 2);
            array_unshift($array_datos_organizados_parametrizacion_calificacion_pcl, $Id_cliente);
    
            // Borramos el dato de contador ya que este no se necesitará
            unset($array_datos_organizados_parametrizacion_calificacion_pcl[2]);
    
            /* echo "<pre>";
            print_r($array_datos_organizados_parametrizacion_calificacion_pcl);
            echo "</pre>"; */
    
            // Creamos el array con el nombre de las columnas de la tabla sigmel_informacion_parametrizaciones_clientes
            $array_tabla_parametrizaciones_cliente = ['Id_cliente','Id_proceso','F_creacion_movimiento','Servicio_asociado',
            'Estado','Accion_ejecutar','Accion_antecesora','Modulo_nuevo','Modulo_consultar','Bandeja_trabajo',
            'Modulo_principal','Detiene_tiempo_gestion','Equipo_trabajo','Profesional_asignado','Enviar_a_bandeja_trabajo_destino','Bandeja_trabajo_destino',
            'Estado_facturacion','Tiempo_alerta','Porcentaje_alerta_naranja','Porcentaje_alerta_roja',
            'Status_parametrico','Motivo_descripcion_movimiento','Nombre_usuario','F_actualizacion_movimiento'
            ];
    
            // Realizamos la combinación del array de datos y los nombres de las columnas de la tabla
            $array_datos_con_keys_parametrizacion_calificacion_pcl = [];
            array_push($array_datos_con_keys_parametrizacion_calificacion_pcl, array_combine($array_tabla_parametrizaciones_cliente, $array_datos_organizados_parametrizacion_calificacion_pcl));
    
            // Inserción de datos
            sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')->insert($array_datos_con_keys_parametrizacion_calificacion_pcl);
    
            $enviar_mensaje = 'Parametrización guardada satisfactoriamente.';
            $mensajes = array(
                "parametro" => 'agrego_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
    
            return json_decode(json_encode($mensajes, true));
        }else{
            $enviar_mensaje = 'No se pudo guardar la parametrización.';
            $mensajes = array(
                "parametro" => 'no_agrego_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
    
            return json_decode(json_encode($mensajes, true));
        }
    }

    public function ActualizarParametrizacionCalificacionPcl(Request $request){
        // Captura del id del cliente al cual se le guardará la parametrización
        $Id_cliente = $request->Id_cliente;
                
        // Evaluamos que el array de datos de la tabla de parametrizaciones no venga vacío
        if (count($request->array_datos_fila_parametrizacion_calificacion_pcl) > 0) {
    
            $array_datos_fila_parametrizacion_calificacion_pcl = $request->array_datos_fila_parametrizacion_calificacion_pcl;
            
            // Extraemos solamente los valores del array
            $array_datos_organizados_parametrizacion_calificacion_pcl = [];
            for ($i=0; $i < count($array_datos_fila_parametrizacion_calificacion_pcl); $i++) {
                array_push($array_datos_organizados_parametrizacion_calificacion_pcl, $array_datos_fila_parametrizacion_calificacion_pcl[$i]["valor"]);
            }
    
            // Agregramos el id del cliente y el proceso al array: Recordar que el proceso es 2 por que es Calificación pcl
            array_unshift($array_datos_organizados_parametrizacion_calificacion_pcl, 2);
            array_unshift($array_datos_organizados_parametrizacion_calificacion_pcl, $Id_cliente);
    
    
            // Borramos el dato de contador ya que este no se necesitará
            // unset($array_datos_organizados_parametrizacion_calificacion_pcl[2]);
    
            // seteamos la fecha de actualizacion
            $time = time();
            $date = date("Y-m-d", $time);
    
            
            $array_datos_organizados_parametrizacion_calificacion_pcl[22] = $date;
    
            /* echo "<pre>";
            print_r($array_datos_organizados_parametrizacion_calificacion_pcl);
            echo "</pre>"; */
    
            // Creamos el array con el nombre de las columnas de la tabla sigmel_informacion_parametrizaciones_clientes
            $array_tabla_parametrizaciones_cliente = ['Id_cliente','Id_proceso','F_creacion_movimiento','Servicio_asociado',
            'Estado','Accion_ejecutar','Accion_antecesora','Modulo_nuevo','Modulo_consultar','Bandeja_trabajo',
            'Modulo_principal','Detiene_tiempo_gestion','Equipo_trabajo','Profesional_asignado','Enviar_a_bandeja_trabajo_destino','Bandeja_trabajo_destino',
            'Estado_facturacion','Tiempo_alerta','Porcentaje_alerta_naranja','Porcentaje_alerta_roja',
            'Status_parametrico','Motivo_descripcion_movimiento','Nombre_usuario','F_actualizacion_movimiento'
            ];
    
            // Realizamos la combinación del array de datos y los nombres de las columnas de la tabla
            $array_datos_con_keys_parametrizacion_calificacion_pcl = [];
            array_push($array_datos_con_keys_parametrizacion_calificacion_pcl, array_combine($array_tabla_parametrizaciones_cliente, $array_datos_organizados_parametrizacion_calificacion_pcl));
    
            // Actualización de datos
            foreach ($array_datos_con_keys_parametrizacion_calificacion_pcl as $actualizar_parametrizacion_calificacion_pcl) {
                sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
                ->where([
                    ['Id_parametrizacion', $request->id_parametrizacion_calificacion_pcl_editar],
                    ['Id_cliente', $Id_cliente]
                ])
                ->update($actualizar_parametrizacion_calificacion_pcl);
            }
            
            $enviar_mensaje = 'Parametrización actualizada satisfactoriamente.';
            $mensajes = array(
                "parametro" => 'actualizo_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
    
            return json_decode(json_encode($mensajes, true));
    
        }else{
            $enviar_mensaje = 'No se pudo guardar la parametrización.';
            $mensajes = array(
                "parametro" => 'no_actualizo_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
    
            return json_decode(json_encode($mensajes, true));
        }
       
    }

    public function EnvioParametrizacionJuntas(Request $request){

        // Captura del id del cliente al cual se le guardará la parametrización
        $Id_cliente = $request->Id_cliente;
        
        // Evaluamos que el array de datos de la tabla de parametrizaciones no venga vacío
        if (count($request->array_datos_fila_parametrizacion_juntas) > 0) {
        
            $array_datos_fila_parametrizacion_juntas = $request->array_datos_fila_parametrizacion_juntas;
            
            // Extraemos solamente los valores del array
            $array_datos_organizados_parametrizacion_juntas = [];
            for ($i=0; $i < count($array_datos_fila_parametrizacion_juntas); $i++) {
                array_push($array_datos_organizados_parametrizacion_juntas, $array_datos_fila_parametrizacion_juntas[$i]["valor"]);
            }
        
            // Agregramos el id del cliente y el proceso al array: Recordar que el proceso es 3 por que es Juntas
            array_unshift($array_datos_organizados_parametrizacion_juntas, 3);
            array_unshift($array_datos_organizados_parametrizacion_juntas, $Id_cliente);
        
            // Borramos el dato de contador ya que este no se necesitará
            unset($array_datos_organizados_parametrizacion_juntas[2]);
        
            /* echo "<pre>";
            print_r($array_datos_organizados_parametrizacion_juntas);
            echo "</pre>"; */
        
            // Creamos el array con el nombre de las columnas de la tabla sigmel_informacion_parametrizaciones_clientes
            $array_tabla_parametrizaciones_cliente = ['Id_cliente','Id_proceso','F_creacion_movimiento','Servicio_asociado',
            'Estado','Accion_ejecutar','Accion_antecesora','Modulo_nuevo','Modulo_consultar','Bandeja_trabajo',
            'Modulo_principal','Detiene_tiempo_gestion','Equipo_trabajo','Profesional_asignado','Enviar_a_bandeja_trabajo_destino','Bandeja_trabajo_destino',
            'Estado_facturacion','Tiempo_alerta','Porcentaje_alerta_naranja','Porcentaje_alerta_roja',
            'Status_parametrico','Motivo_descripcion_movimiento','Nombre_usuario','F_actualizacion_movimiento'
            ];
        
            // Realizamos la combinación del array de datos y los nombres de las columnas de la tabla
            $array_datos_con_keys_parametrizacion_juntas = [];
            array_push($array_datos_con_keys_parametrizacion_juntas, array_combine($array_tabla_parametrizaciones_cliente, $array_datos_organizados_parametrizacion_juntas));
        
            // Inserción de datos
            sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')->insert($array_datos_con_keys_parametrizacion_juntas);
        
            $enviar_mensaje = 'Parametrización guardada satisfactoriamente.';
            $mensajes = array(
                "parametro" => 'agrego_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
        
            return json_decode(json_encode($mensajes, true));
        }else{
            $enviar_mensaje = 'No se pudo guardar la parametrización.';
            $mensajes = array(
                "parametro" => 'no_agrego_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
        
            return json_decode(json_encode($mensajes, true));
        }
    }

    public function ActualizarParametrizacionJuntas(Request $request){
        // Captura del id del cliente al cual se le guardará la parametrización
        $Id_cliente = $request->Id_cliente;
                
        // Evaluamos que el array de datos de la tabla de parametrizaciones no venga vacío
        if (count($request->array_datos_fila_parametrizacion_juntas) > 0) {
    
            $array_datos_fila_parametrizacion_juntas = $request->array_datos_fila_parametrizacion_juntas;
            
            // Extraemos solamente los valores del array
            $array_datos_organizados_parametrizacion_juntas = [];
            for ($i=0; $i < count($array_datos_fila_parametrizacion_juntas); $i++) {
                array_push($array_datos_organizados_parametrizacion_juntas, $array_datos_fila_parametrizacion_juntas[$i]["valor"]);
            }
    
            // Agregramos el id del cliente y el proceso al array: Recordar que el proceso es 3 por que es Juntas
            array_unshift($array_datos_organizados_parametrizacion_juntas, 3);
            array_unshift($array_datos_organizados_parametrizacion_juntas, $Id_cliente);
    
    
            // Borramos el dato de contador ya que este no se necesitará
            // unset($array_datos_organizados_parametrizacion_juntas[2]);
    
            // seteamos la fecha de actualizacion
            $time = time();
            $date = date("Y-m-d", $time);
    
            
            $array_datos_organizados_parametrizacion_juntas[22] = $date;
    
            /* echo "<pre>";
            print_r($array_datos_organizados_parametrizacion_juntas);
            echo "</pre>"; */
    
            // Creamos el array con el nombre de las columnas de la tabla sigmel_informacion_parametrizaciones_clientes
            $array_tabla_parametrizaciones_cliente = ['Id_cliente','Id_proceso','F_creacion_movimiento','Servicio_asociado',
            'Estado','Accion_ejecutar','Accion_antecesora','Modulo_nuevo','Modulo_consultar','Bandeja_trabajo',
            'Modulo_principal','Detiene_tiempo_gestion','Equipo_trabajo','Profesional_asignado','Enviar_a_bandeja_trabajo_destino','Bandeja_trabajo_destino',
            'Estado_facturacion','Tiempo_alerta','Porcentaje_alerta_naranja','Porcentaje_alerta_roja',
            'Status_parametrico','Motivo_descripcion_movimiento','Nombre_usuario','F_actualizacion_movimiento'
            ];
    
            // Realizamos la combinación del array de datos y los nombres de las columnas de la tabla
            $array_datos_con_keys_parametrizacion_juntas = [];
            array_push($array_datos_con_keys_parametrizacion_juntas, array_combine($array_tabla_parametrizaciones_cliente, $array_datos_organizados_parametrizacion_juntas));
    
            // Actualización de datos
            foreach ($array_datos_con_keys_parametrizacion_juntas as $actualizar_parametrizacion_juntas) {
                sigmel_informacion_parametrizaciones_clientes::on('sigmel_gestiones')
                ->where([
                    ['Id_parametrizacion', $request->id_parametrizacion_juntas_editar],
                    ['Id_cliente', $Id_cliente]
                ])
                ->update($actualizar_parametrizacion_juntas);
            }
            
            $enviar_mensaje = 'Parametrización actualizada satisfactoriamente.';
            $mensajes = array(
                "parametro" => 'actualizo_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
    
            return json_decode(json_encode($mensajes, true));
    
        }else{
            $enviar_mensaje = 'No se pudo guardar la parametrización.';
            $mensajes = array(
                "parametro" => 'no_actualizo_parametrizacion',
                "mensaje" => $enviar_mensaje
            ); 
    
            return json_decode(json_encode($mensajes, true));
        }
       
    }
}
