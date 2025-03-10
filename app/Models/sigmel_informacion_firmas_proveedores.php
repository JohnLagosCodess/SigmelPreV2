<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_firmas_proveedores extends Model
{
    // use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_firma';

    protected $fillable = [
        'Id_cliente',
        'Nombre_firmante',
        'Cargo_firmante',
        'Firma',
        'Url',
        'Estado',
        'Nombre_usuario',
        'F_registro',
    ];
}
