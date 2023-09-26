@extends('adminlte::page')
@section('title', 'DTO ATEL')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-8">
            <div>
                <a href="{{route("bandejaOrigen")}}" class="btn btn-info" type="button"><i class="fas fa-archive"></i> Regresar Bandeja</a>
                <a onclick="document.getElementById('botonEnvioVista').click();" style="cursor:pointer;" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Módulo Origen</a>
                <p>
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Origen ATEL - Evento: {{$array_datos_DTO_ATEL[0]->ID_evento}}</h4>
            <h5 style="font-style: italic;">Determinación de Origen (DTO)</h5>
            <input type="hidden" id="para_ver_edicion_evento" value="{{ route('gestionInicialEdicion') }}">
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form action="" method="POST" id="form_multiproposito">
                        @csrf
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Validación de Cobertura y Tipo de evento</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="activo">Activo <span style="color:red;">(*)</span></label>
                                            <select class="custom-select es_activo" name="es_activo" id="es_activo">
                                                <option value=""></option>
                                                <option value="Si">Si</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Tipo de Evento <span style="color:red;">(*)</span></label>
                                            <input type="hidden" id="nombre_evento_bd" value="{{$array_datos_DTO_ATEL[0]->Nombre_evento}}">
                                            <select class="custom-select tipo_evento" name="tipo_evento" id="tipo_evento" disabled></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- FORMULARIO ACCIDENTE --}}
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información del afiliado</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre_afiliado">Nombre de afiliado</label>
                                            <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_DTO_ATEL[0]->Nombre_afiliado}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nro_identificacion">N° Identificación</label>
                                            <input type="text" class="form-control" name="nro_identificacion" id="nro_identificacion" value="{{$array_datos_DTO_ATEL[0]->Nro_identificacion}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="id_evento">ID evento</span></label>
                                            <br>
                                            {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                            {{-- <input type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_DTO_ATEL[0]->ID_evento}}" disabled> --}}
                                            <input class="btn text-info" id="edit_evento_{{$array_datos_DTO_ATEL[0]->ID_evento}}" type="submit" style="font-weight: bold;" value="{{$array_datos_DTO_ATEL[0]->ID_evento}}">
                                            <input type="hidden" name="badera_buscador_evento" id="badera_buscador_evento" value="desdebuscador">
                                            <input type="hidden" name="newIdEvento" value="{{$array_datos_DTO_ATEL[0]->ID_evento}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información General del Dictamen</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="f_dictamen">Fecha Dictamen</label>
                                            <input type="text" class="form-control" name="fecha_dictamen" id="fecha_dictamen" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nro_dictamen">Dictamen N°</label>
                                            <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" style="color: red;" value="{{$numero_consecutivo}}" disabled>   
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="motivo_solicitud">Motivo Solicitud <span style="color:red;">(*)</span></label>
                                            <input type="hidden" id="motivo_solicitud_bd" value="{{$motivo_solicitud_actual[0]->Nombre_solicitud}}">
                                            <select class="custom-select motivo_solicitud" name="motivo_solicitud" id="motivo_solicitud"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="nombre_apoderado">Nombre apoderado</label>
                                            <input type="text" class="form-control" name="nombre_apoderado" id="nombre_apoderado" value="{{$datos_apoderado_actual[0]->Nombre_apoderado}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="identificacion_apoderado">N° identificación apoderado</label>
                                            <input type="text" class="form-control" name="identificacion_apoderado" id="identificacion_apoderado" value="{{$datos_apoderado_actual[0]->Nro_identificacion_apoderado}}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información laboral</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <?php $radio = $array_datos_info_laboral[0]->Tipo_empleado; if($radio == "Empleado actual"):?>
                                        <div class="col-sm">
                                            <div class="form-check custom-control custom-radio">
                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" checked disabled>
                                            <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleo Actual</strong></label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-check custom-control custom-radio">
                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" disabled>
                                            <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                            </div>
                                        </div>
                                    <?php elseif ($radio == "Independiente"):?>
                                        <div class="col-sm">
                                            <div class="form-check custom-control custom-radio">
                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" disabled>
                                            <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleado Actual</strong></label>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-check custom-control custom-radio">
                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" checked disabled>
                                            <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                            </div>
                                        </div>
                                    <?php endif?>
                                </div>
                                <?php if($radio == "Empleado actual"):?>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="empresa" class="col-form-label">Empresa</label>
                                                <input type="text" class="empresa form-control" name="empresa" id="empresa"  value="{{$array_datos_info_laboral[0]->Empresa}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nit_cc" class="col-form-label">NIT / CC</label>
                                                <input type="text" class="nit_cc form-control" name="nit_cc" id="nit_cc"  value="{{$array_datos_info_laboral[0]->Nit_o_cc}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="actividad_economica" class="col-form-label">Actividad económica</label>
                                                <input type="text" class="form-control" name="act_economica" id="act_economica" value="{{$array_datos_info_laboral[0]->Id_actividad_economica}} - {{$array_datos_info_laboral[0]->Nombre_actividad}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="departamento_info_laboral" class="col-form-label">Departamento</label>
                                                <input type="hidden" name="id_departamento" id="id_departamento" value="{{$array_datos_info_laboral[0]->Id_departamento}}">
                                                <input type="text" class="form-control" name="nombre_departamento" id="nombre_departamento" value="{{$array_datos_info_laboral[0]->Nombre_departamento}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="municipio_info_laboral" class="col-form-label">Municipio</label>
                                                <input type="hidden" name="id_municipio" id="id_municipio" value="{{$array_datos_info_laboral[0]->Id_municipio}}">
                                                <input type="text" class="form-control" name="nombre_municipio" id="nombre_municipio" value="{{$array_datos_info_laboral[0]->Nombre_municipio}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_ingreso" class="col-form-label">Fecha de ingreso</label>
                                                <input type="date" class="fecha_ingreso form-control" name="fecha_ingreso" id="fecha_ingreso" value="{{$array_datos_info_laboral[0]->F_ingreso}}" max="{{date("Y-m-d")}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="cargo" class="col-form-label">Cargo</span></label>
                                                <input type="text" class="cargo form-control" name="cargo" id="cargo" value="{{$array_datos_info_laboral[0]->Cargo}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="antiguedad_cargo" class="col-form-label">Antiguedad en el cargo (Meses)</label>
                                                <input type="number" class="antiguedad_cargo form-control" name="antiguedad_cargo" id="antiguedad_cargo" value="{{$array_datos_info_laboral[0]->Antiguedad_cargo_empresa}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="antiguedad_empresa" class="col-form-label">Antiguedad en empresa (Meses)</label>
                                                <input type="number" class="antiguedad_empresa form-control" name="antiguedad_empresa" id="antiguedad_empresa" value="{{$array_datos_info_laboral[0]->Antiguedad_empresa}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="descripcion" class="col-form-label">Descripción</label>
                                            <textarea class="descripcion form-control" name="descripcion" id="descripcion" rows="2" disabled>{{$array_datos_info_laboral[0]->Descripcion}}</textarea>
                                        </div>
                                    </div>
                                <?php endif?>
                            </div>
                        </div>
                    </form>  
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript">
        $(document).on('mouseover',"input[id^='edit_evento_']", function(){
            let url_editar_evento = $('#para_ver_edicion_evento').val();
            $("form[id^='form_multiproposito']").attr("action", url_editar_evento);    
        });
    </script>
    <script type="text/javascript" src="/js/dto_atel.js"></script>
@stop