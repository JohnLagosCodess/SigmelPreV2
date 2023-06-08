<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_pericial_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Pericial';

    protected $fillable = [
    'ID_evento',
    'Id_motivo_solicitud',
    'Tipo_vinculacion',
    'Regimen_salud',
    'Id_solicitante',
    'Id_nombre_solicitante',
    'Fuente_informacion',
    'Nombre_usuario',
    'F_registro',
    ];
}