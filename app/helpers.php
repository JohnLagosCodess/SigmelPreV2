<?php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

if (!function_exists("getDatabaseName")) {
    function getDatabaseName($database, $includePeriod = true)
    {
        if (empty(config('database.connections.' . $database . '.database'))) {
            new Exception('no database connection for' . $database);
        }

        if ($includePeriod === false) {
            return config('database.connections.' . $database . '.database');
        }

        return config('database.connections.' . $database . '.database') . '.';
    }
}
if (!function_exists("calcularDiasHabiles")) {
    /**
     * Funcion para calcular los dias habiles a partir de una secuencia LunesAViernes | LunesASabado
     * @param string FechaInicio Fecha a partir de la cual se estara realizando el calculo.
     * @param optional Secuencia Secuencia a aplicar para realizar el calculo  LunesAViernes | LunesASabado
     * @return date Fecha Nueva fecha teniendo en cuenta los dias habiles.
     */
    function calcularDiasHabiles(string $FechaInicio, string $Secuencia = 'LunesAViernes')
    {
        $fecha = DB::select("SELECT sigmel_gestiones.fnCalcularDiasHabilesV2(?,?) as Fecha", [$FechaInicio, $Secuencia]);
        return $fecha[0]->Fecha;
    }
}
if (!function_exists("getRadicado")) {
    /**
     * Funcion para para obtener un numero de radicado en funcion de un proceso en especifico
     * @param string $proceso Proceso al que pertenece la secuencia
     * @return string numero de radicado
     */
    function getRadicado($proceso)
    {
        switch ($proceso) {
            case 'Juntas':
                $p1 = 'SAL-JUN';
                $p2 = date('Ymd'); //fecha actual
                $consecutivo = DB::table(getDatabaseName('sigmel_gestiones') . 'cndatos_info_comunicado_eventos')
                    ->select('N_radicado')
                    ->where('Id_proceso', 3)->max('N_radicado');

                $consecutivo = $consecutivo ? substr($consecutivo, -6) : 0;

                if (date("Ymd") != $p2) {
                    $nuevoConsecutivo = 0;
                }

                //Obtenemos los ultimos 6 digitos del ultimo radicado y =le sumamos 1
                $consecutivo = sprintf("%06d", $consecutivo + 1);

                $radicado = sprintf("%s%s%s", $p1, $p2, $consecutivo);
                break;
            default:
                '';
        }

        return $radicado;
    }
}

if (!function_exists("fechaFormateada")) {
    /**
     * Funcion para para obtener la fecha formateada como: # de día de nombre del mes de # de año 
     * @param string $fecha 
     * @return string fecha formateada
     */
    function fechaFormateada($fecha)
    {

        // Establecemos el idioma a español para que los meses y días se traduzcan correctamente.
        Carbon::setLocale('es');

        // Realizamos el formateo de la fecha como: # de día de nombre del mes de # de año 
        $fecha_formateada = Carbon::parse($fecha)->translatedFormat('d \d\e F \d\e Y');

        return $fecha_formateada;
    }
}
if (!function_exists("generarNumeroEvento")) {
    function generarNumeroEvento(){
        $dato_consecutivoEvento = DB::table(getDatabaseName('sigmel_gestiones') . "sigmel_numero_orden_eventos")->select('Numero_orden')
        ->where([['Proceso', 'General_Evento'], ['Estado', 'activo']])->get();
    
        $Id_evento = $dato_consecutivoEvento[0]->Numero_orden;        
        $consecutivoEventos = (int)$Id_evento;        
        $consecutivoEventos+=1;
        $Nuevo_consecutivoEvento = sprintf("%015d", $consecutivoEventos);   
    
        return $Nuevo_consecutivoEvento;
    }
}

