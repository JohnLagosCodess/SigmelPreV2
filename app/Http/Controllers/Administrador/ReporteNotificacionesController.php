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
            
            // $reporte_notificaciones = cndatos_reporte_notificaciones_v5s::on('sigmel_gestiones')
            // ->select('Fecha_envio', 'No_identificacion', 'No_guia_asignado', 'Orden_impresion', 'Proceso', 'Servicio', 'Ultima_Accion',
            // 'Estado', 'No_OIP', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion', 'Telefono', 'Departamento', 'Ciudad',
            // 'Folios_entregados', 'Medio_Notificacion', 'Correo_electronico', 'Archivo_1', 'Archivo_2')
            // ->get();
            // $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true));
            // return response()->json($array_reporte_notificaciones);
            $mensajes = array(
                "parametro" => 'falta_un_parametro',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        else if (!empty($fecha_desde) && !empty($fecha_hasta)){
            $reporte_notificaciones = cndatos_reportes_notificaciones::on('sigmel_gestiones')
            ->select(
                'F_comunicado',
                'N_radicado',
                'Nombre_documento',
                'Carpeta_impresion',
                'Observaciones',
                'N_identificacion',
                'Destinatario',
                'Nombre_destinatario',
                'Direccion_destinatario',
                'Telefono_destinatario',
                'Ciudad',
                'Departamento',
                'Email_destinatario',
                'Proceso',
                'Servicio',
                'Accion',
                'Estado',
                'N_orden',
                'Tipo_destinatario',
                'N_guia',
                'Folios',
                'F_envio',
                'F_notificacion',
                'Estado_correspondencia')
            ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
            // ->orderBy('ID_evento', 'desc')
            ->get();

            $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true)); 

            /* Consultamos el nro de orden */
            $array_n_orden = sigmel_numero_orden_eventos::on('sigmel_gestiones')
            ->select('Numero_orden')
            ->get();

            $n_orden = $array_n_orden[0]->Numero_orden;

            $datos = [
                'n_orden' => $n_orden,
                'reporte' => $array_reporte_notificaciones
            ];

            return response()->json($datos);

            // return response()->json($array_reporte_notificaciones);
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

            /* Consultamos unicamente las columnas de Nombre_documento y Carpeta_impresion y ID_evento */
            $datos_reporte_notificaciones = cndatos_reportes_notificaciones::on('sigmel_gestiones')
            ->select('ID_evento', 'Nombre_documento', 'Carpeta_impresion')
            ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
            ->get();

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

    public function cargueCorrespondencias(Request $request)
    {
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
            return back()->with('success', 'Archivo procesado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }
}
