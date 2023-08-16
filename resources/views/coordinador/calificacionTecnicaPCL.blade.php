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
                                    <select class="custom-select" name="origen_firme" id="origen_firme" required>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="origen_cobertura">Cobertura<span style="color: red;">(*)</span></label>
                                    <select class="custom-select" name="origen_cobertura" id="origen_cobertura" required>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="decreto_califi">Decreto de Calificación<span style="color: red;">(*)</span></label>
                                    <select class="custom-select" name="decreto_califi" id="decreto_califi" required>
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Informacion Afiliado-->
                    <div class="card-info">
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
                    <div class="card-info">
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
                                        <select class="custom-select" name="motivo_solicitud" id="motivo_solicitud" required>
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
                    <div class="card-info">
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
                    <div class="card-info">
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
                    <div class="card-info">
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
                    <div class="card-info">
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
                    <div class="card-info">
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
                                                        <th>Tabla</th>
                                                        <th>Titulo tabla</th>
                                                        <th>Clase principal<br>(FP)</th>
                                                        <th>CFM1</th>
                                                        <th>CFM2</th>
                                                        <th>FU</th>
                                                        <th>CAT</th>
                                                        <th>Clase final</th>
                                                        <th>DX<br>principal</th>
                                                        <th>MSD</th>
                                                        <th>Deficiencia</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_porfactor"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--Traer los datos ya registrados en la BD -->
                                                </tbody>
                                            </table>
                                        </div><br>
                                        <x-adminlte-button class="mr-auto" id="guardar_datos_deficiencia" theme="info" label="Guardar" disabled/>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-info">
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
                        <div class="card-info">
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
                    <div class="card-info">
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
                                            <table id="listado_laboralmente_activo_p2" class="table table-striped table-bordered"  width="100%">
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
                            <!-- prueba desde la BD -->
                            <div class="row">

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
        <button type="submit" id="botonEnvioVista"></button>
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

    //  SCRIPT PARA ELIMINAR LA FILA DE LA AGUDEZA VISUAL CUANDO EL USUARIO REALICE LA ACCIÓN

    $(document).ready(function(){
        var tabla_agudeza_visual = $('#listado_agudeza_visual').DataTable({
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

        autoAdjustColumns(tabla_agudeza_visual);
        $(document).on('click', "a[id^='btn_remover_fila_']", function(){
            var nombre_fila_agudeza = $(this).data("fila_agudeza");
            var regex =  /\d+/;
            var id_agudeza = nombre_fila_agudeza.match(regex)[0];
            
            let datos_eliminar_info_agudeza = {
                '_token':  $("input[name='_token']").val(),
                'Id_agudeza': id_agudeza,
                'ID_evento': {{$array_datos_calificacionPclTecnica[0]->ID_evento}}
            };

            $.ajax({
                url: "/eliminarAgudezaVisual",
                type: "post",
                data: datos_eliminar_info_agudeza,
                success:function(response){
                    tabla_agudeza_visual.row("."+nombre_fila_agudeza).remove().draw();
                    if(response.parametro == "borro"){
                        $("#btn_abrir_modal_agudeza").prop('disabled', false);
                        $("#btn_abrir_modal_agudeza").hover(function(){
                            $(this).css('cursor', 'pointer');
                        });
                        $("#btn_abrir_modal_agudeza").attr("data-target", "#modal_nueva_agudeza_visual");
                        location.reload();
                    }
                }         
            });

            
            
        });

        $(window).scrollTop(2758);

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
{{-- <script type="text/javascript" src="/js/campimetria.js"></script> --}}
<script type="text/javascript" src="/js/edicion_campimetria.js"></script>
<script type="text/javascript" src="/js/funciones_helpers.js"></script>
@stop