$(document).ready(function(){

    // Incialización selec2 activo o no
    $(".es_activo").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Inicialización de select2 listado tipo de eventos
    $(".tipo_evento").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Inicialización de select2 listado motivo solicitud
    $(".motivo_solicitud").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    var token = $('input[name=_token]').val();
    
    // validacion de si es activo o no para llenar el selector de tipo de evento
    $("#es_activo").change(function(){
        let opcion_seleccionada = $(this).val();
        
        if (opcion_seleccionada == "Si") {
            let datos_tipo_evento = {
                '_token': token,
                'parametro':"tipo_de_evento_si"
            };
            $.ajax({
                type:'POST',
                url:'/cargueListadoSelectoresDTOATEL',
                data: datos_tipo_evento,
                success:function(data){
                    //console.log(data);
                    let nombre_evento_bd = $('#nombre_evento_bd').val();
                    $('#tipo_evento').prop('disabled', false);
                    $('#tipo_evento').empty();
                    $('#tipo_evento').append('<option value=""></option>');

                    let listado_tipo_evento = Object.keys(data);
                    for (let i = 0; i < listado_tipo_evento.length; i++) {
                        if (data[listado_tipo_evento[i]]['Nombre_evento'] == nombre_evento_bd) {                    
                            $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        }else{
                            $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        }
                    }
                }
            });
        }else{
            let datos_tipo_evento = {
                '_token': token,
                'parametro':"tipo_de_evento_no"
            };
            $.ajax({
                type:'POST',
                url:'/cargueListadoSelectoresDTOATEL',
                data: datos_tipo_evento,
                success:function(data){
                    //console.log(data);
                    let nombre_evento_bd = $('#nombre_evento_bd').val();
                    $('#tipo_evento').prop('disabled', false);
                    $('#tipo_evento').empty();
                    $('#tipo_evento').append('<option value=""></option>');

                    let listado_tipo_evento = Object.keys(data);
                    for (let i = 0; i < listado_tipo_evento.length; i++) {
                        if (data[listado_tipo_evento[i]]['Nombre_evento'] == nombre_evento_bd) {                    
                            $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        }else{
                            $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        }
                    }
                }
            });
        }
    });


    let datos_tipo_evento = {
        '_token': token,
        'parametro':"motivo_solicitud"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_tipo_evento,
        success:function(data){
            //console.log(data);
            let motivo_solicitud_bd = $('#motivo_solicitud_bd').val();
            // if (motivo_solicitud_bd != "") {
                $('#motivo_solicitud').append('<option value=""></option>');
                let listado_tipo_evento = Object.keys(data);
                for (let i = 0; i < listado_tipo_evento.length; i++) {
                    if (data[listado_tipo_evento[i]]['Nombre_solicitud'] == motivo_solicitud_bd) {                    
                        $('#motivo_solicitud').append('<option value="'+data[listado_tipo_evento[i]]['Id_Solicitud']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_solicitud']+'</option>');
                    }else{
                        $('#motivo_solicitud').append('<option value="'+data[listado_tipo_evento[i]]['Id_Solicitud']+'">'+data[listado_tipo_evento[i]]['Nombre_solicitud']+'</option>');
                    }
                }
            // }else{
            //     $('#motivo_solicitud').empty();
            //     $('#motivo_solicitud').append('<option value=""></option>');
            // }
        }
    });


});