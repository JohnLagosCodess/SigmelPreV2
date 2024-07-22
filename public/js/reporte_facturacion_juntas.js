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
    var listado_reporte_facturacion_juntas = $('#datos_reporte_facturacion_juntas').DataTable({
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
                    title: fechaActual+" Reporte Facturación Juntas",
                    text:'Exportar datos',
                    className: 'btn btn-success',
                    "excelStyles": [                      // Add an excelStyles definition
                                                 
                    ],
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,
                            31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,
                            61,62,63,64,65,66,67,68,69,70,71,72,73,74,75
                        ]
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
    $('#form_consulta_reporte_facturacion_juntas').submit(function(e){
        e.preventDefault();

        /* Captura de variables del formulario */
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();

        // función para consulta de la información y llenado de datos en la tabla
        llenar_informacion_reporte_factu_juntas(listado_reporte_facturacion_juntas, fecha_desde, fecha_hasta, token);
    });

    /* Funcionalidad para descargar el reporte de excel */
    $('#btn_expor_datos_reporte_facturacion_juntas').click(function () {
        $('.dt-button').click();
    });
});

// Función para llenar datos en el datatable
function renderizarRegistros(data, inicio, fin, reporteFactuJuntasTable) {

    for (let a = inicio; a < fin; a++) {

        var datos = [
            data[a].Cons,
            data[a].Nro_Siniestro,
            data[a].Tipo_Documento,
            data[a].Identificacion,
            data[a].Nombre,
            data[a].Tipo_Afiliado,
            data[a].Fecha_Notificacion_Afiliado,
            data[a].Fecha_Controversia_Afiliado,
            data[a].Fecha_Plazo_Afiliado,
            data[a].Fecha_Radicacion,
            data[a].Fecha_Pago_Honorarios_JR,
            data[a].Fuente_Informacion,
            data[a].Tipo_Evento,
            data[a].Tipo_Controversia1,
            data[a].Tipo_Controversia2,
            data[a].Tipo_Controversia3,
            data[a].Tipo_Controversia4,
            data[a].Tipo_Controversia5,
            data[a].Dx_Principal,
            data[a].Diagnostico2,
            data[a].Diagnostico3,
            data[a].Diagnostico4,
            data[a].Diagnostico5,
            data[a].Diagnostico6,
            data[a].Accidente_Enfermedad,
            data[a].Origen_1A_Oportunidad,
            data[a].Calificacion_Pcl,
            data[a].Fecha_Estructuracion,
            data[a].Entidad_Califica_1A_Opo,
            data[a].Parte_Interpone_Recurso,
            data[a].Fecha_Pago_Jr,
            data[a].Fecha_Pago_Jr_Radicado,
            data[a].Fecha_Envio_A_Jr,
            data[a].Guia_Junta,
            data[a].Guia_Afiliado,
            data[a].Guia_Rta_Junta_Regional,
            data[a].Fecha_Reenvio_A_Jr,
            data[a].Fecha_Reenvio_2_A_Jr,
            data[a].Fecha_Reenvio_3_A_Jr,
            data[a].Junta_Regional,
            data[a].Fecha_Radicado_Dictamen_Jr,
            data[a].Fecha_Dictamen_Junta,
            data[a].Origen_Jr,
            data[a].Total_Minusvalia_Jr,
            data[a].Total_Discapacidad_Jr,
            data[a].Total_Deficiencia_Jr,
            data[a].Total_Rol_Laboral_Jr,
            data[a].Calificacion_Pcl_Jr,
            data[a].Fecha_Estructuracion_Jr,
            data[a].ARL,
            data[a].EPS,
            data[a].Fecha_Sol_Constancia_Eje,
            data[a].Fecha_Recibido_Dictamen_Jr,
            data[a].Fecha_Pago_Jn,
            data[a].Fecha_Pago_Jn_Radicado,
            data[a].Fecha_Envio_A_Jn,
            data[a].Fecha_Dictamen_Jn,
            data[a].Origen_Jn,
            data[a].Calificacion_Pcl_Jn,
            data[a].Fecha_Estructuracion_Jn,
            data[a].Funcionario_Actual,
            data[a].Funcionario_Ultima_Accion,
            data[a].Estado,
            data[a].Observacion_1,
            data[a].Fecha_Asignar_Profesional,
            data[a].Fecha_Acuerdo,
            data[a].Fecha_Controversia,
            data[a].Fecha_De_Notificacion_A_Alfa,
            data[a].Fecha_Guia_De_Salida_Correspondencia_Afiliado,
            data[a].Fecha_Guia_De_Salida_Correspondencia_Jr,
            data[a].Ans_Dias,
            data[a].Ans_Estado,
            data[a].Observacion_2,
            data[a].Corte,
            data[a].Fecha_Pago_Jr_blanco,
            data[a].Fecha_Envio_Efectvio_A_La_Jr
        ];
        
        reporteFactuJuntasTable.row.add(datos).draw(false).node();
        datos = [];
    }
}

// función para consulta de la información y envio de datos a la tabla
function llenar_informacion_reporte_factu_juntas (reporteFactuJuntasTable, fecha_desde, fecha_hasta, token){
    var datos_consulta_reporte_factu_juntas = {
        '_token': token,
        'fecha_desde': fecha_desde,
        'fecha_hasta': fecha_hasta,
    };

    $.ajax({
        type:'POST',
        url:'/consultaReporteFactuJuntas',
        data: datos_consulta_reporte_factu_juntas,
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
                $('#botones_reporte_facturacion_juntas').addClass('d-none');

            }else{
                // Mostrando mensajes
                $('.resultado_validacion').removeClass('d-none');
                $('.resultado_validacion').addClass('alert-info');
                var string_texto = '<span>Se encontraron <b>'+data.length+'</b> registros, esto tardará un tiempo en cargar los resultados. Por favor espere.</span>';
                $('#llenar_mensaje_validacion').append(string_texto);

                // Ocultando el label de conteo de registros
                $('#div_info_numero_registros').addClass('d-none');
                $("#total_registros_reporte_facturacion_juntas").empty();
                // Se oculta el boton para descarga del excel
                $('#botones_reporte_facturacion_juntas').addClass('d-none');

                // Creacion del contador para añadirlo a los registros
                for (let i = 0; i < data.length; i++) {
                    data[i]['Cons'] = i+1;                        
                }

                // Vaciado del datatable
                reporteFactuJuntasTable.clear();

                // Inserción del contenido cada 100 registros
                var inicio = 0;
                var fin = Math.min(100, data.length);
                function renderizarSiguienteBloque() {
                    if (inicio < data.length) {
                        renderizarRegistros(data, inicio, fin, reporteFactuJuntasTable);
                        inicio = fin;
                        fin += Math.min(fin + 100, data.length) - fin;
                        
                        if (inicio >= data.length) {
                            // LLenado del label de conteo de registros
                            $('#div_info_numero_registros').removeClass('d-none');
                            $("#total_registros_reporte_facturacion_juntas").empty();
                            $("#total_registros_reporte_facturacion_juntas").append(data.length);
                            // Se muestra el boton para descarga del excel
                            $('#botones_reporte_facturacion_juntas').removeClass('d-none');

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
};