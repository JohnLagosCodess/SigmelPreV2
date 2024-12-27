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
use App\Models\sigmel_usuarios_vistas;
use App\Models\sigmel_menus;
class IngenieriaController extends Controller
{
    public function show(){
        if(!Auth::check()){
            return redirect('/');
        }
        $id_usuario = Auth::id();
        $email_usuario = Auth::user()->email;

        $datos = DB::table('sigmel_roles as sr')
                        ->leftJoin("sigmel_usuarios_roles as sur", 'sr.id', '=', 'sur.rol_id') 
                        ->leftJoin("users as u", 'u.id', '=', 'sur.usuario_id' ) 
                        ->select("sr.nombre_rol")
                        ->where([
                            ['u.id', '=', $id_usuario],
                            ['u.email', '=', $email_usuario],
                        ])
                        ->get();

        $informacion_usuario = json_decode(json_encode($datos), true);
        if (count($informacion_usuario) > 0) {
            $rol_usuario = $informacion_usuario[0]['nombre_rol'];
            
            $user = Auth::user();
            $user->rol_usuario = $rol_usuario;
            return view ('ingenieria.index', compact('user'));

        }else{
            return redirect()->route('login');
        }

    }
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
        // $listado_usuarios = DB::table('users')->select('id', 'name', 'email', 'tipo_contrato', 'empresa', 'created_at', 'updated_at')->get();
        $listado_usuarios = DB::table('users as u')
        ->leftJoin('sigmel_usuarios_roles as sur', 'u.id', '=', 'sur.usuario_id')
        ->leftJoin('sigmel_roles as sr', 'sur.rol_id', '=', 'sr.id')
        ->select('u.id', 'u.name', 'u.tipo_colaborador', 'u.estado',
        DB::raw("GROUP_CONCAT(sr.nombre_rol SEPARATOR ', ') as roles_usuario"),
        DB::raw('(SELECT GROUP_CONCAT(DISTINCT Nombre_proceso SEPARATOR ", ") FROM sigmel_gestiones.sigmel_lista_procesos_servicios where FIND_IN_SET(Id_proceso, u.id_procesos_usuario)) as procesos_usuario'),
        'u.email_contacto',
	    'u.telefono_contacto',
	    'u.created_at',
	    'u.updated_at')->groupBy('u.id')->get();

        $conteo_activos_inactivos = DB::table('users')
        ->select(DB::raw("COUNT(IF(estado = 'Activo', 1, NULL)) AS 'Activos'"), DB::raw("COUNT(IF(estado = 'Inactivo', 1, NULL)) AS 'Inactivos'"))
        ->get();


        return view('ingenieria.listarUsuarios', compact('user', 'listado_usuarios', 'conteo_activos_inactivos'));
    }

    public function mostrarVistaEditarUsuario (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $id_usuario = $request->id_usuario;

        $info_usuario = DB::table('users as u')
        ->select('u.id','u.name', 'u.email', 'u.email_contacto', 'u.tipo_identificacion', 
        'u.nro_identificacion', 'u.tipo_colaborador', 'u.empresa', 'u.cargo', 'u.telefono_contacto', 
        'u.id_procesos_usuario', 'u.estado')->where('u.id', $id_usuario)->get();

        $informacion_usuario = json_decode(json_encode($info_usuario), true);
        return response()->json($informacion_usuario);

        // return view('ingenieria.edicionUsuario', compact('user', 'info_usuario', 'id_usuario'));
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

            if (count($request->listado_procesos_crear_usuario) > 0) {
                $strings_id_procesos_usuario = implode(",", $request->listado_procesos_crear_usuario);
            }else {
                $strings_id_procesos_usuario = "";
            }

            // E-MAIL : correo_contacto_usuario
            // CORREO POR USUARIO: correo_usuario
            $nuevo_usuario = array(
                'name' => $request->nombre_usuario,
                'email' => $request->correo_usuario,
                'email_contacto' => $request->correo_contacto_usuario,
                'tipo_identificacion' => $request->tipo_identificacion_usuario,
                'nro_identificacion' => $request->nro_identificacion_usuario,
                'tipo_colaborador' => $request->tipo_colaborador,
                'empresa' => $request->empresa_usuario,
                'cargo' => $request->cargo_usuario,
                'telefono_contacto' => $request->telefono_contacto_usuario,
                'password' => bcrypt($request->password_usuario),
                'id_procesos_usuario' => $strings_id_procesos_usuario,
                'estado' => $request->status_crear_usuario,
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

        if (isset($request->editar_listado_procesos_crear_usuario)) {
            $strings_id_procesos_usuario = implode(",", $request->editar_listado_procesos_crear_usuario);
        }else {
            $strings_id_procesos_usuario = "";
        }

        if($request->editar_password_usuario <> ""){
            $password = bcrypt($request->editar_password_usuario);
        }else{
            $password_db =DB::table('users')->where('id', $request->captura_id_usuario)->select('password')->get();
            $password = $password_db[0]->password;
        }

        // Si el estado se cambia a inactivo entonces se realiza la inactivación de su rol principal en caso de que tenga.

        if ($request->editar_status_crear_usuario == "Inactivo") {
            
            $inactivar_rol_principal = array(
                'estado' => "inactivo",
                'updated_at' => $date
            );

            sigmel_usuarios_roles::where([['usuario_id', $request->captura_id_usuario], ['tipo', 'principal']])->update($inactivar_rol_principal);
        }else{
            $activar_rol_principal = array(
                'estado' => "activo",
                'updated_at' => $date
            );
            sigmel_usuarios_roles::where([['usuario_id', $request->captura_id_usuario], ['tipo', 'principal']])->update($activar_rol_principal);
        }
        
        $datos_actualizar_usuario = array(
            'name' => $request->editar_nombre_usuario,
            'email' => $request->editar_correo_usuario,
            'email_contacto' => $request->editar_correo_contacto_usuario,
            'tipo_identificacion' => $request->editar_tipo_identificacion_usuario,
            'nro_identificacion' => $request->editar_nro_identificacion_usuario,
            'tipo_colaborador' => $request->editar_tipo_colaborador,
            'empresa' => $request->editar_empresa_usuario,
            'cargo' => $request->editar_cargo_usuario,
            'telefono_contacto' => $request->editar_telefono_contacto_usuario,
            'password' => $password,
            'id_procesos_usuario' => $strings_id_procesos_usuario,
            'estado' => $request->editar_status_crear_usuario,
            'updated_at' => $date,
        );
        
        // Generamos el update
        User::where('id', $request->captura_id_usuario)->update($datos_actualizar_usuario);

        $mensajes = array(
            "parametro" => 'exito',
            "mensaje" => 'Información de usuario actualizada satisfactoriamente. Para visualizar los cambios debe hacer clic en el botón Actualizar.'
        );

        
        return json_decode(json_encode($mensajes, true));

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

    public function listadoTiposColaborador (){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_tipo_contratos')->select("id", "tipo_contrato as tipo_colaborador")->get();
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

    public function listadotiposColaboradorEditar(Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_tipo_contratos')->select("id", "tipo_contrato as tipo_colaborador")->whereNotIn('tipo_contrato', [$request->tipo_colaborador])->get();
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
        $listado_roles = DB::table('sigmel_roles')->select('id', 'nombre_rol', 'descripcion_rol', 'created_at', 'updated_at')->get();
        return view('ingenieria.listarRoles', compact('user', 'listado_roles'));
    }

    public function mostrarVistaEditarRol(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $rol_id = $request->rol_id;
        $info_rol = DB::table('sigmel_roles')->select('id','nombre_rol', 'descripcion_rol')->where('id', $rol_id)->get();
        return view('ingenieria.edicionRol', compact('user', 'info_rol', 'rol_id'));
    }

    public function guardar_rol (Request $request){

        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para insertarlos
        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        
        // validación para determinar si ya existe un rol dentro del aplicativo
        $validar_rol = DB::table('sigmel_roles')->where('nombre_rol', $request->nombre_rol)->get();

        if(count($validar_rol) > 0){
            return back()->with('rol_no_creado','Este rol se encuentra actualmente registrado. Para verlo y/o modificarlo debe ir a: Consultar Lista Roles');
        }else{
            $nuevo_rol = array(
                'nombre_rol' => $request->nombre_rol,
                'descripcion_rol' => $request->descripcion_rol,
                'created_at' => $date,
            );
        }

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
        
        $mensajes = array(
            "parametro" => 'exito',
            "mensaje" => 'Información de rol actualizada satisfactoriamente. Para visualizar los cambios debe hacer clic en el botón Actualizar.'
        );

        
        return json_decode(json_encode($mensajes, true));
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

        $datos = DB::table('users')->select("id", "name", "email")->get();

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

    /* NUEVA FUNCIÓN: LISTADO DE ROLES QUE LE FALTAN A UN USUARIO POR ASIGNAR DEPENDIENDO DEL USUARIO SELECCIONADO */
    public function listadoRolesXUsuario(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Extraemos los id de los roles que tiene asignados el usuario
        $ids_roles_asignados_usuario = DB::table('sigmel_roles as sr')
        ->leftJoin('sigmel_usuarios_roles as sur', 'sr.id', '=', 'sur.rol_id')
        ->select('sr.id')->where('sur.usuario_id', $request->id_usuario_seleccionado)->get();

        $string_ids_roles_asignados_usuario = array();
        for ($i=0; $i < count($ids_roles_asignados_usuario); $i++) { 
            array_push($string_ids_roles_asignados_usuario, $ids_roles_asignados_usuario[$i]->id);
        }

        if(count($string_ids_roles_asignados_usuario) > 0){
            $datos_info_roles_faltantes = DB::table('sigmel_roles')->select("id", "nombre_rol")->whereNotIn('id', $string_ids_roles_asignados_usuario)->get();
        }else{
            $datos_info_roles_faltantes = DB::table('sigmel_roles')->select("id", "nombre_rol")->get();
        }

        $informacion_roles_faltantes = json_decode(json_encode($datos_info_roles_faltantes), true);
        return response()->json($informacion_roles_faltantes);
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

        $datos_activar_rol = array(
            'estado' => 'activo',
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_usuarios_roles::where([
            ['id', '=', $request->id],
            ['usuario_id', '=', $request->usuario_id],
            ['rol_id', '=', $request->rol_id]
        ])
        ->update($datos_activar_rol);

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
        if($request->nombre_renderizar <>  ""){
            $nombre_renderizar = str_replace(' ', '', lcfirst(ucwords($request->nombre_renderizar)));
        }else {
            $nombre_renderizar = null;
        }
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
                    'nombre_renderizar' => $nombre_renderizar,
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
                    'nombre_renderizar' => $nombre_renderizar,
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
        if($request->nombre_renderizar <>  ""){
            $nombre_renderizar = str_replace(' ', '', lcfirst(ucwords($request->nombre_renderizar)));
        }else {
            $nombre_renderizar = null;
        }
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
                    'nombre_renderizar' => $nombre_renderizar,
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
                    'nombre_renderizar' => $nombre_renderizar,
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

        $datos = DB::table('sigmel_vistas')->select("id", "carpeta", "subcarpeta", "archivo")->orderBy('carpeta')->get();
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
        $info_vista = DB::table('sigmel_vistas')->select('archivo', 'observacion')->where('id', $id_vista)->get();
        return view('ingenieria.edicionVista', compact('user', 'info_vista', 'id_vista'));
    }

    /* public function actualizar_vista(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_actualizar_vista = array(
            'archivo' => str_replace(' ', '', lcfirst(ucwords($request->edicion_nombre_archivo))),
            'observacion' => $request->edicion_observacion_vista,
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_vistas::where('id', $request->id_vista)->update($datos_actualizar_vista);
        return redirect()->route('ConsultarAsignacionVista')->with('vista_actualizada','Vista actualizada correctamente.');
    } */

    public function inactivarVista (Request $request){
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
        sigmel_usuarios_vistas::where([
            ['id', '=', $request->id],
            ['rol_id', '=', $request->rol_id],
            ['vista_id', '=', $request->vista_id],
        ])
        ->update($datos_inactivar_rol);

        return redirect()->route('ConsultarAsignacionVista')->with('confirmacion_vista_inactivada','Vista inactivada correctamente.');
    }

    public function activarVista (Request $request){
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
        sigmel_usuarios_vistas::where([
            ['id', '=', $request->id],
            ['rol_id', '=', $request->rol_id],
            ['vista_id', '=', $request->vista_id],
        ])
        ->update($datos_inactivar_rol);

        return redirect()->route('ConsultarAsignacionVista')->with('confirmacion_vista_activada','Vista activada correctamente.');
    }

    public function cambiarAVistaPrincipal (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }


        // Se extrae el id del rol que el usuario tiene actualmente como principal para cambiarlo al tipo de rol: otro
        $id_tipo_vista_principal= DB::table('sigmel_usuarios_vistas')
                                    ->select('id')
                                    ->where([
                                        ['rol_id', '=', $request->rol_id],
                                        ['tipo', '=', 'principal']
                                    ])->get();
        
        // Actualizamos el rol del id extraido
        $datos_vista_principal_out = array(
            'tipo' => 'otro'
        );
        sigmel_usuarios_vistas::where('id', $id_tipo_vista_principal[0]->id)->update($datos_vista_principal_out);

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_a_vista_principal = array(
            'tipo' => 'principal',
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_usuarios_vistas::where([
            ['id', '=', $request->id],
            ['rol_id', '=', $request->rol_id],
            ['vista_id', '=', $request->vista_id],
        ])
        ->update($datos_a_vista_principal);

        return redirect()->route('ConsultarAsignacionVista')->with('confirmacion_vista_principal','El cambio de tipo de vista se realizó correctamente.');
    }

    /* TODO LO REFERENTE A MENÚS */
    public function mostrarVistaNuevoMenu(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.crearMenu', compact('user'));
    }

    public function guardar_menu(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        
        $nombre = ucfirst(ucwords($request->nombre_menu));
        $id_padre = null;
        $estado = $request->estado_menu;
        $tipo = $request->tipo_menu;
        $rol_id = $request->listado_roles_para_menus;

        // Si no escoje una vista quiere decir que será un menú padre. caso contrario
        // el menú tendrá su misma funcionalidad
        if($request->listado_vistas_para_menus == ''){
            $vista_id = 0;
        }
        else {
            $vista_id = $request->listado_vistas_para_menus;
        }

        $icono = trim($request->nombre_icono);
        $observacion = $request->observacion_menu;

        if ($tipo <> 'primario') {
            return back()->with('menu_no_creado','Aquí solamente podrá crear menús con funcionalidad o menú padres.');
        }

        // / Preparamos los datos en un array para insertarlos
        $crear_menu = array(
            'nombre' => $nombre,
            'id_padre' => $id_padre,
            'estado' => $estado,
            'tipo' => $tipo,
            'rol_id' => $rol_id,
            'vista_id' => $vista_id,
            'icono' => $icono,
            'observacion' => $observacion,
            'created_at' => $date,
        );

        DB::table('sigmel_menuses')->insert($crear_menu);
        return back()->with('menu_creado','Menú creado correctamente.');
    }

    public function mostrarVistaNuevoSubMenu(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.crearSubMenu', compact('user'));
    }

    public function listadoMenusPadres(){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('sigmel_menuses as sm')
                    ->leftJoin("sigmel_roles as sr", "sm.rol_id", "=", "sr.id")
                    ->select("sm.id", "sm.nombre", "sr.nombre_rol")
                    ->whereNull('id_padre')
                    ->where([
                        ['estado', 'activo'],
                        ['tipo', 'primario'],
                        ['vista_id', '0']
                    ])
                    ->get();

        $informacion_roles = json_decode(json_encode($datos), true);

        return response()->json($informacion_roles);
    }

    public function guardar_submenu(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        
        $nombre = ucfirst(ucwords($request->nombre_submenu));
        $id_padre = $request->listado_padres_menu;
        $estado = $request->estado_submenu;
        $tipo = $request->tipo_submenu;

        // select rol_id from sigmel_menuses sm where id = 15;
        $rol_id = DB::table('sigmel_menuses')->select('rol_id')->where('id', $id_padre)->get();
        $rol_id = $rol_id[0]->rol_id;
        
        $vista_id = $request->listado_vistas_para_submenus;

        $icono = trim($request->nombre_icono);
        $observacion = $request->observacion_submenu;

        if ($tipo <> 'secundario') {
            return back()->with('submenu_no_creado','Aquí solamente podrá crear sub menús.');
        }

        // / Preparamos los datos en un array para insertarlos
        $crear_submenu = array(
            'nombre' => $nombre,
            'id_padre' => $id_padre,
            'estado' => $estado,
            'tipo' => $tipo,
            'rol_id' => $rol_id,
            'vista_id' => $vista_id,
            'icono' => $icono,
            'observacion' => $observacion,
            'created_at' => $date,
        );

        DB::table('sigmel_menuses')->insert($crear_submenu);
        return back()->with('submenu_creado','Sub Menú creado correctamente.');
    }

    public function mostrarVistaListarMenuSubmenu(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.listarMenusSubmenus', compact('user'));
    }

    public function ConsultaMenusSubmenus (Request $request){

        
        /* Preparamos los datos para consultar los roles que tiene asignado un usuario */
        $datos = DB::table('sigmel_menuses as sm')
                    ->leftJoin('sigmel_menuses as sm2', 'sm.id_padre', '=', 'sm2.id')
                    ->select("sm.id", "sm.id_padre", "sm.nombre", "sm.estado", "sm2.nombre as padre",
                    DB::raw("CASE WHEN sm.id_padre is null && sm.tipo = 'primario' && sm.vista_id <> 0 THEN 'Menu + Funcionalidad' WHEN sm.id_padre is null && sm.tipo = 'primario' && sm.vista_id = 0 THEN 'Menu Padre' WHEN sm.id_padre <> '' && sm.tipo = 'secundario' && sm.vista_id <> 0 THEN 'Sub menu' ELSE 1 END as tipo_menu"), "sm.created_at", "sm.updated_at")
                    ->where("sm.rol_id", $request->rol_id)
                    ->orderBy('sm.id', 'asc')
                    ->get();
        
        // echo "<pre>";
        // print_r(json_decode(json_encode($datos), true));
        // echo "</pre>";

        return response()->json($datos);
    }

    public function inactivarMenuSubmenu (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_inactivar_menu = array(
            'estado' => 'inactivo',
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_menus::where([
            ['id', '=', $request->id]
        ])
        ->update($datos_inactivar_menu);

        $tipo = str_replace("_", " ", $request->tipo_menu);
        $msg = "{$tipo} inactivado correctamente.";
        return redirect()->route('listarMenusSubmenus')->with('confirmacion_menu_inactivado', $msg);
    }

    public function activarMenuSubmenu (Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_activar_menu = array(
            'estado' => 'activo',
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_menus::where([
            ['id', '=', $request->id],
        ])
        ->update($datos_activar_menu);

        $tipo = str_replace("_", " ", $request->tipo_menu);
        $msg = "{$tipo} activado correctamente.";
        return redirect()->route('listarMenusSubmenus')->with('confirmacion_menu_activado', $msg);
    }

    public function mostrarVistaEditarMenu(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $id_menu = $request->id_menu;
        $info_menu = DB::table('sigmel_menuses')->select('nombre', 'icono', 'observacion')->where('id', $id_menu)->get();
        return view('ingenieria.edicionMenu', compact('user', 'info_menu', 'id_menu'));
    }

    public function actualizar_menu(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info del usuario.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        $datos_actualizar_menu = array(
            'nombre' => $request->editar_nombre_menu,
            'observacion' => $request->editar_descripcion_menu,
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_menus::where('id', $request->id_menu)->update($datos_actualizar_menu);
        return redirect()->route('listarMenusSubmenus')->with('confirmacion_menu_editado','Información actualizada correctamente.');
    }

    /* TODO LO REFERENTE A EDICION DE PLANTILLA: SIDEBAR - NAVBAR - FOOTER */
    public function mostrarVistaEdicionPlantilla(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('ingenieria.edicionNavbarSidebarFooter', compact('user'));
    }

    public function aplicar_edicion_plantilla(Request $request){

        $ruta_archivo_css = '/var/www/html/Sigmel/public/css/navbar_sidebar_footer.css';
        /* OPCION: PLANTILLA PREDETERMINADA */
        $plantilla_predeterminada = $request->plantilla_predeterminada;
        
        if ($plantilla_predeterminada <> 'ninguna_predeterminada') {
            switch ($plantilla_predeterminada) {
                case 'plantilla_oscura':
                    $codigo_css = "
                        /* background */
                        .sidebar-dark-white2{
                            background-color: #343a40 !important;
                        }
    
                        /* colores menú padre sin seleccionar */
                        .nav-sidebar .nav-item > .nav-link{
                            color: white !important;
                        }
    
                        /* colores menú padre que tengan sub menus cuando son seleccionados */
                        .nav-sidebar > .nav-item.menu-open > .nav-link{
                            background-color: rgba(255, 255, 255, .1) !important;
                            color: white !important;
                        }
    
                        /* color menú padre cuando se deja de hacer focus */
                        .nav-pills .nav-link.active{
                            color: #fff !important;
                            background-color: #007bff !important;
                        }
    
                        /* colores menú hijos */
                        .nav-treeview > .nav-item > .nav-link{
                            color:white !important;
                        }
    
                        /* hover menu hijos */
                        .nav-treeview > .nav-item > .nav-link:hover{
                            box-shadow: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);
                            margin-left: 7px;
                        }
    
                        /* colores menú hijo seleccionado */
                        .nav-treeview >.nav-item >.nav-link.active{
                            background-color: rgba(255,255,255,.9) !important;
                            color: #343A40 !important;
                        }
    
                        .nav-header {
                            color: white !important;
                            text-align: center;
                            font-weight: bold;
                        }
                    ";
                    
                    $apertura_archivo = fopen($ruta_archivo_css, 'w+');
                    fwrite($apertura_archivo, trim($codigo_css));
                    fclose($apertura_archivo);
                break;
                case 'plantilla_gris':
                    $codigo_css = "
                        /* background */
                        .sidebar-dark-white2{
                            background-color: #ECECEC !important;
                        }
    
                        /* colores menú padre sin seleccionar */
                        .nav-sidebar .nav-item > .nav-link{
                            color:rgb(10, 10, 10) !important;
                        }
    
                        /* colores menú padre que tengan sub menus cuando son seleccionados */
                        .nav-sidebar > .nav-item.menu-open > .nav-link{
                            color: rgb(10, 10, 10) !important;
                            background-color: rgba(255, 255, 255, .1) !important;
                        }
    
                        /* color menú padre cuando se deja de hacer focus */
                        .nav-pills .nav-link.active{
                            color: rgb(10, 10, 10) !important;
                            background-color: #ECECEC !important;
                        }
    
                        /* colores menú hijos */
                        .nav-treeview > .nav-item > .nav-link{
                            color:rgb(10, 10, 10) !important;
                        }
    
                        /* hover menu hijos*/
                        .nav-treeview > .nav-item > .nav-link:hover{
                            box-shadow: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);
                            margin-left: 7px;
                        }
    
                        /* colores menú hijo seleccionado */
                        .nav-treeview >.nav-item >.nav-link.active{
                            background-color: rgba(255,255,255,.9) !important;
                        }
    
                        .nav-header {
                            color: black !important;
                            text-align: center;
                            font-weight: bold;
                        }
                    ";
    
                    $apertura_archivo = fopen($ruta_archivo_css, 'w+');
                    fwrite($apertura_archivo, trim($codigo_css));
                    fclose($apertura_archivo);
                break;
                case 'plantilla_botones':
                    $codigo_css = "
                        .sidebar-dark-white2{
                            background-color: #ECECEC !important;
                        }
                        
                        .nav .nav-link{/* Cambiar estilo de opciones*/ 
                            color:black !important; 
                            background: linear-gradient(90deg, rgba(163,163,163,1) 0%, rgba(251,251,251,1) 82%);
                            font-weight: bold;
                            border-right: #9a9a9a 3px solid;
                            border-bottom: #9a9a9a 3px solid;
                            margin-bottom: 10px !important;
                        }
                        .nav .nav-link:hover{
                            background: linear-gradient(90deg,rgba(251,251,251,1)  70%, rgba(163,163,163,1) 90%);
                            color:#0d6efd!important;
                            font-weight: bold;
                            font-size: 16px;
                        }
                        
                        .nav-pills .nav-treeview .nav-link.active, .nav-pills .show>.nav-link {
                            color: white !important;
                            font-weight: bold;  
                            background: #17a2b8 !important; 
                        }
                        
                        .nav-treeview .nav-link {
                            margin-left: 7px;
                            font-weight: normal;
                            border-right: #0d6efd 1px solid;
                            border-bottom: #0d6efd 1px solid;
                        }
    
                        .nav-header {
                            color: black !important;
                            text-align: center;
                            font-weight: bold;
                        }
                    ";
    
                    $apertura_archivo = fopen($ruta_archivo_css, 'w+');
                    fwrite($apertura_archivo, trim($codigo_css));
                    fclose($apertura_archivo);
                break;
                case 'plantilla_naranja':
                    $codigo_css = "
                        .sidebar-dark-white2{
                            background: #ECECEC !important;  /* Color para el sidebar*/
                        }
                        
                        .nav-link{
                            color:rgb(10, 10, 10) !important; /* Cambiar color de texto de nav*/ 
                        }
                        
                        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
                            color: rgb(10, 10, 10);
                            background-color: #ECECEC;
                        }
                        
                        .nav-header {
                            color: black !important;
                        }
                        
                        .nav-link.active {
                            color: rgb(253, 126, 20) !important;
                        }
                        
                        /* backgroubd navbar */
                        .navbar-orange{
                            background-color: #FD7E14 !important;
                        }
                        
                        /* botón apertura/cierre sidebar */
                        .fa-bars{
                            color: #0A0A0A !important;
                        }
                        
                        /* nombre usuario */
                        .dropdown-toggle{
                            color: #0A0A0A !important;
                        }
                    ";
                    $apertura_archivo = fopen($ruta_archivo_css, 'w+');
                    fwrite($apertura_archivo, trim($codigo_css));
                    fclose($apertura_archivo);
                break;
                default:
                    # code...
                break;
            }

            sleep(6);
            return back()->with('edicion_realizada', 'Código css editado correctamente');
        }

        if ($request->background_sidebar <> '') {

            /* CAPTURA DE LOS CHECKBOX DE CONFIRMACIÓN DE CAMBIO DE ESTILOS */
            $si_no_sidebar = $request->si_no_sidebar;
            $si_no_navbar = $request->si_no_navbar;
            $si_no_footer = $request->si_no_footer;

            if($si_no_sidebar === 'on'){
                /* VARIABLES PARA EL SIDEBAR */
                $background_sidebar = $request->background_sidebar;
                $color_menu_padre_sin_seleccionar_sidebar = $request->color_menu_padre_sin_seleccionar_sidebar;
                $color_menu_padre_submenu_seleccionado_sidebar = $this->rgb2hex2rgb($request->color_menu_padre_submenu_seleccionado_sidebar);
                $color_menu_padre_sin_focus_sidebar = $request->color_menu_padre_sin_focus_sidebar;
                $color_sub_menus_sidebar = $request->color_sub_menus_sidebar;
                $color_hover_sub_menus_sidebar = $this->rgb2hex2rgb($request->color_hover_sub_menus_sidebar);
                $color_sub_menu_seleccionado_sidebar = $this->rgb2hex2rgb($request->color_sub_menu_seleccionado_sidebar);

                $codigo_css_sidebar = " /* TODO LO REFERENTE AL SIDEBAR */
                    /* background */
                    .sidebar-dark-white2{
                        background-color: {$background_sidebar} !important;
                    }

                    /* colores menú padre sin seleccionar */
                    .nav-sidebar .nav-item > .nav-link{
                        color: {$color_menu_padre_sin_seleccionar_sidebar} !important;
                    }

                    /* colores menú padre que tengan sub menus cuando son seleccionados */
                    .nav-sidebar > .nav-item.menu-open > .nav-link{
                        background-color: rgba({$color_menu_padre_submenu_seleccionado_sidebar['r']}, {$color_menu_padre_submenu_seleccionado_sidebar['g']}, {$color_menu_padre_submenu_seleccionado_sidebar['b']}, .1) !important;
                        /* color: white !important; */
                    }

                    /* color menú padre cuando se deja de hacer focus */
                    .nav-pills .nav-link.active{
                        color: #fff !important;
                        background-color: {$color_menu_padre_sin_focus_sidebar} !important;
                    }

                    /* colores menú hijos */
                    .nav-treeview > .nav-item > .nav-link{
                        color: {$color_sub_menus_sidebar} !important;
                    }

                    /* hover menu hijos */
                    .nav-treeview > .nav-item > .nav-link:hover{
                        box-shadow: 0 1px 3px rgba({$color_hover_sub_menus_sidebar['r']}, {$color_hover_sub_menus_sidebar['g']}, {$color_hover_sub_menus_sidebar['b']}, .12),0 1px 2px rgba({$color_hover_sub_menus_sidebar['r']}, {$color_hover_sub_menus_sidebar['g']}, {$color_hover_sub_menus_sidebar['b']}, .24);
                        margin-left: 7px;
                    }

                    /* colores menú hijo seleccionado */
                    .nav-treeview >.nav-item >.nav-link.active{
                        background-color: rgba({$color_sub_menu_seleccionado_sidebar['r']}, {$color_sub_menu_seleccionado_sidebar['g']}, {$color_sub_menu_seleccionado_sidebar['b']}, .9) !important;
                        color: #343A40 !important;
                    }
                ";

                /* Mofidica todo el contenido del archivo */
                $tipo_edicion = 'w+'; 

                $apertura_archivo = fopen($ruta_archivo_css, $tipo_edicion);
                fwrite($apertura_archivo, trim($codigo_css_sidebar));
                fclose($apertura_archivo);
                
                
            }else{$codigo_css_sidebar= '';}

            if($si_no_navbar === 'on'){
                /* VARIABLES PARA EL NAVBAR */
                $background_navbar = $request->background_navbar;
                $color_boton_apertura_cierre_sidebar = $request->color_boton_apertura_cierre_sidebar;
                $color_nombre_usuario = $request->color_nombre_usuario;

                $codigo_css_navbar = "
                    /* TODO LO REFERENTE AL NAVBAR */

                    /* backgroubd navbar */
                    .navbar-white{
                        background-color: {$background_navbar} !important;
                    }

                    /* botón apertura/cierre sidebar */
                    .fa-bars{
                        color: {$color_boton_apertura_cierre_sidebar} !important;
                    }

                    /* nombre usuario */
                    .dropdown-toggle{
                        color: {$color_nombre_usuario} !important;
                    }
                ";

                /* Añade el contenido al archivo */
                $tipo_edicion = 'a+'; 

                $apertura_archivo = fopen($ruta_archivo_css, $tipo_edicion);
                fwrite($apertura_archivo, trim($codigo_css_navbar));
                fclose($apertura_archivo);
                
            }else{$codigo_css_navbar = '';}

            if($si_no_footer === 'on'){
                /* VARIABLES PARA EL FOOTER */
                $background_footer = $request->background_footer;
                $color_texto_footer = $request->color_texto_footer;

                $codigo_css_footer = "
                    /* TODO LO REFERENTE AL FOOTER */
    
                    /* background y texto footer */
                    .main-footer{
                        background-color: {$background_footer} !important;
                        color: {$color_texto_footer} !important;
                    }
                ";

                /* Añade el contenido al archivo */
                $tipo_edicion = 'a+'; 

                $apertura_archivo = fopen($ruta_archivo_css, $tipo_edicion);
                fwrite($apertura_archivo, trim($codigo_css_footer));
                fclose($apertura_archivo);

            }else{$codigo_css_footer= '';}

            /* $codigo_css_nuevo = $codigo_css_sidebar.$codigo_css_navbar.$codigo_css_footer;
            $apertura_archivo = fopen($ruta_archivo_css, $tipo_edicion);
            fwrite($apertura_archivo, trim($codigo_css_nuevo));
            fclose($apertura_archivo); */

            sleep(6);
            return back()->with('edicion_realizada', 'Código css editado correctamente');
            
        }
    }
    function rgb2hex2rgb($color){ 
        if(!$color) return false; 
        $color = trim($color); 
        $result = false; 
       if(preg_match("/^[0-9ABCDEFabcdef\#]+$/i", $color)){
           $hex = str_replace('#','', $color);
           if(!$hex) return false;
           if(strlen($hex) == 3):
              $result['r'] = hexdec(substr($hex,0,1).substr($hex,0,1));
              $result['g'] = hexdec(substr($hex,1,1).substr($hex,1,1));
              $result['b'] = hexdec(substr($hex,2,1).substr($hex,2,1));
           else:
              $result['r'] = hexdec(substr($hex,0,2));
              $result['g'] = hexdec(substr($hex,2,2));
              $result['b'] = hexdec(substr($hex,4,2));
           endif;       
        }elseif (preg_match("/^[0-9]+(,| |.)+[0-9]+(,| |.)+[0-9]+$/i", $color)){ 
           $rgbstr = str_replace(array(',',' ','.'), ':', $color); 
           $rgbarr = explode(":", $rgbstr);
           $result = '#';
           $result .= str_pad(dechex($rgbarr[0]), 2, "0", STR_PAD_LEFT);
           $result .= str_pad(dechex($rgbarr[1]), 2, "0", STR_PAD_LEFT);
           $result .= str_pad(dechex($rgbarr[2]), 2, "0", STR_PAD_LEFT);
           $result = strtoupper($result); 
        }else{
           $result = false;
        }
               
        return $result; 
    } 
}
