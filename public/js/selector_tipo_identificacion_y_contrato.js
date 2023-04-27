$(document).ready(function(){
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE IDENTIFICACIÓN */
    $(".tipo_identificacion_usuario").select2({
        placeholder: "Seleccione",
        allowClear: false
    });
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE CONTRATO */
    $(".tipo_contrato_usuario").select2({
        placeholder: "Seleccione",
        allowClear: false
    });

    /* CARGA LISTADO DE TIPOS DE IDENTIFICACIÓN */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listartiposidentificacion',
        data: datos,
        success:function(data) {
            $('#tipo_identificacion_usuario').empty();
            $('#tipo_identificacion_usuario').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_identificacion_usuario').append('<option value="'+data[claves[i]]["tipo_identificacion"]+'">'+data[claves[i]]["tipo_identificacion"]+'</option>');
            }
        }
    });

    /* CARGA LISTADO DE TIPOS DE CONTRATO */
    var datos = {
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listartiposContrato',
        data: datos,
        success:function(data) {
            $('#tipo_contrato_usuario').empty();
            $('#tipo_contrato_usuario').append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#tipo_contrato_usuario').append('<option value="'+data[claves[i]]["tipo_contrato"]+'">'+data[claves[i]]["tipo_contrato"]+'</option>');
            }
        }
    });


    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE CONTRATO */
    $(".editar_tipo_identificacion_usuario").select2();

    /* CARGA LISTADO DE TIPOS DE IDENTIFICACIÓN EDICION USUARIO */
    var datos = {
        'tipo_identificacion': $('#editar_tipo_identificacion_usuario').val(),
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listadoTiposIdentificacionEditar',
        data: datos,
        success:function(data) {
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#editar_tipo_identificacion_usuario').append('<option value="'+data[claves[i]]["tipo_identificacion"]+'">'+data[claves[i]]["tipo_identificacion"]+'</option>');
            }
        }
    });

    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE CONTRATO */
    $(".editar_tipo_contrato_usuario").select2();

    /* CARGA LISTADO DE TIPOS DE IDENTIFICACIÓN EDICION USUARIO */
    var datos = {
        'tipo_contrato': $('#editar_tipo_contrato_usuario').val(),
        '_token': $('input[name=_token]').val()
    };
    $.ajax({
        type:'POST',
        url:'/listadotiposContratoEditar',
        data: datos,
        success:function(data) {
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#editar_tipo_contrato_usuario').append('<option value="'+data[claves[i]]["tipo_contrato"]+'">'+data[claves[i]]["tipo_contrato"]+'</option>');
            }
        }
    });
});