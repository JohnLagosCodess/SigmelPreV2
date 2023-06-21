<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuscarEventoController extends Controller
{
    /* TODO LO REFERENTE AL FORMULARIO DE BUSCAR UN EVENTO*/
    // Busqueda Evaluado y evento
    public function mostrarVistaBuscarEvento(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.busquedaEvento', compact('user'));
    }
    // Resultado de busqueda
    public function mostrarResultadoBusqueda(Request $request){
    
        if(!Auth::check()){
            return redirect('/');
        }
        $cedu=$request->parametro;
        return view('administrador.busquedaEvento', compact('user', 'cedu'));
    }
}
