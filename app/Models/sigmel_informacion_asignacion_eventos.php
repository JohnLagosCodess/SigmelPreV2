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
    'Id_servicio',
    'Id_accion',
    'Descripcion',
    'F_alerta',
    'Id_Estado_procesos',
    'F_accion',
    'Nombre_usuario',
    'F_registro',
    ];
}