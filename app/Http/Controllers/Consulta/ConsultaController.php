<?php

namespace App\Http\Controllers\Consulta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{
    public function show(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('consulta.index', compact('user'));
    }
}