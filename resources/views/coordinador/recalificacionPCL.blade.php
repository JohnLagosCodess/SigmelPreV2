@extends('adminlte::page')
@if (!empty($array_datos_RecalificacionPcl[0]->Id_Servicio) && $array_datos_RecalificacionPcl[0]->Id_Servicio == 7)
    @section('title', 'Recalificación PCL')    
@elseif(!empty($array_datos_RecalificacionPcl[0]->Id_Servicio) && $array_datos_RecalificacionPcl[0]->Id_Servicio == 8)
    @section('title', 'Revisión pensión PCL')            
@endif
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
                    {{-- {{($array_datos_motivo_solicitud[0]->Id_motivo_solicitud)}}
                    {{($validar_evento_asignacion[0]->Id_servicio)}} --}}
                </p>
            </div>
        </div>
    </div>
    @if (!empty($array_datos_motivo_solicitud[0]->Id_motivo_solicitud) && $array_datos_motivo_solicitud[0]->Id_motivo_solicitud != 1 && !empty($validar_evento_asignacion[0]->Id_servicio) && !empty($validar_estado_decreto[0]->Estado_decreto)  && $validar_estado_decreto[0]->Estado_decreto == 'Abierto')
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">¡No se puede realizar Recalificación o Revisión Pensión!</h4>
            <p>El Evento tiene Calificación Técnica y el motivo de solicitud para la Calificación Técnica no se encuentra en primera oportunidad, el motivo de solicitud esta en {{$array_datos_motivo_solicitud[0]->Nombre_solicitud}}.</p>
            <hr>
            <p class="mb-0">Debe actualizar el motivo de solicitud para la Calificación Técnica o actualizarlo en la Calificación Técnica cuando se creé.</p>
        </div>
    @elseif(!empty($array_datos_motivo_solicitud[0]->Id_motivo_solicitud) && $array_datos_motivo_solicitud[0]->Id_motivo_solicitud == 1 && !empty($validar_evento_asignacion[0]->Id_servicio) && !empty($validar_estado_decreto[0]->Estado_decreto) && $validar_estado_decreto[0]->Estado_decreto == 'Abierto')
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">¡No se puede realizar Recalificación o Revisión Pensión!</h4>
            <p>El Evento tiene Calificación Técnica y no se ha finalizado.</p>
            <hr>
            <p class="mb-0">Para realizar Recalificación o Revisión Pensión al Evento debe finalizar la Calificación Técnica.</p>
        </div>
    @elseif(!empty($array_datos_motivo_solicitud[0]->Id_motivo_solicitud) && $array_datos_motivo_solicitud[0]->Id_motivo_solicitud == 1 && !empty($validar_evento_asignacion[0]->Id_servicio) && !empty($validar_estado_decreto[0]->Estado_decreto) && $validar_estado_decreto[0]->Estado_decreto == 'Cerrado')        
        <div class="card-info" style="border: 1px solid black;">
            <div class="card-header text-center">
                @if ($array_datos_RecalificacionPcl[0]->Id_Servicio == 7)                    
                    <h4>Calificación PCL - Evento: {{$array_datos_RecalificacionPcl[0]->ID_evento}}</h4>
                    <h5 style="font-style: italic;">Recalificación</h5>                
                @elseif($array_datos_RecalificacionPcl[0]->Id_Servicio == 8)
                    <h4>Calificación PCL - Evento: {{$array_datos_RecalificacionPcl[0]->ID_evento}}</h4>
                    <h5 style="font-style: italic;">Revisión pensión</h5>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form id="form_RecaliDecreto" method="POST">
                            <!--Calificacón PCL-->
                            <div class="card-body" id="id_calificacion_pcl">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="origen_firme">Origen en firme<span style="color: red;">(*)</span></label>
                                            <select class="custom-select origen_firme" name="origen_firme" id="origen_firme" required>
                                                @if (!empty($datos_decreto[0]->Origen_firme))
                                                    <option value="{{$datos_decreto[0]->Origen_firme}}" selected>{{$datos_decreto[0]->Origen}}</option>
                                                @else
                                                    <option value="">Seleccione una opción</option>
                                                @endif
                                            </select>
                                            <input hidden="hidden" type="text" name="Id_Evento_decreto" id="Id_Evento_decreto" value="{{$array_datos_RecalificacionPcl[0]->ID_evento}}">
                                            <input hidden="hidden" type="text" name="Id_Proceso_decreto" id="Id_Proceso_decreto" value="{{$array_datos_RecalificacionPcl[0]->Id_proceso}}">
                                            <input hidden="hidden" type="text" name="Id_Asignacion_decreto" id="Id_Asignacion_decreto" value="{{$array_datos_RecalificacionPcl[0]->Id_Asignacion}}">                                        
                                            {{-- <input hidden="hidden" type="text" class="form-control" id="conteo_listado_examenes_interconsulta" value="{{count($array_datos_examenes_interconsultas)}}">
                                            <input hidden="hidden" type="text" class="form-control" id="conteo_listado_diagnosticos_moticalifi" value="{{count($array_datos_diagnostico_motcalifi)}}">
                                            <input hidden="hidden" type="text" class="form-control" id="conteo_listado_agudeza_auditiva" value="{{count($array_agudeza_Auditiva)}}">
                                            <input hidden="hidden" type="text" class="form-control" id="conteo_listado_deficiencia_alteraciones" value="{{count($array_datos_deficiencias_alteraciones)}}"> --}}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="origen_cobertura">Cobertura<span style="color: red;">(*)</span></label>
                                            <select class="custom-select origen_cobertura" name="origen_cobertura" id="origen_cobertura" required>
                                                @if (!empty($datos_decreto[0]->Cobertura))
                                                    <option value="{{$datos_decreto[0]->Cobertura}}" selected>{{$datos_decreto[0]->Coberturas}}</option>
                                                @else
                                                    <option value="">Seleccione una opción</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4" id="decreto_seleccion">
                                        <div class="form-group">
                                            <label for="decreto_califi">Decreto de Calificación<span style="color: red;">(*)</span></label>
                                            <select class="custom-select decreto_califi" name="decreto_califi" id="decreto_califi" required>
                                                @if (!empty($datos_decreto[0]->Decreto_calificacion))
                                                    <option value="{{$datos_decreto[0]->Decreto_calificacion}}" selected>{{$datos_decreto[0]->Nombre_decreto}}</option>
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
                            if(!empty($datos_decreto[0]->Origen_firme) &&  $datos_decreto[0]->Origen_firme == '48' && !empty($datos_decreto[0]->Cobertura) && $datos_decreto[0]->Cobertura == '50'):
                                $decreto_1507='1';
                            else:
                                $decreto_1507='0';                            
                            endif
                            ?>                                                                
                            <!-- Informacion Afiliado-->
                            <div class="card-info columna_row1_afiliado" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif>
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Información del afiliado</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nombre_afiliado">Nombre de afiliado</label>
                                                <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_RecalificacionPcl[0]->Nombre_afiliado}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="identificacion">N° Identificación</label>
                                                <input type="text" class="form-control" name="identificacion" id="identificacion" value="{{$array_datos_RecalificacionPcl[0]->Nro_identificacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="id_evento">ID evento</label>
                                                <input type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_RecalificacionPcl[0]->ID_evento}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Informacion Ditacmen-->
                            <div class="card-info columna_row1_dictamen" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif>
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Información del Dictamen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_dictamen">Fecha Dictamen</label>
                                                <input type="text" class="form-control" name="fecha_dictamen" id="fecha_dictamen" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="numero_dictamen">N° Dictamen</label>
                                                @if (empty($array_info_decreto_evento_re[0]->Numero_dictamen))
                                                    <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" value="{{$numero_consecutivo}}" disabled>                                                
                                                @else
                                                    <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" value="{{$array_info_decreto_evento_re[0]->Numero_dictamen}}" disabled>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="motivo_solicitud">Motivo Solicitud<span style="color: red;">(*)</span></label>
                                                <select class="custom-select motivo_solicitud" name="motivo_solicitud" id="motivo_solicitud" style="width: 100%;" required>
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
                            <div class="card-info columna_row1_documentos" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif >
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
                                                    @if ($array_datos_relacion_documentos['Historiaclinicacompleta'] == 'vacio')
                                                        <input class="custom-control-input" type="checkbox" id="hitoria_clinica" name="hitoria_clinica" value="Historia clínica completa">
                                                        <label for="hitoria_clinica" class="custom-control-label">Historia clínica completa</label>                 
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="hitoria_clinica" name="hitoria_clinica" value="Historia clínica completa" checked>
                                                        <label for="hitoria_clinica" class="custom-control-label">Historia clínica completa</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if ($array_datos_relacion_documentos['Examenespreocupacionales'] == 'vacio')
                                                        <input class="custom-control-input" type="checkbox" id="examanes_preocupacionales" name="examanes_preocupacionales" value="Exámenes preocupacionales">
                                                        <label for="examanes_preocupacionales" class="custom-control-label">Exámenes preocupacionales</label>                                                    
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="examanes_preocupacionales" name="examanes_preocupacionales" value="Exámenes preocupacionales" checked>
                                                        <label for="examanes_preocupacionales" class="custom-control-label">Exámenes preocupacionales</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if ($array_datos_relacion_documentos['Epicrisis'] == 'vacio')
                                                        <input class="custom-control-input" type="checkbox" id="epicrisis" name="epicrisis" value="Epicrisis">
                                                        <label for="epicrisis" class="custom-control-label">Epicrisis</label>                                                    
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="epicrisis" name="epicrisis" value="Epicrisis" checked>
                                                        <label for="epicrisis" class="custom-control-label">Epicrisis</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if ($array_datos_relacion_documentos['Examenesperiodicosocupacionales'] == 'vacio')
                                                        <input class="custom-control-input" type="checkbox" id="examanes_periodicos" name="examanes_periodicos" value="Exámenes periódicos ocupacionales">
                                                        <label for="examanes_periodicos" class="custom-control-label">Exámenes periódicos ocupacionales</label>                                                    
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="examanes_periodicos" name="examanes_periodicos" value="Exámenes periódicos ocupacionales" checked>
                                                        <label for="examanes_periodicos" class="custom-control-label">Exámenes periódicos ocupacionales</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if ($array_datos_relacion_documentos['Examenesparaclinicos'] == 'vacio')
                                                        <input class="custom-control-input" type="checkbox" id="examanes_paraclinicos" name="examanes_paraclinicos" value="Exámenes paraclinicos">
                                                        <label for="examanes_paraclinicos" class="custom-control-label">Exámenes paraclinicos</label>                                                    
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="examanes_paraclinicos" name="examanes_paraclinicos" value="Exámenes paraclinicos" checked>
                                                        <label for="examanes_paraclinicos" class="custom-control-label">Exámenes paraclinicos</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if ($array_datos_relacion_documentos['ExamenesPostocupacionales'] == 'vacio')
                                                        <input class="custom-control-input" type="checkbox" id="examanes_post_ocupacionales" name="examanes_post_ocupacionales" value="Exámenes Post-ocupacionales">
                                                        <label for="examanes_post_ocupacionales" class="custom-control-label">Exámenes Post-ocupacionales</label>                                                    
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="examanes_post_ocupacionales" name="examanes_post_ocupacionales" value="Exámenes Post-ocupacionales" checked>
                                                        <label for="examanes_post_ocupacionales" class="custom-control-label">Exámenes Post-ocupacionales</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if ($array_datos_relacion_documentos['Conceptosdesaludocupacion'] == 'vacio')
                                                        <input class="custom-control-input" type="checkbox" id="salud_ocupacionales" name="salud_ocupacionales" value="Conceptos de salud ocupacional">
                                                        <label for="salud_ocupacionales" class="custom-control-label">Conceptos de salud ocupacional</label>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="salud_ocupacionales" name="salud_ocupacionales" value="Conceptos de salud ocupacional" checked>
                                                        <label for="salud_ocupacionales" class="custom-control-label">Conceptos de salud ocupacional</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="descripcion_otros">Otros:</label> 
                                                @if (!empty($array_info_decreto_evento[0]->Otros_relacion_doc) && empty($array_info_decreto_evento_re[0]->Otros_relacion_doc))
                                                    <textarea class="form-control" name="descripcion_otros" id="descripcion_otros" cols="30" rows="5" style="resize: none;">{{$array_info_decreto_evento[0]->Otros_relacion_doc}}</textarea>                                                
                                                @elseif(!empty($array_info_decreto_evento_re[0]->Otros_relacion_doc))
                                                    <textarea class="form-control" name="descripcion_otros" id="descripcion_otros" cols="30" rows="5" style="resize: none;">{{$array_info_decreto_evento_re[0]->Otros_relacion_doc}}</textarea>                                                
                                                @endif                                           
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fundamentos para la calificacion-->
                            <div class="card-info columna_row1_fundamentos" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif>
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Fundamentos para la calificación de la Pérdida de Capacidad Laboral y ocupacional</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="descripcion_enfermedad">Descripción de la enfermedad actual<span style="color: red;">(*)</span>:</label>
                                                @if (!empty($array_info_decreto_evento[0]->Descripcion_enfermedad_actual) && empty($array_info_decreto_evento_re[0]->Descripcion_enfermedad_actual))
                                                    <textarea class="form-control" name="descripcion_enfermedad" id="descripcion_enfermedad" cols="30" rows="5" style="resize: none;" >{{$array_info_decreto_evento[0]->Descripcion_enfermedad_actual}}</textarea>
                                                @elseif(!empty($array_info_decreto_evento_re[0]->Descripcion_enfermedad_actual))
                                                    <textarea class="form-control" name="descripcion_enfermedad" id="descripcion_enfermedad" cols="30" rows="5" style="resize: none;" >{{$array_info_decreto_evento_re[0]->Descripcion_enfermedad_actual}}</textarea>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="dominancia">Dominancia</label>
                                                <input type="text" class="form-control" name="dominancia" id="dominancia" value="{{$motivo_solicitud_actual[0]->Nombre_dominancia}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Guardar Historial Enfermedad-->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                    @if (!empty($array_info_decreto_evento[0]->ID_Evento) && empty($array_info_decreto_evento_re[0]->ID_Evento))
                                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de diligenciar los formularios anteriores dar clic en el botón <b>Guardar</b>, al <b>Guardar</b> no podra cambiar de <b>Decreto</b>.                                                    
                                                    @elseif(!empty($array_info_decreto_evento_re[0]->ID_Evento))
                                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Si realizo algún cambio en los formularios anteriores dar clic en el botón <b>Actualizar</b>.
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                @if (!empty($array_info_decreto_evento[0]->ID_Evento) && empty($array_info_decreto_evento_re[0]->ID_Evento))
                                                    <input type="submit" id="GuardarDecreto" name="GuardarDecreto" class="btn btn-info" value="Guardar">                                                
                                                    <input hidden="hidden" type="text" id="bandera_decreto_guardar_actualizar" value="Guardar">
                                                @elseif(!empty($array_info_decreto_evento_re[0]->ID_Evento))
                                                    <input type="submit" id="ActualizarDecreto" name="ActualizarDecreto" class="btn btn-info" value="Actualizar">
                                                    <input hidden="hidden" type="text" id="bandera_decreto_guardar_actualizar" value="Actualizar">
                                                @endif
                                            </div>
                                        </div>
                                        <div id="div_alerta_decreto" class="col-12 d-none">
                                            <div class="form-group">
                                                <div class="alerta_decreto alert alert-success mt-2 mr-auto" role="alert"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="botonNoDecrecto">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            @if (empty($datos_decreto[0]->ID_Evento))                                            
                                                <input type="submit" id="GuardarNoDecreto" name="GuardarNoDecreto" class="btn btn-info" value="Guardar">
                                                <input hidden="hidden" type="text" name="banderaGuardarNoDecreto" id="banderaGuardarNoDecreto" value="Guardar">
                                            @else
                                                <input type="submit" id="ActualizarNoDecreto" name="ActualizarNoDecreto" class="btn btn-info" value="Actualizar">
                                                <input hidden="hidden" type="text" name="banderaGuardarNoDecreto" id="banderaGuardarNoDecreto" value="Actualizar">
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_alerta_Nodecreto" class="col-12 d-none">
                                        <div class="form-group">
                                            <div class="alerta_Nodecreto alert alert-success mt-2 mr-auto" role="alert"></div>
                                        </div>
                                    </div>                                
                                </div>
                            </div>
                        </form>
                        <!-- examen interconsulta-->
                        <div class="card-info columna_row1_interconsulta" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif>
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
                                                        @foreach ($array_datos_examenes_interconsultasre as $examenesre)
                                                        <tr class="fila_examenes_{{$examenesre->Id_Examenes_interconsultas}}" id="datos_examenes_interconsulta">
                                                            <td>{{$examenesre->F_examen_interconsulta}}</td>
                                                            <td>{{$examenesre->Nombre_examen_interconsulta}}</td>
                                                            <td>{{$examenesre->Descripcion_resultado}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_examen_fila_examenes_{{$examenesre->Id_Examenes_interconsultas}}" data-id_fila_quitar="{{$examenesre->Id_Examenes_interconsultas}}" data-clase_fila="fila_examenes_{{$examenesre->Id_Examenes_interconsultas}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr>                                                        
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto" id="guardar_datos_examenes" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Diagnostico motivo cali-->
                        <div class="card-info columna_row1_motivo_cali" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif>
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
                                                        @foreach ($array_datos_diagnostico_motcalifire as $diagnosticore)
                                                        <tr class="fila_diagnosticos_{{$diagnosticore->Id_Diagnosticos_motcali}}" id="datos_diagnostico">
                                                            <td>{{$diagnosticore->Codigo}}</td>
                                                            <td>{{$diagnosticore->Nombre_CIE10}}</td>
                                                            <td>{{$diagnosticore->Nombre_parametro}}</td>
                                                            <td>{{$diagnosticore->Deficiencia_motivo_califi_condiciones}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_moticalifi{{$diagnosticore->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnosticore->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnosticore->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto" id="guardar_datos_cie10" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Deficiencia-->
                    <div class="card-info columna_row1_deficiencia" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Titulo I Calificación / Valoración de las Deficiencias (50%)</h5>
                        </div>
                        <br>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores</h5>
                        </div>
                        @if (!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 2 && empty($array_info_decreto_evento_re[0]->Decreto_calificacion))
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="insercion_decreto_cero" role="alert">
                                            </div>
                                            <div class="table-responsive">
                                                <table id="listado_deficiencias_decretoCero" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Tabla</th>
                                                            <th>Titulo tabla</th>
                                                            <th>Deficiencia</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_decretoceroFila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($array_datos_deficiencias_alteraciones as $deficiencias_ateraciones)
                                                        <tr class="fila_deficiencias_{{$deficiencias_ateraciones->Id_Deficiencia}}" id="datos_deficiencias">
                                                            <td>{{$deficiencias_ateraciones->Ident_tabla}}</td>
                                                            <td>{{$deficiencias_ateraciones->Nombre_tabla}}</td>
                                                            <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencias_decretocero_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficiencias_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_deficiencias_DecretoCero" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (!empty($array_info_decreto_evento_re[0]->Decreto_calificacion) && $array_info_decreto_evento_re[0]->Decreto_calificacion == 2)
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="insercion_decreto_cero" role="alert">
                                            </div>
                                            <div class="table-responsive">
                                                <table id="listado_deficiencias_decretoCero" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Tabla</th>
                                                            <th>Titulo tabla</th>
                                                            <th>Deficiencia</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_decretoceroFila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($array_datos_deficiencias_alteraciones as $deficiencias_ateraciones)
                                                        <tr class="fila_deficiencias_{{$deficiencias_ateraciones->Id_Deficiencia}}" id="datos_deficiencias">
                                                            <td>{{$deficiencias_ateraciones->Ident_tabla}}</td>
                                                            <td>{{$deficiencias_ateraciones->Nombre_tabla}}</td>
                                                            <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencias_decretocero_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficiencias_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
                                                        @foreach ($array_datos_deficiencias_alteracionesre as $deficiencias_ateracionesre)
                                                        <tr class="fila_deficiencias_{{$deficiencias_ateracionesre->Id_Deficiencia}}" id="datos_deficiencias">
                                                            <td>{{$deficiencias_ateracionesre->Ident_tabla}}</td>
                                                            <td>{{$deficiencias_ateracionesre->Nombre_tabla}}</td>
                                                            <td>{{$deficiencias_ateracionesre->Deficiencia}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencias_decretocero_{{$deficiencias_ateracionesre->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateracionesre->Id_Deficiencia}}" data-clase_fila="fila_deficiencias_{{$deficiencias_ateracionesre->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_deficiencias_DecretoCero" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3 && empty($array_info_decreto_evento_re[0]->Decreto_calificacion))
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="insercion_decreto_3" role="alert">                                                
                                            </div>
                                            <div class="table-responsive">
                                                <table id="listado_deficiencias_decreto_tres" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Tabla</th>
                                                            <th>Titulo tabla</th>
                                                            <th>Deficiencia</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_decretotresfila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($array_datos_deficiencias_alteraciones as $deficiencias_ateraciones)
                                                        <tr class="fila_deficiencias3_{{$deficiencias_ateraciones->Id_Deficiencia}}" id="datos_deficiencias3">
                                                            <td>{{$deficiencias_ateraciones->Tabla1999}}</td>
                                                            <td>{{$deficiencias_ateraciones->Titulo_tabla1999}}</td>
                                                            <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencias_decreto3_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficiencias3_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_deficiencias_Decreto3" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (!empty($array_info_decreto_evento_re[0]->Decreto_calificacion) && $array_info_decreto_evento_re[0]->Decreto_calificacion == 3)
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="insercion_decreto_3" role="alert">                                                
                                            </div>
                                            <div class="table-responsive">
                                                <table id="listado_deficiencias_decreto_tres" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>Tabla</th>
                                                            <th>Titulo tabla</th>
                                                            <th>Deficiencia</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_decretotresfila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($array_datos_deficiencias_alteraciones as $deficiencias_ateraciones)
                                                        <tr class="fila_deficiencias3_{{$deficiencias_ateraciones->Id_Deficiencia}}" id="datos_deficiencias3">
                                                            <td>{{$deficiencias_ateraciones->Tabla1999}}</td>
                                                            <td>{{$deficiencias_ateraciones->Titulo_tabla1999}}</td>
                                                            <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencias_decreto3_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficiencias3_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
                                                        @foreach ($array_datos_deficiencias_alteracionesre as $deficiencias_ateracionesre)
                                                        <tr class="fila_deficiencias3_{{$deficiencias_ateracionesre->Id_Deficiencia}}" id="datos_deficiencias3">
                                                            <td>{{$deficiencias_ateracionesre->Tabla1999}}</td>
                                                            <td>{{$deficiencias_ateracionesre->Titulo_tabla1999}}</td>
                                                            <td>{{$deficiencias_ateracionesre->Deficiencia}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencias_decreto3_{{$deficiencias_ateracionesre->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateracionesre->Id_Deficiencia}}" data-clase_fila="fila_deficiencias3_{{$deficiencias_ateracionesre->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_deficiencias_Decreto3" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 1 && empty($array_info_decreto_evento_re[0]->Decreto_calificacion))
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
                                                            <th style="width: 140px !important;">Título tabla</th>
                                                            <th style="width: 140px !important;">Clase principal (FP)</th>
                                                            <th style="width: 140px !important;">CFM1</th>
                                                            <th style="width: 140px !important;">CFM2</th>
                                                            <th style="width: 140px !important;">FU</th>
                                                            <th style="width: 140px !important;">CAT</th>
                                                            <th style="width: 140px !important;">Clase final</th>
                                                            <th style="width: 140px !important;">DX principal</th>
                                                            <th style="width: 140px !important;">MSD</th>
                                                            <th style="width: 140px !important;">Deficiencia</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_porfactor"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($array_datos_deficiencias_alteraciones as $deficiencias_ateraciones)
                                                            <tr class="fila_deficienaAlteracion_{{$deficiencias_ateraciones->Id_Deficiencia}}" id="datos_deficiencia_alteraciones">
                                                                <td>{{$deficiencias_ateraciones->Ident_tabla}}</td>
                                                                <td>{{$deficiencias_ateraciones->Nombre_tabla}}</td>
                                                                <td>{{$deficiencias_ateraciones->FP}}</td>
                                                                <td>{{$deficiencias_ateraciones->CFM1}}</td>
                                                                <td>{{$deficiencias_ateraciones->CFM2}}</td>
                                                                <td>{{$deficiencias_ateraciones->FU}}</td>
                                                                <td>{{$deficiencias_ateraciones->CAT}}</td>
                                                                <td>{{$deficiencias_ateraciones->Clase_Final}}</td>
                                                                <td>
                                                                    @if ($deficiencias_ateraciones->Dx_Principal == 'Si')
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}" checked>
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="NoDxPrincipal_deficiencia_alteraciones">
                                                                    @else
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}">
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="SiDxPrincipal_deficiencia_alteraciones">
                                                                    @endif
                                                                </td>
                                                                <td>{{$deficiencias_ateraciones->MSD}}</td>
                                                                <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencia_alteraciones{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficienaAlteracion_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_datos_deficiencia_alteraciones" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (!empty($array_info_decreto_evento_re[0]->Decreto_calificacion) && $array_info_decreto_evento_re[0]->Decreto_calificacion == 1)
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
                                                            <th style="width: 140px !important;">Título tabla</th>
                                                            <th style="width: 140px !important;">Clase principal (FP)</th>
                                                            <th style="width: 140px !important;">CFM1</th>
                                                            <th style="width: 140px !important;">CFM2</th>
                                                            <th style="width: 140px !important;">FU</th>
                                                            <th style="width: 140px !important;">CAT</th>
                                                            <th style="width: 140px !important;">Clase final</th>
                                                            <th style="width: 140px !important;">DX principal</th>
                                                            <th style="width: 140px !important;">MSD</th>
                                                            <th style="width: 140px !important;">Deficiencia</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_porfactor"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($array_datos_deficiencias_alteraciones as $deficiencias_ateraciones)
                                                            <tr class="fila_deficienaAlteracion_{{$deficiencias_ateraciones->Id_Deficiencia}}" id="datos_deficiencia_alteraciones">
                                                                <td>{{$deficiencias_ateraciones->Ident_tabla}}</td>
                                                                <td>{{$deficiencias_ateraciones->Nombre_tabla}}</td>
                                                                <td>{{$deficiencias_ateraciones->FP}}</td>
                                                                <td>{{$deficiencias_ateraciones->CFM1}}</td>
                                                                <td>{{$deficiencias_ateraciones->CFM2}}</td>
                                                                <td>{{$deficiencias_ateraciones->FU}}</td>
                                                                <td>{{$deficiencias_ateraciones->CAT}}</td>
                                                                <td>{{$deficiencias_ateraciones->Clase_Final}}</td>
                                                                <td>
                                                                    @if ($deficiencias_ateraciones->Dx_Principal == 'Si')
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}" checked>
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="NoDxPrincipal_deficiencia_alteraciones">
                                                                    @else
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}">
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="SiDxPrincipal_deficiencia_alteraciones">
                                                                    @endif
                                                                </td>
                                                                <td>{{$deficiencias_ateraciones->MSD}}</td>
                                                                <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencia_alteraciones{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficienaAlteracion_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                        @endforeach
                                                        @foreach ($array_datos_deficiencias_alteracionesre as $deficiencias_ateracionesre)
                                                            <tr class="fila_deficienaAlteracion_{{$deficiencias_ateracionesre->Id_Deficiencia}}" id="datos_deficiencia_alteraciones">
                                                                <td>{{$deficiencias_ateracionesre->Ident_tabla}}</td>
                                                                <td>{{$deficiencias_ateracionesre->Nombre_tabla}}</td>
                                                                <td>{{$deficiencias_ateracionesre->FP}}</td>
                                                                <td>{{$deficiencias_ateracionesre->CFM1}}</td>
                                                                <td>{{$deficiencias_ateracionesre->CFM2}}</td>
                                                                <td>{{$deficiencias_ateracionesre->FU}}</td>
                                                                <td>{{$deficiencias_ateracionesre->CAT}}</td>
                                                                <td>{{$deficiencias_ateracionesre->Clase_Final}}</td>
                                                                <td>
                                                                    @if ($deficiencias_ateracionesre->Dx_Principal == 'Si')
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateracionesre->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateracionesre->Id_Deficiencia}}" checked>
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="NoDxPrincipal_deficiencia_alteraciones">
                                                                    @else
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateracionesre->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateracionesre->Id_Deficiencia}}">
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="SiDxPrincipal_deficiencia_alteraciones">
                                                                    @endif
                                                                </td>
                                                                <td>{{$deficiencias_ateracionesre->MSD}}</td>
                                                                <td>{{$deficiencias_ateracionesre->Deficiencia}}</td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencia_alteraciones{{$deficiencias_ateracionesre->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateracionesre->Id_Deficiencia}}" data-clase_fila="fila_deficienaAlteracion_{{$deficiencias_ateracionesre->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_datos_deficiencia_alteraciones" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
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
                                                            <th style="width: 140px !important;">Título tabla</th>
                                                            <th style="width: 140px !important;">Clase principal (FP)</th>
                                                            <th style="width: 140px !important;">CFM1</th>
                                                            <th style="width: 140px !important;">CFM2</th>
                                                            <th style="width: 140px !important;">FU</th>
                                                            <th style="width: 140px !important;">CAT</th>
                                                            <th style="width: 140px !important;">Clase final</th>
                                                            <th style="width: 140px !important;">DX principal</th>
                                                            <th style="width: 140px !important;">MSD</th>
                                                            <th style="width: 140px !important;">Deficiencia</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_deficiencia_porfactor"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($array_datos_deficiencias_alteraciones as $deficiencias_ateraciones)
                                                            <tr class="fila_deficienaAlteracion_{{$deficiencias_ateraciones->Id_Deficiencia}}" id="datos_deficiencia_alteraciones">
                                                                <td>{{$deficiencias_ateraciones->Ident_tabla}}</td>
                                                                <td>{{$deficiencias_ateraciones->Nombre_tabla}}</td>
                                                                <td>{{$deficiencias_ateraciones->FP}}</td>
                                                                <td>{{$deficiencias_ateraciones->CFM1}}</td>
                                                                <td>{{$deficiencias_ateraciones->CFM2}}</td>
                                                                <td>{{$deficiencias_ateraciones->FU}}</td>
                                                                <td>{{$deficiencias_ateraciones->CAT}}</td>
                                                                <td>{{$deficiencias_ateraciones->Clase_Final}}</td>
                                                                <td>
                                                                    @if ($deficiencias_ateraciones->Dx_Principal == 'Si')
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}" checked>
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="NoDxPrincipal_deficiencia_alteraciones">
                                                                    @else
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}">
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="SiDxPrincipal_deficiencia_alteraciones">
                                                                    @endif
                                                                </td>
                                                                <td>{{$deficiencias_ateraciones->MSD}}</td>
                                                                <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencia_alteraciones{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficienaAlteracion_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_datos_deficiencia_alteraciones" theme="info" label="Guardar"/>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card-info columna_row1_auditivo" @if ($decreto_1507=='1' && !empty($datos_decreto[0]->Decreto_calificacion) && $datos_decreto[0]->Decreto_calificacion <> 2 && $datos_decreto[0]->Decreto_calificacion <> 3) style="display:block" @else style="display:none" @endif>                            
                            <a href="#" id="btn_abrir_modal_auditivo" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal"
                                @if (count($array_agudeza_Auditiva) > 0 && count($array_agudeza_Auditivare) == 0)
                                    style="cursor: not-allowed;"
                                @elseif(count($array_agudeza_Auditivare) > 0)
                                    style="cursor: not-allowed;"
                                @else
                                    data-target="#modal_grilla_auditivo"
                                @endif
                                >
                                <i class="fas fa-plus-circle text-info"></i> <strong>Agudeza auditiva</strong>                                
                            </a>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Tabla 9.3 Deficiencia por Alteraciones del Sistema Auditivo</h5>
                            </div>
                            <div class="card-body">                                
                                <div class="alert d-none" id="eliminar_agudeza_auditiva" role="alert">
                                </div>
                                <div class="table-responsive">
                                    <table id="listado_Agudeza_auditiva" class="table table-striped table-bordered" width="100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>Deficiencia Monoaural Izquierda</th>
                                                <th>Deficiencia Monoaural Derecha</th>
                                                <th>Deficiencia Binaural</th>
                                                <th>Adicion por Tinnitus</th>
                                                <th>Dx principal</th>
                                                <th>Deficiencia</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($array_agudeza_Auditiva as $agudeza_auditiva)
                                                <tr class="fila_agudeza_{{$agudeza_auditiva->Id_Agudeza_auditiva}}" id="datos_agudeza_visual">
                                                    <td>{{$agudeza_auditiva->Deficiencia_monoaural_izquierda}}</td>
                                                    <td>{{$agudeza_auditiva->Deficiencia_monoaural_derecha}}</td>
                                                    <td>{{$agudeza_auditiva->Deficiencia_binaural}}</td>
                                                    <td>{{$agudeza_auditiva->Adicion_tinnitus}}</td>
                                                    <td>
                                                        @if ($agudeza_auditiva->Dx_Principal == 'Si') 
                                                            <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_auditiva" id="dx_principal_deficiencia_auditiva_{{$agudeza_auditiva->Id_Agudeza_auditiva}}" data-id_fila_dx_auditiva="{{$agudeza_auditiva->Id_Agudeza_auditiva}}" checked>
                                                            <input hidden="hidden" type="text" name="banderaDxPrincipal" id="banderaDxPrincipal" value="NoDxPrincipal">
                                                        @else
                                                            <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_auditiva" id="dx_principal_deficiencia_auditiva_{{$agudeza_auditiva->Id_Agudeza_auditiva}}" data-id_fila_dx_auditiva="{{$agudeza_auditiva->Id_Agudeza_auditiva}}">
                                                            <input hidden="hidden" type="text" name="banderaDxPrincipal" id="banderaDxPrincipal" value="SiDxPrincipal">
                                                        @endif
                                                    </td>
                                                    <td>{{$agudeza_auditiva->Deficiencia}}</td>
                                                    <td>
                                                        <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_examen_fila_agudeza{{$agudeza_auditiva->Id_Agudeza_auditiva}}" data-id_fila_quitar="{{$agudeza_auditiva->Id_Agudeza_auditiva}}" data-clase_fila="fila_agudeza_{{$agudeza_auditiva->Id_Agudeza_auditiva}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                    </td>
                                                </tr>   
                                            @endforeach
                                            @foreach ($array_agudeza_Auditivare as $agudeza_auditivare)
                                                <tr class="fila_agudeza_{{$agudeza_auditivare->Id_Agudeza_auditiva}}" id="datos_agudeza_visual">
                                                    <td>{{$agudeza_auditivare->Deficiencia_monoaural_izquierda}}</td>
                                                    <td>{{$agudeza_auditivare->Deficiencia_monoaural_derecha}}</td>
                                                    <td>{{$agudeza_auditivare->Deficiencia_binaural}}</td>
                                                    <td>{{$agudeza_auditivare->Adicion_tinnitus}}</td>
                                                    <td>
                                                        @if ($agudeza_auditivare->Dx_Principal == 'Si') 
                                                            <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_auditiva" id="dx_principal_deficiencia_auditiva_{{$agudeza_auditivare->Id_Agudeza_auditiva}}" data-id_fila_dx_auditiva="{{$agudeza_auditivare->Id_Agudeza_auditiva}}" checked>
                                                            <input hidden="hidden" type="text" name="banderaDxPrincipal" id="banderaDxPrincipal" value="NoDxPrincipal">
                                                        @else
                                                            <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_auditiva" id="dx_principal_deficiencia_auditiva_{{$agudeza_auditivare->Id_Agudeza_auditiva}}" data-id_fila_dx_auditiva="{{$agudeza_auditivare->Id_Agudeza_auditiva}}">
                                                            <input hidden="hidden" type="text" name="banderaDxPrincipal" id="banderaDxPrincipal" value="SiDxPrincipal">
                                                        @endif
                                                    </td>
                                                    <td>{{$agudeza_auditivare->Deficiencia}}</td>
                                                    <td>
                                                        <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_examen_fila_agudeza{{$agudeza_auditivare->Id_Agudeza_auditiva}}" data-id_fila_quitar="{{$agudeza_auditivare->Id_Agudeza_auditiva}}" data-clase_fila="fila_agudeza_{{$agudeza_auditivare->Id_Agudeza_auditiva}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                    </td>
                                                </tr>   
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-info columna_row1_visual"  @if ($decreto_1507=='1' && !empty($datos_decreto[0]->Decreto_calificacion) && $datos_decreto[0]->Decreto_calificacion <> 2 && $datos_decreto[0]->Decreto_calificacion <> 3) style="display:block" @else style="display:none" @endif>
                            <a href="javascript:void(0);" id="btn_abrir_modal_agudeza" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" 
                                @if (count($hay_agudeza_visual) > 0 && count($hay_agudeza_visualre) == 0)
                                    style="cursor:not-allowed"
                                @elseif (count($hay_agudeza_visualre) > 0)
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
                                <div class="alert d-none" id="dx_visual" role="alert">
                                </div>
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
                                            @if (count($hay_agudeza_visual) > 0)
                                                @foreach ($hay_agudeza_visual as $info_agudeza)
                                                    <tr class="fila_visual_agudeza_{{$info_agudeza->Id_agudeza_re}}">
                                                        <td>{{$info_agudeza->Agudeza_Ojo_Izq_re}}</td>
                                                        <td>{{$info_agudeza->Agudeza_Ojo_Der_re}}</td>
                                                        <td>{{$info_agudeza->Agudeza_Ambos_Ojos_re}}</td>
                                                        <td>{{$info_agudeza->PAVF_re}}</td>
                                                        <td>{{$info_agudeza->DAV_re}}</td>
                                                        <td>{{$info_agudeza->Campo_Visual_Ojo_Izq_re}}</td>
                                                        <td>{{$info_agudeza->Campo_Visual_Ojo_Der_re}}</td>
                                                        <td>{{$info_agudeza->Campo_Visual_Ambos_Ojos_re}}</td>
                                                        <td>{{$info_agudeza->CVF_re}}</td>
                                                        <td>{{$info_agudeza->DCV_re}}</td>
                                                        <td>{{$info_agudeza->DSV_re}}</td>
                                                        <td>
                                                            @if ($info_agudeza->Dx_Principal_re == 'Si')
                                                                <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_visual" id="dx_principal_deficiencia_visual_{{$info_agudeza->Id_agudeza_re}}" data-id_fila_dx_visual="{{$info_agudeza->Id_agudeza_re}}" checked>
                                                                <input hidden="hidden" type="text" name="banderaDxPrincipal_visual" id="banderaDxPrincipal_visual" value="NoDxPrincipal">
                                                            @else
                                                                <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_visual" id="dx_principal_deficiencia_visual_{{$info_agudeza->Id_agudeza_re}}" data-id_fila_dx_visual="{{$info_agudeza->Id_agudeza_re}}">
                                                                <input hidden="hidden" type="text" name="banderaDxPrincipal_visual" id="banderaDxPrincipal_visual" value="SiDxPrincipal">
                                                            @endif
                                                        </td>
                                                        <td>{{$info_agudeza->Deficiencia_re}}</td>
                                                        <td>
                                                            <div style="text-align:center;">
                                                                <a href="javascript:void(0);" id="btn_editar_agudeza_visual" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modal_editar_agudeza_visual">
                                                                    <i class="fa fa-pen text-primary"></i>
                                                                </a>
                                                                <a href="javascript:void(0);" id="btn_remover_fila_{{$info_agudeza->Id_agudeza_re}}" data-fila_agudeza="fila_visual_agudeza_{{$info_agudeza->Id_agudeza_re}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach                                                
                                            @else
                                                @foreach ($hay_agudeza_visualre as $info_agudezare)
                                                    <tr class="fila_visual_agudeza_{{$info_agudezare->Id_agudeza_re}}">
                                                        <td>{{$info_agudezare->Agudeza_Ojo_Izq_re}}</td>
                                                        <td>{{$info_agudezare->Agudeza_Ojo_Der_re}}</td>
                                                        <td>{{$info_agudezare->Agudeza_Ambos_Ojos_re}}</td>
                                                        <td>{{$info_agudezare->PAVF_re}}</td>
                                                        <td>{{$info_agudezare->DAV_re}}</td>
                                                        <td>{{$info_agudezare->Campo_Visual_Ojo_Izq_re}}</td>
                                                        <td>{{$info_agudezare->Campo_Visual_Ojo_Der_re}}</td>
                                                        <td>{{$info_agudezare->Campo_Visual_Ambos_Ojos_re}}</td>
                                                        <td>{{$info_agudezare->CVF_re}}</td>
                                                        <td>{{$info_agudezare->DCV_re}}</td>
                                                        <td>{{$info_agudezare->DSV_re}}</td>
                                                        <td>
                                                            @if ($info_agudezare->Dx_Principal_re == 'Si')
                                                                <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_visual" id="dx_principal_deficiencia_visual_{{$info_agudezare->Id_agudeza_re}}" data-id_fila_dx_visual="{{$info_agudezare->Id_agudeza_re}}" checked>
                                                                <input hidden="hidden" type="text" name="banderaDxPrincipal_visual" id="banderaDxPrincipal_visual" value="NoDxPrincipal">
                                                            @else
                                                                <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_visual" id="dx_principal_deficiencia_visual_{{$info_agudezare->Id_agudeza_re}}" data-id_fila_dx_visual="{{$info_agudezare->Id_agudeza_re}}">
                                                                <input hidden="hidden" type="text" name="banderaDxPrincipal_visual" id="banderaDxPrincipal_visual" value="SiDxPrincipal">
                                                            @endif
                                                        </td>
                                                        <td>{{$info_agudezare->Deficiencia_re}}</td>
                                                        <td>
                                                            <div style="text-align:center;">
                                                                <a href="javascript:void(0);" id="btn_editar_agudeza_visual2" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modal_editar_agudeza_visual">
                                                                    <i class="fa fa-pen text-primary"></i>
                                                                </a>
                                                                <a href="javascript:void(0);" id="btn_remover_fila_{{$info_agudezare->Id_agudeza_re}}" data-fila_agudeza="fila_visual_agudeza_{{$info_agudezare->Id_agudeza_re}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach                                                
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>                            
                        </div> 
                        {{-- <!-- Total Deficiencia (50%) -->
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="suma_combinada">Suma Combinada:</label>   
                                        @if(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 2)
                                            <input type="text" id="suma_combinada" name="suma_combinada" value="0" readonly=""> 
                                        @elseif(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3 && !empty($array_info_decreto_evento[0]->Suma_combinada))
                                            <input type="number" id="suma_combinada" name="suma_combinada" value="{{$array_info_decreto_evento[0]->Suma_combinada}}" readonly> 
                                        @elseif(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3 && empty($array_info_decreto_evento[0]->Suma_combinada))
                                            <input type="number" id="suma_combinada" name="suma_combinada"> 
                                        @elseif(!empty($deficiencias[0]))
                                            <input type="text" id="suma_combinada" name="suma_combinada" value="{{round($deficiencias[0], 2)}}" readonly="">                                             
                                        @else
                                            <input type="text" id="suma_combinada" name="suma_combinada" value="0" readonly=""> 
                                        @endif                 
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="Total_Deficiencia50">Total Deficiencia (50%):</label>    
                                        @if(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 2)
                                            <input type="number" id="Total_Deficiencia50" name="Total_Deficiencia50" value="0" readonly="">      
                                        @elseif(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3 && !empty($array_info_decreto_evento[0]->Total_Deficiencia50))
                                            <input type="number" id="Total_Deficiencia50" name="Total_Deficiencia50" value="{{$array_info_decreto_evento[0]->Total_Deficiencia50}}" readonly> 
                                        @elseif(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3 && empty($array_info_decreto_evento[0]->Total_Deficiencia50))
                                            <input type="number" id="Total_Deficiencia50" name="Total_Deficiencia50">              
                                        @elseif (!empty($TotalDeficiencia50))
                                            <input type="text" id="Total_Deficiencia50" name="Total_Deficiencia50" value="{{round($TotalDeficiencia50, 2)}}" readonly="">                                             
                                        @else
                                            <input type="text" id="Total_Deficiencia50" name="Total_Deficiencia50" value="0" readonly="">                                             
                                        @endif                
                                    </div>
                                </div>
                            </div>                                
                        </div>  --}}                       
                    </div>                              
                    </div>
                </div>
            </div>       
        </div>   
    @else
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">¡No se puede realizar Recalificación o Revisión Pensión!</h4>
            <p>El Evento no tiene Calificación Técnica y el motivo de solicitud para la Calificación Técnica no se encuentra en primera oportunidad, el motivo de solicitud esta en {{$array_datos_motivo_solicitud[0]->Nombre_solicitud}}.</p>
            <hr>
            <p class="mb-0">Debe actualizar el motivo de solicitud para la Calificación Técnica o actualizarlo en la Calificación Técnica cuando se creé.</p>
        </div>     
    @endif
    <form action="{{route('calificacionPCL')}}" id="formularioEnvio" method="POST">            
        @csrf
        <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="{{$array_datos_RecalificacionPcl[0]->ID_evento}}">
        <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="{{$array_datos_RecalificacionPcl[0]->Id_Asignacion}}">
        <button type="submit" id="botonEnvioVista" style="display:none !important;"></button>
    </form>

    @if (count($array_agudeza_Auditivare) == 0)
        @include('coordinador.modalagudezaAuditivaRe')
    {{-- @else
        @include('coordinador.modalagudezaAuditivaEdicion'); --}}                 
    @endif    
    @if (count($hay_agudeza_visual) == 0)         
        {{-- MODAL NUEVA DEFICIENCIA VISUAL --}}
        @include('coordinador.campimetriaRePCL')
    @elseif(count($hay_agudeza_visual) > 0)
        {{-- MODAL EDICIÓN DEFICIENCIA VISUAL --}}
        @include('coordinador.edicionCampimetriaRePCL')
    @elseif(count($hay_agudeza_visualre) == 0)
        {{-- MODAL EDICIÓN DEFICIENCIA VISUAL --}}
        @include('coordinador.campimetriaRePCL')
    @elseif(count($hay_agudeza_visualre) > 0)
        {{-- MODAL EDICIÓN DEFICIENCIA VISUAL --}}
        @include('coordinador.edicionCampimetriaRePCL')
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
                '<select id="lista_origenCie10_fila_'+contador_cie10+'" class="custom-select lista_origenCie10_fila_'+contador_cie10+'" name="lista_origenCie10"><option></option></select>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie10+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
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

        $(document).on('click', "a[id^='btn_remover_diagnosticos_moticalifi']", function(){
            var nombre_cie10_fila = $(this).data("clase_fila");
            listado_diagnostico_cie10.row("."+nombre_cie10_fila).remove().draw();
        });

        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE AGUDEZA AUDITIVA
        $(".centrar").css('text-align', 'center');
        var listado_Agudeza_auditiva = $('#listado_Agudeza_auditiva').DataTable({
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

        autoAdjustColumns(listado_Agudeza_auditiva);

        $(document).on('click', '#btn_remover_examen_fila', function(){
            var nombre_exame_fila = $(this).data("fila");
            listado_Agudeza_auditiva.row("."+nombre_exame_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_examen_fila_agudeza']", function(){
            var nombre_exame_fila = $(this).data("clase_fila");
            listado_Agudeza_auditiva.row("."+nombre_exame_fila).remove().draw();
        });

        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DEFICIENCIAS ALTERACIONES DECRETO CERO 
        $(document).ready(function() {
            $(".centrar").css('text-align', 'center');
            if ($('#listado_deficiencias_decretoCero').length > 0) {
                // Si existe, ejecutar el código
                var listado_deficiencias_decretoCero = $('#listado_deficiencias_decretoCero').DataTable({
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
        
                autoAdjustColumns(listado_deficiencias_decretoCero);
                //listado_deficiencias_alteraciones_decretoCero.columns.adjust();
                var contador_decretocero = 0;
                $('#btn_agregar_deficiencia_decretoceroFila').click(function(){
                    $('#guardar_deficiencias_DecretoCero').removeClass('d-none');
        
                    contador_decretocero = contador_decretocero + 1;
                    var nueva_fila_decretoCero = [
                        '<select id="lista_tabla_'+contador_decretocero+'" class="custom-select lista_tabla_'+contador_decretocero+'" name="lista_tabla"><option></option></select>',
                        '<div id="titulotabla_'+contador_decretocero+'"></div>',       
                        '<div id="deficienciaDecreto_'+contador_decretocero+'">0</div>',
                        '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficienciaDecretoCero" class="text-info" data-fila="fila_'+contador_decretocero+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                        'fila_'+contador_decretocero
                    ];
        
                    var agregar_deficiencia_decretoCero = listado_deficiencias_decretoCero.row.add(nueva_fila_decretoCero).draw().node();
                    $(agregar_deficiencia_decretoCero).addClass('fila_'+contador_decretocero);
                    $(agregar_deficiencia_decretoCero).attr("id", 'fila_'+contador_decretocero);
        
                    // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
                    funciones_elementos_fila_deficienciasDecretocero(contador_decretocero);
                });
                    
                $(document).on('click', '#btn_remover_deficienciaDecretoCero', function(){
                    var nombre_decretoCero = $(this).data("fila");
                    listado_deficiencias_decretoCero.row("."+nombre_decretoCero).remove().draw();
                });
        
                $(document).on('click', "a[id^='btn_remover_deficiencias_decretocero_']", function(){
                    var nombre_decretoCero = $(this).data("clase_fila");
                    listado_deficiencias_decretoCero.row("."+nombre_decretoCero).remove().draw();
                });
            }
        });
        
        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DEFICIENCIAS ALTERACIONES DECRETO TRES
        $(document).ready(function() {
            $(".centrar").css('text-align', 'center');
            if ($('#listado_deficiencias_decreto_tres').length > 0) {
                // Si existe, ejecutar el código
                var listado_deficiencias_decreto_tres = $('#listado_deficiencias_decreto_tres').DataTable({
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
                autoAdjustColumns(listado_deficiencias_decreto_tres);
                //listado_deficiencias_decreto_tres.columns.adjust();  
                var contador_decreto3 = 0;
                $('#btn_agregar_deficiencia_decretotresfila').click(function(){
                    $('#guardar_deficiencias_Decreto3').removeClass('d-none');
        
                    contador_decreto3 = contador_decreto3 + 1;
                    var nueva_fila_decreto3 = [
                        '<input type="text" class="form-control" name="tabladecreto3_" id="tabladecreto3_'+contador_decreto3+'">',
                        '<input type="text" class="form-control"  name="tablatitulodecreto3_" id="tablatitulodecreto3_'+contador_decreto3+'">',
                        '<input type="number" class="form-control"  name="deficienciadecreto3_" id="deficienciadecreto3_'+contador_decreto3+'">',
                        '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficienciaDecreto3" class="text-info" data-fila="fila_'+contador_decreto3+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                        'fila_'+contador_decreto3
                    ];
        
                    var agregar_deficiencia_decreto3 = listado_deficiencias_decreto_tres.row.add(nueva_fila_decreto3).draw().node();
                    $(agregar_deficiencia_decreto3).addClass('fila_'+contador_decreto3);
                    $(agregar_deficiencia_decreto3).attr("id", 'fila_'+contador_decreto3);
        
                });
        
               
                $(document).on('click', '#btn_remover_deficienciaDecreto3', function(){
                    var nombre_decreto3 = $(this).data("fila");
                    listado_deficiencias_decreto_tres.row("."+nombre_decreto3).remove().draw();
                });
        
                $(document).on('click', "a[id^='btn_remover_deficiencias_decreto3_']", function(){
                    var nombre_decreto3 = $(this).data("clase_fila");
                    listado_deficiencias_decreto_tres.row("."+nombre_decreto3).remove().draw();
                });
            }
    
        });
        
        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE DEFICIENCIA POR FACTOR
        //Falta agregar función


    });
</script>
<script type="text/javascript" src="/js/funciones_helpers.js"></script>
<script type="text/javascript" src="/js/recalificacionpcl.js"></script>
{{-- JS: Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores --}}
<script type="text/javascript" src="/js/datatable_deficiencias_alteraciones_sistemas_re.js"></script>
<script type="text/javascript" src="/js/agudeza_auditiva_re.js"></script>
{{-- JS: DATATABLE AGUDEZA VISUAL --}}
<script type="text/javascript" src="/js/datatable_agudeza_visual_re.js"></script>
@stop