@extends('adminlte::page')
@section('title', 'Crear Grupos de Trabajo')

@section('css')
    <link rel="stylesheet" type="text/css" href="/plugins/duallistbox_4.0.2/src/bootstrap-duallistbox.css">
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
        <div class="card card-primary">
            <div class="card-header">
                <h3>Formulario para Creación de Grupos de Trabajo</h3>
            </div>
            <form action="{{route('CrearNuevoGrupo')}}" method="POST">
                @csrf
                <div class="card-body">
                    @if (session()->get('grupo_creado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('grupo_creado')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('grupo_no_creado'))
                        <div class="alert alert-danger mt-2" role="alert">
                            <strong>{{session()->get('grupo_no_creado')}}</strong>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="nombre_grupo_trabajo" class="col-form-label">Nombre Grupo</label>
                                <input type="text" class="form-control" name="nombre_grupo_trabajo" id="nombre_grupo_trabajo" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="listado_lider" class="col-form-label">Lider Grupo</label>
                                <select id="listado_lider" class="listado_lider custom-select" name="listado_lider" required></select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="estado_grupo" class="col-form-label">Estado</label>
                                <select id="estado_grupo" class="estado_grupo custom-select" name="estado_grupo" required>
                                    <option value="activo" selected>Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="listado_usuarios_grupo" class="col-form-label">Usuarios del Grupo</label>
                                <select multiple="multiple" id="listado_usuarios_grupo" class="listado_usuarios_grupo" name="listado_usuarios_grupo[]"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="observacion_grupo_trabajo" class="col-form-label">Observación (Opcional)</label>
                                <textarea class="form-control" name="observacion_grupo_trabajo" id="observacion_grupo_trabajo" rows="4"></textarea>
                            </div>
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
<script src="/plugins/duallistbox_4.0.2/dist/jquery.bootstrap-duallistbox.js"></script>
<script src="/js/grupos_trabajo.js"></script>
@stop