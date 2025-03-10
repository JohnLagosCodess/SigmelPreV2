<?php

namespace App\Imports;

use App\Models\sigmel_probando;
use Maatwebsite\Excel\Concerns\ToModel;

class ProbandoImportCsvSinEncabezados implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        /* echo "<pre>";
        print_r($row);
        echo "</pre>"; */

        sigmel_probando::on('mysql2')->insert([
            'nombre'=> $row[0],
            'created_at' => $row[1],
            'updated_at' => $row[2],
        ]);
    }
}
