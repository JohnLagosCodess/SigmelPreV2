@extends('adminlte::page')
@section('title', 'Calificación Ténica PCL')
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
                <a href="{{route("bandejaPCL")}}" class="btn btn-info" type="button"><i class="fas fa-archive"></i> Regresar Bandeja</a>
                <a onclick="document.getElementById('botonEnvioVista').click();" style="cursor:pointer;" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Módulo PCL</a>
                <p>
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Calificación PCL - Evento: {{$array_datos_calificacionPclTecnica[0]->ID_evento}}</h4>
            <h5 style="font-style: italic;">Calificación Técnica</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <!--Calificacón PCL-->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="origen_firme">Origen en firme<span style="color: red;">(*)</span></label>
                                    <select class="custom-select origen_firme" name="origen_firme" id="origen_firme" required>
                                        @if ($datos_demos["Origen"] > 0)
                                            <option value="{{$datos_demos["Origen"]}}" selected>{{$datos_demos["NombreOrigen"]}}</option>
                                        @else
                                            <option value="">Seleccione una opción</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="origen_cobertura">Cobertura<span style="color: red;">(*)</span></label>
                                    <select class="custom-select origen_cobertura" name="origen_cobertura" id="origen_cobertura" required>
                                        @if ($datos_demos["Cobertura"] > 0)
                                            <option value="{{$datos_demos["Cobertura"]}}" selected>{{$datos_demos["NombreCobertura"]}}</option>
                                        @else
                                            <option value="">Seleccione una opción</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="decreto_califi">Decreto de Calificación<span style="color: red;">(*)</span></label>
                                    <select class="custom-select decreto_califi" name="decreto_califi" id="decreto_califi" required>
                                        @if ($datos_demos["Decreto"] > 0)
                                            <option value="{{$datos_demos["Decreto"]}}" selected>{{$datos_demos["NombreDecreto"]}}</option>
                                        @else
                                            <option value="">Seleccione una opción</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Parametro para mostrar ITEM ya gestionados -->
                    <?php
                    if($datos_demos["Origen"] ='48' && $datos_demos["Cobertura"] ='50'):
                        $decreto_1507='1';
                    else:
                        $decreto_1507='0';
                    endif
                    ?>
                    <!-- Informacion Afiliado-->
                    <div class="card-info columna_row1_afiliado" @if ($decreto_1507='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Información del afiliado</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="nombre_afiliado">Nombre de afiliado</label>
                                        <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_calificacionPclTecnica[0]->Nombre_afiliado}}" disabled>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="identificacion">N° Identificación</label>
                                        <input type="text" class="form-control" name="identificacion" id="identificacion" value="{{$array_datos_calificacionPclTecnica[0]->Nro_identificacion}}" disabled>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="id_evento">ID evento</label>
                                        <input type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_calificacionPclTecnica[0]->ID_evento}}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Informacion Ditacmen-->
                    <div class="card-info columna_row1_dictamen" @if ($decreto_1507='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Información del Dictamen</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="fecha_dictamen">Fecha Dictamen</label>
                                        <input type="text" class="form-control" name="fecha_dictamen" id="fecha_dictamen" style="color: red;" value="NO ESTA DEFINIDO BACKEND" disabled>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="numero_dictamen">N° Dictamen</label>
                                        <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" style="color: red;" value="NO ESTA DEFINIDO BACKEND" disabled>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="motivo_solicitud">Motivo Solicitud<span style="color: red;">(*)</span></label>
                                        <select class="custom-select motivo_solicitud" name="motivo_solicitud" id="motivo_solicitud" required>
                                            @if ($motivo_solicitud_actual[0]->Id_motivo_solicitud > 0)
                                                <option value="{{$motivo_solicitud_actual[0]->Id_motivo_solicitud}}" selected>{{$motivo_solicitud_actual[0]->Nombre_solicitud}}</option>
                                            @else
                                                <option value="">Seleccione una opción</option>
                                            @endif
                                        </select>
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
                    <!-- Relacion de documetos-->
                    <div class="card-info columna_row1_documentos" @if ($decreto_1507='1') style="display:block" @else style="display:none" @endif >
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Relación de documentos / Examenes fisico - (Descripción)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <h6 class="text-center"><b>Documentos tenidos en cuenta para la calificación</b><h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="hitoria_clinica" name="hitoria_clinica">
                                            <label for="hitoria_clinica" class="custom-control-label">Historia clínica completa</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="examanes_preocupacionales" name="examanes_preocupacionales">
                                            <label for="examanes_preocupacionales" class="custom-control-label">Exámenes preocupacionales</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="epicrisis" name="epicrisis">
                                            <label for="epicrisis" class="custom-control-label">Epicrisis</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="examanes_periodicos" name="examanes_periodicos">
                                            <label for="examanes_periodicos" class="custom-control-label">Examanes periódicos ocupacionales</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="examanes_paraclinicos" name="examanes_paraclinicos">
                                            <label for="examanes_paraclinicos" class="custom-control-label">Exámenes paraclinicos</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="examanes_post_ocupacionales" name="examanes_post_ocupacionales">
                                            <label for="examanes_post_ocupacionales" class="custom-control-label">Exámanes Post-ocupacionales</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="salud_ocupacionales" name="salud_ocupacionales">
                                            <label for="salud_ocupacionales" class="custom-control-label">Conceptos de salud ocupacional</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="descripcion_otros">Otros:</label>
                                        <textarea class="form-control" name="descripcion_otros" id="descripcion_otros" cols="30" rows="5" style="resize: none;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fundamentos para la calificacion-->
                    <div class="card-info columna_row1_fundamentos" @if ($decreto_1507='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Fundamentos para la calificación de la Pérdida de Capacidad Laboral y ocupacional</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="descripcion_enfermedad">Descripción de la enfermedad actual<span style="color: red;">(*)</span>:</label>
                                        <textarea class="form-control" name="descripcion_enfermedad" id="descripcion_enfermedad" cols="30" rows="5" style="resize: none;" required></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="dominancia">Dominancia</label>
                                        <input type="text" class="form-control" name="dominancia" id="dominancia" value="{{$motivo_solicitud_actual[0]->Nombre_dominancia}}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- examen interconsulta-->
                    <div class="card-info columna_row1_interconsulta" @if ($decreto_1507='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Exámanes e interconsultas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                            que diligencie en su totalidad los campos.
                                        </div>
                                        <div class="alert d-none" id="resultado_insercion_examen" role="alert">
                                        </div>
                                        <div class="table-responsive">
                                            <table id="listado_examenes_interconsultas" class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Fecha examen e interconsulta</th>
                                                        <th>Nombre de examen e interconsulta</th>
                                                        <th>Descripción resultado</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_examen_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--Traer los datos ya registrados en la BD -->
                                                </tbody>
                                            </table>
                                        </div><br>
                                        <x-adminlte-button class="mr-auto" id="guardar_datos_examenes" theme="info" label="Guardar" disabled/>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Diagnostico motivo cali-->
                    <div class="card-info columna_row1_motivo_cali" @if ($decreto_1507='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Diagnóstico motivo de calificación</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                            que diligencie en su totalidad los campos.
                                        </div>
                                        <div class="alert d-none" id="resultado_insercion_cie10" role="alert">
                                        </div>
                                        <div class="table-responsive">
                                            <table id="listado_diagnostico_cie10" class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>CIE10</th>
                                                        <th>Nombre CIE10</th>
                                                        <th>Origen CIE10</th>
                                                        <th>Deficiencia(s) motivo de la calificación/<br>Condiciones de salud</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--Traer los datos ya registrados en la BD -->
                                                </tbody>
                                            </table>
                                        </div><br>
                                        <x-adminlte-button class="mr-auto" id="guardar_datos_cie10" theme="info" label="Guardar" disabled/>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Deficiencia-->
                    <div class="card-info columna_row1_deficiencia" @if ($decreto_1507='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Titulo I Calificación / Valoración de las Deficiencias (50%)</h5>
                        </div>
                        <br>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                            que diligencie en su totalidad los campos.
                                        </div>
                                        <div class="alert d-none" id="resultado_insercion_deficiencia" role="alert">
                                        </div>
                                        <div class="table-responsive">
                                            <table id="listado_deficiencia_porfactor" class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th style="width: 140px !important;">Tabla</th>
                                                        <th style="width: 140px !important;">Titulo tabla</th>
                                                        <th style="width: 140px !important;">Clase principal<br>(FP)</th>
                                                        <th style="width: 140px !important;">CFM1</th>
                                                        <th style="width: 140px !important;">CFM2</th>
                                                        <th style="width: 140px !important;">FU</th>
                                                        <th style="width: 140px !important;">CAT</th>
                                                        <th style="width: 140px !important;">Clase final</th>
                                                        <th style="width: 140px !important;">DX<br>principal</th>
                                                        <th style="width: 140px !important;">MSD</th>
                                                        <th style="width: 140px !important;">Deficiencia</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_porfactor"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--Traer los datos ya registrados en la BD -->
                                                </tbody>
                                            </table>
                                        </div><br>
                                        <x-adminlte-button class="mr-auto d-none" id="guardar_datos_deficiencia_alteraciones" theme="info" label="Guardar"/>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-info columna_row1_auditivo" @if ($decreto_1507='1' && $datos_demos["Decreto"]<>'3') style="display:block" @else style="display:none" @endif>
                            <a href="#" id="" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modal_grilla_auditivo"><i class="fas fa-plus-circle text-info"></i> <strong>Agudeza auditiva</strong></a>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Tabla 9.3 Deficiencia por Alteraciones del Sistema Auditivo</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table></table>
                                </div>
                            </div>
                        </div>
                        <div class="card-info columna_row1_visual"  @if ($decreto_1507='1' && $datos_demos["Decreto"]<>'3') style="display:block" @else style="display:none" @endif>
                            <a href="javascript:void(0);" id="btn_abrir_modal_agudeza" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" 
                                @if (count($hay_agudeza_visual) > 0)
                                    style="cursor:not-allowed"
                                @else
                                    data-target="#modal_nueva_agudeza_visual"
                                @endif ><i class="fas fa-plus-circle text-info">
                                </i> <strong>Agudeza Visual</strong>
                            </a>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Tabla 11.3 Deficiencias por Alteraciones del Sistema Visual</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" >
                                    <table id="listado_agudeza_visual" class="table table-striped table-bordered" width="100%">
                                        <thead>
                                            <tr class="bg-info text-center">
                                                <th>Agudeza Ojo Izquierdo</th>
                                                <th>Agudeza Ojo Derecho</th>
                                                <th>Agudeza Ambos Ojos</th>
                                                <th>Puntaje de Agudeza Visual Funcional (PAVF)</th>
                                                <th>Deficiencia por Agudeza Visual (DAV)</th>
                                                <th>Campo Visual Ojo Izquierdo</th>
                                                <th>Campo Visual Ojo Derecho</th>
                                                <th>Campo Visual Ambos Ojos</th>
                                                <th>Puntaje Campo Visual Funcional (CVF)</th>
                                                <th>Deficiencia por Campo Visual (DCV)</th>
                                                <th>Deficiencia Global del Sistema Visual (DSV)</th>
                                                <th>Dx Principal</th>
                                                <th>Deficiencia</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="llenar_info_agudeza_visual">
                                            @foreach ($hay_agudeza_visual as $info_agudeza)
                                                <tr class="fila_visual_agudeza_{{$info_agudeza->Id_agudeza}}">
                                                    <td>{{$info_agudeza->Agudeza_Ojo_Izq}}</td>
                                                    <td>{{$info_agudeza->Agudeza_Ojo_Der}}</td>
                                                    <td>{{$info_agudeza->Agudeza_Ambos_Ojos}}</td>
                                                    <td>{{$info_agudeza->PAVF}}</td>
                                                    <td>{{$info_agudeza->DAV}}</td>
                                                    <td>{{$info_agudeza->Campo_Visual_Ojo_Izq}}</td>
                                                    <td>{{$info_agudeza->Campo_Visual_Ojo_Der}}</td>
                                                    <td>{{$info_agudeza->Campo_Visual_Ambos_Ojos}}</td>
                                                    <td>{{$info_agudeza->CVF}}</td>
                                                    <td>{{$info_agudeza->DCV}}</td>
                                                    <td>{{$info_agudeza->DSV}}</td>
                                                    <td><input type="checkbox" name="" id=""></td>
                                                    <td>{{$info_agudeza->Deficiencia}}</td>
                                                    <td>
                                                        <div style="text-align:center;">
                                                            <a href="javascript:void(0);" id="btn_editar_agudeza_visual" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modal_editar_agudeza_visual">
                                                                <i class="fa fa-pen text-primary"></i>
                                                            </a>
                                                            <a href="javascript:void(0);" id="btn_remover_fila_{{$info_agudeza->Id_agudeza}}" data-fila_agudeza="fila_visual_agudeza_{{$info_agudeza->Id_agudeza}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Valoracion Laboral-->
                    <div class="card-info columna_row1_valoracion_laboral"  @if ($decreto_1507='1' && $datos_demos["Decreto"]<>'3') style="display:block" @else style="display:none" @endif>
                        <div class="card-header " style="border: 1.5px solid black;">
                            <h5>Título II Valoración del Rol Laboral, Rol ocupacional y otras Áreas ocupacionales (50%)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="form-check custom-control custom-radio">
                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_laboral" id="laboral_actual" value="Laboralmente_Activo">
                                            <label class="form-check-label custom-control-label" for="laboral_actual"><strong>Laboralmente Activo</strong></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="form-check custom-control custom-radio">
                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_laboral" id="rol_ocupacional" value="Rol_ocupacional">
                                            <label class="form-check-label custom-control-label" for="rol_ocupacional"><strong>Aplicar rol ocupacional</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Mostrar si es laboralmente activo-->
                            <div class="row">
                                <!--Tabla 1 - Restricciones de rol-->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="identificacion">Tabla 1 - Restricciones de rol</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p1" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>1. Activo: Sin limitaciones</th>
                                                        <th>2. Rol laboral recortado</th>
                                                        <th>3. Rol laboral o puesto de trabajo adaptado</th>
                                                        <th>4. Cambio de rol laboral o de puesto de trabajo</th>
                                                        <th>5. Cambio de rol laboral recortado</th>
                                                        <th>6. Restricciones completas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="activo_limitaciones" value="0.0">
                                                            <label class="form-check-label custom-control-label" for="activo_limitaciones"><strong>0.0</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="laboral_recortado" value="5.0">
                                                            <label class="form-check-label custom-control-label" for="laboral_recortado"><strong>5.0</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="laboral_adaptado" value="10">
                                                            <label class="form-check-label custom-control-label" for="laboral_adaptado"><strong>10</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="laboral_cambio" value="15">
                                                            <label class="form-check-label custom-control-label" for="laboral_cambio"><strong>15</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="laboral_recortado_cambio" value="20">
                                                            <label class="form-check-label custom-control-label" for="laboral_recortado_cambio"><strong>20</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restriciones_completa" value="25">
                                                            <label class="form-check-label custom-control-label" for="restriciones_completa"><strong>25</strong></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!--Tabla 2 - Autosuficiencia económica-->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="identificacion">Tabla 2 - Autosuficiencia económica</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p2" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Autosuficiencia</th>
                                                        <th>Autosuficiencia reajustada</th>
                                                        <th>Precariamente autosuficiente</th>
                                                        <th>Económicamente débiles</th>
                                                        <th>Económicamente dependientes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia" value="0.0">
                                                            <label class="form-check-label custom-control-label" for="autosuficiencia"><strong>0.0</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_reajustada" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="autosuficiencia_reajustada"><strong>1.0</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_precaria" value="1.5">
                                                            <label class="form-check-label custom-control-label" for="autosuficiencia_precaria"><strong>1.5</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="economica_debil" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="economica_debil"><strong>2.0</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="economica_depediente" value="2.5">
                                                            <label class="form-check-label custom-control-label" for="economica_depediente"><strong>2.5</strong></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!--Tabla 3 - Edad cronológica-->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="identificacion">Tabla 3 - Edad cronológica</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p3" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Menor de 18 años</th>
                                                        <th>Mayor o igual a 18, menor de 30 años</th>
                                                        <th>Mayor o igual a 30, menor de 40 años</th>
                                                        <th>Mayor o igual a 40, menor de 50 años</th>
                                                        <th>Mayor o igual a 50, menor de 60 años</th>
                                                        <th>Mayor o igual a 60 años</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="menor_18" value="2.5">
                                                            <label class="form-check-label custom-control-label" for="menor_18"><strong>2.5</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="mayor_18_30" value="0.5">
                                                            <label class="form-check-label custom-control-label" for="mayor_18_30"><strong>0.5</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="mayor_30_40" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="mayor_30_40"><strong>1.0</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="mayor_40_50" value="1.5">
                                                            <label class="form-check-label custom-control-label" for="mayor_40_50"><strong>1.5</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="mayor_50_60" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="mayor_50_60"><strong>2.0</strong></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="mayor_60" value="2.5">
                                                            <label class="form-check-label custom-control-label" for="mayor_60"><strong>2.5</strong></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Total Rol Laboral-->
                            <div class="row" style="text-align: right">
                                <div class="col-12">
                                    <div class="form-group" style="align-content: center">
                                        <label for="total_rol">Rol Laboral (30%): 0</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Otras Areas ocupacionales-->
                            <div class="row text-center">
                                <div class="col-12">
                                    <div class="form-group">
                                        <h6><b>Otras Áreas ocupacionales</b></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!--Tabla 6  - Aprendizaje y aplicación del conocimiento-->
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tabla6">Tabla 6  - Aprendizaje y aplicación del conocimiento</label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p6" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Área ocupacional</th>
                                                        <th>0.1</th>
                                                        <th>0.2</th>
                                                        <th>0.3</th>
                                                        <th>0.4</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Mirar<br><br></strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="mirar_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="mirar_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="mirar_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="mirar_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Escuchar<br><br></strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="escuchar_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="escuchar_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="escuchar_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="escuchar_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Aprender a leer, escribir y calcular<br><br></strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="aprender_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="aprender_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="aprender_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="aprender_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Aprender a calcular<br><br></strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="calcular_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="calcular_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="calcular_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="calcular_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Pensar</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="pensar_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="pensar_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="pensar_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="pensar_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Leer</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="leer_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="leer_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="leer_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="leer_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Escribir</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="escribir_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="escribir_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="escribir_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="escribir_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Calcular usando principios matemáticos</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="matematicos_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="matematicos_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="matematicos_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="matematicos_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Resolver problemas y tomar decisiones</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="decisiones_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="decisiones_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="decisiones_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="decisiones_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Llevar a cabo tareas simples</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="tareas_simples_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="tareas_simples_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="tareas_simples_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="tareas_simples_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total: 0</label>
                                    </div>
                                </div>
                                <!--Tabla 7 - Categorías del área ocupacional de comunicación-->
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tabla7">Tabla 7 - Categorías del área ocupacional de comunicación</label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Área ocupacional</th>
                                                        <th>0.1</th>
                                                        <th>0.2</th>
                                                        <th>0.3</th>
                                                        <th>0.4</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Comunicarse con recepción de mensajes verbales</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_mensaje_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_mensaje_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_mensaje_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_mensaje_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Comunicarse con recepción de mensajes no verbales</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="no_comunicarse_mensaje_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="no_comunicarse_mensaje_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="no_comunicarse_mensaje_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="no_comunicarse_mensaje_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Comunicarse, recepción en lenguaje signos formal</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_signos_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_signos_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_signos_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_signos_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Comunicarse recepción de mensajes escritos</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_escrito_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_escrito_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_escrito_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="comunicarse_escrito_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Habla palabras, frases y párrafos</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="habla_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="habla_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="habla_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="habla_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Producción de mensajes no verbales</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="no_verbales_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="no_verbales_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="no_verbales_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="no_verbales_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Mensajes escritos</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="mensajes_escritos_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="mensajes_escritos_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="mensajes_escritos_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="mensajes_escritos_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Iniciar y sostener conversación</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="sostener_conversa_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="sostener_conversa_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="sostener_conversa_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="sostener_conversa_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Iniciar, mantener y finalizar discusiones</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="iniciar_discusiones_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="iniciar_discusiones_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="iniciar_discusiones_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="iniciar_discusiones_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Utilización dispositivos y técnicas de comunicación</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="utiliza_dispositivos_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="utiliza_dispositivos_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="utiliza_dispositivos_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="tareas_simples_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="utiliza_dispositivos_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total: 0</label>
                                    </div>
                                </div>
                                <!--Tabla 8 - Relación de categorías del área ocupacional de movilidad-->
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tabla8">Tabla 8 - Relación de categorías del área ocupacional de movilidad<br><br></label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Área ocupacional</th>
                                                        <th>0.1</th>
                                                        <th>0.2</th>
                                                        <th>0.3</th>
                                                        <th>0.4</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Cambiar posturas corporales básicas y de lugar</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="cambiar_posturas_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="cambiar_posturas_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="cambiar_posturas_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="cambiar_posturas_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Mantener la posición del cuerpo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="posicion_cuerpo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="posicion_cuerpo_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="posicion_cuerpo_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="posicion_cuerpo_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Levantar y llevar objetos</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="llevar_objetos_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="llevar_objetos_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="llevar_objetos_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="llevar_objetos_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Uso fino de la mano</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="uso_fino_mano_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="uso_fino_mano_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="uso_fino_mano_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="uso_fino_mano_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Uso de la mano y el brazo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="uso_mano_brazo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="uso_mano_brazo_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="uso_mano_brazo_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="uso_mano_brazo_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Andar y desplazarse por el entorno</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="desplazarse_entorno_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="desplazarse_entorno02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="desplazarse_entorno_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="desplazarse_entorno_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Desplazarse por distintos lugares</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="distintos_lugares_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="distintos_lugares_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="distintos_lugares_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="distintos_lugares_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Desplazarse utilizando algún tipo de equipo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="desplazarse_con_equipo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="desplazarse_con_equipo_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="desplazarse_con_equipo_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="desplazarse_con_equipo_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Utilización de transporte como pasajero</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="transporte_pasajero_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="transporte_pasajero_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="transporte_pasajero_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="transporte_pasajero_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Conducción</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="conduccion_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="conduccion_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="conduccion_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="conduccion_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total: 0</label>
                                    </div>
                                </div>
                                <!--Tabla 9 - Relación por categorías para el área ocupacional del cuidado personal-->
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tabla9">Tabla 9 - Relación por categorías para el área ocupacional del cuidado personal</label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Área ocupacional</th>
                                                        <th>0.1</th>
                                                        <th>0.2</th>
                                                        <th>0.3</th>
                                                        <th>0.4</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Lavarse<br><br></strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="lavarse_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="lavarse_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="lavarse_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="lavarse_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Cuidado de partes del cuerpo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="cuidado_cuerpo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="cuidado_cuerpo_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="cuidado_cuerpo_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="cuidado_cuerpo_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Higiene personal relacionada con procesos excreción</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="higiene_personal_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="higiene_personal_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="higiene_personal_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="higiene_personal_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Vestirse</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="vestirse_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="vestirse_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="vestirse_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="vestirse_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Quitarse la ropa</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="quitarse_ropa_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="quitarse_ropa_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="quitarse_ropa_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="quitarse_ropa_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Ponerse el calzado</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="ponerse_calzado_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="ponerse_calzado_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="ponerse_calzado_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="ponerse_calzado_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Comer</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="comer_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="comer_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="comer_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="comer_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Beber</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="beber_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="beber_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="beber_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="beber_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Cuidado de la propia salud</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="cuidado_salud_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="cuidado_salud_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="cuidado_salud_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="cuidado_salud_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Control de la dieta y la forma física<br><br></strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="control_dieta_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="control_dieta_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="control_dieta_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="control_dieta_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total: 0</label>
                                    </div>
                                </div>
                                <!--Tabla 10 - Relación por categorías para el área ocupacional del cuidado personal-->
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tabla10">Tabla 10 - Relación de las categorías para el área ocupacional de la vida doméstica</label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Área ocupacional</th>
                                                        <th>0.1</th>
                                                        <th>0.2</th>
                                                        <th>0.3</th>
                                                        <th>0.4</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Adquisición de lugar para vivir</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="lavarse_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="adquisicion_para_vivir_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="adquisicion_para_vivir_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="adquisicion_para_vivir_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Adquisición de bienes y servicios</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="bienes_servicios_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="bienes_servicios_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="bienes_servicios_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="bienes_servicios_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Comprar</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="comprar_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="comprar_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="comprar_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="comprar_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Preparar comidas</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="preparar_comida_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="preparar_comida_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="preparar_comida_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="preparar_comida_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Realizar los quehaceres de la casa</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="quehaceres_casa_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="quehaceres_casa_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="quehaceres_casa_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="quehaceres_casa_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Limpieza de la vivienda</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="limpieza_vivienda_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="limpieza_vivienda_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="limpieza_vivienda_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="limpieza_vivienda_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Cuidado de los objetos del hogar</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="objetos_hogar_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="objetos_hogar_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="objetos_hogar_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="objetos_hogar_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Ayudar a los demás</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="ayudar_los_demas_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="ayudar_los_demas_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="ayudar_los_demas_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="ayudar_los_demas_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Mantenimiento de los dispositivos de ayuda</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="mantenimiento_dispositivos_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="mantenimiento_dispositivos_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="mantenimiento_dispositivos_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="mantenimiento_dispositivos_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Cuidado de los animales</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_01" value="0.1">
                                                            <label class="form-check-label custom-control-label" for="cuidado_animales_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_02" value="0.2">
                                                            <label class="form-check-label custom-control-label" for="cuidado_animales_02"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_03" value="0.3">
                                                            <label class="form-check-label custom-control-label" for="cuidado_animales_03"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_04" value="0.4">
                                                            <label class="form-check-label custom-control-label" for="cuidado_animales_04"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total: 0</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Total de otras areas y rol laboral -->
                            <div class="row" style="text-align: right">
                                <div class="col-12">
                                    <div class="form-group" style="align-content: center">
                                        <label for="total_otras">Total otras areas  (20%): 0</label><br>
                                        <label for="total_rol_areas">Total rol laboral y otras areas  (50%): 0</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Aplicar rol ocupacional tablas -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="poblacion_califi">Población a calificar</label>
                                        <select class="custom-select" name="poblacion_califi" id="poblacion_califi" required>
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--Tabla 12 - Criterios desarrollo neuroevolutivo Niños y Niñas 0 a 3 años-->
                            <div class="row text-center">
                                <div class="col-12">
                                    <label for="tabla12">Tabla 12 - Criterios desarrollo neuroevolutivo Niños y Niñas 0 a 3 años</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tabla10">Actividad Motriz</label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Área ocupacional</th>
                                                        <th>1.0</th>
                                                        <th>2.0</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Mantiene una postura simétrica o alineada</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_postura" id="mantiene_postura_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="mantiene_postura_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_postura" id="mantiene_postura_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="mantiene_postura_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Tiene actividad espontánea</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="actividad_espontanea" id="actividad_espontanea_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="actividad_espontanea_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="actividad_espontanea" id="actividad_espontanea_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="actividad_espontanea_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Sujeta la cabeza</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sujeta_cabeza" id="sujeta_cabeza_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="sujeta_cabeza_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sujeta_cabeza" id="sujeta_cabeza_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="sujeta_cabeza_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Se sienta con apoyo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sienta_apoyo" id="sienta_apoyo_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="sienta_apoyo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sienta_apoyo" id="sienta_apoyo_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="sienta_apoyo_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Gira sobre sí mismo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sobre_mismo" id="sobre_mismo_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="sobre_mismo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sobre_mismo" id="sobre_mismo_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="sobre_mismo_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Se mantiene sentado sin apoyo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sentado_sin_apoyo" id="sentado_sin_apoyo_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="sentado_sin_apoyo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sentado_sin_apoyo" id="sentado_sin_apoyo_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="sentado_sin_apoyo_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Pasa de tumbado a sentado</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tumbado_sentado" id="tumbado_sentado_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="tumbado_sentado_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tumbado_sentado" id="tumbado_sentado_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="tumbado_sentado_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Se pone de pie con apoyo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pie_apoyo" id="pie_apoyo_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="pie_apoyo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pie_apoyo" id="pie_apoyo_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="pie_apoyo_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Da pasos con apoyo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pasos_apoyo" id="pasos_apoyo_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="pasos_apoyo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pasos_apoyo" id="pasos_apoyo_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="pasos_apoyo_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Se mantiene de pie sin apoyo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_sin_apoyo" id="mantiene_sin_apoyo_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="mantiene_sin_apoyo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_sin_apoyo" id="mantiene_sin_apoyo_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="mantiene_sin_apoyo_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Anda solo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="anda_solo" id="anda_solo_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="anda_solo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="anda_solo" id="anda_solo_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="anda_solo_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Empuja una pelota con los pies</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="empuja_pelota" id="empuja_pelota_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="empuja_pelota_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="empuja_pelota" id="empuja_pelota_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="empuja_pelota_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Anda sorteando obstáculos</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sorteando_obstaculos" id="sorteando_obstaculos_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="sorteando_obstaculos_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sorteando_obstaculos" id="sorteando_obstaculos_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="sorteando_obstaculos_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tabla10">Actividad Adaptativa</label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Área ocupacional</th>
                                                        <th>1.0</th>
                                                        <th>2.0</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Succiona</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="succiona" id="succiona_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="succiona_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="succiona" id="succiona_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="succiona_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Fija la mirada</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="fija_mirada" id="afija_mirada_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="fija_mirada_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="fija_mirada" id="fija_mirada_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="fija_mirada_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Sigue la trayectoria de un objeto</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="trayectoria_objeto" id="trayectoria_objeto_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="trayectoria_objeto_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="trayectoria_objeto" id="trayectoria_objeto_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="trayectoria_objeto_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Sostiene un sonajero</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_sonajero" id="sostiene_sonajero_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="sostiene_sonajero_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_sonajero" id="sostiene_sonajero_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="sostiene_sonajero_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Tiende la mano hacia un objeto</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="hacia_objeto" id="hacia_objeto_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="hacia_objeto_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="hacia_objeto" id="hacia_objeto_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="hacia_objeto_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Sostiene un objeto en cada mano</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_objeto" id="sostiene_objeto_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="sostiene_objeto_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_objeto" id="sostiene_objeto_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="sostiene_objeto_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Abre cajones</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_cajones" id="abre_cajones_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="abre_cajones_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_cajones" id="abre_cajones_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="abre_cajones_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Bebe solo</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bebe_solo" id="bebe_solo_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="bebe_solo_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bebe_solo" id="bebe_solo_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="bebe_solo_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Se quita una prenda de vestir</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quita_prenda" id="quita_prenda_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="quita_prenda_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quita_prenda" id="quita_prenda_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="quita_prenda_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Reconoce la función de los espacios de la casa</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="espacios_casa" id="espacios_casa_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="espacios_casa_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="espacios_casa" id="espacios_casa_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="espacios_casa_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Imita trazos con el lápiz</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="imita_trazaso" id="imita_trazaso_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="imita_trazaso_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="imita_trazaso" id="imita_trazaso_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="imita_trazaso_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                                <tbody>
                                                    <td>
                                                        <label><strong>Abre una puerta</strong></label>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_puerta" id="abre_puerta_01" value="1.0">
                                                            <label class="form-check-label custom-control-label" for="abre_puerta_01"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_puerta" id="abre_puerta_02" value="2.0">
                                                            <label class="form-check-label custom-control-label" for="abre_puerta_02"></label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total: 0</label>
                                    </div>
                                </div>
                            </div>
                            <!--Tabla 13 - Valoración de los roles ocupacionales de juego-estudio en niños y niñas mayores de tres años y adolescentes-->
                            <div class="row text-center">
                                <div class="col-12">
                                    <label for="tabla12">Tabla 13 - Valoración de los roles ocupacionales de juego-estudio en niños y niñas mayores de tres años y adolescentes</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tabla10">Roles ocupacionales de juego-estudio</label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Clase A - Sin dificultad</th>
                                                        <th>Clase B - Con dificultad leve</th>
                                                        <th>Clase C - Con dificultad moderada</th>
                                                        <th>Clase D - Con dificultad severa</th>
                                                        <th>Clase E - Con dificultad completa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseA_dificulta_01" value="0">
                                                            <label class="form-check-label custom-control-label" for="claseA_dificulta_01">0</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseB_dificulta_01" value="10">
                                                            <label class="form-check-label custom-control-label" for="claseB_dificulta_01">10</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseC_dificulta_01" value="25">
                                                            <label class="form-check-label custom-control-label" for="claseC_dificulta_01">25</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseD_dificulta_01" value="35">
                                                            <label class="form-check-label custom-control-label" for="claseD_dificulta_01">35</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseE_dificulta_01" value="50">
                                                            <label class="form-check-label custom-control-label" for="claseE_dificulta_01">50</label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tabla 14 - Valoración de los roles ocupacional relacionado con el uso del tiempo libre y de esparcimiento en adultos mayores -->
                            <div class="row text-center">
                                <div class="col-12">
                                    <label for="tabla12">Tabla 14 - Valoración de los roles ocupacional relacionado con el uso del tiempo libre y de esparcimiento en adultos mayores</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tabla10">Rol ocupacional Adultos Mayores</label>
                                        <div class="table-responsive">
                                            <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Clase A - Sin dificultad</th>
                                                        <th>Clase B - Con dificultad leve</th>
                                                        <th>Clase C - Con dificultad moderada</th>
                                                        <th>Clase D - Con dificultad severa</th>
                                                        <th>Clase E - Con dificultad completa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseA_dificulta_01" value="0">
                                                            <label class="form-check-label custom-control-label" for="claseA_dificulta_01">0</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseB_dificulta_01" value="10">
                                                            <label class="form-check-label custom-control-label" for="claseB_dificulta_01">10</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseC_dificulta_01" value="25">
                                                            <label class="form-check-label custom-control-label" for="claseC_dificulta_01">25</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseD_dificulta_01" value="35">
                                                            <label class="form-check-label custom-control-label" for="claseD_dificulta_01">35</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseE_dificulta_01" value="50">
                                                            <label class="form-check-label custom-control-label" for="claseE_dificulta_01">50</label>
                                                        </div>
                                                    </td>
                                                </tbody>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total rol ocupacional (50%): 0</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Libro II Calificación de las discapacidades (20%) Decreto Muci-->
                    <div class="card-info columna_row1_discapacidades"  @if ($decreto_1507='1' && $datos_demos["Decreto"]==='3') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Libro II Calificación de las discapacidades (20%)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th width="12%">Discapacidad</th>
                                                        <th>Número de  la discapacidad</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <table id="listado_conducta" class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="12%">Conducta</th>
                                                        <th>
                                                            <p class="text-center">10</p>
                                                            <select class="custom-select" name="conducta_10" id="conducta_10">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">11</p>
                                                            <select class="custom-select" name="conducta_11" id="conducta_11">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">12</p>
                                                            <select class="custom-select" name="conducta_12" id="conducta_12">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">13</p>
                                                            <select class="custom-select" name="conducta_13" id="conducta_13">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">14</p>
                                                            <select class="custom-select" name="conducta_14" id="conducta_14">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">15</p>
                                                            <select class="custom-select" name="conducta_15" id="conducta_15">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">16</p>
                                                            <select class="custom-select" name="conducta_16" id="conducta_16">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">17</p>
                                                            <select class="custom-select" name="conducta_17" id="conducta_17">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">18</p>
                                                            <select class="custom-select" name="conducta_18" id="conducta_18">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">19</p>
                                                            <select class="custom-select" name="conducta_19" id="conducta_19">
                                                                <option value="">0</option>
                                                            </select> 
                                                        </th>
                                                        <th width="8%">
                                                            <p class="text-center">Total</p>
                                                            <input type="text" class="form-control" name="total_conducta" id="total_conducta" value="" disabled>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th width="12%">Comunicación</th>
                                                        <th>
                                                            <p class="text-center">20</p>
                                                            <select class="custom-select" name="comunicacion_20" id="comunicacion_20">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">21</p>
                                                            <select class="custom-select" name="comunicacion_21" id="comunicacion_21">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">22</p>
                                                            <select class="custom-select" name="comunicacion_22" id="comunicacion_22">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">23</p>
                                                            <select class="custom-select" name="comunicacion_23" id="comunicacion_23">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">24</p>
                                                            <select class="custom-select" name="comunicacion_24" id="comunicacion_24">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">25</p>
                                                            <select class="custom-select" name="comunicacion_25" id="comunicacion_25">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">26</p>
                                                            <select class="custom-select" name="comunicacion_26" id="comunicacion_26">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">27</p>
                                                            <select class="custom-select" name="comunicacion_27" id="comunicacion_27">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">28</p>
                                                            <select class="custom-select" name="comunicacion_28" id="comunicacion_28">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">29</p>
                                                            <select class="custom-select" name="comunicacion_29" id="comunicacion_29">
                                                                <option value="">0</option>
                                                            </select> 
                                                        </th>
                                                        <th width="8%">
                                                            <input type="text" class="form-control" name="total_comunicacion" id="total_comunicacion" value="" disabled>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th width="12%">Cuidado personal</th>
                                                        <th>
                                                            <p class="text-center">30</p>
                                                            <select class="custom-select" name="cuidado_personal_30" id="cuidado_personal_30">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">31</p>
                                                            <select class="custom-select" name="cuidado_personal_31" id="cuidado_personal_31">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">32</p>
                                                            <select class="custom-select" name="cuidado_personal_32" id="cuidado_personal_32">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">33</p>
                                                            <select class="custom-select" name="cuidado_personal_33" id="cuidado_personal_33">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">34</p>
                                                            <select class="custom-select" name="cuidado_personal_34" id="cuidado_personal_34">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">35</p>
                                                            <select class="custom-select" name="cuidado_personal_35" id="cuidado_personal_35">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">36</p>
                                                            <select class="custom-select" name="cuidado_personal_36" id="cuidado_personal_36">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">37</p>
                                                            <select class="custom-select" name="cuidado_personal_37" id="cuidado_personal_37">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">38</p>
                                                            <select class="custom-select" name="cuidado_personal_38" id="cuidado_personal_38">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">39</p>
                                                            <select class="custom-select" name="cuidado_personal_39" id="cuidado_personal_39">
                                                                <option value="">0</option>
                                                            </select> 
                                                        </th>
                                                        <th width="8%">
                                                            <input type="text" class="form-control" name="total_cuidado_personal" id="total_cuidado_personal" value="" disabled>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th width="12%">Locomoción</th>
                                                        <th>
                                                            <p class="text-center">40</p>
                                                            <select class="custom-select" name="lomocion_40" id="lomocion_40">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">41</p>
                                                            <select class="custom-select" name="lomocion_41" id="lomocion_41">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">42</p>
                                                            <select class="custom-select" name="lomocion_42" id="lomocion_42">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">43</p>
                                                            <select class="custom-select" name="lomocion_43" id="lomocion_43">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">44</p>
                                                            <select class="custom-select" name="lomocion_44" id="lomocion_44">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">45</p>
                                                            <select class="custom-select" name="lomocion_45" id="lomocion_45">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">46</p>
                                                            <select class="custom-select" name="lomocion_46" id="lomocion_46">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">47</p>
                                                            <select class="custom-select" name="lomocion_47" id="lomocion_47">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">48</p>
                                                            <select class="custom-select" name="lomocion_48" id="lomocion_48">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">49</p>
                                                            <select class="custom-select" name="lomocion_49" id="lomocion_49">
                                                                <option value="">0</option>
                                                            </select> 
                                                        </th>
                                                        <th width="8%">
                                                            <input type="text" class="form-control" name="total_lomocion" id="total_lomocion" value="" disabled>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th width="12%">Disposición del cuerpo</th>
                                                        <th>
                                                            <p class="text-center">50</p>
                                                            <select class="custom-select" name="disposicion_50" id="disposicion_50">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">51</p>
                                                            <select class="custom-select" name="disposicion_51" id="disposicion_51">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">52</p>
                                                            <select class="custom-select" name="disposicion_52" id="disposicion_52">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">53</p>
                                                            <select class="custom-select" name="disposicion_53" id="disposicion_53">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">54</p>
                                                            <select class="custom-select" name="disposicion_54" id="disposicion_54">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">55</p>
                                                            <select class="custom-select" name="disposicion_55" id="disposicion_55">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">56</p>
                                                            <select class="custom-select" name="disposicion_56" id="disposicion_56">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">57</p>
                                                            <select class="custom-select" name="disposicion_57" id="disposicion_57">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">58</p>
                                                            <select class="custom-select" name="disposicion_58" id="disposicion_58">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">59</p>
                                                            <select class="custom-select" name="disposicion_59" id="disposicion_59">
                                                                <option value="">0</option>
                                                            </select> 
                                                        </th>
                                                        <th width="8%">
                                                            <input type="text" class="form-control" name="total_diposicion" id="total_diposicion" value="" disabled>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th width="12%">Destreza</th>
                                                        <th>
                                                            <p class="text-center">60</p>
                                                            <select class="custom-select" name="destreza_60" id="destreza_60">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">61</p>
                                                            <select class="custom-select" name="destreza_61" id="destreza_61">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">62</p>
                                                            <select class="custom-select" name="destreza_62" id="destreza_62">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">63</p>
                                                            <select class="custom-select" name="destreza_63" id="destreza_63">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">64</p>
                                                            <select class="custom-select" name="destreza_64" id="destreza_64">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">65</p>
                                                            <select class="custom-select" name="destreza_65" id="destreza_65">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">66</p>
                                                            <select class="custom-select" name="destreza_66" id="destreza_66">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">67</p>
                                                            <select class="custom-select" name="destreza_67" id="destreza_67">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">68</p>
                                                            <select class="custom-select" name="destreza_68" id="destreza_68">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">69</p>
                                                            <select class="custom-select" name="destreza_69" id="destreza_69">
                                                                <option value="">0</option>
                                                            </select> 
                                                        </th>
                                                        <th width="8%">
                                                            <input type="text" class="form-control" name="total_destreza" id="total_destreza" value="" disabled>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th width="12%">Situación</th>
                                                        <th>
                                                            <p class="text-center">70</p>
                                                            <select class="custom-select" name="situacion_70" id="situacion_70">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">71</p>
                                                            <select class="custom-select" name="situacion_71" id="situacion_71">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">72</p>
                                                            <select class="custom-select" name="situacion_72" id="situacion_72">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">73</p>
                                                            <select class="custom-select" name="situacion_73" id="situacion_73">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">74</p>
                                                            <select class="custom-select" name="situacion_74" id="situacion_74">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">75</p>
                                                            <select class="custom-select" name="situacion_75" id="situacion_75">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">76</p>
                                                            <select class="custom-select" name="situacion_76" id="situacion_76">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">77</p>
                                                            <select class="custom-select" name="situacion_77" id="situacion_77">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th>
                                                            <p class="text-center">78</p>
                                                            <select class="custom-select" name="situacion_78" id="situacion_78">
                                                                <option value="">0</option>
                                                            </select>
                                                        </th>
                                                        <th></th>
                                                        <th width="8%">
                                                            <input type="text" class="form-control" name="total_situacion" id="total_situacion" value="" disabled>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total Discapacidades: 0</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-info columna_row1_minusvalias" @if ($decreto_1507=='1' && $datos_demos["Decreto"]==='3') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Libro III Calificación de minusvalías (30%)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered"  width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Orientación</th>
                                                        <th>
                                                            <p>Orientado</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientado" value="0.0">
                                                                <label class="form-check-label custom-control-label" for="orientado">0.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Compensado</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="compensado" value="0.5">
                                                                <label class="form-check-label custom-control-label" for="compensado">0.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Compensado requiere ayuda</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="compensado_ayuda" value="1.0">
                                                                <label class="form-check-label custom-control-label" for="compensado_ayuda">1.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>No compensado</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="no_compensado" value="1.5">
                                                                <label class="form-check-label custom-control-label" for="no_compensado">1.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Ausencia</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="ausencia" value="2.0">
                                                                <label class="form-check-label custom-control-label" for="ausencia">2.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Inconsciencia</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="inconsciencia" value="2.5">
                                                                <label class="form-check-label custom-control-label" for="inconsciencia">2.5</label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Independencia física</th>
                                                        <th>
                                                            <p>Independiente</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="independiente" value="0.0">
                                                                <label class="form-check-label custom-control-label" for="independiente">0.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Independencia con ayuda</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="independiente_ayuda" value="0.5">
                                                                <label class="form-check-label custom-control-label" for="independiente_ayuda">0.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Independencia adaptada</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="independiente_adaptada" value="1.0">
                                                                <label class="form-check-label custom-control-label" for="independiente_adaptada">1.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Dependencia situacional</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="situacional" value="1.5">
                                                                <label class="form-check-label custom-control-label" for="situacional">1.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Dependencia asistida</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="depen_asistida" value="2.0">
                                                                <label class="form-check-label custom-control-label" for="depen_asistida">2.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Dependencia cuidados esp. / perm.</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="cuidados_esp" value="2.5">
                                                                <label class="form-check-label custom-control-label" for="cuidados_esp">2.5</label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Desplazamiento</th>
                                                        <th>
                                                            <p>Pleno</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="pleno" value="0.0">
                                                                <label class="form-check-label custom-control-label" for="pleno">0.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Restricciones intermitentes</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="restricion_intermi" value="0.5">
                                                                <label class="form-check-label custom-control-label" for="restricion_intermi">0.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Deficiente</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="deficiente" value="1.0">
                                                                <label class="form-check-label custom-control-label" for="deficiente">1.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Reducido al ámbito de la vecindad </p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="reducido_vecindad" value="1.5">
                                                                <label class="form-check-label custom-control-label" for="reducido_vecindad">1.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Reducido al ámbito del domicilio</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="reducido_domicilio" value="2.0">
                                                                <label class="form-check-label custom-control-label" for="reducido_domicilio">2.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Confinamiento silla / cama</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="confinamiento_cama" value="2.5">
                                                                <label class="form-check-label custom-control-label" for="confinamiento_cama">2.5</label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Ocupacional</th>
                                                        <th>
                                                            <p>Habitualmente ocupado</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="habitualmente_ocupado" value="0.0">
                                                                <label class="form-check-label custom-control-label" for="habitualmente_ocupado">0.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Ocupación recortada </p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacion_recortada" value="2.5">
                                                                <label class="form-check-label custom-control-label" for="ocupacion_recortada">2.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Ocupación adaptada</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacion_adaptada" value="5.0">
                                                                <label class="form-check-label custom-control-label" for="ocupacion_adaptada">5.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Cambio de ocupación</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="cambio_ocupacion" value="7.5">
                                                                <label class="form-check-label custom-control-label" for="cambio_ocupacion">7.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Ocupación reducida</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacion_reducida" value="10.0">
                                                                <label class="form-check-label custom-control-label" for="ocupacion_reducida">10.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Ocupación restringida</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacion_restringida" value="12.5">
                                                                <label class="form-check-label custom-control-label" for="ocupacion_restringida">12.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Sin posibilidad de ocupación</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="sin_ocupacion" value="15.0">
                                                                <label class="form-check-label custom-control-label" for="sin_ocupacion">15.0</label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Integración social</th>
                                                        <th>
                                                            <p>Socialmente integrado</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="integra_social" value="0.0">
                                                                <label class="form-check-label custom-control-label" for="integra_social">0.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Participación inhibida</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="inhibida" value="0.5">
                                                                <label class="form-check-label custom-control-label" for="inhibida">0.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Participación disminuida</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="disminuida" value="1.0">
                                                                <label class="form-check-label custom-control-label" for="disminuida">1.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Participación empobrecida</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="empobrecida" value="1.5">
                                                                <label class="form-check-label custom-control-label" for="empobrecida">1.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Relaciones reducidas</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="reducidas" value="2.0">
                                                                <label class="form-check-label custom-control-label" for="reducidas">2.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Aislamiento social</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="aislamiento" value="2.5">
                                                                <label class="form-check-label custom-control-label" for="aislamiento">2.5</label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Autosuficiencia económica</th>
                                                        <th>
                                                            <p>Plenamente autosuficiente</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="plenamente_autosufi" value="0.0">
                                                                <label class="form-check-label custom-control-label" for="plenamente_autosufi">0.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Autosuficiente</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="autosuficiente" value="0.5">
                                                                <label class="form-check-label custom-control-label" for="autosuficiente">0.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Autosuficiencia reajustada</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="auto_reajustada" value="1.0">
                                                                <label class="form-check-label custom-control-label" for="auto_reajustada">1.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Precariamente autosuficiente</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="precariamente_auto" value="1.5">
                                                                <label class="form-check-label custom-control-label" for="precariamente_auto">1.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Económicamente débil</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economicamente_debil" value="2.0">
                                                                <label class="form-check-label custom-control-label" for="economicamente_debil">2.0</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>Inactivo económicamente</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="inactivo_economicamente" value="2.5">
                                                                <label class="form-check-label custom-control-label" for="inactivo_economicamente">2.5</label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Edad cronológica</th>
                                                        <th>
                                                            <p>Menor de 18 años</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="menor_18" value="2.5">
                                                                <label class="form-check-label custom-control-label" for="menor_18">2.5</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>De 18 a 29 años</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="de18_29" value="1.3">
                                                                <label class="form-check-label custom-control-label" for="de18_29">1.3</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>De 30 a 39 años</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="de30_39" value="1.8">
                                                                <label class="form-check-label custom-control-label" for="de30_39">1.8</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>De 40 a 49 años</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="de40_49" value="2">
                                                                <label class="form-check-label custom-control-label" for="de40_49">2</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>De 50 a 54 años</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="de50_54" value="2.3">
                                                                <label class="form-check-label custom-control-label" for="de50_54">2.3</label>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <p>De 55 o más años</p>
                                                            <div class="form-check custom-control custom-radio">
                                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="de55" value="2.5">
                                                                <label class="form-check-label custom-control-label" for="de55">2.5</label>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <label for="identificacion">Total Minusvalía: 0</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Concepto final del Dictamen Pericial-->
                    <div class="card-info columna_row1_dictamen" @if ($decreto_1507='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Concepto final del Dictamen Pericial</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="porcentaje_pcl">% PCL</label>
                                        <input type="text" class="form-control" name="porcentaje_pcl" id="porcentaje_pcl" style="color: red;" value="NO ESTA DEFINIDO BACKEND" disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="rango_pcl">Rango PCL</label>
                                        <input type="text" class="form-control" name="rango_pcl" id="rango_pcl" style="color: red;" value="NO ESTA DEFINIDO BACKEND" disabled>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="tipo_evento">Tipo de evento<span style="color: red;">(*)</span></label>
                                        <select class="custom-select" name="tipo_evento" id="tipo_evento" required>
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="tipo_origen">Origen<span style="color: red;">(*)</span></label>
                                        <select class="custom-select" name="tipo_origen" id="tipo_origen" required>
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="fecha_evento">Fecha de evento<span style="color: red;">(*)</span></label>
                                        <input type="date" class="form-control" id="fecha_evento'" name="fecha_evento" max="{{date("Y-m-d")}}"/>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="fecha_estructura">Fecha de estructuración<span style="color: red;">(*)</span></label>
                                        <input type="date" class="form-control" id="fecha_estructura'" name="fecha_estructura" max="{{date("Y-m-d")}}"/>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="sustenta_fecha">Sustentación de fecha de estructuración<span style="color: red;">(*)</span></label>
                                        <textarea id="sustenta_fecha" class="form-control" name="sustenta_fecha" cols="90" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="detalle_califi">Detalle de la calificación<span style="color: red;">(*)</span></label>
                                        <textarea id="detalle_califi" class="form-control" name="detalle_califi" cols="90" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="enfermedad_catastrofica" name="enfermedad_catastrofica">
                                            <label for="enfermedad_catastrofica" class="custom-control-label">Enfermedad Catastrófica</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="enfermedad_congenita" name="enfermedad_congenita">
                                            <label for="enfermedad_congenita" class="custom-control-label">Enfermedad Congénita o cercana al nacimiento</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tipo_enfermedad">Tipo de enfermedad</label>
                                        <select class="custom-select" name="tipo_enfermedad" id="tipo_enfermedad">
                                            <option value="">Seleccione una opción</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tipo_clasificacion">Clasificación condición de salud - Tipo de enfermedad</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="requiere_persona" name="requiere_persona">
                                            <label for="requiere_persona" class="custom-control-label">Requiere tercera persona</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="requiere_decisiones_persona" name="requiere_decisiones_persona">
                                            <label for="requiere_decisiones_persona" class="custom-control-label">Requiere de tercera persona para la toma de decisiones</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="requiere_dispositivo_apoyo" name="requiere_dispositivo_apoyo">
                                            <label for="requiere_dispositivo_apoyo" class="custom-control-label">Requiere de dispositivo de apoyo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="justi_dependencia">Justificación de dependencia<span style="color: red;">(*)</span></label>
                                        <textarea id="justi_dependencia" class="form-control" name="justi_dependencia" cols="90" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <!--Retonar al modulo PCL -->
   <form action="{{route('calificacionPCL')}}" id="formularioEnvio" method="POST">            
        @csrf
        <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="{{$array_datos_calificacionPclTecnica[0]->ID_evento}}">
        <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="{{$array_datos_calificacionPclTecnica[0]->Id_Asignacion}}">
        <button type="submit" id="botonEnvioVista" style="display:none !important;"></button>
    </form> 

    @if (count($hay_agudeza_visual) == 0)
        {{-- MODAL NUEVA DEFICIENCIA VISUAL --}}
        @include('coordinador.campimetriaPCL')
    @else
        {{-- MODAL EDICIÓN DEFICIENCIA VISUAL --}}
        @include('coordinador.edicionCampimetriaPCL')
    @endif
 @stop
 

@section('js')
<script type="text/javascript">
    document.getElementById('botonEnvioVista').addEventListener('click', function(event) {
        event.preventDefault();
        // Realizar las acciones que quieres al hacer clic en el botón
        document.getElementById('formularioEnvio').submit();
    });

    //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE EXAMENES E INTERCONSULTAS
    $(document).ready(function(){
        $(".centrar").css('text-align', 'center');
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
                '<input type="date" class="form-control" id="fecha_examen_fila_'+contador_examen+'" name="fecha_examen" max="{{date("Y-m-d")}}"/>',
                '<input type="text" class="form-control" id="nombre_examen_fila_'+contador_examen+'" name="nombre_examen"/>',
                '<textarea id="descripcion_resultado_fila_'+contador_examen+'" class="form-control" name="descripcion_resultado" cols="90" rows="4"></textarea>',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_examen_fila" class="text-info" data-fila="fila_'+contador_examen+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_examen
            ];

            var agregar_examen_fila = listado_examenes_interconsultas.row.add(nueva_fila_examen).draw().node();
            $(agregar_examen_fila).addClass('fila_'+contador_examen);
            $(agregar_examen_fila).attr("id", 'fila_'+contador_examen);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
            funciones_elementos_fila(contador_examen);
        });
        
        $(document).on('click', '#btn_remover_examen_fila', function(){
            var nombre_exame_fila = $(this).data("fila");
            listado_examenes_interconsultas.row("."+nombre_exame_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_examen_fila_visual_']", function(){
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
        $('#btn_agregar_cie10_fila').click(function(){
            $('#guardar_datos_cie10').removeClass('d-none');

            contador_cie10 = contador_cie10 + 1;
            var nueva_fila_cie10 = [
                '<select id="lista_Cie10_fila_'+contador_cie10+'" class="custom-select lista_Cie10_fila_'+contador_cie10+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie10+'" name="nombre_cie10"/>',
                '<select id="lista_origenCie10_fila_'+contador_cie10+'" class="custom-select lista_origenCie10_fila_'+contador_cie10+'" name="lista_origenCie10"><option></option></select>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie10+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_cie10_fila" class="text-info" data-fila="fila_'+contador_cie10+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_cie10
            ];

            var agregar_cie10_fila = listado_diagnostico_cie10.row.add(nueva_fila_cie10).draw().node();
            $(agregar_cie10_fila).addClass('fila_'+contador_cie10);
            $(agregar_cie10_fila).attr("id", 'fila_'+contador_cie10);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
            funciones_elementos_fila(contador_cie10);
        });
            
        $(document).on('click', '#btn_remover_cie10_fila', function(){
            var nombre_cie10_fila = $(this).data("fila");
            listado_diagnostico_cie10.row("."+nombre_cie10_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_cie10_fila_visual_']", function(){
            var nombre_cie10_fila = $(this).data("clase_fila");
            listado_diagnostico_cie10.row("."+nombre_cie10_fila).remove().draw();
        });
        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE DEFICIENCIA POR FACTOR
        //Falta agregar función


    });

</script>
<script type="text/javascript" src="/js/calificacionpcl_tecnica.js"></script>
{{-- JS: Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores --}}
<script type="text/javascript" src="/js/datatable_deficiencias_alteraciones_sistemas.js"></script>
{{-- JS: DATATABLE AGUDEZA VISUAL --}}
<script type="text/javascript" src="/js/datatable_agudeza_visual.js"></script>
<script type="text/javascript" src="/js/funciones_helpers.js"></script>
@stop