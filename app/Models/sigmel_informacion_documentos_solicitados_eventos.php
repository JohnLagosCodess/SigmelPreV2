<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_documentos_solicitados_eventos extends Model
{
    // use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Documento_Solicitado';

    protected $fillable = [
        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'F_solicitud_documento',
        'Id_Documento',
        'Nombre_documento',
        'Descripcion',
        'Id_solicitante',
        'Nombre_solicitante',
        'F_recepcion_documento',
        'Estado',
        'Nombre_usuario',
        'F_registro'
    ];

}
