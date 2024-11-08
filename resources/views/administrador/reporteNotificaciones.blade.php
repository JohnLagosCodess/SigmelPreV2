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
                            <p>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</p>
                            <form id="form_consulta_reporte_notificaciones" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Fecha Desde <span style="color: red;">(*)</span></label>
                                            <input type="date" class="form-control" name="fecha_desde" id="fecha_desde" max="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Fecha Hasta <span style="color: red;">(*)</span></label>
                                            <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta" max="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="estado_general_calificacion" class="col-form-label">Estado general de la notificación</label>
                                            <select class="estado_general_calificacion custom-select" name="estado_general_calificacion" id="estado_general_calificacion">                                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="numero_orden" class="col-form-label">N° de orden</label>
                                            <input type="text" minlength="14" class="form-control" name="numero_orden" id="numero_orden">
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
                                        <input type="text" id="nro_orden">
                                        <table id="datos_reporte_notificaciones" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="bg-info">
                                                    <th>CONS</th>                                                    
                                                    <th>ID EVENTO</th>
                                                    <th>FECHA COMUNICADO</th>
                                                    <th>N° RADICADO</th>
                                                    <th>DOCUMENTO</th>
                                                    <th>CARPETA DE IMPRESIÓN</th>
                                                    <th>OBSERVACIONES</th>
                                                    <th>N° DE IDENTIFICACIÓN</th>
                                                    <th>DESTINATARIO</th>
                                                    <th>NOMBRE DESTINATARIO</th>
                                                    <th>DIRECCION</th>
                                                    <th>TELÉFONO</th>	
                                                    <th>CIUDAD - DEPARTAMENTO</th>                                                    
                                                    <th>CORREO ELECTRONICO</th>
                                                    <th>PROCESO - SERVICIO</th>                                                    
                                                    <th>ULTIMA ACCIÓN</th>
                                                    <th>ESTADO</th>
                                                    <th>N° DE ORDEN</th>
                                                    <th>ID DESTINATARIO</th>
                                                    <th>TIPO DESTINATARIO</th>
                                                    <th>N° DE GUÍA</th>
                                                    <th>FOLIOS</th>
                                                    <th>FECHA DE ENVÍO</th>
                                                    <th>FECHA DE NOTIFICACIÓN</th>
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
            <div class="row">
                <div class="col-12">
                    <div class="card-info">
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Cargue Correspondencia</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <form action="{{route('subirCorrespondencia')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="cargue_corres" class="col-form-label">Seleccione el archivo</label>
                                            <input style="padding: unset; height: auto;" type="file" name="cargue_corres" class="form-control" required>
                                        </div>
                                        <input type="submit" class="btn btn-info" id="cargar_Correspondencia" value="Cargar">
                                    </form>
                                    @if ($errors->has('cargue_corres'))
                                        <span class="text-danger">{{ $errors->first('cargue_corres') }}</span>
                                    @endif
                                </div>

                                <div class="col-6">
                                    <br>
                                    <a class="btn btn-info" style="padding-top: 10px;" href="/Plantilla_Correspondencia/Plantilla_Cargue_Correspondecias.xlsx" download>Descargar Plantilla De Cargue</a>
                                </div>
                                
                            </div>
                            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                                <i class="fas fa-chevron-up"></i>
                            </a> 
                            <br>                                                   
                            <div class="text-center d-none" id="mostrar_barra_cargar_correspondencia">                                
                                <button class="btn btn-info" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Cargando Correspondencias...
                                </button>
                            </div>
                            @if(session('success'))
                                <div class="alert alert-success" id="alerta_cargue_correspondencia">
                                    {{ session('success') }}
                                </div>
                            @endif  
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