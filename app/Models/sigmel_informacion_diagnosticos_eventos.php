<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_diagnosticos_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_Diagnosticos_motcali';

    protected $fillable = [

        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'CIE10',
        'Nombre_CIE10',
        'Origen_CIE10',
        'Lateralidad_CIE10',
        'Deficiencia_motivo_califi_condiciones',
        'Principal',
        'F_adicion_CIE10',
        'Estado',
        'Estado_Recalificacion',
        'Nombre_usuario',
        'F_registro',
    ];
}
