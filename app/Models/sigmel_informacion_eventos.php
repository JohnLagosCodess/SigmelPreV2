<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_eventos extends Model
{
    //use HasFactory;
    public $timestamps = false;
    
    protected $primaryKey = 'Id_Eventos';

    protected $fillable = [
    'Cliente',
    'Tipo_cliente',
    'Tipo_evento',
    'ID_evento',
    'F_evento',
    'F_radicacion',
    'Nombre_usuario',
    'F_registro'];


}