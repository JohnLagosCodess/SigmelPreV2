@extends('adminlte::page')
@section('title', 'Buscar Evento')
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
                <h4>Convenciones:</h4>
                <p>
                    <!--<i class="far fa-eye text-success"></i> Activar Menú/Sub Menú &nbsp;
                    <i class="far fa-eye-slash text-danger"></i> Inactivar Menú/Sub Menú &nbsp;-->
                    <i class="fas fa-filter text-primary"></i> Habilitar Filtros Columnas &nbsp;
                   <span style="color:red;">(*)</span> Campo Obligatorio.
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Consultar Evento</h4>
        </div>
        <!-- Busqueda Filtros -->
        <form action="{{route('creacionEvento')}}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="nro_identificacion" class="col-form-label">N° de identificación</label>
                                    <input type="number" class="nro_identificacion form-control" name="nro_identificacion" id="nro_identificacion">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="id_evento" class="col-form-label">ID evento</label>
                                    <input type="number" class="id_evento form-control" name="id_evento" id="id_evento">
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
                </div>
            </div>
        </form>
        <!-- Fin Busqueda Filtros -->
        <!-- Información del afiliado-->
        <div class="row">
            <div class="col-12">
                <div class="card-info">
                    <div class="card-header text-center" style="border: 1.5px solid black;">
                        <h5>Información del afiliado</h5>
                        <input type="hidden" id="action_afiliado" value="">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">Nombre de afiliado:</label>
                            <span id="nombre_afiliado">Pepe Ramirez</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label">N° de identificación:</label>
                            <span id="nro_identificacion">1054545054054</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin información de afiliado-->
        <!-- información del evento-->
        <div class="row">
            <div class="col-12">
                &nbsp; <label for="nro_registros" class="col-form-label">Se encontraron xxxx registros</label>
                <div class="card-info">
                    <div class="card-header text-center" style="border: 1.5px solid black;">
                        <h5>Resultados de consulta</h5>
                        <input type="hidden" id="action_afiliado" value="">
                    </div>
                </div>
                
                <i class="fas fa-filter text-primary"></i>  <input type="checkbox" name="subscribe" id="subscribe">

                <div class="card-body">
                    <div class="table table-responsive">
                        <table id="Consulta_Eventos" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>ID evento</th>
                                    <th>Cliente</th>
                                    <th>Empresa</th>
                                    <th>Tipo de evento</th>
                                    <th>Fecha radicación </th>
                                    <th>Fecha registro</th>
                                    <th>Proceso</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Resultado</th>
                                    <th>Fecha de acción</th>
                                    <th>Fecha de dictamen</th>
                                    <th>Fecha de Notificación</th>
                                    <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                    <td>xxxx</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="grupo_botones" style="float: left;">
                        <input type="submit" id="btn_expor_datos" class="btn btn-info" value="Exportar datos">
                        <input type="submit" id="btn_new_servicio" class="btn btn-info" value="Nuevo servicio">
                        <input type="submit" id="btn_new_consulta" class="btn btn-info" value="Nueva Consulta">
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
   <script>
        $(document).ready(function(){
            
            var table = $('#Consulta_Eventos').DataTable({
                "orderCellsTop": true,
                "fixedHeader": true,
                "scrollX": true,
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
            $('#subscribe').on('change',function(){
                if (this.checked) {
                    //Creamos una fila en el head de la tabla y lo clonamos para cada columna
                    $('#Consulta_Eventos thead tr').clone(true).appendTo( '#Consulta_Eventos thead' );
                    $('#Consulta_Eventos thead tr:eq(1) th').each( function (i) {
                        var title = $(this).text(); //es el nombre de la columna
                        $(this).html( '<input type="text" placeholder="Buscar...'+title+'" />' );
                
                        $( 'input', this ).on( 'keyup change', function () {
                            if ( table.column(i).search() !== this.value ) {
                                table
                                    .column(i)
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                    } );  
                } else {
                    $("#Consulta_Eventos thead").hide();
                }  
             })
        });

    </script>
@stop