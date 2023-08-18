@extends('adminlte::page')
@section('title', 'Calificación PCL')
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
                <a href="{{route("bandejaPCL")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <button id="Hacciones" class="btn btn-info"  onclick="historialDeAcciones()"><i class="fas fa-list"></i>Historial Acciones</button>
                <p>
                    <!--<i class="far fa-eye text-success"></i> Activar Menú/Sub Menú &nbsp;
                    <i class="far fa-eye-slash text-danger"></i> Inactivar Menú/Sub Menú &nbsp;-->
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                    <!--<i class="fa fa-puzzle-piece text-info"></i> Agregar Nuevo Servicio&nbsp;-->
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Calificación PCL - Evento: {{$array_datos_calificacionPcl[0]->ID_evento}}</h4>
        </div>
        <form action="{{ route('registrarCalificacionPCL') }}" method="POST">
            @csrf
            <div class="card-body">                
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div id="aumentarColAfiliado" class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información del afiliado</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="cliente">Cliente</label>
                                                    <input type="text" class="form-control" name="cliente" id="cliente" value="{{$array_datos_calificacionPcl[0]->Nombre_Cliente}}" disabled>
                                                    <input hidden="hidden" type="text" class="form-control" name="newId_evento" id="newId_evento" value="{{$array_datos_calificacionPcl[0]->ID_evento}}">
                                                    <input hidden="hidden" type="text" class="form-control" name="newId_asignacion" id="newId_asignacion" value="{{$array_datos_calificacionPcl[0]->Id_Asignacion}}">
                                                    <input hidden="hidden" type="text" class="form-control" name="Id_proceso" id="Id_proceso" value="{{$array_datos_calificacionPcl[0]->Id_proceso}}">
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
                                                    <input type="text" class="form-control" name="identificacion" id="identificacion" value="{{$array_datos_calificacionPcl[0]->Nro_identificacion}}" disabled>
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
                                                    <input type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_calificacionPcl[0]->ID_evento}}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="divHistorialAcciones" class="">
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
                        <div class="row">
                            <div id="aumentarColActividad" class="col-12">
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
                                                    <a onclick="document.getElementById('botonFormulario2').click();" style="cursor:pointer;"><i class="fa fa-puzzle-piece text-info"></i> <strong class="text-dark">{{$array_datos_calificacionPcl[0]->Nombre_servicio}}</strong></a>
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
                                                    <input type="text" class="form-control" name="fecha_asignacion_calificacion" id="fecha_asignacion_calificacion" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="profesional_calificador">Profesional Calificador</label>
                                                    <input type="text" class="form-control" name="profesional_calificador" id="profesional_calificador" value="{{$array_datos_calificacionPcl[0]->Nombre_profesional}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="tipo_profesional_calificador">Tipo Profesional calificador</label>
                                                    <input type="text" class="form-control" name="tipo_profesional_calificador" id="tipo_profesional_calificador" value="{{$array_datos_calificacionPcl[0]->Tipo_Profesional_calificador}}" disabled>
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
                                                    <label for="modalidad_calificacion">Modalidad Calificación <span style="color: red;">(*)</span></label>
                                                    <select class="custom-select" name="modalidad_calificacion" id="modalidad_calificacion" required>
                                                        @if ($array_datos_calificacionPcl[0]->Modalidad_calificacion > 0)
                                                            <option value="{{$array_datos_calificacionPcl[0]->Modalidad_calificacion}}" selected>{{$array_datos_calificacionPcl[0]->Nombre_Modalidad_calificacion}}</option>
                                                        @else
                                                            <option value="">Seleccione una opción</option>
                                                        @endif
                                                    </select>
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
                                                    <input type="text" class="form-control" name="tiempo_gestion" id="tiempo_gestion" value="{{$array_datos_calificacionPcl[0]->Tiempo_de_gestion}}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">                                                  
                                                    <a href="#" id="clicGuardado" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modalSolicitudDocSeguimiento"><i class="fas fa-book-open text-info"></i> <strong>Solicitud documentos - Seguimientos</strong></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="aumentarColAccionRealizar" class="col-12">
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
                                                    <input hidden="hidden" type="date" class="form-control" name="f_accion" id="_accion" value="{{now()->format('Y-m-d')}}">
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
                                                    <input type="date" class="form-control" name="fecha_alerta" id="fecha_alerta" min="{{now()->format('Y-m-d')}}" value="{{$array_datos_calificacionPcl[0]->F_alerta}}">
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
                                                    <textarea class="form-control" name="descripcion_accion" id="descripcion_accion" cols="30" rows="5" style="resize: none;">{{$array_datos_calificacionPcl[0]->Descripcion_accion}}</textarea>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="grupo_botones" style="float: left;">
                    <input type="reset" id="Borrar" class="btn btn-info" value="Restablecer">
                    @if (empty($array_datos_calificacionPcl[0]->Nombre_Modalidad_calificacion))
                        <input type="submit" id="Edicion" class="btn btn-info" value="Guardar" onclick="OcultarbotonGuardar()">
                        <input type="hidden" name="bandera_accion_guardar_actualizar" value="Guardar">
                    @else 
                        <input type="submit" id="Edicion" class="btn btn-info" value="Actualizar" onclick="OcultarbotonGuardar()">
                        <input type="hidden" name="bandera_accion_guardar_actualizar" value="Actualizar">
                    @endif                    
                </div>
                <div class="text-center" id="mostrar-barra2"  style="display:none;">                                
                    <button class="btn btn-info" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Guardando/Actualizando Calificación PCl...
                    </button>
                </div>
            </div>           
        </form>     
        
        <form action="{{route($SubModulo)}}" id="formulario2" method="POST">            
            @csrf
            <input hidden="hidden" type="text" name="Id_evento_calitec" id="Id_evento_calitec" value="{{$array_datos_calificacionPcl[0]->ID_evento}}">
            <input hidden="hidden" type="text" name="Id_asignacion_calitec" id="Id_asignacion_calitec" value="{{$array_datos_calificacionPcl[0]->Id_Asignacion}}">
            <button type="submit" id="botonFormulario2"></button>
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
                                                <th>Fecha recepción de documento</th>
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
                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila_visual_{{$prueba->Id_Documento_Solicitado}}" data-id_fila_quitar="{{$prueba->Id_Documento_Solicitado}}" data-clase_fila="fila_visual_{{$prueba->Id_Documento_Solicitado}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div><br>
                                <x-adminlte-button class="mr-auto" id="guardar_datos_tabla" theme="info" label="Guardar"/>
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
                                            <a href="#" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalAgregarSeguimiento"><i class="fas fa-folder-open text-info"></i> <strong>Agregar Seguimiento</strong></a>
                                        </div>
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
                                                <input class="form-control" type="date" name="fecha_seguimiento" id="fecha_seguimiento" value="{{now()->format('Y-m-d')}}" required>
                                            </div> 
                                        </div>                                    
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="causal_seguimiento">Causal de seguimiento <span style="color: red;">(*)</span></label><br>
                                                <select class="causal_seguimiento custom-select" name="causal_seguimiento" id="causal_seguimiento" required>
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
                                                <div class="alerta_seguimiento alert alert-success mt-2 mr-auto d-none" role="alert"></div>
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
                            <form>                                
                                <div class="card-body">                                
                                    <div class="row">  
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="ciudad_comunicado">Ciudad <span style="color: red;">(*)</span></label>
                                                <input class="form-control" type="text" name="ciudad_comunicado" id="ciudad" value="Bogotá D.C" required>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="fecha_comunicado">Fecha</label>
                                                <input class="form-control" type="date" name="fecha_comunicado" id="fecha_comunicado" value="{{now()->format('Y-m-d')}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="radicado">N° Radicado</label>
                                                <input class="form-control" type="text" name="radicado" id="radicado" disabled>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="cliente_comunicado">Cliente</label>
                                                <input class="form-control" type="text" name="cliente_comunicado" id="cliente_comunicado" value="{{$array_datos_calificacionPcl[0]->Nombre_Cliente}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="nombre_afiliado_comunicado">Nombre del afiliado</label>
                                                <input class="form-control" type="text" name="nombre_afiliado_comunicado" id="nombre_afiliado_comunicado" value="{{$array_datos_calificacionPcl[0]->Nombre_afiliado}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="tipo_documento_comunicado">Tipo de documento</label>
                                                <input class="form-control" type="text" name="tipo_documento_comunicado" id="tipo_documento_comunicado" value="{{$array_datos_calificacionPcl[0]->Nombre_tipo_documento}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="identificacion_comunicado">N° de identificación</label>
                                                <input class="form-control" type="text" name="identificacion_comunicado" id="identificacion_comunicado" value="{{$array_datos_calificacionPcl[0]->Nro_identificacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="id_evento_comunicado">ID evento</label>
                                                <input class="form-control" type="text" name="id_evento_comunicado" id="id_evento_comunicado" value="{{$array_datos_calificacionPcl[0]->ID_evento}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <label for="destinatario_principal" style="margin-left: 7px;">Destinatario Principal: <span style="color: red;">(*)</span></label>
                                        
                                            <div class="col-3">
                                                <label for="afiliado_comunicado"><strong>Afiliado</strong></label>
                                                <input class="scales" type="radio" name="afiliado_comunicado" id="afiliado_comunicado" value="Afiliado" style="margin-left: revert;" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="empresa_comunicado"><strong>Empresa</strong></label>
                                                <input class="scales" type="radio" name="afiliado_comunicado" id="empresa_comunicado" value="Empresa" style="margin-left: revert;" required>
                                            </div>
                                            <div class="col-3">
                                                <label for="Otro"><strong>Otro</strong></label>
                                                <input class="scales" type="radio" name="afiliado_comunicado" id="Otro" value="Otro" style="margin-left: revert;" required>
                                            </div>
                                        <?php    
                                        ?>
                                    </div>     
                                    {{-- <div id="destinatarioPrincipal">
                                    </div>
                                    <div id="datos"></div> --}}
                                    {{-- @foreach ($array_datos_destinatarios as $item)
                                        <div>{{$item->Nombre_afiliado}}</div>
                                        <div>{{$item->Nombre_afiliado}}</div>                                        
                                    @endforeach   --}}                                                                                                                       
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
                                                    <input class="form-control" type="number" min="7" max="10" name="telefono_destinatario" id="telefono_destinatario" required>
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
                                        <div class="col-3">
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
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="reviso">Revisó</label><br>
                                                <select class="reviso custom-select" name="reviso" id="reviso" style="width: 100%;" required>                                                    
                                                    <option value="">Seleccione una opción</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group" id="contenedorCopia">
                                                <label for="agregar_copia">Agregar copia</label>
                                                <input class="form-control" type="text" name="agregar_copia" id="agregar_copia"><br>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group" style="padding-top: 31px;"> 
                                                <button class="btn btn-info" type="button" onclick="duplicate()">Duplicar</button>
                                            </div>
                                        </div>
                                    </div>                                                                        
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                {{-- <a href="#" class="text-dark text-md" download><i class="fas fa-print text-info"></i> <strong>Ver comunicado</strong></a> --}}
                                                <input type="submit" id="Generar_comunicados" class="btn btn-info" value="Guardar">
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


    {{-- Modal cargue documentos --}}
    <?php $aperturaModal = 'Edicion'; ?>
    @include('//.administrador.modalcarguedocumentos')
    
@stop
@section('js')
    <script>
        //funcion para habilitar el historial de acciones
        function historialDeAcciones() {
            var div = document.getElementById("historialAcciones");
            
            if (div.style.width === "0px") {
                div.style.width = "auto";
                $('#aumentarColAfiliado').removeClass('col-12');
                $('#aumentarColAfiliado').addClass('col-6');
                $('#divHistorialAcciones').addClass('col-6')
                $('#historialAcciones').removeClass('d-none')
                $('#aumentarColActividad').removeClass('col-12');
                $('#aumentarColActividad').addClass('col-6');                
                $('#aumentarColAccionRealizar').removeClass('col-12');
                $('#aumentarColAccionRealizar').addClass('col-6');
            } else {
                div.style.width = "0px";
                $('#aumentarColAfiliado').removeClass('col-6');
                $('#aumentarColAfiliado').addClass('col-12');
                $('#divHistorialAcciones').removeClass('col-6')
                $('#historialAcciones').addClass('d-none');
                $('#aumentarColActividad').removeClass('col-6');
                $('#aumentarColActividad').addClass('col-12');                
                $('#aumentarColAccionRealizar').removeClass('col-6');
                $('#aumentarColAccionRealizar').addClass('col-12');
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
            $('#mostrar-barra2').css("display","block");
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
            
            $(document).on('click', '#btn_remover_fila', function(){
                var nombre_fila = $(this).data("fila");
                listado_docs_solicitados.row("."+nombre_fila).remove().draw();
            });

            $(document).on('click', "a[id^='btn_remover_fila_visual_']", function(){
                var nombre_fila = $(this).data("clase_fila");
                listado_docs_solicitados.row("."+nombre_fila).remove().draw();
            });
            //Elimina sessionStorage
            sessionStorage.removeItem("scrollTop");
        });
    </script>
    
    <script type="text/javascript" src="/js/calificacionpcl.js"></script>
    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
@stop