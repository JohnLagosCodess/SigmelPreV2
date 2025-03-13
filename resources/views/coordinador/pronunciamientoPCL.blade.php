@extends('adminlte::page')
@section('title', 'Pronunciamiento PCL')
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
                <a href="{{route("bandejaPCL")}}" class="btn btn-info" type="button"><i class="fas fa-archive"></i> Regresar Bandeja</a>
                <a onclick="document.getElementById('botonEnvioVista').click();" style="cursor:pointer;" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Módulo PCL</a>
                <p>
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Calificación PCL - Evento: <u><a onclick="document.getElementById('botonVerEdicionEvento').click();" style="cursor:pointer;">{{$array_datos_pronunciamientoPcl[0]->ID_evento}}</a></u> Afiliado: {{$array_datos_pronunciamientoPcl[0]->Nombre_afiliado}} {{$array_datos_pronunciamientoPcl[0]->Nombre_tipo_documento}} {{$array_datos_pronunciamientoPcl[0]->Nro_identificacion}} - {{$array_datos_pronunciamientoPcl[0]->Tipo_afiliado}}</h4>
            <h5 style="font-style: italic;">Pronunciamiento</h5>
            <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form id="form_CaliPronuncia" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Informacion Afiliado-->
                        <div class="card-info d-none" id="div_info_afi">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información del afiliado</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre_afiliado">Nombre de afiliado</label>
                                            <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_pronunciamientoPcl[0]->Nombre_afiliado}}" readonly>
                                            <input hidden="hidden" type="text" name="Id_Evento_pronuncia" id="Id_Evento_pronuncia" value="{{$array_datos_pronunciamientoPcl[0]->ID_evento}}">
                                            <input hidden="hidden" type="text" name="Id_Proceso_pronuncia" id="Id_Proceso_pronuncia" value="{{$array_datos_pronunciamientoPcl[0]->Id_proceso}}">
                                            <input hidden="hidden" type="text" name="Asignacion_Pronuncia" id="Asignacion_Pronuncia" value="{{$array_datos_pronunciamientoPcl[0]->Id_Asignacion}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="identificacion">N° Identificación</label>
                                            <input type="text" class="form-control" name="identificacion" id="identificacion" value="{{$array_datos_pronunciamientoPcl[0]->Nro_identificacion}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="id_evento">ID evento</label>
                                            <br>
                                            {{-- DATOS PARA VER EDICIÓN DE EVENTO --}}
                                            <a onclick="document.getElementById('botonVerEdicionEvento').click();" style="cursor:pointer; font-weight: bold;" class="btn text-info" type="button"><?php if(!empty($array_datos_pronunciamientoPcl[0]->ID_evento)){echo $array_datos_pronunciamientoPcl[0]->ID_evento;}?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Información de la entidad calificadora -->
                        <div class="card-info" id="div_info_enti_califi">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información de la entidad calificadora</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="primer_calificador">Primer Calificador<span style="color: red;">(*)</span></label>
                                            <select class="custom-select primer_calificador" name="primer_calificador" id="primer_calificador" required>
                                                @if (!empty($info_pronuncia[0]->Id_primer_calificador))
                                                    <option value="{{$info_pronuncia[0]->Id_primer_calificador}}" selected>{{$info_pronuncia[0]->Tipo_Entidad}}</option>
                                                 @else
                                                    <option value="">Seleccione una opción</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="nombre_calificador" id="label_calificador">Nombre de entidad calificadora <span style="color: red;">(*)</span></label>
                                            <div id="content_entidad">
                                                @if(!empty($info_pronuncia[0]->Id_nombre_calificador) && $info_pronuncia[0]->Id_primer_calificador == 6) 
                                                    <select class="custom-select nombre_calificador w-100 d-none" name="nombre_calificador" id="nombre_calificador">
                                                    </select>
                                                    <input type="text" class="form-control" name="otro_calificador" id="otro_calificador" placeholder="Escriba el nombre del calificador." value="{{$info_pronuncia[0]->Nombre_calificador}}" required>
                                                @else
                                                    <select class="custom-select nombre_calificador" name="nombre_calificador" id="nombre_calificador" disabled required>
                                                        <option value="{{$info_pronuncia[0]->Id_nombre_calificador ?? ""}}" selected>{{$info_pronuncia[0]->Nombre_calificador ?? "Seleccione una opción"}}</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nit_calificador">NIT<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Nit_calificador))
                                                <input type="text" class="form-control" name="nit_calificador" id="nit_calificador" value="{{$info_pronuncia[0]->Nit_calificador}}" {{$info_pronuncia[0]->Id_primer_calificador == 6 ? '' : "readonly" }}>
                                            @else
                                                <input type="text" class="form-control" name="nit_calificador" id="nit_calificador" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="dir_calificador">Dirección<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Dir_calificador))
                                                <input type="text" class="form-control" name="dir_calificador" id="dir_calificador" value="{{$info_pronuncia[0]->Dir_calificador}}" {{$info_pronuncia[0]->Id_primer_calificador == 6 ? '' : "readonly" }}>
                                            @else
                                                <input type="text" class="form-control" name="dir_calificador" id="dir_calificador" value="" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="mail_calificador">E-mail<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Email_calificador))
                                                <input type="text" class="form-control" name="mail_calificador" id="mail_calificador" value="{{$info_pronuncia[0]->Email_calificador}}" {{$info_pronuncia[0]->Id_primer_calificador == 6 ? '' : "readonly" }}>
                                            @else
                                                <input type="text" class="form-control" name="mail_calificador" id="mail_calificador" value="" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="telefono_calificador">Teléfonos<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Telefono_calificador))
                                                <input type="text" class="form-control" name="telefono_calificador" id="telefono_calificador" value="{{$info_pronuncia[0]->Telefono_calificador}}" {{$info_pronuncia[0]->Id_primer_calificador == 6 ? '' : "readonly" }}>
                                            @else
                                                <input type="text" class="form-control" name="telefono_calificador" id="telefono_calificador" value="" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="depar_calificador">Departamento<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Depar_calificador))
                                                <input type="text" class="form-control" name="depar_calificador" id="depar_calificador" value="{{$info_pronuncia[0]->Depar_calificador}}" {{$info_pronuncia[0]->Id_primer_calificador == 6 ? '' : "readonly" }}>
                                            @else
                                                <input type="text" class="form-control" name="depar_calificador" id="depar_calificador" value="" readonly>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="ciudad_calificador">Ciudad<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Ciudad_calificador))
                                                <input type="text" class="form-control" name="ciudad_calificador" id="ciudad_calificador" value="{{$info_pronuncia[0]->Ciudad_calificador}}" {{$info_pronuncia[0]->Id_primer_calificador == 6 ? '' : "readonly" }}>
                                            @else
                                                <input type="text" class="form-control" name="ciudad_calificador" id="ciudad_calificador" value="" readonly>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <!-- Información de la calificacion -->
                         <div class="card-info" id="div_info_califi">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información de la Calificación</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="tipo_pronunciamiento">Tipo de pronunciamiento<span style="color: red;">(*)</span></label>
                                            <select class="custom-select tipo_pronunciamiento" name="tipo_pronunciamiento" id="tipo_pronunciamiento" required>
                                                @if (!empty($info_pronuncia[0]->Id_tipo_pronunciamiento))
                                                    <option value="{{$info_pronuncia[0]->Id_tipo_pronunciamiento}}" selected>{{$info_pronuncia[0]->Tpronuncia}}</option>
                                                 @else
                                                    <option value="">Seleccione una opción</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="tipo_evento">Tipo de evento<span style="color: red;">(*)</span></label>
                                            <select class="custom-select tipo_evento" name="tipo_evento" id="tipo_evento" required>
                                                @if (!empty($info_pronuncia[0]->Id_tipo_evento))
                                                    <option value="{{$info_pronuncia[0]->Id_tipo_evento}}" selected>{{$info_pronuncia[0]->Nombre_evento}}</option>
                                                 @else
                                                    <option value="">Seleccione una opción</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="tipo_origen">Origen<span style="color: red;">(*)</span></label>
                                            <select class="custom-select tipo_origen" name="tipo_origen" id="tipo_origen" required>
                                                @if (!empty($info_pronuncia[0]->Id_tipo_origen))
                                                    <option value="{{$info_pronuncia[0]->Id_tipo_origen}}" selected>{{$info_pronuncia[0]->T_origen}}</option>
                                                 @else
                                                    <option value="">Seleccione una opción</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4" id="div_tipo_evento">
                                        <div class="form-group">
                                            <label for="fecha_evento">Fecha del evento<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Fecha_evento))
                                                <input type="date" class="form-control" id="fecha_evento" name="fecha_evento" max="{{date("Y-m-d")}}" min='1900-01-01' value="{{$info_pronuncia[0]->Fecha_evento}}" required>
                                            @else
                                                <input type="date" class="form-control" id="fecha_evento" name="fecha_evento" max="{{date("Y-m-d")}}" min='1900-01-01' required>
                                            @endif
                                            <span class="d-none" id="fecha_evento_alerta" style="color: red; font-style: italic;"></span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="dictamen_calificador">N° dictamen primer calificador</label>
                                            @if (!empty($info_pronuncia[0]->Dictamen_calificador))
                                                <input type="text" class="form-control soloNumeros1" id="dictamen_calificador" name="dictamen_calificador"  value="{{$info_pronuncia[0]->Dictamen_calificador}}" oninput="validarLongitud(this)" maxlength="20">
                                            @else
                                                <input type="text" class="form-control soloNumeros1" id="dictamen_calificador" name="dictamen_calificador" oninput="validarLongitud(this)" maxlength="20">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_calificador">Fecha dictamen primer calificador<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Fecha_calificador))
                                                <input type="date" class="form-control" id="fecha_calificador" name="fecha_calificador" max="{{date("Y-m-d")}}" min='1900-01-01' value="{{$info_pronuncia[0]->Fecha_calificador}}" required>
                                            @else
                                                <input type="date" class="form-control" id="fecha_calificador" name="fecha_calificador" max="{{date("Y-m-d")}}" min='1900-01-01' required/>
                                            @endif
                                            <span class="d-none" id="fecha_calificador_alerta" style="color: red; font-style: italic;"></span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="n_siniestro">N° de Siniestro</label>                                            
                                            @if (!empty($N_siniestro_evento[0]->N_siniestro))                                                
                                                <input type="text" class="n_siniestro form-control" id="n_siniestro" name="n_siniestro" value="{{$N_siniestro_evento[0]->N_siniestro}}">                                                
                                            @else                                               
                                                <input type="text" class="n_siniestro form-control" id="n_siniestro" name="n_siniestro">                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_estruturacion">Fecha Estructuración<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Fecha_estruturacion))
                                                <input type="date" class="form-control" id="fecha_estruturacion" name="fecha_estruturacion" max="{{date("Y-m-d")}}" min='1900-01-01' value="{{$info_pronuncia[0]->Fecha_estruturacion}}" required>
                                            @else
                                                <input type="date" class="form-control" id="fecha_estruturacion" name="fecha_estruturacion" max="{{date("Y-m-d")}}" min='1900-01-01' required/>
                                            @endif
                                            <span class="d-none" id="fecha_estruturacion_alerta" style="color: red; font-style: italic;"></span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="porcentaje_pcl">%PCL<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Porcentaje_pcl))
                                                <input type="text" class="form-control" id="porcentaje_pcl" name="porcentaje_pcl" value="{{$info_pronuncia[0]->Porcentaje_pcl}}" required>
                                            @else
                                                <input type="text" class="form-control" id="porcentaje_pcl" name="porcentaje_pcl" required/>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="rango_pcl">Rango PCL<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Rango_pcl))
                                                <input type="text" class="form-control" id="rango_pcl" name="rango_pcl" value="{{$info_pronuncia[0]->Rango_pcl}}" readonly/>
                                            @else
                                                <input type="text" class="form-control" id="rango_pcl" name="rango_pcl" readonly/>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Diagnósticos motivo de calificación -->
                        <div class="card-info" id="div_mot_cali">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Diagnósticos motivo de calificación</h5>
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
                                                            <th style="width: 340px !important;">CIE10</th>
                                                            <th style="width: 340px !important;">Nombre CIE10</th>
                                                            <th style="width: 140px !important;">Lateralidad Dx</th>
                                                            <th style="width: 140px !important;">Origen CIE10</th>
                                                            <th>Descripción</th>
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
                                                            <td>{{$diagnostico->Deficiencia_motivo_califi_condiciones}}</td>
                                                            <td>
                                                                <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_moticalifi{{$diagnostico->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnostico->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnostico->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                            </td>
                                                        </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div><!--<br>
                                            <x-adminlte-button class="mr-auto" id="guardar_datos_cie10" theme="info" label="Guardar"/>
                                            <br>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pronunciamiento ante la calificación -->
                        <div class="card-info" id="div_pronu_califi">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Pronunciamiento ante la calificación </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-1">
                                        <div class="form-group">
                                            <label for="decision">Decisión:<span style="color: red;">(*)</span></label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="form-check custom-control custom-radio">
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_pr" id="di_acuerdo_pr" value="Acuerdo" <?php if(!empty($info_pronuncia[0]->Decision) && $info_pronuncia[0]->Decision=='Acuerdo'){ ?> checked <?php } ?> required>
                                                <label class="form-check-label custom-control-label" for="di_acuerdo_pr"><strong>Acuerdo</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="form-check custom-control custom-radio">
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_pr" id="di_desacuerdo_pr" value="Desacuerdo" <?php if(!empty($info_pronuncia[0]->Decision) && $info_pronuncia[0]->Decision=='Desacuerdo'){ ?> checked <?php } ?> required>
                                                <label class="form-check-label custom-control-label" for="di_desacuerdo_pr"><strong>Desacuerdo</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="form-check custom-control custom-radio">
                                                <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_pr" id="di_silencio_pr" value="Silencio" <?php if(!empty($info_pronuncia[0]->Decision) && $info_pronuncia[0]->Decision=='Silencio'){ ?> checked <?php } ?> required>
                                                <label class="form-check-label custom-control-label" for="di_silencio_pr"><strong>Silencio</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="fecha_pronuncia">Fecha de pronunciamiento:</label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            @if (!empty($info_pronuncia[0]->Fecha_pronuncia))
                                                <p>{{$info_pronuncia[0]->Fecha_pronuncia}}</p>
                                            @else
                                                <p id="fechaHora">{{now()->format('Y-m-d h:i:s')}}</p>                                                                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12" id="asun_correspondencia">
                                        <div class="form-group">
                                            <label for="asunto_cali">Asunto<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Asunto_cali))
                                                <input type="text" class="form-control" name="asunto_cali" id="asunto_cali" value="{{$info_pronuncia[0]->Asunto_cali}}" required>
                                            @else
                                                <input type="text" class="form-control" name="asunto_cali" id="asunto_cali" value="" required>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12" id="susten_correspondencia">
                                        <div class="form-group">
                                            <label for="sustenta_cali">Sustentación<span style="color: red;">(*)</span></label>
                                            <br>
                                            <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_Nombre_afiliado">Nombre afiliado</button>
                                            <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_nombreCIE10">CIE10-Nombre-Origen</button>
                                            {{-- <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_Origen">Origen</button> --}}
                                            <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_porPcl">% PCL</button>
                                            <button class="btn btn-sm btn-secondary mb-2 d-none" id="btn_insertar_F_estructuracion">Fecha de estructuracion</button>
                                            @if (!empty($info_pronuncia[0]->Sustenta_cali))
                                                <textarea class="form-control" name="sustenta_cali" id="sustenta_cali" cols="30" rows="5" style="resize: none;" required>{{$info_pronuncia[0]->Sustenta_cali}}</textarea>
                                            @else
                                                <textarea class="form-control" name="sustenta_cali" id="sustenta_cali" cols="30" rows="5" style="resize: none;" required></textarea>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Correspondencia -->
                        <div class="card-info row_correspondencia d-none" id='correspondencia-item'>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Correspondencia</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" id="bd_checkbox_destinatario_principal" value="<?php if(!empty($info_pronuncia[0]->Destinatario_principal)){ echo $info_pronuncia[0]->Destinatario_principal;} ?>">
                                                <input class="custom-control-input" type="checkbox" id="destinatario_principal" name="destinatario_principal" value="Si" @if (!empty($info_pronuncia[0]->Destinatario_principal) && $info_pronuncia[0]->Destinatario_principal=='Si') checked @endif>
                                                <label for="destinatario_principal" class="custom-control-label">Destinatario Principal</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="columna_tipo_entidad form-group" style="display:none">
                                            <label for="" class="col-form-label">Entidad</label>
                                            <input type="hidden" id="bd_tipo_entidad" value="<?php if(!empty($info_pronuncia[0]->Tipo_entidad)){ echo $info_pronuncia[0]->Tipo_entidad;} ?>">
                                            <select class="custom-select tipo_entidad" name="tipo_entidad" id="tipo_entidad">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="columna_nombre_entidad form-group" style="display:none">
                                            <label for="" class="col-form-label">Nombre Entidad</label>
                                            <input type="hidden" id="bd_nombre_entidad" value="<?php if(!empty($info_pronuncia[0]->Nombre_entidad)){ echo $info_pronuncia[0]->Nombre_entidad;} ?>">
                                            <select class="custom-select nombre_entidad" name="nombre_entidad" id="nombre_entidad"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="decision">Copia partes interesadas</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_afiliado" name="copia_afiliado" value="Afiliado" @if (!empty($info_pronuncia[0]->Copia_afiliado) && $info_pronuncia[0]->Copia_afiliado=='Afiliado') checked @endif>
                                                    <label for="copia_afiliado" class="custom-control-label">Afiliado</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_empleador" name="copia_empleador" value="Empleador" @if (!empty($info_pronuncia[0]->copia_empleador) && $info_pronuncia[0]->copia_empleador=='Empleador') checked @endif>
                                                    <label for="copia_empleador" class="custom-control-label">Empleador</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_eps" name="copia_eps" value="EPS" @if (!empty($info_pronuncia[0]->Copia_eps) && $info_pronuncia[0]->Copia_eps=='EPS') checked @endif>
                                                    <label for="copia_eps" class="custom-control-label">EPS</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_afp" name="copia_afp" value="AFP" @if (!empty($info_pronuncia[0]->Copia_afp) && $info_pronuncia[0]->Copia_afp=='AFP') checked @endif>
                                                    <label for="copia_afp" class="custom-control-label">AFP</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="copia_arl" name="copia_arl" value="ARL" @if (!empty($info_pronuncia[0]->Copia_arl) && $info_pronuncia[0]->Copia_arl=='ARL') checked @endif>
                                                    <label for="copia_arl" class="custom-control-label">ARL</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="entidad_conocimiento" id="entidad_conocimiento" value="<?php if(!empty($info_afp_conocimiento[0]->Entidad_conocimiento)){echo $info_afp_conocimiento[0]->Entidad_conocimiento;}?>">
                                    @if (!empty($info_afp_conocimiento[0]->Entidad_conocimiento) && $info_afp_conocimiento[0]->Entidad_conocimiento == "Si")
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="dependencia_justificacion custom-control-input" type="checkbox" id="afp_conocimiento" name="afp_conocimiento" value="AFP_Conocimiento" @if (!empty($info_pronuncia[0]->Copia_Afp_Conocimiento) && $info_pronuncia[0]->Copia_Afp_Conocimiento=='AFP_Conocimiento') checked @endif>
                                                    <label for="afp_conocimiento" class="custom-control-label">Entidad conocimiento</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-4 d-none">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="junta_regional" name="junta_regional" value="junta_regi" @if (!empty($info_pronuncia[0]->Copia_junta_regional) && $info_pronuncia[0]->Copia_junta_regional=='junta_regi') checked @endif>
                                                    <label for="junta_regional" class="custom-control-label">Junta Regional de Calificación de invalidez</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 d-none" id="div_cual">
                                        <div class="form-group">
                                            <label for="junta_regional_cual">¿Cuál?<span style="color: red;">(*)</span></label>
                                            <select class="junta_regional_cual custom-select" name="junta_regional_cual" id="junta_regional_cual">
                                                @if (!empty($info_pronuncia[0]->Junta_regional_cual))
                                                    <option value="{{$info_pronuncia[0]->Junta_regional_cual}}" selected>{{$info_pronuncia[0]->Junta_regional_cual}}</option>
                                                 @else
                                                    <option>Seleccione una opción</option>
                                                 @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4 d-none">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="junta_nacional" name="junta_nacional" value="junta_naci" @if (!empty($info_pronuncia[0]->Copia_junta_nacional) && $info_pronuncia[0]->Copia_junta_nacional=='junta_naci') checked @endif>
                                                    <label for="junta_nacional" class="custom-control-label">Junta Nacional de Calificación de invalidez</label>                 
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="n_anexos">N° Anexos</label>
                                            @if (!empty($info_pronuncia[0]->N_anexos))
                                                <input type="number" class="form-control soloNumeros" name="n_anexos" id="n_anexos" value="{{$info_pronuncia[0]->N_anexos}}">
                                            @else
                                                <input type="number" class="form-control soloNumeros" name="n_anexos" id="n_anexos" value="">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="elaboro">Elaboró</label>
                                            @if (!empty($info_pronuncia[0]->Elaboro_pronuncia))
                                                <input type="text" class="form-control" name="elaboro" id="elaboro" value="{{$info_pronuncia[0]->Elaboro_pronuncia}}" readonly>
                                            @else
                                                <input type="text" class="form-control" name="elaboro" id="elaboro" value="" readonly>
                                            @endif
                                                <input hidden="hidden" class="form-control" type="text" name="elaboro_data" id="elaboro_data" value="{{$user->name}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="reviso">Revisó<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Reviso_Pronuncia))
                                                <input type="hidden" id="bd_quien_reviso" value="{{$info_pronuncia[0]->Reviso_Pronuncia}}">
                                            @endif                                        
                                            <select class="reviso custom-select" name="reviso" id="reviso" style="width: 100%;">
                                                 <option value="">Seleccione una opción</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="ciudad_correspon">Ciudad<span style="color: red;">(*)</span></label>
                                            @if (!empty($info_pronuncia[0]->Ciudad_correspon))
                                                <input type="text" class="form-control" name="ciudad_correspon" id="ciudad_correspon" value="{{$info_pronuncia[0]->Ciudad_correspon}}" required>
                                            @else
                                                <input type="text" class="form-control" name="ciudad_correspon" id="ciudad_correspon" value="Bogotá D.C." required>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="fecha_correspon">Fecha</label>
                                            @if (!empty($info_pronuncia[0]->Fecha_correspondencia))
                                                <input type="date" class="form-control" name="fecha_correspon" id="fecha_correspon" value="{{$info_pronuncia[0]->Fecha_correspondencia}}" readonly>
                                            @else
                                                <input type="date" class="form-control" name="fecha_correspon" id="fecha_correspon" value="{{now()->format('Y-m-d')}}" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="n_radicado">N° Radicado</label>
                                            @if (!empty($info_pronuncia[0]->Fecha_correspondencia))
                                                <input type="text" class="form-control" name="n_radicado" id="n_radicado" value="{{$info_pronuncia[0]->N_radicado}}" readonly>
                                            @else
                                                <input type="text" class="form-control" name="n_radicado" id="n_radicado" value="{{$consecutivo}}" readonly>
                                            @endif
                                            <input type="hidden" class="form-control" name="radicado_comunicado_manual" id="radicado_comunicado_manual" value="{{$consecutivo}}" readonly>
                                            @if (!empty($info_pronuncia[0]))
                                                <input type="hidden" class="form-control" name="info_pronuncia" id="info_pronuncia" value="{{$info_pronuncia}}" readonly>   
                                            @else
                                                <input type="hidden" class="form-control" name="info_pronuncia" id="info_pronuncia" value="{{null}}" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <br>
                                                <input class="custom-control-input" type="checkbox" id="firmar" name="firmar" value="firmar" @if (!empty($info_pronuncia[0]->Firmar) && $info_pronuncia[0]->Firmar=='firmar') checked @endif>
                                                <label for="firmar" class="custom-control-label">Firmar</label>                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="div_doc_pronu">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="n_radicado">Cargue Documento Pronunciamiento:</label>
                                    @if (!empty($info_pronuncia[0]->Archivo_pronuncia) && $info_pronuncia[0]->Archivo_pronuncia <>'N/A')
                                        <input hidden="hidden" type="text" name="nom_archivo" id="nom_archivo" value="{{$info_pronuncia[0]->Archivo_pronuncia}}">
                                        <a href="{{route('VerDocumentoPronuncia', ['Id_evento' => $info_pronuncia[0]->ID_evento,'nom_archivo' => $info_pronuncia[0]->Archivo_pronuncia, 'Id_Asignacion'=> $info_pronuncia[0]->Id_Asignacion,'Id_proceso'=> $info_pronuncia[0]->Id_proceso, 'Fecha_correspondencia'=> $info_pronuncia[0]->Fecha_correspondencia, 'N_radicado'=> $info_pronuncia[0]->N_radicado])}}">Ver documento ya cargado</a>
                                    @endif
                                    <input type="file" class="form-control select-doc" name="DocPronuncia" id="DocPronuncia" aria-describedby="Carguedocumentos" aria-label="Upload"/>
                                </div>
                            </div>
                            <div id="div_alerta_archivo" class="col-12 d-none">
                                <div class="form-group">
                                    <div class="alerta_archivo alert alert-danger mt-2 mr-auto" role="alert"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12" id="div_msg_alerta">
                                    <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para guardar la información es necesario dar clic en el botón guardar/actualizar.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="alerta_roja_guardado alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        @if (!empty($info_pronuncia[0]->ID_evento))
                                            <input type="button" id="ActualizarPronuncia" name="ActualizarPronuncia" class="btn btn-info" value="Actualizar">
                                            <input hidden="hidden" type="text" id="bandera_pronuncia_guardar_actualizar" value="Actualizar">
                                        @else
                                            <input type="button" id="GuardarPronuncia" name="GuardarPronuncia" class="btn btn-info" value="Guardar">                                                
                                            <input hidden="hidden" type="text" id="bandera_pronuncia_guardar_actualizar" value="Guardar">
                                        @endif
                                    </div>
                                </div>
                                <div id="div_alerta_pronuncia" class="col-12 d-none">
                                    <div class="form-group">
                                        <div class="alerta_pronucia alert alert-success mt-2 mr-auto" role="alert"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                            <div class="row d-none" id="contenedor_filtros_comunicados">
                                <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="filtrar_comunicados" name="filtrar_comunicados">
                                        <label for="filtrar_comunicados" class="custom-control-label">Ocultar No notificar</label>                 
                                    </div>
                                </div>
                            </div>
                            <div class="row">  
                                <div class="col-12">
                                    <div class="form-group">                                            
                                        <div class="table-responsive">
                                            
                                            <table id="listado_comunicados_clpcl" class="table table-striped table-bordered" style="width: 100%;  white-space: nowrap;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>N° de Radicado</th>
                                                        <th>Elaboró</th>
                                                        <th>Fecha de comunicado</th>
                                                        <th>Documento</th>
                                                        {{-- Si el caso está en la bandeja de notificaciones se muestra la columna Destinatarios --}}
                                                        @if ($caso_notificado == "Si")
                                                            <th>Destinatarios</th>
                                                        @endif
                                                        <th>Estado general de la Notificación</th>
                                                        <th>Nota</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($array_comunicados as $comunicados)
                                                        <tr>
                                                            <td data-id_comunicado="{{$comunicados->Id_Comunicado}}">
                                                                {{$comunicados->N_radicado}}
                                                            </td>
                                                            <td>{{$comunicados->Elaboro}}</td>
                                                            <td>{{$comunicados->F_comunicado}}</td>
                                                            <td><?php if($comunicados->Tipo_descarga == 'Manual'){echo $comunicados->Asunto;}else{echo $comunicados->Tipo_descarga;}?></td>
                                                            @if ($caso_notificado == "Si")
                                                                <td style="white-space: normal;">
                                                                    <div id="destinatariosContainer_{{$comunicados->Id_Comunicado}}"></div>
                                                                </td>
                                                            @endif
                                                            <td><select class="custom-select" id="status_notificacion_{{$comunicados->N_radicado}}" data-deshabilitar={{$deshabilitarSelector ?? '1'}} style="width:100%;" data-deshabilitar={{$deshabilitarSelector ?? '1'}} data-default={{$comunicados->Estado_Notificacion}}></select></td>
                                                            <td><textarea class="form-control nota-col" name="nota_comunicado_{{$comunicados->N_radicado}}" id="nota_comunicado_{{$comunicados->N_radicado}}" cols="70" rows="3" style="resize:none; width:200px;">{{$comunicados->Nota}}</textarea></td> {{-- campo Nota--}}
                                                            <td style="display: flex; flex-direction:row; justify-content:space-around;">
                                                                @if ($comunicados->Tipo_descarga == "Acuerdo" || $comunicados->Tipo_descarga == "Desacuerdo")
                                                                    <form id="archivo_{{$comunicados->Id_Comunicado}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                        @csrf
                                                                        <div class="d-none">
                                                                            @if ($comunicados->Tipo_descarga == "Acuerdo")
                                                                                {{-- tipo de proforma --}}
                                                                                <input type="hidden" id="bandera_tipo_proforma" value="proforma_acuerdo">
                                                                                {{-- Tipo de documento --}}
                                                                                <input type="hidden" id="tipo_identificacion" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Nombre_tipo_documento)){echo $array_datos_pronunciamientoOrigen[0]->Nombre_tipo_documento;}?>">
                                                                                {{-- Nro de documento --}}
                                                                                <input type="hidden" id="num_identificacion" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Nro_identificacion)){echo $array_datos_pronunciamientoOrigen[0]->Nro_identificacion;}?>">
                                                                                {{-- Siniestro --}}
                                                                                <input type="hidden" id="nro_siniestro" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->ID_evento)){echo $array_datos_pronunciamientoOrigen[0]->ID_evento;} ?>">
                                                                                {{-- Nombre afiliado --}}
                                                                                <input type="hidden" id="nombre_afiliado" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Nombre_afiliado)){echo $array_datos_pronunciamientoOrigen[0]->Nombre_afiliado;}?>">
                                                                                {{-- Nombre entidad calificadora --}}
                                                                                <input type="hidden" id="nom_entidad_califi" value="<?php if(!empty($info_pronuncia[0]->Nombre_entidad)){echo $info_pronuncia[0]->Nombre_entidad;}?>">
                                                                                {{-- Id asignacion para consultar los diagnosticos --}}
                                                                                <input type="hidden" id="Id_Asignacion_consulta_dx" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Id_Asignacion)){echo $array_datos_pronunciamientoOrigen[0]->Id_Asignacion;}?>">
                                                                                {{-- Id proceso para consultar los diagnosticos --}}
                                                                                <input type="hidden" id="Id_Proceso_consulta_dx" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Id_proceso)){echo $array_datos_pronunciamientoOrigen[0]->Id_proceso;}?>">
                                                                                {{-- Id del cliente para consultar el nombre del cliente --}}
                                                                                <input type="hidden" id="Id_cliente_firma" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Id_cliente)){echo $array_datos_pronunciamientoOrigen[0]->Id_cliente;}?>">
                                                                                {{-- Id del comunicado a editar --}}
                                                                                <input type="hidden" id="id_comunicado_a_editar" value="<?php if(!empty($comunicados->Id_Comunicado)){echo $comunicados->Id_Comunicado;}?>">

                                                                            @elseif ($comunicados->Tipo_descarga == "Desacuerdo")

                                                                                {{-- Nombre entidad calificadora --}}
                                                                                <input type="hidden" id="nom_entidad_califi" value="<?php if(!empty($info_pronuncia[0]->Nombre_entidad)){echo $info_pronuncia[0]->Nombre_entidad;}?>">
                                                                                {{-- tipo de proforma --}}
                                                                                <input type="hidden" id="bandera_tipo_proforma" value="proforma_desacuerdo">
                                                                                {{-- Tipo de documento --}}
                                                                                <input type="hidden" id="tipo_identificacion" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Nombre_tipo_documento)){echo $array_datos_pronunciamientoOrigen[0]->Nombre_tipo_documento;}?>">
                                                                                {{-- Nro de documento --}}
                                                                                <input type="hidden" id="num_identificacion" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Nro_identificacion)){echo $array_datos_pronunciamientoOrigen[0]->Nro_identificacion;}?>">
                                                                                {{-- Siniestro --}}
                                                                                <input type="hidden" id="nro_siniestro" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->ID_evento)){echo $array_datos_pronunciamientoOrigen[0]->ID_evento;} ?>">
                                                                                {{-- Nombre afiliado --}}
                                                                                <input type="hidden" id="nombre_afiliado" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Nombre_afiliado)){echo $array_datos_pronunciamientoOrigen[0]->Nombre_afiliado;}?>">
                                                                                {{-- Id asignacion para consultar los diagnosticos --}}
                                                                                <input type="hidden" id="Id_Asignacion_consulta_dx" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Id_Asignacion)){echo $array_datos_pronunciamientoOrigen[0]->Id_Asignacion;}?>">
                                                                                {{-- Id proceso para consultar los diagnosticos --}}
                                                                                <input type="hidden" id="Id_Proceso_consulta_dx" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Id_proceso)){echo $array_datos_pronunciamientoOrigen[0]->Id_proceso;}?>">
                                                                                {{-- Id del cliente para consultar el nombre del cliente --}}
                                                                                <input type="hidden" id="Id_cliente_firma" value="<?php if(!empty($array_datos_pronunciamientoOrigen[0]->Id_cliente)){echo $array_datos_pronunciamientoOrigen[0]->Id_cliente;}?>">
                                                                                {{-- Id del comunicado a editar --}}
                                                                                <input type="hidden" id="id_comunicado_a_editar" value="<?php if(!empty($comunicados->Id_Comunicado)){echo $comunicados->Id_Comunicado;}?>">
                                                                            @endif
                                                                        </div>
                                                                        <button type="submit" id="btn_generar_proforma" style="border: none; background:transparent;">
                                                                            <i class="far fa-eye text-info"></i>
                                                                        </button>
                                                                    </form>
                                                                    @if($dato_rol !== '7')
                                                                        <form  id="form_editar_comunicado_{{$comunicados->Id_Comunicado}}" data-tupla_comunicado="{{$comunicados}}" method="POST">
                                                                            <button type="submit" id="editar_correspondencia" style="border: none; background:transparent;">
                                                                                <i class="fa fa-pen text-info"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                    @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                        <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                            <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" {{$deshabilitarRemplazar ?? ''}}>
                                                                                <i class="fas fa-sync-alt text-info"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                {{-- </td> --}}
                                                                @else {{--  if ($comunicados->Tipo_descarga == "Manual") --}}
                                                                    <form id="form_descargar_archivo_{{$comunicados->Id_Comunicado}}" data-archivo="{{$comunicados}}" method="POST">
                                                                        <button type="submit" id="btn_descargar_archivo_{{$comunicados->Id_Comunicado}}" style="border: none; background:transparent;">
                                                                            <i class="far fa-eye text-info"></i>
                                                                        </button>
                                                                    </form>
                                                                    @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                        <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                            <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" style="border: none; background: transparent;" {{$deshabilitarRemplazar ?? ''}}>
                                                                                <i class="fas fa-sync-alt text-info"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                                {{-- <button id="replace_file" style="border: none; background:transparent;">
                                                                    <i class="fas fa-sync-alt text-info"></i>
                                                                </button> --}}
                                                                @if($dato_rol !== '7')
                                                                    <a href="javascript:void(0);"  class="editar_comunicado_{{$comunicados->N_radicado}}" id="editar_comunicado" data-radicado="{{$comunicados->N_radicado}}" style="{{$deshabilitaredicion ?? ''}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                @endif
                                                            </td>
                                                        </tr>                                                      
                                                    @endforeach
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
        <input type="hidden" name="newIdEvento" id="newIdEvento" value="{{$array_datos_pronunciamientoPcl[0]->ID_evento}}">
        <input type="hidden" name="newIdAsignacion" id="newIdAsignacion" value="{{$array_datos_pronunciamientoPcl[0]->Id_Asignacion}}">
        <input type="hidden" name="Id_Servicio" id="Id_Servicio" value="<?php if(!empty($array_datos_pronunciamientoPcl[0]->Id_Servicio)){ echo $array_datos_pronunciamientoPcl[0]->Id_Servicio;}?>">
        <button type="submit" id="botonEnvioVista" style="display:none !important;"></button>
    </form> 
    <!--Retonar al modulo Modulo Nuevo edicion -->
    <form action="{{route('gestionInicialEdicion')}}" id="formularioLlevarEdicionEvento" method="POST">
        @csrf
        <input type="hidden" name="bandera_buscador_pro" id="bandera_buscador_pro" value="desdepro">
        <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="<?php if(!empty($array_datos_pronunciamientoPcl[0]->ID_evento)){echo $array_datos_pronunciamientoPcl[0]->ID_evento;}?>">
        <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="<?php if(!empty($array_datos_pronunciamientoPcl[0]->Id_Asignacion)){echo $array_datos_pronunciamientoPcl[0]->Id_Asignacion;}?>">
        <input hidden="hidden" type="text" name="newIdproceso" id="newIdproceso" value="<?php if(!empty($array_datos_pronunciamientoPcl[0]->Id_proceso)){ echo $array_datos_pronunciamientoPcl[0]->Id_proceso;}?>">
        <input hidden="hidden" type="text" name="newIdservicio" id="newIdservicio" value="<?php if(!empty($array_datos_pronunciamientoPcl[0]->Id_Servicio)){ echo $array_datos_pronunciamientoPcl[0]->Id_Servicio;}?>">
        <button type="submit" id="botonVerEdicionEvento" style="display:none !important;"></button>
   </form>
   @include('//.coordinador.modalReemplazarArchivos')
   @include('//.coordinador.modalCorrespondencia')
   @include('//.modals.alertaRadicado')
   @include('//.modals.alertasGestion')
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
        //Diagnosticos CIE10
        let arrayDatosDiagnosticos = @json($array_datos_diagnostico_motcalifi);
        $(document).ready(function(){
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
                $('#Generar_correspondencia').removeClass('d-none');

                contador_cie10 = contador_cie10 + 1;
                var nueva_fila_cie10 = [
                    '<select id="lista_Cie10_fila_'+contador_cie10+'" class="custom-select lista_Cie10_fila_'+contador_cie10+'" name="lista_Cie10"><option></option></select>',
                    '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie10+'" name="nombre_cie10"/>',
                    '<select id="lista_lateralidadCie10_fila_'+contador_cie10+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie10+'" name="lista_lateralidadCie10"><option></option></select>',
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
            
        });

        function validarLongitud(input) {
            if (input.value.length > 20) {
                input.value = input.value.slice(0, 20);
            }
        };
    </script>
    <script type="text/javascript" src="/js/pronunciamientopcl.js?v=1.0.0"></script>
    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
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
                    $('#GuardarPronuncia').addClass('d-none');
                    $('#ActualizarPronuncia').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(this.value > today){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#GuardarPronuncia').addClass('d-none');
                    $('#ActualizarPronuncia').addClass('d-none');
                    return;
                }
                $('#GuardarPronuncia').removeClass('d-none');
                $('#ActualizarPronuncia').removeClass('d-none');
                return $(`#${this.id}_alerta`).text('').addClass("d-none");
            });
        });
    </script>
    <script>
        let entidades_conocimiento = @json($entidades_conocimiento);
        document.addEventListener("DOMContentLoaded", function () {
            @foreach ($array_comunicados as $index => $comunicados)
                var comunicados_submodulo = @json($comunicados);
                var destinatarios = getHistorialNotificacion(
                    "{{ $comunicados->N_radicado }}",
                    "{{ $comunicados->Nota }}",
                    null,
                    comunicados_submodulo,
                    entidades_conocimiento,
                    false,
                    true
                );
    
                if (document.getElementById("destinatariosContainer_{{ $comunicados->Id_Comunicado }}") && destinatarios) {
                    document.getElementById("destinatariosContainer_{{ $comunicados->Id_Comunicado }}").innerHTML = destinatarios;
                }
            @endforeach
        });
    </script>
@stop