<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_deficiencias_alteraciones_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_Deficiencia';

    protected $fillable = [
        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'Id_tabla',
        'FP',
        'CFM1',
        'CFM2',
        'FU',
        'CAT',
        'Clase_Final',
        'Dx_Principal',
        'MSD',
        'Dominancia',
        'Deficiencia',
        'Total_deficiencia',
        'Estado',
        'Estado_Recalificacion',
        'Nombre_usuario',
        'F_registro',
    ];
}
