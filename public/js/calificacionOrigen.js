$(document).ready(function(){

    /* $(".tipo_evento_doc").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });
    
    $(".grupo_documental").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    }); */

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

    // llenado del formulario para la captura de datos del modulo de calificacion Origen ATEL
    $('#form_calificacionOrigen').submit(function (e) {
        e.preventDefault();  
        
        document.querySelector("#Edicion").disabled = true;
        document.querySelector("#Borrar").disabled = true;

        var newId_evento = $('#newId_evento').val();
        var newId_asignacion = $('#newId_asignacion').val();
        var Id_proceso = $('#Id_proceso').val();
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
               /* $("#cargue_docs_modal_listado_docs").prop('disabled', true);
               $("#cargue_docs_modal_listado_docs").hover(function(){
                   $(this).css('cursor', 'not-allowed');
               }); */

        } else {
               $("#btn_agregar_fila").css('display', 'block');
              /*  $("#cargue_docs_modal_listado_docs").prop('disabled', false);
               $("#cargue_docs_modal_listado_docs").hover(function(){
                   $(this).css('cursor', 'pointer');
               }); */
        }
    });
    //Guardar documetos seguimiento
    $("#guardar_datos_tabla").click(function(){

        let token = $("input[name='_token']").val();
        var guardar_datos = [];
        var datos_finales_documentos_solicitados = [];
        var coincidencia_2 = "lista_solicitante_fila_";
        var vali_gr_doc=$('#grupo_documental').val();

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
                'articulo_12': $('#No_aporta_documentos').filter(":checked").val(),
                'grupo_documental': $('#grupo_documental').val(),
                'tipo_evento_doc': $('#tipo_evento_doc').val(),
                'parametro': "datos_bitacora"
            };
            console.log(envio_datos)
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
            // Validación: No se inserta datos y selecciona el checkbox de No aporta documentos
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

            } else if(vali_gr_doc==''){
                $('#resultado_insercion').removeClass('d-none');
                $('#resultado_insercion').addClass('alert-danger');
                $('#resultado_insercion').append('<strong>Seleccione un grupo documental.</strong>');
                setTimeout(() => {
                    $('#resultado_insercion').addClass('d-none');
                    $('#resultado_insercion').removeClass('alert-danger');
                    $('#resultado_insercion').empty();
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