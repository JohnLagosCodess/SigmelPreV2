$(document).ready(function () {

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

    // Inicialización select2 listado profesional bandeja de Juntas
    $(".profesional").select2({
        width: '100%',
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    //llenado de selectores 
    let token = $('input[name=_token]').val();

    // listado de procesos que almenos tiene configurado una paramétrica
    let datos_lista_procesos_parametrizados = {
        '_token':token,
        'parametro':"listado_procesos_parametrizados"
    }

    $.ajax({
        type:'POST',
        url:'/selectoresBandejaJuntas',
        data: datos_lista_procesos_parametrizados,
        success:function (data) {
            // console.log(data);
            $('#procesos_parametrizados').append('<option value="" selected>Seleccione</option>');
            let procesos_parametrizados = Object.keys(data);
            for (let i = 0; i < procesos_parametrizados.length; i++) {
                $('#procesos_parametrizados').append('<option value="'+data[procesos_parametrizados[i]]['Id_proceso']+'">'+data[procesos_parametrizados[i]]['Nombre_proceso']+'</option>')
            }
        }
    });

    //Listado de servicios de bandeja Juntas
    $('#procesos_parametrizados').change(function(){
        let datos_lista_servicio = {
            '_token': token,
            'parametro':"lista_servicios_juntas",
            'id_proceso': $(this).val()
        };
        $.ajax({
            type:'POST',
            url:'/selectoresBandejaJuntas',
            data: datos_lista_servicio,
            success:function(data){
                $('#accion').empty();
                $('#profesional').empty();
                $(".columna_selector_profesional").slideUp('slow');
                $(".columna_decripcion_bandeja").slideUp('slow');
                $('#redireccionar').empty();
                $('#redireccionar').append('<option value="" selected>Seleccione</option>');
                let serviciojuntas = Object.keys(data);
                for (let i =0; i < serviciojuntas.length; i++ ){
                    $('#redireccionar').append('<option value="'+data[serviciojuntas[i]]['Id_Servicio']+'">'+data[serviciojuntas[i]]['Nombre_servicio']+'</option>');
                }
            }
        });
    });

    $(".columna_selector_profesional").slideUp('slow');
    $(".columna_decripcion_bandeja").slideUp('slow');
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
            url:'/selectoresBandejaJuntas',
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

    // CARGUE LISTADO DE PROFESIONALES DEPENDIENDO DE LA SELECCIÓN DE LA ACCIÓN
    $('#accion').change(function(){
        // LISTADO PROFESIONAL
        $('#profesional').prop('disabled', false);
        let datos_lista_profesional={
            '_token':token,
            'parametro':"lista_profesional_juntas",
            // 'Id_cliente': $("#cliente").val(),
            'Id_proceso': $("#procesos_parametrizados").val(),
            'Id_servicio': $("#redireccionar").val(),
            'Id_accion': $(this).val(),
        }
        
        $.ajax({
            type:'POST',
            url:'/selectoresBandejaJuntas',
            data: datos_lista_profesional,
            success:function (data) {
                //console.log(data)
                $('#profesional').empty();
                $('#profesional').append('<option value="" selected>Seleccione</option>');
                let profesionales = Object.keys(data.info_listado_profesionales);
                for (let i = 0; i < profesionales.length; i++) {
                    if (data.info_listado_profesionales[profesionales[i]]['id'] == data.Profesional_asignado) {
                        $('#profesional').append('<option value="'+data.info_listado_profesionales[profesionales[i]]['id']+'" selected>'+data.info_listado_profesionales[profesionales[i]]['name']+'</option>')
                    } else {
                        $('#profesional').append('<option value="'+data.info_listado_profesionales[profesionales[i]]['id']+'">'+data.info_listado_profesionales[profesionales[i]]['name']+'</option>')
                    }
                }
            }
        });
    });

    /* VALIDACIÓN PARA DETERMINAR QUE LA PARAMÉTRICA QUE SE CONFIGURE PARA EL MÓDULO NUEVO ESTE EN UN VALOR DE SI EN LA TABLA sigmel_informacion_parametrizaciones_clientes */
    var validar_bandeja_trabajo = setInterval(() => {
        if($("#procesos_parametrizados").val() != '' && $("#redireccionar").val() != '' && $("#accion").val() != ''){
            let datos_ejecutar_parametrica_bandeja_trabajo = {
                '_token': token,
                'parametro': "validarSiBandejaTrabajo",
                'Id_proceso': $("#procesos_parametrizados").val(),
                'Id_servicio': $("#redireccionar").val(),
                'Id_accion': $("#accion").val(),
            };
            // console.log(datos_ejecutar_parametrica_bandeja_trabajo);
            $.ajax({
                type:'POST',
                url:'/validacionParametricaEnSi',
                data: datos_ejecutar_parametrica_bandeja_trabajo,
                success:function(data) {
                    // console.log(data);
                    if(data.length > 0){
                        if (data[0]["Bandeja_trabajo"] !== "Si") {
                            $(".no_ejecutar_parametrica_bandeja_trabajo").removeClass('d-none');
                            $("#btn_guardar").addClass('d-none');
                            $(".columna_selector_profesional").slideUp('slow');
                            $(".columna_decripcion_bandeja").slideUp('slow');
                        } else {
                            $(".no_ejecutar_parametrica_bandeja_trabajo").addClass('d-none');
                            $("#btn_guardar").removeClass('d-none');
                            clearInterval(validar_bandeja_trabajo);

                            // Listado de seleccion profecional bandeja Juntas
                            $(".columna_selector_profesional").slideDown('slow');
                            $(".columna_decripcion_bandeja").slideDown('slow');
                            // let datos_lista_profesional={
                            //     '_token':token,
                            //     'parametro':"lista_profesional_juntas"
                            // }
                        
                            // $.ajax({
                            //     type:'POST',
                            //     url:'/selectoresBandejaJuntas',
                            //     data: datos_lista_profesional,
                            //     success:function (data) {
                            //         $('#profesional').empty();
                            //         $('#profesional').append('<option value="" selected>Seleccione</option>');
                            //         let profecionaljuntas = Object.keys(data);
                            //         for (let i = 0; i < profecionaljuntas.length; i++) {
                            //             $('#profesional').append('<option value="'+data[profecionaljuntas[i]]['id']+'">'+data[profecionaljuntas[i]]['name']+'</option>')
                            //         }
                                    
                            //     }
                            // });
                        }
                    }
                }
            });
        }
    }, 500);

    /// cargue de datos sin filtros
    $("#mensaje_importante").removeClass('d-none');
    $('#Bandeja_Juntas thead tr').clone(true).addClass('filters').appendTo('#Bandeja_Juntas thead');
    var lista_eventos_juntas = $('#Bandeja_Juntas').DataTable({            
        orderCellsTop: true,
        fixedHeader: true,
        scrollY: 350,
        scrollX: true,
        initComplete: function () {
            var api = this.api();
                // For each column
            api.columns().eq(0).each(function (colIdx) {
                // Set the header cell to contain the input element
                var cell_1 = $('.filters th').eq(
                    $(api.column(colIdx).header()).index()
                );
                
                // console.log(cell_1[0].cellIndex);

                if(cell_1[0].cellIndex != 35){

                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    
                    var title = $(cell).text();
                    
                    if (title === 'Detalle  ') {
                        $(cell).append('<input type="checkbox" class="principal" id="toggleButton" />');                            

                         // Selecciona los elementos repetidos (en este caso, elementos con la clase "repetido")
                        var elementosRepetidos = $(".principal");

                        // Verifica que haya más de un elemento repetido antes de eliminarlos
                        if (elementosRepetidos.length > 1) {
                            // Selecciona los dos primeros elementos repetidos utilizando "slice(0, 2)"
                            var elementosEliminar = elementosRepetidos.slice(0, 1);

                            // Elimina los elementos seleccionados del DOM
                            elementosEliminar.remove();
                        }                            
                        
                    }else{
                        $(cell).html('<input type="text" placeholder="' + title + '" />');
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
                    
                }

            });
        },
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
                    title: 'Bandeja Juntas',
                    text:'Exportar datos',
                    className: 'btn btn-success',
                    "excelStyles": [                      // estilos de excel
                                                
                    ],
                    //Limitar columnas para el reporte
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
                    }  
                }
            ]
        }, 
        "destroy": true,
        "pageLength": 20,
        // "order": [[5, 'desc']],            
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

    cargarDatosBandejaJuntas(lista_eventos_juntas, "sin_filtros", null, null, null);

    //Captura id Checkbox para extraer su value
    var arrayIdCheckActualizar = [];
    $(document).on('change', "input[id^='actualizar_id_asignacion_']", function(){
        var IdCheckActualizar = $(this).val();
        if ($(this).is(':checked')) {
            arrayIdCheckActualizar.push(IdCheckActualizar);
            //console.log('array lleno');
            //console.log(arrayIdCheckActualizar);
        }else{
            eliminarElemento(IdCheckActualizar);            
        }       
        
    });

    //Llenado del formulario para captura de data para dataTable
    $('#form_filtro_bandejaJuntas').submit(function (e) {
        e.preventDefault();
        var consultar_f_desde = $('#consultar_f_desde').val();
        var consultar_f_hasta = $('#consultar_f_hasta').val();
        var consultar_g_dias = $('#consultar_g_dias').val();
        
        cargarDatosBandejaJuntas(lista_eventos_juntas, "con_filtros", consultar_f_desde, consultar_f_hasta, consultar_g_dias);

    })

    //Dimensionar o ajustar columnas de la tabla
    var dimensionartable = 0;    
    $(".Juntasbandeja").hover(function(){
        dimensionartable++;
        if (dimensionartable == 1) {
            $('.detallejuntas').click();		
        }
    }); 

    //Datatable Bandeja Juntas
    /* $('#Bandeja_Juntas thead tr').clone(true).addClass('filters').appendTo('#Bandeja_Juntas thead');
    function capturar_informacion_bandejaJuntas(response, index, value) {        
        $('#Bandeja_Juntas').DataTable({            
            orderCellsTop: true,
            fixedHeader: true,
            scrollY: 350,
            scrollX: true,
            initComplete: function () {
                var api = this.api();
                    // For each column
                api.columns().eq(0).each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell_1 = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    
                    // console.log(cell_1[0].cellIndex);

                    if(cell_1[0].cellIndex != 35){

                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        
                        var title = $(cell).text();
                        
                        if (title === 'Detalle  ') {
                            $(cell).append('<input type="checkbox" class="principal" id="toggleButton" />');                            

                             // Selecciona los elementos repetidos (en este caso, elementos con la clase "repetido")
                            var elementosRepetidos = $(".principal");

                            // Verifica que haya más de un elemento repetido antes de eliminarlos
                            if (elementosRepetidos.length > 1) {
                                // Selecciona los dos primeros elementos repetidos utilizando "slice(0, 2)"
                                var elementosEliminar = elementosRepetidos.slice(0, 1);

                                // Elimina los elementos seleccionados del DOM
                                elementosEliminar.remove();
                            }                            
                            
                        }else{
                            $(cell).html('<input type="text" placeholder="' + title + '" />');
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
                        
                    }

                });
            },
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
                        title: 'Bandeja Juntas',
                        text:'Exportar datos',
                        className: 'btn btn-success',
                        "excelStyles": [                      // estilos de excel
                                                    
                        ],
                        //Limitar columnas para el reporte
                        exportOptions: {
                            columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
                        }  
                    }
                ]
            }, 
            "destroy": true,
            "data": response,
            "pageLength": 20,
            "order": [[5, 'desc']],            
            "columns":[
                {
                    data: null,
                    render: function (data, type, row) {
                        return data.actualizarproser+data.moduloJuntas;
                    }
                },
                {"data":"Nombre_Cliente"},
                {"data":"Nombre_afiliado"},
                {"data":"Nro_identificacion"},
                {"data":"Nombre_servicio"},
                {"data":"Nombre_estado"},
                {"data":"Accion"},
                {"data":"Nombre_profesional"},
                {"data":"Nombre_evento"},
                {"data":"ID_evento"},
                {"data":"F_evento"},
                {"data":"F_radicacion"},
                {"data":"Tiempo_de_gestion"},
                {"data":"Dias_transcurridos_desde_el_evento"},
                {"data":"Empresa"},
                {"data":"Nombre_proceso_actual"},
                {"data":"Nombre_proceso_anterior"},
                {"data":"Fecha_asignacion_al_proceso"},
                {"data":"Asignado_por"},
                {"data":"F_alerta"},
                {"data":"Fecha_alerta"},
                {"data":"Enfermedad_heredada"},
                {"data":"Parte_controvierte_primera_califi"},
                {"data":"Tipo_controversia_primera_califi"},
                {"data":"Termino_controversia_primera_califi"},
                {"data":"Junta_regional_califi_invalidez"},
                {"data":"N_dictamen_Jrci"},
                {"data":"F_radicado_entrada_dictamen_Jrci"},
                {"data":"F_asignacion_pronuncia_junta"},
                {"data":"Decision_dictamen_jrci"},
                {"data":"N_acta_ejecutoria_emitida_Jrci"},
                {"data":"N_radicado_de_recurso_Jrci"},
                {"data":"Termino_controversia_propia_Jrci"},
				{"data":"Parte_controversia_ante_Jrci"},
                {"data":"Tipo_controversia_por_otra_Jrci"},
                {"data":"F_accion"},
                
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
    } */

    //Seteo de todos los checkbox
    $(document).on('change', "#toggleButton", function () {         
        var isChecked = $(this).is(":checked");

        if (isChecked) {
            // Establecer estado de todos los checkboxes
            $("input[id^='actualizar_id_asignacion_']").prop("checked", isChecked);              
    
            setTimeout(() => {            
                $('#Bandeja_Juntas input[type="checkbox"]').each(function() {
                    arrayIdCheckActualizar.push($(this).val());
                });
                arrayIdCheckActualizar.splice(0,1);
                //console.log('seleccion grupal')
                //console.log(arrayIdCheckActualizar);
            }, 2000);            
        }else{            
            setTimeout(() => {
                $('#Bandeja_Juntas input[type="checkbox"]').each(function() {
                    eliminarElemento($(this).val());           
                });
            }, 2000);
           $("input[id^='actualizar_id_asignacion_']").prop("checked", false);              
        }
    }) 

    //Ocultar boton del datatable
    // setTimeout(() => {
    //     var botonFiltrar = $('#contenedorTable').parents();
    //     var contendorBotoFiltrar = botonFiltrar[1].childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[0].classList[0];
    //     //console.log(contendorBotoFiltrar);
    //     $('.'+contendorBotoFiltrar).addClass('d-none');
    // }, 2000);

    $('#btn_expor_datos').click(function () {
        // var infobtnExcel = $(this).parents();
        // var selectorbtnExcel = infobtnExcel[3].children[0].childNodes[3].childNodes[1].childNodes[1].childNodes[0].childNodes[0].classList[0];
        // //console.log(selectorbtnExcel);
        // $('.'+selectorbtnExcel).click();
        $('.dt-button').click();
    });

    //Asignar ruta del formulario de modulo calificacion Juntas
    $(document).on('mouseover',"input[id^='modulo_califi_Juntas_']", function(){
        let url_editar_evento = $('#action_modulo_calificacion_Juntas').val();
        $("form[id^='form_modulo_calificacion_Juntas_']").attr("action", url_editar_evento);    
    });

    // Función para eliminar el elemento del array al desmarcar checkbox
    function eliminarElemento(elemento) {
        var index = arrayIdCheckActualizar.indexOf(elemento);

        if (index > -1) {
            arrayIdCheckActualizar.splice(index, 1);
            //console.log("Elemento eliminado: ", elemento);
            //console.log(arrayIdCheckActualizar);
        } else {
            //console.log("Elemento no encontrado en el array: ", elemento);
            //console.log(arrayIdCheckActualizar);
        }
    }

    $('#form_proser_bandejaJuntas').submit(function (e) {
        e.preventDefault();       

        if (arrayIdCheckActualizar.length > 0) {            

            var proceso_parametrizado = $('#procesos_parametrizados').val();
            var redireccionar = $('#redireccionar').val();
            var accion = $("#accion").val();
            var profesional = $('#profesional').val();
            var descripcion_bandeja = $('#descripcion_bandeja').val();

            let token = $('input[name=_token]').val();
                        
            var datos_actualizar = {
                'proceso_parametrizado': proceso_parametrizado,
                'redireccionar': redireccionar,
                'accion': accion,
                'profesional': profesional,
                'descripcion_bandeja':descripcion_bandeja,
            }
            
            var datos_enviar ={
                '_token': token,
                array:arrayIdCheckActualizar,
                json:datos_actualizar
            }
            //console.log(datos_enviar);      
            $.ajax({
                url:'/actualizarProfesionalServicioJuntas',          
                type:'POST',
                data: datos_enviar,
                success: function (response) 
                {
                    //console.log(response);
                    if(response.parametro == 'actualizado_B_Juntas'){                        
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
            }, 6000);
        }
    });

});

// Función para obtener las alertas naranjas

function obtenerAlertasNaranjaRojas() {
    var token = $('meta[name="csrf-token"]').attr('content');
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/alertasNaranjasRojasJuntas',
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

// Función principal para renderizar registros

function renderizarRegistros(data, inicio, fin, bandejaJuntasTable) {
    obtenerAlertasNaranjaRojas().then(alertasNaranja => {
        const alertasNaranjaRojaMap = new Map(alertasNaranja.map(alerta => [
            alerta.Id_Asignacion, 
            {
                naranja: alerta.F_accion_alerta_naranja,
                roja: alerta.F_accion_alerta_roja
            }
        ]));

        var f_radicacion = '';

        for (let a = inicio; a < fin; a++) {
            if (data[a].Nueva_F_radicacion === null) {
                f_radicacion = data[a].F_radicacion;
            } else {
                f_radicacion = data[a].Nueva_F_radicacion;
            }

            var datos = [
                data[a].actualizarproser+data[a].moduloJuntas,
                data[a].Nombre_Cliente,
                data[a].Nombre_afiliado,
                data[a].Nro_identificacion,
                data[a].Nombre_servicio,
                data[a].Nombre_estado,
                data[a].Accion,
                data[a].Nombre_profesional,
                data[a].Nombre_evento,
                data[a].ID_evento,
                data[a].F_evento,
                f_radicacion,
                data[a].F_vencimiento,
                data[a].F_alerta,
                data[a].Tiempo_de_gestion,
                data[a].Dias_transcurridos_desde_el_evento,
                data[a].Empresa,
                data[a].Nombre_proceso_actual,
                data[a].Nombre_proceso_anterior,
                data[a].Fecha_asignacion_al_proceso,
                data[a].Asignado_por,
                data[a].Fecha_alerta,
                data[a].Enfermedad_heredada,
                data[a].Parte_controvierte_primera_califi,
                data[a].Tipo_controversia_primera_califi,
                data[a].Termino_controversia_primera_califi,
                data[a].Junta_regional_califi_invalidez,
                data[a].N_dictamen_Jrci,
                data[a].F_radicado_entrada_dictamen_Jrci,
                data[a].F_asignacion_pronuncia_junta,
                data[a].Decision_dictamen_jrci,
                data[a].N_acta_ejecutoria_emitida_Jrci,
                data[a].N_radicado_de_recurso_Jrci,
                data[a].Termino_controversia_propia_Jrci,
                data[a].Parte_controversia_ante_Jrci,
                data[a].Tipo_controversia_por_otra_Jrci,
                data[a].F_accion,
            ];

            let rowNode = bandejaJuntasTable.row.add(datos).draw(false).node();

            /* 
                Con el id de asignacion consultamos si ya se ha ejecutado un ANS, ya que este tendrá la prioridad
                de coloreo naranja o rojo.
            */

            var datos_ejecucion_ans ={
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'id_asignacion': data[a].Id_Asignacion
            }

            $.ajax({
                url:'/consultaANSejecutadoJuntas',          
                type:'POST',
                data: datos_ejecucion_ans,
                success: function (response) 
                {
                    // si tiene un ANS ejecutado es porque ya trae info de la bd y el coloreado es en base a la fechas de alerta del ans
                    // En caso de que no, el coloreado es en base a la fechas de alerta de la paramétrica.
                    if (response.length == 1) {

                        let currentTime_ans = new Date();
                        // console.log("TIEMPO ACTUAL: "+currentTime_ans);
                        
                        ColoreadoEventosANS(currentTime_ans, response[0].Fecha_alerta_naranja, response[0].Fecha_alerta_roja, rowNode);
                    }
                    else{
                        if (alertasNaranjaRojaMap.has(data[a].Id_Asignacion)) {
                            let alertaFechas = alertasNaranjaRojaMap.get(data[a].Id_Asignacion);
                            let currentTime = new Date();
            
                            // Verificar alerta naranja
                            if (alertaFechas.naranja) {
                                let alertaFechaNaranja = new Date(alertaFechas.naranja);
                                // let diferenciaNaranja = Math.abs(currentTime - alertaFechaNaranja);
            
                                if (currentTime >= alertaFechaNaranja) {  
                                    $(rowNode).find('td').css('color', 'orange');
                                }
                            }
            
                            // Verificar alerta roja
                            if (alertaFechas.roja) {
                                let alertaFechaRoja = new Date(alertaFechas.roja);
                                // let diferenciaRoja = Math.abs(currentTime - alertaFechaRoja);
            
                                if (currentTime >= alertaFechaRoja) { 
                                    $(rowNode).find('td').css('color', 'red');
                                }
                            }
                        }
                    }
                }
            });

            datos = [];
        }
    }).catch(error => {
        console.error('Error al obtener alertas naranja:', error);
    });
}

// function renderizarRegistros(data, inicio, fin, bandejaJuntasTable) {

//     var f_radicacion = '';
//     for (let a = inicio; a < fin; a++) {

//         if (data[a].Nueva_F_radicacion === null) {
//             f_radicacion = data[a].F_radicacion;
//         } else {
//             f_radicacion = data[a].Nueva_F_radicacion;
//         };

//         var datos = [
//             data[a].actualizarproser+data[a].moduloJuntas,
//             data[a].Nombre_Cliente,
//             data[a].Nombre_afiliado,
//             data[a].Nro_identificacion,
//             data[a].Nombre_servicio,
//             data[a].Nombre_estado,
//             data[a].Accion,
//             data[a].Nombre_profesional,
//             data[a].Nombre_evento,
//             data[a].ID_evento,
//             data[a].F_evento,
//             f_radicacion,
//             data[a].Tiempo_de_gestion,
//             data[a].Dias_transcurridos_desde_el_evento,
//             data[a].Empresa,
//             data[a].Nombre_proceso_actual,
//             data[a].Nombre_proceso_anterior,
//             data[a].Fecha_asignacion_al_proceso,
//             data[a].Asignado_por,
//             data[a].F_alerta,
//             data[a].Fecha_alerta,
//             data[a].Enfermedad_heredada,
//             data[a].Parte_controvierte_primera_califi,
//             data[a].Tipo_controversia_primera_califi,
//             data[a].Termino_controversia_primera_califi,
//             data[a].Junta_regional_califi_invalidez,
//             data[a].N_dictamen_Jrci,
//             data[a].F_radicado_entrada_dictamen_Jrci,
//             data[a].F_asignacion_pronuncia_junta,
//             data[a].Decision_dictamen_jrci,
//             data[a].N_acta_ejecutoria_emitida_Jrci,
//             data[a].N_radicado_de_recurso_Jrci,
//             data[a].Termino_controversia_propia_Jrci,
//             data[a].Parte_controversia_ante_Jrci,
//             data[a].Tipo_controversia_por_otra_Jrci,
//             data[a].F_accion,
//         ];
        
//         bandejaJuntasTable.row.add(datos).draw(false).node();
//         datos = [];
//     }
// }

function cargarDatosBandejaJuntas(bandejaJuntasTable, tipo_consulta, consultar_f_desde, consultar_f_hasta, consultar_g_dias){
    var token = $('input[name=_token]').val();
    $(".dt-buttons").addClass('d-none');

    if (tipo_consulta == "sin_filtros") {
        //captura de data sin Filtros        
        let datos_sin_filtro = {            
            '_token': token,
            'BandejaJuntasTotal': "CargaBandejaJuntas",
            'newId_rol': $("#newId_rol").val(),
            'newId_user': $("#newId_user").val(),
        };

        $.ajax({
            type:'POST',
            url:'/sinfiltrosBandejaJuntas',
            data: datos_sin_filtro,
            success:function(data) {

                $("#iniciando_bandeja").addClass('d-none');
                $("#bandeja_iniciada").removeClass('d-none');
                $('#alerta_num_registros').empty();
                $('#alerta_num_registros').append(data.length);

                $('#num_registros').empty();
                $('#num_registros').append(data.length);

                var actualizarBandeja = '';
                var modulocalificacionJuntas = '';

                for (let i = 0; i < data.length; i++) {

                    if (data[i]['Id_Asignacion'] != '') {
                        actualizarBandeja='<input class="checkbox-class" name="actualizaBandejaJuntas" id="actualizar_id_asignacion_'+data[i]['Id_Asignacion']+'" type="checkbox" value="'+data[i]["Id_Asignacion"]+'">';
                        data[i]['actualizarproser'] = actualizarBandeja;
                    }else{
                        data[i]['actualizarproser'] = ""; 
                    }               
                    
                    if (data[i]['Id_Asignacion'] != ''){

                        modulocalificacionJuntas = '<form id="form_modulo_calificacion_Juntas_'+data[i]["Id_Asignacion"]+'" action="" method="POST">'+
                                    '<input type="hidden" name="_token" value="'+token+'">'+
                                    '<input class="btn btn-sm text-info" id="modulo_califi_Juntas_'+data[i]["Id_Asignacion"]+'" value="Modulo Juntas" type="submit" style="font-weight: bold; padding-left: inherit;">'+ 
                                    '<input type="hidden" name="newIdAsignacion" value="'+data[i]["Id_Asignacion"]+'">'+
                                    '<input type="hidden" name="newIdEvento" id="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                    '<input type="hidden" name="Id_Servicio" id="Id_Servicio" value="'+data[i]["Id_Servicio"]+'">'+
                                    '</form>';
                        data[i]['moduloJuntas'] = modulocalificacionJuntas;
                        
                    }else{
                        data[i]['moduloJuntas'] = ""; 
                    } 
                }

                var inicio = 0;
                var fin = Math.min(100, data.length);
                function renderizarSiguienteBloque() {
                    if (inicio < data.length) {
                        renderizarRegistros(data, inicio, fin, bandejaJuntasTable);
                        inicio = fin;
                        fin += Math.min(fin + 100, data.length) - fin;
                        
                        if (inicio >= data.length) {
                            $('#num_registroslabel').removeClass('d-none');
                            $('#contenedorTable').removeClass('d-none');
                            $('#mensaje_importante').addClass('d-none');
                            $("#bandeja_iniciada").addClass('d-none');
                        } else {
                            setTimeout(renderizarSiguienteBloque, 2000); // Pausa de 2 segundos
                        }
                        
                    }
                }
    
                renderizarSiguienteBloque();
                
            }
            
        });
    } else {
        
        if(consultar_f_desde == "" && consultar_f_hasta == "" && consultar_g_dias == ""){                        
            $('.resultado_validacion').addClass('d-none');
            $('.resultado_validacion2').removeClass('d-none');
            $('#body_listado_casos_juntas').empty();
            $('#contenedorTable').addClass('d-none');
            $('#contenedor_selectores').addClass('d-none');
            $('#num_registros2').removeClass('d-none');            
            $('#num_registroslabel').addClass('d-none');
            //$('#body_listado_casos_juntas').empty();
            $('#btn_expor_datos').addClass('d-none');
            $('#btn_guardar').addClass('d-none');
            $('#btn_bandeja').removeClass('d-none');
        }
        else{
            
            var datos_filtro = {
                '_token': $('input[name=_token]').val(),
                'consultar_f_desde': consultar_f_desde,
                'consultar_f_hasta': consultar_f_hasta,
                'consultar_g_dias': consultar_g_dias,
                'newId_rol': $("#newId_rol").val(),
                'newId_user': $("#newId_user").val(),
            }

            $.ajax({
                type:'POST',
                url:'/filtrosBandejaJuntas',
                data: datos_filtro,
                success:function(data){
                    //console.log();
                    if(data.parametro == "sin_datos"){
                        // No se encuentra datos
                        $('.resultado_validacion2').addClass('d-none');
                        $('#llenar_mensaje_validacion').empty();
                        $('.resultado_validacion').removeClass('d-none');
                        $('.resultado_validacion').addClass('alert-danger');
                        $('#llenar_mensaje_validacion').append(data.mensajes);                        
                        $('#body_listado_casos_juntas').empty();
                        $('#contenedorTable').addClass('d-none');
                        $('#contenedor_selectores').addClass('d-none');
                        $('#num_registroslabel').removeClass('d-none');
                        $('#num_registros2').addClass('d-none');   
                        $('#num_registros').empty();
                        $('#num_registros').append(data.registros);
                        $('#btn_expor_datos').addClass('d-none');
                        $('#btn_guardar').addClass('d-none');
                        $('#btn_bandeja').removeClass('d-none');
                    }else{

                        $("#mensaje_importante").removeClass('d-none');
                        $("#iniciando_bandeja").addClass('d-none');
                        $("#bandeja_iniciada").removeClass('d-none');
                        $('#alerta_num_registros').empty();
                        $('#alerta_num_registros').append(data.length);

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

                        var actualizarBandeja = '';
                        var modulocalificacionJuntas = '';

                        for (let i = 0; i < data.length; i++) {

                            if (data[i]['Id_Asignacion'] != '') {
                                actualizarBandeja='<input class="checkbox-class" name="actualizaBandejaJuntas" id="actualizar_id_asignacion_'+data[i]['Id_Asignacion']+'" type="checkbox" value="'+data[i]["Id_Asignacion"]+'">';
                                data[i]['actualizarproser'] = actualizarBandeja;
                            }else{
                                data[i]['actualizarproser'] = ""; 
                            }               
                            
                            if (data[i]['Id_Asignacion'] != ''){
            
                                modulocalificacionJuntas = '<form id="form_modulo_calificacion_Juntas_'+data[i]["Id_Asignacion"]+'" action="" method="POST">'+
                                            '<input type="hidden" name="_token" value="'+token+'">'+
                                            '<input class="btn btn-sm text-info" id="modulo_califi_Juntas_'+data[i]["Id_Asignacion"]+'" value="Modulo Juntas" type="submit" style="font-weight: bold; padding-left: inherit;">'+ 
                                            '<input type="hidden" name="newIdAsignacion" value="'+data[i]["Id_Asignacion"]+'">'+
                                            '<input type="hidden" name="newIdEvento" id="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                            '<input type="hidden" name="Id_Servicio" id="Id_Servicio" value="'+data[i]["Id_Servicio"]+'">'+
                                            '</form>';
                                data[i]['moduloJuntas'] = modulocalificacionJuntas;
                                
                            }else{
                                data[i]['moduloJuntas'] = ""; 
                            }  
                        }

                        var inicio = 0;
                        var fin = Math.min(100, data.length);
                        bandejaJuntasTable.clear();
                        function renderizarSiguienteBloque() {
                            if (inicio < data.length) {
                                renderizarRegistros(data, inicio, fin, bandejaJuntasTable);
                                inicio = fin;
                                fin += Math.min(fin + 100, data.length) - fin;
                    
                                if (inicio >= data.length) {
                                    $('#num_registroslabel').removeClass('d-none');
                                    $('#contenedorTable').removeClass('d-none');
                                    $('#mensaje_importante').addClass('d-none');
                                    $("#bandeja_iniciada").addClass('d-none');
                                } else {
                                    setTimeout(renderizarSiguienteBloque, 2000); // Pausa de 2 segundos
                                }
                            }
                        }
                        renderizarSiguienteBloque();
                    }
                }
            });
        }
    }
}