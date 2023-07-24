<?php

namespace App\Http\Controllers\Coordinador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use App\Models\cndatos_bandeja_pcls;
use App\Models\sigmel_lista_parametros;
use App\Models\sigmel_informacion_accion_eventos;
use App\Models\sigmel_registro_documentos_eventos;
use App\Models\sigmel_lista_documentos;

class CalificacionPCLController extends Controller
{
    public function mostrarVistaCalificacionPCL(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        $newIdAsignacion=$request->newIdAsignacion;
        $newIdEvento = $request->newIdEvento;

        $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));

        $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento)); 

        return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl','arraylistado_documentos'));
    }

    public function cargueListadoSelectoresModuloCalifcacionPcl(Request $request){
        $parametro = $request->parametro;

        // Listado Modalidad calificacion

        if($parametro == 'lista_modalidad_calificacion_pcl'){
            $listado_modalidad_calificacion = sigmel_lista_parametros::on('sigmel_gestiones')
            ->select('Id_Parametro', 'Nombre_parametro')
            ->where([
                ['Tipo_lista', '=', 'Modalidad de Calificacion'],
                ['Estado', '=', 'activo']
            ])
            ->get();

            $info_listado_modalidad_calificacion = json_decode(json_encode($listado_modalidad_calificacion, true));
            return response()->json($info_listado_modalidad_calificacion);

        }
    }

    public function guardarCalificacionPCL(Request $request){
        if (!Auth::check()) {
            return redirect('/');
        }
        $time = time();
        $date = date("Y-m-d", $time);
        $user = Auth::user();
        $nombre_usuario = Auth::user()->name;
        $newIdAsignacion = $request->newId_asignacion;
        $newIdEvento = $request->newId_evento;
        // validacion de bandera para guardar o actualizar
        if ($request->bandera_accion_guardar_actualizar == 'Guardar') {
               
            // insercion de datos a la tabla de sigmel_informacion_accion_eventos
    
            $datos_info__registrarCalifcacionPcl= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Modalidad_calificacion' => $request->modalidad_calificacion,
                'F_accion' => $request->f_accion,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Causal_devolucion_comite' => $request->causal_devolucion_comite,
                'Descripcion_accion' => $request->descripcion_accion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];
    
            sigmel_informacion_accion_eventos::on('sigmel_gestiones')->insert($datos_info__registrarCalifcacionPcl);
    
            $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));

            $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento)); 
    
            return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'arraylistado_documentos'));
        }elseif ($request->bandera_accion_guardar_actualizar == 'Actualizar') {
            
            // actualizacion de datos a la tabla de sigmel_informacion_accion_eventos

            $datos_info_actualizarCalifcacionPcl= [
                'ID_evento' => $request->newId_evento,
                'Id_Asignacion' => $request->newId_asignacion,
                'Modalidad_calificacion' => $request->modalidad_calificacion,
                'F_accion' => $request->f_accion,
                'Accion' => $request->accion,
                'F_Alerta' => $request->fecha_alerta,
                'Enviar' => $request->enviar,
                'Causal_devolucion_comite' => $request->causal_devolucion_comite,
                'Descripcion_accion' => $request->descripcion_accion,
                'Nombre_usuario' => $nombre_usuario,
                'F_registro' => $date,
            ];

            sigmel_informacion_accion_eventos::on('sigmel_gestiones')
            ->where('Id_Asignacion', $newIdAsignacion)->update($datos_info_actualizarCalifcacionPcl);

            $array_datos_calificacionPcl = DB::select('CALL psrcalificacionpcl(?)', array($newIdAsignacion));

            $arraylistado_documentos = DB::select('CALL psrvistadocumentos(?)',array($newIdEvento));
    
            return view('coordinador.calificacionPCL', compact('user','array_datos_calificacionPcl', 'arraylistado_documentos'));
        }
        
    }

    // Cargue de listado de Documentos Solicitados para el modal Solicitud Documentos-Seguimientos
    public function CargarDocsSolicitados(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        $parametro = $request->parametro;

        if ($parametro == 'listado_documentos_solicitados') {
            $datos_docs_solicitados = sigmel_lista_documentos::on('sigmel_gestiones')
            ->select('Id_Documento', 'Nro_documento', 'Nombre_documento')
            ->whereIn('Nro_documento', [4,31,9,28,29,30,37])->get();

            $informacion_docs_solicitados = json_decode(json_encode($datos_docs_solicitados), true);
            return response()->json($informacion_docs_solicitados);
        }
    }

    /* public function cargaListadoDocumentosInicialNuevo(Request $request){
        
        $time = time();
        $date = date("Y-m-d", $time);
        $nombre_usuario = Auth::user()->name;

        //Validación N° 1: TAMAÑO DEL ARCHIVO 
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

        //Validación N° 2: Cuando el documento que se intenta cargar son de los que no son obligatorios y aún así se manda vacío el dato 
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

        //Validación N° 3: EL ID DEL EVENTO DEBE ESTAR ESCRITO 
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
        
        //Validación N° 4: TIPO DE DOCUMENTO 
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

        //Si las validaciones son exitosas, Se procede a subir el documento 

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

    } */
}
