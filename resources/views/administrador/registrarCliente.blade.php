@extends('adminlte::page')
@section('title', 'Registro Cliente')
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
                @if (isset($info_registro_cliente[0]->id) && $info_registro_cliente[0]->id <> '')
                    <h3>
                        HV Cliente: {{$info_registro_cliente[0]->nombre_cliente}} 
                        ( Fecha creación: {{$info_registro_cliente[0]->created_at}}
                        - Fecha actualización: {{$info_registro_cliente[0]->updated_at}}
                        )
                    </h3>
                @else
                <h3>Formulario Registro Único de Cliente</h3>
                @endif
            </div>
                 @if (isset($info_registro_cliente[0]->id) && $info_registro_cliente[0]->id <> '')
                    @php $ruta = route('ActualizarCliente'); @endphp
                @else
                    @php $ruta = route('CrearCliente'); @endphp
                @endif
            <form action="@php echo $ruta; @endphp" method="POST">
                @csrf
                <div class="card-body">
                    @if (session()->get('cliente_creado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('cliente_creado')}}</strong>
                        </div>
                    @endif
                    <div class="d-none">
                        @php
                            if (isset($info_registro_cliente[0]->id) && $info_registro_cliente[0]->id <> '') {
                                $id_cliente = $info_registro_cliente[0]->id;
                            }else{$id_cliente = '';}
                        @endphp
                        <input type="text" name="id_cliente" value ="@php echo $id_cliente; @endphp">
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                @php
                                    if (isset($info_registro_cliente[0]->nombre_cliente) && $info_registro_cliente[0]->nombre_cliente <> '') {
                                        $nombre_cliente = $info_registro_cliente[0]->nombre_cliente;
                                    }else{$nombre_cliente = '';}
                                @endphp
                                <label for="nombre_cliente" class="col-form-label">Nombre Cliente</label>
                                <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" value="@php echo $nombre_cliente; @endphp" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                @php
                                    if (isset($info_registro_cliente[0]->nit) && $info_registro_cliente[0]->nit <> '') {
                                        $nit_cliente = $info_registro_cliente[0]->nit;
                                    }else{$nit_cliente = '';}
                                @endphp
                                <label for="nit_cliente" class="col-form-label">NIT</label>
                                <input type="text" class="form-control" name="nit_cliente" id="nit_cliente" value="@php echo $nit_cliente; @endphp" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                @php
                                    if (isset($info_registro_cliente[0]->razon_social) && $info_registro_cliente[0]->razon_social <> '') {
                                        $razon_social_cliente = $info_registro_cliente[0]->razon_social;
                                    }else{$razon_social_cliente = '';}
                                @endphp
                                <label for="razon_social_cliente" class="col-form-label">Razón Social</label>
                                <input type="text" class="form-control" name="razon_social_cliente" id="razon_social_cliente" value="@php echo $razon_social_cliente; @endphp" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                @php
                                    if (isset($info_registro_cliente[0]->representante_legal) && $info_registro_cliente[0]->representante_legal <> '') {
                                        $representante_legal_cliente = $info_registro_cliente[0]->representante_legal;
                                    }else{$representante_legal_cliente = '';}
                                @endphp
                                <label for="representante_legal_cliente" class="col-form-label">Representante Legal</label>
                                <input type="text" class="form-control" name="representante_legal_cliente" id="representante_legal_cliente" value="@php echo $representante_legal_cliente; @endphp" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                @php
                                    if (isset($info_registro_cliente[0]->telefono_contacto) && $info_registro_cliente[0]->telefono_contacto <> '') {
                                        $telefono_contacto_cliente = $info_registro_cliente[0]->telefono_contacto;
                                    }else{$telefono_contacto_cliente = '';}
                                @endphp
                                <label for="telefono_contacto_cliente" class="col-form-label">Teléfono De Contacto</label>
                                <input type="number" class="form-control" name="telefono_contacto_cliente" id="telefono_contacto_cliente" value="@php echo $telefono_contacto_cliente; @endphp" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                @php
                                    if (isset($info_registro_cliente[0]->correo_contacto) && $info_registro_cliente[0]->correo_contacto <> '') {
                                        $correo_contacto_cliente = $info_registro_cliente[0]->correo_contacto;
                                    }else{$correo_contacto_cliente = '';}
                                @endphp
                                <label for="correo_contacto_cliente" class="col-form-label">Correo de Contacto</label>
                                <input type="email" class="form-control" name="correo_contacto_cliente" id="correo_contacto_cliente" value="@php echo $correo_contacto_cliente; @endphp" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                @php
                                    if (isset($info_registro_cliente[0]->estado) && $info_registro_cliente[0]->estado <> '') {
                                        $estado_cliente = $info_registro_cliente[0]->estado;
                                    }else{$estado_cliente = '';}
                                @endphp
                                <label for="estado_cliente" class="col-form-label">Estado</label>
                                <select class="custom-select" name="estado_cliente" id="estado_cliente" required>
                                    @if ($estado_cliente === '')
                                        <option value="activo" selected>Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    @else
                                        @if ($estado_cliente === 'activo')
                                            <option value="activo" selected>Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                        @else
                                            <option value="activo">Activo</option>
                                            <option value="inactivo" selected>Inactivo</option>
                                        @endif
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                @php
                                    if (isset($info_registro_cliente[0]->observacion) && $info_registro_cliente[0]->observacion <> '') {
                                        $observacion_cliente = $info_registro_cliente[0]->observacion;
                                    }else{$observacion_cliente = '';}
                                @endphp
                                <label for="observacion_cliente" class="col-form-label">Observación (Opcional)</label>
                                <textarea class="form-control" name="observacion_cliente" id="observacion_cliente" rows="4">@php echo $observacion_cliente; @endphp</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @if (isset($info_registro_cliente[0]->id) && $info_registro_cliente[0]->id <> '')
                        <input type="submit" class="btn btn-outline-success" value="Actualizar Información">
                    @else
                        <input type="submit" class="btn btn-outline-success" value="Enviar Información">
                    @endif
                </div>
            </form>
        </div>
    </div>
 </div>
@stop

@section('js')
 {{-- AQUI PARA LLAMAR EL ARCHIVO JS SI ES NECESARIO --}}
@stop