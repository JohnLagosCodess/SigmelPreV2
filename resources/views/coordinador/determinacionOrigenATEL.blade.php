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
            <h4>Origen ATEL - Evento: {{$array_datos_calificacion_origen[0]->ID_evento}}</h4>
            <h5 style="font-style: italic;">Determinación de Origen (DTO)</h5>
            <input type="hidden" id="para_ver_edicion_evento" value="{{ route('gestionInicialEdicion') }}">
            <input type="hidden" name="Id_Evento_dto_atel" id="Id_Evento_dto_atel" value="<?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){ echo $array_datos_calificacion_origen[0]->ID_evento;}?>">
            <input type="hidden" name="Id_Asignacion_dto_atel" id="Id_Asignacion_dto_atel" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Asignacion)){echo $array_datos_calificacion_origen[0]->Id_Asignacion;}?>">
            <input type="hidden" name="Id_Proceso_dto_atel" id="Id_Proceso_dto_atel" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_proceso)){echo $array_datos_calificacion_origen[0]->Id_proceso;}?>">
            <input type="hidden" id="id_dto_atel" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Id_Dto_ATEL)){echo $datos_bd_DTO_ATEL[0]->Id_Dto_ATEL;}?>">
            <input type="hidden" id="nombre_evento_gestion_edicion" value="<?php if(!empty($array_datos_calificacion_origen[0]->Nombre_evento)){echo $array_datos_calificacion_origen[0]->Nombre_evento;}?>">
        </div>
        <form method="POST" id="form_DTO_ATEL">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
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
                                            <select class="custom-select es_activo" name="es_activo" id="es_activo" required>
                                                <option value=""></option>
                                                <?php if(!empty($datos_bd_DTO_ATEL[0]->Activo)):?>
                                                    <?php if($datos_bd_DTO_ATEL[0]->Activo == "Si"):?>
                                                        <option value="Si" selected>Si</option>
                                                        <option value="No">No</option>
                                                    <?php else:?>
                                                        <option value="Si">Si</option>
                                                        <option value="No" selected>No</option>
                                                    <?php endif?>
                                                <?php else:?>
                                                    <option value="Si">Si</option>
                                                    <option value="No">No</option>
                                                <?php endif?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Tipo de Evento <span style="color:red;">(*)</span></label>
                                            <input type="hidden" id="nombre_evento_guardado" value="{{$nombre_del_evento_guardado}}">
                                            <input type="hidden" id="bd_tipo_evento" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Tipo_evento)){echo $datos_bd_DTO_ATEL[0]->Tipo_evento;}?>">
                                            <select class="custom-select tipo_evento" name="tipo_evento" id="tipo_evento" disabled required></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- FORMULARIO ACCIDENTE, ENFERMEDAD, INCIDENTE, SIN COBERTURA --}}
                        <div id="mostrar_ocultar_formularios" class="d-none1">
                            {{-- Información del afiliado --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Información del Afiliado</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nombre_afiliado">Nombre de afiliado</label>
                                                <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="<?php if(!empty($array_datos_calificacion_origen[0]->Nombre_afiliado)){echo $array_datos_calificacion_origen[0]->Nombre_afiliado;}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nro_identificacion">N° Identificación</label>
                                                <input type="text" class="form-control" name="nro_identificacion" id="nro_identificacion" value="<?php if(!empty($array_datos_calificacion_origen[0]->Nro_identificacion)){echo $array_datos_calificacion_origen[0]->Nro_identificacion;}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="id_evento">ID evento</span></label>
                                                <br>
                                                {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                                <a onclick="document.getElementById('botonVerEdicionEvento').click();" style="cursor:pointer; font-weight: bold;" class="btn text-info" type="button"><?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){echo $array_datos_calificacion_origen[0]->ID_evento;}?></a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Información General del Dictamen --}}
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
                                                @if (empty($datos_bd_DTO_ATEL[0]->Numero_dictamen))
                                                    <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" value="{{$numero_consecutivo}}" disabled>   
                                                @else
                                                    <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Numero_dictamen)){echo $datos_bd_DTO_ATEL[0]->Numero_dictamen;}?>" disabled>   
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="motivo_solicitud">Motivo Solicitud <span style="color:red;">(*)</span></label>
                                                <input type="hidden" id="motivo_solicitud_bd" value="<?php if(!empty($motivo_solicitud_actual[0]->Nombre_solicitud)){echo $motivo_solicitud_actual[0]->Nombre_solicitud;}?>">
                                                <select class="custom-select motivo_solicitud" name="motivo_solicitud" id="motivo_solicitud" required></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="nombre_apoderado">Nombre apoderado</label>
                                                <input type="text" class="form-control" name="nombre_apoderado" id="nombre_apoderado" value="<?php if(!empty($datos_apoderado_actual[0]->Nombre_apoderado)){echo $datos_apoderado_actual[0]->Nombre_apoderado;}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="identificacion_apoderado">N° identificación apoderado</label>
                                                <input type="text" class="form-control" name="identificacion_apoderado" id="identificacion_apoderado" value="<?php if(!empty($datos_apoderado_actual[0]->Nro_identificacion_apoderado)){echo $datos_apoderado_actual[0]->Nro_identificacion_apoderado;}?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Información laboral --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Información Laboral</h5>
                                </div>
                                <div class="card-body">
                                    <?php if(!empty($array_datos_info_laboral[0]->Tipo_empleado)):?>
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
                                                        <input type="text" class="empresa form-control" name="empresa" id="empresa" value="<?php if(!empty($array_datos_info_laboral[0]->Empresa)){ echo $array_datos_info_laboral[0]->Empresa;}?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="nit_cc" class="col-form-label">NIT / CC</label>
                                                        <input type="text" class="nit_cc form-control" name="nit_cc" id="nit_cc" value="<?php if(!empty($array_datos_info_laboral[0]->Nit_o_cc)){echo $array_datos_info_laboral[0]->Nit_o_cc;}?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="actividad_economica" class="col-form-label">Actividad económica</label>
                                                        <input type="text" class="form-control" name="act_economica" id="act_economica" value="<?php if(!empty($array_datos_info_laboral[0]->Id_actividad_economica) && !empty($array_datos_info_laboral[0]->Nombre_actividad)){echo $array_datos_info_laboral[0]->Id_actividad_economica." - ".$array_datos_info_laboral[0]->Nombre_actividad;}?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="departamento_info_laboral" class="col-form-label">Departamento</label>
                                                        <input type="hidden" name="id_departamento" id="id_departamento" value="<?php if(!empty($array_datos_info_laboral[0]->Id_departamento)){echo $array_datos_info_laboral[0]->Id_departamento;}?>">
                                                        <input type="text" class="form-control" name="nombre_departamento" id="nombre_departamento" value="<?php if(!empty($array_datos_info_laboral[0]->Nombre_departamento)){echo $array_datos_info_laboral[0]->Nombre_departamento;}?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="municipio_info_laboral" class="col-form-label">Municipio</label>
                                                        <input type="hidden" name="id_municipio" id="id_municipio" value="<?php if(!empty($array_datos_info_laboral[0]->Id_municipio)){echo $array_datos_info_laboral[0]->Id_municipio;}?>">
                                                        <input type="text" class="form-control" name="nombre_municipio" id="nombre_municipio" value="<?php if(!empty($array_datos_info_laboral[0]->Id_municipio)){echo $array_datos_info_laboral[0]->Nombre_municipio;}?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="fecha_ingreso" class="col-form-label">Fecha de ingreso</label>
                                                        <input type="date" class="form-control fecha_ingreso" name="fecha_ingreso" id="fecha_ingreso" value="<?php if(!empty($array_datos_info_laboral[0]->F_ingreso)){echo $array_datos_info_laboral[0]->F_ingreso;}?>" max="{{date("Y-m-d")}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="cargo" class="col-form-label">Cargo</span></label>
                                                        <input type="text" class="cargo form-control" name="cargo" id="cargo" value="<?php if(!empty($array_datos_info_laboral[0]->Cargo)){echo $array_datos_info_laboral[0]->Cargo;}?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="antiguedad_cargo" class="col-form-label">Antiguedad en el cargo (Meses)</label>
                                                        <input type="number" class="antiguedad_cargo form-control" name="antiguedad_cargo" id="antiguedad_cargo" value="<?php if(!empty($array_datos_info_laboral[0]->Antiguedad_cargo_empresa)){echo $array_datos_info_laboral[0]->Antiguedad_cargo_empresa;}?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="antiguedad_empresa" class="col-form-label">Antiguedad en empresa (Meses)</label>
                                                        <input type="number" class="antiguedad_empresa form-control" name="antiguedad_empresa" id="antiguedad_empresa" value="<?php if(!empty($array_datos_info_laboral[0]->Antiguedad_empresa)){echo $array_datos_info_laboral[0]->Antiguedad_empresa;}?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="descripcion" class="col-form-label">Descripción</label>
                                                        <textarea class="form-control descripcion" name="descripcion" id="descripcion" rows="2" disabled><?php if(!empty($array_datos_info_laboral[0]->Descripcion)){echo $array_datos_info_laboral[0]->Descripcion;}?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif?>
                                    <?php else:?>
                                        <div class="row text-center">
                                            <div class="col-sm">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" disabled>
                                                    <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleo Actual</strong></label>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" disabled>
                                                    <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="empresa" class="col-form-label">Empresa</label>
                                                    <input type="text" class="empresa form-control" name="empresa" id="empresa" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="nit_cc" class="col-form-label">NIT / CC</label>
                                                    <input type="text" class="nit_cc form-control" name="nit_cc" id="nit_cc" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="actividad_economica" class="col-form-label">Actividad económica</label>
                                                    <input type="text" class="form-control" name="act_economica" id="act_economica" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="departamento_info_laboral" class="col-form-label">Departamento</label>
                                                    <input type="hidden" name="id_departamento" id="id_departamento">
                                                    <input type="text" class="form-control" name="nombre_departamento" id="nombre_departamento" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="municipio_info_laboral" class="col-form-label">Municipio</label>
                                                    <input type="hidden" name="id_municipio" id="id_municipio" value="<?php if(!empty($array_datos_info_laboral[0]->Id_municipio)){echo $array_datos_info_laboral[0]->Id_municipio;}?>">
                                                    <input type="text" class="form-control" name="nombre_municipio" id="nombre_municipio" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="fecha_ingreso" class="col-form-label">Fecha de ingreso</label>
                                                    <input type="date" class="form-control fecha_ingreso" name="fecha_ingreso" id="fecha_ingreso" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="cargo" class="col-form-label">Cargo</span></label>
                                                    <input type="text" class="cargo form-control" name="cargo" id="cargo" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="antiguedad_cargo" class="col-form-label">Antiguedad en el cargo (Meses)</label>
                                                    <input type="number" class="antiguedad_cargo form-control" name="antiguedad_cargo" id="antiguedad_cargo" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="antiguedad_empresa" class="col-form-label">Antiguedad en empresa (Meses)</label>
                                                    <input type="number" class="antiguedad_empresa form-control" name="antiguedad_empresa" id="antiguedad_empresa" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="descripcion" class="col-form-label">Descripción</label>
                                                    <textarea class="form-control descripcion" name="descripcion" id="descripcion" rows="2" disabled></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif?>
                                    {{-- HISTORICO LABORAL(PARA EL FORMULARIO DE ENFERMEDAD) --}}
                                    <div class="row" id="contenedor_historico_laboral">
                                        <div class="col-12">
                                            <div class="card-info">
                                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                                    <h5>Historial Laboral</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <table id="historico_laboral" class="table table-striped table-bordered" width="100%">
                                                                    <thead>
                                                                        <tr class="bg-info">
                                                                            <th>Empresa</th>
                                                                            <th>Actividad económica</th>
                                                                            <th>Clase / Riesgo</th>
                                                                            <th>Cargo</th>
                                                                            <th>Funciones del cargo</th>
                                                                            <th>Antiguedad en la Empresa (meses)</th>
                                                                            <th>Antiguedad en el cargo (meses)</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (!empty($array_datos_historico_laboral))
                                                                            @foreach ($array_datos_historico_laboral as $datos_historico)
                                                                                <tr>
                                                                                    <td>{{$datos_historico->Empresa}}</td>
                                                                                    <td>{{$datos_historico->full_actividad_economica}}</td>
                                                                                    <td>{{$datos_historico->Nombre_riesgo}}</td>
                                                                                    <td>{{$datos_historico->Cargo}}</td>
                                                                                    <td>{{$datos_historico->Funciones_cargo}}</td>
                                                                                    <td>{{$datos_historico->Antiguedad_empresa}}</td>
                                                                                    <td>{{$datos_historico->Antiguedad_cargo_empresa}}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Información del Evento --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Información del Evento</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row" id="contenedor_forms_acci_inci_sincober">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_accidente">Tipo de accidente <span style="color:red;">(*)</span></label>
                                                <input type="hidden" id="bd_tipo_accidente" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Tipo_accidente)){echo $datos_bd_DTO_ATEL[0]->Tipo_accidente;}?>">
                                                <select class="custom-select tipo_accidente" name="tipo_accidente" id="tipo_accidente" required></select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_evento">Fecha del evento <span style="color:red;">(*)</span></label>
                                                <input type="hidden" id="bd_fecha_evento" value="">
                                                <input type="date" class="form-control" name="fecha_evento" id="fecha_evento" max="{{date("Y-m-d")}}" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Fecha_evento)){echo $datos_bd_DTO_ATEL[0]->Fecha_evento;}?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="hora_evento">Hora del evento <span style="color:red;">(*)</span></label>
                                                <input type="time" class="form-control" name="hora_evento" id="hora_evento" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Hora_evento)){echo $datos_bd_DTO_ATEL[0]->Hora_evento;}?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4" id="contenedor_grado_severidad">
                                            <div class="form-group">
                                                <label for="grado_severidad">Grado de severidad <span style="color:red;">(*)</span></label>
                                                <input type="hidden" id="bd_grado_severidad" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Grado_severidad)){echo $datos_bd_DTO_ATEL[0]->Grado_severidad;}?>">
                                                <select class="custom-select grado_severidad" name="grado_severidad" id="grado_severidad" required></select>
                                            </div>
                                        </div>
                                        <div class="col-4" id="contenedor_fecha_diagnos_enfermedad">
                                            <div class="form-group">
                                                <label for="fecha_enfermedad">Fecha Diagnostico de Enfermedad</label>
                                                <input type="date" class="form-control" name="fecha_enfermedad" id="fecha_enfermedad" max="{{date("Y-m-d")}}" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Fecha_diagnostico_enfermedad)){echo $datos_bd_DTO_ATEL[0]->Fecha_diagnostico_enfermedad;}?>">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="mortal">Mortal</label>
                                                <select class="custom-select mortal" name="mortal" id="mortal">
                                                    <option value=""></option>
                                                    <?php if(!empty($datos_bd_DTO_ATEL[0]->Mortal)):?>
                                                        <?php if($datos_bd_DTO_ATEL[0]->Mortal == "Si"):?>
                                                            <option value="Si" selected>Si</option>
                                                            <option value="No">No</option>
                                                        <?php else:?>
                                                            <option value="Si">Si</option>
                                                            <option value="No" selected>No</option>
                                                        <?php endif?>
                                                    <?php else:?>
                                                        <option value="Si">Si</option>
                                                        <option value="No">No</option>
                                                    <?php endif?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4 d-none" id="mostrar_f_fallecimiento">
                                            <div class="form-group">
                                                <label for="fecha_fallecimiento">Fecha de fallecimiento <span style="color:red;">(*)</span></label>
                                                <input type="date" class="form-control" name="fecha_fallecimiento" id="fecha_fallecimiento" max="{{date("Y-m-d")}}" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Fecha_fallecimiento)){echo $datos_bd_DTO_ATEL[0]->Fecha_fallecimiento;}?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="contenedor_descrip_FURAT">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="descripcion_FURAT" class="col-form-label">Descripción Formato Único de Reporte de presunto Accidente de Trabajo (FURAT) <span style="color:red;">(*)</span></label>
                                                <textarea class="form-control descripcion_FURAT" name="descripcion_FURAT" id="descripcion_FURAT" rows="2" required><?php if(!empty($datos_bd_DTO_ATEL[0]->Descripcion_FURAT)){echo $datos_bd_DTO_ATEL[0]->Descripcion_FURAT;}?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="factor_riesgo">Factor de riesgo</label>
                                                <input type="hidden" id="bd_factor_riesgo" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Factor_riesgo)){echo $datos_bd_DTO_ATEL[0]->Factor_riesgo;}?>">
                                                <select class="custom-select factor_riesgo" name="factor_riesgo" id="factor_riesgo"></select>
                                            </div>
                                        </div>
                                        <div class="col-4" id="contenedor_tipo_lesion">
                                            <div class="form-group">
                                                <label for="tipo_lesion">Tipo de lesión</label>
                                                <input type="hidden" id="bd_tipo_lesion" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Tipo_lesion)){echo $datos_bd_DTO_ATEL[0]->Tipo_lesion;}?>">
                                                <select class="custom-select tipo_lesion" name="tipo_lesion" id="tipo_lesion"></select>
                                            </div>
                                        </div>
                                        <div class="col-4" id="contenedor_parte_afectada">
                                            <div class="form-group">
                                                <label for="parte_cuerpo_afectada">Parte del cuerpo afectada</label>
                                                <input type="hidden" id="bd_parte_cuerpo_afectada" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Parte_cuerpo_afectada)){echo $datos_bd_DTO_ATEL[0]->Parte_cuerpo_afectada;}?>">
                                                <select class="custom-select parte_cuerpo_afectada" name="parte_cuerpo_afectada" id="parte_cuerpo_afectada"></select>
                                            </div>
                                        </div>

                                        {{-- PARTE DEL FORMULARIO DE ENFERMEDAD --}}
                                        <div class="col-4" id="contenedor_enfermedad_heredada">
                                            <div class="form-group text-center mt-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="enfermedad_heredada" name="enfermedad_heredada" value="Enfermedad Heredada"
                                                        <?php if(!empty($datos_bd_DTO_ATEL[0]->Enfermedad_heredada)):?>
                                                            <?php if($datos_bd_DTO_ATEL[0]->Enfermedad_heredada == "Si"):?>
                                                                checked
                                                            <?php endif?>
                                                        <?php endif?>
                                                    >
                                                    <label for="enfermedad_heredada" class="custom-control-label">Enfermedad heredada</label>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 d-none" id="contenedor_nombre_entidad_enfermedad_heredada">
                                            <div class="form-group">
                                                <label for="entidad_enfermedad">Nombre de la Entidad que hereda</label>
                                                <input type="text" class="form-control entidad_enfermedad" name="entidad_enfermedad" id="entidad_enfermedad" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Nombre_entidad_hereda) && !empty($datos_bd_DTO_ATEL[0]->Enfermedad_heredada) && $datos_bd_DTO_ATEL[0]->Enfermedad_heredada == "Si"){echo $datos_bd_DTO_ATEL[0]->Nombre_entidad_hereda;}?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Justificación para revisión del Origen --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Justificación para revisión del Origen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="justificacion_revision_origen" class="col-form-label">Justificación para revisión del Origen <span style="color:red;">(*)</span></label>
                                                <textarea class="form-control justificacion_revision_origen" name="justificacion_revision_origen" id="justificacion_revision_origen" rows="2" required><?php if(!empty($datos_bd_DTO_ATEL[0]->Justificacion_revision_origen)){echo $datos_bd_DTO_ATEL[0]->Justificacion_revision_origen;}?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Relación de documentos - Ayudas Diagnósticas e Interconsultas --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Relación de documentos - Ayudas Diagnósticas e Interconsultas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <h6 class="text-center"><b>Documentos tenidos en cuenta para la calificación</b></h6>
                                        </div>
                                        <?php if(!empty($datos_bd_DTO_ATEL[0]->Relacion_documentos)){$array_bd_documentos_relacion = explode(", ", $datos_bd_DTO_ATEL[0]->Relacion_documentos);}else{$array_bd_documentos_relacion= array();}?>
                                    </div>
                                    <div class="row" id="contenedor_checkboxes_acci_inci_sincober">
                                        <div class="col-4 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="furat_acci_inci_sincober" name="furat_acci_inci_sincober" value="FURAT"
                                                        <?php if(in_array("FURAT", $array_bd_documentos_relacion)):?>
                                                            checked
                                                        <?php endif?>
                                                    >
                                                    <label for="furat_acci_inci_sincober" class="custom-control-label">Formato Único de Registro de Accidente de Trabajo (FURAT)</label>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="historia_clinica_acci_inci_sincober" name="historia_clinica_acci_inci_sincober" value="Historia clínica completa"
                                                        <?php if(in_array("Historia clínica completa", $array_bd_documentos_relacion) && !empty($datos_bd_DTO_ATEL[0]->Tipo_evento) && $datos_bd_DTO_ATEL[0]->Tipo_evento != 2):?>
                                                            checked
                                                        <?php endif?>
                                                    >
                                                    <label for="historia_clinica_acci_inci_sincober" class="custom-control-label">Historia clínica completa</label> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group row">
                                                <label for="otros_acci_inci_sincober" class="col-sm-2 col-form-label">Otros</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control otros_acci_inci_sincober" name="otros_acci_inci_sincober" id="otros_acci_inci_sincober" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Otros_relacion_documentos) && !empty($datos_bd_DTO_ATEL[0]->Tipo_evento) && $datos_bd_DTO_ATEL[0]->Tipo_evento != 2){echo $datos_bd_DTO_ATEL[0]->Otros_relacion_documentos;}?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="contenedor_checkboxes_enfermedad">
                                        <div class="row">
                                            <div class="col-3 text-center">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="furel_enfermedad" name="furel_enfermedad" value="FUREL"
                                                            <?php if(in_array("FUREL", $array_bd_documentos_relacion)):?>
                                                                checked
                                                            <?php endif?>
                                                        >
                                                        <label for="furel_enfermedad" class="custom-control-label">Reporte de Formato Único para la Radicación de Enfermedad Laboral (FUREL)</label>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="historia_clinica_enfermedad" name="historia_clinica_enfermedad" value="Historia clínica completa"
                                                            <?php if(in_array("Historia clínica completa", $array_bd_documentos_relacion) && !empty($datos_bd_DTO_ATEL[0]->Tipo_evento) && $datos_bd_DTO_ATEL[0]->Tipo_evento == 2):?>
                                                                checked
                                                            <?php endif?>
                                                        >
                                                        <label for="historia_clinica_enfermedad" class="custom-control-label">Historia clínica completa</label>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="apoyo_diag_interconsulta_enfermedad" name="apoyo_diag_interconsulta_enfermedad" value="Apoyos diagnosticos e Interconsultas"
                                                            <?php if(in_array("Apoyos diagnosticos e Interconsultas", $array_bd_documentos_relacion)):?>
                                                                checked
                                                            <?php endif?>
                                                        >
                                                        <label for="apoyo_diag_interconsulta_enfermedad" class="custom-control-label">Apoyos diagnósticos e Interconsultas</label>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="analisis_puesto_trabajo_enfermedad" name="analisis_puesto_trabajo_enfermedad" value="Análisis de puesto de trabajo"
                                                            <?php if(in_array("Análisis de puesto de trabajo", $array_bd_documentos_relacion)):?>
                                                                checked
                                                            <?php endif?>
                                                        >
                                                        <label for="analisis_puesto_trabajo_enfermedad" class="custom-control-label">Análisis de puesto de trabajo</label>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3 text-center">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="examenes_pre_preocupacionales_enfermedad" name="examenes_pre_preocupacionales_enfermedad" value="Exámenes pre ocupacionales"
                                                            <?php if(in_array("Exámenes pre ocupacionales", $array_bd_documentos_relacion)):?>
                                                                checked
                                                            <?php endif?>
                                                        >
                                                        <label for="examenes_pre_preocupacionales_enfermedad" class="custom-control-label">Exámenes pre ocupacionales</label>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="examenes_periodicos_preocupacionales_enfermedad" name="examenes_periodicos_preocupacionales_enfermedad" value="Exámenes periódicos ocupacionales"
                                                            <?php if(in_array("Exámenes periódicos ocupacionales", $array_bd_documentos_relacion)):?>
                                                                checked
                                                            <?php endif?>
                                                        >
                                                        <label for="examenes_periodicos_preocupacionales_enfermedad" class="custom-control-label">Exámenes periódicos ocupacionales</label>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="examenes_post_ocupacionales_enfermedad" name="examenes_post_ocupacionales_enfermedad" value="Exámenes Post-ocupacionales"
                                                            <?php if(in_array("Exámenes Post-ocupacionales", $array_bd_documentos_relacion)):?>
                                                                checked
                                                            <?php endif?>
                                                        >
                                                        <label for="examenes_post_ocupacionales_enfermedad" class="custom-control-label">Exámenes Post-ocupacionales</label>  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="form-group row">
                                                    <label for="otros_enfermedad" class="col-sm-2 col-form-label">Otros</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control otros_enfermedad" name="otros_enfermedad" id="otros_enfermedad" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Otros_relacion_documentos) && !empty($datos_bd_DTO_ATEL[0]->Tipo_evento) && $datos_bd_DTO_ATEL[0]->Tipo_evento == 2){echo $datos_bd_DTO_ATEL[0]->Otros_relacion_documentos;}?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="listado_docs_seguimiento" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>F solicitud documento</th>
                                                            <th>Documento</th>
                                                            <th>Solicitada a</th>
                                                            <th>F recepción de documento</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($listado_documentos_solicitados))
                                                            @foreach ($listado_documentos_solicitados as $documento)
                                                                <tr>
                                                                    <td>{{$documento->F_solicitud_documento}}</td>
                                                                    <td>{{$documento->Nombre_documento}}</td>
                                                                    <td>{{$documento->Nombre_solicitante}}</td>
                                                                    <td>{{$documento->F_recepcion_documento}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="No_aporta_documentos">Artículo 12</label> &nbsp;
                                                <input class="scales" type="checkbox" name="No_aporta_documentos" id="No_aporta_documentos" value="No_mas_seguimiento" style="margin-left: revert;" <?php if(!empty($dato_articulo_12[0]->Articulo_12) && $dato_articulo_12[0]->Articulo_12=='No_mas_seguimiento'){ ?> checked <?php } ?> disabled>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Exámenes e interconsultas --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Exámenes e Interconsultas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="resultado_insercion_examen" role="alert"></div>
                                            <div class="table-responsive">
                                                <table id="listado_examenes_interconsultas" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Fecha exámen e interconsulta</th>
                                                            <th>Nombre de exámen e interconsulta</th>
                                                            <th>Descripción resultado</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_examen_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($array_datos_examenes_interconsultas))
                                                            @foreach ($array_datos_examenes_interconsultas as $examenes)
                                                            <tr class="fila_examenes_{{$examenes->Id_Examenes_interconsultas}}" id="datos_examenes_interconsulta">
                                                                <td>{{$examenes->F_examen_interconsulta}}</td>
                                                                <td>{{$examenes->Nombre_examen_interconsulta}}</td>
                                                                <td>{{$examenes->Descripcion_resultado}}</td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_examen_fila_examenes_{{$examenes->Id_Examenes_interconsultas}}" data-id_fila_quitar="{{$examenes->Id_Examenes_interconsultas}}" data-clase_fila="fila_examenes_{{$examenes->Id_Examenes_interconsultas}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr>                                                        
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Diagnóstico motivo de calificación --}}
                            <div class="card-info" id="contenedor_diag_moti_califi">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Diagnóstico Motivo de Calificación</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="resultado_insercion_cie10" role="alert"></div>
                                            <div class="table-responsive">
                                                <table id="listado_diagnostico_cie10" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>CIE-10</th>
                                                            <th>Nombre CIE-10</th>
                                                            <th>Descripción complementaria del DX</th>
                                                            <th>Lateralidad Dx</th>
                                                            <th>Origen Dx</th>
                                                            <th>Dx Principal</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($array_datos_diagnostico_motcalifi))
                                                            @foreach ($array_datos_diagnostico_motcalifi as $diagnostico)
                                                            <tr class="fila_diagnosticos_{{$diagnostico->Id_Diagnosticos_motcali}}" id="datos_diagnostico">
                                                                <td>{{$diagnostico->Codigo}}</td>
                                                                <td>{{$diagnostico->Nombre_CIE10}}</td>
                                                                <td>{{$diagnostico->Deficiencia_motivo_califi_condiciones}}</td>
                                                                <td>{{$diagnostico->Nombre_parametro_lateralidad}}</td>
                                                                <td>{{$diagnostico->Nombre_parametro_origen}}</td>
                                                                <td>
                                                                    <input type="checkbox" id="checkbox_dx_principal_visual_Cie10_{{$diagnostico->Id_Diagnosticos_motcali}}" class="checkbox_dx_principal_visual_Cie10_{{$diagnostico->Id_Diagnosticos_motcali}}" data-id_fila_checkbox_dx_principal_cie10_visual="{{$diagnostico->Id_Diagnosticos_motcali}}" <?php if($diagnostico->Principal == "Si"):?> checked <?php endif?> style="transform: scale(1.2) !important;">
                                                                </td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_moticalifi{{$diagnostico->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnostico->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnostico->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Calificación del Origen --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Calificación del Origen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="sustentacion_califi_origen" class="col-form-label">Sustentación <span style="color:red;">(*)</span></label>
                                                <textarea class="form-control sustentacion_califi_origen" name="sustentacion_califi_origen" id="sustentacion_califi_origen" rows="2" required><?php if(!empty($datos_bd_DTO_ATEL[0]->Sustentacion)){echo $datos_bd_DTO_ATEL[0]->Sustentacion;}?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="origen_dto_atel">Origen <span style="color:red;">(*)</span></label>
                                                <input type="hidden" id="bd_origen" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Origen)){echo $datos_bd_DTO_ATEL[0]->Origen;}?>">
                                                <select class="custom-select origen_dto_atel" name="origen_dto_atel" id="origen_dto_atel" required></select>
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
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            @if (empty($datos_bd_DTO_ATEL[0]->ID_evento))
                                <input type="submit" class="btn btn-info" id="GuardarDTOATEL" name="GuardarDTOATEL" value="Guardar">    
                            @else
                                <input type="submit" class="btn btn-info" id="EditarDTOATEL" name="EditarDTOATEL" value="Actualizar">    
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row d-none" id="mostrar_mensaje_agrego_dto_atel">
                    <div  class="col-12">
                        <div class="form-group">
                            <div class="mensaje_agrego_dto_atel alert alert-success" role="alert"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- Retornar al modulo de calificacionOrigen --}}
    <form action="{{route('calificacionOrigen')}}" id="formularioEnvio" method="POST">            
        @csrf
       <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){echo $array_datos_calificacion_origen[0]->ID_evento;}?>">
       <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Asignacion)){echo $array_datos_calificacion_origen[0]->Id_Asignacion;}?>">
       <input hidden="hidden" type="text" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_proceso)){echo $array_datos_calificacion_origen[0]->Id_proceso;}?>">
       <button type="submit" id="botonEnvioVista" style="display:none !important;"></button>
   </form>

   <form action="{{route('gestionInicialEdicion')}}" id="formularioLlevarEdicionEvento" method="POST">
        @csrf
        <input type="hidden" name="bandera_buscador_dto_atel" id="bandera_buscador_dto_atel" value="desdedtoatel">
        <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){echo $array_datos_calificacion_origen[0]->ID_evento;}?>">
        <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Asignacion)){echo $array_datos_calificacion_origen[0]->Id_Asignacion;}?>">
        <input hidden="hidden" type="text" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_proceso)){ echo $array_datos_calificacion_origen[0]->Id_proceso;}?>">
    <button type="submit" id="botonVerEdicionEvento" style="display:none !important;"></button>
   </form>

@stop

@section('js')

    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
    <script type="text/javascript">
        document.getElementById('botonEnvioVista').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('formularioEnvio').submit();
        });

        document.getElementById('botonVerEdicionEvento').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('formularioLlevarEdicionEvento').submit();
        });

        $(".centrar").css('text-align', 'center');
        $(document).on('mouseover',"input[id^='edit_evento_']", function(){
            let url_editar_evento = $('#para_ver_edicion_evento').val();
            $("form[id^='form_DTO_ATEL']").attr("action", url_editar_evento);    

            $("#es_activo").removeAttr('required');
            $("#tipo_evento").removeAttr('required');
            $("#tipo_accidente").removeAttr('required');
            $("#fecha_evento").removeAttr('required');
            $("#hora_evento").removeAttr('required');
            $("#grado_severidad").removeAttr('required');
            $("#descripcion_FURAT").removeAttr('required');
            $("#justificacion_revision_origen").removeAttr('required');
            $("#sustentacion_califi_origen").removeAttr('required');
            $("#origen_dto_atel").removeAttr('required');
        });

        // SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLE DE EXÁMENES E INTERCONSULTAS
        var listado_examenes_interconsultas = $('#listado_examenes_interconsultas').DataTable({
            "responsive": true,
            "info": false,
            "searching": false,
            "ordering": false,
            "scrollCollapse": true,
            "scrollY": "30vh",
            "paging": false,
            "language":{
                "emptyTable": "No se encontró información"
            }
        });

        autoAdjustColumns(listado_examenes_interconsultas);

        var contador_examen = 0;
        $('#btn_agregar_examen_fila').click(function(){
            $('#guardar_datos_examenes').removeClass('d-none');

            contador_examen = contador_examen + 1;
            var nueva_fila_examen = [
                '<input type="date" class="form-control" id="fecha_examen_fila_'+contador_examen+'" name="fecha_examen" max="{{date("Y-m-d")}}" required/>',
                '<input type="text" class="form-control" id="nombre_examen_fila_'+contador_examen+'" name="nombre_examen"/>',
                '<textarea id="descripcion_resultado_fila_'+contador_examen+'" class="form-control" name="descripcion_resultado" cols="90" rows="4"></textarea>',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_examen_fila" class="text-info" data-fila="fila_'+contador_examen+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_examen
            ];

            var agregar_examen_fila = listado_examenes_interconsultas.row.add(nueva_fila_examen).draw().node();
            $(agregar_examen_fila).addClass('fila_'+contador_examen);
            $(agregar_examen_fila).attr("id", 'fila_'+contador_examen);

        });
        
        $(document).on('click', '#btn_remover_examen_fila', function(){
            var nombre_exame_fila = $(this).data("fila");
            listado_examenes_interconsultas.row("."+nombre_exame_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_examen_fila_examenes_']", function(){
            var nombre_exame_fila = $(this).data("clase_fila");
            listado_examenes_interconsultas.row("."+nombre_exame_fila).remove().draw();
        });

        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE DIAGNOSTCO CIE10
        $(".centrar").css('text-align', 'center');
        var listado_diagnostico_cie10 = $('#listado_diagnostico_cie10').DataTable({
            "responsive": true,
            "info": false,
            "searching": false,
            "ordering": false,
            "scrollCollapse": true,
            "scrollY": "30vh",
            "paging": false,
            "language":{
                "emptyTable": "No se encontró información"
            }
        });

        autoAdjustColumns(listado_diagnostico_cie10);

        var contador_cie10 = 0;
        var array_ids_checkboxes_nuevos = [];
        $('#btn_agregar_cie10_fila').click(function(){
            $('#guardar_datos_cie10').removeClass('d-none');

            contador_cie10 = contador_cie10 + 1;
            var nueva_fila_cie10 = [
                '<select id="lista_Cie10_fila_'+contador_cie10+'" class="custom-select lista_Cie10_fila_'+contador_cie10+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie10+'" name="nombre_cie10"/>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie10+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<select id="lista_lateralidadCie10_fila_'+contador_cie10+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie10+'" name="lista_lateralidadCie10"><option></option></select>',
                '<select id="lista_origenCie10_fila_'+contador_cie10+'" class="custom-select lista_origenCie10_fila_'+contador_cie10+'" name="lista_origenCie10"><option></option></select>',
                '<input type="checkbox" id="checkbox_dx_principal_Cie10_'+contador_cie10+'" class="checkbox_dx_principal_Cie10_'+contador_cie10+'" data-id_fila_checkbox_dx_principal_Cie10="'+contador_cie10+'" style="transform: scale(1.2);">',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_cie10_fila" class="text-info" data-fila="fila_'+contador_cie10+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_cie10
            ];

            var agregar_cie10_fila = listado_diagnostico_cie10.row.add(nueva_fila_cie10).draw().node();
            $(agregar_cie10_fila).addClass('fila_'+contador_cie10);
            $(agregar_cie10_fila).attr("id", 'fila_'+contador_cie10);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
            funciones_elementos_fila_diagnosticos(contador_cie10);
            
            array_ids_checkboxes_nuevos.push("checkbox_dx_principal_Cie10_"+contador_cie10);
            
        });
            
        $(document).on('click', '#btn_remover_cie10_fila', function(){
            var nombre_cie10_fila = $(this).data("fila");
            listado_diagnostico_cie10.row("."+nombre_cie10_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_diagnosticos_moticalifi']", function(){
            var nombre_cie10_fila = $(this).data("clase_fila");
            listado_diagnostico_cie10.row("."+nombre_cie10_fila).remove().draw();
        });
 

        setInterval(() => {
            var array_checkboxes_visuales = $('[id^="checkbox_dx_principal_visual_Cie10_"]');
            var confirmar_check_visual;
            var confirmar_check_visual1;
            if($("input[id^='checkbox_dx_principal_visual_Cie10_']").is(":checked")){
                
                array_checkboxes_visuales.each(function() {
                    var id_check_visual = $(this).attr("id");
                    if ($("#"+id_check_visual).is(":checked")) {
                        $("input[id^='checkbox_dx_principal_visual_Cie10_']").not('#' + id_check_visual).prop('disabled', true);
                    }
                    confirmar_check_visual = "Si";
                });
                
                $.each(array_ids_checkboxes_nuevos, function(index, valor) {
                    $("#"+valor).prop("disabled", true);
                });
                
            }else{
                var confirmar_nuevo_check;
                confirmar_check_visual1 = "No";

                if (confirmar_check_visual == undefined) {
                    var array_checkboxes_visuales = $('[id^="checkbox_dx_principal_visual_Cie10_"]');
                    array_checkboxes_visuales.each(function() {
                        var id_check_visual = $(this).attr("id");
                        $("input[id^='checkbox_dx_principal_visual_Cie10_']").not('#' + id_check_visual).prop('disabled', false);
                    });
                }
                
                $.each(array_ids_checkboxes_nuevos, function(index, value) {
                   if ($("#"+value).is(':checked')) {

                    // $("input[id^='checkbox_dx_principal_visual_Cie10_']").prop("disabled", true);

                    array_checkboxes_visuales.each(function() {
                        var id_check_visual = $(this).attr("id");
                        
                        $("#"+id_check_visual).prop('disabled', true);
                    });

                    $.each(array_ids_checkboxes_nuevos, function(index, value2) {
                        if (value != value2) {
                            $("#"+value2).prop("disabled", true);
                        }
                        confirmar_nuevo_check = "Si";
                    });

                   }else{
                    if (confirmar_nuevo_check == undefined) {
                        // $("input[id^='checkbox_dx_principal_visual_Cie10_']").prop("disabled", false);

                        array_checkboxes_visuales.each(function() {
                            var id_check_visual = $(this).attr("id");
                            $("#"+id_check_visual).prop('disabled', false);
                        });

                        $.each(array_ids_checkboxes_nuevos, function(index, value3) {
                            $("#"+value3).prop("disabled", false);
                        });
                    }
                   }
                });
                    
            }
            // console.log("confirmar_check_visual: "+ confirmar_check_visual);
            // console.log("confirmar_check_visual1 "+confirmar_check_visual1);
            // console.log("confirmar_nuevo_check: "+confirmar_nuevo_check);

        }, 500);



    </script>
    <script type="text/javascript" src="/js/dto_atel.js"></script>
   
@stop