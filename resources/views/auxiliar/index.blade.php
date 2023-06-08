@extends('adminlte::page')
@section('title', $user->rol_usuario)
@section('content_header')
    <h2>Rol: @php echo $user->rol_usuario; @endphp</h2>
    <h3>Página de Inicio de: @php echo $user->name; @endphp </h3>
@stop
@section('content')
    <p>CONTENIDO PAGINA 1</p>
@stop