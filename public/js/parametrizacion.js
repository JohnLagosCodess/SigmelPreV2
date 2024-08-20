$(document).ready(function(){

    /* CAPTURA DE NOMBRE DE USUARIO Y FECHA ACTUAL PARA USAR EN LOS DATATABLES */
    var nombre_usuario = $("#nombre_usuario").val();
    var fecha_actual = $("#fecha_actual").val();

    /* INGRESAR AL MÓDULO DE PARAMETRIZACIÓN */
    $(document).on('click', 'a[id^="btn_parametrizacion_"]', function(){
        var id_cliente_parametrizar = $(this).data("id_cliente_parametrizar");
        $("#enviar_form_parametrizacion_"+id_cliente_parametrizar).click();
    });
    
    /* INICIO PROGRAMACIÓN PARAMETRIZACIÓN PROCESO ORIGEN ATEL */
    
    /* TABLA PARA REALIZAR DESCARGA DEL EXCEL */
    $('#tabla_origen_atel_descarga thead tr').clone(true).addClass('filters_origen_atel_descarga').appendTo('#tabla_origen_atel_descarga thead');
    var tabla_origen_atel_descarga = $("#tabla_origen_atel_descarga").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        // searching: false,
        info: false,
        paging: false,
        "destroy": true,
        dom: 'Bfrtip',
        initComplete: function () {
            var api = this.api();

            // Columnas específicas a las que se aplicará el código de filtros
            var targetColumns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26];
            
            // Para cada columna
            api.columns().eq(0).each(function (colIdx) {
                // Verifica si la columna actual está en la lista de columnas objetivo
                if (targetColumns.includes(colIdx)) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters_origen_atel_descarga th').eq(
                        $(api.column(colIdx).header()).index()
                    );

                    var title = $(cell).text();
                    // Modifica la condición para excluir el último filtro
                    if (title !== 'Detalle' && $(cell).attr('class') !== 'centrar sorting_disabled') {
                        $(cell).html('<input type="text" style="width:100%;"/>');
                        $('input', $('.filters_origen_atel_descarga th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                        .on('change', function (e) {
                                // Obtiene el valor de búsqueda
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
                    else {
                        $(cell).html('<input type="hidden" style="width:100%;"/>');
                    }
                }
            });

        },
        buttons:{
            dom:{
                buttons:{
                    className: 'btn'
                }
            },
            buttons:[
                {
                    extend:"excel",
                    title: 'Listado Parametrización Proceso Origen ATEL',
                    text:'Exportar datos',
                    className: 'btn btn-info',
                    "excelStyles": [                      // Add an excelStyles definition
                                                
                    ],
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26]
                    }
                }
            ]
        },
    });
    
    autoAdjustColumns(tabla_origen_atel_descarga);

    /* CREACIÓN DE DATATABLE PARAMETRIZACIÓN ORIGEN ATEL */
    var tabla_parametrizar_origen_atel = '';
    $("#btn_abrir_parametrica_origen_atel").click(function(){
        $('#parametrizar_origen_atel thead tr').clone(true).addClass('filters').appendTo('#parametrizar_origen_atel thead');
        tabla_parametrizar_origen_atel = $("#parametrizar_origen_atel").DataTable({
            "responsive": true,
            "scrollCollapse": true,
            ordering: false,
            fixedHeader: true,
            scrollX: true,
            scrollY: 500,
            paging: false,
            "destroy": true,
            initComplete: function () {
                var api = this.api();
    
                // Columnas específicas a las que se aplicará el código de filtros
                var targetColumns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27];
                
                // Para cada columna
                api.columns().eq(0).each(function (colIdx) {
                    // Verifica si la columna actual está en la lista de columnas objetivo
                    if (targetColumns.includes(colIdx)) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
    
                        var title = $(cell).text();
                        // Modifica la condición para excluir el último filtro
                        if (title !== 'Detalle' && $(cell).attr('class') !== 'centrar sorting_disabled') {
                            $(cell).html('<input type="text" style="width:100%;"/>');
                            $('input', $('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                            .on('change', function (e) {
                                    // Obtiene el valor de búsqueda
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
                                        
                                        
                                    tabla_origen_atel_descarga.column(colIdx).search(
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
                        else {
                            $(cell).html('<input type="hidden" style="width:100%;"/>');
                        }
                    }
                });
    
            },
            dom: 'Bfrtip',
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
        
        autoAdjustColumns(tabla_parametrizar_origen_atel);

        $('button[aria-controls="parametrizar_origen_atel"]').hide();
        $('input[aria-controls="tabla_origen_atel_descarga"]').hide();
    });
    
    /* SETEO DE LOS DATOS DE LA PARAMETRIZACIÓN DEL PROCESO ORIGEN ATEL (SOLAMENTE CUANDO SE ENCUENTREN) */
    $(document).on('click', 'a[id^="bd_editar_fila_origen_atel_"]', function(){
        var id_fila_parametrizacion_editar = $(this).data("id_fila_parametrizacion_editar");
        $("#id_parametrizacion_origen_atel_editar").val(id_fila_parametrizacion_editar);

        var row = $(this).closest('tr');
        row.find('input, textarea, select').prop('readonly', false).prop('disabled', false);
        row.find('input').removeClass('d-none');
        row.find('span').addClass('d-none');
        if ($('#bd_movimiento_automatico_origen_atel_'+id_fila_parametrizacion_editar).is(':checked')) {
            $('#bd_tiempo_movimiento_origen_atel_'+id_fila_parametrizacion_editar).prop('disabled', false);
            $('#bd_accion_automatica_origen_atel_'+id_fila_parametrizacion_editar).prop('disabled', false);
        } else {
            $('#bd_tiempo_movimiento_origen_atel_'+id_fila_parametrizacion_editar).prop('disabled', true);
            $('#bd_accion_automatica_origen_atel_'+id_fila_parametrizacion_editar).prop('disabled', true);            
        }
        row.find('#bd_editar_fila_origen_atel_'+id_fila_parametrizacion_editar).addClass('d-none');
        row.find('#bd_guardar_fila_origen_atel_'+id_fila_parametrizacion_editar).removeClass('d-none');

        // mantenemos el input fecha actualización movimiento disabled en todo movimiento
        $("#bd_fecha_actualizacion_movimiento_origen_atel_"+id_fila_parametrizacion_editar).prop('disabled', true);

        // Esta función realiza los controles de cada elemento por fila
        edicion_parametrizacion_origen_atel(id_fila_parametrizacion_editar);

//Si no hay niguna accion selecionada setea los valores de enviar como vacio
$(`#bd_accion_ejecutar_origen_atel_${id_parametrizacion_calificacion_pcl_editar} `).change(function(){
    if($(this).val() == 0){
        console.log('q');
        $('#bd_enviar_a_origen_atel_'+id_parametrizacion_calificacion_pcl_editar).prop('disabled',true);
        $('#bd_enviar_a_origen_atel_'+id_parametrizacion_calificacion_pcl_editar).prop('checked',false);
        $('#bd_bandeja_trabajo_destino_origen_atel_'+id_parametrizacion_calificacion_pcl_editar).empty();
    }else{
        $('#bd_enviar_a_origen_atel_'+id_parametrizacion_calificacion_pcl_editar).prop('disabled',false);
    }
});
    });
    
    /* ACTUALIZAR PARAMETRIZACIÓN ORIGEN ATEL */
    $(document).on('click', "a[id^='bd_guardar_fila_origen_atel_']", function(){
        let token = $("input[name='_token']").val();
        var id_parametrizacion_origen_atel_editar = $("#id_parametrizacion_origen_atel_editar").val();
        // Capturamos los datos de cada tr
        var row = $(this).closest('tr');
        // A todos los input, textarea, select se les adiciona las propiedades readonlu y disabled
        row.find('input, textarea, select').prop('readonly', true).prop('disabled', true);
        // mostrar el botón para editar de nuevo
        row.find('#bd_editar_fila_origen_atel_'+id_parametrizacion_origen_atel_editar).removeClass('d-none');
        row.find('#bd_guardar_fila_origen_atel_'+id_parametrizacion_origen_atel_editar).addClass('d-none');

        // Inicializamos un objeto para almacenar los valores de la fila
        var datos_cada_fila_proceso_origen_atel = {};
 
        // Recorre todas las celdas de la fila
        row.find('td').each(function() {
            // Obtén el valor de la celda y luego cada id correspondiente
            var cell = $(this);
            var input = cell.find('input, textarea, select');
            var fieldName = input.attr('id'); 
 
            // Se valida los checkbox para verificar si fueron marcados o no.
            if (input.is(':checkbox')) {
                datos_cada_fila_proceso_origen_atel[fieldName] = input.is(':checked') ? 'Si' : 'No';
 
            } else if (input.val() !== undefined ) {
                datos_cada_fila_proceso_origen_atel[fieldName] = input.val();
            }
        });
 
        // Convierte el objeto en un array para mejor manejo de los datos
        var array_datos_fila_parametrizacion_origen_atel = $.map(datos_cada_fila_proceso_origen_atel, function(value, key) {
            return { nombre: key, valor: value };
        });

        array_datos_fila_parametrizacion_origen_atel.shift();
        
        // Enviamos la información para insertar y/o actualizar
        let actualizar_informacion_parametrizacion_origen_atel = {
            '_token': token,
            'array_datos_fila_parametrizacion_origen_atel' : array_datos_fila_parametrizacion_origen_atel,
            'Id_cliente': $("#Id_cliente").val(),
            'id_parametrizacion_origen_atel_editar': $("#id_parametrizacion_origen_atel_editar").val()
        };

        $.ajax({
            type:'POST',
            url:'/ActualizarParametrizacionOrigenAtel',
            data: actualizar_informacion_parametrizacion_origen_atel,
            success:function(response){
                if (response.parametro == "actualizo_parametrizacion") {
                    $("#mostrar_mensaje_agrego_parametrizacion_origen_atel").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_origen_atel").addClass('alert-success');
                    $(".mensaje_agrego_parametrizacion_origen_atel").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_origen_atel").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_origen_atel").removeClass('alert-success');
                        $(".mensaje_agrego_parametrizacion_origen_atel").empty();
                        window.location.reload();
                    }, 3000);
                }else{
                    $("#mostrar_mensaje_agrego_parametrizacion_origen_atel").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_origen_atel").addClass('alert-danger');
                    $(".mensaje_agrego_parametrizacion_origen_atel").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_origen_atel").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_origen_atel").removeClass('alert-danger');
                        $(".mensaje_agrego_parametrizacion_origen_atel").empty();
                        window.location.reload();
                    }, 3000);
                }
            }
        });

    });

    /* CREACION DE FILAS DINÁMICAS ORIGEN ATEL*/
    var contador_origen_atel = 0;
    var retenedor_contador_origen_atel = 0;
    $("#btn_agregar_parametrizacion_origen_atel").click(function(){
       
        if ($("#total_parametrizaciones_origen_atel").val() > 0) {
            if (retenedor_contador_origen_atel > 0) {
                contador_origen_atel = parseInt($("#contador_origen_atel_"+retenedor_contador_origen_atel).val()) + 1;
                retenedor_contador_origen_atel = contador_origen_atel;
            }else{
                var total_parametrizaciones_origen_atel = parseInt($("#total_parametrizaciones_origen_atel").val());
                contador_origen_atel = parseInt($("#contador_origen_atel_"+total_parametrizaciones_origen_atel).val()) + 1;
                retenedor_contador_origen_atel = contador_origen_atel;
            }
        }else{
            contador_origen_atel = contador_origen_atel + 1;
        }
       
        var nueva_fila_parametrizacion_origen_atel = [
            '<div style="text-align:center;"><a href="javascript:void(0);" class="d-none" id="editar_fila_origen_atel_'+contador_origen_atel+'"><i class="fa fa-sm fa-pen text-primary"></i></a> <a href="javascript:void(0);" id="guardar_fila_origen_atel_'+contador_origen_atel+'"><i class="fa fa-sm fa-check text-success"></i></a></div>',
            '<div style="text-align:center;">'+contador_origen_atel+'</div><input type="hidden" id="contador_origen_atel_'+contador_origen_atel+'" value="'+contador_origen_atel+'">',
            '<input type="date" class="form-control" name="fecha_creacion_movimiento_origen_atel" id="fecha_creacion_movimiento_origen_atel_'+contador_origen_atel+'" value="'+fecha_actual+'">',
            '<select class="custom-select servicio_asociado_origen_atel_'+contador_origen_atel+'" name="servicio_asociado_origen_atel" id="servicio_asociado_origen_atel_'+contador_origen_atel+'"><option></option></select>',
            '<select class="custom-select estado_origen_atel_'+contador_origen_atel+'" name="estado_origen_atel" id="estado_origen_atel_'+contador_origen_atel+'"><option value=""></option></select>',
            '<select disabled class="custom-select accion_ejecutar_origen_atel_'+contador_origen_atel+'" name="accion_ejecutar_origen_atel" id="accion_ejecutar_origen_atel_'+contador_origen_atel+'"><option value=""></option></select>',
            '<select class="custom-select accion_antecesora_origen_atel_'+contador_origen_atel+'" name="accion_antecesora_origen_atel" id="accion_antecesora_origen_atel_'+contador_origen_atel+'"><option value=""></option></select>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_nuevo_origen_atel" id="modulo_nuevo_origen_atel_'+contador_origen_atel+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_consultar_origen_atel" id="modulo_consultar_origen_atel_'+contador_origen_atel+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="bandeja_trabajo_origen_atel" id="bandeja_trabajo_origen_atel_'+contador_origen_atel+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_principal_origen_atel" id="modulo_principal_origen_atel_'+contador_origen_atel+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="detiene_tiempo_gestion_origen_atel" id="detiene_tiempo_gestion_origen_atel_'+contador_origen_atel+'"></div>',
            '<select disabled class="custom-select equipo_trabajo_origen_atel_'+contador_origen_atel+'" name="equipo_trabajo_origen_atel" id="equipo_trabajo_origen_atel_'+contador_origen_atel+'"><option value=""></option></select>',
            '<select disabled class="custom-select listado_profesionales_origen_atel_'+contador_origen_atel+'" name="listado_profesionales_origenl_atel" id="listado_profesionales_origen_atel_'+contador_origen_atel+'"><option value=""></option></select>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="enviar_a_origen_atel" id="enviar_a_origen_atel_'+contador_origen_atel+'" disabled></div>',
            '<select disabled class="custom-select bandeja_trabajo_destino_origen_atel_'+contador_origen_atel+'" name="bandeja_trabajo_destino_origen_atel" id="bandeja_trabajo_destino_origen_atel_'+contador_origen_atel+'"><option value=""></option></select>',
            '<input type="text" class="form-control" name="estado_facturacion_origen_atel" id="estado_facturacion_origen_atel_'+contador_origen_atel+'">',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="movimiento_automatico_origen_atel" id="movimiento_automatico_origen_atel_'+contador_origen_atel+'" data-id_movimiento_automatico_origen_atel="'+contador_origen_atel+'"></div>',
            '<input disabled style="width:140px;" type="number" class="form-control" name="tiempo_movimiento_origen_atel" id="tiempo_movimiento_origen_atel_'+contador_origen_atel+'">',
            '<select disabled class="custom-select accion_automatica_origen_atel'+contador_origen_atel+'" name="accion_automatica_origen_atel" id="accion_automatica_origen_atel_'+contador_origen_atel+'"><option value=""></option></select>',
            '<input style="width:140px;" type="text" class="form-control" name="tiempo_alerta_origen_atel" id="tiempo_alerta_origen_atel_'+contador_origen_atel+'">',
            '<input style="width:140px;" type="text" class="form-control" name="porcentaje_alerta_naranja_origen_atel" id="porcentaje_alerta_naranja_origen_atel_'+contador_origen_atel+'">',
            '<input style="width:140px;" type="text" class="form-control" name="porcentaje_alerta_roja_origen_atel" id="porcentaje_alerta_roja_origen_atel_'+contador_origen_atel+'">',
            '<select class="custom-select status_parametrico_origen_atel_'+contador_origen_atel+'" name="status_parametrico_origen_atel" id="status_parametrico_origen_atel_'+contador_origen_atel+'"><option></option><option value="Activo">Activo</option><option value="Inactivado">Inactivado</option></select>',
            '<textarea style="width:140px;" class="form-control" name="motivo_movimiento_origen_atel" id="motivo_movimiento_origen_atel_'+contador_origen_atel+'" cols="90" rows="4"></textarea>',
            '<input style="width:140px;" type="text" class="form-control" name="nombre_usuario_origen_atel" id="nombre_usuario_origen_atel_'+contador_origen_atel+'" value="'+nombre_usuario+'">',
            '<input type="date" class="form-control" name="fecha_actualizacion_movimiento_origen_atel" id="fecha_actualizacion_movimiento_origen_atel_'+contador_origen_atel+'">',
            '<div style="text-align:center;">-<div>',
            'fila_'+contador_origen_atel
        ];

        var agregar_parametrizacion_origen_atel_fila = tabla_parametrizar_origen_atel.row.add(nueva_fila_parametrizacion_origen_atel).draw('full-hold').node();
        $(agregar_parametrizacion_origen_atel_fila).addClass('fila_'+contador_origen_atel);
        $(agregar_parametrizacion_origen_atel_fila).attr("id", 'fila_'+contador_origen_atel);

        // Esta función realiza los controles de cada elemento por fila
        funciones_elementos_fila_parametrizar_origen_atel(contador_origen_atel);
    });

    /* FIN PROGRAMACIÓN PARAMETRIZACIÓN PROCESO ORIGEN ATEL */

    /* INICIO PROGRAMACIÓN PARAMETRIZACIÓN PROCESO CALIFICACIÓN PCL */

    /* TABLA PARA REALIZAR DESCARGA DEL EXCEL */
    $('#tabla_calificacion_pcl_descarga thead tr').clone(true).addClass('filters_calificacion_pcl_descarga').appendTo('#tabla_calificacion_pcl_descarga thead');
    var tabla_calificacion_pcl_descarga = $("#tabla_calificacion_pcl_descarga").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        info: false,
        paging: false,
        "destroy": true,
        dom: 'Bfrtip',
        initComplete: function () {
            var api = this.api();

            // Columnas específicas a las que se aplicará el código de filtros
            var targetColumns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26];
            
            // Para cada columna
            api.columns().eq(0).each(function (colIdx) {
                // Verifica si la columna actual está en la lista de columnas objetivo
                if (targetColumns.includes(colIdx)) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters_calificacion_pcl_descarga th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    
                    var title = $(cell).text();
                    
                    // Modifica la condición para excluir el último filtro
                    if (title !== 'Detalle' && $(cell).attr('class') !== 'centrar sorting_disabled') {
                        $(cell).html('<input type="text" style="width:100%;"/>');
                        $('input', $('.filters_calificacion_pcl_descarga th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                        .on('change', function (e) {
                            // Obtiene el valor de búsqueda
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
                    else {
                        $(cell).html('<input type="hidden" style="width:100%;"/>');
                    }
                }
            });
        },
        buttons:{
            dom:{
                buttons:{
                    className: 'btn'
                }
            },
            buttons:[
                {
                    extend:"excel",
                    title: 'Listado Parametrización Proceso Calificación PCL',
                    text:'Exportar datos',
                    className: 'btn btn-info',
                    "excelStyles": [                      // Add an excelStyles definition
                                                
                    ],
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26]
                    }
                }
            ]
        },
    });

    autoAdjustColumns(tabla_calificacion_pcl_descarga);

    /* CREACIÓN DE DATATABLE PARAMETRIZACIÓN CALIFICACIÓN PCL */
    var tabla_parametrizar_calificacion_pcl = '';
    $("#btn_abrir_parametrica_calificacion_pcl").click(function(){
        $('#parametrizar_calificacion_pcl thead tr').clone(true).addClass('filters_calificacion_pcl').appendTo('#parametrizar_calificacion_pcl thead');
        tabla_parametrizar_calificacion_pcl = $("#parametrizar_calificacion_pcl").DataTable({
            "responsive": true,
            "scrollCollapse": true,
            ordering: false,
            fixedHeader: true,
            scrollX: true,
            scrollY: 500,
            "destroy": true,
            paging: false,
            initComplete: function () {
                var api = this.api();
    
                // Columnas específicas a las que se aplicará el código de filtros
                var targetColumns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27];
                
                // Para cada columna
                api.columns().eq(0).each(function (colIdx) {
                    // Verifica si la columna actual está en la lista de columnas objetivo
                    if (targetColumns.includes(colIdx)) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters_calificacion_pcl th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        
                        var title = $(cell).text();
                        
                        // Modifica la condición para excluir el último filtro
                        if (title !== 'Detalle' && $(cell).attr('class') !== 'centrar sorting_disabled') {
                            $(cell).html('<input type="text" style="width:100%;"/>');
                            $('input', $('.filters_calificacion_pcl th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                            .on('change', function (e) {
                                // Obtiene el valor de búsqueda
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

                                tabla_calificacion_pcl_descarga.column(colIdx).search(
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
                        else {
                            $(cell).html('<input type="hidden" style="width:100%;"/>');
                        }
                    }
                });
            },
            dom: 'Bfrtip',
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
    
        autoAdjustColumns(tabla_parametrizar_calificacion_pcl);
        $('button[aria-controls="parametrizar_calificacion_pcl"]').hide();
        $('input[aria-controls="tabla_calificacion_pcl_descarga"]').hide();
    });

    /* SETEO DE LOS DATOS DE LA PARAMETRIZACIÓN DEL PROCESO CALIFICACIÓN PCL (SOLAMENTE CUANDO SE ENCUENTREN) */
    $(document).on('click', 'a[id^="bd_editar_fila_calificacion_pcl_"]', function(){
        var id_fila_parametrizacion_editar = $(this).data("id_fila_parametrizacion_editar");
        $("#id_parametrizacion_calificacion_pcl_editar").val(id_fila_parametrizacion_editar);

        var row = $(this).closest('tr');
        row.find('input, textarea, select').prop('readonly', false).prop('disabled', false);
        row.find('input').removeClass('d-none');
        row.find('span').addClass('d-none');
        if ($('#bd_movimiento_automatico_calificacion_pcl_'+id_fila_parametrizacion_editar).is(':checked')) {
            $('#bd_tiempo_movimiebd_tiempo_movimiento_calificacion_pcl_nto_origen_atel_'+id_fila_parametrizacion_editar).prop('disabled', false);
            $('#bd_accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_editar).prop('disabled', false);
        } else {
            $('#bd_tiempo_movimiento_calificacion_pcl_'+id_fila_parametrizacion_editar).prop('disabled', true);
            $('#bd_accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_editar).prop('disabled', true);            
        }
        row.find('#bd_editar_fila_calificacion_pcl_'+id_fila_parametrizacion_editar).addClass('d-none');
        row.find('#bd_guardar_fila_calificacion_pcl_'+id_fila_parametrizacion_editar).removeClass('d-none');

        // mantenemos el input fecha actualización movimiento disabled en todo movimiento
        $("#bd_fecha_actualizacion_movimiento_calificacion_pcl_"+id_fila_parametrizacion_editar).prop('disabled', true);

        // Esta función realiza los controles de cada elemento por fila
        edicion_parametrizacion_calificacion_pcl(id_fila_parametrizacion_editar);
    });

    /* ACTUALIZAR PARAMETRIZACIÓN CALIFICACIÓN PCL */
    $(document).on('click', "a[id^='bd_guardar_fila_calificacion_pcl_']", function(){
        let token = $("input[name='_token']").val();
        var id_parametrizacion_calificacion_pcl_editar = $("#id_parametrizacion_calificacion_pcl_editar").val();
        // Capturamos los datos de cada tr
        var row = $(this).closest('tr');
        // A todos los input, textarea, select se les adiciona las propiedades readonlu y disabled
        row.find('input, textarea, select').prop('readonly', true).prop('disabled', true);
        // mostrar el botón para editar de nuevo
        row.find('#bd_editar_fila_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).removeClass('d-none');
        row.find('#bd_guardar_fila_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).addClass('d-none');

        // Inicializamos un objeto para almacenar los valores de la fila
        var datos_cada_fila_proceso_calificacion_pcl = {};
 
        // Recorre todas las celdas de la fila
        row.find('td').each(function() {
            // Obtén el valor de la celda y luego cada id correspondiente
            var cell = $(this);
            var input = cell.find('input, textarea, select');
            var fieldName = input.attr('id'); 
 
            // Se valida los checkbox para verificar si fueron marcados o no.
            if (input.is(':checkbox')) {
                datos_cada_fila_proceso_calificacion_pcl[fieldName] = input.is(':checked') ? 'Si' : 'No';
 
            } else if (input.val() !== undefined ) {
                datos_cada_fila_proceso_calificacion_pcl[fieldName] = input.val();
            }
        });
 
        // Convierte el objeto en un array para mejor manejo de los datos
        var array_datos_fila_parametrizacion_calificacion_pcl = $.map(datos_cada_fila_proceso_calificacion_pcl, function(value, key) {
            return { nombre: key, valor: value };
        });

        array_datos_fila_parametrizacion_calificacion_pcl.shift();

        // Enviamos la información para insertar y/o actualizar
        let actualizar_informacion_parametrizacion_calificacion_pcl = {
            '_token': token,
            'array_datos_fila_parametrizacion_calificacion_pcl' : array_datos_fila_parametrizacion_calificacion_pcl,
            'Id_cliente': $("#Id_cliente").val(),
            'id_parametrizacion_calificacion_pcl_editar': $("#id_parametrizacion_calificacion_pcl_editar").val()
        };

        $.ajax({
            type:'POST',
            url:'/ActualizarParametrizacionCalificacionPcl',
            data: actualizar_informacion_parametrizacion_calificacion_pcl,
            success:function(response){
                if (response.parametro == "actualizo_parametrizacion") {
                    $("#mostrar_mensaje_agrego_parametrizacion_calificacion_pcl").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_calificacion_pcl").addClass('alert-success');
                    $(".mensaje_agrego_parametrizacion_calificacion_pcl").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_calificacion_pcl").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_calificacion_pcl").removeClass('alert-success');
                        $(".mensaje_agrego_parametrizacion_calificacion_pcl").empty();
                        window.location.reload();
                    }, 3000);
                }else{
                    $("#mostrar_mensaje_agrego_parametrizacion_calificacion_pcl").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_calificacion_pcl").addClass('alert-danger');
                    $(".mensaje_agrego_parametrizacion_calificacion_pcl").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_calificacion_pcl").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_calificacion_pcl").removeClass('alert-danger');
                        $(".mensaje_agrego_parametrizacion_calificacion_pcl").empty();
                        window.location.reload();
                    }, 3000);
                }
            }
        });

    });

    /* CREACION DE FILAS DINÁMICAS CALIFICACIÓN PCL */
    var contador_calificacion_pcl = 0;
    var retenedor_contador_calificacion_pcl = 0;
    $("#btn_agregar_parametrizacion_calificacion_pcl").click(function(){
        if ($("#total_parametrizaciones_calificacion_pcl").val() > 0) {
            if (retenedor_contador_calificacion_pcl > 0) {
                contador_calificacion_pcl = parseInt($("#contador_calificacion_pcl_"+retenedor_contador_calificacion_pcl).val()) + 1;
                retenedor_contador_calificacion_pcl = contador_calificacion_pcl;
            }else{
                var total_parametrizaciones_calificacion_pcl = parseInt($("#total_parametrizaciones_calificacion_pcl").val());
                contador_calificacion_pcl = parseInt($("#contador_calificacion_pcl_"+total_parametrizaciones_calificacion_pcl).val()) + 1;
                retenedor_contador_calificacion_pcl = contador_calificacion_pcl;
            }
        }else{
            contador_calificacion_pcl = contador_calificacion_pcl + 1;
        }
       
        var nueva_fila_parametrizacion_calificacion_pcl = [
            '<div style="text-align:center;"><a href="javascript:void(0);" class="d-none" id="editar_fila_calificacion_pcl_'+contador_calificacion_pcl+'"><i class="fa fa-sm fa-pen text-primary"></i></a> <a href="javascript:void(0);" id="guardar_fila_calificacion_pcl_'+contador_calificacion_pcl+'"><i class="fa fa-sm fa-check text-success"></i></a></div>',
            '<div style="text-align:center;">'+contador_calificacion_pcl+'</div><input type="hidden" id="contador_calificacion_pcl_'+contador_calificacion_pcl+'" value="'+contador_calificacion_pcl+'">',
            '<input type="date" class="form-control" name="fecha_creacion_movimiento_calificacion_pcl" id="fecha_creacion_movimiento_calificacion_pcl_'+contador_calificacion_pcl+'" value="'+fecha_actual+'">',
            '<select class="custom-select servicio_asociado_calificacion_pcl_'+contador_calificacion_pcl+'" name="servicio_asociado_calificacion_pcl" id="servicio_asociado_calificacion_pcl_'+contador_calificacion_pcl+'"><option></option></select>',
            '<select class="custom-select estado_calificacion_pcl_'+contador_calificacion_pcl+'" name="estado_calificacion_pcl" id="estado_calificacion_pcl_'+contador_calificacion_pcl+'"><option value=""></option></select>',
            '<select disabled class="custom-select accion_ejecutar_calificacion_pcl_'+contador_calificacion_pcl+'" name="accion_ejecutar_calificacion_pcl" id="accion_ejecutar_calificacion_pcl_'+contador_calificacion_pcl+'"><option value=""></option></select>',
            '<select class="custom-select accion_antecesora_calificacion_pcl_'+contador_calificacion_pcl+'" name="accion_antecesora_calificacion_pcl" id="accion_antecesora_calificacion_pcl_'+contador_calificacion_pcl+'"><option value=""></option></select>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_nuevo_calificacion_pcl" id="modulo_nuevo_calificacion_pcl_'+contador_calificacion_pcl+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_consultar_calificacion_pcl" id="modulo_consultar_calificacion_pcl_'+contador_calificacion_pcl+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="bandeja_trabajo_calificacion_pcl" id="bandeja_trabajo_calificacion_pcl_'+contador_calificacion_pcl+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_principal_calificacion_pcl" id="modulo_principal_calificacion_pcl_'+contador_calificacion_pcl+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="detiene_tiempo_gestion_calificacion_pcl" id="detiene_tiempo_gestion_calificacion_pcl_'+contador_calificacion_pcl+'"></div>',
            '<select class="custom-select equipo_trabajo_calificacion_pcl_'+contador_calificacion_pcl+'" name="equipo_trabajo_calificacion_pcl" id="equipo_trabajo_calificacion_pcl_'+contador_calificacion_pcl+'"><option value=""></option></select>',
            '<select disabled class="custom-select listado_profesionales_calificacion_pcl_'+contador_calificacion_pcl+'" name="listado_profesionales_calificacion_pcl" id="listado_profesionales_calificacion_pcl_'+contador_calificacion_pcl+'"><option value=""></option></select>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="enviar_a_calificacion_pcl" id="enviar_a_calificacion_pcl_'+contador_calificacion_pcl+'" disabled></div>',
            '<select disabled class="custom-select bandeja_trabajo_destino_calificacion_pcl_'+contador_calificacion_pcl+'" name="bandeja_trabajo_destino_calificacion_pcl" id="bandeja_trabajo_destino_calificacion_pcl_'+contador_calificacion_pcl+'"><option value=""></option></select>',
            '<input type="text" class="form-control" name="estado_facturacion_calificacion_pcl" id="estado_facturacion_calificacion_pcl_'+contador_calificacion_pcl+'">',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="movimiento_automatico_calificacion_pcl" id="movimiento_automatico_calificacion_pcl_'+contador_calificacion_pcl+'" data-id_movimiento_automatico_calificacion_pcl="'+contador_calificacion_pcl+'"></div>',
            '<input disabled style="width:140px;" type="number" class="form-control" name="tiempo_movimiento_calificacion_pcl" id="tiempo_movimiento_calificacion_pcl_'+contador_calificacion_pcl+'">',
            '<select disabled class="custom-select accion_automatica_calificacion_pcl'+contador_calificacion_pcl+'" name="accion_automatica_calificacion_pcl" id="accion_automatica_calificacion_pcl_'+contador_calificacion_pcl+'"><option value=""></option></select>',
            '<input style="width:140px;" type="text" class="form-control" name="tiempo_alerta_calificacion_pcl" id="tiempo_alerta_calificacion_pcl_'+contador_calificacion_pcl+'">',
            '<input style="width:140px;" type="text" class="form-control" name="porcentaje_alerta_naranja_calificacion_pcl" id="porcentaje_alerta_naranja_calificacion_pcl_'+contador_calificacion_pcl+'">',
            '<input style="width:140px;" type="text" class="form-control" name="porcentaje_alerta_roja_calificacion_pcl" id="porcentaje_alerta_roja_calificacion_pcl_'+contador_calificacion_pcl+'">',
            '<select class="custom-select status_parametrico_calificacion_pcl_'+contador_calificacion_pcl+'" name="status_parametrico_calificacion_pcl" id="status_parametrico_calificacion_pcl_'+contador_calificacion_pcl+'"><option></option><option value="Activo">Activo</option><option value="Inactivado">Inactivado</option></select>',
            '<textarea style="width:140px;" class="form-control" name="motivo_movimiento_calificacion_pcl" id="motivo_movimiento_calificacion_pcl_'+contador_calificacion_pcl+'" cols="90" rows="4"></textarea>',
            '<input style="width:140px;" type="text" class="form-control" name="nombre_usuario_calificacion_pcl" id="nombre_usuario_calificacion_pcl_'+contador_calificacion_pcl+'" value="'+nombre_usuario+'">',
            '<input type="date" class="form-control" name="fecha_actualizacion_movimiento_calificacion_pcl" id="fecha_actualizacion_movimiento_calificacion_pcl_'+contador_calificacion_pcl+'">',
            '<div style="text-align:center;">-<div>',
            'fila_'+contador_calificacion_pcl
        ];

        var agregar_parametrizacion_calificacion_pcl_fila = tabla_parametrizar_calificacion_pcl.row.add(nueva_fila_parametrizacion_calificacion_pcl).draw('full-hold').node();
        $(agregar_parametrizacion_calificacion_pcl_fila).addClass('fila_'+contador_calificacion_pcl);
        $(agregar_parametrizacion_calificacion_pcl_fila).attr("id", 'fila_'+contador_calificacion_pcl);

        // Esta función realiza los controles de cada elemento por fila
        funciones_elementos_fila_parametrizar_calificacion_pcl(contador_calificacion_pcl);
    });

    /* FIN PROGRAMACIÓN PARAMETRIZACIÓN PROCESO CALIFICACIÓN PCL */

    /* INICIO PROGRAMACIÓN PARAMETRIZACIÓN PROCESO JUNTAS */

    /* TABLA PARA REALIZAR DESCARGA DEL EXCEL */
    $('#tabla_juntas_descarga thead tr').clone(true).addClass('filters_juntas_descarga').appendTo('#tabla_juntas_descarga thead');
    var tabla_juntas_descarga = $("#tabla_juntas_descarga").DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        info: false,
        paging: false,
        "destroy": true,
        dom: 'Bfrtip',
        initComplete: function () {
            var api = this.api();

            // Columnas específicas a las que se aplicará el código de filtros
            var targetColumns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26];            

            // Para cada columna
            api.columns().eq(0).each(function (colIdx) {
                // Verifica si la columna actual está en la lista de columnas objetivo
                if (targetColumns.includes(colIdx)) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters_juntas_descarga th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    
                    var title = $(cell).text();
                    
                    // Modifica la condición para excluir el último filtro
                    if (title !== 'Detalle' && $(cell).attr('class') !== 'centrar sorting_disabled') {
                        $(cell).html('<input type="text" style="width:100%;"/>');
                        $('input', $('.filters_juntas_descarga th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                        .on('change', function (e) {
                            // Obtiene el valor de búsqueda
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
                    else {
                        $(cell).html('<input type="hidden" style="width:100%;"/>');
                    }
                }
            });
        },
        buttons:{
            dom:{
                buttons:{
                    className: 'btn'
                }
            },
            buttons:[
                {
                    extend:"excel",
                    title: 'Listado Parametrización Proceso Juntas',
                    text:'Exportar datos',
                    className: 'btn btn-info',
                    "excelStyles": [                      // Add an excelStyles definition
                                                
                    ],
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24, 25, 26]
                    }
                }
            ]
        },
    });
    autoAdjustColumns(tabla_juntas_descarga);

    /* CREACIÓN DE DATATABLE PARAMETRIZACIÓN JUNTAS */
    var tabla_parametrizar_juntas = '';
    $("#btn_abrir_parametrica_juntas").click(function(){
        $('#parametrizar_juntas thead tr').clone(true).addClass('filters_juntas').appendTo('#parametrizar_juntas thead');
        tabla_parametrizar_juntas = $("#parametrizar_juntas").DataTable({
            "responsive": true,
            "scrollCollapse": true,
            ordering: false,
            fixedHeader: true,
            scrollX: true,
            scrollY: 500,
            "destroy": true,
            paging: false,
            initComplete: function () {
                var api = this.api();
    
                // Columnas específicas a las que se aplicará el código de filtros
                var targetColumns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27];
                
                // Para cada columna
                api.columns().eq(0).each(function (colIdx) {
                    // Verifica si la columna actual está en la lista de columnas objetivo
                    if (targetColumns.includes(colIdx)) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters_juntas th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        
                        var title = $(cell).text();
                        
                        // Modifica la condición para excluir el último filtro
                        if (title !== 'Detalle' && $(cell).attr('class') !== 'centrar sorting_disabled') {
                            $(cell).html('<input type="text" style="width:100%;"/>');
                            $('input', $('.filters_juntas th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                            .on('change', function (e) {
                                // Obtiene el valor de búsqueda
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
                                
                                tabla_juntas_descarga.column(colIdx).search(
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
                        else {
                            $(cell).html('<input type="hidden" style="width:100%;"/>');
                        }
                    }
                });
            },
            dom: 'Bfrtip',
            // buttons:{
            //     dom:{
            //         buttons:{
            //             className: 'btn'
            //         }
            //     },
            //     buttons:[
            //         {
            //             extend:"excel",
            //             title: 'Listado Parametrización Proceso Juntas',
            //             text:'Exportar datos',
            //             className: 'btn btn-info',
            //             "excelStyles": [                      // Add an excelStyles definition
                                                    
            //             ],
            //             exportOptions: {
            //                 columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
            //             }
            //         }
            //     ]
            // },
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
    
        autoAdjustColumns(tabla_parametrizar_juntas);
        $('button[aria-controls="parametrizar_juntas"]').hide();
        $('input[aria-controls="tabla_juntas_descarga"]').hide();
    })

    /* SETEO DE LOS DATOS DE LA PARAMETRIZACIÓN DEL PROCESO JUNTAS (SOLAMENTE CUANDO SE ENCUENTREN) */
    $(document).on('click', 'a[id^="bd_editar_fila_juntas_"]', function(){
        var id_fila_parametrizacion_editar = $(this).data("id_fila_parametrizacion_editar");
        $("#id_parametrizacion_juntas_editar").val(id_fila_parametrizacion_editar);

        var row = $(this).closest('tr');
        row.find('input, textarea, select').prop('readonly', false).prop('disabled', false);
        row.find('input').removeClass('d-none');
        row.find('span').addClass('d-none');
        if ($('#bd_movimiento_automatico_juntas_'+id_fila_parametrizacion_editar).is(':checked')) {
            $('#bd_tiempo_movimiento_juntas_'+id_fila_parametrizacion_editar).prop('disabled', false);
            $('#bd_accion_automatica_juntas_'+id_fila_parametrizacion_editar).prop('disabled', false);
        } else {
            $('#bd_tiempo_movimiento_juntas_'+id_fila_parametrizacion_editar).prop('disabled', true);
            $('#bd_accion_automatica_juntas_'+id_fila_parametrizacion_editar).prop('disabled', true);            
        }
        row.find('#bd_editar_fila_juntas_'+id_fila_parametrizacion_editar).addClass('d-none');
        row.find('#bd_guardar_fila_juntas_'+id_fila_parametrizacion_editar).removeClass('d-none');

        // mantenemos el input fecha actualización movimiento disabled en todo movimiento
        $("#bd_fecha_actualizacion_movimiento_juntas_"+id_fila_parametrizacion_editar).prop('disabled', true);

        // Esta función realiza los controles de cada elemento por fila
        edicion_parametrizacion_juntas(id_fila_parametrizacion_editar);
    });

    /* ACTUALIZAR PARAMETRIZACIÓN JUNTAS */
    $(document).on('click', "a[id^='bd_guardar_fila_juntas_']", function(){
        let token = $("input[name='_token']").val();
        var id_parametrizacion_juntas_editar = $("#id_parametrizacion_juntas_editar").val();
        // Capturamos los datos de cada tr
        var row = $(this).closest('tr');
        // A todos los input, textarea, select se les adiciona las propiedades readonlu y disabled
        row.find('input, textarea, select').prop('readonly', true).prop('disabled', true);
        // mostrar el botón para editar de nuevo
        row.find('#bd_editar_fila_juntas_'+id_parametrizacion_juntas_editar).removeClass('d-none');
        row.find('#bd_guardar_fila_juntas_'+id_parametrizacion_juntas_editar).addClass('d-none');

        // Inicializamos un objeto para almacenar los valores de la fila
        var datos_cada_fila_proceso_juntas = {};
 
        // Recorre todas las celdas de la fila
        row.find('td').each(function() {
            // Obtén el valor de la celda y luego cada id correspondiente
            var cell = $(this);
            var input = cell.find('input, textarea, select');
            var fieldName = input.attr('id'); 
 
            // Se valida los checkbox para verificar si fueron marcados o no.
            if (input.is(':checkbox')) {
                datos_cada_fila_proceso_juntas[fieldName] = input.is(':checked') ? 'Si' : 'No';
 
            } else if (input.val() !== undefined ) {
                datos_cada_fila_proceso_juntas[fieldName] = input.val();
            }
        });
 
        // Convierte el objeto en un array para mejor manejo de los datos
        var array_datos_fila_parametrizacion_juntas = $.map(datos_cada_fila_proceso_juntas, function(value, key) {
            return { nombre: key, valor: value };
        });

        array_datos_fila_parametrizacion_juntas.shift();

        // Enviamos la información para insertar y/o actualizar
        let actualizar_informacion_parametrizacion_juntas = {
            '_token': token,
            'array_datos_fila_parametrizacion_juntas' : array_datos_fila_parametrizacion_juntas,
            'Id_cliente': $("#Id_cliente").val(),
            'id_parametrizacion_juntas_editar': $("#id_parametrizacion_juntas_editar").val()
        };

        $.ajax({
            type:'POST',
            url:'/ActualizarParametrizacionJuntas',
            data: actualizar_informacion_parametrizacion_juntas,
            success:function(response){
                if (response.parametro == "actualizo_parametrizacion") {
                    $("#mostrar_mensaje_agrego_parametrizacion_juntas").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_juntas").addClass('alert-success');
                    $(".mensaje_agrego_parametrizacion_juntas").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_juntas").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_juntas").removeClass('alert-success');
                        $(".mensaje_agrego_parametrizacion_juntas").empty();
                        window.location.reload();
                    }, 3000);
                }else{
                    $("#mostrar_mensaje_agrego_parametrizacion_juntas").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_juntas").addClass('alert-danger');
                    $(".mensaje_agrego_parametrizacion_juntas").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_juntas").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_juntas").removeClass('alert-danger');
                        $(".mensaje_agrego_parametrizacion_juntas").empty();
                        window.location.reload();
                    }, 3000);
                }
            }
        });

    });

    /* CREACION DE FILAS DINÁMICAS JUNTAS */
    var contador_juntas = 0;
    var retenedor_contador_juntas = 0;
    $("#btn_agregar_parametrizacion_juntas").click(function(){
        if ($("#total_parametrizaciones_juntas").val() > 0) {
            if (retenedor_contador_juntas > 0) {
                contador_juntas = parseInt($("#contador_juntas_"+retenedor_contador_juntas).val()) + 1;
                retenedor_contador_juntas = contador_juntas;
            }else{
                var total_parametrizaciones_juntas = parseInt($("#total_parametrizaciones_juntas").val());
                contador_juntas = parseInt($("#contador_juntas_"+total_parametrizaciones_juntas).val()) + 1;
                retenedor_contador_juntas = contador_juntas;
            }
        }else{
            contador_juntas = contador_juntas + 1;
        }
       
        var nueva_fila_parametrizacion_juntas = [
            '<div style="text-align:center;"><a href="javascript:void(0);" class="d-none" id="editar_fila_juntas_'+contador_juntas+'"><i class="fa fa-sm fa-pen text-primary"></i></a> <a href="javascript:void(0);" id="guardar_fila_juntas_'+contador_juntas+'"><i class="fa fa-sm fa-check text-success"></i></a></div>',
            '<div style="text-align:center;">'+contador_juntas+'</div><input type="hidden" id="contador_juntas_'+contador_juntas+'" value="'+contador_juntas+'">',
            '<input type="date" class="form-control" name="fecha_creacion_movimiento_juntas" id="fecha_creacion_movimiento_juntas_'+contador_juntas+'" value="'+fecha_actual+'">',
            '<select class="custom-select servicio_asociado_juntas_'+contador_juntas+'" name="servicio_asociado_juntas" id="servicio_asociado_juntas_'+contador_juntas+'"><option></option></select>',
            '<select class="custom-select estado_juntas_'+contador_juntas+'" name="estado_juntas" id="estado_juntas_'+contador_juntas+'"><option value=""></option></select>',
            '<select disabled class="custom-select accion_ejecutar_juntas_'+contador_juntas+'" name="accion_ejecutar_juntas" id="accion_ejecutar_juntas_'+contador_juntas+'"><option value=""></option></select>',
            '<select class="custom-select accion_antecesora_juntas_'+contador_juntas+'" name="accion_antecesora_juntas" id="accion_antecesora_juntas_'+contador_juntas+'"><option value=""></option></select>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_nuevo_juntas" id="modulo_nuevo_juntas_'+contador_juntas+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_consultar_juntas" id="modulo_consultar_juntas_'+contador_juntas+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="bandeja_trabajo_juntas" id="bandeja_trabajo_juntas_'+contador_juntas+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="modulo_principal_juntas" id="modulo_principal_juntas_'+contador_juntas+'"></div>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="detiene_tiempo_gestion_juntas" id="detiene_tiempo_gestion_juntas_'+contador_juntas+'"></div>',
            '<select class="custom-select equipo_trabajo_juntas_'+contador_juntas+'" name="equipo_trabajo_juntas" id="equipo_trabajo_juntas_'+contador_juntas+'"><option value=""></option></select>',
            '<select disabled class="custom-select listado_profesionales_juntas_'+contador_juntas+'" name="listado_profesionales_juntas" id="listado_profesionales_juntas_'+contador_juntas+'"><option value=""></option></select>',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="enviar_a_juntas" id="enviar_a_juntas_'+contador_juntas+'" disabled></div>',
            '<select disabled class="custom-select bandeja_trabajo_destino_juntas_'+contador_juntas+'" name="bandeja_trabajo_destino_juntas" id="bandeja_trabajo_destino_juntas_'+contador_juntas+'"><option value=""></option></select>',
            '<input type="text" class="form-control" name="estado_facturacion_juntas" id="estado_facturacion_juntas_'+contador_juntas+'">',
            '<div style="text-align:center;"><input type="checkbox" class="scales" name="movimiento_automatico_juntas" id="movimiento_automatico_juntas_'+contador_juntas+'" data-id_movimiento_automatico_juntas="'+contador_juntas+'"></div>',
            '<input disabled style="width:140px;" type="number" class="form-control" name="tiempo_movimiento_juntas" id="tiempo_movimiento_juntas_'+contador_juntas+'">',
            '<select disabled class="custom-select accion_automatica_juntas'+contador_juntas+'" name="accion_automatica_juntas" id="accion_automatica_juntas_'+contador_juntas+'"><option value=""></option></select>',
            '<input style="width:140px;" type="text" class="form-control" name="tiempo_alerta_juntas" id="tiempo_alerta_juntas_'+contador_juntas+'">',
            '<input style="width:140px;" type="text" class="form-control" name="porcentaje_alerta_naranja_juntas" id="porcentaje_alerta_naranja_juntas_'+contador_juntas+'">',
            '<input style="width:140px;" type="text" class="form-control" name="porcentaje_alerta_roja_juntas" id="porcentaje_alerta_roja_juntas_'+contador_juntas+'">',
            '<select class="custom-select status_parametrico_juntas_'+contador_juntas+'" name="status_parametrico_juntas" id="status_parametrico_juntas_'+contador_juntas+'"><option></option><option value="Activo">Activo</option><option value="Inactivado">Inactivado</option></select>',
            '<textarea style="width:140px;" class="form-control" name="motivo_movimiento_juntas" id="motivo_movimiento_juntas_'+contador_juntas+'" cols="90" rows="4"></textarea>',
            '<input style="width:140px;" type="text" class="form-control" name="nombre_usuario_juntas" id="nombre_usuario_juntas_'+contador_juntas+'" value="'+nombre_usuario+'">',
            '<input type="date" class="form-control" name="fecha_actualizacion_movimiento_juntas" id="fecha_actualizacion_movimiento_juntas_'+contador_juntas+'">',
            '<div style="text-align:center;">-<div>',
            'fila_'+contador_juntas
        ];

        var agregar_parametrizacion_juntas_fila = tabla_parametrizar_juntas.row.add(nueva_fila_parametrizacion_juntas).draw('full-hold').node();
        $(agregar_parametrizacion_juntas_fila).addClass('fila_'+contador_juntas);
        $(agregar_parametrizacion_juntas_fila).attr("id", 'fila_'+contador_juntas);

        // Esta función realiza los controles de cada elemento por fila
        funciones_elementos_fila_parametrizar_juntas(contador_juntas);
    });
    
    /* FIN PROGRAMACIÓN PARAMETRIZACIÓN PROCESO JUNTAS */

});

function funciones_elementos_fila_parametrizar_origen_atel(num_consecutivo){
    
    /* INICIALIZACIÓN SELECT 2 LISTADO DE SERVICIOS ASOCIADOS */
    $(".servicio_asociado_origen_atel_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO ESTADOS */
    $(".estado_origen_atel_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES A EJECUTAR */
    $(".accion_ejecutar_origen_atel_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES ANTECESORAS */
    $(".accion_antecesora_origen_atel_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO EQUIPOS DE TRABAJO */
    $(".equipo_trabajo_origen_atel_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE PROFESIONALES */
    $(".listado_profesionales_origen_atel_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES AUTOMATICAS */
    $(".accion_automatica_origen_atel"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO BANDEJA TRABAJO DESTINO */
    $(".bandeja_trabajo_destino_origen_atel_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });
    

    /* INICIALIZACIÓN SELECT 2 STATUS PARAMETRICO */
    $(".status_parametrico_origen_atel_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();
    var Id_cliente = $("#Id_cliente").val();

    /* Carga del selector servicios asociados del proceso origen atel */
    let datos_servicios_asociados_origen_atel = {
        '_token': token,
        'parametro' : "servicios_asociados_proceso_origen_atel",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_servicios_asociados_origen_atel,
        success:function(data){
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#servicio_asociado_origen_atel_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
            }
        }
    });

    /* Carga del selector de estados del proceso origen atel */
    let datos_estados_origen_atel = {
        '_token': token,
        'parametro' : "lista_estados",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estados_origen_atel,
        success:function(data){
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#estado_origen_atel_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    /* Carga del selector de acciones a ejecutar dependiendo de la seleccion del estado del proceso origen atel */
    $("#estado_origen_atel_"+num_consecutivo).change(function(){
        $("#accion_ejecutar_origen_atel_"+num_consecutivo).prop('disabled', false);
        let id_estado_seleccionada_origen_atel = $("#estado_origen_atel_"+num_consecutivo).val();

        validacionesNotificaciones(num_consecutivo,'origen');

        let datos_acciones_ejecutar_origen_atel = {
            '_token': token,
            'parametro': "acciones_ejecutar_proceso_origen_atel",
            'Id_cliente': Id_cliente,
            'id_accion_seleccionada_origen_atel': id_estado_seleccionada_origen_atel
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_ejecutar_origen_atel,
            success:function(data){
                $('#accion_ejecutar_origen_atel_'+num_consecutivo).empty();
                $('#accion_ejecutar_origen_atel_'+num_consecutivo).append('<option value="0" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_ejecutar_origen_atel_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de equipos de trabajo dependiendo  del proceso origen atel */
    $("#accion_ejecutar_origen_atel_"+num_consecutivo).change(function(){
        $("#equipo_trabajo_origen_atel_"+num_consecutivo).prop('disabled', false);
        // let id_accion = $("#accion_ejecutar_origen_atel_"+num_consecutivo).val();
        validacionesNotificaciones(num_consecutivo,'origen','');

        let datos_equipos_trabajo_origen_atel = {
            '_token': token,
            'parametro' : "equipos_trabajo_proceso_origen_atel",
            // 'id_accion_seleccionada': id_accion
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_equipos_trabajo_origen_atel,
            success:function(data){
                $('#equipo_trabajo_origen_atel_'+num_consecutivo).empty();
                $('#equipo_trabajo_origen_atel_'+num_consecutivo).append('<option value="0" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#equipo_trabajo_origen_atel_"+num_consecutivo).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo */
    $("#equipo_trabajo_origen_atel_"+num_consecutivo).change(function(){
        $("#listado_profesionales_origen_atel_"+num_consecutivo).prop('disabled', false);
        let id_equipo_seleccionado = $("#equipo_trabajo_origen_atel_"+num_consecutivo).val();
        let datos_listado_profesionales_origen_atel = {
            '_token': token,
            'parametro' : "listado_profesionales_proceso_origen_atel",
            'id_equipo_seleccionado': id_equipo_seleccionado
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_listado_profesionales_origen_atel,
            success:function(data){
                $('#listado_profesionales_origen_atel_'+num_consecutivo).empty();
                $('#listado_profesionales_origen_atel_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#listado_profesionales_origen_atel_"+num_consecutivo).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado */
    $("#servicio_asociado_origen_atel_"+num_consecutivo).change(function(){
        var id_servicio_asociado_origen_atel = $(this).val();
        let datos_accion_antecesora_origen_atel = {
            '_token': token,
            'parametro' : "acciones_antecesoras_proceso_origen_atel",
            'servicio_asociado_origen_atel':id_servicio_asociado_origen_atel,
            'Id_cliente': Id_cliente
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_accion_antecesora_origen_atel,
            success:function(data){
                $('#accion_antecesora_origen_atel_'+num_consecutivo).empty();
                $('#accion_antecesora_origen_atel_'+num_consecutivo).append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_antecesora_origen_atel_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });

        /* Carga del selector de acciones automaticas dependiendo del servicio seleccionado*/        
        let datos_acciones_automaticas_origen_atel = {
            '_token': token,
            'parametro': "acciones_automaticas_proceso_origen_atel",
            'Id_cliente': Id_cliente,
            'Servicio_asociado_origen': id_servicio_asociado_origen_atel
        };
        // console.log(datos_acciones_automaticas_origen_atel);
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_automaticas_origen_atel,
            success:function(data){
                $('#accion_automatica_origen_atel_'+num_consecutivo).empty();
                $('#accion_automatica_origen_atel_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_automatica_origen_atel_"+num_consecutivo).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* HABILITAR CAMPOS DE TIEMPO DE MOVIMIENTO Y ACCION AUTOMATICA*/

    $("[id^='movimiento_automatico_origen_atel_']").change(function () {
        let id_fila_parametrizacion_movimiento = $(this).data("id_movimiento_automatico_origen_atel");
        let servicio_asociado_origen = $('#servicio_asociado_origen_atel_'+num_consecutivo).val();
        if ($(this).is(':checked')) {
            // console.log('id movi editar es: '+id_fila_parametrizacion_movimiento_editar);   
            $('#tiempo_movimiento_origen_atel_'+id_fila_parametrizacion_movimiento).prop('disabled', false);
            $('#accion_automatica_origen_atel_'+id_fila_parametrizacion_movimiento).prop('disabled', false); 
            
            /* Carga del selector de acciones automaticas dependiendo del estado seleccionado*/        
            let datos_acciones_automaticas_origen_atel = {
                '_token': token,
                'parametro': "acciones_automaticas_proceso_origen_atel",
                'Id_cliente': Id_cliente,
                'Servicio_asociado_origen': servicio_asociado_origen
            };
            // console.log(datos_acciones_automaticas_origen_atel);
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_acciones_automaticas_origen_atel,
                success:function(data){
                    $('#accion_automatica_origen_atel_'+num_consecutivo).empty();
                    $('#accion_automatica_origen_atel_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $("#accion_automatica_origen_atel_"+num_consecutivo).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                    }
                }
            });                
        }else{
            $("#tiempo_movimiento_origen_atel_"+id_fila_parametrizacion_movimiento).prop("disabled", true);
            $('#tiempo_movimiento_origen_atel_'+id_fila_parametrizacion_movimiento).val('');
            $('#accion_automatica_origen_atel_'+id_fila_parametrizacion_movimiento).prop('disabled', true);
            $('#accion_automatica_origen_atel_'+id_fila_parametrizacion_movimiento).empty();
            $('#accion_automatica_origen_atel_'+id_fila_parametrizacion_movimiento).append('<option value="" selected>Seleccione</option>');                 
        }
    });

    /* Habilitar el selector de bandeja de trabajo destino cuando se checkea el checkbox de enviar del proceso origen atel */
    $("#enviar_a_origen_atel_"+num_consecutivo).click(function(){
        if($(this).is(':checked')){
            $("#bandeja_trabajo_destino_origen_atel_"+num_consecutivo).prop("disabled", false);

            let datos_bandeja_trabajo_destino_origen_atel = {
                '_token': token,
                'parametro': "bandeja_trabajo_destino_notificaciones",
                'Id_cliente': Id_cliente,
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_bandeja_trabajo_destino_origen_atel,
                success:function(data){
                    $('#bandeja_trabajo_destino_origen_atel_'+num_consecutivo).empty();
                    $('#bandeja_trabajo_destino_origen_atel_'+num_consecutivo).append(`<option value="${data.Id_proceso}" selected>${data.Nombre_proceso}</option>`);
                }
            });

        }else{
            $("#bandeja_trabajo_destino_origen_atel_"+num_consecutivo).prop("disabled", true);
            $('#bandeja_trabajo_destino_origen_atel_'+num_consecutivo).empty();
            $('#bandeja_trabajo_destino_origen_atel_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
        }
    });

    /* Función solo numeros para input tiempo alerta del proceso origen atel */
    /* La funcionalidad de permitir que solo se ingrese una sola cifra decimal está realizada en el archivo funciones_helpers.js */
    $("#tiempo_alerta_origen_atel_" + num_consecutivo).on('input', function () {

        let value = $(this).val();
        // Expresión regular para números con hasta 6 dígitos y hasta 2 decimales
        let regex = /^\d{0,6}(\.\d{0,2})?$/;
        // Verificar si el valor tiene más de un punto decimal o más de 6 dígitos enteros
        let parts = value.split('.');
        let integerPart = parts[0];
        let decimalPart = parts[1];

        if (integerPart.length > 6 || (decimalPart && decimalPart.length > 2) || value.split('.').length > 2) {
            $(this).val(value.slice(0, -1)); // Elimina el último carácter si no coincide con el regex o si tiene más de 6 enteros o más de 2 decimales
        }
    });
    // Funcion que permite solo numeros enteros del 0 al 100

    $("#porcentaje_alerta_naranja_origen_atel_" + num_consecutivo).on('input', function () {
        $("#porcentaje_alerta_roja_origen_atel_" + num_consecutivo).val('');  
        let porcentaje_alerta_naranja = $(this).val();    
        // Remover caracteres que no son dígitos
        porcentaje_alerta_naranja = porcentaje_alerta_naranja.replace(/\D/g, '');    
        // Limitar el rango de 0 a 100
        if (porcentaje_alerta_naranja !== '') {
            let intporcentaje_alerta_naranja = parseInt(porcentaje_alerta_naranja, 10);
            if (intporcentaje_alerta_naranja > 100) {
                porcentaje_alerta_naranja = '100';
            } else if (intporcentaje_alerta_naranja < 0) {
                porcentaje_alerta_naranja = '0';
            }
        }    
        // Establecer el valor corregido en el input    
        $(this).val(porcentaje_alerta_naranja);
    })

    let tiempo_alerta_rojas;

    $("#porcentaje_alerta_roja_origen_atel_" + num_consecutivo).on('input', function () {
        clearTimeout(tiempo_alerta_rojas);
        tiempo_alerta_rojas = setTimeout(() => {
            let porcentaje_alerta_naranja = $("#porcentaje_alerta_naranja_origen_atel_" + num_consecutivo).val();
            let porcentaje_alerta_roja = $(this).val();
            // Remover caracteres que no son dígitos
            porcentaje_alerta_roja = porcentaje_alerta_roja.replace(/\D/g, '');
            // Limitar a 3 dígitos
            if (porcentaje_alerta_roja.length > 3) {
                porcentaje_alerta_roja = porcentaje_alerta_roja.substring(0, 3);
            }
            // Limitar el rango desde el porcentaje de alerta naranja a 100
            if (porcentaje_alerta_roja !== '') {
                let intporcentaje_alerta_roja = parseInt(porcentaje_alerta_roja, 10);
                if (intporcentaje_alerta_roja > 100) {
                    porcentaje_alerta_roja = '100';
                } else if (intporcentaje_alerta_roja < porcentaje_alerta_naranja) {
                    porcentaje_alerta_roja = porcentaje_alerta_naranja;
                }
            }
            // Establecer el valor corregido en el input
            $(this).val(porcentaje_alerta_roja);
        }, 1500);
    });

    // Prevenir entrada de caracteres no numéricos y limitar a 3 dígitos
    $("#porcentaje_alerta_roja_origen_atel_" + num_consecutivo).on('keydown', function (e) {
        let value = $(this).val();
        // Permitir: backspace, delete, tab, escape, enter y .
        if ($.inArray(e.key, ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', '.']) !== -1 ||
            // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.ctrlKey === true && (e.key === 'a' || e.key === 'c' || e.key === 'v' || e.key === 'x')) ||
            // Permitir: home, end, left, right, down, up
            (e.key >= 'Home' && e.key <= 'End')) {
            return;
        }
        // Prevenir entrada si la longitud actual es de 3 dígitos
        if (value.length >= 3 && (e.key >= '0' && e.key <= '9')) {
            e.preventDefault();
        }
        // Prevenir: cualquier otro carácter que no sea número
        if ((e.key < '0' || e.key > '9')) {
            e.preventDefault();
        }
    });

    // mantenemos el input fecha actualización movimiento disabled en todo movimiento
    $("#fecha_actualizacion_movimiento_origen_atel_"+num_consecutivo).prop('disabled', true);

    /* Funcionalidad para editar la fila */
    $('#editar_fila_origen_atel_'+num_consecutivo).click(function() {
        var row = $(this).closest('tr');
        row.find('input, textarea, select').prop('readonly', false).prop('disabled', false);
        row.find('#editar_fila_origen_atel_'+num_consecutivo).addClass('d-none');
        row.find('#guardar_fila_origen_atel_'+num_consecutivo).removeClass('d-none');
    });

    /* Funcionalidad para guardar los datos e insertarlo y/o actualizarlos en la tabla de
    sigmel_informacion_parametrizaciones_clientes */
    $('#guardar_fila_origen_atel_'+num_consecutivo).click(function() {
        // Capturamos los datos de cada tr
        var row = $(this).closest('tr');
        // A todos los input, textarea, select se les adiciona las propiedades readonlu y disabled
        row.find('input, textarea, select').prop('readonly', true).prop('disabled', true);
        // mostrar el botón para editar de nuevo
        row.find('#editar_fila_origen_atel_'+num_consecutivo).removeClass('d-none');
        row.find('#guardar_fila_origen_atel_'+num_consecutivo).addClass('d-none');

        // Inicializamos un objeto para almacenar los valores de la fila
        var datos_cada_fila_proceso_origen_atel = {};

        // Recorre todas las celdas de la fila
        row.find('td').each(function() {
            // Obtén el valor de la celda y luego cada id correspondiente
            var cell = $(this);
            var input = cell.find('input, textarea, select');
            var fieldName = input.attr('id'); 

            // Se valida los checkbox para verificar si fueron marcados o no.
            if (input.is(':checkbox')) {
                datos_cada_fila_proceso_origen_atel[fieldName] = input.is(':checked') ? 'Si' : 'No';

            } else if (input.val() !== undefined ) {
                datos_cada_fila_proceso_origen_atel[fieldName] = input.val();
            }

            // // Recorre cada elemento dentro de la celda
            // cell.find('input, textarea, select').each(function() {
            //     // Obtén el elemento actual
            //     var input = $(this);
            //     var fieldName = input.attr('id'); 

            //     // Se valida los checkbox para verificar si fueron marcados o no.
            //     if (input.is(':checkbox')) {
            //         datos_cada_fila_proceso_origen_atel[fieldName] = input.is(':checked') ? 'Si' : 'No';
            //     } else if (input.val() !== undefined ) {
            //         datos_cada_fila_proceso_origen_atel[fieldName] = input.val();
            //     }
            // });
        });

        // Convierte el objeto en un array para mejor manejo de los datos
        var array_datos_fila_parametrizacion_origen_atel = $.map(datos_cada_fila_proceso_origen_atel, function(value, key) {
            return { nombre: key, valor: value };
        });
        // console.log(array_datos_fila_parametrizacion_origen_atel);
        // Enviamos la información para insertar y/o actualizar
        let enviar_informacion_parametrizacion_origen_atel = {
            '_token': token,
            'array_datos_fila_parametrizacion_origen_atel' : array_datos_fila_parametrizacion_origen_atel,
            'Id_cliente': $("#Id_cliente").val(),
        };
        $.ajax({
            type:'POST',
            url:'/EnvioParametrizacionOrigenAtel',
            data: enviar_informacion_parametrizacion_origen_atel,
            success:function(response){
                if (response.parametro == "agrego_parametrizacion") {
                    $("#mostrar_mensaje_agrego_parametrizacion_origen_atel").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_origen_atel").addClass('alert-success');
                    $(".mensaje_agrego_parametrizacion_origen_atel").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_origen_atel").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_origen_atel").removeClass('alert-success');
                        $(".mensaje_agrego_parametrizacion_origen_atel").empty();
                        window.location.reload();
                    }, 3000);
                }else{
                    $("#mostrar_mensaje_agrego_parametrizacion_origen_atel").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_origen_atel").addClass('alert-danger');
                    $(".mensaje_agrego_parametrizacion_origen_atel").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_origen_atel").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_origen_atel").removeClass('alert-danger');
                        $(".mensaje_agrego_parametrizacion_origen_atel").empty();
                        window.location.reload();
                    }, 3000);
                }
            }
        });


    });

};

function edicion_parametrizacion_origen_atel(id_parametrizacion_origen_atel_editar){
    /* INICIALIZACIÓN SELECT 2 LISTADO DE SERVICIOS ASOCIADOS */
    $(".bd_servicio_asociado_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO ESTADOS */
    $(".bd_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES A EJECUTAR */
    $(".bd_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO EQUIPOS DE TRABAJO */
    $(".bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE PROFESIONALES */
    $(".bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES ANTECESORAS */
    $(".bd_accion_antecesora_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO BANDEJA TRABAJO DESTINO */
    $(".bd_bandeja_trabajo_destino_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES AUTOMATICAS */
    $(".bd_accion_automatica_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 STATUS PARAMETRICO */
    $(".bd_status_parametrico_origen_atel_"+id_parametrizacion_origen_atel_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();
    var Id_cliente = $("#Id_cliente").val();

    /* Carga del selector servicios asociados del proceso origen atel */
    let datos_servicios_asociados_origen_atel = {
        '_token': token,
        'parametro' : "servicios_asociados_proceso_origen_atel",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_servicios_asociados_origen_atel,
        success:function(data){
            $("#bd_servicio_asociado_origen_atel_"+id_parametrizacion_origen_atel_editar).empty();
            $("#bd_servicio_asociado_origen_atel_"+id_parametrizacion_origen_atel_editar).append("<option></option>");
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_servicio"] == $("#bd_id_servicio_asociado_origen_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                    $("#bd_servicio_asociado_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_servicio"]+'" selected>'+data[claves[i]]["Nombre_servicio"]+'</option>');
                } else {
                    $("#bd_servicio_asociado_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de estados del proceso origen atel */
    let datos_estados_origen_atel = {
        '_token': token,
        'parametro' : "lista_estados",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estados_origen_atel,
        success:function(data){
            $("#bd_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).empty();
            $("#bd_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).append("<option></option>");
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_Parametro"] == $("#bd_id_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                    $("#bd_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_Parametro"]+'" selected>'+data[claves[i]]["Nombre_parametro"]+'</option>');
                } else {
                    $("#bd_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de acciones a ejecutar dependiendo de la seleccion del estado del proceso origen atel apenas carga */
    let datos_acciones_ejecutar_origen_atel = {
        '_token': token,
        'parametro': "acciones_ejecutar_proceso_origen_atel",
        'Id_cliente': Id_cliente,
        'id_accion_seleccionada_origen_atel': $("#bd_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).val()
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_acciones_ejecutar_origen_atel,
        success:function(data){
            $('#bd_accion_ejecutar_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
            $('#bd_accion_ejecutar_origen_atel_'+id_parametrizacion_origen_atel_editar).append('<option value="0" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_Accion"] == $("#bd_id_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                    $("#bd_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                } else {
                    $("#bd_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });

    validacionesNotificaciones(id_parametrizacion_origen_atel_editar,'origen');

    /* Carga del selector de acciones a ejecutar dependiendo de la seleccion del estado del proceso origen atel evento change */
    $("#bd_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).change(function(){
        $("#bd_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).prop('disabled', false);
        let id_accion_seleccionada_origen_atel = $("#bd_estado_origen_atel_"+id_parametrizacion_origen_atel_editar).val();
        let datos_acciones_ejecutar_origen_atel = {
            '_token': token,
            'parametro': "acciones_ejecutar_proceso_origen_atel",
            'Id_cliente': Id_cliente,
            'id_accion_seleccionada_origen_atel': id_accion_seleccionada_origen_atel
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_ejecutar_origen_atel,
            success:function(data){
                $('#bd_accion_ejecutar_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
                $('#bd_accion_ejecutar_origen_atel_'+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de equipos de trabajo dependiendo  del proceso origen atel*/   
    let datos_equipos_trabajo_origen_atel = {
        '_token': token,
        'parametro' : "equipos_trabajo_proceso_origen_atel",
        // 'id_accion_seleccionada': $("#bd_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).val()
    };
    
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_equipos_trabajo_origen_atel,
        success:function(data){
            $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).empty();
            $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["id"] == $("#bd_id_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                    $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["id"]+'" selected>'+data[claves[i]]["nombre"]+'</option>');
                } else {
                    $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de equipos de trabajo dependiendo del proceso origen atel evento change */
    // $("#bd_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).change(function(){
    //     $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).prop('disabled', false);
    //     // let id_accion = $("#bd_accion_ejecutar_origen_atel_"+id_parametrizacion_origen_atel_editar).val();
    
    //     let datos_equipos_trabajo_origen_atel = {
    //         '_token': token,
    //         'parametro' : "equipos_trabajo_proceso_origen_atel",
    //         // 'id_accion_seleccionada': id_accion
    //     };
        
    //     $.ajax({
    //         type:'POST',
    //         url:'/CargueSelectoresParametrizar',
    //         data: datos_equipos_trabajo_origen_atel,
    //         success:function(data){
    //             $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).empty();
    //             $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
    //             let claves = Object.keys(data);
    //             for (let i = 0; i < claves.length; i++) {
    //                 $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
    //             }
    //         }
    //     });
    // });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo apenas carga */
    let datos_listado_profesionales_origen_atel = {
        '_token': token,
        'parametro' : "listado_profesionales_proceso_origen_atel",
        'id_equipo_seleccionado': $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).val()
    };
    
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_listado_profesionales_origen_atel,
        success:function(data){
            $("#bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).empty();
            $("#bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["id"] == $("#bd_id_profesional_asignado_origen_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                    $("#bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["id"]+'" selected>'+data[claves[i]]["nombre"]+'</option>');
                } else {
                    $("#bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo evento change */
    $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).change(function(){
        $("#bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).prop('disabled', false);
        let id_equipo_seleccionado = $("#bd_equipo_trabajo_origen_atel_"+id_parametrizacion_origen_atel_editar).val();
        let datos_listado_profesionales_origen_atel = {
            '_token': token,
            'parametro' : "listado_profesionales_proceso_origen_atel",
            'id_equipo_seleccionado': id_equipo_seleccionado
        };

        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_listado_profesionales_origen_atel,
            success:function(data){
                $("#bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).empty();
                $("#bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_listado_profesionales_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado apenas carga */
    let datos_accion_antecesora_origen_atel = {
        '_token': token,
        'parametro' : "acciones_antecesoras_proceso_origen_atel",
        'servicio_asociado_origen_atel':$("#bd_servicio_asociado_origen_atel_"+id_parametrizacion_origen_atel_editar).val(),
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_accion_antecesora_origen_atel,
        success:function(data){
            //$('#bd_accion_antecesora_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
            //$('#bd_accion_antecesora_origen_atel_'+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_Accion"] != $("#bd_accion_antecesora_origen_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                    $("#bd_accion_antecesora_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado evento change */
    $("#bd_servicio_asociado_origen_atel_"+id_parametrizacion_origen_atel_editar).change(function(){
        var id_servicio_asociado_origen_atel = $(this).val();
        let datos_accion_antecesora_origen_atel = {
            '_token': token,
            'parametro' : "acciones_antecesoras_proceso_origen_atel",
            'servicio_asociado_origen_atel':id_servicio_asociado_origen_atel,
            'Id_cliente': Id_cliente
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_accion_antecesora_origen_atel,
            success:function(data){
                $('#bd_accion_antecesora_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
                $('#bd_accion_antecesora_origen_atel_'+id_parametrizacion_origen_atel_editar).append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_antecesora_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
        /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del proceso origen atel apenas carga */
        let datos_acciones_automaticas_origen_atel = {
            '_token': token,
            'parametro': "acciones_automaticas_proceso_origen_atel",
            'Id_cliente': Id_cliente,
            'Servicio_asociado_origen': id_servicio_asociado_origen_atel,
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_automaticas_origen_atel,
            success:function(data){
                $('#bd_accion_automatica_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
                $('#bd_accion_automatica_origen_atel_'+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_automatica_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');                    
                }
            }
        });
    });

    /* Carga del selector de status parametrica proceso origen atel*/
    let datos_estatus_parametrica_origen = {
        '_token': token,
        'parametro' : "estatus_parametrica"
    };
    
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estatus_parametrica_origen,
        success:function(data){
            $("#bd_status_parametrico_origen_atel_"+id_parametrizacion_origen_atel_editar).empty();
            $("#bd_status_parametrico_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Nombre_parametro"] == $("#bd_id_status_parametrico_origen_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                    $("#bd_status_parametrico_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Nombre_parametro"]+'" selected>'+data[claves[i]]["Nombre_parametro"]+'</option>');
                } else {
                    $("#bd_status_parametrico_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    /* Habilitar el selector de bandeja de trabajo destino cuando se checkea el checkbox de enviar del proceso origen atel */
    $("#bd_enviar_a_origen_atel_"+id_parametrizacion_origen_atel_editar).click(function(){
        if($(this).is(':checked')){
            $("#bd_bandeja_trabajo_destino_origen_atel_"+id_parametrizacion_origen_atel_editar).prop("disabled", false);

            let datos_bandeja_trabajo_destino_origen_atel = {
                '_token': token,
                'parametro': "bandeja_trabajo_destino_notificaciones",
                'Id_cliente': Id_cliente,
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_bandeja_trabajo_destino_origen_atel,
                success:function(data){
                    $('#bd_bandeja_trabajo_destino_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
                    $("#bd_bandeja_trabajo_destino_origen_atel_"+id_parametrizacion_origen_atel_editar).append(`<option value="${data.Id_proceso}" selected>${data.Nombre_proceso}</option>`);
                }
            });

        }else{
            $("#bd_bandeja_trabajo_destino_origen_atel_"+id_parametrizacion_origen_atel_editar).prop("disabled", true);
            $('#bd_bandeja_trabajo_destino_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
            $('#bd_bandeja_trabajo_destino_origen_atel_'+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
        }
    });

    /* Función solo numeros para input tiempo alerta del proceso origen atel */
    /* La funcionalidad de permitir que solo se ingrese una sola cifra decimal está realizada en el archivo funciones_helpers.js */
    $("#bd_tiempo_alerta_origen_atel_" + id_parametrizacion_origen_atel_editar).on('input', function () {
        
        let value = $(this).val();
        // Expresión regular para números con hasta 6 dígitos y hasta 2 decimales
        let regex = /^\d{0,6}(\.\d{0,2})?$/;
        // Verificar si el valor tiene más de un punto decimal o más de 6 dígitos enteros
        let parts = value.split('.');
        let integerPart = parts[0];
        let decimalPart = parts[1];

        if (integerPart.length > 6 || (decimalPart && decimalPart.length > 2) || value.split('.').length > 2) {
            $(this).val(value.slice(0, -1)); // Elimina el último carácter si no coincide con el regex o si tiene más de 6 enteros o más de 2 decimales
        }
    });
    
    // Funcion que permite solo numeros enteros del 0 al 100 en porcentaje alerta naranja

    $("#bd_porcentaje_alerta_naranja_origen_atel_" + id_parametrizacion_origen_atel_editar).on('input', function () {     
        $("#bd_porcentaje_alerta_roja_origen_atel_" + id_parametrizacion_origen_atel_editar).val('');  
        let porcentaje_alerta_naranja = $(this).val();    
        // Remover caracteres que no son dígitos
        porcentaje_alerta_naranja = porcentaje_alerta_naranja.replace(/\D/g, '');    
        // Limitar el rango de 0 a 100
        if (porcentaje_alerta_naranja !== '') {
            let intporcentaje_alerta_naranja = parseInt(porcentaje_alerta_naranja, 10);
            if (intporcentaje_alerta_naranja > 100) {
                porcentaje_alerta_naranja = '100';
            } else if (intporcentaje_alerta_naranja < 0) {
                porcentaje_alerta_naranja = '0';
            }
        }    
        // Establecer el valor corregido en el input
        $(this).val(porcentaje_alerta_naranja);
    });

    // funcion para el rango  en el porcentaje de alerta roja
    let tiempo_alerta_roja_editar;

    $("#bd_porcentaje_alerta_roja_origen_atel_" + id_parametrizacion_origen_atel_editar).on('input', function () {
        clearTimeout(tiempo_alerta_roja_editar);
        tiempo_alerta_roja_editar = setTimeout(() => {
            let porcentaje_alerta_naranja = $("#bd_porcentaje_alerta_naranja_origen_atel_" + id_parametrizacion_origen_atel_editar).val();
            let porcentaje_alerta_roja = $(this).val();
            // Remover caracteres que no son dígitos
            porcentaje_alerta_roja = porcentaje_alerta_roja.replace(/\D/g, '');
            // Limitar a 3 dígitos
            if (porcentaje_alerta_roja.length > 3) {
                porcentaje_alerta_roja = porcentaje_alerta_roja.substring(0, 3);
            }
            // Limitar el rango desde el porcentaje de alerta naranja a 100
            if (porcentaje_alerta_roja !== '') {
                let intporcentaje_alerta_roja = parseInt(porcentaje_alerta_roja, 10);
                if (intporcentaje_alerta_roja > 100) {
                    porcentaje_alerta_roja = '100';
                } else if (intporcentaje_alerta_roja < porcentaje_alerta_naranja) {
                    porcentaje_alerta_roja = porcentaje_alerta_naranja;
                }
            }
            // Establecer el valor corregido en el input
            $(this).val(porcentaje_alerta_roja);
        }, 1500);
    });

    // Prevenir entrada de caracteres no numéricos y limitar a 3 dígitos
    $("#bd_porcentaje_alerta_roja_origen_atel_" + id_parametrizacion_origen_atel_editar).on('keydown', function (e) {
        let value = $(this).val();
        // Permitir: backspace, delete, tab, escape, enter y .
        if ($.inArray(e.key, ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', '.']) !== -1 ||
            // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.ctrlKey === true && (e.key === 'a' || e.key === 'c' || e.key === 'v' || e.key === 'x')) ||
            // Permitir: home, end, left, right, down, up
            (e.key >= 'Home' && e.key <= 'End')) {
            return;
        }
        // Prevenir entrada si la longitud actual es de 3 dígitos
        if (value.length >= 3 && (e.key >= '0' && e.key <= '9')) {
            e.preventDefault();
        }
        // Prevenir: cualquier otro carácter que no sea número
        if ((e.key < '0' || e.key > '9')) {
            e.preventDefault();
        }
    });

    let Servicio_asociado_origen_db_editar = $('#bd_servicio_asociado_origen_atel_'+id_parametrizacion_origen_atel_editar).val();
    /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del proceso origen atel apenas carga */
    let datos_acciones_automaticas_origen_atel = {
        '_token': token,
        'parametro': "acciones_automaticas_proceso_origen_atel",
        'Id_cliente': Id_cliente,
        'Servicio_asociado_origen': Servicio_asociado_origen_db_editar,
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_acciones_automaticas_origen_atel,
        success:function(data){
            $('#bd_accion_automatica_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
            $('#bd_accion_automatica_origen_atel_'+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Accion_ejecutar"] == $("#bd_id_accion_automatica_orgien_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                    $("#bd_accion_automatica_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                } else {
                    $("#bd_accion_automatica_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });

    /* HABILITAR CAMPOS DE TIEMPO DE MOVIMIENTO Y ACCION AUTOMATICA*/

    $("[id^='bd_movimiento_automatico_origen_atel_']").change(function () {
        let id_fila_parametrizacion_movimiento_editar = $(this).data("id_movimiento_automatico_origen_atel");
        let Servicio_asociado_origen_editar = $('#bd_servicio_asociado_origen_atel_'+id_parametrizacion_origen_atel_editar).val();
        if ($(this).is(':checked')) {
            // console.log('id movi editar es: '+id_fila_parametrizacion_movimiento_editar);   
            $('#bd_tiempo_movimiento_origen_atel_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', false);
            $('#bd_accion_automatica_origen_atel_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', false); 
            
            /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del proceso origen atel apenas carga */
            let datos_acciones_automaticas_origen_atel = {
                '_token': token,
                'parametro': "acciones_automaticas_proceso_origen_atel",
                'Id_cliente': Id_cliente,
                'Servicio_asociado_origen': Servicio_asociado_origen_editar
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_acciones_automaticas_origen_atel,
                success:function(data){
                    $('#bd_accion_automatica_origen_atel_'+id_parametrizacion_origen_atel_editar).empty();
                    $('#bd_accion_automatica_origen_atel_'+id_parametrizacion_origen_atel_editar).append('<option value="" selected>Seleccione</option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        if (data[claves[i]]["Accion_ejecutar"] == $("#bd_id_accion_automatica_orgien_atel_"+id_parametrizacion_origen_atel_editar).val()) {
                            $("#bd_accion_automatica_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                        } else {
                            $("#bd_accion_automatica_origen_atel_"+id_parametrizacion_origen_atel_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                        }
                    }
                }
            });
            
        } else {
            $("#bd_tiempo_movimiento_origen_atel_"+id_fila_parametrizacion_movimiento_editar).prop("disabled", true);
            $('#bd_tiempo_movimiento_origen_atel_'+id_fila_parametrizacion_movimiento_editar).val('');
            $('#bd_accion_automatica_origen_atel_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', true);
            $('#bd_accion_automatica_origen_atel_'+id_fila_parametrizacion_movimiento_editar).empty();
            $('#bd_accion_automatica_origen_atel_'+id_fila_parametrizacion_movimiento_editar).append('<option value="" selected>Seleccione</option>');                
        }
    });  

};

function funciones_elementos_fila_parametrizar_calificacion_pcl(num_consecutivo){
    
    /* INICIALIZACIÓN SELECT 2 LISTADO DE SERVICIOS ASOCIADOS */
    $(".servicio_asociado_calificacion_pcl_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO ESTADOS */
    $(".estado_calificacion_pcl_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES A EJECUTAR */
    $(".accion_ejecutar_calificacion_pcl_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES ANTECESORAS */
    $(".accion_antecesora_calificacion_pcl_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO EQUIPOS DE TRABAJO */
    $(".equipo_trabajo_calificacion_pcl_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO BANDEJA TRABAJO DESTINO */
    $(".bandeja_trabajo_destino_calificacion_pcl_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES AUTOMATICAS */
    $(".accion_automatica_calificacion_pcl"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE PROFESIONALES */
    $(".listado_profesionales_calificacion_pcl_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 STATUS PARAMETRICO */
    $(".status_parametrico_calificacion_pcl_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();
    var Id_cliente = $("#Id_cliente").val();

    /* Carga del selector servicios asociados del proceso calificacion pcl */
    let datos_servicios_asociados_calificacion_pcl = {
        '_token': token,
        'parametro' : "servicios_asociados_proceso_calificacion_pcl",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_servicios_asociados_calificacion_pcl,
        success:function(data){
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#servicio_asociado_calificacion_pcl_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
            }
        }
    });

    /* Carga del selector de estados del proceso calificacion pcl */
    let datos_estados_calificacion_pcl = {
        '_token': token,
        'parametro' : "lista_estados",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estados_calificacion_pcl,
        success:function(data){
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#estado_calificacion_pcl_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    /* Carga del selector de acciones a ejecutar dependiendo de la seleccion del estado del proceso calificacion pcl */
    $("#estado_calificacion_pcl_"+num_consecutivo).change(function(){
        $("#accion_ejecutar_calificacion_pcl_"+num_consecutivo).prop('disabled', false);
        let id_accion_seleccionada_calificacion_pcl = $("#estado_calificacion_pcl_"+num_consecutivo).val();
        let datos_acciones_ejecutar_calificacion_pcl = {
            '_token': token,
            'parametro': "acciones_ejecutar_proceso_calificacion_pcl",
            'Id_cliente': Id_cliente,
            'id_accion_seleccionada_calificacion_pcl': id_accion_seleccionada_calificacion_pcl
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_ejecutar_calificacion_pcl,
            success:function(data){
                $('#accion_ejecutar_calificacion_pcl_'+num_consecutivo).empty();
                $('#accion_ejecutar_calificacion_pcl_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_ejecutar_calificacion_pcl_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de equipos de trabajo dependiendo del proceso calificacion pcl */    
    $("#accion_ejecutar_calificacion_pcl_"+num_consecutivo).change(function(){
        $("#equipo_trabajo_calificacion_pcl_"+num_consecutivo).prop('disabled', false);
        // let id_accion = $("#accion_ejecutar_calificacion_pcl_"+num_consecutivo).val();
        validacionesNotificaciones(num_consecutivo,'pcl','');

        let datos_equipos_trabajo_calificacion_pcl = {
            '_token': token,
            'parametro' : "equipos_trabajo_proceso_calificacion_pcl",
            // 'id_accion_seleccionada': id_accion
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_equipos_trabajo_calificacion_pcl,
            success:function(data){
                $('#equipo_trabajo_calificacion_pcl_'+num_consecutivo).empty();
                $('#equipo_trabajo_calificacion_pcl_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#equipo_trabajo_calificacion_pcl_"+num_consecutivo).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo del proceso calificacion pcl */
    $("#equipo_trabajo_calificacion_pcl_"+num_consecutivo).change(function(){
        $("#listado_profesionales_calificacion_pcl_"+num_consecutivo).prop('disabled', false);
        let id_equipo_seleccionado = $("#equipo_trabajo_calificacion_pcl_"+num_consecutivo).val();
        let datos_listado_profesionales_calificacion_pcl = {
            '_token': token,
            'parametro' : "listado_profesionales_proceso_calificacion_pcl",
            'id_equipo_seleccionado': id_equipo_seleccionado
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_listado_profesionales_calificacion_pcl,
            success:function(data){
                $('#listado_profesionales_calificacion_pcl_'+num_consecutivo).empty();
                $('#listado_profesionales_calificacion_pcl_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#listado_profesionales_calificacion_pcl_"+num_consecutivo).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado */
    $("#servicio_asociado_calificacion_pcl_"+num_consecutivo).change(function(){
        var id_servicio_asociado_calificacion_pcl = $(this).val();
        let datos_accion_antecesora_calificacion_pcl = {
            '_token': token,
            'parametro' : "acciones_antecesoras_proceso_calificacion_pcl",
            'servicio_asociado_calificacion_pcl':id_servicio_asociado_calificacion_pcl,
            'Id_cliente': Id_cliente
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_accion_antecesora_calificacion_pcl,
            success:function(data){
                $('#accion_antecesora_calificacion_pcl_'+num_consecutivo).empty();
                $('#accion_antecesora_calificacion_pcl_'+num_consecutivo).append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_antecesora_calificacion_pcl_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });

        /* Carga del selector de acciones automaticas dependiendo del servicio seleccionado*/        
        let datos_acciones_automaticas_calificacion_pcl = {
            '_token': token,
            'parametro': "acciones_automaticas_proceso_calificacion_pcl",
            'Id_cliente': Id_cliente,
            'Servicio_asociado_pcl': id_servicio_asociado_calificacion_pcl
        };
        // console.log(datos_acciones_automaticas_calificacion_pcl);
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_automaticas_calificacion_pcl,
            success:function(data){
                $('#accion_automatica_calificacion_pcl_'+num_consecutivo).empty();
                $('#accion_automatica_calificacion_pcl_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_automatica_calificacion_pcl_"+num_consecutivo).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* HABILITAR CAMPOS DE TIEMPO DE MOVIMIENTO Y ACCION AUTOMATICA*/

    $("[id^='movimiento_automatico_calificacion_pcl_']").change(function () {        
        let id_fila_parametrizacion_movimiento = $(this).data("id_movimiento_automatico_calificacion_pcl");
        let servicio_asociado_pcl = $('#servicio_asociado_calificacion_pcl_'+num_consecutivo).val();
        if ($(this).is(':checked')) {
            // console.log('id movi editar es: '+id_fila_parametrizacion_movimiento_editar);   
            $('#tiempo_movimiento_calificacion_pcl_'+id_fila_parametrizacion_movimiento).prop('disabled', false);
            $('#accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_movimiento).prop('disabled', false); 
            
            /* Carga del selector de acciones automaticas dependiendo del estado seleccionado*/        
            let datos_acciones_automaticas_calificacion_pcl = {
                '_token': token,
                'parametro': "acciones_automaticas_proceso_calificacion_pcl",
                'Id_cliente': Id_cliente,
                'Servicio_asociado_pcl': servicio_asociado_pcl
            };
            // console.log(datos_acciones_automaticas_calificacion_pcl);
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_acciones_automaticas_calificacion_pcl,
                success:function(data){
                    $('#accion_automatica_calificacion_pcl_'+num_consecutivo).empty();
                    $('#accion_automatica_calificacion_pcl_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $("#accion_automatica_calificacion_pcl_"+num_consecutivo).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                    }
                }
            });                
        }else{
            $("#tiempo_movimiento_calificacion_pcl_"+id_fila_parametrizacion_movimiento).prop("disabled", true);
            $('#tiempo_movimiento_calificacion_pcl_'+id_fila_parametrizacion_movimiento).val('');
            $('#accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_movimiento).prop('disabled', true);
            $('#accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_movimiento).empty();
            $('#accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_movimiento).append('<option value="" selected>Seleccione</option>');                 
        }
    }); 

    /* Habilitar el selector de bandeja de trabajo destino cuando se checkea el checkbox de enviar del proceso calificacion pcl */
    $("#enviar_a_calificacion_pcl_"+num_consecutivo).click(function(){
        if($(this).is(':checked')){
            $("#bandeja_trabajo_destino_calificacion_pcl_"+num_consecutivo).prop("disabled", false);

            let datos_bandeja_trabajo_destino_calificacion_pcl = {
                '_token': token,
                'parametro': "bandeja_trabajo_destino_notificaciones",
                'Id_cliente': Id_cliente,
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_bandeja_trabajo_destino_calificacion_pcl,
                success:function(data){
                    $('#bandeja_trabajo_destino_calificacion_pcl_'+num_consecutivo).empty();
                    $("#bandeja_trabajo_destino_calificacion_pcl_"+num_consecutivo).append(`<option value="${data.Id_proceso}" selected>${data.Nombre_proceso}</option>`);
                }
            });

        }else{
            $("#bandeja_trabajo_destino_calificacion_pcl_"+num_consecutivo).prop("disabled", true);
            $('#bandeja_trabajo_destino_calificacion_pcl_'+num_consecutivo).empty();
            $('#bandeja_trabajo_destino_calificacion_pcl_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
        }
    });

    /* Función solo numeros para input tiempo alerta del proceso calificacion pcl */
    /* La funcionalidad de permitir que solo se ingrese una sola cifra decimal está realizada en el archivo funciones_helpers.js */
    $("#tiempo_alerta_calificacion_pcl_" + num_consecutivo).on('input', function () {        
        let value = $(this).val();
        // Expresión regular para números con hasta 6 dígitos y hasta 2 decimales
        let regex = /^\d{0,6}(\.\d{0,2})?$/;
        // Verificar si el valor tiene más de un punto decimal o más de 6 dígitos enteros
        let parts = value.split('.');
        let integerPart = parts[0];
        let decimalPart = parts[1];

        if (integerPart.length > 6 || (decimalPart && decimalPart.length > 2) || value.split('.').length > 2) {
            $(this).val(value.slice(0, -1)); // Elimina el último carácter si no coincide con el regex o si tiene más de 6 enteros o más de 2 decimales
        }
    });
    // Funcion que permite solo numeros enteros del 0 al 100

    $("#porcentaje_alerta_naranja_calificacion_pcl_" + num_consecutivo).on('input', function () {
        $("#porcentaje_alerta_roja_calificacion_pcl_" + num_consecutivo).val('');  
        let porcentaje_alerta_naranja = $(this).val();    
        // Remover caracteres que no son dígitos
        porcentaje_alerta_naranja = porcentaje_alerta_naranja.replace(/\D/g, '');    
        // Limitar el rango de 0 a 100
        if (porcentaje_alerta_naranja !== '') {
            let intporcentaje_alerta_naranja = parseInt(porcentaje_alerta_naranja, 10);
            if (intporcentaje_alerta_naranja > 100) {
                porcentaje_alerta_naranja = '100';
            } else if (intporcentaje_alerta_naranja < 0) {
                porcentaje_alerta_naranja = '0';
            }
        }    
        // Establecer el valor corregido en el input    
        $(this).val(porcentaje_alerta_naranja);
    })

    let tiempo_alerta_rojas;

    $("#porcentaje_alerta_roja_calificacion_pcl_" + num_consecutivo).on('input', function () {
        clearTimeout(tiempo_alerta_rojas);
        tiempo_alerta_rojas = setTimeout(() => {
            let porcentaje_alerta_naranja = $("#porcentaje_alerta_naranja_calificacion_pcl_" + num_consecutivo).val();
            let porcentaje_alerta_roja = $(this).val();
            // Remover caracteres que no son dígitos
            porcentaje_alerta_roja = porcentaje_alerta_roja.replace(/\D/g, '');
            // Limitar a 3 dígitos
            if (porcentaje_alerta_roja.length > 3) {
                porcentaje_alerta_roja = porcentaje_alerta_roja.substring(0, 3);
            }
            // Limitar el rango desde el porcentaje de alerta naranja a 100
            if (porcentaje_alerta_roja !== '') {
                let intporcentaje_alerta_roja = parseInt(porcentaje_alerta_roja, 10);
                if (intporcentaje_alerta_roja > 100) {
                    porcentaje_alerta_roja = '100';
                } else if (intporcentaje_alerta_roja < porcentaje_alerta_naranja) {
                    porcentaje_alerta_roja = porcentaje_alerta_naranja;
                }
            }
            // Establecer el valor corregido en el input
            $(this).val(porcentaje_alerta_roja);
        }, 1500);
    });

    // Prevenir entrada de caracteres no numéricos y limitar a 3 dígitos
    $("#porcentaje_alerta_roja_calificacion_pcl_" + num_consecutivo).on('keydown', function (e) {
        let value = $(this).val();
        // Permitir: backspace, delete, tab, escape, enter y .
        if ($.inArray(e.key, ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', '.']) !== -1 ||
            // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.ctrlKey === true && (e.key === 'a' || e.key === 'c' || e.key === 'v' || e.key === 'x')) ||
            // Permitir: home, end, left, right, down, up
            (e.key >= 'Home' && e.key <= 'End')) {
            return;
        }
        // Prevenir entrada si la longitud actual es de 3 dígitos
        if (value.length >= 3 && (e.key >= '0' && e.key <= '9')) {
            e.preventDefault();
        }
        // Prevenir: cualquier otro carácter que no sea número
        if ((e.key < '0' || e.key > '9')) {
            e.preventDefault();
        }
    });

    // mantenemos el input fecha actualización movimiento disabled en todo movimiento
    $("#fecha_actualizacion_movimiento_calificacion_pcl_"+num_consecutivo).prop('disabled', true);

    /* Funcionalidad para editar la fila */
    $('#editar_fila_calificacion_pcl_'+num_consecutivo).click(function() {
        var row = $(this).closest('tr');
        row.find('input, textarea, select').prop('readonly', false).prop('disabled', false);
        row.find('#editar_fila_calificacion_pcl_'+num_consecutivo).addClass('d-none');
        row.find('#guardar_fila_calificacion_pcl_'+num_consecutivo).removeClass('d-none');
    });

    /* Funcionalidad para guardar los datos e insertarlo y/o actualizarlos en la tabla de
    sigmel_informacion_parametrizaciones_clientes */
    $('#guardar_fila_calificacion_pcl_'+num_consecutivo).click(function() {
        // Capturamos los datos de cada tr
        var row = $(this).closest('tr');
        // A todos los input, textarea, select se les adiciona las propiedades readonlu y disabled
        row.find('input, textarea, select').prop('readonly', true).prop('disabled', true);
        // mostrar el botón para editar de nuevo
        row.find('#editar_fila_calificacion_pcl_'+num_consecutivo).removeClass('d-none');
        row.find('#guardar_fila_calificacion_pcl_'+num_consecutivo).addClass('d-none');

        // Inicializamos un objeto para almacenar los valores de la fila
        var datos_cada_fila_proceso_calificacion_pcl = {};

        // Recorre todas las celdas de la fila
        row.find('td').each(function() {
            // Obtén el valor de la celda y luego cada id correspondiente
            var cell = $(this);
            var input = cell.find('input, textarea, select');
            var fieldName = input.attr('id'); 

            // Se valida los checkbox para verificar si fueron marcados o no.
            if (input.is(':checkbox')) {
                datos_cada_fila_proceso_calificacion_pcl[fieldName] = input.is(':checked') ? 'Si' : 'No';

            } else if (input.val() !== undefined ) {
                datos_cada_fila_proceso_calificacion_pcl[fieldName] = input.val();
            }
        });

        // Convierte el objeto en un array para mejor manejo de los datos
        var array_datos_fila_parametrizacion_calificacion_pcl = $.map(datos_cada_fila_proceso_calificacion_pcl, function(value, key) {
            return { nombre: key, valor: value };
        });

        // Enviamos la información para insertar y/o actualizar
        let enviar_informacion_parametrizacion_calificacion_pcl = {
            '_token': token,
            'array_datos_fila_parametrizacion_calificacion_pcl' : array_datos_fila_parametrizacion_calificacion_pcl,
            'Id_cliente': $("#Id_cliente").val(),
        };
        $.ajax({
            type:'POST',
            url:'/EnvioParametrizacionCalificacionPcl',
            data: enviar_informacion_parametrizacion_calificacion_pcl,
            success:function(response){
                if (response.parametro == "agrego_parametrizacion") {
                    $("#mostrar_mensaje_agrego_parametrizacion_calificacion_pcl").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_calificacion_pcl").addClass('alert-success');
                    $(".mensaje_agrego_parametrizacion_calificacion_pcl").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_calificacion_pcl").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_calificacion_pcl").removeClass('alert-success');
                        $(".mensaje_agrego_parametrizacion_calificacion_pcl").empty();
                        window.location.reload();
                    }, 3000);
                }else{
                    $("#mostrar_mensaje_agrego_parametrizacion_calificacion_pcl").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_calificacion_pcl").addClass('alert-danger');
                    $(".mensaje_agrego_parametrizacion_calificacion_pcl").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_calificacion_pcl").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_calificacion_pcl").removeClass('alert-danger');
                        $(".mensaje_agrego_parametrizacion_calificacion_pcl").empty();
                        window.location.reload();
                    }, 3000);
                }
            }
        });


    });

};

function edicion_parametrizacion_calificacion_pcl(id_parametrizacion_calificacion_pcl_editar){
    /* INICIALIZACIÓN SELECT 2 LISTADO DE SERVICIOS ASOCIADOS */
    $(".bd_servicio_asociado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO ESTADOS */
    $(".bd_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES A EJECUTAR */
    $(".bd_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES ANTECESORAS */
    $(".bd_accion_antecesora_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO EQUIPOS DE TRABAJO */
    $(".bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE PROFESIONALES */
    $(".bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO BANDEJA TRABAJO DESTINO */
    $(".bd_bandeja_trabajo_destino_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES AUTOMATICAS */
    $(".bd_accion_automatica_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });


    /* INICIALIZACIÓN SELECT 2 STATUS PARAMETRICO */
    $(".bd_status_parametrico_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();
    var Id_cliente = $("#Id_cliente").val();

    /* Carga del selector servicios asociados del proceso calificacion pcl */
    let datos_servicios_asociados_calificacion_pcl = {
        '_token': token,
        'parametro' : "servicios_asociados_proceso_calificacion_pcl",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_servicios_asociados_calificacion_pcl,
        success:function(data){
            $("#bd_servicio_asociado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).empty();
            $("#bd_servicio_asociado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append("<option></option>");
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_servicio"] == $("#bd_id_servicio_asociado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                    $("#bd_servicio_asociado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_servicio"]+'" selected>'+data[claves[i]]["Nombre_servicio"]+'</option>');
                } else {
                    $("#bd_servicio_asociado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de estados del proceso calificacion pcl */
    let datos_estados_calificacion_pcl = {
        '_token': token,
        'parametro' : "lista_estados",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estados_calificacion_pcl,
        success:function(data){
            $("#bd_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).empty();
            $("#bd_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append("<option></option>");
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_Parametro"] == $("#bd_id_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                    $("#bd_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_Parametro"]+'" selected>'+data[claves[i]]["Nombre_parametro"]+'</option>');
                } else {
                    $("#bd_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de acciones a ejecutar dependiendo de la seleccion del estado del proceso origen atel */
    let datos_acciones_ejecutar_calificacion_pcl = {
        '_token': token,
        'parametro': "acciones_ejecutar_proceso_calificacion_pcl",
        'Id_cliente': Id_cliente,
        'id_accion_seleccionada_calificacion_pcl': $("#bd_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_acciones_ejecutar_calificacion_pcl,
        success:function(data){
            $('#bd_accion_ejecutar_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
            $('#bd_accion_ejecutar_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_Accion"] == $("#bd_id_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                    $("#bd_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                } else {
                    $("#bd_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });

    validacionesNotificaciones(id_parametrizacion_calificacion_pcl_editar,'pcl');

    /* Carga del selector de equipos de trabajo dependiendo del proceso calificacion pcl apenas carga */
    let datos_equipos_trabajo_calificacion_pcl = {
        '_token': token,
        'parametro' : "equipos_trabajo_proceso_calificacion_pcl",
        // 'id_accion_seleccionada': $("#bd_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()
    };

    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_equipos_trabajo_calificacion_pcl,
        success:function(data){
            $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).empty();
            $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["id"] == $("#bd_id_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                    $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["id"]+'" selected>'+data[claves[i]]["nombre"]+'</option>');
                } else {
                    $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de equipos de trabajo dependiendo  del proceso calificacion pcl evento change */    
    // $("#bd_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).change(function(){
    //     $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).prop('disabled', false);
    //     // let id_accion = $("#bd_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val();

    //     let datos_equipos_trabajo_calificacion_pcl = {
    //         '_token': token,
    //         'parametro' : "equipos_trabajo_proceso_calificacion_pcl",
    //         // 'id_accion_seleccionada': id_accion
    //     };
        
    //     $.ajax({
    //         type:'POST',
    //         url:'/CargueSelectoresParametrizar',
    //         data: datos_equipos_trabajo_calificacion_pcl,
    //         success:function(data){
    //             $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).empty();
    //             $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
    //             let claves = Object.keys(data);
    //             for (let i = 0; i < claves.length; i++) {
    //                 $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
    //             }
    //         }
    //     });
    // });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo apenas carga */
    let datos_listado_profesionales_calificacion_pcl = {
        '_token': token,
        'parametro' : "listado_profesionales_proceso_calificacion_pcl",
        'id_equipo_seleccionado': $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()
    };

    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_listado_profesionales_calificacion_pcl,
        success:function(data){
            $("#bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).empty();
            $("#bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["id"] == $("#bd_id_profesional_asignado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                    $("#bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["id"]+'" selected>'+data[claves[i]]["nombre"]+'</option>');
                } else {
                    $("#bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo evento change */
    $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).change(function(){
        $("#bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).prop('disabled', false);
        let id_equipo_seleccionado = $("#bd_equipo_trabajo_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val();

        let datos_listado_profesionales_calificacion_pcl = {
            '_token': token,
            'parametro' : "listado_profesionales_proceso_calificacion_pcl",
            'id_equipo_seleccionado': id_equipo_seleccionado
        };

        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_listado_profesionales_calificacion_pcl,
            success:function(data){
                $("#bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).empty();
                $("#bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_listado_profesionales_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado apenas carga */
    let datos_accion_antecesora_calificacion_pcl = {
        '_token': token,
        'parametro' : "acciones_antecesoras_proceso_calificacion_pcl",
        'servicio_asociado_calificacion_pcl':$("#bd_servicio_asociado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val(),
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_accion_antecesora_calificacion_pcl,
        success:function(data){
            //$('#bd_accion_antecesora_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
            //$('#bd_accion_antecesora_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                
                if (data[claves[i]]["Id_Accion"] != $("#bd_accion_antecesora_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                    $("#bd_accion_antecesora_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado evento change */
    $("#bd_servicio_asociado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).change(function(){
        var id_servicio_asociado_calificacion_pcl = $(this).val();
        let datos_accion_antecesora_calificacion_pcl = {
            '_token': token,
            'parametro' : "acciones_antecesoras_proceso_calificacion_pcl",
            'servicio_asociado_calificacion_pcl':id_servicio_asociado_calificacion_pcl,
            'Id_cliente': Id_cliente
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_accion_antecesora_calificacion_pcl,
            success:function(data){
                $('#bd_accion_antecesora_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
                $('#bd_accion_antecesora_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_antecesora_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });

        /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del proceso calificacion pcl apenas carga */
        let datos_acciones_automaticas_calificacion_pcl = {
            '_token': token,
            'parametro': "acciones_automaticas_proceso_calificacion_pcl",
            'Id_cliente': Id_cliente,
            'Servicio_asociado_pcl': id_servicio_asociado_calificacion_pcl,
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_automaticas_calificacion_pcl,
            success:function(data){
                $('#bd_accion_automatica_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
                $('#bd_accion_automatica_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_automatica_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');                    
                }
            }
        });
    });

    $("#bd_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).change(function(){
        $("#bd_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).prop('disabled', false);
        let id_accion_seleccionada_calificacion_pcl = $("#bd_estado_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val();
        let datos_acciones_ejecutar_calificacion_pcl = {
            '_token': token,
            'parametro': "acciones_ejecutar_proceso_calificacion_pcl",
            'Id_cliente': Id_cliente,
            'id_accion_seleccionada_calificacion_pcl': id_accion_seleccionada_calificacion_pcl
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_ejecutar_calificacion_pcl,
            success:function(data){
                $('#bd_accion_ejecutar_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
                $('#bd_accion_ejecutar_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_ejecutar_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de status parametrica proceso calificacion pcl*/
    let datos_estatus_parametrica_pcl = {
        '_token': token,
        'parametro' : "estatus_parametrica"
    };
    
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estatus_parametrica_pcl,
        success:function(data){
            $("#bd_status_parametrico_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).empty();
            $("#bd_status_parametrico_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Nombre_parametro"] == $("#bd_id_status_parametrico_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                    $("#bd_status_parametrico_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Nombre_parametro"]+'" selected>'+data[claves[i]]["Nombre_parametro"]+'</option>');
                } else {
                    $("#bd_status_parametrico_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    /* Habilitar el selector de bandeja de trabajo destino cuando se checkea el checkbox de enviar del proceso origen atel */
    $("#bd_enviar_a_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).click(function(){
        if($(this).is(':checked')){
            $("#bd_bandeja_trabajo_destino_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).prop("disabled", false);

            let datos_bandeja_trabajo_destino_calificacion_pcl = {
                '_token': token,
                'parametro': "bandeja_trabajo_destino_notificaciones",
                'Id_cliente': Id_cliente,
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_bandeja_trabajo_destino_calificacion_pcl,
                success:function(data){
                    $('#bd_bandeja_trabajo_destino_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
                    $("#bd_bandeja_trabajo_destino_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append(`<option value="${data.Id_proceso}" selected>${data.Nombre_proceso}</option>`);   
                }
            });

        }else{
            $("#bd_bandeja_trabajo_destino_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).prop("disabled", true);
            $('#bd_bandeja_trabajo_destino_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
            $('#bd_bandeja_trabajo_destino_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
        }
    });

    /* Función solo numeros para input tiempo alerta del proceso origen atel */
    /* La funcionalidad de permitir que solo se ingrese una sola cifra decimal está realizada en el archivo funciones_helpers.js */
    $("#bd_tiempo_alerta_calificacion_pcl_" + id_parametrizacion_calificacion_pcl_editar).on('input', function () {        
        let value = $(this).val();
        // Expresión regular para números con hasta 6 dígitos y hasta 2 decimales
        let regex = /^\d{0,6}(\.\d{0,2})?$/;
        // Verificar si el valor tiene más de un punto decimal o más de 6 dígitos enteros
        let parts = value.split('.');
        let integerPart = parts[0];
        let decimalPart = parts[1];

        if (integerPart.length > 6 || (decimalPart && decimalPart.length > 2) || value.split('.').length > 2) {
            $(this).val(value.slice(0, -1)); // Elimina el último carácter si no coincide con el regex o si tiene más de 6 enteros o más de 2 decimales
        }
    });
    
    // Funcion que permite solo numeros enteros del 0 al 100 en porcentaje alerta naranja

    $("#bd_porcentaje_alerta_naranja_calificacion_pcl_" + id_parametrizacion_calificacion_pcl_editar).on('input', function () {
        $("#bd_porcentaje_alerta_roja_calificacion_pcl_" + id_parametrizacion_calificacion_pcl_editar).val('');  
        let porcentaje_alerta_naranja = $(this).val();    
        // Remover caracteres que no son dígitos
        porcentaje_alerta_naranja = porcentaje_alerta_naranja.replace(/\D/g, '');    
        // Limitar el rango de 0 a 100
        if (porcentaje_alerta_naranja !== '') {
            let intporcentaje_alerta_naranja = parseInt(porcentaje_alerta_naranja, 10);
            if (intporcentaje_alerta_naranja > 100) {
                porcentaje_alerta_naranja = '100';
            } else if (intporcentaje_alerta_naranja < 0) {
                porcentaje_alerta_naranja = '0';
            }
        }    
        // Establecer el valor corregido en el input
        $(this).val(porcentaje_alerta_naranja);
    });

    // funcion para el rango  en el porcentaje de alerta roja
    let tiempo_alerta_roja_editar;

    $("#bd_porcentaje_alerta_roja_calificacion_pcl_" + id_parametrizacion_calificacion_pcl_editar).on('input', function () {
        clearTimeout(tiempo_alerta_roja_editar);
        tiempo_alerta_roja_editar = setTimeout(() => {
            let porcentaje_alerta_naranja = $("#bd_porcentaje_alerta_naranja_calificacion_pcl_" + id_parametrizacion_calificacion_pcl_editar).val();
            let porcentaje_alerta_roja = $(this).val();
            // Remover caracteres que no son dígitos
            porcentaje_alerta_roja = porcentaje_alerta_roja.replace(/\D/g, '');
            // Limitar a 3 dígitos
            if (porcentaje_alerta_roja.length > 3) {
                porcentaje_alerta_roja = porcentaje_alerta_roja.substring(0, 3);
            }
            // Limitar el rango desde el porcentaje de alerta naranja a 100
            if (porcentaje_alerta_roja !== '') {
                let intporcentaje_alerta_roja = parseInt(porcentaje_alerta_roja, 10);
                if (intporcentaje_alerta_roja > 100) {
                    porcentaje_alerta_roja = '100';
                } else if (intporcentaje_alerta_roja < porcentaje_alerta_naranja) {
                    porcentaje_alerta_roja = porcentaje_alerta_naranja;
                }
            }
            // Establecer el valor corregido en el input
            $(this).val(porcentaje_alerta_roja);
        }, 1500);
    });

    // Prevenir entrada de caracteres no numéricos y limitar a 3 dígitos
    $("#bd_porcentaje_alerta_roja_calificacion_pcl_" + id_parametrizacion_calificacion_pcl_editar).on('keydown', function (e) {
        let value = $(this).val();
        // Permitir: backspace, delete, tab, escape, enter y .
        if ($.inArray(e.key, ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', '.']) !== -1 ||
            // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.ctrlKey === true && (e.key === 'a' || e.key === 'c' || e.key === 'v' || e.key === 'x')) ||
            // Permitir: home, end, left, right, down, up
            (e.key >= 'Home' && e.key <= 'End')) {
            return;
        }
        // Prevenir entrada si la longitud actual es de 3 dígitos
        if (value.length >= 3 && (e.key >= '0' && e.key <= '9')) {
            e.preventDefault();
        }
        // Prevenir: cualquier otro carácter que no sea número
        if ((e.key < '0' || e.key > '9')) {
            e.preventDefault();
        }
    });

    let Servicio_asociado_pcl_db_editar = $('#bd_servicio_asociado_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).val();
    /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del proceso calificacion pcl apenas carga */
    let datos_acciones_automaticas_calificacion_pcl = {
        '_token': token,
        'parametro': "acciones_automaticas_proceso_calificacion_pcl",
        'Id_cliente': Id_cliente,
        'Servicio_asociado_pcl': Servicio_asociado_pcl_db_editar,
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_acciones_automaticas_calificacion_pcl,
        success:function(data){
            $('#bd_accion_automatica_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
            $('#bd_accion_automatica_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Accion_ejecutar"] == $("#bd_id_accion_automatica_orgien_atel_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                    $("#bd_accion_automatica_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                } else {
                    $("#bd_accion_automatica_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });

    /* HABILITAR CAMPOS DE TIEMPO DE MOVIMIENTO Y ACCION AUTOMATICA*/

    $("[id^='bd_movimiento_automatico_calificacion_pcl_']").change(function () {
        let id_fila_parametrizacion_movimiento_editar = $(this).data("id_movimiento_automatico_calificacion_pcl");
        let Servicio_asociado_pcl_editar = $('#bd_servicio_asociado_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).val();
        // console.log(id_fila_parametrizacion_movimiento_editar + ' - ' + Servicio_asociado_pcl_editar);
        if ($(this).is(':checked')) {
            // console.log('id movi editar es: '+id_fila_parametrizacion_movimiento_editar);   
            $('#bd_tiempo_movimiento_calificacion_pcl_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', false);
            $('#bd_accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', false); 
            
            /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del proceso origen atel apenas carga */
            let datos_acciones_automaticas_calificacion_pcl = {
                '_token': token,
                'parametro': "acciones_automaticas_proceso_calificacion_pcl",
                'Id_cliente': Id_cliente,
                'Servicio_asociado_pcl': Servicio_asociado_pcl_editar
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_acciones_automaticas_calificacion_pcl,
                success:function(data){
                    $('#bd_accion_automatica_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).empty();
                    $('#bd_accion_automatica_calificacion_pcl_'+id_parametrizacion_calificacion_pcl_editar).append('<option value="" selected>Seleccione</option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        if (data[claves[i]]["Accion_ejecutar"] == $("#bd_id_accion_automatica_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).val()) {
                            $("#bd_accion_automatica_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                        } else {
                            $("#bd_accion_automatica_calificacion_pcl_"+id_parametrizacion_calificacion_pcl_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                        }
                    }
                }
            });
            
        } else {
            $("#bd_tiempo_movimiento_calificacion_pcl_"+id_fila_parametrizacion_movimiento_editar).prop("disabled", true);
            $('#bd_tiempo_movimiento_calificacion_pcl_'+id_fila_parametrizacion_movimiento_editar).val('');
            $('#bd_accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', true);
            $('#bd_accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_movimiento_editar).empty();
            $('#bd_accion_automatica_calificacion_pcl_'+id_fila_parametrizacion_movimiento_editar).append('<option value="" selected>Seleccione</option>');                
        }
    }); 

};

function funciones_elementos_fila_parametrizar_juntas(num_consecutivo){
    
    /* INICIALIZACIÓN SELECT 2 LISTADO DE SERVICIOS ASOCIADOS */
    $(".servicio_asociado_juntas_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO ESTADOS */
    $(".estado_juntas_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES A EJECUTAR */
    $(".accion_ejecutar_juntas_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES ANTECESORAS */
    $(".accion_antecesora_juntas_"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO EQUIPOS DE TRABAJO */
    $(".equipo_trabajo_juntas_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE PROFESIONALES */
    $(".listado_profesionales_juntas_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO BANDEJA TRABAJO DESTINO */
    $(".bandeja_trabajo_destino_juntas_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES AUTOMATICAS */
    $(".accion_automatica_juntas"+num_consecutivo).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 STATUS PARAMETRICO */
    $(".status_parametrico_juntas_"+num_consecutivo).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();
    var Id_cliente = $("#Id_cliente").val();

    /* Carga del selector servicios asociados del proceso origen atel */
    let datos_servicios_asociados_juntas = {
        '_token': token,
        'parametro' : "servicios_asociados_proceso_juntas",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_servicios_asociados_juntas,
        success:function(data){
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#servicio_asociado_juntas_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
            }
        }
    });

    /* Carga del selector de estados del proceso origen atel */
    let datos_estados_juntas = {
        '_token': token,
        'parametro' : "lista_estados",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estados_juntas,
        success:function(data){
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                $("#estado_juntas_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
            }
        }
    });

    /* Carga del selector de acciones a ejecutar dependiendo de la seleccion del estado del proceso origen atel */
    $("#estado_juntas_"+num_consecutivo).change(function(){
        $("#accion_ejecutar_juntas_"+num_consecutivo).prop('disabled', false);
        let id_accion_seleccionada_juntas = $("#estado_juntas_"+num_consecutivo).val();
        let datos_acciones_ejecutar_juntas = {
            '_token': token,
            'parametro': "acciones_ejecutar_proceso_juntas",
            'Id_cliente': Id_cliente,
            'id_accion_seleccionada_juntas': id_accion_seleccionada_juntas
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_ejecutar_juntas,
            success:function(data){
                $('#accion_ejecutar_juntas_'+num_consecutivo).empty();
                $('#accion_ejecutar_juntas_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_ejecutar_juntas_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de equipos de trabajo dependiendo del proceso juntas */    
    $("#accion_ejecutar_juntas_"+num_consecutivo).change(function(){
        $("#equipo_trabajo_juntas_"+num_consecutivo).prop('disabled', false);
        // let id_accion = $("#accion_ejecutar_juntas_"+num_consecutivo).val();
        
        validacionesNotificaciones(num_consecutivo,'juntas','');

        let datos_equipos_trabajo_juntas = {
            '_token': token,
            'parametro' : "equipos_trabajo_proceso_juntas",
            // 'id_accion_seleccionada': id_accion
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_equipos_trabajo_juntas,
            success:function(data){
                $('#equipo_trabajo_juntas_'+num_consecutivo).empty();
                $('#equipo_trabajo_juntas_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#equipo_trabajo_juntas_"+num_consecutivo).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo */
    $("#equipo_trabajo_juntas_"+num_consecutivo).change(function(){
        $("#listado_profesionales_juntas_"+num_consecutivo).prop('disabled', false);
        let id_equipo_seleccionado = $("#equipo_trabajo_juntas_"+num_consecutivo).val();
        let datos_listado_profesionales_juntas = {
            '_token': token,
            'parametro' : "listado_profesionales_proceso_juntas",
            'id_equipo_seleccionado': id_equipo_seleccionado
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_listado_profesionales_juntas,
            success:function(data){
                $('#listado_profesionales_juntas_'+num_consecutivo).empty();
                $('#listado_profesionales_juntas_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#listado_profesionales_juntas_"+num_consecutivo).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado */
    $("#servicio_asociado_juntas_"+num_consecutivo).change(function(){
        var id_servicio_asociado_juntas = $(this).val();
        let datos_accion_antecesora_juntas = {
            '_token': token,
            'parametro' : "acciones_antecesoras_proceso_juntas",
            'servicio_asociado_juntas':id_servicio_asociado_juntas,
            'Id_cliente': Id_cliente
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_accion_antecesora_juntas,
            success:function(data){
                $('#accion_antecesora_juntas_'+num_consecutivo).empty();
                $('#accion_antecesora_juntas_'+num_consecutivo).append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_antecesora_juntas_"+num_consecutivo).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });

        /* Carga del selector de acciones automaticas dependiendo del servicio seleccionado*/        
        let datos_acciones_automaticas_juntas = {
            '_token': token,
            'parametro': "acciones_automaticas_proceso_juntas",
            'Id_cliente': Id_cliente,
            'Servicio_asociado_juntas': id_servicio_asociado_juntas
        };
        // console.log(datos_acciones_automaticas_juntas);
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_automaticas_juntas,
            success:function(data){
                $('#accion_automatica_juntas_'+num_consecutivo).empty();
                $('#accion_automatica_juntas_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#accion_automatica_juntas_"+num_consecutivo).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* HABILITAR CAMPOS DE TIEMPO DE MOVIMIENTO Y ACCION AUTOMATICA*/

    $("[id^='movimiento_automatico_juntas_']").change(function () {        
        let id_fila_parametrizacion_movimiento = $(this).data("id_movimiento_automatico_juntas");
        let servicio_asociado_juntas = $('#servicio_asociado_juntas_'+num_consecutivo).val();
        if ($(this).is(':checked')) {
            // console.log('id movi editar es: '+id_fila_parametrizacion_movimiento_editar);   
            $('#tiempo_movimiento_juntas_'+id_fila_parametrizacion_movimiento).prop('disabled', false);
            $('#accion_automatica_juntas_'+id_fila_parametrizacion_movimiento).prop('disabled', false); 
            
            /* Carga del selector de acciones automaticas dependiendo del estado seleccionado*/        
            let datos_acciones_automaticas_juntas = {
                '_token': token,
                'parametro': "acciones_automaticas_proceso_juntas",
                'Id_cliente': Id_cliente,
                'Servicio_asociado_juntas': servicio_asociado_juntas
            };
            // console.log(datos_acciones_automaticas_juntas);
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_acciones_automaticas_juntas,
                success:function(data){
                    $('#accion_automatica_juntas_'+num_consecutivo).empty();
                    $('#accion_automatica_juntas_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        $("#accion_automatica_juntas_"+num_consecutivo).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                    }
                }
            });                
        }else{
            $("#tiempo_movimiento_juntas_"+id_fila_parametrizacion_movimiento).prop("disabled", true);
            $('#tiempo_movimiento_juntas_'+id_fila_parametrizacion_movimiento).val('');
            $('#accion_automatica_juntas_'+id_fila_parametrizacion_movimiento).prop('disabled', true);
            $('#accion_automatica_juntas_'+id_fila_parametrizacion_movimiento).empty();
            $('#accion_automatica_juntas_'+id_fila_parametrizacion_movimiento).append('<option value="" selected>Seleccione</option>');                 
        }
    }); 

    /* Habilitar el selector de bandeja de trabajo destino cuando se checkea el checkbox de enviar del proceso origen atel */
    $("#enviar_a_juntas_"+num_consecutivo).click(function(){
        if($(this).is(':checked')){
            $("#bandeja_trabajo_destino_juntas_"+num_consecutivo).prop("disabled", false);

            let datos_bandeja_trabajo_destino_juntas = {
                '_token': token,
                'parametro': "bandeja_trabajo_destino_notificaciones",
                'Id_cliente': Id_cliente,
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_bandeja_trabajo_destino_juntas,
                success:function(data){
                    $('#bandeja_trabajo_destino_juntas_'+num_consecutivo).empty();
                    $('#bandeja_trabajo_destino_juntas_'+num_consecutivo).append(`<option value="${data.Id_proceso}" selected>${data.Nombre_proceso}</option>`);
                }
            });

        }else{
            $("#bandeja_trabajo_destino_juntas_"+num_consecutivo).prop("disabled", true);
            $('#bandeja_trabajo_destino_juntas_'+num_consecutivo).empty();
            $('#bandeja_trabajo_destino_juntas_'+num_consecutivo).append('<option value="" selected>Seleccione</option>');
        }
    });

    /* Función solo numeros para input tiempo alerta del proceso origen atel */
    /* La funcionalidad de permitir que solo se ingrese una sola cifra decimal está realizada en el archivo funciones_helpers.js */
    $("#tiempo_alerta_juntas_" + num_consecutivo).on('input', function () {

        let value = $(this).val();
        // Expresión regular para números con hasta 6 dígitos y hasta 2 decimales
        let regex = /^\d{0,6}(\.\d{0,2})?$/;
        // Verificar si el valor tiene más de un punto decimal o más de 6 dígitos enteros
        let parts = value.split('.');
        let integerPart = parts[0];
        let decimalPart = parts[1];

        if (integerPart.length > 6 || (decimalPart && decimalPart.length > 2) || value.split('.').length > 2) {
            $(this).val(value.slice(0, -1)); // Elimina el último carácter si no coincide con el regex o si tiene más de 6 enteros o más de 2 decimales
        }
    });
    // Funcion que permite solo numeros enteros del 0 al 100

    $("#porcentaje_alerta_naranja_juntas_" + num_consecutivo).on('input', function () {
        $("#porcentaje_alerta_roja_juntas_" + num_consecutivo).val('');  
        let porcentaje_alerta_naranja = $(this).val();    
        // Remover caracteres que no son dígitos
        porcentaje_alerta_naranja = porcentaje_alerta_naranja.replace(/\D/g, '');    
        // Limitar el rango de 0 a 100
        if (porcentaje_alerta_naranja !== '') {
            let intporcentaje_alerta_naranja = parseInt(porcentaje_alerta_naranja, 10);
            if (intporcentaje_alerta_naranja > 100) {
                porcentaje_alerta_naranja = '100';
            } else if (intporcentaje_alerta_naranja < 0) {
                porcentaje_alerta_naranja = '0';
            }
        }    
        // Establecer el valor corregido en el input    
        $(this).val(porcentaje_alerta_naranja);
    })

    let tiempo_alerta_rojas;

    $("#porcentaje_alerta_roja_juntas_" + num_consecutivo).on('input', function () {
        clearTimeout(tiempo_alerta_rojas);
        tiempo_alerta_rojas = setTimeout(() => {
            let porcentaje_alerta_naranja = $("#porcentaje_alerta_naranja_juntas_" + num_consecutivo).val();
            let porcentaje_alerta_roja = $(this).val();
            // Remover caracteres que no son dígitos
            porcentaje_alerta_roja = porcentaje_alerta_roja.replace(/\D/g, '');
            // Limitar a 3 dígitos
            if (porcentaje_alerta_roja.length > 3) {
                porcentaje_alerta_roja = porcentaje_alerta_roja.substring(0, 3);
            }
            // Limitar el rango desde el porcentaje de alerta naranja a 100
            if (porcentaje_alerta_roja !== '') {
                let intporcentaje_alerta_roja = parseInt(porcentaje_alerta_roja, 10);
                if (intporcentaje_alerta_roja > 100) {
                    porcentaje_alerta_roja = '100';
                } else if (intporcentaje_alerta_roja < porcentaje_alerta_naranja) {
                    porcentaje_alerta_roja = porcentaje_alerta_naranja;
                }
            }
            // Establecer el valor corregido en el input
            $(this).val(porcentaje_alerta_roja);
        }, 1500);
    });

    // Prevenir entrada de caracteres no numéricos y limitar a 3 dígitos
    $("#porcentaje_alerta_roja_juntas_" + num_consecutivo).on('keydown', function (e) {
        let value = $(this).val();
        // Permitir: backspace, delete, tab, escape, enter y .
        if ($.inArray(e.key, ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', '.']) !== -1 ||
            // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.ctrlKey === true && (e.key === 'a' || e.key === 'c' || e.key === 'v' || e.key === 'x')) ||
            // Permitir: home, end, left, right, down, up
            (e.key >= 'Home' && e.key <= 'End')) {
            return;
        }
        // Prevenir entrada si la longitud actual es de 3 dígitos
        if (value.length >= 3 && (e.key >= '0' && e.key <= '9')) {
            e.preventDefault();
        }
        // Prevenir: cualquier otro carácter que no sea número
        if ((e.key < '0' || e.key > '9')) {
            e.preventDefault();
        }
    });

    // mantenemos el input fecha actualización movimiento disabled en todo movimiento
    $("#fecha_actualizacion_movimiento_juntas_"+num_consecutivo).prop('disabled', true);

    /* Funcionalidad para editar la fila */
    $('#editar_fila_juntas_'+num_consecutivo).click(function() {
        var row = $(this).closest('tr');
        row.find('input, textarea, select').prop('readonly', false).prop('disabled', false);
        row.find('#editar_fila_juntas_'+num_consecutivo).addClass('d-none');
        row.find('#guardar_fila_juntas_'+num_consecutivo).removeClass('d-none');
    });

    /* Funcionalidad para guardar los datos e insertarlo y/o actualizarlos en la tabla de
    sigmel_informacion_parametrizaciones_clientes */
    $('#guardar_fila_juntas_'+num_consecutivo).click(function() {
        // Capturamos los datos de cada tr
        var row = $(this).closest('tr');
        // A todos los input, textarea, select se les adiciona las propiedades readonlu y disabled
        row.find('input, textarea, select').prop('readonly', true).prop('disabled', true);
        // mostrar el botón para editar de nuevo
        row.find('#editar_fila_juntas_'+num_consecutivo).removeClass('d-none');
        row.find('#guardar_fila_juntas_'+num_consecutivo).addClass('d-none');

        // Inicializamos un objeto para almacenar los valores de la fila
        var datos_cada_fila_proceso_juntas = {};

        // Recorre todas las celdas de la fila
        row.find('td').each(function() {
            // Obtén el valor de la celda y luego cada id correspondiente
            var cell = $(this);
            var input = cell.find('input, textarea, select');
            var fieldName = input.attr('id'); 

            // Se valida los checkbox para verificar si fueron marcados o no.
            if (input.is(':checkbox')) {
                datos_cada_fila_proceso_juntas[fieldName] = input.is(':checked') ? 'Si' : 'No';

            } else if (input.val() !== undefined ) {
                datos_cada_fila_proceso_juntas[fieldName] = input.val();
            }
        });

        // Convierte el objeto en un array para mejor manejo de los datos
        var array_datos_fila_parametrizacion_juntas = $.map(datos_cada_fila_proceso_juntas, function(value, key) {
            return { nombre: key, valor: value };
        });

        // Enviamos la información para insertar y/o actualizar
        let enviar_informacion_parametrizacion_juntas = {
            '_token': token,
            'array_datos_fila_parametrizacion_juntas' : array_datos_fila_parametrizacion_juntas,
            'Id_cliente': $("#Id_cliente").val(),
        };
        $.ajax({
            type:'POST',
            url:'/EnvioParametrizacionJuntas',
            data: enviar_informacion_parametrizacion_juntas,
            success:function(response){
                if (response.parametro == "agrego_parametrizacion") {
                    $("#mostrar_mensaje_agrego_parametrizacion_juntas").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_juntas").addClass('alert-success');
                    $(".mensaje_agrego_parametrizacion_juntas").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_juntas").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_juntas").removeClass('alert-success');
                        $(".mensaje_agrego_parametrizacion_juntas").empty();
                        window.location.reload();
                    }, 3000);
                }else{
                    $("#mostrar_mensaje_agrego_parametrizacion_juntas").removeClass('d-none');
                    $(".mensaje_agrego_parametrizacion_juntas").addClass('alert-danger');
                    $(".mensaje_agrego_parametrizacion_juntas").append('<strong>'+response.mensaje+'</strong>');
                    setTimeout(() => {
                        $("#mostrar_mensaje_agrego_parametrizacion_juntas").addClass('d-none');
                        $(".mensaje_agrego_parametrizacion_juntas").removeClass('alert-danger');
                        $(".mensaje_agrego_parametrizacion_juntas").empty();
                        window.location.reload();
                    }, 3000);
                }
            }
        });


    });

};

function edicion_parametrizacion_juntas(id_parametrizacion_juntas_editar){
    /* INICIALIZACIÓN SELECT 2 LISTADO DE SERVICIOS ASOCIADOS */
    $(".bd_servicio_asociado_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO ESTADOS */
    $(".bd_estado_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES A EJECUTAR */
    $(".bd_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES ANTECESORAS */
    $(".bd_accion_antecesora_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO EQUIPOS DE TRABAJO */
    $(".bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE PROFESIONALES */
    $(".bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO BANDEJA TRABAJO DESTINO */
    $(".bd_bandeja_trabajo_destino_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 LISTADO DE ACCIONES AUTOMATICAS */
    $(".bd_accion_automatica_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '240px',
        placeholder: "Seleccione",
        allowClear: false
    });

    /* INICIALIZACIÓN SELECT 2 STATUS PARAMETRICO */
    $(".bd_status_parametrico_juntas_"+id_parametrizacion_juntas_editar).select2({
        width: '140px',
        placeholder: "Seleccione",
        allowClear: false
    });

    let token = $("input[name='_token']").val();
    var Id_cliente = $("#Id_cliente").val();

    /* Carga del selector servicios asociados del proceso origen atel */
    let datos_servicios_asociados_juntas = {
        '_token': token,
        'parametro' : "servicios_asociados_proceso_juntas",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_servicios_asociados_juntas,
        success:function(data){
            $("#bd_servicio_asociado_juntas_"+id_parametrizacion_juntas_editar).empty();
            $("#bd_servicio_asociado_juntas_"+id_parametrizacion_juntas_editar).append("<option></option>");
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_servicio"] == $("#bd_id_servicio_asociado_juntas_"+id_parametrizacion_juntas_editar).val()) {
                    $("#bd_servicio_asociado_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_servicio"]+'" selected>'+data[claves[i]]["Nombre_servicio"]+'</option>');
                } else {
                    $("#bd_servicio_asociado_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_servicio"]+'">'+data[claves[i]]["Nombre_servicio"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de estados del proceso origen atel */
    let datos_estados_juntas = {
        '_token': token,
        'parametro' : "lista_estados",
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estados_juntas,
        success:function(data){
            $("#bd_estado_juntas_"+id_parametrizacion_juntas_editar).empty();
            $("#bd_estado_juntas_"+id_parametrizacion_juntas_editar).append("<option></option>");
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_Parametro"] == $("#bd_id_estado_juntas_"+id_parametrizacion_juntas_editar).val()) {
                    $("#bd_estado_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_Parametro"]+'" selected>'+data[claves[i]]["Nombre_parametro"]+'</option>');
                } else {
                    $("#bd_estado_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_Parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de acciones a ejecutar dependiendo de la seleccion del estado del proceso origen atel */
    let datos_acciones_ejecutar_juntas = {
        '_token': token,
        'parametro': "acciones_ejecutar_proceso_juntas",
        'Id_cliente': Id_cliente,
        'id_accion_seleccionada_juntas': $("#bd_estado_juntas_"+id_parametrizacion_juntas_editar).val()
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_acciones_ejecutar_juntas,
        success:function(data){
            $('#bd_accion_ejecutar_juntas_'+id_parametrizacion_juntas_editar).empty();
            $('#bd_accion_ejecutar_juntas_'+id_parametrizacion_juntas_editar).append('<option value="0">Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_Accion"] == $("#bd_id_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).val()) {
                    $("#bd_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                } else {
                    $("#bd_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });

    validacionesNotificaciones(id_parametrizacion_juntas_editar,'juntas');

    /* Carga del selector de equipos de trabajo dependiendo del proceso juntas apenas carga */
    let datos_equipos_trabajo_juntas = {
        '_token': token,
        'parametro' : "equipos_trabajo_proceso_juntas",
        // 'id_accion_seleccionada': $("#bd_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).val()
    };

    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_equipos_trabajo_juntas,
        success:function(data){
            $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).empty();
            $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["id"] == $("#bd_id_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).val()) {
                    $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["id"]+'" selected>'+data[claves[i]]["nombre"]+'</option>');
                } else {
                    $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de equipos de trabajo dependiendo del proceso juntas evento change */    
    // $("#bd_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).change(function(){
    //     $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).prop('disabled', false);
    //     // let id_accion = $("#bd_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).val();

    //     let datos_equipos_trabajo_juntas = {
    //         '_token': token,
    //         'parametro' : "equipos_trabajo_proceso_juntas",
    //         // 'id_accion_seleccionada': id_accion
    //     };
        
    //     $.ajax({
    //         type:'POST',
    //         url:'/CargueSelectoresParametrizar',
    //         data: datos_equipos_trabajo_juntas,
    //         success:function(data){
    //             $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).empty();
    //             $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
    //             let claves = Object.keys(data);
    //             for (let i = 0; i < claves.length; i++) {
    //                 $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
    //             }
    //         }
    //     });
    // });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo apenas carga */
    let datos_listado_profesionales_juntas = {
        '_token': token,
        'parametro' : "listado_profesionales_proceso_juntas",
        'id_equipo_seleccionado': $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).val()
    };

    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_listado_profesionales_juntas,
        success:function(data){
            $("#bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).empty();
            $("#bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["id"] == $("#bd_id_profesional_asignado_juntas_"+id_parametrizacion_juntas_editar).val()) {
                    $("#bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["id"]+'" selected>'+data[claves[i]]["nombre"]+'</option>');
                } else {
                    $("#bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        }
    });

    /* Carga del selector de profesionales dependendiendo de la selección del equipo de trabajo evento change */
    $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).change(function(){
        $("#bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).prop('disabled', false);
        let id_equipo_seleccionado = $("#bd_equipo_trabajo_juntas_"+id_parametrizacion_juntas_editar).val();

        let datos_listado_profesionales_juntas = {
            '_token': token,
            'parametro' : "listado_profesionales_proceso_juntas",
            'id_equipo_seleccionado': id_equipo_seleccionado
        };

        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_listado_profesionales_juntas,
            success:function(data){
                $("#bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).empty();
                $("#bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_listado_profesionales_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["id"]+'">'+data[claves[i]]["nombre"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado apenas carga */
    let datos_accion_antecesora_juntas = {
        '_token': token,
        'parametro' : "acciones_antecesoras_proceso_juntas",
        'servicio_asociado_juntas':$("#bd_servicio_asociado_juntas_"+id_parametrizacion_juntas_editar).val(),
        'Id_cliente': Id_cliente
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_accion_antecesora_juntas,
        success:function(data){
            //$('#bd_accion_antecesora_juntas_'+id_parametrizacion_juntas_editar).empty();
            //$('#bd_accion_antecesora_juntas_'+id_parametrizacion_juntas_editar).append('<option value="">Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Id_Accion"] != $("#bd_accion_antecesora_juntas"+id_parametrizacion_juntas_editar).val()) {
                    $("#bd_accion_antecesora_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });
    
    /* Carga del selector de acciones antecesoras dependiendo del servicio asociado generado evento change */
    $("#bd_servicio_asociado_juntas_"+id_parametrizacion_juntas_editar).change(function(){
        var id_servicio_asociado_juntas = $(this).val();
        let datos_accion_antecesora_juntas = {
            '_token': token,
            'parametro' : "acciones_antecesoras_proceso_juntas",
            'servicio_asociado_juntas':id_servicio_asociado_juntas,
            'Id_cliente': Id_cliente
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_accion_antecesora_juntas,
            success:function(data){
                $('#bd_accion_antecesora_juntas_'+id_parametrizacion_juntas_editar).empty();
                $('#bd_accion_antecesora_juntas_'+id_parametrizacion_juntas_editar).append('<option></option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_antecesora_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });

        /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del proceso juntas apenas carga */
        let datos_acciones_automaticas_juntas = {
            '_token': token,
            'parametro': "acciones_automaticas_proceso_juntas",
            'Id_cliente': Id_cliente,
            'Servicio_asociado_juntas': id_servicio_asociado_juntas,
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_automaticas_juntas,
            success:function(data){
                $('#bd_accion_automatica_juntas_'+id_parametrizacion_juntas_editar).empty();
                $('#bd_accion_automatica_juntas_'+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_automatica_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');                    
                }
            }
        });
    });

    $("#bd_estado_juntas_"+id_parametrizacion_juntas_editar).change(function(){
        $("#bd_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).prop('disabled', false);
        let id_accion_seleccionada_juntas = $("#bd_estado_juntas_"+id_parametrizacion_juntas_editar).val();
        let datos_acciones_ejecutar_juntas = {
            '_token': token,
            'parametro': "acciones_ejecutar_proceso_juntas",
            'Id_cliente': Id_cliente,
            'id_accion_seleccionada_juntas': id_accion_seleccionada_juntas
        };
        $.ajax({
            type:'POST',
            url:'/CargueSelectoresParametrizar',
            data: datos_acciones_ejecutar_juntas,
            success:function(data){
                $('#bd_accion_ejecutar_juntas_'+id_parametrizacion_juntas_editar).empty();
                $('#bd_accion_ejecutar_juntas_'+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
                let claves = Object.keys(data);
                for (let i = 0; i < claves.length; i++) {
                    $("#bd_accion_ejecutar_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Id_Accion"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        });
    });

    /* Carga del selector de status parametrica proceso juntas*/
    let datos_estatus_parametrica_juntas = {
        '_token': token,
        'parametro' : "estatus_parametrica"
    };
    
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_estatus_parametrica_juntas,
        success:function(data){
            $("#bd_status_parametrico_juntas_"+id_parametrizacion_juntas_editar).empty();
            $("#bd_status_parametrico_juntas_"+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Nombre_parametro"] == $("#bd_id_status_parametrico_juntas_"+id_parametrizacion_juntas_editar).val()) {
                    $("#bd_status_parametrico_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Nombre_parametro"]+'" selected>'+data[claves[i]]["Nombre_parametro"]+'</option>');
                } else {
                    $("#bd_status_parametrico_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Nombre_parametro"]+'">'+data[claves[i]]["Nombre_parametro"]+'</option>');
                }
            }
        }
    });

    /* Habilitar el selector de bandeja de trabajo destino cuando se checkea el checkbox de enviar del proceso origen atel */
    $("#bd_enviar_a_juntas_"+id_parametrizacion_juntas_editar).click(function(){
        if($(this).is(':checked')){
            $("#bd_bandeja_trabajo_destino_juntas_"+id_parametrizacion_juntas_editar).prop("disabled", false);

            let datos_bandeja_trabajo_destino_juntas = {
                '_token': token,
                'parametro': "bandeja_trabajo_destino_notificaciones",
                'Id_cliente': Id_cliente,
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_bandeja_trabajo_destino_juntas,
                success:function(data){
                    $('#bd_bandeja_trabajo_destino_juntas_'+id_parametrizacion_juntas_editar).empty();
                    $('#bd_bandeja_trabajo_destino_juntas_'+id_parametrizacion_juntas_editar).append(`<option value="${data.Id_proceso}" selected>${data.Nombre_proceso}</option>`);
                }
            });

        }else{
            $("#bd_bandeja_trabajo_destino_juntas_"+id_parametrizacion_juntas_editar).prop("disabled", true);
            $('#bd_bandeja_trabajo_destino_juntas_'+id_parametrizacion_juntas_editar).empty();
            $('#bd_bandeja_trabajo_destino_juntas_'+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
        }
    });

    /* Función solo numeros para input tiempo alerta del proceso origen atel */
    /* La funcionalidad de permitir que solo se ingrese una sola cifra decimal está realizada en el archivo funciones_helpers.js */
    $("#bd_tiempo_alerta_juntas_" + id_parametrizacion_juntas_editar).on('input', function () {
        
        let value = $(this).val();
        // Expresión regular para números con hasta 6 dígitos y hasta 2 decimales
        let regex = /^\d{0,6}(\.\d{0,2})?$/;
        // Verificar si el valor tiene más de un punto decimal o más de 6 dígitos enteros
        let parts = value.split('.');
        let integerPart = parts[0];
        let decimalPart = parts[1];

        if (integerPart.length > 6 || (decimalPart && decimalPart.length > 2) || value.split('.').length > 2) {
            $(this).val(value.slice(0, -1)); // Elimina el último carácter si no coincide con el regex o si tiene más de 6 enteros o más de 2 decimales
        }
    });
    
    // Funcion que permite solo numeros enteros del 0 al 100 en porcentaje alerta naranja

    $("#bd_porcentaje_alerta_naranja_juntas_" + id_parametrizacion_juntas_editar).on('input', function () {     
        $("#bd_porcentaje_alerta_roja_juntas_" + id_parametrizacion_juntas_editar).val('');  
        let porcentaje_alerta_naranja = $(this).val();    
        // Remover caracteres que no son dígitos
        porcentaje_alerta_naranja = porcentaje_alerta_naranja.replace(/\D/g, '');    
        // Limitar el rango de 0 a 100
        if (porcentaje_alerta_naranja !== '') {
            let intporcentaje_alerta_naranja = parseInt(porcentaje_alerta_naranja, 10);
            if (intporcentaje_alerta_naranja > 100) {
                porcentaje_alerta_naranja = '100';
            } else if (intporcentaje_alerta_naranja < 0) {
                porcentaje_alerta_naranja = '0';
            }
        }    
        // Establecer el valor corregido en el input
        $(this).val(porcentaje_alerta_naranja);
    });

    // funcion para el rango  en el porcentaje de alerta roja
    let tiempo_alerta_roja_editar;

    $("#bd_porcentaje_alerta_roja_juntas_" + id_parametrizacion_juntas_editar).on('input', function () {
        clearTimeout(tiempo_alerta_roja_editar);
        tiempo_alerta_roja_editar = setTimeout(() => {
            let porcentaje_alerta_naranja = $("#bd_porcentaje_alerta_naranja_juntas_" + id_parametrizacion_juntas_editar).val();
            let porcentaje_alerta_roja = $(this).val();
            // Remover caracteres que no son dígitos
            porcentaje_alerta_roja = porcentaje_alerta_roja.replace(/\D/g, '');
            // Limitar a 3 dígitos
            if (porcentaje_alerta_roja.length > 3) {
                porcentaje_alerta_roja = porcentaje_alerta_roja.substring(0, 3);
            }
            // Limitar el rango desde el porcentaje de alerta naranja a 100
            if (porcentaje_alerta_roja !== '') {
                let intporcentaje_alerta_roja = parseInt(porcentaje_alerta_roja, 10);
                if (intporcentaje_alerta_roja > 100) {
                    porcentaje_alerta_roja = '100';
                } else if (intporcentaje_alerta_roja < porcentaje_alerta_naranja) {
                    porcentaje_alerta_roja = porcentaje_alerta_naranja;
                }
            }
            // Establecer el valor corregido en el input
            $(this).val(porcentaje_alerta_roja);
        }, 1500);
    });

    // Prevenir entrada de caracteres no numéricos y limitar a 3 dígitos
    $("#bd_porcentaje_alerta_roja_juntas_" + id_parametrizacion_juntas_editar).on('keydown', function (e) {
        let value = $(this).val();
        // Permitir: backspace, delete, tab, escape, enter y .
        if ($.inArray(e.key, ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', '.']) !== -1 ||
            // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.ctrlKey === true && (e.key === 'a' || e.key === 'c' || e.key === 'v' || e.key === 'x')) ||
            // Permitir: home, end, left, right, down, up
            (e.key >= 'Home' && e.key <= 'End')) {
            return;
        }
        // Prevenir entrada si la longitud actual es de 3 dígitos
        if (value.length >= 3 && (e.key >= '0' && e.key <= '9')) {
            e.preventDefault();
        }
        // Prevenir: cualquier otro carácter que no sea número
        if ((e.key < '0' || e.key > '9')) {
            e.preventDefault();
        }
    });

    let Servicio_asociado_juntas_db_editar = $('#bd_servicio_asociado_juntas_'+id_parametrizacion_juntas_editar).val();
    /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del juntas apenas carga */
    let datos_acciones_automaticas_juntas = {
        '_token': token,
        'parametro': "acciones_automaticas_proceso_juntas",
        'Id_cliente': Id_cliente,
        'Servicio_asociado_juntas': Servicio_asociado_juntas_db_editar,
    };
    $.ajax({
        type:'POST',
        url:'/CargueSelectoresParametrizar',
        data: datos_acciones_automaticas_juntas,
        success:function(data){
            $('#bd_accion_automatica_juntas_'+id_parametrizacion_juntas_editar).empty();
            $('#bd_accion_automatica_juntas_'+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
            let claves = Object.keys(data);
            for (let i = 0; i < claves.length; i++) {
                if (data[claves[i]]["Accion_ejecutar"] == $("#bd_id_accion_automatica_orgien_atel_"+id_parametrizacion_juntas_editar).val()) {
                    $("#bd_accion_automatica_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                } else {
                    $("#bd_accion_automatica_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                }
            }
        }
    });

    /* HABILITAR CAMPOS DE TIEMPO DE MOVIMIENTO Y ACCION AUTOMATICA*/
    
    $("[id^='bd_movimiento_automatico_juntas_']").change(function () {
        let id_fila_parametrizacion_movimiento_editar = $(this).data("id_movimiento_automatico_juntas");
        let Servicio_asociado_juntas_editar = $('#bd_servicio_asociado_juntas_'+id_parametrizacion_juntas_editar).val();
        if ($(this).is(':checked')) {
            // console.log('id movi editar es: '+id_fila_parametrizacion_movimiento_editar);   
            $('#bd_tiempo_movimiento_juntas_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', false);
            $('#bd_accion_automatica_juntas_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', false); 
            
            /* Carga del selector de acciones automaticas dependiendo de la seleccion del estado del proceso origen atel apenas carga */
            let datos_acciones_automaticas_juntas_atel = {
                '_token': token,
                'parametro': "acciones_automaticas_proceso_juntas",
                'Id_cliente': Id_cliente,
                'Servicio_asociado_juntas': Servicio_asociado_juntas_editar
            };
            $.ajax({
                type:'POST',
                url:'/CargueSelectoresParametrizar',
                data: datos_acciones_automaticas_juntas_atel,
                success:function(data){
                    $('#bd_accion_automatica_juntas_'+id_parametrizacion_juntas_editar).empty();
                    $('#bd_accion_automatica_juntas_'+id_parametrizacion_juntas_editar).append('<option value="" selected>Seleccione</option>');
                    let claves = Object.keys(data);
                    for (let i = 0; i < claves.length; i++) {
                        if (data[claves[i]]["Accion_ejecutar"] == $("#bd_id_accion_automatica_orgien_atel_"+id_parametrizacion_juntas_editar).val()) {
                            $("#bd_accion_automatica_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'" selected>'+data[claves[i]]["Accion"]+'</option>');
                        } else {
                            $("#bd_accion_automatica_juntas_"+id_parametrizacion_juntas_editar).append('<option value="'+data[claves[i]]["Accion_ejecutar"]+'">'+data[claves[i]]["Accion"]+'</option>');
                        }
                    }
                }
            });
            
        } else {
            $("#bd_tiempo_movimiento_juntas_"+id_fila_parametrizacion_movimiento_editar).prop("disabled", true);
            $('#bd_tiempo_movimiento_juntas_'+id_fila_parametrizacion_movimiento_editar).val('');
            $('#bd_accion_automatica_juntas_'+id_fila_parametrizacion_movimiento_editar).prop('disabled', true);
            $('#bd_accion_automatica_juntas_'+id_fila_parametrizacion_movimiento_editar).empty();
            $('#bd_accion_automatica_juntas_'+id_fila_parametrizacion_movimiento_editar).append('<option value="" selected>Seleccione</option>');                
        }
    });  
};

function validacionesNotificaciones(idFila,caso,prefix = 'bd_'){
    //Habilitar campo enviar solo cuando haya una accion selecionada dependiendo del caso
    switch(caso){
        case 'juntas' :
            accionSeleccionada  = $(`#${prefix}accion_ejecutar_juntas_${idFila}`).find(':selected').val();
            if(accionSeleccionada == 0 || accionSeleccionada == undefined){
                $(`#${prefix}enviar_a_juntas_${idFila}`).prop('disabled', true);
            }else{
               $(`#${prefix}enviar_a_juntas_${idFila}`).prop('disabled', false);
            }
            break;
        case 'pcl' : 
            accionSeleccionada  = $(`#${prefix}accion_ejecutar_calificacion_pcl_${idFila}`).find(':selected').val();
            if(accionSeleccionada == 0 || accionSeleccionada == undefined){
                console.log('entre');
                $(`#${prefix}enviar_a_calificacion_pcl_${idFila}`).prop('disabled', true);
            }else{
                console.log(accionSeleccionada,'no entre');
                console.log(`$(#${prefix}enviar_a_calificacion_pcl_${idFila}).prop('disabled', false)`);
                $(`#${prefix}enviar_a_calificacion_pcl_${idFila}`).prop('disabled', false);
            }
            break;
        case 'origen' :
            accionSeleccionada  = $(`#${prefix}accion_ejecutar_origen_atel_${idFila}`).find(':selected').val();
            if(accionSeleccionada == 0 || accionSeleccionada == undefined){
                console.log('entre');
                $(`#${prefix}enviar_a_origen_atel_${idFila}`).prop('disabled', true);
            }else{
                console.log(accionSeleccionada,'no entre');
                console.log(`$(#${prefix}enviar_a_origen_atel_${idFila}).prop('disabled', false)`);
                $(`#${prefix}enviar_a_origen_atel_${idFila}`).prop('disabled', false);
            }
            break;
    }
    
    

}