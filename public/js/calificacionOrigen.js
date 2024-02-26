$(document).ready(function(){

    /* $(".tipo_evento_doc").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    
    $(".grupo_documental").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    }); */

    $(".forma_envio").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".accion").select2({
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".causal_devolucion_comite").select2({      
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".departamento_destinatario").select2({      
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".ciudad_destinatario").select2({      
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".forma_envio_act").select2({      
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    $(".reviso").select2({      
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    /* FUNCIONALIDAD DESCARGA DOCUMENTO */
    $("a[id^='btn_generar_descarga_']").click(function(){
        var id_documento = $(this).data('id_documento_descargar');
        var nombre_documento = $("#nombre_documento_descarga_"+id_documento).val();
        var extension_documento = $("#extension_documento_descarga_"+id_documento).val();
        var id_evento = nombre_documento.match(/\d+/g).join('');
    
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

    //Scroll para table de documen sugeridos
    var listado_docs_segueridos = $('#listado_docs_sugeridos').DataTable({
        "responsive": true,
        "info": false,
        "searching": false,
        "ordering": false,
        "scrollCollapse": true,
        "scrollY": "20vh",
        "paging": false,
        "language":{
            "emptyTable": "No se encontró información"
        }
    });
    autoAdjustColumns(listado_docs_segueridos);

    // llenado de selectores
    let token = $('input[name=_token]').val();

    //Listado de tipo evento
    let datos_lista_tipo_evento = {
        '_token': token,
        'parametro':"lista_tipo_evento"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresOrigenAtel',
        data: datos_lista_tipo_evento,
        success:function(data) {
            //console.log(data);
            let IdtipoEvento = $('select[name=tipo_evento_doc]').val();
            let tipoevento = Object.keys(data);
            for (let i = 0; i < tipoevento.length; i++) {
                if (data[tipoevento[i]]['Id_Evento'] != IdtipoEvento) {  
                    $('#tipo_evento_doc').append('<option value="'+data[tipoevento[i]]["Id_Evento"]+'">'+data[tipoevento[i]]["Nombre_evento"]+'</option>');
                }
            }
        }
    });

    // Validar el tipo de Evento 
    var tipo_evento_listado_docSeguimiento = $('#tipo_evento_doc').val();

    if (tipo_evento_listado_docSeguimiento == 3 || tipo_evento_listado_docSeguimiento == 4) {
        $('#div_GrupoDocumental').addClass('d-none');
        $('#div_GrupoDocumental').attr('required', false);
        $('#div_DocumentosSugeridos').addClass('d-none');
    }

    // Ocultar el grupo documental y documentos sugeridos 

    $('#tipo_evento_doc').on('change', function() {
        // Actualizar la variable tipo_evento_listado_docSeguimiento con el nuevo valor seleccionado
        var tipo_evento_lista_docSeguimiento = $(this).val();
        
        if (tipo_evento_lista_docSeguimiento == 3 || tipo_evento_lista_docSeguimiento == 4) {
            $('#div_GrupoDocumental').addClass('d-none');
            $('#div_GrupoDocumental').attr('required', false);
            $('#div_DocumentosSugeridos').addClass('d-none');
        } else {
            $('#div_GrupoDocumental').removeClass('d-none');
            $('#div_GrupoDocumental').attr('required', true);
            $('#div_DocumentosSugeridos').removeClass('d-none');
        }
        
      });

    //Listado Grupo documental 
    var tipo_evento_doc = $('#tipo_evento_doc').val();
    let datos_lista_grupo_documental = {
        '_token': token,
        'tipo_evento_doc': tipo_evento_doc,
        'parametro':"lista_grupo_documental"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresOrigenAtel',
        data: datos_lista_grupo_documental,
        success:function(data) {
            //console.log(data);
            let IdtipoDocumental = $('select[name=grupo_documental]').val();
            let tipoDocumental = Object.keys(data);
            for (let i = 0; i < tipoDocumental.length; i++) {
                if (data[tipoDocumental[i]]['Id_Tipo_documento'] != IdtipoDocumental) {
                    $('#grupo_documental').append('<option value="'+data[tipoDocumental[i]]["Id_Tipo_documento"]+'">'+data[tipoDocumental[i]]["Tipo_documento"]+'</option>');
                }
            }
        }
    });
    // Listado Grupo documental cuando se realice cambio
    $('#tipo_evento_doc').change( function(){
        let id_tipo_evento = $('#tipo_evento_doc').val();
        let datos_nombre_documental = {
            '_token': token,
            'parametro' : "lista_tipo_documental",
            'tipo_evento_doc': id_tipo_evento
        };

        $.ajax({
            type:'POST',
            url:'/selectoresOrigenAtel',
            data: datos_nombre_documental,
            success:function(data) {
                $("#grupo_documental").empty();
                let IdDocumental = $('select[name=grupo_documental]').val();
                $('#grupo_documental').append('<option value="" selected>Seleccione</option>');
                let nombredocumental = Object.keys(data);
                for (let i = 0; i < nombredocumental.length; i++) {
                    if (data[nombredocumental[i]]['Id_Tipo_documento'] != IdDocumental) { 
                        $('#grupo_documental').append('<option value="'+data[nombredocumental[i]]["Id_Tipo_documento"]+'">'+data[nombredocumental[i]]["Tipo_documento"]+'</option>');
                    }
                }
            }
        });
    });
    
    //Mostrar los documetos sugeridos
    $('#grupo_documental').change(function() {
        let id_gr_documental = $(this).val();
        let datos_sugerido_documentos = {
          '_token': token,
          'parametro': "lista_doc_sugeridos",
          'id_gr_documental': id_gr_documental
        };
      
        $.ajax({
          type: 'POST',
          url: '/selectoresOrigenAtel',
          data: datos_sugerido_documentos,
          success: function(data) {
            //console.log(data);
            $("#datos_visuales").empty();
            let nombredocumental = Object.keys(data);
            for (let i = 0; i < nombredocumental.length; i++) {
              $('#datos_visuales').append('<tr><td><a href="javascript:void(0);" id="btn_insertar_documen_visual_'+data[i]["Id_documental"]+'" data-id_fila_agregar_doc="'+data[i]["Id_documental"]+'"  data-nom_fila_agregar_doc="'+data[i]["Documento"]+'">' + data[nombredocumental[i]]["Documento"] + '</a></td></tr>');
            }
          }
        });
    });

    //Listado de causal de devolucion comite calificacion PCL    
    var Id_asignacion_pro = $('#newId_asignacion').val();
    var Id_proceso_actual = $('#Id_proceso').val();
    let datos_lista_causal_devolucion_comite = {
        '_token': token,
        'parametro':"lista_causal_devo_comite",
        'Id_asignacion_pro':Id_asignacion_pro,
        'Id_proceso_actual':Id_proceso_actual
    };
    
    $.ajax({
        type:'POST',
        url:'/selectoresOrigenAtel',
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
        url: 'selectoresOrigenAtel',
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

    var accion_realizarinput = $('#bd_id_accion').val();

    if (accion_realizarinput == 7) {
        $('#div_causal_devolucion_comite').removeClass('d-none');        
    }

    var accion_realizarselect = $('#accion');
    accion_realizarselect.change(function() {
        var valoraccion_realizarselect = $(this).val();
        if (valoraccion_realizarselect == 7) {
            $('#div_causal_devolucion_comite').removeClass('d-none');
        } else {
            $('#div_causal_devolucion_comite').addClass('d-none');            
        }  
    });
    
    // llenado del formulario para la captura de datos del modulo de calificacion Origen ATEL
    $('#form_calificacionOrigen').submit(function (e) {
        e.preventDefault();  
        
        document.querySelector("#Edicion").disabled = true;
        document.querySelector("#Borrar").disabled = true;

        var newId_evento = $('#newId_evento').val();
        var newId_asignacion = $('#newId_asignacion').val();
        var Id_proceso = $('#Id_proceso').val();
        var Id_servicio = $("#Id_servicio").val();
        var fecha_devolucion = $('#fecha_devolucion').val();   
        var f_accion = $('#f_accion').val();
        var accion = $('#accion').val();
        var fecha_alerta = $('#fecha_alerta').val();
        var enviar = $('#enviar').val();
        var causal_devolucion_comite = $('#causal_devolucion_comite').val();
        var descripcion_accion = $('#descripcion_accion').val();
        var banderaguardar =$('#bandera_accion_guardar_actualizar').val();

        let token = $('input[name=_token]').val();
        
        var datos_agregarCalificacionOrigen = {
            '_token': token,
            'newId_evento':newId_evento,
            'newId_asignacion':newId_asignacion,
            'Id_proceso':Id_proceso,
            'Id_servicio': Id_servicio,
            'fecha_devolucion': fecha_devolucion,
            'f_accion':f_accion,
            'accion':accion,
            'fecha_alerta':fecha_alerta,
            'enviar':enviar,
            'causal_devolucion_comite':causal_devolucion_comite,
            'descripcion_accion':descripcion_accion,
            'bandera_accion_guardar_actualizar':banderaguardar,
        }

        $.ajax({
            type:'POST',
            url:'/registrarCalificacionOrigen',
            data: datos_agregarCalificacionOrigen,
            success:function(response){
                if (response.parametro == 'agregarCalificacionOrigen') {
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
    
    //Mostrar Historial de acciones
    $('#Hacciones').click(function(){
        $('#borrar_tabla_historial_acciones').empty();

        var datos_llenar_tabla_historial_acciones = {
             '_token': $('input[name=_token]').val(),
             'ID_evento' : $('#id_evento').val()
         };
         
         $.ajax({
             type:'POST',
             url:'/consultarHistorialAcciones',
             data: datos_llenar_tabla_historial_acciones,
             success:function(data) {
                 if(data.length == 0){
                     $('#borrar_tabla_historial_acciones').empty();
                 }else{
                     // console.log(data);
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
                {"data":"Accion_realizada"},
                {"data":"Descripcion"}
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

    //Insertar documentos sugeridos
    $(document).on('click', "a[id^='btn_insertar_documen_visual_']", function(){
        //Agregar Documento para registrar
        var nom_fila_agregar_doc =  $(this).data("nom_fila_agregar_doc");
        // Añadir Doc Sugerido
        $("#Nom_DocSugerido").empty();
        $("#Nom_DocSugerido").val(nom_fila_agregar_doc);
        var btn_agregar_fila = $('#btn_agregar_fila');
        // Simula hacer clic en el enlace automaticamente
        btn_agregar_fila.click();

    });
    //CUANDO SE HACE CHECK EN LA OPCIÓN NO APORTA DOCUMENTOS */
    $("#No_aporta_documentos").click(function () {
        if ($(this).is(':checked')) {
               $("#btn_agregar_fila").css('display', 'none');
               $("#grupo_documental").prop('disabled', true);
               $("#cargue_docs_modal_listado_docs").prop('disabled', true);
               $("#cargue_docs_modal_listado_docs").hover(function(){
                   $(this).css('cursor', 'not-allowed');
               });

        } else {
               $("#btn_agregar_fila").css('display', 'block');
               $("#grupo_documental").prop('disabled', false);
               $("#cargue_docs_modal_listado_docs").prop('disabled', false);
               $("#cargue_docs_modal_listado_docs").hover(function(){
                   $(this).css('cursor', 'pointer');
               });
        }
    });
    //Guardar documetos seguimiento
    $("#guardar_datos_tabla").click(function(){

        let token = $("input[name='_token']").val();
        var guardar_datos = [];
        var datos_finales_documentos_solicitados = [];
        var coincidencia_2 = "lista_solicitante_fila_";
        var vali_gr_doc = $('#grupo_documental').val();
        var no_apor_doc = $('#No_aporta_documentos').filter(":checked").val();
        //console.log(no_apor_doc);

        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_docs_seguimiento tbody tr').each(function (index) {
            array_id_filas.push($(this).attr('id'));
            if ($(this).attr('id') !== "datos_visuales") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        guardar_datos.push($('#'+nombres_ids).val());
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
        if (datos_finales_documentos_solicitados.length > 0 && no_apor_doc != "No_mas_seguimiento") {
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
                'articulo_12': $('#No_aporta_documentos').filter(":checked").val(),
                'grupo_documental': $('#grupo_documental').val(),
                'tipo_evento_doc': $('#tipo_evento_doc').val(),
                'parametro': "datos_bitacora"
            };
             $.ajax({
                type:'POST',
                url:'/GuardarDocumentosSeguimiento',
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
            // Validación: No se inserta datos si selecciona el checkbox de No aporta documentos
            if ($("#No_aporta_documentos").is(':checked') && vali_gr_doc!='') {
                let envio_datos = {
                    '_token': token,
                    'Id_evento': $('#newId_evento').val(),
                    'Id_Asignacion': $('#newId_asignacion').val(),
                    'Id_proceso': $('#Id_proceso').val(),
                    'grupo_documental': $('#grupo_documental').val(),
                    'parametro': "no_aporta"
                };
        
                $.ajax({
                    type:'POST',
                    url:'/GuardarDocumentosSeguimiento',
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

            // } else if(vali_gr_doc==''){
            //     $('#resultado_insercion').removeClass('d-none');
            //     $('#resultado_insercion').addClass('alert-danger');
            //     $('#resultado_insercion').append('<strong>Seleccione un grupo documental.</strong>');
            //     setTimeout(() => {
            //         $('#resultado_insercion').addClass('d-none');
            //         $('#resultado_insercion').removeClass('alert-danger');
            //         $('#resultado_insercion').empty();
            //     }, 3000);
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
    });
    //Eliminar registro documento de seguimiento
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
            url:'/EliminarFilaSeguimiento',
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
            }
        });

        

    });
    // Abrir modal de agregar seguimiento despues de guardar 
    if (localStorage.getItem("#guardar_datos_tabla")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#guardar_datos_tabla");
        document.querySelector("#clicGuardado").click();
    }
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
        }
        $.ajax({
            type:'POST',
            url:'/captuarDestinatarioOrigen',
            data: datos_destinarioPrincipal,
            success: function(data){
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
        
        if (opc_seleccionada == "Documento_Origen") {
            $("#insertar_mensaje_importante").removeClass('d-none');
            $("#btn_insertar_pruebas").prop('disabled', false);
            $("#asunto").val("SOLICITUD DE PRUEBAS");
            var texto_insertar = "<p>Reciba usted un cordial saludo de Seguros de Vida Alfa S.A.</p><p>Con el fin de establecer la determinación de origen del accidente reportado, está entidad solicita se anexen los siguientes documentos</p><p>{{$pruebas_solicitadas}}</p><p>Lo anterior con fundamento en lo establecido en la Decreto 1072 de 2015 art. 2.2.5.1.29, 2.2.5.1.28 (integración jurídica y análoga.)</p><p>El aporte documental deberá realizarse en un tiempo menor de 30 días hábiles siguientes al recibido de ésta comunicación escrita;&nbsp; La información solicitada es en cumplimiento de lo establecido en la Resolución 1401 de 2007 por la cual se reglamenta la investigación de incidentes y accidentes de trabajo. Lo anterior con fundamento en lo establecido en el Dec.2463 de 2001, parágrafo 1, Res.0156 de 2005 art.3, y Dec.1295 de 1994, art.12: “Toda enfermedad o patología, accidente o muerte, que no hayan sido clasificados o calificados como de origen profesional, se consideran de origen Común”.</p><p>La omisión del aporte documental y de requisitos mínimos para la calificación tendría como consecuencia la generación de concepto desfavorable o de origen común refiriendo el fundamento de dicha decisión.</p>";
            $('#cuerpo_comunicado').summernote('code', texto_insertar);
        }else{
            $("#insertar_mensaje_importante").addClass('d-none');
            $("#btn_insertar_pruebas").prop('disabled', true);
            $("#asunto").val("");
            $('#cuerpo_comunicado').summernote('code', '');
        }
    });


    // llenado del formulario para la captura de la modal de Generar Comunicado
    $('#form_generarComunicadoOrigen').submit(function (e) {
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
        var firmarcomunicado = $('#firmarcomunicado').filter(":checked").val();
        var tipo_descarga = $("[name='tipo_documento_descarga_califi']").filter(":checked").val();
        //Copias Interesadas Origen
        var copiaComunicadoTotal = [];

        $('input[type="checkbox"]').each(function() {
            var copiaComunicado = $(this).attr('id');            
            if (copiaComunicado === 'copia_afiliado' || copiaComunicado === 'copia_empleador' || 
                copiaComunicado === 'copia_eps' || copiaComunicado === 'copia_afp' || 
                copiaComunicado === 'copia_arl') {                
                if ($(this).is(':checked')) {                
                var relacionCopiaValor = $(this).val();
                copiaComunicadoTotal.push(relacionCopiaValor);
                }
            }
        });

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
            'copiaComunicadoTotal':copiaComunicadoTotal,
            'firmarcomunicado':firmarcomunicado,
            'tipo_descarga': tipo_descarga
        }

        document.querySelector("#Generar_comunicados").disabled = true;   
        $.ajax({
            type:'POST',
            url:'/registrarComunicadoOrigen',
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
    }) ;

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
        url:'/historialComunicadoOrigen',
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
                    data-cuerpo_comunicado="'+data[i]["Cuerpo_comunicado"]+'" data-anexos_comunicados="'+data[i]["Anexos"]+'"\
                    data-forma_envio_comunicado="'+data[i]["Forma_envio"]+'" data-nombre_envio_comunicado="'+data[i]["Nombre_forma_envio"]+'"\
                    data-elaboro_comunicado="'+data[i]["Elaboro"]+'"\
                    data-reviso_comunicado="'+data[i]["Reviso"]+'" data-revisonombre_comunicado="'+data[i]["Nombre_lider"]+'"\
                    data-firmar_comunicado="'+data[i]["Firmar_Comunicado"]+'"\
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
        let url_editar_evento = $('#action_actualizar_comunicado').val();        
        $('form[name="formu_comunicado"]').attr("action", url_editar_evento);    
        $('form[name="formu_comunicado"]').removeAttr('id');
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
        var agregar_copia =  $(this).data("agregar_copia");
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
            url: '/modalComunicadoOrigen',
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

        if (tipo_descarga == "Documento_Origen") {
            $("#documentos_origen_editar").prop("checked", true);
            $("#otro_documento_origen_editar").prop("checked", false);
            $("#insertar_mensaje_importante_editar").removeClass('d-none');
        } else {
            $("#documentos_origen_editar").prop("checked", false);
            $("#otro_documento_origen_editar").prop("checked", true);
            $("#insertar_mensaje_importante_editar").addClass('d-none');
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
            }
    
            $.ajax({
                type:'POST',
                url:'/captuarDestinatarioOrigen',
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
                        var ciudad_destinatario_editar =$('#ciudad_destinatario_editar');
                        ciudad_destinatario_editar.empty();
                        ciudad_destinatario_editar.append('<option value="'+data.array_datos_destinatarios[0].Id_municipio_afiliado+'">'+data.array_datos_destinatarios[0].Nombre_municipio_afiliado+'</option>')
                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
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
                        var ciudadafiliado =$('#ciudad_destinatario_editar');
                        ciudadafiliado.empty();
                        ciudadafiliado.append('<option value="'+data.array_datos_destinatarios[0].Id_municipio_empresa+'">'+data.array_datos_destinatarios[0].Nombre_municipio_empresa+'</option>')
                        document.querySelector("#ciudad_destinatario_editar").disabled = true;
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
                    }else if(data.destinatarioPrincipal == 'Otro'){
                        //console.log(data.destinatarioPrincipal);
                        document.querySelector("#nombre_destinatario_editar").disabled = false;
                        document.querySelector("#nic_cc_editar").disabled = false;
                        document.querySelector("#direccion_destinatario_editar").disabled = false;
                        document.querySelector("#telefono_destinatario_editar").disabled = false;
                        document.querySelector("#email_destinatario_editar").disabled = false;
                        document.querySelector("#departamento_destinatario_editar").disabled = false;
                        document.querySelector("#ciudad_destinatario_editar").disabled = false;
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
        
        if (opc_seleccionada == "Documento_Origen") {
            $("#insertar_mensaje_importante_editar").removeClass('d-none');
            $("#btn_insertar_pruebas_editar").prop('disabled', false);
            $("#asunto_editar").val("SOLICITUD DE PRUEBAS");
            var texto_insertar = "<p>Reciba usted un cordial saludo de Seguros de Vida Alfa S.A.</p><p>Con el fin de establecer la determinación de origen del accidente reportado, está entidad solicita se anexen los siguientes documentos</p><p>{{$pruebas_solicitadas}}</p><p>Lo anterior con fundamento en lo establecido en la Decreto 1072 de 2015 art. 2.2.5.1.29, 2.2.5.1.28 (integración jurídica y análoga.)</p><p>El aporte documental deberá realizarse en un tiempo menor de 30 días hábiles siguientes al recibido de ésta comunicación escrita;&nbsp; La información solicitada es en cumplimiento de lo establecido en la Resolución 1401 de 2007 por la cual se reglamenta la investigación de incidentes y accidentes de trabajo. Lo anterior con fundamento en lo establecido en el Dec.2463 de 2001, parágrafo 1, Res.0156 de 2005 art.3, y Dec.1295 de 1994, art.12: “Toda enfermedad o patología, accidente o muerte, que no hayan sido clasificados o calificados como de origen profesional, se consideran de origen Común”.</p><p>La omisión del aporte documental y de requisitos mínimos para la calificación tendría como consecuencia la generación de concepto desfavorable o de origen común refiriendo el fundamento de dicha decisión.</p>";
            $('#cuerpo_comunicado_editar').summernote('code', texto_insertar);
        }else{
            $("#insertar_mensaje_importante_editar").addClass('d-none');
            $("#btn_insertar_pruebas_editar").prop('disabled', true);
            $("#asunto_editar").val("");
            $('#cuerpo_comunicado_editar').summernote('code', '');
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
            $('#Editar_comunicados').prop('disabled', false); 
            // $('#Pdf').prop('disabled', false);           
        } else {
            // Si hay campos vacíos, deshabilita el botón
            $('#Editar_comunicados').prop('disabled', true); 
            // $('#Pdf').prop('disabled', true);         
        }
    }, 1000); // 1000 milisegundos = 1 segundo

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
        var firmarcomunicado = $('#firmarcomunicado_editar').filter(":checked").val();
        var tipo_descarga = $("[name='tipo_documento_descarga_califi_editar']").filter(":checked").val();
       //Copias Interesadas Origen
       var EditComunicadoTotal = [];

       $('input[type="checkbox"]').each(function() {
            var copiaComunicado2 = $(this).attr('id');            
            if (copiaComunicado2 === 'edit_copia_afiliado' || copiaComunicado2 === 'edit_copia_empleador' || 
                copiaComunicado2 === 'edit_copia_eps' || copiaComunicado2 === 'edit_copia_afp' || 
                copiaComunicado2 === 'edit_copia_arl') {                
                if ($(this).is(':checked')) {                
                var relacionCopiaValor2 = $(this).val();
                EditComunicadoTotal.push(relacionCopiaValor2);
                }
            }
       });
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
            'agregar_copia_editar':EditComunicadoTotal,
            'firmarcomunicado':firmarcomunicado,
            'tipo_descarga':tipo_descarga
        }

        document.querySelector("#Editar_comunicados").disabled = true;     
        $.ajax({
            type:'POST',
            url:'/actualizarComunicadoOrigen',
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
    //Guardar historial de seguimientos Origen
    $("#guardar_datos_seguimiento").click(function(){ 

        var guardar_datos = [];
        var datos_finales_documentos_solicitados = [];
        var array_id_filas = [];
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#listado_histori_seguimiento tbody tr').each(function (index) {
            array_id_filas.push($(this).attr('id'));
            if ($(this).attr('id') !== "datos_visuales_segui" && $(this).attr('id') !== "primer_seguimiento" && $(this).attr('id') !== "segundo_seguimiento" && $(this).attr('id') !== "tercer_seguimiento") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        guardar_datos.push($('#'+nombres_ids).val());
                    }
                    if((index2+1) % 5 === 0){
                        datos_finales_documentos_solicitados.push(guardar_datos);
                        guardar_datos = [];
                    }
                });
            }
        });
        
        let token = $("input[name='_token']").val();
        let envio_seguimientos = {
            '_token': token,
            'datos_finales_documentos_solicitados' : datos_finales_documentos_solicitados,
            'Id_evento': $('#newId_evento').val(),
            'Id_Asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val(),
            'primer_causal': $('#primer_causal').val(),
            'descrip_seguimiento1': $('#descrip_seguimiento1').val(),
            'segundo_causal': $('#segundo_causal').val(),
            'descrip_seguimiento2': $('#descrip_seguimiento2').val(),
            'tercer_causal': $('#tercer_causal').val(),
            'descrip_seguimiento3': $('#descrip_seguimiento3').val(),
            'parametro': "datos_seguimientos_origen"
        };
        $.ajax({
            type:'POST',
            url:'/GuardarHistorialSeguiOrigen',
            data: envio_seguimientos,
            success:function(response){
                // console.log(response);
                if (response.parametro == "inserto_informacion") {
                    $('#resultado_insercion_seguimiento').removeClass('d-none');
                    $('#resultado_insercion_seguimiento').addClass('alert-success');
                    $('#resultado_insercion_seguimiento').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $('#resultado_insercion_seguimiento').addClass('d-none');
                        $('#resultado_insercion_seguimiento').removeClass('alert-success');
                        $('#resultado_insercion_seguimiento').empty();
                    }, 3000);
                }
            }
        });
        localStorage.setItem("#guardar_datos_seguimiento", true);
        setTimeout(() => {
            location.reload();
        }, 3000);

    });

    //Eliminar registro historial seguimiento
    $(document).on('click', "a[id^='btn_remover_fila_visual_segui_']", function(){

        var id_seleccion = $(this).attr("id");

        let token = $("input[name='_token']").val();
        let datos_segui_fila_quitar = {
            '_token': token,
            'fila' : $(this).data("id_fila_quitar_segui"),
            'Id_evento': $('#newId_evento').val()
        };
        
        $.ajax({
            type:'POST',
            url:'/EliminarFilaHistoSeguimiento',
            data: datos_segui_fila_quitar,
            success:function(response){
                // console.log(response);
                if (response.parametro == "fila_eliminada") {
                    $('#resultado_insercion_seguimiento').empty();
                    $('#resultado_insercion_seguimiento').removeClass('d-none');
                    $('#resultado_insercion_seguimiento').addClass('alert-success');
                    $('#resultado_insercion_seguimiento').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion_seguimiento').addClass('d-none');
                        $('#resultado_insercion_seguimiento').removeClass('alert-success');
                        $('#resultado_insercion_seguimiento').empty();
                    }, 3000);
                }
                /* if (response.total_registros == 0) {
                    $("#conteo_listado_documentos_solicitados").val(response.total_registros);
                } */
                localStorage.setItem("#eliminar_datos_seguimiento", true);
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        });
    });
    
    // Abrir modal de agregar seguimiento despues de guardar historial seguimiento
    if (localStorage.getItem("#guardar_datos_seguimiento")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#guardar_datos_seguimiento");
        document.querySelector("#clicGuardado").click();
    }
    // Abrir modal de agregar seguimiento despues de eliminar historial seguimiento
    if (localStorage.getItem("#eliminar_datos_seguimiento")) {
        // Simular el clic en la etiqueta a después de recargar la página
        localStorage.removeItem("#eliminar_datos_seguimiento");
        document.querySelector("#clicGuardado").click();
    }


});

/* Función para añadir los controles de cada elemento de cada fila */
function funciones_elementos_fila(num_consecutivo) {
    
    let token = $("input[name='_token']").val();

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