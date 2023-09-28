$(document).ready(function(){
    $("#mostrar_ocultar_formularios").slideUp('fast');
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

    // Inicialización de select2 de tipo de accidente
    $(".tipo_accidente").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Inicialización de select2 de grado de severidad
    $(".grado_severidad").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Inicialización de select2 de mortal
    $(".mortal").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Incialización de select2 factor de riesgo
    $(".factor_riesgo").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Incialización de select2 tipo de lesion
    $(".tipo_lesion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Incialización de select2 parte del cuerpo afectada
    $(".parte_cuerpo_afectada").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Incialización de select2 ORIGEN DTO ATEL
    $(".origen_dto_atel").select2({
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
                        // if (data[listado_tipo_evento[i]]['Nombre_evento'] == nombre_evento_bd) {                    
                        //     $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        // }else{
                        // }
                        $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
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

    // llenado de selector de motivos de solicitud
    let datos_motivo_solicitud = {
        '_token': token,
        'parametro':"motivo_solicitud"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_motivo_solicitud,
        success:function(data){
            //console.log(data);
            let motivo_solicitud_bd = $('#motivo_solicitud_bd').val();
            $('#motivo_solicitud').append('<option value=""></option>');
            let listado_motivo_solicitud = Object.keys(data);
            for (let i = 0; i < listado_motivo_solicitud.length; i++) {
                if (data[listado_motivo_solicitud[i]]['Nombre_solicitud'] == motivo_solicitud_bd) {                    
                    $('#motivo_solicitud').append('<option value="'+data[listado_motivo_solicitud[i]]['Id_Solicitud']+'" selected>'+data[listado_motivo_solicitud[i]]['Nombre_solicitud']+'</option>');
                }else{
                    $('#motivo_solicitud').append('<option value="'+data[listado_motivo_solicitud[i]]['Id_Solicitud']+'">'+data[listado_motivo_solicitud[i]]['Nombre_solicitud']+'</option>');
                }
            }
        }
    });

    // llenado de datos selector tipo de accidente
    let datos_tipo_accidente = {
        '_token': token,
        'parametro':"tipo_accidente"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_tipo_accidente,
        success:function(data){
            //console.log(data);
            $('#tipo_accidente').empty();
            $('#tipo_accidente').append('<option value=""></option>');
            let listado_tipo_accidente = Object.keys(data);
            for (let i = 0; i < listado_tipo_accidente.length; i++) {
                $('#tipo_accidente').append('<option value="'+data[listado_tipo_accidente[i]]['Id_Parametro']+'">'+data[listado_tipo_accidente[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    // llenado de datos de selector de grado de severidad
    let datos_grado_severidad = {
        '_token': token,
        'parametro':"grado_severidad"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_grado_severidad,
        success:function(data){
            //console.log(data);
            $('#grado_severidad').empty();
            $('#grado_severidad').append('<option value=""></option>');
            let listado_grado_severidad = Object.keys(data);
            for (let i = 0; i < listado_grado_severidad.length; i++) {
                $('#grado_severidad').append('<option value="'+data[listado_grado_severidad[i]]['Id_Parametro']+'">'+data[listado_grado_severidad[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    // Validación selector mortal
    $("#mortal").change(function(){
        let opt_mortal_selccionada = $(this).val();

        if (opt_mortal_selccionada == "Si") {
            // $("#mostrar_f_fallecimiento").slideDown('slow');
            $("#mostrar_f_fallecimiento").removeClass("d-none");
            $("#fecha_fallecimiento").prop("required", true);
        } else {
            // $("#mostrar_f_fallecimiento").slideUp('slow');
            $("#mostrar_f_fallecimiento").addClass("d-none");
            $("#fecha_fallecimiento").prop("required", false);
        }
    });

    // llenado de datos de selector de factor de riesgo
    let datos_factor_riesgo = {
        '_token': token,
        'parametro':"factor_riesgo"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_factor_riesgo,
        success:function(data){
            //console.log(data);
            $('#factor_riesgo').empty();
            $('#factor_riesgo').append('<option value=""></option>');
            let listado_factor_riesgo = Object.keys(data);
            for (let i = 0; i < listado_factor_riesgo.length; i++) {
                $('#factor_riesgo').append('<option value="'+data[listado_factor_riesgo[i]]['Id_Parametro']+'">'+data[listado_factor_riesgo[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    // llenado de datos de selector de tipo de lesion
    let datos_tipo_lesion = {
        '_token': token,
        'parametro':"tipo_lesion"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_tipo_lesion,
        success:function(data){
            //console.log(data);
            $('#tipo_lesion').empty();
            $('#tipo_lesion').append('<option value=""></option>');
            let listado_tipo_lesion = Object.keys(data);
            for (let i = 0; i < listado_tipo_lesion.length; i++) {
                $('#tipo_lesion').append('<option value="'+data[listado_tipo_lesion[i]]['Id_Parametro']+'">'+data[listado_tipo_lesion[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    // llenado de datos de selector de parte del cuerpo afectada
    let datos_parte_cuerpo_afectada = {
        '_token': token,
        'parametro':"parte_cuerpo_afectada"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_parte_cuerpo_afectada,
        success:function(data){
            //console.log(data);
            $('#parte_cuerpo_afectada').empty();
            $('#parte_cuerpo_afectada').append('<option value=""></option>');
            let listado_parte_cuerpo_afectada = Object.keys(data);
            for (let i = 0; i < listado_parte_cuerpo_afectada.length; i++) {
                $('#parte_cuerpo_afectada').append('<option value="'+data[listado_parte_cuerpo_afectada[i]]['Id_Parametro']+'">'+data[listado_parte_cuerpo_afectada[i]]['Nombre_parametro']+'</option>');
            }
        }
    });

    // DataTable LISTADO DOCUMENTOS SOLICITADOS
    var listado_docs_seguimiento = $('#listado_docs_seguimiento').DataTable({
        "responsive": true,
        "info": false,
        "searching": false,
        "ordering": false,
        "scrollCollapse": true,
        "scrollY": "30vh",
        "paging": false,
        "language":{
            "emptyTable": "No se encontró información"
        }
    });
    autoAdjustColumns(listado_docs_seguimiento);

     // DataTable HISTÓRICO LABORAL
     var historico_laboral = $('#historico_laboral').DataTable({
        "responsive": true,
        "info": false,
        "searching": false,
        "ordering": false,
        "scrollCollapse": true,
        "scrollY": "30vh",
        "paging": false,
        "language":{
            "emptyTable": "No se encontró información"
        }
    });
    autoAdjustColumns(historico_laboral);

    // Validaciones de Cobertura y Tipo de Evento
    // 
    $("#tipo_evento").change(function(){
        var tipo_evento_selecccionado = $("#tipo_evento option:selected").text();
        var activo = $("#es_activo").val();
       
        // Validacion N°1: Activo = Si y Tipo de Evento = Accidente
        if (activo == "Si" && tipo_evento_selecccionado == "Accidente") {
            // $("#mostrar_ocultar_formularios").removeClass('d-none');
            $("#mostrar_ocultar_formularios").slideDown('slow');
            var parametro_origen_dto_atel = "origen_vali_1";
        }
        // Validacion N°1: Activo = Si y Tipo de Evento = Enfermedad
        else if(activo == "Si" && tipo_evento_selecccionado == "Enfermedad"){
            $("#mostrar_ocultar_formularios").slideDown('slow');
        }


        // llenado de datos del selector origen acorde a la validación N°1
        let datos_selector_origen_val_1 = {
            '_token': token,
            'parametro': parametro_origen_dto_atel
        };
        $.ajax({
            type:'POST',
            url:'/cargueListadoSelectoresDTOATEL',
            data: datos_selector_origen_val_1,
            success:function(data){
                //console.log(data);
                $('#origen_dto_atel').empty();
                $('#origen_dto_atel').append('<option value=""></option>');
                let listado_origen_dto_atel = Object.keys(data);
                for (let i = 0; i < listado_origen_dto_atel.length; i++) {
                    $('#origen_dto_atel').append('<option value="'+data[listado_origen_dto_atel[i]]['Id_Parametro']+'">'+data[listado_origen_dto_atel[i]]['Nombre_parametro']+'</option>');
                }
            }
        });
    });


});

/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnostico motivo de calificación*/
function funciones_elementos_fila_diagnosticos(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_origenCie10_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_lateralidadCie10_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    //Carga de datos en los selectores

    let token = $("input[name='_token']").val();
    let datos_CIE10 = {
        '_token': token,
        'parametro' : "listado_CIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_CIE10,
        success:function(data){
            // $("select[id^='lista_Cie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+'</option>');
            }
        }
    });

    let datos_Origen_CIE10 = {
        '_token': token,
        'parametro' : "listado_OrigenCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Origen_CIE10,
        success:function(data){
            // $("select[id^='lista_origenCie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_origenCie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    let datos_Lateralidad_CIE10 = {
        '_token': token,
        'parametro' : "listado_LateralidadCIE10",
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_Lateralidad_CIE10,
        success:function(data){
            // $("select[id^='lista_origenCie10_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_lateralidadCie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    $(document).on('change', '#lista_Cie10_fila_'+num_consecutivo, function() {        
        let seleccion = $(this).val();        
        let datos_Nombre_CIE = {
            '_token': token,
            'parametro' : "listado_NombreCIE10",
            'seleccion': seleccion,
        };    
        $.ajax({
            type:'POST',
            url:'/cargueListadoSelectoresDTOATEL',
            data: datos_Nombre_CIE,
            success:function(data){
                //console.log(data);
                let claves = Object.keys(data);
                //console.log(claves);
                for (let i = 0; i < claves.length; i++) {
                    $("#nombre_cie10_fila_"+num_consecutivo).val(data[claves[i]]["Descripcion_diagnostico"]);
                }
            }
        });
    });
}
