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
            <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
            <div class="card card-info">
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
                        @if (session()->get('rol_no_creado'))
                            <div class="alert alert-danger mt-2" role="alert">
                                <strong>{{session()->get('rol_no_creado')}}</strong>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="nombre_rol" class="col-sm-2 col-form-label">Nombre del rol <span style="color:red;">(*)</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nombre_rol" id="nombre_rol" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="descripcion_rol" class="col-sm-2 col-form-label">Descripción del rol <span style="color:red;">(*)</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="descripcion_rol" id="descripcion_rol" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Fecha de creación</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>">
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
    <script src="/js/funciones_helpers.js"></script>
@stop