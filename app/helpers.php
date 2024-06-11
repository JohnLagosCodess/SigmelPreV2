<?php
use Illuminate\Support\Facades\DB;

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

    /**
     * Funcion para calcular los dias habiles a partir de una secuencia LunesAViernes | LunesASabado
     * @param string FechaInicio Fecha a partir de la cual se estara realizando el calculo.
     * @param optional Secuencia Secuencia a aplicar para realizar el calculo  LunesAViernes | LunesASabado
     * @return date Fecha Nueva fecha teniendo en cuenta los dias habiles.
     */
    function calcularDiasHabiles(string $FechaInicio,string $Secuencia = 'LunesAViernes'){
       $fecha = DB::select("SELECT sigmel_gestiones.fnCalcularDiasHabilesV2(?,?) as Fecha",[$FechaInicio,$Secuencia]);
       return $fecha[0]->Fecha;
    }
?>