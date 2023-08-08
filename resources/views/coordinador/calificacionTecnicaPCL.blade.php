@extends('adminlte::page')
@section('title', 'Calificación Ténica PCL')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-8">
            <div>
                <a href="{{route("bandejaPCL")}}" class="btn btn-success" type="button"><i class="fa fa-arrow-left"></i> Regresar</a>
                <p>
                    <h5>Los campos marcados con <span style="color:red;">(*)</span> son Obligatorios</h5>
                </p>
            </div>
        </div>
    </div>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Calificación PCL - Evento: FALTA PONER EL ID DE EVENTO</h4>
            <h5 style="font-style: italic;">Calificación Técnica</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card-info">
                        <a href="#" id="" class="text-dark text-md apertura_modal" label="Open Modal" data-toggle="modal" data-target="#modal_grilla_ojos"><i class="fas fa-plus-circle text-info"></i> <strong>Agudeza Visual</strong></a>
                        <div class="card-header mt-2" style="border: 1.5px solid black;">
                            <h5>Tabla 11.3 Deficiencias por Alteraciones del Sistema Visual</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- MODAL CALCULO DE DEFICIENCIA VISUAL --}}
    @include('coordinador.campimetriaPCL')
 @stop
 

@section('js')
<script type="text/javascript" src="/js/campimetria.js"></script>
@stop