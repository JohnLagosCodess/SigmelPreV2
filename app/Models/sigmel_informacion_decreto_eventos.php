<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_decreto_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_decreto';

    protected $fillable = [

    'ID_Evento',
    'Id_proceso',
    'Id_Asignacion',
    'Origen_firme',
    'Cobertura',
    'Decreto_calificacion',
    'Numero_dictamen',
    'PCL_anterior',
    'Descripcion_nueva_calificacion',
    'Relacion_documentos',
    'Otros_relacion_doc',
    'Descripcion_enfermedad_actual',
    'Suma_combinada',
    'Total_Deficiencia50',
    'Porcentaje_pcl',
    'Rango_pcl',
    'Monto_indemnizacion',
    'Tipo_evento',
    'Origen',
    'F_evento',
    'F_estructuracion',
    'Requiere_Revision_Pension',
    'Sustentacion_F_estructuracion',
    'Detalle_calificacion',
    'Enfermedad_catastrofica',
    'Enfermedad_congenita',
    'Tipo_enfermedad',
    'Requiere_tercera_persona',
    'Requiere_tercera_persona_decisiones',
    'Requiere_dispositivo_apoyo',
    'Justificacion_dependencia',
    'N_radicado',
    'N_siniestro',
    'Estado_decreto',
    'Modalidad_calificacion',
    'Nombre_usuario',
    'F_registro',
    ];
}
