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
        <div class="card-header">{{$user->rol_usuario}}</div>
    </div>
@stop

@section('js')
 {{-- AQUI PARA LLAMAR EL ARCHIVO JS SI ES NECESARIO --}}
@stop