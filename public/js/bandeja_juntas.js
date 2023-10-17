$(document).ready(function () {

    // Inicializacion del select2 del listado de servicio y profesional bandeja de Juntas
    $(".redireccionar").select2({
        placeholder:"Selecione una opción",
        allowClear:false

    });

    $(".profesional").select2({
        placeholder:"Seleccione una opción",
        allowClear:false
    });

    //llenado de selectores 
    let token = $('input[name=_token]').val();

    //Listado de seleccion profecional bandeja Juntas

    let datos_lista_profesional={
        '_token':token,
        'parametro':"lista_profesional_juntas"
    }

    $.ajax({
        type:'POST',
        url:'/selectoresBandejaJuntas',
        data: datos_lista_profesional,
        success:function (data) {
            $('#profesional').append('<option value="" selected>Seleccione</option>');
            let profecionaljuntas = Object.keys(data);
            for (let i = 0; i < profecionaljuntas.length; i++) {
                $('#profesional').append('<option value="'+data[profecionaljuntas[i]]['id']+'">'+data[profecionaljuntas[i]]['name']+'</option>')
            }
            
        }
    });

    //Listado de servicio de badeja Juntas
    let datos_lista_servicio = {
        '_token': token,
        'parametro':"lista_servicios_juntas"
    };
    $.ajax({
        type:'POST',
        url:'/selectoresBandejaJuntas',
        data: datos_lista_servicio,
        success:function(data){
            // console.log(data);
            $('#redireccionar').empty();
            $('#redireccionar').append('<option value="" selected>Seleccione</option>');
            let serviciojuntas = Object.keys(data);
            for (let i =0; i < serviciojuntas.length; i++ ){
                $('#redireccionar').append('<option value="'+data[serviciojuntas[i]]['Id_Servicio']+'">'+data[serviciojuntas[i]]['Nombre_servicio']+'</option>');
            }
        }
    });

    //captura de data sin Filtros        
    let datos_sin_filtro = {            
        '_token': token,
        'BandejaJuntasTotal': "CargaBandejaJuntas"
    };
    $.ajax({
        type:'POST',
        url:'/sinfiltrosBandejaJuntas',
        data: datos_sin_filtro,
        success:function(data) {
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
                                '</form>';
                    data[i]['moduloJuntas'] = modulocalificacionJuntas;
                    
                }else{
                    data[i]['moduloJuntas'] = ""; 
                } 
            }

            $.each(data, function(index, value){
                capturar_informacion_bandejaJuntas(data, index, value)                  
            })
            
        }
        
    });
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
        let token = $('input[name=_token]').val();
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
                'consultar_g_dias': consultar_g_dias
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
                                            '</form>';
                                data[i]['moduloJuntas'] = modulocalificacionJuntas;
                                
                            }else{
                                data[i]['moduloJuntas'] = ""; 
                            }  
                        }

                        $.each(data, function(index, value) {
                            capturar_informacion_bandejaJuntas(data, index, value)
                        })
                    }
                }
            });
    
        }
        setTimeout(() => {
            var botonFiltrar = $('#contenedorTable').parents();
            var contendorBotoFiltrar = botonFiltrar[1].childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[0].classList[0];
            //console.log(contendorBotoFiltrar);
            $('.'+contendorBotoFiltrar).addClass('d-none');
        }, 3000);

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
    $('#Bandeja_Juntas thead tr').clone(true).addClass('filters').appendTo('#Bandeja_Juntas thead');
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

                    if(cell_1[0].cellIndex != 34){

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
                            columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34]
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
    }
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

    setTimeout(() => {
        var botonFiltrar = $('#contenedorTable').parents();
        var contendorBotoFiltrar = botonFiltrar[1].childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[0].classList[0];
        //console.log(contendorBotoFiltrar);
        $('.'+contendorBotoFiltrar).addClass('d-none');
    }, 2000);

    $('#btn_expor_datos').click(function () {
        var infobtnExcel = $(this).parents();
        var selectorbtnExcel = infobtnExcel[3].children[0].childNodes[3].childNodes[1].childNodes[1].childNodes[0].childNodes[0].classList[0];
        //console.log(selectorbtnExcel);
        $('.'+selectorbtnExcel).click();

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

            var profesional = $('#profesional').val();
            var redireccionar = $('#redireccionar').val();
            //var token = $('meta[name="csrf-token"]').attr('content');
            let token = $('input[name=_token]').val();
                        
            var datos_actualizar = {
                'profesional': profesional,
                'redireccionar': redireccionar
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
                        }, 2000);
                    }else{
                        $('.mostrar_mensaje_No_actualizo_bandeja').removeClass('d-none');
                        $('.mostrar_mensaje_No_actualizo_bandeja').append('<strong>'+response.mensaje+'</strong>');
                        setTimeout(function(){
                            $('.mostrar_mensaje_No_actualizo_bandeja').addClass('d-none');
                            $('.mostrar_mensaje_No_actualizo_bandeja').empty();                            
                        }, 2000);
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

});