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
        'Tipo_accidente',
        'Fecha_evento',
        'Hora_evento',
        'Grado_severidad',
        'Mortal',
        'Fecha_fallecimiento',
        'Descripcion_FURAT',
        'Factor_riesgo',
        'Tipo_lesion',
        'Parte_cuerpo_afectada',
        'Justificacion_revision_origen',
        'Sustentacion',
        'Nombre_usuario',
        'F_registro'
    ];

    
}
