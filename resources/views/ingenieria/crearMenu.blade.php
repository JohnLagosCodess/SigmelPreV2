@extends('adminlte::page')
@section('title', 'Creación de Menús')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
 <div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3>Formulario para Creación de Menús</h3>
            </div>
            <form action="{{route('creacionMenu')}}" method="POST">
                @csrf
                <div class="card-body">
                    @if (session()->get('menu_no_creado'))
                        <div class="alert alert-danger mt-2" role="alert">
                            <strong>{{session()->get('menu_no_creado')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('menu_creado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('menu_creado')}}</strong>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="nombre_menu" class="col-sm-2 col-form-label">Nombre Menú</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nombre_menu" id="nombre_menu" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="estado_menu" class="col-sm-2 col-form-label">Seleccione un Estado</label>
                        <div class="col-sm-10">
                            <select id="estado_menu" class="custom-select" name="estado_menu" required>
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tipo_menu" class="col-sm-2 col-form-label">Seleccione el tipo de Menú</label>
                        <div class="col-sm-10">
                            <select id="tipo_menu" class="custom-select" name="tipo_menu" required>
                                <option value="primario" selected>Primario</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="listado_roles_para_menus" class="col-sm-2 col-form-label">Rol para Asociar</label>
                        <div class="col-sm-10">
                            <select id="listado_roles_para_menus" class="listado_roles_para_menus custom-select" name="listado_roles_para_menus" required></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="asignar_funcion" class="col-sm-2 col-form-label">¿Tiene Función Principal?</label>
                        <div class="col-sm-10">
                            <div class="custom-control custom-checkbox mt-3">
                                <input class="custom-control-input custom-control-input-success custom-control-input-outline" type="checkbox" id="si_ver_vistas">
                                <label for="si_ver_vistas" class="custom-control-label">Sí</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" id="contenedor_vistas">
                        <label for="listado_vistas_para_menus" class="col-sm-2 col-form-label">Vista para Asociar</label>
                        <div class="col-sm-10">
                            <select id="listado_vistas_para_menus" class="listado_vistas_para_menus custom-select" name="listado_vistas_para_menus"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nombre_icono" class="col-sm-2 col-form-label">Nombre Icono (Opcional)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nombre_icono" id="nombre_icono">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="observacion_menu" class="col-sm-2 col-form-label">Observación (Opcional)</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="observacion_menu" id="observacion_menu" rows="4"></textarea>
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
    <script src="/js/menus.js"></script>
@stop