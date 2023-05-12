<?php

use App\Http\Controllers\Administrador\AdministradorController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Autenticacion\LoginController;
use App\Http\Controllers\Autenticacion\LogoutController;
use App\Http\Controllers\Ingenieria\IngenieriaController;
use App\Http\Controllers\ProbandoController;
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
// Acción: Creación de nuevo usuario
Route::post('/Sigmel/creacionUsuario', [IngenieriaController::class, 'guardar_usuario'])->name('CreacionUsuario');
// Vista: Listar Usuarios
Route::get('/Sigmel/usuarios/listarUsuarios', [IngenieriaController::class, 'mostrarVistaListarUsuarios'])->name('ListarUsuarios');
// Vista: formulario para editar la información de un usuario
Route::post('/Sigmel/usuarios/editarUsuario', [IngenieriaController::class, 'mostrarVistaEditarUsuario'])->name('EditarUsuario');
// Acción: Actualización de la información del Usuario
Route::post('/Sigmel/usuarios/actualizarUsuario', [IngenieriaController::class, 'actualizar_usuario'])->name('ActualizacionUsuario');

// 24/04/2023 - CRUD DE ROLES.
// Vista: Mostrar formulario para crear roles
Route::get('/Sigmel/usuarios/nuevoRol', [IngenieriaController::class, 'mostrarVistaNuevoRol'])->name('NuevoRol');
// Acción: Creación de un nuevo rol
Route::post('/Sigmel/usuarios/creacionRol', [IngenieriaController::class, 'guardar_rol'])->name('CreacionRol');
// Vista: Listar Roles
Route::get('/Sigmel/usuarios/listarRoles', [IngenieriaController::class, 'mostrarVistaListarRoles'])->name('ListadoRoles');
// Vista: formulario para editar la información de un rol
Route::post('/Sigmel/usuarios/editarRol', [IngenieriaController::class, 'mostrarVistaEditarRol'])->name('EditarRol');
// Acción: Actualización de la información del rol
Route::post('/Sigmel/usuarios/actualizarRol', [IngenieriaController::class, 'actualizar_rol'])->name('ActualizacionRol');

// 24/04/2023 - ASIGNACIÓN DE ROLES A USUARIOS.
// Vista: Asignación de Roles a Usuarios
Route::get('/Sigmel/usuarios/asignacionRol', [IngenieriaController::class, 'mostrarVistaAsignacionRol'])->name('AsignacionRol');
// Acción: Traer listado de usuarios para selector de usuarios js
Route::post('/listausuarios', [IngenieriaController::class, 'listadoUsuarios']);
// Acción: Traer listado de todos los roles para selector de roles js
Route::post('/listatodosroles', [IngenieriaController::class, 'listadoTodosRoles']);
// Acción: Creación de Asignación de Rol
Route::post('/Sigmel/usuarios/asignacionRol', [IngenieriaController::class, 'asignar_rol'])->name('AsignacionRol');

// 24/04/2023 - CONSULTAR ASIGNACIÓN DE ROLES A USUARIOS.
// Vista: Consulta de Asignación de roles de usuario
Route::get('/Sigmel/usuarios/consultarAsignacionRol', [IngenieriaController::class, 'mostrarVistaConsultarAsignacionRol'])->name('ConsultarAsignacionRol');
// Acción: Trae la información de los roles que tiene asignado un usuario para llenar el datatable correspondiente.
Route::post('/ConsultaAsignacionRolUsuario', [IngenieriaController::class, 'consultaAsignacionRolUsuario']);

// 25/04/2023
// Acción: Traer listado de tipos de identificación para el selector de tipo de documento js
Route::post('/listartiposidentificacion', [IngenieriaController::class, 'listadoTiposIdentificacion']);
// Acción: Traer listado de tipos de contratos para el selector de tipos de contrato js
Route::post('/listartiposContrato', [IngenieriaController::class, 'listadotiposContrato']);
// Acción: Traer listado de tipos de identificación edición usuario para el selector de tipo de documento para la edición js
Route::post('/listadoTiposIdentificacionEditar', [IngenieriaController::class, 'listadoTiposIdentificacionEditar']);
// Acción: Traer listado de tipos de contratos edición usuario para el selector tipo de contratos para la edición js
Route::post('/listadotiposContratoEditar', [IngenieriaController::class, 'listadotiposContratoEditar']);
// Acción Inactivar rol
Route::get('/inactivarRol/{id}/{usuario_id}/{rol_id}', [IngenieriaController::class, 'inactivarRol'])->name('inactivarRol');
// Acción: Activar rol
Route::get('/activarRol/{id}/{usuario_id}/{rol_id}', [IngenieriaController::class, 'activarRol'])->name('activarRol');
// Acción: Cambiar a ROL Otro
Route::get('/cambiarARolPrincipal/{id}/{usuario_id}/{rol_id}', [IngenieriaController::class, 'cambiarARolPrincipal'])->name('cambiarARolPrincipal');

// 26/04/2023 - CRUD VISTAS
// Vista: Mostrar formulario para crear una nueva vista principal
Route::get('/Sigmel/usuarios/nuevaVista', [IngenieriaController::class, 'mostrarVistaNuevaVista'])->name('NuevaVista');
// Acción: Creación de una nueva vista
Route::post('/Sigmel/usuarios/creacionVista', [IngenieriaController::class, 'guardar_vista'])->name('CreacionVista');
// Vista: Mostrar formulario para crear vistas secundarias
Route::get('/Sigmel/usuarios/nuevaOtraVista', [IngenieriaController::class, 'mostrarVistaNuevaVistaOtros'])->name('NuevaVistaOtros');
// Acción: Traer listado de carpetas de vistas para el selector de carpetas js
Route::post('/listarCarpetasVistas', [IngenieriaController::class, 'listadoCarpetasVistas']);
// Acción: Traer listado de sub carpetas de las carpetas de las vistas para el selector de subcarpetas js
Route::post('/listarSubCarpetasCarpetasVistas', [IngenieriaController::class, 'listadoSubCarpetasCarpetasVistas']);
// Acción: Creación de una otra vista
Route::post('/Sigmel/usuarios/creacionOtraVista', [IngenieriaController::class, 'guardar_otra_vista'])->name('CreacionOtraVista');

// 27/04/2023 - ASIGNACIÓN DE VISTAS A ROLES
// Vista: Asignación de Vistas a Roles
Route::get('/Sigmel/usuarios/asignacionVista', [IngenieriaController::class, 'mostrarVistaAsignacionVista'])->name('AsignacionVista');
// Acción: Traer listado de carpetas y subcarpetas de vistas para el selector de carpetas js
Route::post('/listarCarpetasYSubCarpetasVistas', [IngenieriaController::class, 'listadoCarpetasSubCarpetasVistas']);
// Acción: Creación de Asignación de Vista
Route::post('/Sigmel/usuarios/asignacionVista', [IngenieriaController::class, 'asignar_vista'])->name('AsignacionVista');

// 27/04/2023 - CONSULTAR ASIGNACIÓN DE VISTAS A ROLES.
// Vista: Consulta de Asignación de Vistas a roles
Route::get('/Sigmel/usuarios/consultarAsignacionVista', [IngenieriaController::class, 'mostrarVistaConsultarAsignacionVista'])->name('ConsultarAsignacionVista');
// Acción: Trae la información de las vistas que tiene asignado un rol para llenar el datatable correspondiente.
Route::post('/ConsultaAsignacionVistaRol', [IngenieriaController::class, 'ConsultaAsignacionVistaRol']);
// Vista: formulario para editar la información de una vista
Route::post('/Sigmel/usuarios/editarVista', [IngenieriaController::class, 'mostrarVistaEditarVista'])->name('EditarVista');

// 28/04/2023
// Acción Actualización de la información del vista
// Route::post('/Sigmel/usuarios/actualizarVista', [IngenieriaController::class, 'actualizar_vista'])->name('ActualizacionVista');
// Acción Inactivar Vista
Route::get('/inactivarVista/{id}/{rol_id}/{vista_id}', [IngenieriaController::class, 'inactivarVista'])->name('inactivarVista');
// Acción: Activar Vista
Route::get('/activarVista/{id}/{rol_id}/{vista_id}', [IngenieriaController::class, 'activarVista'])->name('activarVista');
// Acción: Cambiar a vista principal
Route::get('/cambiarAVistaPrincipal/{id}/{rol_id}/{vista_id}', [IngenieriaController::class, 'cambiarAVistaPrincipal'])->name('cambiarAVistaPrincipal');

// 02/05/2023 - CRUD MENÚS
// Vista: Formulario para crear un menú
Route::get('/Sigmel/usuarios/nuevoMenu', [IngenieriaController::class, 'mostrarVistaNuevoMenu'])->name('nuevoMenu');
// Acción: Creación de un menu padre
Route::post('/Sigmel/usuarios/creacionMenu', [IngenieriaController::class, 'guardar_menu'])->name('creacionMenu');
// Vista: Formulario para crear un sub menú
Route::get('/Sigmel/usuarios/nuevoSubMenu', [IngenieriaController::class, 'mostrarVistaNuevoSubMenu'])->name('nuevoSubMenu');
// Acción: Traer listado de todos los menu padres para selector de menu padres js
Route::post('/listamenupadres', [IngenieriaController::class, 'listadoMenusPadres']);
// Acción: Creación de un sub menu
Route::post('/Sigmel/usuarios/creacionSubMenu', [IngenieriaController::class, 'guardar_submenu'])->name('creacionSubMenu');
// Vista: Listar Menus y/o Sub menus
Route::get('/Sigmel/usuarios/listarMenuSubmenu', [IngenieriaController::class, 'mostrarVistaListarMenuSubmenu'])->name('listarMenusSubmenus');
// Acción: Trae la información de los menus que tiene asignado un rol para llenar el datatable correspondiente.
Route::post('/ConsultaMenusSubmenus', [IngenieriaController::class, 'ConsultaMenusSubmenus']);
// Acción Inactivar Menu
Route::get('/inactivarMenuSubmenu/{id}/{tipo_menu}', [IngenieriaController::class, 'inactivarMenuSubmenu'])->name('inactivarMenuSubmenu');
// Acción: Activar Menu
Route::get('/activarMenuSubmenu/{id}/{tipo_menu}', [IngenieriaController::class, 'activarMenuSubmenu'])->name('activarMenuSubmenu');
// Vista: Editar Información Menu
Route::post('/Sigmel/usuarios/EditarMenu', [IngenieriaController::class, 'mostrarVistaEditarMenu'])->name('EditarMenu');
// Acción: Actualización de la información del menú
Route::post('/Sigmel/usuarios/actualizarMenu', [IngenieriaController::class, 'actualizar_menu'])->name('ActualizacionMenu');

// 03/05/2023 - CRUD GRUPOS TRABAJO
// Vista: Formulario para crear un nuevo grupo de trabajo
Route::get('/Sigmel/RolAdministrador/NuevoGrupoTrabajo', [AdministradorController::class, 'mostrarVistaNuevoGrupoTrabajo'])->name('crearGruposTrabajo');
// Acción: Crear un nuevo grupo de trabajo
Route::post('/Sigmel/RolAdministrador/CrearNuevoGrupo', [AdministradorController::class, 'guardar_grupo_trabajo'])->name('CrearNuevoGrupo');
// Vista: Listar grupos de trabajo
Route::get('/Sigmel/RolAdministrador/listarGruposTrabajo', [AdministradorController::class, 'mostrarVistaListarGruposTrabajo'])->name('listarGruposTrabajo');
// Vista: Formulario para editar un grupo de trabajo
Route::post('/Sigmel/RolAdministrador/EditarGrupoTrabajo', [AdministradorController::class, 'mostrarVistaEditarGrupoTrabajo'])->name('EditarGrupoTrabajo');
// Acción: Traer listado de lideres para edición grupo de trabajos para el selector de lideres para la edición js
Route::post('/listadoLideresEditar', [AdministradorController::class, 'listadoLideresEditar']);
// Acción: Traer listado de usuarios asignados y no asignados para construir el selector dual de usuarios asignar grupos 
Route::post('/listadoUsuariosAsignacion', [AdministradorController::class, 'listadoUsuariosAsignacion']);

// 04/05/2023
// Acción: Guardar edición de grupo de trabajo
Route::post('/Sigmel/RolAdministrador/GuardarEdicionGrupo', [AdministradorController::class, 'editar_grupo_trabajo'])->name('GuardarEdicionGrupo');
// Vista: Formulario para registrar Cliente
Route::get('/Sigmel/RolAdministrador/RegistroCliente', [AdministradorController::class, 'mostrarVistaCrearCliente'])->name('registrarCliente');
// Acción: Registrar el cliente
Route::post('/Sigmel/RolAdministrador/CrearCliente', [AdministradorController::class, 'guardar_cliente'])->name('CrearCliente');
// Acción: Actualizar información del cliente.
Route::post('/Sigmel/RolAdministrador/ActualizarCliente', [AdministradorController::class, 'actualizar_cliente'])->name('ActualizarCliente');
// Vista: Bandeja de gestión Inicial
Route::get('/Sigmel/RolAdministrador/BandejaGestionInicial', [AdministradorController::class, 'mostrarVistaBandejaGestionInicial'])->name('bandejaGestionInicial');
// Vista: Cargue de Bases
Route::get('/Sigmel/RolAdministrador/CargueBases', [AdministradorController::class, 'mostrarVistaCargueBases'])->name('cargueBases');
// Vista: Reportes Módulos
Route::get('/Sigmel/RolAdministrador/ReportesModulos', [AdministradorController::class, 'mostrarVistaReportesModulos'])->name('reportesModulos');
// Vista: Reportes Bandejas
Route::get('/Sigmel/RolAdministrador/ReportesBandejas', [AdministradorController::class, 'mostrarVistaReportesBandejas'])->name('reportesBandejas');
// Vista: Gestionar Facturación
Route::get('/Sigmel/RolAdministrador/GestionarFacturacion', [AdministradorController::class, 'mostrarVistaGestionarFacturacion'])->name('gestionarFacturacion');
// Vista: Auditoria Grupos
Route::get('/Sigmel/RolAdministrador/AuditoriaGrupos', [AdministradorController::class, 'mostrarVistaAuditoriaGrupos'])->name('auditoriaGrupos');

/* INICIO SECCION: AQUI SE RENDERIZARÁN LAS RUTAS DE LOS DEMÁS ROLES: */

// 28/04/2023 
// Vista: Index Rol Ingenieria
Route::get('/Sigmel/RolIngenieria', [IngenieriaController::class, 'show'])->name('IndexIngenieria');
// Vista: Index Rol Administrador
Route::get('/Sigmel/RolAdministrador', [AdministradorController::class, 'show'])->name('IndexAdministrador');

/* FIN SECCION: AQUI SE RENDERIZARÁN LAS RUTAS DE LOS DEMÁS ROLES: */

Route::get('/Sigmel/pruebas', [ProbandoController::class, 'index']);
Route::post('/Sigmel/pruebas', [ProbandoController::class, 'generar'])->name('generarExcel');