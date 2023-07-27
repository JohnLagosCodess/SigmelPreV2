$(document).ready(function(){

    // Inicializacion del select2 de listados  Módulo Calificacion PCL

    $(".modalidad_calificacion").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
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
});

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
        let token = $("input[name='_token']").val();
        let envio_datos = {
            '_token': token,
            'datos_finales_documentos_solicitados' : datos_finales_documentos_solicitados,
            'Id_evento': $('#newId_evento').val(),
            'Id_Asignacion': $('#newId_asignacion').val(),
            'Id_proceso': $('#Id_proceso').val()
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
                }
            }
        });

        localStorage.setItem("#guardar_datos_tabla", true);

        setTimeout(() => {
            location.reload();
        }, 3000);

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
        };
        
        $.ajax({
            type:'POST',
            url:'/EliminarFila',
            data: datos_fila_quitar,
            success:function(response){

                if (response.parametro == "fila_eliminada") {
                    $('#resultado_insercion').removeClass('d-none');
                    $('#resultado_insercion').addClass('alert-success');
                    $('#resultado_insercion').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_insercion').addClass('d-none');
                        $('#resultado_insercion').removeClass('alert-success');
                        $('#resultado_insercion').empty();
                    }, 3000);
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
    
});