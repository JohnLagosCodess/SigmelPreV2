@extends('adminlte::page')
@section('title', 'Listado de Usuarios')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">

        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div>
                <h4>Convenciones:</h4>
                <p>
                    <i class="fa fa-sm fa-pen text-primary"></i> Editar &nbsp;
                </p>
            </div>
            <div class="card card-info">
                <div class="card-header">
                    <h3>Listado de Usuarios</h3>
                </div>
                <div class="card-body">
                    @if (session()->get('actualizado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('actualizado')}}</strong>
                        </div>
                    @endif
                    <label ><span>Usuarios: Activos: {{$conteo_activos_inactivos[0]->Activos}}</span> - Inactivos: {{$conteo_activos_inactivos[0]->Inactivos}} </label>
                    <div class="table-responsive">
                        <input type="hidden" id="ruta_ed_evento" value="{{route('EditarUsuario')}}">
                        <input type="hidden" id="ruta_guardar_ed_evento" value="{{route('ActualizacionUsuario')}}">
                        <table id="listado_usuarios" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="bg-info">
                                    <th>Acciones</th>
                                    <th>N°</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Tipo de Colaborador</th>
                                    <th>Status del Usuario</th>
                                    <th>Roles</th>
                                    <th>Procesos</th>
                                    <th>E-mail</th>
                                    <th>Número de Contacto</th>
                                    <th>Fecha y Hora de Creación</th>
                                    <th>Fecha y Hora de Actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $iterar = 0;?>
                                @foreach ($listado_usuarios as $usuario)
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);" class="editar_usuario" id="btn_modal_edicion_usuario_{{$usuario->id}}" data-toggle="modal" data-target="#modalEdicionUsuario_{{$usuario->id}}" data-id_editar_usuario="{{$usuario->id}}" data-nombre_editar_usuario="{{$usuario->name}}"><i class="fa fa-pen text-primary"></i></a>
                                        </td>
                                        <td><?php echo $iterar = $iterar + 1; ?></td>
                                        <td>{{$usuario->name}}</td>
                                        <td>{{$usuario->tipo_colaborador}}</td>
                                        <td>{{$usuario->estado}}</td>
                                        <td>{{$usuario->roles_usuario}}</td>
                                        <td>{{$usuario->procesos_usuario}}</td>
                                        <td>{{$usuario->email_contacto}}</td>
                                        <td>{{$usuario->telefono_contacto}}</td>
                                        <td>{{$usuario->created_at}}</td>
                                        <td>{{$usuario->updated_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- MODAL EDICION USUARIO --}}
            <x-adminlte-modal class="habilitar_modal_edicion_usuario" id="" theme="info" icon="fa fa-pen" size='xl' scrollable="yes" disable-animations>
                <div class="row">
                    <div class="col-12">
                        <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
                        <form class="actualizar_usuario" method="POST">
                            @csrf
                            <div class="row">
                                <input type="hidden" id="captura_id_usuario" name="captura_id_usuario">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Nombre <span style="color:red;">(*)</span></label>
                                        <input type="text" class="form-control" name="editar_nombre_usuario" id="editar_nombre_usuario" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Tipo de Identificación <span style="color:red;">(*)</span></label>
                                        <select class="editar_tipo_identificacion_usuario custom-select" name="editar_tipo_identificacion_usuario" id="editar_tipo_identificacion_usuario" style="width:100%;" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">N° Identificación <span style="color:red;">(*)</span></label>
                                        <input type="number" class="form-control" name="editar_nro_identificacion_usuario" id="editar_nro_identificacion_usuario" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">E-mail <span style="color:red;">(*)</span></label>
                                        <input type="email" class="form-control" name="editar_correo_contacto_usuario" id="editar_correo_contacto_usuario" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Tipo de colaborador <span style="color:red;">(*)</span></label>
                                        <select class="editar_tipo_colaborador custom-select" name="editar_tipo_colaborador" id="editar_tipo_colaborador" style="width: 100%;" required></select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Empresa <span style="color:red;">(*)</span></label>
                                        <input type="text" class="form-control" name="editar_empresa_usuario" id="editar_empresa_usuario" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Cargo <span style="color:red;">(*)</span></label>
                                        <input type="text" class="form-control" name="editar_cargo_usuario" id="editar_cargo_usuario" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Número de Contacto <span style="color:red;">(*)</span></label>
                                        <input type="text" class="form-control" name="editar_telefono_contacto_usuario" id="editar_telefono_contacto_usuario" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Correo por Usuario <span style="color:red;">(*)</span></label>
                                        <input type="email" class="form-control" name="editar_correo_usuario" id="editar_correo_usuario" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form group">
                                        <label class="col-form-label">Contraseña</label>
                                        <input type="text" class="form-control" name="editar_password_usuario" id="editar_password_usuario">
                                        <strong class="text-danger text-sm" role="alert">Por seguridad, el campo: Contraseña no trae la información del usuario.</strong>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label  class="col-form-label">¿A qué procesos pertenece? <span style="color:red;">(*)</span></label>
                                        <input type="hidden" id="string_id_procesos">
                                        <select class="editar_listado_procesos_crear_usuario custom-select" name="editar_listado_procesos_crear_usuario[]" id="editar_listado_procesos_crear_usuario" style="width:100%;" required></select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Status del usuario <span style="color:red;">(*)</span></label>
                                        <select class="editar_status_crear_usuario custom-select" name="editar_status_crear_usuario" id="editar_status_crear_usuario" style="width:100%;" required></select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div id="mostrar_mensaje_actualizacion" class="alert mt-2 mr-auto d-none" role="alert"></div>
                            <button type="submit" class="btn btn-info mr-auto">Guardar Información</button>
                            <button type="button" id="btn_actualizar_consulta" class="btn btn-info mr-auto d-none">Actualizar</button>
                            <button type="button" class="btn btn-danger" style="float:right;" data-dismiss="modal">Cerrar</button>
                            <x-slot name="footerSlot">
                            </x-slot>
                        </form>
                    </div>
                </div>
            </x-adminlte-modal>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/selector_tipo_identificacion_y_contrato.js"></script>
    <script src="/js/funciones_helpers.js"></script>
@stop