<?php

namespace App\Http\Controllers\Ingenieria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\sigmel_roles;
use App\Models\sigmel_usuarios_roles;
class IngenieriaController extends Controller
{
    /* TODO LO REFERENTE A LOS USUARIOS DEL APLICATIVO */
    public function mostrarVistaNuevoUsuario (){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.creacionUsuario', compact('user'));
    }

    public function mostrarVistaListarUsuarios(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $listado_usuarios = DB::table('users')->select('id', 'name', 'email', 'tipo_contrato', 'empresa', 'created_at', 'updated_at')->get();
        return view('ingenieria.listarUsuarios', compact('user', 'listado_usuarios'));
    }

    public function mostrarVistaEditarUsuario (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $id_usuario = $request->id_usuario;

        $info_usuario = DB::table('users')->select('name', 'email', 'email_contacto', 'tipo_identificacion', 
        'nro_identificacion', 'tipo_contrato', 'empresa', 'cargo', 'telefono_contacto', 'extension')->where('id', $id_usuario)->get();
        return view('ingenieria.edicionUsuario', compact('user', 'info_usuario', 'id_usuario'));
    }

    public function guardar_usuario (Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
  
        // Evaluamos que el correo del usuario no exista en la base de datos
        $email_db =DB::table('users')->select('email')->where('email', $request->correo_usuario)->get();

        if (isset($email_db[0]->email) && $email_db[0]->email === $request->correo_usuario) {
            return back()->with('email','No se puede registrar: El correo ya se encuentra registrado en el aplicativo.');
        } else {
        
            // Preparamos los datos en un array para insertarlos
            $time = time();
            $date = date("Y-m-d h:i:s", $time);
    
            $nuevo_usuario = array(
                'name' => $request->nombre_usuario,
                'email' => $request->correo_usuario,
                'email_contacto' => $request->correo_contacto_usuario,
                'tipo_identificacion' => $request->tipo_identificacion_usuario,
                'nro_identificacion' => $request->nro_identificacion_usuario,
                'tipo_contrato' => $request->tipo_contrato_usuario,
                'empresa' => $request->empresa_usuario,
                'cargo' => $request->cargo_usuario,
                'telefono_contacto' => $request->telefono_contacto_usuario,
                'extension' => $request->extension_contacto_usuario,
                'password' => bcrypt($request->password_usuario),
                'created_at' => $date,
            );

            DB::table('users')->insert($nuevo_usuario);
            return back()->with('creado','Usuario creado correctamente.');
        }
    }

    public function actualizar_usuario(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        if($request->editar_password_usuario <> ""){
            $password = bcrypt($request->editar_password_usuario);
        }else{
            $password_db =DB::table('users')->where('id', $request->id_usuario)->select('password')->get();
            $password = $password_db[0]->password;
        }

        $datos_actualizar_usuario = array(
            'name' => $request->editar_nombre_usuario,
            'email' => $request->editar_correo_usuario,
            'email_contacto' => $request->editar_correo_contacto_usuario,
            'tipo_identificacion' => $request->editar_tipo_identificacion_usuario,
            'nro_identificacion' => $request->editar_nro_identificacion_usuario,
            'tipo_contrato' => $request->editar_tipo_contrato_usuario,
            'empresa' => $request->editar_empresa_usuario,
            'cargo' => $request->editar_cargo_usuario,
            'telefono_contacto' => $request->editar_telefono_contacto_usuario,
            'extension' => $request->editar_extension_contacto_usuario,
            'password' => $password,
            'updated_at' => $date,
        );
        
        // Generamos el update
        User::where('id', $request->id_usuario)->update($datos_actualizar_usuario);
        return redirect()->route('ListarUsuarios')->with('actualizado','Usuario actualizado correctamente.');
    }

    public function listadoTiposIdentificacion (){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_tipo_identificaciones')->select("id", "tipo_identificacion")->get();
        $informacion_usuario = json_decode(json_encode($datos), true);
        return response()->json($informacion_usuario);
    }

    public function listadotiposContrato (){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_tipo_contratos')->select("id", "tipo_contrato")->get();
        $informacion_usuario = json_decode(json_encode($datos), true);
        return response()->json($informacion_usuario);
    }

    public function listadoTiposIdentificacionEditar(Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_tipo_identificaciones')->select("id", "tipo_identificacion")->whereNotIn('tipo_identificacion', [$request->tipo_identificacion])->get();
        $informacion_usuario = json_decode(json_encode($datos), true);
        return response()->json($informacion_usuario);
    }

    public function listadotiposContratoEditar(Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_tipo_contratos')->select("id", "tipo_contrato")->whereNotIn('tipo_contrato', [$request->tipo_contrato])->get();
        $informacion_usuario = json_decode(json_encode($datos), true);
        return response()->json($informacion_usuario);
    }

    /* TODO LO REFERENTE A LOS ROLES QUE PUEDE LLEGAR A TENER UN USUARIO */
    public function mostrarVistaNuevoRol(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.creacionRol', compact('user'));
    }

    public function mostrarVistaListarRoles(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $listado_roles = DB::table('sigmel_roles')->select('id', 'nombre_rol', 'created_at', 'updated_at')->get();
        return view('ingenieria.listarRoles', compact('user', 'listado_roles'));
    }

    public function mostrarVistaEditarRol(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $rol_id = $request->rol_id;
        $info_rol = DB::table('sigmel_roles')->select('nombre_rol', 'descripcion_rol')->where('id', $rol_id)->get();
        return view('ingenieria.edicionRol', compact('user', 'info_rol', 'rol_id'));
    }

    public function guardar_rol (Request $request){

        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para insertarlos
        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        
        $nuevo_rol = array(
            'nombre_rol' => $request->nombre_rol,
            'descripcion_rol' => $request->descripcion_rol,
            'created_at' => $date,
        );

        DB::table('sigmel_roles')->insert($nuevo_rol);
        return back()->with('rol_creado','Rol creado correctamente.');
    }

    public function actualizar_rol(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_actualizar_rol = array(
            'nombre_rol' => $request->editar_nombre_rol,
            'descripcion_rol' => $request->editar_descripcion_rol,
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_roles::where('id', $request->rol_id)->update($datos_actualizar_rol);
        return redirect()->route('ListadoRoles')->with('rol_actualizado','Rol actualizado correctamente.');
    }

    /* TODO LO REFERENTE A ASIGNACIÓN DE ROL */
    public function mostrarVistaAsignacionRol (){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.asignacionRol', compact('user'));
    }

    public function listadoUsuarios (){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('users')->select("id", "name")->get();

        $informacion_usuario = json_decode(json_encode($datos), true);

        return response()->json($informacion_usuario);
    }

    public function listadoTodosRoles(){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_roles')->select("id", "nombre_rol")->get();

        $informacion_roles = json_decode(json_encode($datos), true);

        return response()->json($informacion_roles);
    }

    public function asignar_rol(Request $request){

        // Preparamos los datos en un array para ingresar la asignacion de rol a un usuario
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        // Consultamos el rol principal que tiene actualmente el usuario
        $verificar_rol_principal = DB::table('sigmel_usuarios_roles')->select('tipo')
                                    ->where('usuario_id', $request->listado_usuarios)
                                    ->where('tipo', 'principal')->get();
        
        // Si es por primera vez que se le va asignar un rol a un asuario
        if(count($verificar_rol_principal) == 0){
            $asignar_rol = array(
                'rol_id' => $request->listado_todos_roles,
                'usuario_id' => $request->listado_usuarios,
                'estado' => $request->estado_rol,
                'tipo' => $request->tipo_rol,
                'created_at' => $date
            );
    
            DB::table('sigmel_usuarios_roles')->insert($asignar_rol);
            return back()->with('asignacion_rol_success','Asignación de rol creada correctamente.');

        }else{

            // Si el tipo de rol que desea asignar es principal, no permitirá asignar ese rol a ese usuario
            if ($verificar_rol_principal[0]->tipo == $request->tipo_rol) {
                return back()->with('asignacion_rol_failed','El usuario ya cuenta con un rol principal.');
            }
            else{
                // Consultamos el rol otro
                $verificar_rol_otro = DB::table('sigmel_usuarios_roles as sur')
                                        ->leftJoin('sigmel_roles as sr', 'sur.rol_id', '=', 'sr.id')
                                        ->select('sur.tipo', 'sr.nombre_rol')
                                        ->where('sur.usuario_id', $request->listado_usuarios)
                                        ->where('sur.tipo', 'otro')
                                        ->where('sur.rol_id', $request->listado_todos_roles)
                                        ->get();
                
                // Si va a asignar un rol de tipo otro y este ya está asignado a este usuario, no lo dejará
                if(count($verificar_rol_otro) == 0){
                    // Nuevamente se verifca el rol principal
                    $verificar_rol_principal_again = DB::table('sigmel_usuarios_roles')->select('tipo')
                                                ->where('usuario_id', $request->listado_usuarios)
                                                ->where('rol_id', $request->listado_todos_roles)
                                                ->where('tipo', 'principal')->get();
                        
                    if (count($verificar_rol_principal_again) <> 0) {
                        return back()->with('asignacion_rol_failed','No puede asignar un rol principal como otro rol.');
                    }else{
                        $asignar_rol = array(
                            'rol_id' => $request->listado_todos_roles,
                            'usuario_id' => $request->listado_usuarios,
                            'estado' => $request->estado_rol,
                            'tipo' => $request->tipo_rol,
                            'created_at' => $date
                        );
                        DB::table('sigmel_usuarios_roles')->insert($asignar_rol);
                        return back()->with('asignacion_rol_success','Asignación de rol creada correctamente.');
                    }
            
                }else{
                    $msg= "El rol {$verificar_rol_otro[0]->nombre_rol} ya fue asignado a este usuario.";
                    return back()->with('asignacion_rol_failed', $msg);
                }
            }
        }


        
    }

    /* CONSULTAR ASIGNACIÓN DEL ROL */
    public function mostrarVistaConsultarAsignacionRol (){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.listarAsignacionRol', compact('user'));
    }

    public function consultaAsignacionRolUsuario (Request $request){
    
        /* Preparamos los datos para consultar los roles que tiene asignado un usuario */
        $roles_asociados_usuario = DB::table('users as u')
                                    ->leftJoin('sigmel_usuarios_roles as sur', 'u.id', '=', 'sur.usuario_id')
                                    ->leftJoin('sigmel_roles as sr', 'sur.rol_id','=', 'sr.id')
                                    ->select('sur.id', 'sur.usuario_id', 'sur.rol_id', 'sr.nombre_rol', 'sur.estado', 'sur.tipo', 'sur.created_at', 'sur.updated_at')
                                    ->where('sur.usuario_id', $request->usuario_id)
                                    ->get();
        
        return response()->json($roles_asociados_usuario);
    }

    public function inactivarRol (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_inactivar_rol = array(
            'estado' => 'inactivo',
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_usuarios_roles::where([
            ['id', '=', $request->id],
            ['usuario_id', '=', $request->usuario_id],
            ['rol_id', '=', $request->rol_id]
        ])
        ->update($datos_inactivar_rol);

        return redirect()->route('ConsultarAsignacionRol')->with('rol_inactivado','Rol inactivado correctamente.');
    }

    public function activarRol (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_inactivar_rol = array(
            'estado' => 'activo',
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_usuarios_roles::where([
            ['id', '=', $request->id],
            ['usuario_id', '=', $request->usuario_id],
            ['rol_id', '=', $request->rol_id]
        ])
        ->update($datos_inactivar_rol);

        return redirect()->route('ConsultarAsignacionRol')->with('rol_activado','Rol activado correctamente.');
    }

    public function cambiarARolPrincipal (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }


        // Se extrae el id del rol que el usuario tiene actualmente como principal para cambiarlo al tipo de rol: otro
        $id_tipo_rol_principal= DB::table('sigmel_usuarios_roles')
                                    ->select('id')
                                    ->where([
                                        ['usuario_id', '=', $request->usuario_id],
                                        ['tipo', '=', 'principal']
                                    ])->get();
        
        // Actualizamos el rol del id extraido
        $datos_rol_principal_out = array(
            'tipo' => 'otro'
        );
        sigmel_usuarios_roles::where('id', $id_tipo_rol_principal[0]->id)->update($datos_rol_principal_out);

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_a_rol_principal = array(
            'tipo' => 'principal',
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_usuarios_roles::where([
            ['id', '=', $request->id],
            ['usuario_id', '=', $request->usuario_id],
            ['rol_id', '=', $request->rol_id]
        ])
        ->update($datos_a_rol_principal);

        return redirect()->route('ConsultarAsignacionRol')->with('rol_principal','El cambio de tipo de rol se realizó correctamente.');
    }

    /* TODO LO REFERENTE A VISTAS */
    public function mostrarVistaNuevaVista(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.creacionVistaPrincipal', compact('user'));
    }

    public function guardar_vista (Request $request){

        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $nombre_carpeta_nueva = str_replace(' ', '_', strtolower($request->nombre_carpeta));
        $nombre_subcarpeta_nueva = str_replace(' ', '_',strtolower($request->nombre_subcarpeta));
        $nombre_archivo_nuevo = str_replace(' ', '', lcfirst(ucwords($request->nombre_archivo)));
        $observacion_vista_nueva = $request->observacion_vista;      
        
        // creación de ruta de views
        $ruta_carpeta_views = '/var/www/html/Sigmel/resources/views/';

        $contenido_nro_1 = "@extends('adminlte::page')\n@section('title', 'COLOCAR AQUÍ EL TÍTULO')\n@section('content_header') \n    <div class='row mb-2'>\n        <div class='col-sm-6'>\n        </div>\n    </div>\n@stop\n";
        $contenido_nro_2 = "\n@section('content')\n {{-- AQUI DEBE COLOCAR EL CONTENIDO DE LA VISTA --}}\n@stop\n\n@section('js')\n {{-- AQUI PARA LLAMAR EL ARCHIVO JS SI ES NECESARIO --}}\n@stop";
        $string_contenido_todos_archivos = $contenido_nro_1.$contenido_nro_2;

        // Validamos si el usuario desea crear el archivo principal dentro de la subcarpeta
        if ($nombre_subcarpeta_nueva == "") {

            // Si el directorio no existe se procede a crearlo con el nombre que dijo el usuario.
            if (!File::isDirectory($ruta_carpeta_views.$nombre_carpeta_nueva)) {
            
                File::makeDirectory($ruta_carpeta_views.$nombre_carpeta_nueva,0777, true, true);

                // Se crea el archivo con el nombre que dijo el usuario.
                $crear_archivo = $ruta_carpeta_views.$nombre_carpeta_nueva."/{$nombre_archivo_nuevo}.blade.php";

                $gestor =fopen($crear_archivo, "w+");

                // Agregamos el string para el archivo
                fwrite($gestor, $string_contenido_todos_archivos);
                // Cerramos el archivo
                fclose($gestor);

                // Se habilitan todos los permisos para la carpeta y para el archivo en caso de que necesiten editar o eliminar
                $mode = 777;
                chmod($ruta_carpeta_views.$nombre_carpeta_nueva, octdec($mode));
                chmod($crear_archivo, octdec($mode));

                // Preparamos los datos en un array para insertarlos
                $nueva_vista = array(
                    'carpeta' => $nombre_carpeta_nueva,
                    'subcarpeta' => null,
                    'archivo' => $nombre_archivo_nuevo,
                    'observacion' => $observacion_vista_nueva,
                    'created_at' => $date,
                );

                DB::table('sigmel_vistas')->insert($nueva_vista);                
                return back()->with('vista_creada','Vista y Archivo principal creados correctamente.');

            }else{
                $msg= "El directorio {$nombre_carpeta_nueva} ya está creado.";
                return back()->with('vista_no_creada', $msg);
            }
        }else{
            // SI EL DIRECTORIO NO EXISTE SE PROCEDE A CREARLO
            if (!File::isDirectory($ruta_carpeta_views.$nombre_carpeta_nueva)) {

                File::makeDirectory($ruta_carpeta_views.$nombre_carpeta_nueva.'/'.$nombre_subcarpeta_nueva, 0777, true, true);

                // Se crea el archivo con el nombre que dijo el usuario.
                $crear_archivo = $ruta_carpeta_views.$nombre_carpeta_nueva."/{$nombre_subcarpeta_nueva}/{$nombre_archivo_nuevo}.blade.php";

                $gestor =fopen($crear_archivo, "w+");

                // Agregamos el string para el archivo
                fwrite($gestor, $string_contenido_todos_archivos);
                // Cerramos el archivo
                fclose($gestor);

                // Se habilitan todos los permisos para la carpeta, subcarpeta y para el archivo en caso de que necesiten editar o eliminar
                $mode = 777;
                chmod($ruta_carpeta_views.$nombre_carpeta_nueva, octdec($mode));
                chmod($ruta_carpeta_views.$nombre_carpeta_nueva.'/'.$nombre_subcarpeta_nueva, octdec($mode));
                chmod($crear_archivo, octdec($mode));

                // Preparamos los datos en un array para insertarlos
                $nueva_vista = array(
                    'carpeta' => $nombre_carpeta_nueva,
                    'subcarpeta' => $nombre_subcarpeta_nueva,
                    'archivo' => $nombre_archivo_nuevo,
                    'observacion' => $observacion_vista_nueva,
                    'created_at' => $date,
                );

                DB::table('sigmel_vistas')->insert($nueva_vista);  
                return back()->with('vista_creada','Vista y Archivo principal creados correctamente.');

            }else{
                $msg= "El directorio {$nombre_carpeta_nueva} ya está creado.";
                return back()->with('vista_no_creada', $msg);
            }
        }

    }

    public function mostrarVistaNuevaVistaOtros(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.creacionOtrasVistas', compact('user'));
    }

    public function listadoCarpetasVistas (){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_vistas')->select("carpeta")->distinct('carpeta')->get();
        $informacion_usuario = json_decode(json_encode($datos), true);
        return response()->json($informacion_usuario);
    }
    
    public function listadoSubCarpetasCarpetasVistas (Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_vistas')->select("id", "subcarpeta")->where('carpeta', $request->nombre_carpeta)->get();
        $informacion_usuario = json_decode(json_encode($datos), true);
        return response()->json($informacion_usuario);
    }

    public function guardar_otra_vista(Request $request){
        
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $nombre_carpeta_seleccionada = $request->selector_nombre_carpeta;
        $nombre_subcarpeta_seleccionada = $request->selector_nombre_subcarpeta;
        $nombre_archivo = str_replace(' ', '', lcfirst(ucwords($request->nombre_archivo)));
        $observacion_vista = $request->observacion_vista;      
        
        // creación de ruta de views
        $ruta_carpeta_views = '/var/www/html/Sigmel/resources/views/';

        $contenido_nro_1 = "@extends('adminlte::page')\n@section('title', 'COLOCAR AQUÍ EL TÍTULO')\n@section('content_header') \n    <div class='row mb-2'>\n        <div class='col-sm-6'>\n        </div>\n    </div>\n@stop\n";
        $contenido_nro_2 = "\n@section('content')\n {{-- AQUI DEBE COLOCAR EL CONTENIDO DE LA VISTA --}}\n@stop\n\n@section('js')\n {{-- AQUI PARA LLAMAR EL ARCHIVO JS SI ES NECESARIO --}}\n@stop";
        $string_contenido_todos_archivos = $contenido_nro_1.$contenido_nro_2;

        // Validamos si el usuario desea crear el archivo dentro de la subcarpeta
        
        if ($nombre_subcarpeta_seleccionada == "") {
            
            if (File::exists($ruta_carpeta_views.$nombre_carpeta_seleccionada)) {

                // Se crea el archivo con el nombre que dijo el usuario.
                $crear_archivo = $ruta_carpeta_views.$nombre_carpeta_seleccionada."/{$nombre_archivo}.blade.php";
    
                $gestor =fopen($crear_archivo, "w+");
                // Agregamos el string para el archivo
                fwrite($gestor, $string_contenido_todos_archivos);
                // Cerramos el archivo
                fclose($gestor);
                
                // Se habilitan todos los permisos para el archivo en caso de que necesiten editar o eliminar
                $mode = 707;
                chmod($crear_archivo, octdec($mode));

                // / Preparamos los datos en un array para insertarlos
                $otra_vista = array(
                    'carpeta' => $nombre_carpeta_seleccionada,
                    'subcarpeta' => null,
                    'archivo' => $nombre_archivo,
                    'observacion' => $observacion_vista,
                    'created_at' => $date,
                );

                DB::table('sigmel_vistas')->insert($otra_vista);
                return back()->with('otra_vista_creada','Archivo creado correctamente.');
            }else{
                return back()->with('otra_vista_no_creada','El directorio no existe.');
            }

        } else {
            if (File::exists($ruta_carpeta_views.$nombre_carpeta_seleccionada)) {
                // Se crea el archivo con el nombre que dijo el usuario.
                $crear_archivo = $ruta_carpeta_views.$nombre_carpeta_seleccionada."/{$nombre_subcarpeta_seleccionada}/{$nombre_archivo}.blade.php";
    
                $gestor =fopen($crear_archivo, "w+");
                // Agregamos el string para el archivo
                fwrite($gestor, $string_contenido_todos_archivos);
                // Cerramos el archivo
                fclose($gestor);
                
                // Se habilitan todos los permisos para el archivo en caso de que necesiten editar o eliminar
                $mode = 707;
                chmod($crear_archivo, octdec($mode));

                // / Preparamos los datos en un array para insertarlos
                $otra_vista = array(
                    'carpeta' => $nombre_carpeta_seleccionada,
                    'subcarpeta' => $nombre_subcarpeta_seleccionada,
                    'archivo' => $nombre_archivo,
                    'observacion' => $observacion_vista,
                    'created_at' => $date,
                );

                DB::table('sigmel_vistas')->insert($otra_vista);
                return back()->with('otra_vista_creada','Archivo creado correctamente.');
            }else {
                return back()->with('otra_vista_no_creada','El directorio no existe.');
            }
        }
        

    }

    /* TODO LO REFERENTE A ASIGNACIÓN DE VISTAS */
    public function mostrarVistaAsignacionVista (){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.asignacionVista', compact('user'));
    }

    public function listadoCarpetasSubCarpetasVistas (){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_vistas')->select("id", "carpeta", "subcarpeta", "archivo")->get();
        $informacion_usuario = json_decode(json_encode($datos), true);
        return response()->json($informacion_usuario);
    }

    public function asignar_vista(Request $request){

        // Preparamos los datos en un array para ingresar la asignacion de una vista a un rol
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        // Consultamos la vista principal que tiene el rol seleccionado
        $verificar_vista_principal = DB::table('sigmel_usuarios_vistas')->select('tipo')
                                    ->where('rol_id', $request->listado_roles_para_vistas)
                                    ->where('tipo', 'principal')->get();
    
        /* echo "id rol: ".$request->listado_roles_para_vistas."<br>";
        echo "id vista: ".$request->listado_vistas_asignar."<br>";
        echo "tipo: ".$request->tipo_vista."<br><br>";
        echo $verificar_vista_principal."<br><br>"; */

        // Si es por primera vez que se le va asignar una vista principal a un rol
        if (empty($verificar_vista_principal[0])) {
            // echo "no existo"."<br>";
            $asignar_vista = array(
                'rol_id' => $request->listado_roles_para_vistas,
                'vista_id' => $request->listado_vistas_asignar,
                'estado' => $request->estado_vista,
                'tipo' => $request->tipo_vista,
                'created_at' => $date
            );

            DB::table('sigmel_usuarios_vistas')->insert($asignar_vista);
            return back()->with('asignacion_vista_success','Asignación de vista creada correctamente.');

        }else {
            // echo "existo"."<br>";
            // Si el tipo de vista que desea asignar es principal, no permitirá asignar esa vista a ese rol
            if ($verificar_vista_principal[0]->tipo == $request->tipo_vista) {
                return back()->with('asignacion_vista_failed', 'El rol seleccionado ya cuenta con una vista principal.');
            }

            // consultamos si la vista es de tipo otro 
            $verificar_vista_otro = DB::table('sigmel_usuarios_vistas')
                                    ->select('tipo')
                                    ->where('rol_id', $request->listado_roles_para_vistas)
                                    ->where('vista_id', $request->listado_vistas_asignar)
                                    ->where('tipo', 'otro')
                                    ->get();

            // echo $verificar_vista_otro."<br>";
            // Si la vista de tipo otro será asignada pero ya existe, el sistema no dejará asignarla nuevamente.
            if (!empty($verificar_vista_otro[0])) {
                return back()->with('asignacion_vista_failed', 'La vista que intenta asignar ya está asignada al rol seleccionado.');
            }else {
                // Se verifica nuevamente la vista principal
                $verificar_vista_principal_again = DB::table('sigmel_usuarios_vistas')->select('tipo')
                                                ->where('rol_id', $request->listado_roles_para_vistas)
                                                ->where('vista_id', $request->listado_vistas_asignar)
                                                ->where('tipo', 'principal')->get();
                
                // echo $verificar_vista_principal_again;
                // Si la vista que desea asignar como tipo otro ya está creada como tipo principal, no lo dejerá
                if (!empty($verificar_vista_principal_again[0])) {
                    return back()->with('asignacion_vista_failed', 'No puede asignar una vista principal como otra vista');
                }else {
                    $asignar_vista = array(
                        'rol_id' => $request->listado_roles_para_vistas,
                        'vista_id' => $request->listado_vistas_asignar,
                        'estado' => $request->estado_vista,
                        'tipo' => $request->tipo_vista,
                        'created_at' => $date
                    );

                    DB::table('sigmel_usuarios_vistas')->insert($asignar_vista);
                    return back()->with('asignacion_vista_success','Asignación de vista creada correctamente.');
                }
            }

        }


        
    }

    /* CONSULTAR ASIGNACIÓN DE VISTAS A ROLES */
    public function mostrarVistaConsultarAsignacionVista (){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.listarAsignacionVista', compact('user'));
    }

    public function ConsultaAsignacionVistaRol (Request $request){

        
        /* Preparamos los datos para consultar los roles que tiene asignado un usuario */
        $roles_asociados_usuario = DB::table('sigmel_vistas as sv')
                                    ->leftJoin('sigmel_usuarios_vistas as suv', 'sv.id', '=', 'suv.vista_id')
                                    ->select('sv.id as id_vista', 'sv.carpeta', 'sv.subcarpeta', 'sv.archivo', 'suv.id as id_asignacion', 'suv.rol_id', 'suv.vista_id', 'suv.estado', 'suv.tipo', 'suv.created_at', 'suv.updated_at')
                                    ->where('suv.rol_id', $request->rol_id)
                                    ->get();
        
        return response()->json($roles_asociados_usuario);
    }

    public function mostrarVistaEditarVista(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $id_vista = $request->id_vista;
        $info_vista = DB::table('sigmel_vistas')->select('carpeta', 'subcarpeta', 'archivo', 'observacion')->where('id', $id_vista)->get();
        return view('ingenieria.edicionVista', compact('user', 'info_vista', 'id_vista'));
    }

}
