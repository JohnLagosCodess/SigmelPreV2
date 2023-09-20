<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_rol_ocupacional_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_Rol_ocupacional';

    protected $fillable = [
        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'Poblacion_calificar',
        'Motriz_postura_simetrica',
        'Motriz_actividad_espontanea',
        'Motriz_sujeta_cabeza',
        'Motriz_sentarse_apoyo',
        'Motriz_gira_sobre_mismo',
        'Motriz_sentanser_sin_apoyo',
        'Motriz_pasa_tumbado_sentado',
        'Motriz_pararse_apoyo',
        'Motriz_pasos_apoyo',
        'Motriz_pararse_sin_apoyo',
        'Motriz_anda_solo',
        'Motriz_empujar_pelota_pies',
        'Motriz_andar_obstaculos',
        'Adaptativa_succiona',
        'Adaptativa_fija_mirada',
        'Adaptativa_sigue_trayectoria_objeto',
        'Adaptativa_sostiene_sonajero',
        'Adaptativa_tiende_mano_hacia_objeto',
        'Adaptativa_sostiene_objeto_manos',
        'Adaptativa_abre_cajones',
        'Adaptativa_bebe_solo',
        'Adaptativa_quitar_prenda_vestir',
        'Adaptativa_reconoce_funcion_espacios_casa',
        'Adaptativa_imita_trazo_lapiz',
        'Adaptativa_abre_puerta',
        'Total_criterios_desarrollo',
        'Juego_estudio_clase',
        'Total_rol_estudio_clase',
        'Adultos_mayores',
        'Total_rol_adultos_ayores',
        'Nombre_usuario',
        'F_registro',
    ];
}
