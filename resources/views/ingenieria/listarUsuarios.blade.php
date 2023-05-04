@extends('adminlte::page')
@section('title', 'Listado de Usuarios')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">

        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- <a href="{{route("RolPrincipal")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a> --}}
            {{-- <a href="{{route("NuevoUsuario")}}" class="btn btn-info" type="button"><i class="fas fa-user"></i> Crear Usuario</a> --}}
            {{-- <br> --}}
            <div>
                <h4>Convenciones:</h4>
                <p>
                    <i class="fa fa-sm fa-pen text-primary"></i> Editar &nbsp;
                </p>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3>Listado de Usuarios</h3>
                </div>
                <div class="card-body">
                    @if (session()->get('actualizado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('actualizado')}}</strong>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="listado_usuarios" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Tipo de Contrato</th>
                                    <th>Empresa</th>
                                    <th>Fecha y Hora de Creación</th>
                                    <th>Fecha y Hora de Actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listado_usuarios as $usuario)
                                    <tr>
                                        <td>{{$usuario->name}}</td>
                                        <td>{{$usuario->email}}</td>
                                        <td>{{$usuario->tipo_contrato}}</td>
                                        <td>{{$usuario->empresa}}</td>
                                        <td>{{$usuario->created_at}}</td>
                                        <td>{{$usuario->updated_at}}</td>
                                        <td>
                                            <form action="{{route("EditarUsuario")}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_usuario" value="{{$usuario->id}}">
                                                <button class="btn btn-xs btn-default text-primary" title="Editar" type="submit">
                                                    <i class="fa fa-lg fa-pen"></i>
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
            $('#listado_usuarios').DataTable({
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