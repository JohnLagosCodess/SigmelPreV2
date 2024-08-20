<?php

namespace App\Imports;

use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\sigmel_informacion_comunicado_eventos;
use App\Models\sigmel_informacion_correspondencia_eventos;

class CargueNotificaciones implements ToModel, WithHeadingRow
{
    
    public function model(array $row)
    {
        // Probar la conexión a la base de datos
        try {
            DB::connection()->getPdo();
            // Log::info('Conexión a la base de datos establecida correctamente.');
        } catch (\Exception$e) {
            // Log::error('No se puede conectar a la base de datos: ' . $e->getMessage());
            return null;
        }
    
        // Resto del código...
        try {
            $data = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones');
            // Log::info('Modelo cargado correctamente.');
        } catch (\Exception$e) {
            // Log::error('Error al usar el modelo: ' . $e->getMessage());
            return null;
        }

        // Log::info('Datos de la fila antes de la validación: ', $row);

        
        // Convertir desde la serie de fechas de Excel a un timestamp, y luego a Y-m-d
        try {
            $row['f_envio'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['f_envio']))->format('Y-m-d');
            $row['f_notificacion'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['f_notificacion']))->format('Y-m-d');
            // Log::info('Fechas convertidas: f_envio=' . $row['f_envio'] . ', f_notificacion=' . $row['f_notificacion']);
        } catch (\Exception $e) {
            // Log::error('Error al procesar las fechas en la fila: ' . json_encode($row) . ' - Mensaje: ' . $e->getMessage());
            return null;
        }

        // Validar los datos de la fila
        $validaciones = Validator::make($row, [
            'id_correspondencia' => 'required|numeric',
            'id_asignacion' => 'required|numeric',
            'id_comunicado' => 'required|numeric',
            'n_radicado' => 'required|string',
            'nombre_documento' => 'required|string',
            'carpeta_impresion' => 'required|string',
            'destinatario' => 'required|string',
            'tipo_destinatario' => 'required|string',
            'n_guia' => 'required|numeric',
            'folios' => 'required|numeric',
            'f_envio' => 'required|date_format:Y-m-d',
            'f_notificacion' => 'required|date_format:Y-m-d',
            'estado_correspondencia' => 'required|string',
            'copias' => 'string',
            'jrci' => 'nullable|string',            
        ]);

        // Log::info('Datos de la fila Despues de la validación: ', $row);
        // Manejar errores de validación según validator 
        if ($validaciones->fails()) {
            // Log::info('Encabezados en el archivo: ' . implode(', ', array_keys($row)));
            // Log::error('Error de validación en la fila: ', $validaciones->errors()->toArray());
            return null;
        }

        // Cambiar el valor de estado_correspondencia basado en las condiciones de la tabla  sigmel_lista_parametros
        switch ($row['estado_correspondencia']) {
            case 'Notificado':
                $row['estado_correspondencia'] = 363;
                break;
            case 'Devuelto':
                $row['estado_correspondencia'] = 364;
                break;
            case 'Pendiente':
                $row['estado_correspondencia'] = 365;
                break;
        }

        // Log::info('Datos de la fila después de modificar estado_correspondencia: ', $row);

        // Realizar una operación de actualización o inserción en la base de datos
        
        //    Convertir los ID a entero
        $idComunicado = intval($row['id_comunicado']);
        $idCorrespondencia = intval($row['id_correspondencia']);
        $idAsignacion = intval($row['id_asignacion']);


        // Buscar  el registro en la tabla sigmel_informacion_comunicado_eventos
        $actualizar_comunicado = sigmel_informacion_comunicado_eventos::on('sigmel_gestiones')
        ->where('Id_Comunicado', $idComunicado)
        ->first();

        if ($actualizar_comunicado) {
            // Log::info('Registro encontrado: ' . json_encode($actualizar_comunicado));
            // Actualizar el registro
            $actualizar_comunicado->update([
                'Agregar_copia' => $row['copias'],
                'JRCI_copia' => $row['jrci'],
            ]);
            // Log::info('Registro actualizado correctamente para Id_Comunicado: ' . $idComunicado);
        } else {
            // Log::warning('No se encontró ningún registro con Id_Comunicado: ' . $idComunicado);
        }

        // Buscar  el registro en la tabla sigmel_informacion_correspondencia_eventos
        $actualizar_correspondencia = sigmel_informacion_correspondencia_eventos::on('sigmel_gestiones')
        ->where('Id_Correspondencia', $idCorrespondencia)
        ->first();

        if ($actualizar_correspondencia) {
            // Log::info('Registro encontrado: ' . json_encode($actualizar_correspondencia));
            // Actualizar el registro
            $actualizar_correspondencia->update([
                'Tipo_destinatario' => $row['tipo_destinatario'],
                'N_guia' => $row['n_guia'],
                'Folios' => $row['folios'],
                'F_envio' => $row['f_envio'],
                'F_notificacion' => $row['f_notificacion'],  
                'Id_Estado_corresp' => $row['estado_correspondencia'],
            ]);
            // Log::info('Registro actualizado correctamente para Id_Correspondencia: ' . $idCorrespondencia);
        } else {
            // Log::warning('No se encontró ningún registro con Id_Correspondencia: ' . $idCorrespondencia);
        }

    }
}
