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
            {{-- <a href="{{route("RolPrincipal")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
            <a href="{{route('NuevoRol')}}" class="btn btn-info"><i class="fas fa-plus"></i> Crear Rol</a>
            <a href="{{route('AsignacionRol')}}" class="btn btn-info"><i class="far fa-address-card"></i> Asignar Roles a Usuarios</a>
            <a href="{{route('ConsultarAsignacionRol')}}" class="btn btn-info"><i class="fas fa-list"></i> Consultar Asignación de Roles a Usuarios</a>
            <br> --}}
            <div>
                <h4>Convenciones:</h4>
                <p>
                    <i class="fa fa-sm fa-pen text-primary"></i> Editar Rol &nbsp;
                </p>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Listado de Roles</h3>
                </div>
                <div class="card-body">
                    @if (session()->get('rol_actualizado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('rol_actualizado')}}</strong>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="listado_roles" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Fecha y Hora de Creación</th>
                                    <th>Fecha y Hora de Actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listado_roles as $rol_editar)
                                    <tr>
                                        <td>{{$rol_editar->nombre_rol}}</td>
                                        <td>{{$rol_editar->created_at}}</td>
                                        <td>{{$rol_editar->updated_at}}</td>
                                        <td>
                                            <form action="{{route("EditarRol")}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="rol_id" value="{{$rol_editar->id}}">
                                                <button class="btn btn-xs btn-default text-primary" title="Editar" type="submit">
                                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                                </button>
                                            </form>
                                        </td>
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
    <script>
        $(document).ready(function(){
            $('#listado_roles').DataTable({
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
        });
    </script>
@stop