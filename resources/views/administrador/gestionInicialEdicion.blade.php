@extends('adminlte::page')
@section('title', 'Edición Evento')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
 {{-- AQUI DEBE COLOCAR EL CONTENIDO DE LA VISTA --}} 
    <a href="{{route("gestionInicialNuevo")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a><br>
    <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Edición de Evento: {{$array_datos_info_evento[0]->ID_evento}}</h4>
        </div>
        <form action="{{route('actualizarEvento')}}" method="POST">
            @csrf
            <div class="card-body">
                @if (session()->get('evento_actualizado'))
                    <div class="alert alert-success mt-2" role="alert">
                        <strong>{{session()->get('evento_actualizado')}}</strong>
                    </div>
                @endif
                @if (session()->get('confirmacion_evento_no_creado'))
                    <div class="alert alert-danger mt-2" role="alert">
                        <strong>{{session()->get('confirmacion_evento_no_creado')}}</strong>
                    </div>
                @endif
                <div class="row">
                    {{-- AQUI VA EL FORMULARIO COMPLETO --}}
                    <div class="col-12">
                        {{-- CLIENTE Y TIPO DE CLIENTE --}}
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="cliente" class="col-form-label">Cliente <span style="color:red;">(*)</span></label>
                                    <select class="cliente custom-select" name="cliente" id="cliente" required="true">
                                        <option value="{{$array_datos_info_evento[0]->Cliente}}" selected>{{$array_datos_info_evento[0]->Nombre_cliente}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="tipo_cliente" class="col-form-label">Tipo de Cliente <span style="color:red;">(*)</span></label>
                                    <select class="tipo_cliente custom-select" name="tipo_cliente" id="tipo_cliente" required>
                                        <option value="{{$array_datos_info_evento[0]->Tipo_cliente}}" selected>{{$array_datos_info_evento[0]->Nombre_tipo_cliente}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm columna_otro_tipo_cliente">
                                <div class="form-group">
                                    <label for="otro_tipo_cliente" class="col-form-label">Otro Tipo Cliente <span style="color:red;">(*)</span></label>
                                    <input class="otro_tipo_cliente form-control" name="otro_tipo_cliente" id="otro_tipo_cliente">
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN DEL EVENTO --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información del evento</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="tipo_evento" class="col-form-label">Tipo de evento <span style="color:red;">(*)</span></label>
                                                    <select class="tipo_evento custom-select" name="tipo_evento" id="tipo_evento" required>
                                                        <option value="{{$array_datos_info_evento[0]->Tipo_evento}}" selected>{{$array_datos_info_evento[0]->Nombre_evento}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="id_evento" class="col-form-label">ID evento <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="id_evento form-control" name="id_evento" id="id_evento" value="{{$array_datos_info_evento[0]->ID_evento}}" disabled>
                                                    <input type="hidden" name="id_evento_enviar" value="{{$array_datos_info_evento[0]->ID_evento}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="fecha_evento" class="col-form-label">Fecha de evento <span style="color:red;">(*)</span></label>
                                                    <input type="date" class="fecha_evento form-control" name="fecha_evento" id="fecha_evento" value="{{$array_datos_info_evento[0]->F_evento}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="fecha_radicacion" class="col-form-label">Fecha de radicación <span style="color:red;">(*)</span></label>
                                                    <input type="date" class="fecha_radicacion form-control" name="fecha_radicacion" id="fecha_radicacion" value="{{$array_datos_info_evento[0]->F_radicacion}}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN DEL AFILIADO --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información del afiliado</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nombre_afiliado" class="col-form-label">Nombre de afiliado <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="nombre_afiliado form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_info_afiliados[0]->Nombre_afiliado}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="direccion_info_afiliado" class="col-form-label">Dirección <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="direccion_info_afiliado form-control" name="direccion_info_afiliado" id="direccion_info_afiliado" value="{{$array_datos_info_afiliados[0]->Direccion}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="tipo_documento" class="col-form-label">Tipo de documento <span style="color:red;">(*)</span></label>
                                                    <select class="tipo_documento custom-select" name="tipo_documento" id="tipo_documento" required>
                                                        <option value="{{$array_datos_info_afiliados[0]->Tipo_documento}}" selected>{{$array_datos_info_afiliados[0]->Nombre_documento}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm otro_documento d-none">
                                                <div class="form-group">
                                                    <label for="otro_nombre_documento" class="col-form-label" style="color:;">Otro Documento</label>
                                                    <input type="text" class="otro_nombre_documento form-control" name="otro_nombre_documento" id="otro_nombre_documento">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nro_identificacion" class="col-form-label">N° de identificación <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="nro_identificacion form-control" name="nro_identificacion" id="nro_identificacion" value="{{$array_datos_info_afiliados[0]->Nro_identificacion}}" disabled>
                                                    <input type="hidden" name="nro_identificacion_enviar" value="{{$array_datos_info_afiliados[0]->Nro_identificacion}}">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="no_creacion_evento h4 text-danger d-none">HAY EVENTO.</p>
                                        <div class="ocultar_seccion_info_afiliado">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="fecha_nacimiento" class="col-form-label">Fecha de nacimiento <span style="color:red;">(*)</span></label>
                                                        <input type="date" class="fecha_nacimiento form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="{{$array_datos_info_afiliados[0]->F_nacimiento}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="edad" class="col-form-label">Edad</label>
                                                        <input type="number" class="edad form-control" name="edad" id="edad" value="{{$array_datos_info_afiliados[0]->Edad}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="genero" class="col-form-label">Género</label>
                                                        <select class="genero custom-select" name="genero" id="genero">
                                                            <option value="{{$array_datos_info_afiliados[0]->Genero}}" selected>{{$array_datos_info_afiliados[0]->Nombre_genero}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="email_info_afiliado" class="col-form-label">Email</label>
                                                        <input type="email" class="email_info_afiliado form-control" name="email_info_afiliado" id="email_info_afiliado" value="{{$array_datos_info_afiliados[0]->Email}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="telefono" class="col-form-label">Teléfono/Celular <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="telefono form-control" name="telefono" id="telefono" value="{{$array_datos_info_afiliados[0]->Telefono_contacto}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="estado_civil" class="col-form-label">Estado civil</label>
                                                        <select class="estado_civil custom-select" name="estado_civil" id="estado_civil">
                                                            <option value="{{$array_datos_info_afiliados[0]->Estado_civil}}" selected>{{$array_datos_info_afiliados[0]->Nombre_estado_civil}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_estado_civil d-none">
                                                    <div class="form-group">
                                                        <label for="otro_estado_civil" class="col-form-label">Otro Estado civil</label>
                                                        <input class="otro_estado_civil form-control" name="otro_estado_civil" id="otro_estado_civil">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="nivel_escolar" class="col-form-label">Nivel escolar</label>
                                                        <select class="nivel_escolar custom-select" name="nivel_escolar" id="nivel_escolar">
                                                            <option value="{{$array_datos_info_afiliados[0]->Nivel_escolar}}">{{$array_datos_info_afiliados[0]->Nombre_nivel_escolar}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_nivel_escolar d-none">
                                                    <div class="form-group">
                                                        <label for="otro_nivel_escolar" class="col-form-label">Otro Nivel escolar</label>
                                                        <input class="otro_nivel_escolar form-control" name="otro_nivel_escolar" id="otro_nivel_escolar">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="dominancia" class="col-form-label">Dominancia</label>
                                                        <select class="dominancia custom-select" name="dominancia" id="dominancia">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_dominancia}}" selected>{{$array_datos_info_afiliados[0]->Dominancia}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="departamento_info_afiliado" class="col-form-label">Departamento</label>
                                                        <select class="departamento_info_afiliado custom-select" name="departamento_info_afiliado" id="departamento_info_afiliado">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_departamento}}" selected>{{$array_datos_info_afiliados[0]->Nombre_departamento}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_municipio_info_afiliado">
                                                    <div class="form-group">
                                                        <label for="municipio_info_afiliado" class="col-form-label">Municipio</label>
                                                        <select class="municipio_info_afiliado custom-select" name="municipio_info_afiliado" id="municipio_info_afiliado">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_municipio}}" selected>{{$array_datos_info_afiliados[0]->Nombre_municipio}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_pais_exterior_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="pais_exterior_info_afiliado" class="col-form-label">País Exterior</label>
                                                        <input type="text" class="pais_exterior_info_afiliado form-control" name="pais_exterior_info_afiliado" id="pais_exterior_info_afiliado">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="ocupacion" class="col-form-label">Ocupación</label>
                                                        <input type="text" class="ocupacion form-control" name="ocupacion" id="ocupacion" value="{{$array_datos_info_afiliados[0]->Ocupacion}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="tipo_afiliado" class="col-form-label">Tipo de afiliado</label>
                                                        <select class="tipo_afiliado custom-select" name="tipo_afiliado" id="tipo_afiliado">
                                                            <option value="{{$array_datos_info_afiliados[0]->Tipo_afiliado}}">{{$array_datos_info_afiliados[0]->Nombre_tipo_afiliado}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_tipo_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="otro_tipo_afiliado" class="col-form-label">Otro Tipo de Afiliado</label>
                                                        <input class="otro_tipo_afiliado form-control" name="otro_tipo_afiliado" id="otro_tipo_afiliado">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="ibc" class="col-form label">IBC</label>
                                                        <input type="text" class="ibc form-control" name="ibc" id="ibc" value="{{$array_datos_info_afiliados[0]->Ibc}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="eps" class="col-form label">EPS</label>
                                                        <select class="eps custom-select" name="eps" id="eps">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_eps}}">{{$array_datos_info_afiliados[0]->Nombre_eps}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_eps d-none">
                                                    <div class="form-group">
                                                        <label for="otra_eps" class="col-form label">Otra EPS</label>
                                                        <input type="text" class="otra_eps form-control" name="otra_eps" id="otra_eps">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="afp" class="col-form label">AFP</label>
                                                        <select class="afp custom-select" name="afp" id="afp">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_Afp}}">{{$array_datos_info_afiliados[0]->Nombre_afp}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_afp d-none">
                                                    <div class="form-group">
                                                        <label for="otra_afp" class="col-form label">Otra AFP</label>
                                                        <input type="text" class="otra_afp form-control" name="otra_afp" id="otra_afp">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="arl_info_afiliado" class="col-form label">ARL</label>
                                                        <select class="arl_info_afiliado custom-select" name="arl_info_afiliado" id="arl_info_afiliado">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_Arl}}">{{$array_datos_info_afiliados[0]->Nombre_arl}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_arl_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="otra_arl_info_afiliado" class="col-form label">Otra ARL</label>
                                                        <input type="text" class="otra_arl_info_afiliado form-control" name="otra_arl_info_afiliado" id="otra_arl_info_afiliado">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3 si_no_apoderado">
                                                    <div class="form-group">
                                                        <label for="apoderado" class="col-form-label">Apoderado</label>
                                                        <select class="apoderado custom-select" name="apoderado" id="apoderado">
                                                            <option value="{{$array_datos_info_afiliados[0]->Apoderado}}">{{$array_datos_info_afiliados[0]->Apoderado}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php 
                                                $apoderados = $array_datos_info_afiliados[0]->Apoderado;
                                                if($apoderados == 'Si' ): ?>
                                                    <div class="col-3 columna_nombre_apoderado">
                                                        <div class="form-group">
                                                            <label for="nombre_apoderado" class="col-form-label">Nombre del apoderado</label>
                                                            <input type="text" class="nombre_apoderado form-control" name="nombre_apoderado" id="nombre_apoderado" value="{{$array_datos_info_afiliados[0]->Nombre_apoderado}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-3 columna_identificacion_apoderado">
                                                        <div class="form-group">
                                                            <label for="nro_identificacion_apoderado" class="col-form-label">N° identificación apoderado</label>
                                                            <input type="text" class="nro_identificacion_apoderado form-control" name="nro_identificacion_apoderado" id="nro_identificacion_apoderado" value="{{$array_datos_info_afiliados[0]->Nro_identificacion_apoderado}}">
                                                        </div>
                                                    </div>
                                                <?php elseif ($apoderados == 'No' ): ?> 
                                                    <div class="col-3 columna_nombre_apoderado d-none">
                                                        <div class="form-group">
                                                            <label for="nombre_apoderado" class="col-form-label">Nombre del apoderado</label>
                                                            <input type="text" class="nombre_apoderado form-control" name="nombre_apoderado" id="nombre_apoderado" value="{{$array_datos_info_afiliados[0]->Nombre_apoderado}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-3 columna_identificacion_apoderado d-none">
                                                        <div class="form-group">
                                                            <label for="nro_identificacion_apoderado" class="col-form-label">N° identificación apoderado</label>
                                                            <input type="text" class="nro_identificacion_apoderado form-control" name="nro_identificacion_apoderado" id="nro_identificacion_apoderado" value="{{$array_datos_info_afiliados[0]->Nro_identificacion_apoderado}}">
                                                        </div>
                                                    </div>
                                                <?php endif ?>                                                                                                
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="activo" class="col-form-label">Activo <span style="color:red;">(*)</span></label>
                                                        <select class="activo custom-select" name="activo" id="activo" required>                                                            
                                                            <option value="{{$array_datos_info_afiliados[0]->Activo}}">{{$array_datos_info_afiliados[0]->Activo}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN LABORAL --}}
                        <div class="row ocultar_seccion_info_laboral">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información laboral</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">   
                                            <?php
                                                $radio = $array_datos_info_laboral[0]->Tipo_empleado;
                                                if ($radio == 'Empleado actual'):?>                                                
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                      <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" checked required>
                                                      <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleo Actual</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                      <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" required>
                                                      <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                      <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="beneficiario" value="Beneficiario" required>
                                                      <label class="form-check-label custom-control-label" for="beneficiario"><strong>Beneficiario</strong></label>
                                                    </div>
                                                </div>
                                            <?php elseif ($radio == 'Independiente'):?>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" required>
                                                    <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleo Actual</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" checked required>
                                                    <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="beneficiario" value="Beneficiario" required>
                                                    <label class="form-check-label custom-control-label" for="beneficiario"><strong>Beneficiario</strong></label>
                                                    </div>
                                                </div>
                                            <?php elseif ($radio == 'Beneficiario'): ?>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" required>
                                                    <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleo Actual</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" required>
                                                    <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="beneficiario" value="Beneficiario" checked required>
                                                    <label class="form-check-label custom-control-label" for="beneficiario"><strong>Beneficiario</strong></label>
                                                    </div>
                                                </div>
                                            <?php endif?>
                                        </div> 
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="arl_info_laboral" class="col-form-label">ARL</label>
                                                    <select class="arl_info_laboral custom-select" name="arl_info_laboral" id="arl_info_laboral">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_arl}}">{{$array_datos_info_laboral[0]->Nombre_arl}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm otro_arl_info_laboral d-none">
                                                <div class="form-group">
                                                    <label for="otra_arl_info_laboral" class="col-form-label">Otra ARL</label>
                                                    <input type="text" class="otra_arl_info_laboral form-control" name="otra_arl_info_laboral" id="otra_arl_info_laboral">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="empresa" class="col-form-label">Empresa <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="empresa form-control" name="empresa" id="empresa"  value="{{$array_datos_info_laboral[0]->Empresa}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nit_cc" class="col-form-label">NIT / CC <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="nit_cc form-control" name="nit_cc" id="nit_cc"  value="{{$array_datos_info_laboral[0]->Nit_o_cc}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefono_empresa" class="col-form-label">Télefono empresa</label>
                                                    <input type="text" class="telefono_empresa form-control" name="telefono_empresa" id="telefono_empresa" value="{{$array_datos_info_laboral[0]->Telefono_empresa}}" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="email_info_laboral" class="col-form-label">Email</label>
                                                    <input type="email" class="email_info_laboral form-control" name="email_info_laboral" id="email_info_laboral" value="{{$array_datos_info_laboral[0]->Email}}">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="direccion_info_laboral" class="col-form-label">Dirección</label>
                                                    <input type="text" class="direccion_info_laboral form-control" name="direccion_info_laboral" id="direccion_info_laboral" value="{{$array_datos_info_laboral[0]->Direccion}}">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="departamento_info_laboral" class="col-form-label">Departamento</label>
                                                    <select class="departamento_info_laboral custom-select" name="departamento_info_laboral" id="departamento_info_laboral">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_departamento}}">{{$array_datos_info_laboral[0]->Nombre_departamento}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm columna_municipio_info_laboral">
                                                <div class="form-group">
                                                    <label for="municipio_info_laboral" class="col-form-label">Municipio</label>
                                                    <select class="municipio_info_laboral custom-select" name="municipio_info_laboral" id="municipio_info_laboral">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_municipio}}">{{$array_datos_info_laboral[0]->Nombre_municipio}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm columna_pais_exterior_info_laboral d-none">
                                                <div class="form-group">
                                                    <label for="pais_exterior_info_laboral" class="col-form-label">País Exterior</label>
                                                    <input type="text" class="pais_exterior_info_laboral form-control" name="pais_exterior_info_laboral" id="pais_exterior_info_laboral">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="actividad_economica" class="col-form-label">Actividad económica</label>
                                                    <select class="actividad_economica custom-select" name="actividad_economica" id="actividad_economica">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_actividad_economica}}">{{$array_datos_info_laboral[0]->Nombre_actividad}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="clase_riesgo" class="col-form-label">Clase / Riesgo</label>
                                                    <select class="clase_riesgo custom-select" name="clase_riesgo" id="clase_riesgo">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_clase_riesgo}}">{{$array_datos_info_laboral[0]->Nombre_riesgo}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="persona_contacto" class="col-form-label">Persona de contacto</label>
                                                    <input type="text" class="persona_contacto form-control" name="persona_contacto" id="persona_contacto" value="{{$array_datos_info_laboral[0]->Persona_contacto}}">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefono_persona_contacto" class="col-form-label">Teléfono persona contacto</label>
                                                    <input type="text" class="telefono_persona_contacto form-control" name="telefono_persona_contacto" id="telefono_persona_contacto" value="{{$array_datos_info_laboral[0]->Telefono_persona_contacto}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="codigo_ciuo" class="col-form-label">Código CIUO</label>
                                                    <select class="codigo_ciuo custom-select" name="codigo_ciuo" id="codigo_ciuo">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_codigo_ciuo}}">{{$array_datos_info_laboral[0]->Nombre_ciuo}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fecha_ingreso" class="col-form-label">Fecha de ingreso</label>
                                                    <input type="date" class="fecha_ingreso form-control" name="fecha_ingreso" id="fecha_ingreso" value="{{$array_datos_info_laboral[0]->F_ingreso}}" >
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="cargo" class="col-form-label">Cargo</label>
                                                    <input type="text" class="cargo form-control" name="cargo" id="cargo" value="{{$array_datos_info_laboral[0]->Cargo}}" >
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="funciones_cargo" class="col-form-label">Funciones del cargo</label>
                                                    <textarea class="funciones_cargo form-control" name="funciones_cargo" id="funciones_cargo" rows="2">{{$array_datos_info_laboral[0]->Funciones_cargo}} </textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="antiguedad_empresa" class="col-form-label">Antiguedad en empresa (Meses)</label>
                                                    <input type="number" class="antiguedad_empresa form-control" name="antiguedad_empresa" id="antiguedad_empresa" value="{{$array_datos_info_laboral[0]->Antiguedad_empresa}}">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="antiguedad_cargo" class="col-form-label">Antiguedad en el cargo (Meses)</label>
                                                    <input type="number" class="antiguedad_cargo form-control" name="antiguedad_cargo" id="antiguedad_cargo" value="{{$array_datos_info_laboral[0]->Antiguedad_cargo_empresa}}">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fecha_retiro" class="col-form-label">Fecha de retiro</label>
                                                    <input type="date" class="fecha_retiro form-control" name="fecha_retiro" id="fecha_retiro" value="{{$array_datos_info_laboral[0]->F_retiro}}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="descripcion" class="col-form-label">Descripción</label>
                                                    <textarea class="descripcion form-control" name="descripcion" id="descripcion" rows="2">{{$array_datos_info_laboral[0]->Descripcion}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- OPCIONES PARA HABILITAR EL COLLAPSE Y EL MODAL --}}
                                        <div class="row">
                                            <div class="col-6">
                                                <a  data-toggle="collapse" class="text-dark" id="llenar_tabla_historico_empresas" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="far fa-eye text-info"></i> <strong>ver histórico empresas</strong></a>&nbsp;
                                                <a href="javascript:void(0);" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalInfoLaboral"><i class="fas fa-plus-circle text-info"></i> <strong>Agregar nueva empresa</strong></a>
                                            </div>
                                        </div><br>
                                        {{-- COLLAPSE PARA MOSTRAR EL HISTÓRICO DE RESULTADOS --}}
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="ver_historico_empresa_afiliado">
                                                    <div class="collapse" id="collapseExample">
                                                      <div class="card card-body">
                                                        <div class="table table-responsive" id="si_tabla">
                                                            <table id="listado_historico_empresas" class="table table-striped table-bordered" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Tipo Empleo</th>
                                                                        <th>ARL</th>
                                                                        <th>Empresa</th>
                                                                        <th>NIT / CC</th>
                                                                        <th>Télefono Empresa</th>
                                                                        <th>Email</th>
                                                                        <th>Dirección</th>
                                                                        <th>Departamento</th>
                                                                        <th>Municipio</th>
                                                                        <th>Actividad económica</th>
                                                                        <th>Clase / Riesgo</th>
                                                                        <th>Persona de contacto</th>
                                                                        <th>Teléfono Persona Contacto</th>
                                                                        <th>Código CIUO</th>
                                                                        <th>Fecha de ingreso</th>
                                                                        <th>Cargo</th>
                                                                        <th>Funciones Del Cargo</th>
                                                                        <th>Antiguedad en Empresa (Meses)</th>
                                                                        <th>Antiguedad en el Cargo (Meses)</th>
                                                                        <th>Fecha de retiro</th>
                                                                        <th>Descripción</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="borrar"></tbody>
                                                            </table>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- MODAL PARA AGREGAR INFORMACION LABORAL --}}
                                        <div class="row">
                                            <div class="contenedor_agregar_empresa" style="float: left;">
                                                <x-adminlte-modal id="modalInfoLaboral" title="Agregar Información laboral" theme="info" icon="fas fa-plus" size='xl' disable-animations>
                                                    <form id="formulario_empresa">
                                                        @csrf
                                                        <div class="row text-center">
                                                            <div class="col-sm">
                                                                <div class="form-check custom-control custom-radio">
                                                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo_registrar" id="empleo_actual_registrar" value="Empleado actual" required>
                                                                  <label class="form-check-label custom-control-label" for="empleo_actual_registrar"><strong>Empleo Actual</strong></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm">
                                                                <div class="form-check custom-control custom-radio">
                                                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo_registrar" id="independiente_registrar" value="Independiente" required>
                                                                  <label class="form-check-label custom-control-label" for="independiente_registrar"><strong>Independiente</strong></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm">
                                                                <div class="form-check custom-control custom-radio">
                                                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo_registrar" id="beneficiario_registrar" value="Beneficiario" required>
                                                                  <label class="form-check-label custom-control-label" for="beneficiario_registrar"><strong>Beneficiario</strong></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="arl_info_laboral_registrar" class="col-form-label">ARL</label><br>
                                                                        <select class="arl_info_laboral_registrar custom-select" name="arl_info_laboral_registrar" id="arl_info_laboral_registrar" style="width: 261.5px;"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm otro_arl_info_laboral_registrar d-none">
                                                                    <div class="form-group">
                                                                        <label for="otra_arl_info_laboral_registrar" class="col-form-label">Otra ARL</label>
                                                                        <input type="text" class="otra_arl_info_laboral_registrar form-control" name="otra_arl_info_laboral_registrar" id="otra_arl_info_laboral_registrar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="empresa_registrar" class="col-form-label">Empresa <span style="color:red;">(*)</span></label>
                                                                        <input type="text" class="empresa_registrar form-control" name="empresa_registrar" id="empresa_registrar" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="nit_cc_registrar" class="col-form-label">NIT / CC <span style="color:red;">(*)</span></label>
                                                                        <input type="text" class="nit_cc_registrar form-control" name="nit_cc_registrar" id="nit_cc_registrar" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="telefono_empresa_registrar" class="col-form-label">Télefono empresa</label>
                                                                        <input type="text" class="telefono_empresa_registrar form-control" name="telefono_empresa_registrar" id="telefono_empresa_registrar">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="email_info_laboral_registrar" class="col-form-label">Email</label>
                                                                        <input type="email" class="email_info_laboral_registrar form-control" name="email_info_laboral_registrar" id="email_info_laboral_registrar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="direccion_info_laboral_registrar" class="col-form-label">Dirección</label>
                                                                        <input type="text" class="direccion_info_laboral_registrar form-control" name="direccion_info_laboral_registrar" id="direccion_info_laboral_registrar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="departamento_info_laboral_registrar" class="col-form-label">Departamento</label>
                                                                        <select class="departamento_info_laboral_registrar custom-select" name="departamento_info_laboral_registrar" id="departamento_info_laboral_registrar" style="width: 261.5px;"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm columna_municipio_info_laboral_registrar">
                                                                    <div class="form-group">
                                                                        <label for="municipio_info_laboral_registrar" class="col-form-label">Municipio</label>
                                                                        <select class="municipio_info_laboral_registrar custom-select" name="municipio_info_laboral_registrar" id="municipio_info_laboral_registrar" style="width: 261.5px;" disabled></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm columna_pais_exterior_info_laboral_registrar d-none">
                                                                    <div class="form-group">
                                                                        <label for="pais_exterior_info_laboral_registrar" class="col-form-label">País Exterior</label>
                                                                        <input type="text" class="pais_exterior_info_laboral_registrar form-control" name="pais_exterior_info_laboral_registrar" id="pais_exterior_info_laboral_registrar">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="actividad_economica_registrar" class="col-form-label">Actividad económica</label>
                                                                        <select class="actividad_economica_registrar custom-select" name="actividad_economica_registrar" id="actividad_economica_registrar" style="width: 261.5px;"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="clase_riesgo_registrar" class="col-form-label">Clase / Riesgo</label>
                                                                        <select class="clase_riesgo_registrar custom-select" name="clase_riesgo_registrar" id="clase_riesgo_registrar" style="width: 261.5px;"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="persona_contacto_registrar" class="col-form-label">Persona de contacto</label>
                                                                        <input type="text" class="persona_contacto_registrar form-control" name="persona_contacto_registrar" id="persona_contacto_registrar" style="width: 100% !important;">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="telefono_persona_contacto_registrar" class="col-form-label">Tel persona contacto</label>
                                                                        <input type="text" class="telefono_persona_contacto_registrar form-control" name="telefono_persona_contacto_registrar" id="telefono_persona_contacto_registrar" style="width: 100% !important;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="codigo_ciuo_registrar" class="col-form-label">Código CIUO</label><br>
                                                                        <select class="codigo_ciuo_registrar custom-select" name="codigo_ciuo_registrar" id="codigo_ciuo_registrar" style="width: 353.67px;"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="fecha_ingreso_registrar" class="col-form-label">Fecha de ingreso</label>
                                                                        <input type="date" class="fecha_ingreso_registrar form-control" name="fecha_ingreso_registrar" id="fecha_ingreso_registrar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="cargo_registrar" class="col-form-label">Cargo</label>
                                                                        <input type="text" class="cargo_registrar form-control" name="cargo_registrar" id="cargo_registrar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label for="funciones_cargo_registrar" class="col-form-label">Funciones del cargo</label>
                                                                        <textarea class="funciones_cargo_registrar form-control" name="funciones_cargo_registrar" id="funciones_cargo_registrar" rows="2"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="antiguedad_empresa_registrar" class="col-form-label">Antiguedad en empresa (Meses)</label>
                                                                        <input type="number" class="antiguedad_empresa_registrar form-control" name="antiguedad_empresa_registrar" id="antiguedad_empresa_registrar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="antiguedad_cargo_registrar" class="col-form-label">Antiguedad en el cargo (Meses)</label>
                                                                        <input type="number" class="antiguedad_cargo_registrar form-control" name="antiguedad_cargo_registrar" id="antiguedad_cargo_registrar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm">
                                                                    <div class="form-group">
                                                                        <label for="fecha_retiro_registrar" class="col-form-label">Fecha de retiro</label>
                                                                        <input type="date" class="fecha_retiro_registrar form-control" name="fecha_retiro_registrar" id="fecha_retiro_registrar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <label for="descripcion_registrar" class="col-form-label">Descripción</label>
                                                                        <textarea class="descripcion_registrar form-control" name="descripcion_registrar" id="descripcion_registrar" rows="2"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="no_creada_empresa alert alert-danger mt-2 d-none" role="alert"></div>
                                                                    <div class="creada_empresa alert alert-success mt-2 d-none" role="alert"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <x-slot name="footerSlot">
                                                            <x-adminlte-button class="mr-auto" id="guardar_otra_empresa" theme="info" label="Guardar"/>
                                                            <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
                                                        </x-slot>
                                                    </form>
                                                </x-adminlte-modal>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN PERICIAL --}}
                        <div class="row ocultar_seccion_info_pericial">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información Pericial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="motivo_solicitud" class="col-form label">Motivo solicitud</label>
                                                    <select class="motivo_solicitud custom-select" name="motivo_solicitud" id="motivo_solicitud">
                                                        <option value="{{$array_datos_info_pericial[0]->Id_motivo_solicitud}}">{{$array_datos_info_pericial[0]->Nombre_solicitud}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="tipovinculo" class="col-form label">Tipo de vinculación</label>
                                                    <select class="tipovinculo custom-select" name="tipovinculo" id="tipovinculo">
                                                        <option value="{{$array_datos_info_pericial[0]->Tipo_vinculacion}}">{{$array_datos_info_pericial[0]->tipo_viculacion}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="regimen" class="col-form label">Régimen en salud</label>
                                                    <select class="regimen custom-select" name="regimen" id="regimen">
                                                        <option value="{{$array_datos_info_pericial[0]->Regimen_salud}}">{{$array_datos_info_pericial[0]->regimen_salud}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="solicitante" class="col-form label">Solicitante</label>
                                                    <select class="solicitante custom-select" name="solicitante" id="solicitante">
                                                        <option value="{{$array_datos_info_pericial[0]->Id_solicitante}}">{{$array_datos_info_pericial[0]->Solicitante}}</option>
                                                    </select>
                                                </div>
                                            </div>     
                                            <div class="col-4 columna_otro_solicitante d-none">
                                                <div class="form-group">
                                                    <label for="otro_solicitante" class="col-form label">Otro solicitante</label>
                                                    <input type="text" class="otro_solicitante form-control" name="otro_solicitante" id="otro_solicitante">
                                                </div>
                                            </div>
                                            <div class="col-4 columna_nombre_solicitante">
                                                <div class="form-group">
                                                    <label for="nombre_solicitante" class="col-form label">Nombre de solicitante</label>
                                                    <select class="nombre_solicitante custom-select" name="nombre_solicitante" id="nombre_solicitante">
                                                        <option value="{{$array_datos_info_pericial[0]->Id_nombre_solicitante}}">{{$array_datos_info_pericial[0]->Nombre_solicitante}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 columna_otro_nombre_solicitante d-none">
                                                <div class="form-group">
                                                    <label for="otro_nombre_solicitante" class="col-form label">Otro Nombre de solicitante</label>
                                                    <input type="text" class="otro_nombre_solicitante form-control" name="otro_nombre_solicitante" id="otro_nombre_solicitante">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="fuente_informacion" class="col-form label">Fuente de información</label>
                                                    <select class="fuente_informacion custom-select" name="fuente_informacion" id="fuente_informacion">
                                                        <option value="{{$array_datos_info_pericial[0]->Fuente_informacion}}">{{$array_datos_info_pericial[0]->fuente_informacion}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 columna_otra_fuente_informacion d-none">
                                                <div class="form-group">
                                                    <label for="otra_fuente_informacion" class="col-form label">Otra Fuente de información</label>
                                                    <input type="text" class="otra_fuente_informacion form-control" name="otra_fuente_informacion" id="otra_fuente_informacion">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="row">
                                            <div class="col-sm">
                                                <label for="solicitante" class="col-form label">Solicitante</label>
                                                <select class="solicitante custom-select" name="solicitante" id="solicitante"></select>
                                            </div>     
                                            <div class="col-sm">
                                                <label for="otro_solicitante" class="col-form label">Otro solicitante</label>
                                                <input type="text" class="otro_solicitante form-control" name="otro_solicitante" id="otro_solicitante">
                                            </div>
                                            <div class="col-sm">
                                                <label for="nombre_solicitante" class="col-form label">Nombre de solicitante</label>
                                                <select class="nombre_solicitante custom-select" name="nombre_solicitante" id="nombre_solicitante"></select>
                                            </div>
                                            <div class="col-sm">
                                                <label for="otro_nombre_solicitante" class="col-form label">Otro Nombre de solicitante</label>
                                                <input type="text" class="otro_nombre_solicitante form-control" name="otro_nombre_solicitante" id="otro_nombre_solicitante">
                                            </div>
                                            <div class="col-sm">
                                                <label for="fuente_informacion" class="col-form label">Fuente de información</label>
                                                <select class="fuente_informacion custom-select" name="fuente_informacion" id="fuente_informacion"></select>
                                            </div>
                                            <div class="col-sm">
                                                <label for="otra_fuente_informacion" class="col-form label">Otra Fuente de información</label>
                                                <input type="text" class="otra_fuente_informacion form-control" name="otra_fuente_informacion" id="otra_fuente_informacion">
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN ASIGNACION --}}
                        <div class="row ocultar_seccion_info_asignacion">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Asignación</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="proceso" class="col-form label">Proceso <span style="color:red;">(*)</span></label>
                                                <select class="proceso custom-select" name="proceso" id="proceso" requierd>
                                                    <option value="{{$array_datos_info_asignacion[0]->Id_proceso}}">{{$array_datos_info_asignacion[0]->Nombre_proceso}}</option>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <label for="servicio" class="col-form label">Servicio <span style="color:red;">(*)</span></label>
                                                <select class="servicio custom-select" name="servicio" id="servicio" requierd>
                                                    <option value="{{$array_datos_info_asignacion[0]->Id_servicio}}">{{$array_datos_info_asignacion[0]->Nombre_servicio}}</option>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <label for="accion" class="col-form label">Acción <span style="color:red;">(*)</span></label>
                                                <select class="accion custom-select" name="accion" id="accion" requierd>
                                                    <option value="{{$array_datos_info_asignacion[0]->Id_accion}}">{{$array_datos_info_asignacion[0]->Nombre_accion}}</option>
                                                </select>
                                            </div>                                                                                       
                                        </div>    
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="descripcion_asignacion" class="col-form label">Descripción</label>                                            
                                                <textarea class="form-control" name="descripcion_asignacion" id="descripcion_asignacion" rows="2" required>{{$array_datos_info_asignacion[0]->Descripcion}}</textarea>
                                            </div> 
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="grupo_botones" style="float: left;">
                    <input type="reset" id="Borrar" class="btn btn-info" value="Borrar">
                    <input type="submit" id="Edicion" class="btn btn-info" value="Actualizar" onclick="OcultarbotonActualizar()">
                </div>
                <div class="text-center" id="mostrar-barra2"  style="display:none;">                                
                    <button class="btn btn-info" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Actualizando el Evento...
                    </button>
                </div>
            </div>
        </form>
        
    </div>
@stop

@section('js')
    <script src="/js/selectores_gestion_edicion.js"></script>  

    <script type="text/javascript">
        // var conteo = 0;
        $('#llenar_tabla_historico_empresas').click(function(){
            $('#borrar').empty();
            // conteo = conteo + 1;
            // if (conteo == 1) {
            //     conteo = 0;
            // }
            var nro_ident = $('#listado_usuarios_asignacion_rol').val();
            var datos_llenar_tabla_info_laboral = {
                '_token': $('input[name=_token]').val(),
                'numero_identificacion' : $('#nro_identificacion').val()
            };
            $.ajax({
                type:'POST',
                url:'/consultaHistoricoEmpresas',
                data: datos_llenar_tabla_info_laboral,
                success:function(data) {
                    if(data.length == 0){
                        $('#borrar').empty();
                    }else{
                        // console.log(data);
                        $.each(data, function(index, value){
                            llenar(data, index, value);
                        });
                    }
                }
            });
        });

        function llenar(response, index, value){
            $('#listado_historico_empresas').DataTable({
                "destroy": true,
                "data": response,
                "pageLength": 2,
                // "order": [[2, 'desc']],
                "columns":[
                    {"data":"Tipo_empleado"},
                    {"data":"Nombre_arl"},
                    {"data":"Empresa"},
                    {"data":"Nit_o_cc"},
                    {"data":"Telefono_empresa"},
                    {"data":"Email"},
                    {"data":"Direccion"},
                    {"data":"Nombre_departamento"},
                    {"data":"Nombre_municipio"},
                    {"data": "full_actividad_economica"},
                    {"data":"Nombre_riesgo"},
                    {"data":"Persona_contacto"},
                    {"data":"Telefono_persona_contacto"},
                    {"data":"full_ciuo"},
                    {"data":"F_ingreso"},
                    {"data":"Cargo"},
                    {"data":"Funciones_cargo"},
                    {"data":"Antiguedad_empresa"},
                    {"data":"Antiguedad_cargo_empresa"},
                    {"data":"F_retiro"},
                    {"data":"Descripcion"}
                ],
                "language":{
                    "search": "Buscar",
                    "lengthMenu": "Mostrar _MENU_ resgistros por página",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "first": "Primero",
                        "last": "Último"
                    },
                    "emptyTable": "No se encontró información",
                    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                }
            });
        }
    </script>

    <script>
        function OcultarbotonActualizar(){
            $('#Edicion').addClass('d-none');
            $('#Borrar').addClass('d-none');
            $('#mostrar-barra2').css("display","block");
        }
    </script> 
@stop