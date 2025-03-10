<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sigmel_consecutivos_destinatarios extends Model
{
    use HasFactory;
    public $timestamps = true;
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'Consecutivo_Destinatario',
        'Estado',
        'F_creacion',
        'F_actualizacion' 
    ];
    const CREATED_AT = 'F_creacion';
    const UPDATED_AT = 'F_actualizacion';
}
