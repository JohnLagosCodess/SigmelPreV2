@extends('adminlte::page')
@section('title', 'Parametrizaciones')

@section('css')
<style>
    body {
      max-width: 100%;
      overflow-x: hidden;
    }

    table tr td:nth-child(4) {
      width: 100px;
      color: green;
    }
  </style>
@stop

@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-8">
            <a href="{{route("listarClientes")}}" class="btn btn-info" type="button"><i class="fa fa-arrow-left"></i> Regresar a Listado de Clientes</a>
            <a href="{{route("listarAcciones")}}" class="btn btn-info" type="button"><i class="fas fa-list"></i> Ir a Listado de Acciones</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div>
                <h4>Convenciones:</h4>
                <p><i class="fa fa-sm fa-pen text-primary"></i> Editar Pametrización &nbsp; <i class="fa fa-sm fa-check text-success"></i> Guardar Parametrización</p>
            </div>
            <div class="card-info">
                <div class="card-header text-center">
                    <h3>Paramétrica del Cliente: <strong><?php echo $_POST['Nombre_cliente'];?></strong></h3>
                    <input type="hidden" id="Id_cliente" value="<?php echo $Id_cliente; ?>">                    
                    <input type="hidden" id="nombre_usuario" value="<?php echo $nombre_usuario;?>">
                    <input type="hidden" id="fecha_actual" value="{{date("Y-m-d")}}">
                </div>
                <div class="card-body">
                    <form>@csrf</form>
                    <label><span>Tipo de Cliente:&nbsp;</span> <?php echo $_POST['Nombre_tipo_cliente'];?> &nbsp;&nbsp;&nbsp;&nbsp;<span>Cliente:&nbsp;</span> <?php echo $_POST['Nombre_cliente'];?></label>
                    <input type="hidden" id="total_parametrizaciones_origen_atel" value="<?php if(!empty($listado_parametrizaciones_proceso_origen_atel)){echo count($listado_parametrizaciones_proceso_origen_atel);}?>">
                    <input type="hidden" id="id_parametrizacion_origen_atel_editar">

                    <input type="hidden" id="total_parametrizaciones_calificacion_pcl" value="<?php if(!empty($listado_parametrizaciones_proceso_calificacion_pcl)){echo count($listado_parametrizaciones_proceso_calificacion_pcl);}?>">
                    <input type="hidden" id="id_parametrizacion_calificacion_pcl_editar">

                    <input type="hidden" id="total_parametrizaciones_juntas" value="<?php if(!empty($listado_parametrizaciones_proceso_juntas)){echo count($listado_parametrizaciones_proceso_juntas);}?>">
                    <input type="hidden" id="id_parametrizacion_juntas_editar">
                    
                    <div class="accordion" id="accordionParametrizacion">
                        <a class="btn btn-outline-info" data-toggle="collapse" data-target="#collapseOrigenAtel" role="button" aria-expanded="false" aria-controls="collapseOrigenAtel" id="btn_abrir_parametrica_origen_atel">Origen ATEL</a>
                        <a class="btn btn-outline-info" data-toggle="collapse" data-target="#collapseCalificacionPcl" role="button" aria-expanded="false" aria-controls="collapseCalificacionPcl" id="btn_abrir_parametrica_calificacion_pcl">Calificación PCL</a>
                        <a class="btn btn-outline-info" data-toggle="collapse" data-target="#collapseJuntas" role="button" aria-expanded="false" aria-controls="collapseJuntas" id="btn_abrir_parametrica_juntas">Juntas</a>
                        <br><br>

                        {{-- MOSTRAR LA TABLA DE PARAMETRIACIÓN ACORDE AL PROCESO ORIGEN ATEL --}}
                        <div id="collapseOrigenAtel" class="collapse" data-parent="#accordionParametrizacion">
                            <div class="card-info">
                                <div class="card-header text-center">
                                    <h3>Paramétrica del Proceso Origen ATEL</h3>
                                </div>
                                <div class="card-body">
                                    <?php if($conteo_servicios_proceso_origen_atel > 0): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para guardar y/o editar una parametrización debe hacerlo sobre la misma fila. 
                                                    El sistema al ejecutar la acción correspondiente realizará un recargue para poder ver la información actualizada.
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label ><span>Movimientos: Activos: {{$conteo_activos_inactivos_parametrizaciones_origen_atel[0]->Activos}}</span> - Inactivos: {{$conteo_activos_inactivos_parametrizaciones_origen_atel[0]->Inactivos}} </label>
                                            </div>
                                        </div>
                                        {{-- tabla para descargar el reporte de origen atel --}}
                                        <div class="table-responsive">
                                            <table id="tabla_origen_atel_descarga" class="table table-striped table-bordered d-none" style="width:100%;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Detalle</th>
                                                        <th>N°</th>
                                                        <th>Fecha creación de movimiento</th>
                                                        <th>Servicio asociado</th>
                                                        <th>Estado</th>
                                                        <th>Accion a ejecutar</th>
                                                        <th>Acción antecesora</th>
                                                        <th>Módulo Nuevo</th>
                                                        <th>Módulo Consultar</th>
                                                        <th>Bandeja de Trabajo</th>
                                                        <th>Módulo principal</th>
                                                        <th>Detiene tiempo de gestión</th>
                                                        <th>Equipo de trabajo asociado</th>
                                                        <th>Profesional asignado</th>
                                                        <th>Enviar a</th>
                                                        <th>Bandeja de trabajo destino</th>
                                                        <th>Estado de Facturación</th>
                                                        <th>Movimiento automático</th>
                                                        <th>Tiempo para el movimiento (Días)</th>
                                                        <th>Acción automática</th>
                                                        <th>Tiempo de  alerta (horas)</th>
                                                        <th>Porcentaje alerta (Naranja)</th>
                                                        <th>Porcentaje alerta (Rojo)</th>
                                                        <th>Status paramétrico</th>
                                                        <th>Motivo / Descripción de movimiento</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha actualización de movimiento</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($listado_parametrizaciones_proceso_origen_atel)): ?>
                                                        <?php $conteo_general_proceso_origen_atel = 0;?>
                                                        @foreach ($listado_parametrizaciones_proceso_origen_atel as $parametrizacion_origen_atel_editar)
                                                            <?php $conteo_general_proceso_origen_atel = $conteo_general_proceso_origen_atel + 1;?>
                                                            <tr>
                                                                {{-- detalle --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <a href="javascript:void(0);" class="d-none1" id="bd_editar_fila_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" data-id_fila_parametrizacion_editar="{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-pen text-primary"></i></a>
                                                                        <a href="javascript:void(0);" class="d-none" id="bd_guardar_fila_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    </div>
                                                                </td>
                                                                {{-- n° --}}
                                                                <td><div style="text-align:center;">{{$conteo_general_proceso_origen_atel}}<input type="hidden" id="contador_origen_atel_{{$conteo_general_proceso_origen_atel}}" value="{{$conteo_general_proceso_origen_atel}}"></div></td>
                                                                {{-- fecha creacion movimiento --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->F_creacion_movimiento}}
                                                                </td>
                                                                {{-- servicio asociado --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Nombre_servicio}}
                                                                </td>
                                                                {{-- estado --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Nombre_estado}}
                                                                </td>
                                                                {{-- accion ejecutar --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Nombre_accion}}
                                                                </td>
                                                                {{-- acción antecesora --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Nombre_accion_antecesora}}
                                                                </td>
                                                                {{-- mod nuevo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_origen_atel_editar->Modulo_nuevo == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- mod consultar --}}
                                                                <td>
                                                                    <?php if($parametrizacion_origen_atel_editar->Modulo_consultar == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- bandeja trabajo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_origen_atel_editar->Bandeja_trabajo == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- mod principal --}}
                                                                <td>
                                                                    <?php if($parametrizacion_origen_atel_editar->Modulo_principal == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- detiene tiempo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_origen_atel_editar->Detiene_tiempo_gestion == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- equipo trabajo --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Nombre_equipo_trabajo}}
                                                                </td>
                                                                {{-- profesional asignado --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Nombre_profesional}}
                                                                </td>
                                                                {{-- enviar a --}}
                                                                <td>
                                                                    <?php if($parametrizacion_origen_atel_editar->Enviar_a_bandeja_trabajo_destino == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- bandeja trabajo destino --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Nombre_bandeja_trabajo_destino}}
                                                                </td>
                                                                {{-- estado facturacion --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Estado_facturacion}}
                                                                </td>
                                                                {{-- movimiento automático --}}
                                                                <td>
                                                                    <?php if($parametrizacion_origen_atel_editar->Movimiento_automatico == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- Tiempo para el movimiento (Días) --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Tiempo_movimiento}}
                                                                </td>
                                                                {{-- Acción automática --}}
                                                                <td>
                                                                {{-- tiempo alerta --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Tiempo_alerta}}
                                                                </td>
                                                                {{-- porcentaje alerta naranja --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Porcentaje_alerta_naranja}}
                                                                </td>
                                                                {{-- porcentaje alerta roja --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Porcentaje_alerta_roja}}
                                                                </td>
                                                                
                                                                {{-- status --}}
                                                                <td>
                                                                    <?php if($parametrizacion_origen_atel_editar->Status_parametrico == "Activo"): ?>
                                                                        Activo
                                                                    <?php else: ?>
                                                                        Inactivado
                                                                    <?php endif ?>
                                                                </td>
                                                                {{-- motivo descripcion --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Motivo_descripcion_movimiento}}
                                                                </td>
                                                                {{-- usuairo --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->Nombre_usuario}}
                                                                </td>
                                                                {{-- fecha actualizacion movimiento --}}
                                                                <td>
                                                                    {{$parametrizacion_origen_atel_editar->F_actualizacion_movimiento}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    <?php endif ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- tabla para crear o editar parametrizaciones de origen atel --}}
                                        <div class="table-responsive">
                                            <table id="parametrizar_origen_atel" class="table table-striped table-bordered" style="width:100%;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Detalle</th>
                                                        <th>N°</th>
                                                        <th>Fecha creación de movimiento</th>
                                                        <th>Servicio asociado</th>
                                                        <th>Estado</th>
                                                        <th>Accion a ejecutar</th>
                                                        <th>Acción antecesora</th>
                                                        <th>Módulo Nuevo</th>
                                                        <th>Módulo Consultar</th>
                                                        <th>Bandeja de Trabajo</th>
                                                        <th>Módulo principal</th>
                                                        <th>Detiene tiempo de gestión</th>
                                                        <th>Equipo de trabajo asociado</th>
                                                        <th>Profesional asignado</th>
                                                        <th>Enviar a</th>
                                                        <th>Bandeja de trabajo destino</th>
                                                        <th>Estado de Facturación</th>
                                                        <th>Movimiento automático</th>
                                                        <th>Tiempo para el movimiento (Días)</th>
                                                        <th>Acción automática</th>
                                                        <th>Tiempo de  alerta (horas)</th>
                                                        <th>Porcentaje alerta (Naranja)</th>
                                                        <th>Porcentaje alerta (Rojo)</th>
                                                        <th>Status paramétrico</th>
                                                        <th>Motivo / Descripción de movimiento</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha actualización de movimiento</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_parametrizacion_origen_atel"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($listado_parametrizaciones_proceso_origen_atel)): ?>
                                                        <?php $conteo_general_proceso_origen_atel = 0;?>
                                                        @foreach ($listado_parametrizaciones_proceso_origen_atel as $parametrizacion_origen_atel_editar)
                                                            <?php $conteo_general_proceso_origen_atel = $conteo_general_proceso_origen_atel + 1;?>
                                                            <tr>
                                                                {{-- detalle --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <a href="javascript:void(0);" class="d-none1" id="bd_editar_fila_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" data-id_fila_parametrizacion_editar="{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-pen text-primary"></i></a>
                                                                        <a href="javascript:void(0);" class="d-none" id="bd_guardar_fila_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    </div>
                                                                </td>
                                                                {{-- n° --}}
                                                                <td><div style="text-align:center;">{{$conteo_general_proceso_origen_atel}}<input type="hidden" id="contador_origen_atel_{{$conteo_general_proceso_origen_atel}}" value="{{$conteo_general_proceso_origen_atel}}"></div></td>
                                                                {{-- fecha creacion movimiento --}}
                                                                <td>
                                                                    <input type="date" class="form-control d-none" id="bd_fecha_creacion_movimiento_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->F_creacion_movimiento}}" readonly>
                                                                    <span style="width:156px;" class="form-control" readonly>{{date("d/m/Y", strtotime($parametrizacion_origen_atel_editar->F_creacion_movimiento))}}</span>
                                                                </td>
                                                                {{-- servicio asociado --}}
                                                                <input type="hidden" id="bd_id_servicio_asociado_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Servicio_asociado}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_servicio_asociado_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_servicio_asociado_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Servicio_asociado}}" selected>{{$parametrizacion_origen_atel_editar->Nombre_servicio}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- estado --}}
                                                                <input type="hidden" id="bd_id_estado_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Estado}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_estado_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_estado_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Estado}}" selected>{{$parametrizacion_origen_atel_editar->Nombre_estado}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- accion ejecutar --}}
                                                                <input type="hidden" id="bd_id_accion_ejecutar_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Accion_ejecutar}}">
                                                                <td>
                                                                    <select style="width:240px;" disabled class="custom-select bd_accion_ejecutar_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_accion_ejecutar_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Accion_ejecutar}}" selected>{{$parametrizacion_origen_atel_editar->Nombre_accion}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- acción antecesora --}}
                                                                <input type="hidden" id="bd_id_accion_antecesora_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Accion_antecesora}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_accion_antecesora_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_accion_antecesora_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option>Ninguno</option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Accion_antecesora}}" selected>{{$parametrizacion_origen_atel_editar->Nombre_accion_antecesora}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- mod nuevo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_nuevo_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_origen_atel_editar->Modulo_nuevo == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- mod consultar --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_consultar_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_origen_atel_editar->Modulo_consultar == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- bandeja trabajo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_bandeja_trabajo_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_origen_atel_editar->Bandeja_trabajo == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- mod principal --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_principal_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_origen_atel_editar->Modulo_principal == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- detiene tiempo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_detiene_tiempo_gestion_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_origen_atel_editar->Detiene_tiempo_gestion == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- equipo trabajo --}}
                                                                <input type="hidden" id="bd_id_equipo_trabajo_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Equipo_trabajo}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_equipo_trabajo_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_equipo_trabajo_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Equipo_trabajo}}" selected>{{$parametrizacion_origen_atel_editar->Nombre_equipo_trabajo}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- profesional asignado --}}
                                                                <input type="hidden" id="bd_id_profesional_asignado_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Profesional_asignado}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_listado_profesionales_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_listado_profesionales_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Profesional_asignado}}" selected>{{$parametrizacion_origen_atel_editar->Nombre_profesional}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- enviar a --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_enviar_a_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_origen_atel_editar->Enviar_a_bandeja_trabajo_destino == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- bandeja trabajo destino --}}
                                                                <input type="hidden" id="bd_id_bandeja_trabajo_destino_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Bandeja_trabajo_destino}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_bandeja_trabajo_destino_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_bandeja_trabajo_destino_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Bandeja_trabajo_destino}}" selected>{{$parametrizacion_origen_atel_editar->Nombre_bandeja_trabajo_destino}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- estado facturacion --}}
                                                                <td>
                                                                    {{-- <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_copia_facturacion_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_origen_atel_editar->Estado_facturacion == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div> --}}
                                                                    <input type="text" class="form-control d-none" id="bd_estado_facturacion_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Estado_facturacion}}" disabled>
                                                                    <span style="width:156px; height:auto;" class="form-control" readonly>{{$parametrizacion_origen_atel_editar->Estado_facturacion}}</span>
                                                                </td>
                                                                {{-- movimiento automático --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_movimiento_automatico_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" data-id_movimiento_automatico_origen_atel="{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_origen_atel_editar->Movimiento_automatico == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- Tiempo para el movimiento (Días) --}}
                                                                <td>
                                                                    <input style="width:140px;" type="number" class="form-control d-none" id="bd_tiempo_movimiento_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Tiempo_movimiento}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_origen_atel_editar->Tiempo_movimiento}}</span>
                                                                </td>
                                                                {{-- Acción automática --}}
                                                                <input type="hidden" id="bd_id_accion_automatica_orgien_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Accion_automatica}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_accion_automatica_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_accion_automatica_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Accion_automatica}}" selected>{{$parametrizacion_origen_atel_editar->Nombre_accion_automatica}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- tiempo alerta --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_tiempo_alerta_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Tiempo_alerta}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_origen_atel_editar->Tiempo_alerta}}</span>
                                                                </td>
                                                                {{-- porcentaje alerta naranja --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_porcentaje_alerta_naranja_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Porcentaje_alerta_naranja}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_origen_atel_editar->Porcentaje_alerta_naranja}}</span>
                                                                </td>
                                                                {{-- porcentaje alerta roja --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_porcentaje_alerta_roja_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Porcentaje_alerta_roja}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_origen_atel_editar->Porcentaje_alerta_roja}}</span>
                                                                </td>
                                                                {{-- status --}}
                                                                <input type="hidden" id="bd_id_status_parametrico_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Status_parametrico}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_status_parametrico_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" id="bd_status_parametrico_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_origen_atel_editar->Status_parametrico}}" selected>{{$parametrizacion_origen_atel_editar->Status_parametrico}}</option>                                                                        
                                                                    </select>
                                                                </td>
                                                                {{-- motivo descripcion --}}
                                                                <td>
                                                                    <textarea style="width:140px;" class="form-control" id="bd_motivo_movimiento_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" cols="150" rows="4" disabled>{{$parametrizacion_origen_atel_editar->Motivo_descripcion_movimiento}}</textarea>
                                                                </td>
                                                                {{-- usuario --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_nombre_usuario_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->Nombre_usuario}}" disabled>
                                                                    <span style="width:200px; height: auto;" class="form-control" readonly>{{$parametrizacion_origen_atel_editar->Nombre_usuario}}</span>                                                                    
                                                                </td>
                                                                {{-- fecha actualizacion movimiento --}}
                                                                <td>
                                                                    <input type="date" class="form-control d-none" id="bd_fecha_actualizacion_movimiento_origen_atel_{{$parametrizacion_origen_atel_editar->Id_parametrizacion}}" value="{{$parametrizacion_origen_atel_editar->F_actualizacion_movimiento}}" disabled>
                                                                    <span style="width:156px;" class="form-control" readonly>{{date("d/m/Y", strtotime($parametrizacion_origen_atel_editar->F_actualizacion_movimiento))}}</span>                                                                    
                                                                </td>
                                                                <td>
                                                                    <div style="text-align:center;">-<div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    <?php endif ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-2 d-none" id="mostrar_mensaje_agrego_parametrizacion_origen_atel">
                                            <div  class="col-12">
                                                <div class="form-group">
                                                    <div class="mensaje_agrego_parametrizacion_origen_atel alert" role="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede crear una parametrización debido a que no hay 
                                                    contratado ningún servcio para este proceso.
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        {{-- MOSTRAR LA TABLA DE PARAMETRIACIÓN ACORDE AL PROCESO CALIFICACIÓN PCL --}}
                        <div id="collapseCalificacionPcl" class="collapse" data-parent="#accordionParametrizacion">
                            <div class="card-info">
                                <div class="card-header text-center">
                                    <h3>Paramétrica del Proceso Calificación PCL</h3>
                                </div>
                                <div class="card-body">
                                    <?php if($conteo_servicios_proceso_calificacion_pcl > 0): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para guardar y/o editar una parametrización debe hacerlo sobre la misma fila. 
                                                    El sistema al ejecutar la acción correspondiente realizará un recargue para poder ver la información actualizada.
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label ><span>Movimientos: Activos: {{$conteo_activos_inactivos_parametrizaciones_calificacion_pcl[0]->Activos}}</span> - Inactivos: {{$conteo_activos_inactivos_parametrizaciones_calificacion_pcl[0]->Inactivos}} </label>
                                            </div>
                                        </div>
                                        {{-- tabla para la descarga del reporte pcl --}}
                                        <div class="table-responsive">
                                            <table id="tabla_calificacion_pcl_descarga" class="table table-striped table-bordered d-none" style="width:100%;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Detalle</th>
                                                        <th>N°</th>
                                                        <th>Fecha creación de movimiento</th>
                                                        <th>Servicio asociado</th>
                                                        <th>Estado</th>
                                                        <th>Accion a ejecutar</th>
                                                        <th>Acción antecesora</th>
                                                        <th>Módulo Nuevo</th>
                                                        <th>Módulo Consultar</th>
                                                        <th>Bandeja de Trabajo</th>
                                                        <th>Módulo principal</th>
                                                        <th>Detiene tiempo de gestión</th>
                                                        <th>Equipo de trabajo asociado</th>
                                                        <th>Profesional asignado</th>
                                                        <th>Enviar a</th>
                                                        <th>Bandeja de trabajo destino</th>
                                                        <th>Estado de Facturación</th>
                                                        <th>Movimiento automático</th>
                                                        <th>Tiempo para el movimiento (Días)</th>
                                                        <th>Acción automática</th>
                                                        <th>Tiempo de  alerta (horas)</th>
                                                        <th>Porcentaje alerta (Naranja)</th>
                                                        <th>Porcentaje alerta (Rojo)</th>
                                                        <th>Status paramétrico</th>
                                                        <th>Motivo / Descripción de movimiento</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha actualización de movimiento</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($listado_parametrizaciones_proceso_calificacion_pcl)): ?>
                                                        <?php $conteo_general_proceso_calificacion_pcl = 0;?>
                                                        @foreach ($listado_parametrizaciones_proceso_calificacion_pcl as $parametrizacion_calificacion_pcl_editar)
                                                            <?php $conteo_general_proceso_calificacion_pcl= $conteo_general_proceso_calificacion_pcl + 1;?>
                                                            <tr>
                                                                {{-- detalle --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <a href="javascript:void(0);" class="d-none1" id="bd_editar_fila_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" data-id_fila_parametrizacion_editar="{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-pen text-primary"></i></a>
                                                                        <a href="javascript:void(0);" class="d-none" id="bd_guardar_fila_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    </div>
                                                                </td>
                                                                {{-- n° --}}
                                                                <td><div style="text-align:center;">{{$conteo_general_proceso_calificacion_pcl}}<input type="hidden" id="contador_calificacion_pcl_{{$conteo_general_proceso_calificacion_pcl}}" value="{{$conteo_general_proceso_calificacion_pcl}}"></div></td>
                                                                {{-- fecha creacion movimiento --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->F_creacion_movimiento}}
                                                                </td>
                                                                {{-- servicio asociado --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_servicio}}
                                                                </td>
                                                                {{-- estado --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_estado}}
                                                                </td>
                                                                {{-- accion ejecutar --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_accion}}
                                                                </td>
                                                                {{-- acción antecesora --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_accion_antecesora}}
                                                                </td>
                                                                {{-- mod nuevo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_calificacion_pcl_editar->Modulo_nuevo == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- mod consultar --}}
                                                                <td>
                                                                    <?php if($parametrizacion_calificacion_pcl_editar->Modulo_consultar == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- bandeja trabajo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_calificacion_pcl_editar->Bandeja_trabajo == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- mod principal --}}
                                                                <td>
                                                                    <?php if($parametrizacion_calificacion_pcl_editar->Modulo_principal == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- detiene tiempo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_calificacion_pcl_editar->Detiene_tiempo_gestion == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- equipo trabajo --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_equipo_trabajo}}
                                                                </td>
                                                                {{-- profesional asignado --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_profesional}}
                                                                </td>
                                                                {{-- enviar a --}}
                                                                <td>
                                                                    <?php if($parametrizacion_calificacion_pcl_editar->Enviar_a_bandeja_trabajo_destino == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- bandeja trabajo destino --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_bandeja_trabajo_destino}}
                                                                </td>
                                                                {{-- estado facturacion --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Estado_facturacion}}
                                                                </td>
                                                                {{-- movimiento automático --}}
                                                                <td>
                                                                    <?php if($parametrizacion_calificacion_pcl_editar->Movimiento_automatico == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- Tiempo para el movimiento (Días) --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Tiempo_movimiento}}
                                                                </td>
                                                                {{-- Acción automática --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_accion_automatica}}
                                                                </td>
                                                                {{-- tiempo alerta --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Tiempo_alerta}}
                                                                </td>
                                                                {{-- porcentaje alerta naranja --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Porcentaje_alerta_naranja}}
                                                                </td>
                                                                {{-- porcentaje alerta roja --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Porcentaje_alerta_roja}}
                                                                </td>
                                                                {{-- status --}}
                                                                <td>                                                                    
                                                                    <?php if($parametrizacion_calificacion_pcl_editar->Status_parametrico == "Activo"): ?>
                                                                        Activo
                                                                    <?php else: ?>
                                                                        Inactivado
                                                                    <?php endif ?>
                                                                </td>
                                                                {{-- motivo descripcion --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Motivo_descripcion_movimiento}}
                                                                </td>
                                                                {{-- usuairo --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->Nombre_usuario}}
                                                                </td>
                                                                {{-- fecha actualizacion movimiento --}}
                                                                <td>
                                                                    {{$parametrizacion_calificacion_pcl_editar->F_actualizacion_movimiento}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    <?php endif ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- tabla para crear o editar parametrizaciones de pcl --}}
                                        <div class="table-responsive">
                                            <table id="parametrizar_calificacion_pcl" class="table table-striped table-bordered" style="width:100%;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Detalle</th>
                                                        <th>N°</th>
                                                        <th>Fecha creación de movimiento</th>
                                                        <th>Servicio asociado</th>
                                                        <th>Estado</th>
                                                        <th>Accion a ejecutar</th>
                                                        <th>Acción antecesora</th>
                                                        <th>Módulo Nuevo</th>
                                                        <th>Módulo Consultar</th>
                                                        <th>Bandeja de Trabajo</th>
                                                        <th>Módulo principal</th>
                                                        <th>Detiene tiempo de gestión</th>
                                                        <th>Equipo de trabajo asociado</th>
                                                        <th>Profesional asignado</th>
                                                        <th>Enviar a</th>
                                                        <th>Bandeja de trabajo destino</th>
                                                        <th>Estado de Facturación</th>
                                                        <th>Movimiento automático</th>
                                                        <th>Tiempo para el movimiento (Días)</th>
                                                        <th>Acción automática</th>
                                                        <th>Tiempo de  alerta (horas)</th>
                                                        <th>Porcentaje alerta (Naranja)</th>
                                                        <th>Porcentaje alerta (Rojo)</th>
                                                        <th>Status paramétrico</th>
                                                        <th>Motivo / Descripción de movimiento</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha actualización de movimiento</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_parametrizacion_calificacion_pcl"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($listado_parametrizaciones_proceso_calificacion_pcl)): ?>
                                                        <?php $conteo_general_proceso_calificacion_pcl = 0;?>
                                                        @foreach ($listado_parametrizaciones_proceso_calificacion_pcl as $parametrizacion_calificacion_pcl_editar)
                                                            <?php $conteo_general_proceso_calificacion_pcl= $conteo_general_proceso_calificacion_pcl + 1;?>
                                                            <tr>
                                                                {{-- detalle --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <a href="javascript:void(0);" class="d-none1" id="bd_editar_fila_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" data-id_fila_parametrizacion_editar="{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-pen text-primary"></i></a>
                                                                        <a href="javascript:void(0);" class="d-none" id="bd_guardar_fila_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    </div>
                                                                </td>
                                                                {{-- n° --}}
                                                                <td><div style="text-align:center;">{{$conteo_general_proceso_calificacion_pcl}}<input type="hidden" id="contador_calificacion_pcl_{{$conteo_general_proceso_calificacion_pcl}}" value="{{$conteo_general_proceso_calificacion_pcl}}"></div></td>
                                                                {{-- fecha creacion movimiento --}}
                                                                <td>
                                                                    <input type="date" class="form-control d-none" id="bd_fecha_creacion_movimiento_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->F_creacion_movimiento}}" readonly>
                                                                    <span style="width:156px;" class="form-control" readonly>{{date("d/m/Y", strtotime($parametrizacion_calificacion_pcl_editar->F_creacion_movimiento))}}</span>
                                                                </td>
                                                                {{-- servicio asociado --}}
                                                                <input type="hidden" id="bd_id_servicio_asociado_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Servicio_asociado}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_servicio_asociado_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_servicio_asociado_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Servicio_asociado}}" selected>{{$parametrizacion_calificacion_pcl_editar->Nombre_servicio}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- estado --}}
                                                                <input type="hidden" id="bd_id_estado_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Estado}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_estado_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_estado_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Estado}}" selected>{{$parametrizacion_calificacion_pcl_editar->Nombre_estado}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- accion ejecutar --}}
                                                                <input type="hidden" id="bd_id_accion_ejecutar_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Accion_ejecutar}}">
                                                                <td>
                                                                    <select style="width:240px;" disabled class="custom-select bd_accion_ejecutar_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_accion_ejecutar_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Accion_ejecutar}}" selected>{{$parametrizacion_calificacion_pcl_editar->Nombre_accion}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- acción antecesora --}}
                                                                <input type="hidden" id="bd_id_accion_antecesora_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Accion_antecesora}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_accion_antecesora_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_accion_antecesora_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Accion_antecesora}}" selected>{{$parametrizacion_calificacion_pcl_editar->Nombre_accion_antecesora}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- mod nuevo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_nuevo_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_calificacion_pcl_editar->Modulo_nuevo == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- mod consultar --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_consultar_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_calificacion_pcl_editar->Modulo_consultar == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- bandeja trabajo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_bandeja_trabajo_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_calificacion_pcl_editar->Bandeja_trabajo == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- mod principal --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_principal_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_calificacion_pcl_editar->Modulo_principal == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- detiene tiempo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_detiene_tiempo_gestion_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_calificacion_pcl_editar->Detiene_tiempo_gestion == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- equipo trabajo --}}
                                                                <input type="hidden" id="bd_id_equipo_trabajo_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Equipo_trabajo}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_equipo_trabajo_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_equipo_trabajo_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Equipo_trabajo}}" selected>{{$parametrizacion_calificacion_pcl_editar->Nombre_equipo_trabajo}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- profesional asignado --}}
                                                                <input type="hidden" id="bd_id_profesional_asignado_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Profesional_asignado}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_listado_profesionales_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_listado_profesionales_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Profesional_asignado}}" selected>{{$parametrizacion_calificacion_pcl_editar->Nombre_profesional}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- enviar a --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_enviar_a_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_calificacion_pcl_editar->Enviar_a_bandeja_trabajo_destino == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- bandeja trabajo destino --}}
                                                                <input type="hidden" id="bd_id_bandeja_trabajo_destino_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Bandeja_trabajo_destino}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_bandeja_trabajo_destino_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_bandeja_trabajo_destino_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Bandeja_trabajo_destino}}" selected>{{$parametrizacion_calificacion_pcl_editar->Nombre_bandeja_trabajo_destino}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- estado facturacion --}}
                                                                <td>
                                                                    {{-- <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_copia_facturacion_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_calificacion_pcl_editar->Estado_facturacion == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div> --}}
                                                                    <input type="text" class="form-control d-none" id="bd_estado_facturacion_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Estado_facturacion}}" disabled>
                                                                    <span style="width:156px; height:auto;" class="form-control" readonly>{{$parametrizacion_calificacion_pcl_editar->Estado_facturacion}}</span>
                                                                </td>
                                                                {{-- movimiento automático --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_movimiento_automatico_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" data-id_movimiento_automatico_calificacion_pcl="{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_calificacion_pcl_editar->Movimiento_automatico == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- Tiempo para el movimiento (Días) --}}
                                                                <td>
                                                                    <input style="width:140px;" type="number" class="form-control d-none" id="bd_tiempo_movimiento_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Tiempo_movimiento}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_calificacion_pcl_editar->Tiempo_movimiento}}</span>
                                                                </td>
                                                                {{-- Acción automática --}}
                                                                <input type="hidden" id="bd_id_accion_automatica_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Accion_automatica}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_accion_automatica_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_accion_automatica_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Accion_automatica}}" selected>{{$parametrizacion_calificacion_pcl_editar->Nombre_accion_automatica}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- tiempo alerta --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_tiempo_alerta_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Tiempo_alerta}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_calificacion_pcl_editar->Tiempo_alerta}}</span>
                                                                </td>
                                                                {{-- porcentaje alerta naranja --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_porcentaje_alerta_naranja_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Porcentaje_alerta_naranja}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_calificacion_pcl_editar->Porcentaje_alerta_naranja}}</span>
                                                                </td>
                                                                {{-- porcentaje alerta roja --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_porcentaje_alerta_roja_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Porcentaje_alerta_roja}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_calificacion_pcl_editar->Porcentaje_alerta_roja}}</span>
                                                                </td>
                                                                
                                                                {{-- status --}}
                                                                <input type="hidden" id="bd_id_status_parametrico_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Status_parametrico}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_status_parametrico_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" id="bd_status_parametrico_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_calificacion_pcl_editar->Status_parametrico}}" selected>{{$parametrizacion_calificacion_pcl_editar->Status_parametrico}}</option>                                                                        
                                                                    </select> 
                                                                </td>
                                                                {{-- motivo descripcion --}}
                                                                <td>
                                                                    <textarea style="width:140px;" class="form-control" id="bd_motivo_movimiento_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" cols="150" rows="4" disabled>{{$parametrizacion_calificacion_pcl_editar->Motivo_descripcion_movimiento}}</textarea>
                                                                </td>
                                                                {{-- usuario --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_nombre_usuario_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->Nombre_usuario}}" disabled>
                                                                    <span style="width:200px; height: auto;" class="form-control" readonly>{{$parametrizacion_calificacion_pcl_editar->Nombre_usuario}}</span>
                                                                </td>
                                                                {{-- fecha actualizacion movimiento --}}
                                                                <td>
                                                                    <input type="date" class="form-control d-none" id="bd_fecha_actualizacion_movimiento_calificacion_pcl_{{$parametrizacion_calificacion_pcl_editar->Id_parametrizacion}}" value="{{$parametrizacion_calificacion_pcl_editar->F_actualizacion_movimiento}}" disabled>
                                                                    <span style="width:156px;" class="form-control" readonly>{{date("d/m/Y", strtotime($parametrizacion_calificacion_pcl_editar->F_actualizacion_movimiento))}}</span>                                                                    
                                                                </td>
                                                                <td>
                                                                    <div style="text-align:center;">-<div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    <?php endif ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-2 d-none" id="mostrar_mensaje_agrego_parametrizacion_calificacion_pcl">
                                            <div  class="col-12">
                                                <div class="form-group">
                                                    <div class="mensaje_agrego_parametrizacion_calificacion_pcl alert" role="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede crear una parametrización debido a que no hay 
                                                    contratado ningún servcio para este proceso.
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                        {{-- MOSTRAR LA TABLA DE PARAMETRIACIÓN ACORDE AL PROCESO JUNTAS --}}
                        <div id="collapseJuntas" class="collapse" data-parent="#accordionParametrizacion">
                            <div class="card-info">
                                <div class="card-header text-center">
                                    <h3>Paramétrica del Proceso Juntas</h3>
                                </div>
                                <div class="card-body">
                                    <?php if($conteo_servicios_proceso_juntas > 0): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para guardar y/o editar una parametrización debe hacerlo sobre la misma fila. 
                                                    El sistema al ejecutar la acción correspondiente realizará un recargue para poder ver la información actualizada.
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label ><span>Movimientos: Activos: {{$conteo_activos_inactivos_parametrizaciones_juntas[0]->Activos}}</span> - Inactivos: {{$conteo_activos_inactivos_parametrizaciones_juntas[0]->Inactivos}} </label>
                                            </div>
                                        </div>
                                        {{-- tabla para la descarga del reporte juntas --}}
                                        <div class="table-responsive">
                                            <table id="tabla_juntas_descarga" class="table table-striped table-bordered d-none" style="width:100%;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Detalle</th>
                                                        <th>N°</th>
                                                        <th>Fecha creación de movimiento</th>
                                                        <th>Servicio asociado</th>
                                                        <th>Estado</th>
                                                        <th>Accion a ejecutar</th>
                                                        <th>Acción antecesora</th>
                                                        <th>Módulo Nuevo</th>
                                                        <th>Módulo Consultar</th>
                                                        <th>Bandeja de Trabajo</th>
                                                        <th>Módulo principal</th>
                                                        <th>Detiene tiempo de gestión</th>
                                                        <th>Equipo de trabajo asociado</th>
                                                        <th>Profesional asignado</th>
                                                        <th>Enviar a</th>
                                                        <th>Bandeja de trabajo destino</th>
                                                        <th>Estado de Facturación</th>
                                                        <th>Movimiento automático</th>
                                                        <th>Tiempo para el movimiento (Días)</th>
                                                        <th>Acción automática</th>
                                                        <th>Tiempo de  alerta (horas)</th>
                                                        <th>Porcentaje alerta (Naranja)</th>
                                                        <th>Porcentaje alerta (Rojo)</th>
                                                        <th>Status paramétrico</th>
                                                        <th>Motivo / Descripción de movimiento</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha actualización de movimiento</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($listado_parametrizaciones_proceso_juntas)): ?>
                                                        <?php $conteo_general_proceso_juntas = 0;?>
                                                        @foreach ($listado_parametrizaciones_proceso_juntas as $parametrizacion_juntas_editar)
                                                            <?php $conteo_general_proceso_juntas= $conteo_general_proceso_juntas + 1;?>
                                                            <tr>
                                                                {{-- detalle --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <a href="javascript:void(0);" class="d-none1" id="bd_editar_fila_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" data-id_fila_parametrizacion_editar="{{$parametrizacion_juntas_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-pen text-primary"></i></a>
                                                                        <a href="javascript:void(0);" class="d-none" id="bd_guardar_fila_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    </div>
                                                                </td>
                                                                {{-- n° --}}
                                                                <td><div style="text-align:center;">{{$conteo_general_proceso_juntas}}<input type="hidden" id="contador_juntas_{{$conteo_general_proceso_juntas}}" value="{{$conteo_general_proceso_juntas}}"></div></td>
                                                                {{-- fecha creacion movimiento --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->F_creacion_movimiento}}
                                                                </td>
                                                                {{-- servicio asociado --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_servicio}}
                                                                </td>
                                                                {{-- estado --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_estado}}
                                                                </td>
                                                                {{-- accion ejecutar --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_accion}}
                                                                </td>
                                                                {{-- acción antecesora --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_accion_antecesora}}
                                                                </td>
                                                                {{-- mod nuevo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_juntas_editar->Modulo_nuevo == "Si"):?>
                                                                        Si
                                                                    <?php else:?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- mod consultar --}}
                                                                <td>
                                                                    <?php if($parametrizacion_juntas_editar->Modulo_consultar == "Si"):?>
                                                                        Si
                                                                    <?php else:?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- bandeja trabajo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_juntas_editar->Bandeja_trabajo == "Si"):?>
                                                                        Si
                                                                    <?php else:?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- mod principal --}}
                                                                <td>
                                                                    <?php if($parametrizacion_juntas_editar->Modulo_principal == "Si"):?>
                                                                        Si
                                                                    <?php else:?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- detiene tiempo --}}
                                                                <td>
                                                                    <?php if($parametrizacion_juntas_editar->Detiene_tiempo_gestion == "Si"):?>
                                                                        Si
                                                                    <?php else:?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- equipo trabajo --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_equipo_trabajo}}
                                                                </td>
                                                                {{-- profesional asignado --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_profesional}}
                                                                </td>
                                                                {{-- enviar a --}}
                                                                <td>
                                                                    <?php if($parametrizacion_juntas_editar->Enviar_a_bandeja_trabajo_destino == "Si"):?>
                                                                        Si
                                                                    <?php else:?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- bandeja trabajo destino --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_bandeja_trabajo_destino}}
                                                                </td>
                                                                {{-- estado facturacion --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Estado_facturacion}}
                                                                </td>
                                                                {{-- movimiento automático --}}
                                                                <td>
                                                                    <?php if($parametrizacion_juntas_editar->Movimiento_automatico == "Si"):?>
                                                                        Si
                                                                    <?php else: ?>
                                                                        No
                                                                    <?php endif?>
                                                                </td>
                                                                {{-- Tiempo para el movimiento (Días) --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Tiempo_movimiento}}
                                                                </td>
                                                                {{-- Acción automática --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_accion_automatica}}
                                                                </td>
                                                                {{-- tiempo alerta --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Tiempo_alerta}}
                                                                </td>
                                                                {{-- porcentaje alerta naranja --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Porcentaje_alerta_naranja}}
                                                                </td>
                                                                {{-- porcentaje alerta roja --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Porcentaje_alerta_roja}}
                                                                </td>
                                                                
                                                                {{-- status --}}
                                                                <td>
                                                                    <?php if($parametrizacion_juntas_editar->Status_parametrico == "Activo"): ?>
                                                                        Activo
                                                                    <?php else: ?>
                                                                        Inactivado
                                                                    <?php endif ?>
                                                                </td>
                                                                {{-- motivo descripcion --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Motivo_descripcion_movimiento}}
                                                                </td>
                                                                {{-- usuairo --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->Nombre_usuario}}
                                                                </td>
                                                                {{-- fecha actualizacion movimiento --}}
                                                                <td>
                                                                    {{$parametrizacion_juntas_editar->F_actualizacion_movimiento}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    <?php endif ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- tabla para crear o editar parametrizaciones de juntas --}}
                                        <div class="table-responsive">
                                            <table id="parametrizar_juntas" class="table table-striped table-bordered" style="width:100%;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>Detalle</th>
                                                        <th>N°</th>
                                                        <th>Fecha creación de movimiento</th>
                                                        <th>Servicio asociado</th>
                                                        <th>Estado</th>
                                                        <th>Accion a ejecutar</th>
                                                        <th>Acción antecesora</th>
                                                        <th>Módulo Nuevo</th>
                                                        <th>Módulo Consultar</th>
                                                        <th>Bandeja de Trabajo</th>
                                                        <th>Módulo principal</th>
                                                        <th>Detiene tiempo de gestión</th>
                                                        <th>Equipo de trabajo asociado</th>
                                                        <th>Profesional asignado</th>
                                                        <th>Enviar a</th>
                                                        <th>Bandeja de trabajo destino</th>
                                                        <th>Estado de Facturación</th>
                                                        <th>Movimiento automático</th>
                                                        <th>Tiempo para el movimiento (Días)</th>
                                                        <th>Acción automática</th>
                                                        <th>Tiempo de  alerta (horas)</th>
                                                        <th>Porcentaje alerta (Naranja)</th>
                                                        <th>Porcentaje alerta (Rojo)</th>
                                                        <th>Status paramétrico</th>
                                                        <th>Motivo / Descripción de movimiento</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha actualización de movimiento</th>
                                                        <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_parametrizacion_juntas"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(!empty($listado_parametrizaciones_proceso_juntas)): ?>
                                                        <?php $conteo_general_proceso_juntas = 0;?>
                                                        @foreach ($listado_parametrizaciones_proceso_juntas as $parametrizacion_juntas_editar)
                                                            <?php $conteo_general_proceso_juntas= $conteo_general_proceso_juntas + 1;?>
                                                            <tr>
                                                                {{-- detalle --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <a href="javascript:void(0);" class="d-none1" id="bd_editar_fila_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" data-id_fila_parametrizacion_editar="{{$parametrizacion_juntas_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-pen text-primary"></i></a>
                                                                        <a href="javascript:void(0);" class="d-none" id="bd_guardar_fila_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    </div>
                                                                </td>
                                                                {{-- n° --}}
                                                                <td><div style="text-align:center;">{{$conteo_general_proceso_juntas}}<input type="hidden" id="contador_juntas_{{$conteo_general_proceso_juntas}}" value="{{$conteo_general_proceso_juntas}}"></div></td>
                                                                {{-- fecha creacion movimiento --}}
                                                                <td>
                                                                    <input type="date" class="form-control d-none" id="bd_fecha_creacion_movimiento_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->F_creacion_movimiento}}" readonly>
                                                                    <span style="width:156px;" class="form-control" readonly>{{date("d/m/Y", strtotime($parametrizacion_juntas_editar->F_creacion_movimiento))}}</span>                                                                    
                                                                </td>
                                                                {{-- servicio asociado --}}
                                                                <input type="hidden" id="bd_id_servicio_asociado_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Servicio_asociado}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_servicio_asociado_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_servicio_asociado_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Servicio_asociado}}" selected>{{$parametrizacion_juntas_editar->Nombre_servicio}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- estado --}}
                                                                <input type="hidden" id="bd_id_estado_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Estado}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_estado_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_estado_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Estado}}" selected>{{$parametrizacion_juntas_editar->Nombre_estado}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- accion ejecutar --}}
                                                                <input type="hidden" id="bd_id_accion_ejecutar_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Accion_ejecutar}}">
                                                                <td>
                                                                    <select style="width:240px;" disabled class="custom-select bd_accion_ejecutar_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_accion_ejecutar_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Accion_ejecutar}}" selected>{{$parametrizacion_juntas_editar->Nombre_accion}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- acción antecesora --}}
                                                                <input type="hidden" id="bd_id_accion_antecesora_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Accion_antecesora}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_accion_antecesora_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_accion_antecesora_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Accion_antecesora}}" selected>{{$parametrizacion_juntas_editar->Nombre_accion_antecesora}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- mod nuevo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_nuevo_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_juntas_editar->Modulo_nuevo == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- mod consultar --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_consultar_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_juntas_editar->Modulo_consultar == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- bandeja trabajo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_bandeja_trabajo_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_juntas_editar->Bandeja_trabajo == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- mod principal --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_modulo_principal_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_juntas_editar->Modulo_principal == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- detiene tiempo --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_detiene_tiempo_gestion_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_juntas_editar->Detiene_tiempo_gestion == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- equipo trabajo --}}
                                                                <input type="hidden" id="bd_id_equipo_trabajo_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Equipo_trabajo}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_equipo_trabajo_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_equipo_trabajo_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Equipo_trabajo}}" selected>{{$parametrizacion_juntas_editar->Nombre_equipo_trabajo}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- profesional asignado --}}
                                                                <input type="hidden" id="bd_id_profesional_asignado_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Profesional_asignado}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_listado_profesionales_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_listado_profesionales_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Profesional_asignado}}" selected>{{$parametrizacion_juntas_editar->Nombre_profesional}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- enviar a --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_enviar_a_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_juntas_editar->Enviar_a_bandeja_trabajo_destino == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- bandeja trabajo destino --}}
                                                                <input type="hidden" id="bd_id_bandeja_trabajo_destino_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Bandeja_trabajo_destino}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_bandeja_trabajo_destino_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_bandeja_trabajo_destino_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Bandeja_trabajo_destino}}" selected>{{$parametrizacion_juntas_editar->Nombre_bandeja_trabajo_destino}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- estado facturacion --}}
                                                                <td>
                                                                    {{-- <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_copia_facturacion_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_juntas_editar->Estado_facturacion == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div> --}}
                                                                    <input type="text" class="form-control d-none" id="bd_estado_facturacion_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Estado_facturacion}}" disabled>
                                                                    <span style="width:156px; height:auto;" class="form-control" readonly>{{$parametrizacion_juntas_editar->Estado_facturacion}}</span>
                                                                </td>
                                                                {{-- movimiento automático --}}
                                                                <td>
                                                                    <div style="text-align:center;">
                                                                        <input type="checkbox" class="scales" id="bd_movimiento_automatico_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" data-id_movimiento_automatico_juntas="{{$parametrizacion_juntas_editar->Id_parametrizacion}}"
                                                                        <?php if($parametrizacion_juntas_editar->Movimiento_automatico == "Si"):?>
                                                                            checked
                                                                        <?php endif?>
                                                                        disabled>
                                                                    </div>
                                                                </td>
                                                                {{-- Tiempo para el movimiento (Días) --}}
                                                                <td>
                                                                    <input style="width:140px;" type="number" class="form-control d-none" id="bd_tiempo_movimiento_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Tiempo_movimiento}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_juntas_editar->Tiempo_movimiento}}</span>                                                                    
                                                                </td>
                                                                {{-- Acción automática --}}
                                                                <input type="hidden" id="bd_id_accion_automatica_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Accion_automatica}}">
                                                                <td>
                                                                    <select style="width:240px;" class="custom-select bd_accion_automatica_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_accion_automatica_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Accion_automatica}}" selected>{{$parametrizacion_juntas_editar->Nombre_accion_automatica}}</option>
                                                                    </select>
                                                                </td>
                                                                {{-- tiempo alerta --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_tiempo_alerta_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Tiempo_alerta}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_juntas_editar->Tiempo_alerta}}</span>
                                                                </td>
                                                                {{-- porcentaje alerta naranja --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_porcentaje_alerta_naranja_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Porcentaje_alerta_naranja}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_juntas_editar->Porcentaje_alerta_naranja}}</span>
                                                                </td>
                                                                {{-- porcentaje alerta roja --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_porcentaje_alerta_roja_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Porcentaje_alerta_roja}}" disabled>
                                                                    <span style="width:140px;" class="form-control" readonly>{{$parametrizacion_juntas_editar->Porcentaje_alerta_roja}}</span>
                                                                </td>
                                                                {{-- status --}}
                                                                <input type="hidden" id="bd_id_status_parametrico_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Status_parametrico}}">
                                                                <td>
                                                                    <select style="width:140px;" class="custom-select bd_status_parametrico_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" id="bd_status_parametrico_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" disabled>
                                                                        <option></option>
                                                                        <option value="{{$parametrizacion_juntas_editar->Status_parametrico}}" selected>{{$parametrizacion_juntas_editar->Status_parametrico}}</option>                                                                        
                                                                    </select> 
                                                                </td>
                                                                {{-- motivo descripcion --}}
                                                                <td>
                                                                    <textarea style="width:140px;" class="form-control" id="bd_motivo_movimiento_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" cols="150" rows="4" disabled>{{$parametrizacion_juntas_editar->Motivo_descripcion_movimiento}}</textarea>
                                                                </td>
                                                                {{-- usuario --}}
                                                                <td>
                                                                    <input style="width:140px;" type="text" class="form-control d-none" id="bd_nombre_usuario_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->Nombre_usuario}}" disabled>
                                                                    <span style="width:200px; height: auto;" class="form-control" readonly>{{$parametrizacion_juntas_editar->Nombre_usuario}}</span>
                                                                </td>
                                                                {{-- fecha actualizacion movimiento --}}
                                                                <td>
                                                                    <input type="date" class="form-control d-none" id="bd_fecha_actualizacion_movimiento_juntas_{{$parametrizacion_juntas_editar->Id_parametrizacion}}" value="{{$parametrizacion_juntas_editar->F_actualizacion_movimiento}}" disabled>
                                                                    <span style="width:156px;" class="form-control" readonly>{{date("d/m/Y", strtotime($parametrizacion_juntas_editar->F_actualizacion_movimiento))}}</span>
                                                                </td>
                                                                <td>
                                                                    <div style="text-align:center;">-<div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    <?php endif ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row mt-2 d-none" id="mostrar_mensaje_agrego_parametrizacion_juntas">
                                            <div  class="col-12">
                                                <div class="form-group">
                                                    <div class="mensaje_agrego_parametrizacion_juntas alert" role="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede crear una parametrización debido a que no hay 
                                                    contratado ningún servcio para este proceso.
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
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
@stop

@section('js')
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.templates.min.js"></script>
    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
    <script src="/js/parametrizacion.js"></script>
@stop