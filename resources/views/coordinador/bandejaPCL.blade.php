@extends('adminlte::page')
@section('title', 'Bandeja PCL')
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
                <span style="color:red;">(*)</span> Campo Obligatorio&nbsp;
                <!--<i class="fa fa-puzzle-piece text-info"></i> Agregar Nuevo Servicio&nbsp;-->
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Bandeja PCL</h4>
        </div>
        <!-- Busqueda Filtros bandeja PCL -->
        <form id="form_filtro_bandejaPcl" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="f_desde" class="col-form-label">Fecha Desde</label>
                                    <input type="date" class="f_desde form-control" name="consultar_f_desde" id="consultar_f_desde">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="f_hasta" class="col-form-label">Hasta</label>
                                    <input type="date" class="f_hasta form-control" name="consultar_f_hasta" id="consultar_f_hasta">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="c_dias" class="col-form-label">Días mayor o igual a</label>
                                    <input type="number" class="c_dias form-control" name="consultar_g_dias" id="consultar_g_dias">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <p>&nbsp;</p>
                                    <input type="submit" id="btn_filtro_bandeja" class="btn btn-info" value="Filtrar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="resultado_validacion alert mt-1 d-none" id="llenar_mensaje_validacion" role="alert">
                            <strong ></strong>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Bandeja PCL-->
        <div class="row contenedor_casos_pcl">
            <div class="col-12">
                &nbsp; <label for="nro_registros" class="col-form-label">Se encontraron <span id="num_registros"></span> registros</label>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table id="Consulta_Eventos" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="bg-info">
                                    <th>Detalle</th>
                                    <th>Cliente</th>
                                    <th>Nombre de afiliado</th>
                                    <th>N° identificación</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                    <th>Profesional actual</th>
                                    <th>Tipo de evento</th>
                                    <th>ID evento</th>
                                    <th>Fecha de evento</th>
                                    <th>Fecha de radicación</th>
                                    <th>Tiempo de gestión</th>
                                    <th>Dias transcurridos desde el evento</th>
                                    <th>Empresa actual</th>
                                    <th>Proceso</th>
                                    <th>Proceso que envia</th>
                                    <th>Fecha asignación al proceso</th>
                                    <th>Asignado por</th>
                                    <th>Fecha alerta</th>
                                    <th>Fecha solicitud documentos</th>
                                    <th>Fecha recepción documentos</th>
                                    <th>Fecha de asignación para calificación</th>
                                    <th>Fecha devolución comité</th>
                                    <th>Fecha acción</th>
                                </tr>
                            </thead>
                            <tbody id="body_listado_casos_pcl"></tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="grupo_botones" style="float: left;">
                        <input type="submit" id="btn_new_consulta" class="btn btn-info" value="Guardar">
                        <input type="button" id="btn_expor_datos" class="btn btn-info" value="Exportar datos"> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop