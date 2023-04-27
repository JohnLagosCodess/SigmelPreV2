$(document).ready(function(){

    // CONTENEDOR DE SUBCARPETA OCULTO INICIALMENTE
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
                        $('#selector_nombre_subcarpeta').prop('disabled', true);
                    }else{
                        $('#selector_nombre_subcarpeta').append('<option value="'+data[claves[i]]["subcarpeta"]+'">'+data[claves[i]]["subcarpeta"]+'</option>');
                    }
                }
            }
        });
    });

});