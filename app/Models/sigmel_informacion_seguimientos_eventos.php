<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_seguimientos_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey = 'Id_Seguimiento';

    protected $fillable = [
    'ID_evento',
    'Id_Asignacion',
    'F_seguimiento',
    'Causal_seguimiento',
    'Descripcion_seguimiento',
    'Nombre_usuario',
    'F_registro'
    ];
}
