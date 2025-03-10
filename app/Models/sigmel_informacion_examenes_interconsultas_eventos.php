<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_examenes_interconsultas_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_Examenes_interconsultas';

    protected $fillable = [

        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'F_examen_interconsulta',
        'Nombre_examen_interconsulta',
        'Descripcion_resultado',
        'Estado',
        'Estado_Recalificacion',
        'Nombre_usuario',
        'F_registro',
    ];
}
