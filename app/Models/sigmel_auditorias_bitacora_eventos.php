<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_auditorias_bitacora_eventos extends Model
{
    use HasFactory;

    protected $primaryKey = "id_bitacora";

    protected $fillable = ['Id_accion','Id_Asignacion','ID_evento','Id_proceso','Id_servicio','Descripcion','F_accion','Nombre_usuario'];
}
