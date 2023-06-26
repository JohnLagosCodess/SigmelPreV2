$(document).ready(function () {
            
    $('#form_consultar_evento').submit(function(e){
        e.preventDefault();
        /* Captura de variables de formulario de consulta de evento */
        var consultar_nro_identificacion = $('#consultar_nro_identificacion').val();
        var consultar_id_evento = $('#consultar_id_evento').val();
        let token = $('input[name=_token]').val();
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

                        var IrEvento = '';
                        var acciones = '';

                        for (let i = 0; i < data.length; i++) {
                            // Validación para mostrar el formulario de edición correspondiente al ID de evento.
                            if (data[i]['ID_evento'] != '') {
                                IrEvento = '<form id="form_editar_evento_'+data[i]["ID_evento"]+'" action="" method="POST">'+
                                '<input type="hidden" name="_token" value="'+token+'">'+
                                '<input class="btn text-info btn-sm" id="edit_evento_'+data[i]["ID_evento"]+'" type="submit" style="font-weight: bold;" value="'+data[i]["ID_evento"]+'">'+
                                '<input type="hidden" name="badera_buscador_evento" id="badera_buscador_evento" value="desdebuscador">'+
                                '<input type="hidden" name="newIdEvento" value="'+data[i]["ID_evento"]+'">'+
                                '</form>';
                                data[i]['consulta_evento'] = IrEvento;
                            }                
                            
                            // Validación para crear el modal del formulario de nuevo servicio
                            // acciones = '<a href="javascript:void(0);"><i class="fas fa-bezier-curve"></i></a>';
                            // data[i]['acciones'] = acciones;
                            
                            if(data[i]['Nombre_servicio'] == 'Determinación del Origen (DTO) ATEL' || data[i]['Nombre_servicio'] == 'Adición DX' || 
                            data[i]['Nombre_servicio'] == 'Calificación técnica' || data[i]['Nombre_servicio'] == 'Recalificación' || data[i]['Nombre_servicio'] == 'Revisión pensión'){
                                acciones = '<a href="javascript:void(0);" data-toggle="modal" data-target="#modalNuevoServicio_'+data[i]["ID_evento"]+'" id="btn_nuevo_servicio_'+data[i]["ID_evento"]+'" title="Agregar Nuevo Servicio"><i class="fa fa-puzzle-piece text-info"></i></a>'+
                                '<div class="modal fade" id="modalNuevoServicio_'+data[i]["ID_evento"]+'" tabindex="-1" aria-hidden="true">\
                                    <div class="modal-dialog modal-lg">\
                                        <div class="modal-content">\
                                            <div class="modal-header bg-info">\
                                                <h4 class="modal-title"><i class="fa fa-puzzle-piece"></i> Nuevo servicio para el evento: '+data[i]['ID_evento']+'</h4>\
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                                    <span aria-hidden="true">&times;</span>\
                                                </button>\
                                            </div>\
                                            <div class="modal-body">\
                                                <div class="row">\
                                                    <div class="col-12">\
                                                        <form method="POST">\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Fecha de Radicación</label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="date" class="form-control" name="nueva_fecha_radicacion" id="nueva_fecha_radicacion" required>\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Proceso</label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="text" readonly class="form-control" name="nuevo_proceso" id="nuevo_proceso" value="'+data[i]["Nombre_proceso"]+'">\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Servicio</label>\
                                                                <div class="col-sm-9">\
                                                                    <select class="nuevo_servicio_'+data[i]['ID_evento']+' custom-select" name="nuevo_servicio" id="nuevo_servicio_'+data[i]['ID_evento']+'" style="width:100%;" requierd></select>\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Fecha de acción</label>\
                                                                <div class="col-sm-9">\
                                                                    <input type="date" class="form-control" name="nueva_fecha_accion" id="nueva_fecha_accion_'+data[i]['ID_evento']+'">\
                                                                </div>\
                                                            </div>\
                                                            <div class="form-group row">\
                                                                <label for="" class="col-sm-3 col-form-label">Acción</label>\
                                                                <div class="col-sm-9">\
                                                                    <select class="nuevo_servicio_'+data[i]['ID_evento']+' custom-select" name="nuevo_servicio" id="nuevo_servicio_'+data[i]['ID_evento']+'" style="width:100%;" requierd></select>\
                                                                </div>\
                                                            </div>\
                                                        </form>\
                                                    </div>\
                                                </div>\
                                            </div>\
                                            <div class="modal-footer">\
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>';
                                

                                data[i]['acciones'] = acciones; 
                            }
                        }                        

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
                    var cell_1 = $('.filters th').eq(
                        $(api.column(colIdx).header()).index()
                    );
                    
                    // console.log(cell_1[0].cellIndex);

                    if(cell_1[0].cellIndex != 13){

                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        
                        var title = $(cell).text();
                        
                        if (title !== 'Detalle') {
    
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
            "destroy": true,
            "data": response,
            "pageLength": 5,
            "order": [[5, 'desc']],
            "columns":[
                {"data":"consulta_evento"},
                {"data":"Nombre_Cliente"},
                {"data":"Empresa"},
                {"data":"Nombre_evento"},
                {"data":"F_radicacion"},
                {"data":"F_registro"},
                {"data":"Nombre_proceso"},
                {"data":"Nombre_servicio"},
                {"data":"Nombre_estado"},
                {"data":"Resultado"},
                {"data":"F_accion"},
                {"data":"F_dictamen"},
                {"data":"F_notificacion"},
                {"data":"acciones"}
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

    /* Asignar ruta del formulario de edicion de evento antes de dar clic en el botón para ir al Evento */
    $(document).on('mouseover',"input[id^='edit_evento_']", function(){
        let url_editar_evento = $('#action_evento_consultar').val();
        $("form[id^='form_editar_evento_']").attr("action", url_editar_evento);    
    });

    $(document).on('click', "a[id^='btn_nuevo_servicio_']", function(){
        /* INICIALIZACIÓN DEL SELECT2 DE LISTADO PROCESO */
        $("select[id^='nuevo_servicio_']").select2({
            placeholder: "Seleccione una opción",
            allowClear: false
        });

        /* SETEO DE LA FECHA ACTUAL PARA EL CAMPO DE FECHA DE ACCIÓN */
        var fecha = new Date();
        $("input[id^='nueva_fecha_accion_']").val(fecha.toJSON().slice(0,10));
    });


    
});


