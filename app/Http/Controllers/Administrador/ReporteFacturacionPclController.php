<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\reporte_facturacion_pcls;

class ReporteFacturacionPclController extends Controller
{
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
            ->get();
            $array_reporte_facturacion_pcl = json_decode(json_encode($reporte_facturacion_pcl, true)); 
            return response()->json($array_reporte_facturacion_pcl);
        }
    }
}
