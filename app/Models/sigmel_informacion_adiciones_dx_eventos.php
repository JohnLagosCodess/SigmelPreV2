<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_adiciones_dx_eventos extends Model
{
    // use HasFactory;
    public $timestamps = false;
    
    protected $primaryKey = 'Id_Adiciones_Dx';

    protected $fillable = [
        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'Id_Dto_ATEL',
        'Activo',
        'Tipo_evento',
        'Relacion_documentos',
        'Otros_relacion_documentos',
        'Sustentacion_Adicion_Dx',
        'Origen',
        'N_radicado',
        'N_siniestro',
        'Nombre_usuario',
        'F_registro'
    ];

    
}
