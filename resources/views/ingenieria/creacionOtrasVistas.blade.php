@extends('adminlte::page')
@section('title', 'Creación de Otras Vistas')
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
        <a href="{{route('NuevaVista')}}" class="btn btn-info"><i class="fas fa-plus"></i> Crear Vista Principal</a>
        <br><br> --}}
        <div class="card card-primary">
            <div class="card-header">
                <h3>Formulario para Creación de Otras Vistas</h3>
            </div>
            <form action="{{ route('CreacionOtraVista') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if (session()->get('otra_vista_no_creada'))
                        <div class="alert alert-danger mt-2" role="alert">
                            <strong>{{session()->get('otra_vista_no_creada')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('otra_vista_creada'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('otra_vista_creada')}}</strong>
                        </div>
                    @endif
                
                    <div class="form-group row">
                        <label for="selector_nombre_carpeta" class="col-sm-3 col-form-label">Carpeta</label>
                        <div class="col-sm-9">
                            <select class="selector_nombre_carpeta custom-select" name="selector_nombre_carpeta" id="selector_nombre_carpeta" required></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="selector_nombre_subcarpeta" class="col-sm-3 col-form-label">Sub Carpeta (Opcional)</label>
                        <div class="col-sm-9" id="padre">
                            <select class="selector_nombre_subcarpeta custom-select" name="selector_nombre_subcarpeta" id="selector_nombre_subcarpeta" disabled></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nombre_archivo" class="col-sm-3 col-form-label">Nombre Archivo</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nombre_archivo" id="nombre_archivo" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nombre_renderizar" class="col-sm-3 col-form-label">Nombre Vista (Opcional)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nombre_renderizar" id="nombre_renderizar">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="observacion_vista" class="col-sm-3 col-form-label">Observación (Opcional)</label>
                        <div class="col-sm-9">
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