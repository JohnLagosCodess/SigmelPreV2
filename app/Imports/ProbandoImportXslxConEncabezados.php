<?php

namespace App\Imports;

use App\Models\sigmel_probando;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProbandoImportXslxConEncabezados implements ToModel, WithHeadingRow
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
            'nombre'=> $row['nombre_usuario'],
            'created_at' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_creacion'])->format('Y-m-d h:i:s'),
            'updated_at' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_actualizacion'])->format('Y-m-d h:i:s'),
        ]);
    }
}
