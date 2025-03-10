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
    var listado_reporte_facturacion_pcl = $('#datos_reporte_facturacion_pcl').DataTable({
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
                    title: fechaActual+" Reporte Facturación PCL",
                    text:'Exportar datos',
                    className: 'btn btn-success',
                    "excelStyles": [                      // Add an excelStyles definition
                                                 
                    ],
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,
                            31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53
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
    $('#form_consulta_reporte_facturacion_pcl').submit(function(e){
        e.preventDefault();

        /* Captura de variables del formulario */
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();

        // función para consulta de la información y llenado de datos en la tabla
        llenar_informacion_reporte_factu_pcl(listado_reporte_facturacion_pcl, fecha_desde, fecha_hasta, token);
    });

    /* Funcionalidad para descargar el reporte de excel */
    $('#btn_expor_datos_reporte_facturacion_pcl').click(function () {
        $('.dt-button').click();
    });
});

// Función para llenar datos en el datatable
function renderizarRegistros(data, inicio, fin, reporteFactuPclTable) {

    for (let a = inicio; a < fin; a++) {

        var datos = [
            data[a].Cons,
            data[a].Nombre_Servicio,
            data[a].Tipo_Afiliado,
            data[a].Fecha_Radicacion_A_Codess,
            data[a].Nro_Siniestro,
            data[a].Documento,
            data[a].Nombre,
            data[a].Fecha_Solicitud_Documentos,
            data[a].Fecha_Dictamen,
            data[a].Total_Minusvalia,
            data[a].Total_Discapacidad,
            data[a].Total_Deficiencia,
            data[a].Total_Rol_Laboral,
            data[a].Fecha_Estructuracion,
            data[a].Calificacion,
            data[a].Origen,
            data[a].Tipo_Evento,
            data[a].Calificado_Con,
            data[a].Estado,
            data[a].Cie10_1,
            data[a].Diagnostico_1,
            data[a].Cie10_2,
            data[a].Diagnostico_2,
            data[a].Cie10_3,
            data[a].Diagnostico_3,
            data[a].Cie10_4,
            data[a].Diagnostico_4,
            data[a].Cie10_5,
            data[a].Diagnostico_5,
            data[a].Cie10_6,
            data[a].Diagnostico_6,
            data[a].Requiere_Ayuda_Tercero,
            data[a].Requiere_Tercero_Toma_Decisiones,
            data[a].Requiere_Revision_Pension,
            data[a].Empleador,
            data[a].ARL,
            data[a].EPS,
            data[a].Guia_Afiliado,
            data[a].Guia_Eps,
            data[a].Guia_Afp,
            data[a].Guia_Empleador,
            data[a].Guia_Arl,
            data[a].Nombre_Departamento,
            data[a].Fecha_Correspondencia,
            data[a].Fecha_Notificacion_Alfa,
            data[a].Calificador,
            data[a].Ans_Dias,
            data[a].Ans_Estado,
            data[a].Observaciones,
            data[a].Tipo_Servicio,
            data[a].Tipo_Envio,
            data[a].Corte,
            data[a].Entidad_Remite_Dictamen,
            data[a].Porcentaje_Deficiencia
        ];
        
        reporteFactuPclTable.row.add(datos).draw(false).node();
        datos = [];
    }
}

// función para consulta de la información y envio de datos a la tabla
function llenar_informacion_reporte_factu_pcl (reporteFactuPclTable, fecha_desde, fecha_hasta, token){
    var datos_consulta_reporte_factu_pcl = {
        '_token': token,
        'fecha_desde': fecha_desde,
        'fecha_hasta': fecha_hasta,
    };

    $.ajax({
        type:'POST',
        url:'/consultaReporteFactuPcl',
        data: datos_consulta_reporte_factu_pcl,
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
                $('#botones_reporte_facturacion_pcl').addClass('d-none');

            }else{
                // Mostrando mensajes
                $('.resultado_validacion').removeClass('d-none');
                $('.resultado_validacion').addClass('alert-info');
                var string_texto = '<span>Se encontraron <b>'+data.length+'</b> registros, esto tardará un tiempo en cargar los resultados. Por favor espere.</span>';
                $('#llenar_mensaje_validacion').append(string_texto);

                // Ocultando el label de conteo de registros
                $('#div_info_numero_registros').addClass('d-none');
                $("#total_registros_reporte_facturacion_pcl").empty();
                // Se oculta el boton para descarga del excel
                $('#botones_reporte_facturacion_pcl').addClass('d-none');

                // Creacion del contador para añadirlo a los registros
                for (let i = 0; i < data.length; i++) {
                    data[i]['Cons'] = i+1;                        
                }

                // Vaciado del datatable
                reporteFactuPclTable.clear();

                // Inserción del contenido cada 100 registros
                var inicio = 0;
                var fin = Math.min(100, data.length);
                function renderizarSiguienteBloque() {
                    if (inicio < data.length) {
                        renderizarRegistros(data, inicio, fin, reporteFactuPclTable);
                        inicio = fin;
                        fin += Math.min(fin + 100, data.length) - fin;
                        
                        if (inicio >= data.length) {
                            // LLenado del label de conteo de registros
                            $('#div_info_numero_registros').removeClass('d-none');
                            $("#total_registros_reporte_facturacion_pcl").empty();
                            $("#total_registros_reporte_facturacion_pcl").append(data.length);
                            // Se muestra el boton para descarga del excel
                            $('#botones_reporte_facturacion_pcl').removeClass('d-none');

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