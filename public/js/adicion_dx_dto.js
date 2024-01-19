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

    // Incialización de select2 ORIGEN DTO ATEL
    $(".origen_dto_atel").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Incialización de select2 Correspondencia

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

    // llenado de selector de motivos de solicitud
    let datos_motivo_solicitud = {
        '_token': token,
        'parametro':"motivo_solicitud"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresAdicionDx',
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

    // Listado tipo destinatario

    let datos_lista_tipo_destinatario = {
        '_token': token,
        'parametro' : "listado_destinatarios"
    };
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresAdicionDx',
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
            url:'/cargueListadoSelectoresAdicionDx',
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
            url:'/cargueListadoSelectoresAdicionDx',
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
        url:'/cargueListadoSelectoresAdicionDx',
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
    var idProcesoLider = $("#Id_Proceso_adicion_dx").val();
    let datos_lista_reviso = {
        '_token': token,
        'parametro':"lista_reviso",
        'idProcesoLider':idProcesoLider
    };

    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectoresAdicionDx',
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
        }
    });
    
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
                url:'/cargueListadoSelectoresAdicionDx',
                data: datos_tipo_evento,
                success:function(data){
                    //console.log(data);
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
        }else{
            let datos_tipo_evento = {
                '_token': token,
                'parametro':"tipo_de_evento_no"
            };
            $.ajax({
                type:'POST',
                url:'/cargueListadoSelectoresAdicionDx',
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
                url:'/cargueListadoSelectoresAdicionDx',
                data: datos_tipo_evento,
                success:function(data){
                    //console.log(data);
                    $('#tipo_evento').prop('disabled', false);
                    $('#tipo_evento').empty();
                    $('#tipo_evento').append('<option value=""></option>');
    
                    let listado_tipo_evento = Object.keys(data);
                    for (let i = 0; i < listado_tipo_evento.length; i++) {             
                        // validacion del tipo evento cuando viene de la base de datos de determinacion o adicion
                        if ($("#bd_tipo_evento").val() != "") {
                            if (data[listado_tipo_evento[i]]['Id_Evento'] == $("#bd_tipo_evento").val()) {
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }else{
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }
                        }else{
                            // validacion del tipo evento cuando viene del procedimiento almacenado de calificacionorigen
                            if (data[listado_tipo_evento[i]]['Nombre_evento'] == $("#nombre_evento_gestion_edicion").val()) {
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }else{
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }
                        }             
                    }

                    if ($("#bd_tipo_evento").val() == 1) {
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
                        
                        
                        $("#contenedor_diag_moti_califi").removeClass('d-none');
                        $("#contenedor_diag_moti_califi_adicional").removeClass('d-none');
            
                        $("#GuardarAdicionDx").removeClass('d-none');
                        $("#ActualizarAdicionDx").removeClass('d-none');

                        $("#btn_agregar_examen_fila").removeClass('d-none');
                        $("a[id^='btn_remover_examen_fila_examenes_']").removeClass('d-none');
                        
                        $("#btn_agregar_cie10_fila").removeClass('d-none');
                        $("a[id^='btn_remover_diagnosticos_moticalifi']").removeClass('d-none');
                        $("a[id^='btn_remover_cie10_fila']").removeClass('d-none');
            
                        // llenado de datos del selector origen acorde a las validaciones
                        let datos_selector_origen_val_1 = {
                            '_token': token,
                            'parametro': parametro_origen_dto_atel
                        };
                        $.ajax({
                            type:'POST',
                            url:'/cargueListadoSelectoresAdicionDx',
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
                    
                }
            });
        }else{
            let datos_tipo_evento = {
                '_token': token,
                'parametro':"tipo_de_evento_no"
            };
            $.ajax({
                type:'POST',
                url:'/cargueListadoSelectoresAdicionDx',
                data: datos_tipo_evento,
                success:function(data){
                    //console.log(data);
                    $('#tipo_evento').prop('disabled', false);
                    $('#tipo_evento').empty();
                    $('#tipo_evento').append('<option value=""></option>');
    
                    let listado_tipo_evento = Object.keys(data);
                    for (let i = 0; i < listado_tipo_evento.length; i++) {
                        // validacion del tipo evento cuando viene de la base de datos de determinacion o adicion
                        if ($("#bd_tipo_evento").val() != "") {
                            if (data[listado_tipo_evento[i]]['Id_Evento'] == $("#bd_tipo_evento").val()) {                    
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }else{
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }
                        }else{
                            // validacion del tipo evento cuando viene del procedimiento almacenado de calificacionorigen
                            if (data[listado_tipo_evento[i]]['Nombre_evento'] == $("#nombre_evento_gestion_edicion").val()) {
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'" selected>'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }else{
                                $('#tipo_evento').append('<option value="'+data[listado_tipo_evento[i]]['Id_Evento']+'">'+data[listado_tipo_evento[i]]['Nombre_evento']+'</option>');
                            }
                        }             
                    }

                    if ($("#bd_tipo_evento").val() == 4) {
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

                        
                        // $("#contenedor_diag_moti_califi").addClass('d-none');
                        // $("#contenedor_diag_moti_califi_adicional").addClass('d-none');

                        $("#GuardarAdicionDx").addClass('d-none');
                        $("#ActualizarAdicionDx").addClass('d-none');
                        
                        $("#btn_agregar_examen_fila").addClass('d-none');
                        $("a[id^='btn_remover_examen_fila_examenes_']").addClass('d-none');
                        
                        $("#btn_agregar_cie10_fila").addClass('d-none');
                        $("a[id^='btn_remover_diagnosticos_moticalifi']").addClass('d-none');
                        $("a[id^='btn_remover_cie10_fila']").addClass('d-none');
                        
                        // llenado de datos del selector origen acorde a las validaciones
                        let datos_selector_origen_val_1 = {
                            '_token': token,
                            'parametro': parametro_origen_dto_atel
                        };
                        $.ajax({
                            type:'POST',
                            url:'/cargueListadoSelectoresAdicionDx',
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
                }
            });
        }
    });

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
            
            
            $("#contenedor_diag_moti_califi").removeClass('d-none');
            $("#contenedor_diag_moti_califi_adicional").removeClass('d-none');

            $("#GuardarAdicionDx").removeClass('d-none');
            $("#ActualizarAdicionDx").removeClass('d-none');

            $("#btn_agregar_examen_fila").removeClass('d-none');
            $("a[id^='btn_remover_examen_fila_examenes_']").removeClass('d-none');
            
            $("#btn_agregar_cie10_fila").removeClass('d-none');
            $("a[id^='btn_remover_diagnosticos_moticalifi']").removeClass('d-none');
            $("a[id^='btn_remover_cie10_fila']").removeClass('d-none');

            // llenado de datos del selector origen acorde a las validaciones
            let datos_selector_origen_val_1 = {
                '_token': token,
                'parametro': parametro_origen_dto_atel
            };
            $.ajax({
                type:'POST',
                url:'/cargueListadoSelectoresAdicionDx',
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
        
        // Validacion N°2: Activo = Si y Tipo de Evento = Sin Cobertura
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

            
            // $("#contenedor_diag_moti_califi").addClass('d-none');
            // $("#contenedor_diag_moti_califi_adicional").addClass('d-none');

            $("#GuardarAdicionDx").addClass('d-none');
            $("#ActualizarAdicionDx").addClass('d-none');

            $("#btn_agregar_examen_fila").addClass('d-none');
            $("a[id^='btn_remover_examen_fila_examenes_']").addClass('d-none');
            
            $("#btn_agregar_cie10_fila").addClass('d-none');
            $("a[id^='btn_remover_diagnosticos_moticalifi']").addClass('d-none');
            $("a[id^='btn_remover_cie10_fila']").addClass('d-none');
            
            // llenado de datos del selector origen acorde a las validaciones
            let datos_selector_origen_val_1 = {
                '_token': token,
                'parametro': parametro_origen_dto_atel
            };
            $.ajax({
                type:'POST',
                url:'/cargueListadoSelectoresAdicionDx',
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
            
            
            $("#contenedor_diag_moti_califi").removeClass('d-none');
            $("#contenedor_diag_moti_califi_adicional").removeClass('d-none');

            $("#GuardarAdicionDx").removeClass('d-none');
            $("#ActualizarAdicionDx").removeClass('d-none');

            $("#btn_agregar_examen_fila").removeClass('d-none');
            $("a[id^='btn_remover_examen_fila_examenes_']").removeClass('d-none');
            
            $("#btn_agregar_cie10_fila").removeClass('d-none');
            $("a[id^='btn_remover_diagnosticos_moticalifi']").removeClass('d-none');
            $("a[id^='btn_remover_cie10_fila']").removeClass('d-none');

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

            
            // $("#contenedor_diag_moti_califi").addClass('d-none');
            // $("#contenedor_diag_moti_califi_adicional").addClass('d-none');

            $("#GuardarAdicionDx").addClass('d-none');
            $("#ActualizarAdicionDx").addClass('d-none');

            $("#btn_agregar_examen_fila").addClass('d-none');
            $("a[id^='btn_remover_examen_fila_examenes_']").addClass('d-none');
            
            $("#btn_agregar_cie10_fila").addClass('d-none');
            $("a[id^='btn_remover_diagnosticos_moticalifi']").addClass('d-none');
            $("a[id^='btn_remover_cie10_fila']").addClass('d-none');

        }


        // llenado de datos del selector origen acorde a la validación N°1
        let datos_selector_origen_val_1 = {
            '_token': token,
            'parametro': parametro_origen_dto_atel
        };
        $.ajax({
            type:'POST',
            url:'/cargueListadoSelectoresAdicionDx',
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
                        $('#origen_dto_atel').append('<option value="'+data[listado_origen_dto_atel[i]]['Id_Parametro']+'" selected>'+data[listado_origen_dto_atel[i]]['Nombre_parametro']+'</option>');
                    }
                }
            }
        });
    });

    // VERIFICACION DE SELECTOR MORTAL EN CASO DE QUE EXISTA INFORMACIÓN GUARDADA
    var mortal_opt = "";

    var verificacion_mortal= $("#mortal").val();
    if (verificacion_mortal == "Si") {
        $("#mostrar_f_fallecimiento").removeClass("d-none");
        mortal_opt = verificacion_mortal;
    } else if(verificacion_mortal == "No") {
        $("#mostrar_f_fallecimiento").addClass("d-none");
        mortal_opt = "No";
    } else{
        mortal_opt = "";
    }


    // Inactivar filas visuales cuando se eliminen de la pantalla para la tabla de Diagnósticos Adicionados
    $(document).on('click', "a[id^='btn_remover_diagnosticos_moticalifi']", function(){
        var id_evento = $("#Id_Evento").val();
        var id_asignacion = $('#Id_Asignacion_adicion_dx').val();
        var id_proceso = $("#Id_Proceso_adicion_dx").val();
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

    // Quitar el Si como DX principal en la tabla Diagnósticos Adicionados
    $(document).on('click', "input[id^='checkbox_dx_principal_visual_Cie10_']", function(){
        var fila = $(this).data("id_fila_checkbox_dx_principal_cie10_visual");
        var id_asig_fila = $(this).data("id_asig_checkbox_dx_principal_cie10_visual");
        var id_proce_fila = $(this).data("id_proce_checkbox_dx_principal_cie10_visual");
        let token = $("input[name='_token']").val();

        if ($("#checkbox_dx_principal_visual_Cie10_"+fila).is(":checked")) {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "Si",
                'Id_evento': $('#Id_Evento').val(),
                'Id_Asignacion': id_asig_fila,
                'Id_proceso': id_proce_fila
            }
        } else {
            var informacion_actualizar = {
                '_token': token,
                'fila':fila,
                'bandera': "No",
                'Id_evento': $('#Id_Evento').val(),
                'Id_Asignacion': id_asig_fila,
                'Id_proceso': id_proce_fila
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

    // Inactivar filas visuales cuando se eliminen de la pantalla para la tabla de Examenes e Interconsultas
    $(document).on('click', "a[id^='btn_remover_examen_fila_examenes_']", function(){
        var id_evento = $("#Id_Evento").val();
        var id_asignacion = $('#Id_Asignacion_adicion_dx').val();
        var id_proceso = $("#Id_Proceso_adicion_dx").val();
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


    // Envío de la información
    $("#form_Adicion_Dx").submit(function(e){
        e.preventDefault();

        // Captura del Id_evento
        var id_evento = $("#Id_Evento").val();
        // caputra del id de asignacion y id proceso de la adicion dx
        var id_asignacion_adicion_dx = $("#Id_Asignacion_adicion_dx").val();
        var id_proceso_adicion_dx = $("#Id_Proceso_adicion_dx").val();

        // Captura del id de la determinación dto
        var id_dto_atel = $("#id_dto_atel").val();

        let token = $("input[name='_token']").val();

        var tipo_evento = $("#tipo_evento").val();

        if (tipo_evento == 1) {
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
            var datos_finales_adiciones_calificacion = [];
            // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
            $('#listado_diagnostico_cie10 tbody tr').each(function (index) {
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
                        if((index2+1) % 8 === 0){
                            datos_finales_adiciones_calificacion.push(guardar_datos_motivo_calificacion);
                            guardar_datos_motivo_calificacion = [];
                        }
                    });
                }
            });

            // Validación de id_asignacion_dx para saber si toca actualizar la información
            var id_adicion_dx = $("#id_adicion_dx").val();

            if (id_adicion_dx == "" || id_adicion_dx == undefined) {
                // Registrar Información
                var informacion_formulario = {
                    '_token': token,
                    'ID_Evento': id_evento,
                    'Id_Asignacion': id_asignacion_adicion_dx,
                    'Id_proceso': id_proceso_adicion_dx,
                    'Id_Dto_ATEL': id_dto_atel,
                    'Activo': $("#es_activo").val(),
                    'Tipo_evento': tipo_evento,
                    'motivo_solicitud': $("#motivo_solicitud").val(),
                    'Relacion_documentos': relacion_docs_dto_atel,
                    'Examenes_interconsultas': datos_finales_examenes_interconsultas,
                    'Adicion_motivo_calificacion': datos_finales_adiciones_calificacion,
                    'Otros_relacion_documentos': $("#otros_docs").val(),
                    'Sustentacion_Adicion_Dx': $("#sustentacion_adicion_dx").val(),
                    'Origen': $("#origen_dto_atel").val(),
                    'radicado_dictamen': $("#radicado_dictamen").val(),                    
                };
            } else {
                // Actualizar Información
                var informacion_formulario = {
                    '_token': token,
                    'Id_Adiciones_Dx': id_adicion_dx,
                    'ID_Evento': id_evento,
                    'Id_Asignacion': id_asignacion_adicion_dx,
                    'Id_proceso': id_proceso_adicion_dx,
                    'Id_Dto_ATEL': id_dto_atel,
                    'Activo': $("#es_activo").val(),
                    'Tipo_evento': tipo_evento,
                    'motivo_solicitud': $("#motivo_solicitud").val(),
                    'Relacion_documentos': relacion_docs_dto_atel,
                    'Examenes_interconsultas': datos_finales_examenes_interconsultas,
                    'Adicion_motivo_calificacion': datos_finales_adiciones_calificacion,
                    'Otros_relacion_documentos': $("#otros_docs").val(),
                    'Sustentacion_Adicion_Dx': $("#sustentacion_adicion_dx").val(),
                    'Origen': $("#origen_dto_atel").val(),
                };
            }


            $.ajax({
                type:'POST',
                url:'/GuardaroActualizarInfoAdicionDX',
                data: informacion_formulario,
                success: function(response){
                    if (response.parametro == "agregar_dto_atel") {
                        $("#GuardarAdicionDx").addClass('d-none');
                        $("#ActualizarAdicionDx").addClass('d-none');
                        $("#mostrar_mensaje_agrego_adicion_dx").removeClass('d-none');
                        $(".mensaje_agrego_adicion_dx").append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(() => {
                            $("#mostrar_mensaje_agrego_adicion_dx").addClass('d-none');
                            $(".mensaje_agrego_adicion_dx").empty();
                            location.reload();
                        }, 3000);
                    }
                }
            });
        }

    });

    if ($('#ActualizarAdicionDx').length) {
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

        var Id_Evento = $('#Id_Evento').val();
        var Id_Proceso_adicion_dx = $('#Id_Proceso_adicion_dx').val();
        var Id_Asignacion_adicion_dx  = $('#Id_Asignacion_adicion_dx').val();
        var visar = $('#visar').val();
        var profesional_comite = $('#profesional_comite').val();
        var f_visado_comite = $('#f_visado_comite').val();
       
        var datos_comiteInterdisciplianario={
            '_token': token,            
            'Id_Evento':Id_Evento,
            'Id_Proceso_adicion_dx':Id_Proceso_adicion_dx,
            'Id_Asignacion_adicion_dx':Id_Asignacion_adicion_dx,
            'visar':visar,
            'profesional_comite':profesional_comite,
            'f_visado_comite':f_visado_comite,
        }

        $.ajax({    
            type:'POST',
            url:'/guardarcomitesinterdisciplinarioADX',
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

    // Validar cual de los oficios esta marcado

    var oficiopclcorres = $('#oficiopcl');
    var oficioincacorres = $('#oficioinca');
    
    oficiopclcorres.change(function(){
        if ($(this).prop('checked')) {
            oficioincacorres.prop('disabled', true);            
        }else{
            oficioincacorres.prop('disabled', false);            
        }
    });

    oficioincacorres.change(function(){
        if ($(this).prop('checked')) {
            oficiopclcorres.prop('disabled', true);            
        }else{
            oficiopclcorres.prop('disabled', false);            
        }
    });

    if (oficiopclcorres.prop('checked')) {
        oficioincacorres.prop('disabled', true);                    
    }
    if (oficioincacorres.prop('checked')) {
        oficiopclcorres.prop('disabled', true);                    
    }
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

    /* funcionalidad para insertar la etiqueta de los cie10 */
    $("#cuerpo_comunicado").summernote({
        height: 'auto',
        toolbar: false
    });
    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");

    $("#btn_insertar_cie10").click(function(e){
        e.preventDefault();

        var etiqueta = "{{$diagnosticos_cie10}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta);
    });

    //Captura Formulario Correspondencia
    $('#form_correspondencia').submit(function (e){
        e.preventDefault();              
       
        var Id_Evento = $('#Id_Evento').val();
        var Id_Proceso_adicion_dx = $('#Id_Proceso_adicion_dx').val();
        var Id_Asignacion_adicion_dx  = $('#Id_Asignacion_adicion_dx').val();
        var oficiopcl = $('input[name="oficiopcl"]:checked').val();
        var oficioinca = $('input[name="oficioinca"]:checked').val();
        if (oficiopcl == undefined) {
            oficiopcl = '';
        }
        if(oficioinca == undefined){
            oficioinca = '';
        }
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
        var empleador = $('input[name="empleador"]:checked').val();;        
        var eps = $('input[name="eps"]:checked').val();
        var afp = $('input[name="afp"]:checked').val();
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
            'Id_Evento':Id_Evento,
            'Id_Proceso_adicion_dx':Id_Proceso_adicion_dx,
            'Id_Asignacion_adicion_dx':Id_Asignacion_adicion_dx,            
            'oficiopcl':oficiopcl,
            'oficioinca':oficioinca,
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
            'empleador':empleador,
            'eps':eps,
            'afp':afp,
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
            url:'/guardarcorrespondenciaADX',
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

    // Captura Formulario PDF Notificación DML ORIGEN
    $("form[id^='Form_noti_dml_origen_pdf_']").submit(function (e){
        e.preventDefault();              
       
        var tupla_comunicado = $(this).data("tupla_comunicado");

        // Captura de variables del formulario
        var id_tupla_comunicado = $("#id_tupla_comunicado_"+tupla_comunicado).val();
        var asunto = $("#asunto_proforma_dml_"+tupla_comunicado).val();
        var cuerpo = $("#cuerpo_comunicado").val();
        // var nro_radicado = $("#nro_radicado").val();
        var tipo_identificacion = $("#tipo_identificacion_"+tupla_comunicado).val();
        var num_identificacion = $("#num_identificacion_"+tupla_comunicado).val();
        var nro_siniestro = $("#nro_siniestro_"+tupla_comunicado).val();
        var ciudad = $("#ciudad_"+tupla_comunicado).val();
        var fecha = $("#fecha_"+tupla_comunicado).val();
        var nombre_afiliado = $("#nombre_afiliado_"+tupla_comunicado).val();
        var direccion_afiliado = $("#direccion_afiliado_"+tupla_comunicado).val();
        var telefono_afiliado = $("#telefono_afiliado_"+tupla_comunicado).val();
        var Id_Asignacion_consulta_dx = $("#Id_Asignacion_consulta_dx_"+tupla_comunicado).val();
        var Id_Proceso_consulta_dx = $("#Id_Proceso_consulta_dx_"+tupla_comunicado).val();
        var nombre_evento = $("#nombre_evento_"+tupla_comunicado).val();
        //checkbox de Copias de partes interesadas
        var copia_empleador = $('#empleador').filter(":checked").val();
        var copia_eps = $('#eps').filter(":checked").val();
        var copia_afp = $('#afp').filter(":checked").val();
        var copia_arl = $('#arl').filter(":checked").val();
        var firmar = $('#firmar').filter(":checked").val();
        var Id_cliente_firma = $('#Id_cliente_firma_'+tupla_comunicado).val();

        datos_generacion_pdf_noti_dml_origen = {
            '_token': token, 
            'id_tupla_comunicado': id_tupla_comunicado,
            'asunto': asunto,
            'cuerpo': cuerpo,
            // 'nro_radicado': nro_radicado,
            'tipo_identificacion': tipo_identificacion,
            'num_identificacion': num_identificacion,
            'nro_siniestro': nro_siniestro,
            'ciudad': ciudad,
            'fecha': fecha,
            'nombre_afiliado': nombre_afiliado,
            'direccion_afiliado': direccion_afiliado,
            'telefono_afiliado': telefono_afiliado,
            'Id_Asignacion_consulta_dx': Id_Asignacion_consulta_dx,
            'Id_Proceso_consulta_dx': Id_Proceso_consulta_dx,
            'nombre_evento': nombre_evento,
            'copia_empleador': copia_empleador,
            'copia_eps': copia_eps,
            'copia_afp': copia_afp,
            'copia_arl': copia_arl,
            'firmar': firmar,
            'Id_cliente_firma': Id_cliente_firma
        };
        
        $.ajax({    
            type:'POST',
            url:'/DescargaProformaNotiDML',
            data: datos_generacion_pdf_noti_dml_origen,
            xhrFields: {
                responseType: 'blob' // Indica que la respuesta es un blob
            },
            success: function (response, status, xhr) {
                var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
        
                // Crear un enlace de descarga similar al ejemplo anterior
                var nombre_pdf = "ORI_DML_"+Id_Asignacion_consulta_dx+"_"+num_identificacion+".pdf";
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
            }       
        });
    });
    
    // Captura Formulario PDF DML ORIGEN ATEL
    $("form[id^='Form_dml_origen_pdf_']").submit(function (e){
        e.preventDefault();              
       
        var tupla_comunicado = $(this).data("tupla_comunicado");

        var num_identificacion = $("#num_identificacion_"+tupla_comunicado).val();
        var id_evento = $("#id_evento_"+tupla_comunicado).val();
        var Id_Asignacion = $("#Id_Asignacion_"+tupla_comunicado).val();
        var Id_Proceso = $("#Id_Proceso_"+tupla_comunicado).val();
        var fecha_dictamen = $("#f_dictamen_"+tupla_comunicado).val();
        var nro_dictamen = $("#nro_dictamen_"+tupla_comunicado).val();
        var motivo_solicitud = $("#motivo_solicitud_"+tupla_comunicado).val();
        var id_cliente = $("#Id_cliente_"+tupla_comunicado).val();
        var justi_revision_origen = $("#justi_revision_origen_"+tupla_comunicado).val();
        var nombre_evento = $("#nombre_evento_"+tupla_comunicado).val();
        var mortal = $("#mortal_"+tupla_comunicado).val();
        var f_fallecimiento = $("#f_fallecimiento_"+tupla_comunicado).val();
        var f_evento = $("#f_evento_"+tupla_comunicado).val();
        var hora_evento = $("#hora_evento_"+tupla_comunicado).val();
        var furat = $("#descrip_FURAT_"+tupla_comunicado).val();
        var origen = $("#origen_dto_atel option:selected").text();
        var sustentacion = $("#sustentacion_califi_origen_"+tupla_comunicado).val();

        datos_generacion_pdf_dml_origen = {
            '_token': token, 
            'id_evento': id_evento,
            'Id_Asignacion': Id_Asignacion,
            'Id_Proceso': Id_Proceso,
            'fecha_dictamen': fecha_dictamen,
            'nro_dictamen': nro_dictamen,
            'motivo_solicitud': motivo_solicitud,
            'id_cliente': id_cliente,
            'justi_revision_origen': justi_revision_origen,
            'nombre_evento': nombre_evento,
            'mortal': mortal,
            'f_fallecimiento': f_fallecimiento,
            'f_evento': f_evento,
            'hora_evento': hora_evento,
            'furat': furat,
            'origen': origen,
            'sustentacion': sustentacion
        };
        
        $.ajax({    
            type:'POST',
            url:'/DescargaProformaDML',
            data: datos_generacion_pdf_dml_origen,
            xhrFields: {
                responseType: 'blob' // Indica que la respuesta es un blob
            },
            success: function (response, status, xhr) {
                var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
        
                // Crear un enlace de descarga similar al ejemplo anterior
                var nombre_pdf = "ORI_OFICIO_"+Id_Asignacion+"_"+num_identificacion+".pdf";
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
            }       
        });
    });
});


/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnostico motivo de calificación*/
function funciones_elementos_fila_diagnosticos(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_"+num_consecutivo).select2({
        //width: '100%',
        width: '140px',
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
        url:'/cargueListadoSelectoresAdicionDx',
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
        url:'/cargueListadoSelectoresAdicionDx',
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
        url:'/cargueListadoSelectoresAdicionDx',
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
            url:'/cargueListadoSelectoresAdicionDx',
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