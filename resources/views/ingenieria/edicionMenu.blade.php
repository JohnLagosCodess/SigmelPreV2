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
            <a href="{{route("listarMenusSubmenus")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <br><br>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Formulario para Editar la Información del Menú: {{$info_menu[0]->nombre}}</h3>
                </div>
                <form action="{{ route('ActualizacionMenu') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div style="display:none;">
                            <input type="text" name="id_menu" value="{{$id_menu}}">
                        </div>
                        <div class="form-group row">
                            <label for="editar_nombre_menu" class="col-sm-2 col-form-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="editar_nombre_menu" id="editar_nombre_menu" value="{{$info_menu[0]->nombre}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="editar_descripcion_menu" class="col-sm-2 col-form-label">Descripción (Opcional)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="editar_descripcion_menu" id="editar_descripcion_menu" rows="4">{{$info_menu[0]->observacion}}</textarea>
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