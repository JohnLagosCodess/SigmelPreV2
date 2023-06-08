<?php
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
?>