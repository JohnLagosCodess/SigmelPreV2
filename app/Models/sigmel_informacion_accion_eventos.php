<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_accion_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;

    protected $primaryKey= 'Id_Accion';

    protected $fillable = [
    'ID_evento',
    'Id_Asignacion',
    'Modalidad_calificacion',
    'F_accion',
    'Accion',
    'F_Alerta',
    'Enviar',
    'Causal_devolucion_comite',
    'Descripcion_accion',
    'Nombre_usuario',
    'F_registro',
    ];
}