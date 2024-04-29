
@extends('adminlte::page')
@section('title', 'Buscar Evento')
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
        <div class="col-12">
            <div>
                <h4>Convenciones:</h4>
                <p>
                   <span style="color:red;">(*)</span> Campo Obligatorio&nbsp;|
                   <i class="fa fa-puzzle-piece text-info"></i> Agregar Nuevo Servicio&nbsp;|
                   <i class="far fa-clone text-info"></i> Agregar Nuevo Proceso&nbsp;
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Consultar Evento</h4>
            <input type="hidden" id="id_rol" value="<?php echo session('id_cambio_rol');?>">
        </div>
        <!-- Busqueda Filtros -->
        <form id="form_consultar_evento" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="nro_identificacion" class="col-form-label">N° de identificación</label>
                                    @if (session()->get('num_ident'))
                                        <input type="number" class="nro_identificacion form-control" name="consultar_nro_identificacion" id="consultar_nro_identificacion" value="{{session()->get('num_ident')}}">
                                    @else
                                        <input type="number" class="nro_identificacion form-control" name="consultar_nro_identificacion" id="consultar_nro_identificacion">
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="id_evento" class="col-form-label">ID evento</label>
                                    @if (session()->get('num_id_evento'))
                                        <input type="text" class="id_evento form-control" name="consultar_id_evento" id="consultar_id_evento" value="{{session()->get('num_id_evento')}}">
                                    @else
                                        <input type="text" class="id_evento form-control" name="consultar_id_evento" id="consultar_id_evento">
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <p>&nbsp;</p>
                                    <input type="submit" id="btn_buscar_evento" class="btn btn-info" value="Buscar">
                                </div>
                            </div>
                        </div>
                    </diV>
                    <div class="col-12">
                        <div class="resultado_validacion alert mt-1 d-none" id="llenar_mensaje_validacion" role="alert">
                            <strong ></strong>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Información del afiliado-->
        <div class="row contenedor_info_afiliado d-none">
            <div class="col-12">
                <div class="card-info">
                    <div class="card-header text-center" style="border: 1.5px solid black;">
                        <h5>Información del afiliado</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">Nombre de afiliado:</label>
                            <span id="span_nombre_afiliado"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">N° de identificación:</label>
                            <span id="span_nro_identificacion"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">Tipo de afiliado:</label>
                            <span id="span_tipo_afiliado"></span>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
        <!-- información del evento-->
        <div class="row contenedor_info_evento d-none">
            <div class="col-12">
                &nbsp; <label for="nro_registros" class="col-form-label">Se encontraron <span id="num_registros"></span> registros</label>
                <div class="card-info" id="contenedorTable">
                    <div class="card-header text-center" style="border: 1.5px solid black;">
                        <h5>Resultados de consulta</h5>
                        <input type="hidden" id="action_evento_consultar" value="{{ route('gestionInicialEdicion') }}">
                        <input type="hidden" id="action_modulo_principal_pcl" value="{{ route('calificacionPCL') }}">
                        <input type="hidden" id="action_modulo_principal_origen" value="{{ route('calificacionOrigen') }}">
                        <input type="hidden" id="action_modulo_principal_noti" value="{{ route('calificacionNotifi') }}">
                        <input type="hidden" id="action_modulo_principal_juntas" value="{{ route('calificacionJuntas') }}">
                    </div>
                </div>                
                <div class="card-body">
                    <div class="table table-responsive">
                        <table id="Consulta_Eventos" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="bg-info">
                                    <th>ID evento</th>
                                    <th>Cliente</th>
                                    <th>Empresa</th>
                                    <th>Tipo de evento</th>
                                    <th>Fecha radicación</th>
                                    <th>Fecha registro</th>
                                    <th>Proceso</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Resultado</th>
                                    <th>Última acción</th>
                                    <th>Fecha de acción</th>
                                    <th>Fecha de dictamen</th>
                                    <th>Fecha de Notificación</th>
                                    <th>Profesional actual</th>
                                    <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody id="body_listado_eventos"></tbody>
                        </table>                        
                    </div>
                </div>

                <div class="card-footer">
                    <div class="grupo_botones" style="float: left;">
                        <input type="button" id="btn_expor_datos" class="btn btn-info" value="Exportar datos"> 
                        <input type="submit" id="btn_nueva_consulta" class="btn btn-info" value="Nueva Consulta">
                    </div>
                </div>
            </div>

            {{-- PARA PROPOSITOS DE PONER EL MODAL DE NUEVO SERVICIO --}}
            <input type="hidden" id="fecha_de_hoy" value="{{date("Y-m-d")}}">
            <div class="renderizar_nuevo_servicio"></div>
            <div class="renderizar_nuevo_proceso"></div>

            {{-- MODAL PARA AGREGAR DOCUMENTOS INFORMACION PERICIAL --}}
            <?php $aperturaModal = 'Edicion_Busqueda'; ?>
            @include('administrador.modalcarguedocumentos')
        
        </div>
        
        
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
    
    <script src="/js/consultar_eventos.js"></script>
@stop