<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Coordinador\CoordinadorController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\reporte_facturacion_juntas_s;

class ReporteFacturacionJuntasController extends Controller
{

    /** @var Array Contiene los procesos a ejecutar para los comunicados
     * La llave siempre debe ser el comunicado sobre el cual se esta procesando.
     * acciones -> Contiene las acciones disponibles para que el comunicado pueda ser mostrado.
     * estados -> Contiene los estados (estado general de la notificacion) para mostrar dicho comunicado.
     * match_correspondencia -> Contiene los destinatarios, en los cuales se obtendra la correspodencia del comunicado consultado.
     */
    private $acciones_comunicado = [
        "Oficio_Afiliado" => [
            "acciones" => [144, 148, 130, 162, 163, 164],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,jrci"
        ],
        "ACUERDO JRCI" => [
            "acciones" => [144, 148, 130, 162, 163, 164],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,jrci"
        ],
        "RECURSO JRCI" => [
            "acciones" => [144, 148, 130, 162, 163, 164],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,jrci"
        ],
    ];

    public function show(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.reporteFacturacionJuntas', compact('user'));
    }

    /* Función para consultar el reporte de facturación Juntas */
    public function consultaReporteFactuJuntas(Request $request){
        if(!Auth::check()){
            return redirect('/');
        }

        /* Captura de variables */
        $fecha_desde = $request->fecha_desde;
        $fecha_hasta = $request->fecha_hasta;

        // Validaciones
        /* 
            1: Fecha desde está vacío y Fecha hasta tiene dato = No hay reporte.
            2: Fecha desde tiene dato y Fecha hasta está vació = No hay reporte.
            3. Fecha desde y Fecha hasta están vacíos = Se genera reporte completo sin fechas.
            4. Fecha desde y Fecha hasta tienen datos = Se genera reporte completo dependiendo del rango de fechas seleccionado.
        */

        if (empty($fecha_desde) && !empty($fecha_hasta)) {
            $mensajes = array(
                "parametro" => 'falta_un_parametro',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));

        }
        elseif (!empty($fecha_desde) && empty($fecha_hasta)) {
            $mensajes = array(
                "parametro" => 'falta_un_parametro',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));

        }
        elseif (empty($fecha_desde) && empty($fecha_hasta)) {

            $mensajes = array(
                "parametro" => 'falta_un_parametro',
                "mensaje" => 'Debe seleccionar las dos fechas para realizar la consulta.'
            );
            return json_decode(json_encode($mensajes, true));
        }
        else if (!empty($fecha_desde) && !empty($fecha_hasta)){
            $reporte_facturacion_juntas = reporte_facturacion_juntas_s::on('sigmel_gestiones')
            ->select('Nro_Siniestro',
                'ID_evento',
                'Id_Asignacion',
                'Accion_ejecutada',
                'Tipo_Documento',
                'Identificacion',
                'Nombre',
                'Tipo_Afiliado',
                'Fecha_Notificacion_Afiliado',
                'Fecha_Controversia_Afiliado',
                'Fecha_Plazo_Afiliado',
                'Fecha_Radicacion',
                'Fecha_Pago_Honorarios_JR',
                'Fuente_Informacion',
                'Tipo_Evento',
                'Tipo_Controversia1',
                'Tipo_Controversia2',
                'Tipo_Controversia3',
                'Tipo_Controversia4',
                'Tipo_Controversia5',
                'Dx_Principal',
                'Diagnostico2',
                'Diagnostico3',
                'Diagnostico4',
                'Diagnostico5',
                'Diagnostico6',
                'Accidente_Enfermedad',
                'Origen_1A_Oportunidad',
                'Calificacion_Pcl',
                'Fecha_Estructuracion',
                'Entidad_Califica_1A_Opo',
                'Parte_Interpone_Recurso',
                'Fecha_Pago_Jr',
                'Fecha_Pago_Jr_Radicado',
                'Fecha_Envio_A_Jr',
                'Guia_Junta',
                'Guia_Afiliado',
                'Guia_Rta_Junta_Regional',
                'Fecha_Reenvio_A_Jr',
                'Fecha_Reenvio_2_A_Jr',
                'Fecha_Reenvio_3_A_Jr',
                'Junta_Regional',
                'Fecha_Radicado_Dictamen_Jr',
                'Fecha_Dictamen_Junta',
                'Origen_Jr',
                'Total_Minusvalia_Jr',
                'Total_Discapacidad_Jr',
                'Total_Deficiencia_Jr',
                'Total_Rol_Laboral_Jr',
                'Calificacion_Pcl_Jr',
                'Fecha_Estructuracion_Jr',
                'ARL',
                'EPS',
                'Fecha_Sol_Constancia_Eje',
                'Fecha_Recibido_Dictamen_Jr',
                'Fecha_Pago_Jn',
                'Fecha_Pago_Jn_Radicado',
                'Fecha_Envio_A_Jn',
                'Fecha_Dictamen_Jn',
                'Origen_Jn',
                'Calificacion_Pcl_Jn',
                'Fecha_Estructuracion_Jn',
                'Funcionario_Actual',
                'Funcionario_Ultima_Accion',
                'Estado',
                'Observacion_1',
                'Fecha_Asignar_Profesional',
                'Fecha_Acuerdo',
                'Fecha_Controversia',
                'Fecha_De_Notificacion_A_Alfa',
                'Fecha_Guia_De_Salida_Correspondencia_Afiliado',
                'Fecha_Guia_De_Salida_Correspondencia_Jr',
                'Ans_Dias',
                'Ans_Estado',
                'Observacion_2',
                'Corte',
                'Fecha_Pago_Jr_blanco',
                'Fecha_Envio_Efectvio_A_La_Jr'
            )
            ->whereRaw('DATE(F_accion) BETWEEN ? AND ?', [$fecha_desde, $fecha_hasta])
            ->orderBy('F_accion', 'asc')
            ->get()->toArray();

            $reporte_facturacion_juntas = $this->combinar_reporte_correspondencia($reporte_facturacion_juntas);

            //Elimina los elementos para que queden acorde a las columnas del excel
            $reporte_facturacion_juntas =  array_map(function($element) {
                unset($element['ID_evento'], $element['Id_Asignacion'], $element['Accion_ejecutada']);
                return $element;
            }, $reporte_facturacion_juntas);

            return response()->json($reporte_facturacion_juntas);
        }
    }

    /**
     * Obtiene las correspodencia por cada registro obtenido en el reporte.
     * @param Array Reporte generado por {reporte_facturacion_pcls}
     * @return Array Devuelve el reporte seteado
     */
    public function combinar_reporte_correspondencia(Array $reporte){

        /**
         * @var callable Setea los campos de la correspondencia para las parte de las guias.
         * @param Array $procesar Corresponde a la correspondencia que se esta evaluando
         * @param Array $item Corresponde a cada item del reporte de facturacion.
         */
        $asignar_guia = function(Array $procesar,$item){
            foreach($procesar as $correspondencia){
                $tipo_correspondencia = ucfirst($correspondencia->Tipo_correspondencia);
                if($correspondencia->Tipo_correspondencia == "jrci"){
                    $item["Guia_Rta_Junta_Regional"] = $correspondencia->N_guia;
                }else{
                    $item["Guia_{$tipo_correspondencia}"] =  $correspondencia->N_guia;
                }
            }
            return $item;
        };
        
        /** @var Array Contiene los datos del reporte de facturacion seteados con los datos de la correspondencia */
        $correspondencias = [];

        foreach ($reporte as $item) {

            /** Contiene los datos de la correspodencia en funcion del comunicado para el evento que se esta consultando */
            $comunicados = CoordinadorController::getCorrespondencia_comunicado($item["ID_evento"],$item["Id_Asignacion"],$item["Accion_ejecutada"],$this->acciones_comunicado);
            Log::channel('reportes_facturacion')->info("Fila reporte",[
                "evento" => $item["ID_evento"],
                "id_asignacion" => $item["Id_Asignacion"],
                "accion_ejecutada" => $item["Accion_ejecutada"],
                "comunicados" => $comunicados
            ]);

            if(!empty($comunicados)){
                foreach ($comunicados as $correspondencia) {
                    $correspondencias[] = $asignar_guia($correspondencia,$item);
                }
            }else{
                array_push($correspondencias,$item);
            }
        }
        
        return $correspondencias;
    } 
}
