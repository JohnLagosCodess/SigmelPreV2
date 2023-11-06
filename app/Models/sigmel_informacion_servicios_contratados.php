<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_servicios_contratados extends Model
{
    // use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_Servicio_Contratado';

    protected $fillable = [
        'Id_cliente',
        'Id_proceso',
        'Id_servicio',
        'Valor_tarifa_servicio',
        'Nro_consecutivo_dictamen_servicio',
        'Nombre_usuario',
        'F_registro'
    ];
}
