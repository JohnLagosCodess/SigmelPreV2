<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_sucursales_clientes extends Model
{
    // use HasFactory;

    public $timestamps =false;

    protected $primaryKey= 'Id_sucursal';

    protected $fillable = [
        'Id_cliente',
        'Nombre',
        'Gerente',
        'Telefono_principal',
        'Otros_telefonos',
        'Email_principal',
        'Otros_emails',
        'Linea_atencion_principal',
        'Otras_lineas_atencion',
        'Direccion',
        'Id_Departamento',
        'Id_Ciudad',
        'Estado',
        'Nombre_usuario',
        'F_registro'
    ];
}
