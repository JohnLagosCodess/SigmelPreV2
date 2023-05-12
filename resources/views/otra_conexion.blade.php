@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-12 mt-5">
            <table border="1">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>nombre</th>
                        <th>Creado</th>
                        <th>Actualizado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datos_pruebas as $info)
                        <tr>
                            <td>{{ $info->id }}</td>
                            <td>{{ $info->nombre }}</td>
                            <td>{{ $info->created_at }}</td>
                            <td>{{ $info->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <br>
            <form action="{{route('generarExcel')}}" method="POST">
                @csrf
                <input type="submit" class="btn btn-warning" value="GENERAR">
            </form>
        </div>
    </div>
@stop