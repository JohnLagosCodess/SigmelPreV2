$(document).ready(function () {
    // captura token
    var token = $('input[name=_token]').val();

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
                    title: fechaActual+" Correspondencia SIGMEL",
                    text:'Exportar datos',
                    className: 'btn btn-success',
                    "excelStyles": [                      // Add an excelStyles definition
                                                 
                    ],
                    exportOptions: {
                        columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]
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

        /* Captura de variables del formulario */
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();

        llenar_informacion_reporte_notificaciones(listado_reporte_notificaciones, fecha_desde, fecha_hasta, token);
    })

    /* Funcionalidad para descargar el reporte de excel */
    $('#btn_expor_datos_reporte_notificaciones').click(function () {
        $('.dt-button').click();
    });

    /* Funcionalidad para descargar el archivo .zip */
    $('#btn_generar_zip_reporte_notificaciones').click(function () {
        /* Captura de variables del formulario */
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();

        var datos_generar_zip_reporte_notifi = {
            '_token': token,
            'fecha_desde': fecha_desde,
            'fecha_hasta': fecha_hasta,
        };

        $.ajax({
            type:'POST',
            url:'/generarZipReporteNotificaciones',
            data: datos_generar_zip_reporte_notifi,
            success:function(data){ 
                if (data.parametro == "error") {
                    /* Mostrar contenedor mensaje de que no hay información */
                    $('.resultado_validacion').removeClass('d-none');
                    $('.resultado_validacion').addClass('alert-danger');
                    $('#llenar_mensaje_validacion').append(data.mensaje);
                    setTimeout(() => {
                        $('.resultado_validacion').addClass('d-none');
                        $('.resultado_validacion').removeClass('alert-danger');
                        $('#llenar_mensaje_validacion').empty();
                    }, 4000);

                }else{
                    // Descarga del Archivo
                    window.location.href = data.url;

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
    });
});


function renderizarRegistros(data, inicio, fin, reporteNotificacionesTable) {

    for (let a = inicio; a < fin; a++) {

        var datos = [
            data[a].Cons,
            data[a].Fecha_envio,
            data[a].No_identificacion,
            data[a].No_guia_asignado,
            data[a].Orden_impresion,
            data[a].Proceso,
            data[a].Servicio,
            data[a].Ultima_Accion,
            data[a].Estado,
            data[a].No_OIP,
            data[a].Tipo_destinatario,
            data[a].Nombre_destinatario,
            data[a].Direccion,
            data[a].Telefono,
            data[a].Departamento,
            data[a].Ciudad,
            data[a].Folios_entregados,
            data[a].Medio_Notificacion,
            data[a].Correo_electronico,
            data[a].Archivo_1,
            data[a].Archivo_2,
        ];
        
        reporteNotificacionesTable.row.add(datos).draw(false).node();
        datos = [];
    }
}

function llenar_informacion_reporte_notificaciones(reporteNotificacionesTable, fecha_desde, fecha_hasta, token){
    var datos_consulta_reporte_notificaciones = {
        '_token': token,
        'fecha_desde': fecha_desde,
        'fecha_hasta': fecha_hasta,
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

                // Mostrando mensajes
                $('.resultado_validacion').removeClass('d-none');
                $('.resultado_validacion').addClass('alert-info');
                var string_texto = '<span>Se encontraron <b>'+data.length+'</b> registros, esto tardará un tiempo en cargar los resultados. Por favor espere.</span>';
                $('#llenar_mensaje_validacion').append(string_texto);

                // Ocultando el label de conteo de registros
                $('#div_info_numero_registros').addClass('d-none');
                $("#total_registros_reporte_notificaciones").empty();
                // Se oculta los botones para descarga del excel y el .zip
                $('#botones_reporte_notificaciones').addClass('d-none');

                // Creacion del contador para añadirlo a los registros
                for (let i = 0; i < data.length; i++) {
                    data[i]['Cons'] = i+1;                        
                }

                // Vaciado del datatable
                reporteNotificacionesTable.clear();

                // Inserción del contenido cada 100 registros
                var inicio = 0;
                var fin = Math.min(100, data.length);
                function renderizarSiguienteBloque() {
                    if (inicio < data.length) {
                        renderizarRegistros(data, inicio, fin, reporteNotificacionesTable);
                        inicio = fin;
                        fin += Math.min(fin + 100, data.length) - fin;
                        
                        if (inicio >= data.length) {
                            // LLenado del label de conteo de registros
                            $('#div_info_numero_registros').removeClass('d-none');
                            $("#total_registros_reporte_notificaciones").empty();
                            $("#total_registros_reporte_notificaciones").append(data.length);
                            // Se muestra los botones para descarga del excel y el .zip
                            $('#botones_reporte_notificaciones').removeClass('d-none');

                            // ocultando mensaje
                            $('.resultado_validacion').addClass('d-none');
                            $('.resultado_validacion').removeClass('alert-info');
                            $('#llenar_mensaje_validacion').empty();
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