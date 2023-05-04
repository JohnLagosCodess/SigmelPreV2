@extends('adminlte::page')
@section('title', 'Menús y Sub Menús')
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
                    <i class="far fa-eye text-success"></i> Activar Menú/Sub Menú &nbsp;
                    <i class="far fa-eye-slash text-danger"></i> Inactivar Menú/Sub Menú &nbsp;
                    <i class="fa fa-sm fa-pen text-primary"></i> Editar Información Menú/Sub Menú &nbsp;
                </p>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Formulario para Consultar Información de Menús y Sub Menús</h3>
                </div>
                <div class="card-body">
                    @if (session()->get('confirmacion_menu_inactivado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('confirmacion_menu_inactivado')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('confirmacion_menu_activado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('confirmacion_menu_activado')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('confirmacion_menu_editado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('confirmacion_menu_editado')}}</strong>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="listado_roles_consultar" class="col-sm-2 col-form-label">Seleccione un Rol</label>
                        <div class="col-sm-10">
                            <select id="listado_roles_consultar" class="listado_roles_consultar custom-select" name="listado_roles_consultar"></select>
                        </div>
                    </div>
                    <hr>
                    <span id="no_info"></span>
                    <div class="table table-responsive" id="si_tabla">
                        <table id="listado_menus_submenus" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre Menú</th>
                                    <th>Tipo de Menú</th>
                                    <th>Padre</th>
                                    <th>Estado</th>
                                    <th>Fecha de Creación</th>
                                    <th>Fecha de Actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="/js/menus.js"></script>
<script type="text/javascript">
    /* CARGAR DATOS A DATATABLE ACORDE A LA SELECCIÓN DEL ROL. */
    $('#listado_roles_consultar').change(function(){
        var rol_consultar = $('#listado_roles_consultar').val();

        var datos_consultar = {
                'rol_id': rol_consultar,
                '_token': $('input[name=_token]').val()
        };
        $.ajax({
            type:'POST',
            url:'/ConsultaMenusSubmenus',
            data: datos_consultar,
            success:function(data) {

                if(data.length == 0){
                    $('#no_info').empty();
                    $('#no_info').append('<h3>No se encontró información.</h3>');
                    $('#si_tabla').css("display", "none");
                }else{
                    $('#no_info').empty();
                    $('#si_tabla').css("display", "block");
                    var generar_estado = "";
                    var generar_editar = "";
                    for (let i = 0; i < data.length; i++) {
                        
                        // ESTADO DEL MENU
                        if (data[i]['estado'] === 'activo') {
                            generar_estado = "<a href={{route('inactivarMenuSubmenu', ['id'=>':id', 'tipo_menu'=>':tipo_menu'])}} class='btn' Title='Inactivar'><i class='far fa-eye-slash text-danger'></i></a>";
                            generar_estado = generar_estado.replace(':id', data[i]['id']);
                            generar_estado = generar_estado.replace(':tipo_menu', data[i]['tipo_menu'].replace(/\s+/g, '_'));
                            data[i]['acciones'] = generar_estado;
                        }else{
                            generar_estado = "<a href={{route('activarMenuSubmenu', ['id'=>':id', 'tipo_menu'=>':tipo_menu'])}} class='btn' Title='Activar'><i class='far fa-eye text-success'></i></a>";
                            generar_estado = generar_estado.replace(':id', data[i]['id']);
                            generar_estado = generar_estado.replace(':tipo_menu', data[i]['tipo_menu'].replace(/\s+/g, '_'));
                            data[i]['acciones'] = generar_estado;
                        }
    
                        generar_editar='<form action="{{route("EditarMenu")}}" method="POST" style="display:inline;">@csrf <input type="hidden" name="id_menu" value="'+data[i]['id']+'"><button class="btn btn-xs btn-default text-primary" title="Editar" type="submit"><i class="fa fa-lg fa-fw fa-pen"></i></button></form>';
                        data[i]['acciones'] = generar_estado + generar_editar;
                        
                    };
                        
                    $.each(data, function(index, value){
                        llenar(data, index, value);
                    });
                }
            }
        });
    });
    function llenar(response, index,value){
            $('#listado_menus_submenus').DataTable({
                "destroy": true,
                "data": response,
                "ordering": false,
                "pageLength": 5,
                "columns":[
                    {"data":"nombre"},
                    {"data":"tipo_menu"},
                    {"data":"padre"},
                    {"data":"estado"},
                    {"data":"created_at"},
                    {"data":"updated_at"},
                    {"data":"acciones"}
                ],
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
        }
</script>
@stop