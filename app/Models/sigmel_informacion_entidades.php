<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_entidades extends Model
{
    public $fillable = [
        "Direccion",
        "Dirigido",
        "Nombre_entidad",
        "Emails", 
        "Id_Departamento",
        "Id_Ciudad",
        "Sucursal",
        "Nit_entidad",
        "Telefonos",
        'IdTipo_entidad',
        "Estado_entidad"
    ];

    protected $primaryKey = 'Id_Entidad';
    use HasFactory;
}
