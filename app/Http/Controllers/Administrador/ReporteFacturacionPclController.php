<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Coordinador\CoordinadorController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\reporte_facturacion_pcls;

class ReporteFacturacionPclController extends Controller
{

    /** @var Array Contiene los procesos a ejecutar para los comunicados
     * La llave siempre debe ser el comunicado sobre el cual se esta procesando.
     * acciones -> Contiene las acciones disponibles para que el comunicado pueda ser mostrado.
     * estados -> Contiene los estados (estado general de la notificacion) para mostrar dicho comunicado.
     * match_correspondencia -> Contiene los destinatarios, en los cuales se obtendra la correspodencia del comunicado consultado.
     */
    private $acciones_comunicado = [
        "Comunicado" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
        "Acuerdo" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
        "Desacuerdo" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
        "Oficio" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
        "Documento_PCL" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
        "Documento_calificacion_tecnica" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
        "Documento_No_Recalificacion" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
        "Documento_Revision_pension" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
        "Formato_B_Revision_pension" => [
            "acciones" => [141, 144, 148, 134, 136, 142, 173, 175,
            176, 149, 151, 152, 153, 154],
            "estados" => [356, 357, 358, 359, 360],
            "match_correspondencia" => "Afiliado,Empleador,eps,afp,arl"
        ],
    ];
    
    public function show(){
        if(!Auth::check()){
            return redirect('/');
        }
        $user = Auth::user();
        return view('administrador.reporteFacturacionPcl', compact('user'));
    }

    /* Función para consultar el reporte de facturación PCL */
    public function consultaReporteFactuPcl(Request $request){
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
            $reporte_facturacion_pcl = reporte_facturacion_pcls::on('sigmel_gestiones')
            ->select('Nombre_Servicio',
                'ID_evento',
                'Id_Asignacion',
                'Accion_ejecutada',
                'Tipo_Afiliado',
                'Fecha_Radicacion_A_Codess',
                'Nro_Siniestro',
                'Documento',
                'Nombre',
                'Fecha_Solicitud_Documentos',
                'Fecha_Dictamen',
                'Total_Minusvalia',
                'Total_Discapacidad',
                'Total_Deficiencia',
                'Total_Rol_Laboral',
                'Fecha_Estructuracion',
                'Calificacion',
                'Origen',
                'Tipo_Evento',
                'Calificado_Con',
                'Estado',
                'Cie10_1',
                'Diagnostico_1',
                'Cie10_2',
                'Diagnostico_2',
                'Cie10_3',
                'Diagnostico_3',
                'Cie10_4',
                'Diagnostico_4',
                'Cie10_5',
                'Diagnostico_5',
                'Cie10_6',
                'Diagnostico_6',
                'Requiere_Ayuda_Tercero',
                'Requiere_Tercero_Toma_Decisiones',
                'Requiere_Revision_Pension',
                'Empleador',
                'ARL',
                'EPS',
                'Guia_Afiliado',
                'Guia_Eps',
                'Guia_Afp',
                'Guia_Empleador',
                'Guia_Arl',
                'Nombre_Departamento',
                'Fecha_Correspondencia',
                'Fecha_Notificacion_Alfa',
                'Calificador',
                'Ans_Dias',
                'Ans_Estado',
                'Observaciones',
                'Tipo_Servicio',
                'Tipo_Envio',
                'Corte',
                'Entidad_Remite_Dictamen',
                'Porcentaje_Deficiencia'
            )
            ->whereRaw('DATE(F_accion) BETWEEN ? AND ?', [$fecha_desde, $fecha_hasta])
            ->orderBy('F_accion', 'asc')
            ->get()->toArray();

            $reporte_facturacion_pcl = $this->combinar_reporte_correspondencia($reporte_facturacion_pcl);

            //Elimina los elementos para que queden acorde a las columnas del excel
            $reporte_facturacion_pcl =  array_map(function($element) {
                unset($element['ID_evento'], $element['Id_Asignacion'], $element['Accion_ejecutada']);
                return $element;
            }, $reporte_facturacion_pcl);
            
            return response()->json($reporte_facturacion_pcl);
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
                $item["Guia_{$tipo_correspondencia}"] =  $correspondencia->N_guia;
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
