<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CargarDocRequest;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Validation\Rules\File;

use Illuminate\Support\Facades\File;

use App\Models\sigmel_grupos_trabajos;
use App\Models\sigmel_usuarios_grupos_trabajos;
use App\Models\sigmel_clientes;
use App\Models\sigmel_auditorias_gr_trabajos;
use App\Models\sigmel_auditorias_creacion_clientes;

/* llamado modelos formulario gestion inicial */
use App\Models\sigmel_lista_clientes;
use App\Models\sigmel_lista_tipo_clientes;
use App\Models\sigmel_lista_tipo_eventos;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_lista_dominancias;
use App\Models\sigmel_lista_departamentos_municipios;
use App\Models\sigmel_lista_eps;
use App\Models\sigmel_lista_afps;
use App\Models\sigmel_lista_arls;
use App\Models\sigmel_lista_actividad_economicas;
use App\Models\sigmel_lista_clase_riesgos;
use App\Models\sigmel_lista_ciuo_codigos;
use App\Models\sigmel_lista_motivo_solicitudes;
use App\Models\sigmel_lista_solicitantes;
use App\Models\sigmel_lista_procesos_servicios;
use App\Models\sigmel_lista_acciones_procesos_servicios;
use App\Models\sigmel_lista_documentos;

/* llamado modelos para insertar la información del formulario de gestion inicial (creacion de evento) */
use App\Models\sigmel_informacion_eventos;
use App\Models\sigmel_informacion_afiliado_eventos;
use App\Models\sigmel_informacion_pericial_eventos;
use App\Models\sigmel_informacion_laboral_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;
use App\Models\sigmel_historico_empresas_afiliados;
use App\Models\sigmel_registro_documentos_eventos;

/* Llamado modelo para consultar historial de acciones */
use App\Models\sigmel_historial_acciones_eventos;
use App\Models\psrvistadocumentos;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use stdClass;

class AdministradorController extends Controller
{
    
    public function show(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.index', compact('user'));
    }

    // TODO LO REFERENTE A LOS GRUPOS DE TRABAJO
    public function mostrarVistaNuevoEquipoTrabajo(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.crearGruposTrabajo', compact('user'));
    }


    public function ListaLideresXProceso(Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        // Traemos los lideres acorde a la selección del proceso
        // DB::raw("SELECT id, name, email FROM users WHERE FIND_IN_SET($request->id_proceso_seleccionado, id_procesos_usuario)");
        $datos_lideres_x_proceso = DB::table('users')
        ->select("id", "name", "email")
        ->whereRaw("FIND_IN_SET($request->id_proceso_seleccionado, id_procesos_usuario) > 0")
        ->get();

        $informacion_de_vuelta = json_decode(json_encode($datos_lideres_x_proceso), true);

        return response()->json($informacion_de_vuelta);
    }

    public function guardar_equipo_trabajo(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        
        

        if (empty($request->listado_usuarios_equipo)) {
            return back()->with('equipo_creado', 'Debe almenos seleccionar un usuario para crear el equipo de trabajo.');
        }else{

            if ($request->listado_lider == "") {
                return back()->with('equipo_creado', 'Debe almenos seleccionar un lider para crear el equipo de trabajo.');
            }else{

                // Se realiza el registro de un nuevo grupo de trabajo
                $nuevo_equipo = array(
                    'nombre' => $request->nombre_equipo_trabajo,
                    'Id_proceso_equipo' => $request->proceso,
                    'lider' => $request->listado_lider,
                    'estado' => $request->estado_equipo,
                    'descripcion' => $request->descripcion_equipo_trabajo,
                    'created_at' => $date
                );
    
                
                sigmel_grupos_trabajos::on('sigmel_gestiones')->insert($nuevo_equipo);
    
                $id_equipo_trabajo = sigmel_grupos_trabajos::on('sigmel_gestiones')->select('id')->latest('id')->first();
    
                for ($i=0; $i < count($request->listado_usuarios_equipo); $i++) { 
                    $id_usuario_asignar = $request->listado_usuarios_equipo[$i];
    
                    $asignar_usuarios = array(
                        'id_equipo_trabajo' => $id_equipo_trabajo['id'],
                        'id_usuarios_asignados' => $id_usuario_asignar,
                        'created_at' => $date
                    );
                    sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')->insert($asignar_usuarios);
                }
    
    
                /* REGISTRO ACTIVIDAD PARA AUDITORIA */
                $accion_realizada = "Registro de Grupo de Trabajo N° {$id_equipo_trabajo['id']}";
                $registro_actividad = [
                    'id_usuario_sesion' => Auth::id(),
                    'nombre_usuario_sesion' => Auth::user()->name,
                    'email_usuario_sesion' => Auth::user()->email,
                    'acccion_realizada' => $accion_realizada,
                    'fecha_registro_accion' => $date
                ];
                
                sigmel_auditorias_gr_trabajos::on('sigmel_auditorias')->insert($registro_actividad);
    
                return back()->with('equipo_creado', 'Equipo de trabajo creado correctamente.');
            }


        }

    }

    public function mostrarVistaListarEquiposTrabajo(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $listado_equipos_trabajo = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_grupos_trabajos as sgt')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'sgt.Id_proceso_equipo', '=', 'slps.Id_proceso')
        ->leftJoin('sigmel_sys.users as u', 'sgt.lider', '=', 'u.id')
        ->select("sgt.id", "slps.Id_proceso", "slps.Nombre_proceso", "sgt.nombre as Nombre_equipo", "sgt.lider", "u.name as Nombre_lider", "u.email as Correo_lider", "sgt.estado", "sgt.descripcion","sgt.created_at")
        ->groupBy("sgt.id")
        ->get();
        
        $conteo_activos_inactivos = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_grupos_trabajos')
        ->select(DB::raw("COUNT(IF(estado = 'activo', 1, NULL)) AS 'Activos'"), DB::raw("COUNT(IF(estado = 'inactivo', 1, NULL)) AS 'Inactivos'"))
        ->get();
        
        return view('administrador.listarGruposTrabajo', compact('user', 'listado_equipos_trabajo', 'conteo_activos_inactivos'));
    }

    public function mostrarVistaEditarEquipoTrabajo(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $id = $request->id;
        $lider = $request->lider;

        $info_selector_lider = DB::table('users')->select('id', 'name', 'email')->where('id', $lider)->get();
        $info_grupo_trabajo = sigmel_grupos_trabajos::on('sigmel_gestiones')->select('id', 'nombre', 'estado', 'descripcion')
                              ->where('id', $id)->get();

        return view('administrador.editarGrupoTrabajo', compact('user', 'info_selector_lider', 'info_grupo_trabajo'));
    }

    public function listadoLideresEditar(Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        $datos = DB::table('users')->select('id', 'name', 'email')->whereNotIn('id', [$request->id])->get();
        $informacion_usuario = json_decode(json_encode($datos), true);
        return response()->json($informacion_usuario);
    }
    // MAURO
    public function listadoUsuariosAsignacion (Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }
        
        $id_equipo_trabajo = $request->id_equipo_trabajo;

        $ids_usuarios_asignados =  sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')
                                    ->select('id_usuarios_asignados')
                                    ->where('id_equipo_trabajo', $id_equipo_trabajo)->get();

        $string_ids = array();
        for ($i=0; $i < count($ids_usuarios_asignados); $i++) { 
            
            array_push($string_ids, $ids_usuarios_asignados[$i]->id_usuarios_asignados);
        }

        $datos_usuarios_no_asignados = DB::table('users')->select('id', 'name', 'email', DB::raw("'no' as seleccionado"))
        ->whereRaw("FIND_IN_SET($request->id_proceso_seleccionado, id_procesos_usuario) > 0")
        ->whereNotIn('id', $string_ids)->get();

        $datos_usuarios_asignados = DB::table('users')->select('id', 'name', 'email', DB::raw("'selected' as seleccionado"))->whereIn('id', $string_ids)->get();

        $info_usuarios_no_asignados = json_decode(json_encode($datos_usuarios_no_asignados), true);
        $info_usuarios_asignados = json_decode(json_encode($datos_usuarios_asignados), true);

        $listado_todos_usuarios = array();

        for ($a=0; $a < count($info_usuarios_no_asignados); $a++) { 
            array_push($listado_todos_usuarios, [
                'id' => $info_usuarios_no_asignados[$a]['id'],
                'name' => $info_usuarios_no_asignados[$a]['name'],
                'email' => $info_usuarios_no_asignados[$a]['email'],
                'seleccionado' => $info_usuarios_no_asignados[$a]['seleccionado'],
            ]);
        }

        for ($c=0; $c < count($info_usuarios_asignados); $c++) { 
            array_push($listado_todos_usuarios, [
                'id' => $info_usuarios_asignados[$c]['id'],
                'name' => $info_usuarios_asignados[$c]['name'],
                'email' => $info_usuarios_asignados[$c]['email'],
                'seleccionado' => $info_usuarios_asignados[$c]['seleccionado'],
            ]);
        }

        return response()->json($listado_todos_usuarios);
    }

    public function editar_equipo_trabajo(Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }
        
        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        
        $id_equipo_trabajo = $request->id_equipo_trabajo;

        $ids_usuarios_asignados =  sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')
                                    ->select('id_usuarios_asignados')
                                    ->where('id_equipo_trabajo', $id_equipo_trabajo)->get();
                                    
        
        $id_asignados_orignales = [];
        for ($i=0; $i < count($ids_usuarios_asignados); $i++) { 
            $id_asignados_orignales [] = $ids_usuarios_asignados[$i]->id_usuarios_asignados;
        }

        $ids_asignados_formulario = $request->editar_listado_usuarios_equipo;

        if (empty($ids_asignados_formulario)) {
            $mensajes = array(
                "parametro" => 'fallo',
                "mensaje" => 'No puede eliminar todos los usuarios del grupo.'
            );
            return json_decode(json_encode($mensajes, true));

        } else {

            if($request->editar_descripcion_equipo_trabajo <> ""){
                $descripcion_final = $request->editar_descripcion_equipo_trabajo;
            }else{
                $descripcion_final = null;
            }

            // Se realiza la actualización de la información del grupo de trabajo
            $actualizar_info_equipo = array(
                'Id_proceso_equipo' => $request->editar_proceso,
                'nombre' => $request->editar_nombre_equipo_trabajo,
                'lider' => $request->editar_listado_lider,
                'estado' => $request->editar_estado_equipo,
                'descripcion' => $descripcion_final,
                'updated_at' => $date
            );
            
            sigmel_grupos_trabajos::on('sigmel_gestiones')->where('id', $id_equipo_trabajo)->update($actualizar_info_equipo);
            
            $eliminar_ids_asignados = array();
            if (count($ids_asignados_formulario) < count($id_asignados_orignales)) {
                $diferencia = array_diff($id_asignados_orignales, $ids_asignados_formulario);
                // echo "borrar";

                foreach ($diferencia as $key => $id_eliminar) {
                    sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')
                    ->where([
                        ['id_equipo_trabajo', $id_equipo_trabajo],
                        ['id_usuarios_asignados', $id_eliminar]
                    ])->delete();
                }
            }
    
            if (count($ids_asignados_formulario) > count($id_asignados_orignales)) {
                $diferencia = array_diff($ids_asignados_formulario, $id_asignados_orignales);
                // echo "insertar";

                foreach ($diferencia as $key => $id_insertar) {
                    $asignar_usuarios = array(
                        'id_equipo_trabajo' => $id_equipo_trabajo,
                        'id_usuarios_asignados' => $id_insertar,
                        'created_at' => $date
                    );
                    sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')->insert($asignar_usuarios);
                }
            }

            /* REGISTRO ACTIVIDAD PARA AUDITORIA */
            $accion_realizada = "Edición de Grupo de Trabajo N° {$id_equipo_trabajo}";
            $registro_actividad = [
                'id_usuario_sesion' => Auth::id(),
                'nombre_usuario_sesion' => Auth::user()->name,
                'email_usuario_sesion' => Auth::user()->email,
                'acccion_realizada' => $accion_realizada,
                'fecha_registro_accion' => $date
            ];
            
            sigmel_auditorias_gr_trabajos::on('sigmel_auditorias')->insert($registro_actividad);

            $mensajes = array(
                "parametro" => 'exito',
                "mensaje" => 'Información de equipo de trabajo actualizada satisfactoriamente. Para visualizar los cambios debe hacer clic en el botón Actualizar.'
            );
            return json_decode(json_encode($mensajes, true));

        }
        
    }

    /* TODO LO REFERENTE A CLIENTE */
    public function mostrarVistaCrearCliente(){
        if(!Auth::check()){
            return redirect('/');
        }

        $user = Auth::user();

        $info_registro_cliente = sigmel_clientes::on('sigmel_gestiones')
        ->select('id', 'nombre_cliente', 'nit', 'razon_social', 'representante_legal', 
        'telefono_contacto', 'correo_contacto', 
        'estado', 'observacion', 'created_at', 'updated_at')->get();

        return view('administrador.registrarCliente', compact('user', 'info_registro_cliente'));
    }

    public function guardar_cliente(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        if ($request->observacion_cliente <> '') {
            $observacion = $request->observacion_cliente;
        } else {
            $observacion = null;
        }

        $crear_unico_cliente = [
            'nombre_cliente' => $request->nombre_cliente,
            'nit' => $request->nit_cliente,
            'razon_social' => $request->razon_social_cliente,
            'representante_legal' => $request->representante_legal_cliente,
            'telefono_contacto' => $request->telefono_contacto_cliente,
            'correo_contacto' => $request->correo_contacto_cliente,
            'estado' => $request->estado_cliente,
            'observacion' => $observacion,
            'created_at' => $date
        ];

        sigmel_clientes::on('sigmel_gestiones')->insert($crear_unico_cliente);

        /* REGISTRO ACTIVIDAD PARA AUDITORIA */
        $accion_realizada = "Registro de cliente: {$request->nombre_cliente}";
        $registro_actividad = [
            'id_usuario_sesion' => Auth::id(),
            'nombre_usuario_sesion' => Auth::user()->name,
            'email_usuario_sesion' => Auth::user()->email,
            'acccion_realizada' => $accion_realizada,
            'fecha_registro_accion' => $date
        ];
        
        sigmel_auditorias_creacion_clientes::on('sigmel_auditorias')->insert($registro_actividad);

        $msg= "Cliente registrado correctamente.";
        return redirect()->route('registrarCliente')->with('cliente_creado', $msg);

    }

    public function actualizar_cliente(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        if ($request->observacion_cliente <> '') {
            $observacion = $request->observacion_cliente;
        } else {
            $observacion = null;
        }

        $crear_unico_cliente = [
            'nombre_cliente' => $request->nombre_cliente,
            'nit' => $request->nit_cliente,
            'razon_social' => $request->razon_social_cliente,
            'representante_legal' => $request->representante_legal_cliente,
            'telefono_contacto' => $request->telefono_contacto_cliente,
            'correo_contacto' => $request->correo_contacto_cliente,
            'estado' => $request->estado_cliente,
            'observacion' => $observacion,
            'updated_at' => $date
        ];


        
        sigmel_clientes::on('sigmel_gestiones')
        ->where('id', $request->id_cliente)
        ->update($crear_unico_cliente);

        /* REGISTRO ACTIVIDAD PARA AUDITORIA */
        $accion_realizada = "Actualización de información del cliente: {$request->nombre_cliente}";
        $registro_actividad = [
            'id_usuario_sesion' => Auth::id(),
            'nombre_usuario_sesion' => Auth::user()->name,
            'email_usuario_sesion' => Auth::user()->email,
            'acccion_realizada' => $accion_realizada,
            'fecha_registro_accion' => $date
        ];
        
        sigmel_auditorias_creacion_clientes::on('sigmel_auditorias')->insert($registro_actividad);
        
        $msg= "Información de cliente actualizada correctamente.";
        return redirect()->route('registrarCliente')->with('cliente_creado', $msg);
    }

    /* VISTA FRONTEND BANDEJA GESTIÓN INICIAL */
    public function mostrarVistaBandejaGestionInicial(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.bandejaGestionInicial', compact('user'));
    }

    /* VISTA FRONTEND PARA CARGUE DE BASES */
    public function mostrarVistaCargueBases(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.cargueBases', compact('user'));
    }

    /* VISTA FRONTEND PARA REPORTES MODULOS */
    public function mostrarVistaReportesModulos(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.reportesModulos', compact('user'));
    }

    /* VISTA FRONTEND PARA REPORTES BANDEJAS */
    public function mostrarVistaReportesBandejas(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.reportesBandejas', compact('user'));
    }

    /* VISTA FRONTEND PARA GESTIONAR FACTURACION */
    public function mostrarVistaGestionarFacturacion(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.gestionarFacturacion', compact('user'));
    }

    public function mostrarVistaAuditoriaGrupos(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.auditoriaGrupos', compact('user'));
    }

    /* TODO LO REFERENTE AL FORMULARIO DE CREACIÓN DE EVENTO NUEVO */
    public function mostrarVistaGestionInicialNuevo(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        $listado_documentos = sigmel_lista_documentos::on('sigmel_gestiones')
        ->select('Id_Documento', 'Nro_documento', 'Nombre_documento', 'Requerido')
        ->where([['Estado', "=", "activo"]])->get();
        
        return view('administrador.gestionInicialNuevo', compact('user', 'listado_documentos'));
    }

    public function cargueListadoSelectores(Request $request){

        $parametro = $request->parametro;

        /* TRAER LISTADO DE CLIENTES */
        if($parametro == 'lista_clientes'){
            $listado_clientes = sigmel_lista_clientes::on('sigmel_gestiones')
                ->select('Id_Cliente', 'Nombre_cliente')
                ->where('Estado', 'activo')
                ->get();
            
            $info_lista_clientes = json_decode(json_encode($listado_clientes, true));
            return response()->json($info_lista_clientes);
        }

        /* TRAER LISTADO DE TIPOS DE CLIENTE */
        if ($parametro == "lista_tipo_clientes") {
            
            $listado_tipo_clientes = sigmel_lista_tipo_clientes::on('sigmel_gestiones')
                ->select('Id_TipoCliente', 'Nombre_tipo_cliente')
                ->where('Estado', 'activo')
                ->get();
            
            $info_lista_tipo_clientes = json_decode(json_encode($listado_tipo_clientes, true));
            return response()->json($info_lista_tipo_clientes);
        }

        /* TRAER LISTADO DE TIPOS DE EVENTO */
        if ($parametro == "lista_tipo_evento") {
            
            $listado_tipo_eventos = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
            ->select('Id_Evento', 'Nombre_evento')
            ->where('Estado', 'activo')
            ->get();
            
            $info_lista_tipo_eventos = json_decode(json_encode($listado_tipo_eventos, true));
            return response()->json($info_lista_tipo_eventos);
        }

        /* TRAER LISTADO DE TIPOS DE DOCUMENTO */
        if ($parametro == "lista_tipo_documento") {
            
            $listado_tipo_documento = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Tipo de documento'],
                    ['Estado', '=', 'activo']
                ])
                ->get();
            
            $info_lista_tipo_documento = json_decode(json_encode($listado_tipo_documento, true));
            return response()->json($info_lista_tipo_documento);
        }

        /* TRAER LISTADO DE GENERO */
        if ($parametro == "genero") {
            
            $listado_generos = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Genero'],
                    ['Estado', '=', 'activo']
                ])
                ->get();
            
            $info_lista_generos = json_decode(json_encode($listado_generos, true));
            return response()->json($info_lista_generos);
        }

        /* TRAER LISTADO DE ESTADO CIVIL */
        if ($parametro == "estado_civil") {
            
            $listado_estado_civil = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Estado civil'],
                    ['Estado', '=', 'activo']
                ])
                ->get();
            
            $info_lista_estado_civil = json_decode(json_encode($listado_estado_civil, true));
            return response()->json($info_lista_estado_civil);
        }

        /* TRAER LISTADO DE NIVEL ESCOLAR */
        if ($parametro == "nivel_escolar") {
            
            $listado_nivel_escolar = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro','Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Nivel escolar'],
                ['Estado', '=', 'activo']
            ])
            ->get();
            
            $info_lista_nivel_escolar = json_decode(json_encode($listado_nivel_escolar, true));
            return response()->json($info_lista_nivel_escolar);
        }

        /* TRAER LISTADO DE DOMINANCIAS */
        if ($parametro == "dominancia") {
            
            $listado_dominancia = sigmel_lista_dominancias::on('sigmel_gestiones')
                ->select('Id_Dominancia', 'Nombre_dominancia')
                ->where('Estado', 'activo')
                ->get();
            
            $info_lista_dominancia = json_decode(json_encode($listado_dominancia, true));
            return response()->json($info_lista_dominancia);
        }

        /* TRAER LISTADO DEPARTAMENTOS (INFORMACIÓN DE AFILIADO) */
        if ($parametro == "departamentos_info_afiliado") {
            
            $listado_departamentos_info_afiliado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_departamento', 'Nombre_departamento')
                ->where('Estado', 'activo')
                ->groupBy('Nombre_departamento')
                ->get();
            
            $info_lista_departamentos_info_afiliado = json_decode(json_encode($listado_departamentos_info_afiliado, true));
            return response()->json($info_lista_departamentos_info_afiliado);
        }

        /* TRAER LISTADO MUNCIPIOS (INFORMACIÓN DE AFILIADO) */
        if($parametro == "municipios_info_afiliado"){
            $listado_municipios_info_afiliado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_municipios', 'Nombre_municipio')
                ->where([
                    ['Id_departamento', '=', $request->id_departamento_info_afiliado],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_lista_municpios_info_afiliado = json_decode(json_encode($listado_municipios_info_afiliado, true));
            return response()->json($info_lista_municpios_info_afiliado);
        }
        
        /* TRAER LISTADO DE TIPOS DE AFILIADO */
        if ($parametro == "tipo_afiliado") {
            
            $listado_tipo_afiliado = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Tipo de Afiliado'],
                    ['Estado', '=', 'activo']
                ])
                ->get();
            
            $info_lista_tipo_afiliado = json_decode(json_encode($listado_tipo_afiliado, true));
            return response()->json($info_lista_tipo_afiliado);
        }

        /* TRAER LISTADO DE EPS */
        if ($parametro == "lista_eps") {
            
            $listado_eps = sigmel_lista_eps::on('sigmel_gestiones')
                ->select('Id_Eps', 'Nombre_eps')
                ->where('Estado', 'activo')
                // ->orderBy('Nombre_eps', 'asc')
                ->get();
            
            $info_lista_eps = json_decode(json_encode($listado_eps, true));
            return response()->json($info_lista_eps);
        }

        /* TRAER LISTADO DE AFP */
        if ($parametro == "lista_afp") {
            
            $listado_afp = sigmel_lista_afps::on('sigmel_gestiones')
                ->select('Id_Afp', 'Nombre_afp')
                ->where('Estado', 'activo')
                // ->orderBy('Nombre_afp', 'asc')
                ->get();
            
            $info_lista_afp = json_decode(json_encode($listado_afp, true));
            return response()->json($info_lista_afp);
        }

        /* TRAER LISTADO DE ARL (Información Afiliado) */
        if ($parametro == "lista_arl_info_afiliado") {
            
            $listado_arl_info_afiliado = sigmel_lista_arls::on('sigmel_gestiones')
                ->select('Id_Arl', 'Nombre_arl')
                ->where('Estado', 'activo')
                // ->orderBy('Nombre_arl', 'asc')
                ->get();
            
            $info_lista_arl_info_afiliado = json_decode(json_encode($listado_arl_info_afiliado, true));
            return response()->json($info_lista_arl_info_afiliado);
        }

        /* TRAER LISTADO DE APODERADO */
        if ($parametro == "apoderado") {
            
            $listado_apoderado = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Apoderado'],
                    ['Estado', '=', 'activo']
                ])
                ->get();
            
            $info_lista_apoderado = json_decode(json_encode($listado_apoderado, true));
            return response()->json($info_lista_apoderado);
        }

        /* TRAER LISTADO DE ACTIVO */
        if ($parametro == "activo") {
            
            $listado_activo = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Activo'],
                    ['Estado', '=', 'activo']
                ])
                ->get();
            
            $info_lista_activo = json_decode(json_encode($listado_activo, true));
            return response()->json($info_lista_activo);
        }

        /* LISTADO ARL */
        if($parametro == 'listado_arl_info_laboral'){
            $listado_arls = sigmel_lista_arls::on('sigmel_gestiones')
                ->select('Id_Arl', 'Nombre_arl')
                ->where('Estado', 'activo')
                ->get();
            
            $info_listado_arls = json_decode(json_encode($listado_arls, true));
            return response()->json(($info_listado_arls));
        }

        /* LISTADO DEPARTAMENTOS (Información Laboral) */
        if($parametro == 'listado_departamento_info_laboral'){
            $listado_departamento_info_laboral = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_departamento', 'Nombre_departamento')
                ->where('Estado', 'activo')
                ->groupBy('Id_departamento','Nombre_departamento')
                ->get();

            $info_listado_departamento_info_laboral = json_decode(json_encode($listado_departamento_info_laboral, true));
            return response()->json(($info_listado_departamento_info_laboral));
        }

        /* LISTADO DE MUNICIPIOS (Información Laboral) */
        if($parametro == "municipios_info_laboral"){
            $listado_municipios_info_laboral = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_municipios', 'Nombre_municipio')
                ->where([
                    ['Id_departamento', '=', $request-> id_departamento_info_laboral],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_listado_municipio_info_laboral = json_decode(json_encode($listado_municipios_info_laboral, true));
            return response()->json(($info_listado_municipio_info_laboral));
        }

        /* LISTADO ACTIVIDAD ECONOMICA */
        if($parametro == 'listado_actividad_economica'){
            $listado_actividades_economicas = sigmel_lista_actividad_economicas::on('sigmel_gestiones')
                ->select('Id_ActEco', 'id_codigo', 'Nombre_actividad')
                ->where('Estado', 'activo')
                ->get();

            $info_listado_actividades_economicas = json_decode(json_encode($listado_actividades_economicas, true));
            return response()->json(($info_listado_actividades_economicas));
        }

        /* LISTADO CLASE DE RIESGO */
        if($parametro == 'listado_clase_riesgo'){
            $listado_clases_de_riesgos = sigmel_lista_clase_riesgos::on('sigmel_gestiones')
                ->select('Id_Riesgo', 'Nombre_riesgo')
                ->where('Estado', 'activo')
                ->get();

            $info_listado_clases_de_riesgos = json_decode(json_encode($listado_clases_de_riesgos, true));
            return response()->json(($info_listado_clases_de_riesgos));
        }

        /* LISTADO CODIGO CIUO */
        if($parametro == 'listado_codigo_ciuo'){
            $listado_actividades_economicas = sigmel_lista_ciuo_codigos::on('sigmel_gestiones')
                ->select('Id_Codigo', 'id_codigo_ciuo', 'Nombre_ciuo')
                ->where('Estado', 'activo')
                ->get();

            $info_listado_actividades_economicas = json_decode(json_encode($listado_actividades_economicas, true));
            return response()->json(($info_listado_actividades_economicas));
        }
        
        /* LISTADO MOTIVO SOLICITUD */
        if($parametro == 'listado_motivo_solicitud'){
            $listado_motivo_solicitud = sigmel_lista_motivo_solicitudes::on('sigmel_gestiones')
                ->select('Id_Solicitud', 'Nombre_solicitud')
                ->where('Estado', 'activo')
                ->get();

            $info_listado_motivo_solicitud = json_decode(json_encode($listado_motivo_solicitud, true));
            return response()->json(($info_listado_motivo_solicitud));
        }

        /* LISTADO TIPO VINCULO */
        if($parametro == 'listado_tipo_vinculo'){
            $listado_tipo_vinculo = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Tipo de vinculacion'],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_listado_tipo_vinculo = json_decode(json_encode($listado_tipo_vinculo, true));
            return response()->json(($info_listado_tipo_vinculo));
        }

        /* LISTADO REGIMEN EN SALUD */
        if($parametro == 'listado_solicitud_regimen_en_salud'){
            $listado_solicitud_regimen_salud = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Solicitud Regimen en salud'],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_listado_solicitud_regimen_salud = json_decode(json_encode($listado_solicitud_regimen_salud, true));
            return response()->json(($info_listado_solicitud_regimen_salud));
        }

        /* LISTADO SOLICITANTE */
        if($parametro == 'listado_solicitante'){
            $listado_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')
                ->select('Id_solicitante', 'Solicitante')
                ->where('Estado', 'activo')
                ->groupBy('Id_solicitante','Solicitante')
                ->get();

            $info_listado_solicitante = json_decode(json_encode($listado_solicitante, true));
            return response()->json(($info_listado_solicitante));
        }

        /* NOMBRE DE SOLICITANTE */
        if($parametro == "nombre_solicitante"){
            $listado_nombre_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')
            ->select('Id_Nombre_solicitante', 'Nombre_solicitante')
            ->where([
                ['Id_solicitante', '=', $request->id_solicitante],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_nombre_solicitante = json_decode(json_encode($listado_nombre_solicitante, true));
            return response()->json(($info_listado_nombre_solicitante));
        }

        /* FUENTE DE INFORMACIÓN */
        if($parametro == 'listado_fuente_informacion'){
            $listado_fuente_informacion = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Fuente de informacion'],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_listado_fuente_informacion = json_decode(json_encode($listado_fuente_informacion, true));
            return response()->json(($info_listado_fuente_informacion));
        }

        /* LISTADO PROCESO */
        if($parametro == 'listado_proceso'){
            $listado_proceso = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
                ->select('Id_proceso', 'Nombre_proceso')
                ->where('Estado', 'activo')
                ->groupBy('Id_proceso','Nombre_proceso')
                ->get();

            $info_listado_proceso = json_decode(json_encode($listado_proceso, true));
            return response()->json(($info_listado_proceso));
        }

        /* LISTADO DE PROCESOS PARA LA EDICICIÓN DE USUARIO(MODAL FORMULARIO EDICIÓN USUARIO) */
        if($parametro == 'listado_proceso_edicion_usuario'){

            $ids_procesos_usuario = $request->id_procesos;
            $arreglo_id_procesos_usuario = explode(",", $ids_procesos_usuario);

            /* SE TRAE LA LISTA DE LOS PROCESOS QUE LE FUERON ASIGNADOS AL USUARIO */
            $listado_procesos_usuario = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_proceso', 'Nombre_proceso', DB::raw("'si' as seleccionado"))
            ->where('Estado', 'activo')
            ->whereIn('Id_proceso', $arreglo_id_procesos_usuario)
            ->distinct()
            ->get();

            $info_procesos_usuario = json_decode(json_encode($listado_procesos_usuario, true));
            // echo "<pre>";
            // print_r($info_procesos_usuario);
            // echo "</pre>";

            /* SE TRAE LA LISTA DE TODOS LOS PROCESOS A EXCEPCION DE LOS QUE LE FUERON ASIGNADOS AL USUARIO */
            $listado_procesos_faltantes = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_proceso', 'Nombre_proceso', DB::raw("'no' as seleccionado"))
            ->where('Estado', 'activo')
            ->whereNotIn('Id_proceso', $arreglo_id_procesos_usuario)
            ->distinct()
            ->get();

            $info_procesos_faltantes = json_decode(json_encode($listado_procesos_faltantes, true));
            // echo "<pre>";
            // print_r($info_procesos_faltantes);
            // echo "</pre>";

            $array_info_final = array_merge($info_procesos_usuario, $info_procesos_faltantes);
            // echo "<pre>";
            // print_r($array_info_final);
            // echo "</pre>";

            return response()->json(($array_info_final));

        }

        /* LISTADO SERVICIOS */
        if ($parametro == 'listado_servicios') {
            $listado_servicios = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                ['Id_proceso', '=', $request->id_proceso],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicios = json_decode(json_encode($listado_servicios, true));
            return response()->json(($info_listado_servicios));
        }

        /* LISTADO ACCION */
        if($parametro == 'listado_accion'){
            $listado_accion = sigmel_lista_acciones_procesos_servicios::on('sigmel_gestiones')
                ->select('Id_Accion', 'Nombre_accion')
                ->where('Estado', 'activo')
                ->get();

            $info_listado_accion= json_decode(json_encode($listado_accion, true));
            return response()->json(($info_listado_accion));
        }

        /* LISTADO DE SERVICIOS DEPENDIENDO DEL PROCESO PARA LA VISTA DE BUSCADOR DE EVENTOS (MODAL NUEVO SERVICIO) 
            Se captura el evento para traer los servicios que no han sido usados en eventos
        */
        if($parametro == 'listado_servicios_nuevo_servicio'){
            
            $listado_id_servicios_evento = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_servicio')
                ->where([
                    ['ID_evento', '=', $request->nro_evento],
                    ['Id_proceso', '=',  $request->id_proceso_actual]
                ])->get();

            $string_ids_servicios = array();

            for ($i=0; $i < count($listado_id_servicios_evento); $i++) { 
                array_push($string_ids_servicios, $listado_id_servicios_evento[$i]->Id_servicio);
            }

            $listado_servicios = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                ['Id_proceso', '=', $request->id_proceso_actual],
                ['Estado', '=', 'activo']
            ])
            ->whereNotIn('Id_Servicio', $string_ids_servicios)
            ->get();

            $info_listado_servicios = json_decode(json_encode($listado_servicios, true));
            return response()->json(($info_listado_servicios));
        }

        /* LISTADO DE ACCIONES PARA LA VISTA DE BUSCADOR DE EVENTOS (MODAL NUEVO SERVICIO) */
        if ($parametro == 'listado_accion_nuevo_servicio') {
            $listado_accion = sigmel_lista_acciones_procesos_servicios::on('sigmel_gestiones')
                ->select('Id_Accion', 'Nombre_accion')
                ->where([
                    ['Id_Accion', '=', 1],
                    ['Estado', 'activo']
                    ])
                ->get();

            $info_listado_accion= json_decode(json_encode($listado_accion, true));
            return response()->json(($info_listado_accion));
        }

        /* LISTADO DE PROCESOS DEPENDIENDO DEL PROCESO ACTUAL (TRAE LOS QUE SEAN DIFERENTES AL ID ENVIADO) PARA LA VISTA BUSCADOR DE EVENTOS (MODAL NUEVO PROCESO) */
        if($parametro == 'listado_procesos_nuevo_proceso'){

            $listado_id_procesos_evento = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_proceso')
                ->where([
                    ['ID_evento', '=', $request->ident_evento_actual],
                ])->groupBy('Id_proceso')
                ->get();

            $string_ids_procesos = array();

            for ($i=0; $i < count($listado_id_procesos_evento); $i++) { 
                array_push($string_ids_procesos, $listado_id_procesos_evento[$i]->Id_proceso);
            }

            $listado_procesos_nuevo_proceso = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
                ->select('Id_proceso', 'Nombre_proceso')
                ->where('Estado', 'activo')
                ->whereNotIn('Id_proceso', $string_ids_procesos)
                ->groupBy('Id_proceso','Nombre_proceso')
                ->get();

            $info_listado_procesos_nuevo_proceso = json_decode(json_encode($listado_procesos_nuevo_proceso, true));
            return response()->json(($info_listado_procesos_nuevo_proceso));
        }


        /* LISTADO DE SERVICIOS DEPENDIENDO DEL PROCESO PARA LA VISTA DE BUSCADOR DE EVENTOS (MODAL NUEVO PROCESO) 
            Se captura el evento para traer los servicios que no han sido usados en eventos
        */
        if($parametro == 'listado_servicios_nuevo_proceso'){
            
            $listado_id_servicios_evento = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
                ->select('Id_servicio')
                ->where([
                    ['ID_evento', '=', $request->nro_evento_nuevo_proceso],
                    ['Id_proceso', '=',  $request->id_proceso_escogido]
                ])->get();

            $string_ids_servicios = array();

            for ($i=0; $i < count($listado_id_servicios_evento); $i++) { 
                array_push($string_ids_servicios, $listado_id_servicios_evento[$i]->Id_servicio);
            }

            $listado_servicios = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                ['Id_proceso', '=', $request->id_proceso_escogido],
                ['Estado', '=', 'activo']
            ])
            ->whereNotIn('Id_Servicio', $string_ids_servicios)
            ->get();

            $info_listado_servicios = json_decode(json_encode($listado_servicios, true));
            return response()->json(($info_listado_servicios));
        }

        /* LISTADO DE ACCIONES PARA LA VISTA DE BUSCADOR DE EVENTOS (MODAL NUEVO PROCESO) */
        if ($parametro == 'listado_accion_nuevo_proceso') {
            $listado_accion = sigmel_lista_acciones_procesos_servicios::on('sigmel_gestiones')
                ->select('Id_Accion', 'Nombre_accion')
                ->where([
                    ['Id_Accion', '=', 1],
                    ['Estado', 'activo']
                    ])
                ->get();

            $info_listado_accion= json_decode(json_encode($listado_accion, true));
            return response()->json(($info_listado_accion));
        }

        /* LISTADO DE PROCESOS PARA EL FORMULARIO DE EDICIÓN DE EQUIPO DE TRABAJO (MODAL EDICIÓN EQUIPO DE TRABAJO) */
        if ($parametro == 'listado_proceso_edicion_equipo') {
            $listado_procesos_edicion_equipo = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
                ->select('Id_proceso', 'Nombre_proceso')
                ->where('Estado', 'activo')
                // ->whereNotIn('Id_proceso', [$request->id_proceso])
                ->groupBy('Id_proceso','Nombre_proceso')
                ->get();

            $info_listado_procesos_edicion_equipo = json_decode(json_encode($listado_procesos_edicion_equipo, true));
            return response()->json(($info_listado_procesos_edicion_equipo));
        }


    }
    
    public function creacionEvento(Request $request){
    
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        $array_evento = sigmel_informacion_eventos::on('sigmel_gestiones')->select('ID_evento')->where('ID_evento', '=', $request->id_evento)->first();

        if (!empty($array_evento)) {
            // $id_evento_validacion = $array_evento['ID_evento'];
            return back()->with('confirmacion_evento_no_creado', 'El Evento ya se encuentra registrado.');
        } 
        else{
            /* RECOLECCIÓN INFORMACIÓN PARA LA TABLA: sigmel_informacion_eventos */

            // Evaluamos si selecciona la opción OTRO/¿Cuál? del selector de tipo de cliente
            if ($request->tipo_cliente == 4) {
                
                $datos_otro_tipo_cliente = [
                    'Nombre_tipo_cliente' => $request->otro_tipo_cliente,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];
                sigmel_lista_tipo_clientes::on('sigmel_gestiones')->insert($datos_otro_tipo_cliente);
                $array_tipo_cliente = sigmel_lista_tipo_clientes::on('sigmel_gestiones')->select('Id_TipoCliente')->latest('Id_TipoCliente')->first();
                $tipo_cliente = $array_tipo_cliente['Id_TipoCliente'];
            } else {
                $tipo_cliente = $request->tipo_cliente;
            }

            $datos_info_evento = [
                'Cliente' => $request->cliente,
                'Tipo_cliente' => $tipo_cliente,
                'Tipo_evento' => $request->tipo_evento,
                'ID_evento' => $request->id_evento,
                'F_evento' => $request->fecha_evento,
                'F_radicacion' => $request->fecha_radicacion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];

            // Inserción de datos en la tabla sigmel_informacion_eventos
            sigmel_informacion_eventos::on('sigmel_gestiones')->insert($datos_info_evento);

            // colacamos un tiempo de retardo pequeño para que alcance a insertar los datos
            sleep(2);

            /* RECOLECCIÓN INFORMACIÓN PARA LA TABLA: sigmel_informacion_afiliado_eventos */
            
            // Evaluamos si selecciona la opción Otro/¿Cuál? del selector de tipo de documento
            if ($request->tipo_documento == 8) {
                
                $datos_otro_tipo_documento = [
                    'Tipo_lista' => 'Tipo de documento',
                    'Nombre_parametro' => $request->otro_nombre_documento,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otro_tipo_documento);
                $array_tipo_documento = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
                $tipo_documento = $array_tipo_documento['Id_Parametro'];
            } else {
                $tipo_documento = $request->tipo_documento;
            }
            
            // Evaluamos si selecciona la opción Otro/¿Cual? del selector de Estado civil
            if ($request->estado_civil == 14) {

                $datos_otro_estado_civil = [
                    'Tipo_lista' => 'Estado civil',
                    'Nombre_parametro' => $request->otro_estado_civil,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otro_estado_civil);
                $array_estado_civil = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
                $estado_civil = $array_estado_civil['Id_Parametro'];
            } else {
                $estado_civil = $request->estado_civil;
            }
            
            // Evaluamos si selecciona la opción Otro/¿Cual? del selector de Nivel escolar
            if ($request->nivel_escolar == 25) {
                
                $datos_otro_nivel_escolar = [
                    'Tipo_lista' => 'Nivel escolar',
                    'Nombre_parametro' => $request->otro_nivel_escolar,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otro_nivel_escolar);
                $array_nivel_escolar = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
                $nivel_escolar = $array_nivel_escolar['Id_Parametro'];
            } else {
                $nivel_escolar = $request->nivel_escolar;
            }
            
            // Evaluamos si selecciona la opción Si del selector Apoderado
            if ($request->apoderado == 'Si') {
                
                $nombre_apoderado = $request->nombre_apoderado;
                $nro_identificacion_apoderado = $request->nro_identificacion_apoderado;
            } else {
                $nombre_apoderado = "";
                $nro_identificacion_apoderado = "";
            }
            
            // Evaluamos si selecciona la opción de Exterior del selector Departamentos (Información afiliado)
            if ($request->departamento_info_afiliado == 33) {
                
                // Evaluamos si selecciona la opción de País? del selector Municipios (Información afiliado)
                if($request->municipio_info_afiliado == 1120){

                    $datos_pais_exterior = [
                    'Id_departamento' => 33,
                    'Nombre_departamento' => "Exterior",
                    'Nombre_municipio' => $request->pais_exterior_info_afiliado,
                    'Estado' => "activo",
                    'F_registro' => $date
                    ];

                    sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->insert($datos_pais_exterior);
                    $array_id_municipio = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->select('Id_municipios')->latest('Id_municipios')->first();
                    $id_municipio = $array_id_municipio['Id_municipios'];

                }else{
                    $id_municipio = $request->municipio_info_afiliado;
                }

            } else{
                $id_municipio = $request->municipio_info_afiliado;
            }
            
            // Evaluamos si selecciona la opción Otro/¿Cuál? del selector de Tipo de afiliado
            if ($request->tipo_afiliado == 29) {
                
                $datos_otro_tipo_afiliado = [
                    'Tipo_lista' => 'Tipo de Afiliado',
                    'Nombre_parametro' => $request->otro_tipo_afiliado,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otro_tipo_afiliado);
                $array_tipo_afiliado = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
                $tipo_afiliado = $array_tipo_afiliado['Id_Parametro'];

            } else {
                $tipo_afiliado = $request->tipo_afiliado;
            }

            // Evaluamos si selecciona la opción Otro/¿Cual? del selector de EPS
            if ($request->eps == 31) {

                $datos_otra_eps = [
                    'Nombre_eps' => $request->otra_eps,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_eps::on('sigmel_gestiones')->insert($datos_otra_eps);
                $array_id_eps = sigmel_lista_eps::on('sigmel_gestiones')->select('Id_Eps')->latest('Id_Eps')->first();
                $id_eps = $array_id_eps['Id_Eps'];

            } else {
                $id_eps = $request->eps;
            }

            // Evaluamos si selecciona la opción Otro/¿Cual? del selector de AFP
            if ($request->afp == 6) {

                $datos_otra_afp = [
                    'Nombre_afp' => $request->otra_afp,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_afps::on('sigmel_gestiones')->insert($datos_otra_afp);
                $array_id_afp = sigmel_lista_afps::on('sigmel_gestiones')->select('Id_Afp')->latest('Id_Afp')->first();
                $id_afp = $array_id_afp['Id_Afp'];

            } else {
                $id_afp = $request->afp;
            }

            // Evaluamos si selecciona la opción Otro/¿Cual? del selector de ARL
            if ($request->arl_info_afiliado == 10) {

                $datos_otra_arl = [
                    'Nombre_arl' => $request->otra_arl_info_afiliado,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_arls::on('sigmel_gestiones')->insert($datos_otra_arl);
                $array_id_arl = sigmel_lista_arls::on('sigmel_gestiones')->select('Id_Arl')->latest('Id_Arl')->first();
                $id_arl = $array_id_arl['Id_Arl'];

            } else {
                $id_arl = $request->arl_info_afiliado;
            }
            
            $datos_info_afiliado_evento = [
                'ID_evento' => $request->id_evento,
                'Nombre_afiliado' => $request->nombre_afiliado,
                'Tipo_documento' => $tipo_documento,
                'Nro_identificacion' => $request->nro_identificacion,
                'F_nacimiento' => $request->fecha_nacimiento,
                'Edad' => $request->edad,
                'Genero' => $request->genero,
                'Email' => $request->email_info_afiliado,
                'Telefono_contacto' =>  $request->telefono,
                'Estado_civil' => $estado_civil,
                'Nivel_escolar' => $nivel_escolar,
                'Apoderado'=> $request->apoderado,
                'Nombre_apoderado' => $nombre_apoderado,
                'Nro_identificacion_apoderado' => $nro_identificacion_apoderado,
                'Id_dominancia' => $request->dominancia,
                'Direccion' => $request->direccion_info_afiliado,
                'Id_departamento' => $request->departamento_info_afiliado,
                'Id_municipio' => $id_municipio,
                'Ocupacion' => $request->ocupacion,
                'Tipo_afiliado' => $tipo_afiliado,
                'Ibc' => $request->ibc,
                'Id_eps' => $id_eps,
                'Id_afp' => $id_afp,
                'Id_arl' => $id_arl,
                'Activo' => $request->activo,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];

            // Inserción de datos en la tabla sigmel_informacion_afiliado_eventos
            sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')->insert($datos_info_afiliado_evento);

            // colacamos un tiempo de retardo pequeño para que alcance a insertar los datos
            sleep(2);


            /* RECOLECCIÓN INFORMACIÓN PARA LA TABLA: sigmel_informacion_laboral_eventos */

            // Evaluamos si selecciona la opción Otro/¿Cuál? del selector arl (información laboral)
            if ($request->arl_info_laboral == 10) {
                
                $datos_otra_arl_info_laboral = [
                    'Nombre_arl' => $request->otra_arl_info_laboral,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_arls::on('sigmel_gestiones')->insert($datos_otra_arl_info_laboral);
                $array_otra_arl = sigmel_lista_arls::on('sigmel_gestiones')->select('Id_Arl')->latest('Id_Arl')->first();
                $otra_arl = $array_otra_arl['Id_Arl'];
            } else {
                $otra_arl = $request->arl_info_laboral;
            }

            if ($request->departamento_info_laboral == 33) {
                
                // Evaluamos si selecciona la opción de País? del selector Municipios (Información laboral)
                if($request->municipio_info_laboral == 1120){

                    $datos_pais_exterior = [
                    'Id_departamento' => 33,
                    'Nombre_departamento' => "Exterior",
                    'Nombre_municipio' => $request->pais_exterior_info_laboral,
                    'Estado' => "activo",
                    'F_registro' => $date
                    ];

                    sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->insert($datos_pais_exterior);
                    $array_id_municipio_laboral = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->select('Id_municipios')->latest('Id_municipios')->first();
                    $id_municipio_laboral = $array_id_municipio_laboral['Id_municipios'];
                }else{
                    $id_municipio_laboral = $request->municipio_info_laboral;
                }

            } else{
                $id_municipio_laboral = $request->municipio_info_laboral;
            }

            $datos_info_laboral_evento =[
                'ID_evento' => $request->id_evento,
                'Nro_identificacion' => $request->nro_identificacion,
                'Tipo_empleado' => $request->tipo_empleo,
                'Id_arl' => $otra_arl,
                'Empresa' => $request->empresa,
                'Nit_o_cc' => $request->nit_cc,
                'Telefono_empresa' => $request->telefono_empresa,
                'Email' => $request->email_info_laboral,
                'Direccion' => $request->direccion_info_laboral,
                'Id_departamento' => $request->departamento_info_laboral,
                'Id_municipio' => $id_municipio_laboral,
                'Id_actividad_economica' => $request->actividad_economica,
                'Id_clase_riesgo' => $request->clase_riesgo,
                'Persona_contacto' => $request->persona_contacto,
                'Telefono_persona_contacto' => $request->telefono_persona_contacto,
                'Id_codigo_ciuo' => $request->codigo_ciuo,
                'F_ingreso' => $request->fecha_ingreso,
                'Cargo' => $request->cargo,
                'Funciones_cargo' => $request->funciones_cargo,
                'Antiguedad_empresa' => $request->antiguedad_empresa,
                'Antiguedad_cargo_empresa' => $request->antiguedad_cargo,
                'F_retiro' => $request->fecha_retiro,
                'Descripcion' => $request->descripcion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];

            // Inserción de datos en la tabla sigmel_informacion_laboral_eventos
            sigmel_informacion_laboral_eventos::on('sigmel_gestiones')->insert($datos_info_laboral_evento);

            // colacamos un tiempo de retardo pequeño para que alcance a insertar los datos
            sleep(2);

            /* RECOLECCIÓN INFORMACIÓN PARA LA TABLA: sigmel_informacion_pericial_eventos */

            // Evaluamos si selecciona la opción Otro/¿Cual? del selector de Solicitante
            if($request->solicitante == 8){

                $id_solicitante_actual = sigmel_lista_solicitantes::on('sigmel_gestiones')
                ->select('Id_solicitante')->max('Id_solicitante');

                $id_solicitante_nuevo = $id_solicitante_actual + 1;

                $datos_otro_solicitante = [
                    'Id_solicitante' => $id_solicitante_nuevo,
                    'Solicitante' => $request->otro_solicitante,
                    'Nombre_solicitante' => "",
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_solicitantes::on('sigmel_gestiones')->insert($datos_otro_solicitante);
                $array_id_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')->select('Id_solicitante')->latest('Id_solicitante')->first();
                $id_solicitante = $array_id_solicitante['Id_solicitante'];
            }else{
                $id_solicitante = $request->solicitante;
            }

            // Evaluamos si selecciona la opción Otro/¿Cual? del selector de Nombre Solicitante

            $id_nombre_solicitante_analizar = $request->nombre_solicitante;

            switch($id_nombre_solicitante_analizar)
            {
                case 10:
                    $datos_otro_nombre_solicitante = [
                        'Id_solicitante' => 1,
                        'Solicitante' => 'ARL',
                        'Nombre_solicitante' => $request->otro_nombre_solicitante,
                        'Estado' => 'activo',
                        'F_registro' => $date
                    ];

                    sigmel_lista_solicitantes::on('sigmel_gestiones')->insert($datos_otro_nombre_solicitante);
                    $array_id_nombre_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')->select('Id_Nombre_solicitante')->latest('Id_Nombre_solicitante')->first();
                    $id_nombre_solicitante = $array_id_nombre_solicitante['Id_Nombre_solicitante'];
                break;
                case 16:
                    $datos_otro_nombre_solicitante = [
                        'Id_solicitante' => 2,
                        'Solicitante' => 'AFP',
                        'Nombre_solicitante' => $request->otro_nombre_solicitante,
                        'Estado' => 'activo',
                        'F_registro' => $date
                    ];

                    sigmel_lista_solicitantes::on('sigmel_gestiones')->insert($datos_otro_nombre_solicitante);
                    $array_id_nombre_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')->select('Id_Nombre_solicitante')->latest('Id_Nombre_solicitante')->first();
                    $id_nombre_solicitante = $array_id_nombre_solicitante['Id_Nombre_solicitante'];
                break;
                case 47:
                    $datos_otro_nombre_solicitante = [
                        'Id_solicitante' => 3,
                        'Solicitante' => 'EPS',
                        'Nombre_solicitante' => $request->otro_nombre_solicitante,
                        'Estado' => 'activo',
                        'F_registro' => $date
                    ];

                    sigmel_lista_solicitantes::on('sigmel_gestiones')->insert($datos_otro_nombre_solicitante);
                    $array_id_nombre_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')->select('Id_Nombre_solicitante')->latest('Id_Nombre_solicitante')->first();
                    $id_nombre_solicitante = $array_id_nombre_solicitante['Id_Nombre_solicitante'];
                break;
                default;
                    $id_nombre_solicitante = $request->nombre_solicitante;
                break;
            }


            // Evaluamos si selecciona la opción Otro/¿Cual? del selector de Fuente de Información
            if ($request->fuente_informacion == 42) {
                
                $datos_otra_fuente_informacion = [
                    'Tipo_lista' => 'Fuente de informacion',
                    'Nombre_parametro' => $request->otra_fuente_informacion,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otra_fuente_informacion);
                $array_fuente_informacion = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
                $fuente_informacion = $array_fuente_informacion['Id_Parametro'];
                
            } else {
                $fuente_informacion = $request->fuente_informacion;
            }


            $datos_info_pericial_evento = [
                'ID_evento' => $request->id_evento,
                'Id_motivo_solicitud' => $request->motivo_solicitud,
                'Tipo_vinculacion' => $request->tipovinculo,
                'Regimen_salud' => $request->regimen,
                'Id_solicitante' => $id_solicitante,
                'Id_nombre_solicitante' => $id_nombre_solicitante,
                'Fuente_informacion' => $fuente_informacion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];

            // Inserción de datos en la tabla sigmel_informacion_pericial_eventos
            sigmel_informacion_pericial_eventos::on('sigmel_gestiones')->insert($datos_info_pericial_evento);

            // colacamos un tiempo de retardo pequeño para que alcance a insertar los datos
            sleep(2);

            /* RECOLECCIÓN INFORMACIÓN PARA LA TABLA: sigmel_informacion_asignacion_eventos */
            $datos_info_asignacion_evento =[
                'ID_evento' => $request->id_evento,
                'Id_proceso' => $request->proceso,
                'Id_servicio' => $request->servicio,
                'Id_accion' => $request->accion,
                'Descripcion' => $request->descripcion_asignacion,
                'F_alerta' => $request->fecha_alerta,
                'Id_Estado_evento' => 1,
                'F_radicacion' => $request->fecha_radicacion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date
            ];

            // Inserción de datos en la tabla sigmel_informacion_asignacion_eventos
            sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->insert($datos_info_asignacion_evento);

            // colacamos un tiempo de retardo pequeño para que alcance a insertar los datos
            sleep(2);

            /* RECOLECCIÓN INFORMACIÓN PARA LA TABLA: sigmel_historial_acciones_eventos */
            $datos_historial_acciones = [
                'ID_evento' => $request->id_evento,
                'F_accion' => $date,
                'Nombre_usuario' => $nombre_usuario,
                'Accion_realizada' => "Creación de evento.",
                'Descripcion' => $request->descripcion_asignacion,
            ];

            sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_historial_acciones);
            sleep(2);
            return back()->with('mensaje_confirmacion_nuevo_evento', 'Evento creado correctamente');
        }
        
    }

    public function ConsultaFechaNroIdent(Request $request){

        $numero_ident_afiliado = $request->numero_ident_afiliado;
        $fecha_evento = $request->fecha_evento;

        // extraemos todos los ID EVENTO acorde al numero de identificación del afiliado
        $array_id_evento_buscado = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->select('ID_evento')->where('Nro_identificacion', $numero_ident_afiliado)->get();

        if (!empty($array_id_evento_buscado)) {

            /* realizamos una subconsulta para determinar si ya existe un
            evento acorde a la fecha de evento del formulario y todos los ID Evento posibles */

            $array_fecha_evento_db = sigmel_informacion_eventos::on('sigmel_gestiones')
            ->select('F_evento')
            ->where([
                ['F_evento', '=', $fecha_evento]
            ])
            ->whereIn('ID_evento', $array_id_evento_buscado)->first();

            /* Si el arreglo no viene vacio quiere decir que ya hay un evento creado con la
            fecha de evento que se captura del formulario */
            if(!empty($array_fecha_evento_db)){
                $fecha_evento_db =  $array_fecha_evento_db['F_evento'];
                echo $fecha_evento_db;
            }

            // $array_informacion_afiliado = sigmel_informacion_eventos::on('sigmel_gestiones')
            // ->select('Nombre_afiliado', 'Direccion');

            
        }
    }

    public function llenarDatosInfoAfiliado(Request $request){

        $array_datos_info_afiliado = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_tipo_doc', 'siae.Tipo_documento', '=', 'slp_tipo_doc.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_tipo_genero', 'siae.Genero', '=', 'slp_tipo_genero.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_estado_civil', 'siae.Estado_civil', '=', 'slp_estado_civil.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_nivel_escolar', 'siae.Nivel_escolar', '=', 'slp_nivel_escolar.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_dominancias as sld', 'siae.Id_dominancia', '=', 'sld.Id_Dominancia')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'siae.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sldm1.Id_municipios', '=', 'siae.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_tipo_afiliado', 'siae.Tipo_afiliado', '=', 'slp_tipo_afiliado.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_eps as sle', 'sle.Id_Eps', '=', 'siae.Id_eps')
        ->leftJoin('sigmel_gestiones.sigmel_lista_afps as slafp', 'slafp.Id_Afp', '=', 'siae.Id_afp')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as slarl', 'slarl.Id_Arl', '=', 'siae.Id_arl')
        ->select(
            'siae.ID_evento',
            'siae.Id_Afiliado',
            'siae.Nombre_afiliado',
            'siae.Direccion',
            'siae.Tipo_documento',
            'slp_tipo_doc.Nombre_parametro as Nombre_documento',
            'siae.Nro_identificacion',
            'siae.F_nacimiento',
            'siae.Edad',
            'siae.Genero',
            'slp_tipo_genero.Nombre_parametro as Nombre_genero',
            'siae.Email',
            'siae.Telefono_contacto',
            'siae.Estado_civil',
            'slp_estado_civil.Nombre_parametro as Nombre_estado_civil',
            'Nivel_escolar',
            'slp_nivel_escolar.Nombre_parametro as Nombre_nivel_escolar',
            'sld.Id_dominancia',
            'sld.Nombre_dominancia as Dominancia',
            'siae.Id_departamento',
            'sldm.Nombre_departamento',
            'siae.Id_municipio',
            'sldm1.Nombre_municipio',
            'siae.Ocupacion',
            'siae.Tipo_afiliado',
            'slp_tipo_afiliado.Nombre_parametro as Nombre_tipo_afiliado',
            'siae.Ibc',
            'siae.Id_eps',
            'sle.Nombre_eps',
            'siae.Id_afp',
            'slafp.Nombre_afp',
            'siae.Id_arl',
            'slarl.Nombre_arl',
            'siae.Apoderado',
            'siae.Nombre_apoderado',
            'siae.Nro_identificacion_apoderado',
            'siae.Activo'
        )
        ->where([
            ['siae.Nro_identificacion', '=', $request->numero_ident_afiliado]
        ])
        ->orderBy('siae.F_registro', 'desc')
        ->limit(1)
        ->get();

        $informacion_afiliado = json_decode(json_encode($array_datos_info_afiliado, true));
        return response()->json($informacion_afiliado);
    }

    public function llenarDatosInfoLaboral(Request $request){

        $array_datos_laboral = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as slarl', 'slarl.Id_Arl', '=', 'sile.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'sile.Id_departamento') 
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sldm1.Id_municipios', '=', 'sile.Id_municipio') 
        ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'sile.Id_actividad_economica')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'sile.Id_clase_riesgo')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
        ->select(
            'sile.Tipo_empleado',
            'sile.Id_arl',
            'slarl.Nombre_arl',
            'sile.Empresa',
            'sile.Nit_o_cc',
            'sile.Telefono_empresa',
            'sile.Email',
            'sile.Direccion',
            'sile.Id_departamento',
            'sldm.Nombre_departamento',
            'sile.Id_municipio',
            'sldm1.Nombre_municipio',
            'sile.Id_actividad_economica',
            'slae.id_codigo',
            'slae.Nombre_actividad',
            'sile.Id_clase_riesgo',
            'slcr.Nombre_riesgo',
            'sile.Persona_contacto',
            'sile.Telefono_persona_contacto',
            'sile.Id_codigo_ciuo',
            'slcc.id_codigo_ciuo',
            'slcc.Nombre_ciuo',
            'sile.F_ingreso',
            'sile.Cargo',
            'sile.Funciones_cargo',
            'sile.Antiguedad_empresa',
            'sile.Antiguedad_cargo_empresa',
            'sile.F_retiro',
            'sile.Descripcion'
        )
        ->where([
            ['sile.Nro_identificacion', '=', $request->numero_ident_laboral]
        ])
        ->orderBy('sile.F_registro', 'desc')
        ->limit(1)
        ->get();

        $informacion_laboral = json_decode(json_encode($array_datos_laboral, true));
        return response()->json($informacion_laboral);
    }


    /* TODO LO REFERENTE AL FORMULARIO DE EDICIÓN DE EVENTO */
    public function ConsultaIDEvento(Request $request){
        $idEventodigitado = $request->IdEvento;
        //consultar el id evento en la DB

        $arrayEventoconsultado = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->select('ID_evento','F_evento','F_radicacion')->where('ID_evento', $idEventodigitado)->get();

        $info_EventoConsultado= json_decode(json_encode($arrayEventoconsultado, true));
        return response()->json(($info_EventoConsultado));
    }

    public function mostrarVistaEdicionInicialNuevo(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $newIdEvento = $request->newIdEvento;
        $parametro = $request->parametro;

        $array_datos_info_evento =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_eventos as sie')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clientes as slc', 'sie.Cliente', '=', 'slc.Id_Cliente')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_clientes as sltc', 'sie.Tipo_cliente', '=', 'sltc.Id_TipoCliente')
        ->leftJoin('sigmel_gestiones.sigmel_lista_tipo_eventos as slte', 'sie.Tipo_evento', '=', 'slte.Id_Evento')
        ->select('sie.Cliente', 'slc.Nombre_cliente', 'sie.Tipo_cliente', 'sltc.Nombre_tipo_cliente', 'sie.Tipo_evento',
        'slte.Nombre_evento', 'sie.ID_evento', 'sie.F_evento', 'sie.F_radicacion')
        ->where([['sie.ID_evento', '=', $newIdEvento]])->get();  
        
        $array_datos_info_afiliados =DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_afiliado_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_tipo_doc', 'siae.Tipo_documento', '=', 'slp_tipo_doc.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_tipo_genero', 'siae.Genero', '=', 'slp_tipo_genero.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_estado_civil', 'siae.Estado_civil', '=', 'slp_estado_civil.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_nivel_escolar', 'siae.Nivel_escolar', '=', 'slp_nivel_escolar.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_dominancias as sld', 'siae.Id_dominancia', '=', 'sld.Id_Dominancia')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'siae.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sldm1.Id_municipios', '=', 'siae.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp_tipo_afiliado', 'siae.Tipo_afiliado', '=', 'slp_tipo_afiliado.Id_Parametro')
        ->leftJoin('sigmel_gestiones.sigmel_lista_eps as sle', 'sle.Id_Eps', '=', 'siae.Id_eps')
        ->leftJoin('sigmel_gestiones.sigmel_lista_afps as slafp', 'slafp.Id_Afp', '=', 'siae.Id_afp')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as slarl', 'slarl.Id_Arl', '=', 'siae.Id_arl')
        ->select('siae.Id_Afiliado', 'siae.ID_evento', 'siae.F_registro', 'siae.Nombre_afiliado', 'siae.Direccion', 'siae.Tipo_documento',
        'slp_tipo_doc.Nombre_parametro as Nombre_documento', 'siae.Nro_identificacion', 'siae.F_nacimiento', 'siae.Edad', 'siae.Genero',
        'slp_tipo_genero.Nombre_parametro as Nombre_genero', 'siae.Email', 'siae.Telefono_contacto', 'siae.Estado_civil',
        'slp_estado_civil.Nombre_parametro as Nombre_estado_civil', 'siae.Nivel_escolar', 'slp_nivel_escolar.Nombre_parametro as Nombre_nivel_escolar',
        'sld.Id_dominancia', 'sld.Nombre_dominancia as Dominancia', 'siae.Id_departamento', 'sldm.Nombre_departamento',
        'siae.Id_municipio', 'sldm1.Nombre_municipio', 'siae.Ocupacion', 'siae.Tipo_afiliado', 'slp_tipo_afiliado.Nombre_parametro as Nombre_tipo_afiliado',
        'siae.Ibc', 'siae.Id_eps', 'sle.Nombre_eps', 'slafp.Id_Afp', 'slafp.Nombre_afp', 'slarl.Id_Arl', 'slarl.Nombre_arl',
        'siae.Apoderado', 'siae.Nombre_apoderado', 'siae.Nro_identificacion_apoderado', 'siae.Activo')
        ->where([['siae.ID_evento','=',$newIdEvento]])
        ->orderBy('siae.F_registro', 'desc')
        ->limit(1)
        ->get();

        $array_datos_info_laboral=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_laboral_eventos as sile')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as sla', 'sla.Id_arl', '=', 'sile.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'sile.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldms', 'sldms.Id_municipios', '=', 'sile.Id_municipio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'sile.Id_actividad_economica')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'sile.Id_clase_riesgo')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'sile.Id_codigo_ciuo')
        ->select('sile.ID_evento', 'sile.Tipo_empleado','sile.Id_arl', 'sla.Nombre_arl', 'sile.Empresa', 'sile.Nit_o_cc', 'sile.Telefono_empresa',
        'sile.Email', 'sile.Direccion', 'sile.Id_departamento', 'sldm.Nombre_departamento', 'sile.Id_municipio', 
        'sldms.Nombre_municipio', 'sile.Id_actividad_economica', 'slae.Nombre_actividad', 'sile.Id_clase_riesgo', 
        'slcr.Nombre_riesgo', 'sile.Persona_contacto', 'sile.Telefono_persona_contacto', 'sile.Id_codigo_ciuo', 'slcc.Nombre_ciuo', 
        'sile.F_ingreso', 'sile.Cargo', 'sile.Funciones_cargo', 'sile.Antiguedad_empresa', 'sile.Antiguedad_cargo_empresa', 
        'sile.F_retiro', 'sile.Descripcion')
        ->where([['sile.ID_evento','=', $newIdEvento]])
        ->orderBy('sile.F_registro', 'desc')
        ->limit(1)
        ->get();

        $array_datos_info_pericial=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_pericial_eventos as sipe')
        ->leftJoin('sigmel_gestiones.sigmel_lista_motivo_solicitudes as slms', 'slms.Id_Solicitud', '=', 'sipe.Id_motivo_solicitud')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'slp.Id_Parametro', '=', 'sipe.Tipo_vinculacion')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slps', 'slps.Id_Parametro', '=', 'sipe.Regimen_salud')
        ->leftJoin('sigmel_gestiones.sigmel_lista_solicitantes as sls', 'sls.Id_solicitante', '=', 'sipe.Id_solicitante')
        ->leftJoin('sigmel_gestiones.sigmel_lista_solicitantes as slsn', 'slsn.Id_nombre_solicitante', '=', 'sipe.Id_nombre_solicitante')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slpf', 'slpf.Id_Parametro', '=', 'sipe.Fuente_informacion')
        ->select('sipe.ID_evento', 'sipe.Id_motivo_solicitud', 'slms.Nombre_solicitud', 'sipe.Tipo_vinculacion', 'slp.Nombre_parametro as tipo_viculacion',
        'sipe.Regimen_salud', 'slps.Nombre_parametro as regimen_salud', 'sipe.Id_solicitante', 'sls.Solicitante', 'sipe.Id_nombre_solicitante',
        'slsn.Nombre_solicitante', 'sipe.Fuente_informacion', 'slpf.Nombre_parametro as fuente_informacion')
        ->where([['sipe.ID_evento','=', $newIdEvento]])
        ->orderBy('sipe.F_registro', 'desc')
        ->limit(1)
        ->get();

        /* $array_datos_info_asignacion=DB::table(getDatabaseName('sigmel_gestiones') . 'sigmel_informacion_asignacion_eventos as siae')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slps', 'slps.Id_proceso', '=', 'siae.Id_proceso')
        ->leftJoin('sigmel_gestiones.sigmel_lista_procesos_servicios as slpss', 'slpss.Id_servicio', '=', 'siae.Id_servicio')
        ->leftJoin('sigmel_gestiones.sigmel_lista_acciones_procesos_servicios as slaps', 'slaps.Id_Accion', '=', 'siae.Id_accion')
        ->select('siae.ID_evento', 'siae.Id_proceso', 'slps.Nombre_proceso', 'siae.Id_servicio', 'slpss.Nombre_servicio',
        'siae.Id_accion', 'slaps.Nombre_accion', 'siae.Descripcion', 'siae.F_alerta')
        ->where([['siae.ID_evento', '=', $newIdEvento]])
        ->orderBy('siae.F_registro', 'Desc')
        ->limit(1)
        ->get(); */          

        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento)); 
        
        return view('administrador.gestionInicialEdicion', compact('user', 'array_datos_info_evento', 'array_datos_info_afiliados',
        'array_datos_info_laboral', 'array_datos_info_pericial', 'arraylistado_documentos'));

    }

    public function actualizarGestionInicial(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        $IdEventoactulizar = $request->id_evento_enviar;
        
        /* Actualizacion tabla sigmel_informacion_eventos */

        //validacion del otro/cual? en el tipo de cliente 

        if ($request->tipo_cliente == 4) {
                
            $datos_otro_tipo_cliente_edicion = [
                'Nombre_tipo_cliente' => $request->otro_tipo_cliente,
                'Estado' => 'activo',
                'F_registro' => $date
            ];
            sigmel_lista_tipo_clientes::on('sigmel_gestiones')->insert($datos_otro_tipo_cliente_edicion);
            $array_tipo_cliente_edicion = sigmel_lista_tipo_clientes::on('sigmel_gestiones')->select('Id_TipoCliente')->latest('Id_TipoCliente')->first();
            $tipo_cliente = $array_tipo_cliente_edicion['Id_TipoCliente'];
        } else {
            $tipo_cliente = $request->tipo_cliente;
        } 

        
        $actualizar_GestionInicialEvento = [
            'Cliente' => $request->cliente,
            'Tipo_cliente' => $tipo_cliente,
            'Tipo_evento' => $request->tipo_evento,
            'F_evento' => $request->fecha_evento,
            'F_radicacion' => $request->fecha_radicacion,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];      
        
        
        $eventoActualizar = sigmel_informacion_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $IdEventoactulizar)->firstOrFail();
        $eventoActualizar->fill($actualizar_GestionInicialEvento);
        $eventoActualizar->save();

        
        sleep(2);

        /* Actualizacion tabla sigmel_informacion_afiliado_eventos */

        // validacion si selecciona la opción Otro/¿Cual? del selector de Tipo de documento

        if ($request->tipo_documento == 8) {
                
            $datos_otro_tipo_documento_edicion = [
                'Tipo_lista' => 'Tipo de documento',
                'Nombre_parametro' => $request->otro_nombre_documento,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otro_tipo_documento_edicion);
            $array_tipo_documento_edicion = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
            $tipo_documento = $array_tipo_documento_edicion['Id_Parametro'];
        } else {
            $tipo_documento = $request->tipo_documento;
        }
        
        // validacion si selecciona la opción Otro/¿Cual? del selector de Estado civil
        if ($request->estado_civil == 14) {

            $datos_otro_estado_civil_edicion = [
                'Tipo_lista' => 'Estado civil',
                'Nombre_parametro' => $request->otro_estado_civil,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otro_estado_civil_edicion);
            $array_estado_civil_edicion = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
            $estado_civil = $array_estado_civil_edicion['Id_Parametro'];
        } else {
            $estado_civil = $request->estado_civil;
        }
        
        // validacion si selecciona la opción Otro/¿Cual? del selector de Nivel escolar
        if ($request->nivel_escolar == 25) {
            
            $datos_otro_nivel_escolar_edicion = [
                'Tipo_lista' => 'Nivel escolar',
                'Nombre_parametro' => $request->otro_nivel_escolar,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otro_nivel_escolar_edicion);
            $array_nivel_escolar_edicion = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
            $nivel_escolar = $array_nivel_escolar_edicion['Id_Parametro'];
        } else {
            $nivel_escolar = $request->nivel_escolar;
        }
        
        // validacion si selecciona la opción Si del selector Apoderado
        if ($request->apoderado == 'Si') {
            
            $nombre_apoderado = $request->nombre_apoderado;
            $nro_identificacion_apoderado = $request->nro_identificacion_apoderado;
        } else {
            $nombre_apoderado = "";
            $nro_identificacion_apoderado = "";
        }
        
        // validacion si selecciona la opción de Exterior del selector Departamentos (Información afiliado)
        if ($request->departamento_info_afiliado == 33) {
            
            // validacion si selecciona la opción de País? del selector Municipios (Información afiliado)
            if($request->municipio_info_afiliado == 1120){

                $datos_pais_exterior_edicion = [
                'Id_departamento' => 33,
                'Nombre_departamento' => "Exterior",
                'Nombre_municipio' => $request->pais_exterior_info_afiliado,
                'Estado' => "activo",
                'F_registro' => $date
                ];

                sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->insert($datos_pais_exterior_edicion);
                $array_id_municipio_edicion = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->select('Id_municipios')->latest('Id_municipios')->first();
                $id_municipio = $array_id_municipio_edicion['Id_municipios'];

            }else{
                $id_municipio = $request->municipio_info_afiliado;
            }

        } else{
            $id_municipio = $request->municipio_info_afiliado;
        }
        
        // validacion si selecciona la opción Otro/¿Cuál? del selector de Tipo de afiliado
        if ($request->tipo_afiliado == 29) {
            
            $datos_otro_tipo_afiliado_edicion = [
                'Tipo_lista' => 'Tipo de Afiliado',
                'Nombre_parametro' => $request->otro_tipo_afiliado,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otro_tipo_afiliado_edicion);
            $array_tipo_afiliado_edicion = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
            $tipo_afiliado = $array_tipo_afiliado_edicion['Id_Parametro'];

        } else {
            $tipo_afiliado = $request->tipo_afiliado;
        }

        // validacion si selecciona la opción Otro/¿Cual? del selector de EPS
        if ($request->eps == 31) {

            $datos_otra_eps_edicion = [
                'Nombre_eps' => $request->otra_eps,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_eps::on('sigmel_gestiones')->insert($datos_otra_eps_edicion);
            $array_id_eps_edicion = sigmel_lista_eps::on('sigmel_gestiones')->select('Id_Eps')->latest('Id_Eps')->first();
            $id_eps = $array_id_eps_edicion['Id_Eps'];

        } else {
            $id_eps = $request->eps;
        }

        // validacion si selecciona la opción Otro/¿Cual? del selector de AFP
        if ($request->afp == 6) {

            $datos_otra_afp_edicion = [
                'Nombre_afp' => $request->otra_afp,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_afps::on('sigmel_gestiones')->insert($datos_otra_afp_edicion);
            $array_id_afp_edicion = sigmel_lista_afps::on('sigmel_gestiones')->select('Id_Afp')->latest('Id_Afp')->first();
            $id_afp = $array_id_afp_edicion['Id_Afp'];

        } else {
            $id_afp = $request->afp;
        }

        // validacion si selecciona la opción Otro/¿Cual? del selector de ARL
        if ($request->arl_info_afiliado == 10) {

            $datos_otra_arl_edicion = [
                'Nombre_arl' => $request->otra_arl_info_afiliado,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_arls::on('sigmel_gestiones')->insert($datos_otra_arl_edicion);
            $array_id_arl_edicion = sigmel_lista_arls::on('sigmel_gestiones')->select('Id_Arl')->latest('Id_Arl')->first();
            $id_arl = $array_id_arl_edicion['Id_Arl'];

        } else {
            $id_arl = $request->arl_info_afiliado;
        }

        $actualizar_GestionInicialAfiliado = [
            'Nombre_afiliado' => $request->nombre_afiliado,
            'Tipo_documento' => $tipo_documento,
            'Nro_identificacion' => $request->nro_identificacion_enviar,
            'F_nacimiento' => $request->fecha_nacimiento,
            'Edad' => $request->edad,
            'Genero' => $request->genero,
            'Email' => $request->email_info_afiliado,
            'Telefono_contacto' => $request->telefono,
            'Estado_civil' => $estado_civil,
            'Nivel_escolar' => $nivel_escolar,
            'Apoderado' => $request->apoderado,
            'Nombre_apoderado' => $nombre_apoderado,
            'Nro_identificacion_apoderado' => $nro_identificacion_apoderado,
            'Id_dominancia' => $request->dominancia,
            'Direccion' => $request->direccion_info_afiliado,
            'Id_departamento' => $request->departamento_info_afiliado,
            'Id_municipio' => $id_municipio,
            'Ocupacion' => $request->ocupacion,
            'Tipo_afiliado' => $tipo_afiliado,
            'Ibc' => $request->ibc,
            'Id_eps' => $id_eps,
            'Id_afp' => $id_afp,
            'Id_arl' => $id_arl,
            'Activo' => $request->activo,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];        

        $afiliadoActualizar = sigmel_informacion_afiliado_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $IdEventoactulizar)->firstOrFail();
        $afiliadoActualizar->fill($actualizar_GestionInicialAfiliado);
        $afiliadoActualizar->save();

        sleep(2);

        /* Actualizacion tabla sigmel_informacion_laboral_eventos */

        // validacion si selecciona la opción Otro/¿Cual? del selector de ARL

        if ($request->arl_info_laboral == 10) {
                
            $datos_otra_arl_info_laboral_edicion = [
                'Nombre_arl' => $request->otra_arl_info_laboral,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_arls::on('sigmel_gestiones')->insert($datos_otra_arl_info_laboral_edicion);
            $array_otra_arl_edicion = sigmel_lista_arls::on('sigmel_gestiones')->select('Id_Arl')->latest('Id_Arl')->first();
            $otra_arl = $array_otra_arl_edicion['Id_Arl'];
        } else {
            $otra_arl = $request->arl_info_laboral;
        }

        if ($request->departamento_info_laboral == 33) {
            
            // validacion si selecciona la opción de País? del selector Municipios (Información laboral)
            if($request->municipio_info_laboral == 1120){

                $datos_pais_exterior_edicion_laboral = [
                'Id_departamento' => 33,
                'Nombre_departamento' => "Exterior",
                'Nombre_municipio' => $request->pais_exterior_info_laboral,
                'Estado' => "activo",
                'F_registro' => $date
                ];

                sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->insert($datos_pais_exterior_edicion_laboral);
                $array_id_municipio_laboral_edicion = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->select('Id_municipios')->latest('Id_municipios')->first();
                $id_municipio_laboral = $array_id_municipio_laboral_edicion['Id_municipios'];
            }else{
                $id_municipio_laboral = $request->municipio_info_laboral;
            }

        } else{
            $id_municipio_laboral = $request->municipio_info_laboral;
        }

        $actualizar_GestionInicialLaboral = [
            'Nro_identificacion' => $request->nro_identificacion_enviar,
            'Tipo_empleado' => $request->tipo_empleo,
            'Id_arl' => $otra_arl,
            'Empresa' => $request->empresa,
            'Nit_o_cc' => $request->nit_cc,
            'Telefono_empresa' => $request->telefono_empresa,
            'Email' => $request->email_info_laboral,
            'Direccion' => $request->direccion_info_laboral,
            'Id_departamento' => $request->departamento_info_laboral,
            'Id_municipio' => $id_municipio_laboral,
            'Id_actividad_economica' => $request->actividad_economica,
            'Id_clase_riesgo' => $request->clase_riesgo,
            'Persona_contacto' => $request->persona_contacto,
            'Telefono_persona_contacto' => $request->telefono_persona_contacto,
            'Id_codigo_ciuo' => $request->codigo_ciuo,
            'F_ingreso' => $request->fecha_ingreso,
            'Cargo' => $request->cargo,
            'Funciones_cargo' => $request->funciones_cargo,
            'Antiguedad_empresa' => $request->antiguedad_empresa,
            'Antiguedad_cargo_empresa' => $request->antiguedad_cargo,
            'F_retiro' => $request->fecha_retiro,
            'Descripcion' => $request->descripcion,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];

         
        // Se recibe los datos como un objeto
        $ActualInformacionLaboral = sigmel_informacion_laboral_eventos::on('sigmel_gestiones')
        ->select('Nro_identificacion','Tipo_empleado', 'Id_arl', 'Empresa', 'Nit_o_cc', 'Telefono_empresa', 'Email', 'Direccion', 
        'Id_departamento', 'Id_municipio', 'Id_actividad_economica', 'Id_clase_riesgo', 'Persona_contacto', 
        'Telefono_persona_contacto', 'Id_codigo_ciuo', 'F_ingreso', 'Cargo', 'Funciones_cargo', 'Antiguedad_empresa', 
        'Antiguedad_cargo_empresa', 'F_retiro', 'Descripcion', 'Nombre_usuario', 'F_registro')
        ->where('ID_evento', $IdEventoactulizar)->get();

        // Se crea el array
        $arrayActualInformacionLaboral= json_decode(json_encode($ActualInformacionLaboral, true));

        //Convertir el std objet a array
        function convert_object_to_array($data) {

            if (is_object($data)) {
                $data = get_object_vars($data);
            }
        
            if (is_array($data)) {
                return array_map(__FUNCTION__, $data);
            }
            else {
                return $data;
            }
        }

        $objetoconvertido = convert_object_to_array($arrayActualInformacionLaboral[0]);
        //echo 'actual'.'<br>';
        /* echo '<pre>';
        print_r($objetoconvertido['Tipo_empleado']);
        echo '</pre>'; */
        //echo 'nuevo'.'<br>';
        //print_r($actualizar_GestionInicialLaboral);
        $resultado = array_intersect($objetoconvertido, $actualizar_GestionInicialLaboral);        
        $cantidadarray = sizeof($resultado);

        if ($objetoconvertido['Tipo_empleado'] == 'Independiente' || $objetoconvertido['Tipo_empleado'] == 'Beneficiario') {
            $laboralActualizar = sigmel_informacion_laboral_eventos::on('sigmel_gestiones')
            ->where('ID_evento', $IdEventoactulizar)->firstOrFail();
            $laboralActualizar->fill($actualizar_GestionInicialLaboral);
            $laboralActualizar->save();              
        }else{
            //Si los array son iguales no se guarda en la tabla historico            
            if ($cantidadarray == 24) {    
                $laboralActualizar = sigmel_informacion_laboral_eventos::on('sigmel_gestiones')
                ->where('ID_evento', $IdEventoactulizar)->firstOrFail();
                $laboralActualizar->fill($actualizar_GestionInicialLaboral);
                $laboralActualizar->save();         
    
            }else{    
                sigmel_historico_empresas_afiliados::on('sigmel_gestiones')->insert($objetoconvertido);    
                sleep(2);    
                $laboralActualizar = sigmel_informacion_laboral_eventos::on('sigmel_gestiones')
                ->where('ID_evento', $IdEventoactulizar)->firstOrFail();
                $laboralActualizar->fill($actualizar_GestionInicialLaboral);
                $laboralActualizar->save(); 
    
                sleep(2);
            }
        }        

        /* Actualizacion tabla sigmel_informacion_pericial_eventos */

        // validacion si selecciona la opción de otro/cual? del selector solicitante

        if($request->solicitante == 8){

            $id_solicitante_actual = sigmel_lista_solicitantes::on('sigmel_gestiones')
            ->select('Id_solicitante')->max('Id_solicitante');

            $id_solicitante_nuevo = $id_solicitante_actual + 1;

            $datos_otro_solicitante_edicion = [
                'Id_solicitante' => $id_solicitante_nuevo,
                'Solicitante' => $request->otro_solicitante,
                'Nombre_solicitante' => "",
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_solicitantes::on('sigmel_gestiones')->insert($datos_otro_solicitante_edicion);
            $array_id_solicitante_edicion = sigmel_lista_solicitantes::on('sigmel_gestiones')->select('Id_solicitante')->latest('Id_solicitante')->first();
            $id_solicitante = $array_id_solicitante_edicion['Id_solicitante'];
        }else{
            $id_solicitante = $request->solicitante;
        }

        // validacion si selecciona la opción Otro/¿Cual? del selector de Nombre Solicitante

        $id_nombre_solicitante_analizar = $request->nombre_solicitante;

        switch($id_nombre_solicitante_analizar)
        {
            case 10:
                $datos_otro_nombre_solicitante_edicion = [
                    'Id_solicitante' => 1,
                    'Solicitante' => 'ARL',
                    'Nombre_solicitante' => $request->otro_nombre_solicitante,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_solicitantes::on('sigmel_gestiones')->insert($datos_otro_nombre_solicitante_edicion);
                $array_id_nombre_solicitante_edicion = sigmel_lista_solicitantes::on('sigmel_gestiones')->select('Id_Nombre_solicitante')->latest('Id_Nombre_solicitante')->first();
                $id_nombre_solicitante = $array_id_nombre_solicitante_edicion['Id_Nombre_solicitante'];
            break;
            case 16:
                $datos_otro_nombre_solicitante_edicion = [
                    'Id_solicitante' => 2,
                    'Solicitante' => 'AFP',
                    'Nombre_solicitante' => $request->otro_nombre_solicitante,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_solicitantes::on('sigmel_gestiones')->insert($datos_otro_nombre_solicitante_edicion);
                $array_id_nombre_solicitante_edicion = sigmel_lista_solicitantes::on('sigmel_gestiones')->select('Id_Nombre_solicitante')->latest('Id_Nombre_solicitante')->first();
                $id_nombre_solicitante = $array_id_nombre_solicitante_edicion['Id_Nombre_solicitante'];
            break;
            case 47:
                $datos_otro_nombre_solicitante_edicion = [
                    'Id_solicitante' => 3,
                    'Solicitante' => 'EPS',
                    'Nombre_solicitante' => $request->otro_nombre_solicitante,
                    'Estado' => 'activo',
                    'F_registro' => $date
                ];

                sigmel_lista_solicitantes::on('sigmel_gestiones')->insert($datos_otro_nombre_solicitante_edicion);
                $array_id_nombre_solicitante_edicion = sigmel_lista_solicitantes::on('sigmel_gestiones')->select('Id_Nombre_solicitante')->latest('Id_Nombre_solicitante')->first();
                $id_nombre_solicitante = $array_id_nombre_solicitante_edicion['Id_Nombre_solicitante'];
            break;
            default;
                $id_nombre_solicitante = $request->nombre_solicitante;
            break;
        }

        // validacion si selecciona la opción Otro/¿Cual? del selector de Fuente de Información

        if ($request->fuente_informacion == 42) {
            
            $datos_otra_fuente_informacion_edicion = [
                'Tipo_lista' => 'Fuente de informacion',
                'Nombre_parametro' => $request->otra_fuente_informacion,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_parametros::on('sigmel_gestiones')->insert($datos_otra_fuente_informacion_edicion);
            $array_fuente_informacion_edicion = sigmel_lista_parametros::on('sigmel_gestiones')->select('Id_Parametro')->latest('Id_Parametro')->first();
            $fuente_informacion = $array_fuente_informacion_edicion['Id_Parametro'];
            
        } else {
            $fuente_informacion = $request->fuente_informacion;
        }

        $actualizar_GestionIniciaPericial = [
            'Id_motivo_solicitud' => $request->motivo_solicitud,
            'Tipo_vinculacion' => $request->tipovinculo,
            'Regimen_salud' => $request->regimen,
            'Id_solicitante' => $id_solicitante,
            'Id_nombre_solicitante' => $id_nombre_solicitante,
            'Fuente_informacion' => $fuente_informacion,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];        

        $pericialActualizar = sigmel_informacion_pericial_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $IdEventoactulizar)->firstOrFail();
        $pericialActualizar->fill($actualizar_GestionIniciaPericial);
        $pericialActualizar->save();

        sleep(2);


        /* Actualizacion tabla sigmel_informacion_asignacion_eventos (NO ESTÁ YA ACTIVADA ESTE PARTE DEL CÓDIGO DEBIDO A QUE ESTA INFORMACIÓN YA NO SERÁ DE ACCESO PARA EL USUARIO) */

        /* $actualizar_GestionIniciaAsignacion = [
            'Id_proceso' => $request->proceso,
            'Id_servicio' => $request->servicio,
            'Id_accion' => $request->accion,
            'Descripcion' => $request->descripcion_asignacion,
            'F_alerta' => $request->fecha_alerta,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];  

        $asignacionActualizar = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
        ->where('ID_evento', $IdEventoactulizar)->firstOrFail();
        $asignacionActualizar->fill($actualizar_GestionIniciaAsignacion);
        $asignacionActualizar->save();

        sleep(2); */

        /* RECOLECCIÓN INFORMACIÓN PARA LA TABLA: sigmel_historial_acciones_eventos */
        $datos_historial_acciones = [
            'ID_evento' => $IdEventoactulizar,
            'F_accion' => $date,
            'Nombre_usuario' => $nombre_usuario,
            'Accion_realizada' => "Edición de información del evento.",
        ];

        sigmel_historial_acciones_eventos::on('sigmel_gestiones')->insert($datos_historial_acciones);
        sleep(2);
        return redirect()->route('gestionInicialNuevo')->with('evento_actualizado', 'Evento actualizado Sactifactoriamente');
    }

    public function registrarOtraEmpresa(Request $request){

        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;
        
        /* RECOLECCIÓN INFORMACIÓN PARA LA TABLA: sigmel_informacion_laboral_eventos */

        // Evaluamos si selecciona la opción Otro/¿Cuál? del selector arl (información laboral)
        if ($request->arl_info_laboral_registrar == 10) {
            
            $datos_otra_arl_info_laboral = [
                'Nombre_arl' => $request->otra_arl_info_laboral_registrar,
                'Estado' => 'activo',
                'F_registro' => $date
            ];

            sigmel_lista_arls::on('sigmel_gestiones')->insert($datos_otra_arl_info_laboral);
            $array_otra_arl = sigmel_lista_arls::on('sigmel_gestiones')->select('Id_Arl')->latest('Id_Arl')->first();
            $otra_arl = $array_otra_arl['Id_Arl'];
        } else {
            $otra_arl = $request->arl_info_laboral_registrar;
        }

        if ($request->departamento_info_laboral_registrar == 33) {
            
            // Evaluamos si selecciona la opción de País? del selector Municipios (Información laboral)
            if($request->municipio_info_laboral_registrar == 1120){

                $datos_pais_exterior = [
                'Id_departamento' => 33,
                'Nombre_departamento' => "Exterior",
                'Nombre_municipio' => $request->pais_exterior_info_laboral_registrar,
                'Estado' => "activo",
                'F_registro' => $date
                ];

                sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->insert($datos_pais_exterior);
                $array_id_municipio_laboral = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')->select('Id_municipios')->latest('Id_municipios')->first();
                $id_municipio_laboral = $array_id_municipio_laboral['Id_municipios'];
            }else{
                $id_municipio_laboral = $request->municipio_info_laboral_registrar;
            }

        } else{
            $id_municipio_laboral = $request->municipio_info_laboral_registrar;
        }

        $datos_info_laboral_nuevo =[
            'Nro_identificacion' => $request->nro_identificacion_registrar,
            'Tipo_empleado' => $request->tipo_empleo_registrar,
            'Id_arl' => $otra_arl,
            'Empresa' => $request->empresa_registrar,
            'Nit_o_cc' => $request->nit_cc_registrar,
            'Telefono_empresa' => $request->telefono_empresa_registrar,
            'Email' => $request->email_info_laboral_registrar,
            'Direccion' => $request->direccion_info_laboral_registrar,
            'Id_departamento' => $request->departamento_info_laboral_registrar,
            'Id_municipio' => $id_municipio_laboral,
            'Id_actividad_economica' => $request->actividad_economica_registrar,
            'Id_clase_riesgo' => $request->clase_riesgo_registrar,
            'Persona_contacto' => $request->persona_contacto_registrar,
            'Telefono_persona_contacto' => $request->telefono_persona_contacto_registrar,
            'Id_codigo_ciuo' => $request->codigo_ciuo_registrar,
            'F_ingreso' => $request->fecha_ingreso_registrar,
            'Cargo' => $request->cargo_registrar,
            'Funciones_cargo' => $request->funciones_cargo_registrar,
            'Antiguedad_empresa' => $request->antiguedad_empresa_registrar,
            'Antiguedad_cargo_empresa' => $request->antiguedad_cargo_registrar,
            'F_retiro' => $request->fecha_retiro_registrar,
            'Descripcion' => $request->descripcion_registrar,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];

        // Inserción de datos en la tabla sigmel_informacion_laboral_eventos
        sigmel_historico_empresas_afiliados::on('sigmel_gestiones')->insert($datos_info_laboral_nuevo);

        // colacamos un tiempo de retardo pequeño para que alcance a insertar los datos
        sleep(2);

        $mensajes = array(
            "a" => 'si_creo',
            "b" => 'Registro creado satisfactoriamente.'
        );
        
        return json_decode(json_encode($mensajes, true));
	    
    }

    public function consultaHistoricoEmpresas(Request $request){
        $array_datos_laboral_tabla = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_historico_empresas_afiliados as shea')
        ->leftJoin('sigmel_gestiones.sigmel_lista_arls as slarl', 'slarl.Id_Arl', '=', 'shea.Id_arl')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm', 'sldm.Id_departamento', '=', 'shea.Id_departamento') 
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as sldm1', 'sldm1.Id_municipios', '=', 'shea.Id_municipio') 
        ->leftJoin('sigmel_gestiones.sigmel_lista_actividad_economicas as slae', 'slae.Id_ActEco', '=', 'shea.Id_actividad_economica')
        ->leftJoin('sigmel_gestiones.sigmel_lista_clase_riesgos as slcr', 'slcr.Id_Riesgo', '=', 'shea.Id_clase_riesgo')
        ->leftJoin('sigmel_gestiones.sigmel_lista_ciuo_codigos as slcc', 'slcc.Id_Codigo', '=', 'shea.Id_codigo_ciuo')
        ->select(
            'shea.Tipo_empleado',
            'shea.Id_arl',
            'slarl.Nombre_arl',
            'shea.Empresa',
            'shea.Nit_o_cc',
            'shea.Telefono_empresa',
            'shea.Email',
            'shea.Direccion',
            'shea.Id_departamento',
            'sldm.Nombre_departamento',
            'shea.Id_municipio',
            'sldm1.Nombre_municipio',
            'shea.Id_actividad_economica',
            'slae.id_codigo',
            'slae.Nombre_actividad',
            DB::raw("CONCAT(slae.id_codigo,' - ',slae.Nombre_actividad) as full_actividad_economica"),
            'shea.Id_clase_riesgo',
            'slcr.Nombre_riesgo',
            'shea.Persona_contacto',
            'shea.Telefono_persona_contacto',
            'shea.Id_codigo_ciuo',
            'slcc.id_codigo_ciuo',
            'slcc.Nombre_ciuo',
            DB::raw("CONCAT(slcc.id_codigo_ciuo,' - ',slcc.Nombre_ciuo) as full_ciuo"),
            'shea.F_ingreso',
            'shea.Cargo',
            'shea.Funciones_cargo',
            'shea.Antiguedad_empresa',
            'shea.Antiguedad_cargo_empresa',
            'shea.F_retiro',
            'shea.Descripcion'
        )
        ->where([
            ['shea.Nro_identificacion', '=', $request->numero_identificacion]
        ])
        // ->groupBy('shea.Nro_identificacion')
        // ->orderBy('shea.F_registro', 'desc')
        ->distinct()
        ->get();

        return response()->json($array_datos_laboral_tabla);
    }

    public function cargaListadoDocumentosInicialNuevo(Request $request){
        
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        /* Validación N° 1: TAMAÑO DEL ARCHIVO */
        $reglas_validacion_tamano_documento = array(
            'listadodocumento' => 'max:10000'
        );

        $ejecutar_validador_tamano_documento = Validator::make($request->all(), $reglas_validacion_tamano_documento);

        if ($ejecutar_validador_tamano_documento->fails()) {

            $mensajes = array(
                "parametro" => 'fallo',
                "mensaje" => 'El tamaño máximo permitido para cargar este documento es de 10 Megas.'
            );

            // Retornamos el valor de la bandera del OTRO DOCUMENTO para validaciones visuales.
            if (!empty($request->bandera_otro_documento) && $request->bandera_otro_documento <> 0) {
                $mensajes["otro"] = $request->bandera_otro_documento;
            }
            
            return json_decode(json_encode($mensajes, true));
        }

        /* Validación N° 2: Cuando el documento que se intenta cargar son de los que no son obligatorios y aún así se manda vacío el dato */
        if($request->file('listadodocumento') == ""){

            // echo "estoy vacio no subo gonorreas";
            $mensajes = array(
                "parametro" => 'fallo',
                "mensaje" => 'Debe cargar este documento para poder guardarlo.'
            );

            // Retornamos el valor de la bandera del OTRO DOCUMENTO para validaciones visuales.
            if (!empty($request->bandera_otro_documento) && $request->bandera_otro_documento <> 0) {
                $mensajes["otro"] = $request->bandera_otro_documento;
            }

            return json_decode(json_encode($mensajes, true));
        }

        /* Validación N° 3: EL ID DEL EVENTO DEBE ESTAR ESCRITO */
        if($request->EventoID == ""){
            $mensajes = array(
                "parametro" => 'fallo',
                "mensaje" => 'Debe diligenciar primero el formulario para poder cargar este documento.'
            );

            // Retornamos el valor de la bandera del OTRO DOCUMENTO para validaciones visuales.
            if (!empty($request->bandera_otro_documento) && $request->bandera_otro_documento <> 0) {
                $mensajes["otro"] = $request->bandera_otro_documento;
            }

            return json_decode(json_encode($mensajes, true));
        }
        
        /* Validación N° 4: TIPO DE DOCUMENTO */
        $reglas_validacion_tipo_documento = array(
            'listadodocumento' => 'mimes:pdf,xls,xlsx,doc,docx,jpeg,png'
        );

        $ejecutar_validador_tipo_documento = Validator::make($request->all(), $reglas_validacion_tipo_documento);

        if ($ejecutar_validador_tipo_documento->fails()) {
            $mensajes = array(
                "parametro" => 'fallo',
                "mensaje" => 'El tipo de documento debe ser de alguna de estas extensiones: pdf, xls, xlsx, doc, docx, jpeg, png.'
            );

            // Retornamos el valor de la bandera del OTRO DOCUMENTO para validaciones visuales.
            if (!empty($request->bandera_otro_documento) && $request->bandera_otro_documento <> 0) {
                $mensajes["otro"] = $request->bandera_otro_documento;
            }

            return json_decode(json_encode($mensajes, true));
        }

        /* Si las validaciones son exitosas, Se procede a subir el documento */

        // Captura de variables del formulario.
        $file = $request->file('listadodocumento');
        $id_documento = $request->Id_Documento;
        
        // Evaluamos si han enviado el OTRO DOCUMENTO para que así pueda reemplazar el nombre original por el nombre que indico en el formulario
        if (!empty($request->bandera_otro_documento) && $request->bandera_otro_documento <> 0) {
            $nombre_lista_documento = str_replace(' ', '_', str_replace('/', '_', $request->nombre_otro_documento));
            $nombre_lista_documento = "Otro_Documento_".$nombre_lista_documento;
        } else {
            $nombre_lista_documento = str_replace(' ', '_', str_replace('/', '_', $request->Nombre_documento));
        }
        
        $idEvento = $request->EventoID;

        // Creación de carpeta con el ID EVENTO para insertar los documentos
        $path = public_path('Documentos_Eventos/'.$idEvento);
        $mode = 777;
        
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);

            chmod($path, octdec($mode));

            $nombre_final_documento_en_carpeta = $nombre_lista_documento."_IdEvento_".$idEvento.".".$file->extension();
            Storage::putFileAs($idEvento, $file, $nombre_final_documento_en_carpeta);

        }else {

            if(!empty($request->bandera_nombre_otro_doc)){
                $nombre_final_documento_en_carpeta = $request->bandera_nombre_otro_doc.".".$file->extension();
            }else{
                $nombre_final_documento_en_carpeta = $nombre_lista_documento."_IdEvento_".$idEvento.".".$file->extension();
            }

            Storage::putFileAs($idEvento, $file, $nombre_final_documento_en_carpeta);
        }

        // Registrar la información del documento con relación al ID del evento.
        if(!empty($request->bandera_nombre_otro_doc)){
            $nombrecompletodocumento = $request->bandera_nombre_otro_doc;
        }else{
            $nombrecompletodocumento = $nombre_lista_documento."_IdEvento_".$idEvento;
        }

        $nuevoDocumento = [
            'Id_Documento' => $id_documento,
            'ID_evento' => $idEvento,
            'Nombre_documento' => $nombrecompletodocumento,
            'Formato_documento' => $file->extension(),
            'Estado' => 'activo',
            'F_cargue_documento' => $date,
            'Descripcion' => $request->descripcion_documento,
            'Nombre_usuario' => $nombre_usuario,
            'F_registro' => $date
        ];  

        if (count($nuevoDocumento) > 0) {

            // Consultamos si el documento ya se encuentra dentro de la tabla para no cargarlo nuevamente (se reemplaza por el nuevo).
            $consulta_documento_bd = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
                ->select( "Id_Registro_Documento", "Nombre_documento", "Formato_documento")
                ->where([
                    ["Nombre_documento", "=", $nombrecompletodocumento],
                    // ["Formato_documento", "=", $file->extension()],
                    ["ID_evento", "=", $idEvento]
                ])->get();

            $array_consulta_documento_bd = json_decode(json_encode($consulta_documento_bd), true);
            
            if(!empty($array_consulta_documento_bd)){
                $Id_Registro_Documento_en_bd = $array_consulta_documento_bd[0]['Id_Registro_Documento'];
                $Nombre_documento_en_bd = $array_consulta_documento_bd[0]['Nombre_documento'];

                // $Formato_documento_en_bd = $array_consulta_documento_bd[0]['Formato_documento'];
                // && $Formato_documento_en_bd == $file->extension()
                
                if ($Nombre_documento_en_bd == $nombrecompletodocumento) {
                    $actualizar_documento = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
                        ->where('Id_Registro_Documento', $Id_Registro_Documento_en_bd)->firstOrFail();
    
                    $actualizar_documento->fill($nuevoDocumento);
                    $actualizar_documento->save();
    
                    $mensajes = array(
                        "parametro" => 'exito',
                        "mensaje" => 'Documento cargado satisfactoriamente.'
                    );
                    return json_decode(json_encode($mensajes, true));
    
                } 

            }
            else {

                sigmel_registro_documentos_eventos::on('sigmel_gestiones')->insert($nuevoDocumento);
    
                $mensajes = array(
                    "parametro" => 'exito',
                    "mensaje" => 'Documento cargado satisfactoriamente.'
                );
    
                // Retornamos el valor de la bandera del OTRO DOCUMENTO para validaciones visuales.
                if (!empty($request->bandera_otro_documento) && $request->bandera_otro_documento <> 0) {
                    $mensajes["otro"] = $request->bandera_otro_documento;
                }
                
                // SE VALIDA SI TODOS LOS DOCUMENTOS OBLIGATORIOS HAN SIDO CARGADOS PARA PROCEDER A HABILITAR EL BOTÓN QUE CREARÁ EL EVENTO
                $id_docs_obligatorios = sigmel_lista_documentos::on('sigmel_gestiones')
                        ->select('Id_Documento')
                        ->where([
                            ["Requerido", "=", "Si"],
                            ["Estado", "=", "activo"]
                        ])->get();
        
                $array_id_docs_obligatorios = json_decode(json_encode($id_docs_obligatorios), true);
                $cantidad_id_docs_obligatorios = count($array_id_docs_obligatorios);
        
                $cantidad_id_docs_subidos = sigmel_registro_documentos_eventos::on('sigmel_gestiones')
                ->where([
                    ['ID_evento', '=', $request->EventoID]
                ])
                ->whereIn('Id_Documento', $array_id_docs_obligatorios)->count();
                
                if ($cantidad_id_docs_obligatorios == $cantidad_id_docs_subidos) {
                    $mensajes["todos_obligatorios"] = "Si";
                }
    
                return json_decode(json_encode($mensajes, true));
                
            }

        }

    }

    public function consultaHistorialAcciones (Request $request){
        $array_datos_historial_acciones = sigmel_historial_acciones_eventos::on('sigmel_gestiones')
        ->select('F_accion', 'Nombre_usuario', 'Accion_realizada', 'Descripcion')
        ->where('ID_evento', $request->ID_evento)
        ->orderBy('F_accion', 'asc')
        ->get();

        return response()->json($array_datos_historial_acciones);
    }

    /* TODO LO REFERENTE A BUSQUEDA DE EVENTO */
    public function cargueDocumentosXEvento(Request $request){

        $id_evento = $request->id_evento;

        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($id_evento)); 
        // $aperturaModal = 'Edicion_Busqueda';
        // return view('administrador.modalcarguedocumentos', compact('arraylistado_documentos', 'aperturaModal'));
        return response()->json($arraylistado_documentos);
    }

    
}
