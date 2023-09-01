@extends('adminlte::page')
@section('title', 'Crear Entidad')

@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <h5>Los campos marcados con <span style="color:red;">(*)</span> son obligatorios.</h5>
            <div class="card card-info">
                <div class="card-header">
                    <h3>Formulario para Creación de Entidad</h3>
                </div>
                <form action="{{route('CrearNuevoEntidad')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if (session()->get('entidad_creado'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('entidad_creado')}}</strong>
                        </div>
                        @endif
                        @if (session()->get('entidad_no_creado'))
                            <div class="alert alert-danger mt-2" role="alert">
                                <strong>{{session()->get('entidad_no_creado')}}</strong>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Tipo de Entidad <span style="color:red;">(*)</span></label>
                                    <select class="tipo_entidad custom-select" name="tipo_entidad" id="tipo_entidad" requierd></select>
                                </div>
                            </div>  
                            <div class="columna_otro_entidad col-3" style="display:none">
                                <div class="form-group">
                                    <label  class="col-form-label">Otra Entidad <span style="color:red;">(*)</span></label>
                                    <input type="text" class="mayus_entidad form-control" name="otra_entidad" id="otra_entidad">
                                </div>
                            </div>  
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Nombre de Entidad <span style="color:red;">(*)</span></label>
                                    <input type="text" class="mayus_entidad form-control" name="nombre_entidad" id="nombre_entidad" required>
                                </div>
                            </div>  
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">NIT<span style="color:red;">(*)</span></label>
                                    <input type="texto" class="form-control" name="nit_entidad" id="nit_entidad" required>
                                </div>
                            </div>  
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Teléfóno Principal<span style="color:red;">(*)</span></label>
                                    <input type="number" class="soloNumeros form-control" name="entidad_telefono" id="entidad_telefono" maxlength="12" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Otros Teléfóno(s)</label>
                                    <input type="text" class="form-control" name="entidad_telefono_otro" id="entidad_telefono_otro">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">E-mail Principal<span style="color:red;">(*)</span></label>
                                    <input type="email" class="form-control" name="entidad_email" id="entidad_email" required>
                                </div>
                            </div> 
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Otros E-mail(s)</label>
                                    <input type="text" class="form-control" name="entidad_email_otro" id="entidad_email_otro">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Dirección<span style="color:red;">(*)</span></label>
                                    <input type="text" class="mayus_entidad form-control" name="entidad_direccion" id="entidad_direccion" required>
                                </div>
                            </div> 
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Departamento<span style="color:red;">(*)</span></label>
                                    <select class="entidad_departamento proceso custom-select" name="entidad_departamento" id="entidad_departamento" requierd></select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Ciudad<span style="color:red;">(*)</span></label>
                                    <select class="entidad_ciudad proceso custom-select" name="entidad_ciudad" id="entidad_ciudad" disabled></select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Medio de Notificación<span style="color:red;">(*)</span></label>
                                    <select class="entidad_medio_noti proceso custom-select" name="entidad_medio_noti" id="entidad_medio_noti" requierd></select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Sucursal<span style="color:red;">(*)</span></label>
                                    <input type="text" class="mayus_entidad form-control" name="entidad_sucursal" id="entidad_sucursal" required>
                                </div>
                            </div> 
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Dirigido a<span style="color:red;">(*)</span></label>
                                    <input type="text" class="mayus_entidad form-control" name="entidad_dirigido" id="entidad_dirigido" required>
                                </div>
                            </div> 
                            <div class="col-3">
                                <div class="form-group">
                                    <label  class="col-form-label">Status<span style="color:red;">(*)</span></label>
                                    <select class="estado_entidad proceso custom-select" name="estado_entidad" id="estado_entidad" requierd>
                                        <option value="activo" selected>Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="grupo_botones" style="float: left;">
                            <input type="submit" id="btn_guardar_entidad" class="btn btn-outline-success" value="Enviar Información">
                        </div>
                        <div class="text-center" id="mostrar_barra_creacion_entidad"  style="display:none;">                                
                            <button class="btn btn-info" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Creando Entidad por favor espere...
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="/js/entidades.js"></script>
    <script src="/js/funciones_helpers.js"></script>
@stop