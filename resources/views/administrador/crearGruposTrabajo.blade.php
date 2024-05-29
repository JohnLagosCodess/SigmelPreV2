@extends('adminlte::page')
@section('title', 'Crear equipos de Trabajo')

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
        <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
        <div class="card card-info">
            <div class="card-header">
                <h3>Formulario para Creación de Equipos de Trabajo</h3>
            </div>
            <form action="{{route('CrearNuevoEquipo')}}" method="POST">
                @csrf
                <div class="card-body">
                    @if (session()->get('equipo_creado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('equipo_creado')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('equipo_no_creado'))
                        <div class="alert alert-danger mt-2" role="alert">
                            <strong>{{session()->get('equipo_no_creado')}}</strong>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label  class="col-form-label">Proceso <span style="color:red;">(*)</span></label>
                                <select class="proceso custom-select" name="proceso" id="proceso" requierd></select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="nombre_equipo_trabajo" class="col-form-label">Equipo de trabajo <span style="color:red;">(*)</span></label>
                                <input type="text" class="form-control" name="nombre_equipo_trabajo" id="nombre_equipo_trabajo" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="listado_lider" class="col-form-label">Lider del equipo de trabajo <span style="color:red;">(*)</span></label>
                                <select id="listado_lider" class="listado_lider custom-select" name="listado_lider" required></select>
                                <strong class="mensaje_no_hay_usuarios text-danger text-sm d-none" role="alert">No hay usuarios relacionados al proceso seleccionado.</strong>
                            </div>
                        </div>
                        {{-- <div class="col-2">
                            <div class="form-group">
                                <label for="listado_acciones" class="col-form-label">Acción <span style="color:red;">(*)</span></label>
                                <select id="listado_acciones" name="listado_acciones" class="listado_acciones custom-select" required></select>
                            </div>
                        </div> --}}
                        <div class="col-3">
                            <div class="form-group">
                                <label for="estado_equipo" class="col-form-label">Status <span style="color:red;">(*)</span></label>
                                <select id="estado_equipo" class="estado_equipo custom-select" name="estado_equipo" required>
                                    <option value="activo" selected>Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="listado_usuarios_equipo" class="col-form-label">Usuarios del equipo de trabajo <span style="color:red;">(*)</span></label>
                                <select multiple="multiple" id="listado_usuarios_equipo" class="listado_usuarios_equipo" name="listado_usuarios_equipo[]" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="fecha_creacion_equipo" class="col-form-label">Fecha de creación <span style="color:red;">(*)</span></label>
                                <input type="date" class="form-control" readonly name="fecha_creacion_equipo" id="fecha_creacion_equipo" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="descripcion_equipo_trabajo" class="col-form-label">Descripción del equipo de trabajo <span style="color:red;">(*)</span></label>
                                <textarea class="form-control" name="descripcion_equipo_trabajo" id="descripcion_equipo_trabajo" rows="4" required></textarea>
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
<script src="/js/equipos_trabajo.js"></script>
<script src="/js/funciones_helpers.js"></script>
@stop