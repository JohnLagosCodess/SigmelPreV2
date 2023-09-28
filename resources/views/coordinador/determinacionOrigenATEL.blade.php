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
            <input type="hidden" name="Id_Evento_dto_atel" id="Id_Evento_dto_atel" value="{{$array_datos_DTO_ATEL[0]->ID_evento}}">
            <input type="hidden" name="Id_Asignacion_dto_atel" id="Id_Asignacion_dto_atel" value="{{$array_datos_DTO_ATEL[0]->Id_Asignacion}}">
            <input type="hidden" name="Id_Proceso_dto_atel" id="Id_Proceso_dto_atel" value="{{$array_datos_DTO_ATEL[0]->Id_proceso}}">
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form action="" method="POST" id="form_DTO_ATEL">
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
                                                <option value="Si">Si</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Tipo de Evento <span style="color:red;">(*)</span></label>
                                            <input type="hidden" id="nombre_evento_bd" value="{{$array_datos_DTO_ATEL[0]->Nombre_evento}}">
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
                                                <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" style="color: red;" value="{{$numero_consecutivo}}" disabled>   
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="motivo_solicitud">Motivo Solicitud <span style="color:red;">(*)</span></label>
                                                <input type="hidden" id="motivo_solicitud_bd" value="{{$motivo_solicitud_actual[0]->Nombre_solicitud}}">
                                                <select class="custom-select motivo_solicitud" name="motivo_solicitud" id="motivo_solicitud" required></select>
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
    
                            {{-- Información laboral --}}
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
                                                    <input type="date" class="form-control fecha_ingreso" name="fecha_ingreso" id="fecha_ingreso" value="{{$array_datos_info_laboral[0]->F_ingreso}}" max="{{date("Y-m-d")}}" disabled>
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
                                                <div class="form-group">
                                                    <label for="descripcion" class="col-form-label">Descripción</label>
                                                    <textarea class="form-control descripcion" name="descripcion" id="descripcion" rows="2" disabled>{{$array_datos_info_laboral[0]->Descripcion}}</textarea>
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
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_accidente">Tipo de accidente <span style="color:red;">(*)</span></label>
                                                <select class="custom-select tipo_accidente" name="tipo_accidente" id="tipo_accidente" required></select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_evento">Fecha del evento <span style="color:red;">(*)</span></label>
                                                <input type="date" class="form-control" name="fecha_evento" id="fecha_evento" max="{{date("Y-m-d")}}" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="hora_evento">Hora del evento <span style="color:red;">(*)</span></label>
                                                <input type="time" class="form-control" name="hora_evento" id="hora_evento" step="3600" min="00:00" max="23:59" pattern="[0-2][0-9]:[0-5][0-9]" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="grado_severidad">Grado de severidad <span style="color:red;">(*)</span></label>
                                                <select class="custom-select grado_severidad" name="grado_severidad" id="grado_severidad" required></select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="mortal">Mortal</label>
                                                <select class="custom-select mortal" name="mortal" id="mortal">
                                                    <option value=""></option>
                                                    <option value="Si">Si</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4 d-none" id="mostrar_f_fallecimiento">
                                            <div class="form-group">
                                                <label for="fecha_fallecimiento">Fecha de fallecimiento <span style="color:red;">(*)</span></label>
                                                <input type="date" class="form-control" name="fecha_fallecimiento" id="fecha_fallecimiento" max="{{date("Y-m-d")}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="descripcion_FURAT" class="col-form-label">Descripción Formato Único de Reporte de presunto Accidente de Trabajo (FURAT) <span style="color:red;">(*)</span></label>
                                                <textarea class="form-control descripcion_FURAT" name="descripcion_FURAT" id="descripcion_FURAT" rows="2" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="factor_riesgo">Factor de riesgo</label>
                                                <select class="custom-select factor_riesgo" name="factor_riesgo" id="factor_riesgo"></select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_lesion">Tipo de lesión</label>
                                                <select class="custom-select tipo_lesion" name="tipo_lesion" id="tipo_lesion"></select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="parte_cuerpo_afectada">Parte del cuerpo afectada</label>
                                                <select class="custom-select parte_cuerpo_afectada" name="parte_cuerpo_afectada" id="parte_cuerpo_afectada"></select>
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
                                                <textarea class="form-control justificacion_revision_origen" name="justificacion_revision_origen" id="justificacion_revision_origen" rows="2" required></textarea>
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
                                            <h6 class="text-center"><b>Documentos tenidos en cuenta para la calificación</b></h6><h6>
                                        </h6></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="furat" name="furat" value="FURAT">
                                                    <label for="furat" class="custom-control-label">Formato Único de Registro de Accidente de Trabajo (FURAT)</label>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="historia_clinica_completa" name="historia_clinica_completa" value="Historia clínica completa">
                                                    <label for="historia_clinica_completa" class="custom-control-label">Historia clínica completa</label> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-2 col-form-label">Otros</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control otros" name="otros" id="otros">
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
    
                            {{-- Exámanes e interconsultas --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Exámanes e interconsultas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
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
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Diagnóstico motivo de calificación --}}
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Diagnóstico motivo de calificación</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="table-responsive">
                                                <table id="listado_diagnostico_cie10" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>CIE-10</th>
                                                            <th>Nombre CIE-10</th>
                                                            <th>Descripción complementaria del DX</th>
                                                            <th>Lateralidad Dx</th>
                                                            <th>Origen Dx</th>
                                                            <th>Principal</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($array_datos_diagnostico_motcalifi as $diagnostico)
                                                        <tr class="fila_diagnosticos_{{$diagnostico->Id_Diagnosticos_motcali}}" id="datos_diagnostico">
                                                            <td>{{$diagnostico->Codigo}}</td>
                                                            <td>{{$diagnostico->Nombre_CIE10}}</td>
                                                            <td>{{$diagnostico->Nombre_parametro}}</td>
                                                            <td>{{$diagnostico->Deficiencia_motivo_califi_condiciones}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_moticalifi{{$diagnostico->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnostico->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnostico->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
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
                                                <textarea class="form-control sustentacion_califi_origen" name="sustentacion_califi_origen" id="sustentacion_califi_origen" rows="2" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="origen_dto_atel">Origen <span style="color:red;">(*)</span></label>
                                                <select class="custom-select origen_dto_atel" name="origen_dto_atel" id="origen_dto_atel" required></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
    <script type="text/javascript">
        $(".centrar").css('text-align', 'center');
        $(document).on('mouseover',"input[id^='edit_evento_']", function(){
            let url_editar_evento = $('#para_ver_edicion_evento').val();
            $("form[id^='form_DTO_ATEL']").attr("action", url_editar_evento);    
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
        $('#btn_agregar_cie10_fila').click(function(){
            $('#guardar_datos_cie10').removeClass('d-none');

            contador_cie10 = contador_cie10 + 1;
            var nueva_fila_cie10 = [
                '<select id="lista_Cie10_fila_'+contador_cie10+'" class="custom-select lista_Cie10_fila_'+contador_cie10+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie10+'" name="nombre_cie10"/>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie10+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<select id="lista_lateralidadCie10_fila_'+contador_cie10+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie10+'" name="lista_lateralidadCie10"><option></option></select>',
                '<select id="lista_origenCie10_fila_'+contador_cie10+'" class="custom-select lista_origenCie10_fila_'+contador_cie10+'" name="lista_origenCie10"><option></option></select>',
                '<input type="checkbox" id="checkbox_dx_principal_Cie10'+contador_cie10+'" class="checkbox_dx_principal_Cie10'+contador_cie10+'" data-id_fila_checkbox_dx_principal_Cie10="'+contador_cie10+'" style="transform: scale(1.2);">',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_cie10_fila" class="text-info" data-fila="fila_'+contador_cie10+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_cie10
            ];

            var agregar_cie10_fila = listado_diagnostico_cie10.row.add(nueva_fila_cie10).draw().node();
            $(agregar_cie10_fila).addClass('fila_'+contador_cie10);
            $(agregar_cie10_fila).attr("id", 'fila_'+contador_cie10);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
            funciones_elementos_fila_diagnosticos(contador_cie10);
        });
            
        $(document).on('click', '#btn_remover_cie10_fila', function(){
            var nombre_cie10_fila = $(this).data("fila");
            listado_diagnostico_cie10.row("."+nombre_cie10_fila).remove().draw();
        });
    </script>
    <script type="text/javascript" src="/js/dto_atel.js"></script>
   
@stop