<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_laboral_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Laboral';

    protected $fillable = [
    'ID_evento',
    'Nro_identificacion',
    'Tipo_empleado',
    'Id_arl',
    'Empresa',
    'Nit_o_cc',
    'Telefono_empresa',
    'Email',
    'Direccion',
    'Id_departamento',
    'Id_municipio',
    'Id_actividad_economica',
    'Id_clase_riesgo',
    'Persona_contacto',
    'Telefono_persona_contacto',
    'Id_codigo_ciuo',
    'F_ingreso',
    'Cargo',
    'Funciones_cargo',
    'Antiguedad_empresa',
    'Antiguedad_cargo_empresa',
    'F_retiro',
    'Descripcion',
    'Nombre_usuario',
    'F_registro',
    ];
}