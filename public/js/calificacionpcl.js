$(document).ready(function(){

    // Inicializacion del select2 de listados  Módulo Calificacion PCL

    $(".modalidad_calificacion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    // Inicializacion del select2 modal agregar seguimiento
    $(".causal_seguimiento").select2({
        placeholder:"Seleccione una opción",
        allowClear:false,
    });
    // llenado de selectores

    let token = $('input[name=_token]').val();

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

    /* Obtener el ID del evento a dar clic en cualquier botón de cargue de archivo y asignarlo al input hidden del id evento */
    $("input[id^='listadodocumento_']").click(function(){
        let idobtenido = $('#newId_evento').val();
        console.log(idobtenido);
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

    // llenado del formulario para la captura de la modal de Agregar Seguimiento

    $('#form_agregar_seguimientoPcl').submit(function (e) {
        e.preventDefault();    
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
                    $("#Guardar_seguimientos").addClass('d-none');
                    $("#Actualizar_seguimientos").removeClass('d-none');
                    $('.alerta_seguimiento').removeClass('d-none');
                    $('.alerta_seguimiento').append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(function(){
                        $('.alerta_seguimiento').addClass('d-none');
                        $('.alerta_seguimiento').empty();
                        $("#Guardar_seguimientos").removeClass('d-none');
                        document.querySelector("#Guardar_seguimientos").disabled = true;                        
                    }, 7000);
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

    // Captura de data para la tabla de Historial de seguimientos
    // dentro de la modal de agregar seguimientos

    let datos_historial_seguimiento ={
        '_token':token,
        'HistorialSeguimientoPcl': "CargaHistorialSeguimiento"
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
                "lengthMenu": "Mostrar _MENU_ resgistros",
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

      
});

/* PRUEBAS */
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
        url:'/CargarDocsSolicitados',
        data: datos_consultar_documentos_solicitados,
        success:function(data){
            // $("select[id^='lista_docs_fila_']").empty();
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#lista_docs_fila_"+num_consecutivo).append('<option value="'+data[claves[i]]["Nro_documento"]+'">'+data[claves[i]]["Nombre_documento"]+'</option>');
            }
        }
    });

}
/* Si se selecciona la opción Otros Documentos Inserta un campo de texto */
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

    