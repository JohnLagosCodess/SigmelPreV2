@extends('adminlte::page')
@section('title', 'Listar Equipos de Trabajo')
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
                <i class="fa fa-sm fa-pen text-primary"></i> Editar Equipo &nbsp;
            </p>
        </div>
        <div class="card card-info">
            <div class="card-header">
                <h3>Listado de Equipos de Trabajo</h3>
            </div>
            <div class="card-body">
                @if (session()->get('grupo_editado'))
                    <div class="alert alert-success mt-2" role="alert">
                        <strong>{{session()->get('grupo_editado')}}</strong>
                    </div>
                @endif
                @if (session()->get('grupo_no_editado'))
                    <div class="alert alert-danger mt-2" role="alert">
                        <strong>{{session()->get('grupo_no_editado')}}</strong>
                    </div>
                @endif
                <label ><span>Equipos de Trabajo: Activos: {{$conteo_activos_inactivos[0]->Activos}}</span> - Inactivos: {{$conteo_activos_inactivos[0]->Inactivos}} </label>
                <div class="table-responsiv">
                    <table id="listado_grupos_trabajo" class="table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr class="bg-info">
                                <th>Acciones</th>
                                <th>N°</th>
                                <th>Proceso</th>
                                <th>Nombre del equipo de trabajo</th>
                                <th>Líder del equipo de trabajo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listado_equipos_trabajo as $editar_info_equipo)
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);" class="editar_grupo_trabajo" id="btn_modal_edicion_equipo_{{$editar_info_equipo->id}}" data-id_proceso="{{$editar_info_equipo->Id_proceso}}" data-id_lider="{{$editar_info_equipo->lider}}" data-id_equipo_trabajo="{{$editar_info_equipo->id}}" data-id_accion="{{$editar_info_equipo->Accion}}" data-toggle="modal" data-target="#modalEdicionEquipo_{{$editar_info_equipo->id}}"><i class="fa fa-pen text-primary"></i></a>
                                        <x-adminlte-modal id="modalEdicionEquipo_{{$editar_info_equipo->id}}" title="Formulario para editar Equipo de Trabajo: {{$editar_info_equipo->Nombre_equipo}}" theme="info" icon="fa fa-pen" size='xl' scrollable="yes" disable-animations>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
                                                    <form id="form_actualizar_equipo_{{$editar_info_equipo->id}}" method="POST">
                                                        @csrf
                                                        <div style="display:none;">
                                                            <input type="text" id="ruta_guardar_edicion_equipo" value="{{route('GuardarEdicionEquipoTrabajo')}}">
                                                            <input type="text" name="id_equipo_trabajo" value="{{$editar_info_equipo->id}}">
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <div class="form-group">
                                                                    <label  class="col-form-label">Proceso <span style="color:red;">(*)</span></label>
                                                                    <select class="editar_proceso" custom-select" name="editar_proceso" id="editar_proceso_{{$editar_info_equipo->Id_proceso}}" style="width:100%;" requierd></select>
                                                                </div>
                                                            </div>
                                                            <div class="col-3">
                                                                <div class="form-group">
                                                                    <label for="editar_nombre_equipo_trabajo" class="col-form-label">Equipo de trabajo <span style="color:red;">(*)</span></label>
                                                                    <input type="text" class="form-control" name="editar_nombre_equipo_trabajo" id="editar_nombre_equipo_trabajo_{{$editar_info_equipo->id}}" value="{{$editar_info_equipo->Nombre_equipo}}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-3">
                                                                <div class="form-group">
                                                                    <label for="editar_listado_lider" class="col-form-label">Lider del equipo de trabajo <span style="color:red;">(*)</span></label>
                                                                    <select class="editar_listado_lider custom-select" id="editar_listado_lider_{{$editar_info_equipo->Id_proceso}}" name="editar_listado_lider" style="width:100%;" required>
                                                                    </select>
                                                                    <strong class="mensaje_no_hay_usuarios_edicion text-danger text-sm d-none" role="alert">No hay usuarios relacionados al proceso seleccionado.</strong>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-2">
                                                                <div class="form-group">
                                                                    <label for="listado_acciones_editar" class="col-form-label">Acción <span style="color:red;">(*)</span></label>
                                                                    <select id="listado_acciones_editar_{{$editar_info_equipo->Id_proceso}}" name="listado_acciones_editar" class="listado_acciones_editar_{{$editar_info_equipo->Id_proceso}} custom-select" required></select>
                                                                </div>
                                                            </div> --}}
                                                            <div class="col-3">
                                                                <div class="form-group">
                                                                    <label for="editar_estado_equipo" class="col-form-label">Status <span style="color:red;">(*)</span></label>
                                                                    <select id="editar_estado_equipo" class="editar_estado_equipo_{{$editar_info_equipo->Id_proceso}} custom-select" name="editar_estado_equipo" required>
                                                                        @if ($editar_info_equipo->estado == 'activo')
                                                                            <option value="activo" selected>Activo</option> 
                                                                            <option value="inactivo">Inactivo</option>
                                                                            @elseif ($editar_info_equipo->estado == 'inactivo')
                                                                            <option value="activo">Activo</option> 
                                                                            <option value="inactivo" selected>Inactivo</option>
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="editar_listado_usuarios_equipo" class="col-form-label">Usuarios del equipo de trabajo <span style="color:red;">(*)</span></label>
                                                                    <select multiple="multiple" id="editar_listado_usuarios_equipo_{{$editar_info_equipo->id}}" class="editar_listado_usuarios_equipo" name="editar_listado_usuarios_equipo[]"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="fecha_creacion_equipo" class="col-form-label">Fecha de creación <span style="color:red;">(*)</span></label>
                                                                    <input type="date" class="form-control" readonly name="fecha_creacion_equipo" id="fecha_creacion_equipo" value="<?php echo date("Y-m-d", strtotime($editar_info_equipo->created_at)); ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label class="col-form-label">Descripción del equipo de trabajo <span style="color:red;">(*)</span></label>
                                                                    <textarea class="form-control" name="editar_descripcion_equipo_trabajo" id="editar_descripcion_equipo_trabajo_{{$editar_info_equipo->id}}" rows="4" required>{{$editar_info_equipo->descripcion}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div id="mostrar_mensaje_act_equipo_{{$editar_info_equipo->id}}" class="alert mt-2 mr-auto d-none" role="alert"></div>
                                                        <input type="submit" class="btn btn-info" value="Guardar Información">
                                                        <button type="button" id="btn_actualizar_consulta_{{$editar_info_equipo->id}}" class="btn btn-info mr-auto d-none">Actualizar</button>
                                                        <button type="button" class="btn btn-danger" style="float:right;" data-dismiss="modal">Cerrar</button>
                                                        <x-slot name="footerSlot">
                                                        </x-slot>
                                                    </form>
                                                </div>
                                            </div>
                                        </x-adminlte-modal>
                                    </td>
                                    <td>{{$editar_info_equipo->id}}</td>
                                    <td>{{$editar_info_equipo->Nombre_proceso}}</td>
                                    <td>{{$editar_info_equipo->Nombre_equipo}}</td>
                                    <td>{{$editar_info_equipo->Nombre_lider}}</td>
                                    <td><?php echo ucfirst($editar_info_equipo->estado);?></td>
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
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables-buttons-excel-styles@1.2.0/js/buttons.html5.styles.templates.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#listado_grupos_trabajo thead tr').clone(true).addClass('filters').appendTo('#listado_grupos_trabajo thead');
            $('#listado_grupos_trabajo').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                pageLength: 5,
                "destroy": true,
                "order": [[1, 'asc']],
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
                            
                            $(cell).html('<input type="text" style="width:100%;"/>');
                            $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                            .on('change', function (e) {
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})';
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
                            title: 'Lista de Equipos de Trabajo',
                            text:'Exportar datos',
                            className: 'btn btn-info',
                            "excelStyles": [                      // Add an excelStyles definition
                                                        
                            ],
                            exportOptions: {
                                columns: [1,2,3,4]
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
        });
    </script>
    <script src="/plugins/duallistbox_4.0.2/dist/jquery.bootstrap-duallistbox.js"></script>
    <script src="/js/equipos_trabajo.js"></script>
    <script src="/js/funciones_helpers.js"></script>
@stop