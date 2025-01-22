<?php

namespace App\Imports;

use App\Models\sigmel_probando;
use Maatwebsite\Excel\Concerns\ToModel;

class ProbandoImportXslxSinEncabezados implements ToModel
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
            'created_at' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1])->format('Y-m-d h:i:s'),
            'updated_at' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2])->format('Y-m-d h:i:s'),
        ]);
    }
}
