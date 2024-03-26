<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\sigmel_registro_descarga_documentos;

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
            3. Fecha desde y Fecha hasta están vacíos = Se genera reporte.
            4. Fecha desde y Fecha hasta tienen datos = Se genera reporte.
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
            echo "se genera reporte";
        }
        else if (!empty($fecha_desde) && !empty($fecha_hasta)){
            echo "se genera reporte";
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
            3. Fecha desde y Fecha hasta están vacíos = Se genera reporte.
            4. Fecha desde y Fecha hasta tienen datos = Se genera reporte.
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
            $documentos_archivo_1 = sigmel_registro_descarga_documentos::on('sigmel_gestiones')
            ->select('ID_evento','Nombre_documento')
            ->get();

            $array_documento_archivo_1 = json_decode(json_encode($documentos_archivo_1), true);

            // Ruta donde se guardará el archivo comprimido
            $rutaArchivoComprimido = storage_path('app/'.$date.' Correspondencia SIGMEL.zip');

            // Crear un nuevo archivo zip
            $zip = new ZipArchive;
            if ($zip->open($rutaArchivoComprimido, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Agregar cada archivo al archivo zip
                foreach ($array_documento_archivo_1 as $archivo) {
                    $rutaArchivo = "Documentos_Eventos/{$archivo['ID_evento']}/{$archivo['Nombre_documento']}";
                    // $rutaArchivo = public_path('Documentos_Eventos/') . $archivo;
                    if (file_exists($rutaArchivo)) {
                        $zip->addFile($rutaArchivo, $archivo['Nombre_documento']);
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

            return response()->json(['url' => $urlArchivoComprimido, 'nom_archivo' => $nombreArchivoComprimido]);

            // Descargar el archivo comprimido
            // return response()->download($rutaArchivoComprimido);
            // return response()->download($rutaArchivoComprimido)->deleteFileAfterSend(true);
            // return response()->json(['url' => $rutaArchivoComprimido]);

        }
        else if (!empty($fecha_desde) && !empty($fecha_hasta)){
            echo "se genera reporte";
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
