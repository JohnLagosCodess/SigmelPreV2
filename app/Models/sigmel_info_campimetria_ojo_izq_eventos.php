<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_info_campimetria_ojo_izq_eventos extends Model
{
    // use HasFactory;

    public $timestamps = false;
    
    protected $primaryKey = 'Id_info';

    protected $fillable = [
        'Id_agudeza',
        'InfoFila1',
        'InfoFila2',
        'InfoFila3',
        'InfoFila4',
        'InfoFila5',
        'InfoFila6',
        'InfoFila7',
        'InfoFila8',
        'InfoFila9',
        'InfoFila10',
        'Nombre_usuario',
        'F_registro'
    ];
}
