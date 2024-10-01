<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_comunicado_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_Comunicado';

    protected $fillable = [
    'ID_evento',
    'Id_Asignacion',
    'Id_proceso',
    'Ciudad',
    'F_comunicado',
    'N_radicado',
    'Cliente',
    'Nombre_afiliado',
    'T_documento',
    'N_identificacion',
    'Destinatario',
    'JRCI_Destinatario',
    'Nombre_destinatario',
    'Nit_cc',
    'Direccion_destinatario',
    'Telefono_destinatario',
    'Email_destinatario',
    'Id_departamento',
    'Id_municipio',
    'Asunto',
    'Cuerpo_comunicado',
    'Anexos',
    'Forma_envio',
    'Elaboro',
    'Reviso',
    'Agregar_copia',
    'JRCI_copia',
    'Firmar_Comunicado',
    'Tipo_descarga',
    'Modulo_creacion',
    'Reemplazado',
    'Nombre_documento',
    'Lista_chequeo',
    'N_siniestro',
    'Nota',
    'Estado_Notificacion',
    'Correspondencia',
    'Otro_destinatario',
    'Id_Destinatarios',
    'Nombre_usuario',
    'F_registro'
    ];
}
