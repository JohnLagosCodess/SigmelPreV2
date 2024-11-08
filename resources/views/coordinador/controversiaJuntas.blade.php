@extends('adminlte::page')
@section('title', 'Controversia Juntas')

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
                <?php if($dato_rol == 7): ?>
                    <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Consultar Evento</a>                                                
                <?php else:?>
                    <a href="{{route("bandejaJuntas")}}" class="btn btn-info" type="button"><i class="fas fa-archive"></i> Regresar Bandeja</a>
                <?php endif ?>
                <a onclick="document.getElementById('botonEnvioVista').click();" style="cursor:pointer;" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Módulo Juntas</a>
                <p>
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                </p>
            </div>
        </div>
    </div>
    {{-- @if (empty($arrayinfo_controvertido[0]->ID_evento))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede trabajar una controversia si no es diligenciado la
                    Gestión de controversia - Seguimiento en el módulo principal de Juntas
                </div>
            </div>
        </div> --}}
    {{-- @else --}}
        <div class="card-info" style="border: 1px solid black;">
            <div class="card-header text-center">
                <h4>Juntas Controversia - Evento: {{$array_datos_controversiaJuntas[0]->ID_evento}}</h4>
                <h5 style="font-style: italic;"><?php echo $array_datos_controversiaJuntas[0]->Nombre_servicio;?></h5>
                <input type="hidden" name="NombreUsuario" id="NombreUsuario" value="{{$user->name}}">
                <input type="hidden" class="form-control" name="newId_evento" id="newId_evento" value="{{$array_datos_controversiaJuntas[0]->ID_evento}}">
                <input type="hidden" class="form-control" name="newId_asignacion" id="newId_asignacion" value="{{$array_datos_controversiaJuntas[0]->Id_Asignacion}}">
                <input type="hidden" class="form-control" name="Id_proceso" id="Id_proceso" value="{{$array_datos_controversiaJuntas[0]->Id_proceso}}">
                
                {{-- campos creados para extraer algunos datos de la proforma  --}}
                <input type="hidden" class="form-control" id="id_servicio" value="{{$array_datos_controversiaJuntas[0]->Id_Servicio}}">
                <input type="hidden" id="id_cliente"  value="<?php if(!empty($array_datos_controversiaJuntas[0]->Id_cliente)){echo $array_datos_controversiaJuntas[0]->Id_cliente;}?>">
                <input type="hidden" id="tipo_documento" value="<?php if(!empty($array_datos_controversiaJuntas[0]->Nombre_tipo_documento)){echo $array_datos_controversiaJuntas[0]->Nombre_tipo_documento;}?>">
                <input type="hidden" id="id_Jrci_califi_invalidez" value="<?php if(!empty($arrayinfo_controvertido[0]->Jrci_califi_invalidez)){echo $arrayinfo_controvertido[0]->Jrci_califi_invalidez;}?>">
                <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <!-- Informacion Afiliado-->
                        <div class="card-info" id="div_info_afiliado">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información del afiliado</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre_afiliado">Nombre de afiliado</label>
                                            <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_controversiaJuntas[0]->Nombre_afiliado}}" readonly>
                                            <input hidden="hidden" type="text" name="Id_Evento_controversia" id="Id_Evento_controversia" value="{{$array_datos_controversiaJuntas[0]->ID_evento}}">
                                            <input hidden="hidden" type="text" name="Id_Proceso_controversia" id="Id_Proceso_controversia" value="{{$array_datos_controversiaJuntas[0]->Id_proceso}}">
                                            <input hidden="hidden" type="text" name="Asignacion_Controversia" id="Asignacion_Controversia" value="{{$array_datos_controversiaJuntas[0]->Id_Asignacion}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="identificacion">N° Identificación</label>
                                            <input type="text" class="form-control" name="identificacion" id="identificacion" value="{{$array_datos_controversiaJuntas[0]->Nro_identificacion}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="id_evento">ID evento</label>
                                            <input type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_controversiaJuntas[0]->ID_evento}}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Información del Dictamen Controvertido-->
                        <div class="card-info" id="div_info_dic_cotrover">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Información del Dictamen Controvertido</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="primer_calificador">Primer Calificador</label>
                                            <input type="text" class="form-control" name="primer_calificador" id="primer_calificador" value="<?php if(!empty($arrayinfo_controvertido[0]->Calificador)) { echo $arrayinfo_controvertido[0]->Calificador;} ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nom_entidad">Nombre de entidad calificadora</label>
                                            <input type="text" class="form-control" name="nom_entidad" id="nom_entidad" value="<?php if(!empty($arrayinfo_controvertido[0]->Nom_entidad)) { echo $arrayinfo_controvertido[0]->Nom_entidad;} ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Tipo_evento_juntas">Tipo de Evento</label>
                                            <input type="text" class="form-control" name="Tipo_evento_juntas" id="Tipo_evento_juntas" value="<?php if(!empty($array_datos_controversiaJuntas[0]->Nombre_evento)) { echo $array_datos_controversiaJuntas[0]->Nombre_evento;} ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="N_dictamen_controvertido">N° Dictamen controvertido</label>
                                            <input type="text" class="form-control" name="N_dictamen_controvertido" id="N_dictamen_controvertido" value="<?php if(!empty($arrayinfo_controvertido[0]->N_dictamen_controvertido)) { echo $arrayinfo_controvertido[0]->N_dictamen_controvertido;} ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="parte_controvierte_califi">Parte que controvierte primera calificación</label>
                                            <input type="text" class="form-control" name="parte_controvierte_califi" id="parte_controvierte_califi" value="<?php if(!empty($arrayinfo_controvertido[0]->ParteCalificador)) { echo $arrayinfo_controvertido[0]->ParteCalificador;} ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre_controvierte_califi">Nombre de quién controvierte primera calificación</label>
                                            <input type="text" class="form-control" name="nombre_controvierte_califi" id="nombre_controvierte_califi" value="<?php if(!empty($arrayinfo_controvertido[0]->Nombre_controvierte_califi)) { echo $arrayinfo_controvertido[0]->Nombre_controvierte_califi;} ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="n_siniestro">N° de Siniestro</label>                                            
                                            <input type="text" class="n_siniestro form-control" id="n_siniestro" name="n_siniestro" value="<?php if(!empty($N_siniestro_evento[0]->N_siniestro)) { echo $N_siniestro_evento[0]->N_siniestro;} ?>">                                                                                                                                                        
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="tipo_controvierte_califi">Tipo de controversia primera calificación</label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="contro_origen" name="contro_origen" value="Origen" @if (!empty($arrayinfo_controvertido[0]->Contro_origen) && $arrayinfo_controvertido[0]->Contro_origen=='Origen') checked @endif disabled>
                                                <label for="contro_origen" class="custom-control-label">Origen</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="contro_pcl" name="contro_pcl" value="% PCL" @if (!empty($arrayinfo_controvertido[0]->Contro_pcl) && $arrayinfo_controvertido[0]->Contro_pcl=='% PCL') checked @endif disabled>
                                                <label for="contro_pcl" class="custom-control-label">%PCL</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="contro_diagnostico" name="contro_diagnostico" value="Diagnósticos" @if (!empty($arrayinfo_controvertido[0]->Contro_diagnostico) && $arrayinfo_controvertido[0]->Contro_diagnostico=='Diagnósticos') checked @endif disabled >
                                                <label for="contro_diagnostico" class="custom-control-label">Diagnósticos</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="contro_f_estructura" name="contro_f_estructura" value="Fecha estructuración" @if (!empty($arrayinfo_controvertido[0]->Contro_f_estructura) && $arrayinfo_controvertido[0]->Contro_f_estructura=='Fecha estructuración') checked @endif disabled>
                                                <label for="contro_f_estructura" class="custom-control-label">Fecha estructuración</label>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="contro_m_califi" name="contro_m_califi" value="Manual de calificación" @if (!empty($arrayinfo_controvertido[0]->Contro_m_califi) && $arrayinfo_controvertido[0]->Contro_m_califi=='Manual de calificación') checked @endif disabled>
                                                <label for="contro_m_califi" class="custom-control-label">Manual de calificación</label>                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Diagnósticos del Dictamen Controvertido -->
                        <div class="card-info" id="div_dx_dicta_contro">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Diagnósticos del Dictamen Controvertido</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarControvertido" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                                <div class="alert alert-warning mensaje_confirmacion_controvertido" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                    que diligencie en su totalidad los campos.
                                                </div>
                                                <div class="alert d-none" id="resultado_insercion_cie10_controvertido" role="alert"></div>
                                                <div class="table-responsive">
                                                    <table id="listado_diagnostico_cie10_controvertido" class="table table-striped table-bordered" width="100%">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>CIE-10</th>
                                                                <th>Nombre CIE-10</th>
                                                                <th>Descripción complementaria del DX</th>
                                                                <th>Lateralidad Dx</th>
                                                                <th>Origen Dx</th>
                                                                <th>Dx Principal</th>
                                                                <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_controvertido_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($array_datos_diagnostico_motcalifi_contro))
                                                                @foreach ($array_datos_diagnostico_motcalifi_contro as $diagnostico_contro)
                                                                <tr class="fila_diagnosticos_{{$diagnostico_contro->Id_Diagnosticos_motcali}}" id="datos_diagnostico_controvertido">
                                                                    <td>{{$diagnostico_contro->Codigo}}</td>
                                                                    <td>{{$diagnostico_contro->Nombre_CIE10}}</td>
                                                                    <td>{{$diagnostico_contro->Deficiencia_motivo_califi_condiciones}}</td>
                                                                    <td>{{$diagnostico_contro->Nombre_parametro_lateralidad}}</td>
                                                                    <td>{{$diagnostico_contro->Nombre_parametro_origen}}</td>
                                                                    <td>
                                                                        <input type="checkbox" id="checkbox_dx_principal_visual_Cie10_{{$diagnostico_contro->Id_Diagnosticos_motcali}}" class="checkbox_dx_principal_visual_Cie10_{{$diagnostico_contro->Id_Diagnosticos_motcali}}" data-id_fila_checkbox_dx_principal_cie10_visual="{{$diagnostico_contro->Id_Diagnosticos_motcali}}" <?php if($diagnostico_contro->Principal == "Si"):?> checked <?php endif?> style="transform: scale(1.2) !important;">
                                                                    </td>
                                                                    <td>
                                                                        <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_moticalifi{{$diagnostico_contro->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnostico_contro->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnostico_contro->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                    </td>
                                                                </tr> 
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="origen_controversia">Origen <span style="color: red;">(*)</span></label>
                                                <select class="custom-select origen_controversia" name="origen_controversia" id="origen_controversia" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Origen_controversia))
                                                            <option value="{{$arrayinfo_controvertido[0]->Origen_controversia}}" selected required>{{$arrayinfo_controvertido[0]->OrigenContro}}</option>
                                                    @else
                                                        <option value="" required>Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        @if($array_control['Servicios'])
                                        <div class="col-4" >
                                            <div class="form-group">
                                                <br>
                                                <label for="manual_de_califi">Manual de calificación <span style="color: red;">(*)</span></label>
                                                <select class="custom-select manual_de_califi" name="manual_de_califi" id="manual_de_califi" style="width: 100%;" required
                                                    @if ($bandera_manual_calificacion == "desactivar")
                                                        disabled
                                                    @endif
                                                >
                                                    @if (!empty($arrayinfo_controvertido[0]->Manual_de_califi))
                                                            <option value="{{$arrayinfo_controvertido[0]->Manual_de_califi}}" selected>{{$arrayinfo_controvertido[0]->Nombre_decreto}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div  class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="total_deficiencia">Total Deficiencia <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_deficiencia" id="total_deficiencia" value="<?php if(isset($arrayinfo_controvertido[0]->Total_deficiencia)) { echo $arrayinfo_controvertido[0]->Total_deficiencia;} ?>" required>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-4 rol_ocupacional" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi) && $arrayinfo_controvertido[0]->Manual_de_califi=='1'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group" >
                                                <br>
                                                <label for="total_rol_ocupacional" style="font-size:0.9em;">Total Laboralmente activo / Rol ocupacional  <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_rol_ocupacional" id="total_rol_ocupacional" value="<?php if(isset($arrayinfo_controvertido[0]->Total_rol_ocupacional)) { echo $arrayinfo_controvertido[0]->Total_rol_ocupacional;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4 total_discapaci" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi)&& $arrayinfo_controvertido[0]->Manual_de_califi=='3'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="total_discapacidad">Total Discapacidad <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_discapacidad" id="total_discapacidad" value="<?php if(isset($arrayinfo_controvertido[0]->Total_discapacidad)) { echo $arrayinfo_controvertido[0]->Total_discapacidad;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4 total_minusva" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi)&& $arrayinfo_controvertido[0]->Manual_de_califi=='3'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="total_minusvalia">Total Minusvalía <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_minusvalia" id="total_minusvalia" value="<?php if(isset($arrayinfo_controvertido[0]->Total_minusvalia)) { echo $arrayinfo_controvertido[0]->Total_minusvalia;} ?>">
                                            </div>
                                        </div>

                                        @if($array_control['Servicios'])
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="porcentaje_pcl">% PCL <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="porcentaje_pcl" id="porcentaje_pcl" value="<?php if(isset($arrayinfo_controvertido[0]->Porcentaje_pcl)) { echo $arrayinfo_controvertido[0]->Porcentaje_pcl;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4" >
                                            <div class="form-group">
                                                <br>
                                                <label for="rango_pcl">Rango PCL <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="rango_pcl" id="rango_pcl" value="<?php if(!empty($arrayinfo_controvertido[0]->Rango_pcl)) { echo $arrayinfo_controvertido[0]->Rango_pcl;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_estructuracion_contro">Fecha de estructuración <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_estructuracion_contro" id="f_estructuracion_contro" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_estructuracion_contro)) { echo $arrayinfo_controvertido[0]->F_estructuracion_contro;} ?>" required>
                                                <span class="d-none" id="f_estructuracion_contro_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-4" style="display: none">
                                            <div class="form-group">
                                                <br>
                                                <label for="n_pago_jnci_contro">N° orden de pago (JRCI)</label>
                                                <input type="text" class="form-control n_pago_jnci_contro" name="n_pago_jnci_contro" id="n_pago_jnci_contro" value="<?php if(!empty($arrayinfo_controvertido[0]->N_pago_jnci_contro)) { echo $arrayinfo_controvertido[0]->N_pago_jnci_contro;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4" style="display: none">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_pago_jnci_contro">Fecha pago (JRCI)</label>
                                                <input type="date" class="form-control" name="f_pago_jnci_contro" id="f_pago_jnci_contro" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_pago_jnci_contro)) { echo $arrayinfo_controvertido[0]->F_pago_jnci_contro;} ?>">
                                                <span class="d-none" id="f_pago_jnci_contro_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4" style="display: none">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_radica_pago_jnci_contro">Fecha de radicación pago (JRCI)</label>
                                                <input type="date" class="form-control" name="f_radica_pago_jnci_contro" id="f_radica_pago_jnci_contro" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_radica_pago_jnci_contro)) { echo $arrayinfo_controvertido[0]->F_radica_pago_jnci_contro;} ?>">
                                                <span class="d-none" id="f_radica_pago_jnci_contro_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4" style="display: none">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_envio_jrci">Fecha de envío a (JRCI)</label>
                                                <input type="date" class="form-control" name="f_envio_jrci" id="f_envio_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_envio_jrci)) { echo $arrayinfo_controvertido[0]->F_envio_jrci;} ?>">
                                                <span class="d-none" id="f_envio_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alerta_diagnosticos alert alert-danger mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php dump($array_datos_diagnostico_motcalifi_contro)?>
                                                <input type="submit" id="guardar_datos_controvertido_j" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_controvertido_juntas alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--- Dictamen emitido por la Junta Regional de Calificación de Invalidez (JRCI) -->
                        <div class="card-info" id="div_dicta_jun_regio">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Dictamen emitido por la Junta Regional de Calificación de Invalidez (JRCI)</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarEmitidoJrci" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="jrci_califi_invalidez">Junta Regional de Calificación de Invalidez (JRCI)</label>
                                                <input type="text" class="form-control" name="jrci_califi_invalidez" id="jrci_califi_invalidez" value="<?php if(!empty($arrayinfo_controvertido[0]->JrciNombre)) { echo $arrayinfo_controvertido[0]->JrciNombre;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="n_dictamen_jrci_emitido">N° Dictamen (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_dictamen_jrci_emitido" id="n_dictamen_jrci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->N_dictamen_jrci_emitido)) { echo $arrayinfo_controvertido[0]->N_dictamen_jrci_emitido;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_dictamen_jrci_emitido">Fecha Dictamen (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_dictamen_jrci_emitido" id="f_dictamen_jrci_emitido" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_dictamen_jrci_emitido)) { echo $arrayinfo_controvertido[0]->F_dictamen_jrci_emitido;} ?>" required>
                                                <span class="d-none" id="f_dictamen_jrci_emitido_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                    <div class="col-12">
                                            <div class="alert alert-warning mensaje_confirmacion_emitido_jrci" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="resultado_insercion_cie10_jrci_emitido" role="alert"></div>
                                            <div class="table-responsive">
                                                <table id="listado_diagnostico_cie10_jrci_emitido" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>CIE-10 (JRCI)</th>
                                                            <th>Nombre CIE-10 (JRCI)</th>
                                                            <th>Descripción complementaria del DX (JRCI)</th>
                                                            <th>Lateralidad Dx (JRCI)</th>
                                                            <th>Origen Dx (JRCI)</th>
                                                            <th>Dx Principal (JRCI)</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_jrci_emitido_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($array_datos_diagnostico_motcalifi_emitido_jrci))
                                                            @foreach ($array_datos_diagnostico_motcalifi_emitido_jrci as $diagnostico_emitido)
                                                            <tr class="fila_diagnosticos_{{$diagnostico_emitido->Id_Diagnosticos_motcali}}" id="datos_diagnostico_emitido_jrci">
                                                                <td>{{$diagnostico_emitido->Codigo}}</td>
                                                                <td>{{$diagnostico_emitido->Nombre_CIE10}}</td>
                                                                <td>{{$diagnostico_emitido->Deficiencia_motivo_califi_condiciones}}</td>
                                                                <td>{{$diagnostico_emitido->Nombre_parametro_lateralidad}}</td>
                                                                <td>{{$diagnostico_emitido->Nombre_parametro_origen}}</td>
                                                                <td>
                                                                    <input type="checkbox" id="checkbox_dx_principal_visual_emitido_Cie10_{{$diagnostico_emitido->Id_Diagnosticos_motcali}}" class="checkbox_dx_principal_visual_Cie10_{{$diagnostico_emitido->Id_Diagnosticos_motcali}}" data-id_fila_checkbox_dx_principal_cie10_emitido_visual="{{$diagnostico_emitido->Id_Diagnosticos_motcali}}" <?php if($diagnostico_emitido->Principal == "Si"):?> checked <?php endif?> style="transform: scale(1.2) !important;">
                                                                </td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_jrci_emitido{{$diagnostico_emitido->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnostico_emitido->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnostico_emitido->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="origen_jrci_emitido">Origen (JRCI)</label>
                                                <select class="custom-select origen_jrci_emitido" name="origen_jrci_emitido" id="origen_jrci_emitido" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Origen_jrci_emitido))
                                                            <option value="{{$arrayinfo_controvertido[0]->Origen_jrci_emitido}}" selected>{{$arrayinfo_controvertido[0]->OrigenEmitidoJrci}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        @if ($array_control['Servicios'])
                                            <div class="col-4">
                                                <div class="form-group">
                                                <br>
                                                <label for="manual_de_califi_jrci_emitido">Manual de calificación (JRCI) <span style="color: red;">(*)</span></label>
                                                <select class="custom-select manual_de_califi_jrci_emitido" name="manual_de_califi_jrci_emitido" id="manual_de_califi_jrci_emitido" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Manual_de_califi_jrci_emitido))
                                                            <option value="{{$arrayinfo_controvertido[0]->Manual_de_califi_jrci_emitido}}" selected>{{$arrayinfo_controvertido[0]->Nombre_decretoJrci}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                            </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="total_deficiencia_jrci_emitido">Total Deficiencia (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_deficiencia_jrci_emitido" id="total_deficiencia_jrci_emitido" value="<?php if(isset($arrayinfo_controvertido[0]->Total_deficiencia_jrci_emitido)) { echo $arrayinfo_controvertido[0]->Total_deficiencia_jrci_emitido;} ?>">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-4 rol_ocupacional_jrci_emitido" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi_jrci_emitido) && $arrayinfo_controvertido[0]->Manual_de_califi_jrci_emitido=='1'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group" >
                                                <br>
                                                <label for="total_rol_ocupacional_jrci_emitido" style='font-size:0.8em;'>Total Laboralmente activo / Rol ocupacional (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_rol_ocupacional_jrci_emitido" id="total_rol_ocupacional_jrci_emitido" value="<?php if(isset($arrayinfo_controvertido[0]->Total_rol_ocupacional_jrci_emitido)) { echo $arrayinfo_controvertido[0]->Total_rol_ocupacional_jrci_emitido;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4 total_discapaci_jrci_emitido" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi_jrci_emitido)&& $arrayinfo_controvertido[0]->Manual_de_califi_jrci_emitido=='3'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="total_discapacidad_jrci_emitido">Total Discapacidad (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_discapacidad_jrci_emitido" id="total_discapacidad_jrci_emitido" value="<?php if(isset($arrayinfo_controvertido[0]->Total_discapacidad_jrci_emitido)) { echo $arrayinfo_controvertido[0]->Total_discapacidad_jrci_emitido;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4 total_minusva_jrci_emitido" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi_jrci_emitido)&& $arrayinfo_controvertido[0]->Manual_de_califi_jrci_emitido=='3'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="total_minusvalia_jrci_emitido">Total Minusvalía (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_minusvalia_jrci_emitido" id="total_minusvalia_jrci_emitido" value="<?php if(isset($arrayinfo_controvertido[0]->Total_minusvalia_jrci_emitido)) { echo $arrayinfo_controvertido[0]->Total_minusvalia_jrci_emitido;} ?>">
                                            </div>
                                        </div>
                                        @if ($array_control['Servicios'])
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="porcentaje_pcl_jrci_emitido">% PCL (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="porcentaje_pcl_jrci_emitido" id="porcentaje_pcl_jrci_emitido" value="<?php if(isset($arrayinfo_controvertido[0]->Porcentaje_pcl_jrci_emitido)) { echo $arrayinfo_controvertido[0]->Porcentaje_pcl_jrci_emitido;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="rango_pcl_jrci_emitido">Rango PCL (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="rango_pcl_jrci_emitido" id="rango_pcl_jrci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->Rango_pcl_jrci_emitido)) { echo $arrayinfo_controvertido[0]->Rango_pcl_jrci_emitido;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_estructuracion_contro_jrci_emitido">Fecha de estructuración (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_estructuracion_contro_jrci_emitido" id="f_estructuracion_contro_jrci_emitido" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_estructuracion_contro_jrci_emitido)) { echo $arrayinfo_controvertido[0]->F_estructuracion_contro_jrci_emitido;} ?>">
                                                <span class="d-none" id="f_estructuracion_contro_jrci_emitido_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="resumen_dictamen_jrci">Resumen Dictamen (JRCI)</label>
                                                <textarea class="form-control soloPrimeraLetraMayus" name="resumen_dictamen_jrci " id="resumen_dictamen_jrci" cols="30" rows="5" style="resise:none;"><?php if(!empty($arrayinfo_controvertido[0]->Resumen_dictamen_jrci)) { echo $arrayinfo_controvertido[0]->Resumen_dictamen_jrci;} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_noti_dictamen_jrci">Fecha de notificación  dictamen (JRCI)</label>
                                                <input type="date" class="form-control" name="f_noti_dictamen_jrci" id="f_noti_dictamen_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_noti_dictamen_jrci)) { echo $arrayinfo_controvertido[0]->F_noti_dictamen_jrci;} ?>">
                                                <span class="d-none" id="f_noti_dictamen_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_radica_dictamen_jrci">Fecha de Radicado entrada Dictamen (JRCI)</label>
                                                <input type="date" class="form-control" name="f_radica_dictamen_jrci" id="f_radica_dictamen_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_radica_dictamen_jrci)) { echo $arrayinfo_controvertido[0]->F_radica_dictamen_jrci;} ?>">
                                                <span class="d-none" id="f_radica_dictamen_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_maxima_recurso_jrci">Fecha máxima para recurso ante JRCI</label>
                                                <input type="date" class="form-control" name="f_maxima_recurso_jrci" id="f_maxima_recurso_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->F_maxima_recurso_jrci)) { echo $arrayinfo_controvertido[0]->F_maxima_recurso_jrci;} ?>" readonly>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_emitido_jrci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_emitido_jrci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--- Revisión ante concepto de la Junta Regional -->
                        <div class="card-info" id="div_rev_concep_jun_regio">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Revisión ante concepto de la Junta Regional</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarRevisionjrci" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="decision">Pronunciamiento ante Dictamen de JRCI:</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_dictamen_jrci" id="acuerdo_revision_jrci" value="Acuerdo" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Acuerdo'){ ?> checked <?php } ?>>
                                                    <label class="form-check-label custom-control-label" for="acuerdo_revision_jrci"><strong>Acuerdo</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_dictamen_jrci" id="desacuerdo_revision_jrci" value="Desacuerdo" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Desacuerdo'){ ?> checked <?php } ?>>
                                                    <label class="form-check-label custom-control-label" for="desacuerdo_revision_jrci"><strong>Desacuerdo</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div <?php if(!empty($arrayinfo_controvertido[0]->Contro_origen)){ ?> class="col-2" <?php }else{ ?> class="col-2 d-none" <?php } ?>>
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_dictamen_jrci" id="silecion_revision_jrci" value="Silencio" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Silencio'){ ?> checked <?php } ?>>
                                                    <label class="form-check-label custom-control-label" for="silecion_revision_jrci"><strong>Silencio</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div <?php if(!empty($arrayinfo_controvertido[0]->Contro_pcl)){ ?> class="col-2" <?php }else{ ?> class="col-2 d-none" <?php } ?> >
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_dictamen_jrci" id="informativo_revision_jrci" value="Informativo" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Informativo'){ ?> checked <?php } ?>>
                                                    <label class="form-check-label custom-control-label" for="informativo_revision_jrci"><strong>Informativo</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 row_causal_decision" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci)&& $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Acuerdo' || !empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Desacuerdo'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="causal_decision">Causal de decisión</label>
                                                <input type="text" class="form-control soloPrimeraLetraMayus" name="causal_decision" id="causal_decision" value="<?php echo $arrayinfo_controvertido[0]->Causal_decision_jrci ?? '' ?>">

                                                {{-- Desactivado segun PSB024 <select class="custom-select causal_decision" name="causal_decision" id="causal_decision" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Causal_decision_jrci))
                                                            <option value="{{$arrayinfo_controvertido[0]->Causal_decision_jrci}}" selected>{{$arrayinfo_controvertido[0]->NombreCausal}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select> --}}
                                            </div>
                                        </div>
                                        <div class="col-12 row_sustenta_jrci" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci)){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <label for="sustentacion_concepto_jrci">Sustentación ante concepto de la JRCI <span style="color: red;">(*)</span></span></label>
                                                <textarea class="form-control soloPrimeraLetraMayus" name="sustentacion_concepto_jrci " id="sustentacion_concepto_jrci" cols="30" rows="5" style="resise:none;"><?php if(!empty($arrayinfo_controvertido[0]->Sustentacion_concepto_jrci)) { echo $arrayinfo_controvertido[0]->Sustentacion_concepto_jrci;} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-4 row_f_sustenta_jrci" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci)){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="f_sustenta_jrci">Fecha de sustentación ante la JRCI</label>
                                                <input type="date" class="form-control" name="f_sustenta_jrci" id="f_sustenta_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->F_sustenta_jrci)) { echo $arrayinfo_controvertido[0]->F_sustenta_jrci;} ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row activa_boton_g" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci)){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                        <div @if(empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && empty($arrayinfo_controvertido[0]->N_dictamen_jrci_emitido) && empty($arrayinfo_controvertido[0]->F_dictamen_jrci_emitido)) class="col-12" @else class="col-6" @endif>
                                            <div class="form-group">
                                                @if (!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && !empty($arrayinfo_controvertido[0]->N_dictamen_jrci_emitido) && !empty($arrayinfo_controvertido[0]->F_dictamen_jrci_emitido))
                                                    <input type="submit" id="guardar_datos_revision_jrci" class="btn btn-info" value="Guardar">
                                                    <input type="hidden" id="bandera_porfesional_pronunciamiento" value="Actualizar">                                                                                                        
                                                @elseif(empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && !empty($arrayinfo_controvertido[0]->N_dictamen_jrci_emitido) && !empty($arrayinfo_controvertido[0]->F_dictamen_jrci_emitido))
                                                    <input type="submit" id="guardar_datos_revision_jrci" class="btn btn-info" value="Guardar">
                                                    <input type="hidden" id="bandera_porfesional_pronunciamiento" value="Guardar">    
                                                @else
                                                    <div class="alert alert-danger" role="alert">
                                                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Antes de pronunciarse debe registrar la información del dictamen de la JRCI en la sección Dictamen emitido por la Junta Regional de Calificación de Invalidez (JRCI).
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_revision_jrci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!---  Presentación de recurso ante la JRCI -->
                        <div class="card-info row_recurso_ante_jrci" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci)&& $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Desacuerdo'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Presentación de recurso ante la JRCI</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarRecursojrci" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_notificacion_recurso_jrci">Fecha notificación de recurso ante JRCI <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_notificacion_recurso_jrci" id="f_notificacion_recurso_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_notificacion_recurso_jrci)) { echo $arrayinfo_controvertido[0]->F_notificacion_recurso_jrci;} ?>" required>
                                                <span class="d-none" id="f_notificacion_recurso_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="n_radicado_recurso_jrci">N° radicado de recurso ante JRCI <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_radicado_recurso_jrci" id="n_radicado_recurso_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->N_radicado_recurso_jrci)) { echo $arrayinfo_controvertido[0]->N_radicado_recurso_jrci;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="termino_contro_propia_jrci">Término de controversia propia ante JRCI <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="termino_contro_propia_jrci" id="termino_contro_propia_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->Termino_contro_propia_jrci)) { echo $arrayinfo_controvertido[0]->Termino_contro_propia_jrci;} ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_recursos_jrci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_recursos_jrci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!---  Firmeza o controversia por otra parte interesada del Dictamen de Calificación de Invalidez (JRCI) -->
                        <div class="card-info" id="div_Firmeza_controversiaJRCI">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5> Firmeza o controversia por otra parte interesada del Dictamen Junta Regional de Calificación de Invalidez (JRCI)</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarInteresadajrci" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="firmeza_intere_contro_jrci" name="firmeza_intere_contro_jrci" value="Controversia ante JRCI" @if (!empty($arrayinfo_controvertido[0]->Firmeza_intere_contro_jrci) && $arrayinfo_controvertido[0]->Firmeza_intere_contro_jrci=='Controversia ante JRCI') checked disabled @endif >
                                                    <label for="firmeza_intere_contro_jrci" class="custom-control-label">Otra parte interesada presenta controversia ante la JRCI</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="firmeza_reposicion_jrci" name="firmeza_reposicion_jrci" value="Reposición por parte JRCI" @if (!empty($arrayinfo_controvertido[0]->Firmeza_reposicion_jrci) && $arrayinfo_controvertido[0]->Firmeza_reposicion_jrci=='Reposición por parte JRCI') checked disabled @endif >
                                                    <label for="firmeza_reposicion_jrci" class="custom-control-label">Reposición del Dictamen por parte de la JRCI</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="firmeza_acta_ejecutoria_jrci" name="firmeza_acta_ejecutoria_jrci" value="Registra Ejecutoria JRCI" @if (!empty($arrayinfo_controvertido[0]->Firmeza_acta_ejecutoria_jrci) && $arrayinfo_controvertido[0]->Firmeza_acta_ejecutoria_jrci=='Registra Ejecutoria JRCI') checked  disabled @endif >
                                                    <label for="firmeza_acta_ejecutoria_jrci" class="custom-control-label">Registrar Acta Ejecutoria emitida por JRCI</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="firmeza_apelacion_jnci_jrci" name="firmeza_apelacion_jnci_jrci" value="Apelación JNCI a JRCI" @if (!empty($arrayinfo_controvertido[0]->Firmeza_apelacion_jnci_jrci) && $arrayinfo_controvertido[0]->Firmeza_apelacion_jnci_jrci=='Apelación JNCI a JRCI') checked  disabled @endif >
                                                    <label for="firmeza_apelacion_jnci_jrci" class="custom-control-label">Apelación del Dictamen ante la JNCI por parte de la JRCI</label>                 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_partes_jrci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_partes_jrci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Otra parte interesada presenta controversia ante la JRCI -->
                        <div  id="row_firmeza_intere" <?php if(!empty($arrayinfo_controvertido[0]->Firmeza_intere_contro_jrci)&& $arrayinfo_controvertido[0]->Firmeza_intere_contro_jrci=='Controversia ante JRCI'){ ?>class="card-info" <?php }else{ ?>class="card-info d-none"<?php } ?> >
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Otra parte interesada presenta controversia ante la JRCI</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarOtraJRCI" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="parte_contro_ante_jrci">Parte que presenta controversia ante JRCI <span style="color: red;">(*)</span></label>
                                                <select class="custom-select parte_contro_ante_jrci" name="parte_contro_ante_jrci" id="parte_contro_ante_jrci" style="width: 100%;" required>
                                                    @if (!empty($arrayinfo_controvertido[0]->Parte_contro_ante_jrci))
                                                            <option value="{{$arrayinfo_controvertido[0]->Parte_contro_ante_jrci}}" selected>{{$arrayinfo_controvertido[0]->NomPresentaJrci}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nombre_presen_contro_jrci">Nombre de la parte que presenta controversia ante la JRCI <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control soloPrimeraLetraMayus" name="nombre_presen_contro_jrci" id="nombre_presen_contro_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->Nombre_presen_contro_jrci)) { echo $arrayinfo_controvertido[0]->Nombre_presen_contro_jrci;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_contro_otra_jrci">Fecha de controversia por otra parte interesada ante la JRCI <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_contro_otra_jrci" id="f_contro_otra_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_contro_otra_jrci)) { echo $arrayinfo_controvertido[0]->F_contro_otra_jrci;} ?>" required>
                                                <span class="d-none" id="f_contro_otra_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tipo_controvierte_califi">Tipo de controversia presentada por otra parte ante JRC</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_origen_jrci" name="contro_origen_jrci" value="Origen Jrci" @if (!empty($arrayinfo_controvertido[0]->Contro_origen_jrci) && $arrayinfo_controvertido[0]->Contro_origen_jrci=='Origen Jrci') checked @endif>
                                                    <label for="contro_origen_jrci" class="custom-control-label">Origen</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_pcl_jrci" name="contro_pcl_jrci" value="% PCL Jrci" @if (!empty($arrayinfo_controvertido[0]->Contro_pcl_jrci) && $arrayinfo_controvertido[0]->Contro_pcl_jrci=='% PCL Jrci') checked @endif>
                                                    <label for="contro_pcl_jrci" class="custom-control-label">%PCL</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_diagnostico_jrci" name="contro_diagnostico_jrci" value="Diagnósticos Jrci" @if (!empty($arrayinfo_controvertido[0]->Contro_diagnostico_jrci) && $arrayinfo_controvertido[0]->Contro_diagnostico_jrci=='Diagnósticos Jrci') checked @endif>
                                                    <label for="contro_diagnostico_jrci" class="custom-control-label">Diagnósticos</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_f_estructura_jrci" name="contro_f_estructura_jrci" value="Fecha estructuración Jrci" @if (!empty($arrayinfo_controvertido[0]->Contro_f_estructura_jrci) && $arrayinfo_controvertido[0]->Contro_f_estructura_jrci=='Fecha estructuración Jrci') checked @endif>
                                                    <label for="contro_f_estructura_jrci" class="custom-control-label">Fecha estructuración</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="contro_m_califi_jrci" name="contro_m_califi_jrci" value="Manual de calificación Jrci" @if (!empty($arrayinfo_controvertido[0]->Contro_m_califi_jrci) && $arrayinfo_controvertido[0]->Contro_m_califi_jrci=='Manual de calificación Jrci') checked @endif>
                                                    <label for="contro_m_califi_jrci" class="custom-control-label">Manual de calificación</label>                 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_interasa_contro_jrci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_interasa_contro_jrci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Reposición del Dictamen por parte de la JRCI -->
                        <div id="row_repo_dictamen" <?php if(!empty($arrayinfo_controvertido[0]->Firmeza_reposicion_jrci)&& $arrayinfo_controvertido[0]->Firmeza_reposicion_jrci=='Reposición por parte JRCI'){ ?>class="card-info" <?php }else{ ?>class="card-info d-none"<?php } ?> >
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Reposición del Dictamen por parte de la JRCI</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarReposicionJRCI" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-2">
                                            <br>
                                            <label for="reposi_propio_jrci"><strong>Propio</strong></label>
                                            <input class="scalesR" type="radio" name="reposicion_dictamen_jrci" id="reposi_propio_jrci" value="Propio" style="margin-left: revert;" @if (!empty($arrayinfo_controvertido[0]->Reposicion_dictamen_jrci) && $arrayinfo_controvertido[0]->Reposicion_dictamen_jrci=='Propio') checked @endif>
                                        </div>
                                        <div class="col-3">
                                            <br>
                                            <label for="reposi_otras_parte_jrci"><strong>Otra parte interesada</strong></label>
                                            <input class="scalesR" type="radio" name="reposicion_dictamen_jrci" id="reposi_otras_parte_jrci" value="Otra parte interesada" style="margin-left: revert;" @if (!empty($arrayinfo_controvertido[0]->Reposicion_dictamen_jrci) && $arrayinfo_controvertido[0]->Reposicion_dictamen_jrci=='Otra parte interesada') checked @endif>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="n_dictamen_reposicion_jrci">N° Dictamen (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_dictamen_reposicion_jrci" id="n_dictamen_reposicion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->N_dictamen_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->N_dictamen_reposicion_jrci;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="f_dictamen_reposicion_jrci">Fecha Dictamen (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_dictamen_reposicion_jrci" id="f_dictamen_reposicion_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_dictamen_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->F_dictamen_reposicion_jrci;} ?>" required>
                                                <span class="d-none" id="f_dictamen_reposicion_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alert alert-warning mensaje_reposicion_jrci" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="resultado_insercion_cie10_jrci_reposicion" role="alert"></div>
                                            <div class="table-responsive">
                                                <table id="listado_diagnostico_cie10_jrci_reposicion" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>CIE-10 (Reposición JRCI)</th>
                                                            <th>Nombre CIE-10 (Reposición JRCI)</th>
                                                            <th>Descripción complementaria del DX (Reposición JRCI)</th>
                                                            <th>Lateralidad Dx (Reposición JRCI)</th>
                                                            <th>Origen Dx (Reposición JRCI)</th>
                                                            <th>Dx Principal (Reposición JRCI)</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_jrci_reposicion_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($array_datos_diagnostico_reposi_dictamen_jrci))
                                                            @foreach ($array_datos_diagnostico_reposi_dictamen_jrci as $diagnostico_reposicion)
                                                            <tr class="fila_diagnosticos_{{$diagnostico_reposicion->Id_Diagnosticos_motcali}}" id="datos_diagnostico_repo_jrci">
                                                                <td>{{$diagnostico_reposicion->Codigo}}</td>
                                                                <td>{{$diagnostico_reposicion->Nombre_CIE10}}</td>
                                                                <td>{{$diagnostico_reposicion->Deficiencia_motivo_califi_condiciones}}</td>
                                                                <td>{{$diagnostico_reposicion->Nombre_parametro_lateralidad}}</td>
                                                                <td>{{$diagnostico_reposicion->Nombre_parametro_origen}}</td>
                                                                <td>
                                                                    <input type="checkbox" id="checkbox_dx_principal_visual_reposicion_Cie10_{{$diagnostico_reposicion->Id_Diagnosticos_motcali}}" class="checkbox_dx_principal_visual_Cie10_{{$diagnostico_reposicion->Id_Diagnosticos_motcali}}" data-id_fila_checkbox_dx_principal_cie10_reposicion_visual="{{$diagnostico_reposicion->Id_Diagnosticos_motcali}}" <?php if($diagnostico_reposicion->Principal == "Si"):?> checked <?php endif?> style="transform: scale(1.2) !important;">
                                                                </td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_jrci_reposicion{{$diagnostico_reposicion->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnostico_reposicion->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnostico_reposicion->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="origen_reposicion_jrci">Origen (Reposición JRCI)</label>
                                                <select class="custom-select origen_reposicion_jrci" name="origen_reposicion_jrci" id="origen_reposicion_jrci" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Origen_reposicion_jrci))
                                                            <option value="{{$arrayinfo_controvertido[0]->Origen_reposicion_jrci}}" selected>{{$arrayinfo_controvertido[0]->Nombre_origenRepoJrci}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        @if ($array_control['Servicios'])
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="manual_reposicion_jrci">Manual de calificación (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <select class="custom-select manual_reposicion_jrci" name="manual_reposicion_jrci" id="manual_reposicion_jrci" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Manual_reposicion_jrci))
                                                            <option value="{{$arrayinfo_controvertido[0]->Manual_reposicion_jrci}}" selected>{{$arrayinfo_controvertido[0]->Nombre_decretoRepoJrci}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="total_deficiencia_reposicion_jrci">Total Deficiencia (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_deficiencia_reposicion_jrci" id="total_deficiencia_reposicion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->Total_deficiencia_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->Total_deficiencia_reposicion_jrci;} ?>">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-4 rol_ocupacional_jrci_reposicion" <?php if(!empty($arrayinfo_controvertido[0]->Manual_reposicion_jrci) && $arrayinfo_controvertido[0]->Manual_reposicion_jrci=='1'){ ?> <?php }else{ ?>style="display:none"<?php } ?> >
                                            <div class="form-group">
                                                <label for="total_rol_reposicion_jrci">Total Rol ocupacional (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_rol_reposicion_jrci" id="total_rol_reposicion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->Total_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->Total_reposicion_jrci;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4 total_dicapacida_jrci_reposicion" <?php if(!empty($arrayinfo_controvertido[0]->Manual_reposicion_jrci) && $arrayinfo_controvertido[0]->Manual_reposicion_jrci=='3'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <label for="total_discapacidad_reposicion_jrci">Total Discapacidad (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input tsype="text" class="form-control" name="total_discapacidad_reposicion_jrci" id="total_discapacidad_reposicion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->Total_discapacidad_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->Total_discapacidad_reposicion_jrci;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4 total_minusva_jrci_reposicion" <?php if(!empty($arrayinfo_controvertido[0]->Manual_reposicion_jrci) && $arrayinfo_controvertido[0]->Manual_reposicion_jrci=='3'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <label for="total_minusvalia_reposicion_jrci">Total Minusvalía (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input tsype="text" class="form-control" name="total_minusvalia_reposicion_jrci" id="total_minusvalia_reposicion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->Total_minusvalia_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->Total_minusvalia_reposicion_jrci;} ?>">
                                            </div>
                                        </div>
                                        @if ($array_control['Servicios'])
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="porcentaje_pcl_reposicion_jrci">% PCL (JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="porcentaje_pcl_reposicion_jrci" id="porcentaje_pcl_reposicion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->Porcentaje_pcl_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->Porcentaje_pcl_reposicion_jrci;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="rango_pcl_reposicion_jrci">Rango PCL (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="rango_pcl_reposicion_jrci" id="rango_pcl_reposicion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->Rango_pcl_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->Rango_pcl_reposicion_jrci;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div  class="col-4">
                                            <div class="form-group">
                                                <label for="f_estructuracion_contro_reposicion_jrci">Fecha de estructuración (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_estructuracion_contro_reposicion_jrci" id="f_estructuracion_contro_reposicion_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_estructuracion_contro_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->F_estructuracion_contro_reposicion_jrci;} ?>">
                                                <span class="d-none" id="f_estructuracion_contro_reposicion_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="resumen_dictamen_reposicion_jrci">Resumen recurso de reposición Dictamen (JRCI)</span></label>
                                                <textarea class="form-control soloPrimeraLetraMayus" name="resumen_dictamen_reposicion_jrci " id="resumen_dictamen_reposicion_jrci" cols="30" rows="5" style="resise:none;" required> <?php if(!empty($arrayinfo_controvertido[0]->Resumen_dictamen_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->Resumen_dictamen_reposicion_jrci;} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_noti_dictamen_reposicion_jrci">Fecha de notificación dictamen (Reposición JRCI)</label>
                                                <input type="date" class="form-control" name="f_noti_dictamen_reposicion_jrci" id="f_noti_dictamen_reposicion_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_noti_dictamen_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->F_noti_dictamen_reposicion_jrci;} ?>">
                                                <span class="d-none" id="f_noti_dictamen_reposicion_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_radica_dictamen_reposicion_jrci">Fecha de Radicado entrada Dictamen (Reposición JRCI)</label>
                                                <input type="date" class="form-control" name="f_radica_dictamen_reposicion_jrci" id="f_radica_dictamen_reposicion_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_radica_dictamen_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->F_radica_dictamen_reposicion_jrci;} ?>">
                                                <span class="d-none" id="f_radica_dictamen_reposicion_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_maxima_apelacion_jrci">Fecha máxima para apelación de recurso ante JRCI</label>
                                                <input type="date" class="form-control" name="f_maxima_apelacion_jrci" id="f_maxima_apelacion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->F_maxima_apelacion_jrci)) { echo $arrayinfo_controvertido[0]->F_maxima_apelacion_jrci;} ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_reposicion_jrci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_reposicion_jrci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Revisión ante recurso de reposición de la Junta Regional -->
                        <div id="div_rev_recur_repo" class="card-info d-none">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Revisión ante recurso de reposición de la Junta Regional</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarRevisionRecursojrci" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="recurso_reposicion">Pronunciamiento ante recurso de reposición Dictamen de JRCI:</label>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_dictamen_repo_jrci" id="acuerdo_revision_repo_jrci" value="Acuerdo" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci=='Acuerdo'){ ?> checked <?php } ?>>
                                                    <label class="form-check-label custom-control-label" for="acuerdo_revision_repo_jrci"><strong>Acuerdo</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_dictamen_repo_jrci" id="desacuerdo_revision_repo_jrci" value="Desacuerdo" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci=='Desacuerdo'){ ?> checked <?php } ?>>
                                                    <label class="form-check-label custom-control-label" for="desacuerdo_revision_repo_jrci"><strong>Desacuerdo</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div <?php if(!empty($arrayinfo_controvertido[0]->Contro_origen)){ ?> class="col-2" <?php }else{ ?> class="col-2 d-none" <?php } ?>>
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_dictamen_repo_jrci" id="silecion_revision_repo_jrci" value="Silencio" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci=='Silencio'){ ?> checked <?php } ?>>
                                                    <label class="form-check-label custom-control-label" for="silecion_revision_repo_jrci"><strong>Silencio</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div <?php if(!empty($arrayinfo_controvertido[0]->Contro_pcl)){ ?> class="col-2" <?php }else{ ?> class="col-2 d-none" <?php } ?> >
                                            <div class="form-group">
                                                <div class="form-check custom-control custom-radio">
                                                    <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="decision_dictamen_repo_jrci" id="informativo_revision_repo_jrci" value="Informativo" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci=='Informativo'){ ?> checked <?php } ?>>
                                                    <label class="form-check-label custom-control-label" for="informativo_revision_repo_jrci"><strong>Informativo</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 row_causal_decision_repo" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci)&& $arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci=='Acuerdo' || !empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci=='Desacuerdo'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="causal_decision_repo">Causal de decisión (Reposición JRCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control soloPrimeraLetraMayus" name="causal_decision_repo" id="causal_decision_repo" value="<?php echo $arrayinfo_controvertido[0]->Causal_decision_repo_jrci ?? '' ?>" required>
                                                {{-- Se cambia a texto segun solicitud PSB024 <select class="custom-select causal_decision" name="causal_decision_repo" id="causal_decision_repo" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Causal_decision_repo_jrci))
                                                            <option value="{{$arrayinfo_controvertido[0]->Causal_decision_repo_jrci}}" selected>{{$arrayinfo_controvertido[0]->NombreCausalRepo}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select> --}}
                                            </div>
                                        </div>
                                        <div class="col-12 row_sustenta_repo_jrci" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci)){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <label for="sustentacion_concepto_repo_jrci">Sustentación ante concepto de la JRCI <span style="color: red;">(*)</span></span></label>
                                                <textarea class="form-control soloPrimeraLetraMayus" name="sustentacion_concepto_repo_jrci " id="sustentacion_concepto_repo_jrci" cols="30" rows="5" style="resise:none;"><?php if(!empty($arrayinfo_controvertido[0]->Sustentacion_concepto_repo_jrci)) { echo $arrayinfo_controvertido[0]->Sustentacion_concepto_repo_jrci;} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-4 row_f_sustenta_reposicion_jrci" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci)){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="f_sustenta_reposicion_jrci">Fecha de sustentación ante recurso de reposición de la JRC</label>
                                                <input type="date" class="form-control" name="f_sustenta_reposicion_jrci" id="f_sustenta_reposicion_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->F_sustenta_reposicion_jrci)) { echo $arrayinfo_controvertido[0]->F_sustenta_reposicion_jrci;} ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row activa_boton_repo_g" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci)){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_concepto_repo_jrci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_concepto_repo_jrci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Apelación de recurso ante la JNCI -->
                        <div class="card-info" id="apela_recu" <?php if(!empty($arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci)&& $arrayinfo_controvertido[0]->Decision_dictamen_repo_jrci=='Desacuerdo'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Apelación de recurso ante la JNCI</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarApelaciónJnci" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_noti_apela_recurso_jrci">Fecha notificación de apelación de recurso ante JRCI <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_noti_apela_recurso_jrci" id="f_noti_apela_recurso_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_noti_apela_recurso_jrci)) { echo $arrayinfo_controvertido[0]->F_noti_apela_recurso_jrci;} ?>" required>
                                                <span class="d-none" id="f_noti_apela_recurso_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="n_radicado_apela_recurso_jrci">N° radicado de apelación de recurso ante JRCI <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_radicado_apela_recurso_jrci" id="n_radicado_apela_recurso_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->N_radicado_apela_recurso_jrci)) { echo $arrayinfo_controvertido[0]->N_radicado_apela_recurso_jrci;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="t_propia_apela_recurso_jrci">Término de controversia propia de apelación de recurso ante JRCI <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="t_propia_apela_recurso_jrci" id="t_propia_apela_recurso_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->T_propia_apela_recurso_jrci)) { echo $arrayinfo_controvertido[0]->T_propia_apela_recurso_jrci;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="correspon_pago_jnci" name="correspon_pago_jnci" value="Corresponde pago a JNCI" @if (!empty($arrayinfo_controvertido[0]->Correspon_pago_jnci) && $arrayinfo_controvertido[0]->Correspon_pago_jnci=='Corresponde pago a JNCI') checked @endif>
                                                    <label for="correspon_pago_jnci" class="custom-control-label">Corresponde pago a JNCI</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div id="row_apela_num" <?php if(!empty($arrayinfo_controvertido[0]->Correspon_pago_jnci)&& $arrayinfo_controvertido[0]->Correspon_pago_jnci=='Corresponde pago a JNCI'){ ?>class="card-info col-4" <?php }else{ ?>class="card-info col-4 d-none"<?php } ?> >
                                            <div class="form-group">
                                                <label for="n_orden_pago_jnci">N° orden de pago (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_orden_pago_jnci" id="n_orden_pago_jnci" value="<?php if(!empty($arrayinfo_controvertido[0]->N_orden_pago_jnci)) { echo $arrayinfo_controvertido[0]->N_orden_pago_jnci;} ?>">
                                            </div>
                                        </div>
                                        <div id="row_apela_fecha" <?php if(!empty($arrayinfo_controvertido[0]->Correspon_pago_jnci)&& $arrayinfo_controvertido[0]->Correspon_pago_jnci=='Corresponde pago a JNCI'){ ?>class="card-info col-4" <?php }else{ ?>class="card-info col-4 d-none"<?php } ?>>
                                            <div class="form-group">
                                                <label for="f_orden_pago_jnci">Fecha pago (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_orden_pago_jnci" id="f_orden_pago_jnci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_orden_pago_jnci)) { echo $arrayinfo_controvertido[0]->F_orden_pago_jnci;} ?>">
                                                <span class="d-none" id="f_orden_pago_jnci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div id="row_apela_fecha_radi" <?php if(!empty($arrayinfo_controvertido[0]->Correspon_pago_jnci)&& $arrayinfo_controvertido[0]->Correspon_pago_jnci=='Corresponde pago a JNCI'){ ?>class="card-info col-4" <?php }else{ ?>class="card-info col-4 d-none"<?php } ?>>
                                            <div class="form-group">
                                                <label for="f_radi_pago_jnci">Fecha de radicación pago (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_radi_pago_jnci" id="f_radi_pago_jnci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_radi_pago_jnci)) { echo $arrayinfo_controvertido[0]->F_radi_pago_jnci;} ?>">
                                                <span class="d-none" id="f_radi_pago_jnci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_apela_jnci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_datos_apela_jnci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Registrar Acta Ejecutoria emitida por JRCI -->
                        <div id="row_acta_ejecutoria" <?php if(!empty($arrayinfo_controvertido[0]->Firmeza_acta_ejecutoria_jrci)&& $arrayinfo_controvertido[0]->Firmeza_acta_ejecutoria_jrci=='Registra Ejecutoria JRCI'){ ?>class="card-info" <?php }else{ ?>class="card-info d-none"<?php } ?>>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Registrar Acta Ejecutoria emitida por JRCI</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarActaEjecuJrci" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="n_acta_ejecutario_emitida_jrci">N° Acta de Ejecutoría emitida por JRCI <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_acta_ejecutario_emitida_jrci" id="n_acta_ejecutario_emitida_jrci" value="<?php if(!empty($arrayinfo_controvertido[0]->N_acta_ejecutario_emitida_jrci)) { echo $arrayinfo_controvertido[0]->N_acta_ejecutario_emitida_jrci;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_acta_ejecutoria_emitida_jrci">Fecha Acta de Ejecutoría emitida por JRCI <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_acta_ejecutoria_emitida_jrci" id="f_acta_ejecutoria_emitida_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_acta_ejecutoria_emitida_jrci)) { echo $arrayinfo_controvertido[0]->F_acta_ejecutoria_emitida_jrci;} ?>" required>
                                                <span class="d-none" id="f_acta_ejecutoria_emitida_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_firmeza_dictamen_jrci">Fecha firmeza Dictamen <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_firmeza_dictamen_jrci" id="f_firmeza_dictamen_jrci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_firmeza_dictamen_jrci)) { echo $arrayinfo_controvertido[0]->F_firmeza_dictamen_jrci;} ?>" required>
                                                <span class="d-none" id="f_firmeza_dictamen_jrci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <a href="javascript:void(0);" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargar Acta de ejecutoría</strong></a>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="dictamen_firme_jrci" name="dictamen_firme_jrci" value="Dictamen en firme" @if (!empty($arrayinfo_controvertido[0]->Dictamen_firme_jrci) && $arrayinfo_controvertido[0]->Dictamen_firme_jrci=='Dictamen en firme') checked @endif disabled>
                                                    <label for="dictamen_firme_jrci" class="custom-control-label">Dictamen en firme</label>                 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_ejecutoria_jrci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_datos_ejecutoria_jrci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Dictamen emitido por la Junta Nacional de Calificación de Invalidez (JNCI) -->
                        <div id="row_emitido_jnci" <?php if(!empty($arrayinfo_controvertido[0]->Firmeza_apelacion_jnci_jrci)&& $arrayinfo_controvertido[0]->Firmeza_apelacion_jnci_jrci=='Apelación JNCI a JRCI'){ ?>class="card-info" <?php }else{ ?>class="card-info d-none"<?php } ?>>
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Dictamen emitido por la Junta Nacional de Calificación de Invalidez (JNCI)</h5>
                            </div>
                            <div class="card-body">
                                <form id="form_guardarEmitidoJnci" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="n_dictamen_jnci_emitido">N° Dictamen (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="n_dictamen_jnci_emitido" id="n_dictamen_jnci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->N_dictamen_jnci_emitido)) { echo $arrayinfo_controvertido[0]->N_dictamen_jnci_emitido;} ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="f_dictamen_jnci_emitido">Fecha Dictamen (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_dictamen_jnci_emitido" id="f_dictamen_jnci_emitido" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_dictamen_jnci_emitido)) { echo $arrayinfo_controvertido[0]->F_dictamen_jnci_emitido;} ?>" required>
                                                <span class="d-none" id="f_dictamen_jnci_emitido_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                    <div class="col-12">
                                            <div class="alert alert-warning mensaje_confirmacion_emitido_jnci" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de agregar una fila nueva es necesario
                                                que diligencie en su totalidad los campos.
                                            </div>
                                            <div class="alert d-none" id="resultado_insercion_cie10_jnci_emitido" role="alert"></div>
                                            <div class="table-responsive">
                                                <table id="listado_diagnostico_cie10_jnci_emitido" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info">
                                                            <th>CIE-10 (JNCI)</th>
                                                            <th>Nombre CIE-10 (JNCI)</th>
                                                            <th>Descripción complementaria del DX (JNCI)</th>
                                                            <th>Lateralidad Dx (JNCI)</th>
                                                            <th>Origen Dx (JNCI)</th>
                                                            <th>Dx Principal (JNCI)</th>
                                                            <th class="centrar"><a href="javascript:void(0);" id="btn_agregar_cie10_jnci_emitido_fila"><i class="fas fa-plus-circle" style="font-size:24px; color:white;"></i></a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($array_datos_diagnostico_motcalifi_emitido_jnci))
                                                            @foreach ($array_datos_diagnostico_motcalifi_emitido_jnci as $diagnostico_emitidos)
                                                            <tr class="fila_diagnosticos_emitido_{{$diagnostico_emitidos->Id_Diagnosticos_motcali}}" id="datos_diagnostico_emitido_jnci">
                                                                <td>{{$diagnostico_emitidos->Codigo}}</td>
                                                                <td>{{$diagnostico_emitidos->Nombre_CIE10}}</td>
                                                                <td>{{$diagnostico_emitidos->Deficiencia_motivo_califi_condiciones}}</td>
                                                                <td>{{$diagnostico_emitidos->Nombre_parametro_lateralidad}}</td>
                                                                <td>{{$diagnostico_emitidos->Nombre_parametro_origen}}</td>
                                                                <td>
                                                                    <input type="checkbox" id="checkbox_dx_principal_visual_emitido_jnci_Cie10_{{$diagnostico_emitidos->Id_Diagnosticos_motcali}}" class="checkbox_dx_principal_visual_Cie10_{{$diagnostico_emitidos->Id_Diagnosticos_motcali}}" data-id_fila_checkbox_dx_principal_cie10_jnci_visual="{{$diagnostico_emitidos->Id_Diagnosticos_motcali}}" <?php if($diagnostico_emitidos->Principal == "Si"):?> checked <?php endif?> style="transform: scale(1.2) !important;">
                                                                </td>
                                                                <td>
                                                                    <div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_diagnosticos_jnci_emitido{{$diagnostico_emitidos->Id_Diagnosticos_motcali}}" data-id_fila_quitar="{{$diagnostico_emitidos->Id_Diagnosticos_motcali}}" data-clase_fila="fila_diagnosticos_{{$diagnostico_emitidos->Id_Diagnosticos_motcali}}" class="text-info"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>
                                                                </td>
                                                            </tr> 
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="origen_jnci_emitido">Origen (JNCI)</label>
                                                <select class="custom-select origen_jnci_emitido" name="origen_jnci_emitido" id="origen_jnci_emitido" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Origen_jnci_emitido))
                                                            <option value="{{$arrayinfo_controvertido[0]->Origen_jnci_emitido}}" selected>{{$arrayinfo_controvertido[0]->NombreOrigen}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        @if ( $array_control['Servicios'])
                                        <div class="col-4" >
                                            <div class="form-group">
                                                <br>
                                                <label for="manual_de_califi_jnci_emitido">Manual de calificación (JNCI) <span style="color: red;">(*)</span></label>
                                                <select class="custom-select manual_de_califi_jnci_emitido" name="manual_de_califi_jnci_emitido" id="manual_de_califi_jnci_emitido" style="width: 100%;">
                                                    @if (!empty($arrayinfo_controvertido[0]->Manual_de_califi_jnci_emitido))
                                                            <option value="{{$arrayinfo_controvertido[0]->Manual_de_califi_jnci_emitido}}" selected>{{$arrayinfo_controvertido[0]->Nombre_decretoJnci}}</option>
                                                    @else
                                                        <option value="">Seleccione una opción</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="total_deficiencia_jnci_emitido">Total Deficiencia (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_deficiencia_jnci_emitido" id="total_deficiencia_jnci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->Total_deficiencia_jnci_emitido)) { echo $arrayinfo_controvertido[0]->Total_deficiencia_jnci_emitido;} ?>">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-4 rol_ocupacional_jnci_emitido" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi_jnci_emitido) && $arrayinfo_controvertido[0]->Manual_de_califi_jnci_emitido=='1'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group" >
                                                <br>
                                                <label for="total_rol_ocupacional_jnci_emitido"  style='font-size:0.8em;'>Total Laboralmente activo / Rol ocupacional (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_rol_ocupacional_jnci_emitido" id="total_rol_ocupacional_jnci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->Total_rol_ocupacional_jnci_emitido)) { echo $arrayinfo_controvertido[0]->Total_rol_ocupacional_jnci_emitido;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4 total_discapaci_jnci_emitido" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi_jnci_emitido)&& $arrayinfo_controvertido[0]->Manual_de_califi_jnci_emitido=='3'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="total_discapacidad_jnci_emitido">Total Discapacidad (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_discapacidad_jnci_emitido" id="total_discapacidad_jnci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->Total_discapacidad_jnci_emitido)) { echo $arrayinfo_controvertido[0]->Total_discapacidad_jnci_emitido;} ?>">
                                            </div>
                                        </div>
                                        <div class="col-4 total_minusva_jnci_emitido" <?php if(!empty($arrayinfo_controvertido[0]->Manual_de_califi_jnci_emitido)&& $arrayinfo_controvertido[0]->Manual_de_califi_jnci_emitido=='3'){ ?> <?php }else{ ?>style="display:none"<?php } ?>>
                                            <div class="form-group">
                                                <br>
                                                <label for="total_minusvalia_jnci_emitido">Total Minusvalía (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="total_minusvalia_jnci_emitido" id="total_minusvalia_jnci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->Total_minusvalia_jnci_emitido)) { echo $arrayinfo_controvertido[0]->Total_minusvalia_jnci_emitido;} ?>">
                                            </div>
                                        </div>
                                        @if ( $array_control['Servicios'])
                                        <div class="col-4" >
                                            <div class="form-group">
                                                <br>
                                                <label for="porcentaje_pcl_jnci_emitido">% PCL (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="porcentaje_pcl_jnci_emitido" id="porcentaje_pcl_jnci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->Porcentaje_pcl_jnci_emitido)) { echo $arrayinfo_controvertido[0]->Porcentaje_pcl_jnci_emitido;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4" >
                                            <div class="form-group">
                                                <br>
                                                <label for="rango_pcl_jnci_emitido">Rango PCL (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="rango_pcl_jnci_emitido" id="rango_pcl_jnci_emitido" value="<?php if(!empty($arrayinfo_controvertido[0]->Rango_pcl_jnci_emitido)) { echo $arrayinfo_controvertido[0]->Rango_pcl_jnci_emitido;} ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_estructuracion_contro_jnci_emitido">Fecha de estructuración (JNCI) <span style="color: red;">(*)</span></label>
                                                <input type="date" class="form-control" name="f_estructuracion_contro_jnci_emitido" id="f_estructuracion_contro_jnci_emitido" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_estructuracion_contro_jnci_emitido)) { echo $arrayinfo_controvertido[0]->F_estructuracion_contro_jnci_emitido;} ?>">
                                                <span class="d-none" id="f_estructuracion_contro_jnci_emitido_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="resumen_dictamen_jnci">Resumen Dictamen (JNCI)</span></label>
                                                <textarea class="form-control soloPrimeraLetraMayus" name="resumen_dictamen_jnci" id="resumen_dictamen_jnci" cols="30" rows="5" style="resise:none;" required><?php if(!empty($arrayinfo_controvertido[0]->Resumen_dictamen_jnci)) { echo $arrayinfo_controvertido[0]->Resumen_dictamen_jnci;} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="sustentacion_dictamen_jnci">Sustentación ante concepto de la (JNCI)</span></label>
                                                <textarea class="form-control soloPrimeraLetraMayus" name="sustentacion_dictamen_jnci" id="sustentacion_dictamen_jnci" cols="30" rows="5" style="resise:none;" required><?php if(!empty($arrayinfo_controvertido[0]->Sustentacion_dictamen_jnci)) { echo $arrayinfo_controvertido[0]->Sustentacion_dictamen_jnci;} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_sustenta_ante_jnci">Fecha de sustentación ante la (JNCI)</label>
                                                <input type="date" class="form-control" name="f_sustenta_ante_jnci" id="f_sustenta_ante_jnci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_sustenta_ante_jnci)) { echo $arrayinfo_controvertido[0]->F_sustenta_ante_jnci;} ?>">
                                                <span class="d-none" id="f_sustenta_ante_jnci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_noti_ante_jnci">Fecha de notificación dictamen (JNCI)</label>
                                                <input type="date" class="form-control" name="f_noti_ante_jnci" id="f_noti_ante_jnci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_noti_ante_jnci)) { echo $arrayinfo_controvertido[0]->F_noti_ante_jnci;} ?>">
                                                <span class="d-none" id="f_noti_ante_jnci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_radica_dictamen_jnci">Fecha de Radicado entrada Dictamen (JNCI)</label>
                                                <input type="date" class="form-control" name="f_radica_dictamen_jnci" id="f_radica_dictamen_jnci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_radica_dictamen_jnci)) { echo $arrayinfo_controvertido[0]->F_radica_dictamen_jnci;} ?>">
                                                <span class="d-none" id="f_radica_dictamen_jnci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <br>
                                                <label for="f_envio_jnci">Fecha envió (JNCI)</label>
                                                <input type="date" class="form-control" name="f_envio_jnci" id="f_envio_jnci" max="{{now()->format('Y-m-d')}}" min="1900-01-01" value="<?php if(!empty($arrayinfo_controvertido[0]->F_envio_jnci)) { echo $arrayinfo_controvertido[0]->F_envio_jnci;} ?>">
                                                <span class="d-none" id="f_envio_jnci_alerta" style="color: red; font-style: italic;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="submit" id="guardar_datos_emitido_jnci" class="btn btn-info" value="Guardar">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alerta_datos_emitido_jnci alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Comite Interdisciplinario -->                    
                        {{-- <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Comité Interdiciplinario</h5>
                            </div>
                            <form id="form_comite_interdisciplinario" action="POST">                            
                                <div class="card-body">
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
                        </div>   --}}

                        <!--  Correspondencia -->
                        <div class="card-info d-none" id="div_correspondencia">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Correspondencia</h5>
                                <input class="d-none" type="hidden" id="hay_datos_form_corres" value={{true}}>
                            </div>
                            <form id="form_correspondencia_juntas" action="POST">
                                <div class="card-body">
                                    <?php if(empty($arrayinfo_controvertido[0]->JrciNombre)):?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-danger" role="alert">
                                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede guardar esta correspondencia debido a que
                                                    el destinatario principal está vacío. Por favor diríjase al módulo principal de juntas, ventana Gestión de controversia - Seguimiento,
                                                    sección Información de la controversia y seleccione la Junta Regional de Calificación de Invalidez (JRCI).
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="destinatario_principal">Destinatario Principal</label>                                            
                                                <input type="text" class="form-control" name="destinatario_principal" id="destinatario_principal" value="<?php if(!empty($arrayinfo_controvertido[0]->JrciNombre)) { echo $arrayinfo_controvertido[0]->JrciNombre;} ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="form-group text-center">
                                                <div class="custom-control custom-checkbox">
                                                    {{-- <input class="dependencia_justificacion custom-control-input" type="checkbox" id="otrodestinariop" name="otrodestinariop" value="Si" checked> --}}
                                                    <input class="custom-control-input" type="checkbox" id="otrodestinariop" name="otrodestinariop" value="Si">                                                    
                                                    <label for="otrodestinariop" class="custom-control-label">Otro Destinatario Principal</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3" id=div_tipo_destinatario_principal>
                                            <div class="form-group">
                                                <label for="tipo_destinatario_principal">Tipo Destinatario Principal <span style="color: red;">(*)</span></label>
                                                <input type="hidden" id="db_tipo_destinatario_principal"> 
                                                <select class="tipo_destinatario_principal custom-select" name="tipo_destinatario_principal" id="tipo_destinatario_principal" style="width: 100%">                                                    
                                                </select>                                                
                                            </div>
                                        </div>
                                        <div class="col-3" id="div_nombre_destinatariopri">
                                            <div class="form-group">
                                                <label for="nombre_destinatariopri">Nombre del destinatario principal <span style="color: red;">(*)</span></label>
                                                <input type="hidden" id="db_nombre_destinatariopri" value="">
                                                <select class="nombre_destinatariopri custom-select" name="nombre_destinatariopri" id="nombre_destinatariopri" style="width: 100%">                                                    
                                                </select>                                                
                                            </div>      
                                        </div>
                                        <div class="col-3" id="div_nombre_destinatariopri_afi_">
                                            <div class="form-group">
                                                <label for="nombre_destinatario_afi">Nombre del destinatario principal <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="nombre_destinatario_afi" id="nombre_destinatario_afi" disabled>
                                            </div>      
                                        </div>
                                        <div class="col-3" id="div_nombre_destinatariopri_empl">
                                            <div class="form-group">
                                                <label for="nombre_destinatario_emp">Nombre del destinatario principal <span style="color: red;">(*)</span></label>
                                                <input type="text" class="form-control" name="nombre_destinatario_emp" id="nombre_destinatario_emp" disabled>
                                            </div>      
                                        </div>
                                    </div>

                                    <div class="row" id="div_datos_otro_destinatario">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="nombre_destinatario">Nombre destinatario</label>
                                                <input type="text" class="form-control" name="nombre_destinatario" id="nombre_destinatario" >                                                
                                            </div>      
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="nitcc_destinatario">NIT / CC</label>
                                                <input type="text" class="form-control" name="nitcc_destinatario" id="nitcc_destinatario" >                                                
                                            </div>      
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="direccion_destinatario">Dirección destinatario</label>
                                                <input type="text" class="form-control" name="direccion_destinatario" id="direccion_destinatario" >                                                
                                            </div>      
                                        </div>                                    
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="telefono_destinatario">Teléfono destinatario</label>
                                                <input type="text" class="form-control" name="telefono_destinatario" id="telefono_destinatario" >                                                
                                            </div>      
                                        </div>
                                    
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="email_destinatario">E-mail destinatario</label>
                                                <input type="email" class="form-control" name="email_destinatario" id="email_destinatario" >                                                
                                            </div>      
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="departamento_destinatario">Departamento</label>
                                                <input type="text" class="form-control" name="departamento_destinatario" id="departamento_destinatario" >                                                
                                            </div>      
                                        </div>                                        
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="ciudad_destinatario">Ciudad</label>
                                                <input type="text" class="form-control" name="ciudad_destinatario" id="ciudad_destinatario" >                                                
                                            </div>      
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-warning" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para mostrar todo el asunto <span id="tipo_descarga"></span> que usted escriba,
                                                debe incluir la etiqueta
                                                @if (!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Acuerdo')
                                                    <span id="etiqueta_asunto">Número de Dictamen JRCI </span>
                                                @elseif(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Desacuerdo')
                                                    <span id="etiqueta_asunto">Fecha de Dictamen JRCI </span>
                                                @endif
                                                dentro del campo Asunto.
                                            </div>
                                            <div class="form-group">
                                                <label for="Asunto">Asunto <span style="color: red;">(*)</label>
                                                <br>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_nro_dictamen_jrci_asunto">Número de Dictamen JRCI</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_fecha_dictamen_jrci_asunto">Fecha de Dictamen JRCI</button>
                                                <input type="text" class="form-control" name="Asunto" id="Asunto" required>                                           
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            {{-- <div class="alert alert-warning" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para mostrar todo el cuerpo del comunicado (dentro del word) que usted escriba, 
                                                debe incluir las etiquetas de Nombre Afiliado, Nombre Junta Regional, 
                                                @if ($array_datos_controversiaJuntas[0]->Id_Servicio == 13)
                                                Nombre CIE-10 JRCI, %Pcl JRCI, Fecha Estructuracion JRCI, 
                                                @elseif ($array_datos_controversiaJuntas[0]->Id_Servicio == 12)
                                                Origen Dx JRCI, CIE-10 - Nombre CIE-10 JRCI, 
                                                @endif
                                                Sustentación Concepto JRCI (Revisión ante concepto de la Junta Regional) dentro de la sección Cuerpo del Comunicado.
                                            </div>
                                            <div class="form-group">
                                                <label for="cuerpo_comunicado">Cuerpo del comunicado <span style="color: red;">(*)</label>
                                                <br>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_nombre_afiliado">Nombre Afiliado</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_nombre_junta_regional">Nombre Junta Regional</button>
                                                @if ($array_datos_controversiaJuntas[0]->Id_Servicio == 13)
                                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_cie10_jrci">Nombre CIE-10 JRCI</button>
                                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_pcl_jrci">%Pcl JRCI</button>
                                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_f_estructuracion_jrci">Fecha Estructuracion JRCI</button>
                                                @elseif ($array_datos_controversiaJuntas[0]->Id_Servicio == 12)
                                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_origen_dx_jrci">Origen Dx JRCI</button>
                                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_cie_nombre_jrci">CIE-10 - Nombre CIE-10 JRCI</button>
                                                @endif
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_sustentacion_jrci">Sustentación Concepto JRCI (Revisión ante concepto de la Junta Regional)</button>
                                                <?php if(count($array_comunicados_correspondencia) > 0): ?>
                                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_sustentacion_jrci1">Sustentación Concepto JRCI (Revisión ante recurso de reposición de la Junta Regional)</button>
                                                <?php endif ?>

                                                <textarea id="cuerpo_comunicado" required></textarea>                                                                                         
                                            </div> --}}

                                            {{-- PREVISIONAL --}}
                                            <div class="alert alert-warning" role="alert">
                                                <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Para mostrar todo el cuerpo del comunicado <span id="tipo_descarga_cuerpo"></span> que usted escriba,
                                                debe incluir las etiquetas 
                                                @if (!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Acuerdo')
                                                    <span id="etiquetas_cuerpo">Número de Dictamen JRCI, Fecha de Dictamen JRCI, Nombre Afiliado, Tipo Documento Afiliado, Número Documento Afiliado, CIE-10 - Nombre CIE-10 JRCI, 
                                                        %Pcl JRCI, Origen Dx JRCI, Fecha Estructuracion JRCI, Decreto Calificador JRCI, </span>
                                                @elseif(!empty($arrayinfo_controvertido[0]->Decision_dictamen_jrci) && $arrayinfo_controvertido[0]->Decision_dictamen_jrci=='Desacuerdo')
                                                    <span id="etiquetas_cuerpo"></span>
                                                @endif
                                                Sustentación Concepto JRCI (Revisión ante concepto de la Junta Regional) dentro de la sección Cuerpo del Comunicado.
                                            </div>
                                            <div class="form-group">
                                                <label for="cuerpo_comunicado">Cuerpo del comunicado <span style="color: red;">(*)</label>
                                                <br>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_nro_dictamen_jrci">Número de Dictamen JRCI</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_fecha_dictamen_jrci">Fecha de Dictamen JRCI</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_nombre_afiliado">Nombre Afiliado</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_tipo_documento_afiliado">Tipo Documento Afiliado</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_documento_afiliado">Número Documento Afiliado</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_cie_nombre_jrci">CIE-10 - Nombre CIE-10 JRCI</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_pcl_jrci">%Pcl JRCI</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_origen_dx_jrci">Origen Dx JRCI</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_f_estructuracion_jrci">Fecha Estructuracion JRCI</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_decreto_calificador_jrci">Decreto Calificador JRCI</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_sustentacion_jrci">Sustentación Concepto JRCI (Revisión ante concepto de la Junta Regional)</button>
                                                <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_tipos_contro">Tipo Controversia Primera Calificación</button>
                                                <?php if(count($array_comunicados_correspondencia) > 0): ?>
                                                    <button class="btn btn-sm btn-secondary mb-2" id="btn_insertar_sustentacion_jrci1">Sustentación Concepto JRCI (Revisión ante recurso de reposición de la Junta Regional)</button>
                                                <?php endif ?>

                                                <textarea id="cuerpo_comunicado" required></textarea>   
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tipo_clasificacion">Copia a partes interesadas</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="afiliado" name="afiliado" value="Afiliado">
                                                    <label for="afiliado" class="custom-control-label">Afiliado</label>                 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="empleador" name="empleador" value="Empleador">                                                    
                                                    <label for="empleador" class="custom-control-label">Empleador</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="eps" name="eps" value="EPS">                                                    
                                                    <label for="eps" class="custom-control-label">EPS</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="afp" name="afp" value="AFP">                                                    
                                                    <label for="afp" class="custom-control-label">AFP</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="arl" name="arl" value="ARL">                                                    
                                                    <label for="arl" class="custom-control-label">ARL</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="jrci" name="jrci" value="jrci">                                                    
                                                    <label for="jrci" class="custom-control-label">Junta Regional de Calificación de Invalidez</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-4" id="div_cual">
                                            {{-- <div class="form-group">
                                                <label for="cual">¿Cuál?<span style="color: red;">(*)</span></label>
                                                <input type="hidden" id="bd_cual_jr">
                                                <select class="cual custom-select" name="cual" id="cual" style="width: 100%">
                                                    <option value="">Seleccione una opción</option>
                                                </select>
                                                                                              
                                            </div> --}}
                                            <input type="text" class="form-control" name="jrci_copia" id="jrci_copia" value="<?php if(!empty($arrayinfo_controvertido[0]->JrciNombre)) { echo $arrayinfo_controvertido[0]->JrciNombre;} ?>" disabled>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="jnci" name="jnci" value="jnci">                                                    
                                                    <label for="jnci" class="custom-control-label">Junta Nacional de Calificación de Invalidez</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-1">
                                            <div class="form-group">
                                                <label for="anexos">No. Anexos</label>
                                                <input type="number" class="form-control" name="anexos" id="anexos">                                                
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group">
                                                <label for="elaboro">Elaboró</label>
                                                {{-- <input type="text" class="form-control" name="elaboro" id="elaboro" disabled> --}}
                                                @if(!empty($array_comite_interdisciplinario[0]->Elaboro))
                                                <input type="text" class="form-control" name="elaboro" id="elaboro" value="{{$array_comite_interdisciplinario[0]->Elaboro}}" disabled>                                                
                                                @else
                                                    <input type="text" class="form-control" name="elaboro" id="elaboro" value="{{$user->name}}" disabled>                                                
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group">
                                                <label for="reviso">Revisó<span style="color: red;">(*)</span></label>
                                                <select class="reviso custom-select" name="reviso" id="reviso" style="width: 100%" required>
                                                    <option value="">Seleccione una opción</option>
                                                </select>                                                
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="firmar" name="firmar" value="Firmar">                                                    
                                                    <label for="firmar" class="custom-control-label">Firmar</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="ciudad">Ciudad</label>
                                                {{-- <input type="text" class="form-control" name="ciudad" id="ciudad">  --}}
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
                                                {{-- <input type="date" class="form-control" name="f_correspondencia" id="f_correspondencia" disabled> --}}
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
                                                <input type="hidden" class="form-control" name="radicado_comunicado_manual" id="radicado_comunicado_manual" value="{{$consecutivo}}" disabled>
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                @if(!empty($arrayinfo_controvertido[0]->JrciNombre) && count($array_comunicados_correspondencia) == 0)
                                                    <input type="submit" id="GuardarCorrespondencia" name="GuardarCorrespondencia" class="btn btn-info" value="Guardar">                                                
                                                    <input hidden="hidden" type="text" id="bandera_correspondecia_guardar_actualizar" value="Guardar">
                                                @elseif(!empty($arrayinfo_controvertido[0]->JrciNombre) && count($array_comunicados_correspondencia) > 0)
                                                    <input type="submit" id="ActualizarCorrespondencia" name="ActualizarCorrespondencia" class="btn btn-info" value="Actualizar">                                                
                                                    <input hidden="hidden" type="text" id="bandera_correspondecia_guardar_actualizar" value="Actualizar">
                                                @endif
                                                {{-- @if (count($array_comunicados_correspondencia) > 0) --}}
                                                    {{-- <button class="btn btn-info" id="generar_proforma_recurso_reposicion_pcl">Word</button> --}}
                                                {{-- @endif --}}
                                            </div>
                                        </div>
                                        <div id="div_alerta_Correspondencia" class="col-12 d-none">
                                            <div class="form-group"> 
                                                <div class="alerta_Correspondencia alert alert-success mt-2 mr-auto" role="alert"></div>
                                            </div>
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
                        {{-- Comunicados --}}
                        <div class="card-info">
                            <div class="card-header text-center" style="border: 1.5px solid black;">
                                <h5>Comunicados</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12" id="msg_alerta">
                                        <div class="alert alert-warning mensaje_confirmacion_emitido_jnci" role="alert">
                                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Al momento de ver la información de una correspondencia, 
                                            tenga en cuenta que al modifcarla y hacer clic en el botón Guardar, el sistema generará un nuevo registro más no una actualización de la información.
                                            Si necesita generar la proforma debe editar la información del formulario y luego hacer clic en el botón correspondiente.
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="tabla_comunicados_juntas"  style="width: 100%;  white-space: nowrap;">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th>N° de Radicado</th>
                                                        <th>Elaboró</th>
                                                        <th>Fecha de comunicado</th>
                                                        <th>Documento</th>
                                                        @if ($caso_notificado == "Si")
                                                            <th>Destinatarios</th>
                                                        @endif
                                                        <th>Estado general de la Notificación</th>
                                                        <th>Nota</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($array_comunicados_correspondencia as $key => $comunicados)
                                                        <tr>
                                                            <td data-id_comunicado="{{$comunicados->Id_Comunicado}}">{{$comunicados->N_radicado}}</td>
                                                            <td>{{$comunicados->Elaboro}}</td>
                                                            <td>{{$comunicados->F_comunicado}}</td>
                                                            <td><?php if($comunicados->Tipo_descarga == 'Manual'){echo $comunicados->Asunto;}else{echo $comunicados->Tipo_descarga;}?></td>
                                                            @if ($caso_notificado == "Si")
                                                                <td>
                                                                    <?php
                                                                        $destinatario = strtolower($comunicados->Destinatario);
                                                                        $copias = $comunicados->Agregar_copia;
                                                                        $correspondencia = $comunicados->Correspondencia;
                                                                        $comunicados->Estado_correspondencia = $comunicados->Estado_correspondencia ?? '1';
                                                                        $deshabilitarSelector = $comunicados->Estado_correspondencia == '1' ?  '1' : '0';
                                                                        $deshabilitaredicion = $comunicados->Estado_correspondencia == '1' || ($comunicados->Estado_Notificacion == 359 || $comunicados->Estado_Notificacion == 358) ?  '' : 'pointer-events: none; color: gray;';
                                                                        $deshabilitarRemplazar = $comunicados->Estado_correspondencia == '1' || ($comunicados->Estado_Notificacion == 359 || $comunicados->Estado_Notificacion == 358) ? '' : 'disabled';
                                                                
                                                                        $array_copias = array();
                                                                        $array_correspondencia = array();

                                                                        if ($copias) {
                                                                            $copias = explode(", ", $copias);
                                                                            $array_copias = array_map(function($item) {
                                                                                return strtolower(trim($item));
                                                                            }, $copias);
                                                                        }

                                                                        if($correspondencia){
                                                                            $correspondencia = explode(", ", $correspondencia);
                                                                            $array_correspondencia = array_map(function($item) {
                                                                                return strtolower(trim($item));
                                                                            }, $correspondencia);
                                                                        }
                                                                        
                                                                        if (!function_exists('subrayado')) {
                                                                            function subrayado($entidad, $destinatario, $array_copias, $array_correspondencia) {

                                                                                $array_copias = implode(',', $array_copias);
                                                                                $array_correspondencia = implode(',', $array_correspondencia);

                                                                                $negrita = (isset($array_correspondencia) && strpos($array_correspondencia, $entidad) !== false) ? 'font-weight:700;' : '';
                                                                                $underline = ($destinatario === strtolower($entidad) || (isset($array_copias) && strpos(strtolower($array_copias), strtolower($entidad)) !== false)) ? 'text-decoration-line: underline;' : '';

                                                                                return $negrita.$underline;
                                                                            }
                                                                        }
                                                                    ?>
                                                                    <a  href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="Afiliado"
                                                                    data-id_comunicado="{{$comunicados->Id_Comunicado}}" data-n_radicado="{{$comunicados->N_radicado}}" data-copias="<?php echo implode(',', $array_copias); ?>" 
                                                                    data-destinatario_principal="{{$comunicados->Destinatario}}" data-id_evento="{{$comunicados->ID_evento}}" data-id_asignacion="{{$comunicados->Id_Asignacion}}" 
                                                                    data-id_proceso="{{$comunicados->Id_proceso}}" data-anexos="{{$comunicados->Anexos}}" data-correspondencia="{{$comunicados->Correspondencia}}" 
                                                                    data-tipo_descarga="{{$comunicados->Tipo_descarga}}" data-nombre_afiliado="{{$comunicados->Nombre_afiliado}}" data-numero_identificacion="{{$comunicados->N_identificacion}}"
                                                                    data-estado_correspondencia="{{$comunicados->Estado_correspondencia}}" data-ids_destinatario="{{$comunicados->Id_Destinatarios}}" style="<?php echo subrayado('afiliado', $destinatario, $array_copias, $array_correspondencia); ?>"
                                                                    >Afiliado</a>
        
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="Empleador"
                                                                    data-id_comunicado="{{$comunicados->Id_Comunicado}}" data-n_radicado="{{$comunicados->N_radicado}}" data-copias="<?php echo implode(',', $array_copias); ?>" 
                                                                    data-destinatario_principal="{{$comunicados->Destinatario}}" data-id_evento="{{$comunicados->ID_evento}}" data-id_asignacion="{{$comunicados->Id_Asignacion}}" 
                                                                    data-id_proceso="{{$comunicados->Id_proceso}}" data-anexos="{{$comunicados->Anexos}}" data-correspondencia="{{$comunicados->Correspondencia}}" 
                                                                    data-tipo_descarga="{{$comunicados->Tipo_descarga}}" data-nombre_afiliado="{{$comunicados->Nombre_afiliado}}" data-numero_identificacion="{{$comunicados->N_identificacion}}"
                                                                    data-estado_correspondencia="{{$comunicados->Estado_correspondencia}}" data-ids_destinatario="{{$comunicados->Id_Destinatarios}}" style="<?php echo subrayado('empleador', $destinatario, $array_copias, $array_correspondencia); ?>"
                                                                    >Empleador</a>
        
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="eps"
                                                                    data-id_comunicado="{{$comunicados->Id_Comunicado}}" data-n_radicado="{{$comunicados->N_radicado}}" data-copias="<?php echo implode(',', $array_copias); ?>" 
                                                                    data-destinatario_principal="{{$comunicados->Destinatario}}" data-id_evento="{{$comunicados->ID_evento}}" data-id_asignacion="{{$comunicados->Id_Asignacion}}" 
                                                                    data-id_proceso="{{$comunicados->Id_proceso}}" data-anexos="{{$comunicados->Anexos}}" data-correspondencia="{{$comunicados->Correspondencia}}" 
                                                                    data-tipo_descarga="{{$comunicados->Tipo_descarga}}" data-nombre_afiliado="{{$comunicados->Nombre_afiliado}}" data-numero_identificacion="{{$comunicados->N_identificacion}}"
                                                                    data-estado_correspondencia="{{$comunicados->Estado_correspondencia}}" data-ids_destinatario="{{$comunicados->Id_Destinatarios}}" style="<?php echo subrayado('eps', $destinatario, $array_copias, $array_correspondencia); ?>"
                                                                    >EPS</a>
        
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="afp"
                                                                    data-id_comunicado="{{$comunicados->Id_Comunicado}}" data-n_radicado="{{$comunicados->N_radicado}}" data-copias="<?php echo implode(',', $array_copias); ?>" 
                                                                    data-destinatario_principal="{{$comunicados->Destinatario}}" data-id_evento="{{$comunicados->ID_evento}}" data-id_asignacion="{{$comunicados->Id_Asignacion}}" 
                                                                    data-id_proceso="{{$comunicados->Id_proceso}}" data-anexos="{{$comunicados->Anexos}}" data-correspondencia="{{$comunicados->Correspondencia}}" 
                                                                    data-tipo_descarga="{{$comunicados->Tipo_descarga}}" data-nombre_afiliado="{{$comunicados->Nombre_afiliado}}" data-numero_identificacion="{{$comunicados->N_identificacion}}"
                                                                    data-estado_correspondencia="{{$comunicados->Estado_correspondencia}}" data-ids_destinatario="{{$comunicados->Id_Destinatarios}}" style="<?php echo subrayado('afp', $destinatario, $array_copias, $array_correspondencia); ?>"
                                                                    >AFP</a>
        
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="arl"
                                                                    data-id_comunicado="{{$comunicados->Id_Comunicado}}" data-n_radicado="{{$comunicados->N_radicado}}" data-copias="<?php echo implode(',', $array_copias); ?>" 
                                                                    data-destinatario_principal="{{$comunicados->Destinatario}}" data-id_evento="{{$comunicados->ID_evento}}" data-id_asignacion="{{$comunicados->Id_Asignacion}}" 
                                                                    data-id_proceso="{{$comunicados->Id_proceso}}" data-anexos="{{$comunicados->Anexos}}" data-correspondencia="{{$comunicados->Correspondencia}}" 
                                                                    data-tipo_descarga="{{$comunicados->Tipo_descarga}}" data-nombre_afiliado="{{$comunicados->Nombre_afiliado}}" data-numero_identificacion="{{$comunicados->N_identificacion}}"
                                                                    data-estado_correspondencia="{{$comunicados->Estado_correspondencia}}" data-ids_destinatario="{{$comunicados->Id_Destinatarios}}" style="<?php echo subrayado('arl', $destinatario, $array_copias, $array_correspondencia); ?>"
                                                                    >ARL</a>
                                                                    
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="jrci"
                                                                    data-id_comunicado="{{$comunicados->Id_Comunicado}}" data-n_radicado="{{$comunicados->N_radicado}}" data-copias="<?php echo implode(',', $array_copias); ?>" 
                                                                    data-destinatario_principal="{{$comunicados->Destinatario}}" data-id_evento="{{$comunicados->ID_evento}}" data-id_asignacion="{{$comunicados->Id_Asignacion}}" 
                                                                    data-id_proceso="{{$comunicados->Id_proceso}}" data-anexos="{{$comunicados->Anexos}}" data-correspondencia="{{$comunicados->Correspondencia}}" 
                                                                    data-tipo_descarga="{{$comunicados->Tipo_descarga}}" data-nombre_afiliado="{{$comunicados->Nombre_afiliado}}" data-numero_identificacion="{{$comunicados->N_identificacion}}"
                                                                    data-estado_correspondencia="{{$comunicados->Estado_correspondencia}}" data-ids_destinatario="{{$comunicados->Id_Destinatarios}}" style="<?php echo subrayado('jrci', $destinatario, $array_copias, $array_correspondencia); ?>"
                                                                    >JRCI</a>
        
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="jnci"
                                                                    data-id_comunicado="{{$comunicados->Id_Comunicado}}" data-n_radicado="{{$comunicados->N_radicado}}" data-copias="<?php echo implode(',', $array_copias); ?>" 
                                                                    data-destinatario_principal="{{$comunicados->Destinatario}}" data-id_evento="{{$comunicados->ID_evento}}" data-id_asignacion="{{$comunicados->Id_Asignacion}}" 
                                                                    data-id_proceso="{{$comunicados->Id_proceso}}" data-anexos="{{$comunicados->Anexos}}" data-correspondencia="{{$comunicados->Correspondencia}}" 
                                                                    data-tipo_descarga="{{$comunicados->Tipo_descarga}}" data-nombre_afiliado="{{$comunicados->Nombre_afiliado}}" data-numero_identificacion="{{$comunicados->N_identificacion}}"
                                                                    data-estado_correspondencia="{{$comunicados->Estado_correspondencia}}" data-ids_destinatario="{{$comunicados->Id_Destinatarios}}" style="<?php echo subrayado('jnci', $destinatario, $array_copias, $array_correspondencia); ?>"
                                                                    >JNCI</a>
                                                                    
                                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modalCorrespondencia" id="CorrespondenciaNotificacion" data-tipo_correspondencia="afp_conocimiento"
                                                                    data-id_comunicado="{{$comunicados->Id_Comunicado}}" data-n_radicado="{{$comunicados->N_radicado}}" data-copias="<?php echo implode(',', $array_copias); ?>" 
                                                                    data-destinatario_principal="{{$comunicados->Destinatario}}" data-id_evento="{{$comunicados->ID_evento}}" data-id_asignacion="{{$comunicados->Id_Asignacion}}" 
                                                                    data-id_proceso="{{$comunicados->Id_proceso}}" data-anexos="{{$comunicados->Anexos}}" data-correspondencia="{{$comunicados->Correspondencia}}" 
                                                                    data-tipo_descarga="{{$comunicados->Tipo_descarga}}" data-nombre_afiliado="{{$comunicados->Nombre_afiliado}}" data-numero_identificacion="{{$comunicados->N_identificacion}}"
                                                                    data-estado_correspondencia="{{$comunicados->Estado_correspondencia}}" data-ids_destinatario="{{$comunicados->Id_Destinatarios}}" style="<?php echo subrayado('afp_conocimiento', $destinatario, $array_copias, $array_correspondencia); ?>"
                                                                    >AFP Conocimiento</a>
                                                                </td>
                                                           @endif
                                                            <td><select class="custom-select" id="status_notificacion_{{$comunicados->N_radicado}}" style="width:100%;" data-deshabilitar={{$deshabilitarSelector ?? '1'}} data-default={{$comunicados->Estado_Notificacion}}></select></td>
                                                            <td><textarea class="form-control nota-col" name="nota_comunicado_{{$comunicados->N_radicado}}" id="nota_comunicado_{{$comunicados->N_radicado}}" cols="70" rows="3" style="resize:none; width:200px;">{{$comunicados->Nota}}</textarea></td> {{-- campo Nota--}}
                                                            @if ($comunicados->Tipo_descarga == "Manual") 
                                                                <td style="display: flex; flex-direction:row; justify-content:space-around;">
                                                                    <form id="form_descargar_archivo_{{$comunicados->Id_Comunicado}}" data-archivo="{{$comunicados}}" method="POST">
                                                                        <button type="submit" id="btn_descargar_archivo_{{$comunicados->Id_Comunicado}}" style="border: none; background:transparent;">
                                                                            <i class="far fa-eye text-info"></i>
                                                                        </button>
                                                                    </form>
                                                                    @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                        <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                            <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" {{$deshabilitarRemplazar ?? ''}} style="border: none; background: transparent;">
                                                                                <i class="fas fa-sync-alt text-info"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                    @if($dato_rol !== '7')
                                                                        <a href="javascript:void(0);"  class="editar_comunicado_{{$comunicados->N_radicado}}" id="editar_comunicado" data-radicado="{{$comunicados->N_radicado}}" style="{{$deshabilitaredicion ?? ''}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    @endif
                                                                </td>
                                                            @else
                                                                <td style="display: flex; flex-direction:row; justify-content:space-around;">
                                                                    {{-- <label for="editar_correspondencia_{{$comunicados->Id_Comunicado}}"><i class="fa fa-pen text-info"></i></label>
                                                                    <input class="btn btn-icon-only text-info btn-sm" id="editar_correspondencia_{{$comunicados->Id_Comunicado}}" 
                                                                    data-tupla_comunicado="{{$comunicados->Id_Comunicado}}" 
                                                                    data-id_evento= "{{$array_datos_controversiaJuntas[0]->ID_evento}}"
                                                                    data-id_asignacion= "{{$array_datos_controversiaJuntas[0]->Id_Asignacion}}"
                                                                    type="button" style="font-weight: bold;"> --}}
                                                                    @foreach ($array_comite_interdisciplinario as $comite_inter)
                                                                        @if($comite_inter->N_radicado === $comunicados->N_radicado)
                                                                            @if(($comunicados->Correspondencia === '' || $comunicados->Correspondencia === null) && $dato_rol !== '7')
                                                                                <a href="javascript:void(0);" id="editar_correspondencia_{{$comunicados->Id_Comunicado}}"
                                                                                    data-id_comite_inter={{$comite_inter->Id_com_inter}}              
                                                                                    data-tupla_comunicado="{{$comunicados->Id_Comunicado}}" 
                                                                                    data-id_evento= "{{$array_datos_controversiaJuntas[0]->ID_evento}}"
                                                                                    data-id_asignacion= "{{$array_datos_controversiaJuntas[0]->Id_Asignacion}}"
                                                                                ><i class="fa fa-pen text-info"></i></a>
                                                                            @endif
                                                                            <a href="javascript:void(0);" id="generar_proforma_{{$comite_inter->Decision_dictamen ?? ''}}"
                                                                                data-archivo = "{{$comunicados}}"
                                                                                data-tupla_nro_radicado="{{$comunicados->N_radicado}}"
                                                                                data-id_comite_inter={{$comite_inter->Id_com_inter}}
                                                                                data-tupla_comunicado="{{$comunicados->Id_Comunicado}}" 
                                                                                data-id_evento= "{{$array_datos_controversiaJuntas[0]->ID_evento}}"
                                                                                data-id_asignacion= "{{$array_datos_controversiaJuntas[0]->Id_Asignacion}}"
                                                                            ><i class="far fa-eye text-info"></i>
                                                                            </a>
                                                                            @if ($comunicados['Existe'] && $dato_rol !== '7')
                                                                                <form id="form_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" data-archivo="{{json_encode($comunicados)}}" method="POST">
                                                                                    <button type="submit" id="btn_reemplazar_archivo_{{$comunicados['Id_Comunicado']}}" {{$deshabilitarRemplazar ?? ''}} style="border: none; background: transparent;">
                                                                                        <i class="fas fa-sync-alt text-info"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                    @if ($dato_rol !== '7')
                                                                        <a href="javascript:void(0);"  class="editar_comunicado_{{$comunicados->N_radicado}}" id="editar_comunicado" data-radicado="{{$comunicados->N_radicado}}" style="{{$deshabilitaredicion ?? ''}}"><i class="fa fa-sm fa-check text-success"></i></a>
                                                                    @endif
                                                                </td>
                                                            @endif
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
                <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                    <i class="fas fa-chevron-up"></i>
                </a>
            </div>
        </div>
    {{-- @endif --}}
    <!--Retonar al modulo Origen -->
   <form action="{{route('calificacionJuntas')}}" id="formularioEnvio" method="POST">            
        @csrf
        <input hidden="hidden" type="text" name="newIdEvento" id="newIdEvento" value="{{$array_datos_controversiaJuntas[0]->ID_evento}}">
        <input hidden="hidden" type="text" name="newIdAsignacion" id="newIdAsignacion" value="{{$array_datos_controversiaJuntas[0]->Id_Asignacion}}">
        <input hidden="hidden" type="text" name="Id_Servicio" id="Id_Servicio" value="{{$array_datos_controversiaJuntas[0]->Id_Servicio}}">
        <button type="submit" id="botonEnvioVista" style="display:none !important;"></button>
    </form>
    <?php $aperturaModal = 'Edicion'; ?>
    @include('//.administrador.modalcarguedocumentos')
    @include('//.administrador.modalProgressbar')
    @include('//.coordinador.modalReemplazarArchivos')
    @include('//.coordinador.modalCorrespondencia')
    @include('//.modals.alertaRadicado')

@stop
@section('js')
    <script type="text/javascript" src="/js/funciones_helpers.js?v=1.0.0"></script>
    {{-- Validación general para todos los campos de tipo fecha --}}
    <script>
        let today = new Date().toISOString().split("T")[0];

        // Seleccionar todos los inputs de tipo date
        const dateInputs = document.querySelectorAll('input[type="date"]');

        // Agregar evento de escucha a cada input de tipo date que haya
        dateInputs.forEach(input => {
            //Usamos el evento change para detectar los cambios de cada uno de los inputs de tipo fecha
            input.addEventListener('change', function() {
                console.log('This is value of input type date ', this.value);
                //Validamos que la fecha sea mayor a la fecha de 1900-01-01
                if(this.value < '1900-01-01'){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(this.value > today){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    return;
                }
                return $(`#${this.id}_alerta`).text('').addClass("d-none");
            });
        });
    </script>
    <script type="text/javascript">
        //Diagnosticos CIE10 que vienen desde la base de datos
        let arrayDatosDiagnostico = @json($array_datos_diagnostico_motcalifi_contro);
        
        document.getElementById('botonEnvioVista').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('formularioEnvio').submit();
        });

        var arrayComunicadosCorrespondencia = <?php echo json_encode($array_comunicados_correspondencia); ?>;
        
        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE DIAGNOSTCO CIE10 CONTROVERTIDO
        $(".centrar").css('text-align', 'center');
        var listado_diagnostico_cie10_controvertido = $('#listado_diagnostico_cie10_controvertido').DataTable({
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

        autoAdjustColumns(listado_diagnostico_cie10_controvertido);

        var contador_cie10 = 0;
        var array_ids_checkboxes_nuevos = [];
        $('#btn_agregar_cie10_controvertido_fila').click(function(){
            $('#guardar_datos_cie10').removeClass('d-none');

            contador_cie10 = contador_cie10 + 1;
            var nueva_fila_cie10 = [
                '<select id="lista_Cie10_fila_'+contador_cie10+'" class="custom-select lista_Cie10_fila_'+contador_cie10+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie10+'" name="nombre_cie10"/>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie10+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<select id="lista_lateralidadCie10_fila_'+contador_cie10+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie10+'" name="lista_lateralidadCie10"><option></option></select>',
                '<select id="lista_origenCie10_fila_'+contador_cie10+'" class="custom-select lista_origenCie10_fila_'+contador_cie10+'" name="lista_origenCie10"><option></option></select>',
                '<input type="checkbox" id="checkbox_dx_principal_Cie10_'+contador_cie10+'" class="checkbox_dx_principal_Cie10_'+contador_cie10+'" data-id_fila_checkbox_dx_principal_Cie10="'+contador_cie10+'" style="transform: scale(1.2);">',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_cie10_fila" class="text-info" data-fila="fila_'+contador_cie10+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_cie10
            ];

            var agregar_cie10_fila = listado_diagnostico_cie10_controvertido.row.add(nueva_fila_cie10).draw().node();
            $(agregar_cie10_fila).addClass('fila_'+contador_cie10);
            $(agregar_cie10_fila).attr("id", 'fila_'+contador_cie10);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
            funciones_elementos_fila_diagnosticos(contador_cie10);
            
            array_ids_checkboxes_nuevos.push("checkbox_dx_principal_Cie10_"+contador_cie10);
            
        });
            
        $(document).on('click', '#btn_remover_cie10_fila', function(){
            var nombre_cie10_fila = $(this).data("fila");
            listado_diagnostico_cie10_controvertido.row("."+nombre_cie10_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_diagnosticos_moticalifi']", function(){
            var nombre_cie10_fila = $(this).data("clase_fila");
            listado_diagnostico_cie10_controvertido.row("."+nombre_cie10_fila).remove().draw();
        });

        setInterval(() => {
            var array_checkboxes_visuales = $('[id^="checkbox_dx_principal_visual_Cie10_"]');
            var confirmar_check_visual;
            var confirmar_check_visual1;
            if($("input[id^='checkbox_dx_principal_visual_Cie10_']").is(":checked")){
                
                array_checkboxes_visuales.each(function() {
                    var id_check_visual = $(this).attr("id");
                    if ($("#"+id_check_visual).is(":checked")) {
                        $("input[id^='checkbox_dx_principal_visual_Cie10_']").not('#' + id_check_visual).prop('disabled', true);
                    }
                    confirmar_check_visual = "Si";
                });
                
                $.each(array_ids_checkboxes_nuevos, function(index, valor) {
                    $("#"+valor).prop("disabled", true);
                });
                
            }else{
                var confirmar_nuevo_check;
                confirmar_check_visual1 = "No";

                if (confirmar_check_visual == undefined) {
                    var array_checkboxes_visuales = $('[id^="checkbox_dx_principal_visual_Cie10_"]');
                    array_checkboxes_visuales.each(function() {
                        var id_check_visual = $(this).attr("id");
                        $("input[id^='checkbox_dx_principal_visual_Cie10_']").not('#' + id_check_visual).prop('disabled', false);
                    });
                }
                
                $.each(array_ids_checkboxes_nuevos, function(index, value) {
                   if ($("#"+value).is(':checked')) {

                    // $("input[id^='checkbox_dx_principal_visual_Cie10_']").prop("disabled", true);

                    array_checkboxes_visuales.each(function() {
                        var id_check_visual = $(this).attr("id");
                        
                        $("#"+id_check_visual).prop('disabled', true);
                    });

                    $.each(array_ids_checkboxes_nuevos, function(index, value2) {
                        if (value != value2) {
                            $("#"+value2).prop("disabled", true);
                        }
                        confirmar_nuevo_check = "Si";
                    });

                   }else{
                    if (confirmar_nuevo_check == undefined) {
                        // $("input[id^='checkbox_dx_principal_visual_Cie10_']").prop("disabled", false);

                        array_checkboxes_visuales.each(function() {
                            var id_check_visual = $(this).attr("id");
                            $("#"+id_check_visual).prop('disabled', false);
                        });

                        $.each(array_ids_checkboxes_nuevos, function(index, value3) {
                            $("#"+value3).prop("disabled", false);
                        });
                    }
                   }
                });
                    
            }

        }, 500);

        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE DIAGNOSTCO CIE10 JRCI EMITIDO
        $(".centrar").css('text-align', 'center');
        var listado_diagnostico_cie10_jrci_emitido = $('#listado_diagnostico_cie10_jrci_emitido').DataTable({
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

        autoAdjustColumns(listado_diagnostico_cie10_jrci_emitido);

        var contador_cie102 = 0;
        var array_ids_checkboxes_nuevos2 = [];
        $('#btn_agregar_cie10_jrci_emitido_fila').click(function(){
            //$('#guardar_datos_cie10').removeClass('d-none');

            contador_cie102 = contador_cie102 + 1;
            var nueva_fila_cie102 = [
                '<select id="lista_Cie10_fila_'+contador_cie102+'" class="custom-select lista_Cie10_fila_'+contador_cie102+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie102+'" name="nombre_cie10"/>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie102+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<select id="lista_lateralidadCie10_fila_'+contador_cie102+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie102+'" name="lista_lateralidadCie10"><option></option></select>',
                '<select id="lista_origenCie10_fila_'+contador_cie102+'" class="custom-select lista_origenCie10_fila_'+contador_cie102+'" name="lista_origenCie10"><option></option></select>',
                '<input type="checkbox" id="checkbox_dx_principal_Cie10_'+contador_cie102+'" class="checkbox_dx_principal_Cie10_'+contador_cie102+'" data-id_fila_checkbox_dx_principal_Cie10="'+contador_cie102+'" style="transform: scale(1.2);">',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_cie102_fila" class="text-info" data-fila="fila_'+contador_cie102+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila2_'+contador_cie102
            ];

            var agregar_cie102_fila = listado_diagnostico_cie10_jrci_emitido.row.add(nueva_fila_cie102).draw().node();
            $(agregar_cie102_fila).addClass('fila_'+contador_cie102);
            $(agregar_cie102_fila).attr("id", 'fila_'+contador_cie102);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
            funciones_elementos_fila_diagnosticos2(contador_cie102);
            
            array_ids_checkboxes_nuevos2.push("checkbox_dx_principal_Cie10_"+contador_cie102);
            
        });
            
        $(document).on('click', '#btn_remover_cie102_fila', function(){
            var nombre_cie102_fila = $(this).data("fila");
            listado_diagnostico_cie10_jrci_emitido.row("."+nombre_cie102_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_diagnosticos_jrci_emitido']", function(){
            var nombre_cie102_fila = $(this).data("clase_fila");
            listado_diagnostico_cie10_jrci_emitido.row("."+nombre_cie102_fila).remove().draw();
        });

        setInterval(() => {
            var array_checkboxes_visuales2 = $('[id^="checkbox_dx_principal_visual_emitido_Cie10_"]');
            var confirmar_check_visual2;
            var confirmar_check_visual12;
            if($("input[id^='checkbox_dx_principal_visual_emitido_Cie10_']").is(":checked")){
                
                array_checkboxes_visuales2.each(function() {
                    var id_check_visual2 = $(this).attr("id");
                    if ($("#"+id_check_visual2).is(":checked")) {
                        $("input[id^='checkbox_dx_principal_visual_emitido_Cie10_']").not('#' + id_check_visual2).prop('disabled', true);
                    }
                    confirmar_check_visual2 = "Si";
                });
                
                $.each(array_ids_checkboxes_nuevos2, function(index, valor) {
                    $("#"+valor).prop("disabled", true);
                });
                
            }else{
                var confirmar_nuevo_check;
                confirmar_check_visual12 = "No";

                if (confirmar_check_visual2 == undefined) {
                    var array_checkboxes_visuales2 = $('[id^="checkbox_dx_principal_visual_emitido_Cie10_"]');
                    array_checkboxes_visuales2.each(function() {
                        var id_check_visual2 = $(this).attr("id");
                        $("input[id^='checkbox_dx_principal_visual_emitido_Cie10_']").not('#' + id_check_visual2).prop('disabled', false);
                    });
                }
                
                $.each(array_ids_checkboxes_nuevos2, function(index, value) {
                   if ($("#"+value).is(':checked')) {

                    // $("input[id^='checkbox_dx_principal_visual_emitido_Cie10_']").prop("disabled", true);

                    array_checkboxes_visuales2.each(function() {
                        var id_check_visual2 = $(this).attr("id");
                        
                        $("#"+id_check_visual2).prop('disabled', true);
                    });

                    $.each(array_ids_checkboxes_nuevos2, function(index, value2) {
                        if (value != value2) {
                            $("#"+value2).prop("disabled", true);
                        }
                        confirmar_nuevo_check = "Si";
                    });

                   }else{
                    if (confirmar_nuevo_check == undefined) {
                        // $("input[id^='checkbox_dx_principal_visual_emitido_Cie10_']").prop("disabled", false);

                        array_checkboxes_visuales2.each(function() {
                            var id_check_visual2 = $(this).attr("id");
                            $("#"+id_check_visual2).prop('disabled', false);
                        });

                        $.each(array_ids_checkboxes_nuevos2, function(index, value3) {
                            $("#"+value3).prop("disabled", false);
                        });
                    }
                   }
                });
                    
            }

        }, 500);

        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE REPOSICION DEL DICTAMEN JRCI
        $(".centrar").css('text-align', 'center');
        var listado_diagnostico_cie10_jrci_reposicion = $('#listado_diagnostico_cie10_jrci_reposicion').DataTable({
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

        autoAdjustColumns(listado_diagnostico_cie10_jrci_reposicion);

        var contador_cie103 = 0;
        var array_ids_checkboxes_nuevos3 = [];
        $('#btn_agregar_cie10_jrci_reposicion_fila').click(function(){
            //$('#guardar_datos_cie10').removeClass('d-none');

            contador_cie103 = contador_cie103 + 1;
            var nueva_fila_cie103 = [
                '<select id="lista_Cie10_fila_'+contador_cie103+'" class="custom-select lista_Cie10_fila_'+contador_cie103+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie103+'" name="nombre_cie10"/>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie103+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<select id="lista_lateralidadCie10_fila_'+contador_cie103+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie103+'" name="lista_lateralidadCie10"><option></option></select>',
                '<select id="lista_origenCie10_fila_'+contador_cie103+'" class="custom-select lista_origenCie10_fila_'+contador_cie103+'" name="lista_origenCie10"><option></option></select>',
                '<input type="checkbox" id="checkbox_dx_principal_Cie10_'+contador_cie103+'" class="checkbox_dx_principal_Cie10_'+contador_cie103+'" data-id_fila_checkbox_dx_principal_Cie10="'+contador_cie103+'" style="transform: scale(1.2);">',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_cie103_fila" class="text-info" data-fila="fila_'+contador_cie103+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila2_'+contador_cie103
            ];

            var agregar_cie103_fila = listado_diagnostico_cie10_jrci_reposicion.row.add(nueva_fila_cie103).draw().node();
            $(agregar_cie103_fila).addClass('fila_'+contador_cie103);
            $(agregar_cie103_fila).attr("id", 'fila_'+contador_cie103);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo controversia_juntas.js)
            funciones_elementos_fila_diagnosticos3(contador_cie103);
            
            array_ids_checkboxes_nuevos3.push("checkbox_dx_principal_Cie10_"+contador_cie103);
            
        });
            
        $(document).on('click', '#btn_remover_cie103_fila', function(){
            var nombre_cie103_fila = $(this).data("fila");
            listado_diagnostico_cie10_jrci_reposicion.row("."+nombre_cie103_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_diagnosticos_jrci_reposicion']", function(){
            var nombre_cie103_fila = $(this).data("clase_fila");
            listado_diagnostico_cie10_jrci_reposicion.row("."+nombre_cie103_fila).remove().draw();
        });

        setInterval(() => {
            var array_checkboxes_visuales3 = $('[id^="checkbox_dx_principal_visual_reposicion_Cie10_"]');
            var confirmar_check_visual3;
            var confirmar_check_visual13;
            if($("input[id^='checkbox_dx_principal_visual_reposicion_Cie10_']").is(":checked")){
                
                array_checkboxes_visuales3.each(function() {
                    var id_check_visual3 = $(this).attr("id");
                    if ($("#"+id_check_visual3).is(":checked")) {
                        $("input[id^='checkbox_dx_principal_visual_reposicion_Cie10_']").not('#' + id_check_visual3).prop('disabled', true);
                    }
                    confirmar_check_visual3 = "Si";
                });
                
                $.each(array_ids_checkboxes_nuevos3, function(index, valor) {
                    $("#"+valor).prop("disabled", true);
                });
                
            }else{
                var confirmar_nuevo_check3;
                confirmar_check_visual13 = "No";

                if (confirmar_check_visual3 == undefined) {
                    var array_checkboxes_visuales3 = $('[id^="checkbox_dx_principal_visual_reposicion_Cie10_"]');
                    array_checkboxes_visuales3.each(function() {
                        var id_check_visual3 = $(this).attr("id");
                        $("input[id^='checkbox_dx_principal_visual_reposicion_Cie10_']").not('#' + id_check_visual3).prop('disabled', false);
                    });
                }
                
                $.each(array_ids_checkboxes_nuevos3, function(index, value) {
                   if ($("#"+value).is(':checked')) {

                    // $("input[id^='checkbox_dx_principal_visual_reposicion_Cie10_']").prop("disabled", true);

                    array_checkboxes_visuales3.each(function() {
                        var id_check_visual3 = $(this).attr("id");
                        
                        $("#"+id_check_visual3).prop('disabled', true);
                    });

                    $.each(array_ids_checkboxes_nuevos3, function(index, value2) {
                        if (value != value2) {
                            $("#"+value2).prop("disabled", true);
                        }
                        confirmar_nuevo_check3 = "Si";
                    });

                   }else{
                    if (confirmar_nuevo_check3 == undefined) {
                        // $("input[id^='checkbox_dx_principal_visual_emitido_Cie10_']").prop("disabled", false);

                        array_checkboxes_visuales3.each(function() {
                            var id_check_visual3 = $(this).attr("id");
                            $("#"+id_check_visual3).prop('disabled', false);
                        });

                        $.each(array_ids_checkboxes_nuevos3, function(index, value3) {
                            $("#"+value3).prop("disabled", false);
                        });
                    }
                   }
                });
                    
            }

        }, 500);

        //SCRIPT PARA INSERTAR O ELIMINAR FILAS DINAMICAS DEL DATATABLES DE DIAGNOSTCO CIE10 JNCI EMITIDO
        $(".centrar").css('text-align', 'center');
        var listado_diagnostico_cie10_jnci_emitido = $('#listado_diagnostico_cie10_jnci_emitido').DataTable({
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

        autoAdjustColumns(listado_diagnostico_cie10_jnci_emitido);
        var contador_cie104 = 0;
        var array_ids_checkboxes_nuevos4 = [];
        $('#btn_agregar_cie10_jnci_emitido_fila').click(function(){
            contador_cie104 = contador_cie104 + 1;
            var nueva_fila_cie104 = [
                '<select id="lista_Cie10_fila_'+contador_cie104+'" class="custom-select lista_Cie10_fila_'+contador_cie104+'" name="lista_Cie10"><option></option></select>',
                '<input type="text" class="form-control" id="nombre_cie10_fila_'+contador_cie104+'" name="nombre_cie10"/>',
                '<textarea id="descripcion_cie10_fila_'+contador_cie104+'" class="form-control" name="descripcion_cie10" cols="90" rows="4"></textarea>',
                '<select id="lista_lateralidadCie10_fila_'+contador_cie104+'" class="custom-select lista_lateralidadCie10_fila_'+contador_cie104+'" name="lista_lateralidadCie10"><option></option></select>',
                '<select id="lista_origenCie10_fila_'+contador_cie104+'" class="custom-select lista_origenCie10_fila_'+contador_cie104+'" name="lista_origenCie10"><option></option></select>',
                '<input type="checkbox" id="checkbox_dx_principal_Cie10_'+contador_cie104+'" class="checkbox_dx_principal_Cie10_'+contador_cie104+'" data-id_fila_checkbox_dx_principal_Cie10="'+contador_cie104+'" style="transform: scale(1.2);">',
                '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_cie104_fila" class="text-info" data-fila="fila_'+contador_cie104+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila2_'+contador_cie104
            ];

            var agregar_cie104_fila = listado_diagnostico_cie10_jnci_emitido.row.add(nueva_fila_cie104).draw().node();
            $(agregar_cie104_fila).addClass('fila_'+contador_cie104);
            $(agregar_cie104_fila).attr("id", 'fila_'+contador_cie104);

            // Esta función realiza los controles de cada elemento por fila (está dentro del archivo calificacionpcl.js)
            funciones_elementos_fila_diagnosticos4(contador_cie104);
            
            array_ids_checkboxes_nuevos4.push("checkbox_dx_principal_Cie10_"+contador_cie104);
            
        });
            
        $(document).on('click', '#btn_remover_cie104_fila', function(){
            var nombre_cie104_fila = $(this).data("fila");
            listado_diagnostico_cie10_jnci_emitido.row("."+nombre_cie104_fila).remove().draw();
        });

        $(document).on('click', "a[id^='btn_remover_diagnosticos_jnci_emitido']", function(){
            var nombre_cie104_fila = $(this).data("clase_fila");
            listado_diagnostico_cie10_jnci_emitido.row("."+nombre_cie104_fila).remove().draw();
        });

        setInterval(() => {
            var array_checkboxes_visuales4 = $('[id^="checkbox_dx_principal_visual_emitido_jnci_Cie10_"]');
            var confirmar_check_visual4;
            var confirmar_check_visual14;
            if($("input[id^='checkbox_dx_principal_visual_emitido_jnci_Cie10_']").is(":checked")){
                
                array_checkboxes_visuales4.each(function() {
                    var id_check_visual4 = $(this).attr("id");
                    if ($("#"+id_check_visual4).is(":checked")) {
                        $("input[id^='checkbox_dx_principal_visual_emitido_jnci_Cie10_']").not('#' + id_check_visual4).prop('disabled', true);
                    }
                    confirmar_check_visual4 = "Si";
                });
                
                $.each(array_ids_checkboxes_nuevos4, function(index, valor) {
                    $("#"+valor).prop("disabled", true);
                });
                
            }else{
                var confirmar_nuevo_check4;
                confirmar_check_visual14 = "No";

                if (confirmar_check_visual4 == undefined) {
                    var array_checkboxes_visuales4 = $('[id^="checkbox_dx_principal_visual_emitido_jnci_Cie10_"]');
                    array_checkboxes_visuales4.each(function() {
                        var id_check_visual4 = $(this).attr("id");
                        $("input[id^='checkbox_dx_principal_visual_emitido_jnci_Cie10_']").not('#' + id_check_visual4).prop('disabled', false);
                    });
                }
                
                $.each(array_ids_checkboxes_nuevos4, function(index, value) {
                   if ($("#"+value).is(':checked')) {

                    // $("input[id^='checkbox_dx_principal_visual_emitido_jnci_Cie10_']").prop("disabled", true);

                    array_checkboxes_visuales4.each(function() {
                        var id_check_visual4 = $(this).attr("id");
                        
                        $("#"+id_check_visual4).prop('disabled', true);
                    });

                    $.each(array_ids_checkboxes_nuevos4, function(index, value2) {
                        if (value != value2) {
                            $("#"+value2).prop("disabled", true);
                        }
                        confirmar_nuevo_check3 = "Si";
                    });

                   }else{
                    if (confirmar_nuevo_check4 == undefined) {
                        // $("input[id^='checkbox_dx_principal_visual_emitido_jnci_Cie10_']").prop("disabled", false);

                        array_checkboxes_visuales4.each(function() {
                            var id_check_visual4 = $(this).attr("id");
                            $("#"+id_check_visual4).prop('disabled', false);
                        });

                        $.each(array_ids_checkboxes_nuevos4, function(index, value3) {
                            $("#"+value3).prop("disabled", false);
                        });
                    }
                   }
                });
                    
            }

        }, 500);

    </script>
    <script type="text/javascript" src="/js/controversia_juntas.js"></script>
    <script src="/plugins/summernote/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
@stop