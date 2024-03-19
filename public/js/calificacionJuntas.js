$(document).ready(function(){

    var idRol = $("#id_rol").val();

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

    // llenado de selectores
    let token = $('input[name=_token]').val();

    /* FUNCIONALIDAD DESCARGA DOCUMENTO */
    $("a[id^='btn_generar_descarga_']").click(function(){
        var id_documento = $(this).data('id_documento_descargar');
        var nombre_documento = $("#nombre_documento_descarga_"+id_documento).val();
        var extension_documento = $("#extension_documento_descarga_"+id_documento).val();
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

    //Listado de profesional    

    let datos_lista_profesional={
        '_token':token,
        'parametro':"lista_profesional_proceso",
        'id_proceso' : Id_proceso_actual,
    }

    $.ajax({
        type:'POST',
        url:'/selectoresJuntas',
        data: datos_lista_profesional,
        success:function (data) {
            // console.log(data)
            let id_profesional= $('select[name=profesional]').val();
            let profecionalpcl = Object.keys(data);
            for (let i = 0; i < profecionalpcl.length; i++) {
                if (data[profecionalpcl[i]]['id'] != id_profesional) {
                    $('#profesional').append('<option value="'+data[profecionalpcl[i]]['id']+'">'+data[profecionalpcl[i]]['name']+'</option>')                    
                }
            }
        }
    });

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
                    if (data[claves[i]]["Id_Accion"] == $("#bd_id_accion").val()) {
                        $("#accion").append('<option value="'+data[claves[i]]["Id_Accion"]+'" selected>'+data[claves[i]]["Nombre_accion"]+'</option>');
                    } else {
                        $("#accion").append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Nombre_accion"]+'</option>');
                    }
                }
                
                $(".no_ejecutar_parametrica_modulo_principal").addClass('d-none');
                $("#Edicion").removeClass('d-none');
            }else{
                $("#accion").empty();
                $("#accion").append('<option></option>');

                $(".no_ejecutar_parametrica_modulo_principal").removeClass('d-none');
                $("#Edicion").addClass('d-none');
            }
        }
    });

    $("#accion").change(function(){
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
                    } else {
                        $(".no_ejecutar_parametrica_modulo_principal").addClass('d-none');
                        $("#Edicion").removeClass('d-none');
                    }
                }
            
            }
        });
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

    // llenado del formulario para la captura de datos del modulo de Juntas
    $('#form_calificacionJuntas').submit(function (e) {
        e.preventDefault();  
        // Deshabilitar elementos mientras se realiza la petición                
        document.querySelector("#Edicion").disabled = true;
        document.querySelector("#Borrar").disabled = true;

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
        // formData.append('f_accion', $('#f_accion').val());
        formData.append('accion', $('#accion').val());
        formData.append('fecha_alerta', $('#fecha_alerta').val());
        formData.append('enviar', $('#enviar').val());
        formData.append('profesional', $('#profesional').val());
        formData.append('descripcion_accion', $('#descripcion_accion').val());
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
                        $('.alerta_calificacion').append('<strong>'+response.mensaje_1+'</strong>');
                    } else {
                        $('.alerta_calificacion').append('<strong>'+response.mensaje+'</strong>');
                    }
                    setTimeout(function(){
                        $('.alerta_calificacion').addClass('d-none');
                        $('.alerta_calificacion').empty(); 
                        location.reload();                       
                    }, 3000);
                }                
            }
        })        
        // location.reload();
    }) 
    /* Obtener el ID del evento a dar clic en cualquier botón de cargue de archivo y asignarlo al input hidden del id evento */
    $("input[id^='listadodocumento_']").click(function(){
        let idobtenido = $('#newId_evento').val();
        //console.log(idobtenido);
        $("input[id^='EventoID_']").val(idobtenido);
    });
    /* Envío de Información del Documento a Cargar */
    $("form[id^='formulario_documento_']").submit(function(e){

        e.preventDefault();
        var formData = new FormData($(this)[0]);
        var cambio_estado = $(this).parents()[1]['children'][2]["id"];
        var input_documento = $(this).parents()[0]['children'][0][4]["id"];

        //for (var pair of formData.entries()) {
        //   console.log(pair[0]+ ', ' + pair[1]); 
        //}
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
            'f_notifi_afiliado': $('#f_notifi_afiliado').val(),
            'f_contro_primer_califi': $('#f_contro_primer_califi').val(),
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
                $("#div_input_jrci_copia").addClass('d-none');
                $("#div_select_jrci_copia").removeClass('d-none');

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
                                $('#jrci_califi_invalidez_comunicado').append('<option>Seleccione una opción</option>');
                                let juntajrci = Object.keys(data);
                                for (let i = 0; i < juntajrci.length; i++) {
                                    // if (data[juntajrci[i]]['Id_Parametro'] != IdJuntaInvalidez) {  
                                    // }
                                    $('#jrci_califi_invalidez_comunicado').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                                }
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
                                }
                            });
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
                }else if(data.destinatarioPrincipal == 'Empresa'){      
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
        // callbacks: {
        //     onInit: function() {
        //         // Ajusta la longitud máxima de la línea para la tokenización
        //         $('#cuerpo_comunicado').summernote('option', 'editor.maxTokenizationLineLength', 2000); // Puedes ajustar el valor según tus necesidades
        //     }
        // }
    });

    $("#cuerpo_comunicado_editar").summernote({
        height: 'auto',
        toolbar: false
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
            var texto_insertar = "<p>Cordial saludo:</p><p>Dando respuesta a inconformidad presentada frente a calificación de la Pérdida de la Capacidad Laboral emitida por esta aseguradora, dentro de los términos estipulados en la normatividad vigente, se informa: que hoy los documentos concernientes a su caso han sido enviados a la {{$nombre_junta}}, la cual, le citará a la valoración correspondiente.</p><p>Recuerde por favor, que las Juntas Regionales de Calificación de Invalidez son entidades independientes y autónomas de las compañías de seguros, cuyos procesos y procedimientos son ajenos a nuestra entidad, por lo tanto, cualquier requerimiento ante la misma debe ser tratado directamente con la Junta Regional de Calificación de Invalidez.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

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

            $("#asunto").val('');
            $('#cuerpo_comunicado').summernote('code', '');

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
            var texto_insertar = "<p>Respetados señores, cordial saludo:</p><p>En aras de tramitar el recurso y/o controversia presentada en tiempo por la parte interesada contra el dictamen de calificación de PÉRDIDA DE CAPACIDAD LABORAL, remitimos el expediente del afiliado con la documentación exigida por el artículo 30 del Decreto 1352 de 2013 (historia clínica, constancia de notificación, dictamen médico laboral, controversia, etc.) para su valoración.</p><p>Según lo dispone el artículo 20 del mismo decreto, el valor de los honorarios corresponde a un (1) salario mínimo mensual legal vigente, el cual fue cancelado por esta aseguradora. Para los efectos, adjuntamos orden de pago de honorarios No {{$nro_orden_pago}}.</p><p>Finalmente, indicamos que la fecha de notificación del dictamen lo fue el {{$fecha_notificacion_afiliado}} y la dedicación del desacuerdo el {{$fecha_radicacion_controversia_primera_calificacion}}, razón por la cual es procedente tramitar el recurso.</p><p>Los datos del afiliado son los siguientes:</p><table class='tabla_cuerpo_remision_expediente'><tbody><tr><td class='bg'><b>TIPO Y No. DE IDENTIFICACIÓN</b></td><td><p>{{$tipo_documento_afiliado}}{{$documento_afiliado}}<br></p></td></tr><tr><td class='bg'><b>NOMBRE COMPLETO</b></td><td><p>{{$nombre_afiliado}}<br></p></td></tr><tr><td class='bg'><b>EVENTO</b></td><td><p>{{$fecha_estructuracion}} {{$tipo_evento}}<br></p></td></tr><tr><td class='bg'><b>DIAGNÓSTICO</b></td><td><p>{{$nombres_cie10}}<br></p></td></tr><tr><td class='bg'><b>CONTROVERSIA POR</b></td><td><p>{{$tipo_controversia_primera_calificacion}}<br></p></td></tr><tr><td class='bg'><b>DIRECCIÓN Y TELÉFONO DEL ASEGURADO</b></td><td><p>{{$direccion_afiliado}} {{$telefono_afiliado}}<br></p></td></tr><tr><td class='bg'><b>OBSERVACIONES</b></td><td><br></td></tr></tbody></table><p>En virtud de lo señalado en el Artículo 2 del Decreto 1352 de 2013 que establece:</p><p>Artículo 2. Personas interesadas. Para efectos del presente decreto, se entenderá como personas interesadas en el dictamen y de obligatoria notificación o comunicación como mínimo las siguientes:</p><ul><li><span style=''>La persona objeto de dictamen o sus beneficiarios en caso de muerte.</span></li><li><span style=''>La Entidad Promotora de Salud.</span></li><li><span style=''>La Administradora de Riesgos Laborales.</span></li><li><span style=''>La Administradora del Fondo de Pensiones o Administradora de Régimen de Prima Media.</span></li><li><span style=''>El Empleador.</span></li><li><span style=''>La Compañía de Seguro que asuma el riesgo de invalidez, sobrevivencia y muerte. (Subrayado fuera del texto original).</span></li></ul><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

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
            <p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras l íneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>\
            ";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);

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

        }
    });

    // llenado del formulario para la captura de la modal de Generar Comunicado
    $('#form_generarComunicadoJuntas').submit(function (e) {
        e.preventDefault();  

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

        $('input[type="checkbox"]').each(function() {
            var copiaComunicado = $(this).attr('id');            
            if (copiaComunicado === 'copia_afiliado' || copiaComunicado === 'copia_empleador' || 
                copiaComunicado === 'copia_eps' || copiaComunicado === 'copia_afp' || 
                copiaComunicado === 'copia_arl' || copiaComunicado === 'copia_jrci' || copiaComunicado === 'copia_jnci') {                
                if ($(this).is(':checked')) {                
                var relacionCopiaValor = $(this).val();
                copiaComunicadoTotal.push(relacionCopiaValor);
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
            'radiojrci_comunicado': radiojrci_comunicado,
            'JRCI_Destinatario': JRCI_Destinatario,  
            'radiojnci_comunicado': radiojnci_comunicado,        
            'radioafiliado_comunicado':radioafiliado_comunicado,
            'radioempresa_comunicado':radioempresa_comunicado,
            'radioeps_comunicado': radioeps_comunicado,
            'radioafp_comunicado': radioafp_comunicado,
            'radioarl_comunicado': radioarl_comunicado,
            'radioOtro':radioOtro,
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
            'tipo_descarga': tipo_descarga
        }
        
        document.querySelector("#Generar_comunicados").disabled = true;   
        $.ajax({
            type:'POST',
            url:'/registrarComunicadoJuntas',
            data: datos_generarComunicado,            
            success:function(response){
                if (response.parametro == 'agregar_comunicado') {
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

            for (let i = 0; i < data.length; i++) {                             
                
                if (data[i]['N_radicado'] != ''){

                    comunicadoNradico = '<a href="javascript:void(0);" class="text-dark" data-toggle="modal" data-target="#modalcomunicados_" id="EditarComunicado_'+data[i]["Id_Comunicado"]+'" title="Editar Comunicado"\
                    data-id_comunicado="'+data[i]["Id_Comunicado"]+'" data-id_evento="'+data[i]["ID_evento"]+'"\
                    data-id_asignacion="'+data[i]["Id_Asignacion"]+'" data-id_proceso="'+data[i]["Id_proceso"]+'"\
                    data-ciudad_comunicaddo="'+data[i]["Ciudad"]+'" data-fecha_comunicado="'+data[i]["F_comunicado"]+'"\
                    data-numero_radicado="'+data[i]["N_radicado"]+'" data-cliente_comunicado="'+data[i]["Cliente"]+'"\
                    data-nombre_afiliado="'+data[i]["Nombre_afiliado"]+'" data-tipo_documento="'+data[i]["T_documento"]+'"\
                    data-numero_identificacion="'+data[i]["N_identificacion"]+'" data-destinatario_principal="'+data[i]["Destinatario"]+'"\
                    data-jrci_destinatario="'+data[i]["JRCI_Destinatario"]+'"\
                    data-nombre_destinatario="'+data[i]["Nombre_destinatario"]+'" data-niccc_comunicado="'+data[i]["Nit_cc"]+'"\
                    data-direccion_destinatario="'+data[i]["Direccion_destinatario"]+'" data-telefono_destinatario="'+data[i]["Telefono_destinatario"]+'"\
                    data-email_destinatario="'+data[i]["Email_destinatario"]+'" data-id_departamento="'+data[i]["Id_departamento"]+'"\
                    data-nombre_departamento="'+data[i]["Nombre_departamento"]+'" data-id_municipio="'+data[i]["Id_municipio"]+'"\
                    data-nombre_municipio="'+data[i]["Nombre_municipio"]+'" data-asunto_comunicado="'+data[i]["Asunto"]+'"\
                    data-cuerpo_comunicado=\''+data[i]["Cuerpo_comunicado"]+'\' data-anexos_comunicados="'+data[i]["Anexos"]+'"\
                    data-forma_envio_comunicado="'+data[i]["Forma_envio"]+'" data-nombre_envio_comunicado="'+data[i]["Nombre_forma_envio"]+'"\
                    data-elaboro_comunicado="'+data[i]["Elaboro"]+'"\
                    data-reviso_comunicado="'+data[i]["Reviso"]+'" data-revisonombre_comunicado="'+data[i]["Nombre_lider"]+'"\
                    data-firmar_comunicado="'+data[i]["Firmar_Comunicado"]+'"\
                    data-jrci_copia="'+data[i]["JRCI_copia"]+'"\
                    data-agregar_copia="'+data[i]["Agregar_copia"]+'"\
                    data-tipo_descarga="'+data[i]["Tipo_descarga"]+'">\
                    <i class="fas fa-file-pdf text-info"></i> Editar</a>';
                    
                    data[i]['Editarcomunicado'] = comunicadoNradico;
                    
                }else{
                    data[i]['Editarcomunicado'] = ""; 
                } 
            }
            $.each(data, function(index, value){
                capturar_informacion_comunicados(data, index, value)
            })
        }
    });

    //DataTable Historial de comunicados

    function capturar_informacion_comunicados(response, index, value) {
        $('#listado_agregar_comunicados').DataTable({
            orderCellsTop:true,
            fixedHeader:true,
            "destroy":true,
            "data": response,
            paging:true,
            "pageLength": 3,
            "order": [[0, 'desc']],
            "columns":[
                {"data":"N_radicado"},
                {"data":"Elaboro"},
                {"data":"F_comunicado"},
                {"data":"Editarcomunicado"},

            ],            
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
    }

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
                $(':input, select, a, button').not('#listado_roles_usuario, #Hacciones, #botonVerEdicionEvento, #cargue_docs, #clicGuardado, #cargue_docs_modal_listado_docs, #botonFormulario2, .btn-danger, a[id^="EditarComunicado_"]').prop('disabled', true);
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
                }
                else if(destino == 'Empresa'){
                    $('#empresa_comunicado_editar').prop('checked', true);
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;
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
            $("#mensaje_cuerpo_editar").addClassClass('d-none');
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
                        // si no, el mismo selector de jrci
                        else{
                            $("#div_input_jrci_editar").addClass('d-none');
                            $("#div_select_jrci_editar").removeClass('d-none');

                            $.ajax({
                                type:'POST',
                                url:'/selectoresJuntas',
                                data: datos_lista_juntas_invalidez,
                                success:function(data) {
                                    $('#jrci_califi_invalidez_comunicado_editar').append('<option>Seleccione una opción</option>');
                                    let juntajrci = Object.keys(data);
                                    for (let i = 0; i < juntajrci.length; i++) {
                                        $('#jrci_califi_invalidez_comunicado_editar').append('<option value="'+data[juntajrci[i]]["Id_Parametro"]+'">'+data[juntajrci[i]]["Nombre_parametro"]+'</option>');
                                    }
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
                    }else if(data.destinatarioPrincipal == 'Empresa'){      
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
                        var reviso = $('#reviso_editar');
                        reviso.empty();
                        reviso.append('<option value="" selected>Seleccione una opción</option>');
                        let revisolider = Object.keys(data.array_datos_lider);
                        for (let i = 0; i < revisolider.length; i++) {
                            reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        }

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
            var texto_insertar = "<p>Cordial saludo:</p><p>Dando respuesta a inconformidad presentada frente a calificación de la Pérdida de la Capacidad Laboral emitida por esta aseguradora, dentro de los términos estipulados en la normatividad vigente, se informa: que hoy los documentos concernientes a su caso han sido enviados a la {{$nombre_junta}}, la cual, le citará a la valoración correspondiente.</p><p>Recuerde por favor, que las Juntas Regionales de Calificación de Invalidez son entidades independientes y autónomas de las compañías de seguros, cuyos procesos y procedimientos son ajenos a nuestra entidad, por lo tanto, cualquier requerimiento ante la misma debe ser tratado directamente con la Junta Regional de Calificación de Invalidez.</p><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

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

            $("#asunto_editar").val('');
            $('#cuerpo_comunicado_editar').summernote('code', '');

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

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
            var texto_insertar = "<p>Respetados señores, cordial saludo:</p><p>En aras de tramitar el recurso y/o controversia presentada en tiempo por la parte interesada contra el dictamen de calificación de PÉRDIDA DE CAPACIDAD LABORAL, remitimos el expediente del afiliado con la documentación exigida por el artículo 30 del Decreto 1352 de 2013 (historia clínica, constancia de notificación, dictamen médico laboral, controversia, etc.) para su valoración.</p><p>Según lo dispone el artículo 20 del mismo decreto, el valor de los honorarios corresponde a un (1) salario mínimo mensual legal vigente, el cual fue cancelado por esta aseguradora. Para los efectos, adjuntamos orden de pago de honorarios No {{$nro_orden_pago}}.</p><p>Finalmente, indicamos que la fecha de notificación del dictamen lo fue el {{$fecha_notificacion_afiliado}} y la dedicación del desacuerdo el {{$fecha_radicacion_controversia_primera_calificacion}}, razón por la cual es procedente tramitar el recurso.</p><p>Los datos del afiliado son los siguientes:</p><table class='tabla_cuerpo_remision_expediente'><tbody><tr><td class='bg'><b>TIPO Y No. DE IDENTIFICACIÓN</b></td><td><p>{{$tipo_documento_afiliado}}{{$documento_afiliado}}<br></p></td></tr><tr><td class='bg'><b>NOMBRE COMPLETO</b></td><td><p>{{$nombre_afiliado}}<br></p></td></tr><tr><td class='bg'><b>EVENTO</b></td><td><p>{{$fecha_estructuracion}} {{$tipo_evento}}<br></p></td></tr><tr><td class='bg'><b>DIAGNÓSTICO</b></td><td><p>{{$nombres_cie10}}<br></p></td></tr><tr><td class='bg'><b>CONTROVERSIA POR</b></td><td><p>{{$tipo_controversia_primera_calificacion}}<br></p></td></tr><tr><td class='bg'><b>DIRECCIÓN Y TELÉFONO DEL ASEGURADO</b></td><td><p>{{$direccion_afiliado}} {{$telefono_afiliado}}<br></p></td></tr><tr><td class='bg'><b>OBSERVACIONES</b></td><td><br></td></tr></tbody></table><p>En virtud de lo señalado en el Artículo 2 del Decreto 1352 de 2013 que establece:</p><p>Artículo 2. Personas interesadas. Para efectos del presente decreto, se entenderá como personas interesadas en el dictamen y de obligatoria notificación o comunicación como mínimo las siguientes:</p><ul><li><span style=''>La persona objeto de dictamen o sus beneficiarios en caso de muerte.</span></li><li><span style=''>La Entidad Promotora de Salud.</span></li><li><span style=''>La Administradora de Riesgos Laborales.</span></li><li><span style=''>La Administradora del Fondo de Pensiones o Administradora de Régimen de Prima Media.</span></li><li><span style=''>El Empleador.</span></li><li><span style=''>La Compañía de Seguro que asuma el riesgo de invalidez, sobrevivencia y muerte. (Subrayado fuera del texto original).</span></li></ul><p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicio al cliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);

            $("#Pdf").val("Pdf");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('PDF');

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
            <p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras l íneas de atención al cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>\
            ";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);

            $("#Pdf").val("Word");
            $("#formato_descarga").html('');
            $("#formato_descarga").html('Word');
    
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

        }
    });

     // Función para verificar si todos los campos están llenos
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
                $("#div_input_jrci_copia_editar").addClass('d-none');
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
                $("#div_select_jrci_copia_editar").addClass('d-none');
                $('#jrci_califi_invalidez_copia_editar').empty();  
            }
        }
    });

    // Actualiza comunicado de origen
    $('#Editar_comunicados').click(function (e) {
        e.preventDefault();  
        $('#Pdf').prop('disabled', false);     
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
            if($('#input_jrci_seleccionado_editar').val() != ""){
                JRCI_Destinatario = $('#input_jrci_seleccionado_editar').val();
            }else{
                JRCI_Destinatario = $("#jrci_califi_invalidez_comunicado_editar").val();
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
       //Copias Interesadas Origen
       var EditComunicadoTotal = [];

       $('input[type="checkbox"]').each(function() {
            var copiaComunicado2 = $(this).attr('id');            
            if (copiaComunicado2 === 'edit_copia_afiliado' || copiaComunicado2 === 'edit_copia_empleador' || 
                copiaComunicado2 === 'edit_copia_eps' || copiaComunicado2 === 'edit_copia_afp' || 
                copiaComunicado2 === 'edit_copia_arl' || copiaComunicado2 === 'edit_copia_jrci' || copiaComunicado2 === 'edit_copia_jnci' ) {                
                if ($(this).is(':checked')) {                
                var relacionCopiaValor2 = $(this).val();
                EditComunicadoTotal.push(relacionCopiaValor2);
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
            'radiojrci_comunicado_editar': radiojrci_comunicado,
            'JRCI_Destinatario_editar': JRCI_Destinatario,
            'radiojnci_comunicado_editar': radiojnci_comunicado,
            'radioafiliado_comunicado_editar':radioafiliado_comunicado,
            'radioempresa_comunicado_editar':radioempresa_comunicado,
            'radioeps_comunicado_editar': radioeps_comunicado,
            'radioafp_comunicado_editar': radioafp_comunicado,
            'radioarl_comunicado_editar': radioarl_comunicado,
            'radioOtro_editar':radioOtro,
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
            'tipo_descarga': tipo_descarga
        }

        document.querySelector("#Editar_comunicados").disabled = true;     
        $.ajax({
            type:'POST',
            url:'/actualizarComunicadoJuntas',
            data: datos_actualizarComunicado,            
            success:function(response){
                if (response.parametro == 'actualizar_comunicado') {
                    $('.alerta_editar_comunicado').removeClass('d-none');
                    $('.alerta_editar_comunicado').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_editar_comunicado').addClass('d-none');
                        $('.alerta_editar_comunicado').empty();
                        document.querySelector("#Editar_comunicados").disabled = false;
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
        $(':input, select, a, button').not('#listado_roles_usuario, #Hacciones, #botonVerEdicionEvento, #cargue_docs, #clicGuardado, #cargue_docs_modal_listado_docs, #botonFormulario2, .btn-danger, a[id^="EditarComunicado_"]').prop('disabled', true);
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
  

});
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