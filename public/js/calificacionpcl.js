$(document).ready(function(){

    var idRol = $("#id_rol").val();
    //localStorage.removeItem("#Generar_comunicados");
    //console.log(Generar_comunicadossesion);
    // Inicializacion del select2 de listados  Módulo Calificacion PCL

    $(".modalidad_calificacion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false,
        width: '100%'
    });

    $(".fuente_informacion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false,
        width: '100%'        
    });

    // Inicializacion del select2 modal agregar seguimiento
    $(".causal_seguimiento").select2({
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

    $(".causal_devolucion_comite").select2({      
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

    //Listado de Modalidad calificacion PCL

    let datos_lista_modalidad_calificacion = {
        '_token': token,
        'parametro':"lista_modalidad_calificacion_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresModuloCalificacionPCL',
        data: datos_lista_modalidad_calificacion,
        success:function(data){
            //console.log(data);
            let NombremodalidadCalificacionPcl = $('select[name=modalidad_calificacion]').val();
            let modalidadCalificacionPcl = Object.keys(data);
            for (let i = 0; i < modalidadCalificacionPcl.length; i++) {
                if (data[modalidadCalificacionPcl[i]]['Id_Parametro'] != NombremodalidadCalificacionPcl) {                    
                    $('#modalidad_calificacion').append('<option value="'+data[modalidadCalificacionPcl[i]]['Id_Parametro']+'">'+data[modalidadCalificacionPcl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    //Listado de fuente de informacion calificacion PCL    
    let datos_lista_fuente_informacion = {
        '_token': token,
        'parametro':"lista_fuente_informacion",        
    };
    
    $.ajax({
        type:'POST',
        url:'/selectoresModuloCalificacionPCL',
        data: datos_lista_fuente_informacion,
        success:function(data){
            //console.log(data);
            let fuenteInformacionCalificacionPcl = $('select[name=fuente_informacion]').val();
            let fuenteInfoCalificacionPcl = Object.keys(data);
            for (let i = 0; i < fuenteInfoCalificacionPcl.length; i++) {
                if (data[fuenteInfoCalificacionPcl[i]]['Id_Parametro'] != fuenteInformacionCalificacionPcl) {                    
                    $('#fuente_informacion').append('<option value="'+data[fuenteInfoCalificacionPcl[i]]['Id_Parametro']+'">'+data[fuenteInfoCalificacionPcl[i]]['Nombre_parametro']+'</option>');
                }
            }
        }
    });

    // Listado de agregar seguimiento

    let datos_lista_causal_seguimiento = {
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

    var Id_asignacion_pro = $('#newId_asignacion').val();
    var Id_proceso_actual = $('#Id_proceso').val();

    //Listado de profesional    

    let datos_lista_profesional={
        '_token':token,
        'parametro':"lista_profesional_proceso",
        'id_proceso' : Id_proceso_actual,
    }

    $.ajax({
        type:'POST',
        url:'/selectoresModuloCalificacionPCL',
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

    //Listado de causal de devolucion comite calificacion PCL    
    
    let datos_lista_causal_devolucion_comite = {
        '_token': token,
        'parametro':"lista_causal_devo_comite",
        'Id_asignacion_pro':Id_asignacion_pro,
        'Id_proceso_actual':Id_proceso_actual
    };
    
    $.ajax({
        type:'POST',
        url:'/selectoresModuloCalificacionPCL',
        data:datos_lista_causal_devolucion_comite,
        success:function(data){
            //console.log(data);
            let idcausal_devolucion_comite= $('select[name=causal_devolucion_comite]').val();
            let causal_devolucion_comitepcl = Object.keys(data);
            for (let i = 0; i < causal_devolucion_comitepcl.length; i++) {
                if (data[causal_devolucion_comitepcl[i]]['Id_causal_devo'] != idcausal_devolucion_comite) {
                    $('#causal_devolucion_comite').append('<option value="'+data[causal_devolucion_comitepcl[i]]['Id_causal_devo']+'">'+data[causal_devolucion_comitepcl[i]]['Causal_devolucion']+'</option>');
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
        url: 'selectoresModuloCalificacionPCL',
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

    // llenado del formulario para la captura de datos del modulo de calificacion Pcl

    var accion_realizarinput = $('#bd_id_accion').val();

    if (accion_realizarinput == 52 || accion_realizarinput == 98 || accion_realizarinput == 99) {
        $('#div_causal_devolucion_comite').removeClass('d-none');        
    }

    var accion_realizarselect = $('#accion');
    accion_realizarselect.change(function() {
        var valoraccion_realizarselect = $(this).val();
        if (valoraccion_realizarselect == 52 || valoraccion_realizarselect == 98 || valoraccion_realizarselect == 99) {
            $('#div_causal_devolucion_comite').removeClass('d-none');
        } else {
            $('#div_causal_devolucion_comite').addClass('d-none');            
        }  
    });

    var newId_evento = $('#newId_evento').val();
    var newId_asignacion = $('#newId_asignacion').val();    
    //console.log(newId_evento);
    $('#form_calificacionPcl').submit(function (e) {
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
        formData.append('modalidad_calificacion', $('#modalidad_calificacion').val());   
        formData.append('fecha_devolucion', $('#fecha_devolucion').val());   
        formData.append('fuente_informacion', $('#fuente_informacion').val());        
        formData.append('accion', $('#accion').val());
        formData.append('fecha_alerta', $('#fecha_alerta').val());
        formData.append('enviar', $('#enviar').val());
        formData.append('profesional', $('#profesional').val());
        formData.append('causal_devolucion_comite', $('#causal_devolucion_comite').val());
        formData.append('descripcion_accion', $('#descripcion_accion').val());
        formData.append('banderaguardar',$('#bandera_accion_guardar_actualizar').val());        

        $.ajax({
            type:'POST',
            url:'/registrarCalificacionPCL',
            data: formData,
            processData: false,
            contentType: false,
            success:function(response){
                if (response.parametro == 'agregarCalificacionPcl') {
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
    });

    // llenado del formulario para la captura de la modal de Agregar Seguimiento

    $('#form_agregar_seguimientoPcl').submit(function (e) {
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
            url:'/registrarCausalSeguimiento',
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

    // Captura de data para la tabla de Comunicados
    // data de la modal de agregar comunicados

    let datos_comunicados ={
        '_token':token,
        'HistorialComunicadosPcl': "CargarComunicados",
        'newId_evento':newId_evento,
        'newId_asignacion':newId_asignacion,
    }

    $.ajax({
        type:'POST',
        url:'/historialComunicadoPcl',
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
                    data-nombre_destinatario="'+data[i]["Nombre_destinatario"]+'" data-niccc_comunicado="'+data[i]["Nit_cc"]+'"\
                    data-direccion_destinatario="'+data[i]["Direccion_destinatario"]+'" data-telefono_destinatario="'+data[i]["Telefono_destinatario"]+'"\
                    data-email_destinatario="'+data[i]["Email_destinatario"]+'" data-id_departamento="'+data[i]["Id_departamento"]+'"\
                    data-nombre_departamento="'+data[i]["Nombre_departamento"]+'" data-id_municipio="'+data[i]["Id_municipio"]+'"\
                    data-nombre_municipio="'+data[i]["Nombre_municipio"]+'" data-asunto_comunicado="'+data[i]["Asunto"]+'"\
                    data-cuerpo_comunicado=\''+data[i]["Cuerpo_comunicado"]+'\' data-anexos_comunicados="'+data[i]["Anexos"]+'"\
                    data-forma_envio_comunicado="'+data[i]["Forma_envio"]+'" data-nombre_envio_comunicado="'+data[i]["Nombre_forma_envio"]+'"\
                    data-elaboro_comunicado="'+data[i]["Elaboro"]+'"\
                    data-reviso_comunicado="'+data[i]["Reviso"]+'" data-revisonombre_comunicado="'+data[i]["Nombre_lider"]+'"\
                    data-firma_cliente="'+data[i]["Firmar_Comunicado"]+'" data-agregar_copia="'+data[i]["Agregar_copia"]+'"\
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
        let url_editar_evento = $('#action_actualizar_comunicado').val();        
        $('form[name="formu_comunicado"]').attr("action", url_editar_evento);    
        $('form[name="formu_comunicado"]').removeAttr('id');

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

            // Despues de descargado el documento deja todo nuevamente deshabilitado + los controles hechos
            if (idRol == 7) {
                // Desactivar todos los elementos excepto los especificados
                $(':input, select, a, button').not('#listado_roles_usuario, #Hacciones, #botonVerEdicionEvento, #cargue_docs, #clicGuardado, #cargue_docs_modal_listado_docs, #abrir_agregar_seguimiento, #fecha_seguimiento, #causal_seguimiento, #descripcion_seguimiento, #Guardar_seguimientos, #botonFormulario2, .btn-danger, a[id^="EditarComunicado_"]').prop('disabled', true);
                $('#aumentarColAccionRealizar').addClass('d-none');
                // Quitar el disabled al formulario oculto para permitirme ir a la edicion del evento.
                $("#enlace_ed_evento").hover(function(){
                    $("input[name='_token']").prop('disabled', false);
                    $("#bandera_buscador_clpcl").prop('disabled', false);
                    $("#newIdEvento").prop('disabled', false);
                    $("#newIdAsignacion").prop('disabled', false);
                    $("#newIdproceso").prop('disabled', false);
                    $("#newIdservicio").prop('disabled', false);
                });
                // Quitar el disabled al formulario oculto para permitirme ir al submodulo
                $("#llevar_servicio").hover(function(){
                    $("input[name='_token']").prop('disabled', false);
                    $("#Id_evento_pcl").prop('disabled', false);
                    $("#Id_asignacion_pcl").prop('disabled', false);
                });
                // Deshabilitar el botón Actualizar y Activar el botón Pdf en los comunicados
                $("#Pdf").prop('disabled', false);
            }
        }, 10000);

    })     
    
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
        //$('#contenedorCopia2').empty();
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
        var firma_cliente = $(this).data("firma_cliente");
        var agregar_copia =  $(this).data("agregar_copia");
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
        //console.log(agregar_copia);
        let datos_destinatario_principal ={
            '_token':token,
            'destinatario_principal': destinatario_principal,
            'id_evento': id_evento,
            'id_asignacion': id_asignacion,
            'id_proceso': id_proceso,
        }
        $.ajax({
            url: '/modalComunicado',
            method:'POST',
            data: datos_destinatario_principal,  
            success:function(data){
                var destino = data.destinatario_principal_comu;
                //console.log(destino);
                if (destino == 'Afiliado') {
                    $('#afiliado_comunicado_editar').prop('checked', true);                    
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;
                }else if(destino == 'Empresa'){
                    $('#empresa_comunicado_editar').prop('checked', true);
                    document.querySelector("#nombre_destinatario_editar").disabled = true;
                    document.querySelector("#nic_cc_editar").disabled = true;
                    document.querySelector("#direccion_destinatario_editar").disabled = true;
                    document.querySelector("#telefono_destinatario_editar").disabled = true;
                    document.querySelector("#email_destinatario_editar").disabled = true;
                    document.querySelector("#departamento_destinatario_editar").disabled = true;
                    document.querySelector("#ciudad_destinatario_editar").disabled = true;
                }else if(destino == 'Otro'){
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
        // console.log(tipo_descarga);
        if (tipo_descarga == "Documento_PCL") {
            $("#documentos_pcl_editar").prop("checked", true);
            $("#otro_documento_pcl_editar").prop("checked", false);
            $("#formatoB_revisionpension_editar").prop("checked", false);
            $("#documento_revisionpension_editar").prop("checked", false);
            $("#No_procede_recali_editar").prop("checked", false);
        }else if (tipo_descarga == "Formato_B_Revision_pension") {
            $("#documentos_pcl_editar").prop("checked", false);
            $("#otro_documento_pcl_editar").prop("checked", false);
            $("#formatoB_revisionpension_editar").prop("checked", true);
            $("#documento_revisionpension_editar").prop("checked", false);
            $("#No_procede_recali_editar").prop("checked", false);
        }else if (tipo_descarga == "Documento_Revision_pension") {
            $("#documentos_pcl_editar").prop("checked", false);
            $("#otro_documento_pcl_editar").prop("checked", false);
            $("#formatoB_revisionpension_editar").prop("checked", false);
            $("#documento_revisionpension_editar").prop("checked", true);
            $("#No_procede_recali_editar").prop("checked", false);
        }else if (tipo_descarga == "Documento_No_Recalificacion") {
            $("#documentos_pcl_editar").prop("checked", false);
            $("#otro_documento_pcl_editar").prop("checked", false);
            $("#formatoB_revisionpension_editar").prop("checked", false);
            $("#documento_revisionpension_editar").prop("checked", false);
            $("#No_procede_recali_editar").prop("checked", true);
            $('#btn_insertar_Origen_editar').removeClass('d-none')
            $('#btn_insertar_nombreCIE10_editar').removeClass('d-none')
            $('#btn_insertar_porPcl_editar').removeClass('d-none')
            $('#btn_insertar_F_estructuracion_editar').removeClass('d-none')
        }else {
            $("#documentos_pcl_editar").prop("checked", false);
            $("#otro_documento_pcl_editar").prop("checked", true);
            $("#formatoB_revisionpension_editar").prop("checked", false);
            $("#documento_revisionpension_editar").prop("checked", false);
            $("#No_procede_recali_editar").prop("checked", false);
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
        var forma_envio_editar = $('#forma_envio_editar');
        forma_envio_editar.empty();
        forma_envio_editar.append('<option value="'+forma_envio_comunicado+'" selected>'+nombre_envio_comunicado+'</option>');
        forma_envio_editar.prop('required', true);
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
        // Validar si va la firma del cliente
        $("input[id^='firmarcomunicado_editar']").each(function() {
            const checkboxValue = $(this).val();
            if (firma_cliente.includes(checkboxValue)) {
                $(this).prop('checked', true);
            }else{
                $(this).prop('checked', false);
            }
        });
        //document.getElementById('firmarcomunicado_editar').value=firma_cliente;
        // var arregloRegistros = agregar_copia.split(","); 
        // for (var i = 0; i < arregloRegistros.length; i++) { 
        //     //duplicate2(arregloRegistros[i]);            
        //     if (arregloRegistros[i]) {
        //         duplicate2(arregloRegistros[i]);
        //     }
        // }
        
        
        //Valida si tiene alguna copia
        $("input[id^='edit_copia_']").each(function() {
            const checkboxValue = $(this).val();
            if (agregar_copia.includes(checkboxValue)) {
                $(this).prop('checked', true);
            }else{
                $(this).prop('checked', false);
            }
        });
        $('input[type="radio"]').change(function(){
            $('#Pdf').prop('disabled', true);
            var destinarioPrincipal = $(this).val();  
            var identificacion_comunicado_afiliado = $('#identificacion_comunicado_editar').val();
            var datos_destinarioPrincipal ={
                '_token':token,
                'destinatarioPrincipal': destinarioPrincipal,
                'identificacion_comunicado_afiliado':identificacion_comunicado_afiliado,
                'newId_evento': id_evento,
                'newId_asignacion': id_asignacion,
                'Id_proceso': id_proceso,
            }
    
            $.ajax({
                type:'POST',
                url:'/captuarDestinatario',
                data: datos_destinarioPrincipal,
                success: function(data){
                    /* $('#destinatarioPrincipal').text(data.destinatarioPrincipal);
                    $('#datos').text(JSON.stringify(data.data)); */
                    if (data.destinatarioPrincipal == 'Afiliado') {
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
                        /* var forma_envio_editar = $('#forma_envio_editar');
                        forma_envio_editar.empty();
                        forma_envio_editar.append('<option value="'+data.hitorialAgregarComunicado[0].Forma_envio+'" selected>'+data.hitorialAgregarComunicado[0].Nombre_forma_envio+'</option>'); */
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
                                $('#forma_envio_editar').append('<option value="">Seleccione una opción</option>');
                                // Agregar el atributo required
                                let NobreFormaEnvio = $('select[name=forma_envio_act]').val();
                                let formaenviogenerarcomunicado = Object.keys(data);
                                for (let i = 0; i < formaenviogenerarcomunicado.length; i++) {
                                    if (data[formaenviogenerarcomunicado[i]]['Id_Parametro'] != NobreFormaEnvio) {
                                        $('#forma_envio_editar').append('<option value="' + data[formaenviogenerarcomunicado[i]]['Id_Parametro'] + '">' + data[formaenviogenerarcomunicado[i]]['Nombre_parametro'] + '</option>');
                                    }
                                }
                                // Desencadenar el evento change de Select2 para activar la actualización
                                // $('#forma_envio_editar').trigger('change.select2');
                            }
                        });
                        // $('#forma_envio_editar').prop('required', true);

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
                        // reviso.append('<option value="">Seleccione una opción</option>');                        
                        // let revisolider = Object.keys(data.array_datos_lider);
                        // for (let i = 0; i < revisolider.length; i++) {
                        //     reviso.append('<option value="'+data.array_datos_lider[revisolider[i]]["id"]+'">'+data.array_datos_lider[revisolider[i]]["name"]+'</option>');
                        // }
                        // reviso.prop('required', true);
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
                    }
    
                }        
            });
            
        });

    });   
    
    /* Funcionalidad para insertar la etiqueta de pruebas solicitadas (edición) */
    $("#cuerpo_comunicado_editar").summernote({
        height: 'auto',
        toolbar: false
    });
    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");

    $("#btn_insertar_pruebas_editar").click(function(e){
        e.preventDefault();

        var etiqueta_pruebas = "{{$pruebas_solicitadas}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_pruebas);
    });

    /* Funcionalidad radio buttons Solicitud documentos Origen y Otro documento (edición) */
    $("[name='tipo_documento_descarga_califi_editar']").on("change", function(){
        var opc_seleccionada = $(this).val();
        
        if (opc_seleccionada == "Documento_PCL") {
            $("#asunto_editar").val("Solicitud de documentos Calificación de Pérdida de Capacidad laboral al Fondo de Pensiones Porvenir S.A.");
            var texto_insertar = "<p>En Seguros de Vida Alfa S.A. siempre buscamos la protección y satisfacción de nuestros clientes. De acuerdo a tu solicitud de "+
            "calificación de pérdida de capacidad laboral (PCL) radicada en la AFP Porvenir S.A., te informamos que el historial médico aportado "+
            "ha sido revisado por el grupo interdisciplinario de calificación de Seguros de Vida Alfa S.A.</p>"+
            "<p>No obstante, a que la información suministrada es relevante, se hace necesario que sean aportados documentos adicionales con el fin "+
            "de poder realizar la calificación de pérdida de capacidad laboral requerida, que a continuación relacionamos:</p>"+
            "<p>1. </p>"+
            "<p>Esta documentación debe suministrarla a los siguientes correos electrónicos: servicioalcliente@codess.org.co, "+
            "servicioalcliente@segurosalfa.com.co en un término de tres (3) meses contados a partir del recibido de la presente comunicación, y a "+
            "partir de ese momento se inicia nuevamente el estudio de tu solicitud. En el evento de no recibir la documentación medica "+
            "actualizada, se considerará desistimiento de tu solicitud por parte de esta aseguradora.</p>"+
            "<p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 "+
            "70 32 o a la línea nacional gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p.m. - sábados de 8:00 a.m. a 12 m., o "+
            "escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose Maria Cordoba, Bogota D.C.</p>";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);

            // Auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado_editar').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Eps
            $("#edit_copia_eps").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', true);

        }else if (opc_seleccionada == "Formato_B_Revision_pension") {
            $("#asunto_editar").val("NOTIFICACIÓN RESULTADO REVISIÓN PENSIONAL");
            var texto_insertar = "<p>Reciba un cordial saludo, </p>"+
            "<p>Agradecemos la respuesta que hemos recibido a nuestra solicitud de actualización de historia clínica con "+ 
            "el fin de revisar sus condiciones de salud.</p>"+
            "<p>De la revisión que ha realizado el Grupo Interdisciplinario de Calificación de Invalidez de Seguros de Vida "+
            "Alfa S.A., hemos definido que Usted actualmente mantiene las condiciones para continuar con el beneficio "+
            "de pensión por invalidez sobre el cual esta compañía Aseguradora ha venido pagando la mesada pensional "+
            "en virtud del contrato de Renta Vitalicia Inmediata suscrito por encargo de la Administradora de Fondos de "+
            "Pensiones Porvenir S.A.</p>"+
            "<p>En forma sucinta la revisión de invalidez, se fundamenta en: </p>"+
            "<p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras l íneas de atención al "+
            "cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes, de "+
            "8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a "+
            "«servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);
        }else if (opc_seleccionada == "Documento_Revision_pension") {
            $("#asunto_editar").val("Servicio del Proceso de PCL  Revision Pension");
            var texto_insertar = '<p>Reciba un cordial saludo por parte de Seguros de Vida Alfa S.A.</p>'+
            '<p>En cumplimiento de la normatividad vigente, Seguros de Vida Alfa S.A, se encuentra realizando la actualización de información de las '+ 
            'condiciones de salud de los pensionados por invalidez, sustentada en el artículo 44 de la Ley 100 de 1993, que establece:</p>'+
            '<p class="cuerpo_doc_revPen">“(...)<strong>ARTÍCULO 44. REVISIÓN DE LAS PENSIONES DE INVALIDEZ.</strong> El estado de invalidez podrá <br>'+
            'revisarse: <br>'+
            'a. Por solicitud de la entidad de previsión o seguridad social correspondiente cada tres (3) años, '+
            'con el fin de ratificar, modificar o dejar sin efectos el dictamen que sirvió de base para la liquidación '+
            'de la pensión que disfruta su beneficiario y proceder a la extinción, disminución o aumento de la '+
            'misma, si a ello hubiera lugar.<br>'+
            'Este nuevo dictamen se sujeta a las reglas de los artículos anteriores. <br>'+
            'El pensionado tendrá un plazo de tres (3) meses contados a partir de la fecha de dicha solicitud, '+
            'para someterse a la respectiva revisión del estado de invalidez. Salvo casos de fuerza mayor, si el '+
            'pensionado no se presenta o impide dicha revisión dentro de dicho plazo, se suspenderá el pago de '+
            'la pensión. Transcurridos doce (12) meses contados desde la misma fecha sin que el pensionado '+
            'se presente o permita el examen, la respectiva pensión prescribirá. '+
            'Para readquirir el derecho en forma posterior, el afiliado que alegue permanecer inválido deberá '+
            'someterse a un nuevo dictamen. Los gastos de este nuevo dictamen ser án pagados por el afiliado <br>'+
            '(...)”</p>'+
            '<p>De acuerdo con lo anterior y una vez revisado el expediente previsional de referencia, se estableció que es necesario que allegue '+
            'esta documentación a los siguientes correos electrónicos : alfa.previsional2@codess.org.co, servicioalcliente@segurosalfa.com.co en '+
            'un término de tres (3) meses contados a partir del recibido de la presente comunicación, los siguientes documentos:</p>'+
            '<p>1. </p>'+
            '<p>Los documentos anteriormente mencionados deben ser solicitados en su EPS con su médico tratante; por otra parte aclaramos que los '+
            'mismos se deben radicar en papelería física. No se aceptan medios magnéticos como CD o correos electrónicos.</p>'+
            '<p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 '+
            '70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p.m. - sábados de 8:00 a.m. a 12 m., o '+
            'escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose Maria Cordoba, Bogota D.C.</p>';
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);
            // $('#btn_insertar_Detalle_calificacion').removeClass('d-none');

            // Auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado_editar').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Eps
            $("#edit_copia_eps").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', true);

        }else if (opc_seleccionada == "Documento_No_Recalificacion") {
            $("#asunto_editar").val("RESPUESTA A SOLICITUD DE RECALIFICACIÓN");
            var texto_insertar = '<p>Reciba un cordial saludo, </p>'+
            '<p>Con ocasión a la solicitud de calificación de pérdida de capacidad laboral radicado por usted en la Administradora de Fondo de '+ 
            'Pensiones Porvenir, Seguros de Vida Alfa S.A. se permite dar respuesta en los términos que se describen a continuación. '+
            'Una vez revisadas nuestras bases de datos y sistemas de información, se evidencia con respecto a su caso, que Seguros de Vida Alfa '+
            'S.A. realizó calificación de la Pérdida de Capacidad Laboral (PCL), con los diagnósticos de origen {{$OrigenPcl_dp}}: {{$CIE10Nombres}}, '+
            'con un porcentaje del {{$PorcentajePcl_dp}} % y fecha de estructuración {{$F_estructuracionPcl_dp}}, la cual le fue remitida y le fue notificada.</p>'+
            '<p>Por tanto, en el momento no hay lugar a emitir nuevo dictamen de Calificación de Pérdida de Capacidad Laboral (PCL) por parte de '+
            'Seguros Alfa; de acuerdo a lo establecido en el Decreto 1352 del 2013 Articulo 55:</p>'+
            '<p class="cuerpo_doc_revPen">“...la revisión de la pérdida de incapacidad permanente parcial por parte de las '+
            'Juntas será procedente cuando el porcentaje sea inferior al 50% de pérdida de '+
            'capacidad laboral a solicitud de la Administradora de Riesgos Laborales, los '+
            'trabajadores o personas interesadas, mínimo al año siguiente de la calificación y '+
            'siguiendo los procedimientos y términos de tiempo establecidos en el presente '+
            'decreto...”</p>'+
            '<p>Esperamos de esta forma haber dado respuesta a su requerimiento y reiteramos nuestra voluntad de servicio. </p>';            
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);
            $('#btn_insertar_Origen_editar').removeClass('d-none')
            $('#btn_insertar_nombreCIE10_editar').removeClass('d-none')
            $('#btn_insertar_porPcl_editar').removeClass('d-none')
            $('#btn_insertar_F_estructuracion_editar').removeClass('d-none')
            // $('#btn_insertar_Detalle_calificacion').removeClass('d-none');

            // Auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado_editar').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Eps
            $("#edit_copia_eps").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', true);
        }
        else{
            $("#asunto_editar").val("");
            $('#cuerpo_comunicado_editar').summernote('code', '');
            $('#btn_insertar_Origen_editar').addClass('d-none');
            $('#btn_insertar_nombreCIE10_editar').addClass('d-none');
            $('#btn_insertar_porPcl_editar').addClass('d-none');
            $('#btn_insertar_F_estructuracion_editar').addClass('d-none');

            // Quitar auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado_editar').prop('checked', false);

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos_editar").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Eps
            $("#edit_copia_eps").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado_editar").prop('checked', false);

        }
    });  
    
    $("#btn_insertar_Origen_editar").click(function(e){
        e.preventDefault();

        var etiqueta_nombreCIE10 = "{{$OrigenPcl_dp}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_nombreCIE10);
    });

    $("#btn_insertar_nombreCIE10_editar").click(function(e){
        e.preventDefault();

        var etiqueta_nombreCIE10 = "{{$CIE10Nombres}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_nombreCIE10);
    });

    $("#btn_insertar_porPcl_editar").click(function(e){
        e.preventDefault();

        var etiqueta_porPCL = "{{$PorcentajePcl_dp}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_porPCL);
    });  
    
    $("#btn_insertar_F_estructuracion_editar").click(function(e){
        e.preventDefault();

        var etiqueta_F_estructuracion = "{{$F_estructuracionPcl_dp}}";
        $('#cuerpo_comunicado_editar').summernote('editor.insertText', etiqueta_F_estructuracion);
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

    // Captura de data para el fomulario de actualizar el comunicado

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
        var afiliado_comunicado = $('#afiliado_comunicado_editar').prop('checked');
        var empresa_comunicado = $('#empresa_comunicado_editar').prop('checked');
        var Otro = $('#Otro_editar').prop('checked');
        var radioafiliado_comunicado;
        var radioempresa_comunicado;
        var radioOtro;
        if(afiliado_comunicado){
           var radioafiliado_comunicado = afiliado_comunicado;
        }else if(empresa_comunicado){
           var radioempresa_comunicado = empresa_comunicado;
        }else if(Otro){
           var radioOtro = Otro;
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
        var firmarcomunicadoPcl = [];
        $('input[type="checkbox"]').each(function() {
            var firmarcomunicado = $(this).attr('id');   
            if (firmarcomunicado === 'firmarcomunicado_editar') {                
                if ($(this).is(':checked')) {                
                var Valorfirmarcomunicado = $(this).val();
                firmarcomunicadoPcl.push(Valorfirmarcomunicado);
                }
            }
        });
        //var arrayinputs = [];
        // Función para capturar los valores de todos los inputs de Agregar copia
        // function capturarValores() {            
        //     var contenedor = document.getElementById("contenedorCopia2");                        
        //     var inputs = contenedor.getElementsByTagName("input");                        
        //     for (var i = 0; i < inputs.length; i++) {                
        //         var valor = inputs[i].value;
        //     arrayinputs.push(valor)
        //     }
        //     if (arrayinputs.length === 0) {
        //         arrayinputs = ['CopiaVacia'];
        //     }else{
        //         arrayinputs;  
        //     }
        // }    
        // capturarValores();
        //console.log(arrayinputs); 
        var EditComunicadosPcl = [];

       $('input[type="checkbox"]').each(function() {
            var copiaComunicado2 = $(this).attr('id');            
            if (copiaComunicado2 === 'edit_copia_afiliado' || copiaComunicado2 === 'edit_copia_empleador' || 
                copiaComunicado2 === 'edit_copia_eps' || copiaComunicado2 === 'edit_copia_afp' || 
                copiaComunicado2 === 'edit_copia_arl') {                
                if ($(this).is(':checked')) {                
                var relacionCopiaValor2 = $(this).val();
                EditComunicadosPcl.push(relacionCopiaValor2);
                }
            }
       });       
       var tipo_descarga = $("[name='tipo_documento_descarga_califi_editar']").filter(":checked").val();
       
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
            'tipo_descarga':tipo_descarga,
            'radioafiliado_comunicado_editar':radioafiliado_comunicado,
            'radioempresa_comunicado_editar':radioempresa_comunicado,
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
            'firmarcomunicado_editar':firmarcomunicadoPcl,
            'agregar_copia_editar':EditComunicadosPcl,
        }

        document.querySelector("#Editar_comunicados").disabled = true;     
        $.ajax({
            type:'POST',
            url:'/actualizarComunicado',
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

    // Captura de data para la tabla de Historial de seguimientos
    // data de la modal de agregar seguimientos

    let datos_historial_seguimiento ={
        '_token':token,
        'HistorialSeguimientoPcl': "CargaHistorialSeguimiento",
        'newId_evento':newId_evento,
        'newId_asignacion':newId_asignacion,
    }

    $.ajax({
        type:'POST',
        url:'/historialSeguimientoPCL',
        data: datos_historial_seguimiento,
        success:function(data){
            $.each(data, function(index, value) {
                capturar_informacion_historial_seguimiento(data, index, value)
            })
        }
    });

    // DataTable Historial de seguimientos
    function capturar_informacion_historial_seguimiento(response, index, value) {
        $('#listado_agregar_seguimientos').DataTable({
            orderCellsTop:true,
            fixedHeader:true,
            "destroy":true,
            "data": response,
            paging:true,
            "pageLength": 5,
            "order": [[0, 'desc']],
            "columns":[
                {"data":"F_seguimiento"},
                {"data":"Causal_seguimiento"},
                {"data":"Descripcion_seguimiento"},
                {"data":"Nombre_usuario"},
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
 
    /* FUNCIONALIDAD PARA VALIDAR SI APORTÓ O NO APORTÓ DOCUMENTOS PARA SETEAR EL CHECK */
    if($("#validacion_aporta_doc").val() == "No"){
        $("#No_aporta_documentos").prop("checked", true);
        $("#btn_agregar_fila").css('display', 'none');
        $("#cargue_docs_modal_listado_docs").prop('disabled', true);
        $("#cargue_docs_modal_listado_docs").hover(function(){
            $(this).css('cursor', 'not-allowed');
        });

    }else{
        $("#No_aporta_documentos").prop("checked", false);
        $("#btn_agregar_fila").css('display', 'block');
        $("#cargue_docs_modal_listado_docs").prop('disabled', false);
        $("#cargue_docs_modal_listado_docs").hover(function(){
            $(this).css('cursor', 'pointer');
        });
    }

    // Captura de datos segun la opcion seleccionada en destinatario principal
    // En la modal de generar comunicado
    $('input[type="radio"]').change(function(){
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
        }
        $.ajax({
            type:'POST',
            url:'/captuarDestinatario',
            data: datos_destinarioPrincipal,
            success: function(data){
                /* $('#destinatarioPrincipal').text(data.destinatarioPrincipal);
                $('#datos').text(JSON.stringify(data.data)); */
                if (data.destinatarioPrincipal == 'Afiliado') {
                    // console.log(data.array_datos_destinatarios);
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
                }else if(data.destinatarioPrincipal == 'Otro'){
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
                }

            }        
        });
        
    });

    // validacion para numeros enteros en anexos modal agregar seguimiento
    var input = document.getElementById("anexos");
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

    /* Funcionalidad para insertar la etiqueta de pruebas solicitadas */
    $("#cuerpo_comunicado").summernote({
        height: 'auto',
        toolbar: false
    });
    $('.note-editing-area').css("background", "white");
    $('.note-editor').css("border", "1px solid black");

    $("#btn_insertar_pruebas").click(function(e){
        e.preventDefault();

        var etiqueta_pruebas = "{{$pruebas_solicitadas}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_pruebas);
    });

    /* Funcionalidad radio buttons Solicitud documentos Origen y Otro documento */
    $("[name='tipo_documento_descarga_califi']").on("change", function(){
        var opc_seleccionada = $(this).val();
            
        if (opc_seleccionada == "Documento_PCL") {
            $("#asunto").val("SOLICITUD DE DOCUMENTOS PARA CALIFICACIÓN DE PÉRDIDA DE LA CAPACIDAD LABORAL");
            var texto_insertar = "<p>En Seguros de Vida Alfa S.A. siempre buscamos la protección y satisfacción de nuestros clientes. De acuerdo a tu solicitud de "+
            "calificación de pérdida de capacidad laboral (PCL) radicada en la AFP Porvenir S.A., te informamos que el historial médico aportado "+
            "ha sido revisado por el grupo interdisciplinario de calificación de Seguros de Vida Alfa S.A.</p>"+
            "<p>No obstante, a que la información suministrada es relevante, se hace necesario que sean aportados documentos adicionales con el fin "+
            "de poder realizar la calificación de pérdida de capacidad laboral requerida, que a continuación relacionamos:</p>"+
            "<p>1. </p>"+
            "<p>Esta documentación debe sumistrarla a los siguientes correos electrónicos: servicioalcliente@codess.org.co, "+
            "servicioalcliente@segurosalfa.com.co en un término de tres (3) meses contados a partir del recibido de la presente comunicación, y a "+
            "partir de ese momento se inicia nuevamente el estudio de tu solicitud. En el evento de no recibir la documentación medica "+
            "actualizada, se considerará desistimiento de tu solicitud por parte de esta aseguradora.</p>"+
            "<p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras l íneas de atención al cliente en Bogotá (601) 3 07 "+
            "70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p.m. - sábados de 8:00 a.m. a 12 m., o "+
            "escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);
            // $('#btn_insertar_Detalle_calificacion').addClass('d-none');

            // Auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Eps
            $("#copia_eps").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', true);

        }else if (opc_seleccionada == "Formato_B_Revision_pension") {
            $("#asunto").val("NOTIFICACIÓN RESULTADO REVISIÓN PENSIONAL");
            var texto_insertar = "<p>Reciba un cordial saludo, </p>"+
            "<p>Agradecemos la respuesta que hemos recibido a nuestra solicitud de actualización de historia clínica con "+ 
            "el fin de revisar sus condiciones de salud.</p>"+
            "<p>De la revisión que ha realizado el Grupo Interdisciplinario de Calificación de Invalidez de Seguros de Vida "+
            "Alfa S.A., hemos definido que Usted actualmente mantiene las condiciones para continuar con el beneficio "+
            "de pensión por invalidez sobre el cual esta compañía Aseguradora ha venido pagando la mesada pensional "+
            "en virtud del contrato de Renta Vitalicia Inmediata suscrito por encargo de la Administradora de Fondos de "+
            "Pensiones Porvenir S.A.</p>"+
            "<p>En forma sucinta la revisión de invalidez, se fundamenta en: </p>"+
            "<p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras l íneas de atención al "+
            "cliente en Bogotá (601) 3 07 70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes, de "+
            "8:00 a. m. a 8:00 p. m. - sábados de 8:00 a.m. a 12 m., o escribanos a "+
            "«servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);
            // $('#btn_insertar_Detalle_calificacion').removeClass('d-none');
        }else if (opc_seleccionada == "Documento_Revision_pension") {
            $("#asunto").val("Servicio del Proceso de PCL  Revision Pension");
            var texto_insertar = '<p>Reciba un cordial saludo por parte de Seguros de Vida Alfa S.A.</p>'+
            '<p>En cumplimiento de la normatividad vigente, Seguros de Vida Alfa S.A, se encuentra realizando la actualización de información de las '+ 
            'condiciones de salud de los pensionados por invalidez, sustentada en el artículo 44 de la Ley 100 de 1993, que establece:</p>'+
            '<p class="cuerpo_doc_revPen">“(...)<strong>ARTÍCULO 44. REVISIÓN DE LAS PENSIONES DE INVALIDEZ.</strong> El estado de invalidez podrá <br>'+
            'revisarse: <br>'+
            'a. Por solicitud de la entidad de previsión o seguridad social correspondiente cada tres (3) años, '+
            'con el fin de ratificar, modificar o dejar sin efectos el dictamen que sirvió de base para la liquidación '+
            'de la pensión que disfruta su beneficiario y proceder a la extinción, disminución o aumento de la '+
            'misma, si a ello hubiera lugar.<br>'+
            'Este nuevo dictamen se sujeta a las reglas de los artículos anteriores. <br>'+
            'El pensionado tendrá un plazo de tres (3) meses contados a partir de la fecha de dicha solicitud, '+
            'para someterse a la respectiva revisión del estado de invalidez. Salvo casos de fuerza mayor, si el '+
            'pensionado no se presenta o impide dicha revisión dentro de dicho plazo, se suspenderá el pago de '+
            'la pensión. Transcurridos doce (12) meses contados desde la misma fecha sin que el pensionado '+
            'se presente o permita el examen, la respectiva pensión prescribirá. '+
            'Para readquirir el derecho en forma posterior, el afiliado que alegue permanecer inválido deberá '+
            'someterse a un nuevo dictamen. Los gastos de este nuevo dictamen ser án pagados por el afiliado <br>'+
            '(...)”</p>'+
            '<p>De acuerdo con lo anterior y una vez revisado el expediente previsional de referencia, se estableció que es necesario que allegue '+
            'esta documentación a los siguientes correos electrónicos : alfa.previsional2@codess.org.co, servicioalcliente@segurosalfa.com.co en '+
            'un término de tres (3) meses contados a partir del recibido de la presente comunicación, los siguientes documentos:</p>'+
            '<p>1. </p>'+
            '<p>Los documentos anteriormente mencionados deben ser solicitados en su EPS con su médico tratante; por otra parte aclaramos que los '+
            'mismos se deben radicar en papelería física. No se aceptan medios magnéticos como CD o correos electrónicos.</p>'+
            '<p>Cualquier inquietud o consulta al respecto, le invitamos a comunicarse a nuestras líneas de atención al cliente en Bogotá (601) 3 07 '+
            '70 32 o a la línea naciona gratuita 01 8000 122 532, de lunes a viernes, de 8:00 a. m. a 8:00 p.m. - sábados de 8:00 a.m. a 12 m., o '+
            'escribanos a «servicioalcliente@segurosalfa.com.co» o a la dirección Carrera 10 # 18-36 piso 4 Edificio Jose maria Cordoba, Bogota D.C.</p>';
            $('#cuerpo_comunicado').summernote('code', texto_insertar);
            // $('#btn_insertar_Detalle_calificacion').removeClass('d-none');

            // Auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Selección automática de las copias a partes interesadas: Eps
            $("#copia_eps").prop('checked', true);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', true);

        }else if (opc_seleccionada == "Documento_No_Recalificacion") {
            $("#asunto").val("RESPUESTA A SOLICITUD DE RECALIFICACIÓN");
            var texto_insertar = '<p>Reciba un cordial saludo, </p>'+
            '<p>Con ocasión a la solicitud de calificación de pérdida de capacidad laboral radicado por usted en la Administradora de Fondo de '+ 
            'Pensiones Porvenir, Seguros de Vida Alfa S.A. se permite dar respuesta en los términos que se describen a continuación. '+
            'Una vez revisadas nuestras bases de datos y sistemas de información, se evidencia con respecto a su caso, que Seguros de Vida Alfa '+
            'S.A. realizó calificación de la Pérdida de Capacidad Laboral (PCL), con los diagnósticos de origen {{$OrigenPcl_dp}}: {{$CIE10Nombres}}, '+
            'con un porcentaje del {{$PorcentajePcl_dp}} % y fecha de estructuración {{$F_estructuracionPcl_dp}}, la cual le fue remitida y le fue notificada.</p>'+
            '<p>Por tanto, en el momento no hay lugar a emitir nuevo dictamen de Calificación de Pérdida de Capacidad Laboral (PCL) por parte de '+
            'Seguros Alfa; de acuerdo a lo establecido en el Decreto 1352 del 2013 Articulo 55:</p>'+
            '<p class="cuerpo_doc_revPen">“...la revisión de la pérdida de incapacidad permanente parcial por parte de las '+
            'Juntas será procedente cuando el porcentaje sea inferior al 50% de pérdida de '+
            'capacidad laboral a solicitud de la Administradora de Riesgos Laborales, los '+
            'trabajadores o personas interesadas, mínimo al año siguiente de la calificación y '+
            'siguiendo los procedimientos y términos de tiempo establecidos en el presente '+
            'decreto...”</p>'+
            '<p>Esperamos de esta forma haber dado respuesta a su requerimiento y reiteramos nuestra voluntad de servicio. </p>';            
            $('#cuerpo_comunicado').summernote('code', texto_insertar);
            // $('#btn_insertar_Detalle_calificacion').removeClass('d-none');
            $('#btn_insertar_Origen').removeClass('d-none');
            $('#btn_insertar_nombreCIE10').removeClass('d-none');
            $('#btn_insertar_porPcl').removeClass('d-none');
            $('#btn_insertar_F_estructuracion').removeClass('d-none');

            // Auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado').click();

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Eps
            $("#copia_eps").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', true);
        }        
        else{
            $("#asunto").val("");
            $('#cuerpo_comunicado').summernote('code', '');
            // $('#btn_insertar_Detalle_calificacion').addClass('d-none');
            $('#btn_insertar_Origen').addClass('d-none');
            $('#btn_insertar_nombreCIE10').addClass('d-none');
            $('#btn_insertar_porPcl').addClass('d-none');
            $('#btn_insertar_F_estructuracion').addClass('d-none');

            // Quitar auto selección de la opción Afiliado (Destinatario Principal)
            $('#afiliado_comunicado').prop('checked', false);

            // Seteo automático del nro de anexos:
            var seteo_nro_anexos = 0;
            $("#anexos").val(seteo_nro_anexos);

            // Deselección automática de las copias a partes interesadas: Eps
            $("#copia_eps").prop('checked', false);

            // Selección automática del checkbox firmar
            $("#firmarcomunicado").prop('checked', false);

        }
    });

    // $("#btn_insertar_Detalle_calificacion").click(function(e){
    //     e.preventDefault(); 

    //     var etiqueta_Detalle_calificacion = "{{$Detalle_calificacion_Fbdp}}";
    //     $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_Detalle_calificacion);
    // });    
    
    $("#btn_insertar_Origen").click(function(e){
        e.preventDefault();

        var etiqueta_nombreCIE10 = "{{$OrigenPcl_dp}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_nombreCIE10);
    });

    $("#btn_insertar_nombreCIE10").click(function(e){
        e.preventDefault();

        var etiqueta_nombreCIE10 = "{{$CIE10Nombres}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_nombreCIE10);
    });

    $("#btn_insertar_porPcl").click(function(e){
        e.preventDefault();

        var etiqueta_porPCL = "{{$PorcentajePcl_dp}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_porPCL);
    });  
    
    $("#btn_insertar_F_estructuracion").click(function(e){
        e.preventDefault();

        var etiqueta_F_estructuracion = "{{$F_estructuracionPcl_dp}}";
        $('#cuerpo_comunicado').summernote('editor.insertText', etiqueta_F_estructuracion);
    });    

    // llenado del formulario para la captura de la modal de Generar Comunicado
    
    $('#form_generarComunicadoPcl').submit(function (e) {
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
        var afiliado_comunicado = $('#afiliado_comunicado').prop('checked');
        var empresa_comunicado = $('#empresa_comunicado').prop('checked');
        var Otro = $('#Otro').prop('checked');
        var radioafiliado_comunicado;
        var radioempresa_comunicado;
        var radioOtro;
        if(afiliado_comunicado){
           var radioafiliado_comunicado = afiliado_comunicado;
        }else if(empresa_comunicado){
           var radioempresa_comunicado = empresa_comunicado;
        }else if(Otro){
           var radioOtro = Otro;
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
        var firmarcomunicadoPcl = [];
        $('input[type="checkbox"]').each(function() {
            var firmarcomunicado = $(this).attr('id');   
            if (firmarcomunicado === 'firmarcomunicado') {                
                if ($(this).is(':checked')) {                
                var Valorfirmarcomunicado = $(this).val();
                firmarcomunicadoPcl.push(Valorfirmarcomunicado);
                }
            }
        });
        //console.log(firmarcomunicadoPcl);
        // var arrayinputs = [];
        // // Función para capturar los valores de todos los inputs de Agregar copia
        // function capturarValores() {            
        //     var contenedor = document.getElementById("contenedorCopia");                        
        //     var inputs = contenedor.getElementsByTagName("input");                        
        //     for (var i = 0; i < inputs.length; i++) {                
        //         var valor = inputs[i].value;
        //     arrayinputs.push(valor)
        //     }
        //     if (arrayinputs.length === 0) {
        //         arrayinputs = ['CopiaVacia'];
        //     }else{
        //         arrayinputs;  
        //     }
        // }
        // capturarValores();
        //console.log(arrayinputs);        
        var copiaComunicadosPcl = [];

        $('input[type="checkbox"]').each(function() {
            var copiaComunicado = $(this).attr('id');            
            if (copiaComunicado === 'copia_afiliado' || copiaComunicado === 'copia_empleador' || 
                copiaComunicado === 'copia_eps' || copiaComunicado === 'copia_afp' || 
                copiaComunicado === 'copia_arl') {                
                if ($(this).is(':checked')) {                
                var relacionCopiaValor = $(this).val();
                copiaComunicadosPcl.push(relacionCopiaValor);
                }
            }
        });
        var tipo_descarga = $("[name='tipo_documento_descarga_califi']").filter(":checked").val();
        
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
            'radioafiliado_comunicado':radioafiliado_comunicado,
            'radioempresa_comunicado':radioempresa_comunicado,
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
            'firmarcomunicado':firmarcomunicadoPcl,
            'agregar_copia':copiaComunicadosPcl,
            'tipo_descarga':tipo_descarga,
        }
        
        document.querySelector("#Generar_comunicados").disabled = true;   
        $.ajax({
            type:'POST',
            url:'/registrarComunicado',
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
        })        
    }) 

    // Abrir modal de agregar solictudes despues de guardar 
    if (localStorage.getItem("#Generar_comunicados")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#Generar_comunicados");
        document.querySelector("#clicGuardado").click();
    }

    $('#Hacciones').click(function(){
        $('#borrar_tabla_historial_acciones').empty();

        var datos_llenar_tabla_historial_acciones = {
            '_token': $('input[name=_token]').val(),
            'ID_evento' : $('#id_evento').val(),
            'Id_proceso': $('#Id_proceso').val()
        };
         
        $.ajax({
            type:'POST',
            url:'/historialAccionesEventosPcl',
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

    /* Validaciones para el rol Consulta cuando entra a la vista */
    if (idRol == 7) {
        // Desactivar todos los elementos excepto los especificados
        $(':input, select, a, button').not('#listado_roles_usuario, #Hacciones, #botonVerEdicionEvento, #cargue_docs, #clicGuardado, #cargue_docs_modal_listado_docs, #abrir_agregar_seguimiento, #fecha_seguimiento, #causal_seguimiento, #descripcion_seguimiento, #Guardar_seguimientos, #botonFormulario2, .btn-danger, a[id^="EditarComunicado_"]').prop('disabled', true);
        $('#aumentarColAccionRealizar').addClass('d-none');
        // Quitar el disabled al formulario oculto para permitirme ir a la edicion del evento.
        $("#enlace_ed_evento").hover(function(){
            $("input[name='_token']").prop('disabled', false);
            $("#bandera_buscador_clpcl").prop('disabled', false);
            $("#newIdEvento").prop('disabled', false);
            $("#newIdAsignacion").prop('disabled', false);
            $("#newIdproceso").prop('disabled', false);
            $("#newIdservicio").prop('disabled', false);
        });
        
        // Si el servicio es Pronunciamiento deshabilita la entrada al submodulo
        if ($("#newIdservicio").val() == 9) {
            $("#botonFormulario2").prop('disabled', true);
        }

        // Quitar el disabled al formulario oculto para permitirme ir al submodulo
        $("#llevar_servicio").hover(function(){
            $("input[name='_token']").prop('disabled', false);
            $("#Id_evento_pcl").prop('disabled', false);
            $("#Id_asignacion_pcl").prop('disabled', false);
        });
        // Deshabilitar el botón Actualizar y Activar el botón Pdf en los comunicados
        $("#Pdf").prop('disabled', false);

    }
    
    // A los usuarios que no tengan el rol Administrador se les aplica los siguientes controles en el formulario de correspondencia:
    // inhabilita los campos nro anexos, asunto, etiquetas, cuerpo comunicado, firmar
    if (idRol != 6) {
        $("#anexos").prop('readonly', true);
        $("#anexos_editar").prop('readonly', true);
        $("#asunto").prop('readonly', true);
        $("#asunto_editar").prop('readonly', true);

        $('#btn_insertar_Origen').prop('disabled', true);
        $('#btn_insertar_nombreCIE10').prop('disabled', true);
        $('#btn_insertar_porPcl').prop('disabled', true);
        $('#btn_insertar_F_estructuracion').prop('disabled', true);

        $('#btn_insertar_Origen_editar').prop('disabled', true);
        $('#btn_insertar_nombreCIE10_editar').prop('disabled', true);
        $('#btn_insertar_porPcl_editar').prop('disabled', true);
        $('#btn_insertar_F_estructuracion_editar').prop('disabled', true);
        
        $(".note-editable").attr("contenteditable", false);
        $("#firmarcomunicado").prop('disabled', true);
        $("#firmarcomunicado_editar").prop('disabled', true);
    }
});

var copia = 1;
function duplicate() {
    // se crea el contenedor principal
    var container = document.getElementById('contenedorCopia');    
    // se crea el contenedor con el col-6
    var newDiv = document.createElement('div');
    newDiv.className= 'col-6';
    newDiv.id = 'contenerdorCol';
    // Se crea el contenedro con el form-group
    var newDiv2 = document.createElement('div');
    newDiv2.className ='form-group';
    newDiv2.id = 'contenerform';    
    // Se crea un nuevo elemento de entrada input
    var newInput = document.createElement('input');
    newInput.type = 'text';  
    newInput.className = 'form-control';
    // Se genera un nuevo id para el elemento de entrada duplicado
    function incrementarCopia() {
        copia++;
    }      
    var newId = 'input' + (container.getElementsByTagName('input').length + 1);
    newInput.id = newId;
    newInput.name = newId;   
    newInput.placeholder = 'Copia ' + copia;
    incrementarCopia();
    //Se crea el col-3 para el boton
    var newDiv3 = document.createElement('div');
    newDiv3.className = 'col-3';
    newDiv3.id = 'contenerdorCol2';
    //se crea el form-group y se ajusta la posicion
    var newDiv4 = document.createElement('div');
    newDiv4.className = 'form-group';
    newDiv4.id = 'contenerform2';    
    // Se crea el boton de remover la copia
    var removerButton = document.createElement("button");
    removerButton.className = 'btn btn-info';
    removerButton.style = 'border-radius: 50%';
    var icono= document.createElement('i');
    icono.className = 'fas fa-minus';

    removerButton.onclick = function () {    
    container.removeChild(newDiv);
    container.removeChild(newDiv3);
    copia = 1;
    };

    container.appendChild(newDiv);
    newDiv.appendChild(newDiv2);
    newDiv2.appendChild(newInput);
    container.appendChild(newDiv3);
    newDiv3.appendChild(newDiv4);
    newDiv4.appendChild(removerButton);
    removerButton.appendChild(icono);
   
}

var copia2 = 1;
function duplicate2(registro) {
    // se crea el contenedor principal
    var container = document.getElementById('contenedorCopia2');    
    // se crea el contenedor con el col-6
    var newDiv = document.createElement('div');
    newDiv.className= 'col-6';
    newDiv.id = 'contenerdorCol';
    // Se crea el contenedro con el form-group
    var newDiv2 = document.createElement('div');
    newDiv2.className ='form-group';
    newDiv2.id = 'contenerform';    
    // Se crea un nuevo elemento de entrada input
    var newInput = document.createElement('input');
    newInput.type = 'text';  
    newInput.className = 'form-control';
    newInput.value = registro; 
    // Se genera un nuevo id para el elemento de entrada duplicado
    function incrementarCopia() {
        copia2++;
    }      
    var newId = 'input' + (container.getElementsByTagName('input').length + 1);
    newInput.id = newId;
    newInput.name = newId;   
    newInput.placeholder = 'Copia ' + copia2;
    incrementarCopia();
    //Se crea el col-3 para el boton
    var newDiv3 = document.createElement('div');
    newDiv3.className = 'col-3';
    newDiv3.id = 'contenerdorCol2';
    //se crea el form-group y se ajusta la posicion
    var newDiv4 = document.createElement('div');
    newDiv4.className = 'form-group';
    newDiv4.id = 'contenerform2';    
    // Se crea el boton de remover la copia
    var removerButton = document.createElement("button");
    removerButton.className = 'btn btn-info';
    removerButton.style = 'border-radius: 50%';
    var icono= document.createElement('i');
    icono.className = 'fas fa-minus';

    removerButton.onclick = function () {    
    container.removeChild(newDiv);
    container.removeChild(newDiv3);
    copia2 = 1;
    };

    container.appendChild(newDiv);
    newDiv.appendChild(newDiv2);
    newDiv2.appendChild(newInput);
    container.appendChild(newDiv3);
    newDiv3.appendChild(newDiv4);
    newDiv4.appendChild(removerButton);
    removerButton.appendChild(icono);
   
}

var copia3 = 1;
function duplicate3() {
    // se crea el contenedor principal
    var container = document.getElementById('contenedorCopia2');    
    // se crea el contenedor con el col-6
    var newDiv = document.createElement('div');
    newDiv.className= 'col-6';
    newDiv.id = 'contenerdorCol';
    // Se crea el contenedro con el form-group
    var newDiv2 = document.createElement('div');
    newDiv2.className ='form-group';
    newDiv2.id = 'contenerform';    
    // Se crea un nuevo elemento de entrada input
    var newInput = document.createElement('input');
    newInput.type = 'text';  
    newInput.className = 'form-control';
    // Se genera un nuevo id para el elemento de entrada duplicado
    function incrementarCopia() {
        copia3++;
    }      
    var newId = 'input' + (container.getElementsByTagName('input').length + 1);
    newInput.id = newId;
    newInput.name = newId;   
    newInput.placeholder = 'Copia ' + copia3;
    incrementarCopia();
    //Se crea el col-3 para el boton
    var newDiv3 = document.createElement('div');
    newDiv3.className = 'col-3';
    newDiv3.id = 'contenerdorCol2';
    //se crea el form-group y se ajusta la posicion
    var newDiv4 = document.createElement('div');
    newDiv4.className = 'form-group';
    newDiv4.id = 'contenerform2';    
    // Se crea el boton de remover la copia
    var removerButton = document.createElement("button");
    removerButton.className = 'btn btn-info';
    removerButton.style = 'border-radius: 50%';
    var icono= document.createElement('i');
    icono.className = 'fas fa-minus';

    removerButton.onclick = function () {    
    container.removeChild(newDiv);
    container.removeChild(newDiv3);
    copia3 = 1;
    };

    container.appendChild(newDiv);
    newDiv.appendChild(newDiv2);
    newDiv2.appendChild(newInput);
    container.appendChild(newDiv3);
    newDiv3.appendChild(newDiv4);
    newDiv4.appendChild(removerButton);
    removerButton.appendChild(icono);
   
}


/* Función para añadir los controles de cada elemento de cada fila */
function funciones_elementos_fila(num_consecutivo) {
    /* SELECT 2 LISTADO DOCUMENTOS SOLICITADOS */
    $("#lista_docs_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    // Cargue de Documentos solicitados
    let token = $("input[name='_token']").val();
    let datos_consultar_documentos_solicitados = {
        '_token': token,
        'parametro' : "listado_documentos_solicitados",
    };
    $.ajax({
        type:'POST',
        url:'/CargarDatosSolicitados',
        data: datos_consultar_documentos_solicitados,
        success:function(data){
            // $("select[id^='lista_docs_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_docs_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Documento"]+'">'+data[claves[i]]["Nombre_documento"]+'</option>');
            }
        }
    });

    /* SELECT 2 LISTADO SOLICITANTES */
    $("#lista_solicitante_fila_"+num_consecutivo).select2({
        width: '100%',
        placeholder: "Seleccione",
        allowClear: false
    });

    // Cargue de listado de Solicitantes
    let datos_consultar_solicitantes = {
        '_token': token,
        'parametro' : "listado_solicitantes",
    };
    $.ajax({
        type:'POST',
        url:'/CargarDatosSolicitados',
        data: datos_consultar_solicitantes,
        success:function(data){
            // $("select[id^='lista_docs_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_solicitante_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_solicitante"]+'">'+data[claves[i]]["Solicitante"]+'</option>');
            }
        }
    });
}

/* Si se selecciona la opción Otros Documentos Inserta un campo de texto (SELECTOR DE DOCUMENTOS) */
$(document).on('change', "select[id^='lista_docs_fila_']", function(){
    
    var id_selecccionado = $(this).attr("id");
    var consecutivo = id_selecccionado.match(/[0-9]+/);
    if ($(this).find('option:selected').text() == 'Otros documentos') {
        $string_input_otro_doc = '<input type="text" class="form-control" name="nombre_otro_doc" id="nombre_otro_doc_'+consecutivo[0]+'" placeholder="Escriba el nombre del documento." required>';
        $('#contenedor_otro_doc_fila_'+consecutivo[0]).append($string_input_otro_doc);
    }else{
        $('#contenedor_otro_doc_fila_'+consecutivo[0]).empty();
    }
});

/* Si se selecciona la opción Otro Cual Inserta un campo de texto (SELECTOR DE SOLICITANTES) */
$(document).on('change', "select[id^='lista_solicitante_fila_']", function(){
    var id_selecccionado = $(this).attr("id");
    var consecutivo = id_selecccionado.match(/[0-9]+/);
    if ($(this).find('option:selected').text() == 'Otro/¿Cual?') {
        $string_input_otro_doc = '<input type="text" class="form-control" name="nombre_otro_solicitante" id="nombre_otro_solicitante_'+consecutivo[0]+'" placeholder="Escriba el nombre del solicitante." required>';
        $('#contenedor_otro_solicitante_fila_'+consecutivo[0]).append($string_input_otro_doc);
    }else{
        $('#contenedor_otro_solicitante_fila_'+consecutivo[0]).empty();
    }
});

/* FUNCIONALIDAD PARA RECOPILAR LOS DATOS POR CADA FILA DE LA TABLA */
$(document).ready(function(){
    $("#guardar_datos_tabla").click(function(){

        // Validación: Se checkea la opción no aporta documentos y se intenta enviar pero ya con registros existentes en la tabla
        if ($("#No_aporta_documentos").is(':checked') == true && $("#conteo_listado_documentos_solicitados").val() > 0) {
            $("#No_aporta_documentos").prop("checked", false);
            $('#resultado_insercion').removeClass('d-none');
            $('#resultado_insercion').addClass('alert-danger');
            $('#resultado_insercion').append('<strong>No puede seleccionar la opción No aporta documentos debido a que existe información guardada en el sistema.</strong>');
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
                    url:'/GuardarDocumentosSolicitados',
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
        
                localStorage.setItem("#guardar_datos_tabla", true);
        
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
    
                    localStorage.setItem("#guardar_datos_tabla", true);
        
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
    
                }else{
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
    if (localStorage.getItem("#guardar_datos_tabla")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#guardar_datos_tabla");
        document.querySelector("#clicGuardado").click();
    }

});

/* FUNCIONALIDAD PARA ELIMINAR LAS FILAS INFORMATIVAS */
$(document).ready(function(){

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
            url:'/EditarFecha_Recepcion_Doc_soliPCl',
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
    
});


