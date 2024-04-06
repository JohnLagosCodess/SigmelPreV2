<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\sigmel_registro_descarga_documentos;

use App\Models\cndatos_reporte_notificaciones_v5s;

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

    /* Función para consultar el reporte de notificaciones */
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
            
            /* $reporte_notificaciones = cndatos_reporte_notificaciones_v5s::on('sigmel_gestiones')
            ->select('Fecha_envio', 'No_identificacion', 'No_guia_asignado', 'Orden_impresion', 'Proceso', 'Servicio', 'Ultima_Accion',
            'Estado', 'No_OIP', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion', 'Telefono', 'Departamento', 'Ciudad',
            'Folios_entregados', 'Medio_Notificacion', 'Correo_electronico', 'Archivo_1', 'Archivo_2')
            ->get();
            $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true));
            return response()->json($array_reporte_notificaciones); */
            $mensajes = array(
                "parametro" => 'falta_un_parametro',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        else if (!empty($fecha_desde) && !empty($fecha_hasta)){
            $reporte_notificaciones = cndatos_reporte_notificaciones_v5s::on('sigmel_gestiones')
            ->select('Fecha_envio', 'No_identificacion', 'No_guia_asignado', 'Orden_impresion', 'Proceso', 'Servicio', 'Ultima_Accion',
            'Estado', 'No_OIP', 'Tipo_destinatario', 'Nombre_destinatario', 'Direccion', 'Telefono', 'Departamento', 'Ciudad',
            'Folios_entregados', 'Medio_Notificacion', 'Correo_electronico', 'Archivo_1', 'Archivo_2')
            ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
            ->orderBy('ID_evento', 'desc')
            ->get();
            $array_reporte_notificaciones = json_decode(json_encode($reporte_notificaciones, true)); 
            return response()->json($array_reporte_notificaciones);
        }
    }

    /* Función para descargar los archivos comprimidos */
    public function generarZipReporteNotificaciones(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);

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

            /* // Extraemos los documentos de la columna Archivo 1
            $documentos_archivo_1 = cndatos_reporte_notificaciones_v5s::on('sigmel_gestiones')
            ->select('ID_evento','Archivo_1')
            ->get();
            $array_documentos_archivo_1 = json_decode(json_encode($documentos_archivo_1, true)); 
            
            // Extraemos los documentos de la columna Archivo 2
            $documentos_archivo_2 = cndatos_reporte_notificaciones_v5s::on('sigmel_gestiones')
            ->select('ID_evento','Archivo_2')
            ->get();
            $array_documentos_archivo_2 = json_decode(json_encode($documentos_archivo_2, true)); 

            // Ruta donde se guardará el archivo comprimido
            $rutaArchivoComprimido = storage_path('app/'.$date.' Correspondencia SIGMEL.zip');

            // Crear un nuevo archivo zip
            $zip = new ZipArchive;
            if ($zip->open($rutaArchivoComprimido, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Agregar cada archivo 1 al archivo zip
                foreach ($array_documentos_archivo_1 as $archivo) {
                    if ($archivo->Archivo_1 <> "") {
                        $rutaArchivo = "Documentos_Eventos/{$archivo->ID_evento}/{$archivo->Archivo_1}";
                        // $rutaArchivo = public_path('Documentos_Eventos/') . $archivo;
                        if (file_exists($rutaArchivo)) {
                            $zip->addFile($rutaArchivo, $archivo->Archivo_1);
                        }
                    }
                }
                sleep(2);

                // Agregar cada archivo 2 al archivo zip
                foreach ($array_documentos_archivo_2 as $archivo2) {
                    if ($archivo2->Archivo_2 <> "") {
                        $rutaArchivo = "Documentos_Eventos/{$archivo2->ID_evento}/{$archivo2->Archivo_2}";
                        // $rutaArchivo = public_path('Documentos_Eventos/') . $archivo;
                        if (file_exists($rutaArchivo)) {
                            $zip->addFile($rutaArchivo, $archivo2->Archivo_2);
                        }
                    }
                }
                // Cerrar el archivo zip
                $zip->close();
            }

            // Mover el archivo zip al directorio público
            $nombreArchivoComprimido = $date.' Correspondencia SIGMEL.zip';
            $ubicacionDestino = public_path($nombreArchivoComprimido);
            File::move($rutaArchivoComprimido, $ubicacionDestino);

            // Devolver la URL del archivo zip en la respuesta Ajax
            $urlArchivoComprimido = asset($nombreArchivoComprimido);

            return response()->json(['url' => $urlArchivoComprimido, 'nom_archivo' => $nombreArchivoComprimido]); */
            $mensajes = array(
                "parametro" => 'error',
                "mensaje" => 'Debe seleccionar las dos fechas para generar el zip.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        else if (!empty($fecha_desde) && !empty($fecha_hasta)){
            // Extraemos los documentos de la columna Archivo 1
            $documentos_archivo_1 = cndatos_reporte_notificaciones_v5s::on('sigmel_gestiones')
            ->select('ID_evento','Archivo_1')
            ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
            ->get();
            $array_documentos_archivo_1 = json_decode(json_encode($documentos_archivo_1, true)); 
            
            // Extraemos los documentos de la columna Archivo 2
            $documentos_archivo_2 = cndatos_reporte_notificaciones_v5s::on('sigmel_gestiones')
            ->select('ID_evento','Archivo_2')
            ->whereBetween('F_comunicado', [$fecha_desde , $fecha_hasta])
            ->get();
            $array_documentos_archivo_2 = json_decode(json_encode($documentos_archivo_2, true)); 

            // echo (count($array_documentos_archivo_1));
            
            // Ruta donde se guardará el archivo comprimido
            $rutaArchivoComprimido = storage_path('app/'.$date.' Correspondencia SIGMEL.zip');

            // Crear un nuevo archivo zip
            $zip = new ZipArchive;
            if ($zip->open($rutaArchivoComprimido, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Agregar cada archivo 1 al archivo zip
                foreach ($array_documentos_archivo_1 as $archivo) {
                    if ($archivo->Archivo_1 <> "") {
                        $rutaArchivo = "Documentos_Eventos/{$archivo->ID_evento}/{$archivo->Archivo_1}";
                        // $rutaArchivo = public_path('Documentos_Eventos/') . $archivo;
                        if (file_exists($rutaArchivo)) {
                            $zip->addFile($rutaArchivo, $archivo->Archivo_1);
                        }
                    }
                }
                sleep(2);

                // Agregar cada archivo 2 al archivo zip
                foreach ($array_documentos_archivo_2 as $archivo2) {
                    if ($archivo2->Archivo_2 <> "") {
                        $rutaArchivo = "Documentos_Eventos/{$archivo2->ID_evento}/{$archivo2->Archivo_2}";
                        // $rutaArchivo = public_path('Documentos_Eventos/') . $archivo;
                        if (file_exists($rutaArchivo)) {
                            $zip->addFile($rutaArchivo, $archivo2->Archivo_2);
                        }
                    }
                }
                // Cerrar el archivo zip
                $zip->close();
            }

            if ($zip->numFiles === 0) {
                $mensajes = array(
                    "parametro" => 'error',
                    "mensaje" => 'El archivo .zip no se pudo descargar, debido a que no existen documentos generados por el sistema.'
                );
                return json_decode(json_encode($mensajes, true));
            }else{
                // Mover el archivo zip al directorio público
                $nombreArchivoComprimido = $date.' Correspondencia SIGMEL.zip';
                $ubicacionDestino = public_path($nombreArchivoComprimido);
                File::move($rutaArchivoComprimido, $ubicacionDestino);
    
                // Devolver la URL del archivo zip en la respuesta Ajax
                $urlArchivoComprimido = asset($nombreArchivoComprimido);
                return response()->json(['url' => $urlArchivoComprimido, 'nom_archivo' => $nombreArchivoComprimido]);
            }

        }
        else{
            $mensajes = array(
                "parametro" => 'error',
                "mensaje" => 'Consulte a soporte sobre este error error.'
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
}
