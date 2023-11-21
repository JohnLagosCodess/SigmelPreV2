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
        'Descripcion',
        'Valor',
        'Unidad',
        'Nombre_usuario',
        'F_registro'
    ];
}
