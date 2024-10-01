@extends('adminlte::page')
@section('title', 'Calificación Notificaciones')
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
                <?php if (isset($_POST['badera_modulo_principal_noti']) &&  $_POST['badera_modulo_principal_noti'] == 'desdebus_mod_noti' ):?>
                    <a href="{{route("busquedaEvento")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <?php else: ?>
                    <a href="{{route("bandejaNotifi")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
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
            <h4>Módulo Notificaciones - Evento: {{$array_datos_calificacionNoti[0]->ID_evento}}</h4>
            {{-- <input type="hidden" id="action_actualizar_comunicado" value="{{ route('descargarPdf') }}"> --}}
        </div>
        <form id="form_calificacionNotifi" method="POST">
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
                                                <input type="text" class="form-control" name="cliente" id="cliente" value="{{$array_datos_calificacionNoti[0]->Nombre_Cliente}}" disabled>
                                                <input hidden="hidden" type="text" class="form-control" name="newId_evento" id="newId_evento" value="{{$array_datos_calificacionNoti[0]->ID_evento}}">
                                                <input hidden="hidden" type="text" class="form-control" name="newId_asignacion" id="newId_asignacion" value="{{$array_datos_calificacionNoti[0]->Id_Asignacion}}">
                                                <input hidden="hidden" type="text" class="form-control" name="Id_proceso" id="Id_proceso" value="{{$array_datos_calificacionNoti[0]->Id_proceso}}">
                                                {{-- @if (count($dato_validacion_no_aporta_docs) > 0)
                                                <input hidden="hidden" type="text" class="form-control" data-id_tupla_no_aporta="{{$dato_validacion_no_aporta_docs[0]->Id_Documento_Solicitado}}" id="validacion_aporta_doc" value="{{$dato_validacion_no_aporta_docs[0]->Aporta_documento}}">
                                                @endif
                                                <input type="hidden" class="form-control" id="conteo_listado_documentos_solicitados" value="{{count($listado_documentos_solicitados)}}"> --}}
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="nombre_afiliado">Nombre de afiliado</label>
                                                <input type="text" class="form-control" name="nombre_afiliado" id="nombre_afiliado" value="{{$array_datos_calificacionNoti[0]->Nombre_afiliado}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="identificacion">N° Identificación</label>
                                                <input type="text" class="form-control" name="identificacion" id="identificacion" value="{{$array_datos_calificacionNoti[0]->Nro_identificacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="empresa">Empresa actual</label>
                                                <input type="text" class="form-control" name="empresa" id="empresa" value="{{$array_datos_calificacionNoti[0]->Empresa}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_evento">Tipo de evento</label>
                                                <input type="text" class="form-control" name="tipo_evento" id="tipo_evento" value="{{$array_datos_calificacionNoti[0]->Nombre_evento}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="id_evento">ID evento</label>
                                                <input type="text" class="form-control" name="id_evento" id="id_evento" value="{{$array_datos_calificacionNoti[0]->ID_evento}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_evento">Tipo de afiliado</label>
                                                <input type="text" class="form-control" name="tipo_afiliado" id="tipo_afiliado" value="{{$array_datos_calificacionNoti[0]->Tipo_afiliado}}" disabled>
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
                                                <input type="text" class="form-control" name="proceso_actual" id="proceso_actual" value="{{$array_datos_calificacionNoti[0]->Nombre_proceso_actual}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                                <div class="form-group">
                                                    <label for="servicio">Servicio</label><br>
                                                    <a onclick="document.getElementById('botonFormulario2').click();" style="cursor:pointer;" id="servicio_Noti"><i class="fa fa-puzzle-piece text-info"></i> <strong class="text-dark">{{$array_datos_calificacionNoti[0]->Nombre_servicio}}</strong></a>
                                                    <input type="hidden" class="form-control" name="servicio" id="servicio" value="{{$array_datos_calificacionNoti[0]->Nombre_servicio}}">
                                                </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="proceso_envia">Proceso que envía</label>
                                                <input type="text" class="form-control" name="proceso_envia" id="proceso_envia" value="{{$array_datos_calificacionNoti[0]->Nombre_proceso_anterior}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_radicacion">Fecha de radicación</label>
                                                <input type="date" class="form-control" name="fecha_radicacion" id="fecha_radicacion" value="{{$array_datos_calificacionNoti[0]->F_radicacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_asignacion">Fecha asignación al proceso</label>
                                                <input type="date" class="form-control" name="fecha_asignacion" id="fecha_asignacion" value="{{$array_datos_calificacionNoti[0]->F_registro_asignacion}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="estado">Estado</label>
                                                <input type="text" class="form-control" name="estado" id="estado" value="{{$array_datos_calificacionNoti[0]->Nombre_estado}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="dias_trascurrido">Dias transcurridos desde el evento</label>
                                                <input type="text" class="form-control" name="dias_trascurrido" id="dias_trascurrido" value="{{$array_datos_calificacionNoti[0]->Dias_transcurridos_desde_el_evento}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="asignado_por">Asignado por</label>
                                                <input type="text" class="form-control" name="asignado_por" id="asignado_por" value="{{$array_datos_calificacionNoti[0]->Asignado_por}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="fecha_asignacion_notificacion">Fecha de asignación para notificacion</label>
                                                <input type="text" class="form-control" name="fecha_asignacion_notificacion" id="fecha_asignacion_notificacion" style="color: red;" value="NO ESTA DEFINIDO" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="profesional_notificacion">Profesional Notificacion</label>
                                                <input type="text" class="form-control" name="profesional_notificacion" id="profesional_notificacion" value="{{$array_datos_calificacionNoti[0]->Nombre_profesional}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tipo_profesional_notificacion">Tipo Profesional Notificaciones</label>
                                                <input type="text" class="form-control" name="tipo_profesional_notificacion" id="tipo_profesional_notificacion" value="{{$array_datos_calificacionNoti[0]->Tipo_Profesional_calificador}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="tiempo_gestion">Tiempo de gestión</label>
                                                <input type="text" class="form-control" name="tiempo_gestion" id="tiempo_gestion" value="{{$array_datos_calificacionNoti[0]->Tiempo_de_gestion}}" disabled>
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
                                                <a href="#" id="clicGuardado" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modalHistoricoNotifi"><i class="fas fa-book-open text-info"></i> <strong>Histórico de Notificaciones</strong></a>
                                            </div>
                                        </div>
                                    </div>
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
                                                @if (!empty($array_datos_calificacionNoti[0]->F_accion_realizar))
                                                    <input type="date" class="form-control" name="fecha_accion" id="fecha_accion" value="{{$array_datos_calificacionNoti[0]->F_accion_realizar}}" disabled>
                                                    <input hidden="hidden" type="date" class="form-control" name="f_accion" id="f_accion" value="{{$array_datos_calificacionNoti[0]->F_accion_realizar}}">
                                                @else
                                                    <input type="date" class="form-control" name="fecha_accion" id="fecha_accion" value="{{now()->format('Y-m-d')}}" disabled>
                                                    <input hidden="hidden" type="date" class="form-control" name="f_accion" id="f_accion" value="{{now()->format('Y-m-d')}}">
                                                @endif
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
                                                <input type="date" class="form-control" name="fecha_alerta" id="fecha_alerta" min="{{now()->format('Y-m-d')}}" value="{{$array_datos_calificacionNoti[0]->F_alerta}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="enviar">Enviar a</label>
                                                <select class="custom-select" name="enviar" id="enviar" style="color: red;" disabled>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="descripcion_accion">Descripción acción</label>
                                                <textarea class="form-control" name="descripcion_accion" id="descripcion_accion" cols="30" rows="5" style="resize: none;">{{$array_datos_calificacionNoti[0]->Descripcion_accion}}</textarea>                                                
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
                <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                    <i class="fas fa-chevron-up"></i>
                </a> 
            </div>
            <div class="card-footer">
                <div class="grupo_botones">
                    <input type="reset" id="Borrar" class="btn btn-info" value="Restablecer">
                    @if (empty($array_datos_calificacionNoti[0]->Accion_realizar))
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
        {{-- <form action="{{route($SubModulo)}}" id="formulario2" method="POST">            
            @csrf
            <input hidden="hidden" type="text" name="Id_evento_notifi" id="Id_evento_notifi" value="{{$array_datos_calificacionNoti[0]->ID_evento}}">
            <input hidden="hidden" type="text" name="Id_asignacion_notifi" id="Id_asignacion_notifi" value="{{$array_datos_calificacionNoti[0]->Id_Asignacion}}">
            <input hidden="hidden" type="text" name="Id_proceso_notifi" id="Id_proceso_notifi" value="{{$array_datos_calificacionNoti[0]->Id_proceso}}">
            <button type="submit" id="botonFormulario2" style="display: none; !important"></button>
        </form> --}}
    </div>
    {{-- Modal Historico de Notificaciones --}}
    <div class="row">
        <div class="contenedor_historico_notificaciones" style="float: left;">
            <x-adminlte-modal id="modalHistoricoNotifi" class="modalscroll" title="Histórico de Notificaciones" theme="info" icon="fas fa-book-open" size='xl' disable-animations>
                <div class="row">
                    <div class="col-12">
                        <div class="card-info" style="border: 1.5px solid black; border-radius: 2px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="table-responsive">
                                                <table id="listado_historial_notifi" class="table table-striped table-bordered" width="100%">
                                                    <thead>
                                                        <tr class="bg-info" style="text-align:center;">
                                                            <th>Servicio</th>
                                                            <th>N° de orden</th>
                                                            <th>No. Radicado</th>
                                                            <th>Asunto</th>
                                                            <th>Empleador</th>
                                                            <th>EPS</th>
                                                            <th>AFP</th>
                                                            <th>JRCI</th>
                                                            <th>JNCI</th>
                                                            <th>Fecha de Envío</th>
                                                            <th>Estado general de la Notificación</th>
                                                            <th>Profesional de Notificación</th>
                                                            <th>Documentos adjuntos</th>
                                                            <th>Histórico </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="datos_visuales">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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
    <?php $aperturaModal = 'Edicion'; ?>
    @include('//.administrador.modalcarguedocumentos')
    @include('//.administrador.modalProgressbar')
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

        /* document.getElementById('botonFormulario2').addEventListener('click', function(event) {
            event.preventDefault();
            // Realizar las acciones que quieres al hacer clic en el botón
            document.getElementById('formulario2').submit();
        }); */
        
         //Elimina sessionStorage
         sessionStorage.removeItem("scrollTopNotifi");
    </script>
    <script type="text/javascript" src="/js/calificacionNotifi.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <script type="text/javascript" src="/js/funciones_helpers.js"></script>
@stop