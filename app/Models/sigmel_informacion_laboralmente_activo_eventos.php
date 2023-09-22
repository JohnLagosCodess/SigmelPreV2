<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_laboralmente_activo_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_Laboral_activo';

    protected $fillable = [
        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'Restricciones_rol',
        'Autosuficiencia_economica',
        'Edad_cronologica_menor',
        'Edad_cronologica',
        'Total_rol_laboral',
        'Aprendizaje_mirar',
        'Aprendizaje_escuchar',
        'Aprendizaje_aprender',
        'Aprendizaje_calcular',
        'Aprendizaje_pensar',
        'Aprendizaje_leer',
        'Aprendizaje_escribir',
        'Aprendizaje_matematicos',
        'Aprendizaje_resolver',
        'Aprendizaje_tareas',
        'Aprendizaje_total',
        'Comunicacion_verbales',
        'Comunicacion_noverbales',
        'Comunicacion_formal',
        'Comunicacion_escritos',
        'Comunicacion_habla',
        'Comunicacion_produccion',
        'Comunicacion_mensajes',
        'Comunicacion_conversacion',
        'Comunicacion_discusiones',
        'Comunicacion_dispositivos',
        'Comunicacion_total',
        'Movilidad_cambiar_posturas',
        'Movilidad_mantener_posicion',
        'Movilidad_objetos',
        'Movilidad_uso_mano',
        'Movilidad_mano_brazo',
        'Movilidad_Andar',
        'Movilidad_desplazarse',
        'Movilidad_equipo',
        'Movilidad_transporte',
        'Movilidad_conduccion',
        'Movilidad_total',
        'Cuidado_lavarse',
        'Cuidado_partes_cuerpo',
        'Cuidado_higiene',
        'Cuidado_vestirse',
        'Cuidado_quitarse',
        'Cuidado_ponerse_calzado',
        'Cuidado_comer',
        'Cuidado_beber',
        'Cuidado_salud',
        'Cuidado_dieta',
        'Cuidado_total',
        'Domestica_vivir',
        'Domestica_bienes',
        'Domestica_comprar',
        'Domestica_comidas',
        'Domestica_quehaceres',
        'Domestica_limpieza',
        'Domestica_objetos',
        'Domestica_ayudar',
        'Domestica_mantenimiento',
        'Domestica_animales',
        'Domestica_total',
        'Total_otras_areas',
        'Total_laboral_otras_areas',
        'Nombre_usuario',
        'F_registro',
    ];
}
