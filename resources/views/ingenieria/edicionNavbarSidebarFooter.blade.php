@extends('adminlte::page')
@section('title', 'Edición Plantilla')
@section('content_header') 
    <div class='row mb-2'>
        <div class='col-sm-6'>
        </div>
    </div>
@stop

@section('content')
 <div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3>Edición de Plantilla: Sidebar - Navbar - Footer</h3>
            </div>
            <form action="{{route('AplicarEdicionPlantilla')}}" method="POST">
                @csrf
                <div class="card-body">
                    @if (session()->get('edicion_realizada'))
                        <div class="alert alert-success mt-2" role="alert">
                            <strong>{{session()->get('edicion_realizada')}}</strong>
                        </div>
                    @endif
                    @if (session()->get('asignacion_rol_failed'))
                        <div class="alert alert-danger mt-2" role="alert">
                            <strong>{{session()->get('asignacion_rol_failed')}}</strong>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="col-form-label">Opciones de Modificación Plantilla</label>
                                <select class="custom-select" name="opciones_edicion" id="opciones_edicion" required>
                                    <option value="ninguna" selected>Seleccione</option>
                                    <option value="predeterminada">Plantilla predeterminada</option>
                                    <option value="nuevos_estilos">Estilos Personalizados</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 d-none" id="contenedor_plantilla_predeterminado">
                            <div class="form-group">
                                <label class="col-form-label">Listado de Plantillas predeterminados</label>
                                <select class="custom-select" name="plantilla_predeterminada" id="plantilla_predeterminada">
                                    <option value="ninguna_predeterminada" selected>Seleccione</option>
                                    <option value="plantilla_oscura">Oscura</option>
                                    <option value="plantilla_gris">Gris (Sin Opciones Resaltadas)</option>
                                    <option value="plantilla_botones">Gris (Con Opciones Resaltadas)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 d-none" id="contenedor_estilos_nuevos">
                            <h3 class="text-info">Sidebar</h3>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Color Fondo Sidebar</label>
                                        <input class="form-control" type="color" name="background_sidebar" id="background_sidebar">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Color del Menú Padre sin seleccionar </label>
                                        <input class="form-control" type="color" name="color_menu_padre_sin_seleccionar_sidebar" id="color_menu_padre_sin_seleccionar_sidebar">
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Color del Menú Padre que tengan sub menus cuando es seleccionado</label>
                                        <input class="form-control" type="color" name="color_menu_padre_submenu_seleccionado_sidebar" id="color_menu_padre_submenu_seleccionado_sidebar">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Color Menú Padre cuando se deja de hacer focus en él</label>
                                        <input class="form-control" type="color" name="color_menu_padre_sin_focus_sidebar" id="color_menu_padre_sin_focus_sidebar">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Color de los Sub Menús</label>
                                        <input class="form-control" type="color" name="color_sub_menus_sidebar" id="color_sub_menus_sidebar">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Color del hover los Sub Menús</label>
                                        <input class="form-control" type="color" name="color_hover_sub_menus_sidebar" id="color_hover_sub_menus_sidebar">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Color del Sub Menús cuando es seleccionado</label>
                                        <input class="form-control" type="color" name="color_sub_menu_seleccionado_sidebar" id="color_sub_menu_seleccionado_sidebar">
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-info">Navbar</h3>
                            <div class="row">
                                <div class="col-4">
                                    <label class="col-form-label">Fondo Navbar</label>
                                    <input class="form-control" type="color" name="background_navbar" id="background_navbar">
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Botón Apertura/Cierre sidebar</label>
                                        <input class="form-control" type="color" name="color_boton_apertura_cierre_sidebar" id="color_boton_apertura_cierre_sidebar">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Color Nombre Usuario</label>
                                        <input class="form-control" type="color" name="color_nombre_usuario" id="color_nombre_usuario">
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-info">Footer</h3>
                            <div class="row">
                                <div class="col-6">
                                    <label class="col-form-label">Color fondo Footer</label>
                                    <input class="form-control" type="color" name="background_footer" id="background_footer">
                                </div>
                                <div class="col-6">
                                    <label class="col-form-label">Color texto Footer</label>
                                    <input class="form-control" type="color" name="color_texto_footer" id="color_texto_footer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-outline-success" value="Aplicar Estilos">
                </div>
            </form>
        </div>
    </div>
 </div>
@stop

@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            /* OCULTAR EL CONTENEDOR DE PLANTILLA PREDETERMINADA */
            $('#opciones_edicion').change(function(){
                let opcion = $('#opciones_edicion').val();
                if (opcion === "predeterminada") {
                    $('#contenedor_plantilla_predeterminado').removeClass('d-none');
                    $('#contenedor_estilos_nuevos').addClass('d-none');
                } else {
                    $('#contenedor_estilos_nuevos').removeClass('d-none');
                    $('#contenedor_plantilla_predeterminado').addClass('d-none');
                }
            });
        });
    </script>
@stop