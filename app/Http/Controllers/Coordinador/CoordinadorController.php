<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordinadorController extends Controller
{
    public function show(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('Coordinador.index', compact('user'));
    }

    // Bandeja PCL Coordinador
    public function mostrarVistaBandejaPCL(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        

        return view('coordinador.bandejaPCL', compact('user'));
    }
}
