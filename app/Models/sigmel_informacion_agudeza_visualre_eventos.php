<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_informacion_agudeza_visualre_eventos extends Model
{
    // use HasFactory;
    public $timestamps = false;
    
    protected $primaryKey = 'Id_agudeza_re';

    protected $fillable = [
       'ID_evento_re',
       'Id_Asignacion_re',
       'Id_proceso_re',
       'Ceguera_Total_re',
       'Agudeza_Ojo_Izq_re',
       'Agudeza_Ojo_Der_re',
       'Agudeza_Ambos_Ojos_re',
       'PAVF_re',
       'DAV_re',
       'Campo_Visual_Ojo_Izq_re',
       'Campo_Visual_Ojo_Der_re',
       'Campo_Visual_Ambos_Ojos_re',
       'CVF_re',
       'DCV_re',
       'DSV_re',
       'Dx_Principal_re',
       'Deficiencia_re',
       'Estado',
       'Estado_Recalificacion',
       'Nombre_usuario',
       'F_registro'
    ];
}
