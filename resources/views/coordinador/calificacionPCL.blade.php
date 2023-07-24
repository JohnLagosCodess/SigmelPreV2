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
                                                    <a href="#" id="servicio_Pcl"><i class="fa fa-puzzle-piece text-info"></i> <strong class="text-dark">{{$array_datos_calificacionPcl[0]->Nombre_servicio}}</strong></a>
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
                                                        <option value="{{$array_datos_calificacionPcl[0]->Modalidad_calificacion}}" selected>{{$array_datos_calificacionPcl[0]->Nombre_Modalidad_calificacion}}</option>                                                 
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
                                                    <a href="#" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalSolicitudDocSeguimiento"><i class="fas fa-book-open text-info"></i> <strong>Solicitud documentos - Seguimientos</strong></a>
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
        {{-- Modal solicitud documentos - seguimientos --}}
        <div class="row">
            <div class="contenedor_sol_Docuementos_seguimiento" style="float: left;">
                <x-adminlte-modal id="modalSolicitudDocSeguimiento" title="Solicitud Documentos - Seguimientos" theme="info" icon="fas fa-book-open" size='xl' disable-animations>
                    <form id="formulario_empresa">
                        @csrf
                        <div class="row text-center">
                            
                        </div>
                        <div class="container">
                            
                        </div>
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="mr-auto" id="guardar_otra_empresa" theme="info" label="Guardar"/>
                            <x-adminlte-button theme="danger" label="Cerrar" data-dismiss="modal"/>
                        </x-slot>
                    </form>
                </x-adminlte-modal>
            </div>
        </div>
        {{-- Modal para agregar documentos adjuntos --}}
        <?php $aperturaModal = 'Edicion'; ?>
        @include('administrador.modalcarguedocumentos')      
        <?php 
        /* echo'<pre>';
        print_r($arraylistado_documentos) ;
        echo'</pre>'; */
        ?>
    </div>    
@stop
@section('js')

    <script src="/js/calificacionpcl.js"></script>
    
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

        //funcion para poner la primera en mayuscula en el texarea descipcion de Acion a realizar

        // Obtén el elemento de textarea
        var descripcionAccion = document.getElementById('descripcion_accion');
        
        // Escucha el evento de entrada de texto en el textarea
        descripcionAccion.addEventListener('input', function() {
            // Obtén el contenido del textarea
            var mayuscula = descripcionAccion.value;
            
            // Verifica si hay texto en el textarea
            if (mayuscula.length > 0) {
                // Convierte la primera letra a mayúscula
                var textoMayuscula = mayuscula.charAt(0).toUpperCase() + mayuscula.slice(1);
                
                // Establece el contenido del textarea con la primera letra en mayúscula
                descripcionAccion.value = textoMayuscula;
            }
        });

        //funcion para ocultar el boton guardar
        function OcultarbotonGuardar(){
            $('#Edicion').addClass('d-none');
            $('#Borrar').addClass('d-none');
            $('#mostrar-barra2').css("display","block");
        }

        $('#Borrar').click(function(){
            location.reload();
        });
    </script>
        
@stop