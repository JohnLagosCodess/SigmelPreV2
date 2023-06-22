@extends('adminlte::page')
@section('title', 'Buscar Evento')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
            
        </div>
    </div>

@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div>
                <h4>Convenciones:</h4>
                <p>
                    <!--<i class="far fa-eye text-success"></i> Activar Menú/Sub Menú &nbsp;
                    <i class="far fa-eye-slash text-danger"></i> Inactivar Menú/Sub Menú &nbsp;-->
                   <span style="color:red;">(*)</span> Campo Obligatorio.&nbsp;
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Consultar Evento</h4>
        </div>
        <!-- Busqueda Filtros -->
        <form id="form_consultar_evento" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="nro_identificacion" class="col-form-label">N° de identificación</label>
                                    <input type="number" class="nro_identificacion form-control" name="consultar_nro_identificacion" id="consultar_nro_identificacion">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="id_evento" class="col-form-label">ID evento</label>
                                    <input type="number" class="id_evento form-control" name="consultar_id_evento" id="consultar_id_evento">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <p>&nbsp;</p>
                                    <input type="submit" id="btn_buscar_evento" class="btn btn-info" value="Buscar">
                                </div>
                            </div>
                        </div>
                    </diV>
                    <div class="col-12">
                        <div class="resultado_validacion alert mt-1 d-none" id="llenar_mensaje_validacion" role="alert">
                            <strong ></strong>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Información del afiliado-->
        <div class="row contenedor_info_afiliado d-none">
            <div class="col-12">
                <div class="card-info">
                    <div class="card-header text-center" style="border: 1.5px solid black;">
                        <h5>Información del afiliado</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">Nombre de afiliado:</label>
                            <span id="span_nombre_afiliado"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">N° de identificación:</label>
                            <span id="span_nro_identificacion"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- información del evento-->
        <div class="row contenedor_info_evento d-none">
            <div class="col-12">
                &nbsp; <label for="nro_registros" class="col-form-label">Se encontraron <span id="num_registros"></span> registros</label>
                <div class="card-info">
                    <div class="card-header text-center" style="border: 1.5px solid black;">
                        <h5>Resultados de consulta</h5>
                        <input type="hidden" id="action_afiliado" value="">
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table table-responsive">
                        <table id="Consulta_Eventos" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID evento</th>
                                    <th>Cliente</th>
                                    <th>Empresa</th>
                                    <th>Tipo de evento</th>
                                    <th>Fecha radicación</th>
                                    <th>Fecha registro</th>
                                    <th>Proceso</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Resultado</th>
                                    <th>Fecha de acción</th>
                                    <th>Fecha de dictamen</th>
                                    <th>Fecha de Notificación</th>
                                    <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody id="body_listado_eventos"></tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="grupo_botones" style="float: left;">
                        <input type="submit" id="btn_expor_datos" class="btn btn-info" value="Exportar datos">
                        <input type="submit" id="btn_new_servicio" class="btn btn-info" value="Nuevo servicio">
                        <input type="submit" id="btn_new_consulta" class="btn btn-info" value="Nueva Consulta">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="/js/consultar_eventos.js"></script>
    <script type="text/javascript">
    
    </script>
@stop