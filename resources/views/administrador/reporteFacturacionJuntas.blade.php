@extends('adminlte::page')
@section('title', 'Reporte Facturación Juntas')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
<div class="card-info" style="border: 1px solid black;">
    <div class="card-header text-center">
        <h4>Módulo Reportes</h4>
        <h5 style="font-style: italic;">Reporte Facturación Juntas</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="card-info">
                    <div class="card-header text-center" style="border: 1.5px solid black;">
                        <h5>Correspondencia</h5>
                    </div>
                    <div class="card-body">
                        <form id="form_consulta_reporte_facturacion_juntas" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="" class="col-form-label">Fecha Desde <span style="color: red;">(*)</span></label>
                                        <input type="date" class="form-control" name="fecha_desde" id="fecha_desde" max="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="" class="col-form-label">Fecha Hasta <span style="color: red;">(*)</span></label>
                                        <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta" max="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <p>&nbsp;</p>
                                        <input type="submit" id="btn_generar_reporte" class="btn btn-info" value="Consultar">
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- DESDE AQUI SE MUESTRA LA INFORMACIÓN DEL REPORTE --}}
                        {{-- Validaciones --}}
                        <div class="col-12">
                            <div class="resultado_validacion alert mt-1 d-none" id="llenar_mensaje_validacion" role="alert">
                                <strong></strong>
                            </div>
                        </div>
                        {{-- Nro de registros --}}
                        <div class="row d-none" id="div_info_numero_registros">
                            <div class="col 12">
                                <label>Se encontraron <span id="total_registros_reporte_facturacion_juntas"></span> registros.</label>
                            </div>
                        </div>
                        {{-- Tabla para registrar los datos --}}
                        <div class="row d-none">
                            <div class="col-12">
                                <div class="table table-responsive">
                                    <table id="datos_reporte_facturacion_juntas" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>Cons</th>
                                                <th>NRO_SINIESTRO</th>
                                                <th>ID_TIPO_DOC</th>
                                                <th>IDENTIFICACION</th>
                                                <th>NOMBRE</th>
                                                <th>TIPO_AFILIADO</th>
                                                <th>FECHA_NOTIFICACION_AFILIADO</th>
                                                <th>FECHA_CONTROVERSIA_AFILIADO</th>
                                                <th>FECHA_PLAZO_AFILIADO</th>
                                                <th>FECHA_RADICACION</th>
                                                <th>FECHA_PAGO_HONORARIOS_JR</th>
                                                <th>FUENTE_INFORMACION</th>
                                                <th>TIPO_EVENTO</th>
                                                <th>TIPO_CONTROVERSIA</th>
                                                <th>TIPO_CONTROVERSIA2</th>
                                                <th>TIPO_CONTROVERSIA3</th>
                                                <th>TIPO_CONTROVERSIA4</th>
                                                <th>TIPO_CONTROVERSIA5</th>
                                                <th>DX_PRINCIPAL</th>
                                                <th>DIAGNOSTICO 2</th>
                                                <th>DIAGNOSTICO 3</th>
                                                <th>DIAGNOSTICO 4</th>
                                                <th>DIAGNOSTICO 5</th>
                                                <th>DIAGNOSTICO 6</th>
                                                <th>ACCIDENTE ENFERMEDAD</th>
                                                <th>ORIGEN_1A_OPORTUNIDAD</th>
                                                <th>CALIFICACION_PCL</th>
                                                <th>FECHA_ESTRUCTURACION</th>
                                                <th>ENTIDAD_CALIFICA_1A_OPO</th>
                                                <th>PARTE_INTERPONE_RECURSO</th>
                                                <th>FECHA_PAGO_JR</th>
                                                <th>FECHA_PAGO_JR_RADICADO</th>
                                                <th>FECHA_ENVIO_A_JR</th>
                                                <th>GUIA_JUNTA</th>
                                                <th>GUIA_AFILIADO</th>
                                                <th>GUIA_RTA_JUNTA_REGIONAL</th>
                                                <th>FECHA_REENVIO_A_JR</th>
                                                <th>FECHA_REENVIO_2_A_JR</th>
                                                <th>FECHA_REENVIO_3_A_JR</th>
                                                <th>JUNTA_REGIONAL</th>
                                                <th>FECHA_RADICADO_DICTAMEN_JR</th>
                                                <th>FECHA_DICTAMEN_JUNTA</th>
                                                <th>ORIGEN_JR</th>
                                                <th>TOTAL MINUSVALIA_JR</th>
                                                <th>TOTAL DISCAPACIDAD_JR</th>
                                                <th>TOTAL DEFICIENCIA_JR</th>
                                                <th>TOTAL ROL LABORAL_JR</th>
                                                <th>CALIFICACION_PCL_JR</th>
                                                <th>FECHA_ESTRUCTURACION_JR</th>
                                                <th>ARL</th>
                                                <th>EPS</th>
                                                <th>FECHA_SOL_CONSTANCIA_EJE</th>
                                                <th>FECHA_RECIBIDO_DICTAMEN_JR</th>
                                                <th>FECHA_PAGO_JN</th>
                                                <th>FECHA_PAGO_JN_RADICADO</th>
                                                <th>FECHA_ENVIO_JN</th>
                                                <th>FECHA_DICTAMEN_JN</th>
                                                <th>ORIGEN_JN</th>
                                                <th>CALIFICACION_PCL_JN</th>
                                                <th>FECHA_ESTRUCTURACION_JN</th>
                                                <th>FUNCIONARIO_ACTUAL</th>
                                                <th>FUNCIONARIO_ULTIMA_ACCION</th>
                                                <th>ESTADO_ACTUAL</th>
                                                <th>OBSERVACION</th>
                                                <th>FECHA_ASIGNAR_PROFESIONAL</th>
                                                <th>FECHA_ACUERDO</th>
                                                <th>FECHA_CONTROVERSIA</th>
                                                <th>FECHA DE NOTIFICACIÓN A ALFA</th>
                                                <th>FECHA GUIA DE SALIDA - CORRESPONDENCIA AFILIADO</th>
                                                <th>FECHA GUIA DE SALIDA - CORRESPONDENCIA JR</th>
                                                <th>ANS DÍAS</th>
                                                <th>ANS ESTADO</th>
                                                <th>OBSERVACIÓN</th>
                                                <th>CORTE</th>
                                                <th>FECHA PAGO JR</th>
                                                <th>FECHA ENVIO EFECTIVO A LA JR</th>
                                            </tr>
                                        </thead>
                                        <tbody id="vaciar_tabla_reporte_facturacion_juntas"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- Generar reporte Excel --}}
                        <br>
                        <div class="row d-none" id="botones_reporte_facturacion_juntas">
                            <div class="col-12">
                                <div style="float: left;">
                                    <input type="button" id="btn_expor_datos_reporte_facturacion_juntas" class="btn btn-info" value="Generar Reporte"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                        <i class="fas fa-chevron-up"></i>
                    </a> 
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.templates.min.js"></script>

    <script src="/js/reporte_facturacion_juntas.js"></script>
@stop