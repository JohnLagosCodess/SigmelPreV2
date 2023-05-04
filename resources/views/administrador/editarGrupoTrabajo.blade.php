@extends('adminlte::page')
@section('title', 'Editar Grupo de Trabajo')

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
        <a href="{{route("listarGruposTrabajo")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <br><br>
        <div class="card card-primary">
            <div class="card-header">
                <h3>Formulario para editar Grupo de Trabajo: {{$info_grupo_trabajo[0]->nombre}}</h3>
            </div>
            <form action="{{route('GuardarEdicionGrupo')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="editar_nombre_grupo_trabajo" class="col-form-label">Nombre Grupo</label>
                                <input type="text" class="form-control" name="editar_nombre_grupo_trabajo" id="editar_nombre_grupo_trabajo" value="{{$info_grupo_trabajo[0]->nombre}}" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="editar_listado_lider" class="col-form-label">Lider Grupo</label>
                                <select id="editar_listado_lider" class="editar_listado_lider custom-select" name="editar_listado_lider" required>
                                    <option value="{{$info_selector_lider[0]->id}}" selected>{{$info_selector_lider[0]->name}} ({{$info_selector_lider[0]->email}})</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="editar_estado_grupo" class="col-form-label">Estado</label>
                                <select id="editar_estado_grupo" class="editar_estado_grupo custom-select" name="editar_estado_grupo" required>
                                    @if ($info_grupo_trabajo[0]->estado == 'activo')
                                        <option value="activo" selected>Activo</option> 
                                        <option value="inactivo">Inactivo</option>
                                        @elseif ($info_grupo_trabajo[0]->estado == 'inactivo')
                                        <option value="activo">Activo</option> 
                                        <option value="inactivo" selected>Inactivo</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="hidden" id="id_grupo" name="id_grupo_trabajo" value="{{$info_grupo_trabajo[0]->id}}">
                                <label for="editar_listado_usuarios_grupo" class="col-form-label">Usuarios del Grupo</label>
                                <select multiple="multiple" id="editar_listado_usuarios_grupo" class="editar_listado_usuarios_grupo" name="editar_listado_usuarios_grupo[]"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="editar_observacion_grupo_trabajo" class="col-form-label">Observación (Opcional)</label>
                                <textarea class="form-control" name="editar_observacion_grupo_trabajo" id="editar_observacion_grupo_trabajo" rows="4">{{$info_grupo_trabajo[0]->observacion}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-outline-success" value="Actualizar Información">
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