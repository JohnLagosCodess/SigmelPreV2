<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_alertas_ans_eventos extends Model
{
    public $timestamps =false;

    protected $primaryKey= 'Id_alerta_ans';

    protected $fillable = [
        'ID_evento',
        'Id_Asignacion',
        'Id_ans',
        'Fecha_alerta_naranja',
        'Fecha_alerta_roja',
        'Nombre_usuario',
        'F_registro'
    ];
}
