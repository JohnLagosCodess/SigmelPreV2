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
    // llenado del formulario para la captura de datos del modulo de Juntas
    $('#form_calificacionJuntas').submit(function (e) {
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
            url:'/registrarCalificacionJuntas',
            data: datos_agregarCalificacionJuntas,
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

});
