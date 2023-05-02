@extends('adminlte::page')
@section('title', 'COLOCAR AQUÍ EL TÍTULO')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop
@php
   $url= $_SERVER["REQUEST_URI"];
   $url_actual = str_replace('/Sigmel/usuarios/editarVista/', '' ,$url);
//    echo $url_actual;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <a href="{{route("ConsultarAsignacionVista")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <br><br>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Formulario para Edición de Vista</h3>
                </div>
                <form action="{{route('ActualizacionVista')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <strong class="text-danger">Por serguridad sólo se permite cambiar el nombre del archivo y la observación.</strong>
                        <input type="text" name="id_vista" value="{{$id_vista}}" style="display:none;">
                        <div class="form-group row mt-3">
                            <label for="edicion_nombre_archivo" class="col-sm-2 col-form-label">Nombre Archivo</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="edicion_nombre_archivo" id="edicion_nombre_archivo" value="{{$info_vista[0]->archivo}}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="edicion_observacion_vista" class="col-sm-2 col-form-label">Observación (Opcional)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="edicion_observacion_vista" id="edicion_observacion_vista" rows="4">{{$info_vista[0]->observacion}}</textarea>
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
 {{-- AQUI PARA LLAMAR EL ARCHIVO JS SI ES NECESARIO --}}
@stop
