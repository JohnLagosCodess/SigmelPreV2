<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_pronunciamiento_eventos extends Model
{
    public $timestamps =false;
    protected $primaryKey = 'Id_Pronuncia';

    protected $fillable = [
        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'Id_primer_calificador',
        'Id_nombre_calificador',
        'Nit_calificador',
        'Dir_calificador',
        'Email_calificador',
        'Telefono_calificador',
        'Depar_calificador',
        'Ciudad_calificador',
        'Id_tipo_pronunciamiento',
        'Id_tipo_evento',
        'Id_tipo_origen',
        'Fecha_evento',
        'Dictamen_calificador',
        'Fecha_calificador',
        'Fecha_estruturacion',
        'Porcentaje_pcl',
        'Rango_pcl',
        'Decision',
        'Fecha_pronuncia',
        'Asunto_cali',
        'Sustenta_cali',
        'Copia_afiliado',
        'Copia_empleador',
        'Copia_eps',
        'Copia_afp',
        'Copia_arl',
        'Copia_junta_regional',
        'Copia_junta_nacional',
        'Junta_regional_cual',
        'N_anexos',
        'Elaboro_pronuncia',
        'Reviso_pronuncia',
        'Ciudad_correspon',
        'N_radicado',
        'Firmar',
        'Fecha_correspondencia',
        'Archivo_pronuncia'
        ];
}
