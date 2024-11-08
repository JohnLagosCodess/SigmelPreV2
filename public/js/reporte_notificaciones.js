$(document).ready(function () {

    // Inicializacion de select estado_general_calificacion
    $(".estado_general_calificacion").select2({
        allowClear: false
    });
    // captura token
    var token = $('input[name=_token]').val();

    // Llenado del selector estado_general_calificacion

    let datos_lista_estado_general_calificacion = {
        '_token': token,
        'parametro': "lista_estado_general_calificacion"
    };    
    $.ajax({
        type:'POST',
        url:'/cargueListadoSelectorReporteNoti',
        data:datos_lista_estado_general_calificacion,
        success:function (data) {
            let estado_noti_califi = Object.keys(data);
            for (let i = 0; i < estado_noti_califi.length; i++) {
                let idParametro = data[estado_noti_califi[i]]["Id_Parametro"];
                let nombreParametro = data[estado_noti_califi[i]]["Nombre_parametro"];
                // Verifica si el Id_Parametro es 359 (Pendiente para dejarlo selected)
                let selected = idParametro == 359 ? 'selected' : ''; 
                $('#estado_general_calificacion').append('<option value="' + idParametro + '" ' + selected + '>' + nombreParametro + '</option>');
            }
        }
    });

    setTimeout(() => {
        $("#alerta_cargue_correspondencia").addClass('d-none');
    }, 5000);

    // Función para obtener la fecha actual
    function obtenerFechaActual() {
        var hoy = new Date();
        var dia = hoy.getDate();
        var mes = hoy.getMonth() + 1; // Los meses van de 0 a 11
        var año = hoy.getFullYear();

        // Formatear la fecha como AAAA-MM-DD
        if (mes < 10) {
            mes = '0' + mes; // Agregar un cero si el mes es menor a 10
        }
        if (dia < 10) {
            dia = '0' + dia; // Agregar un cero si el día es menor a 10
        }

        return año + '-' + mes + '-' + dia;
    };

    var fechaActual = obtenerFechaActual();

    // Datatable para llenar la información del reporte
    var listado_reporte_notificaciones = $('#datos_reporte_notificaciones').DataTable({
        "searching": false,
        "info": false,
        scrollY: 350,
        scrollX: true,
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
                    title: function(){
                        return fechaActual + "_" + $('#nro_orden').val();
                    },
                    text:'Exportar datos',
                    className: 'btn btn-success',
                    "excelStyles": [                      // Add an excelStyles definition
                                                 
                    ],
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                    }
                }
            ]
        },
        "destroy": true,
        "pageLength": 20,
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

    /* Envío de información del formulario */
    $('#form_consulta_reporte_notificaciones').submit(function(e){
        e.preventDefault();        
        $('#btn_generar_reporte').prop('disabled', true);
        /* Captura de variables del formulario */
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();
        // variables NO Obligatorias
        var estado_general_calificacion = $('#estado_general_calificacion').val();
        var numero_orden = $('#numero_orden').val();


        llenar_informacion_reporte_notificaciones(listado_reporte_notificaciones, fecha_desde, fecha_hasta, estado_general_calificacion, numero_orden, token);
    })

    /* Funcionalidad para descargar el reporte de excel */
    $('#btn_expor_datos_reporte_notificaciones').click(function () {
        $('.dt-button').click();
    });

    /* Funcionalidad para descargar el archivo .zip */
    $('#btn_generar_zip_reporte_notificaciones').click(function () {

        // Mostrando mensajes
        $('.resultado_validacion').removeClass('d-none');
        $('.resultado_validacion').addClass('alert-info');
        $('#llenar_mensaje_validacion').empty();
        var string_texto = '<span>Generando Archivo .zip, Por favor espere ... </span>';
        $('#llenar_mensaje_validacion').append(string_texto);

        // Deshabilitar el botón del zip para que no den clic muchas veces
        $("#btn_generar_zip_reporte_notificaciones").prop('disabled', true);
        
        setTimeout(() => {
            /* Captura de variables del formulario */
            var fecha_desde = $('#fecha_desde').val();
            var fecha_hasta = $('#fecha_hasta').val();
            // variables NO Obligatorias
            var estado_general_calificacion = $('#estado_general_calificacion').val();
            var numero_orden = $('#numero_orden').val();
    
            var datos_generar_zip_reporte_notifi = {
                '_token': token,
                'fecha_desde': fecha_desde,
                'fecha_hasta': fecha_hasta,
                'nro_orden': $('#nro_orden').val(),
                'estado_general_calificacion': estado_general_calificacion,
                'numero_orden': numero_orden
            };
    
    
            $.ajax({
                type:'POST',
                url:'/generarZipReporteNotificaciones',
                data: datos_generar_zip_reporte_notifi,
                success:function(data){ 
                    if (data.parametro == "error") {
                        /* Mostrar contenedor mensaje de que no hay información */
                        
                        /* En caso de que el zip que genera es vacio muestra este error si 
                        no los otros errores aparecerán */
                        if (data.vacio == "zip_vacio") {
                            $('.resultado_validacion').removeClass('alert-info');
                            $('#llenar_mensaje_validacion').empty();
                            $('.resultado_validacion').addClass('alert-danger');
                            $('#llenar_mensaje_validacion').append(data.mensaje);

                            // habilitar el botón del zip nuevamente
                            $("#btn_generar_zip_reporte_notificaciones").prop('disabled', false);

                        }else{
                            $('.resultado_validacion').removeClass('d-none');
                            $('.resultado_validacion').addClass('alert-danger');
                            $('#llenar_mensaje_validacion').append(data.mensaje);
                        }

                        setTimeout(() => {
                            $('.resultado_validacion').addClass('d-none');
                            $('.resultado_validacion').removeClass('alert-danger');
                            $('#llenar_mensaje_validacion').empty();
                        }, 6000);
    
                    }else{
                        // Descarga del Archivo
                        window.location.href = data.url;
                        // Eliminando mensajes
                        $('.resultado_validacion').addClass('d-none');
                        $('.resultado_validacion').removeClass('alert-info');
                        $('#llenar_mensaje_validacion').append('');
    
                        // habilitar el botón del zip nuevamente
                        $("#btn_generar_zip_reporte_notificaciones").prop('disabled', false);
    
                        // Eliminar el archivo después de un tiempo de espera (por ejemplo, 10 segundos)
                        setTimeout(function() {
                            var datos_eliminar_reporte = {
                                '_token': token,
                                'nom_archivo': data.nom_archivo
                            };
                            
                            $.ajax({
                                type: 'POST',
                                url: '/eliminarZipReporteNotificaciones',
                                data: datos_eliminar_reporte,
                                success: function(response) {
                                }
                            });
                        }, 10000);
                        
                    }
                }
            });
            
        }, 3500);

    });

    $('#cargar_Correspondencia').click(function () {
        setTimeout(() => {
            $(this).prop('disabled', true);            
        }, 500);
        $('#mostrar_barra_cargar_correspondencia').removeClass('d-none');        
    });

});


function renderizarRegistros(data, inicio, fin, reporteNotificacionesTable) {

    for (let a = inicio; a < fin; a++) {

        var datos = [
            data[a].Cons,
            data[a].ID_evento,
            data[a].F_comunicado,
            data[a].N_radicado,
            data[a].Nombre_documento,
            data[a].Carpeta_impresion,
            data[a].Observaciones,
            data[a].N_identificacion,
            data[a].Tipo_destinatario,
            data[a].Nombre_destinatario,
            data[a].Direccion_destinatario,
            data[a].Telefono_destinatario,
            data[a].Ciudad_departamento,            
            data[a].Email_destinatario,
            data[a].Proceso_servicio,           
            data[a].Ultima_accion,
            data[a].Estado,
            data[a].N_de_orden,
            data[a].Id_destinatario,
            data[a].Tipo_correspondencia,
            data[a].N_guia,
            data[a].Folios,
            data[a].F_envio,
            data[a].F_notificacion
        ];
        
        reporteNotificacionesTable.row.add(datos).draw(false).node();
        datos = [];
    }
}

function llenar_informacion_reporte_notificaciones(reporteNotificacionesTable, fecha_desde, fecha_hasta, estado_general_calificacion, numero_orden, token){
    var datos_consulta_reporte_notificaciones = {
        '_token': token,
        'fecha_desde': fecha_desde,
        'fecha_hasta': fecha_hasta,
        'estado_general_calificacion' :estado_general_calificacion, 
        'numero_orden' :numero_orden
    };
    $.ajax({
        type:'POST',
        url:'/consultaReporteNotificaciones',
        data: datos_consulta_reporte_notificaciones,
        success:function(data){ 
            if (data.parametro == "falta_un_parametro") {
                /* Mostrar contenedor mensaje de que no hay información */
                $('.resultado_validacion').removeClass('d-none');
                $('.resultado_validacion').addClass('alert-danger');
                $('#llenar_mensaje_validacion').append(data.mensaje);
                setTimeout(() => {
                    $('.resultado_validacion').addClass('d-none');
                    $('.resultado_validacion').removeClass('alert-danger');
                    $('#llenar_mensaje_validacion').empty();
                }, 4000);

                $('#fecha_desde').val('');
                $('#fecha_hasta').val('');
                $('#div_info_numero_registros').addClass('d-none');
                $('#botones_reporte_notificaciones').addClass('d-none');                
            }else{

                if (data.parametro == "Numero_orden_NoVigente") {
                    // Ocultando el label de conteo de registros
                    $('#div_info_numero_registros').addClass('d-none');
                    $("#total_registros_reporte_notificaciones").empty();
                    // Se oculta los botones para descarga del excel y el .zip
                    $('#botones_reporte_notificaciones').addClass('d-none');
                    // Mostrando mensajes
                    $('.resultado_validacion').removeClass('d-none');
                    $('.resultado_validacion').addClass('alert-danger');
                    $('#llenar_mensaje_validacion').append(data.mensaje);
                    setTimeout(() => {
                        $('.resultado_validacion').addClass('d-none');
                        $('.resultado_validacion').removeClass('alert-danger');
                        $('#llenar_mensaje_validacion').empty();
                        $(document).ready(function() {
                            $('#btn_generar_reporte').prop('disabled', false);
                        });
                    }, 4000);                
                } 
                else if (data.parametro == "Numero_orden_NoExiste"){
                    // Ocultando el label de conteo de registros
                    $('#div_info_numero_registros').addClass('d-none');
                    $("#total_registros_reporte_notificaciones").empty();
                    // Se oculta los botones para descarga del excel y el .zip
                    $('#botones_reporte_notificaciones').addClass('d-none');
                    // Mostrando mensajes
                    $('.resultado_validacion').removeClass('d-none');
                    $('.resultado_validacion').addClass('alert-danger');
                    $('#llenar_mensaje_validacion').append(data.mensaje);
                    setTimeout(() => {
                        $('.resultado_validacion').addClass('d-none');
                        $('.resultado_validacion').removeClass('alert-danger');
                        $('#llenar_mensaje_validacion').empty();
                        $(document).ready(function() {
                            $('#btn_generar_reporte').prop('disabled', false);
                        });
                    }, 4000);  
                }
                else if (data.parametro == "falta_numero_orden_DB"){
                    // Mostrando mensajes
                    $('.resultado_validacion').removeClass('d-none');
                    $('.resultado_validacion').addClass('alert-danger');
                    $('#llenar_mensaje_validacion').append(data.mensaje);
                    setTimeout(() => {
                        $('.resultado_validacion').addClass('d-none');
                        $('.resultado_validacion').removeClass('alert-danger');
                        $('#llenar_mensaje_validacion').empty();
                        $(document).ready(function() {
                            $('#btn_generar_reporte').prop('disabled', false);
                        });
                    }, 4000);  
                }
                else {
                    // Mostrando mensajes
                    $('.resultado_validacion').removeClass('d-none');
                    $('.resultado_validacion').addClass('alert-info');                                        
                    
                    if (data.reporte.length == 0) {
                        var string_texto = '<span>Se encontraron <b>'+data.reporte.length+'</b> registros, con este numero de orden, vuelve a consultar.</span>';
                        $('#llenar_mensaje_validacion').append(string_texto);
                        // Ocultando el label de conteo de registros
                        $('#div_info_numero_registros').addClass('d-none');
                        $("#total_registros_reporte_notificaciones").empty();
                        // Se oculta los botones para descarga del excel y el .zip
                        $('#botones_reporte_notificaciones').addClass('d-none');
                        setTimeout(() => {
                            $('.resultado_validacion').addClass('d-none');
                            $('.resultado_validacion').removeClass('alert-info');
                            $('#llenar_mensaje_validacion').empty();
                            $(document).ready(function() {
                                $('#btn_generar_reporte').prop('disabled', false);
                            });
                        }, 4000);
                    } else {
                        var string_texto = '<span>Se encontraron <b>'+data.reporte.length+'</b> registros, esto tardará un tiempo en cargar los resultados. Por favor espere.</span>';
                        $('#llenar_mensaje_validacion').append(string_texto);
                        setTimeout(() => {
                            
                            // Ocultando el label de conteo de registros
                            $('#div_info_numero_registros').addClass('d-none');
                            $("#total_registros_reporte_notificaciones").empty();
                            // Se oculta los botones para descarga del excel y el .zip
                            $('#botones_reporte_notificaciones').addClass('d-none');
        
                            // seteamos el input de nro de orden
                            $('#nro_orden').val(data.n_orden);
            
                            // Creacion del contador para añadirlo a los registros
                            for (let i = 0; i < data.reporte.length; i++) {
                                data.reporte[i]['Cons'] = i+1;                        
                            }
            
                            // Vaciado del datatable
                            reporteNotificacionesTable.clear();
            
                            // Inserción del contenido cada 100 registros
                            var inicio = 0;
                            var fin = Math.min(100, data.reporte.length);
                            function renderizarSiguienteBloque() {
                                if (inicio < data.reporte.length) {
                                    renderizarRegistros(data.reporte, inicio, fin, reporteNotificacionesTable);
                                    inicio = fin;
                                    fin += Math.min(fin + 100, data.reporte.length) - fin;
                                    
                                    if (inicio >= data.reporte.length) {
                                        // LLenado del label de conteo de registros
                                        $('#div_info_numero_registros').removeClass('d-none');
                                        $("#total_registros_reporte_notificaciones").empty();
                                        $("#total_registros_reporte_notificaciones").append(data.reporte.length);
                                        // Se muestra los botones para descarga del excel y el .zip
                                        $('#botones_reporte_notificaciones').removeClass('d-none');
            
                                        // ocultando mensaje
                                        $('.resultado_validacion').addClass('d-none');
                                        $('.resultado_validacion').removeClass('alert-info');
                                        $('#llenar_mensaje_validacion').empty();
                                        $(document).ready(function() {
                                            $('#btn_generar_reporte').prop('disabled', false);
                                        });
                                    } else {
                                        setTimeout(renderizarSiguienteBloque, 2000); // Pausa de 2 segundos
                                    }
                                    
                                }
                            }
                
                            renderizarSiguienteBloque();                    
                        }, 3500);                        
                    }    
                    
                }

                
            }
        }
    });
}