@extends('adminlte::page')
@section('title', 'Asignación de Rol')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
            <div class="card card-info">
                <div class="card-header">
                    <h3>Formulario para Asignación del Roles a Usuarios</h3>
                </div>
                <form action="{{route('AsignacionRol')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if (session()->get('asignacion_rol_success'))
                            <div class="alert alert-success mt-2" role="alert">
                                <strong>{{session()->get('asignacion_rol_success')}}</strong>
                            </div>
                        @endif
                        @if (session()->get('asignacion_rol_failed'))
                            <div class="alert alert-danger mt-2" role="alert">
                                <strong>{{session()->get('asignacion_rol_failed')}}</strong>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="listado_usuarios" class="col-sm-2 col-form-label">Seleccione un Usuario <span style="color:red;">(*)</span></label>
                            <div class="col-sm-10">
                                <select id="listado_usuarios" class="listado_usuarios custom-select" name="listado_usuarios" required></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="listado_todos_roles" class="col-sm-2 col-form-label">Seleccione un Rol <span style="color:red;">(*)</span></label>
                            <div class="col-sm-10">
                                <select id="listado_todos_roles" class="listado_todos_roles custom-select" name="listado_todos_roles" required></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="estado_rol" class="col-sm-2 col-form-label">Seleccione un Estado <span style="color:red;">(*)</span></label>
                            <div class="col-sm-10">
                                <select id="estado_rol" class="borde_selector_no_select_2 custom-select" name="estado_rol" required>
                                    <option value="" selected>Seleccione</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tipo_rol" class="col-sm-2 col-form-label">Seleccione el Tipo de Rol <span style="color:red;">(*)</span></label>
                            <div class="col-sm-10">
                                <select id="tipo_rol" class=" borde_selector_no_select_2 custom-select" name="tipo_rol" required>
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
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="/js/selector_usuarios.js"></script>
@stop