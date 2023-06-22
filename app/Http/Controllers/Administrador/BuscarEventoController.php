<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\cndatos_eventos;

class BuscarEventoController extends Controller
{
    /* TODO LO REFERENTE AL FORMULARIO DE BUSCAR UN EVENTO*/
    // Busqueda Evaluado y evento
    public function mostrarVistaBuscarEvento(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();

        

        return view('administrador.busquedaEvento', compact('user'));
    }
    // Resultado de busqueda
    public function consultaInformacionEvento(Request $request){
    
        $consultar_nro_identificacion = $request->consultar_nro_identificacion;
        $consultar_id_evento = $request->consultar_id_evento;

        switch(true)
        {
            case (!empty($consultar_nro_identificacion) and empty($consultar_id_evento)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where('Nro_identificacion', $consultar_nro_identificacion)
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));
                if(count($array_informacion_eventos)>0){
                    return response()->json($informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            case (!empty($consultar_id_evento) and empty($consultar_nro_identificacion)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where('ID_evento', $consultar_id_evento)
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));
                if(count($array_informacion_eventos)>0){
                    return response()->json($informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            case (!empty($consultar_id_evento) and !empty($consultar_nro_identificacion)):
                $informacion_eventos = cndatos_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nro_identificacion', '=', $consultar_nro_identificacion],
                        ['ID_evento', '=', $consultar_id_evento]
                    ])
                    ->get();
                $array_informacion_eventos = json_decode(json_encode($informacion_eventos, true));
                if(count($array_informacion_eventos)>0){
                    return response()->json($informacion_eventos);
                }else{
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensaje" => 'No se encontraron datos acorde a la búsqueda realizada.'
                    );
                    return json_decode(json_encode($mensajes, true));
                }
            break;
            default:
            break;
        }
        
    }
}
