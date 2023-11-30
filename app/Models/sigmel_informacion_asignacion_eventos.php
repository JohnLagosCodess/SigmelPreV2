<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_asignacion_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Asignacion';

    protected $fillable = [
        'ID_evento',
        'Id_proceso',
        'Visible_Nuevo_Proceso',
        'Id_servicio',
        'Visible_Nuevo_Servicio',
        'Id_accion',
        'Descripcion',
        'F_alerta',
        'Id_Estado_evento',
        'F_accion',
        'F_radicacion',
        'N_de_orden',
        'Id_proceso_anterior',
        'Id_servicio_anterior',
        'F_asignacion_calificacion',
        'Id_profesional',
        'Nombre_profesional',
        'Nombre_usuario',
        'F_registro'
    ];
}