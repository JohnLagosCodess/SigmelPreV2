$(document).ready(function(){

    $(".centrar").css('text-align', 'center');
    /* INICIALIZACIÓN DEL SELECT2 DE LISTADO DE TIPOS DE CLIENTES */
    $(".tipo_cliente").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN DEL SELECT2 DE DEPARTAMENTOS  y CIUDADES */
    $(".departamento").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    $(".ciudad").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT2 STATUS CLIENTE */
    $(".status_cliente").select2({
        placeholder: "Seleccione una opción",
        allowClear: false
    });

    let token = $('input[name=_token]').val();

    // TABLA PARA MOSTRAR EL LISTADO DE CLIENTES
    $('#listado_clientes thead tr').clone(true).addClass('filters').appendTo('#listado_clientes thead');
    $('#listado_clientes').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 5,
        "destroy": true,
        "order": [[1, 'asc']],
        initComplete: function () {
            var api = this.api();
                // For each column
            api.columns().eq(0).each(function (colIdx) {
                // Set the header cell to contain the input element
                var cell = $('.filters th').eq(
                    $(api.column(colIdx).header()).index()
                );
                
                var title = $(cell).text();
                
                if (title !== 'Detalle') {
                    
                    $(cell).html('<input type="text" style="width:100%;"/>');
                    $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                    .on('change', function (e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({search})';
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
                    title: 'Lista de Clientes',
                    text:'Exportar datos',
                    className: 'btn btn-info',
                    "excelStyles": [                      // Add an excelStyles definition
                                                
                    ],
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
                    }
                }
            ]
        },
        "language":{
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_ resgistros por página",
            "info": "Mostrando página _PAGE_ de _PAGES_",
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


    // Listado Municipio evento change
    $('#departamento').change( function(){
        $('#ciudad').prop('disabled', false);
        let id_departamento_cliente = $('#departamento').val();
        let datos_municipio_cliente = {
            '_token': token,
            'parametro' : "lista_municipios_cliente",
            'id_departamento_cliente': id_departamento_cliente
        };

        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_municipio_cliente,
            success:function(data) {
                //console.log(data);
                $('#ciudad').empty();
                $('#ciudad').append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#ciudad').append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                }
            }
        });
    });

    // Validación de la escritura de correo en el campo Email principal
    $("#email_principal").keyup(function(){
        var email_escrito = $(this).val();
        var patronCorreo = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!patronCorreo.test(email_escrito)) {
            $(".mensaje_correo_mal_escrito").removeClass('d-none');
            $("#GuardarCliente").prop("disabled", true);
        } else {
            $(".mensaje_correo_mal_escrito").addClass('d-none');
            $("#GuardarCliente").prop("disabled", false);
        }
    });

    /* Validación opción OTRO/¿Cuál? del selector Tipo de Cliente */
    $('.columna_otro_tipo_cliente').css('display','none');
    $('#tipo_cliente').change(function(){
        let opt_otro_cual_tipo_cliente = $("#tipo_cliente option:selected").text();
        if (opt_otro_cual_tipo_cliente === "OTRO/¿Cuál?") {
            $(".columna_otro_tipo_cliente").slideDown('slow');
            $('#otro_tipo_cliente').prop('required', true);
        }else{
            $(".columna_otro_tipo_cliente").slideUp('slow');
            $('#otro_tipo_cliente').prop('required', false);
        }
    });     

    // Función solo numeros para input valor ans
    $(document).on('input', "input[id^='valor_ans_']", function(event){
        var value = $(this).val();
      
        // Eliminar todos los caracteres no numéricos y no "."
        value = value.replace(/[^0-9.]/g, '');
        
        // Verificar si hay más de un punto decimal y eliminar el exceso
        var decimalCount = (value.match(/\./g) || []).length;
        if (decimalCount > 1) {
            value = value.replace(/\.+$/,"");
        }
        
        // Actualizar el valor del input
        $(this).val(value);
    });

    /* INICIALIZACIÓN SUMMERNOTE PARA FIRMAS DEL CLIENTE */
    $('#firma_del_cliente').summernote({
        height: 210,
        toolbar: [
            ['font', ['fontname']],
            ['fontsize', ['fontsize']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['forecolor']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['picture']],
        ]
    });

    /* INICIALIZACIÓN SUMMERNOTE PARA FIRMAS DEL PROVEEDOR */
    $('#firma_del_proveedor').summernote({
        height: 210,
        toolbar: [
            ['font', ['fontname']],
            ['fontsize', ['fontsize']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['forecolor']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['picture']],
        ]
    });
    
    var no_datos_sucursales = null;
    var no_datos_ans = null;
    var no_datos_firmas_cliente = null;
    var no_datos_firmas_proveedor = null;

    // EJECUCIÓN DE CÓDIGOS CUANDO SE PASA EL MOUSE SOBRE EL BOTÓN DEL MODAL EDICIÓN CLIENTE
    $(document).on('mouseover', "a[id^='btn_modal_edicion_cliente_']", function(){
        // borrar el modal title para insertar un texto en especifico y eliminar el modal footer
        $(".modal-title").empty();
        $(".modal-footer").remove();

        // Captura del id de la accion y el nombre de la misma.
        let id_editar_cliente = $(this).data("id_editar_cliente");
        let nombre_editar_cliente = $(this).data("nombre_editar_cliente");

        // generar el datatarget especifico para cuando se abra un modal de edicion de accion
        $(".habilitar_modal_edicion_cliente").attr("id", "modalEdicionCliente_"+id_editar_cliente);
        // colocar el titulo del modal
        $(".modal-title").append("<i class='fa fa-pen mr-2'></i> Edición Cliente : "+nombre_editar_cliente);
 
        // añadir el atributo id para cuando se necesite enviar los datos de la accion a editar
        $(".actualizar_cliente").attr("id", "form_actualizar_cliente_"+id_editar_cliente);
        // Enviar el id de la accion a editar para temas de actualizacion en el controlador.
        $('#id_cliente_editar').val(id_editar_cliente);

        // SETEAR A VACIO DE LOS CAMPOS DEL FORM
        $('#tipo_cliente').empty();
        $('#nombre_cliente').empty();
        $('#nit_cliente').empty();
        $('#telefono_principal').empty();
        $('#otros_telefonos').empty();
        $('#email_principal').empty();
        $('#otros_emails').empty();
        $('#linea_atencion_principal').empty();
        $('#otras_lineas_atencion').empty();
        $('#direccion').empty();
        $('#departamento').empty();
        $('#ciudad').empty();
        $('#nro_contrato').empty();
        $('#f_inicio_contrato').empty();
        $('#f_finalizacion_contrato').empty();
        $('#nro_contrato').empty();
        $('#f_inicio_contrato').empty();
        $('#f_finalizacion_contrato').empty();
        $('#nro_consecutivo_dictamen').empty();
        $("#previewImage").val('');
        $("#footerContainer").val('');
        $("#footer_dato_1").val('');
        $("#footer_dato_2").val('');
        $("#footer_dato_3").val('');
        $("#footer_dato_4").val('');
        $("#footer_dato_5").val('');
        

        $("#checkbox_servicio_dto").prop("checked", false);
        $("#valor_tarifa_servicio_dto").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_dto").val('').addClass('d-none');
        $("#checkbox_servicio_adicion_dx").prop("checked", false);
        $("#valor_tarifa_servicio_adicion_dx").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_adicion_dx").val('').addClass('d-none');
        $("#checkbox_servicio_pronunciamiento").prop("checked", false);
        $("#valor_tarifa_servicio_pronunciamiento").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_pronunciamiento").val('').addClass('d-none');
        $("#checkbox_servicio_calificacion_tecnica").prop("checked", false);
        $("#valor_tarifa_servicio_calificacion_tecnica").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_calificacion_tecnica").val('').addClass('d-none');
        $("#checkbox_servicio_recalificacion").prop("checked", false);
        $("#valor_tarifa_servicio_recalificacion").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_recalificacion").val('').addClass('d-none');
        $("#checkbox_servicio_revision_pension").prop("checked", false);
        $("#valor_tarifa_servicio_revision_pension").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_revision_pension").val('').addClass('d-none');
        $("#checkbox_servicio_pronunciamiento_pcl").prop("checked", false);
        $("#valor_tarifa_servicio_pronunciamiento_pcl").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_pronunciamiento_pcl").val('').addClass('d-none');
        $("#checkbox_servicio_controversia_origen").prop("checked", false);
        $("#valor_tarifa_servicio_controversia_origen").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_controversia_origen").val('').addClass('d-none');
        $("#checkbox_servicio_controversia_pcl").prop("checked", false);
        $("#valor_tarifa_servicio_controversia_pcl").val('').addClass('d-none');
        // $("#nro_consecutivo_servicio_controversia_pcl").val('').addClass('d-none');

        // $("#checkbox_servicio_pqrd").prop("checked", false);
        // $("#valor_tarifa_servicio_pqrd").val('').addClass('d-none');
        // // $("#nro_consecutivo_servicio_pqrd").val('').addClass('d-none');
        // $("#checkbox_servicio_tutelas").prop("checked", false);
        // $("#valor_tarifa_servicio_tutelas").val('').addClass('d-none');
        // // $("#nro_consecutivo_servicio_tutelas").val('').addClass('d-none');
        // $("#checkbox_servicio_gis").prop("checked", false);
        // $("#valor_tarifa_servicio_gis").val('').addClass('d-none');
        // // $("#nro_consecutivo_servicio_gis").val('').addClass('d-none');
        // $("#checkbox_servicio_auditorias").prop("checked", false);
        // $("#valor_tarifa_servicio_auditorias").val('').addClass('d-none');
        // // $("#nro_consecutivo_servicio_auditorias").val('').addClass('d-none');


        // SETEAR CAMPOS DEL FORMULARIO
        var consultar_info_cliente = {
            '_token': $('input[name=_token]').val(),
            'parametro': "info_basica",
            'id_cliente_editar': id_editar_cliente,
        };

        $.ajax({
            type:'POST',
            url: $('#traer_datos_cliente').val(),
            data: consultar_info_cliente,
            success:function(data_cliente){
                
                // listado de tipos de clientes
                let datos_lista_tipo_clientes = {
                    '_token': token,
                    'parametro' : "lista_tipo_clientes",
                    'parametro1': "nuevo_cliente"
                };
                $.ajax({
                    type:'POST',
                    url:'/cargarselectores',
                    data: datos_lista_tipo_clientes,
                    success:function(data) {
                        $('#tipo_cliente').empty();
                        $('#tipo_cliente').append('<option value="" selected>Seleccione</option>');
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            if (data[claves[i]]["Id_TipoCliente"] == data_cliente[0]["Tipo_cliente"]) {
                                $('#tipo_cliente').append('<option value="'+data[claves[i]]["Id_TipoCliente"]+'" selected>'+data[claves[i]]["Nombre_tipo_cliente"]+'</option>');
                            } else {
                                $('#tipo_cliente').append('<option value="'+data[claves[i]]["Id_TipoCliente"]+'">'+data[claves[i]]["Nombre_tipo_cliente"]+'</option>');
                            }
                        }
                    }
                });

                $('#nombre_cliente').val(data_cliente[0]["Nombre_cliente"]);
                $('#nit_cliente').val(data_cliente[0]["Nit"]);
                $('#telefono_principal').val(data_cliente[0]["Telefono_principal"]);
                $('#otros_telefonos').val(data_cliente[0]["Otros_telefonos"]);
                $('#email_principal').val(data_cliente[0]["Email_principal"]);
                $('#otros_emails').val(data_cliente[0]["Otros_emails"]);
                $('#linea_atencion_principal').val(data_cliente[0]["Linea_atencion_principal"]);
                $('#otras_lineas_atencion').val(data_cliente[0]["Otras_lineas_atencion"]);
                $('#direccion').val(data_cliente[0]["Direccion"]);
                $('#nro_contrato').val(data_cliente[0]["Nro_Contrato"]);
                $('#f_inicio_contrato').val(data_cliente[0]["F_inicio_contrato"]);
                $('#f_finalizacion_contrato').val(data_cliente[0]["F_finalizacion_contrato"]);
                $('#nro_consecutivo_dictamen').val(data_cliente[0]["Nro_consecutivo_dictamen"]);

                // Captura consecutivo Evento
                let consecutivo_evento = {
                    '_token': token,
                    'parametro' : "Nro_consecutivo_evento"                    
                };

                $.ajax({
                    type:'POST',
                    url:'/ConsecutivoIdEvento',
                    data:consecutivo_evento,
                    success:function(dataEvento){
                        $('#nro_consecutivo_evento').val(dataEvento[0]["Numero_orden"]);                        
                    }
                }); 

                //Listado de departamento
                let datos_lista_departamento_cliente = {
                    '_token': token,
                    'parametro':"lista_departamentos_cliente"
                };
                $.ajax({
                    type:'POST',
                    url:'/cargarselectores',
                    data: datos_lista_departamento_cliente,
                    success:function(data) {
                        //console.log(data);
                        $('#departamento').empty();
                        $('#departamento').append('<option value="" selected>Seleccione</option>');
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            if (data[claves[i]]["Id_departamento"] == data_cliente[0]["Id_Departamento"]) {
                                $('#departamento').append('<option value="'+data[claves[i]]["Id_departamento"]+'" selected>'+data[claves[i]]["Nombre_departamento"]+'</option>');
                            } else {
                                $('#departamento').append('<option value="'+data[claves[i]]["Id_departamento"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
                            }
                        }
                    }
                });

                // Listado de Ciudades
                $('#ciudad').prop('disabled', false);
                let id_departamento_cliente = data_cliente[0]["Id_Departamento"];
                
                let datos_municipio_cliente = {
                    '_token': token,
                    'parametro' : "lista_municipios_cliente",
                    'id_departamento_cliente': id_departamento_cliente
                };

                $.ajax({
                    type:'POST',
                    url:'/cargarselectores',
                    data: datos_municipio_cliente,
                    success:function(data) {
                        //console.log(data);
                        $('#ciudad').empty();
                        $('#ciudad').append('<option value="" selected>Seleccione</option>');
                        let claves = Object.keys(data);
                        for (let i = 0; i < claves.length; i++) {
                            if (data[claves[i]]["Id_municipios"] == data_cliente[0]["Id_Ciudad"]) {
                                $('#ciudad').append('<option value="'+data[claves[i]]["Id_municipios"]+'" selected>'+data[claves[i]]["Nombre_municipio"]+'</option>');
                            } else {
                                $('#ciudad').append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                            }
                        }
                    }
                });


                if (data_cliente[0]["Logo_cliente"] != null) {
                    var url_logo_cliente = $("#httpohttps").val()+$("#host").val()+"/logos_clientes/"+id_editar_cliente+"/"+data_cliente[0]["Logo_cliente"];
                    $("#previewImage").attr('src', url_logo_cliente);
                    $("#nombre_logo_bd").val(data_cliente[0]["Logo_cliente"]);
                }

                if (data_cliente[0]["Footer_cliente"] != null) {
                    var url_footer_cliente = $("#httpohttps").val()+$("#host").val()+"/footer_clientes/"+id_editar_cliente+"/"+data_cliente[0]["Footer_cliente"];
                    $("#footerContainer").attr('src', url_footer_cliente);
                    $("#nombre_footer_bd").val(data_cliente[0]["Footer_cliente"]);
                }

                if (data_cliente[0]["Estado"] == "Activo") {
                    $("#status_cliente").empty();
                    $("#status_cliente").append('<option></option><option value="Activo" selected>Activo</option><option value="Inactivo">Inactivo</option>')
                } else {
                    $("#status_cliente").empty();
                    $("#status_cliente").append('<option></option><option value="Activo">Activo</option><option value="Inactivo" selected>Inactivo</option>')
                }


                $("#codigo_cliente").val(data_cliente[0]["Codigo_cliente"]);
                $("#fecha_creacion").val(data_cliente[0]["F_registro"]);

                $("#footer_dato_1").val(data_cliente[0]["footer_dato_1"]);
                $("#footer_dato_2").val(data_cliente[0]["footer_dato_2"]);
                $("#footer_dato_3").val(data_cliente[0]["footer_dato_3"]);
                $("#footer_dato_4").val(data_cliente[0]["footer_dato_4"]);
                $("#footer_dato_5").val(data_cliente[0]["footer_dato_5"]);
                
            }
        });
        
        // Construcción del Datatable Sucursales incluyendo los datos que hay en la bd
        let datos_sucursales_cliente = {
            '_token': token,
            'parametro': "listado_sucursales_cliente",
            'id_cliente_editar': id_editar_cliente,
        };
        
        $.ajax({
            type:'POST',
            url: $('#traer_datos_cliente').val(),
            data: datos_sucursales_cliente,
            success:function(data_sucursales){
                $('#borrar_tabla_sucursales').empty();
                if(data_sucursales.length == 0){
                    $('#borrar_tabla_sucursales').empty();
                    /* DATATABLES SURCURSALES */
                    sucursales = $('#sucursales').DataTable({
                        "responsive": true,
                        "info": false,
                        "searching": false,
                        "ordering": false,
                        "scrollCollapse": true,
                        "destroy": true,
                        "paging": false,
                        "language":{
                            "emptyTable": "No se encontró información"
                        }
                    });

                    autoAdjustColumns(sucursales);
                    no_datos_sucursales = 1;
                }else{
                    $.each(data_sucursales, function(index, value){
                        llenar_tabla_sucursales(data_sucursales);
                    });
                    no_datos_sucursales = 0;
                }
            }
        });
        
        // Construcción de marcación y visualización de los Servicios Contratados incluyendo los datos que hay en la bd
        let datos_servicios_contratados_cliente = {
            '_token': token,
            'parametro': "servicios_contratados_cliente",
            'id_cliente_editar': id_editar_cliente,
        }
        
        $.ajax({
            type:'POST',
            url: $('#traer_datos_cliente').val(),
            data: datos_servicios_contratados_cliente,
            success:function(data_servicios_contratados){
               
                var valor_checkbox_servicio_dto = $("#checkbox_servicio_dto").val();
                var valor_checkbox_servicio_adicion_dx = $("#checkbox_servicio_adicion_dx").val();
                var valor_checkbox_servicio_pronunciamiento = $("#checkbox_servicio_pronunciamiento").val();
                var valor_checkbox_servicio_calificacion_tecnica = $("#checkbox_servicio_calificacion_tecnica").val();
                var valor_checkbox_servicio_recalificacion = $("#checkbox_servicio_recalificacion").val();
                var valor_checkbox_servicio_revision_pension = $("#checkbox_servicio_revision_pension").val();
                var valor_checkbox_servicio_pronunciamiento_pcl = $("#checkbox_servicio_pronunciamiento_pcl").val();
                var valor_checkbox_servicio_controversia_origen = $("#checkbox_servicio_controversia_origen").val();
                var valor_checkbox_servicio_controversia_pcl = $("#checkbox_servicio_controversia_pcl").val();
                var valor_checkbox_servicio_pqrd = $("#checkbox_servicio_pqrd").val();
                var valor_checkbox_servicio_tutelas = $("#checkbox_servicio_tutelas").val();
                var valor_checkbox_servicio_gis = $("#checkbox_servicio_gis").val();
                var valor_checkbox_servicio_auditorias = $("#checkbox_servicio_auditorias").val();

                if(data_servicios_contratados.length != 0){
                    $.each(data_servicios_contratados, function(index, value){

                        if (valor_checkbox_servicio_dto == value['Id_servicio']) {
                            $("#checkbox_servicio_dto").prop("checked", true);
                            $("#valor_tarifa_servicio_dto").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_dto").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_dto").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }
                        if (valor_checkbox_servicio_adicion_dx == value['Id_servicio']) {
                            $("#checkbox_servicio_adicion_dx").prop("checked", true);
                            $("#valor_tarifa_servicio_adicion_dx").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_adicion_dx").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_adicion_dx").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }
                        if (valor_checkbox_servicio_pronunciamiento == value['Id_servicio']) {
                            $("#checkbox_servicio_pronunciamiento").prop("checked", true);
                            $("#valor_tarifa_servicio_pronunciamiento").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_pronunciamiento").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_pronunciamiento").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        if (valor_checkbox_servicio_calificacion_tecnica == value['Id_servicio']) {
                            $("#checkbox_servicio_calificacion_tecnica").prop("checked", true);
                            $("#valor_tarifa_servicio_calificacion_tecnica").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_calificacion_tecnica").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_calificacion_tecnica").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        if (valor_checkbox_servicio_recalificacion == value['Id_servicio']) {
                            $("#checkbox_servicio_recalificacion").prop("checked", true);
                            $("#valor_tarifa_servicio_recalificacion").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_recalificacion").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_recalificacion").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }
                        
                        if (valor_checkbox_servicio_revision_pension == value['Id_servicio']) {
                            $("#checkbox_servicio_revision_pension").prop("checked", true);
                            $("#valor_tarifa_servicio_revision_pension").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_revision_pension").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_revision_pension").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        if (valor_checkbox_servicio_pronunciamiento_pcl == value['Id_servicio']) {
                            $("#checkbox_servicio_pronunciamiento_pcl").prop("checked", true);
                            $("#valor_tarifa_servicio_pronunciamiento_pcl").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_pronunciamiento_pcl").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_pronunciamiento_pcl").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        if (valor_checkbox_servicio_controversia_origen == value['Id_servicio']) {
                            $("#checkbox_servicio_controversia_origen").prop("checked", true);
                            $("#valor_tarifa_servicio_controversia_origen").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_controversia_origen").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_controversia_origen").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        if (valor_checkbox_servicio_controversia_pcl == value['Id_servicio']) {
                            $("#checkbox_servicio_controversia_pcl").prop("checked", true);
                            $("#valor_tarifa_servicio_controversia_pcl").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_controversia_pcl").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_controversia_pcl").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        /* LOS SERVICIOS QUE CORRESPONDEN A OTROS SE PROGRAMAN PERO NO SE USAN ACTUALMENTE */
                        /* if (valor_checkbox_servicio_pqrd == value['Id_servicio']) {
                            $("#checkbox_servicio_pqrd").prop("checked", true);
                            $("#valor_tarifa_servicio_pqrd").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_pqrd").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_pqrd").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        if (valor_checkbox_servicio_tutelas == value['Id_servicio']) {
                            $("#checkbox_servicio_tutelas").prop("checked", true);
                            $("#valor_tarifa_servicio_tutelas").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_tutelas").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_tutelas").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        if (valor_checkbox_servicio_gis == value['Id_servicio']) {
                            $("#checkbox_servicio_gis").prop("checked", true);
                            $("#valor_tarifa_servicio_gis").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_gis").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_gis").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        }

                        if (valor_checkbox_servicio_auditorias == value['Id_servicio']) {
                            $("#checkbox_servicio_auditorias").prop("checked", true);
                            $("#valor_tarifa_servicio_auditorias").val(value['Valor_tarifa_servicio']).removeClass('d-none');
                            // if (value['Nro_consecutivo_dictamen_servicio'] != null) {
                            //     $("#nro_consecutivo_servicio_auditorias").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", true);
                            // }else{
                            //     $("#nro_consecutivo_servicio_auditorias").val(value['Nro_consecutivo_dictamen_servicio']).removeClass('d-none').prop("readonly", false);
                            // }
                        } */

                    });
                }
                
            }
        });


        // Construcción del Datatable ANS incluyendo los datos que hay en la bd
        let datos_ans_cliente = {
            '_token': token,
            'parametro': "listado_ans_cliente",
            'id_cliente_editar': id_editar_cliente,
        };
        
        $.ajax({
            type:'POST',
            url: $('#traer_datos_cliente').val(),
            data: datos_ans_cliente,
            success:function(data_ans){
                $('#borrar_tabla_ans').empty();
                if(data_ans.length == 0){
                    $('#borrar_tabla_ans').empty();

                    /* DATATABLE ANS */
                    ans = $('#ans').DataTable({
                        "responsive": true,
                        "info": false,
                        "searching": false,
                        "ordering": false,
                        "scrollCollapse": true,
                        "destroy": true,
                        "paging": false,
                        "language":{
                            "emptyTable": "No se encontró información"
                        }
                    });

                    autoAdjustColumns(ans);
                    no_datos_ans = 1;
                }else{
                    
                    $.each(data_ans, function(index, value){
                        llenar_tabla_ans(data_ans);
                    });
                    no_datos_ans = 0;
                }
            }
        });

        // Firmas Cliente
        let datos_firmas_cliente = {
            '_token': token,
            'parametro': "firmas_cliente",
            'id_cliente_editar': id_editar_cliente,
        };

        $.ajax({
            type:'POST',
            url: $('#traer_datos_cliente').val(),
            data: datos_firmas_cliente,
            success:function(data_firmas){
                // console.log(data_firmas);
                var container = $("#contenedor_html_firma_cliente");
                for (let i = 0; i < data_firmas.length; i++) {

                    container.html(data_firmas[i].Firma);

                    var images = container.find('img');
                    for (var a= 0; a < images.length; a++) {
                        images.attr('src', data_firmas[i].Url);
                    }

                    data_firmas[i].Firma = $("#contenedor_html_firma_cliente").html();
                }
                
                $('#borrar_tabla_firmas_cliente').empty();
                if(data_firmas.length == 0){
                    $('#borrar_tabla_firmas_cliente').empty();

                    /* DATATABLE FIRMAS */
                    firmas_cliente = $('#listado_firmas_cliente').DataTable({
                        "responsive": true,
                        "info": false,
                        "searching": false,
                        "ordering": false,
                        "scrollCollapse": true,
                        "destroy": true,
                        "paging": false,
                        "language":{
                            "emptyTable": "No se encontró información"
                        }
                    });

                    autoAdjustColumns(firmas_cliente);

                    $("#agregar_firma_cliente").removeClass('d-none');
                    $("#nombre_del_firmante_cliente").prop("required", true);
                    $("#cargo_del_firmante_cliente").prop("required", true);
                    $("#firma_del_cliente").prop("required", true);
                    // $("#cerrar_formulario_edicion_firma_cliente").addClass('d-none');

                    no_datos_firmas_cliente = 1;
                }else{
                    
                    $.each(data_firmas, function(index, value){
                        llenar_tabla_firmas_cliente(data_firmas);
                    });
                    no_datos_firmas_cliente = 0;

                    $("#agregar_firma_cliente").addClass('d-none');
                    $("#nombre_del_firmante_cliente").prop("required", false);
                    $("#cargo_del_firmante_cliente").prop("required", false);
                    $("#firma_del_cliente").prop("required", false);
                }
            }
        });

        // Firmas Proveedor
        let datos_firmas_proveedor = {
            '_token': token,
            'parametro': "firmas_proveedor",
            'id_cliente_editar': id_editar_cliente,
        };

        $.ajax({
            type:'POST',
            url: $('#traer_datos_cliente').val(),
            data: datos_firmas_proveedor,
            success:function(data_firmas){
                // console.log(data_firmas);
                var container = $("#contenedor_html_firma_proveedor");
                for (let i = 0; i < data_firmas.length; i++) {

                    container.html(data_firmas[i].Firma);

                    var images = container.find('img');
                    for (var a= 0; a < images.length; a++) {
                        images.attr('src', data_firmas[i].Url);
                    }

                    data_firmas[i].Firma = $("#contenedor_html_firma_proveedor").html();
                }
                
                $('#borrar_tabla_firmas_proveedor').empty();
                if(data_firmas.length == 0){
                    $('#borrar_tabla_firmas_proveedor').empty();

                    /* DATATABLE FIRMAS */
                    firmas_proveedor = $('#listado_firmas_proveedor').DataTable({
                        "responsive": true,
                        "info": false,
                        "searching": false,
                        "ordering": false,
                        "scrollCollapse": true,
                        "destroy": true,
                        "paging": false,
                        "language":{
                            "emptyTable": "No se encontró información"
                        }
                    });

                    autoAdjustColumns(firmas_proveedor);

                    $("#agregar_firma_proveedor").removeClass('d-none');
                    $("#nombre_del_firmante_proveedor").prop("required", true);
                    $("#cargo_del_firmante_proveedor").prop("required", true);
                    $("#firma_del_proveedor").prop("required", true);
                    // $("#cerrar_formulario_edicion_firma_proveedor").addClass('d-none');

                    no_datos_firmas_proveedor = 1;
                }else{
                    
                    $.each(data_firmas, function(index, value){
                        llenar_tabla_firmas_proveedor(data_firmas);
                    });
                    no_datos_firmas_proveedor = 0;

                    $("#agregar_firma_proveedor").addClass('d-none');
                    $("#nombre_del_firmante_proveedor").prop("required", false);
                    $("#cargo_del_firmante_proveedor").prop("required", false);
                    $("#firma_del_proveedor").prop("required", false);
                }
            }
        });

        
    });

    // FUNCIÓN PARA LLENAR LOS DATOS DEL DATATABLE SUCURSALES
    var sucursales = "";
    function llenar_tabla_sucursales(response){

        /* DATATABLES SURCURSALES */
        sucursales = $('#sucursales').DataTable({
            "responsive": true,
            "info": false,
            "searching": false,
            "ordering": false,
            "scrollCollapse": true,
            "paging": false,
            "destroy": true,
            "data": response,
            createdRow: function(row, data, index) {
                $(row).addClass("fila_sucursal_" + data.Id_sucursal);
                $(row).attr("id", "datos_sucursales");
            },
            "columns":[
                {data:"Nombre"},
                {data:"Gerente"},
                {data:"Telefono_principal"},
                {data:"Otros_telefonos"},
                {data:"Email_principal"},
                {data:"Otros_emails"},
                {data:"Linea_atencion_principal"},
                {data:"Otras_lineas_atencion"},
                {data:"Direccion"},
                {data:"Nombre_departamento"},
                {data:"Nombre_municipio"},
                {data:"string_html"},
            ],
            "language":{
                "emptyTable": "No se encontró información"
            }
        });

        autoAdjustColumns(sucursales);
    }

    // FUNCIONALIDADES TABLA SUCURSALES
    var contador_sucursales = 0;
    $("#btn_agregar_sucursal_fila").click(function(){
        contador_sucursales = contador_sucursales + 1;

        if(no_datos_sucursales == 0){
            var nueva_fila_sucursales = {
                Nombre: '<input type="text" class="form-control" name="nombre_sucursal" id="nombre_sucursal_'+contador_sucursales+'">',
                Gerente: '<input type="text" class="form-control" name="nombre_gerente_sucursal" id="nombre_gerente_sucursal_'+contador_sucursales+'">',
                Telefono_principal: '<input type="number" class="form-control soloNumeros" name="telefono_principal_sucursal" id="telefono_principal_sucursal_'+contador_sucursales+'">',
                Otros_telefonos: '<input type="text" class="form-control" name="otro_telefono_sucursal" id="otro_telefono_sucursal_'+contador_sucursales+'">',
                Email_principal: '<input type="email" class="form-control" name="email_principal_sucursal" id="email_principal_sucursal_'+contador_sucursales+'">',
                Otros_emails: '<input type="text" class="form-control" name="otro_email_sucursal" id="otro_email_sucursal_'+contador_sucursales+'">',
                Linea_atencion_principal: '<input type="number" class="form-control soloNumeros" name="linea_atencion_principal_sucursal" id="linea_atencion_principal_sucursal_'+contador_sucursales+'">',
                Otras_lineas_atencion: '<input type="text" class="form-control" name="otro_linea_atencion_sucursal" id="otro_linea_atencion_sucursal_'+contador_sucursales+'">',
                Direccion: '<input type="text" class="form-control" name="direccion_sucursal" id="direccion_sucursal_'+contador_sucursales+'">',
                Nombre_departamento: '<select  name="departamento_sucursal" id="departamento_sucursal_'+contador_sucursales+'" class="custom-select departamento_sucursal_'+contador_sucursales+'"><option></option></select>',
                Nombre_municipio: '<select  name="ciudad_sucursal" id="ciudad_sucursal_'+contador_sucursales+'" class="custom-select ciudad_sucursal_'+contador_sucursales+'" disabled><option></option></select>',
                string_html: '<div class="centrar"><a href="javascript:void(0);" id="btn_remover_sucursal_fila" class="text-info" data-fila="fila_'+contador_sucursales+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                fila_:'fila_'+contador_sucursales,
            };
        }else{
            var nueva_fila_sucursales = [
                '<input type="text" class="form-control" name="nombre_sucursal" id="nombre_sucursal_'+contador_sucursales+'">',
                '<input type="text" class="form-control" name="nombre_gerente_sucursal" id="nombre_gerente_sucursal_'+contador_sucursales+'">',
                '<input type="number" class="form-control soloNumeros" name="telefono_principal_sucursal" id="telefono_principal_sucursal_'+contador_sucursales+'">',
                '<input type="text" class="form-control" name="otro_telefono_sucursal" id="otro_telefono_sucursal_'+contador_sucursales+'">',
                '<input type="email" class="form-control" name="email_principal_sucursal" id="email_principal_sucursal_'+contador_sucursales+'">',
                '<input type="text" class="form-control" name="otro_email_sucursal" id="otro_email_sucursal_'+contador_sucursales+'">',
                '<input type="number" class="form-control soloNumeros" name="linea_atencion_principal_sucursal" id="linea_atencion_principal_sucursal_'+contador_sucursales+'">',
                '<input type="text" class="form-control" name="otro_linea_atencion_sucursal" id="otro_linea_atencion_sucursal_'+contador_sucursales+'">',
                '<input type="text" class="form-control" name="direccion_sucursal" id="direccion_sucursal_'+contador_sucursales+'">',
                '<select  name="departamento_sucursal" id="departamento_sucursal_'+contador_sucursales+'" class="custom-select departamento_sucursal_'+contador_sucursales+'"><option></option></select>',
                '<select  name="ciudad_sucursal" id="ciudad_sucursal_'+contador_sucursales+'" class="custom-select ciudad_sucursal_'+contador_sucursales+'" disabled><option></option></select>',
                '<div class="centrar"><a href="javascript:void(0);" id="btn_remover_sucursal_fila" class="text-info" data-fila="fila_'+contador_sucursales+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_sucursales,
            ];
        }


        var agregar_sucursal_fila = sucursales.row.add(nueva_fila_sucursales).draw().node();
        $(agregar_sucursal_fila).addClass('fila_'+contador_sucursales);
        $(agregar_sucursal_fila).attr("id", 'fila_'+contador_sucursales);

        // Esta función realiza los controles de cada elemento por fila (está dentro del archivo clientes.js)
        funciones_elementos_fila_sucursales(contador_sucursales);
    });

    // Remover filas nuevas insertadas
    $(document).on('click', '#btn_remover_sucursal_fila', function(){
        var nombre_sucursal_fila = $(this).data("fila");
        sucursales.row("."+nombre_sucursal_fila).remove().draw();
    });
    
    // Remover filas visuales que se traen de la bd
    $(document).on('click', "a[id^='btn_remover_fila_sucursal_']", function(){
        var nombre_sucur_fila = $(this).data("clase_fila");
        sucursales.row("."+nombre_sucur_fila).remove().draw();

        var datos_fila_quitar_sucursal = {
            '_token': token,
            'fila':$(this).data("id_fila_quitar"),
            'id_cliente': $('#id_cliente_editar').val()
        };

        $.ajax({
            type:'POST',
            url:'/eliminarSucursalCliente',
            data: datos_fila_quitar_sucursal,
            success:function(response){
                if (response.parametro == "fila_sucursal_eliminada") {
                    $('#resultado').empty();
                    $('#resultado').removeClass('d-none');
                    $('#resultado').addClass('alert-success');
                    $('#resultado').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado').addClass('d-none');
                        $('#resultado').removeClass('alert-success');
                        $('#resultado').empty();
                    }, 3000);
                }
            }
        });  

    });

    // FUNCIÓN PARA LLENAR LOS DATOS DEL DATATABLE ANS
    var ans = "";
    function llenar_tabla_ans(response){
        /* DATATABLE ANS */
        ans = $('#ans').DataTable({
            "responsive": true,
            "info": false,
            "searching": false,
            "ordering": false,
            "scrollCollapse": true,
            "paging": false,
            "destroy": true,
            "data": response,
            createdRow: function(row, data, index) {
                $(row).addClass("fila_ans_" + data.Id_ans);
                $(row).attr("id", "datos_ans");
            },
            "columns":[
                {data:"Nombre"},
                {data:"Descripcion"},
                {data:"Valor"},
                {data:"Nombre_unidad"},
                {data:"string_html"},
            ],
            "language":{
                "emptyTable": "No se encontró información"
            }
        });

        autoAdjustColumns(ans);
    }

    // FUNCIONALIDADES TABLA ANS
    var contador_ans = 0;
    $("#btn_agregar_ans_fila").click(function(){
        contador_ans = contador_ans + 1;

        if (no_datos_ans == 0) {
            var nueva_fila_ans = {
                Nombre: '<input type="text" class="form-control" name="nombre_ans" id="nombre_ans_'+contador_ans+'">',
                Descripcion:'<textarea id="descripcion_ans_'+contador_ans+'" class="form-control" name="descripcion_ans" cols="90" rows="3"></textarea>',
                Valor:'<input type="text" class="form-control" name="valor_ans" id="valor_ans_'+contador_ans+'">',
                Nombre_unidad:'<select  name="unidad_ans" id="unidad_ans_'+contador_ans+'" class="custom-select unidad_ans_'+contador_ans+'"><option></option></select>',
                string_html:'<div class="centrar"><a href="javascript:void(0);" id="btn_remover_ans_fila" class="text-info" data-fila="fila_'+contador_ans+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                fila_:'fila_'+contador_ans
            };
            
        } else {
            var nueva_fila_ans = [
                '<input type="text" class="form-control" name="nombre_ans" id="nombre_ans_'+contador_ans+'">',
                '<textarea id="descripcion_ans_'+contador_ans+'" class="form-control" name="descripcion_ans" cols="90" rows="3"></textarea>',
                '<input type="text" class="form-control" name="valor_ans" id="valor_ans_'+contador_ans+'">',
                '<select  name="unidad_ans" id="unidad_ans_'+contador_ans+'" class="custom-select unidad_ans_'+contador_ans+'"><option></option></select>',
                '<div class="centrar"><a href="javascript:void(0);" id="btn_remover_ans_fila" class="text-info" data-fila="fila_'+contador_ans+'"><i class="fas fa-minus-circle" style="font-size:24px;"></i></a></div>',
                'fila_'+contador_ans
            ];
        }

        var agregar_ans_fila = ans.row.add(nueva_fila_ans).draw().node();
        $(agregar_ans_fila).addClass('fila_'+contador_ans);
        $(agregar_ans_fila).attr("id", 'fila_'+contador_ans);

        // Esta función realiza los controles de cada elemento por fila (está dentro del archivo clientes.js)
        funciones_elementos_fila_ans(contador_ans);

    });

    // Remover filas nuevas insertadas
    $(document).on('click', '#btn_remover_ans_fila', function(){
        var nombre_ans_fila = $(this).data("fila");
        ans.row("."+nombre_ans_fila).remove().draw();
    });

    // Remover filas visuales que se traen de la bd
    $(document).on('click', "a[id^='btn_remover_fila_ans_']", function(){
        var nombre_ans_fila = $(this).data("clase_fila");
        ans.row("."+nombre_ans_fila).remove().draw();

        var datos_fila_quitar_ans = {
            '_token': token,
            'fila':$(this).data("id_fila_quitar"),
            'id_cliente': $('#id_cliente_editar').val()
        };

        $.ajax({
            type:'POST',
            url:'/eliminarAnsCliente',
            data: datos_fila_quitar_ans,
            success:function(response){
                if (response.parametro == "fila_ans_eliminada") {
                    $('#resultado_ans').empty();
                    $('#resultado_ans').removeClass('d-none');
                    $('#resultado_ans').addClass('alert-success');
                    $('#resultado_ans').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_ans').addClass('d-none');
                        $('#resultado_ans').removeClass('alert-success');
                        $('#resultado_ans').empty();
                    }, 3000);
                }
            }
        });  

    });


    // FUNCIÓN PARA LLENAR LOS DATOS DEL DATATABLE FIRMAS CLIENTE
    var firmas_cliente = "";
    function llenar_tabla_firmas_cliente(response){
        /* DATATABLE FIRMAS CLIENTE */
        firmas_cliente = $('#listado_firmas_cliente').DataTable({
            "responsive": true,
            "info": false,
            "searching": false,
            "ordering": false,
            "scrollCollapse": true,
            "paging": false,
            "destroy": true,
            "data": response,
            createdRow: function(row, data, index) {
                $(row).addClass("fila_firma_cliente_" + data.Id_firma);
                // $(row).attr("id", "datos_ans");
            },
            "columns":[
                {data:"Nombre_firmante"},
                {data:"Cargo_firmante"},
                {data:"Firma"},
                {data:"string_html"},
            ],
            "language":{
                "emptyTable": "No se encontró información"
            }
        });

        autoAdjustColumns(firmas_cliente);
    };

    // Remover filas visuales que se traen de la bd
    $(document).on('click', "a[id^='btn_remover_fila_firma_cliente_']", function(){
        var nombre_firma_fila = $(this).data("clase_fila");
        firmas_cliente.row("."+nombre_firma_fila).remove().draw();

        var datos_fila_quitar_firma = {
            '_token': token,
            'fila':$(this).data("id_fila_quitar"),
            'id_cliente': $('#id_cliente_editar').val()
        };

        $.ajax({
            type:'POST',
            url:'/eliminarFirmaCliente',
            data: datos_fila_quitar_firma,
            success:function(response){
                if (response.parametro == "firma_eliminada") {
                    $('#resultado_firma_cliente').empty();
                    $('#resultado_firma_cliente').removeClass('d-none');
                    $('#resultado_firma_cliente').addClass('alert-success');
                    $('#resultado_firma_cliente').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_firma_cliente').addClass('d-none');
                        $('#resultado_firma_cliente').removeClass('alert-success');
                        $('#resultado_firma_cliente').empty();
                    }, 3000);
                }
            }
        });  

    });

    // Mostrar el Formulario para editar la firma del cliente
    $(document).on('click', "a[id^='btn_editar_firma_cliente_']", function(){
        
        $("#contenedor_html_firma_cliente").empty();
        
        var fila_editar = $(this).data("id_fila_editar");
        
        // Edición Firma Cliente
        let datos_edicion_firma_cliente = {
            '_token': token,
            'parametro': "traer_firma_editar_cliente",
            'fila_editar': fila_editar,
            'id_cliente_editar': $('#id_cliente_editar').val(),
        };

        $.ajax({
            type:'POST',
            url: $('#traer_datos_cliente').val(),
            data: datos_edicion_firma_cliente,
            success:function(data){
                var container = $("#contenedor_html_firma_cliente");
                for (let i = 0; i < data.length; i++) {
                    container.html(data[i].Firma);

                    var images = container.find('img');
                    for (var a= 0; a < images.length; a++) {
                        images.attr('src', data[i].Url);
                    }
                    data[i].Firma = $("#contenedor_html_firma_cliente").html();

                    $("#agregar_firma_cliente").removeClass('d-none');
                    $("#Id_firma_cliente_cliente").val(data[i].Id_firma);
                    $("#nombre_del_firmante_cliente").val(data[i].Nombre_firmante).prop("required", true);
                    $("#cargo_del_firmante_cliente").val(data[i].Cargo_firmante).prop("required", true);
                    $("#firma_del_cliente").prop("required", true);
                    $("#firma_del_cliente").summernote('code', data[i].Firma);
                }
            }
        });
    });

    // Ocultar el formulario en caso de que no desee editar la firma del cliente
    $("#no_editar_firma_cliente").click(function(){
        $("#agregar_firma_cliente").addClass('d-none');
        $("#nombre_del_firmante_cliente").val('').prop("required", false);
        $("#cargo_del_firmante_cliente").val('').prop("required", false);
        $("#firma_del_cliente").summernote('code', '<p><br></p>');
    });

    // Mostrar el formulario de firmas para insertar más firmas de cliente
    $("#btn_abrir_form_firma_cliente").click(function(){
        $('#Id_firma_cliente_cliente').val('');
        $("#agregar_firma_cliente").removeClass('d-none');
        $("#nombre_del_firmante_cliente").prop("required", true);
        $("#cargo_del_firmante_cliente").prop("required", true);
        $("#firma_del_cliente").prop("required", true);
    });

    // Guardar una nueva firma de cliente
    $("#guardar_nueva_firma_cliente").click(function(){
        
        
        if ($("#nombre_del_firmante_cliente").val() != '' 
            && $("#cargo_del_firmante_cliente").val() != '' 
            && $('#firma_del_cliente').summernote('code') != '<p><br></p>'
        ) {

                // Creación de array con los datos de la firma cliente
                var guardar_datos_firmas_cliente = [];
                var datos_finales_firmas_cliente = [];

                guardar_datos_firmas_cliente.push($("#nombre_del_firmante_cliente").val());
                guardar_datos_firmas_cliente.push($("#cargo_del_firmante_cliente").val());
                guardar_datos_firmas_cliente.push($('#firma_del_cliente').summernote('code'));
                datos_finales_firmas_cliente.push(guardar_datos_firmas_cliente);
                guardar_datos_firmas_cliente = [];
        
                var url_imagenes = [];
                var extension_imagenes = [];
                $.each(datos_finales_firmas_cliente, function (index, subArray) {
                    $.each(subArray, function (subIndex, value) {
        
                        var container = document.createElement('div');
                        container.innerHTML = value;
        
                        var images = container.getElementsByTagName('img');
                        for (var i = 0; i < images.length; i++) {
                            var imageUrl = images[i].currentSrc;
                            var extension = images[i].dataset.filename;
                            url_imagenes.push(imageUrl);
                            extension_imagenes.push(extension.split('.').pop().toLowerCase());
                        }
                    });
                });
        
                let token = $("input[name='_token']").val();
                var enviar_info_firmas_cliente = {
                    '_token': token,
                    'Id_cliente': $("#id_cliente_editar").val(),
                    'Id_firma_editar': $("#Id_firma_cliente_cliente").val(),
                    'Firmas': datos_finales_firmas_cliente,
                    'Urls': url_imagenes,
                    'Extensiones_firmas': extension_imagenes,
                };

                $.ajax({
                    type: 'POST',
                    url: '/GuardarActualizarFirmasCliente',
                    data: enviar_info_firmas_cliente,
                    success: function(response){
                        if (response.parametro == "gestion_firma") {
                            $('#resultado_nueva_firma_cliente').empty();
                            $('#resultado_nueva_firma_cliente').removeClass('d-none');
                            $('#resultado_nueva_firma_cliente').addClass('alert-success');
                            $('#resultado_nueva_firma_cliente').append('<strong>'+response.mensaje+'</strong>');
                            
                            setTimeout(() => {
                                $('#resultado_nueva_firma_cliente').addClass('d-none');
                                $('#resultado_nueva_firma_cliente').removeClass('alert-success');
                                $('#resultado_nueva_firma_cliente').empty();
                            }, 3000);
                        }

                        $("#Id_firma_cliente_cliente").val('');
                        $("#nombre_del_firmante_cliente").val('');
                        $("#cargo_del_firmante_cliente").val('');
                        $("#firma_del_cliente").summernote('code', '<p><br></p>');
                    }
                });
        }else{
            $('#resultado_nueva_firma_cliente').empty();
            $('#resultado_nueva_firma_cliente').removeClass('d-none');
            $('#resultado_nueva_firma_cliente').addClass('alert-danger');
            $('#resultado_nueva_firma_cliente').append('<strong>Debe diligenciar el formulario completo.</strong>');
            
            setTimeout(() => {
                $('#resultado_nueva_firma_cliente').addClass('d-none');
                $('#resultado_nueva_firma_cliente').removeClass('alert-danger');
                $('#resultado_nueva_firma_cliente').empty();
            }, 3000);
        }
        
    });

    // FUNCIÓN PARA LLENAR LOS DATOS DEL DATATABLE FIRMAS PROVEEDOR
    var firmas_proveedor = "";
    function llenar_tabla_firmas_proveedor(response){
        /* DATATABLE FIRMAS PROVEEDOR */
        firmas_proveedor = $('#listado_firmas_proveedor').DataTable({
            "responsive": true,
            "info": false,
            "searching": false,
            "ordering": false,
            "scrollCollapse": true,
            "paging": false,
            "destroy": true,
            "data": response,
            createdRow: function(row, data, index) {
                $(row).addClass("fila_firma_proveedor_" + data.Id_firma);
                // $(row).attr("id", "datos_ans");
            },
            "columns":[
                {data:"Nombre_firmante"},
                {data:"Cargo_firmante"},
                {data:"Firma"},
                {data:"string_html"},
            ],
            "language":{
                "emptyTable": "No se encontró información"
            }
        });

        autoAdjustColumns(firmas_proveedor);
    };

    // Remover filas visuales que se traen de la bd
    $(document).on('click', "a[id^='btn_remover_fila_firma_proveedor_']", function(){
        var nombre_firma_fila = $(this).data("clase_fila");
        firmas_proveedor.row("."+nombre_firma_fila).remove().draw();

        var datos_fila_quitar_firma = {
            '_token': token,
            'fila':$(this).data("id_fila_quitar"),
            'id_cliente': $('#id_cliente_editar').val()
        };

        $.ajax({
            type:'POST',
            url:'/eliminarFirmaProveedor',
            data: datos_fila_quitar_firma,
            success:function(response){
                if (response.parametro == "firma_eliminada") {
                    $('#resultado_firma_proveedor').empty();
                    $('#resultado_firma_proveedor').removeClass('d-none');
                    $('#resultado_firma_proveedor').addClass('alert-success');
                    $('#resultado_firma_proveedor').append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $('#resultado_firma_proveedor').addClass('d-none');
                        $('#resultado_firma_proveedor').removeClass('alert-success');
                        $('#resultado_firma_proveedor').empty();
                    }, 3000);
                }
            }
        });  
    });

    // Mostrar el Formulario para editar la firma del proveedor
    $(document).on('click', "a[id^='btn_editar_firma_proveedor_']", function(){
            
        $("#contenedor_html_firma_proveedor").empty();
        
        var fila_editar = $(this).data("id_fila_editar");

        // Edición Firma Cliente
        let datos_edicion_firma_proveedor = {
            '_token': token,
            'parametro': "traer_firma_editar_proveedor",
            'fila_editar': fila_editar,
            'id_cliente_editar': $('#id_cliente_editar').val(),
        };

        $.ajax({
            type:'POST',
            url: $('#traer_datos_cliente').val(),
            data: datos_edicion_firma_proveedor,
            success:function(data){
                var container = $("#contenedor_html_firma_proveedor");
                for (let i = 0; i < data.length; i++) {
                    container.html(data[i].Firma);

                    var images = container.find('img');
                    for (var a= 0; a < images.length; a++) {
                        images.attr('src', data[i].Url);
                    }
                    data[i].Firma = $("#contenedor_html_firma_proveedor").html();

                    $("#agregar_firma_proveedor").removeClass('d-none');
                    $("#Id_firma_cliente_proveedor").val(data[i].Id_firma);
                    $("#nombre_del_firmante_proveedor").val(data[i].Nombre_firmante).prop("required", true);
                    $("#cargo_del_firmante_proveedor").val(data[i].Cargo_firmante).prop("required", true);
                    $("#firma_del_proveedor").prop("required", true);
                    $("#firma_del_proveedor").summernote('code', data[i].Firma);
                }
            }
        });
    });

    // Ocultar el formulario en caso de que no desee editar la firma del proveedor
    $("#no_editar_firma_proveedor").click(function(){
        $("#agregar_firma_proveedor").addClass('d-none');
        $("#nombre_del_firmante_proveedor").val('').prop("required", false);
        $("#cargo_del_firmante_proveedor").val('').prop("required", false);
        $("#firma_del_proveedor").summernote('code', '<p><br></p>');
    });

    // Mostrar el formulario de firmas para insertar más firmas de proveedor
    $("#btn_abrir_form_firma_proveedor").click(function(){
        $('#Id_firma_cliente_proveedor').val('');
        $("#agregar_firma_proveedor").removeClass('d-none');
        $("#nombre_del_firmante_proveedor").prop("required", true);
        $("#cargo_del_firmante_proveedor").prop("required", true);
        $("#firma_del_proveedor").prop("required", true);
    });

    // Guardar una nueva firma de proveedor
    $("#guardar_nueva_firma_proveedor").click(function(){
            
        if ($("#nombre_del_firmante_proveedor").val() != '' 
            && $("#cargo_del_firmante_proveedor").val() != '' 
            && $('#firma_del_proveedor').summernote('code') != '<p><br></p>'
        ) {

                // Creación de array con los datos de la firma proveedor
                var guardar_datos_firmas_proveedor = [];
                var datos_finales_firmas_proveedor = [];

                guardar_datos_firmas_proveedor.push($("#nombre_del_firmante_proveedor").val());
                guardar_datos_firmas_proveedor.push($("#cargo_del_firmante_proveedor").val());
                guardar_datos_firmas_proveedor.push($('#firma_del_proveedor').summernote('code'));
                datos_finales_firmas_proveedor.push(guardar_datos_firmas_proveedor);
                guardar_datos_firmas_proveedor = [];
        
                var url_imagenes = [];
                var extension_imagenes = [];
                $.each(datos_finales_firmas_proveedor, function (index, subArray) {
                    $.each(subArray, function (subIndex, value) {
        
                        var container = document.createElement('div');
                        container.innerHTML = value;
        
                        var images = container.getElementsByTagName('img');
                        for (var i = 0; i < images.length; i++) {
                            var imageUrl = images[i].currentSrc;
                            var extension = images[i].dataset.filename;
                            url_imagenes.push(imageUrl);
                            extension_imagenes.push(extension.split('.').pop().toLowerCase());
                        }
                    });
                });
        
                let token = $("input[name='_token']").val();
                var enviar_info_firmas_proveedor = {
                    '_token': token,
                    'Id_cliente': $("#id_cliente_editar").val(),
                    'Id_firma_editar': $("#Id_firma_cliente_proveedor").val(),
                    'Firmas_proveedor': datos_finales_firmas_proveedor,
                    'Urls_proveedor': url_imagenes,
                    'Extensiones_firmas_proveedor': extension_imagenes,
                };
        
                $.ajax({
                    type: 'POST',
                    url: '/GuardarActualizarFirmasProveedor',
                    data: enviar_info_firmas_proveedor,
                    success: function(response){
                        if (response.parametro == "gestion_firma") {
                            $('#resultado_nueva_firma_proveedor').empty();
                            $('#resultado_nueva_firma_proveedor').removeClass('d-none');
                            $('#resultado_nueva_firma_proveedor').addClass('alert-success');
                            $('#resultado_nueva_firma_proveedor').append('<strong>'+response.mensaje+'</strong>');
                            
                            setTimeout(() => {
                                $('#resultado_nueva_firma_proveedor').addClass('d-none');
                                $('#resultado_nueva_firma_proveedor').removeClass('alert-success');
                                $('#resultado_nueva_firma_proveedor').empty();
                            }, 3000);
                        }

                        $("#Id_firma_cliente_proveedor").val('');
                        $("#nombre_del_firmante_proveedor").val('');
                        $("#cargo_del_firmante_proveedor").val('');
                        $("#firma_del_proveedor").summernote('code', '<p><br></p>');
                    }
                });
        }else{
            $('#resultado_nueva_firma_proveedor').empty();
            $('#resultado_nueva_firma_proveedor').removeClass('d-none');
            $('#resultado_nueva_firma_proveedor').addClass('alert-danger');
            $('#resultado_nueva_firma_proveedor').append('<strong>Debe diligenciar el formulario completo.</strong>');
            
            setTimeout(() => {
                $('#resultado_nueva_firma_proveedor').addClass('d-none');
                $('#resultado_nueva_firma_proveedor').removeClass('alert-danger');
                $('#resultado_nueva_firma_proveedor').empty();
            }, 3000);
        }
        
    });

    // VALIDACIÓN CHECKBOXES TABLA SERVICIOS CONTRATADOS

    /* PROCESO Origen ATEL */

    // Servicio Determinación de Origen (DTO)
    $("#checkbox_servicio_dto").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_dto").removeClass('d-none');
            // $("#nro_consecutivo_servicio_dto").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_dto").val() == '') {
            //     $("#nro_consecutivo_servicio_dto").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_dto").addClass('d-none');
            $("#valor_tarifa_servicio_dto").val('');
            // $("#nro_consecutivo_servicio_dto").addClass('d-none');

            // if ($("#nro_consecutivo_servicio_dto").val() == '') {
            //     $("#nro_consecutivo_servicio_dto").val('');
            //     $("#nro_consecutivo_servicio_dto").prop("readonly", false);
            // }
        }
    });

    // Servicio Adición DX
    $("#checkbox_servicio_adicion_dx").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_adicion_dx").removeClass('d-none');
            // $("#nro_consecutivo_servicio_adicion_dx").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_adicion_dx").val() == '') {
            //     $("#nro_consecutivo_servicio_adicion_dx").prop("readonly", false);
            // }

        }else{
            $("#valor_tarifa_servicio_adicion_dx").addClass('d-none');
            $("#valor_tarifa_servicio_adicion_dx").val('');
            
            // $("#nro_consecutivo_servicio_adicion_dx").addClass('d-none');

            // if ($("#nro_consecutivo_servicio_adicion_dx").val() == '') {
            //     $("#nro_consecutivo_servicio_adicion_dx").val('');
            //     $("#nro_consecutivo_servicio_adicion_dx").prop("readonly", false);
            // }
        }
    });

    // Servicio Pronunciamientos
    $("#checkbox_servicio_pronunciamiento").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_pronunciamiento").removeClass('d-none');
            // $("#nro_consecutivo_servicio_pronunciamiento").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_pronunciamiento").val() == '') {
            //     $("#nro_consecutivo_servicio_pronunciamiento").prop("readonly", false);
            // }

        }else{
            $("#valor_tarifa_servicio_pronunciamiento").addClass('d-none');
            $("#valor_tarifa_servicio_pronunciamiento").val('');
            
            // $("#nro_consecutivo_servicio_pronunciamiento").addClass('d-none');
            
            // if ($("#nro_consecutivo_servicio_pronunciamiento").val() == '') {
            //     $("#nro_consecutivo_servicio_pronunciamiento").val('');
            //     $("#nro_consecutivo_servicio_pronunciamiento").prop("readonly", false);
            // }
        }
    });

    /* PROCESO Calificación PCL */

    // Servicio Calificación Técnica
    $("#checkbox_servicio_calificacion_tecnica").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_calificacion_tecnica").removeClass('d-none');
            // $("#nro_consecutivo_servicio_calificacion_tecnica").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_calificacion_tecnica").val() == '') {
            //     $("#nro_consecutivo_servicio_calificacion_tecnica").prop("readonly", false);
            // }

        }else{
            $("#valor_tarifa_servicio_calificacion_tecnica").addClass('d-none');
            $("#valor_tarifa_servicio_calificacion_tecnica").val('');
            
            // $("#nro_consecutivo_servicio_calificacion_tecnica").addClass('d-none');

            // if ($("#nro_consecutivo_servicio_calificacion_tecnica").val() == '') {
            //     $("#nro_consecutivo_servicio_calificacion_tecnica").val('');
            //     $("#nro_consecutivo_servicio_calificacion_tecnica").prop("readonly", false);
            // }

        }
    });

    // Servicio Recalificación
    $("#checkbox_servicio_recalificacion").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_recalificacion").removeClass('d-none');
            // $("#nro_consecutivo_servicio_recalificacion").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_recalificacion").val() == '') {
            //     $("#nro_consecutivo_servicio_recalificacion").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_recalificacion").addClass('d-none');
            $("#valor_tarifa_servicio_recalificacion").val('');
            
            
            // $("#nro_consecutivo_servicio_recalificacion").addClass('d-none');
            // if ($("#nro_consecutivo_servicio_recalificacion").val() == '') {
            //     $("#nro_consecutivo_servicio_recalificacion").val('');
            //     $("#nro_consecutivo_servicio_recalificacion").prop("readonly", false);
            // }
        }
    });

    // Servicio Revisión Pensión
    $("#checkbox_servicio_revision_pension").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_revision_pension").removeClass('d-none');
            // $("#nro_consecutivo_servicio_revision_pension").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_revision_pension").val() == '') {
            //     $("#nro_consecutivo_servicio_revision_pension").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_revision_pension").addClass('d-none');
            $("#valor_tarifa_servicio_revision_pension").val('');
            
            // $("#nro_consecutivo_servicio_revision_pension").addClass('d-none');
            
            // if ($("#nro_consecutivo_servicio_revision_pension").val() == '') {
            //     $("#nro_consecutivo_servicio_revision_pension").val('');
            //     $("#nro_consecutivo_servicio_revision_pension").prop("readonly", false);
            // }
        }
    });

    // Servicio Pronunciamientos
    $("#checkbox_servicio_pronunciamiento_pcl").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_pronunciamiento_pcl").removeClass('d-none');
            // $("#nro_consecutivo_servicio_pronunciamiento_pcl").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_pronunciamiento_pcl").val() == '') {
            //     $("#nro_consecutivo_servicio_pronunciamiento_pcl").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_pronunciamiento_pcl").addClass('d-none');
            // $("#nro_consecutivo_servicio_pronunciamiento_pcl").addClass('d-none');

            $("#valor_tarifa_servicio_pronunciamiento_pcl").val('');

            // if ($("#nro_consecutivo_servicio_pronunciamiento_pcl").val() == '') {
            //     $("#nro_consecutivo_servicio_pronunciamiento_pcl").val('');
            //     $("#nro_consecutivo_servicio_pronunciamiento_pcl").prop("readonly", false);
            // }
        }
    });

    /* PROCESO Juntas */

    // Servicio Controversia Origen
    $("#checkbox_servicio_controversia_origen").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_controversia_origen").removeClass('d-none');
            // $("#nro_consecutivo_servicio_controversia_origen").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_controversia_origen").val() == '') {
            //     $("#nro_consecutivo_servicio_controversia_origen").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_controversia_origen").addClass('d-none');
            $("#valor_tarifa_servicio_controversia_origen").val('');
            
            // $("#nro_consecutivo_servicio_controversia_origen").addClass('d-none');

            // if ($("#nro_consecutivo_servicio_controversia_origen").val() == '') {
            //     $("#nro_consecutivo_servicio_controversia_origen").val('');
            //     $("#nro_consecutivo_servicio_controversia_origen").prop("readonly", false);
            // }
        }
    });

    // Servicio Controversia Pcl
    $("#checkbox_servicio_controversia_pcl").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_controversia_pcl").removeClass('d-none');
            // $("#nro_consecutivo_servicio_controversia_pcl").removeClass('d-none');
    
            // if ($("#nro_consecutivo_servicio_controversia_pcl").val() == '') {
            //     $("#nro_consecutivo_servicio_controversia_pcl").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_controversia_pcl").addClass('d-none');
            $("#valor_tarifa_servicio_controversia_pcl").val('');
            
            // $("#nro_consecutivo_servicio_controversia_pcl").addClass('d-none');
    
            // if ($("#nro_consecutivo_servicio_controversia_pcl").val() == '') {
            //     $("#nro_consecutivo_servicio_controversia_pcl").val('');
            //     $("#nro_consecutivo_servicio_controversia_pcl").prop("readonly", false);
            // }
        }
    });

    /* PROCESO Otros */

    // Servicio PQRD
    $("#checkbox_servicio_pqrd").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_pqrd").removeClass('d-none');
            // $("#nro_consecutivo_servicio_pqrd").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_pqrd").val() == '') {
            //     $("#nro_consecutivo_servicio_pqrd").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_pqrd").addClass('d-none');
            // $("#nro_consecutivo_servicio_pqrd").addClass('d-none');

            $("#valor_tarifa_servicio_pqrd").val('');

            // if ($("#nro_consecutivo_servicio_pqrd").val() == '') {
            //     $("#nro_consecutivo_servicio_pqrd").val('');
            //     $("#nro_consecutivo_servicio_pqrd").prop("readonly", false);
            // }
        }
    });

    // Servicio Tutelas
    $("#checkbox_servicio_tutelas").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_tutelas").removeClass('d-none');
            // $("#nro_consecutivo_servicio_tutelas").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_tutelas").val() == '') {
            //     $("#nro_consecutivo_servicio_tutelas").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_tutelas").addClass('d-none');
            // $("#nro_consecutivo_servicio_tutelas").addClass('d-none');

            $("#valor_tarifa_servicio_tutelas").val('');

            // if ($("#nro_consecutivo_servicio_tutelas").val() == '') {
            //     $("#nro_consecutivo_servicio_tutelas").val('');
            //     $("#nro_consecutivo_servicio_tutelas").prop("readonly", false);
            // }
        }
    });
    
    // Servicio Gestión Integral del Siniestro (GIS)
    $("#checkbox_servicio_gis").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_gis").removeClass('d-none');
            // $("#nro_consecutivo_servicio_gis").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_gis").val() == '') {
            //     $("#nro_consecutivo_servicio_gis").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_gis").addClass('d-none');
            // $("#nro_consecutivo_servicio_gis").addClass('d-none');

            $("#valor_tarifa_servicio_gis").val('');

            // if ($("#nro_consecutivo_servicio_gis").val() == '') {
            //     $("#nro_consecutivo_servicio_gis").val('');
            //     $("#nro_consecutivo_servicio_gis").prop("readonly", false);
            // }
        }
    });

    // Servicio Auditorías
    $("#checkbox_servicio_auditorias").click(function(){
        if($(this).is(":checked")){
            $("#valor_tarifa_servicio_auditorias").removeClass('d-none');
            // $("#nro_consecutivo_servicio_auditorias").removeClass('d-none');

            // if ($("#nro_consecutivo_servicio_auditorias").val() == '') {
            //     $("#nro_consecutivo_servicio_auditorias").prop("readonly", false);
            // }
        }else{
            $("#valor_tarifa_servicio_auditorias").addClass('d-none');
            // $("#nro_consecutivo_servicio_auditorias").addClass('d-none');

            $("#valor_tarifa_servicio_auditorias").val('');

            // if ($("#nro_consecutivo_servicio_auditorias").val() == '') {
            //     $("#nro_consecutivo_servicio_auditorias").val('');
            //     $("#nro_consecutivo_servicio_auditorias").prop("readonly", false);
            // }
        }
    });


    // Previsualización de la imagen
    $("#logo_cliente").change(function(){
        var selectedFile = $(this)[0].files[0];

        if (selectedFile) {
            var fileName = selectedFile.name;
            var fileExtension = fileName.split('.').pop().toLowerCase();
            $("#nombre_ext_imagen").val(fileExtension);

            if (fileExtension === 'png' || fileExtension === 'jpg') {
                $(".mensaje_extension_logo").addClass('d-none');
                visualizar_imagen(this,'#img_codificada','#previewImage');
            } else {
                $(".mensaje_extension_logo").removeClass('d-none');
                setTimeout(() => {
                    $(".mensaje_extension_logo").addClass('d-none');
                }, 5000);
                $(this).val('');
            }
        }
    });
    //Previsualización del footer
    $("#logo_footer").change(function(){
        var selectedFile = $(this)[0].files[0];

        if (selectedFile) {
            var fileName = selectedFile.name;
            var fileExtension = fileName.split('.').pop().toLowerCase();
            $("#nombre_ext_footer").val(fileExtension);

            if (fileExtension === 'png' || fileExtension === 'jpg') {
                $(".mensaje_extension_footer").addClass('d-none');
                visualizar_imagen(this,'#footer_codificado','#footerContainer');
            } else {
                $(".mensaje_extension_footer").removeClass('d-none');
                setTimeout(() => {
                    $(".mensaje_extension_footer").addClass('d-none');
                }, 5000);
                $(this).val('');
            }
        }
    });

    function visualizar_imagen(input,imagen,container) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(container).attr('src', e.target.result);
                $(imagen).val(e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Envío de información para Actualizar el cliente
    $(document).on('submit', "form[id^='form_actualizar_cliente_']", function(e){
        e.preventDefault();
        
        // Mostramos el spiner de espera y ocultamos el boton de guardar
        $("#mostrar_barra_actualizar_cliente").removeClass('d-none');
        $("#contenedor_btn_actualizar_cliente").addClass('d-none');

        let token = $("input[name='_token']").val();

        // Creación de array con los datos de la tabla dinámica Sucursales
        var guardar_datos_sucursales = [];
        var datos_finales_sucursales = [];
        
        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO 
        // (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#sucursales tbody tr').each(function (index) {
            if ($(this).attr('id') !== "datos_sucursales") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        guardar_datos_sucursales.push($('#'+nombres_ids).val());                        
                    }
                    if((index2+1) % 11 === 0){
                        datos_finales_sucursales.push(guardar_datos_sucursales);
                        guardar_datos_sucursales = [];
                    }
                });
            }
        });

        // Recolección de los Servicios Contratados con su correspondiente valor de tarifa y nro consecutivo dictamen
        var array_servicios_contratados = [];
        var listado_nombre_servicios = [];
        $('input[type="checkbox"]').each(function() {
            var id_checkboxes_servicios_contratados = $(this).attr('id');   
            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_dto') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 1;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_dto").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_dto").val());

                    array_servicios_contratados.push(listado_nombre_servicios);
                    listado_nombre_servicios = [];
                }
            };

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_adicion_dx') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 1;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_adicion_dx").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_adicion_dx").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_pronunciamiento') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 1;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_pronunciamiento").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_pronunciamiento").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_calificacion_tecnica') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 2;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_calificacion_tecnica").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_calificacion_tecnica").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_recalificacion') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 2;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_recalificacion").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_recalificacion").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_revision_pension') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 2;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_revision_pension").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_revision_pension").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_pronunciamiento_pcl') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 2;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_pronunciamiento_pcl").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_pronunciamiento_pcl").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_controversia_origen') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 3;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_controversia_origen").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_controversia_origen").val());
                    array_servicios_contratados.push(listado_nombre_servicios);

                    listado_nombre_servicios = [];
                }
            }

            if (id_checkboxes_servicios_contratados === 'checkbox_servicio_controversia_pcl') {                
                if ($(this).is(':checked')) {                
                    var valor_servicio = $(this).val();
                    var valor_proceso = 3;
                    listado_nombre_servicios.push(valor_proceso);
                    listado_nombre_servicios.push(valor_servicio);
                    listado_nombre_servicios.push($("#valor_tarifa_servicio_controversia_pcl").val());
                    // listado_nombre_servicios.push($("#nro_consecutivo_servicio_controversia_pcl").val());
                    array_servicios_contratados.push(listado_nombre_servicios);
            
                    listado_nombre_servicios = [];
                }
            }
        });

        // Creación de array con los datos de la tabla dinámica ANS
        var guardar_datos_ans = [];
        var datos_finales_ans = [];

        // RECORREMOS LOS TD DE LA TABLA PARA EXTRAER LOS DATOS E INSERTARLOS EN UN ARREGLO 
        // (LA INSERCIÓN LA HACE POR CADA FILA, POR ENDE, ES UN ARRAY MULTIDIMENSIONAL)
        $('#ans tbody tr').each(function (index) {
            if ($(this).attr('id') !== "datos_ans") {
                $(this).children("td").each(function (index2) {
                    var nombres_ids = $(this).find('*').attr("id");
                    if (nombres_ids != undefined) {
                        guardar_datos_ans.push($('#'+nombres_ids).val());                        
                    }
                    if((index2+1) % 4 === 0){
                        datos_finales_ans.push(guardar_datos_ans);
                        guardar_datos_ans = [];
                    }
                });
            }
        });

        /* Datos del Footer */
        var footer_dato_1 = $("#footer_dato_1").val();
        var footer_dato_2 = $("#footer_dato_2").val();
        var footer_dato_3 = $("#footer_dato_3").val();
        var footer_dato_4 = $("#footer_dato_4").val();
        var footer_dato_5 = $("#footer_dato_5").val();

        // Recolección de la información para crear un cliente
        var enviar_info_actualizar_cliente = {
            '_token': token,
            'Id_cliente': $("#id_cliente_editar").val(),
            'Tipo_cliente' : $("#tipo_cliente").val(),
            'Otro_tipo_cliente': $("#otro_tipo_cliente").val(),
            'Nombre_cliente' : $("#nombre_cliente").val(),
            'Nit' : $("#nit_cliente").val(),
            'Telefono_principal' : $("#telefono_principal").val(),
            'Otros_telefonos' : $("#otros_telefonos").val(),
            'Email_principal' : $("#email_principal").val(),
            'Otros_emails' : $("#otros_emails").val(),
            'Linea_atencion_principal' : $("#linea_atencion_principal").val(),
            'Otras_lineas_atencion' : $("#otras_lineas_atencion").val(),
            'Direccion' : $("#direccion").val(),
            'Id_Departamento' : $("#departamento").val(),
            'Id_Ciudad' : $("#ciudad").val(),
            'Nro_Contrato': $("#nro_contrato").val(),
            'F_inicio_contrato': $("#f_inicio_contrato").val(),
            'F_finalizacion_contrato': $("#f_finalizacion_contrato").val(),
            'Nro_consecutivo_dictamen': $("#nro_consecutivo_dictamen").val(),
            'Estado': $("#status_cliente").val(),
            'Codigo_cliente': $("#codigo_cliente").val(),
            'Fecha_creacion': $("#fecha_creacion").val(),
            'Sucursales': datos_finales_sucursales,
            'Servicios_contratados': array_servicios_contratados,
            'ANS': datos_finales_ans,
            'Nombre_logo_bd': $("#nombre_logo_bd").val(),
            'Logo': $("#img_codificada").val(),
            'Extension_logo': $("#nombre_ext_imagen").val(),
            'Nombre_footer_bd': $("#nombre_footer_bd").val(),
            'Footer': $("#footer_codificado").val(),
            'Extension_footer': $("#nombre_ext_footer").val()
            // 'footer_dato_1': footer_dato_1,
            // 'footer_dato_2': footer_dato_2,
            // 'footer_dato_3': footer_dato_3,
            // 'footer_dato_4': footer_dato_4,
            // 'footer_dato_5': footer_dato_5
        };

        $.ajax({
            type: 'POST',
            url: '/ActualizarCliente',
            data: enviar_info_actualizar_cliente,
            success: function(response){

                if (response.parametro == "actualizo_cliente") {
                    $("#mostrar_barra_actualizar_cliente").addClass('d-none');
                    $("#mostrar_mensaje_actualizo_cliente").removeClass('d-none');
                    $(".mensaje_actualizo_cliente").addClass('alert-success');
                    $(".mensaje_actualizo_cliente").append('<strong>'+response.mensaje+'</strong>');
                    
                    setTimeout(() => {
                        $("#mostrar_mensaje_actualizo_cliente").addClass('d-none');
                        $(".mensaje_actualizo_cliente").removeClass('alert-success');
                        $(".mensaje_actualizo_cliente").empty();
                        $("#contenedor_btn_actualizar_cliente").removeClass('d-none');
                        location.reload();
                    }, 3000);
                }else{
                    $("#mostrar_barra_actualizar_cliente").addClass('d-none');
                    $("#mostrar_mensaje_actualizo_cliente").removeClass('d-none');
                    $(".mensaje_actualizo_cliente").addClass('alert-danger');
                    $(".mensaje_actualizo_cliente").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_actualizo_cliente").addClass('d-none');
                        $(".mensaje_actualizo_cliente").removeClass('alert-success');
                        $(".mensaje_actualizo_cliente").empty();
                        $("#contenedor_btn_actualizar_cliente").removeClass('d-none');
                        // location.reload();
                    }, 3000);
                }
            }
        });
    });
});

/* Función para añadir los controles de cada elemento de cada fila en la tabla sucursales */
function funciones_elementos_fila_sucursales(num_consecutivo){

    // SELECT 2 DEPARTAMENTOS
    $(".departamento_sucursal_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    // SELECT2 CIUDADES
    $(".ciudad_sucursal_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();

    //Listado de departamento
    let datos_lista_departamento_cliente = {
        '_token': token,
        'parametro':"lista_departamentos_cliente"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_departamento_cliente,
        success:function(data) {
            //console.log(data);
            $('#departamento_sucursal_'+num_consecutivo).empty();
            $('#departamento_sucursal_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#departamento_sucursal_'+num_consecutivo).append('<option value="'+data[claves[i]]["Id_departamento"]+'">'+data[claves[i]]["Nombre_departamento"]+'</option>');
            }
        }
    });

    // Listado Municipio
    $('#departamento_sucursal_'+num_consecutivo).change( function(){
        $('#ciudad_sucursal_'+num_consecutivo).prop('disabled', false);
        let id_departamento_cliente = $('#departamento_sucursal_'+num_consecutivo).val();
        let datos_municipio_cliente = {
            '_token': token,
            'parametro' : "lista_municipios_cliente",
            'id_departamento_cliente': id_departamento_cliente
        };

        $.ajax({
            type:'POST',
            url:'/cargarselectores',
            data: datos_municipio_cliente,
            success:function(data) {
                //console.log(data);
                $('#ciudad_sucursal_'+num_consecutivo).empty();
                $('#ciudad_sucursal_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $('#ciudad_sucursal_'+num_consecutivo).append('<option value="'+data[claves[i]]["Id_municipios"]+'">'+data[claves[i]]["Nombre_municipio"]+'</option>');
                }
            }
        });
    });

}

/* Función para añadir los controles de cada elemento de cada fila en la tabla ans */
function funciones_elementos_fila_ans(num_consecutivo){

    // SELECT 2 DEPARTAMENTOS
    $(".unidad_ans_"+num_consecutivo).select2({
        // width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();

    //Listado de departamento
    let datos_lista_unidades_ans = {
        '_token': token,
        'parametro':"lista_unidades_ans"
    };
    $.ajax({
        type:'POST',
        url:'/cargarselectores',
        data: datos_lista_unidades_ans,
        success:function(data) {
            //console.log(data);
            $('#unidad_ans_'+num_consecutivo).empty();
            $('#unidad_ans_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $('#unidad_ans_'+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

}
