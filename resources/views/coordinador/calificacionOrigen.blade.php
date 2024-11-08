@extends('adminlte::page')
@section('title', 'Origen ATEL')

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
            <?php if (isset($_POST['badera_modulo_principal_origen']) &&  $_POST['badera_modulo_principal_origen'] == 'desdebus_mod_origen' ):?>
                <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>            
            <?php elseif ($dato_rol == 7):?>
                <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <?php elseif(isset($_POST['bd_notificacion']) && $_POST['bd_notificacion'] == true):?>
                <a href="{{route("bandejaNotifi")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>                                                
            <?php else: ?>
                <a href="{{route("bandejaOrigen")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <?php endif ?>
            <button id="Hacciones" class="btn btn-info"  onclick="historialDeAcciones()"><i class="fas fa-list"></i>Historial Acciones</button>                
            <button label="Open Modal" data-toggle="modal" data-target="#historial_servicios" class="btn btn-info"><i class="fas fa-project-diagram mt-1"></i>Historial de servicios</button> 
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
        <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
    </div>
    <form id="form_calificacionOrigen" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-12" id="filaprincipal">
                    <div class="row" id="aumentarColAfiliado">
                        <div class="col-12">
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Información del afiliado</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="cliente">Cliente</label>
                                                <input type="text" class="form-control" name="cliente" id="cliente" data-id={{$array_datos_calificacionOrigen[0]->Id_cliente}} value="{{$array_datos_calificacionOrigen[0]->Nombre_Cliente}}" disabled>
                                                <input type="hidden" class="form-control" name="newId_evento" id="newId_evento" value="{{$array_datos_calificacionOrigen[0]->ID_evento}}">
                                                <input type="hidden" class="form-control" name="newId_asignacion" id="newId_asignacion" value="{{$array_datos_calificacionOrigen[0]->Id_Asignacion}}">
                                                <input type="hidden" class="form-control" name="Id_proceso" id="Id_proceso" value="{{$array_datos_calificacionOrigen[0]->Id_proceso}}">
                                                <input type="hidden" class="form-control" id="Id_servicio" value="{{$array_datos_calificacionOrigen[0]->Id_Servicio}}">
                                                @if (!empty($array_datos_calificacionOrigen) && count($dato_validacion_no_aporta_docs) > 0)
                                                    <input hidden="hidden" type="text" class="form-control" data-id_tupla_no_aporta="{{$dato_validacion_no_aporta_docs[0]->Id_Documento_Solicitado}}" id="validacion_aporta_doc" value="{{$dato_validacion_no_aporta_docs[0]->Aporta_documento}}">
                                                @endif
                                                @if (!empty($listado_documentos_solicitados))
                                                    <!--Conteo de documetos de seguimiento ya registrados-->
                                                    <input type="hidden" class="form-control" id="conteo_listado_documentos_solicitados" value="{{count($listado_documentos_solicitados)}}">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nombre_afiliado">Nombre de afiliado</label>
                                                <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Nombre_afiliado)){echo $array_datos_calificacionOrigen[0]->Nombre_afiliado;}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="identificacion">N° Identificación</label>
                                                <input type="text" class="form-control" name="identificacion" id="identificacion" data-tipo="{{$array_datos_calificacionOrigen[0]->Nombre_tipo_documento}}" value="{{$array_datos_calificacionOrigen[0]->Nro_identificacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="empresa">Empresa actual</label>
                                                <input type="text" class="form-control" name="empresa" id="empresa" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Empresa)){echo $array_datos_calificacionOrigen[0]->Empresa;}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_evento">Tipo de evento</label>
                                                <input type="text" class="form-control" name="tipo_evento" id="tipo_evento" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Nombre_evento)){echo $array_datos_calificacionOrigen[0]->Nombre_evento;}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="id_evento">ID evento</label>
                                                <br>
                                                <input hidden="hidden" type="text" class="form-control" name="id_evento" id="id_evento" value="<?php if(!empty($array_datos_calificacionOrigen[0]->ID_evento)){echo $array_datos_calificacionOrigen[0]->ID_evento;}?>" disabled>
                                                {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                                <a onclick="document.getElementById('botonVerEdicionEvento').click();" id="enlace_ed_evento" style="cursor:pointer; font-weight: bold;" class="btn text-info" type="button"><?php if(!empty($array_datos_calificacionOrigen[0]->ID_evento)){echo $array_datos_calificacionOrigen[0]->ID_evento;}?></a>                                            
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_evento">Tipo de afiliado</label>
                                                <input type="text" class="form-control" name="tipo_afiliado" id="tipo_afiliado" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Tipo_afiliado)){echo $array_datos_calificacionOrigen[0]->Tipo_afiliado;}?>" disabled>
                                            </div>
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
                                            <input type="text" class="form-control" name="proceso_actual" id="proceso_actual" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Nombre_proceso_actual)){echo $array_datos_calificacionOrigen[0]->Nombre_proceso_actual;}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                            <div class="form-group">
                                                <label for="servicio">Servicio</label><br>
                                                <a onclick="document.getElementById('botonFormulario2').click();" id="llevar_servicio" style="cursor:pointer;" id="servicio_Origen"><i class="fa fa-puzzle-piece text-info"></i> <strong class="text-dark">{{$array_datos_calificacionOrigen[0]->Nombre_servicio}}</strong></a>
                                                <input type="hidden" class="form-control" name="servicio" id="servicio" value="{{$array_datos_calificacionOrigen[0]->Nombre_servicio}}">
                                            </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="proceso_envia">Proceso que envía</label>
                                            <input type="text" class="form-control" name="proceso_envia" id="proceso_envia" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Nombre_proceso_anterior)){echo $array_datos_calificacionOrigen[0]->Nombre_proceso_anterior;}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_radicacion">Fecha de radicación</label>
                                            <input type="date" class="form-control" name="fecha_radicacion" id="fecha_radicacion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->F_radicacion)){echo $array_datos_calificacionOrigen[0]->F_radicacion;}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_asignacion">Fecha asignación al proceso</label>
                                            <input type="date" class="form-control" name="fecha_asignacion" id="fecha_asignacion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->F_registro_asignacion)){echo $array_datos_calificacionOrigen[0]->F_registro_asignacion;}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="estado">Estado</label>
                                            <input type="text" class="form-control" name="estado" id="estado" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Nombre_estado)){echo $array_datos_calificacionOrigen[0]->Nombre_estado;}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="dias_trascurrido">Dias transcurridos desde el evento</label>
                                            <input type="text" class="form-control" name="dias_trascurrido" id="dias_trascurrido" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Dias_transcurridos_desde_el_evento)){echo $array_datos_calificacionOrigen[0]->Dias_transcurridos_desde_el_evento;}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="asignado_por">Asignado por</label>
                                            <input type="text" class="form-control" name="asignado_por" id="asignado_por" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Asignado_por)){echo $array_datos_calificacionOrigen[0]->Asignado_por;}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_asignacion_calificacion">Fecha de asignación para DTO</label>
                                            <input type="text" class="form-control" name="fecha_asignacion_dto" id="fecha_asignacion_dto"  value="<?php if(!empty($array_datos_calificacionOrigen[0]->Fecha_asignacion_dto) && $array_datos_calificacionOrigen[0]->Fecha_asignacion_dto != '0000-00-00 00:00:00'){echo $array_datos_calificacionOrigen[0]->Fecha_asignacion_dto;}else{ echo 'Sin Fecha de Asignación para DTO';}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="profesional_calificador">Profesional Calificador</label>
                                            <input type="text" class="form-control" name="profesional_calificador" id="profesional_calificador" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Nombre_calificador)){echo $array_datos_calificacionOrigen[0]->Nombre_calificador;}else{ echo 'Sin Calificación';}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="tipo_profesional_calificador">Tipo Profesional calificador</label>
                                            <input type="text" class="form-control" name="tipo_profesional_calificador" id="tipo_profesional_calificador" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Tipo_Profesional_calificador)){echo $array_datos_calificacionOrigen[0]->Tipo_Profesional_calificador;}else{ echo 'Sin Calificación';}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_calificacion">Fecha de calificación</label>
                                            <input type="text" class="form-control" name="fecha_calificacion" id="fecha_calificacion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->F_calificacion_servicio)){echo $array_datos_calificacionOrigen[0]->F_calificacion_servicio;}else{ echo 'Sin Calificación';}?>" disabled> 
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="profesional_comite">Profesional Comité</label>
                                            <input type="text" class="form-control" name="profesional_comite" id="profesional_comite" value="<?php if(!empty($cali_profe_comite[0]->Profesional_comite)){echo $cali_profe_comite[0]->Profesional_comite;}else{echo 'Sin Visado';}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_visado_comite">Fecha de visado comité</label>
                                            {{-- <input type="text" class="form-control" name="fecha_visado_comite" id="fecha_visado_comite" value="{{$cali_profe_comite[0]->F_visado_comite}}" disabled> --}}
                                            <input type="text" class="form-control" name="fecha_visado_comite" id="fecha_visado_comite" value="<?php if(!empty($cali_profe_comite[0]->F_visado_comite)){echo $cali_profe_comite[0]->F_visado_comite;}else{echo 'Sin Visado';}?>" disabled>
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
                                            <a href="javascript:void(0);" id="cargue_docs" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_devolucion">Fecha devolución comité</label>
                                            <input type="text" class="form-control" name="fecha_devolucion" id="fecha_devolucion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Fecha_devolucion_comite) && $array_datos_calificacionOrigen[0]->Fecha_devolucion_comite != '0000-00-00'){echo $array_datos_calificacionOrigen[0]->Fecha_devolucion_comite;}else{ echo 'Sin Fecha Devolución';}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="tiempo_gestion">Tiempo de gestión</label>
                                            <input type="text" class="form-control" name="tiempo_gestion" id="tiempo_gestion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Tiempo_de_gestion)){echo $array_datos_calificacionOrigen[0]->Tiempo_de_gestion;}?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Nueva Fecha de radicación</label>
                                            <input type="date" class="form-control" name="nueva_fecha_radicacion" id="nueva_fecha_radicacion" max="{{now()->format('Y-m-d')}}" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Nueva_F_radicacion)){echo $array_datos_calificacionOrigen[0]->Nueva_F_radicacion;}?>">
                                            <span class="d-none" id="alertaNuevaFechaDeRadicacion" style="color: red; font-style: italic;">La fecha ingresada debe ser superior a la fecha de radicación inicial</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fuente_informacion">Fuente de información</label>
                                            <select class="fuente_informacion custom-select" name="fuente_informacion" id="fuente_informacion">
                                                @if (!empty($array_datos_calificacionOrigen[0]->Fuente_informacion))
                                                    <option value="{{$array_datos_calificacionOrigen[0]->Fuente_informacion}}" selected>{{$array_datos_calificacionOrigen[0]->Nombre_Fuente_informacion}}</option>
                                                @else
                                                    <option value="">Seleccione una opción</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">  
                                            <?php if($arraycampa_documento_solicitado<> 0):?>                                                
                                                <a href="#" id="clicGuardado" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modalSolicitudDocSeguimiento"><i class="fas fa-book-open text-info"></i> <strong>Solicitud documentos - Seguimientos</strong> <i class="fas fa-bell text-info icono"></i></a>
                                            <?php else:?>
                                                <a href="#" id="clicGuardado" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modalSolicitudDocSeguimiento"><i class="fas fa-book-open text-info"></i> <strong>Solicitud documentos - Seguimientos</strong></a>
                                            <?php endif?>                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row" id="aumentarColAccionRealizar">
                        <div class="col-12">
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Acción a realizar</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_accion">Fecha de acción <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="fecha_accion" id="fecha_accion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->F_accion_realizar)){echo $array_datos_calificacionOrigen[0]->F_accion_realizar; }else{echo now()->format('Y-m-d H:i:s');} ?>" disabled>
                                                {{-- @if (!empty($array_datos_calificacionOrigen[0]->F_accion_realizar))
                                                    <input type="date" class="form-control" name="fecha_accion" id="fecha_accion" value="{{$array_datos_calificacionOrigen[0]->F_accion_realizar}}" disabled>
                                                    <input hidden="hidden" type="date" class="form-control" name="f_accion" id="f_accion" value="{{$array_datos_calificacionOrigen[0]->F_accion_realizar}}">
                                                @else
                                                    <input type="date" class="form-control" name="fecha_accion" id="fecha_accion" value="{{now()->format('Y-m-d')}}" disabled>
                                                    <input hidden="hidden" type="date" class="form-control" name="f_accion" id="f_accion" value="{{now()->format('Y-m-d')}}">
                                                @endif --}}
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="accion">Acción <span style="color: red;">(*)</span></label>
                                                <input type="hidden" id="bd_id_accion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Id_accion)){echo $array_datos_calificacionOrigen[0]->Id_accion;}?>">
                                                <select class="custom-select accion" name="accion" id="accion" required>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_alerta">Fecha de alerta</label>
                                                <input type="datetime-local" class="form-control" name="fecha_alerta" id="fecha_alerta" value="<?php if(!empty($array_datos_calificacionOrigen[0]->F_alerta)){echo $array_datos_calificacionOrigen[0]->F_alerta;}?>">
                                                <span class="d-none" id="alerta_fecha_alerta" style="color: red; font-style: italic;">La Fecha de alerta no puede ser inferior a la fecha actual</span>
                                                {{-- <input type="date" class="form-control" name="fecha_alerta" id="fecha_alerta" min="{{now()->format('Y-m-d')}}" value="<?php if(!empty($array_datos_calificacionOrigen[0]->F_alerta)){echo $array_datos_calificacionOrigen[0]->F_alerta;}?>"> --}}
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="enviar">Enviar a</label>
                                                <select class="custom-select" name="enviar" id="enviar" disabled>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="estado_facturacion">Estado de Facturación</label>
                                                <input type="text" class="form-control" name="estado_facturacion" id="estado_facturacion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Estado_Facturacion)){echo $array_datos_calificacionOrigen[0]->Estado_Facturacion;}?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="profesional" class="col-form label">Profesional</label>
                                                <select class="profesional custom-select" name="profesional" id="profesional">
                                                    @if (!empty($array_datos_calificacionOrigen[0]->Id_profesional))
                                                        <option value="{{$array_datos_calificacionOrigen[0]->Id_profesional}}" selected>{{$array_datos_calificacionOrigen[0]->Nombre_profesional}}</option>                                                        
                                                    @else
                                                        <option value="">Seleccione una opción</option>                                                        
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4 d-none" id="div_causal_devolucion_comite">
                                            <div class="form-group">
                                                <label for="causal_devolucion_comite">Causal de devolución comité</label>
                                                <select class="causal_devolucion_comite custom-select" name="causal_devolucion_comite" id="causal_devolucion_comite">
                                                    @if (!empty($array_datos_calificacionOrigen[0]->Nombre_Causal_devolucion_comite))
                                                            <option value="{{$array_datos_calificacionOrigen[0]->Causal_devolucion_comite}}" selected>{{$array_datos_calificacionOrigen[0]->Nombre_Causal_devolucion_comite}}</option>
                                                        @else
                                                            <option value="">Seleccione una opción</option>
                                                        @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">Fecha de cierre</label>
                                                <input type="date" class="form-control" name="fecha_cierre" id="fecha_cierre" max="{{now()->format('Y-m-d')}}" value="<?php if(!empty($array_datos_calificacionOrigen[0]->F_cierre)){echo $array_datos_calificacionOrigen[0]->F_cierre;}?>">
                                                <span class="d-none" id="fecha_cierre_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="cargue_documentos">Cargue Documento Historial:</label>                                                
                                                <input type="file" class="form-control select-doc" name="cargue_documentos" id="cargue_documentos" aria-describedby="Carguedocumentos" aria-label="Upload"/>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="descripcion_accion">Descripción acción</label>
                                                {{-- <textarea class="form-control" name="descripcion_accion" id="descripcion_accion" cols="30" rows="5" style="resize: none;">{{$array_datos_calificacionOrigen[0]->Descripcion_accion}}</textarea>  --}}
                                                
                                                <textarea class="form-control" name="descripcion_accion" id="descripcion_accion" cols="30" rows="5" style="resize: none;"></textarea>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                    
                        </div>                            
                    </div>
                </div>
                <!-- Historial de Acciones -->
                <div class="col-6">                                
                    <div id="historialAcciones" class="card-info d-none">
                        <div class="card-header text-center" style="border: 1.5px solid black;">
                            <h5>Historial de acciones</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table table-responsive">
                                        <table id="listado_historial_acciones_evento" class="table table-striped table-bordered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Fecha de acción</th>
                                                    <th>Usuario de acción</th>
                                                    <th>Acción realizada</th>
                                                    <th>Descripción</th>
                                                    <th>Descarga</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="borrar_tabla_historial_acciones"></tbody>
                                        </table>
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
       <div class="card-footer">
            <div class="alert alert-danger no_ejecutar_parametrica_modulo_principal d-none" role="alert">
                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede actualizar la información debido a que el proceso, servicio y/o acción no tienen una parametrización
                asociada. Debe configurar una.
            </div>
            <div class="grupo_botones">
                {{-- <input type="reset" id="Borrar" class="btn btn-info" value="Restablecer"> --}}
                @if (empty($array_datos_calificacionOrigen[0]->Accion_realizar))
                    <input type="button" id="Edicion"  label="Open Modal" data-toggle="modal" data-target="#confirmar_accion" class="btn btn-info" value="Guardar">
                    <div class="col-12">
                        <div class="alerta_calificacion alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                    </div>
                    <input type="hidden" id="bandera_accion_guardar_actualizar" value="Guardar">
                @else 
                    <input type="button" id="Edicion" label="Open Modal" data-toggle="modal" data-target="#confirmar_accion" class="btn btn-info" value="Actualizar">
                    <div class="col-12">
                        <div class="alerta_calificacion alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                    </div>
                    <input type="hidden" id="bandera_accion_guardar_actualizar" value="Actualizar">
                @endif                    
            </div>
        </div>
    </form>
    <form action="{{route($SubModulo)}}" id="formulario2" method="POST">            
        @csrf
        <input type="hidden" name="Id_evento_calitec" id="Id_evento_calitec" value="{{$array_datos_calificacionOrigen[0]->ID_evento}}">
        <input type="hidden" name="Id_asignacion_calitec" id="Id_asignacion_calitec" value="{{$array_datos_calificacionOrigen[0]->Id_Asignacion}}">
        <input type="hidden" name="Id_proceso_calitec" id="Id_proceso_calitec" value="{{$array_datos_calificacionOrigen[0]->Id_proceso}}">
        <input type="hidden" name="Id_Servicio" id="Id_Servicio" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Id_Servicio)){ echo $array_datos_calificacionOrigen[0]->Id_Servicio;}?>">
        <button type="submit" id="botonFormulario2" style="display: none; !important"></button>
    </form>
    <!--Retonar al modulo Modulo Nuevo edicion -->
    <form action="{{route('gestionInicialEdicion')}}" id="formularioLlevarEdicionEvento" method="POST">
        @csrf
        <input type="hidden" name="bandera_buscador_calori" id="bandera_buscador_calori" value="desdecalori">
        <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_calificacionOrigen[0]->ID_evento)){echo $array_datos_calificacionOrigen[0]->ID_evento;}?>">
        <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Id_Asignacion)){echo $array_datos_calificacionOrigen[0]->Id_Asignacion;}?>">
        <input hidden="hidden" type="text" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Id_proceso)){ echo $array_datos_calificacionOrigen[0]->Id_proceso;}?>">
        <input hidden="hidden" type="text" name="newIdservicio" id="newIdservicio" value="<?php if(!empty($array_datos_calificacionOrigen[0]->Id_Servicio)){ echo $array_datos_calificacionOrigen[0]->Id_Servicio;}?>">
        <button type="submit" id="botonVerEdicionEvento" style="display:none !important;"></button>
   </form>
</div>
{{-- Modal solicitud documentos - seguimientos --}}
<div class="row">
    <div class="contenedor_sol_Docuementos_seguimiento" style="float: left;">
        <x-adminlte-modal id="modalSolicitudDocSeguimiento" class="modalscroll" title="Solicitud Documentos - Seguimientos" theme="info" icon="fas fa-book-open" size='xl' disable-animations>
            <div class="row">
                <div class="col-12">
                    <div class="card-info" style="border: 1.5px solid black; border-radius: 2px;">
                        <div class="card-header text-center">
                            <h5>Listado de documentos seguimiento</h5>
                            <input hidden="hidden" type="text" class="form-control" name="Id_DocSugerido" id="Id_DocSugerido">
                            <input hidden="hidden" type="text" class="form-control" name="Nom_DocSugerido" id="Nom_DocSugerido">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tipo_evento_doc">Tipo de evento<span style="color: red;">(*)</span></label>
                                        <select class="tipo_evento_doc custom-select " name="tipo_evento_doc" id="tipo_evento_doc" required>
                                            @if (!empty($Fnuevo[0]->Tipo_evento))
                                                <option value="{{$Fnuevo[0]->Tipo_evento}}" selected>{{$Fnuevo[0]->Nombre_evento}}</option>
                                            @else
                                                <option value="">Seleccione una opción</option>
                                            @endif
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-6" id="div_GrupoDocumental">
                                    <div class="form-group">
                                        <label for="grupo_documental">Grupo documental <span style="color: red;">(*)</span></label>
                                        <select class="grupo_documental custom-select " name="grupo_documental" id="grupo_documental" <?php if(!empty($dato_articulo_12[0]->Articulo_12) && $dato_articulo_12[0]->Articulo_12=='No_mas_seguimiento'){ ?> disabled <?php } ?> required>
                                            @if (!empty($dato_ultimo_grupo_doc[0]->Grupo_documental))
                                                <option value="{{$dato_ultimo_grupo_doc[0]->Grupo_documental}}" selected>{{$dato_ultimo_grupo_doc[0]->Tipo_documento}}</option>
                                            @else
                                                <option value="">Seleccione una opción</option>
                                            @endif
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-12" id="div_DocumentosSugeridos">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="listado_docs_sugeridos" class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th style="text-align:center;">Documentos Sugeridos</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="datos_visuales">
                                                    @if (!empty($dato_doc_sugeridos))
                                                        @foreach ($dato_doc_sugeridos as $sugeridos)
                                                            <tr>
                                                            <td><a href="javascript:void(0);"  id="btn_insertar_documen_visual_{{$sugeridos->Id_documental}}" data-id_fila_agregar_doc="{{$sugeridos->Id_documental}}" data-nom_fila_agregar_doc="{{$sugeridos->Documento}}">{{$sugeridos->Documento}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                            que diligencie en su totalidad los campos.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="No_aporta_documentos">Articulo 12</label> &nbsp;
                                        <input class="scales" type="checkbox" name="No_aporta_documentos" id="No_aporta_documentos" value="No_mas_seguimiento" style="margin-left: revert;" <?php if(!empty($dato_articulo_12[0]->Articulo_12) && $dato_articulo_12[0]->Articulo_12=='No_mas_seguimiento'){ ?> checked <?php } ?>>
                                    </div> 
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="alert d-none" id="resultado_insercion" role="alert">
                                        </div>
                                        <div class="table-responsive">
                                            <table id="listado_docs_seguimiento" class="table table-striped table-bordered" width="100%">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Fecha solicitud documento</th>
                                                        <th style="width:164.719px !important;">Documento</th>
                                                        <th>Solicitada a</th>
                                                        <th>Fecha recepción de documentos</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_fila" <?php if(!empty($dato_articulo_12[0]->Articulo_12) && $dato_articulo_12[0]->Articulo_12=='No_mas_seguimiento'){ ?> style="display:none" <?php } ?>><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($listado_documentos_solicitados))
                                                        @foreach ($listado_documentos_solicitados as $prueba)
                                                            <tr class="fila_visual_{{$prueba->Id_Documento_Solicitado}}" id="datos_visuales">
                                                                <td>{{$prueba->F_solicitud_documento}}</td>
                                                                <td>{{$prueba->Nombre_documento}}</td>
                                                                <td>{{$prueba->Nombre_solicitante}}</td>
                                                                <td>{{$prueba->F_recepcion_documento}}</td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_edicion_documento_solicitud_{{$prueba->Id_Documento_Solicitado}}" data-id_fila_editar="{{$prueba->Id_Documento_Solicitado}}" data-clase_fila="fila_visual_{{$prueba->Id_Documento_Solicitado}}" class="text-info"><i class="fas fa-pen" style="font-size:24px;"></i></a></div>
                                                                    {{-- <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila_visual_{{$prueba->Id_Documento_Solicitado}}" data-id_fila_quitar="{{$prueba->Id_Documento_Solicitado}}" data-clase_fila="fila_visual_{{$prueba->Id_Documento_Solicitado}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div> --}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div><br>
                                        <x-adminlte-button class="mr-auto" id="guardar_datos_tabla" theme="info" label="Guardar"/>
                                        <x-adminlte-button class="mr-auto d-none" id="actualizar_datos_tabla" theme="info" label="Actualizar"/>
                                        <br><br>
                                    </div>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="form-group">
                                        <a href="javascript:void(0);" id="cargue_docs_modal_listado_docs" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                    </div>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="form-group">
                                        <a href="#" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalGenerarComunicado"><i class="fas fa-file-pdf text-info"></i> <strong>Generar Comunicado</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- Ver Comunicados -->
                        <div class="card-header text-center">
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
                            <div class="table-responsive">
                                <table id="listado_agregar_comunicados" class="table table-striped table-bordered" style="width: 100%;  white-space: nowrap;">
                                    <thead>
                                        <tr class="bg-info">
                                            <th>N° Radicado</th>
                                            <th>Elaboro</th>
                                            <th>Fecha Comunicado</th>
                                            <th>Documento</th>
                                            @if (!empty($enviar_notificaciones[0]->Notificacion) && $enviar_notificaciones[0]->Notificacion == 'Si')
                                                <th>Destinatarios</th>
                                            @endif
                                            <th>Estado general de la Notificación</th>
                                            <th>Nota</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
                        <!-- Ver Historial de seguimientos -->
                        <div class="card-header text-center">
                            <h5>Historial de seguimientos</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                            que diligencie en su totalidad los campos.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert d-none" id="resultado_insercion_seguimiento" role="alert">
                            </div>
                            <div class="table-responsive">
                                <!-- Validamos el primer documento solicitado -->
                                <?php 
                                    $date_actual = date("Y-m-d");
                                    if(!empty($listado_documentos_solicitados[0]->F_solicitud_documento)){
                                        $f_primer_documento=$listado_documentos_solicitados[0]->F_solicitud_documento;
                                    }else{
                                        $f_primer_documento=$date_actual;
                                    }
                                    // Calculamos primer seguimiento
                                    $fecha_estipula_segui= date('Y-m-d', strtotime($f_primer_documento . ' +7 days'));
                                    // Calculamos segundo seguimiento
                                    $fecha_estipula_segui2= date('Y-m-d', strtotime($f_primer_documento . ' +21 days'));
                                    // Calculamos tercer seguimiento
                                    $fecha_estipula_segui3= date('Y-m-d', strtotime($f_primer_documento . ' +27 days'));
                                ?>
                                <table id="listado_histori_seguimiento" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                        <tr class="bg-info">
                                            <th>Causal de seguimiento</th>
                                            <th>Fecha estipulada de seguimiento</th>
                                            <th>Fecha seguimiento</th>
                                            <th>Descripcion del seguimiento</th>
                                            <th>Realizado Por</th>
                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_segui_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (empty(($primer_seguimiento[0]->Causal_seguimiento)))
                                            <tr id="primer_seguimiento">
                                                <td><input type="text" id="primer_causal" name="primer_causal" value="Primer seguimiento" readonly></td>
                                                <td><input type="date" id="f_estipulada1" name="f_estipulada1" value="{{$fecha_estipula_segui}}" disabled></td>
                                                <td><input type="date" id="f_seguimiento1" name="f_seguimiento1" disabled></td>
                                                <td><textarea id="descrip_seguimiento1" class="form-control" name="descrip_seguimiento1" cols="90" rows="2"></textarea></td>
                                                <td><input type="text" class="form-control" name="realizado_por1" id="realizado_por1" value="" disabled></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                        @if (empty(($segundo_seguimiento[0]->Causal_seguimiento)))
                                            <tr id="segundo_seguimiento">
                                                <td><input type="text" id="segundo_causal" name="segundo_causal" value="Segundo seguimiento" readonly></td>
                                                <td><input type="date" id="f_estipulada2" name="f_estipulada2" value="{{$fecha_estipula_segui2}}" disabled></td>
                                                <td><input type="date" id="f_seguimiento2" name="f_seguimiento2"  disabled></td>
                                                <td><textarea id="descrip_seguimiento2" class="form-control" name="descrip_seguimiento2" cols="90" rows="2"></textarea></td>
                                                <td><input type="text" class="form-control" name="realizado_por2" id="realizado_por2" value="" disabled></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                        @if (empty(($tercer_seguimiento[0]->Causal_seguimiento)))
                                            <tr id="tercer_seguimiento">
                                                <td><input type="text" id="tercer_causal" name="tercer_causal" value="Tercer seguimiento" readonly></td>
                                                <td><input type="date" id="f_estipulada3" name="f_estipulada3" value="{{$fecha_estipula_segui3}}" disabled></td>
                                                <td><input type="date" id="f_seguimiento3" name="f_seguimiento3" disabled></td>
                                                <td><textarea id="descrip_seguimiento3" class="form-control" name="descrip_seguimiento3" cols="90" rows="2"></textarea></td>
                                                <td><input type="text" class="form-control" name="realizado_por3" id="realizado_por3" value="" disabled></td>
                                                <td></td>
                                            </tr>
                                        @endif
                                        @if (!empty($listado_seguimiento_solicitados))
                                        @foreach ($listado_seguimiento_solicitados as $segui)
                                            <tr class="fila_visual_segui_{{$segui->Id_Seguimiento}}" id="datos_visuales_segui">
                                                <td>{{$segui->Causal_seguimiento}}</td>
                                                <td>{{$segui->F_estipula_seguimiento}}</td>
                                                <td>{{$segui->F_seguimiento}}</td>
                                                <td>{{$segui->Descripcion_seguimiento}}</td>
                                                <td>{{$segui->Nombre_usuario}}</td>
                                                <td>
                                                    {{-- <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila_visual_segui_{{$segui->Id_Seguimiento}}" data-id_fila_quitar_segui="{{$segui->Id_Seguimiento}}" data-clase_fila="fila_visual_segui_{{$segui->Id_Seguimiento}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div><br>
                            <x-adminlte-button class="mr-auto" id="guardar_datos_seguimiento" theme="info" label="Guardar"/>
                            <br><br>
                        </div>
                    </div>
                </div>

            </div>
            <x-slot name="footerSlot">                    
                <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
        
    </div>
</div>
 {{-- Modal  Generar comunicado --}}
 <div class="row">
    <div class="contenedor_sol_Generar_comunicado" style="float: left;">
        <x-adminlte-modal id="modalGenerarComunicado" title="Generar comunicado" theme="info" icon="fas fa-file-pdf" size='xl' disable-animations>
            <div class="row">
                <div class="col-12">
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                    <div class="card-info" style="border: 1.5px solid black; border-radius: 2px;">
                        <div class="card-header text-center">
                            <h5>Generar comunicado</h5>
                        </div>
                        <form  id="form_generarComunicadoOrigen" method="POST">
                            @csrf 
                            <div class="card-body">
                                <div class="row"> 
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="cliente_comunicado">Cliente</label>
                                            <input class="form-control" type="text" name="cliente_comunicado" id="cliente_comunicado" value="{{$array_datos_calificacionOrigen[0]->Nombre_Cliente}}" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="cliente_comunicado2" id="cliente_comunicado2" value="{{$array_datos_calificacionOrigen[0]->Nombre_Cliente}}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="nombre_afiliado_comunicado">Nombre del afiliado</label>
                                            <input class="form-control" type="text" name="nombre_afiliado_comunicado" id="nombre_afiliado_comunicado" value="{{$array_datos_calificacionOrigen[0]->Nombre_afiliado}}" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="nombre_afiliado_comunicado2" id="nombre_afiliado_comunicado2" value="{{$array_datos_calificacionOrigen[0]->Nombre_afiliado}}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="tipo_documento_comunicado">Tipo de documento</label>
                                            <input class="form-control" type="text" name="tipo_documento_comunicado" id="tipo_documento_comunicado" value="{{$array_datos_calificacionOrigen[0]->Nombre_tipo_documento}}" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="tipo_documento_comunicado2" id="tipo_documento_comunicado2" value="{{$array_datos_calificacionOrigen[0]->Nombre_tipo_documento}}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="identificacion_comunicado">N° de identificación</label>
                                            <input class="form-control" type="text" name="identificacion_comunicado" id="identificacion_comunicado" value="{{$array_datos_calificacionOrigen[0]->Nro_identificacion}}" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="identificacion_comunicado2" id="identificacion_comunicado2" value="{{$array_datos_calificacionOrigen[0]->Nro_identificacion}}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="id_evento_comunicado">ID evento</label>
                                            <input class="form-control" type="text" name="id_evento_comunicado" id="id_evento_comunicado" value="{{$array_datos_calificacionOrigen[0]->ID_evento}}" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="id_evento_comunicado2" id="id_evento_comunicado2" value="{{$array_datos_calificacionOrigen[0]->ID_evento}}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="n_siniestro">N° de Siniestro</label>
                                            <input type="text" class="n_siniestro form-control" id="n_siniestro" name="n_siniestro" value="{{$N_siniestro_evento[0]->N_siniestro}}">        
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- <div class="col-3">
                                        <div class="form-group">
                                            <div class="form-check custom-control custom-radio">
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi" id="documentos_origen" value="Documento_Origen" required>
                                                <label class="form-check-label custom-control-label" for="documentos_origen"><strong>Solicitud Documentos Origen</strong></label>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="form-check custom-control custom-radio">
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi" id="otro_documento_origen" value="Otro_Documento" required>
                                                <label class="form-check-label custom-control-label" for="otro_documento_origen"><strong>Otro Documento</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <label for="destinatario_principal" style="margin-left: 7px;">Destinatario Principal: <span style="color: red;">(*)</span></label>                                        
                                    <div class="col-3">
                                        <label for="afiliado_comunicado"><strong>Afiliado</strong></label>
                                        <input class="scalesR" type="radio" name="afiliado_comunicado" id="afiliado_comunicado" value="Afiliado" style="margin-left: revert;" required>
                                    </div>
                                    <div class="col-3">
                                        <label for="empresa_comunicado"><strong>Empleador</strong></label>
                                        <input class="scalesR" type="radio" name="afiliado_comunicado" id="empresa_comunicado" value="Empleador" style="margin-left: revert;" required>
                                    </div>
                                    <div class="col-3">
                                        <label for="Otro"><strong>Otro</strong></label>
                                        <input class="scalesR" type="radio" name="afiliado_comunicado" id="Otro" value="Otro" style="margin-left: revert;" required>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre_destinatario"> Nombre destinatario <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="nombre_destinatario" id="nombre_destinatario"  required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nic_cc">NIT / CC <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="nic_cc" id="nic_cc" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="direccion_destinatario">Dirección destinatario <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="direccion_destinatario" id="direccion_destinatario" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="telefono_destinatario">Telefono destinatario <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="telefono_destinatario" id="telefono_destinatario" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="email_destinatario">E-mail destinatario <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="email" name="email_destinatario" id="email_destinatario" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="departamento_destinatario">Departamento <span style="color: red;">(*)</span></label><br>
                                            <select class="departamento_destinatario custom-select" name="departamento_destinatario" id="departamento_destinatario" style="width: 100%;" required>                                                        
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="ciudad_destinatario">Ciudad <span style="color: red;">(*)</span></label><br>
                                            <select class="ciudad_destinatario custom-select" name="ciudad_destinatario" id="ciudad_destinatario" style="width: 100%;" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="asunto">Asunto <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="asunto" id="asunto" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-warning d-none" id="insertar_mensaje_importante" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong>
                                            Para mostrar todo el cuerpo del comunicado (dentro del pdf) que usted escriba, debe incluir la etiqueta de las Pruebas Solicitadas dentro de la sección Cuerpo del Comunicado.
                                        </div>
                                        <div class="form-group">
                                            <label for="cuerpo_comunicado">Cuerpo del comunicado <span style="color: red;">(*)</span></label>
                                            <br>
                                            <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_pruebas">Pruebas Solicitadas</button>
                                            <textarea class="form-control" name="cuerpo_comunicado" id="cuerpo_comunicado" style="resize:none;" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="anexos">Anexos</label>
                                            <input class="form-control" type="number" name="anexos" id="anexos">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="forma_envio">Forma de envío <span style="color: red;">(*)</span></label><br>
                                            <select class="forma_envio custom-select" name="forma_envio" id="forma_envio" style="width: 100%;" required>                                                    
                                                <option value="">Seleccione una opción</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="elaboro">Elaboró</label>
                                            <input class="form-control" type="text" name="elaboro" id="elaboro" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="elaboro2" id="elaboro2">                                                
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="reviso">Revisó <span style="color: red;">(*)</span></label><br>
                                            <select class="reviso custom-select" name="reviso" id="reviso" style="width: 100%;" required>                                                    
                                                <option value="">Seleccione una opción</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <br>
                                                <input class="custom-control-input" type="checkbox" id="firmarcomunicado" name="firmarcomunicado" value="firmar comunicado">
                                                <label for="firmarcomunicado" class="custom-control-label">Firmar</label>                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="contenedorCopia">
                                    {{-- <div class="col-6">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="agregar_copia" id="agregar_copia" placeholder="Copia 1">
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-12">
                                        <div class="form-group"> 
                                            <label for="agregar_copia">Agregar copia</label>
                                            <button class="btn btn-info" type="button" onclick="duplicate()" style="border-radius: 50%">
                                               <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div> --}}
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="agregar_copia">Agregar copia</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_afiliado" name="copia_afiliado" value="Afiliado">
                                                    <label for="copia_afiliado" class="custom-control-label">Afiliado</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_empleador" name="copia_empleador" value="Empleador">
                                                    <label for="copia_empleador" class="custom-control-label">Empleador</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_eps" name="copia_eps" value="EPS">
                                                    <label for="copia_eps" class="custom-control-label">EPS</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_afp" name="copia_afp" value="AFP">
                                                    <label for="copia_afp" class="custom-control-label">AFP</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_arl" name="copia_arl" value="ARL">
                                                    <label for="copia_arl" class="custom-control-label">ARL</label>                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="ciudad_comunicado">Ciudad <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="ciudad_comunicado" id="ciudad" value="Bogotá D.C" required>
                                            <input hidden="hidden" type="text" class="form-control" name="Id_evento" id="Id_evento" value="{{$array_datos_calificacionOrigen[0]->ID_evento}}">
                                                <input hidden="hidden" type="text" class="form-control" name="Id_asignacion" id="Id_asignacion" value="{{$array_datos_calificacionOrigen[0]->Id_Asignacion}}">
                                                <input hidden="hidden" type="text" class="form-control" name="Id_procesos" id="Id_procesos" value="{{$array_datos_calificacionOrigen[0]->Id_proceso}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_comunicado">Fecha</label>
                                            <input class="form-control" type="date" name="fecha_comunicado" id="fecha_comunicado" value="{{now()->format('Y-m-d')}}" disabled>
                                            <input hidden="hidden" class="form-control" type="date" name="fecha_comunicado2" id="fecha_comunicado2" value="{{now()->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="radicado">N° Radicado</label>
                                            <input class="form-control" type="text" name="radicado" id="radicado" value="{{$consecutivo}}" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="radicado2" id="radicado2" value="{{$consecutivo}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="Generar_comunicados" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_comunicado alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                        <div class="text-center d-none" id="mostrar_barra_creacion_comunicado">                                
                                            <button class="btn btn-info" type="button" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Guardando Comunicado por favor espere...
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>                       
                    </div>
                </div>
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>            
    </div>
</div>
{{-- Modal actualizar comunicado--}}
<div class="row">
    <div class="contenedor_sol_Generar_comunicado" style="float: left;">
        <x-adminlte-modal id="modalcomunicados_" title="Generar comunicado" theme="info" icon="fas fa-file-pdf" size='xl' disable-animations>
            <div class="row">
                <div class="col-12">
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                    <div class="card-info" style="border: 1.5px solid black; border-radius: 2px;">
                        <div class="card-header text-center">
                            <h5>Generar comunicado</h5>
                        </div>
                        <form name="formu_comunicado" method="POST">
                            @csrf
                            <div class="card-body">                                
                                <div class="row">  
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="cliente_comunicado_act">Cliente</label>
                                            <input class="form-control" type="text" name="cliente_comunicado_act" id="cliente_comunicado_editar" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="cliente_comunicado2_act" id="cliente_comunicado2_editar">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="nombre_afiliado_comunicado_act">Nombre del afiliado</label>
                                            <input class="form-control" type="text" name="nombre_afiliado_comunicado_act" id="nombre_afiliado_comunicado_editar" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="nombre_afiliado_comunicado2_act" id="nombre_afiliado_comunicado2_editar">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="tipo_documento_comunicado_act">Tipo de documento</label>
                                            <input class="form-control" type="text" name="tipo_documento_comunicado_act" id="tipo_documento_comunicado_editar" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="tipo_documento_comunicado2_act" id="tipo_documento_comunicado2_editar">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="identificacion_comunicado_act">N° de identificación</label>
                                            <input class="form-control" type="text" name="identificacion_comunicado_act" id="identificacion_comunicado_editar" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="identificacion_comunicado2_act" id="identificacion_comunicado2_editar">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="id_evento_comunicado_act">ID evento</label>
                                            <input class="form-control" type="text" name="id_evento_comunicado_act" id="id_evento_comunicado_editar"  disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="id_evento_comunicado2_act" id="id_evento_comunicado2_editar">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="n_siniestro_proforma_editar">N° de Siniestro</label>
                                            <input type="text" class="n_siniestro form-control" id="n_siniestro_proforma_editar" name="n_siniestro_proforma_editar" value="{{$N_siniestro_evento[0]->N_siniestro}}">        
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de cambiar el destinatario
                                    (Afiliado y Empresa) debe seleccionar nuevamente la Forma de envio y Revisó y en (Otro) todos.
                                </div>
                                <div class="row">
                                    {{-- <div class="col-3">
                                        <div class="form-group">
                                            <div class="form-check custom-control custom-radio">
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi_editar" id="documentos_origen_editar" value="Documento_Origen" required>
                                                <label class="form-check-label custom-control-label" for="documentos_origen_editar"><strong>Solicitud Documentos Origen</strong></label>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="form-check custom-control custom-radio">
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi_editar" id="otro_documento_origen_editar" value="Otro_Documento" required>
                                                <label class="form-check-label custom-control-label" for="otro_documento_origen_editar"><strong>Otro Documento</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>                          
                                <div class="row text-center">                                  
                                    <label for="destinatario_principal_act" style="margin-left: 7px;">Destinatario Principal: <span style="color: red;">(*)</span></label>                                        
                                    <div class="col-3">
                                        <label for="afiliado_comunicado_editar"><strong>Afiliado</strong></label>
                                        <input class="scalesR" type="radio" name="afiliado_comunicado_act" id="afiliado_comunicado_editar" value="Afiliado" style="margin-left: revert;" required>
                                    </div>
                                    <div class="col-3">
                                        <label for="empresa_comunicado_editar"><strong>Empleador</strong></label>
                                        <input class="scalesR" type="radio" name="afiliado_comunicado_act" id="empresa_comunicado_editar" value="Empleador" style="margin-left: revert;" required>
                                    </div>
                                    <div class="col-3">
                                        <label for="Otro_editar"><strong>Otro</strong></label>
                                        <input class="scalesR" type="radio" name="afiliado_comunicado_act" id="Otro_editar" value="Otro" style="margin-left: revert;" required>
                                    </div>
                                </div>                                                                                                                     
                                <div class="row">                                        
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre_destinatario_act"> Nombre destinatario <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="nombre_destinatario_act" id="nombre_destinatario_editar" required>
                                            <input hidden="hidden" class="form-control" type="text" name="nombre_destinatario_act2" id="nombre_destinatario_editar2" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nic_cc_act">NIT / CC <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="nic_cc_act" id="nic_cc_editar" required>
                                            <input hidden="hidden" class="form-control" type="text" name="nic_cc_act2" id="nic_cc_editar2" required>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="direccion_destinatario_act">Dirección destinatario <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="direccion_destinatario_act" id="direccion_destinatario_editar" required>
                                            <input hidden="hidden" class="form-control" type="text" name="direccion_destinatario_act2" id="direccion_destinatario_editar2" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="telefono_destinatario_act">Telefono destinatario <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text"  name="telefono_destinatario_act" id="telefono_destinatario_editar" required>
                                            <input hidden="hidden" class="form-control" type="text" name="telefono_destinatario_act2" id="telefono_destinatario_editar2" required>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="email_destinatario_act">E-mail destinatario <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="email" name="email_destinatario_act" id="email_destinatario_editar" required>
                                            <input hidden="hidden" class="form-control" type="email" name="email_destinatario_act2" id="email_destinatario_editar2" required>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="departamento_destinatario_act">Departamento <span style="color: red;">(*)</span></label><br>
                                            <select class="departamento_destinatario custom-select" name="departamento_destinatario_act" id="departamento_destinatario_editar" style="width: 100%;" required>                                                        
                                            </select>
                                            <input hidden="hidden" type="text" name="departamento_pdf" id="departamento_pdf">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="ciudad_destinatario_act">Ciudad <span style="color: red;">(*)</span></label><br>
                                            <select class="ciudad_destinatario custom-select" name="ciudad_destinatario_act" id="ciudad_destinatario_editar" style="width: 100%;" required>
                                            </select>
                                            <input hidden="hidden" type="text" name="ciudad_pdf" id="ciudad_pdf">
                                        </div>
                                    </div>                                        
                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="asunto_act">Asunto <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="asunto_act" id="asunto_editar" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-warning d-none" id="insertar_mensaje_importante_editar" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong>
                                            Para mostrar todo el cuerpo del comunicado (dentro del pdf) que usted escriba, debe incluir la etiqueta de las Pruebas Solicitadas dentro de la sección Cuerpo del Comunicado.
                                        </div>
                                        <div class="form-group">
                                            <label for="cuerpo_comunicado_act">Cuerpo del comunicado <span style="color: red;">(*)</span></label>
                                            <br>
                                            <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_pruebas_editar">Pruebas Solicitadas</button>
                                            <textarea class="form-control" name="cuerpo_comunicado_act" id="cuerpo_comunicado_editar" style="resize:none;" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="anexos_act">Anexos</label>
                                            <input class="form-control" type="number" name="anexos_act" id="anexos_editar">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="forma_envio_act">Forma de envío <span style="color: red;">(*)</span></label><br>
                                            <select class="forma_envio_act custom-select" name="forma_envio_act" id="forma_envio_editar" style="width: 100%;" required>                                                    
                                                <option value="">Seleccione una opción</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="elaboro_act">Elaboró</label>
                                            <input class="form-control" type="text" name="elaboro_act" id="elaboro_editar" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="elaboro2_act" id="elaboro2_editar">                                                
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="reviso_act">Revisó <span style="color: red;">(*)</span></label><br>
                                            <select class="reviso custom-select" name="reviso_act" id="reviso_editar" style="width: 100%;" required>                                                    
                                                <option value="">Seleccione una opción</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <br>
                                                <input class="custom-control-input" type="checkbox" id="firmarcomunicado_editar" name="firmarcomunicado_editar" value="firmar comunicado">
                                                <label for="firmarcomunicado_editar" class="custom-control-label">Firmar</label>                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="agregar_copia">Agregar copia</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="edit_copia_afiliado" name="edit_copia_afiliado" value="Afiliado">
                                                    <label for="edit_copia_afiliado" class="custom-control-label">Afiliado</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="edit_copia_empleador" name="edit_copia_empleador" value="Empleador">
                                                    <label for="edit_copia_empleador" class="custom-control-label">Empleador</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="edit_copia_eps" name="edit_copia_eps" value="EPS">
                                                    <label for="edit_copia_eps" class="custom-control-label">EPS</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="edit_copia_afp" name="edit_copia_afp" value="AFP">
                                                    <label for="edit_copia_afp" class="custom-control-label">AFP</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="edit_copia_arl" name="edit_copia_arl" value="ARL">
                                                    <label for="edit_copia_arl" class="custom-control-label">ARL</label>                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="ciudad_comunicado_act">Ciudad <span style="color: red;">(*)</span></label>
                                            <input class="form-control" type="text" name="ciudad_comunicado_act" id="ciudad_comunicado_editar" required>
                                            <input hidden="hidden" type="text" class="form-control" name="Id_comunicado_act" id="Id_comunicado_act">
                                            <input hidden="hidden" type="text" class="form-control" name="Id_evento_act" id="Id_evento_act">
                                            <input hidden="hidden" type="text" class="form-control" name="Id_asignacion_act" id="Id_asignacion_act">
                                            <input hidden="hidden" type="text" class="form-control" name="Id_procesos_act" id="Id_procesos_act">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_comunicado_act">Fecha</label>
                                            <input class="form-control" type="date" name="fecha_comunicado_act" id="fecha_comunicado_editar" disabled>
                                            <input hidden="hidden" class="form-control" type="date" name="fecha_comunicado2_act" id="fecha_comunicado2_editar">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="radicado_act">N° Radicado</label>
                                            <input class="form-control" type="text" name="radicado_act" id="radicado_comunicado_editar" disabled>
                                            <input hidden="hidden" class="form-control" type="text" name="radicado2_act" id="radicado2_comunicado_editar">
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Recuerde Actualizar siempre después de haber modificado uno o más campos, El botón Actualizar se bloquea cuando falte algún campo obligatorio por llenar, y el del PDF se habilitará después de realizar la actualización.
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="button" id="Editar_comunicados" class="btn btn-info" value="Actualizar">
                                            {{-- <input type="submit" id="Pdf" class="btn btn-info" value="Pdf"> --}}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">                                
                                            <button class="btn btn-info d-none" type="button" id="mostrar_barra_descarga_pdf" disabled>
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Descargando PDF por favor espere...
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="alerta_editar_comunicado alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                    </div>
                                    <div class="text-center d-none" id="mostrar_barra_actualizacion_comunicado">                                
                                        <button class="btn btn-info" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Actualizando Comunicado por favor espere...
                                        </button>
                                    </div>
                                </div>
                            </div>                                
                        </form>                                                            
                    </div>
                </div>
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>            
    </div>
</div>

{{-- Modal cargue documentos --}}
<?php $aperturaModal = 'Edicion'; ?>
@include('//.administrador.modalcarguedocumentos')
@include('//.administrador.modalProgressbar')
@include('//.coordinador.modalReemplazarArchivos')
@include('//.coordinador.modalCorrespondencia')
@include('//.modals.confirmacionAccion')
@include('//.modals.historialServicios')
@include('//.modals.alertaRadicado')
@stop
@section('js')

    {{-- SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE LISTADOS DE DOCUMENTOS SEGUIMIENTO --}}
    <script type="text/javascript">
         $(document).ready(function(){
            var listado_docs_seguimiento = $('#listado_docs_seguimiento').DataTable({
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
            autoAdjustColumns(listado_docs_seguimiento);
            var contador = 0;
            $('#btn_agregar_fila').click(function(){
                $('#guardar_datos_tabla').removeClass('d-none');
                $('#actualizar_datos_tabla').addClass('d-none');                
                var Nom_DocSugerido=$('#Nom_DocSugerido').val();
                contador = contador + 1;
                var nueva_fila = [
                    '<?php echo date("Y-m-d");?> <input type="hidden" id="fecha_solicitud_fila_'+contador+'" name="fecha_solicitud" value="{{date("Y-m-d")}}" />',
                    '<textarea id="documento_soli_fila_'+contador+'" class="form-control" name="documento_soli" cols="90" rows="4"></textarea>',
                    '<select id="lista_solicitante_fila_'+contador+'" class="custom-select lista_solicitante_fila_'+contador+'" name="solicitante"><option></option></select><div id="contenedor_otro_solicitante_fila_'+contador+'" class="mt-1"></div>',
                    '<input type="date" class="form-control" id="fecha_recepcion_fila_'+contador+'" name="fecha_recepcion" max="{{date("Y-m-d")}}"/>',
                    '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila" class="text-info" data-fila="fila_'+contador+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                    'fila_'+contador
                ];

                var agregar_fila = listado_docs_seguimiento.row.add(nueva_fila).draw().node();
                //agregar documento sugerido
                $("#documento_soli_fila_" + contador).val(Nom_DocSugerido);
                //Vacia variable global y campo
                Nom_DocSugerido=null;
                $("#Nom_DocSugerido").val("");
                $(agregar_fila).addClass('fila_'+contador);
                $(agregar_fila).attr("id", 'fila_'+contador);

                // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionOrigen.js)
                funciones_elementos_fila(contador);
            });

            // Agregar controlador de eventos para agregar input de fecha al hacer clic en las etiquetas 'a'
            $('#listado_docs_seguimiento').on('click', 'a[id^="btn_edicion_documento_solicitud_"]', function() {
                // Obtener el id de la etiqueta 'a'
                var id_doc_solicitado = $(this).attr('id').split('_').pop();
                // console.log(id_doc_solicitado);
                // Crear el input tipo fecha
                var inputFecha = '<input type="date" class="form-control fecha_recepcion" id="fecha_recepcion_'+id_doc_solicitado+'" max="{{date("Y-m-d")}}"/>';
                
                // Insertar el input tipo fecha en la columna Fecha recepción de la misma fila
                $(this).closest('tr').find('td:eq(3)').html(inputFecha);

                $('#guardar_datos_tabla').addClass('d-none');
                $('#actualizar_datos_tabla').removeClass('d-none');                
            }); 

            $(document).on('click', '#btn_remover_fila', function(){
                var nombre_fila = $(this).data("fila");
                listado_docs_seguimiento.row("."+nombre_fila).remove().draw();
            });

            $(document).on('click', "a[id^='btn_remover_fila_visual_']", function(){
                var nombre_fila = $(this).data("clase_fila");
                listado_docs_seguimiento.row("."+nombre_fila).remove().draw();
            });
            
         });
    </script>
    {{-- SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE HISTORIAL DE SEGUIMIENTOS --}}
    <script type="text/javascript">
        $(document).ready(function(){
            //Deshabilitar el btn de descarga del pdf general lista de chequeo
            $("a[id^='btn_generar_descarga_15']").remove();
            var listado_histo_seguimiento = $('#listado_histori_seguimiento').DataTable({
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
            autoAdjustColumns(listado_histo_seguimiento);
            var contador2 = 0;
            $('#btn_agregar_segui_fila').click(function(){
                contador2 = contador2 + 1;
                var nueva_fila2 = [
                    '<input type="text" class="form-control" id="otro_causal_fila_'+contador2+'" name="otro_causal">',
                    '<input type="date" class="form-control" id="fecha_estipula_fila_'+contador2+'" name="fecha_estipula" max="{{date("Y-m-d")}}"/><span class="d-none" id="fecha_estipula_fila_'+contador2+'_alerta" style="color: red; font-style: italic;"></span>',
                    '<input type="date" class="form-control" id="fecha_seguimiento_fila_'+contador2+'" name="fecha_segui" value="<?php echo date("Y-m-d");?>" readonly/>',
                    '<textarea id="descrip_segui_fila_'+contador2+'" class="form-control" name="descrip_segui" cols="90" rows="4"></textarea>',
                    '<input type="text" class="form-control" id="otro_realizo_fila_'+contador2+'" name="otro_realizo" value="{{$nombre_usuario}}" readonly>',
                    '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_segui_fila" class="text-info" data-fila="fila_'+contador2+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                    'fila_'+contador2
                    ]; 
                var agregar_fila2 = listado_histo_seguimiento.row.add(nueva_fila2).draw().node();
                
                $(agregar_fila2).addClass('fila_'+contador2);
                $(agregar_fila2).attr("id", 'fila_'+contador2);

                // Llamar a la función para añadir la validación al nuevo campo de tipo fecha
                agregarValidacionFecha(`#fecha_estipula_fila_${contador2}`);
                agregarValidacionFecha(`#fecha_seguimiento_fila_${contador2}`);

               // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionOrigen.js)
               //funciones_elementos_fila2(contador2);
            });

            $(document).on('click', '#btn_remover_segui_fila', function(){
                var nombre_fila2 = $(this).data("fila");
                listado_histo_seguimiento.row("."+nombre_fila2).remove().draw();
            });

            $(document).on('click', "a[id^='btn_remover_fila_visual_segui_']", function(){
                var nombre_fila2 = $(this).data("clase_fila");
                listado_histo_seguimiento.row("."+nombre_fila2).remove().draw();
            });
            // Función para agregar la validación a los inputs de tipo fecha
            function agregarValidacionFecha(selector) {
                let today = new Date().toISOString().split("T")[0];
                
                $(selector).on('change', function() {
                    // Validación de fecha mínima
                    if (this.value < '1900-01-01') {
                        $(`#${this.id}_alerta`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                        $('#guardar_datos_seguimiento').addClass('d-none');
                        return;
                    }
                    // Validación de fecha máxima (hoy)
                    if (this.value > today) {
                        $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                        $('#guardar_datos_seguimiento').addClass('d-none');
                        return;
                    }
                    // Limpiar mensaje de error si la fecha es válida
                    $(`#${this.id}_alerta`).text('').addClass("d-none");
                    $('#guardar_datos_seguimiento').removeClass('d-none');
                });
            }
        });
   </script>
    <script>
        //funcion para habilitar el historial de acciones
        function historialDeAcciones() {
            var div = document.getElementById("historialAcciones");
            
            if (div.style.width === "0px") {
                div.style.width = "auto";
                $('#filaprincipal').removeClass('col-12');
                $('#filaprincipal').addClass('col-6');                
                $('#historialAcciones').removeClass('d-none')                
            } else {
                div.style.width = "0px";
                $('#filaprincipal').removeClass('col-6');
                $('#filaprincipal').addClass('col-12');                
                $('#historialAcciones').addClass('d-none');                
            }
        }
        // Obtener el botón
        var boton = document.getElementById('Hacciones');
        // Definir una función de clic que se activará solo una vez
        function clicUnico() {
            // Coloca aquí el código que se ejecutará cuando se presione el botón
            $('#Hacciones').click();
            // Desactivar el event listener después de un clic
            boton.removeEventListener('click', clicUnico);
        }
        // Agregar el event listener al botón
        boton.addEventListener('click', clicUnico);

        //funcion para ocultar el boton guardar
        function OcultarbotonGuardar(){
            $('#Edicion').addClass('d-none');
            $('#Borrar').addClass('d-none');
            /* $('#mostrar-barra2').css("display","block"); */
        }

        $('#Borrar').click(function(){
            location.reload();
        });

        document.getElementById('botonFormulario2').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('formulario2').submit();
        });        
        document.getElementById('botonVerEdicionEvento').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('formularioLlevarEdicionEvento').submit();
        });
         //Elimina sessionStorage
         sessionStorage.removeItem("scrollTop");
    </script>
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script type="text/javascript" src="/js/calificacionOrigen.js"></script>
    <script type="text/javascript" src="/js/funciones_helpers.js?v=1.0.0"></script>
    <script src="/plugins/summernote/summernote.min.js"></script>
    {{-- Validación de fechas, en las cuales la nueva fecha de radicación no puede ser menor a la fecha inicial de radicación. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Obtener referencias a los campos de fecha y elementos de alerta
            const nuevaFechaRadicación = document.getElementById('nueva_fecha_radicacion');
            const fechaRadicacionInicial = document.getElementById('fecha_radicacion');
            const alertaNuevaFechaRadicación = document.getElementById('alertaNuevaFechaDeRadicacion');
            const alertaParametrica = $(".no_ejecutar_parametrica_modulo_principal")[0].classList;
            const today = new Date().toISOString().split("T")[0];

            // Evento para cuando se cambie la fecha de envío
            nuevaFechaRadicación.addEventListener('change', function () {
                // Obtener los valores de las fechas
                const nuevaFechaDeRadicacion = new Date(nuevaFechaRadicación.value);
                const fechaDeRadicacionInicial = fechaRadicacionInicial.value ? new Date(fechaRadicacionInicial.value) : null;

                // Validar que la fecha ingresada no sea menor que 1900-01-01
                if (nuevaFechaRadicación.value < '1900-01-01') {
                    $("#alertaNuevaFechaDeRadicacion").text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                    $('#Edicion').addClass('d-none');
                    return;
                }

                // Validar que la fecha ingresada no sea mayor a la actual
                if (nuevaFechaRadicación.value > today) {
                    $("#alertaNuevaFechaDeRadicacion").text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#Edicion').addClass('d-none');
                    return;
                }

                // Validar que la nueva fecha de radicación no sea menor a la fecha de radicación inicial
                if (fechaRadicacionInicial && fechaDeRadicacionInicial > nuevaFechaDeRadicacion) {
                    $("#alertaNuevaFechaDeRadicacion").text('La fecha ingresada debe ser superior a la fecha de radicación inicial').removeClass('d-none');
                    $('#Edicion').addClass('d-none');
                    return;
                }

                // Si pasa todas las validaciones, ocultar el mensaje de error y habilitar el botón
                $("#alertaNuevaFechaDeRadicacion").text('').addClass('d-none');
                if(alertaParametrica.contains('d-none')) {
                    $('#Edicion').removeClass('d-none');
                }
            });
        });
    </script>
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
                    $('#Edicion').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(this.value > today){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#Edicion').addClass('d-none');
                    return;
                }
                $('#Edicion').removeClass('d-none');
                return $(`#${this.id}_alerta`).text('').addClass("d-none");
            });
        });
    </script>

@stop