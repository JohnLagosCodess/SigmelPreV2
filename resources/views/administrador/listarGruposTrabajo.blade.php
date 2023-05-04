@extends('adminlte::page')
@section('title', 'Crear Grupos de Trabajo')
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
                {{-- <i class="far fa-eye text-success"></i> Activar Grupo &nbsp; --}}
                {{-- <i class="far fa-eye-slash text-danger"></i> Inactivar Grupo &nbsp; --}}
                <i class="fa fa-sm fa-pen text-primary"></i> Editar Grupo &nbsp;
            </p>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h3>Listado de Grupos de Trabajo</h3>
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
                <div class="table-responsive">
                    <table id="listado_grupos_trabajo" class="table table-striped">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Fecha de Creación</th>
                                <th>Fecha de Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listado_grupos_trabajo as $grupo_editar)
                                <tr>
                                    <td>{{$grupo_editar->id}}</td>
                                    <td>{{$grupo_editar->nombre}}</td>
                                    <td>{{$grupo_editar->estado}}</td>
                                    <td>{{$grupo_editar->created_at}}</td>
                                    <td>{{$grupo_editar->updated_at}}</td>
                                    <td>
                                        <form action="{{route('EditarGrupoTrabajo')}}" method="POST" style="display:inline-block">
                                            @csrf
                                            <div style="display:none;">
                                                <input type="text" name="id" value="{{$grupo_editar->id}}">
                                                <input type="text" name="lider" value="{{$grupo_editar->lider}}">
                                            </div>
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
            $('#listado_grupos_trabajo').DataTable({
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