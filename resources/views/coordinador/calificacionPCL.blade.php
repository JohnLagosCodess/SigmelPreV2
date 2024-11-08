@extends('adminlte::page')
@section('title', 'Calificación PCL')
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
                <?php elseif (isset($_POST['badera_modulo_principal_pcl']) &&  $_POST['badera_modulo_principal_pcl'] == 'desdebus_mod_pcl' ):?>
                    <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <?php elseif (isset($_POST['badera_modulo_principal_juntas']) &&  $_POST['badera_modulo_principal_juntas'] == 'desdebus_mod_juntas' ):?>
                    <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <?php elseif (isset($_POST['badera_modulo_principal_noti']) &&  $_POST['badera_modulo_principal_noti'] == 'desdebus_mod_noti' ):?>
                    <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <?php elseif(isset($_POST['bd_notificacion']) && $_POST['bd_notificacion'] == true):?>
                    <a href="{{route("bandejaNotifi")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>                                                
                <?php else: ?>
                    <a href="{{route("bandejaPCL")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <?php endif ?>
                <button id="Hacciones" class="btn btn-info"  onclick="historialDeAcciones()"><i class="fas fa-list"></i>Historial Acciones</button>
                <button label="Open Modal" data-toggle="modal" data-target="#historial_servicios" class="btn btn-info"><i class="fas fa-project-diagram mt-1"></i>Historial de servicios</button>
                <p>
                    {{-- <i class="far fa-eye-slash text-danger"></i> Inactivar Menú/Sub Menú &nbsp;--> --}}
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Calificación PCL - Evento: {{$array_datos_calificacionPcl[0]->ID_evento}}</h4>
            <input type="hidden" id="action_actualizar_comunicado" value="{{ route('descargarPdf') }}">
            <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
        </div>
        <form id="form_calificacionPcl" method="POST">
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
                                                    <input type="text" class="form-control" name="cliente" id="cliente" value="{{$array_datos_calificacionPcl[0]->Nombre_Cliente}}" data-id={{$array_datos_calificacionPcl[0]->Id_cliente}} disabled>
                                                    <input type="hidden" class="form-control" name="newId_evento" id="newId_evento" value="{{$array_datos_calificacionPcl[0]->ID_evento}}">
                                                    <input type="hidden" class="form-control" name="newId_asignacion" id="newId_asignacion" value="{{$array_datos_calificacionPcl[0]->Id_Asignacion}}">
                                                    <input type="hidden" class="form-control" name="Id_proceso" id="Id_proceso" value="{{$array_datos_calificacionPcl[0]->Id_proceso}}">
                                                    <input type="hidden" class="form-control" id="Id_servicio" value="{{$array_datos_calificacionPcl[0]->Id_Servicio}}">
                                                    @if (count($dato_validacion_no_aporta_docs) > 0)
                                                    <input hidden="hidden" type="text" class="form-control" data-id_tupla_no_aporta="{{$dato_validacion_no_aporta_docs[0]->Id_Documento_Solicitado}}" id="validacion_aporta_doc" value="{{$dato_validacion_no_aporta_docs[0]->Aporta_documento}}">
                                                    @endif
                                                    <input type="hidden" class="form-control" id="conteo_listado_documentos_solicitados" value="{{count($listado_documentos_solicitados)}}">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="nombre_afiliado">Nombre de afiliado</label>
                                                    <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_calificacionPcl[0]->Nombre_afiliado}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="identificacion">N° Identificación</label>
                                                    <input type="text" class="form-control" name="identificacion" data-tipo="{{$array_datos_calificacionPcl[0]->Nombre_tipo_documento}}" id="identificacion" value="{{$array_datos_calificacionPcl[0]->Nro_identificacion}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="empresa">Empresa actual</label>
                                                    <input type="text" class="form-control" name="empresa" id="empresa" value="{{$array_datos_calificacionPcl[0]->Empresa}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="tipo_evento">Tipo de evento</label>
                                                    <input type="text" class="form-control" name="tipo_evento" id="tipo_evento" value="{{$array_datos_calificacionPcl[0]->Nombre_evento}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="id_evento">ID evento</label>
                                                    <br>
                                                    <input hidden="hidden" type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_calificacionPcl[0]->ID_evento}}" disabled>
                                                {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                                    <a onclick="document.getElementById('botonVerEdicionEvento').click();" id="enlace_ed_evento" style="cursor:pointer; font-weight: bold;" class="btn text-info" type="button"><?php if(!empty($array_datos_calificacionPcl[0]->ID_evento)){echo $array_datos_calificacionPcl[0]->ID_evento;}?></a>                                                
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="tipo_evento">Tipo de afiliado</label>
                                                    <input type="text" class="form-control" name="tipo_afiliado" id="tipo_afiliado" value="{{$array_datos_calificacionPcl[0]->Tipo_afiliado}}" disabled>
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
                                                <input type="text" class="form-control" name="proceso_actual" id="proceso_actual" value="{{$array_datos_calificacionPcl[0]->Nombre_proceso_actual}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                                <div class="form-group">
                                                    <label for="servicio">Servicio</label><br>
                                                    <a onclick="document.getElementById('botonFormulario2').click();" id="llevar_servicio" style="cursor:pointer;" id="servicio_Pcl"><i class="fa fa-puzzle-piece text-info"></i> <strong class="text-dark">{{$array_datos_calificacionPcl[0]->Nombre_servicio}}</strong></a>
                                                    <input type="hidden" class="form-control" name="servicio" id="servicio" value="{{$array_datos_calificacionPcl[0]->Nombre_servicio}}">
                                                </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="proceso_envia">Proceso que envía</label>
                                                <input type="text" class="form-control" name="proceso_envia" id="proceso_envia" value="{{$array_datos_calificacionPcl[0]->Nombre_proceso_anterior}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_radicacion">Fecha de radicación</label>
                                                <input type="date" class="form-control" name="fecha_radicacion" id="fecha_radicacion" value="{{$array_datos_calificacionPcl[0]->F_radicacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_asignacion">Fecha asignación al proceso</label>
                                                <input type="date" class="form-control" name="fecha_asignacion" id="fecha_asignacion" value="{{$array_datos_calificacionPcl[0]->F_registro_asignacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="estado">Estado</label>
                                                <input type="text" class="form-control" name="estado" id="estado" value="{{$array_datos_calificacionPcl[0]->Nombre_estado}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="dias_trascurrido">Dias transcurridos desde el evento</label>
                                                <input type="text" class="form-control" name="dias_trascurrido" id="dias_trascurrido" value="{{$array_datos_calificacionPcl[0]->Dias_transcurridos_desde_el_evento}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="asignado_por">Asignado por</label>
                                                <input type="text" class="form-control" name="asignado_por" id="asignado_por" value="{{$array_datos_calificacionPcl[0]->Asignado_por}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_asignacion_calificacion">Fecha de asignación para calificación</label>
                                                <input type="text" class="form-control" name="fecha_asignacion_calificacion" id="fecha_asignacion_calificacion" value="{{$array_datos_calificacionPcl[0]->Fecha_asignacion_calif}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="profesional_calificador">Profesional Calificador</label>
                                                <input type="text" class="form-control" name="profesional_calificador" id="profesional_calificador" value="<?php if(!empty($array_datos_calificacionPcl[0]->Nombre_calificador)){echo $array_datos_calificacionPcl[0]->Nombre_calificador;}else{ echo 'Sin Calificación';}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_profesional_calificador">Tipo Profesional calificador</label>
                                                <input type="text" class="form-control" name="tipo_profesional_calificador" id="tipo_profesional_calificador" value="<?php if(!empty($array_datos_calificacionPcl[0]->Tipo_Profesional_calificador)){echo $array_datos_calificacionPcl[0]->Tipo_Profesional_calificador;}else{ echo 'Sin Calificación';}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_calificacion">Fecha de calificación</label>
                                                <input type="text" class="form-control" name="fecha_calificacion" id="fecha_calificacion" value="<?php if(!empty($array_datos_calificacionPcl[0]->F_calificacion)){echo $array_datos_calificacionPcl[0]->F_calificacion;}else{ echo 'Sin Calificación';}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="profesional_comite">Profesional Comité</label>
                                                @if ($array_datos_calificacionPcl[0]->Id_Servicio == 9)
                                                    <input type="text" class="form-control" name="profesional_comite" id="profesional_comite" value="Sin Visado" disabled>                                                                                                        
                                                @else
                                                    <input type="text" class="form-control" name="profesional_comite" id="profesional_comite" value="<?php if(!empty($info_comite_inter[0]->Profesional_comite)){echo $info_comite_inter[0]->Profesional_comite;}else{echo 'Sin Visado';}?>" disabled>                                                    
                                                @endif                                               
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">                                                
                                                <label for="fecha_visado_comite">Fecha de visado comité</label>
                                                @if ($array_datos_calificacionPcl[0]->Id_Servicio == 9)
                                                    <input type="text" class="form-control" name="fecha_visado_comite" id="fecha_visado_comite" value="Sin Visado" disabled>                                                                                                        
                                                @else
                                                    <input type="text" class="form-control" name="fecha_visado_comite" id="fecha_visado_comite" value="<?php if(!empty($info_comite_inter[0]->F_visado_comite)){echo $info_comite_inter[0]->F_visado_comite;}else{ echo 'Sin Visado';}?>" disabled>                                                    
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <div class="col-4">
                                            <div class="form-group">                                                
                                                <label for="modalidad_calificacion">Modalidad Calificación</label>                                                    
                                                <select class="modalidad_calificacion custom-select" name="modalidad_calificacion" id="modalidad_calificacion">
                                                    @if ($array_datos_calificacionPcl[0]->Modalidad_calificacion > 0)
                                                        <option value="{{$array_datos_calificacionPcl[0]->Modalidad_calificacion}}" selected>{{$array_datos_calificacionPcl[0]->Nombre_Modalidad_calificacion}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>                                                 
                                            </div>
                                        </div> --}}
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="modalidad_calificacion">Documentos adjuntos</label><br>
                                                <a href="javascript:void(0);" id="cargue_docs" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_devolucion">Fecha devolución comité</label>
                                                <input type="text" class="form-control" name="fecha_devolucion" id="fecha_devolucion" value="<?php if(!empty($array_datos_calificacionPcl[0]->Fecha_devolucion_comite) && $array_datos_calificacionPcl[0]->Fecha_devolucion_comite != '0000-00-00'){echo $array_datos_calificacionPcl[0]->Fecha_devolucion_comite;}else{ echo 'Sin Fecha Devolución';}?>" disabled>
                                            </div>                                                                                                                  
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tiempo_gestion">Tiempo de gestión</label>
                                                <input type="text" class="form-control" name="tiempo_gestion" id="tiempo_gestion" value="{{$array_datos_calificacionPcl[0]->Tiempo_de_gestion}}" disabled>
                                            </div>
                                        </div>  
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fuente_informacion">Fuente de información</label>
                                                <select class="fuente_informacion custom-select" name="fuente_informacion" id="fuente_informacion">
                                                    @if (!empty($array_datos_calificacionPcl[0]->Fuente_informacion))
                                                        <option value="{{$array_datos_calificacionPcl[0]->Fuente_informacion}}" selected>{{$array_datos_calificacionPcl[0]->Nombre_Fuente_informacion}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_ajuste_califi">Fecha de ajuste calificación</label>
                                                <input type="text" class="form-control" name="fecha_ajuste_califi" id="fecha_ajuste_califi" value="<?php if(!empty($array_datos_calificacionPcl[0]->F_ajuste_calificacion)){echo $array_datos_calificacionPcl[0]->F_ajuste_calificacion;}else{ echo 'Sin ajuste Calificación';}?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">Nueva Fecha de radicación</label>
                                                <input type="date" class="form-control" name="nueva_fecha_radicacion" id="nueva_fecha_radicacion" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($array_datos_calificacionPcl[0]->Nueva_F_radicacion)){echo $array_datos_calificacionPcl[0]->Nueva_F_radicacion;}?>">
                                                <span class="d-none" id="alertaNuevaFechaDeRadicacion" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">  
                                                <?php if(!$arraycampa_documento_solicitado->isEmpty()):?>                                                
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
                                                    <input type="text" class="form-control" name="fecha_accion" id="fecha_accion" value="<?php if(!empty($array_datos_calificacionPcl[0]->F_accion_realizar)){echo $array_datos_calificacionPcl[0]->F_accion_realizar; }else{echo now()->format('Y-m-d H:i:s');} ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="accion">Acción <span style="color: red;">(*)</span></label>
                                                    <input type="hidden" id="bd_id_accion" value="<?php if(!empty($array_datos_calificacionPcl[0]->Id_accion)){echo $array_datos_calificacionPcl[0]->Id_accion;}?>">
                                                    <select class="custom-select accion" name="accion" id="accion" required>                                          
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="fecha_alerta">Fecha de alerta</label>
                                                    <input type="datetime-local" class="form-control" name="fecha_alerta" id="fecha_alerta" value="<?php if(!empty($array_datos_calificacionPcl[0]->F_alerta)){echo $array_datos_calificacionPcl[0]->F_alerta;}?>">                                                    
                                                    <span class="d-none" id="alerta_fecha_alerta" style="color: red; font-style: italic;">La Fecha de alerta no puede ser inferior a la fecha actual</span>
                                                    {{-- <input type="date" class="form-control" name="fecha_alerta" id="fecha_alerta" min="{{now()->format('Y-m-d')}}" value="{{$array_datos_calificacionPcl[0]->F_alerta}}"> --}}
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="enviar">Enviar a</label>{{--Selector de bandeja destino--}}
                                                    <select class="custom-select" name="enviar" id="enviar" disabled>
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="estado_facturacion">Estado de Facturación</label>
                                                    <input type="text" class="form-control" name="estado_facturacion" id="estado_facturacion" value="<?php if(!empty($array_datos_calificacionPcl[0]->Estado_Facturacion)){echo $array_datos_calificacionPcl[0]->Estado_Facturacion;}?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="profesional" class="col-form label">Profesional</label>
                                                    <select class="profesional custom-select" name="profesional" id="profesional">
                                                        @if (!empty($array_datos_calificacionPcl[0]->Id_profesional))
                                                            <option value="{{$array_datos_calificacionPcl[0]->Id_profesional}}" selected>{{$array_datos_calificacionPcl[0]->Nombre_profesional}}</option>                                                        
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
                                                        @if (!empty($array_datos_calificacionPcl[0]->Nombre_Causal_devolucion_comite))
                                                            <option value="{{$array_datos_calificacionPcl[0]->Causal_devolucion_comite}}" selected>{{$array_datos_calificacionPcl[0]->Nombre_Causal_devolucion_comite}}</option>
                                                        @else
                                                            <option value="">Seleccione una opción</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="">Fecha de cierre</label>
                                                    <input type="date" class="form-control" name="fecha_cierre" id="fecha_cierre" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($array_datos_calificacionPcl[0]->F_cierre)){echo $array_datos_calificacionPcl[0]->F_cierre;}?>">
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
                                                    {{-- <textarea class="form-control" name="descripcion_accion" id="descripcion_accion" cols="30" rows="5" style="resize: none;">{{$array_datos_calificacionPcl[0]->Descripcion_accion}}</textarea> --}}
                                                    
                                                    <textarea class="form-control" name="descripcion_accion" id="descripcion_accion" cols="30" rows="5" style="resize: none;"></textarea>      
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                    
                            </div>
                        </div>
                    </div>
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
                    @if (empty($info_accion_eventos[0]->Id_Asignacion))
                        <input type="button" id="Edicion" label="Open Modal" data-toggle="modal" data-target="#confirmar_accion" class="btn btn-info" value="Guardar">
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
            <input hidden="hidden" type="text" name="Id_evento_pcl" id="Id_evento_pcl" value="{{$array_datos_calificacionPcl[0]->ID_evento}}">
            <input hidden="hidden" type="text" name="Id_asignacion_pcl" id="Id_asignacion_pcl" value="{{$array_datos_calificacionPcl[0]->Id_Asignacion}}">
            <input hidden="hidden" type="text" name="Idservicio" id="Idservicio" value="<?php if(!empty($array_datos_calificacionPcl[0]->Id_Servicio)){ echo $array_datos_calificacionPcl[0]->Id_Servicio;}?>">
            <button type="submit" id="botonFormulario2" style="display: none; !important"></button>
        </form>
        <form action="{{route('gestionInicialEdicion')}}" id="formularioLlevarEdicionEvento" method="POST">
            @csrf
            <input type="hidden" name="bandera_buscador_clpcl" id="bandera_buscador_clpcl" value="desdeclpcl">
            <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_calificacionPcl[0]->ID_evento)){echo $array_datos_calificacionPcl[0]->ID_evento;}?>">
            <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_calificacionPcl[0]->Id_Asignacion)){echo $array_datos_calificacionPcl[0]->Id_Asignacion;}?>">
            <input hidden="hidden" type="text" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_calificacionPcl[0]->Id_proceso)){ echo $array_datos_calificacionPcl[0]->Id_proceso;}?>">
            <input hidden="hidden" type="text" name="newIdservicio" id="newIdservicio" value="<?php if(!empty($array_datos_calificacionPcl[0]->Id_Servicio)){ echo $array_datos_calificacionPcl[0]->Id_Servicio;}?>">
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
                                <h5>Listado de documentos solicitados</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                    que diligencie en su totalidad los campos.
                                </div>
                                <div class="alert d-none" id="resultado_insercion" role="alert">
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="No_aporta_documentos">No aporta documentos</label> &nbsp;
                                        <input class="scales" type="checkbox" name="No_aporta_documentos" id="No_aporta_documentos" style="margin-left: revert;">
                                    </div> 
                                </div>
                                <div class="table-responsive">
                                    <table id="listado_docs_solicitados" class="table table-striped table-bordered" width="100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>Fecha solicitud documento</th>
                                                <th style="width:164.719px !important;">Documento</th>
                                                <th style="width:200px !important;">Descripción</th>
                                                <th>Solicitada a</th>
                                                <th>Fecha recepción de documentos</th>
                                                <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($listado_documentos_solicitados as $prueba)
                                            <tr class="fila_visual_{{$prueba->Id_Documento_Solicitado}}" id="datos_visuales">
                                                <td>{{$prueba->F_solicitud_documento}}</td>
                                                <td>{{$prueba->Nombre_documento}}</td>
                                                <td>{{$prueba->Descripcion}}</td>
                                                <td>{{$prueba->Nombre_solicitante}}</td>
                                                <td>{{$prueba->F_recepcion_documento}}</td>
                                                <td>
                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_edicion_documento_solicitud_{{$prueba->Id_Documento_Solicitado}}" data-id_fila_editar="{{$prueba->Id_Documento_Solicitado}}" data-clase_fila="fila_visual_{{$prueba->Id_Documento_Solicitado}}" class="text-info"><i class="fas fa-pen" style="font-size:24px;"></i></a></div>
                                                    {{-- <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila_visual_{{$prueba->Id_Documento_Solicitado}}" data-id_fila_quitar="{{$prueba->Id_Documento_Solicitado}}" data-clase_fila="fila_visual_{{$prueba->Id_Documento_Solicitado}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div> --}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div><br>
                                <x-adminlte-button class="mr-auto" id="guardar_datos_tabla" theme="info" label="Guardar"/>
                                <x-adminlte-button class="mr-auto d-none" id="actualizar_datos_tabla" theme="info" label="Actualizar"/>
                                <br><br>
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <div class="form-group">
                                            <a href="javascript:void(0);" id="cargue_docs_modal_listado_docs" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                        </div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <div class="form-group">
                                            <a href="#" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalGenerarComunicado"><i class="fas fa-file-pdf text-info"></i> <strong>Generar Comunicado</strong></a>

                                        </div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <div class="form-group">
                                            <a href="#" class="text-dark text-md" id="abrir_agregar_seguimiento" label="Open Modal" data-toggle="modal" data-target="#modalAgregarSeguimiento"><i class="fas fa-folder-open text-info"></i> <strong>Agregar Seguimiento</strong></a>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
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
                            <div class="card-header text-center">
                                <h5>Historial de seguimientos</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="listado_agregar_seguimientos" class="table table-striped table-bordered" style="width: 100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>Fecha de seguimiento</th>
                                                <th>Causal de seguimiento</th>
                                                <th>Descripción del seguimiento</th>
                                                <th>Realizado por</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
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

    {{-- Modal Agregar seguimiento --}}
    <div class="row">
        <div class="contenedor_sol_Agregar_seguimiento" style="float: left;">
            <x-adminlte-modal id="modalAgregarSeguimiento" title="Agregar Seguimiento" theme="info" icon="fas fa-folder-open" size='xl' disable-animations>
                <div class="row">
                    <div class="col-12">
                        <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                        <div class="card-info" style="border: 1.5px solid black; border-radius: 2px;">
                            <div class="card-header text-center">
                                <h5>Agregar Seguimiento</h5>
                            </div>
                            <form id="form_agregar_seguimientoPcl" method="POST">
                                @csrf
                                <div class="card-body">                                
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="fecha_seguimiento">Fecha Seguimiento <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="date" name="fecha_seguimiento" id="fecha_seguimiento" value="{{now()->format('Y-m-d')}}" max="{{date("Y-m-d")}}" min='1900-01-01' required>
                                                <span class="d-none" id="fecha_seguimiento_alerta" style="color: red; font-style: italic;"></span>
                                            </div> 
                                        </div>                                    
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="causal_seguimiento">Causal de seguimiento <span style="color: red;">(*)</span></label><br>
                                                <select class="causal_seguimiento custom-select" name="causal_seguimiento" id="causal_seguimiento" style="width: 100%;" required>
                                                    <option value="">Seleccione una opción</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="descripcion_seguimiento">Descripción del seguimiento <span style="color: red;">(*)</span></label>
                                                <textarea class="form-control" name="descripcion_seguimiento" id="descripcion_seguimiento" cols="30" rows="5" style="resise:none;" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <input type="submit" id="Guardar_seguimientos" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_seguimiento alert alert-success mt-2 mr-auto d-none" role="alert"></div>
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
                            <form  id="form_generarComunicadoPcl" method="POST">  
                                @csrf                              
                                <div class="card-body">                                
                                    <div class="row">  
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="ciudad_comunicado">Ciudad <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="text" name="ciudad_comunicado" id="ciudad" value="Bogotá D.C." required>
                                                <input hidden="hidden" type="text" class="form-control" name="Id_evento" id="Id_evento" value="{{$array_datos_calificacionPcl[0]->ID_evento}}">
                                                    <input hidden="hidden" type="text" class="form-control" name="Id_asignacion" id="Id_asignacion" value="{{$array_datos_calificacionPcl[0]->Id_Asignacion}}">
                                                    <input hidden="hidden" type="text" class="form-control" name="Id_procesos" id="Id_procesos" value="{{$array_datos_calificacionPcl[0]->Id_proceso}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="fecha_comunicado">Fecha</label>
                                                <input class="form-control" type="date" name="fecha_comunicado" id="fecha_comunicado" value="{{now()->format('Y-m-d')}}" disabled>
                                                <input hidden="hidden" class="form-control" type="date" name="fecha_comunicado2" id="fecha_comunicado2" value="{{now()->format('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="radicado">N° Radicado</label>
                                                <input class="form-control" type="text" name="radicado" id="radicado" value="{{$consecutivo}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="radicado2" id="radicado2" value="{{$consecutivo}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="cliente_comunicado">Cliente</label>
                                                <input class="form-control" type="text" name="cliente_comunicado" id="cliente_comunicado" value="{{$array_datos_calificacionPcl[0]->Nombre_Cliente}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="cliente_comunicado2" id="cliente_comunicado2" value="{{$array_datos_calificacionPcl[0]->Nombre_Cliente}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="nombre_afiliado_comunicado">Nombre del afiliado</label>
                                                <input class="form-control" type="text" name="nombre_afiliado_comunicado" id="nombre_afiliado_comunicado" value="{{$array_datos_calificacionPcl[0]->Nombre_afiliado}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="nombre_afiliado_comunicado2" id="nombre_afiliado_comunicado2" value="{{$array_datos_calificacionPcl[0]->Nombre_afiliado}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="tipo_documento_comunicado">Tipo de documento</label>
                                                <input class="form-control" type="text" name="tipo_documento_comunicado" id="tipo_documento_comunicado" value="{{$array_datos_calificacionPcl[0]->Nombre_tipo_documento}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="tipo_documento_comunicado2" id="tipo_documento_comunicado2" value="{{$array_datos_calificacionPcl[0]->Nombre_tipo_documento}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="identificacion_comunicado">N° de identificación</label>
                                                <input class="form-control" type="text" name="identificacion_comunicado" id="identificacion_comunicado" value="{{$array_datos_calificacionPcl[0]->Nro_identificacion}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="identificacion_comunicado2" id="identificacion_comunicado2" value="{{$array_datos_calificacionPcl[0]->Nro_identificacion}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="id_evento_comunicado">ID evento</label>
                                                <input class="form-control" type="text" name="id_evento_comunicado" id="id_evento_comunicado" value="{{$array_datos_calificacionPcl[0]->ID_evento}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="id_evento_comunicado2" id="id_evento_comunicado2" value="{{$array_datos_calificacionPcl[0]->ID_evento}}">
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
                                        @if (!empty($array_datos_calificacionPcl[0]->Id_Servicio) )
                                            @if ($array_datos_calificacionPcl[0]->Id_Servicio == 6 || $array_datos_calificacionPcl[0]->Id_Servicio == 7)
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi" id="documentos_pcl" value="Documento_PCL" required>
                                                            <label class="form-check-label custom-control-label" for="documentos_pcl"><strong>SOLICITUD DOCUMENTOS (PCL)</strong></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi" id="otro_documento_pcl" value="Otro_Documento" required>
                                                    <label class="form-check-label custom-control-label" for="otro_documento_pcl"><strong>Otro Documento</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!empty($array_datos_calificacionPcl[0]->Id_Servicio) && $array_datos_calificacionPcl[0]->Id_Servicio == 8)
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <div class="form-check custom-control custom-radio">
                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi" id="formatoB_revisionpension" value="Formato_B_Revision_pension" required>
                                                        <label class="form-check-label custom-control-label" for="formatoB_revisionpension"><strong>RATIFICACIÓN (B)</strong></label>
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <div class="form-check custom-control custom-radio">
                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi" id="documento_revisionpension" value="Documento_Revision_pension" required>
                                                        <label class="form-check-label custom-control-label" for="documento_revisionpension"><strong>SOLICITUD DOCUMENTOS (R.V)</strong></label>
                                                    </div>
                                                </div>
                                            </div>                                          
                                        @endif
                                        @if (!empty($array_datos_calificacionPcl[0]->Id_Servicio) && $array_datos_calificacionPcl[0]->Id_Servicio == 7)
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <div class="form-check custom-control custom-radio">
                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi" id="No_procede_recali" value="Documento_No_Recalificacion" required>
                                                        <label class="form-check-label custom-control-label" for="No_procede_recali"><strong>NO RECALIFICACIÓN</strong></label>
                                                    </div>
                                                </div>
                                            </div> 
                                        @endif
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
                                            <div class="form-group">
                                                <label for="cuerpo_comunicado">Cuerpo del comunicado <span style="color: red;">(*)</span></label><br>
                                                @if (!empty($array_datos_calificacionPcl[0]->Id_Servicio) && $array_datos_calificacionPcl[0]->Id_Servicio == 7)
                                                    <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_Origen">Origen</button>                                                    
                                                    <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_nombreCIE10">Nombre CIE10</button>
                                                    <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_porPcl">% PCL</button>
                                                    <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_F_estructuracion">Fecha de estructuracion</button>
                                                @endif
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
                                                <label for="ciudad_comunicado_act">Ciudad <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="text" name="ciudad_comunicado_act" id="ciudad_comunicado_editar" required>
                                                <input hidden="hidden" type="text" class="form-control" name="Id_comunicado_act" id="Id_comunicado_act">
                                                <input hidden="hidden" type="text" class="form-control" name="Id_evento_act" id="Id_evento_act">
                                                <input hidden="hidden" type="text" class="form-control" name="Id_asignacion_act" id="Id_asignacion_act">
                                                <input hidden="hidden" type="text" class="form-control" name="Id_procesos_act" id="Id_procesos_act">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="fecha_comunicado_act">Fecha</label>
                                                <input class="form-control" type="date" name="fecha_comunicado_act" id="fecha_comunicado_editar" disabled>
                                                <input hidden="hidden" class="form-control" type="date" name="fecha_comunicado2_act" id="fecha_comunicado2_editar">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="radicado_act">N° Radicado</label>
                                                <input class="form-control" type="text" name="radicado_act" id="radicado_comunicado_editar" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="radicado2_act" id="radicado2_comunicado_editar">
                                            </div>
                                        </div>
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
                                                <input type="text" class="n_siniestro_proforma_editar form-control" id="n_siniestro_proforma_editar" name="n_siniestro_proforma_editar" value="{{$N_siniestro_evento[0]->N_siniestro}}">        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de cambiar el destinatario
                                        (Afiliado y Empresa) debe seleccionar nuevamente la Forma de envio y Revisó y en (Otro) todos.
                                    </div>            
                                    <div class="row">
                                        @if (!empty($array_datos_calificacionPcl[0]->Id_Servicio) )
                                            @if ($array_datos_calificacionPcl[0]->Id_Servicio == 6 || $array_datos_calificacionPcl[0]->Id_Servicio == 7)
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <div class="form-check custom-control custom-radio">
                                                            <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi_editar" id="documentos_pcl_editar" value="Documento_PCL" required>
                                                            <label class="form-check-label custom-control-label" for="documentos_pcl_editar"><strong>SOLICITUD DOCIMENTOS (PCL)</strong></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi_editar" id="otro_documento_pcl_editar" value="Otro_Documento" required>
                                                    <label class="form-check-label custom-control-label" for="otro_documento_pcl_editar"><strong>Otro Documento</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!empty($array_datos_calificacionPcl[0]->Id_Servicio) && $array_datos_calificacionPcl[0]->Id_Servicio == 8)
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <div class="form-check custom-control custom-radio">
                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi_editar" id="formatoB_revisionpension_editar" value="Formato_B_Revision_pension" required>
                                                        <label class="form-check-label custom-control-label" for="formatoB_revisionpension_editar"><strong>RATIFICACIÓN (B)</strong></label>
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <div class="form-check custom-control custom-radio">
                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi_editar" id="documento_revisionpension_editar" value="Documento_Revision_pension" required>
                                                        <label class="form-check-label custom-control-label" for="documento_revisionpension_editar"><strong>SOLICITUD DOCUMENTOS (R.V)</strong></label>
                                                    </div>
                                                </div>
                                            </div>                                          
                                        @endif
                                        @if (!empty($array_datos_calificacionPcl[0]->Id_Servicio) && $array_datos_calificacionPcl[0]->Id_Servicio == 7)
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <div class="form-check custom-control custom-radio">
                                                        <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_documento_descarga_califi_editar" id="No_procede_recali_editar" value="Documento_No_Recalificacion" required>
                                                        <label class="form-check-label custom-control-label" for="No_procede_recali_editar"><strong>NO RECALIFICACIÓN</strong></label>
                                                    </div>
                                                </div>
                                            </div> 
                                        @endif
                                    </div>               
                                    <div class="row text-center">                                  
                                        <label for="destinatario_principal_act" style="margin-left: 7px;">Destinatario Principal: <span style="color: red;">(*)</span></label>                                        
                                        <div class="col-3">
                                            <label for="afiliado_comunicado_act"><strong>Afiliado</strong></label>
                                            <input class="scalesR" type="radio" name="afiliado_comunicado_act" id="afiliado_comunicado_editar" value="Afiliado" style="margin-left: revert;" required>
                                        </div>
                                        <div class="col-3">
                                            <label for="empresa_comunicado"><strong>Empleador</strong></label>
                                            <input class="scalesR" type="radio" name="afiliado_comunicado_act" id="empresa_comunicado_editar" value="Empleador" style="margin-left: revert;" required>
                                        </div>
                                        <div class="col-3">
                                            <label for="Otro"><strong>Otro</strong></label>
                                            <input class="scalesR" type="radio" name="afiliado_comunicado_act" id="Otro_editar" value="Otro" style="margin-left: revert;" required>
                                        </div>
                                    </div>                                                                                                                     
                                    <div class="row">                                        
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nombre_destinatario_act"> Nombre destinatario <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="text" name="nombre_destinatario_act" id="nombre_destinatario_editar" required>
                                                <input type="hidden" class="form-control" type="text" name="nombre_destinatario_act2" id="nombre_destinatario_editar2" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nic_cc_act">NIT / CC <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="text" name="nic_cc_act" id="nic_cc_editar" required>
                                                <input type="hidden" class="form-control" type="text" name="nic_cc_act2" id="nic_cc_editar2" required>

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="direccion_destinatario_act">Dirección destinatario <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="text" name="direccion_destinatario_act" id="direccion_destinatario_editar" required>
                                                <input type="hidden" class="form-control" type="text" name="direccion_destinatario_act2" id="direccion_destinatario_editar2" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="telefono_destinatario_act">Telefono destinatario <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="text"  name="telefono_destinatario_act" id="telefono_destinatario_editar" required>
                                                <input type="hidden" class="form-control" type="text"  name="telefono_destinatario_act2" id="telefono_destinatario_editar2" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="email_destinatario_act">E-mail destinatario <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="email" name="email_destinatario_act" id="email_destinatario_editar" required>
                                                <input type="hidden" class="form-control" type="email" name="email_destinatario_act2" id="email_destinatario_editar2" required>

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="departamento_destinatario_act">Departamento <span style="color: red;">(*)</span></label><br>
                                                <select class="departamento_destinatario custom-select" name="departamento_destinatario_act" id="departamento_destinatario_editar" style="width: 100%;" required>                                                        
                                                </select>
                                                <input type="hidden" name="departamento_pdf" id="departamento_pdf">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="ciudad_destinatario_act">Ciudad <span style="color: red;">(*)</span></label><br>
                                                <select class="ciudad_destinatario custom-select" name="ciudad_destinatario_act" id="ciudad_destinatario_editar" style="width: 100%;" required>
                                                </select>
                                                <input type="hidden" name="ciudad_pdf" id="ciudad_pdf">
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
                                            <div class="form-group">
                                                <label for="cuerpo_comunicado_act">Cuerpo del comunicado <span style="color: red;">(*)</span></label><br>
                                                @if (!empty($array_datos_calificacionPcl[0]->Id_Servicio) && $array_datos_calificacionPcl[0]->Id_Servicio == 7)
                                                    <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_Origen_editar">Origen</button>                                                    
                                                    <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_nombreCIE10_editar">Nombre CIE10</button>
                                                    <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_porPcl_editar">% PCL</button>
                                                    <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_F_estructuracion_editar">Fecha de estructuracion</button>
                                                @endif
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
                                                    {{-- <option value="">Seleccione una opción</option> --}}
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
                                        {{-- <div class="col-6">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="agregar_copia" id="agregar_copia" placeholder="Copia 1">
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-12">
                                            <div class="form-group"> 
                                                <label for="agregar_copia">Agregar copia</label>
                                                <button class="btn btn-info" type="button" onclick="duplicate3()" style="border-radius: 50%">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row col-12" id="contenedorCopia2"></div> --}}
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
                                    <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Recuerde Actualizar siempre después de haber modificado uno o más campos, El botón Actualizar se bloquea cuando falte algún campo obligatorio por llenar, y el del PDF se habilitará después de realizar la actualización.
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="Editar_comunicados" class="btn btn-info" value="Actualizar">
                                                {{-- <input type="submit" id="Pdf" class="btn btn-info" value="Pdf">                             --}}
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
        
    </script>

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
                    if(this.id === 'fecha_seguimiento'){
                        return $('#Guardar_seguimientos').addClass('d-none')
                    }
                    return $('#Edicion').addClass('d-none');
                    
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(this.value > today){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    if(this.id === 'fecha_seguimiento'){
                        return $('#Guardar_seguimientos').addClass('d-none')
                    }
                    return $('#Edicion').addClass('d-none');
                }
                $('#Guardar_seguimientos').removeClass('d-none')
                $('#Edicion').removeClass('d-none');
                return $(`#${this.id}_alerta`).text('').addClass("d-none");
            });
        });
    </script>

    <script type="text/javascript">
        document.getElementById('botonVerEdicionEvento').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('formularioLlevarEdicionEvento').submit();
        });
        
        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE LISTADOS DE DOCUMENTOS SOLICITADOS
        $(document).ready(function(){
            //Deshabilitar el btn de descarga del pdf general lista de chequeo
            $("a[id^='btn_generar_descarga_15']").remove();

            $(".centrar").css('text-align', 'center');
            var listado_docs_solicitados = $('#listado_docs_solicitados').DataTable({
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

            autoAdjustColumns(listado_docs_solicitados);

            var contador = 0;
            $('#btn_agregar_fila').click(function(){
                $('#guardar_datos_tabla').removeClass('d-none');
                $('#actualizar_datos_tabla').addClass('d-none');                
                contador = contador + 1;
                var nueva_fila = [
                    '<?php echo date("Y-m-d");?> <input type="hidden" id="fecha_solicitud_fila_'+contador+'" name="fecha_solicitud" value="{{date("Y-m-d")}}" />',
                    '<select id="lista_docs_fila_'+contador+'" class="form-comtrol custom-select lista_docs_fila_'+contador+'" name="documento"><option></option></select><div id="contenedor_otro_doc_fila_'+contador+'" class="mt-1"></div>',
                    '<textarea id="descripcion_fila_'+contador+'" class="form-control" name="descripcion" cols="90" rows="4"></textarea>',
                    '<select id="lista_solicitante_fila_'+contador+'" class="custom-select lista_solicitante_fila_'+contador+'" name="solicitante"><option></option></select><div id="contenedor_otro_solicitante_fila_'+contador+'" class="mt-1"></div>',
                    '<input type="date" class="form-control" id="fecha_recepcion_fila_'+contador+'" name="fecha_recepcion" max="{{date("Y-m-d")}}"/>',
                    '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila" class="text-info" data-fila="fila_'+contador+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                    'fila_'+contador
                ];

                var agregar_fila = listado_docs_solicitados.row.add(nueva_fila).draw().node();
                $(agregar_fila).addClass('fila_'+contador);
                $(agregar_fila).attr("id", 'fila_'+contador);

                // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
                funciones_elementos_fila(contador);
            });

            // Agregar controlador de eventos para agregar input de fecha al hacer clic en las etiquetas 'a'
            $('#listado_docs_solicitados').on('click', 'a[id^="btn_edicion_documento_solicitud_"]', function() {
                // Obtener el id de la etiqueta 'a'
                var id_doc_solicitado = $(this).attr('id').split('_').pop();
                // console.log(id_doc_solicitado);
                // Crear el input tipo fecha
                var inputFecha = '<input type="date" class="form-control fecha_recepcion" id="fecha_recepcion_'+id_doc_solicitado+'" max="{{date("Y-m-d")}}"/>';
                
                // Insertar el input tipo fecha en la columna Fecha recepción de la misma fila
                $(this).closest('tr').find('td:eq(4)').html(inputFecha);

                $('#guardar_datos_tabla').addClass('d-none');
                $('#actualizar_datos_tabla').removeClass('d-none');                
            });
            
            $(document).on('click', '#btn_remover_fila', function(){
                var nombre_fila = $(this).data("fila");
                listado_docs_solicitados.row("."+nombre_fila).remove().draw();
            });

            $(document).on('click', "a[id^='btn_remover_fila_visual_']", function(){
                var nombre_fila = $(this).data("clase_fila");
                listado_docs_solicitados.row("."+nombre_fila).remove().draw();
            });

            //Elimina sessionStorage
            sessionStorage.removeItem("scrollToptecnica");
            sessionStorage.removeItem("scrollTopPronuncia");

        });
    </script>
    
    <script type="text/javascript" src="/js/calificacionpcl.js"></script>
    <script type="text/javascript" src="/js/funciones_helpers.js?v=1.0.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script src="/plugins/summernote/summernote.min.js"></script>
@stop