<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_registro_descarga_documentos extends Model
{
    // use HasFactory;
    public $timestamps = false;
    
    protected $primaryKey = 'Id_registro_documento';

    protected $fillable = [
        'Id_Asignacion',
        'Id_proceso',
        'Id_servicio',
        'ID_evento',
        'Nombre_documento',
        'N_radicado_documento',
        'F_elaboracion_correspondencia',
        'F_descarga_documento',
        'Nombre_usuario'
    ];
}
