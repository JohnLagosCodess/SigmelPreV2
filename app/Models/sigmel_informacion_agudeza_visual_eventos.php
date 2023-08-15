<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_agudeza_visual_eventos extends Model
{
    // use HasFactory;
    public $timestamps = false;
    
    protected $primaryKey = 'Id_agudeza';

    protected $fillable = [
       'ID_evento',
       'Id_Asignacion',
       'Id_proceso',
       'Ceguera_Total',
       'Agudeza_Ojo_Izq',
       'Agudeza_Ojo_Der',
       'Agudeza_Ambos_Ojos',
       'PAVF',
       'DAV',
       'Campo_Visual_Ojo_Izq',
       'Campo_Visual_Ojo_Der',
       'Campo_Visual_Ambos_Ojos',
       'CVF',
       'DCV',
       'DSV',
       'Deficiencia',
       'Nombre_usuario',
       'F_registro'
    ];
}
