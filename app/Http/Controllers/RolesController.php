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
                                        ->select('sur.rol_id as id_rol_principal', 'sur.estado')
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
            $this->crear_menu();
            // Redireccionar al usuario acorde a su rol principal teniendo encuenta que su estado sea activo
            $datos = DB::table('sigmel_roles as sr')
                        ->leftJoin("sigmel_usuarios_roles as sur", 'sr.id', '=', 'sur.rol_id') 
                        ->leftJoin("sigmel_usuarios_vistas as suv", 'suv.rol_id', '=', 'sr.id') 
                        ->leftJoin("sigmel_vistas as sv", 'sv.id', '=', 'suv.vista_id') 
                        ->leftJoin("users as u", 'u.id', '=', 'sur.usuario_id' ) 
                        ->select("sr.nombre_rol", "sv.carpeta", "sv.subcarpeta", "sv.archivo", 'suv.estado as estado_vista')
                        ->where([
                            ['u.id', '=', $id_usuario],
                            ['u.email', '=', $email_usuario],
                            ['sur.tipo', '=', 'principal'],
                            ['sur.estado', '=', 'activo'],
                            ['suv.tipo', '=', 'principal'],
                        ])
                        ->get();
    
            $informacion_usuario = json_decode(json_encode($datos), true);
            if (count($informacion_usuario) > 0) {
                $rol_usuario = $informacion_usuario[0]['nombre_rol'];
                $carpeta_vista = $informacion_usuario[0]['carpeta'];
                $subcarpeta_vista = $informacion_usuario[0]['subcarpeta'];
                $archivo_vista = $informacion_usuario[0]['archivo'];
                $estado_vista = $informacion_usuario[0]['estado_vista'];

                // validamos si la vista principal se creó dentro de una subcarpeta
                if($subcarpeta_vista <> ""){
                    $ruta_renderizar = "{$carpeta_vista}.{$subcarpeta_vista}.{$archivo_vista}";
                }else{
                    $ruta_renderizar = "{$carpeta_vista}.{$archivo_vista}";
                }

                $user = Auth::user();
                $user->rol_usuario = $rol_usuario;
                $user->estado_vista = $estado_vista;
                return view ($ruta_renderizar, compact('user'));
    
            }else{
                return redirect()->route('login');
            }
        }

        
    }

    /* FUNCIÓN PARA LISTAR LOS ROLES EN EL SELECTOR DE ROLES DEL NAVBAR */
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

        session(['id_cambio_rol' => $rol_id]);
        $this->crear_menu();

        $datos = DB::table('sigmel_vistas as sv')
                    ->leftJoin('sigmel_usuarios_vistas as suv', 'sv.id', '=', 'suv.vista_id')
                    ->leftJoin('sigmel_usuarios_roles as sur', 'suv.rol_id', '=', 'sur.rol_id')
                    ->leftJoin('sigmel_roles as sr', 'sur.rol_id', '=', 'sr.id')
                    ->leftJoin('users as u', 'u.id', '=', 'sur.usuario_id')
                    ->select("sr.nombre_rol", "sv.carpeta", "sv.subcarpeta", "sv.archivo", 'suv.estado as estado_vista')
                    ->where([
                        ['suv.rol_id', '=', $rol_id],
                        ['sur.usuario_id', '=', $id_usuario],
                        ['u.email', '=', $correo_usuario],
                        // ['suv.estado', '=', 'activo'],
                        ['suv.tipo', '=', 'principal']
                    ])
                    ->get();
        
        $informacion_usuario = json_decode(json_encode($datos), true);

        if (count($informacion_usuario) > 0) {
            $rol_usuario = $informacion_usuario[0]['nombre_rol'];
            $carpeta_vista = $informacion_usuario[0]['carpeta'];
            $subcarpeta_vista = $informacion_usuario[0]['subcarpeta'];
            $archivo_vista = $informacion_usuario[0]['archivo'];
            $estado_vista = $informacion_usuario[0]['estado_vista'];
                        
            // validamos si la vista principal se creó dentro de una subcarpeta
            if($subcarpeta_vista <> ""){
                $ruta_renderizar = "{$carpeta_vista}.{$subcarpeta_vista}.{$archivo_vista}";
            }else{
                $ruta_renderizar = "{$carpeta_vista}.{$archivo_vista}";
            }
            
            $user = Auth::user();
            $user->rol_usuario = $rol_usuario;
            $user->estado_vista = $estado_vista;
            return view ($ruta_renderizar, compact('user'));
          
        }else{
            return redirect()->route('login');
        }

    }

    public function crear_menu(){

        $valor_almacenado = session('id_cambio_rol');
        
        $id_usuario = Auth::id();

        if ($valor_almacenado <> "") {
            $id_rol =  $valor_almacenado;
        }else {
            $consulta = DB::table("sigmel_usuarios_roles as sur")
                ->leftJoin("users as u", 'sur.usuario_id', '=', 'u.id')
                ->select('sur.rol_id as id_rol_principal')
                ->where([
                    ['u.id', '=', $id_usuario],
                    ['sur.tipo', '=', 'principal']
                ])
                ->get();
            $id_rol = $consulta[0]->id_rol_principal;
        }
        
        $datos_menu = DB::table('sigmel_menuses as sm')
                        ->leftJoin('sigmel_usuarios_vistas as suv', 'sm.vista_id', '=', 'suv.vista_id')
                        ->leftJoin('sigmel_usuarios_vistas as suv1', 'sm.rol_id', '=', 'suv1.rol_id')
                        ->leftJoin('sigmel_vistas as sv', 'suv.vista_id', '=', 'sv.id')
                        ->select('sm.id', 'sm.nombre', 'sm.id_padre', 'sm.tipo', 'sv.nombre_renderizar', 'sm.icono', 'sm.estado')
                        ->where([
                            ['sm.rol_id', $id_rol],
                            ['sm.tipo', 'primario']
                        ])->groupBy('sm.nombre')
                        ->get();


        $informacion_menu = json_decode(json_encode($datos_menu), true);
        // echo "<pre>";
        // print_r($informacion_menu);
        // echo "</pre>";

        $menu_final = array();
        for ($i=0; $i < count($informacion_menu) ; $i++) { 
            // Creamos la opción de menu que es una función y no tiene hijos
            if($informacion_menu[$i]['id_padre'] == '' && $informacion_menu[$i]['nombre_renderizar'] <> '' && $informacion_menu[$i]['estado'] == 'activo'){
                array_push($menu_final, 
                    [
                        'text'=> $informacion_menu[$i]['nombre'], 
                        'icon' => $informacion_menu[$i]['icono'], 
                        'url' => route($informacion_menu[$i]['nombre_renderizar'])
                    ]
                );
            }
            // Creamos la opción de menu que sera un padre y tendra hijos
            if ($informacion_menu[$i]['id_padre'] == '' && $informacion_menu[$i]['nombre_renderizar'] == '' && $informacion_menu[$i]['estado'] == 'activo') {
                $datos_submenu = DB::table('sigmel_menuses as sm')
                                ->leftJoin('sigmel_usuarios_vistas as suv', 'sm.vista_id', '=', 'suv.vista_id')
                                ->leftJoin('sigmel_usuarios_vistas as suv1', 'sm.rol_id', '=', 'suv1.rol_id')
                                ->leftJoin('sigmel_vistas as sv', 'suv.vista_id', '=', 'sv.id')
                                ->select('sm.nombre', 'sm.id_padre', 'sm.tipo', 'sv.nombre_renderizar', 'sm.icono')
                                ->where([
                                    ['sm.id_padre', $informacion_menu[$i]['id']],
                                    ['sm.tipo', 'secundario'],
                                    ['sm.estado', 'activo']
                                ])->groupBy('sm.nombre')
                                ->get();

                $informacion_submenu = json_decode(json_encode($datos_submenu), true);
                
                $array_submenu = array([
                    'text'=> $informacion_menu[$i]['nombre'], 
                    'icon' => $informacion_menu[$i]['icono'], 
                    'submenu' => []
                ]);

                for ($a=0; $a < count($informacion_submenu); $a++) { 
                    array_push($array_submenu[0]['submenu'], [
                        
                        'text' => $informacion_submenu[$a]['nombre'], 
                        'icon' => $informacion_submenu[$a]['icono'], 
                        'url' => route($informacion_submenu[$a]['nombre_renderizar'])
                         
                    ]);
                }
                // array_push($menu_final, $array_submenu);
                $menu_final[] = $array_submenu[0];
            }

        }

        //         echo "<pre>";
        // print_r($menu_final);
        // echo "</pre>";
        
        return $menu_final;

    }

}
