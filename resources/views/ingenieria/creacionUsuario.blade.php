@extends('adminlte::page')
@section('title', 'Creación Usuario')
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
                    <h3>Formulario para Creación de Usuarios</h3>
                </div>
                <form action="{{ route('CreacionUsuario') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if(session()->get('email'))
                            <div class="alert alert-danger mt-2" role="alert">
                                <strong>{{session()->get('email')}}</strong>
                            </div>
                        @endif
                        @if (session()->get('creado'))
                            <div class="alert alert-success mt-2" role="alert">
                                <strong>{{session()->get('creado')}}</strong>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="nombre_usuario" class="col-form-label">Nombre <span style="color:red;">(*)</span></label>
                                    <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="tipo_identificacion_usuario" class="col-form-label">Tipo de Identificación <span style="color:red;">(*)</span></label>
                                    <select class="tipo_identificacion_usuario custom-select" name="tipo_identificacion_usuario" id="tipo_identificacion_usuario" required></select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="nro_identificacion_usuario" class="col-form-label">N° Identificación <span style="color:red;">(*)</span></label>
                                    <input type="number" class="form-control" name="nro_identificacion_usuario" id="nro_identificacion_usuario" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="correo_contacto_usuario" class="col-form-label">E-mail <span style="color:red;">(*)</span></label>
                                    <input type="email" class="form-control" name="correo_contacto_usuario" id="correo_contacto_usuario" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="tipo_colaborador" class="col-form-label">Tipo de colaborador <span style="color:red;">(*)</span></label>
                                    <select class="tipo_colaborador custom-select" name="tipo_colaborador" id="tipo_colaborador" required></select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="empresa_usuario" class="col-form-label">Empresa <span style="color:red;">(*)</span></label>
                                    <input type="text" class="form-control" name="empresa_usuario" id="empresa_usuario" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="cargo_usuario" class="col-form-label">Cargo <span style="color:red;">(*)</span></label>
                                    <input type="text" class="form-control" name="cargo_usuario" id="cargo_usuario" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="telefono_contacto_usuario" class="col-form-label">Número de Contacto <span style="color:red;">(*)</span></label>
                                    <input type="text" class="form-control" name="telefono_contacto_usuario" id="telefono_contacto_usuario" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="correo_usuario" class="col-form-label">Correo por Usuario <span style="color:red;">(*)</span></label>
                                    <input type="email" class="form-control" name="correo_usuario" id="correo_usuario" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form group">
                                    <label for="password_usuario" class="col-form-label">Contraseña <span style="color:red;">(*)</span></label>
                                    <input type="text" class="form-control" name="password_usuario" id="password_usuario" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="listado_procesos_crear_usuario" class="col-form-label">¿A qué procesos pertenece?</label>
                                    <select class="listado_procesos_crear_usuario custom-select" name="listado_procesos_crear_usuario[]" id="listado_procesos_crear_usuario"></select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="status_crear_usuario" class="col-form-label">Status del usuario <span style="color:red;">(*)</span></label>
                                    <select class="status_crear_usuario custom-select" name="status_crear_usuario" id="status_crear_usuario" required></select>
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
    <script src="/js/selector_tipo_identificacion_y_contrato.js"></script>
    <script src="/js/funciones_helpers.js"></script>
@stop