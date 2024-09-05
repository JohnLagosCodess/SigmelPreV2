$(document).ready(function () {
    /* Lo comentareado no se utiliza, lo dejo por si acaso.
    // Inicialización select2 listado de procesos parametrizados
    $(".procesos_parametrizados").select2({
        width: '100%',
        placeholder:"Selecione una opción",
        allowClear:false
    });

    // Inicializacion del select2 del listado de servicios
    $(".redireccionar").select2({
        width: '100%',
        placeholder:"Selecione una opción",
        allowClear:false
    });

    // Inicialización select 2 listado de accciones
    $(".accion").select2({
        width: '100%',
        placeholder:"Selecione una opción",
        allowClear:false
    });

    // Inicialización select2 listado profesional bandeja de Notificaciones
    $(".profesional").select2({
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });
        // listado de procesos que almenos tiene configurado una paramétrica
    let datos_lista_procesos_parametrizados = {
        '_token':token,
        'parametro':"listado_procesos_parametrizados"
    }

    $.ajax({
        type:'POST',
        url:'/selectoresBandejaNotifi',
        data: datos_lista_procesos_parametrizados,
        success:function (data) {
            // //console.log(data);
            $('#procesos_parametrizados').append('<option value="" selected>Seleccione</option>');
            let procesos_parametrizados = Object.keys(data);
            for (let i = 0; i < procesos_parametrizados.length; i++) {
                $('#procesos_parametrizados').append('<option value="'+data[procesos_parametrizados[i]]['Id_proceso']+'">'+data[procesos_parametrizados[i]]['Nombre_proceso']+'</option>')
            }
        }
    });

    //Listado de servicios de bandeja Notificaciones
    $('#procesos_parametrizados').change(function(){
        let datos_lista_servicio = {
            '_token': token,
            'parametro':"lista_servicios_notifi",
            'id_proceso': $(this).val()
        };
        $.ajax({
            type:'POST',
            url:'/selectoresBandejaNotifi',
            data: datos_lista_servicio,
            success:function(data){
                $('#accion').empty();
                $('#profesional').empty();
                $(".columna_selector_profesional").slideUp('slow');
                $('#redireccionar').empty();
                $('#redireccionar').append('<option value="" selected>Seleccione</option>');
                let servicionotifi = Object.keys(data);
                for (let i =0; i < servicionotifi.length; i++ ){
                    $('#redireccionar').append('<option value="'+data[servicionotifi[i]]['Id_Servicio']+'">'+data[servicionotifi[i]]['Nombre_servicio']+'</option>');
                }
            }
        });
    });

    $(".columna_selector_profesional").slideUp('slow');
    // listado de acciones a ejecutar dependiendo del proceso y servicio (es decir lo de parametrizaciones)
    $("#redireccionar").change(function(){
        let datos_listado_accion = {
            '_token': token,
            'Id_proceso': $("#procesos_parametrizados").val(),
            'Id_servicio': $(this).val(),
        //    'Id_asignacion' : arrayIdCheckActualizar,
            'parametro' : "listado_accion"
        };
   
        $.ajax({
            type:'POST',
            url:'/selectoresBandejaNotifi',
            data: datos_listado_accion,
            success:function(data) {
                if (data.length > 0) {
                    $('#accion').empty();
                    $('#accion').append('<option></option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $('#accion').append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Nombre_accion"]+'</option>');
                    }
                    
                    $(".no_ejecutar_parametrica_bandeja_trabajo").addClass('d-none');
                    $("#btn_guardar").removeClass('d-none');
                } else {
                    $('#accion').empty();
                    $('#accion').append('<option></option>');

                    $(".no_ejecutar_parametrica_bandeja_trabajo").removeClass('d-none');
                    $("#btn_guardar").addClass('d-none');
                }
            }
        });
    });

    // VALIDACIÓN PARA DETERMINAR QUE LA PARAMÉTRICA QUE SE CONFIGURE PARA EL MÓDULO NUEVO ESTE EN UN VALOR DE SI EN LA TABLA sigmel_informacion_parametrizaciones_clientes //
    var validar_bandeja_trabajo = setInterval(() => {
        if($("#procesos_parametrizados").val() != '' && $("#redireccionar").val() != '' && $("#accion").val() != ''){
            let datos_ejecutar_parametrica_bandeja_trabajo = {
                '_token': token,
                'parametro': "validarSiBandejaTrabajo",
                'Id_proceso': $("#procesos_parametrizados").val(),
                'Id_servicio': $("#redireccionar").val(),
                'Id_accion': $("#accion").val(),
            };
            // //console.log(datos_ejecutar_parametrica_bandeja_trabajo);
            $.ajax({
                type:'POST',
                url:'/validacionParametricaEnSi',
                data: datos_ejecutar_parametrica_bandeja_trabajo,
                success:function(data) {
                    // //console.log(data);
                    if(data.length > 0){
                        if (data[0]["Bandeja_trabajo"] !== "Si") {
                            $(".no_ejecutar_parametrica_bandeja_trabajo").removeClass('d-none');
                            $("#btn_guardar").addClass('d-none');
                            $(".columna_selector_profesional").slideUp('slow');
                        } else {
                            $(".no_ejecutar_parametrica_bandeja_trabajo").addClass('d-none');
                            $("#btn_guardar").removeClass('d-none');
                            clearInterval(validar_bandeja_trabajo);

                            // Cargue Listado de seleccion profesional bandeja Notificaciones
                            $(".columna_selector_profesional").slideDown('slow');
                            let datos_lista_profesional={
                                '_token':token,
                                'parametro':"lista_profesional_notifi"
                            }
                        
                            $.ajax({
                                type:'POST',
                                url:'/selectoresBandejaNotifi',
                                data: datos_lista_profesional,
                                success:function (data) {
                                    $('#profesional').empty();
                                    $('#profesional').append('<option value="" selected>Seleccione</option>');
                                    let profecionalnotifi = Object.keys(data);
                                    for (let i = 0; i < profecionalnotifi.length; i++) {
                                        $('#profesional').append('<option value="'+data[profecionalnotifi[i]]['id']+'">'+data[profecionalnotifi[i]]['name']+'</option>')
                                    }
                                    
                                }
                            });
                        }
                    }
                }
            });
        }
    }, 500);

    //Captura id Checkbox para extraer su value
    var arrayIdCheckActualizar = [];
    $(document).on('change', "input[id^='actualizar_id_asignacion_']", function(){
        var IdCheckActualizar = $(this).val();
        if ($(this).is(':checked')) {
            arrayIdCheckActualizar.push(IdCheckActualizar);
            ////console.log('array lleno');
            ////console.log(arrayIdCheckActualizar);
        }else{
            eliminarElemento(IdCheckActualizar);            
        }       
        
    });
        //Seteo de todos los checkbox
    $(document).on('change', "#toggleButton", function () {         
        var isChecked = $(this).is(":checked");

        if (isChecked) {
            // Establecer estado de todos los checkboxes
            $("input[id^='actualizar_id_asignacion_']").prop("checked", isChecked);              
    
            setTimeout(() => {            
                $('#Bandeja_Notifi input[type="checkbox"]').each(function() {
                    arrayIdCheckActualizar.push($(this).val());
                });
                arrayIdCheckActualizar.splice(0,1);
                ////console.log('seleccion grupal')
                ////console.log(arrayIdCheckActualizar);
            }, 2000);            
        }else{            
            setTimeout(() => {
                $('#Bandeja_Notifi input[type="checkbox"]').each(function() {
                    eliminarElemento($(this).val());           
                });
            }, 2000);
           $("input[id^='actualizar_id_asignacion_']").prop("checked", false);              
        }
    })
            // Función para eliminar el elemento del array al desmarcar checkbox
    function eliminarElemento(elemento) {
        var index = arrayIdCheckActualizar.indexOf(elemento);

        if (index > -1) {
            arrayIdCheckActualizar.splice(index, 1);
            ////console.log("Elemento eliminado: ", elemento);
            ////console.log(arrayIdCheckActualizar);
        } else {
            ////console.log("Elemento no encontrado en el array: ", elemento);
            ////console.log(arrayIdCheckActualizar);
        }
    };
      //Asignar ruta del formulario de modulo calificacion Notifi
    $(document).on('mouseover',"input[id^='modulo_califi_Notifi_']", function(){
        let url_editar_evento = $('#action_modulo_calificacion_Notifi').val();
        $("form[id^='form_modulo_calificacion_Notifi_']").attr("action", url_editar_evento);    
    });


    $('#form_proser_bandejaNotifi').submit(function (e) {
        e.preventDefault();       

        if (arrayIdCheckActualizar.length > 0) {            

            var proceso_parametrizado = $('#procesos_parametrizados').val();
            var redireccionar = $('#redireccionar').val();
            var accion = $("#accion").val();
            var profesional = $('#profesional').val();
            
            let token = $('input[name=_token]').val();
                        
            var datos_actualizar = {
                'proceso_parametrizado': proceso_parametrizado,
                'redireccionar': redireccionar,
                'accion': accion,
                'profesional': profesional,
            }
            
            var datos_enviar ={
                '_token': token,
                array:arrayIdCheckActualizar,
                json:datos_actualizar
            }
            ////console.log(datos_enviar);      
            $.ajax({
                url:'/actualizarProfesionalServicioNotifi',          
                type:'POST',
                data: datos_enviar,
                success: function (response) 
                {
                    ////console.log(response);
                    if(response.parametro == 'actualizado_B_Notifi'){                        
                        $('.mostrar_mensaje_actualizo_bandeja').removeClass('d-none');
                        $('.mostrar_mensaje_actualizo_bandeja').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(function(){
                            $('.mostrar_mensaje_actualizo_bandeja').addClass('d-none');
                            $('.mostrar_mensaje_actualizo_bandeja').empty();
                            location.reload();
                        }, 6000);
                    }else{
                        $('.mostrar_mensaje_No_actualizo_bandeja').removeClass('d-none');
                        $('.mostrar_mensaje_No_actualizo_bandeja').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(function(){
                            $('.mostrar_mensaje_No_actualizo_bandeja').addClass('d-none');
                            $('.mostrar_mensaje_No_actualizo_bandeja').empty();                            
                        }, 6000);
                    }  
                }
            });
        }else{
            $('.mostrar_mensaje_No_actualizo_bandeja').removeClass('d-none');
            $('.mostrar_mensaje_No_actualizo_bandeja').append('<strong>Debe seleccionar un registro en la tabla y el Profesional o Redireccionar a, para Actualizar</strong>');
            setTimeout(function(){
                $('.mostrar_mensaje_No_actualizo_bandeja').addClass('d-none');
                $('.mostrar_mensaje_No_actualizo_bandeja').empty();
            }, 2000);
        }
    });
    */
    //llenado de selectores 
    let token = $('input[name=_token]').val();

    /** @var procesos Valores asociados al hipérvinculo del campo detalle para que este pueda ser invocado */
    let procesos = {
        'Calificación PCL': () => {
            return {
                'Nombre': 'PCL',
                'id': 'form_modulo_calificacion_pcl_',
                'url': $("#action_modulo_calificacion_pcl").val()
            }
        },
        'Juntas': () => {
            return {
                'Nombre': 'Juntas',
                'id': 'form_modulo_califi_Juntas_',
                'url': $("#action_modulo_calificacion_Juntas").val()
            }
        },
        'Origen': () => {
            return {
                'Nombre': 'Origen',
                'id': 'form_modulo_califi_Origen_',
                'url': $("#action_modulo_calificacion_Origen").val()
            }
        },
    }
    //captura de data sin Filtros        
    let datos_sin_filtro = {
        '_token': token,
        'BandejaNotifiTotal': "CargaBandejaNotifi",
        'newId_rol': $("#newId_rol").val(),
        'newId_user': $("#newId_user").val(),
    };

    sinfiltrosBandejaNotifi(datos_sin_filtro,procesos,token);

    filtro_bandejaNotifi(procesos);

    //Dimensionar o ajustar columnas de la tabla
   /* var dimensionartable = 0;
    $(".Notifibandeja").hover(function () {
        dimensionartable++;
        if (dimensionartable == 1) {
            $('.detallenotifi').click();
        }
    }); */


    //Ocultar boton del datatable
   /* setTimeout(() => {
        var botonFiltrar = $('#contenedorTable').parents();
        //console.log(botonFiltrar[1]);
        var contendorBotoFiltrar = botonFiltrar[1].childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[0].classList[0];
        ////console.log(contendorBotoFiltrar);
        $('.' + contendorBotoFiltrar).addClass('d-none');
    }, 2000); */

    /* $('#btn_expor_datos').click(function () {
        var infobtnExcel = $(this).parents();
        var selectorbtnExcel = infobtnExcel[3].children[0].childNodes[3].childNodes[1].childNodes[1].childNodes[0].childNodes[0].classList[0];
        ////console.log(selectorbtnExcel);
        $('.'+selectorbtnExcel).click();

    }); */

    $('#btn_expor_datos').click(function () {
        $('.dt-button').click();

    });

});

/**
 * Consulta sin filtro
 * @param {*} datos_sin_filtro Datos para consulta
 * @param {*} procesos Procesos asociados a la bandeja
 * @param {*} token 
 */
function sinfiltrosBandejaNotifi(datos_sin_filtro,procesos,token) {
    $.ajax({
        type: 'POST',
        url: '/sinfiltrosBandejaNotifi',
        data: datos_sin_filtro,
        beforeSend: function() {
            // Muestra el spinner antes de comenzar la solicitud
            $("#mensaje_importante").removeClass('d-none');
        },
        success: function (data) {
            $('#num_registros').empty();
            $('#num_registros').append(data.length);
            obtenerAlertasNaranja().then(alertasNaranja => {
                const start = performance.now();

                const alertasNaranjaMap = new Map(alertasNaranja.map(alerta => [
                    alerta.Id_Asignacion, 
                    {
                        naranja: alerta.F_accion_alerta_naranja,
                        roja: alerta.F_accion_alerta_roja
                    }
                ]));

                let cellCss = [];

                var enlaceModuloPrincipal = '';

                for (let i = 0; i < data.length; i++) {
                if (data[i]['Id_Asignacion'] != '') {
                    //Seteamos los datos y construimos el formulario para cada fila en funcion del proceso actual.
                    let action = procesos[data[i]['Nombre_proceso_actual']]().url;
                    let idInput = procesos[data[i]['Nombre_proceso_actual']]().id + data[i]["Id_Asignacion"];
                    let nombreInput = procesos[data[i]['Nombre_proceso_actual']]().Nombre;

                    enlaceModuloPrincipal = `<form action="${action}" method="POST">
                                            <input type="hidden" name="_token" value="${token}">
                                            <input class="btn btn-sm text-info"  id=${idInput} value="Modulo ${nombreInput}" type="submit" style="font-weight: bold; padding-left: inherit;">
                                            <input type="hidden" name="bd_notificacion" value="true">
                                            <input type="hidden" name="newIdAsignacion" value="${data[i]["Id_Asignacion"]}">
                                            <input type="hidden" name="newIdEvento" id="newIdEvento" value="${data[i]["ID_evento"]}">
                                            <input type="hidden" name="Id_Servicio" id="Id_Servicio" value="${data[i]["Id_Servicio"]}">
                                        </form>`;
                    data[i]['moduloNotifi'] = enlaceModuloPrincipal;

                    if (alertasNaranjaMap.has(data[i]['Id_Asignacion'])) {
                        let alertaFechas = alertasNaranjaMap.get(data[i]['Id_Asignacion']);
                        let currentTime = new Date();

                        // Verificar alerta naranja
                        if (alertaFechas.naranja) {
                            
                            let alertaFechaNaranja = new Date(alertaFechas.naranja);
                            // let diferenciaNaranja = Math.abs(currentTime - alertaFechaNaranja);
        
                            if (currentTime >= alertaFechaNaranja) {  
                                cellCss.push({
                                    color: 'orange',
                                    evento: data[i]["ID_evento"],
                                    id_aignacion: data[i]['Id_Asignacion']
                                });
                            }
                        }
        
                        // Verificar alerta roja
                        if (alertaFechas.roja) {
                            let alertaFechaRoja = new Date(alertaFechas.roja);
                            // let diferenciaRoja = Math.abs(currentTime - alertaFechaRoja);
                            if (currentTime >= alertaFechaRoja) { 
                                cellCss.push({
                                    color: 'red',
                                    evento: data[i]["ID_evento"],
                                    id_aignacion: data[i]['Id_Asignacion']
                                });
                            }
                        }
                    }
                } else {
                    data[i]['moduloNotifi'] = "";
                }
                }
                //const end = performance.now();
                //console.log(`Execution time Bucle: ${end - start} ms`);
                //const startDatatable = performance.now();
                //$.each(data, function (index, value) {
                    capturar_informacion_bandejaNotifi(data,cellCss)
                //});
                //const endDatatable = performance.now();
                //console.log(`Execution time datatable: ${endDatatable - startDatatable} ms`);
                //Oculta btn de exporta datatable
                $(".dt-buttons").addClass('d-none');
            }).catch(error => {
                console.error('Error al obtener alertas naranja:', error);
            });
        
        },
        complete: function() {
            $("#mensaje_importante").addClass('d-none');
            $("#form_proser_bandejaNotifi").removeClass('d-none');
        }

    });
}

function filtro_bandejaNotifi(procesos) {
    //Llenado del formulario para captura de data para dataTable
    $('#form_filtro_bandejaNotifi').submit(function (e) {
        e.preventDefault();
        var consultar_f_desde = $('#consultar_f_desde').val();
        var consultar_f_hasta = $('#consultar_f_hasta').val();
        var consultar_g_dias = $('#consultar_g_dias').val();
        let token = $('input[name=_token]').val();
        if (consultar_f_desde == "" && consultar_f_hasta == "" && consultar_g_dias == "") {
            $('.resultado_validacion').addClass('d-none');
            $('.resultado_validacion2').removeClass('d-none');
            $('#body_listado_casos_notifi').empty();
            $('#contenedorTable').addClass('d-none');
            $('#contenedor_selectores').addClass('d-none');
            $('#btn_expor_datos').addClass('d-none');
            $('#btn_guardar').addClass('d-none');
            $('#btn_bandeja').removeClass('d-none');
        }
        else {

            var datos_filtro = {
                '_token': token,
                'consultar_f_desde': consultar_f_desde,
                'consultar_f_hasta': consultar_f_hasta,
                'consultar_g_dias': consultar_g_dias,
                'newId_rol': $("#newId_rol").val(),
                'newId_user': $("#newId_user").val(),
            }

            $.ajax({
                type: 'POST',
                url: '/filtrosBandejaNotifi',
                data: datos_filtro,
                beforeSend: function() {
                    $("#form_proser_bandejaNotifi").removeClass('d-none');
                },
                success: function (data) {
                    ////console.log();
                    if (data.parametro == "sin_datos") {
                        // No se encuentra datos
                        $('.resultado_validacion2').addClass('d-none');
                        $('#llenar_mensaje_validacion').empty();
                        $('.resultado_validacion').removeClass('d-none');
                        $('.resultado_validacion').addClass('alert-danger');
                        $('#llenar_mensaje_validacion').append(data.mensajes);
                        $('#body_listado_casos_notifi').empty();
                        $('#contenedorTable').addClass('d-none');
                        $('#contenedor_selectores').addClass('d-none');
                        $('#btn_expor_datos').addClass('d-none');
                        $('#btn_guardar').addClass('d-none');
                        $('#btn_bandeja').removeClass('d-none');
                    } else {
                        $('.resultado_validacion2').addClass('d-none');
                        $('#num_registros2').addClass('d-none');
                        $('.resultado_validacion').addClass('d-none');
                        $('#num_registroslabel').removeClass('d-none');
                        $('#num_registros').empty();
                        $('#num_registros').append(data.length);
                        $('#contenedorTable').removeClass('d-none');
                        $('#contenedor_selectores').removeClass('d-none');
                        $('#btn_expor_datos').removeClass('d-none');
                        $('#btn_guardar').removeClass('d-none');
                        $('#btn_bandeja').removeClass('d-none');

                        var enlaceModuloPrincipal = '';
                        let cellCss = [];
                        obtenerAlertasNaranja().then(alertasNaranja => {
                            const alertasNaranjaMap = new Map(alertasNaranja.map(alerta => [
                                alerta.Id_Asignacion, 
                                {
                                    naranja: alerta.F_accion_alerta_naranja,
                                    roja: alerta.F_accion_alerta_roja
                                }
                            ]));

                        for (let i = 0; i < data.length; i++) {

                            //Seteamos los datos y construimos el formulario para cada fila en funcion del proceso actual.
                            let action = procesos[data[i]['Nombre_proceso_actual']]().url;
                            let idInput = procesos[data[i]['Nombre_proceso_actual']]().id + data[i]["Id_Asignacion"];
                            let nombreInput = procesos[data[i]['Nombre_proceso_actual']]().Nombre;

                            if (data[i]['Id_Asignacion'] != '') {
                                enlaceModuloPrincipal = `<form action="${action}" method="POST">
                                                        <input type="hidden" name="_token" value="${token}">
                                                        <input class="btn btn-sm text-info"  id=${idInput} value="Modulo ${nombreInput}" type="submit" style="font-weight: bold; padding-left: inherit;">
                                                        <input type="hidden" name="bd_notificacion" value="true">
                                                        <input type="hidden" name="newIdAsignacion" value="${data[i]["Id_Asignacion"]}">
                                                        <input type="hidden" name="newIdEvento" id="newIdEvento" value="${data[i]["ID_evento"]}">
                                                        <input type="hidden" name="Id_Servicio" id="Id_Servicio" value="${data[i]["Id_Servicio"]}">
                                                    </form>`;
                                data[i]['moduloNotifi'] = enlaceModuloPrincipal;

                                //Manejo de alertas
                                if (alertasNaranjaMap.has(data[i]['Id_Asignacion'])) {
                                    let alertaFechas = alertasNaranjaMap.get(data[i]['Id_Asignacion']);
                                    let currentTime = new Date();
                    
                                    // Verificar alerta naranja
                                    if (alertaFechas.naranja) {
                                        let alertaFechaNaranja = new Date(alertaFechas.naranja);
                                        // let diferenciaNaranja = Math.abs(currentTime - alertaFechaNaranja);
                    
                                        if (currentTime >= alertaFechaNaranja) {  
                                            cellCss.push({
                                                color: 'orange',
                                                evento: data[i]["ID_evento"],
                                                id_aignacion: data[i]['Id_Asignacion']
                                            });
                                        }
                                    }
                    
                                    // Verificar alerta roja
                                    if (alertaFechas.roja) {
                                        let alertaFechaRoja = new Date(alertaFechas.roja);
                                        // let diferenciaRoja = Math.abs(currentTime - alertaFechaRoja);
                    
                                        if (currentTime >= alertaFechaRoja) { 
                                            cellCss.push({
                                                color: 'red',
                                                evento: data[i]["ID_evento"],
                                                id_aignacion: data[i]['Id_Asignacion']
                                            });
                                        }
                                    }
                                }
                            }else {
                                data[i]['moduloNotifi'] = "";
                            }
                        }

                        capturar_informacion_bandejaNotifi(data,cellCss);

                        //Oculta btn de exporta datatable
                        $(".dt-buttons").addClass('d-none');
                    }).catch(error => {
                        console.error('Error al obtener alertas naranja:', error);
                    });
                    }
                },
            });

        }
        setTimeout(() => {
            //Oculta btn de exporta datatable
            $(".dt-buttons").addClass('d-none');
        }, 3000);

    });
}

//Datatable Bandeja Notifi
$('#Bandeja_Notifi thead tr').clone(true).addClass('filters').appendTo('#Bandeja_Notifi thead');
function capturar_informacion_bandejaNotifi(response,estilos) {
      let Bandeja_Notifi =   $('#Bandeja_Notifi').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            scrollY: 350,
            scrollX: true,
            autoWidth: false,
            initComplete: function () {
                var api = this.api();
                // For each column
                api.columns().eq(0).each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell_1 = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );

                    if (cell_1[0].cellIndex != 28) {

                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );

                        var title = $(cell).text().trim();

                        if(title == 'Detalle'){
                            $(cell).text('');
                        }

                        if(title != '' && title != 'Detalle') {
                            $(cell).html(`<input type="text" placeholder="${title}" />`);
                            $('input', $('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
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

                    }

                });
            },
            dom: 'Bfrtip',
            buttons: {
                dom: {
                    buttons: {
                        className: 'btn'
                    }
                },
                buttons: [
                    {
                        extend: "excel",
                        title: 'Bandeja Notificaciones',
                        text: 'Exportar datos',
                        className: 'btn btn-success',
                        "excelStyles": [                      // estilos de excel

                        ],
                        //Limitar columnas para el reporte
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27]
                        }
                    }
                ]
            },
            "destroy": true,
            "data": response,
            "pageLength": 20,
            "order": [[5, 'desc']],
            "columns": [
                {
                    data: null,
                    render: function (data) {
                        return data.moduloNotifi;
                    }
                },
                { "data": "Nombre_Cliente" },
                { "data": "Nombre_afiliado" },
                { "data": "Nro_identificacion" },
                { "data": "Nombre_servicio" },
                { "data": "Nombre_estado" },
                { "data": "Accion" },
                { "data": "Nombre_profesional" },
                { "data": "Nombre_evento" },
                { "data": "ID_evento" },
                { "data": "F_evento" },
                { "data": null, render: function(data) {                    
                    return data.Nueva_F_radicacion != null ? data.Nueva_F_radicacion : data.F_radicacion;
                    }
                },
                { "data": "Tiempo_de_gestion" },
                { "data": "Dias_transcurridos_desde_el_evento" },
                { "data": "Empresa" },
                { "data": "Nombre_proceso_actual" },
                { "data": "Nombre_proceso_anterior" },
                {
                "data": null, render: function (data) {
 
                        let f_asignacion_p = (() => {
                            switch (data.Id_proceso) {
                                case 1:
                                    return data.fecha_asignacion_dto == null ? "Sin Fecha de Asignación para DTO" : data.fecha_asignacion_dto  //Origen
                                    break;
                                case 2:
                                    return data.Fecha_asignacion_calif == null ? "Sin Fecha de Asignación para Calificación" : data.Fecha_asignacion_calif//PCL
                                    return
                                    break;
                                case 3:
                                    return data.Fecha_asignacion_al_proceso == null ? "Sin Fecha de Asignación para el proceso" : data.Fecha_asignacion_al_proceso //Juntas
                                    break;
                            }
                        })();
                        //console.log(f_asignacion_p,fecha_asignacion_dto,Fecha_asignacion_calif,);
                        return f_asignacion_p;
                    }
                },
                { "data": "Asignado_por" },
                { "data": "F_alerta" },
                { "data": "Fecha_alerta" },
                { "data": "F_asigna_notifi" },
                { "data": "N_de_orden" },
                { "data": "N_radicado_notifi" },
                { "data": "Asunto_notifi" },
                { "data": "F_envio_notifi" },
                { "data": "Estado_general_notifi" },
                { "data": "F_accion" },
            ],
            drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
               
                // Aplicamoa los estilos a las celdas según el ID_evento
                $(rows).each(function (rowIdx, row) {
                    let cells = $(row).find('td');
                    cells.each(function (colIdx, cell) {
                        var cellData = api.cell(cell).data();

                        estilos.forEach(function (estilo) {
                            if (cellData && cellData.ID_evento === estilo.evento && cellData.Id_Asignacion === estilo.id_aignacion) {
                                $(row).find('td').css('color',estilo.color);
                            }
                        });
                    });
                });
            },
            "language": {
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


    autoAdjustColumns(Bandeja_Notifi);
}

// Función para obtener las alertas naranjas

function obtenerAlertasNaranja() {
    var token = $('meta[name="csrf-token"]').attr('content');
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/alertasNaranjasRojasNotif',
            method: 'POST',
            data: {
                _token: token
            },
            success: function(response) {
                resolve(response.data); 
            },
            // error: function(xhr, status, error) {
            //     console.error('Error:', status, error);
            //     reject({ status, error });
            // }
        });
    });
}