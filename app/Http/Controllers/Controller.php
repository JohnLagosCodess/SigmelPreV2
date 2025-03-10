<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\sigmel_mantenimiento;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $activar_mantenimiento = sigmel_mantenimiento::where('Estado','!=','inactivo')->first();

        if (!is_null($activar_mantenimiento)) {
            $fecha_actual = date("Y-m-d H:i:s");

            if ($activar_mantenimiento->Estado == "activo" && $fecha_actual < $activar_mantenimiento->Fecha_expiracion) {
                Session::put('activar_mantenimiento', 'activar');
                $activar_mantenimiento->Estado = 'ejecutando';
                $activar_mantenimiento->save();
            } elseif ($activar_mantenimiento->Estado == "ejecutando" && $fecha_actual < $activar_mantenimiento->Fecha_expiracion) {
                Session::forget('activar_mantenimiento');
                Session::put('activar_mantenimiento', 'procesando');
            } else {
                $activar_mantenimiento->Estado = "inactivo";
                $activar_mantenimiento->save();
                Session::forget('activar_mantenimiento');
            }
        }
        
    }
}
