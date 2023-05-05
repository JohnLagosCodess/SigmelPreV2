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
}
