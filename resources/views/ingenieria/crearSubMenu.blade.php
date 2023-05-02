@extends('adminlte::page')
@section('title', 'Creación de Sub Menús')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
 <div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3>Formulario para Creación de Sub Menús</h3>
            </div>
            <form action="{{route('creacionSubMenu')}}" method="POST">
                @csrf
                <div class="card-body">
                    @if (session()->get('submenu_no_creado'))
                        <div class="alert alert-danger mt-2" role="alert">
                            <strong>{{session()->get('submenu_no_creado')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('submenu_creado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('submenu_creado')}}</strong>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="listado_padres_menu" class="col-sm-2 col-form-label">Seleccione un Padre para Asociar</label>
                        <div class="col-sm-10">
                            <select id="listado_padres_menu" class="listado_padres_menu custom-select" name="listado_padres_menu" required></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nombre_submenu" class="col-sm-2 col-form-label">Nombre Sub Menú</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nombre_submenu" id="nombre_submenu" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="estado_submenu" class="col-sm-2 col-form-label">Seleccione un Estado</label>
                        <div class="col-sm-10">
                            <select id="estado_submenu" class="custom-select" name="estado_submenu" required>
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tipo_submenu" class="col-sm-2 col-form-label">Seleccione el tipo de Menú</label>
                        <div class="col-sm-10">
                            <select id="tipo_submenu" class="custom-select" name="tipo_submenu" required>
                                <option value="secundario" selected>Secundario</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="listado_vistas_para_submenus" class="col-sm-2 col-form-label">Vista para Asociar</label>
                        <div class="col-sm-10">
                            <select id="listado_vistas_para_submenus" class="listado_vistas_para_submenus custom-select" name="listado_vistas_para_submenus" required></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nombre_icono" class="col-sm-2 col-form-label">Nombre Icono (Opcional)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nombre_icono" id="nombre_icono">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="observacion_submenu" class="col-sm-2 col-form-label">Observación (Opcional)</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="observacion_submenu" id="observacion_submenu" rows="4"></textarea>
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