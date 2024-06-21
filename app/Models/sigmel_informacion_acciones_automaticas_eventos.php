<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_acciones_automaticas_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;

    protected $primaryKey= 'Id_accion_automatica';

    protected $fillable = [
    'Id_Asignacion',
    'ID_evento',
    'Id_proceso',
    'Id_servicio',
    'Id_cliente',
    'Accion_automatica',
    'F_accion',
    'Id_profesional_automatico',
    'Nombre_profesional_automatico',
    'F_movimiento_automatico',
    'Estado_accion_automatica',
    'Nombre_usuario',
    'F_registro',
    ];
}
