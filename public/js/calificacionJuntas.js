$(document).ready(function(){
    
    var idRol = $("#id_rol").val();
    var Id_evento_expediente = $("#newId_evento").val();
    var Id_proceso_expediente = $("#Id_proceso").val();
    var Id_asignacion_expediente = $("#newId_asignacion").val();
    var Id_servicio_expediente =  $("#Id_servicio").val();

    $(".primer_calificador").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".parte_controvierte_califi").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".jrci_califi_invalidez").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".jrci_califi_invalidez_comunicado").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".jrci_califi_invalidez_copia").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".tipo_pago").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".f_pago_radicacion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Inicializacion del select2 modal generar comunicado

    $(".departamento_destinatario").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".ciudad_destinatario").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".forma_envio").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".state_notificacion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".forma_envio_act").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });  

    $(".reviso").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Inicializacion del select2 modal agregar seguimiento
    $(".causal_seguimiento").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".accion").select2({
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".profesional").select2({
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".listado_tipos_documentos").select2({      
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".listado_tipos_documentos_guias").select2({      
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".fuente_informacion").select2({      
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Inicializar select de modal crear expediente cuadro o tabla de expediente

    // $(".folear_expediente").select2({
    //     width: '100%',
    //     placeholder: "Seleccione una opción",
    //     allowClear:false
    // });

    // llenado de selectores
    let token = $('input[name=_token]').val();
    //Listado de fuente de informacion Calificacion juntas
    let datos_lista_fuente_informacion = {
        '_token': token,
        'parametro':"lista_fuente_informacion",
        'opciones': ['Afiliado','Juntas', 'PCL 45 a 49','Tutela','Otros'] //Opciones que estaran disponibles para seleccion
    };
    
    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_lista_fuente_informacion,
        success:function(data){
            let fuente_info = $("#fuente_info_juntas").val();
            for(const key in data){
                $("#fuente_info_juntas").append(`<option value='${data[key].Id_Parametro}'>${data[key].Nombre_parametro}</option>`);
            }

        }
    });

    /* FUNCIONALIDAD DESCARGA DOCUMENTO */
    $("a[id^='btn_generar_descarga_']").click(function(){
        var id_registro_doc = $(this).data('id_doc_reg_descargar');
        var id_documento = $(this).data('id_documento_descargar');

        var nombre_documento = $("#nombre_documento_descarga_"+id_registro_doc+"_"+id_documento).val();
        var extension_documento = $("#extension_documento_descarga_"+id_registro_doc+"_"+id_documento).val();

        var regex = /IdEvento_(.*?)_IdServicio/;
        var resultado = nombre_documento.match(regex);

        if (resultado) {
            var id_evento = resultado[1];
        } else {
            var id_evento = "";
        }
    
        // Crear un enlace temporal para la descarga
        var enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = '/descargar-archivo/'+nombre_documento+'.'+extension_documento+'/'+id_evento;
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

    //Listado de primer calificador
    let datos_lista_primer_califi = {
        '_token': token,
        'parametro':"lista_primer_calificador"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_lista_primer_califi,
        success:function(data) {
            let IdCalifi = $('select[name=primer_calificador]').val();
            let primercali = Object.keys(data);
            for (let i = 0; i < primercali.length; i++) {
                if (data[primercali[i]]['Id_Parametro'] != IdCalifi) {  
                    $('#primer_calificador').append('<option value="'+data[primercali[i]]["Id_Parametro"]+'">'+data[primercali[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    // Listado parte_controvierte_califi
    let datos_lista_controvienrte_califi = {
        '_token': token,
        'parametro':"lista_controvierte_calificacion"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_lista_controvienrte_califi,
        success:function(data) {
            let IdCalifi_contro = $('select[name=parte_controvierte_califi]').val();
            let partecontro = Object.keys(data);
            for (let i = 0; i < partecontro.length; i++) {
                if (data[partecontro[i]]['Id_Parametro'] != IdCalifi_contro) {  
                    $('#parte_controvierte_califi').append('<option value="'+data[partecontro[i]]["Id_Parametro"]+'">'+data[partecontro[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    //Caso Informacion controversia
    obtener_info_afiliado("#parte_controvierte_califi","#nombre_controvierte_califi"); 
    //Caso dictamen controversia
    obtener_info_afiliado("#primer_calificador","#nom_entidad");

    // Listado Junta Jrci Invalidez
    let datos_lista_juntas_invalidez = {
        '_token': token,
        'parametro':"lista_juntas_invalidez"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_lista_juntas_invalidez,
        success:function(data) {
            let IdJuntaInvalidez = $('select[name=jrci_califi_invalidez]').val();
            let juntajrci = Object.keys(data);
            for (let i = 0; i < juntajrci.length; i++) {
                if (data[juntajrci[i]]['Id_Parametro'] != IdJuntaInvalidez) {  
                    $('#jrci_califi_invalidez').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    //Listado pago honorarios
    let datos_lista_tipo_pagos = {
        '_token': token,
        'parametro':"lista_tipo_pago"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_lista_tipo_pagos,
        success:function(data) {
            let IdTipoPagos = $('select[name=tipo_pago]').val();
            let tipopago = Object.keys(data);
            for (let i = 0; i < tipopago.length; i++) {
                if (data[tipopago[i]]['Id_Parametro'] != IdTipoPagos) {  
                    $('#tipo_pago').append('<option value="'+data[tipopago[i]]["Id_Parametro"]+'">'+data[tipopago[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    //Listado juntas pago
    let datos_lista_juntas_pagos = {
        '_token': token,
        'parametro':"lista_juntas_pago"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_lista_juntas_pagos,
        success:function(data) {
            let IdJuntaPagos = $('select[name=pago_junta]').val();
            let juntapago = Object.keys(data);
            for (let i = 0; i < juntapago.length; i++) {
                if (data[juntapago[i]]['Id_Parametro'] != IdJuntaPagos) {  
                    $('#pago_junta').append('<option value="'+data[juntapago[i]]["Id_Parametro"]+'">'+data[juntapago[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    var Id_proceso_actual = $('#Id_proceso').val();

    // LISTADO DE ACCIONES 
    var datos_listado_accion = {
        '_token': token,
        'parametro' : "listado_accion",
        'Id_proceso' : $("#Id_proceso").val(),
        'Id_servicio': $("#Id_servicio").val(),
        'nro_evento': $("#newId_evento").val(),
        'Id_asignacion': $("#newId_asignacion").val()
    };
    
    $.ajax({
        type: 'POST',
        url: 'selectoresJuntas',
        data: datos_listado_accion,
        success:function(data){
            if (data.length > 0) {
                $("#accion").empty();
                $("#accion").append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    // if (data[claves[i]]["Id_Accion"] == $("#bd_id_accion").val()) {
                    //     $("#accion").append('<option value="'+data[claves[i]]["Id_Accion"]+'" selected>'+data[claves[i]]["Nombre_accion"]+'</option>');
                    // } else {
                        $("#accion").append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Nombre_accion"]+'</option>');
                    // }
                }
                
                $(".no_ejecutar_parametrica_modulo_principal").addClass('d-none');
                $("#Edicion").removeClass('d-none');
                $("#Edicion").prop('disabled',false);
            }else{
                $("#accion").empty();
                $("#accion").append('<option></option>');

                $(".no_ejecutar_parametrica_modulo_principal").removeClass('d-none');
                $("#Edicion").addClass('d-none');
                $("#Edicion").prop('disabled',true);
            }
        }
    });

    // autoseleccion del estado de facturacion y cargue de listado de profesionales dependiendo de la accion cuando la pag carga
    var accion_cargada = $("#bd_id_accion").val();
    if (accion_cargada != '') {
        let datos_ejecutar_parametrica_mod_principal = {
            '_token': token,
            'parametro': "validarSiModPrincipal",
            'Id_proceso': $("#Id_proceso").val(),
            'Id_servicio': $("#Id_servicio").val(),
            'Id_accion': accion_cargada,
            'nro_evento': $("#newId_evento").val()
        };

        $.ajax({
            type:'POST',
            url:'/validacionParametricaEnSi',
            data: datos_ejecutar_parametrica_mod_principal,
            success:function(data) {
                if(data.length > 0){
                    if (data[0]["Modulo_principal"] !== "Si") {
                        $(".no_ejecutar_parametrica_modulo_principal").removeClass('d-none');
                        $("#Edicion").addClass('d-none');
                        $("#Edicion").prop('disabled',true);
                    } else {
                        $(".no_ejecutar_parametrica_modulo_principal").addClass('d-none');
                        $("#Edicion").removeClass('d-none');
                        $("#Edicion").prop('disabled',false);

                        // llenado del input Estado de Facturación
                        $("#estado_facturacion").val(data[0]["Estado_facturacion"]);
                    }
                }
            
            }
        });

        let datos_lista_profesional={
            '_token':token,
            'parametro':"lista_profesional_accion",
            'nro_evento': $("#newId_evento").val(),
            'Id_proceso' : Id_proceso_actual,
            'Id_servicio': $("#Id_servicio").val(),
            'Id_accion': accion_cargada,
        }
    
        $.ajax({
            type:'POST',
            url:'/selectoresJuntas',
            data: datos_lista_profesional,
            success:function (data) {
                //$('#profesional').empty();
                $('#profesional').append('<option value="" >Seleccione</option>');
                let id_profesional= $('select[name=profesional]').val();
                let profecionalpcl = Object.keys(data.info_listado_profesionales);
                for (let i = 0; i < profecionalpcl.length; i++) {
                    if (data.info_listado_profesionales[profecionalpcl[i]]['id'] != id_profesional) {
                        if (data.info_listado_profesionales[profecionalpcl[i]]['id'] == data.Profesional_asignado) {
                            $('#profesional').append('<option value="'+data.info_listado_profesionales[profecionalpcl[i]]['id']+'" >'+data.info_listado_profesionales[profecionalpcl[i]]['name']+'</option>')                    
                        }else{
                            $('#profesional').append('<option value="'+data.info_listado_profesionales[profecionalpcl[i]]['id']+'">'+data.info_listado_profesionales[profecionalpcl[i]]['name']+'</option>')                    
                        }
                    }
                }
            }
        });

        //Selector enviara, seccion 'Accion a realizar'
        let datos_bandeja_destino = {
            '_token':token,
            'parametro':"lista_bandejas_destino",
            'Id_proceso' : Id_proceso_actual,
            'Id_cliente' : $("#cliente").data('id'),
            'Id_servicio': $("#Id_servicio").val(),
            'Id_accion': accion_cargada,
        }
        
        $.ajax({
            type:'POST',
            url:'/selectoresJuntas',
            data: datos_bandeja_destino,
            success:function (data) {
                $('#enviar').empty();
                $('#enviar').append(`<option value="${data.bd_destino}" selected>${data.Nombre_proceso}</option>`);
            }
        });
    }

    $("#accion").change(async function(){
        let datos_ejecutar_parametrica_mod_principal = {
            '_token': token,
            'parametro': "validarSiModPrincipal",
            'Id_proceso': $("#Id_proceso").val(),
            'Id_servicio': $("#Id_servicio").val(),
            'Id_accion': $(this).val(),
            'nro_evento': $("#newId_evento").val()
        };

        $.ajax({
            type:'POST',
            url:'/validacionParametricaEnSi',
            data: datos_ejecutar_parametrica_mod_principal,
            success:function(data) {
                if(data.length > 0){
                    if (data[0]["Modulo_principal"] !== "Si") {
                        $(".no_ejecutar_parametrica_modulo_principal").removeClass('d-none');
                        $("#Edicion").addClass('d-none');
                        $("#Edicion").prop('disabled',true);
                    } else {
                        $(".no_ejecutar_parametrica_modulo_principal").addClass('d-none');
                        $("#Edicion").removeClass('d-none');
                        $("#Edicion").prop('disabled',false);

                        // llenado del input Estado de Facturación
                        $("#estado_facturacion").val(data[0]["Estado_facturacion"]);
                    }
                }
            
            }
        });

        let datos_lista_profesional = {
            '_token':token,
            'parametro':"lista_profesional_accion",
            // 'id_proceso' : Id_proceso_actual,
            'Id_proceso' : Id_proceso_actual,
            'Id_servicio': $("#Id_servicio").val(),
            'Id_accion': $(this).val(),
            'nro_evento': $('#id_evento').val()
        }
    
        await $.ajax({
            type:'POST',
            url:'/selectoresJuntas',
            data: datos_lista_profesional,
            success:function (data) {
                $('#profesional').empty();
                $('#profesional').append('<option value="" selected>Seleccione</option>');
                let id_profesional= $('select[name=profesional]').val();
                let profesionaljuntas = Object.keys(data.info_listado_profesionales);
                for (let i = 0; i < profesionaljuntas.length; i++) {
                    if (data.info_listado_profesionales[profesionaljuntas[i]]['id'] != id_profesional) {
                        if (data.info_listado_profesionales[profesionaljuntas[i]]['id'] == data.Profesional_asignado) {
                            $('#profesional').append('<option value="'+data.info_listado_profesionales[profesionaljuntas[i]]['id']+'" selected>'+data.info_listado_profesionales[profesionaljuntas[i]]['name']+'</option>')                    
                        }else{
                            $('#profesional').append('<option value="'+data.info_listado_profesionales[profesionaljuntas[i]]['id']+'">'+data.info_listado_profesionales[profesionaljuntas[i]]['name']+'</option>')                    
                        }
                    }
                }
            }
        });

        //Selector enviara, seccion 'Accion a realizar'
        let datos_bandeja_destino = {
            '_token':token,
            'parametro':"lista_bandejas_destino",
            'Id_proceso' : Id_proceso_actual,
            'Id_cliente' : $("#cliente").data('id'),
            'Id_servicio': $("#Id_servicio").val(),
            'Id_accion': $(this).val(),
        }

        $.ajax({
            type:'POST',
            url:'/selectoresModuloCalificacionPCL',
            data: datos_bandeja_destino,
            success:function (data) {
                // console.log(data);
                $('#enviar').empty();
                $('#enviar').append(`<option value="${data.bd_destino}" selected>${data.Nombre_proceso}</option>`);
            }
        });
        //RECORDATORIO ACTUALIZACION FECHA DE RADICACIÓN PBS068
        if(($("#Id_servicio").val() == 12 || $("#Id_servicio").val() == 13) && ($(this).val() == 155 || $(this).val() == 162 || $(this).val() == 163 || $(this).val() == 164
            || $(this).val() == 168 || $(this).val() == 169 || $(this).val() == 174)){
            $('#modalAlerta').modal('show');
            $('#mensaje_alerta').text('Recuerde actualizar la fecha de radicación en el campo Nueva fecha de radicación. Si ya la actualizó o no lo requiere por favor omita éste mensaje.');
            setTimeout(() => {
                $('#modalAlerta').modal('hide');
                $('#mensaje_alerta').text('');
            }, 4000);
        }
        //Capturar el ultimo que ejecuto la acción de asignación, cuando seleccionen la acción de devolver asignación PBS068
        if($(this).val() == 131){
            data_ult_usuario = {
                '_token':token,
                'id_proceso' : $('#Id_proceso').val(),
                'id_cliente' : $("#cliente").data('id'),
                'id_servicio': $("#Id_servicio").val(),
                'id_evento': $("#newId_evento").val(),
                'id_asignacion':$('#newId_asignacion').val(),
                'id_accion_devolucion': $(this).val()
            };
            let ultimoUsuario = await consultaUltimoUsuarioEjecutarAccion(data_ult_usuario);
            if(ultimoUsuario[0] && Array.isArray(ultimoUsuario[1])){
                let info_user = ultimoUsuario[1][0];
                $('#profesional option[value="'+info_user['id']+'"]').prop('selected', true);
                $('#profesional').val(info_user['id']).trigger('change');
            }
        }
    });

    //Mostrar Historial de acciones
    $('#Hacciones').click(function(){
        $('#borrar_tabla_historial_acciones').empty();

        var datos_llenar_tabla_historial_acciones = {
            '_token': $('input[name=_token]').val(),
            'ID_evento' : $('#id_evento').val(),
            'Id_proceso': $('#Id_proceso').val()
        };
         
        $.ajax({
            type:'POST',
            url:'/historialAccionesEventosJun',
            data: datos_llenar_tabla_historial_acciones,
            success:function(data) {
                if(data.length == 0){
                    $('#borrar_tabla_historial_acciones').empty();
                }else{
                    // console.log(data);
                    var descargaDocHistorial = '';

                    for (let i = 0; i < data.length; i++) {                                   
                        
                        if (data[i]['Documento'] != 'N/A'){
                            descargaDocHistorial = '<a href="javascript:void(0);" id="DescargaHistorialdoc_' + data[i]['Id_historial_accion'] + '" data-id_doc_descargar="' + data[i]['Id_historial_accion'] + '"><i class="fas fa-download text-info"></i></a>' + 
                                        '<input type="hidden" name="nom_archivo" id="nom_archivo" value="'+data[i]["Documento"]+'">'+
                                        '<input type="hidden" type="text" name="Id_historial_accion" id="Id_historial_accion" value="'+data[i]["Id_historial_accion"]+'">'+                                        
                                        '<input type="hidden" name="ID_evento" id="ID_evento" value="'+data[i]["ID_evento"]+'">'+
                                        '<input type="hidden" name="Id_proceso" id="Id_proceso" value="'+data[i]["Id_proceso"]+'">'+
                                        '<input type="hidden" name="Id_servicio" id="Id_servicio" value="'+data[i]["Id_servicio"]+'">';                          
                            data[i]['descargardoc'] = descargaDocHistorial;
                            
                        }else{
                            data[i]['descargardoc'] = ""; 
                        } 
                    } 

                    $.each(data, function(index, value){
                        llenar_historial_acciones(data, index, value);
                    });
                }
            }
        });
    });

    function llenar_historial_acciones(response, index, value){
        $('#listado_historial_acciones_evento').DataTable({
            dom: 'Bfrtip',                      
            buttons:{
                dom:{
                    buttons:{
                        className: 'btn'
                    }
                },
                buttons:[
                    {
                        extend:"excel",
                        title: 'Historial de acciones',
                        text:'Exportar historial',
                        className: 'btn btn-info',
                        "excelStyles": [                      // estilos de excel
                                                    
                        ],
                        //Limitar columnas para el reporte
                        exportOptions: {
                            columns: [0,1,2,3]
                        }  
                    }
                ]
            },
            "processing": true, 
            "destroy": true,
            "data": response,
            "order": [[0, 'desc']],
            "columns":[
                {"data":"F_accion"},
                {"data":"Nombre_usuario"},
                {"data":"Accion"},
                {"data":"Descripcion"},
                {"data":"descargardoc"},
            ],
            "language":{
                "search": "Buscar",
                "processing": '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
                "info": "Mostrando registros _START_ de _END_ de un total de _TOTAL_ registros",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "first": "Primero",
                    "last": "Último"
                },
                "emptyTable": "No se encontró información",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            }
        });
    } 

    // Descargar documento del historial de acciones
    $(document).on('click', 'a[id^="DescargaHistorialdoc_"]', function() {
        var id_documento = $(this).data('id_doc_descargar');
        var nom_archivo = $(this).siblings('input[name="nom_archivo"]').val();
        var ID_evento = $(this).siblings('input[name="ID_evento"]').val();     
    
        // Crear un enlace temporal para la descarga
        var enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = '/descargar-archivo/'+nom_archivo+'/'+ID_evento;
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

    // Funcion para que permitirá que el usuario seleccione cualquier hora a partir de la 
    //fecha actual, sin restricciones de hora, minutos o segundos específicos,
    //pero que la fecha no sea inferior a al actual

    var Fecha_alerta_capturada = $('#fecha_alerta');
    var hoyactual = new Date();
    var diaactual = hoyactual.getDate();
    var mesactual = hoyactual.getMonth() + 1; // Los meses empiezan en 0
    var anioactual = hoyactual.getFullYear();
    // Añadir un cero al día y al mes si son menores de 10
    if(diaactual < 10) {
        diaactual = '0' + diaactual;
    }
    if(mesactual < 10) {
        mesactual = '0' + mesactual;
    }
    var fechaActual_alerta = anioactual + '-' + mesactual + '-' + diaactual;
    Fecha_alerta_capturada.change(function() {
        var valor_Fecha_alerta_capturada = $(this).val();        
        // Se saca solo la fecha de la F_alerta_capturada
        var F_alerta_capturada = valor_Fecha_alerta_capturada.split('T')[0];        
        if (F_alerta_capturada == ''){
            $('#Edicion').prop('disabled', false)
            $('#alerta_fecha_alerta').addClass('d-none');
        }else if (F_alerta_capturada < fechaActual_alerta) {
            $('#Edicion').prop('disabled', true)
            $('#alerta_fecha_alerta').removeClass('d-none');
        }else if (F_alerta_capturada >= fechaActual_alerta){
            $('#Edicion').prop('disabled', false)
            $('#alerta_fecha_alerta').addClass('d-none');
        }
    });
    // console.log(Fecha_alerta_capturada.val());
    if (Fecha_alerta_capturada.val() == '') {
        // console.log('if');
        $('#Edicion').prop('disabled', false)
        $('#alerta_fecha_alerta').addClass('d-none');
    }else if (Fecha_alerta_capturada.val() < fechaActual_alerta){
        // console.log('else');
        $('#Edicion').prop('disabled', true) 
        $('#alerta_fecha_alerta').removeClass('d-none');
    }

    // llenado del formulario para la captura de datos del modulo de Juntas
    $('#form_calificacionJuntas').submit(function (e) {
        e.preventDefault();  
        // Deshabilitar elementos mientras se realiza la petición                
        document.querySelector("#Edicion").disabled = true;
        // document.querySelector("#Borrar").disabled = true;

        // Obtener el archivo seleccionado
        var archivo = $('#cargue_documentos')[0].files[0];

        // Crear un objeto FormData para enviar el archivo
        var formData = new FormData($('form')[0]);
        formData.append('cargue_documentos', archivo);
        // Agregar otros datos al formData
        formData.append('token', $('input[name=_token]').val());

        formData.append('newId_evento', $('#newId_evento').val());
        formData.append('newId_asignacion', $('#newId_asignacion').val());
        formData.append('Id_proceso', $('#Id_proceso').val());
        formData.append('Id_servicio', $("#Id_servicio").val());
        formData.append('nueva_fecha_radicacion', $('#nueva_fecha_radicacion').val());
        formData.append('accion', $('#accion').val());
        formData.append('fecha_radicacion_actual', $('#fecha_radicacion').val());
        formData.append('fecha_alerta', $('#fecha_alerta').val());
        formData.append('enviar', $('#enviar').val());
        formData.append('estado_facturacion', $('#estado_facturacion').val());
        formData.append('profesional', $('#profesional').val());
        formData.append('descripcion_accion', $('#descripcion_accion').val());
        formData.append('fuente_info_juntas', $('#fuente_info_juntas').val());
        formData.append('fecha_cierre', $('#fecha_cierre').val());
        formData.append('fecha_vencimiento_actual', $('#fecha_vencimiento_actual').val());
        formData.append('tiempo_gestion', $('#tiempo_gestion').val());
        formData.append('fecha_remision_expediente',$('#fecha_remision_expediente').val());
        formData.append('fecha_pronunciamiento',$('#fecha_pronunciamiento').val());
        formData.append('profesional_remision_expediente',$('#profesional_remision_expediente_sin_tipo_colaborador').val());
        formData.append('profesional_pronunciamiento',$('#profesional_pronunciamiento_sin_tipo_colaborador').val());
        formData.append('id_profesional_remision_expediente',$('#id_profesional_remision_expediente').val());
        formData.append('id_profesional_pronunciamiento',$('#id_profesional_pronunciamiento').val());
        formData.append('banderaguardar', $('#bandera_accion_guardar_actualizar').val());
        $.ajax({
            type:'POST',
            url:'/registrarCalificacionJuntas',
            data: formData,
            processData: false,
            contentType: false,
            success:function(response){
                if (response.parametro == 'agregarCalificacionJuntas') {
                    $('.alerta_calificacion').removeClass('d-none');
                    if (response.parametro_1 == "guardo") {
                        $('.alerta_calificacion').append('<strong>'+response.mensaje_1+' Y '+response.mensaje_2+'</strong>');
                    } else {
                        $('.alerta_calificacion').append('<strong>'+response.mensaje+' Y '+response.mensaje_2+'</strong>');
                    }
                    setTimeout(function(){
                        $('.alerta_calificacion').addClass('d-none');
                        $('.alerta_calificacion').empty(); 
                        location.reload();                       
                    }, 5000);
                }                
            }
        })        
        // location.reload();
    });

    //Listado de los tipos de documento que pueden subir
    let datos_lista_tipos_documentos = {
        '_token': token,
        'evento': $("#newId_evento").val(),
        'servicio': $("#Id_servicio").val(),
        'asignacion': $("#newId_asignacion").val(),
        'parametro':"lista_tipos_docs",
    };
    
    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_lista_tipos_documentos,
        success:function(data) {
            let tiposdoc = Object.keys(data);
            for (let i = 0; i < tiposdoc.length; i++) {
                $('#listado_tipos_documentos').append('<option value="'+data[tiposdoc[i]]["Nro_documento"]+'">'+data[tiposdoc[i]]["Nro_documento"]+' - '+data[tiposdoc[i]]["Nombre_documento"]+'</option>');
            }
        }
    });

    // seteo del id, nombre del documento familia, id evento, id servicio
    $("#listado_tipos_documentos").change(function(){
        var id_doc_familia_seleccionado = $(this).val();
        var nombre_doc_familia_seleccionado = $(this).find("option:selected").text().replace(/^\d+\s*-\s*/, '');
        $("#id_doc_familia").val(id_doc_familia_seleccionado);
        $("#nombre_doc_familia").val(nombre_doc_familia_seleccionado);

        var evento = $("#newId_evento").val();
        var servicio = $("#Id_servicio").val();
        var asignacion = $("#newId_asignacion").val();        
        $("#id_evento_familia").val(evento);
        $("#id_servicio_familia").val(servicio);
        $("#id_asignacion_familia").val(asignacion);
    });

    /* Envío de información del documento familia */
    $("#familia_documentos").submit(function(e){
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        // for (var pair of formData.entries()) {
        //     console.log(pair[0] + ": " + pair[1]);
        // }
    
        $.ajax({
            url: "/cargaDocumentosComplementarios",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                if (response.parametro == "fallo") {
                    if (response.otro != undefined) {
                        $('#listadodocumento_'+response.otro).val('');
                    }else{
                        $('#doc_subir').val('');
                    }
                    $('.mostrar_fallo_doc_familia').removeClass('d-none');
                    $('.mostrar_fallo_doc_familia').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_fallo_doc_familia').addClass('d-none');
                        $('.mostrar_fallo_doc_familia').empty();
                    }, 6000);
                }else if (response.parametro == "exito") {
                    // if(response.otro != undefined){
                    //     $("#estadoDocumentoOtro_"+response.otro).empty();
                    //     $("#estadoDocumentoOtro_"+response.otro).append('<strong class="text-success">Cargado</strong>');
                    //     $('#listadodocumento_'+response.otro).prop("disabled", true);
                    //     $('#CargarDocumento_'+response.otro).prop("disabled", true);
                    //     $('#habilitar_modal_otro_doc').prop("disabled", true);
                    // }else{
                    //     $("#"+cambio_estado).empty();
                    //     $("#"+cambio_estado).append('<strong class="text-success">Cargado</strong>');
                    // }

                    $('.mostrar_exito_doc_familia').removeClass('d-none');
                    $('.mostrar_exito_doc_familia').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_exito_doc_familia').addClass('d-none');
                        $('.mostrar_exito_doc_familia').empty();
                    }, 6000);
                }else{}

            }         
        });
    });

    /* Envío de Información para eliminar el documento Complementario */
    $("form[id^='form_eliminar_doc_complementario_']").submit(function(e){
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        // for (var pair of formData.entries()) {
        //     console.log(pair[0] + ": " + pair[1]);
        // }
        $.ajax({
            url: "/eliminarDocumentoComplementario",
            type: "post",
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                if (response.parametro == "fallo") {
                    $('.mostrar_fallo').removeClass('d-none');
                    $('.mostrar_fallo').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_fallo').addClass('d-none');
                        $('.mostrar_fallo').empty();
                    }, 6000);
                }else if (response.parametro == "exito") {
                    $('.mostrar_exito').removeClass('d-none');
                    $('.mostrar_exito').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_exito').addClass('d-none');
                        $('.mostrar_exito').empty();
                    }, 6000);
                }else{}
            }         
        });
    });

    /* INICIO FUNCIONALIDAD DEL CARGUE DE DOCUMENTOS COMPLEMENTARIOS (GUIA) */
    // seteo del id, nombre del documento familia, id evento, id servicio
    $("#CargarDocumento_guias").prop('disabled', true);
    $("#listado_tipos_documentos_guias").change(function(){
        var id_doc_familia_seleccionado = $(this).val();
        var nombre_doc_familia_seleccionado = $(this).find("option:selected").text().replace(/^\d+\s*-\s*/, '');
        $("#id_doc_familia_guias").val(id_doc_familia_seleccionado);
        $("#nombre_doc_familia_guias").val(nombre_doc_familia_seleccionado);

        var evento = $("#newId_evento").val();
        var servicio = $("#Id_servicio").val();
        var asignacion = $("#newId_asignacion").val();

        $("#id_evento_familia_guias").val(evento);
        $("#id_servicio_familia_guias").val(servicio);
        $("#id_asignacion_familia_guias").val(asignacion);
        
        if (id_doc_familia_seleccionado != "") {
            $("#CargarDocumento_guias").prop('disabled', false);
        }
    });
    
    /* Envío de información del documento familia */
    $("#CargarDocumento_guias").click(function(){
        
        let formData = new FormData();
        formData.append('_token', $('input[name=_token]').val());
        formData.append('id_doc_familia', $("#id_doc_familia_guias").val());
        formData.append('nombre_doc_familia', $("#nombre_doc_familia_guias").val());
        formData.append('id_evento_familia', $("#id_evento_familia_guias").val());
        formData.append('id_servicio_familia', $("#id_servicio_familia_guias").val());
        formData.append('id_asignacion_familia', $("#id_asignacion_familia_guias").val());
        formData.append('doc_subir', $("#doc_subir_guias")[0].files[0]);
    
        $.ajax({
            url: "/cargaDocumentosComplementarios",
            method: 'POST',
            data: formData,
            processData: false, // No procesar los datos automáticamente
            contentType: false, // Establecer contentType en false
            success:function(response){
                if (response.parametro == "fallo") {
                    if (response.otro != undefined) {
                        $('#listadodocumento_'+response.otro).val('');
                    }else{
                        $('#doc_subir_guias').val('');
                    }
                    $('.mostrar_fallo_doc_familia').removeClass('d-none');
                    $('.mostrar_fallo_doc_familia').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_fallo_doc_familia').addClass('d-none');
                        $('.mostrar_fallo_doc_familia').empty();
                    }, 6000);
                }else if (response.parametro == "exito") {
                    $('.mostrar_exito_doc_familia').removeClass('d-none');
                    $('.mostrar_exito_doc_familia').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_exito_doc_familia').addClass('d-none');
                        $('.mostrar_exito_doc_familia').empty();
                    }, 6000);
                }else{}

            }         
        });
    });

    /* FIN FUNCIONALIDAD DEL CARGUE DE DOCUMENTOS COMPLEMENTARIOS (GUIA) */

    //Se crea el resumable usando Resumable.js, el cual tiene como fin crear los chunks y enviarlos al endpoint('target') especificado 
    let resumable = new Resumable({
        target: '/upload',
        query:{_token:$("input[name='_token']").val()},
        maxFiles: 1,
        fileType: ['pdf','xls','xlsx','doc','docx','jpeg','png','zip'],
        testChunks: false,
        headers: {
            'Accept' : 'application/json'
        },
    });

    /* Obtener el ID del evento a dar clic en cualquier botón de cargue de archivo y asignarlo al input hidden del id evento */
    $("input[id^='listadodocumento_']").click(function(){
        let idobtenido = $('#newId_evento').val();
        let tipoDoc = $(this).data('tipo_documento');
        let idDoc = $(this).data('id_doc');
        $("input[id^='EventoID_']").val(idobtenido);
        if(idDoc === 4 && !tipoDoc){
            //Tomamos el input seleccionado
            let inputFile = $(`#listadodocumento_${idDoc}`)
            //Le asignamos el metodo de entrada de archivo el cual viene de nuestro input
            resumable.assignBrowse(inputFile[0]);
            //Esta función detecta cuando un archivo fue cargado
            resumable.on('fileAdded', function (file) {
                $(`#fileName_${idDoc}`).text(file.fileName);
                resumable.opts.query.EventoID = idobtenido;
                resumable.opts.query.Id_Documento = idDoc;
                resumable.opts.query.Nombre_documento = $(`#Nombre_documento_${idDoc}`).val().replace(/ /g, "_");
                resumable.opts.query.Id_servicio = $(`#Id_servicio_${idDoc}`).val();
                resumable.opts.query.Id_asignacion = $(`#Id_asignacion_${idDoc}`).val();
            });
        }
    });
    //Mostrar modal de progressBar cargue de documentos
    function showProgress() {
        let progress = $('.progress');
        $('#modalProgressBar').show();
        progress.find('.progress-bar').css('width', '0%');
        progress.find('.progress-bar').html('0%');
        progress.find('.progress-bar').removeClass('bg-success');
        progress.show();
    }

    //Actualización progressBar cargue de documentos
    function updateProgress(value) {
        let progress = $('.progress');
        progress.find('.progress-bar').css('width', `${value}%`)
        progress.find('.progress-bar').html(`${value}%`)
    }

    //Errores al cargar un documento en Historia clinica completa
    let errorCargueDocumentosID4 = (error,time=2000) => {
        if ($('.mostrar_fallo').hasClass('d-none')) {
            $('.mostrar_fallo').removeClass('d-none');
            $('.mostrar_fallo').append(`<strong>${error}</strong>`);
            setTimeout(function(){
                $('.mostrar_fallo').addClass('d-none');
                $('.mostrar_fallo').empty();
            }, time);
        }
    }

    let tipo_eventoJuntas = {
        '_token': $('input[name=_token]').val(),
        'parametro': 'lista_tipo_evento_juntas'
    }

    $.post('/selectoresJuntas',tipo_eventoJuntas,function(data){
        let tipo_selecionado = $("#Tipo_evento_juntas :selected").val();

        data.forEach(function(item){
            if(item.Id_Evento != tipo_selecionado){
                $("#Tipo_evento_juntas").append(`<option value="${item.Id_Evento}">${item.Nombre_evento}</option>`);
            }
        });
    });

    /* Envío de Información del Documento a Cargar */
    var fechaActual = new Date().toISOString().slice(0,10);
    $("form[id^='formulario_documento_']").submit(function(e){

        e.preventDefault();
        var id_reg_doc = $(this).data("id_reg_doc");
        var id_doc = $(this).data("id_doc");
        let tipoDoc = $(this).data('tipo_documento');

        var formData = new FormData($(this)[0]);
        var cambio_estado = $(this).parents()[1]['children'][2]["id"];
        var input_documento = $(this).parents()[0]['children'][0][4]["id"];

        //for (var pair of formData.entries()) {
        //   console.log(pair[0]+ ', ' + pair[1]); 
        //}
        if(id_doc === 4 && !tipoDoc){
            //Validación de posibles errores antes de enviar el documento
            if(resumable.opts.query.EventoID === ""){
                errorCargueDocumentosID4('Debe diligenciar primero el formulario para poder cargar este documento.')
            }
            if(resumable.opts.query.Id_servicio === ""){
                errorCargueDocumentosID4('Debe seleccionar un servicio para poder cargar este documento.')
            }
            let file = resumable.files;
            if(resumable.files.length > 0){
                if(file[0].size > 1000000000){
                    return errorCargueDocumentosID4('El tamaño máximo permitido para cargar en este documento es de 1Gb.');
                }
                showProgress();
                resumable.upload();
            }
            else{
                errorCargueDocumentosID4('Debe cargar este documento para poder guardarlo.');
            }

            resumable.on('fileProgress', function (file) { // trigger when file progress update
                updateProgress(Math.floor(file.progress() * 100));
            });

            resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
                response = JSON.parse(response)
                if (response.parametro == "exito") {
                    $("#fecha_cargue_documento_"+id_reg_doc+"_"+id_doc).val(fechaActual);
                    $("#"+cambio_estado).empty();
                    $("#"+cambio_estado).append('<strong class="text-success">Cargado</strong>');
                    $('.mostrar_exito').removeClass('d-none');
                    $('.mostrar_exito').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.mostrar_exito').addClass('d-none');
                        $('.mostrar_exito').empty();
                    }, 6000);
                }
                setTimeout(() => {
                    $('#modalProgressBar').hide();
                }, 500);
            });

            resumable.on('fileError', function (file, response) { // trigger when there is any error
                alert('file uploading error.')
            });
        }
        else{
            // Enviamos los datos para validar y guardar el docmuento correspondiente
            $.ajax({
                url: "/cargarDocumentos",
                type: "post",
                dataType: "json",
                data: formData,
                cache: false,
                contentType: false,
                processData: false  ,
                success:function(response){
                    // console.log(response);
                    if (response.parametro == "fallo") {
                        if (response.otro != undefined) {
                            $('#listadodocumento_'+response.otro).val('');
                        }else{
                            $('#'+input_documento).val('');
                        }
                        $('.mostrar_fallo').removeClass('d-none');
                        $('.mostrar_fallo').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(function(){
                            $('.mostrar_fallo').addClass('d-none');
                            $('.mostrar_fallo').empty();
                        }, 6000);
                    }else if (response.parametro == "exito") {
                        $("#fecha_cargue_documento_"+id_reg_doc+"_"+id_doc).val(fechaActual);
                        if(response.otro != undefined){
                            $("#estadoDocumentoOtro_"+response.otro).empty();
                            $("#estadoDocumentoOtro_"+response.otro).append('<strong class="text-success">Cargado</strong>');
                            $('#listadodocumento_'+response.otro).prop("disabled", true);
                            $('#CargarDocumento_'+response.otro).prop("disabled", true);
                            $('#habilitar_modal_otro_doc').prop("disabled", true);
                        }else{
                            $("#"+cambio_estado).empty();
                            $("#"+cambio_estado).append('<strong class="text-success">Cargado</strong>');
                        }
                        $('.mostrar_exito').removeClass('d-none');
                        $('.mostrar_exito').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(function(){
                            $('.mostrar_exito').addClass('d-none');
                            $('.mostrar_exito').empty();
                        }, 6000);
                    }else{}
                    

                }         
            });
        }
    });

    /* VALIDACIÓN MOSTRAR ENFERMEDAD HEREDADA  */ 
    var opt_tipo_evento;
    $("#Tipo_evento_juntas").change(function(){
        opt_tipo_evento = parseInt($(this).val());
        $("#Tipo_evento_juntas").val(opt_tipo_evento);
        iniciarIntervalo_tEvento();
    });

    // Función para validar items a mostrar
    function iniciarIntervalo_tEvento() {
        intervalo = setInterval(() => {
            //console.log(opt_tipo_evento)
            switch (opt_tipo_evento) {
                case 2:
                    $("#contenedor_enfermedad").removeClass('d-none');
                    
                break;
                default:
                    // Deslizar hacia arriba (ocultar) los elementos
                    $('#enfermedad_heredada').prop('checked', false);
                    $("#contenedor_enfermedad").addClass('d-none');
                    $("#contenedor_enfermedad_fecha").addClass('d-none');
                break;
            }

        }, 500);

    }
    
    /* VALIDACIÓN MOSTRAR FECHA ENFERMEDAD */
    $("#enfermedad_heredada").click(function(){
        if ($(this).is(":checked")) {
            $("#contenedor_enfermedad_fecha").removeClass('d-none');
            $('#f_transferencia_enfermedad').prop('required', true);
        }else{
            $("#contenedor_enfermedad_fecha").addClass('d-none');
            $('#f_transferencia_enfermedad').prop('required', false);
            $("#f_transferencia_enfermedad").val("");
        }
    });

    //Guardar datos controvertidos
    $('#form_guardarControvertido').submit(function (e) {
        e.preventDefault();  
        let token = $('input[name=_token]').val();  
        var datos_controvertido = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'enfermedad_heredada': $('#enfermedad_heredada').filter(":checked").val(),
            'f_transferencia_enfermedad': $('#f_transferencia_enfermedad').val(),
            'primer_calificador': $('#primer_calificador').val(),
            'nom_entidad': $('#nom_entidad').val(),
            'N_dictamen_controvertido': $('#N_dictamen_controvertido').val(),
            'f_dictamen_controvertido': $('#f_dictamen_controvertido').val(),
            'n_siniestro': $('#n_siniestro').val(),
            'f_notifi_afiliado': $('#f_notifi_afiliado').val(),
            'f_contro_primer_califi': $('#f_contro_primer_califi').val(),
            'tipo_evento': $("#Tipo_evento_juntas :selected").val(),
            'bandera_controvertido_guardar_actualizar': $('#guardar_datos_controvertido').val(),
        }
        document.querySelector("#guardar_datos_controvertido").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarControvertido',
            data: datos_controvertido,            
            success:function(response){
                if (response.parametro == 'agregar_controvertido') {
                    $('.alerta_controvertido').removeClass('d-none');
                    $('.alerta_controvertido').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_controvertido').addClass('d-none');
                        $('.alerta_controvertido').empty();
                        localStorage.setItem("#Generar_controvertido", true);
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 

    // Abrir modal una vez se guarde item de controvertido
    if (localStorage.getItem("#Generar_controvertido")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#Generar_controvertido");
        document.querySelector("#clicGuardado").click();
    }
    
    //Guardar datos controversia
    $('#form_guardarControversia').submit(function (e) {
        e.preventDefault();  
        let token = $('input[name=_token]').val();  
        var datos_controversia = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'parte_controvierte_califi': $('#parte_controvierte_califi').val(),
            'nombre_controvierte_califi': $('#nombre_controvierte_califi').val(),
            'n_radicado_entrada_contro': $('#n_radicado_entrada_contro').val(),
            'contro_origen': $('#contro_origen').filter(":checked").val(),
            'contro_pcl': $('#contro_pcl').filter(":checked").val(),
            'contro_diagnostico': $('#contro_diagnostico').filter(":checked").val(),
            'contro_f_estructura': $('#contro_f_estructura').filter(":checked").val(),
            'contro_m_califi': $('#contro_m_califi').filter(":checked").val(),
            'f_contro_primer_califi': $('#f_contro_primer_califi').val(),
            'f_contro_radi_califi': $('#f_contro_radi_califi').val(),
            'f_notifi_afiliado': $('#f_notifi_afiliado').val(),
            'termino_contro_califi': $('#termino_contro_califi').val(),
            'jrci_califi_invalidez': $('#jrci_califi_invalidez').val(),
            'fecha_envio_jrci': $('#f_envio_jrci').val(),
            'fecha_envio_jnci': $('#f_envio_jnci').val(),
            'Observaciones': $('#observaciones_contro').val(),
            'bandera_controversia_guardar_actualizar': $('#guardar_datos_controversia').val(),
        }
        document.querySelector("#guardar_datos_controversia").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarControversia',
            data: datos_controversia,            
            success:function(response){
                if (response.parametro == 'agregar_controversia') {
                    $('.alerta_controversia').removeClass('d-none');
                    $('.alerta_controversia').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_controversia').addClass('d-none');
                        $('.alerta_controversia').empty();
                        localStorage.setItem("#Generar_controversia", true);
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 

    // Abrir modal una vez se guarde item de controversia
    if (localStorage.getItem("#Generar_controversia")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#Generar_controversia");
        document.querySelector("#clicGuardado").click();
    }

    //Guardar datos pagos honorarios
    $('#form_guardarPagosjuntas').submit(function (e) {
        e.preventDefault();  
        let token = $('input[name=_token]').val();  
        var datos_pagosjuntas = {
            '_token': token,
            'newId_evento': $('#newId_evento').val(),
            'newId_asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'tipo_pago': $('#tipo_pago').val(),
            'f_solicitud_pago': $('#f_solicitud_pago').val(),
            'pago_junta': $('#pago_junta').val(),
            'n_orden_pago': $('#n_orden_pago').val(),
            'valor_pagado': $('#valor_pagado').val(),
            'f_pago_honorarios': $('#f_pago_honorarios').val(),
            'f_pago_radicacion': $('#f_pago_radicacion').val(),
            'bandera_pagos_guardar': $('#guardar_datos_pagos').val(),
        }
        document.querySelector("#guardar_datos_pagos").disabled = true;
        $.ajax({
            type:'POST',
            url:'/registrarPagoJuntas',
            data: datos_pagosjuntas,            
            success:function(response){
                if (response.parametro == 'agregar_pagosjuntas') {
                    $('.alerta_pagos').removeClass('d-none');
                    $('.alerta_pagos').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_pagos').addClass('d-none');
                        $('.alerta_pagos').empty();
                        localStorage.setItem("#Generar_pagojuntas", true);
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 

    // Abrir modal una vez se guarde item el pago honorarios
    if (localStorage.getItem("#Generar_pagojuntas")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#Generar_pagojuntas");
        document.querySelector("#clicGuardado").click();
    }

    // Listar Historial de pagos
    $('#listado_pagos_honorarios thead tr').clone(true).addClass('filters').appendTo('#listado_usuarios thead');
    $('#listado_pagos_honorarios').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            var api = this.api();
                // For each column
            api.columns().eq(0).each(function (colIdx) {
                // Set the header cell to contain the input element
                var cell = $('.filters th').eq(
                    $(api.column(colIdx).header()).index()
                );
                
                var title = $(cell).text();
                
                if (title !== 'Acciones') {
                    
                    $(cell).html('<input type="text" />');
                    $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                    .on('change', function (e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({search})'; //$(this).parents('th').find('select').val();
                        // Search the column for that value
                        api
                            .column(colIdx)
                            .search(
                                this.value != ''
                                    ? regexr.replace('{search}', '(((' + this.value + ')))')
                                    : '',
                                this.value != '',
                                this.value == ''
                            )
                            .draw();
                    })
                    .on('keyup', function (e) {
                        e.stopPropagation();
                        var cursorPosition = this.selectionStart;
                        $(this).trigger('change');
                        $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
                    });
                }

            });
        },
        /* dom: 'Bfrtip', */
        /* buttons:{
            dom:{
                buttons:{
                    className: 'btn'
                }
            },
            buttons:[
                {
                    extend:"excel",
                    title: 'Lista pagos honorarios',
                    text:'Exportar datos',
                    className: 'btn btn-info',
                    "excelStyles": [                      // Add an excelStyles definition
                                                
                    ],
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8]
                    }
                }
            ]
        }, */
        "language":{
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_ resgistros por página",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente",
                "first": "Primero",
                "last": "Último"
            },
            "emptyTable": "No se encontró información",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        }
    });

    /*  FUNCIONALIDAD: HABILITAR O DESHABILITAR: BOTÓN AGREGAR FILA, BOTÓN GUARDAR, BOTÓN CARGUE DOCUMENTOS
    CUANDO SE HACE CHECK EN LA OPCIÓN NO APORTA DOCUMENTOS */
    $("#No_aporta_documentos").click(function () {
        if ($(this).is(':checked')) {
               $("#btn_agregar_fila").css('display', 'none');
               $("#cargue_docs_modal_listado_docs").prop('disabled', true);
               $("#cargue_docs_modal_listado_docs").hover(function(){
                   $(this).css('cursor', 'not-allowed');
               });

        } else {
               $("#btn_agregar_fila").css('display', 'block');
               $("#cargue_docs_modal_listado_docs").prop('disabled', false);
               $("#cargue_docs_modal_listado_docs").hover(function(){
                   $(this).css('cursor', 'pointer');
               });
        }
    });
 
    //Guardar Registro solicitar Documentos
    $("#guardar_datos_tabla").click(function(){
        document.querySelector("#guardar_datos_tabla").disabled = true;
        // Validación: Se checkea la opción no aporta documentos y se intenta enviar pero ya con registros existentes en la tabla
        if ($("#No_aporta_documentos").is(':checked') == true && $("#conteo_listado_documentos_solicitados").val() > 0) {
            $("#No_aporta_documentos").prop("checked", false);
            $('#resultado_insercion').removeClass('d-none');
            $('#resultado_insercion').addClass('alert-danger');
            $('#resultado_insercion').append('<strong>No puede seleccionar la opción No aporta documentos debido a que existe información guardada en el sistema.</strong>');
            document.querySelector("#guardar_datos_tabla").disabled = false;
            setTimeout(() => {
                $('#resultado_insercion').addClass('d-none');
                $('#resultado_insercion').removeClass('alert-danger');
                $('#resultado_insercion').empty();
            }, 4000);
        }else{
            
            let token = $("input[name='_token']").val();
            var guardar_datos = [];
            var datos_finales_documentos_solicitados = [];
            var coincidencia_1 = "lista_docs_fila_";
            var coincidencia_2 = "lista_solicitante_fila_";
    
            var array_id_filas = [];
            // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
            $('#listado_docs_solicitados tbody tr').each(function (index) {
                array_id_filas.push($(this).attr('id'));
                if ($(this).attr('id') !== "datos_visuales") {
                    $(this).children("td").each(function (index2) {
                        var nombres_ids = $(this).find('*').attr("id");
                        if (nombres_ids != undefined) {
                            guardar_datos.push($('#'+nombres_ids).val());
                            if (nombres_ids.startsWith(coincidencia_1)) {
        
                                if ($('#'+nombres_ids).val() == 37) {
                                    guardar_datos.push($(this).find("input[id^='nombre_otro_doc_']").val());
                                }else{
                                    guardar_datos.push($('#'+nombres_ids).find('option:selected').text());
                                }
                            }
                            if (nombres_ids.startsWith(coincidencia_2)) {
                                if ($('#'+nombres_ids).val() == 8) {
                                    guardar_datos.push($(this).find("input[id^='nombre_otro_solicitante_']").val());
                                }else{
                                    guardar_datos.push($('#'+nombres_ids).find('option:selected').text());
                                }
                            }
                        }
                        if((index2+1) % 5 === 0){
                            datos_finales_documentos_solicitados.push(guardar_datos);
                            guardar_datos = [];
                        }
                    });
                }
            });
            //console.log(datos_finales_documentos_solicitados)
            // ENVÍO POR AJAX LA INFORMACIÓN FINAL DE LA TABLA, JUNTO CON EL ID EVENTO, ID ASIGNACION, ID PROCESO
            if (datos_finales_documentos_solicitados.length > 0) {
                // Validacion: Se desmarca la opción no aporta documentos y se inserta registros.
                if ($('#validacion_aporta_doc').data("id_tupla_no_aporta") != undefined) {
                    var tupla_no_aporta = $('#validacion_aporta_doc').data("id_tupla_no_aporta");
                }else{
                    var tupla_no_aporta = 0;
                }
                let envio_datos = {
                    '_token': token,
                    'datos_finales_documentos_solicitados' : datos_finales_documentos_solicitados,
                    'Id_evento': $('#newId_evento').val(),
                    'Id_Asignacion': $('#newId_asignacion').val(),
                    'Id_proceso': $('#Id_proceso').val(),
                    'tupla_no_aporta': tupla_no_aporta,
                    'parametro': "datos_bitacora"
                };
                
                $.ajax({
                    type:'POST',
                    url:'/GuardarDocumentosSolicitadosJuntas',
                    data: envio_datos,
                    beforeSend:  function() {
                        $("#guardar_datos_tabla").addClass("descarga-deshabilitada");
                    },
                    success:function(response){
                        // console.log(response);
                        if (response.parametro == "inserto_informacion") {
                            $('#resultado_insercion').removeClass('d-none');
                            $('#resultado_insercion').addClass('alert-success');
                            $('#resultado_insercion').append('<strong>'+response.mensaje+'</strong>');
                            setTimeout(() => {
                                $('#resultado_insercion').addClass('d-none');
                                $('#resultado_insercion').removeClass('alert-success');
                                $('#resultado_insercion').empty();
                            }, 3000);
                        }
                    },
                    complete:function(){
                        localStorage.setItem("#guardar_datos_tabla", true);
                        location.reload();
                        // $("#guardar_datos_tabla").removeClass("descarga-deshabilitada");
                    }
                });
        
                localStorage.setItem("#guardar_datos_documen", true);
        
                setTimeout(() => {
                    location.reload();
                }, 3000);
                
            }else{
    
                // Validación: No se inserta datos y selecciona el checkbox de No aporta documentos
                if ($("#No_aporta_documentos").is(':checked')) {
                    let envio_datos = {
                        '_token': token,
                        'Id_evento': $('#newId_evento').val(),
                        'Id_Asignacion': $('#newId_asignacion').val(),
                        'Id_proceso': $('#Id_proceso').val(),
                        'parametro': "no_aporta"
                    };
            
                    $.ajax({
                        type:'POST',
                        url:'/GuardarDocumentosSolicitados',
                        data: envio_datos,
                        beforeSend:  function() {
                            $("#guardar_datos_tabla").addClass("descarga-deshabilitada");
                        },
                        success:function(response){
                            if (response.parametro == "inserto_informacion") {
                                $('#resultado_insercion').removeClass('d-none');
                                $('#resultado_insercion').addClass('alert-success');
                                $('#resultado_insercion').append('<strong>'+response.mensaje+'</strong>');
                                setTimeout(() => {
                                    $('#resultado_insercion').addClass('d-none');
                                    $('#resultado_insercion').removeClass('alert-success');
                                    $('#resultado_insercion').empty();
                                }, 3000);
                            }else{
                                $('#resultado_insercion').removeClass('d-none');
                                $('#resultado_insercion').addClass('alert-danger');
                                $('#resultado_insercion').append('<strong>'+response.mensaje+'</strong>');
                                setTimeout(() => {
                                    $('#resultado_insercion').addClass('d-none');
                                    $('#resultado_insercion').removeClass('alert-danger');
                                    $('#resultado_insercion').empty();
                                }, 3000);
                            }
                        },
                        complete:function(){
                            localStorage.setItem("#guardar_datos_tabla", true);
                            location.reload();
                            // $("#guardar_datos_tabla").removeClass("descarga-deshabilitada");
                        }
                    });
    
                    localStorage.setItem("#guardar_datos_documen", true);
        
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
    
                }else{
                    document.querySelector("#guardar_datos_tabla").disabled = false;
                    $('#resultado_insercion').removeClass('d-none');
                    $('#resultado_insercion').addClass('alert-danger');
                    $('#resultado_insercion').append('<strong>No se encontró información para guardar en el sistema.</strong>');
                    setTimeout(() => {
                        $('#resultado_insercion').addClass('d-none');
                        $('#resultado_insercion').removeClass('alert-danger');
                        $('#resultado_insercion').empty();
                    }, 3000);
                }
            }
        }
    });

    // Abrir modal de agregar seguimiento despues de guardar 
    if (localStorage.getItem("#guardar_datos_documen")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#guardar_datos_documen");
        document.querySelector("#clicGuardado").click();
    }

    //Elimanar Documenos Solicitados
    $(document).on('click', "a[id^='btn_remover_fila_visual_']", function(){

        var id_seleccion = $(this).attr("id");

        let token = $("input[name='_token']").val();
        let datos_fila_quitar = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento': $('#newId_evento').val()
        };
        
        $.ajax({
            type:'POST',
            url:'/EliminarFila',
            data: datos_fila_quitar,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_eliminada") {
                    $('#resultado_insercion').empty();
                    $('#resultado_insercion').removeClass('d-none');
                    $('#resultado_insercion').addClass('alert-success');
                    $('#resultado_insercion').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion').addClass('d-none');
                        $('#resultado_insercion').removeClass('alert-success');
                        $('#resultado_insercion').empty();
                    }, 3000);
                }
                if (response.total_registros == 0) {
                    $("#conteo_listado_documentos_solicitados").val(response.total_registros);
                }

                // localStorage.setItem("#"+id_seleccion, true);

                // setTimeout(() => {
                //     location.reload();
                // }, 3000);

                // // Abrir modal de agregar seguimiento despues de guardar 
                // if (localStorage.getItem("#"+id_seleccion)) {
                //     // Simular el clic en la etiqueta a después de recargar la página
                //     localStorage.removeItem("#"+id_seleccion);
                //     document.querySelector("#clicGuardado").click();
                // }

            }
        });

    });

    // Actualizar fecha de recepcion de documentos solicitados
    $('#actualizar_datos_tabla').click(function (e) {
        e.preventDefault();            
        var valoresInputsFecha = {};
        $('input[id^="fecha_recepcion_"]').each(function() {
            var id = $(this).attr('id').split('_').pop();
            var valor = $(this).val();
            if (valor !== "" && typeof valor !== "undefined") {
                valoresInputsFecha[id] = valor;
            }
        });
        // console.log(valoresInputsFecha);
        // Convertir el objeto en un array de objetos
        var Fechas_recepcion = Object.keys(valoresInputsFecha).map(function(key) {
            return { id: key, fecha: valoresInputsFecha[key] };
        });
        // console.log(Fechas_recepcion);
        let token = $("input[name='_token']").val();
        let datos_fila_editar= {
            '_token': token,
            'Fechas_recepcion' : Fechas_recepcion,
            'Id_evento': $('#newId_evento').val()
        };    

        $.ajax({
            type:'POST',
            url:'/EditarFechas_Recepcion_Doc_soli_jun',
            data: datos_fila_editar,
            success:function(response){
                // console.log(response);
                if (response.parametro == "filas_editadas") {
                    $('#resultado_insercion').empty();
                    $('#resultado_insercion').removeClass('d-none');
                    $('#resultado_insercion').addClass('alert-success');
                    $('#resultado_insercion').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion').addClass('d-none');
                        $('#resultado_insercion').removeClass('alert-success');
                        $('#resultado_insercion').empty();
                    }, 3000);
                    localStorage.setItem("#guardar_datos_tabla", true);
                    
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                }
                // else{
                //     $('#resultado_insercion').empty();
                //     $('#resultado_insercion').removeClass('d-none');
                //     $('#resultado_insercion').addClass('alert-danger');
                //     $('#resultado_insercion').append('<strong>'+response.mensaje+'</strong>');
                    
                //     setTimeout(() => {
                //         $('#resultado_insercion').addClass('d-none');
                //         $('#resultado_insercion').removeClass('alert-danger');
                //         $('#resultado_insercion').empty();
                //     }, 3000);
                // }             
            }
        });

    });

    // Abrir modal de agregar seguimiento despues de guardar 
    if (localStorage.getItem("#guardar_datos_tabla")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#guardar_datos_tabla");
        document.querySelector("#clicGuardado").click();
    }

    var jrci_seleccionado = $("#jrci_califi_invalidez").val();
    $("#id_jrci_del_input").val(jrci_seleccionado);
    
    /* Funcionalidad checkbox JRCI (Copia Partes Interesadas) */
    $("#copia_jrci").click(function(){
        // Si fue seleccionado realiza lo siguente
        if ($(this).prop('checked')) {
            // 1. Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
            var jrci_seleccionado = $("#jrci_califi_invalidez").val();
            // Si existe, debe mostrar un input con el nombre de la jrci seleccioanda y cargar los datos del destinatario con la
            // info del jrci.
            if (jrci_seleccionado > 0) {
                $("#div_input_jrci_copia").removeClass('d-none');
                $("#div_select_jrci_copia").addClass('d-none');
                $("#input_jrci_seleccionado_copia").val($("#jrci_califi_invalidez option:selected").text());
            }
            // si no, el mismo selector de jrci
            else{
                $("#div_input_jrci_copia").removeClass('d-none');
                $("#input_jrci_seleccionado_copia").val($("#jrci_califi_invalidez_comunicado option:selected").text());
                $("#div_select_jrci_copia").addClass('d-none');

                $.ajax({
                    type:'POST',
                    url:'/selectoresJuntas',
                    data: datos_lista_juntas_invalidez,
                    success:function(data) {
                        // let IdJuntaInvalidez = $('select[name=jrci_califi_invalidez]').val();
                        $('#jrci_califi_invalidez_copia').append('<option>Seleccione una opción</option>');
                        let juntajrci = Object.keys(data);
                        for (let i = 0; i < juntajrci.length; i++) {
                            // if (data[juntajrci[i]]['Id_Parametro'] != IdJuntaInvalidez) {  
                            // }
                            $('#jrci_califi_invalidez_copia').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                        }
                    }
                });
            }
        }
        else{
            // 1. Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
            var jrci_seleccionado = $("#jrci_califi_invalidez").val();
            // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
            if (jrci_seleccionado > 0) {
                $("#div_input_jrci_copia").addClass('d-none');
                $("#input_jrci_seleccionado_copia").val('');
            }
            // Si no, eliminar el las opciones del selector y deja todo limpio.
            else{
                $("#div_input_jrci_copia").addClass('d-none');
                $("#input_jrci_seleccionado_copia").val('');

                $("#div_select_jrci_copia").addClass('d-none');
                $('#jrci_califi_invalidez_copia').empty();  
            }
        }
    });
    
    // Captura de datos segun la opcion seleccionada en destinatario principal
    // En la modal de generar comunicado
    $('input[type="radio"]').change(function(){

        $('#Pdf').prop('disabled', true);
        var destinarioPrincipal = $(this).val();
        var identificacion_comunicado_afiliado = $('#identificacion_comunicado').val();
        $("#input_jrci_seleccionado_copia").val('');
        var newId_evento = $('#newId_evento').val();
        var newId_asignacion = $('#newId_asignacion').val();
        var Id_proceso = $('#Id_proceso').val();
        var datos_destinarioPrincipal ={
            '_token':token,
            'destinatarioPrincipal': destinarioPrincipal,
            'identificacion_comunicado_afiliado':identificacion_comunicado_afiliado,
            'newId_evento': newId_evento,
            'newId_asignacion': newId_asignacion,
            'Id_proceso': Id_proceso,
            'id_jrci': jrci_seleccionado
        }
        $.ajax({
            type:'POST',
            url:'/captuarDestinatarioJuntas',
            data: datos_destinarioPrincipal,
            success: function(data){
                if(data.destinatarioPrincipal == 'JRCI_comunicado'){
                    var jrci_seleccionado= $("#jrci_califi_invalidez").val();
                    // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                    // Si existe, debe mostrar un input con el nombre de la jrci seleccioanda y cargar los datos del destinatario con la
                    // info del jrci.
                    if (jrci_seleccionado > 0) {
                        $("#div_input_jrci").removeClass('d-none');
                        $("#div_select_jrci").addClass('d-none');
                        $("#input_jrci_seleccionado").val($("#jrci_califi_invalidez option:selected").text());

                        var Nombre_afiliado = $('#nombre_destinatario');
                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                        document.querySelector("#nombre_destinatario").disabled = true;
                        var nitccafiliado = $('#nic_cc');
                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                        document.querySelector("#nic_cc").disabled = true;
                        var direccionafiliado = $('#direccion_destinatario');
                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                        document.querySelector("#direccion_destinatario").disabled = true;
                        var telefonoafiliado = $('#telefono_destinatario');
                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                        document.querySelector("#telefono_destinatario").disabled = true;
                        var emailafiliado = $('#email_destinatario');
                        emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                        document.querySelector("#email_destinatario").disabled = true;
                        var departamentoafiliado = $('#departamento_destinatario');
                        departamentoafiliado.empty();
                        departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                        document.querySelector("#departamento_destinatario").disabled = true;
                        var ciudadafiliado =$('#ciudad_destinatario');
                        ciudadafiliado.empty();
                        ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                        document.querySelector("#ciudad_destinatario").disabled = true;

                        // Seleccción de la forma de envío acorde a la selección de la JRCI
                        if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                            $('#forma_envio').val('46').trigger('change.select2');
                        }else{
                            $('#forma_envio').val('47').trigger('change.select2');
                        }

                        var nombre_usuario = $('#elaboro');
                        nombre_usuario.val(data.nombreusuario);
                        var nombre_usuario2 = $('#elaboro2');
                        nombre_usuario2.val(data.nombreusuario);
                        var reviso = $('#reviso');
                        reviso.empty();
                        reviso.append('<option value="" selected>Seleccione una opción</option>');
                        let revisolider = Object.keys(data.array_datos_lider);
                        for (let i = 0; i < revisolider.length; i++) {
                            reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        }
                        $("#reviso").prop("selectedIndex", 1);
                    } 
                    // si no, el mismo selector de jrci
                    else {

                        $("#div_input_jrci").addClass('d-none');
                        $("#div_select_jrci").removeClass('d-none');

                        $.ajax({
                            type:'POST',
                            url:'/selectoresJuntas',
                            data: datos_lista_juntas_invalidez,
                            success:function(data) {
                                // let IdJuntaInvalidez = $('select[name=jrci_califi_invalidez]').val();
                                $('#jrci_califi_invalidez_comunicado').empty();
                                $('#jrci_califi_invalidez_comunicado').append('<option>Seleccione una opción</option>');
                                let juntajrci = Object.keys(data);
                                for (let i = 0; i < juntajrci.length; i++) {
                                    // if (data[juntajrci[i]]['Id_Parametro'] != IdJuntaInvalidez) {  
                                    // }
                                    $('#jrci_califi_invalidez_comunicado').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                                }

                                // Se autoselecciona la jrci
                                $('#jrci_califi_invalidez_comunicado').prop("selectedIndex", 1);
                                var seleccion_automatica_jrci = $('#jrci_califi_invalidez_comunicado').val();

                                var identificacion_comunicado_afiliado = $('#identificacion_comunicado').val();
                                var newId_evento = $('#newId_evento').val();
                                var newId_asignacion = $('#newId_asignacion').val();
                                var Id_proceso = $('#Id_proceso').val();
                                var datos_destinarioPrincipal ={
                                    '_token':token,
                                    'destinatarioPrincipal': "JRCI_comunicado",
                                    'identificacion_comunicado_afiliado':identificacion_comunicado_afiliado,
                                    'newId_evento': newId_evento,
                                    'newId_asignacion': newId_asignacion,
                                    'Id_proceso': Id_proceso,
                                    'id_jrci': seleccion_automatica_jrci
                                };
                                $.ajax({
                                    type:'POST',
                                    url:'/captuarDestinatarioJuntas',
                                    data: datos_destinarioPrincipal,
                                    success: function(data){
                                        var Nombre_afiliado = $('#nombre_destinatario');
                                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                                        document.querySelector("#nombre_destinatario").disabled = true;
                                        var nitccafiliado = $('#nic_cc');
                                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                                        document.querySelector("#nic_cc").disabled = true;
                                        var direccionafiliado = $('#direccion_destinatario');
                                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                                        document.querySelector("#direccion_destinatario").disabled = true;
                                        var telefonoafiliado = $('#telefono_destinatario');
                                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                                        document.querySelector("#telefono_destinatario").disabled = true;
                                        var emailafiliado = $('#email_destinatario');
                                        emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                                        document.querySelector("#email_destinatario").disabled = true;
                                        var departamentoafiliado = $('#departamento_destinatario');
                                        departamentoafiliado.empty();
                                        departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                                        document.querySelector("#departamento_destinatario").disabled = true;
                                        var ciudadafiliado =$('#ciudad_destinatario');
                                        ciudadafiliado.empty();
                                        ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                                        document.querySelector("#ciudad_destinatario").disabled = true;

                                        // Seleccción de la forma de envío acorde a la selección de la JRCI
                                        if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                            $('#forma_envio').val('46').trigger('change.select2');
                                        }else{
                                            $('#forma_envio').val('47').trigger('change.select2');
                                        }

                                        var nombre_usuario = $('#elaboro');
                                        nombre_usuario.val(data.nombreusuario);
                                        var nombre_usuario2 = $('#elaboro2');
                                        nombre_usuario2.val(data.nombreusuario);
                                        var reviso = $('#reviso');
                                        reviso.empty();
                                        reviso.append('<option value="" selected>Seleccione una opción</option>');
                                        let revisolider = Object.keys(data.array_datos_lider);
                                        for (let i = 0; i < revisolider.length; i++) {
                                            reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                                        }
                                        $("#reviso").prop("selectedIndex", 1);
                                        $("#input_jrci_seleccionado_copia").val($("#jrci_califi_invalidez_comunicado option:selected").text());
                                    }
                                });
                            }
                        });

                        $('#jrci_califi_invalidez_comunicado').change(function(){
                            var identificacion_comunicado_afiliado = $('#identificacion_comunicado').val();
                            var newId_evento = $('#newId_evento').val();
                            var newId_asignacion = $('#newId_asignacion').val();
                            var Id_proceso = $('#Id_proceso').val();
                            var datos_destinarioPrincipal ={
                                '_token':token,
                                'destinatarioPrincipal': "JRCI_comunicado",
                                'identificacion_comunicado_afiliado':identificacion_comunicado_afiliado,
                                'newId_evento': newId_evento,
                                'newId_asignacion': newId_asignacion,
                                'Id_proceso': Id_proceso,
                                'id_jrci': $(this).val()
                            };
                            $.ajax({
                                type:'POST',
                                url:'/captuarDestinatarioJuntas',
                                data: datos_destinarioPrincipal,
                                success: function(data){
                                    var Nombre_afiliado = $('#nombre_destinatario');
                                    Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                                    document.querySelector("#nombre_destinatario").disabled = true;
                                    var nitccafiliado = $('#nic_cc');
                                    nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                                    document.querySelector("#nic_cc").disabled = true;
                                    var direccionafiliado = $('#direccion_destinatario');
                                    direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                                    document.querySelector("#direccion_destinatario").disabled = true;
                                    var telefonoafiliado = $('#telefono_destinatario');
                                    telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                                    document.querySelector("#telefono_destinatario").disabled = true;
                                    var emailafiliado = $('#email_destinatario');
                                    emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                                    document.querySelector("#email_destinatario").disabled = true;
                                    var departamentoafiliado = $('#departamento_destinatario');
                                    departamentoafiliado.empty();
                                    departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                                    document.querySelector("#departamento_destinatario").disabled = true;
                                    var ciudadafiliado =$('#ciudad_destinatario');
                                    ciudadafiliado.empty();
                                    ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                                    document.querySelector("#ciudad_destinatario").disabled = true;

                                    // Seleccción de la forma de envío acorde a la selección de la JRCI
                                    if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                        $('#forma_envio').val('46').trigger('change.select2');
                                    }else{
                                        $('#forma_envio').val('47').trigger('change.select2');
                                    }

                                    var nombre_usuario = $('#elaboro');
                                    nombre_usuario.val(data.nombreusuario);
                                    var nombre_usuario2 = $('#elaboro2');
                                    nombre_usuario2.val(data.nombreusuario);
                                    var reviso = $('#reviso');
                                    reviso.empty();
                                    reviso.append('<option value="" selected>Seleccione una opción</option>');
                                    let revisolider = Object.keys(data.array_datos_lider);
                                    for (let i = 0; i < revisolider.length; i++) {
                                        reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                                    }
                                    $("#reviso").prop("selectedIndex", 1);
                                }
                            });
                            $("#input_jrci_seleccionado_copia").val($("#jrci_califi_invalidez_comunicado option:selected").text());
                        });
                    }
                }else if(data.destinatarioPrincipal == 'JNCI_comunicado'){
                    var Nombre_afiliado = $('#nombre_destinatario');
                    Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                    document.querySelector("#nombre_destinatario").disabled = true;
                    var nitccafiliado = $('#nic_cc');
                    nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                    document.querySelector("#nic_cc").disabled = true;
                    var direccionafiliado = $('#direccion_destinatario');
                    direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                    document.querySelector("#direccion_destinatario").disabled = true;
                    var telefonoafiliado = $('#telefono_destinatario');
                    telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                    document.querySelector("#telefono_destinatario").disabled = true;
                    var emailafiliado = $('#email_destinatario');
                    emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                    document.querySelector("#email_destinatario").disabled = true;
                    var departamentoafiliado = $('#departamento_destinatario');
                    departamentoafiliado.empty();
                    departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                    document.querySelector("#departamento_destinatario").disabled = true;
                    var ciudadafiliado =$('#ciudad_destinatario');
                    ciudadafiliado.empty();
                    ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                    document.querySelector("#ciudad_destinatario").disabled = true;

                    // Seleccción de la forma de envío acorde a la selección de la JNCI
                    if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                        $('#forma_envio').val('46').trigger('change.select2');
                    }else{
                        $('#forma_envio').val('47').trigger('change.select2');
                    }

                    var nombre_usuario = $('#elaboro');
                    nombre_usuario.val(data.nombreusuario);
                    var nombre_usuario2 = $('#elaboro2');
                    nombre_usuario2.val(data.nombreusuario);
                    var reviso = $('#reviso');
                    reviso.empty();
                    reviso.append('<option value="" selected>Seleccione una opción</option>');
                    let revisolider = Object.keys(data.array_datos_lider);
                    for (let i = 0; i < revisolider.length; i++) {
                        reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                    }
                    $("#reviso").prop("selectedIndex", 1);
                    // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                    var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                    // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                    if (jrci_seleccionado > 0) {
                        $("#div_input_jrci").addClass('d-none');
                        $("#input_jrci_seleccionado").val('');
                    } 
                    // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                    else {
                        $("#div_select_jrci").addClass('d-none');
                        $('#jrci_califi_invalidez_comunicado').empty();  
                    }
                }
                if (data.destinatarioPrincipal == 'Afiliado') {
                    //console.log(data.array_datos_destinatarios);
                    var Nombre_afiliado = $('#nombre_destinatario');
                    Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_afiliado);                    
                    document.querySelector("#nombre_destinatario").disabled = true;
                    var nitccafiliado = $('#nic_cc');
                    nitccafiliado.val(data.array_datos_destinatarios[0].Nro_identificacion);
                    document.querySelector("#nic_cc").disabled = true;
                    var direccionafiliado = $('#direccion_destinatario');
                    direccionafiliado.val(data.array_datos_destinatarios[0].Direccion_afiliado);
                    document.querySelector("#direccion_destinatario").disabled = true;
                    var telefonoafiliado = $('#telefono_destinatario');
                    telefonoafiliado.val(data.array_datos_destinatarios[0].Telefono_contacto);
                    document.querySelector("#telefono_destinatario").disabled = true;
                    var emailafiliado = $('#email_destinatario');
                    emailafiliado.val(data.array_datos_destinatarios[0].Email_afiliado);
                    document.querySelector("#email_destinatario").disabled = true;
                    var departamentoafiliado = $('#departamento_destinatario');
                    departamentoafiliado.empty();
                    departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento_afiliado+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento_afiliado+'</option>');
                    document.querySelector("#departamento_destinatario").disabled = true;
                    var ciudadafiliado =$('#ciudad_destinatario');
                    ciudadafiliado.empty();
                    ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipio_afiliado+'">'+data.array_datos_destinatarios[0].Nombre_municipio_afiliado+'</option>')
                    document.querySelector("#ciudad_destinatario").disabled = true;

                    // Seleccción de la forma de envío acorde a la selección del afiliado
                    if (data.info_medio_noti[0].Medio_notificacion == "Físico") {
                        $('#forma_envio').val('46').trigger('change.select2');
                    }else{
                        $('#forma_envio').val('47').trigger('change.select2');
                    }

                    var nombre_usuario = $('#elaboro');
                    nombre_usuario.val(data.nombreusuario);
                    var nombre_usuario2 = $('#elaboro2');
                    nombre_usuario2.val(data.nombreusuario);
                    var reviso = $('#reviso');
                    reviso.empty();
                    reviso.append('<option value="" selected>Seleccione una opción</option>');
                    let revisolider = Object.keys(data.array_datos_lider);
                    for (let i = 0; i < revisolider.length; i++) {
                        reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                    }
                    $("#reviso").prop("selectedIndex", 1);
                    // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                    var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                    // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                    if (jrci_seleccionado > 0) {
                        $("#div_input_jrci").addClass('d-none');
                        $("#input_jrci_seleccionado").val('');
                    } 
                    // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                    else {
                        $("#div_select_jrci").addClass('d-none');
                        $('#jrci_califi_invalidez_comunicado').empty();  
                    }
                }else if(data.destinatarioPrincipal == 'Empleador'){
                    //console.log(data.array_datos_destinatarios);
                    var Nombre_afiliado = $('#nombre_destinatario');
                    Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_empresa);
                    document.querySelector("#nombre_destinatario").disabled = true;
                    var nitccafiliado = $('#nic_cc');
                    nitccafiliado.val(data.array_datos_destinatarios[0].Nit_o_cc);
                    document.querySelector("#nic_cc").disabled = true;
                    var direccionafiliado = $('#direccion_destinatario');
                    direccionafiliado.val(data.array_datos_destinatarios[0].Direccion_empresa);
                    document.querySelector("#direccion_destinatario").disabled = true;
                    var telefonoafiliado = $('#telefono_destinatario');
                    telefonoafiliado.val(data.array_datos_destinatarios[0].Telefono_empresa);
                    document.querySelector("#telefono_destinatario").disabled = true;
                    var emailafiliado = $('#email_destinatario');
                    emailafiliado.val(data.array_datos_destinatarios[0].Email_empresa);
                    document.querySelector("#email_destinatario").disabled = true;
                    var departamentoafiliado = $('#departamento_destinatario');
                    departamentoafiliado.empty();
                    departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento_empresa+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento_empresa+'</option>');
                    document.querySelector("#departamento_destinatario").disabled = true;
                    var ciudadafiliado =$('#ciudad_destinatario');
                    ciudadafiliado.empty();
                    ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipio_empresa+'">'+data.array_datos_destinatarios[0].Nombre_municipio_empresa+'</option>')
                    document.querySelector("#ciudad_destinatario").disabled = true;

                    // Seleccción de la forma de envío acorde a la selección del empleador
                    if (data.info_medio_noti[0].Medio_notificacion == "Físico") {
                        $('#forma_envio').val('46').trigger('change.select2');
                    }else{
                        $('#forma_envio').val('47').trigger('change.select2');
                    }

                    var nombre_usuario = $('#elaboro');
                    nombre_usuario.val(data.nombreusuario);
                    var nombre_usuario2 = $('#elaboro2');
                    nombre_usuario2.val(data.nombreusuario);
                    var reviso = $('#reviso');
                    reviso.empty();
                    reviso.append('<option value="" selected>Seleccione una opción</option>');
                    let revisolider = Object.keys(data.array_datos_lider);
                    for (let i = 0; i < revisolider.length; i++) {
                        reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                    }
                    $("#reviso").prop("selectedIndex", 1);
                    // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                    var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                    // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                    if (jrci_seleccionado > 0) {
                        $("#div_input_jrci").addClass('d-none');
                        $("#input_jrci_seleccionado").val('');
                    } 
                    // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                    else {
                        $("#div_select_jrci").addClass('d-none');
                        $('#jrci_califi_invalidez_comunicado').empty();  
                    }
                }
                else if(data.destinatarioPrincipal == 'EPS_comunicado'){
                    //console.log(data.array_datos_destinatarios);
                    var Nombre_afiliado = $('#nombre_destinatario');
                    Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                    document.querySelector("#nombre_destinatario").disabled = true;
                    var nitccafiliado = $('#nic_cc');
                    nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                    document.querySelector("#nic_cc").disabled = true;
                    var direccionafiliado = $('#direccion_destinatario');
                    direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                    document.querySelector("#direccion_destinatario").disabled = true;
                    var telefonoafiliado = $('#telefono_destinatario');
                    telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                    document.querySelector("#telefono_destinatario").disabled = true;
                    var emailafiliado = $('#email_destinatario');
                    emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                    document.querySelector("#email_destinatario").disabled = true;
                    var departamentoafiliado = $('#departamento_destinatario');
                    departamentoafiliado.empty();
                    departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                    document.querySelector("#departamento_destinatario").disabled = true;
                    var ciudadafiliado =$('#ciudad_destinatario');
                    ciudadafiliado.empty();
                    ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                    document.querySelector("#ciudad_destinatario").disabled = true;

                    // Seleccción de la forma de envío acorde a la selección de la EPS
                    if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                        $('#forma_envio').val('46').trigger('change.select2');
                    }else{
                        $('#forma_envio').val('47').trigger('change.select2');
                    }

                    var nombre_usuario = $('#elaboro');
                    nombre_usuario.val(data.nombreusuario);
                    var nombre_usuario2 = $('#elaboro2');
                    nombre_usuario2.val(data.nombreusuario);
                    var reviso = $('#reviso');
                    reviso.empty();
                    reviso.append('<option value="" selected>Seleccione una opción</option>');
                    let revisolider = Object.keys(data.array_datos_lider);
                    for (let i = 0; i < revisolider.length; i++) {
                        reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                    }
                    $("#reviso").prop("selectedIndex", 1);
                    // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                    var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                    // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                    if (jrci_seleccionado > 0) {
                        $("#div_input_jrci").addClass('d-none');
                        $("#input_jrci_seleccionado").val('');
                    } 
                    // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                    else {
                        $("#div_select_jrci").addClass('d-none');
                        $('#jrci_califi_invalidez_comunicado').empty();  
                    }
                }
                else if(data.destinatarioPrincipal == 'AFP_comunicado'){
                    //console.log(data.array_datos_destinatarios);
                    var Nombre_afiliado = $('#nombre_destinatario');
                    Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                    document.querySelector("#nombre_destinatario").disabled = true;
                    var nitccafiliado = $('#nic_cc');
                    nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                    document.querySelector("#nic_cc").disabled = true;
                    var direccionafiliado = $('#direccion_destinatario');
                    direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                    document.querySelector("#direccion_destinatario").disabled = true;
                    var telefonoafiliado = $('#telefono_destinatario');
                    telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                    document.querySelector("#telefono_destinatario").disabled = true;
                    var emailafiliado = $('#email_destinatario');
                    emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                    document.querySelector("#email_destinatario").disabled = true;
                    var departamentoafiliado = $('#departamento_destinatario');
                    departamentoafiliado.empty();
                    departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                    document.querySelector("#departamento_destinatario").disabled = true;
                    var ciudadafiliado =$('#ciudad_destinatario');
                    ciudadafiliado.empty();
                    ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                    document.querySelector("#ciudad_destinatario").disabled = true;

                    // Seleccción de la forma de envío acorde a la selección de la AFP
                    if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                        $('#forma_envio').val('46').trigger('change.select2');
                    }else{
                        $('#forma_envio').val('47').trigger('change.select2');
                    }

                    var nombre_usuario = $('#elaboro');
                    nombre_usuario.val(data.nombreusuario);
                    var nombre_usuario2 = $('#elaboro2');
                    nombre_usuario2.val(data.nombreusuario);
                    var reviso = $('#reviso');
                    reviso.empty();
                    reviso.append('<option value="" selected>Seleccione una opción</option>');
                    let revisolider = Object.keys(data.array_datos_lider);
                    for (let i = 0; i < revisolider.length; i++) {
                        reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                    }
                    $("#reviso").prop("selectedIndex", 1);
                    // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                    var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                    // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                    if (jrci_seleccionado > 0) {
                        $("#div_input_jrci").addClass('d-none');
                        $("#input_jrci_seleccionado").val('');
                    } 
                    // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                    else {
                        $("#div_select_jrci").addClass('d-none');
                        $('#jrci_califi_invalidez_comunicado').empty();  
                    }
                }
                else if(data.destinatarioPrincipal == 'ARL_comunicado'){
                    //console.log(data.array_datos_destinatarios);
                    var Nombre_afiliado = $('#nombre_destinatario');
                    Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                    document.querySelector("#nombre_destinatario").disabled = true;
                    var nitccafiliado = $('#nic_cc');
                    nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                    document.querySelector("#nic_cc").disabled = true;
                    var direccionafiliado = $('#direccion_destinatario');
                    direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                    document.querySelector("#direccion_destinatario").disabled = true;
                    var telefonoafiliado = $('#telefono_destinatario');
                    telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                    document.querySelector("#telefono_destinatario").disabled = true;
                    var emailafiliado = $('#email_destinatario');
                    emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                    document.querySelector("#email_destinatario").disabled = true;
                    var departamentoafiliado = $('#departamento_destinatario');
                    departamentoafiliado.empty();
                    departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                    document.querySelector("#departamento_destinatario").disabled = true;
                    var ciudadafiliado =$('#ciudad_destinatario');
                    ciudadafiliado.empty();
                    ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                    document.querySelector("#ciudad_destinatario").disabled = true;

                    // Seleccción de la forma de envío acorde a la selección de la EPS
                    if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                        $('#forma_envio').val('46').trigger('change.select2');
                    }else{
                        $('#forma_envio').val('47').trigger('change.select2');
                    }

                    var nombre_usuario = $('#elaboro');
                    nombre_usuario.val(data.nombreusuario);
                    var nombre_usuario2 = $('#elaboro2');
                    nombre_usuario2.val(data.nombreusuario);
                    var reviso = $('#reviso');
                    reviso.empty();
                    reviso.append('<option value="" selected>Seleccione una opción</option>');
                    let revisolider = Object.keys(data.array_datos_lider);
                    for (let i = 0; i < revisolider.length; i++) {
                        reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                    }
                    $("#reviso").prop("selectedIndex", 1);
                    // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                    var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                    // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                    if (jrci_seleccionado > 0) {
                        $("#div_input_jrci").addClass('d-none');
                        $("#input_jrci_seleccionado").val('');
                    } 
                    // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                    else {
                        $("#div_select_jrci").addClass('d-none');
                        $('#jrci_califi_invalidez_comunicado').empty();  
                    }
                }
                else if(data.destinatarioPrincipal == 'Otro'){
                    //console.log(data.destinatarioPrincipal);
                    document.querySelector("#nombre_destinatario").disabled = false;
                    $('#nombre_destinatario').val('');
                    document.querySelector("#nic_cc").disabled = false;
                    $('#nic_cc').val('');
                    document.querySelector("#direccion_destinatario").disabled = false;
                    $('#direccion_destinatario').val('');
                    document.querySelector("#telefono_destinatario").disabled = false;
                    $('#telefono_destinatario').val('');
                    document.querySelector("#email_destinatario").disabled = false;
                    $('#email_destinatario').val('');
                    document.querySelector("#departamento_destinatario").disabled = false;
                    document.querySelector("#ciudad_destinatario").disabled = false;
                    // Listado de departamento generar comunicado
                    let datos_lista_departamentos_generar_comunicado = {
                        '_token': token,
                        'parametro' : "departamentos_generar_comunicado"
                    };
                    $.ajax({
                        type:'POST',
                        url:'/selectoresModuloCalificacionPCL',
                        data: datos_lista_departamentos_generar_comunicado,
                        success:function(data) {
                            // console.log(data);
                            $('#departamento_destinatario').empty();
                            $('#ciudad_destinatario').empty();
                            $('#departamento_destinatario').append('<option value="" selected>Seleccione</option>');
                            let claves = Object.keys(data);
                            for (let i = 0; i < claves.length; i++) {
                                $('#departamento_destinatario').append('<option value="'+data[claves[i]]["Id_departamento"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
                            }
                        }
                    });
                    // listado municipios dependiendo del departamentos generar comunicado
                    $('#departamento_destinatario').change(function(){
                        $('#ciudad_destinatario').prop('disabled', false);
                        let id_departamento_destinatario = $('#departamento_destinatario').val();
                        let datos_lista_municipios_generar_comunicado = {
                            '_token': token,
                            'parametro' : "municipios_generar_comunicado",
                            'id_departamento_destinatario': id_departamento_destinatario
                        };
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data: datos_lista_municipios_generar_comunicado,
                            success:function(data) {
                                // console.log(data);
                                $('#ciudad_destinatario').empty();
                                $('#ciudad_destinatario').append('<option value="" selected>Seleccione</option>');
                                let claves = Object.keys(data);
                                for (let i = 0; i < claves.length; i++) {
                                    $('#ciudad_destinatario').append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                                }
                            }
                        });
                    });
                    var nombre_usuario = $('#elaboro');
                    nombre_usuario.val(data.nombreusuario);
                    var nombre_usuario2 = $('#elaboro2');
                    nombre_usuario2.val(data.nombreusuario);
                    var reviso = $('#reviso');
                    reviso.empty();
                    reviso.append('<option value="" selected>Seleccione una opción</option>');
                    let revisolider = Object.keys(data.array_datos_lider);
                    for (let i = 0; i < revisolider.length; i++) {
                        reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                    }
                    $("#reviso").prop("selectedIndex", 1);

                    // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                    var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                    // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                    if (jrci_seleccionado > 0) {
                        $("#div_input_jrci").addClass('d-none');
                        $("#input_jrci_seleccionado").val('');
                    } 
                    // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                    else {
                        $("#div_select_jrci").addClass('d-none');
                        $('#jrci_califi_invalidez_comunicado').empty();  
                    }
                }

            }        
        });
        
    });

    // Listado de forma de envio de generar comunicado

    let datos_lista_forma_envio = {
        '_token':token,        
        'parametro':"lista_forma_envio"
    }

    $.ajax({
        type:'POST',
        url:'/selectoresModuloCalificacionPCL',
        data:datos_lista_forma_envio,
        success:function(data){
            //console.log(data);
            let NobreFormaEnvio = $('select[name=forma_envio]').val();
            let formaenviogenerarcomunicado = Object.keys(data);
            for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                    $('#forma_envio').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                }                
            }
        }
    });

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
        // callbacks: {
        //     onInit: function() {
        //         // Ajusta la longitud máxima de la línea para la tokenización
        //         $('#cuerpo_comunicado').summernote('option', 'editor.maxTokenizationLineLength', 2000); // Puedes ajustar el valor según tus necesidades
        //     }
        // }
    });

    $("#cuerpo_comunicado_editar").summernote({
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

    
    /* Funcionalidad para insertar las etiquetas correspondientes de las proformas 
        Oficion Juntas afiliado, Oficio Juntas JRCI, 
        Expediente JRCI, Devol. Expediente JRCI, Solicitud Dictamen JRCI, Otro Documento
    */
    $("#btn_insertar_nombre_junta_regional_asunto").click(function(e){
        e.preventDefault();
        var cursorPos = $("#asunto").prop('selectionStart');
        var currentValue = $("#asunto").val();
        var newValue = currentValue.slice(0, cursorPos) + '{{$nombre_junta_asunto}}' + currentValue.slice(cursorPos);
        // Actualiza el valor del input
        $("#asunto").val(newValue);
        // Coloca el cursor después de la etiqueta
        $("#asunto").prop('selectionStart', cursorPos + 25);
        $("#asunto").prop('selectionEnd', cursorPos + 25);
        $("#asunto").focus();
    });

    $("#btn_insertar_nombre_junta_regional").click(function(e){
        e.preventDefault();
        var etiqueta_nombre_junta = "{{$nombre_junta}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_nombre_junta);
    });

    $("#btn_insertar_nro_orden_pago").click(function(e){
        e.preventDefault();
        var etiqueta_nro_orden_pago = "{{$nro_orden_pago}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_nro_orden_pago);
    });

    $("#btn_insertar_fecha_notifi_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_fecha_notificacion_afiliado = "{{$fecha_notificacion_afiliado}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_fecha_notificacion_afiliado);
    });

    $("#btn_insertar_fecha_radi_contro_pri_cali").click(function(e){
        e.preventDefault();
        var etiqueta_fecha_radicacion_controversia_primera_calificacion = "{{$fecha_radicacion_controversia_primera_calificacion}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_fecha_radicacion_controversia_primera_calificacion);
    });

    $("#btn_insertar_tipo_doc_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_tipo_documento_afiliado = "{{$tipo_documento_afiliado}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_tipo_documento_afiliado);
    });

    $("#btn_insertar_documento_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_documento_afiliado = "{{$documento_afiliado}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_documento_afiliado);
    });

    $("#btn_insertar_nombre_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_nombre_afiliado = "{{$nombre_afiliado}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_nombre_afiliado);
    });

    $("#btn_insertar_fecha_estructuracion").click(function(e){
        e.preventDefault();
        var etiqueta_fecha_estructuracion = "{{$fecha_estructuracion}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_fecha_estructuracion);
    });

    $("#btn_insertar_tipo_evento").click(function(e){
        e.preventDefault();
        var etiqueta_tipo_evento = "{{$tipo_evento}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_tipo_evento);
    });

    $("#btn_insertar_nombres_cie10").click(function(e){
        e.preventDefault();
        var etiqueta_nombres_cie10 = "{{$nombres_cie10}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_nombres_cie10);
    });

    $("#btn_insertar_tipo_controversia_pri_cali").click(function(e){
        e.preventDefault();
        var etiqueta_tipo_controversia_primera_calificacion = "{{$tipo_controversia_primera_calificacion}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_tipo_controversia_primera_calificacion);
    });

    $("#btn_insertar_direccion_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_direccion_afiliado = "{{$direccion_afiliado}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_direccion_afiliado);
    });

    $("#btn_insertar_telefono_afiliado").click(function(e){
        e.preventDefault();
        var etiqueta_telefono_afiliado = "{{$telefono_afiliado}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_telefono_afiliado);
    });

    // $("#btn_insertar_nombre_documento").click(function(e){
    //     e.preventDefault();
    //     var etiqueta_nombre_documento = "{{$nombre_documento}}";
    //     $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_nombre_documento);
    // });

    $("#btn_insertar_correo_solicitud_info").click(function(e){
        e.preventDefault();
        var etiqueta_correo_solicitud_informacion = "{{$correo_solicitud_informacion}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_correo_solicitud_informacion);
    });
    
    /* Funcionalidad para los radio buttons de Oficion Juntas afiliado, Oficio Juntas JRCI, 
        Expediente JRCI, Devol. Expediente JRCI, Solicitud Dictamen JRCI, Otro Documento */
    $("[name='tipo_de_preforma']").on("change", function(){
        var opc_seleccionada = $(this).val();

        if(opc_seleccionada == "Oficio_Afiliado"){
            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto").removeClass('d-none');
            $("#rellenar_asunto").html('Para mostrar todo el asunto dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Asunto.');
            $("#mensaje_cuerpo").removeClass('d-none');
            $("#rellenar_cuerpo").html('');
            $("#rellenar_cuerpo").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto").prop('disabled', false);
            $("#btn_insertar_nombre_junta_regional").prop('disabled', false);
            // botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado").prop('disabled', true);
            $("#btn_insertar_documento_afiliado").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion").prop('disabled', true);
            $("#btn_insertar_tipo_evento").prop('disabled', true);
            $("#btn_insertar_nombres_cie10").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info").prop('disabled', true);

            $("#asunto").val("REMISIÓN DEL CASO A {{$nombre_junta_asunto}}");
            var texto_insertar = "<p>Cordial saludo:</p><p>Dando respuesta a inconformidad presentada frente a calificación de la Pérdida de la Capacidad Laboral emitida por esta aseguradora, dentro de los términos estipulados en la normatividad vigente, se informa: que hoy los documentos concernientes a su caso han sido enviados a la {{$nombre_junta}}, la cual, le citará a la valoración correspondiente.</p><p>Recuerde por favor, que las Juntas Regionales de Calificación de Invalidez son entidades independientes y autónomas de las compañías de seguros, cuyos procesos y procedimientos son ajenos a nuestra entidad, por lo tanto, cualquier requerimiento ante la misma debe ser tratado directamente con la Junta Regional de Calificación de Invalidez.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escríbanos a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio José María Córdoba, Bogotá D.C.</p>";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

            // Auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', true);
            
        }else if(opc_seleccionada == "Oficio_Juntas_JRCI"){
            // Esta proforma no tiene botones para insertar en el asunto y/o cuerpo del comunicado

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto").addClass('d-none');
            $("#rellenar_asunto").html('');
            $("#mensaje_cuerpo").addClass('d-none');
            $("#rellenar_cuerpo").html('');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado").prop('disabled', true);
            $("#btn_insertar_documento_afiliado").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion").prop('disabled', true);
            $("#btn_insertar_tipo_evento").prop('disabled', true);
            $("#btn_insertar_nombres_cie10").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info").prop('disabled', true);

            $("#asunto").val('N/A');
            $('#cuerpo_comunicado').summernote('code', 'N/A');

            // Auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', true);

        }else if(opc_seleccionada == "Remision_Expediente_JRCI"){

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto").addClass('d-none');
            $("#rellenar_asunto").html('');
            $("#mensaje_cuerpo").removeClass('d-none');
            $("#rellenar_cuerpo").html('');
            $("#rellenar_cuerpo").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir las etiquetas N° Orden pago, Fecha Notificación al Afiliado, Fecha Radicación Controversia Primera Calificación, Tipo Documento Afiliado, Documento Afiliado, Nombre Afiliado, Fecha Estructuración, Tipo de Evento, Nombres CIE-10, Tipo Controversia Primera Calificación, Dirección Afiliado, Teléfono Afiliado, dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago").prop('disabled', false);
            $("#btn_insertar_fecha_notifi_afiliado").prop('disabled', false);
            $("#btn_insertar_fecha_radi_contro_pri_cali").prop('disabled', false);
            $("#btn_insertar_tipo_doc_afiliado").prop('disabled', false);
            $("#btn_insertar_documento_afiliado").prop('disabled', false);
            $("#btn_insertar_nombre_afiliado").prop('disabled', false);
            $("#btn_insertar_fecha_estructuracion").prop('disabled', false);
            $("#btn_insertar_tipo_evento").prop('disabled', false);
            $("#btn_insertar_nombres_cie10").prop('disabled', false);
            $("#btn_insertar_tipo_controversia_pri_cali").prop('disabled', false);
            $("#btn_insertar_direccion_afiliado").prop('disabled', false);
            $("#btn_insertar_telefono_afiliado").prop('disabled', false);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info").prop('disabled', true);

            $("#asunto").val("REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA");
            var texto_insertar = "<p>Respetados señores, cordial saludo:</p><p>En aras de tramitar el recurso y/o controversia presentada en tiempo por la parte interesada contra el dictamen de calificación de PÉRDIDA DE CAPACIDAD LABORAL, remitimos el expediente del afiliado con la documentación exigida por el artículo 30 del Decreto 1352 de 2013 (historia clínica, constancia de notificación, dictamen médico laboral, controversia, etc.) para su valoración.</p><p>Según lo dispone el artículo 20 del mismo decreto, el valor de los honorarios corresponde a un (1) salario mínimo mensual legal vigente, el cual fue cancelado por esta aseguradora. Para los efectos, adjuntamos orden de pago de honorarios No {{$nro_orden_pago}}.</p><p>Finalmente, indicamos que la fecha de notificación del dictamen lo fue el {{$fecha_notificacion_afiliado}} y la radicación del desacuerdo el {{$fecha_radicacion_controversia_primera_calificacion}}, razón por la cual es procedente tramitar el recurso.</p><p>Los datos del afiliado son los siguientes:</p><table class='tabla_cuerpo_remision_expediente'><tbody><tr><td class='bg'><b>TIPO Y No. DE IDENTIFICACIÓN</b></td><td><p>{{$tipo_documento_afiliado}}{{$documento_afiliado}}<br></p></td></tr><tr><td class='bg'><b>NOMBRE COMPLETO</b></td><td><p>{{$nombre_afiliado}}<br></p></td></tr><tr><td class='bg'><b>FECHA DE ESTRUCTURACIÓN Y TIPO DE EVENTO</b></td><td><p>PCL {{$porcentaje_pcl}}-{{$fecha_estructuracion}}-{{$tipo_evento}}<br></p></td></tr><tr><td class='bg'><b>DIAGNÓSTICO</b></td><td><p>{{$nombres_cie10}}<br></p></td></tr><tr><td class='bg'><b>CONTROVERSIA POR</b></td><td><p>{{$tipo_controversia_primera_calificacion}}<br></p></td></tr><tr><td class='bg'><b>DIRECCIÓN Y TELÉFONO DEL ASEGURADO</b></td><td><p>{{$direccion_afiliado}} {{$telefono_afiliado}}<br></p></td></tr><tr><td class='bg'><b>OBSERVACIONES</b></td><td><p>{{$observaciones}}</p></td></tr></tbody></table><p>En virtud de lo señalado en el Artículo 2 del Decreto 1352 de 2013 que establece:</p><p>Artículo 2. Personas interesadas. Para efectos del presente decreto, se entenderá como personas interesadas en el dictamen y de obligatoria notificación o comunicación como mínimo las siguientes:</p><ul><li><span style=''>La persona objeto de dictamen o sus beneficiarios en caso de muerte.</span></li><li><span style=''>La Entidad Promotora de Salud.</span></li><li><span style=''>La Administradora de Riesgos Laborales.</span></li><li><span style=''>La Administradora del Fondo de Pensiones o Administradora de Régimen de Prima Media.</span></li><li><span style=''>El Empleador.</span></li><li><span style=''>La Compañía de Seguro que asuma el riesgo de invalidez, sobrevivencia y muerte. (Subrayado fuera del texto original).</span></li></ul><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escríbanos a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio José María Córdoba, Bogotá D.C.</p>";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

            // Auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // selección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', true);

        }else if(opc_seleccionada == "Devolucion_Expediente_JRCI"){
            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto").addClass('d-none');
            $("#rellenar_asunto").html('');
            $("#mensaje_cuerpo").removeClass('d-none');
            $("#rellenar_cuerpo").html('');
            $("#rellenar_cuerpo").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional").prop('disabled', false);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado").prop('disabled', true);
            $("#btn_insertar_documento_afiliado").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion").prop('disabled', true);
            $("#btn_insertar_tipo_evento").prop('disabled', true);
            $("#btn_insertar_nombres_cie10").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento").prop('disabled', false);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info").prop('disabled', true);

            $("#asunto").val("RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE");
            var texto_insertar = "<p>Reciban un cordial saludo,</p>\
            <p>En respuesta a la solicitud radicada por ustedes, esta aseguradora se permite dar respuesta en los términos que se describen a continuación:</p>\
            <p>Una vez revisadas nuestras bases y sistemas de información evidenciamos que el día (Anotar Fecha de envío de expediente) esta compañía solicitó a ustedes calificación de (Anotar la causal de controversia) en virtud de la controversia interpuesta por el usuario en asunto.</p>\
            <p>Ahora bien, con respecto a la solicitud emitida por ustedes, nos permitimos informar que recibimos el expediente en devolución, motivo por el cual el grupo interdisciplinario de Seguros de Vida Alfa realizó la respectiva verificación por lo que remitimos nuevamente el expediente completo con la documentación solicitada:</p>\
            <p style='color:red;'>Nota: En este espacio del documento debe crear la tabla de Documento y No. Folio.<p>\
            <p>Por lo anterior, solicitamos amablemente a la Honorable {{$nombre_junta}} para que se realice el trámite correspondiente según la normatividad vigente teniendo en cuenta que la afiliada se encuentra en términos legales para dirimir la controversia interpuesta.</p>\
            <p>Agradecemos la atención prestada y reiteramos nuestra voluntad de servicio.</p>\
            ";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

            // Auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', true);

        }else if(opc_seleccionada == "Solicitud_Dictamen_JRCI"){
            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto").addClass('d-none');
            $("#rellenar_asunto").html('');
            $("#mensaje_cuerpo").removeClass('d-none');
            $("#rellenar_cuerpo").html('');
            $("#rellenar_cuerpo").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir las etiquetas Nombre Afiliado, Correo Solicitud Información, dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado").prop('disabled', true);
            $("#btn_insertar_documento_afiliado").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado").prop('disabled', false);
            $("#btn_insertar_fecha_estructuracion").prop('disabled', true);
            $("#btn_insertar_tipo_evento").prop('disabled', true);
            $("#btn_insertar_nombres_cie10").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info").prop('disabled', false);

            $("#asunto").val("ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN");
            var texto_insertar = "<p>Respetados señores, cordial saludo:</p>\
            <p>Revisadas nuestras bases de datos y sistemas de información evidenciamos que su entidad remitió a la AFP Porvenir, notificación del dictamen de calificación de Pérdida de Capacidad Laboral (PCL) el pasado (Anote Fecha de notificación del Dictamen de JRCI) acaecido al señor {{$nombre_afiliado}}, por lo que esta entidad remitió estos comunicados a Seguros de Vida Alfa, como aseguradora que expidió su seguro previsional.</p>\
            <p>Al respecto, el equipo interdisciplinario de calificación procedió a revisar sus comunicaciones y evidenció que dentro de dicha notificación no se adjuntó el Dictamen, por lo que el día (Anote Fecha de solicitud de Dictamen) se solicitó a los correos {{$correo_solicitud_informacion}}, el envío del mismo con el fin de verificar cada una de las razones de hecho y de derecho de la decisión tomada por ustedes.</p>\
            <p>En consecuencia, se observa que después de esta petición no se tiene radicación o aclaración sobre lo antes mencionado, por lo que se solicita amablemente emitir estado de caso sobre la notificación y el recurso emitido, toda vez que en su momento no se tuvo como verificar lo calificado por ustedes a favor del señor {{$nombre_afiliado}}.</p>\
            <p>Dicha notificación se puede realizar en la dirección Carrera 10 # 18 - 36 Edificio Córdoba Piso 4 en Bogotá.</p>\
            ";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

            // Auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', true);

        }else{
            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto").addClass('d-none');
            $("#rellenar_asunto").html('');
            $("#mensaje_cuerpo").addClass('d-none');
            $("#rellenar_cuerpo").html('');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado").prop('disabled', true);
            $("#btn_insertar_documento_afiliado").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion").prop('disabled', true);
            $("#btn_insertar_tipo_evento").prop('disabled', true);
            $("#btn_insertar_nombres_cie10").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info").prop('disabled', true);

            $("#asunto").val('');
            $('#cuerpo_comunicado').summernote('code', '');

            // Quitar auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado').prop('checked', false);

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#copia_afiliado").prop('checked', false);

            // Deselección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', false);

        }
    });

    //Cargar_comunicadopcl
    $('#cargarComunicado').click(function(){
        if(!$('#cargue_comunicados')[0].files[0]){
            return $(".cargueundocumentoprimero").removeClass('d-none');
        }
        $(".cargueundocumentoprimero").addClass('d-none');
        var archivo = $('#cargue_comunicados')[0].files[0];
        var documentName = archivo.name;
        var formData = new FormData($('form')[0]);
        formData.append('cargue_comunicados', archivo);
        formData.append('token', $('input[name=_token]').val());
        formData.append('ciudad', $('#ciudad').val());
        formData.append('Id_evento',$('#Id_evento').val());
        formData.append('Id_asignacion',$('#Id_asignacion').val());
        formData.append('Id_procesos',$('#Id_procesos').val());
        formData.append('fecha_comunicado2',$('#fecha_comunicado2').val());
        formData.append('radicado2',$('#radicado2').val());
        formData.append('cliente_comunicado2',$('#cliente_comunicado2').val());
        formData.append('nombre_afiliado_comunicado2',$('#nombre_afiliado_comunicado2').val());
        formData.append('tipo_documento_comunicado2',$('#tipo_documento_comunicado2').val());
        formData.append('identificacion_comunicado2',$('#identificacion_comunicado2').val());
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
        formData.append('modulo_creacion','calificacionJuntas');
        formData.append('modulo','Gestion de controversia - seguimientos juntas');
        
        document.querySelector("#Generar_comunicados").disabled = true;   
        $.ajax({
            type:'POST',
            url:'/registrarComunicadoJuntas',
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
                        // localStorage.setItem("#Generar_comunicados", true);
                        location.reload()
                    }, 3000);
                }
            },
            complete:function(){
                $("#cargarComunicado").removeClass("descarga-deshabilitada");
            }
        });  
    }); 

    // llenado del formulario para la captura de la modal de Generar Comunicado
    $('#form_generarComunicadoJuntas').submit(function (e) {
        e.preventDefault();  

        $("#Generar_comunicados").remove();
        $("#mostrar_barra_creacion_comunicado").removeClass('d-none');
        var ciudad = $('#ciudad').val();
        var Id_evento = $('#Id_evento').val();
        var Id_asignacion = $('#Id_asignacion').val();
        var Id_procesos = $('#Id_procesos').val();
        var fecha_comunicado2 = $('#fecha_comunicado2').val();
        var radicado2 = $('#radicado2').val();
        var cliente_comunicado2 = $('#cliente_comunicado2').val();
        var nombre_afiliado_comunicado2 = $('#nombre_afiliado_comunicado2').val();
        var tipo_documento_comunicado2 = $('#tipo_documento_comunicado2').val();
        var identificacion_comunicado2 = $('#identificacion_comunicado2').val();  
        var jrci_comunicado = $('#jrci_comunicado').prop('checked');
        var jnci_comunicado = $('#jnci_comunicado').prop('checked');                     
        var afiliado_comunicado = $('#afiliado_comunicado').prop('checked');
        var empresa_comunicado = $('#empresa_comunicado').prop('checked');
        var eps_comunicado = $('#eps_comunicado').prop('checked');
        var afp_comunicado = $('#afp_comunicado').prop('checked');
        var arl_comunicado = $('#arl_comunicado').prop('checked');
        var Otro = $('#Otro').prop('checked');
        var N_siniestro = $("#n_siniestro_comunicado").val();

        var radiojrci_comunicado, radiojnci_comunicado, radioafiliado_comunicado,
        radioempresa_comunicado, radioeps_comunicado, radioafp_comunicado,
        radioarl_comunicado, radioOtro;

        var JRCI_Destinatario;
        if(jrci_comunicado){
            radiojrci_comunicado = jrci_comunicado;
            if($('#input_jrci_seleccionado').val() != ""){
                JRCI_Destinatario = $('#input_jrci_seleccionado').val();
            }else{
                JRCI_Destinatario = $("#jrci_califi_invalidez_comunicado").val();
            }
        }else if(jnci_comunicado){
            radiojnci_comunicado = jnci_comunicado;
            JRCI_Destinatario = '';
        }
        else if(afiliado_comunicado){
           radioafiliado_comunicado = afiliado_comunicado;
           JRCI_Destinatario = '';
        }
        else if(empresa_comunicado){
            radioempresa_comunicado = empresa_comunicado;
            JRCI_Destinatario = '';
        }else if(eps_comunicado){
            radioeps_comunicado = eps_comunicado;
            JRCI_Destinatario = '';
        }else if(afp_comunicado){
            radioafp_comunicado = afp_comunicado;
            JRCI_Destinatario = '';
        }else if(arl_comunicado){
            radioarl_comunicado = arl_comunicado;
            JRCI_Destinatario = '';
        }else if(Otro){
            radioOtro = Otro;
            JRCI_Destinatario = '';
        }
        
        //console.log(radioafiliado_comunicado);
        var nombre_destinatario = $('#nombre_destinatario').val();
        var nic_cc = $('#nic_cc').val();
        var direccion_destinatario = $('#direccion_destinatario').val();
        var telefono_destinatario = $('#telefono_destinatario').val();
        var email_destinatario = $('#email_destinatario').val();
        var departamento_destinatario = $('#departamento_destinatario').val();
        var ciudad_destinatario = $('#ciudad_destinatario').val();
        var asunto = $('#asunto').val();
        var cuerpo_comunicado = $('#cuerpo_comunicado').val();
        var anexos = $('#anexos').val();
        var forma_envio = $('#forma_envio').val();
        var elaboro2 = $('#elaboro2').val();
        var reviso = $('#reviso').val();
        var firmarcomunicado = $('#firmarcomunicado').filter(":checked").val();
        var tipo_descarga = $("[name='tipo_de_preforma']").filter(":checked").val();
        //Copias Interesadas Origen
        var copiaComunicadoTotal = [];
        let copias = {};
        cuerpo_comunicado = cuerpo_comunicado ? cuerpo_comunicado.replace(/"/g, "'") : '';

        $('input[type="checkbox"]').each(function() {
            var copiaComunicado = $(this).attr('id');            
            if (copiaComunicado === 'copia_afiliado' || copiaComunicado === 'copia_empleador' || 
                copiaComunicado === 'copia_eps' || copiaComunicado === 'copia_afp' || copiaComunicado === 'copia_arl' || copiaComunicado === 'copia_afp_conocimiento'
                || copiaComunicado === 'copia_jrci' || copiaComunicado === 'copia_jnci') {                
                if ($(this).is(':checked')) {     
                    var relacionCopiaValor = $(this).val();
                    copiaComunicadoTotal.push(relacionCopiaValor);
                    copias[$(this).val()] = true;
                }else{
                    copias[$(this).val()] = false;
                }
            }
        });

        var JRCI_copia;
        if($("#copia_jrci").is(':checked')){
            if($("#input_jrci_seleccionado_copia").val() != ''){
                JRCI_copia = $("#input_jrci_seleccionado_copia").val();
            }else{
                JRCI_copia = $("#jrci_califi_invalidez_copia").val();
            }
        }else{
            JRCI_copia= '';
        }

        //console.log(copiaComunicadoTotal);
        let token = $('input[name=_token]').val();        
        var datos_generarComunicado = {
            '_token': token,
            'ciudad':ciudad,
            'Id_evento':Id_evento,
            'Id_asignacion':Id_asignacion,
            'Id_procesos':Id_procesos,
            'fecha_comunicado2':fecha_comunicado2,
            'radicado2':radicado2,
            'cliente_comunicado2':cliente_comunicado2,
            'nombre_afiliado_comunicado2':nombre_afiliado_comunicado2,
            'tipo_documento_comunicado2':tipo_documento_comunicado2,
            'identificacion_comunicado2':identificacion_comunicado2,
            'radiojrci_comunicado': radiojrci_comunicado ?? null,
            'JRCI_Destinatario': JRCI_Destinatario,  
            'radiojnci_comunicado': radiojnci_comunicado ?? null,        
            'radioafiliado_comunicado':radioafiliado_comunicado ?? null,
            'radioempresa_comunicado':radioempresa_comunicado ?? null,
            'radioeps_comunicado': radioeps_comunicado ?? null,
            'radioafp_comunicado': radioafp_comunicado ?? null,
            'radioarl_comunicado': radioarl_comunicado ?? null,
            'radioOtro':radioOtro ?? null,
            'nombre_destinatario':nombre_destinatario,
            'nic_cc':nic_cc,
            'direccion_destinatario':direccion_destinatario,
            'telefono_destinatario':telefono_destinatario,
            'email_destinatario':email_destinatario,
            'departamento_destinatario':departamento_destinatario,
            'ciudad_destinatario':ciudad_destinatario,
            'asunto':asunto,
            'cuerpo_comunicado':cuerpo_comunicado,
            'anexos':anexos,
            'forma_envio':forma_envio,
            'elaboro2':elaboro2,
            'reviso':reviso,
            'copiaComunicadoTotal':copiaComunicadoTotal,
            'JRCI_copia': JRCI_copia,
            'firmarcomunicado':firmarcomunicado,
            'tipo_descarga': tipo_descarga,
            'modulo_creacion':'calificacionJuntas',
            'N_siniestro':N_siniestro,
            'Nombre_junta_act': $('#Nombre_junta_act').val(),
            'F_estructuracion_act': $('#F_estructuracion_act').val(),
            'F_dictamen_act' : $('#F_dictamen_act').val(),
            'input_jrci_seleccionado_copia_editar' : JRCI_copia,
            'id_jrci_del_input': $('#Id_junta_act').val(),
            'F_radicacion_contro_pri_cali_act' : $('#F_radicacion_contro_pri_cali_act').val(),
            'F_notifi_afiliado_act' : $('#F_notifi_afiliado_act').val(),
            'Id_junta_act' : $('#Id_junta_act').val(),
            "edit_copia_afiliado" : copias['Afiliado'] ,
            "edit_copia_empleador" : copias['Empleador'],
            "edit_copia_eps" : copias['EPS'],
            "edit_copia_afp" : copias['AFP'],
            "edit_copia_arl" : copias['ARL'],
            "edit_copia_jrci" : copias['JRCI'],
            "edit_copia_jnci" : copias['JNCI'],
            'tipo_de_preforma_editar': tipo_descarga
        }


        $.ajax({
            type:'POST',
            url:'/registrarComunicadoJuntas',
            data: datos_generarComunicado,            
            success:function(response){
                if (response.parametro == 'agregar_comunicado') {
                    $("#mostrar_barra_creacion_comunicado").addClass('d-none');
                    $('.alerta_comunicado').removeClass('d-none');
                    $('.alerta_comunicado').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_comunicado').addClass('d-none');
                        $('.alerta_comunicado').empty();
                        localStorage.setItem("#Generar_comunicados", true);
                        location.reload();
                    }, 3000);
                }
            }
        });
    }) 

    // Abrir modal de agregar solictudes despues de guardar 
    if (localStorage.getItem("#Generar_comunicados")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#Generar_comunicados");
        document.querySelector("#clicGuardado").click();
    }
    let selectores_notificacion = {
        '_token': $('input[name=_token]').val(),
        'parametro': 'EstadosNotificaion'
    }

    let opciones_Notificacion = [];

    $.ajax({
        type: 'POST',
        url: '/cargarselectores',
        data: selectores_notificacion,
        success: function (data) {
            $.each(data, function (index, item) {
                //Establecemos el color que tendra le texto de cada opcion segun corresponda
                let color = (()=>{
                    switch(item.Nombre_parametro){
                        case 'Pendiente':
                            return '#000000'; //negro
                            break;
                        case 'No notificar':
                            return '#CBCBCB'; //Gris
                            break;
                        case 'Devuelto':
                            return '#E70000'; //Rojo
                            break;
                        case 'Notificado efectivamente':
                            return '#00E738'; //Verde
                            break;
                        case 'Notificado parcialmente':
                            return '#00ACE7'; //Azul
                            break;
                    }
                })();
                
                /**@var opciones_Notificacion Corresponde a las propiedades del elemento */
                opciones_Notificacion.push({
                    opciones: `<option value="${item.Id_Parametro}">${item.Nombre_parametro}</option>`,
                    id:item.Id_Parametro,
                    texto: item.Nombre_parametro,
                    color: color
                });
            });

        },
    });

    // Captura de data para la tabla de Comunicados
    // data de la modal de agregar comunicados

    let datos_comunicados ={
        '_token':token,
        'HistorialComunicadosOrigen': "CargarComunicados",
        'newId_evento':$('#newId_evento').val(),
        'newId_asignacion':$('#newId_asignacion').val(),
    }

    $.ajax({
        type:'POST',
        url:'/historialComunicadoJuntas',
        data: datos_comunicados,
        success:function(data){
            var comunicadoNradico = '';  
            /** @var select2 Config. del select2 */
            let select2 = [];
            for (let i = 0; i < data.hitorialAgregarComunicado.length; i++) {
                
                let estado_correspondencia = {}
                let estado_notificacion = data.hitorialAgregarComunicado[i].Estado_Notificacion;
                
                if (data.enviar_notificacion[0].Notificacion == 'Si') {
                    estado_correspondencia ={
                        deshabilitar_selector : data.hitorialAgregarComunicado[i].Estado_correspondencia == '1' ||(estado_notificacion == 359 ||  estado_notificacion == 358) ? false : true,
                        deshabilitar_edicion: data.hitorialAgregarComunicado[i].Estado_correspondencia == '1' ||(estado_notificacion == 359 ||  estado_notificacion == 358) ? '' : 'pointer-events: none; color: gray;',
                        deshabilitar_remplazar: data.hitorialAgregarComunicado[i].Estado_correspondencia == '1' ||(estado_notificacion == 359 ||  estado_notificacion == 358) ? '' : 'pointer-events: none; color: gray;'
                    };
                }
                if (data.hitorialAgregarComunicado[i].N_radicado != '' && data.hitorialAgregarComunicado[i].Tipo_descarga != 'Manual'){
                    let comunicadoNradico = '<div style="display: flex; flex-direction: row; justify-content: space-around; align-items: center">';
                    if (!data.hitorialAgregarComunicado[i].Correspondencia && idRol !== '7' && !data.hitorialAgregarComunicado[i].Asunto.includes('Expediente_chequeo')) {
                        comunicadoNradico += '<a href="javascript:void(0);" class="text-dark" data-toggle="modal" data-target="#modalcomunicados_" id="EditarComunicado_'+data.hitorialAgregarComunicado[i].Id_Comunicado+'" title="Editar Comunicado"\
                            data-id_comunicado="'+data.hitorialAgregarComunicado[i].Id_Comunicado+'" data-id_evento="'+data.hitorialAgregarComunicado[i].ID_evento+'"\
                            data-id_asignacion="'+data.hitorialAgregarComunicado[i].Id_Asignacion+'" data-id_proceso="'+data.hitorialAgregarComunicado[i].Id_proceso+'"\
                            data-ciudad_comunicaddo="'+data.hitorialAgregarComunicado[i].Ciudad+'" data-fecha_comunicado="'+data.hitorialAgregarComunicado[i].F_comunicado+'"\
                            data-numero_radicado="'+data.hitorialAgregarComunicado[i].N_radicado+'" data-cliente_comunicado="'+data.hitorialAgregarComunicado[i].Cliente+'"\
                            data-nombre_afiliado="'+data.hitorialAgregarComunicado[i].Nombre_afiliado+'" data-tipo_documento="'+data.hitorialAgregarComunicado[i].T_documento+'"\
                            data-numero_identificacion="'+data.hitorialAgregarComunicado[i].N_identificacion+'" data-destinatario_principal="'+data.hitorialAgregarComunicado[i].Destinatario+'"\
                            data-jrci_destinatario="'+data.hitorialAgregarComunicado[i].JRCI_Destinatario+'"\
                            data-nombre_destinatario="'+data.hitorialAgregarComunicado[i].Nombre_destinatario+'" data-niccc_comunicado="'+data.hitorialAgregarComunicado[i].Nit_cc+'"\
                            data-direccion_destinatario="'+data.hitorialAgregarComunicado[i].Direccion_destinatario+'" data-telefono_destinatario="'+data.hitorialAgregarComunicado[i].Telefono_destinatario+'"\
                            data-email_destinatario="'+data.hitorialAgregarComunicado[i].Email_destinatario+'" data-id_departamento="'+data.hitorialAgregarComunicado[i].Id_departamento+'"\
                            data-nombre_departamento="'+data.hitorialAgregarComunicado[i].Nombre_departamento+'" data-id_municipio="'+data.hitorialAgregarComunicado[i].Id_municipio+'"\
                            data-nombre_municipio="'+data.hitorialAgregarComunicado[i].Nombre_municipio+'" data-asunto_comunicado="'+data.hitorialAgregarComunicado[i].Asunto+'"\
                            data-cuerpo_comunicado="'+data.hitorialAgregarComunicado[i].Cuerpo_comunicado+'" data-anexos_comunicados="'+data.hitorialAgregarComunicado[i].Anexos+'"\
                            data-forma_envio_comunicado="'+data.hitorialAgregarComunicado[i].Forma_envio+'" data-nombre_envio_comunicado="'+data.hitorialAgregarComunicado[i].Nombre_forma_envio+'"\
                            data-elaboro_comunicado="'+data.hitorialAgregarComunicado[i].Elaboro+'"\
                            data-reviso_comunicado="'+data.hitorialAgregarComunicado[i].Reviso+'" data-revisonombre_comunicado="'+data.hitorialAgregarComunicado[i].Nombre_lider+'"\
                            data-firmar_comunicado="'+data.hitorialAgregarComunicado[i].Firmar_Comunicado+'"\
                            data-jrci_copia="'+data.hitorialAgregarComunicado[i].JRCI_copia+'"\
                            data-agregar_copia="'+data.hitorialAgregarComunicado[i].Agregar_copia+'" data-tipo_descarga="'+data.hitorialAgregarComunicado[i].Tipo_descarga+'"\
                            data-modulo_creacion="'+data.hitorialAgregarComunicado[i].Modulo_creacion+'" data-reemplazado="'+data.hitorialAgregarComunicado[i].Reemplazado+'" data-nombre_documento="'+data.hitorialAgregarComunicado[i].Nombre_documento + '"\
                            data-numero_siniestro="'+data.hitorialAgregarComunicado[i].N_siniestro+'"\
                            data-ids_destinatario="'+data.hitorialAgregarComunicado[i].Id_Destinatarios+'"><i style="cursor:pointer; display: flex; justify-content: center; align-items:center;" class="fa fa-pen text-info"></i></a>';
                    }
                    comunicadoNradico += '<a href="javascript:void(0);" class="text-dark" id="verDocumento_'+data.hitorialAgregarComunicado[i].Id_Comunicado+'"\
                        title="Descargar Comunicado"\
                        id_comunicado="'+data.hitorialAgregarComunicado[i].Id_Comunicado+'" id_evento="'+data.hitorialAgregarComunicado[i].ID_evento+'"\
                        id_asignacion="'+data.hitorialAgregarComunicado[i].Id_Asignacion+'" id_proceso="'+data.hitorialAgregarComunicado[i].Id_proceso+'"\
                        ciudad_comunicaddo="'+data.hitorialAgregarComunicado[i].Ciudad+'" fecha_comunicado="'+data.hitorialAgregarComunicado[i].F_comunicado+'"\
                        numero_radicado="'+data.hitorialAgregarComunicado[i].N_radicado+'" cliente_comunicado="'+data.hitorialAgregarComunicado[i].Cliente+'"\
                        nombre_afiliado="'+data.hitorialAgregarComunicado[i].Nombre_afiliado+'" tipo_documento="'+data.hitorialAgregarComunicado[i].T_documento+'"\
                        numero_identificacion="'+data.hitorialAgregarComunicado[i].N_identificacion+'" destinatario_principal="'+data.hitorialAgregarComunicado[i].Destinatario+'"\
                        jrci_destinatario="'+data.hitorialAgregarComunicado[i].JRCI_Destinatario+'"\
                        nombre_destinatario="'+data.hitorialAgregarComunicado[i].Nombre_destinatario+'" niccc_comunicado="'+data.hitorialAgregarComunicado[i].Nit_cc+'"\
                        direccion_destinatario="'+data.hitorialAgregarComunicado[i].Direccion_destinatario+'" telefono_destinatario="'+data.hitorialAgregarComunicado[i].Telefono_destinatario+'"\
                        email_destinatario="'+data.hitorialAgregarComunicado[i].Email_destinatario+'" id_departamento="'+data.hitorialAgregarComunicado[i].Id_departamento+'"\
                        nombre_departamento="'+data.hitorialAgregarComunicado[i].Nombre_departamento+'" id_municipio="'+data.hitorialAgregarComunicado[i].Id_municipio+'"\
                        nombre_municipio="'+data.hitorialAgregarComunicado[i].Nombre_municipio+'" asunto_comunicado="'+data.hitorialAgregarComunicado[i].Asunto+'"\
                        cuerpo_comunicado="'+data.hitorialAgregarComunicado[i].Cuerpo_comunicado+'" anexos_comunicados="'+data.hitorialAgregarComunicado[i].Anexos+'"\
                        forma_envio_comunicado="'+data.hitorialAgregarComunicado[i].Forma_envio+'" nombre_envio_comunicado="'+data.hitorialAgregarComunicado[i].Nombre_forma_envio+'"\
                        elaboro_comunicado="'+data.hitorialAgregarComunicado[i].Elaboro+'"\
                        reviso_comunicado="'+data.hitorialAgregarComunicado[i].Reviso+'" revisonombre_comunicado="'+data.hitorialAgregarComunicado[i].Nombre_lider+'"\
                        firmar_comunicado="'+data.hitorialAgregarComunicado[i].Firmar_Comunicado+'"\
                        jrci_copia="'+data.hitorialAgregarComunicado[i].JRCI_copia+'" agregar_copia="'+data.hitorialAgregarComunicado[i].Agregar_copia+'" tipo_descarga="'+data.hitorialAgregarComunicado[i].Tipo_descarga+'"\
                        modulo_creacion="'+data.hitorialAgregarComunicado[i].Modulo_creacion+'" reemplazado="'+data.hitorialAgregarComunicado[i].Reemplazado+'" nombre_documento="'+data.hitorialAgregarComunicado[i].Nombre_documento + '"\
                        numero_siniestro="'+data.hitorialAgregarComunicado[i].N_siniestro+'"\
                        ids_destinatario="'+data.hitorialAgregarComunicado[i].Id_Destinatarios+'"><i style="cursor:pointer; display: flex; justify-content: center; align-items:center;" class="far fa-eye text-info"></i></a>';
                    if(data.hitorialAgregarComunicado[i].Existe && data.hitorialAgregarComunicado[i].Nombre_documento != null && idRol !== '7'){
                        comunicadoNradico += '<a href="javascript:void(0);" id="replace_file" style="'+estado_correspondencia.deshabilitar_remplazar+'" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalReemplazarArchivos"\
                            data-id_evento="' + data.hitorialAgregarComunicado[i].ID_evento + '" data-id_comunicado="'+ data.hitorialAgregarComunicado[i].Id_Comunicado + '"\
                            data-numero_radicado="'+ data.hitorialAgregarComunicado[i].N_radicado + '" data-fecha_comunicado="' + data.hitorialAgregarComunicado[i].F_comunicado + '"\
                            data-tipo_descarga="'+ data.hitorialAgregarComunicado[i].Tipo_descarga + '" data-asunto_comunicado="' + data.hitorialAgregarComunicado[i].Asunto + '"\
                            data-id_asignacion="'+ data.hitorialAgregarComunicado[i].Id_Asignacion + '" data-id_proceso="' + data.hitorialAgregarComunicado[i].Id_proceso +'"\
                            data-numero_identificacion="'+data.hitorialAgregarComunicado[i].N_identificacion +'" data-nombre_documento="'+data.hitorialAgregarComunicado[i].Nombre_documento + '"\
                            ><i class="fas fa-sync-alt text-info"></i></a>';
                    }
                    // Accion editar lista de chequeo y Expediente
                    if(data.hitorialAgregarComunicado[i].Asunto.includes('Expediente_chequeo') && idRol !== '7' && data.hitorialAgregarComunicado[i].Expediente_estado == 'Reciente'){
                        comunicadoNradico += '<a href="javascript:void(0);" class="text-dark" data-toggle="modal" data-target="#modalCrearExpediente" title="Editar expediente" id="editarExpediente"><i style="cursor:pointer" class="fa fa-pen text-info"></i></a>';
                    }
                    if(idRol !== '7'){
                        comunicadoNradico += `<a href="javascript:void(0);"  class="editar_comunicado_${data.hitorialAgregarComunicado[i].N_radicado}" id="editar_comunicado" data-radicado="${data.hitorialAgregarComunicado[i].N_radicado}" style="display: flex; justify-content: center; ${estado_correspondencia.deshabilitar_edicion}"><i class="fa fa-sm fa-check text-success"></i></a>`;
                    }
                    comunicadoNradico += '</div>';
                    data.hitorialAgregarComunicado[i].Editarcomunicado = comunicadoNradico;
                    
                }
                else if(data.hitorialAgregarComunicado[i].N_radicado != '' && data.hitorialAgregarComunicado[i].Tipo_descarga == 'Manual'){
                    comunicadoNradico = '<div style="display: flex; flex-direction: row; justify-content: space-around; align-items: center"><a href="javascript:void(0);" class="text-dark" id="generar_descarga_archivo_'+data.hitorialAgregarComunicado[i].Id_Comunicado+'"\
                    title="Descargar Comunicado"\
                    id_comunicado="'+data.hitorialAgregarComunicado[i].Id_Comunicado+'" id_evento="'+data.hitorialAgregarComunicado[i].ID_evento+'"\
                    id_asignacion="'+data.hitorialAgregarComunicado[i].Id_Asignacion+'" id_proceso="'+data.hitorialAgregarComunicado[i].Id_proceso+'"\
                    ciudad_comunicaddo="'+data.hitorialAgregarComunicado[i].Ciudad+'" fecha_comunicado="'+data.hitorialAgregarComunicado[i].F_comunicado+'"\
                    numero_radicado="'+data.hitorialAgregarComunicado[i].N_radicado+'" cliente_comunicado="'+data.hitorialAgregarComunicado[i].Cliente+'"\
                    nombre_afiliado="'+data.hitorialAgregarComunicado[i].Nombre_afiliado+'" tipo_documento="'+data.hitorialAgregarComunicado[i].T_documento+'"\
                    numero_identificacion="'+data.hitorialAgregarComunicado[i].N_identificacion+'" destinatario_principal="'+data.hitorialAgregarComunicado[i].Destinatario+'"\
                    jrci_destinatario="'+data.hitorialAgregarComunicado[i].JRCI_Destinatario+'"\
                    nombre_destinatario="'+data.hitorialAgregarComunicado[i].Nombre_destinatario+'" niccc_comunicado="'+data.hitorialAgregarComunicado[i].Nit_cc+'"\
                    direccion_destinatario="'+data.hitorialAgregarComunicado[i].Direccion_destinatario+'" telefono_destinatario="'+data.hitorialAgregarComunicado[i].Telefono_destinatario+'"\
                    email_destinatario="'+data.hitorialAgregarComunicado[i].Email_destinatario+'" id_departamento="'+data.hitorialAgregarComunicado[i].Id_departamento+'"\
                    nombre_departamento="'+data.hitorialAgregarComunicado[i].Nombre_departamento+'" id_municipio="'+data.hitorialAgregarComunicado[i].Id_municipio+'"\
                    nombre_municipio="'+data.hitorialAgregarComunicado[i].Nombre_municipio+'" asunto_comunicado="'+data.hitorialAgregarComunicado[i].Asunto+'"\
                    cuerpo_comunicado=\''+data.hitorialAgregarComunicado[i].Cuerpo_comunicado+'\' anexos_comunicados="'+data.hitorialAgregarComunicado[i].Anexos+'"\
                    forma_envio_comunicado="'+data.hitorialAgregarComunicado[i].Forma_envio+'" nombre_envio_comunicado="'+data.hitorialAgregarComunicado[i].Nombre_forma_envio+'"\
                    elaboro_comunicado="'+data.hitorialAgregarComunicado[i].Elaboro+'"\
                    reviso_comunicado="'+data.hitorialAgregarComunicado[i].Reviso+'" revisonombre_comunicado="'+data.hitorialAgregarComunicado[i].Nombre_lider+'"\
                    firmar_comunicado="'+data.hitorialAgregarComunicado[i].Firmar_Comunicado+'"\
                    jrci_copia="'+data.hitorialAgregarComunicado[i].JRCI_copia+'"\
                    agregar_copia="'+data.hitorialAgregarComunicado[i].Agregar_copia+'"tipo_descarga="'+data.hitorialAgregarComunicado[i].Tipo_descarga+ '"\
                    modulo_creacion="'+data.hitorialAgregarComunicado[i].Modulo_creacion+'" reemplazado="'+data.hitorialAgregarComunicado[i].Reemplazado+'" nombre_documento="'+data.hitorialAgregarComunicado[i].Nombre_documento + '"\
                    ids_destinatario="'+data.hitorialAgregarComunicado[i].Id_Destinatarios+'"><i style="cursor:pointer" id="comunicado_manual_boton" class="far fa-eye text-info"></i></a>';
                    if(data.hitorialAgregarComunicado[i].Existe  && !data.hitorialAgregarComunicado[i].Asunto.includes('Lista_chequeo') && idRol !== '7'){
                        comunicadoNradico += '<a href="javascript:void(0);" id="replace_file" style="'+estado_correspondencia.deshabilitar_remplazar+'" class="text-dark text-md" label="Open Modal" data-toggle="modal" data-target="#modalReemplazarArchivos"\
                            data-id_evento="' + data.hitorialAgregarComunicado[i].ID_evento + '" data-id_comunicado="'+ data.hitorialAgregarComunicado[i].Id_Comunicado + '"\
                            data-numero_radicado="'+ data.hitorialAgregarComunicado[i].N_radicado + '" data-fecha_comunicado="' + data.hitorialAgregarComunicado[i].F_comunicado + '"\
                            data-tipo_descarga="'+ data.hitorialAgregarComunicado[i].Tipo_descarga + '" data-asunto_comunicado="' + data.hitorialAgregarComunicado[i].Asunto + '"\
                            data-id_asignacion="'+ data.hitorialAgregarComunicado[i].Id_Asignacion + '" data-id_proceso="' + data.hitorialAgregarComunicado[i].Id_proceso +'"\
                            data-numero_identificacion="'+data.hitorialAgregarComunicado[i].N_identificacion + '" data-nombre_documento="'+data.hitorialAgregarComunicado[i].Nombre_documento +'"\
                            ><i class="fas fa-sync-alt text-info"></i></a>';
                    }

                    // Accion editar lista de chequeo
                    if(data.hitorialAgregarComunicado[i].Asunto.includes('Lista_chequeo') && idRol !== '7'){
                        comunicadoNradico += '<a href="javascript:void(0);" class="text-dark" data-toggle="modal" data-target="#modalCrearExpediente" title="Editar expediente" id="editarExpediente"><i style="cursor:pointer" class="fa fa-pen text-info"></i></a>';
                    }
                    if(idRol !== '7'){
                        comunicadoNradico += `<a href="javascript:void(0);"  class="editar_comunicado_{{$comunicados->N_radicado}}" id="editar_comunicado" data-radicado="${data.hitorialAgregarComunicado[i].N_radicado}" style="display: flex; justify-content: center; ${estado_correspondencia.deshabilitar_edicion}"><i class="fa fa-sm fa-check text-success"></i></a>`;
                    }
                    comunicadoNradico += '</div>';
                    data.hitorialAgregarComunicado[i].Editarcomunicado = comunicadoNradico;
                }
                else{
                    data.hitorialAgregarComunicado[i].Editarcomunicado = "";
                }

                //Obtenemos los datos de los campos 'Destinatarios','Estado_general','Nota' para mostrar en la tabla de comunicados y expedientes
                let info_notificacion = getHistorialNotificacion(data.hitorialAgregarComunicado[i].N_radicado,data.hitorialAgregarComunicado[i].Nota,opciones_Notificacion,data.hitorialAgregarComunicado[i],entidades_conocimiento,true);
                if (data.enviar_notificacion[0].Notificacion == 'Si') {
                    data.hitorialAgregarComunicado[i].Destinatarios = info_notificacion.Destinatarios;                    
                }
                data.hitorialAgregarComunicado[i].Estado_General = info_notificacion.Estado_General;
                data.hitorialAgregarComunicado[i].Nota = info_notificacion.Nota_Comunicados;

                //Configuracion que se cargara en el select2
                let select2Config = {
                    selector: `#status_notificacion_${data.hitorialAgregarComunicado[i].N_radicado}`,
                    default:  data.hitorialAgregarComunicado[i].Estado_Notificacion, //Opcion a selecionar
                    data: opciones_Notificacion, // Opciones disponibles para seleccionar
                    enable:  estado_correspondencia.deshabilitar_selector
                };

                
                select2.push(select2Config);

            }
            $.each(data.hitorialAgregarComunicado, function(index, value){
                capturar_informacion_comunicados(data.hitorialAgregarComunicado, index, value, data.enviar_notificacion[0].Notificacion)
            });

            //Se inicializa configuracion de select2 con los estilos definidos
            select2.forEach(function(item) {
                $(item.selector).select2({
                    placeholder: "Seleccione una opción",
                    allowClear: false,
                    data: item.data,
                    disabled:item.enable,
                    templateResult: function(data){
                        if(data.color != undefined){
                            return $(`<span style="color: ${data.color}">${data.texto}</span>`); //Opciones disponibles
                        }
                    },
                    templateSelection: function(data){
                        if(data.color != undefined){
                            return $(`<span style="color: ${data.color}">${data.texto}</span>`); //Opcion selecionada
                        }
                    }
                }).val(item.default);

                $(item.selector).trigger('change');

            });

        }
    });

    //DataTable Historial de comunicados

    function capturar_informacion_comunicados(response, index, value, enviara) {
        let columns = [
            {"data":"N_radicado"},
            {"data":"Elaboro"},
            {"data":"F_comunicado"},
            { 
                "data": function(row) {
                    if (row.Tipo_descarga === "Oficio_Afiliado") {
                        return "Oficio Afiliado";
                    }
                    else if(row.Tipo_descarga === 'Oficio_Juntas_JRCI'){
                        return "Oficio Juntas JRCI";
                    }
                    else if(row.Tipo_descarga === 'Remision_Expediente_JRCI'){
                        return "Remisión Expediente JRCI";
                    }
                    else if(row.Tipo_descarga === 'Devolucion_Expediente_JRCI'){
                        return "Devolución Expediente JRCI";
                    }
                    else if(row.Tipo_descarga === 'Solicitud_Dictamen_JRCI'){
                        return "Solicitud Dictamen JRCI";
                    }
                    else if(row.Tipo_descarga === 'Expediente_completo_JRCI'){
                        return "Expediente completo JRCI";
                    }
                    else if(row.Tipo_descarga === "Otro_Documento") {
                        return "Otro Documento";
                    }
                    else if(row.Tipo_descarga === 'Manual'){
                        return row.Asunto;
                    }
                    else{
                        return row.Tipo_descarga;
                    }
                }
            },
            {"data": "Estado_General"},
            {"data": "Nota"},
            {"data":"Editarcomunicado"}
        ];
        if (enviara === 'Si') {
            columns.splice(4, 0, {
                "data": function(row) {
                    return row.Destinatarios;
                }
            });
        }
       // response.push();
        let listadoComunicados = $('#listado_comunicados_juntas').DataTable({
            scrollY: "30vh", //dos celdas
            orderCellsTop: true,
            fixedHeader: false,
            scrollX: true,
            scrollCollapse: true,
            destroy: true,
            data: response,
            paging: false,
            order: [[0, 'desc']],
            "columns": columns,
            createdRow: function(row, data, dataIndex) {
                //agregamos el id del comunicado dentro del primer td
                $(row).find('td').eq(0).attr('data-id_comunicado', data.Id_Comunicado);
                // Si "enviara" es "Si", significa que la columna Destinatarios fue agregada
                if (enviara === 'Si') {
                    // La columna de Destinatarios estaría en la posición 4 (después de F_comunicado)
                    $(row).find('td').eq(4).css('white-space', 'normal');
                }
            },  
            "language":{                
                "search": "Buscar",
                "lengthMenu": "Mostrar _MENU_ registros",
                "info": "Mostrando registros _START_ a _END_ de un total de _TOTAL_ registros",
                
                "paginate": {
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "first": "Primero",
                    "last": "Último"
                },
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "No se encontró información",
                "infoEmpty": "No se encontró información",
            }
        });
        autoAdjustColumns(listadoComunicados);
    }

    $("#listado_comunicados_juntas").on('click', "#CorrespondenciaNotificacion", async function() {
        let id = $(this);
        let token = $('input[name=_token]').val(); 
        let tipo_correspondencia = $(id).data('tipo_correspondencia');
        /* Aqui se programará algunas cosas de la ficha PBS 082 */

        // 1. motrar el botón de que tipo de guia es
        // 2. precargar el selector de documentos complementarios dependiendo de que correspondecia es.

        $("#tipo_guia").text('');
        $('#listado_documentos_ed tr[id^="fila_doc_"]').removeClass('d-none');
        $("#collapseGuia").removeClass('show');
        $("#doc_subir_guias").val('');
        switch (tipo_correspondencia) {
            case 'Afiliado':
                $("#tipo_guia").text('Afiliado');
                break;
            case 'Empleador':
                $("#tipo_guia").text('Empleador');
                break;
            case 'eps':
                $("#tipo_guia").text('EPS');
                break;
            case 'afp':
                $("#tipo_guia").text('AFP');
                break;
            case 'arl':
                $("#tipo_guia").text('ARL');
                break;
            case 'afp_conocimiento':
                $("#tipo_guia").text('Entidad conocimiento');
                break;
            case 'jrci':
                $("#tipo_guia").text('JRCI');
                break;
            case 'jnci':
                $("#tipo_guia").text('JNCI');
                break;
            default:
                break;
        }

        $('#listado_documentos_ed tr[id^="fila_doc_"]').not('#fila_doc_43').addClass('d-none');

        var datos_lista_tipos_documentos = {
            '_token': token,
            'evento': $("#newId_evento").val(),
            'servicio': $("#Id_servicio").val(),
            'parametro':"docs_complementarios",
            'tipo_correspondencia': 43,
        };
        
        $.ajax({
            type:'POST',
            url:'/selectoresJuntas',
            data: datos_lista_tipos_documentos,
            success:function(data) {
                $("#listado_tipos_documentos_guias").empty();
                $("#listado_tipos_documentos_guias").append('<option value="">Seleccione una Opción</option>');
                let tiposdoc = Object.keys(data);
                for (let i = 0; i < tiposdoc.length; i++) {
                    $('#listado_tipos_documentos_guias').append('<option value="'+data[tiposdoc[i]]["Nro_documento"]+'">'+data[tiposdoc[i]]["Nro_documento"]+' - '+data[tiposdoc[i]]["Nombre_documento"]+'</option>');
                }
            }
        });

    });

    $(document).on('click', "a[id^='generar_descarga_archivo_']", function(){
        var nombre_documento = this.getAttribute('asunto_comunicado');
        var idEvento = this.getAttribute('id_evento');
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

    $(document).on('click', "a[id^='verDocumento_']", function(){
        var verDocumento = $(this);
        var nombreDocumento = this.getAttribute('nombre_documento');
        var Nombre_junta_act = $('#Nombre_junta_act').val();
        var Id_junta_act = $('#Id_junta_act').val();
        var F_notifi_afiliado_act = $('#F_notifi_afiliado_act').val();
        var F_radicacion_contro_pri_cali_act = $('#F_radicacion_contro_pri_cali_act').val();
        var F_estructuracion_act = $('#F_estructuracion_act').val();
        var F_dictamen_act = $('#F_dictamen_act').val();
        var Id_Asignacion = this.getAttribute('id_asignacion');
        var Id_comunicado = this.getAttribute('id_comunicado');
        var num_identificacion = this.getAttribute('numero_identificacion');
        var Nradicado = this.getAttribute('numero_radicado');
        var TipoDescarga = this.getAttribute('tipo_descarga');
        var input_jrci_seleccionado_copia_editar = this.getAttribute('jrci_copia');
        var Reemplazado = this.getAttribute('reemplazado');
        var edit_copia_afiliado;
        var edit_copia_empleador;
        var edit_copia_eps;
        var edit_copia_afp;
        var edit_copia_arl;
        var edit_copia_jrci;
        var edit_copia_jnci;
        if(this.getAttribute('agregar_copia')){
            if(this.getAttribute('agregar_copia').includes("Afiliado")){
                edit_copia_afiliado = true;
            }
            if(this.getAttribute('agregar_copia').includes("Empleador")){
                edit_copia_empleador = true;
            }
            if(this.getAttribute('agregar_copia').includes("EPS")){
                edit_copia_eps = true;
            }
            if(this.getAttribute('agregar_copia').includes("AFP")){
                edit_copia_afp = true;
            }
            if(this.getAttribute('agregar_copia').includes("ARL")){
                edit_copia_arl = true;
            }
            if(this.getAttribute('agregar_copia').includes("JRCI")){
                edit_copia_jrci = true;
            }
            if(this.getAttribute('agregar_copia').includes("JNCI")){
                edit_copia_jnci = true;
            }
        }
        if(this.getAttribute('destinatario_principal') != "Otro"){
            datos_comunicado = {
                '_token': token,
                'cliente_comunicado2_act': this.getAttribute('cliente_comunicado'),
                'nombre_afiliado_comunicado2_act': this.getAttribute('nombre_afiliado'),
                'tipo_documento_comunicado2_act': this.getAttribute('tipo_documento'),
                'identificacion_comunicado2_act': this.getAttribute('numero_identificacion'),
                'id_evento_comunicado2_act': this.getAttribute('id_evento'),
                'tipo_documento_descarga_califi_editar': this.getAttribute('tipo_descarga'),
                'afiliado_comunicado_act': this.getAttribute('destinatario_principal'),
                'nombre_destinatario_act2': this.getAttribute('nombre_destinatario'),
                'nic_cc_act2': this.getAttribute('niccc_comunicado'),
                'direccion_destinatario_act2': this.getAttribute('direccion_destinatario'),
                'telefono_destinatario_act2': this.getAttribute('telefono_destinatario'),
                'email_destinatario_act2': this.getAttribute('email_destinatario'),
                'departamento_pdf': this.getAttribute('id_departamento'),
                'ciudad_pdf': this.getAttribute('id_municipio'),
                'asunto_act': this.getAttribute('asunto_comunicado'),
                'cuerpo_comunicado_act': this.getAttribute('cuerpo_comunicado'),
                'files': null,
                'anexos_act': this.getAttribute('anexos_comunicados'),
                'forma_envio_act': this.getAttribute('forma_envio_comunicado'),
                'elaboro2_act': this.getAttribute('elaboro_comunicado'),
                'reviso_act': this.getAttribute('reviso_comunicado'),
                'firmarcomunicado_editar': this.getAttribute('firmar_comunicado'),
                'ciudad_comunicado_act': this.getAttribute('ciudad_comunicaddo'),
                'Id_comunicado_act': this.getAttribute('id_comunicado'),
                'Id_evento_act': this.getAttribute('id_evento'),
                'Id_asignacion_act': this.getAttribute('id_asignacion'),
                'Id_procesos_act': this.getAttribute('id_proceso'),
                'fecha_comunicado2_act': this.getAttribute('fecha_comunicado'),
                'agregar_copia_editar':this.getAttribute('agregar_copia'),
                'radicado2_act': this.getAttribute('numero_radicado'),
                'edit_copia_afiliado': edit_copia_afiliado,
                'edit_copia_empleador':edit_copia_empleador,
                'edit_copia_eps':edit_copia_eps,
                'edit_copia_afp':edit_copia_afp,
                'edit_copia_arl':edit_copia_arl,
                'edit_copia_jrci':edit_copia_jrci,
                'edit_copia_jnci':edit_copia_jnci,
                'n_siniestro_proforma_editar': this.getAttribute('numero_siniestro') !== 'null' ? this.getAttribute('numero_siniestro') : null,
            };
        }
        else{
            datos_comunicado = {
                '_token': token,
                'cliente_comunicado2_act': this.getAttribute('cliente_comunicado'),
                'nombre_afiliado_comunicado2_act': this.getAttribute('nombre_afiliado'),
                'tipo_documento_comunicado2_act': this.getAttribute('tipo_documento'),
                'identificacion_comunicado2_act': this.getAttribute('numero_identificacion'),
                'id_evento_comunicado2_act': this.getAttribute('id_evento'),
                'tipo_documento_descarga_califi_editar': this.getAttribute('tipo_descarga'),
                'afiliado_comunicado_act': this.getAttribute('destinatario_principal'),
                'nombre_destinatario_act': this.getAttribute('nombre_destinatario'),
                'nic_cc_act': this.getAttribute('niccc_comunicado'),
                'nic_cc_editar': this.getAttribute('niccc_comunicado'),
                'direccion_destinatario_act': this.getAttribute('direccion_destinatario'),
                'telefono_destinatario_act': this.getAttribute('telefono_destinatario'),
                'email_destinatario_act': this.getAttribute('email_destinatario'),
                'nombre_destinatario_act2': this.getAttribute('nombre_destinatario'),
                'nic_cc_act2': this.getAttribute('niccc_comunicado'),
                'direccion_destinatario_act2': this.getAttribute('direccion_destinatario'),
                'telefono_destinatario_act2': this.getAttribute('telefono_destinatario'),
                'email_destinatario_act2': this.getAttribute('email_destinatario'),
                'departamento_pdf': this.getAttribute('id_departamento'),
                'ciudad_pdf': this.getAttribute('id_municipio'),
                'asunto_act': this.getAttribute('asunto_comunicado'),
                'cuerpo_comunicado_act': this.getAttribute('cuerpo_comunicado'),
                'files': null,
                'anexos_act': this.getAttribute('anexos_comunicados'),
                'forma_envio_act': this.getAttribute('forma_envio_comunicado'),
                'elaboro2_act': this.getAttribute('elaboro_comunicado'),
                'reviso_act': this.getAttribute('reviso_comunicado'),
                'firmarcomunicado_editar': this.getAttribute('firmar_comunicado'),
                'ciudad_comunicado_act': this.getAttribute('ciudad_comunicaddo'),
                'Id_comunicado_act': this.getAttribute('id_comunicado'),
                'Id_evento_act': this.getAttribute('id_evento'),
                'Id_asignacion_act': this.getAttribute('id_asignacion'),
                'Id_procesos_act': this.getAttribute('id_proceso'),
                'fecha_comunicado2_act': this.getAttribute('fecha_comunicado'),
                'agregar_copia_editar':this.getAttribute('agregar_copia'),
                'radicado2_act': this.getAttribute('numero_radicado'),
                'edit_copia_afiliado': edit_copia_afiliado,
                'edit_copia_empleador':edit_copia_empleador,
                'edit_copia_eps':edit_copia_eps,
                'edit_copia_afp':edit_copia_afp,
                'edit_copia_arl':edit_copia_arl,
                'edit_copia_jrci':edit_copia_jrci,
                'edit_copia_jnci':edit_copia_jnci,
                'n_siniestro_proforma_editar': this.getAttribute('numero_siniestro') !== 'null' ? this.getAttribute('numero_siniestro') : null,
            };
            }

        datos_comunicado.tipo_de_preforma_editar = this.getAttribute('tipo_descarga');
        datos_comunicado.Nombre_junta_act = Nombre_junta_act;
        datos_comunicado.Id_junta_act = Id_junta_act;
        datos_comunicado.F_notifi_afiliado_act = F_notifi_afiliado_act;
        datos_comunicado.F_radicacion_contro_pri_cali_act = F_radicacion_contro_pri_cali_act;
        datos_comunicado.F_estructuracion_act = F_estructuracion_act;
        datos_comunicado.F_dictamen_act = F_dictamen_act;
        datos_comunicado.input_jrci_seleccionado_copia_editar = input_jrci_seleccionado_copia_editar;
        datos_comunicado.id_jrci_del_input = Id_junta_act;
        if(this.getAttribute('destinatario_principal') === "Jrci"){
            datos_comunicado.afiliado_comunicado_act = "JRCI_comunicado";
        }
        else if(this.getAttribute('destinatario_principal') === "Jnci"){
            datos_comunicado.afiliado_comunicado_act = "JNCI_comunicado";
        }
        else if(this.getAttribute('destinatario_principal') === "Eps"){
            datos_comunicado.afiliado_comunicado_act = "EPS_comunicado";
        }
        else if(this.getAttribute('destinatario_principal') === "Afp"){
            datos_comunicado.afiliado_comunicado_act = "AFP_comunicado";
        }
        else if(this.getAttribute('destinatario_principal') === "Arl"){
            datos_comunicado.afiliado_comunicado_act = "ARL_comunicado";
        }
        if(parseInt(Reemplazado) == 1){
            var nombre_doc = this.getAttribute('nombre_documento');
            var idEvento = this.getAttribute('id_evento');
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
        else if(TipoDescarga == "Expediente_completo_JRCI"){
            var nombre_doc = this.getAttribute('nombre_documento');
            var idEvento = this.getAttribute('id_evento');
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
            if(TipoDescarga === "Otro_Documento"){
                $.ajax({    
                    type:'POST',
                    url:'/generarPdf',
                    data: datos_comunicado,
                    // xhrFields: {
                    //     responseType: 'blob' // Indica que la respuesta es un blob
                    // },
                    beforeSend:  function() {
                        verDocumento.addClass("descarga-deshabilitada");
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

                        // var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });

                        var indicativo = response.indicativo;
                        
                        // var nombre_pdf = "Comunicado_"+Id_comunicado+"_"+Nradicado+".pdf";
                        var nombre_pdf = "Comunicado_"+Id_comunicado+"_"+Nradicado+"_"+indicativo+".pdf";

                        // Crear un enlace de descarga similar al ejemplo anterior
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
                    complete: function(){
                        verDocumento.removeClass("descarga-deshabilitada");
                        if(nombreDocumento == null || nombreDocumento == "null"){
                            localStorage.setItem("#Generar_comunicados", true);
                            location.reload();
                        }
                    }         
                });
            }else{
                $.ajax({    
                    type:'POST',
                    url:'/DescargarProformasJuntas',
                    data: datos_comunicado,
                    // xhrFields: {
                    //     responseType: 'blob' // Indica que la respuesta es un blob
                    // },
                    beforeSend:  function() {
                        verDocumento.addClass("descarga-deshabilitada");
                    },
                    success: function (response, status, xhr) {
                        
                        // var blob = new Blob([response], { type: xhr.getResponseHeader('content-type') });
                        var indicativo = response.indicativo;

                        if(TipoDescarga === 'Oficio_Afiliado'){
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

                            // var nombre_documento = "JUN_OFICIO_AFILIADO_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+".pdf";
                            var nombre_documento = "JUN_OFICIO_AFILIADO_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+"_"+indicativo+".pdf";
                        }
                        else if(TipoDescarga === 'Oficio_Juntas_JRCI'){
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

                            // var nombre_documento = "JUN_OFICIO_JRCI_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+".pdf";
                            var nombre_documento = "JUN_OFICIO_JRCI_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+"_"+indicativo+".pdf";
                        }
                        else if(TipoDescarga === 'Remision_Expediente_JRCI'){
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

                            // var nombre_documento = "JUN_REM_EXPEDIENTE_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+".pdf";
                            var nombre_documento = "JUN_REM_EXPEDIENTE_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+"_"+indicativo+".pdf";
                        }
                        else if(TipoDescarga === 'Devolucion_Expediente_JRCI'){
                            // Obtener el contenido codificado en base64 del PDF desde la respuesta
                            var base64Word = response.word;
                
                            // Decodificar base64 en un array de bytes
                            var binaryString = atob(base64Word);
                            var len = binaryString.length;
                            var bytes = new Uint8Array(len);
                    
                            for (var i = 0; i < len; i++) {
                                bytes[i] = binaryString.charCodeAt(i);
                            }

                            var blob = new Blob([bytes], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });

                            // var nombre_documento = "JUN_DEV_EXPEDIENTE_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+".docx";
                            var nombre_documento = "JUN_DEV_EXPEDIENTE_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+"_"+indicativo+".docx";
                        }
                        else if(TipoDescarga === 'Solicitud_Dictamen_JRCI'){
                            // Obtener el contenido codificado en base64 del PDF desde la respuesta
                            var base64Word = response.word;
                
                            // Decodificar base64 en un array de bytes
                            var binaryString = atob(base64Word);
                            var len = binaryString.length;
                            var bytes = new Uint8Array(len);
                    
                            for (var i = 0; i < len; i++) {
                                bytes[i] = binaryString.charCodeAt(i);
                            }

                            var blob = new Blob([bytes], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });

                            // var nombre_documento = "JUN_SOL_DICTAMEN_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+".docx";
                            var nombre_documento = "JUN_SOL_DICTAMEN_"+Id_comunicado+"_"+Id_Asignacion+"_"+num_identificacion+"_"+indicativo+".docx";
                        }
                        // Crear un enlace de descarga similar al ejemplo anterior
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = nombre_documento;  // Reemplaza con el nombre deseado para el archivo PDF
                
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
                    complete: function(){
                        verDocumento.removeClass("descarga-deshabilitada");
                        if(nombreDocumento == null || nombreDocumento == "null"){
                            localStorage.setItem("#Generar_comunicados", true);
                            location.reload();
                        }
                    }        
                });
            }
        }
    }); 

    //Reemplazar Documento
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
        if(comunicado_reemplazar.Tipo_descarga === 'Manual' && extensionDocManual?.includes(extensionDocCargado)){
            var formData = new FormData($('form')[0]);
            formData.append('doc_de_reemplazo', archivo);
            formData.append('token', $('input[name=_token]').val());
            formData.append('id_comunicado', comunicado_reemplazar.Id_Comunicado);
            formData.append('tipo_descarga', comunicado_reemplazar.Tipo_descarga);
            formData.append('id_asignacion', comunicado_reemplazar.Id_Asignacion);
            formData.append('id_proceso', comunicado_reemplazar.Id_proceso);
            formData.append('id_evento', comunicado_reemplazar.ID_evento);
            formData.append('n_radicado', comunicado_reemplazar.N_radicado);
            formData.append('numero_identificacion', comunicado_reemplazar.N_identificacion)
            formData.append('modulo_creacion', 'calificacionJuntas');
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
            formData.append('numero_identificacion', comunicado_reemplazar.N_identificacion)
            formData.append('modulo_creacion', 'calificacionJuntas');
            formData.append('nombre_documento', comunicado_reemplazar.Nombre_documento);
            formData.append('asunto', comunicado_reemplazar.Asunto);
            formData.append('nombre_anterior', '');
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
    //Acción del boton de reemplazar
    let comunicado_reemplazar = null;
    $(document).on('click', "[id^='replace_file']", function(){
        $(".cargueundocumentoprimeromodal").addClass('d-none');
        $(".extensionInvalidaModal").addClass('d-none');
        $('#cargue_comunicados_modal').val('');
        data_comunicado = {
            '_token': $('input[name=_token]').val(),
            'id_comunicado': this.getAttribute('data-id_comunicado')
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

    //Asignar ruta del formulario de actualizar el comunicado
    $(document).on('mouseover',"input[id^='Pdf']", function(){
        
        if ($("[name='tipo_de_preforma_editar']").filter(":checked").val() != "Otro_Documento") {
            let url_editar_evento = $('#action_actualizar_comunicado').val();        
            $('form[name="formu_comunicado"]').attr("action", url_editar_evento);    
            $('form[name="formu_comunicado"]').removeAttr('id');
            
        } else {
            let url_editar_evento = $('#action_actualizar_comunicado_otro').val();        
            $('form[name="formu_comunicado"]').attr("action", url_editar_evento);    
            $('form[name="formu_comunicado"]').removeAttr('id');
            
        }

        // Deshabilitar todo para descargar el o los comunicados
        if (idRol == 7) {
            $(':input, select, a, button').prop('disabled', false);
        }
    });

    $(document).on('mouseover',"input[id^='Editar_comunicados']", function(){ 
        $('form[name="formu_comunicado"]').attr('id', 'form_actualizarComunicadoPcl');
        $('form[name="formu_comunicado"]').removeAttr('action');

    });

    //Accion agregar nota comunicado
    $("#listado_comunicados_juntas").on("click",'#editar_comunicado',function(){
        let radicado = $(this).data('radicado');
        let datos_comunicados_actualizar = {
            '_token' : token,
            'bandera': 'Actualizar',
            'radicado' : $(this).data('radicado'),
            'id_asignacion': $('#newId_asignacion').val(),
            'Nota': $("#nota_comunicado_" + radicado).val(),
            'Estado_general': $("#status_notificacion_" + radicado).val()
        };
        $.ajax({
            type:'POST',
            url:'/historialComunicadoJuntas',
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
    })
    
    var AlertaPdf;
    $(document).on('click', "input[id='Pdf']", function () {                       
        AlertaPdf = setTimeout(() => {            
            $('#mostrar_barra_descarga_pdf').removeClass('d-none');                        
            $('#Pdf').attr('disabled', true);
            $('#Editar_comunicados').attr('disabled', true);
        }, 1000);
       
        setTimeout(function() {
            clearTimeout(AlertaPdf);
            $('#mostrar_barra_descarga_pdf').addClass('d-none');                        
            $('#Pdf').attr('disabled', false);  
            $('#Editar_comunicados').attr('disabled', false);

            /* Validaciones para el rol Consulta cuando entra a la vista */
            if (idRol == 7) {
                // Desactivar todos los elementos excepto los especificados
                $(':input, select, a, button').not('#listado_roles_usuario, #his_servicios, #Hacciones, #botonVerEdicionEvento, #cargue_docs, #clicGuardado, #cargue_docs_modal_listado_docs, #abrir_agregar_seguimiento, #fecha_seguimiento, #causal_seguimiento, #descripcion_seguimiento, #Guardar_seguimientos, #botonFormulario2, .btn-danger, a[id^="EditarComunicado_"]').prop('disabled', true);
                $('#aumentarColAccionRealizar').addClass('d-none');
                $("#enlace_ed_evento").hover(function(){
                    $("input[name='_token']").prop('disabled', false);
                    $("#bandera_buscador_juntas").prop('disabled', false);
                    $("#newIdEvento").prop('disabled', false);
                    $("#newIdAsignacion").prop('disabled', false);
                    $("#newIdproceso").prop('disabled', false);
                    $("#newIdservicio").prop('disabled', false);
                });

                // Quitar el disabled al formulario oculto para permitirme ir al submodulo
                $("#llevar_servicio").hover(function(){
                    $("input[name='_token']").prop('disabled', false);
                    $("#Id_evento_juntas").prop('disabled', false);
                    $("#Id_asignacion_juntas").prop('disabled', false);
                    $("#Id_proceso_juntas").prop('disabled', false);
                    $("#Id_Servicio").prop('disabled', false);
                });
                // Deshabilitar el botón Actualizar y Activar el botón Pdf en los comunicados
                $("#Pdf").prop('disabled', false);
            }
        }, 5000);

    });

    // Creacion de la modal para la edicion del comunicado 
    $(document).on('click', "a[id^='EditarComunicado_']", function(){
        // validacion para numeros enteros en anexos modal agregar seguimiento
        var input = document.getElementById("anexos_editar");
        // Agrega un event listener para el evento "input"
        input.addEventListener("input", function() {
            var valor = input.value;
            if (Number.isInteger(Number(valor))) {
                //console.log("El valor es un número entero");
            } else {
                input.value = "";
                //console.log("El valor no es un número entero");
            }
        });
  
        var id_comunicado =  $(this).data("id_comunicado"); 
        var id_evento =  $(this).data("id_evento");     
        var id_asignacion =  $(this).data("id_asignacion");     
        var id_proceso =  $(this).data("id_proceso"); 
        var ciudad_comunicado =  $(this).data("ciudad_comunicaddo"); 
        var fecha_comunicado =  $(this).data("fecha_comunicado");
        var numero_radicado =  $(this).data("numero_radicado"); 
        var cliente_comunicado =  $(this).data("cliente_comunicado");
        var nombre_afiliado =  $(this).data("nombre_afiliado");
        var tipo_documento =  $(this).data("tipo_documento");         
        var numero_identificacion =  $(this).data("numero_identificacion");         
        var destinatario_principal =  $(this).data("destinatario_principal");
        var jrci_destinatario = $(this).data("jrci_destinatario");
        var nombre_destinatario =  $(this).data("nombre_destinatario"); 
        var niccc_comunicado =  $(this).data("niccc_comunicado");
        var direccion_destinatario =  $(this).data("direccion_destinatario");
        var telefono_destinatario =  $(this).data("telefono_destinatario");
        var email_destinatario =  $(this).data("email_destinatario");
        var id_departamento =  $(this).data("id_departamento");
        var nombre_departamento = $(this).data("nombre_departamento");
        var id_municipio =  $(this).data("id_municipio");
        var nombre_municipio = $(this).data("nombre_municipio");
        var asunto_comunicado =  $(this).data("asunto_comunicado");  
        var cuerpo_comunicado =  $(this).data("cuerpo_comunicado");  
        var anexos_comunicados =  $(this).data("anexos_comunicados"); 
        var forma_envio_comunicado =  $(this).data("forma_envio_comunicado"); 
        var nombre_envio_comunicado =  $(this).data("nombre_envio_comunicado");         
        var elaboro_comunicado =  $(this).data("elaboro_comunicado"); 
        var reviso_comunicado =  $(this).data("reviso_comunicado");     
        var revisonombre_comunicado =  $(this).data("revisonombre_comunicado"); 
        var agregar_copia =  $(this).data("agregar_copia");
        var jrci_copia = $(this).data("jrci_copia");
        var firmar_comunicado =  $(this).data("firmar_comunicado");
        var tipo_descarga = $(this).data("tipo_descarga");
        var N_siniestro = $(this).data("numero_siniestro");
        document.getElementById('ciudad_comunicado_editar').value=ciudad_comunicado;
        document.getElementById('Id_comunicado_act').value=id_comunicado;
        document.getElementById('Id_evento_act').value=id_evento;
        document.getElementById('Id_asignacion_act').value=id_asignacion;
        document.getElementById('Id_procesos_act').value=id_proceso;
        document.getElementById('fecha_comunicado_editar').value=fecha_comunicado;
        document.getElementById('fecha_comunicado2_editar').value=fecha_comunicado;
        document.getElementById('radicado_comunicado_editar').value=numero_radicado;
        document.getElementById('radicado2_comunicado_editar').value=numero_radicado;
        document.getElementById('cliente_comunicado_editar').value=cliente_comunicado;
        document.getElementById('cliente_comunicado2_editar').value=cliente_comunicado;
        document.getElementById('nombre_afiliado_comunicado_editar').value=nombre_afiliado;
        document.getElementById('nombre_afiliado_comunicado2_editar').value=nombre_afiliado;
        document.getElementById('tipo_documento_comunicado_editar').value=tipo_documento;
        document.getElementById('tipo_documento_comunicado2_editar').value=tipo_documento;
        document.getElementById('identificacion_comunicado_editar').value=numero_identificacion;
        document.getElementById('identificacion_comunicado2_editar').value=numero_identificacion;
        document.getElementById('id_evento_comunicado_editar').value=id_evento;
        document.getElementById('id_evento_comunicado2_editar').value=id_evento;  
        document.getElementById('n_siniestro_proforma_editar').value = N_siniestro; 
        let datos_destinatario_principal ={
            '_token':token,
            'destinatario_principal': destinatario_principal,
            'id_evento': id_evento,
            'id_asignacion': id_asignacion,
            'id_proceso': id_proceso,
        }
        $.ajax({
            url: '/modalComunicadoJuntas',
            method:'POST',
            data: datos_destinatario_principal,  
            success:function(data){
                var destino = data.destinatario_principal_comu;
                //console.log(destino);
                if(destino == 'Jrci'){
                    $('#jrci_comunicado_editar').prop('checked', true);                    
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;

                    if (jrci_destinatario != '') {
                        // Se evalúa si la jrci es un input o un selector
                        var numeroRegex = /^-?\d*\.?\d+$/;
                        if (numeroRegex.test(jrci_destinatario)) {
                            $("#div_select_jrci_editar").removeClass('d-none');
                            $(".jrci_califi_invalidez_comunicado_editar").select2({
                                placeholder:"Seleccione una opción",
                                allowClear:false
                            });
                            $.ajax({
                                type:'POST',
                                url:'/selectoresJuntas',
                                data: datos_lista_juntas_invalidez,
                                success:function(data) {
                                    $('#jrci_califi_invalidez_comunicado_editar').append('<option>Seleccione una opción</option>');
                                    let juntajrci = Object.keys(data);
                                    for (let i = 0; i < juntajrci.length; i++) {
                                        if (data[juntajrci[i]]['Id_Parametro'] == jrci_destinatario) {  
                                            $('#jrci_califi_invalidez_comunicado_editar').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'" selected>'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                                        }else{
                                            $('#jrci_califi_invalidez_comunicado_editar').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                                        }
                                    }
                                }
                            });

                            $('#jrci_califi_invalidez_comunicado_editar').change(function(){
                                var identificacion_comunicado_afiliado = $('#identificacion_comunicado_editar').val();
                                var datos_destinarioPrincipal ={
                                    '_token':token,
                                    'destinatarioPrincipal': "JRCI_comunicado",
                                    'identificacion_comunicado_afiliado':identificacion_comunicado_afiliado,
                                    'newId_evento': id_evento,
                                    'newId_asignacion': id_asignacion,
                                    'Id_proceso': id_proceso,
                                    'id_jrci': $(this).val()
                                };
                                $.ajax({
                                    type:'POST',
                                    url:'/captuarDestinatarioJuntas',
                                    data: datos_destinarioPrincipal,
                                    success: function(data){
                                        var Nombre_afiliado = $('#nombre_destinatario_editar');
                                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                                        document.querySelector("#nombre_destinatario_editar").disabled = true;
                                        document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_entidad;  
                                        var nitccafiliado = $('#nic_cc_editar');
                                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                                        document.querySelector("#nic_cc_editar").disabled = true;
                                        document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_entidad;        
                                        var direccionafiliado = $('#direccion_destinatario_editar');
                                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                                        document.querySelector("#direccion_destinatario_editar").disabled = true;
                                        document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion;        
                                        var telefonoafiliado = $('#telefono_destinatario_editar');
                                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                                        document.querySelector("#telefono_destinatario_editar").disabled = true;
                                        document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefonos;        
                                        var emailafiliado = $('#email_destinatario_editar');
                                        emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                                        document.querySelector("#email_destinatario_editar").disabled = true;
                                        document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Emails;
                                        var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                                        departamento_destinatario_editar.empty();
                                        departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                                        document.querySelector("#departamento_destinatario_editar").disabled = true;
                                        $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento);
                                        var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                                        ciudad_destinatario_editar.empty();
                                        ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
                                        $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipios);
                                    
                                        let datos_lista_forma_envios = {
                                            '_token':token,        
                                            'parametro':"lista_forma_envio"
                                        }
                                        $.ajax({
                                            type:'POST',
                                            url:'/selectoresModuloCalificacionPCL',
                                            data:datos_lista_forma_envios,
                                            success:function(data){
                                                //console.log(data);
                                                $('#forma_envio_editar').empty();
                                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                                let formaenviogenerarcomunicado = Object.keys(data);
                                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                                    }                
                                                }
                                            }
                                        });
                                        var nombre_usuario = $('#elaboro_editar');
                                        nombre_usuario.val(data.nombreusuario);
                                        var nombre_usuario2 = $('#elaboro2_editar');
                                        nombre_usuario2.val(data.nombreusuario);
                                        var reviso = $('#reviso_editar');
                                        reviso.empty();
                                        reviso.append('<option value="" selected>Seleccione una opción</option>');
                                        let revisolider = Object.keys(data.array_datos_lider);
                                        for (let i = 0; i < revisolider.length; i++) {
                                            reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                                        }
                                    }
                                });
                                $("#input_jrci_seleccionado_copia_editar").val($("#jrci_califi_invalidez_comunicado_editar option:selected").text());
                            });
                        }else{
                            $("#div_input_jrci_editar").removeClass('d-none');
                            $("#input_jrci_seleccionado_editar").val(jrci_destinatario);
                        }
                    }
                }
                else if(destino == 'Jnci'){
                    $('#jnci_comunicado_editar').prop('checked', true);                    
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;

                    if ($("#input_jrci_seleccionado_editar").val() != '') {
                        $("#div_input_jrci_editar").addClass("d-none");
                        $("#input_jrci_seleccionado_editar").val('');
                    } else {
                        $("#div_select_jrci_editar").addClass("d-none");
                        $("#jrci_califi_invalidez_comunicado_editar").val('');
                    }
                }
                else if (destino == 'Afiliado') {
                    $('#afiliado_comunicado_editar').prop('checked', true);                    
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;

                    if ($("#input_jrci_seleccionado_editar").val() != '') {
                        $("#div_input_jrci_editar").addClass("d-none");
                        $("#input_jrci_seleccionado_editar").val('');
                    } else {
                        $("#div_select_jrci_editar").addClass("d-none");
                        $("#jrci_califi_invalidez_comunicado_editar").val('');
                    }
                }
                else if(destino == 'Empleador'){
                    $('#empresa_comunicado_editar').prop('checked', true);
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;

                    if ($("#input_jrci_seleccionado_editar").val() != '') {
                        $("#div_input_jrci_editar").addClass("d-none");
                        $("#input_jrci_seleccionado_editar").val('');
                    } else {
                        $("#div_select_jrci_editar").addClass("d-none");
                        $("#jrci_califi_invalidez_comunicado_editar").val('');
                    }
                }
                else if(destino == 'Eps'){
                    $('#eps_comunicado_editar').prop('checked', true);
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;

                    if ($("#input_jrci_seleccionado_editar").val() != '') {
                        $("#div_input_jrci_editar").addClass("d-none");
                        $("#input_jrci_seleccionado_editar").val('');
                    } else {
                        $("#div_select_jrci_editar").addClass("d-none");
                        $("#jrci_califi_invalidez_comunicado_editar").val('');
                    }
                }
                else if(destino == 'Afp'){
                    $('#afp_comunicado_editar').prop('checked', true);
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;

                    if ($("#input_jrci_seleccionado_editar").val() != '') {
                        $("#div_input_jrci_editar").addClass("d-none");
                        $("#input_jrci_seleccionado_editar").val('');
                    } else {
                        $("#div_select_jrci_editar").addClass("d-none");
                        $("#jrci_califi_invalidez_comunicado_editar").val('');
                    }
                }
                else if(destino == 'Arl'){
                    $('#arl_comunicado_editar').prop('checked', true);
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;

                    if ($("#input_jrci_seleccionado_editar").val() != '') {
                        $("#div_input_jrci_editar").addClass("d-none");
                        $("#input_jrci_seleccionado_editar").val('');
                    } else {
                        $("#div_select_jrci_editar").addClass("d-none");
                        $("#jrci_califi_invalidez_comunicado_editar").val('');
                    }
                }
                else if(destino == 'Otro'){
                    $('#Otro_editar').prop('checked', true);
                    document.querySelector("#nombre_destinatario_editar").disabled = false;
                    document.querySelector("#nic_cc_editar").disabled = false;
                    document.querySelector("#direccion_destinatario_editar").disabled = false;
                    document.querySelector("#telefono_destinatario_editar").disabled = false;
                    document.querySelector("#email_destinatario_editar").disabled = false;
                    document.querySelector("#departamento_destinatario_editar").disabled = false;
                    document.querySelector("#ciudad_destinatario_editar").disabled = false;

                    if ($("#input_jrci_seleccionado_editar").val() != '') {
                        $("#div_input_jrci_editar").addClass("d-none");
                        $("#input_jrci_seleccionado_editar").val('');
                    } else {
                        $("#div_select_jrci_editar").addClass("d-none");
                        $("#jrci_califi_invalidez_comunicado_editar").val('');
                    }
                }

                var reviso_editar = $('#reviso_editar');
                reviso_editar.empty();
                reviso_editar.append('<option value="'+reviso_comunicado+'" selected>'+revisonombre_comunicado+'</option>');
                let NobreLider = $('select[name=reviso_act]').val();
                let revisolider = Object.keys(data.array_datos_lider);
                for (let i = 0; i < revisolider.length; i++) {
                    if (data.array_datos_lider[i]['id'] != NobreLider) {                
                        reviso_editar.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');            
                    }
                }
            }

        });


        if (tipo_descarga == "Oficio_Afiliado") {
            $("#oficio_afiliado_editar").prop("checked", true);
            $("#oficio_juntas_jrci_editar").prop("checked", false);
            $("#remision_expediente_jrci_editar").prop("checked", false);
            $("#devol_expediente_jrci_editar").prop("checked", false);
            $("#solicitud_dictamen_jrci_editar").prop("checked", false);
            $("#otro_documento_editar").prop("checked", false);

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").removeClass('d-none');
            $("#rellenar_asunto_editar").html('Para mostrar todo el asunto dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Asunto.');
            $("#mensaje_cuerpo_editar").removeClass('d-none');
            $("#rellenar_cuerpo_editar").html('');
            $("#rellenar_cuerpo_editar").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO	
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', false);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', false);
            //  botones REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

        } else if(tipo_descarga == "Oficio_Juntas_JRCI") {
            $("#oficio_afiliado_editar").prop("checked", false);
            $("#oficio_juntas_jrci_editar").prop("checked", true);
            $("#remision_expediente_jrci_editar").prop("checked", false);
            $("#devol_expediente_jrci_editar").prop("checked", false);
            $("#solicitud_dictamen_jrci_editar").prop("checked", false);
            $("#otro_documento_editar").prop("checked", false);

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").addClass('d-none');
            $("#rellenar_cuerpo_editar").html('');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO	
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
            //  botones REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

        } else if(tipo_descarga == "Remision_Expediente_JRCI"){
            $("#oficio_afiliado_editar").prop("checked", false);
            $("#oficio_juntas_jrci_editar").prop("checked", false);
            $("#remision_expediente_jrci_editar").prop("checked", true);
            $("#devol_expediente_jrci_editar").prop("checked", false);
            $("#solicitud_dictamen_jrci_editar").prop("checked", false);
            $("#otro_documento_editar").prop("checked", false);

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").removeClass('d-none');
            $("#rellenar_cuerpo_editar").html('');
            $("#rellenar_cuerpo_editar").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir las etiquetas N° Orden pago, Fecha Notificación al Afiliado, Fecha Radicación Controversia Primera Calificación, Tipo Documento Afiliado, Documento Afiliado, Nombre Afiliado, Fecha Estructuración, Tipo de Evento, Nombres CIE-10, Tipo Controversia Primera Calificación, Dirección Afiliado, Teléfono Afiliado, dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO	
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
            //  botones REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', false);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', false);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', false);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', false);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', false);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', false);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', false);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

        } else if(tipo_descarga == "Devolucion_Expediente_JRCI"){
            $("#oficio_afiliado_editar").prop("checked", false);
            $("#oficio_juntas_jrci_editar").prop("checked", false);
            $("#remision_expediente_jrci_editar").prop("checked", false);
            $("#devol_expediente_jrci_editar").prop("checked", true);
            $("#solicitud_dictamen_jrci_editar").prop("checked", false);
            $("#otro_documento_editar").prop("checked", false);

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").removeClass('d-none');
            $("#rellenar_cuerpo_editar").html('');
            $("#rellenar_cuerpo_editar").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO	
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', false);
            //  botones REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', false);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#Pdf").val("Word");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('Word');

        }else if(tipo_descarga == "Solicitud_Dictamen_JRCI"){
            $("#oficio_afiliado_editar").prop("checked", false);
            $("#oficio_juntas_jrci_editar").prop("checked", false);
            $("#remision_expediente_jrci_editar").prop("checked", false);
            $("#devol_expediente_jrci_editar").prop("checked", false);
            $("#solicitud_dictamen_jrci_editar").prop("checked", true);
            $("#otro_documento_editar").prop("checked", false);

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").removeClass('d-none');
            $("#rellenar_cuerpo_editar").html('');
            $("#rellenar_cuerpo_editar").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir las etiquetas Nombre Afiliado, Correo Solicitud Información, dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', false);

            $("#Pdf").val("Word");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('Word');

        }else{
            $("#oficio_afiliado_editar").prop("checked", false);
            $("#oficio_juntas_jrci_editar").prop("checked", false);
            $("#remision_expediente_jrci_editar").prop("checked", false);
            $("#devol_expediente_jrci_editar").prop("checked", false);
            $("#solicitud_dictamen_jrci_editar").prop("checked", false);
            $("#otro_documento_editar").prop("checked", true);

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").addClass('d-none');
            $("#rellenar_cuerpo_editar").html('');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');
            
        }


        document.getElementById('nombre_destinatario_editar').value=nombre_destinatario;        
        document.getElementById('nombre_destinatario_editar2').value=nombre_destinatario;  
        document.getElementById('nic_cc_editar').value=niccc_comunicado;        
        document.getElementById('nic_cc_editar2').value=niccc_comunicado;        
        document.getElementById('direccion_destinatario_editar').value=direccion_destinatario;        
        document.getElementById('direccion_destinatario_editar2').value=direccion_destinatario;        
        document.getElementById('telefono_destinatario_editar').value=telefono_destinatario;        
        document.getElementById('telefono_destinatario_editar2').value=telefono_destinatario;        
        document.getElementById('email_destinatario_editar').value=email_destinatario;
        document.getElementById('email_destinatario_editar2').value=email_destinatario;
        var departamento_destinatario_editar = $('#departamento_destinatario_editar');
        departamento_destinatario_editar.empty();
        departamento_destinatario_editar.append('<option value="'+id_departamento+'" selected>'+nombre_departamento+'</option>');        
        var departamento_destinatario = $('#departamento_destinatario_editar').val();
        $("#departamento_pdf").val(departamento_destinatario);
        var ciudad_destinatario_editar = $('#ciudad_destinatario_editar');
        ciudad_destinatario_editar.empty();
        ciudad_destinatario_editar.append('<option value="'+id_municipio+'" selected>'+nombre_municipio+'</option>');
        var ciudad_destinatario = $('#ciudad_destinatario_editar').val();
        $("#ciudad_pdf").val(ciudad_destinatario);
        document.getElementById('asunto_editar').value=asunto_comunicado;
        // document.getElementById('cuerpo_comunicado_editar').value=cuerpo_comunicado;
        $("#cuerpo_comunicado_editar").summernote('code', cuerpo_comunicado);
        document.getElementById('anexos_editar').value=anexos_comunicados;
        if (firmar_comunicado == 'firmar comunicado') {
            $('#firmarcomunicado_editar').prop('checked', true);  
        }
        //Valida si tiene alguna copia
        $("input[id^='edit_copia_']").each(function() {
            const checkboxValue = $(this).val();
            if (agregar_copia.includes(checkboxValue)) {
                $(this).prop('checked', true);
            }else{
                $(this).prop('checked', false);
            }
        });

        // validación para mostrar el selector el input de la jrci en las copias interesadas
        if($("#edit_copia_jrci").prop('checked')){
            if (jrci_copia != '') {
                // Se evalúa si la jrci es un input o un selector
                var numeroRegex = /^-?\d*\.?\d+$/;
                if (numeroRegex.test(jrci_copia)) {
                    $("#div_select_jrci_copia_editar").removeClass('d-none');
                    $(".jrci_califi_invalidez_copia_editar").select2({
                        placeholder:"Seleccione una opción",
                        allowClear:false
                    });
                    $.ajax({
                        type:'POST',
                        url:'/selectoresJuntas',
                        data: datos_lista_juntas_invalidez,
                        success:function(data) {
                            $('#jrci_califi_invalidez_copia_editar').append('<option>Seleccione una opción</option>');
                            let juntajrci = Object.keys(data);
                            for (let i = 0; i < juntajrci.length; i++) {
                                if (data[juntajrci[i]]['Id_Parametro'] == jrci_copia) {  
                                    $('#jrci_califi_invalidez_copia_editar').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'" selected>'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                                }else{
                                    $('#jrci_califi_invalidez_copia_editar').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                                }
                            }
                        }
                    });
                }else{
                    $("#div_input_jrci_copia_editar").removeClass('d-none');
                    $("#input_jrci_seleccionado_copia_editar").val(jrci_copia);
                }
            }
        }
        
        var forma_envio_editar = $('#forma_envio_editar');
        forma_envio_editar.empty();
        forma_envio_editar.append('<option value="'+forma_envio_comunicado+'" selected>'+nombre_envio_comunicado+'</option>');
        // Listado de forma de editar de generar comunicado
        let datos_lista_forma_envios = {
            '_token':token,        
            'parametro':"lista_forma_envio"
        }
        $.ajax({
            type:'POST',
            url:'/selectoresModuloCalificacionPCL',
            data:datos_lista_forma_envios,
            success:function(data){
                //console.log(data);
                //$('#forma_envio_editar').empty();
                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                let formaenviogenerarcomunicado = Object.keys(data);
                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                    }                
                }
            }
        });
        document.getElementById('elaboro_editar').value=elaboro_comunicado;
        document.getElementById('elaboro2_editar').value=elaboro_comunicado;

        $('input[type="radio"]').change(function(){
            var destinarioPrincipal = $(this).val();    
            var identificacion_comunicado_afiliado = $('#identificacion_comunicado_editar').val();
            $("#input_jrci_seleccionado_copia_editar").val('');
            var datos_destinarioPrincipal ={
                '_token':token,
                'destinatarioPrincipal': destinarioPrincipal,
                'identificacion_comunicado_afiliado':identificacion_comunicado_afiliado,
                'newId_evento': id_evento,
                'newId_asignacion': id_asignacion,
                'Id_proceso': id_proceso,
                'id_jrci': jrci_seleccionado
            }
    
            $.ajax({
                type:'POST',
                url:'/captuarDestinatarioJuntas',
                data: datos_destinarioPrincipal,
                success: function(data){
                    if(data.destinatarioPrincipal == 'JRCI_comunicado'){
                        var jrci_seleccionado= $("#jrci_califi_invalidez").val();
                         // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                        // Si existe, debe mostrar un input con el nombre de la jrci seleccioanda y cargar los datos del destinatario con la
                        // info del jrci.
                        if (jrci_seleccionado > 0) {
                            $("#div_input_jrci_editar").removeClass('d-none');
                            $("#div_select_jrci_editar").addClass('d-none');
                            $("#input_jrci_seleccionado_editar").val($("#jrci_califi_invalidez option:selected").text());

                            var Nombre_afiliado = $('#nombre_destinatario_editar');
                            Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                            document.querySelector("#nombre_destinatario_editar").disabled = true;
                            document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_entidad;  
                            var nitccafiliado = $('#nic_cc_editar');
                            nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                            document.querySelector("#nic_cc_editar").disabled = true;
                            document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_entidad;        
                            var direccionafiliado = $('#direccion_destinatario_editar');
                            direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                            document.querySelector("#direccion_destinatario_editar").disabled = true;
                            document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion;        
                            var telefonoafiliado = $('#telefono_destinatario_editar');
                            telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                            document.querySelector("#telefono_destinatario_editar").disabled = true;
                            document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefonos;        
                            var emailafiliado = $('#email_destinatario_editar');
                            emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                            document.querySelector("#email_destinatario_editar").disabled = true;
                            document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Emails;
                            var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                            departamento_destinatario_editar.empty();
                            departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                            document.querySelector("#departamento_destinatario_editar").disabled = true;
                            $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento);
                            var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                            ciudad_destinatario_editar.empty();
                            ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                            document.querySelector("#ciudad_destinatario_editar").disabled = true;
                            $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipios);
                          
                            let datos_lista_forma_envios = {
                                '_token':token,        
                                'parametro':"lista_forma_envio"
                            }
                            $.ajax({
                                type:'POST',
                                url:'/selectoresModuloCalificacionPCL',
                                data:datos_lista_forma_envios,
                                success:function(data){
                                    //console.log(data);
                                    $('#forma_envio_editar').empty();
                                    forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                    let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                    let formaenviogenerarcomunicado = Object.keys(data);
                                    for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                        if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                            $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                        }                
                                    }
                                }
                            });

                            // Seleccción de la forma de envío acorde a la selección de la JRCI
                            setTimeout(() => {
                                if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                    $('#forma_envio_editar').val('46').trigger('change.select2');
                                }else{
                                    $('#forma_envio_editar').val('47').trigger('change.select2');
                                }
                            }, 400);

                            var nombre_usuario = $('#elaboro_editar');
                            nombre_usuario.val(data.nombreusuario);
                            var nombre_usuario2 = $('#elaboro2_editar');
                            nombre_usuario2.val(data.nombreusuario);
                            // var reviso = $('#reviso_editar');
                            // reviso.empty();
                            // reviso.append('<option value="" selected>Seleccione una opción</option>');
                            // let revisolider = Object.keys(data.array_datos_lider);
                            // for (let i = 0; i < revisolider.length; i++) {
                            //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                            // }
                        }
                        // si no, el mismo selector de jrci
                        else{
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#div_select_jrci_editar").removeClass('d-none');

                            $.ajax({
                                type:'POST',
                                url:'/selectoresJuntas',
                                data: datos_lista_juntas_invalidez,
                                success:function(data) {
                                    $('#jrci_califi_invalidez_comunicado_editar').empty();
                                    $('#jrci_califi_invalidez_comunicado_editar').append('<option>Seleccione una opción</option>');
                                    let juntajrci = Object.keys(data);
                                    for (let i = 0; i < juntajrci.length; i++) {
                                        $('#jrci_califi_invalidez_comunicado_editar').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                                    }
                                    
                                    // Selección automática jrci
                                    $('#jrci_califi_invalidez_comunicado_editar').prop("selectedIndex", 1);
                                    var seleccion_automatica_jrci_editar = $('#jrci_califi_invalidez_comunicado_editar').val();

                                    var identificacion_comunicado_afiliado = $('#identificacion_comunicado_editar').val();
                                    var datos_destinarioPrincipal ={
                                        '_token':token,
                                        'destinatarioPrincipal': "JRCI_comunicado",
                                        'identificacion_comunicado_afiliado':identificacion_comunicado_afiliado,
                                        'newId_evento': id_evento,
                                        'newId_asignacion': id_asignacion,
                                        'Id_proceso': id_proceso,
                                        'id_jrci': seleccion_automatica_jrci_editar
                                    };
                                    $.ajax({
                                        type:'POST',
                                        url:'/captuarDestinatarioJuntas',
                                        data: datos_destinarioPrincipal,
                                        success: function(data){
                                            var Nombre_afiliado = $('#nombre_destinatario_editar');
                                            Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                                            document.querySelector("#nombre_destinatario_editar").disabled = true;
                                            document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_entidad;  
                                            var nitccafiliado = $('#nic_cc_editar');
                                            nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                                            document.querySelector("#nic_cc_editar").disabled = true;
                                            document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_entidad;        
                                            var direccionafiliado = $('#direccion_destinatario_editar');
                                            direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                                            document.querySelector("#direccion_destinatario_editar").disabled = true;
                                            document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion;        
                                            var telefonoafiliado = $('#telefono_destinatario_editar');
                                            telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                                            document.querySelector("#telefono_destinatario_editar").disabled = true;
                                            document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefonos;        
                                            var emailafiliado = $('#email_destinatario_editar');
                                            emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                                            document.querySelector("#email_destinatario_editar").disabled = true;
                                            document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Emails;
                                            var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                                            departamento_destinatario_editar.empty();
                                            departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                                            document.querySelector("#departamento_destinatario_editar").disabled = true;
                                            $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento);
                                            var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                                            ciudad_destinatario_editar.empty();
                                            ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                                            document.querySelector("#ciudad_destinatario_editar").disabled = true;
                                            $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipios);
                                        
                                            let datos_lista_forma_envios = {
                                                '_token':token,        
                                                'parametro':"lista_forma_envio"
                                            }
                                            $.ajax({
                                                type:'POST',
                                                url:'/selectoresModuloCalificacionPCL',
                                                data:datos_lista_forma_envios,
                                                success:function(data){
                                                    //console.log(data);
                                                    $('#forma_envio_editar').empty();
                                                    forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                                    let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                                    let formaenviogenerarcomunicado = Object.keys(data);
                                                    for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                                        if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                                            $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                                        }                
                                                    }
                                                }
                                            });

                                            // Seleccción de la forma de envío acorde a la selección de la JRCI
                                            setTimeout(() => {
                                                if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                                    $('#forma_envio_editar').val('46').trigger('change.select2');
                                                }else{
                                                    $('#forma_envio_editar').val('47').trigger('change.select2');
                                                }
                                            }, 400);

                                            var nombre_usuario = $('#elaboro_editar');
                                            nombre_usuario.val(data.nombreusuario);
                                            var nombre_usuario2 = $('#elaboro2_editar');
                                            nombre_usuario2.val(data.nombreusuario);
                                            // var reviso = $('#reviso_editar');
                                            // reviso.empty();
                                            // reviso.append('<option value="" selected>Seleccione una opción</option>');
                                            // let revisolider = Object.keys(data.array_datos_lider);
                                            // for (let i = 0; i < revisolider.length; i++) {
                                            //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                                            // }
                                            $("#input_jrci_seleccionado_copia_editar").val($("#jrci_califi_invalidez_comunicado_editar option:selected").text());
                                        }
                                    });

                                }
                            });

                            $(".jrci_califi_invalidez_comunicado_editar").select2({
                                placeholder:"Seleccione una opción",
                                allowClear:false
                            });
                            $('#jrci_califi_invalidez_comunicado_editar').change(function(){
                                var identificacion_comunicado_afiliado = $('#identificacion_comunicado_editar').val();
                                var datos_destinarioPrincipal ={
                                    '_token':token,
                                    'destinatarioPrincipal': "JRCI_comunicado",
                                    'identificacion_comunicado_afiliado':identificacion_comunicado_afiliado,
                                    'newId_evento': id_evento,
                                    'newId_asignacion': id_asignacion,
                                    'Id_proceso': id_proceso,
                                    'id_jrci': $(this).val()
                                };
                                $.ajax({
                                    type:'POST',
                                    url:'/captuarDestinatarioJuntas',
                                    data: datos_destinarioPrincipal,
                                    success: function(data){
                                        var Nombre_afiliado = $('#nombre_destinatario_editar');
                                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                                        document.querySelector("#nombre_destinatario_editar").disabled = true;
                                        document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_entidad;  
                                        var nitccafiliado = $('#nic_cc_editar');
                                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                                        document.querySelector("#nic_cc_editar").disabled = true;
                                        document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_entidad;        
                                        var direccionafiliado = $('#direccion_destinatario_editar');
                                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                                        document.querySelector("#direccion_destinatario_editar").disabled = true;
                                        document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion;        
                                        var telefonoafiliado = $('#telefono_destinatario_editar');
                                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                                        document.querySelector("#telefono_destinatario_editar").disabled = true;
                                        document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefonos;        
                                        var emailafiliado = $('#email_destinatario_editar');
                                        emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                                        document.querySelector("#email_destinatario_editar").disabled = true;
                                        document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Emails;
                                        var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                                        departamento_destinatario_editar.empty();
                                        departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                                        document.querySelector("#departamento_destinatario_editar").disabled = true;
                                        $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento);
                                        var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                                        ciudad_destinatario_editar.empty();
                                        ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
                                        $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipios);
                                    
                                        let datos_lista_forma_envios = {
                                            '_token':token,        
                                            'parametro':"lista_forma_envio"
                                        }
                                        $.ajax({
                                            type:'POST',
                                            url:'/selectoresModuloCalificacionPCL',
                                            data:datos_lista_forma_envios,
                                            success:function(data){
                                                //console.log(data);
                                                $('#forma_envio_editar').empty();
                                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                                let formaenviogenerarcomunicado = Object.keys(data);
                                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                                    }                
                                                }
                                            }
                                        });

                                        // Seleccción de la forma de envío acorde a la selección de la JRCI
                                        setTimeout(() => {
                                            if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                                $('#forma_envio_editar').val('46').trigger('change.select2');
                                            }else{
                                                $('#forma_envio_editar').val('47').trigger('change.select2');
                                            }
                                        }, 400);

                                        var nombre_usuario = $('#elaboro_editar');
                                        nombre_usuario.val(data.nombreusuario);
                                        var nombre_usuario2 = $('#elaboro2_editar');
                                        nombre_usuario2.val(data.nombreusuario);
                                        // var reviso = $('#reviso_editar');
                                        // reviso.empty();
                                        // reviso.append('<option value="" selected>Seleccione una opción</option>');
                                        // let revisolider = Object.keys(data.array_datos_lider);
                                        // for (let i = 0; i < revisolider.length; i++) {
                                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                                        // }
                                    }
                                });
                            });
                        }
                    }else if(data.destinatarioPrincipal == 'JNCI_comunicado'){
                        var Nombre_afiliado = $('#nombre_destinatario_editar');
                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                        document.querySelector("#nombre_destinatario_editar").disabled = true;
                        document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_entidad;  
                        var nitccafiliado = $('#nic_cc_editar');
                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                        document.querySelector("#nic_cc_editar").disabled = true;
                        document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_entidad;        
                        var direccionafiliado = $('#direccion_destinatario_editar');
                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                        document.querySelector("#direccion_destinatario_editar").disabled = true;
                        document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion;        
                        var telefonoafiliado = $('#telefono_destinatario_editar');
                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                        document.querySelector("#telefono_destinatario_editar").disabled = true;
                        document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefonos;        
                        var emailafiliado = $('#email_destinatario_editar');
                        emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                        document.querySelector("#email_destinatario_editar").disabled = true;
                        document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Emails;
                        var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                        departamento_destinatario_editar.empty();
                        departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                        document.querySelector("#departamento_destinatario_editar").disabled = true;
                        $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento);
                        var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                        ciudad_destinatario_editar.empty();
                        ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
                        $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipios);
                        
                        let datos_lista_forma_envios = {
                            '_token':token,        
                            'parametro':"lista_forma_envio"
                        }
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data:datos_lista_forma_envios,
                            success:function(data){
                                //console.log(data);
                                $('#forma_envio_editar').empty();
                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            }
                        });

                        // Seleccción de la forma de envío acorde a la selección de la JNCI
                        setTimeout(() => {
                            if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                $('#forma_envio_editar').val('46').trigger('change.select2');
                            }else{
                                $('#forma_envio_editar').val('47').trigger('change.select2');
                            }
                        }, 400);

                        var nombre_usuario = $('#elaboro_editar');
                        nombre_usuario.val(data.nombreusuario);
                        var nombre_usuario2 = $('#elaboro2_editar');
                        nombre_usuario2.val(data.nombreusuario);
                        // var reviso = $('#reviso_editar');
                        // reviso.empty();
                        // reviso.append('<option value="" selected>Seleccione una opción</option>');
                        // let revisolider = Object.keys(data.array_datos_lider);
                        // for (let i = 0; i < revisolider.length; i++) {
                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        // }

                        // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                        var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                        // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                        if (jrci_seleccionado > 0) {
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#input_jrci_seleccionado_editar").val('');
                        } 
                        // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                        else {
                            $("#div_select_jrci_editar").addClass('d-none');
                            $('#jrci_califi_invalidez_comunicado_editar').empty();  
                        }
                    }else if (data.destinatarioPrincipal == 'Afiliado') {
                        //console.log(data.hitorialAgregarComunicado);
                        var Nombre_afiliado = $('#nombre_destinatario_editar');
                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_afiliado);
                        document.querySelector("#nombre_destinatario_editar").disabled = true;
                        document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_afiliado;  
                        var nitccafiliado = $('#nic_cc_editar');
                        nitccafiliado.val(data.array_datos_destinatarios[0].Nro_identificacion);
                        document.querySelector("#nic_cc_editar").disabled = true;
                        document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nro_identificacion;        
                        var direccionafiliado = $('#direccion_destinatario_editar');
                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion_afiliado);
                        document.querySelector("#direccion_destinatario_editar").disabled = true;
                        document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion_afiliado;        
                        var telefonoafiliado = $('#telefono_destinatario_editar');
                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefono_contacto);
                        document.querySelector("#telefono_destinatario_editar").disabled = true;
                        document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefono_contacto;        
                        var emailafiliado = $('#email_destinatario_editar');
                        emailafiliado.val(data.array_datos_destinatarios[0].Email_afiliado);
                        document.querySelector("#email_destinatario_editar").disabled = true;
                        document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Email_afiliado;
                        var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                        departamento_destinatario_editar.empty();
                        departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento_afiliado+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento_afiliado+'</option>');
                        document.querySelector("#departamento_destinatario_editar").disabled = true;
                        $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento_afiliado);
                        var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                        ciudad_destinatario_editar.empty();
                        ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipio_afiliado+'">'+data.array_datos_destinatarios[0].Nombre_municipio_afiliado+'</option>')
                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
                        $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipio_afiliado);
                        // Listado de forma de editar de generar comunicado
                        let datos_lista_forma_envios = {
                            '_token':token,        
                            'parametro':"lista_forma_envio"
                        }
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data:datos_lista_forma_envios,
                            success:function(data){
                                //console.log(data);
                                $('#forma_envio_editar').empty();
                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            }
                        });

                        // Seleccción de la forma de envío acorde a la selección del afiliado
                        setTimeout(() => {
                            if (data.info_medio_noti[0].Medio_notificacion == "Físico") {
                                $('#forma_envio_editar').val('46').trigger('change.select2');
                            }else{
                                $('#forma_envio_editar').val('47').trigger('change.select2');
                            }
                        }, 400);

                        var nombre_usuario = $('#elaboro_editar');
                        nombre_usuario.val(data.nombreusuario);
                        var nombre_usuario2 = $('#elaboro2_editar');
                        nombre_usuario2.val(data.nombreusuario);
                        // var reviso = $('#reviso_editar');
                        // reviso.empty();
                        // reviso.append('<option value="" selected>Seleccione una opción</option>');
                        // let revisolider = Object.keys(data.array_datos_lider);
                        // for (let i = 0; i < revisolider.length; i++) {
                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        // }
                        // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                        var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                        // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                        if (jrci_seleccionado > 0) {
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#input_jrci_seleccionado_editar").val('');
                        } 
                        // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                        else {
                            $("#div_select_jrci_editar").addClass('d-none');
                            $('#jrci_califi_invalidez_comunicado_editar').empty();  
                        }
                    }else if(data.destinatarioPrincipal == 'Empleador'){
                        //console.log(data.array_datos_destinatarios);
                        var Nombre_afiliado = $('#nombre_destinatario_editar');
                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_empresa);
                        document.querySelector("#nombre_destinatario_editar").disabled = true;
                        document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_empresa;  
                        var nitccafiliado = $('#nic_cc_editar');
                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_o_cc);
                        document.querySelector("#nic_cc_editar").disabled = true;
                        document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_o_cc;        
                        var direccionafiliado = $('#direccion_destinatario_editar');
                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion_empresa);
                        document.querySelector("#direccion_destinatario_editar").disabled = true;
                        document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion_empresa;        
                        var telefonoafiliado = $('#telefono_destinatario_editar');
                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefono_empresa);
                        document.querySelector("#telefono_destinatario_editar").disabled = true;
                        document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefono_empresa;        
                        var emailafiliado = $('#email_destinatario_editar');
                        emailafiliado.val(data.array_datos_destinatarios[0].Email_empresa);
                        document.querySelector("#email_destinatario_editar").disabled = true;
                        document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Email_empresa;
                        var departamentoafiliado = $('#departamento_destinatario_editar');
                        departamentoafiliado.empty();
                        departamentoafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento_empresa+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento_empresa+'</option>');
                        document.querySelector("#departamento_destinatario_editar").disabled = true;
                        $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento_empresa);
                        var ciudadafiliado =$('#ciudad_destinatario_editar');
                        ciudadafiliado.empty();
                        ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipio_empresa+'">'+data.array_datos_destinatarios[0].Nombre_municipio_empresa+'</option>')
                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
                        $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipio_empresa);
                        // Listado de forma de editar de generar comunicado
                        let datos_lista_forma_envios = {
                            '_token':token,        
                            'parametro':"lista_forma_envio"
                        }
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data:datos_lista_forma_envios,
                            success:function(data){
                                //console.log(data);
                                $('#forma_envio_editar').empty();
                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            }
                        });

                        // Seleccción de la forma de envío acorde a la selección del empleador
                        setTimeout(() => {
                            if (data.info_medio_noti[0].Medio_notificacion == "Físico") {
                                $('#forma_envio_editar').val('46').trigger('change.select2');
                            }else{
                                $('#forma_envio_editar').val('47').trigger('change.select2');
                            }
                        }, 400);

                        var nombre_usuario = $('#elaboro_editar');
                        nombre_usuario.val(data.nombreusuario);
                        var nombre_usuario2 = $('#elaboro2_editar');
                        nombre_usuario2.val(data.nombreusuario);
                        // var reviso = $('#reviso_editar');
                        // reviso.empty();
                        // reviso.append('<option value="" selected>Seleccione una opción</option>');
                        // let revisolider = Object.keys(data.array_datos_lider);
                        // for (let i = 0; i < revisolider.length; i++) {
                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        // }
                        // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                        var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                        // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                        if (jrci_seleccionado > 0) {
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#input_jrci_seleccionado_editar").val('');
                        } 
                        // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                        else {
                            $("#div_select_jrci_editar").addClass('d-none');
                            $('#jrci_califi_invalidez_comunicado_editar').empty();  
                        }
                    }else if(data.destinatarioPrincipal == 'EPS_comunicado'){
                        var Nombre_afiliado = $('#nombre_destinatario_editar');
                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                        document.querySelector("#nombre_destinatario_editar").disabled = true;
                        document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_entidad;  
                        var nitccafiliado = $('#nic_cc_editar');
                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                        document.querySelector("#nic_cc_editar").disabled = true;
                        document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_entidad;        
                        var direccionafiliado = $('#direccion_destinatario_editar');
                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                        document.querySelector("#direccion_destinatario_editar").disabled = true;
                        document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion;        
                        var telefonoafiliado = $('#telefono_destinatario_editar');
                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                        document.querySelector("#telefono_destinatario_editar").disabled = true;
                        document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefonos;        
                        var emailafiliado = $('#email_destinatario_editar');
                        emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                        document.querySelector("#email_destinatario_editar").disabled = true;
                        document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Emails;
                        var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                        departamento_destinatario_editar.empty();
                        departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                        document.querySelector("#departamento_destinatario_editar").disabled = true;
                        $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento);
                        var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                        ciudad_destinatario_editar.empty();
                        ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
                        $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipios);
                        
                        let datos_lista_forma_envios = {
                            '_token':token,        
                            'parametro':"lista_forma_envio"
                        }
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data:datos_lista_forma_envios,
                            success:function(data){
                                //console.log(data);
                                $('#forma_envio_editar').empty();
                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            }
                        });

                        // Seleccción de la forma de envío acorde a la selección de la EPS
                        setTimeout(() => {
                            if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                $('#forma_envio_editar').val('46').trigger('change.select2');
                            }else{
                                $('#forma_envio_editar').val('47').trigger('change.select2');
                            }
                        }, 400);

                        var nombre_usuario = $('#elaboro_editar');
                        nombre_usuario.val(data.nombreusuario);
                        var nombre_usuario2 = $('#elaboro2_editar');
                        nombre_usuario2.val(data.nombreusuario);
                        // var reviso = $('#reviso_editar');
                        // reviso.empty();
                        // reviso.append('<option value="" selected>Seleccione una opción</option>');
                        // let revisolider = Object.keys(data.array_datos_lider);
                        // for (let i = 0; i < revisolider.length; i++) {
                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        // }

                        // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                        var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                        // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                        if (jrci_seleccionado > 0) {
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#input_jrci_seleccionado_editar").val('');
                        } 
                        // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                        else {
                            $("#div_select_jrci_editar").addClass('d-none');
                            $('#jrci_califi_invalidez_comunicado_editar').empty();  
                        }
                    }else if(data.destinatarioPrincipal == 'AFP_comunicado'){
                        var Nombre_afiliado = $('#nombre_destinatario_editar');
                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                        document.querySelector("#nombre_destinatario_editar").disabled = true;
                        document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_entidad;  
                        var nitccafiliado = $('#nic_cc_editar');
                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                        document.querySelector("#nic_cc_editar").disabled = true;
                        document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_entidad;        
                        var direccionafiliado = $('#direccion_destinatario_editar');
                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                        document.querySelector("#direccion_destinatario_editar").disabled = true;
                        document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion;        
                        var telefonoafiliado = $('#telefono_destinatario_editar');
                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                        document.querySelector("#telefono_destinatario_editar").disabled = true;
                        document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefonos;        
                        var emailafiliado = $('#email_destinatario_editar');
                        emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                        document.querySelector("#email_destinatario_editar").disabled = true;
                        document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Emails;
                        var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                        departamento_destinatario_editar.empty();
                        departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                        document.querySelector("#departamento_destinatario_editar").disabled = true;
                        $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento);
                        var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                        ciudad_destinatario_editar.empty();
                        ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
                        $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipios);
                        
                        let datos_lista_forma_envios = {
                            '_token':token,        
                            'parametro':"lista_forma_envio"
                        }
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data:datos_lista_forma_envios,
                            success:function(data){
                                //console.log(data);
                                $('#forma_envio_editar').empty();
                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            }
                        });

                        // Seleccción de la forma de envío acorde a la selección de la AFP
                        setTimeout(() => {
                            if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                $('#forma_envio_editar').val('46').trigger('change.select2');
                            }else{
                                $('#forma_envio_editar').val('47').trigger('change.select2');
                            }
                        }, 400);

                        var nombre_usuario = $('#elaboro_editar');
                        nombre_usuario.val(data.nombreusuario);
                        var nombre_usuario2 = $('#elaboro2_editar');
                        nombre_usuario2.val(data.nombreusuario);
                        // var reviso = $('#reviso_editar');
                        // reviso.empty();
                        // reviso.append('<option value="" selected>Seleccione una opción</option>');
                        // let revisolider = Object.keys(data.array_datos_lider);
                        // for (let i = 0; i < revisolider.length; i++) {
                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        // }

                        // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                        var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                        // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                        if (jrci_seleccionado > 0) {
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#input_jrci_seleccionado_editar").val('');
                        } 
                        // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                        else {
                            $("#div_select_jrci_editar").addClass('d-none');
                            $('#jrci_califi_invalidez_comunicado_editar').empty();  
                        }
                    }else if(data.destinatarioPrincipal == 'ARL_comunicado'){
                        var Nombre_afiliado = $('#nombre_destinatario_editar');
                        Nombre_afiliado.val(data.array_datos_destinatarios[0].Nombre_entidad);
                        document.querySelector("#nombre_destinatario_editar").disabled = true;
                        document.getElementById('nombre_destinatario_editar2').value=data.array_datos_destinatarios[0].Nombre_entidad;  
                        var nitccafiliado = $('#nic_cc_editar');
                        nitccafiliado.val(data.array_datos_destinatarios[0].Nit_entidad);
                        document.querySelector("#nic_cc_editar").disabled = true;
                        document.getElementById('nic_cc_editar2').value=data.array_datos_destinatarios[0].Nit_entidad;        
                        var direccionafiliado = $('#direccion_destinatario_editar');
                        direccionafiliado.val(data.array_datos_destinatarios[0].Direccion);
                        document.querySelector("#direccion_destinatario_editar").disabled = true;
                        document.getElementById('direccion_destinatario_editar2').value=data.array_datos_destinatarios[0].Direccion;        
                        var telefonoafiliado = $('#telefono_destinatario_editar');
                        telefonoafiliado.val(data.array_datos_destinatarios[0].Telefonos);
                        document.querySelector("#telefono_destinatario_editar").disabled = true;
                        document.getElementById('telefono_destinatario_editar2').value=data.array_datos_destinatarios[0].Telefonos;        
                        var emailafiliado = $('#email_destinatario_editar');
                        emailafiliado.val(data.array_datos_destinatarios[0].Emails);
                        document.querySelector("#email_destinatario_editar").disabled = true;
                        document.getElementById('email_destinatario_editar2').value=data.array_datos_destinatarios[0].Emails;
                        var departamento_destinatario_editar = $('#departamento_destinatario_editar');
                        departamento_destinatario_editar.empty();
                        departamento_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_departamento+'" selected>'+data.array_datos_destinatarios[0].Nombre_departamento+'</option>');
                        document.querySelector("#departamento_destinatario_editar").disabled = true;
                        $("#departamento_pdf").val(data.array_datos_destinatarios[0].Id_departamento);
                        var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                        ciudad_destinatario_editar.empty();
                        ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipios+'">'+data.array_datos_destinatarios[0].Nombre_ciudad+'</option>')
                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
                        $("#ciudad_pdf").val(data.array_datos_destinatarios[0].Id_municipios);
                        
                        let datos_lista_forma_envios = {
                            '_token':token,        
                            'parametro':"lista_forma_envio"
                        }
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data:datos_lista_forma_envios,
                            success:function(data){
                                //console.log(data);
                                $('#forma_envio_editar').empty();
                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            }
                        });

                        // Seleccción de la forma de envío acorde a la selección de la ARL
                        setTimeout(() => {
                            if (data.array_datos_destinatarios[0].Id_Medio_Noti == 81) {
                                $('#forma_envio_editar').val('46').trigger('change.select2');
                            }else{
                                $('#forma_envio_editar').val('47').trigger('change.select2');
                            }
                        }, 400);

                        var nombre_usuario = $('#elaboro_editar');
                        nombre_usuario.val(data.nombreusuario);
                        var nombre_usuario2 = $('#elaboro2_editar');
                        nombre_usuario2.val(data.nombreusuario);
                        // var reviso = $('#reviso_editar');
                        // reviso.empty();
                        // reviso.append('<option value="" selected>Seleccione una opción</option>');
                        // let revisolider = Object.keys(data.array_datos_lider);
                        // for (let i = 0; i < revisolider.length; i++) {
                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        // }

                        // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                        var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                        // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                        if (jrci_seleccionado > 0) {
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#input_jrci_seleccionado_editar").val('');
                        } 
                        // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                        else {
                            $("#div_select_jrci_editar").addClass('d-none');
                            $('#jrci_califi_invalidez_comunicado_editar').empty();  
                        }
                    }else if(data.destinatarioPrincipal == 'Otro'){
                        //console.log(data.destinatarioPrincipal);
                        document.querySelector("#nombre_destinatario_editar").disabled = false;
                        document.querySelector("#nic_cc_editar").disabled = false;
                        document.querySelector("#direccion_destinatario_editar").disabled = false;
                        document.querySelector("#telefono_destinatario_editar").disabled = false;
                        document.querySelector("#email_destinatario_editar").disabled = false;
                        document.querySelector("#departamento_destinatario_editar").disabled = false;
                        document.querySelector("#ciudad_destinatario_editar").disabled = false;
                        $("#departamento_pdf").val('');
                        $("#ciudad_pdf").val('');
                        $('#nombre_destinatario_editar').val('');
                        $('#nic_cc_editar').val('');
                        $('#direccion_destinatario_editar').val('');
                        $('#telefono_destinatario_editar').val('');
                        $('#email_destinatario_editar').val('');
                        // Listado de departamento generar comunicado
                        let datos_lista_departamentos_generar_comunicado = {
                            '_token': token,
                            'parametro' : "departamentos_generar_comunicado"
                        };
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data: datos_lista_departamentos_generar_comunicado,
                            success:function(data) {
                                // console.log(data);
                                $('#departamento_destinatario_editar').empty();
                                $('#ciudad_destinatario_editar').empty();
                                $('#departamento_destinatario_editar').append('<option value="" selected>Seleccione</option>');
                                let claves = Object.keys(data);
                                for (let i = 0; i < claves.length; i++) {
                                    $('#departamento_destinatario_editar').append('<option value="'+data[claves[i]]["Id_departamento"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
                                }
                            }
                        });
                        // listado municipios dependiendo del departamentos generar comunicado
                        $('#departamento_destinatario_editar').change(function(){
                            $('#ciudad_destinatario_editar').prop('disabled', false);
                            let id_departamento_destinatario = $('#departamento_destinatario_editar').val();
                            $("#departamento_pdf").val(id_departamento_destinatario);
                            let datos_lista_municipios_generar_comunicado = {
                                '_token': token,
                                'parametro' : "municipios_generar_comunicado",
                                'id_departamento_destinatario': id_departamento_destinatario
                            };
                            $.ajax({
                                type:'POST',
                                url:'/selectoresModuloCalificacionPCL',
                                data: datos_lista_municipios_generar_comunicado,
                                success:function(data) {
                                    // console.log(data);
                                    $('#ciudad_destinatario_editar').empty();
                                    $('#ciudad_destinatario_editar').append('<option value="" selected>Seleccione</option>');
                                    let claves = Object.keys(data);
                                    for (let i = 0; i < claves.length; i++) {
                                        $('#ciudad_destinatario_editar').append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                                    }
                                }
                            });
                        });

                        $("#ciudad_destinatario_editar").change(function(){
                            let id_ciudad_destinatario = $('#ciudad_destinatario_editar').val();
                            $("#ciudad_pdf").val(id_ciudad_destinatario);
                        });
                        // Listado de forma de editar de generar comunicado
                        let datos_lista_forma_envios = {
                            '_token':token,        
                            'parametro':"lista_forma_envio"
                        }
                        $.ajax({
                            type:'POST',
                            url:'/selectoresModuloCalificacionPCL',
                            data:datos_lista_forma_envios,
                            success:function(data){
                                //console.log(data);
                                $('#forma_envio_editar').empty();
                                forma_envio_editar.append('<option value="" selected>Seleccione una opción</option>');
                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                        $('#forma_envio_editar').append('<option value="'+data[formaenviogenerarcomunicado[i]]['Id_Parametro']+'">'+data[formaenviogenerarcomunicado[i]]['Nombre_parametro']+'</option>');
                                    }                
                                }
                            }
                        });
                        var nombre_usuario = $('#elaboro_editar');
                        nombre_usuario.val(data.nombreusuario);
                        var nombre_usuario2 = $('#elaboro2_editar');
                        nombre_usuario2.val(data.nombreusuario);
                        // var reviso = $('#reviso_editar');
                        // reviso.empty();
                        // reviso.append('<option value="" selected>Seleccione una opción</option>');
                        // let revisolider = Object.keys(data.array_datos_lider);
                        // for (let i = 0; i < revisolider.length; i++) {
                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        // }

                        // Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
                        var jrci_seleccionado = $("#jrci_califi_invalidez").val();
                        // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
                        if (jrci_seleccionado > 0) {
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#input_jrci_seleccionado_editar").val('');
                        } 
                        // Si no, eliminar el las opciones del selector y deja todo limpio y eliminarlos datos del destinatario 
                        else {
                            $("#div_select_jrci_editar").addClass('d-none');
                            $('#jrci_califi_invalidez_comunicado_editar').empty();  
                        }
                    }
    
                }        
            });
            
        });

    });

    /* Funcionalidad para insertar las etiquetas correspondientes de las proformas 
        Oficion Juntas afiliado, Oficio Juntas JRCI, 
        Expediente JRCI, Devol. Expediente JRCI, Solicitud Dictamen JRCI, Otro Documento
        Edición
    */
    $("#btn_insertar_nombre_junta_regional_asunto_editar").click(function(e){
        e.preventDefault();
        var cursorPos = $("#asunto_editar").prop('selectionStart');
        var currentValue = $("#asunto_editar").val();
        var newValue = currentValue.slice(0, cursorPos) + '{{$nombre_junta_asunto}}' + currentValue.slice(cursorPos);
        // Actualiza el valor del input
        $("#asunto_editar").val(newValue);
        // Coloca el cursor después de la etiqueta
        $("#asunto_editar").prop('selectionStart', cursorPos + 25);
        $("#asunto_editar").prop('selectionEnd', cursorPos + 25);
        $("#asunto_editar").focus();
    });
    
    $("#btn_insertar_nombre_junta_regional_editar").click(function(e){
        e.preventDefault();
        var etiqueta_nombre_junta = "{{$nombre_junta}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_nombre_junta);
    });

    $("#btn_insertar_nro_orden_pago_editar").click(function(e){
        e.preventDefault();
        var etiqueta_nro_orden_pago = "{{$nro_orden_pago}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_nro_orden_pago);
    });

    $("#btn_insertar_fecha_notifi_afiliado_editar").click(function(e){
        e.preventDefault();
        var etiqueta_fecha_notificacion_afiliado = "{{$fecha_notificacion_afiliado}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_fecha_notificacion_afiliado);
    });

    $("#btn_insertar_fecha_radi_contro_pri_cali_editar").click(function(e){
        e.preventDefault();
        var etiqueta_fecha_radicacion_controversia_primera_calificacion = "{{$fecha_radicacion_controversia_primera_calificacion}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_fecha_radicacion_controversia_primera_calificacion);
    });

    $("#btn_insertar_tipo_doc_afiliado_editar").click(function(e){
        e.preventDefault();
        var etiqueta_tipo_documento_afiliado = "{{$tipo_documento_afiliado}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_tipo_documento_afiliado);
    });

    $("#btn_insertar_documento_afiliado_editar").click(function(e){
        e.preventDefault();
        var etiqueta_documento_afiliado = "{{$documento_afiliado}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_documento_afiliado);
    });

    $("#btn_insertar_nombre_afiliado_editar").click(function(e){
        e.preventDefault();
        var etiqueta_nombre_afiliado = "{{$nombre_afiliado}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_nombre_afiliado);
    });

    $("#btn_insertar_fecha_estructuracion_editar").click(function(e){
        e.preventDefault();
        var etiqueta_fecha_estructuracion = "{{$fecha_estructuracion}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_fecha_estructuracion);
    });

    $("#btn_insertar_tipo_evento_editar").click(function(e){
        e.preventDefault();
        var etiqueta_tipo_evento = "{{$tipo_evento}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_tipo_evento);
    });

    $("#btn_insertar_nombres_cie10_editar").click(function(e){
        e.preventDefault();
        var etiqueta_nombres_cie10 = "{{$nombres_cie10}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_nombres_cie10);
    });

    $("#btn_insertar_tipo_controversia_pri_cali_editar").click(function(e){
        e.preventDefault();
        var etiqueta_tipo_controversia_primera_calificacion = "{{$tipo_controversia_primera_calificacion}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_tipo_controversia_primera_calificacion);
    });

    $("#btn_insertar_direccion_afiliado_editar").click(function(e){
        e.preventDefault();
        var etiqueta_direccion_afiliado = "{{$direccion_afiliado}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_direccion_afiliado);
    });
    
    $("#btn_insertar_telefono_afiliado_editar").click(function(e){
        e.preventDefault();
        var etiqueta_telefono_afiliado = "{{$telefono_afiliado}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_telefono_afiliado);
    });

    // $("#btn_insertar_nombre_documento_editar").click(function(e){
    //     e.preventDefault();
    //     var etiqueta_nombre_documento = "{{$nombre_documento}}";
    //     $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_nombre_documento);
    // });

    $("#btn_insertar_correo_solicitud_info_editar").click(function(e){
        e.preventDefault();
        var etiqueta_correo_solicitud_informacion = "{{$correo_solicitud_informacion}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_correo_solicitud_informacion);
    });
    
    /* Funcionalidad para los radio buttons de Oficion Juntas afiliado, Oficio Juntas JRCI, 
        Expediente JRCI, Devol. Expediente JRCI, Solicitud Dictamen JRCI, Otro Documento Edición */
    $("[name='tipo_de_preforma_editar']").on("change", function(){
        var opc_seleccionada = $(this).val();

        if(opc_seleccionada == "Oficio_Afiliado"){
            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").removeClass('d-none');
            $("#rellenar_asunto_editar").html('Para mostrar todo el asunto dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Asunto.');
            $("#mensaje_cuerpo_editar").removeClass('d-none');
            $("#rellenar_cuerpo_editar").html('');
            $("#rellenar_cuerpo_editar").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO	
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', false);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', false);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#asunto_editar").val("REMISIÓN DEL CASO A {{$nombre_junta_asunto}}");
            var texto_insertar = "<p>Cordial saludo:</p><p>Dando respuesta a inconformidad presentada frente a calificación de la Pérdida de la Capacidad Laboral emitida por esta aseguradora, dentro de los términos estipulados en la normatividad vigente, se informa: que hoy los documentos concernientes a su caso han sido enviados a la {{$nombre_junta}}, la cual, le citará a la valoración correspondiente.</p><p>Recuerde por favor, que las Juntas Regionales de Calificación de Invalidez son entidades independientes y autónomas de las compañías de seguros, cuyos procesos y procedimientos son ajenos a nuestra entidad, por lo tanto, cualquier requerimiento ante la misma debe ser tratado directamente con la Junta Regional de Calificación de Invalidez.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escríbanos a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio José María Córdoba, Bogotá D.C.</p>";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

            // Auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado_editar').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#edit_copia_afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', true);

        }else if(opc_seleccionada == "Oficio_Juntas_JRCI"){
            // Esta proforma no tiene botones para insertar en el asunto y/o cuerpo del comunicado

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").addClass('d-none');
            $("#rellenar_cuerpo_editar").html('');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO	
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#asunto_editar").val('N/A');
            $('#cuerpo_comunicado_editar').summernote('code', 'N/A');

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

            // Auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado_editar').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#edit_copia_afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', true);

        }else if(opc_seleccionada == "Remision_Expediente_JRCI"){
            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").removeClass('d-none');
            $("#rellenar_cuerpo_editar").html('');
            $("#rellenar_cuerpo_editar").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir las etiquetas N° Orden pago, Fecha Notificación al Afiliado, Fecha Radicación Controversia Primera Calificación, Tipo Documento Afiliado, Documento Afiliado, Nombre Afiliado, Fecha Estructuración, Tipo de Evento, Nombres CIE-10, Tipo Controversia Primera Calificación, Dirección Afiliado, Teléfono Afiliado, dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', false);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', false);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', false);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', false);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', false);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', false);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', false);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#asunto_editar").val("REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA");
            var texto_insertar = "<p>Respetados señores, cordial saludo:</p><p>En aras de tramitar el recurso y/o controversia presentada en tiempo por la parte interesada contra el dictamen de calificación de PÉRDIDA DE CAPACIDAD LABORAL, remitimos el expediente del afiliado con la documentación exigida por el artículo 30 del Decreto 1352 de 2013 (historia clínica, constancia de notificación, dictamen médico laboral, controversia, etc.) para su valoración.</p><p>Según lo dispone el artículo 20 del mismo decreto, el valor de los honorarios corresponde a un (1) salario mínimo mensual legal vigente, el cual fue cancelado por esta aseguradora. Para los efectos, adjuntamos orden de pago de honorarios No {{$nro_orden_pago}}.</p><p>Finalmente, indicamos que la fecha de notificación del dictamen lo fue el {{$fecha_notificacion_afiliado}} y la radicación del desacuerdo el {{$fecha_radicacion_controversia_primera_calificacion}}, razón por la cual es procedente tramitar el recurso.</p><p>Los datos del afiliado son los siguientes:</p><table class='tabla_cuerpo_remision_expediente'><tbody><tr><td class='bg'><b>TIPO Y No. DE IDENTIFICACIÓN</b></td><td><p>{{$tipo_documento_afiliado}}{{$documento_afiliado}}<br></p></td></tr><tr><td class='bg'><b>NOMBRE COMPLETO</b></td><td><p>{{$nombre_afiliado}}<br></p></td></tr><tr><td class='bg'><b>FECHA DE ESTRUCTURACIÓN Y TIPO DE EVENTO</b></td><td><p>PCL {{$porcentaje_pcl}}-{{$fecha_estructuracion}}-{{$tipo_evento}}<br></p></td></tr><tr><td class='bg'><b>DIAGNÓSTICO</b></td><td><p>{{$nombres_cie10}}<br></p></td></tr><tr><td class='bg'><b>CONTROVERSIA POR</b></td><td><p>{{$tipo_controversia_primera_calificacion}}<br></p></td></tr><tr><td class='bg'><b>DIRECCIÓN Y TELÉFONO DEL ASEGURADO</b></td><td><p>{{$direccion_afiliado}} {{$telefono_afiliado}}<br></p></td></tr><tr><td class='bg'><b>OBSERVACIONES</b></td><td><p>{{$observaciones}}</p></td></tr></tbody></table><p>En virtud de lo señalado en el Artículo 2 del Decreto 1352 de 2013 que establece:</p><p>Artículo 2. Personas interesadas. Para efectos del presente decreto, se entenderá como personas interesadas en el dictamen y de obligatoria notificación o comunicación como mínimo las siguientes:</p><ul><li><span style=''>La persona objeto de dictamen o sus beneficiarios en caso de muerte.</span></li><li><span style=''>La Entidad Promotora de Salud.</span></li><li><span style=''>La Administradora de Riesgos Laborales.</span></li><li><span style=''>La Administradora del Fondo de Pensiones o Administradora de Régimen de Prima Media.</span></li><li><span style=''>El Empleador.</span></li><li><span style=''>La Compañía de Seguro que asuma el riesgo de invalidez, sobrevivencia y muerte. (Subrayado fuera del texto original).</span></li></ul><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escríbanos a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio José María Córdoba, Bogotá D.C.</p>";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

            // Auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado_editar').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // selección automática de las copias a partes interesadas: Afiliado
            $("#edit_copia_afiliado").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', true);

        }else if(opc_seleccionada == "Devolucion_Expediente_JRCI"){
            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").removeClass('d-none');
            $("#rellenar_cuerpo_editar").html('');
            $("#rellenar_cuerpo_editar").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir la etiqueta Nombre Junta Regional dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', false);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', false);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);
        
            $("#asunto_editar").val("RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE");
            var texto_insertar = "<p>Reciban un cordial saludo,</p>\
            <p>En respuesta a la solicitud radicada por ustedes, esta aseguradora se permite dar respuesta en los términos que se describen a continuación:</p>\
            <p>Una vez revisadas nuestras bases y sistemas de información evidenciamos que el día (Anotar Fecha de envío de expediente) esta compañía solicitó a ustedes calificación de (Anotar la causal de controversia) en virtud de la controversia interpuesta por el usuario en asunto.</p>\
            <p>Ahora bien, con respecto a la solicitud emitida por ustedes, nos permitimos informar que recibimos el expediente en devolución, motivo por el cual el grupo interdisciplinario de Seguros de Vida Alfa realizó la respectiva verificación por lo que remitimos nuevamente el expediente completo con la documentación solicitada:</p>\
            <p style='color:red;'>Nota: En este espacio del documento debe crear la tabla de Documento y No. Folio.<p>\
            <p>Por lo anterior, solicitamos amablemente a la Honorable {{$nombre_junta}} para que se realice el trámite correspondiente según la normatividad vigente teniendo en cuenta que la afiliada se encuentra en términos legales para dirimir la controversia interpuesta.</p>\
            <p>Agradecemos la atención prestada y reiteramos nuestra voluntad de servicio.</p>\
            ";

            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);

            $("#Pdf").val("Word");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('Word');

            // Auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado_editar').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Afiliado
            $("#edit_copia_afiliado").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', true);
            
        }else if(opc_seleccionada == "Solicitud_Dictamen_JRCI"){

            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").removeClass('d-none');
            $("#rellenar_cuerpo_editar").html('');
            $("#rellenar_cuerpo_editar").html('Para mostrar todo el cuerpo del comunicado dentro del documento, debe incluir las etiquetas Nombre Afiliado, Correo Solicitud Información, dentro del campo Cuerpo del comunicado.');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', false);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento_editar").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', false);
    
            $("#asunto_editar").val("ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN");
            var texto_insertar = "<p>Respetados señores, cordial saludo:</p>\
            <p>Revisadas nuestras bases de datos y sistemas de información evidenciamos que su entidad remitió a la AFP Porvenir, notificación del dictamen de calificación de Pérdida de Capacidad Laboral (PCL) el pasado (Anote Fecha de notificación del Dictamen de JRCI) acaecido al señor {{$nombre_afiliado}}, por lo que esta entidad remitió estos comunicados a Seguros de Vida Alfa, como aseguradora que expidió su seguro previsional.</p>\
            <p>Al respecto, el equipo interdisciplinario de calificación procedió a revisar sus comunicaciones y evidenció que dentro de dicha notificación no se adjuntó el Dictamen, por lo que el día (Anote Fecha de solicitud de Dictamen) se solicitó a los correos {{$correo_solicitud_informacion}}, el envío del mismo con el fin de verificar cada una de las razones de hecho y de derecho de la decisión tomada por ustedes.</p>\
            <p>En consecuencia, se observa que después de esta petición no se tiene radicación o aclaración sobre lo antes mencionado, por lo que se solicita amablemente emitir estado de caso sobre la notificación y el recurso emitido, toda vez que en su momento no se tuvo como verificar lo calificado por ustedes a favor del señor {{$nombre_afiliado}}.</p>\
            <p>Dicha notificación se puede realizar en la dirección Carrera 10 # 18 - 36 Edificio Córdoba Piso 4 en Bogotá.</p>\
            ";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);

            $("#Pdf").val("Word");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('Word');

            // Auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado_editar').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#edit_copia_afiliado").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', true);
    
        }else{
            // mostrar mensaje(s) importante(s)
            $("#mensaje_asunto_editar").addClass('d-none');
            $("#rellenar_asunto_editar").html('');
            $("#mensaje_cuerpo_editar").addClass('d-none');
            $("#rellenar_cuerpo_editar").html('');

            // botones proforma ADJUNTAR OFICIO AL AFILIADO
            $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
            $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
            //  botones proforma REMISIÓN DE EXPEDIENTE PARA TRÁMITE DE CONTROVERSIA
            $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
            $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
            $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
            $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
            $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
            $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
            $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
            // botón preforma RESPUESTA A DEVOLUCIÓN DE EXPEDIENTE
            // $("#btn_insertar_nombre_documento").prop('disabled', true);
            // botón preforma: ACLARACIÓN E INFORMACIÓN SOBRE RECURSO DE REPOSICIÓN EN SUBSIDIO DE APELACIÓN
            $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);

            $("#asunto_editar").val('');
            $('#cuerpo_comunicado_editar').summernote('code', '');

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

            // Quitar auto selección de la opción JRCI (Destinatario Principal)
            $('#jrci_comunicado_editar').prop('checked', false);

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Afiliado
            $("#edit_copia_afiliado").prop('checked', false);

            // Deselección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', false);

        }
    });

     // Función para verificar si todos los campos están llenos al momento de guardar el comunicado
    function verificarCamposLlenosGuardar() {
        var todosLlenos = true;
        // Lista de IDs de los campos que quieres verificar
        var camposIDs = ['#nombre_destinatario', '#nic_cc', '#direccion_destinatario', '#telefono_destinatario',
        '#email_destinatario', '#departamento_destinatario_editar', '#departamento_destinatario', '#asunto', 
        '#cuerpo_comunicado', '#forma_envio', '#reviso'];
        
        // Verifica cada campo por su ID
        camposIDs.forEach(function(id) {
            var campo = $(id);
            if (campo.is('input, select, textarea') && campo.val() === '') {
                todosLlenos = false;
                return false; // Sale del bucle si encuentra un campo vacío
            }
        });
        return todosLlenos;
    }

    // Temporizador que se ejecuta cada segundo
    setInterval(function() {
        if (verificarCamposLlenosGuardar()) {
            // Si todos los campos están llenos, habilita el botón
            if (idRol == 7) {
                $('#Generar_comunicados').prop('disabled', true); 
            } else {
                $('#Generar_comunicados').prop('disabled', false); 
            }
            // $('#Pdf').prop('disabled', false);           
        } else {
            // Si hay campos vacíos, deshabilita el botón
            $('#Generar_comunicados').prop('disabled', true); 
            // $('#Pdf').prop('disabled', true);           
        }
    }, 1000); // 1000 milisegundos = 1 segundo

    // Función para verificar si todos los campos están llenos al momento de actualizar el comunicado
     function verificarCamposLlenos() {
        var todosLlenos = true;
        // Lista de IDs de los campos que quieres verificar
        var camposIDs = ['#nombre_destinatario_editar', '#nic_cc_editar', '#direccion_destinatario_editar', '#telefono_destinatario_editar',
        '#email_destinatario_editar', '#departamento_destinatario_editar', '#ciudad_destinatario_editar', '#asunto_editar', 
        '#cuerpo_comunicado_editar', '#forma_envio_editar', '#reviso_editar'];
        
        // Verifica cada campo por su ID
        camposIDs.forEach(function(id) {
            var campo = $(id);
            if (campo.is('input, select, textarea') && campo.val() === '') {
                todosLlenos = false;
                return false; // Sale del bucle si encuentra un campo vacío
            }
        });
        return todosLlenos;
    }
    
    // Temporizador que se ejecuta cada segundo
    setInterval(function() {
        if (verificarCamposLlenos()) {
            // Si todos los campos están llenos, habilita el botón
            if (idRol == 7) {
                $('#Editar_comunicados').prop('disabled', true); 
            } else {
                $('#Editar_comunicados').prop('disabled', false); 
            }
            // $('#Pdf').prop('disabled', false);           
        } else {
            // Si hay campos vacíos, deshabilita el botón
            $('#Editar_comunicados').prop('disabled', true); 
            // $('#Pdf').prop('disabled', true);         
        }
    }, 1000); // 1000 milisegundos = 1 segundo

    /* Funcionalidad checkbox JRCI (Copia Partes Interesadas) */
    $("#edit_copia_jrci").click(function(){
        // Si fue seleccionado realiza lo siguente
        if ($(this).prop('checked')) {
            // 1. Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
            var jrci_seleccionado = $("#jrci_califi_invalidez").val();
            // Si existe, debe mostrar un input con el nombre de la jrci seleccioanda y cargar los datos del destinatario con la
            // info del jrci.
            if (jrci_seleccionado > 0) {
                $("#div_input_jrci_copia_editar").removeClass('d-none');
                $("#div_select_jrci_copia_editar").addClass('d-none');
                $("#input_jrci_seleccionado_copia_editar").val($("#jrci_califi_invalidez option:selected").text());
            }
            // si no, el mismo selector de jrci
            else{
                $("#div_input_jrci_copia_editar").removeClass('d-none');
                $("#input_jrci_seleccionado_copia_editar").val($("#jrci_califi_invalidez_comunicado_editar option:selected").text());
                $("#div_select_jrci_copia_editar").addClass('d-none');

                $(".jrci_califi_invalidez_copia_editar").select2({
                    placeholder:"Seleccione una opción",
                    allowClear:false
                });
                $.ajax({
                    type:'POST',
                    url:'/selectoresJuntas',
                    data: datos_lista_juntas_invalidez,
                    success:function(data) {
                        // let IdJuntaInvalidez = $('select[name=jrci_califi_invalidez]').val();
                        $('#jrci_califi_invalidez_copia_editar').append('<option>Seleccione una opción</option>');
                        let juntajrci = Object.keys(data);
                        for (let i = 0; i < juntajrci.length; i++) {
                            $('#jrci_califi_invalidez_copia_editar').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                        }
                    }
                });
            }
        }
        else{
            // 1. Evalua el selector de Junta Regional de Calificación de Invalidez JRCI para ver si tiene un valor seleccionado.
            var jrci_seleccionado = $("#jrci_califi_invalidez").val();
            // Si existe, debe eliminar la info un input con el nombre de la jrci seleccioanda
            if (jrci_seleccionado > 0) {
                $("#div_input_jrci_copia_editar").addClass('d-none');
                $("#input_jrci_seleccionado_copia_editar").val('');
            }
            // Si no, eliminar el las opciones del selector y deja todo limpio.
            else{
                //Limpiar input de copia 
                $("#div_input_jrci_copia_editar").addClass('d-none');
                $("#input_jrci_seleccionado_copia_editar").val('');

                $("#div_select_jrci_copia_editar").addClass('d-none');
                $('#jrci_califi_invalidez_copia_editar').empty();  
            }
        }
    });

    // Actualiza comunicado de origen
    $('#Editar_comunicados').click(function (e) {
        e.preventDefault();  
        $('#Pdf').prop('disabled', false);
        $("#Editar_comunicados").remove();
        $("#mostrar_barra_creacion_comunicado").removeClass('d-none');     
        var Id_comunicado = $('#Id_comunicado_act').val();
        var ciudad = $('#ciudad_comunicado_editar').val();
        var Id_evento = $('#Id_evento_act').val();
        var Id_asignacion = $('#Id_asignacion_act').val();
        var Id_procesos = $('#Id_procesos_act').val();
        var fecha_comunicado2 = $('#fecha_comunicado2_editar').val();
        var radicado2 = $('#radicado2_comunicado_editar').val();
        var cliente_comunicado2 = $('#cliente_comunicado2_editar').val();
        var nombre_afiliado_comunicado2 = $('#nombre_afiliado_comunicado2_editar').val();
        var tipo_documento_comunicado2 = $('#tipo_documento_comunicado2_editar').val();
        var identificacion_comunicado2 = $('#identificacion_comunicado2_editar').val();
        var jrci_comunicado = $('#jrci_comunicado_editar').prop('checked');
        var jnci_comunicado = $('#jnci_comunicado_editar').prop('checked');                       
        var afiliado_comunicado = $('#afiliado_comunicado_editar').prop('checked');
        var empresa_comunicado = $('#empresa_comunicado_editar').prop('checked');
        var eps_comunicado = $('#eps_comunicado_editar').prop('checked');
        var afp_comunicado = $('#afp_comunicado_editar').prop('checked');
        var arl_comunicado = $('#arl_comunicado_editar').prop('checked');
        var Otro = $('#Otro_editar').prop('checked');
       
        var radiojrci_comunicado, radiojnci_comunicado, radioafiliado_comunicado,
        radioempresa_comunicado, radioeps_comunicado, radioafp_comunicado,
        radioarl_comunicado, radioOtro;

        var JRCI_Destinatario;

        if(jrci_comunicado){
            radiojrci_comunicado = jrci_comunicado;
            if($('#jrci_califi_invalidez_comunicado_editar').val() != ""){
                JRCI_Destinatario = $("#jrci_califi_invalidez_comunicado_editar").val();
            }else{
                JRCI_Destinatario = $('#input_jrci_seleccionado_editar').val();
            }
        }else if(jnci_comunicado){
            radiojnci_comunicado = jnci_comunicado;
            JRCI_Destinatario = '';
        }else if(afiliado_comunicado){
           var radioafiliado_comunicado = afiliado_comunicado;
           JRCI_Destinatario = '';
        }else if(empresa_comunicado){
           var radioempresa_comunicado = empresa_comunicado;
           JRCI_Destinatario = '';
        }else if(eps_comunicado){
            radioeps_comunicado = eps_comunicado;
            JRCI_Destinatario = '';
        }else if(afp_comunicado){
            radioafp_comunicado = afp_comunicado;
            JRCI_Destinatario = '';
        }else if(arl_comunicado){
            radioarl_comunicado = arl_comunicado;
            JRCI_Destinatario = '';
        }else if(Otro){
           var radioOtro = Otro;
           JRCI_Destinatario = '';
        }
        
        //console.log(radioafiliado_comunicado);
        var nombre_destinatario = $('#nombre_destinatario_editar').val();
        var nic_cc = $('#nic_cc_editar').val();
        var direccion_destinatario = $('#direccion_destinatario_editar').val();
        var telefono_destinatario = $('#telefono_destinatario_editar').val();
        var email_destinatario = $('#email_destinatario_editar').val();
        var departamento_destinatario = $('#departamento_destinatario_editar').val();
        var ciudad_destinatario = $('#ciudad_destinatario_editar').val();
        var asunto = $('#asunto_editar').val();
        var cuerpo_comunicado = $('#cuerpo_comunicado_editar').val();
        var anexos = $('#anexos_editar').val();
        var forma_envio = $('#forma_envio_editar').val();
        var elaboro2 = $('#elaboro2_editar').val();
        var reviso = $('#reviso_editar').val();
        var firmarcomunicado_editar = $('#firmarcomunicado_editar').filter(":checked").val();
        var tipo_descarga = $("[name='tipo_de_preforma_editar']").filter(":checked").val();
        var N_siniestro = $("#n_siniestro_proforma_editar").val();
       //Copias Interesadas Origen
       var EditComunicadoTotal = [];
       let copias = {};
       cuerpo_comunicado = cuerpo_comunicado ? cuerpo_comunicado.replace(/"/g, "'") : '';
       $('input[type="checkbox"]').each(function() {
            var copiaComunicado2 = $(this).attr('id');            
            if (copiaComunicado2 === 'edit_copia_afiliado' || copiaComunicado2 === 'edit_copia_empleador' || 
                copiaComunicado2 === 'edit_copia_eps' || copiaComunicado2 === 'edit_copia_afp' || copiaComunicado2 === 'edit_copia_arl' || copiaComunicado2 === 'edit_copia_afp_conocimiento'
                || copiaComunicado2 === 'edit_copia_jrci' || copiaComunicado2 === 'edit_copia_jnci' ) {                
                if ($(this).is(':checked')) {                
                var relacionCopiaValor2 = $(this).val();
                EditComunicadoTotal.push(relacionCopiaValor2);
                copias[$(this).val()] = true;
                }else{
                    copias[$(this).val()] = false;
                }
            }
       });

       var JRCI_copia;
        if($("#edit_copia_jrci").is(':checked')){
            if($("#input_jrci_seleccionado_copia_editar").val() != ''){
                JRCI_copia = $("#input_jrci_seleccionado_copia_editar").val();
            }else{
                JRCI_copia = $("#jrci_califi_invalidez_copia_editar").val();
            }
        }else{
            JRCI_copia= '';
        }

        let token = $('input[name=_token]').val();        
        var datos_actualizarComunicado = {
            '_token': token,
            'Id_comunicado_editar':Id_comunicado,
            'ciudad_editar':ciudad,
            'Id_evento_editar':Id_evento,
            'Id_asignacion_editar':Id_asignacion,
            'Id_procesos_editar':Id_procesos,
            'fecha_comunicado2_editar':fecha_comunicado2,
            'radicado2_editar':radicado2,
            'cliente_comunicado2_editar':cliente_comunicado2,
            'nombre_afiliado_comunicado2_editar':nombre_afiliado_comunicado2,
            'tipo_documento_comunicado2_editar':tipo_documento_comunicado2,
            'identificacion_comunicado2_editar':identificacion_comunicado2,            
            'radiojrci_comunicado_editar': radiojrci_comunicado ?? null,
            'JRCI_Destinatario_editar': JRCI_Destinatario,
            'radiojnci_comunicado_editar': radiojnci_comunicado ?? null,
            'radioafiliado_comunicado_editar':radioafiliado_comunicado ?? null,
            'radioempresa_comunicado_editar':radioempresa_comunicado ?? null,
            'radioeps_comunicado_editar': radioeps_comunicado ?? null,
            'radioafp_comunicado_editar': radioafp_comunicado ?? null,
            'radioarl_comunicado_editar': radioarl_comunicado ?? null,
            'radioOtro_editar':radioOtro ?? null,
            'nombre_destinatario_editar':nombre_destinatario,
            'nic_cc_editar':nic_cc,
            'direccion_destinatario_editar':direccion_destinatario,
            'telefono_destinatario_editar':telefono_destinatario,
            'email_destinatario_editar':email_destinatario,
            'departamento_destinatario_editar':departamento_destinatario,
            'ciudad_destinatario_editar':ciudad_destinatario,
            'asunto_editar':asunto,
            'cuerpo_comunicado_editar':cuerpo_comunicado,
            'anexos_editar':anexos,
            'forma_envio_editar':forma_envio,
            'elaboro2_editar':elaboro2,
            'reviso_editar':reviso,
            'agregar_copia_editar':EditComunicadoTotal,
            'JRCI_copia_editar': JRCI_copia,
            'firmarcomunicado_editar':firmarcomunicado_editar,
            'tipo_descarga': tipo_descarga,
            'modulo_creacion':'calificacionJuntas',
            'N_siniestro':N_siniestro,
            'Nombre_junta_act': $('#Nombre_junta_act').val(),
            'F_estructuracion_act': $('#F_estructuracion_act').val(),
            'F_dictamen_act' : $('#F_dictamen_act').val(),
            'input_jrci_seleccionado_copia_editar' : JRCI_copia,
            'id_jrci_del_input': $('#Id_junta_act').val(),
            'F_radicacion_contro_pri_cali_act' : $('#F_radicacion_contro_pri_cali_act').val(),
            'F_notifi_afiliado_act' : $('#F_notifi_afiliado_act').val(),
            'Id_junta_act' : $('#Id_junta_act').val(),
            "edit_copia_afiliado" : copias['Afiliado'] ,
            "edit_copia_empleador" : copias['Empleador'],
            "edit_copia_eps" : copias['EPS'],
            "edit_copia_afp" : copias['AFP'],
            "edit_copia_arl" : copias['ARL'],
            "edit_copia_jrci" : copias['JRCI'],
            "edit_copia_jnci" : copias['JNCI'],
            'tipo_de_preforma_editar': tipo_descarga
        }

        $.ajax({
            type:'POST',
            url:'/actualizarComunicadoJuntas',
            data: datos_actualizarComunicado,            
            success:function(response){
                if (response.parametro == 'actualizar_comunicado') {
                    $("#mostrar_barra_creacion_comunicado").addClass('d-none');
                    $('.alerta_editar_comunicado').removeClass('d-none');
                    $('.alerta_editar_comunicado').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_editar_comunicado').addClass('d-none');
                        $('.alerta_editar_comunicado').empty();
                        localStorage.setItem("#Generar_comunicados", true);
                        location.reload();
                    }, 3000);
                }
            }
        })
    }) 

    // Listado de agregar seguimiento

    /* let datos_lista_causal_seguimiento = {
        '_token':token,        
        'parametro':"lista_causal_seguimiento_pcl"
    }

    $.ajax({
        type:'POST',
        url:'/selectoresModuloCalificacionPCL',
        data:datos_lista_causal_seguimiento,
        success:function(data){
            //console.log(data);
            let NombreCausalSeguimiento = $('select[name=causal_seguimiento]').val();
            let causalSeguimientoPCl = Object.keys(data);
            for (let i = 0; i < causalSeguimientoPCl.length; i++) {
                if (data[causalSeguimientoPCl[i]]['Id_causal'] != NombreCausalSeguimiento) {
                    $('#causal_seguimiento').append('<option value"'+data[causalSeguimientoPCl[i]]['Id_causal']+'">'+data[causalSeguimientoPCl[i]]['Nombre_causal']+'</option>');
                }                
            }
        }
    });
    */
    // llenado del formulario para la captura de la modal de Agregar Seguimiento

    $('#form_agregar_seguimientoJuntas').submit(function (e) {
        e.preventDefault(); 

        document.querySelector("#Guardar_seguimientos").disabled = true;  

        var fecha_seguimiento = $('#fecha_seguimiento').val();
        var causal_seguimiento = $('#causal_seguimiento').val();
        var descripcion_seguimiento = $('#descripcion_seguimiento').val();
        var newId_evento = $('#newId_evento').val();
        var newId_asignacion = $('#newId_asignacion').val();
        var Id_proceso = $('#Id_proceso').val();

        let token = $('input[name=_token]').val();
        
        var datos_agregarSeguimiento = {
            '_token': token,
            'newId_evento': newId_evento,
            'newId_asignacion': newId_asignacion,
            'Id_proceso': Id_proceso,
            'fecha_seguimiento': fecha_seguimiento,
            'causal_seguimiento': causal_seguimiento,
            'descripcion_seguimiento': descripcion_seguimiento,
        }

        $.ajax({
            type:'POST',
            url:'/registrarCausalSeguimientoJuntas',
            data: datos_agregarSeguimiento,
            success:function(response){
                if (response.parametro == 'agregar_seguimiento') {
                                     
                    $('.alerta_seguimiento').removeClass('d-none');
                    $('.alerta_seguimiento').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_seguimiento').addClass('d-none');
                        $('.alerta_seguimiento').empty();
                        $("#Guardar_seguimientos").removeClass('d-none');
                                             
                    }, 3000);
                }
            }
        })
        localStorage.setItem("#Guardar_seguimientos", true);
        location.reload();
    }) 
    // Abrir modal de agregar seguimiento despues de guardar 
    if (localStorage.getItem("#Guardar_seguimientos")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#Guardar_seguimientos");
        document.querySelector("#clicGuardado").click();
    }

     // Listar Historial de pagos
     $('#listado_agregar_seguimientos thead tr').clone(true).addClass('filters');
     $('#listado_agregar_seguimientos').DataTable({
         orderCellsTop: true,
         fixedHeader: true,
         initComplete: function () {
             var api = this.api();
                 // For each column
             api.columns().eq(0).each(function (colIdx) {
                 // Set the header cell to contain the input element
                 var cell = $('.filters th').eq(
                     $(api.column(colIdx).header()).index()
                 );
                 
                 var title = $(cell).text();
                 
                 if (title !== 'Acciones') {
                     
                     $(cell).html('<input type="text" />');
                     $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                     .on('change', function (e) {
                         // Get the search value
                         $(this).attr('title', $(this).val());
                         var regexr = '({search})'; //$(this).parents('th').find('select').val();
                         // Search the column for that value
                         api
                             .column(colIdx)
                             .search(
                                 this.value != ''
                                     ? regexr.replace('{search}', '(((' + this.value + ')))')
                                     : '',
                                 this.value != '',
                                 this.value == ''
                             )
                             .draw();
                     })
                     .on('keyup', function (e) {
                         e.stopPropagation();
                         var cursorPosition = this.selectionStart;
                         $(this).trigger('change');
                         $(this)
                             .focus()[0]
                             .setSelectionRange(cursorPosition, cursorPosition);
                     });
                 }
 
             });
         },
         /* dom: 'Bfrtip', */
         /* buttons:{
             dom:{
                 buttons:{
                     className: 'btn'
                 }
             },
             buttons:[
                 {
                     extend:"excel",
                     title: 'Lista pagos honorarios',
                     text:'Exportar datos',
                     className: 'btn btn-info',
                     "excelStyles": [                      // Add an excelStyles definition
                                                 
                     ],
                     exportOptions: {
                         columns: [1,2,3,4,5,6,7,8]
                     }
                 }
             ]
         }, */
         "language":{
             "search": "Buscar",
             "lengthMenu": "Mostrar _MENU_ resgistros por página",
             "info": "Mostrando página _PAGE_ de _PAGES_",
             "paginate": {
                 "previous": "Anterior",
                 "next": "Siguiente",
                 "first": "Primero",
                 "last": "Último"
             },
             "emptyTable": "No se encontró información",
             "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
         }
     });

     /* Validaciones para el rol Consulta cuando entra a la vista */
    if (idRol == 7) {
        // Desactivar todos los elementos excepto los especificados
        $(':input, select, a, button').not('#listado_roles_usuario, #his_servicios, #Hacciones, #botonVerEdicionEvento, #cargue_docs, #clicGuardado, #cargue_docs_modal_listado_docs, #abrir_agregar_seguimiento, #fecha_seguimiento, #causal_seguimiento, #descripcion_seguimiento, #Guardar_seguimientos, #botonFormulario2, .btn-danger, a[id^="EditarComunicado_"]').prop('disabled', true);
        $('#aumentarColAccionRealizar').addClass('d-none');
        $("#enlace_ed_evento").hover(function(){
            $("input[name='_token']").prop('disabled', false);
            $("#bandera_buscador_juntas").prop('disabled', false);
            $("#newIdEvento").prop('disabled', false);
            $("#newIdAsignacion").prop('disabled', false);
            $("#newIdproceso").prop('disabled', false);
            $("#newIdservicio").prop('disabled', false);
        });

        // Quitar el disabled al formulario oculto para permitirme ir al submodulo
        $("#llevar_servicio").hover(function(){
            $("input[name='_token']").prop('disabled', false);
            $("#Id_evento_juntas").prop('disabled', false);
            $("#Id_asignacion_juntas").prop('disabled', false);
            $("#Id_proceso_juntas").prop('disabled', false);
            $("#Id_Servicio").prop('disabled', false);
        });
        // Deshabilitar el botón Actualizar y Activar el botón Pdf en los comunicados
        $("#Pdf").prop('disabled', false);
    };

    /* Códigos para el tema del rol administrador (modelo a seguir) */
    // A los usuarios que no tengan el rol Administrador se les aplica los siguientes controles en el formulario de correspondencia:
    // inhabilita los campos nro anexos, asunto, etiquetas, cuerpo comunicado, firmar
    if (idRol != 6) {
        $("#asunto").prop('readonly', true);
        $("#asunto_editar").prop('readonly', true);
        $("#anexos").prop('readonly', true);
        $("#anexos_editar").prop('readonly', true);
        $(".note-editable").attr("contenteditable", false);

        $("#btn_insertar_nombre_junta_regional_asunto").prop('disabled', true);
        $("#btn_insertar_nombre_junta_regional").prop('disabled', true);
        $("#btn_insertar_nro_orden_pago").prop('disabled', true);
        $("#btn_insertar_fecha_notifi_afiliado").prop('disabled', true);
        $("#btn_insertar_fecha_radi_contro_pri_cali").prop('disabled', true);
        $("#btn_insertar_tipo_doc_afiliado").prop('disabled', true);
        $("#btn_insertar_documento_afiliado").prop('disabled', true);
        $("#btn_insertar_nombre_afiliado").prop('disabled', true);
        $("#btn_insertar_fecha_estructuracion").prop('disabled', true);
        $("#btn_insertar_tipo_evento").prop('disabled', true);
        $("#btn_insertar_nombres_cie10").prop('disabled', true);
        $("#btn_insertar_tipo_controversia_pri_cali").prop('disabled', true);
        $("#btn_insertar_direccion_afiliado").prop('disabled', true);
        $("#btn_insertar_telefono_afiliado").prop('disabled', true);
        $("#btn_insertar_correo_solicitud_info").prop('disabled', true);

        $("#btn_insertar_nombre_junta_regional_asunto_editar").prop('disabled', true);
        $("#btn_insertar_nombre_junta_regional_editar").prop('disabled', true);
        $("#btn_insertar_nro_orden_pago_editar").prop('disabled', true);
        $("#btn_insertar_fecha_notifi_afiliado_editar").prop('disabled', true);
        $("#btn_insertar_fecha_radi_contro_pri_cali_editar").prop('disabled', true);
        $("#btn_insertar_tipo_doc_afiliado_editar").prop('disabled', true);
        $("#btn_insertar_documento_afiliado_editar").prop('disabled', true);
        $("#btn_insertar_nombre_afiliado_editar").prop('disabled', true);
        $("#btn_insertar_fecha_estructuracion_editar").prop('disabled', true);
        $("#btn_insertar_tipo_evento_editar").prop('disabled', true);
        $("#btn_insertar_nombres_cie10_editar").prop('disabled', true);
        $("#btn_insertar_tipo_controversia_pri_cali_editar").prop('disabled', true);
        $("#btn_insertar_direccion_afiliado_editar").prop('disabled', true);
        $("#btn_insertar_telefono_afiliado_editar").prop('disabled', true);
        $("#btn_insertar_correo_solicitud_info_editar").prop('disabled', true);
        
        
        $("#firmarcomunicado").prop('disabled', true);
        $("#firmarcomunicado_editar").prop('disabled', true);
    }

    //Cargamos el historial de comunicados - Proceso para crear y generar lista de chequeo - PBS036
    let  get_comunicados = {
        '_token':token,
        'HistorialComunicadosOrigen': "CargarComunicados",
        'newId_evento':$('#newId_evento').val(),
        'newId_asignacion':$('#newId_asignacion').val(),
    }

    $.ajax({
        type:'POST',
        url:'/historialComunicadoJuntas',
        data: get_comunicados,
        success:function(data){
            // console.log(data.hitorialAgregarComunicado);            
            crear_Expediente(data.hitorialAgregarComunicado);
        }
    });

    // Detectar el cambio en el checkbox de la lista de chequeo en el th y Marcar todos
    $('#Marcar_todos_lista_chequeo').change(function() {
        // Verificar si está marcado o desmarcado
        var isChecked = $(this).is(':checked');  
        // validacion para habilitar o deshabilitar el boton 
        if (isChecked == true) {
            deshabilita_habilita = false;
        } else {
            deshabilita_habilita = true;            
            $(".posicion-foleo").val('');
        }
        // Cambiar el estado de los checkboxes en el tbody lista_documentos_check que no están deshabilitados
        $('#lista_documentos_check input[type="checkbox"]:not(:disabled)').prop('checked', isChecked);
        $(".actualizar_chequeo, .guardar_chequeo").prop('disabled', deshabilita_habilita);
        $(".posicion-foleo").prop('disabled', deshabilita_habilita);       

    });

    // Habilitar o deshabilitar el input posicion del checkbox al marcarlo individualmente según su id doc
    $(document).on('change', '.check_marcado', function() {
        // verificar si está marcado y capturar su atributo data del id documento
        var estacheck = $(this).is(':checked');
        if (estacheck) {
            let dataId_doc = $(this).data('id');                        
            // Iteramos sobre cada input con la clase 'posicion-foleo'
            $(".posicion-foleo").each(function () {
                let iddocumento = $(this).data('iddocumento');
                // validar si son iguales los atributos data
                if (iddocumento == dataId_doc) {
                    $(this).prop('disabled', false);                    
                }                  
            });
            // Iteramos sobre los otros check con la clase  check_marcado
            $(".check_marcado").each(function () {
                let iddocumento = $(this).data('id');
                // validar si son iguales los atributos data
                if (iddocumento == dataId_doc) {
                    $(this).prop('checked', estacheck);                    
                }                  
            });
        }else {
            let dataId_doc = $(this).data('id');                        
            // Iteramos sobre cada input con la clase 'posicion-foleo'
            $(".posicion-foleo").each(function () {
                let iddocumento = $(this).data('iddocumento');
                // validar si son iguales los atributos data
                if (iddocumento == dataId_doc) {
                    $(this).prop('disabled', true).val('');                                 
                }                  
            });
            // Iteramos sobre los otros check con la clase  check_marcado
            $(".check_marcado").each(function () {
                let iddocumento = $(this).data('id');
                // validar si son iguales los atributos data
                if (iddocumento == dataId_doc) {
                    $(this).prop('checked', estacheck);                    
                }                  
            });
        }
    });

    // poner el mismo valor para los inputs del mismo data-iddocumento cuando uno de ellos sea manipulado
    $(document).on('input', '.posicion-foleo', function() {
        // Obtener el valor del atributo data-iddocumento del input actual
        var idDocumento_data = $(this).data('iddocumento');
        
        // Obtener el valor que se está escribiendo
        var valorInput_data = $(this).val();
        
        // Seleccionar todos los inputs con la misma clase y mismo valor de data-iddocumento
        $('.posicion-foleo[data-iddocumento="' + idDocumento_data + '"]').val(valorInput_data);
    });    

    // Abrir modal una vez se genere la lista de chequeo
    if (localStorage.getItem("#Generar_lista_chequeo")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#Generar_lista_chequeo");
        document.getElementById("Cargando_expediente").style.display = "flex";
        document.querySelector("#clicGuardado").click();
        setTimeout(() => {
            document.querySelector("#crearExpediente").click();                    
            document.getElementById("Cargando_expediente").style.display = "none";
        }, 5000);
    }  

    // Validar si ya existe una lista de chequeo con el boton actualizar
    var Actualizar_chequeo = $('.actualizar_chequeo');
    if (Actualizar_chequeo.length > 0) {
        $(".titulo_lista_chequeo").attr("colspan", "5");
        $(".posicion_foleo").removeClass('d-none');
        $(".actualizar_chequeo").removeClass('d-none');
        $("#row_cuadro_expediente").removeClass('d-none');
    }
    
    // capturar el focus de la posicion que se va a manipular con su respectivo valor    
    $(document).on('focus', "input[id^='posicionFoleo_'], input[id^='posicion_expediente_']", function() {
        var InputvalorPosicion = $(this).val();              
        var InputdataIdDoc = $(this).data('iddocumento');
        // Eliminar o cerrar el evento anterior del focus
        $(document).off('input', "input[id^='posicionFoleo_'], input[id^='posicion_expediente_']");
        // Detectar el change de la posicion que se va a manipular en la cual se hizo el focus
        // Capturar su valor
        $(document).on('change', "input[id^='posicionFoleo_'], input[id^='posicion_expediente_']", function() {            
            var InputvalorPosicion_manipulado = $(this).val();            
            var InputdataIdDoc_manipulado = $(this).data('iddocumento');
            var atributoidPosicion = $(this).attr('id');
            // console.log(atributoidPosicion);            
            // si la posicion del focus es igual al valor que se ingresa o manipula se retorna
            if (InputvalorPosicion == InputvalorPosicion_manipulado) {
                // console.log('retornando igualdad');                
                return;
            } 
            // Validacion de campos en caso de que sea diferente al valor inicial
            else {
                // Validar si viene NaN o es igual o menor a cero se setea la posicion ingresada, se retorna
                if (isNaN(InputvalorPosicion_manipulado) || InputvalorPosicion_manipulado <= 0) {
                    $(this).val('');
                    return;
                }
    
                // variable de la posicion actual y variable para indicar si la alerta se muestra si esta duplicado
                var PosicionActual = $(this);                
                var valorDuplicado = false;
                // Se recorre los inputs y se validad el valor de la posicion de cada input y su id de documento
                $("input.posicion-foleo, input[id^='posicion_expediente_']").each(function() {                    
                    var valorPosicion = $(this).val();
                    var atributoidPosiciones = $(this).attr('id');
                    var dataIDoc = $(this).data("iddocumento");
                    // console.log(dataIDoc);                    
                    if (dataIDoc !== undefined) {
                        // si el valor de la posicion de los inputs encontrados es igual al valor que se ingreso y
                        // id del documento es diferete quiere decir que hay una posicon duplicada
                        if (valorPosicion === InputvalorPosicion_manipulado && dataIDoc !== InputdataIdDoc_manipulado) {                                             
                            valorDuplicado = true;
                            return false; // Salir del each si se encuentra duplicado
                        } 
                    } else {
                        if (atributoidPosicion != atributoidPosiciones) {
                            // console.log(atributoidPosiciones);                        
                            // si el valor de la posicion de los inputs encontrados es igual al valor que se ingreso y                            
                            if(valorPosicion === InputvalorPosicion_manipulado){                                              
                                valorDuplicado = true;
                                return false; // Salir del each si se encuentra duplicado
                            }                     
                        }                         
                    }                    
                });
                // si el valor duplicado es true muestra la alerta y setea el input de la posicion manipualdo
                if (valorDuplicado) {
                    // alert("Este valor ya ha sido ingresado. Debe ser único.");
                    Swal.fire({
                        title: 'Posición Duplicada',
                        text: 'Este valor ya ha sido ingresado. Debe ser único.',
                        icon: 'info',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#17A2B8'                        
                    });
                    PosicionActual.val('');                    
                }

                // Array para almacenar las posiciones de los inputs
                let posicionesValidar = [];
                // Recorre los inputs y guarda sus valores en el array posiciones
                $("input.posicion-foleo, input[id^='posicion_expediente_']").each(function() {
                    let valorPosicion = parseInt($(this).val(), 10); // Convierte a entero para facilitar la comparación
                    let dataIDoc = $(this).data("iddocumento");
                    if (valorPosicion >= 1) { // Asegura que el valor es mayor o igual a 1
                        if (!posicionesValidar[dataIDoc]) {
                            posicionesValidar[dataIDoc] = []; // Crea un array para este documento si no existe
                        }
                        posicionesValidar[dataIDoc].push(valorPosicion);
                    }
                });

                // Array para almacenar todas las posiciones únicas
                let posicionesUnicas = [];

                // Recorre los grupos por data-iddocumento
                for (let documento in posicionesValidar) {
                    // Filtra valores únicos dentro del grupo y los agrega al array de posiciones únicas
                    posicionesUnicas.push(...new Set(posicionesValidar[documento]));
                }

                // Ordena los valores de las posiciones en orden ascendente
                posicionesUnicas.sort((a, b) => a - b);

                // Variable para verificar la consecutividad
                let esConsecutivoPosicion = true;
                let valorFaltantePosicion = null;
                // Verifica que los valores sean consecutivos
                for (let i = 0; i < posicionesUnicas.length - 1; i++) {
                    if (posicionesUnicas[i + 1] !== posicionesUnicas[i] + 1) {
                        esConsecutivoPosicion = false;
                        valorFaltantePosicion = posicionesUnicas[i] + 1;
                        break;
                    }
                }

                if (esConsecutivoPosicion) {
                    // console.log("Los valores son consecutivos.");
                } else {
                    // console.log("Los valores no son consecutivos.");
                    if (valorDuplicado) {
                        setTimeout(() => {
                            Swal.fire({
                                title: 'No Consecutivas',
                                text: 'Los posiciones no son consecutivas, la posición que falta en la secuencia es: ' + valorFaltantePosicion,
                                icon: 'info',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#17A2B8'                        
                            });                            
                        }, 3000);
                    }else{
                        Swal.fire({
                            title: 'No Consecutivas',
                            text: 'Los posiciones no son consecutivas, la posición que falta en la secuencia es: ' + valorFaltantePosicion,
                            icon: 'info',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#17A2B8'                        
                        });
                        PosicionActual.val('');
                    }
                }
            }
        });
    });   
    
    //Accion para agregar o actualizar la posicion y foleo del la tabla expedientes
    $(document).on("click", "a[id^='editar_posicion_foleo_expediente_']",function(){
        // Remover la variable localStorage en caso de que se de mas click en el icono
        // localStorage.removeItem("#Generar_lista_expediente");
        $("a[id^='editar_posicion_foleo_expediente_']").prop('disabled', true);
        let validarDocumento_exp = true;
        // Recorrer todos los inputs de id 'posicion_expediente_' y verificar su estado
        $("select[id^='documento_expediente_']").each(function() {
            // Solo validar los inputs que no están deshabilitados
            if (!$(this).is(':disabled') && !$(this).val()) {
                // Mostrar un error visual
                $(this).addClass('is-invalid');  
                // Indicar que el formulario no es válido
                validarDocumento_exp = false;
            } else {
                // Quitar el error si es válido
                $(this).removeClass('is-invalid');
            }
        });
        // Si hay algún campo inválido, detener el proceso y mostrar mensaje
        if (!validarDocumento_exp) {
            Swal.fire({
                title: 'Campos de Documento',
                text: 'Por favor, llena todos los campos requeridos en la columna Documento tabla Expediente.',
                icon: 'info',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#17A2B8'                        
            });
            // Evitar continuar si no es válido
            return false;
        }

        let validarPosiciones_exp = true;
        // Recorrer todos los inputs de id 'posicion_expediente_' y verificar su estado
        $("input[id^='posicion_expediente_']").each(function() {
            // Solo validar los inputs que no están deshabilitados
            if (!$(this).is(':disabled') && !$(this).val()) {
                // Mostrar un error visual
                $(this).addClass('is-invalid');  
                // Indicar que el formulario no es válido
                validarPosiciones_exp = false;
            } else {
                // Quitar el error si es válido
                $(this).removeClass('is-invalid');
            }
        });
        // Si hay algún campo inválido, detener el proceso y mostrar mensaje
        if (!validarPosiciones_exp) {
            Swal.fire({
                title: 'Campos de Posición',
                text: 'Por favor, llena todos los campos requeridos en la columna Posición tabla Expediente.',
                icon: 'info',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#17A2B8'                        
            });
            // Evitar continuar si no es válido
            return false;
        }
        // construccion de data para envio al controlador
        let token = $('input[name=_token]').val();
        let id_expediente = $(this).data('id_expediente');
        if (id_expediente == 'documento_comunicado_expediente') {
            let id_contador_expediente = $(this).data('id_contador_expediente');
            var datos_expediente_posicion_foleo = {
                '_token': token,
                'Id_evento_expediente': Id_evento_expediente,
                'Id_asignacion_expediente': Id_asignacion_expediente,
                'Id_servicio_expediente': Id_servicio_expediente,
                'id_expediente': id_expediente,
                'documento': $("#documento_expediente_" + id_contador_expediente).val(),
                'posicion': $("#posicion_expediente_" + id_contador_expediente).val(),
                'folear': $("#folear_expediente_" + id_contador_expediente).val()
            };
        } else {            
            var datos_expediente_posicion_foleo = {
                '_token': token,
                'id_expediente': id_expediente,
                'posicion': $("#posicion_expediente_" + id_expediente).val(),
                'folear': $("#folear_expediente_" + id_expediente).val()
            };
        }
        
        $.ajax({
            type:'POST',
            url:'/insertarActualizarPosicionFoleos',
            data: datos_expediente_posicion_foleo,
            success:function(expediente_posicion_foleo){
                // console.log(expediente_posicion_foleo.Fallo_insercion);                
                if (expediente_posicion_foleo.Fallo_insercion == 'exito insercion') { 
                    $('#resultado_inseractua_expediente').removeClass('d-none');
                    $('#resultado_inseractua_expediente').addClass('alert-success');
                    $("#resultado_inseractua_expediente").append("<strong>" + expediente_posicion_foleo.mensaje + "</strong>");
                    setTimeout(() => {
                        $('#resultado_inseractua_expediente').removeClass('alert-success');
                        $('#resultado_inseractua_expediente').addClass('d-none');  
                        $('#resultado_inseractua_expediente').empty();
                        $("a[id^='editar_posicion_foleo_expediente_']").prop('disabled', false);
                        // Crear variable localStorage despues de manipular el icono 
                        // localStorage.setItem("#Generar_lista_expediente", true);
                    }, 3000);                   
                    
                } else if (expediente_posicion_foleo.Fallo_insercion == 'Fallo insercion'){
                    $('#resultado_inseractua_expediente').removeClass('d-none');
                    $('#resultado_inseractua_expediente').addClass('alert-danger');
                    $("#resultado_inseractua_expediente").append("<strong>" + expediente_posicion_foleo.mensaje + "</strong>");
                    setTimeout(() => {
                        $('#resultado_inseractua_expediente').removeClass('alert-danger');
                        $('#resultado_inseractua_expediente').addClass('d-none');  
                        $('#resultado_inseractua_expediente').empty();
                        $("a[id^='editar_posicion_foleo_expediente_']").prop('disabled', false);
                        // Crear variable localStorage despues de manipular el icono 
                        // localStorage.setItem("#Generar_lista_expediente", true);
                    }, 3000);
                }
                    
            }
        });
    })

    // Descargar documento de lista de chequeo
    $(document).on('click', 'a[id^="Descarga_listachequeo_"]', function() {
        var id_doc_expediente_chequeo = $(this).data('id_doc_expediente_chequeo');
        var nombre_documento_chequeo = $(this).data('nombre_documento_chequeo');
        var id_evento = $(this).data('id_evento');
        
        // Crear un enlace temporal para la descarga
        var enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = '/descargar-archivo/'+nombre_documento_chequeo+'/'+id_evento;
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

    // Remover filas y elmininar documento de la tabla de expedientes
    $(document).on('click', "a[id^='btn_remover_comunicado_expediente_']", function(){
        $("#generar_datos_expediente").prop('disabled', true);
        $("#generarNuevo_datos_expediente").prop('disabled', true);
        $("#CrearActualizarExpediente").prop('disabled', true);
        let token = $("input[name='_token']").val();
        var datos_fila_quitar_comunicado_expediente = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar"),
            'Id_evento_expediente': Id_evento_expediente,
            'Id_asignacion_expediente': Id_asignacion_expediente,
            'Id_servicio_expediente': Id_servicio_expediente,
        };
        
        $.ajax({
            type:'POST',
            url:'/eliminarComunicadosExpediente',
            data: datos_fila_quitar_comunicado_expediente,
            success:function(comunicado_expediente_eliminado){
                $('#resultado_inseractua_expediente').empty();
                $('#resultado_inseractua_expediente').removeClass('d-none');
                $('#resultado_inseractua_expediente').addClass('alert-success');
                $("#resultado_inseractua_expediente").append("<strong>" + comunicado_expediente_eliminado.mensaje + "</strong>");
                setTimeout(() => {
                    $('#resultado_inseractua_expediente').addClass('d-none');
                    $('#resultado_inseractua_expediente').removeClass('alert-success');
                    $('#resultado_inseractua_expediente').empty();
                }, 3000);

                setTimeout(() => {
                    // Array para almacenar las posiciones de los inputs
                    let posicionesValidar = [];
                    // Recorre los inputs y guarda sus valores en el array posiciones
                    $("input.posicion-foleo, input[id^='posicion_expediente_']").each(function() {
                        let valorPosicion = parseInt($(this).val(), 10); // Convierte a entero para facilitar la comparación
                        let dataIDoc = $(this).data("iddocumento");
                        if (valorPosicion >= 1) { // Asegura que el valor es mayor o igual a 1
                            if (!posicionesValidar[dataIDoc]) {
                                posicionesValidar[dataIDoc] = []; // Crea un array para este documento si no existe
                            }
                            posicionesValidar[dataIDoc].push(valorPosicion);
                        }
                    });               
                    
                    // Array para almacenar todas las posiciones únicas
                    let posicionesUnicas = [];
        
                    // Recorre los grupos por data-iddocumento
                    for (let documento in posicionesValidar) {
                        // Filtra valores únicos dentro del grupo y los agrega al array de posiciones únicas
                        posicionesUnicas.push(...new Set(posicionesValidar[documento]));
                    }
        
                    // Ordena los valores de las posiciones en orden ascendente
                    posicionesUnicas.sort((a, b) => a - b);
        
                    // Variable para verificar la consecutividad
                    let esConsecutivoPosicion = true;
                    let valorFaltantePosicion = null;
                    // Verifica que los valores sean consecutivos
                    for (let i = 0; i < posicionesUnicas.length - 1; i++) {
                        if (posicionesUnicas[i + 1] !== posicionesUnicas[i] + 1) {
                            esConsecutivoPosicion = false;
                            valorFaltantePosicion = posicionesUnicas[i] + 1;
                            break;
                        }
                    }
        
                    if (esConsecutivoPosicion) {
                        // console.log("Los valores son consecutivos.");
                        $("#generar_datos_expediente").prop('disabled', true);
                    } else {            
                        Swal.fire({
                            title: 'No Consecutivas',
                            text: 'Los posiciones no son consecutivas, la posición que falta en la secuencia es: ' + valorFaltantePosicion,
                            icon: 'info',
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#17A2B8'                        
                        });
                        $("#generar_datos_expediente").prop('disabled', true);                    
                    }
                }, 2000);
            }
        });
    });

    // Abrir modal una vez se de click en el boton de Actualizar Tabla Expediente
    // validando el localStorage Generar_lista_expediente
    $("#refrescar_expedientes").click(function(){  
        localStorage.setItem("#Generar_lista_expediente", true);
        $("#refrescar_expedientes").prop('disabled', true);
        setTimeout(() => {
            $("#refrescar_expedientes").prop('disabled', false);            
            location.reload();            
        }, 1000);
    });
    
    if (localStorage.getItem("#Generar_lista_expediente")) {            
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#Generar_lista_expediente");
        document.getElementById("Cargando_expediente").style.display = "flex";
        document.querySelector("#clicGuardado").click();
        setTimeout(() => {
            document.querySelector("#crearExpediente").click();                    
            document.getElementById("Cargando_expediente").style.display = "none";
            setTimeout(() => {
                // Array para almacenar las posiciones de los inputs
                let posicionesValidar = [];
                // Recorre los inputs y guarda sus valores en el array posiciones
                $("input.posicion-foleo, input[id^='posicion_expediente_']").each(function() {
                    let valorPosicion = parseInt($(this).val(), 10); // Convierte a entero para facilitar la comparación
                    let dataIDoc = $(this).data("iddocumento");
                    if (valorPosicion >= 1) { // Asegura que el valor es mayor o igual a 1
                        if (!posicionesValidar[dataIDoc]) {
                            posicionesValidar[dataIDoc] = []; // Crea un array para este documento si no existe
                        }
                        posicionesValidar[dataIDoc].push(valorPosicion);
                    }
                });               
                
                // Array para almacenar todas las posiciones únicas
                let posicionesUnicas = [];
    
                // Recorre los grupos por data-iddocumento
                for (let documento in posicionesValidar) {
                    // Filtra valores únicos dentro del grupo y los agrega al array de posiciones únicas
                    posicionesUnicas.push(...new Set(posicionesValidar[documento]));
                }
    
                // Ordena los valores de las posiciones en orden ascendente
                posicionesUnicas.sort((a, b) => a - b);
    
                // Variable para verificar la consecutividad
                let esConsecutivoPosicion = true;
                let valorFaltantePosicion = null;
                // Verifica que los valores sean consecutivos
                for (let i = 0; i < posicionesUnicas.length - 1; i++) {
                    if (posicionesUnicas[i + 1] !== posicionesUnicas[i] + 1) {
                        esConsecutivoPosicion = false;
                        valorFaltantePosicion = posicionesUnicas[i] + 1;
                        break;
                    }
                }
    
                if (esConsecutivoPosicion) {
                    // console.log("Los valores son consecutivos.");
                } else {            
                    Swal.fire({
                        title: 'No Consecutivas',
                        text: 'Los posiciones no son consecutivas, la posición que falta en la secuencia es: ' + valorFaltantePosicion,
                        icon: 'info',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#17A2B8'                        
                    });
                    $("#generar_datos_expediente").prop('disabled', true);                    
                }
            }, 2000);
        }, 5000);

    }    

    // Refrescar la pagina y abril modal de comunicado despues de haber actualizado el expdiente o creado uno nuevo    
    // validando el localStorage actualizar_lista_comunicados
    $("#refrescar_comunicados").click(function(){  
        localStorage.setItem("#actualizar_lista_comunicados", true);
        $("#refrescar_comunicados").prop('disabled', true);
        setTimeout(() => {
            $("#refrescar_comunicados").prop('disabled', false);            
            location.reload();            
        }, 1000);
    });
    
    if (localStorage.getItem("#actualizar_lista_comunicados")) {            
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#actualizar_lista_comunicados");
        document.querySelector("#clicGuardado").click();
    }

    // Generar expendiente
    $("#generar_datos_expediente, #generarNuevo_datos_expediente").click(function () {

        if ($(this).attr('id') === 'generar_datos_expediente') {
            $("#generar_datos_expediente").prop('disabled', true);
            $("#generarNuevo_datos_expediente").prop('disabled', true);
        } else if ($(this).attr('id') === 'generarNuevo_datos_expediente') {
            $("#generar_datos_expediente").prop('disabled', true);
            $("#generarNuevo_datos_expediente").prop('disabled', true);
            $("#IdExpediente_estado").val('');
        }

        setTimeout(() => {
            // Array para almacenar las posiciones de los inputs
            let posicionesValidar = [];
            // Recorre los inputs y guarda sus valores en el array posiciones
            $("input.posicion-foleo, input[id^='posicion_expediente_']").each(function() {
                let valorPosicion = parseInt($(this).val(), 10); // Convierte a entero para facilitar la comparación
                let dataIDoc = $(this).data("iddocumento");
                if (valorPosicion >= 1) { // Asegura que el valor es mayor o igual a 1
                    if (!posicionesValidar[dataIDoc]) {
                        posicionesValidar[dataIDoc] = []; // Crea un array para este documento si no existe
                    }
                    posicionesValidar[dataIDoc].push(valorPosicion);
                }
            });
                        
            // Array para almacenar todas las posiciones únicas
            let posicionesUnicas = [];

            // Recorre los grupos por data-iddocumento
            for (let documento in posicionesValidar) {
                // Filtra valores únicos dentro del grupo y los agrega al array de posiciones únicas
                posicionesUnicas.push(...new Set(posicionesValidar[documento]));
            }

            // Ordena los valores de las posiciones en orden ascendente
            posicionesUnicas.sort((a, b) => a - b);

            // Variable para verificar la consecutividad
            let esConsecutivoPosicion = true;
            let valorFaltantePosicion = null;
            // Verifica que los valores sean consecutivos
            for (let i = 0; i < posicionesUnicas.length - 1; i++) {
                if (posicionesUnicas[i + 1] !== posicionesUnicas[i] + 1) {
                    esConsecutivoPosicion = false;
                    valorFaltantePosicion = posicionesUnicas[i] + 1;
                    break;
                }
            }

            if (esConsecutivoPosicion) {
                // console.log("Los valores son consecutivos.");
                let token = $("input[name='_token']").val();
                var datos_generar_expediente = {
                    '_token': token,
                    'Id_evento_expediente': Id_evento_expediente,
                    'Id_asignacion_expediente': Id_asignacion_expediente,
                    'Id_servicio_expediente': Id_servicio_expediente,
                };

                $.ajax({
                    type: 'POST',
                    url: '/generarDatosExpedientes',
                    data: datos_generar_expediente,
                    success:function (respuesta_expediente) {
                        if (respuesta_expediente.parametro == "errorzip") {
                           
                            if (respuesta_expediente.vacio == "zip_vacio") {
                                Swal.fire({
                                    title: 'Error Zip',
                                    text: respuesta_expediente.mensaje,
                                    icon: 'info',
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: '#17A2B8'                        
                                });                                
    
                                // habilitar el botón del zip nuevamente
                                $("#generar_datos_expediente").prop('disabled', false);
    
                            }else{
                                Swal.fire({
                                    title: 'Error Zip',
                                    text: respuesta_expediente.mensaje,
                                    icon: 'info',
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: '#17A2B8'                        
                                });                                  
                            }
            
                        }else{
                            // Descarga del Archivo
                            window.location.href = respuesta_expediente.url;                            
        
                            // habilitar el botón del zip nuevamente
                            
                            if ($(this).attr('id') === 'generar_datos_expediente') {
                                $("#generar_datos_expediente").prop('disabled', false);
                            } else if ($(this).attr('id') === 'generarNuevo_datos_expediente') {
                                $("#generar_datos_expediente").prop('disabled', true);
                                $("#generarNuevo_datos_expediente").prop('disabled', true);                                
                            }
        
                            // Eliminar el archivo después de un tiempo de espera (por ejemplo, 10 segundos)
                            setTimeout(function() {
                                var datos_eliminar_expedientezip = {
                                    '_token': token,
                                    'nom_archivo': respuesta_expediente.nom_archivo
                                };
                                
                                $.ajax({
                                    type: 'POST',
                                    url: '/eliminarZipExpedientes',
                                    data: datos_eliminar_expedientezip,
                                    success: function(response) {
                                    }
                                });
                            }, 10000);
                            
                        }
                    }
                });
            } else {            
                Swal.fire({
                    title: 'No Consecutivas',
                    text: 'Los posiciones no son consecutivas, la posición que falta en la secuencia es: ' + valorFaltantePosicion,
                    icon: 'info',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#17A2B8'                        
                });
                $("#generar_datos_expediente").prop('disabled', false);                    
            }
        }, 2000);        
    });

    //insertar o eliminar las filas dinamicas del cuadro de expediente
    $(".centrar").css('text-align', 'center');
    var listado_lista_expedientes = $('#listado_lista_expedientes').DataTable({
        "responsive": true,
        "info": false,
        "searching": false,
        "ordering": false,
        "scrollCollapse": true,
        "scrollX": true,
        "scrollY": "30vh",
        "paging": false,
        "language":{
            "emptyTable": "No se encontró información"
        }
    });

    autoAdjustColumns(listado_lista_expedientes);

    var contador_expediente = 0;
    $('#btn_agregar_fila_expediente').click(function(){
        $('#guardar_datos_expediente').removeClass('d-none');

        contador_expediente = contador_expediente + 1;
        var nueva_fila_expediente = [
            '<select id="documento_expediente_'+contador_expediente+'" class="custom-select documento_expediente_'+contador_expediente+'" name="documento_expediente_"><option></option></select>',
            '<input type="text" class="form-control" id="posicion_expediente_'+contador_expediente+'" name="posicion_expediente" required />',
            '<select id="folear_expediente_'+contador_expediente+'" class="custom-select folear_expediente_'+contador_expediente+'" name="folear_expediente_"><option value="No">No</option><option value="Si">Si</option></select>',            
            '<div style="text-align:center;"><a href="javascript:void(0);" class="editar_posicion_foleo_expediente_'+contador_expediente+'" id="editar_posicion_foleo_expediente_'+contador_expediente+'" data-id_expediente = "documento_comunicado_expediente" data-id_contador_expediente = "'+contador_expediente+'" style="display: flex; justify-content: center;"><i class="fa fa-sm fa-check text-success"></i></a></div>',
            '<div style="text-align:center;"><a href="javascript:void(0);" id="btn_remover_fila_expediente" class="text-info" data-fila="fila_'+contador_expediente+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
            'fila_'+contador_expediente
        ];

        var agregar_fila_expediente = listado_lista_expedientes.row.add(nueva_fila_expediente).draw().node();
        $(agregar_fila_expediente).addClass('fila_'+contador_expediente);
        $(agregar_fila_expediente).attr("id", 'fila_'+contador_expediente);

        // Esta función realiza los controles de cada elemento por fila
        funciones_elementos_fila_expedientes(contador_expediente, Id_evento_expediente, Id_proceso_expediente, Id_asignacion_expediente);
        
    });

    $(document).on('click', '#btn_remover_fila_expediente', function(){
        var expedientes_filas = $(this).data("fila");
        listado_lista_expedientes.row("."+expedientes_filas).remove().draw();
    });

    $(document).on('click', "a[id^='btn_remover_comunicado_expediente_']", function(){
        var expedientes_filas = $(this).data("clase_fila");
        listado_lista_expedientes.row("."+expedientes_filas).remove().draw();
    });
    //Validacion duplicado documentos
    setTimeout(function() {
        radicados_duplicados('listado_comunicados_juntas');
    }, 500);

    // Función para añadir los controles de cada elemento de cada fila en la tabla de expediente
    function funciones_elementos_fila_expedientes(num_consecutivo, Id_evento_expediente, Id_proceso_expediente, Id_asignacion_expediente) {
        // Inicializacion de select 2
        // $("#folear_expediente_"+num_consecutivo).select2({
        //     width: '100%',
        //     placeholder: "Seleccione",
        //     allowClear: false
        // });

        $("#documento_expediente_"+num_consecutivo).select2({
            width: '100%',
            placeholder: "Seleccione",
            allowClear: false
        });
        
        //Carga de datos en los selectores

        let token = $("input[name='_token']").val();
        let datos_Comunicados_expediente = {
            '_token': token,
            'parametro' : "listado_Comunicados_expediente",
            'Id_evento_expediente' : Id_evento_expediente,
            'Id_proceso_expediente' : Id_proceso_expediente,
            'Id_asignacion_expediente' : Id_asignacion_expediente,
        };
        $.ajax({
            type:'POST',
            url:'/selectoresJuntas',
            data: datos_Comunicados_expediente,
            success:function(comunicado_expediente){
                let claves = Object.keys(comunicado_expediente);
                for (let i = 0; i < claves.length; i++) {
                    $("#documento_expediente_"+num_consecutivo).append('<option value="'+comunicado_expediente[claves[i]]["Nombre_documento"]+'">'+comunicado_expediente[claves[i]]["Nombre_documento"]+'</option>');
                }
            }
        });
    }
});
/**
 * Obtiene la información del afiliado seleccionado y la muestra en un elemento de destino.
 * @param {string} idSelector - El id del selector que desencadena el evento de cambio.
 * @param {string} append - El id del elemento donde se mostrará la información del afiliado.
 * @returns {void}
 */
function obtener_info_afiliado(idSelector,append) {
    $(idSelector).change(function(){
        let controvertido = {
            '_token': $("input[name='_token']").val(),
            'parametro' : 'info_afiliado',
            'controvierte' : $(this).find('option:selected').text(),
            'evento' : $("#Id_Evento").val()
        };

        $.ajax({
            type:'POST',
            url:'/selectoresJuntas',
            data: controvertido,
            success:function(data) {

                $(append).val(data.Nombre);
            }
        });
    });
}
/* SELECT 2 LISTADO SOLICITANTES */
function funciones_elementos_fila(num_consecutivo) {
    $("#lista_solicitante_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    // Cargue de listado de Solicitantes
    let token = $("input[name='_token']").val();
    let datos_consultar_solicitantes = {
        '_token': token,
        'parametro' : "listado_solicitantes",
    };
    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_consultar_solicitantes,
        success:function(data){
            // $("select[id^='lista_docs_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_solicitante_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });
}

// Habilita los botones de acciones de acuerdo si hay algo chequeado o no.
function verificarCheckboxes() {    
    let hayCheckboxMarcado = false;    
    // Iterar sobre cada checkbox
    $("#lista_documentos_check :checkbox").each(function() {       
        if ($(this).is(':checked')  &&  $(this).data('nombre') !== 'Lista de chequeo') {
            hayCheckboxMarcado = true;
            return false; // Salir del bucle una vez que se encuentre al menos uno marcado
        }
    });
    // Habilitar o deshabilitar los botones según la variable hayCheckboxMarcado
    $(".actualizar_chequeo, .guardar_chequeo").prop('disabled', !hayCheckboxMarcado);
}

/**
 * Funcion para procesar el flujo de la creacion de un expediente y la lista de chequeo
 * @param {Array} data Comunicados de Historial de comunicados y expedientes
 */
function crear_Expediente(data) {
    // Configurar select2
    $("#historial_comunicados").select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });


    // Abrimos el modal de crear expediente, si data no esta definimo mostramos la alerta
    $("#crearExpediente, #editarExpediente").on('click', function() {

        //Mostrar alerta si se debe actualizar la lista de chequeo
        var validarChequeo = $('#Alerta_chequeo');
        if (validarChequeo.length > 0) {
            Swal.fire({
                title: 'Actualizar Lista de chequeo',
                text: 'Se agregaron o se eliminarion documentos a la Lista de chequeo',
                icon: 'info',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#17A2B8'                        
            });
        }
 
        if ($.isEmptyObject(data)) {
            $("#form_crear_ExpedientesJuntas, #info_expediente").addClass('d-none');
            $("#alert_expediente").removeClass('d-none');
        } else {
            // Actualizar la lista de comunicados
            actualizarComunicados(data);               
        }
    });    

    // Desactiva el boton de generar chequeo si no hay nada chequeado
    $("#lista_documentos_check").on('change', ':checkbox', function() {
        // Contadores de los checkbox
        let totalCheckbox = 0;
        let checkboxMarcados = 0;
        // recorrer los check para validar si todos estan marcados
        $('#lista_documentos_check input[type="checkbox"]:not(:disabled)').each(function() {
            // Incrementa el contador total de checkbox
            totalCheckbox++; 
            if ($(this).prop('checked')) {
                // Incrementa el contador de checkbox marcados
                checkboxMarcados++; 
            }
        });

        // validacion para marcar y desmarcar el checkbox general 
        if (totalCheckbox == checkboxMarcados) {
            $("#Marcar_todos_lista_chequeo").prop('checked', true);            
        }else{
            $("#Marcar_todos_lista_chequeo").prop('checked', false);            
        }
        
        verificarCheckboxes();
    });

    // Disparamos el proceso para crear la lista de chequeo
    $(".actualizar_chequeo, .guardar_chequeo").on('click', function() { 
        let accion = $(this).attr('data-accion');
        procesarListaChequeo(accion);
    });

}

// Función para limpiar y agregar comunicados al select2
function actualizarComunicados(data) {
    
    //Quita cualquier extension del comunicado.
    // let nombre_proforma = data.map(item => {
    //     // Verifica si el asunto incluye 'Lista_chequeo'
    //     if (item.Asunto.includes('Lista_chequeo')) {
    //         return {
    //             nombre: item.Asunto,
    //             id_comunicado: item.Id_Comunicado
    //         };
    //     } else {
    //         // Si el asunto no incluye 'Lista_chequeo', retorna null para filtrarlo después
    //         return null;
    //     }
    // }).filter(item => item !== null);

    // Limpiar opciones existentes antes de agregar nuevas
    // $("#historial_comunicados").empty();

    // Agregar las opciones al select2
    //comunicados.forEach(item => {
       // $("#historial_comunicados").append(`<option value='${item.id_comunicado}'>${item.nombre}</option>`);
    //});

    // // Actualizar select2 después de agregar opciones
    // $("#historial_comunicados").trigger('change');

    //Cargamos la tabla con la lista de chequeo
    get_documentos_para_check();

    // if(nombre_proforma == ''){
    //     return null;
    // }

    //habilitamos la lista de chequeo en caso de que ya exista una en las comunicaciones
    // $('#ver_chequeo').removeClass('d-none');
    // $('#ver_chequeo a').attr('id','generar_descarga_archivo_' + $('#newId_evento').val());
    // $('#ver_chequeo a').attr('asunto_comunicado',nombre_proforma[0].nombre);
    // $('#ver_chequeo a').attr('id_evento',$('#newId_evento').val());

    // $(".actualizar_chequeo").removeClass('d-none');
    // $(".guardar_chequeo").addClass('d-none');

}
/**
 * Funcion para setear los documentos para chequear en la tabla Lista de chequeo
 * @param {Array} comunicados Comunicados cargados en Historial de comunicados y expedientes
 */
function get_documentos_para_check() {

    // Validar si ya existe una lista de chequeo con el boton actualizar
    var Actualizar_chequeo = $('.actualizar_chequeo');   
        
    const get_documentos = {
        '_token': $("input[name='_token']").val(),
        'parametro': "listado_documentos",
        'Id_evento': $('#newId_evento').val(),
        'Id_servicio': $("#Id_servicio").val(),
        'Id_asignacion' : $('#newId_asignacion').val(),
    };

    // Limpiamos la tabla antes de agregar nuevos elementos
    $("#lista_documentos_check").empty();

    $.ajax({
        type: 'POST',
        url: '/selectoresJuntas',
        data: get_documentos,
        success: function (data) {
            let n_documentos = 0;
            // console.log(data.status_doc);            
            // Iterar sobre los documentos
            $.each(data, function(key, item) {
                if (key !== "comunicados" ) {
                    n_documentos++;
                    let status_doc, checkbox, posicion;
                                        
                    if (item.status_doc === "No Cargado") {
                        status_doc = `<strong class="text-danger">${item.status_doc}</strong>`;
                        checkbox = '<input class="scales" type="checkbox" disabled>';
                        // columna de posicion para agregar en el tbody
                        if (Actualizar_chequeo.length > 0) {                            
                            posicion = '<input class="form-control" type="number" id="posicionFoleo" disabled>';
                        }

                    } else if (item.status_doc === "Cargado") {                        
                        status_doc = `<strong class="text-success">${item.status_doc}</strong>`;
                        checkbox = `<input class="scales check_marcado" type="checkbox" data-id='${item.Id_Documento}' data-nombre='${item.doc_nombre}' data-idregistrodoc='${item.id_Registro_Documento}' data-nombrearchivo='${item.archivo}'>`;
                        if (Actualizar_chequeo.length > 0) {                            
                            posicion = `<input class="form-control posicion-foleo" type="number" id="posicionFoleo_${item.id_Registro_Documento}" data-idDocumento='${item.Id_Documento}' data-nombre='${item.doc_nombre}' data-idRegistroDoc='${item.id_Registro_Documento}' data-nombreArchivo='${item.archivo}' disabled>`;
                        }
                        
                        if(item.check == 'Si'){
                            checkbox = `<input class="scales check_marcado" type="checkbox" data-id='${item.Id_Documento}' data-nombre='${item.doc_nombre}' data-idregistrodoc='${item.id_Registro_Documento}' data-nombrearchivo='${item.archivo}' checked>`;
                            // columna de posicion para agregar en el tbody
                            if (Actualizar_chequeo.length > 0) {                                
                                posicion = `<input class="form-control posicion-foleo" type="number" id="posicionFoleo_${item.id_Registro_Documento}" data-idDocumento='${item.Id_Documento}' data-nombre='${item.doc_nombre}' data-idRegistroDoc='${item.id_Registro_Documento}' data-nombreArchivo='${item.archivo}'>`; 
                            }
                            // console.log(posicion);                                                                                 
                        }

                        if(item.doc_nombre ==  'Lista de chequeo'){
                            checkbox = `<input class="scales" type="checkbox" data-id='${item.Id_Documento}' data-nombre='${item.doc_nombre}' checked disabled>`;
                            // columna de posicion para agregar en el tbody
                            if (Actualizar_chequeo.length > 0) {
                                posicion = `<input class="form-control posicion-lista_chequeo" type="number" id="posicionFoleo" data-id='${item.Id_Documento}' data-nombre='${item.doc_nombre}' disabled>`;
                            }                            
                        }
                    }

                    if (Actualizar_chequeo.length > 0) {
                        let html_documentos = `<tr>
                            <td>${n_documentos}</td>
                            <td>${item.doc_nombre}</td>
                            <td>${status_doc}</td>
                            <td>${checkbox}</td>
                            <td>${posicion}</td>
                        </tr>`;
                        $("#lista_documentos_check").append(html_documentos);

                    }else {
                        let html_documentos = `<tr>
                            <td>${n_documentos}</td>
                            <td>${item.doc_nombre}</td>
                            <td>${status_doc}</td>
                            <td>${checkbox}</td>                            
                        </tr>`;
                        $("#lista_documentos_check").append(html_documentos);
                    }


                }                
            });

            // Iterar sobre los comunicados
            $.each(data.comunicados, function(index, item) {
                n_documentos++;
                checkbox = `<input class="scales" type="checkbox" data-id='${n_documentos}' data-nombre='${item.nombre}' data-idcomunicado='${item.Id_Comunicado}' disabled>`;
                if (Actualizar_chequeo.length > 0) {
                    let html_comunicado = `<tr>
                        <td>${n_documentos}</td>
                        <td>${item.nombre}</td>
                        <td><strong class="text-info">Comunicado</strong></td>
                        <td>${checkbox}</td>
                        <td>${posicion}</td>
                    </tr>`;
                    $("#lista_documentos_check").append(html_comunicado);
                }else {
                    let html_comunicado = `<tr>
                        <td>${n_documentos}</td>
                        <td>${item.nombre}</td>
                        <td><strong class="text-info">Comunicado</strong></td>
                        <td>${checkbox}</td>                        
                    </tr>`;
                    $("#lista_documentos_check").append(html_comunicado);
                }

            });   
            
            // llenamos los valores de los inputs de posicion 
            // Consultar el valor de la posicion en la tabla de expedientes
            let datos_expediente = {
                '_token': $("input[name='_token']").val(),
                'parametro': "posicion_expediente",
                'Id_evento': $('#newId_evento').val(),
                'Id_servicio': $("#Id_servicio").val(),
                'Id_asignacion' : $('#newId_asignacion').val(),
            }                                        
            $.ajax({
                type: 'POST',
                url: '/selectoresJuntas',
                data: datos_expediente,
                success: function (posicion_expe) {                                                                                                                                          
                    // Iteramos sobre cada input con la clase 'posicion-foleo' para asignar valores
                    $(".posicion-foleo").each(function () {
                        let iddocumento = $(this).data('iddocumento');
                        // Buscamos la posición correspondiente en la respuesta del AJAX que sea igual al Id_Documento
                        let posicionEncontrada = posicion_expe.find(item => item.Id_Documento == iddocumento);                        
                        // Si encontramos la posición, la asignamos al input su valor consultado
                        if (posicionEncontrada) {
                            $(this).val(posicionEncontrada.Posicion);
                        }
                    }); 
                    // Identificamos cuales son los inputs que quedaron con un valor
                    $(".posicion-foleo").each(function () {
                        // Verificamos si el input tiene un valor
                        if ($(this).val()) {
                            // Capturamos el valor del atributo 'data-iddocumento'
                            var idDocumento = $(this).data('iddocumento');
                            $(this).prop('disabled', false);  
                            // Iteramos sobre los otros check con la clase  check_marcado
                            $(".check_marcado").each(function () {
                                // capturamos su id doc 
                                var id_doc = $(this).data('id');
                                let estacheck = true;
                                // validamos si son iguales para dejarlos checkeados
                                if (idDocumento == id_doc) {
                                    $(this).prop('checked', estacheck);                                                        
                                }                          
                            });
                            
                        }
                    });
                }
            });

            // Asignar valor del input de la poisicion del documento lista de chequo del expediente
            // una vez que el elemento está en el DOM al input que esta disabled de la lista de chequeo
            var posicion_expediente_15 = $('.posicion-expediente_15');
            if (posicion_expediente_15.length > 0) {
                var posicion_doc_15 = posicion_expediente_15.val();                                                                            
                $('.posicion-lista_chequeo').val(posicion_doc_15);
            }

            // Disparar evento de cambio después de actualizar la tabla
            $("#lista_documentos_check").trigger('change');

            verificarCheckboxes();
        }
    });
}

/**
 * Procesa y registra todos los comunicados que esten chequeados.
 * @param {string} accion Accion a ejecutar (Actualizar, Guardar) 
 */
function procesarListaChequeo(accion){

    // console.log(accion);
    
    if (accion == 'Actualizar') {
        
        let validarPosiciones = true;
    
        // Recorrer todos los inputs de clase 'posicion-foleo' y verificar su estado
        $(".posicion-foleo").each(function() {
            // Solo validar los inputs que no están deshabilitados
            if (!$(this).is(':disabled') && !$(this).val()) {
                // Mostrar un error visual
                $(this).addClass('is-invalid');  
                // Indicar que el formulario no es válido
                validarPosiciones = false;
            } else {
                // Quitar el error si es válido
                $(this).removeClass('is-invalid');
            }
        });
    
        // Si hay algún campo inválido, detener el proceso y mostrar mensaje
        if (!validarPosiciones) {
            // alert("Por favor, llena todos los campos requeridos en la columna Posición.");
            Swal.fire({
                title: 'Campos de Posición',
                text: 'Por favor, llena todos los campos requeridos en la columna Posición tabla Lista de chequeo.',
                icon: 'info',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#17A2B8'                        
            });
            // Evitar continuar si no es válido
            return false;  
        }
        // console.log("Procesando lista de chequeo con acción: " + accion);
    }

    let registrarChequeo = {
        '_token': $("input[name='_token']").val(),
        'Id_evento' : $('#newId_evento').val(),
        'Id_proceso' : $('#Id_proceso').val(),
        'bandera' : accion,
        'Id_asignacion' : $('#newId_asignacion').val(),
        'Id_servicio': $("#Id_servicio").val(),
        'cliente': $("#cliente").val(),
        'afiliado': $("#nombre_afiliado").val(),
        'identificacion':  $("#identificacion").val(),
        't_documento' :  $("#tipo_documento_comunicado").val(),
    }
    let datos = [];

    $(".actualizar_chequeo, .guardar_chequeo").prop('disabled',true);

    $('#lista_documentos_check :checkbox').each(function() {
        if ($(this).is(':checked') &&  $(this).data('nombre') !== 'Lista de chequeo') {

            //Documentos generales que esten check
            let lista_chequeo = {
                'id_doc' : $(this).data('id'),
                'statusDoc' :  'Cargado',
                'nombreDoc' : $(this).data('nombre'),
                'idRegisDoc' : $(this).data('idregistrodoc'),
                'nombreArchi' : $(this).data('nombrearchivo'),

            };

            /*if($(this).data('idcomunicado')){
                lista_chequeo['idComunicado'] = $(this).data('idcomunicado');
                lista_chequeo['statusDoc'] = 'Comunicado';
            }*/
            datos.push(lista_chequeo);
        }
    });

    // console.log(datos);

    if (accion == 'Actualizar') {
        
        // Iterar sobre los inputs y actualizar el array
        datos.forEach((documento) => {
            // Encontrar el input correspondiente en el DOM por id_doc
            let inputPosicion = $(`input[data-idDocumento="${documento.id_doc}"]`);
            // Si existe el input en el DOM, capturar su valor
            if (inputPosicion.length > 0) {
                // Obtener el valor del input y su data (no lo trae de manera correcta debido a que la consulta solo a apunto a los nombre de los documento de homologación)
                let valorPosicion = inputPosicion.val();  
                // let id_registroDoc = inputPosicion.attr('data-idRegistroDoc');
                // let nombrearchivo =  inputPosicion.attr('data-nombreArchivo');
                // Asignar el valor capturado a una nueva propiedad en el objeto del array
                documento.posicion = valorPosicion;
                // documento.id_registro_docu = id_registroDoc;
                // documento.nombre_archivo = nombrearchivo;
            }
        });
    
        // console.log(datos);
    }
    
    // Ahora, el array `datos` tendrá los valores actualizados de los inputs
    registrarChequeo['lista_chequeo'] = datos;
    // console.log(registrarChequeo);

    $.ajax({
        type: 'POST',
        url: '/registrarListaChequeo',
        data: registrarChequeo,
        dataType: 'json',
        success: function(response){
            if (response.parametro == 'agregar_lista_chequeo') {

                $('.alerta_chequeo').removeClass('d-none');
                $('.alerta_chequeo').append('<strong>'+response.message+'</strong>');

                //Agregamos los atributos necesarios para poder descargar el archivo mediante el proceso de 'generar_descarga_archivo_'
                $('#ver_chequeo').removeClass('d-none');
                $('#ver_chequeo a').attr('id','generar_descarga_archivo_' + $('#newId_evento').val());
                $('#ver_chequeo a').attr('asunto_comunicado',response.nombre_proforma);
                $('#ver_chequeo a').attr('id_evento',$('#newId_evento').val());

                $(".actualizar_chequeo").prop('disabled',false);
                $(".guardar_chequeo").addClass('d-none');
                $(".actualizar_chequeo").removeClass('d-none');
                setTimeout(function(){
                    $('.alerta_chequeo').addClass('d-none');
                    $('.alerta_chequeo').empty();                                       
                    localStorage.setItem("#Generar_lista_chequeo", true);
                    location.reload(); 
                }, 3000);
            }             
        },
        error: function(response){
            //Muestra un mensaje en caso de error, ya sea mediante el coontroaldor o fallse en algun proceso de laravel
            response = response.responseJSON;

            $('.error_chequeo').removeClass('d-none');
            $('.error_chequeo').append('<strong>'+response.message+'</strong>');

            $(".actualizar_chequeo, .guardar_chequeo").prop('disabled',false);

            setTimeout(function(){
                $('.error_chequeo').addClass('d-none');
                $('.error_chequeo').empty();                  
            }, 3000);
        }
    })

}
