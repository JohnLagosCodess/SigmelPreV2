@extends('adminlte::page')
@section('title', 'Listado de Roles')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">

        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div>
                <h4>Convenciones:</h4>
                <p>
                    <i class="fa fa-sm fa-pen text-primary"></i> Editar Rol &nbsp;
                </p>
            </div>
            <div class="card card-info">
                <div class="card-header">
                    <h3>Listado de Roles</h3>
                </div>
                <div class="card-body">
                    @if (session()->get('rol_actualizado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('rol_actualizado')}}</strong>
                        </div>
                    @endif
                    <label><span>Total Roles: <?php echo count($listado_roles);?></span></label>
                    <div class="table-responsive">
                        <table id="listado_roles" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="bg-info">
                                    <th>Acciones</th>
                                    <th>N°</th>
                                    <th>Nombre del rol</th>
                                    <th>Descripción del rol</th>
                                    <th>Fecha y Hora de Creación</th>
                                    <th>Fecha y Hora de Actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listado_roles as $rol_editar)
                                    <tr>
                                        <td>
                                           
                                            <a href="javascript:void(0);" id="btn_modal_edicion_rol_{{$rol_editar->id}}" data-toggle="modal" data-target="#modalEdicionRol_{{$rol_editar->id}}"><i class="fa fa-pen text-primary"></i></a>
                                            <x-adminlte-modal id="modalEdicionRol_{{$rol_editar->id}}" title="Formulario Edición Rol: {{$rol_editar->nombre_rol}}" theme="info" icon="fa fa-pen" size='xl' scrollable="yes" disable-animations>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
                                                        <form id="form_actualizar_rol_{{$rol_editar->id}}" method="POST">
                                                            @csrf
                                                            <div style="display:none;">
                                                                <input type="text" name="rol_id" value="{{$rol_editar->id}}">
                                                                <input type="text" id="ruta_guardar_edicion_rol" value="{{ route('ActualizacionRol') }}">
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="editar_nombre_rol" class="col-sm-2 col-form-label">Nombre del rol <span style="color:red;">(*)</span></label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="editar_nombre_rol" id="editar_nombre_rol_{{$rol_editar->id}}" value="{{$rol_editar->nombre_rol}}" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="editar_descripcion_rol" class="col-sm-2 col-form-label">Descripción del rol <span style="color:red;">(*)</span></label>
                                                                <div class="col-sm-10">
                                                                    <textarea class="form-control" name="editar_descripcion_rol" id="editar_descripcion_rol_{{$rol_editar->id}}" rows="4" required>{{$rol_editar->descripcion_rol}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label">Fecha de actualización</label>
                                                                <div class="col-sm-10">
                                                                    <input type="date" class="form-control" readonly value="<?php echo date('Y-m-d'); ?>">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div id="mostrar_mensaje_act_rol_{{$rol_editar->id}}" class="alert mt-2 mr-auto d-none" role="alert"></div>
                                                            <input type="submit" class="btn btn-info" value="Guardar Información">
                                                            <button type="button" id="btn_actualizar_consulta__{{$rol_editar->id}}" class="btn btn-info mr-auto d-none">Actualizar</button>
                                                            <button type="button" class="btn btn-danger" style="float:right;" data-dismiss="modal">Cerrar</button>
                                                            <x-slot name="footerSlot">
                                                            </x-slot>
                                                        </form>
                                                    </div>
                                                </div>
                                            </x-adminlte-modal>
                                        </td>
                                        <td>{{$rol_editar->id}}</td>
                                        <td>{{$rol_editar->nombre_rol}}</td>
                                        <td>{{$rol_editar->descripcion_rol}}</td>
                                        <td>{{$rol_editar->created_at}}</td>
                                        <td>{{$rol_editar->updated_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="/js/funciones_helpers.js"></script>
    <script>
        $(document).ready(function(){
            /* PINTAR DATATABLE CON EL BOTÓN DE DESCARGA DE DATOS Y FILTROS POR COLUMNA */
            $('#listado_roles thead tr').clone(true).addClass('filters').appendTo('#listado_roles thead');
            $('#listado_roles').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                pageLength: 5,
                "order": [[ 1, 'asc' ]],
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
                            title: 'Lista Roles del Sistema',
                            text:'Exportar datos',
                            className: 'btn btn-info',
                            "excelStyles": [                      // Add an excelStyles definition
                                                        
                            ],
                            exportOptions: {
                                columns: [1,2,3,4,5]
                            }
                        }
                    ]
                },
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

            /* FUNCIONES BOTÓN HABILITAR MODAL EDICIÓN ROL */
            $(document).on('mouseover', "a[id^='btn_modal_edicion_rol_']", function(){
                $(".modal-footer").remove();
                $("button[id^='btn_actualizar_consulta_']").addClass('d-none');
            });

            /* ENVÍO DE FORMULARIO PARA GUARDAR EDICIÓN DE ROL */
            $(document).on('submit', "form[id^='form_actualizar_rol_']", function (e) {
                e.preventDefault();
    
                
                var formData = new FormData($(this)[0]);
                // for (var pair of formData.entries()) {
                //     console.log(pair[0]+" - "+ pair[1]);
                // }
    
                $.ajax({
                    url: $('#ruta_guardar_edicion_rol').val(),
                    type: "post",
                    dataType: "json",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        
                        $("button[id^='btn_actualizar_consulta_']").removeClass('d-none');
                        if (response.parametro == "exito") {
                            $("div[id^='mostrar_mensaje_act_rol_']").removeClass('d-none');
                            $("div[id^='mostrar_mensaje_act_rol_']").addClass('alert-success');
                            $("div[id^='mostrar_mensaje_act_rol_']").append('<strong>'+response.mensaje+'</strong>');
    
                            setTimeout(() => {
                                $("div[id^='mostrar_mensaje_act_rol_']").addClass('d-none');
                                $("div[id^='mostrar_mensaje_act_rol_']").removeClass('alert-success');
                                $("div[id^='mostrar_mensaje_act_rol_']").empty();
                            }, 9000);
    
                        }
                    }         
                });
    
            });
    
            /* FUNCIONALIDAD ACTUALIZAR PÁGINA DE CONSULTA DE USUARIOS */
            $(document).on('click', "button[id^='btn_actualizar_consulta_']", function(){
                location.reload();
            });
        });
    </script>


@stop