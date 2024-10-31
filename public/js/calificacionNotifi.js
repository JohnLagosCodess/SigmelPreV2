$(document).ready(function(){

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
    // llenado del formulario para la captura de datos del modulo de Notificaciones
    $('#form_calificacionNotifi').submit(function (e) {
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
        var descripcion_accion = $('#descripcion_accion').val();
        var banderaguardar =$('#bandera_accion_guardar_actualizar').val();

        let token = $('input[name=_token]').val();
        
        var datos_agregarCalificacionNotifi = {
            '_token': token,
            'newId_evento':newId_evento,
            'newId_asignacion':newId_asignacion,
            'Id_proceso':Id_proceso,
            'f_accion':f_accion,
            'accion':accion,
            'fecha_alerta':fecha_alerta,
            'enviar':enviar,
            'descripcion_accion':descripcion_accion,
            'bandera_accion_guardar_actualizar':banderaguardar,
        }

        $.ajax({
            type:'POST',
            url:'/registrarCalificacionNotifi',
            data: datos_agregarCalificacionNotifi,
            success:function(response){
                if (response.parametro == 'agregarCalificacionNotifi') {
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
    /* Envío de Información del Documento a Cargar */
    $("form[id^='formulario_documento_']").submit(function(e){

        e.preventDefault();
        var formData = new FormData($(this)[0]);
        var cambio_estado = $(this).parents()[1]['children'][2]["id"];
        var input_documento = $(this).parents()[0]['children'][0][4]["id"];
        var id_reg_doc = $(this).data("id_reg_doc");
        var id_doc = $(this).data("id_doc");
        let tipoDoc = $(this).data('tipo_documento');

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

});
