@extends('adminlte::page')
@section('title', 'Bandeja Notificaciones')
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
    <div class="card-info Notifibandeja" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Bandeja Notificaciones</h4>
            <input type="hidden" id="action_modulo_calificacion_Juntas" value="{{ route('calificacionJuntas') }}">
            <input type="hidden" id="action_modulo_calificacion_Origen" value="{{ route('calificacionOrigen') }}">
            <input type="hidden" id="action_modulo_calificacion_pcl" value="{{ route('calificacionPCL') }}">
            <!--traemos los datos de id rol y id usuario de la session -->
            <input type="hidden" class="form-control" name="newId_rol" id="newId_rol" value="{{$captura_id_rol = session('id_cambio_rol')}}">
            <input type="hidden" class="form-control" name="newId_user" id="newId_user" value="{{$user->id}}">
        </div>
 
        <form id="form_filtro_bandejaNotifi" method="POST">
            @csrf
            <div class="card-body">                
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="f_desde" class="col-form-label">Fecha Desde</label>
                                    <input type="date" class="f_desde form-control" name="consultar_f_desde" id="consultar_f_desde" max="{{date("Y-m-d")}}" min='1900-01-01'>
                                    <span class="d-none" id="consultar_f_desde_alerta" style="color: red; font-style: italic;"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="f_hasta" class="col-form-label">Hasta</label>
                                    <input type="date" class="f_hasta form-control" name="consultar_f_hasta" id="consultar_f_hasta" max="{{date("Y-m-d")}}" min='1900-01-01'>
                                    <span class="d-none" id="consultar_f_hasta_alerta" style="color: red; font-style: italic;"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="c_dias" class="col-form-label">Días mayor o igual a</label>
                                    <input type="number" class="c_dias form-control" name="consultar_g_dias" id="consultar_g_dias">                                    
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <p>&nbsp;</p>
                                    <input type="submit" id="btn_filtro_bandeja" class="btn btn-info" value="Filtrar" onclick="ocultarBotonFiltrar()">
                                </div>
                                <div class="text-center" id="mostrar-barra"  style="display:none;">                                
                                    <button class="btn btn-info" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Filtrando Bandeja Notificaciones...
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="resultado_validacion alert mt-1 d-none" id="llenar_mensaje_validacion" role="alert">
                            <strong ></strong>
                        </div>
                    </div>  
                    <div class="col-12">
                        <div class="resultado_validacion2 alert alert-danger mt-1 d-none" id="llenar_mensaje_validacion2" role="alert">
                            Debe ingresar la Fecha Desde y Hasta o Días mayor o igual a, para poder Filtar
                        </div>
                    </div>                                      
                </div>
                <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                    <i class="fas fa-chevron-up"></i>
                </a>
            </div>
        </form> 
        <div class="alert mt-1 alert-info " id="mensaje_importante" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle m-1"></i> 
                <strong class="me-2">Importante:</strong>
                <span class="spinner-border m-1" role="status" aria-hidden="true" style="width: 1rem; height: 1rem;"></span>
                <i>Cargando resultados de la Bandeja, por favor espere un momento...</i>
            </div>
        </div>
        <!-- Bandeja Notificaciones-->
        <form id="form_proser_bandejaNotifi" method="POST" class="d-none">
            @csrf
            <div class="row contenedor_casos_notifi">
                <div class="col-12">                                           
                    &nbsp; <label for="nro_registros" class="col-form-label" id="num_registroslabel">Se encontraron <span id="num_registros"></span> registros</label>
                    <br>&nbsp;<label for="nro_orden" class="col-form-label" id="num_ordenlabel">N° de orden: {{$n_orden[0]->Numero_orden}}</label>
                    <div class="card-body" id="contenedorTable">
                        <div class="col-12">
                            <div class="text-center">                                
                                <div class="alert alert-primary d-none"  id="actualizando_bandeja">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Actualizando bandeja <span id="formato_descarga"></span> por favor espere...
                                </div>
                            </div>
                            <div class="text-center">                                
                                <div class="alert alert-warning d-none"  id="sindatos_bandeja">
                                    No  se encontraron registros para la acción selecionada. 
                                    <input type="button" class="btn btn-info" onClick="history.go(0);" value="Recargar bandeja">
                                </div>
                            </div>
                        </div>
                        <div class="table table-responsive Bandeja_Notifi">
                            <table id="Bandeja_Notifi" class="table table-striped table-bordered" style="width:100%;">
                                <thead>
                                    <tr class="bg-info">
                                        <th class="detallenotifi">Detalle  </th>
                                        <th>Nombre de afiliado</th>
                                        <th>N° identificación</th>
                                        <th>Servicio</th>
                                        <th>N° de orden</th>
                                        <th>Estado general de la Notificacion</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                        <th>Fecha acción</th>
                                        <th>Profesional actual</th>
                                        <th>Tipo de evento</th>
                                        <th>ID evento</th>
                                        <th>Fecha de evento</th>
                                        <th>Fecha de radicación</th>
                                        <th>Tiempo de gestión</th>
                                        <th>Dias transcurridos desde el evento</th>
                                        <th>Empresa actual</th>
                                        <th>Proceso</th>
                                        <th>Proceso que envia</th>
                                        <th>Fecha asignación al proceso</th>
                                        <th>Asignado por</th>
                                        <th>Fecha alerta</th>
                                        <th>Estado alerta</th>
                                        <th>Fecha de asignación para notificación</th>
                                        <th>N° Radicado</th>
                                        {{-- <th>Asunto</th>
                                        <th>Fecha de envio</th> --}}
                                        <th>Cliente</th>
                                    </tr>
                                </thead>
                                <tbody id="body_listado_casos_notifi">                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                   {{-- @if ($dato_rol<>'5' && $dato_rol<>'9') --}}
                        <div class="card-body" id="contenedor_selectores">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alerta_completado alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                    <div class="alerta_error alert alert-danger mt-2 mr-auto d-none" role="alert"></div>        
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="f_accion" class="form-label col-form-label">Fecha de acción</label>
                                                <input type="datetime" class="form-control" name="f_accion" id="f_accion" value="{{now()->format('Y-m-d h:s')}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="accion_ejecutar" class="col-form-label">Acción <span style="color:red;">(*)</span></label>
                                                <select class="custom-select initSelect2" id="accion_ejecutar" name="accion_ejecutar" style="width: 100%" required>
                                                    <option value=""></option>
                                                    @foreach ($listado_Acciones as $acciones )
                                                        <option value="{{$acciones->Accion_ejecutar}}">{{$acciones->Accion}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>   
                                        <div class="col-4">
                                            <div class="form-group">    
                                                <label for="profesional" class="col-form-label">Descripcion</label>
                                                <textarea name="descripcion" class="form-control" id="descripcion"  rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="f_alerta" class="col-form-label">Fecha de alerta</label>
                                                <input type="datetime-local" class="form-control" name="f_alerta" id="f_alerta">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{-- @endif --}}
                    <div class="card-footer">
                        <div class="alert alert-danger no_ejecutar_accion d-none" role="alert">
                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede ejecutar la accion seleccionada, ya que no ha seleccionado ningun evento
                        </div>
                        <div class="grupo_botones" style="float: left;">
                            <input type="button" id="btn_expor_datos" class="btn btn-info" value="Exportar datos"> 
                            <input type="button" id="btn_ejecutar_accion" class="btn btn-info" value="Ejecutar accion"> 
                        </div>
                    </div>
                </div>
            </div>
        </form>  
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
    <script src="/js/funciones_helpers.js?v=1.0.0"></script>
    <script src="/js/bandeja_notifi.js"></script>
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
                    $('#btn_filtro_bandeja').addClass('d-none');
                    return;
                }
                //Validamos que la fecha no sea mayor a la fecha actual
                if(this.value > today){
                    $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                    $('#btn_filtro_bandeja').addClass('d-none');
                    return;
                }
                $('#btn_filtro_bandeja').removeClass('d-none');
                return $(`#${this.id}_alerta`).text('').addClass("d-none");
            });
        });
    </script>
    <script>
        function ocultarBotonFiltrar(){
            $('#btn_filtro_bandeja').addClass('d-none');
            $('#mostrar-barra').css("display","block");                
            setTimeout(() => {
                $('#btn_filtro_bandeja').removeClass('d-none');
                $('#mostrar-barra').css("display","none");                
            }, 2000);
        }

        $('#btn_bandeja').click(function(){
            location.reload();
        });
    </script>

@stop