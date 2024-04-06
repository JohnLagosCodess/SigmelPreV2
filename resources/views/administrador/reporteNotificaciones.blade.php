@extends('adminlte::page')
@section('title', 'Reporte Notificaciones')
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
            <h5 style="font-style: italic;">Reporte Notificaciones</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card-info">
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Correspondencia</h5>
                        </div>
                        <div class="card-body">
                            <form id="form_consulta_reporte_notificaciones" method="POST">
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
                                    <label>Se encontraron <span id="total_registros_reporte_notificaciones"></span> registros.</label>
                                </div>
                            </div>

                            {{-- Tabla para registrar los datos --}}
                            <div class="row d-none">
                                <div class="col-12">
                                    <div class="table table-responsive">
                                        <table id="datos_reporte_notificaciones" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="bg-info">
                                                    <th>Cons</th>
                                                    <th>Fecha de Envío</th>
                                                    <th>No. de Identificación</th>
                                                    <th>No. de Guía Asignado</th>
                                                    <th>Orden de Impresión	</th>
                                                    <th>Proceso</th>
                                                    <th>Servicio</th>
                                                    <th>Última Acción</th>
                                                    <th>Estado</th>
                                                    <th>No. de OIP	</th>
                                                    <th>Tipo de Destinatario</th>
                                                    <th>Nombre de Destinatario	</th>
                                                    <th>Dirección</th>
                                                    <th>Teléfono</th>
                                                    <th>Departamento</th>
                                                    <th>Ciudad</th>
                                                    <th>Folios Entregados</th>
                                                    <th>Medio de Notificación</th>
                                                    <th>Correo Electrónico</th>
                                                    <th>Archivo 1</th>
                                                    <th>Archivo 2</th>
                                                </tr>
                                            </thead>
                                            <tbody id="vaciar_tabla_reporte_notificaciones">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Generar reporte excel y descarga del zip --}}
                            <br>
                            <div class="row d-none" id="botones_reporte_notificaciones">
                                <div class="col-12">
                                    <div style="float: left;">
                                        <input type="button" id="btn_expor_datos_reporte_notificaciones" class="btn btn-info" value="Generar Reporte"> 
                                        <input type="button" id="btn_generar_zip_reporte_notificaciones" class="btn btn-info" value="Descargar Zip">
                                    </div>
                                </div>
                            </div>
                        </div>
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

    <script src="/js/reporte_notificaciones.js"></script>
@stop