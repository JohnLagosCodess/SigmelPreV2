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
    'Entidad_conocimiento',
    'Id_afp_entidad_conocimiento',
    'Id_afp_entidad_conocimiento2',
    'Id_afp_entidad_conocimiento3',
    'Id_afp_entidad_conocimiento4',
    'Id_afp_entidad_conocimiento5',
    'Id_afp_entidad_conocimiento6',
    'Id_afp_entidad_conocimiento7',
    'Id_afp_entidad_conocimiento8',
    'Otras_entidades_conocimiento',
    'Activo',
    'Nombre_afiliado_benefi',
    'Tipo_documento_benefi',
    'Nro_identificacion_benefi',
    'Direccion_benefi',
    'Id_departamento_benefi',
    'Id_municipio_benefi',
    'Medio_notificacion',
    'Nombre_usuario',
    'F_registro',
    'F_actualizacion'
    ];
}