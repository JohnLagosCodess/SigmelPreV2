@extends('adminlte::page')
@section('title', 'Bandeja Gestión Inicial')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
            <h3>Bandeja de Trabajo: Gestión Inicial</h3>
        </div>
    </div>
@stop

@section('content')
 <div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="tabla_bandeja_gestion" class="table table-bordered" style="width:100%">
                <thead class="bg-info">
                    <tr>
                        <th>Proceso</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Cliente</th>
                        <th>Nombre de afiliado</th>
                        <th>N° Identificación</th>
                        <th>Tipo de evento</th>
                        <th>ID evento</th>
                        <th>Empresa</th>
                        <th>Fecha última acción</th>
                        <th>Profesional acción</th>
                        <th>Fecha alerta</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                    </tr>
                    <tr>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                    </tr>
                    <tr>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                    </tr>
                    <tr>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                    </tr>
                    <tr>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                    </tr>
                    <tr>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                    </tr>
                    <tr>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                    </tr>
                    <tr>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                        <td>xxxxx</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12 mt-3">
        <input class="btn btn-outline-info" type="button" value="Generar Reporte">&nbsp;&nbsp;
        <input class="btn btn-outline-success" type="button" value="Guardar">
    </div>
</div>
@stop
            
@section('js')
    <script>
        $(document).ready(function(){
            $('#tabla_bandeja_gestion').DataTable({
                "pageLength": 20,
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
        });
    </script>
@stop