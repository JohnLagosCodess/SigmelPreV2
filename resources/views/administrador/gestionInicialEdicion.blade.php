@extends('adminlte::page')
@section('title', 'Edición Evento')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-2">
            <?php if (isset($_POST['badera_buscador_evento']) &&  $_POST['badera_buscador_evento'] == 'desdebuscador' ):?>
                <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <?php elseif (isset($_POST['bandera_buscador_dto_atel']) &&  $_POST['bandera_buscador_dto_atel'] == 'desdedtoatel' ): ?>
                <a onclick="document.getElementById('btn_regreso_dto_atel').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("determinacionOrigenATEL")}}" id="Regreso_DTO_ATEL" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="Id_evento_calitec" id="Id_evento_calitec" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="Id_asignacion_calitec" id="Id_asignacion_calitec" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_calitec" id="Id_proceso_calitec" value="<?php echo $_POST['newIdproceso'];?>">
                    <button type="submit" id="btn_regreso_dto_atel" style="display: none; !important"></button>
                </form>
            <?php elseif (isset($_POST['bandera_buscador_adicion_dx']) &&  $_POST['bandera_buscador_adicion_dx'] == 'desdeadiciondx' ): ?>
                <a onclick="document.getElementById('btn_regreso_adicion_dx').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("adicionDxDtoOrigen")}}" id="Regreso_Adicion_Dx" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="Id_evento_calitec" id="Id_evento_calitec" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="Id_asignacion_calitec" id="Id_asignacion_calitec" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_calitec" id="Id_proceso_calitec" value="<?php echo $_POST['newIdproceso'];?>">
                    <button type="submit" id="btn_regreso_adicion_dx" style="display: none; !important"></button>
                </form>
            <?php elseif (isset($_POST['bandera_buscador_clt']) &&  $_POST['bandera_buscador_clt'] == 'desdeclt' ): ?>
                <a onclick="document.getElementById('btn_regreso_clt').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("CalficacionTecnicaPCL")}}" id="Regreso_Clt" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="Id_evento_calitec" id="Id_evento_calitec" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="Id_asignacion_calitec" id="Id_asignacion_calitec" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_calitec" id="Id_proceso_calitec" value="<?php echo $_POST['newIdproceso'];?>">
                    <input hidden="hidden" type="text" name="Id_servicio_calitec" id="Id_servicio_calitec" value="<?php echo $_POST['newIdservicio'];?>">
                    <button type="submit" id="btn_regreso_clt" style="display: none; !important"></button>
                </form>
            <?php elseif (isset($_POST['bandera_buscador_rec']) &&  $_POST['bandera_buscador_rec'] == 'desderec' ): ?>
                <a onclick="document.getElementById('btn_regreso_rec').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("recalificacionPCL")}}" id="Regreso_Rec" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="Id_evento_recali" id="Id_evento_recali" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="Id_asignacion_recali" id="Id_asignacion_recali" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_recali" id="Id_proceso_recali" value="<?php echo $_POST['newIdproceso'];?>">
                    <input hidden="hidden" type="text" name="Id_servicio_recali" id="Id_servicio_recali" value="<?php echo $_POST['newIdservicio'];?>">
                    <button type="submit" id="btn_regreso_rec" style="display: none; !important"></button>
                </form>
            <?php elseif (isset($_POST['bandera_buscador_pro']) &&  $_POST['bandera_buscador_pro'] == 'desdepro' ): ?>
                <a onclick="document.getElementById('btn_regreso_pro').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("pronunciamientoPCL")}}" id="Regreso_Pro" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="Id_evento_pcl" id="Id_evento_pcl" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="Id_asignacion_pcl" id="Id_asignacion_pcl" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_pronun" id="Id_proceso_pronun" value="<?php echo $_POST['newIdproceso'];?>">
                    <input hidden="hidden" type="text" name="Id_servicio_pronun" id="Id_servicio_pronun" value="<?php echo $_POST['newIdservicio'];?>">
                    <button type="submit" id="btn_regreso_pro" style="display: none; !important"></button>
                </form>
            <?php elseif (isset($_POST['bandera_buscador_clpcl']) &&  $_POST['bandera_buscador_clpcl'] == 'desdeclpcl' ): ?>
                <a onclick="document.getElementById('btn_regreso_clpcl').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("calificacionPCL")}}" id="Regreso_ClPcl" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="Id_evento_pcl" id="Id_evento_pcl" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="Id_asignacion_pcl" id="Id_asignacion_pcl" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_pcl" id="Id_proceso_pcl" value="<?php echo $_POST['newIdproceso'];?>">
                    <input hidden="hidden" type="text" name="Id_servicio_pcl" id="Id_servicio_pcl" value="<?php echo $_POST['newIdservicio'];?>">
                    <button type="submit" id="btn_regreso_clpcl" style="display: none; !important"></button>
                </form>    
            <?php elseif (isset($_POST['bandera_buscador_proori']) &&  $_POST['bandera_buscador_proori'] == 'desdeproori' ): ?>
                <a onclick="document.getElementById('btn_regreso_proori').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("pronunciamientoOrigen")}}" id="Regreso_ProOri" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="Id_evento_calitec" id="Id_evento_calitec" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="Id_asignacion_calitec" id="Id_asignacion_calitec" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_pcl" id="Id_proceso_pcl" value="<?php echo $_POST['newIdproceso'];?>">
                    <input hidden="hidden" type="text" name="Id_servicio_pcl" id="Id_servicio_pcl" value="<?php echo $_POST['newIdservicio'];?>">
                    <button type="submit" id="btn_regreso_proori" style="display: none; !important"></button>
                </form>         
            <?php elseif (isset($_POST['bandera_buscador_calori']) &&  $_POST['bandera_buscador_calori'] == 'desdecalori' ): ?>
                <a onclick="document.getElementById('btn_regreso_calori').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("calificacionOrigen")}}" id="Regreso_ClOri" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_pcl" id="Id_proceso_pcl" value="<?php echo $_POST['newIdproceso'];?>">
                    {{-- <input hidden="hidden" type="text" name="Id_servicio_pcl" id="Id_servicio_pcl" value="<?php echo $_POST['newIdservicio'];?>"> --}}
                    <input hidden="hidden" type="text" name="Id_Servicio" id="Id_Servicio" value="<?php echo $_POST['newIdservicio'];?>">
                    <button type="submit" id="btn_regreso_calori" style="display: none; !important"></button>
                </form> 
            <?php elseif (isset($_POST['bandera_buscador_juntas']) &&  $_POST['bandera_buscador_juntas'] == 'desdejuntas' ): ?>
                <a onclick="document.getElementById('btn_regreso_juntas').click();"   class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <form action="{{route("calificacionJuntas")}}" id="Regreso_Juntas" method="POST">            
                    @csrf
                    <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php echo $_POST['newIdEvento'];?>">
                    <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php echo $_POST['newIdAsignacion'];?>">
                    <input hidden="hidden" type="text" name="Id_proceso_pcl" id="Id_proceso_pcl" value="<?php echo $_POST['newIdproceso'];?>">
                    <input hidden="hidden" type="text" name="Id_Servicio" id="Id_Servicio" value="<?php echo $_POST['newIdservicio'];?>">
                    <button type="submit" id="btn_regreso_juntas" style="display: none; !important"></button>
                </form>
            <?php elseif(isset($_POST['bandera_mod_nuevo']) && $_POST['bandera_mod_nuevo'] == "retornar_mod_nuevo"): ?>
                <a href="{{route("gestionInicialNuevo")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <?php elseif(isset($_POST['regresar_anterior']) && $_POST['regresar_anterior'] == "regresar_anterior"): ?>
                <button class="btn btn-success" onClick="history.go(-1);" value="Recargar bandeja"><i class="fa fa-arrow-left"></i> Regresar</a></button>           
            <?php else: ?>
                <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>           
            <?php endif ?>
        </div>
        <div class="col-6 d-flex" style="text-align: left !important; margin-left: -55px;">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#modalHistorialAcciones" class="btn btn-info" id="cargar_historial_acciones"><i class="fas fa-list"></i> Historial Acciones</a>
            <button label="Open Modal" data-toggle="modal" data-target="#historial_servicios" class="btn btn-info ml-1"><i class="fas fa-project-diagram mt-1"></i>Historial de servicios</button>
        </div>
    </div>
    {{-- MODAL HISTORIAL DE ACCIONES --}}
    <x-adminlte-modal id="modalHistorialAcciones" title="Historial de acciones - Evento: {{$array_datos_info_evento[0]->ID_evento}}" theme="info" icon="fas fa-list" size='xl' disable-animations>
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
        <x-slot name="footerSlot">
            <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>

    <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
    <div class="col-12">
        <?php if(isset($evento_actualizado) && !empty($evento_actualizado)):?>
            <div class="alert alert-success mt-2" role="alert">
                <strong>{{$evento_actualizado}}</strong>
            </div>
        <?php endif?>

    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Edición de Evento: {{$array_datos_info_evento[0]->ID_evento}}</h4>
            <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
        </div>
        <form action="{{route('actualizarEvento')}}" method="POST" id="gestion_inicial">
            @csrf
            <div class="card-body">
                @if (session()->get('evento_actualizado'))
                    <div class="alert alert-success mt-2" role="alert">
                        <strong>{{session()->get('evento_actualizado')}}</strong>
                    </div>
                @endif
                @if (session()->get('confirmacion_evento_no_creado'))
                    <div class="alert alert-danger mt-2" role="alert">
                        <strong>{{session()->get('confirmacion_evento_no_creado')}}</strong>
                    </div>
                @endif
                <div class="row">
                    {{-- AQUI VA EL FORMULARIO COMPLETO --}}
                    <div class="col-12">
                        {{-- CLIENTE Y TIPO DE CLIENTE --}}
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="cliente" class="col-form-label">Cliente <span style="color:red;">(*)</span></label>
                                    <input type="hidden" name="cliente" id="cliente" value="{{$array_datos_info_evento[0]->Cliente}}">
                                    <input type="text" class="form-control" id="mostrar_nombre_cliente" value="{{$array_datos_info_evento[0]->Nombre_cliente}}" readonly required>
                                    {{-- <select class="cliente custom-select" name="cliente" id="cliente" required="true">
                                        <option value="{{$array_datos_info_evento[0]->Cliente}}" selected>{{$array_datos_info_evento[0]->Nombre_cliente}}</option>
                                    </select> --}}
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="tipo_cliente" class="col-form-label">Tipo de Cliente <span style="color:red;">(*)</span></label>
                                    <input type="hidden" name="tipo_cliente" id="tipo_cliente" value="{{$array_datos_info_evento[0]->Tipo_cliente}}" required>
                                    <input type="text" class="form-control" id="mostrar_nombre_tipo_cliente" value="{{$array_datos_info_evento[0]->Nombre_tipo_cliente}}" readonly>
                                    {{-- <select class="tipo_cliente custom-select" name="tipo_cliente" id="tipo_cliente" required>
                                        <option value="{{$array_datos_info_evento[0]->Tipo_cliente}}" selected>{{$array_datos_info_evento[0]->Nombre_tipo_cliente}}</option>
                                    </select> --}}
                                </div>
                            </div>
                            <div class="col-sm columna_otro_tipo_cliente">
                                <div class="form-group">
                                    <label for="otro_tipo_cliente" class="col-form-label">Otro Tipo Cliente <span style="color:red;">(*)</span></label>
                                    <input class="otro_tipo_cliente form-control" name="otro_tipo_cliente" id="otro_tipo_cliente">
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN DEL EVENTO --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información del evento</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="tipo_evento" class="col-form-label">Tipo de evento <!-- <span style="color:red;">(*)</span> --></label>
                                                    <select class="tipo_evento custom-select" name="tipo_evento" id="tipo_evento">
                                                        <option value="{{$array_datos_info_evento[0]->Tipo_evento}}" selected>{{$array_datos_info_evento[0]->Nombre_evento}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="id_evento" class="col-form-label">ID evento <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="id_evento form-control" name="id_evento" id="id_evento" value="{{$array_datos_info_evento[0]->ID_evento}}" disabled>
                                                    <input type="hidden" name="id_evento_enviar" value="{{$array_datos_info_evento[0]->ID_evento}}">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="fecha_evento" class="col-form-label">Fecha de evento <!-- <span style="color:red;">(*)</span> --></label>
                                                    <input type="date" class="fecha_evento form-control" name="fecha_evento" id="fecha_evento" value="{{$array_datos_info_evento[0]->F_evento}}"  max="{{date("Y-m-d")}}">
                                                    <span class="d-none" id="fecha_evento_alerta" style="color: red; font-style: italic;"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="fecha_radicacion" class="col-form-label">Fecha de radicación <span style="color:red;">(*)</span></label>
                                                    <input type="date" class="fecha_radicacion form-control" name="fecha_radicacion" id="fecha_radicacion" value="{{$array_datos_info_evento[0]->F_radicacion}}" max="{{date("Y-m-d")}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="n_siniestro" class="col-form-label">N° de Siniestro </label>
                                                    <input type="text" class="n_siniestro form-control" name="n_siniestro" id="n_siniestro" maxlength="25" value="{{$array_datos_info_evento[0]->N_siniestro}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN DEL AFILIADO --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información del Afiliado / Beneficiario</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="tipo_afiliado" class="col-form-label">Tipo de afiliado<span style="color:red;">(*)</span></label>
                                                    <select class="tipo_afiliado custom-select" name="tipo_afiliado" id="tipo_afiliado" required>
                                                        <option value="{{$array_datos_info_afiliados[0]->Tipo_afiliado}}">{{$array_datos_info_afiliados[0]->Nombre_tipo_afiliado}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm columna_otro_tipo_afiliado d-none">
                                                <div class="form-group">
                                                    <label for="otro_tipo_afiliado" class="col-form-label">Otro Tipo de Afiliado</label>
                                                    <input class="otro_tipo_afiliado form-control" name="otro_tipo_afiliado" id="otro_tipo_afiliado">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nro_identificacion" class="col-form-label">N° de identificación <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="nro_identificacion form-control" name="nro_identificacion" id="nro_identificacion" value="{{$array_datos_info_afiliados[0]->Nro_identificacion}}" disabled>
                                                    <input type="hidden" name="nro_identificacion_enviar" value="{{$array_datos_info_afiliados[0]->Nro_identificacion}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="tipo_documento" class="col-form-label">Tipo de documento <span style="color:red;">(*)</span></label>
                                                    <select class="tipo_documento custom-select" name="tipo_documento" id="tipo_documento" required>
                                                        <option value="{{$array_datos_info_afiliados[0]->Tipo_documento}}" selected>{{$array_datos_info_afiliados[0]->Nombre_documento}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm otro_documento d-none">
                                                <div class="form-group">
                                                    <label for="otro_nombre_documento" class="col-form-label" style="color:;">Otro Documento</label>
                                                    <input type="text" class="otro_nombre_documento form-control" name="otro_nombre_documento" id="otro_nombre_documento">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nombre_afiliado" class="col-form-label nom_afiliado">Nombre de afiliado <span style="color:red;">(*)</span></label>
                                                    <label for="nombre_afiliado" class="col-form-label nom_beneficiario d-none">Nombre del Beneficiario <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="nombre_afiliado form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_info_afiliados[0]->Nombre_afiliado}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="direccion_info_afiliado" class="col-form-label">Dirección <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="direccion_info_afiliado form-control" name="direccion_info_afiliado" id="direccion_info_afiliado" value="{{$array_datos_info_afiliados[0]->Direccion}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="no_creacion_evento h4 text-danger d-none">HAY EVENTO.</p>
                                        <div class="ocultar_seccion_info_afiliado">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="fecha_nacimiento" class="col-form-label">Fecha de nacimiento <span style="color:red;">(*)</span></label>
                                                        <input type="date" class="fecha_nacimiento form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="{{$array_datos_info_afiliados[0]->F_nacimiento}}" max="{{date("Y-m-d")}}" required>
                                                        <span class="d-none" id="fecha_nacimiento_alerta" style="color: red; font-style: italic;"></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="edad" class="col-form-label">Edad</label>
                                                        <input type="number" class="edad form-control" name="edad" id="edad" value="{{$array_datos_info_afiliados[0]->Edad}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="genero" class="col-form-label">Género</label>
                                                        <select class="genero custom-select" name="genero" id="genero">
                                                            <option value="{{$array_datos_info_afiliados[0]->Genero}}" selected>{{$array_datos_info_afiliados[0]->Nombre_genero}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="email_info_afiliado" class="col-form-label">Email <span style="color:red;">(*)</span> </label>
                                                        {{-- <input type="email" class="email_info_afiliado form-control" name="email_info_afiliado" id="email_info_afiliado" value="{{$array_datos_info_afiliados[0]->Email}}"> --}}
                                                        <input type="email" class="email_info_afiliado form-control" list="opciones_email" id="email_info_afiliado" name="email_info_afiliado" placeholder="Selecciona o escribe..." value="{{$array_datos_info_afiliados[0]->Email}}" required>
                                                        <datalist id="opciones_email">
                                                            <option value="sin@correo.com">
                                                        </datalist><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="telefono" class="col-form-label">Teléfono/Celular <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="telefono form-control" name="telefono" id="telefono" value="{{$array_datos_info_afiliados[0]->Telefono_contacto}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="estado_civil" class="col-form-label">Estado civil</label>
                                                        <select class="estado_civil custom-select" name="estado_civil" id="estado_civil">
                                                            <option value="{{$array_datos_info_afiliados[0]->Estado_civil}}" selected>{{$array_datos_info_afiliados[0]->Nombre_estado_civil}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_estado_civil d-none">
                                                    <div class="form-group">
                                                        <label for="otro_estado_civil" class="col-form-label">Otro Estado civil</label>
                                                        <input class="otro_estado_civil form-control" name="otro_estado_civil" id="otro_estado_civil">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="nivel_escolar" class="col-form-label">Nivel escolar</label>
                                                        <select class="nivel_escolar custom-select" name="nivel_escolar" id="nivel_escolar">
                                                            <option value="{{$array_datos_info_afiliados[0]->Nivel_escolar}}">{{$array_datos_info_afiliados[0]->Nombre_nivel_escolar}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_nivel_escolar d-none">
                                                    <div class="form-group">
                                                        <label for="otro_nivel_escolar" class="col-form-label">Otro Nivel escolar</label>
                                                        <input class="otro_nivel_escolar form-control" name="otro_nivel_escolar" id="otro_nivel_escolar">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="dominancia" class="col-form-label">Dominancia</label>
                                                        <select class="dominancia custom-select" name="dominancia" id="dominancia">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_dominancia}}" selected>{{$array_datos_info_afiliados[0]->Dominancia}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="departamento_info_afiliado" class="col-form-label">Departamento<span style="color:red;">(*)</span></label>
                                                        <select class="departamento_info_afiliado custom-select" name="departamento_info_afiliado" id="departamento_info_afiliado" required>
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_departamento}}" selected>{{$array_datos_info_afiliados[0]->Nombre_departamento}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_municipio_info_afiliado">
                                                    <div class="form-group">
                                                        <label for="municipio_info_afiliado" class="col-form-label">Ciudad<span style="color:red;">(*)</span></label>
                                                        <select class="municipio_info_afiliado custom-select" name="municipio_info_afiliado" id="municipio_info_afiliado">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_municipio}}" selected>{{$array_datos_info_afiliados[0]->Nombre_municipio}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_pais_exterior_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="pais_exterior_info_afiliado" class="col-form-label">País Exterior</label>
                                                        <input type="text" class="pais_exterior_info_afiliado form-control" name="pais_exterior_info_afiliado" id="pais_exterior_info_afiliado">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="ocupacion" class="col-form-label">Ocupación</label>
                                                        <input type="text" class="ocupacion form-control" name="ocupacion" id="ocupacion" value="{{$array_datos_info_afiliados[0]->Ocupacion}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="ibc" class="col-form label">IBC</label>
                                                        <input type="text" class="ibc form-control" name="ibc" id="ibc" value="{{$array_datos_info_afiliados[0]->Ibc}}">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="eps" class="col-form label">EPS<span style="color:red;">(*)</span></label>
                                                        <select class="eps custom-select" name="eps" id="eps" required>
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_eps}}">{{$array_datos_info_afiliados[0]->Nombre_eps}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_eps d-none">
                                                    <div class="form-group">
                                                        <label for="otra_eps" class="col-form label">Otra EPS</label>
                                                        <input type="text" class="otra_eps form-control" name="otra_eps" id="otra_eps">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="afp" class="col-form label">AFP <span style="color:red;">(*)</span></label>
                                                        <select class="afp custom-select" name="afp" id="afp" required>
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_afp}}">{{$array_datos_info_afiliados[0]->Nombre_afp}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_afp d-none">
                                                    <div class="form-group">
                                                        <label for="otra_afp" class="col-form label">Otra AFP</label>
                                                        <input type="text" class="otra_afp form-control" name="otra_afp" id="otra_afp">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="arl_info_afiliado" class="col-form label">ARL<span style="color:red;">(*)</span></label>
                                                        <select class="arl_info_afiliado custom-select" name="arl_info_afiliado" id="arl_info_afiliado" required>
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_arl}}">{{$array_datos_info_afiliados[0]->Nombre_arl}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_arl_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="otra_arl_info_afiliado" class="col-form label">Otra ARL</label>
                                                        <input type="text" class="otra_arl_info_afiliado form-control" name="otra_arl_info_afiliado" id="otra_arl_info_afiliado">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3 si_no_apoderado">
                                                    <div class="form-group">
                                                        <label for="apoderado" class="col-form-label">Apoderado</label>
                                                        <select class="apoderado custom-select" name="apoderado" id="apoderado">
                                                            <option value="{{$array_datos_info_afiliados[0]->Apoderado}}">{{$array_datos_info_afiliados[0]->Apoderado}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php 
                                                $apoderados = $array_datos_info_afiliados[0]->Apoderado;
                                                if($apoderados == 'Si' ): ?>
                                                    <div class="col-3 columna_nombre_apoderado">
                                                        <div class="form-group">
                                                            <label for="nombre_apoderado" class="col-form-label">Nombre del apoderado</label>
                                                            <input type="text" class="nombre_apoderado form-control" name="nombre_apoderado" id="nombre_apoderado" value="{{$array_datos_info_afiliados[0]->Nombre_apoderado}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-3 columna_identificacion_apoderado">
                                                        <div class="form-group">
                                                            <label for="nro_identificacion_apoderado" class="col-form-label">N° identificación apoderado</label>
                                                            <input type="text" class="nro_identificacion_apoderado form-control" name="nro_identificacion_apoderado" id="nro_identificacion_apoderado" value="{{$array_datos_info_afiliados[0]->Nro_identificacion_apoderado}}">
                                                        </div>
                                                    </div>
                                                <?php elseif ($apoderados == 'No' ): ?> 
                                                    <div class="col-3 columna_nombre_apoderado d-none">
                                                        <div class="form-group">
                                                            <label for="nombre_apoderado" class="col-form-label">Nombre del apoderado</label>
                                                            <input type="text" class="nombre_apoderado form-control" name="nombre_apoderado" id="nombre_apoderado" value="{{$array_datos_info_afiliados[0]->Nombre_apoderado}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-3 columna_identificacion_apoderado d-none">
                                                        <div class="form-group">
                                                            <label for="nro_identificacion_apoderado" class="col-form-label">N° identificación apoderado</label>
                                                            <input type="text" class="nro_identificacion_apoderado form-control" name="nro_identificacion_apoderado" id="nro_identificacion_apoderado" value="{{$array_datos_info_afiliados[0]->Nro_identificacion_apoderado}}">
                                                        </div>
                                                    </div>
                                                <?php endif ?>                                                                                                
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="activo" class="col-form-label">Activo <span style="color:red;">(*)</span></label>
                                                        <select class="activo custom-select" name="activo" id="activo" required>                                                            
                                                            <option value="{{$array_datos_info_afiliados[0]->Activo}}">{{$array_datos_info_afiliados[0]->Activo}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="medio_notificacion_afiliado" class="col-form-label">Medio de Notificación <span style="color:red;">(*)</span></label>
                                                        <select class="medio_notificacion_afiliado custom-select" name="medio_notificacion_afiliado" id="medio_notificacion_afiliado" required>
                                                            <option value="{{$array_datos_info_afiliados[0]->Medio_notificacion}}">{{$array_datos_info_afiliados[0]->Medio_notificacion}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        @if (!empty($array_datos_info_afiliados[0]->Entidad_conocimiento) && $array_datos_info_afiliados[0]->Entidad_conocimiento == 'Si')
                                                            <input class="form-contol scalesR" type="checkbox" id="entidad_conocimiento" name="entidad_conocimiento" value="Si" checked>                                                            
                                                        @else
                                                            <input class="form-contol scalesR" type="checkbox" id="entidad_conocimiento" name="entidad_conocimiento" value="Si">                                                            
                                                        @endif
                                                        <label for="entidad_conocimiento" class="col-form-label">Entidad de Conocimiento (AFP)</label>
                                                        <div id="div_afp_conocimiento" class="d-none">
                                                            <select class="afp_conocimiento custom-select" name="afp_conocimiento" id="afp_conocimiento">
                                                                <option value="{{$array_datos_info_afiliados[0]->Id_afp_entidad_conocimiento}}">{{$array_datos_info_afiliados[0]->Nombre_afp_conocimiento}}</option>
                                                            </select>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_identificacion_afi_beni d-none">
                                                    <div class="form-group">
                                                        <label for="afi_nro_identificacion" class="col-form-label">N° de identificación afiliado<span style="color:red;">(*)</span></label>
                                                        <input type="text" class="afi_nro_identificacion form-control" name="afi_nro_identificacion" id="afi_nro_identificacion" value="{{$array_datos_info_afiliados[0]->Nro_identificacion_benefi}}">
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_tipo_documen_afi_beni d-none">
                                                    <div class="form-group" style="display:flex; flex-direction:column;">
                                                        <label for="afi_tipo_documento" class="col-form-label">Tipo de documento afiliado<span style="color:red;">(*)</span></label>
                                                        <select class="afi_tipo_documento custom-select" name="afi_tipo_documento" id="afi_tipo_documento">
                                                            <option value="{{$array_datos_info_afiliados[0]->Tipo_documento_benefi}}" selected>{{$array_datos_info_afiliados[0]->Nombre_documento_benefi}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-3 afi_otro_documento d-none">
                                                    <div class="form-group">
                                                        <label for="afi_otro_nombre_documento" class="col-form-label" style="color:;">Otro Documento<span style="color:red;">(*)</span></label>
                                                        <input type="text" class="afi_otro_nombre_documento form-control" name="afi_otro_nombre_documento" id="afi_otro_nombre_documento">
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_nombre_afi_beni d-none">
                                                    <div class="form-group">
                                                        <label for="afi_nombre_afiliado" class="col-form-label">Nombre afiliado<span style="color:red;">(*)</span></label>
                                                        <input type="text" class="afi_nombre_afiliado form-control" name="afi_nombre_afiliado" id="afi_nombre_afiliado" value="{{$array_datos_info_afiliados[0]->Nombre_afiliado_benefi}}">
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_direccion_afi_beni d-none">
                                                    <div class="form-group">
                                                        <label for="afi_direccion_info_afiliado" class="col-form-label">Dirección afiliado<span style="color:red;">(*)</span></label>
                                                        <input type="text" class="afi_direccion_info_afiliado form-control" name="afi_direccion_info_afiliado" id="afi_direccion_info_afiliado" value="{{$array_datos_info_afiliados[0]->Direccion_benefi}}">
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_depar_afi_beni d-none">
                                                    <div class="form-group" style="display:flex; flex-direction:column;">
                                                        <label for="afi_departamento_info_afiliado" class="col-form-label">Departamento afiliado<span style="color:red;">(*)</span></label>
                                                        <select class="afi_departamento_info_afiliado custom-select" name="afi_departamento_info_afiliado" id="afi_departamento_info_afiliado">
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_departamento_benefi}}" selected>{{$array_datos_info_afiliados[0]->Nombre_departamento_benefi}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-3 afi_columna_municipio_info_afiliado d-none">
                                                    <div class="form-group" style="display:flex; flex-direction:column;">
                                                        <label for="afi_municipio_info_afiliado" class="col-form-label">Ciudad afiliado<span style="color:red;">(*)</span></label>
                                                        <select class="afi_municipio_info_afiliado custom-select" name="afi_municipio_info_afiliado" id="afi_municipio_info_afiliado" disabled>
                                                            <option value="{{$array_datos_info_afiliados[0]->Id_municipio_benefi}}" selected>{{$array_datos_info_afiliados[0]->Nombre_municipio_benefi}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm afi_columna_pais_exterior_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="afi_pais_exterior_info_afiliado" class="col-form-label">País Exterior</label>
                                                        <input type="text" class="afi_pais_exterior_info_afiliado form-control" name="afi_pais_exterior_info_afiliado" id="afi_pais_exterior_info_afiliado">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN LABORAL --}}
                        <div class="row ocultar_seccion_info_laboral">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información laboral</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">   
                                            <?php
                                                $radio = $array_datos_info_laboral[0]->Tipo_empleado;
                                                if ($radio == 'Empleado actual'):?>                                                
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                      <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" checked required>
                                                      <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleo Actual</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                      <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" required>
                                                      <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                      <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="beneficiario" value="Beneficiario" required>
                                                      <label class="form-check-label custom-control-label" for="beneficiario"><strong>Beneficiario</strong></label>
                                                    </div>
                                                </div>
                                            <?php elseif ($radio == 'Independiente'):?>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" required>
                                                    <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleado Actual</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" checked required>
                                                    <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="beneficiario" value="Beneficiario" required>
                                                    <label class="form-check-label custom-control-label" for="beneficiario"><strong>Beneficiario</strong></label>
                                                    </div>
                                                </div>
                                            <?php elseif ($radio == 'Beneficiario'): ?>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" required>
                                                    <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleo Actual</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" required>
                                                    <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="beneficiario" value="Beneficiario" checked required>
                                                    <label class="form-check-label custom-control-label" for="beneficiario"><strong>Beneficiario</strong></label>
                                                    </div>
                                                </div>
                                            <?php endif?>
                                        </div> 
                                        <input type="hidden" class="form-control" name="t_laboral" id="t_laboral" value="{{$radio}}">
                                        <div class="row  columna_row1_laboral"  <?php if ($radio == 'Empleado actual' || $radio == 'Independiente'): ?> style="display:flex" <?php else: ?>  style="display:none" <?php endif?>>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="arl_info_laboral" class="col-form-label">ARL</label>
                                                    <select class="arl_info_laboral custom-select" name="arl_info_laboral" id="arl_info_laboral">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_arl}}">{{$array_datos_info_laboral[0]->Nombre_arl}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm otro_arl_info_laboral d-none">
                                                <div class="form-group">
                                                    <label for="otra_arl_info_laboral" class="col-form-label">Otra ARL</label>
                                                    <input type="text" class="otra_arl_info_laboral form-control" name="otra_arl_info_laboral" id="otra_arl_info_laboral">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <?php if ($radio == 'Empleado actual'): ?>
                                                        <label for="empresa" class="col-form-label si_nom_empresa">Empresa <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="empresa form-control" name="empresa" id="empresa"  value="{{$array_datos_info_laboral[0]->Empresa}}" required>
                                                    <?php elseif($radio == 'Independiente'): ?>
                                                        <label for="empresa" class="col-form-label no_nom_empresa">Empresa</label>
                                                        <input type="text" class="empresa form-control" name="empresa" id="empresa"  value="{{$array_datos_info_laboral[0]->Empresa}}">
                                                    <?php else: ?>
                                                        <label for="empresa" class="col-form-label si_nom_empresa">Empresa <span style="color:red;">(*)</span></label>
                                                        <label for="empresa" class="col-form-label no_nom_empresa d-none">Empresa</label>
                                                        <input type="text" class="empresa form-control" name="empresa" id="empresa"  value="{{$array_datos_info_laboral[0]->Empresa}}">
                                                    <?php endif?>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <?php if ($radio == 'Empleado actual'): ?>
                                                        <label for="nit_cc" class="col-form-label si_nom_nitcc">NIT / CC <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="nit_cc form-control" name="nit_cc" id="nit_cc"  value="{{$array_datos_info_laboral[0]->Nit_o_cc}}" required>
                                                    <?php elseif($radio == 'Independiente'): ?>
                                                        <label for="nit_cc" class="col-form-label no_nom_nitcc">NIT / CC</label>
                                                        <input type="text" class="nit_cc form-control" name="nit_cc" id="nit_cc"  value="{{$array_datos_info_laboral[0]->Nit_o_cc}}">
                                                    <?php else: ?>
                                                        <label for="nit_cc" class="col-form-label si_nom_nitcc">NIT / CC <span style="color:red;">(*)</span></label>
                                                        <label for="nit_cc" class="col-form-label no_nom_nitcc d-none">NIT / CC</label>
                                                        <input type="text" class="nit_cc form-control" name="nit_cc" id="nit_cc"  value="{{$array_datos_info_laboral[0]->Nit_o_cc}}">
                                                    <?php endif?>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <?php if ($radio == 'Empleado actual'): ?>
                                                        <label for="telefono_empresa" class="col-form-label si_nom_tel_empresa">Télefono empresa <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="telefono_empresa form-control" name="telefono_empresa" id="telefono_empresa" value="{{$array_datos_info_laboral[0]->Telefono_empresa}}" required>
                                                    <?php elseif($radio == 'Independiente'):?>
                                                        <label for="telefono_empresa" class="col-form-label no_nom_tel_empresa">Télefono empresa</label>
                                                        <input type="text" class="telefono_empresa form-control" name="telefono_empresa" id="telefono_empresa" value="{{$array_datos_info_laboral[0]->Telefono_empresa}}">
                                                    <?php else: ?>
                                                        <label for="telefono_empresa" class="col-form-label si_nom_tel_empresa">Télefono empresa <span style="color:red;">(*)</span></label>
                                                        <label for="telefono_empresa" class="col-form-label no_nom_tel_empresa d-none">Télefono empresa</label>
                                                        <input type="text" class="telefono_empresa form-control" name="telefono_empresa" id="telefono_empresa" value="{{$array_datos_info_laboral[0]->Telefono_empresa}}">
                                                    <?php endif?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row2_laboral" <?php if ($radio == 'Empleado actual'  || $radio == 'Independiente'): ?> style="display:flex" <?php else: ?>  style="display:none" <?php endif?>>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <?php if ($radio == 'Empleado actual'): ?>
                                                        <label for="email_info_laboral" class="col-form-label si_nom_email">Email <span style="color:red;">(*)</span></label>                                                        
                                                        <input type="email" class="email_info_laboral form-control" list="opciones_email_laboral" id="email_info_laboral" name="email_info_laboral" placeholder="Selecciona o escribe..." value="{{$array_datos_info_laboral[0]->Email}}" required>
                                                        <datalist id="opciones_email_laboral">
                                                            <option value="sin@correo.com">
                                                        </datalist><br>
                                                    <?php elseif($radio == 'Independiente'):?>
                                                        <label for="email_info_laboral" class="col-form-label no_nom_email">Email</label>
                                                        <input type="email" class="email_info_laboral form-control" list="opciones_email_laboral" id="email_info_laboral" name="email_info_laboral" placeholder="Selecciona o escribe..." value="{{$array_datos_info_laboral[0]->Email}}">
                                                        <datalist id="opciones_email_laboral">
                                                            <option value="sin@correo.com">
                                                        </datalist><br>
                                                    <?php else: ?>
                                                        <label for="email_info_laboral" class="col-form-label si_nom_email">Email <span style="color:red;">(*)</span></label>
                                                        <label for="email_info_laboral" class="col-form-label no_nom_email d-none">Email</label>
                                                        <input type="email" class="email_info_laboral form-control" name="email_info_laboral" id="email_info_laboral" value="{{$array_datos_info_laboral[0]->Email}}">
                                                    <?php endif?>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <?php if ($radio == 'Empleado actual'): ?>
                                                        <label for="direccion_info_laboral" class="col-form-label si_nom_direccion">Dirección <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="direccion_info_laboral form-control" name="direccion_info_laboral" id="direccion_info_laboral" value="{{$array_datos_info_laboral[0]->Direccion}}" required>
                                                    <?php elseif($radio == 'Independiente'):?>
                                                        <label for="direccion_info_laboral" class="col-form-label no_nom_direccion">Dirección</label>
                                                        <input type="text" class="direccion_info_laboral form-control" name="direccion_info_laboral" id="direccion_info_laboral" value="{{$array_datos_info_laboral[0]->Direccion}}">
                                                    <?php else: ?>
                                                        <label for="direccion_info_laboral" class="col-form-label si_nom_direccion">Dirección <span style="color:red;">(*)</span></label>
                                                        <label for="direccion_info_laboral" class="col-form-label no_nom_direccion d-none">Dirección</label>
                                                        <input type="text" class="direccion_info_laboral form-control" name="direccion_info_laboral" id="direccion_info_laboral" value="{{$array_datos_info_laboral[0]->Direccion}}">
                                                    <?php endif?>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <?php if ($radio == 'Empleado actual'): ?>
                                                        <label for="departamento_info_laboral" class="col-form-label si_nom_departamento">Departamento <span style="color:red;">(*)</span></label>
                                                        <select class="departamento_info_laboral custom-select" name="departamento_info_laboral" id="departamento_info_laboral" required>
                                                            <option value="{{$array_datos_info_laboral[0]->Id_departamento}}">{{$array_datos_info_laboral[0]->Nombre_departamento}}</option>
                                                        </select>
                                                    <?php elseif($radio == 'Independiente'):?>
                                                        <label for="departamento_info_laboral" class="col-form-label no_nom_departamento">Departamento</label>
                                                        <select class="departamento_info_laboral custom-select" name="departamento_info_laboral" id="departamento_info_laboral">
                                                            <option value="{{$array_datos_info_laboral[0]->Id_departamento}}">{{$array_datos_info_laboral[0]->Nombre_departamento}}</option>
                                                        </select>
                                                    <?php else: ?>
                                                        <label for="departamento_info_laboral" class="col-form-label si_nom_departamento">Departamento <span style="color:red;">(*)</span></label>
                                                        <label for="departamento_info_laboral" class="col-form-label no_nom_departamento d-none">Departamento</label>
                                                        <select class="departamento_info_laboral custom-select" name="departamento_info_laboral" id="departamento_info_laboral">
                                                            <option value="{{$array_datos_info_laboral[0]->Id_departamento}}">{{$array_datos_info_laboral[0]->Nombre_departamento}}</option>
                                                        </select>
                                                    <?php endif?>
                                                </div>
                                            </div>
                                            <div class="col-sm columna_municipio_info_laboral">
                                                <div class="form-group">
                                                    <?php if ($radio == 'Empleado actual'): ?>
                                                        <label for="municipio_info_laboral" class="col-form-label si_nom_ciudad">Ciudad <span style="color:red;">(*)</span></label>
                                                        <select class="municipio_info_laboral custom-select" name="municipio_info_laboral" id="municipio_info_laboral" required>
                                                            <option value="{{$array_datos_info_laboral[0]->Id_municipio}}">{{$array_datos_info_laboral[0]->Nombre_municipio}}</option>
                                                        </select>
                                                    <?php elseif($radio == 'Independiente'):?>
                                                        <label for="municipio_info_laboral" class="col-form-label no_nom_ciudad">Ciudad</label>
                                                        <select class="municipio_info_laboral custom-select" name="municipio_info_laboral" id="municipio_info_laboral">
                                                            <option value="{{$array_datos_info_laboral[0]->Id_municipio}}">{{$array_datos_info_laboral[0]->Nombre_municipio}}</option>
                                                        </select>
                                                    <?php else: ?>
                                                        <label for="municipio_info_laboral" class="col-form-label si_nom_ciudad">Ciudad <span style="color:red;">(*)</span></label>
                                                        <label for="municipio_info_laboral" class="col-form-label no_nom_ciudad d-none">Ciudad</label>
                                                        <select class="municipio_info_laboral custom-select" name="municipio_info_laboral" id="municipio_info_laboral">
                                                            <option value="{{$array_datos_info_laboral[0]->Id_municipio}}">{{$array_datos_info_laboral[0]->Nombre_municipio}}</option>
                                                        </select>
                                                    <?php endif?>
                                                </div>
                                            </div>
                                            <div class="col-sm columna_pais_exterior_info_laboral d-none">
                                                <div class="form-group">
                                                    <label for="pais_exterior_info_laboral" class="col-form-label">País Exterior</label>
                                                    <input type="text" class="pais_exterior_info_laboral form-control" name="pais_exterior_info_laboral" id="pais_exterior_info_laboral">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row3_laboral" <?php if ($radio == 'Empleado actual'  || $radio == 'Independiente'): ?> style="display:flex" <?php else: ?>  style="display:none" <?php endif?>>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="actividad_economica" class="col-form-label">Actividad económica</label>
                                                    <select class="actividad_economica custom-select" name="actividad_economica" id="actividad_economica">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_actividad_economica}}">{{$array_datos_info_laboral[0]->Nombre_actividad}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="clase_riesgo" class="col-form-label">Clase / Riesgo</label>
                                                    <select class="clase_riesgo custom-select" name="clase_riesgo" id="clase_riesgo">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_clase_riesgo}}">{{$array_datos_info_laboral[0]->Nombre_riesgo}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="persona_contacto" class="col-form-label">Persona de contacto</label>
                                                    <input type="text" class="persona_contacto form-control" name="persona_contacto" id="persona_contacto" value="{{$array_datos_info_laboral[0]->Persona_contacto}}">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefono_persona_contacto" class="col-form-label">Teléfono persona contacto</label>
                                                    <input type="text" class="telefono_persona_contacto form-control" name="telefono_persona_contacto" id="telefono_persona_contacto" value="{{$array_datos_info_laboral[0]->Telefono_persona_contacto}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row4_laboral" <?php if ($radio == 'Empleado actual' || $radio == 'Independiente'): ?> style="display:flex" <?php else: ?>  style="display:none" <?php endif?>>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="codigo_ciuo" class="col-form-label">Código CIUO</label>
                                                    <select class="codigo_ciuo custom-select" name="codigo_ciuo" id="codigo_ciuo">
                                                        <option value="{{$array_datos_info_laboral[0]->Id_codigo_ciuo}}">{{$array_datos_info_laboral[0]->Nombre_ciuo}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fecha_ingreso" class="col-form-label">Fecha de ingreso</label>
                                                    <input type="date" class="fecha_ingreso form-control" name="fecha_ingreso" id="fecha_ingreso" value="{{$array_datos_info_laboral[0]->F_ingreso}}" max="{{date("Y-m-d")}}">
                                                    <span class="d-none" id="fecha_ingreso_alerta" style="color: red; font-style: italic;"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="cargo" class="col-form-label">Cargo</label>
                                                    <input type="text" class="cargo form-control" name="cargo" id="cargo" value="{{$array_datos_info_laboral[0]->Cargo}}" >
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="funciones_cargo" class="col-form-label">Funciones del cargo</label>
                                                    <textarea class="funciones_cargo form-control" name="funciones_cargo" id="funciones_cargo" rows="2">{{$array_datos_info_laboral[0]->Funciones_cargo}} </textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row5_laboral" <?php if ($radio == 'Empleado actual' || $radio == 'Independiente'): ?> style="display:flex" <?php else: ?>  style="display:none" <?php endif?>>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="antiguedad_empresa" class="col-form-label">Antiguedad en empresa (Meses)</label>
                                                    <input type="number" class="antiguedad_empresa form-control" name="antiguedad_empresa" id="antiguedad_empresa" value="{{$array_datos_info_laboral[0]->Antiguedad_empresa}}">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="antiguedad_cargo" class="col-form-label">Antiguedad en el cargo (Meses)</label>
                                                    <input type="number" class="antiguedad_cargo form-control" name="antiguedad_cargo" id="antiguedad_cargo" value="{{$array_datos_info_laboral[0]->Antiguedad_cargo_empresa}}">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fecha_retiro" class="col-form-label">Fecha de retiro</label>
                                                    <input type="date" class="fecha_retiro form-control" name="fecha_retiro" id="fecha_retiro" value="{{$array_datos_info_laboral[0]->F_retiro}}" max="{{date("Y-m-d")}}">
                                                    <span class="d-none" id="fecha_retiro_alerta" style="color: red; font-style: italic;"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="medio_notificacion_laboral" class="col-form-label si_medio_noti">Medio de Notificación <span style="color:red;">(*)</span></label>
                                                    <label for="medio_notificacion_laboral" class="col-form-label no_medio_noti d-none">Medio de Notificación</label>
                                                    <select class="medio_notificacion_laboral custom-select" name="medio_notificacion_laboral" id="medio_notificacion_laboral">
                                                        <option value="{{$array_datos_info_laboral[0]->Medio_notificacion}}">{{$array_datos_info_laboral[0]->Medio_notificacion}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="descripcion" class="col-form-label">Descripción</label>
                                                    <textarea class="descripcion form-control" name="descripcion" id="descripcion" rows="2">{{$array_datos_info_laboral[0]->Descripcion}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        {{-- OPCIONES PARA HABILITAR EL COLLAPSE Y EL MODAL --}}
                                        <div class="row">
                                            <div class="col-6">
                                                <a  data-toggle="collapse" class="text-dark" id="llenar_tabla_historico_empresas" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="far fa-eye text-info"></i> <strong>ver histórico empresas</strong></a>&nbsp;
                                                <a href="javascript:void(0);" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalInfoLaboral"><i class="fas fa-plus-circle text-info"></i> <strong>Agregar nueva empresa</strong></a>
                                            </div>
                                        </div><br>
                                        {{-- COLLAPSE PARA MOSTRAR EL HISTÓRICO DE RESULTADOS --}}
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="ver_historico_empresa_afiliado">
                                                    <div class="collapse" id="collapseExample">
                                                      <div class="card card-body">
                                                        <div class="table table-responsive" id="si_tabla">
                                                            <table id="listado_historico_empresas" class="table table-striped table-bordered" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Tipo Empleo</th>
                                                                        <th>ARL</th>
                                                                        <th>Empresa</th>
                                                                        <th>NIT / CC</th>
                                                                        <th>Télefono Empresa</th>
                                                                        <th>Email</th>
                                                                        <th>Dirección</th>
                                                                        <th>Departamento</th>
                                                                        <th>Ciudad</th>
                                                                        <th>Actividad económica</th>
                                                                        <th>Clase / Riesgo</th>
                                                                        <th>Persona de contacto</th>
                                                                        <th>Teléfono Persona Contacto</th>
                                                                        <th>Código CIUO</th>
                                                                        <th>Fecha de ingreso</th>
                                                                        <th>Cargo</th>
                                                                        <th>Funciones Del Cargo</th>
                                                                        <th>Antiguedad en Empresa (Meses)</th>
                                                                        <th>Antiguedad en el Cargo (Meses)</th>
                                                                        <th>Fecha de retiro</th>
                                                                        <th>Descripción</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="borrar_tabla_historico_empresa"></tbody>
                                                            </table>
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
                        {{-- INFORMACIÓN PERICIAL --}}
                        <div class="row ocultar_seccion_info_pericial">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información Pericial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="motivo_solicitud" class="col-form label">Motivo solicitud</label>
                                                    <select class="motivo_solicitud custom-select" name="motivo_solicitud" id="motivo_solicitud">
                                                        <option value="{{$array_datos_info_pericial[0]->Id_motivo_solicitud}}">{{$array_datos_info_pericial[0]->Nombre_solicitud}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="tipovinculo" class="col-form label">Tipo de vinculación</label>
                                                    <select class="tipovinculo custom-select" name="tipovinculo" id="tipovinculo">
                                                        <option value="{{$array_datos_info_pericial[0]->Tipo_vinculacion}}">{{$array_datos_info_pericial[0]->tipo_viculacion}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="regimen" class="col-form label">Régimen en salud</label>
                                                    <select class="regimen custom-select" name="regimen" id="regimen">
                                                        <option value="{{$array_datos_info_pericial[0]->Regimen_salud}}">{{$array_datos_info_pericial[0]->regimen_salud}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="solicitante" class="col-form label">Solicitante</label>
                                                    <select class="solicitante custom-select" name="solicitante" id="solicitante">
                                                        <option value="{{$array_datos_info_pericial[0]->Id_solicitante}}">{{$array_datos_info_pericial[0]->Solicitante}}</option>
                                                    </select>
                                                </div>
                                            </div>     
                                            <div class="col-4 columna_otro_solicitante d-none">
                                                <div class="form-group">
                                                    <label for="otro_solicitante" class="col-form label">Nombre solicitante</label>
                                                    <input type="text" class="otro_solicitante form-control" name="otro_solicitante" id="otro_solicitante" value="{{$array_datos_info_pericial[0]->Nombre_solicitante}}">
                                                </div>
                                            </div>
                                            <div class="col-4 columna_nombre_solicitante">
                                                <div class="form-group">
                                                    <label for="nombre_solicitante" class="col-form label">Nombre de solicitante</label>
                                                    <select class="nombre_solicitante custom-select" name="nombre_solicitante" id="nombre_solicitante">
                                                        <option value="{{$array_datos_info_pericial[0]->Id_nombre_solicitante}}">{{$array_datos_info_pericial[0]->Nombre_solicitante}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 columna_otro_nombre_solicitante d-none">
                                                <div class="form-group">
                                                    <label for="otro_nombre_solicitante" class="col-form label">Otro Nombre de solicitante</label>
                                                    <input type="text" class="otro_nombre_solicitante form-control" name="otro_nombre_solicitante" id="otro_nombre_solicitante">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="fuente_informacion" class="col-form label">Fuente de información</label>
                                                    <select class="fuente_informacion custom-select" name="fuente_informacion" id="fuente_informacion">
                                                        <option value="{{$array_datos_info_pericial[0]->Fuente_informacion}}">{{$array_datos_info_pericial[0]->fuente_informacion}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4 columna_otra_fuente_informacion d-none">
                                                <div class="form-group">
                                                    <label for="otra_fuente_informacion" class="col-form label">Otra Fuente de información</label>
                                                    <input type="text" class="otra_fuente_informacion form-control" name="otra_fuente_informacion" id="otra_fuente_informacion">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- OPCIONES PARA HABILITAR EL MODAL DE DOCUMENTOS --}}
                                        {{-- <div class="row">
                                            <div class="col-6">
                                                <a href="javascript:void(0);" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN ASIGNACION (NO ESTÁ YA ACTIVADA ESTE PARTE DEL CÓDIGO DEBIDO A QUE ESTA INFORMACIÓN YA NO SERÁ DE ACCESO PARA EL USUARIO)  --}}
                        {{-- <div class="row ocultar_seccion_info_asignacion">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Asignación</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="proceso" class="col-form label">Proceso <span style="color:red;">(*)</span></label>
                                                <select class="proceso custom-select" name="proceso" id="proceso" requierd>
                                                    <option value="{{$array_datos_info_asignacion[0]->Id_proceso}}">{{$array_datos_info_asignacion[0]->Nombre_proceso}}</option>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <label for="servicio" class="col-form label">Servicio <span style="color:red;">(*)</span></label>
                                                <select class="servicio custom-select" name="servicio" id="servicio" requierd>
                                                    <option value="{{$array_datos_info_asignacion[0]->Id_servicio}}">{{$array_datos_info_asignacion[0]->Nombre_servicio}}</option>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <label for="accion" class="col-form label">Acción <span style="color:red;">(*)</span></label>
                                                <select class="accion custom-select" name="accion" id="accion" requierd>
                                                    <option value="{{$array_datos_info_asignacion[0]->Id_accion}}">{{$array_datos_info_asignacion[0]->Nombre_accion}}</option>
                                                </select>
                                            </div>    
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fecha_alerta" class="col-form label">Fecha Alerta</label>
                                                    <input type="date" class="fecha_alerta form-control" name="fecha_alerta" id="fecha_alerta" value="{{$array_datos_info_asignacion[0]->F_alerta}}">
                                                </div>
                                            </div>                                                                                     
                                        </div>    
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="descripcion_asignacion" class="col-form label">Descripción</label>                                            
                                                <textarea class="form-control" name="descripcion_asignacion" id="descripcion_asignacion" rows="2" required>{{$array_datos_info_asignacion[0]->Descripcion}}</textarea>
                                            </div> 
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="grupo_botones" style="float: left;">
                    {{-- <input type="reset" id="Borrar" class="btn btn-info" value="Restablecer"> --}}
                    <input type="submit" id="Edicion_editar" class="btn btn-info" value="Actualizar" onclick="OcultarbotonActualizar()">
                </div>
                <div class="text-center" id="mostrar-barra2"  style="display:none;">                                
                    <button class="btn btn-info" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Actualizando el Evento...
                    </button>
                </div>
            </div>
            <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                <i class="fas fa-chevron-up"></i>
            </a> 
        </form>
        {{-- MODAL PARA AGREGAR INFORMACION LABORAL --}}
        <div class="row">
            <div class="contenedor_agregar_empresa" style="float: left;">
                <x-adminlte-modal id="modalInfoLaboral" title="Agregar Información laboral" theme="info" icon="fas fa-plus" size='xl' disable-animations>
                    <form id="formulario_empresa">
                        @csrf
                        <div class="row text-center">
                            <div class="col-sm">
                                <div class="form-check custom-control custom-radio">
                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo_registrar" id="empleo_actual_registrar" value="Empleado actual" required>
                                  <label class="form-check-label custom-control-label" for="empleo_actual_registrar"><strong>Empleo Actual</strong></label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-check custom-control custom-radio">
                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo_registrar" id="independiente_registrar" value="Independiente" required>
                                  <label class="form-check-label custom-control-label" for="independiente_registrar"><strong>Independiente</strong></label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-check custom-control custom-radio">
                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo_registrar" id="beneficiario_registrar" value="Beneficiario" required>
                                  <label class="form-check-label custom-control-label" for="beneficiario_registrar"><strong>Beneficiario</strong></label>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row columna_row1_laboral_registrar">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="arl_info_laboral_registrar" class="col-form-label">ARL</label><br>
                                        <select class="arl_info_laboral_registrar custom-select" name="arl_info_laboral_registrar" id="arl_info_laboral_registrar" style="width: 261.5px;"></select>
                                    </div>
                                </div>
                                <div class="col-sm otro_arl_info_laboral_registrar d-none">
                                    <div class="form-group">
                                        <label for="otra_arl_info_laboral_registrar" class="col-form-label">Otra ARL</label>
                                        <input type="text" class="otra_arl_info_laboral_registrar form-control" name="otra_arl_info_laboral_registrar" id="otra_arl_info_laboral_registrar">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="empresa_registrar" class="col-form-label">Empresa <span style="color:red;">(*)</span></label>
                                        <input type="text" class="empresa_registrar form-control" name="empresa_registrar" id="empresa_registrar" >
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="nit_cc_registrar" class="col-form-label">NIT / CC <span style="color:red;">(*)</span></label>
                                        <input type="text" class="nit_cc_registrar form-control" name="nit_cc_registrar" id="nit_cc_registrar" >
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="telefono_empresa_registrar" class="col-form-label">Télefono empresa</label>
                                        <input type="text" class="telefono_empresa_registrar form-control" name="telefono_empresa_registrar" id="telefono_empresa_registrar">
                                    </div>
                                </div>
                            </div>
                            <div class="row columna_row2_laboral_registrar">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="email_info_laboral_registrar" class="col-form-label">Email</label>
                                        <input type="email" class="email_info_laboral_registrar form-control" name="email_info_laboral_registrar" id="email_info_laboral_registrar">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="direccion_info_laboral_registrar" class="col-form-label">Dirección</label>
                                        <input type="text" class="direccion_info_laboral_registrar form-control" name="direccion_info_laboral_registrar" id="direccion_info_laboral_registrar">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="departamento_info_laboral_registrar" class="col-form-label">Departamento</label>
                                        <select class="departamento_info_laboral_registrar custom-select" name="departamento_info_laboral_registrar" id="departamento_info_laboral_registrar" style="width: 261.5px;"></select>
                                    </div>
                                </div>
                                <div class="col-sm columna_municipio_info_laboral_registrar">
                                    <div class="form-group">
                                        <label for="municipio_info_laboral_registrar" class="col-form-label">Ciudad</label>
                                        <select class="municipio_info_laboral_registrar custom-select" name="municipio_info_laboral_registrar" id="municipio_info_laboral_registrar" style="width: 261.5px;" disabled></select>
                                    </div>
                                </div>
                                <div class="col-sm columna_pais_exterior_info_laboral_registrar d-none">
                                    <div class="form-group">
                                        <label for="pais_exterior_info_laboral_registrar" class="col-form-label">País Exterior</label>
                                        <input type="text" class="pais_exterior_info_laboral_registrar form-control" name="pais_exterior_info_laboral_registrar" id="pais_exterior_info_laboral_registrar">
                                    </div>
                                </div>
                            </div>
                            <div class="row columna_row3_laboral_registrar">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="actividad_economica_registrar" class="col-form-label">Actividad económica</label>
                                        <select class="actividad_economica_registrar custom-select" name="actividad_economica_registrar" id="actividad_economica_registrar" style="width: 261.5px;"></select>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="clase_riesgo_registrar" class="col-form-label">Clase / Riesgo</label>
                                        <select class="clase_riesgo_registrar custom-select" name="clase_riesgo_registrar" id="clase_riesgo_registrar" style="width: 261.5px;"></select>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="persona_contacto_registrar" class="col-form-label">Persona de contacto</label>
                                        <input type="text" class="persona_contacto_registrar form-control" name="persona_contacto_registrar" id="persona_contacto_registrar" style="width: 100% !important;">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="telefono_persona_contacto_registrar" class="col-form-label">Tel persona contacto</label>
                                        <input type="text" class="telefono_persona_contacto_registrar form-control" name="telefono_persona_contacto_registrar" id="telefono_persona_contacto_registrar" style="width: 100% !important;">
                                    </div>
                                </div>
                            </div>
                            <div class="row columna_row4_laboral_registrar">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="codigo_ciuo_registrar" class="col-form-label">Código CIUO</label><br>
                                        <select class="codigo_ciuo_registrar custom-select" name="codigo_ciuo_registrar" id="codigo_ciuo_registrar" style="width: 353.67px;"></select>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="fecha_ingreso_registrar" class="col-form-label">Fecha de ingreso</label>
                                        <input type="date" class="fecha_ingreso_registrar form-control" name="fecha_ingreso_registrar" id="fecha_ingreso_registrar" max="{{date("Y-m-d")}}">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="cargo_registrar" class="col-form-label">Cargo</label>
                                        <input type="text" class="cargo_registrar form-control" name="cargo_registrar" id="cargo_registrar">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="funciones_cargo_registrar" class="col-form-label">Funciones del cargo</label>
                                        <textarea class="funciones_cargo_registrar form-control" name="funciones_cargo_registrar" id="funciones_cargo_registrar" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row columna_row5_laboral_registrar">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="antiguedad_empresa_registrar" class="col-form-label">Antiguedad en empresa (Meses)</label>
                                        <input type="number" class="antiguedad_empresa_registrar form-control" name="antiguedad_empresa_registrar" id="antiguedad_empresa_registrar">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="antiguedad_cargo_registrar" class="col-form-label">Antiguedad en el cargo (Meses)</label>
                                        <input type="number" class="antiguedad_cargo_registrar form-control" name="antiguedad_cargo_registrar" id="antiguedad_cargo_registrar">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="fecha_retiro_registrar" class="col-form-label">Fecha de retiro</label>
                                        <input type="date" class="fecha_retiro_registrar form-control" name="fecha_retiro_registrar" id="fecha_retiro_registrar" max="{{date("Y-m-d")}}">
                                        <span class="d-none" id="fecha_retiro_alerta" style="color: red; font-style: italic;"></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="descripcion_registrar" class="col-form-label">Descripción</label>
                                        <textarea class="descripcion_registrar form-control" name="descripcion_registrar" id="descripcion_registrar" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="no_creada_empresa alert alert-danger mt-2 d-none" role="alert"></div>
                                    <div class="creada_empresa alert alert-success mt-2 d-none" role="alert"></div>
                                </div>
                            </div>
                        </div>
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="mr-auto" id="guardar_otra_empresa" theme="info" label="Guardar"/>
                            <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
                        </x-slot>
                    </form>
                </x-adminlte-modal>
            </div>
        </div>
        @include('//.modals.historialServicios')
        {{-- MODAL PARA AGREGAR DOCUMENTOS INFORMACION PERICIAL --}}
        <?php $aperturaModal = 'Edicion'; ?>
        {{-- @include('administrador.modalcarguedocumentos') --}}
        @include('//.administrador.modalProgressbar')
        <?php 
        /* echo'<pre>';
        print_r($arraylistado_documentos) ;
        echo'</pre>'; */
        ?>
    </div>
@stop

@section('js')
    {{-- <script type="text/javascript">
        document.getElementById('btn_regreso_dto_atel').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('Regreso_DTO_ATEL').submit();
        });

        document.getElementById('btn_regreso_adicion_dx').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('Regreso_Adicion_Dx').submit();
        });
    </script> --}}
    <script src="/js/selectores_gestion_edicion.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script type="text/javascript">
        // var conteo = 0;
        $('#llenar_tabla_historico_empresas').click(function(){
            $('#borrar_tabla_historico_empresa').empty();
            var nro_ident = $('#listado_usuarios_asignacion_rol').val();
            var datos_llenar_tabla_info_laboral = {
                '_token': $('input[name=_token]').val(),
                'numero_identificacion' : $('#nro_identificacion').val()
            };
            $.ajax({
                type:'POST',
                url:'/consultaHistoricoEmpresas',
                data: datos_llenar_tabla_info_laboral,
                success:function(data) {
                    if(data.length == 0){
                        $('#borrar_tabla_historico_empresa').empty();
                    }else{
                        // console.log(data);
                        $.each(data, function(index, value){
                            llenar_historico_empresas(data, index, value);
                        });
                    }
                }
            });
        });

        $('#cargar_historial_acciones').click(function(){
           $('#borrar_tabla_historial_acciones').empty();

           var datos_llenar_tabla_historial_acciones = {
                '_token': $('input[name=_token]').val(),
                'ID_evento' : $('#id_evento').val()
            };
            
            $.ajax({
                type:'POST',
                url:'/historialAccionesEventos',
                data: datos_llenar_tabla_historial_acciones,
                success:function(data) {
                    if(data.length == 0){
                        $('#borrar_tabla_historial_acciones').empty();
                    }else{
                        // console.log(data);
                        $.each(data, function(index, value){
                            llenar_historial_acciones(data, index, value);
                        });
                    }
                }
            });
        });

        function llenar_historico_empresas(response, index, value){
            $('#listado_historico_empresas').DataTable({
                dom: 'Bfrtip',                      
                buttons:{
                    dom:{
                        buttons:{
                            className: 'btn'
                        }
                    },
                    buttons:[
                        {
                            extend:"excel",
                            title: 'Historico De Empresas',
                            text:'Exportar datos',
                            className: 'btn btn-info',
                            "excelStyles": [                      // estilos de excel
                                                        
                            ],
                            //Limitar columnas para el reporte
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
                            }  
                        }
                    ]
                }, 
                "destroy": true,
                "data": response,
                "pageLength": 2,
                // "order": [[2, 'desc']],
                "columns":[
                    {"data":"Tipo_empleado"},
                    {"data":"Nombre_arl"},
                    {"data":"Empresa"},
                    {"data":"Nit_o_cc"},
                    {"data":"Telefono_empresa"},
                    {"data":"Email"},
                    {"data":"Direccion"},
                    {"data":"Nombre_departamento"},
                    {"data":"Nombre_municipio"},
                    {"data": "full_actividad_economica"},
                    {"data":"Nombre_riesgo"},
                    {"data":"Persona_contacto"},
                    {"data":"Telefono_persona_contacto"},
                    {"data":"full_ciuo"},
                    {"data":"F_ingreso"},
                    {"data":"Cargo"},
                    {"data":"Funciones_cargo"},
                    {"data":"Antiguedad_empresa"},
                    {"data":"Antiguedad_cargo_empresa"},
                    {"data":"F_retiro"},
                    {"data":"Descripcion"}
                ],
                "language":{
                    "search": "Buscar",
                    "lengthMenu": "Mostrar _MENU_ resgistros por página",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "first": "Primero",
                        "last": "Último"
                    },
                    "emptyTable": "No se encontró información",
                    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                }
            });
        }

        function llenar_historial_acciones(response, index, value){
            $('#listado_historial_acciones_evento').DataTable({
                "dom": 'rtip',
                "destroy": true,
                "data": response,
                "pageLength": 5,
                // "order": [[0, 'desc']],
                "columns":[
                    {"data":"F_accion"},
                    {"data":"Nombre_usuario"},
                    {"data":"Accion"},
                    {"data":"Descripcion"}
                ],
                "language":{
                    "search": "Buscar",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente",
                        "first": "Primero",
                        "last": "Último"
                    },
                    "emptyTable": "No se encontró información",
                    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                }
            });
        }
    </script>
    <script>
        function OcultarbotonActualizar(){
            $("#gestion_inicial").submit(function(e){
                $('#Edicion_editar, #Borrar').addClass('d-none');
                $('#mostrar-barra2').css("display", "block");
                return true;
            });
        }


        $('#Borrar').click(function(){
            location.reload();
        });
    </script> 
    <script>
       
        // $("#fecha_evento").on("change", function() {
        //     var fechaEvento = $(this).val();
        //     $("#fecha_radicacion").val('').attr("min", fechaEvento);
        // });

        /* Nueva validación para la fecha de Radicación */
        $(document).on('keyup change click', '#fecha_radicacion',function(event){
            
            var fechaEvento = $("#fecha_evento").val();
            var tipo_handler = event.type;
            /* 
                CASO 1: Si el evento es de tipo click entonces modifica el atributo min de la f de radicación
                para no permitir dejar escoger fechas anteriores a la fecha de evento seleccionada.
            */
            /*
                CASO 2: Si los eventos son keyup y change entonces se valida que la fecha de radicación
                no debe ser inferior a la fecha del evento, solo puede ser superior o igual.
            */
            switch (tipo_handler) {
                case 'click':
                    $(this).attr("min", fechaEvento);
                break;
                
                case 'keyup':
                case 'change':
                    if ($(this).val() < fechaEvento) {
                        // Eliminar cualquier alerta previa
                        if ($(this).next('i').length) {
                            $(this).next('i').remove();
                        }
                        let alerta = '<i style="color:red;">La fecha debe ser igual o mayor a la fecha evento: '+fechaEvento+'</i>';
                        $(this).after(alerta);
                    }else{
                        if ($(this).next('i').length) {
                            $(this).next('i').remove();
                        }
                    }
                break;

                default:
                break;
            }
        });
            
        $("#fecha_ingreso").on("change", function() {
            var fechaEvento = $(this).val();
            $("#fecha_retiro").val('').attr("min", fechaEvento);
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
                    $('#Edicion_editar').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(this.value > today){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#Edicion_editar').addClass('d-none');
                    return;
                }
                $('#Edicion_editar').removeClass('d-none');
                return $(`#${this.id}_alerta`).text('').addClass("d-none");
            });
        });
    </script>
@stop