<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_ans_clientes extends Model
{
    // use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_ans';

    protected $fillable = [
        'Id_cliente',
        'Nombre',
        'Servicio',
        'Accion',
        'Valor',
        'Unidad',
        'Porcentaje_Alerta_Naranja',
        'Porcentaje_Alerta_Roja',
        'Estado',
        'Nombre_usuario',
        'F_registro'
    ];
}
