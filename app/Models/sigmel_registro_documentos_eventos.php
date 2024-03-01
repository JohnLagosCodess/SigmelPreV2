<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_registro_documentos_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Registro_Documento';

    protected $fillable = [
    'Id_Documento',
    'ID_evento',
    'Nombre_documento',
    'Formato_documento',
    'Id_servicio',
    'Estado',
    'F_cargue_documento',
    'Nombre_usuario',
    'F_registro',
    ];
}
