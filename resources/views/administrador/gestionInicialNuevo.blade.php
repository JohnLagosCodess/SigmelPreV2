@extends('adminlte::page')
@section('title', 'Nuevo Evento')

@section('css')
    <link rel="stylesheet" type="text/css" href="/plugins/toatsr/build/toastr.css">
@stop
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
            
        </div>
    </div>

@stop

@section('content')
    <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
    <div class="card-info" style="border: 1px solid black;">
        <div class="card-header text-center">
            <h4>Registrar: Nuevo Evento</h4>
        </div>
        <div class="card-body">
            @if (session()->get('mensaje_confirmacion_nuevo_evento'))
                <div class="alert alert-success mt-2" role="alert">
                    <strong>{{session()->get('mensaje_confirmacion_nuevo_evento')}}</strong>
                </div>
                <div class="d-none">
                    <input type="text" id="id_evento_registrado" value="{{ session('id_evento_registrado') }}">
                    <input type="text" id="id_servicio_registrado" value="{{ session('id_servicio_registrado') }}">
                </div>
                <div id="div_mensaje_carga_docs">
                    <b>¿Desea Cargar los documentos?</b> &nbsp; <button class="btn btn-sm btn-success" id="abrir_modal_doc_mod_nuevo" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos">Si</button> 
                    &nbsp; <button class="btn btn-sm btn-danger" id="cerrar_modal_doc_mod_nuevo">No</button>
                </div>
            @endif
            @if (session()->get('confirmacion_evento_no_creado'))
                <div class="alert alert-danger mt-2" role="alert">
                    <strong>{{session()->get('confirmacion_evento_no_creado')}}</strong>
                </div>
            @endif
        </div>
        <form action="{{route('creacionEvento')}}" method="POST" id="gestion_inicial_nuevo">
            @csrf
            <div class="card-body">
                <div class="row">
                    {{-- AQUI VA EL FORMULARIO COMPLETO --}}
                    <div class="col-12">
                        {{-- <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning mensaje_confirmacion_cargar_evento" role="alert">
                                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Debe cargar los documentos obligatorios para crear el evento.
                                </div>
                            </div>
                        </div> --}}
                        {{-- CLIENTE Y TIPO DE CLIENTE --}}
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="cliente" class="col-form-label">Cliente <span style="color:red;">(*)</span></label>
                                    <select class="cliente custom-select" name="cliente" id="cliente" required="true"></select>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="tipo_cliente" class="col-form-label">Tipo de Cliente <span style="color:red;">(*)</span></label>
                                    {{-- <select class="tipo_cliente custom-select" name="tipo_cliente" id="tipo_cliente" required></select> --}}
                                    <input type="hidden" name="tipo_cliente" id="tipo_cliente">
                                    <input type="text" class="form-control" id="nombre_tipo_cliente" readonly>
                                </div>
                            </div>
                            <div class="col-sm columna_otro_tipo_cliente">
                                <div class="form-group">
                                    <label for="otro_tipo_cliente" class="col-form-label">Otro Tipo Cliente <span style="color:red;">(*)</span></label>
                                    <input class="otro_tipo_cliente form-control" name="otro_tipo_cliente" id="otro_tipo_cliente">
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN DEL EVENTO --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información del evento</h5>
                                        <input type="hidden" id="action_evento" value="{{route("gestionInicialEdicion")}}">
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="tipo_evento" class="col-form-label">Tipo de evento <!--<span style="color:red;">(*)</span> --></label>
                                                    <select class="tipo_evento custom-select" name="tipo_evento" id="tipo_evento"></select>
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="id_evento" class="col-form-label">ID evento <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="id_evento form-control" name="id_evento" id="id_evento" required>
                                                </div>
                                            </div> --}}
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="fecha_evento" class="col-form-label">Fecha de evento <!-- <span style="color:red;">(*)</span> --></label>
                                                    <input type="date" class="fecha_evento form-control" name="fecha_evento" id="fecha_evento" max="{{date("Y-m-d")}}">
                                                    <span class="d-none" id="fecha_evento_alerta" style="color: red; font-style: italic;"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="fecha_radicacion" class="col-form-label">Fecha de radicación <span style="color:red;">(*)</span></label>
                                                    <input type="date" class="fecha_radicacion form-control" name="fecha_radicacion" id="fecha_radicacion" max="{{date("Y-m-d")}}" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="n_siniestro" class="col-form-label">N° de Siniestro </label>
                                                    <input type="text" class="n_siniestro form-control" name="n_siniestro" id="n_siniestro" maxlength="25">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mostrar_tabla_eventos d-none">
                                            <div class="alert alert-warning" role="alert">
                                                Este Evento ya se encuentra <b>Registrado!</b> para ver los detalles hacer clic en el botón <b>Editar</b>
                                            </div>
                                            <div class="card-info">
                                                <div class="card-header text-center" style="border: 1.5px solid black;">
                                                    <h5>Id Evento</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Id evento</th>
                                                                    <th>Fecha de evento</th>
                                                                    <th>Fecha de radicación</th>
                                                                    <th>Acción</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="info_Idevento">                                                                                                                            
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN DEL AFILIADO --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información del Afiliado / Beneficiario</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nro_identificacion" class="col-form-label">N° de identificación <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="nro_identificacion form-control" name="nro_identificacion" id="nro_identificacion" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="tipo_afiliado" class="col-form-label">Tipo de afiliado<span style="color:red;">(*)</span></label>
                                                    <select class="tipo_afiliado custom-select" name="tipo_afiliado" id="tipo_afiliado" required></select>
                                                </div>
                                            </div>
                                            <div class="col-sm columna_otro_tipo_afiliado d-none">
                                                <div class="form-group">
                                                    <label for="otro_tipo_afiliado" class="col-form-label">Otro Tipo de Afiliado</label>
                                                    <input class="otro_tipo_afiliado form-control" name="otro_tipo_afiliado" id="otro_tipo_afiliado">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="tipo_documento" class="col-form-label">Tipo de documento <span style="color:red;">(*)</span></label>
                                                    <select class="tipo_documento custom-select" name="tipo_documento" id="tipo_documento" required></select>
                                                </div>
                                            </div>
                                            <div class="col-sm otro_documento d-none">
                                                <div class="form-group">
                                                    <label for="otro_nombre_documento" class="col-form-label" style="color:;">Otro Documento</label>
                                                    <input type="text" class="otro_nombre_documento form-control" name="otro_nombre_documento" id="otro_nombre_documento">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nombre_afiliado" class="col-form-label nom_afiliado">Nombre de afiliado <span style="color:red;">(*)</span></label>
                                                    <label for="nombre_afiliado" class="col-form-label nom_beneficiario d-none">Nombre del Beneficiario <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="nombre_afiliado form-control" name="nombre_afiliado" id="nombre_afiliado" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="direccion_info_afiliado" class="col-form-label">Dirección <span style="color:red;">(*)</span></label>
                                                    <input type="text" class="direccion_info_afiliado form-control" name="direccion_info_afiliado" id="direccion_info_afiliado" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-warning no_creacion_evento d-none" role="alert">
                                            La fecha de evento: <strong id="mostrar_f_evento"></strong> ya está asociada a otro Evento. 
                                            Por favor valide la información que desea registrar en el sistema.
                                        </div>
                                        <div class="ocultar_seccion_info_afiliado">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="fecha_nacimiento" class="col-form-label">Fecha de nacimiento <span style="color:red;">(*)</span></label>
                                                        <input type="date" class="fecha_nacimiento form-control" name="fecha_nacimiento" id="fecha_nacimiento" max="{{date("Y-m-d")}}" required>
                                                        <span class="d-none" id="fecha_nacimiento_alerta" style="color: red; font-style: italic;"></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="edad" class="col-form-label">Edad</label>
                                                        <input type="number" class="edad form-control" name="edad" id="edad">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="genero" class="col-form-label">Género</label>
                                                        <select class="genero custom-select" name="genero" id="genero"></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="email_info_afiliado" class="col-form-label">Email <span style="color:red;">(*)</span></label>
                                                        <input type="email" class="email_info_afiliado form-control" list="opciones_email" id="email_info_afiliado" name="email_info_afiliado" placeholder="Selecciona o escribe..." required>
                                                        <datalist id="opciones_email">
                                                            <option value="sin@correo.com">
                                                        </datalist><br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="telefono" class="col-form-label">Teléfono/Celular <span style="color:red;">(*)</span></label>
                                                        <input type="text" class="telefono form-control" name="telefono" id="telefono" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="estado_civil" class="col-form-label">Estado civil</label>
                                                        <select class="estado_civil custom-select" name="estado_civil" id="estado_civil"></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_estado_civil d-none">
                                                    <div class="form-group">
                                                        <label for="otro_estado_civil" class="col-form-label">Otro Estado civil</label>
                                                        <input class="otro_estado_civil form-control" name="otro_estado_civil" id="otro_estado_civil">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="nivel_escolar" class="col-form-label">Nivel escolar</label>
                                                        <select class="nivel_escolar custom-select" name="nivel_escolar" id="nivel_escolar"></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_nivel_escolar d-none">
                                                    <div class="form-group">
                                                        <label for="otro_nivel_escolar" class="col-form-label">Otro Nivel escolar</label>
                                                        <input class="otro_nivel_escolar form-control" name="otro_nivel_escolar" id="otro_nivel_escolar">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="dominancia" class="col-form-label">Dominancia</label>
                                                        <select class="dominancia custom-select" name="dominancia" id="dominancia"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="departamento_info_afiliado" class="col-form-label">Departamento<span style="color:red;">(*)</span></label>
                                                        <select class="departamento_info_afiliado custom-select" name="departamento_info_afiliado" id="departamento_info_afiliado" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_municipio_info_afiliado">
                                                    <div class="form-group">
                                                        <label for="municipio_info_afiliado" class="col-form-label">Ciudad<span style="color:red;">(*)</span></label>
                                                        <select class="municipio_info_afiliado custom-select" name="municipio_info_afiliado" id="municipio_info_afiliado" disabled></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_pais_exterior_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="pais_exterior_info_afiliado" class="col-form-label">País Exterior</label>
                                                        <input type="text" class="pais_exterior_info_afiliado form-control" name="pais_exterior_info_afiliado" id="pais_exterior_info_afiliado">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="ocupacion" class="col-form-label">Ocupación</label>
                                                        <input type="text" class="ocupacion form-control" name="ocupacion" id="ocupacion">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="ibc" class="col-form label">IBC</label>
                                                        <input type="text" class="ibc form-control" name="ibc" id="ibc">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="eps" class="col-form label">EPS<span style="color:red;">(*)</span></label>
                                                        <select class="eps custom-select" name="eps" id="eps" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_eps d-none">
                                                    <div class="form-group">
                                                        <label for="otra_eps" class="col-form label">Otra EPS</label>
                                                        <input type="text" class="otra_eps form-control" name="otra_eps" id="otra_eps">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="afp" class="col-form label">AFP<span style="color:red;">(*)</span></label>
                                                        <select class="afp custom-select" name="afp" id="afp" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_afp d-none">
                                                    <div class="form-group">
                                                        <label for="otra_afp" class="col-form label">Otra AFP</label>
                                                        <input type="text" class="otra_afp form-control" name="otra_afp" id="otra_afp">
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label for="arl_info_afiliado" class="col-form label">ARL<span style="color:red;">(*)</span></label>
                                                        <select class="arl_info_afiliado custom-select" name="arl_info_afiliado" id="arl_info_afiliado" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm columna_otro_arl_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="otra_arl_info_afiliado" class="col-form label">Otra ARL</label>
                                                        <input type="text" class="otra_arl_info_afiliado form-control" name="otra_arl_info_afiliado" id="otra_arl_info_afiliado">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-3 si_no_apoderado">
                                                    <div class="form-group">
                                                        <label for="apoderado" class="col-form-label">Apoderado</label>
                                                        <select class="apoderado custom-select" name="apoderado" id="apoderado"></select>
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_nombre_apoderado d-none">
                                                    <div class="form-group">
                                                        <label for="nombre_apoderado" class="col-form-label">Nombre del apoderado</label>
                                                        <input type="text" class="nombre_apoderado form-control" name="nombre_apoderado" id="nombre_apoderado">
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_identificacion_apoderado d-none">
                                                    <div class="form-group">
                                                        <label for="nro_identificacion_apoderado" class="col-form-label">N° identificación apoderado</label>
                                                        <input type="text" class="nro_identificacion_apoderado form-control" name="nro_identificacion_apoderado" id="nro_identificacion_apoderado">
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="activo" class="col-form-label">Activo <span style="color:red;">(*)</span></label>
                                                        <select class="activo custom-select" name="activo" id="activo" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="medio_notificacion_afiliado" class="col-form-label">Medio de Notificación <span style="color:red;">(*)</span></label>
                                                        <select class="medio_notificacion_afiliado custom-select" name="medio_notificacion_afiliado" id="medio_notificacion_afiliado" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-3 mt-4 text-center">
                                                    <div class="form-group">
                                                        <input class="form-contol scalesR" type="checkbox" id="entidad_conocimiento" name="entidad_conocimiento" value="Si">
                                                        <label for="entidad_conocimiento" class="col-form-label">&nbsp; Entidad de Conocimiento (AFP)</label>
                                                        <div id="div_afp_conocimiento" class="d-none">
                                                            <select class="afp_conocimiento custom-select" name="afp_conocimiento" id="afp_conocimiento"></select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_identificacion_afi_beni d-none">
                                                    <div class="form-group">
                                                        <label for="afi_nro_identificacion" class="col-form-label">N° de identificación afiliado<span style="color:red;">(*)</span></label>
                                                        <input type="text" class="afi_nro_identificacion form-control" name="afi_nro_identificacion" id="afi_nro_identificacion">
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_tipo_documen_afi_beni d-none">
                                                    <div class="form-group">
                                                        <label for="afi_tipo_documento" class="col-form-label">Tipo de documento afiliado<span style="color:red;">(*)</span></label>
                                                        <select class="afi_tipo_documento custom-select" name="afi_tipo_documento" id="afi_tipo_documento"></select>
                                                    </div>
                                                </div>
                                                <div class="col-3 afi_otro_documento d-none">
                                                    <div class="form-group">
                                                        <label for="afi_otro_nombre_documento" class="col-form-label" style="color:;">Otro Documento<span style="color:red;">(*)</span></label>
                                                        <input type="text" class="afi_otro_nombre_documento form-control" name="afi_otro_nombre_documento" id="afi_otro_nombre_documento">
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_nombre_afi_beni d-none">
                                                    <div class="form-group">
                                                        <label for="afi_nombre_afiliado" class="col-form-label">Nombre afiliado<span style="color:red;">(*)</span></label>
                                                        <input type="text" class="afi_nombre_afiliado form-control" name="afi_nombre_afiliado" id="afi_nombre_afiliado">
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_direccion_afi_beni d-none">
                                                    <div class="form-group">
                                                        <label for="afi_direccion_info_afiliado" class="col-form-label">Dirección afiliado<span style="color:red;">(*)</span></label>
                                                        <input type="text" class="afi_direccion_info_afiliado form-control" name="afi_direccion_info_afiliado" id="afi_direccion_info_afiliado" >
                                                    </div>
                                                </div>
                                                <div class="col-3 columna_depar_afi_beni d-none">
                                                    <div class="form-group">
                                                        <label for="afi_departamento_info_afiliado" class="col-form-label">Departamento afiliado<span style="color:red;">(*)</span></label>
                                                        <select class="afi_departamento_info_afiliado custom-select" name="afi_departamento_info_afiliado" id="afi_departamento_info_afiliado"></select>
                                                    </div>
                                                </div>
                                                <div class="col-3 afi_columna_municipio_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="afi_municipio_info_afiliado" class="col-form-label">Ciudad afiliado<span style="color:red;">(*)</span></label>
                                                        <select class="afi_municipio_info_afiliado custom-select" name="afi_municipio_info_afiliado" id="afi_municipio_info_afiliado" disabled></select>
                                                    </div>
                                                </div>
                                                <div class="col-sm afi_columna_pais_exterior_info_afiliado d-none">
                                                    <div class="form-group">
                                                        <label for="afi_pais_exterior_info_afiliado" class="col-form-label">País Exterior</label>
                                                        <input type="text" class="afi_pais_exterior_info_afiliado form-control" name="afi_pais_exterior_info_afiliado" id="afi_pais_exterior_info_afiliado">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN LABORAL --}}
                        <div class="row ocultar_seccion_info_laboral">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información laboral</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-sm">
                                                <div class="form-check custom-control custom-radio">
                                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="empleo_actual" value="Empleado actual" required>
                                                  <label class="form-check-label custom-control-label" for="empleo_actual"><strong>Empleado Actual</strong></label>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-check custom-control custom-radio">
                                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="independiente" value="Independiente" required>
                                                  <label class="form-check-label custom-control-label" for="independiente"><strong>Independiente</strong></label>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-check custom-control custom-radio">
                                                  <input class="form-check-input custom-control-input custom-control-input-info" type="radio" name="tipo_empleo" id="beneficiario" value="Beneficiario" required>
                                                  <label class="form-check-label custom-control-label" for="beneficiario"><strong>Beneficiario</strong></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row1_laboral">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="arl_info_laboral" class="col-form-label">ARL</label>
                                                    <select class="arl_info_laboral custom-select" name="arl_info_laboral" id="arl_info_laboral"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm otro_arl_info_laboral d-none">
                                                <div class="form-group">
                                                    <label for="otra_arl_info_laboral" class="col-form-label">Otra ARL</label>
                                                    <input type="text" class="otra_arl_info_laboral form-control" name="otra_arl_info_laboral" id="otra_arl_info_laboral">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="empresa" class="col-form-label si_nom_empresa">Empresa <span style="color:red;">(*)</span></label>
                                                    <label for="empresa" class="col-form-label no_nom_empresa d-none">Empresa</label>
                                                    <input type="text" class="empresa form-control" name="empresa" id="empresa" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nit_cc" class="col-form-label si_nom_nitcc">NIT / CC <span style="color:red;">(*)</span></label>
                                                    <label for="nit_cc" class="col-form-label no_nom_nitcc d-none">NIT / CC</label>
                                                    <input type="text" class="nit_cc form-control" name="nit_cc" id="nit_cc" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefono_empresa" class="col-form-label si_nom_tel_empresa">Télefono empresa <span style="color:red;">(*)</span></label>
                                                    <label for="telefono_empresa" class="col-form-label no_nom_tel_empresa d-none">Télefono empresa</label>
                                                    <input type="text" class="telefono_empresa form-control" name="telefono_empresa" id="telefono_empresa" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row2_laboral ">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="email_info_laboral" class="col-form-label si_nom_email">Email <span style="color:red;">(*)</span></label>
                                                    <label for="email_info_laboral" class="col-form-label no_nom_email d-none">Email </label>
                                                    <input type="email" class="email_info_laboral form-control" list="opciones_email_laboral" id="email_info_laboral" name="email_info_laboral" placeholder="Selecciona o escribe..." required>
                                                    <datalist id="opciones_email_laboral">
                                                        <option value="sin@correo.com">
                                                    </datalist><br>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="direccion_info_laboral" class="col-form-label si_nom_direccion">Dirección <span style="color:red;">(*)</span></label>
                                                    <label for="direccion_info_laboral" class="col-form-label no_nom_direccion d-none">Dirección</label>
                                                    <input type="text" class="direccion_info_laboral form-control" name="direccion_info_laboral" id="direccion_info_laboral" required>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="departamento_info_laboral" class="col-form-label si_nom_departamento">Departamento <span style="color:red;">(*)</span></label>
                                                    <label for="departamento_info_laboral" class="col-form-label no_nom_departamento d-none">Departamento</label>
                                                    <select class="departamento_info_laboral custom-select" name="departamento_info_laboral" id="departamento_info_laboral" required></select>
                                                </div>
                                            </div>
                                            <div class="col-sm columna_municipio_info_laboral ">
                                                <div class="form-group">
                                                    <label for="municipio_info_laboral" class="col-form-label si_nom_ciudad">Ciudad <span style="color:red;">(*)</span></label>
                                                    <label for="municipio_info_laboral" class="col-form-label no_nom_ciudad d-none">Ciudad</label>
                                                    <select class="municipio_info_laboral custom-select" name="municipio_info_laboral" id="municipio_info_laboral" disabled required></select>
                                                </div>
                                            </div>
                                            <div class="col-sm columna_pais_exterior_info_laboral d-none">
                                                <div class="form-group">
                                                    <label for="pais_exterior_info_laboral" class="col-form-label">País Exterior</label>
                                                    <input type="text" class="pais_exterior_info_laboral form-control" name="pais_exterior_info_laboral" id="pais_exterior_info_laboral">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row3_laboral ">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="actividad_economica" class="col-form-label">Actividad económica</label>
                                                    <select class="actividad_economica custom-select" name="actividad_economica" id="actividad_economica"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="clase_riesgo" class="col-form-label">Clase / Riesgo</label>
                                                    <select class="clase_riesgo custom-select" name="clase_riesgo" id="clase_riesgo"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="persona_contacto" class="col-form-label">Persona de contacto</label>
                                                    <input type="text" class="persona_contacto form-control" name="persona_contacto" id="persona_contacto">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefono_persona_contacto" class="col-form-label">Teléfono persona contacto</label>
                                                    <input type="text" class="telefono_persona_contacto form-control" name="telefono_persona_contacto" id="telefono_persona_contacto">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row4_laboral ">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="codigo_ciuo" class="col-form-label">Código CIUO</label>
                                                    <select class="codigo_ciuo custom-select" name="codigo_ciuo" id="codigo_ciuo"></select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fecha_ingreso" class="col-form-label">Fecha de ingreso</label>
                                                    <input type="date" class="fecha_ingreso form-control" name="fecha_ingreso" id="fecha_ingreso" max="{{date("Y-m-d")}}">
                                                    <span class="d-none" id="fecha_ingreso_alerta" style="color: red; font-style: italic;"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="cargo" class="col-form-label">Cargo</label>
                                                    <input type="text" class="cargo form-control" name="cargo" id="cargo">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="funciones_cargo" class="col-form-label">Funciones del cargo</label>
                                                    <textarea class="funciones_cargo form-control" name="funciones_cargo" id="funciones_cargo" rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row columna_row5_laboral ">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="antiguedad_empresa" class="col-form-label">Antiguedad en empresa (Meses)</label>
                                                    <input type="number" class="antiguedad_empresa form-control" name="antiguedad_empresa" id="antiguedad_empresa">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="antiguedad_cargo" class="col-form-label">Antiguedad en el cargo (Meses)</label>
                                                    <input type="number" class="antiguedad_cargo form-control" name="antiguedad_cargo" id="antiguedad_cargo">
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fecha_retiro" class="col-form-label">Fecha de retiro</label>
                                                    <input type="date" class="fecha_retiro form-control" name="fecha_retiro" id="fecha_retiro">
                                                    <span class="d-none" id="fecha_retiro_alerta" style="color: red; font-style: italic;"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="medio_notificacion_laboral" class="col-form-label si_medio_noti">Medio de Notificación</label>
                                                    {{-- <label for="medio_notificacion_laboral" class="col-form-label no_medio_noti">Medio de Notificación</label> --}}
                                                    <select class="medio_notificacion_laboral custom-select" name="medio_notificacion_laboral" id="medio_notificacion_laboral"></select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="descripcion" class="col-form-label">Descripción</label>
                                                    <textarea class="descripcion form-control" name="descripcion" id="descripcion" rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN PERICIAL --}}
                        <div class="row ocultar_seccion_info_pericial">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Información Pericial</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="motivo_solicitud" class="col-form label">Motivo solicitud <span style="color:red;">(*)</span></label>
                                                    <select class="motivo_solicitud custom-select" name="motivo_solicitud" id="motivo_solicitud" required></select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="tipovinculo" class="col-form label">Tipo de vinculación <span style="color:red;">(*)</span></label>
                                                    <select class="tipovinculo custom-select" name="tipovinculo" id="tipovinculo" required></select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="regimen" class="col-form label">Régimen en salud <span style="color:red;">(*)</span></label>
                                                    <select class="regimen custom-select" name="regimen" id="regimen" required></select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="solicitante" class="col-form label">Solicitante <span style="color:red;">(*)</span></label>
                                                    <select class="solicitante custom-select" name="solicitante" id="solicitante" required></select>
                                                </div>
                                            </div>     
                                            <div class="col-4 columna_otro_solicitante d-none">
                                                <div class="form-group">
                                                    <label for="otro_solicitante" class="col-form label">Nombre solicitante</label>
                                                    <input type="text" class="otro_solicitante form-control" name="otro_solicitante" id="otro_solicitante">
                                                </div>
                                            </div>
                                            <div class="col-4 columna_nombre_solicitante">
                                                <div class="form-group">
                                                    <label for="nombre_solicitante" class="col-form label">Nombre de solicitante <span style="color:red;">(*)</span></label>
                                                    <select class="nombre_solicitante custom-select" name="nombre_solicitante" id="nombre_solicitante" requiered disabled></select>
                                                </div>
                                            </div>
                                            {{-- <div class="col-4 columna_otro_nombre_solicitante d-none">
                                                <div class="form-group">
                                                    <label for="otro_nombre_solicitante" class="col-form label">Otro Nombre de solicitante</label>
                                                    <input type="text" class="otro_nombre_solicitante form-control" name="otro_nombre_solicitante" id="otro_nombre_solicitante">
                                                </div>
                                            </div> --}}
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="fuente_informacion" class="col-form label">Fuente de información <span style="color:red;">(*)</span></label>
                                                    <select class="fuente_informacion custom-select" name="fuente_informacion" id="fuente_informacion" required></select>
                                                </div>
                                            </div>
                                            <div class="col-4 columna_otra_fuente_informacion d-none">
                                                <div class="form-group">
                                                    <label for="otra_fuente_informacion" class="col-form label">Otra Fuente de información</label>
                                                    <input type="text" class="otra_fuente_informacion form-control" name="otra_fuente_informacion" id="otra_fuente_informacion">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- OPCIONES PARA HABILITAR EL MODAL DE DOCUMENTOS --}}
                                        {{-- <div class="row">
                                            <div class="col-6">
                                                <a href="javascript:void(0);" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalListaDocumentos"><i class="far fa-file text-info"></i> <strong>Cargue Documentos</strong></a>
                                            </div>
                                        </div> --}}
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- INFORMACIÓN ASIGNACION --}}
                        <div class="row ocultar_seccion_info_asignacion">
                            <div class="col-12">
                                <div class="card-info">
                                    <div class="card-header text-center" style="border: 1.5px solid black;">
                                        <h5>Asignación</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="proceso" class="col-form label">Proceso <span style="color:red;">(*)</span></label>
                                                    <select class="proceso custom-select" name="proceso" id="proceso" required></select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="servicio" class="col-form label">Servicio <span style="color:red;">(*)</span></label>
                                                    <select class="servicio custom-select" name="servicio" id="servicio" required disabled></select>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="accion" class="col-form label">Acción <span style="color:red;">(*)</span></label>
                                                    <select class="accion custom-select" name="accion" id="accion" required></select>
                                                </div>
                                            </div> 
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="fecha_alerta" class="col-form label">Fecha Alerta</label>
                                                    <input type="date" class="fecha_alerta form-control" name="fecha_alerta" id="fecha_alerta" min="{{date("Y-m-d")}}">
                                                </div>
                                            </div>                                                                                      
                                        </div>
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-group">
                                                    <label for="profesional" class="col-form label">Profesional</label>
                                                    <select class="profesional custom-select" name="profesional" id="profesional" disabled></select>
                                                </div>
                                            </div> 
                                        </div>  
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="descripcion_asignacion" class="col-form label">Descripción</label>                                            
                                                <textarea class="form-control" name="descripcion_asignacion" id="descripcion_asignacion" rows="2"></textarea>
                                            </div> 
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="alert alert-danger no_ejecutar_parametrica_mod_nuevo d-none" role="alert">
                    <i class="fas fa-info-circle"></i> <strong>Importante:</strong> No puede crear el evento debido a que el proceso, servicio y acción seleccionados no tienen una parametrización
                    asociada. Debe configurar una.
                </div>

                <div class="grupo_botones" style="float: left;">
                    {{-- <input type="reset" id="btn_borrar" class="btn btn-info" value="Restablecer"> --}}
                    <input type="submit" id="btn_guardar_evento" class="btn btn-info d-none" value="Guardar">
                    {{-- <input type="submit" id="btn_guardar_evento" class="btn btn-info d-none" value="Guardar" onclick="OcultarbotonGuardar()"> --}}
                </div>
                <div class="text-center" id="mostrar_barra_creacion_evento"  style="display:none;">                                
                    <button class="btn btn-info" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Creando Evento por favor espere...
                    </button>
                </div>
                <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
                    <i class="fas fa-chevron-up"></i>
                </a>
            </div>
        </form>
        {{-- MODAL PARA MOSTRAR EL LISTADO DE DOCUMETNOS --}}
        <?php $aperturaModal = 'Nuevo'; ?>
        @include('administrador.modalcarguedocumentos')
        @include('//.administrador.modalProgressbar')
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
<script src="/plugins/toatsr/build/toastr.min.js"></script>
<script src="/js/selectores_gestion_inicial.js"></script>
<script src="/js/funciones_helpers.js?v=1.0.0"></script>
<script>
    $(document).ready(function(){
        $('#gestion_inicial_nuevo').submit(function(e){
            $('#btn_borrar,#btn_guardar_evento').addClass('d-none');
            $('#mostrar_barra_creacion_evento').css("display","block");
            var si_cliente = $('#cliente').val();
            var si_tipo_cliente = $('#tipo_cliente').val();            
            var si_id_evento= $('#id_evento').val();            
            var si_fecha_radicacion = $('#fecha_radicacion').val();
            var si_nro_identificacion = $('#nro_identificacion').val();
            var si_tipo_afiliado = $('#tipo_afiliado').val();
            var si_tipo_documento = $('#tipo_documento').val();
            var si_nombre_afiliado = $('#nombre_afiliado').val();
            var si_direccion_info_afiliado = $('#direccion_info_afiliado').val();
            var si_fecha_nacimiento = $('#fecha_nacimiento').val();
            var si_departamento_info_afiliado = $('#departamento_info_afiliado').val();
            var si_municipio_info_afiliado = $('#municipio_info_afiliado').val();
            var si_eps = $('#eps').val();
            var si_afp = $('#afp').val();
            var si_arl_info_afiliado = $('#arl_info_afiliado').val();
            var si_activo = $('#activo').val();
            var si_medio_notificacion_afiliado = $('#medio_notificacion_afiliado').val();
            var si_proceso = $('#proceso').val();
            var si_servicio = $('#servicio').val();
            var si_accion = $('#accion').val();

            if (si_cliente != '' && si_tipo_cliente != '' && si_id_evento != '' &&
            si_fecha_radicacion != '' && si_nro_identificacion != '' && si_tipo_afiliado != '' && si_tipo_documento != '' && si_nombre_afiliado != '' &&
            si_direccion_info_afiliado != '' && si_fecha_nacimiento != '' && si_departamento_info_afiliado != '' && si_municipio_info_afiliado != '' 
            && si_eps != '' && si_afp != '' && si_arl_info_afiliado != '' && si_activo != '' && si_medio_notificacion_afiliado != '' 
            && si_proceso != '' && si_servicio != '' && si_accion != '') {
                /*if(si_cliente != '' && si_tipo_cliente != '' && si_tipo_evento !='' && si_id_evento != ''  &&
                si_fecha_radicacion != '' && si_nro_identificacion != '' && si_tipo_documento != '' && si_nombre_afiliado != ''){   */

            }
            return true;
        });
    });

    // function OcultarbotonGuardar(){

    //     var si_cliente = $('#cliente').val();
    //     var si_tipo_cliente = $('#tipo_cliente').val();
    //     var si_tipo_evento = $('#tipo_evento').val();
    //     var si_id_evento= $('#id_evento').val();
    //     var si_fecha_evento = $('#fecha_evento').val();
    //     var si_fecha_radicacion = $('#fecha_radicacion').val();
    //     var si_nro_identificacion = $('#nro_identificacion').val();
    //     var si_tipo_documento = $('#tipo_documento').val();
    //     var si_nombre_afiliado = $('#nombre_afiliado').val();
    //     var si_direccion_info_afiliado = $('#direccion_info_afiliado').val();
    //     var si_fecha_nacimiento = $('#fecha_nacimiento').val();
    //     var si_telefono = $('#telefono').val();
    //     var si_activo = $('#activo').val();
    //     var si_empresa = $('#empresa').val();
    //     var si_nit_cc = $('#nit_cc').val();
    //     var si_proceso = $('#proceso').val();
    //     var si_servicio = $('#servicio').val();
    //     var si_accion = $('#accion').val();

    //     if (si_cliente != '' && si_tipo_cliente != '' && si_tipo_evento !='' && si_id_evento != '' si_fecha_evento != '' &&
    //     si_fecha_radicacion != '' && si_nro_identificacion != '' && si_tipo_documento != '' && si_nombre_afiliado != '' &&
    //     si_direccion_info_afiliado != '' && si_fecha_nacimiento != '' && si_telefono != '' && si_activo != '' && 
    //     si_empresa != '' && si_nit_cc != '' si_proceso != '' si_servicio != '' si_accion) {
            
    //         $('#btn_borrar').addClass('d-none');
    //         $('#btn_guardar_evento').addClass('d-none');
    //         $('#mostrar_barra_creacion_evento').css("display","block");
    //     }
    // }
</script> 
<script>
    $(document).ready(function(){
        $('#listado_documentos1').DataTable({
            "paging": false,
        });

        $('#btn_borrar').click(function(){
            location.reload();
        });
    })
</script>
<script>
    // Validación inicial si el input de fecha está vacío
    // $("#fecha_radicacion").on("focus", function() {
    //     if(!$("#fecha_evento").val()){
    //         Validarfecha("#fecha_radicacion");
    //     }else{
    //         let fechaEvento = $("#fecha_evento").val();
    //         Validarfecha("#fecha_radicacion","<",fechaEvento,"La fecha debe ser igual o mayor al evento");
    //     }
    // });

    // $("#fecha_evento").on("focus", function() {
    //             var fechaEvento = $(this).val();
    //             $("#fecha_radicacion").val('').attr("min", fechaEvento);
    // });

    /* Nueva validación para la fecha de Radicación */
    $(document).on('keyup change click', '#fecha_radicacion',function(event){
        
        var fechaEvento = $("#fecha_evento").val();
        var tipo_handler = event.type;
        /* 
            CASO 1: Si el evento es de tipo click entonces modifica el atributo min de la f de radicación
            para no permitir dejar escoger fechas anteriores a la fecha de evento seleccionada.
        */
       /*
            CASO 2: Si los eventos son keyup y change entonces se valida que la fecha de radicación
            no debe ser inferior a la fecha del evento, solo puede ser superior o igual.
        */
        switch (tipo_handler) {
            case 'click':
                $(this).attr("min", fechaEvento);
            break;
            
            case 'keyup':
            case 'change':
                if ($(this).val() < fechaEvento) {
                    // Eliminar cualquier alerta previa
                    if ($(this).next('i').length) {
                        $(this).next('i').remove();
                    }
                    let alerta = '<i style="color:red;">La fecha debe ser igual o mayor a la fecha evento: '+fechaEvento+'</i>';
                    $(this).after(alerta);
                }else{
                    if ($(this).next('i').length) {
                        $(this).next('i').remove();
                    }
                }
            break;

            default:
            break;
        }
    });

    $(document).on('keyup change', '#fecha_alerta', function(event){

        var fechaActual = new Date().toISOString().slice(0,10);

        var tipo_handler = event.type;
        if (tipo_handler == 'keyup' || tipo_handler == 'change') {
            if ($(this).val() < fechaActual) {
                // Eliminar cualquier alerta previa
                if ($(this).next('i').length) {
                    $(this).next('i').remove();
                }
                let alerta = '<i style="color:red;">La fecha debe ser igual o mayor a: '+fechaActual+'</i>';
                $(this).after(alerta);
            }else{
                if ($(this).next('i').length) {
                    $(this).next('i').remove();
                }
            }
        }
    });

    $(function() {
        $("#fecha_ingreso").on("change", function() {
            var fechaEvento = $(this).val();
            $("#fecha_retiro").val('').attr("min", fechaEvento);
        });
    });
</script>
{{-- Validación general para todos los campos de tipo fecha --}}
<script>
    let today = new Date().toISOString().split("T")[0];

    // Seleccionar todos los inputs de tipo date
    const dateInputs = document.querySelectorAll('input[type="date"]');

    // Agregar evento de escucha a cada input de tipo date que haya
    dateInputs.forEach(input => {
        //Usamos el evento change para detectar los cambios de cada uno de los inputs de tipo fecha
        input.addEventListener('change', function() {
            //Validamos que la fecha sea mayor a la fecha de 1900-01-01
            if(this.value < '1900-01-01'){
                $(`#${this.id}_alerta`).text("La fecha ingresada no es válida. Por favor valide la fecha ingresada").removeClass("d-none");
                $('#Edicion_editar').addClass('d-none');
                return;
            }
            //Validamos que la fecha no sea mayor a la fecha actual
            if(this.value > today){
                $(`#${this.id}_alerta`).text("La fecha ingresada no puede ser mayor a la actual").removeClass("d-none");
                $('#Edicion_editar').addClass('d-none');
                return;
            }
            return $(`#${this.id}_alerta`).text('').addClass("d-none");
        });
    });
</script>

@stop