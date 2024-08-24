<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_correspondencia_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Correspondencia';

    protected $fillable = [
    'ID_evento',
    'Id_Asignacion',
    'Id_proceso',
    'Id_servicio',
    'Id_comunicado',
    'Nombre_afiliado',
    'N_identificacion',
    'N_radicado',
    'N_orden',
    'Tipo_destinatario',
    'Nombre_destinatario',
    'Direccion_destinatario',
    'Departamento',
    'Ciudad',
    'Telefono_destinatario',
    'Email_destinatario',
    'Medio_notificacion',
    'N_guia',
    'Folios',
    'F_envio',
    'F_notificacion',
    'Id_Estado_corresp',
    'Tipo_correspondencia',
    'Nombre_usuario',
    'F_registro',
    ];
}
