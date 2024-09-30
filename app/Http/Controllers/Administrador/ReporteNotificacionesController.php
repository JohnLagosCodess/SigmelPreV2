<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Imports\CargueNotificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use App\Models\sigmel_numero_orden_eventos;
use App\Models\cndatos_reportes_notificaciones;
use App\Models\cndatos_reportes_notificaciones_manuales;
use App\Models\cnvista_reportes_notificaciones_uniones;
use App\Models\sigmel_lista_parametros;
use Maatwebsite\Excel\Facades\Excel;

use ZipArchive;

class ReporteNotificacionesController extends Controller
{
    public function show(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.reporteNotificaciones', compact('user'));
    }

    /* Función para realizar la consulta al reporte de notificaciones */
    public function consultaReporteNotificaciones(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        /* Captura de variables */
        $fecha_desde = $request->fecha_desde;
        $fecha_hasta = $request->fecha_hasta;

        // Variables NO Obligatorias
        $estado_general_calificacion = $request->estado_general_calificacion;
        $numero_orden = $request->numero_orden;

        // Validaciones
        /* 
            1: Fecha desde está vacío y Fecha hasta tiene dato = No hay reporte.
            2: Fecha desde tiene dato y Fecha hasta está vació = No hay reporte.
            3. Fecha desde y Fecha hasta están vacíos = Se genera reporte completo sin fechas.
            4. Fecha desde y Fecha hasta tienen datos = Se genera reporte completo dependiendo del rango de fechas seleccionado.
        */
        if (empty($fecha_desde) && !empty($fecha_hasta)) {
            $mensajes = array(
                "parametro" => 'falta_un_parametro',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));

        }
        elseif (!empty($fecha_desde) && empty($fecha_hasta)) {
            $mensajes = array(
                "parametro" => 'falta_un_parametro',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));

        }
        elseif (empty($fecha_desde) && empty($fecha_hasta)) {            
            $mensajes = array(
                "parametro" => 'falta_un_parametro',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        else if (!empty($fecha_desde) && !empty($fecha_hasta)){   
            
            // Validacion del estado general de calificacion y numero de orden para descargar los reportes            

            //Case 1: Si el estado general de calificacion es igual a Pendiente (359) y viene vacio numero de orden
            //Case 2: Si el estado general de calificacion es igual a Pendiente (359) y viene numero de orden
            //Case 3: Si el estado general de calificacion es distinto a Todos (365), Pendiente (359) y viene vacio numero de orden
            //Case 4: Si el estado general de calificacion es distinto a Todos (365), Pendiente (359) y viene numero de orden
            //Case 5: Si el estado general de calificacion es igual a Todos (365) y viene vacio numero de orden
            //Case 6: Si el estado general de calificacion es igual a Todos (365) y viene numero de orden

            $validar_numero_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
            ->select('Numero_orden')
            ->where([
                ['Proceso', 'Notificaciones']
            ])
            ->get();

            $numero_orden_actual = $validar_numero_orden[0]->Numero_orden;            
            switch (true) {
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 359 && empty($numero_orden)):                    
                    $reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                    ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                    'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                    'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                    'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                    'Folios', 'F_envio', 'F_notificacion')
                    ->where([
                        ['Estado_general_notificacion', $estado_general_calificacion],
                        ['Bandeja_notificaciones', 'Si']                    
                    ])
                    ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                    ->orderBy('F_comunicado')
                    ->orderBy('N_radicado') 
                    ->get();

                    $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true)); 
    
                    $datos = [
                        'n_orden' => $numero_orden_actual,
                        'reporte' => $array_reporte_notificaciones
                    ];

                    return response()->json($datos);
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 359 && !empty($numero_orden)):                        
                    if (!empty($numero_orden_actual)) {
                        
                        if ($numero_orden > $numero_orden_actual) {
                            $mensajes = array(
                                "parametro" => 'Numero_orden_NoVigente',
                                "mensaje" => 'El número de orden aún no se encuentra vigente en Sigmel'
                            );
                            return response()->json($mensajes);
                        } elseif ($numero_orden <= $numero_orden_actual) {
                            $numero_orden_inicial = 00000000000001;
                            if ($numero_orden < $numero_orden_inicial) {                                
                                $mensajes = array(
                                    "parametro" => 'Numero_orden_NoExiste',
                                    "mensaje" => 'El número de orden es menor a los que se encuentran en Sigmel'
                                );
                                return response()->json($mensajes);
                            } else {
                                
                                $reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                                ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                                'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                                'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                                'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                                'Folios', 'F_envio', 'F_notificacion')
                                ->where([
                                    ['Estado_general_notificacion', $estado_general_calificacion],
                                    ['Bandeja_notificaciones', 'Si'],
                                    ['N_de_orden', $numero_orden]
                                ])
                                ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                                ->orderBy('F_comunicado')
                                ->orderBy('N_radicado') 
                                ->get();
    
                                $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true)); 
    
                                $datos = [
                                    'n_orden' => $numero_orden_actual,
                                    'reporte' => $array_reporte_notificaciones
                                ];
    
                                return response()->json($datos);
                            }
                            
                        }
                    } else {
                        $mensajes = array(
                            "parametro" => 'falta_numero_orden_DB',
                            "mensaje" => 'No existe número orden para la bandeja de Notificaciones'
                        );
                        return response()->json($mensajes);
                    }
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 356 && empty($numero_orden) 
                    || !empty($estado_general_calificacion) && $estado_general_calificacion == 357 && empty($numero_orden)
                    || !empty($estado_general_calificacion) && $estado_general_calificacion == 358 && empty($numero_orden)):                    
                    $reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                    ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                    'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                    'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                    'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                    'Folios', 'F_envio', 'F_notificacion')
                    ->where([
                        ['Estado_general_notificacion', $estado_general_calificacion]                        
                    ])
                    ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                    ->orderBy('F_comunicado')
                    ->orderBy('N_radicado') 
                    ->get();

                    $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true)); 
    
                    $datos = [
                        'n_orden' => $numero_orden_actual,
                        'reporte' => $array_reporte_notificaciones
                    ];

                    return response()->json($datos);
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 356 && !empty($numero_orden) 
                    || !empty($estado_general_calificacion) && $estado_general_calificacion == 357 && !empty($numero_orden)
                    || !empty($estado_general_calificacion) && $estado_general_calificacion == 358 && !empty($numero_orden)):
                    
                    if (!empty($numero_orden_actual)) {
                        
                        if ($numero_orden > $numero_orden_actual) {
                            $mensajes = array(
                                "parametro" => 'Numero_orden_NoVigente',
                                "mensaje" => 'El número de orden aún no se encuentra vigente en Sigmel'
                            );
                            return response()->json($mensajes);
                        } elseif ($numero_orden <= $numero_orden_actual) {
                            $numero_orden_inicial = 00000000000001;
                            if ($numero_orden < $numero_orden_inicial) {                                
                                $mensajes = array(
                                    "parametro" => 'Numero_orden_NoExiste',
                                    "mensaje" => 'El número de orden es menor a los que se encuentran en Sigmel'
                                );
                                return response()->json($mensajes);
                            } else {
                                
                                $reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                                ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                                'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                                'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                                'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                                'Folios', 'F_envio', 'F_notificacion')
                                ->where([
                                    ['Estado_general_notificacion', $estado_general_calificacion],
                                    ['N_de_orden', $numero_orden]
                                ])
                                ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                                ->orderBy('F_comunicado')
                                ->orderBy('N_radicado') 
                                ->get();
    
                                $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true)); 
    
                                $datos = [
                                    'n_orden' => $numero_orden_actual,
                                    'reporte' => $array_reporte_notificaciones
                                ];
    
                                return response()->json($datos);
                            }
                            
                        }
                    } else {
                        $mensajes = array(
                            "parametro" => 'falta_numero_orden_DB',
                            "mensaje" => 'No existe número orden para la bandeja de Notificaciones'
                        );
                        return response()->json($mensajes);
                    }                    
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 365 && empty($numero_orden)):                    
                    $reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                    ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                    'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                    'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                    'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                    'Folios', 'F_envio', 'F_notificacion')
                    ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                    ->orderBy('F_comunicado')
                    ->orderBy('N_radicado') 
                    ->get();                    
                    $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true)); 
    
                    $datos = [
                        'n_orden' => $numero_orden_actual,
                        'reporte' => $array_reporte_notificaciones
                    ];

                    return response()->json($datos);
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 365 && !empty($numero_orden)):                    
                    if (!empty($numero_orden_actual)) {
                        
                        if ($numero_orden > $numero_orden_actual) {
                            $mensajes = array(
                                "parametro" => 'Numero_orden_NoVigente',
                                "mensaje" => 'El número de orden aún no se encuentra vigente en Sigmel'
                            );
                            return response()->json($mensajes);
                        } elseif ($numero_orden <= $numero_orden_actual) {
                            $numero_orden_inicial = 00000000000001;
                            if ($numero_orden < $numero_orden_inicial) {                                
                                $mensajes = array(
                                    "parametro" => 'Numero_orden_NoExiste',
                                    "mensaje" => 'El número de orden es menor a los que se encuentran en Sigmel'
                                );
                                return response()->json($mensajes);
                            } else {
                                
                                $reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                                ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                                'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                                'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                                'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                                'Folios', 'F_envio', 'F_notificacion')
                                ->where([
                                    ['N_de_orden', $numero_orden]
                                ])
                                ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                                ->orderBy('F_comunicado')
                                ->orderBy('N_radicado')                    
                                ->get();
    
                                $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true)); 
    
                                $datos = [
                                    'n_orden' => $numero_orden_actual,
                                    'reporte' => $array_reporte_notificaciones
                                ];
    
                                return response()->json($datos);
                            }
                            
                        }
                    } else {
                        $mensajes = array(
                            "parametro" => 'falta_numero_orden_DB',
                            "mensaje" => 'No existe número orden para la bandeja de Notificaciones'
                        );
                        return response()->json($mensajes);
                    }  
                    
                break;
            }           
        }
    }

    /* Función para generar la descarga del zip */
    public function generarZipReporteNotificaciones(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);

        /* Captura de variables */
        $fecha_desde = $request->fecha_desde;
        $fecha_hasta = $request->fecha_hasta;
        $nro_orden = $request->nro_orden;

        // Variables NO Obligatorias
        $estado_general_calificacion = $request->estado_general_calificacion;
        $numero_orden = $request->numero_orden;

        // Validaciones
        /* 
            1: Fecha desde está vacío y Fecha hasta tiene dato = No hay reporte.
            2: Fecha desde tiene dato y Fecha hasta está vació = No hay reporte.
            3. Fecha desde y Fecha hasta están vacíos = Se genera reporte completo sin fechas.
            4. Fecha desde y Fecha hasta tienen datos = Se genera reporte completo dependiendo del rango de fechas seleccionado.
        */

        if (empty($fecha_desde) && !empty($fecha_hasta)) {
            $mensajes = array(
                "parametro" => 'error',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));

        }
        elseif (!empty($fecha_desde) && empty($fecha_hasta)) {
            $mensajes = array(
                "parametro" => 'error',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));

        }
        elseif (empty($fecha_desde) && empty($fecha_hasta)) {

            $mensajes = array(
                "parametro" => 'error',
                "mensaje" => 'Debe seleccionar las dos fechas para generar el zip.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        else if (!empty($fecha_desde) && !empty($fecha_hasta)){

            // Validacion del estado general de calificacion y numero de orden para descargar los documentos

            //Case 1: Si el estado general de calificacion es igual a Pendiente (359) y viene vacio numero de orden
            //Case 2: Si el estado general de calificacion es igual a Pendiente (359) y viene numero de orden
            //Case 3: Si el estado general de calificacion es distinto a Todos (365), Pendiente (359) y viene vacio numero de orden
            //Case 4: Si el estado general de calificacion es distinto a Todos (365), Pendiente (359) y viene numero de orden
            //Case 5: Si el estado general de calificacion es igual a Todos (365) y viene vacio numero de orden
            //Case 6: Si el estado general de calificacion es igual a Todos (365) y viene numero de orden

            switch (true) {
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 359 && empty($numero_orden)):                    
                    $datos_reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                    ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                    'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                    'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                    'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                    'Folios', 'F_envio', 'F_notificacion')
                    ->where([
                        ['Estado_general_notificacion', $estado_general_calificacion],
                        ['Bandeja_notificaciones', 'Si']                    
                    ])
                    ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                    ->orderBy('F_comunicado')
                    ->orderBy('N_radicado') 
                    ->get();

                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 359 && !empty($numero_orden)):                    
                    if (!empty($nro_orden)) {                                                
                                                                                    
                        $datos_reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                        ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                        'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                        'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                        'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                        'Folios', 'F_envio', 'F_notificacion')
                        ->where([
                            ['Estado_general_notificacion', $estado_general_calificacion],
                            ['Bandeja_notificaciones', 'Si'],
                            ['N_de_orden', $numero_orden]
                        ])
                        ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                        ->orderBy('F_comunicado')
                        ->orderBy('N_radicado') 
                        ->get();                        
                    }
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 356 && empty($numero_orden) 
                    || !empty($estado_general_calificacion) && $estado_general_calificacion == 357 && empty($numero_orden)
                    || !empty($estado_general_calificacion) && $estado_general_calificacion == 358 && empty($numero_orden)):                    
                    $datos_reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                    ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                    'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                    'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                    'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                    'Folios', 'F_envio', 'F_notificacion')
                    ->where([
                        ['Estado_general_notificacion', $estado_general_calificacion]                        
                    ])
                    ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                    ->orderBy('F_comunicado')
                    ->orderBy('N_radicado') 
                    ->get();
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 356 && !empty($numero_orden) 
                    || !empty($estado_general_calificacion) && $estado_general_calificacion == 357 && !empty($numero_orden)
                    || !empty($estado_general_calificacion) && $estado_general_calificacion == 358 && !empty($numero_orden)):                    
                    if (!empty($nro_orden)) {                                                
                            
                            $datos_reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                            ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                            'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                            'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                            'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                            'Folios', 'F_envio', 'F_notificacion')
                            ->where([
                                ['Estado_general_notificacion', $estado_general_calificacion],
                                ['N_de_orden', $numero_orden]
                            ])
                            ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                            ->orderBy('F_comunicado')
                            ->orderBy('N_radicado') 
                            ->get();                        
                    }                    
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 365 && empty($numero_orden)):                    
                    $datos_reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                    ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                    'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                    'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                    'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                    'Folios', 'F_envio', 'F_notificacion')
                    ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                    ->orderBy('F_comunicado')
                    ->orderBy('N_radicado') 
                    ->get();                                        
                break;
                case (!empty($estado_general_calificacion) && $estado_general_calificacion == 365 && !empty($numero_orden)):                    
                    if (!empty($nro_orden)) {                       
                            
                        $datos_reporte_notificaciones = cnvista_reportes_notificaciones_uniones::on('sigmel_gestiones')
                        ->select('ID_evento', 'F_comunicado', 'N_radicado', 'Nombre_documento', 'Carpeta_impresion',
                        'Observaciones', 'N_identificacion', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion_destinatario',
                        'Telefono_destinatario', 'Ciudad_departamento', 'Email_destinatario', 'Proceso_servicio', 
                        'Ultima_accion', 'Estado', 'N_de_orden', 'Id_destinatario', 'Tipo_correspondencia', 'N_guia',
                        'Folios', 'F_envio', 'F_notificacion')
                        ->where([
                            ['N_de_orden', $numero_orden]
                        ])
                        ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
                        ->orderBy('F_comunicado')
                        ->orderBy('N_radicado')                    
                        ->get();
                    }
                break;
            }   
            /* guardarmos los datos en un array */
            $array_datos_reporte_notificaciones = json_decode(json_encode($datos_reporte_notificaciones, true));

            // Ruta donde se guardará el archivo comprimido
            $rutaArchivoComprimido = storage_path('app/'.$date.'_'.$nro_orden.'.zip');

            /* Se valida que exista informacion para generar el proceso de creación del .zip */
            if (count($array_datos_reporte_notificaciones) == 0) {
                $mensajes = array(
                    "parametro" => 'error',
                    "mensaje" => 'El archivo .zip no se pudo descargar, debido a que no existen documentos generados por el sistema.'
                );
                return json_decode(json_encode($mensajes, true));
            } else {
                
                // // Crear un nuevo archivo zip
                $zip = new ZipArchive;
                if ($zip->open($rutaArchivoComprimido, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    $archivosAgregados = 0;

                    foreach ($array_datos_reporte_notificaciones as $archivo) {
                        
                        if ($archivo->Nombre_documento <> 'No Descargado' && $archivo->Nombre_documento <> 'N/A') {
                            
                            $nombreArchivo = $archivo->Nombre_documento;
                            $rutaArchivo = "Documentos_Eventos/{$archivo->ID_evento}/{$nombreArchivo}";
            
                            if (file_exists($rutaArchivo)) {
                                
                                if ($archivo->Carpeta_impresion <> "N/A" && $archivo->Carpeta_impresion <> "No Tiene Copia") {
            
                                    $nombre_carpeta = $archivo->Carpeta_impresion;
                                    // Crear la estructura de carpetas en el ZIP
                                    if (!$zip->locateName($nombre_carpeta, ZipArchive::FL_NOCASE | ZipArchive::FL_NODIR)) {
                                        $zip->addEmptyDir($nombre_carpeta);
                                    }
            
                                    // Agregar el archivo al ZIP en la subcarpeta
                                    $zip->addFile($rutaArchivo, $nombre_carpeta . '/' . $nombreArchivo);

                                    $archivosAgregados++;
                                }
                            }
            
                        }
                    }
            
                    // Cerrar el archivo zip
                    $zip->close();

                    // Si no se agregaron archivos al zip se procede a eliminar el zip
                    if ($archivosAgregados == 0) {
                        // Eliminar el archivo ZIP vacío
                        File::delete($rutaArchivoComprimido);
                        // Retornar un mensaje de error
                        $mensajes = array(
                            "parametro" => 'error',
                            "vacio" => "zip_vacio",
                            "mensaje" => 'El archivo .zip no se pudo descargar, debido a que no existen documentos válidos para comprimir.'
                        );
                        return response()->json($mensajes);
                    }else{

                        // Mover el archivo zip al directorio público
                        $nombreArchivoComprimido = $date.'_'.$nro_orden.'.zip';
                        $ubicacionDestino = public_path($nombreArchivoComprimido);
                        File::move($rutaArchivoComprimido, $ubicacionDestino);
            
                        // Devolver la URL del archivo zip en la respuesta Ajax
                        $urlArchivoComprimido = asset($nombreArchivoComprimido);
                        return response()->json(['url' => $urlArchivoComprimido, 'nom_archivo' => $nombreArchivoComprimido]);
                    }
    
                }
    
    
            }
            
        }
        else{
            $mensajes = array(
                "parametro" => 'error',
                "mensaje" => 'Consulte a soporte sobre este error.'
            );
            return json_decode(json_encode($mensajes, true));
        }

    }

    // Eliminar el reporte de notificaciones
    public function eliminarZipReporteNotificaciones(Request $request){
        $nom_archivo = $request->nom_archivo;

        // Eliminar el archivo
        if (File::exists(public_path($nom_archivo))) {
            File::delete(public_path($nom_archivo));
        }
    }

    // Cargue correspondecias o notificaciones

    public function cargueCorrespondencias(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        
        $request->validate([
            'cargue_corres' => 'required|mimes:csv,xlsx,xls|max:20480'
        ], [
            'cargue_corres.required' => 'Debe seleccionar un archivo.',
            'cargue_corres.mimes' => 'El archivo debe ser un archivo de tipo: csv, xlsx, xls.',
            'cargue_corres.max' => 'El archivo no debe superar los 20 MB.',
        ]);
    
        $file = $request->file('cargue_corres');
    
        // Procesar el archivo aquí              

        try {
            Excel::import(new CargueNotificaciones, $file);
            return back()->with('success', 'Cargue correspondencias realizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    // Select Reporte notificaciones

    public function cargueListadoSelectoresReporteNoti(Request $request){
        $parametro = $request->parametro;

        // Llenado de select Estado general de calificación
        if ($parametro == "lista_estado_general_calificacion") {

            //356 (Devuelto), 357 (Notificado efectivamente), 358 (Notificado parcialmente), 359 (Pendiente) y 365 (Todos) 
            
            $lista_estado_general_califi = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->whereIn('Id_Parametro', [356, 357, 358, 359, 365])
            ->get();

            $info_lista_estado_general_califi = json_decode(json_encode($lista_estado_general_califi, true));
            return response()->json($info_lista_estado_general_califi);

        }
    }
}
