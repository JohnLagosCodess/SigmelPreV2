@extends('adminlte::page')
@section('title', 'Listar Entidades')
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
                    <i class="fa fa-sm fa-pen text-primary"></i> Editar Entidad &nbsp;
                </p>
            </div>
            <div class="card card-info">
                <div class="card-header">
                    <h3>Listado De Entidades</h3>
                </div>
                <div class="card-body">
                    <!--Validaciones BACKEP-->
                    <label ><span>Entidades: Activos: {{$conteo_activos_inactivos[0]->Activos}}</span> - Inactivos: {{$conteo_activos_inactivos[0]->Inactivos}} </label>
                    <div class="table-responsive">
                        <input type="hidden" id="ruta_ed_identidad" value="{{route('EditarEntidad')}}">
                        <input type="hidden" id="ruta_guardar_ed_identidad" value="{{route('ActualizacionEntidad')}}">
                        <table id="listado_entidades" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr class="bg-info">
                                    <th>Acciones</th>
                                    <th>N°</th>
                                    <th>Tipo de Entidad</th>
                                    <th>Nombre de Entidad</th>
                                    <th>NIT</th>
                                    <th>Teléfóno Principal</th>
                                    <th>Otros Teléfóno(s)</th>
                                    <th>E-mail Principal</th>
                                    <th>Otros E-mail(s)</th>
                                    <th>Dirección</th>
                                    <th>Departamento</th>
                                    <th>Ciudad</th>
                                    <th>Medio de Notificación</th>
                                    <th>Sucursal</th>
                                    <th>Dirigido a</th>
                                    <th>Status</th>
                                    <th>Fecha de creación</th>
                                    <th>Fecha de actualización</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $iterar = 0;?>
                                @foreach ($listado_entidades as $editar_info_entidad)
                                    <tr>
                                        <td><a href="javascript:void(0);" id="btn_modal_edicion_entidad_{{$editar_info_entidad->Id_Entidad}}" data-toggle="modal" data-target="#modalEdicionEntidad_{{$editar_info_entidad->Id_Entidad}}" data-id_editar_entidad="{{$editar_info_entidad->Id_Entidad}}" data-nombre_editar_entidad="{{$editar_info_entidad->Nombre_entidad}}"><i class="fa fa-pen text-primary"></i></a></td>
                                        <td><?php echo $iterar = $iterar + 1; ?></td>
                                        <td>
                                            @if ($editar_info_entidad->Tipo_Entidad == "Otro/¿Cual?")
                                                {{$editar_info_entidad->Otro_entidad}}
                                            @else
                                                {{$editar_info_entidad->Tipo_Entidad}}
                                            @endif
                                        </td>
                                        <td>{{$editar_info_entidad->Nombre_entidad}}</td>
                                        <td>{{$editar_info_entidad->Nit_entidad}}</td>
                                        <td>{{$editar_info_entidad->Telefonos}}</td>
                                        <td>{{"Tel"."-".$editar_info_entidad->Otros_Telefonos}}</td>
                                        <td>{{$editar_info_entidad->Emails}}</td>
                                        <td>
                                            <?php 
                                                $email_c=$editar_info_entidad->Otros_Emails; 
                                                echo wordwrap($email_c, 15, "<br>" ,TRUE);
                                            ?>
                                        </td>
                                        <td>{{$editar_info_entidad->Direccion}}</td>
                                        <td>{{$editar_info_entidad->Nombre_departamento}}</td>
                                        <td>{{$editar_info_entidad->Nombre_municipio}}</td>
                                        <td>{{$editar_info_entidad->Medio_Noti}}</td>
                                        <td>{{$editar_info_entidad->Sucursal}}</td>
                                        <td>{{$editar_info_entidad->Dirigido}}</td>
                                        <td>{{$editar_info_entidad->Estado_entidad}}</td>
                                        <td>{{$editar_info_entidad->F_Registro}}</td>
                                        <td>{{$editar_info_entidad->F_Actuali}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- MODAL EDICION ENTIDAD --}}
            @include('administrador.editarEntidades')
        </div>
    </div>
@stop
@section('js')
    <script src="/js/entidades.js"></script>
    <script src="/js/funciones_helpers.js"></script>
@stop