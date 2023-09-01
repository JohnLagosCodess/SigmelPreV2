<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_agudeza_auditiva_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_Agudeza_auditiva';

    protected $fillable = [

        'ID_evento',
        'Id_Asignacion',
        'Id_proceso',
        'Oido_Izquierdo',
        'Oido_Derecho',
        'Deficiencia_monoaural_izquierda',
        'Deficiencia_monoaural_derecha',
        'Deficiencia_binaural',
        'Adicion_tinnitus',
        'Deficiencia',
        'Estado',
        'Nombre_usuario',
        'F_registro',
    ];
}
