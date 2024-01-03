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
            <h3>GENERAR PDF</h3>
            <form action="{{route('generarPDF')}}" method="POST">
                @csrf
                <input type="submit" class="btn btn-outline-danger" value="GENERAR">
            </form><br>
            <!-- Muestra el código QR generado -->
            {!! $codigoQR !!}
            <br>
        </div>
        <div class="col-12">
            <br>
            <h3>GENERAR EXCEL CON PHPSPREADSHEET</h3>
            {{-- <form action="{{route('generarExcel')}}" method="POST">
                @csrf
                <input type="submit" class="btn btn-outline-warning" value="GENERAR">
            </form><br> --}}
        </div>

        <div class="col-12" style="border: 3px solid purple;">
            <h3>EJEMPLO N°1 CON LARAVEL EXCEL</h3>
            <h3>DESCARGAR ARCHIVO (.xlsx)</h3>
            <div class="row">
                <div class="col-6">
                    <form action="{{route('ExportarArchivo')}}" method="POST">
                        @csrf
                        <input type="submit" class="btn btn-outline-danger" value="Descargar Archivo">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 mt-2" style="border: 3px solid black;">
            <h3>EJEMPLO N°2 CON LARAVEL EXCEL</h3>
            <h3>IMPORTAR DATOS CON ENCABEZADOS EN FORMATO (.csv)</h3>
            <div class="row">
                <div class="col-6">
                    
                    <form action="{{route('ImportarCsvConEncabezados')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Suba el archivo</label>
                            <input type="file" name="file_csv_con_encabezados" class="form-control" required>
                        </div>
                        <input type="submit" class="btn btn-outline-success" value="Importar Archivo CSV">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-2" style="border: 3px solid blue;">
            <h3>EJEMPLO N°3 CON LARAVEL EXCEL</h3>
            <h3>IMPORTAR DATOS SIN ENCABEZADOS EN FORMATO (.csv)</h3>
            <div class="row">
                <div class="col-6">
                    <form action="{{route('ImportarCsvSinEncabezados')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Suba el archivo</label>
                            <input type="file" name="file_csv_sin_encabezados" class="form-control" required>
                        </div>
                        <input type="submit" class="btn btn-outline-success" value="Importar Archivo CSV">
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-12 mt-2"  style="border: 3px solid orange;">
            <h3>EJEMPLO N°4 CON LARAVEL EXCEL</h3>
            <h3>IMPORTAR DATOS CON ENCABEZADOS EN FORMATO (.xslx)</h3>
            <div class="row">
                <div class="col-6">
                    <form action="{{route('ImportarXlsxConEncabezados')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Suba el archivo</label>
                            <input type="file" name="file_xlsx_con_encabezados" class="form-control" required>
                        </div>
                        <input type="submit" class="btn btn-outline-success" value="Importar Archivo XSLX">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-2"  style="border: 3px solid red;">
            <h3>EJEMPLO N°5 CON LARAVEL EXCEL</h3>
            <h3>IMPORTAR DATOS SIN ENCABEZADOS EN FORMATO (.xslx)</h3>
            <div class="row">
                <div class="col-6">
                    <form action="{{route('ImportarXlsxSinEncabezados')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Suba el archivo</label>
                            <input type="file" name="file_xlsx_sin_encabezados" class="form-control" required>
                        </div>
                        <input type="submit" class="btn btn-outline-success" value="Importar Archivo XSLX">
                    </form>
                </div>
            </div>
        </div>


        
        
    </div>
@stop