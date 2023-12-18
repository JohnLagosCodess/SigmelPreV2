<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_afiliado_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Afiliado';

    protected $fillable = [
    'ID_evento',
    'Nombre_afiliado',
    'Tipo_documento',
    'Nro_identificacion',
    'F_nacimiento',
    'Edad',
    'Genero',
    'Email',
    'Telefono_contacto',
    'Estado_civil',
    'Nivel_escolar',
    'Apoderado',
    'Nombre_apoderado',
    'Nro_identificacion_apoderado',
    'Id_dominancia',
    'Direccion',
    'Id_departamento',
    'Id_municipio',
    'Ocupacion',
    'Tipo_afiliado',
    'Ibc',
    'Id_eps',
    'Id_afp',
    'Id_arl',
    'Activo',
    'Medio_notificacion',
    'Nombre_usuario',
    'F_registro',
    'F_actualizacion'
    ];
}