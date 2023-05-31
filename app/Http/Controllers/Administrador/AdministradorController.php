<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function mostrarVistaNuevoGrupoTrabajo(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.crearGruposTrabajo', compact('user'));
    }

    public function guardar_grupo_trabajo(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        
        

        if (empty($request->listado_usuarios_grupo)) {
            return back()->with('grupo_no_creado', 'Debe almenos seleccionar un usuario para crear un grupo de trabajo.');
        }else{

            // Se realiza el registro de un nuevo grupo de trabajo
            $nuevo_grupo = array(
                'nombre' => $request->nombre_grupo_trabajo,
                'lider' => $request->listado_lider,
                'estado' => $request->estado_grupo,
                'observacion' => $request->observacion_grupo_trabajo,
                'created_at' => $date
            );

            
            sigmel_grupos_trabajos::on('sigmel_gestiones')->insert($nuevo_grupo);

            $id_grupo_trabajo = sigmel_grupos_trabajos::on('sigmel_gestiones')->select('id')->latest('id')->first();

            for ($i=0; $i < count($request->listado_usuarios_grupo); $i++) { 
                $id_usuario_asignar = $request->listado_usuarios_grupo[$i];

                $asignar_usuarios = array(
                    'id_grupo_trabajo' => $id_grupo_trabajo['id'],
                    'id_usuarios_asignados' => $id_usuario_asignar,
                    'created_at' => $date
                );
                sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')->insert($asignar_usuarios);
            }


            /* REGISTRO ACTIVIDAD PARA AUDITORIA */
            $accion_realizada = "Registro de Grupo de Trabajo N° {$id_grupo_trabajo['id']}";
            $registro_actividad = [
                'id_usuario_sesion' => Auth::id(),
                'nombre_usuario_sesion' => Auth::user()->name,
                'email_usuario_sesion' => Auth::user()->email,
                'acccion_realizada' => $accion_realizada,
                'fecha_registro_accion' => $date
            ];
            
            sigmel_auditorias_gr_trabajos::on('sigmel_auditorias')->insert($registro_actividad);

            return back()->with('grupo_creado', 'Grupo de trabajo creado correctamente.');

        }

    }

    public function mostrarVistaListarGruposTrabajo(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $listado_grupos_trabajo = sigmel_grupos_trabajos::on('sigmel_gestiones')
                                ->select('id', 'lider', 'nombre', 'estado', 'created_at', 'updated_at')->get();

        
        return view('administrador.listarGruposTrabajo', compact('user', 'listado_grupos_trabajo'));
    }

    public function mostrarVistaEditarGrupoTrabajo(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $id = $request->id;
        $lider = $request->lider;

        $info_selector_lider = DB::table('users')->select('id', 'name', 'email')->where('id', $lider)->get();
        $info_grupo_trabajo = sigmel_grupos_trabajos::on('sigmel_gestiones')->select('id', 'nombre', 'estado', 'observacion')
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

    public function listadoUsuariosAsignacion (Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }
        
        $id_grupo_trabajo = $request->id_grupo_trabajo;

        $ids_usuarios_asignados =  sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')
                                    ->select('id_usuarios_asignados')
                                    ->where('id_grupo_trabajo', $id_grupo_trabajo)->get();

        $string_ids = array();
        for ($i=0; $i < count($ids_usuarios_asignados); $i++) { 
            
            array_push($string_ids, $ids_usuarios_asignados[$i]->id_usuarios_asignados);
        }

        $datos_usuarios_no_asignados = DB::table('users')->select('id', 'name', 'email', DB::raw("'no' as seleccionado"))->whereNotIn('id', $string_ids)->get();

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

    public function editar_grupo_trabajo(Request $request){
        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }
        
        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        
        $id_grupo_trabajo = $request->id_grupo_trabajo;

        $ids_usuarios_asignados =  sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')
                                    ->select('id_usuarios_asignados')
                                    ->where('id_grupo_trabajo', $id_grupo_trabajo)->get();
                                    
        
        $id_asignados_orignales = [];
        for ($i=0; $i < count($ids_usuarios_asignados); $i++) { 
            $id_asignados_orignales [] = $ids_usuarios_asignados[$i]->id_usuarios_asignados;
        }

        $ids_asignados_formulario = $request->editar_listado_usuarios_grupo;

        if (empty($ids_asignados_formulario)) {
            $msg = 'No puede eliminar todos los usuarios del grupo.';
            return redirect()->route('listarGruposTrabajo')->with('grupo_no_editado', $msg);
        } else {

            if($request->editar_observacion_grupo_trabajo <> ""){
                $observacion = $request->editar_observacion_grupo_trabajo;
            }else{
                $observacion = null;
            }

            // Se realiza la actualización de la información del grupo de trabajo
            $actualizar_info_grupo = array(
                'nombre' => $request->editar_nombre_grupo_trabajo,
                'lider' => $request->editar_listado_lider,
                'estado' => $request->editar_estado_grupo,
                'observacion' => $observacion,
                'updated_at' => $date
            );
            
            sigmel_grupos_trabajos::on('sigmel_gestiones')
            ->where('id', $id_grupo_trabajo)
            ->update($actualizar_info_grupo);
            
            $eliminar_ids_asignados = array();
            if (count($ids_asignados_formulario) < count($id_asignados_orignales)) {
                $diferencia = array_diff($id_asignados_orignales, $ids_asignados_formulario);
                // echo "borrar";

                foreach ($diferencia as $key => $id_eliminar) {
                    sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')
                    ->where([
                        ['id_grupo_trabajo', $id_grupo_trabajo],
                        ['id_usuarios_asignados', $id_eliminar]
                    ])->delete();
                }
            }
    
            if (count($ids_asignados_formulario) > count($id_asignados_orignales)) {
                $diferencia = array_diff($ids_asignados_formulario, $id_asignados_orignales);
                // echo "insertar";

                foreach ($diferencia as $key => $id_insertar) {
                    $asignar_usuarios = array(
                        'id_grupo_trabajo' => $id_grupo_trabajo,
                        'id_usuarios_asignados' => $id_insertar,
                        'created_at' => $date
                    );
                    sigmel_usuarios_grupos_trabajos::on('sigmel_gestiones')->insert($asignar_usuarios);
                }
            }

            /* REGISTRO ACTIVIDAD PARA AUDITORIA */
            $accion_realizada = "Edición de Grupo de Trabajo N° {$id_grupo_trabajo}";
            $registro_actividad = [
                'id_usuario_sesion' => Auth::id(),
                'nombre_usuario_sesion' => Auth::user()->name,
                'email_usuario_sesion' => Auth::user()->email,
                'acccion_realizada' => $accion_realizada,
                'fecha_registro_accion' => $date
            ];
            
            sigmel_auditorias_gr_trabajos::on('sigmel_auditorias')->insert($registro_actividad);

            $msg = 'Información actualizada correctamente';
            return redirect()->route('listarGruposTrabajo')->with('grupo_editado', $msg);

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

    /* TODO LO REFERENTE AL FORMULARIO DE GESTIÓN INICIAL NUEVO */
    public function mostrarVistaGestionInicialNuevo(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.gestionInicialNuevo', compact('user'));
    }

    public function cargueListadoSelectores(Request $request){

        $parametro = $request->parametro;

        /* TRAER LISTADO DE CLIENTES */
        if($parametro == 'lista_clientes'){
            $listado_clientes = sigmel_lista_clientes::on('sigmel_gestiones')
                            ->select('Id_Cliente', 'Nombre_cliente')->get();
            
            $info_lista_clientes = json_decode(json_encode($listado_clientes, true));
            return response()->json($info_lista_clientes);
        }

        /* TRAER LISTADO DE TIPOS DE CLIENTE */
        if ($parametro == "lista_tipo_clientes") {
            
            $listado_tipo_clientes = sigmel_lista_tipo_clientes::on('sigmel_gestiones')
                            ->select('Id_TipoCliente', 'Nombre_tipo_cliente')->get();
            
            $info_lista_tipo_clientes = json_decode(json_encode($listado_tipo_clientes, true));
            return response()->json($info_lista_tipo_clientes);
        }

        /* TRAER LISTADO DE TIPOS DE EVENTO */
        if ($parametro == "lista_tipo_evento") {
            
            $listado_tipo_eventos = sigmel_lista_tipo_eventos::on('sigmel_gestiones')
                            ->select('Id_Evento', 'Nombre_evento')->get();
            
            $info_lista_tipo_eventos = json_decode(json_encode($listado_tipo_eventos, true));
            return response()->json($info_lista_tipo_eventos);
        }

        /* TRAER LISTADO DE TIPOS DE DOCUMENTO */
        if ($parametro == "lista_tipo_documento") {
            
            $listado_tipo_documento = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')
                            ->where('Tipo_lista', 'Tipo de documento')
                            ->get();
            
            $info_lista_tipo_documento = json_decode(json_encode($listado_tipo_documento, true));
            return response()->json($info_lista_tipo_documento);
        }

        /* TRAER LISTADO DE GENERO */
        if ($parametro == "genero") {
            
            $listado_generos = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')
                            ->where('Tipo_lista', 'Genero')
                            ->get();
            
            $info_lista_generos = json_decode(json_encode($listado_generos, true));
            return response()->json($info_lista_generos);
        }

        /* TRAER LISTADO DE ESTADO CIVIL */
        if ($parametro == "estado_civil") {
            
            $listado_estado_civil = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')
                            ->where('Tipo_lista', 'Estado civil')
                            ->get();
            
            $info_lista_estado_civil = json_decode(json_encode($listado_estado_civil, true));
            return response()->json($info_lista_estado_civil);
        }

        /* TRAER LISTADO DE NIVEL ESCOLAR */
        if ($parametro == "nivel_escolar") {
            
            $listado_nivel_escolar = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')
                            ->where('Tipo_lista', 'Nivel escolar')
                            ->get();
            
            $info_lista_nivel_escolar = json_decode(json_encode($listado_nivel_escolar, true));
            return response()->json($info_lista_nivel_escolar);
        }

        /* TRAER LISTADO DE DOMINANCIAS */
        if ($parametro == "dominancia") {
            
            $listado_dominancia = sigmel_lista_dominancias::on('sigmel_gestiones')
                            ->select('Id_Dominancia', 'Nombre_dominancia')
                            ->get();
            
            $info_lista_dominancia = json_decode(json_encode($listado_dominancia, true));
            return response()->json($info_lista_dominancia);
        }

        /* TRAER LISTADO DEPARTAMENTOS (INFORMACIÓN DE AFILIADO) */
        if ($parametro == "departamentos_info_afiliado") {
            
            $listado_departamentos_info_afiliado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
            ->select('Id_departamento', 'Nombre_departamento')
            ->groupBy('Nombre_departamento')
            ->get();
            
            $info_lista_departamentos_info_afiliado = json_decode(json_encode($listado_departamentos_info_afiliado, true));
            return response()->json($info_lista_departamentos_info_afiliado);
        }

        /* TRAER LISTADO MUNCIPIOS (INFORMACIÓN DE AFILIADO) */
        if($parametro == "municipios_info_afiliado"){
            $listado_municipios_info_afiliado = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
            ->select('Id_municipios', 'Nombre_municipio')
            ->where('Id_departamento', $request->id_departamento_info_afiliado)
            ->get();

            $info_lista_municpios_info_afiliado = json_decode(json_encode($listado_municipios_info_afiliado, true));
            return response()->json($info_lista_municpios_info_afiliado);
        }
        
        /* TRAER LISTADO DE TIPOS DE AFILIADO */
        if ($parametro == "tipo_afiliado") {
            
            $listado_tipo_afiliado = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')
                            ->where('Tipo_lista', 'Tipo de Afiliado')
                            ->get();
            
            $info_lista_tipo_afiliado = json_decode(json_encode($listado_tipo_afiliado, true));
            return response()->json($info_lista_tipo_afiliado);
        }

        /* TRAER LISTADO DE EPS */
        if ($parametro == "lista_eps") {
            
            $listado_eps = sigmel_lista_eps::on('sigmel_gestiones')
                            ->select('Id_Eps', 'Nombre_eps')
                            // ->orderBy('Nombre_eps', 'asc')
                            ->get();
            
            $info_lista_eps = json_decode(json_encode($listado_eps, true));
            return response()->json($info_lista_eps);
        }

        /* TRAER LISTADO DE AFP */
        if ($parametro == "lista_afp") {
            
            $listado_afp = sigmel_lista_afps::on('sigmel_gestiones')
                            ->select('Id_Afp', 'Nombre_afp')
                            // ->orderBy('Nombre_afp', 'asc')
                            ->get();
            
            $info_lista_afp = json_decode(json_encode($listado_afp, true));
            return response()->json($info_lista_afp);
        }

        /* TRAER LISTADO DE ARL (Información Afiliado) */
        if ($parametro == "lista_arl_info_afiliado") {
            
            $listado_arl_info_afiliado = sigmel_lista_arls::on('sigmel_gestiones')
                            ->select('Id_Arl', 'Nombre_arl')
                            // ->orderBy('Nombre_arl', 'asc')
                            ->get();
            
            $info_lista_arl_info_afiliado = json_decode(json_encode($listado_arl_info_afiliado, true));
            return response()->json($info_lista_arl_info_afiliado);
        }

        /* TRAER LISTADO DE APODERADO */
        if ($parametro == "apoderado") {
            
            $listado_apoderado = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')
                            ->where('Tipo_lista', 'Apoderado')
                            ->get();
            
            $info_lista_apoderado = json_decode(json_encode($listado_apoderado, true));
            return response()->json($info_lista_apoderado);
        }

        /* TRAER LISTADO DE ACTIVO */
        if ($parametro == "activo") {
            
            $listado_activo = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')
                            ->where('Tipo_lista', 'Activo')
                            ->get();
            
            $info_lista_activo = json_decode(json_encode($listado_activo, true));
            return response()->json($info_lista_activo);
        }

        /* LISTADO ARL */
        if($parametro == 'listado_arl_info_laboral'){
            $listado_arls = sigmel_lista_arls::on('sigmel_gestiones')
                            ->select('Id_Arl', 'Nombre_arl')->get();
            $info_listado_arls = json_decode(json_encode($listado_arls, true));
            return response()->json(($info_listado_arls));
        }

        /* LISTADO DEPARTAMENTOS (Información Laboral) */
        if($parametro == 'listado_departamento_info_laboral'){
            $listado_departamento_info_laboral = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                            ->select('Id_departamento', 'Nombre_departamento')->groupBy('Id_departamento','Nombre_departamento')->get();
            $info_listado_departamento_info_laboral = json_decode(json_encode($listado_departamento_info_laboral, true));
            return response()->json(($info_listado_departamento_info_laboral));
        }

        /* LISTADO DE MUNICIPIOS (Información Laboral) */
        if($parametro == "municipios_info_laboral"){
            $listado_municipios_info_laboral = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
            ->select('Id_municipios', 'Nombre_municipio')->where('Id_departamento', '=', $request-> id_departamento_info_laboral)->get();
            $info_listado_municipio_info_laboral = json_decode(json_encode($listado_municipios_info_laboral, true));
            return response()->json(($info_listado_municipio_info_laboral));
        }

        /* LISTADO ACTIVIDAD ECONOMICA */
        if($parametro == 'listado_actividad_economica'){
            $listado_actividades_economicas = sigmel_lista_actividad_economicas::on('sigmel_gestiones')
                            ->select('Id_ActEco', 'id_codigo', 'Nombre_actividad')->get();
            $info_listado_actividades_economicas = json_decode(json_encode($listado_actividades_economicas, true));
            return response()->json(($info_listado_actividades_economicas));
        }

        /* LISTADO CLASE DE RIESGO */
        if($parametro == 'listado_clase_riesgo'){
            $listado_clases_de_riesgos = sigmel_lista_clase_riesgos::on('sigmel_gestiones')
                            ->select('Id_Riesgo', 'Nombre_riesgo')->get();
            $info_listado_clases_de_riesgos = json_decode(json_encode($listado_clases_de_riesgos, true));
            return response()->json(($info_listado_clases_de_riesgos));
        }

        /* LISTADO CODIGO CIUO */
        if($parametro == 'listado_codigo_ciuo'){
            $listado_actividades_economicas = sigmel_lista_ciuo_codigos::on('sigmel_gestiones')
                            ->select('Id_Codigo', 'id_codigo_ciuo', 'Nombre_ciuo')->get();
            $info_listado_actividades_economicas = json_decode(json_encode($listado_actividades_economicas, true));
            return response()->json(($info_listado_actividades_economicas));
        }
        
        /* LISTADO MOTIVO SOLICITUD */
        if($parametro == 'listado_motivo_solicitud'){
            $listado_motivo_solicitud = sigmel_lista_motivo_solicitudes::on('sigmel_gestiones')
                            ->select('Id_Solicitud', 'Nombre_solicitud')->get();
            $info_listado_motivo_solicitud = json_decode(json_encode($listado_motivo_solicitud, true));
            return response()->json(($info_listado_motivo_solicitud));
        }

        /* LISTADO TIPO VINCULO */
        if($parametro == 'listado_tipo_vinculo'){
            $listado_tipo_vinculo = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')->where('Tipo_lista', '=', 'Tipo de vinculacion')->get();
            $info_listado_tipo_vinculo = json_decode(json_encode($listado_tipo_vinculo, true));
            return response()->json(($info_listado_tipo_vinculo));
        }

        /* LISTADO REGIMEN EN SALUD */
        if($parametro == 'listado_solicitud_regimen_en_salud'){
            $listado_solicitud_regimen_salud = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')->where('Tipo_lista', '=', 'Solicitud Regimen en salud')->get();
            $info_listado_solicitud_regimen_salud = json_decode(json_encode($listado_solicitud_regimen_salud, true));
            return response()->json(($info_listado_solicitud_regimen_salud));
        }

        /* LISTADO SOLICITANTE */
        if($parametro == 'listado_solicitante'){
            $listado_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')
                            ->select('Id_solicitante', 'Solicitante')->groupBy('Id_solicitante','Solicitante')->get();
            $info_listado_solicitante = json_decode(json_encode($listado_solicitante, true));
            return response()->json(($info_listado_solicitante));
        }

        /* NOMBRE DE SOLICITANTE */
        if($parametro == "nombre_solicitante"){
            $listado_nombre_solicitante = sigmel_lista_solicitantes::on('sigmel_gestiones')
            ->select('Id_Nombre_solicitante', 'Nombre_solicitante')
            ->where('Id_solicitante', $request->id_solicitante)
            ->get();

            $info_listado_nombre_solicitante = json_decode(json_encode($listado_nombre_solicitante, true));
            return response()->json(($info_listado_nombre_solicitante));
        }

        /* FUENTE DE INFORMACIÓN */
        if($parametro == 'listado_fuente_informacion'){
            $listado_fuente_informacion = sigmel_lista_parametros::on('sigmel_gestiones')
                            ->select('Nombre_parametro')
                            ->where('Tipo_lista', '=', 'Fuente de informacion')
                            ->get();
            $info_listado_fuente_informacion = json_decode(json_encode($listado_fuente_informacion, true));
            return response()->json(($info_listado_fuente_informacion));
        }

        /* LISTADO PROCESO */
        if($parametro == 'listado_proceso'){
            $listado_proceso = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
                            ->select('Id_proceso', 'Nombre_proceso')->groupBy('Id_proceso','Nombre_proceso')->get();
            $info_listado_proceso = json_decode(json_encode($listado_proceso, true));
            return response()->json(($info_listado_proceso));
        }

        /* LISTADO SERVICIOS */
        if ($parametro == 'listado_servicios') {
            $listado_servicios = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where('Id_proceso', $request->id_proceso)
            ->get();

            $info_listado_servicios = json_decode(json_encode($listado_servicios, true));
            return response()->json(($info_listado_servicios));
        }

        /* LISTADO ACCION */
        if($parametro == 'listado_accion'){
            $listado_accion = sigmel_lista_acciones_procesos_servicios::on('sigmel_gestiones')
                            ->select('Id_Accion', 'Nombre_accion')->get();
            $info_listado_accion= json_decode(json_encode($listado_accion, true));
            return response()->json(($info_listado_accion));
        }
    }
}
