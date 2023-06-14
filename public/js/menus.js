$(document).ready(function(){
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ROLES */
    $(".listado_roles_para_menus").select2({
        placeholder: "Listado Roles",
        allowClear: false
    });
    
    /* CARGA DE TODOS LOS ROLES */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listatodosroles',
        data: datos,
        success:function(data) {
            $('#listado_roles_para_menus').empty();
            $('#listado_roles_para_menus').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_roles_para_menus').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre_rol"]+'</option>');
            }
        }
    });
    
    /* CONTENEDOR DE VISTAS OCULTO INICIALMENTE */
    $('#contenedor_vistas').css('display','none');
    
    // SI SE SELECCIONA EL CHECKBOX DE CONFIRMACIÓN DE VISTA HABILITARÁ EL DIV
    $('#si_ver_vistas').click(function(){
        if( $('#si_ver_vistas').is(':checked') ) {
            $('#contenedor_vistas').css('display','flex');
            $('.select2').css('width', '1330px');
        }else{
            $('#contenedor_vistas').css('display','none');
        }
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE VISTAS PARA MENUS */
    $(".listado_vistas_para_menus").select2({
        placeholder: "Seleccione",
        allowClear: false
    });
    $.ajax({
        type:'POST',
        url:'/listarCarpetasYSubCarpetasVistas',
        data: datos,
        success:function(data) {
            $('#listado_vistas_para_menus').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["subcarpeta"] == null) {
                    $('#listado_vistas_para_menus').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["carpeta"]+'/'+data[claves[i]]["archivo"]+'</option>');
                }else{
                    $('#listado_vistas_para_menus').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["carpeta"]+'/'+data[claves[i]]["subcarpeta"]+'/'+data[claves[i]]["archivo"]+'</option>');
                }
            }
        }
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE VISTAS PARA SUB MENUS */
    $(".listado_vistas_para_submenus").select2({
        placeholder: "Seleccione",
        allowClear: false
    });
    $.ajax({
        type:'POST',
        url:'/listarCarpetasYSubCarpetasVistas',
        data: datos,
        success:function(data) {
            $('#listado_vistas_para_submenus').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["subcarpeta"] == null) {
                    $('#listado_vistas_para_submenus').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["carpeta"]+'/'+data[claves[i]]["archivo"]+'</option>');
                }else{
                    $('#listado_vistas_para_submenus').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["carpeta"]+'/'+data[claves[i]]["subcarpeta"]+'/'+data[claves[i]]["archivo"]+'</option>');
                }
            }
        }
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE MENU PADRES */
    $(".listado_padres_menu").select2({
        placeholder: "Listado Menú Padres Acorde al Rol",
        allowClear: false
    });
    
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listamenupadres',
        data: datos,
        success:function(data) {
            $('#listado_padres_menu').empty();
            $('#listado_padres_menu').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_padres_menu').append('<option value="'+data[claves[i]]["id"]+'">Rol ('+data[claves[i]]["nombre_rol"]+'): '+data[claves[i]]["nombre"]+'</option>');
            }
        }
    });


    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ROLES PARA CONSULTAR ASIGNACIÓN */
    $(".listado_roles_consultar").select2({
        placeholder: "Listado Roles",
        allowClear: false
    });

    /* CARGA DE TODOS LOS ROLES PARA CONSULTAR ASIGNACIÓN */
    $.ajax({
        type:'POST',
        url:'/listatodosroles',
        data: datos,
        success:function(data) {
            $('#listado_roles_consultar').empty();
            $('#listado_roles_consultar').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_roles_consultar').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre_rol"]+'</option>');
            }
        }
    });


});