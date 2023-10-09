<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_dto_atel_eventos extends Model
{
    // use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Dto_ATEL';

    protected $fillable = [
        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'Activo',
        'Tipo_evento',
        'Fecha_dictamen',
        'Numero_dictamen',
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
        'Fecha_diagnostico_enfermedad',
        'Enfermedad_heredada',
        'Nombre_entidad_hereda',
        'Justificacion_revision_origen',
        'Relacion_documentos',
        'Otros_relacion_documentos',
        'Sustentacion',
        'Origen',
        'Nombre_usuario',
        'F_registro'
    ];
}
