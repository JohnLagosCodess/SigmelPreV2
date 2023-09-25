<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DeterminacionOrigenATEL extends Controller
{
    public function mostrarVistaDtoATEL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        return view('coordinador.determinacionOrigenATEL');
    }
}
