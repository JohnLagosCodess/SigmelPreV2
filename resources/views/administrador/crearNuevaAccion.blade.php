@extends('adminlte::page')
@section('title', 'Nueva Acción')
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
                    <h3>Formulario para Creación de Acciones</h3>
                </div>
                <form  id="form_nueva_accion" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="alert mt-2 d-none" id="resultado_insercion_nueva_accion" role="alert"></div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label  class="col-form-label">Estado <span style="color:red;">(*)</span></label>
                                    <select class="custom-select estado" name="estado" id="estado" required></select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label  class="col-form-label">Acción <span style="color:red;">(*)</span></label>
                                    <input type="text" class="form-control" name="accion" id="accion" required>
                                    {{-- <textarea class="form-control" name="accion" id="accion" rows="2" required></textarea> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label  class="col-form-label">Descripción de Acción <span style="color:red;">(*)</span></label>
                                    <textarea class="form-control" name="descrip_accion" id="descrip_accion" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="">
                                    <label class="col-form-label">Status <span style="color: red;">(*)</span></label>
                                    <select class="custom-select status" name="status" id="status" required>
                                        <option></option>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="">
                                    <label class="col-form-label">Fecha de Creación <span style="color: red;">(*)</span></label>
                                    <input type="date" class="form-control" name="fecha_creacion" id="fecha_creacion" value="<?php echo date('Y-m-d'); ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-3">
                            <input type="submit" class="btn btn-outline-success" id="btn_nueva_accion" value="Crear acción">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/acciones.js"></script>
    <script src="/js/funciones_helpers.js"></script>
@stop