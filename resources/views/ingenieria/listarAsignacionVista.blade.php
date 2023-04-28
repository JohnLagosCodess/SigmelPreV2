@extends('adminlte::page')
@section('title', 'Consulta Asignación de Vistas')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
 <div class="row">
    <div class="col-12">
        <a href="{{route("RolPrincipal")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
        <br>
        <div>
            <h4>Convenciones:</h4>
            <p>
                <i class="far fa-eye text-success"></i> Activar Vista &nbsp;
                <i class="far fa-eye-slash text-danger"></i> Inactivar Vista &nbsp;
                <i class="fas fa-user-check text-success"></i> Cambiar a Vista principal &nbsp;
                <i class="fa fa-sm fa-pen text-primary"></i> Editar Vista &nbsp;
            </p>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h3>Formulario para Consultar Asignación de Vistas</h3>
            </div>
            <div class="card-body">
                @if (session()->get('confirmacion_vista_inactivada'))
                    <div class="alert alert-success mt-2" role="alert">
                        <strong>{{session()->get('confirmacion_vista_inactivada')}}</strong>
                    </div>
                @endif
                @if (session()->get('confirmacion_vista_activada'))
                    <div class="alert alert-success mt-2" role="alert">
                        <strong>{{session()->get('confirmacion_vista_activada')}}</strong>
                    </div>
                @endif
                @if (session()->get('confirmacion_vista_principal'))
                    <div class="alert alert-success mt-2" role="alert">
                        <strong>{{session()->get('confirmacion_vista_principal')}}</strong>
                    </div>
                @endif
                <div class="form-group row">
                    <label for="listado_roles_asignacion" class="col-sm-2 col-form-label">Seleccione un Rol</label>
                    <div class="col-sm-10">
                        <select id="listado_roles_asignacion" class="listado_roles_asignacion custom-select" name="listado_roles_asignacion"></select>
                    </div>
                </div>
                <hr>
                <div class="table table-responsive">
                    <table id="listado_asignacion_vistas" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre Vista (Carpeta)</th>
                                <th>Sub Carpeta</th>
                                <th>Nombre Archivo</th>
                                <th>Tipo de Vista</th>
                                <th>Estado de la Vista</th>
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
    <script src="/js/vistas.js"></script>
    <script type="text/javascript">
        /* CARGAR DATOS A DATATABLE ACORDE A LA SELECCIÓN DEL ROL. */
        $('#listado_roles_asignacion').change(function(){
            var rol_asignacion = $('#listado_roles_asignacion').val();

            var datos_consultar_asignacion = {
                'rol_id': rol_asignacion,
                '_token': $('input[name=_token]').val()
            };
            $.ajax({
                type:'POST',
                url:'/ConsultaAsignacionVistaRol',
                data: datos_consultar_asignacion,
                success:function(data) {
                    var generar_estado = "";
                    var generar_tipo = "";
                    for (let i = 0; i < data.length; i++) {
                        generar_estado = "<a href={{route('EditarVista', ['id_vista'=>':id'])}} class='btn' Title='Editar Vista'><i class='fa fa-sm fa-pen text-primary'></i></a>";
                        generar_estado = generar_estado.replace(':id', data[i]['id_vista']);
                        data[i]['acciones'] = generar_estado;

                        /* // ESTADO DEL ROL
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
                        } */
                    };
                    
                    $.each(data, function(index, value){
                        llenar(data, index, value);
                    });
                }
            });
        });
        function llenar(response, index,value){
            $('#listado_asignacion_vistas').DataTable({
                "destroy": true,
                "data": response,
                "order": [[3, 'desc']],
                "columns":[
                    {"data":"carpeta"},
                    {"data":"subcarpeta"},
                    {"data":"archivo"},
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
                    }
                }
            });
        }
    </script>
@stop