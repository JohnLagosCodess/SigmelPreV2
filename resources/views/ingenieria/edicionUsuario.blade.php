@extends('adminlte::page')
@section('title', 'Edición de Usuario')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3>Edición de Usuario</h3>
        </div>
        <div class="col-sm-6">
            
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <a href="{{route("ListarUsuarios")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <br><br>
            <div class="card card-info">
                <div class="card-header">
                    <h3>Formulario para Editar la Información del Usuario: {{$info_usuario[0]->name}}</h3>
                </div>
                <form action="{{ route('ActualizacionUsuario') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div style="display:none;">
                            <input type="text" name="id_usuario" value="{{$id_usuario}}">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_nombre_usuario" class="col-form-label">Nombre</label>
                                    <input type="text" class="form-control" name="editar_nombre_usuario" id="editar_nombre_usuario" value="{{$info_usuario[0]->name}}" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_correo_usuario" class="col-form-label">Correo</label>
                                    <input type="email" class="form-control" name="editar_correo_usuario" id="editar_correo_usuario" value="{{$info_usuario[0]->email}}" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_correo_contacto_usuario" class="col-form-label">Correo de Contacto</label>
                                    <input type="email" class="form-control" name="editar_correo_contacto_usuario" id="editar_correo_contacto_usuario" value="{{$info_usuario[0]->email_contacto}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_tipo_identificacion_usuario" class="col-form-label">Tipo de Identificación</label>
                                    <select class="editar_tipo_identificacion_usuario custom-select" name="editar_tipo_identificacion_usuario" id="editar_tipo_identificacion_usuario" required>
                                        <option value="{{$info_usuario[0]->tipo_identificacion}}" selected>{{$info_usuario[0]->tipo_identificacion}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_nro_identificacion_usuario" class="col-form-label">N° Identificación</label>
                                    <input type="text" class="form-control" name="editar_nro_identificacion_usuario" id="editar_nro_identificacion_usuario" value="{{$info_usuario[0]->nro_identificacion}}" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_tipo_contrato_usuario" class="col-form-label">Tipo de Contrato</label>
                                    <select class="editar_tipo_contrato_usuario custom-select" name="editar_tipo_contrato_usuario" id="editar_tipo_contrato_usuario" required>
                                        <option value="{{$info_usuario[0]->tipo_contrato}}" selected>{{$info_usuario[0]->tipo_contrato}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_empresa_usuario" class="col-form-label">Empresa</label>
                                    <input type="text" class="form-control" name="editar_empresa_usuario" id="editar_empresa_usuario" value="{{$info_usuario[0]->empresa}}" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_cargo_usuario" class="col-form-label">Cargo</label>
                                    <input type="text" class="form-control" name="editar_cargo_usuario" id="editar_cargo_usuario" value="{{$info_usuario[0]->cargo}}" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="editar_telefono_contacto_usuario" class="col-form-label">Número de Contacto</label>
                                    <input type="number" class="form-control" name="editar_telefono_contacto_usuario" id="editar_telefono_contacto_usuario" value="{{$info_usuario[0]->telefono_contacto}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form group">
                                    <label for="editar_extension_contacto_usuario" class="col-form-label">Número de Extensión</label>
                                    <input type="number" class="form-control" name="editar_extension_contacto_usuario" id="editar_extension_contacto_usuario" value="{{$info_usuario[0]->extension}}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form group">
                                    <label for="editar_password_usuario" class="col-form-label">Contraseña</label>
                                    <input type="password" class="form-control" name="editar_password_usuario" id="editar_password_usuario">
                                    <strong class="text-danger text-sm" role="alert">Por seguridad, el campo: Contraseña no trae la información del usuario.</strong>
                                </div>
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
    <script src="/js/selector_tipo_identificacion_y_contrato.js"></script>
@stop