<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_accion_eventos extends Model
{
    //use HasFactory;

    public $timestamps = false;

    protected $primaryKey= 'Id_Accion';

    protected $fillable = [
    'ID_evento',
    'Id_Asignacion',
    'Modalidad_calificacion',
    'Fuente_informacion',
    'F_accion',
    'Accion',
    'F_Alerta',
    'Enviar',
    'Estado_Facturacion',
    'Causal_devolucion_comite',
    'F_devolucion_comite',
    'Descripcion_accion',
    'F_recepcion_doc_origen',
    'F_asignacion_dto',
    'F_calificacion_servicio',
    'F_asignacion_pronu_juntas',
    'F_cierre',
    'Nombre_usuario',
    'F_registro',
    ];
}
