<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_historial_accion_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_historial_accion';

    protected $fillable = [
    'Id_Asignacion',
    'ID_evento',
    'Id_proceso',
    'Id_servicio',
    'Id_accion',
    'Documento',
    'Descripcion',
    'F_accion',
    'Nombre_usuario',    
    ];
}
