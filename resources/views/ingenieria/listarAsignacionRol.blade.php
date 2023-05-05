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
                        <table id="listado_asignacion_roles" class="table table-striped">
                            <thead>
                                <tr>
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
        $('#listado_usuarios_asignacion_rol').change(function(){
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
                        for (let i = 0; i < data.length; i++) {
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
                            llenar(data, index, value);
                        });
                    }
                }
            });
        });
        function llenar(response, index,value){
            $('#listado_asignacion_roles').DataTable({
                "destroy": true,
                "data": response,
                "order": [[2, 'desc']],
                "columns":[
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