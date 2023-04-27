<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


/* use App\Models\User;
use App\Models\sigmel_roles;
use App\Models\sigmel_usuarios_roles; */

use App\Models\sigmel_control_sesiones;

class RolesController extends Controller
{
    
    /* FUNCIÓN PARA RENDERIZAR LA VISTA Y EL ROL PRINCIPAL DEL USUARIO CUANDO INICIA SESIÓN */
    public function rolPrincipal (Request $request){
        
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }
        // Auth::logoutOtherDevices(session()->get('password_hash_web'));

        // EXTRACCIÓN DE ALGUNOS DATOS PARA USAR PARA CARGAR EL ROL Y PARA LLENAR EL REGISTRO DE AUDITORIA DE INICIO DE SESIÓN
        $time = time();
        $id_usuario = Auth::id();
        $nombre_usuario = Auth::user()->name;
        $email_usuario = Auth::user()->email;
        $ip_cliente = $_SERVER ['REMOTE_ADDR'];
        $fecha_inicio_sesion = date("Y-m-d", $time);
        $hora_inicio_sesion = date("h:i:s", $time);
        
        /* REGISTRO DE INICIO SESIÓN */
        if(Auth::check()){
            
            $registro_inicio_sesion = new sigmel_control_sesiones;
            $registro_inicio_sesion->usuario_id = $id_usuario;
            $registro_inicio_sesion->bandera = 1;
            $registro_inicio_sesion->nombre = $nombre_usuario;
            $registro_inicio_sesion->email =  $email_usuario;
            $registro_inicio_sesion->ip_address = $ip_cliente;
            $registro_inicio_sesion->fecha_inicio_sesion = $fecha_inicio_sesion;
            $registro_inicio_sesion->hora_inicio_sesion = $hora_inicio_sesion;
            $registro_inicio_sesion->created_at = now();
            $registro_inicio_sesion->updated_at = now();
            $registro_inicio_sesion->save();
        }

        // Si el rol principal se ha inactivado entonces no le permite iniciar sesión.
        $estado_actual_rol_principal = DB::table("sigmel_usuarios_roles as sur")
                                        ->leftJoin("users as u", 'sur.usuario_id', '=', 'u.id')
                                        ->select('sur.estado')
                                        ->where([
                                            ['u.id', '=', $id_usuario],
                                            ['sur.tipo', '=', 'principal']
                                        ])
                                        ->get();

        if($estado_actual_rol_principal[0]->estado === "inactivo"){
            Session::flush();
            Auth::logout();
            return back()->withErrors([
                'email' => ['Su usuario está inactivo, por favor contacte a soporte TIC para activarlo.']
            ]);
        }else {
            // Redireccionar al usuario acorde a su rol principal teniendo encuenta que su estado sea activo
            $datos = DB::table('sigmel_roles as sr')
                        ->leftJoin("sigmel_usuarios_roles as sur", 'sr.id', '=', 'sur.rol_id') 
                        ->leftJoin("sigmel_usuarios_vistas as suv", 'suv.rol_id', '=', 'sr.id') 
                        ->leftJoin("sigmel_vistas as sv", 'sv.id', '=', 'suv.vista_id') 
                        ->leftJoin("users as u", 'u.id', '=', 'sur.usuario_id' ) 
                        ->select("sr.nombre_rol", "sv.carpeta", "sv.subcarpeta", "sv.archivo")
                        ->where([
                            ['u.id', '=', $id_usuario],
                            ['u.email', '=', $email_usuario],
                            ['sur.tipo', '=', 'principal'],
                            ['suv.tipo', '=', 'principal'],
                            ['sur.estado', '=', 'activo']
                        ])
                        ->get();
    
            $informacion_usuario = json_decode(json_encode($datos), true);
            if (count($informacion_usuario) > 0) {
                $rol_usuario = $informacion_usuario[0]['nombre_rol'];
                $carpeta_vista = $informacion_usuario[0]['carpeta'];
                $subcarpeta_vista = $informacion_usuario[0]['subcarpeta'];
                $archivo_vista = $informacion_usuario[0]['archivo'];

                // validamos si la vista principal se creó dentro de una subcarpeta
                if($subcarpeta_vista <> ""){
                    $ruta_renderizar = "{$carpeta_vista}.{$subcarpeta_vista}.{$archivo_vista}";
                }else{
                    $ruta_renderizar = "{$carpeta_vista}.{$archivo_vista}";
                }

                $user = Auth::user();
                $user->rol_usuario = $rol_usuario;
                return view ($ruta_renderizar, compact('user'));
    
            }else{
                return redirect()->route('login');
            }
        }

        
    }

    /* FUNCIÓN PARA LISTAR LOS ROLES EN EL SELECTOR DE ROLES */
    public function listadoRoles (Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }
        
        $id_usuario = $request->input('id_usuario');
        $correo_usuario = $request->input('correo_usuario');

        $datos = DB::table('sigmel_roles as sr')
                    ->leftJoin("sigmel_usuarios_roles as sur", 'sr.id', '=', 'sur.rol_id')
                    ->leftJoin("users as u", 'u.id', '=', 'sur.usuario_id' ) 
                    ->select("sur.rol_id", "sr.nombre_rol")
                    ->where([
                        ['u.id', '=', $id_usuario],
                        ['u.email', '=', $correo_usuario],
                        ['sur.estado', '=', 'activo']
                    ])
                    ->get();

        $informacion_usuario = json_decode(json_encode($datos), true);

        return response()->json($informacion_usuario);
    }

    /* FUNCIÓN PARA REALIZAR EL CAMBIO DE ROL DEPENDIENDO DEL SELECTOR DE ROLES*/
    /* DEPENDIENDO DEL CAMBIO SE RENDERIZA LA VISTA PRINCIPAL DE ESE ROL */
    public function cambioDeRol (Request $request){

        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $id_usuario = $request->input('id_usuario');
        $correo_usuario = $request->input('correo_usuario');
        $rol_id = $request->input('listado_roles_usuario');

        $datos = DB::table('sigmel_vistas as sv')
                    ->leftJoin('sigmel_usuarios_vistas as suv', 'sv.id', '=', 'suv.vista_id')
                    ->leftJoin('sigmel_usuarios_roles as sur', 'suv.rol_id', '=', 'sur.rol_id')
                    ->leftJoin('sigmel_roles as sr', 'sur.rol_id', '=', 'sr.id')
                    ->leftJoin('users as u', 'u.id', '=', 'sur.usuario_id')
                    ->select("sr.nombre_rol", "sv.carpeta", "sv.subcarpeta", "sv.archivo")
                    ->where([
                        ['suv.rol_id', '=', $rol_id],
                        ['sur.usuario_id', '=', $id_usuario],
                        ['u.email', '=', $correo_usuario],
                        ['suv.estado', '=', 'activo'],
                        ['suv.tipo', '=', 'principal']
                    ])
                    ->get();
        
        $informacion_usuario = json_decode(json_encode($datos), true);

        if (count($informacion_usuario) > 0) {
            $rol_usuario = $informacion_usuario[0]['nombre_rol'];
            $carpeta_vista = $informacion_usuario[0]['carpeta'];
            $subcarpeta_vista = $informacion_usuario[0]['subcarpeta'];
            $archivo_vista = $informacion_usuario[0]['archivo'];
                        
            // validamos si la vista principal se creó dentro de una subcarpeta
            if($subcarpeta_vista <> ""){
                $ruta_renderizar = "{$carpeta_vista}.{$subcarpeta_vista}.{$archivo_vista}";
            }else{
                $ruta_renderizar = "{$carpeta_vista}.{$archivo_vista}";
            }
            
            $user = Auth::user();
            $user->rol_usuario = $rol_usuario;
            return view ($ruta_renderizar, compact('user'));
          
        }else{
            return redirect()->route('login');
        }

    }
}
