@extends('adminlte::page')
@section('title', $user->rol_usuario)
@section('content_header')
    <h2>Rol: @php echo $user->rol_usuario; @endphp</h2>
    <h3>Página de Inicio de: @php echo $user->name; @endphp </h3>
@stop
@section('content')
    <p>CONTENIDO PÁGINA</p>

    @php
        echo "<pre>";
            // print_r($user);
            // print_r(session()->get('password_hash_web'));
        echo "</pre>";
         
    @endphp
    <div class="card-info">
        <div class="card-header">asdasd</div>
    </div>
@stop

