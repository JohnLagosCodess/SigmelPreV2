@extends('adminlte::page')
@section('title', 'Consulta Asignación de Rol')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-12">
            {{-- <a href="{{route("RolPrincipal")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <a href="{{route('NuevoRol')}}" class="btn btn-info"><i class="fas fa-plus"></i> Crear Rol</a>
            <a href="{{route('ListadoRoles')}}" class="btn btn-info"><i class="fas fa-list"></i> Consultar Lista de Roles</a>
            <a href="{{route('AsignacionRol')}}" class="btn btn-info"><i class="far fa-address-card"></i> Asignar Roles a Usuarios</a>
            <br> --}}
            <div>
                <h4>Convenciones:</h4>
                <p>
                    <i class="far fa-eye text-success"></i> Activar Rol &nbsp;
                    <i class="far fa-eye-slash text-danger"></i> Inactivar Rol &nbsp;
                    <i class="fas fa-user-check text-success"></i> Cambiar a Rol Principal &nbsp;
                </p>
            </div>
            <div class="card card-info">
                <div class="card-header">
                    <h3>Formulario para Consultar Asignación de Roles</h3>
                </div>
                <div class="card-body">
                    @if (session()->get('rol_inactivado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('rol_inactivado')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('rol_activado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('rol_activado')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('rol_principal'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('rol_principal')}}</strong>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="listado_usuarios_asignacion_rol" class="col-sm-2 col-form-label">Seleccione un Usuario</label>
                        <div class="col-sm-10">
                            <select id="listado_usuarios_asignacion_rol" class="listado_usuarios_asignacion_rol custom-select" name="listado_usuarios_asignacion_rol"></select>
                        </div>
                    </div>
                    <hr>
                    <span id="no_info"></span>
                    <div class="table table-responsive" id="si_tabla">
                        <table id="listado_asignacion_roles" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="bg-info">
                                    <th>N°</th>
                                    <th>Nombre Rol</th>
                                    <th>Tipo de Rol</th>
                                    <th>Estado del Rol</th>
                                    <th>Fecha y Hora de Creación</th>
                                    <th>Fecha y Hora de Actualización</th>
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
    <script src="/js/selector_usuarios.js"></script>
    <script type="text/javascript">
        /* CARGAR DATOS A DATATABLE ACORDE A LA SELECCIÓN DEL USUARIO. */
        $('#listado_asignacion_roles thead tr').clone(true).addClass('filters').appendTo('#listado_asignacion_roles thead');
        $('#listado_usuarios_asignacion_rol').change(function(){
            var selectedOption = $('#listado_usuarios_asignacion_rol').find(':selected');
            var textoUsuario = selectedOption.text();

            var usuario_asignacion = $('#listado_usuarios_asignacion_rol').val();
            var datos_consultar_asignacion = {
                'usuario_id': usuario_asignacion,
                '_token': $('input[name=_token]').val()
            };
            $.ajax({
                type:'POST',
                url:'/ConsultaAsignacionRolUsuario',
                data: datos_consultar_asignacion,
                success:function(data) {
                    if(data.length == 0){
                        $('#no_info').empty();
                        $('#no_info').append('<h3>No se encontró información.</h3>');
                        $('#si_tabla').css("display", "none");
                    }else{
                        $('#no_info').empty();
                        $('#si_tabla').css("display", "block");
                        var generar_estado = "";
                        var generar_tipo = "";
                        var aumentar = 0;
                        for (let i = 0; i < data.length; i++) {
                            aumentar = aumentar + 1
                            data[i]['aumentar'] = aumentar;
                            // ESTADO DEL ROL
                            if(data[i]['estado'] === 'activo'){
                                generar_estado = "<a href={{route('inactivarRol', ['id'=>':id', 'usuario_id'=>':usuario_id', 'rol_id'=>':rol_id'])}} class='btn' Title='Inactivar Rol'><i class='far fa-eye-slash text-danger'></i></a>";
                                generar_estado = generar_estado.replace(':id', data[i]['id']);
                                generar_estado = generar_estado.replace(':usuario_id', data[i]['usuario_id']);
                                generar_estado = generar_estado.replace(':rol_id', data[i]['rol_id']);
                                data[i]['acciones'] = generar_estado;
                            }else{
                                generar_estado = "<a href={{route('activarRol', ['id'=>':id', 'usuario_id'=>':usuario_id', 'rol_id'=>':rol_id'])}} class='btn' Title='Activar Rol'><i class='far fa-eye text-success'></i></a>";
                                generar_estado = generar_estado.replace(':id', data[i]['id']);
                                generar_estado = generar_estado.replace(':usuario_id', data[i]['usuario_id']);
                                generar_estado = generar_estado.replace(':rol_id', data[i]['rol_id']);
                                data[i]['acciones'] = generar_estado;
                            }
                            // TIPO DE ROL
                            if (data[i]['tipo'] === 'otro' && data[i]['estado'] === 'activo') {
                                generar_tipo = "<a href={{route('cambiarARolPrincipal', ['id'=>':id', 'usuario_id'=>':usuario_id', 'rol_id'=>':rol_id'])}} class='btn' Title='Cambiar a Rol Principal'><i class='fas fa-user-check text-success'></i></a>";
                                generar_tipo = generar_tipo.replace(':id', data[i]['id']);
                                generar_tipo = generar_tipo.replace(':usuario_id', data[i]['usuario_id']);
                                generar_tipo = generar_tipo.replace(':rol_id', data[i]['rol_id']);
                                data[i]['acciones'] = generar_estado + generar_tipo;
                            }
                        };
                        
                        $.each(data, function(index, value){
                            llenar(data, index, value, textoUsuario);
                        });
                    }
                }
            });
        });
        function llenar(response, index,value, textoUsuario){
            
            $('#listado_asignacion_roles').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                pageLength: 5,
                "destroy": true,
                "data": response,
                "order": [[0, 'asc']],
                initComplete: function () {
                    var api = this.api();
                        // For each column
                    api.columns().eq(0).each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        
                        var title = $(cell).text();
                        
                        if (title !== 'Acciones') {
                            
                            $(cell).html('<input type="text" />');
                            $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                            .on('change', function (e) {
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
                            })
                            .on('keyup', function (e) {
                                e.stopPropagation();
                                var cursorPosition = this.selectionStart;
                                $(this).trigger('change');
                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                        }

                    });
                },
                dom: 'Bfrtip',
                buttons:{
                    dom:{
                        buttons:{
                            className: 'btn'
                        }
                    },
                    buttons:[
                        {
                            extend:"excel",
                            title: 'Lista Asignacion de Roles de: '+ textoUsuario,
                            text:'Exportar datos',
                            className: 'btn btn-info',
                            "excelStyles": [                      // Add an excelStyles definition
                                                        
                            ],
                            exportOptions: {
                                columns: [0,1,2,3,4,5]
                            }
                        }
                    ]
                },
                "columns":[
                    {"data":"aumentar"},
                    {"data":"nombre_rol"},
                    {"data":"tipo"},
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