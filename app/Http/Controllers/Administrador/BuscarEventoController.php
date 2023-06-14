<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuscarEventoController extends Controller
{
    /* TODO LO REFERENTE AL FORMULARIO DE BUSCAR UN EVENTO*/
    public function mostrarVistaBuscarEvento(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        /*$listado_documentos = sigmel_lista_documentos::on('sigmel_gestiones')
        ->select('Id_Documento', 'Nro_documento', 'Nombre_documento', 'Requerido')
        ->where([['Estado', "=", "activo"]])->get();*/
        
        //return view('administrador.busquedaEvento', compact('user', 'listado_documentos'));
        return view('administrador.busquedaEvento', compact('user'));
    }
}
