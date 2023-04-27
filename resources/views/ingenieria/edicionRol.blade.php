@extends('adminlte::page')
@section('title', 'Edición de Rol')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <a href="{{route("ListadoRoles")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <br><br>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Formulario para Editar la Información del Rol: {{$info_rol[0]->nombre_rol}}</h3>
                </div>
                <form action="{{ route('ActualizacionRol') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div style="display:none;">
                            <input type="text" name="rol_id" value="{{$rol_id}}">
                        </div>
                        <div class="form-group row">
                            <label for="editar_nombre_rol" class="col-sm-2 col-form-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="editar_nombre_rol" id="editar_nombre_rol" value="{{$info_rol[0]->nombre_rol}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editar_descripcion_rol" class="col-sm-2 col-form-label">Descripción (Opcional)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="editar_descripcion_rol" id="editar_descripcion_rol" rows="4">{{$info_rol[0]->descripcion_rol}}</textarea>
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