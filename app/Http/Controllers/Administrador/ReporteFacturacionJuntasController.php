<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\reporte_facturacion_juntas_s;

class ReporteFacturacionJuntasController extends Controller
{
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
            ->orderBy('ID_evento', 'desc')
            ->get();
            $array_reporte_facturacion_juntas = json_decode(json_encode($reporte_facturacion_juntas, true)); 
            return response()->json($array_reporte_facturacion_juntas);
        }
    }
}
