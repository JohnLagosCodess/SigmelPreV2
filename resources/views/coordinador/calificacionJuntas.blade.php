@extends('adminlte::page')
@section('title', 'Juntas')
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
                <?php if (isset($_POST['badera_modulo_principal_juntas']) &&  $_POST['badera_modulo_principal_juntas'] == 'desdebus_mod_juntas' ):?>
                    <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>                
                <?php else: ?>
                    <a href="{{route("bandejaJuntas")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <?php endif ?>
                <button id="Hacciones" class="btn btn-info"  onclick="historialDeAcciones()"><i class="fas fa-list"></i>Historial Acciones</button>                
                <p>
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Módulo Juntas - Evento: {{$array_datos_calificacionJuntas[0]->ID_evento}}</h4>
            <input type="hidden" id="action_actualizar_comunicado" value="{{ route('descargarPdf') }}">
        </div>
        <form id="form_calificacionJuntas" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12" id="filaprincipal">
                        <div id="aumentarColAfiliado"> 
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Información del afiliado</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="cliente">Cliente</label>
                                                <input type="text" class="form-control" name="cliente" id="cliente" value="{{$array_datos_calificacionJuntas[0]->Nombre_Cliente}}" disabled>
                                                <input type="hidden" class="form-control" name="newId_evento" id="newId_evento" value="{{$array_datos_calificacionJuntas[0]->ID_evento}}">
                                                <input type="hidden" class="form-control" name="newId_asignacion" id="newId_asignacion" value="{{$array_datos_calificacionJuntas[0]->Id_Asignacion}}">
                                                <input type="hidden" class="form-control" name="Id_proceso" id="Id_proceso" value="{{$array_datos_calificacionJuntas[0]->Id_proceso}}">
                                                <input type="hidden" class="form-control" id="Id_servicio" value="{{$array_datos_calificacionJuntas[0]->Id_Servicio}}">
                                                @if (count($dato_validacion_no_aporta_docs) > 0)
                                                <input hidden="hidden" type="text" class="form-control" data-id_tupla_no_aporta="{{$dato_validacion_no_aporta_docs[0]->Id_Documento_Solicitado}}" id="validacion_aporta_doc" value="{{$dato_validacion_no_aporta_docs[0]->Aporta_documento}}">
                                                @endif
                                                <input type="hidden" class="form-control" id="conteo_listado_documentos_solicitados" value="{{count($listado_documentos_solicitados)}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nombre_afiliado">Nombre de afiliado</label>
                                                <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_calificacionJuntas[0]->Nombre_afiliado}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="identificacion">N° Identificación</label>
                                                <input type="text" class="form-control" name="identificacion" id="identificacion" value="{{$array_datos_calificacionJuntas[0]->Nro_identificacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="empresa">Empresa actual</label>
                                                <input type="text" class="form-control" name="empresa" id="empresa" value="{{$array_datos_calificacionJuntas[0]->Empresa}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_evento">Tipo de evento</label>
                                                <input type="text" class="form-control" name="tipo_evento" id="tipo_evento" value="{{$array_datos_calificacionJuntas[0]->Nombre_evento}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="id_evento">ID evento</label>
                                                <br>
                                                <input hidden="hidden" type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_calificacionJuntas[0]->ID_evento}}" disabled>
                                                {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                                <a onclick="document.getElementById('botonVerEdicionEvento').click();" style="cursor:pointer; font-weight: bold;" class="btn text-info" type="button"><?php if(!empty($array_datos_calificacionJuntas[0]->ID_evento)){echo $array_datos_calificacionJuntas[0]->ID_evento;}?></a>                                            
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_evento">Tipo de afiliado</label>
                                                <input type="text" class="form-control" name="tipo_afiliado" id="tipo_afiliado" value="{{$array_datos_calificacionJuntas[0]->Tipo_afiliado}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>     
                        </div>
                        <div id="aumentarColActividad">                                    
                            <div class="card-info">
                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                    <h5>Información de la actividad</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="proceso_actual">Proceso actual</label>
                                                <input type="text" class="form-control" name="proceso_actual" id="proceso_actual" value="{{$array_datos_calificacionJuntas[0]->Nombre_proceso_actual}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            @if ($dato_rol=='3')
                                                <div class="form-group">
                                                    <label for="servicio">Servicio</label><br>
                                                    <i class="fa fa-puzzle-piece text-info"></i> <strong class="text-dark">{{$array_datos_calificacionJuntas[0]->Nombre_servicio}}</strong>
                                                    <input type="hidden" class="form-control" name="servicio" id="servicio" value="{{$array_datos_calificacionJuntas[0]->Nombre_servicio}}">
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <label for="servicio">Servicio</label><br>
                                                    <a onclick="document.getElementById('botonFormulario2').click();" style="cursor:pointer;" id="servicio_Juntas"><i class="fa fa-puzzle-piece text-info"></i> <strong class="text-dark">{{$array_datos_calificacionJuntas[0]->Nombre_servicio}}</strong></a>
                                                    <input type="hidden" class="form-control" name="servicio" id="servicio" value="{{$array_datos_calificacionJuntas[0]->Nombre_servicio}}">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="proceso_envia">Proceso que envía</label>
                                                <input type="text" class="form-control" name="proceso_envia" id="proceso_envia" value="{{$array_datos_calificacionJuntas[0]->Nombre_proceso_anterior}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_radicacion">Fecha de radicación</label>
                                                <input type="date" class="form-control" name="fecha_radicacion" id="fecha_radicacion" value="{{$array_datos_calificacionJuntas[0]->F_radicacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_asignacion">Fecha asignación al proceso</label>
                                                <input type="date" class="form-control" name="fecha_asignacion" id="fecha_asignacion" value="{{$array_datos_calificacionJuntas[0]->F_registro_asignacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="estado">Estado</label>
                                                <input type="text" class="form-control" name="estado" id="estado" value="{{$array_datos_calificacionJuntas[0]->Nombre_estado}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="dias_trascurrido">Dias transcurridos desde el evento</label>
                                                <input type="text" class="form-control" name="dias_trascurrido" id="dias_trascurrido" value="{{$array_datos_calificacionJuntas[0]->Dias_transcurridos_desde_el_evento}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="asignado_por">Asignado por</label>
                                                <input type="text" class="form-control" name="asignado_por" id="asignado_por" value="{{$array_datos_calificacionJuntas[0]->Asignado_por}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_asignacion_juntas">Fecha de asignación para Juntas </label>
                                                <input type="text" class="form-control" name="fecha_asignacion_juntas" id="fecha_asignacion_juntas" value="{{$array_datos_calificacionJuntas[0]->Fecha_asignacion_calif}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_dictamen_jrci">Fecha de notificación dictamen (JRCI)</label>
                                                <input type="text" class="form-control" name="fecha_dictamen_jrci" id="fecha_dictamen_jrci" value="{{$array_datos_calificacionJuntas[0]->F_dictamen_jrci_emitido}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="profesional_pronunciamiento">Profesional pronunciamiento</label>
                                                <input type="text" class="form-control" name="profesional_pronunciamiento" id="profesional_pronunciamiento" value="{{$array_datos_calificacionJuntas[0]->Nombre_profesional}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_profesional_pronunciamiento">Tipo Profesional pronunciamiento</label>
                                                <input type="text" class="form-control" name="tipo_profesional_pronunciamiento" id="tipo_profesional_pronunciamiento" value="{{$array_datos_calificacionJuntas[0]->Tipo_Profesional_calificador}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_ejecutoria_jrci">Fecha Acta Ejecutoria emitida por JRCI</label>
                                                <input type="text" class="form-control" name="fecha_ejecutoria_jrci" id="fecha_ejecutoria_jrci" value="{{$array_datos_calificacionJuntas[0]->F_acta_ejecutoria_emitida_jrci}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_notificacion_jrci">Fecha notificación de recurso ante JRCI</label>
                                                <input type="text" class="form-control" name="fecha_notificacion_jrci" id="fecha_notificacion_jrci" value="{{$array_datos_calificacionJuntas[0]->F_notificacion_recurso_jrci}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_notificacion_jnci">Fecha de notificación dictamen (JNCI)</label>
                                                <input type="text" class="form-control" name="fecha_notificacion_jnci" id="fecha_notificacion_jnci" value="{{$array_datos_calificacionJuntas[0]->F_noti_ante_jnci}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tiempo_gestion">Tiempo de gestión</label>
                                                <input type="text" class="form-control" name="tiempo_gestion" id="tiempo_gestion" value="{{$array_datos_calificacionJuntas[0]->Tiempo_de_gestion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="modalidad_documentos">Documentos adjuntos</label><br>
                                                <a href="javascript:void(0);" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <?php if(!$arraycampa_documento_solicitado->isEmpty()):?>                                                
                                                    <a href="#" id="clicGuardado" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modalControversiaSeguimiento"><i class="fas fa-book-open text-info"></i> <strong>Gestión de controversia - Seguimiento</strong> <i class="fas fa-bell text-info icono"></i></a>
                                                <?php else:?>
                                                    <a href="#" id="clicGuardado" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modalControversiaSeguimiento"><i class="fas fa-book-open text-info"></i> <strong>Gestión de controversia - Seguimiento</strong></a>
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
                                                    @if (!empty($array_datos_calificacionJuntas[0]->F_accion_realizar))
                                                        <input type="date" class="form-control" name="fecha_accion" id="fecha_accion" value="{{$array_datos_calificacionJuntas[0]->F_accion_realizar}}" disabled>
                                                        <input hidden="hidden" type="date" class="form-control" name="f_accion" id="f_accion" value="{{$array_datos_calificacionJuntas[0]->F_accion_realizar}}">
                                                    @else
                                                        <input type="date" class="form-control" name="fecha_accion" id="fecha_accion" value="{{now()->format('Y-m-d')}}" disabled>
                                                        <input hidden="hidden" type="date" class="form-control" name="f_accion" id="f_accion" value="{{now()->format('Y-m-d')}}">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="accion">Acción <span style="color: red;">(*)</span></label>
                                                    <input type="hidden" id="bd_id_accion" value="<?php if(!empty($array_datos_calificacionJuntas[0]->Id_accion)){echo $array_datos_calificacionJuntas[0]->Id_accion;}?>">
                                                    <select class="custom-select accion" name="accion" id="accion" style="color: red;">
                                                        {{-- <option value="NO ESTA DEFINIDO">NO ESTA DEFINIDO</option> --}}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="fecha_alerta">Fecha de alerta</label>
                                                    <input type="date" class="form-control" name="fecha_alerta" id="fecha_alerta" min="{{now()->format('Y-m-d')}}" value="{{$array_datos_calificacionJuntas[0]->F_alerta}}">
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
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="descripcion_accion">Descripción acción</label>
                                                    <textarea class="form-control" name="descripcion_accion" id="descripcion_accion" cols="30" rows="5" style="resize: none;">{{$array_datos_calificacionJuntas[0]->Descripcion_accion}}</textarea>                                                
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
            </div>
            <div class="card-footer">
                <div class="alert alert-danger no_ejecutar_parametrica_modulo_principal d-none" role="alert">
                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede actualizar la información debido a que el proceso, servicio y/o acción no tienen una parametrización
                    asociada. Debe configurar una.
                </div>
                <div class="grupo_botones">
                    <input type="reset" id="Borrar" class="btn btn-info" value="Restablecer">
                    @if (empty($array_datos_calificacionJuntas[0]->Accion_realizar))
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
        <form action="{{route($SubModulo)}}" id="formulario2" method="POST">            
            @csrf
            <input hidden="hidden" type="text" name="Id_evento_juntas" id="Id_evento_juntas" value="{{$array_datos_calificacionJuntas[0]->ID_evento}}">
            <input hidden="hidden" type="text" name="Id_asignacion_juntas" id="Id_asignacion_juntas" value="{{$array_datos_calificacionJuntas[0]->Id_Asignacion}}">
            <input hidden="hidden" type="text" name="Id_proceso_juntas" id="Id_proceso_juntas" value="{{$array_datos_calificacionJuntas[0]->Id_proceso}}">
            <button type="submit" id="botonFormulario2" style="display: none; !important"></button>
        </form>
        <!--Retonar al modulo Modulo Nuevo edicion -->
        <form action="{{route('gestionInicialEdicion')}}" id="formularioLlevarEdicionEvento" method="POST">
            @csrf
            <input type="hidden" name="bandera_buscador_juntas" id="bandera_buscador_juntas" value="desdejuntas">
            <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_calificacionJuntas[0]->ID_evento)){echo $array_datos_calificacionJuntas[0]->ID_evento;}?>">
            <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_calificacionJuntas[0]->Id_Asignacion)){echo $array_datos_calificacionJuntas[0]->Id_Asignacion;}?>">
            <input hidden="hidden" type="text" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_calificacionJuntas[0]->Id_proceso)){ echo $array_datos_calificacionJuntas[0]->Id_proceso;}?>">
            <input hidden="hidden" type="text" name="newIdservicio" id="newIdservicio" value="<?php if(!empty($array_datos_calificacionJuntas[0]->Id_Servicio)){ echo $array_datos_calificacionJuntas[0]->Id_Servicio;}?>">
            <button type="submit" id="botonVerEdicionEvento" style="display:none !important;"></button>
        </form>
    </div>
    {{-- Modal Gestion de controversia - Seguimiento --}}
    <div class="row">
        <div class="contenedor_controversia_seguimiento" style="float: left;">
            <x-adminlte-modal id="modalControversiaSeguimiento" class="modalscroll" title="Gestión de controversia - Seguimiento" theme="info" icon="fas fa-book-open" size='xl' disable-animations>
                <div class="row">
                    <div class="col-12">
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información del afiliado</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Nom_afiliado">Nombre de afiliado</label>
                                            <input type="text" class="form-control" name="Nom_afiliado" id="Nom_afiliado" value="{{$array_datos_calificacionJuntas[0]->Nombre_afiliado}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="N_identificacion">N° de identificación</label>
                                            <input type="text" class="form-control" name="N_identificacion" id="N_identificacion" value="{{$array_datos_calificacionJuntas[0]->Nro_identificacion}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Id_Evento">ID evento</label>
                                            <input type="text" class="form-control" name="Id_Evento" id="Id_Evento" value="{{$array_datos_calificacionJuntas[0]->ID_evento}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Evento_Dir">Dirección</label>
                                            <input type="text" class="form-control" name="Evento_Dir" id="Evento_Dir" value="{{$arrayinfo_afiliado[0]->Direccion}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Evento_Ciudad">Ciudad</label>
                                            <input type="text" class="form-control" name="Evento_Ciudad" id="Evento_Ciudad" value="{{$arrayinfo_afiliado[0]->Nombre_municipio}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Evento_Depar">Departamento</label>
                                            <input type="text" class="form-control" name="Evento_Depar" id="Evento_Depar" value="{{$arrayinfo_afiliado[0]->Nombre_departamento}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Datos_movil">Teléfono / Celular</label>
                                            <input type="text" class="form-control" name="Datos_movil" id="Datos_movil" value="{{$arrayinfo_afiliado[0]->Telefono_contacto}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Evento_Email">E-mail</label>
                                            <input type="text" class="form-control" name="Evento_Email" id="Evento_Email" value="{{$arrayinfo_afiliado[0]->Email}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <br>
                                            <label for="Se_actualiza_datos">Se actualizaron datos</label> &nbsp;
                                            <input class="scales" type="checkbox" name="Se_actualiza_datos" id="Se_actualiza_datos" value="Se_actualiza_datos" style="margin-left: revert;" <?php if(!empty($arrayinfo_afiliado[0]->F_actualizacion)){ ?> checked <?php } ?> disabled>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>    
                        <!-- Datos del Dictamen Controvertido -->
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Datos del Dictamen Controvertido</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarControvertido" method="POST">
                                    @csrf 
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="Tipo_evento_juntas">Tipo de evento<span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="Tipo_evento_juntas" id="Tipo_evento_juntas" value="{{$array_datos_calificacionJuntas[0]->Nombre_evento}}" disabled>
                                            </div>
                                        </div>
                                        <div <?php if(!empty($array_datos_calificacionJuntas[0]->Nombre_evento) && $array_datos_calificacionJuntas[0]->Nombre_evento=='Enfermedad'){ ?> class="col-4 text-center" <?php }else{ ?> class="col-4 text-center d-none" <?php } ?>  id="contenedor_enfermedad" >
                                            <div class="form-group">
                                                <br>
                                                <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="enfermedad_heredada" name="enfermedad_heredada" value="Enfermedad Heredada" @if (!empty($arrayinfo_controvertido[0]->Enfermedad_heredada) && $arrayinfo_controvertido[0]->Enfermedad_heredada=='Enfermedad Heredada') checked @endif>
                                                        <label for="enfermedad_heredada" class="custom-control-label">Enfermedad Heredada</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div <?php if(!empty($arrayinfo_controvertido[0]->F_transferencia_enfermedad)){ ?> class="col-4" <?php }else{ ?> class="col-4 text-center d-none" <?php } ?> id="contenedor_enfermedad_fecha">
                                            <div class="form-group">
                                                <label for="f_transferencia_enfermedad">Fecha transferencia de enfermedad<span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_transferencia_enfermedad" id="f_transferencia_enfermedad" max="{{now()->format('Y-m-d')}}" value="<?php if(!empty($arrayinfo_controvertido[0]->F_transferencia_enfermedad)) { echo $arrayinfo_controvertido[0]->F_transferencia_enfermedad;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="primer_calificador">Primer Calificador<span style="color: red;">(*)</span></label>
                                                <select class="custom-select primer_calificador" name="primer_calificador" id="primer_calificador" style="width: 100%;" required>
                                                    @if (!empty($arrayinfo_controvertido[0]->Primer_calificador))
                                                            <option value="{{$arrayinfo_controvertido[0]->Primer_calificador}}" selected>{{$arrayinfo_controvertido[0]->Calificador}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nom_entidad">Nombre de entidad calificadora<span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control soloPrimeraLetraMayus" name="nom_entidad" id="nom_entidad" value="<?php if(!empty($arrayinfo_controvertido[0]->Nom_entidad)) { echo $arrayinfo_controvertido[0]->Nom_entidad;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="N_dictamen_controvertido">N° Dictamen controvertido<span style="color: red;">(*)</span></label>
                                                <input type="number" class="form-control soloNumeros" name="N_dictamen_controvertido" id="N_dictamen_controvertido" value="<?php if(!empty($arrayinfo_controvertido[0]->N_dictamen_controvertido)) { echo $arrayinfo_controvertido[0]->N_dictamen_controvertido;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_dictamen_controvertido">Fecha dictámen controvertido<span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_dictamen_controvertido" id="f_dictamen_controvertido" max="{{now()->format('Y-m-d')}}" value="<?php if(!empty($arrayinfo_controvertido[0]->F_dictamen_controvertido)) { echo $arrayinfo_controvertido[0]->F_dictamen_controvertido;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_notifi_afiliado">Fecha notificación al afiliado<span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_notifi_afiliado" id="f_notifi_afiliado" max="{{now()->format('Y-m-d')}}" value="<?php if(!empty($arrayinfo_controvertido[0]->F_notifi_afiliado)) { echo $arrayinfo_controvertido[0]->F_notifi_afiliado;} ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                @if (empty($arrayinfo_controvertido[0]->ID_evento))
                                                    <input type="submit" id="guardar_datos_controvertido" class="btn btn-info" value="Guardar">
                                                @else 
                                                    <input type="submit" id="guardar_datos_controvertido" class="btn btn-info" value="Actualizar">
                                                @endif    
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_controvertido alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Datos informacion de la controversia -->
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información de la controversia</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarControversia" method="POST">
                                    @csrf 
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="parte_controvierte_califi">Parte que controvierte primera calificación<br><span style="color: red;">(*)</span></label>
                                                <select class="custom-select parte_controvierte_califi" name="parte_controvierte_califi" id="parte_controvierte_califi" style="width: 100%;" required>
                                                    @if (!empty($arrayinfo_controvertido[0]->Parte_controvierte_califi))
                                                        <option value="{{$arrayinfo_controvertido[0]->Parte_controvierte_califi}}" selected>{{$arrayinfo_controvertido[0]->ParteCalificador}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nombre_controvierte_califi">Nombre de quien Controvierte primera calificación<span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control soloPrimeraLetraMayus" name="nombre_controvierte_califi" id="nombre_controvierte_califi" value="<?php if(!empty($arrayinfo_controvertido[0]->Nombre_controvierte_califi)) { echo $arrayinfo_controvertido[0]->Nombre_controvierte_califi;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="n_radicado_entrada_contro"> N° Radicado Entrada - Controversia primera calificación<span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_radicado_entrada_contro" id="n_radicado_entrada_contro" value="<?php if(!empty($arrayinfo_controvertido[0]->N_radicado_entrada_contro)) { echo $arrayinfo_controvertido[0]->N_radicado_entrada_contro;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tipo_controvierte_califi">Tipo de controversia primera calificación<span style="color: red;">(*)</span></label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_origen" name="contro_origen" value="Origen" @if (!empty($arrayinfo_controvertido[0]->Contro_origen) && $arrayinfo_controvertido[0]->Contro_origen=='Origen') checked @endif >
                                                    <label for="contro_origen" class="custom-control-label">Origen</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_pcl" name="contro_pcl" value="% PCL" @if (!empty($arrayinfo_controvertido[0]->Contro_pcl) && $arrayinfo_controvertido[0]->Contro_pcl=='% PCL') checked @endif>
                                                    <label for="contro_pcl" class="custom-control-label">%PCL</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_diagnostico" name="contro_diagnostico" value="Diagnósticos" @if (!empty($arrayinfo_controvertido[0]->Contro_diagnostico) && $arrayinfo_controvertido[0]->Contro_diagnostico=='Diagnósticos') checked @endif >
                                                    <label for="contro_diagnostico" class="custom-control-label">Diagnósticos</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_f_estructura" name="contro_f_estructura" value="Fecha estructuración" @if (!empty($arrayinfo_controvertido[0]->Contro_f_estructura) && $arrayinfo_controvertido[0]->Contro_f_estructura=='Fecha estructuración') checked @endif>
                                                    <label for="contro_f_estructura" class="custom-control-label">Fecha estructuración</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_m_califi" name="contro_m_califi" value="Manual de calificación" @if (!empty($arrayinfo_controvertido[0]->Contro_m_califi) && $arrayinfo_controvertido[0]->Contro_m_califi=='Manual de calificación') checked @endif>
                                                    <label for="contro_m_califi" class="custom-control-label">Manual de calificación</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_contro_primer_califi">Fecha de controversia primera calificación<br><span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_contro_primer_califi" id="f_contro_primer_califi" max="{{now()->format('Y-m-d')}}" value="<?php if(!empty($arrayinfo_controvertido[0]->F_contro_primer_califi)) { echo $arrayinfo_controvertido[0]->F_contro_primer_califi;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_contro_radi_califi">Fecha de radicación controversia primera calificación<span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_contro_radi_califi" id="f_contro_radi_califi" max="{{now()->format('Y-m-d')}}" value="<?php if(!empty($arrayinfo_controvertido[0]->F_contro_radi_califi)) { echo $arrayinfo_controvertido[0]->F_contro_radi_califi;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="termino_contro_califi">Término de controversia ante primera calificación</label>
                                                <input type="text" class="form-control" name="termino_contro_califi" id="termino_contro_califi" value="<?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_califi)) { echo $arrayinfo_controvertido[0]->Termino_contro_califi;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="jrci_califi_invalidez">Junta Regional de Calificación de Invalidez (JRCI)</label>
                                                <select class="custom-select jrci_califi_invalidez" name="jrci_califi_invalidez" id="jrci_califi_invalidez" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Jrci_califi_invalidez))
                                                        <option value="{{$arrayinfo_controvertido[0]->Jrci_califi_invalidez}}" selected>{{$arrayinfo_controvertido[0]->JrciNombre}}</option>
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
                                                @if (empty($arrayinfo_controvertido[0]->ID_evento))
                                                    <input type="submit" id="guardar_datos_controversia" class="btn btn-info" value="Guardar">
                                                @else 
                                                    <input type="submit" id="guardar_datos_controversia" class="btn btn-info" value="Actualizar">
                                                @endif    
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_controversia alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Datos informacion Pago de Honorarios  -->
                        <div class="card-info" <?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_califi) && $arrayinfo_controvertido[0]->Termino_contro_califi=='Fuera de términos'){ ?> style="display:none" <?php } ?>>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Pago de Honorarios</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarPagosjuntas" method="POST">
                                    @csrf 
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_pago">Tipo de Pago<span style="color: red;">(*)</span></label>
                                                <select class="custom-select tipo_pago" name="tipo_pago" id="tipo_pago" style="width: 100%;" required>
                                                    <option value="">Seleccione una opción</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_solicitud_pago">Fecha solicitud de pago<span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_solicitud_pago" id="f_solicitud_pago" max="{{now()->format('Y-m-d')}}" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="pago_junta">Junta<span style="color: red;">(*)</span></label>
                                                <select class="custom-select tipo_pago" name="pago_junta" id="pago_junta" style="width: 100%;" required>
                                                    <option value="">Seleccione una opción</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="n_orden_pago">N° orden de pago<span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_orden_pago" id="n_orden_pago" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="valor_pagado">Valor Pagado</label>
                                                <input type="text" class="form-control soloContabilidad" name="valor_pagado" id="valor_pagado">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_pago_honorarios">Fecha pago de honorarios<span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_pago_honorarios" id="f_pago_honorarios" max="{{now()->format('Y-m-d')}}" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_pago_radicacion">Fecha de radicación pago<span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_pago_radicacion" id="f_pago_radicacion" max="{{now()->format('Y-m-d')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_pagos" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_pagos alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Datos Historial de pagos  -->
                        <div class="card-info" <?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_califi) && $arrayinfo_controvertido[0]->Termino_contro_califi=='Fuera de términos'){ ?> style="display:none" <?php } ?>>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Histórico de Honorarios</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="listado_pagos_honorarios" class="table table-striped table-bordered" style="width: 100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>N°</th>
                                                <th>Tipo de Pago</th>
                                                <th>Fecha solicitud de pago</th>
                                                <th>Junta</th>
                                                <th>N° orden de pago</th>
                                                <th>Valor Pagado</th>
                                                <th>Fecha pago de honorarios</th>
                                                <th>Fecha de radicación pago</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $iterar = 0;?>
                                            @foreach ($arrayinfo_pagos as $listapagos)
                                                <tr>
                                                    <td><?php echo $iterar = $iterar + 1; ?></td>
                                                    <td>{{$listapagos->NomPago}}</td>
                                                    <td>{{$listapagos->F_solicitud_pago}}</td>
                                                    <td>{{$listapagos->JuntaPago}}</td>
                                                    <td>{{$listapagos->N_orden_pago}}</td>
                                                    <td>{{$listapagos->Valor_pagado}}</td>
                                                    <td>{{$listapagos->F_pago_honorarios}}</td>
                                                    <td>{{$listapagos->F_pago_radicacion}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Solicitud documentos -->
                        <div class="card-info" <?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_califi) && $arrayinfo_controvertido[0]->Termino_contro_califi=='Fuera de términos'){ ?> style="display:none" <?php } ?>>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Solicitud de documentos</h5>
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
                                        <input class="scales" type="checkbox" name="No_aporta_documentos" id="No_aporta_documentos" style="margin-left: revert;" <?php if(!empty($dato_validacion_no_aporta_docs[0]->Aporta_documento) && $dato_validacion_no_aporta_docs[0]->Aporta_documento=='No'){ ?> checked <?php } ?>>
                                    </div> 
                                </div>
                                <div class="table-responsive">
                                    <table id="listado_docs_solicitados" class="table table-striped table-bordered" width="100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>Fecha solicitud documento</th>
                                                <th style="width:164.719px !important;">Nombre Documento</th>
                                                <th style="width:200px !important;">Descripción</th>
                                                <th>Solicitada a</th>
                                                <th>Fecha recepción de documentos</th>
                                                <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_fila" <?php if(!empty($dato_validacion_no_aporta_docs[0]->Aporta_documento) && $dato_validacion_no_aporta_docs[0]->Aporta_documento=='No'){ ?> style="display:none" <?php } ?>><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
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
                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila_visual_{{$prueba->Id_Documento_Solicitado}}" data-id_fila_quitar="{{$prueba->Id_Documento_Solicitado}}" data-clase_fila="fila_visual_{{$prueba->Id_Documento_Solicitado}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div><br>
                                <x-adminlte-button class="mr-auto" id="guardar_datos_tabla" theme="info" label="Guardar"/>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Cargar Documentos Estandar -->
                            <div class="col-3 text-center" <?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_califi) && $arrayinfo_controvertido[0]->Termino_contro_califi=='Fuera de términos'){ ?> style="display:none" <?php } ?>>
                                <div class="form-group">
                                    <a href="javascript:void(0);" id="cargue_docs_modal_listado_docs" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-group">
                                    <a href="#" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalGenerarComunicado"><i class="fas fa-file-pdf text-info"></i> <strong>Generar Comunicado</strong></a>
                                </div>
                            </div>
                            <div class="col-3 text-center" <?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_califi) && $arrayinfo_controvertido[0]->Termino_contro_califi=='Fuera de términos'){ ?> style="display:none" <?php } ?>>
                                <div class="form-group">
                                    <a href="#" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalAgregarExpediente"><i class="fas fa-archive text-info"></i> <strong>Crear Expediente</strong></a>
                                </div>
                            </div>
                            <div class="col-3 text-center" <?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_califi) && $arrayinfo_controvertido[0]->Termino_contro_califi=='Fuera de términos'){ ?> style="display:none" <?php } ?>>
                                <div class="form-group">
                                    <a href="#" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalAgregarSeguimiento"><i class="fas fa-folder-open text-info"></i> <strong>Agregar Seguimiento</strong></a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Historial de comunicados y expedientes -->
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Historial de comunicados y expedientes</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="listado_agregar_comunicados" class="table table-striped table-bordered" style="width: 100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>N° Radicado</th>
                                                <th>Elaboro</th>
                                                <th>Fecha Comunicado</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Historial de seguimiento-->
                        <div class="card-info" <?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_califi) && $arrayinfo_controvertido[0]->Termino_contro_califi=='Fuera de términos'){ ?> style="display:none" <?php } ?>>
                            <div class="card-header text-center">
                                <h5>Historial de seguimientos</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="listado_agregar_seguimientos" class="table table-striped table-bordered" style="width: 100%">
                                        <thead>
                                            <tr class="bg-info">
                                                <th>N°</th>
                                                <th>Fecha de seguimiento</th>
                                                <th>Causal de seguimiento</th>
                                                <th>Descripción del seguimiento</th>
                                                <th>Realizado por</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $iterar2 = 0;?>
                                            @foreach ($hitorialAgregarSeguimiento as $listaseguimientos)
                                                <tr>
                                                    <td><?php echo $iterar2 = $iterar2 + 1; ?></td>
                                                    <td>{{$listaseguimientos->F_seguimiento}}</td>
                                                    <td>{{$listaseguimientos->Causal_seguimiento}}</td>
                                                    <td>{{$listaseguimientos->Descripcion_seguimiento}}</td>
                                                    <td>{{$listaseguimientos->Nombre_usuario}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
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
    {{-- Modal Genrear Comunicado --}}
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
                            <form  id="form_generarComunicadoJuntas" method="POST">
                                @csrf 
                                <div class="card-body">
                                    <div class="row"> 
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="cliente_comunicado">Cliente</label>
                                                <input class="form-control" type="text" name="cliente_comunicado" id="cliente_comunicado" value="{{$array_datos_calificacionJuntas[0]->Nombre_Cliente}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="cliente_comunicado2" id="cliente_comunicado2" value="{{$array_datos_calificacionJuntas[0]->Nombre_Cliente}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="nombre_afiliado_comunicado">Nombre del afiliado</label>
                                                <input class="form-control" type="text" name="nombre_afiliado_comunicado" id="nombre_afiliado_comunicado" value="{{$array_datos_calificacionJuntas[0]->Nombre_afiliado}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="nombre_afiliado_comunicado2" id="nombre_afiliado_comunicado2" value="{{$array_datos_calificacionJuntas[0]->Nombre_afiliado}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="tipo_documento_comunicado">Tipo de documento</label>
                                                <input class="form-control" type="text" name="tipo_documento_comunicado" id="tipo_documento_comunicado" value="{{$array_datos_calificacionJuntas[0]->Nombre_tipo_documento}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="tipo_documento_comunicado2" id="tipo_documento_comunicado2" value="{{$array_datos_calificacionJuntas[0]->Nombre_tipo_documento}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="identificacion_comunicado">N° de identificación</label>
                                                <input class="form-control" type="text" name="identificacion_comunicado" id="identificacion_comunicado" value="{{$array_datos_calificacionJuntas[0]->Nro_identificacion}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="identificacion_comunicado2" id="identificacion_comunicado2" value="{{$array_datos_calificacionJuntas[0]->Nro_identificacion}}">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="id_evento_comunicado">ID evento</label>
                                                <input class="form-control" type="text" name="id_evento_comunicado" id="id_evento_comunicado" value="{{$array_datos_calificacionJuntas[0]->ID_evento}}" disabled>
                                                <input hidden="hidden" class="form-control" type="text" name="id_evento_comunicado2" id="id_evento_comunicado2" value="{{$array_datos_calificacionJuntas[0]->ID_evento}}">
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
                                            <label for="empresa_comunicado"><strong>Empresa</strong></label>
                                            <input class="scalesR" type="radio" name="afiliado_comunicado" id="empresa_comunicado" value="Empresa" style="margin-left: revert;" required>
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
                                                <input class="form-control" type="number" min="999999" max="9999999999" name="telefono_destinatario" id="telefono_destinatario" required>
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
                                                <label for="cuerpo_comunicado">Cuerpo del comunicado <span style="color: red;">(*)</span></label>
                                                <textarea class="form-control" name="cuerpo_comunicado" id="cuerpo_comunicado" cols="30" rows="5" style="resize:none;" required></textarea>
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
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="copia_jrci" name="copia_jrci" value="JRCI">
                                                        <label for="copia_jrci" class="custom-control-label">Junta Regional de Calificación de Invalidez</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="copia_jnci" name="copia_jnci" value="JNCI">
                                                        <label for="copia_jnci" class="custom-control-label">Junta Nacional de Calificación de Invalidez</label>                 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="ciudad_comunicado">Ciudad <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="text" name="ciudad_comunicado" id="ciudad" value="Bogotá D.C" required>
                                                <input hidden="hidden" type="text" class="form-control" name="Id_evento" id="Id_evento" value="{{$array_datos_calificacionJuntas[0]->ID_evento}}">
                                                    <input hidden="hidden" type="text" class="form-control" name="Id_asignacion" id="Id_asignacion" value="{{$array_datos_calificacionJuntas[0]->Id_Asignacion}}">
                                                    <input hidden="hidden" type="text" class="form-control" name="Id_procesos" id="Id_procesos" value="{{$array_datos_calificacionJuntas[0]->Id_proceso}}">
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
                                    </div>
                                    <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de cambiar el destinatario
                                        (Afiliado y Empresa) debe seleccionar nuevamente la Forma de envio y Revisó y en (Otro) todos.
                                    </div>                              
                                    <div class="row text-center">                                  
                                        <label for="destinatario_principal_act" style="margin-left: 7px;">Destinatario Principal: <span style="color: red;">(*)</span></label>                                        
                                        <div class="col-3">
                                            <label for="afiliado_comunicado_act"><strong>Afiliado</strong></label>
                                            <input class="scalesR" type="radio" name="afiliado_comunicado_act" id="afiliado_comunicado_editar" value="Afiliado" style="margin-left: revert;" required>
                                        </div>
                                        <div class="col-3">
                                            <label for="empresa_comunicado"><strong>Empresa</strong></label>
                                            <input class="scalesR" type="radio" name="afiliado_comunicado_act" id="empresa_comunicado_editar" value="Empresa" style="margin-left: revert;" required>
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
                                                <input class="form-control" type="number" min="999999" max="9999999999" name="telefono_destinatario_act" id="telefono_destinatario_editar" required>
                                                <input hidden="hidden" class="form-control" type="number" min="999999" max="9999999999" name="telefono_destinatario_act2" id="telefono_destinatario_editar2" required>
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
                                            <div class="form-group">
                                                <label for="cuerpo_comunicado_act">Cuerpo del comunicado <span style="color: red;">(*)</span></label>
                                                <textarea class="form-control" name="cuerpo_comunicado_act" id="cuerpo_comunicado_editar" cols="30" rows="5" style="resize:none;" required></textarea>
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
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="edit_copia_jrci" name="edit_copia_jrci" value="JRCI">
                                                        <label for="edit_copia_jrci" class="custom-control-label">Junta Regional de Calificación de Invalidez</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="edit_copia_jnci" name="edit_copia_jnci" value="JNCI">
                                                        <label for="edit_copia_jnci" class="custom-control-label">Junta Nacional de Calificación de Invalidez</label>                 
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
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="button" id="Editar_comunicados" class="btn btn-info" value="Actualizar">
                                                <input type="submit" id="Pdf" class="btn btn-info" value="Pdf">                            
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_editar_comunicado alert alert-success mt-2 mr-auto d-none" role="alert"></div>
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
                            <form id="form_agregar_seguimientoJuntas" method="POST">
                                @csrf
                                <div class="card-body">                                
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="fecha_seguimiento">Fecha Seguimiento <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="date" name="fecha_seguimiento" id="fecha_seguimiento" value="{{now()->format('Y-m-d')}}" readonly>
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
    <?php $aperturaModal = 'Edicion'; ?>
    @include('//.administrador.modalcarguedocumentos')
@stop
@section('js')
    <script type="text/javascript" src="/js/calificacionJuntas.js"></script>
    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
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
    {{-- SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE LISTADOS DE DOCUMENTOS SOLICITADOS --}}
    <script type="text/javascript">
        $(document).ready(function(){
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

                contador = contador + 1;
                var nueva_fila = [
                    '<?php echo date("Y-m-d");?> <input type="hidden" id="fecha_solicitud_fila_'+contador+'" name="fecha_solicitud" value="{{date("Y-m-d")}}" />',
                    '<textarea id="documento_soli_fila_'+contador+'" class="form-control soloPrimeraLetraMayus" name="documento_soli" cols="90" rows="1"></textarea>',
                    '<textarea id="descripcion_fila_'+contador+'" class="form-control " name="descripcion" cols="90" rows="2"></textarea>',
                    '<select id="lista_solicitante_fila_'+contador+'" class="custom-select lista_solicitante_fila_'+contador+'" name="solicitante"><option></option></select><div id="contenedor_otro_solicitante_fila_'+contador+'" class="mt-1"></div>',
                    '<input type="date" class="form-control" id="fecha_recepcion_fila_'+contador+'" name="fecha_recepcion" max="{{date("Y-m-d")}}"/>',
                    '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila" class="text-info" data-fila="fila_'+contador+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                    'fila_'+contador
                ];

                var agregar_fila = listado_docs_solicitados.row.add(nueva_fila).draw().node();
                $(agregar_fila).addClass('fila_'+contador);
                $(agregar_fila).attr("id", 'fila_'+contador);

                // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionJuntas.js)
                funciones_elementos_fila(contador);
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
            sessionStorage.removeItem("scrollTopControJuntas");

        });
    </script>
@stop