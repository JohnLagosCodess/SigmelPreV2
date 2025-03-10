@extends('adminlte::page')
@section('title', 'Asignación de Vistas')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
 <div class="row">
    <div class="col-12">
        <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
        <div class="card card-info">
            <div class="card-header">
                <h3>Formulario para Asignación de Vistas a Roles</h3>
            </div>
            <form action="{{route('AsignacionVista')}}" method="POST">
                @csrf
                <div class="card-body">
                    @if (session()->get('asignacion_vista_success'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('asignacion_vista_success')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('asignacion_vista_failed'))
                        <div class="alert alert-danger mt-2" role="alert">
                            <strong>{{session()->get('asignacion_vista_failed')}}</strong>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="listado_roles_para_vistas" class="col-sm-2 col-form-label">Seleccione un Rol <span style="color:red;">(*)</span></label>
                        <div class="col-sm-10">
                            <select id="listado_roles_para_vistas" class="listado_roles_para_vistas custom-select" name="listado_roles_para_vistas" required></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="listado_vistas_asignar" class="col-sm-2 col-form-label">Seleccione una Vista <span style="color:red;">(*)</span></label>
                        <div class="col-sm-10">
                            <select id="listado_vistas_asignar" class="listado_vistas_asignar custom-select" name="listado_vistas_asignar" required></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="estado_vista" class="col-sm-2 col-form-label">Seleccione un Estado <span style="color:red;">(*)</span></label>
                        <div class="col-sm-10">
                            <select id="estado_vista" class="custom-select" name="estado_vista" required>
                                <option value="" selected>Seleccione</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tipo_vista" class="col-sm-2 col-form-label">Seleccione el Tipo de Vista <span style="color:red;">(*)</span></label>
                        <div class="col-sm-10">
                            <select id="tipo_vista" class="custom-select" name="tipo_vista" required>
                                <option value="" selected>Seleccione</option>
                                <option value="principal">Principal</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-outline-success" value="Enviar Información">
                </div>
                </div>
            </form>
        </div>
    </div>
 </div>
@stop

@section('js')
    <script src="/js/vistas.js"></script>
@stop