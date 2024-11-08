@extends('adminlte::page')
@section('title', 'Calificación Técnica PCL')
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
                <?php else:?>
                    <a href="{{route("bandejaPCL")}}" class="btn btn-info" type="button"><i class="fas fa-archive"></i> Regresar Bandeja</a>
                <?php endif ?>
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
            <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form id="form_CaliTecDecreto" method="POST">
                        <!--Calificacón PCL-->
                        <div class="card-body" id="id_calificacion_pcl">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="origen_firme">Origen en firme<span style="color: red;">(*)</span></label>
                                        <select class="custom-select origen_firme" name="origen_firme" id="origen_firme" required>
                                            @if (!empty($datos_demos[0]->Origen_firme))
                                                <option value="{{$datos_demos[0]->Origen_firme}}" selected>{{$datos_demos[0]->Origen}}</option>
                                            @else
                                                <option value="">Seleccione una opción</option>
                                            @endif
                                        </select>
                                        <input type="hidden" name="NombreUsuario" id="NombreUsuario" value="{{$user->name}}">
                                        <input type="hidden" name="Id_Evento_decreto" id="Id_Evento_decreto" value="{{$array_datos_calificacionPclTecnica[0]->ID_evento}}">
                                        <input type="hidden" name="Id_Proceso_decreto" id="Id_Proceso_decreto" value="{{$array_datos_calificacionPclTecnica[0]->Id_proceso}}">
                                        <input type="hidden" name="Id_Asignacion_decreto" id="Id_Asignacion_decreto" value="{{$array_datos_calificacionPclTecnica[0]->Id_Asignacion}}">                                        
                                        <input type="hidden" class="form-control" id="conteo_listado_examenes_interconsulta" value="{{count($array_datos_examenes_interconsultas)}}">
                                        <input type="hidden" class="form-control" id="conteo_listado_diagnosticos_moticalifi" value="{{count($array_datos_diagnostico_motcalifi)}}">
                                        <input type="hidden" class="form-control" id="conteo_listado_agudeza_auditiva" value="{{count($array_agudeza_Auditiva)}}">
                                        <input type="hidden" class="form-control" id="conteo_listado_deficiencia_alteraciones" value="{{count($array_datos_deficiencias_alteraciones)}}">                                        
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="origen_cobertura">Cobertura<span style="color: red;">(*)</span></label>
                                        <select class="custom-select origen_cobertura" name="origen_cobertura" id="origen_cobertura" required>
                                            @if (!empty($datos_demos[0]->Cobertura))
                                                <option value="{{$datos_demos[0]->Cobertura}}" selected>{{$datos_demos[0]->Coberturas}}</option>
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
                                            @if (!empty($datos_demos[0]->Decreto_calificacion))
                                                <option value="{{$datos_demos[0]->Decreto_calificacion}}" selected>{{$datos_demos[0]->Nombre_decreto}}</option>
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
                        if(!empty($datos_demos[0]->Origen_firme) &&  $datos_demos[0]->Origen_firme == '48' && !empty($datos_demos[0]->Cobertura) && $datos_demos[0]->Cobertura == '50'):
                            $decreto_1507='1';
                        else:
                            $decreto_1507='0';                            
                        endif
                        ?>                        
                        {{-- <div class="d-none" id="div_calificacion_Pcl">   --}}               
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
                                            <br>
                                            {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                            <a onclick="document.getElementById('botonVerEdicionEvento').click();" style="cursor:pointer; font-weight: bold;" class="btn text-info" type="button"><?php if(!empty($array_datos_calificacionPclTecnica[0]->ID_evento)){echo $array_datos_calificacionPclTecnica[0]->ID_evento;}?></a>
                                            <input type="hidden" name="id_evento" id="id_evento" value="{{$array_datos_calificacionPclTecnica[0]->ID_evento}}">
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
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="fecha_dictamen">Fecha Dictamen</label>
                                            <input type="text" class="form-control" name="fecha_dictamen" id="fecha_dictamen" value="<?php if(!empty($array_comite_interdisciplinario[0]->F_visado_comite)){echo $array_comite_interdisciplinario[0]->F_visado_comite;}else{echo now()->format('Y-m-d');}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="numero_dictamen">N° Dictamen</label>
                                            <input type="text" class="form-control" name="numero_dictamen" id="numero_dictamen" value="{{$array_datos_calificacionPclTecnica[0]->Consecutivo_dictamen}}" disabled>                                                                                                                                        
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="motivo_solicitud">Motivo Solicitud<span style="color: red;">(*)</span></label>
                                            <select class="custom-select motivo_solicitud" name="motivo_solicitud" id="motivo_solicitud" style="width: 100%;" required>
                                                @if (!empty($motivo_solicitud_actual[0]->Id_motivo_solicitud))
                                                    <option value="{{$motivo_solicitud_actual[0]->Id_motivo_solicitud}}" selected>{{$motivo_solicitud_actual[0]->Nombre_solicitud}}</option>
                                                @else
                                                    <option value="">Seleccione una opción</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">                                                
                                            <label for="modalidad_calificacion">Modalidad Calificación<span style="color: red;">(*)</span></label>
                                            <select class="modalidad_calificacion custom-select" name="modalidad_calificacion" id="modalidad_calificacion" required>
                                                @if ($Modalidad_calificacion)
                                                    <option value="{{$Modalidad_calificacion[0]->Modalidad_calificacion}}" selected>{{$Modalidad_calificacion[0]->Nombre_modalidad_calificacion}}</option>
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
                                            <input type="text" class="form-control" name="nombre_apoderado" id="nombre_apoderado" value="<?php if (!empty($datos_apoderado_actual[0]->Nombre_apoderado)) {echo $datos_apoderado_actual[0]->Nombre_apoderado; } ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="identificacion_apoderado">N° identificación apoderado</label>
                                            <input type="text" class="form-control" name="identificacion_apoderado" id="identificacion_apoderado" value="<?php if (!empty($datos_apoderado_actual[0]->Nro_identificacion_apoderado)) {echo $datos_apoderado_actual[0]->Nro_identificacion_apoderado; } ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Relacion de documetos-->
                        <div class="card-info columna_row1_documentos" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif >
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Relación de documentos / Exámenes fisico - (Descripción)</h5>
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
                                            @if (empty($array_info_decreto_evento[0]->Otros_relacion_doc))
                                                <textarea class="form-control" name="descripcion_otros" id="descripcion_otros" cols="30" rows="5" style="resize: none;"></textarea>                                                
                                            @else
                                                <textarea class="form-control" name="descripcion_otros" id="descripcion_otros" cols="30" rows="5" style="resize: none;">{{$array_info_decreto_evento[0]->Otros_relacion_doc}}</textarea>                                                
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
                                            @if (empty($array_info_decreto_evento[0]->Descripcion_enfermedad_actual))
                                                <textarea class="form-control" name="descripcion_enfermedad" id="descripcion_enfermedad" cols="30" rows="5" style="resize: none;" ></textarea>                                                
                                            @else
                                                <textarea class="form-control" name="descripcion_enfermedad" id="descripcion_enfermedad" cols="30" rows="5" style="resize: none;" >{{$array_info_decreto_evento[0]->Descripcion_enfermedad_actual}}</textarea>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="dominancia">Dominancia</label>
                                            {{-- <input type="text" class="form-control" name="dominancia" id="dominancia" value="<?php if(!empty($motivo_solicitud_actual[0]->Nombre_dominancia)){echo $motivo_solicitud_actual[0]->Nombre_dominancia;}?>" disabled> --}}
                                            <input type="hidden" name="id_afiliado" id="id_afiliado" value="{{$array_datos_calificacionPclTecnica[0]->Id_Afiliado}}">
                                            <input type="hidden" id="bd_id_dominancia" value="<?php if(!empty($motivo_solicitud_actual[0]->Id_dominancia)){echo $motivo_solicitud_actual[0]->Id_dominancia;}?>">
                                            <select class="custom-select dominancia" name="dominancia" id="dominancia"></select>
                                        </div>
                                    </div>
                                </div>
                                <!--Guardar Historial Enfermedad-->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                                @if (empty($array_info_decreto_evento[0]->ID_Evento))
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de diligenciar los formularios anteriores dar clic en el botón Guardar.                                                    
                                                @else
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Si realizo algún cambio en los formularios anteriores dar clic en el botón Actualizar.
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            @if (empty($array_info_decreto_evento[0]->ID_Evento))
                                                <input type="submit" id="GuardarDecreto" name="GuardarDecreto" class="btn btn-info" value="Guardar">                                                
                                                <input hidden="hidden" type="text" id="bandera_decreto_guardar_actualizar" value="Guardar">
                                            @else
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
                        {{-- </div> --}}
                        <div class="card-body" id="botonNoDecrecto">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        @if (empty($datos_demos[0]->ID_Evento))                                            
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
                        {{-- <div class="d-none" id="div_calificacion_Pcl2"> --}}
                    </form>
                    <!-- examen interconsulta-->
                    <div class="card-info columna_row1_interconsulta" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Exámenes e interconsultas</h5>
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
                                            que diligencie en su totalidad los campos. Y en el campo <b>Deficiencia(s) motivo de la calificación/
                                                Condiciones de salud</b> solo acepta <b>Máximo 100 caracteres</b>
                                        </div>
                                        <div class="alert d-none" id="resultado_insercion_cie10" role="alert">
                                        </div>
                                        <div class="table-responsive">
                                            <table id="listado_diagnostico_cie10" class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>CIE10</th>
                                                        <th>Nombre CIE10</th>
                                                        <th>Lateralidad Dx</th>
                                                        <th>Origen CIE10</th>
                                                        <th>Dx principal</th>
                                                        <th>Deficiencia(s) motivo de la calificación/<br>Condiciones de salud</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($array_datos_diagnostico_motcalifi as $diagnostico)
                                                    <tr class="fila_diagnosticos_{{$diagnostico->Id_Diagnosticos_motcali}}" id="datos_diagnostico">
                                                        <td>{{$diagnostico->Codigo}}</td>
                                                        <td>{{$diagnostico->Nombre_CIE10}}</td>
                                                        <td>{{$diagnostico->Nombre_parametro_lateralidad}}</td>
                                                        <td>{{$diagnostico->Nombre_parametro}}</td>
                                                        <td>
                                                            @if ($diagnostico->Principal == 'Si')
                                                                <input class="scalesR" type="checkbox" name="dx_principal_diganostico" id="dx_principal_diganostico_{{$diagnostico->Id_Diagnosticos_motcali}}" data-id_fila_dx_principal_diagnostico="{{$diagnostico->Id_Diagnosticos_motcali}}" checked>
                                                                <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="NoDxPrincipal_diagnostico">
                                                            @else
                                                                <input class="scalesR" type="checkbox" name="dx_principal_diganostico" id="dx_principal_diganostico_{{$diagnostico->Id_Diagnosticos_motcali}}" data-id_fila_dx_principal_diagnostico="{{$diagnostico->Id_Diagnosticos_motcali}}">
                                                                <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="SiDxPrincipal_diagnostico">
                                                            @endif
                                                        </td>
                                                        <td>{{$diagnostico->Deficiencia_motivo_califi_condiciones}}</td>
                                                        <td>
                                                            <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_moticalifi{{$diagnostico->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnostico->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnostico->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
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
                            @if (!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion <> 3)
                                <h5>Titulo I Calificación / Valoración de las Deficiencias (50%)</h5>                                
                            @else
                                <h5>Libro I Calificación / Valoración de las Deficiencias (50%)</h5>                                                                
                            @endif
                        </div>
                        <br>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores</h5>
                        </div>
                        @if (!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 2)
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
                                                            <td>{{$deficiencias_ateraciones->Total_deficiencia}}</td>
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
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Título II Valoración del Rol Laboral, Rol ocupacional y otras Áreas ocupacionales (50%)</h5>
                            </div>   
                            <div class="card-body">
                                <div class="row" style="text-align: right">
                                    <div class="col-12">
                                        <div class="form-group" style="align-content: center">                                               
                                            <label for="total_rol_areas">Total valoración del Rol Laboral, Rol Ocupacional y otras Áreas ocupacionales(50%):</label>                                            
                                            <input type="text" id="total_rol_areas" name="total_rol_areas" value="0"  readonly="">                                                                                                                                                                                                                                                                                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3)
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
                                                            <td>{{$deficiencias_ateraciones->Total_deficiencia}}</td>
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
                        @elseif (!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 1)
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
                                                            <th>Título tabla</th>
                                                            <th>Clase principal (FP)</th>
                                                            <th>CFM1</th>
                                                            <th>CFM2</th>
                                                            <th>FU</th>
                                                            <th>CAT</th>
                                                            <th>Clase final</th>
                                                            {{-- <th style="width: 140px !important;">DX principal</th> --}}
                                                            <th style="width: 140px !important;">MSD</th>
                                                            <th style="width: 140px !important;">Dominancia</th>
                                                            <th>Deficiencia</th>
                                                            <th style="width: 140px !important;">% Total Deficiencia (F.Balthazar,sin ponderar)</th>                                                            
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
                                                                {{-- <td>
                                                                    @if ($deficiencias_ateraciones->Dx_Principal == 'Si')
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}" checked>
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="NoDxPrincipal_deficiencia_alteraciones">
                                                                    @else
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}">
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="SiDxPrincipal_deficiencia_alteraciones">
                                                                    @endif
                                                                </td> --}}
                                                                <td>{{$deficiencias_ateraciones->MSD}}</td>
                                                                <td>{{$deficiencias_ateraciones->Dominancia}}</td>
                                                                <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                                <td>{{$deficiencias_ateraciones->Total_deficiencia}}</td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencia_alteraciones{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficienaAlteracion_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_datos_deficiencia_alteraciones" theme="info" label="Guardar"/>
                                            <div class="text-center d-none" id="mostrar_barra_guardar_deficiencias">                                
                                                <button class="btn btn-info" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Guardando Deficiencia por alteraciones por favor espere...
                                                </button>
                                            </div>
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
                                                            <th>Tabla</th>
                                                            <th>Título tabla</th>
                                                            <th>Clase principal (FP)</th>
                                                            <th>CFM1</th>
                                                            <th>CFM2</th>
                                                            <th>FU</th>
                                                            <th>CAT</th>
                                                            <th>Clase final</th>
                                                            {{-- <th style="width: 140px !important;">DX principal</th> --}}
                                                            <th style="width: 140px !important;">MSD</th>
                                                            <th style="width: 140px !important;">Dominancia</th>
                                                            <th>Deficiencia</th>
                                                            <th style="width: 140px !important;">% Total Deficiencia (F.Balthazar,sin ponderar)</th>                                                            
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
                                                                {{-- <td>
                                                                    @if ($deficiencias_ateraciones->Dx_Principal == 'Si')
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}" checked>
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="NoDxPrincipal_deficiencia_alteraciones">
                                                                    @else
                                                                        <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_alteraciones" id="dx_principal_deficiencia_alteraciones_{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_dx_principal="{{$deficiencias_ateraciones->Id_Deficiencia}}">
                                                                        <input hidden="hidden" type="text" name="banderaDxPrincipalDA" id="banderaDxPrincipalDA" value="SiDxPrincipal_deficiencia_alteraciones">
                                                                    @endif
                                                                </td> --}}
                                                                <td>{{$deficiencias_ateraciones->MSD}}</td>
                                                                <td>{{$deficiencias_ateraciones->Dominancia}}</td>
                                                                <td>{{$deficiencias_ateraciones->Deficiencia}}</td>
                                                                <td>{{$deficiencias_ateraciones->Total_deficiencia}}</td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_deficiencia_alteraciones{{$deficiencias_ateraciones->Id_Deficiencia}}" data-id_fila_quitar="{{$deficiencias_ateraciones->Id_Deficiencia}}" data-clase_fila="fila_deficienaAlteracion_{{$deficiencias_ateraciones->Id_Deficiencia}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <x-adminlte-button class="mr-auto d-none" id="guardar_datos_deficiencia_alteraciones" theme="info" label="Guardar"/>
                                            <div class="text-center d-none" id="mostrar_barra_guardar_deficiencias">                                
                                                <button class="btn btn-info" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Guardando Deficiencia por alteraciones por favor espere...
                                                </button>   
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card-info columna_row1_auditivo" @if ($decreto_1507=='1' && !empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion <> 2 && $datos_demos[0]->Decreto_calificacion <> 3) style="display:block" @else style="display:none" @endif>                            
                            <a href="javascript:void(0);" id="btn_abrir_modal_auditivo" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal"
                                @if (count($array_agudeza_Auditiva) > 0)
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
                                                {{-- <th>Dx principal</th> --}}
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
                                                    {{-- <td>
                                                        @if ($agudeza_auditiva->Dx_Principal == 'Si') 
                                                            <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_auditiva" id="dx_principal_deficiencia_auditiva_{{$agudeza_auditiva->Id_Agudeza_auditiva}}" data-id_fila_dx_auditiva="{{$agudeza_auditiva->Id_Agudeza_auditiva}}" checked>
                                                            <input hidden="hidden" type="text" name="banderaDxPrincipal" id="banderaDxPrincipal" value="NoDxPrincipal">
                                                        @else
                                                            <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_auditiva" id="dx_principal_deficiencia_auditiva_{{$agudeza_auditiva->Id_Agudeza_auditiva}}" data-id_fila_dx_auditiva="{{$agudeza_auditiva->Id_Agudeza_auditiva}}">
                                                            <input hidden="hidden" type="text" name="banderaDxPrincipal" id="banderaDxPrincipal" value="SiDxPrincipal">
                                                        @endif
                                                    </td> --}}
                                                    <td>{{$agudeza_auditiva->Deficiencia}}</td>
                                                    <td>
                                                        <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_examen_fila_agudeza{{$agudeza_auditiva->Id_Agudeza_auditiva}}" data-id_fila_quitar="{{$agudeza_auditiva->Id_Agudeza_auditiva}}" data-clase_fila="fila_agudeza_{{$agudeza_auditiva->Id_Agudeza_auditiva}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                    </td>
                                                </tr>   
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-info columna_row1_visual"  @if ($decreto_1507=='1' && !empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion <> 2 && $datos_demos[0]->Decreto_calificacion <> 3) style="display:block" @else style="display:none" @endif>
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
                                                {{-- <th>Dx Principal</th> --}}
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
                                                    {{-- <td>
                                                        @if ($info_agudeza->Dx_Principal == 'Si')
                                                            <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_visual" id="dx_principal_deficiencia_visual_{{$info_agudeza->Id_agudeza}}" data-id_fila_dx_visual="{{$info_agudeza->Id_agudeza}}" checked>
                                                            <input hidden="hidden" type="text" name="banderaDxPrincipal_visual" id="banderaDxPrincipal_visual" value="NoDxPrincipal">
                                                        @else
                                                            <input class="scalesR" type="checkbox" name="dx_principal_deficiencia_visual" id="dx_principal_deficiencia_visual_{{$info_agudeza->Id_agudeza}}" data-id_fila_dx_visual="{{$info_agudeza->Id_agudeza}}">
                                                            <input hidden="hidden" type="text" name="banderaDxPrincipal_visual" id="banderaDxPrincipal_visual" value="SiDxPrincipal">
                                                        @endif
                                                    </td> --}}
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
                        <!-- Total Deficiencia (50%) -->
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        @if(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3 && !empty($array_info_decreto_evento[0]->Suma_combinada))
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert" style="text-align: initial;">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para guardar la Suma combinada y el Total Deficiencia (50%) se realiza al momento de guardar el <b>Concepto final del Dictamen Pericial</b>
                                            </div>
                                        @elseif(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3 && empty($array_info_decreto_evento[0]->Suma_combinada))
                                            <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert" style="text-align: initial;">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para guardar la Suma combinada y el Total Deficiencia (50%) se realiza al momento de guardar el <b>Concepto final del Dictamen Pericial</b>
                                            </div>
                                        @endif 
                                    </div>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="suma_combinada">Suma Combinada:</label>   
                                        @if(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 2)
                                            <input type="text" id="suma_combinada" name="suma_combinada" value="0" readonly=""> 
                                        @elseif(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 3 && !empty($array_info_decreto_evento[0]->Suma_combinada))
                                            <input type="number" id="suma_combinada" name="suma_combinada" value="{{$array_info_decreto_evento[0]->Suma_combinada}}"> 
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
                                            <input type="number" id="Total_Deficiencia50" name="Total_Deficiencia50" value="{{$array_info_decreto_evento[0]->Total_Deficiencia50}}"> 
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
                        </div>                        
                    </div>                                                                                                                                                                                    
                    <!-- Valoracion Laboral-->
                    <div class="card-info columna_row1_valoracion_laboral"  @if ($decreto_1507=='1' && !empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion === 1) style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Título II Valoración del Rol Laboral, Rol ocupacional y otras Áreas ocupacionales (50%)</h5>
                        </div>
                        <div class="card-body">
                            <!-- Radios laboralmente activo y Rol ocupacional -->
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="form-check custom-control custom-radio">
                                            @if (count($array_laboralmente_Activo) > 0 && count($array_rol_ocupacional) == 0)                                            
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_laboral" id="laboral_actual" value="Laboralmente_activo" checked>                                                
                                            @else
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_laboral" id="laboral_actual" value="Laboralmente_activo">
                                            @endif                                                                                       
                                            <label class="form-check-label custom-control-label" for="laboral_actual"><strong>Laboralmente Activo</strong></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="form-check custom-control custom-radio">
                                            @if (count($array_rol_ocupacional) > 0 && count($array_laboralmente_Activo) == 0 )                                                
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="rol_ocupa" id="rol_ocupacional" value="Rol_ocupacional" checked>
                                            @else                                                
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="rol_ocupa" id="rol_ocupacional" value="Rol_ocupacional">
                                            @endif 
                                            <label class="form-check-label custom-control-label" for="rol_ocupacional"><strong>Aplicar rol ocupacional</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Mostrar si es laboralmente activo-->
                            <form id="form_laboralmente_activo" action="POST">
                                @csrf
                                <div class="row columna_row1_rol_laboral" style="display:none">
                                    <!--Tabla 1 - Restricciones de rol-->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="identificacion">Tabla 1 - Restricciones de rol</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group" id="activarintervalos">
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
                                                                @if (!empty($array_laboralmente_Activo[0]->Restricciones_rol) && $array_laboralmente_Activo[0]->Restricciones_rol == 0.0)
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_01" value="0.0" checked>                                                                                                                                                                                                                                                                                            
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_01" value="0.0">                                                                                                                                        
                                                                @endif                                                                
                                                                <label class="form-check-label custom-control-label" for="restricciones_rol_01"><strong>0.0</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Restricciones_rol) && $array_laboralmente_Activo[0]->Restricciones_rol == 5.0)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_02" value="5.0" checked>                                                                                                                                        
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_02" value="5.0">                                                                    
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="restricciones_rol_02"><strong>5.0</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Restricciones_rol) && $array_laboralmente_Activo[0]->Restricciones_rol == 10)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_03" value="10" checked>                                                                                                                                        
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_03" value="10">                                                                    
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="restricciones_rol_03"><strong>10</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Restricciones_rol) && $array_laboralmente_Activo[0]->Restricciones_rol == 15)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_04" value="15" checked>                                                                                                                                        
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_04" value="15">                                                                    
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="restricciones_rol_04"><strong>15</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Restricciones_rol) && $array_laboralmente_Activo[0]->Restricciones_rol == 20)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_05" value="20" checked>                                                                                                                                        
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_05" value="20">                                                                    
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="restricciones_rol_05"><strong>20</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Restricciones_rol) && $array_laboralmente_Activo[0]->Restricciones_rol == 25)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_06" value="25" checked>                                                                                                                                        
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="restricion_rol" id="restricciones_rol_06" value="25">                                                                    
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="restricciones_rol_06"><strong>25</strong></label>
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
                                        <div class="form-group" id="activarintervalos2">
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
                                                                @if (!empty($array_laboralmente_Activo[0]->Autosuficiencia_economica) && $array_laboralmente_Activo[0]->Autosuficiencia_economica == 0.0)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_01" value="0.0" checked>                                                                                                                                                                                                            
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_01" value="0.0">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="autosuficiencia_01"><strong>0.0</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Autosuficiencia_economica) && $array_laboralmente_Activo[0]->Autosuficiencia_economica == 1.0)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_02" value="1.0" checked>                                                                                                                                                                                                                
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_02" value="1.0">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="autosuficiencia_02"><strong>1.0</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Autosuficiencia_economica) && $array_laboralmente_Activo[0]->Autosuficiencia_economica == 1.5)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_03" value="1.5" checked>                                                                                                                                                                                                                
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_03" value="1.5">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="autosuficiencia_03"><strong>1.5</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Autosuficiencia_economica) && $array_laboralmente_Activo[0]->Autosuficiencia_economica == 2.0)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_04" value="2.0" checked>                                                                                                                                                                                                            
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_04" value="2.0">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="autosuficiencia_04"><strong>2.0</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Autosuficiencia_economica) && $array_laboralmente_Activo[0]->Autosuficiencia_economica == 2.5)                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_05" value="2.5" checked>                                                                                                                                                                                                                
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="auto_suficiencia" id="autosuficiencia_05" value="2.5">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="autosuficiencia_05"><strong>2.5</strong></label>
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
                                        <div class="form-group" id="activarintervalos3">
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
                                                        @if (!empty($array_laboralmente_Activo[0]->Edad_cronologica_menor) && $array_laboralmente_Activo[0]->Edad_cronologica_menor == 2.5 && empty($array_comite_interdisciplinario[0]->Id_Asignacion))
                                                            <input type="hidden"  id="Edad_Menor" value="{{$array_laboralmente_Activo[0]->Edad_cronologica_menor}}">
                                                        @elseif(!empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 0.5 && empty($array_comite_interdisciplinario[0]->Id_Asignacion) || 
                                                            !empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 1.0 && empty($array_comite_interdisciplinario[0]->Id_Asignacion) ||
                                                            !empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 1.5 && empty($array_comite_interdisciplinario[0]->Id_Asignacion) ||
                                                            !empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 2.0 && empty($array_comite_interdisciplinario[0]->Id_Asignacion) ||
                                                            !empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 2.5 && empty($array_comite_interdisciplinario[0]->Id_Asignacion))
                                                            <input type="hidden"  id="Edad_Mayor" value="{{$array_laboralmente_Activo[0]->Edad_cronologica}}">
                                                        @endif
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">                                                                
                                                                @if (!empty($array_laboralmente_Activo[0]->Edad_cronologica_menor) && $array_laboralmente_Activo[0]->Edad_cronologica_menor == 2.5 && !empty($array_comite_interdisciplinario[0]->Id_Asignacion))                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="cronologica_menor" value="2.5" checked>                                                                                                                                                                                                                
                                                                @elseif(!empty($edad_afiliado) && $edad_afiliado < 18 && empty($array_comite_interdisciplinario[0]->Id_Asignacion))
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="cronologica_menor" value="2.5" checked>
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="cronologica_menor" value="2.5">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="cronologica_menor"><strong>2.5</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 0.5 && !empty($array_comite_interdisciplinario[0]->Id_Asignacion))                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_02" value="0.5" checked>                                                                                                                                                                                                                
                                                                @elseif(!empty($edad_afiliado) && $edad_afiliado >= 18 && $edad_afiliado < 30  && empty($array_comite_interdisciplinario[0]->Id_Asignacion))
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_02" value="0.5" checked>   
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_02" value="0.5">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="edad_cronologica_02"><strong>0.5</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 1.0 && !empty($array_comite_interdisciplinario[0]->Id_Asignacion))                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_03" value="1.0" checked>
                                                                @elseif(!empty($edad_afiliado) && $edad_afiliado >= 30 && $edad_afiliado < 40  && empty($array_comite_interdisciplinario[0]->Id_Asignacion))               
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_03" value="1.0" checked>
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_03" value="1.0">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="edad_cronologica_03"><strong>1.0</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 1.5 && !empty($array_comite_interdisciplinario[0]->Id_Asignacion))                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_04" value="1.5" checked>
                                                                @elseif(!empty($edad_afiliado) && $edad_afiliado >= 40 && $edad_afiliado < 50 && empty($array_comite_interdisciplinario[0]->Id_Asignacion))
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_04" value="1.5" checked>
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_04" value="1.5">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="edad_cronologica_04"><strong>1.5</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 2.0 && !empty($array_comite_interdisciplinario[0]->Id_Asignacion))                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_05" value="2.0" checked>
                                                                @elseif(!empty($edad_afiliado) && $edad_afiliado >= 50 && $edad_afiliado < 60 && empty($array_comite_interdisciplinario[0]->Id_Asignacion))
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_05" value="2.0" checked>
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_05" value="2.0">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="edad_cronologica_05"><strong>2.0</strong></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check custom-control custom-radio">
                                                                @if (!empty($array_laboralmente_Activo[0]->Edad_cronologica) && $array_laboralmente_Activo[0]->Edad_cronologica == 2.5 && !empty($array_comite_interdisciplinario[0]->Id_Asignacion))                                                                    
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_06" value="2.5" checked>
                                                                @elseif(!empty($edad_afiliado) && $edad_afiliado >= 60 && empty($array_comite_interdisciplinario[0]->Id_Asignacion))
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_06" value="2.5" checked>
                                                                @else
                                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="edad_cronologica" id="edad_cronologica_06" value="2.5">
                                                                @endif
                                                                <label class="form-check-label custom-control-label" for="edad_cronologica_06"><strong>2.5</strong></label>
                                                            </div>
                                                        </td>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Total Rol Laboral-->
                                    <div class="col-12">
                                        <div class="form-group" style="align-content: center">
                                            <label for="total_rol">Rol Laboral (30%):</label>
                                            @if (!empty($array_laboralmente_Activo[0]->Total_rol_laboral))
                                                <input type="text" id="resultado_rol_laboral_30" name="resultado_rol_laboral_30" value="{{$array_laboralmente_Activo[0]->Total_rol_laboral}}" readonly="">                                                
                                            @else
                                                <input type="text" id="resultado_rol_laboral_30" name="resultado_rol_laboral_30" value="0" readonly="">                                                
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Otras Areas ocupacionales-->
                                <div class="columna_row1_otras_areas" style="display:none">
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
                                            <div class="form-group" id="activarintervalos4">
                                                <label for="tabla6">Tabla 6 - Aprendizaje y aplicación del conocimiento</label>
                                                <div class="table-responsive">
                                                    <table id="listado_laboralmente_activo_p6" class="table table-striped table-bordered"  width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Área ocupacional</th>
                                                                <th>0.0</th>
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_mirar) && $array_laboralmente_Activo[0]->Aprendizaje_mirar == 0.0)                                                                       
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_00" value="0.0" checked>
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mirar_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_mirar) && $array_laboralmente_Activo[0]->Aprendizaje_mirar == 0.1)                                                                       
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_01" value="0.1" checked>
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mirar_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_mirar) && $array_laboralmente_Activo[0]->Aprendizaje_mirar == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_02" value="0.2" checked>                                                                        
                                                                    @else                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mirar_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_mirar) && $array_laboralmente_Activo[0]->Aprendizaje_mirar == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_03" value="0.3" checked>                                                                                                                                                                                                                        
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mirar_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_mirar) && $array_laboralmente_Activo[0]->Aprendizaje_mirar == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_04" value="0.4" checked>                                                                                                                                                                                                                             
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mirar" id="mirar_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escuchar) && $array_laboralmente_Activo[0]->Aprendizaje_escuchar == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_00" value="0.0" checked>                                                                                                                                                                                                                            
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="escuchar_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escuchar) && $array_laboralmente_Activo[0]->Aprendizaje_escuchar == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_01" value="0.1" checked>                                                                                                                                                                                                                            
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="escuchar_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escuchar) && $array_laboralmente_Activo[0]->Aprendizaje_escuchar == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_02" value="0.2" checked>                                                                                                                                                                                                                            
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_02" value="0.2">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="escuchar_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escuchar) && $array_laboralmente_Activo[0]->Aprendizaje_escuchar == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_03" value="0.3" checked>                                                                                                                                                                                                                            
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="escuchar_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escuchar) && $array_laboralmente_Activo[0]->Aprendizaje_escuchar == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_04" value="0.4" checked>                                                                                                                                                                                                                            
                                                                    @else                                                                                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escuchar" id="escuchar_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_aprender) && $array_laboralmente_Activo[0]->Aprendizaje_aprender == 0.0)                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="aprender_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_aprender) && $array_laboralmente_Activo[0]->Aprendizaje_aprender == 0.1)                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="aprender_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_aprender) && $array_laboralmente_Activo[0]->Aprendizaje_aprender == 0.2)                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="aprender_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_aprender) && $array_laboralmente_Activo[0]->Aprendizaje_aprender == 0.3)                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="aprender_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_aprender) && $array_laboralmente_Activo[0]->Aprendizaje_aprender == 0.4)                                                                            
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="aprender" id="aprender_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_calcular) && $array_laboralmente_Activo[0]->Aprendizaje_calcular == 0.0)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="calcular_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_calcular) && $array_laboralmente_Activo[0]->Aprendizaje_calcular == 0.1)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="calcular_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_calcular) && $array_laboralmente_Activo[0]->Aprendizaje_calcular == 0.2)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="calcular_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_calcular) && $array_laboralmente_Activo[0]->Aprendizaje_calcular == 0.3)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="calcular_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_calcular) && $array_laboralmente_Activo[0]->Aprendizaje_calcular == 0.4)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="calcular" id="calcular_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_pensar) && $array_laboralmente_Activo[0]->Aprendizaje_pensar == 0.0)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="pensar_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_pensar) && $array_laboralmente_Activo[0]->Aprendizaje_pensar == 0.1)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_01" value="0.1" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="pensar_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_pensar) && $array_laboralmente_Activo[0]->Aprendizaje_pensar == 0.2)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_02" value="0.2" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="pensar_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_pensar) && $array_laboralmente_Activo[0]->Aprendizaje_pensar == 0.3)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_03" value="0.3" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="pensar_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_pensar) && $array_laboralmente_Activo[0]->Aprendizaje_pensar == 0.4)                                                                                                                                                    
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_04" value="0.4" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pensar" id="pensar_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_leer) && $array_laboralmente_Activo[0]->Aprendizaje_leer == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="leer_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_leer) && $array_laboralmente_Activo[0]->Aprendizaje_leer == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="leer_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_leer) && $array_laboralmente_Activo[0]->Aprendizaje_leer == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="leer_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_leer) && $array_laboralmente_Activo[0]->Aprendizaje_leer == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="leer_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_leer) && $array_laboralmente_Activo[0]->Aprendizaje_leer == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="leer" id="leer_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escribir) && $array_laboralmente_Activo[0]->Aprendizaje_escribir == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="escribir_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escribir) && $array_laboralmente_Activo[0]->Aprendizaje_escribir == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="escribir_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escribir) && $array_laboralmente_Activo[0]->Aprendizaje_escribir == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="escribir_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escribir) && $array_laboralmente_Activo[0]->Aprendizaje_escribir == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="escribir_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_escribir) && $array_laboralmente_Activo[0]->Aprendizaje_escribir == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="escribir" id="escribir_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_matematicos) && $array_laboralmente_Activo[0]->Aprendizaje_matematicos == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_00" value="0.0" checked>                                                                                                                                                                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="matematicos_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_matematicos) && $array_laboralmente_Activo[0]->Aprendizaje_matematicos == 0.1)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_01" value="0.1" checked>                                                                                                                                                                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="matematicos_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_matematicos) && $array_laboralmente_Activo[0]->Aprendizaje_matematicos == 0.2)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="matematicos_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_matematicos) && $array_laboralmente_Activo[0]->Aprendizaje_matematicos == 0.3)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="matematicos_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_matematicos) && $array_laboralmente_Activo[0]->Aprendizaje_matematicos == 0.4)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="matematicos" id="matematicos_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_resolver) && $array_laboralmente_Activo[0]->Aprendizaje_resolver == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="decisiones_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_resolver) && $array_laboralmente_Activo[0]->Aprendizaje_resolver == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="decisiones_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_resolver) && $array_laboralmente_Activo[0]->Aprendizaje_resolver == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="decisiones_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_resolver) && $array_laboralmente_Activo[0]->Aprendizaje_resolver == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="decisiones_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_resolver) && $array_laboralmente_Activo[0]->Aprendizaje_resolver == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decisiones" id="decisiones_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_tareas) && $array_laboralmente_Activo[0]->Aprendizaje_tareas == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="tareas_simples_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_tareas) && $array_laboralmente_Activo[0]->Aprendizaje_tareas == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="tareas_simples_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_tareas) && $array_laboralmente_Activo[0]->Aprendizaje_tareas == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="tareas_simples_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_tareas) && $array_laboralmente_Activo[0]->Aprendizaje_tareas == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="tareas_simples_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_tareas) && $array_laboralmente_Activo[0]->Aprendizaje_tareas == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tareas_simples" id="tareas_simples_04" value="0.4">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="tareas_simples_04"></label>
                                                                </div>
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <label for="tabla6_aprendizaje">Total:</label>
                                                @if (!empty($array_laboralmente_Activo[0]->Aprendizaje_total))
                                                    <input type="text" id="resultado_tabla6" name="resultado_tabla6" value="{{$array_laboralmente_Activo[0]->Aprendizaje_total}}"  readonly="">
                                                @else
                                                    <input type="text" id="resultado_tabla6" name="resultado_tabla6" value="0" readonly="">                                                    
                                                @endif
                                            </div>
                                        </div>
                                        <!--Tabla 7 - Categorías del área ocupacional de comunicación-->
                                        <div class="col-6">
                                            <div class="form-group" id="activarintervalos5">
                                                <label for="tabla7">Tabla 7 - Categorías del área ocupacional de comunicación</label>
                                                <div class="table-responsive">
                                                    <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Área ocupacional</th>
                                                                <th>0.0</th>
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_verbales) && $array_laboralmente_Activo[0]->Comunicacion_verbales == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_mensaje_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_verbales) && $array_laboralmente_Activo[0]->Comunicacion_verbales == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_01" value="0.1" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_mensaje_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_verbales) && $array_laboralmente_Activo[0]->Comunicacion_verbales == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_02" value="0.2" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_mensaje_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_verbales) && $array_laboralmente_Activo[0]->Comunicacion_verbales == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_03" value="0.3" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_mensaje_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_verbales) && $array_laboralmente_Activo[0]->Comunicacion_verbales == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_04" value="0.4" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_mensaje" id="comunicarse_mensaje_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_noverbales) && $array_laboralmente_Activo[0]->Comunicacion_noverbales == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_00" value="0.0" checked>                                                                                                                                                                                                                            
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="no_comunicarse_mensaje_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_noverbales) && $array_laboralmente_Activo[0]->Comunicacion_noverbales == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_01" value="0.1" checked>                                                                                                                                                                                                                            
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="no_comunicarse_mensaje_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_noverbales) && $array_laboralmente_Activo[0]->Comunicacion_noverbales == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_02" value="0.2" checked>                                                                                                                                                                                                                            
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="no_comunicarse_mensaje_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_noverbales) && $array_laboralmente_Activo[0]->Comunicacion_noverbales == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_03" value="0.3" checked>                                                                                                                                                                                                                            
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="no_comunicarse_mensaje_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_noverbales) && $array_laboralmente_Activo[0]->Comunicacion_noverbales == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_04" value="0.4" checked>                                                                                                                                                                                                                            
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_comunicarse_mensaje" id="no_comunicarse_mensaje_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_formal) && $array_laboralmente_Activo[0]->Comunicacion_formal == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_signos_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_formal) && $array_laboralmente_Activo[0]->Comunicacion_formal == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_signos_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_formal) && $array_laboralmente_Activo[0]->Comunicacion_formal == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_signos_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_formal) && $array_laboralmente_Activo[0]->Comunicacion_formal == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_signos_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_formal) && $array_laboralmente_Activo[0]->Comunicacion_formal == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_signos" id="comunicarse_signos_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_escritos) && $array_laboralmente_Activo[0]->Comunicacion_escritos == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_escrito_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_escritos) && $array_laboralmente_Activo[0]->Comunicacion_escritos == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_01" value="0.1" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_escrito_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_escritos) && $array_laboralmente_Activo[0]->Comunicacion_escritos == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_02" value="0.2" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_escrito_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_escritos) && $array_laboralmente_Activo[0]->Comunicacion_escritos == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_03" value="0.3" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comunicarse_escrito_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_escritos) && $array_laboralmente_Activo[0]->Comunicacion_escritos == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_04" value="0.4" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comunicarse_escrito" id="comunicarse_escrito_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_habla) && $array_laboralmente_Activo[0]->Comunicacion_habla == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="habla_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_habla) && $array_laboralmente_Activo[0]->Comunicacion_habla == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="habla_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_habla) && $array_laboralmente_Activo[0]->Comunicacion_habla == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="habla_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_habla) && $array_laboralmente_Activo[0]->Comunicacion_habla == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="habla_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_habla) && $array_laboralmente_Activo[0]->Comunicacion_habla == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="habla" id="habla_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_produccion) && $array_laboralmente_Activo[0]->Comunicacion_produccion == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="no_verbales_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_produccion) && $array_laboralmente_Activo[0]->Comunicacion_produccion == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="no_verbales_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_produccion) && $array_laboralmente_Activo[0]->Comunicacion_produccion == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="no_verbales_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_produccion) && $array_laboralmente_Activo[0]->Comunicacion_produccion == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="no_verbales_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_produccion) && $array_laboralmente_Activo[0]->Comunicacion_produccion == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="no_verbales" id="no_verbales_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_mensajes) && $array_laboralmente_Activo[0]->Comunicacion_mensajes == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mensajes_escritos_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_mensajes) && $array_laboralmente_Activo[0]->Comunicacion_mensajes == 0.1)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mensajes_escritos_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_mensajes) && $array_laboralmente_Activo[0]->Comunicacion_mensajes == 0.2)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mensajes_escritos_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_mensajes) && $array_laboralmente_Activo[0]->Comunicacion_mensajes == 0.3)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mensajes_escritos_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_mensajes) && $array_laboralmente_Activo[0]->Comunicacion_mensajes == 0.4)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mensajes_escritos" id="mensajes_escritos_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_conversacion) && $array_laboralmente_Activo[0]->Comunicacion_conversacion == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sostener_conversa_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_conversacion) && $array_laboralmente_Activo[0]->Comunicacion_conversacion == 0.1)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sostener_conversa_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_conversacion) && $array_laboralmente_Activo[0]->Comunicacion_conversacion == 0.2)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sostener_conversa_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_conversacion) && $array_laboralmente_Activo[0]->Comunicacion_conversacion == 0.3)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sostener_conversa_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_conversacion) && $array_laboralmente_Activo[0]->Comunicacion_conversacion == 0.4)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostener_conversa" id="sostener_conversa_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_discusiones) && $array_laboralmente_Activo[0]->Comunicacion_discusiones == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="iniciar_discusiones_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_discusiones) && $array_laboralmente_Activo[0]->Comunicacion_discusiones == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="iniciar_discusiones_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_discusiones) && $array_laboralmente_Activo[0]->Comunicacion_discusiones == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="iniciar_discusiones_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_discusiones) && $array_laboralmente_Activo[0]->Comunicacion_discusiones == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="iniciar_discusiones_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_discusiones) && $array_laboralmente_Activo[0]->Comunicacion_discusiones == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="iniciar_discusiones" id="iniciar_discusiones_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_dispositivos) && $array_laboralmente_Activo[0]->Comunicacion_dispositivos == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="utiliza_dispositivos_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_dispositivos) && $array_laboralmente_Activo[0]->Comunicacion_dispositivos == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_01" value="0.1" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="utiliza_dispositivos_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_dispositivos) && $array_laboralmente_Activo[0]->Comunicacion_dispositivos == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_02" value="0.2" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="utiliza_dispositivos_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_dispositivos) && $array_laboralmente_Activo[0]->Comunicacion_dispositivos == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_03" value="0.3" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="utiliza_dispositivos_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Comunicacion_dispositivos) && $array_laboralmente_Activo[0]->Comunicacion_dispositivos == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_04" value="0.4" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="utiliza_dispositivos" id="utiliza_dispositivos_04" value="0.4">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="utiliza_dispositivos_04"></label>
                                                                </div>
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <label for="tabla7_comuni">Total:</label>
                                                @if (!empty($array_laboralmente_Activo[0]->Comunicacion_total))
                                                    <input type="text" id="resultado_tabla7" name="resultado_tabla7" value="{{$array_laboralmente_Activo[0]->Comunicacion_total}}" readonly="">                                                    
                                                @else
                                                    <input type="text" id="resultado_tabla7" name="resultado_tabla7" value="0" readonly="">
                                                @endif
                                            </div>
                                        </div>
                                        <!--Tabla 8 - Relación de categorías del área ocupacional de movilidad-->
                                        <div class="col-6">
                                            <div class="form-group" id="activarintervalos6">
                                                <label for="tabla8">Tabla 8 - Relación de categorías del área ocupacional de movilidad<br><br></label>
                                                <div class="table-responsive">
                                                    <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Área ocupacional</th>
                                                                <th>0.0</th>
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_cambiar_posturas) && $array_laboralmente_Activo[0]->Movilidad_cambiar_posturas == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cambiar_posturas_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_cambiar_posturas) && $array_laboralmente_Activo[0]->Movilidad_cambiar_posturas == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cambiar_posturas_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_cambiar_posturas) && $array_laboralmente_Activo[0]->Movilidad_cambiar_posturas == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cambiar_posturas_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_cambiar_posturas) && $array_laboralmente_Activo[0]->Movilidad_cambiar_posturas == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cambiar_posturas_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_cambiar_posturas) && $array_laboralmente_Activo[0]->Movilidad_cambiar_posturas == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cambiar_posturas" id="cambiar_posturas_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mantener_posicion) && $array_laboralmente_Activo[0]->Movilidad_mantener_posicion == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="posicion_cuerpo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mantener_posicion) && $array_laboralmente_Activo[0]->Movilidad_mantener_posicion == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="posicion_cuerpo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mantener_posicion) && $array_laboralmente_Activo[0]->Movilidad_mantener_posicion == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="posicion_cuerpo_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mantener_posicion) && $array_laboralmente_Activo[0]->Movilidad_mantener_posicion == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="posicion_cuerpo_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mantener_posicion) && $array_laboralmente_Activo[0]->Movilidad_mantener_posicion == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="posicion_cuerpo" id="posicion_cuerpo_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_objetos) && $array_laboralmente_Activo[0]->Movilidad_objetos == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_00" value="0.0" checked>                                                                                                                                                                                                                    
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_00" value="0.0"> 
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="llevar_objetos_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_objetos) && $array_laboralmente_Activo[0]->Movilidad_objetos == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_01" value="0.1" checked>                                                                                                                                                                                                                    
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_01" value="0.1"> 
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="llevar_objetos_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_objetos) && $array_laboralmente_Activo[0]->Movilidad_objetos == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_02" value="0.2"> 
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="llevar_objetos_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_objetos) && $array_laboralmente_Activo[0]->Movilidad_objetos == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_03" value="0.3"> 
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="llevar_objetos_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_objetos) && $array_laboralmente_Activo[0]->Movilidad_objetos == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="llevar_objetos" id="llevar_objetos_04" value="0.4"> 
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_uso_mano) && $array_laboralmente_Activo[0]->Movilidad_uso_mano == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="uso_fino_mano_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_uso_mano) && $array_laboralmente_Activo[0]->Movilidad_uso_mano == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="uso_fino_mano_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_uso_mano) && $array_laboralmente_Activo[0]->Movilidad_uso_mano == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="uso_fino_mano_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_uso_mano) && $array_laboralmente_Activo[0]->Movilidad_uso_mano == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="uso_fino_mano_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_uso_mano) && $array_laboralmente_Activo[0]->Movilidad_uso_mano == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_fino_mano" id="uso_fino_mano_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mano_brazo) && $array_laboralmente_Activo[0]->Movilidad_mano_brazo == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="uso_mano_brazo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mano_brazo) && $array_laboralmente_Activo[0]->Movilidad_mano_brazo == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="uso_mano_brazo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mano_brazo) && $array_laboralmente_Activo[0]->Movilidad_mano_brazo == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="uso_mano_brazo_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mano_brazo) && $array_laboralmente_Activo[0]->Movilidad_mano_brazo == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="uso_mano_brazo_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_mano_brazo) && $array_laboralmente_Activo[0]->Movilidad_mano_brazo == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="uso_mano_brazo" id="uso_mano_brazo_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_Andar) && $array_laboralmente_Activo[0]->Movilidad_Andar == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazarse_entorno_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_Andar) && $array_laboralmente_Activo[0]->Movilidad_Andar == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazarse_entorno_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_Andar) && $array_laboralmente_Activo[0]->Movilidad_Andar == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazarse_entorno_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_Andar) && $array_laboralmente_Activo[0]->Movilidad_Andar == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazarse_entorno_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_Andar) && $array_laboralmente_Activo[0]->Movilidad_Andar == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_entorno" id="desplazarse_entorno_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_desplazarse) && $array_laboralmente_Activo[0]->Movilidad_desplazarse == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="distintos_lugares_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_desplazarse) && $array_laboralmente_Activo[0]->Movilidad_desplazarse == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="distintos_lugares_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_desplazarse) && $array_laboralmente_Activo[0]->Movilidad_desplazarse == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="distintos_lugares_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_desplazarse) && $array_laboralmente_Activo[0]->Movilidad_desplazarse == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="distintos_lugares_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_desplazarse) && $array_laboralmente_Activo[0]->Movilidad_desplazarse == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="distintos_lugares" id="distintos_lugares_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_equipo) && $array_laboralmente_Activo[0]->Movilidad_equipo == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazarse_con_equipo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_equipo) && $array_laboralmente_Activo[0]->Movilidad_equipo == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_01" value="0.1" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazarse_con_equipo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_equipo) && $array_laboralmente_Activo[0]->Movilidad_equipo == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_02" value="0.2" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazarse_con_equipo_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_equipo) && $array_laboralmente_Activo[0]->Movilidad_equipo == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_03" value="0.3" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazarse_con_equipo_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_equipo) && $array_laboralmente_Activo[0]->Movilidad_equipo == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_04" value="0.4" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazarse_con_equipo" id="desplazarse_con_equipo_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_transporte) && $array_laboralmente_Activo[0]->Movilidad_transporte == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="transporte_pasajero_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_transporte) && $array_laboralmente_Activo[0]->Movilidad_transporte == 0.1)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="transporte_pasajero_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_transporte) && $array_laboralmente_Activo[0]->Movilidad_transporte == 0.2)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="transporte_pasajero_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_transporte) && $array_laboralmente_Activo[0]->Movilidad_transporte == 0.3)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="transporte_pasajero_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_transporte) && $array_laboralmente_Activo[0]->Movilidad_transporte == 0.4)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="transporte_pasajero" id="transporte_pasajero_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_conduccion) && $array_laboralmente_Activo[0]->Movilidad_conduccion == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="conduccion_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_conduccion) && $array_laboralmente_Activo[0]->Movilidad_conduccion == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_01" value="0.1" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="conduccion_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_conduccion) && $array_laboralmente_Activo[0]->Movilidad_conduccion == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_02" value="0.2" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="conduccion_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_conduccion) && $array_laboralmente_Activo[0]->Movilidad_conduccion == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_03" value="0.3" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="conduccion_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Movilidad_conduccion) && $array_laboralmente_Activo[0]->Movilidad_conduccion == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_04" value="0.4" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="conduccion" id="conduccion_04" value="0.4">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="conduccion_04"></label>
                                                                </div>
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <label for="tabla8_movilidad">Total:</label>
                                                @if (!empty($array_laboralmente_Activo[0]->Movilidad_total))
                                                    <input type="text" id="resultado_tabla8" name="resultado_tabla8" value="{{$array_laboralmente_Activo[0]->Movilidad_total}}" readonly="">                                                    
                                                @else
                                                    <input type="text" id="resultado_tabla8" name="resultado_tabla8" value="0" readonly="">                                                    
                                                @endif
                                            </div>
                                        </div>
                                        <!--Tabla 9 - Relación por categorías para el área ocupacional del cuidado personal-->
                                        <div class="col-6">
                                            <div class="form-group" id="activarintervalos7">
                                                <label for="tabla9">Tabla 9 - Relación por categorías para el área ocupacional del cuidado personal</label>
                                                <div class="table-responsive">
                                                    <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Área ocupacional</th>
                                                                <th>0.0</th>
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_lavarse) && $array_laboralmente_Activo[0]->Cuidado_lavarse == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="lavarse_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_lavarse) && $array_laboralmente_Activo[0]->Cuidado_lavarse == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="lavarse_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_lavarse) && $array_laboralmente_Activo[0]->Cuidado_lavarse == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="lavarse_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_lavarse) && $array_laboralmente_Activo[0]->Cuidado_lavarse == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="lavarse_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_lavarse) && $array_laboralmente_Activo[0]->Cuidado_lavarse == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="lavarse" id="lavarse_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_partes_cuerpo) && $array_laboralmente_Activo[0]->Cuidado_partes_cuerpo == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_00" value="0.0" checked>                                                                                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_cuerpo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_partes_cuerpo) && $array_laboralmente_Activo[0]->Cuidado_partes_cuerpo == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_01" value="0.1" checked>                                                                                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_cuerpo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_partes_cuerpo) && $array_laboralmente_Activo[0]->Cuidado_partes_cuerpo == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_02" value="0.2" checked>                                                                                                                                            
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_cuerpo_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_partes_cuerpo) && $array_laboralmente_Activo[0]->Cuidado_partes_cuerpo == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_03" value="0.3" checked>                                                                                                                                            
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_cuerpo_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_partes_cuerpo) && $array_laboralmente_Activo[0]->Cuidado_partes_cuerpo == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_04" value="0.4" checked>                                                                                                                                            
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_cuerpo" id="cuidado_cuerpo_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_higiene) && $array_laboralmente_Activo[0]->Cuidado_higiene == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="higiene_personal_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_higiene) && $array_laboralmente_Activo[0]->Cuidado_higiene == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="higiene_personal_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_higiene) && $array_laboralmente_Activo[0]->Cuidado_higiene == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="higiene_personal_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_higiene) && $array_laboralmente_Activo[0]->Cuidado_higiene == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="higiene_personal_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_higiene) && $array_laboralmente_Activo[0]->Cuidado_higiene == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="higiene_personal" id="higiene_personal_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_vestirse) && $array_laboralmente_Activo[0]->Cuidado_vestirse == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="vestirse_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_vestirse) && $array_laboralmente_Activo[0]->Cuidado_vestirse == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="vestirse_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_vestirse) && $array_laboralmente_Activo[0]->Cuidado_vestirse == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="vestirse_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_vestirse) && $array_laboralmente_Activo[0]->Cuidado_vestirse == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="vestirse_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_vestirse) && $array_laboralmente_Activo[0]->Cuidado_vestirse == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="vestirse" id="vestirse_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_quitarse) && $array_laboralmente_Activo[0]->Cuidado_quitarse == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quitarse_ropa_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_quitarse) && $array_laboralmente_Activo[0]->Cuidado_quitarse == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quitarse_ropa_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_quitarse) && $array_laboralmente_Activo[0]->Cuidado_quitarse == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quitarse_ropa_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_quitarse) && $array_laboralmente_Activo[0]->Cuidado_quitarse == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quitarse_ropa_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_quitarse) && $array_laboralmente_Activo[0]->Cuidado_quitarse == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quitarse_ropa" id="quitarse_ropa_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_ponerse_calzado) && $array_laboralmente_Activo[0]->Cuidado_ponerse_calzado == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ponerse_calzado_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_ponerse_calzado) && $array_laboralmente_Activo[0]->Cuidado_ponerse_calzado == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ponerse_calzado_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_ponerse_calzado) && $array_laboralmente_Activo[0]->Cuidado_ponerse_calzado == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ponerse_calzado_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_ponerse_calzado) && $array_laboralmente_Activo[0]->Cuidado_ponerse_calzado == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ponerse_calzado_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_ponerse_calzado) && $array_laboralmente_Activo[0]->Cuidado_ponerse_calzado == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ponerse_calzado" id="ponerse_calzado_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_comer) && $array_laboralmente_Activo[0]->Cuidado_comer == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comer_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_comer) && $array_laboralmente_Activo[0]->Cuidado_comer == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comer_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_comer) && $array_laboralmente_Activo[0]->Cuidado_comer == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comer_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_comer) && $array_laboralmente_Activo[0]->Cuidado_comer == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comer_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_comer) && $array_laboralmente_Activo[0]->Cuidado_comer == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comer" id="comer_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_beber) && $array_laboralmente_Activo[0]->Cuidado_beber == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="beber_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_beber) && $array_laboralmente_Activo[0]->Cuidado_beber == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="beber_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_beber) && $array_laboralmente_Activo[0]->Cuidado_beber == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="beber_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_beber) && $array_laboralmente_Activo[0]->Cuidado_beber == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="beber_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_beber) && $array_laboralmente_Activo[0]->Cuidado_beber == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="beber" id="beber_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_salud) && $array_laboralmente_Activo[0]->Cuidado_salud == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_salud_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_salud) && $array_laboralmente_Activo[0]->Cuidado_salud == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_salud_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_salud) && $array_laboralmente_Activo[0]->Cuidado_salud == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_salud_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_salud) && $array_laboralmente_Activo[0]->Cuidado_salud == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_salud_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_salud) && $array_laboralmente_Activo[0]->Cuidado_salud == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_salud" id="cuidado_salud_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_dieta) && $array_laboralmente_Activo[0]->Cuidado_dieta == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="control_dieta_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_dieta) && $array_laboralmente_Activo[0]->Cuidado_dieta == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_01" value="0.1" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="control_dieta_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_dieta) && $array_laboralmente_Activo[0]->Cuidado_dieta == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_02" value="0.2" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="control_dieta_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_dieta) && $array_laboralmente_Activo[0]->Cuidado_dieta == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_03" value="0.3" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="control_dieta_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Cuidado_dieta) && $array_laboralmente_Activo[0]->Cuidado_dieta == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_04" value="0.4" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="control_dieta" id="control_dieta_04" value="0.4">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="control_dieta_04"></label>
                                                                </div>
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <label for="tabla9_areas">Total:</label>
                                                @if (!empty($array_laboralmente_Activo[0]->Cuidado_total))
                                                    <input type="text" id="resultado_tabla9" name="resultado_tabla9" value="{{$array_laboralmente_Activo[0]->Cuidado_total}}"  readonly="">                                                    
                                                @else                                                    
                                                    <input type="text" id="resultado_tabla9" name="resultado_tabla9" value="0"  readonly="">
                                                @endif
                                            </div>
                                        </div>
                                        <!--Tabla 10 - Relación por categorías para el área ocupacional del cuidado personal-->
                                        <div class="col-6">
                                            <div class="form-group" id="activarintervalos8">
                                                <label for="tabla10">Tabla 10 - Relación de las categorías para el área ocupacional de la vida doméstica</label>
                                                <div class="table-responsive">
                                                    <table id="listado_laboralmente_activo_p7" class="table table-striped table-bordered"  width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Área ocupacional</th>
                                                                <th>0.0</th>
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_vivir) && $array_laboralmente_Activo[0]->Domestica_vivir == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="adquisicion_para_vivir_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_vivir) && $array_laboralmente_Activo[0]->Domestica_vivir == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="adquisicion_para_vivir_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_vivir) && $array_laboralmente_Activo[0]->Domestica_vivir == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="adquisicion_para_vivir_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_vivir) && $array_laboralmente_Activo[0]->Domestica_vivir == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="adquisicion_para_vivir_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_vivir) && $array_laboralmente_Activo[0]->Domestica_vivir == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="adquisicion_para_vivir" id="adquisicion_para_vivir_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_bienes) && $array_laboralmente_Activo[0]->Domestica_bienes == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="bienes_servicios_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_bienes) && $array_laboralmente_Activo[0]->Domestica_bienes == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="bienes_servicios_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_bienes) && $array_laboralmente_Activo[0]->Domestica_bienes == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="bienes_servicios_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_bienes) && $array_laboralmente_Activo[0]->Domestica_bienes == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="bienes_servicios_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_bienes) && $array_laboralmente_Activo[0]->Domestica_bienes == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bienes_servicios" id="bienes_servicios_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comprar) && $array_laboralmente_Activo[0]->Domestica_comprar == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comprar_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comprar) && $array_laboralmente_Activo[0]->Domestica_comprar == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_01" value="0.1" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comprar_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comprar) && $array_laboralmente_Activo[0]->Domestica_comprar == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_02" value="0.2" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comprar_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comprar) && $array_laboralmente_Activo[0]->Domestica_comprar == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_03" value="0.3" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="comprar_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comprar) && $array_laboralmente_Activo[0]->Domestica_comprar == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_04" value="0.4" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="comprar" id="comprar_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comidas) && $array_laboralmente_Activo[0]->Domestica_comidas == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_00" value="0.0" checked>                                                                                                                                               
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="preparar_comida_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comidas) && $array_laboralmente_Activo[0]->Domestica_comidas == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_01" value="0.1" checked>                                                                                                                                               
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="preparar_comida_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comidas) && $array_laboralmente_Activo[0]->Domestica_comidas == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_02" value="0.2" checked>                                                                                                                                               
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="preparar_comida_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comidas) && $array_laboralmente_Activo[0]->Domestica_comidas == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_03" value="0.3" checked>                                                                                                                                               
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="preparar_comida_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_comidas) && $array_laboralmente_Activo[0]->Domestica_comidas == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_04" value="0.4" checked>                                                                                                                                               
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="preparar_comida" id="preparar_comida_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_quehaceres) && $array_laboralmente_Activo[0]->Domestica_quehaceres == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quehaceres_casa_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_quehaceres) && $array_laboralmente_Activo[0]->Domestica_quehaceres == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quehaceres_casa_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_quehaceres) && $array_laboralmente_Activo[0]->Domestica_quehaceres == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quehaceres_casa_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_quehaceres) && $array_laboralmente_Activo[0]->Domestica_quehaceres == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quehaceres_casa_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_quehaceres) && $array_laboralmente_Activo[0]->Domestica_quehaceres == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quehaceres_casa" id="quehaceres_casa_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_limpieza) && $array_laboralmente_Activo[0]->Domestica_limpieza == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="limpieza_vivienda_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_limpieza) && $array_laboralmente_Activo[0]->Domestica_limpieza == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="limpieza_vivienda_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_limpieza) && $array_laboralmente_Activo[0]->Domestica_limpieza == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="limpieza_vivienda_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_limpieza) && $array_laboralmente_Activo[0]->Domestica_limpieza == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="limpieza_vivienda_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_limpieza) && $array_laboralmente_Activo[0]->Domestica_limpieza == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="limpieza_vivienda" id="limpieza_vivienda_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_objetos) && $array_laboralmente_Activo[0]->Domestica_objetos == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="objetos_hogar_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_objetos) && $array_laboralmente_Activo[0]->Domestica_objetos == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="objetos_hogar_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_objetos) && $array_laboralmente_Activo[0]->Domestica_objetos == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="objetos_hogar_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_objetos) && $array_laboralmente_Activo[0]->Domestica_objetos == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="objetos_hogar_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_objetos) && $array_laboralmente_Activo[0]->Domestica_objetos == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="objetos_hogar" id="objetos_hogar_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_ayudar) && $array_laboralmente_Activo[0]->Domestica_ayudar == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ayudar_los_demas_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_ayudar) && $array_laboralmente_Activo[0]->Domestica_ayudar == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ayudar_los_demas_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_ayudar) && $array_laboralmente_Activo[0]->Domestica_ayudar == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ayudar_los_demas_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_ayudar) && $array_laboralmente_Activo[0]->Domestica_ayudar == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ayudar_los_demas_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_ayudar) && $array_laboralmente_Activo[0]->Domestica_ayudar == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ayudar_los_demas" id="ayudar_los_demas_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_mantenimiento) && $array_laboralmente_Activo[0]->Domestica_mantenimiento == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mantenimiento_dispositivos_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_mantenimiento) && $array_laboralmente_Activo[0]->Domestica_mantenimiento == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mantenimiento_dispositivos_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_mantenimiento) && $array_laboralmente_Activo[0]->Domestica_mantenimiento == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mantenimiento_dispositivos_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_mantenimiento) && $array_laboralmente_Activo[0]->Domestica_mantenimiento == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mantenimiento_dispositivos_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_mantenimiento) && $array_laboralmente_Activo[0]->Domestica_mantenimiento == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantenimiento_dispositivos" id="mantenimiento_dispositivos_04" value="0.4">
                                                                    @endif
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
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_animales) && $array_laboralmente_Activo[0]->Domestica_animales == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_00" value="0.0" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_animales_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_animales) && $array_laboralmente_Activo[0]->Domestica_animales == 0.1)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_01" value="0.1" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_01" value="0.1">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_animales_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_animales) && $array_laboralmente_Activo[0]->Domestica_animales == 0.2)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_02" value="0.2" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_02" value="0.2">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_animales_02"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_animales) && $array_laboralmente_Activo[0]->Domestica_animales == 0.3)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_03" value="0.3" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_03" value="0.3">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_animales_03"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_laboralmente_Activo[0]->Domestica_animales) && $array_laboralmente_Activo[0]->Domestica_animales == 0.4)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_04" value="0.4" checked>                                                                                                                                                
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cuidado_animales" id="cuidado_animales_04" value="0.4">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cuidado_animales_04"></label>
                                                                </div>
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <label for="tabla10_domestica">Total:</label>
                                                @if (!empty($array_laboralmente_Activo[0]->Domestica_total))
                                                    <input type="text" id="resultado_tabla10" name="resultado_tabla10" value="{{$array_laboralmente_Activo[0]->Domestica_total}}"  readonly="">                                                    
                                                @else
                                                    <input type="text" id="resultado_tabla10" name="resultado_tabla10" value="0"  readonly="">                                                    
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Total de otras areas y rol laboral -->
                                    <div class="row" style="text-align: right">
                                        <div class="col-12">
                                            <div class="form-group" id="activarintervalos9" style="align-content: center">                                               
                                                <label for="total_otras">Total otras areas(20%):</label>
                                                @if (!empty($array_laboralmente_Activo[0]->Total_otras_areas))
                                                    <input type="text" id="total_otras" name="total_otras" value="{{$array_laboralmente_Activo[0]->Total_otras_areas}}"  readonly="">                                                    
                                                @else
                                                    <input type="text" id="total_otras" name="total_otras" value="0"  readonly="">                                                                                                        
                                                @endif
                                                <br>
                                                <label for="total_rol_areas">Total rol laboral y otras areas(50%):</label>
                                                @if (!empty($array_laboralmente_Activo[0]->Total_laboral_otras_areas))
                                                    <input type="text" id="total_rol_areas" name="total_rol_areas" value="{{$array_laboralmente_Activo[0]->Total_laboral_otras_areas}}" readonly="">                                                    
                                                @else
                                                    <input type="text" id="total_rol_areas" name="total_rol_areas"  readonly="">                                                          
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group" id="activarintervalos10">
                                                @if (count($array_laboralmente_Activo) == 0)
                                                    <input type="submit" id="GuardarLaboralActivo" name="GuardarLaboralActivo" class="btn btn-info" value="Guardar">                                                
                                                    <input hidden="hidden" type="text" id="bandera_LaboralActivo_guardar_actualizar" value="Guardar">                                                       
                                                @else
                                                    <input type="submit" id="ActualizarLaboralActivo" name="ActualizarLaboralActivo" class="btn btn-info" value="Actualizar">                                                
                                                    <input hidden="hidden" type="text" id="bandera_LaboralActivo_guardar_actualizar" value="Actualizar">                                                                                                   
                                                @endif
                                            </div>
                                        </div>
                                        <div id="div_alerta_laboralmente_activo" class="col-12 d-none">
                                            <div class="form-group"> 
                                                <div class="alerta_laboralmente_activo alert alert-success mt-2 mr-auto" role="alert"></div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            </form>

                            <!-- Mostrar Aplicar rol ocupacional tablas -->
                            <form id="form_rol_ocupacional" action="POST">
                                <div class="row columna_row1_rol_ocupacional" style="display:none">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="poblacion_califi">Población a calificar</label>
                                            @if (!empty($array_rol_ocupacional[0]->Poblacion_calificar))
                                                <br>
                                                <select class="poblacion_califi2 custom-select" name="poblacion_califi" id="poblacion_califi" style="width: 100%;" disabled>  
                                                    <option value="{{$array_rol_ocupacional[0]->Poblacion_calificar}}" selected>{{$array_rol_ocupacional[0]->Nombre_parametro}}</option>
                                                </select>
                                            @else
                                                <br>
                                                <select class="poblacion_califi custom-select" name="poblacion_califi" id="poblacion_califi" style="width: 100%;" required>
                                                    <option value="">Seleccione una opción</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>                                    
                                </div>
                                <!--Tabla 12 - Criterios desarrollo neuroevolutivo Niños y Niñas 0 a 3 años-->
                                <div class="columna_row1_tabla_12" style="display:none" id="columna_row1_tabla_12">
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
                                                                <th>0.0</th>
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_postura_simetrica) && $array_rol_ocupacional[0]->Motriz_postura_simetrica == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_postura" id="mantiene_postura_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_postura" id="mantiene_postura_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mantiene_postura_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_postura_simetrica) && $array_rol_ocupacional[0]->Motriz_postura_simetrica == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_postura" id="mantiene_postura_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_postura" id="mantiene_postura_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mantiene_postura_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_postura_simetrica) && $array_rol_ocupacional[0]->Motriz_postura_simetrica == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_postura" id="mantiene_postura_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_postura" id="mantiene_postura_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_actividad_espontanea) && $array_rol_ocupacional[0]->Motriz_actividad_espontanea == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="actividad_espontanea" id="actividad_espontanea_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="actividad_espontanea" id="actividad_espontanea_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="actividad_espontanea_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_actividad_espontanea) && $array_rol_ocupacional[0]->Motriz_actividad_espontanea == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="actividad_espontanea" id="actividad_espontanea_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="actividad_espontanea" id="actividad_espontanea_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="actividad_espontanea_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_actividad_espontanea) && $array_rol_ocupacional[0]->Motriz_actividad_espontanea == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="actividad_espontanea" id="actividad_espontanea_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="actividad_espontanea" id="actividad_espontanea_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sujeta_cabeza) && $array_rol_ocupacional[0]->Motriz_sujeta_cabeza == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sujeta_cabeza" id="sujeta_cabeza_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sujeta_cabeza" id="sujeta_cabeza_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sujeta_cabeza_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sujeta_cabeza) && $array_rol_ocupacional[0]->Motriz_sujeta_cabeza == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sujeta_cabeza" id="sujeta_cabeza_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sujeta_cabeza" id="sujeta_cabeza_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sujeta_cabeza_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sujeta_cabeza) && $array_rol_ocupacional[0]->Motriz_sujeta_cabeza == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sujeta_cabeza" id="sujeta_cabeza_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sujeta_cabeza" id="sujeta_cabeza_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sentarse_apoyo) && $array_rol_ocupacional[0]->Motriz_sentarse_apoyo == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sienta_apoyo" id="sienta_apoyo_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sienta_apoyo" id="sienta_apoyo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sienta_apoyo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sentarse_apoyo) && $array_rol_ocupacional[0]->Motriz_sentarse_apoyo == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sienta_apoyo" id="sienta_apoyo_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sienta_apoyo" id="sienta_apoyo_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sienta_apoyo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sentarse_apoyo) && $array_rol_ocupacional[0]->Motriz_sentarse_apoyo == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sienta_apoyo" id="sienta_apoyo_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sienta_apoyo" id="sienta_apoyo_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_gira_sobre_mismo) && $array_rol_ocupacional[0]->Motriz_gira_sobre_mismo == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sobre_mismo" id="sobre_mismo_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sobre_mismo" id="sobre_mismo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sobre_mismo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_gira_sobre_mismo) && $array_rol_ocupacional[0]->Motriz_gira_sobre_mismo == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sobre_mismo" id="sobre_mismo_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sobre_mismo" id="sobre_mismo_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sobre_mismo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_gira_sobre_mismo) && $array_rol_ocupacional[0]->Motriz_gira_sobre_mismo == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sobre_mismo" id="sobre_mismo_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sobre_mismo" id="sobre_mismo_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo) && $array_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sentado_sin_apoyo" id="sentado_sin_apoyo_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sentado_sin_apoyo" id="sentado_sin_apoyo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sentado_sin_apoyo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo) && $array_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sentado_sin_apoyo" id="sentado_sin_apoyo_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sentado_sin_apoyo" id="sentado_sin_apoyo_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sentado_sin_apoyo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo) && $array_rol_ocupacional[0]->Motriz_sentanser_sin_apoyo == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sentado_sin_apoyo" id="sentado_sin_apoyo_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sentado_sin_apoyo" id="sentado_sin_apoyo_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado) && $array_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tumbado_sentado" id="tumbado_sentado_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tumbado_sentado" id="tumbado_sentado_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="tumbado_sentado_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado) && $array_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tumbado_sentado" id="tumbado_sentado_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tumbado_sentado" id="tumbado_sentado_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="tumbado_sentado_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado) && $array_rol_ocupacional[0]->Motriz_pasa_tumbado_sentado == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tumbado_sentado" id="tumbado_sentado_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tumbado_sentado" id="tumbado_sentado_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pararse_apoyo) && $array_rol_ocupacional[0]->Motriz_pararse_apoyo == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pie_apoyo" id="pie_apoyo_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pie_apoyo" id="pie_apoyo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="pie_apoyo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pararse_apoyo) && $array_rol_ocupacional[0]->Motriz_pararse_apoyo == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pie_apoyo" id="pie_apoyo_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pie_apoyo" id="pie_apoyo_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="pie_apoyo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pararse_apoyo) && $array_rol_ocupacional[0]->Motriz_pararse_apoyo == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pie_apoyo" id="pie_apoyo_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pie_apoyo" id="pie_apoyo_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pasos_apoyo) && $array_rol_ocupacional[0]->Motriz_pasos_apoyo == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pasos_apoyo" id="pasos_apoyo_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pasos_apoyo" id="pasos_apoyo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="pasos_apoyo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pasos_apoyo) && $array_rol_ocupacional[0]->Motriz_pasos_apoyo == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pasos_apoyo" id="pasos_apoyo_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pasos_apoyo" id="pasos_apoyo_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="pasos_apoyo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pasos_apoyo) && $array_rol_ocupacional[0]->Motriz_pasos_apoyo == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pasos_apoyo" id="pasos_apoyo_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="pasos_apoyo" id="pasos_apoyo_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pararse_sin_apoyo) && $array_rol_ocupacional[0]->Motriz_pararse_sin_apoyo == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_sin_apoyo" id="mantiene_sin_apoyo_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_sin_apoyo" id="mantiene_sin_apoyo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mantiene_sin_apoyo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pararse_sin_apoyo) && $array_rol_ocupacional[0]->Motriz_pararse_sin_apoyo == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_sin_apoyo" id="mantiene_sin_apoyo_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_sin_apoyo" id="mantiene_sin_apoyo_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="mantiene_sin_apoyo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_pararse_sin_apoyo) && $array_rol_ocupacional[0]->Motriz_pararse_sin_apoyo == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_sin_apoyo" id="mantiene_sin_apoyo_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="mantiene_sin_apoyo" id="mantiene_sin_apoyo_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_anda_solo) && $array_rol_ocupacional[0]->Motriz_anda_solo == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="anda_solo" id="anda_solo_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="anda_solo" id="anda_solo_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="anda_solo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_anda_solo) && $array_rol_ocupacional[0]->Motriz_anda_solo == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="anda_solo" id="anda_solo_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="anda_solo" id="anda_solo_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="anda_solo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_anda_solo) && $array_rol_ocupacional[0]->Motriz_anda_solo == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="anda_solo" id="anda_solo_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="anda_solo" id="anda_solo_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_empujar_pelota_pies) && $array_rol_ocupacional[0]->Motriz_empujar_pelota_pies == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="empuja_pelota" id="empuja_pelota_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="empuja_pelota" id="empuja_pelota_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="empuja_pelota_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_empujar_pelota_pies) && $array_rol_ocupacional[0]->Motriz_empujar_pelota_pies == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="empuja_pelota" id="empuja_pelota_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="empuja_pelota" id="empuja_pelota_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="empuja_pelota_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_empujar_pelota_pies) && $array_rol_ocupacional[0]->Motriz_empujar_pelota_pies == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="empuja_pelota" id="empuja_pelota_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="empuja_pelota" id="empuja_pelota_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_andar_obstaculos) && $array_rol_ocupacional[0]->Motriz_andar_obstaculos == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sorteando_obstaculos" id="sorteando_obstaculos_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sorteando_obstaculos" id="sorteando_obstaculos_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sorteando_obstaculos_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_andar_obstaculos) && $array_rol_ocupacional[0]->Motriz_andar_obstaculos == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sorteando_obstaculos" id="sorteando_obstaculos_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sorteando_obstaculos" id="sorteando_obstaculos_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sorteando_obstaculos_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Motriz_andar_obstaculos) && $array_rol_ocupacional[0]->Motriz_andar_obstaculos == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sorteando_obstaculos" id="sorteando_obstaculos_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sorteando_obstaculos" id="sorteando_obstaculos_02" value="2.0">
                                                                    @endif
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
                                                                <th>0.0</th>
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_succiona) && $array_rol_ocupacional[0]->Adaptativa_succiona == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="succiona" id="succiona_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="succiona" id="succiona_00" value="0.0">                                                                        
                                                                    @endif 
                                                                    <label class="form-check-label custom-control-label" for="succiona_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_succiona) && $array_rol_ocupacional[0]->Adaptativa_succiona == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="succiona" id="succiona_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="succiona" id="succiona_01" value="1.0">                                                                        
                                                                    @endif 
                                                                    <label class="form-check-label custom-control-label" for="succiona_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_succiona) && $array_rol_ocupacional[0]->Adaptativa_succiona == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="succiona" id="succiona_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="succiona" id="succiona_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_fija_mirada) && $array_rol_ocupacional[0]->Adaptativa_fija_mirada == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="fija_mirada" id="fija_mirada_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="fija_mirada" id="fija_mirada_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="fija_mirada_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_fija_mirada) && $array_rol_ocupacional[0]->Adaptativa_fija_mirada == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="fija_mirada" id="fija_mirada_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="fija_mirada" id="fija_mirada_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="fija_mirada_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_fija_mirada) && $array_rol_ocupacional[0]->Adaptativa_fija_mirada == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="fija_mirada" id="fija_mirada_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="fija_mirada" id="fija_mirada_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto) && $array_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="trayectoria_objeto" id="trayectoria_objeto_00" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="trayectoria_objeto" id="trayectoria_objeto_00" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="trayectoria_objeto_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto) && $array_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="trayectoria_objeto" id="trayectoria_objeto_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="trayectoria_objeto" id="trayectoria_objeto_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="trayectoria_objeto_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto) && $array_rol_ocupacional[0]->Adaptativa_sigue_trayectoria_objeto == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="trayectoria_objeto" id="trayectoria_objeto_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="trayectoria_objeto" id="trayectoria_objeto_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sostiene_sonajero) && $array_rol_ocupacional[0]->Adaptativa_sostiene_sonajero == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_sonajero" id="sostiene_sonajero_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_sonajero" id="sostiene_sonajero_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sostiene_sonajero_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sostiene_sonajero) && $array_rol_ocupacional[0]->Adaptativa_sostiene_sonajero == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_sonajero" id="sostiene_sonajero_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_sonajero" id="sostiene_sonajero_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sostiene_sonajero_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sostiene_sonajero) && $array_rol_ocupacional[0]->Adaptativa_sostiene_sonajero == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_sonajero" id="sostiene_sonajero_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_sonajero" id="sostiene_sonajero_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto) && $array_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="hacia_objeto" id="hacia_objeto_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="hacia_objeto" id="hacia_objeto_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="hacia_objeto_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto) && $array_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="hacia_objeto" id="hacia_objeto_01" value="1.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="hacia_objeto" id="hacia_objeto_01" value="1.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="hacia_objeto_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto) && $array_rol_ocupacional[0]->Adaptativa_tiende_mano_hacia_objeto == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="hacia_objeto" id="hacia_objeto_02" value="2.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="hacia_objeto" id="hacia_objeto_02" value="2.0">
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos) && $array_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_objeto" id="sostiene_objeto_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_objeto" id="sostiene_objeto_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sostiene_objeto_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos) && $array_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_objeto" id="sostiene_objeto_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_objeto" id="sostiene_objeto_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="sostiene_objeto_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos) && $array_rol_ocupacional[0]->Adaptativa_sostiene_objeto_manos == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_objeto" id="sostiene_objeto_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="sostiene_objeto" id="sostiene_objeto_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_abre_cajones) && $array_rol_ocupacional[0]->Adaptativa_abre_cajones == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_cajones" id="abre_cajones_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_cajones" id="abre_cajones_00" value="0.0">                                                                    
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="abre_cajones_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_abre_cajones) && $array_rol_ocupacional[0]->Adaptativa_abre_cajones == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_cajones" id="abre_cajones_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_cajones" id="abre_cajones_01" value="1.0">                                                                    
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="abre_cajones_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_abre_cajones) && $array_rol_ocupacional[0]->Adaptativa_abre_cajones == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_cajones" id="abre_cajones_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_cajones" id="abre_cajones_02" value="2.0">                                                                    
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_bebe_solo) && $array_rol_ocupacional[0]->Adaptativa_bebe_solo == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bebe_solo" id="bebe_solo_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bebe_solo" id="bebe_solo_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="bebe_solo_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_bebe_solo) && $array_rol_ocupacional[0]->Adaptativa_bebe_solo == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bebe_solo" id="bebe_solo_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bebe_solo" id="bebe_solo_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="bebe_solo_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_bebe_solo) && $array_rol_ocupacional[0]->Adaptativa_bebe_solo == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bebe_solo" id="bebe_solo_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="bebe_solo" id="bebe_solo_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir) && $array_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quita_prenda" id="quita_prenda_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quita_prenda" id="quita_prenda_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quita_prenda_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir) && $array_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quita_prenda" id="quita_prenda_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quita_prenda" id="quita_prenda_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="quita_prenda_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir) && $array_rol_ocupacional[0]->Adaptativa_quitar_prenda_vestir == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quita_prenda" id="quita_prenda_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="quita_prenda" id="quita_prenda_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa) && $array_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="espacios_casa" id="espacios_casa_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="espacios_casa" id="espacios_casa_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="espacios_casa_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa) && $array_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="espacios_casa" id="espacios_casa_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="espacios_casa" id="espacios_casa_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="espacios_casa_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa) && $array_rol_ocupacional[0]->Adaptativa_reconoce_funcion_espacios_casa == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="espacios_casa" id="espacios_casa_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="espacios_casa" id="espacios_casa_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz) &&  $array_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="imita_trazaso" id="imita_trazaso_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="imita_trazaso" id="imita_trazaso_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="imita_trazaso_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz) &&  $array_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="imita_trazaso" id="imita_trazaso_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="imita_trazaso" id="imita_trazaso_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="imita_trazaso_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz) &&  $array_rol_ocupacional[0]->Adaptativa_imita_trazo_lapiz == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="imita_trazaso" id="imita_trazaso_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="imita_trazaso" id="imita_trazaso_02" value="2.0">                                                                        
                                                                    @endif
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_abre_puerta) && $array_rol_ocupacional[0]->Adaptativa_abre_puerta == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_puerta" id="abre_puerta_00" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_puerta" id="abre_puerta_00" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="abre_puerta_00"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_abre_puerta) && $array_rol_ocupacional[0]->Adaptativa_abre_puerta == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_puerta" id="abre_puerta_01" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_puerta" id="abre_puerta_01" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="abre_puerta_01"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adaptativa_abre_puerta) && $array_rol_ocupacional[0]->Adaptativa_abre_puerta == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_puerta" id="abre_puerta_02" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="abre_puerta" id="abre_puerta_02" value="2.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="abre_puerta_02"></label>
                                                                </div>
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <label for="total_tabla12">Total rol ocupacional (50%):</label>
                                                @if (!empty($array_rol_ocupacional[0]->Total_criterios_desarrollo))
                                                    <input type="text" id="total_tabla12" name="total_tabla12"  value="{{$array_rol_ocupacional[0]->Total_criterios_desarrollo}}" readonly="">                                                    
                                                @else
                                                    <input type="text" id="total_tabla12" name="total_tabla12" value="0" readonly="">                                                    
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Tabla 13 - Valoración de los roles ocupacionales de juego-estudio en niños y niñas mayores de tres años y adolescentes-->
                                <div class="columna_row1_tabla_13" style="display:none" id="columna_row1_tabla_13">
                                    <div class="row text-center">
                                        <div class="col-12">
                                            <label for="tabla12">Tabla 13 - Valoración de los roles ocupacionales de juego-estudio en niños y niñas mayores de tres años y adolescentes</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tabla10">Roles ocupacionales de juego-estudio</label>
                                                <div class="table-responsive" id="activarInter_01">
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Juego_estudio_clase) && $array_rol_ocupacional[0]->Juego_estudio_clase == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseA_dificulta_01" value="0.0" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseA_dificulta_01" value="0.0">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseA_dificulta_01">0</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Juego_estudio_clase) && $array_rol_ocupacional[0]->Juego_estudio_clase == 10)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseB_dificulta_01" value="10" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseB_dificulta_01" value="10">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseB_dificulta_01">10</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Juego_estudio_clase) && $array_rol_ocupacional[0]->Juego_estudio_clase == 25)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseC_dificulta_01" value="25" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseC_dificulta_01" value="25">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseC_dificulta_01">25</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Juego_estudio_clase) && $array_rol_ocupacional[0]->Juego_estudio_clase == 35)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseD_dificulta_01" value="35" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseD_dificulta_01" value="35">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseD_dificulta_01">35</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Juego_estudio_clase) && $array_rol_ocupacional[0]->Juego_estudio_clase == 50)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseE_dificulta_01" value="50" checked>                                                                        
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_juego" id="claseE_dificulta_01" value="50">
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseE_dificulta_01">50</label>
                                                                </div>
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <label for="total_tabla13">Total rol ocupacional (50%):</label>
                                                @if (!empty($array_rol_ocupacional[0]->Total_rol_estudio_clase))
                                                    <input type="text" id="total_tabla13" name="total_tabla13" value="{{$array_rol_ocupacional[0]->Total_rol_estudio_clase}}" readonly="">
                                                @else
                                                    <input type="text" id="total_tabla13" name="total_tabla13" value="0"  readonly="">                                                                                                        
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tabla 14 - Valoración de los roles ocupacional relacionado con el uso del tiempo libre y de esparcimiento en adultos mayores -->
                                <div class="columna_row1_tabla_14" style="display:none" id="columna_row1_tabla_14">
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
                                                                    @if (!empty($array_rol_ocupacional[0]->Adultos_mayores) && $array_rol_ocupacional[0]->Adultos_mayores == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseA_dificultaAdulto_01" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseA_dificultaAdulto_01" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseA_dificultaAdulto_01">0</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adultos_mayores) && $array_rol_ocupacional[0]->Adultos_mayores == 10)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseB_dificultaAdulto_01" value="10" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseB_dificultaAdulto_01" value="10">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseB_dificultaAdulto_01">10</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adultos_mayores) && $array_rol_ocupacional[0]->Adultos_mayores == 25)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseC_dificultaAdulto_01" value="25" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseC_dificultaAdulto_01" value="25">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseC_dificultaAdulto_01">25</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adultos_mayores) && $array_rol_ocupacional[0]->Adultos_mayores == 35)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseD_dificultaAdulto_01" value="35" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseD_dificultaAdulto_01" value="35">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseD_dificultaAdulto_01">35</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_rol_ocupacional[0]->Adultos_mayores) && $array_rol_ocupacional[0]->Adultos_mayores == 50)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseE_dificultaAdulto_01" value="50" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="roles_ocupacionales_adultos" id="claseE_dificultaAdulto_01" value="50">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="claseE_dificultaAdulto_01">50</label>
                                                                </div>
                                                            </td>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <label for="total_tabla14">Total rol ocupacional (50%):</label>
                                                @if (!empty($array_rol_ocupacional[0]->Total_rol_adultos_ayores))
                                                    <input type="text" id="total_tabla14" name="total_tabla14" value="{{$array_rol_ocupacional[0]->Total_rol_adultos_ayores}}" readonly="">
                                                @else
                                                    <input type="text" id="total_tabla14" name="total_tabla14" value="0"  readonly="">                                                                                                        
                                                @endif
                                            </div>
                                        </div>
                                    </div>                                     
                                </div> 
                                <div class="row columna_row1_rol_ocupacional" style="display:none">
                                    <div class="col-12">
                                        <div class="form-group">
                                            @if (count($array_rol_ocupacional) == 0)
                                                <input type="submit" id="GuardarRolOcupacional" name="GuardarRolOcupacional" class="btn btn-info" value="Guardar">                                                
                                                <input hidden="hidden" type="text" id="bandera_RolOcupacional_guardar_actualizar" value="Guardar">                                                                                                                                                       
                                            @else
                                                <input type="submit" id="ActualizarRolOcupacional" name="ActualizarRolOcupacional" class="btn btn-info" value="Actualizar">                                                
                                                <input hidden="hidden" type="text" id="bandera_RolOcupacional_guardar_actualizar" value="Actualizar">                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_alerta_rol_ocupacional" class="col-12 d-none">
                                        <div class="form-group"> 
                                            <div class="alerta_rol_ocupacional alert alert-success mt-2 mr-auto" role="alert"></div>
                                        </div>
                                    </div>
                                </div>                                
                            </form>
                            {{-- <div class="row">
                                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert" style="text-align: initial;">
                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> <b>Si realizó algún cambio en las secciones anteriores que involucre % PCL, debe actualizar nuevamente el Concepto final del Dictamen Pericial</b>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <!-- Libro II Calificación de las discapacidades (20%) Decreto Muci-->
                    <form id="form_libros_2_3" action="POST">
                        <div class="card-info columna_row1_discapacidades"  @if ($decreto_1507=='1' && !empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion === 3) style="display:block" @else style="display:none" @endif>
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
                                                                @if (!empty($array_libros_2_3[0]->Conducta10))
                                                                    <select class="custom-select" name="conducta_10" id="conducta_10">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta10}}" selected>{{$array_libros_2_3[0]->Conducta10}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_10" id="conducta_10">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">11</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta11))
                                                                    <select class="custom-select" name="conducta_11" id="conducta_11">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta11}}" selected>{{$array_libros_2_3[0]->Conducta11}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_11" id="conducta_11">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">12</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta12))
                                                                    <select class="custom-select" name="conducta_12" id="conducta_12">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta12}}" selected>{{$array_libros_2_3[0]->Conducta12}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_12" id="conducta_12">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">13</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta13))
                                                                    <select class="custom-select" name="conducta_13" id="conducta_13">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta13}}" selected>{{$array_libros_2_3[0]->Conducta13}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_13" id="conducta_13">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">14</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta14))
                                                                    <select class="custom-select" name="conducta_14" id="conducta_14">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta14}}" selected>{{$array_libros_2_3[0]->Conducta14}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_14" id="conducta_14">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">15</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta15))
                                                                    <select class="custom-select" name="conducta_15" id="conducta_15">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta15}}" selected>{{$array_libros_2_3[0]->Conducta15}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_15" id="conducta_15">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">16</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta16))
                                                                    <select class="custom-select" name="conducta_16" id="conducta_16">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta16}}" selected>{{$array_libros_2_3[0]->Conducta16}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_16" id="conducta_16">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">17</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta17))
                                                                    <select class="custom-select" name="conducta_17" id="conducta_17">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta17}}" selected>{{$array_libros_2_3[0]->Conducta17}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_17" id="conducta_17">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">18</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta18))
                                                                    <select class="custom-select" name="conducta_18" id="conducta_18">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta18}}" selected>{{$array_libros_2_3[0]->Conducta18}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="conducta_18" id="conducta_18">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">19</p>
                                                                @if (!empty($array_libros_2_3[0]->Conducta19))
                                                                    <select class="custom-select" name="conducta_19" id="conducta_19">
                                                                        <option value="{{$array_libros_2_3[0]->Conducta19}}" selected>{{$array_libros_2_3[0]->Conducta19}}</option>
                                                                        <option value="0">0</option>
                                                                    </select> 
                                                                @else
                                                                    <select class="custom-select" name="conducta_19" id="conducta_19">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                     
                                                                @endif
                                                            </th>
                                                            <th width="8%">
                                                                <p class="text-center">Total</p>
                                                                @if (!empty($array_libros_2_3[0]->Total_conducta))
                                                                    <input type="text" class="form-control" name="total_conducta" id="total_conducta" value="{{$array_libros_2_3[0]->Total_conducta}}" readonly>                                                                    
                                                                @else
                                                                    <input type="text" class="form-control" name="total_conducta" id="total_conducta" value="0" readonly>                                                                    
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th width="12%">Comunicación</th>
                                                            <th>
                                                                <p class="text-center">20</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion20))
                                                                    <select class="custom-select" name="comunicacion_20" id="comunicacion_20">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion20}}" selected>{{$array_libros_2_3[0]->Comunicacion20}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_20" id="comunicacion_20">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">21</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion21))
                                                                    <select class="custom-select" name="comunicacion_21" id="comunicacion_21">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion21}}" selected>{{$array_libros_2_3[0]->Comunicacion21}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_21" id="comunicacion_21">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">22</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion22))
                                                                    <select class="custom-select" name="comunicacion_22" id="comunicacion_22">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion22}}" selected>{{$array_libros_2_3[0]->Comunicacion22}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_22" id="comunicacion_22">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">23</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion23))
                                                                    <select class="custom-select" name="comunicacion_23" id="comunicacion_23">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion23}}" selected>{{$array_libros_2_3[0]->Comunicacion23}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_23" id="comunicacion_23">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">24</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion24))
                                                                    <select class="custom-select" name="comunicacion_24" id="comunicacion_24">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion24}}" selected>{{$array_libros_2_3[0]->Comunicacion24}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_24" id="comunicacion_24">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">25</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion25))
                                                                    <select class="custom-select" name="comunicacion_25" id="comunicacion_25">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion25}}" selected>{{$array_libros_2_3[0]->Comunicacion25}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_25" id="comunicacion_25">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">26</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion26))
                                                                    <select class="custom-select" name="comunicacion_26" id="comunicacion_26">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion26}}" selected>{{$array_libros_2_3[0]->Comunicacion26}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_26" id="comunicacion_26">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">27</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion27))
                                                                    <select class="custom-select" name="comunicacion_27" id="comunicacion_27">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion27}}" selected>{{$array_libros_2_3[0]->Comunicacion27}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_27" id="comunicacion_27">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">28</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion28))
                                                                    <select class="custom-select" name="comunicacion_28" id="comunicacion_28">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion28}}" selected>{{$array_libros_2_3[0]->Comunicacion28}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_28" id="comunicacion_28">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">29</p>
                                                                @if (!empty($array_libros_2_3[0]->Comunicacion29))
                                                                    <select class="custom-select" name="comunicacion_29" id="comunicacion_29">
                                                                        <option value="{{$array_libros_2_3[0]->Comunicacion29}}" selected>{{$array_libros_2_3[0]->Comunicacion29}}</option>
                                                                        <option value="0">0</option>
                                                                    </select> 
                                                                @else
                                                                    <select class="custom-select" name="comunicacion_29" id="comunicacion_29">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                     
                                                                @endif
                                                            </th>
                                                            <th width="8%">
                                                                @if (!empty($array_libros_2_3[0]->Total_comunicacion))
                                                                    <input type="text" class="form-control" name="total_comunicacion" id="total_comunicacion" value="{{$array_libros_2_3[0]->Total_comunicacion}}" readonly>                                                                    
                                                                @else
                                                                    <input type="text" class="form-control" name="total_comunicacion" id="total_comunicacion" value="0" readonly>                                                                    
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th width="12%">Cuidado personal</th>
                                                            <th>
                                                                <p class="text-center">30</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal30))
                                                                    <select class="custom-select" name="cuidado_personal_30" id="cuidado_personal_30">
                                                                        <option value="{{$array_libros_2_3[0]->Personal30}}" selected>{{$array_libros_2_3[0]->Personal30}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_30" id="cuidado_personal_30">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">31</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal31))
                                                                    <select class="custom-select" name="cuidado_personal_31" id="cuidado_personal_31">
                                                                        <option value="{{$array_libros_2_3[0]->Personal31}}" selected>{{$array_libros_2_3[0]->Personal31}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_31" id="cuidado_personal_31">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">32</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal32))
                                                                    <select class="custom-select" name="cuidado_personal_32" id="cuidado_personal_32">
                                                                        <option value="{{$array_libros_2_3[0]->Personal32}}" selected>{{$array_libros_2_3[0]->Personal32}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_32" id="cuidado_personal_32">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">33</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal33))
                                                                    <select class="custom-select" name="cuidado_personal_33" id="cuidado_personal_33">
                                                                        <option value="{{$array_libros_2_3[0]->Personal33}}" selected>{{$array_libros_2_3[0]->Personal33}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_33" id="cuidado_personal_33">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">34</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal34))
                                                                    <select class="custom-select" name="cuidado_personal_34" id="cuidado_personal_34">
                                                                        <option value="{{$array_libros_2_3[0]->Personal34}}" selected>{{$array_libros_2_3[0]->Personal34}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_34" id="cuidado_personal_34">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">35</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal35))
                                                                    <select class="custom-select" name="cuidado_personal_35" id="cuidado_personal_35">
                                                                        <option value="{{$array_libros_2_3[0]->Personal35}}" selected>{{$array_libros_2_3[0]->Personal35}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_35" id="cuidado_personal_35">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">36</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal36))
                                                                    <select class="custom-select" name="cuidado_personal_36" id="cuidado_personal_36">
                                                                        <option value="{{$array_libros_2_3[0]->Personal36}}" selected>{{$array_libros_2_3[0]->Personal36}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_36" id="cuidado_personal_36">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">37</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal37))
                                                                    <select class="custom-select" name="cuidado_personal_37" id="cuidado_personal_37">
                                                                        <option value="{{$array_libros_2_3[0]->Personal37}}" selected>{{$array_libros_2_3[0]->Personal37}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_37" id="cuidado_personal_37">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">38</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal38))
                                                                    <select class="custom-select" name="cuidado_personal_38" id="cuidado_personal_38">
                                                                        <option value="{{$array_libros_2_3[0]->Personal38}}" selected>{{$array_libros_2_3[0]->Personal38}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_38" id="cuidado_personal_38">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">39</p>
                                                                @if (!empty($array_libros_2_3[0]->Personal39))
                                                                    <select class="custom-select" name="cuidado_personal_39" id="cuidado_personal_39">
                                                                        <option value="{{$array_libros_2_3[0]->Personal39}}" selected>{{$array_libros_2_3[0]->Personal39}}</option>
                                                                        <option value="0">0</option>
                                                                    </select> 
                                                                @else
                                                                    <select class="custom-select" name="cuidado_personal_39" id="cuidado_personal_39">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                     
                                                                @endif
                                                            </th>
                                                            <th width="8%">
                                                                @if (!empty($array_libros_2_3[0]->Total_personal))
                                                                    <input type="text" class="form-control" name="total_cuidado_personal" id="total_cuidado_personal" value="{{$array_libros_2_3[0]->Total_personal}}" readonly>
                                                                @else
                                                                    <input type="text" class="form-control" name="total_cuidado_personal" id="total_cuidado_personal" value="0" readonly>                                                                    
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th width="12%">Locomoción</th>
                                                            <th>
                                                                <p class="text-center">40</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion40))
                                                                    <select class="custom-select" name="lomocion_40" id="lomocion_40">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion40}}" selected>{{$array_libros_2_3[0]->Locomocion40}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_40" id="lomocion_40">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">41</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion41))
                                                                    <select class="custom-select" name="lomocion_41" id="lomocion_41">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion41}}" selected>{{$array_libros_2_3[0]->Locomocion41}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_41" id="lomocion_41">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">42</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion42))
                                                                    <select class="custom-select" name="lomocion_42" id="lomocion_42">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion42}}" selected>{{$array_libros_2_3[0]->Locomocion42}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_42" id="lomocion_42">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">43</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion43))
                                                                    <select class="custom-select" name="lomocion_43" id="lomocion_43">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion43}}" selected>{{$array_libros_2_3[0]->Locomocion43}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_43" id="lomocion_43">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">44</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion44))
                                                                    <select class="custom-select" name="lomocion_44" id="lomocion_44">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion44}}" selected>{{$array_libros_2_3[0]->Locomocion44}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_44" id="lomocion_44">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">45</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion45))
                                                                    <select class="custom-select" name="lomocion_45" id="lomocion_45">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion45}}" selected>{{$array_libros_2_3[0]->Locomocion45}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_45" id="lomocion_45">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">46</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion46))
                                                                    <select class="custom-select" name="lomocion_46" id="lomocion_46">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion46}}" selected>{{$array_libros_2_3[0]->Locomocion46}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_46" id="lomocion_46">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">47</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion47))
                                                                    <select class="custom-select" name="lomocion_47" id="lomocion_47">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion47}}" selected>{{$array_libros_2_3[0]->Locomocion47}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_47" id="lomocion_47">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">48</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion48))
                                                                    <select class="custom-select" name="lomocion_48" id="lomocion_48">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion48}}" selected>{{$array_libros_2_3[0]->Locomocion48}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="lomocion_48" id="lomocion_48">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">49</p>
                                                                @if (!empty($array_libros_2_3[0]->Locomocion49))
                                                                    <select class="custom-select" name="lomocion_49" id="lomocion_49">
                                                                        <option value="{{$array_libros_2_3[0]->Locomocion49}}" selected>{{$array_libros_2_3[0]->Locomocion49}}</option>
                                                                        <option value="0">0</option>
                                                                    </select> 
                                                                @else
                                                                    <select class="custom-select" name="lomocion_49" id="lomocion_49">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                     
                                                                @endif
                                                            </th>
                                                            <th width="8%">
                                                                @if (!empty($array_libros_2_3[0]->Total_locomocion))
                                                                    <input type="text" class="form-control" name="total_lomocion" id="total_lomocion" value="{{$array_libros_2_3[0]->Total_locomocion}}" readonly>                                                                                                                                        
                                                                @else
                                                                    <input type="text" class="form-control" name="total_lomocion" id="total_lomocion" value="0" readonly>                                                                    
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th width="12%">Disposición del cuerpo</th>
                                                            <th>
                                                                <p class="text-center">50</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion50))
                                                                    <select class="custom-select" name="disposicion_50" id="disposicion_50">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion50}}" selected>{{$array_libros_2_3[0]->Disposicion50}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_50" id="disposicion_50">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">51</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion51))
                                                                    <select class="custom-select" name="disposicion_51" id="disposicion_51">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion51}}" selected>{{$array_libros_2_3[0]->Disposicion51}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_51" id="disposicion_51">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">52</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion52))
                                                                    <select class="custom-select" name="disposicion_52" id="disposicion_52">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion52}}" selected>{{$array_libros_2_3[0]->Disposicion52}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_52" id="disposicion_52">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">53</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion53))
                                                                    <select class="custom-select" name="disposicion_53" id="disposicion_53">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion53}}" selected>{{$array_libros_2_3[0]->Disposicion53}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_53" id="disposicion_53">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">54</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion54))
                                                                    <select class="custom-select" name="disposicion_54" id="disposicion_54">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion54}}" selected>{{$array_libros_2_3[0]->Disposicion54}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_54" id="disposicion_54">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">55</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion55))
                                                                    <select class="custom-select" name="disposicion_55" id="disposicion_55">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion55}}" selected>{{$array_libros_2_3[0]->Disposicion55}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_55" id="disposicion_55">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">56</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion56))
                                                                    <select class="custom-select" name="disposicion_56" id="disposicion_56">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion56}}" selected>{{$array_libros_2_3[0]->Disposicion56}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_56" id="disposicion_56">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">57</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion57))
                                                                    <select class="custom-select" name="disposicion_57" id="disposicion_57">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion57}}" selected>{{$array_libros_2_3[0]->Disposicion57}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_57" id="disposicion_57">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">58</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion58))
                                                                    <select class="custom-select" name="disposicion_58" id="disposicion_58">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion58}}" selected>{{$array_libros_2_3[0]->Disposicion58}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="disposicion_58" id="disposicion_58">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">59</p>
                                                                @if (!empty($array_libros_2_3[0]->Disposicion59))
                                                                    <select class="custom-select" name="disposicion_59" id="disposicion_59">
                                                                        <option value="{{$array_libros_2_3[0]->Disposicion59}}" selected>{{$array_libros_2_3[0]->Disposicion59}}</option>
                                                                        <option value="0">0</option>
                                                                    </select> 
                                                                @else
                                                                    <select class="custom-select" name="disposicion_59" id="disposicion_59">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                     
                                                                @endif
                                                            </th>
                                                            <th width="8%">
                                                                @if (!empty($array_libros_2_3[0]->Total_disposicion))
                                                                    <input type="text" class="form-control" name="total_disposicion" id="total_disposicion" value="{{$array_libros_2_3[0]->Total_disposicion}}" readonly>
                                                                @else
                                                                    <input type="text" class="form-control" name="total_disposicion" id="total_disposicion" value="0" readonly>                                                                    
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th width="12%">Destreza</th>
                                                            <th>
                                                                <p class="text-center">60</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza60))
                                                                    <select class="custom-select" name="destreza_60" id="destreza_60">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza60}}" selected>{{$array_libros_2_3[0]->Destreza60}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_60" id="destreza_60">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">61</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza61))
                                                                    <select class="custom-select" name="destreza_61" id="destreza_61">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza61}}" selected>{{$array_libros_2_3[0]->Destreza61}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_61" id="destreza_61">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">62</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza62))
                                                                    <select class="custom-select" name="destreza_62" id="destreza_62">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza62}}" selected>{{$array_libros_2_3[0]->Destreza62}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_62" id="destreza_62">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">63</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza63))
                                                                    <select class="custom-select" name="destreza_63" id="destreza_63">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza63}}" selected>{{$array_libros_2_3[0]->Destreza63}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_63" id="destreza_63">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">64</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza64))
                                                                    <select class="custom-select" name="destreza_64" id="destreza_64">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza64}}" selected>{{$array_libros_2_3[0]->Destreza64}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_64" id="destreza_64">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">65</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza65))
                                                                    <select class="custom-select" name="destreza_65" id="destreza_65">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza65}}" selected>{{$array_libros_2_3[0]->Destreza65}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_65" id="destreza_65">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">66</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza66))
                                                                    <select class="custom-select" name="destreza_66" id="destreza_66">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza66}}" selected>{{$array_libros_2_3[0]->Destreza66}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_66" id="destreza_66">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">67</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza67))
                                                                    <select class="custom-select" name="destreza_67" id="destreza_67">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza67}}" selected>{{$array_libros_2_3[0]->Destreza67}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_67" id="destreza_67">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">68</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza68))
                                                                    <select class="custom-select" name="destreza_68" id="destreza_68">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza68}}" selected>{{$array_libros_2_3[0]->Destreza68}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="destreza_68" id="destreza_68">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">69</p>
                                                                @if (!empty($array_libros_2_3[0]->Destreza69))
                                                                    <select class="custom-select" name="destreza_69" id="destreza_69">
                                                                        <option value="{{$array_libros_2_3[0]->Destreza69}}" selected>{{$array_libros_2_3[0]->Destreza69}}</option>
                                                                        <option value="0">0</option>
                                                                    </select> 
                                                                @else
                                                                    <select class="custom-select" name="destreza_69" id="destreza_69">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                     
                                                                @endif
                                                            </th>
                                                            <th width="8%">
                                                                @if (!empty($array_libros_2_3[0]->Total_destreza))
                                                                    <input type="text" class="form-control" name="total_destreza" id="total_destreza" value="{{$array_libros_2_3[0]->Total_destreza}}" readonly>                                                                                                                                        
                                                                @else
                                                                    <input type="text" class="form-control" name="total_destreza" id="total_destreza" value="0" readonly>                                                                    
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th width="12%">Situación</th>
                                                            <th>
                                                                <p class="text-center">70</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion70))
                                                                    <select class="custom-select" name="situacion_70" id="situacion_70">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion70}}" selected>{{$array_libros_2_3[0]->Situacion70}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_70" id="situacion_70">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">71</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion71))
                                                                    <select class="custom-select" name="situacion_71" id="situacion_71">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion71}}" selected>{{$array_libros_2_3[0]->Situacion71}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_71" id="situacion_71">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">72</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion72))
                                                                    <select class="custom-select" name="situacion_72" id="situacion_72">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion72}}" selected>{{$array_libros_2_3[0]->Situacion72}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_72" id="situacion_72">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">73</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion73))
                                                                    <select class="custom-select" name="situacion_73" id="situacion_73">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion73}}" selected>{{$array_libros_2_3[0]->Situacion73}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_73" id="situacion_73">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">74</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion74))
                                                                    <select class="custom-select" name="situacion_74" id="situacion_74">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion74}}" selected>{{$array_libros_2_3[0]->Situacion74}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_74" id="situacion_74">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">75</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion75))
                                                                    <select class="custom-select" name="situacion_75" id="situacion_75">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion75}}" selected>{{$array_libros_2_3[0]->Situacion75}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_75" id="situacion_75">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">76</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion76))
                                                                    <select class="custom-select" name="situacion_76" id="situacion_76">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion76}}" selected>{{$array_libros_2_3[0]->Situacion76}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_76" id="situacion_76">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">77</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion77))
                                                                    <select class="custom-select" name="situacion_77" id="situacion_77">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion77}}" selected>{{$array_libros_2_3[0]->Situacion77}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_77" id="situacion_77">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th>
                                                                <p class="text-center">78</p>
                                                                @if (!empty($array_libros_2_3[0]->Situacion78))
                                                                    <select class="custom-select" name="situacion_78" id="situacion_78">
                                                                        <option value="{{$array_libros_2_3[0]->Situacion78}}" selected>{{$array_libros_2_3[0]->Situacion78}}</option>
                                                                        <option value="0">0</option>
                                                                    </select>
                                                                @else
                                                                    <select class="custom-select" name="situacion_78" id="situacion_78">
                                                                        <option value="0">0</option>
                                                                    </select>                                                                    
                                                                @endif
                                                            </th>
                                                            <th></th>
                                                            <th width="8%">
                                                                @if (!empty($array_libros_2_3[0]->Total_situacion))
                                                                    <input type="text" class="form-control" name="total_situacion" id="total_situacion" value="{{$array_libros_2_3[0]->Total_situacion}}" readonly>
                                                                @else
                                                                    <input type="text" class="form-control" name="total_situacion" id="total_situacion" value="0" readonly>                                                                    
                                                                @endif
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <label for="total_discapacidades">Total Discapacidades:</label>
                                        @if (!empty($array_libros_2_3[0]->Total_discapacidad))
                                            <input type="text" name="total_discapacidades" id="total_discapacidades" value="{{$array_libros_2_3[0]->Total_discapacidad}}" readonly>
                                        @else
                                            <input type="text" name="total_discapacidades" id="total_discapacidades" value="0" readonly>                                            
                                        @endif
                                    </div>
                                </div>                             
                            </div>
                        </div>
                        <div class="card-info columna_row1_minusvalias" @if ($decreto_1507=='1' && !empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion === 3) style="display:block" @else style="display:none" @endif>
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
                                                        <tr id="activarminusvalia_01">
                                                            <th>Orientación</th>
                                                            <th>
                                                                <p>Orientado</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Orientacion) && $array_libros_2_3[0]->Orientacion == 0.0)                                                                        
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_01" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_01" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="orientacion_01">0.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Compensado</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Orientacion) && $array_libros_2_3[0]->Orientacion == 0.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_02" value="0.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_02" value="0.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="orientacion_02">0.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Compensado requiere ayuda</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Orientacion) && $array_libros_2_3[0]->Orientacion == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_03" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_03" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="orientacion_03">1.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>No compensado</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Orientacion) && $array_libros_2_3[0]->Orientacion == 1.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_04" value="1.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_04" value="1.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="orientacion_04">1.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Ausencia</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Orientacion) && $array_libros_2_3[0]->Orientacion == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_05" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_05" value="2.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="orientacion_05">2.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Inconsciencia</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Orientacion) && $array_libros_2_3[0]->Orientacion == 2.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_06" value="2.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="orientacion" id="orientacion_06" value="2.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="orientacion_06">2.5</label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr id="activarminusvalia_02">
                                                            <th>Independencia física</th>
                                                            <th>
                                                                <p>Independiente</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Idenpendencia_fisica) && $array_libros_2_3[0]->Idenpendencia_fisica == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_01" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_01" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="indepen_fisica_01">0.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Independencia con ayuda</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Idenpendencia_fisica) && $array_libros_2_3[0]->Idenpendencia_fisica == 0.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_02" value="0.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_02" value="0.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="indepen_fisica_02">0.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Independencia adaptada</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Idenpendencia_fisica) && $array_libros_2_3[0]->Idenpendencia_fisica == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_03" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_03" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="indepen_fisica_03">1.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Dependencia situacional</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Idenpendencia_fisica) && $array_libros_2_3[0]->Idenpendencia_fisica == 1.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_04" value="1.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_04" value="1.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="indepen_fisica_04">1.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Dependencia asistida</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Idenpendencia_fisica) && $array_libros_2_3[0]->Idenpendencia_fisica == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_05" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_05" value="2.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="indepen_fisica_05">2.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Dependencia cuidados esp. / perm.</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Idenpendencia_fisica) && $array_libros_2_3[0]->Idenpendencia_fisica == 2.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_06" value="2.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="indepen_fisica" id="indepen_fisica_06" value="2.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="indepen_fisica_06">2.5</label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr id="activarminusvalia_03">
                                                            <th>Desplazamiento</th>
                                                            <th>
                                                                <p>Pleno</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Desplazamiento) && $array_libros_2_3[0]->Desplazamiento == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_01" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_01" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazamiento_01">0.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Restricciones intermitentes</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Desplazamiento) && $array_libros_2_3[0]->Desplazamiento == 0.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_02" value="0.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_02" value="0.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazamiento_02">0.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Deficiente</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Desplazamiento) && $array_libros_2_3[0]->Desplazamiento == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_03" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_03" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazamiento_03">1.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Reducido al ámbito de la vecindad </p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Desplazamiento) && $array_libros_2_3[0]->Desplazamiento == 1.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_04" value="1.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_04" value="1.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazamiento_04">1.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Reducido al ámbito del domicilio</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Desplazamiento) && $array_libros_2_3[0]->Desplazamiento == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_05" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_05" value="2.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazamiento_05">2.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Confinamiento silla / cama</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Desplazamiento) && $array_libros_2_3[0]->Desplazamiento == 2.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_06" value="2.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="desplazamiento" id="desplazamiento_06" value="2.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="desplazamiento_06">2.5</label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr id="activarminusvalia_04">
                                                            <th>Ocupacional</th>
                                                            <th>
                                                                <p>Habitualmente ocupado</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Ocupacional) && $array_libros_2_3[0]->Ocupacional == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_01" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_01" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ocupacional_01">0.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Ocupación recortada </p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Ocupacional) && $array_libros_2_3[0]->Ocupacional == 2.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_02" value="2.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_02" value="2.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ocupacional_02">2.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Ocupación adaptada</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Ocupacional) && $array_libros_2_3[0]->Ocupacional == 5.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_03" value="5.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_03" value="5.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ocupacional_03">5.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Cambio de ocupación</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Ocupacional) && $array_libros_2_3[0]->Ocupacional == 7.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_04" value="7.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_04" value="7.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ocupacional_04">7.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Ocupación reducida</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Ocupacional) && $array_libros_2_3[0]->Ocupacional == 10.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_05" value="10.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_05" value="10.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ocupacional_05">10.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Ocupación restringida</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Ocupacional) && $array_libros_2_3[0]->Ocupacional == 12.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_06" value="12.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_06" value="12.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ocupacional_06">12.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Sin posibilidad de ocupación</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Ocupacional) && $array_libros_2_3[0]->Ocupacional == 15.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_07" value="15.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="ocupacional" id="ocupacional_07" value="15.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="ocupacional_07">15.0</label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr id="activarminusvalia_05">
                                                            <th>Integración social</th>
                                                            <th>
                                                                <p>Socialmente integrado</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Integracion) && $array_libros_2_3[0]->Integracion == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_01" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_01" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="social_01">0.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Participación inhibida</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Integracion) && $array_libros_2_3[0]->Integracion == 0.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_02" value="0.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_02" value="0.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="social_02">0.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Participación disminuida</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Integracion) && $array_libros_2_3[0]->Integracion == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_03" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_03" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="social_03">1.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Participación empobrecida</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Integracion) && $array_libros_2_3[0]->Integracion == 1.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_04" value="1.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_04" value="1.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="social_04">1.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Relaciones reducidas</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Integracion) && $array_libros_2_3[0]->Integracion == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_05" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_05" value="2.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="social_05">2.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Aislamiento social</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Integracion) && $array_libros_2_3[0]->Integracion == 2.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_06" value="2.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="social" id="social_06" value="2.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="social_06">2.5</label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr id="activarminusvalia_06">
                                                            <th>Autosuficiencia económica</th>
                                                            <th>
                                                                <p>Plenamente autosuficiente</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Autosuficiencia) && $array_libros_2_3[0]->Autosuficiencia == 0.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_01" value="0.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_01" value="0.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="economica_01">0.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Autosuficiente</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Autosuficiencia) && $array_libros_2_3[0]->Autosuficiencia == 0.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_02" value="0.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_02" value="0.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="economica_02">0.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Autosuficiencia reajustada</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Autosuficiencia) && $array_libros_2_3[0]->Autosuficiencia == 1.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_03" value="1.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_03" value="1.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="economica_03">1.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Precariamente autosuficiente</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Autosuficiencia) && $array_libros_2_3[0]->Autosuficiencia == 1.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_04" value="1.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_04" value="1.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="economica_04">1.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Económicamente débil</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Autosuficiencia) && $array_libros_2_3[0]->Autosuficiencia == 2.0)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_05" value="2.0" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_05" value="2.0">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="economica_05">2.0</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>Inactivo económicamente</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Autosuficiencia) && $array_libros_2_3[0]->Autosuficiencia == 2.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_06" value="2.5" checked>
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="economica" id="economica_06" value="2.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="economica_06">2.5</label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                        <tr id="activarminusvalia_07">
                                                            <th>Edad cronológica</th>
                                                            <th>
                                                                <p>Menor de 18 años</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Edad_cronologica_menor) && $array_libros_2_3[0]->Edad_cronologica_menor == 2.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="menor_18" value="2.5" checked> 
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="menor_18" value="2.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="menor_18">2.5</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>De 18 a 29 años</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Edad_cronologica_adulto) && $array_libros_2_3[0]->Edad_cronologica_adulto == 1.3)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_01" value="1.3" checked> 
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_01" value="1.3">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cronologica_01">1.3</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>De 30 a 39 años</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Edad_cronologica_adulto) && $array_libros_2_3[0]->Edad_cronologica_adulto == 1.8)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_02" value="1.8" checked> 
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_02" value="1.8">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cronologica_02">1.8</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>De 40 a 49 años</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Edad_cronologica_adulto) && $array_libros_2_3[0]->Edad_cronologica_adulto == 2)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_03" value="2" checked> 
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_03" value="2">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cronologica_03">2</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>De 50 a 54 años</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Edad_cronologica_adulto) && $array_libros_2_3[0]->Edad_cronologica_adulto == 2.3)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_04" value="2.3" checked> 
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_04" value="2.3">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cronologica_04">2.3</label>
                                                                </div>
                                                            </th>
                                                            <th>
                                                                <p>De 55 o más años</p>
                                                                <div class="form-check custom-control custom-radio">
                                                                    @if (!empty($array_libros_2_3[0]->Edad_cronologica_adulto) && $array_libros_2_3[0]->Edad_cronologica_adulto == 2.5)
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_05" value="2.5" checked> 
                                                                    @else
                                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="cronologica" id="cronologica_05" value="2.5">                                                                        
                                                                    @endif
                                                                    <label class="form-check-label custom-control-label" for="cronologica_05">2.5</label>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <label for="total_minusvalia">Total Minusvalía:</label>
                                        @if (!empty($array_libros_2_3[0]->Total_minusvalia))
                                            <input type="text" name="total_minusvalia" id="total_minusvalia" value="{{$array_libros_2_3[0]->Total_minusvalia}}" readonly>                                            
                                        @else
                                            <input type="text" name="total_minusvalia" id="total_minusvalia" value="0" readonly>                                            
                                        @endif
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">                                        
                                            @if (count($array_libros_2_3) == 0)
                                                <input type="submit" id="GuardarLibros2_3" name="GuardarLibros2_3" class="btn btn-info" value="Guardar">                                                
                                                <input hidden="hidden" type="text" id="bandera_Libros2_3_guardar_actualizar" value="Guardar">                                               
                                            @else
                                                <input type="submit" id="ActualizarLibros2_3" name="ActualizarLibros2_3" class="btn btn-info" value="Actualizar">                                                
                                                <input hidden="hidden" type="text" id="bandera_Libros2_3_guardar_actualizar" value="Actualizar">    
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_alerta_libros2_3" class="col-12 d-none">
                                        <div class="form-group"> 
                                            <div class="alerta_libros2_3 alert alert-success mt-2 mr-auto" role="alert"></div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert" style="text-align: initial;">
                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> <b>Si realizó algún cambio en las secciones anteriores que involucre % PCL, debe actualizar nuevamente el Concepto final del Dictamen Pericial</b>
                                    </div>
                                </div> --}}
                            </div>                            
                        </div>                        
                    </form>                    
                    <!--Concepto final del Dictamen Pericial-->
                    <div class="card-info columna_row1_dictamen" @if ($decreto_1507=='1') style="display:block" @else style="display:none" @endif>
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Concepto final del Dictamen Pericial</h5>
                        </div>
                        <form id="form_dictamen_pericial" action="POST">                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">                                            
                                            @if(!empty($array_dictamen_pericial[0]->N_radicado))    
                                                <input type="hidden" class="form-control" name="radicado_dictamen" id="radicado_dictamen" value="{{$array_dictamen_pericial[0]->N_radicado}}" disabled>                                                
                                            @else
                                                <input type="hidden" class="form-control" name="radicado_dictamen" id="radicado_dictamen" value="{{$consecutivo}}" disabled> 
                                            @endif
                                                <input type="hidden" class="form-control" name="radicado_comunicado_manual" id="radicado_comunicado_manual" value="{{$consecutivo}}" disabled>
                                            <label for="porcentaje_pcl">% PCL</label>
                                            @if(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 2)
                                                <input type="text" class="form-control" name="porcentaje_pcl" id="porcentaje_pcl" value="0" disabled>                                                
                                            @elseif(!empty($array_dictamen_pericial[0]->Porcentaje_pcl))
                                                <input type="text" class="form-control" name="porcentaje_pcl" id="porcentaje_pcl" value="{{$array_dictamen_pericial[0]->Porcentaje_pcl}}" disabled>                                                
                                            @else
                                                <input type="text" class="form-control" name="porcentaje_pcl" id="porcentaje_pcl" disabled>                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="rango_pcl">Rango PCL</label>
                                            @if(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 2)
                                                <input type="text" class="form-control" name="rango_pcl" id="rango_pcl" value="0" disabled>                                                
                                            @elseif(!empty($array_dictamen_pericial[0]->Rango_pcl))
                                                <input type="text" class="form-control" name="rango_pcl" id="rango_pcl" value="{{$array_dictamen_pericial[0]->Rango_pcl}}" disabled>                                                
                                            @else
                                                <input type="text" class="form-control" name="rango_pcl" id="rango_pcl" disabled>                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="monto_inde">Monto Indemnización (Meses)</label>
                                            @if(!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion == 2)
                                                <input type="text" class="form-control" name="monto_inde" id="monto_inde" value="0" disabled>                                                
                                            @elseif(!empty($array_dictamen_pericial[0]->Monto_indemnizacion))
                                                <input type="text" class="form-control" name="monto_inde" id="monto_inde" value="{{$array_dictamen_pericial[0]->Monto_indemnizacion}}" disabled>                                                
                                            @else
                                                <input type="text" class="form-control" name="monto_inde" id="monto_inde" disabled>                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="tipo_evento">Tipo de evento<span style="color: red;">(*)</span></label>                                           
                                                <select class="tipo_evento custom-select" name="tipo_evento" id="tipo_evento" style="width: 100%;" required>
                                                    <option value="{{$array_tipo_fecha_evento[0]->Tipo_evento}}" selected>{{$array_tipo_fecha_evento[0]->Nombre_evento}}</option>
                                                    <option value="">Seleccione una opción</option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="tipo_origen">Origen<span style="color: red;">(*)</span></label>
                                            @if (!empty($array_dictamen_pericial[0]->Origen))
                                                <select class="tipo_origen custom-select" name="tipo_origen" id="tipo_origen" style="width: 100%" required>
                                                    <option value="{{$array_dictamen_pericial[0]->Origen}}">{{$array_dictamen_pericial[0]->Nombre_parametro}}</option>
                                                    <option value="">Seleccione una opción</option>
                                                </select>
                                            @else
                                                <select class="tipo_origen custom-select" name="tipo_origen" id="tipo_origen" style="width: 100%" required>
                                                    <option value="">Seleccione una opción</option>
                                                </select>                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-3" id="div_tipo_evento">
                                        <div class="form-group">
                                            <label for="f_evento_pericial">Fecha de evento<span style="color: red;">(*)</span></label>
                                            @if (!empty($array_dictamen_pericial[0]->F_evento) && $array_dictamen_pericial[0]->F_evento !== '0000-00-00')                                                
                                                <input type="date" class="f_evento_pericial form-control" id="f_evento_pericial" name="f_evento_pericial" value="{{$array_dictamen_pericial[0]->F_evento}}" max="{{now()->format('Y-m-d')}}" min='1900-01-01' required>                                                                                                
                                            @elseif(!empty($array_tipo_fecha_evento[0]->F_evento))
                                                <input type="date" class="f_evento_pericial form-control" id="f_evento_pericial" name="f_evento_pericial" value="{{$array_tipo_fecha_evento[0]->F_evento}}" max="{{now()->format('Y-m-d')}}" min='1900-01-01' required>
                                            @else
                                                <input type="date" class="f_evento_pericial form-control" id="f_evento_pericial" name="f_evento_pericial" max="{{now()->format('Y-m-d')}}" min='1900-01-01' required>                                                
                                            @endif 
                                            <span class="d-none" id="f_evento_pericial_alerta" style="color: red; font-style: italic;"></span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="f_estructura_pericial">Fecha de estructuración<span style="color: red;">(*)</span></label>                                            
                                            @if (!empty($array_dictamen_pericial[0]->F_estructuracion))                                                
                                                <input type="date" class="f_estructura_pericial form-control" id="f_estructura_pericial" name="f_estructura_pericial" value="{{$array_dictamen_pericial[0]->F_estructuracion}}" max="{{now()->format('Y-m-d')}}" min='1900-01-01' required>                                                
                                            @else                                               
                                                <input type="date" class="f_estructura_pericial form-control" id="f_estructura_pericial" name="f_estructura_pericial" max="{{now()->format('Y-m-d')}}" min='1900-01-01' required>                                                
                                            @endif
                                            <span class="d-none" id="f_estructura_pericial_alerta" style="color: red; font-style: italic;"></span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="n_siniestro">N° de Siniestro</label>                                            
                                            @if (!empty($N_siniestro_evento[0]->N_siniestro))                                                
                                                <input type="text" class="n_siniestro form-control" id="n_siniestro" name="n_siniestro" value="{{$N_siniestro_evento[0]->N_siniestro}}">                                                
                                            @else                                               
                                                <input type="text" class="n_siniestro form-control" id="n_siniestro" name="n_siniestro">                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <br>
                                            <div class="custom-control custom-checkbox">
                                                @if (!empty($array_dictamen_pericial[0]->Requiere_Revision_Pension))
                                                    <input class="custom-control-input" type="checkbox" id="requiere_rev_pension" name="requiere_rev_pension" value="Require Revision Pension" checked>
                                                @else
                                                    <input class="custom-control-input" type="checkbox" id="requiere_rev_pension" name="requiere_rev_pension" value="Require Revision Pension">
                                                @endif
                                                <label for="requiere_rev_pension" class="custom-control-label">¿Requiere revisión pensión?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="sustenta_fecha">Sustentación de fecha de estructuración<span style="color: red;">(*)</span></label>
                                            @if (!empty($array_dictamen_pericial[0]->Sustentacion_F_estructuracion))
                                                <textarea id="sustenta_fecha" class="form-control" name="sustenta_fecha" cols="90" rows="4" required>{{$array_dictamen_pericial[0]->Sustentacion_F_estructuracion}}</textarea>
                                            @else
                                                <textarea id="sustenta_fecha" class="form-control" name="sustenta_fecha" cols="90" rows="4" required></textarea>                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="detalle_califi">Detalle de la calificación<span style="color: red;">(*)</span></label>
                                            @if (!empty($array_dictamen_pericial[0]->Detalle_calificacion))
                                                <textarea id="detalle_califi" class="form-control" name="detalle_califi" cols="90" rows="4" required>{{$array_dictamen_pericial[0]->Detalle_calificacion}}</textarea>                                                
                                            @else
                                                <textarea id="detalle_califi" class="form-control" name="detalle_califi" cols="90" rows="4" required></textarea>                                                
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($array_info_decreto_evento[0]->Decreto_calificacion) && $array_info_decreto_evento[0]->Decreto_calificacion <> 2)
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if (!empty($array_dictamen_pericial[0]->Enfermedad_catastrofica))
                                                        <input class="custom-control-input" type="checkbox" id="enfermedad_catastrofica" name="enfermedad_catastrofica" value="Enfermedad Catastrófica" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="enfermedad_catastrofica" name="enfermedad_catastrofica" value="Enfermedad Catastrófica">                                                    
                                                    @endif
                                                    <label for="enfermedad_catastrofica" class="custom-control-label">Enfermedad Catastrófica</label>
                                                </div>
                                            </div>
                                        </div>                                                                            
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if (!empty($array_dictamen_pericial[0]->Enfermedad_congenita))
                                                        <input class="custom-control-input" type="checkbox" id="enfermedad_congenita" name="enfermedad_congenita" value="Enfermedad Congénita o cercana al nacimiento" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="enfermedad_congenita" name="enfermedad_congenita" value="Enfermedad Congénita o cercana al nacimiento">                                                    
                                                    @endif
                                                    <label for="enfermedad_congenita" class="custom-control-label">Enfermedad Congénita o cercana al nacimiento</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tipo_enfermedad">Tipo de enfermedad</label>
                                                @if (!empty($array_dictamen_pericial[0]->Tipo_enfermedad))
                                                    <select class="tipo_enfermedad custom-select" name="tipo_enfermedad" id="tipo_enfermedad" style="width: 100%;">
                                                        <option value="{{$array_dictamen_pericial[0]->Tipo_enfermedad}}">{{$array_dictamen_pericial[0]->TipoEnfermedad}}</option>
                                                        <option value="">Seleccione una opción</option>
                                                    </select>
                                                @else
                                                    <select class="tipo_enfermedad custom-select" name="tipo_enfermedad" id="tipo_enfermedad" style="width: 100%;">
                                                        <option value="">Seleccione una opción</option>
                                                    </select>                                                
                                                @endif
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
                                                    @if (!empty($array_dictamen_pericial[0]->Requiere_tercera_persona))
                                                        <input class="dependencia_justificacion custom-control-input" type="checkbox" id="requiere_persona" name="requiere_persona" value="Requiere tercera persona" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="requiere_persona" name="requiere_persona" value="Requiere tercera persona">                                                    
                                                    @endif
                                                    <label for="requiere_persona" class="custom-control-label">Requiere tercera persona</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if (!empty($array_dictamen_pericial[0]->Requiere_tercera_persona_decisiones))
                                                        <input class="dependencia_justificacion custom-control-input" type="checkbox" id="requiere_decisiones_persona" name="requiere_decisiones_persona" value="Requiere de tercera persona para la toma de decisiones" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="requiere_decisiones_persona" name="requiere_decisiones_persona" value="Requiere de tercera persona para la toma de decisiones">                                                    
                                                    @endif
                                                    <label for="requiere_decisiones_persona" class="custom-control-label">Requiere de tercera persona para la toma de decisiones</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if (!empty($array_dictamen_pericial[0]->Requiere_dispositivo_apoyo))
                                                        <input class="dependencia_justificacion custom-control-input" type="checkbox" id="requiere_dispositivo_apoyo" name="requiere_dispositivo_apoyo" value="Requiere de dispositivo de apoyo" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="requiere_dispositivo_apoyo" name="requiere_dispositivo_apoyo" value="Requiere de dispositivo de apoyo">                                                    
                                                    @endif
                                                    <label for="requiere_dispositivo_apoyo" class="custom-control-label">Requiere de dispositivo de apoyo</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-none" id="justiDependencia">
                                            <div class="form-group">
                                                <label for="justi_dependencia">Justificación de dependencia</label>
                                                @if (!empty($array_dictamen_pericial[0]->Justificacion_dependencia))
                                                    <textarea id="justi_dependencia" class="form-control" name="justi_dependencia" cols="90" rows="4">{{$array_dictamen_pericial[0]->Justificacion_dependencia}}</textarea>
                                                @else
                                                    <textarea id="justi_dependencia" class="form-control" name="justi_dependencia" cols="90" rows="4"></textarea>                                                
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">                                             
                                            @if (!empty($array_dictamen_pericial[0]->F_estructuracion) && $array_dictamen_pericial[0]->F_estructuracion !== '0000-00-00')
                                                <input type="submit" id="GuardrDictamenPericial" name="GuardrDictamenPericial" class="btn btn-info" value="Actualizar">                                                                                                                                                
                                                <input hidden="hidden" type="text" id="bandera_dictamen_pericial" value="Actualizar">                                                                                           
                                            @else
                                                <input type="submit" id="GuardrDictamenPericial" name="GuardrDictamenPericial" class="btn btn-info" value="Guardar">                                                                                                
                                                <input hidden="hidden" type="text" id="bandera_dictamen_pericial" value="Guardar">                                                                                           
                                            @endif
                                        </div>
                                    </div>
                                    <div id="div_alerta_dictamen_pericial" class="col-12 d-none">
                                        <div class="form-group"> 
                                            <div class="alerta_dictamen_pericial alert alert-success mt-2 mr-auto" role="alert"></div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </form>
                    </div>
                    <!-- Comite Interdisciplinario -->                    
                    <div class="card-info d-none" id="div_comite_interdisciplinario">
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Comité Interdisciplinario</h5>
                            <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
                        </div>
                        <form id="form_comite_interdisciplinario" action="POST">                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">                                               
                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Despues de <b>Guardar</b> el <b>Comité Interdiciplinario</b> no podra hacer más actualizaciones en la Calificacion Técnica. <br>
                                    </div>
                                    @if (empty($array_comite_interdisciplinario[0]->Visar) && !empty($array_laboralmente_Activo[0]->Edad_cronologica_menor) || empty($array_comite_interdisciplinario[0]->Visar) && !empty($array_laboralmente_Activo[0]->Edad_cronologica))
                                        <div class="alert alert-warning" role="alert" id="div_alerta_sirena">
                                            <i class="fas fa-exclamation-triangle sirena"></i> <strong>Importante:</strong> La edad del afiliado a cambiado, debe actualizar Título II, en la Tabla 3 - Edad cronológica el rago es distinto. <i class="fas fa-exclamation-triangle sirena"></i>
                                        </div>                                            
                                    @endif
                                </div>
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
                        <form id="form_correspondencia_pcl" action="POST">                            
                            <div class="card-body">
                                <div class="row">
                                    {{-- @if (!empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion <> 2)
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">                                                
                                                    <input class="custom-control-input" type="checkbox" id="notificacionpcl" name="notificacionpcl" value="notificacionpcl">                                                
                                                    <label for="notificacionpcl" class="custom-control-label">Notificación</label>
                                                </div>
                                            </div>
                                        </div>                                         
                                    @else
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">                                                
                                                    <input class="custom-control-input" type="checkbox" id="notificacionpclcero" name="notificacionpclcero" value="notificacionpcl">                                                
                                                    <label for="notificacionpclcero" class="custom-control-label">Notificación</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif --}}
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                @if (!empty($array_comite_interdisciplinario[0]->Oficio_pcl) && $array_comite_interdisciplinario[0]->Oficio_pcl == 'Si')
                                                    <input class="dependencia_justificacion custom-control-input" type="checkbox" id="oficiopcl" name="oficiopcl" value="Si" checked>
                                                @else
                                                    <input class="custom-control-input" type="checkbox" id="oficiopcl" name="oficiopcl" value="Si" required>                                                    
                                                @endif
                                                <label for="oficiopcl" class="custom-control-label">Oficio PCL</label>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                @if (!empty($array_comite_interdisciplinario[0]->Oficio_incapacidad) && $array_comite_interdisciplinario[0]->Oficio_incapacidad == 'Si')
                                                    <input class="dependencia_justificacion custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" checked>
                                                @else
                                                    <input class="custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" required>                                                    
                                                @endif
                                                <label for="oficioinca" class="custom-control-label">Oficio Incapacidad</label>
                                            </div>
                                        </div>
                                    </div> 
                                    {{-- <div class="col-3" style="display: flex; flex-direction: row; justify-content:space-between;">
                                        <div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if (!empty($array_comite_interdisciplinario[0]->Oficio_incapacidad) && $array_comite_interdisciplinario[0]->Oficio_incapacidad == 'Si')
                                                        <input class="dependencia_justificacion custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" required>                                                    
                                                    @endif
                                                    <label for="oficioinca" class="custom-control-label">Formato C</label>
                                                </div>
                                            </div>
                                        </div> 
                                        <div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if (!empty($array_comite_interdisciplinario[0]->Oficio_incapacidad) && $array_comite_interdisciplinario[0]->Oficio_incapacidad == 'Si')
                                                        <input class="dependencia_justificacion custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" required>                                                    
                                                    @endif
                                                    <label for="oficioinca" class="custom-control-label">Formato D</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if (!empty($array_comite_interdisciplinario[0]->Oficio_incapacidad) && $array_comite_interdisciplinario[0]->Oficio_incapacidad == 'Si')
                                                        <input class="dependencia_justificacion custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="oficioinca" name="oficioinca" value="Si" required>                                                    
                                                    @endif
                                                    <label for="oficioinca" class="custom-control-label">Formato E</label>
                                                </div>
                                            </div>
                                        </div>  
                                    </div> --}}
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="destinatario_principal">Destinatario Principal</label>                                            
                                            <input type="text" class="form-control" name="destinatario_principal" id="destinatario_principal" value="{{$array_datos_calificacionPclTecnica[0]->Nombre_afiliado}}" disabled>
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
                                            <input type="text" class="form-control" name="nombre_destinatario_afi" id="nombre_destinatario_afi" value="{{$array_datos_calificacionPclTecnica[0]->Nombre_afiliado}}" disabled>
                                        </div>      
                                    </div>
                                    <div class="col-3" id="div_nombre_destinatariopri_empl">
                                        <div class="form-group">
                                            <label for="nombre_destinatario_emp">Nombre del destinatario principal<span style="color: red;">(*)</span></label>
                                            <input type="text" class="form-control" name="nombre_destinatario_emp" id="nombre_destinatario_emp" value="{{$array_datos_calificacionPclTecnica[0]->Empleador_afi}}" disabled>
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
                                            <label for="Asunto">Asunto<span style="color: red;">(*)</label>
                                            @if(!empty($array_comite_interdisciplinario[0]->Asunto))
                                                <input type="text" class="form-control" name="Asunto" id="Asunto" value="{{$array_comite_interdisciplinario[0]->Asunto}}" required>                                                
                                            @else
                                                <input type="text" class="form-control" name="Asunto" id="Asunto" required>                                                
                                            @endif
                                        </div>              
                                    </div>
                                    <div class="col-12 alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Recuerde si el oficio o formato seleccionado para la proforma tiene etiquetas o (botones) debe incluirlas obligatoriamente dentrol del cuerpo del comunicado, de lo contrario no se mostrará el cuerpo del comunicado en el PDF.
                                    </div>
                                    {{-- @if (!empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion <> 2) --}}
                                    <div class="col-12">
                                        <div class="form-group">                                            
                                            <label for="cuerpo_comunicado">Cuerpo del comunicado<span style="color: red;">(*)</label>
                                            <br>
                                            <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_Nombre_afiliado">Nombre afiliado</button>
                                            <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_porPcl">% PCL</button>
                                            <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_F_estructuracion">Fecha de estructuracion</button>
                                            <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_Origen">Origen</button>
                                            @if(!empty($array_comite_interdisciplinario[0]->Cuerpo_comunicado))
                                                <input type="hidden" id="rellenar_textarea" value="llenar">
                                                <textarea class="form-control" name="cuerpo_comunicado" id="cuerpo_comunicado" required>{{$array_comite_interdisciplinario[0]->Cuerpo_comunicado}}</textarea>                                                                                                 
                                            @else
                                                <input type="hidden" id="rellenar_textarea" value="Nollenar">
                                                <textarea class="form-control" name="cuerpo_comunicado" id="cuerpo_comunicado" required></textarea>                                                                                              
                                            @endif                                            
                                        </div>
                                    </div>                                         
                                    {{-- @else
                                        <div class="col-12">
                                            <div class="form-group">                                            
                                                <label for="cuerpo_comunicado_cero">Cuerpo del comunicado<span style="color: red;">(*)</label>
                                                <br>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_porPcl_cero">% PCL</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_nombreCIE10_cero">Nombre CIE10</button>

                                                @if(!empty($array_comite_interdisciplinario[0]->Cuerpo_comunicado))
                                                    <input type="hidden" id="rellenar_textareacero" value="llenarcero">
                                                    <textarea class="form-control" name="cuerpo_comunicado_cero" id="cuerpo_comunicado_cero" required>{{$array_comite_interdisciplinario[0]->Cuerpo_comunicado}}</textarea>                                                                                                 
                                                @else
                                                    <input type="hidden" id="rellenar_textareacero" value="Nollenarcero">
                                                    <textarea class="form-control" name="cuerpo_comunicado_cero" id="cuerpo_comunicado_cero" required></textarea>                                                                                              
                                                @endif                                            
                                            </div>
                                        </div>
                                    @endif --}}
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="tipo_clasificacion">Copia a partes interesadas</label>
                                        </div>
                                    </div>
                                    {{-- <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                @if (!empty($array_comite_interdisciplinario[0]->Copia_empleador))
                                                    <input class="custom-control-input" type="checkbox" id="afiliado" name="afiliado" value="Afiliado" checked>
                                                @else
                                                    <input class="custom-control-input" type="checkbox" id="afiliado" name="afiliado" value="Afiliado">
                                                @endif
                                                <label for="afiliado" class="custom-control-label">Afiliado</label>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                @if (!empty($array_comite_interdisciplinario[0]->Copia_afiliado))
                                                    <input class="custom-control-input" type="checkbox" id="afiliado" name="afiliado" value="Afiliado" checked>
                                                @else
                                                    <input class="custom-control-input" type="checkbox" id="afiliado" name="afiliado" value="Afiliado">
                                                @endif
                                                <label for="afiliado" class="custom-control-label">Afiliado</label>
                                            </div>
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
                                    <input type="hidden" name="entidad_conocimiento" id="entidad_conocimiento" value="<?php if(!empty($info_afp_conocimiento[0]->Entidad_conocimiento)){echo $info_afp_conocimiento[0]->Entidad_conocimiento;}?>">
                                    @if (!empty($info_afp_conocimiento[0]->Entidad_conocimiento) && $info_afp_conocimiento[0]->Entidad_conocimiento == "Si")
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    @if (!empty($array_comite_interdisciplinario[0]->Copia_afp_conocimiento))
                                                        <input class="dependencia_justificacion custom-control-input" type="checkbox" id="afp_conocimiento" name="afp_conocimiento" value="AFP_Conocimiento" checked>
                                                    @else
                                                        <input class="custom-control-input" type="checkbox" id="afp_conocimiento" name="afp_conocimiento" value="AFP_Conocimiento">                                                    
                                                    @endif
                                                    <label for="afp_conocimiento" class="custom-control-label">AFP Conocimiento</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                    {{-- <div class="col-4">
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
                                    </div>  --}}
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
                                            @if(!empty($array_comite_interdisciplinario[0]->Elaboro) && $array_comite_interdisciplinario[0]->Elaboro == $user->name)
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
                    <div class="card-info" id="div_comunicado_dictamen_oficioremisorio">
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Comunicados</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">                                               
                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Recuerde que despues de generar o actualizar un archivo debe descargarlo y recargar la pagina para poder reemplazarlo
                                    </div>
                                </div>
                            </div>
                            <div class="row">  
                                <div class="col-12">
                                    <div class="form-group">          
                                        <input type="hidden" id="descargar_dictamenesPcl" value="{{ route('descargar_Dictamen_PCL') }}">
                                        <div class="table-responsive">
                                            <table id="listado_comunicados_clpcl" class="table table-striped table-bordered" style="width: 100%;  white-space: nowrap;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>N° de Radicado</th>
                                                        <th>Elaboró</th>
                                                        <th>Fecha de comunicado</th>
                                                        <th>Documento</th>
                                                        <th>Destinatarios</th>
                                                        <th>Estado general de la Notificación</th>
                                                        <th>Nota</th>                                                        
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion == 1)                                                            
                                                        @foreach ($array_comunicados_correspondencia as $index => $comunicados)
                                                            <input type="hidden" id="status_default_{{$comunicados['N_radicado']}}" value="{{$comunicados['Estado_Notificacion']}}">
                                                            <input type="hidden" id="Nota_comunicado_{{$comunicados['N_radicado']}}" value="{{$comunicados['Nota']}}">                                                        
                                                            <input type="hidden" id="Estado_Correspondencia_{{$comunicados['N_radicado']}}" value="{{$comunicados['Estado_correspondencia'] ?? '0'}}">
                                                            @if ($comunicados->Tipo_descarga != 'Oficio')
                                                                <tr>
                                                                    {{-- Generar pdf Dictamen PCL 1507 --}}
                                                                    <td data-id_comunicado="{{$comunicados['Id_Comunicado'] ?? null}}">{{$comunicados['N_radicado']}}</td>
                                                                    <td>{{$comunicados['Elaboro']}}</td>
                                                                    <td>{{$comunicados['F_comunicado']}}</td>
                                                                    <td><?php if($comunicados->Tipo_descarga == 'Manual'){echo $comunicados->Asunto;}else{echo $comunicados->Tipo_descarga;}?></td>                                                                       
                                                                    @if ($comunicados->Ciudad == 'N/A' && $comunicados->Tipo_descarga != 'Manual')
                                                                        <td style="display: flex; flex-direction:row; justify-content:space-around;">                                                                    
                                                                            <form name="ver_dictamenPcl" data-archivo="{{json_encode($comunicados)}}" @if($comunicados->Reemplazado === 1) id="ver_dictamentPCL" @else action="{{ route('descargar_Dictamen_PCL') }}" @endif method="POST"> 
                                                                                @csrf                                                                
                                                                                    <input type="hidden"  name="ID_Evento_comuni" value="{{$comunicados['ID_evento']}}">
                                                                                    <input type="hidden"  name="Id_Asignacion_comuni" value="{{$comunicados['Id_Asignacion']}}">
                                                                                    <input type="hidden"  name="Id_Proceso_comuni" value="{{$comunicados['Id_proceso']}}">     
                                                                                    <input type="hidden"  name="Radicado_comuni" value="{{$comunicados['N_radicado']}}">
                                                                                    <input type="hidden"  name="Id_Comunicado" value="{{$comunicados['Id_Comunicado']}}">
                                                                                    <input type="hidden"  name="N_siniestro" value="{{$comunicados['N_siniestro']}}">
                                                                                    <label for="ver_dictamenesPcl"><i class="far fa-eye text-info" style="cursor: pointer;"></i></i></label>
                                                                                    <input class="btn-icon-only text-info btn-sm" name="ver_dictamenesPcl" id="ver_dictamenesPcl" type="submit" style="font-weight: bold;" value="">
                                                                            </form> 
                                                                            @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                                <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                                    <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" >
                                                                                        <i class="fas fa-sync-alt text-info"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif       
                                                                        </td> 
                                                                    @elseif ($comunicados->Tipo_descarga == 'Manual')  
                                                                        <td style="display: flex; flex-direction:row; justify-content:space-around;">
                                                                            <form id="form_descargar_archivo_{{$comunicados->Id_Comunicado}}" data-archivo="{{$comunicados}}" method="POST">
                                                                                <button type="submit" id="btn_descargar_archivo_{{$comunicados->Id_Comunicado}}" style="border: none; background:transparent;">
                                                                                    <i class="far fa-eye text-info" style="cursor: pointer;"></i>
                                                                                </button>
                                                                            </form>
                                                                            @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                                <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                                    <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" >
                                                                                        <i class="fas fa-sync-alt text-info"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        @if (!empty($array_comunicados_comite_inter[0]->Asunto))
                                                            @foreach ($array_comunicados_comite_inter as $comite_inter)
                                                            <tr>
                                                                <td>{{$comite_inter->N_radicado}}</td>
                                                                <td>{{$comite_inter->Elaboro}}</td>
                                                                <td>{{$comite_inter->F_visado_comite}}</td>
                                                                <?php 
                                                                    if($comite_inter->Oficio_pcl && $comite_inter->Oficio_pcl === 'Si'){
                                                                        $tipo_descarga = 'Oficio PCL';
                                                                    }
                                                                    else if($comite_inter->Oficio_incapacidad && $comite_inter->Oficio_incapacidad === 'Si'){
                                                                        $tipo_descarga = 'Oficio Incapacidad';
                                                                    }
                                                                    else{
                                                                        $tipo_descarga = 'Oficio';
                                                                    }
                                                                ?>
                                                                <td><?php echo $tipo_descarga;?></td>
                                                                <td style="display: flex; flex-direction:row; justify-content:space-around; align-items:center;">
                                                                    <form name="ver_notificacionPcl" data-archivo="{{json_encode($comite_inter)}}" @if($comite_inter->Reemplazado === 1) id="verNotificacionPCL" @else action="{{ route('generarOficio_Pcl') }}" @endif method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="ID_Evento_comuni_comite" value="{{$comite_inter->ID_evento}}">
                                                                        <input type="hidden" name="Id_Asignacion_comuni_comite" value="{{$comite_inter->Id_Asignacion}}">
                                                                        <input type="hidden" name="Id_Proceso_comuni_comite" value="{{$comite_inter->Id_proceso}}">    
                                                                        <input type="hidden" name="Radicado_comuni_comite" value="{{$comite_inter->N_radicado}}"> 
                                                                        <input type="hidden" name="Firma_comuni_comite" value="{{$comite_inter->Firmar}}">
                                                                        <input type="hidden" name="Id_Comunicado" value="{{$comite_inter->Id_Comunicado}}">
                                                                        <input type="hidden"  name="N_siniestro" value="{{$comite_inter->N_siniestro}}">
                                                                        <label for="ver_notificacionesPcl" style="margin-bottom: 0px;"><i class="far fa-eye text-info" style="cursor: pointer;"></i></label>
                                                                        <input class="btn-icon-only text-info btn-sm" name="ver_notificacionesPcl" id="ver_notificacionesPcl" type="submit" style="font-weight: bold; cursor: pointer;" value="">
                                                                    </form>
                                                                    @if($dato_rol !== '7')
                                                                        <i class="fa fa-pen text-info" id="editar_correspondencia" style="cursor: pointer;"></i>
                                                                    @endif
                                                                    @if ($comite_inter->Existe && $dato_rol !== '7')
                                                                        <form id="form_reemplazar_archivo_" data-archivo="{{json_encode($comite_inter)}}" method="POST">
                                                                            <button type="submit" id="btn_reemplazar_archivo" style="border: none; background: transparent;">
                                                                                <i class="fas fa-sync-alt text-info"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>                                                                                                                               
                                                            </tr>
                                                            @endforeach                                                                
                                                        @endif     
                                                    @elseif(!empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion == 2)                                                            
                                                        @foreach ($array_comunicados_correspondencia as $index => $comunicados)
                                                            <input type="hidden" id="status_default_{{$comunicados['N_radicado']}}" value="{{$comunicados['Estado_Notificacion']}}">
                                                            <input type="hidden" id="Nota_comunicado_{{$comunicados['N_radicado']}}" value="{{$comunicados['Nota']}}">         
                                                            <input type="hidden" id="Estado_Correspondencia_{{$comunicados['N_radicado']}}" value="{{$comunicados['Estado_correspondencia'] ?? '0'}}">
                                                            @if ($comunicados->Tipo_descarga != 'Oficio')
                                                                <tr>
                                                                    {{-- Generar pdf Dictamen PCL Cero --}}
                                                                    <td data-id_comunicado="{{$comunicados['Id_Comunicado'] ?? null}}">{{$comunicados['N_radicado']}}</td>
                                                                    <td>{{$comunicados['Elaboro']}}</td>
                                                                    <td>{{$comunicados['F_comunicado']}}</td>
                                                                    <td><?php if($comunicados->Tipo_descarga == 'Manual'){echo $comunicados->Asunto;}else{echo $comunicados->Tipo_descarga;}?></td>                                                                       
                                                                    @if ($comunicados->Ciudad == 'N/A' && $comunicados->Tipo_descarga != 'Manual')
                                                                        <td style="display: flex; flex-direction:row; justify-content:space-around;">                                                                    
                                                                            <form name="ver_dictamenPcl" data-archivo="{{json_encode($comunicados)}}" @if($comunicados->Reemplazado === 1) id="ver_dictamentPCL" @else action="{{ route('descargar_Dictamen_PCLCero') }}" @endif method="POST"> 
                                                                                @csrf                        
                                                                                <input type="hidden"  name="ID_Evento_comuni" value="{{$comunicados['ID_evento']}}">
                                                                                <input type="hidden"  name="Id_Asignacion_comuni" value="{{$comunicados['Id_Asignacion']}}">
                                                                                <input type="hidden"  name="Id_Proceso_comuni" value="{{$comunicados['Id_proceso']}}">     
                                                                                <input type="hidden"  name="Radicado_comuni" value="{{$comunicados['N_radicado']}}">
                                                                                <input type="hidden"  name="Id_Comunicado" value="{{$comunicados['Id_Comunicado']}}">
                                                                                <input type="hidden"  name="N_siniestro" value="{{$comunicados['N_siniestro']}}">
                                                                                <label for="ver_dictamenesPcl"><i class="far fa-eye text-info" style="cursor: pointer;"></i></label>
                                                                                <input class="btn-icon-only text-info btn-sm" name="ver_dictamenesPcl" id="ver_dictamenesPcl" type="submit" style="font-weight: bold;" value="">
                                                                            </form>
                                                                            @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                                <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                                    <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" >
                                                                                        <i class="fas fa-sync-alt text-info"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </td>                                                                
                                                                    @elseif ($comunicados->Tipo_descarga == 'Manual')  
                                                                        <td style="display: flex; flex-direction:row; justify-content:space-around;">
                                                                            <form id="form_descargar_archivo_{{$comunicados->Id_Comunicado}}" data-archivo="{{$comunicados}}" method="POST">
                                                                                <button type="submit" id="btn_descargar_archivo_{{$comunicados->Id_Comunicado}}" style="border: none; background:transparent;">
                                                                                    <i class="far fa-eye text-info" style="cursor: pointer;"></i>
                                                                                </button>
                                                                            </form>
                                                                            @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                                <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                                    <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" >
                                                                                        <i class="fas fa-sync-alt text-info"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </td>                                                             
                                                                    @endif
                                                                </tr>
                                                            @endif 
                                                        @endforeach
                                                        @if (!empty($array_comunicados_comite_inter[0]->Asunto))
                                                            @foreach ($array_comunicados_comite_inter as $comite_inter)
                                                            <tr>
                                                                <td>{{$comite_inter->N_radicado}}</td>
                                                                <td>{{$comite_inter->Elaboro}}</td>
                                                                <td>{{$comite_inter->F_visado_comite}}</td>
                                                                <?php 
                                                                    if($comite_inter->Oficio_pcl && $comite_inter->Oficio_pcl === 'Si'){
                                                                        $tipo_descarga = 'Oficio PCL';
                                                                    }
                                                                    else if($comite_inter->Oficio_incapacidad && $comite_inter->Oficio_incapacidad === 'Si'){
                                                                        $tipo_descarga = 'Oficio Incapacidad';
                                                                    }
                                                                    else{
                                                                        $tipo_descarga = 'Oficio';
                                                                    }
                                                                ?>
                                                                <td><?php echo $tipo_descarga;?></td>
                                                                <td style="display: flex; flex-direction:row; justify-content:space-around; align-items:center;">
                                                                    <form name="ver_notificacionPcl" data-archivo="{{json_encode($comite_inter)}}" @if($comite_inter->Reemplazado === 1) id="verNotificacionPCL" @else action="{{ route('generarOficio_Pcl') }}" @endif method="POST">
                                                                        @csrf
                                                                        <input type="hidden"  name="ID_Evento_comuni_comite" value="{{$comite_inter->ID_evento}}">
                                                                        <input type="hidden"  name="Id_Asignacion_comuni_comite" value="{{$comite_inter->Id_Asignacion}}">
                                                                        <input type="hidden"  name="Id_Proceso_comuni_comite" value="{{$comite_inter->Id_proceso}}">    
                                                                        <input type="hidden"  name="Radicado_comuni_comite" value="{{$comite_inter->N_radicado}}"> 
                                                                        <input type="hidden"  name="Firma_comuni_comite" value="{{$comite_inter->Firmar}}">
                                                                        <input type="hidden"  name="Id_Comunicado" value="{{$comite_inter->Id_Comunicado}}">
                                                                        <input type="hidden"  name="N_siniestro" value="{{$comite_inter->N_siniestro}}">
                                                                        <label for="ver_notificacionesPcl" style="margin-bottom: 0px;"><i class="far fa-eye text-info" style="cursor: pointer;"></i></label>
                                                                        <input class="btn-icon-only text-info btn-sm" name="ver_notificacionesPcl" id="ver_notificacionesPcl" type="submit" style="font-weight: bold; cursor:pointer;" value="">
                                                                    </form>
                                                                    @if($dato_rol !== '7')
                                                                        <i class="fa fa-pen text-info" style="cursor:pointer;" id="editar_correspondencia"></i>
                                                                    @endif
                                                                    @if ($comite_inter->Existe && $dato_rol !== '7')
                                                                        <form id="form_reemplazar_archivo_" data-archivo="{{json_encode($comite_inter)}}" method="POST">
                                                                            <button type="submit" id="btn_reemplazar_archivo_" style="border: none; background: transparent;">
                                                                                <i class="fas fa-sync-alt text-info"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>                                                                                                                               
                                                            </tr>
                                                            @endforeach
                                                        @endif                                                   
                                                    @elseif(!empty($datos_demos[0]->Decreto_calificacion) && $datos_demos[0]->Decreto_calificacion == 3)                                                            
                                                        @foreach ($array_comunicados_correspondencia as $index => $comunicados)
                                                            <input type="hidden" id="status_default_{{$comunicados['N_radicado']}}" value="{{$comunicados['Estado_Notificacion']}}">
                                                            <input type="hidden" id="Nota_comunicado_{{$comunicados['N_radicado']}}" value="{{$comunicados['Nota']}}"> 
                                                            <input type="hidden" id="Estado_Correspondencia_{{$comunicados['N_radicado']}}" value="{{$comunicados['Estado_correspondencia'] ?? '0'}}">
                                                            @if ($comunicados->Tipo_descarga != 'Oficio')
                                                                <tr>
                                                                    {{-- Generar pdf Dictamen PCL 917 --}}
                                                                    <td data-id_comunicado="{{$comunicados['Id_Comunicado'] ?? null}}">{{$comunicados['N_radicado']}}</td>
                                                                    <td>{{$comunicados['Elaboro']}}</td>
                                                                    <td>{{$comunicados['F_comunicado']}}</td>  
                                                                    <td><?php if($comunicados->Tipo_descarga == 'Manual'){echo $comunicados->Asunto;}else{echo $comunicados->Tipo_descarga;}?></td> 
                                                                    @if ($comunicados->Ciudad == 'N/A' && $comunicados->Tipo_descarga != 'Manual')
                                                                        <td style="display: flex; flex-direction:row; justify-content:space-around; align-items:center;">
                                                                            <form name="ver_dictamenPcl" data-archivo="{{json_encode($comunicados)}}" @if($comunicados->Reemplazado === 1) id="ver_dictamentPCL" @else action="{{ route('descargar_Dictamen_PCL917') }}" @endif method="POST"> 
                                                                                @csrf              
                                                                                    <input type="hidden"  name="ID_Evento_comuni" value="{{$comunicados['ID_evento']}}">
                                                                                    <input type="hidden"  name="Id_Asignacion_comuni" value="{{$comunicados['Id_Asignacion']}}">
                                                                                    <input type="hidden"  name="Id_Proceso_comuni" value="{{$comunicados['Id_proceso']}}">     
                                                                                    <input type="hidden"  name="Radicado_comuni" value="{{$comunicados['N_radicado']}}"> 
                                                                                    <input type="hidden"  name="Id_Comunicado" value="{{$comunicados['Id_Comunicado']}}">
                                                                                    <input type="hidden"  name="N_siniestro" value="{{$comunicados['N_siniestro']}}">
                                                                                    <label for="ver_dictamenesPcl" style="margin-bottom: 0px;"><i class="far fa-eye text-info" style="cursor: pointer;"></i></i></label>
                                                                                    <input class="btn-icon-only text-info btn-sm" name="ver_dictamenesPcl" id="ver_dictamenesPcl" type="submit" style="font-weight: bold;" value="">
                                                                            </form>
                                                                            @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                                <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                                    <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" >
                                                                                        <i class="fas fa-sync-alt text-info"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </td>                                                                
                                                                    @elseif ($comunicados->Tipo_descarga == 'Manual')
                                                                        <td style="display: flex; flex-direction:row; justify-content:space-around;">
                                                                            <form id="form_descargar_archivo_{{$comunicados->Id_Comunicado}}" data-archivo="{{$comunicados}}" method="POST">
                                                                                <button type="submit" id="btn_descargar_archivo_{{$comunicados->Id_Comunicado}}" style="border: none; background:transparent;">
                                                                                    <i class="far fa-eye text-info"></i>
                                                                                </button>
                                                                            </form>
                                                                            @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                                <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                                    <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" >
                                                                                        <i class="fas fa-sync-alt text-info"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        @if (!empty($array_comunicados_comite_inter[0]->Asunto))
                                                            @foreach ($array_comunicados_comite_inter as $comite_inter)
                                                            <tr>
                                                                <td>{{$comite_inter->N_radicado}}</td>
                                                                <td>{{$comite_inter->Elaboro}}</td>
                                                                <td>{{$comite_inter->F_visado_comite}}</td>
                                                                <?php 
                                                                    if($comite_inter->Oficio_pcl && $comite_inter->Oficio_pcl === 'Si'){
                                                                        $tipo_descarga = 'Oficio PCL';
                                                                    }
                                                                    else if($comite_inter->Oficio_incapacidad && $comite_inter->Oficio_incapacidad === 'Si'){
                                                                        $tipo_descarga = 'Oficio Incapacidad';
                                                                    }
                                                                    else{
                                                                        $tipo_descarga = 'Oficio';
                                                                    }
                                                                ?>
                                                                <td><?php echo $tipo_descarga;?></td>
                                                                <td style="display: flex; flex-direction:row; justify-content:space-around; align-items:center;">
                                                                    <form name="ver_notificacionPcl" data-archivo="{{json_encode($comite_inter)}}" @if($comite_inter->Reemplazado === 1) id="verNotificacionPCL" @else action="{{ route('generarOficio_Pcl') }}" @endif method="POST">
                                                                        @csrf
                                                                        <input type="hidden"  name="ID_Evento_comuni_comite" value="{{$comite_inter->ID_evento}}">
                                                                        <input type="hidden"  name="Id_Asignacion_comuni_comite" value="{{$comite_inter->Id_Asignacion}}">
                                                                        <input type="hidden"  name="Id_Proceso_comuni_comite" value="{{$comite_inter->Id_proceso}}">    
                                                                        <input type="hidden"  name="Radicado_comuni_comite" value="{{$comite_inter->N_radicado}}"> 
                                                                        <input type="hidden"  name="Firma_comuni_comite" value="{{$comite_inter->Firmar}}">
                                                                        <input type="hidden"  name="Id_Comunicado" value="{{$comite_inter->Id_Comunicado}}">
                                                                        <input type="hidden"  name="N_siniestro" value="{{$comite_inter->N_siniestro}}">
                                                                        <label for="ver_notificacionesPcl" style="margin-bottom: 0px;"><i class="far fa-eye text-info" style="cursor: pointer;"></i></label>
                                                                        <input class="btn-icon-only text-info btn-sm" name="ver_notificacionesPcl" id="ver_notificacionesPcl" type="submit" style="font-weight: bold; cursor: pointer;" value="">
                                                                    </form>
                                                                    @if($dato_rol !== '7')
                                                                        <i class="fa fa-pen text-info" id="editar_correspondencia" style="cursor: pointer;"></i>
                                                                    @endif
                                                                    @if ($comite_inter->Existe && $dato_rol !== '7')
                                                                        <form id="form_reemplazar_archivo_" data-archivo="{{json_encode($comite_inter)}}" method="POST">
                                                                            <button type="submit" id="btn_reemplazar_archivo_" style="border: none; background: transparent;">
                                                                                <i class="fas fa-sync-alt text-info"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>                                                                                                                                  
                                                            </tr>
                                                            @endforeach
                                                        @endif
                                                    @elseif(empty($datos_demos[0]->Decreto_calificacion))
                                                        @foreach ($array_comunicados_correspondencia as $index => $comunicados)
                                                        <input type="hidden" id="status_default_{{$comunicados['N_radicado']}}" value="{{$comunicados['Estado_Notificacion']}}">
                                                        <input type="hidden" id="Nota_comunicado_{{$comunicados['N_radicado']}}" value="{{$comunicados['Nota']}}"> 
                                                        <input type="hidden" id="Estado_Correspondencia_{{$comunicados['N_radicado']}}" value="{{$comunicados['Estado_correspondencia'] ?? '0'}}">
                                                            @if ($comunicados->Tipo_descarga != 'Oficio')
                                                                <tr>
                                                                    {{-- Documentos cargados manualmente  --}}
                                                                    <td data-id_comunicado="{{$comunicados['Id_Comunicado'] ?? null}}">{{$comunicados['N_radicado']}}</td>
                                                                    <td>{{$comunicados['Elaboro']}}</td>
                                                                    <td>{{$comunicados['F_comunicado']}}</td>  
                                                                    <td><?php if($comunicados->Tipo_descarga == 'Manual'){echo $comunicados->Asunto;}else{echo $comunicados->Tipo_descarga;}?></td>                                                                       
                                                                    @if ($comunicados->Tipo_descarga == 'Manual')  
                                                                        <td style="display: flex; flex-direction:row; justify-content:space-around;">
                                                                            <form id="form_descargar_archivo_{{$comunicados->Id_Comunicado}}" data-archivo="{{$comunicados}}" method="POST">
                                                                                <button type="submit" id="btn_descargar_archivo_{{$comunicados->Id_Comunicado}}" style="border: none; background:transparent;">
                                                                                    <i class="far fa-eye text-info" style="cursor: pointer;"></i>
                                                                                </button>
                                                                            </form>
                                                                            @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                                <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                                    <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" >
                                                                                        <i class="fas fa-sync-alt text-info"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif   
                                                                        </td>                                                             
                                                                    @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                            <div class="alert alert-danger cargueundocumentoprimero d-none" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Por favor, adjunta un documento antes de cargar. 
                                            </div>
                                            <div class="alerta_externa_comunicado alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                            <div style="display: flex; flex-direction:row; justify-content:flex-end; gap:2px;"> <!-- Alinea el contenido a la derecha -->
                                                <input style="width:40%" type="file" class="form-control select-doc" name="cargue_comunicados" id="cargue_comunicados" aria-describedby="Carguecomunicados" aria-label="Upload" accept=".pdf, .doc, .docx"/>
                                                <button class="btn btn-sm btn-info" id="cargarComunicado">Cargar</button>
                                            </div>
                                        </div>  
                                    </div>
                                </div>                     
                            </div>                                                                
                        </div>                        
                    </div>                     
                </div>
            </div>
            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a> 
        </div>
    </div>
   <!--Retonar al modulo PCL -->
   <form action="{{route('calificacionPCL')}}" id="formularioEnvio" method="POST">            
        @csrf
        <input type="hidden" name="newIdEvento" id="newIdEvento" value="{{$array_datos_calificacionPclTecnica[0]->ID_evento}}">
        <input type="hidden" name="newIdAsignacion" id="newIdAsignacion" value="{{$array_datos_calificacionPclTecnica[0]->Id_Asignacion}}">
        <input type="hidden" name="Id_Servicio" id="Id_Servicio" value="<?php if(!empty($array_datos_calificacionPclTecnica[0]->Id_Servicio)){ echo $array_datos_calificacionPclTecnica[0]->Id_Servicio;}?>">
        <button type="submit" id="botonEnvioVista" style="display:none !important;"></button>
    </form> 
    <!--Retonar al modulo Modulo Nuevo edicion -->
    <form action="{{route('gestionInicialEdicion')}}" id="formularioLlevarEdicionEvento" method="POST">
        @csrf
        <input type="hidden" name="bandera_buscador_clt" id="bandera_buscador_clt" value="desdeclt">
        <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_calificacionPclTecnica[0]->ID_evento)){echo $array_datos_calificacionPclTecnica[0]->ID_evento;}?>">
        <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_calificacionPclTecnica[0]->Id_Asignacion)){echo $array_datos_calificacionPclTecnica[0]->Id_Asignacion;}?>">
        <input hidden="hidden" type="text" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_calificacionPclTecnica[0]->Id_proceso)){ echo $array_datos_calificacionPclTecnica[0]->Id_proceso;}?>">
        <input hidden="hidden" type="text" name="newIdservicio" id="newIdservicio" value="<?php if(!empty($array_datos_calificacionPclTecnica[0]->Id_Servicio)){ echo $array_datos_calificacionPclTecnica[0]->Id_Servicio;}?>">
        <button type="submit" id="botonVerEdicionEvento" style="display:none !important;"></button>
   </form>

    @if (count($hay_agudeza_visual) == 0)
        {{-- MODAL NUEVA DEFICIENCIA VISUAL --}}        
        @include('coordinador.campimetriaPCL')
    @else
        {{-- MODAL EDICIÓN DEFICIENCIA VISUAL --}}
        @include('coordinador.edicionCampimetriaPCL')
    @endif
    @if (count($array_agudeza_Auditiva) == 0)
        @include('coordinador.modalagudezaAuditiva')
    {{-- @else
        @include('coordinador.modalagudezaAuditivaEdicion'); --}}                 
    @endif

    <!-- Modal Alerta Suma Combinada y total deficiencia para el decreto 1999-->    
    <x-adminlte-modal id="AlertaScTd" class="modalscroll" title="Suma Combinada y Total Deficiencia" theme="info" icon="fas fa-info-circle" size='md' disable-animations>
        <div class="row">
            <div class="col-12">        
                <div class="card-body">
                    La Suma Combinada y Total Deficiencia  los campos son requeridos. Por favor, llenarlos antes de guardar.
                </div>                                   
            </div>
        </div>  
        <div class="row">
            <div class="col-12">
                <button type="button" id="btn_cerrar_modal_agudeza" class="btn btn-danger" style="float:right !important;" data-dismiss="modal">Cerrar</button>
            </div>
        </div> 
        <x-slot name="footerSlot">
        </x-slot>     
    </x-adminlte-modal>
    @include('//.coordinador.modalReemplazarArchivos')
    @include('//.coordinador.modalCorrespondencia')
 @stop
 

@section('js')
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
                '<input type="date" class="form-control" id="fecha_examen_fila_'+contador_examen+'" name="fecha_examen" max="{{date("Y-m-d")}}" min="1900-01-01" required/><span class="d-none" id="fecha_examen_fila_'+contador_examen+'_alerta" style="color: red; font-style: italic;"></span>',
                '<input type="text" class="form-control" id="nombre_examen_fila_'+contador_examen+'" name="nombre_examen"/>',
                '<textarea id="descripcion_resultado_fila_'+contador_examen+'" class="form-control" name="descripcion_resultado" cols="90" rows="4"></textarea>',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_examen_fila" class="text-info" data-fila="fila_'+contador_examen+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_examen
            ];

            var agregar_examen_fila = listado_examenes_interconsultas.row.add(nueva_fila_examen).draw().node();
            $(agregar_examen_fila).addClass('fila_'+contador_examen);
            $(agregar_examen_fila).attr("id", 'fila_'+contador_examen);

            //Agrega las validaciones generales a las fechas
            agregarValidacionFecha("#fecha_examen_fila_"+contador_examen, 'guardar_datos_examenes');

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
            "scrollX": true,
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
                '<select id="lista_Cie10_fila_'+contador_cie10+'" class="form-comtrol custom-select lista_Cie10_fila_'+contador_cie10+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" style="width: 239.422px;" id="nombre_cie10_fila_'+contador_cie10+'" name="nombre_cie10"/>',
                '<select id="lista_lateralidadCie10_fila_'+contador_cie10+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie10+'" name="lista_lateralidadCie10"><option></option></select>',
                '<select id="lista_origenCie10_fila_'+contador_cie10+'" class="custom-select lista_origenCie10_fila_'+contador_cie10+'" name="lista_origenCie10"><option></option></select>',
                '<input type="checkbox" id="checkbox_dx_principal_cie10_'+contador_cie10+'" class="checkbox_dx_principal_cie10_'+contador_cie10+'" data-id_fila_checkbox_dx_principal_cie10_="'+contador_cie10+'" style="transform: scale(1.2);">',
                '<textarea id="descripcion_cie10_fila_'+contador_cie10+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_cie10_fila" class="text-info" data-fila="fila_'+contador_cie10+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_cie10
            ];

            var agregar_cie10_fila = listado_diagnostico_cie10.row.add(nueva_fila_cie10).draw().node();
            $(agregar_cie10_fila).addClass('fila_'+contador_cie10);
            $(agregar_cie10_fila).attr("id", 'fila_'+contador_cie10);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
            funciones_elementos_fila_diagnosticos(contador_cie10);
            
            // Añadir el límite de caracteres al textarea recién creado descripcion_cie10_fila_
            $('#descripcion_cie10_fila_' + contador_cie10).on('keyup', function() {
                var maxLength = 100; // Máximo número de caracteres permitidos
                if ($(this).val().length > maxLength) {
                    $(this).val($(this).val().substring(0, maxLength)); // Trunca el texto a maxLength caracteres
                }
            });
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
<script type="text/javascript" src="/js/funciones_helpers.js?v=1.0.0"></script>
<script type="text/javascript" src="/js/calificacionpcl_tecnica.js"></script>
{{-- JS: Deficiencias por Alteraciones de los Sistemas Generales cálculadas por factores --}}
<script type="text/javascript" src="/js/datatable_deficiencias_alteraciones_sistemas.js"></script>
<script type="text/javascript" src="/js/agudeza_auditiva.js"></script>
{{-- JS: DATATABLE AGUDEZA VISUAL --}}
<script type="text/javascript" src="/js/datatable_agudeza_visual.js"></script>
<script src="/plugins/summernote/summernote.min.js"></script>
{{-- Validación general para todos los campos de tipo fecha --}}
<script>
    let today = new Date().toISOString().split("T")[0];

    // Seleccionar todos los inputs de tipo date
    const dateInputs = document.querySelectorAll('input[type="date"]');

    // Agregar evento de escucha a cada input de tipo date que haya
    dateInputs.forEach(input => {
        //Usamos el evento change para detectar los cambios de cada uno de los inputs de tipo fecha
        input.addEventListener('change', function() {
            //Validamos que la fecha sea mayor a la fecha de 1900-01-01
            if(this.value < '1900-01-01'){
                $(`#${this.id}_alerta`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                $('#GuardrDictamenPericial').addClass('d-none');
                return;
            }
            //Validamos que la fecha no sea mayor a la fecha actual
            if(this.value > today){
                $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                $('#GuardrDictamenPericial').addClass('d-none');
                return;
            }
            $('#GuardrDictamenPericial').removeClass('d-none');
            return $(`#${this.id}_alerta`).text('').addClass("d-none");
        });
    });
</script>
@stop