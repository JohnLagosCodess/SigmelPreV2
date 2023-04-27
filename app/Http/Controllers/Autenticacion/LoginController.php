<?php

namespace App\Http\Controllers\Autenticacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function show(){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        return view('autenticacion.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
 
        // SI LA VALIDACIÓN SE CUMPLE SE PROCEDE A INICIAR SESIÓN
        if (Auth::attempt($credentials)) {
            Auth::logoutOtherDevices($request->password);
            return redirect()->route('RolPrincipal');
        }
  
        // DE LO CONTRARIO NO SE MANDA AL LOGIN
        /* EXTRACCIÓN DEL CORREO Y PASSWORD DEL USUARIO DE LA BD */
        $email_db = DB::table('users')
                    ->select('email')
                    ->where('email', $request->email)->get();
        
        $password_db = DB::table('users')
                        ->select('password')
                        ->where('email', $request->email)->get();
        
        /* SI EL CORREO NO EXISTE NO DEJARÁ ENTRAR */
        if (count($email_db) == 0) {
            return back()->withErrors([
                'email' => 'el correo ingresado no está registrado.',
            ]);
        }

        /* SI LA CONTRASEÑA NO EXISTE NO DEJARÁ ENTRAR */
        if (! Hash::check($request->password, $password_db[0]->password)) {
            return back()->withErrors([
                'password' => ['Contraseña incorrecta.']
            ]);
        }
        
    }

  
}
