<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_expedientes_eventos extends Model
{
    //use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_expedientes';

    protected $fillable = [

        'Id_Documento',
        'ID_evento',
        'Nombre_documento',
        'Formato_documento',
        'Id_servicio',
        'Estado',
        'Posicion',
        'Folear',
        'Nombre_usuario',
        'F_registro',
    ];
}
