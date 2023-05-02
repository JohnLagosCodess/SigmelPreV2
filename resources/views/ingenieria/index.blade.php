@extends('adminlte::page')
@section('title', $user->rol_usuario)
@section('content_header')
    <h2>Rol: @php echo $user->rol_usuario; @endphp</h2>
@stop
@section('content')
    <div class="row">
        <div class="col-6">
            <a href="{{route('NuevoUsuario')}}" class="btn btn-primary"><i class="fas fa-user"></i> Crear Usuario</a>
            <a href="{{route('ListarUsuarios')}}" class="btn btn-primary"><i class="fas fa-list"></i> Consultar Lista de Usuarios</a>
        </div>
    </div><br>
    <div class="row">
        <div class="col-12">
            <a href="{{route('NuevoRol')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Crear Rol</a>
            <a href="{{route('ListadoRoles')}}" class="btn btn-primary"><i class="fas fa-list"></i> Consultar Lista de Roles</a>
            <a href="{{route('AsignacionRol')}}" class="btn btn-primary"><i class="far fa-address-card"></i> Asignar Roles a Usuarios</a>
            <a href="{{route('ConsultarAsignacionRol')}}" class="btn btn-primary"><i class="fas fa-list"></i> Consultar Asignación de Roles a Usuarios</a>
        </div>
    </div><br>
    <div class="row">
        <div class="col-12">
            <i class="fa-users-viewfinder"></i>
            <a href="{{route('NuevaVista')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Crear Vista Principal</a>
            <a href="{{route('NuevaVistaOtros')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Crear Vistas Secundarias</a>
            <a href="{{route('AsignacionVista')}}" class="btn btn-primary"><i class="far fa-address-card"></i> Asignar Vistas a Roles</a>
            <a href="{{route('ConsultarAsignacionVista')}}" class="btn btn-primary"><i class="fas fa-list"></i> Consultar Asignación de Vistas a Roles</a>
        </div>
    </div>
@stop

