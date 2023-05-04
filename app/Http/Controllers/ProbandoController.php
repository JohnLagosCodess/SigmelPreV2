<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\sigmel_probando;
class ProbandoController extends Controller
{
    public function index(){

        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        /* $nuevos = new sigmel_probando;
        $nuevos->nombre = 'PRUEBA NOMBRE PAULA C';
        $nuevos->save(); */

        // $id_ult = sigmel_probando::on('mysql2')->select('id')->latest('id')->first();
        // echo "<pre>";
        // print_r($id_ult['id']);
        // echo "</pre>";

        // $datos_pruebas = sigmel_probando::on('mysql2')->get();
        // $datos_pruebas = sigmel_probando::on('mysql2')->where('id', '2')->get();
        // $user= Auth::user();
        // return view ('otra_conexion', compact('datos_pruebas', 'user'));
    }
}
