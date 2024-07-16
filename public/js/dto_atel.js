$(document).ready(function(){

    var idRol = $("#id_rol").val();

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
    // Inicializacion de select2 Correspondencia y comite interdisciplinario
    $(".tipo_destinatario_principal").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".nombre_destinatariopri").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".cual").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".reviso").select2({
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

    // Listado tipo destinatario

    let datos_lista_tipo_destinatario = {
        '_token': token,
        'parametro' : "listado_destinatarios"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_lista_tipo_destinatario,
        success:function(data) {
            $('#tipo_destinatario_principal').append('<option value="" selected>Seleccione</option>');
            //let IdCalifi_contro = $('select[name=tipo_destinatario_principal]').val();
            let partecontro = Object.keys(data);
            for (let i = 0; i < partecontro.length; i++) {
                if (data[partecontro[i]]['Id_solicitante'] == $('#db_tipo_destinatario_principal').val()) {  
                    $('#tipo_destinatario_principal').append('<option value="'+data[partecontro[i]]["Id_solicitante"]+'" selected>'+data[partecontro[i]]["Solicitante"]+'</option>');
                }else{                    
                    $('#tipo_destinatario_principal').append('<option value="'+data[partecontro[i]]["Id_solicitante"]+'">'+data[partecontro[i]]["Solicitante"]+'</option>');
                }
            }
        }        
    });

    // Listado tipo nombre destinatario

    if($('select[name=nombre_destinatariopri]').val() !== ''){
        $('#nombre_destinatariopri').prop('disabled', false);
        let id_solicitante = $('#db_tipo_destinatario_principal').val();
        let datos_listado_nombre_solicitante = {
            '_token': token,
            'parametro' : "nombre_destinatariopri",
            'id_solicitante': id_solicitante
        };
        
        $.ajax({
            type:'POST',
            url:'/cargueListadoSelectoresDTOATEL',
            data: datos_listado_nombre_solicitante,
            success:function(data) {
                let nombre_solicitanteEdicion = $('select[name=nombre_destinatariopri]').val();                
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    if (data[claves[i]]['Id_Nombre_solicitante'] == $('#db_nombre_destinatariopri').val()) {  
                        $('#nombre_destinatariopri').append('<option value="'+data[claves[i]]["Id_Nombre_solicitante"]+'" selected>'+data[claves[i]]["Nombre_solicitante"]+'</option>');
                    }else{                    
                        $('#nombre_destinatariopri').append('<option value="'+data[claves[i]]["Id_Nombre_solicitante"]+'">'+data[claves[i]]["Nombre_solicitante"]+'</option>');
                    }
                }
            }
        });
    }

    $('#tipo_destinatario_principal').change(function(){
        $('#nombre_destinatariopri').empty();
        $('#nombre_destinatariopri').prop('disabled', false);
        let id_solicitante = $('#tipo_destinatario_principal').val();
        let datos_listado_nombre_destinatariopri = {
            '_token': token,
            'parametro' : "nombre_destinatariopri",
            'id_solicitante': id_solicitante
        };
        $.ajax({
            type:'POST',
            url:'/cargueListadoSelectoresDTOATEL',
            data: datos_listado_nombre_destinatariopri,
            success:function(data) {
                $('#nombre_destinatariopri').append('<option value="" selected>Seleccione</option>');
                //let IdCalifi_contro = $('select[name=nombre_destinatariopri]').val();
                let partecontro = Object.keys(data);
                for (let i = 0; i < partecontro.length; i++) {
                    //console.log(data[partecontro[i]]['Id_Nombre_solicitante']);
                    if (data[partecontro[i]]['Id_Nombre_solicitante'] == $('#db_nombre_destinatariopri').val()) {  
                        $('#nombre_destinatariopri').append('<option value="'+data[partecontro[i]]["Id_Nombre_solicitante"]+'" selected>'+data[partecontro[i]]["Nombre_solicitante"]+'</option>');
                    }else{                    
                        $('#nombre_destinatariopri').append('<option value="'+data[partecontro[i]]["Id_Nombre_solicitante"]+'">'+data[partecontro[i]]["Nombre_solicitante"]+'</option>');
                    }
                }
            }
        });
    });

    //Listado juntas regional Correspondencia
    let datos_lista_regional_junta = {
        '_token': token,
        'parametro':"lista_regional_junta"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_lista_regional_junta,
        success:function(data) {
            //console.log(data);
            let IdJunta = $('select[name=cual]').val();
            let primercali = Object.keys(data);
            for (let i = 0; i < primercali.length; i++) {
                if (data[primercali[i]]['Id_juntaR'] != IdJunta) {  
                    $('#cual').append('<option value="'+data[primercali[i]]["Ciudad_Junta"]+'">'+data[primercali[i]]["Ciudad_Junta"]+'</option>');
                }
            }
        }
    });

    // Listado Reviso (Lideres del grupos de trabajo segun proceso PCL)
    var idProcesoLider = $("#Id_Proceso_dto_atel").val();
    let datos_lista_reviso = {
        '_token': token,
        'parametro':"lista_reviso",
        'idProcesoLider':idProcesoLider
    };
    
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresDTOATEL',
        data: datos_lista_reviso,
        success:function(data){
            //console.log(data);
            let NombreReviso = $('select[name=reviso]').val();
            let nombreRevisoPcl = Object.keys(data);
            for (let i = 0; i < nombreRevisoPcl.length; i++) {
                if (data[nombreRevisoPcl[i]]['name'] != NombreReviso) {                    
                    $('#reviso').append('<option value="'+data[nombreRevisoPcl[i]]['name']+'">'+data[nombreRevisoPcl[i]]['name']+'</option>');
                }
            }
            $("#reviso").prop("selectedIndex", 1);
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

    /* Validacion botón para guardar o actualizar la sección Información del evento
    para quitar el required de los demás campos del formulario */
    var btn_guardar_info_evento = 0;
    $("#btn_guardar_info_evento").click(function(){
        
        var btn_seccion_info_evento = $('#btn_guardar_info_evento').val();
        
        if (btn_seccion_info_evento == "Guardar" || btn_seccion_info_evento == "Actualizar") {
            // campos sección Justificación para revisión del Origen
            $("#justificacion_revision_origen").prop("required", true);
            // campos sección Calificación del Origen
            $("#sustentacion_califi_origen").prop("required", true);
            $("#origen_dto_atel").prop("required", true);

            btn_guardar_info_evento = 1; 
        }
    });

    /* Validación botón para guardar o actualizar la sección Justificación para revisión del Origen
    para quitar el required de los demás campos del formulario */
    var btn_guardar_justi_revi_ori = 0;
    $("#btn_guardar_justi_revi_ori").click(function(){
        
        var btn_seccion_justi_revi_ori = $('#btn_guardar_justi_revi_ori').val();
        
        if (btn_seccion_justi_revi_ori == "Guardar" || btn_seccion_justi_revi_ori == "Actualizar") {
            // campos sección Información del evento
            $("#tipo_accidente").prop("required", false);
            $("#fecha_evento").prop("required", false);
            $("#hora_evento").prop("required", false);
            $("#grado_severidad").prop("required", false);
            $("#fecha_fallecimiento").prop("required", false);
            $("#descripcion_FURAT").prop("required", false);
            // campos sección Calificación del Origen
            $("#sustentacion_califi_origen").prop("required", true);
            $("#origen_dto_atel").prop("required", true);

            btn_guardar_justi_revi_ori = 1;
        }
    });

    /* Validación botón para guardar o actualizar la sección Relación de documentos - Ayudas Diagnósticas e Interconsultas
    para quitar el required de los demás campos del formulario */
    var btn_guardar_relacion_docs = 0;
    $("#btn_guardar_relacion_docs").click(function(){

        var btn_seccion_relacion_docs = $('#btn_guardar_relacion_docs').val();

        if (btn_seccion_relacion_docs == "Guardar" || btn_seccion_relacion_docs == "Actualizar") {
            // campos sección Información del evento
            $("#tipo_accidente").prop("required", false);
            $("#fecha_evento").prop("required", false);
            $("#hora_evento").prop("required", false);
            $("#grado_severidad").prop("required", false);
            $("#fecha_fallecimiento").prop("required", false);
            $("#descripcion_FURAT").prop("required", false);
            // campos sección Justificación para revisión del Origen
            $("#justificacion_revision_origen").prop("required", true);
            // campos sección Calificación del Origen
            $("#sustentacion_califi_origen").prop("required", true);
            $("#origen_dto_atel").prop("required", true);

            btn_guardar_relacion_docs = 1;
        }
    });

    /* Validación botón para guardar o actualizar la sección Diagnóstico motivo de calificación
    para quitar el required de los demás campos del formulario */
    var btn_guardar_diagnosticos_mot_cali = 0;
    $("#btn_guardar_diagnosticos_mot_cali").click(function(){

        var btn_seccion_diagnosticos_mot_cali = $('#btn_guardar_diagnosticos_mot_cali').val();

        if (btn_seccion_diagnosticos_mot_cali == "Guardar" || btn_seccion_diagnosticos_mot_cali == "Actualizar") {
            // campos sección Información del evento
            $("#tipo_accidente").prop("required", false);
            $("#fecha_evento").prop("required", false);
            $("#hora_evento").prop("required", false);
            $("#grado_severidad").prop("required", false);
            $("#fecha_fallecimiento").prop("required", false);
            $("#descripcion_FURAT").prop("required", false);
            // campos sección Justificación para revisión del Origen
            $("#justificacion_revision_origen").prop("required", true);
            // campos sección Calificación del Origen
            $("#sustentacion_califi_origen").prop("required", true);
            $("#origen_dto_atel").prop("required", true);

            btn_guardar_diagnosticos_mot_cali = 1;
        }
    });

    //Cargar comunicado
    $('#cargarComunicado').click(function(){
        if(!$('#cargue_comunicados')[0].files[0]){
            return $(".cargueundocumentoprimero").removeClass('d-none');
        }
        $(".cargueundocumentoprimero").addClass('d-none');
        var archivo = $('#cargue_comunicados')[0].files[0];
        var documentName = archivo.name;
        var formData = new FormData($('form')[0]);
        formData.append('cargue_comunicados', archivo);
        formData.append('token', $("input[name='_token']").val());
        formData.append('ciudad', 'N/A');
        formData.append('Id_evento',$("#Id_Evento_dto_atel").val());
        formData.append('Id_asignacion',$('#Id_Asignacion_dto_atel').val());
        formData.append('Id_procesos',$("#Id_Proceso_dto_atel").val());
        formData.append('fecha_comunicado2',null);
        formData.append('radicado2',$('#radicado_comunicado_manual').val());
        formData.append('cliente_comunicado2','N/A');
        formData.append('nombre_afiliado_comunicado2','N/A');
        formData.append('tipo_documento_comunicado2','N/A');
        formData.append('identificacion_comunicado2','N/A');
        formData.append('destinatario', 'N/A');
        formData.append('nombre_destinatario','N/A');
        formData.append('nic_cc','N/A');
        formData.append('direccion_destinatario','N/A');
        formData.append('telefono_destinatario',1);
        formData.append('email_destinatario','N/A');
        formData.append('departamento_destinatario',1);
        formData.append('ciudad_destinatario',1);
        formData.append('asunto',documentName);
        formData.append('cuerpo_comunicado','N/A');
        formData.append('anexos',0);
        formData.append('forma_envio',0);
        formData.append('reviso',0);
        formData.append('firmarcomunicado',null);
        formData.append('tipo_descarga', 'Manual');
        formData.append('Nombre_documento', documentName);
        formData.append('modulo_creacion','determinacionOrigenATEL');
        formData.append('modulo','Comunicados determinacion origen ATEL');
        $.ajax({
            type:'POST',
            url:'/registrarComunicadoOrigen',
            data: formData,   
            processData: false,
            contentType: false,   
            beforeSend:  function() {
                $("#cargarComunicado").addClass("descarga-deshabilitada");
            },      
            success:function(response){
                if (response.parametro == 'agregar_comunicado') {
                    $('.alerta_externa_comunicado').removeClass('d-none');
                    $('.alerta_externa_comunicado').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_externa_comunicado').addClass('d-none');
                        $('.alerta_externa_comunicado').empty();
                        location.reload();
                    }, 3000);
                }
            },
            complete:function(){
                $("#cargarComunicado").removeClass("descarga-deshabilitada");
            }
        });  
    }); 

    //Descargar archivo cargado manualmente
    $("form[id^='form_descargar_archivo_']").submit(function (e){
        e.preventDefault();              
        var archivo = $(this).data("archivo");

        var nombre_documento = archivo.Asunto;
        var idEvento = archivo.ID_evento;
        var enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = '/descargar-archivo/'+nombre_documento+'/'+idEvento;     
        enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
        enlaceDescarga.style.display = 'none';
        document.body.appendChild(enlaceDescarga);
    
        // Simular clic en el enlace para iniciar la descarga
        enlaceDescarga.click();
    
        // Eliminar el enlace después de la descarga
        setTimeout(function() {
            document.body.removeChild(enlaceDescarga);
        }, 1000);
    });
    // Envío de información
    $("#form_DTO_ATEL").submit(function(e){
        e.preventDefault();

        $("#btn_guardar_info_evento").prop("disabled", true);
        $("#btn_guardar_justi_revi_ori").prop("disabled", true);
        $("#btn_guardar_relacion_docs").prop("disabled", true);
        $("#btn_guardar_diagnosticos_mot_cali").prop("disabled", true);

        var GuardarDTOATEL = $('#GuardarDTOATEL');
        var EditarDTOATEL = $('#EditarDTOATEL');

        if (GuardarDTOATEL.length > 0) {
            document.querySelector('#GuardarDTOATEL').disabled=true;            
        }
        if (EditarDTOATEL.length > 0) {
            document.querySelector('#EditarDTOATEL').disabled=true;
        }

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
                    'N_siniestro': $("#n_siniestro").val(),                     
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
                    'radicado_dictamen': $("#radicado_dictamen").val(),
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
                    'N_siniestro': $("#n_siniestro").val(), 
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
                    'radicado_dictamen': $("#radicado_dictamen").val(),
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
                    'N_siniestro': $("#n_siniestro").val(), 
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
                    'radicado_dictamen': $("#radicado_dictamen").val(),                    
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
                    'N_siniestro': $("#n_siniestro").val(), 
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
                    'radicado_dictamen': $("#radicado_dictamen").val(),
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
                    if (btn_guardar_info_evento > 0) {
                        $("#btn_guardar_info_evento").addClass('d-none');
                        $("#mostrar_mensaje_1").removeClass('d-none');
                        $(".mensaje_agrego_1").append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(() => {
                            $("#mostrar_mensaje_1").addClass('d-none');
                            $(".mensaje_agrego_1").empty();
                            location.reload();
                        }, 3000);
                    }

                    else if(btn_guardar_justi_revi_ori > 0){
                        $("#btn_guardar_justi_revi_ori").addClass('d-none');
                        $("#mostrar_mensaje_2").removeClass('d-none');
                        $(".mensaje_agrego_2").append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(() => {
                            $("#mostrar_mensaje_2").addClass('d-none');
                            $(".mensaje_agrego_2").empty();
                            location.reload();
                        }, 3000);
                    }

                    else if(btn_guardar_relacion_docs > 0){
                        $("#btn_guardar_relacion_docs").addClass('d-none');
                        $("#mostrar_mensaje_3").removeClass('d-none');
                        $(".mensaje_agrego_3").append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(() => {
                            $("#mostrar_mensaje_3").addClass('d-none');
                            $(".mensaje_agrego_3").empty();
                            location.reload();
                        }, 3000);
                    }

                    else if(btn_guardar_diagnosticos_mot_cali > 0){
                        $("#btn_guardar_diagnosticos_mot_cali").addClass('d-none');
                        $("#mostrar_mensaje_4").removeClass('d-none');
                        $(".mensaje_agrego_4").append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(() => {
                            $("#mostrar_mensaje_4").addClass('d-none');
                            $(".mensaje_agrego_4").empty();
                            location.reload();
                        }, 3000);
                    }
                    
                    else{
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

    if ($('#EditarDTOATEL').length) {
        $('#div_comite_interdisciplinario').removeClass('d-none');
        $('#div_comunicado_dictamen_oficioremisorio').removeClass('d-none');
    }

    // Captura del nombre del usuario que marca el checkbox Visar
    var NombreUsuario = $("#NombreUsuario").val();
    var Visar = $("#visar");

    Visar.change(function(){
        if ($(this).prop('checked')) {
            $("#profesional_comite").val(NombreUsuario);            
        } else {            
            $("#profesional_comite").val('');            
        }
    });    
    
    //Captura Formulario Comite Interdisciplinario
    $('#form_comite_interdisciplinario').submit(function (e){
        e.preventDefault();              
       
        var Id_Evento_dto_atel = $('#Id_Evento_dto_atel').val();
        var Id_Proceso_dto_atel = $('#Id_Proceso_dto_atel').val();
        var Id_Asignacion_dto_atel  = $('#Id_Asignacion_dto_atel').val();
        var visar = $('#visar').val();
        var profesional_comite = $('#profesional_comite').val();
        var f_visado_comite = $('#f_visado_comite').val();
       
        var datos_comiteInterdisciplianario={
            '_token': token,            
            'Id_Evento_dto_atel':Id_Evento_dto_atel,
            'Id_Proceso_dto_atel':Id_Proceso_dto_atel,
            'Id_Asignacion_dto_atel':Id_Asignacion_dto_atel,
            'visar':visar,
            'profesional_comite':profesional_comite,
            'f_visado_comite':f_visado_comite,
        }

        $.ajax({    
            type:'POST',
            url:'/guardarcomitesinterdisciplinarioDTO',
            data: datos_comiteInterdisciplianario,
            success: function(response){
                if (response.parametro == 'insertar_comite_interdisciplinario') {
                    $('#GuardarComiteInter').prop('disabled', true);
                    $('#div_alerta_comiteInter').removeClass('d-none');
                    $('.alerta_comiteInter').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_comiteInter').addClass('d-none');
                        $('.alerta_comiteInter').empty();   
                        location.reload();
                    }, 3000);   
                }
            }          
        })
    }) 

    var profesional_comite = $("#profesional_comite").val();
    if (profesional_comite !== '') {
        $("#GuardarComiteInter").prop('disabled', true);
        $("#div_correspondecia").removeClass('d-none');
    }

    // Desabilitar los botones si ya esta visado
    var visar_servicio = $("#visar_servicio").val();
    if (visar_servicio!== '') {
        // $("#div_correspondecia").addClass('d-none');
        // $("#EditarDTOATEL").prop('disabled', true);
    }


    // Validar cual de los oficios esta marcado

    // var oficio_origencorres = $('#oficio_origen');
    // var oficioincacorres = $('#oficioinca');
    
    /* oficio_origencorres.change(function(){
        if ($(this).prop('checked')) {
            oficioincacorres.prop('disabled', true);            
        }else{
            oficioincacorres.prop('disabled', false);            
        }
    });

    oficioincacorres.change(function(){
        if ($(this).prop('checked')) {
            oficio_origencorres.prop('disabled', true);            
        }else{
            oficio_origencorres.prop('disabled', false);            
        }
    }); */

    /* if (oficio_origencorres.prop('checked')) {
        oficioincacorres.prop('disabled', true);                    
    }
    if (oficioincacorres.prop('checked')) {
        oficio_origencorres.prop('disabled', true);                    
    } */


    // Funcionalidad para introducir el texto predeterminado para la proforma Notificación DML ORIGEN
    var entidad_conocimiento = $("#entidad_conocimiento").val();
    $('#oficio_origen').change(function(){
        if ($(this).prop('checked')) {
            var asunto_insertar = "Dictamen presunto origen evento";
            var texto_insertar = '<p>Respetados Señores</p><p>En atención a la solicitud de emisión de concepto sobre el presunto origen de la contingencia, le informamos que una vez estudiada la documentación del paciente por el comité intedisciplinario de calificación de pérdida de la capacidad laboral y origen de Seguros de Vida ALFA S.A., experto en la materia, se considera que el presunto origen de la muerte del Señor(a) {{$nombre_afiliado}}, es con ocasión de un {{$tipo_evento}} {{$origen_evento}}.</p><p>Para los efectos, se adjuntó el concepto que sustenta lo manifestado.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestra líneas de atención, al cliente en Bogotá (601) 3077032 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escríbanos a «servicioalcliente@segurosalfa.com.co»; o a la dirección Carrera 10 # 18-36 piso 4 Edificio José María Córdoba, Bogotá D.C.</p>';

            $("#Asunto").val(asunto_insertar);
            $("#cuerpo_comunicado").summernote('code', texto_insertar);

            // Habilitación etiquetas
            $("#btn_insertar_nombre_afiliado").prop('disabled', false);
            $("#btn_insertar_tipo_evento").prop('disabled', false);
            $("#btn_insertar_origen_evento").prop('disabled', false);

            // Selección automática de las copias a partes interesadas: Benficiario, Empleador, EPS, ARL
            $("#beneficiario").prop('checked', true);
            $("#empleador").prop('checked', true);
            $("#eps").prop('checked', true);
            $("#arl").prop('checked', true);

            // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
            if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
                $("#afp_conocimiento").prop('checked', true);
            }
        
            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 1;
            $("#anexos").val(seteo_nro_anexos);

            // Selección automática del checkbox firmar
            $("#firmar").prop('checked', true);

        }else{
            $("#Asunto").val('');
            $("#cuerpo_comunicado").summernote('code', '');

            // deshabilitación etiquetas
            $("#btn_insertar_nombre_afiliado").prop('disabled', true);
            $("#btn_insertar_tipo_evento").prop('disabled', true);
            $("#btn_insertar_origen_evento").prop('disabled', true);

            // Deselección automática de las copias a partes interesadas: Benficiario, Empleador, EPS, ARL
            $("#beneficiario").prop('checked', false);
            $("#empleador").prop('checked', false);
            $("#eps").prop('checked', false);
            $("#arl").prop('checked', false);

            // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
            if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
                $("#afp_conocimiento").prop('checked', false);
            }

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática del checkbox firmar
            $("#firmar").prop('checked', false);
        }
    });

    // validar si el otro destinatario principal esta marcado

    var otrodestinariop = $("#otrodestinariop");
    otrodestinariop.change(function(){
        if ($(this).prop('checked')) {     
            $('#tipo_destinatario_principal').empty();

            $(".tipo_destinatario_principal").select2({
                placeholder:"Seleccione una opción",
                allowClear:false
            });            
            
            let datos_lista_tipo_destinatario = {
                '_token': token,
                'parametro' : "listado_destinatarios"
            };
            $.ajax({
                type:'POST',
                url:'/selectoresJuntasControversia',
                data: datos_lista_tipo_destinatario,
                success:function(data) {
                    $('#tipo_destinatario_principal').append('<option value="" selected>Seleccione</option>');
                    let IdCalifi_contro = $('select[name=tipo_destinatario_principal]').val();
                    let partecontro = Object.keys(data);
                    for (let i = 0; i < partecontro.length; i++) {
                        if (data[partecontro[i]]['Id_solicitante'] == $('#db_tipo_destinatario_principal').val()) {  
                            $('#tipo_destinatario_principal').append('<option value="'+data[partecontro[i]]["Id_solicitante"]+'" selected>'+data[partecontro[i]]["Solicitante"]+'</option>');
                        }else{                    
                            $('#tipo_destinatario_principal').append('<option value="'+data[partecontro[i]]["Id_solicitante"]+'">'+data[partecontro[i]]["Solicitante"]+'</option>');
                        }
                    }
                }
                
            });

            if($('select[name=nombre_destinatariopri]').val() !== ''){
                $('#nombre_destinatariopri').prop('disabled', false);
                let id_solicitante = $('#db_tipo_destinatario_principal').val();
                let datos_listado_nombre_solicitante = {
                    '_token': token,
                    'parametro' : "nombre_destinatariopri",
                    'id_solicitante': id_solicitante
                };
                
                $.ajax({
                    type:'POST',
                    url:'/selectoresJuntasControversia',
                    data: datos_listado_nombre_solicitante,
                    success:function(data) {
                        let nombre_solicitanteEdicion = $('select[name=nombre_destinatariopri]').val();                
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            if (data[claves[i]]['Id_Nombre_solicitante'] == $('#db_nombre_destinatariopri').val()) {  
                                $('#nombre_destinatariopri').append('<option value="'+data[claves[i]]["Id_Nombre_solicitante"]+'" selected>'+data[claves[i]]["Nombre_solicitante"]+'</option>');
                            }else{                    
                                $('#nombre_destinatariopri').append('<option value="'+data[claves[i]]["Id_Nombre_solicitante"]+'">'+data[claves[i]]["Nombre_solicitante"]+'</option>');
                            }
                        }
                    }
                });
            }                      
            
            var destinatario_principal_select = $('#db_tipo_destinatario_principal').val();
            if (destinatario_principal_select == 8) {
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_datos_otro_destinatario').slideDown('slow');
                $('#div_nombre_destinatariopri').slideUp('up');                
            }else if(destinatario_principal_select == 4) {
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_datos_otro_destinatario').slideUp('up');
                $('#div_nombre_destinatariopri').slideUp('up');
                $('#div_nombre_destinatariopri_afi_').slideDown('slow');
            }else if(destinatario_principal_select == 5) {
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_datos_otro_destinatario').slideUp('up');
                $('#div_nombre_destinatariopri').slideUp('up');
                $('#div_nombre_destinatariopri_empl').slideDown('slow');
            }
            else {
                $('#tipo_destinatario_principal').prop('required', true);
                $('#nombre_destinatariopri').prop('required', true);
                $('#div_tipo_destinatario_principal').slideDown('slow');            
                $('#div_nombre_destinatariopri').slideDown('slow');
                $('#div_datos_otro_destinatario').slideUp('up');
            }             
        } else {
            $('#div_tipo_destinatario_principal').slideUp('up');
            $('#tipo_destinatario_principal').prop('required', false);
            $('#tipo_destinatario_principal').empty();
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');            
            $('#div_datos_otro_destinatario').slideUp('up'); 
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
        }
    });

    // validar si tipo de destinatario es igual a otro

    var select_destinatario_principal = $('#tipo_destinatario_principal');
    var select_destinatario_principal2 = $('#db_tipo_destinatario_principal').val();
    
    select_destinatario_principal.change(function() {
       if ($(this).val() == 8) {
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_datos_otro_destinatario').slideDown('slow');
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
       }else if ($(this).val() == 4) {
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri_afi_').slideDown('slow');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
        }else if ($(this).val() == 5) {
            $('#nombre_destinatariopri').prop('required', false); 
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideDown('slow');
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
        }else {
            $('#nombre_destinatariopri').prop('required', true); 
            $('#div_nombre_destinatariopri').slideDown('slow');
            $('#div_datos_otro_destinatario').slideUp('up');  
            $('#div_nombre_destinatariopri_afi_').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideUp('up');
        } 
    });

    if(otrodestinariop.prop('checked')){           
        $('#div_tipo_destinatario_principal').slideDown('slow');
        if (select_destinatario_principal2 == 8) {
            $('#div_datos_otro_destinatario').slideDown('slow');
            $('#div_nombre_destinatariopri').slideUp('up');
        }else if(select_destinatario_principal2 == 4) {
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_nombre_destinatariopri_afi_').slideDown('slow');
        }else if(select_destinatario_principal2 == 5) {
            $('#div_datos_otro_destinatario').slideUp('up');
            $('#div_nombre_destinatariopri').slideUp('up');
            $('#div_nombre_destinatariopri_empl').slideDown('slow');
        }
        else {
            $('#div_nombre_destinatariopri').slideDown('slow');
            $('#div_datos_otro_destinatario').slideUp('up');
        }        
    }else{
        $('#div_nombre_destinatariopri').slideUp('up'); 
        $('#div_datos_otro_destinatario').slideUp('up');
    }
    // validar si la Junta regional esta marcada
    
    var juntaregionalCi = $("#jrci");
    juntaregionalCi.change(function() {
        if ($(this).prop('checked')) {
            $('#div_cual').slideDown('slow');
            $('#cual').prop('required', true);
        }else{
            $('#div_cual').slideUp('up');  
            $('#cual').prop('required', false);
        }
    });

    if (juntaregionalCi.prop('checked')) {
        $('#div_cual').slideDown('slow');        
    }

    // validar asunto

    var asuntocorrespondencia = $("#Asunto").val();
    if (asuntocorrespondencia !== '') {
        $("#div_correspondecia").addClass('d-none');
    }

    // Habilitar formulario de correspondencia
    var editar_correspondencia = $('#editar_correspondencia');
    editar_correspondencia.click(function(){
        $("#div_correspondecia").removeClass('d-none');
    });

    /* funcionalidad para insertar la etiqueta de nombre de afiliado y origen evento */
    $("#cuerpo_comunicado").summernote({
        height: 'auto',
        toolbar: false,
        callbacks: {
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            }
        }
    });
    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");

    $("#btn_insertar_nombre_afiliado").click(function(e){
        e.preventDefault();

        var etiqueta = "{{$nombre_afiliado}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta);
    });

    $("#btn_insertar_origen_evento").click(function(e){
        e.preventDefault();

        var etiqueta = "{{$origen_evento}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta);
    });

    $("#btn_insertar_tipo_evento").click(function(e){
        e.preventDefault();

        var etiqueta = "{{$tipo_evento}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta);
    });

    //Captura Formulario Correspondencia
    $('#form_correspondencia').submit(function (e){
        e.preventDefault();              
       
        var Id_Evento_dto_atel = $('#Id_Evento_dto_atel').val();
        var Id_Proceso_dto_atel = $('#Id_Proceso_dto_atel').val();
        var Id_Asignacion_dto_atel  = $('#Id_Asignacion_dto_atel').val();
        var oficio_origen = $('input[name="oficio_origen"]:checked').val();
        // var oficioinca = $('input[name="oficioinca"]:checked').val();
        if (oficio_origen == undefined) {
            oficio_origen = '';
        }
        // if(oficioinca == undefined){
        //     oficioinca = '';
        // }
        var destinatario_principal = $('#destinatario_principal').val();        
        var otrodestinariop = $('input[name="otrodestinariop"]:checked').val();
        if (otrodestinariop == undefined) {
            var tipo_destinatario_principal = '';
            var nombre_destinatariopri = '';            
        } else {
            var tipo_destinatario_principal = $('#tipo_destinatario_principal').val();            
            var nombre_destinatariopri = $('#nombre_destinatariopri').val();
        }
        var nombre_destinatario_afi = $('#nombre_destinatario_afi').val();
        var nombre_destinatario_emp = $('#nombre_destinatario_emp').val();
        if (tipo_destinatario_principal == 4) {
            var nombre_destinatario_afi_emp = nombre_destinatario_afi;
        } else if(tipo_destinatario_principal == 5) {
            var nombre_destinatario_afi_emp = nombre_destinatario_emp;            
        }
        if(tipo_destinatario_principal == 8){
            var nombre_destinatario = $('#nombre_destinatario').val();
            var nitcc_destinatario = $('#nitcc_destinatario').val();
            var direccion_destinatario = $('#direccion_destinatario').val();
            var telefono_destinatario = $('#telefono_destinatario').val();
            var email_destinatario = $('#email_destinatario').val();
            var departamento_destinatario = $('#departamento_destinatario').val();
            var ciudad_destinatario = $('#ciudad_destinatario').val();
        }else{            
            var nombre_destinatario = '';
            var nitcc_destinatario = '';
            var direccion_destinatario = '';
            var telefono_destinatario = '';
            var email_destinatario = '';
            var departamento_destinatario = '';
            var ciudad_destinatario = '';
        }        
        var Asunto = $('#Asunto').val();
        var cuerpo_comunicado = $('#cuerpo_comunicado').val();
        var beneficiario = $('input[name="beneficiario"]:checked').val();
        var empleador = $('input[name="empleador"]:checked').val();
        var eps = $('input[name="eps"]:checked').val();
        var afp = $('input[name="afp"]:checked').val();
        cuerpo_comunicado = cuerpo_comunicado ?  cuerpo_comunicado.replace(/"/g, "'") : '';

        // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
        var afp_conocimiento = '';
        if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
            afp_conocimiento = $('input[name="afp_conocimiento"]:checked').val();
        }

        var arl = $('input[name="arl"]:checked').val();
        var jrci = $('input[name="jrci"]:checked').val();
            
        if (jrci == undefined) {
            var cual = '';                        
        } else {
            var cual = $('#cual').val();            
        }       
        var jnci = $('input[name="jnci"]:checked').val();
        var anexos = $('#anexos').val();
        var elaboro = $('#elaboro').val();
        var reviso = $('#reviso').val();
        var firmar = $('input[name="firmar"]:checked').val();
        var ciudad = $('#ciudad').val();
        var f_correspondencia = $('#f_correspondencia').val();
        var radicado = $('#radicado').val();
        var bandera_correspondecia_guardar_actualizar = $('#bandera_correspondecia_guardar_actualizar').val();
        
        var datos_correspondecia={
            '_token': token,            
            'Id_Evento_dto_atel':Id_Evento_dto_atel,
            'Id_Proceso_dto_atel':Id_Proceso_dto_atel,
            'Id_Asignacion_dto_atel':Id_Asignacion_dto_atel,            
            'oficio_origen':oficio_origen,
            // 'oficioinca':oficioinca,
            'destinatario_principal':destinatario_principal,
            'otrodestinariop' : otrodestinariop,
            'tipo_destinatario_principal' : tipo_destinatario_principal,
            'nombre_destinatariopri' : nombre_destinatariopri,
            'Nombre_dest_principal_afi_empl' : nombre_destinatario_afi_emp,
            'nombre_destinatario': nombre_destinatario,
            'nitcc_destinatario': nitcc_destinatario,
            'direccion_destinatario': direccion_destinatario,
            'telefono_destinatario': telefono_destinatario,
            'email_destinatario': email_destinatario,
            'departamento_destinatario': departamento_destinatario,
            'ciudad_destinatario': ciudad_destinatario,
            'Asunto':Asunto,
            'cuerpo_comunicado':cuerpo_comunicado,
            'beneficiario': beneficiario,
            'empleador':empleador,
            'eps':eps,
            'afp':afp,
            'afp_conocimiento': afp_conocimiento,
            'arl':arl,
            'jrci':jrci,
            'cual':cual,
            'jnci':jnci,
            'anexos':anexos,
            'elaboro':elaboro,
            'reviso':reviso,
            'firmar':firmar,
            'ciudad':ciudad,
            'f_correspondencia':f_correspondencia,
            'radicado':radicado,
            'bandera_correspondecia_guardar_actualizar':bandera_correspondecia_guardar_actualizar
        }
        
        $.ajax({    
            type:'POST',
            url:'/guardarcorrespondenciaDTO',
            data: datos_correspondecia,
            success: function(response){
                if (response.parametro == 'insertar_correspondencia') {
                    $('#GuardarCorrespondencia').prop('disabled', true);
                    $('#div_alerta_Correspondencia').removeClass('d-none');
                    $('.alerta_Correspondencia').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_Correspondencia').addClass('d-none');
                        $('.alerta_Correspondencia').empty();   
                        location.reload();
                    }, 3000);   
                }else if(response.parametro == 'actualizar_correspondencia'){
                    $('#ActualizarCorrespondencia').prop('disabled', true);
                    $('#div_alerta_Correspondencia').removeClass('d-none');
                    $('.alerta_Correspondencia').append('<strong>'+response.mensaje+'</strong>');                                            
                    setTimeout(function(){
                        $('#div_alerta_Correspondencia').addClass('d-none');
                        $('.alerta_Correspondencia').empty();   
                        location.reload();
                    }, 3000);  
                }

            }          
        })
    });

        //Reemplazar archivo 
        let comunicado_reemplazar = null;
        $("form[id^='form_reemplazar_archivo_']").submit(function (e){
            e.preventDefault();           
            $('#modalReemplazarArchivos').modal('show');  
            comunicado_reemplazar = $(this).data('archivo');
            let nombre_doc = comunicado_reemplazar.Nombre_documento;
            let nombre_doc_manual = comunicado_reemplazar.Asunto;
            if(nombre_doc != null && nombre_doc != "null"){
                extensionDoc = `.${ nombre_doc.split('.').pop()}`;
                document.getElementById('cargue_comunicados_modal').setAttribute('accept', extensionDoc);
            }
            else if(nombre_doc_manual != null && nombre_doc_manual != "null"){
                extensionDoc = `.${ nombre_doc_manual.split('.').pop()}`;
                document.getElementById('cargue_comunicados_modal').setAttribute('accept', extensionDoc);
            }
        });
    
        $("form[id^='reemplazar_documento']").submit(function(e){
            e.preventDefault();
            if(!$('#cargue_comunicados_modal')[0].files[0]){
                return $(".cargueundocumentoprimeromodal").removeClass('d-none');
            }
            $(".cargueundocumentoprimeromodal").addClass('d-none');
            $(".extensionInvalidaModal").addClass('d-none');
            var archivo = $('#cargue_comunicados_modal')[0].files[0];
            extensionDocCargado = `.${archivo.name.split('.').pop()}`;
            if(extensionDoc === extensionDocCargado){
                var formData = new FormData($('form')[0]);
                formData.append('doc_de_reemplazo', archivo);
                formData.append('token', $('input[name=_token]').val());
                formData.append('id_comunicado', comunicado_reemplazar.Id_Comunicado);
                formData.append('tipo_descarga', comunicado_reemplazar.Tipo_descarga);
                formData.append('id_asignacion', comunicado_reemplazar.Id_Asignacion);
                formData.append('id_proceso', comunicado_reemplazar.Id_proceso);
                formData.append('id_evento', comunicado_reemplazar.ID_evento);
                formData.append('n_radicado', comunicado_reemplazar.N_radicado);
                formData.append('numero_identificacion', comunicado_reemplazar.N_identificacion);
                formData.append('modulo_creacion', 'determinacionOrigenATEL');
                formData.append('asunto', comunicado_reemplazar.Asunto);
                formData.append('nombre_documento', comunicado_reemplazar.Nombre_documento);
                $.ajax({
                    type:'POST',
                    url:'/reemplazarDocumento',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend:  function() {
                        $("#cargarComunicadoModal").addClass("descarga-deshabilitada");
                    },
                    success:function(response){
                        if (response.parametro == 'reemplazar_comunicado') {
                            $('.alerta_externa_comunicado_modal').removeClass('d-none');
                            $('.alerta_externa_comunicado_modal').append('<strong>'+response.mensaje+'</strong>');
                            setTimeout(function(){
                                $('.alerta_externa_comunicado_modal').addClass('d-none');
                                $('.alerta_externa_comunicado_modal').empty();
                                localStorage.setItem("#Generar_comunicados", true);
                                location.reload();
                                $("#modalReemplazarArchivos").modal('hide');
                            }, 1000);
                        }
                    },
                    complete:function(){
                        $("#cargarComunicadoModal").removeClass("descarga-deshabilitada");
                    }
                });
            }
            else{
                document.getElementById('extensionInvalidaMensaje').textContent += extensionDoc;
                return $(".extensionInvalidaModal").removeClass('d-none');
            }
        });

    // Captura Formulario DML ORIGEN PREVISIONAL (DICTAMEN)
    $("form[id^='Form_dml_origen_previsional_']").submit(function (e){
        e.preventDefault();              
        var tupla_comunicado = $(this).data("tupla_comunicado");
        var infoComunicado = $(this).data("info_comunicados");
        var id_cliente = $("#Id_cliente_"+tupla_comunicado).val();
        var num_identificacion = $("#num_identificacion_"+tupla_comunicado).val();
        var nro_siniestro = $("#nro_siniestro_"+tupla_comunicado).val();
        var Id_Asignacion = $("#Id_Asignacion_"+tupla_comunicado).val();
        var Id_Proceso = $("#Id_Proceso_"+tupla_comunicado).val();
        var f_dictamen = $("#f_dictamen_"+tupla_comunicado).val();
        var empresa_laboral = $("#empresa_laboral_"+tupla_comunicado).val();
        var nit_cc_laboral = $("#nit_cc_laboral_"+tupla_comunicado).val();
        var cargo_laboral = $("#cargo_laboral_"+tupla_comunicado).val();
        var antiguedad_cargo_laboral = $("#antiguedad_cargo_laboral_"+tupla_comunicado).val();
        var act_economica_laboral = $("#act_economica_laboral_"+tupla_comunicado).val();
        var justificacion_revision_origen = $("#justificacion_revision_origen_"+tupla_comunicado).val();
        var nombre_evento = $("#nombre_evento_"+tupla_comunicado).val();
        var f_evento = $("#f_evento_"+tupla_comunicado).val();
        var f_fallecimiento = $("#f_fallecimiento_"+tupla_comunicado).val();
        var sustentacion_califi_origen = $("#sustentacion_califi_origen_"+tupla_comunicado).val();
        var origen = $("#origen_dto_atel option:selected").text();
        var N_siniestro = $("#n_siniestro").val();

        datos_generacion_proforma_dml_previsional = {
            '_token': token,
            'id_cliente': id_cliente,
            'nro_siniestro': nro_siniestro,
            'Id_Asignacion': Id_Asignacion,
            'Id_Proceso': Id_Proceso,
            'f_dictamen': f_dictamen,
            'empresa_laboral': empresa_laboral,
            'nit_cc_laboral': nit_cc_laboral,
            'cargo_laboral': cargo_laboral,
            'antiguedad_cargo_laboral': antiguedad_cargo_laboral,
            'act_economica_laboral': act_economica_laboral,
            'justificacion_revision_origen': justificacion_revision_origen,
            'nombre_evento': nombre_evento,
            'f_evento': f_evento,
            'f_fallecimiento': f_fallecimiento,
            'sustentacion_califi_origen': sustentacion_califi_origen,
            'origen': origen,
            'id_comunicado' : infoComunicado.Id_Comunicado,
            'N_siniestro': N_siniestro,
        };
        if(infoComunicado.Reemplazado == 1){
            var nombre_doc = infoComunicado.Nombre_documento;
            var idEvento = infoComunicado.ID_evento;
            var enlaceDescarga = document.createElement('a');
            enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+idEvento;     
            enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
            enlaceDescarga.style.display = 'none';
            document.body.appendChild(enlaceDescarga);
            enlaceDescarga.click();
            setTimeout(function() {
                document.body.removeChild(enlaceDescarga);
            }, 1000);
        }else{
            $.ajax({    
                type:'POST',
                url:'/DescargaProformaDMLPrev',
                data: datos_generacion_proforma_dml_previsional,
                xhrFields: {
                    responseType: 'blob' // Indica que la respuesta es un blob
                },
                beforeSend:  function() {
                    $("#btn_enviar_dictamen_previsional").addClass("descarga-deshabilitada");
                },
                success: function (response, status, xhr) {
                    var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
            
                    // Crear un enlace de descarga similar al ejemplo anterior
                    var nombre_pdf = "ORI_DML_"+Id_Asignacion+"_"+num_identificacion+".pdf";
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = nombre_pdf;  // Reemplaza con el nombre deseado para el archivo PDF
            
                    // Adjuntar el enlace al documento y activar el evento de clic
                    document.body.appendChild(link);
                    link.click();
            
                    // Eliminar el enlace del documento
                    document.body.removeChild(link);
                },
                error: function (error) {
                    // Manejar casos de error
                    console.error('Error al descargar el PDF:', error);
                },
                complete:  function() {
                    $("#btn_enviar_dictamen_previsional").removeClass("descarga-deshabilitada");
                    if(infoComunicado.Nombre_documento == null){
                        location.reload();
                    }
                },       
            });
        }
    });
    
    // Captura Formulario PDF Notificación del DML previsional (OFICIO)
    $("form[id^='Form_noti_dml_previsional_']").submit(function (e){
        e.preventDefault();              
       
        var tupla_comunicado = $(this).data("tupla_comunicado");
        var infoComunicado = $(this).data("archivo");
        // Captura de variables del formulario
        var id_tupla_comunicado = $("#id_tupla_comunicado_"+tupla_comunicado).val();
        var id_com_inter = $("#id_com_inter_"+tupla_comunicado).val();
        var ciudad = $("#ciudad_"+tupla_comunicado).val();
        var fecha = $("#fecha_"+tupla_comunicado).val();
        var asunto = $("#asunto_proforma_dml_"+tupla_comunicado).val();
        var cuerpo = $("#cuerpo_comunicado").val();
        var tipo_identificacion = $("#tipo_identificacion_"+tupla_comunicado).val();
        var num_identificacion = $("#num_identificacion_"+tupla_comunicado).val();
        var nro_siniestro = $("#nro_siniestro_"+tupla_comunicado).val();
        var nombre_afiliado = $("#nombre_afiliado_"+tupla_comunicado).val();
        var direccion_afiliado = $("#direccion_afiliado_"+tupla_comunicado).val();
        var telefono_afiliado = $("#telefono_afiliado_"+tupla_comunicado).val();
        var nombre_afp = $("#nombre_afp_"+tupla_comunicado).val();
        var email_afp = $("#email_afp_"+tupla_comunicado).val();
        var direccion_afp = $("#direccion_afp_"+tupla_comunicado).val();
        var telefono_afp = $("#telefono_afp_"+tupla_comunicado).val();
        var ciudad_afp = $("#ciudad_afp_"+tupla_comunicado).val();
        var Id_asignacion = $("#Id_Asignacion_consulta_"+tupla_comunicado).val();
        var Id_cliente_firma = $('#Id_cliente_firma_'+tupla_comunicado).val();
        var tipo_evento = $('#nombre_evento_guardado').val();
        var origen = $("#origen_dto_atel option:selected").text();
        //checkbox de Copias de partes interesadas
        var copia_beneficiario = $('#beneficiario').filter(":checked").val();
        var copia_empleador = $('#empleador').filter(":checked").val();
        var copia_eps = $('#eps').filter(":checked").val();
        var copia_afp = $('#afp').filter(":checked").val();
        var N_siniestro = $("#n_siniestro").val();
        cuerpo = cuerpo ? cuerpo.replace(/"/g, "'") : '';
        // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
        var copia_afp_conocimiento = '';
        if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
            copia_afp_conocimiento = $('#afp_conocimiento').filter(":checked").val();
        }


        var copia_arl = $('#arl').filter(":checked").val();
        var firmar = $('#firmar').filter(":checked").val();
        var anexos = $('#anexos').val();

        datos_pdf_noti_dml_previsional = {
            '_token': token, 
            'id_tupla_comunicado': id_tupla_comunicado,
            'id_com_inter': id_com_inter,
            'ciudad': ciudad,
            'fecha': fecha,
            'asunto': asunto,
            'cuerpo': cuerpo,
            'tipo_identificacion': tipo_identificacion,
            'num_identificacion': num_identificacion,
            'nro_siniestro': nro_siniestro,
            'nombre_afiliado': nombre_afiliado,
            'direccion_afiliado': direccion_afiliado,
            'telefono_afiliado': telefono_afiliado,
            'nombre_afp': nombre_afp,
            'email_afp' : email_afp,
            'direccion_afp': direccion_afp,
            'telefono_afp': telefono_afp,
            'ciudad_afp': ciudad_afp,
            'Id_asignacion': Id_asignacion,
            'Id_cliente_firma': Id_cliente_firma,
            'origen': origen,
            'copia_beneficiario': copia_beneficiario,
            'copia_empleador': copia_empleador,
            'copia_eps': copia_eps,
            'copia_afp': copia_afp,
            'copia_afp_conocimiento': copia_afp_conocimiento,
            'copia_arl': copia_arl,
            'firmar': firmar,
            'anexos': anexos,
            'tipo_evento': tipo_evento,
            'N_siniestro': N_siniestro,
        };
        if(infoComunicado.Reemplazado == 1){
            var nombre_doc = infoComunicado.Nombre_documento;
            var idEvento = infoComunicado.ID_evento;
            var enlaceDescarga = document.createElement('a');
            enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+idEvento;     
            enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
            enlaceDescarga.style.display = 'none';
            document.body.appendChild(enlaceDescarga);
            enlaceDescarga.click();
            setTimeout(function() {
                document.body.removeChild(enlaceDescarga);
            }, 1000);
        }
        else{
            $.ajax({    
                type:'POST',
                url:'/DescargaProformaNotiDMLPrev',
                data: datos_pdf_noti_dml_previsional,
                xhrFields: {
                    responseType: 'blob' // Indica que la respuesta es un blob
                },
                beforeSend:  function() {
                    $("#enviar_form_noti_previsional").addClass("descarga-deshabilitada");
                },
                success: function (response, status, xhr) {
                    var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
            
                    // Crear un enlace de descarga similar al ejemplo anterior
                    var nombre_pdf = "ORI_OFICIO_"+Id_asignacion+"_"+num_identificacion+".pdf";
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = nombre_pdf;  // Reemplaza con el nombre deseado para el archivo PDF
            
                    // Adjuntar el enlace al documento y activar el evento de clic
                    document.body.appendChild(link);
                    link.click();
            
                    // Eliminar el enlace del documento
                    document.body.removeChild(link);
                },
                error: function (error) {
                    // Manejar casos de error
                    console.error('Error al descargar el PDF:', error);
                },
                complete:  function() {
                    $("#enviar_form_noti_previsional").removeClass("descarga-deshabilitada");
                    if(infoComunicado.Nombre_documento == null){
                        location.reload();
                    }
                },       
            });
        }
    });

    /* Funcionalidad para mostrar solo la tabla de comunicados para el rol de Consulta */
    if (idRol == 7) {
        $("#form_DTO_ATEL").addClass('d-none');
        $("#div_comite_interdisciplinario").addClass('d-none');
        $("#div_correspondecia").addClass('d-none');
        $("label[for='editar_correspondencia']").addClass('d-none');
    }

    // A los usuarios que no tengan el rol Administrador se les aplica los siguientes controles en el formulario de correspondencia:
    // inhabilita los campos nro anexos, asunto, etiquetas, cuerpo comunicado, firmar
    if (idRol != 6) {
        $("#anexos").prop('readonly', true);
        $("#Asunto").prop('readonly', true);
        $("#btn_insertar_nombre_afiliado").prop('disabled', true);
        $("#btn_insertar_tipo_evento").prop('disabled', true);
        $("#btn_insertar_origen_evento").prop('disabled', true);
        $(".note-editable").attr("contenteditable", false);
        $("#firmar").prop('disabled', true);
        if ($("#visar_servicio").val() !== '' && $("#profesional_comite").val() !== '') {
            $("#btn_guardar_info_evento").prop("disabled", true);
            $("#btn_guardar_justi_revi_ori").prop("disabled", true);
            $("#btn_guardar_relacion_docs").prop("disabled", true);
            $("#btn_guardar_diagnosticos_mot_cali").prop("disabled", true);
            $("#EditarDTOATEL").prop('disabled', true);
        }
    }

});

/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnostico motivo de calificación*/
function funciones_elementos_fila_diagnosticos(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        //width: '100%',
        width: '340px',
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
                $("#lista_Cie10_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+' - '+data[claves[i]]["Descripcion_diagnostico"]+'</option>');
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
