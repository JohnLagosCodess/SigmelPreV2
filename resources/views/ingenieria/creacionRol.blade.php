@extends('adminlte::page')
@section('title', 'Creación de Rol')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            {{-- <a href="{{route("RolPrincipal")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <a href="{{route('ListadoRoles')}}" class="btn btn-info"><i class="fas fa-list"></i> Consultar Lista de Roles</a>
            <a href="{{route('AsignacionRol')}}" class="btn btn-info"><i class="far fa-address-card"></i> Asignar Roles a Usuarios</a>
            <a href="{{route('ConsultarAsignacionRol')}}" class="btn btn-info"><i class="fas fa-list"></i> Consultar Asignación de Roles a Usuarios</a>
            <br><br> --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Formulario para Creación de Rol</h3>
                </div>
                <form action="{{ route('CreacionRol') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if (session()->get('rol_creado'))
                            <div class="alert alert-success mt-2" role="alert">
                                <strong>{{session()->get('rol_creado')}}</strong>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="nombre_rol" class="col-sm-2 col-form-label">Nombre</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nombre_rol" id="nombre_rol" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="descripcion_rol" class="col-sm-2 col-form-label">Descripción (Opcional)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="descripcion_rol" id="descripcion_rol" rows="4"></textarea>
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