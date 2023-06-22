$(document).ready(function () {
            
    $('#form_consultar_evento').submit(function(e){
        e.preventDefault();
        /* Captura de variables de formulario de consulta de evento */
        var consultar_nro_identificacion = $('#consultar_nro_identificacion').val();
        var consultar_id_evento = $('#consultar_id_evento').val();

        if(consultar_nro_identificacion != '' || consultar_id_evento != ''){
            
            var datos_consulta_evento = {
                '_token': $('input[name=_token]').val(),
                'consultar_nro_identificacion': consultar_nro_identificacion,
                'consultar_id_evento': consultar_id_evento,
            };
            
            $.ajax({
                type:'POST',
                url:'/consultaInformacionEvento',
                data: datos_consulta_evento,
                success:function(data) {
                    // console.log();
                    if (data.parametro == "sin_datos") {
                        /* Mostrar contenedor mensaje de que no hay información */
                        $('.resultado_validacion').removeClass('d-none');
                        $('.resultado_validacion').addClass('alert-danger');
                        $('#llenar_mensaje_validacion').append(data.mensaje);
                        setTimeout(() => {
                            $('.resultado_validacion').addClass('d-none');
                            $('.resultado_validacion').removeClass('alert-danger');
                            $('#llenar_mensaje_validacion').empty();
                        }, 5000);

                        /* Ocultar contenedor informacion del afiliado */
                        $('.contenedor_info_afiliado').addClass('d-none');
                        $('#span_nombre_afiliado').empty();
                        $('#span_nro_identificacion').empty();

                        /* Ocultar contenedor información del evento */
                        $('.contenedor_info_evento').addClass('d-none');
                        $('#num_registros').empty();
                        $('#body_listado_eventos').empty();

                    } else {
                        /* Ocultar contenedor mensaje de que no hay información */
                        $('.contenendor_mensaje_no_datos').addClass('d-none');
                        $('.mensaje_no_datos').empty();

                        /* Habilitar contenedor informacion del afiliado */
                        $('#span_nombre_afiliado').empty();
                        $('#span_nro_identificacion').empty();
                        $('.contenedor_info_afiliado').removeClass('d-none');
                        $('#span_nombre_afiliado').append(data[0]['Nombre_afiliado']);
                        $('#span_nro_identificacion').append(data[0]['Nro_identificacion']);

                        /* Habilitar contenedor información del evento */
                        $('#num_registros').empty();
                        $('.contenedor_info_evento').removeClass('d-none');
                        $('#num_registros').append(data.length);
                        $.each(data, function(index, value){
                            llenar_informacion_evento(data, index, value);
                        });
                    }

                }
            });

        }
        else{
            var mensaje = "<i class='fas fa-info-circle'></i> <strong>Importante:</strong> Debe digitar un número de identifiación o un número de evento para realizar la consulta.";
            $('.resultado_validacion').removeClass('d-none');
            $('.resultado_validacion').addClass('alert-warning');
            $('#llenar_mensaje_validacion').append(mensaje);
            setTimeout(() => {
                $('.resultado_validacion').addClass('d-none');
                $('.resultado_validacion').removeClass('alert-warning');
                $('#llenar_mensaje_validacion').empty();
            }, 5000);
        }

        
    });  
    $('#Consulta_Eventos thead tr').clone(true).addClass('filters').appendTo('#Consulta_Eventos thead');
    function llenar_informacion_evento(response, index, value){
        $('#Consulta_Eventos').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            initComplete: function () {
                var api = this.api();
                    // For each column
                api.columns().eq(0).each(function (colIdx) {
                    // Set the header cell to contain the input element
                    var cell = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    var title = $(cell).text();
                    $(cell).html('<input type="text" placeholder="' + title + '" />');
                    $('input',$('.filters th').eq($(api.column(colIdx).header()).index())).off('keyup change')
                    .on('change', function (e) {
                        // Get the search value
                        $(this).attr('title', $(this).val());
                        var regexr = '({search})'; //$(this).parents('th').find('select').val();

                        var cursorPosition = this.selectionStart;
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

                        $(this).trigger('change');
                        $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
                    });
                });
            },
            "destroy": true,
            "data": response,
            "pageLength": 5,
            "order": [[5, 'desc']],
            "columns":[
                {"data":"ID_evento"},
                {"data":"Nombre_Cliente"},
                {"data":"Empresa"},
                {"data":"Nombre_evento"},
                {"data":"F_radicacion"},
                {"data":"F_registro"},
                {"data":"Nombre_proceso"},
                {"data":"Nombre_servicio"},
                {"data":"NO DATA"},
                {"data":"NO DATA"},
                {"data":"NO DATA"},
                {"data":"NO DATA"},
                {"data":"NO DATA"},
                {"data":"NO DATA"}
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
    
});
