<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_comite_interdisciplinario_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_com_inter';

    protected $fillable = [        
        'ID_evento',
        'Id_proceso',
        'Id_Asignacion',
        'Visar',
        'Profesional_comite',
        'F_visado_comite',
        'Oficio_pcl',
        'Oficio_incapacidad',
        'Destinatario_principal',
        'Otro_destinatario',
        'Tipo_destinatario',
        'Nombre_dest_principal',
        'Nombre_dest_principal_afi_empl',
        'Nombre_destinatario',
        'Nit_cc',
        'Direccion_destinatario',
        'Telefono_destinatario',
        'Email_destinatario',
        'Departamento_destinatario',
        'Ciudad',
        'Asunto',
        'Cuerpo_comunicado',
        'Copia_empleador',
        'Copia_eps',
        'Copia_afp',
        'Copia_arl',
        'Copia_jr',
        'Cual_jr',
        'Copia_jn',
        'Anexos',
        'Elaboro',
        'Reviso',
        'Firmar',
        'Ciudad',
        'F_correspondecia',
        'N_radicado',
        'Nombre_usuario',
        'F_registro'
    ];
}
