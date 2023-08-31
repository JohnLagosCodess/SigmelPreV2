<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

//Cargar Modelos
use App\Models\sigmel_lista_entidades;
use App\Models\sigmel_lista_departamentos_municipios;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_informacion_entidades;
use App\Models\sigmel_auditorias_entidades;


class EntidadesController extends Controller
{
    // TODO LO REFERENTE ENTIDADES 
    public function mostrarVistaNuevoEntidad(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.crearEntidades', compact('user'));
    }

    public function mostrarVistaEditarEntidad(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $id_entidad = $request->id_entidad;
        $info_entidad= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as enti')
        ->select("enti.Id_Entidad","enti.IdTipo_entidad","ti.Tipo_Entidad","enti.Otro_entidad","enti.Nombre_entidad","enti.Nit_entidad"
        ,"enti.Telefonos","enti.Otros_Telefonos","enti.Emails","enti.Otros_Emails","enti.Direccion","enti.Id_Departamento","d.Nombre_departamento","enti.Id_Ciudad","c.Nombre_municipio","enti.Id_Medio_Noti","m.Nombre_parametro as Medio_Noti"
        ,"enti.Sucursal","enti.Dirigido","enti.Estado_entidad","enti.created_at as F_Registro","enti.updated_at as F_Actuali")
        ->leftJoin('sigmel_gestiones.sigmel_lista_entidades as ti', 'enti.IdTipo_entidad', '=', 'ti.Id_Entidad')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as d', 'enti.Id_Departamento', '=', 'd.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as c', 'enti.Id_Ciudad', '=', 'c.Id_municipios')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as m', 'enti.Id_Medio_Noti', '=', 'm.Id_Parametro')
        ->where('enti.Id_Entidad', $id_entidad)->get();

        $informacion_entidad = json_decode(json_encode($info_entidad), true);
        return response()->json($informacion_entidad);
    }

    public function mostrarVistaListarEntidades(){
        if(!Auth::check()){
            return redirect('/');
        }

        $user = Auth::user();
        $listado_entidades= DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades as enti')
        ->select("enti.Id_Entidad","ti.Tipo_Entidad","enti.Otro_entidad","enti.Nombre_entidad","enti.Nit_entidad"
        ,"enti.Telefonos","enti.Otros_Telefonos","enti.Emails","enti.Otros_Emails","enti.Direccion","d.Nombre_departamento","c.Nombre_municipio","m.Nombre_parametro as Medio_Noti"
        ,"enti.Sucursal","enti.Dirigido","enti.Estado_entidad","enti.created_at as F_Registro","enti.updated_at as F_Actuali")
        ->leftJoin('sigmel_gestiones.sigmel_lista_entidades as ti', 'enti.IdTipo_entidad', '=', 'ti.Id_Entidad')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as d', 'enti.Id_Departamento', '=', 'd.Id_departamento')
        ->leftJoin('sigmel_gestiones.sigmel_lista_departamentos_municipios as c', 'enti.Id_Ciudad', '=', 'c.Id_municipios')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as m', 'enti.Id_Medio_Noti', '=', 'm.Id_Parametro')
        ->groupBy("enti.Id_Entidad")
        ->get();
        
        $conteo_activos_inactivos = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_entidades')
        ->select(DB::raw("COUNT(IF(Estado_entidad = 'activo', 1, NULL)) AS 'Activos'"), DB::raw("COUNT(IF(Estado_entidad = 'inactivo', 1, NULL)) AS 'Inactivos'"))
        ->get();
        
        return view('administrador.listarEntidades', compact('user','listado_entidades','conteo_activos_inactivos'));
        //return view('administrador.listarEntidades', compact('user'));
    }

    public function cargueListadoSelectoresEntidad(Request $request){
    
        $parametro = $request->parametro;
        // Listado tipo entidad
        if($parametro == 'lista_tipo_entidad'){
            $listado_tipo_entidad = sigmel_lista_entidades::on('sigmel_gestiones')
            ->select('Id_Entidad', 'Tipo_Entidad')
            ->where([
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_tipo_entidad = json_decode(json_encode($listado_tipo_entidad, true));
            return response()->json($info_listado_tipo_entidad);
        }

         // Listado tipo departamento
         if($parametro == 'lista_departamento_entidad'){
            $listado_departamento_entidad = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_departamento', 'Nombre_departamento')
                ->where('Estado', 'activo')
                ->groupBy('Id_departamento','Nombre_departamento')
                ->get();

            $info_listado_departamento_entidad = json_decode(json_encode($listado_departamento_entidad, true));
            return response()->json(($info_listado_departamento_entidad));
        }

        // Lista de municipios
        if($parametro == "lista_municipios_entidad"){
            $listado_municipios_entidad = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_municipios', 'Nombre_municipio')
                ->where([
                    ['Id_departamento', '=', $request->id_departamento_entidad],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_lista_municpios_entidad = json_decode(json_encode($listado_municipios_entidad, true));
            return response()->json($info_lista_municpios_entidad);
        }
        //lista medio noti
        if ($parametro == "lista_medio_noti") {
            
            $listado_motivo_noti = sigmel_lista_parametros::on('sigmel_gestiones')
                ->select('Id_Parametro','Nombre_parametro')
                ->where([
                    ['Tipo_lista', '=', 'Medio Notificacion'],
                    ['Estado', '=', 'activo']
                ])
                ->get();
            
            $info_motivo_noti = json_decode(json_encode($listado_motivo_noti, true));
            return response()->json($info_motivo_noti);
        }
        //Entidad Tipo edit
        if ($parametro == "lista_tipo_entidad_edit") {
           
            $datos_enti = sigmel_lista_entidades::on('sigmel_gestiones')
            ->select("Id_Entidad", "Tipo_Entidad")
            ->whereNotIn('Id_Entidad', [$request->edi_tipo_entidad])
            ->get();
            $informacion_entidad_edit= json_decode(json_encode($datos_enti), true);
            return response()->json($informacion_entidad_edit);
            
        }
        //Entidad Departamento
        if ($parametro == "lista_depar_edit") {
            $datos_depar = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
            ->select('Id_departamento', 'Nombre_departamento')
            ->where([
                ['Estado', 'activo']
            ])
            ->groupBy('Nombre_departamento')
            ->get();

            $informacion_depar_edit= json_decode(json_encode($datos_depar), true);
            return response()->json($informacion_depar_edit);
        }

        // Entidad ciudad Edit
         if($parametro == "lista_ciudad_edit"){
            $datos_ciudad = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_municipios', 'Nombre_municipio')
                ->where([
                    ['Id_departamento', $request->edi_departamento_c],
                    ['Estado', 'activo']
                ])
                ->get();

            $informacion_ciudad_edit = json_decode(json_encode($datos_ciudad, true));
            return response()->json($informacion_ciudad_edit);
        }

        //lista medio noti editar
        if ($parametro == "lista_medio_noti_edit") {
            
            $datos_noti = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro','Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Medio Notificacion'],
                ['Estado', '=', 'activo'],
            ])
            ->get();
            $info_motivo_noti_edit = json_decode(json_encode($datos_noti, true));
            return response()->json($info_motivo_noti_edit);
        }

        // Lista de municipios edi
        if($parametro == "lista_municipios_entidad_edit"){
            $listado_municipios_entidad_edi = sigmel_lista_departamentos_municipios::on('sigmel_gestiones')
                ->select('Id_municipios', 'Nombre_municipio')
                ->where([
                    ['Id_departamento', '=', $request->id_departamento_entidad_edit],
                    ['Estado', '=', 'activo']
                ])
                ->get();

            $info_lista_municpios_entidad_edit = json_decode(json_encode($listado_municipios_entidad_edi, true));
            return response()->json($info_lista_municpios_entidad_edit);
        }


    }
    //Guarda Registro de entidad
    public function guardar_entidad(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d h:i:s", $time);

        if (empty($request->nombre_entidad)) {
            return back()->with('entidad_no_creado', 'Debe registrar un nombre de entidad para crearlo.');
        }else{

            // Se realiza el registro de un nueva entidad
            $nueva_entidad  = array(
                'IdTipo_entidad' => $request->tipo_entidad,
                'Otro_entidad' => $request->otra_entidad,
                'Nombre_entidad' => $request->nombre_entidad,
                'Nit_entidad' => $request->nit_entidad,
                'Telefonos' => $request->entidad_telefono,
                'Otros_Telefonos' => $request->entidad_telefono_otro,
                'Emails' => $request->entidad_email,
                'Otros_Emails' => $request->entidad_email_otro,
                'Direccion' => $request->entidad_direccion,
                'Id_Departamento' => $request->entidad_departamento,
                'Id_Ciudad' => $request->entidad_ciudad,
                'Id_Medio_Noti' => $request->entidad_medio_noti,
                'Sucursal' => $request->entidad_sucursal,
                'Dirigido' => $request->entidad_dirigido,
                'Estado_entidad' => $request->estado_entidad,
                'created_at' => $date
            );
                    
            sigmel_informacion_entidades::on('sigmel_gestiones')->insert($nueva_entidad);

            $id_entidad = sigmel_informacion_entidades::on('sigmel_gestiones')->select('Id_Entidad')->latest('Id_Entidad')->first();
        
            /* REGISTRO ACTIVIDAD PARA AUDITORIA */
            $accion_realizada = "Registro de Entidad° {$id_entidad['Id_Entidad']}";
            $registro_actividad = [
                'Id_Entidad' => $id_entidad['Id_Entidad'],
                'IdTipo_entidad' => $request->tipo_entidad,
                'Otro_entidad' => $request->otra_entidad,
                'Nombre_entidad' => $request->nombre_entidad,
                'Nit_entidad' => $request->nit_entidad,
                'Telefonos' => $request->entidad_telefono,
                'Otros_Telefonos' => $request->entidad_telefono_otro,
                'Emails' => $request->entidad_email,
                'Otros_Emails' => $request->entidad_email_otro,
                'Direccion' => $request->entidad_direccion,
                'Id_Departamento' => $request->entidad_departamento,
                'Id_Ciudad' => $request->entidad_ciudad,
                'Id_Medio_Noti' => $request->entidad_medio_noti,
                'Sucursal' => $request->entidad_sucursal,
                'Dirigido' => $request->entidad_dirigido,
                'Estado_entidad' => $request->estado_entidad,
                'id_usuario_sesion' => Auth::id(),
                'nombre_usuario_sesion' => Auth::user()->name,
                'acccion_realizada' => $accion_realizada,
                'fecha_registro_accion' => $date
            ];
            sigmel_auditorias_entidades::on('sigmel_auditorias')->insert($registro_actividad);
        
            return back()->with('entidad_creado', 'Equipo de trabajo creado correctamente.');

        }

    }

    public function actualizarEntidad(Request $request){

        // Si el usuario no ha iniciado, no podrá ingresar al sistema
        if(!Auth::check()){
            return redirect('/');
        }

        // Preparamos los datos en un array para actualizar la info entidad.
        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        $id_entidad = $request->captura_id_entidad;
        
         // Se realiza el registro de un nueva entidad
         $update_entidad  = array(
            'IdTipo_entidad' => $request->edi_tipo_entidad,
            'Otro_entidad' => $request->otra_entidad_edit,
            'Nombre_entidad' => $request->nombre_entidad,
            'Nit_entidad' => $request->nit_entidad,
            'Telefonos' => $request->entidad_telefono,
            'Otros_Telefonos' => $request->entidad_telefono_otro,
            'Emails' => $request->entidad_email,
            'Otros_Emails' => $request->entidad_email_otro,
            'Direccion' => $request->entidad_direccion,
            'Id_Departamento' => $request->edi_entidad_departamento,
            'Id_Ciudad' => $request->edi_entidad_ciudad,
            'Id_Medio_Noti' => $request->edi_entidad_medio_noti,
            'Sucursal' => $request->entidad_sucursal,
            'Dirigido' => $request->entidad_dirigido,
            'Estado_entidad' => $request->edit_estado_entidad,
            'updated_at' => $date
        );

        // Generamos el update
        sigmel_informacion_entidades::on('sigmel_gestiones')->where('Id_Entidad', $id_entidad)
        ->update($update_entidad);

         /* REGISTRO ACTIVIDAD PARA AUDITORIA */
         $accion_realizada = "Actuliza Entidad° {$id_entidad}";
         $registro_actividad = [
             'Id_Entidad' => $id_entidad,
             'IdTipo_entidad' => $request->tipo_entidad,
             'Otro_entidad' => $request->otra_entidad,
             'Nombre_entidad' => $request->nombre_entidad,
             'Nit_entidad' => $request->nit_entidad,
             'Telefonos' => $request->entidad_telefono,
             'Otros_Telefonos' => $request->entidad_telefono_otro,
             'Emails' => $request->entidad_email,
             'Otros_Emails' => $request->entidad_email_otro,
             'Direccion' => $request->entidad_direccion,
             'Id_Departamento' => $request->entidad_departamento,
             'Id_Ciudad' => $request->entidad_ciudad,
             'Id_Medio_Noti' => $request->entidad_medio_noti,
             'Sucursal' => $request->entidad_sucursal,
             'Dirigido' => $request->entidad_dirigido,
             'Estado_entidad' => $request->estado_entidad,
             'id_usuario_sesion' => Auth::id(),
             'nombre_usuario_sesion' => Auth::user()->name,
             'acccion_realizada' => $accion_realizada,
             'fecha_registro_accion' => $date
         ];
         sigmel_auditorias_entidades::on('sigmel_auditorias')->insert($registro_actividad);

        $mensajes = array(
            "parametro" => 'exito',
            "mensaje" => 'Información de Entidad actualizada satisfactoriamente. Para visualizar los cambios debe hacer clic en el botón Actualizar.'
        );



        return json_decode(json_encode($mensajes, true));


    }

}
