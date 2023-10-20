@extends('adminlte::page')
@section('title', 'COLOCAR AQUÍ EL TÍTULO')
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
                    <i class="fa fa-sm fa-pen text-primary"></i> Editar Acción &nbsp;
                </p>
            </div>
            <div class="card card-info">
                <div class="card-header">
                    <h3>Listado De Acciones</h3>
                </div>
                <div class="card-body">
                    <label ><span>Acciones: Activas: {{$conteo_activos_inactivos[0]->Activos}}</span> - Inactivas: {{$conteo_activos_inactivos[0]->Inactivos}} </label>
                    <div class="table-responsive">
                        <input type="hidden" id="traer_datos_accion" value="{{route('InformacionAccionEditar')}}">

                        <table id="listado_acciones" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr class="bg-info">
                                    <th>Detalle</th>
                                    <th>N°</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                    <th>Descripción de Acción</th>
                                    <th>Status</th>
                                    <th>Fecha de Creación</th>
                                    <th>Fecha de Actualización</th>
                                    <th>Gestionado por</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($listado_acciones)):?>
                                    @foreach ($listado_acciones as $accion_editar)
                                        <tr>
                                            <td><a href="javascript:void(0);" id="btn_modal_edicion_accion_{{$accion_editar->Id_Accion}}" data-toggle="modal" data-target="#modalEdicionAccion_{{$accion_editar->Id_Accion}}" data-id_editar_accion="{{$accion_editar->Id_Accion}}" data-nombre_editar_accion="{{$accion_editar->Accion}}"><i class="fa fa-pen text-primary"></i></a></td>
                                            <td>{{$accion_editar->Id_Accion}}</td>
                                            <td>{{$accion_editar->Nombre_estado}}</td>
                                            <td>{{$accion_editar->Accion}}</td>
                                            <td>{{$accion_editar->Descripcion_accion}}</td>
                                            <td>{{$accion_editar->Status_accion}}</td>
                                            <td>{{$accion_editar->F_creacion_accion}}</td>
                                            <td>{{$accion_editar->updated_at}}</td>
                                            <td>{{$accion_editar->Nombre_usuario}}</td>
                                        </tr>
                                    @endforeach
                                <?php endif?>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- MODAL EDICION ACCION --}}
            @include('administrador.editarAcciones')
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/acciones.js"></script>
@stop