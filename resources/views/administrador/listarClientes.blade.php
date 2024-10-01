@extends('adminlte::page')
@section('title', 'Listado de Clientes')

@section('css')
    <link rel="stylesheet" type="text/css" href="/plugins/summernote/summernote.min.css">
@stop
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
                <p><i class="fa fa-sm fa-pen text-primary"></i> Editar Cliente &nbsp; <i class="fas fa-draw-polygon text-primary"></i> Configurar Parametrización &nbsp;</p>
            </div>
            <div class="card-info">
                <div class="card-header">
                    <h3>Listado De Clientes</h3>
                </div>
                <div class="card-body">
                    <label ><span>Clientes: Activos: {{$conteo_activos_inactivos[0]->Activos}}</span> - Inactivos: {{$conteo_activos_inactivos[0]->Inactivos}} </label>
                    <div class="table-responsive">
                        <input type="hidden" id="traer_datos_cliente" value="{{route('InformacionClienteEditar')}}">
                        <table id="listado_clientes" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr class="bg-info">
                                    <th>Detalle</th>
                                    <th>N°</th>
                                    <th>Tipo de cliente</th>
                                    <th>Nombre del cliente</th>
                                    <th>NIT</th>
                                    <th>Teléfono(s)</th>
                                    <th>E-mail(s)</th>
                                    <th>Línea(s) de Atención Principal(es)</th>
                                    <th>Dirección</th>
                                    <th>Departamento</th>
                                    <th>Ciudad</th>
                                    <th>Sucursal(es)</th>
                                    <th>Status</th>
                                    <th>Código cliente</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($array_datos_clientes)):?>
                                    @foreach ($array_datos_clientes as $cliente_editar)
                                        <tr>
                                            <td>
                                                <form action="{{route('mostrarVistaParametrizacion')}}" id="FormVistaParametrizacion_{{$cliente_editar->Id_cliente}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="Id_cliente" id="Id_cliente" value="<?php echo $cliente_editar->Id_cliente; ?>">
                                                    <input type="hidden" name="Nombre_tipo_cliente" value="<?php echo $cliente_editar->Nombre_tipo_cliente; ?>">
                                                    <input type="hidden" name="Nombre_cliente" value="<?php echo $cliente_editar->Nombre_cliente; ?>">
                                                    <input type="submit" id="enviar_form_parametrizacion_{{$cliente_editar->Id_cliente}}" class="d-none">
                                                    <a href="javascript:void(0);" type="submit" id="btn_parametrizacion_{{$cliente_editar->Id_cliente}}" data-id_cliente_parametrizar="{{$cliente_editar->Id_cliente}}"><i class="fas fa-draw-polygon text-primary"></i></a>
                                                </form>
                                                <a href="javascript:void(0);" id="btn_modal_edicion_cliente_{{$cliente_editar->Id_cliente}}" data-toggle="modal" data-target="#modalEdicionCliente_{{$cliente_editar->Id_cliente}}" data-id_editar_cliente="{{$cliente_editar->Id_cliente}}" data-nombre_editar_cliente="{{$cliente_editar->Nombre_cliente}}"><i class="fa fa-pen text-primary"></i></a>
                                            </td>
                                            <td>{{$cliente_editar->Id_cliente}}</td>
                                            <td>{{$cliente_editar->Nombre_tipo_cliente}}</td>
                                            <td>{{$cliente_editar->Nombre_cliente}}</td>
                                            <td>{{$cliente_editar->Nit}}</td>
                                            <td>{{$cliente_editar->Telefonos}}</td>
                                            <td>{{$cliente_editar->Emails}}</td>
                                            <td>{{$cliente_editar->Lineas_atencion}}</td>
                                            <td>{{$cliente_editar->Direccion}}</td>
                                            <td>{{$cliente_editar->Nombre_departamento}}</td>
                                            <td>{{$cliente_editar->Nombre_municipio}}</td>
                                            <td>{{$cliente_editar->Sucursales}}</td>
                                            <td>{{$cliente_editar->Estado}}</td>
                                            <td>{{$cliente_editar->Codigo_cliente}}</td>
                                            <td>{{$cliente_editar->F_registro}}</td>
                                            <td>{{$cliente_editar->updated_at}}</td>
                                        </tr>
                                    @endforeach
                                <?php endif?>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- MODAL EDICION CLIENTE --}}
                @include('administrador.editarCliente')
            </div> 
        </div>
        <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
@stop

@section('js')
    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
    <script src="/plugins/summernote/summernote.min.js"></script>
    <script src="/js/editar_clientes.js"></script>
    <script src="/js/parametrizacion.js"></script>
@stop