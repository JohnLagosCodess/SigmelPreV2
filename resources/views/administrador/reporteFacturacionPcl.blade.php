@extends('adminlte::page')
@section('title', 'Reporte Facturación PCL')
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
            <h5 style="font-style: italic;">Reporte Facturación PCL</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card-info">
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Correspondencia</h5>
                        </div>
                        <div class="card-body">
                            <form id="form_consulta_reporte_facturacion_pcl" method="POST">
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
                                    <label>Se encontraron <span id="total_registros_reporte_facturacion_pcl"></span> registros.</label>
                                </div>
                            </div>
                            {{-- Tabla para registrar los datos --}}
                            <div class="row d-none">
                                <div class="col-12">
                                    <div class="table table-responsive">
                                        <table id="datos_reporte_facturacion_pcl" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="bg-info">
                                                    <th>Cons</th>
                                                    <th>SERVICIO</th>
                                                    <th>TIPO DE AFILIADO</th>
                                                    <th>FECHA DE RADICACION A CODESS</th>
                                                    <th>N° SINIESTRO</th>
                                                    <th>DOCUMENTO</th>
                                                    <th>NOMBRE</th>
                                                    <th>FECHA DE SOLICITUD DE DOCUMENTOS</th>
                                                    <th>FECHA DE DICTAMEN</th>
                                                    <th>TOTAL MINUSVALIA</th>
                                                    <th>TOTAL DISCAPACIDAD</th>
                                                    <th>TOTAL DEFICIENCIA</th>
                                                    <th>TOTAL ROL LABORAL</th>
                                                    <th>FECHA ESTRUCTURACION </th>
                                                    <th>CALIFICACION</th>
                                                    <th>ORIGEN</th>
                                                    <th>TIPO EVENTO</th>
                                                    <th>CALIFICADO CON</th>
                                                    <th>ESTADO</th>
                                                    <th>CIE 10 1</th>
                                                    <th>DIAGNOSTICO 1</th>
                                                    <th>CIE 10 2</th>
                                                    <th>DIAGNOSTICO 2</th>
                                                    <th>CIE 10 3</th>
                                                    <th>DIAGNOSTICO 3</th>
                                                    <th>CIE 10 4</th>
                                                    <th>DIAGNOSTICO 4</th>
                                                    <th>CIE 10 5</th>
                                                    <th>DIAGNOSTICO 5</th>
                                                    <th>CIE 10 6</th>
                                                    <th>DIAGNOSTICO 6</th>
                                                    <th>REQ_AYUDA_TERCERO</th>
                                                    <th>REQ_TERCERO_TOMA_DECISIONES</th>
                                                    <th>REQUIERE_REVISION_PENSION</th>
                                                    <th>EMPLEADOR</th>
                                                    <th>ARL</th>
                                                    <th>EPS</th>
                                                    <th>GUIA AFILIADO</th>
                                                    <th>GUIA EPS</th>
                                                    <th>GUIA AFP</th>
                                                    <th>GUIA EMPLEADOR</th>
                                                    <th>GUIA ARL</th>
                                                    <th>NOMBRE DEPARTAMENTO</th>
                                                    <th>FECHA DE CORRESPONDENCIA</th>
                                                    <th>FECHA DE NOTIFICACIÓN A ALFA</th>
                                                    <th>CALIFICADOR</th>
                                                    <th>ANS DIAS</th>
                                                    <th>ANS ESTADO </th>
                                                    <th>OBSERVACICIONES</th>
                                                    <th>TIPO DE SERVICIO</th>
                                                    <th>TIPO DE ENVIO</th>
                                                    <th>CORTE</th>
                                                    <th>ENTIDAD QUE REMITE DICTAMEN</th>
                                                    <th>PORCENTAJE DEFICIENCIA</th>
                                                </tr>
                                            </thead>
                                            <tbody id="vaciar_tabla_reporte_facturacion_pcl"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- Generar reporte Excel --}}
                            <br>
                            <div class="row d-none" id="botones_reporte_facturacion_pcl">
                                <div class="col-12">
                                    <div style="float: left;">
                                        <input type="button" id="btn_expor_datos_reporte_facturacion_pcl" class="btn btn-info" value="Generar Reporte"> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a>
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

    <script src="/js/reporte_facturacion_pcl.js"></script>
@stop