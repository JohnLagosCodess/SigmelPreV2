@extends('adminlte::page')
@section('title', 'Bandeja Juntas')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
            <?php 
               $dato_rol=$captura_id_rol = session('id_cambio_rol');
               /* 
                    Id rol #5 pertenece al Profesional
                    Id rol #9 pertenece al Analista
                    Id rol #10 pertenece al Comité
                */
            ?>
        </div>
    </div>

@stop
@section('content')
    <div class="card-info Juntasbandeja" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Bandeja Juntas</h4>
            <input type="hidden" id="action_modulo_calificacion_Juntas" value="{{ route('calificacionJuntas') }}">
            <!--traemos los datos de id rol y id usuario de la session -->
            <input type="hidden" class="form-control" name="newId_rol" id="newId_rol" value="{{$captura_id_rol = session('id_cambio_rol')}}">
            <input type="hidden" class="form-control" name="newId_user" id="newId_user" value="{{$user->id}}">
        </div>
        <form id="form_filtro_bandejaJuntas" method="POST">
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
                                        Filtrando Bandeja Juntas...
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

            &nbsp; <label for="nro_registros2" class="col-form-label d-none" id="num_registros2">Se encontraron <span></span> 0 registros</label>
        </form> 
        <!-- Bandeja Juntas-->
        <form id="form_proser_bandejaJuntas" method="POST">
            @csrf
            <div class="row contenedor_casos_juntas">
                <div class="col-12">
                    <div class="alert mt-1 alert-info d-none" id="mensaje_importante" role="alert">
                        <i class="fas fa-info-circle"></i> <strong>Importante:</strong> <span id="iniciando_bandeja">Cargando resultados de la Bandeja ...</span>
                        <span id="bandeja_iniciada" class="d-none">Se encontraron <b><span id="alerta_num_registros"></span></b> registros, esto tardará un tiempo en cargar los resultados. Por favor espere.</span>
                    </div>                                      
                    &nbsp; <label for="nro_registros" class="col-form-label d-none" id="num_registroslabel">Se encontraron <span id="num_registros"></span> registros</label>
                    <div class="card-body d-none" id="contenedorTable">
                        <div class="table table-responsive">
                            <table id="Bandeja_Juntas" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="bg-info">
                                        <th class="detallejuntas">Detalle  </th>
                                        <th>Cliente</th>
                                        <th>Nombre de afiliado</th>
                                        <th>N° identificación</th>
                                        <th>Servicio</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
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
                                        <th>Enfermedad heredada</th>
                                        <th>Parte que controvierte primera calificación</th>
                                        <th>Tipo de controversia primera calificación</th>
                                        <th>Término de controversia ante primera calificación</th>
                                        <th>Junta Regional de Calificación de Invalidez (JRCI)</th>
                                        <th>N° Dictamen (JRCI)</th>
                                        <th>Fecha de Radicado entrada Dictamen (JRCI)</th>
                                        <th>Fecha de asignación para pronunciamiento de Juntas</th>
                                        <th>Pronunciamiento ante Dictamen de JRCI</th>
                                        <th>N° Acta Ejecutoria emitida por JRCI</th>
                                        <th>N° radicado de recurso ante JRCI</th>
                                        <th>Término de controversia propia ante JRCI</th>
                                        <th>Parte que presenta controversia ante JRCI</th>
                                        <th>Tipo de controversia presentada por otra parte ante JRCI</th>
                                        <th>Fecha acción</th> 
                                    </tr>
                                </thead>
                                <tbody id="body_listado_casos_juntas">                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($dato_rol<>'5' && $dato_rol<>'9' && $dato_rol<>'10')
                        <div class="card-body" id="contenedor_selectores">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mostrar_mensaje_actualizo_bandeja alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                    <div class="mostrar_mensaje_No_actualizo_bandeja alert alert-danger mt-2 mr-auto d-none" role="alert"></div>        
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="procesos_parametrizados" class="col-form-label">Procesos</label>
                                                <select class="procesos_parametrizados custom-select" id="procesos_parametrizados" name="procesos_parametrizados"></select>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="redireccionar" class="col-form-label">Redireccionar a</label>
                                                <select class="redireccionar custom-select" id="redireccionar" name="redireccionar"></select>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="accion" class="col-form-label">Acción</label>
                                                <select class="accion custom-select" id="accion" name="accion"></select>
                                            </div>
                                        </div>    
                                        <div class="col-sm columna_selector_profesional">
                                            <div class="form-group">
                                                <label for="profesional" class="col-form-label">Profesional</label>
                                                <select class="profesional custom-select" id="profesional"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 columna_decripcion_bandeja">
                                            <div class="form-group">
                                                <label for="descripcion_bandeja" class="col-form-label">Descripción</label>
                                                <textarea class="form-control" name="descripcion_bandeja" id="descripcion_bandeja" cols="30" rows="2" style="resize: none;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-footer">
                        <div class="alert alert-danger no_ejecutar_parametrica_bandeja_trabajo d-none" role="alert">
                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede mover la información debido a que el proceso, servicio y/o acción seleccionados no tienen una parametrización
                            asociada. Debe configurar una.
                        </div>
                        <div class="grupo_botones" style="float: left;">
                            @if ($dato_rol<>'5' && $dato_rol<>'9' && $dato_rol<>'10')
                                <input type="submit" id="btn_guardar" class="btn btn-info" value="Actualizar">
                                <input type="button" id="btn_bandeja" class="btn btn-info d-none" value="Retornar Bandeja">
                            @endif
                            <input type="button" id="btn_expor_datos" class="btn btn-info" value="Exportar datos"> 
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

    <script src="/js/bandeja_juntas.js"></script>
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