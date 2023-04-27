<?php

namespace App\Http\Controllers\Autenticacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\sigmel_control_sesiones;

class LogoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $id_usuario = Auth::id();    
        $fecha_cerro_sesion = date("Y-m-d", $time);
        $hora_cerro_sesion = date("h:i:s", $time);

        $data = array(
            'bandera' => 0,
            'fecha_cerro_sesion' => $fecha_cerro_sesion,
            'hora_cerro_sesion' => $hora_cerro_sesion
        );
        
        // Generamos el update
        sigmel_control_sesiones::where('usuario_id', $id_usuario)
                                ->where('bandera', 1)
                                ->update($data);

        Session::flush();
        Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return redirect('/');
    }
}
