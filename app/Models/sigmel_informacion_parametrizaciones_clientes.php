<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_parametrizaciones_clientes extends Model
{
    // use HasFactory;
    public $timestamps = false;
    
    protected $primaryKey = 'Id_parametrizacion';

    protected $fillable = [
        'Id_cliente',
        'Id_proceso',
        'F_creacion_movimiento',
        'Servicio_asociado',
        'Estado',
        'Accion_ejecutar',
        'Accion_antecesora',
        'Modulo_nuevo',
        'Modulo_consultar',
        'Bandeja_trabajo',
        'Modulo_principal',
        'Detiene_tiempo_gestion',
        'Equipo_trabajo',
        'Profesional_asignado',
        'Enviar_a_bandeja_trabajo_destino',
        'Bandeja_trabajo_destino',
        'Estado_facturacion',
        'Movimiento_automatico',
        'Tiempo_movimiento',
        'Accion_automatica',
        'Tiempo_alerta',
        'Porcentaje_alerta_naranja',
        'Porcentaje_alerta_roja',
        'Status_parametrico',
        'Motivo_descripcion_movimiento',
        'Nombre_usuario',
        'F_actualizacion_movimiento',
    ];
}
