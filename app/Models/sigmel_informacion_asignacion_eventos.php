<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_asignacion_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Asignacion';

    protected $fillable = [
        'ID_evento',
        'Id_proceso',
        'Visible_Nuevo_Proceso',
        'Id_servicio',
        'Visible_Nuevo_Servicio',
        'Id_accion',
        'Descripcion',
        'F_alerta',
        'Id_Estado_evento',
        'F_accion',
        'F_radicacion',
        'Nueva_F_radicacion',
        'N_de_orden',
        'Id_proceso_anterior',
        'Id_servicio_anterior',
        'F_asignacion_calificacion',
        'Consecutivo_dictamen',
        'Id_profesional',
        'Nombre_profesional',
        'Id_calificador',
        'Nombre_calificador',
        'Descripcion_bandeja',
        'F_calificacion',
        'F_ajuste_calificacion',
        'Detener_tiempo_gestion',
        'F_detencion_tiempo_gestion',
        'Fuente_info_juntas',
        'Notificacion',
        'F_remision_expediente',
        'Id_profesional_remision_expediente',
        'Profesional_remision_expediente',
        'Id_profesional_pronunciamiento',
        'Profesional_pronunciamiento',
        'F_pronunciamiento',
        'Nombre_usuario',
        'F_registro'
    ];
}