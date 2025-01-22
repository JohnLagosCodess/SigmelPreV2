@extends('adminlte::page')
@section('title', $user->rol_usuario)
@section('content_header')
<div class='row mb-2'>
    <div class='col-sm-6'>
    </div>
</div>
@stop
@section('content')
    <div class="card-info">
        <div class="card-header">ADMIN</div>
    </div>
@stop
@if(session('info_cliente'))
    <script>
        sessionStorage.setItem("infoCliente",JSON.stringify(@json(session('info_cliente'))));
    </script>
@endif

