<?php

use App\Http\Controllers\Administrador\AdministradorController;
use App\Http\Controllers\Administrador\EntidadesController;
use App\Http\Controllers\Administrador\BuscarEventoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Autenticacion\LoginController;
use App\Http\Controllers\Autenticacion\LogoutController;
use App\Http\Controllers\Coordinador\CalificacionPCLController;
use App\Http\Controllers\Coordinador\CalificacionOrigenController;
use App\Http\Controllers\Ingenieria\IngenieriaController;
use App\Http\Controllers\ProbandoController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Coordinador\CoordinadorController;
use App\Http\Controllers\Coordinador\SolicitudDocumentoSeguimientosPCLController;
use App\Http\Controllers\Coordinador\PronunciamientoPCLController;
use App\Http\Controllers\Coordinador\BandejaOrigenController;
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

// 23/05/2023
// Vista: Gestión Inicial Nuevo
Route::get('/Sigmel/RolAdministrador/GestionInicialNuevo', [AdministradorController::class, 'mostrarVistaGestionInicialNuevo'])->name('gestionInicialNuevo');
// Acción: Rellenar los selectores del formulario acorde al parametro indicado
Route::post('/cargarselectores', [AdministradorController::class, 'cargueListadoSelectores']);
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
Route::post('/actualizarDxPrincipalAdudezaAuditivas', [CalificacionPCLController::class, 'actualizarDxPrincipalAdudezaAuditiva']);
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
// Acción: Traer listado de selectores para el calculo de DEFICIENCIA POR ALTERACIONES DE LOS SISTEMAS GENERALES
Route::post('/ListadoSelectoresDefiAlteraciones', [CalificacionPCLController::class, 'ListadoSelectoresDefiAlteraciones']);
// Acción: Consultar Deficiencia acorde al clase final y la tabla
Route::post('/consultaValorDeficiencia', [CalificacionPCLController::class, 'consultaValorDeficiencia']);
// Acción: Guardar Datos Listado de documentos solicitados
Route::post('/GuardarDeficienciaAlteraciones',[CalificacionPCLController::class, 'GuardarDeficienciaAlteraciones']);

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
Route::post('/actualizarProfesionalServicio', [BandejaOrigenController::class, 'actualizarBandejaOrigen']);

// 14/09/2023
// Vista: Módulo Calificación Origen Coordinador
Route::get('/calificacionOrigen', [CalificacionOrigenController::class, 'mostrarVistaCalificacionOrigen'])->name('calificacionOrigen');
Route::post('/calificacionOrigen', [CalificacionOrigenController::class, 'mostrarVistaCalificacionOrigen'])->name('calificacionOrigen');
// Accion: Insertar Califcación Origen
Route::post('/registrarCalificacionOrigen', [CalificacionOrigenController::class, 'guardarCalificacionOrigen']);
/* FIN SECCION: AQUI SE RENDERIZARÁN LAS RUTAS DE LOS DEMÁS ROLES: */


Route::get('/Sigmel/pruebas', [ProbandoController::class, 'index']);
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