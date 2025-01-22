<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_alertas_automaticas_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;

    protected $primaryKey= 'Id_alerta_automatica';

    protected $fillable = [
    'Id_Asignacion',
    'ID_evento',
    'Id_proceso',
    'Id_servicio',
    'Id_cliente',
    'Accion_ejecutar',
    'F_accion',
    'Tiempo_alerta',
    'Porcentaje_alerta_naranja',
    'F_accion_alerta_naranja',
    'Porcentaje_alerta_roja',
    'F_accion_alerta_roja',
    'Estado_alerta_automatica',
    'Nombre_usuario',
    'F_registro',    
    
    ];
}
