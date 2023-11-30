$(document).ready(function () {

    // Inicializacion del select2 del listado de servicio y profesional bandeja de PCL

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

    //Listado de seleccion profecional bandeja PCL

    let datos_lista_profesional={
        '_token':token,
        'parametro':"lista_profesional_pcl"
    }

    $.ajax({
        type:'POST',
        url:'/selectoresBandejaPCL',
        data: datos_lista_profesional,
        success:function (data) {
            //console.log(data)
            //$('#profesional').empty();
            $('#profesional').append('<option value="" selected>Seleccione</option>');
            let profecionalpcl = Object.keys(data);
            for (let i = 0; i < profecionalpcl.length; i++) {
                $('#profesional').append('<option value="'+data[profecionalpcl[i]]['id']+'">'+data[profecionalpcl[i]]['name']+'</option>')
            }
            
        }
    });

    //Listado de servicio de badeja PCl
    let datos_lista_servicio = {
        '_token': token,
        'parametro':"lista_servicios_pcl"
    };

    $.ajax({
        type:'POST',
        url:'/selectoresBandejaPCL',
        data: datos_lista_servicio,
        success:function(data){
            // console.log(data);
            $('#redireccionar').empty();
            $('#redireccionar').append('<option value="" selected>Seleccione</option>');
            let serviciopcl = Object.keys(data);
            for (let i =0; i < serviciopcl.length; i++ ){
                $('#redireccionar').append('<option value="'+data[serviciopcl[i]]['Id_Servicio']+'">'+data[serviciopcl[i]]['Nombre_servicio']+'</option>');
            }
        }
    });

    //captura de data sin Filtros        
    let datos_sin_filtro = {            
        '_token': token,
        'BandejaPClTotal': "CargaBandejaPCl"
    };
    
    $.ajax({
        type:'POST',
        url:'/sinfiltrosBandejaPCL',
        data: datos_sin_filtro,
        success:function(data) {
            $('#num_registros').empty();
            $('#num_registros').append(data.length);

            var actualizarBandeja = '';
            var modulocalificacionpcl = '';

            for (let i = 0; i < data.length; i++) {

                if (data[i]['Id_Asignacion'] != '') {
                    actualizarBandeja='<input class="checkbox-class" name="actualizaBandejaPCL" id="actualizar_id_asignacion_'+data[i]['Id_Asignacion']+'" type="checkbox" value="'+data[i]["Id_Asignacion"]+'">';
                    data[i]['actualizarproser'] = actualizarBandeja;
                }else{
                    data[i]['actualizarproser'] = ""; 
                }               
                
                if (data[i]['Id_Asignacion'] != ''){

                    modulocalificacionpcl = '<form id="form_modulo_calificacion_PCL_'+data[i]["Id_Asignacion"]+'" action="" method="POST">'+
                                '<input type="hidden" name="_token" value="'+token+'">'+
                                '<input class="btn btn-sm text-info" id="modulo_califi_pcl_'+data[i]["Id_Asignacion"]+'" value="Modulo PCL" type="submit" style="font-weight: bold; padding-left: inherit;">'+ 
                                '<input type="hidden" name="newIdAsignacion" value="'+data[i]["Id_Asignacion"]+'">'+
                                '<input type="hidden" name="newIdEvento" id="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                '</form>';
                    data[i]['moduloPCL'] = modulocalificacionpcl;
                    
                }else{
                    data[i]['moduloPCL'] = ""; 
                } 
            }

            $.each(data, function(index, value){
                capturar_informacion_bandejaPCL(data, index, value)                  
            })
            
        }
        
    });  

        
    //Captura id Checkbox para extraer su value
    var arrayIdCheckActualizar = [];    
    //console.log('array inicial');
    //console.log(arrayIdCheckActualizar);
        
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

    $('#form_filtro_bandejaPcl').submit(function (e) {
        e.preventDefault();
        var consultar_f_desde = $('#consultar_f_desde').val();
        var consultar_f_hasta = $('#consultar_f_hasta').val();
        var consultar_g_dias = $('#consultar_g_dias').val();
        let token = $('input[name=_token]').val();
        if(consultar_f_desde == "" && consultar_f_hasta == "" && consultar_g_dias == ""){                        
            $('.resultado_validacion').addClass('d-none');
            $('.resultado_validacion2').removeClass('d-none');
            $('#body_listado_casos_pcl').empty();
            $('#contenedorTable').addClass('d-none');
            $('#contenedor_selectores').addClass('d-none');
            $('#num_registros2').removeClass('d-none');            
            $('#num_registroslabel').addClass('d-none');
            $('#body_listado_casos_pcl').empty();
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
                url:'/filtrosBandejaPCL',
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
                        $('#body_listado_casos_pcl').empty();
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
                        var modulocalificacionpcl = '';

                        for (let i = 0; i < data.length; i++) {

                            if (data[i]['Id_Asignacion'] != '') {
                                actualizarBandeja='<input class="checkbox-class" name="actualizaBandejaPCL" id="actualizar_id_asignacion_'+data[i]['Id_Asignacion']+'" type="checkbox" value="'+data[i]["Id_Asignacion"]+'">';
                                data[i]['actualizarproser'] = actualizarBandeja;
                            }else{
                                data[i]['actualizarproser'] = ""; 
                            }               
                            
                            if (data[i]['Id_Asignacion'] != ''){
            
                                modulocalificacionpcl = '<form id="form_modulo_calificacion_PCL_'+data[i]["Id_Asignacion"]+'" action="" method="POST">'+
                                            '<input type="hidden" name="_token" value="'+token+'">'+
                                            '<input class="btn btn-sm text-info" id="modulo_califi_pcl_'+data[i]["Id_Asignacion"]+'" value="Modulo PCL" type="submit" style="font-weight: bold; padding-left: inherit;">'+ 
                                            '<input type="hidden" name="newIdAsignacion" value="'+data[i]["Id_Asignacion"]+'">'+
                                            '<input type="hidden" name="newIdEvento" id="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                            '</form>';
                                data[i]['moduloPCL'] = modulocalificacionpcl;
                                
                            }else{
                                data[i]['moduloPCL'] = ""; 
                            }  
                        }

                        $.each(data, function(index, value) {
                            capturar_informacion_bandejaPCL(data, index, value)
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
    $(".PCLbandeja").hover(function(){
        dimensionartable++;
        if (dimensionartable == 1) {
            $('.detallepcl').click();		
        }
    });   

        
    //Datatable Bandeja PCL
    
    $('#Bandeja_Pcl thead tr').clone(true).addClass('filters').appendTo('#Bandeja_Pcl thead');
    function capturar_informacion_bandejaPCL(response, index, value) {        
        $('#Bandeja_Pcl').DataTable({            
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

                    if(cell_1[0].cellIndex != 26){

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
                        title: 'Bandeja PCL',
                        text:'Exportar datos',
                        className: 'btn btn-success',
                        "excelStyles": [                      // estilos de excel
                                                    
                        ],
                        //Limitar columnas para el reporte
                        exportOptions: {
                            columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]
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
                        return data.actualizarproser+data.moduloPCL;
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
                {"data":"F_solicitud_documento"},
                {"data":"F_recepcion_documento"},
                {"data":"Fecha_asignacion_calif"},
                {"data":"Fecha_devolucion_comite"},
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
                $('#Bandeja_Pcl input[type="checkbox"]').each(function() {
                    arrayIdCheckActualizar.push($(this).val());
                });
                arrayIdCheckActualizar.splice(0,1);
                //console.log('seleccion grupal')
                //console.log(arrayIdCheckActualizar);
            }, 2000);            
        }else{            
            setTimeout(() => {
                $('#Bandeja_Pcl input[type="checkbox"]').each(function() {
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

    //Asignar ruta del formulario de modulo calificacion pcl
    $(document).on('mouseover',"input[id^='modulo_califi_pcl_']", function(){
        let url_editar_evento = $('#action_modulo_calificacion_pcl').val();
        $("form[id^='form_modulo_calificacion_PCL_']").attr("action", url_editar_evento);    
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
   

    $('#form_proser_bandejaPcl').submit(function (e) {
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
                url:'/actualizarProfesionalServicio',          
                type:'POST',
                data: datos_enviar,
                success: function (response) 
                {
                    //console.log(response);
                    if(response.parametro == 'actualizado_B_PCL'){                        
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