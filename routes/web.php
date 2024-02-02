<?php

use App\Http\Controllers\Administrador\AdministradorController;
use App\Http\Controllers\Administrador\EntidadesController;
use App\Http\Controllers\Administrador\AccionesController;
use App\Http\Controllers\Administrador\BuscarEventoController;
use App\Http\Controllers\Administrador\ParametrizacionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Autenticacion\LoginController;
use App\Http\Controllers\Autenticacion\LogoutController;
use App\Http\Controllers\Coordinador\AdicionDxDTO;
use App\Http\Controllers\Coordinador\CalificacionPCLController;
use App\Http\Controllers\Coordinador\RecalificacionPCLController;
use App\Http\Controllers\Coordinador\CalificacionOrigenController;
use App\Http\Controllers\Coordinador\CalificacionNotifiController;
use App\Http\Controllers\Ingenieria\IngenieriaController;
use App\Http\Controllers\ProbandoController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Coordinador\CoordinadorController;
use App\Http\Controllers\Coordinador\SolicitudDocumentoSeguimientosPCLController;
use App\Http\Controllers\Coordinador\PronunciamientoPCLController;
use App\Http\Controllers\Coordinador\BandejaOrigenController;
use App\Http\Controllers\Coordinador\BandejaNotifiController;
use App\Http\Controllers\Coordinador\DeterminacionOrigenATEL;
use App\Http\Controllers\Coordinador\PronunciamientoOrigenController;
use App\Http\Controllers\Coordinador\BandejaJuntasController;
use App\Http\Controllers\Coordinador\CalificacionJuntasController;
use App\Http\Controllers\Coordinador\ControversiaJuntasController;
use App\Http\Controllers\Profesional\ProfesionalController;
use App\Http\Controllers\Analista\AnalistaController;
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

// Acción: Listado de Roles que le faltan por asignar dependiendo de la selección del usuario
Route::post('/listadoRolesXUsuario', [IngenieriaController::class, 'listadoRolesXUsuario']);
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
Route::post('/listarTiposColaborador', [IngenieriaController::class, 'listadoTiposColaborador']);
// Acción: Traer listado de tipos de identificación edición usuario para el selector de tipo de documento para la edición js
Route::post('/listadoTiposIdentificacionEditar', [IngenieriaController::class, 'listadoTiposIdentificacionEditar']);
// Acción: Traer listado de tipos de contratos edición usuario para el selector tipo de contratos para la edición js
Route::post('/listadotiposColaboradorEditar', [IngenieriaController::class, 'listadotiposColaboradorEditar']);
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

// 03/05/2023 - CRUD EQUIPOS DE TRABAJO
// Acción: Traer listado de lideres dependiendo de la selección del proceso
Route::post('/ListaLideresXProceso', [AdministradorController::class, 'ListaLideresXProceso']);
// Vista: Formulario para crear un nuevo equipo de trabajo
Route::get('/Sigmel/RolAdministrador/NuevoEquipoTrabajo', [AdministradorController::class, 'mostrarVistaNuevoEquipoTrabajo'])->name('crearEquipoTrabajo');
// Acción: Crear un nuevo equipo de trabajo
Route::post('/Sigmel/RolAdministrador/CrearNuevoEquipo', [AdministradorController::class, 'guardar_equipo_trabajo'])->name('CrearNuevoEquipo');
// Vista: Listar equipos de trabajo
Route::get('/Sigmel/RolAdministrador/listarEquiposTrabajo', [AdministradorController::class, 'mostrarVistaListarEquiposTrabajo'])->name('listarEquiposTrabajo');
// Vista: Formulario para editar un grupo de trabajo
Route::post('/Sigmel/RolAdministrador/EditarEquipoTrabajo', [AdministradorController::class, 'mostrarVistaEditarEquipoTrabajo'])->name('EditarEquipoTrabajo');
// Acción: Traer listado de lideres para edición grupo de trabajos para el selector de lideres para la edición js
Route::post('/listadoLideresEditar', [AdministradorController::class, 'listadoLideresEditar']);
// Acción: Traer listado de usuarios asignados y no asignados para construir el selector dual de usuarios asignar grupos 
Route::post('/listadoUsuariosAsignacion', [AdministradorController::class, 'listadoUsuariosAsignacion']);
// Acción: Guardar edición de grupo de trabajo
Route::post('/Sigmel/RolAdministrador/GuardarEdicionEquipoTrabajo', [AdministradorController::class, 'editar_equipo_trabajo'])->name('GuardarEdicionEquipoTrabajo');

// 04/05/2023

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

// 12-05-2023
// Vista: Edición de Sidebar - Navbar - Footer 
Route::get('/Sigmel/usuarios/EdicionPlantilla', [IngenieriaController::class, 'mostrarVistaEdicionPlantilla'])->name('edicionNavbarSidebarFooter');
// Acción: Editar Plantilla
Route::post('/Sigmel/usuarios/AplicarEdicionPlantilla', [IngenieriaController::class, 'aplicar_edicion_plantilla'])->name('AplicarEdicionPlantilla');

/* INICIO SECCION: AQUI SE RENDERIZARÁN LAS RUTAS DE LOS DEMÁS ROLES: */

// 28/04/2023 
// Vista: Index Rol Ingenieria
Route::get('/Sigmel/RolIngenieria', [IngenieriaController::class, 'show'])->name('IndexIngenieria');
// Vista: Index Rol Administrador
Route::get('/Sigmel/RolAdministrador', [AdministradorController::class, 'show'])->name('IndexAdministrador');
// Vista: Index Rol Coordinador 28/06/2023
Route::get('/Sigmel/RolCoordinador', [CoordinadorController::class, 'show'])->name('IndexCoordinador');
// Vista: Index Rol Profesional 15/12/2023
Route::get('/Sigmel/Profesional', [ProfesionalController::class, 'show'])->name('IndexProfesional');
// Vista: Index Rol Auxiliar 18/12/2023
Route::get('/Sigmel/Auxiliar', [ProfesionalController::class, 'show'])->name('IndexAuxiliar');
// Vista: Index Rol Analista 15/12/2023
Route::get('/Sigmel/Analista', [AnalistaController::class, 'show'])->name('IndexAnalista');

// 23/05/2023
// Vista: Gestión Inicial Nuevo
Route::get('/Sigmel/RolAdministrador/GestionInicialNuevo', [AdministradorController::class, 'mostrarVistaGestionInicialNuevo'])->name('gestionInicialNuevo');
// Acción: Rellenar los selectores del formulario acorde al parametro indicado
Route::post('/cargarselectores', [AdministradorController::class, 'cargueListadoSelectores']);
// Acción: Verficar que la columna Modulo_nuevo de la tabla sigmel_informacion_parametrizaciones_clientes este en si para permitir ejecutar la paramétrica
Route::post('/validacionParametricaEnSi', [AdministradorController::class, 'validacionParametricaEnSi']);


// Acción: Registrar evento
Route::post('/Sigmel/RolAdministrador/CreacionEvento', [AdministradorController::class, 'creacionEvento'])->name('creacionEvento');
// Acción: Consulta de Nro identificacion afiliado y fecha evento para saber si permite un registro de evento nuevo
Route::post('/consultaFechaNroIdent', [AdministradorController::class, 'ConsultaFechaNroIdent']);

// Acción: Consultar datos Información Afiliado acorde al numero de identificación para autocompletar los campos de
// información afiliado del formulario de creación de envento.
Route::post('/consultarInfoAfiliadoLlenar', [AdministradorController::class, 'llenarDatosInfoAfiliado']);
// Acción: Consultar datos Información Laboral acorde al numero de identificación para autocompletar los campos de
// información laboral del formulario de creación de envento.
Route::post('/consultarInfoLaboralLlenar', [AdministradorController::class, 'llenarDatosInfoLaboral']);
// Acción:Registrar Otra EMpresa en el modal de Empresas

// Acción: Cargar documentos en Gestion Inicial Nuevo
Route::post('/cargarDocumentos', [AdministradorController::class, 'cargaListadoDocumentosInicialNuevo'])->name('cargaDocumento');

// Acción: Descargar Documentos
Route::get('/descargar-archivo/{nombreArchivo}/{id_evento}', [AdministradorController::class, 'DescargarDocumentos']);

Route::post('/registrarOtraEmpresa', [AdministradorController::class, 'registrarOtraEmpresa']);
// Acción: Traer el listado de historicos de empresa dependiendo del numero de identificacion del afiliado
Route::post('/consultaHistoricoEmpresas', [AdministradorController::class, 'consultaHistoricoEmpresas']);

// Acción: Consulta del Id Evento para validar si ya se encuentra registrado el Id Evento
Route::post('/consultaIdEvento', [AdministradorController::class, 'ConsultaIDEvento']);
// Vista: Edicion del Id Evento
Route::post('/Sigmel/RolAdministrador/GestionInicialEdicion', [AdministradorController::class, 'mostrarVistaEdicionInicialNuevo'])->name('gestionInicialEdicion');
// Acción: Actualizar evento
Route::post('/Sigmel/RolAdministrador/ActualizarEvento', [AdministradorController::class, 'actualizarGestionInicial'])->name('actualizarEvento');

// 14/06/2023
// Vista: Buscar Evento
Route::get('/Sigmel/RolAdministrador/BusquedaEvento', [BuscarEventoController::class, 'mostrarVistaBuscarEvento'])->name('busquedaEvento');
// Acción Consultar evento 
Route::post('/consultaInformacionEvento', [BuscarEventoController::class, 'consultaInformacionEvento']);

// 28/06/2023
// Vista: Bandeja PCL Coordinador
Route::get('/Sigmel/RolCoordinador/BandejaPCL', [CoordinadorController::class, 'mostrarVistaBandejaPCL'])->name('bandejaPCL');
// Accion: Selectores Bandeja PCL
Route::post('/selectoresBandejaPCL', [CoordinadorController::class, 'cargueListadoSelectoresBandejaPCL']);
// Accion: Capturar data sin filtros
Route::post('/sinfiltrosBandejaPCL', [CoordinadorController::class, 'sinFiltroBandejaPCL']);
// Accion: Capturar data según los filtros
Route::post('/filtrosBandejaPCL', [CoordinadorController::class, 'filtroBandejaPCl']);
// Accion: Actualizar el profesional y redireccionar el servicio
Route::post('/actualizarProfesionalServicio', [CoordinadorController::class, 'actualizarBandejaPCL']);

// 14/07/2023
// Vista: Módulo Calificación PCL Coordinador
Route::post('/calificacionPCL', [CalificacionPCLController::class, 'mostrarVistaCalificacionPCL'])->name('calificacionPCL');
Route::get('/calificacionPCL', [CalificacionPCLController::class, 'mostrarVistaCalificacionPCL'])->name('calificacionPCL');
// Accion: Selectores Módulo calificación PCl
Route::post('/selectoresModuloCalificacionPCL', [CalificacionPCLController::class, 'cargueListadoSelectoresModuloCalifcacionPcl']);
// Accion: Insertar Califcación PCL
Route::post('/registrarCalificacionPCL', [CalificacionPCLController::class, 'guardarCalificacionPCL']);
// Acción: Traer listado de documentos solicitados para el selector de documentos solicitados (Modal Solicitud de Documentos Seguimientos)
Route::post('/CargarDatosSolicitados', [CalificacionPCLController::class, 'CargarDatosSolicitados']);
// Acción: Guardar Datos Listado de documentos solicitados
Route::post('/GuardarDocumentosSolicitados',[CalificacionPCLController::class, 'GuardarDocumentosSolicitados']);
// Acción: Cargar Datos Listado de documentos solicitados
Route::post('/CargarDocumentosSolicitados',[CalificacionPCLController::class, 'CargarDocumentosSolicitados']);
// Acción: Eliminar Fila (Cambiar a estado inactivo)
Route::post('/EliminarFila', [CalificacionPCLController::class, 'EliminarFila'])->name('EliminarFila');
// Acción: Insertar Agregar Seguimiento
Route::post('/registrarCausalSeguimiento', [CalificacionPCLController::class, 'guardarAgregarSeguimiento']);
// Acción: Capturar datos para el dataTable Historial de seguimientos
Route::post('/historialSeguimientoPCL', [CalificacionPCLController::class, 'historialSeguimientosPCL']);
// Acción: Capturar de datos para el formulario generar comunicado destinatario final
Route::post('/captuarDestinatario', [CalificacionPCLController::class, 'captuarDestinatariosPrincipal']);
// Acción Insertar comunicado
Route::post('/registrarComunicado', [CalificacionPCLController::class, 'guardarComunicado']);
// Acción: Capturar datos para el dataTable Comunicados
Route::post('/historialComunicadoPcl', [CalificacionPCLController::class, 'historialComunicadosPCL']);
// Acción: Abrir modal para editar comunicado
Route::post('/modalComunicado', [CalificacionPCLController::class, 'mostrarModalComunicadoPCL'])->name('modalComunicado');
// Acción: Actualizar comunicado
Route::post('/actualizarComunicado', [CalificacionPCLController::class, 'actualizarComunicado']);
// Acción: Generar pdf comunicado
Route::post('/generarPdf', [CalificacionPCLController::class, 'generarPdf'])->name('descargarPdf');
// Acción: Historia de Acciones del evento desde calificacion Pcl
Route::post('/consultarHistorialAcciones', [CalificacionPCLController::class, 'historialAcciones']);


// 01/08/2023
// SUBMÓDULO CALIFICACIÓN TÉCNICA PCL
// Acción: MOstrar vista Calificación Técnica PCL
Route::get('/CalficacionTecnicaPCL', [CalificacionPCLController::class, 'mostrarVistaCalificacionTecnicaPCL'])->name('CalficacionTecnicaPCL');
Route::post('/CalficacionTecnicaPCL', [CalificacionPCLController::class, 'mostrarVistaCalificacionTecnicaPCL'])->name('CalficacionTecnicaPCL');
// Accion: Selectores Módulo calificación PCl
Route::post('/selectoresCalificacionTecnicaPCL', [CalificacionPCLController::class, 'cargueListadoSelectoresCalifcacionTecnicaPcl']);
// Acción: Guardar Informacion Decreto, Dictamen, Relacion documentos, Fundamentos para la calificación de la perdida de capacidad laboral y ocupacional
Route::post('/guardarDecretoDictamenRelacionDocFunda', [CalificacionPCLController::class, 'guardarDecretoDicRelaDocFund']);
// Acción: Guardar registros Examenes e interconsultas
Route::post('/guardarExamenesInterconsultas', [CalificacionPCLController::class, 'guardarExamenesInterconsulta']);
// Acción: Eliminar registro de Examenes e interconsultar visualmente e inactiva en la DB
Route::post('/eliminarExamenesInterconsultas', [CalificacionPCLController::class, 'eliminarExamenInterconsulta']);
// Acción: Guardar registros Diagnosticos motivo de calificación
Route::post('/guardarDiagnosticosMotivoCalificacion', [CalificacionPCLController::class, 'guardarDiagnosticoMotivoCalificacion']);
// Acción: Eliminar registros Diagnosticos motivo de calificacion visualmente e inactiva en la DB
Route::post('/eliminarDiagnosticosMotivoCalificacion', [CalificacionPCLController::class, 'eliminarDiagnosticoMotivoCalificacion']);
// Acción: Guardar Deficiencia Agudeza Auditiva
Route::post('/guardarDeficienciaAgudezaAuditiva', [CalificacionPCLController::class, 'guardarDeficienciasAgudezaAuditivas']);
// Acción: Eliminar registros Agudeza Auditiva visualmente  e inactiva en la DB
Route::post('/eliminarAgudezasAuditivas', [CalificacionPCLController::class, 'eliminarAgudezaAuditiva']);
// Acción: Actualizar Dx Principal de Agudeza Auditiva
Route::post('/actualizarDxPrincipalAgudezaAuditiva', [CalificacionPCLController::class, 'actualizarDxPrincipalAgudezaAuditiva']);
// Acción: Actualizar Deficiencia Agudeza Auditiva
Route::post('/actualizarDeficienciaAgudezaAuditiva', [CalificacionPCLController::class, 'actualizarDeficienciasAgudezaAuditivas']);
// Acción: Consulta Campimetría por fila
Route::post('/ConsultaCampimetriaXFila', [CalificacionPCLController::class, 'ConsultaCampimetriaXFila']);
// Acción: Guardar Información Agudeza Visual
Route::post('/guardarAgudezaVisual', [CalificacionPCLController::class, 'guardarAgudezaVisual']);
// Acción: Traer información de Agudeza visual cuando se guarde la información
Route::post('/infoAgudezaVisual', [CalificacionPCLController::class, 'infoAgudezaVisual']);
// Acción: Guardar Información Agudeza Visual
Route::post('/actualizarAgudezaVisual', [CalificacionPCLController::class, 'actualizarAgudezaVisual']);
// Acción: Borrar Información Agudeza Visual
Route::post('/eliminarAgudezaVisual', [CalificacionPCLController::class, 'eliminarAgudezaVisual']);
// Acción: Actualizar DX Principal agudeza Visual
Route::post('/actualizarDxPrincipalAgudezasVisual', [CalificacionPCLController::class, 'actualizarDxPrincipalAgudezaVisual']);
// Acción: Traer listado de selectores para el calculo de DEFICIENCIA POR ALTERACIONES DE LOS SISTEMAS GENERALES
Route::post('/ListadoSelectoresDefiAlteraciones', [CalificacionPCLController::class, 'ListadoSelectoresDefiAlteraciones']);
// Acción: Consultar Deficiencia acorde al clase final y la tabla
Route::post('/consultaValorDeficiencia', [CalificacionPCLController::class, 'consultaValorDeficiencia']);
// Acción: Guardar Datos Listado de documentos solicitados
Route::post('/GuardarDeficienciaAlteraciones',[CalificacionPCLController::class, 'GuardarDeficienciaAlteraciones']);
// Acción: Eliminar la Deficiencia alteraciones en la visualmente e inactiva en la DB
Route::post('/eliminarDeficienciasAteraciones',[CalificacionPCLController::class, 'eliminarDeficienciaAteraciones']);
// Acción: Actualizar Dx Principal Deficiencias Alteraciones
Route::post('/actualizarDxPrincipalDeficienciaAlteraciones', [CalificacionPCLController::class, 'actualizarDxPrincipalDeficienciasAlteraciones']);
// Acción: Guardar y Actualizar Título II Valoración del Rol Laboral laboralmente Activo
Route::post('/guardarLaboralmenteActivos', [CalificacionPCLController::class, 'guardarLaboralmenteActivo']); 
// Acción: Guardar y Actualizar Título II Valoración del Rol Ocupacional
Route::post('/guardarRolOcupacionales', [CalificacionPCLController::class, 'guardarRolOcupacional']); 
// Acción: Guardar y Actualizar Libro II Calificación de las discapacidades (20%) y Libro III Calificación de minusvalías (30%)
Route::post('/guardarLibros2_3', [CalificacionPCLController::class, 'guardarLibro2_3']); 
// Acción: Guardar Comite Interdisciplinario
Route::post('/guardarcomitesinterdisciplinario', [CalificacionPCLController::class, 'guardarcomiteinterdisciplinario']); 
// Acción: Guardar Correspondecia
Route::post('/guardarcorrespondencias', [CalificacionPCLController::class, 'guardarcorrespondencia']); 
// Acción: Guardar Dictamen Pericial
Route::post('/guardardictamenesPericial', [CalificacionPCLController::class, 'guardardictamenPericial']); 
// Acción: Guardar deficiencias Decreto Cero
Route::post('/guardarDeficieciasDecretosCero', [CalificacionPCLController::class, 'guardarDeficieciasDecretoCero']);
// Acción: Eliminar deficiencias Decrecto creo visualmente e inactiva en la DB
Route::post('/eliminarDeficieciasDecretosCero', [CalificacionPCLController::class, 'eliminarDeficieciasDecretoCero']);
// Acción: Guardar deficiencias Decreto tres
Route::post('/guardarDeficieciasDecretosTres', [CalificacionPCLController::class, 'guardarDeficieciasDecretoTres']);
// Acción: Elimincar deficiencias Decreto tres visualmente e inactiva en la DB
Route::post('/eliminarDeficieciasDecretosTres', [CalificacionPCLController::class, 'eliminarDeficieciasDecretoTres']);
// Acción: Generar pdf Dictamen PCL 1507
Route::post('/generarPdfDictamenesPcl', [CalificacionPCLController::class, 'generarPdfDictamenPcl'])->name('descargar_Dictamen_PCL');
// Acción: Generar pdf Dictamen PCL 917
Route::post('/generarPdfDictamenesPcl917', [CalificacionPCLController::class, 'generarPdfDictamenPcl917'])->name('descargar_Dictamen_PCL917');
// Acción: Generar pdf Notificacion PCL numericas
Route::post('/generarPdfNotificacionesPcl', [CalificacionPCLController::class, 'generarPdfNotificacionPcl'])->name('descargar_Notificacion_PCL');
// Acción: Generar pdf Dictamen PCL Cero
Route::post('/generarPdfDictamenesPclCero', [CalificacionPCLController::class, 'generarPdfDictamenPclCero'])->name('descargar_Dictamen_PCLCero');
// Acción: Generar pdf Notificacion PCL Cero    
Route::post('/generarPdfNotificacionesPclCero', [CalificacionPCLController::class, 'generarPdfNotificacionPclCero'])->name('descargar_Notificacion_PCLCero');

// 02/10/2023
// SUBMÓDULO RECALIFICACIÓN PCL
// Acción: Mostrar vista Recalificación PCL
Route::get('/recalificacionPCL',[RecalificacionPCLController::class, 'mostrarVistaRecalificacionPCL'])->name('recalificacionPCL');
Route::post('/recalificacionPCL',[RecalificacionPCLController::class, 'mostrarVistaRecalificacionPCL'])->name('recalificacionPCL');
// Acción: Llenado de selectores recalificacion y revision pension
Route::post('/selectoresRecalificacionPCL', [RecalificacionPCLController::class, 'cargueListadoSelectoresRecalificacionPcl']);
// Acción: Guardar Informacion Decreto, Dictamen, Relacion documentos, Fundamentos para la calificación de la perdida de capacidad laboral y ocupacional Recalificacion
//Route::get('/guardarDecretoDictamenRelacionDocFundaRe', [RecalificacionPCLController::class, 'guardarDecretoDicRelaDocFundRe']);
// Acción: Guardar Informacion Decreto, Dictamen, Relacion documentos, Fundamentos para la calificación de la perdida de capacidad laboral y ocupacional Recalificacion
Route::post('/guardarDecretoDictamenRelacionDocFundaRe', [RecalificacionPCLController::class, 'guardarDecretoDicRelaDocFundRe']);
// Acción: Guardar registros Examenes e interconsultas
Route::post('/guardarExamenesInterconsultasRe', [RecalificacionPCLController::class, 'guardarExamenesInterconsultaRe']);
// Acción: Eliminar registro de Examenes e interconsultar visualmente e inactiva en la DB
Route::post('/eliminarExamenesInterconsultasRe', [RecalificacionPCLController::class, 'eliminarExamenInterconsultaRe']);
// Acción: Guardar registros Diagnosticos motivo de calificación
Route::post('/guardarDiagnosticosMotivoCalificacionRe', [RecalificacionPCLController::class, 'guardarDiagnosticoMotivoCalificacionRe']);
// Acción: Eliminar registros Diagnosticos motivo de calificacion visualmente e inactiva en la DB
Route::post('/eliminarDiagnosticosMotivoCalificacionRe', [RecalificacionPCLController::class, 'eliminarDiagnosticoMotivoCalificacionRe']);
// Acción: Traer listado de selectores para el calculo de DEFICIENCIA POR ALTERACIONES DE LOS SISTEMAS GENERALES
Route::post('/ListadoSelectoresDefiAlteracionesRe', [RecalificacionPCLController::class, 'ListadoSelectoresDefiAlteracionesRe']);
// Acción: Consultar Deficiencia acorde al clase final y la tabla
Route::post('/consultaValorDeficienciaRe', [RecalificacionPCLController::class, 'consultaValorDeficienciaRe']);
// Acción: Guardar Datos Deficiencias por alteraciones
Route::post('/GuardarDeficienciaAlteracionesRe',[RecalificacionPCLController::class, 'GuardarDeficienciaAlteracionesRe']);
// Acción: Eliminar la Deficiencia alteraciones en la visualmente e inactiva en la DB
Route::post('/eliminarDeficienciasAteracionesRe',[RecalificacionPCLController::class, 'eliminarDeficienciaAteracionesRe']);
// Acción: Actualizar Dx Principal Deficiencias Alteraciones
Route::post('/actualizarDxPrincipalDeficienciaAlteracionesRe', [RecalificacionPCLController::class, 'actualizarDxPrincipalDeficienciasAlteracionesRe']);
// Acción: Guardar Deficiencia Agudeza Auditiva
Route::post('/guardarDeficienciaAgudezaAuditivaRe', [RecalificacionPCLController::class, 'guardarDeficienciasAgudezaAuditivasRe']);
// Acción: Eliminar registros Agudeza Auditiva visualmente  e inactiva en la DB
Route::post('/eliminarAgudezasAuditivasRe', [RecalificacionPCLController::class, 'eliminarAgudezaAuditivaRe']);
// Acción: Actualizar Dx Principal de Agudeza Auditiva
Route::post('/actualizarDxPrincipalAgudezaAuditivaRe', [RecalificacionPCLController::class, 'actualizarDxPrincipalAgudezaAuditivaRe']);
// Acción: Consulta Campimetría por fila
Route::post('/ConsultaCampimetriaXFilaRe', [RecalificacionPCLController::class, 'ConsultaCampimetriaXFilaRe']);
// Acción: Guardar Información Agudeza Visual
Route::post('/guardarAgudezaVisualRe', [RecalificacionPCLController::class, 'guardarAgudezaVisualRe']);
// Acción: Traer información de Agudeza visual cuando se guarde la información
Route::post('/infoAgudezaVisualRe', [RecalificacionPCLController::class, 'infoAgudezaVisualRe']);
// Acción: Actualizar Información Agudeza Visual
Route::post('/actualizarAgudezaVisualRe', [RecalificacionPCLController::class, 'actualizarAgudezaVisualRe']);
// Acción: Borrar Información Agudeza Visual
Route::post('/eliminarAgudezaVisualRe', [RecalificacionPCLController::class, 'eliminarAgudezaVisualRe']);
// Acción: Actualizar DX Principal agudeza Visual
Route::post('/actualizarDxPrincipalAgudezasVisualRe', [RecalificacionPCLController::class, 'actualizarDxPrincipalAgudezaVisualRe']);
// Acción: Guardar y Actualizar Título II Valoración del Rol Laboral laboralmente Activo
Route::post('/guardarLaboralmenteActivosRe', [RecalificacionPCLController::class, 'guardarLaboralmenteActivoRe']); 
// Acción: Guardar y Actualizar Título II Valoración del Rol Ocupacional
Route::post('/guardarRolOcupacionalesRe', [RecalificacionPCLController::class, 'guardarRolOcupacionalRe']);
// Acción: Guardar y Actualizar Libro II Calificación de las discapacidades (20%) y Libro III Calificación de minusvalías (30%)
Route::post('/guardarLibros2_3Re', [RecalificacionPCLController::class, 'guardarLibro2_3Re']);
// Acción: Guardar deficiencias Decreto Cero
Route::post('/guardarDeficieciasDecretosCeroRe', [RecalificacionPCLController::class, 'guardarDeficieciasDecretoCeroRe']);
// Acción: Eliminar deficiencias Decrecto creo visualmente e inactiva en la DB
Route::post('/eliminarDeficieciasDecretosCeroRe', [RecalificacionPCLController::class, 'eliminarDeficieciasDecretoCeroRe']);
// Acción: Guardar deficiencias Decreto tres
Route::post('/guardarDeficieciasDecretosTresRe', [RecalificacionPCLController::class, 'guardarDeficieciasDecretoTresRe']);
// Acción: Elimincar deficiencias Decreto tres visualmente e inactiva en la DB
Route::post('/eliminarDeficieciasDecretosTresRe', [RecalificacionPCLController::class, 'eliminarDeficieciasDecretoTresRe']);
// Acción: Guardar Comite Interdisciplinario
Route::post('/guardarcomitesinterdisciplinarioRe', [RecalificacionPCLController::class, 'guardarcomiteinterdisciplinarioRe']); 
// Acción: Guardar Correspondecia
Route::post('/guardarcorrespondenciasRe', [RecalificacionPCLController::class, 'guardarcorrespondenciaRe']); 
// Acción: Guardar Dictamen Pericial
Route::post('/guardardictamenesPericialRe', [RecalificacionPCLController::class, 'guardardictamenPericialRe']); 
// Acción: Generar pdf Dictamen PCL 1507
Route::post('/generarPdfDictamenesPclRe', [RecalificacionPCLController::class, 'generarPdfDictamenPclRe'])->name('descargar_Dictamen_PCLRe');
// Acción: Generar pdf Dictamen PCL 917
Route::post('/generarPdfDictamenesPcl917Re', [RecalificacionPCLController::class, 'generarPdfDictamenPcl917Re'])->name('descargar_Dictamen_PCL917Re');
// Acción: Generar pdf Notificacion PCL numericas
Route::post('/generarPdfNotificacionesPclRe', [RecalificacionPCLController::class, 'generarPdfNotificacionPclRe'])->name('descargar_Notificacion_PCLRe');
// Acción: Generar pdf Dictamen PCL Cero
Route::post('/generarPdfDictamenesPclCeroRe', [RecalificacionPCLController::class, 'generarPdfDictamenPclCeroRe'])->name('descargar_Dictamen_PCLCeroRe');
// Acción: Generar pdf Notificacion PCL Cero    
Route::post('/generarPdfNotificacionesPclCeroRe', [RecalificacionPCLController::class, 'generarPdfNotificacionPclCeroRe'])->name('descargar_Notificacion_PCLCeroRe');


Route::get  ('/Sigmel/RolAdministrador/ListarClientes', [AdministradorController::class, 'mostrarVistaListarClientes'])->name('listarClientes');

// Acción: Traer el listado de historial de acciones del evento
Route::post('/consultaHistorialAcciones', [AdministradorController::class, 'consultaHistorialAcciones']);
// Acción: Traer la información de los documentos acorde al id evento: Vista Buscador de Eventos (Modal Formulario Nuevo Servicio)
Route::post('/cargueDocumentosXEvento', [AdministradorController::class, 'cargueDocumentosXEvento']);
// Acción: Traer el listado de profesionales acorde al evento
Route::post('/ProfesionalesXProceso', [BuscarEventoController::class, 'ProfesionalesXProceso']);
// Acción: Crear un nuevo servicio a partir del Evento
Route::post('/crearNuevoServicio', [BuscarEventoController::class, 'crearNuevoServicio']);
// Acción: Crear un nuevo proceso a partir del Evento
Route::post('/crearNuevoProceso', [BuscarEventoController::class, 'crearNuevoProceso']);
// Acción: Mantener datos de búsqueda del formulario
Route::post('/mantenerDatosBusquedaEvento', [BuscarEventoController::class, 'mantenerDatosBusquedaEvento']);

// 28/08/2023 - CRUD ENTIDADES
// Vista: Formulario para crear una entidad
Route::get('/Sigmel/NuevoEntidad', [EntidadesController::class, 'mostrarVistaNuevoEntidad'])->name('crearEntidades');
// Vista: Formulario para editar una entidad
Route::post('/Sigmel/EditarEntidad', [EntidadesController::class, 'mostrarVistaEditarEntidad'])->name('EditarEntidades');
// Vista: Listar entidades
Route::get('/Sigmel/listarEntidades', [EntidadesController::class, 'mostrarVistaListarEntidades'])->name('listarEntidades');
// Accion: Selectores Módulo Entidades
Route::post('/selectoresEntidad', [EntidadesController::class, 'cargueListadoSelectoresEntidad']);
// Acción: Crear un nuevo entidad
Route::post('/Sigmel/CrearNuevoEntidad', [EntidadesController::class, 'guardar_entidad'])->name('CrearNuevoEntidad');
// Vista: formulario para editar la información de una identidad
Route::post('/Sigmel/Entidad/editarEntidad', [EntidadesController::class, 'mostrarVistaEditarEntidad'])->name('EditarEntidad');
// Acción: Actualización de la información de entidad
Route::post('/Sigmel/Entidad/actualizarEntidad', [EntidadesController::class, 'actualizarEntidad'])->name('ActualizacionEntidad');

// 01/09/2023
// Vista: Módulo Pronunciamiento PCL
Route::post('/calificacionPCL/pronunciamiento', [PronunciamientoPCLController::class, 'mostrarVistaPronunciamiento'])->name('pronunciamientoPCL');
Route::get('/calificacionPCL/pronunciamiento', [PronunciamientoPCLController::class, 'mostrarVistaPronunciamiento'])->name('pronunciamientoPCL');
// Accion: Selectores Módulo pronunciamiento
Route::post('/selectoresPronunciamiento', [PronunciamientoPCLController::class, 'cargueListadoSelectoresPronunciamiento']);
// Acción: Guardar Informacion Servicio Pronunciamiento
Route::post('/guardarInfoServiPronuncia', [PronunciamientoPCLController::class, 'guardarInfoServiPronuncia']);
// Ver documento Pronunciamiento
//Route::get('/VerDocumentoPronuncia', [PronunciamientoPCLController::class, 'VerDocumentoPronuncia']);
Route::get('/VerDocumentoPronuncia', [PronunciamientoPCLController::class, 'VerDocumentoPronuncia'])->name('VerDocumentoPronuncia');

//13/09/2023
//Vista: Bandeja Origen Coordinador
Route::get('/Sigmel/RolCoordinador/BandejaOrigen', [BandejaOrigenController::class, 'mostrarVistaBandejaOrigen'])->name('bandejaOrigen');
// Accion: Selectores Bandeja Origen
Route::post('/selectoresBandejaOrigen', [BandejaOrigenController::class, 'cargueListadoSelectoresBandejaOrigen']);
// Accion: Capturar data sin filtros
Route::post('/sinfiltrosBandejaOrigen', [BandejaOrigenController::class, 'sinFiltroBandejaOrigen']);
// Accion: Capturar data según los filtros
Route::post('/filtrosBandejaOrigen', [BandejaOrigenController::class, 'filtrosBandejaOrigen']);
// Accion: Actualizar el profesional y redireccionar el servicio
Route::post('/actualizarProfesionalServicioOrigen', [BandejaOrigenController::class, 'actualizarBandejaOrigen']);

// 14/09/2023
// Vista: Módulo Calificación Origen Coordinador
Route::get('/calificacionOrigen', [CalificacionOrigenController::class, 'mostrarVistaCalificacionOrigen'])->name('calificacionOrigen');
Route::post('/calificacionOrigen', [CalificacionOrigenController::class, 'mostrarVistaCalificacionOrigen'])->name('calificacionOrigen');
// Accion: Insertar Califcación Origen
Route::post('/registrarCalificacionOrigen', [CalificacionOrigenController::class, 'guardarCalificacionOrigen']);
// Accion: Selectores Módulo Origen
Route::post('/selectoresOrigenAtel', [CalificacionOrigenController::class, 'cargueListadoSelectoresOrigenAtel']);
// Acción: Guardar Documento Sugerido
Route::post('/GuardarDocumentosSeguimiento', [CalificacionOrigenController::class, 'GuardarDocumentosSeguimiento']);
// Acción: Eliminar Fila (Cambiar a estado inactivo)
Route::post('/EliminarFilaSeguimiento', [CalificacionOrigenController::class, 'EliminarFilaSeguimiento'])->name('EliminarFilaSeguimiento');
// Acción: Capturar de datos para el formulario generar comunicado destinatario final en Origen ATEL
Route::post('/captuarDestinatarioOrigen', [CalificacionOrigenController::class, 'captuarDestinatariosPrincipalOrigen']);
// Acción Insertar comunicado
Route::post('/registrarComunicadoOrigen', [CalificacionOrigenController::class, 'guardarComunicadoOrigen']);
// Acción: Capturar datos para el dataTable Comunicados Orogen
Route::post('/historialComunicadoOrigen', [CalificacionOrigenController::class, 'historialComunicadosOrigen']);
// Acción: Abrir modal para editar comunicado
Route::post('/modalComunicadoOrigen', [CalificacionOrigenController::class, 'mostrarModalComunicadoOrigen'])->name('modalComunicadoOrigen');
// Acción: Guardar Seguimientos Sugerido
Route::post('/GuardarHistorialSeguiOrigen', [CalificacionOrigenController::class, 'GuardarHistorialSeguiOrigen']);
// Acción: Actualizar comunicado
Route::post('/actualizarComunicadoOrigen', [CalificacionOrigenController::class, 'actualizarComunicadoOrigen']);
// Acción: Eliminar Fila (Cambiar a estado inactivo) Historial Seguimiento
Route::post('/EliminarFilaHistoSeguimiento', [CalificacionOrigenController::class, 'EliminarFilaHistoSeguimiento'])->name('EliminarFilaHistoSeguimiento');
// Acción: Mostrar vista Determinación del Origen DTO ATEL
Route::get('/determinacionOrigenATEL', [DeterminacionOrigenATEL::class, 'mostrarVistaDtoATEL'])->name('determinacionOrigenATEL');
Route::post('/determinacionOrigenATEL', [DeterminacionOrigenATEL::class, 'mostrarVistaDtoATEL'])->name('determinacionOrigenATEL');
// Accion: Selectores Submodulo DTO ATEL
Route::post('/cargueListadoSelectoresDTOATEL', [DeterminacionOrigenATEL::class, 'cargueListadoSelectoresDTOATEL']);
// Acción: Guardar información DTO ATEL
Route::post('/GuardaroActualizarInfoDTOTAEL', [DeterminacionOrigenATEL::class, 'GuardaroActualizarInfoDTOTAEL']);
// Acción: Eliminar registro de Examenes e interconsultar visualmente e inactiva en la DB
Route::post('/eliminarExamenesInterconsultasDTOATEL', [DeterminacionOrigenATEL::class, 'eliminarExamenInterconsulta']);
// Acción: Eliminar registros Diagnosticos motivo de calificacion visualmente e inactiva en la DB
Route::post('/eliminarDiagnosticosMotivoCalificacionDTOATEL', [DeterminacionOrigenATEL::class, 'eliminarDiagnosticoMotivoCalificacion']);
// Acción: Marcar o Desmarcar Dx Principal en Diagnósticos motivo calificación DTO ATEL
Route::post('/actualizarDxPrincipalDTOATEL', [DeterminacionOrigenATEL::class, 'actualizarDxPrincipalDTOATEL']);
// Acción: Guardar información DTO ATEL
Route::post('/GuardaroActualizarInfoDTOTAEL', [DeterminacionOrigenATEL::class, 'GuardaroActualizarInfoDTOTAEL']);
// // Acción: Eliminar registro de Examenes e interconsultar visualmente e inactiva en la DB
// Route::post('/eliminarExamenesInterconsultasDTOATEL', [DeterminacionOrigenATEL::class, 'eliminarExamenInterconsulta']);
// Acción: Eliminar registros Diagnosticos motivo de calificacion visualmente e inactiva en la DB
Route::post('/eliminarDiagnosticosMotivoCalificacionDTOATEL', [DeterminacionOrigenATEL::class, 'eliminarDiagnosticoMotivoCalificacion']);
// Acción: Guardar Comite InterdisciplinarioDTO
Route::post('/guardarcomitesinterdisciplinarioDTO', [DeterminacionOrigenATEL::class, 'guardarcomiteinterdisciplinarioDto']); 
// Acción: Guardar CorrespondeciaRTO
Route::post('/guardarcorrespondenciaDTO', [DeterminacionOrigenATEL::class, 'guardarcorrespondenciaDto']);
// 02/10/2023
// Vista: Módulo Pronunciamiento Origen
Route::post('/calificacionOrigen/pronunciamientoOrigen', [PronunciamientoOrigenController::class, 'mostrarVistaPronunciamientoOrigen'])->name('pronunciamientoOrigen');
Route::get('/calificacionOrigen/pronunciamientoOrigen', [PronunciamientoOrigenController::class, 'mostrarVistaPronunciamientoOrigen'])->name('pronunciamientoOrigen');
// Accion: Selectores Módulo pronunciamiento Origen
Route::post('/selectoresPronunciamientoOrigen', [PronunciamientoOrigenController::class, 'cargueListadoSelectoresPronunciamientoOrigen']);
// Acción: Guardar Informacion Servicio Pronunciamiento Origen
Route::post('/guardarInfoServiPronunciaOrigen', [PronunciamientoOrigenController::class, 'guardarInfoServiPronunciaOrigen']);
//04/10/2023
//Vista: Bandeja Noti Coordinador
Route::get('/Sigmel/RolCoordinador/BandejaNotifi', [BandejaNotifiController::class, 'mostrarVistaBandejaNotifi'])->name('bandejaNotifi');
// Accion: Selectores Bandeja Origen
Route::post('/selectoresBandejaNotifi', [BandejaNotifiController::class, 'cargueListadoSelectoresBandejaNotifi']);
// Accion: Capturar data sin filtros
Route::post('/sinfiltrosBandejaNotifi', [BandejaNotifiController::class, 'sinFiltroBandejaNotifi']);
// Accion: Capturar data según los filtros
Route::post('/filtrosBandejaNotifi', [BandejaNotifiController::class, 'filtrosBandejaNotifi']);
// Accion: Actualizar el profesional y redireccionar el servicio
Route::post('/actualizarProfesionalServicioNotifi', [BandejaNotifiController::class, 'actualizarBandejaNotifi']);
// 05/10/2023
// Vista: Módulo Calificación Noti Coordinador
Route::get('/calificacionNotifi', [CalificacionNotifiController::class, 'mostrarVistaCalificacionNotifi'])->name('calificacionNotifi');
Route::post('/calificacionNotifi', [CalificacionNotifiController::class, 'mostrarVistaCalificacionNotifi'])->name('calificacionNotifi');
// Accion: Insertar Califcación Notificacion
Route::post('/registrarCalificacionNotifi', [CalificacionNotifiController::class, 'guardarCalificacionNotifi']);
//13/10/2023
//Vista: Bandeja Juntas Coordinador
Route::get('/Sigmel/RolCoordinador/BandejaJuntas', [BandejaJuntasController::class, 'mostrarVistaBandejaJuntas'])->name('bandejaJuntas');
// Accion: Selectores Bandeja Juntas
Route::post('/selectoresBandejaJuntas', [BandejaJuntasController::class, 'cargueListadoSelectoresBandejaJuntas']);
// Accion: Capturar data sin filtros
Route::post('/sinfiltrosBandejaJuntas', [BandejaJuntasController::class, 'sinFiltroBandejaJuntas']);
// Accion: Capturar data según los filtros
Route::post('/filtrosBandejaJuntas', [BandejaJuntasController::class, 'filtrosBandejaJuntas']);
// Accion: Actualizar el profesional y redireccionar el servicio
Route::post('/actualizarProfesionalServicioJuntas', [BandejaJuntasController::class, 'actualizarBandejaJuntas']);
//17/10/2023
// Vista: Módulo Calificación Juntas Coordinador
Route::get('/calificacionJuntas', [CalificacionJuntasController::class, 'mostrarVistaCalificacionJuntas'])->name('calificacionJuntas');
Route::post('/calificacionJuntas', [CalificacionJuntasController::class, 'mostrarVistaCalificacionJuntas'])->name('calificacionJuntas');
// Accion: Insertar Califcación Juntas
Route::post('/registrarCalificacionJuntas', [CalificacionJuntasController::class, 'guardarCalificacionJuntas']);
// Accion: Selectores Módulo Juntas
Route::post('/selectoresJuntas', [CalificacionJuntasController::class, 'cargueListadoSelectoresJuntas']);
// Accion: Registrar Datos de controvertido
Route::post('/registrarControvertido', [CalificacionJuntasController::class, 'guardarControvertidoJuntas']);
// Accion: Registrar Datos de controversia
Route::post('/registrarControversia', [CalificacionJuntasController::class, 'guardarControversiaJuntas']);
// Accion: Registrar Datos pagos honorarios
Route::post('/registrarPagoJuntas', [CalificacionJuntasController::class, 'guardarPagosJuntas']);
// Acción: Guardar Datos Listado de documentos solicitados
Route::post('/GuardarDocumentosSolicitadosJuntas',[CalificacionJuntasController::class, 'GuardarDocumentosSolicitadosJuntas']);
// Acción: Capturar de datos para el formulario generar comunicado destinatario final en Juntas
Route::post('/captuarDestinatarioJuntas', [CalificacionJuntasController::class, 'captuarDestinatariosPrincipalJuntas']);
// Acción Insertar comunicado
Route::post('/registrarComunicadoJuntas', [CalificacionJuntasController::class, 'guardarComunicadoJuntas']);
// Acción: Capturar datos para el dataTable Comunicados Orogen
Route::post('/historialComunicadoJuntas', [CalificacionJuntasController::class, 'historialComunicadosJuntas']);
// Acción: Abrir modal para editar comunicado
Route::post('/modalComunicadoJuntas', [CalificacionJuntasController::class, 'mostrarModalComunicadoJuntas'])->name('modalComunicadoJuntas');
// Acción: Actualizar comunicado
Route::post('/actualizarComunicadoJuntas', [CalificacionJuntasController::class, 'actualizarComunicadoJuntas']);
// Acción: Insertar Agregar Seguimiento
Route::post('/registrarCausalSeguimientoJuntas', [CalificacionJuntasController::class, 'guardarAgregarSeguimientoJuntas']);

//18/11/2023
// Vista: Módulo Controversia Juntas
Route::post('/calificacionJuntas/controversiaJuntas', [ControversiaJuntasController::class, 'mostrarVistaPronunciamientoJuntas'])->name('controversiaJuntas');
Route::get('/calificacionJuntas/controversiaJuntas', [ControversiaJuntasController::class, 'mostrarVistaPronunciamientoJuntas'])->name('controversiaJuntas');
// Accion: Selectores Módulo Controversia Juntas
Route::post('/selectoresJuntasControversia', [ControversiaJuntasController::class, 'cargueListadoSelectoresJuntasControversia']);
// Acción: Guardar Informacion Servicio Controversia Juntas
Route::post('/guardarInfoServiPronunciaJuntas', [ControversiaJuntasController::class, 'guardarInfoServiPronunciaJuntas']);
// Accion: Registrar Datos de controvertido Modulo Juntas
Route::post('/registrarControvertidoJuntas', [ControversiaJuntasController::class, 'guardarControvertidoMoJuntas']);
// Accion: Registrar Datos de emitido JRCI
Route::post('/registrarEmitidoJrci', [ControversiaJuntasController::class, 'guardarEmitidoMoJrci']);
// Accion: Registrar Datos de revision JRCI
Route::post('/registrarRevisionJrci', [ControversiaJuntasController::class, 'guardarRevisionMoJrci']);
// Accion: Registrar Datos de revision JRCI
Route::post('/registrarRecursoJrci', [ControversiaJuntasController::class, 'guardarRecursoMoJrci']);
// Accion: Registrar Datos de partes JRCI
Route::post('/registrarPartesJrci', [ControversiaJuntasController::class, 'guardarParteMoJrci']);
// Accion: Registrar Datos de partes Controversia JRCI
Route::post('/registrarPartesControJrci', [ControversiaJuntasController::class, 'guardarParteControMoJrci']);
// Accion: Registrar Datos de partes reposicion JRCI
Route::post('/registrarDatosRepoJrci', [ControversiaJuntasController::class, 'guardarDatosRepoMoJrci']);
// Accion: Registrar Revisión ante recurso de reposición de la Junta Regional
Route::post('/registrarReposicionJrci', [ControversiaJuntasController::class, 'guardarRegiRepoMoJrci']);
// Accion: Registrar Apelación de recurso ante la JNCI
Route::post('/registrarApelaJrci', [ControversiaJuntasController::class, 'guardarRegiApelaMoJrci']);
// Accion: Registrar Acta Ejecutoria emitida por JRCI
Route::post('/registrarActaJrci', [ControversiaJuntasController::class, 'guardarRegiActaMoJrci']);
// Accion: Registrar Datos de emitido JRCI
Route::post('/registrarEmitidoJnci', [ControversiaJuntasController::class, 'guardarEmitidoMoJnci']);

// Acción: Eliminar registros Diagnosticos motivo de calificacion visualmente e inactiva en la DB
Route::post('/eliminarDiagnosticosMotivoCalificacionContro', [ControversiaJuntasController::class, 'eliminarDiagnosticoMotivoCalificacionContro']);
// Acción: Guardar Comite InterdisciplinarioDTO
Route::post('/guardarcomitesinterdisciplinarioJuntas', [ControversiaJuntasController::class, 'guardarcomiteinterdisciplinarioJuntas']); 
// Acción: Guardar CorrespondeciaRTO
Route::post('/guardarcorrespondenciasJuntas', [ControversiaJuntasController::class, 'guardarcorrespondenciaJuntas']);

// Acción: Mostrar vista ADICIÓN DX DTO
Route::get('/adicionDxDtoOrigen', [AdicionDxDTO::class, 'mostrarVistaAdicionDxDTO'])->name('adicionDxDtoOrigen');
Route::post('/adicionDxDtoOrigen', [AdicionDxDTO::class, 'mostrarVistaAdicionDxDTO'])->name('adicionDxDtoOrigen');
// Accion: Selectores Submodulo Adición DX DTO
Route::post('/cargueListadoSelectoresAdicionDx', [AdicionDxDTO::class, 'cargueListadoSelectoresAdicionDx']);
// Acción: Insertar o Editar Adicion DX
Route::post('/GuardaroActualizarInfoAdicionDX', [AdicionDxDTO::class, 'GuardaroActualizarInfoAdicionDX']);
// Acción: Guardar Comite InterdisciplinarioDTO
Route::post('/guardarcomitesinterdisciplinarioADX', [AdicionDxDTO::class, 'guardarcomiteinterdisciplinarioAdx']); 
// Acción: Guardar CorrespondeciaRTO
Route::post('/guardarcorrespondenciaADX', [AdicionDxDTO::class, 'guardarcorrespondenciaAdx']);

// 17/10/2023 - CRUD ACCIONES
// Vista: Formulario para crear una nueva acción
Route::get('/Sigmel/NuevaAccion', [AccionesController::class, 'mostrarVistaNuevaAccion'])->name('crearNuevaAccion');
// Accion: Selectores Módulo Acciones
Route::post('/selectoresAcciones', [AccionesController::class, 'cargueListadoSelectoresAcciones']);
// Acción: Crear una nueva acción
Route::post('/CrearNuevaAccion', [AccionesController::class, 'CrearNuevaAccion']);
// Vista: Formulario para listar acciones
Route::get('/Sigmel/ListarAcciones', [AccionesController::class, 'mostrarVistaListarAcciones'])->name('listarAcciones');
// Acción:Traer datos de la acción a editar
Route::post('/Sigmel/InformacionAccionEditar', [AccionesController::class, 'InformacionAccionEditar'])->name('InformacionAccionEditar');
// Acción: Actualización de la información de la Acción
Route::post('/ActualizarAccion', [AccionesController::class, 'ActualizarAccion'])->name('ActualizarAccion');

// Vista: Formulario para registrar Cliente
Route::get('/Sigmel/RolAdministrador/RegistroCliente', [AdministradorController::class, 'mostrarVistaCrearCliente'])->name('registrarCliente');
// Acción: Registrar el cliente
Route::post('/CrearCliente', [AdministradorController::class, 'guardar_cliente']);
// Vista: Traer lista de clientes
Route::get  ('/Sigmel/RolAdministrador/ListarClientes', [AdministradorController::class, 'mostrarVistaListarClientes'])->name('listarClientes');
// Acción: Traer información del cliente
Route::post('/Sigmel/InformacionClienteEditar', [AdministradorController::class, 'InformacionClienteEditar'])->name('InformacionClienteEditar');
// Acción: Eliminar fila dinámica en tabla de sucursales modal edicion cliente
Route::post('/eliminarSucursalCliente', [AdministradorController::class, 'eliminarSucursalCliente']);
// Acción: Eliminar fila dinámica en tabla de ans modal edicion cliente
Route::post('/eliminarAnsCliente', [AdministradorController::class, 'eliminarAnsCliente']);
// Acción: Actualizar información del cliente.
Route::post('/ActualizarCliente', [AdministradorController::class, 'actualizar_cliente']);

// Acción: Eliminar fila dinámica en tabla de firmas cliente modal edicion cliente
Route::post('/eliminarFirmaCliente', [AdministradorController::class, 'eliminarFirmaCliente']);
// Acción: Eliminar fila dinámica en tabla de firmas proveedor modal edicion cliente
Route::post('/eliminarFirmaProveedor', [AdministradorController::class, 'eliminarFirmaProveedor']);
// Guardar o Actualizar Firma Cliente
Route::post('/GuardarActualizarFirmasCliente', [AdministradorController::class, 'GuardarActualizarFirmasCliente']);
// Guardar o Actualizar Firma Proveedor
Route::post('/GuardarActualizarFirmasProveedor', [AdministradorController::class, 'GuardarActualizarFirmasProveedor']);

// 08/11/2023: Parametrización
Route::post('/Sigmel/RolAdministrador/Parametrizaciones', [ParametrizacionController::class, 'mostrarVistaParametrizacion'])->name('mostrarVistaParametrizacion');
// Acción: Traer datos para la vista de parametrización
Route::post('/CargueSelectoresParametrizar', [ParametrizacionController::class, 'CargueSelectoresParametrizar']);

// Acción: Envío de parametrizaciones del proceso origen atel
Route::post('/EnvioParametrizacionOrigenAtel', [ParametrizacionController::class, 'EnvioParametrizacionOrigenAtel']);
// Acción: Actualizar la parametrización del proceso Origen Atel
Route::post('/ActualizarParametrizacionOrigenAtel', [ParametrizacionController::class, 'ActualizarParametrizacionOrigenAtel']);
// Acción: Envío de parametrizaciones del proceso calificación pcl
Route::post('/EnvioParametrizacionCalificacionPcl', [ParametrizacionController::class, 'EnvioParametrizacionCalificacionPcl']);
// Acción: Actualizar la parametrización del proceso calificación pcl
Route::post('/ActualizarParametrizacionCalificacionPcl', [ParametrizacionController::class, 'ActualizarParametrizacionCalificacionPcl']);
// Acción: Envío de parametrizaciones del proceso juntas
Route::post('/EnvioParametrizacionJuntas', [ParametrizacionController::class, 'EnvioParametrizacionJuntas']);
// Acción: Actualizar la parametrización del proceso juntas
Route::post('/ActualizarParametrizacionJuntas', [ParametrizacionController::class, 'ActualizarParametrizacionJuntas']);


/* DESCARGA DE PROFORMAS */
// Proforma Notificación DML ORIGEN ATEL (OFICIO REMISORIO)
Route::post('/DescargaProformaNotiDML', [DeterminacionOrigenATEL::class, 'DescargaProformaNotiDML']);
Route::post('/ADescargaProformaNotiDML', [AdicionDxDTO::class, 'ADescargaProformaNotiDML']);
// Proforma DML ORIGEN (DICTAMEN)
Route::post('/DescargaProformaDML', [DeterminacionOrigenATEL::class, 'DescargaProformaDML']);
Route::post('/ADescargaProformaDML', [AdicionDxDTO::class, 'ADescargaProformaDML']);
// Proforma Acuerdo Calificación de EPS
Route::post('/DescargarProformaPronunciamiento', [PronunciamientoOrigenController::class, 'DescargarProformaPronunciamiento']);
Route::post('/DescargarProformaRecursoReposicion', [ControversiaJuntasController::class, 'DescargarProformaRecursoReposicion']);


/* DESCARGAR PROFORMAS PREVISIONAL */


/* FIN SECCION: AQUI SE RENDERIZARÁN LAS RUTAS DE LOS DEMÁS ROLES: */
Route::post('/DescargaProformaNotiDMLPrev', [DeterminacionOrigenATEL::class, 'DescargaProformaNotiDMLPrev']);




Route::get('/Sigmel/pruebas', [ProbandoController::class, 'index']);
Route::get('/Sigmel/proformas', [ProbandoController::class, 'mostrarProformas']);
// GENERAR EXCEL CON PHPSPREADSHEET
// Route::post('/Sigmel/pruebas', [ProbandoController::class, 'generar'])->name('generarExcel');
Route::post('/Sigmel/pruebas', [ProbandoController::class, 'generarPDF'])->name('generarPDF');
// GENERAR EXCEL CON LARAVEL EXCEL
Route::controller(ProbandoController::class)->group(function(){
    Route::post('/Sigmel/probando-export', 'ExportarArchivo')->name('ExportarArchivo');
    // CSV
    Route::post('/Sigmel/probando-import-csv-con_encabezados', 'importarCsvConEncabezados')->name('ImportarCsvConEncabezados');
    Route::post('/Sigmel/probando-import-csv-sin_encabezados', 'importarCsvSinEncabezados')->name('ImportarCsvSinEncabezados');
    // XLSX
    Route::post('/Sigmel/probando-import-xlsx-sin_encabezados', 'importarXlsxSinEncabezados')->name('ImportarXlsxSinEncabezados');
    Route::post('/Sigmel/probando-import-xlsx-con_encabezados', 'importarXlsxConEncabezados')->name('ImportarXlsxConEncabezados');
});

Route::get('test_proformas', [ProbandoController::class, 'test_proformas']);