$(document).ready(function(){

    /* CONTENEDOR DE SUBCARPETA OCULTO INICIALMENTE */
    $('#contenedor_subcarpeta').css('display','none');

    // SI SE SELECCIONA EL CHECKBOX DE CONFIRMACIÓN DE SUBCARPETA HABILITARÁ EL DIV
    $('#si_crear_subcarpeta').click(function(){
        
        if( $('#si_crear_subcarpeta').is(':checked') ) {
            $('#contenedor_subcarpeta').css('display','flex');
        }else{
            $('#contenedor_subcarpeta').css('display','none');
        }
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE CARPETAS DE LAS VISTAS */
    $(".selector_nombre_carpeta").select2({
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE SUBCARPETAS DE LAS CARPETAS DE LAS VISTAS */
    $(".selector_nombre_subcarpeta").select2({
        placeholder: "Seleccione",
        allowClear: false
    });

    /* CARGA LISTADO DE LISTADO DE CARPETAS DE LAS VISTAS */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listarCarpetasVistas',
        data: datos,
        success:function(data) {
            $('#selector_nombre_carpeta').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#selector_nombre_carpeta').append('<option value="'+data[claves[i]]["carpeta"]+'">'+data[claves[i]]["carpeta"]+'</option>');
            }
        }
    });

    /* CARGA LISTADO DE LISTADO DE SUB CARPETAS DE LAS CARPETAS DE LAS VISTAS */
    $('#selector_nombre_carpeta').change(function(){
        var nombre_carpeta = $('#selector_nombre_carpeta').val();
        
        var datos = {
            'nombre_carpeta': nombre_carpeta,
            '_token': $('input[name=_token]').val()
        };
        $.ajax({
            type:'POST',
            url:'/listarSubCarpetasCarpetasVistas',
            data: datos,
            success:function(data) {
                $('#selector_nombre_subcarpeta').prop('disabled', false);
                $('#selector_nombre_subcarpeta').empty();
                $('#selector_nombre_subcarpeta').append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    if (data[claves[i]]["subcarpeta"] == null) {
                        $('.borrame').remove();
                        $('#selector_nombre_subcarpeta').prop("disabled", true);
                        $('#padre').append('<strong class="text-danger text-sm borrame" role="alert">No se encontraron sub carpetas.</strong>')
                    }else{
                        $('.borrame').remove();
                        $('#selector_nombre_subcarpeta').css('border', '1px solid red');
                        $('#selector_nombre_subcarpeta').append('<option value="'+data[claves[i]]["subcarpeta"]+'">'+data[claves[i]]["subcarpeta"]+'</option>');
                    }
                }
            }
        });
    });


    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ROLES */
    $(".listado_roles_para_vistas").select2({
        placeholder: "Listado Roles",
        allowClear: false
    });

    /* CARGA DE TODOS LOS ROLES */
    $.ajax({
        type:'POST',
        url:'/listatodosroles',
        data: datos,
        success:function(data) {
            $('#listado_roles_para_vistas').empty();
            $('#listado_roles_para_vistas').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_roles_para_vistas').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre_rol"]+'</option>');
            }
        }
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE VISTAS PARA ASIGNAR */
    $(".listado_vistas_asignar").select2({
        placeholder: "Seleccione",
        allowClear: false
    });

    /* CARGA LISTADO DE LISTADO DE CARPETAS DE LAS VISTAS */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listarCarpetasYSubCarpetasVistas',
        data: datos,
        success:function(data) {
            $('#listado_vistas_asignar').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["subcarpeta"] == null) {
                    $('#listado_vistas_asignar').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["carpeta"]+'/'+data[claves[i]]["archivo"]+'</option>');
                }else{
                    $('#listado_vistas_asignar').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["carpeta"]+'/'+data[claves[i]]["subcarpeta"]+'/'+data[claves[i]]["archivo"]+'</option>');
                }
            }
        }
    });



    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE ROLES PARA CONSULTAR ASIGNACIÓN */
    $(".listado_roles_asignacion").select2({
        placeholder: "Listado Roles",
        allowClear: false
    });

    /* CARGA DE TODOS LOS ROLES PARA CONSULTAR ASIGNACIÓN */
    $.ajax({
        type:'POST',
        url:'/listatodosroles',
        data: datos,
        success:function(data) {
            $('#listado_roles_asignacion').empty();
            $('#listado_roles_asignacion').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#listado_roles_asignacion').append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre_rol"]+'</option>');
            }
        }
    });


});