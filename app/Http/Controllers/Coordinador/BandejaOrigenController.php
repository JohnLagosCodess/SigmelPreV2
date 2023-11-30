<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
//llamado de modelos para formulario BandejaOrigen y captura de data
use App\Models\sigmel_lista_procesos_servicios;
use App\Models\cndatos_bandeja_eventos;
use App\Models\sigmel_informacion_asignacion_eventos;


class BandejaOrigenController extends Controller
{
    // Bandeja Origen Coordinador
    public function mostrarVistaBandejaOrigen(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();        

        return view('coordinador.bandejaOrigen', compact('user'));
    }

    //Selectores Bandeja Origen
    public function cargueListadoSelectoresBandejaOrigen(Request $request){
        $parametro = $request->parametro;
        
        if ($parametro == 'lista_profesional_origen') {
            
            $listado_profesional_origen = DB::table('users')->select('id', 'name')
            ->where('estado', 'Activo')
            ->whereRaw("FIND_IN_SET(1, id_procesos_usuario) > 0")->get();

            $info_listado_profesional_Origen = json_decode(json_encode($listado_profesional_origen, true));
            return response()->json($info_listado_profesional_Origen);
        }

        //Listado servicio proceso Origen
        if($parametro == 'lista_servicios_origen'){
            $listado_servicio_Origen = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            ->select('Id_Servicio', 'Nombre_servicio')
            ->where([
                ['Nombre_proceso', '=', 'Origen'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_servicio_Origen = json_decode(json_encode($listado_servicio_Origen, true));
            return response()->json($info_listado_servicio_Origen);
        }
    }

    public function sinFiltroBandejaOrigen(Request $request){

        $BandejaOrigenTotal = $request->BandejaOrigenTotal;        

        if($BandejaOrigenTotal == 'CargaBandejaOrigen'){

            // Consultar la vista de mysql, traer eventos acorde al proceso
            $bandejaOrigen = cndatos_bandeja_eventos::on('sigmel_gestiones')
            ->where([
                ['Nombre_proceso_actual', '=', 'Origen']
            ])
            ->get();
            // $ID_evento_bandeja = $bandejaOrigen[0]->ID_evento;
            // $Id_proceso_bandeja = $bandejaOrigen[0]->Id_proceso;

            // Json vacio para llenado en Else en caso de que no haya un proceso anterior            
            //$Ids_Nombre_proceso_anterior = response()->json([]);

            // validacion de la vista

            // if (!empty($bandejaOrigen[0]->Nombre_proceso_actual)) {
            //     $datos_bandejaOrigen = [];
            //     foreach ($bandejaOrigen as $item) {
            //         // Accede a cada propiedad del objeto dentro del bucle  para capturar el id_asignacion                  
            //         $datos_bandejaOrigen[]=[                        
            //             'Id_Asignacion_bandeja' => $item->Id_Asignacion,
            //         ];
            //     }    
            //     // echo '<pre>';
            //     //     print_r($datos_bandejaOrigen);
            //     // echo '</pre>';             
            //     // calcular cantidad de id_asignacion 
            //     $cantidad_Id_Asignacion = count($datos_bandejaOrigen);
            //     //$Id_Asignacion_cantidad = $cantidad_Id_Asignacion; 
            //     // if ($cantidad_Id_Asignacion == 1) {                    
            //     // } else {
            //     //     # code...
            //     // }
            //     //$cantidad_Id_Asignacion += 1; 
                
            //     //echo $cantidad_Id_Asignacion;

            //     // Validar si existe un porceso anterior con los Id_Asignacion del array $datos_bandejaPCl

            //     $validar_proceso_anterior = sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')
            //     ->select('Id_proceso', 'Id_Asignacion')
            //     ->where('ID_evento', $ID_evento_bandeja);
            //     foreach ($datos_bandejaOrigen as $dato) {
            //         $validar_proceso_anterior->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja']);
            //     }
            //     // foreach ($datos_bandejaOrigen as $dato) {
            //     //     $validar_proceso_anterior->orWhere(function ($query) use ($dato) {
            //     //         $query->where('Id_Asignacion', '<', $dato['Id_Asignacion_bandeja']);
            //     //     });
            //     // }
            //     $validar_proceso_anterior = $validar_proceso_anterior
            //     ->orderBy('Id_Asignacion', 'desc')
            //     ->limit($cantidad_Id_Asignacion)
            //     ->get();
            //     // echo '<pre>';
            //     //     print_r($validar_proceso_anterior);
            //     // echo '</pre>';
            //     // echo count($validar_proceso_anterior);
            //     // Si se cumple la validacion anterior entra al IF Si no entra al Else y Utiliza el Json

            //     if (count($validar_proceso_anterior) > 0) {
            //         //echo 'IF';
            //         // Se construyen los id del proceso y asignacion
            //         $proceso_id = [];
            //         $asignacion_id = [];
            //         foreach ($validar_proceso_anterior as $key) {
            //             $proceso_id[]=[                        
            //                 'Id_Proceso_anterior' => $key->Id_proceso
            //             ];    
            //             $asignacion_id[] = [
            //                 'Id_Asignacion_bandeja' => $key->Id_Asignacion
            //             ];
            //         } 

            //         // Validar el nombre del proceso anterior en base a los id_procesos optenidos en el array  $proceso_id

            //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         ->select('Nombre_proceso', 'Id_proceso')->whereIn('Id_proceso', $proceso_id)
            //         ->groupBy('Nombre_proceso')
            //         ->get();  
            //         // construir array para los nombres de los procesos
            //         $nombre_proceso = [];
            //         foreach ($validar_Nombre_proceso_anterior as $value) {
            //             $nombre_proceso[] = [
            //                 'Nombre_proceso_anterior' => $value->Nombre_proceso,
            //                 'Id_proceso' => $value->Id_proceso,
            //             ];
            //         }   

            //         // acondicionar los array acorde al order la consulta Inicial de $bandejaPCL
            //         array_shift($proceso_id);                    
            //         $orden_proceso_id = array_reverse($proceso_id);
            //         array_pop($asignacion_id);
            //         $orden_asignacion_id = array_reverse($asignacion_id);

            //         //Combinar los array de  orden_proceso_id y nombre_proceso acorde al proceso
            //         foreach ($orden_proceso_id as $key => $value) {
            //             foreach ($nombre_proceso as $item) {
            //                 if ($value['Id_Proceso_anterior'] == $item['Id_proceso']) {
            //                     $orden_proceso_id[$key]['Nombre_proceso_anterior'] = $item['Nombre_proceso_anterior'];
            //                     break;
            //                 }
            //             }
            //         }

            //         // combinar array anterior orden_proceso_id con el array orden_asignacion_id 
            //         $combinar_proceso_asignacion = array();
            //         foreach ($orden_asignacion_id as $key => $valor) {
            //             if (isset($orden_proceso_id[$key])) {
            //                 // Fusionar arrays
            //                 $combinar_proceso_asignacion[] = array_merge($orden_proceso_id[$key], $valor);
            //             }
            //         } 

            //         // se convierte el array  $bandejaOrigen a un object
            //         $arraybandejaOrigen = json_decode(json_encode($bandejaOrigen, true));
            //         // Combinar el array object con el array combinar_proceso_asignacion
            //         foreach ($arraybandejaOrigen as $key2 => $value2) {
            //             foreach ($combinar_proceso_asignacion as $value1) {
            //                 // Verifica si los Id_Asignacion coinciden
            //                 if ($value2->Id_Asignacion == $value1['Id_Asignacion_bandeja']) {
            //                     // Agrega las propiedades al segundo array
            //                     $arraybandejaOrigen[$key2]->Id_Proceso_anterior = $value1['Id_Proceso_anterior'];
            //                     $arraybandejaOrigen[$key2]->Nombre_proceso_anterior = $value1['Nombre_proceso_anterior'];
            //                 }
            //             }
            //         }
            //     } else {
            //         //echo 'ELSE';

            //         // Validar el nombre del proceso que viene siendo el mismo ya que no hay uno anterior                
            //         $validar_Nombre_proceso_anterior = sigmel_lista_procesos_servicios::on('sigmel_gestiones')
            //         ->select('Nombre_proceso')->where([['Id_proceso', $Id_proceso_bandeja]])
            //         ->limit(1)->get(); 
            //         // Se alimenta el Json Vacio
            //         $Ids_Nombre_proceso_anterior = response()->json([
            //             'Id_Proceso_anterior' => $Id_proceso_bandeja,
            //             'Nombre_proceso_anterior' => $validar_Nombre_proceso_anterior[0]->Nombre_proceso,
            //         ]);
            //         // Se Captura los valosres del json y se agregan al object $arraybandejaOrigen
            //         $Ids_Nombre_proceso_anterior_array = json_decode($Ids_Nombre_proceso_anterior->getContent(), true);                    
            //         $arraybandejaOrigen = json_decode(json_encode($bandejaOrigen, true));    
            //         $arraybandejaOrigen[0]->Id_Proceso_anterior = $Ids_Nombre_proceso_anterior_array['Id_Proceso_anterior'];
            //         $arraybandejaOrigen[0]->Nombre_proceso_anterior = $Ids_Nombre_proceso_anterior_array['Nombre_proceso_anterior'];
                    
            //     }
            // }

            $arraybandejaOrigen = json_decode(json_encode($bandejaOrigen, true));                                                 
            return response()->json($arraybandejaOrigen);                

        }
    }

    public function filtrosBandejaOrigen(Request $request){
        
        $consultar_f_desde = $request->consultar_f_desde;
        $consultar_f_hasta = $request->consultar_f_hasta;
        $consultar_g_dias = $request->consultar_g_dias;
              
        switch (true) {
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):

                    $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Origen'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    ])
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();
                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);
                    
                    // $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Origen'],
                    //         ['Id_proceso_anterior', '<>', 1],
                    //         ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    //     ])            
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    // ->union($bandejaOrigensin_Pro_ant)
                    // ->get();
            
                    $arraybandejaOrigenFiltros = json_decode(json_encode($bandejaOrigenFiltros, true));
                    if (count($arraybandejaOrigenFiltros)>0) {
                        return response()->json($arraybandejaOrigenFiltros);                        
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada.',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }                    
            break;
            case (!empty($consultar_f_desde) and !empty($consultar_f_hasta) and empty($consultar_g_dias)):
                    
                    $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Origen'],
                    ])
                    ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    ->get();

                    // ->whereNull('Nombre_proceso_anterior')
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta]);

                    // $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Origen'],
                    //         ['Id_proceso_anterior', '<>', 1],
                    //     ])            
                    // ->whereBetween('F_registro_asignacion', [$consultar_f_desde ,$consultar_f_hasta])
                    // ->union($bandejaOrigensin_Pro_ant)
                    // ->get();                    

                    $arraybandejaOrigenFiltros = json_decode(json_encode($bandejaOrigenFiltros, true));
                    if (count($arraybandejaOrigenFiltros)>0) {
                        return response()->json($arraybandejaOrigenFiltros);
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada.',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }
            break;
            case (empty($consultar_f_desde) and empty($consultar_f_hasta) and !empty($consultar_g_dias)):
                    
                    $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    ->where([
                        ['Nombre_proceso_actual', '=', 'Origen'],
                        ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias],
                    ])
                    ->get();

                    // ->whereNull('Nombre_proceso_anterior');
                    
                    // $bandejaOrigenFiltros = cndatos_bandeja_eventos::on('sigmel_gestiones')
                    // ->where([
                    //         ['Nombre_proceso_actual', '=', 'Origen'],
                    //         ['Id_proceso_anterior', '<>', 1],
                    //         ['Dias_transcurridos_desde_el_evento', '>=', $consultar_g_dias]
                    //     ])            
                    // ->union($bandejaOrigensin_Pro_ant)
                    // ->get();
                
                    $arraybandejaOrigenFiltros = json_decode(json_encode($bandejaOrigenFiltros, true));
                    if (count($arraybandejaOrigenFiltros)>0) {
                        return response()->json($arraybandejaOrigenFiltros);
                    }else{
                        $mensajes = array(
                            "parametro" => 'sin_datos',
                            "mensajes" => 'No se encontraron registros acorde a la búsqueda realizada',
                            "registros" => 0
                        );
                        return json_decode(json_encode($mensajes, true));
                    }
            break;     
            case (!empty($consultar_f_desde) and empty($consultar_f_hasta) and empty($consultar_g_dias)):
                    $mensajes = array(
                        "parametro" => 'sin_datos',
                        "mensajes" => 'Debe ingresar la fecha Hasta para poder filtrar',
                        "registros" => 0
                    );
                    return json_decode(json_encode($mensajes, true));
            break;  
            case (empty($consultar_f_desde) and !empty($consultar_f_hasta) and empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la Fecha Desde para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;  
            case (!empty($consultar_f_desde) and empty($consultar_f_hasta) and !empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la fecha Hasta para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;  
            case (empty($consultar_f_desde) and !empty($consultar_f_hasta) and !empty($consultar_g_dias)):
                $mensajes = array(
                    "parametro" => 'sin_datos',
                    "mensajes" => 'Debe ingresar la Fecha Desde para poder filtrar',
                    "registros" => 0
                );
                return json_decode(json_encode($mensajes, true));
            break;              
            default:                
            break;
        }
    
        
    }
    public function actualizarBandejaOrigen(Request $request){

        if(!Auth::check()){
            return redirect('/');
        }
        $usuario = Auth::user()->name;        
        //print_r($request->json);
        $IdEventoBandejaOrigen = $request->array;
        $Id_profesional = $request->json['profesional'];
        $Id_Servicio_redireccionar = $request->json['redireccionar'];

        $profesional = DB::table('users')
        ->select('name')->where('id',$Id_profesional)
        ->get();
        if (count($profesional) > 0) {
            $nombre = json_decode(json_encode($profesional));
            $nombre_profesional= $nombre[0]->name; 

            $actualizar_bandejaOrigen = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional,
                'Id_servicio' => $Id_Servicio_redireccionar
            ];       

            $actualizar_bandejaOrigen_Profesional = [
                'Nombre_usuario' => $usuario,
                'Id_profesional' => $Id_profesional,
                'Nombre_profesional' => $nombre_profesional
            ]; 
        }else{
            $actualizar_bandejaOrigen_Servicio = [
                'Nombre_usuario' => $usuario,
                'Id_servicio' => $Id_Servicio_redireccionar
            ]; 
        }
        
        switch (true) {
            case (!empty($IdEventoBandejaOrigen) and !empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):
        
                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaOrigen)
                    ->update($actualizar_bandejaOrigen);
            
                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );
            
                    return json_decode(json_encode($mensajes, true));
                
            break;
            case (!empty($IdEventoBandejaOrigen) and empty($Id_profesional) and !empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaOrigen)
                    ->update($actualizar_bandejaOrigen_Servicio);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;
            
            case (!empty($IdEventoBandejaOrigen) and !empty($Id_profesional) and empty($Id_Servicio_redireccionar)):

                    sigmel_informacion_asignacion_eventos::on('sigmel_gestiones')->whereIn('Id_Asignacion', $IdEventoBandejaOrigen)
                    ->update($actualizar_bandejaOrigen_Profesional);

                    $mensajes = array(
                        "parametro" => 'actualizado_B_Origen',
                        "mensaje" => 'Se realizó la actualizacion satisfactoriamente'
                    );

                    return json_decode(json_encode($mensajes, true));

            break;

            case (!empty($IdEventoBandejaOrigen) and empty($Id_profesional) and empty($Id_Servicio_redireccionar)):
                    $mensajes = array(
                        "parametro" => 'NOactualizado_B_Origen',
                        "mensaje" => 'Debe seleccionar el Profesional o Redireccionar a, para Actualizar'
                    );

                    return json_decode(json_encode($mensajes, true));
            break;
            
            default:                
            break;
        }
        
    }

}
