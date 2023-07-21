@extends('adminlte::page')
@section('title', 'Bandeja PCL')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
            
        </div>
    </div>

@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div>
                {{-- <h4>Convenciones:</h4>
                <p>
                    <!--<i class="far fa-eye text-success"></i> Activar Menú/Sub Menú &nbsp;
                    <i class="far fa-eye-slash text-danger"></i> Inactivar Menú/Sub Menú &nbsp;-->
                <span style="color:red;">(*)</span> Campo Obligatorio&nbsp;
                <!--<i class="fa fa-puzzle-piece text-info"></i> Agregar Nuevo Servicio&nbsp;-->
                </p> --}}
            </div>
        </div>
    </div>
    <div class="card-info PCLbandeja" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Bandeja PCL</h4>
            <input type="hidden" id="action_modulo_calificacion_pcl" value="{{ route('calificacionPCL') }}">
        </div>        
        <form id="form_filtro_bandejaPcl" method="POST">
            @csrf
            <div class="card-body">                
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="f_desde" class="col-form-label">Fecha Desde</label>
                                    <input type="date" class="f_desde form-control" name="consultar_f_desde" id="consultar_f_desde">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="f_hasta" class="col-form-label">Hasta</label>
                                    <input type="date" class="f_hasta form-control" name="consultar_f_hasta" id="consultar_f_hasta">
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
                                        Filtrando Bandeja PCL...
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
            </div>
                          
            &nbsp; <label for="nro_registros2" class="col-form-label d-none" id="num_registros2">Se encontraron <span></span> 0 registros</label>
            
        </form>        
        <!-- Bandeja PCL-->
        <form id="form_proser_bandejaPcl" method="POST">
            @csrf
            <div class="row contenedor_casos_pcl">
                <div class="col-12">                                           
                    &nbsp; <label for="nro_registros" class="col-form-label" id="num_registroslabel">Se encontraron <span id="num_registros"></span> registros</label>
                    <div class="card-body" id="contenedorTable">
                        <div class="table table-responsive">
                            <table id="Bandeja_Pcl" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="bg-info">
                                        <th class="detallepcl">Detalle  </th>
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
                                        <th>Fecha solicitud documentos</th>
                                        <th>Fecha recepción documentos</th>
                                        <th>Fecha de asignación para calificación</th>
                                        <th>Fecha devolución comité</th>
                                        <th>Fecha acción</th>
                                    </tr>
                                </thead>
                                <tbody id="body_listado_casos_pcl">                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body" id="contenedor_selectores">
                        <div class="row">
                            <div class="col-12">
                                <div class="mostrar_mensaje_actualizo_bandeja alert alert-success mt-2 mr-auto d-none" role="alert"></div>
                                <div class="mostrar_mensaje_No_actualizo_bandeja alert alert-danger mt-2 mr-auto d-none" role="alert"></div>        
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="profesional" class="col-form-label">Profesional</label>
                                            <select class="profesional custom-select" id="profesional">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="redireccionar" class="col-form-label">Redireccionar a</label>
                                            <select class="redireccionar custom-select" id="redireccionar" name="redireccionar"></select>
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="grupo_botones" style="float: left;">
                            <input type="submit" id="btn_guardar" class="btn btn-info" value="Actualizar">
                            <input type="button" id="btn_expor_datos" class="btn btn-info" value="Exportar datos"> 
                            <input type="button" id="btn_bandeja" class="btn btn-info d-none" value="Retornar Bandeja"> 
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

    <script src="/js/bandeja_pcl.js"></script>    

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