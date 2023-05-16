@extends('adminlte::page')
@section('title', 'Edición Plantilla')

@section('css')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
@stop
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
                                    <option value="plantilla_naranja">Naranja</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-none" id="contenedor_estilos_nuevos">
                        <div class="row">
                            <div class="col-4">
                                <label class="col-form-label">¿Cambiar estilos del Sidebar?</label>
                                <input type="checkbox" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="outline-success" data-offstyle="outline-danger" data-size="sm" name="si_no_sidebar" id="si_no_sidebar">
                            </div>
                            <div class="col-4">
                                <label class="col-form-label">¿Cambiar estilos del Navbar?</label>
                                <input type="checkbox" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="outline-success" data-offstyle="outline-danger" data-size="sm" name="si_no_navbar" id="si_no_navbar">
                            </div>
                            <div class="col-4">
                                <label class="col-form-label">¿Cambiar estilos del Footer?</label>
                                <input type="checkbox" data-toggle="toggle" data-on="Si" data-off="No" data-onstyle="outline-success" data-offstyle="outline-danger" data-size="sm" name="si_no_footer" id="si_no_footer">
                            </div>
                        </div>
                        <div class="d-none" id="habilitar_estilos_sidebar">
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
                                        <label class="col-form-label">Color Menús Padre</label>
                                        <input class="form-control" type="color" name="color_menu_padre_sin_seleccionar_sidebar" id="color_menu_padre_sin_seleccionar_sidebar">
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Color Menús Padre que tiene sub menus cuando es seleccionado</label>
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
                        </div>

                        <div class="d-none" id="habilitar_estilos_navbar">
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
                        </div>

                        <div class="d-none" id="habilitar_estilos_footer">
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
                    <input type="submit" class="btn btn-outline-success" id="btn_aplicar_estilos" value="Aplicar Estilos">
                    <div class="d-none" id="confirmacion_accion_aplicar">
                        <span>Aplicando Estilos...  <span class="spinner-border spinner-border-sm text-success" role="status"></span></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
 </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
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

            /* MANDAR MENSAJE DE APLICAR ESTILOS Y OCULTAR EL BOTÓN */
            $('#btn_aplicar_estilos').click(function(){
                $('#btn_aplicar_estilos').prop('disable', true);
                $('#btn_aplicar_estilos').addClass('d-none');
                $('#confirmacion_accion_aplicar').removeClass('d-none');
            });

            /* DETECTAR CHECKBOX DE SI O NO SIDEBAR */
            $('input:checkbox[name=si_no_sidebar]').change(function(){
                let habilito_sidebar = $('input:checkbox[name=si_no_sidebar]:checked').val();

                if (habilito_sidebar == 'on') {
                    $('#habilitar_estilos_sidebar').removeClass('d-none');    
                }else{
                    $('#habilitar_estilos_sidebar').addClass('d-none');    
                }
            });

            /* DETECTAR CHECKBOX DE SI O NO NAVBAR */
            $('input:checkbox[name=si_no_navbar]').change(function(){
                let habilito_navbar = $('input:checkbox[name=si_no_navbar]:checked').val();

                if (habilito_navbar == 'on') {
                    $('#habilitar_estilos_navbar').removeClass('d-none');    
                }else{
                    $('#habilitar_estilos_navbar').addClass('d-none');    
                }
            });

            /* DETECTAR CHECKBOX DE SI O NO FOOTER */
            $('input:checkbox[name=si_no_footer]').change(function(){
                let habilito_footer = $('input:checkbox[name=si_no_footer]:checked').val();

                if (habilito_footer == 'on') {
                    $('#habilitar_estilos_footer').removeClass('d-none');    
                }else{
                    $('#habilitar_estilos_footer').addClass('d-none');    
                }
            });


        });
    </script>
@stop