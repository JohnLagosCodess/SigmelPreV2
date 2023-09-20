@extends('adminlte::page')
@section('title', 'Origen ATEL')
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
            <a href="{{route("bandejaOrigen")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <button id="Hacciones" class="btn btn-info"  onclick="historialDeAcciones()"><i class="fas fa-list"></i>Historial Acciones</button>                
            <p>
                <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
            </p>
        </div>
    </div>
</div>
<div class="card-info" style="border: 1px solid black;">
    <div class="card-header text-center">
        <h4>Origen ATEL - Evento: {{$array_datos_calificacionOrigen[0]->ID_evento}}</h4>
        <input type="hidden" id="action_actualizar_comunicado" value="{{ route('descargarPdf') }}">
    </div>
    <form id="form_calificacionOrigen" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-12" id="filaprincipal">
                    <div class="row col-12" id="aumentarColAfiliado"> 
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información del afiliado</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="cliente">Cliente</label>
                                            <input type="text" class="form-control" name="cliente" id="cliente" value="{{$array_datos_calificacionOrigen[0]->Nombre_Cliente}}" disabled>
                                            <input hidden="hidden" type="text" class="form-control" name="newId_evento" id="newId_evento" value="{{$array_datos_calificacionOrigen[0]->ID_evento}}">
                                            <input hidden="hidden" type="text" class="form-control" name="newId_asignacion" id="newId_asignacion" value="{{$array_datos_calificacionOrigen[0]->Id_Asignacion}}">
                                            <input hidden="hidden" type="text" class="form-control" name="Id_proceso" id="Id_proceso" value="{{$array_datos_calificacionOrigen[0]->Id_proceso}}">
                                            
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre_afiliado">Nombre de afiliado</label>
                                            <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_calificacionOrigen[0]->Nombre_afiliado}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="identificacion">N° Identificación</label>
                                            <input type="text" class="form-control" name="identificacion" id="identificacion" value="{{$array_datos_calificacionOrigen[0]->Nro_identificacion}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="empresa">Empresa actual</label>
                                            <input type="text" class="form-control" name="empresa" id="empresa" value="{{$array_datos_calificacionOrigen[0]->Empresa}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="tipo_evento">Tipo de evento</label>
                                            <input type="text" class="form-control" name="tipo_evento" id="tipo_evento" value="{{$array_datos_calificacionOrigen[0]->Nombre_evento}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="id_evento">ID evento</label>
                                            <input type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_calificacionOrigen[0]->ID_evento}}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row col-12" id="aumentarColActividad">
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información de la actividad</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="proceso_actual">Proceso actual</label>
                                            <input type="text" class="form-control" name="proceso_actual" id="proceso_actual" value="{{$array_datos_calificacionOrigen[0]->Nombre_proceso_actual}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="servicio">Servicio</label><br>
                                            <a onclick="document.getElementById('botonFormulario2').click();" style="cursor:pointer;" id="servicio_Origen"><i class="fa fa-puzzle-piece text-info"></i> <strong class="text-dark">{{$array_datos_calificacionOrigen[0]->Nombre_servicio}}</strong></a>
                                            <input type="hidden" class="form-control" name="servicio" id="servicio" value="{{$array_datos_calificacionOrigen[0]->Nombre_servicio}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="proceso_envia">Proceso que envía</label>
                                            <input type="text" class="form-control" name="proceso_envia" id="proceso_envia" value="{{$array_datos_calificacionOrigen[0]->Nombre_proceso_anterior}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_radicacion">Fecha de radicación</label>
                                            <input type="date" class="form-control" name="fecha_radicacion" id="fecha_radicacion" value="{{$array_datos_calificacionOrigen[0]->F_radicacion}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_asignacion">Fecha asignación al proceso</label>
                                            <input type="date" class="form-control" name="fecha_asignacion" id="fecha_asignacion" value="{{$array_datos_calificacionOrigen[0]->F_registro_asignacion}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="estado">Estado</label>
                                            <input type="text" class="form-control" name="estado" id="estado" value="{{$array_datos_calificacionOrigen[0]->Nombre_estado}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="dias_trascurrido">Dias transcurridos desde el evento</label>
                                            <input type="text" class="form-control" name="dias_trascurrido" id="dias_trascurrido" value="{{$array_datos_calificacionOrigen[0]->Dias_transcurridos_desde_el_evento}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="asignado_por">Asignado por</label>
                                            <input type="text" class="form-control" name="asignado_por" id="asignado_por" value="{{$array_datos_calificacionOrigen[0]->Asignado_por}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_asignacion_calificacion">Fecha de asignación para DTO</label>
                                            <input type="text" class="form-control" name="fecha_asignacion_dto" id="fecha_asignacion_dto" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="profesional_calificador">Profesional Calificador</label>
                                            <input type="text" class="form-control" name="profesional_calificador" id="profesional_calificador" value="{{$array_datos_calificacionOrigen[0]->Asignado_por}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="tipo_profesional_calificador">Tipo Profesional calificador</label>
                                            <input type="text" class="form-control" name="tipo_profesional_calificador" id="tipo_profesional_calificador" value="{{$array_datos_calificacionOrigen[0]->Asignado_por}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_calificacion">Fecha de calificación</label>
                                            <input type="text" class="form-control" name="fecha_calificacion" id="fecha_calificacion" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="profesional_comite">Profesional Comité</label>
                                            <input type="text" class="form-control" name="profesional_comite" id="profesional_comite" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_visado_comite">Fecha de visado comité</label>
                                            <input type="text" class="form-control" name="fecha_visado_comite" id="fecha_visado_comite" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="profesional_comite">Fecha de ajuste calificación</label>
                                            <input type="text" class="form-control" name="fecha_ajuste_califi" id="fecha_ajuste_califi" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="modalidad_calificacion">Documentos adjuntos</label><br>
                                            <a href="javascript:void(0);" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_devolucion">Fecha devolución comité</label>
                                            <input type="text" class="form-control" name="fecha_devolucion" id="fecha_devolucion" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="tiempo_gestion">Tiempo de gestión</label>
                                            <input type="text" class="form-control" name="tiempo_gestion" id="tiempo_gestion" value="{{$array_datos_calificacionOrigen[0]->Tiempo_de_gestion}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="row">
                                    <div class="col-12">
                                        <div class="form-group">  
                                            <?php //if(!$arraycampa_documento_solicitado->isEmpty()):?>                                                
                                                <a href="#" id="clicGuardado" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modalSolicitudDocSeguimiento"><i class="fas fa-book-open text-info"></i> <strong>Solicitud documentos - Seguimientos</strong> <i class="fas fa-bell text-info icono"></i></a>
                                            <?php //else:?>
                                                <a href="#" id="clicGuardado" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modalSolicitudDocSeguimiento"><i class="fas fa-book-open text-info"></i> <strong>Solicitud documentos - Seguimientos</strong></a>
                                            <?php //endif?>                                                
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        </div> 
                    </div>
                    <div class="row col-12" id="aumentarColAccionRealizar">                                    
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Acción a realizar</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_accion">Fecha de acción <span style="color: red;">(*)</span></label>
                                            <input type="date" class="form-control" name="fecha_accion" id="fecha_accion" value="{{now()->format('Y-m-d')}}" disabled>
                                            <input hidden="hidden" type="date" class="form-control" name="f_accion" id="f_accion" value="{{now()->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="accion">Acción <span style="color: red;">(*)</span></label>
                                            <select class="custom-select" name="accion" id="accion" style="color: red;">
                                                <option value="NO ESTA DEFINIDO">NO ESTA DEFINIDO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_alerta">Fecha de alerta</label>
                                            <input type="date" class="form-control" name="fecha_alerta" id="fecha_alerta" min="{{now()->format('Y-m-d')}}" value="{{$array_datos_calificacionOrigen[0]->F_alerta}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="enviar">Enviar a <span style="color: red;">(*)</span></label>
                                            <select class="custom-select" name="enviar" id="enviar" style="color: red;">
                                                <option value="NO ESTA DEFINIDO">NO ESTA DEFINIDO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="causal_devolucion_comite">Causal de devolución comité</label>
                                            <select class="custom-select" name="causal_devolucion_comite" id="causal_devolucion_comite" style="color: red;">
                                                <option value="NO ESTA DEFINIDO">NO ESTA DEFINIDO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="descripcion_accion">Descripción acción</label>
                                            <textarea class="form-control" name="descripcion_accion" id="descripcion_accion" cols="30" rows="5" style="resize: none;">{{$array_datos_calificacionOrigen[0]->Descripcion_accion}}</textarea>                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                                    
                    </div>
                </div>
                <!--Definir Historial Acciones-->
            </div>
        </div>
        <div class="card-footer">
            <div class="grupo_botones">
                <input type="reset" id="Borrar" class="btn btn-info" value="Restablecer">
                @if (empty($array_datos_calificacionOrigen[0]->Accion_realizar))
                    <input type="submit" id="Edicion" class="btn btn-info" value="Guardar">
                    <div class="col-12">
                        <div class="alerta_calificacion alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                    </div>
                    <input type="hidden" id="bandera_accion_guardar_actualizar" value="Guardar">
                @else 
                    <input type="submit" id="Edicion" class="btn btn-info" value="Actualizar">
                    <div class="col-12">
                        <div class="alerta_calificacion alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                    </div>
                    <input type="hidden" id="bandera_accion_guardar_actualizar" value="Actualizar">
                @endif                    
            </div>
        </div>
    </form>
</div>

{{-- Modal cargue documentos --}}
<?php $aperturaModal = 'Edicion'; ?>
@include('//.administrador.modalcarguedocumentos')
@stop
@section('js')
    <script type="text/javascript" src="/js/calificacionOrigen.js"></script>
    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
@stop