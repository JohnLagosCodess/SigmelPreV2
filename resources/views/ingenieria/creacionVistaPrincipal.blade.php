@extends('adminlte::page')
@section('title', 'Creación de Vista')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <a href="{{route("RolPrincipal")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <br><br>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Formulario para Creación de Vista Principal</h3>
                </div>
                <form action="{{ route('CreacionVista') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if (session()->get('vista_no_creada'))
                            <div class="alert alert-danger mt-2" role="alert">
                                <strong>{{session()->get('vista_no_creada')}}</strong>
                            </div>
                        @endif
                        @if (session()->get('vista_creada'))
                            <div class="alert alert-success mt-2" role="alert">
                                <strong>{{session()->get('vista_creada')}}</strong>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="nombre_carpeta" class="col-sm-2 col-form-label">Nombre Carpeta</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nombre_carpeta" id="nombre_carpeta" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nombre_carpeta" class="col-sm-2 col-form-label">¿Crear Archivo Principal Dentro de una Sub Carpeta?</label>
                            <div class="col-sm-10">
                                <div class="custom-control custom-checkbox mt-3">
                                    <input class="custom-control-input custom-control-input-success custom-control-input-outline" type="checkbox" id="si_crear_subcarpeta">
                                    <label for="si_crear_subcarpeta" class="custom-control-label">Sí</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="contenedor_subcarpeta">
                            <label for="nombre_subcarpeta" class="col-sm-2 col-form-label">Nombre Sub Carpeta (Opcional)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nombre_subcarpeta" id="nombre_subcarpeta">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nombre_archivo" class="col-sm-2 col-form-label">Nombre Archivo Principal</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nombre_archivo" id="nombre_archivo" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nombre_renderizar" class="col-sm-2 col-form-label">Nombre Vista (Opcional)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nombre_renderizar" id="nombre_renderizar">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="observacion_vista" class="col-sm-2 col-form-label">Observación (Opcional)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="observacion_vista" id="observacion_vista" rows="4"></textarea>
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
    <script src="/js/vistas.js"></script>
@stop