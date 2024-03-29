<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Cargue de modelos
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_informacion_acciones;


class AccionesController extends Controller
{
    // TODO LO REFERENTE ENTIDADES 

    // Vista formulario nueva acción
    public function mostrarVistaNuevaAccion(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.crearNuevaAccion', compact('user'));
    }

    // Guardar una Accion
    public function CrearNuevaAccion(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        $nombre_usuario = Auth::user()->name;

        $nueva_accion = array(
            'Estado_accion' => $request->Estado_accion,
            'Accion' => $request->Accion,
            'Descripcion_accion' => $request->Descripcion_accion,
            'Status_accion' => $request->Status_accion,
            'F_creacion_accion' => $request->F_creacion_accion,
            'Nombre_usuario' => $nombre_usuario,
            'created_at' => $date
        );

        sigmel_informacion_acciones::on('sigmel_gestiones')->insert($nueva_accion);

        // return back()->with('accion_creada', 'Acción creada correctamente.');

        $mensajes = array(
            "parametro" => 'accion_creada',
            "mensaje" => 'Acción creada satisfactoriamente.'
        );
        return json_decode(json_encode($mensajes, true));
    }

    // cargue de selectores
    public function cargueListadoSelectoresAcciones(Request $request){
        $parametro = $request->parametro;

        if ($parametro == "lista_estados") {
            $listado_estados = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Acciones'],
                ['Estado', '=', 'activo'],
            ])->get();

            $info_lista_estados = json_decode(json_encode($listado_estados, true));
            return response()->json($info_lista_estados);
        };

        if ($parametro == "lista_estados_edicion") {
            $listado_estados = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Acciones'],
                ['Estado', '=', 'activo'],
            ])->get();

            $info_lista_estados = json_decode(json_encode($listado_estados, true));
            return response()->json($info_lista_estados);
        };

    }
    
    // Vista listado de acciones
    public function mostrarVistaListarAcciones(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        $listado_acciones = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_acciones as sia')
        ->select('sia.Id_Accion', 'sia.Estado_accion', 'slp.Nombre_parametro as Nombre_estado', 'sia.Accion','sia.Descripcion_accion',
        'sia.Status_accion', 'sia.F_creacion_accion', 'sia.Nombre_usuario', 'sia.created_at','sia.updated_at')
        ->leftJoin('sigmel_gestiones.sigmel_lista_parametros as slp', 'sia.Estado_accion', '=', 'slp.Id_Parametro')
        ->get();

        $conteo_activos_inactivos = DB::table(getDatabaseName('sigmel_gestiones') .'sigmel_informacion_acciones')
        ->select(DB::raw("COUNT(IF(Status_accion = 'Activo', 1, NULL)) AS 'Activos'"), DB::raw("COUNT(IF(Status_accion = 'Inactivo', 1, NULL)) AS 'Inactivos'"))
        ->get();

        return view('administrador.listarAcciones', compact('user', 'listado_acciones', 'conteo_activos_inactivos'));
    }

    // Traer la información de la Acción a editar
    public function InformacionAccionEditar(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $datos_accion_editar = sigmel_informacion_acciones::on('sigmel_gestiones')
        ->where('Id_Accion', $request->id_accion_editar)
        ->get();

        $informacion_accion_editar = json_decode(json_encode($datos_accion_editar), true);
        return response()->json($informacion_accion_editar);

    }

    // Actualizar la información de una accion
    public function ActualizarAccion(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $time = time();
        $date = date("Y-m-d h:i:s", $time);
        $nombre_usuario = Auth::user()->name;

        $actualizar_accion = array(
            'Estado_accion' => $request->Estado_accion,
            'Accion' => $request->Accion,
            'Descripcion_accion' => $request->Descripcion_accion,
            'Status_accion' => $request->Status_accion,
            'F_creacion_accion' => $request->F_creacion_accion,
            'Nombre_usuario' => $nombre_usuario,
            'updated_at' => $date
        );

        sigmel_informacion_acciones::on('sigmel_gestiones')
        ->where('Id_Accion', $request->id_accion)
        ->update($actualizar_accion);


        $mensajes = array(
            "parametro" => 'accion_editada',
            "mensaje" => 'Acción editada satisfactoriamente.'
        );
        return json_decode(json_encode($mensajes, true));
    }

}
