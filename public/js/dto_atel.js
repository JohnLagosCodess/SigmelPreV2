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

    // VERIFICACIÓN DEL DATO ACTIVO EN CASO DE QUE EXISTA INFORMACIÓN GUARDADA
    var verificacion_activo = $("#es_activo").val();
    
    if (verificacion_activo != "") {
        if (verificacion_activo == "Si") {
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
                    $('#tipo_evento').prop('disabled', false);
                    $('#tipo_evento').empty();
                    $('#tipo_evento').append('<option value=""></option>');

                    let listado_tipo_evento = Object.keys(data);
                    for (let i = 0; i < listado_tipo_evento.length; i++) {
                        // if (data[listado_tipo_evento[i]]['Nombre_evento'] == nombre_evento_bd) {                    
                        if (data[listado_tipo_evento[i]]['Id_Evento'] == $("#bd_tipo_evento").val()) {                    
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
                    
                    $('#tipo_evento').prop('disabled', false);
                    $('#tipo_evento').empty();
                    $('#tipo_evento').append('<option value=""></option>');

                    let listado_tipo_evento = Object.keys(data);
                    for (let i = 0; i < listado_tipo_evento.length; i++) {
                        if (data[listado_tipo_evento[i]]['Id_Evento'] == $("#bd_tipo_evento").val()) {  
                            $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        }else{
                            $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        }
                    }
                }
            });
        }
    }

    
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
                    $('#tipo_evento').prop('disabled', false);
                    $('#tipo_evento').empty();
                    $('#tipo_evento').append('<option value=""></option>');

                    let listado_tipo_evento = Object.keys(data);
                    for (let i = 0; i < listado_tipo_evento.length; i++) {             
                        // if (data[listado_tipo_evento[i]]['Id_Evento'] == $("#bd_tipo_evento").val()) {      
                        //     $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        // }else{
                        //     $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        // }
                        if ($("#bd_tipo_evento").val() != "") {
                            if (data[listado_tipo_evento[i]]['Id_Evento'] == $("#bd_tipo_evento").val()) {                    
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }else{
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }
                        }else{
                            
                            if (data[listado_tipo_evento[i]]['Nombre_evento'] == $("#nombre_evento_gestion_edicion").val()) {
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }else{
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }
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
                    $('#tipo_evento').prop('disabled', false);
                    $('#tipo_evento').empty();
                    $('#tipo_evento').append('<option value=""></option>');

                    let listado_tipo_evento = Object.keys(data);
                    for (let i = 0; i < listado_tipo_evento.length; i++) {
                        // if (data[listado_tipo_evento[i]]['Id_Evento'] == $("#bd_tipo_evento").val()) {         
                        //     $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        // }else{
                        //     $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                        // }

                        if ($("#bd_tipo_evento").val() != "") {
                            if (data[listado_tipo_evento[i]]['Id_Evento'] == $("#bd_tipo_evento").val()) {                    
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }else{
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }
                        }else{
                            
                            if (data[listado_tipo_evento[i]]['Nombre_evento'] == $("#nombre_evento_gestion_edicion").val()) {
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }else{
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }
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
                if (data[listado_tipo_accidente[i]]['Id_Parametro'] == $("#bd_tipo_accidente").val()) {
                    $('#tipo_accidente').append('<option value="'+data[listado_tipo_accidente[i]]['Id_Parametro']+'" selected>'+data[listado_tipo_accidente[i]]['Nombre_parametro']+'</option>');
                } else {
                    $('#tipo_accidente').append('<option value="'+data[listado_tipo_accidente[i]]['Id_Parametro']+'">'+data[listado_tipo_accidente[i]]['Nombre_parametro']+'</option>');
                }
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
                if (data[listado_grado_severidad[i]]['Id_Parametro'] == $("#bd_grado_severidad").val()) {
                    $('#grado_severidad').append('<option value="'+data[listado_grado_severidad[i]]['Id_Parametro']+'" selected>'+data[listado_grado_severidad[i]]['Nombre_parametro']+'</option>');
                } else {
                    $('#grado_severidad').append('<option value="'+data[listado_grado_severidad[i]]['Id_Parametro']+'">'+data[listado_grado_severidad[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    // VERIFICACION DE SELECTOR MORTAL EN CASO DE QUE EXISTA INFORMACIÓN GUARDADA
    var mortal_opt = "";

    var verificacion_mortal= $("#mortal").val();
    if (verificacion_mortal == "Si") {
        $("#mostrar_f_fallecimiento").removeClass("d-none");
        $("#fecha_fallecimiento").prop("required", true);
        mortal_opt = verificacion_mortal;
    } else if(verificacion_mortal == "No") {
        $("#mostrar_f_fallecimiento").addClass("d-none");
        $("#fecha_fallecimiento").prop("required", false);
        $("#fecha_fallecimiento").val("");
        mortal_opt = "No";
    } else{
        mortal_opt = "";
    }
    
    // Validación selector mortal
    $("#mortal").change(function(){
        var opt_mortal_selccionada = $(this).val();

        if (opt_mortal_selccionada == "Si") {
            $("#mostrar_f_fallecimiento").removeClass("d-none");
            $("#fecha_fallecimiento").prop("required", true);
            mortal_opt = opt_mortal_selccionada;
        } else if(opt_mortal_selccionada == "No") {
            $("#mostrar_f_fallecimiento").addClass("d-none");
            $("#fecha_fallecimiento").prop("required", false);
            $("#fecha_fallecimiento").val("");
            mortal_opt = "No";
        }else{
            mortal_opt = "";
        }
    });

    // VERIFICACION DE CHECKBOX ENFERMEDAD HEREDADA EN CASO DE DE QUE EXISTA INFORMACIÓN YA GUARADADA.
    var enfermedad_heredada_opt = "";

    if ($("#enfermedad_heredada").is(":checked")) {
        $("#contenedor_nombre_entidad_enfermedad_heredada").removeClass('d-none');
        enfermedad_heredada_opt = "Si";
    } else {
        $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
        $("#entidad_enfermedad").val("");
        enfermedad_heredada_opt = "No";
    }

    // Validadion checkbox Enfermedad heredada
    
    $("#enfermedad_heredada").click(function(){
        if ($(this).is(":checked")) {
            $("#contenedor_nombre_entidad_enfermedad_heredada").removeClass('d-none');
            enfermedad_heredada_opt = "Si";
        }else{
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#entidad_enfermedad").val("");
            enfermedad_heredada_opt = "No";
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
                if (data[listado_factor_riesgo[i]]['Id_Parametro'] == $("#bd_factor_riesgo").val()) {
                    $('#factor_riesgo').append('<option value="'+data[listado_factor_riesgo[i]]['Id_Parametro']+'" selected>'+data[listado_factor_riesgo[i]]['Nombre_parametro']+'</option>');
                } else {
                    $('#factor_riesgo').append('<option value="'+data[listado_factor_riesgo[i]]['Id_Parametro']+'">'+data[listado_factor_riesgo[i]]['Nombre_parametro']+'</option>');
                }
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
                if (data[listado_tipo_lesion[i]]['Id_Parametro'] == $("#bd_tipo_lesion").val()) {
                    $('#tipo_lesion').append('<option value="'+data[listado_tipo_lesion[i]]['Id_Parametro']+'" selected>'+data[listado_tipo_lesion[i]]['Nombre_parametro']+'</option>');
                } else {
                    $('#tipo_lesion').append('<option value="'+data[listado_tipo_lesion[i]]['Id_Parametro']+'">'+data[listado_tipo_lesion[i]]['Nombre_parametro']+'</option>');
                }
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
                if (data[listado_parte_cuerpo_afectada[i]]['Id_Parametro'] == $("#bd_parte_cuerpo_afectada").val()) {
                    $('#parte_cuerpo_afectada').append('<option value="'+data[listado_parte_cuerpo_afectada[i]]['Id_Parametro']+'" selected>'+data[listado_parte_cuerpo_afectada[i]]['Nombre_parametro']+'</option>');
                } else {
                    $('#parte_cuerpo_afectada').append('<option value="'+data[listado_parte_cuerpo_afectada[i]]['Id_Parametro']+'">'+data[listado_parte_cuerpo_afectada[i]]['Nombre_parametro']+'</option>');
                }
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

    var intervalo = setInterval(() => {
        var activo = $("#es_activo").val();
        var verificacion_tipo_evento = $("#tipo_evento option:selected").text();

        // Validacion N°1: Activo = Si y Tipo de Evento = Accidente
        if (activo == "Si" && verificacion_tipo_evento == "Accidente") {
            var parametro_origen_dto_atel = "origen_vali_1";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');
            
            // Mostramos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").removeClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');
            clearInterval(intervalo);
        }
        // Validacion N°2: Activo = Si y Tipo de Evento = Enfermedad
        else if(activo == "Si" && verificacion_tipo_evento == "Enfermedad"){
            var parametro_origen_dto_atel = "origen_vali_1";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").removeClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").removeClass('d-none');
            $("#contenedor_enfermedad_heredada").removeClass('d-none');
            // $("#contenedor_nombre_entidad_enfermedad_heredada").removeClass('d-none');
            $("#contenedor_checkboxes_enfermedad").removeClass('d-none');

            // Mostramos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").removeClass('d-none');

            // Les quitamos el atributo required
            $("#tipo_accidente").removeAttr('required');
            $("#fecha_evento").removeAttr('required');
            $("#hora_evento").removeAttr('required');
            $("#grado_severidad").removeAttr('required');
            $("#descripcion_FURAT").removeAttr('required');

            // Ocultamos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").addClass('d-none');
            $("#contenedor_grado_severidad").addClass('d-none');
            $("#contenedor_descrip_FURAT").addClass('d-none');
            $("#contenedor_tipo_lesion").addClass('d-none');
            $("#contenedor_parte_afectada").addClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").addClass('d-none');
            clearInterval(intervalo);
        }
        // Validacion N°3: Activo = Si y Tipo de Evento = Incidente
        else if(activo == "Si" && verificacion_tipo_evento == "Incidente"){
            var parametro_origen_dto_atel = "origen_vali_2";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');

            // Ocultamos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").addClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');
            clearInterval(intervalo);
        }
        // Validacion N°4: Activo = Si y Tipo de Evento = Sin Cobertura
        else if(activo == "No" && verificacion_tipo_evento == "Sin Cobertura"){
            var parametro_origen_dto_atel = "origen_vali_3";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');

            // Ocultamos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").addClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');
            clearInterval(intervalo);
        }

        
    }, 500);


    // VERIFICACION DEL DATO DE TIPO DE EVENTO EN CASO DE QUE YA EXISTA INFORMACIÓN GUARDADA
    var verificacion_tipo_evento = $("#nombre_evento_guardado").val();
   
    if (verificacion_tipo_evento != "") {
        var activo = $("#es_activo").val();
       
        // Validacion N°1: Activo = Si y Tipo de Evento = Accidente
        if (activo == "Si" && verificacion_tipo_evento == "Accidente") {
            var parametro_origen_dto_atel = "origen_vali_1";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');
            
            // Mostramos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").removeClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');

        }
        // Validacion N°2: Activo = Si y Tipo de Evento = Enfermedad
        else if(activo == "Si" && verificacion_tipo_evento == "Enfermedad"){
            var parametro_origen_dto_atel = "origen_vali_1";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").removeClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").removeClass('d-none');
            $("#contenedor_enfermedad_heredada").removeClass('d-none');
            // $("#contenedor_nombre_entidad_enfermedad_heredada").removeClass('d-none');
            $("#contenedor_checkboxes_enfermedad").removeClass('d-none');

            // Mostramos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").removeClass('d-none');

            // Les quitamos el atributo required
            $("#tipo_accidente").removeAttr('required');
            $("#fecha_evento").removeAttr('required');
            $("#hora_evento").removeAttr('required');
            $("#grado_severidad").removeAttr('required');
            $("#descripcion_FURAT").removeAttr('required');

            // Ocultamos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").addClass('d-none');
            $("#contenedor_grado_severidad").addClass('d-none');
            $("#contenedor_descrip_FURAT").addClass('d-none');
            $("#contenedor_tipo_lesion").addClass('d-none');
            $("#contenedor_parte_afectada").addClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").addClass('d-none');
        }
        // Validacion N°3: Activo = Si y Tipo de Evento = Incidente
        else if(activo == "Si" && verificacion_tipo_evento == "Incidente"){
            var parametro_origen_dto_atel = "origen_vali_2";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');

            // Ocultamos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").addClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');

        }
        // Validacion N°4: Activo = Si y Tipo de Evento = Sin Cobertura
        else if(activo == "No" && verificacion_tipo_evento == "Sin Cobertura"){
            var parametro_origen_dto_atel = "origen_vali_3";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');

            // Ocultamos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").addClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');
            
        }


        // llenado de datos del selector origen acorde a las validaciones
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
                    if (data[listado_origen_dto_atel[i]]['Id_Parametro'] == $("#bd_origen").val()) {
                        $('#origen_dto_atel').append('<option value="'+data[listado_origen_dto_atel[i]]['Id_Parametro']+'" selected>'+data[listado_origen_dto_atel[i]]['Nombre_parametro']+'</option>');
                    } else {
                        $('#origen_dto_atel').append('<option value="'+data[listado_origen_dto_atel[i]]['Id_Parametro']+'">'+data[listado_origen_dto_atel[i]]['Nombre_parametro']+'</option>');
                    }
                }
            }
        });
    }

    // Validaciones de Cobertura y Tipo de Evento
    $("#tipo_evento").change(function(){
        var tipo_evento_selecccionado = $("#tipo_evento option:selected").text();
        var activo = $("#es_activo").val();
       
        // Validacion N°1: Activo = Si y Tipo de Evento = Accidente
        if (activo == "Si" && tipo_evento_selecccionado == "Accidente") {
            var parametro_origen_dto_atel = "origen_vali_1";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');
            
            // Mostramos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").removeClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');

        }
        // Validacion N°2: Activo = Si y Tipo de Evento = Enfermedad
        else if(activo == "Si" && tipo_evento_selecccionado == "Enfermedad"){
            var parametro_origen_dto_atel = "origen_vali_1";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").removeClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").removeClass('d-none');
            $("#contenedor_enfermedad_heredada").removeClass('d-none');
            // $("#contenedor_nombre_entidad_enfermedad_heredada").removeClass('d-none');
            $("#contenedor_checkboxes_enfermedad").removeClass('d-none');

            // Mostramos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").removeClass('d-none');

            // Les quitamos el atributo required
            $("#tipo_accidente").removeAttr('required');
            $("#fecha_evento").removeAttr('required');
            $("#hora_evento").removeAttr('required');
            $("#grado_severidad").removeAttr('required');
            $("#descripcion_FURAT").removeAttr('required');

            // Ocultamos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").addClass('d-none');
            $("#contenedor_grado_severidad").addClass('d-none');
            $("#contenedor_descrip_FURAT").addClass('d-none');
            $("#contenedor_tipo_lesion").addClass('d-none');
            $("#contenedor_parte_afectada").addClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").addClass('d-none');
        }
        // Validacion N°3: Activo = Si y Tipo de Evento = Incidente
        else if(activo == "Si" && tipo_evento_selecccionado == "Incidente"){
            var parametro_origen_dto_atel = "origen_vali_2";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');

            // Ocultamos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").addClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');

        }

        // Validacion N°4: Activo = Si y Tipo de Evento = Sin Cobertura
        else if(activo == "No" && tipo_evento_selecccionado == "Sin Cobertura"){
            var parametro_origen_dto_atel = "origen_vali_3";
            $("#mostrar_ocultar_formularios").slideUp('slow');
            $("#mostrar_ocultar_formularios").slideDown('slow');

            // Mostramos los contenedores del formulario accidente
            $("#contenedor_forms_acci_inci_sincober").removeClass('d-none');
            $("#contenedor_grado_severidad").removeClass('d-none');
            $("#contenedor_descrip_FURAT").removeClass('d-none');
            $("#contenedor_tipo_lesion").removeClass('d-none');
            $("#contenedor_parte_afectada").removeClass('d-none');
            $("#contenedor_checkboxes_acci_inci_sincober").removeClass('d-none');

            // Ocultamos el contenedor del formulario de incidente
            $("#contenedor_diag_moti_califi").addClass('d-none');

            // Les adicionamos el atributo required
            $("#tipo_accidente").attr('required', true);
            $("#fecha_evento").attr('required', true);
            $("#hora_evento").attr('required', true);
            $("#grado_severidad").attr('required', true);
            $("#descripcion_FURAT").attr('required', true);
            
            // Ocultamos los contenedores del formulario enfermedad
            $("#contenedor_historico_laboral").addClass('d-none');
            $("#contenedor_fecha_diagnos_enfermedad").addClass('d-none');
            $("#contenedor_enfermedad_heredada").addClass('d-none');
            $("#contenedor_nombre_entidad_enfermedad_heredada").addClass('d-none');
            $("#contenedor_checkboxes_enfermedad").addClass('d-none');
            
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
                    if (data[listado_origen_dto_atel[i]]['Id_Parametro'] == $("#bd_origen").val()) {
                        $('#origen_dto_atel').append('<option value="'+data[listado_origen_dto_atel[i]]['Id_Parametro']+'" selected>'+data[listado_origen_dto_atel[i]]['Nombre_parametro']+'</option>');
                    } else {
                        $('#origen_dto_atel').append('<option value="'+data[listado_origen_dto_atel[i]]['Id_Parametro']+'">'+data[listado_origen_dto_atel[i]]['Nombre_parametro']+'</option>');
                    }
                }
            }
        });
    });

    // Envío de información
    $("#form_DTO_ATEL").submit(function(e){
        e.preventDefault();

        // Captura de Id evento, Id asignacion, Id proceso
        var id_evento = $("#Id_Evento_dto_atel").val();
        var id_asignacion = $('#Id_Asignacion_dto_atel').val();
        var id_proceso = $("#Id_Proceso_dto_atel").val();

        // Creación de Datos para los formulario: Accidente, Incidente, Sin cobertura
        let token = $("input[name='_token']").val();

        var tipo_evento = $("#tipo_evento").val();

        // Formulario: Accidente, Incidente, Sin Cobertura
        if (tipo_evento == 1 || tipo_evento == 3 || tipo_evento == 4) {
            // Creacion de array para los checkboxes de relacion de documentos
            var relacion_docs_dto_atel = [];
            $('input[type="checkbox"]').each(function() {
                var relacion_documento_dto_atel = $(this).attr('id');            
                if (relacion_documento_dto_atel === 'furat_acci_inci_sincober' || relacion_documento_dto_atel === 'historia_clinica_acci_inci_sincober') {                
                    if ($(this).is(':checked')) {                
                        var relacion_documento_dto_atel_valor = $(this).val();
                        relacion_docs_dto_atel.push(relacion_documento_dto_atel_valor);
                    }
                }
            });
            
            // Creacion de array con los datos de la tabla dinámica Exámenes e interconsultas
            var guardar_datos_examenes_interconsultas = [];
            var datos_finales_examenes_interconsultas = [];
            // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
            $('#listado_examenes_interconsultas tbody tr').each(function (index) {
                if ($(this).attr('id') !== "datos_examenes_interconsulta") {
                    $(this).children("td").each(function (index2) {
                        var nombres_ids = $(this).find('*').attr("id");
                        if (nombres_ids != undefined) {
                            guardar_datos_examenes_interconsultas.push($('#'+nombres_ids).val());                        
                        }
                        if((index2+1) % 3 === 0){
                            datos_finales_examenes_interconsultas.push(guardar_datos_examenes_interconsultas);
                            guardar_datos_examenes_interconsultas = [];
                        }
                    });
                }
            });

            // Creacion de array con los datos de la tabla dinámica Diagnóstico motivo de calificación
            var guardar_datos_motivo_calificacion = [];
            var datos_finales_motivo_calificacion = [];
            var array_id_filas = [];
            // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
            $('#listado_diagnostico_cie10 tbody tr').each(function (index) {
                array_id_filas.push($(this).attr('id'));
                if ($(this).attr('id') !== "datos_diagnostico") {
                    $(this).children("td").each(function (index2) {
                        var nombres_ids = $(this).find('*').attr("id");
                        if (nombres_ids != undefined) {
                            if ($('#'+nombres_ids).val() == "on") {
                                if ($('#'+nombres_ids).is(':checked')) {
                                    guardar_datos_motivo_calificacion.push("Si");  
                                } else {
                                    guardar_datos_motivo_calificacion.push("No");  
                                }
                            }else{
                                guardar_datos_motivo_calificacion.push($('#'+nombres_ids).val());                     
                            }
                        }
                        if((index2+1) % 6 === 0){
                            datos_finales_motivo_calificacion.push(guardar_datos_motivo_calificacion);
                            guardar_datos_motivo_calificacion = [];
                        }
                    });
                }
            });

            // validación de id_dto_atel para saber si toca actualizar la información
            var id_dto_atel = $("#id_dto_atel").val();

            if (id_dto_atel == "" || id_dto_atel == undefined) {
                // Registrar Información
                var informacion_formulario = {
                    '_token': token,
                    'ID_Evento': id_evento,
                    'Id_Asignacion': id_asignacion,
                    'Id_proceso': id_proceso,
                    'Activo': $("#es_activo").val(),
                    'Tipo_evento': tipo_evento,
                    'Fecha_dictamen': $("#fecha_dictamen").val(),
                    'Numero_dictamen': $("#numero_dictamen").val(),
                    'motivo_solicitud': $("#motivo_solicitud").val(),
                    'Tipo_accidente': $("#tipo_accidente").val(), 
                    'Fecha_evento': $("#fecha_evento").val(), 
                    'Hora_evento': $("#hora_evento").val(), 
                    'Grado_severidad': $("#grado_severidad").val(),
                    'Mortal': mortal_opt,
                    'Fecha_fallecimiento': $("#fecha_fallecimiento").val(),
                    'Descripcion_FURAT': $("#descripcion_FURAT").val(),
                    'Factor_riesgo': $("#factor_riesgo").val(),
                    'Tipo_lesion': $("#tipo_lesion").val(),
                    'Parte_cuerpo_afectada': $("#parte_cuerpo_afectada").val(),
                    'Justificacion_revision_origen': $("#justificacion_revision_origen").val(),
                    'Relacion_documentos': relacion_docs_dto_atel,
                    'Examenes_interconsultas': datos_finales_examenes_interconsultas,
                    'Motivo_calificacion': datos_finales_motivo_calificacion,
                    'Otros_relacion_documentos': $("#otros_acci_inci_sincober").val(),
                    'Sustentacion': $("#sustentacion_califi_origen").val(),
                    'Origen': $("#origen_dto_atel").val(),
                };
            } else {
                // Editar Información
                var informacion_formulario = {
                    '_token': token,
                    'ID_Evento': id_evento,
                    'Id_Asignacion': id_asignacion,
                    'Id_proceso': id_proceso,
                    'Id_Dto_ATEL': id_dto_atel,
                    'Activo': $("#es_activo").val(),
                    'Tipo_evento': tipo_evento,
                    'Fecha_dictamen': $("#fecha_dictamen").val(),
                    'Numero_dictamen': $("#numero_dictamen").val(),
                    'motivo_solicitud': $("#motivo_solicitud").val(),
                    'Tipo_accidente': $("#tipo_accidente").val(), 
                    'Fecha_evento': $("#fecha_evento").val(), 
                    'Hora_evento': $("#hora_evento").val(), 
                    'Grado_severidad': $("#grado_severidad").val(),
                    'Mortal': mortal_opt,
                    'Fecha_fallecimiento': $("#fecha_fallecimiento").val(),
                    'Descripcion_FURAT': $("#descripcion_FURAT").val(),
                    'Factor_riesgo': $("#factor_riesgo").val(),
                    'Tipo_lesion': $("#tipo_lesion").val(),
                    'Parte_cuerpo_afectada': $("#parte_cuerpo_afectada").val(),
                    'Justificacion_revision_origen': $("#justificacion_revision_origen").val(),
                    'Relacion_documentos': relacion_docs_dto_atel,
                    'Examenes_interconsultas': datos_finales_examenes_interconsultas,
                    'Motivo_calificacion': datos_finales_motivo_calificacion,
                    'Otros_relacion_documentos': $("#otros_acci_inci_sincober").val(),
                    'Sustentacion': $("#sustentacion_califi_origen").val(),
                    'Origen': $("#origen_dto_atel").val(),
                };
            }
        }
        // Formulario: Enfermedad
        else if (tipo_evento == 2) {
            // Creacion de array para los checkboxes de relacion de documentos
            var relacion_docs_dto_atel = [];
            $('input[type="checkbox"]').each(function() {
                var relacion_documento_dto_atel = $(this).attr('id');            
                if (relacion_documento_dto_atel === 'furel_enfermedad' || relacion_documento_dto_atel === 'historia_clinica_enfermedad' ||
                    relacion_documento_dto_atel === 'apoyo_diag_interconsulta_enfermedad' || relacion_documento_dto_atel === 'analisis_puesto_trabajo_enfermedad' ||
                    relacion_documento_dto_atel === 'examenes_pre_preocupacionales_enfermedad' || relacion_documento_dto_atel === 'examenes_periodicos_preocupacionales_enfermedad' ||
                    relacion_documento_dto_atel === 'examenes_post_ocupacionales_enfermedad'
                ) {                
                    if ($(this).is(':checked')) {                
                        var relacion_documento_dto_atel_valor = $(this).val();
                        relacion_docs_dto_atel.push(relacion_documento_dto_atel_valor);
                    }
                }
            });

            // Creacion de array con los datos de la tabla dinámica Exámenes e interconsultas
            var guardar_datos_examenes_interconsultas = [];
            var datos_finales_examenes_interconsultas = [];
            // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
            $('#listado_examenes_interconsultas tbody tr').each(function (index) {
                if ($(this).attr('id') !== "datos_examenes_interconsulta") {
                    $(this).children("td").each(function (index2) {
                        var nombres_ids = $(this).find('*').attr("id");
                        if (nombres_ids != undefined) {
                            guardar_datos_examenes_interconsultas.push($('#'+nombres_ids).val());                        
                        }
                        if((index2+1) % 3 === 0){
                            datos_finales_examenes_interconsultas.push(guardar_datos_examenes_interconsultas);
                            guardar_datos_examenes_interconsultas = [];
                        }
                    });
                }
            });

            // Creacion de array con los datos de la tabla dinámica Diagnóstico motivo de calificación
            var guardar_datos_motivo_calificacion = [];
            var datos_finales_motivo_calificacion = [];
            var array_id_filas = [];
            // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
            $('#listado_diagnostico_cie10 tbody tr').each(function (index) {
                array_id_filas.push($(this).attr('id'));
                if ($(this).attr('id') !== "datos_diagnostico") {
                    $(this).children("td").each(function (index2) {
                        var nombres_ids = $(this).find('*').attr("id");
                        if (nombres_ids != undefined) {
                            if ($('#'+nombres_ids).val() == "on") {
                                if ($('#'+nombres_ids).is(':checked')) {
                                    guardar_datos_motivo_calificacion.push("Si");  
                                } else {
                                    guardar_datos_motivo_calificacion.push("No");  
                                }
                            }else{
                                guardar_datos_motivo_calificacion.push($('#'+nombres_ids).val());                     
                            }
                        }
                        if((index2+1) % 6 === 0){
                            datos_finales_motivo_calificacion.push(guardar_datos_motivo_calificacion);
                            guardar_datos_motivo_calificacion = [];
                        }
                    });
                }
            });

            // validación de id_dto_atel para saber si toca actualizar la información
            var id_dto_atel = $("#id_dto_atel").val();

            if (id_dto_atel == "" || id_dto_atel == undefined) {
                // Registrar Información
                var informacion_formulario = {
                    '_token': token,
                    'ID_Evento': id_evento,
                    'Id_Asignacion': id_asignacion,
                    'Id_proceso': id_proceso,
                    'Activo': $("#es_activo").val(),
                    'Tipo_evento': tipo_evento,
                    'Fecha_dictamen': $("#fecha_dictamen").val(),
                    'Numero_dictamen': $("#numero_dictamen").val(),
                    'motivo_solicitud': $("#motivo_solicitud").val(),
                    'Fecha_diagnostico_enfermedad': $("#fecha_enfermedad").val(),
                    'Mortal': mortal_opt,
                    'Fecha_fallecimiento': $("#fecha_fallecimiento").val(),
                    'Factor_riesgo': $("#factor_riesgo").val(),
                    'Enfermedad_heredada': enfermedad_heredada_opt,
                    'Nombre_entidad_hereda': $("#entidad_enfermedad").val(),
                    'Justificacion_revision_origen': $("#justificacion_revision_origen").val(),
                    'Relacion_documentos': relacion_docs_dto_atel,
                    'Examenes_interconsultas': datos_finales_examenes_interconsultas,
                    'Motivo_calificacion': datos_finales_motivo_calificacion,
                    'Otros_relacion_documentos': $("#otros_enfermedad").val(),
                    'Sustentacion': $("#sustentacion_califi_origen").val(),
                    'Origen': $("#origen_dto_atel").val(),
                };
            }else{
                // Editar Información
                var informacion_formulario = {
                    '_token': token,
                    'ID_Evento': id_evento,
                    'Id_Asignacion': id_asignacion,
                    'Id_proceso': id_proceso,
                    'Id_Dto_ATEL': id_dto_atel,
                    'Activo': $("#es_activo").val(),
                    'Tipo_evento': tipo_evento,
                    'Fecha_dictamen': $("#fecha_dictamen").val(),
                    'Numero_dictamen': $("#numero_dictamen").val(),
                    'motivo_solicitud': $("#motivo_solicitud").val(),
                    'Fecha_diagnostico_enfermedad': $("#fecha_enfermedad").val(),
                    'Mortal': mortal_opt,
                    'Fecha_fallecimiento': $("#fecha_fallecimiento").val(),
                    'Factor_riesgo': $("#factor_riesgo").val(),
                    'Enfermedad_heredada': enfermedad_heredada_opt,
                    'Nombre_entidad_hereda': $("#entidad_enfermedad").val(),
                    'Justificacion_revision_origen': $("#justificacion_revision_origen").val(),
                    'Relacion_documentos': relacion_docs_dto_atel,
                    'Examenes_interconsultas': datos_finales_examenes_interconsultas,
                    'Motivo_calificacion': datos_finales_motivo_calificacion,
                    'Otros_relacion_documentos': $("#otros_enfermedad").val(),
                    'Sustentacion': $("#sustentacion_califi_origen").val(),
                    'Origen': $("#origen_dto_atel").val(),
                };
            }

        }


        $.ajax({
            type:'POST',
            url:'/GuardaroActualizarInfoDTOTAEL',
            data: informacion_formulario,
            success: function(response){
                if (response.parametro == "agregar_dto_atel") {
                    // $("#GuardarDTOATEL").prop("disabled", true);
                    $("#GuardarDTOATEL").addClass('d-none');
                    $("#EditarDTOATEL").addClass('d-none');
                    $("#mostrar_mensaje_agrego_dto_atel").removeClass('d-none');
                    $(".mensaje_agrego_dto_atel").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_dto_atel").addClass('d-none');
                        $(".mensaje_agrego_dto_atel").empty();
                        location.reload();
                    }, 3000);
                }
            }
        });

    });

    // Quitar el action del formulario (para cuando se da clic en el botón de evento y lo lleva a edicion de evento)
    $("#GuardarDTOATEL, #EditarDTOATEL").hover(function(){
        $("form[id^='form_DTO_ATEL']").removeAttr("action");
    });

    // Inactivar filas visuales cuando se eliminen de la pantalla para la tabla de Examenes e Interconsultas
    $(document).on('click', "a[id^='btn_remover_examen_fila_examenes_']", function(){
        var id_evento = $("#Id_Evento_dto_atel").val();
        var id_asignacion = $('#Id_Asignacion_dto_atel').val();
        var id_proceso = $("#Id_Proceso_dto_atel").val();
        let token = $("input[name='_token']").val();

        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': id_evento,
            'Id_asignacion': id_asignacion,
            'Id_proceso': id_proceso
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarExamenesInterconsultasDTOATEL',
            data: datos_fila_quitar_examen,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_examen_eliminada") {
                    $('#resultado_insercion_examen').empty();
                    $('#resultado_insercion_examen').removeClass('d-none');
                    $('#resultado_insercion_examen').addClass('alert-success');
                    $('#resultado_insercion_examen').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_examen').addClass('d-none');
                        $('#resultado_insercion_examen').removeClass('alert-success');
                        $('#resultado_insercion_examen').empty();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_examenes_interconsulta").val(response.total_registros);
                }
            }
        });        

    });

    // Inactivar filas visuales cuando se eliminen de la pantalla para la tabla de Diagnósticos Motivo Calificacion
    $(document).on('click', "a[id^='btn_remover_diagnosticos_moticalifi']", function(){
        var id_evento = $("#Id_Evento_dto_atel").val();
        var id_asignacion = $('#Id_Asignacion_dto_atel').val();
        var id_proceso = $("#Id_Proceso_dto_atel").val();
        let token = $("input[name='_token']").val();

        var datos_fila_quitar_examen = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': id_evento,
            'Id_asignacion': id_asignacion,
            'Id_proceso': id_proceso
        };
        
        $.ajax({
            type:'POST',
            url:'eliminarDiagnosticosMotivoCalificacionDTOATEL',
            data: datos_fila_quitar_examen,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_diagnostico_eliminada") {
                    $('#resultado_insercion_cie10').empty();
                    $('#resultado_insercion_cie10').removeClass('d-none');
                    $('#resultado_insercion_cie10').addClass('alert-success');
                    $('#resultado_insercion_cie10').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_cie10').addClass('d-none');
                        $('#resultado_insercion_cie10').removeClass('alert-success');
                        $('#resultado_insercion_cie10').empty();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_diagnosticos_moticalifi").val(response.total_registros);
                }
            }
        });        

    });

    // Quitar el Si como DX principal en la tabla Diagnósticos Motivo Calificación
    $(document).on('click', "input[id^='checkbox_dx_principal_visual_Cie10_']", function(){
        var fila = $(this).data("id_fila_checkbox_dx_principal_cie10_visual");
        let token = $("input[name='_token']").val();

        if ($("#checkbox_dx_principal_visual_Cie10_"+fila).is(":checked")) {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "Si",
                'Id_evento': $('#Id_Evento_dto_atel').val(),
                'Id_Asignacion': $('#Id_Asignacion_dto_atel').val(),
                'Id_proceso': $('#Id_Proceso_dto_atel').val()
            }
        } else {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "No",
                'Id_evento': $('#Id_Evento_dto_atel').val(),
                'Id_Asignacion': $('#Id_Asignacion_dto_atel').val(),
                'Id_proceso': $('#Id_Proceso_dto_atel').val()
            }
        };

        $.ajax({
            type:'POST',
            url:'/actualizarDxPrincipalDTOATEL',
            data: informacion_actualizar,
            success:function(response){
                if (response.parametro == "hecho") {
                    $("#resultado_insercion_cie10").empty();
                    $("#resultado_insercion_cie10").removeClass('d-none');
                    $("#resultado_insercion_cie10").addClass('alert-success');
                    $("#resultado_insercion_cie10").append('<strong>'+response.mensaje+'</strong>');

                    setTimeout(() => {
                        $("#resultado_insercion_cie10").addClass('d-none');
                        $("#resultado_insercion_cie10").removeClass('alert-success');
                        $("#resultado_insercion_cie10").empty();
                    }, 3000);
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
