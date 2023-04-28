<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Autenticacion\LoginController;
use App\Http\Controllers\Autenticacion\LogoutController;
use App\Http\Controllers\Ingenieria\IngenieriaController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('login',[LoginController::class, 'show'])->name('login');
// Cuando se ingresa a sigmel manda el login
Route::get('/', [LoginController::class, 'show'])->name('login');
// Cuando se ingresan las credenciales de inicion de sesión
Route::post('/', [LoginController::class, 'authenticate'])->name('loginSigmel');
// Cuando se cierra sesión
Route::post('/logout', [LogoutController:: class, 'destroy']);
// Inicio de sesión
Route::get('/Sigmel', [RolesController::class, 'rolPrincipal'])->name('RolPrincipal');
// Traer listado de roles
Route::post('/rolesiniciales', [RolesController::class, 'listadoRoles']);
// Cambio de rol
Route::post('/Sigmel',[RolesController::class, 'cambioDeRol'])->name('Sigmel');

// 24/04/2023 - CRUD DE USUARIOS.
// Vista: formulario para crear usuarios
Route::get('/Sigmel/usuarios/nuevoUsuario', [IngenieriaController::class, 'mostrarVistaNuevoUsuario'])->name('NuevoUsuario');
// Creación de nuevo usuario
Route::post('/Sigmel/creacionUsuario', [IngenieriaController::class, 'guardar_usuario'])->name('CreacionUsuario');
// Vista: Listar Usuarios
Route::get('/Sigmel/usuarios/listarUsuarios', [IngenieriaController::class, 'mostrarVistaListarUsuarios'])->name('ListarUsuarios');
// Vista: formulario para editar la información de un usuario
Route::post('/Sigmel/usuarios/editarUsuario', [IngenieriaController::class, 'mostrarVistaEditarUsuario'])->name('EditarUsuario');
// Actualización de la información del Usuario
Route::post('/Sigmel/usuarios/actualizarUsuario', [IngenieriaController::class, 'actualizar_usuario'])->name('ActualizacionUsuario');

// 24/04/2023 - CRUD DE ROLES.
// Vista: Mostrar formulario para crear roles
Route::get('/Sigmel/usuarios/nuevoRol', [IngenieriaController::class, 'mostrarVistaNuevoRol'])->name('NuevoRol');
// Creación de un nuevo rol
Route::post('/Sigmel/usuarios/creacionRol', [IngenieriaController::class, 'guardar_rol'])->name('CreacionRol');
// Vista: Listar Usuarios
Route::get('/Sigmel/usuarios/listarRoles', [IngenieriaController::class, 'mostrarVistaListarRoles'])->name('ListadoRoles');
// Vista: formulario para editar la información de un rol
Route::post('/Sigmel/usuarios/editarRol', [IngenieriaController::class, 'mostrarVistaEditarRol'])->name('EditarRol');
// Actualización de la información del rol
Route::post('/Sigmel/usuarios/actualizarRol', [IngenieriaController::class, 'actualizar_rol'])->name('ActualizacionRol');

// 24/04/2023 - ASIGNACIÓN DE ROLES A USUARIOS.
// Vista: Asignación de Roles a Usuarios
Route::get('/Sigmel/usuarios/asignacionRol', [IngenieriaController::class, 'mostrarVistaAsignacionRol'])->name('AsignacionRol');
// Traer listado de usuarios para selector de usuarios
Route::post('/listausuarios', [IngenieriaController::class, 'listadoUsuarios']);
// Traer listado de todos los roles para selector de roles
Route::post('/listatodosroles', [IngenieriaController::class, 'listadoTodosRoles']);
// Creación de Asignación de Rol
Route::post('/Sigmel/usuarios/asignacionRol', [IngenieriaController::class, 'asignar_rol'])->name('AsignacionRol');

// 24/04/2023 - CONSULTAR ASIGNACIÓN DE ROLES A USUARIOS.
// Vista: Consulta de Asignación de roles de usuario
Route::get('/Sigmel/usuarios/consultarAsignacionRol', [IngenieriaController::class, 'mostrarVistaConsultarAsignacionRol'])->name('ConsultarAsignacionRol');
// Trae la información de los roles que tiene asignado un usuario para llenar el datatable correspondiente.
Route::post('/ConsultaAsignacionRolUsuario', [IngenieriaController::class, 'consultaAsignacionRolUsuario']);

// 25/04/2023
// Traer listado de tipos de identificación para el selector de tipo de documento
Route::post('/listartiposidentificacion', [IngenieriaController::class, 'listadoTiposIdentificacion']);
// Traer listado de tipos de contratos para el selector de tipos de contrato
Route::post('/listartiposContrato', [IngenieriaController::class, 'listadotiposContrato']);
// Traer listado de tipos de identificación edición usuario para el selector de tipo de documento para la edición
Route::post('/listadoTiposIdentificacionEditar', [IngenieriaController::class, 'listadoTiposIdentificacionEditar']);
// Traer listado de tipos de contratos edición usuario para el selector tipo de contratos para la edición
Route::post('/listadotiposContratoEditar', [IngenieriaController::class, 'listadotiposContratoEditar']);
// Inactivar rol
Route::get('/inactivarRol/{id}/{usuario_id}/{rol_id}', [IngenieriaController::class, 'inactivarRol'])->name('inactivarRol');
// Activar rol
Route::get('/activarRol/{id}/{usuario_id}/{rol_id}', [IngenieriaController::class, 'activarRol'])->name('activarRol');
//Cambiar a ROL Otro
Route::get('/cambiarARolPrincipal/{id}/{usuario_id}/{rol_id}', [IngenieriaController::class, 'cambiarARolPrincipal'])->name('cambiarARolPrincipal');

// 26/04/2023 - CRUD VISTAS
// Vista: Mostrar formulario para crear una nueva vista principal
Route::get('/Sigmel/usuarios/nuevaVista', [IngenieriaController::class, 'mostrarVistaNuevaVista'])->name('NuevaVista');
// Creación de una nueva vista
Route::post('/Sigmel/usuarios/creacionVista', [IngenieriaController::class, 'guardar_vista'])->name('CreacionVista');
// Vista: Mostrar formulario para crear vistas secundarias
Route::get('/Sigmel/usuarios/nuevaOtraVista', [IngenieriaController::class, 'mostrarVistaNuevaVistaOtros'])->name('NuevaVistaOtros');
// Traer listado de carpetas de vistas para el selector de carpetas
Route::post('/listarCarpetasVistas', [IngenieriaController::class, 'listadoCarpetasVistas']);
// Traer listado de sub carpetas de las carpetas de las vistas para el selector de subcarpetas
Route::post('/listarSubCarpetasCarpetasVistas', [IngenieriaController::class, 'listadoSubCarpetasCarpetasVistas']);
// Creación de una otra vista
Route::post('/Sigmel/usuarios/creacionOtraVista', [IngenieriaController::class, 'guardar_otra_vista'])->name('CreacionOtraVista');

// 27/04/2023 - ASIGNACIÓN DE VISTAS A ROLES
// Vista: Asignación de Roles a Usuarios
Route::get('/Sigmel/usuarios/asignacionVista', [IngenieriaController::class, 'mostrarVistaAsignacionVista'])->name('AsignacionVista');
// Traer listado de carpetas y subcarpetas de vistas para el selector de carpetas
Route::post('/listarCarpetasYSubCarpetasVistas', [IngenieriaController::class, 'listadoCarpetasSubCarpetasVistas']);
// Creación de Asignación de Vista
Route::post('/Sigmel/usuarios/asignacionVista', [IngenieriaController::class, 'asignar_vista'])->name('AsignacionVista');

// 27/04/2023 - CONSULTAR ASIGNACIÓN DE VISTAS A ROLES.
// Vista: Consulta de Asignación de Vistas a roles
Route::get('/Sigmel/usuarios/consultarAsignacionVista', [IngenieriaController::class, 'mostrarVistaConsultarAsignacionVista'])->name('ConsultarAsignacionVista');
// Trae la información de las vistas que tiene asignado un rol para llenar el datatable correspondiente.
Route::post('/ConsultaAsignacionVistaRol', [IngenieriaController::class, 'ConsultaAsignacionVistaRol']);
// Vista: formulario para editar la información de una vista
Route::get('/Sigmel/usuarios/editarVista/{id_vista}', [IngenieriaController::class, 'mostrarVistaEditarVista'])->name('EditarVista');

/* Route::get('Sigmel/usuarios/mao', function(){
    $user = Auth::user();
    return view('coordinador.iniciales_1.index', compact('user'));
}); */