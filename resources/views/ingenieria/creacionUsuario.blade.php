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
            <a href="{{route("RolPrincipal")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <a href="{{route('ListarUsuarios')}}" class="btn btn-info"><i class="fas fa-edit"></i> Consultar Lista de Usuarios</a>
            <br><br>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Formulario para Creación de Usuario</h3>
                </div>
                <form action="{{ route('CreacionUsuario') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if(session()->get('email'))
                            <div class="alert alert-warning mt-2" role="alert">
                                <strong>{{session()->get('email')}}</strong>
                            </div>
                        @endif
                        @if (session()->get('creado'))
                            <div class="alert alert-success mt-2" role="alert">
                                <strong>{{session()->get('creado')}}</strong>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nombre_usuario" class="col-form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="correo_usuario" class="col-form-label">Correo</label>
                                    <input type="email" class="form-control" name="correo_usuario" id="correo_usuario" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="correo_contacto_usuario" class="col-form-label">Correo de Contacto</label>
                                    <input type="email" class="form-control" name="correo_contacto_usuario" id="correo_contacto_usuario" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="tipo_identificacion_usuario" class="col-form-label">Tipo de Identificación</label>
                                    <select class="tipo_identificacion_usuario custom-select" name="tipo_identificacion_usuario" id="tipo_identificacion_usuario" required></select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nro_identificacion_usuario" class="col-form-label">N° Identificación</label>
                                    <input type="text" class="form-control" name="nro_identificacion_usuario" id="nro_identificacion_usuario" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="tipo_contrato_usuario" class="col-form-label">Tipo de Contrato</label>
                                    <select class="tipo_contrato_usuario custom-select" name="tipo_contrato_usuario" id="tipo_contrato_usuario" required></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="empresa_usuario" class="col-form-label">Empresa</label>
                                    <input type="text" class="form-control" name="empresa_usuario" id="empresa_usuario" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="cargo_usuario" class="col-form-label">Cargo</label>
                                    <input type="text" class="form-control" name="cargo_usuario" id="cargo_usuario" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="telefono_contacto_usuario" class="col-form-label">Número de Contacto</label>
                                    <input type="number" class="form-control" name="telefono_contacto_usuario" id="telefono_contacto_usuario" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form group">
                                    <label for="extension_contacto_usuario" class="col-form-label">Número de Extensión</label>
                                    <input type="number" class="form-control" name="extension_contacto_usuario" id="extension_contacto_usuario" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form group">
                                    <label for="password_usuario" class="col-form-label">Contraseña</label>
                                    <input type="text" class="form-control" name="password_usuario" id="password_usuario" required>
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
@stop