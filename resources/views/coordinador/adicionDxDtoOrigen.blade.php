@extends('adminlte::page')
@section('title', 'ADICIÓN DX DTO')

@section('css')
    <link rel="stylesheet" type="text/css" href="/plugins/summernote/summernote.min.css">
@stop
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
            <?php 
                $dato_rol=$captura_id_rol = session('id_cambio_rol');
            ?>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-8">
            <div>
                <?php if ($dato_rol == 7):?>
                    <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Consultar Evento</a>
                <?php else: ?>
                    <a href="{{route("bandejaOrigen")}}" class="btn btn-info" type="button"><i class="fas fa-archive"></i> Regresar Bandeja</a>
                <?php endif ?> 
                <a onclick="document.getElementById('botonEnvioVista').click();" style="cursor:pointer;" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Módulo Origen</a>
                <p>
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                </p>
            </div>
        </div>
    </div>
    <?php if($bandera_hay_dto === "no_hay_dto_atel"):?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede crear una Adición DX debido a que primero
                    debe existir una Determinación para el evento: {{$array_datos_calificacion_origen[0]->ID_evento}}
                </div>
            </div>
        </div>
    <?php else:?>
        <div class="card-info" style="border: 1px solid black;">
            <div class="card-header text-center">
                <h4>Origen ATEL - Evento: {{$array_datos_calificacion_origen[0]->ID_evento}}</h4>
                <h5 style="font-style: italic;">Adición DX</h5>
                <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
                <input type="hidden" name="NombreUsuario" id="NombreUsuario" value="{{$user->name}}">
                <input type="hidden" id="para_ver_edicion_evento" value="{{ route('gestionInicialEdicion') }}">
                <input type="hidden" name="Id_Evento" id="Id_Evento" value="<?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){ echo $array_datos_calificacion_origen[0]->ID_evento;}?>">
                {{-- <input type="hidden" name="Id_Asignacion_dto_atel" id="Id_Asignacion_dto_atel" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Id_Asignacion)){echo $datos_bd_DTO_ATEL[0]->Id_Asignacion;}?>"> --}}
                {{-- <input type="hidden" name="Id_Proceso_dto_atel" id="Id_Proceso_dto_atel" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_proceso)){echo $array_datos_calificacion_origen[0]->Id_proceso;}?>"> --}}
                <input type="hidden" id="id_dto_atel" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Id_Dto_ATEL)){echo $datos_bd_DTO_ATEL[0]->Id_Dto_ATEL;}?>">
                <input type="hidden" id="nombre_evento_gestion_edicion" value="<?php if(!empty($array_datos_calificacion_origen[0]->Nombre_evento)){echo $array_datos_calificacion_origen[0]->Nombre_evento;}?>">


                <input type="hidden" name="id_adicion_dx" id="id_adicion_dx" value="<?php if(!empty($info_adicion_dx[0]->Id_Adiciones_Dx)){echo $info_adicion_dx[0]->Id_Adiciones_Dx;}?>">
                <input type="hidden" name="Id_Asignacion_adicion_dx" id="Id_Asignacion_adicion_dx" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Asignacion)){echo $array_datos_calificacion_origen[0]->Id_Asignacion;}?>">
                <input type="hidden" name="Id_Proceso_adicion_dx" id="Id_Proceso_adicion_dx" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_proceso)){echo $array_datos_calificacion_origen[0]->Id_proceso;}?>">

            </div>
        {{-- </div> --}}
        <?php if($bandera_tipo_evento === "tipo_evento_incorrecto"):?>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede crear una Adición DX debido a que el tipo
                        de evento es: {{$nombre_del_evento_guardado}}.
                    </div>
                </div>
            </div>
        <?php else:?>
            <form method="POST" id="form_Adicion_Dx">
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
                                                    <?php if(!empty($info_adicion_dx[0]->Activo)):?>
                                                        <?php if(!empty($info_adicion_dx[0]->Activo)):?>
                                                            <?php if($info_adicion_dx[0]->Activo == "Si"):?>
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
                                                    <?php else:?>
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
                                                    <?php endif?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">Tipo de Evento <span style="color:red;">(*)</span></label>
                                                <input type="hidden" id="nombre_evento_guardado" value="{{$nombre_del_evento_guardado}}">
                                                <?php if(!empty($info_adicion_dx[0]->Tipo_evento)):?>
                                                    <input type="hidden" id="bd_tipo_evento" value="<?php if(!empty($info_adicion_dx[0]->Tipo_evento)){echo $info_adicion_dx[0]->Tipo_evento;}?>">
                                                <?php else:?>
                                                    <input type="hidden" id="bd_tipo_evento" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Tipo_evento)){echo $datos_bd_DTO_ATEL[0]->Tipo_evento;}?>">
                                                <?php endif?>
                                                <select class="custom-select tipo_evento" name="tipo_evento" id="tipo_evento" disabled required></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="mostrar_ocultar_formularios">
                                {{-- Información del afiliado --}}
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información del afiliado</h5>
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
                                                    <input type="text" class="form-control" name="fecha_dictamen" id="fecha_dictamen" value="{{$array_datos_calificacion_origen[0]->F_registro_asignacion}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="nro_dictamen">Dictamen N°</label>
                                                    <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" value="<?php if(!empty($array_datos_calificacion_origen[0]->Consecutivo_dictamen)){echo $array_datos_calificacion_origen[0]->Consecutivo_dictamen;}?>" disabled>   
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
                                                            <textarea class="form-control descripcion" name="descripcion" id="descripcion" rows="2" disabled><?php if(!empty($array_datos_info_laboral[0]->Funciones_cargo)){echo $array_datos_info_laboral[0]->Funciones_cargo;}?></textarea>
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
                                                    <label for="tipo_accidente">Tipo de accidente</label>
                                                    <input type="text" class="form-control" id="tipo_accidente" value="<?php if(!empty($nombre_tipo_accidente)){echo $nombre_tipo_accidente;}?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="fecha_evento">Fecha del evento</label>
                                                    <input type="date" class="form-control" name="fecha_evento" id="fecha_evento" max="{{date("Y-m-d")}}" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Fecha_evento)){echo $datos_bd_DTO_ATEL[0]->Fecha_evento;}?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="hora_evento">Hora del evento</label>
                                                    <input type="time" class="form-control" name="hora_evento" id="hora_evento" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Hora_evento)){echo $datos_bd_DTO_ATEL[0]->Hora_evento;}?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4" id="contenedor_grado_severidad">
                                                <div class="form-group">
                                                    <label for="grado_severidad">Grado de severidad</label>
                                                    <input type="text" class="form-control" id="grado_severidad" value="<?php if(!empty($nombre_grado_severidad)){echo $nombre_grado_severidad;}?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="mortal">Mortal</label>
                                                    <input type="text" class="form-control" id="mortal" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Mortal)){echo $datos_bd_DTO_ATEL[0]->Mortal;}?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4 d-none" id="mostrar_f_fallecimiento">
                                                <div class="form-group">
                                                    <label for="fecha_fallecimiento">Fecha de fallecimiento</label>
                                                    <input type="date" class="form-control" name="fecha_fallecimiento" id="fecha_fallecimiento" max="{{date("Y-m-d")}}" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Fecha_fallecimiento)){echo $datos_bd_DTO_ATEL[0]->Fecha_fallecimiento;}?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="contenedor_descrip_FURAT">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="descripcion_FURAT" class="col-form-label">Descripción Formato Único de Reporte de presunto Accidente de Trabajo (FURAT)</label>
                                                    <textarea class="form-control descripcion_FURAT" name="descripcion_FURAT" id="descripcion_FURAT" rows="2" disabled><?php if(!empty($datos_bd_DTO_ATEL[0]->Descripcion_FURAT)){echo $datos_bd_DTO_ATEL[0]->Descripcion_FURAT;}?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="factor_riesgo">Factor de riesgo</label>
                                                    <input type="text" class="form-control" id="factor_riesgo" value="<?php if(!empty($nombre_factor_riesgo)){echo $nombre_factor_riesgo;}?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4" id="contenedor_tipo_lesion">
                                                <div class="form-group">
                                                    <label for="tipo_lesion">Tipo de lesión</label>
                                                    <input type="text" class="form-control" id="tipo_lesion" value="<?php if(!empty($nombre_tipo_lesion)){echo $nombre_tipo_lesion;}?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4" id="contenedor_parte_afectada">
                                                <div class="form-group">
                                                    <label for="parte_cuerpo_afectada">Parte del cuerpo afectada</label>
                                                    <input type="text" class="form-control" id="parte_cuerpo_afectada" value="<?php if(!empty($nombre_parte_cuerpo_afectada)){echo $nombre_parte_cuerpo_afectada;}?>" disabled>
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
                                                    <label for="justificacion_revision_origen" class="col-form-label">Justificación para revisión del Origen</label>
                                                    <textarea class="form-control justificacion_revision_origen" name="justificacion_revision_origen" id="justificacion_revision_origen" rows="2" disabled><?php if(!empty($datos_bd_DTO_ATEL[0]->Justificacion_revision_origen)){echo $datos_bd_DTO_ATEL[0]->Justificacion_revision_origen;}?></textarea>
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
                                            <?php 
                                                if (!empty($info_adicion_dx[0]->Relacion_documentos)) {
                                                    $array_bd_documentos_relacion = explode(", ", $info_adicion_dx[0]->Relacion_documentos);
                                                } else {
                                                    $array_bd_documentos_relacion= array();
                                                    if(!empty($datos_bd_DTO_ATEL[0]->Relacion_documentos)){
                                                        $array_bd_documentos_relacion = explode(", ", $datos_bd_DTO_ATEL[0]->Relacion_documentos);
                                                    }else{
                                                        $array_bd_documentos_relacion= array();
                                                    }
                                                }
                                            ?>
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
                                                    <label for="otros_docs" class="col-sm-2 col-form-label">Otros</label>
                                                    <div class="col-sm-10">
                                                        <?php if(!empty($info_adicion_dx[0]->Otros_relacion_documentos)):?>
                                                            <input type="text" class="form-control otros_docs" name="otros_docs" id="otros_docs" value="<?php if(!empty($info_adicion_dx[0]->Otros_relacion_documentos)){echo $info_adicion_dx[0]->Otros_relacion_documentos;}?>">
                                                        <?php else:?>
                                                            <input type="text" class="form-control otros_docs" name="otros_docs" id="otros_docs" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Otros_relacion_documentos)){echo $datos_bd_DTO_ATEL[0]->Otros_relacion_documentos;}?>">
                                                        <?php endif?>
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
                                    {{-- <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Exámenes e interconsultas</h5>
                                    </div> --}}
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
                                                                <th>Fecha del Documento</th>
                                                                <th>Nombre del Documento</th>
                                                                <th>Descripción del Documento</th>
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
                                                                        <?php if(!empty($info_adicion_dx[0]->Id_Asignacion)):?>
                                                                            <?php if($info_adicion_dx[0]->Id_Asignacion == $examenes->Id_Asignacion):?>
                                                                                <div class="centrar"><a href="javascript:void(0);" id="btn_remover_examen_fila_examenes_{{$examenes->Id_Examenes_interconsultas}}" data-id_fila_quitar="{{$examenes->Id_Examenes_interconsultas}}" data-clase_fila="fila_examenes_{{$examenes->Id_Examenes_interconsultas}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                            <?php else:?>
                                                                                <div class="centrar">-</div>
                                                                            <?php endif?>
                                                                        <?php endif?>
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
                                                {{-- <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                    que diligencie en su totalidad los campos.
                                                </div> --}}
                                                <div class="alert d-none" id="resultado_insercion_cie10" role="alert"></div>
                                                <div class="table-responsive">
                                                    <table id="listado_diagnostico_cie10_visual" class="table table-striped table-bordered" width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>CIE-10</th>
                                                                <th>Nombre CIE-10</th>
                                                                <th>Descripción complementaria del DX</th>
                                                                <th>Lateralidad Dx</th>
                                                                <th>Origen Dx</th>
                                                                <th>Dx Principal</th>
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
                                                                        <input type="checkbox" id="checkbox_dx_principal_visual_Cie10_{{$diagnostico->Id_Diagnosticos_motcali}}" class="checkbox_dx_principal_visual_Cie10_{{$diagnostico->Id_Diagnosticos_motcali}}" 
                                                                        data-id_fila_checkbox_dx_principal_cie10_visual="{{$diagnostico->Id_Diagnosticos_motcali}}" 
                                                                        data-id_asig_checkbox_dx_principal_cie10_visual="{{$diagnostico->Id_Asignacion}}" 
                                                                        data-id_proce_checkbox_dx_principal_cie10_visual="{{$diagnostico->Id_proceso}}" <?php if($diagnostico->Principal == "Si"):?> checked <?php endif?> style="transform: scale(1.2) !important;">
                                                                    </td>
                                                                </tr> 
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="sustentacion_califi_origen" class="col-form-label">Sustentación</label>
                                                    <textarea class="form-control sustentacion_califi_origen" name="sustentacion_califi_origen" id="sustentacion_califi_origen" rows="2" disabled><?php if(!empty($datos_bd_DTO_ATEL[0]->Sustentacion)){echo $datos_bd_DTO_ATEL[0]->Sustentacion;}?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Diagnósticos adicionados --}}
                                <div class="card-info" id="contenedor_diag_moti_califi_adicional">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Diagnósticos Adicionados</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                    que diligencie en su totalidad los campos.
                                                </div>
                                                <!-- <div class="alert d-none" id="resultado_insercion_cie10" role="alert"></div> -->
                                                <div class="table-responsive">
                                                    <table id="listado_diagnostico_cie10" class="table table-striped table-bordered" width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Fecha Adición Dx</th>
                                                                <th style="width: 140px !important;">CIE-10</th>
                                                                <th>Nombre CIE-10</th>
                                                                <th>Descripción complementaria del DX</th>
                                                                <th>Lateralidad Dx</th>
                                                                <th>Origen Dx</th>
                                                                <th>Dx Principal</th>
                                                                <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($array_datos_diagnostico_adicionales))
                                                                @foreach ($array_datos_diagnostico_adicionales as $diagnosticos_adicionales)
                                                                <tr class="fila_diagnosticos_{{$diagnosticos_adicionales->Id_Diagnosticos_motcali}}" id="datos_diagnostico">
                                                                    <td>{{$diagnosticos_adicionales->F_adicion_CIE10}}</td>
                                                                    <td>{{$diagnosticos_adicionales->Codigo}}</td>
                                                                    <td>{{$diagnosticos_adicionales->Nombre_CIE10}}</td>
                                                                    <td>{{$diagnosticos_adicionales->Deficiencia_motivo_califi_condiciones}}</td>
                                                                    <td>{{$diagnosticos_adicionales->Nombre_parametro_lateralidad}}</td>
                                                                    <td>{{$diagnosticos_adicionales->Nombre_parametro_origen}}</td>
                                                                    <td>
                                                                        <input type="checkbox" id="checkbox_dx_principal_visual_Cie10_{{$diagnosticos_adicionales->Id_Diagnosticos_motcali}}" class="checkbox_dx_principal_visual_Cie10_{{$diagnosticos_adicionales->Id_Diagnosticos_motcali}}"
                                                                        data-id_fila_checkbox_dx_principal_cie10_visual="{{$diagnosticos_adicionales->Id_Diagnosticos_motcali}}"
                                                                        data-id_asig_checkbox_dx_principal_cie10_visual="{{$diagnosticos_adicionales->Id_Asignacion}}" 
                                                                        data-id_proce_checkbox_dx_principal_cie10_visual="{{$diagnosticos_adicionales->Id_proceso}}" <?php if($diagnosticos_adicionales->Principal == "Si"):?> checked <?php endif?> style="transform: scale(1.2) !important;">
                                                                    </td>
                                                                    <td>
                                                                        <?php if($diagnosticos_adicionales->F_adicion_CIE10 == date("Y-m-d")):?>
                                                                            <div class="centrar"><a href="javascript:void(0);" id="btn_remover_diagnosticos_moticalifi{{$diagnosticos_adicionales->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnosticos_adicionales->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnosticos_adicionales->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                        <?php else: ?>
                                                                            <div class="centrar">-</div>
                                                                        <?php endif?>
                                                                    </td>
                                                                </tr> 
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="sustentacion_adicion_dx" class="col-form-label">Sustentación Adición DX <span style="color:red;">(*)</span></label>
                                                    <textarea class="form-control sustentacion_adicion_dx" name="sustentacion_adicion_dx" id="sustentacion_adicion_dx" rows="2" required><?php if(!empty($info_adicion_dx[0]->Sustentacion_Adicion_Dx)){echo $info_adicion_dx[0]->Sustentacion_Adicion_Dx;}?></textarea>
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
                                        {{-- <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="sustentacion_califi_origen" class="col-form-label">Sustentación</label>
                                                    <textarea class="form-control sustentacion_califi_origen" name="sustentacion_califi_origen" id="sustentacion_califi_origen" rows="2" disabled><?php if(!empty($datos_bd_DTO_ATEL[0]->Sustentacion)){echo $datos_bd_DTO_ATEL[0]->Sustentacion;}?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="sustentacion_adicion_dx" class="col-form-label">Sustentación Adición DX <span style="color:red;">(*)</span></label>
                                                    <textarea class="form-control sustentacion_adicion_dx" name="sustentacion_adicion_dx" id="sustentacion_adicion_dx" rows="2" required><?php if(!empty($info_adicion_dx[0]->Sustentacion_Adicion_Dx)){echo $info_adicion_dx[0]->Sustentacion_Adicion_Dx;}?></textarea>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    @if(!empty($info_adicion_dx[0]->N_radicado))                                                           
                                                        <input type="hidden" class="form-control" name="radicado_dictamen" id="radicado_dictamen" value="{{$info_adicion_dx[0]->N_radicado}}" disabled>                                                
                                                    @else
                                                        <input type="hidden" class="form-control" name="radicado_dictamen" id="radicado_dictamen" value="{{$consecutivo}}" disabled> 
                                                    @endif
                                                    <label for="origen_dto_atel">Origen <span style="color:red;">(*)</span></label>
                                                    <?php if(!empty($info_adicion_dx[0]->Origen)):?>
                                                        <input type="hidden" id="bd_origen" value="<?php if(!empty($info_adicion_dx[0]->Origen)){echo $info_adicion_dx[0]->Origen;}?>">
                                                    <?php else:?>
                                                        <input type="hidden" id="bd_origen" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Origen)){echo $datos_bd_DTO_ATEL[0]->Origen;}?>">
                                                    <?php endif?>
                                                    <select class="custom-select origen_dto_atel" name="origen_dto_atel" id="origen_dto_atel" required></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                {{-- <?php if(!empty($info_adicion_dx[0]->Tipo_evento) && $info_adicion_dx[0]->Tipo_evento != 4):?>
                                <?php else: ?>
                                <?php endif?> --}}
                                <?php if(empty($info_adicion_dx[0]->ID_evento)):?>
                                    <input type="submit" class="btn btn-info" id="GuardarAdicionDx" name="GuardarAdicionDx" value="Guardar">
                                <?php else:?>
                                    <input type="submit" class="btn btn-info" id="ActualizarAdicionDx" name="ActualizarAdicionDx" value="Actualizar">
                                <?php endif?>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none" id="mostrar_mensaje_agrego_adicion_dx">
                        <div  class="col-12">
                            <div class="form-group">
                                <div class="mensaje_agrego_adicion_dx alert alert-success" role="alert"></div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </form>
            <div class="card-body">
                <!-- Comite Interdisciplinario -->                    
            <div class="card-info d-none" id="div_comite_interdisciplinario">
                <div class="card-header text-center" style="border: 1.5px solid black;">
                    <h5>Comité Interdisciplinario</h5>
                    <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
                </div>
                <form id="form_comite_interdisciplinario" action="POST">                            
                    <div class="card-body">
                        <div class="row">   
                            <div class="col-1">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">                                                
                                        @if(!empty($array_comite_interdisciplinario[0]->Visar))
                                            <input type="checkbox" class="custom-control-input" name="visar" id="visar" value="Si" checked disabled>                                                
                                        @else
                                            <input type="checkbox" class="custom-control-input" name="visar" id="visar" value="Si" required>                                                
                                        @endif
                                        <label for="visar" class="custom-control-label">Visar<span style="color: red;">(*)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="profesional_comite">Profesional comité</label>                                                                                           
                                    @if(!empty($array_comite_interdisciplinario[0]->Profesional_comite))
                                        <input type="text" class="form-control" name="profesional_comite" id="profesional_comite" value="{{$array_comite_interdisciplinario[0]->Profesional_comite}}" disabled>                                                
                                    @else
                                        <input type="text" class="form-control" name="profesional_comite" id="profesional_comite" disabled>                                                
                                    @endif
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="f_visado_comite">Fecha de visado comité</label>                                                                                          
                                    @if(!empty($array_comite_interdisciplinario[0]->F_visado_comite))
                                        <input type="date" class="form-control" name="f_visado_comite" id="f_visado_comite" value="{{$array_comite_interdisciplinario[0]->F_visado_comite}}" disabled>                                                
                                    @else
                                        <input type="date" class="form-control" name="f_visado_comite" id="f_visado_comite" value="{{now()->format('Y-m-d')}}"  disabled>                                                
                                    @endif
                                </div>
                            </div>                                    
                            <div class="col-2">
                                <div class="form-group" style="padding-top: 31px;">                                             
                                    <input type="submit" id="GuardarComiteInter" name="GuardarComiteInter" class="btn btn-info" value="Guardar">                                                
                                    <input hidden="hidden" type="text" id="bandera_comiteInter" value="Guardar">                                                                                           
                                </div>
                            </div>
                            <div id="div_alerta_comiteInter" class="col-12 d-none">
                                <div class="form-group"> 
                                    <div class="alerta_comiteInter alert alert-success mt-2 mr-auto" role="alert"></div>
                                </div>
                            </div>                                    
                        </div>                                                                
                    </div>
                </form>
            </div>  
            <!--  Correspondia -->
            <div class="card-info d-none" id="div_correspondecia">
                <div class="card-header text-center" style="border: 1.5px solid black;">
                    <h5>Correspondencia</h5>
                </div>
                <br>
                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para mostrar todo el cuerpo del comunicado (dentro del pdf) que usted escriba, 
                    debe incluir las etiquetas de Nombre afiliado y Origen de evento dentro de la sección Cuerpo del Comunicado.
                </div>
                <form id="form_correspondencia" action="POST">                            
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Oficio_Origen) && $array_comite_interdisciplinario[0]->Oficio_Origen == 'Si')
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="oficio_origen" name="oficio_origen" value="Si" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="oficio_origen" name="oficio_origen" value="Si">                                                    
                                        @endif
                                        <label for="oficio_origen" class="custom-control-label">Oficio Origen</label>
                                    </div>
                                </div>
                            </div> 
                            {{-- <div class="col-3">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Oficio_incapacidad) && $array_comite_interdisciplinario[0]->Oficio_incapacidad == 'Si')
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si">                                                    
                                        @endif
                                        <label for="oficioinca" class="custom-control-label">Oficio Incapacidad</label>
                                    </div>
                                </div>
                            </div> --}} 
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="destinatario_principal">Destinatario Principal</label>                                            
                                    <input type="text" class="form-control" name="destinatario_principal" id="destinatario_principal" value="{{$array_datos_calificacion_origen[0]->Nombre_afiliado}}" disabled>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group text-center">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Otro_destinatario))
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="otrodestinariop" name="otrodestinariop" value="Si" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="otrodestinariop" name="otrodestinariop" value="Si">                                                    
                                        @endif
                                        <label for="otrodestinariop" class="custom-control-label">Otro Destinatario Principal</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3" id=div_tipo_destinatario_principal>
                                <div class="form-group">
                                    <label for="tipo_destinatario_principal">Tipo Destinatario Principal<span style="color: red;">(*)</span></label>
                                    <input type="hidden" id="db_tipo_destinatario_principal" value="<?php if(!empty($array_comite_interdisciplinario[0]->Tipo_destinatario)){ echo $array_comite_interdisciplinario[0]->Tipo_destinatario;}?>">                                                
                                    <select class="tipo_destinatario_principal custom-select" name="tipo_destinatario_principal" id="tipo_destinatario_principal" style="width: 100%">                                                    
                                    </select>                                                
                                </div>
                            </div>
                            <div class="col-3" id="div_nombre_destinatariopri">
                                <div class="form-group">
                                    <label for="nombre_destinatariopri">Nombre del destinatario principal<span style="color: red;">(*)</span></label>
                                    <input type="hidden" id="db_nombre_destinatariopri" value="<?php if(!empty($array_comite_interdisciplinario[0]->Nombre_dest_principal)){ echo $array_comite_interdisciplinario[0]->Nombre_dest_principal;}?>">                                                                                                
                                    <select class="nombre_destinatariopri custom-select" name="nombre_destinatariopri" id="nombre_destinatariopri" style="width: 100%">                                                    
                                    </select>                                                
                                </div>      
                            </div>
                            <div class="col-3" id="div_nombre_destinatariopri_afi_">
                                <div class="form-group">
                                    <label for="nombre_destinatario_afi">Nombre del destinatario principal<span style="color: red;">(*)</span></label>
                                    <input type="text" class="form-control" name="nombre_destinatario_afi" id="nombre_destinatario_afi" value="{{$array_datos_calificacion_origen[0]->Nombre_afiliado}}" disabled>
                                </div>      
                            </div>
                            <div class="col-3" id="div_nombre_destinatariopri_empl">
                                <div class="form-group">
                                    <label for="nombre_destinatario_emp">Nombre del destinatario principal<span style="color: red;">(*)</span></label>
                                    <input type="text" class="form-control" name="nombre_destinatario_emp" id="nombre_destinatario_emp" value="{{$array_datos_calificacion_origen[0]->Empleador_afi}}" disabled>
                                </div>      
                            </div>
                        </div>
                        <div class="row" id="div_datos_otro_destinatario">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="nombre_destinatario">Nombre destinatario</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Nombre_destinatario))
                                        <input type="text" class="form-control" name="nombre_destinatario" id="nombre_destinatario" value="{{$array_comite_interdisciplinario[0]->Nombre_destinatario}}" >                                                
                                    @else
                                        <input type="text" class="form-control" name="nombre_destinatario" id="nombre_destinatario" >                                                
                                    @endif
                                </div>      
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="nitcc_destinatario">NIT / CC</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Nit_cc))
                                        <input type="text" class="form-control" name="nitcc_destinatario" id="nitcc_destinatario" value="{{$array_comite_interdisciplinario[0]->Nit_cc}}" >                                                
                                    @else
                                        <input type="text" class="form-control" name="nitcc_destinatario" id="nitcc_destinatario" >                                                
                                    @endif
                                </div>      
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="direccion_destinatario">Dirección destinatario</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Direccion_destinatario))
                                        <input type="text" class="form-control" name="direccion_destinatario" id="direccion_destinatario" value="{{$array_comite_interdisciplinario[0]->Direccion_destinatario}}" >                                                
                                    @else
                                        <input type="text" class="form-control" name="direccion_destinatario" id="direccion_destinatario" >                                                
                                    @endif
                                </div>      
                            </div>                                    
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="telefono_destinatario">Teléfono destinatario</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Telefono_destinatario))
                                        <input type="text" class="form-control" name="telefono_destinatario" id="telefono_destinatario" value="{{$array_comite_interdisciplinario[0]->Telefono_destinatario}}" >                                                
                                    @else
                                        <input type="text" class="form-control" name="telefono_destinatario" id="telefono_destinatario" >                                                
                                    @endif
                                </div>      
                            </div>
                        
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="email_destinatario">E-mail destinatario</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Email_destinatario))                                                    
                                        <input type="email" class="form-control" name="email_destinatario" id="email_destinatario" value="{{$array_comite_interdisciplinario[0]->Email_destinatario}}" >                                                
                                    @else
                                        <input type="email" class="form-control" name="email_destinatario" id="email_destinatario" >                                                
                                    @endif
                                </div>      
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="departamento_destinatario">Departamento</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Departamento_destinatario))
                                        <input type="text" class="form-control" name="departamento_destinatario" id="departamento_destinatario" value="{{$array_comite_interdisciplinario[0]->Departamento_destinatario}}" >                                                
                                    @else
                                        <input type="text" class="form-control" name="departamento_destinatario" id="departamento_destinatario" >                                                
                                    @endif
                                </div>      
                            </div>                                        
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="ciudad_destinatario">Ciudad</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Ciudad_destinatario))
                                        <input type="text" class="form-control" name="ciudad_destinatario" id="ciudad_destinatario" value="{{$array_comite_interdisciplinario[0]->Ciudad_destinatario}}" >                                                
                                    @else
                                        <input type="text" class="form-control" name="ciudad_destinatario" id="ciudad_destinatario" >                                                
                                    @endif
                                </div>      
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="Asunto">Asunto <span style="color: red;">(*)</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Asunto))
                                        <input type="text" class="form-control" name="Asunto" id="Asunto" value="{{$array_comite_interdisciplinario[0]->Asunto}}" required>                                                
                                    @else
                                        <input type="text" class="form-control" name="Asunto" id="Asunto" required>                                                
                                    @endif
                                </div>      
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="cuerpo_comunicado">Cuerpo del comunicado <span style="color: red;">(*)</label>
                                    <br>
                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_nombre_afiliado">Nombre Afiliado</button>
                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_origen_evento">Origen Evento</button>
                                    @if(!empty($array_comite_interdisciplinario[0]->Cuerpo_comunicado))
                                        <textarea class="form-control" name="cuerpo_comunicado" id="cuerpo_comunicado" required>{{$array_comite_interdisciplinario[0]->Cuerpo_comunicado}}</textarea>                                                                                                 
                                    @else
                                        <textarea class="form-control" name="cuerpo_comunicado" id="cuerpo_comunicado" required></textarea>                                                                                              
                                    @endif
                                </div>
                            </div> 
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="tipo_clasificacion">Copia a partes interesadas</label>
                                </div>
                            </div>  
                            <div class="col-3">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Copia_empleador))
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="empleador" name="empleador" value="Empleador" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="empleador" name="empleador" value="Empleador">                                                    
                                        @endif
                                        <label for="empleador" class="custom-control-label">Empleador</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Copia_eps))
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="eps" name="eps" value="EPS" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="eps" name="eps" value="EPS">                                                    
                                        @endif
                                        <label for="eps" class="custom-control-label">EPS</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Copia_afp))
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="afp" name="afp" value="AFP" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="afp" name="afp" value="AFP">                                                    
                                        @endif
                                        <label for="afp" class="custom-control-label">AFP</label>
                                    </div>
                                </div>
                            </div>   
                            <div class="col-3">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Copia_arl))
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="arl" name="arl" value="ARL" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="arl" name="arl" value="ARL">                                                    
                                        @endif
                                        <label for="arl" class="custom-control-label">ARL</label>
                                    </div>
                                </div>
                            </div>    
                            <div class="col-4">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Copia_jr))
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="jrci" name="jrci" value="jrci" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="jrci" name="jrci" value="jrci">                                                    
                                        @endif
                                        <label for="jrci" class="custom-control-label">Junta Regional de Calificación de Invalidez</label>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-4" id="div_cual">
                                <div class="form-group">
                                    <label for="cual">¿Cuál?<span style="color: red;">(*)</span></label>
                                    @if (!empty($array_comite_interdisciplinario[0]->Cual_jr))
                                        <select class="cual custom-select" name="cual" id="cual" style="width: 100%">
                                            <option value="{{$array_comite_interdisciplinario[0]->Cual_jr}}">{{$array_comite_interdisciplinario[0]->Cual_jr}}</option>
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    @else
                                        <select class="cual custom-select" name="cual" id="cual" style="width: 100%" >
                                            <option value="">Seleccione una opción</option>
                                        </select>                                                
                                    @endif
                                </div>
                            </div> 
                            <div class="col-4">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Copia_jn))
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="jnci" name="jnci" value="jnci" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="jnci" name="jnci" value="jnci">                                                    
                                        @endif
                                        <label for="jnci" class="custom-control-label">Junta Nacional de Calificación de Invalidez</label>
                                    </div>
                                </div>
                            </div> 
                        </div>   
                        <div class="row">  
                            <div class="col-1">
                                <div class="form-group">
                                    <label for="anexos">No. Anexos</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Anexos))
                                        <input type="number" class="form-control" name="anexos" id="anexos" value="{{$array_comite_interdisciplinario[0]->Anexos}}">                                                
                                    @else
                                        <input type="number" class="form-control" name="anexos" id="anexos">                                                
                                    @endif
                                </div>
                            </div>    
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="elaboro">Elaboró</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Elaboro))
                                        <input type="text" class="form-control" name="elaboro" id="elaboro" value="{{$array_comite_interdisciplinario[0]->Elaboro}}" disabled>                                                
                                    @else
                                        <input type="text" class="form-control" name="elaboro" id="elaboro" value="{{$user->name}}" disabled>                                                
                                    @endif
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="reviso">Revisó<span style="color: red;">(*)</span></label>
                                    @if (!empty($array_comite_interdisciplinario[0]->Reviso))
                                        <select class="reviso custom-select" name="reviso" id="reviso" style="width: 100%" required>
                                            <option value="{{$array_comite_interdisciplinario[0]->Reviso}}">{{$array_comite_interdisciplinario[0]->Reviso}}</option>
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    @else
                                        <select class="reviso custom-select" name="reviso" id="reviso" style="width: 100%" required>
                                            <option value="">Seleccione una opción</option>
                                        </select>                                                
                                    @endif                                            
                                </div>
                            </div>  
                            <div class="col-1">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        @if (!empty($array_comite_interdisciplinario[0]->Firmar))
                                            <input class="dependencia_justificacion custom-control-input" type="checkbox" id="firmar" name="firmar" value="Firmar" checked>
                                        @else
                                            <input class="custom-control-input" type="checkbox" id="firmar" name="firmar" value="Firmar">                                                    
                                        @endif
                                        <label for="firmar" class="custom-control-label">Firmar</label>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="ciudad">Ciudad</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->Ciudad))
                                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="{{$array_comite_interdisciplinario[0]->Ciudad}}">                                                
                                    @else
                                        <input type="text" class="form-control" name="ciudad" id="ciudad" value="Bogotá D.C">                                                
                                    @endif
                                </div>
                            </div>   
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="f_correspondencia">Fecha</label>
                                    @if(!empty($array_comite_interdisciplinario[0]->F_correspondecia))
                                        <input type="date" class="form-control" name="f_correspondencia" id="f_correspondencia" value="{{$array_comite_interdisciplinario[0]->F_correspondecia}}" disabled>
                                    @else
                                        <input type="date" class="form-control" name="f_correspondencia" id="f_correspondencia" value="{{now()->format('Y-m-d')}}" disabled>
                                    @endif
                                </div>
                            </div>  
                            <div class="col-4"> 
                                <div class="form-group">
                                    <label for="radicado">N° Radicado</span></label>
                                    @if(!empty($array_comite_interdisciplinario[0]->N_radicado))
                                        <input type="text" class="form-control" name="radicado" id="radicado" value="{{$array_comite_interdisciplinario[0]->N_radicado}}" disabled>                                                
                                    @else
                                        <input type="text" class="form-control" name="radicado" id="radicado" value="{{$consecutivo}}" disabled> 
                                    @endif
                                </div>
                            </div>                                                                                      
                        </div>                                
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">    
                                    @if (empty($array_comite_interdisciplinario[0]->N_radicado))                                                
                                        <input type="submit" id="GuardarCorrespondencia" name="GuardarCorrespondencia" class="btn btn-info" value="Guardar">                                                
                                        <input hidden="hidden" type="text" id="bandera_correspondecia_guardar_actualizar" value="Guardar">  
                                    @else
                                        <input type="submit" id="ActualizarCorrespondencia" name="ActualizarCorrespondencia" class="btn btn-info" value="Actualizar">
                                        <input hidden="hidden" type="text" id="bandera_correspondecia_guardar_actualizar" value="Actualizar">
                                    @endif                                         
                                                                                                                             
                                </div>
                            </div>
                            <div id="div_alerta_Correspondencia" class="col-12 d-none">
                                <div class="form-group"> 
                                    <div class="alerta_Correspondencia alert alert-success mt-2 mr-auto" role="alert"></div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </form>
            </div>
            <!-- Comunicados - Dictamen y Oficio remisorio -->                    
            <div class="card-info d-none" id="div_comunicado_dictamen_oficioremisorio">
                <div class="card-header text-center" style="border: 1.5px solid black;">
                    <h5>Comunicados</h5>
                </div>
                <div class="card-body">
                    <div class="row">  
                        <div class="col-12">
                            <div class="form-group">                                            
                                <div class="table-responsive">
                                    <table id="listado_comunicados_clpcl" class="table table-striped table-bordered" width="100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>N° de Radicado</th>
                                                <th>Elaboró</th>
                                                <th>Fecha de comunicado</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($array_comunicados_correspondencia as $comunicados)
                                            <tr>
                                                <td>{{$comunicados->N_radicado}}</td>
                                                <td>{{$comunicados->Elaboro}}</td>
                                                <td>{{$comunicados->F_comunicado}}</td>     
                                                @if ($comunicados->Ciudad == 'N/A')
                                                    <td>
                                                       {{-- Formulario para descargar el dml origen atel previsional (dictamen) --}}
                                                       <form id="Form_dml_origen_previsional_{{$comunicados->Id_Comunicado}}" data-tupla_comunicado="{{$comunicados->Id_Comunicado}}" method="POST">
                                                            @csrf
                                                            <div class="d-none">
                                                                {{-- Id cliente --}}
                                                                <input type="text" name="Id_cliente_{{$comunicados->Id_Comunicado}}" id="Id_cliente_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_cliente)){echo $array_datos_calificacion_origen[0]->Id_cliente;}?>">
                                                                {{-- Nro de documento --}}
                                                                <input type="text" name="num_identificacion_{{$comunicados->Id_Comunicado}}" id="num_identificacion_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Nro_identificacion)){echo $array_datos_calificacion_origen[0]->Nro_identificacion;}?>">
                                                                {{-- Nro siniestro --}}
                                                                <input type="text" name="nro_siniestro_{{$comunicados->Id_Comunicado}}" id="nro_siniestro_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){echo $array_datos_calificacion_origen[0]->ID_evento;} ?>">
                                                                {{-- Id asignacion --}}
                                                                <input type="text" name="Id_Asignacion_{{$comunicados->Id_Comunicado}}" id="Id_Asignacion_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Asignacion)){echo $array_datos_calificacion_origen[0]->Id_Asignacion;}?>">
                                                                {{-- Id proceso --}}
                                                                <input type="text" name="Id_Proceso_{{$comunicados->Id_Comunicado}}" id="Id_Proceso_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_proceso)){echo $array_datos_calificacion_origen[0]->Id_proceso;}?>">
                                                                {{-- Fecha Concepto --}}
                                                                <input type="text" name="f_dictamen_{{$comunicados->Id_Comunicado}}" id="f_dictamen_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->F_registro_asignacion)){echo $array_datos_calificacion_origen[0]->F_registro_asignacion;}?>">
                                                                {{-- Empresa --}}
                                                                <input type="text" name="empresa_laboral_{{$comunicados->Id_Comunicado}}" id="empresa_laboral_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_info_laboral[0]->Empresa)){ echo $array_datos_info_laboral[0]->Empresa;}?>">
                                                                {{-- Nit o Cc --}}
                                                                <input type="text" name="nit_cc_laboral_{{$comunicados->Id_Comunicado}}" id="nit_cc_laboral_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_info_laboral[0]->Nit_o_cc)){echo $array_datos_info_laboral[0]->Nit_o_cc;}?>">
                                                                {{-- Cargo --}}
                                                                <input type="text" name="cargo_laboral_{{$comunicados->Id_Comunicado}}" id="cargo_laboral_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_info_laboral[0]->Cargo)){echo $array_datos_info_laboral[0]->Cargo;}?>">
                                                                {{-- Antiguedad en cargo --}}
                                                                <input type="number" name="antiguedad_cargo_laboral_{{$comunicados->Id_Comunicado}}" id="antiguedad_cargo_laboral_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_info_laboral[0]->Antiguedad_cargo_empresa)){echo $array_datos_info_laboral[0]->Antiguedad_cargo_empresa;}?>">
                                                                {{-- Actividad econmica --}}
                                                                <input type="text" name="act_economica_laboral_{{$comunicados->Id_Comunicado}}" id="act_economica_laboral_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_info_laboral[0]->Id_actividad_economica) && !empty($array_datos_info_laboral[0]->Nombre_actividad)){echo $array_datos_info_laboral[0]->Id_actividad_economica." - ".$array_datos_info_laboral[0]->Nombre_actividad;}?>">
                                                                {{-- Justificación para revisión del Origen --}}
                                                                <textarea name="justificacion_revision_origen_{{$comunicados->Id_Comunicado}}" id="justificacion_revision_origen_{{$comunicados->Id_Comunicado}}" rows="2"><?php if(!empty($datos_bd_DTO_ATEL[0]->Justificacion_revision_origen)){echo $datos_bd_DTO_ATEL[0]->Justificacion_revision_origen;}?></textarea>
                                                                {{-- Tipo de evento --}}
                                                                <input type="text" name="nombre_evento_{{$comunicados->Id_Comunicado}}" id="nombre_evento_{{$comunicados->Id_Comunicado}}" value="{{$nombre_del_evento_guardado}}">
                                                                {{-- Fecha evento --}}
                                                                <input type="text" name="f_evento_{{$comunicados->Id_Comunicado}}" id="f_evento_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Fecha_evento)){echo $datos_bd_DTO_ATEL[0]->Fecha_evento;}?>">
                                                                {{-- Fecha fallecimiento --}}
                                                                <input type="text" name="f_fallecimiento_{{$comunicados->Id_Comunicado}}" id="f_fallecimiento_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($datos_bd_DTO_ATEL[0]->Fecha_fallecimiento)){echo $datos_bd_DTO_ATEL[0]->Fecha_fallecimiento;}?>">
                                                                {{-- Sustentación --}}
                                                                <textarea name="sustentacion_califi_origen_{{$comunicados->Id_Comunicado}}" id="sustentacion_califi_origen_{{$comunicados->Id_Comunicado}}" rows="2"><?php if(!empty($datos_bd_DTO_ATEL[0]->Sustentacion)){echo $datos_bd_DTO_ATEL[0]->Sustentacion;}?></textarea>
                                                            </div>
                                                            <button type="submit" id="btn_enviar_dictamen_previsional_{{$comunicados->Id_Comunicado}}" style="border: none; background:transparent;">
                                                                <i class="far fa-eye text-info"></i>
                                                            </button>
                                                        </form>
                                                    </td>                                                                
                                                @else
                                                    <td>
                                                        {{-- formulario Notificación del DML PREVISIONAL (oficio remisorio) --}}
                                                        <form id="Form_noti_dml_previsional_{{$comunicados->Id_Comunicado}}" data-tupla_comunicado="{{$comunicados->Id_Comunicado}}" method="POST">
                                                            <div class="d-none">
                                                                {{-- tupla tabla comunicados para extraer el nro radicado--}}
                                                                <input type="text" name="id_tupla_comunicado_{{$comunicados->Id_Comunicado}}" id="id_tupla_comunicado_{{$comunicados->Id_Comunicado}}" value="<?php echo $comunicados->Id_Comunicado;?>">
                                                                {{-- Id_com_inter --}}
                                                                <input type="text" name="id_com_inter_{{$comunicados->Id_Comunicado}}" id="id_com_inter_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_comite_interdisciplinario[0]->Id_com_inter)){echo $array_comite_interdisciplinario[0]->Id_com_inter;}?>">
                                                                {{-- Ciudad --}}
                                                                <input type="text" name="ciudad_{{$comunicados->Id_Comunicado}}" id="ciudad_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_comite_interdisciplinario[0]->Ciudad)){echo $array_comite_interdisciplinario[0]->Ciudad;}else{echo "Bogotá D.C.";}?>">       
                                                                {{-- Fecha --}}
                                                                <input type="date" name="fecha_{{$comunicados->Id_Comunicado}}" id="fecha_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_comite_interdisciplinario[0]->F_correspondecia)){echo $array_comite_interdisciplinario[0]->F_correspondecia;}else{echo now()->format('Y-m-d');}?>">
                                                                {{-- Asunto --}}
                                                                <input type="text" name="asunto_proforma_dml_{{$comunicados->Id_Comunicado}}" id="asunto_proforma_dml_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_comite_interdisciplinario[0]->Asunto)){echo strtoupper($array_comite_interdisciplinario[0]->Asunto);}else{echo "Sin Asunto";}?>">                                                
                                                                {{-- Tipo de documento --}}
                                                                <input type="text" name="tipo_identificacion_{{$comunicados->Id_Comunicado}}" id="tipo_identificacion_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Nombre_tipo_documento)){echo $array_datos_calificacion_origen[0]->Nombre_tipo_documento;}?>">
                                                                {{-- Nro de documento --}}
                                                                <input type="text" name="num_identificacion_{{$comunicados->Id_Comunicado}}" id="num_identificacion_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Nro_identificacion)){echo $array_datos_calificacion_origen[0]->Nro_identificacion;}?>">
                                                                {{-- Siniestro --}}
                                                                <input type="text" name="nro_siniestro_{{$comunicados->Id_Comunicado}}" id="nro_siniestro_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){echo $array_datos_calificacion_origen[0]->ID_evento;} ?>">
                                                                {{-- Nombre afiliado --}}
                                                                <input type="text" name="nombre_afiliado_{{$comunicados->Id_Comunicado}}" id="nombre_afiliado_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Nombre_afiliado)){echo $array_datos_calificacion_origen[0]->Nombre_afiliado;}?>">
                                                                {{-- Dirección afliado --}}
                                                                <input type="text" name="direccion_afiliado_{{$comunicados->Id_Comunicado}}" id="direccion_afiliado_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Direccion)){echo $array_datos_calificacion_origen[0]->Direccion;}?>">
                                                                {{-- Telefono afiliado --}}
                                                                <input type="text" name="telefono_afiliado_{{$comunicados->Id_Comunicado}}" id="telefono_afiliado_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Telefono_contacto)){echo $array_datos_calificacion_origen[0]->Telefono_contacto;}?>">
                                                                {{-- Id asignacion --}}
                                                                <input type="text" name="Id_Asignacion_consulta_{{$comunicados->Id_Comunicado}}" id="Id_Asignacion_consulta_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Asignacion)){echo $array_datos_calificacion_origen[0]->Id_Asignacion;}?>">
                                                                {{-- Id del cliente para consultar el nombre del cliente --}}
                                                                <input type="text" name="Id_cliente_firma_{{$comunicados->Id_Comunicado}}" id="Id_cliente_firma_{{$comunicados->Id_Comunicado}}" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_cliente)){echo $array_datos_calificacion_origen[0]->Id_cliente;}?>">
                                                            </div>

                                                            <button type="submit" id="enviar_form_noti_previsional_{{$comunicados->Id_Comunicado}}" style="border: none; background:transparent;">
                                                                <i class="far fa-eye text-info"></i>
                                                            </button>
                                                        </form>

                                                        <label for="editar_correspondencia"><i class="fa fa-pen text-info"></i></label>
                                                        <input class="btn btn-icon-only text-info btn-sm" id="editar_correspondencia" type="button" style="font-weight: bold;">
                                                    </td>
                                                @endif
                                            </tr>                                                        
                                            @endforeach
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
        <?php endif?>
    <?php endif?>

    {{-- Retornar al modulo de calificacionOrigen --}}
    <form action="{{route('calificacionOrigen')}}" id="formularioEnvio" method="POST">            
        @csrf
        <input type="hidden" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){echo $array_datos_calificacion_origen[0]->ID_evento;}?>">
        <input type="hidden" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Asignacion)){echo $array_datos_calificacion_origen[0]->Id_Asignacion;}?>">
        <input type="hidden" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_proceso)){echo $array_datos_calificacion_origen[0]->Id_proceso;}?>">
        <input type="hidden" name="Id_Servicio" id="Id_Servicio" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Servicio)){echo $array_datos_calificacion_origen[0]->Id_Servicio;}?>">
        <button type="submit" id="botonEnvioVista" style="display:none !important;"></button>
    </form>

    <form action="{{route('gestionInicialEdicion')}}" id="formularioLlevarEdicionEvento" method="POST">
        @csrf
        <input type="hidden" name="bandera_buscador_adicion_dx" id="bandera_buscador_adicion_dx" value="desdeadiciondx">
        <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_calificacion_origen[0]->ID_evento)){echo $array_datos_calificacion_origen[0]->ID_evento;}?>">
        <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Asignacion)){echo $array_datos_calificacion_origen[0]->Id_Asignacion;}?>">
        <input hidden="hidden" type="text" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_proceso)){ echo $array_datos_calificacion_origen[0]->Id_proceso;}?>">
        <input hidden="hidden" type="text" name="newIdservicio" id="newIdservicio" value="<?php if(!empty($array_datos_calificacion_origen[0]->Id_Servicio)){echo $array_datos_calificacion_origen[0]->Id_Servicio;}?>">
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
                '<div class="centrar"><a href="javascript:void(0);" id="btn_remover_examen_fila" class="text-info" data-fila="fila_'+contador_examen+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
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


        // DATATABLE DE DIAGNOSTICOS CIE10 VISUALES
        var listado_diagnostico_cie10_visual = $('#listado_diagnostico_cie10_visual').DataTable({
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

        autoAdjustColumns(listado_diagnostico_cie10_visual);

        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE DIAGNOSTCO CIE10 ADICIONALES
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
                '<?php echo date("Y-m-d");?> <input type="hidden" id="fecha_adicion_cie10_fila_'+contador_cie10+'" name="fecha_adicion_cie10" value="{{date("Y-m-d")}}">',
                '<select id="lista_Cie10_fila_'+contador_cie10+'" class="custom-select lista_Cie10_fila_'+contador_cie10+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie10+'" name="nombre_cie10"/>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie10+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<select id="lista_lateralidadCie10_fila_'+contador_cie10+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie10+'" name="lista_lateralidadCie10"><option></option></select>',
                '<select id="lista_origenCie10_fila_'+contador_cie10+'" class="custom-select lista_origenCie10_fila_'+contador_cie10+'" name="lista_origenCie10"><option></option></select>',
                '<input type="checkbox" id="checkbox_dx_principal_Cie10_'+contador_cie10+'" class="checkbox_dx_principal_Cie10_'+contador_cie10+'" data-id_fila_checkbox_dx_principal_Cie10="'+contador_cie10+'" style="transform: scale(1.2);">',
                '<div class="centrar"><a href="javascript:void(0);" id="btn_remover_cie10_fila" class="text-info" data-fila="fila_'+contador_cie10+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
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

                if (confirmar_check_visual == undefined) {
                    var array_checkboxes_visuales = $('[id^="checkbox_dx_principal_visual_Cie10_"]');
                    array_checkboxes_visuales.each(function() {
                        var id_check_visual = $(this).attr("id");
                        $("input[id^='checkbox_dx_principal_visual_Cie10_']").not('#' + id_check_visual).prop('disabled', false);
                    });
                }
                
                $.each(array_ids_checkboxes_nuevos, function(index, value) {
                   if ($("#"+value).is(':checked')) {

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

        }, 500);

    </script>
    <script type="text/javascript" src="/js/adicion_dx_dto.js"></script>
    <script src="/plugins/summernote/summernote.min.js"></script>
@stop