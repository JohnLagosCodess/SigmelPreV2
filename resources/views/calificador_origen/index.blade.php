@extends('adminlte::page')
@section('title', $user->rol_usuario)
@section('content_header')
    <h2>Rol: @php echo $user->rol_usuario; @endphp</h2>
    <h3>Página de Inicio de: @php echo $user->name; @endphp </h3>
@stop
@section('content')
{{-- <a href="{{route("RolPrincipal")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a> --}}
    <p>CONTENIDO PAGINA</p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet officiis sit sequi, eligendi distinctio quidem dolore alias cumque illo nobis ipsam, ab ducimus quos libero. Molestiae quos reprehenderit doloremque voluptatem?</p>
@stop