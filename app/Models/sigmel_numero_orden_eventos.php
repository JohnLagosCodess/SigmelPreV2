<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_numero_orden_eventos extends Model
{
    // use HasFactory;
    public $timestamps = false;
    
    protected $primaryKey = 'Id_Orden';

    protected $fillable = [
    'Numero_orden',
    'Proceso',
    'Estado',
    'F_orden',    
    ];
}
