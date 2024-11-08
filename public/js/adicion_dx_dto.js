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

    $(".grado_severidad").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    
    $(".tipo_accidente").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".mortal").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".factor_riesgo").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".tipo_lesion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".parte_cuerpo_afectada").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".state_notificacion").select2({
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

    //Seccion informacion del evento
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
            $("#reviso").prop("selectedIndex", 1);
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

    /* Validacion botón para guardar o actualizar la sección Información del evento
    para quitar el required de los demás campos del formulario */
    var btn_guardar_info_evento = 0;
    let bandera_info_evento ;
    $("#btn_guardar_info_evento").click(function(){
        
        var btn_seccion_info_evento = $('#btn_guardar_info_evento').val();
        
        if (btn_seccion_info_evento == "Guardar" || btn_seccion_info_evento == "Actualizar") {

            bandera_info_evento = "Actualizar";
            // campos sección Diagnósticos Adicionados
            $("#sustentacion_adicion_dx").prop("required", false);
            // campos sección Calificación del Origen
            $("#origen_dto_atel").prop("required", false);

            btn_guardar_info_evento = 1; 
        }
    });

    /* Validación botón para guardar o actualizar la sección Relación de documentos - Ayudas Diagnósticas e Interconsultas
    para quitar el required de los demás campos del formulario */
    var btn_guardar_relacion_docs = 0;
    $("#btn_guardar_relacion_docs").click(function(){

        var btn_seccion_relacion_docs = $('#btn_guardar_relacion_docs').val();

        if (btn_seccion_relacion_docs == "Guardar" || btn_seccion_relacion_docs == "Actualizar") {
            // campos sección Diagnósticos Adicionados
            $("#sustentacion_adicion_dx").prop("required", false);
            // campos sección Calificación del Origen
            $("#origen_dto_atel").prop("required", false);

            btn_guardar_relacion_docs = 1;

        }
    });

    /* Validación botón para guardar o actualizar la sección Diagnóstico adicionados
    para quitar el required de los demás campos del formulario */
    var btn_guardar_diagnosticos_adicionados = 0;
    $("#btn_guardar_diagnosticos_adicionados").click(function(){

        var btn_seccion_diagnosticos_adicionados = $('#btn_guardar_diagnosticos_adicionados').val();

        if (btn_seccion_diagnosticos_adicionados == "Guardar" || btn_seccion_diagnosticos_adicionados == "Actualizar") {
            // campos sección Calificación del Origen
            $("#origen_dto_atel").prop("required", false);

            btn_guardar_diagnosticos_adicionados = 1;
        }
    });

    // Envío de la información
    // $("#form_Adicion_Dx").submit(function(e){
    //     e.preventDefault();

    //     $("#btn_guardar_info_evento").prop("disabled", true);
    //     $("#btn_guardar_relacion_docs").prop("disabled", true);
    //     $("#btn_guardar_diagnosticos_adicionados").prop("disabled", true);

    //     var GuardarAdicionDx = $('#GuardarAdicionDx');
    //     var ActualizarAdicionDx = $('#ActualizarAdicionDx');

    //     if (GuardarAdicionDx.length > 0) {
    //         document.querySelector('#GuardarAdicionDx').disabled=true;            
    //     }
    //     if (ActualizarAdicionDx.length > 0) {
    //         document.querySelector('#ActualizarAdicionDx').disabled=true;
    //     }

    //     // Captura del Id_evento
    //     var id_evento = $("#Id_Evento").val();
    //     // caputra del id de asignacion y id proceso de la adicion dx
    //     var id_asignacion_adicion_dx = $("#Id_Asignacion_adicion_dx").val();
    //     var id_proceso_adicion_dx = $("#Id_Proceso_adicion_dx").val();

    //     // Captura del id de la determinación dto
    //     var id_dto_atel = $("#id_dto_atel").val();

    //     let token = $("input[name='_token']").val();

    //     var tipo_evento = $("#tipo_evento").val();

    //     if (tipo_evento == 1) {
    //         // Creacion de array para los checkboxes de relacion de documentos
    //         var relacion_docs_dto_atel = [];
    //         $('input[type="checkbox"]').each(function() {
    //             var relacion_documento_dto_atel = $(this).attr('id');            
    //             if (relacion_documento_dto_atel === 'furat_acci_inci_sincober' || relacion_documento_dto_atel === 'historia_clinica_acci_inci_sincober') {                
    //                 if ($(this).is(':checked')) {                
    //                     var relacion_documento_dto_atel_valor = $(this).val();
    //                     relacion_docs_dto_atel.push(relacion_documento_dto_atel_valor);
    //                 }
    //             }
    //         });

    //         // Creacion de array con los datos de la tabla dinámica Exámenes e interconsultas
    //         var guardar_datos_examenes_interconsultas = [];
    //         var datos_finales_examenes_interconsultas = [];
    //         // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
    //         $('#listado_examenes_interconsultas tbody tr').each(function (index) {
    //             if ($(this).attr('id') !== "datos_examenes_interconsulta") {
    //                 $(this).children("td").each(function (index2) {
    //                     var nombres_ids = $(this).find('*').attr("id");
    //                     if (nombres_ids != undefined) {
    //                         guardar_datos_examenes_interconsultas.push($('#'+nombres_ids).val());                        
    //                     }
    //                     if((index2+1) % 3 === 0){
    //                         datos_finales_examenes_interconsultas.push(guardar_datos_examenes_interconsultas);
    //                         guardar_datos_examenes_interconsultas = [];
    //                     }
    //                 });
    //             }
    //         });

    //         // Creacion de array con los datos de la tabla dinámica Diagnóstico motivo de calificación
    //         var guardar_datos_motivo_calificacion = [];
    //         var datos_finales_adiciones_calificacion = [];
    //         // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
    //         $('#listado_diagnostico_cie10 tbody tr').each(function (index) {
    //             if ($(this).attr('id') !== "datos_diagnostico") {
    //                 $(this).children("td").each(function (index2) {
    //                     var nombres_ids = $(this).find('*').attr("id");
    //                     if (nombres_ids != undefined) {
    //                         if ($('#'+nombres_ids).val() == "on") {
    //                             if ($('#'+nombres_ids).is(':checked')) {
    //                                 guardar_datos_motivo_calificacion.push("Si");  
    //                             } else {
    //                                 guardar_datos_motivo_calificacion.push("No");  
    //                             }
    //                         }else{
    //                             guardar_datos_motivo_calificacion.push($('#'+nombres_ids).val());                     
    //                         }
    //                     }
    //                     if((index2+1) % 8 === 0){
    //                         datos_finales_adiciones_calificacion.push(guardar_datos_motivo_calificacion);
    //                         guardar_datos_motivo_calificacion = [];
    //                     }
    //                 });
    //             }
    //         });

    //         // Validación de id_asignacion_dx para saber si toca actualizar la información
    //         var id_adicion_dx = $("#id_adicion_dx").val();

    //         if (id_adicion_dx == "" || id_adicion_dx == undefined) {
    //             // Registrar Información
    //             var informacion_formulario = {
    //                 '_token': token,
    //                 'ID_Evento': id_evento,
    //                 'Id_Asignacion': id_asignacion_adicion_dx,
    //                 'Id_proceso': id_proceso_adicion_dx,
    //                 'Id_Dto_ATEL': id_dto_atel,
    //                 'Activo': $("#es_activo").val(),
    //                 'Tipo_evento': tipo_evento,
    //                 'motivo_solicitud': $("#motivo_solicitud").val(),
    //                 'N_siniestro': $("#n_siniestro").val(),
    //                 'Relacion_documentos': relacion_docs_dto_atel,
    //                 'Examenes_interconsultas': datos_finales_examenes_interconsultas,
    //                 'Adicion_motivo_calificacion': datos_finales_adiciones_calificacion,
    //                 'Otros_relacion_documentos': $("#otros_docs").val(),
    //                 'Sustentacion_Adicion_Dx': $("#sustentacion_adicion_dx").val(),
    //                 'Origen': $("#origen_dto_atel").val(),
    //                 'radicado_dictamen': $("#radicado_dictamen").val(),                    
    //             };
    //         } else {
    //             // Actualizar Información
    //             var informacion_formulario = {
    //                 '_token': token,
    //                 'Id_Adiciones_Dx': id_adicion_dx,
    //                 'ID_Evento': id_evento,
    //                 'Id_Asignacion': id_asignacion_adicion_dx,
    //                 'Id_proceso': id_proceso_adicion_dx,
    //                 'Id_Dto_ATEL': id_dto_atel,
    //                 'Activo': $("#es_activo").val(),
    //                 'Tipo_evento': tipo_evento,
    //                 'motivo_solicitud': $("#motivo_solicitud").val(),
    //                 'N_siniestro': $("#n_siniestro").val(),
    //                 'Relacion_documentos': relacion_docs_dto_atel,
    //                 'Examenes_interconsultas': datos_finales_examenes_interconsultas,
    //                 'Adicion_motivo_calificacion': datos_finales_adiciones_calificacion,
    //                 'Otros_relacion_documentos': $("#otros_docs").val(),
    //                 'Sustentacion_Adicion_Dx': $("#sustentacion_adicion_dx").val(),
    //                 'Origen': $("#origen_dto_atel").val(),
    //                 'radicado_dictamen': $("#radicado_dictamen").val(),                    
    //             };
    //         }

            
    //         $.ajax({
    //             type:'POST',
    //             url:'/GuardaroActualizarInfoAdicionDX',
    //             data: informacion_formulario,
    //             success: function(response){
    //                 if (response.parametro == "agregar_dto_atel") {
    //                     if (btn_guardar_info_evento > 0) {
    //                         $("#btn_guardar_info_evento").addClass('d-none');
    //                         $("#mostrar_mensaje_1").removeClass('d-none');
    //                         $(".mensaje_agrego_1").append('<strong>'+response.mensaje+'</strong>');
    //                         setTimeout(() => {
    //                             $("#mostrar_mensaje_1").addClass('d-none');
    //                             $(".mensaje_agrego_1").empty();
    //                             location.reload();
    //                         }, 3000);
    //                     }
    //                     else if (btn_guardar_relacion_docs > 0){
    //                         $("#btn_guardar_relacion_docs").addClass('d-none');
    //                         $("#mostrar_mensaje_2").removeClass('d-none');
    //                         $(".mensaje_agrego_2").append('<strong>'+response.mensaje+'</strong>');
    //                         setTimeout(() => {
    //                             $("#mostrar_mensaje_2").addClass('d-none');
    //                             $(".mensaje_agrego_2").empty();
    //                             location.reload();
    //                         }, 3000);
    //                     }
    //                     else if (btn_guardar_diagnosticos_adicionados > 0){
    //                         $("#btn_guardar_diagnosticos_adicionados").addClass('d-none');
    //                         $("#mostrar_mensaje_3").removeClass('d-none');
    //                         $(".mensaje_agrego_3").append('<strong>'+response.mensaje+'</strong>');
    //                         setTimeout(() => {
    //                             $("#mostrar_mensaje_3").addClass('d-none');
    //                             $(".mensaje_agrego_3").empty();
    //                             location.reload();
    //                         }, 3000);
    //                     }
    //                     else{
    //                         $("#GuardarAdicionDx").addClass('d-none');
    //                         $("#ActualizarAdicionDx").addClass('d-none');
    //                         $("#mostrar_mensaje_agrego_adicion_dx").removeClass('d-none');
    //                         $(".mensaje_agrego_adicion_dx").append('<strong>'+response.mensaje+'</strong>');
    //                         setTimeout(() => {
    //                             $("#mostrar_mensaje_agrego_adicion_dx").addClass('d-none');
    //                             $(".mensaje_agrego_adicion_dx").empty();
    //                             location.reload();
    //                         }, 3000);
    //                     }
    //                 }
    //             }
    //         });
    //     }

    // });

    $("#form_Adicion_Dx").submit(function(e){
        e.preventDefault();

        $("#btn_guardar_info_evento").prop("disabled", true);
        $("#btn_guardar_relacion_docs").prop("disabled", true);
        $("#btn_guardar_diagnosticos_adicionados").prop("disabled", true);

        var GuardarAdicionDx = $('#GuardarAdicionDx');
        var ActualizarAdicionDx = $('#ActualizarAdicionDx');

        if (GuardarAdicionDx.length > 0) {
            document.querySelector('#GuardarAdicionDx').disabled=true;            
        }
        if (ActualizarAdicionDx.length > 0) {
            document.querySelector('#ActualizarAdicionDx').disabled=true;
        }

        // Captura del Id_evento
        var id_evento = $("#Id_Evento").val();
        // caputra del id de asignacion y id proceso de la adicion dx
        var id_asignacion_adicion_dx = $("#Id_Asignacion_adicion_dx").val();
        var id_proceso_adicion_dx = $("#Id_Proceso_adicion_dx").val();

        // Captura del id de la determinación dto
        if ($("#id_dto_atel").val() == '') {
            var id_dto_atel = 0;
        } else {
            var id_dto_atel = $("#id_dto_atel").val();
        }

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
            var IdExamenesInterconsultas = [];
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

                var DataExamenesInterconsulta = $(this).data("id_fila_examen");
                IdExamenesInterconsultas.push(DataExamenesInterconsulta);
            });

            // Creacion de array con los datos de la tabla dinámica Diagnóstico motivo de calificación
            var IdDiagMotCali = [];
            var guardar_datos_motivo_calificacion_vi = [];
            var datos_finales_mot_calificacion = [];
            // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
            $('#listado_diagnostico_cie10_visual tbody tr').each(function (index) {
                if ($(this).attr('id') !== "datos_diagnostico_visual") {
                    $(this).children("td").each(function (index2) {
                        var nombres_ids = $(this).find('*').attr("id");
                        if (nombres_ids != undefined) {
                            if ($('#'+nombres_ids).val() == "on") {
                                if ($('#'+nombres_ids).is(':checked')) {
                                    guardar_datos_motivo_calificacion_vi.push("Si");  
                                } else {
                                    guardar_datos_motivo_calificacion_vi.push("No");  
                                }
                            }else{
                                
                                guardar_datos_motivo_calificacion_vi.push($('#'+nombres_ids).val());                     
                            }
                        }
                        if((index2+1) % 7 === 0){
                            datos_finales_mot_calificacion.push(guardar_datos_motivo_calificacion_vi);
                            guardar_datos_motivo_calificacion_vi = [];
                        }
                    });
                }

                var DataIdDiagMotCali = $(this).data("id_fila_diag");
                IdDiagMotCali.push(DataIdDiagMotCali);
            });

            // Creacion de array con los datos de la tabla dinámica Diagnósticos Adicionados
            var IdDiagMotCaliAdi = [];
            var guardar_datos_motivo_calificacion_adi = [];
            var datos_finales_adiciones_calificacion = [];
            // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
            $('#listado_diagnostico_cie10 tbody tr').each(function (index) {
                if ($(this).attr('id') !== "datos_diagnostico") {
                    $(this).children("td").each(function (index2) {
                        var nombres_ids = $(this).find('*').attr("id");
                        if (nombres_ids != undefined) {
                            if ($('#'+nombres_ids).val() == "on") {
                                if ($('#'+nombres_ids).is(':checked')) {
                                    guardar_datos_motivo_calificacion_adi.push("Si");  
                                } else {
                                    guardar_datos_motivo_calificacion_adi.push("No");  
                                }
                            }else{
                                guardar_datos_motivo_calificacion_adi.push($('#'+nombres_ids).val());                     
                            }
                        }
                        if((index2+1) % 8 === 0){
                            datos_finales_adiciones_calificacion.push(guardar_datos_motivo_calificacion_adi);
                            guardar_datos_motivo_calificacion_adi = [];
                        }
                    });
                }

                var DataIdDiagMotCaliAdi = $(this).data("id_fila_diag_adi");
                IdDiagMotCaliAdi.push(DataIdDiagMotCaliAdi);
            });
            
            // Validación de id_asignacion_dx para saber si toca actualizar la información
            var id_adicion_dx = $("#id_adicion_dx").val();

            /* En caso de que se vuelvan a habilitar los demás botones de inserción o actualizacion en la adicion dx 
                Descomentariar este if y comentariar el el que está activo.
            */
            // if( $("#btn_guardar_info_evento").val() == "Guardar" && 
            //     $("#btn_guardar_relacion_docs").val() == "Guardar" && 
            //     $("#btn_guardar_diagnosticos_adicionados").val() == "Guardar" && 
            //     $("#GuardarAdicionDx").val() == "Guardar"){
            
            if( $("#GuardarAdicionDx").val() == "Guardar"){
                // console.log('guardar');
                // Registrar Información
                var informacion_formulario = {
                    '_token': token,
                    "bandera_info_evento": bandera_info_evento,
                    'ID_Evento': id_evento,
                    'Id_Asignacion': id_asignacion_adicion_dx,
                    'Id_proceso': id_proceso_adicion_dx,
                    'Id_Dto_ATEL': id_dto_atel,
                    'Activo': $("#es_activo").val(),
                    'Tipo_evento': tipo_evento,
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
                    'IdExamenesInterconsultas': IdExamenesInterconsultas,
                    'datos_finales_mot_calificacion': datos_finales_mot_calificacion,
                    'IdDiagMotCali': IdDiagMotCali,
                    'Sustentacion': $("#sustentacion_califi_origen").val(),
                    'Adicion_motivo_calificacion': datos_finales_adiciones_calificacion,
                    'IdDiagMotCaliAdi': IdDiagMotCaliAdi,
                    'Otros_relacion_documentos': $("#otros_docs").val(),
                    'Sustentacion_Adicion_Dx': $("#sustentacion_adicion_dx").val(),
                    'Origen': $("#origen_dto_atel").val(),
                    'radicado_dictamen': $("#radicado_dictamen").val(),  
                    'n_identificacion' : $('#nro_identificacion').val()
                };
            } else {
                // console.log('actualizar');
                // Actualizar Información
                var informacion_formulario = {
                    '_token': token,
                    "bandera_info_evento": bandera_info_evento,
                    'Id_Adiciones_Dx': id_adicion_dx,
                    'ID_Evento': id_evento,
                    'Id_Asignacion': id_asignacion_adicion_dx,
                    'Id_proceso': id_proceso_adicion_dx,
                    'Id_Dto_ATEL': id_dto_atel,
                    'Activo': $("#es_activo").val(),
                    'Tipo_evento': tipo_evento,
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
                    'datos_finales_mot_calificacion': datos_finales_mot_calificacion,
                    'Sustentacion': $("#sustentacion_califi_origen").val(),
                    'Adicion_motivo_calificacion': datos_finales_adiciones_calificacion,
                    'Otros_relacion_documentos': $("#otros_docs").val(),
                    'Sustentacion_Adicion_Dx': $("#sustentacion_adicion_dx").val(),
                    'Origen': $("#origen_dto_atel").val(),
                    'radicado_dictamen': $("#radicado_dictamen").val(), 
                    'n_identificacion' : $('#nro_identificacion').val()
                };
            }
            // console.log(informacion_formulario);
            $.ajax({
                type:'POST',
                url:'/GuardaroActualizarInfoAdicionDX',
                data: informacion_formulario,
                beforeSend: function(){
                    showLoading();
                },
                success: function(response){
                    if(response.Id_Comunicado){
                        mensaje = response.mensaje;
                        datos_adx = dataCreacionDMLADX(response.Id_Comunicado);
                        $.ajax({    
                            type:'POST',
                            url:'/ADescargaProformaDMLPrev',
                            data: datos_adx,
                            success: function (response) {
                                if(response.nombre_documento){  
                                    $("#GuardarAdicionDx").addClass('d-none');
                                    $("#ActualizarAdicionDx").addClass('d-none');
                                    $("#mostrar_mensaje_agrego_adicion_dx").removeClass('d-none');
                                    $(".mensaje_agrego_adicion_dx").append('<strong>'+mensaje+'</strong>');
                                    setTimeout(() => {
                                        $("#mostrar_mensaje_agrego_adicion_dx").addClass('d-none');
                                        $(".mensaje_agrego_adicion_dx").empty();
                                        location.reload();
                                    }, 1500);
                                }
                                else{
                                    $("#mostrar_mensaje_error").removeClass('d-none');
                                    $(".mostrar_mensaje_error").append('<strong>Ha ocurrido un error</strong>');
                                    setTimeout(() => {
                                        $("#mostrar_mensaje_error").addClass('d-none');
                                        $(".mostrar_mensaje_error").empty();
                                    }, 1000);
                                }
                            },
                            complete:  function() {
                                hideLoading();
                            },       
                        });
                    }
                    else{
                        hideLoading();
                        $("#mostrar_mensaje_error").removeClass('d-none');
                        $(".mostrar_mensaje_error").append('<strong>Ha ocurrido un error</strong>');
                        setTimeout(() => {
                            $("#mostrar_mensaje_error").addClass('d-none');
                            $(".mostrar_mensaje_error").empty();
                        }, 1000);
                    }
                }
            });
        }

    });

    function dataCreacionDMLADX(id_comunicado){
        var Id_Evento = $('#Id_Evento').val();
        var Id_Proceso = $('#Id_Proceso_adicion_dx').val();
        var Id_Asignacion = $('#Id_Asignacion_adicion_dx').val();
        var nombre_evento = $('#nombre_evento_guardado').val();
        var origen = $("#origen_dto_atel option:selected").text(); 
        var sustentacion = $("#sustentacion_adicion_dx").val();
        var N_siniestro = $("#n_siniestro").val();
        var Justificacion_revision_origen = $("#justificacion_revision_origen").val();
        return {
            '_token': token,
            'id_evento': Id_Evento,
            'Id_Asignacion': Id_Asignacion,
            'Id_Proceso': Id_Proceso,
            'nombre_evento': nombre_evento,
            'origen': origen,
            'id_comunicado': id_comunicado,
            'sustentacion': sustentacion,
            'N_siniestro': N_siniestro,
            'justificacion_revision_origen': Justificacion_revision_origen,
        };
    }

    function dataCreacionOficioNotificacionADX(id_comunicado){
        token = $("input[name='_token']").val();
        console.log('Id_comunicado ', id_comunicado);
        var Id_Evento = $('#Id_Evento').val();
        var Id_Proceso = $('#Id_Proceso_adicion_dx').val();
        var Id_Asignacion = $('#Id_Asignacion_adicion_dx').val();
        //Se captura el cuerpo del comunicado y se le limpian las comillas ya que estas afectan el correcto funcionamiento del summernote
        var cuerpo = $("#cuerpo_comunicado").val();
        cuerpo = cuerpo ? cuerpo.replace(/"/g, "'") : '';
        var origen = $("#origen_dto_atel option:selected").text();
        //checkbox de Copias de partes interesadas
        var copia_beneficiario = $('#beneficiario').filter(":checked").val();
        var copia_empleador = $('#empleador').filter(":checked").val();
        var copia_eps = $('#eps').filter(":checked").val();
        var copia_afp = $('#afp').filter(":checked").val();
        // Se valida si han marcado como si la opcion de la entidad de conocimiento (afp)
        var copia_afp_conocimiento = '';
        if (entidad_conocimiento != '' && entidad_conocimiento == "Si") {
            copia_afp_conocimiento = $('#afp_conocimiento').filter(":checked").val();
        }
        var tipo_evento = $('#nombre_evento_guardado').val();
        var copia_arl = $('#arl').filter(":checked").val();
        var N_siniestro = $("#n_siniestro").val();
        var firmar = $('#firmar').filter(":checked").val();
        var anexos = $('#anexos').val();

        return {
            '_token': token, 
            'id_comunicado': id_comunicado,
            'cuerpo': cuerpo,
            'Id_Evento': Id_Evento,
            'Id_asignacion': Id_Asignacion,
            'Id_proceso': Id_Proceso,
            'origen': origen,
            'copia_beneficiario': copia_beneficiario,
            'copia_empleador': copia_empleador,
            'copia_eps': copia_eps,
            'copia_afp': copia_afp,
            'copia_afp_conocimiento': copia_afp_conocimiento,
            'tipo_evento': tipo_evento,
            'copia_arl': copia_arl,
            'firmar': firmar,
            'anexos': anexos,
            'tipo_evento': tipo_evento,
            'N_siniestro': N_siniestro,
        };
    }

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
        $("#btn_guardar_info_evento").prop('disabled', true);
        $("#btn_guardar_relacion_docs").prop('disabled', true);
        $("#btn_guardar_diagnosticos_adicionados").prop('disabled', true);
        $("#ActualizarAdicionDx").prop('disabled', true);       
        // $("#div_correspondecia").removeClass('d-none');
    }

    // Desabilitar los botones si ya esta visado
    var visar_servicio = $("#visar_servicio").val();
    if (visar_servicio!== '') {
         $("#EditarDTOATEL").prop('disabled', true);
    }

    // Validar cual de los oficios esta marcado

    /* var oficiopclcorres = $('#oficiopcl');
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
    } */

    // Funcionalidad para introducir el texto predeterminado para la proforma Notificación DML ORIGEN
    var entidad_conocimiento = $("#entidad_conocimiento").val();
    $('#oficio_origen').change(function(){
        if ($(this).prop('checked')) {
         var asunto_insertar = "Dictamen presunto origen evento";
         var texto_insertar = '<p>Respetados Señores.</p><p>En atención a la solicitud de emisión de dictamen sobre el presunto origen de la contingencia, le informamos que una vez estudiada la documentación del paciente por el Comité Interdisciplinario de Calificación de Pérdida de la Capacidad Laboral y Origen de Seguros de Vida ALFA S.A., experto en la materia, se considera que el presunto origen de la muerte del señor(a) {{$nombre_afiliado}}, es con ocasión de un {{$tipo_evento}} {{$origen_evento}}.</p><p>Para los efectos, se adjunta el dictamen que sustenta lo manifestado.</p><p>En caso de que no se encuentre de acuerdo con la calificación emitida por Seguros de Vida Alfa S.A., cuenta con diez (10) días hábiles siguientes a partir de la fecha de recibida la notificación para manifestar la inconformidad frente al resultado. Esta manifestación se debe realizar por escrito y debe estar dirigida a Seguros de Vida Alfa S.A. en donde se exprese sobre cuál o cuáles aspectos se encuentra en desacuerdo.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3077032 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escríbanos a «servicioalcliente@segurosalfa.com.co»; o a la dirección Carrera 10 # 18-36, piso 4, Edificio José María Córdoba, Bogotá D.C.</p>';

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
        //  $("#afp").prop('checked', true);
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
            // $("#afp").prop('checked', false);
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

    // var asuntocorrespondencia = $("#Asunto").val();
    // if (asuntocorrespondencia !== '') {
    //     $("#div_correspondecia").addClass('d-none');
    // }

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
    $('#form_correspondencia_adx').submit(function (e){
        e.preventDefault();              
       
        var Id_Evento = $('#Id_Evento').val();
        var Id_Proceso_adicion_dx = $('#Id_Proceso_adicion_dx').val();
        var Id_Asignacion_adicion_dx  = $('#Id_Asignacion_adicion_dx').val();
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
        cuerpo_comunicado = cuerpo_comunicado ? cuerpo_comunicado.replace(/"/g, "'") : '';
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
            'Id_Evento':Id_Evento,
            'Id_Proceso_adicion_dx':Id_Proceso_adicion_dx,
            'Id_Asignacion_adicion_dx':Id_Asignacion_adicion_dx,            
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
        };

        // console.log(datos_correspondecia);
        $.ajax({    
            type:'POST',
            url:'/guardarcorrespondenciaADX',
            data: datos_correspondecia,
            beforeSend: function(){
                showLoading();
            },
            success: function(response){
                if(response.Id_Comunicado){
                    mensaje = response.mensaje;
                    datos_oficio = dataCreacionOficioNotificacionADX(response.Id_Comunicado);
                    $.ajax({    
                        type:'POST',
                        url:'/ADescargaProformaNotiDMLPrev',
                        data: datos_oficio,
                        beforeSend:  function() {
                            $("#enviar_form_noti_previsional").addClass("descarga-deshabilitada");
                        },
                        success: function (response) {
                            if(response.nombre_documento){
                                $('#GuardarCorrespondencia').prop('disabled', true);
                                $('#ActualizarCorrespondencia').prop('disabled', true);
                                $('#div_alerta_Correspondencia').removeClass('d-none');
                                $('.alerta_Correspondencia').append('<strong>'+mensaje+'</strong>');                                            
                                setTimeout(function(){
                                    $('#div_alerta_Correspondencia').addClass('d-none');
                                    $('.alerta_Correspondencia').empty();   
                                    location.reload();
                                }, 1500);  
                            }
                            else{
                                $("#mostrar_mensaje_error_correspondencia").removeClass('d-none');
                                $(".mostrar_mensaje_error_correspondencia").append('<strong>Ha ocurrido un error</strong>');
                                setTimeout(() => {
                                    $("#mostrar_mensaje_error_correspondencia").addClass('d-none');
                                    $(".mostrar_mensaje_error_correspondencia").empty();
                                }, 1000);
                            }
                        },
                        complete:  function() {
                            hideLoading();
                        },       
                    });
                }
                else{
                    hideLoading();
                    $("#mostrar_mensaje_error_correspondencia").removeClass('d-none');
                    $(".mostrar_mensaje_error_correspondencia").append('<strong>Ha ocurrido un error</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_error_correspondencia").addClass('d-none');
                        $(".mostrar_mensaje_error_correspondencia").empty();
                    }, 1000);
                }
            }          
        })
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
        formData.append('Id_evento',$("#Id_Evento").val());
        formData.append('Id_asignacion',$('#Id_Asignacion_adicion_dx').val());
        formData.append('Id_procesos',$("#Id_Proceso_adicion_dx").val());
        formData.append('fecha_comunicado2',null);
        formData.append('radicado2',$('#radicado_comunicado_manual').val());
        formData.append('cliente_comunicado2','N/A');
        formData.append('nombre_afiliado_comunicado2',$('#nombre_afiliado').val());
        formData.append('tipo_documento_comunicado2','N/A');
        formData.append('identificacion_comunicado2',$('#nro_identificacion').val());
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
        formData.append('modulo_creacion','adicionDxDtoOrigen');
        formData.append('modulo','Comunicados AdicionDX');
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

    function cleanModalCorrespondencia(){
        $("#btn_guardar_actualizar_correspondencia").val('Guardar');

        correspondencia_array = [];
        $("#modalCorrespondencia #check_principal").prop('checked', false).prop('disabled', true).prop('required', true);
        $("#modalCorrespondencia #check_copia").prop('checked', false).prop('disabled', false).prop('required', true);
        $('#state_notificacion').val('').trigger('change');
        $("#modalCorrespondencia #tipo_correspondencia").val('');
        $("#modalCorrespondencia #n_orden").val('');
        $("#modalCorrespondencia #nombre_destinatario").val('');
        $("#modalCorrespondencia #direccion").val('');
        $("#modalCorrespondencia #departamento").val('');
        $("#modalCorrespondencia #ciudad").val('');
        $("#modalCorrespondencia #telefono").val('');
        $("#modalCorrespondencia #email").val('');
        $("#modalCorrespondencia #m_notificacion").val('');
        $("#modalCorrespondencia #folios").val('');
        $("#modalCorrespondencia #n_guia").val('');
        $("#modalCorrespondencia #f_envio").val('');
        $("#modalCorrespondencia #f_notificacion").val('');
        $("#modalCorrespondencia #state_notificacion").val('');
        $("#modalCorrespondencia #id_correspondencia").val('');
        $("#modalCorrespondencia #id_asignacion").val('');
        $("#modalCorrespondencia #id_proceso").val('');
        $("#modalCorrespondencia #id_comunicado").val('');
        $("#modalCorrespondencia #id_destinatario").val('');
    }

    function cargarSelectorModalCorrespondencia(){
        //Listado de opciones de estado de notificación Correspondencia
        let selectores_notificacion_correspondencia = {
            '_token': $('input[name=_token]').val(),
            'parametro': 'EstadosNotificacionCorrespondencia'
        }
        $.ajax({
            type: 'POST',
            url: '/selectoresJuntas',
            data: selectores_notificacion_correspondencia,
            beforeSend:  function() {
                $("#btn_guardar_actualizar_correspondencia").addClass("descarga-deshabilitada");
            },
            success: function (data) {
                let optionSelected = data.find(finder => finder.Id_Parametro === 362);
                $("#modalCorrespondencia #state_notificacion").val(optionSelected?.Id_Parametro);
                $('#state_notificacion').empty();
                $('#state_notificacion').append('<option value="'+optionSelected?.Id_Parametro+'" selected>'+optionSelected?.Nombre_parametro+'</option>');
                let SelectorModalCorrespondencia = $('select[name=state_notificacion]').val();
                let formaenviogenerarcomunicado = Object.keys(data);
                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != SelectorModalCorrespondencia) {
                        $('#state_notificacion').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                    }                
                }                                     
            },
            complete: function(){
                $("#btn_guardar_actualizar_correspondencia").removeClass("descarga-deshabilitada");
            }
        });
    }
    
    let correspondencia_array = [];
    $("#listado_comunicados_adx").on('click', "#CorrespondenciaNotificacion", async function() {
        //Reestablecer modal
        cleanModalCorrespondencia();
        //Cargar selectores modal con Pendiente como valor por defecto
        cargarSelectorModalCorrespondencia();
        //Capturar información
        let id = $(this);

        let token = $('input[name=_token]').val(); 
        let tipo_correspondencia = $(id).data('tipo_correspondencia');
        let idComunicado = $(id).data('id_comunicado');
        let N_radicado = $(id).data('n_radicado');
        let destinatarioPrincipal = $(id).data("destinatario_principal");
        let copias = $(id).data("copias");
        let id_evento = $(id).data('id_evento');
        let id_proceso = $(id).data('id_proceso');
        let id_asignacion = $(id).data('id_asignacion');
        let anexos = $(id).data('anexos');
        let correspondencia = $(id).data('correspondencia');
        
        //Tipo de comunicado si fue cargado manualmente o es generado por Sigmel
        let tipo_descarga = $(id).data('tipo_descarga');

        let id_destinatario = retornarIdDestinatario($(id).data('ids_destinatario'),tipo_correspondencia);
        //Se consultan las correspondencias que fueron guardadas como no notificados por medio de cargue masivo, los cuales deben salir en negrilla
        let correspondencias_guardadas = await consultarRegistroPorIdDestinatario(id_destinatario);
        //Ya que en un principio las copias llegan en un string se separan por , y se les elimina los espacios en blancos para poder comparar 
        copias = copias ? copias.split(',').map(copia => copia.trim()) : copias;
        //Desactiva el formulario en caso de que la correspodencia este inactiva.
        if($(id).data("estado_correspondencia") != 1){
            $("#btn_guardar_actualizar_correspondencia").remove();
            $("#form_correspondencia *").prop('disabled',true);
            $("#cerar_modalCorrespondencia").prop('disabled',false);
        }
        let estado_general = $("#status_notificacion_" + N_radicado).find(":selected").text();
        if((estado_general == 'Notificado efectivamente' || estado_general == 'Devuelto' || estado_general == 'No notificar') 
            && ($(id).data("estado_correspondencia") == 0 || $(id).data("estado_correspondencia") == 1 )){

            $(".alerta_advertencia").removeClass('d-none');
            $(".alerta_advertencia").empty();
            $(".alerta_advertencia").append(`La correspondencia no se puede guardar y/o actualizar ya que el estado del comunicado es <strong>${estado_general}</strong>,por favor cambielo para pode editar la correspondencia.`)
            $("#btn_guardar_actualizar_correspondencia").addClass('d-none');
        
         setTimeout(function(){
            $(".alerta_advertencia").addClass('d-none');
            $(".alerta_advertencia").empty();
        },3000); 
        }else{
             $("#btn_guardar_actualizar_correspondencia").removeClass('d-none');
             $(".alerta_advertencia").empty();
             $(".alerta_advertencia").addClass('d-none');
         }
        //Información superior del modal 
        if(tipo_descarga === 'Manual' || tipo_descarga === 'Dictamen'){
            $("#modalCorrespondencia #nombre_afiliado").val($("#nombre_afiliado").val());
            $("#modalCorrespondencia #n_identificacion").val($("#nro_identificacion").val());
        }
        else{
            $("#modalCorrespondencia #nombre_afiliado").val($(id).data('nombre_afiliado'));
            $("#modalCorrespondencia #n_identificacion").val($(id).data('numero_identificacion'));
        }
        $("#modalCorrespondencia #id_destinatario").val(id_destinatario);
        $("#modalCorrespondencia #id_evento").val($(id).data('id_evento'));
        $("#modalCorrespondencia #enlace_ed_evento").text($(id).data('id_evento'));
        
        if(tipo_descarga === 'Manual'){
            $("#modalCorrespondencia #check_principal").prop('checked', false);
            $("#modalCorrespondencia #check_principal").prop('disabled', false);
            $("#modalCorrespondencia #check_copia").prop('disabled', false);
            $("#modalCorrespondencia #check_copia").prop('checked', false);
        }
        if(correspondencia && correspondencia.length >0){
            array_temp = correspondencia.split(",").map(item => item.trim());
            correspondencia_array = array_temp;
        }
        $("#modalCorrespondencia #tipo_correspondencia").val(tipo_correspondencia);
        $("#modalCorrespondencia #id_asignacion").val(id_asignacion);
        $("#modalCorrespondencia #id_proceso").val(id_proceso);
        $("#modalCorrespondencia #id_comunicado").val(idComunicado);

        if(correspondencia_array.includes(tipo_correspondencia) || correspondencias_guardadas === tipo_correspondencia){
            data_comunicado = {
                _token: token,
                id_comunicado: idComunicado,
                id_evento: id_evento,
                id_asignacion: id_asignacion,
                id_proceso: id_proceso,
                tipo_correspondencia: tipo_correspondencia,
                previous_saved: true 
            }
            
            $.ajax({
                type:'POST',
                url:'/getInformacionCorrespondencia',
                data: data_comunicado,
                beforeSend:  function() {
                    showLoading();
                },
                success: function(response){
                    if(response && response[0]){
                        $("#btn_guardar_actualizar_correspondencia").val('Actualizar');

                        $("#modalCorrespondencia #n_orden").val(response[0]?.N_orden);
                        $("#modalCorrespondencia #nombre_destinatario").val(response[0]?.Nombre_destinatario);
                        $("#modalCorrespondencia #direccion").val(response[0]?.Direccion_destinatario);
                        $("#modalCorrespondencia #departamento").val(response[0]?.Departamento);
                        $("#modalCorrespondencia #ciudad").val(response[0]?.Ciudad);
                        $("#modalCorrespondencia #telefono").val(response[0]?.Telefono_destinatario);
                        $("#modalCorrespondencia #email").val(response[0]?.Email_destinatario);
                        $("#modalCorrespondencia #m_notificacion").val(response[0]?.Medio_notificacion);
                        $("#modalCorrespondencia #folios").val(response[0]?.Folios);
                        $("#modalCorrespondencia #radicado").val(response[0]?.N_radicado);
                        $("#modalCorrespondencia .modal-title").text('Correspondencia ' + response[0]?.Tipo_correspondencia);
                        $("#modalCorrespondencia #n_guia").val(response[0]?.N_guia);
                        $("#modalCorrespondencia #f_envio").val(response[0]?.F_envio);
                        $("#modalCorrespondencia #f_notificacion").val(response[0]?.F_notificacion);
                        $("#modalCorrespondencia #state_notificacion").val(response[0]?.Id_Estado_corresp);
                        $("#modalCorrespondencia #id_correspondencia").val(response[0]?.Id_Correspondencia);
                        
                        if(response[0]?.Tipo_destinatario){
                            if(response[0]?.Tipo_destinatario === $('#modalCorrespondencia #check_principal').val()){
                                if(tipo_descarga != 'Manual'){
                                    $("#modalCorrespondencia #check_principal").prop('checked', true);
                                    $("#modalCorrespondencia #check_copia").prop('disabled', true);
                                    $("#modalCorrespondencia #check_copia").prop('required', false);
                                }
                                else{
                                    $("#modalCorrespondencia #check_principal").prop('checked', true);
                                    $("#modalCorrespondencia #check_principal").prop('disabled', false);
                                    $("#modalCorrespondencia #check_copia").prop('disabled', true);
                                    $("#modalCorrespondencia #check_copia").prop('required', false);
                                }
                                
                            }
                            else if(response[0]?.Tipo_destinatario === $('#modalCorrespondencia #check_copia').val()){
                                
                                if(tipo_descarga != 'Manual'){
                                    $("#modalCorrespondencia #check_copia").prop('checked', true);
                                    $("#modalCorrespondencia #check_copia").prop('disabled', true);
                                    $("#modalCorrespondencia #check_principal").prop('required', false);
                                }
                                else{
                                    $("#modalCorrespondencia #check_copia").prop('checked', true);
                                    $("#modalCorrespondencia #check_principal").prop('disabled', true);
                                    $("#modalCorrespondencia #check_principal").prop('required', false);
                                    $("#modalCorrespondencia #check_copia").prop('disabled', false);
                                }
                            } 
                        }
                        let selectores_notificacion_correspondencia = {
                            '_token': $('input[name=_token]').val(),
                            'parametro': 'EstadosNotificacionCorrespondencia'
                        }
                        $.ajax({
                            type: 'POST',
                            url: '/selectoresJuntas',
                            data: selectores_notificacion_correspondencia,
                            beforeSend:  function() {
                                $("#btn_guardar_actualizar_correspondencia").addClass("descarga-deshabilitada");
                            },
                            success: function (data) {
                                let optionSelected = data.find(finder => finder.Id_Parametro === response[0]?.Id_Estado_corresp);
                                $('#state_notificacion').empty();
                                $('#state_notificacion').append('<option value="'+response[0]?.Id_Estado_corresp+'" selected>'+optionSelected?.Nombre_parametro+'</option>');
                                let SelectorModalCorrespondencia = $('select[name=state_notificacion]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != SelectorModalCorrespondencia) {
                                        $('#state_notificacion').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            },
                            complete: function(){
                                $("#btn_guardar_actualizar_correspondencia").removeClass("descarga-deshabilitada");
                            }
                        });
                    }
                },
                error: function (error) {
                    console.error('Ha ocurrido un error:', error);
                },
                complete: function(){
                    hideLoading();
                }
            });
        }
        else{
            data_comunicado = {
                _token: token,
                id_comunicado: idComunicado,
                id_evento: id_evento,
                id_asignacion: id_asignacion,
                id_proceso: id_proceso,
                tipo_correspondencia: tipo_correspondencia,
                previous_saved: false
            }
            
            $.ajax({
                type:'POST',
                url:'/getInformacionCorrespondencia',
                data: data_comunicado,
                beforeSend:  function() {
                    showLoading();
                },
                success: function(response){
                    
                    if(response && response.datos){
                        $("#modalCorrespondencia #n_orden").val(response?.nro_orden);
                        $("#modalCorrespondencia #nombre_destinatario").val(response?.datos?.Nombre_destinatario);
                        $("#modalCorrespondencia #direccion").val(response?.datos?.Direccion_destinatario);
                        $("#modalCorrespondencia #departamento").val(response?.datos?.Departamento_destinatario);
                        $("#modalCorrespondencia #ciudad").val(response?.datos?.Ciudad_destinatario);
                        $("#modalCorrespondencia #telefono").val(response?.datos?.Telefono_destinatario);
                        $("#modalCorrespondencia #email").val(response?.datos?.Email_destinatario);
                        $("#modalCorrespondencia #m_notificacion").val(response?.datos?.Medio_notificacion_destinatario);
                        $("#modalCorrespondencia #folios").val(anexos);
                        $("#modalCorrespondencia .modal-title").text('Correspondencia ' + tipo_correspondencia);
                        $("#modalCorrespondencia #radicado").val(N_radicado);
                        if(tipo_descarga != 'Manual' && tipo_correspondencia.toLowerCase() === destinatarioPrincipal.toLowerCase()){
                            $("#modalCorrespondencia #check_principal").prop('checked', true);
                            $("#modalCorrespondencia #check_copia").prop('disabled', true);
                            $("#modalCorrespondencia #check_copia").prop('required', false);
                        }
                        else if(tipo_descarga != 'Manual' && tipo_correspondencia.toLowerCase() !== destinatarioPrincipal.toLowerCase() && Array.isArray(copias) && copias?.some(copia => copia.toLowerCase() === tipo_correspondencia.toLowerCase())){
                            $("#modalCorrespondencia #check_copia").prop('checked', true);
                            $("#modalCorrespondencia #check_copia").prop('disabled', true);
                            $("#modalCorrespondencia #check_principal").prop('required', false);
                        }
                    }
                },
                error: function (error) {
                    console.error('Ha ocurrido un error:', error);
                },
                complete: function(){
                    hideLoading();
                }
            });
        }
        // Mostrar la modal
        $("#modalCorrespondencia").show();

        //Eventos checkbox principal
        $("#check_principal").change(function() {
            if ($(this).is(':checked')) {
                $("#check_copia").prop('disabled', true).prop('required', false);
            } else {
                $("#check_copia").prop('disabled', false).prop('required', true);
            }
        });
        //Eventos checkbox copia
        $("#check_copia").change(function() {
            if ($(this).is(':checked')) {
                $("#check_principal").prop('disabled', true).prop('required', false);
            } 
            else if(tipo_descarga == 'Manual') {
                $("#check_principal").prop('disabled', false).prop('required', true);
            }
            else{
                $("#check_principal").prop('disabled', true).prop('required', true);
            }
        });
    });

    
    $('#form_correspondencia').submit(function (e) {
        e.preventDefault();
        let token = $('input[name=_token]').val(); 
        let tipo_correspondencia = $('#modalCorrespondencia #tipo_correspondencia').val();
        if (!correspondencia_array.includes(tipo_correspondencia)) {
            correspondencia_array.push(tipo_correspondencia);
        }
        tipoDestinatario = null;
        if($('#check_principal').is(':checked')){
            tipoDestinatario = $('#modalCorrespondencia #check_principal').val();
            $("#modalCorrespondencia #check_principal").prop('required', false);
        }
        else if($('#check_copia').is(':checked')){
            tipoDestinatario = $('#modalCorrespondencia #check_copia').val();
        }
        else{
            tipoDestinatario = null;
        }
        datos_correspondencia = {
            '_token': token,
            'correspondencia': correspondencia_array,
            'nombre_afiliado': $('#modalCorrespondencia #nombre_afiliado').val(),
            'n_identificacion_afiliado': $('#modalCorrespondencia #n_identificacion').val(),
            'id_asignacion': $('#modalCorrespondencia #id_asignacion').val(),
            'id_proceso': $('#modalCorrespondencia #id_proceso').val(),
            'id_evento': $('#modalCorrespondencia #id_evento').val(),
            'id_comunicado': $('#modalCorrespondencia #id_comunicado').val(),
            'id_destinatario': $('#modalCorrespondencia #id_destinatario').val(),
            'n_radicado': $('#modalCorrespondencia #radicado').val(),
            'n_orden': $('#modalCorrespondencia #n_orden').val(),
            'tipo_destinatario': tipoDestinatario,
            'nombre_destinatario': $('#modalCorrespondencia #nombre_destinatario').val(),
            'direccion_destinatario': $('#modalCorrespondencia #direccion').val(),
            'departamento_destinatario': $('#modalCorrespondencia #departamento').val(),
            'ciudad_destinatario': $('#modalCorrespondencia #ciudad').val(),
            'telefono_destinatario': $('#modalCorrespondencia #telefono').val(),
            'email_destinatario': $('#modalCorrespondencia #email').val(),
            'medio_notificacion_destinatario': $('#modalCorrespondencia #m_notificacion').val(),
            'n_guia': $('#modalCorrespondencia #n_guia').val(),
            'folios': $('#modalCorrespondencia #folios').val(),
            'fecha_envio': $('#modalCorrespondencia #f_envio').val(),
            'fecha_notificacion': $('#modalCorrespondencia #f_notificacion').val(),
            'estado_notificacion': $('#modalCorrespondencia #state_notificacion').val(),
            'tipo_correspondencia': tipo_correspondencia,
            'id_correspondencia': $('#modalCorrespondencia #id_correspondencia').val(),
            'accion': $('#btn_guardar_actualizar_correspondencia').val()
        };
        $.ajax({    
            type:'POST',
            url:'/guardarInformacionCorrespondencia',
            data: datos_correspondencia,
            beforeSend:  function() {
                $("#btn_guardar_actualizar_correspondencia").addClass("descarga-deshabilitada");
                showLoading();
            },
            success: function(response){
                if (response.parametro == 'agregar_correspondencia') {
                    $('.alerta_correspondencia').removeClass('d-none');
                    $('.alerta_correspondencia').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_correspondencia').addClass('d-none');
                        $('.alerta_correspondencia').empty();
                        localStorage.setItem("#Generar_comunicados", true);
                        location.reload();
                    }, 3000);
                }
            },
            error: function (error) {
                $('.alerta_error').removeClass('d-none');
                $('.alerta_error').append('<strong> Ha ocurrido un error al momento de guardar la correspondencia.</strong>');
                setTimeout(function(){
                    $('.alerta_error').addClass('d-none');
                    $('.alerta_error').empty();
                }, 3000);
            },
            complete: function(){
                $("#btn_guardar_actualizar_correspondencia").removeClass("descarga-deshabilitada");
                hideLoading();
            }
        });
    });

    function showLoading() {
        $('#loading').addClass('loading');
        $('#loading-content').addClass('loading-content');
    }
    function hideLoading() {
        $('#loading').removeClass('loading');
        $('#loading-content').removeClass('loading-content');
        
    }

    let selectores_notificacion = {
        '_token': $('input[name=_token]').val(),
        'parametro': 'EstadosNotificaion'
    }

    let opciones_Notificacion = [];
    
    //Selectores estados de notificacion
    $("[id^='status_notificacion_']").each(function() {
        let $selector = $(this);
        let opocionSeleccionada = $selector.data('default');
        let desactivar = $selector.data('deshabilitar') == '1' ? false: true;
        $.ajax({
            type: 'POST',
            url: '/cargarselectores',
            data: selectores_notificacion,
            success: function (data) {
                $.each(data, function (index, item) {
                    //Establecemos el color que tendra le texto de cada opcion segun corresponda
                    let color = (()=>{
                        switch(item.Nombre_parametro){
                            case 'Pendiente': return '#000000'; // negro
                            case 'No notificar': return '#CBCBCB'; // gris
                            case 'Devuelto': return '#E70000'; // rojo
                            case 'Notificado efectivamente': return '#00E738'; // verde
                            case 'Notificado parcialmente': return '#00ACE7'; // azul
                        }
                    })();
    
                    let opcion = $('<option>', {
                        value: item.Id_Parametro,
                        text: item.Nombre_parametro
                    });
    
                    $selector.append(opcion);
    
                    /**@var opciones_Notificacion Corresponde a las propiedades del elemento */
                    opciones_Notificacion.push({
                        id:item.Id_Parametro,
                        texto: item.Nombre_parametro,
                        color: color
                    });
                });
    
                //Cargamos la configuracion del select2
                $selector.select2({
                    placeholder: "Seleccione una opción",
                    allowClear: false,
                    data: opciones_Notificacion,
                    disabled: () => {
                        return opocionSeleccionada == 359 ||  opocionSeleccionada == 358 ? false : desactivar;
                    },
                    templateResult: function(data) {
                        return $('<span>', {
                            style: `color: ${data.color}`,
                            text: data.texto
                        });
                    },
                    templateSelection: function(data) {
                        return $('<span>', {
                            style: `color: ${data.color}`,
                            text: data.texto
                        });
                    }
                }).val(opocionSeleccionada);

                $selector.trigger('change');
            },
        });
    }); 

    //Accion editar comunicado
    $("#listado_comunicados_adx").on("click",'#editar_comunicado',function(){
        let radicado = $(this).data('radicado');
        let datos_comunicados_actualizar = {
            '_token' : token,
            'bandera': 'Actualizar',
            'radicado' : $(this).data('radicado'),
            'Nota': $("#nota_comunicado_" + radicado).val(),
            'Estado_general': $("#status_notificacion_" + radicado).val(),
            'id_asignacion': $('#Id_Asignacion_adicion_dx').val()
        };
        $.ajax({
            type:'POST',
            url:'/historialComunicadoOrigen',
            data: datos_comunicados_actualizar,
            success:function(data){
                $('.alerta_externa_comunicado').removeClass('d-none');
                $(".alerta_externa_comunicado").append("<strong>" + data + "</strong>");
                setTimeout(()=>{
                    localStorage.setItem("#Generar_comunicados", true);
                    location.reload();
                },2000);

            }
        });
});

    //Reemplazar archivo 
    let comunicado_reemplazar = null;
    $("form[id^='form_reemplazar_archivo_']").submit(function (e){
        e.preventDefault();           
        //Se abre el modal
        $('#modalReemplazarArchivos').modal('show');  
        //Se limpian las advertencias y el input de archivo
        $(".cargueundocumentoprimeromodal").addClass('d-none');
        $(".extensionInvalidaModal").addClass('d-none');
        $('#cargue_comunicados_modal').val('');
        comunicado_reemplazar = $(this).data('archivo');
        data_comunicado = {
            '_token': $('input[name=_token]').val(),
            'id_comunicado': comunicado_reemplazar.Id_Comunicado
        }
        $.ajax({
            type:'POST',
            url:'/getInfoComunicado',
            data: data_comunicado,
            beforeSend:  function() {
                $("#cargarComunicadoModal").addClass("descarga-deshabilitada");
            },
            success:function(response){
                if(response && response[0]){
                    comunicado_reemplazar = response[0];
                    let nombre_doc = comunicado_reemplazar.Nombre_documento;
                    if(nombre_doc != null && nombre_doc != "null" && comunicado_reemplazar.Tipo_descarga !== 'Manual'){
                        extensionDoc = ['.pdf','.doc','.docx','.xlsx'];//`.${ nombre_doc.split('.').pop()}`;
                        document.getElementById('cargue_comunicados_modal').setAttribute('accept', extensionDoc);
                    }
                    else if(comunicado_reemplazar.Tipo_descarga === 'Manual'){
                        extensionDocManual = ['.pdf','.doc','.docx','.xlsx']
                        document.getElementById('cargue_comunicados_modal').setAttribute('accept', '.pdf, .doc, .docx, .xlsx');
                    }
                }
            },
            complete:function(){
                $("#cargarComunicadoModal").removeClass("descarga-deshabilitada");
            }
        });
    });

    const initValueExtension = document.getElementById('extensionInvalidaMensaje')?.textContent;
    $("form[id^='reemplazar_documento']").submit(function(e){
        e.preventDefault();
        if(!$('#cargue_comunicados_modal')[0].files[0]){
            return $(".cargueundocumentoprimeromodal").removeClass('d-none');
        }
        $(".cargueundocumentoprimeromodal").addClass('d-none');
        $(".extensionInvalidaModal").addClass('d-none');
        var archivo = $('#cargue_comunicados_modal')[0].files[0];
        extensionDocCargado = `.${archivo.name.split('.').pop()}`;
        if(comunicado_reemplazar.Tipo_descarga === 'Manual' && extensionDocManual.includes(extensionDocCargado)){
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
            formData.append('nombre_documento', archivo.name);
            formData.append('asunto', archivo.name);
            formData.append('nombre_anterior', comunicado_reemplazar.Nombre_documento);
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
        else if(comunicado_reemplazar.Tipo_descarga !== 'Manual' && extensionDoc.includes(extensionDocCargado)){
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
            // if(comunicado_reemplazar.Tipo_descarga === 'Manual'){
            //     formData.append('nombre_documento', archivo.name);
            //     formData.append('asunto', archivo.name);
            //     formData.append('nombre_anterior', comunicado_reemplazar.Nombre_documento);
            // }else{
            formData.append('nombre_documento', comunicado_reemplazar.Nombre_documento);
            formData.append('asunto', comunicado_reemplazar.Asunto);
            formData.append('nombre_anterior', '');
            // }
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
            document.getElementById('extensionInvalidaMensaje').textContent = initValueExtension;
            if(comunicado_reemplazar.Tipo_descarga !== 'Manual'){
                if (!document.getElementById('extensionInvalidaMensaje').textContent.includes(extensionDoc)) {
                    document.getElementById('extensionInvalidaMensaje').textContent += extensionDoc;
                }
                return $(".extensionInvalidaModal").removeClass('d-none');
            }
            if (!document.getElementById('extensionInvalidaMensaje').textContent.includes(extensionDocManual)) {
                document.getElementById('extensionInvalidaMensaje').textContent += extensionDocManual;
            }
            return $(".extensionInvalidaModal").removeClass('d-none');
        }
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
    // Captura Formulario DML ORIGEN PREVISIONAL (DICTAMEN)
    $("form[id^='Form_dml_origen_previsional_']").submit(function (e){
        e.preventDefault();          
        var Id_Evento = $("#Id_Evento").val();
        var informacion_comunicado = $(this).data("info_comunicado");
        
        if(informacion_comunicado.Reemplazado == 1){
            var nombre_doc = informacion_comunicado.Nombre_documento;
            var enlaceDescarga = document.createElement('a');
            enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+Id_Evento;     
            enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
            enlaceDescarga.style.display = 'none';
            document.body.appendChild(enlaceDescarga);
            enlaceDescarga.click();
            setTimeout(function() {
                document.body.removeChild(enlaceDescarga);
            }, 1000);
        }
        else{
            // if(informacion_comunicado.Nombre_documento){
            //     var nombre_doc = informacion_comunicado.Nombre_documento;
            //     var enlaceDescarga = document.createElement('a');
            //     enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+Id_Evento;     
            //     enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
            //     enlaceDescarga.style.display = 'none';
            //     document.body.appendChild(enlaceDescarga);
            //     enlaceDescarga.click();
            //     setTimeout(function() {
            //         document.body.removeChild(enlaceDescarga);
            //     }, 1000);
            // }
            // else{
                datos_generacion_proforma_dml_previsional = dataCreacionDMLADX(informacion_comunicado.Id_Comunicado);
                $.ajax({    
                    type:'POST',
                    url:'/ADescargaProformaDMLPrev',
                    data: datos_generacion_proforma_dml_previsional,
                    beforeSend:  function() {
                        $("#btn_enviar_dictamen_previsional").addClass("descarga-deshabilitada");
                    },
                    success: function (response, status, xhr) {
    
                        // Obtener el contenido codificado en base64 del PDF desde la respuesta
                        var base64Pdf = response.pdf;
    
                        // Decodificar base64 en un array de bytes
                        var binaryString = atob(base64Pdf);
                        var len = binaryString.length;
                        var bytes = new Uint8Array(len);
    
                        for (var i = 0; i < len; i++) {
                            bytes[i] = binaryString.charCodeAt(i);
                        }
    
                        // Crear un Blob a partir del array de bytes
                        var blob = new Blob([bytes], { type: 'application/pdf' });
    
                        // Crear un enlace de descarga similar al ejemplo anterior
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = response.nombre_documento;  // Reemplaza con el nombre deseado para el archivo PDF
                
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
                        if(informacion_comunicado.Nombre_documento == null){
                            location.reload();
                        }
                    },       
                });
            // }
        }

    });

    // Captura Formulario PDF Notificación del DML previsional (OFICIO)
    $("form[id^='Form_noti_dml_previsional_']").submit(function (e){
        e.preventDefault();   
        var Id_Evento = $("#Id_Evento").val();
        var informacion_comunicado = $(this).data("info_comunicado");

        if(informacion_comunicado.Reemplazado == 1){
            var nombre_doc = informacion_comunicado.Nombre_documento;
            var enlaceDescarga = document.createElement('a');
            enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+Id_Evento;     
            enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
            enlaceDescarga.style.display = 'none';
            document.body.appendChild(enlaceDescarga);
            enlaceDescarga.click();
            setTimeout(function() {
                document.body.removeChild(enlaceDescarga);
            }, 1000);
        }
        else{
            if(informacion_comunicado.Nombre_documento){
                var nombre_doc = informacion_comunicado.Nombre_documento;
                var enlaceDescarga = document.createElement('a');
                enlaceDescarga.href = '/descargar-archivo/'+nombre_doc+'/'+Id_Evento;     
                enlaceDescarga.target = '_self'; // Abrir en una nueva ventana/tab
                enlaceDescarga.style.display = 'none';
                document.body.appendChild(enlaceDescarga);
                enlaceDescarga.click();
                setTimeout(function() {
                    document.body.removeChild(enlaceDescarga);
                }, 1000);
            }
            else{
                datos_pdf_noti_dml_previsional = dataCreacionOficioNotificacionADX(informacion_comunicado.Id_Comunicado);
                $.ajax({    
                    type:'POST',
                    url:'/ADescargaProformaNotiDMLPrev',
                    data: datos_pdf_noti_dml_previsional,
                    beforeSend:  function() {
                        $("#enviar_form_noti_previsional").addClass("descarga-deshabilitada");
                    },
                    success: function (response, status, xhr) {
    
                        // Obtener el contenido codificado en base64 del PDF desde la respuesta
                        var base64Pdf = response.pdf;
    
                        // Decodificar base64 en un array de bytes
                        var binaryString = atob(base64Pdf);
                        var len = binaryString.length;
                        var bytes = new Uint8Array(len);
    
                        for (var i = 0; i < len; i++) {
                            bytes[i] = binaryString.charCodeAt(i);
                        }
    
                        // Crear un Blob a partir del array de bytes
                        var blob = new Blob([bytes], { type: 'application/pdf' });
    
                        // Crear un enlace de descarga similar al ejemplo anterior
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = response.nombre_documento;  // Reemplaza con el nombre deseado para el archivo PDF
                
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
                        if(informacion_comunicado.Nombre_documento == null){
                            location.reload();
                        }
                    },       
                });
            }
        }
    });

    /* Funcionalidad para mostrar solo la tabla de comunicados para el rol de Consulta */
    if (idRol == 7) {
        $("#form_Adicion_Dx").addClass('d-none');
        $("#div_comite_interdisciplinario").addClass('d-none');
        $("#div_correspondecia").addClass('d-none');
        $("#editar_correspondencia").addClass('d-none');
        $("#btn_guardar_actualizar_correspondencia").prop('disabled',true);
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
    }

    //Valida si hay radicados duplicados
    setTimeout(function() {
        radicados_duplicados('listado_comunicados_adx');
    }, 500);
});

/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnosticos motivo de calificación*/
function funciones_elementos_fila_diagnosticos_visual(num_consecutivo) {
    // Inicializacion de select 2
    $("#lista_Cie10_fila_visual_"+num_consecutivo).select2({
        //width: '100%',
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_origenCie10_fila_visual_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    $("#lista_lateralidadCie10_fila_visual_"+num_consecutivo).select2({
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
                $("#lista_Cie10_fila_visual_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Cie_diagnostico"]+'">'+data[claves[i]]["CIE10"]+' - '+data[claves[i]]["Descripcion_diagnostico"]+'</option>');
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
                $("#lista_origenCie10_fila_visual_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
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
                $("#lista_lateralidadCie10_fila_visual_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    $(document).on('change', '#lista_Cie10_fila_visual_'+num_consecutivo, function() {        
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
                    $("#nombre_cie10_fila_visual_"+num_consecutivo).val(data[claves[i]]["Descripcion_diagnostico"]);
                }
            }
        });
    });
}

/* Función para añadir los controles de cada elemento de cada fila en la tabla Diagnosticos adicionados */
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